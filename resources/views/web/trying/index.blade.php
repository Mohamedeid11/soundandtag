@extends('web.layouts.master')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
<link rel="stylesheet" href="{{asset('css/web/modal.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/edit-profile.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/home.css?v=1')}}" />
<style>
    .saved {
        color: var(--secondary-color)
    }

    .crop-preview.preview {
        height: 150px;
        max-height: 150px;
        width: 150px;
        max-width: 150px;
    }

    .record-card-text,
    .record-card-text-muted {
        font-size: 1rem;
    }

    .cropper-crop-box,
    .cropper-view-box,
    .profile-pic-image {
        border-radius: 50%;
    }
</style>
@endsection
@section('content')
<div id="vue-app">
    <section class="team_member_part about_me_section sec_padding">
        <div class="container">
            <div class="section_tittle style_2" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInDown;">
                <h5 style="color: #ff7070 !important;">{{__('global.give_it_a_try')}}</h5>
                @if ($text)
                <h2 style="font-weight: 400;">{{$text}}</h2>
                @else
                <h2 style="font-weight: 400;">{{__('global.days_free_trial')}}</h2>
                @endif
            </div>
            <div id="crop-preview" class="crop-preview preview offset-6 col-3 p-0 mx-auto mt-2 rounded-circle" data-target="#profile-pic-chooser" onclick="$('#profile-pic-chooser').modal('show')">
                <div class="profile-preview-div preview-div">
                    <a href="#" data-toggle="modal" data-target="#profile-pic-chooser">
                        <img class="profile-pic-image" height="150" width="150" src="{{asset('images/default-user.png')}}" alt="User avatar">
                    </a>
                </div>
            </div>
            <div class="row" v-cloak>

                <!-- Email -->
                <div class="single_contact_form form-group col-lg-8 col-md-10 mt-3 mb-0 mx-auto">
                    <div class="d-flex flex-nowrap flex-row py-2 px-4 record-card email-input">
                        <button type="button" class="btn btn-lg position-absolute" style="left: 47%; transform: translate(-53%, 30px); visibility: hidden" data-toggle="email-popover" data-content="{{__('global.enter_your')}} {{__('global.email')}}" data-placement="top" style="pointer-events: none"></button>
                        <input type="email" class="w-100 record-card-text p-0 border-0 bg-transparent" placeholder="Email*" id="email" v-model="email">
                    </div>
                    <div class="feedback"></div>
                </div>

                <!-- Data -->
                <div class="col-lg-8 col-md-10 mx-auto mt-3" v-for="available_record_type in personal_records">
                    <div :class="[available_record_type.name.split(' ').join('-'), 'single_contact_form', 'form-group', 'd-flex', 'flex-no-wrap', 'record-card', 'py-2', 'pl-4', 'mb-0', 'justify-content-between', 'align-items-center']">
                        <input type="text" class="flex-fill p-0 border-0 record-card-text bg-transparent" v-model="available_record_type.record.text_representation" :placeholder="available_record_type.name + '*'" :id="'record'+available_record_type.id">
                        <button type="button" class="btn btn-lg position-absolute" style="left: 47%; transform: translate(-53%, 30px); visibility: hidden" data-toggle="first-popover" :data-name="available_record_type.name" :data-content="'{{__('global.enter_your')}} '+available_record_type.name" data-placement="top" style="pointer-events: none"></button>
                        <div class="listen-btn-container mr-n1 d-none d-sm-block">
                            <button type="button" class="btn btn-lg position-absolute" data-toggle="second-popover" :data-name="available_record_type.name" :data-content="'{{__('global.record_popover_button')}}'+available_record_type.name" data-placement="bottom"></button>
                            <a class="btn btn-success btn-outline listen-btn small-btn rounded-circle" href="javascript:;" v-on:click="openRecordModal(available_record_type)">
                                <i class="fas fa-microphone"></i>
                            </a>
                        </div>
                    </div>
                    <div class="feedback"></div>
                    <div class="listen-btn-container mx-auto d-block d-sm-none mt-2">
                        <button type="button" class="btn btn-lg position-absolute" data-toggle="second-popover" :data-name="available_record_type.name" :data-content="'{{__('global.record_popover_button')}}'+available_record_type.name" data-placement="bottom"></button>
                        <a class="btn btn-success btn-outline listen-btn small-btn rounded-circle" href="javascript:;" v-on:click="openRecordModal(available_record_type)">
                            <i class="fas fa-microphone"></i>
                        </a>
                    </div>
                </div>

                <!-- Image -->
                <div class="single_contact_form form-group col-lg-8 col-md-10 mt-3 mb-0 mx-auto image-input">
                    <div class="d-flex flex-nowrap flex-row py-2 px-4 record-card">
                        <label for="image" class="m-0 record-card-text d-flex align-items-center">
                            <button class="border-0 font-weight-normal mr-2 bg-light" style="pointer-events: none">{{__('global.choose_image')}}</button>
                            <span>[[image_txt]]</span>
                        </label>
                        <input type="file" class="w-100 record-card-text p-0 border-0 bg-transparent" id="image" v-on:change="handleImageChange($event)" accept="image/*" hidden>
                        <input type="hidden" name="image_cropping">
                    </div>
                    <div class="feedback"></div>
                </div>

                <!-- Video -->
                <div class="single_contact_form form-group col-lg-8 col-md-10 mt-3 mb-0 mx-auto video-input">
                    <div class="d-flex flex-nowrap flex-row py-2 px-4 record-card">
                        <label for="video" class="m-0 record-card-text d-flex align-items-center">
                            <button class="border-0 font-weight-normal mr-2 bg-light" style="pointer-events: none">{{__('global.choose_video')}}</button>
                            <span>[[video_txt]]</span>
                        </label>
                        <input type="file" class="w-100 record-card-text p-0 border-0 bg-transparent video-input-file" id="video" name="video" v-on:change="handleVideoChange($event)" accept="video/*" hidden>
                    </div>
                    <p style="font-size: 14px;color: var(--light-color);">{{ __('global.video_size_limit') }}</p>
                    <div class="feedback"></div>
                </div>

                <!-- Accept -->
                <div class="single_contact_form col-lg-8 col-md-10 mt-5 terms-check mx-auto">
                    <div class="d-flex justify-content-center align-items-center">
                        <button type="button" class="btn btn-lg position-absolute" data-toggle="forth-popover" data-content="{{__('global.click_hear_to_accept')}} {{__('global.terms_conditions')}}" data-placement="top" style="pointer-events: none"></button>
                        <input type="checkbox" name="terms" id="terms" value="terms" v-model="terms">
                        <label for="terms" class="mb-0 ml-1"> {{__('global.accept')}} <a href="{{url('/terms')}}" target="_blank">{{__('global.terms_conditions')}}</a></label>
                    </div>
                </div>
                <div class="feedback text-center"></div>
            </div>
            <div class="w-100 d-flex mt-2">
                <div class="online-rcrd-buttons m-auto" @mouseover="hoverSubmit()" @mouseout="blurSubmit()">
                    <button type="button" class="btn btn-lg position-absolute" data-toggle="fifth-popover" data-content="{{__('global.save_all_data')}}" data-placement="top" style="pointer-events: none"></button>
                    <a @click="viewProfile()" :class="[{disabled : !is_complete}, 'btn', 'primary-btn', 'py-2', 'px-3', 'mx-auto', 'd-block']" style="width: max-content">{{__('global.go_ahead')}}</a>
                    <p class="text-center mt-2 text-success success-msg"></p>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="record-modal" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid record-voice-container" v-if="active_item.record_type" v-cloak>
                        <div class="row">
                            <input id="record-input" class="record-input d-none" type="file" accept="audio/*" v-on:change="changeVoice($event)">
                            <div class="col-6">
                                <div class="voice-rcrd-icon">
                                    <a href="javascript:;" id="record-button" v-on:click="voiceRecord($event)"><img src="{{asset('images/img/icon/play_icon.png')}}" alt="record icon"></a>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="voice-upload-icon">
                                    <a href="javascript:;" id="upload-button" v-on:click="voiceUpload($event)"><img src="{{asset('images/img/icon/upload.png')}}" alt="upload icon"></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-12 p-4 pt-1">
                                <div class="visualizer indicators h-100" v-if="active == 'recorder'" v-cloak>
                                    <div id="mic-container" class="w-100 mic-container">
                                        <div id="waveform-mic" class="w-100 position-relative">
                                            <div class="justify-content-center align-items-center w-100 h-100 position-absolute d-none" id="loading">
                                                <div class="spinner-border text-light" style="width: 3rem; height: 3rem" role="status">
                                                </div>
                                            </div>
                                            <div class="waveform w-100" id="waveform"></div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mb-2" v-if="wavesurfer">
                                        <span>[[recordedTime]]</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <button class="btn small-btn btn-danger recorder-controls recorder-stop mr-2" v-if="recording_state == 'paused' || recording_state == 'recording'" v-on:click="stopRecording()">
                                            <i class="fa fa-stop"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="player indicators" v-else-if="active=='player'" v-cloak>

                                    <div class="waveform-mic w-100 position-relative">
                                        <div class="justify-content-center align-items-center w-100 h-100 position-absolute d-none" id="loading">
                                            <div class="spinner-border text-light" style="width: 3rem; height: 3rem" role="status">
                                            </div>
                                        </div>
                                        <div class="waveform" id="waveform" style="width: 440px">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between" v-if="wavesurfer">
                                        <span>[[recordedTime]]</span>
                                        <span>[[recordDuration]]</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <button class="btn small-btn btn-success player-controls player-resume rounded mr-2" v-if="playing_state == 'ready' || playing_state == 'paused'" v-on:click="playWaveSurfer()">
                                            <i class="fa fa-play"></i></button>
                                        <button class="btn small-btn btn-warning player-controls player-pause rounded mr-2" v-if="playing_state == 'playing'" v-on:click="pauseWaveSurfer()">
                                            <i class="fa fa-pause"></i></button>
                                        <button class="btn small-btn btn-danger player-controls player-stop mr-2" v-if="playing_state == 'playing' || playing_state == 'paused'" v-on:click="stopWaveSurfer()">
                                            <i class="fa fa-stop"></i></button>
                                    </div>
                                </div>
                                <div class="not-selected indicators h-100" v-else v-cloak>
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <span class="d-inline-flex">{{__('global.record_instruction')}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="online-rcrd-buttons mx-auto">
                                <a href="#" v-bind:class="[{disabled : active !== 'player'}, 'btn', 'primary-btn', 'py-2', 'px-3']" id="done-btn" v-on:click="closeModal($event)">{{__('global.done')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade crop-modal" id="profile-pic-chooser" data-value="profile_image_copper">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('global.crop_image')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="width: 100%; height: 400px">
                        <img class="profile-pic-chooser-image" alt="user image chooser" src="{{asset('images/default-user.png')}}" style="max-width: 100%;display: block;max-height:100%">
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        <button onclick="handleZoomIn()" class="btn primary-btn small-btn mr-3">
                            <i class="fas fa-search-plus"></i>
                        </button>
                        <button onclick="handleZoomOut()" class="btn primary-btn small-btn">
                            <i class="fas fa-search-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn primary-btn crop-image-btn" data-dismiss="modal" v-on:click="doneCrop()">{{__('global.done_cropping')}}</button>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
@section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>
<script src="{{asset('plugins/cropper.min.js')}}"></script>
<script>
    const config = {
        headers: {
            Accept: 'application/json'
        }
    };
    axios.defaults.headers.common = config.headers;
    axios.defaults.baseURL = "{{config('app.url')}}";

    // Zoom in image in crop box
    const handleZoomIn = () => {
        cropper.zoom(0.1)
    }

    // Zoom out image in crop box
    const handleZoomOut = () => {
        cropper.zoom(-0.1)
    }
</script>
@include('web.partials.voice-record')
@include('web.partials.trying-vue-app')
@endsection