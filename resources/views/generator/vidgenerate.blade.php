@extends('layouts.back-app')
@section('style')
    <style>
        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link{
            color: #000!important;
        }

        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{
            color: #28a745!important;
        }
    </style>
@endsection
@section('breadcrumb')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          {{-- <h1>candidate List</h1> --}}
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active">
            @if(Auth::user()->type == 'admin')
            <a href="{{route('admin.election.index')}}">Home</a>
            @else
            <a href="{{ route('generator.index') }}">Home</a>
            @endif
          </li>
            <li class="breadcrumb-item active">Voter ID Generate</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
</section>
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-md-8">
                <div class="card card-red card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Voter ID Generate
                        </h3>
                        <div class="card-tools">

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-md-center">
                            <div class="col-md-10">
                                <nav class="w-100">
                                    <div class="nav nav-tabs" id="product-tab" role="tablist">
                                      <a class="nav-item nav-link" id="excel-tab" data-toggle="tab" href="#excel-import" role="tab" aria-controls="product-desc" aria-selected="true"> <i class="fas fa-file-excel"></i> Excel Generate</a>
                                      <a class="nav-item nav-link active" id="single-tab" data-toggle="tab" href="#single" role="tab" aria-controls="single" aria-selected="false"><i class="fas fa-dice-one"></i> Single Generate</a>
                                    </div>
                                  </nav>
                                  <div class="tab-content p-3" id="nav-tabContent">
                                    <div class="tab-pane fade" id="excel-import" role="tabpanel" aria-labelledby="excel-tab">
                                        <form id="ExcelForm" class="pt-2">
                                            <div class="row">
                                                <div class="col-md-8 form-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="file" class="custom-file-input" id="customFile">
                                                        <label class="custom-file-label" for="customFile">Add Excel file</label>
                                                        </div>
                                                </div>
                                                <div class="col-md-4 form-group text-center">
                                                    <input type="submit" class="btn btn-success btn-block" value="Generate">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 text-center">
                                                <div class="col-xs-12">
                                                <a class="btn btn-outline-success btn-block" href="{{route('vid.excel.template-download')}}">Download Excel Template</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade show active" id="single" role="tabpanel" aria-labelledby="single-tab">
                                        <form id="singleForm" class="pt-2">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <span style="color:red; text-align:center;">
                                                    <strong id="error_msg"></strong>
                                                </span>
                                                <input type="text" id="vid" class="form-control input-lg text-center" style="font-size:50px; font-family: 'Roboto Mono', monospace;" disabled>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="vote_count">Vote Count *</label>
                                                  <input type="number"
                                                      class="form-control form-control-sm" name="vote_count" min="0" id="vote_count" placeholder="Enter Vote Count...">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="name">Name *</label>
                                                  <input type="text"
                                                      class="form-control form-control-sm" name="name" id="name" placeholder="Enter Name...">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="email">Email *</label>
                                                  <input type="text"
                                                      class="form-control form-control-sm" name="email" id="email" placeholder="Enter Email...">
                                                      <span style="color:red; text-align:center;">
                                                        <strong id="email_error_msg"></strong>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ph_no" class="form-label">Phone Number *</label>
                                                    <div class="row">
                                                        <div class="col-md-4 text-center">
                                                            <input type="text" class="form-control" value="+95" disabled>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="text" name="ph_no" id="ph_no" class="form-control" placeholder="09xxxxxxxxx">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success btn-block"><i class="fas fa-download"></i> Generate</button>
                                                <div class="row mt-2">
                                                    <div class="col-md-6">
                                                        <button type="button" id="btn_send" class="btn btn-outline-success btn-block"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send Email and SMS</button>
                                                    </div>
                                                    <div class="col-md-6 mt-md-0 mt-2">
                                                        <button type="button" class="btn btn-outline-success btn-block" id="btn_print"><i class="fa fa-print"></i> Print</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="display:none;">
    <div style="border:2px;" id="print_content">
         <!-- <p style="text-align:center"><img src="" alt="" width="100px" height="50px"></p>  -->
         <p style="color:red; text-align:center">*Voter ID စာရွက်အား နောက် Election ကို မဲပေးရန်အတွက် သိမ်းဆည်းထားပေးပါ။</p>
        <h4 style="text-align:center"></h4>
        <p style="text-align:center;font-size:13px;">Print Date: {{Carbon\Carbon::now()->format('d/M/Y h:i:s A')}}</p>
        <br>
        <p style="text-align:center;font-size:26px;">Voter ID: <span id="voter_id" style="border:2px solid red;margin-left:15px;padding-left:8px;padding-right:8px; font-family: 'Roboto Mono', monospace;"></span> </p>
        <br>
        <p style="text-align:center"> Thank You</p>
        <p>.</p>
    </div>
