<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessUrl extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $url;
    protected $processId;

    protected $resources = [];
    protected $urls = [];

    public function __construct($url, $processId)
    {
        //
        $this->url = $url;
        $this->processId = $processId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $rootHtml = file_get_contents($this->url);

        $crawler = new Crawler($rootHtml);

        $links = $crawler->filter('a');
        foreach ($links as $link)
        {
            $href = $link->getAttribute('href');
            if (str_contains($href, $this->url) && $this->url != $href){
                $this->urls[] = str_replace($this->url, '', $href);
            }

            //1. Don't follow links we've already got
            //2. Don't 

        }

        dd($this->urls);

       die();
    }
}
