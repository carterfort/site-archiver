<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ArchiveComplete extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    protected $sessionId;
    public $directory;

    public function __construct($sessionId, $directory)
    {
        //
        $this->sessionId = $sessionId;
        $this->directory = $directory;
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
}
