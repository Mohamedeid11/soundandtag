<!DOCTYPE html>
<html lang="{{locale()}}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! SEO::generate() !!}
    <link rel="icon" href="{{asset('images/favicon.ico')}}" type="image/png">
    <!-- Fonts -->
    <style>
        @font-face {
            font-family: Futura;
            src: url("{{asset('fonts/FuturaPTLight.woff')}}") format("WOFF");
            font-weight: 300;
            font-display: swap;
        }

        @font-face {
            font-family: Futura;
            src: url("{{asset('fonts/FuturaPTBook.woff')}}") format("WOFF");
            font-weight: 400;
            font-display: swap;
        }

        @font-face {
            font-family: Futura;
            src: url("{{asset('fonts/FuturaPTMedium.woff')}}") format("WOFF");
            font-weight: 500;
            font-display: swap;
        }

        @font-face {
            font-family: Futura;
            src: url("{{asset('fonts/FuturaPTDemi.woff')}}") format("WOFF");
            font-weight: 600;
            font-display: swap;
        }

        @font-face {
            font-family: Futura;
            src: url("{{asset('fonts/FuturaPTHeavy.woff')}}") format("WOFF");
            font-weight: 700;
            font-display: swap;
        }
    </style>
    <!-- Bootstrap CSS -->
    <link rel="preload" href="{{asset('css/web/'.ldir().'/bootstrap.min.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{asset('css/web/'.ldir().'/bootstrap.min.css')}}">
    </noscript>
    <!-- style CSS -->
    <link rel="stylesheet" href="{{asset('css/web/style.css')}}" />
    <link rel="stylesheet" href="{{asset('css/web/'.ldir().'/style.css')}}" />
    <link rel="preload" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    </noscript>
    <link rel="preload" href="{{asset('plugins/bootstrap-sweetalert/sweet-alert.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{asset('plugins/bootstrap-sweetalert/sweet-alert.css')}}">
    </noscript>
    @yield('styles')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-204698897-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-204698897-1');
    </script>
    {!! htmlScriptTagJsApi([]) !!}
</head>

