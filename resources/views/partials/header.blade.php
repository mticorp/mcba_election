@inject('request', 'Illuminate\Http\Request')
<nav class="navbar">
    <div class="text-white text-center w-100">
        @if((request()->is('/') ? 'active' : '') || ($request->segment(2) == 'register' ? 'active' : '') || ($request->segment(1) == 'select-Election' ? 'active' : '') || ($request->segment(1) == 'app' ? 'active' : '') || ($request->segment(1) == 'verify' ? 'active' : ''))
            <img src="{{url('images/election_logo.png')}}" class="col-10" style="width: 300px; height: 80px; object-fit: cover" alt="" >
        
            @else
            @if($election->company_logo)
                <img src="{{url($election->company_logo)}}" alt="" style="width: 300px; height: 80px; object-fit: cover" class=" col-12">
            @else
            <img src="{{url('images/election_logo.png')}}" class="col-10" style="width: 300px; height: 80px; object-fit: cover" alt="">
        
            @endif
        @endif
    </div>
</nav>
