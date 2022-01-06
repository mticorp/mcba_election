@extends('layouts.app')
@section('style')
<style>
    /* #app-body button {
            width: 150px;
        } */

    .se-pre-con {
        position: fixed;
        display: block;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 2;
        cursor: pointer;
    }

    .scroll {
        overflow-y: hidden !important;
    }

    #text {
        position: absolute;
        bottom: 50%;
        left: 46%;
        font-size: 18px;
        font-weight: bold;
        color: white;
        transform: translate(-30%, -30%);
        -ms-transform: translate(-30%, -30%);
    }

    #close {
        position: absolute;
        background: red;
        color: white;
        padding: 10px;
        width: 100px;
        height: 50px;
        bottom: 30%;
        left: 43%;
    }

    .none {
        display: none;
    }

    @media only screen and (max-width:600px) {
        #text {
            position: absolute;
            bottom: 10%;
            left: 45%;
            right: 0;
            font-size: 20px;
            color: white;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
        }

        #close {
            position: absolute;
            background: red;
            color: white;
            font-weight: bold;
            padding: 10px;
            width: 100px;
            height: 50px;
            bottom: 10%;
            left: 33%;
        }
    }
</style>
@endsection
@section('content')
<div class="none" id="popup">
    <div id="text">၁။ ကိုယ်စားလှယ်၏ ကိုယ်ရေးအချက်အလက် အသေးစိတ်တ်ကို သိရှိလိုပါက ဓာတ်ပုံကိုနှိပ်ပါ။ <br><br> ၂။
        ရွေးချယ်လိုပါက လေးထောင့်ကွက်ကို နှိပ်ပါ။ </div>
    <div id="close" class="text-center"> Got It</div>
