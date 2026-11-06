<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Sansetya Florist')</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Poppins:wght@400;500;600&display=swap">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  @include('layouts.header')
  <main>@yield('content')</main>
  @include('layouts.footer')
</body>
</html>