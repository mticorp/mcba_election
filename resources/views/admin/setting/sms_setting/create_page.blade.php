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
                    </li>
                    <li class="breadcrumb-item active">SMS</li>
                </ol>
            </div>
        </div>
    </div>
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
                            Add SMS
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.sms.index') }}" class="text-danger"><i
                                    class="fas fa-arrow-alt-circle-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.sms.update', ['id'=> $election->id]) }}" method="POST">
                            @csrf
                            @method('POST')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">SMS Description*</label>
                                            <textarea class="textarea" name="sms" id="description"
                                                placeholder="Place some text here"
                                                style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('description',$election->smsdescription) }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-flat btn-success"><i class="fas fa-save"></i>
                                        {{ ($election->smsdescription == Null ) ? " Save SMS" : "Updated SMS" }}</button>
                                        
                                    <a href="{{ route('admin.sms.index') }}" type="button"
                                        class="btn btn-flat btn-danger"><i class="fas fa-reply-all"></i> Election
                                        List</a>
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