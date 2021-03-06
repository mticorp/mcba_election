@extends('layouts.back-app')
@section('breadcrumb')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">

            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ route('admin.election.index') }}">Home</a></li>
                    </li>
                    <li class="breadcrumb-item active">Member Announce</li>
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
                            {{ ($setting->member_annouce != Null) ? " Update Member Announce" : " Add Member Announce" }}
                        </h3>                        
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.member.announce.update') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row justify-content-md-center">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="member_annouce">Member Announce Description*</label>
                                        <textarea class="textarea" name="member_annouce" id="member_annouce"
                                            placeholder="Place some text here"
                                            style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('reminder_text',$setting->member_annouce) }}</textarea>
                                    </div>

                                    <div class="col-sm-12 my-2">

                                        <div class="form-group mt-4">

                                            <div class="row mt-2">
                                                <a type="button" id="addVoterName"><i class="fas fa-plus"
                                                        aria-hidden="true"></i>
                                                    Add Member Name</a>
                                                {{-- <a type="button" id="addShareCount" style="user-select: none; margin-left:10px;"><i class="fas fa-plus"
                                                        aria-hidden="true"></i> Add Share Count</a>               --}}

                                                
                                            </div>
                                        </div>
                                    </div>

                                     <p style="color: red; font-family: arisan; font-weight: bold">
                                                    &nbsp;&nbsp; (Note: &nbsp; "[:MemberName]" refer to specific member's
                                                    name)</p>

                                    <div class="col-sm-12 my-2">
                                        <div class="form-group mt-4">
                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-outline-primary btn-block"><i
                                                            class="fas fa-save" aria-hidden="true"></i>
                                                        {{ ($setting->member_annouce != Null) ? " Update Member Announce" : " Save Member Announce" }}</button>
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
    $("#addVoterName").on('click',function(e){
        let cursorPos = $('#member_annouce').prop('selectionStart');
        let text = $("#member_annouce").val();
        var textBefore = text.substring(0,  cursorPos );
        var textAfter  = text.substring( cursorPos, text.length );
        $('#member_annouce').val( textBefore+ " [:MemberName] " +textAfter );        
        e.preventDefault();
    })

    $("#addShareCount").on('click',function(e){
        let cursorPos = $('#member_annouce').prop('selectionStart');
        let text = $("#member_annouce").val();
        var textBefore = text.substring(0,  cursorPos );
        var textAfter  = text.substring( cursorPos, text.length );
        $('#member_annouce').val( textBefore+ " [:ShareCount] " +textAfter );
        e.preventDefault();
    })
    
</script>
@endsection