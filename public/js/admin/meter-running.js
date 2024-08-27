class Meter {
    constructor(meter_id){
        this.audioContext=null;
        this.meter=null;
        this.canvasContext=null;
        this.WIDTH=500;
        this.HEIGHT=50;
        this.rafID=null;
        this.meter_id = meter_id;
        this.mediaStreamSource = null;
    }
    startMeter() {
        // grab our canvas
        this.canvasContext = document.getElementById(this.meter_id).getContext("2d");
        // monkeypatch Web Audio
        window.AudioContext = window.AudioContext || window.webkitAudioContext;

        // grab an audio context
        this.audioContext = new AudioContext();

        // Attempt to get audio input
        try {
            // monkeypatch getUserMedia
            navigator.getUserMedia =
                navigator.getUserMedia ||
                navigator.webkitGetUserMedia ||
                navigator.mozGetUserMedia;

            // ask for an audio input
            navigator.getUserMedia(
                {
                    "audio": {
                        "mandatory": {
                            "googEchoCancellation": "false",
                            "googAutoGainControl": "false",
                            "googNoiseSuppression": "false",
                            "googHighpassFilter": "false"
                        },
                        "optional": []
                    },
                }, this.gotStream.bind(this), this.didntGetStream.bind(this));
        } catch (e) {
            console.log('getUserMedia threw exception :' + e);
        }

    }
    stopMeter() {
        if (this.meter) {
            this.meter.shutdown();
        }
    }
    didntGetStream() {
        console.log('Stream generation failed.');
    }

    gotStream(stream) {
        // Create an AudioNode from the stream.
        this.mediaStreamSource = this.audioContext.createMediaStreamSource(stream);

        // Create a new volume meter and connect it.
        this.meter = createAudioMeter(this.audioContext);
        this.mediaStreamSource.connect(this.meter);

        // kick off the visual updating
        this.drawLoop();
    }
    drawLoop(time) {
        // clear the background
        this.canvasContext.clearRect(0, 0, this.WIDTH, this.HEIGHT);

        // check if we're currently clipping
        if (this.meter.checkClipping())
            this.canvasContext.fillStyle = "red";
        else
            this.canvasContext.fillStyle = "green";

        // draw a bar based on the current volume
        this.canvasContext.fillRect(0, 0, this.meter.volume * this.WIDTH * 1.4, this.HEIGHT);

        // set up the next visual callback
        this.rafID = window.requestAnimationFrame(this.drawLoop.bind(this));
    }
}
