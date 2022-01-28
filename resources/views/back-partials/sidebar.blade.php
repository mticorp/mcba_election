@inject('request', 'Illuminate\Http\Request')
<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-dark-danger">
    <!-- Brand Logo -->
    @if (Auth::user()->type == 'admin')
    @if (
    (request()->is('admin/election') ? 'active' : '')
    || ($request->segment(2) == 'logo' ? 'active' : '')
    ||($request->segment(2) == 'favicon' ? 'active' : '')
    || ($request->segment(2) == 'sms' ? 'active' : '')
    || ($request->segment(2) == 'announce' ? 'active' : '')
    || ($request->segment(2) == 'reminder' ? 'active' : '')
    || ($request->segment(2) == 'security' ? 'active' : '')
    ||(request()->is('admin/election/*') ? 'active' : '')
    || ($request->segment(3) == 'generate' ? 'active' : '')
    ||($request->segment(2) == 'vid' ? 'active' : '')
    || ($request->segment(2) == 'register' ? 'active' : '')
    ||(request()->is('admin/user') ? 'active' : '')
    || (request()->is('admin/company') ? 'active' : '')
    )
    <p class="brand-link navbar-dark">
        <img src="{{ $setting->logo_image ? url($setting->logo_image) : url('images/election_logo.png') }}" alt="Company Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ $setting->logo_name ? $setting->logo_name : 'mmVote' }}</span>
    </p>
    @else
    <p class="brand-link navbar-dark text-center">
        <img src="{{ $setting->logo_image ? url($setting->logo_image) : url('images/election_logo.png') }}" alt="Company Logo"
        class="brand-image img-circle elevation-3" style="opacity: .8"><br>
        <span class="brand-text font-weight-light">{{ $election->company_name ? $election->company_name : 'mmVote' }}</span>

    </p>
    @endif
    @elseif(Auth::user()->type == 'generator')
    <p class="brand-link navbar-dark text-center">
        <img src="{{ url('images/election_logo.png') }}" alt="Company Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">mmVote</span>
    </p>
    @endif

    <!-- Sidebar -->
    <div class="sidebar mt-0">
        @if (Auth::user()->type == 'admin')
        @if ((request()->is('admin/election') ? 'active' : '') || ($request->segment(2) == 'logo' ? 'active' : '') ||
        ($request->segment(2) == 'favicon' ? 'active' : '') || ($request->segment(2) == 'sms' ? 'active' : '') || 
        ($request->segment(2) == 'announce' ? 'active' : '') ||
        ($request->segment(2) == 'reminder' ? 'active' : '') || ($request->segment(2) == 'security' ? 'active' : '')
        || (request()->is('admin/election/*') ? 'active' : '') || ($request->segment(2) == 'vid' ? 'active' : '') ||
        ($request->segment(3) == 'generate' ? 'active' : '') || ($request->segment(2) == 'register' ? 'active' : '') ||
        (request()->is('admin/user') ? 'active' : '') || (request()->is('admin/company') ? 'active' : ''))
        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview"
                role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.election.index') }}"
                        class="nav-link {{ $request->segment(2) == 'election' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                            {{-- <span class="badge badge-info right">2</span> --}}
                        </p>
                    </a>
                </li>
                <li class="nav-header">Tools</li>
                <li class="nav-item">
                    <a href="{{ route('admin.company.index') }}"
                        class="nav-link {{ $request->segment(2) == 'company' ? 'active' : '' }}">
                        <i class="nav-icon far fa-building"></i>
                        <p>
                            Company
                        </p>
                    </a>
                </li>         
                
                <li class="nav-item">
                    <a href="{{ route('admin.register.index') }}"
                        class="nav-link {{ $request->segment(2) == 'register' ? 'active' : '' }}">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Member Lists</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('generator.vid-list') }}"
                        class="nav-link {{ $request->segment(3) == 'list' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>
                            Voter ID List
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('generator.generate.vid') }}"
                        class="nav-link {{ $request->segment(3) == 'generate' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-upload"></i>
                        <p>
                            Voter ID Generate
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.user.index') }}"
                        class="nav-link {{ $request->segment(2) == 'user' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            UserManagement
                        </p>
                    </a>
                </li>


                <li
                    class="nav-item has-treeview {{ $request->segment(2) == 'logo' ? 'menu-open' : '' }} || {{ $request->segment(2) == 'favicon' ? 'menu-open' : '' }} || {{ $request->segment(2) == 'sms' ? 'menu-open' : '' }} || {{ $request->segment(2) == 'announce' ? 'menu-open' : '' }} || {{ $request->segment(2) == 'reminder' ? 'menu-open' : '' }} || {{ $request->segment(2) == 'security' ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ $request->segment(2) == 'logo' ? 'active' : '' }} || {{ $request->segment(2) == 'favicon' ? 'active' : '' }} || {{ $request->segment(2) == 'sms' ? 'active' : '' }} || {{ $request->segment(2) == 'reminder' ? 'active' : '' }} || {{ $request->segment(2) == 'security' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Setting
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.logo.index') }}"
                                class="nav-link {{ $request->segment(2) == 'logo' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-blog"></i>
                                <p>
                                    Logo
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.favicon.index') }}"
                                class="nav-link {{ $request->segment(2) == 'favicon' ? 'active' : '' }}">
                                <i class="nav-icon fa fa-icons"></i>
                                <p>
                                    Favicon
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.member.sms.index') }}"
                                class="nav-link {{ $request->segment(2) == 'sms' && $request->segment(3) == 'member' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-sms"></i>
                                <p>
                                    Member SMS
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.member.announce.index') }}"
                                class="nav-link {{ $request->segment(2) == 'announce' && $request->segment(3) == 'member' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bell"></i>
                                <p>
                                    Member Announce
                                </p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.voter.announce.index') }}"
                                class="nav-link {{ $request->segment(2) == 'announce' && $request->segment(3) == 'voter' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bell"></i>
                                <p>
                                    Voter Announce
                                </p>
                            </a>
                        </li>
                        

                        <li class="nav-item">
                            <a href="{{ route('admin.sms.index') }}"
                                class="nav-link {{ $request->segment(2) == 'sms' && $request->segment(3) == 'voter' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-sms"></i>
                                <p>
                                    Voter SMS
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reminder.index') }}"
                                class="nav-link {{ $request->segment(2) == 'reminder' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-sms"></i>
                                <p>
                                    Reminder
                                </p>
                            </a>
                        </li>                        
                        <li class="nav-item">
                            <a href="{{ route('admin.security.index') }}"
                                class="nav-link {{ $request->segment(2) == 'security' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-lock"></i>
                                <p>
                                    Security
                                </p>
                            </a>
                        </li>     
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
        @else
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview"
                role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard', $election->id) }}"
                        class="nav-link {{ $request->segment(2) == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Dashboard
                            {{-- <span class="badge badge-info right">2</span> --}}
                        </p>
                    </a>
                </li>
                <li class="nav-header">Tools</li>

                <li class="nav-item has-treeview {{ $request->segment(2) == 'voting' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $request->segment(2) == 'voting' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-address-card"></i>
                        <p>
                            Voting Information
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('admin.election.voting-record', $election->id) }}"
                                class="nav-link {{ $request->segment(3) == 'votingrecord' ? 'active' : '' }}">
                                <i class="fas fa-check nav-icon"></i>
                                <p>Voting Record</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.election.rejectvoting-record', $election->id) }}"
                                class="nav-link {{ $request->segment(3) == 'rejectvotingrecord' ? 'active' : '' }}">
                                <i class="fas fa-ban nav-icon"></i>
                                <p>Reject Voting Record</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.election.notvoted-record', $election->id) }}"
                                class="nav-link {{ $request->segment(3) == 'notvotedrecord' ? 'active' : '' }}">
                                <i class="fas fa-times nav-icon"></i>
                                <p>Not Voted Record</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.election.voting-result', $election->id) }}"
                                class="nav-link {{ $request->segment(3) == 'votingresult' ? 'active' : '' }}">
                                <i class="fas fa-file-excel nav-icon"></i>
                                <p>Voting Result</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @if ($election->candidate_flag == 1)
                <li class="nav-item">
                    <a href="{{ route('admin.candidate.index', $election->id) }}"
                        class="nav-link {{ $request->segment(2) == 'candidate' ? 'active' : '' }}">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Candidate Lists</p>
                    </a>
                </li>
                @endif


                @if ($election->ques_flag == 1)
                <li class="nav-item">
                    <a href="{{ route('admin.question.index', $election->id) }}"
                        class="nav-link {{ $request->segment(2) == 'question' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-question-circle"></i>
                        <p>
                            Question List
                        </p>
                    </a>
                </li>
                @endif

                @if ($election->lucky_flag == 1)
                <li class="nav-item">
                    <a href="{{ route('admin.lucky.index', $election->id) }}"
                        class="nav-link {{ $request->segment(2) == 'lucky-draw' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p>
                            Lucky Draw List
                        </p>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
        @endif
        @elseif(Auth::user()->type == 'generator')
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview"
                role="menu" data-accordion="false">
                @if ($request->segment(3) == 'list')
                <li class="nav-item">
                    <a href="{{ route('generator.vid-list',$election->id) }}"
                        class="nav-link {{ $request->segment(3) == 'list' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>
                            Voter ID List
                        </p>
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a href="{{ route('generator.index') }}"
                        class="nav-link {{ request()->is('admin/index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('generator.generate.vid') }}"
                        class="nav-link {{ $request->segment(3) == 'generate' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-upload"></i>
                        <p>
                            Voter ID Generate
                        </p>
                    </a>
                </li>
                @endif

        </nav>
        @endif
    </div>
    <!-- /.sidebar -->
</aside>