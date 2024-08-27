<script src="https://cdnjs.cloudflare.com/ajax/libs/wavesurfer.js/7.1.1/wavesurfer.min.js"
    integrity="sha512-Nf72Rl/+eNHZciDr/qPXIZm9ivm191py0rgawBjInPa09QXKa6uG2bLmFLk/eChGbOmwADPavFrk5ZoJH8Xygw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('js/admin/volume-meter.js')}}"></script>
<script src="{{asset('js/admin/meter-running.js')}}"></script>
<script src="{{asset('plugins/web-audio-recorder/lib-minified/WebAudioRecorder.min.js')}}"></script>
<script>
    function activate(parent, selector){
        parent.find($('.indicators')).addClass('d-none');
        parent.find($(selector)).removeClass('d-none');
    }
    function selector(e){
        return e
            .parents()
            .map(function() { return this.tagName; })
            .get()
            .reverse()
            .concat([this.nodeName])
            .join(">");
    }
    $('.record-row').each(function (){
        let current_row = $(this);
        let wave_form_selector = $(this).find($('.waveform'));
        let player_pause = $(this).find($('.player-pause'));
        let player_resume = $(this).find($('.player-resume'));
        let player_stop = $(this).find($('.player-stop'));
        let record_upload = $(this).find($('.record-upload'));
        let record_input = $(this).find($('.record-input'));
        let recorded_input = $(this).find($('.recorded-input'));
        let name = recorded_input.attr('name');
        let meter_id = 'meter-'+name;
        let wavesurfer = WaveSurfer.create({
            container: wave_form_selector[0],
            waveColor: 'white',
            progressColor: 'orange'
        });
        wavesurfer.on('ready', function() {
            player_pause.addClass('d-none');
            player_resume.removeClass('d-none');
            player_stop.removeClass('d-none');
        });
        wavesurfer.on('finish', function() {
            player_pause.addClass('d-none');
            player_resume.removeClass('d-none');
            player_stop.removeClass('d-none');
        });

        player_resume.on('click', function () {
            event.preventDefault();
            wavesurfer.play();
            player_resume.addClass('d-none');
            player_pause.removeClass('d-none');
        });
        player_pause.on('click', function () {
            event.preventDefault();
            wavesurfer.pause();
            player_pause.addClass('d-none');
            player_resume.removeClass('d-none');
        });
        player_stop.on('click', function () {
            event.preventDefault();
            wavesurfer.stop();
            player_pause.addClass('d-none');
            player_resume.removeClass('d-none');
        });
        record_upload.on('click',function () {
            event.preventDefault();
            if(wavesurfer.isPlaying()){
                wavesurfer.stop();
            }
            record_input.click();
        });
        record_input.on('change', function () {
            activate(current_row, '.indicators.player');
            const audio = $(this)[0].files[0];
            wavesurfer.loadBlob(audio);
            let reader = new FileReader();
            reader.readAsDataURL(audio);
            reader.onloadend = function() {
                let base64data = reader.result;
                recorded_input.val(base64data);
            }
        });


        let recorder_data = {};
        recorder_data.state = 'idle';
        recorder_data.stop_event = new Event('stop-'+name);
        recorder_data.record_mic = current_row.find($('.record-mic'));
        recorder_data.record_upload = current_row.find($('.record-upload'));
        recorder_data.recorder_stop = current_row.find($('.recorder-stop'));
        recorder_data.recorder_controls = current_row.find($('.recorder-controls'));
        recorder_data.meter = new Meter(meter_id);
        const setRecorderState = function (new_state){
            recorder_data.state = new_state;
            switch (new_state){
                case 'recording':
                    recorder_data.meter.startMeter();
                    recorder_data.record_mic.prop('disabled', true);
                    recorder_data.record_upload.prop('disabled', true);
                    activate(current_row, '.indicators.visualizer');
                    recorder_data.recorder_stop.removeClass('d-none');
                    recorder_data.recorder_controls.prop('disabled', false)
                    break;
                case 'done':
                    document.dispatchEvent(recorder_data.stop_event);
                    break;
                case 'idle':
                    activate(current_row, '.indicators.player');
                    recorder_data.record_mic.prop('disabled', false);
                    recorder_data.record_upload.prop('disabled', false);
                    break;
            }
        }
        document.addEventListener('stop-'+name, function (e) {
            wavesurfer.stop();
            recorder_data.mediaRecorder.finishRecording();
            recorder_data.meter.stopMeter(meter_id)
        }, false);
        const handleSuccess = function(stream) {
			setRecorderState('recording');
            var audioContext = new AudioContext;
            var mediaStreamSource = audioContext.createMediaStreamSource( stream );
            recorder_data.mediaRecorder = new WebAudioRecorder(mediaStreamSource, {
				encoding: "mp3",
                encodeAfterRecord: true,
                workerDir: "{{asset('plugins/web-audio-recorder/lib-minified/')}}/",
            });
            recorder_data.mediaRecorder.onComplete = function(rec, blob) {
				setRecorderState('idle');
                wavesurfer.loadBlob(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
					var base64data = reader.result;
                    recorded_input.val(base64data);
                }
            };
            recorder_data.mediaRecorder.setOptions({
				encodeAfterRecord: true,
				ogg: {
					quality: 0.5
				},
				mp3: {
					bitRate: 160
				}
			});
            recorder_data.mediaRecorder.startRecording();
        };
        recorder_data.record_mic.on('click', function () {
            event.preventDefault();
            if(wavesurfer.isPlaying()){
                wavesurfer.stop();
            }
            if(recorder_data.state === 'idle'){
                navigator.mediaDevices.getUserMedia({ audio: true, video: false })
                    .then(handleSuccess).catch(function (error){console.log(error)});
            }
        });
        recorder_data.recorder_stop.on('click', function () {
            event.preventDefault();
            setRecorderState('done');
        });
        const prev = wave_form_selector.attr('data-value');
        if(prev){
            activate(current_row, '.indicators.player');
            wavesurfer.load(prev);
        }
        //end each
    });
</script>
