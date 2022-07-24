<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta id="token" content="{{csrf_token()}}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="{{asset('./public/js/jquery-3.6.0.js')}}"></script>
    <script src="{{asset('./public/js/bootstrap.js')}}"></script>
    <link rel="stylesheet" href="{{asset('./public/css/bootstrap.css')}}">
    <style>
        body{height: 100vh}
    </style>
    @yield('script')
</head>
<body>
    @yield('contents')
</body>
</html>
