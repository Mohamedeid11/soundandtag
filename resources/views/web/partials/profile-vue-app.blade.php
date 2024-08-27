<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.js"
    integrity="sha512-PyKhbAWS+VlTWXjk/36s5hJmUJBxcGY/1hlxg6woHD/EONP2fawZRKmvHdTGOWPKTqk3CPSUPh7+2boIBklbvw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/wavesurfer.js/6.6.4/wavesurfer.min.js"
    integrity="sha512-/OXj4FUgEscXKD5YhrFOjx2NWZ7gjGatMETWNd0z6CNyF/pPDUto2Fu3qE2qqh1kVn5or3jGogF01s1ioSts9A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/wavesurfer.js/6.6.4/plugin/wavesurfer.microphone.min.js"></script>
<script src="{{asset('js/admin/volume-meter.js')}}"></script>
<script src="{{asset('js/admin/meter-running.js')}}"></script>
<script src="{{asset('plugins/web-audio-recorder/lib-minified/WebAudioRecorder.min.js')}}"></script>
<script>
    let timer = 0;
    var gumStream;
    var vm = new Vue({
        el: "#vue-app",
        delimiters: ['[[', ']]'],
        data: {
            'records': null,
            'active_item':{
                'record_type': null,
                'recorded_voice': null
            },
            'wavesurfer': null,
            'playing_state': null,
            'recording_state': 'idle',
            'active': null,
            'mediaRecorder' : null,
            'analyserNode': null,
            'frequencyData': new Uint8Array(128),
            'animation': null,
            'recordingBlob': null,
            'recordTime': 0,
            'sweet_alert_shown': false,
            'is_public': false,
            'errors': {}
        },
        mounted: function () {
            this.loadProfileRecords();
        },
        computed: {
            recordedTime: function() {
                return _recordTime(this.recordTime);
            },
            recordDuration: function() {
                return _recordDuration(this.wavesurfer?.getDuration() || 0);
            },
            textChanged: function() {
                return this.records?.available_record_types.map((available_record_type) => ({text: available_record_type.record.text_representation, id: available_record_type.id}))
            }
        },
        watch: {
            textChanged: {
                handler(newVal, oldVal) {
                    if(oldVal) {
                        const idChanged = newVal.find(({text, id}) => !oldVal.some(old => old.text === text && old.id === id)).id
                        if(document.querySelector(`[data-id="${idChanged}"]`)) {
                            document.querySelector(`[data-id="${idChanged}"]`).classList.remove("disabled");
                            document.querySelector(`[data-id="${idChanged}"]`).innerHTML = "Save";
                            $(`[data-toggle="save-popover"][data-name="${idChanged}"]`).popover("show");
                        }
                    }
                    const emptyText = newVal.find(({text, id}) => !text && oldVal?.some(old => old.text !== text && old.id === id));
                    if(emptyText) {
                        $(`[data-toggle="save-popover"][data-name="${emptyText.id}"]`).popover("hide");
                    }
                },
                deep: true
            }
        },
        methods: {
            dataURItoBlob(dataURI) {
                return _dataURIToBlob(dataURI);
            },
            openRecordModal(available_record_type){
                return _openRecordModal(this, available_record_type);
            },
            closeModal(event){
                const recordId = this.active_item.record_type.id;
                document.querySelector(`[data-id="${recordId}"]`).classList.remove("disabled");
                document.querySelector(`[data-id="${recordId}"]`).innerHTML = "Save";
                $(`[data-toggle="save-popover"][data-name="${recordId}"]`).popover("show");
                return _closeModal(event, this);
            },
            initializeWaveSurfer(){
                return _initializeWaveSurfer(this, timer);
            },
            playWaveSurfer(){
                return _playWaveSurfer(this, timer);
            },
            pauseWaveSurfer(){
                return _pauseWaveSurfer(this, timer);
            },
            stopWaveSurfer(){
                return _stopWaveSurfer(this, timer);
            },
            voiceUpload(){
                return _voiceUpload(this);
            },
            voiceRecord(event){
                return _voiceRecord(this, event);
            },
            stopRecording(){
                return _stopRecording(this, timer);
            },
            handleSuccess() {
                return _handleSuccess(this);
            },
            changeVoice(event){
                return _changeVoice(this, event);
            },
            loadProfileRecords(){
                let vue = this;
                axios.get("{{route('web.api.profile.details')}}")
                    .then(function(response){
                        if (response && response.status === 200) {
                            vue.records = response.data.records;
                            vue.is_public = response.data.is_public
                        }
                    }).catch(function (err) {
                        console.log(err);
                    });
            },
            saveRecord(available_record_type, event){
                const { target } = event
                let vue = this;
                
                if(target.classList.contains("disabled")) return;
                if(available_record_type.record.text_representation.length < 3) {
                    this.errors = {...this.errors, [available_record_type.name]: "The text representation must be at least 3 characters."};
                    return;
                } else if(!available_record_type.record.recorded_voice) {
                    this.errors = {...this.errors, [available_record_type.name]: "The record data field is required."};
                    return;
                } else {
                    this.errors = {...this.errors, [available_record_type.name]: ""};
                }

                axios.post("{{route('web.api.profile.save_record')}}", ({
                    record_type_id: available_record_type.id,
                    record_data: available_record_type.record.recorded_voice,
                    text_representation: available_record_type.record.text_representation
                })).then(function(response){
                    if (response.data.status === 1) {
                        vue.is_public = response.data.is_public;
                        target.innerHTML = `Saved`;
                        vue.errors = {...vue.errors, [available_record_type.name]: ""};
                    } else {
                        swal({
                            title: "{{__('global.complete_sbscription')}}",
                            type: "error",
                            showCancelButton: false,
                            confirmButtonClass: "primary-btn",
                            confirmButtonText: "{{__('global.go_ahead')}}",
                            cancelButtonText: "{{__('admin.cancel')}}",
                            closeOnConfirm: false
                        }, function(confirm) {
                            if (confirm) {
                                window.location = "{{route('account.status')}}";
                            }
                        });
                        target.innerHTML = `Failed`
                    }
                    $(`[data-toggle="save-popover"][data-name="${available_record_type.id}"]`).popover("hide");
                    target.classList.add("disabled")
                }).catch(function (err) {
                    const errors = err.response.data.errors;
                    vue.errors = {...vue.errors, [available_record_type.name]: errors.text_representation?.[0] ||
                    errors.record_data[0]};
                })
            },
            checkHideModal(e, recordModal) {
                return _checkHideModal(this, e, recordModal);
            }
        }
    });
</script>
