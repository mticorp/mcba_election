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
                        <li class="breadcrumb-item active">Answer</li>
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
                                Answers List
                            </h3>
                            <div class="card-tools">
                                <a id="btnExport" href="" class="btn btn-flat btn-success"><i class="fa fa-download"
                                        aria-hidden="true"></i> Download Excel </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="answertable" border="2px" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Voter ID</th>
                                        @foreach ($ques as $que)
                                            <th style="text-align:center;">
                                                <a class="mytooltip"
                                                    style="text-decoration: none; color:black!important; line-height:20px!important;"
                                                    id="{{ $que->id }}myTooltip" title="{{ $que->ques }}">Q -
                                                    {{ $que->no }}</a>
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
                                                    Yes - {{ $count->yes_ans }}
                                                    No - {{ $count->no_ans }}
                                                </th>
                                            @endforeach
                                        @else
                                            <th></th>
                                            <th></th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            // var bootstrapTooltip = $.fn.tooltip.noConflict();
            // $(".mytooltip").each(function(){
            //     var id = $(this).attr('id');
            //     var name = "#" + id;
            //     // console.log(name);
            //     $.fn.bstooltip = bootstrapTooltip;
            //     $(name).bstooltip();
            // })


            $("#answertable").DataTable({
                iDisplayLength: 25,
                "scrollY": 390,
            });

            $("#btnExport").click(function(e) {
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
                a.download = 'Answer_' + postfix + '.xls';
                //triggering the function
                a.click();
                //just in case, prevent default behaviour
                e.preventDefault();
            })
        })
    </script>
@endsection
