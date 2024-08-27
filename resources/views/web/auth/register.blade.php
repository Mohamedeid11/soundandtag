@extends('web.layouts.master')
@section('styles')
<link rel="stylesheet" href="{{asset('css/web/login.css?v=1')}}" />
@endsection
@section('content')
<!-- about part here -->
<section class="contact_section ft_font" id="vue-app">
    <div class="container">
        @if(session()->has('success'))
        <label class="w-100 alert alert-success alert-dismissible fade show" role="alert">
            {{session()->get('success')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </label>
        @elseif(session()->has('error'))
        <label class="w-100 alert alert-danger alert-dismissible fade show" role="alert">
            {{session()->get('error')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </label>
        @endif
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="contact_section_content pr_60 pt-5 section_padding">
                    <h2 style="font-weight: 400">{{__('global.register')}}</h2>
                    <p>{{__('global.join_us')}}</p>
                    <form class="contact_form" action="{{route('web.post_register', ['company' => request()->input('company'), 'hash' => request()->input('hash'), 'email' => request()->input('email')])}}" method="post" id="contactForm" autocomplete="off">
                        @csrf
                        <input type="hidden" name="records" id="records">
                        @if(isset($user) && $user)
                        <input type="hidden" name="invited_by" value="{{$user->id}}">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                @if($company && $hash && $email)
                                <h4 class="text-info">{{__('global.register_as_employee')}}: <u class="text-light">{{$company->name}}</u></h4>
                                <input type="hidden" name="company" value="{{$company->id}}">
                                <input type="hidden" name="hash" value="{{$hash}}">
                                <input type="hidden" name="email" value="{{$email}}">
                                <input type="hidden" name="account_type" value="employee">
                                @else
                                <div class="single_contact_form form-group">
                                    <label for="account-type-select">{{__('global.account_type')}}<sup class="required">*</sup></label>
                                    <div class="acoount-type-check @if($errors->has('account_type')) is-invalid @endif">
                                        <input type="radio" id="account-type-personal" hidden name="account_type" value="personal" v-model="account_type">
                                        <label for="account-type-personal">{{__('global.personal')}}</label>
                                        <input type="radio" id="account-type-corporate" hidden name="account_type" value="corporate" v-model="account_type">
                                        <label for="account-type-corporate">{{__('global.corporate')}}</label>
                                    </div>
                                    @if($errors->has('account_type'))
                                    <div class="invalid-feedback">
                                        @foreach($errors->get('account_type') as $error)
                                        <span>{{$error}}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @if(! $company && ! $hash && ! $email)
                            <div class="col-lg-12">
                                <div class="single_contact_form form-group">
                                    <label for="plan-input">{{__('global.plan')}} <sup class="required">*</sup></label>
                                    <select name="plan" id="plan-input" class="form-control cu_input @if($errors->has('plan')) is-invalid @endif" required v-model="selected_plan" value="{{inp_value(null, 'plan')}}">
                                        <option value="" disabled selected>{{__('global.choose_plan')}}</option>
                                        <option v-for="plan in plans" :value="plan.id" v-if="plan.account_type === 'corporate'">[[plan.period]] - [[plan.items]] {{__('global.employees')}} - [[plan.price]] {{__('global.USD')}}</option>
                                        <option v-for="plan in plans" :value="plan.id" v-if="plan.account_type === 'personal'">[[plan.period]] - [[plan.price]] {{__('global.USD')}}</option>
                                    </select>
                                    @if($errors->has('plan'))
                                    <div class="invalid-feedback">
                                        @foreach($errors->get('plan') as $error)
                                        <span>{{$error}}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            <div class="col-lg-12">
                                <div class="single_contact_form form-group">
                                    <label for="username-input">{{__('global.username')}} <sup class="required">*</sup></label>
                                    <input type="text" name="username" id="username-input" class="form-control cu_input @if($errors->has('username')) is-invalid @endif" autocomplete="new-password" placeholder="{{__('admin.placeholder_text', ['name'=>__('global.username')])}}" value="{{inp_value(null, 'username')}}" required>
                                    @if($errors->has('username'))
                                    <div class="invalid-feedback">
                                        @foreach($errors->get('username') as $error)
                                        <span>{{$error}}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @if(! $email)
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
                                    <label for="password-input">{{__('global.password')}} <sup class="required">*</sup></label>
                                    <div class="input-group @if($errors->has('password')) is-invalid @endif">
                                        <input type="password" name="password" id="password-input" class="form-control cu_input @if($errors->has('password')) is-invalid @endif" placeholder="{{__('admin.placeholder_text',['name'=> __('global.password')])}}" required autocomplete="new-password">
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
                                    <label for="confirm-password-input">{{__('global.password_confirmation')}} <sup class="required">*</sup></label>
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
                                @if($errors->has('password_confirmation'))
                                <div class="invalid-feedback">
                                    @foreach($errors->get('password_confirmation') as $error)
                                    <span>{{$error}}</span>
                                    @endforeach
                                </div>
                                @endif
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
                                <div class="col-lg-12">
                                    <div class="single_contact_form form-group">
                                        <span id="error-captcha" class="alert alert-danger">
                                            {{($error)}}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                                @endif

                                <div class="single_contact_form form-group">
                                    <input type="checkbox" name="terms-check" id="terms-check" required>
                                    <label for="terms-check"> {{__('global.accept')}} <a href="{{url('/terms')}}" target="_blank">{{__('global.terms_conditions')}}</a><sup class="required">*</sup></label>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                {{-- <p class="mb-3" style="line-height: 1"><small class="text-light">Start your registration with
                                        {{$trialPeriod}} days trial, then subscribe to one of our
                                plans , see more details on <a target="_blank" href="{{route('web.get_pricing')}}">Pricing Page</a>, Corporate
                                accounts are allowed 15 employees during trial period </small></p> --}}
                                <div class="single_contact_form d-flex">
                                    <button type="submit" class="btn primary-btn text-uppercase"> {{__('global.register')}}</button>
                                    <a href="{{route('web.get_login')}}" class="ml-auto">{{__('global.already_have_account')}} {{__('global.?')}}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <img src="{{asset('images/img/7834.jpg')}}" style="width: 100%;" alt="illustrated image">
            </div>
        </div>
    </div>
    <div class="contact_shape_1">
        <img src="{{asset('images/img/icon/testimonial_icon_4.png')}}" alt="Illustrated image" data-parallax='{"x": 0, "y": -150, "rotateZ":0}'>
    </div>
</section>
<!-- about part end -->
@endsection
@section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.js"
    integrity="sha512-PyKhbAWS+VlTWXjk/36s5hJmUJBxcGY/1hlxg6woHD/EONP2fawZRKmvHdTGOWPKTqk3CPSUPh7+2boIBklbvw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@include('web.partials.register-vue')
@endsection