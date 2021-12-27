    <!-- meta tag -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('images/mtsh-favico.ico')}}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- link -->
    <!-- <link rel="stylesheet" href="{{asset('css/app.css')}}"> -->

    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">

    <!-- jquery-confirm -->
    <link rel="stylesheet" href="{{asset('css/jquery-confirm.min.css')}}">

    <!-- custom css -->
    <link rel="stylesheet" href="{{asset('css/custom.css')}}" defer>
    
@yield('style')
