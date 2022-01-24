@extends('layouts.back-app')
@section('style')
<style>
    .modal-content {
        -webkit-border-radius: 0px !important;
        -moz-border-radius: 0px !important;
        border-radius: 0px !important;
        background-color: #f8f8f8;
    }

    #reminder button {
        padding: 0.5em 1em;
        cursor: pointer;
        font-size: 0.88em;
        line-height: 1.6em;
        white-space: nowrap;
        overflow: hidden;
        filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0, StartColorStr='rgba(230, 230, 230, 0.1)', EndColorStr='rgba(0, 0, 0, 0.1)');
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        text-decoration: none;
        outline: none;
        text-overflow: ellipsis;
    }

    @media print {
        #print_content {
            position: absolute;
            width: 300px;
            height: 300px;
            z-index: 15;
            top: 20%;
            left: 50%;
            margin: -150px 0 0 -150px;
        }
    }
</style>
@endsection
@section('breadcrumb')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                {{-- <h1>candidate List</h1> --}}
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        @if(Auth::user()->type == 'admin')
                        <a href="{{route('admin.election.index')}}">Home</a>
                        @else
                        <a href="{{ route('generator.index') }}">Home</a>
                        @endif
                    </li>
                    <li class="breadcrumb-item active">Voter ID Generated List</li>
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
                        <h3 class="card-title pt-2">Voter ID Generated List</h3>
                        <div class="card-tools">
                            <a href="{{route('generator.excel.export')}}" class="btn btn-success btn-flat"><i
                                    class="fa fa-download"></i> Download Excel</a>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form method="post" id="reminder" style="display: inline;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row mb-2">
                                        <div class="col-md-8">
                                            <button type="submit" class="btn btn-outline-danger my-1"><i
                                                    class="fa fa-bell" aria-hidden="true"></i>
                                                Send Reminder (All "Not
                                                Voted")
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger my-1"
                                                id="select_reminder"><i class="fa fa-bell" aria-hidden="true"></i> Send
                                                Reminder (Selected)</button>

                                            <button type="button" id="btn_annouceAll"
                                                class="btn btn-sm btn-outline-primary my-1"><i class="fa fa-bell"
                                                    aria-hidden="true"></i>
                                                Annouce (All)
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary my-1"
                                                id="btn_annouceSelected"><i class="fa fa-bell" aria-hidden="true"></i>
                                                Annouce (Selected)</button>
                                        </div>
                                        <div class="col-md-4 text-lg-right">
                                            <button type="button" class="btn btn-outline-success" id="btn_sendAll"><i
                                                    class="fa fa-paper-plane" aria-hidden="true"></i> Send Message (All)
                                            </button>
                                            <button type="button" class="btn btn-outline-success"
                                                id="btn_sendSelected"><i class="fa fa-paper-plane"
                                                    aria-hidden="true"></i> Send Message (Selected)
                                            </button>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <select name="electionid" id="electionid"
                                                class="btn btn-sm btn-outline-primary my-1">
                                                <option value="">--- Select Election ---</option>
                                                @foreach ($electionLidt as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->candidate_flag=='1'? $item->position: $item->ques_title }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary btn-sm ml-3" type="button" id="btn_search"><i
                                                    class="fa fa-search"></i></button>

                                            <select name="flagstatus" id="flagstatus"
                                                class="btn btn-sm btn-outline-secondary my-1">
                                                <option value="">---Select---</option>
                                                <option value="1">Done</option>
                                                <option value="0">Not Yet</option>
                                            </select>
                                            <button class="btn btn-secondary btn-sm ml-3" type="button"
                                                id="btn_search_for_status"><i class="fa fa-search"></i></button>

                                            <button class="btn btn-danger" type="button" id="btn_refresh"><i
                                                    class="fa fa-recycle"></i> Refresh</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table id="vidtable" class="table table-bordered datatable datatable-Client hidden"
                            style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> &nbsp; <input type="checkbox"
                                            name="checked_all"></th>
                                    <th>NO</th>
                                    <th>Voter ID</th>
                                    <th>Name</th>                                    
                                    <th>Phone Number</th>
                                    <th>Vote</th>
                                    <th>Vote Count</th>
                                    <th>Logs</th>
                                    <th>Reminder Logs</th>
                                    <th>Created Date</th>
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

<div style="display:none;">
    <div class="row" id="print_content">
        <div class="col-12">
            <div style="text-align:center;" >                
                <img src="{{ $setting->logo_image ? url($setting->logo_image) : url('images/election_logo.png') }}" class="col-8" style="width: 300px; height: 50px; object-fit: cover" alt="" >    
            </div>

            <h3 style="text-align:center;"> <b><span id="election_name"></span></b></h3>
            <p style="text-align:center;font-size:20px;">Print Date: {{Carbon\Carbon::now()->format('d/M/Y h:i:s A')}}
            </p>
            <br>
            <p style="text-align:center;font-size:30px;"><strong>Voter ID: <span id="voter_id"
                        style="border:2px solid red;margin-left:15px;padding-left:8px;padding-right:8px; font-family: 'Roboto Mono', monospace;"></span>
                </strong></p>
            <br>
            <p style="text-align:center; font-size:20px"> Thank You</p>
            <p>.</p>
        </div>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {  
        var table = null;
             $(document).on('click', '#btn_print', function() {
                let id = $(this).data('id');
                let voter_id = $(this).data('voter_id');
                let name = $(this).data('name');            

                if (voter_id != null) {
                    $("#election_name").text(name);
                    $("#print_content #voter_id").text(voter_id);
                    $.print("#print_content");
                } else {
                    toastr.error('Voter ID Does not Exist!')
                }
            })

            var err = false;

            $("form#sendMethod").on('submit',function(e){
                e.preventDefault();
                var method = $(this).find('input[name=method]:checked').val();
                var action = $('input[name=action]').val();                
                var checkData = [];

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

                if(method == "sms")
                {
                    //sms only
                    url = "{{route('vid.message.smsOnly')}}";
                }else if(method == "email")
                {
                    //email only
                    url = "{{route('vid.message.emailOnly')}}";
                    }else if(method == "both")
                {
                    //both sms & email
                    url = "{{route('vid.message')}}";
                }else{
                    $.unblockUI();
                    toastr.error('Info - Method Failed!')
                    $("#confirmModal").modal('hide');
                    return false;
                }

                if (action == "select_reminder" || action == "select_annouce" || action == "select_message") {
                    $("tbody tr input[name=checked]:checked").each(function() {
                        var check_val = $(this).val();
                        checkData.push(check_val);
                    })
                } else if (action == "all_reminder" || action == "all_annouce" || action == "all_message") {
                    $("tbody tr").each(function() {
                        var check_val = $(this).find('input[name=checked]').val();
                        checkData.push(check_val);
                    })
                }                                             

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        type:action,
                        check_val: checkData,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(data) {
                        $.unblockUI();
                        $("#confirmModal").modal('hide');
                        if (data.errors) {
                            toastr.error('Info - ' + data.errors)
                        } else if (data.success) {
                            toastr.success('Info - ' + data.success)
                        }
                    },
                    error: function(response) {
                        $.unblockUI();
                        $("#confirmModal").modal('hide');
                        if(response['responseJSON'])
                        {
                            toastr.error('Info - ' + response['responseJSON'].message)
                        }else{
                            toastr.error('Info - Something Went Wrong!')
                        }
                    }
                });
            })

            
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
            //Dropdown Election//
            $("#btn_search").on('click',function(){  
                var election_id = $("#electionid").val(); 
                $("#vidtable").removeClass('hidden');
                $("#vidtable").DataTable().destroy();
                table = $('#vidtable').DataTable({
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    processing: true,                                                  
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                    },
                    serverSide: true,
                    ajax: {
                        type:"GET",
                        data:{
                            election_id:election_id
                        },
                        url: window.location.href,
                    },
                    columns: [{
                            data: 'id',
                            name: 'id',
                            render: function(data) {
                                // console.log(data);
                                return '<div class="text-center"><input type="checkbox" name="checked" class="checkbox" value="' +
                                    data + '"></div>';
                            },
                        },
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable:false,
                            searchable:false,
                        },
                        {
                            data: 'voter_id',
                            name: 'voter_id'
                        },
                        {
                            data: 'name',
                            name: 'name',
                        },                        
                        {
                            data: 'phone_no',
                            name: 'phone_no',
                        },
                        {
                            data: 'election_voter.done',
                            name: 'election_voter.done',
                            render: function(data) {
                                // console.log(data);
                                if(data)
                                {                                                                                                        
                                    if(data > 0)
                                    {
                                        return "<span class='badge badge-success'>Done</span>";
                                    }else{
                                        return "<span class='badge badge-danger'>Not Yet</span>";
                                    }

                                    return '';
                                }else{
                                    return "<p class='text-danger'>Not Yet</p>";
                                }
                            }
                        },
                        {
                            data: 'vote_count',
                            name: 'vote_count',
                        },
                        {
                            data: 'log.sms_flag',
                            name: 'log.sms_flag',
                            render:function(data,type,row)
                            {                            
                                if(typeof data === 'undefined' || data === null)
                                {
                                    return "<p class='text-danger'>Something Went Wrong!</p>";
                                }else{
                                    if(row.sms_flag != 0 && row.email_flag != 0)
                                    {
                                        //both
                                        if(row.sms_flag == 1)
                                        {
                                            if(row.email_flag == 1)
                                            {
                                                return "<p class='text-danger'>SMS - Failed!</p><p class='text-danger'> Email - Failed!</p>";
                                            }else{
                                                return "<p class='text-danger'>SMS - Failed!</p><p class='text-success'> Email - Success!</p>";
                                            }
                                        }else{
                                            if(row.email_flag == 1)
                                            {
                                                return "<p class='text-success'>SMS - Success!</p><p class='text-danger'> Email - Failed!</p>";
                                            }else{
                                                return "<p class='text-success'>SMS - Success!</p><p class='text-success'> Email - Success!</p>";
                                            }                                        
                                        }
                                    }else if(row.sms_flag != 0){
                                        if(row.sms_flag == 1)
                                        {
                                            return "<p class='text-danger'>SMS - Failed!</p>";
                                        }else{
                                            return "<p class='text-success'>SMS - Success!</p>";
                                        }
                                    }else if(row.email_flag != 0){
                                        if(row.email_flag == 1)
                                        {
                                            return "<p class='text-danger'>Email - Failed!</p>";
                                        }else{
                                            return "<p class='text-success'>Email - Success!</p>";
                                        }
                                    }else{
                                        return "<p>Not Yet!</p>";
                                    }                              
                                }
                            }
                        },{
                            data: 'log.reminder_sms_flag',
                            name: 'log.reminder_sms_flag',
                            render:function(data,type,row)
                            {                            
                                if(typeof data === 'undefined' || data === null)
                                {
                                    return "<p class='text-danger'>Something Went Wrong!</p>";
                                }else{
                                    if(row.reminder_sms_flag != 0 && row.reminder_email_flag != 0)
                                    {
                                        //both
                                        if(row.reminder_sms_flag == 1)
                                        {
                                            if(row.reminder_email_flag == 1)
                                            {
                                                return "<p class='text-danger'>SMS - Failed!</p><p class='text-danger'> Email - Failed!</p>";
                                            }else{
                                                return "<p class='text-danger'>SMS - Failed!</p><p class='text-success'> Email - Success!</p>";
                                            }
                                        }else{
                                            if(row.reminder_email_flag == 1)
                                            {
                                                return "<p class='text-success'>SMS - Success!</p><p class='text-danger'> Email - Failed!</p>";
                                            }else{
                                                return "<p class='text-success'>SMS - Success!</p><p class='text-success'> Email - Success!</p>";
                                            }                                        
                                        }
                                    }else if(row.reminder_sms_flag != 0){
                                        if(row.reminder_sms_flag == 1)
                                        {
                                            return "<p class='text-danger'>SMS - Failed!</p>";
                                        }else{
                                            return "<p class='text-success'>SMS - Success!</p>";
                                        }
                                    }else if(row.reminder_email_flag != 0){
                                        if(row.reminder_email_flag == 1)
                                        {
                                            return "<p class='text-danger'>Email - Failed!</p>";
                                        }else{
                                            return "<p class='text-success'>Email - Success!</p>";
                                        }
                                    }else{
                                        return "<p>Not Yet!</p>";
                                    }                              
                                }
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
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
                    "aaSorting": [],                        
                });
            })


             $("#btn_refresh").on('click',function(){                
                var status = $("#flagstatus").val('');
                var electionid = $("#electionid").val('');
                $('#vidtable').DataTable().columns().search("").draw();
            })

            $("#btn_search_for_status").on('click',function(){                                             
                var checkflage = $("#flagstatus").val();                                        
                table.column(6).search(checkflage).draw();                
            })           
          

          

            
            $(document).on('change','tbody tr .checkbox',function(){
                if ($(this).closest('tr').hasClass('table-active')) {
                    $(this).closest('tr').removeClass('table-active');
                    $("input[name=checked_all]").prop('checked',false);
                    $("tbody tr").each(function(){
                        $(this).removeClass('allChecked');
                    })
                } else {
                    $(this).closest('tr').addClass('table-active');
                }
            })

            $("#select_reminder").on("click", function() {
                var check = $('input[name=checked]:checked').length;
                if (check == 0) {
                    toastr.error("Warning - Please Select At Least One Row to send Message!")
                    return false;
                }
                // if (election_status == 0) {
                //     toastr.error("Warning - Election is not Started!")
                //     return false;
                // }
                // var stop = false;
                // $('tbody tr').each(function() {
                //     var check_val = $(this).find('input[name=checked]:checked').val();
                //     if (check_val) {
                //         var done = $(this).find('input[name=reminder_done]').val();
                //         if (done != 0) {
                //             stop = true;
                //         }
                //     }
                // })
                // if (stop == true) {
                //     toastr.error('Info - Already Voted Voter Exist!');
                // } else {
                //     $('input[name=action]').val('select_reminder');
                //     $("#confirmModal").modal('show');
                // }

                $('input[name=action]').val('select_reminder');
                    $("#confirmModal").modal('show');
            })

            $("form#reminder").on("submit", function(e) {
                e.preventDefault();

                // if (election_status == 0) {
                //     $.unblockUI();
                //     toastr.error("Warning - Election is not Started!")
                //     return false;
                // }

                $('input[name=action]').val('all_reminder');
                $("#confirmModal").modal('show');
            })

            $("#btn_sendAll").on("click", function() {
                // if (election_status == 0) {
                //     toastr.error("Warning - Election is not Started!")
                //     return false;
                // }

                $('input[name=action]').val('all_message');
                $("#confirmModal").modal('show');
            })

            $("#btn_annouceAll").on("click", function() {                
                // if (election_status == 0) {
                //     toastr.error("Warning - Election is not Started!")
                //     return false;
                // }

                $('input[name=action]').val('all_annouce');
                $("#confirmModal").modal('show');
            })
            

            $("#btn_annouceSelected").on('click', function() {
                var check = $('input[name=checked]:checked').length;                
                if (check == 0) {
                    toastr.error("Warning - Please Select At Least One Row to send Message!")
                    return false;
                }
                // if (election_status == 0) {
                //     toastr.error("Warning - Election is not Started!")
                //     return false;
                // }

                $('input[name=action]').val('select_annouce');
                $("#confirmModal").modal('show');
            })

            $("#btn_sendSelected").on('click', function() {
                var check = $('input[name=checked]:checked').length;
                if (check == 0) {
                    toastr.error("Warning - Please Select At Least One Row to send Message!")
                    return false;
                }
                $('input[name=action]').val('select_message');
                $("#confirmModal").modal('show');
            }); 
        });                  
        

</script>
@endsection