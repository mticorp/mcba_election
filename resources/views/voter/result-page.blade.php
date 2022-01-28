@extends('layouts.app')
@section('style')
<style>
    .dataTables_wrapper .dataTables_processing {
        background-color: transparent;
        border-color: transparent;
        padding: 0px !important;
    }

    /* blockquote h1, blockquote h2, blockquote h3, blockquote h4, blockquote h5, blockquote h6 {
        color: #000!important;
    } */
</style>
@endsection
@section('content')
<div class="container-fluid px-lg-5 px-0 mb-5 pb-5" id="result-page">
    <div class="row mt-4 mx-0">
        <div class="col-sm-12 px-0">
            <div class="card card-info card-tabs">
                <div class="card-header">
                    <h3 class="card-title pt-2">
                        <i class="ion ion-clipboard mr-1"></i>
                        ရွေးချယ်ခြင်း ရလဒ်များ
                      </h3>
                    <div class="card-tools pt-2 pt-lg-0 float-left float-lg-right pl-5 pl-lg-0">
                        <button type="button" class="btn bg-light btn-sm" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            Minimize
                        </button>
                        @if (isset($voter_table_id))
                            <a href="{{route('voter.select.election')}}" class="btn bg-light btn-sm"><i class="fas fa-reply-all"></i> Back</a>
                        @else
                           <a href="{{route('voter.index')}}" class="btn bg-light btn-sm"><i class="fas fa-reply-all"></i> Back</a>
                       @endif
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mt-3" id="custom-tabs-two-tab" role="tablist">                       
                        @if($election->candidate_flag == 1)
                        <li class="nav-item">
                            <a class="nav-link" id="candidate-tab" data-toggle="pill" href="#candidate" role="tab" aria-controls="candidate" aria-selected="true">Candidate Result</a>
                        </li>
                        @endif
                        @if($election->ques_flag == 1)
                        <li class="nav-item">
                            <a class="nav-link" id="question-tab" data-toggle="pill" href="#question" role="tab" aria-controls="question" aria-selected="false">Question Result</a>
                        </li>
                        @endif                       
                    </ul>
                    <div class="tab-content" id="tabContent">
                        @if($election->candidate_flag == 1)
                        <div class="tab-pane fade table-responsive my-3" id="candidate" role="tabpanel" aria-labelledby="candidate-tab">
                            <table class="table table-bordered table-hover" id="candidate_resultTable" style="width:100%;">
                                <thead class="text-center">
                                    <th width="5%">No</th>
                                    <th width="15%">Photo</th>
                                    <th>Name</th>
                                    <th>Result</th>
                                </thead>
                                <tbody class="text-center">

                                </tbody>
                            </table>
                        </div>
                        @endif
                        @if($election->ques_flag == 1)
                        <div class="tab-pane fade my-3 table-responsive" id="question" role="tabpanel" aria-labelledby="question-tab">
                            @if(count($ans_result) > 0)
                            @foreach($ans_result as $result)
                            <blockquote class="quote-info text-dark">
                                {{$result->no}} {!! $result->ques !!}
                                <br>
                                <br>
                                <i class="fa fa-check" style="color: green;"></i> &nbsp;Yes - {{$result->yes_ans}} &nbsp;&nbsp;
                                <i class="fa fa-times" style="color: red;"></i> &nbsp;No - {{$result->no_ans}}
                            </blockquote>
                            @endforeach
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {

        $("#candidate-tab").click(function(){
            window.location.hash = "candidate";
        })

        $("#question-tab").click(function(){
            window.location.hash = "question";
            location.reload();
        })

        if(window.location.hash == "#question")
        {
            $("#question").addClass('show active');
            $("#question-tab").addClass('active');
        }else{
            var candidate_flag = "{{$election->candidate_flag}}";

            if(candidate_flag == 0)
            {
                $("#question").addClass('show active');
                $("#question-tab").addClass('active');
            }else{
                $("#candidate").addClass('show active');
                $("#candidate-tab").addClass('active');
            }           
        }

        var election_id = "{{$election->id}}";

        $('#candidate_resultTable').DataTable({
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            iDisplayLength: -1,
            processing: true,
            language: {
                processing: ' '
            },
            serverSide: true,
            ajax: {
                url: "{{ url('/vote/result') }}/" + election_id,
            },
            // ordering:'true',
            // order: [3, 'desc'],
            columns: [{
                    data: 'rownum',
                    name: 'rownum',
                    orderable: false,
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
                    name: 'mname',
                },
                {
                    data: 'result_vote_count',
                    name: 'result_vote_count'
                },
            ],
        });

        setInterval(function() {
            $('#candidate_resultTable').DataTable().ajax.reload();
        }, 3000);
    });
</script>
@endsection
