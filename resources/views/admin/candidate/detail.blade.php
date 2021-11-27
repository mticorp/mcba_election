 @extends('layouts.back-app')
 @section('breadcrumb')
 <section class="content-header">
     <div class="container-fluid">
       <div class="row mb-2">
         <div class="col-sm-6">
           {{-- <h1>candidate List</h1> --}}
         </div>
         <div class="col-sm-6">
           <ol class="breadcrumb float-sm-right">
           <li class="breadcrumb-item active"><a href="{{route('admin.dashboard',$election->id)}}">Dashobard</a></li>
           <li class="breadcrumb-item active"><a href="{{route('admin.candidate.index',$election->id)}}">Candidates List</a></li>
             <li class="breadcrumb-item active">Candidates Detail</li>
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
                @if($candidate->photo_url)
                <img class="profile-user-img img-fluid img-circle"
              src="{{url($candidate->photo_url)}}"
                     alt="User profile picture">
                    @else
                    <img class="profile-user-img img-fluid img-circle"
              src="{{url('images/user.png')}}"
                     alt="User profile picture">
                    @endif
              </div>

              <h3 class="profile-username text-center">{{$candidate->mname}}</h3>

              <p class="text-muted text-center">{{$candidate->current_job}}</p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>NRC No</b> <a class="float-right">{{$candidate->nrc_no}}</a>
                </li>
                <li class="list-group-item">
                  <b>DOB</b> <a class="float-right">{{ Carbon\Carbon::parse($candidate->dob)->format('d/m/Y') }}</a>
                </li>
                <li class="list-group-item">
                  <b>Phone Number</b> <a class="float-right">{{$candidate->phone_no}}</a>
                </li>
                <li class="list-group-item">
                  <b>Email</b> <a class="float-right">{{$candidate->email}}</a>
                </li>
                <li class="list-group-item">
                  <b>Gender</b> <a class="float-right">{{$candidate->gender}}</a>
                </li>
              </ul>
                <a href="{{ route('admin.candidate.index',$election->id) }}" class="btn btn-success btn-block"><b><i class="fas fa-reply-all"></i> Go Back</b></a>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card">
            <div class="card-header">
                <h3 class="card-title"><b>{{$candidate->mname}}</b> @lang('admin.information')</h3>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p>@lang('admin.candidate_no') - </p>
                    </div>
                    <div class="col-md-8">
                        <p>{{$candidate->candidate_no}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p>@lang('admin.company') - </p>
                    </div>
                    <div class="col-md-8">
                        <p>{{$candidate->company}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p>@lang('admin.position') - </p>
                    </div>
                    <div class="col-md-8">
                        <p>{{$candidate->position}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p>@lang('admin.company_start_date') - </p>
                    </div>
                    <div class="col-md-8">
                        <p>{{$candidate->company_start_date}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p>@lang('admin.no_of_employee') - </p>
                    </div>
                    <div class="col-md-8">
                        <p>{{$candidate->no_of_employee}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p>@lang('admin.company_email') - </p>
                    </div>
                    <div class="col-md-8">
                        <p>{{$candidate->company_email}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p>@lang('admin.company_phone_no') - </p>
                    </div>
                    <div class="col-md-8">
                        <p>{{$candidate->company_phone}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p>@lang('admin.company_address') - </p>
                    </div>
                    <div class="col-md-8">
                      {!! $candidate->company_address !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p>@lang('admin.address') - </p>
                    </div>
                    <div class="col-md-8">
                        {!! $candidate->address !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p>@lang('admin.experience') - </p>
                    </div>
                    <div class="col-md-8">
                        {!! $candidate->experience !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p>@lang('admin.biography') - </p>
                    </div>
                    <div class="col-md-8">
                        {!! $candidate->biography !!}
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
