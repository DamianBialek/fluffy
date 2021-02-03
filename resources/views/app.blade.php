<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="{{\Illuminate\Support\Facades\URL::to("/")}}" />
    <title>Panel administratora - fluffy</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ url(mix('/css/app.css')) }}" rel="stylesheet">
    <link href="{{ url(mix('/css/style.css')) }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <router-view></router-view>
</div>
<script>
    window.Config = {
        base: '{{env("ASSET_URL")}}',
        baseUrl: '{{\Illuminate\Support\Facades\URL::to("/")}}'
    }
</script>
<script src="{{url(mix("/js/app.js"))}}"></script>
</body>
