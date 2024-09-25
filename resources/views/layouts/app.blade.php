<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @stack('meta')


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    {{-- <script src="{{ asset('js') }}/jquery.min.js"></script> --}}

    {{-- <script src="{{ mix('js/network.js') }}"></script> --}}

    <script async="true" type="application/javascript" src="https://kit.fontawesome.com/3ee5214549.js" crossorigin="anonymous"></script>

    <script src="{{ mix('js/app.js') }}"></script>

    <script>
        var baseurl = "{{ URL::to('/') }}";
        </script>

</head>
<body style="background-color: #1d1d20;">
    <div>
        <div id="app">
            <div id="navigation">
                <navbar :current_user="{{ json_encode(auth()->user()) }}"></navbar>
            </div>
            <main style="margin-top: 80px; color: white">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('js')
    <script>
        $(function(){
            new Vue({
                el: '#navigation',
                props: {
                    current_user: {},
                },
            });
        });
    </script>
</body>
</html>
