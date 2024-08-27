@extends("web.layouts.master")
@section('styles')
<link rel="stylesheet" href="{{asset('css/web/likely.css')}}" />
<link rel="stylesheet" href="{{asset('css/web/likely-custom.css')}}" />
<link rel="stylesheet" href="{{asset('css/web/profileCard.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/modal.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/home.css?v=1')}}" />
<style>
    .img_banner_section {
        background-image: url("{{asset('images/sound-wave.png')}}"),
        var(--background);
        /* background-blend-mode: overlay; */
    }
</style>
@endsection
@section('content')
<!-- banner part here -->
<section class="img_banner_section">
    <div class="container" style="margin-bottom: 120px">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-10">
                <div class="banner_iner">
                    @if(Session::has('success'))
                    <label class="alert alert-success w-100 alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        {{Session::get('success')}}
                    </label>
                    @endif
                    @if(Session::has('error'))
                    {{-- @foreach ($errors->all() as $error) --}}
                    <label class="alert alert-danger w-100 alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        {{Session::get('error')}}
                    </label>
                    {{-- @endforeach --}}
                    @endif
                    <div class="overlay_effect text-center">
                        <h2 class="overlay_effect_in home-heading"><span>{{__("global.tag_your")}} </span></h2>
                    </div>
                    <div class="overlay_effect text-center">
                        <h2 class="overlay_effect_in home-heading">{!! __("global.right_pronunciation") !!}</h2>
                    </div>
                    <p class="text-center sub-header">
                        {!! __("global.your_info") !!}
                    </p>
                    <div class="header-services">
                        <img src="{{asset('images/home_card.png')}}" >
                        <div class="header-services__serve">
                            <p>Pronounce & listen to names correctly!</p>
                        </div>
                        <div class="header-services__serve">
                            <p>What is the meaning of your name & full BIO?</p>
                        </div>
                        <div class="header-services__serve">
                            <p>Use your Sound&Tag card anywhere!</p>
                        </div>
                        <div class="header-services__serve">
                            <p>Request others to send you their correct pronunciations</p>
                        </div>
                        <div class="header-services__serve">
                            <p>Tag your brand & employees names</p>
                        </div>
                    </div>
                    <div class="banner_btn d-flex justify-content-center flex-wrap align-items-center">
                        @if(auth()->guard('user')->check())
                        <a href="{{route('profile.edit')}}" class="secondary-btn btn">{{__("global.my_records")}}</a>
                        <a href="{{route('web.profile', ['username'=>auth()->guard('user')->user()->username])}}" class="primary-btn btn">{{__("global.view_public_profile")}}</a>
                        @else
                        <a href="{{route('web.tryService')}}" class="secondary-btn btn header__btns">{{__("global.give_it_a_try")}}</a>
                        <a href="{{route('web.get_register')}}" class="primary-btn btn header__btns--small" style="border-radius: 0.25rem;">{{__("global.register")}}</a>
                        @endif
                        <button class="secondary-btn btn header__btns" type="button" data-toggle="modal" data-target="#requestModal">{{__("global.request_pronunciation")}}</button>
                        <!-- Trigger
                            <button class="btn" data-clipboard-text="Test 1">
                                Copy to clipboard
                            </button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scroll to bottom -->
    <div class="mouse_scroll">
        <div>
            <span class="m_scroll_arrows trei"></span>
        </div>
    </div>
</section>
<!-- banner part end -->

