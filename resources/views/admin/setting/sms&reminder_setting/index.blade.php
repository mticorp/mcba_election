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
                <div class="card ml-3" style="width: 18rem;">
                    <div class="card-body">
                        <h6 class="card-subtitle  text-muted  font-bold text-lg text-theme-1">{{ $election->name }}</h6>
                        <p class="card-text">Start Date: {{ $election->start_time}}</p>
                        <p class="card-text">End Date: {{ $election->end_time}}</p>
                        <p class="card-text">Decprition:<br> {{ $election->description}}</p>
                        <a href="{{route('admin.smscreatepage.index',['id' => $election->id])}}" class="card-link">Add SMS</a>
                        <a href="{{route('admin.remindercreatepage.index',['id' => $election->id])}}" class="card-link">Add Reminder</a>
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