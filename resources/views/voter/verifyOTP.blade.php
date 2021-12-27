@extends('layouts.app')

@section('content')
    <div style="padding:100px 0px 198px 0px;" class="middle-card">
        <div class="card shadow" style="border: 3px solid #17a2b8;">
            <div class="card-body">
                <div class="py-2">
                    <h2 class="text-center" style="font-family: 'Times New Roman', Times, serif">
                        Online Election Voting
                    </h2>
                    <h5 class="text-center" style="font-family: 'Times New Roman', Times, serif; line-height:40px;"></h5>
                    <div class="m-md-5 text-center" id="time"></div>
                    {!! Session::has('status')
                    ? '<div class="alert alert-danger d-block text-center mt-4">'
                        .Session::get('status') .
                        '</div>'
                    : '' !!}
                </div>
                <form action="{{ route('voter.verifyOtp') }}" method="post" id="verifyOtp">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group row">
                        <div class="col-md-12 pt-2 text-center">
                            <label for="VID" class="text-center">OTP CODE:</label>
                        </div>
                        <div class="col-md-12 mb-2 text-center">
                            <input type="text" name="otp" class="form-control"
                                style="border-radius:50px; width:100%!important" placeholder="Enter OTP code here..."
                                autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-center">
                            <input type="submit" value="VOTE" id="btn_vote" class="btn btn-success btn-block login-btn">
                        </div>
                    </div>
                </form>
                <form action="{{ route('vote.sendOtp') }}" method="post" id="resendForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group row">
                        <div class="col-md-12 text-center">
                            <input type="submit" value="Resend Code Again?" class="btn btn-info btn-block login-btn"
                                id="resend">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
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

            var counter = 60;
            var interval = setInterval(function() {
                counter--;
                // Display 'counter' wherever you want to display it.
                if (counter <= 0) {
                    clearInterval(interval);
                    $('#resend').val("Resend Code Again?");
                    return;
                } else {
                    $('#resend').val("Resend Code Again? " + counter + "s");
                }
            }, 1000);

            $("#resend").attr("disabled", "disabled");
            setInterval(function() {
                $("#resend").removeAttr("disabled", "disabled");
            }, 60000);


            $("form#verifyOtp").on('submit', function() {
                $('input').blur();
                $.blockUI({
                    css: {
                        backgroundColor: 'transparent',
                        top: '0px',
                        left: '0px',
                        width: $(document).width(),
                        height: $(document).height(),
                        padding: '20%',
                    },
                    baseZ: 2000,
                    message: '<img src="{{ url('images/loader.gif') }}" width="150" />',
                });
            })

            $("form#resendForm").on("submit", function() {
                $('input').blur();
                $.blockUI({
                    css: {
                        backgroundColor: 'transparent',
                        top: '0px',
                        left: '0px',
                        width: $(document).width(),
                        height: $(document).height(),
                        padding: '20%',
                    },
                    baseZ: 2000,
                    message: '<img src="{{ url('images/loader.gif') }}" width="150" />',
                });
                var counter = 60;
                var interval = setInterval(function() {
                    counter--;
                    // Display 'counter' wherever you want to display it.
                    if (counter <= 0) {
                        clearInterval(interval);
                        $('#resend').val("Resend Code Again?");
                        return;
                    } else {
                        $('#resend').val("Resend Code Again? " + counter + "s");
                    }
                }, 1000);

                $("#resend").attr("disabled", "disabled");
                setInterval(function() {
                    $("#resend").removeAttr("disabled", "disabled");
                }, 60000);
            })
        })

    </script>
@endsection
