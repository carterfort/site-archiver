<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UrlWasArchived extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    
    public $url;

    protected $sessionId;

    public function __construct($url, $sessionId)
    {
        //
        $this->url = $url;
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
        return ['url' => $this->url];
    }
}
