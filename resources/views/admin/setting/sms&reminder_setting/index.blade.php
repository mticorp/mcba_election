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
            <div class="card ml-3 shadow border border-primary" style="width: 30rem;">
                <div class="card-body ">
                    <h6 class="card-subtitle  text-muted  font-bold text-lg text-theme-1" style="font-weight: bold">{{ $election->name }}</h6>
                    <p class="card-text">Start Date: {{ $election->start_time}}</p>
                    <p class="card-text">End Date: {{ $election->end_time}}</p>
                    <p class="card-text">Decprition: {{ $election->description}}</p>
                    <div class="row justify-content-around">
                        <div class="justify-content-around">
        
                        <a class="btn  btn-primary " style="font-size: 1.5ex"
                        href="{{route('admin.smscreatepage.index',['id' => $election->id])}}" class="card-link" ><i class="fas fa-sms"></i> {{
                        ($election->smsdescription == Null) ? " Add SMS" : "Updated SMS" }}</a>
                        </div>

                        <div class="justify-content-around" >
                        <a class="btn btn-secondary " href="{{route('admin.remindercreatepage.index',['id' => $election->id])}}"
                            class="card-link" style="font-size: 1.5ex" class="mt-1"> <i class="fas fa-clock"></i>{{ ($election->reminderdescription != Null) ? " Updated Reminder" : " Add Reminder"
                            }}</a>
                        </div>
                        
                        <div class="justify-content-around" >
                            <a class="btn btn-info " href="{{route('admin.description.index',['id' => $election->id])}}"
                                class="card-link" style="font-size: 1.5ex" class="mt-1"> <i class="fas fa-info-circle"></i>{{ ($election->election_title_description != Null) ? " Updated Title Description" : " Add Title Description"
                                }}</a>
                            </div>
                    </div>
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