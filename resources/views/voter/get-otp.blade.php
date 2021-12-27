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
                    <div class="m-md-4 text-center" id="time"></div>
                    {!! Session::has('status') ? '<div class="alert alert-danger d-block text-center mt-4">' . Session::get('status') . '</div>' : '' !!}
                </div>
                <form action="{{ route('vote.sendOtp') }}" method="post" id="sendOtp">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group row">
                        <div class="col-md-12 text-center">                            
                            @if (count($phones) > 1)
                                <input value="{{ old('phone') }}" type="phone" name="phone" class="col-12 form-control"
                                style="margin-bottom: 14px; padding: 5px;"
                                placeholder="09123456789" required />    
                                <input type="hidden" name="phones" value="true">                        
                            @endif

                            <input type="submit" value="GET OTP" class="btn btn-info btn-block login-btn">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="app_link" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><img src="{{ url('images/election_logo.png') }}"
                            alt="Logo" width="10%"> mmVote</h5>
                </div>
                <div class="modal-body text-center">
                    <h5><b>mmVote</b> Application Avaliable on Google Play Store! </h5>
                </div>
                <div class="modal-footer mx-auto">
                    <a href="https://play.google.com/store/apps/details?id=com.mti.mmvote" class="btn btn-success"><i
                            class="fas fa-download"></i> Get It Now</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>
                        Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--IOS Modal -->
    <div class="modal fade" id="ios_app_link" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><img src="{{ url('images/election_logo.png') }}"
                            alt="Logo" width="10%"> mmVote</h5>
                </div>
                <div class="modal-body text-center">
                    <h5><b>mmVote</b> Application Avaliable on App Store! </h5>
                </div>
                <div class="modal-footer mx-auto">
                    <a href="https://apps.apple.com/ie/app/mmvote/id1573344215" class="btn btn-success"><i
                            class="fas fa-download"></i> Get It Now</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>
                        Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('javascript')
    <script>
        $(document).ready(function() {

            var data = getMobileOperatingSystem();

            if (data == 'Android') {
                // $("#app_link").modal({
                //     backdrop: 'static',
                //     keyboard: false                    
                // });
            }

            if (data == "iOS") {
                // $("#ios_app_link").modal({
                //     backdrop:'static',
                //     keyboard:false
                // })
            }


            function getMobileOperatingSystem() {
                var userAgent = navigator.userAgent || navigator.vendor || window.opera;

                // Windows Phone must come first because its UA also contains "Android"
                if (/windows phone/i.test(userAgent)) {
                    return "Windows Phone";
                }

                if (/android/i.test(userAgent)) {
                    return "Android";
                }

                // iOS detection from: http://stackoverflow.com/a/9039885/177710
                if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                    return "iOS";
                }

                return "unknown";
            }

            var timeDisplay = document.getElementById("time");

            function refreshTime() {
                var dateString = new Date().toLocaleString("en-US", {
                    timeZone: "Asia/yangon"
                });
                var formattedString = dateString.replace(", ", " - ");
                timeDisplay.innerHTML = formattedString;
            }
            setInterval(refreshTime, 10);

            $("form#sendOtp").on('submit', function() {
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
        })
    </script>
@endsection
