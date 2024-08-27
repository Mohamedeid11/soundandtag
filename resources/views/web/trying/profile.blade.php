@extends('web.layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/web/likely.css')}}" />
    <link rel="stylesheet" href="{{asset('css/web/likely-custom.css')}}" />
    <link rel="stylesheet" href="{{asset('css/web/profile.css?v=1')}}" />
    <style>
        .no-link {
            cursor: default;
        }
        .CodeMirror {
            display: none;
        }
    </style>
@endsection

@section('content')
    @php
    $shareUrl = LaravelLocalization::getNonLocalizedURL(route('web.getTryingUserProfile', ['user_id'=>$user->id]))
    @endphp
    <div id="vue-app" class="about_me_section sec_padding">
         <div class="container overflow-hidden mb-3">
            <div class="text-center profile-title mt-5" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInDown;">
                <h5 class="base_color">{{__('global.sound_profile')}}</h5>
                <h2 style="font-weight: 400;">[[user.name]]</h2>
            </div>
            <div class="row mb-5 mr-md-5 ml-md-5 mr-2 ml-2" v-cloak>
                <div class="col-sm-4 ">
                    <div class="col-lg-12 text-center">
                        <img :src="[[user.image]]" class="rounded-circle profile-img" alt="user profike image">
                    </div>
                    <h3 class="text-center text-light mt-3">[[user.name]]</h3>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center p-2 d-none audio-container" id="vz-wrapper">
                                <audio id="myAudio" src="" data-author="insert/author/name" data-title="insert/audio/name" preload="none"></audio>
                            </div>
                        </div>
                    </div>
                        <div v-for="record in records" v-cloak class="my-3">
                            <div class="d-flex flex-row py-2 pr-4 pr-sm-0 pl-4 mb-2 record-card record-profile-card justify-content-between align-items-center">
                                <div class="d-flex flex-wrap w-100 align-items-center">
                                    <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">[[record.name]] </h5>
                                    <p class="record-card-text col-12 col-sm-6 col-md-8 col-xl-10 text-center">[[record.text]]</p>
                                </div>
                                <div class="absolute-btn position-absolute">
                                    <div class="listen-btn-container mr-n1 d-none d-sm-block">
                                        <a class="btn btn-success btn-outline listen-btn small-btn rounded-circle"
                                            href="javascript:;"
                                            onclick="listen(this)"
                                            :data-key="[[record.name]]"
                                            :data-title="[[record.text]]"
                                            :data-author="[[record.name]]"
                                            :data-value="[[record.url]]">
                                            <i class="fas fa-play"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="listen-btn-container d-block d-sm-none mx-auto">
                                <a class="btn btn-success btn-outline listen-btn small-btn rounded-circle"
                                    href="javascript:;"
                                    onclick="listen(this)"
                                    :data-key="[[record.name]]"
                                    :data-title="[[record.text]]"
                                    :data-author="[[record.name]]"
                                    :data-value="[[record.url]]">
                                    <i class="fas fa-play"></i>
                                </a>
                            </div>
                        </div>
                        <div class="d-flex py-2 px-4 my-2 record-card justify-content-between">
                            <div class="d-flex flex-wrap w-100 align-items-center">
                                <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">{{__('global.email')}}</h5>
                                <p class="record-card-text col-12 col-sm-6 col-md-8 col-xl-10 text-center">[[user.email]]</p>
                            </div>
                        </div>
                        <div class="d-flex py-2 px-4 my-2 record-card justify-content-between" v-if="user.video">
                            <div class="d-flex flex-wrap w-100 align-items-center">
                                <h5 class="m-0 col-12 col-sm-6 col-md-4 col-xl-2 p-0 record-heading text-center text-sm-left">{{__('global.video')}}</h5>
                                <video class="col-12 col-sm-6 col-md-8 col-xl-10" controls v-cloak
                                    :src="[[user.video]]">
                                    <source :src="[[user.video]]" v-cloak />
                                </video>
                            </div>
                        </div>

                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                    <div class="text-center d-flex flex-column mt-2 col-12">
                        <div class="copy-card-data-left">
                            <button class="likely__button btn-clip h3 border-0"
                            data-title="{{__('global.copied')}}"
                            @click="copyShareURL('{{ $shareUrl }}', $event)"
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
                            <button class="copy-card-button secondary-btn btn mt-2" @click="copyFormatted('copy-card', 'copy-card-image')">{{__('global.copy')}}</button>
                        </div>
                        <div class="mb-5 copy-card mt-5 flex-fill d-flex flex-column"> 
                            <p style="color: #fff; font-size: 48px" class="mb-2"><i class="fas fa-chevron-down"></i></p>
                            <div>
                                <a href="{{asset('storage/uploads/profile/card-'.$user->id.'.jpg')}}" download>
                                    <img src="{{asset('storage/uploads/profile/card-'.$user->id.'.jpg')}}" style="max-width:100%" alt="user profile image" class="copy-card-image">
                                </a>
                            </div>
                            <figcaption class="w-100 text-warning small px-3 mt-auto">
                                {{__("global.make_sure")}} <strong>{{__("global.plain_text")}}</strong> {{__("global.is_disabled_to_pasting_client")}}
                            </figcaption>
                            <textarea id="copy-card" style="display: none">
                                <style scoped>
                                    img {
                                        max-width: 40%;
                                        max-height: 40%;
                                    }
                                    @media screen and (max-width: 600px) {
                                        img {
                                            max-width: 65%;
                                            max-height: 65%;
                                        }
                                    }
                                </style>
                                <a href="{{route('web.getTryingUserProfile', ['user_id'=>$user->id])}}">
                                    <img src="{{asset('storage/uploads/profile/card-'.$user->id.'.jpg')}}" alt="user profile image" class="no-link">
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
                            <button class="copy-card-button copy-short-card-button secondary-btn btn mt-2" @click="copyFormatted('copy-short-card', 'copy-short-card-image')">{{__('global.copy')}}</button>
                        </div>
                        <div class="mb-5 mt-5 text-center h-100 d-flex align-items-center flex-column">
                            <p style="color: #fff; font-size: 48px" class="mb-2"><i class="fas fa-chevron-down"></i></p>
                            <div>
                                <a href="{{route('web.getTryingUserProfile', ['user_id'=>$user->id])}}" class="m-auto no-link">
                                    <!-- <img src="{{asset('images/img/logo-card.png')}}" style="max-width:100%" alt="Logo"> -->
                                    <img src="{{asset('storage/uploads/profile/short-card-'.$user->id.'.png')}}" style="width: 250px; max-width:100%" alt="Logo" class="copy-short-card-image">
                                </a>
                            </div>
                            <figcaption class="w-100 text-warning small px-3 mt-auto">
                                {{__("global.make_sure")}} <strong>{{__("global.plain_text")}}</strong> {{__("global.is_disabled_to_pasting_client")}}
                            </figcaption>
                            <textarea id="copy-short-card" style="display: none;">
                                <a href="{{route('web.getTryingUserProfile', ['user_id'=>$user->id])}}" class="m-auto no-link">
                                    <!-- <img src="{{asset('images/img/logo-card.png')}}" style="max-width:100%" alt="Logo"> -->
                                    <img src="{{asset('storage/uploads/profile/short-card-'.$user->id.'.png')}}" style="max-height: 12%; max-width:12%" alt="Logo">
                                </a>
                            </textarea>
                        </div>
                    </div>
            </div>

            <div class='likely likely-light text-center section_tittle pt-4 w-100 d-flex flex-wrap justify-content-center' data-url="{{ $shareUrl }}" data-title="">
                <div class='facebook' data-url="{{ $shareUrl }}" data-title=""></div>
                <div class='twitter' data-url="{{ $shareUrl }}" data-title=""></div>
                <div class='linkedin' data-url="{{ $shareUrl }}" data-title=""></div>
                <div class='telegram' data-url="{{ $shareUrl }}" data-title=""></div>
                <div class='whatsapp' data-url="{{ $shareUrl }}" data-title=""></div>
                <div class='likely__widget likely-copy'>
                    <button class="likely__button btn-clip" @click="copyShareURL('{{ $shareUrl }}', $event)" data-toggle="tooltip" data-placement="top" type="button" style="width: 16px; height: 16px">
                        <img class='likely__icon' alt='Copy icon' src='<?=asset("images/img/copy.svg")?>' style="width: 16px; height: 16px">
                    </button>
                </div>
                <span class="likely__widget border-0" style="width: 20px">{{__('global.or')}}</span>
                <div class='likely__widget likely-mob' title="Mobile Only">
                    <a class='likely__button answer-example-share-button' href='javascript:;' data-url="{{ $shareUrl }}" style='color: #FFF; width: 16px; height: 16px'>
                        <img class='likely__icon' alt='Phone icon' src='<?=asset("images/img/phone.svg")?>' style="width: 16px; height: 16px">
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.js" integrity="sha512-PyKhbAWS+VlTWXjk/36s5hJmUJBxcGY/1hlxg6woHD/EONP2fawZRKmvHdTGOWPKTqk3CPSUPh7+2boIBklbvw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-player/2.0.2/lottie-player.js"
        integrity="sha512-NcJNVDzfhHalE0Dj3s8ub4hW0QYw1hfk05I53N3MTXOYDDGt0wpXmAPve+GhmmdDxik8pTUONQjeNKDO/p7rhQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('js/web/likely.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/codemirror.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script> -->
    <script>
        const config = {
            headers: {Accept: 'application/json' }
        };
        axios.defaults.headers.common = config.headers;
        axios.defaults.baseURL = "{{config('app.url')}}";

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
    </script>
    <script>
    $(document).ready(function(){
        $('.no-link').click(function () {
            event.preventDefault();
        })

        $(document).on('click','.answer-example-share-button', function() {
            const url = $(this).attr('data-url');
            if (navigator.share) {
                navigator.share({
                    title: '{{$record->full_name}} Sound&Tag Profile',
                    text: 'Have a look at {{$record->full_name}} Sound&Tag Profile',
                    url: url,
                })
                    .then(() => console.log('Successful share'))
                    .catch((error) => console.log('Error sharing', error));
            } else {
                console.log('Share not supported on this browser, do it the old way.');
            }
        });
    });
    </script>
    @include('web.partials.trying-profile-vue-app')
@endsection
