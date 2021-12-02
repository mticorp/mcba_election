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
          <li class="breadcrumb-item active"><a href="{{route('admin.election.index')}}">Home</a></li>
            <li class="breadcrumb-item active">Logo List</li>
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
                        <div class="card-title">Logo List</div>
                        <div class="card-tools">
                            <button type="button" name="create_record" id="create_record"
                            class="btn btn-success btn-sm btn-flat"><i class="fas fa-plus"></i> Add New Logo</button>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="companytable" class="table table-border">
                            <thead>
                              <tr>
                                <th>NO</th>
                                <th> Logo</th>
                                <th width="15%">&nbsp; Action</th>
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
                <h4 class="modal-title">Add New Logo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                      <div class="row">
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image">Select Logo Image : </label>
                                <input type="file" name="image" id="image" class="mb-3" />
                                    <span id="store_image"></span>
                            </div>
                        </div>
                    </div>

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
    $('#companytable').DataTable({
        processing: true,
            language: {
        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
        serverSide: true,
        ajax: {
            url: "{{ route('admin.logo.index') }}",
        },
        columns: [
            {
                data: 'rownum',
                name: 'rownum' ,
            },
            
            {
                data: 'logo',
                name: 'logo',
                render: function(data, type, full, meta) {
                    return "<img src={{ URL::to('/') }}" + data +
                        " width='70' class='img-thumbnail' />";
                },
                orderable: false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false
            }
        ],
        });

        $('#create_record').click(function() {
                $('#formModal .modal-title').text("Add New Logo");
                $('#action_button').val("Add");
                $('#action').val("Add");
                $('#formModal').modal('show');
                $("#store_image").text(" ");
            });

            $('#sample_form').on('submit', function(event) {
                event.preventDefault();
                if ($('#action').val() == 'Add') {
                    $.ajax({
                        url: "{{ route('admin.logo.store') }}",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function(data) {
                            if (data.errors) {
                                for (var count = 0; count < data.errors.length; count++) {
                                    toastr.error('Info - ' + data.errors[count])
                                }
                            }
                            if (data.success) {
                                $("#formModal").modal('hide');
                                toastr.success('Info - '+ data.success)
                                $('#sample_form')[0].reset();
                                $('#companytable').DataTable().ajax.reload();
                            }
                        }
                    })
                }

                if ($('#action').val() == "Edit") {
                    $.ajax({
                        url: "{{ route('admin.logo.update') }}",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function(data) {
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
                                $('#companytable').DataTable().ajax.reload();
                            }
                        }
                    });
                }
            });

            $(document).on('click', '.edit', function() {
                var id = $(this).attr('id');
                //   console.log(id);
                //   return false;
                $('#form_result').html('');
                $.ajax({
                    url: "{{ url('/admin/logo/edit/') }}/" + id,
                    dataType: "json",
                    success: function(html) {
                        //$('#company_name').val(html.data.company_name);
                        $('#store_image').html("<img src={{ URL::to('/') }}" + html.data
                            .logo + " width='70' class='img-thumbnail' />");
                        $('#store_image').append(
                            "<input type='hidden' name='hidden_image' value='" + html.data
                            .company_logo + "' />");
                        $('#hidden_id').val(html.data.id);
                        $('#formModal .modal-title').text("Edit Logo");
                        $('#action_button').val("Edit");
                        $('#action').val("Edit");
                        $('#formModal').modal('show');
                    }
                })
            });

            var company_id;

            $(document).on('click', '.delete', function() {
                company_id = $(this).attr('id');
                $('#confirmModal').modal('show');
            });

            $('#ok_button').click(function() {
                $.ajax({
                    url: "{{ url('/admin/logo/destroy/') }}/" + company_id,
                    beforeSend: function() {
                        $('#ok_button').text('Deleting...');
                    },
                    success: function(data) {
                        setTimeout(function() {
                            $('#confirmModal').modal('hide');
                            $('#ok_button').text('OK');
                            toastr.success('Info - Successfully Deleted!')
                            $('#companytable').DataTable().ajax.reload();
                        }, 2000);
                    }
                })
            });
    })
</script>
@endsection
