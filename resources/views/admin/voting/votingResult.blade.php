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
                        <li class="breadcrumb-item active">Voting Result</li>
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
                                Voting Results List
                            </h3>
                            <div class="card-tools">
                                @if ($election->candidate_flag == 1 && $election->ques_flag == 1)
                                    <a class="btn btn-flat btn-success" data-toggle="modal" data-target="#confirmModal"><i
                                            class="fa fa-download" aria-hidden="true"></i> Download Excel </a>
                                    <button type="button" class="btn btn-flat btn-info" data-toggle="modal"
                                        data-target="#print_confirmModal"><i class="fa fa-print" aria-hidden="true"></i>
                                        Print</button>
                                @elseif ($election->candidate_flag == 1)
                                    <a class="btn btn-flat btn-success" data-toggle="modal" data-target="#ExcelModal"><i
                                            class="fa fa-download" aria-hidden="true"></i> Download Excel </a>
                                    <button type="button" id="btn_candidate_print" class="btn btn-flat btn-info"><i
                                            class="fa fa-print" aria-hidden="true"></i> Print</button>
                                @else
                                    <a href="{{ route('admin.question-excel.download', ['election_id' => $election->id]) }}"
                                        class="btn btn-flat btn-success"><i class="fa fa-download" aria-hidden="true"></i>
                                        Download Excel </a>
                                    <button type="button" id="btn_question_print" class="btn btn-flat btn-info"><i
                                            class="fa fa-print" aria-hidden="true"></i> Print</button>
                                @endif
                                <button type="submit" id="btn_export" style="display: none;"> Export</button>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <ul class="nav nav-tabs" id="tab" role="tablist">
                                @if ($election->candidate_flag == 1)
                                    <li class="nav-item">
                                        <a class="nav-link" id="candidate-tab" data-toggle="pill" href="#candidate"
                                            role="tab" aria-controls="candidate" aria-selected="true">Candidate
                                            Result</a>
                                    </li>
                                @endif
                                @if ($election->ques_flag == 1)
                                    <li class="nav-item">
                                        <a class="nav-link" id="answer-tab" data-toggle="pill" href="#answer"
                                            role="tab" aria-controls="answer" aria-selected="false">Answer
                                            Result</a>
                                    </li>
                                @endif
                            </ul>
                            <div class="tab-content" id="tabContent">
                                @if ($election->candidate_flag == 1)
                                    <div class="tab-pane fade pt-3" id="candidate" role="tabpanel"
                                        aria-labelledby="candidate-tab">
                                        <div class="col-xs-12">
                                            <p style="letter-spacing:0px;"><i class="fa fa-tag"></i> Voting Record -
                                                {{ $voting_count }} <span style="padding-right:20px;"></span> <i
                                                    class="fa fa-tag"></i> Reject Voting Record -
                                                {{ $voting_reject_count }}
                                                <span style="padding-right:20px;"></span> <i class="fa fa-tag"></i> Not
                                                Voted
                                                Record
                                                - {{ $not_voted_count }}
                                            </p>
                                        </div>
                                        <table id="votingResultTable" border="2px" style="width: 100%!important;"
                                            class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>Profile</th>
                                                    <th>Candidate Name</th>
                                                    <th>Result</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                @if ($election->ques_flag == 1)
                                    <div class="tab-pane fade pt-3" id="answer" role="tabpanel"
                                        aria-labelledby="answer-tab">
                                        <table id="questionResultTable" border="2px" style="width: 100%!important;"
                                            class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>Image</th>
                                                    <th>Questions</th>
                                                    <th>Result</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
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

    @if ($election->candidate_flag == 1)
        <div style="display:none;">
            <div id="candidate_print_content" style="margin-bottom:0px;">
                <h3 style="text-align: center; font-weight:bold; line-height:50px;">{{ $election->name }}</h3>
                <h4 style="text-align: center; line-height:50px;">မြန်မာနိုင်ငံအကောက်ခွန်ဝန်ဆောင်မှုလုပ်ငန်းရှင်များအသင်း၏ (၂၀၂၂-၂၀၂၄)ခုနှစ် အလုပ်အမှုဆောင်သက်တမ်းအတွက် အလုပ်အမှုဆောင်ရွေးချယ်ခံရသူများစာရင်း</h4>
                <br><br>
                <p>Date : {{ Carbon\Carbon::now()->format('d-m-Y g:i A') }}</p>
                <br>
                <table style="border-collapse: collapse;width: 100%;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd;text-align: center;">စဉ်</th>
                            <th style="border: 1px solid #ddd;text-align: center;">ကိုယ်စားလှယ်လောင်းအမှတ်</th>
                            <th style="border: 1px solid #ddd;text-align: center;">ဓာတ်ပုံ</th>
                            <th style="border: 1px solid #ddd;text-align: center;">ကိုယ်စားလှယ်လောင်း အမည်</th>
                            <th style="border: 1px solid #ddd;text-align: center;">မဲ အရေအတွက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $value)
                            <tr>
                                <td style="border: 1px solid #ddd;text-align: center;">{{ $key + 1 }}</td>
                                <td style="border: 1px solid #ddd;text-align: center;">{{ $value->candidate_no }}</td>
                                <td style="border: 1px solid #ddd;text-align: center;">@if ($value->photo) <img src="{{ url($value->photo) }}" alt="img" width="45px" height="50px" style="padding:2px 2px 2px 2px;"> @else <img src="{{ url('images/user.png') }}" alt="img" width="45px" height="50px" style="padding:2px 2px 2px 2px;"> @endif</td>
                                <td style="border: 1px solid #ddd;text-align: center;">{{ $value->mname }}</td>
                                <td style="border: 1px solid #ddd;text-align: center;">{{ $value->vote_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
        </div>
    @endif

    @if ($election->ques_flag == 1)
        <div style="display:none;">
            <div id="question_print_content" style="margin-bottom:0px;">
                <h3 style="text-align: center; font-weight:bold; line-height:50px;">{{ $election->name }}</h3>
                <h4 style="text-align: center;">မေးခွန်းတစ်ခုချင်းစီ၏ မဲရလဒ် အဖြေများ</h4>
                <br><br>
                <p>Date : {{ Carbon\Carbon::now()->format('d-m-Y g:i A') }}</p>
                <br>
                <table style="border-collapse: collapse;width: 100%;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd;text-align: center;">စဉ်</th>
                            <th style="border: 1px solid #ddd;text-align: center;">မေးခွန်း</th>
                            <th style="border: 1px solid #ddd;text-align: center;">သဘောတူပါသည်</th>
                            <th style="border: 1px solid #ddd;text-align: center;">သဘောမတူပါ</th>
                            <th style="border: 1px solid #ddd;text-align: center;">စုစုပေါင်း အရေအတွက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questions as $key => $question)
                            <tr>
                                <td style="border: 1px solid #ddd;text-align: center;">{{ $key + 1 }}</td>
                                <td style="border: 1px solid #ddd;text-align: center;">{!! $question->ques !!}</td>
                                <td style="border: 1px solid #ddd;text-align: center;">{{ $question->yes_count }}
                                </td>
                                <td style="border: 1px solid #ddd;text-align: center;">{{ $question->no_count }}
                                </td>
                                <td style="border: 1px solid #ddd;text-align: center;">
                                    {{ $question->yes_count + $question->no_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
        </div>
    @endif

    <div style="display: none;">
        <table id="result_excel" style="border: 1px solid black;">
            <thead id="excel_th">

            </thead>
            <tbody id="excel_rs">

            </tbody>
        </table>
    </div>

    <div class="modal fade" id="ExcelModal" tabindex="-1" role="dialog" aria-labelledby="aa" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addExcel">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <label for="count">Number of Rows</label>
                        <input type="text" name="count" class="form-control">
                        <input type="hidden" name="type">
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-info btn-flat" id="btn_downloadExcel" value="Download">
                        <button type="button" class="btn btn-flat btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (($election->candidate_flag == 1) & ($election->ques_flag == 1))
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
                                            <span class="label bg-primary"><i class="fas fa-file-excel"></i> Candidate
                                                Record
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
                                <button type="button" id="btnExport" class="btn btn-success btn-flat btn-sm"
                                    data-dismiss="modal" data-toggle="modal" data-target="#ExcelModal"><i
                                        class="fa fa-save"></i>
                                    Select</button>
                                <button type="button" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal"><i
                                        class="fa fa-reply-all"></i> Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="print_confirmModal" tabindex="-1" role="dialog"
            aria-labelledby="print_confirmModalTitle" aria-hidden="true">
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
                                        <input type="radio" name="method" checked id="radioSuccess3" value="candidate">
                                        <label for="radioSuccess3">
                                            <span class="label bg-primary"><i class="fas fa-file-excel"></i> Candidate
                                                Record
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="icheck-success d-inline">
                                        <input type="radio" name="method" id="radioSuccess4" value="answer">
                                        <label for="radioSuccess4">
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
                                <button type="button" id="btn_print" class="btn btn-success btn-flat btn-sm"
                                    data-dismiss="modal"><i class="fa fa-save"></i>
                                    Select</button>
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

            var election_id = "{{ $election->id }}";

            if (candidate_flag == 1) {
                $('#votingResultTable').DataTable({
                    processing: true,
                    language: {
                        processing: ' '
                    },
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.election.get-result') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            election_id: election_id
                        }
                    },
                    columns: [{
                            data: 'rownum',
                            name: 'rownum',
                        },
                        {
                            data: 'photo_url',
                            name: 'photo_url',
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
                            data: 'mname',
                            name: 'mname'
                        },
                        {
                            data: 'vote_count',
                            name: 'vote_count',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false
                        }
                    ],
                });
            }

            if (ques_flag == 1) {
                $('#questionResultTable').DataTable({
                    processing: true,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                    },
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.election.get-ques-result') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            election_id: election_id
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            "width": "5%",
                        },
                        {
                            data: 'image',
                            name: 'image',
                            render: function(data, type, full, meta) {
                                if (data != null && data != 'null') {
                                    return "<img src={{ URL::to('/') }}" + data +
                                        " width='70' class='img-thumbnail' />";
                                } else {
                                    return '';
                                }
                            },
                            orderable: false
                        },
                        {
                            data: 'ques',
                            name: 'ques',
                            render: function(data) {
                                var body;
                                try {
                                    body = document.implementation.createHTMLDocument().body;
                                } catch (e) {
                                    body = document.createElement("body");
                                }
                                body.innerHTML = data;
                                return $(body).text();
                            },
                            "width": "50%",
                        },
                        {
                            data: 'question_title',
                            name: 'question_title',
                            render: function(data, type, full, meta) {
                                return html =
                                    `<span class="text-success">Yes - ${full.yes_count}</span> / <span class="text-danger">No - ${full.no_count}</span>`;
                            },
                            "width": "10%",
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false
                        }
                    ],
                });
            }

            setInterval(function() {
                $('#votingResultTable').DataTable().ajax.reload();
            }, 3000);

            $(document).on('click', '#votingResultTable .detail', function() {
                var candidate_id = $(this).attr('id');
                var url =
                    "{{ route('admin.election.votingresult.show', ['candidate_id' => ':candidate_id', 'election_id' => ':election_id']) }}";
                url = url.replace(':candidate_id', candidate_id);
                url = url.replace(':election_id', election_id);

                window.location.href = url;
            })

            $(document).on('click', '#questionResultTable .detail', function() {
                var question_id = $(this).attr('id');
                var url =
                    "{{ route('admin.election.question-votingresult.show', ['question_id' => ':question_id', 'election_id' => ':election_id']) }}";
                url = url.replace(':question_id', question_id);
                url = url.replace(':election_id', election_id);

                window.location.href = url;
            })

            $('#btnExport').on('click', function() {
                $("input[name=type]").val($(this).closest('.modal-body').find('input[name=method]:checked')
                    .val());
            })

            $('#btn_print').on('click', function() {
                let type = $(this).closest('.modal-body').find('input[name=method]:checked').val();

                type == 'candidate' ? $.print("#candidate_print_content") : $.print(
                    "#question_print_content");
            })

            $('#btn_candidate_print').on('click', function() {
                $.print("#candidate_print_content");
            })

            $('#btn_question_print').on('click', function() {
                $.print("#question_print_content");
            })

            $("#btn_downloadExcel").click(function() {
                if (candidate_flag == 1 && ques_flag == 1) {
                    var type = $('input[name=type]').val();
                } else if (candidate_flag == 1) {
                    var type = 'candidate';
                } else {
                    var type = 'answer';
                }

                let count = $('input[name=count]').val();
                $.ajax({
                    type: "get",
                    url: type == 'candidate' ? "{{ route('admin.candidate-excel.download') }}" :
                        "{{ route('admin.question-excel.download', ['election_id' => $election->id]) }}",
                    data: {
                        election_id: election_id,
                        count: count,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);
                        // return false;                                             
                        $('#ExcelModal').modal('hide');

                        if (type == 'candidate') {
                            $("#excel_th").html(`<tr>
                                <th>စဥ်</th>
                                <th>ကိုယ်စားလှယ်လောင်း အမှတ်</th>                        
                                <th>နာမည်</th>                                                        
                                <th>မဲအရေအတွက်</th>
                            </tr>`);

                            $.each(data, function(i, v) {
                                i = i + 1;
                                $("#excel_rs").append('<tr>' +
                                    '<td>' + i + '</td>' +
                                    '<td>' + v.candidate_no + '</td>' +
                                    '<td>' + v.mname + '</td>' +                                    
                                    '<td>' + v.vote_count + '</td>' +
                                    '</tr>');
                            });
                        } else {
                            $("#excel_th").html(`<tr>
                                <th>စဥ်</th>
                                <th>မေးခွန်း</th>
                                <th>မဲအရေအတွက်</th>
                            </tr>`);

                            $.each(data, function(i, v) {
                                let body;
                                try {
                                    body = document.implementation.createHTMLDocument()
                                        .body;
                                } catch (e) {
                                    body = document.createElement("body");
                                }
                                body.innerHTML = v.question_title;
                                console.log($(body).text());
                                i = i + 1;
                                $("#excel_rs").append('<tr>' +
                                    '<td>' + i + '</td>' +
                                    '<td>' + $(body).text() + '</td>' +
                                    '<td>' + 'Yes - ' + v.yes_ans + ' / No - ' + v
                                    .no_ans + '</td>' +
                                    '</tr>');
                            });
                        }

                        $("#btn_export").click();
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            })

            $("#btn_export").click(function(e) {
                if (candidate_flag == 1 && ques_flag == 1) {
                    var type = $('input[name=type]').val();
                } else if (candidate_flag == 1) {
                    var type = 'candidate';
                } else {
                    var type = 'answer';
                }
                var dt = new Date();
                var day = dt.getDate();
                var month = dt.getMonth() + 1;
                var year = dt.getFullYear();
                var hour = dt.getHours();
                var mins = dt.getMinutes();
                var postfix = day + "." + month + "." + year + "_" + hour + "_" + mins;
                //creating a temporary HTML link element (they support setting file names)
                var a = document.createElement('a');
                //getting data from our div that contains the HTML table
                var data_type = 'data:application/vnd.ms-excel';
                var table_div = document.getElementById('result_excel');
                var table_html = table_div.outerHTML.replace(/ /g, '%20');
                a.href = data_type + ', ' + table_html;
                //setting the file name
                type == 'answer' ? a.download = 'Answer_Voting_Result_' + postfix + '.xls' : a.download =
                    'Candidate_Voting_Result_' + postfix + '.xls';
                //triggering the function
                a.click();
                //just in case, prevent default behaviour
                e.preventDefault();
            })
        })
    </script>
@endsection
