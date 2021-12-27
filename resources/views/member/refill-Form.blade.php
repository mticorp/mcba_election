@extends('layouts.app')
@section('style')
<style>
    @media only screen and (min-width: 768px)
    {
        #app-body .card-tools{
            width: 75%!important;
        }
    }

    #app-body{
        background-color: white!important;
    }
</style>
@endsection
@section('content')
<div class="container pt-3 mb-5 pb-5" id="candidate-create">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list-alt"></i> Form</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <form id="EditForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-center my-3">
                            <div class="col-sm-12">
                                {!! Form::hidden('hidden_id', $member->id) !!}
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <p class="py-2 text-danger"><strong>*အောက်ပါ အချက်အလက်များ မှန်ကန်ကြောင်း အတည်ပြုပါသည်။</strong></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 right">
                                        @if($member->profile)
                                        <img src="{{url($member->profile)}}" alt="Profile Image" id="profile-img-tag">
                                        @else
                                        <img src="{{url('images/user.png')}}" alt="Profile Image" id="profile-img-tag">
                                        @endif
                                        {!! Form::hidden('old_image', $member->profile) !!}
                                    </div>
                                    <div class="col-md-6 pt-5 center">
                                        {!! Form::file('image', ['class' => "profile-img"]) !!}
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="name">အမည်</label>
                                          <input type="text" name="name" id="name" class="form-control w-100" value="{{old('name',$member->name)}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nrc">နိုင်ငံသားစီစစ်ရေး / အမျိုးသားမှတ်ပုံတင်အမှတ်</label>
                                            <input type="hidden" name="nrc_no" id="nrc_no" value="{{$member->nrc}}">
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
                                                <div class="col-5 col-lg-4">                                                    
                                                    <select name="nrc_second" id="nrc_second" class="form-control form-control select2 select2-primary" data-dropdown-css-class="select2-primary" style="width:100%;">
                                                        
                                                    </select>
                                                </div>
                                                <div class="col-4 col-lg-5">
                                                    <input type="text" name="nrc_third" id="nrc_third" class="form-control w-100" placeholder="000000">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="refer_code">Customs Reference Code (အသင်းဝင်ဟောင်းများ)</label>
                                          <input type="text" name="refer_code" id="refer_code" class="form-control w-100" value="{{old('refer_code',$member->refer_code)}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="complete_training_no">အောင်မြင်ခဲ့သော အကောက်ခွန်ဝန်ဆောင်မှုသင်တန်းအပတ်စဉ်</label>
                                            <input type="text" name="complete_training_no" id="complete_training_no" class="form-control w-100" value="{{old('complete_training_no',$member->complete_training_no)}}">
                                          </div>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="AHTN_training_no">AHTN သင်တန်းအပတ်စဉ်</label>
                                          <input type="text" name="AHTN_training_no" id="AHTN_training_no" class="form-control w-100" value="{{old('AHTN_training_no',$member->AHTN_training_no)}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="valuation_training_no">CVA/WTO Valuation သင်တန်းအပတ်စဉ်</label>
                                            <input type="text" name="valuation_training_no" id="valuation_training_no" class="form-control w-100" value="{{old('valuation_training_no',$member->valuation_training_no)}}">
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('graduation', 'ပညာအရည်အချင်း (ဘွဲ့/ဒီပလိုမာ/ သင်တန်းဆင်းလက်မှတ်)'   , ['class' => "form-label"]) !!}
                                            {!! Form::text("graduation",old('graduation',$member->graduation), ['class' => "form-control w-100"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        {!! Form::label('phone_number', 'ဖုန်းနံပါတ်၊ ဖက်စ်နံပါတ်၊ မိုလ်ဘိုင်းဖုန်း'   , ['class' => "form-label"]) !!}
                                        {!! Form::text("phone_number",old('phone_number',$member->phone_number), ['class' => "form-control w-100", 'readonly' => 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        {!! Form::label('email', 'အီးမေးလ်' , ['class' => "form-label"]) !!}
                                        {!! Form::text("email",old('email',$member->email), ['class' => "form-control w-100"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        {!! Form::label('officeName', 'အကောက်ခွန်လုပ်ငန်းများဆိုင်ရာဝန်ဆောင်မှုလုပ်ငန်းအမည်'   , ['class' => "form-label"]) !!}
                                        {!! Form::text("officeName",old('officeName',$member->officeName), ['class' => "form-control w-100"]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        {!! Form::label('office_startDate', 'လုပ်ငန်းစတင်တည်ထောင်သည့် ခုနှစ်' , ['class' => "form-label"]) !!}
                                        {!! Form::text("office_startDate",old('office_startDate',$member->office_startDate), ['class' => "form-control w-100"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        {!! Form::label('officePhone', 'ဆက်သွယ်ရန် ရုံး ဖုန်းနံပါတ်' , ['class' => "form-label"]) !!}
                                        {!! Form::text("officePhone",old('officePhone',$member->officePhone), ['class' => "form-control w-100"]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        {!! Form::label('officeFax', 'ရုံး ဖက်စ်နံပါတ်' , ['class' => "form-label"]) !!}
                                        {!! Form::text("officeFax",old('officeFax',$member->officeFax), ['class' => "form-control w-100"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        {!! Form::label('officeEmail', 'ရုံး အီးမေးလ်' , ['class' => "form-label"]) !!}
                                        {!! Form::text("officeEmail",old('officeEmail',$member->officeEmail), ['class' => "form-control w-100"]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        {!! Form::label('yellowCard', 'အဝါကတ်ကိုင်ဆောင်သူဦးရေ' , ['class' => "form-label"]) !!}
                                        {!! Form::text("yellowCard",old('yellowCard',$member->yellowCard), ['class' => "form-control w-100"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        {!! Form::label('pinkCard', 'ပန်းရောင်ကတ်ကိုင်ဆောင်သူဦးရေ' , ['class' => "form-label"]) !!}
                                        {!! Form::text("pinkCard",old('pinkCard',$member->pinkCard), ['class' => "form-control w-100"]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        {!! Form::label('officeAddress', 'ဆက်သွယ်ရန်ရုံးလိပ်စာ (တိတိကျကျဖော်ပြရန်)'  , ['class' => "form-label"]) !!}
                                            {!! Form::textarea('officeAddress', old('officeAddress',$member->officeAddress), ['class' => "form-control",'rows' => '8']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        {!! Form::label('address', 'အမြဲတမ်းနေရပ်လိပ်စာ'  , ['class' => "form-label"]) !!}
                                            {!! Form::textarea('address', old('address',$member->address), ['class' => "form-control",'rows' => '8']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-info btn-md btn-block"><i class="fas fa-save"></i> Save</button>
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
@endsection
@section('javascript')
<script>
    $(document).ready(function(){        

        var nrc_no = "{{$member->nrc}}";
        // console.log(nrc_no);
        var nrc_no = nrc_no.split("/");
        $("#nrc_first option[value="+nrc_no[0]+"]").attr('selected','selected');
        
        var value1 = nrc_no[0];
        var data1 = nrc_data[value1];
           
        var data1 = data1.sort();
        var select = `<option disabled selected value> </option>`;

        $("#nrc_second").html(select);
        $.each(data1,function(i,v){
            var html = `<option value="${v}">${v}</option>`;
            $("#nrc_second").append(html);
        })
        
        var nrcSecond = nrc_no[1].split("(N)");
        $("#nrc_second option[value="+nrcSecond[0]+"]").attr('selected','selected');

        $("#nrc_third").val(nrcSecond[1]);

        $("#nrc_first").on('change',function(){
            var value = $(this).val();
            var data = nrc_data[value];
           
            var data = data.sort();            
            $("#nrc_second").html(select);
            $.each(data,function(i,v){
                var html = `<option value="${v}">${v}</option>`;
                $("#nrc_second").append(html);
            })
        })  

        $('form#EditForm').on('submit',function(event){
            event.preventDefault();
            $('input').blur();
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

            $.ajax({
                    url: "{{route('vote.member.register.confirm')}}",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function(data) {
                        if (data.errors) {
                            $.unblockUI();
                            for (var count = 0; count < data.errors.length; count++) {
                                toastr.error('Info - ' + data.errors[count])
                            }
                        }
                        if (data.success) {
                            var url = '{{ route("vote.member.register.complete") }}';

                            window.location.href = url;
                        }
                    }
                })
        })


        function readURL(input) {
            if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#profile-img-tag').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
            }
        }
        $(".profile-img").change(function() {
            readURL(this);
        });
    })
</script>
@endsection
