@extends('web.layouts.master')
@section('styles')
<link rel="stylesheet" href="{{asset('css/web/about.css?v=1')}}" />
    {{-- <link rel="stylesheet" href="{{asset('css/web/side-nav.css?v=1')}}" /> --}}
    {{-- <link rel="stylesheet" href="{{asset('css/web/card.css?v=1')}}" /> --}}
    <link rel="stylesheet" href="{{asset('css/web/profileCard.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/profiles.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/login.css?v=1')}}" />
@endsection
    
@section('content')

<section class="team_member_part sec_padding ft_font">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section_tittle">
                    <h5>{{__("global.unsubscribe")}}</h5>
                    <h2 style="color:var(--secondary-color); font-weight: 400">{{__("global.stop_seeing_our_email")}}</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <form action="{{route('web.unsubscribe_trying_user', ['t_user_id' => $t_user_id, 't_user_email' => $t_user_email])}}" method="post">
                @csrf
                <div class="form-group">
                    <button type="submit" class="btn btn-primary p-3"><span class="h4">{{__("global.unsubscribe")}}</span></button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection

@section('scripts')
    
@endsection