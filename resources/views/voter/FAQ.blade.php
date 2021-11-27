@extends('layouts.app')
@section('style')
<style>
    #FAQ .card {
        border: 4px solid gray;
        border-radius: 10px;
    }

    #FAQ .container {
        padding-top: 10px;
        padding-left: 30px;
    }

    /* Hide the browser's default radio button */
    #FAQ .container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Create a custom radio button */
    #FAQ .checkmark {
        position: absolute;
        top: 12px;
        left: 10px;
        height: 25px;
        width: 25px;
        background-color: #eee;
        border-radius: 50%;
    }

    /* On mouse-over, add a grey background color */
    #FAQ .container:hover input~.checkmark {
        background-color: #ccc;
    }

    /* When the radio button is checked, add a blue background */
    #FAQ .container input:checked~.checkmark {
        background-color: #2196F3;
    }

    /* Create the indicator (the dot/circle - hidden when not checked) */
    #FAQ .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the indicator (dot/circle) when checked */
    #FAQ .container input:checked~.checkmark:after {
        display: block;
    }

    /* Style the indicator (dot/circle) */
    #FAQ .container .checkmark:after {
        top: 9px;
        left: 9px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: white;
    }

</style>
@endsection
@section('content')
<div class="container-fluid mb-5 pb-5" id="FAQ">
    <div class="row mt-5">
        <div class="col-md-12">
            <h3 class="text-center">{{$election->ques_title ?? ''}}</h3>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
        <p class="text-center text-danger" style="font-weight:bold; font-size:20px;" id="error_msg"></p>
            @foreach($ques as $key => $que)
            <div class="card shadow-lg my-2 py-3">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="ques_id" value="{{$que->id}}">
                        <div class="col-lg-8">
                            <h5>{{$que->no}} <span style="line-height:35px;">{!! $que->ques !!}</span></h5>
                        </div>
                        <div class="col-lg-2">
                            <label class="container"> သဘောတူသည်။
                                <input type="radio" name="{{$que->id}}" value="1">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="col-lg-2">
                            <label class="container"> သဘောမတူပါ။
                                <input type="radio" name="{{$que->id}}" value="0">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="col-sm-12 text-center">
            <button type="button" class="btn btn-info" id="btn_submit">Submit</button>
        </div>       
    </div>
</div>
@endsection
@section('javascript')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>

$(document).ready(function(){
   
    var election_id = "{{$election->id}}";


     var data = [];
    $(".card").on('change', function() {
        data = [];
        $(".card").each(function() {
            var ques_id = $(this).find('input[name=ques_id]').val();
            var checked_val = $(this).find('input[type=radio]:checked').val();

            data.push({
                ques_id: ques_id,
                checked_val: checked_val
            });
        })      
    })

    $("#btn_submit").on('click', function() {
                
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
        var voter_table_id = "{{$voter_table_id}}";
        var i;
        if(data.length == 0)
        {
                toastr.error('Warning - Answer All Question')
                $.unblockUI();
                return false;
        }
        for (i = 0; i < data.length; i++) {            
            if(data[i].checked_val == null)
            {
                toastr.error('Warning - Answer All Question')
                $.unblockUI();
                return false;
            }
        }

        $.ajax({
            type: "post",
            url: "{{route('vote.faq.store')}}",
            data: {
                'dtData':data,
                'voter_table_id': voter_table_id,
                'election_id':election_id,
                "_token": "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function(data) {
                $.unblockUI();
                if(data.errors)
                {
                    toastr.error('Warning - '+ data.errors)
                }else if(data.success)
                {
                        var url = '{{ route("vote.complete", ["election_id" => ":election_id"]) }}';
                                                        url = url.replace(':election_id', data.election_id);

                    window.location.href = url;
                }
            },
            error: function(data){
                $.unblockUI();
                toastr.error('Warning - Something went wrong!')
            }
        });
        // $(".card").each(function(){
        //     var ques_id = $(this).find('input[name=ques_id]').val();
        //     var checked_val = $(this).find('input[type=radio]:checked').val();
        //     $.ajax({
        //         type: "post",
        //         url: "{{route('vote.faq.store')}}",
        //         data: {
        //             'ques_id': ques_id,
        //             'checked_val' : checked_val,
        //             'voter_table_id': voter_table_id,
        //             'election_id':election_id,
        //             "_token": "{{ csrf_token() }}",
        //         },
        //         dataType: "json",
        //         success: function(data) {
        //             $.unblockUI();
        //             if(data.errors)
        //             {
        //                 toastr.error('Warning - '+ data.errors)
        //             }else if(data.success)
        //             {
        //                  var url = '{{ route("vote.complete", ["election_id" => ":election_id"]) }}';
        //                                                     url = url.replace(':election_id', data.election_id);

        //                 window.location.href = url;
        //             }
        //         },
        //         error: function(data){
        //             $.unblockUI();
        //             toastr.error('Warning - Something went wrong!')
        //         }
        //     });
        // })
    })
})


</script>
@endsection