<!-- team list part here -->
<section class="team_member_part py-5 ft_font">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section_tittle mb-4">
                    <h5>{{__('global.our_profiles')}}</h5>
                    <h2 style="color: var(--secondary-color); font-weight: 400">{{__('global.examples')}}</h2>
                </div>
            </div>
        </div>
        <input type="radio" id="account-type-personal" hidden name="account_type" checked value="personal">
        <input type="radio" id="account-type-corporate" hidden name="account_type" value="corporate">
        <div class="acoount-type-check d-flex justify-content-center mx-auto mb-4">
            <label for="account-type-personal">{{__('global.personal')}}</label>
            <label for="account-type-corporate">{{__('global.corporate')}}</label>
        </div>
        <div class="row justify-content-center mb-4">
            <div class="col-6">
                <div class="sidebar_part mt-0">
                    <div class="single_sidebar">
                        <form class="search_form" action="{{route('web.get_profiles')}}">
                            {{@csrf_field()}}
                            <input type="text" placeholder="{{__('global.search')}}..." id="search_profiles" value="" name="search">
                            <button type="submit" class="d-inline-block border-0 p-0"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex members">
            <div class="row members_personal w-50">
                @foreach($personalProfiles as $profile)
                <div class="col-lg-2 col-4">
                    @include('web.partials.profile-card')
                </div>
                @endforeach

            </div>


            <div class="row members_corporate w-50">
                @foreach($corporateProfiles as $profile)
                <div class="col-lg-2 col-4">
                    @include('web.partials.profile-card')
                </div>
                @endforeach

            </div>

        </div>



        <div class="banner_btn d-flex justify-content-center">
            <a href="{{route('web.get_profiles')}}" class="primary-btn btn rounded">{{__("global.view_all")}}</a>
        </div>
    </div>
</section>
<!-- team list part end -->
<section class="team_member_part py-5 ft_font" style="background: #dddddd">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section_tittle mb-4">
                    <h5>{{__('global.pricing')}}</h5>
                    <h2 style="color: var(--secondary-color); font-weight: 400">{{__('global.choose_from')}} {{__('global.our_plans', ['period'=> $trialPeriod])}}</h2>
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
            <a href="{{route('account.status')}}" class="primary-btn btn rounded">{{__("global.account_status")}}</a>
            @endif
        </div>
    </div>
