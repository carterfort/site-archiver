<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Events\UrlWasArchived;
use App\Events\ResourceWasLoaded;
use Illuminate\Support\Facades\File;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
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
        $this->parseUrl($this->url);
        return ($this->urls);

    }

    protected function parseUrl($url)
    {
        event(new ResourceWasLoaded($url));
        $rootHtml = file_get_contents($url);

        $crawler = new Crawler($rootHtml);

        $links = $crawler->filter('a');
        $followLinks = [];

        foreach ($links as $link)
        {
            $href = $link->getAttribute('href');
            $this->addLink($href, $rootHtml, $followLinks);
        }

        foreach ($followLinks as $linkToFollow)
        {
            $this->parseUrl($linkToFollow);
        }

        $this->storeHtmlForUrl($url, $rootHtml);

        event(new UrlWasArchived($url, null));
    }

    protected function storeHtmlForUrl($url, $html)
    {
        if ( ! is_dir(storage_path('output')))
        {
            mkdir(storage_path('output'), 0755, false);
        }

        $url = str_replace($this->url."/", "", $url);
        $url = rtrim($url, "/");
        $pathComponents = explode("/", $url);

        $currentPath = storage_path('output');

        if ( $url != $this->url){
            foreach ($pathComponents as $component)
            {
                $currentPath .= "/{$component}";
                if ( ! is_dir($currentPath)){
                    mkdir($currentPath, 0777, false);
                }
            }
        }

        $storagePath = "{$currentPath}/index.html";
        $bytes = file_put_contents($storagePath, $html);
    }

    protected function addLink($href, $rootHtml, &$followLinks)
    {
        $pattern = '/(https?:\/\/.*\.(?:png|jpg))/';

        $matches = [];
        preg_match($pattern, $href, $matches);
        if (count($matches) > 0) return;

        if (str_contains($href, $this->url) && $this->url != $href){
                if ( ! in_array($href, $this->urls))
                {
                    $followLinks[] = $href;
                    $this->urls[] = $href;
                }
            }
    }
}
