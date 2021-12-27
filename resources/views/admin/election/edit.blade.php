@extends('layouts.back-app')
@section('breadcrumb')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.election.index') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.election.index') }}">Election List</a>
                        </li>
                        <li class="breadcrumb-item active">Election Edit</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('content')
    <section class="content" id="candidate-create">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-red card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                Edit Form
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.election.index') }}" class="text-danger"><i
                                        class="fas fa-arrow-alt-circle-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" id="election_form" class="form-horizontal">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="mb-3"><b>Main Feature :</b></h4>
                                        <div class="row">
                                            <div class="col-md-6">                                                
                                                <label for="ques">Question : </label>
                                                <input class="ques toggle-class" type="checkbox" id="ques" data-onstyle="success"
                                                    data-offstyle="danger" data-toggle="toggle" data-on="ON" data-off="OFF" {{$election->ques_flag == 1 ? 'checked' : ''}}>
                                            </div>
                                            <div class="col-md-6 my-3 my-md-0">
                                                <label for="candidate">Candidate : </label>
                                                <input class="candidate toggle-class" type="checkbox" id="candidate"
                                                    data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="ON"
                                                    data-off="OFF" {{$election->candidate_flag == 1 ? 'checked' : ''}}>
                                            </div>
                                        </div>                                                                                
                                        <hr>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="mb-3"><b>Additional Feature : </b></h4>
                                        <label for="lucky">Lucky Draw : </label>
                                        <input class="lucky toggle-class" type="checkbox" id="lucky" data-onstyle="success"
                                            data-offstyle="danger" data-toggle="toggle" data-on="ON" data-off="OFF" {{$election->lucky_flag == 1 ? 'checked' : ''}}>
                                        <hr>
                                    </div>
                                </div>

                                 <div class="row mb-3">
                                    <div class="col-12">
                                        <h4><b>General Information</b></h4>
                                    </div>
                                </div>  

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Election Name*</label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                placeholder="Enter Election name..." value="{{ old('name',$election->name) }}" autofocus>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company">Company List *</label>
                                            <select name="company" id="company" class="custom-select">
                                                <option value="" disabled>---Select---</option>
                                                @foreach ($company as $item)
                                                    <option value="{{ $item->id }}" {{$election->company_id == $item->id ? 'selected' : ''}}>{{ $item->company_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="duration_from">Duration From *</label>
                                            <input type="datetime-local" name="durationfrom" class="form-control" step="any"
                                                id="durationfrom" placeholder="Enter Position..."
                                                value="{{ old('durationfrom') }}"
                                                min="{{ Carbon\Carbon::now()->format('Y-m-d\TH:i') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="duration_to">Duration To *</label>
                                            <input type="datetime-local" name="durationto" class="form-control" step="any"
                                                id="durationto" placeholder="Enter Position..."
                                                value="{{ old('durationto') }}"
                                                min="{{ Carbon\Carbon::now()->format('Y-m-d\TH:i') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="election_title_description">Election Title Description</label>
                                            <input type="text" name="election_title_description" class="form-control" id="election_title_description"
                                                placeholder="Enter Election Title Description..." value="{{ old('election_title_description',$election->election_title_description) }}" autofocus>
                                        </div>
                                    </div>
                                </div>

                                <div class="candidate-information {{$election->candidate_flag == 0 ? 'hidden' : ''}}">
                                    <div class="row mb-3">                                    
                                        <div class="col-12">
                                            <hr>
                                            <h4><b>Additional Candidate Information</b></h4>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="position">Position *</label>
                                                <input type="text" name="position" class="form-control" id="position"
                                                    placeholder="Enter Position..." value="{{ old('position',$election->position) }}">
                                            </div>
                                        </div>  
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="no_of_position_mm">No Of Position(MM)*</label>
                                                <input type="text" name="no_of_position_mm" class="form-control"
                                                    id="no_of_position_mm" placeholder="Enter Number of Position (MM)..."
                                                    value="{{ old('no_of_position_mm',$election->no_of_position_mm) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="no_of_position_en">No Of Position(EN)*</label>
                                                <input type="number" min="0" name="no_of_position_en" class="form-control"
                                                    id="no_of_position_en" placeholder="Enter Number of Postion (EN)..."
                                                    value="{{ old('no_of_position_en',$election->no_of_position_en) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Description*</label>
                                                <textarea class="textarea" name="description" id="description"
                                                    placeholder="Place some text here"
                                                    style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('description',$election->description) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Candidate Title*</label>
                                                <textarea class="textarea" name="candidate_title" id="candidate_title"
                                                    placeholder="Place some text here"
                                                    style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('candidate_title',$election->candidate_title) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="question-information {{$election->ques_flag == 0 ? 'hidden' : ''}}">
                                    <div class="row mb-3">                                    
                                        <div class="col-12">
                                            <hr>
                                            <h4><b>Additional Question Information</b></h4>
                                        </div>
                                    </div>


                                     <div class="row">
                                        <div class="col-md-6">
                                            <label for="ques_title">Question Title *</label>
                                        
                                             <textarea class="textarea" name="ques_title" id="ques_title"
                                             placeholder="Enter Question Title..."
                                             style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('ques_title',$election->ques_title) }}</textarea>
                                            
                                        </div>
    
                                        <div class="col-md-6">
                                            <label for="ques_title">Question Description *</label>
                                            <textarea class="textarea" name="ques_description" id="ques_description"
                                            placeholder="Enter Question Description..."
                                            style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('ques_description',$election->ques_description) }}</textarea>
                                           
                                            
                                        </div>
                                    </div>
    
                                </div>                                                      

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-flat btn-success"><i class="fas fa-save"></i>
                                            Update</button>
                                        <a href="{{ route('admin.election.index') }}" type="button"
                                            class="btn btn-flat btn-danger"><i class="fas fa-reply-all"></i> Election
                                            List</a>
                                    </div>
                                </div>

                                <input type="hidden" name="hidden_id" value="{{$election->id}}">
                                <input type="hidden" name="start_time" value="0000-00-00 00:00:00">
                                <input type="hidden" name="end_time" value="0000-00-00 00:00:00">
                                <input type="hidden" name="lucky_flag" id="lucky_flag" value="{{old('lucky_flag',$election->lucky_flag)}}" />
                                <input type="hidden" name="ques_flag" id="ques_flag" value="{{old('ques_flag',$election->ques_flag)}}" />
                                <input type="hidden" name="candidate_flag" id="candidate_flag" value="{{old('candidate_flag',$election->candidate_flag)}}" />
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
        $(document).ready(function() {
            var duration_from = "{{$election->duration_from}}";
            var duration_to = "{{$election->duration_to}}";            

            $('.lucky').change(function() {
                var check = $(this).prop('checked') == true ? 1 : 0;
                $("input[name=lucky_flag]").val(check);
            })

            $('.ques').change(function() {
                var check = $(this).prop('checked') == true ? 1 : 0;
                $("input[name=ques_flag]").val(check);

                check == 1 ? $('.question-information').removeClass('hidden') : $('.question-information').addClass('hidden') & $('.question-information').find('input,textarea').val('') ;
            })

            $('.candidate').change(function() {
                var check = $(this).prop('checked') == true ? 1 : 0;
                $("input[name=candidate_flag]").val(check);

                check == 1 ? $('.candidate-information').removeClass('hidden') : $('.candidate-information').addClass('hidden') & $('.candidate-information').find('input,textarea').val('') ;
            })

            if(duration_from)
            {
                duration_from = duration_from.replace(' ', 'T');
                $('#durationfrom').val(duration_from);
            }

            if(duration_to)
            {
                duration_to = duration_to.replace(' ', 'T');
                $('#durationto').val(duration_to);
            }                     

            $("#election_form").on('submit', function(e) {
                e.preventDefault();
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

                if ($("#ques_flag").val() == 0 && $("#candidate_flag").val() == 0) {
                    $.unblockUI();
                    toastr.error('Info - Chose Main Feature')
                    return false;
                }
                
                $.ajax({
                    url: "{{ route('admin.election.update') }}",
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
                            toastr.success('Info - ' + data.success)
                        }
                    }
                })
            })
        })

    </script>
@endsection
