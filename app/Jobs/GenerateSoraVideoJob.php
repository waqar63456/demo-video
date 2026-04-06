<?php

namespace App\Jobs;

use App\Events\SoraVideoUpdated;
use App\Models\SoraVideo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateSoraVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    public $tries = 1000;
    public $backoff = 30;

    public function __construct(SoraVideo $video)
    {
        $this->video = $video;
    }

    public function handle(): void
    {
        Log::info("🎬 Starting Sora video job | ID: {$this->video->id}");

        // Reload the video to ensure we have latest data
        $this->video->refresh();

        // Update status to processing
        $this->video->update([
            'status' => 'processing',
            'progress' => 0 // Initialize progress
        ]);
        event(new SoraVideoUpdated($this->video));

        $validSizes = ['720x1280', '1280x720'];
        $validDurations = ['4', '8', '12'];

        $seconds = collect($validDurations)
            ->sortBy(fn($v) => abs((int)$v - (int)$this->video->duration))
            ->first();

        if (!in_array($this->video->resolution, $validSizes)) {
            $this->video->update(['status' => 'failed']);
            Log::error("❌ Invalid resolution '{$this->video->resolution}' for video ID {$this->video->id}");
            return;
        }

        $size = $this->video->resolution;

        Log::info("🧠 Generating Sora video | ID: {$this->video->id}", [
            'prompt' => $this->video->prompt,
            'seconds' => $seconds,
            'size' => $size,
        ]);

        // Step 1: Submit video
        if (!$this->video->openai_video_id) {
            try {
                $payload = [
                    'model' => 'sora-2',
                    'prompt' => $this->video->prompt,
                    'seconds' => $seconds,
                    'size' => $size,
                ];
                if ($this->video->image_path) {
                    $imageContent = base64_encode(Storage::disk('public')->get($this->video->image_path));
                    $payload['image'] = $imageContent; // Sora API may accept base64 image
                }
                

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ])->timeout(60)
                  ->post('https://api.openai.com/v1/videos', $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    $openaiVideoId = $data['id'] ?? null;
                    $this->video->update(['openai_video_id' => $openaiVideoId]);
                    Log::info("✅ Sora video submitted successfully | OpenAI ID: {$openaiVideoId}");
                } else {
                    Log::error("❌ Failed to submit video: " . $response->body());
                    $this->video->update(['status' => 'failed']);
                    return;
                }
            } catch (\Throwable $e) {
                Log::error("🔥 Error submitting Sora video: " . $e->getMessage());
                $this->video->update(['status' => 'failed']);
                return;
            }
        }

        // Step 2: Poll video status
        try {
            $openaiVideoId = $this->video->openai_video_id;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->timeout(60)
              ->get("https://api.openai.com/v1/videos/{$openaiVideoId}");

            if (!$response->successful()) {
                Log::error("⚠️ Failed to poll video status: " . $response->body());
                self::dispatch($this->video)->delay(now()->addSeconds(30));
                return;
            }

            $data = $response->json();
            $status = $data['status'] ?? null;
            $progress = $data['progress'] ?? 0;

            Log::info("📊 Video progress: {$progress}% | Status: {$status}");

            // Update progress in DB and broadcast
            $this->video->update(['progress' => $progress]);
            event(new SoraVideoUpdated($this->video));

            if ($status === 'completed') {
                // Fetch video content
                try {
                    $contentResponse = Http::withHeaders([
                        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    ])->timeout(120)
                      ->get("https://api.openai.com/v1/videos/{$openaiVideoId}/content");

                    if ($contentResponse->successful()) {
                        $filename = 'sora_videos/video_' . $this->video->id . '.mp4';
                        Storage::disk('public')->put($filename, $contentResponse->body());

                        $this->video->update([
                            'status' => 'completed',
                            'video_url' => basename($filename),
                            'progress' => 100,
                        ]);
                        event(new SoraVideoUpdated($this->video));

                        Log::info("🎉 Video completed and saved | File: " . basename($filename));
                    } else {
                        Log::warning("⚠️ Video completed but failed to download. Retrying...");
                        self::dispatch($this->video)->delay(now()->addSeconds(30));
                    }
                } catch (\Throwable $e) {
                    Log::error("🔥 Error fetching video content: " . $e->getMessage());
                    self::dispatch($this->video)->delay(now()->addSeconds(30));
                }
            } elseif ($status === 'failed') {
                $errorMsg = $data['error']['message'] ?? 'Unknown error';
                Log::error("❌ Sora video failed | {$errorMsg}");
                $this->video->update([
                    'status' => 'failed',
                    'progress' => 0
                ]);
                event(new SoraVideoUpdated($this->video));
            } else {
                Log::info("⏳ Sora video progress: {$progress}% | ID: {$this->video->id}");
                // Re-dispatch only if not completed
                self::dispatch($this->video)->delay(now()->addSeconds(30));
            }
        } catch (\Throwable $e) {
            Log::error("⚠️ Error polling Sora status: " . $e->getMessage());
            self::dispatch($this->video)->delay(now()->addSeconds(30));
        }
    }
}