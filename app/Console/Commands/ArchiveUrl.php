<?php

namespace App\Console\Commands;

use App\Jobs\ProcessUrl;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ArchiveUrl extends Command
{

    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store static HTML pages for the provided URL';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dispatch(new ProcessUrl($this->argument('url'), null));
        $this->info("URL processing. Thank you :)");
    }
}
