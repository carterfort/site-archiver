<?php

namespace App\Listeners;

use App\Events\ArchiveComplete;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ArchiveCompletedListener
{

    use DispatchesJobs;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ArchiveComplete  $event
     * @return void
     */
    public function handle(ArchiveComplete $event)
    {
        dd("here?");
        $this->dispatch(new CompressArchive($event->directory));
    }
}
