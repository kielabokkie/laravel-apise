<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Apise{{ config('app.name') ? ' - ' . config('app.name') : '' }}</title>
</head>
<body>
    <div id="apise" v-cloak>
        <h1>Apise Logs</h1>

        @foreach($logs->toArray() as $log)
            <pre>@dump($log)</pre>
        @endforeach
    </div>
</body>
</html>
