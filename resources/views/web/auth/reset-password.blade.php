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
                    <h2 style="font-weight: 400">{{__('global.reset_password_or_username')}}</h2>
                    <p>{{__('global.choose_new_password')}}</p>
                    <form class="contact_form" action="{{route('password.update')}}" method="post" id="contactForm">
                        @csrf
                        <input type="hidden" name="token" value="{{$token}}">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="single_contact_form form-group">
                                    <label for="email-input">{{__('global.email')}}</label>
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
                            <div class="col-lg-12">
                                <div class="single_contact_form form-group">
                                    <label for="password-input">{{__('global.password')}}</label>
                                    <div class="input-group @if($errors->has('password')) is-invalid @endif">
                                        <input type="password" name="password" id="password-input" class="form-control cu_input @if($errors->has('password')) is-invalid @endif" placeholder="{{__('admin.placeholder_text',['name'=> __('global.password')])}}" required>
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
                            <div class="col-lg-12">
                                <div class="single_contact_form form-group">
                                    <label for="confirm-password-input">{{__('global.password_confirmation')}}</label>
                                    <div class="input-group @if($errors->has('password_confirmation')) is-invalid @endif">
                                        <input type="password" name="password_confirmation" id="confirm-password-input" class="form-control cu_input @if($errors->has('password_confirmation')) is-invalid @endif" placeholder="{{__('admin.placeholder_text',['name'=> __('global.password_confirmation')])}}" required>
                                        <div class="input-group-append">
                                            <button type="button" class="input-group-text show-password"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                                @if($errors->has('password_confirmation'))
                                <div class="invalid-feedback">
                                    @foreach($errors->get('password_confirmation') as $error)
                                    <span>{{$error}}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="col-lg-12">
                                <div class="single_contact_form mt-4">
                                    <button type="submit" class="btn primary-btn text-uppercase px-5">{{__('global.save')}}</button>
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
<script>
    // Toggle password input
    const showPasswordBtns = document.querySelectorAll(".show-password");
    showPasswordBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            const inputElm = btn.parentElement.previousElementSibling;
            if (inputElm.type === "password") inputElm.type = "text";
            else inputElm.type = "password"
        })
    })
</script>
@endsection