@extends('layouts.back-app')
@section('breadcrumb')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">

            <div class="col-sm-12">
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
<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-md-8">
                <div class="card card-red card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ ($setting->member_sms_text == Null ) ? " Add SMS" : "Update SMS" }}
                        </h3>                        
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.member.sms.update') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row justify-content-md-center">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="sms_text">SMS Text*</label>
                                        <textarea class="textarea" name="sms_text" id="sms_text"
                                            placeholder="Place some text here"
                                            style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('sms_text',$setting->member_sms_text) }}</textarea>
                                    </div>


                                    <div class="col-sm-12 my-2">
                                        <div class="form-group mt-4">
                                            <div class="row mt-2">
                                                <a type="button" id="addMemberName" style="user-select: none;"><i class="fas fa-plus"
                                                        aria-hidden="true"></i> Add Member Name</a>                                                                                
                                            </div>                                            
                                        </div>
                                    </div>       
                                    
                                    <p style="color: red; font-family: arisan; font-weight: bold">
                                                    &nbsp;&nbsp; (Note: &nbsp; "[:MemberName]" refer to specific member's
                                                    name )</p>

                                    <div class="col-sm-12 my-2">
                                        <div class="form-group mt-4">
                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-outline-primary btn-block"><i
                                                            class="fas fa-save" aria-hidden="true"></i>
                                                        {{ ($setting->member_sms_text == Null ) ? " Save SMS" : "Update SMS" }}</button>

                                                </div>                                                
                                            </div>
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
@endsection
@section('javascript')
<script>
    $("#addMemberName").on('click',function(e){
        let cursorPos = $('#sms_text').prop('selectionStart');
        let text = $("#sms_text").val();
        var textBefore = text.substring(0,  cursorPos );
        var textAfter  = text.substring( cursorPos, text.length );
        $('#sms_text').val( textBefore+ " [:MemberName] " +textAfter );        
        e.preventDefault();
    })   

</script>
@endsection