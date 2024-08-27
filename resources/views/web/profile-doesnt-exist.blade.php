@extends('web.layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/web/profile.css?v=1')}}" />
@endsection
@section('content')
    <section class="about_me_section sec_padding">
        <div class="container">
            <div class="section_tittle style_2" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInDown;">
                <h5 class="base_color">{{__('global.sound_profile')}}</h5>
            </div>
            <div class="row ">
                <div class="col-12 text-center d-flex flex-wrap">
                    <div class="w-100 d-flex mb-5">
                    <div class="m-auto text-left">
                        <i class="fa fa-5x fa-exclamation-triangle"></i>
                    </div>
                    </div>
                    <br>
                    @if ($user && auth()->guard('user')->check() && auth()->guard('user')->user()->id == $user->id)
                        <div class="text-light m-auto text-left">
                            <h4 class="text-light">{{__('global.unvalid_profile')}}</h4>
                            <div class="text-center">
                                <h6 class="text-light">{{__('global.please_check_your')}}<a href="{{route('account.status')}}">{{__('global.account_status')}}</a></h6>
                            </div>
                            <br>
                            {{-- <h7>The reason might be one of the following : </h7> --}}
                            {{-- <ul>
                                <li>Email Not Verified <a href="{{route('verification.notice')}}"> Resend Email </a></li>
                                <li>Name and Country not entered <a href="{{route('account.edit')}}"> Add in <b>My Account</b></a></li>
                                <li>You didn't record the required pronunciations <a href="{{route('profile.edit')}}"> Go To <b>My Records</b></a></li>
                            </ul> --}}
                        </div>
                    @else
                        <div class="text-light m-auto text-center">
                        <h6 class="text-light">{{__("global.no_valid_profiles")}}</h6>
                        <br>
                        <p>{{__("global.wrong_turn")}}</p>
                        <br>
                        <a class="secondary-btn btn" href="{{route('web.get_landing')}}">{{__("global.go_home")}}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
