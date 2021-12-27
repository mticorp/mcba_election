<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!-- Styles -->

    @include('back-partials.head')
</head>
<body id="app-body" style="background-color:#d2d6de;">
    {{--  --}}
    @include('partials.header')

    @yield('content')

    @include('back-partials.javascript')
    @include('partials.footer')
</body>
</html>
