@extends('layouts.back-app')

@section('breadcrumb')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Dashboard</h1>
        </div>
        <div class="col-sm-6">
          {{-- <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active">Blank Page</li>
          </ol> --}}
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
           <div class="col-lg-3 col-6">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>{{$percent_voting_count}} %</h3>

                  <p>Voting Record</p>
                </div>
                <div class="icon">
                  <i class="ion ion-android-checkbox-outline"></i>
                </div>
            <a href="{{route('admin.election.voting-record',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
             <div class="col-lg-3 col-6">
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>{{$pecrent_voting_reject_count}} %</h3>

                  <p>Reject Voting Record</p>
                </div>
                <div class="icon">
                  <i class="ion ion-alert-circled"></i>
                </div>
            <a href="{{route('admin.election.rejectvoting-record',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div> 
            <!-- ./col -->
             <div class="col-lg-3 col-6">
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>{{$percent_not_voted_count}} %</h3>

                  <p>Not Voted Record</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-minus"></i>
                </div>
            <a href="{{route('admin.election.notvoted-record',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
             <div class="col-lg-3 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>{{$percent_voting_count}} %</h3>

                  <p>Voting Result</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-list-outline"></i>
                </div>
            <a href="{{route('admin.election.voting-result',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div> 
          </div>          
          <div class="row">
            <div class="col-lg-3 col-6">
               <div class="small-box bg-info">
                 <div class="inner">
                    <h3>{{$total_member_count}} </h3>
 
                    <p>Total Number of Members</p>
                   
                 </div>
                 <div class="icon">
                   <i class="ion ion-android-checkbox-outline"></i>
                 </div>
             <a href="{{route('admin.election.voting-record',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
               </div>
             </div>
             <!-- ./col -->
              <div class="col-lg-3 col-6">
               <div class="small-box bg-info">
                 <div class="inner">
                    <h3>{{$total_register_count}} </h3>
 
                    <p>Total Number of Registers</p>
                 </div>
                 <div class="icon">
                   <i class="ion ion-android-checkbox-outline"></i>
                 </div>
             <a href="{{route('admin.election.rejectvoting-record',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
               </div>
             </div> 
             <!-- ./col -->
              <div class="col-lg-3 col-6">
               <div class="small-box bg-info">
                 <div class="inner">
                    <h3>{{$voting_count}} </h3>
 
                    <p>Total Number of Voted Person</p>                   
                 </div>
                 <div class="icon">
                   <i class="ion ion-android-checkbox-outline"></i>
                 </div>
             <a href="{{route('admin.election.notvoted-record',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
               </div>
             </div>
             <!-- ./col -->
              <div class="col-lg-3 col-6">
               <div class="small-box bg-info">
                 <div class="inner">
                   <h3>{{$not_voted_count}} </h3>
 
                   <p>Total Number of Not Voted Person</p>
                 </div>
                 <div class="icon">
                   <i class="ion ion-ios-list-outline"></i>
                 </div>
             <a href="{{route('admin.election.voting-result',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
               </div>
             </div> 
           </div>
          {{-- <div class="row">
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>{{$tot_share_amt}}</h3>

                  <p>Total Share Amount</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-list-outline"></i>
                </div>
                <!-- <a href="{{route('admin.election.voting-result',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-primary">
                <div class="inner">
                  <h3>{{$tot_voted_share_amt}}</h3>

                  <p>Total Voted Share amount</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-list-outline"></i>
                </div>
                <!-- <a href="{{route('admin.election.voting-result',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a> -->
              </div>
            </div> 
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  @if($voter_voted_count==0 || $voter_count==0 )
                  <h3>0</h3>
                  @else
                  <h3>{{number_format((($tot_voted_share_amt/$tot_share_amt)*100),2)}} %</h3>
                  @endif
                  <p>Percentage of voted Share (%)</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-list-outline"></i>
                </div>
                <!-- <a href="{{route('admin.election.voting-result',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a> -->
              </div>
            </div>          
          <!-- /.row -->
          <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>{{$voter_count}}</h3>

                  <p>Total Voter(Shareholder)</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-list-outline"></i>
                </div>
                <!-- <a href="{{route('admin.election.voting-result',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>{{$voter_voted_count}}</h3>

                  <p>Total Voted Person</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-list-outline"></i>
                </div>
                <!-- <a href="{{route('admin.election.voting-result',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a> -->
              </div>
            </div> 
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                @if($voter_voted_count==0 || $voter_count==0 )
                <h3>0</h3>
                @else
                <h3>{{number_format((($voter_voted_count/$voter_count)*100),2)}} %</h3>
                @endif
                  <p>Percentage of voted Person (%)</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-list-outline"></i>
                </div>
                <!-- <a href="{{route('admin.election.voting-result',$election->id)}}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a> -->
              </div>
            </div> 
            
            <!-- ./col -->
          </div> --}}
          <!-- /.row -->
          {{-- row --}}
          <div class="row">
            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Pie Chart</h3>
                    <div class="card-tools">

                    </div>
                  </div>
                  <div class="card-body">
                    <canvas id="pieChart" style="min-height:250px; height: 250px; max-height: 250px; max-width:100%;"></canvas>
                  </div>
                </div>
              </div>
          </div>
          <div class="row">
              <div class="col-md-8">
                  <!-- USERS LIST -->
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Latest Candidates</h3>
                      <div class="card-tools">
                        {{-- <span class="badge badge-danger">8 New Members</span> --}}
                        <a href="#" class="btn btn-tool btn-sm">
                            <button type="button" id="btn_print_candidate" class="btn btn-flat"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                        </a>
                      </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($candidates as $candidate)
                            <div class="col-md-3 users-list clearfix text-center py-2">
                                @if($candidate->photo_url == null)
                                <img src="{{url('images/user.png')}}" alt="User Image" class="rounded-lg">
                                @else
                                <img src="{{url($candidate->photo_url)}}" alt="User Image" class="rounded-lg">
                                @endif

                                <a class="users-list-name" href="{{route('admin.candidate.detail',['candidate_id' => $candidate->id,'election_id' => $candidate->election_id])}}">{{$candidate->mname}}</a>
                                <span class="users-list-date pt-2">{{ Carbon\Carbon::parse($candidate->created_at)->diffForHumans() }}</span>
                            </div>
                            @endforeach
                        </div>
                        <!-- /.users-list -->
                      </div>
                    <div class="card-footer text-center">
                      <a href="{{route('admin.candidate.index',$election->id)}}">View All Candidates <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                    <!-- /.card-footer -->
                  </div>
              </div>
              @if($election->ques_flag == 1)
              <div class="col-md-4">
                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-question-circle"></i> Questions List</h3>
                      </div>
                      <div class="card-body">
                        @if(count($ques) > 0)
                        @foreach ($ques as $que)
                            <!-- Info Boxes Style 2 -->
                            <div class="info-box mb-3 bg-success">
                            <span class="pt-1 size-50">{{$que->no}}</span>

                            <div class="info-box-content">
                              <span style="width: 300px!important;">{!! $que->ques !!}</span>
                            </div>
                            <!-- /.info-box-content -->
                            </div>
                        @endforeach
                        @else
                            <div class="info-box-content text-center">
                            <p style="line-height: 30px">No Data Avaliable!</p>
                            </div>
                        @endif
                      </div>
                      <div class="card-footer text-center">
                        <a href="{{route('admin.question.index',$election->id)}}">View All Questions <i class="fas fa-arrow-circle-right"></i></a>
                      </div>
                  </div>
              </div>
              @endif

          </div>
          {{-- /.row --}}

      </div>
  </section>

  <div style="display: none;">
    <div id="candidate_print_content">
      <h1 style="text-align: center;font-weight:bold; padding-top:2px;margin-top:2px;">{{$election->name}}</h1>
      <h3 style="text-align: center;line-height:40px;font-weight:bold;">{{$election->candidate_title}}</h3>
      @foreach($candidates as $key=>$candidate)
      <div id="print_table">
          <table class="table table-striped" style="margin-bottom: 1200px; table-layout:fixed; width:100%;">
        <tr>
          <td style="width:30%;">
            <p>ကိုယ်စားလှယ်လောင်းအမှတ်</p>
          </td>
          <td style="width:30%">
            <p>{{$candidate->candidate_no}}</p>
          </td>
          <td rowspan="3" style="border:1px solid black;" class="text-center">
             @if($candidate->photo_url == null)
            <img class="profile-user-img img-responsive" style="margin-top:15px;" src="{{url('images/user.png')}}" alt="User profile picture">
            @else
            <img class="profile-user-img img-responsive" style="margin-top:15px;" src="{{url($candidate->photo_url)}}" alt="User profile picture">
            @endif
          </td>
        </tr>
        <tr>
          <td>
            <p>အမည်</p>
          </td>
          <td>
            <p>{{$candidate->mname}}</p>
          </td>
        </tr>      
        <tr>
          <td>
            <p>နိုင်ငံသားမှတ်ပုံတင်အမှတ်</p>
          </td>
          <td>
            <p>{{$candidate->nrc_no}}</p>
          </td>
        </tr>

        <tr>
          <td>
            <p>မွေးသက္ကရာဇ်</p>
          </td>
          <td>
            <p>{{$candidate->dob}}</p>
          </td>
        </tr>

        <tr>
          <td>
            <p>ကျား/မ</p>
          </td>
          <td colspan="2">
            <p>{{$candidate->gender}}</p>
          </td>
        </tr>

        <tr>
          <td>
            <p>ရာထူး</p>
          </td>
          <td colspan="2">
            <p>{{$candidate->position}}</p>
          </td>
        </tr>

        <tr>
          <td>
            <p>ပညာအရည်အချင်း</p>
          </td>
          <td colspan="2">
            <p>{{$candidate->education}}</p>
          </td>
        </tr>

        <tr>
          <td>
            <p>ဆက်သွယ်ရန်ဖုန်</p>
          </td>
          <td colspan="2">
            <p>{{$candidate->phone_no}}</p>
          </td>
        </tr>
        
        <tr>
          <td>
            <p>အီးမေးလ်</p>
          </td>
          <td colspan="2">
            <p>{{$candidate->email}}</p>
          </td>
        </tr>

        <tr>
          <td>
            <p>နေရပ် လိပ်စာ အပြည့်အစုံ</p>
          </td>
          <td colspan="2">
          {!! $candidate->address !!}
          </td>
        </tr>
     
        <tr>
          <td>
            <p>လုပ်ငန်းအမည်</p>
          </td>
          <td colspan="2">
            <p>{{$candidate->company}}</p>
          </td>
        </tr>

       

        <tr>
          <td>
            <p>ကုမ္ပဏီ စတင်သည့် ခုနှစ်</p>
          </td>
          <td>
            <p>{{$candidate->company_start_date}}</p>
          </td>
        </tr>

        <tr>
          <td>
            <p>အလုပ်သမား အရေအတွက်</p>
          </td>
          <td colspan="2">
            <p>{{$candidate->no_of_employee}}</p>
          </td>
        </tr>

        <tr>
          <td>
            <p>ကုမ္ပဏီ အီးမေးလ်</p>
          </td>
          <td colspan="2">
            <p>{{$candidate->company_email}}</p>
          </td>
        </tr>

        <tr>
          <td>
            <p>ကုမ္ပဏီ ဆက်သွယ်ရန်ဖုန်း</p>
          </td>
          <td colspan="2">
            <p>{{$candidate->company_phone}}</p>
          </td>
        </tr>
        
        <tr>
          <td>
            <p>ကုမ္ပဏီ လုပ်ငန်း လိပ်စာ အပြည့်အစုံ</p>
          </td>
          <td colspan="2">
          {!! $candidate->company_address !!}
          </td>
        </tr>
        <tr>
          <td>
            <p>Organization/Association Experience</p>
          </td>
          <td colspan="2">
          {!! $candidate->experience !!}
          </td>
        </tr>        
      </table>        
      </div>
      @endforeach
    </div>
  </div>
