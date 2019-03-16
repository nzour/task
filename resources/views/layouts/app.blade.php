<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href=" {{ URL::asset('css/libs/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/libs/font-awesome.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('css/styles.css') }}" />
    @yield('title')
    <title>Главная</title>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="{{ URL::asset('js/libs/jquery-3.0.0.min.js') }}"></script>
    <script src="{{ URL::asset('js/libs/bootstrap.bundle.js') }}"></script>
    <script src="{{ URL::asset('js/index.js') }}" type="module"></script>
</body>
</html>