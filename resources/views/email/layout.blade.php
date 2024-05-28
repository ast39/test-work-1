<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="ru">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
</head>

<body style="box-sizing: border-box; font-size: 16px; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; line-height: 1.5em; color: #333; margin: 0 20px; padding: 0;">
<div style="max-width: 800px; width: 100%; margin: 0 auto;">
    <div style="text-align: center; padding: 20px;" align="center">
        {{ env('APP_NAME') }}
    </div>

    <div style="border-radius: 20px; padding: 20px; background-color: #f8f8f8;">
        @yield('content')
    </div>
</div>
</body>

</html>
