@extends('layouts.back-app')
@section('breadcrumb')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            @if (count($elections) > 0)
            <div class="col-sm-12 my-3">
                <h1>Election List</h1>
            </div>
            <br>
            @foreach ($elections as $election)
            <div class="card ml-3 shadow border border-primary" style="width: 20rem;">
                <div class="card-body ">
                    <h6 class="card-subtitle  text-muted  font-bold text-lg text-theme-1">{{ $election->name }}</h6>
                    <p class="card-text">Start Date: {{ $election->start_time}}</p>
                    <p class="card-text">End Date: {{ $election->end_time}}</p>
                    <p class="card-text">Decprition:<br> {{ $election->description}}</p>
                    <div class="row mt-2">
                        <div class="col">
        
                        <a class="btn btn-sm btn-primary justify-content-around" style="font-size: 1.5ex"
                        href="{{route('admin.smscreatepage.index',['id' => $election->id])}}" class="card-link" ><i class="fas fa-sms"></i> {{
                        ($election->ssmsdescription) ? " Add SMS" : "Updated SMS" }}</a>
                        </div>
                        <div class="col">
                        <a class="btn  btn-sm btn-secondary justify-content-around" href="{{route('admin.remindercreatepage.index',['id' => $election->id])}}"
                            class="card-link" style="font-size: 1.5ex" class="mt-1"> <i class="fas fa-clock"></i>{{ ($election->ssmsdescription) ? " Add Reminder" : "Updated Reminder"
                            }}</a>
                        </div>
                    </div>
                    {{-- <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-flat btn-success"><i class="fas fa-save"></i>
                                {{ ($election->ssmsdescription) ? " Save Reminder" : "Updated Reminder" }}</button>

                            <a href="{{ route('admin.sms.index') }}" type="button" class="btn btn-flat btn-danger"><i
                                    class="fas fa-reply-all"></i> Election
                                List</a>
                        </div>
                    </div> --}}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    </div>
    </div>
    @else
    <div class="col-sm-6">
        <h1 class="text-justify">There is No Elction</h1>
    </div>
    @endif
    </div>
    </div><!-- /.container-fluid -->
</section>
@endsection