</section>
<!-- Request modal -->
<div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModal" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('global.request_pronunciation')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center">
                <p class="text-center mx-auto text-white" style="width: 80%">{{__('global.ask_pronunciation')}}</p>
                @if(auth()->guard('user')->check())
                @if($is_public)
                @php
                $shareUrl = LaravelLocalization::getNonLocalizedURL(route('web.get_invite_link', ['username'=>auth()->guard('user')->user()->username]))
                @endphp
                <div class='likely likely-light text-center section_tittle w-100 d-flex flex-wrap justify-content-center' data-url="{{$shareUrl}}">
                    <div class='facebook' data-title="{{__('global.invitation_copy')}}" data-url="{{$shareUrl}}"></div>
                    <div class='twitter' data-title="{{__('global.invitation_copy')}}" data-url="{{$shareUrl}}"></div>
                    <div class='linkedin' data-title="{{__('global.invitation_copy')}}" data-url="{{$shareUrl}}"></div>
                    <div class='telegram' data-title="{{__('global.invitation_copy')}}" data-url="{{$shareUrl}}"></div>
                    <div class='whatsapp' data-title="{{__('global.invitation_copy')}}" data-url="{{$shareUrl}}"></div>
                    <div class='likely__widget likely-email invitation'>
                        <a style='color:#fff; width: 16px; height: 16px'>
                            <i class="fas fa-envelope likely__icon" style="font-size: 16px; width: 16px; height: 16px"></i>
                        </a>
                    </div>
                    <div class='likely__widget likely-copy'>
                        <button class="likely__button btn-clip" data-clipboard-text="{{__('global.invitation_copy')}}{{$shareUrl}}" data-toggle="tooltip" data-placement="top" type="button" style="width: 16px; height: 16px">
                            <img class='likely__icon' alt='Copy icon' src='<?= asset("images/img/copy.svg") ?>' style="width: 16px; height: 16px">
                        </button>
                    </div>
                    <span class="likely__widget border-0" style="width: 20px">or</span>
                    <div class='likely__widget likely-mob' title="Mobile Only">
                        <a class='likely__button answer-example-share-button' href='javascript:;' data-title="{{__('global.invitation_copy')}}" data-url="{{$shareUrl}}" style='color: #FFF; width: 16px; height: 16px'>
                            <img class='likely__icon' alt='Phone icon' src='<?= asset("images/img/phone.svg") ?>' style="width: 16px; height: 16px">
                        </a>
                    </div>
                </div>
                @else
                <p class="text-white">{{__('global.unable_to_send_invitation')}}</p>
                @endif
                @else
                <p class="text-white">{{__('global.please_login_first')}}</p>
                @endif
                <div class="mt-4">
                    @if(!auth()->guard('user')->check())
                    <a href="{{route('web.get_login')}}" class="btn primary-btn">{{__('global.login')}}</a>
                    @endif
                    <button type="button" class="btn secondary-btn" data-dismiss="modal">{{__('global.close')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="invitationModal" tabindex="-1" aria-labelledby="invitationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invitationModalLabel">{{__('global.invitation_mail')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('web.invite_by_email')}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="text-white">{{__('global.name')}}</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email" class="text-white">{{__('global.email')}}</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <div class="single_contact_form">
                            {!! htmlFormSnippet() !!}
                        </div>

                        <div class="mt-4 single_contact_form form-group">
                            <span class="recaptcha-error d-none alert alert-danger">Recaptcha is required</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer m-auto">
                    <button type="submit" class="btn btn-primary">{{__('global.send')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('global.close')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{asset('js/web/likely.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script>
    $("#requestModal").on("shown.bs.modal", () => {
        // Clipboard
        const clip = new ClipboardJS(".btn-clip", {
            container: document.querySelector("#requestModal")
        });
        clip.on("success", function(e) {
            document.querySelector(".btn-clip").title = "{{__('global.copied')}}"
        })
        clip.on("error", function() {
            console.log("Error")
        })

        // Mobile share
        $(document).on('click', '.answer-example-share-button', function() {
            const url = $(this).attr('data-url');
            if (navigator.share) {
                navigator.share({
                        title: 'Sound and Tag Register',
                        text: $(this).attr("data-title"),
                        url: url,
                    })
                    .then(() => console.log('Successful share'))
                    .catch((error) => console.log('Error sharing', error));
            } else {
                console.log('Share not supported on this browser, do it the old way.');
            }
        });
    })

    // Profiles
    const profilesContainer = document.querySelector(".members");
    const personalProfiles = document.querySelector(".members_personal");
    const corporateProfiles = document.querySelector(".members_corporate");
    const accountTypeInputs = document.querySelectorAll("input[name=account_type]");

    profilesContainer.style.height = `${personalProfiles.offsetHeight}px`;

    accountTypeInputs.forEach(inp =>
        inp.addEventListener("change", (e) => {
            if (e.target.value === "personal")
                profilesContainer.style.height = `${personalProfiles.offsetHeight}px`;
            else
                profilesContainer.style.height = `${corporateProfiles.offsetHeight}px`;
        })
    );

    // Pricing
    const pricingContainer = document.querySelector(".pricing");
    const personalPricing = document.querySelector(".pricing_personal");
    const corporatePricing = document.querySelector(".pricing_corporate");
    const pricingInputs = document.querySelectorAll("input[name=pricing]");


    pricingContainer.style.height = `${personalPricing.offsetHeight}px`;

    pricingInputs.forEach(inp =>
        inp.addEventListener("change", (e) => {
            if (e.target.value === "personal")
                pricingContainer.style.height = `${personalPricing.offsetHeight}px`;
            else
                pricingContainer.style.height = `${corporatePricing.offsetHeight}px`;
        })
    )

    $('.invitation').on('click', function() {
        $('#requestModal').modal('hide');
        $('#invitationModal').modal('show');
    });
</script>
@endsection
