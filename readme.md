#Site Archiver

A way to pull down HTML, CSS, JavaScript, and images from a website.

Useful for archiving CMSes that you don't have the capacity to maintain anymore and no longer need to be updated.

###How to use

1. Install (see below)
2. Option A: command line
	1. Using Artisan (Laravel's CLI), fire off the 'archive' command: `php artisan archive http://root-website-url.com`. Optionally, you can add a replacement url which just swaps out all occurences of the url. This is convenient if you want to host the site at a different url.
	2. The site will be saved in `storage/output/site-url`. After all files have been downloaded, a ZIP archive will be saved in `storage/archives/processId.zip`.
3. Option B: web interface
	1. Load the site in a web-browser. The root url is a pretty self-explanatory form, with an input for an url and a button.
	2. Once the archive has started (this will take some time depending on the overall size of the site) information about which urls have been loaded/archived will appear, as well as the last image downloaded.
	3. After the archive has completed, a ZIP will be downloaded through your browser.

After downloading everything, 

###System Requirements

[Composer](https://getcomposer.org)

[Redis](http://redis.io)

[PHP 7](http://php.net/manual/en/migration70.new-features.php)

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