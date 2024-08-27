@extends("web.layouts.master")
@section('styles')
    <link rel="stylesheet" href="{{asset('css/web/profiles.css?v=1')}}" />

    <link rel="stylesheet" href="{{asset('css/web/home.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/login.css?v=1')}}" />

@endsection
@section('content')
    <section class="team_member_part ft_font sec_padding" style="background-color: #dddddd">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="section_tittle mb-4">
                        <h5>{{__('global.pricing')}}</h5>
                        <h2 style="color: var(--secondary-color)">{{__('global.our_plans', ['period'=> $trialPeriod])}}</h2>
                    </div>
                </div>
            </div>
            <input type="radio" id="pricing-personal" hidden name="pricing" checked value="personal">
            <input type="radio" id="pricing-corporate" hidden name="pricing" value="corporate">
            <div class="acoount-type-check d-flex justify-content-center mx-auto mb-4">
                <label for="pricing-personal">{{__('global.personal')}}</label>
                <label for="pricing-corporate">{{__('global.corporate')}}</label>
            </div>
            <div class="d-flex members pricing">
                <div class="row members_personal w-50 justify-content-center pricing_personal">
                    @foreach($personalPlans as $plan)
                        <div class="col-lg-3 col-sm-6">
                            @include('web.partials.plan-card')
                        </div>
                    @endforeach
                </div>
                <div class="row members_corporate w-50 pricing_corporate">
                    @foreach($corporatePlans as $plan)
                        <div class="col-lg-3 col-sm-6">
                            @include('web.partials.plan-card')
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="banner_btn d-flex justify-content-center">
                @if(auth()->guard('user')->check())
                    <a href="{{route('account.status')}}" class="primary-btn btn rounded" >{{__("global.account_status")}}</a>
                @else
                    <a href="{{route('web.get_register')}}" class="primary-btn btn rounded" >{{__("global.register")}}</a>
                @endif
            </div>
        </div>
    </section>
@endsection
