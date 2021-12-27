@extends('layouts.app')
@section('style')
<style>  
    #app-body{
        background-color:#17a2b8!important;
    }

    .footer{
        background-color: #fff!important;
        
    }

    #app-body .footer .footer-copyright a,strong{
        color:#000!important;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 text-center">
           <div class="mt-5 py-5 bg-dark container">
            <p class="text-white">Member Register လုပ်ဆောင်မှုအောင်မြင်ပါသည်။<br><br>
                ကျေးဇူးအထူးတင်ရှိပါသည်။</p>
                <a href="{{route('vote.member.register')}}" class="btn btn-info mt-3" id="close">Close</a>
           </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
    $(document).ready(function(){
        setTimeout(function() {
        var url = '{{ route("vote.member.register") }}';
        window.location.href = url;
    }, 3000);

    window.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("close").click();
        }
    })
    })
</script>
@endsection
