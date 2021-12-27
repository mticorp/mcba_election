@extends('layouts.app')
@section('style')
<style>
    #app-body #complete_page {
        margin: 0 auto;
        padding: 10px;
        background: #17a2b8!important;
        color: #fff!important;        
    }

    #app-body{
        background-color:#17a2b8!important;
    }

    .footer{
        background-color: #fff!important;
        
    }

    #app-body .footer .footer-copyright a,strong{
        color:#000!important;
    }

    .fieldset{
        display: block; */
        margin-inline-start: 2px;
        margin-inline-end: 2px;
        padding-block-start: 0.35em;
        padding-inline-start: 0.75em;
        padding-inline-end: 0.75em;
        padding-block-end: 0.625em;
        min-inline-size: min-content;
        border-width: 2px;
        border-style: groove;
        border-color: white;
        border-image: initial;
        /* display:table-cell!important; 
        width: 100%!important; */
    }

    font{
        word-break: break-all!important;
    }
</style>
@endsection
@section('content')
<div class="container mb-5">
    <div class="row mb-5 pb-5">
        <div class="col-12 text-center" id="complete_page">
            <h3>{{$election->company_name}}</h3>
            <img src="{{url('images/voted.png')}}" width="200px" class="py-2 mb-2">
            <p> လူကြီးမင်း၏ မဲပေးမှု အစီအစဉ် ပြီးဆုံးပါပြီ။</p>
                <p>လူကြီးမင်း၏ မဲပေးမှုအတွက် ကျေးဇူးအထူးတင်ရှိပါသည်။</p>
            
            <div class="text-left fieldset">
                <p>Transaction ID - {{$voter->VId}} ဖြင့် ရွေးချယ်ခဲ့သော </p>
                
                @if (count($candidates) > 0)                
                <p class="pl-1">ကိုယ်စားလှယ်လောင်းများ</p>                
                    @foreach($candidates as $key => $candidate) 
                        <p><i class="fas fa-user-circle" style="font-size:20px;"></i> {{$key+1}}.  {{$candidate->mname}}</p>
                    @endforeach
                @endif

                @if (count($answers) > 0)                
                <p class="pl-1">မေးခွန်းများ</p>
                @foreach ($answers as $ans)                    
                    <div class="my-2 fieldset">
                        <p class="text-white" style="width:100%!important;"><i class="fa fa-question-circle"></i> {!! $ans->ques !!}</p>
                        <p>
                            <i class="fab fa-adn"></i>
                            @if ($ans->ans == 1)
                                သဘောတူပါသည်။
                            @else
                                သဘောမတူပါ။
                            @endif
                        </p>
                    </div>
                @endforeach
                @endif
                {{-- @if(count($candidates) > 0 && count($candidates) != 1)
                <p>Transaction ID - {{$voter->VId}} ဖြင့် @foreach($candidates as $candidate) {{$candidate->mname}}၊ @endforeach တို့ကို  မဲ ပေးခဲ့ပါသည်။</p>
                @elseif(count($candidates) == 1)
                <p>Transaction ID - {{$voter->VId}} ဖြင့် @foreach($candidates as $candidate) {{$candidate->mname}} @endforeach ကို  မဲ ပေးခဲ့ပါသည်။</p>
                @else
                <p>Transaction ID - {{$voter->VId}} ဖြင့် မည်သူမျှကို မဲပေးခဲ့ခြင်းမရှိပါ။</p>
                @endif
                <hr style="border:1px dashed white;s">
                @if (count($answers) > 0)    
                <p class="text-left px-5" style="line-height:30px; color:white!important;">            
                    @foreach ($answers as $ans)
                        {!! $ans->ques !!} <br> ဟူသောမေးခွန်းအား
                            @if ($loop->last)
                            @if ($ans->ans == 1)
                            သဘောတူကြောင်း<br>
                            @else
                            သဘောမတူကြောင်း<br>
                            @endif    
                            @else
                            @if ($ans->ans == 1)
                            သဘောတူကြောင်း၊ <br>
                            @else
                            သဘောမတူကြောင်း၊ <br>
                            @endif    
                            @endif                    
                    @endforeach   
                    စသည်ဖြင့်ဖြေဆိုခဲ့ပါသည်။   
                </p>          
                @endif --}}
            </div>
            
            <p id="lucky_code"></p>
            @if($voter->isVerified == 1)
            <a href="{{route('voter.select.election')}}" class="btn btn-light text-info" id="close">Close</a>
            @else
            <a href="{{route('vote.result-page',$election->id)}}" class="btn btn-light text-info" id="close">Close</a>
            @endif
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
function generateVid(length) {
        var result = '';
        var digits = '0123456789';
        var digitsLength = digits.length;
        for (var i = 0; i < length; i++) {
            result += digits.charAt(Math.floor(Math.random() * digitsLength));
        }

        return result;
    }
    $(document).ready(function(){
        var election_id = "{{$election->id}}";
        var election_lucky = "{{$election->lucky_flag}}";
        var voter_id = "{{$voter->id}}";
        var voterID_No = "{{$voter->voter_id}}";
        var voter_verify = "{{$voter->isVerified}}";
        if(voter_verify == 1)
        {
            var url = '{{ route("voter.select.election") }}';                    
        }else{
            var url = '{{ route("vote.result-page", ":election_id") }}';
                    url = url.replace(':election_id', election_id);
        }
        if(election_lucky == 1)
        {
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{route("lucky.code.store")}}',
                data: {
                'voter_id' : voter_id,
                'election_id': election_id
                },
                success: function(data) {
                    // console.log(data);
                    if(data.errors)
                    {
                        // console.log(data.errors);
                    }
                    else if(data.success)
                    {
                        $("#lucky_code").html("Your Lucky Draw Code is - <u>" + data.code +"</u>. Please Take screenshot for evidence!");
                    }
                }
            });
        }else{
            // setTimeout(function() {
            //     window.location.href = url;
            // }, 5000);
        }

    window.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("close").click();
        }
    })
    })
</script>
@endsection