@endsection
@section('javascript')
    <script>
        $(document).ready(function(){
            $('#btn_print_candidate').on('click', function() {
              $.print("#candidate_print_content");
            })

            var percent_voting_count = "{{$percent_voting_count}}";
            var pecrent_voting_reject_count = "{{$pecrent_voting_reject_count}}";
            var percent_not_voted_count = "{{$percent_not_voted_count}}";

            if(percent_voting_count == 0 && percent_voting_count == 0 && percent_voting_count==0)
            {
                var pieData = {
                    labels: [
                        'No Data',
                        'Voting Record',
                        'Reject Voting Record',
                        'Not Voted Record',
                    ],
                    datasets: [
                        {
                            data: [100],
                            backgroundColor : ['#343a40', '#28a745', '#dc3545','#ffc107'],
                        }
                    ]
                }
            }else{
                var pieData = {
                    labels: [
                        'Voting Record',
                        'Reject Voting Record',
                        'Not Voted Record',
                    ],
                    datasets: [
                        {
                            data: [percent_voting_count,pecrent_voting_reject_count,percent_not_voted_count],
                            backgroundColor : ['#28a745', '#dc3545', '#ffc107'],
                        }
                    ]
                }
            }

            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieOptions     = {
                maintainAspectRatio : false,
                responsive : true,
                tooltips: {
                    callbacks: {
                        label: (tooltipItem, data) => {
                            var value = data.datasets[0].data[tooltipItem.index];
                            var total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                            var pct = 100 / total * value;
                            var pctRounded = Math.round(pct * 10) / 10;
                            return ' (' + value + '%)';
                        }
                    }
                }
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            var pieChart = new Chart(pieChartCanvas, {
                type: 'pie',
                data: pieData,
                options: pieOptions
            })
        })
    </script>
@endsection