</div>
<form action="#" method="post" id="myform">
    {{ csrf_field() }}
    <input type="hidden" name="voter_table_id" value="{{ $voter_table_id }}">
    <input type="hidden" name="voter_vote_count" value="{{ $voter_vote_count }}">
    <input type="hidden" name="election_id" value="{{ $election->id }}">
    <div class="row bg pt-1 pb-2 px-2 mx-0" id="top-head">
        <div class="col-lg-8 mx-0">
            <p class="yellow">{{ $election->description }}</p>
            <p class="yellow">သင်၏ မဲအရေအတွက်မှာ - {{ $voter_vote_count }} ဖြစ်ပါသည်။</p>
        </div>
        <div class="col-lg-2 mt-1 mx-0">
            <button class="btn btn-danger abtn" type="button">သင်ရွေးချယ်ပြီး - <span id="count-checked-checkboxes"></span> / {{ $position }}</button>
        </div>
        <div class="col-lg-2 mt-1 mx-0">
            <input type="button" id="btnVote" class="btn btn-info abtn" value="ရွေးချယ်ပြီးလျှင်နှိပ်ပါ">
        </div>
    </div>

    <div class="container-fluid mb-5">
        <div class="row">
            @if (isset($candidates))
            @foreach ($candidates as $candidate)
            <div class="col-lg-6 my-2" id="{{ $candidate->id }}">
                <div class="card shadow card-height-fixed">
                    <div class="check_div">

                        <div class="col-lg-12 mt-1 mx-0 px-0" id="checkbox_div">
                            <input type="checkbox" class="float-right vote_check" name="candidate[]" value="{{ $candidate->id }}" data-id="{{ $candidate->id }}">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row pb-4">
                            <div class="col-sm-3 text-center responsive">
                                <a href="{{ route('vote.candidate.detail', ['election_id' => $election->id, 'id' => $candidate->id]) }}" class="a">
                                    @if ($candidate->photo_url)
                                    <img src="{{ url($candidate->photo_url) }}" data-toggle="tooltip" data-placement="bottom" title="အသေးစိတ်ကြည့်ရှုရန်" class="reponsive-image" width='100%' height='125px' alt="">
                                    @else
                                    <img src="{{ url('/images/user.png') }}" data-toggle="tooltip" data-placement="bottom" title="အသေးစိတ်ကြည့်ရှုရန်" class="reponsive-image" width='100%' height='125px' alt="">
                                    @endif
                                </a>
                            </div>
                            <div class="col-sm-9">
                                <div class="row py-2 mx-2">
                                    <div class="col-6">
                                        <strong>ကိုယ်စားလှယ်လောင်းအမှတ်</strong>
                                    </div>
                                    <div class="col-6">
                                        <strong>{{ $candidate->candidate_no }}</strong>
                                    </div>
                                </div>
                                <hr class="my-1 mx-0 py-0 bg-secondary">
                                <div class="row py-2 mx-2">
                                    <div class="col-6">
                                        <strong>အမည်</strong>
                                    </div>
                                    <div class="col-6">
                                        <strong id="name">{{ $candidate->mname }}</strong>
                                    </div>
                                </div>
                                <hr class="my-1 mx-0 py-0 bg-secondary">
                                <div class="row py-2 mx-2">
                                    <div class="col-6">
                                        <strong>လုပ်ငန်းအမည်</strong>
                                    </div>
                                    <div class="col-6">
                                        <strong>{{ $candidate->company }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
</form>
</div>
<!-- Modal -->
<!-- Confirm Voting Modal -->


<!-- Full Voting Limit Modal -->
<div class="modal fade" id="alertModel" tabindex="-1" role="dialog" aria-labelledby="alertDataModel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertDataModel">သတိပေးချက်</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                သတ်မှတ်ထားသောအရေအတွက်ထက် ကျော်လွန်နေပါသည်။
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-25" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
    $(document).ready(function() {

        var got_it = localStorage.getItem("confirm");

        if (got_it == "true") {
            $("#popup").hide();
        } else {
            $("#popup").removeClass('none');
            $("#popup").addClass('se-pre-con');
            $("#checkbox_div").addClass('sticky-top');
            $("#checkbox_div").find("input[type=checkbox]").attr('disabled', 'disabled');

            $("body").addClass('scroll');
        }

        $("#close").click(function() {
            localStorage.setItem("confirm", "true");
            $("#popup").addClass('none');
            $("#top-head").addClass('sticky-top');
            $("#checkbox_div").removeClass('sticky-top');
            $("#checkbox_div").find("input[type=checkbox]").removeAttr('disabled', 'disabled');
            $("#top-head").show();
            $("body").removeClass('scroll');
        })

        window.addEventListener("keydown", function(event) {
            if (event.keyCode == 81 && event.ctrlKey) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "{{ route('vote.changestatus') }}",
                    data: $('#myform').serialize(),
                    dataType: "json",
                    success: function(data) {
                        if (data.errors) {
                            sessionStorage.clear();
                            localStorage.clear();
                            window.location.href = "{{ route('vote.already') }}";
                        } else {
                            sessionStorage.clear();
                            localStorage.clear();
                            var url = "{{ route('vote.complete', ['election_id' => ':election_id']) }}";
                            url = url.replace(':election_id', data.election_id);

                            window.location.href = url;
                        }
                    }
                });
            }
        }, true);

        $('[data-toggle="tooltip"]').tooltip();

        var position = "{{ $position }}";
        var electionId = "{{ $election->id }}";
        $('input:checkbox').each(function() {
            var $el = $(this);
            if (sessionStorage[$el.prop('value')]) {
                var data = JSON.parse(sessionStorage[$el.prop('value')]);
                if (electionId == data.election_id) {
                    $el.prop('checked', data.check === true);
                }
                for (var i = 0; i < sessionStorage.length; i++) {
                    if (sessionStorage.key(i) != $el.prop('value')) {
                        var value = JSON.parse(sessionStorage[sessionStorage.key(i)]);
                        if (value.election_id != electionId) {
                            sessionStorage.removeItem(sessionStorage.key(i));
                        }
                    }
                }
            } else {
                for (var i = 0; i < sessionStorage.length; i++) {
                    if (sessionStorage.key(i) != $el.val()) {
                        var value = JSON.parse(sessionStorage[sessionStorage.key(i)]);
                        if (value.election_id != electionId) {
                            sessionStorage.removeItem(sessionStorage.key(i));
                        }
                    }
                }
            }
        });

        $('#count-checked-checkboxes').text(sessionStorage.length);
        $('input:checkbox').on('change', function() {
            var selected = $("input[type=checkbox]:checked").length;
            if (selected <= position) {
                $('#count-checked-checkboxes').text(selected);
            }
            if (selected > position) {
                this.checked = false;
                $('#alertModel').modal('show');
            }

            var $el = $(this);
            if ($el.prop("checked") == true) {
                var obj = {
                    'check': $el.is(':checked'),
                    'election_id': electionId,
                };

                sessionStorage[$el.prop('value')] = JSON.stringify(obj);
            } else if ($el.prop("checked") == false) {
                sessionStorage.removeItem($el.prop('value'));
            }
        });


        $("#btnVote").click(function() {
            $.ajax({
                type: "POST",
                url: "{{ route('voter.change.status') }}",
                data: $('#myform').serialize() + "&_token={{ csrf_token() }}",
                dataType: "json",
                success: function(data) {
                    if (data.errors) {
                        console.log(data.errors);
                    }
                }
            });
            var selected = $("input[type=checkbox]:checked").length;

            var candidate_array = [];
            $('input[type=checkbox]:checked').each(function() {
                var $el = $(this);
                var select_item = $el.val();
                var key = 0;
                $(".card").each(function() {                    
                    var selected_id = $(this).find("input[type=checkbox]").val();
                    if (selected_id == select_item) {
                        key = key + 1;
                        var selected_name = $(this).find("#name").text();
                        candidate_array.push('<br>' + key + '. ' + selected_name);
                    }
                })
            });

            if (selected <= 0) {
                $.confirm({
                    title: '',
                    content: 'မဲပေးရန်သေချာပါသလား ?',
                    type: 'blue',
                    typeAnimated: true,
                    onOpen: function() {
                        $(".btn-blue").focus();
                    },
                    buttons: {
                        tryAgain: {
                            text: 'OK',
                            btnClass: 'btn-blue',
                            action: function() {
                                $.confirm({
                                    title: '',
                                    content: 'မည့်သည့်ကိုယ်စားလှယ်လောင်းကိုမျှရွေးချယ်ထားခြင်းမရှိပါ <br> မဲပေးရန်သေချာပါသလား ?',
                                    type: 'blue',
                                    typeAnimated: true,
                                    onOpen: function() {
                                        $(".btn-blue").focus();
                                    },
                                    buttons: {
                                        tryAgain: {
                                            text: 'OK',
                                            btnClass: 'btn-blue',
                                            action: function() {
                                                $(".btn-blue").attr('disabled',
                                                    'disabled');
                                                $.ajax({
                                                    type: "POST",
                                                    url: "{{ route('vote.store') }}",
                                                    data: $('#myform')
                                                        .serialize() +
                                                        "&_token={{ csrf_token() }}",
                                                    dataType: "json",
                                                    success: function(data) {                                                        
                                                        if(data.election_notFound || data.voter_notFound)
                                                        {
                                                            sessionStorage.clear();
                                                            localStorage.clear();
                                                            window.location.href = "{{ route('vote.unauthorized') }}";
                                                        }else if(data.errors){
                                                            sessionStorage.clear();
                                                            localStorage.clear();
                                                            window.location.href = "{{ route('vote.already') }}";
                                                        }else if(data.success){
                                                            if(data.ques_flag)
                                                            {
                                                                //redirect ques route
                                                                sessionStorage.clear();
                                                                localStorage.clear();
                                                                var url = "{{ route('vote.faq', ['election_id' => ':election_id']) }}";
                                                                url = url.replace(':election_id', data.election_id);

                                                                window.location.href = url;
                                                            }else{
                                                                //redirect complete route
                                                                sessionStorage.clear();
                                                                localStorage.clear();
                                                                var url = "{{ route('vote.complete', ['election_id' => ':election_id']) }}";
                                                                url = url.replace(':election_id', data.election_id);

                                                                window.location.href = url;
                                                            }
                                                        }
                                                    }
                                                });
                                            }
                                        },
                                        cancel: function() {}
                                    }
                                });
                            }
                        },
                        cancel: function() {}
                    }
                });
            } else {
                $.confirm({
                    title: '',
                    content: candidate_array.join('၊') +
                        ' တို့ကို ရွေးချယ်ထားပါသည်။ <br>မဲပေးရန်သေချာပါသလား ?',
                    type: 'blue',
                    typeAnimated: true,
                    onOpen: function() {
                        $(".btn-blue").focus();
                    },
                    buttons: {
                        tryAgain: {
                            text: 'OK',
                            btnClass: 'btn-blue',
                            action: function() {
                                $(".btn-blue").attr('disabled', 'disabled');
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('vote.store') }}",
                                    data: $('#myform').serialize() +
                                        "&_token={{ csrf_token() }}",
                                    dataType: "json",
                                    success: function(data) {
                                        if(data.election_notFound || data.voter_notFound)
                                        {
                                            sessionStorage.clear();
                                            localStorage.clear();
                                            window.location.href = "{{ route('vote.unauthorized') }}";
                                        }else if(data.errors){
                                            sessionStorage.clear();
                                            localStorage.clear();
                                            window.location.href = "{{ route('vote.already') }}";
                                        }else if(data.success){
                                            if(data.ques_flag)
                                            {
                                                //redirect ques route
                                                sessionStorage.clear();
                                                localStorage.clear();
                                                var url = "{{ route('vote.faq', ['election_id' => ':election_id']) }}";
                                                url = url.replace(':election_id', data.election_id);

                                                window.location.href = url;
                                            }else{
                                                //redirect complete route
                                                sessionStorage.clear();
                                                localStorage.clear();
                                                var url = "{{ route('vote.complete', ['election_id' => ':election_id']) }}";
                                                url = url.replace(':election_id', data.election_id);

                                                window.location.href = url;
                                            }
                                        }
                                    }
                                });
                            }
                        },
                        cancel: function() {}
                    }
                });
            }
        })
    });
</script>
@endsection