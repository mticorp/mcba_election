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
                        <li class="breadcrumb-item active"><a
                                href="{{ route('admin.dashboard', $election->id) }}">Dashobard</a></li>
                        <li class="breadcrumb-item active">Candidates List</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-red card-outline">
                        <div class="card-header">
                            <div class="card-title">Candidates List</div>
                            <div class="card-tools">
                                @if ($election->status == 0)
                                
                            <a href="{{route('candidate-excel-export')}}" class="btn btn-dark btn-flat"><i
                                class="fa fa-download"></i> Download Excel</a>
                                <a href="{{ route('admin.candidate.excel.import', $election->id) }}"
                                    class="btn btn-danger btn-flat"><i class="fas fa-file-excel" aria-hidden="true"></i>
                                    Excel Import</a>
                                <a href="{{ route('admin.candidate.create', $election->id) }}"
                                    class="btn btn-flat btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add
                                    Candidate</a>
                                @endif

                                <button type="button" id="btn_print_candidate" class="btn btn-flat btn-info"><i
                                        class="fa fa-print" aria-hidden="true"></i> Print</button>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="candidatetable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Candidate No</th>
                                        <th>Phone Number</th>
                                        <th>Created_Date</th>
                                        <th>Modified_Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title"><i class="fas fa-exclamation-circle"></i> Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 align="center" style="margin:0;">Are you sure you want to remove this data?</h6>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div style="display: none;">
        <div id="candidate_print_content">
            <h1 style="text-align: center;font-weight:bold; padding-top:2px;margin-top:2px;">{{ $election->name }}</h1>
            <h3 style="text-align: center;line-height:40px;font-weight:bold;">{{ $election->candidate_title }}</h3>
            @foreach ($candidates as $key => $candidate)

                <div id="print_table">
                    <table class="table table-borderless" style="margin-bottom: 1200px; margin-top:40px; table-layout:fixed; width:100%;">
                        <tr>
                            <td style="width:30%;">
                                <p>ကိုယ်စားလှယ်လောင်းအမှတ်</p>
                            </td>
                            <td style="width:30%">
                                <p>{{ $candidate->candidate_no }}</p>
                            </td>
                            <td rowspan="3" class="text-center">
                                @if ($candidate->photo_url == null)
                                    <img class="profile-user-img img-responsive" style="margin-top:15px;"
                                        src="{{ url('images/user.png') }}" alt="User profile picture">
                                @else
                                    <img class="profile-user-img img-responsive" style="margin-top:15px;"
                                        src="{{ url($candidate->photo_url) }}" alt="User profile picture">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>အမည်</p>
                            </td>
                            <td>
                                <p>{{ $candidate->mname }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>နိုင်ငံသားမှတ်ပုံတင်အမှတ်</p>
                            </td>
                            <td>
                                <p>{{ $candidate->nrc_no }}</p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <p>မွေးသက္ကရာဇ်</p>
                            </td>
                            <td>
                                <p>{{ $candidate->dob }}</p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <p>ကျား/မ</p>
                            </td>
                            <td colspan="2">
                                <p>{{ $candidate->gender }}</p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <p>ရာထူး</p>
                            </td>
                            <td colspan="2">
                                <p>{{ $candidate->position }}</p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <p>ပညာအရည်အချင်း</p>
                            </td>
                            <td colspan="2">
                                <p>{{ $candidate->education }}</p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <p>ဆက်သွယ်ရန်ဖုန်</p>
                            </td>
                            <td colspan="2">
                                <p>{{ $candidate->phone_no }}</p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <p>အီးမေးလ်</p>
                            </td>
                            <td colspan="2">
                                <p>{{ $candidate->email }}</p>
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
                        {{-- adding end here --}}





                        <tr>
                            <td>
                                <p>လုပ်ငန်းအမည်</p>
                            </td>
                            <td colspan="2">
                                <p>{{ $candidate->company }}</p>
                            </td>
                        </tr>

                        {{-- adding start here --}}

                        <tr>
                            <td>
                                <p>ကုမ္ပဏီ စတင်သည့် ခုနှစ်</p>
                            </td>
                            <td>
                                <p>{{ $candidate->company_start_date }}</p>
                            </td>
                        </tr>

                        {{-- adding end here --}}

                        <tr>
                            <td>
                                <p>အလုပ်သမား အရေအတွက်</p>
                            </td>
                            <td colspan="2">
                                <p>{{ $candidate->no_of_employee }}</p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <p>ကုမ္ပဏီ အီးမေးလ်</p>
                            </td>
                            <td colspan="2">
                                <p>{{ $candidate->company_email }}</p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <p>ကုမ္ပဏီ ဆက်သွယ်ရန်ဖုန်း</p>
                            </td>
                            <td colspan="2">
                                <p>{{ $candidate->company_phone }}</p>
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
        $(document).ready(function() {
            var election_id = "{{ $election->id }}";
            $('#candidatetable').DataTable({
                processing: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                serverSide: true,
                ajax: {
                    url: "{{ url('/admin/candidate/index') }}/" + election_id,
                },
                columns: [{
                        data: 'rownum',
                        name: 'rownum',
                    },
                    {
                        data: 'photo_url',
                        name: 'photo_url',
                        render: function(data, type, full, meta) {
                            if (data != null) {
                                return "<img src={{ URL::to('/') }}" + data +
                                    " width='70' class='img-thumbnail' />";
                            } else {
                                return "<img src='{{ URL::to('/images/user.png') }}' width='70' class='img-thumbnail' />";
                            }
                        },
                        orderable: false
                    },
                    {
                        data: 'mname',
                        name: 'mname'
                    },
                    {
                        data: 'candidate_no',
                        name: 'candidate_no',
                    },
                    {
                        data: 'phone_no',
                        name: 'phone_no'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        render: function(data, type, full, meta) {
                            return data;
                        },
                    }
                ],
            });

            $('#btn_print_candidate').on('click', function() {
                $.print("#candidate_print_content");
            })

            var candidate_id;

            $(document).on('click', '.detail', function() {
                candidate_id = $(this).attr('id');
                var url =
                    '{{ route('admin.candidate.detail', ['candidate_id' => ':candidate_id', 'election_id' => ':election_id']) }}';
                url = url.replace(':candidate_id', candidate_id);
                url = url.replace(':election_id', election_id);

                window.location.href = url;
            })

            $(document).on('click', '.edit', function() {
                candidate_id = $(this).attr('id');
                var url =
                    '{{ route('admin.candidate.edit', ['candidate_id' => ':candidate_id', 'election_id' => ':election_id']) }}';
                url = url.replace(':candidate_id', candidate_id);
                url = url.replace(':election_id', election_id);

                window.location.href = url;
            })

            $(document).on('click', '.delete', function() {
                candidate_id = $(this).attr('id');
                $('#confirmModal').modal('show');
            });

            $('#ok_button').click(function() {
                $.ajax({
                    url: "{{ url('/admin/candidate/destroy/') }}/" + election_id + "/" +
                        candidate_id,
                    beforeSend: function() {
                        $('#ok_button').text('Deleting...');
                    },
                    success: function(data) {
                       if(data.success)
                       {
                            $('#confirmModal').modal('hide');
                            $('#ok_button').text('OK');
                            toastr.success('Info - ' + data.success)
                            $('#candidatetable').DataTable().ajax.reload();
                       }
                    }
                })
            });
        })

    </script>
@endsection
