@extends('layouts.back-app')
@section('breadcrumb')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">

        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active"><a href="{{route('admin.election.index')}}">Home</a></li>
            <li class="breadcrumb-item active"><a href="{{route('admin.register.index')}}">Member List</a></li>
            <li class="breadcrumb-item active">Member Edit</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
@endsection
@section('content')
<section class="content" id="candidate-edit">
   <div class="container-fluid">
     <div class="row">
        <div class="col-sm-12">
            <div class="card card-red card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Edit Form
                    </h3>
                    <div class="card-tools">
                        <a href="{{route('admin.register.index')}}" class="text-success"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="editForm" enctype="multipart/form-data">
                        @csrf
                        {!! Form::hidden('hidden_id', $member->id) !!}
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('name', 'အမည်'   , ['class' => "form-label"]) !!}
                                  {!! Form::text("name",old('email',$member->name), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('nrc', 'နိုင်ငံသားစီစစ်ရေး / အမျိုးသားမှတ်ပုံတင်အမှတ်'   , ['class' => "form-label"]) !!}
                                    <input type="hidden" name="nrc" id="nrc" value="{{$member->nrc}}">
                                    <div class="row">
                                        <div class="col-3">
                                            <select name="nrc_first" id="nrc_first" class="form-control w-100">
                                                <option disabled selected value> </option>
                                                <option value="1">၁</option>
                                                <option value="2">၂</option>
                                                <option value="3">၃</option>
                                                <option value="4">၄</option>
                                                <option value="5">၅</option>
                                                <option value="6">၆</option>
                                                <option value="7">၇</option>
                                                <option value="8">၈</option>
                                                <option value="9">၉</option>
                                                <option value="10">၁၀</option>
                                                <option value="11">၁၁</option>
                                                <option value="12">၁၂</option>
                                                <option value="13">၁၃</option>
                                                <option value="14">၁၄</option>
                                            </select>
                                        </div>
                                        <div class="col-5 col-lg-4">
                                            <select name="nrc_second" id="nrc_second" class="form-control form-control select2 select2-primary" data-dropdown-css-class="select2-primary" style="width:100%;">

                                            </select>
                                        </div>
                                        <div class="col-4 col-lg-5">
                                            <input type="text" name="nrc_third" id="nrc_third" class="form-control w-100" placeholder="၁၂၃၄၅၆" maxlength="6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('refer_code',  'Customs Reference Code (အသင်းဝင်ဟောင်းများ)'  , ['class' => "form-label"]) !!}
                                  {!! Form::text("refer_code",old('email',$member->refer_code), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('complete_training_no', 'အောင်မြင်ခဲ့သော အကောက်ခွန်ဝန်ဆောင်မှုသင်တန်းအပတ်စဉ်'    , ['class' => "form-label"]) !!}
                                  {!! Form::text("complete_training_no",old('email',$member->complete_training_no), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('AHTN_training_no', 'AHTN သင်တန်းအပတ်စဉ်'   , ['class' => "form-label"]) !!}
                                  {!! Form::text("AHTN_training_no",old('email',$member->AHTN_training_no), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('valuation_training_no', 'CVA/WTO Valuation သင်တန်းအပတ်စဉ်'   , ['class' => "form-label"]) !!}
                                    {!! Form::text("valuation_training_no",old('email',$member->valuation_training_no), ['class' => "form-control"]) !!}
                                  </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('graduation', 'ပညာအရည်အချင်း (ဘွဲ့/ဒီပလိုမာ/ သင်တန်းဆင်းလက်မှတ်)'   , ['class' => "form-label"]) !!}
                                    {!! Form::text("graduation",old('email',$member->graduation), ['class' => "form-control"]) !!}
                                  </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('phone_number', 'ဖုန်းနံပါတ်၊ ဖက်စ်နံပါတ်၊ မိုလ်ဘိုင်းဖုန်း'   , ['class' => "form-label"]) !!}
                                  {!! Form::text("phone_number",old('phone_number',$member->phone_number), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('email', 'အီးမေးလ်' , ['class' => "form-label"]) !!}
                                  {!! Form::text("email",old('email',$member->email), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('officeName', 'အကောက်ခွန်လုပ်ငန်းများဆိုင်ရာဝန်ဆောင်မှုလုပ်ငန်းအမည်'   , ['class' => "form-label"]) !!}
                                  {!! Form::text("officeName",old('officeName',$member->officeName), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('office_startDate', 'လုပ်ငန်းစတင်တည်ထောင်သည့် ခုနှစ်' , ['class' => "form-label"]) !!}
                                  {!! Form::text("office_startDate",old('office_startDate',$member->office_startDate), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('officePhone', 'ဆက်သွယ်ရန် ရုံး ဖုန်းနံပါတ်' , ['class' => "form-label"]) !!}
                                  {!! Form::text("officePhone",old('officePhone',$member->officePhone), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('officeFax', 'ရုံး ဖက်စ်နံပါတ်' , ['class' => "form-label"]) !!}
                                  {!! Form::text("officeFax",old('officeFax',$member->officeFax), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('officeEmail', 'ရုံး အီးမေးလ်' , ['class' => "form-label"]) !!}
                                  {!! Form::text("officeEmail",old('officeEmail',$member->officeEmail), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('yellowCard', 'အဝါကတ်ကိုင်ဆောင်သူဦးရေ' , ['class' => "form-label"]) !!}
                                  {!! Form::text("yellowCard",old('yellowCard',$member->yellowCard), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('pinkCard', 'ပန်းရောင်ကတ်ကိုင်ဆောင်သူဦးရေ' , ['class' => "form-label"]) !!}
                                  {!! Form::text("pinkCard",old('pinkCard',$member->pinkCard), ['class' => "form-control"]) !!}
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

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-flat btn-success"><i class="fas fa-edit"></i> Edit</button>
                                <a href="{{route('admin.register.index')}}" type="button" class="btn btn-flat btn-danger"><i class="fas fa-reply-all"></i> Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
     </div>
     <!-- /.row -->
   </div><!-- /.container-fluid -->
 </section>
@endsection
@section('javascript')
    <script src="{{asset('js/mm-nrc.js')}}"></script>   
    <script>
        $(document).ready(function(){
            let nrc_no = "{{$member->nrc}}";                        
            nrc_no = new MMNRC(nrc_no);            
            nrc_no = nrc_no.getFormat();
            nrc_no = nrc_no.split("/");
            let state_no = MMNRC.toEngNum(nrc_no[0]);
            
            $("#nrc_first option[value="+state_no+"]").attr('selected','selected');            
            let data = nrc_data[state_no];

            data = data.sort();
            var select = `<option disabled selected value> </option>`;

            $("#nrc_second").html(select);

            $.each(data,function(i,v){
                var html = `<option value="${v}">${v}</option>`;
                $("#nrc_second").append(html);
            })

            let nrcSecond = nrc_no[1].split("(");
            
            $("#nrc_second option[value="+nrcSecond[0]+"]").attr('selected','selected');


            $("#nrc_third").val(nrcSecond[1].split(")")[1]);

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

            $('form#editForm').on('submit',function(event){
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


                if(!regx_mm_num.test(nrc_third))
                {
                    $.unblockUI();
                    toastr.error('Info - NRC အား မြန်မာစာဖြင့် ဖြည့်ရန်.')
                    return false;
                }

                var nrc_no = MMNRC.toMyaNum(nrc_first) + "/" + nrc_second + "(နိုင်)" + nrc_third;                
                let nrc = new MMNRC(nrc_no);
                nrc = nrc.getFormat();     
                
                $("input[name=nrc]").val(nrc);

                $.ajax({
                        url: "{{ route('admin.register.update') }}",
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
                                var url = '{{ route("admin.register.index") }}';

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