<body>
    <div class="offcanvas_overlay"></div>
    <header class="header_part classic_header menu_item_padding position_abs">
        <div class="{{locale() === 'ar' ? 'container-fluid' : 'container'}}">
            <div class="row">
                <div class="col-xl-12">
                    <nav class="navbar navbar-expand-xl justify-content-between align-items-center">
                        <a class="navbar-brand main_logo p-3" href="{{route('web.get_landing')}}">
                            <img src="{{asset('images/img/logo-1.png')}}" alt="logo" style="height: 70px" class="d-xl-inline-block navbar-brand-img">
                        </a>
                        <div class="collapse navbar-collapse justify-content-end rounded navbar-drop" id="navbarNavDropdown">
                            <form id="logout-form" method="POST" action="{{route('web.post_logout')}}">{{@csrf_field()}}</form>
                            <ul class="navbar-nav">
                                <li class="nav-item  menu_list">
                                    <a class="nav-link {{Str::contains(request()->route()->getName(), 'web.get_landing') ? 'active' : ''}}" href="{{route('web.get_landing')}}">
                                        {{__('global.home')}}
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link {{Str::contains(request()->route()->getName(), 'web.get_profiles') ? 'active' : ''}}" href="{{route('web.get_profiles')}}">
                                        {{__('global.profiles')}}
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link {{(request()->route()->getName() == 'web.get_about') || (request()->route()->getName() == 'web.get_about_us') ?' active':''}}" href="{{route('web.get_about')}}">
                                        {{__('global.about')}}
                                    </a>
                                </li>

                                <li class="nav-item ">
                                    <a class="nav-link {{Str::contains(request()->route()->getName(), 'web.get_terms') ? 'active' : ''}}" href="{{route('web.get_terms')}}">
                                        {{__('global.terms')}}
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link {{Str::contains(request()->route()->getName(), 'web.get_guides') || Str::contains(request()->route()->getName(), 'faqs') ? 'active' : ''}}" href="{{route('web.get_guides')}}">
                                        {{__('global.guide')}}
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link {{Str::contains(request()->route()->getName(), 'web.get_contact') ? 'active' : ''}}" href="{{route('web.get_contact')}}">
                                        {{__('global.contact_us')}}
                                    </a>
                                </li>
                                @if(Auth::guard('user')->check())
                                @php
                                $user = auth()->guard('user')->user();
                                @endphp
                                @if($user->account_type === 'corporate' && (($user->plan && $user->items > 0) || $user->isInTrial()))
                                <li class="nav-item d-xl-none">
                                    <a class="nav-link {{Str::contains(request()->route()->getName(), 'account.employees') ? 'active' : ''}}" href="{{route('account.employees')}}">
                                        {{__('global.employees')}}
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item d-xl-none">
                                    <a class="nav-link {{Str::contains(request()->route()->getName(), 'profile.edit') ? 'active' : ''}}" href="{{route('profile.edit')}}">
                                        {{__('global.my_records')}}
                                    </a>
                                </li>
                                <li class="nav-item d-xl-none">
                                    <a href="{{route('account.edit')}}" class="nav-link {{Str::contains(request()->route()->getName(), 'global.my_account') ? 'active' : ''}}">{{__('global.my_account')}}</a>
                                </li>
                                <li class="nav-item d-xl-none">
                                    <a href="javascript:document.getElementById('logout-form').submit();" class="color-danger nav-link">
                                        <i class="fa fa-sign-out"></i> {{__('global.logout')}}</a>
                                </li>
                                @endif
                                <li class="nav-item d-xl-none">
                                    <form class="form nav-link">
                                        {{@csrf_field()}}
                                        <div class="form-group m-0">
                                            <select class="form-control" onchange="location.href=$(this).find($('option[value='+$(this).val()+']')).attr('data-value');">
                                                @foreach(locales() as $key => $locale)
                                                @if($key == 'en-US')
                                                <option data-value="{{lroute($key)}}" value="{{$key}}" @if($key==locale()) selected @endif>{{$locale['native']}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </li>
                                @if( ! Auth::guard('user')->check())
                                <li class="nav-item d-xl-none">
                                    <a class="nav-link {{Str::contains(request()->route()->getName(), 'web.get_login') ? 'active' : ''}}" href="{{route('web.get_login')}}">
                                        {{__('global.login')}}
                                    </a>
                                </li>
                                <li class="nav-item d-xl-none">
                                    <a class="nav-link {{Str::contains(request()->route()->getName(), 'web.get_register') ? 'active' : ''}}" href="{{route('web.get_register')}}">
                                        {{__('global.register')}}
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item dropdown d-none d-xl-block">
                                    <a class="nav-link" href="javascript:;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-user" data-toggle="tooltip" data-placement="top" title="Profile"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="navbarDropdown">
                                        <li class="dropdown-item">
                                            <form class="form">
                                                {{@csrf_field()}}
                                                <div class="form-group m-0">
                                                    <select class="form-control" onchange="location.href=$(this).find($('option[value='+$(this).val()+']')).attr('data-value');">
                                                        @foreach(locales() as $key => $locale)
                                                        @if($key == 'en-US')
                                                        <option data-value="{{lroute($key)}}" value="{{$key}}" @if($key==locale()) selected @endif>{{$locale['native']}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </form>
                                        </li>
                                        @if(auth()->guard('user')->check())
                                        @if($user->is_public)
                                        <li class="dropdown-item">
                                            <a href="{{route('web.profile', ['username'=>$user->username])}}">
                                                <i class="fa fa-globe"></i> {{__('global.public_profile')}}
                                            </a>
                                        </li>
                                        @endif
                                        @if($user->account_type === 'corporate' && (($user->plan && $user->items > 0) || $user->isInTrial()))
                                        <li class="dropdown-item">
                                            <a href="{{route('account.employees')}}">
                                                <i class="fa fa-users"></i> {{__('global.employees')}}
                                            </a>
                                        </li>
                                        @endif
                                        <li class="dropdown-item">
                                            <a href="{{route('profile.edit')}}">
                                                <i class="fa fa-microphone"></i> {{__('global.my_records')}}
                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="{{route('account.edit')}}"><i class="fa fa-users-cog"></i> {{__('global.my_account')}}</a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="javascript:document.getElementById('logout-form').submit();" class="color-danger text-center">
                                                <i class="fa fa-sign-out-alt"></i> {{__('global.logout')}}</a>
                                        </li>
                                        @else
                                        <li class="dropdown-item">
                                            <a href="{{route('web.get_login')}}">
                                                <i class="fa fa-sign-in-alt"></i> {{__('global.login')}}
                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="{{route('web.get_register')}}">
                                                <i class="fa fa-sign-in-alt"></i> {{__('global.register')}}
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>


                            </ul>
                        </div>
                        <div class="header_right_option">
                            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- header part end -->
    @yield('content')
    <!-- footer part here -->
    <footer class="footer_section footer_style_2">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-3 col-sm-6 d-flex">
                    <div class="single_footer_widget m-auto">
                        <a href="{{route('web.get_landing')}}"><img src="{{asset('images/img/logo-1.png')}}" alt="Logo" style="width: 75%;"></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single_footer_widget footer_nav text-lg-left">
                        <h4>{{__('global.fast_links')}} <img src="{{asset('images/img/icon/links_icon.png')}}" style="width: 24px; height: 24px" alt="Fast links icon"></h4>
                        <ul class="nav flex-column">
                            <li class="nav-item m-0"><a class="nav-link p-0" href="{{route('web.get_profiles')}}">{{__('global.profiles')}}</a></li>
                            <li class="nav-item m-0"><a class="nav-link p-0" href="{{route('web.get_about')}}">{{__('global.about')}}</a></li>
                            <li class="nav-item m-0"><a class="nav-link p-0" href="{{route('web.get_terms')}}">{{__('global.terms')}}</a></li>
                            <li class="nav-item m-0"><a class="nav-link p-0" href="{{route('web.get_faqs')}}">{{__('global.faq')}}</a></li>
                            <li class="nav-item m-0"><a class="nav-link p-0" href="{{route('web.get_guides')}}">{{__('global.guide')}}</a></li>
                            <li class="nav-item m-0"><a class="nav-link p-0" href="{{route('web.get_contact')}}">{{__('global.contact_us')}}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single_footer_widget footer_nav text-lg-left">
                        <h4>{{__('global.contact_info')}} <img src="{{asset('images/img/icon/sent.svg')}}" alt="contact icon" style="width: 18px; height: 18px"> </h4>
                        <ul>
                            <li>
                                @foreach($contact_emails as $email)
                                <a href="mailto:{{$email}}">{{__('global.email_short')}}: {{$email}}</a>
                                @endforeach
                            </li>
                            <li>
                                @foreach($contact_numbers as $contact_number)
                                <a href="tel:{{$contact_number}}">{{__('global.phone')}}: {{$contact_number}}</a>
                                @endforeach
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single_footer_widget footer_nav text-lg-left">
                        <h4>{{__('global.visit_us')}} <img src="{{asset('images/img/icon/location_icon.svg')}}" style="width: 13px; height: 18px" alt="Visit us icon"></h4>
                        <ul>
                            <li>{!! $location !!}</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-12 mt-2">
                    <div class="text-center mt-5 d-flex justify-content-center">
                        @foreach($social_links as $social_link)
                        <a href="{{$social_link->link}}" target="_blank" class="px-1" style="color: var(--secondary-color)">
                            <i class="{{$social_link->icon}}" style="font-size: 24px"></i>
                            {{$social_link->trans('name')}}
                        </a>
                        @endforeach
                    </div>
                    <div class="copyright_part">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-8">
                                <p>© {{date('Y')}} <a href="#" class="text_underline">{{__('global.short_title')}}</a> - {{__('global.rights_reserved')}} - {{__('global.on_air_commerce_product')}} </p>
                            </div>
                            <div class="col-lg-6 col-md-4">
                                <div class="text-right">
                                    {{-- <span class="mr-2 text-light" title="Secure Credit Cards Payment"><i class="fa fa-2x fa-credit-card"></i></span> --}}
                                    {{-- <span class="text-light" title="Secure Amazon Pay Payments"><i class="fab fa-2x fa-amazon-pay"></i></span> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    @if(isset($validity_lack_reasons))
    <div class="position-fixed toast-container toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
        <div id="liveToast">
            <div class="toast-header">
                <i class="text-warning fa fa-exclamation-triangle mr-2 ml-auto" alt="..."></i>
                <p class="mr-auto text-warning">{{__('global.no_public_profile')}}</p>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body rounded d-flex flex-column">
                <h4 class="text-center text-warning">{{$validity_lack_reasons->get('title')}}</h4>
                <p class="my-3">{{$validity_lack_reasons->get('text')}}</p>
                <a class="btn primary-btn small-btn mt-2 align-self-center toaster-btn" href="{{$validity_lack_reasons->get('url')}}">{{$validity_lack_reasons->get('url_text')}}</a>
            </div>
        </div>
    </div>
    @endif
    <!-- jquery slim -->
    <script src="{{asset('js/web/jquery-3.5.1.min.js')}}"></script>
    <!-- popper js -->
    <script src="{{asset('js/web/popper.min.js')}}"></script>
    <!-- bootstarp js -->
    <script src="{{asset('js/web/'.ldir().'/bootstrap.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-sweetalert/sweet-alert.min.js')}}"></script>
    <!-- custom js -->
    <script src="{{asset('js/web/custom.js')}}"></script>
    @if(! empty($validity_lack_reasons))
    <script>
        $(document).ready(function() {
            $('.toast').toast({
                autohide: false
            }).toast('show');
            // $('#liveToastBtn').click(() => $('.toast').toast({autohide: false}).toast('show'))
            if (window.innerWidth < 768) {
                const toastContainer = document.querySelector(".toast-container");

                toastContainer.style.left = `calc(50% - ${toastContainer.offsetWidth / 2}px)`;
                toastContainer.style.right = "unset"
            }
        });
    </script>
    @endif
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
        window.addEventListener('load', (event) => {
            const recaptchas = document.querySelectorAll('[id^=g-recaptcha-response]');

            console.log(recaptchas)
            if (recaptchas.length) {
                recaptchas.forEach((recaptcha, i) => {
                    recaptcha.setAttribute("required", "required");
                    recaptcha.oninvalid = function(e) {
                        document.querySelectorAll(".recaptcha-error")[i].classList.remove("d-none")
                    }
                })
            }
        });
    </script>
    @include('web.partials.scripts')
    @yield('scripts')

</body>

</html>
