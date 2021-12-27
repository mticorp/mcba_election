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
            <li class="breadcrumb-item active">UserManagement</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
@endsection
@section('content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <div class="card card-red card-outline">
              <div class="card-header">
                  <h5 class="card-title">User List</h5>
                  <div class="card-tools">
                      <button type="button" name="create_record" id="create_record"
                      class="btn btn-success btn-sm btn-flat"><i class="fas fa-plus"></i> Add New User</button>
                  </div>
              </div>
            <div class="card-body table-responsive">
                <table id="usertable" class="table table-border">
                    <thead>
                      <tr>
                        <th>NO</th>
                        <th>Photo</th>
                        <th>User Name</th>
                        <th>User Type</th>
                        <th width="15%">Action</th>
                      </tr>
                    </thead>
                    <tbody>

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

  <div class="modal fade" id="formModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">Username</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter Username..." value="{{ old('name') }}" required autofocus>
                            @if ($errors->has('name'))
                            <span class="help-block">
                              <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">Email address</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                            <span class="help-block">
                              <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="pass">Password</label>
                            <input type="password" name="password" class="form-control" id="pass" placeholder="Password" required="">
                            @if ($errors->has('password'))
                            <span class="help-block">
                              <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="cpassword">Confirm Password</label>
                            <input type="password" id="cpassword" class="form-control" name="password_confirmation" placeholder="Confirm Your Password" required>


                          </div>
                        </div>
                      </div>


                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="type">User Type</label>

                          <select class="form-control" name="type" id="type">
                            <option value="admin">Admin</option>
                            <option value="generator">Generator</option>
                          </select>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Select User Image : </label>
                                <input type="file" name="image" id="image" />
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
    $('#usertable').DataTable({
        processing: true,
            language: {
        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
        serverSide: true,
        ajax: {
            url: "{{ route('admin.user.index') }}",
        },
        columns: [
            {
                data: 'rownum',
                name: 'rownum' ,
            },
            {
                data: 'photo',
                name: 'photo',
                render: function(data, type, full, meta) {
                    return "<img src={{ URL::to('/') }}" + data +
                        " width='50' class='img-thumbnail' />";
                },
                orderable: false
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'type',
                name: 'type',
                render: function(data, type, full, meta) {
                  if(data == "admin")
                  {
                    return "<span class='label bg-success'>"+data+"</span>";
                  }else{
                    return "<span class='label bg-primary'>"+data+"</span>";
                  }
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
                $('#formModal .modal-title').text("Add New User");
                $('#action_button').val("Add");
                $('#action').val("Add");
                $('#formModal').modal('show');
                $("#store_image").text(" ");
            });

            $('#sample_form').on('submit', function(event) {
                event.preventDefault();
                if ($('#action').val() == 'Add') {
                    $.ajax({
                        url: "{{ route('admin.user.store') }}",
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
                                $('#usertable').DataTable().ajax.reload();
                            }
                        }
                    })
                }

                if ($('#action').val() == "Edit") {
                    $.ajax({
                        url: "{{ route('admin.user.update') }}",
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
                                $('#usertable').DataTable().ajax.reload();
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
                    url: "{{ url('/admin/user/edit/') }}/" + id,
                    dataType: "json",
                    success: function(html) {
                        $('#name').val(html.data.name);
                        $('#email').val(html.data.email);
                        $('#store_image').html("<img src={{ URL::to('/') }}/" + html.data
                            .photo + " width='70' class='img-thumbnail' />");
                        $('#store_image').append(
                            "<input type='hidden' name='hidden_image' value='" + html.data
                            .photo + "' />");
                        $('#type option').each(function(){
                            if($(this).val() == html.data.type)
                            {
                                $(this).attr('selected','selected');
                            }
                        })
                        $('#hidden_id').val(html.data.id);
                        $('#formModal .modal-title').text("Edit User");
                        $('#action_button').val("Edit");
                        $('#action').val("Edit");
                        $('#formModal').modal('show');
                    }
                })
            });

            var user_id;

            $(document).on('click', '.delete', function() {
                user_id = $(this).attr('id');
                $('#confirmModal').modal('show');
            });

            $('#ok_button').click(function() {
                $.ajax({
                    url: "{{ url('/admin/user/destroy/') }}/" + user_id,
                    beforeSend: function() {
                        $('#ok_button').text('Deleting...');
                    },
                    success: function(data) {
                        setTimeout(function() {
                            $('#confirmModal').modal('hide');
                            $('#ok_button').text('OK');
                            toastr.success('Info - Successfully Deleted!')
                            $('#usertable').DataTable().ajax.reload();
                        }, 2000);
                    }
                })
            });
    })
</script>
@endsection
