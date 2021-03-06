@inject('request', 'Illuminate\Http\Request')
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>
    {{ $setting->fav_name ? $setting->fav_name : 'mmVote'}}
 
</title>
<link rel="icon" href={{ $setting->fav_icon ? url($setting->fav_icon) : asset('images/election_logo.png')  }} type="image/x-icon">

{{-- <link rel="icon" href="{{asset('images/election_logo.png')}}" type="image/x-icon"> --}}

<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Custom css -->
<link rel="stylesheet" href="{{asset('backend/css/custom.css')}}" defer>
<!-- custom css -->
<link rel="stylesheet" href="{{asset('css/custom.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">

<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{asset('backend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Theme style -->
<link rel="stylesheet" href="{{asset('backend/css/adminlte.min.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/plugins/datatables-select/css/select.bootstrap4.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- icheck bootstrap -->
<link rel="stylesheet" href="{{asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('backend/plugins/toastr/toastr.min.css') }}">
<!-- Select 2 -->
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
{{-- Bootstrap toggle --}}
<link href="{{asset('backend/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
<!-- summernote -->
<link rel="stylesheet" href="{{asset('backend/plugins/summernote/summernote-bs4.css')}}">
<!-- jquery-confirm -->
<link rel="stylesheet" href="{{asset('css/jquery-confirm.min.css')}}">
<!-- Jquery UI -->
<link rel="stylesheet" href="{{asset('backend/plugins/jquery-ui/jquery-ui.css')}}">

<link rel="stylesheet" href="{{asset('backend/css/datatable.css')}}">

<style>
    .brand-link {
        white-space: normal!important;
        position:sticky!important;
    }
    
    .brand-link .brand-image{
        float: none!important;     
    }

    .select2-container .select2-selection--single {
        height:calc(2.25rem + 2px)!important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {       
        top: 6px!important;       
    }

    .hidden {
        display: none;
    }
</style>
@yield('style')
