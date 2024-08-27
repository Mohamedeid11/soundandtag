@extends('web.layouts.master')
@section('styles')
<link rel="stylesheet" href="{{asset('css/web/login.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/contact.css?v=1')}}" />
@endsection
@section('content')
<!-- about part here -->
<section class="contact_section ft_font">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                @if(session()->has('success'))
                <label class="w-100 alert alert-success alert-dismissible fade show" role="alert">
                    {{session()->get('success')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </label>
                @endif
                <div class="contact_section_content pr_60 section_padding pt-4">
                    <h2 style="font-weight: 400">{{__("global.get_in_touch")}}</h2>
                    {{-- <p>If your question or comment is about a specific
                            article, the usi the Discuss this
                            page link on the problem page. If you areuch message when you try to edit. </p> --}}
                    <form class="contact_form" action="" method="post" id="contactForm">
                        @csrf
                        <div class="row">

                            @if(!auth()->guard('user')->user() || !auth()->guard('user')->user()->name )
                            <div class="col-lg-12">
                                <div class="single_contact_form form-group">
                                    <label for="name-input">{{__('global.name')}} <sup class="required">*</sup></label>
                                    <input type="text" name="name" id="name-input" class="form-control cu_input @if($errors->has('name')) is-invalid @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.name')])}}" value="{{inp_value(null, 'name')}}" required>
                                    @if($errors->has('name'))
                                    <div class="invalid-feedback">
                                        @foreach($errors->get('name') as $error)
                                        <span>{{$error}}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if(!auth()->guard('user')->user() )
                            <div class="col-lg-12">
                                <div class="single_contact_form form-group">
                                    <label for="email-input">{{__('global.email')}} <sup class="required">*</sup></label>
                                    <input type="text" name="email" id="email-input" class="form-control cu_input @if($errors->has('email')) is-invalid @endif" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.email')])}}" value="{{inp_value(null, 'email')}}" required>
                                    @if($errors->has('email'))
                                    <div class="invalid-feedback">
                                        @foreach($errors->get('email') as $error)
                                        <span>{{$error}}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            <div class="col-lg-12">
                                <div class="single_contact_form form-group">
                                    <label for="message-input">{{__('global.message')}} <sup class="required">*</sup></label>
                                    <textarea name="message" id="message-input" class="form-control cu_input @if($errors->has('message')) is-invalid @endif" required>{{inp_value(null, 'message')}}</textarea>
                                    @if($errors->has('message'))
                                    <div class="invalid-feedback">
                                        @foreach($errors->get('message') as $error)
                                        <span>{{$error}}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="single_contact_form form-group">
                                    {!! htmlFormSnippet() !!}
                                </div>
                                
                                <div class="mt-2 single_contact_form form-group">
                                    <span class="recaptcha-error d-none alert alert-danger">Recaptcha is required</span>
                                </div>
                                
                                @if($errors->has('g-recaptcha-response'))
                                @foreach($errors->get('g-recaptcha-response') as $error)
                                <div class="mt-2 single_contact_form form-group">
                                    <span id="error-captcha" class="alert alert-danger">
                                        {{($error)}}
                                    </span>
                                </div>
                                @endforeach
                                @endif
                            </div>

                            <div class="col-lg-12">
                                <div class="single_contact_form">
                                    <button type="submit" class="btn primary-btn text-uppercase">{{__('global.send_msg')}}</button>
                                </div>
                            </div>

                        </div>
                    </form>
                    <div id="success">{{__('global.msg_success')}}</div>
                    <div id="error">{{__('global.msg_error')}}</div>
                </div>
            </div>
            <div class="col-md-6">
                <img src="{{asset('images/img/login.png')}}" alt="Illustrated image" class="w-100">
            </div>
        </div>
    </div>
    <div class="contact_shape_1">
        <img src="{{asset('images/img/icon/testimonial_icon_4.png')}}" alt="Illustrated image" data-parallax='{"x": 0, "y": -150, "rotateZ":0}'>
    </div>
</section>
<!-- about part end -->

<!-- instructor list part here -->
<section class="contact_info_section section_padding ft_font">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section_tittle">
                    <h5>{{__('global.contact_us')}}</h5>
                    <h2 style="font-weight: 400">{{__('global.contact_info')}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="single_contact_info">
                    <img src="{{asset('images/img/icon/map.png')}}" alt="map icon">
                    <h4>{{__('global.office_location')}}</h4>
                    <p>{!! $location !!}</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="single_contact_info">
                    <img src="{{asset('images/img/icon/phn.png')}}" alt="phone icon">
                    <h4>{{__('global.contact_number')}}</h4>
                    @foreach($contact_numbers as $contact_number)
                    <p> <a href="tel:{{$contact_number}}"> {{$contact_number}}</a></p>
                    @endforeach

                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="single_contact_info">
                    <img src="{{asset('images/img/icon/mail.png')}}" alt="mail icon">
                    <h4>{{__('global.mail_address')}}</h4>
                    @foreach($contact_emails as $email)
                    <p> <a href="mailto:{{$email}}" target="_blank">{{$email}}</a></p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- instructor list part end -->
@endsection
@section('scripts')

@endsection