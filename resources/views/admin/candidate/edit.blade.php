@extends('layouts.back-app')
@section('breadcrumb')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          {{-- <h1>candidate List</h1> --}}
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active"><a href="{{route('admin.dashboard',$election->id)}}">Dashobard</a></li>
          <li class="breadcrumb-item active"><a href="{{route('admin.candidate.index',$election->id)}}">Candidates List</a></li>
            <li class="breadcrumb-item active">Candidate Edit</li>
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
                        <a href="{{route('admin.candidate.index',$election->id)}}" class="text-red"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="editForm" enctype="multipart/form-data">
                        @csrf
                        {!! Form::hidden('election_id', $election->id) !!}
                        {!! Form::hidden('hidden_id', $candidate->id) !!}
                        <div class="row">
                            <div class="col-md-6 right">
                                @if($candidate->photo_url)
                                <img src="{{url($candidate->photo_url)}}" alt="Profile Image" id="profile-img-tag">
                                @else
                                <img src="{{url('images/user.png')}}" alt="Profile Image" id="profile-img-tag">
                                @endif
                                {!! Form::hidden('old_image', $candidate->photo_url) !!}
                            </div>
                            <div class="col-md-6 pt-5 center">
                                {!! Form::file('image', ['class' => "profile-img"]) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('candidate_no', trans('admin.candidate_no') . '*' , ['class' => "form-label"]) !!}
                                  {!! Form::text("candidate_no",$candidate->candidate_no, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('mname', trans('admin.username') . '*' , ['class' => "form-label"]) !!}
                                  {!! Form::text("mname",$candidate->mname, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('nrc_no', trans('admin.nrc_no')   , ['class' => "form-label"]) !!}
                                  {!! Form::text("nrc_no",$candidate->nrc_no, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('dob', trans('admin.dob')    , ['class' => "form-label"]) !!}
                                  {!! Form::date("dob",$candidate->dob, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('gender', trans('admin.gender') . '*' , ['class' => "form-label"]) !!} <br>
                                  <label class="radio-inline">
                                    <input type="radio" name="gender" value="Male" {{$candidate->gender == 'Male' ? 'checked': ''}} class="mx-3">Male</label>
                                    <label class="radio-inline">
                                    <input type="radio" name="gender" value="Female" {{$candidate->gender == 'Female' ? 'checked': ''}}  class="mx-3">Female</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('position', trans('admin.position')  , ['class' => "form-label"]) !!}
                                  {!! Form::text("position",$candidate->position, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('education', trans('admin.education')   , ['class' => "form-label"]) !!}
                                  {!! Form::text("education",$candidate->education, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('phone_no', trans('admin.phone_no')  , ['class' => "form-label"]) !!}
                                  {!! Form::text("phone_no",$candidate->phone_no, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('email', trans('admin.email')   , ['class' => "form-label"]) !!}
                                  {!! Form::email("email",$candidate->email, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  {!! Form::label('address', trans('admin.address')  , ['class' => "form-label"]) !!}
                                    {!! Form::textarea('address', old('address'), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('company', trans('admin.company')  , ['class' => "form-label"]) !!}
                                  {!! Form::text("company",$candidate->company, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('company_start_date', trans('admin.company_start_date')   , ['class' => "form-label"]) !!}
                                  {!! Form::text("company_start_date",$candidate->company_start_date, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('no_of_employee', trans('admin.no_of_employee')  , ['class' => "form-label"]) !!}
                                  {!! Form::text("no_of_employee",$candidate->no_of_employee, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  {!! Form::label('company_email', trans('admin.company_email')   , ['class' => "form-label"]) !!}
                                  {!! Form::email("company_email",$candidate->company_email, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  {!! Form::label('company_phone', trans('admin.company_phone_no')  , ['class' => "form-label"]) !!}
                                  {!! Form::text("company_phone",$candidate->company_phone, ['class' => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  {!! Form::label('company_address', trans('admin.company_address')  , ['class' => "form-label"]) !!}
                                    {!! Form::textarea('company_address', old('company_address',$candidate->company_address), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  {!! Form::label('experience', trans('admin.experience')  , ['class' => "form-label"]) !!}
                                    {!! Form::textarea('experience', old('experience',$candidate->experience), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  {!! Form::label('biography', trans('admin.biography')  , ['class' => "form-label"]) !!}
                                    {!! Form::textarea('biography', old('biography',$candidate->biography), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-flat btn-success"><i class="fas fa-edit"></i> Edit</button>
                                <a href="{{route('admin.candidate.index',$election->id)}}" type="button" class="btn btn-flat btn-danger"><i class="fas fa-reply-all"></i> Back</a>
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
    <script>
        $(document).ready(function(){
            var election_id = "{{$election->id}}";

            $('#address').summernote({
                dialogsFade: true,
                placeholder: 'Type Something Here...',
                tabsize: 2,
                height: 200,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'help']],
                ],
            });

            $('#experience').summernote({
                dialogsFade: true,
                placeholder: 'Type Something Here...',
                tabsize: 2,
                height: 200,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'help']],
                ],
            });

            $('#biography').summernote({
                dialogsFade: true,
                placeholder: 'Type Something Here...',
                tabsize: 2,
                height: 200,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'help']],
                ],
            });

            $('#company_address').summernote({
                dialogsFade: true,
                placeholder: 'Type Something Here...',
                tabsize: 2,
                height: 200,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'help']],
                ],
            });

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

                $.ajax({
                        url: "{{ route('admin.candidate.update') }}",
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
                                var url = '{{ route("admin.candidate.index", ["election_id" => ":election_id"]) }}';
                                url = url.replace(':election_id', election_id);

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
