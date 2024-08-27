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
    const profile_image_copper = {
        cropper: null,
        autoCropArea: 0.8,
        cropping_data: {},
        input: 'image_cropping',
        preview: '.profile-preview-div'
    };
    let cropper;
    let modalShowed = false;
    var vm = new Vue({
        el: "#vue-app",
        delimiters: ['[[', ']]'],
        data: {
            'personal_records': null,
            'active_item': {
                'record_type': null,
                'recorded_voice': null
            },
            'wavesurfer': null,
            'playing_state': null,
            'recording_state': 'idle',
            'active': null,
            'mediaRecorder': null,
            'animation': null,
            'recordTime': 0,
            'sweet_alert_shown': false,
            'is_complete': false,
            'email': "",
            'image': null,
            'terms': false,
            'image_txt': "No file chosen",
            'image_cropping': "",
            'video': null,
            'video_txt': "No file chosen"
        },
        mounted: function() {
            this.loadRecords();
            $("#profile-pic-chooser").on('shown.bs.modal', this.handleOpenCropModal);
            $("#profile-pic-chooser").on('hidden.bs.modal', this.handleCloseCropModal);
        },
        computed: {
            recordedTime: function() {
                return _recordTime(this.recordTime);
            },
            recordDuration: function() {
                return this.wavesurfer ? _recordDuration(this.wavesurfer.getDuration() || 0) : null;
            },
        },
        watch: {
            terms: function() {
                if (this.terms) {
                    this.addValidFeedback(".terms-check")
                    this.checkFinish();
                }
            },
            email: function() {
                this.checkFinish();
            },
            image: function() {
                this.checkFinish();
            },
            video: function() {
                this.checkFinish();
            },
            personal_records: {
                handler: function() {
                    this.checkFinish();
                },
                deep: true
            }
        },
        methods: {
            loadRecords() {
                let vue = this;
                axios.get("{{route('api.trying.recordTypes')}}")
                    .then(function(response) {
                        if (response && response.status === 200) {
                            vue.personal_records = response.data.personal
                                .filter(item => item.required)
                                .map(item => ({
                                    ...item,
                                    record: {
                                        text_representation: "",
                                        original_text: "",
                                        record_data: null,
                                        meaning: null,
                                    }
                                }));
                        }
                    }).catch(function(err) {
                        console.log(err);
                    });
            },
            openRecordModal(available_record_type) {
                return _openRecordModal(this, available_record_type);
            },
            voiceRecord(event) {
                return _voiceRecord(this, event);
            },
            stopWaveSurfer() {
                return _stopWaveSurfer(this, timer);
            },
            handleSuccess() {
                return _handleSuccess(this);
            },
            stopRecording() {
                return _stopRecording(this, timer);
            },
            initializeWaveSurfer() {
                return _initializeWaveSurfer(this, timer);
            },
            playWaveSurfer() {
                return _playWaveSurfer(this, timer);
            },
            pauseWaveSurfer() {
                return _pauseWaveSurfer(this, timer);
            },
            voiceUpload() {
                return _voiceUpload(this);
            },
            changeVoice(event) {
                return _changeVoice(this, event);
            },
            checkHideModal(e, recordModal) {
                return _checkHideModal(this, e, recordModal);
            },
            dataURItoBlob(dataURI) {
                return _dataURIToBlob(dataURI);
            },
            addValidFeedback(target) {
                document.querySelector(`${target} input`).classList.remove("is-invalid");
                document.querySelector(`${target} input`).classList.add("is-valid");
                document.querySelector(`${target} ~ .feedback`).classList.remove("invalid-feedback");
                document.querySelector(`${target} ~ .feedback`).classList.add("valid-feedback");
                document.querySelector(`${target} ~ .feedback`).textContent = "";
            },
            addInvalidFeedback(target, errText) {
                document.querySelector(`${target}`).classList.remove("is-valid");
                document.querySelector(`${target}`).classList.add("is-invalid");
                document.querySelector(`${target} input`).classList.remove("is-valid");
                document.querySelector(`${target} input`).classList.add("is-invalid");
                document.querySelector(`${target} ~ .feedback`).classList.remove("valid-feedback");
                document.querySelector(`${target} ~ .feedback`).classList.add("invalid-feedback");
                document.querySelector(`${target} ~ .feedback`).textContent = errText;
            },
            closeModal(event) {
                const vue = this;
                event.preventDefault();
                if (vue.active !== "player") {
                    return;
                }
                const recordName = vue.active_item.record_type.name;
                setTimeout(() => {
                    vue.checkFinish();
                }, 0);
                _closeModal(event, vue);
            },
            togglePopover(email, first, second, forth, fifth, recordName) {
                // Null => no change ----- True => show ---- False => hide
                $(function() {
                    if (email !== null)
                        $(`[data-toggle="email-popover"]`).popover(email ? "show" : "hide");
                    if (first !== null)
                        if (recordName)
                            $(`[data-toggle="first-popover"][data-name="${recordName}"]`).popover(first ? "show" : "hide");
                        else
                            $(`[data-toggle="first-popover"]`).popover(first ? "show" : "hide");
                    if (second !== null)
                        if (recordName)
                            $(`[data-toggle="second-popover"][data-name="${recordName}"]`).popover(second ? "show" : "hide");
                        else
                            $(`[data-toggle="second-popover"]`).popover(second ? "show" : "hide");
                    if (forth !== null)
                        $(`[data-toggle="forth-popover"]`).popover(forth ? "show" : "hide");
                    if (fifth !== null)
                        $(`[data-toggle="fifth-popover"]`).popover(fifth ? "show" : "hide");
                })
            },
            checkFinish() {
                const vue = this;
                if (vue.email && vue.personal_records[0].record.recorded_voice && vue.personal_records[1].record.recorded_voice) {
                    if (vue.terms) {
                        this.is_complete = true;
                    }
                }
            },
            restoreDefaults() {
                const vue = this;
                vue.personal_records = vue.personal_records.map(rec => ({
                    ...rec,
                    record: {
                        text_representation: "",
                        original_text: "",
                        record_data: null,
                        meaning: "",
                        recorded_voice: ""
                    }
                }))
                vue.email = "";
                vue.image = null;
                document.querySelector(".image-input input").value = "";
                document.querySelectorAll("input").forEach(inp => {
                    inp.classList.remove("is-invalid");
                    inp.classList.remove("is-valid");
                });
                document.querySelectorAll(".feedback").forEach(feedback => {
                    feedback.classList.remove("invalid-feedback");
                    feedback.classList.remove("valid-feedback");
                })
                $(`${profile_image_copper.preview} img`).attr("src", "{{asset('images/default-user.png')}}");
                $(`.video-input-file`).attr("val", "");
            },
            handleImageChange(e) {
                const vue = this;
                const input = e.target;
                vue.image = input.files[0] || vue.image;
                vue.image_txt = input.files[0]?.name || vue.image_txt;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(".profile-pic-chooser-image").attr("src", e.target.result);
                        $("#profile-pic-chooser").modal("show")
                    };
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
                $(this).prev($('.crop-preview')).first().attr('onclick', "$('#profile-pic-chooser').modal('show')")
            },
            handleVideoChange(e) {
                const input = e.target;
                if(input.files && input.files[0]) {
                    this.video = input.files[0] || vue.video;
                    this.video_txt = input.files[0]?.name || vue.video_txt;
                    if(input.files[0].size > 10300000)
                        this.addInvalidFeedback(".video-input .record-card",
                        "{{ __('global.video_size_limit') }}");
                    else if(!input.files[0].type.startsWith("video"))
                        this.addInvalidFeedback(".video-input .record-card",
                        "{{ __('global.video_type_validation') }}");
                    else
                        this.addValidFeedback(".video-input .record-card");
                }
            },
            viewProfile() {
                const vue = this;
                event.preventDefault();
                if (!vue.terms) {
                    vue.addInvalidFeedback(".terms-check", "{{__('global.check_validation')}}");
                    return;
                } 
                if(vue.video && vue.video.size > 10300000) {
                    this.addInvalidFeedback(".video-input .record-card", "{{ __('global.video_size_limit') }}")
                    return;
                }
                if(vue.video && !vue.video.type.startsWith("video")) {
                    this.addInvalidFeedback(".video-input .record-card", "{{ __('global.video_type_validation') }}")
                    return;
                }

                vue.is_complete = false;
                document.querySelector(".online-rcrd-buttons a").innerHTML = `<span class="spinner-border text-light" style="width: 16px; height: 16px" role="status"></span> {{__('global.go_ahead')}}`

                const form = new FormData();

                form.append("email", vue.email);
                form.append("image_cropping", vue.image_cropping);
                form.append("account_type", "personal");
                if(vue.video)
                    form.append("video", vue.video);
                form.append("first_name", vue.personal_records[0].record.text_representation);
                if (vue.personal_records[0].record.recorded_voice)
                    form.append("first_name_file", this.dataURItoBlob(vue.personal_records[0].record.recorded_voice));
                form.append("last_name", vue.personal_records[1].record.text_representation);
                if (vue.personal_records[1].record.recorded_voice)
                    form.append("last_name_file", this.dataURItoBlob(vue.personal_records[1].record.recorded_voice));

                const last_segment = window.location.pathname.split('/').pop();

                form.append("username", last_segment);
                console.log(form);

                axios.post("{{route('api.tryingUser')}}", form, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(function(response) {
                        swal({
                            title: "Done",
                            text: response.data.message,
                            type: "info",
                            showCancelButton: false,
                            confirmButtonClass: "primary-btn",
                            confirmButtonText: "Close",
                            closeOnConfirm: true
                        })
                        vue.restoreDefaults();
                    }).catch(function(err) {
                        const errors = err.response.data.errors;
                        vue.is_complete = true;
                        if (errors.email)
                            vue.addInvalidFeedback(".email-input", errors.email[0]);
                        if (errors.image)
                            vue.addInvalidFeedback(".image-input", errors.image[0]);
                        if (errors.first_name || errors.first_name_file || errors.first_name_meaning)
                            vue.addInvalidFeedback(".First-Name", errors.first_name[0] || errors.first_name_file[0] || errors.first_name_meaning[0]);
                        if (errors.last_name || errors.last_name_file || errors.last_name_meaning)
                            vue.addInvalidFeedback(".Last-Name", errors.last_name || errors.last_name_file || errors.last_name_meaning);
                        if(errors.video)
                            vue.addInvalidFeedback(".video-input .record-card", errors.video);
                    }).finally(function() {
                        document.querySelector(".online-rcrd-buttons a").innerHTML = `{{__('global.go_ahead')}}`
                    })
            },
            handleOpenCropModal() {
                modalShowed = true;
                const vue = this;
                const img = document.querySelector(".profile-pic-chooser-image")
                if (cropper) {
                    cropper.replace(img.src)
                    return;
                }
                cropper = new Cropper(img, {
                    autoCropArea: profile_image_copper.autoCropArea,
                    data: profile_image_copper.cropping_data,
                    aspectRatio: 1 / 1,
                    minCropBoxWidth:200,
                    cropBoxResizable: false,
                    crop(event) {
                        if (modalShowed) {
                            profile_image_copper.cropping_data = event.detail;
                            const base64URL = cropper.getCroppedCanvas().toDataURL();
                            $('input:hidden[name=image_cropping]').val(base64URL)
                            vue.image_cropping = base64URL
                        }
                    }
                })
            },
            handleCloseCropModal() {
                modalShowed = false;
            },
            doneCrop() {
                let vue = this;
                if (cropper) {
                    let url;
                    cropper.getCroppedCanvas().toBlob(blob => {
                        vue.image = blob;
                        url = URL.createObjectURL(blob)
                        $(`${profile_image_copper.preview} img`).attr("src", url)
                    });
                }
            },
            hoverSubmit() {
                const vue = this;
                if (vue.is_complete) return;
                const [firstNameRecord, lastNameRecord] = vue.personal_records;
                if (!firstNameRecord.record.text_representation)
                    this.togglePopover(null, true, false, false, false, firstNameRecord.name);
                else if (!firstNameRecord.record.recorded_voice)
                    this.togglePopover(null, false, true, false, false, firstNameRecord.name);
                if (!lastNameRecord.record.text_representation)
                    this.togglePopover(null, true, false, false, false, lastNameRecord.name);
                else if (!lastNameRecord.record.recorded_voice)
                    this.togglePopover(null, false, true, false, false, lastNameRecord.name);
                if (firstNameRecord.record.text_representation && firstNameRecord.record.recorded_voice && lastNameRecord.record.text_representation && lastNameRecord.record.recorded_voice && !vue.terms)
                    this.togglePopover(null, false, false, true, false);
                if (!vue.email)
                    this.togglePopover(true, null, null, false, false);
            },
            blurSubmit() {
                this.togglePopover(false, false, false, false, false);
            }
        }
    });

    const recordModal = $("#record-modal");
    recordModal.on("hide.bs.modal", function(e) {
        vm.checkHideModal(e, recordModal);
    })
</script>