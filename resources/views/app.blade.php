<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Comm.com</title>

    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    @routes
    @vite(['resources/js/app.js'])
    @inertiaHead
</head>
<body>
@inertia
</body>
</html>