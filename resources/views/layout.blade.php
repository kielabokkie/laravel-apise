<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Apise{{ config('app.name') ? ' - ' . config('app.name') : '' }}</title>
    <link href="{{ asset(mix('tailwind.css', 'vendor/kielabokkie/laravel-apise')) }}" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div id="apise" class="container mx-auto my-4" v-cloak>
        <div class="flex align-items-center p-4 border-b">
            <span class="text-3xl">Apise Logs</span>
        </div>
        <div id="app">
            <logs></logs>
        </div>
    </div>
    <script src="{{ asset(mix('main.js', 'vendor/kielabokkie/laravel-apise')) }}"></script>
</body>
</html>
