@extends('layouts.back-app')
@section('style')
    <style>
        .modal-content {
            -webkit-border-radius: 0px !important;
            -moz-border-radius: 0px !important;
            border-radius: 0px !important;
            background-color: #f8f8f8;
        }

    </style>
@endsection
@section('breadcrumb')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.election.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Member List</li>
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
                            <div class="card-title">Member List</div>
                            <div class="card-tools">
                                
                            <a href="{{route('member-excel-download')}}" class="btn btn-dark btn-flat"><i
                                class="fa fa-download"></i> Download Excel</a>
                                <a href="{{ route('admin.register.excel.import') }}" class="btn btn-danger btn-flat"><i
                                        class="fas fa-file-excel" aria-hidden="true"></i> Excel Import</a>
                                <a href="{{ route('admin.register.create') }}" class="btn btn-success btn-flat"><i
                                        class="fa fa-plus" aria-hidden="true"></i> Add Member</a>   

                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-success btn-sm" id="btn_sendAll"><i
                                            class="fa fa-paper-plane" aria-hidden="true"></i> Send Message (All)
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm" id="btn_sendSelected"><i
                                            class="fa fa-paper-plane" aria-hidden="true"></i> Send Message (Selected)
                                    </button>
                                </div>
                                <div class="col-md-6 text-center text-md-right mt-3 mt-md-0">
                                    <button type="button" class="btn btn-info btn-sm" id="btn_GenerateVoterID"><i
                                        class="fa fa-print" aria-hidden="true"></i> Generate VoterID (Selected)
                                </button>
                                </div>
                            </div>
                            <table id="membertable" class="table table-valign-middle table-border" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th> &nbsp; <input type="checkbox" name="checked_all" class="checkbox"></th>
                                        <th>NO</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>NRC</th>
                                        <th>Reference Code</th>
                                        <th>Phone Number</th>
                                        <th>Created_Date</th>
                                        <th>Modified_Date</th>
                                        <th>Voter Generated</th>
                                        <th>Action</th>
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

    <div class="modal fade" id="deleteconfirmModal">
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


    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Choose Method</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="sendMethod">
                        <input type="hidden" name="action">
                        <div class="form-group clearfix">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="icheck-success d-inline">
                                        <input type="radio" name="method" checked id="radioSuccess1" value="sms">
                                        <label for="radioSuccess1">
                                            <span class="label bg-primary"> Via SMS <i class="fa fa-comment"></i></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="icheck-success d-inline">
                                        <input type="radio" name="method" id="radioSuccess2" value="email">
                                        <label for="radioSuccess2">
                                            <span class="label bg-primary"> Via Email <i class="fa fa-envelope"></i></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="icheck-success d-inline">
                                        <input type="radio" name="method" id="radioSuccess3" value="both">
                                        <label for="radioSuccess3">
                                            <span class="label bg-primary"> Via Both <i class="fa fa-comment"></i> <i
                                                    class="fa fa-envelope"></i></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success btn-flat btn-sm"><i
                                        class="fa fa-paper-plane"></i> Send</button>
                                <button type="button" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal"><i
                                        class="fa fa-reply-all"></i> Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            var table = $('#membertable').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                processing: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.register.index') }}",
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        render: function(data) {
                            return '<div class="text-center"><input type="checkbox" name="checked" class="checkbox" value="' +
                                data + '"></div>';
                        },
                    },
                    {
                        data: 'rownum',
                        name: 'rownum',
                    },
                    {
                        data: 'profile',
                        name: 'profile',
                        render: function(data, type, full, meta) {
                            if (data != null) {
                                return "<img src={{ URL::to('/') }}" + data +
                                    " width='70' class='img-thumbnail' />";
                            } else {
                                return "<img src='{{ URL::to('/images/user.png') }}' width='70' class='img-thumbnail' />";
                            }
                        },
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'nrc',
                        name: 'nrc',
                    },
                    {
                        data: 'refer_code',
                        name: 'refer_code',
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'check_flag',
                        name: 'check_flag',
                        className:'text-center',
                        render: function(data, type, full, meta) {
                            if(data == 1)
                            {
                                return "<span class='badge badge-success'>Done</span>";
                            }else{
                                return "<span class='badge badge-danger'>Not Yet</span>";
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        render: function(data, type, full, meta) {
                            return data;
                        },
                    }
                ],
                columnDefs: [{
                    targets: 0,
                    orderable: false,
                }],
                "aaSorting": []
            });

            var member_id;

            $(document).on('click', '.detail', function() {
                member_id = $(this).attr('id');
                var url = '{{ route('admin.register.detail', ['member_id' => ':member_id']) }}';
                url = url.replace(':member_id', member_id);


                window.location.href = url;
            })

            $(document).on('click', '.edit', function() {
                member_id = $(this).attr('id');
                var url = '{{ route('admin.register.edit', ['member_id' => ':member_id']) }}';
                url = url.replace(':member_id', member_id);


                window.location.href = url;
            })

            $(document).on('click', '.delete', function() {
                member_id = $(this).attr('id');
                $('#deleteconfirmModal').modal('show');
            });

            $('#ok_button').click(function() {
                $.ajax({
                    url: "{{ url('/admin/register/destroy/') }}/" + member_id,
                    beforeSend: function() {
                        $('#ok_button').text('Deleting...');
                    },
                    success: function(data) {
                        setTimeout(function() {
                            $('#deleteconfirmModal').modal('hide');
                            $('#ok_button').text('OK');
                            toastr.success('Info - Successfully Deleted!')
                            $('#membertable').DataTable().ajax.reload();
                        }, 2000);
                    }
                })
            });

            $("input[name=checked_all]").on("change", function() {
                $("tbody tr").each(function() {
                    if ($(this).hasClass('allChecked')) {
                        $(this).find('input[type="checkbox"]').prop('checked', false);
                        $(this).find('input[type="checkbox"]').closest('tr').removeClass(
                            'table-active');
                    } else {
                        $(this).find('input[type="checkbox"]').prop('checked', true);
                        $(this).find('input[type="checkbox"]').closest('tr').addClass(
                            'table-active');
                    }
                    $(this).toggleClass('allChecked');
                })
            })

            $(document).on('change', 'tbody tr .checkbox', function() {
                if ($(this).closest('tr').hasClass('table-active')) {
                    $(this).closest('tr').removeClass('table-active');
                    $("input[name=checked_all]").prop('checked', false);
                    $("tbody tr").each(function() {
                        $(this).removeClass('allChecked');
                    })
                } else {
                    $(this).closest('tr').addClass('table-active');
                }
            })

            $("#btn_sendAll").on("click", function() {

                $('input[name=action]').val('all_message');
                $("#confirmModal").modal('show');
            })

            $("#btn_sendSelected").on('click', function() {
                var check = $('input[name=checked]:checked').length;
                if (check == 0) {
                    toastr.error("Warning - Please Select At Least One Row to Send Message!")
                    return false;
                }

                $('input[name=action]').val('select_message');
                $("#confirmModal").modal('show');
            })

            $("form#sendMethod").on('submit', function(e) {
                e.preventDefault();
                var method = $(this).find('input[name=method]:checked').val();
                var action = $('input[name=action]').val();
                var checkData = [];

                $.blockUI({
                    css: {
                        backgroundColor: 'transparent',
                        top: '0px',
                        left: '0px',
                        width: $(document).width(),
                        height: $(document).height(),
                        padding: '20%',
                    },
                    baseZ: 2000,
                    message: '<img src="{{ url('images/loader.gif') }}" width="150" />',
                });

                if (method == "sms") {
                    //sms only
                    var url = "{{ route('member.message.smsOnly') }}";
                } else if (method == "email") {
                    //email only
                    var url = "{{ route('member.message.emailOnly') }}";
                } else if (method == "both") {
                    //both sms & email
                    var url = "{{ route('member.message') }}";
                } else {
                    $.unblockUI();
                    toastr.error('Info - Method Failed!')
                    $("#confirmModal").modal('hide');
                    return false;
                }

                if (action == "select_message") {
                    $("tbody tr input[name=checked]:checked").each(function() {
                        var check_val = $(this).val();
                        checkData.push(check_val);
                    })
                } else if (action == "all_message") {
                    $("tbody tr").each(function() {
                        var check_val = $(this).find('input[name=checked]').val();
                        checkData.push(check_val);
                    })
                }

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        check_val: checkData,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);
                        $.unblockUI();
                        $("#confirmModal").modal('hide');
                        if (data.errors) {
                            for (var count = 0; count < data.errors.length; count++) {
                                toastr.error('Info - ' + data.errors[count])
                            }
                        } else if (data.success) {
                            toastr.success('Info - ' + data.success)
                        }
                    },
                    error: function(response) {
                        $.unblockUI();
                        $("#confirmModal").modal('hide');
                        if (response['responseJSON']) {
                            toastr.error('Info - ' + response['responseJSON'].message)
                        } else {
                            toastr.error('Info - Something Went Wrong!')
                        }
                    }
                });
            })

            $(document).on('click','#btn_GenerateVoterID',function(){
                var check = $('input[name=checked]:checked').length;
                
                if (check == 0) {
                    toastr.error("Warning - Please Select At Least One Row to Generate!")
                    return false;
                }

                var checkData = [];

                $.blockUI({
                    css: {
                        backgroundColor: 'transparent',
                        top: '0px',
                        left: '0px',
                        width: $(document).width(),
                        height: $(document).height(),
                        padding: '20%',
                    },
                    baseZ: 2000,
                    message: '<img src="{{ url('images/loader.gif') }}" width="150" />',
                });

                $("tbody tr input[name=checked]:checked").each(function() {
                    var check_val = $(this).val();
                    checkData.push(check_val);
                })

                $.ajax({
                    type: "POST",
                    url: "{{route('member.generate.vid')}}",
                    data: {
                        check_val: checkData,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);
                        $.unblockUI();       
                        if (data.errors) {
                            for (var count = 0; count < data.errors.length; count++) {
                                toastr.error('Info - ' + data.errors[count])
                            }
                        } else if (data.success) {
                            toastr.info('Info - ' + data.success)
                            $("input[name=checked_all]").prop('checked',false);
                            table.ajax.reload();
                        }                 
                    },
                    error: function(response) {
                        $.unblockUI();                       
                        if (response['responseJSON']) {
                            toastr.error('Info - ' + response['responseJSON'].message)
                        } else {
                            toastr.error('Info - Something Went Wrong!')
                        }
                    }
                });
            })
        })

    </script>
@endsection
