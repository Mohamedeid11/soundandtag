<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin Dashboard" name="description" />
    <meta content="Ibrahim E.Gad" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
    <link rel="icon" href="{{asset('images/favicon.ico')}}" type="image/png">
    <link rel="stylesheet" href="{{asset('plugins/morris/morris.css')}}">
    <link href="{{asset('css/admin/'.ldir().'/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/icons.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/style.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/'.ldir().'/style.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('plugins/bootstrap-sweetalert/sweet-alert.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/bootstrap4-toggle.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/custom.css')}}" rel="stylesheet" type="text/css">
    @yield('head')
</head>

<body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">
        <!-- Top Bar Start -->
        <div class="topbar">
            <!-- LOGO -->
            <div class="topbar-left">
                <div class="text-center p-2 ">
                    <a href="{{route('admin.get_dashboard')}}" class="logo"><img src="{{storage_asset(App\Models\Setting::where(['name'=>'logo'])->first()->value)}}" alt="logo-img"></a>
                    <a href="{{route('admin.get_dashboard')}}" class="logo-sm"><img src="{{asset('images/al-logo2-small.png')}}" alt="logo-img"></a>
                </div>
            </div>
            <!-- Button mobile view to collapse sidebar menu -->
            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left waves-light waves-effect">
                                <i class="mdi mdi-menu"></i>
                            </button>
                        </li>
                    </ul>

                    <ul class="nav navbar-right float-right list-inline">
                        <li class="dropdown d-none d-sm-block">
                            <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light notification-icon-box" data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-bell"></i> <span class="badge badge-xs badge-danger"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg">
                                <li class="text-center notifi-title">Notification <span class="badge badge-xs badge-success">0</span></li>
                                <li class="list-group">
                                    <!-- list item-->
                                    {{-- <a href="javascript:void(0);" class="list-group-item">--}}
                                    {{-- <div class="media">--}}
                                    {{-- <div class="media-heading">Your order is placed</div>--}}
                                    {{-- <p class="m-0">--}}
                                    {{-- <small>Dummy text of the printing and typesetting industry.</small>--}}
                                    {{-- </p>--}}
                                    {{-- </div>--}}
                                    {{-- </a>--}}
                                    <a href="javascript:void(0);" class="list-group-item">
                                        <small class="text-primary">{{__('global.see_all_notifications')}}</small>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="d-none d-sm-block">
                            <a href="#" id="btn-fullscreen" class="waves-effect waves-light notification-icon-box"><i class="mdi mdi-fullscreen"></i></a>
                        </li>

                        <li class="nav-item dropdown">
                            <a href="javascript:;" class="nav-link dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                                <img src="{{storage_asset(auth()->guard('admin')->user()->image)}}" alt="user-img" class="rounded-circle">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-item px-1">
                                    <div class="py-2" onclick="event.preventDefault();">
                                        <select class="form-control" onchange="location.href=$(this).find($('option[value='+$(this).val()+']')).attr('data-value');">
                                            @foreach(locales() as $key => $locale)
                                            <option data-value="{{lroute($key)}}" value="{{$key}}" @if($key==locale()) selected @endif>{{$locale['native']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                                <li><a href="{{route('admin.get_profile')}}" class="dropdown-item"> {{__('global.edit_profile')}}</a></li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <form id="logout-form" method="POST" action="{{route('admin.post_logout')}}">{{@csrf_field()}}</form>
                                    <a href="javascript:document.getElementById('logout-form').submit();" class="dropdown-item"> {{__('global.logout')}}</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- Top Bar End -->
        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu">
            <div class="sidebar-inner slimscrollleft">

                <div class="user-details">
                    <div class="text-center">
                        <img src="{{storage_asset(auth()->guard('admin')->user()->image)}}" alt="" class="thumb-md img-circle rounded-circle">
                    </div>
                    <div class="user-info m-0 text-center">
                        <p class="m-0">{{ auth()->guard('admin')->user()->trans('name')  }}</p>
                    </div>
                </div>
                <div id="sidebar-menu">
                    <ul>
                        <li>
                            <a href="{{route('admin.get_dashboard')}}" class="waves-effect"><i class="mdi mdi-home"></i>
                                <span>{{__('admin.dashboard')}}</span></a>
                        </li>
                        @php
                        $main_routes = ['admin.countries.index', 'admin.countries.*', 'admin.roles.index', 'admin.roles.*',
                        'admin.admins.index', 'admin.admins.*', 'admin.plans.index', 'admin.plans.*'];
                        @endphp
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect @if(menu_active($main_routes)) active @endif"><i class="mdi mdi-album"></i> <span>{{__('admin.main')}}</span> <span class="float-right"><i class="mdi mdi-plus"></i></span></a>
                            <ul class="list-unstyled">
                                @can('viewAny', \App\Models\Role::class)
                                <li class="@if(menu_active(['admin.roles.index', 'admin.roles.*'])) active @endif">
                                    <a href="{{route('admin.roles.index')}}">
                                        <i class="mdi mdi-account-group"></i>
                                        {{__('global.roles')}}
                                    </a>
                                </li>
                                @endcan
                                @can('viewAny', \App\Models\Admin::class)
                                <li class="@if(menu_active(['admin.admins.index', 'admin.admins.*'])) active @endif">
                                    <a href="{{route('admin.admins.index')}}">
                                        <i class="mdi mdi-account-network"></i>
                                        {{__('global.admins')}}
                                    </a>
                                </li>
                                @endcan

                                @can('viewAny', \App\Models\Plan::class)
                                <li class="@if(menu_active(['admin.plans.index', 'admin.plans.*'])) active @endif">
                                    <a href="{{route('admin.plans.index')}}">
                                        <i class="mdi mdi-pencil"></i>
                                        {{__('global.plans')}}
                                    </a>
                                </li>
                                @endcan
                                @can('viewAny', \App\Models\Country::class)
                                <li class="@if(menu_active(['admin.countries.index', 'admin.countries.*'])) active @endif">
                                    <a href="{{route('admin.countries.index')}}">
                                        <i class="mdi mdi-globe-model"></i>
                                        {{__('global.countries')}}
                                    </a>
                                </li>
                                @endcan

                            </ul>
                        </li>
                        @php
                        $end_user_routes = ['admin.users.index', 'admin.users.*', 'admin.record_types.index', 'admin.record_types.*',
                        'admin.records.index', 'admin.records.*'];
                        @endphp
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect @if(menu_active($end_user_routes)) active @endif"><i class="mdi mdi-album"></i> <span>{{__('admin.end_user_menu')}}</span> <span class="float-right"><i class="mdi mdi-plus"></i></span></a>
                            <ul class="list-unstyled">
                                @can('viewAny', \App\Models\User::class)
                                <li class="@if(menu_active(['admin.users.index', 'admin.users.*'])) active @endif">
                                    <a href="{{route('admin.users.index')}}">
                                        <i class="mdi mdi-account-card-details"></i>
                                        {{__('global.users')}}
                                    </a>
                                </li>
                                @endcan

                                <li class="@if(menu_active(['admin.trial_users', 'admin.trial_users'])) active @endif">
                                    <a href="{{route('admin.trial_users')}}">
                                        <i class="mdi mdi-account-card-details"></i>
                                        {{__('global.trial_users')}}
                                    </a>
                                </li>

                                <li class="@if(menu_active(['admin.login_attempts', 'admin.login_attempts'])) active @endif">
                                    <a href="{{route('admin.login_attempts')}}">
                                        <i class="mdi mdi-account-card-details"></i>
                                        {{__('global.login_attempts')}}
                                    </a>
                                </li>

                                @can('viewAny', \App\Models\RecordType::class)
                                <li class="@if(menu_active(['admin.record_types.index', 'admin.record_types.*'])) active @endif">
                                    <a href="{{route('admin.record_types.index')}}">
                                        <i class="mdi mdi-group"></i>
                                        {{__('global.record_types')}}
                                    </a>
                                </li>
                                @endcan
                                @can('viewAny', \App\Models\Record::class)
                                <li class="@if(menu_active(['admin.records.index', 'admin.records.*'])) active @endif">
                                    <a href="{{route('admin.records.index')}}">
                                        <i class="mdi mdi-microphone"></i>
                                        {{__('global.records')}}
                                    </a>
                                </li>
                                @endcan

                            </ul>
                        </li>
                        @php
                        $management_routes = ['admin.settings.index', 'admin.settings.*', 'admin.contact_messages.index',
                        'admin.contact_messages.*', 'admin.pages.index', 'admin.pages.*',
                        'admin.social_links.index', 'admin.social_links.*',
                        'admin.faqs.index', 'admin.faqs.*',
                        'admin.guides.index', 'admin.guides.*',
                        'admin.faq.index', 'admin.faq.*',
                        'admin.guide.index', 'admin.guide.*',
                        'admin.subscriptions.index', 'admin.subscriptions.*', 'admin.newsletter_emails.index', 'admin.newsletter_emails.*'];
                        @endphp
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect @if(menu_active($management_routes) || menu_active(['admin.newsletter_emails.index'], true)) active @endif">
                                <i class="mdi mdi-album"></i> <span>{{__('admin.management_menu')}}</span> <span class="float-right"><i class="mdi mdi-plus"></i></span></a>
                            <ul class="list-unstyled">
                                @can('viewAny', \App\Models\Setting::class)
                                <li class="@if(menu_active(['admin.settings.index', 'admin.settings.*'])) active @endif">
                                    <a href="{{route('admin.settings.index')}}">
                                        <i class="mdi mdi-cogs"></i>
                                        {{__('global.settings')}}
                                    </a>
                                </li>
                                @endcan
                                @can('viewAny', \App\Models\ContactMessage::class)
                                <li class="@if(menu_active(['admin.contact_messages.index', 'admin.contact_messages.*'])) active @endif">
                                    <a href="{{route('admin.contact_messages.index')}}">
                                        <i class="mdi mdi-mailbox"></i>
                                        {{__('global.contact_messages')}}
                                    </a>
                                </li>
                                @endcan
                                @can('viewAny', \App\Models\Page::class)
                                <li class="@if(menu_active(['admin.pages.index', 'admin.pages.*'])) active @endif">
                                    <a href="{{route('admin.pages.index')}}">
                                        <i class="mdi mdi-google-pages"></i>
                                        {{__('global.pages')}}
                                    </a>
                                </li>
                                @endcan
                                @can('viewAny', \App\Models\SocialLink::class)
                                <li class="@if(menu_active(['admin.social_links.index', 'admin.social_links.*'])) active @endif">
                                    <a href="{{route('admin.social_links.index')}}">
                                        <i class="mdi mdi-globe-model"></i>
                                        {{__('global.social_links')}}
                                    </a>
                                </li>
                                @endcan

                                @can('viewAny', \App\Models\Faq::class)
                                <li class="@if(menu_active(['admin.faqs.index', 'admin.faqs.*'])) active @endif">
                                    <a href="{{route('admin.faqs.index')}}">
                                        <i class="mdi mdi-comment-question-outline"></i>
                                        {{__('global.faq')}}
                                    </a>
                                </li>
                                @endcan

                                @can('viewAny', \App\Models\Guide::class)
                                <li class="@if(menu_active(['admin.guides.index', 'admin.guides.*'])) active @endif">
                                    <a href="{{route('admin.guides.index')}}">
                                        <i class="mdi mdi-comment-question-outline"></i>
                                        {{__('global.guide')}}
                                    </a>
                                </li>
                                @endcan

                                @can('viewAny', \App\Models\Subscription::class)
                                <li class="@if(menu_active(['admin.subscriptions.index', 'admin.subscriptions.*', 'admin.newsletter_emails.index', 'admin.newsletter_emails.*']) || menu_active(['admin.newsletter_emails.index'], true)) active @endif">
                                    <a href="{{route('admin.subscriptions.index')}}">
                                        <i class="mdi mdi-email"></i>
                                        {{__('global.subscriptions')}}
                                    </a>
                                </li>
                                @endcan

                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div> <!-- end sidebarinner -->
        </div>
        <!-- Left Sidebar End -->

        <!-- Start right Content here -->
        <div class="content-page">
            <div class="content">
                <div class="">
                    <div class="page-header-title">
                        <h4 class="page-title">@yield('header_title')</h4>
                    </div>
                </div>
                <div class="page-content-wrapper ">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
                <footer class="footer">
                    © {{date('Y')}} {{__('global.short_title')}} - {{__('admin.rights_reserved')}}.
                </footer>
            </div>
        </div>
    </div>

    <script src="{{asset('js/admin/jquery.min.js')}}"></script>
    <script src="{{asset('js/admin/popper.min.js')}}"></script>
    <script src="{{asset('js/admin/bootstrap'.(LaravelLocalization::getCurrentLocaleDirection() == 'rtl'?'.rtl':'').'.min.js')}}"></script>
    <script src="{{asset('js/admin/modernizr.min.js')}}"></script>
    <script src="{{asset('js/admin/detect.js')}}"></script>
    <script src="{{asset('js/admin/fastclick.js')}}"></script>
    <script src="{{asset('js/admin/jquery.slimscroll.js')}}"></script>
    <script src="{{asset('js/admin/jquery.blockUI.js')}}"></script>
    <script src="{{asset('js/admin/waves.js')}}"></script>
    <script src="{{asset('js/admin/wow.min.js')}}"></script>
    <script src="{{asset('js/admin/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/admin/jquery.scrollTo.min.js')}}"></script>
    <script src="{{asset('plugins/morris/morris.min.js')}}"></script>
    <script src="{{asset('plugins/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-sweetalert/sweet-alert.min.js')}}"></script>
    <script src="{{asset('js/admin/bootstrap4-toggle.min.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>
    <script src="{{asset('js/admin/app.js')}}"></script>
    @include('admin.inc.axiosinit')
    @yield('scripts')
</body>