@extends('layouts.back-app')

@section('content')
<!-- Main content -->
<div class="content pt-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <div class="card card-red card-outline">
              <div class="card-header">
                  <h5 class="card-title">Election List</h5>
                  <div class="card-tools">
                  </div>
              </div>
            <div class="card-body table-responsive">
              <table id="generatortable" class="table table-hover table-bordered">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Name</th>
                      <th>Position</th>
                      <th>Description</th>
                      <th>Candidate Title</th>
                      <th>Number Of Postion</th>
                      <th>Start Time</th>
                      <th>End Time</th>
                      <!-- <th>Created Date</th>
                      <th>Modified Date</th> -->
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($elections as $key => $election)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$election->name}}</td>
                      <!-- <td>
                        <a href="{{route('generator.vid-list',$election->id)}}" class="btn btn-flat btn-xs btn-info"><i class="fa fa-cogs"></i> Dashboard</a>
                      </td> -->
                      <td>{{$election->position}}</td>
                      <td>{{$election->description}}</td>
                      <td>{{$election->candidate_title}}</td>
                      <td>{{$election->no_of_position_en}}</td>
                      <td id="start_time">{{$election->start_time}}</td>
                      <td id="end_time">{{$election->end_time}}</td>
                      <td>
                        @if($election->status == 0)
                        <input data-id="{{$election->id}}" data-status="{{$election->status}}" disabled class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="START" data-off="STOP" {{ $election->status ? 'checked' : '0' }}>
                        @elseif($election->status == 1)
                        <input data-id="{{$election->id}}" data-status="{{$election->status}}" disabled class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="START" data-off="STOP" {{ $election->status ? 'checked' : '1' }}>
                        @endif
                      </td>
                      <td width="10%;">
                        <a href="{{route('generator.vid-list',$election->id)}}" class="btn btn-flat btn-xs btn-info"><i class="fa fa-list-ol"></i> VoterID List</a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
          </div><!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->

@endsection
@section('javascript')
<script>
  $(document).ready(function() {
    $("#generatortable").DataTable();
  })
</script>
@endsection
