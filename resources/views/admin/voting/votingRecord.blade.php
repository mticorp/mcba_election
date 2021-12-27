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
                        <li class="breadcrumb-item active"><a
                                href="{{ route('admin.dashboard', $election->id) }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Voting Information</li>
                        <li class="breadcrumb-item active">Voting Record</li>
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
                                Voting Records List
                            </h3>
                            <div class="card-tools">

                                @if ($election->candidate_flag == 1 && $election->ques_flag == 1)
                                    <a class="btn btn-flat btn-success" data-toggle="modal" data-target="#confirmModal"><i
                                            class="fa fa-download" aria-hidden="true"></i> Download Excel </a>
                                @elseif ($election->candidate_flag == 1)
                                    <a id="btnCandidateExport" href="" class="btn btn-flat btn-success"><i
                                            class="fa fa-download" aria-hidden="true"></i> Download Excel </a>
                                @else
                                    <a id="btnAnswerExport" href="" class="btn btn-flat btn-success"><i
                                            class="fa fa-download" aria-hidden="true"></i> Download Excel </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <ul class="nav nav-tabs" id="tab" role="tablist">
                                @if ($election->candidate_flag == 1)
                                    <li class="nav-item">
                                        <a class="nav-link" id="candidate-tab" data-toggle="pill" href="#candidate"
                                            role="tab" aria-controls="candidate" aria-selected="true">Candidate
                                            Record</a>
                                    </li>
                                @endif
                                @if ($election->ques_flag == 1)
                                    <li class="nav-item">
                                        <a class="nav-link" id="answer-tab" data-toggle="pill" href="#answer"
                                            role="tab" aria-controls="answer" aria-selected="false">Answer
                                            Record</a>
                                    </li>
                                @endif
                            </ul>
                            <div class="tab-content" id="tabContent">
                                @if ($election->candidate_flag == 1)
                                    <div class="tab-pane fade pt-3" id="candidate" role="tabpanel"
                                        aria-labelledby="candidate-tab">
                                        <table id="votingtable" border="2px"
                                            class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Voter ID</th>
                                                    @foreach ($candidates as $candidate)
                                                        <th>{{ $candidate->mname }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $j = 1;
                                                @endphp
                                                @foreach ($voting_records as $key => $value)
                                                    <tr>
                                                        <td><strong>{{ $j++ }}</strong></td>
                                                        <td><strong>{{ $key }}</strong></td>
                                                        @foreach ($value as $k => $v)
                                                            <td style="text-align:center;">{{ $v->vote }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Total Vote</th>
                                                    @foreach ($vote_count as $count)
                                                        <th class="text-center">{{ $count->vote_count }}</th>
                                                    @endforeach
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @endif

                                @if ($election->ques_flag == 1)
                                    <div class="tab-pane fade pt-3" id="answer" role="tabpanel"
                                        aria-labelledby="answer-tab">
                                        <table id="answertable" border="2px"
                                            class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Voter ID</th>
                                                    @foreach ($ques as $que)
                                                        <th>
                                                            <a class="mytooltip"
                                                                style="text-decoration: none; color:black!important; line-height:20px!important;"
                                                                id="{{ $que->id }}myTooltip"
                                                                title="{{ $que->ques }}">Q
                                                                - {{ $que->no }}</a>
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($ans_records) > 0)
                                                    @php
                                                        $j = 1;
                                                    @endphp

                                                    @foreach ($ans_records as $key => $value)
                                                        <tr>
                                                            <td><strong>{{ $j++ }}</strong></td>
                                                            <td><strong>{{ $key }}</strong></td>
                                                            @foreach ($value as $k => $v)
                                                                <td style="text-align:center;">{{ $v->ans }}</td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Total Vote</th>
                                                    @if (count($ans_summary) > 0)
                                                        @foreach ($ans_summary as $count)
                                                            <th class="text-center">                                                               
                                                                <span class="text-success">Yes - {{ $count->yes_ans }}</span> /
                                                                <span class="text-danger">No - {{ $count->no_ans }}</span>
                                                            </th>
                                                        @endforeach
                                                    @else
                                                    @foreach ($ques as $que)
                                                        <th class="text-center">
                                                            <span class="text-success">Yes - 0</span> /
                                                            <span class="text-danger">No - 0</span>
                                                        </th>
                                                    @endforeach
                                                    @endif
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($election->ques_flag == 1)
        <div style="display:none;">
            <table id="answertable_download" border="2px" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Voter ID</th>
                        @foreach ($ques as $que)
                            <th>
                                Q -{{ $que->no }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $j = 1;
                    @endphp
                    @foreach ($ans_records as $key => $value)
                        <tr>
                            <td><strong>{{ $j++ }}</strong></td>
                            <td><strong>{{ $key }}</strong></td>
                            @foreach ($value as $k => $v)
                                <td style="text-align:center;">{{ $v->ans }}</td>
                            @endforeach
                        </tr>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th class="text-center">Total</th>
                        @foreach ($ans_summary as $count)
                            <th class="text-center">
                                Yes - {{ $count->yes_ans }}
                                No - {{ $count->no_ans }}
                            </th>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    @if ($election->candidate_flag == 1 & $election->ques_flag == 1)
        <!-- Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Choose File</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group clearfix">
                            <div class="row text-center">
                                <div class="col-md-6">
                                    <div class="icheck-success d-inline">
                                        <input type="radio" name="method" checked id="radioSuccess1" value="candidate">
                                        <label for="radioSuccess1">
                                            <span class="label bg-primary"><i class="fas fa-file-excel"></i> Candidate Record
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="icheck-success d-inline">
                                        <input type="radio" name="method" id="radioSuccess2" value="answer">
                                        <label for="radioSuccess2">
                                            <span class="label bg-primary"><i class="fas fa-file-excel"></i> Answer Record
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="button" id="btnExport" class="btn btn-success btn-flat btn-sm"><i
                                        class="fa fa-download"></i>
                                    Download</button>
                                <button type="button" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal"><i
                                        class="fa fa-reply-all"></i> Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endif
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            let candidate_flag = "{{ $election->candidate_flag }}";
            let ques_flag = "{{ $election->ques_flag }}";

            $("#candidate-tab").click(function() {
                window.location.hash = "candidate";
                location.reload();
            })

            $("#answer-tab").click(function() {
                window.location.hash = "answer";
                location.reload();
            })

            if (window.location.hash == "#answer") {
                $("#answer").addClass('show active');
                $("#answer-tab").addClass('active');
            } else {
                if (candidate_flag == 0) {
                    $("#answer").addClass('show active');
                    $("#answer-tab").addClass('active');
                } else {
                    $("#candidate").addClass('show active');
                    $("#candidate-tab").addClass('active');
                }
            }

            $("#votingtable").DataTable();

            $("#answertable").DataTable({
                iDisplayLength: 25,
                "scrollY": 390,
            });

            $("#btnExport").click(function(e) {
                let check = $('input[name=method]:checked').val();

                if (check == 'candidate') {
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
                    var table_div = document.getElementById('votingtable');
                    var table_html = table_div.outerHTML.replace(/ /g, '%20');
                    a.href = data_type + ', ' + table_html;
                    //setting the file name
                    a.download = 'Candidate_Voting_Record_' + postfix + '.xls';
                    //triggering the function
                    a.click();                    
                } else {
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
                    var table_div = document.getElementById('answertable_download');
                    var table_html = table_div.outerHTML.replace(/ /g, '%20');
                    a.href = data_type + ', ' + table_html;
                    //setting the file name
                    a.download = 'Answer_Voting_Record_' + postfix + '.xls';
                    //triggering the function
                    a.click();
                }
                //just in case, prevent default behaviour
                e.preventDefault();
            })

            $("#btnCandidateExport").click(function(e) {
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
                var table_div = document.getElementById('votingtable');
                var table_html = table_div.outerHTML.replace(/ /g, '%20');
                a.href = data_type + ', ' + table_html;
                //setting the file name
                a.download = 'Candidate_Voting_Record_' + postfix + '.xls';
                //triggering the function
                a.click();
                //just in case, prevent default behaviour
                e.preventDefault();
            })

            $("#btnAnswerExport").click(function(e) {
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
                var table_div = document.getElementById('answertable_download');
                var table_html = table_div.outerHTML.replace(/ /g, '%20');
                a.href = data_type + ', ' + table_html;
                //setting the file name
                a.download = 'Answer_Voting_Record_' + postfix + '.xls';
                //triggering the function
                a.click();
                //just in case, prevent default behaviour
                e.preventDefault();
            })
        })
    </script>
@endsection
