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
            <li class="breadcrumb-item active">Question List</li>
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
                        <div class="card-title">Question List</div>
                        <div class="card-tools">
                            @if ($election->status == 0)
                            <button type="button" name="create_record" id="create_record"
                            class="btn btn-success btn-flat"><i class="fas fa-plus"></i> Add New Question</button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="Questiontable" class="table table-bordered table-hover table-striped">
                            <thead>
                              <tr>
                                <th>NO</th>
                                <th>Number (mm)</th>
                                <th>Question</th>
                                <th with="20%">Action</th>
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

<div class="modal fade" id="formModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Question</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label for="no_mm">Number (MM)</label>
                            <input type="text" name="no_mm" class="form-control" id="no_mm" placeholder="Enter Number (MM)..." value="{{ old('no_mm') }}" required>
                          </div>
                        </div>
                       
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ques">Question name</label>
                            <textarea class="textarea" placeholder="Place some text here" name="ques"  id="ques">{{ old('ques') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Select Image : </label> <br>
                                <input type="file" name="image" id="image" />                                    
                            </div>
                        </div>
                        <div class="col-md-6">
                            <span id="store_image"></span>
                        </div>
                    </div>

                    <input type="hidden" name="election_id" id="election_id" />
                    <input type="hidden" name="action" id="action" />
                    <input type="hidden" name="hidden_id" id="hidden_id" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success btn-flat" name="action_button"
                    id="action_button">
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="confirmModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title"><i class="fas fa-exclamation-circle"></i> Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 align="center" style="margin:0;">Are you sure you want to remove this data?</h6>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection
@section('javascript')
<script>
$(document).ready(function(){
    $('.textarea').summernote({
        enterHtml: '',
        height:'300px',
        toolbar: [
            // [groupName, [list of button]]            
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],            
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['height', ['height']]
        ]
    });
    
    var election_id = "{{$election->id}}";
    var election_status = "{{$election->status}}";
    
    // console.log(election_id);
    $('#Questiontable').DataTable({
        processing: true,
        language: {
         processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
        serverSide: true,
        ajax: {
            url: "{{url('/admin/question')}}/" + election_id,
        },
        columns: [
            {
                data: 'rownum',
                name: 'rownum' ,
            },
            {
                data: 'no',
                name: 'no'
            },
            {
                data: 'ques',
                name: 'ques',
                render:function(data, type, full, meta){
                    var body;
                    try {
                        body = document.implementation.createHTMLDocument().body;
                        //In chrome this avoids requesting images in the html
                    }
                    catch(e){
                        body = document.createElement("body");
                    }
                    body.innerHTML = data;
                    return $(body).text();
                }
            },

            {
                data: 'action',
                name: 'action',
                orderable: false,                
            }
        ],
        });

            $('#create_record').click(function() {
                $('form#sample_form').trigger("reset");
                $('#formModal .modal-title').text("Add New Question");
                $('#action_button').val("Add");
                $('#action').val("Add");
                $('#formModal').modal('show');
                $("#ques").summernote('code',' ');
                $("#store_image").text(" ");
            });

            $('#sample_form').on('submit', function(event) {
                event.preventDefault();
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
                if ($('#action').val() == 'Add') {
                    $("#election_id").val(election_id);
                    $.ajax({
                        url: "{{ route('admin.question.store') }}",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function(data) {
                            $.unblockUI();
                            if (data.errors) {
                                for (var count = 0; count < data.errors.length; count++) {
                                    toastr.error('Info - ' + data.errors[count])
                                }
                            }
                            if (data.success) {
                                $("#formModal").modal('hide');
                                toastr.success('Info - '+ data.success)
                                $('#sample_form')[0].reset();
                                $('#Questiontable').DataTable().ajax.reload();
                            }
                        }
                    })
                }

                if ($('#action').val() == "Edit") {
                    $.ajax({
                        url: "{{ route('admin.question.update') }}",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function(data) {
                            $.unblockUI();
                            if (data.errors) {
                                for (var count = 0; count < data.errors.length; count++) {
                                    toastr.error('Info - ' + data.errors[count])
                                }
                            }
                            if (data.success) {
                                $("#formModal").modal('hide');
                                toastr.success('Info - Successfully Edited!')
                                $('#sample_form')[0].reset();
                                $('#store_image').html('');
                                $('#Questiontable').DataTable().ajax.reload();
                            }
                        }
                    });
                }
            });

            $(document).on('click', '.edit', function() {
                var id = $(this).attr('id');               
                $('#form_result').html('');
                $.ajax({
                    url: "{{ url('/admin/question/edit/') }}/" + id,
                    dataType: "json",
                    success: function(html) {
                        $("#ques").summernote('code',html.data.ques);
                        $('#store_image').html("<img src={{ URL::to('/') }}" + html.data
                            .image + " width='70' class='img-thumbnail' />");
                        $('#store_image').append(
                            "<input type='hidden' name='hidden_image' value='" + html.data
                            .image + "' />");
                        $('#no_mm').val(html.data.no);
                        $('#hidden_id').val(html.data.id);
                        $('#election_id').val(html.data.election_id);
                        $('#formModal .modal-title').text("Edit Question");
                        $('#action_button').val("Edit");
                        $('#action').val("Edit");
                        $('#formModal').modal('show');
                    }
                })
            });

            var Question_id;

            $(document).on('click', '.delete', function() {
                Question_id = $(this).attr('id');
                $('#confirmModal').modal('show');
            });

            $('#ok_button').click(function() {
                $.ajax({
                    url: "{{ url('/admin/question/destroy/') }}/" + Question_id,
                    beforeSend: function() {
                        $('#ok_button').text('Deleting...');
                    },
                    success: function(data) {
                        setTimeout(function() {
                            $('#confirmModal').modal('hide');
                            $('#ok_button').text('OK');
                            toastr.success('Info - Successfully Deleted!')
                            $('#Questiontable').DataTable().ajax.reload();
                        }, 2000);
                    }
                })
            });
    })
</script>
@endsection
