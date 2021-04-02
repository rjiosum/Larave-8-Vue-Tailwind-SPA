<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('frontend/images/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('frontend/images/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('frontend/images/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('frontend/images/favicon/site.webmanifest')}}">
    <title>{{config('app.name')}}</title>
    <link rel="stylesheet" href="{{mix('frontend/css/app.css', 'frontend')}}">
</head>
<body class="bg-gray-100 font-body relative">
<div id="app">
    <App></App>
</div>
<script src="{{mix('frontend/js/app.js', 'frontend')}}"></script>
</body>
</html>
