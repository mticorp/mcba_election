@inject('request', 'Illuminate\Http\Request')
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            @if (Auth::user()->type == 'admin')
            @if (($request->segment(2) == 'election' ? 'active' : '') || ($request->segment(2) == 'logo' ? 'active' : '') 
            || ($request->segment(2) == 'favicon' ? 'active' : '') || ($request->segment(2) == 'sms' ? 'active' : '') 
            || ($request->segment(2) == 'reminder' ? 'active' : '') || ($request->segment(2) == 'security' ? 'active' : '')
            || ($request->segment(3) == 'list' ? 'active' : '') || ($request->segment(3) == 'generate' ? 'active' : '') 
            || ($request->segment(2) == 'register' ? 'active' : '') || (request()->is('admin/user') ? 'active' : '') 
            || (request()->is('admin/company') ? 'active' : ''))
            @else
            <a href="{{ route('admin.election.index') }}" class="nav-link">Home</a>
            @endif
            @else
            @if ($request->segment(3) == 'list' ? 'active' : '')
            <a href="{{ route('generator.index') }}" class="nav-link">Home</a>
            @endif
            @endif

        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        @if (Auth::user()->type == 'admin')
        @if (($request->segment(2) == 'election' ? 'active' : '') || ($request->segment(2) == 'logo' ? 'active' : '') ||
        ($request->segment(2) == 'favicon' ? 'active' : '') || ($request->segment(2) == 'sms' ? 'active' : '') 
        || ($request->segment(2) == 'reminder' ? 'active' : '') || ($request->segment(2) == 'security' ? 'active' : '')
        || ($request->segment(3) == 'list' ? 'active' : '') || ($request->segment(3) == 'generate' ? 'active' : '') ||
        ($request->segment(2) == 'register' ? 'active' : '') || (request()->is('admin/user') ? 'active' : '') ||
        (request()->is('admin/company') ? 'active' : ''))
        @else
        <li class="nav-item dropdown">
            <a href="#" class="nav-link" data-toggle="dropdown">
                <i class="fas fa-th-large"></i> {{ $election->name }} {{ $election->position ? ($election->position) :
                '' }}
            </a>
            @if (count($elections) > 0)
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                @foreach ($elections as $election)
                <div class="dropdown-divider"></div>
                @if (\Request::route()->getName() === 'admin.dashboard' || \Request::route()->getName() ===
                'generator.vid-list' || \Request::route()->getName() === 'admin.question.index' ||
                \Request::route()->getName() === 'admin.candidate.index' || \Request::route()->getName() ===
                'admin.election.voting-record' || \Request::route()->getName() === 'admin.election.rejectvoting-record'
                || \Request::route()->getName() === 'admin.election.notvoted-record' || \Request::route()->getName() ===
                'admin.election.voting-result' || \Request::route()->getName() === 'admin.answer.index' ||
                \Request::route()->getName() === 'admin.lucky.index')
                <a href="{{ route(\Request::route()->getName(), ['election_id' => $election->id]) }}"
                    class="dropdown-item">
                    <i class="fas fa-angle-right"></i> {{ $election->name }}
                    <span class="float-right text-muted text-sm">{{ $election->position }}</span>
                </a>
                @else
                @if ($request->segment(2) == 'candidate')
                <a href="{{ route('admin.candidate.index', ['election_id' => $election->id]) }}" class="dropdown-item">
                    <i class="fas fa-angle-right"></i> {{ $election->name }}
                    <span class="float-right text-muted text-sm">{{ $election->position }}</span>
                </a>
                @else
                <a href="{{ route('admin.election.voting-result', ['election_id' => $election->id]) }}"
                    class="dropdown-item">
                    <i class="fas fa-angle-right"></i> {{ $election->name }}
                    <span class="float-right text-muted text-sm">{{ $election->position }}</span>
                </a>
                @endif
                @endif
                @endforeach
            </div>
            @else
            @endif
        </li>
        @endif
        @endif
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img src="{{ url(Auth::user()->photo) }}" class="user-image img-circle elevation-2" alt="User Image">
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-white">
                    <img src="{{ url(Auth::user()->photo) }}" class="img-circle elevation-2" alt="User Image">
                    <p>
                        {{ Auth::user()->name }}
                    </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer text-xl-center bg-danger">
                    <form action="{{ route('logout') }}" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-flat"><i class="fas fa-sign-out-alt"></i>
                            Logout</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- /.navbar -->