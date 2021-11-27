@extends('layouts.app')
@section('style')
<style>
    @media only screen and (min-width: 768px) {
        #app-body .card-tools {
            width: 75% !important;
        }
    }

    #app-body {
        background-color: white !important;
    }
</style>
@endsection
@section('content')
<div style="padding:100px 0px 198px 0px;" class="middle-card">
    <div class="card shadow card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list-alt"></i>
                Check Form
            </h3>
            <div class="card-tools">

            </div>
        </div>
        <div class="card-body">            
            {!! Form::open(['id' => 'checkCode']) !!}
            <div class="row">
                <div class="col-md-12">
                    <h6 class="text-center" style="font-family: 'Times New Roman', Times, serif; line-height:40px; color:red">*အောက်ပါနေရာတွင် သင်၏ အချက်အလက်များအား ရိုက်ထည့်ရန်</h6>
                    <div class="row">                       
                        <div class="col-md-12">
                            <div class="form-group my-2">
                                <label for="nrc_second">နိုင်ငံသားစီစစ်ရေး / အမျိုးသားမှတ်ပုံတင်အမှတ် :</label>
                                <input type="hidden" name="nrc_no" id="nrc_no">
                                <div class="row">
                                    <div class="col-3">
                                        <select name="nrc_first" id="nrc_first" class="form-control w-100">
                                        <option disabled selected value> </option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                        </select>
                                    </div>
                                    <div class="col-5">                                      
                                        <select name="nrc_second" id="nrc_second" class="form-control form-control select2 select2-primary" data-dropdown-css-class="select2-primary" style="width:100%;">
                                            
                                        </select>
                                    </div>
                                    <div class="col-1 mx-0 px-0 pt-2 text-center">
                                        (N)
                                    </div>
                                    <div class="col-3">                                    
                                        <input type="text" name="nrc_third" id="nrc_third" class="form-control w-100" placeholder="000000">
                                    </div>
                                </div>
                                <small class="text-muted"><span class="text-danger"> * ( အင်္ဂလိပ်စာဖြင့် ရိုက်ထည့်ရန် )</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group my-2">
                                <label for="refer_code">ဖုန်းနံပါတ် :</label>
                                {!! Form::text('phone_no', old('phone_no'), ['class' => 'form-control w-100','id' => 'phone_no','placeholder' => '09xxxxxxxxx']) !!}
                                <small class="text-muted"><span class="text-danger"> * ( အင်္ဂလိပ်စာဖြင့် ရိုက်ထည့်ရန် )</span></small>
                            </div>                    
                        </div>                        
                    </div>   
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group my-2">
                                <label for="refer_code">Yellow Card / Reference Code :</label>
                                {!! Form::text('refer_code', old('refer_code'), ['class' => 'form-control w-100','id' => 'refer_code','placeholder' => 'Enter Your Yellow Card / Reference Code...']) !!}
                                <small class="text-muted"><span class="text-danger"> * ( အင်္ဂလိပ်စာဖြင့် ရိုက်ထည့်ရန် )</span></small>
                            </div>
                        </div>    
                    </div>                 
                    <div class="form-group">
                        {!! Form::submit('Check', ['class' => 'btn btn-success btn-block mt-3']) !!}
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
    $(document).ready(function() {
        
        $("#nrc_first").on('change',function(){
            var value = $(this).val();
            var data = nrc_data[value];
           
            var data = data.sort();
            var select = `<option disabled selected value> </option>`;
            $("#nrc_second").html(select);
            $.each(data,function(i,v){
                var html = `<option value="${v}">${v}</option>`;
                $("#nrc_second").append(html);
            })
                        
            // $("#nrc_second").autocomplete({
            //     source:data
            // });
        })

        $("form#checkCode").on('submit', function(e) {
            e.preventDefault();
            $('input').blur();
            // return false;
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
            
            // return false;
            var nrc_first = $("select[name=nrc_first]").val();
            var nrc_second = $("select[name=nrc_second]").val();            
            var nrc_third = $("input[name=nrc_third]").val();

            if (nrc_first == '') {
                $.unblockUI();
                toastr.error('Info - NRC is Required.')
                return false;
            }

            if (nrc_second == '') {
                $.unblockUI();
                toastr.error('Info - NRC is Required.')
                return false;
            }

            if (nrc_third == '') {
                $.unblockUI();
                toastr.error('Info - NRC is Required.')
                return false;
            }


            var nrc_no = nrc_first + "/" + nrc_second + "(N)" + nrc_third;
            $("input[name=nrc_no]").val(nrc_no);

            // console.log(nrc_second);
            // return false;                
            $.ajax({
                url: "{{ route('vote.member.register.check') }}",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(data) {
                    $.unblockUI();
                    if (data.errors) {
                        for (var count = 0; count < data.errors.length; count++) {
                            toastr.error('Info - ' + data.errors[count])
                        }
                    }
                    if (data.success) {
                        var url = '{{ route("vote.member.register.form", ["member_id" => ":member_id"]) }}';
                        url = url.replace(':member_id', data.member_id);

                        window.location.href = url;
                    }
                }
            })
        })
    })
</script>
@endsection