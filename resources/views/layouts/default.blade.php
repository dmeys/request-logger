<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{asset(mix('bootstrap.css', 'vendor/request-logger'))}}" rel="stylesheet">
    <link href="{{asset(mix('app.css', 'vendor/request-logger'))}}" rel="stylesheet">
    <link href="{{asset(mix('jsonTree.css', 'vendor/request-logger'))}}" rel="stylesheet">
    @stack('styles')
    <title>Request Logs</title>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <main role="main" class="col-md-12 ml-sm-auto col-lg-12 px-4">
            @yield('content')
        </main>
    </div>
</div>
<script src="{{asset(mix('app.js', 'vendor/request-logger'))}}"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
@stack('scripts')
</body>
</html>
