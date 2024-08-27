<script>
    const _recordTime = (recordTime) => 
        `${Math.floor(recordTime / 60).toString().padStart(2, 0)}:${Math.floor(recordTime % 60).toString().padStart(2, 0)}`

    const _recordDuration = (duration) =>
        `${Math.floor(duration / 60).toString().padStart(2, 0)}:${Math.floor(duration % 60).toString().padStart(2, 0)}`

    const _dataURIToBlob = (dataURI) => {
			var byteString = atob(dataURI.split(',')[1]);
			var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]
			var ab = new ArrayBuffer(byteString.length);
			var ia = new Uint8Array(ab);
			for (var i = 0; i < byteString.length; i++) {
				ia[i] = byteString.charCodeAt(i);
			}
			var blob = new Blob([ab], {type: mimeString});
			return blob;
    }

    const _openRecordModal = (vue, available_record_type) => {
		vue.active_item.record_type = available_record_type;
        vue.sweet_alert_shown = false;
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
				$("#record-modal").modal('show');
				let data = vue.active_item.record_type.record.recorded_voice || vue.active_item.record_type.record.record_data;
                if (data) {
					vue.active = 'player';
					const audio = vue.dataURItoBlob(vue.active_item.record_type.record.recorded_voice || vue.active_item.record_type.record.record_data);
					requestAnimationFrame(function () {
						requestAnimationFrame(function () {
							document.getElementById("loading").classList.add("d-flex");
							document.getElementById("loading").classList.remove("d-none");
							vue.initializeWaveSurfer();
							setTimeout(() => {
								vue.wavesurfer.loadBlob(audio);
							}, 200)
						});
					});
				}
				else if (vue.active_item.record_type.record.full_url){
					vue.active = 'player';
					requestAnimationFrame(function () {
						requestAnimationFrame(function () {
							document.getElementById("loading").classList.add("d-flex");
							document.getElementById("loading").classList.remove("d-none");
							vue.initializeWaveSurfer();
							requestAnimationFrame(function () {
								requestAnimationFrame(function () {
									setTimeout(() => {
										vue.wavesurfer.load(vue.active_item.record_type.record.full_url);
									}, 200)
								});
							});
						});
					});
				}
				else {
					vue.active = null;
				}
            });
        });
    }

    const _closeModal = (event, vue) => {
        if(vue.active !== "player") {
            event.preventDefault();
            return;
        }
        if(vue.active_item.recorded_voice)
            vue.active_item.record_type.record.recorded_voice = vue.active_item.recorded_voice;
        vue.active_item.recorded_voice = null
        clearInterval(timer);
        vue.recordTime = 0;
        $("#record-modal").modal('hide');
    }

    const _initializeWaveSurfer = (vue, timer) => {
        if (!vue.wavesurfer){
            vue.wavesurfer = WaveSurfer.create({
                container: document.querySelector('.player #waveform'),
                waveColor: '#d0d2d3',
                progressColor: '#63caf7',
                fillParent: true
            });
            vue.wavesurfer.on('ready', function() {
                document.getElementById("loading").classList.remove("d-flex");
                document.getElementById("loading").classList.add("d-none");
                vue.playing_state = 'ready';
            });
            vue.wavesurfer.on('finish', function() {
                vue.playing_state = 'ready';
                clearInterval(timer);
                vue.recordTime = 0;
            });
            vue.wavesurfer.on('error', function(err) {
                console.log(err)
            });
        }
        else {
            vue.stopWaveSurfer();
            vue.wavesurfer.destroy();
            vue.wavesurfer = null;
            vue.initializeWaveSurfer();
        }
    }

    const _playWaveSurfer = (vue, timer) => {
        if(vue.wavesurfer){
            vue.wavesurfer.play();
            vue.playing_state = 'playing';
            timer = setInterval(() => vue.recordTime++, 1000);
        }
    }

    const _pauseWaveSurfer = (vue, timer) => {
        if(vue.wavesurfer) {
            vue.wavesurfer.pause();
            vue.playing_state = 'paused';
            clearInterval(timer);
        }
    }

    const _stopWaveSurfer = (vue, timer) => {
        if(vue.wavesurfer) {
            vue.wavesurfer.stop();
            vue.playing_state = 'ready'
            clearInterval(timer);
            vue.recordTime = 0;
        }
    }

    const _voiceUpload = (vue) => {
        vue.stopWaveSurfer();
        document.getElementById("done-btn").classList.remove('disabled');
        document.getElementById('record-input').click();
    }

    const _stopRecording = (vue, timer) => {
        document.getElementById("record-button").classList.remove('disabled');
        document.getElementById("record-button").classList.remove('active');
        document.getElementById("upload-button").classList.remove('disabled');
        if (!vue.mediaRecorder || !vue.wavesurfer) return;
        document.getElementById("loading").classList.add("d-flex");
        document.getElementById("loading").classList.remove("d-none");
        vue.mediaRecorder.finishRecording();
        vue.mediaRecorder = null;
        vue.wavesurfer.microphone.stop();
        clearInterval(timer);
    }

    const _changeVoice = (vue, event) => {
        const el = event.currentTarget;
        vue.active = 'player';
        const audio = el.files[0];
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                vue.initializeWaveSurfer();
                document.getElementById("loading").classList.add("d-flex");
                document.getElementById("loading").classList.remove("d-none");
                vue.wavesurfer.loadBlob(audio);
            });
        });
        let reader = new FileReader();
        reader.readAsDataURL(audio);
        reader.onloadend = function() {
            vue.active_item.recorded_voice = reader.result;
        }
    }

    const _checkHideModal = (vue, e, recordModal) => {
        if(vue.sweet_alert_shown) return;
        if(vue.active_item.recorded_voice) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "Your record didn't saved yet, are you sure you want to close modal?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "secondary-btn",
                cancelButtonClass: "primary-btn",
                confirmButtonText: "Close",
                cancelButtonText: "Stay",
                closeOnConfirm: true
            },
            function(isConfirm) {
                if(isConfirm) {
                    vue.sweet_alert_shown = true;
                    recordModal.modal("hide");
                    vue.active_item = {
                        'record' : null,
                        'record_type': null,
                        'recorded_voice': null
                    };
                    clearInterval(timer);
                    vue.recordTime = 0;
                }
            });
        }
    }

    const _voiceRecord = (vue, event) => {
        vue.active = 'recorder';
        if(vue.recording_state === "recording") {
            event.preventDefault();
            return;
        }
        var constraints = {
            audio: true,
            video: false
        }
        document.getElementById("record-button").classList.add('disabled');
        document.getElementById("upload-button").classList.add('disabled');
        document.getElementById("record-button").classList.add('active');
        vue.stopWaveSurfer();
        requestAnimationFrame(function () {
            if( vue.recording_state === 'idle' && vue.active === 'recorder'){
                const wavesurfer = WaveSurfer.create({
                    container: document.querySelector('#mic-container #waveform'),
                    waveColor: '#63caf7',
                    interact: false,
                    cursorWidth: 0,
                    plugins: [
                        WaveSurfer.microphone.create()
                    ]
                });

                vue.wavesurfer = wavesurfer;
                var audioContext = window.AudioContext ? new window.AudioContext : new window.webkitAudioContext;
                if (!audioContext) {
                    swal({
                        title: "Sorry!!",
                        text: "Your browers doesn't support Web Audio API, Please upgrade to the latest version or use 'Google Chrome' browser.",
                        type: "warning",
                        confirmButtonClass: "primary-btn",
                        confirmButtonText: "Close",
                        closeOnConfirm: true
                    })
                }
                navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
                    var mediaStreamSource = audioContext.createMediaStreamSource( stream );
                    vue.mediaRecorder = new WebAudioRecorder(mediaStreamSource, {
                        encoding: "mp3",
                        encodeAfterRecord: true,
                        workerDir: "{{asset('plugins/web-audio-recorder/lib-minified/')}}/",
                        timeLimit: 10
                    })
                    vue.mediaRecorder.onComplete = function(rec, blob) {
                        document.getElementById("loading").classList.add("d-none");
                        document.getElementById("loading").classList.remove("d-flex");
                        vue.recording_state = 'idle'
                        vue.active = 'player';
                        requestAnimationFrame(function () {
                            requestAnimationFrame(function () {
                                vue.initializeWaveSurfer();
                                vue.wavesurfer.loadBlob(blob);
                            });
                        });
                        var reader = new FileReader();
                        reader.readAsDataURL(blob);
                        reader.onloadend = function() {
                            vue.active_item.recorded_voice = reader.result;
                        }
                    };
                    vue.mediaRecorder.setOptions({
                        encodeAfterRecord: true,
                        ogg: {
                            quality: 0.5
                        },
                        mp3: {
                            bitRate: 160
                        }
                    });
                    vue.handleSuccess();
                    vue.mediaRecorder.startRecording();
                }).catch(function(err) { 
                    swal({
                        title: err.name,
                        text: err.message,
                        type: "warning",
                        confirmButtonClass: "primary-btn",
                        confirmButtonText: "Close",
                        closeOnConfirm: true
                    })
                });
                vue.wavesurfer.microphone.on('deviceError', function(code) {
                    swal({
                        title: "Device error",
                        text: `Code ${code}`,
                        type: "warning",
                        confirmButtonClass: "primary-btn",
                        confirmButtonText: "Close",
                        closeOnConfirm: true
                    })
                });

            }
        })
    }

    const _handleSuccess = (vue) => {
        vue.recording_state = 'recording';
        vue.wavesurfer.microphone.start();
        vue.recordTime = 0;
        timer = setInterval(() => {
            if(vue.recordTime === 9) {
                vue.recordTime = 0;
                vue.stopRecording();
                return;
            }
            vue.recordTime++
        }, 1000);
    }
</script>