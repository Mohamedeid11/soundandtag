@extends('web.layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/web/likely.css')}}" />
    <link rel="stylesheet" href="{{asset('css/web/likely-custom.css')}}" />
    <link rel="stylesheet" href="{{asset('css/web/profile.css?v=1')}}" />
    <style>
        .no-link {
            cursor: default;
        }

        .copy-card-data-center {
            background: rgba(0, 0, 0, 0.6);
            padding: 16px;
        }
        .CodeMirror {
            display: none;
        }
    </style>
@endsection
@section('content')
    @php
        $shareUrl = LaravelLocalization::getNonLocalizedURL(route('web.profile', ['username'=>$profile->get('user')->username]))
    @endphp
    <section class="about_me_section sec_padding">
        <div class="container overflow-hidden mb-3">
            <div class="text-center profile-title mt-5" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInDown;">
                <h5 class="base_color">{{__('global.sound_profile')}}</h5>
                <h2 style="font-weight: 400;">{{$profile->get('user')->full_name}}</h2>
                @if($profile->get('user')->company)
                    <p class="border-bottom d-inline-block pb-3">
                        <a href="{{route('web.profile', ['username'=>$profile->get('user')->company->username])}}">
                            <img src="{{storage_asset($profile->get('user')->company->image)}}" class="rounded-circle profile-company-img"> <span class="text-light">{{__('global.works_at')}}</span> {{$profile->get('user')->company->name}}</a>
                    </p>
                @endif
                @if($profile->get('user')->account_type == 'corporate')
                    <a class="btn secondary-btn profile-toggle-btn mt-3" href="{{route('web.get_corporate_employees', ['company' => $profile->get('user')])}}">{{__('global.our_team')}}</a>
                @endif
            </div>
            <div class="row mb-5 mr-md-5 ml-md-5 mr-2 ml-2">
                <div class="col-sm-4 ">
                    <div class="col-lg-12 text-center">
                        <img src="{{storage_asset($profile->get('user')->image)}}" class="rounded-circle profile-img" alt="user profike image">
                    </div>
                    <h3 class="text-center text-light mt-3">{{$profile->get('user')->full_name}}</h3>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center p-2 d-none audio-container" id="vz-wrapper">
                                <audio id="myAudio" src="" data-author="insert/author/name" data-title="insert/audio/name" preload="none"></audio>
                            </div>
                        </div>
                    </div>
                    <input type="radio" name="profile-toggle" id="pronunciation" hidden checked/>
                    <input type="radio" name="profile-toggle" id="public-data" hidden/>
                    <div class="row mb-4 justify-content-center">
                        <label class="col-6 col-sm-5 profile-toggle-btn d-flex justify-content-center align-items-center" for="pronunciation">{{__('global.pronunciation')}}</label>
                        <label class="col-6 col-sm-5 profile-toggle-btn d-flex justify-content-center align-items-center" for="public-data">{{__('global.public_data')}}</label>
                    </div>
                    <div class="pronunciation-section">
                        @foreach($profile->get('records')['available_record_types'] as $record_type)
                            @if (Arr::has($record_type->record, 'file_path'))
                                <div class="d-flex flex-row py-2 pr-4 pr-sm-0 pl-4 mb-2 record-card record-profile-card justify-content-between align-items-center">
                                    <div class="d-flex flex-wrap w-100 align-items-center">
                                        <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">{{$record_type->trans('name')}} </h5>
                                        <p class="record-card-text col-12 col-sm-6 col-md-8 col-xl-10 text-center">{{$record_type->record ? $record_type->record['text_representation'] : ''}}</p>
                                    </div>
                                    <div class="position-absolute absolute-btn">
                                        <div class="listen-btn-container mr-n1 d-none d-sm-block">
                                            <a class="btn btn-success btn-outline listen-btn small-btn rounded-circle" href="javascript:;" onclick="listen(this)"
                                            data-key="{{$record_type->trans('name')}}"
                                            data-title="{{$record_type->record ? $record_type->record['text_representation'] : ''}}" data-author="{{$profile->get('user')->full_name}}"
                                            data-value="{{$record_type->record ? $record_type->record['full_url'] ?? '' : ''}}">
                                                <i class="fas fa-play"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="listen-btn-container d-block d-sm-none mx-auto">
                                    <a class="btn btn-success btn-outline listen-btn small-btn rounded-circle"
                                        href="javascript:;"
                                        onclick="listen(this)"
                                        data-key="{{$record_type->trans('name')}}"
                                        data-title="{{$record_type->record ? $record_type->record['text_representation'] : ''}}" data-author="{{$profile->get('user')->full_name}}"
                                        data-value="{{$record_type->record ? $record_type->record['full_url'] ?? '' : ''}}">
                                        <i class="fas fa-play"></i>
                                    </a>
                                </div>
                                @if($record_type->record && $record_type->record['meaning'])
                                    <div class="record-card d-flex flex-wrap w-100 align-items-center py-2 px-4 record-card--meaning my-3">
                                        <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">{{__('global.meaning')}} </h5>
                                        <p class="record-card-text col-12 col-sm-6 col-md-8 col-xl-10 text-center">
                                            {{$record_type->record['meaning']}}</p>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                    <div class="public-data-section">
                        <div class="d-flex py-2 px-4 my-3 record-card justify-content-between">
                            <div class="d-flex flex-wrap w-100 align-items-center">
                                <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">{{__("global.email")}} </h5>
                                <p class="record-card-text col-12 col-sm-6 col-md-8 col-xl-10 text-center">{{$profile->get('user')->email}}</p>
                            </div>
                        </div>
                        @if($profile->get('user')->phone)
                            <div class="d-flex py-2 px-4 my-3 record-card justify-content-between">
                                <div class="d-flex flex-wrap w-100 align-items-center">
                                    <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">{{__("global.phone")}} </h5>
                                    <p class="record-card-text col-12 col-sm-6 col-md-8 col-xl-10 text-center">{{$profile->get('user')->phone}}</p>
                                </div>
                            </div>
                        @endif
                        @if($profile->get('user')->country_id)
                            <div class="d-flex py-2 px-4 my-3 record-card justify-content-between">
                                <div class="d-flex flex-wrap w-100 align-items-center">
                                    <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">{{__("global.country")}} </h5>
                                    <p class="record-card-text col-12 col-sm-6 col-md-8 col-xl-10 text-center">{{$profile->get('user')->country()->first()->trans('name')}}</p>
                                </div>
                            </div>
                        @endif
                        @if($profile->get('user')->website)
                            <div class="d-flex py-2 px-4 my-3 record-card justify-content-between">
                                <div class="d-flex flex-wrap w-100 align-items-center">
                                    <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">{{__("global.website")}} </h5>
                                    <p class="record-card-text col-12 col-sm-6 col-md-8 col-xl-10 text-center"><a target="_blank" href="{{$profile->get('user')->website}}" class="text-white">{{$profile->get('user')->website}}</a></p>
                                </div>
                            </div>
                        @endif
                        @if($profile->get('user')->position)
                            <div class="d-flex py-2 px-4 my-3 record-card justify-content-between">
                                <div class="d-flex flex-wrap w-100 align-items-center">
                                    <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">{{__("global.position")}} </h5>
                                    <p class="record-card-text col-12 col-sm-6 col-md-8 col-xl-10 text-center">{{$profile->get('user')->position}}</p>
                                </div>
                            </div>
                        @endif
                        @if($profile->get('user')->address)
                            <div class="d-flex py-2 px-4 my-3 record-card justify-content-between">
                                <div class="d-flex flex-wrap w-100 align-items-center">
                                    <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">{{__("global.address")}} </h5>
                                    <p class="record-card-text col-12 col-sm-6 col-md-8 col-xl-10 text-center">{{$profile->get('user')->address}}</p>
                                </div>
                            </div>
                        @endif
                        @if($profile->get('user')->interests)
                            <div class="d-flex py-2 px-4 my-3 record-card justify-content-between">
                                <div class="d-flex flex-wrap w-100 align-items-center">
                                    <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">{{__("global.interests")}} </h5>
                                    <p class="record-card-text col-12 col-sm-6 col-md-8 col-xl-10 text-center">{{$profile->get('user')->interests}}</p>
                                </div>
                            </div>
                        @endif
                        @if($profile->get('user')->biography)
                            <div class="d-flex py-2 px-4 my-3 record-card justify-content-between biography">
                                <div class="d-flex flex-wrap w-100 align-items-center">
                                    <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">{{$profile->get('user')->account_type == 'corporate'? __("global.company_profile") : __("global.biography")}} </h5>
                                    <p class="record-card-text col-12 col-sm-6 col-md-8 col-xl-10 text-center">
                                        <input type="checkbox" name="biography-more" id="biography-more" hidden />
                                        <span>{{$profile->get('user')->biography}}</span>
                                        <label for="biography-more" class="mb-0">{{__('global.more_about_me')}}</label>
                                    </p>
                                </div>
                            </div>
                        @endif
                        @if($profile->get('user')->video)
                            <div class="d-flex py-2 px-4 my-3 record-card justify-content-between biography">
                                <div class="d-flex flex-wrap w-100 align-items-center">
                                    <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">
                                        @if($profile->get('user')->account_type == 'corporate')
                                            {{ __('global.promotional_video') }}
                                        @elseif($profile->get('user')->account_type == 'personal')
                                            {{ __('global.identity_video') }}
                                        @elseif($profile->get('user')->account_type == 'employee')
                                            {{ __('global.professional_video') }}
                                        @endif
                                    </h5>
                                    <video class="col-12 col-sm-6 col-md-8 col-xl-10" controls>
                                        <source
                                            src="{{ storage_asset($profile->get('user')->video) }}" />
                                    </video>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                @if (auth()->guard('user')->check() && auth()->guard('user')->user()->id == $profile->get('user')->id)
                    <div class="text-center d-flex flex-column mt-2 col-12">
                        <div class="copy-card-data-left">
                            <button class="likely__button btn-clip h3 border-0"
                            data-title="{{__('global.copied')}}"
                            onclick="copyShareURL(this, '{{ $shareUrl }}')"
                            data-toggle="tooltip"

                            data-placement="top"
                            type="button"
                            style="color: var(--secondary-color); background: transparent">
                                    {{__("global.copy_profile_url")}}
                            </button>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 text-center d-flex align-items-center flex-column">
                        <div class="copy-card-data-left">
                            <h3 style="color: var(--secondary-color);">{{__("global.copy_profile_card")}}</h3>
                            <p class="text-light">{{__("global.copy_this_user")}} <strong>{{__("global.email_body")}}</strong>,<strong>{{__("global.email_signature")}}</strong>
                                ,<strong>{{__("global.word_doc")}}</strong>....</p>
                            <br>
                            <br>
                            <button class="copy-card-button secondary-btn btn mt-2" onclick="copyFormatted('copy-card', 'copy-card-image')">{{__('global.copy')}}</button>
                        </div>
                        {{-- <p class="w-100 text-warning small px-3" style="position: absolute;bottom: 0;">
                            {{__("global.make_sure")}} <strong>{{__("global.plain_text")}}</strong> {{__("global.is_disabled_to_pasting_client")}}
                        </p> --}}
                        <div class="mb-5 copy-card mt-5 flex-fill d-flex flex-column">
                            <p style="color: #fff; font-size: 48px" class="mb-2"><i class="fas fa-chevron-down"></i></p>
                            <div>
                                <a href="{{asset('storage/uploads/profile/card-'.$profile->get('user')->username.'.jpg')}}" download title="Click here to download card">
                                    <img src="{{asset('storage/uploads/profile/card-'.$profile->get('user')->username.'.jpg')}}" style="max-width:100%" alt="user profile image" class="copy-card-image">
                                </a>
                            </div>
                            <figcaption class="w-100 text-warning small px-3 mt-auto">
                                {{__("global.make_sure")}} <strong>{{__("global.plain_text")}}</strong> {{__("global.is_disabled_to_pasting_client")}}
                            </figcaption>
                            <textarea id="copy-card" style="display: none">
                                <a href="{{route('web.profile', ['username'=>$profile->get('user')->username])}}">
                                    <img src="{{asset('storage/uploads/profile/card-'.$profile->get('user')->username.'.jpg')}}" style="max-width:40%; max-height: 40%" alt="user profile image" class="no-link">
                                </a>
                            </textarea>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 text-center d-flex align-items-center flex-column">
                        <div class="copy-card-data-right">
                            <h3 style="color: var(--secondary-color);">{{__("global.copy_short_profile_card")}}</h3>
                            <p class="text-light">{{__("global.copy_this_user")}} <strong>{{__("global.email_body")}}</strong>,<strong>{{__("global.email_signature")}}</strong>
                                ,<strong>{{__("global.word_doc")}}</strong>....</p>
                            <br>
                            <br>
                            <button class="copy-card-button copy-short-card-button secondary-btn btn mt-2" onclick="copyFormatted('copy-short-card', 'copy-short-card-image')">{{__('global.copy')}}</button>
                        </div>
                        {{-- <p class="w-100 text-warning small px-3" style="position: absolute;bottom: 0;">
                            {{__("global.make_sure")}} <strong>{{__("global.plain_text")}}</strong> {{__("global.is_disabled_to_pasting_client")}}
                        </p> --}}
                        <div class="mb-5 mt-5 text-center h-100 d-flex align-items-center flex-column">
                            <p style="color: #fff; font-size: 48px" class="mb-2"><i class="fas fa-chevron-down"></i></p>
                            <div>
                                <a href="{{asset('storage/uploads/profile/short-card-'.$profile->get('user')->username.'.png')}}" class="m-auto" download title="Click here to download card">
                                    <!-- <img src="{{asset('storage/uploads/profile/short-card-'.$profile->get('user')->username.'.png')}}" style="max-width:100%" alt="Logo"> -->
                                    <img src="{{asset('storage/uploads/profile/short-card-'.$profile->get('user')->username.'.png')}}" style="width: 250px; max-width:100%" alt="Logo" class="copy-short-card-image">
                                </a>
                            </div>
                            <figcaption class="w-100 text-warning small px-3 mt-auto">
                                {{__("global.make_sure")}} <strong>{{__("global.plain_text")}}</strong> {{__("global.is_disabled_to_pasting_client")}}
                            </figcaption>
                            <textarea id="copy-short-card" style="display: none;">
                                <a href="{{route('web.profile', ['username'=>$profile->get('user')->username])}}" class="m-auto no-link">
                                    <!-- <img src="{{asset('storage/uploads/profile/short-card-'.$profile->get('user')->username.'.png')}}" style="max-width:100%" alt="Logo"> -->
                                    <img src="{{asset('storage/uploads/profile/short-card-'.$profile->get('user')->username.'.png')}}" style="max-height: 12%; max-width:12%" alt="Logo" >
                                </a>
                            </textarea>
                        </div>
                    </div>
                @else
                    <div class="text-center d-flex flex-column mt-2 col-12">
                        <div class="copy-card-data-center">
                            <button class="likely__button btn-clip h3 border-0"
                            data-title="{{__('global.copied')}}"
                            onclick="copyShareURL(this, '{{ $shareUrl }}')"
                            data-toggle="tooltip"

                            data-placement="top"
                            type="button"
                            style="color: var(--secondary-color); background: transparent">
                                    {{__("global.copy_profile_url")}}
                            </button>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        {{-- <h3 style="color: var(--secondary-color);">{{__("global.profile_card")}}</h3>
                        <img src="{{asset('storage/uploads/profile/card-'.$profile->get('user')->username.'.jpg')}}" style="max-width:100%" alt="profile card image"> --}}
                        <div class="copy-card-data-center">
                            <h3 style="color: var(--secondary-color);">{{__("global.copy_profile_card")}}</h3>
                            <p class="text-light">{{__("global.copy_this_user")}} <strong>{{__("global.email_body")}}</strong>,<strong>{{__("global.email_signature")}}</strong>
                                ,<strong>{{__("global.word_doc")}}</strong>....</p>
                            <br>
                            <br>
                            <button class="copy-card-button secondary-btn btn mt-2" onclick="copyFormatted('copy-card', 'copy-card-image')">{{__('global.copy')}}</button>
                        </div>
                        {{-- <p class="w-100 text-warning small px-3 card-caption">
                            {{__("global.make_sure")}} <strong>{{__("global.plain_text")}}</strong> {{__("global.is_disabled_to_pasting_client")}}
                        </p> --}}
                        <div class="mb-5 copy-card mt-5">
                            <p style="color: #fff; font-size: 48px" class="mb-2"><i class="fas fa-chevron-down"></i></p>
                            <div>
                                <a href="{{asset('storage/uploads/profile/card-'.$profile->get('user')->username.'.jpg')}}" download>
                                    <img src="{{asset('storage/uploads/profile/card-'.$profile->get('user')->username.'.jpg')}}" style="max-width:100%" alt="user profile image" class="copy-card-image">
                                </a>
                            </div>
                            <figcaption class="w-100 text-warning small px-3">
                                {{__("global.make_sure")}} <strong>{{__("global.plain_text")}}</strong> {{__("global.is_disabled_to_pasting_client")}}
                            </figcaption>
                            <textarea id="copy-card" style="display: none">
                                <a href="{{route('web.profile', ['username'=>$profile->get('user')->username])}}">
                                    <img src="{{asset('storage/uploads/profile/card-'.$profile->get('user')->username.'.jpg')}}" style="max-width:40%; max-height: 40%" alt="user profile image" class="no-link">
                                </a>
                            </textarea>
                        </div>
                    </div>
                @endif
            </div>

            <div class='likely likely-light text-center section_tittle pt-4 w-100 d-flex flex-wrap justify-content-center' data-url="{{ $shareUrl }}" data-title="">
                <div class='facebook' data-url="{{ $shareUrl }}" data-title=""></div>
                <div class='twitter' data-url="{{ $shareUrl }}" data-title=""></div>
                <div class='linkedin' data-url="{{ $shareUrl }}" data-title=""></div>
                <div class='telegram' data-url="{{ $shareUrl }}" data-title=""></div>
                <div class='whatsapp' data-url="{{ $shareUrl }}" data-title=""></div>
                <div class='instagram' data-url="{{ $shareUrl }}" data-title=""></div>
                {{-- <div class='likely__widget likely-email'>
                    <a class='likely__button' style='color:#fff; width: 16px; height: 16px' href="mailto:?subject=Sound and Tag Profile&body=Have a look at {{$profile->get('user')->full_name}}'s Sound and Tag Profile:{{$shareUrl}}">
                        <i class="fas fa-envelope likely__icon" style="font-size: 16px; width: 16px; height: 16px"></i>
                    </a>
                </div> --}}
                <div class='likely__widget likely-copy'>
                    <button class="likely__button btn-clip" title="{{__('global.copy_profile_url')}}" onclick="copyShareURL(this, '{{ $shareUrl }}')" data-toggle="tooltip" data-placement="top" type="button" style="width: 16px; height: 16px">
                        <img class='likely__icon' alt='Copy icon' src='<?=asset("images/img/copy.svg")?>' style="width: 16px; height: 16px">
                    </button>
                </div>
                <span class="likely__widget border-0" style="width: 20px">{{__('global.or')}}</span>
                <div class='likely__widget likely-mob' title="{{__('global.mobile_only')}}">
                    <a class='likely__button answer-example-share-button' href='javascript:;' data-url="{{ $shareUrl }}" style='color: #FFF; width: 16px; height: 16px'>
                        <img class='likely__icon' alt='Phone icon' src='<?=asset("images/img/phone.svg")?>' style="width: 16px; height: 16px">
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-player/2.0.2/lottie-player.js"
        integrity="sha512-NcJNVDzfhHalE0Dj3s8ub4hW0QYw1hfk05I53N3MTXOYDDGt0wpXmAPve+GhmmdDxik8pTUONQjeNKDO/p7rhQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('js/web/likely.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/codemirror.min.js"></script>
    <script>
        $('.no-link').click(function () {
            event.preventDefault();
        })

        const detectMobile = () => {
            const regex = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Windows Phone/i
            return regex.test(navigator.userAgent);
        }

        // Clipboard
        const copyFormatted = (id, imgClass) => {
            var container = document.createElement('div')


            var htmlEditor = CodeMirror.fromTextArea(document.getElementById(id), {
                mode: 'text/html'
            })

            container.innerHTML = htmlEditor.getValue();
            container.style.position = 'fixed'
            container.style.pointerEvents = 'none'
            container.style.opacity = 0

            container.querySelector("img").style.maxWidth = "130px"
            container.querySelector("img").style.maxHeight = "130px"

            if(detectMobile()) {
                if(id === "copy-card") {
                    container.querySelector("img").style.maxWidth = "65%"
                    container.querySelector("img").style.maxHeight = "65%"
                }
            }
            
            document.body.appendChild(container)

            window.getSelection().removeAllRanges()

            var range = document.createRange()
            range.selectNode(container)
            window.getSelection().addRange(range)

            document.execCommand('copy')
            document.querySelector(`.${imgClass}`).style.mixBlendMode = "overlay";
            setTimeout(() => {
                document.querySelector(`.${imgClass}`).style.mixBlendMode = "normal";
            }, 1300)

            document.body.removeChild(container)
        };

        const copyShareURL = (e, text) => {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            e.title="{{__('global.copied')}}";
            $(e).tooltip("show");
        }

        const audioElement = document.getElementById('myAudio');
        let currentKey;
        let progressBar;

        const clearActive = () => {
            document.querySelectorAll(".listen-btn").forEach(btn => btn.innerHTML = `<i class="fas fa-play"></i>`)
            document.querySelectorAll(".record-card.active").forEach(card => card.classList.remove("active"))
            if(progressBar) progressBar.style.animationPlayState = "running";
            const progressBars = document.querySelectorAll(`.record-progress-bar`).forEach(bar => bar.classList.remove("active"))
        }

        // Remove active class and sound wave when ended
        audioElement.addEventListener("ended", (e) => {
            clearActive();
        })

        audioElement.addEventListener("loadedmetadata", (e) => {
            progressBar.classList.add("active")
            progressBar.style.animationDuration = `${audioElement.duration}s`;
        })

        // Audios
        function listen(listenBtn) {
            if(audioElement.src === listenBtn.getAttribute('data-value')) {
                if(audioElement.paused) {
                    // Play same audio or play after pause
                    audioElement.play();
                    listenBtn.innerHTML =
                        `<lottie-player src="{{asset('js/web/sound-wave.json')}}"  background="transparent" style="width: 24px; height: 24px; margin: 0 auto"  loop  autoplay></lottie-player>`;
                    progressBar.style.animationPlayState = "running";
                } else {
                    // Pause audio
                    audioElement.pause();
                    listenBtn.innerHTML = `<i class="fas fa-pause"></i>`;
                    progressBar.style.animationPlayState = "paused";
                }

                return;
            }

            // Remove active class and sound wave from previous audios
            clearActive();

            listenBtn.innerHTML =
                `<lottie-player src="{{asset('js/web/sound-wave.json')}}"  background="transparent" style="width: 24px; height: 24px; margin: 0 auto"  loop  autoplay></lottie-player>`
            audioElement.autoplay = true;
            audioElement.setAttribute('src',listenBtn.getAttribute('data-value'));
            audioElement.setAttribute('data-author',listenBtn.getAttribute('data-author'));
            audioElement.setAttribute('data-title',listenBtn.getAttribute('data-title'));
            listenBtn.parentElement.parentElement.classList.add("active");
            currentKey = listenBtn.getAttribute('data-key');
            progressBar = document.querySelector(`div.record-progress[data-key='${currentKey}'] .record-progress-bar`);
        }
        $(document).on('click','.answer-example-share-button', function() {
            const url = $(this).attr('data-url');
            if (navigator.share) {
                navigator.share({
                    title: '{{$profile->get('user')->full_name}} Sound&Tag Profile',
                    text: 'Have a look at {{$profile->get('user')->full_name}} Sound&Tag Profile',
                    url: url,
                })
                    .then(() => console.log('Successful share'))
                    .catch((error) => console.log('Error sharing', error));
            } else {
                console.log('Share not supported on this browser, do it the old way.');
            }
        });

        // Check for biography height
        document.querySelector("#public-data").addEventListener('change', (e) => {
            if(e.target.checked) {
                const textElm = document.querySelector(".biography .record-card-text span");
                const labelElm = document.querySelector(".biography .record-card-text label");

                if(textElm) {
                    if(textElm.scrollHeight - textElm.clientHeight > 20)
                        labelElm.hidden = false;
                    else
                        labelElm.hidden = true;
                }

            }
        });

        // Toggle read more and read less
        document.querySelector("#biography-more")?.addEventListener("change", (e) => {
            const labelElm = document.querySelector(".biography .record-card-text label");

            if(e.target.checked) labelElm.innerHTML = "{{__('global.read_less')}}"
            else labelElm.innerHTML = "{{__('global.more_about_me')}}"
        })

    </script>
@endsection
