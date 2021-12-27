@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="back"><a href="{{ URL::previous() }}#{{ $candidate->id }}" class="btn btn-info border-radius"><i
                    class="fa fa-chevron-circle-left"></i> Exit</a></div>
        <div class="row mt-3">
            <div class="col-md-12 pdt-3">
                <div class="card shadow bordertop">
                    <div class="card-body">
                        <div class="mb-5">
                            <div class="text-center pt-2">
                                <strong>
                                    <span>အလုပ်အမှုဆောင်အဖွဲ့ဝင်အဖြစ် ရွေးချယ်ခံရန် ဆန္ဒပြုပုဂ္ဂိုလ်၏
                                        ကိုယ်ရေးမှတ်တမ်း</span>
                                </strong>
                            </div>
                            <hr style="height: 2px; background-color: #3c8dbc;">
                            <div class="col-xs-12">

                                <div class="text-center my-2">
                                    @if ($candidate->photo_url)
                                        <img class="rounded-image" src="{{ url($candidate->photo_url) }}"
                                            alt="User profile picture">
                                    @else
                                        <img class="rounded-image" src="{{ url('images/user.png') }}"
                                            alt="User profile picture">
                                    @endif
                                </div>

                                <div class="table-responsive mx-lg-5">
                                    <table class="table" style="width: 100%!important;">
                                        <tr>
                                            <td style="width:50%;">
                                                <p>@lang('admin.candidate_no')</p>
                                            </td>
                                            <td colspan="2">
                                                <p>{{ $candidate->candidate_no ?? '' }}</p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>@lang('admin.username')</td>
                                            <td colspan="2">
                                                <p>{{ $candidate->mname ?? '' }}</p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>@lang('admin.nrc_no')</td>
                                            <td colspan="2">
                                                <p>{{ $candidate->nrc_no ?? '' }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('admin.dob')</td>
                                            <td colspan="2">
                                                <p>{{ $candidate->dob ?? '' }}</p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>@lang('admin.gender')</td>
                                            <td colspan="2">
                                                <p>{{ $candidate->gender ?? '' }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                        <tr>
                                            <td>@lang('admin.position')</td>
                                            <td colspan="2">
                                                <p>{{ $candidate->position ?? '' }}</p>
                                            </td>
                                        </tr>
                                        <tr>

                                        <tr>
                                            <td>@lang('admin.phone_no')</td>
                                            <td colspan="2">
                                                <p>{{ $candidate->phone_no ?? '' }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('admin.email')</td>
                                            <td colspan="2">
                                                <p>{{ $candidate->email ?? '' }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('admin.address')</td>
                                            <td colspan="2">
                                                {!! $candidate->address ?? '' !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>@lang('admin.company')</td>
                                            <td colspan="2">
                                                <p>{{ $candidate->company ?? '' }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('admin.company_start_date')</td>
                                            <td colspan="2">
                                                <p>{{ $candidate->company_start_date ?? '' }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('admin.no_of_employee')</td>
                                            <td colspan="2">
                                                <p>{{ $candidate->no_of_employee ?? '' }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('admin.company_email')</td>
                                            <td colspan="2">
                                                <p>{{ $candidate->company_email ?? '' }}</p>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>@lang('admin.company_address')</td>
                                            <td colspan="2">
                                                {!! $candidate->company_address ?? '' !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>@lang('admin.experience')</td>
                                            <td colspan="2">
                                                {!! $candidate->experience ?? '' !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>@lang('admin.biography')</td>
                                            <td colspan="2">
                                                {!! $candidate->biography ?? '' !!}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

@endsection
