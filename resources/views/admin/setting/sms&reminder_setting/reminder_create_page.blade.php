@extends('layouts.back-app')
@section('breadcrumb')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">

            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ route('admin.election.index') }}">Home</a></li>
                    </li>
                    <li class="breadcrumb-item active">Reminder</li>
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
                            {{ ($election->reminderdescription != Null) ? " Updated Reminder" : " Add Reminder" }}
                          
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.sms.index') }}" class="text-danger"><i
                                    class="fas fa-arrow-alt-circle-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.sms.reminderupdate', ['id'=> $election->id]) }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row justify-content-md-center">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="description">Reminder Description*</label>
                                        <textarea class="textarea" name="reminder" id="description"
                                            placeholder="Place some text here"
                                            style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('description',$election->reminderdescription) }}</textarea>
                                    </div>
                                    <div class="col-sm-12 my-2">
                                        <div class="form-group mt-4">
                                            <div class="row mt-2">
                                                <div class="col-md-6">

                                                    <button type="submit" class="btn btn-outline-primary btn-block"><i
                                                            class="fas fa-save" aria-hidden="true"></i>
                                                            {{ ($election->reminderdescription != Null) ? " Updated Reminder" : " Save Reminder" }}</button>

                                                </div>
                                                <div class="col-md-6 mt-md-0 mt-2">

                                                    <a href="{{ route('admin.sms.index') }}" type="button"
                                                        class="btn btn-outline-success btn-block"><i
                                                            class="fas fa-reply-all"></i>
                                                        Election
                                                        List</a>
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
@endsection