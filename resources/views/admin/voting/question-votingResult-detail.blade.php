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
          <li class="breadcrumb-item active">Voting Information</li>
          <li class="breadcrumb-item active"><a href="{{route('admin.election.voting-result',$election->id)}}">Answer Voting Result</a></li>
            <li class="breadcrumb-item active">Detail</li>
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
                @if($voting_detail->image)
                <img class="profile-user-img img-fluid img-circle"
                        src="{{url($voting_detail->image)}}"
                        alt="Question image">            
                @endif
             </div>

             <h3 class="profile-username text-center">{!!$voting_detail->ques!!}</h3>
             <hr>
             <a href="{{route('admin.election.voting-result',$election->id)}}" class="btn btn-success btn-block"><b><i class="fas fa-reply-all"></i> Go Back</b></a>
           </div>
           <!-- /.card-body -->
         </div>
         <!-- /.card -->
       </div>
       <!-- /.col -->
       <div class="col-md-9">
         <div class="card">
           <div class="card-header">
               <h3 class="card-title"> အသေးစိတ် ရလဒ်များ</h3>
           </div><!-- /.card-header -->
           <div class="card-body">
               <div class="row">
                   <div class="col-4 text-center">
                    <p><b>VoterID</b></p>
                   </div>
                   <div class="col-4 text-center">
                    <p><b>Voter Vote</b></p>
                   </div>
                   <div class="col-4 text-center">
                    <p><b>Vote Date</b></p>
                   </div>
               </div>
               <div class="row">
                @if(count($voter_id) > 0)
                    @foreach($voter_id as $id)
                    <div class="col-4 text-center">
                        <p>{{$id->voter_id}}</p>
                    </div>
                    <div class="col-4 text-center">
                        <p>{{$id->ans_flag == 1 ? 'Yes' : 'No'}}</p>
                    </div>
                    <div class="col-4 text-center">
                        <p>{{$id->created_at}}</p>
                    </div>
                    @endforeach
                @endif
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
