<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mud Maker</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
@routes
<div id="app">
    <header-app header="1"></header-app>
    <router-view></router-view>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script src='https://cdn.polyfill.io/v2/polyfill.min.js'></script>
</body>
</html>