</div>
@endsection
@section('javascript')
<script>
    function generateVid(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        var digits = '0123456789';
        var charactersLength = characters.length;
        var digitsLength = digits.length;
        for (var i = 0; i < 2; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            result += digits.charAt(Math.floor(Math.random() * digitsLength));
        }

        return result;
    }

    $(document).ready(function(){

        bsCustomFileInput.init();

        jQuery('form#ExcelForm').on('submit', function(e) {
            e.preventDefault();
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var formData = new FormData($('form#ExcelForm')[0]);
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: "{{ route('vid.excel.generate-vid') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $.unblockUI();
                    if(data.errors)
                    {
                        toastr.error('Info - ' + data.errors)
                    }else if(data.success)
                    {
                        toastr.success('Info - ' + data.success)
                    }
                },
                error: function(response) {
                    $.unblockUI();
                    if(response['responseJSON'])
                    {
                        toastr.error('Info - ' + response['responseJSON'].message)
                    }else{
                        toastr.error('Info - Something Went Wrong!')
                    }
                },
            });
        })


        $("form#singleForm").on("submit",function(event) {
            event.preventDefault();
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

            vote_count = $("#vote_count").val();
            name = $("#name").val();
            email = $("#email").val();
            ph_no = $("#ph_no").val();

            if (vote_count < 1) {
                $.unblockUI();
                toastr.error('Info - Please! Fill correct vote count')
                return false;
            }

            if (name == '') {
                $.unblockUI();
                toastr.error('Info - Voter Name is Required!')
                return false;
            }

            if(ph_no == ''){
                $.unblockUI();
                toastr.error('Info - Phone Number is Required!')
                return false;
            }

            // if (email == '') {
            //     $.unblockUI();
            //     toastr.error('Info - Email is Required!')
            //     return false;
            // }

            // if (isEmail(email) == false) {
            //     $.unblockUI();
            //     toastr.error('Info - Email is Invalid!')
            //     return false;
            // }

            // function isEmail(email) {
            //     var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            //     return regex.test(email);
            // }

            var filter = /^\d*(?:\.\d{1,2})?$/;

            if (filter.test(ph_no)) {
                if (ph_no.length <= 11 && ph_no.length >= 7) {
                    vid = "";
                    vid = generateVid(4);
                    $.ajax({
                        type: "POST",
                        url: "{{route('vid.store')}}",
                        data: {
                            vid: vid,
                            vote_count: vote_count,
                            name: name,
                            email: email,
                            ph_no:ph_no,
                            _token: '{{csrf_token()}}'
                        },
                        dataType: "json",
                        success: function(data) {
                            $.unblockUI();
                            // console.log(data);
                            if(data.errors)
                            {
                                toastr.error('Info - ' + data.errors)
                            }
                            if (data.success) {
                                toastr.success('Info - ' + data.success)
                                $('#vid').val(data.vid);
                            }
                        },
                        error: function(response) {
                            $.unblockUI();
                            if(response['responseJSON'])
                            {
                                toastr.error('Info - ' + response['responseJSON'].message)
                            }else{
                                toastr.error('Info - Something Went Wrong!')
                            }
                        }
                    });
                } else {
                    $.unblockUI();
                    toastr.error('Info - Please put 11  digit mobile number')
                    return false;
                }
            } else {
                $.unblockUI();
                toastr.error('Info - Not a valid number')
                return false;
            }
        });

        jQuery(function($) {
            'use strict';
            $(".content").find('#btn_print').on('click', function() {
                //Print ele2 with default options
                if ($("#vid").val().trim() != "") {
                    $("#voter_id").text($("#vid").val());
                    $.print("#print_content");
                    $("#vid").val("");
                    $("#vote_count").val("");
                    $("#name").val("");
                    $("#email").val("");
                    $("#ph_no").val("");
                } else {
                    toastr.error('Firstly,please generate  for your VOTER ID!')
                }

            });
        });

        jQuery(function($) {
            'use strict';
            $(".content").find('#btn_send').on('click', function(event) {
                event.preventDefault();
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

                //Print ele2 with default options
                if ($("#vid").val().trim() != "") {
                    generateVid = $("#vid").val();
                    vote_count = $("#vote_count").val();
                    name = $("#name").val();
                    email = $("#email").val();
                    ph_no = $("#ph_no").val();

                    $.ajax({
                        type: "POST",
                        url: "{{route('vid.message')}}",
                        data: {
                            vid: generateVid,
                            vote_count: vote_count,
                            name: name,
                            email:email,
                            ph_no:ph_no,
                            _token: '{{csrf_token()}}'
                        },
                        dataType: "json",
                        success: function(data) {
                            $.unblockUI();
                            if(data.errors)
                            {
                                toastr.error('Info - ' + data.errors)
                            }else if (data.success) {
                                toastr.success('Info - ' + data.success)
                                $("#vid").val("");
                                $("#vote_count").val("");
                                $("#name").val("");
                                $("#ph_no").val("");
                            }
                        },
                        error: function(response) {
                            $.unblockUI();
                            if(response['responseJSON'])
                            {
                                toastr.error('Info - ' + response['responseJSON'].message)
                            }else{
                                toastr.error('Info - Something Went Wrong!')
                            }
                        }
                    });
                } else {
                    $.unblockUI();
                    toastr.error('Info - Firstly, Please generate  for your VOTER ID!')
                }
            });
        });
    })
</script>
@endsection
