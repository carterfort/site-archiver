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
    protected $replacementUrl;

    protected $resources = [];
    protected $urls = [];

    public function __construct($url, $processId, $replacementUrl = false)
    {
        //
        $this->url = $url;
        $this->processId = $processId;
        $this->replacementUrl = $replacementUrl;
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

    protected function parseUrl($url, $recursing = false)
    {
        try {
            $contents = file_get_contents($url); 
        } catch(\Exception $e){
            return;
        }

        event(new ResourceWasLoaded($url));

        $bareUrl = preg_replace('~(http|ftp|https)://~', "", $this->url);
        $pattern = "!".$bareUrl."([\w.,@?^=%&:/~+#-]*[\w@?^=%&/~+#-])?!u";

        preg_match_all($pattern, 
            $contents, 
            $links
        );

        $links = array_map(function($match) use ($bareUrl){
            $relativeLink = str_replace($bareUrl, "", $match);
            return rtrim($this->url.$relativeLink, "/");
        }, $links[0]);

        foreach ($links as $link)
        {
            $this->addLink($link);
        }

        $this->storeContentsForUrl($url, $contents);

        event(new UrlWasArchived($url, null));
    }

    protected function storeContentsForUrl($url, $contents)
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
            foreach ($pathComponents as $i => $component)
            {
                $currentPath .= "/{$component}";
                if ( ! is_dir($currentPath)){
                    if ( ! ($this->isFile($url) && ($i + 1 == count($pathComponents)))){
                        mkdir($currentPath, 0777, false);
                    }
                }
            }
        }

        $storagePath = "{$currentPath}";
        if (! $this->isFile($url)){
            $storagePath .= '/index.html';
        }

        if ($this->replacementUrl){
            $contents = str_replace($this->url, $this->replacementUrl, $contents);
        }

        $bytes = file_put_contents($storagePath, $contents);
    }

    protected function isFile($url)
    {
        $pattern = '~.*(?:css|ico|jpg|png)~';
        preg_match($pattern, $url, $matches);

        return count($matches) > 0;
    }

    protected function addLink($href)
    {
        if (str_contains($href, $this->url) && $this->url != $href){
                if ( ! in_array($href, $this->urls))
                {
                    $this->urls[] = $href;
                    $this->parseUrl($href, true);
                }
            }
    }
}
