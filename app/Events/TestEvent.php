<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message = 'Hello from backend!')
    {
        $this->message = $message;

        // 📝 Log when event is constructed
        Log::info('📣 TestEvent constructed', ['message' => $this->message]);
    }

    // Public channel
    public function broadcastOn()
    {
        Log::info('📡 Broadcasting TestEvent on channel: test-channel');
        return new Channel('test-channel');
    }

    // Event name
    public function broadcastAs()
    {
        Log::info('🗣️ Broadcasting as event name: TestEvent');
        return 'TestEvent';
    }
}
