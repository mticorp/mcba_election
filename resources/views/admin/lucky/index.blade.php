@extends('layouts.back-app')
@section('breadcrumb')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          {{-- <h1>Question List</h1> --}}
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active"><a href="{{route('admin.dashboard',$election->id)}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Lucky Draw List</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-red card-outline">
                    <div class="card-header">
                        <div class="card-title">Lucky Draw List</div>
                        <div class="card-tools">
                        <a href="{{route('admin.lucky.export',$election->id)}}" id="btnExport"
                            class="btn btn-success btn-flat"><i class="fas fa-download"></i> Download Excel</a>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="Luckytable" class="table table-bordered table-hover table-striped">
                            <thead>
                              <tr>
                                <th>NO</th>
                                <th>Lucky Draw Code</th>
                                <th>Name</th>
                                <th>Phone</th>
                              </tr>
                            </thead>
                            <tbody>

                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
$(document).ready(function(){
    var election_id = {{$election->id}};
    $('#Luckytable').DataTable({
        processing: true,
        //     language: {
        // processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
        serverSide: true,
        ajax: {
            url: "{{url('/admin/lucky-draw')}}/" + election_id,
        },
        columns: [
            {
                data: 'rownum',
                name: 'rownum' ,
            },
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'name',
                name: 'name',
            },
            {
                data: 'phone',
                name: 'phone',
            }
        ],
        });
    })
</script>
@endsection
