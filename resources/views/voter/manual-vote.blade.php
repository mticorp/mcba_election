@extends('layouts.app')

@section('content')
<div style="padding:100px 0px 198px 0px;" class="middle-card">
    <div class="card shadow" style="border: 3px solid #17a2b8;">
        <div class="card-body">
            <div class="py-2">
            <h2 class="text-center" style="font-family: 'Times New Roman', Times, serif">
                @if(isset($election->name))
                {{$election->name}}
                @else

                @endif
            </h2>
                <h5 class="text-center" style="font-family: 'Times New Roman', Times, serif; line-height:40px;">သဘောထားအတည်ပြုခြင်း</h5>
                <div class="py-3 text-center" id="time"></div>
                @if(session('status'))
                @php
                $status = session('status');
                @endphp
                <div class="alert alert-danger d-block text-center">
                    {{$status}}
                </div>
                @endif
            </div>
            <form action="{{route('vote.voter.login')}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="election_id" value="{{$election->id}}">
                <div class="form-group row">
                    <div class="col-md-3 pt-2 text-center">
                        <label for="VID" class="text-center">Voter ID:</label>
                    </div>
                    <div class="col-md-6 mb-2 text-center">
                        <input type="text" name="enter_voter_id" class="form-control" id="VID" style="border-radius:50px; width:100%!important" placeholder="Enter Voter ID here..." autofocus required>
                    </div>
                    <div class="col-md-3 text-center">
                        <input type="submit" value="Login" class="btn btn-info btn-block login-btn">
                    </div>
                </div>
                <div class="text-center pb-4">
                    <a href="{{route('voter.index')}}" style="text-decoration:underline;"><i class="fa fa-arrow-left"></i> Previous page</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('javascript')
<script>
    window.onload = function() {
        var input = document.getElementById("VID").focus();
    }


    $(document).ready(function() {
        var timeDisplay = document.getElementById("time");

        function refreshTime() {
            var dateString = new Date().toLocaleString("en-US", {
                timeZone: "Asia/yangon"
            });
            var formattedString = dateString.replace(", ", " - ");
            timeDisplay.innerHTML = formattedString;
        }
        setInterval(refreshTime, 10);

        $(function(){
        $('#VID').keyup(function() {
            if (this.value.match(/[^a-zA-Z0-9]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
            }
        });
    });
    });
</script>
@endsection
