#Site Archiver

A way to pull down HTML, CSS, JavaScript, and images from a website.

Useful for archiving CMSes that you don't have the capacity to maintain anymore and no longer need to be updated.

Built using the one-and-only [Laravel](http://laravel.com).

###How to use

1. Install (see below)
2. Option A: command line
	1. Using Artisan (Laravel's CLI), fire off the 'archive' command: `php artisan archive http://root-website-url.com`. Optionally, you can add a replacement url which just swaps out all occurences of the url. This is convenient if you want to host the site at a different url.
	2. The site will be saved in `storage/output/site-url`. After all files have been downloaded, a ZIP archive will be saved in `storage/archives/processId.zip`.
3. Option B: web interface
	1. Load the site in a web-browser. The root url is a pretty self-explanatory form, with an input for an url and a button.
	2. Once the archive has started (this will take some time depending on the overall size of the site) information about which urls have been loaded/archived will appear, as well as the last image downloaded.
	3. After the archive has completed, a ZIP will be downloaded through your browser.

If you like, use `redis-cli monitor` to keep an eye on your queued jobs. There will be a *lot* of them.

<b><span style="color:red">Word of warning</span></b>

**This app is only for use on sites you control!**

Site Archiver looks for every link it can find at the provided URL that contain the root URL. i.e., if you sic it on `http://example.com`, it will find every anchor tag, reference link, image source, javascript, stylesheet, etc. it can that contains `http://example.com`. It will then go to each of *those* resources, and, if it finds more links it hasn't yet added to the download queue, it will add those as well.

If you point this at `https://google.com`, I have no idea what will happen, but I imagine it will wind up looking like some kind of DDoS attack and get you in trouble. Seriously, only use this for your own stuff.

###Troubleshooting

Because the app uses Redis to queue up the tasks, it will try to re-start queued processes if there are failures. Keep an eye on the queue listener process (see below) for any error messages, or keep checking `storage/logs/laravel.log`.

If you wind up stuck with a whole bunch of queued jobs that never complete, you can stop the `queue:listen` process and flush your Redis queue with `redis-cli flushall`.

###System Requirements

[Composer](https://getcomposer.org) - PHP package manager

[Redis](http://redis.io) - Memory store. Good for queues and caches

[PHP 7](http://php.net/manual/en/migration70.new-features.php) - The language all the internet adores

If you're on a Mac, I recommend installing PHP and Redis via [Home Brew](http://brew.sh)

###Installation

```
git clone https://github.com/carterfort/site-archiver
cd site-archiver
```

####Install Dependencies
```
composer install
npm install
```

####Set up environment variables

```
cp .env.example .env
php artisan key:generate
```

####Start up your listeners and Socket.io

This app uses Socket.io and a Redis server for event monitoring. To start, you'll need two separate processes that you'll keep running while you're using the app.

`node socket.js` and `php artisan queue:listen`

Both of these have to be *running* for the app to function properly.