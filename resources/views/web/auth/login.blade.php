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
                        <h2 style="font-weight: 400">{{__('global.login')}}</h2>
                        <p>{{__('global.welcome_back')}}</p>
                        <form  class="contact_form" action="" method="post" id="contactForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="single_contact_form form-group">
                                        <label for="username-input">{{__('global.username')}}</label>
                                        <input type="text" name="username" id="username-input" class="form-control cu_input @if($errors->has('username')) is-invalid @endif"
                                               placeholder="{{__('admin.placeholder_text', ['name'=>__('global.username')])}}" value="{{inp_value(null, 'username')}}">
                                        @if($errors->has('username'))
                                            <div class="invalid-feedback">
                                                @foreach($errors->get('username') as $error)
                                                    <span>{{$error}}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="single_contact_form form-group">
                                        <label for="password-input">{{__('global.password')}}</label>
                                        <div class="input-group @if($errors->has('password')) is-invalid @endif">
                                            <input type="password" name="password" id="password-input" class="form-control cu_input @if($errors->has('password')) is-invalid @endif"
                                            placeholder="{{__('admin.placeholder_text',['name'=> __('global.password')])}}" />
                                            <div class="input-group-append">
                                                <button type="button" class="input-group-text show-password"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                        @if($errors->has('password'))
                                            <div class="invalid-feedback">
                                                @foreach($errors->get('password') as $error)
                                                    <span>{{$error}}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if(count($errors) > 0)
                                <div class="col-lg-12">
                                    <div class="single_contact_form form-group">
                                        {!! htmlFormSnippet() !!}
                                    </div>

                                    <div class="mt-2 single_contact_form form-group">
                                        <span class="recaptcha-error d-none alert alert-danger">Recaptcha is required</span>
                                    </div>

                                    @if($errors->has('g-recaptcha-response'))
                                    @foreach($errors->get('g-recaptcha-response') as $error)
                                    <div class="col-lg-12">
                                        <div class="single_contact_form form-group">
                                            <span id="error-captcha" class="alert alert-danger">
                                                {{($error)}}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                @endif
                                <div class="col-lg-12">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="remember" id="defaultCheck1">
                                        <label class="form-check-label text-light" for="defaultCheck1">
                                            {{__('global.remember_me')}} {{__('global.?')}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="single_contact_form mb-4">
                                        <button type="submit" class="btn primary-btn text-uppercase px-5">{{__('global.login')}}</button>
                                    </div>
                                    <div class="d-flex flex-wrap justify-content-between">
                                    <a href="{{route('password.request')}}" class="mx-2">{{__('global.forget_password_username')}} {{__('global.?')}}</a>
                                    <a href="{{route('web.get_register')}}" class="mx-2">{{__('global.dont_have_account')}} {{__('global.?')}}</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="{{asset('images/img/login.png')}}" class="w-100" alt="illustrated image">
                </div>
            </div>
        </div>
        <div class="contact_shape_1">
            <img src="{{asset('images/img/icon/testimonial_icon_4.png')}}" alt="Illustrated image" data-parallax='{"x": 0, "y": -150, "rotateZ":0}'>
        </div>
    </section>
    <!-- about part end -->
    <script>
        // Toggle password
        const togglePasswordBtn = document.querySelector(".show-password");
        const inputPassword = document.querySelector("input[type=password]");
        togglePasswordBtn.addEventListener("click", () => {
            if(inputPassword.type === "password") inputPassword.type = "text";
            else inputPassword.type = "password"
        })
    </script>
    @endsection
