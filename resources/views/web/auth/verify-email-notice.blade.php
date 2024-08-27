@extends('web.layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/web/login.css?v=1')}}" />
@endsection
@section('content')
    <section class="contact_section ft_font">
        <div class="container">
            @if(session()->has('success'))
                <label class="w-100 alert alert-success alert-dismissible fade show" role="alert">
                    {{session()->get('success')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </label>
            @endif
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="contact_section_content pr_60 pt-5 section_padding">
                        <h2 style="font-weight: 400">{{__('global.notice')}}</h2>
                        <p>{{__('global.verify_email_notice_1')}} <a href="mailto:{{auth()->guard('user')->user()->email}}">{{auth()->guard('user')->user()->email}}</a> {{__('global.verify_email_notice_2')}}<br></p>
                        <form  class="contact_form" action="{{route('verification.send')}}" method="post" id="contactForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="single_contact_form mb-4">
                                        <p>{{__('global.click_below_btn')}}:</p>
                                        <button type="submit" class="btn primary-btn text-uppercase px-5">{{__('global.send')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact_shape_1">
            <img src="{{asset('images/img/icon/testimonial_icon_4.png')}}" alt="Illustrated image" data-parallax='{"x": 0, "y": -150, "rotateZ":0}'>
        </div>
    </section>
@endsection
