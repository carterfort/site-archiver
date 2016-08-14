<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ImageWasDownloaded extends Event implements ShouldBroadcast
{
    use SerializesModels;

    protected $image;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    protected $sessionId;

    public function __construct($image, $sessionId)
    {
        $this->image = $image;
        $this->sessionId = $sessionId;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
    return ['session-progress:'.$this->sessionId];
    }

    public function broadcastWith()
    {
        return ['image' => $this->image];
    }


}
