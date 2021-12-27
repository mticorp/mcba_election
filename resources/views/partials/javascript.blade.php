<script src="{{asset('js/jquery-3.3.1.slim.min.js')}}"></script>
<script src="{{asset('js/jquery-3-1-1.min.js')}}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap-4-3-1.min.js')}}"></script>

<script src="{{asset('bootstrap/js/bootstrap.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('bootstrap/js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
<script src="{{asset('bootstrap/js/bootstrap.bundle.js')}}" type="text/javascript"></script>

<script src="{{asset('js/jquery-confirm.min.js')}}"></script>
<script>
    window._token = '{{ csrf_token() }}';
//    history.pushState(null, null, location.href);
//    window.onpopstate = function() {
//        history.go(1);
//    }
</script>

@yield('javascript')
