@extends('layouts.back-app')

@section('content')
    <!-- Main content -->
    <div class="content pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-red card-outline">
                        <div class="card-header">
                            <h5 class="card-title">Election</h5>
                            <div class="card-tools">
                                <a href="{{route('admin.election.create')}}" 
                                    class="btn btn-success btn-sm btn-flat"><i class="fas fa-plus"></i> Add New
                                    Election</a>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="electiontable" class="table table-hover table-border">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Status</th>
                                        <th width="15%">Name</th>                                                                                
                                        <th width="10%">Start Time</th>
                                        <th width="10%">End Time</th>
                                        <th>Lucky Draw</th>
                                        <th>Q&A</th>
                                        <th>Candidate</th>
                                        <th width="auto">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($elections as $key => $election)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if ($election->status == 0)
                                                    <input data-id="{{ $election->id }}"
                                                        data-status="{{ $election->status }}" class="status toggle-class"
                                                        type="checkbox" data-onstyle="success" data-offstyle="danger"
                                                        data-toggle="toggle" data-on="START" data-off="STOP"
                                                        {{ $election->status ? 'checked' : '0' }}>
                                                @elseif($election->status == 1)
                                                    <input data-id="{{ $election->id }}"
                                                        data-status="{{ $election->status }}" class="status toggle-class"
                                                        type="checkbox" data-onstyle="success" data-offstyle="danger"
                                                        data-toggle="toggle" data-on="START" data-off="STOP"
                                                        {{ $election->status ? 'checked' : '1' }}>
                                                @endif
                                            </td>

                                            <td>{{ $election->name }}</td>                                            
                                            <td id="start_time">{{ $election->start_time }}</td>
                                            <td id="end_time">{{ $election->end_time }}</td>
                                            <td>
                                                @if ($election->lucky_flag == 0)
                                                    <label class="label bg-danger">OFF</label>
                                                @elseif($election->lucky_flag == 1)
                                                    <label class="label bg-success">ON</label>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($election->ques_flag == 0)
                                                    <label class="label bg-danger">OFF</label>
                                                @elseif($election->ques_flag == 1)
                                                    <label class="label bg-success">ON</label>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($election->candidate_flag == 0)
                                                    <label class="label bg-danger">OFF</label>
                                                @elseif($election->candidate_flag == 1)
                                                    <label class="label bg-success">ON</label>
                                                @endif
                                            </td>
                                            <td style="width:20%;">
                                                <a href="{{ route('admin.dashboard', $election->id) }}"
                                                    class="btn btn-flat btn-xs btn-info"><i class="fa fa-cogs"></i>
                                                    Dashboard</a>
                                                <button type="button" class="edit btn btn-primary btn-xs btn-flat mr-1"
                                                    data-id="{{ $election->id }}"><i class="fa fa-edit"></i> Edit</a>
                                                <button type="button" class="delete btn btn-danger btn-xs btn-flat"
                                                    data-id="{{ $election->id }}"><i class="fa fa-trash"></i>
                                                    Delete</button>                                               
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

    <div class="modal fade" id="alertModal">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title"><i class="fas fa-exclamation-circle"></i> Confirmation</h5>                   
                </div>
                <div class="modal-body">
                    <h6 align="center" style="margin:0;" class="title">Are you sure you want to start this election? Question Data is Empty!</h6>                    
                    <h6 align="center" class="text-danger m-0 pt-3 warning">Note: if you chose anyway, Question Feature will be closed because question data is empty!</h6>
                    {!! Form::hidden('election_id') !!}
                    {!! Form::hidden('flag') !!}
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" name="anyway_btn" id="anyway_btn" class="btn btn-danger">Anyway</button>
                    <button type="button" class="btn btn-default" id="cancel_btn">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="accessDeniedModal">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title"><i class="fas fa-exclamation-circle"></i> Warning!</h5>                    
                </div>
                <div class="modal-body">
                    <h6 align="center" style="margin:0;" class="text-danger">You Can't Start This Election! Cause Candidate Data and Question Data are Empty!</h6>                                                            
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" name="ok_btn" id="ok_btn" class="btn btn-danger">Ok</button>                    
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
    var election_id;
    var delete_route;
    var status_route = "{{ route('election.changestatus') }}";
    var focre_status_route = "{{route('election.force.changestatus')}}";

    $(document).on("click", ".delete", function () {
        election_id = $(this).data("id");
        delete_route = "{{ url('/admin/election/destroy') }}/" + election_id;
        $("#confirmModal").modal("show");
    });     

    $(document).on("click",".edit",function(){
        election_id = $(this).data("id");
        var edit_route = "{{ url('/admin/election/edit') }}/" + election_id;

        window.location.href = edit_route;
    })

    $("#electiontable").DataTable();

    $(document).ready(function(){
        $("tbody tr").each(function () {
            var election_status = $(this).find('.status').data('status');
            
            if (election_status == 1) {
                $(this).find('.delete').attr('disabled', true);
                $(this).find('.edit').attr('disabled', true);
            } else {
                $(this).find('.delete').attr('disabled', false);
                $(this).find('.edit').attr('disabled', false);
            }
        })
    })

    $("#confirmModal #ok_button").click(function () {    
        $.ajax({
            url: delete_route,
            beforeSend: function () {
                $("#confirmModal #ok_button").text("Deleting...");
            },
            success: function (data) {
                if (data.success) {
                    $("#confirmModal #ok_button").text("OK");
                    $("#confirmModal").modal("hide");
                    toastr.success("Info - " + data.success);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            },
        });
    });

    $("#accessDeniedModal #ok_btn").click(function(){
        $("#accessDeniedModal").modal("hide");
        location.reload();
    });   

    $("#alertModal #anyway_btn").click(function () {
        var election_id = $(this).closest('#alertModal').find('input[name=election_id]').val();
        var flag = $(this).closest('#alertModal').find('input[name=flag]').val();
        
        $.ajax({
            type:"post",
            url: focre_status_route,
            data:{                
                election_id: election_id,
                flag:flag,
            },
            beforeSend: function () {
                $("#alertModal #anyway_btn").text("Processing...");
            },
            success: function (data) {                
                if (data.success) {
                    $("#alertModal #anyway_btn").text("OK");
                    $("#alertModal").modal("hide");
                    toastr.success("Info - " + data.success);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            },
        });
    });

    $("#alertModal #cancel_btn").click(function(e){
        e.preventDefault();
        $("#alertModal").modal("hide");
        location.reload();    
    })
    

    $("#electiontable tr .status").change(function () {
        var status = $(this).prop("checked") == true ? 1 : 0;        
        var election_id = $(this).data("id");
        var this_loc = $(this);

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        
        $.ajax({
            type: "post",
            dataType: "json",
            url: status_route,
            data: {
                status: status,
                election_id: election_id,
            },
            success: function (data) {                
                if(data.ques_notFound && data.cadidate_notFound){
                    $("#accessDeniedModal").modal({
                        backdrop:false,
                        keyboard: false
                    });
                }else if(data.cadidate_notFound){
                    $("#alertModal input[name=election_id]").val(election_id);
                    $("#alertModal input[name=flag]").val('candidate');
                    $("#alertModal .title").html('Are you sure you want to start this election? Candidate Data is Empty!');
                    $("#alertModal .warning").html('Note: if you chose anyway, Candidate Feature will be closed because candidate data is empty!');
                    $("#alertModal").modal("show");
                }else if(data.ques_notFound){
                    $("#alertModal input[name=election_id]").val(election_id);
                    $("#alertModal input[name=flag]").val('question');
                    $("#alertModal .title").html('Are you sure you want to start this election? Question Data is Empty!');
                    $("#alertModal .warning").html('Note: if you chose anyway, Question Feature will be closed because question data is empty!');
                    $("#alertModal").modal("show");
                }else if (data.errors) {
                    toastr.error(
                        "Info - " +
                            data.election +
                            " Election Voting " +
                            data.errors
                    );
                } else if (data.success) {
                    toastr.success("Info - " + data.success);
                    if (data.election.status == 1) {
                        this_loc
                            .closest("tr")
                            .find("#start_time")
                            .html(data.election.start_time);
                        $("tbody tr").each(function () {
                            var check =
                                $(this).find(".status").prop("checked") == true
                                    ? 1
                                    : 0;
                            if (check == data.election.status) {
                                $(this).find(".delete").attr("disabled", true);
                                $(this).find(".edit").attr("disabled", true);
                            }
                        });
                    } else if (data.election.status == 0) {
                        this_loc
                            .closest("tr")
                            .find("#end_time")
                            .html(data.election.end_time);
                        $("tbody tr").each(function () {
                            var check =
                                $(this).find(".status").prop("checked") == true
                                    ? 1
                                    : 0;
                            if (check == data.election.status) {
                                $(this).find(".delete").attr("disabled", false);
                                $(this).find(".edit").attr("disabled", false);
                            }
                        });
                    }
                }
            },
        });
    });
</script>
@endsection
