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
                                  {!! Form::label('name', '????????????'   , ['class' => "form-label"]) !!}
                                  {!! Form::text("name",old('email',$member->name), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('nrc', '?????????????????????????????????????????????????????? / ????????????????????????????????????????????????????????????????????????'   , ['class' => "form-label"]) !!}
                                    <input type="hidden" name="nrc" id="nrc" value="{{$member->nrc}}">
                                    <div class="row">
                                        <div class="col-3">
                                            <select name="nrc_first" id="nrc_first" class="form-control w-100">
                                                <option disabled selected value> </option>
                                                <option value="1">???</option>
                                                <option value="2">???</option>
                                                <option value="3">???</option>
                                                <option value="4">???</option>
                                                <option value="5">???</option>
                                                <option value="6">???</option>
                                                <option value="7">???</option>
                                                <option value="8">???</option>
                                                <option value="9">???</option>
                                                <option value="10">??????</option>
                                                <option value="11">??????</option>
                                                <option value="12">??????</option>
                                                <option value="13">??????</option>
                                                <option value="14">??????</option>
                                            </select>
                                        </div>
                                        <div class="col-5 col-lg-4">
                                            <select name="nrc_second" id="nrc_second" class="form-control form-control select2 select2-primary" data-dropdown-css-class="select2-primary" style="width:100%;">

                                            </select>
                                        </div>
                                        <div class="col-4 col-lg-5">
                                            <input type="text" name="nrc_third" id="nrc_third" class="form-control w-100" placeholder="??????????????????" maxlength="6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('refer_code',  'Customs Reference Code (??????????????????????????????????????????????????????)'  , ['class' => "form-label"]) !!}
                                  {!! Form::text("refer_code",old('email',$member->refer_code), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('complete_training_no', '????????????????????????????????????????????? ?????????????????????????????????????????????????????????????????????????????????????????????????????????'    , ['class' => "form-label"]) !!}
                                  {!! Form::text("complete_training_no",old('email',$member->complete_training_no), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('AHTN_training_no', 'AHTN ??????????????????????????????????????????'   , ['class' => "form-label"]) !!}
                                  {!! Form::text("AHTN_training_no",old('email',$member->AHTN_training_no), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('valuation_training_no', 'CVA/WTO Valuation ??????????????????????????????????????????'   , ['class' => "form-label"]) !!}
                                    {!! Form::text("valuation_training_no",old('email',$member->valuation_training_no), ['class' => "form-control"]) !!}
                                  </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('graduation', '??????????????????????????????????????? (????????????/????????????????????????/ ??????????????????????????????????????????????????????)'   , ['class' => "form-label"]) !!}
                                    {!! Form::text("graduation",old('email',$member->graduation), ['class' => "form-control"]) !!}
                                  </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('phone_number', '???????????????????????????????????? ???????????????????????????????????? ????????????????????????????????????????????????'   , ['class' => "form-label"]) !!}
                                  {!! Form::text("phone_number",old('phone_number',$member->phone_number), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('email', '????????????????????????' , ['class' => "form-label"]) !!}
                                  {!! Form::text("email",old('email',$member->email), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('officeName', '????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????'   , ['class' => "form-label"]) !!}
                                  {!! Form::text("officeName",old('officeName',$member->officeName), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('office_startDate', '???????????????????????????????????????????????????????????????????????? ??????????????????' , ['class' => "form-label"]) !!}
                                  {!! Form::text("office_startDate",old('office_startDate',$member->office_startDate), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('officePhone', '?????????????????????????????? ???????????? ?????????????????????????????????' , ['class' => "form-label"]) !!}
                                  {!! Form::text("officePhone",old('officePhone',$member->officePhone), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('officeFax', '???????????? ?????????????????????????????????' , ['class' => "form-label"]) !!}
                                  {!! Form::text("officeFax",old('officeFax',$member->officeFax), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('officeEmail', '???????????? ????????????????????????' , ['class' => "form-label"]) !!}
                                  {!! Form::text("officeEmail",old('officeEmail',$member->officeEmail), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('yellowCard', '??????????????????????????????????????????????????????????????????' , ['class' => "form-label"]) !!}
                                  {!! Form::text("yellowCard",old('yellowCard',$member->yellowCard), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('pinkCard', '????????????????????????????????????????????????????????????????????????????????????' , ['class' => "form-label"]) !!}
                                  {!! Form::text("pinkCard",old('pinkCard',$member->pinkCard), ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  {!! Form::label('officeAddress', '???????????????????????????????????????????????????????????? (???????????????????????????????????????????????????)'  , ['class' => "form-label"]) !!}
                                    {!! Form::textarea('officeAddress', old('officeAddress',$member->officeAddress), ['class' => "form-control",'rows' => '8']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  {!! Form::label('address', '?????????????????????????????????????????????????????????'  , ['class' => "form-label"]) !!}
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
            
            $("#nrc_first option[value="+state_no+"]").prop('selected','selected');            
            let data = nrc_data[state_no];

            data = data.sort();
            var select = `<option disabled selected value> </option>`;

            $("#nrc_second").html(select);

            $.each(data,function(i,v){
                var html = `<option value="${v}">${v}</option>`;
                $("#nrc_second").append(html);
            })

            let nrcSecond = nrc_no[1].split("(");
            
            $("#nrc_second option[value="+nrcSecond[0]+"]").prop('selected','selected');


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
                    toastr.error('Info - NRC ????????? ??????????????????????????????????????? ????????????????????????.')
                    return false;
                }

                var nrc_no = MMNRC.toMyaNum(nrc_first) + "/" + nrc_second + "(???????????????)" + nrc_third;                
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
                    $('#profile-img-tag').prop('src', e.target.result);
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
