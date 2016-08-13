<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="/css/style.css" />

    </head>
    <body>
        <div class="container">
            <div class="content">
            	@yield('main')
            </div>
        </div>

        <script src="/js/app.js"></script>

        @yield('scripts')
        
    </body>
</html>