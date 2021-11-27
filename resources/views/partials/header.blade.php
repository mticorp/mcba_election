@inject('request', 'Illuminate\Http\Request')
<nav class="navbar">
    <div class="text-white text-center w-100">
        @if((request()->is('/') ? 'active' : '') || ($request->segment(2) == 'register' ? 'active' : '') || ($request->segment(1) == 'select-Election' ? 'active' : '') || ($request->segment(1) == 'app' ? 'active' : '') || ($request->segment(1) == 'verify' ? 'active' : ''))
            <img src="{{url('images/election_logo.png')}}" alt="" class="header-img">
        @else
            @if($election->company_logo)
                <img src="{{url($election->company_logo)}}" alt="" class="header-img">
            @else
                <img src="{{url('images/election_logo.png')}}" alt="" class="header-img">
            @endif
        @endif
    </div>
</nav>
