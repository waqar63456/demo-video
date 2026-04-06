<?php
namespace App\Events;

use App\Models\SoraVideo;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class SoraVideoUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $video;

    public function __construct(SoraVideo $video)
    {
        $this->video = $video;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('sora-videos.' . $this->video->user_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->video->id,
            'status' => $this->video->status,
            'video_url' => $this->video->video_url,
            'progress' => $this->video->progress ?? 0,
            'prompt' => $this->video->prompt,
            'duration' => $this->video->duration,
            'resolution' => $this->video->resolution,
        ];
    }
}