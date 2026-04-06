<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateSoraVideoJob;
use App\Models\SoraVideo;
use Illuminate\Http\Request;

class SoraVideoController extends Controller
{
    public function generate(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'error' => 'Unauthenticated'
            ], 401);
        }
    
        $request->validate([
            'prompt' => 'required|string',
            'duration' => 'nullable|string',
            'resolution' => 'nullable|string',
            'image' => 'nullable|image|max:10240', // optional image, max 10MB
        ]);
    
        // Save uploaded image (if any)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('sora_images', 'public');
        }
    
        $video = SoraVideo::create([
            'user_id' => $user->id,
            'prompt' => $request->prompt,
            'duration' => $request->duration ?? '10',
            'resolution' => $request->resolution ?? '1080p',
            'status' => 'queued',
            'image_url' => $imagePath, // store image reference
        ]);
    
        GenerateSoraVideoJob::dispatch($video);
    
        return response()->json([
            'status' => true,
            'message' => 'Sora AI video generation started.',
            'video' => $video,
        ], 202);
    }
    
    public function index(Request $request)
    {
        // Check authenticated user
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'error' => 'Unauthenticated'
            ], 401);
        }

        $videos = SoraVideo::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'videos' => $videos,
        ]);
    }
}
