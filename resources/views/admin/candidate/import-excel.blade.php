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
          <li class="breadcrumb-item active"><a href="{{route('admin.dashboard',$election->id)}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Candidate Import Execel</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-md-8">
                <div class="card card-red card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Candidate Import Execel
                        </h3>
                        <div class="card-tools">

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-md-center">
                            <div class="col-md-10">
                                <form id="addExcel">
                                    @csrf
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input type="file" name="file" class="custom-file-input" id="customFile">
                                            <label class="custom-file-label" for="customFile">Choose Excel file</label>
                                            <input type="hidden" name="election_id" value="{{$election->id}}">
                                            </div>
                                    </div>
                                    <div class="form-group text-right">
                                        <a href="{{route('candidate-excel-export')}}" class="btn btn-flat btn-info float-left"><i class="fa fa-download" aria-hidden="true"></i> Download Excel Template</a>
                                        <button type="submit" class="btn btn-flat btn-success"><i class="fas fa-upload"></i> Import</button>
                                        <a href="{{route('admin.candidate.index',$election->id)}}" type="button" class="btn btn-flat btn-danger"><i class="fas fa-reply-all"></i> Candidate List</a>
                                    </div>
                                </form>
                            </div>
                        </div>
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
        bsCustomFileInput.init();
        jQuery('form#addExcel').on('submit', function(e) {
            e.preventDefault();
            $.blockUI({
                css: {
                    backgroundColor:'transparent',
                    top: '0px',
                    left: '0px',
                    width: $(document).width(),
                    height: $(document).height(),
                    padding: '20%',
                },
                baseZ: 2000,
                message: '<img src="{{ url("images/loader.gif") }}" width="150" />',
            });
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            var formData = new FormData($('form#addExcel')[0]);
            $.ajax({
            type: 'POST',
            dataType: "json",
            url: "{{ route('admin.candidate.excel-import') }}",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                console.log(data);
                $.unblockUI();
                if (data.validator) {
                    $('form#addExcel').trigger("reset");
                    $.each(data.validator.validator.customMessages,function(i,v){
                        toastr.error('Info - ' + v)
                    })
                }else if(data.errors){
                    $('form#addExcel').trigger("reset");
                    toastr.error('Info - ' + data.errors)
                } else if (data.success) {
                    $('form#addExcel').trigger("reset");
                    toastr.success('Info - ' + data.success)
                }
            },
            error: function(response) {
                $.unblockUI();
                toastr.error('Info - Candidate No, Name and NRC cannot be Empty.')
            },
            });
        })
    })
</script>
@endsection
