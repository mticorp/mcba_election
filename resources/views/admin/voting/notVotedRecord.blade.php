@extends('layouts.back-app')
@section('breadcrumb')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          {{-- <h1>Company List</h1> --}}
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active"><a href="{{route('admin.dashboard',$election->id)}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Voting Information</li>
            <li class="breadcrumb-item active">Not Voted Record</li>
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
                        <h3 class="card-title">
                            Not Voted Records List
                        </h3>
                        <div class="card-tools">
                            <a id="btnExport" href="" class="btn btn-flat btn-success"><i class="fa fa-download" aria-hidden="true"></i> Download Excel </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="notvotingtable" border="2px" class="table table-bordered table-striped table-hover">
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Voter ID</th>                                
                              </tr>
                            </thead>
                            <tbody>
                                @php
                                $j=1;
                                @endphp
                                @foreach($no_voting_records as $key=>$value)
                                <tr>
                                    <td><strong>{{$j++}}</strong></td>
                                    <td><strong>{{$key}}</strong></td>                                    
                                </tr>
                                @endforeach
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
        $("#notvotingtable").DataTable();

        $("#btnExport").click(function(e){
        //getting values of current time for generating the file name
        var dt = new Date();
            var day = dt.getDate();
            var month = dt.getMonth() + 1;
            var year = dt.getFullYear();
            var hour = dt.getHours();
            var mins = dt.getMinutes();
            var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;
            //creating a temporary HTML link element (they support setting file names)
            var a = document.createElement('a');
            //getting data from our div that contains the HTML table
            var data_type = 'data:application/vnd.ms-excel';
            var table_div = document.getElementById('notvotingtable');
            var table_html = table_div.outerHTML.replace(/ /g, '%20');
            a.href = data_type + ', ' + table_html;
            //setting the file name
            a.download = 'Not_Voted_Record_' + postfix + '.xls';
            //triggering the function
            a.click();
            //just in case, prevent default behaviour
            e.preventDefault();
        })
    })
</script>
@endsection
