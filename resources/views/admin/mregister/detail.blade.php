@extends('layouts.back-app')
@section('style')
<style>
    .card .card-body p{
        font-weight:bold;
    }
</style>
@endsection
@section('breadcrumb')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h1>candidate List</h1> --}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{route('admin.election.index')}}">Home</a><</li>
                        <li class="breadcrumb-item active"><a href="{{route('admin.register.index')}}">Member List</a></li>
                        <li class="breadcrumb-item active">Member Detail</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-red card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                @if($member->profile)
                                <img class="profile-user-img img-fluid img-circle" src="{{ url($member->profile) }}"
                                alt="User profile picture">
                                @else
                                <img class="profile-user-img img-fluid img-circle" src="{{ url('images/user.png') }}"
                                        alt="User profile picture">
                                @endif
                            </div>

                            <h3 class="profile-username text-center">{{ $member->name }}</h3>

                            {{-- <p class="text-muted text-center">{{$member->refer_code}}</p> --}}
                            <br>
                            <ul class="list-group list-group-unbordered mb-3">

                                <li class="list-group-item">
                                    <b>Phone Number :</b>
                                    <p>{{ $member->phone_number }}</p>
                                </li>
                                <li class="list-group-item">
                                    <b>Email :</b>
                                    <p>{{ $member->email }}</p>
                                </li>
                            </ul>
                            <a href="{{route('admin.register.index')}}" class="btn btn-success btn-block"><b><i
                                        class="fas fa-reply-all"></i> Go Back</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card card-red card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><b>{{ $member->name }}</b> @lang('admin.information')</h3>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p>?????????????????????????????????????????????????????? / ????????????????????????????????????????????????????????????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {{ $member->nrc }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Customs Reference Code (??????????????????????????????????????????????????????)</p>
                                </div>
                                <div class="col-md-6">
                                    {{ $member->refer_code }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>????????????????????????????????????????????? ?????????????????????????????????????????????????????????????????????????????????????????????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {{ $member->complete_training_no }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>CVA/WTO Valuation ??????????????????????????????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {{ $member->valuation_training_no }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>AHTN ??????????????????????????????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {!! $member->AHTN_training_no !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>??????????????????????????????????????? (????????????/????????????????????????/ ??????????????????????????????????????????????????????)</p>
                                </div>
                                <div class="col-md-6">
                                    {!! $member->graduation !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>?????????????????????????????????????????????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {!! $member->address !!}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {!! $member->officeName !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>???????????????????????????????????????????????????????????????????????? ??????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {!! $member->office_startDate !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>?????????????????????????????? ???????????? ?????????????????????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {!! $member->officePhone !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>???????????? ?????????????????????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {!! $member->officeFax !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>???????????? ????????????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {!! $member->officeEmail !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>??????????????????????????????????????????????????????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {!! $member->yellowCard !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>????????????????????????????????????????????????????????????????????????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {!! $member->pinkCard !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>????????????????????????????????????????????????????????????</p>
                                </div>
                                <div class="col-md-6">
                                    {!! $member->officeAddress !!}
                                </div>
                            </div>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
