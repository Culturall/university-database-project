<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DB-project @ @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/app.css') }}" />
    @isset($css_files)
        @foreach ($css_files as $css_file)
            <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/'.$css_file.'.css')}}" />
        @endforeach
    @endisset
    <script src="{{asset('/js/bootstrap.js')}}"></script>
</head>
<body>
    @component('components.navbar', ['route' => $route])
    @endcomponent

    <div class="container">
        @yield('content')
    </div>

    @component('components.footer')
    @endcomponent
</body>
</html>