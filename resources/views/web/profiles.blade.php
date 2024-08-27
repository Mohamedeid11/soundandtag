@extends('web.layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/web/profileCard.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/profiles.css?v=1')}}" />
    <link rel="stylesheet" href="{{asset('css/web/login.css?v=1')}}" />
@endsection
@section('content')
    <section class="team_member_part sec_padding ft_font">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_tittle">
                        <h5>{{__("global.our_profiles")}}</h5>
                        <h2 style="color:var(--secondary-color); font-weight: 400">{{__("global.examples")}}</h2>
                    </div>
                    <form id="search-form" class="search_form">
                        <div class="acoount-type-check d-flex justify-content-center mx-auto mb-4 profile">
                            <input type="radio" id="account-type-all" hidden name="account_type" {{request()->input('account_type') != 'personal' && request()->input('account_type') != 'corporate' ? 'checked' : ''}} value="all">
                            <label for="account-type-all">{{__('global.all')}}</label>
                            <input type="radio" id="account-type-personal" hidden name="account_type" {{request()->input('account_type') == 'personal' ? 'checked' : ''}} value="personal">
                            <label for="account-type-personal">{{__('global.personal')}}</label>
                            <input type="radio" id="account-type-corporate" hidden name="account_type" {{request()->input('account_type') == 'corporate' ? 'checked' : ''}} value="corporate">
                            <label for="account-type-corporate">{{__('global.corporate')}}</label>
                        </div>

                        <div class="sidebar_part">
                            <div class="single_sidebar d-flex">
                                <div class="position-relative flex-fill h-100">
                                    <input type="text" placeholder="{{__('global.search')}}..." id="search_profiles" value="{{$search}}" name="search">
                                    <button type="submit" class="d-inline-block border-0 p-0 position-absolute">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <a href="{{route('web.get_profiles')}}" class="secondary-btn btn ml-2 d-flex justify-content-center align-items-center" style="border-radius: 6px">{{__('global.reset')}}</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="row members">
                @if(! $profiles->isEmpty())
                    @foreach($profiles as $profile)
                        <div class="col-lg-2 col-4">
                            @include('web.partials.profile-card')
                        </div>
                    @endforeach
                @else
                    <div class="m-auto">
                        <h3 class="text-white">{{__("global.no_results")}}</h3>
                    </div>
                @endif
            </div>
        <div class="justify-content-center d-flex">
            {!! $profiles->links() !!}
        </div>
        </div>

    </section>
@endsection
@section('scripts')
    <script>
        $('input[type=radio][name=account_type]').on('change',function () {
            $('#search-form').submit();
        });
    </script>
@endsection
