@extends('web.layouts.master')
@section('styles')
<link rel="stylesheet" href="{{asset('css/web/modal.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/edit-profile.css?v=1')}}" />
@endsection
@section('content')
    <div id="vue-app">
        <section class="about_me_section sec_padding" >
            <div class="container">
                <div class="section_tittle style_2" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInDown;">
                    <h5 style="color: #ff7070 !important;">{{__('global.online_pronunciation')}}</h5>
                    <h2 style="font-weight: 400;">{{__('global.record_your_pronunciation')}}</h2>
                </div>
                <div class="row align-items-top justify-content-center" v-if="records" v-cloak>
                    <div class="col-lg-8 col-md-10" v-for="available_record_type in records.available_record_types">
                        <div class="d-flex flex-nowrap flex-row py-2 pl-4 my-2 record-card justify-content-between align-items-center">
                            <input 
                                type="text" 
                                class="flex-fill p-0 border-0 record-card-text bg-transparent" 
                                v-model="available_record_type.record.text_representation" 
                                :placeholder="available_record_type.name + [[available_record_type.required ? '*' : '']]" 
                                :id="'record'+available_record_type.id">
                            <div class="listen-btn-container mr-n1 d-none d-sm-block">
                                <a class="btn btn-success btn-outline listen-btn small-btn rounded-circle" href="javascript:;" v-on:click="available_record_type.record.text_representation && openRecordModal(available_record_type)" :disabled="!available_record_type.record.text_representation">
                                    <i :class="[{'record-icon-norecord' : !available_record_type.record.file_path}, 'fas fa-microphone']"></i>
                                </a>
                            </div>
                        </div>
                        <div class="listen-btn-container mx-auto d-block d-sm-none mb-4">
                            <a class="btn btn-success btn-outline listen-btn small-btn rounded-circle" href="javascript:;" v-on:click="available_record_type.record.text_representation && openRecordModal(available_record_type)" :disabled="!available_record_type.record.text_representation">
                                <i :class="[{'record-icon-norecord' : !available_record_type.record.file_path}, 'fas fa-microphone']"></i>
                            </a>
                        </div>
                        <p class="invalid-feedback d-block" v-text="errors[available_record_type.name]" />
                        <div class="position-relative mx-auto" style="width: max-content" v-if="available_record_type.record.text_representation">
                            <button type="button" class="btn btn-lg position-absolute" data-toggle="save-popover" :data-name="available_record_type.id" :data-content="'{{__('global.save_all_data')}}'" data-placement="bottom" style="pointer-events: none; bottom: 10px"></button>
                            <button class="disabled btn secondary-btn mx-auto d-block mb-3" :data-id="available_record_type.id" v-on:click="saveRecord(available_record_type, $event)">{{__('global.save')}}</button>
                        </div>
                    </div>
                </div>
                <div class="w-100 d-flex mt-3">
                    <div class="online-rcrd-buttons m-auto" v-bind:disabled="!is_public">
                        <a href="{{route('web.profile', ['username'=>auth()->guard('user')->user()->username])}}" class="btn primary-btn py-2 px-3" v-bind:style="{pointerEvents: !is_public ? 'none' : 'all'}" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInDown;">{{__('global.view_public_profile')}}</a>
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
                        <ul id="list">

                        </ul>
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
                                            <button class="btn small-btn btn-danger recorder-controls recorder-stop mr-2"
                                            v-if="recording_state == 'paused' || recording_state == 'recording'" v-on:click="stopRecording()">
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
                                            <button class="btn small-btn btn-success player-controls player-resume rounded mr-2"
                                                    v-if="playing_state == 'ready' || playing_state == 'paused'" v-on:click="playWaveSurfer()">
                                                <i class="fa fa-play"></i></button>
                                            <button class="btn small-btn btn-warning player-controls player-pause rounded mr-2"
                                                    v-if="playing_state == 'playing'" v-on:click="pauseWaveSurfer()">
                                                <i class="fa fa-pause"></i></button>
                                            <button class="btn small-btn btn-danger player-controls player-stop mr-2"
                                                    v-if="playing_state == 'playing' || playing_state == 'paused'" v-on:click="stopWaveSurfer()">
                                                <i class="fa fa-stop"></i></button>
                                        </div>
                                        <div class="d-flex justify-centent-center" v-if="playing_state == 'ready' && active_item.recorded_voice">
                                            <p class="text-center text-white" style="font-size: 14px">{{__('global.record_successfully')}}</p>
                                        </div>
                                    </div>
                                    <div class="not-selected indicators h-100" v-else v-cloak>
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <span class="d-inline-flex">{{__('global.record_instruction')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="online-rcrd-buttons mx-auto done-recording">
                                    <a href="#" v-bind:class="[{disabled : active !== 'player'}, 'btn', 'primary-btn', 'py-2', 'px-3']" id="done-btn"  style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInDown;" v-on:click="closeModal($event)">{{__('global.done')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('web.partials.voice-record')
    @include('web.partials.axiosinit')
    @include('web.partials.profile-vue-app')
    <script>
        const recordModal = $("#record-modal");
        recordModal.on("hide.bs.modal", function(e) {
            vm.checkHideModal(e, recordModal);
        })
        recordModal.on("shown.bs.modal", () => {
            document.querySelector(".done-recording a").innerHTML = "{{__('global.done')}}";
            document.querySelector(".done-recording a").classList.add("disabled");
        })
    </script>
@endsection
