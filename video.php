<?php
require_once __DIR__.'/inc/boot.php';
// require_once __DIR__.'/template/header.php';
if (isset($_GET['id']))
$id = $_GET['id'];
else
$id = 6666;
$link = "https://".$_SERVER['HTTP_HOST']."/score.php?id=".$id."&video=true&view=";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>

    <title>Трансляция игры</title>
    
    <!-- Font Awesome -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"/> -->
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.0/mdb.min.css" rel="stylesheet"/>
    <!-- highlightjs -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlight.js@11.3.1/styles/github.css"> -->
    
    <style>
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(0, 0, 0, 0.55)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E") !important;
        }

        .playerWrapper {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%;
            background-color: #000;
        }

        .previewPlayer {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        fieldset {
            padding-left: 0.5rem;
            padding-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .list-group-item {
            padding: 0.5rem 1rem;
        }

        .list-group-item h6 {
            margin-top: 0.5rem;
        }

        footer {
            font-size: 0.85rem;
        }

    </style>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script>
        if (window.location.href.indexOf('localhost') < 0 && window.location.protocol !== 'https:') {
            location.href = location.href.replace('http://', 'https://');
        }
    </script>


</head>
<?php
require_once __DIR__.'/template/menu.php';

if ($_SESSION['user_role'] < 1) {
?>
<body>
<div class="container-lg mt-4">
<h1 class="text-center">У ВАС НЕ ХВАТАЕТ ПРАВ ДЛЯ ТРАНСЛЯЦИИ</h1>
</div>
<?php
} else {
?>
<body>
<div class="container-lg mt-4">
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="input-group g-3 mb-3">    
                <div class="form-outline flex-grow-1">
                    <!—- текстовое поле -->
                    <textarea id="phoneLink" type="text" class="form-control bg-white" readonly>Для телефона: <?php echo ($link);?>phone
Для компьютера: <?php echo ($link);?>desktop</textarea>
                    <label class="form-label" for="phoneLink">Ссылки для соцсетей</label>
                </div>
                <!-- кнопка -->
                <button id="copyPhoneLink" class="btn btn-primary px-2 px-md-4" type="button">Копировать</button>
            </div>
            <!-- <div class="input-group">
                <div class="form-outline flex-grow-1">
                    <input type="text" id="webRtcUrlInput" class="form-control bg-white" value="wss://video.darts28.ru:3334/vidme/<?php echo $id; ?>?direction=send&transport=tcp" />
                    <label class="form-label" for="webRtcUrlInput">WebRTC input URL</label>
                </div>
            </div> -->
            <button id="streamingButton" class="btn btn-primary px-2 px-md-4" type="button">
                    Start
            </button>
        </div>

        <div class="col-lg-6">
            <div class="playerWrapper rounded overflow-hidden">
                <video controls id="previewPlayer" class="previewPlayer"></video>
            </div>
        </div>
        <div class="col-lg-6" style="display: none;>
            <ul class="list-group">
                <li class="list-group-item bg-light hstack">
                    <h5 class="mb-0 fs-6">Информаия о трансляции</h5>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            <h6>Видео</h6>
                            <fieldset>
                                <div class="mb-2">
                                    <span>Разрешение
                                        <span class="badge badge-pill badge-info mr-1" data-mdb-toggle="tooltip" title="This may be different from the resolution you set. Because the browser will get the ideal resolution from the input device.">i</span>:
                                    </span>
                                    <span id="videoResolutionSpan" class="mr-2 align-middle">-</span>
                                </div>
                                <div class="mb-2">
                                    <span>Framerate: </span>
                                    <span id="videoFrameRateSpan" class="mr-2">-</span>
                                </div>
                                <div class="mb-2">
                                    <span>Bitrate: </span>
                                    <span id="bitrateSpan" class="mr-2">-</span>
                                </div>

                            </fieldset>

                            <h6>Подключение</h6>
                            <fieldset>
                                <div class="mb-2">
                                    <span>ICE state: </span>
                                    <span id="iceStateSpan" class="mr-2">-</span>
                                </div>
                            </fieldset>

                            <div class="col-12">
                                <small id="errorText" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <div id="testPlaybackButtonArea" class="mt-2" style="display: none;">
                <button id="playStreamButton" class="btn btn-info w-100">Play stream with test player</button>
            </div>
        </div>

        <div class="col-12">
            <ul class="list-group">
                <li class="list-group-item bg-light">
                    <h5 class="mb-0 fs-6">Input stream settings</h5>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-lg-6 border-start border-2 mb-3 mb-lg-0">
                            <h6>Video settings</h6>
                            <fieldset>
                                <label for="videoSourceSelect">Device</label>
                                <select name="" id="videoSourceSelect" class="form-control constraintSelect mb-2">
                                </select>

                                <label for="videoResolutionSelect">Resolution</label>
                                <select id="videoResolutionSelect" class="form-control constraintSelect mb-2">
                                    <option selected value="">Not Set</option>
                                    <option value="fhd">Full HD (1920x1080)</option>
                                    <option value="hd">HD (1280x720)</option>
                                    <option value="vga">VGA (640x480)</option>
                                </select>

                                <label for="videoBitrateInput">Bitrate limit (kbps)</label>
                                <input id="videoBitrateInput" type="number" class="form-control constraintSelect mb-2" placeholder="Unlimited">

                                <label for="videoFrameInput">Frame rate</label>
                                <input id="videoFrameInput" type="number" class="form-control constraintSelect" placeholder="Not Set">
                            </fieldset>
                        </div>
                        <div class="col-lg-6 border-start border-2 mb-3 mb-lg-0">
                            <h6>Audio settings</h6>
                            <fieldset>
                                <div id="audioSourceSelectArea">
                                    <label for="audioSourceSelect">Device</label>
                                    <select class="form-control constraintSelect" id="audioSourceSelect">
                                        <option selected>Choose...</option>
                                    </select>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                </li>
            </ul>
        </div>
    </div>

</div>
<?php
}
?>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.0/mdb.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/underscore@1.12.0/underscore-min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/ovenlivekit/dist/OvenLiveKit.min.js"></script>

<script>
// копируем ссылки на игру одной кнопкой
var phoneLink = document.getElementById("phoneLink");
var phoneLinkBtn = document.getElementById("copyPhoneLink");
phoneLinkBtn.onclick = function() {
  phoneLink.select();    
  document.execCommand("copy");
}

</script>


<script>
    let gameVideoState = false;
    let gameId = <?php echo $id;?>;
    let allDevices = null;
    let input = null;

    const userResolutions = {
        vga: {
            // width: {exact: 640}, height: {exact: 480}
            // width: { min: 0, ideal: 640}, height: { min: 0, ideal: 480 }
            width: {ideal: 640}, height: {ideal: 480}
        },
        hd: {
            // width: {exact: 1280}, height: {exact: 720}
            // width: { min: 640, ideal: 1280}, height: { min: 480, ideal: 720 }
            width: {ideal: 1280}, height: {ideal: 720}
        },
        fhd: {
            // width: {exact: 1920}, height: {exact: 1080}
            // width: { min: 1280, ideal: 1920}, height: { min: 720, ideal: 1080 }
            width: {ideal: 1920}, height: {ideal: 1080}
        }
    };

    const displayResolutions = {
        vga: {
            width: 640, height: 480
        },
        hd: {
            width: 1280, height: 720
        },
        fhd: {
            width: 1920, height: 1080
        }
    };

    let streamingStarted = false;

    let videoElement = document.getElementById('previewPlayer');

    let streamingButton = $('#streamingButton');

    let errorTextSpan = $('#errorText');

    // let webRtcUrlInput = $('#webRtcUrlInput');
    let webRtcUrlInput = 'wss://video.darts28.ru:3334/vidme/<?php echo $id; ?>?direction=send&transport=tcp';
    let videoSourceSelect = $('#videoSourceSelect');
    let videoResolutionSelect = $('#videoResolutionSelect');
    let videoBitrateInput = $('#videoBitrateInput');
    let videoFrameInput = $('#videoFrameInput');
    let audioSourceSelect = $('#audioSourceSelect');
    let audioSourceSelectArea = $('#audioSourceSelectArea');

    let videoResolutionSpan = $('#videoResolutionSpan');
    let videoFrameRateSpan = $('#videoFrameRateSpan');
    let iceStateSpan = $('#iceStateSpan');
    let bitrateSpan = $('#bitrateSpan');

    let testPlaybackButtonArea = $('#testPlaybackButtonArea');
    let playStreamButton = $('#playStreamButton');

    let savedWebRtcUrl = localStorage.getItem('savedWebRtcUrl');
    // let savedWebRtcUrl = 'wss://video.darts28.ru:3334/vidme/<?php echo $id; ?>?direction=send&transport=tcp';
    let savedWebTrcUrl = webRtcUrlInput;
    let savedVideoSource = localStorage.getItem('savedVideoSource');
    let savedVideoResolution = localStorage.getItem('savedVideoResolution');
    let savedVideoBitrate = localStorage.getItem('savedVideoBitrate');
    let savedVideoFrame = localStorage.getItem('savedVideoFrame');
    let savedAudioSource = localStorage.getItem('savedAudioSource');

    // if (savedWebRtcUrl) {
    //     webRtcUrlInput.val(savedWebRtcUrl);
    // }

    // webRtcUrlInput.on('change', function () {
    //     localStorage.setItem('savedWebRtcUrl', $(this).val());
    // });

    videoSourceSelect.on('change', function () {
        localStorage.setItem('savedVideoSource', $(this).val());

        if ($(this).val() === 'displayCapture') {
            audioSourceSelectArea.hide();
        } else {
            audioSourceSelectArea.show();
        }

        if ($(this).val() === 'no') {

            enableVideoContrll(false);
        } else {

            enableVideoContrll(true);
        }
    });

    videoResolutionSelect.on('change', function () {
        localStorage.setItem('savedVideoResolution', $(this).val());
    });

    videoBitrateInput.on('change', function () {
        localStorage.setItem('savedVideoBitrate', $(this).val());
    });

    videoFrameInput.on('change', function () {
        localStorage.setItem('savedVideoFrame', $(this).val());
    });

    audioSourceSelect.on('change', function () {
        localStorage.setItem('savedAudioSource', $(this).val());
    });

    $('.constraintSelect').on('change', function () {
        stopStreaming();
    });

    function enableVideoContrll(enable) {
        videoResolutionSelect.prop('disabled', !enable);
        videoBitrateInput.prop('disabled', !enable);
        videoFrameInput.prop('disabled', !enable);
    }


    let frameCalculatorTimer = null;
    let totalVideoFrames = 0;

    function getResolutionAndCalculateFrame(videoElement) {

        if (frameCalculatorTimer) {

            clearInterval(frameCalculatorTimer);
            videoResolutionSpan.text('-');
            videoFrameRateSpan.empty('-');
            frameCalculatorTimer = null;
            totalVideoFrames = 0;
        }

        frameCalculatorTimer = setInterval(function () {

            videoResolutionSpan.text(videoElement.videoWidth + 'x' + videoElement.videoHeight);

            if (totalVideoFrames === 0) {

                totalVideoFrames = videoElement.getVideoPlaybackQuality().totalVideoFrames;
            } else {

                let currentTotalFrame = videoElement.getVideoPlaybackQuality().totalVideoFrames;
                let frameRate = currentTotalFrame - totalVideoFrames;

                videoFrameRateSpan.text(frameRate + 'fps');

                totalVideoFrames = currentTotalFrame;
            }

        }, 1000);
    }

    function getUserConstraints() {

        let videoDeviceId = videoSourceSelect.val();
        let videoResolution = videoResolutionSelect.val();
        let videoFrame = videoFrameInput.val();
        let audioDeviceId = audioSourceSelect.val();

        let newConstraint = {};

        if (videoDeviceId !== 'no') {
            if (videoDeviceId) {
                newConstraint.video = {
                    deviceId: {
                        exact: videoDeviceId
                    }
                };
            }

            if (videoResolution) {

                const resolution = userResolutions[videoResolution];

                newConstraint.video.width = resolution.width;
                newConstraint.video.height = resolution.height;
            }

            if (videoFrame) {
                newConstraint.video.frameRate = {exact: parseInt(videoFrame)};
            }
        }

        if (audioDeviceId !== 'no') {
            if (audioDeviceId) {
                newConstraint.audio = {
                    deviceId: {
                        exact: audioDeviceId
                    }
                };
            }
        }

        return newConstraint;
    }

    function getDisplayConstraints() {

        let videoResolution = videoResolutionSelect.val();
        let videoFrame = videoFrameInput.val();

        let newConstraint = {};

        newConstraint.video = {};

        if (videoResolution) {

            const resolution = displayResolutions[videoResolution];

            newConstraint.video.width = resolution.width;
            newConstraint.video.height = resolution.height;
        } else {

            newConstraint.video = true;
        }

        if (videoFrame) {

            if (!newConstraint.video) {

                newConstraint.video = {};
            }

            newConstraint.video.frameRate = parseInt(videoFrame);
        }

        newConstraint.audio = true;

        return newConstraint;
    }

    function setDevice(type, select, devices) {

        select.empty();

        if (type === 'audio' && devices.length === 0) {

            select.append('<option value="">No Source Available</option>')
        } else {

            _.each(devices, function (device) {

                let option = $('<option></option>');

                option.text(device.label);
                option.val(device.deviceId);

                select.append(option);
            });
        }

        if (type === 'video') {

            let option = $('<option></option>');

            option.text('Without video');
            option.val('no');

            select.append(option);
        }

        if (type === 'audio') {

            let option = $('<option></option>');

            option.text('Without audio');
            option.val('no');

            select.append(option);
        }

        select.find('option').eq(0).prop('selected', true);
    }

    function checkDevice(devices, deviceId) {

        if (deviceId === 'displayCapture') {
            return true;
        }

        let filtered = _.filter(devices, function (device) {

            return device.deviceId === deviceId;
        });

        return filtered.length > 0;
    }

    function resetMessages() {

        errorTextSpan.empty();

        clearInterval(frameCalculatorTimer);
        frameCalculatorTimer = null;


        videoResolutionSpan.text('-');
        videoFrameRateSpan.text('-');
        bitrateSpan.text('-');
        iceStateSpan.text('-');

    }


    function createInput() {

        testPlaybackButtonArea.hide();
        streamingButton.prop('disabled', true);

        if (input) {
            input.remove();
            input = null;
        }

        resetMessages();

        input = OvenLiveKit.create({
            callbacks: {
                error: function (error) {

                    let errorMessage = '';

                    if (error.message) {

                        errorMessage = error.message;
                    } else if (error.name) {

                        errorMessage = error.name;
                    } else {

                        errorMessage = error.toString();
                    }

                    if (errorMessage === 'OverconstrainedError') {

                        errorMessage = 'The input device does not support the specified resolution or frame rate.';
                    }

                    resetMessages();

                    errorTextSpan.text(errorMessage);
                },
                connectionClosed: function (type, event) {
                    if (type === 'websocket') {
                        let reason;
                        // See http://tools.ietf.org/html/rfc6455#section-7.4.1
                        if (event.code === 1000)
                            reason = "Normal closure, meaning that the purpose for which the connection was established has been fulfilled.";
                        else if (event.code === 1001)
                            reason = "An endpoint is \"going away\", such as a server going down or a browser having navigated away from a page.";
                        else if (event.code === 1002)
                            reason = "An endpoint is terminating the connection due to a protocol error";
                        else if (event.code === 1003)
                            reason = "An endpoint is terminating the connection because it has received a type of data it cannot accept (e.g., an endpoint that understands only text data MAY send this if it receives a binary message).";
                        else if (event.code === 1004)
                            reason = "Reserved. The specific meaning might be defined in the future.";
                        else if (event.code === 1005)
                            reason = "No status code was actually present.";
                        else if (event.code === 1006)
                            reason = "The connection was closed abnormally, e.g., without sending or receiving a Close control frame";
                        else if (event.code === 1007)
                            reason = "An endpoint is terminating the connection because it has received data within a message that was not consistent with the type of the message (e.g., non-UTF-8 [http://tools.ietf.org/html/rfc3629] data within a text message).";
                        else if (event.code === 1008)
                            reason = "An endpoint is terminating the connection because it has received a message that \"violates its policy\". This reason is given either if there is no other sutible reason, or if there is a need to hide specific details about the policy.";
                        else if (event.code === 1009)
                            reason = "An endpoint is terminating the connection because it has received a message that is too big for it to process.";
                        else if (event.code === 1010) // Note that this status code is not used by the server, because it can fail the WebSocket handshake instead.
                            reason = "An endpoint (client) is terminating the connection because it has expected the server to negotiate one or more extension, but the server didn't return them in the response message of the WebSocket handshake. <br /> Specifically, the extensions that are needed are: " + event.reason;
                        else if (event.code === 1011)
                            reason = "A server is terminating the connection because it encountered an unexpected condition that prevented it from fulfilling the request.";
                        else if (event.code === 1015)
                            reason = "The connection was closed due to a failure to perform a TLS handshake (e.g., the server certificate can't be verified).";
                        else
                            reason = "Unknown reason";
                        $('#errorText').html('Web Socket is closed. ' + reason);
                    }
                    if (type === 'ice') {
                        $('#errorText').html('Peer Connection is closed. State: ' + input.peerConnection.iceConnectionState);
                    }
                },
                iceStateChange: function (state) {
                    iceStateSpan.text(state);
                    if (state === 'connected') {
                        testPlaybackButtonArea.show();
                    }
                }
            }
        });

        input.attachMedia(videoElement);

        if (videoSourceSelect.val()) {

            if (videoSourceSelect.val() === 'displayCapture') {


                input.getDisplayMedia(getDisplayConstraints()).then(function (stream) {

                    streamingButton.prop('disabled', false);
                    getResolutionAndCalculateFrame(videoElement);
                });
            } else {

                const userConstraints = getUserConstraints();

                input.getUserMedia(userConstraints).then(function (stream) {

                    streamingButton.prop('disabled', false);
                    getResolutionAndCalculateFrame(videoElement);
                });
            }
        }
    }
    function setVideoEnable(){
        $.post('do_video.php', { id: gameId, enable: gameVideoState });
        // $.ajax({
        //     url: 'do_video.php',
        //     type: "POST",
        //     data: { id: gameId, enable: gameVideoState },
        //     // success: success,
        //     dataType: 'html'
        // });
    }   
    function startStreaming() {

        streamingStarted = true;
        streamingButton.removeClass('btn-primary').addClass('btn-danger');
        streamingButton.text('STOP');
        gameVideoState = true;
        

        if (input) {

            let connectionConfig = {};

            if (videoBitrateInput.val()) {
                connectionConfig.maxVideoBitrate = parseInt(videoBitrateInput.val());
            }

            // input.startStreaming(webRtcUrlInput.val(), connectionConfig);
            input.startStreaming(webRtcUrlInput, connectionConfig);
        }
    }

    function stopStreaming() {

        streamingStarted = false;
        streamingButton.removeClass('btn-danger').addClass('btn-primary');
        streamingButton.text('START');
        gameVideoState = false;
       

        if (input) {
            createInput();
        }

    }

    streamingButton.on('click', function () {

        if (!streamingStarted) {
            startStreaming();
        } else {
            stopStreaming();
        }
    });

    playStreamButton.on('click', function () {
        const link = {};

        // const inputUrl = webRtcUrlInput.val();
        const inputUrl = webRtcUrlInput;

        const url = new URL(inputUrl);

        let playbackUrl = url.protocol + url.host + url.pathname;

        if (url.searchParams.get('transport')) {

            playbackUrl += '?transport=' + url.searchParams.get('transport');
        }

        link.playerOption = {
            autoStart: true,
            sources: [
                {
                    type: 'webrtc',
                    file: playbackUrl
                }
            ]
        };
        console.log('ссылка: ' + link);
        window.open('https://video.darts28.ru/demo.html#' + encodeURIComponent(JSON.stringify(link)));
    });

    let lastResult;

    setInterval(function () {

        if (!input || !input.peerConnection) {
            bitrateSpan.text('-');
            return;
        }

        let sender = null;

        input.peerConnection.getSenders().forEach(function (s) {

            if (s.track && s.track.kind === 'video') {
                sender = s;
            }
        });

        if (!sender) {
            bitrateSpan.text('-');
            return;
        }

        sender.getStats().then(res => {
            res.forEach(report => {
                let bytes;
                let headerBytes;
                let packets;

                if (report.type === 'outbound-rtp') {
                    if (report.isRemote) {
                        return;
                    }

                    const now = report.timestamp;
                    bytes = report.bytesSent;
                    headerBytes = report.headerBytesSent;

                    packets = report.packetsSent;
                    if (lastResult && lastResult.has(report.id)) {
                        // calculate bitrate
                        const bitrate = 8 * (bytes - lastResult.get(report.id).bytesSent) /
                            (now - lastResult.get(report.id).timestamp);
                        const headerrate = 8 * (headerBytes - lastResult.get(report.id).headerBytesSent) /
                            (now - lastResult.get(report.id).timestamp);

                        const packetsSent = packets - lastResult.get(report.id).packetsSent;

                        bitrateSpan.text(bitrate.toFixed(2) + 'kbps');
                    }

                    // console.log('framesEncoded', report.framesEncoded, 'keyFramesEncoded', report.keyFramesEncoded, report.framesEncoded / report.keyFramesEncoded + '(' + report.framesPerSecond + 'fps)')
                }

                if (report.type === 'track') {
                    // console.log(report)
                }

            });
            lastResult = res;
        });
    }, 2000);


    // get all devices at the first time
    OvenLiveKit.getDevices().then(function (devices) {

        allDevices = devices;
        initDemo();
    }).catch(function (error) {

        let errorMessage = '';

        if (error.message) {

            errorMessage = error.message;
        } else if (error.name) {

            errorMessage = error.name;
        } else {

            errorMessage = error.toString();
        }

        $('#errorText').text(errorMessage);
    });

    function initDemo() {

        if (allDevices) {

            setDevice('video', videoSourceSelect, allDevices.videoinput,);
            setDevice('audio', audioSourceSelect, allDevices.audioinput);

            if (savedVideoSource && checkDevice(allDevices.videoinput, savedVideoSource)) {
                videoSourceSelect.val(savedVideoSource);
            }

            if (savedVideoSource === 'no') {
                enableVideoContrll(false);
            }

            if (savedVideoSource === 'displayCapture') {
                audioSourceSelectArea.hide();
            }

            if (savedVideoResolution) {
                videoResolutionSelect.val(savedVideoResolution);
            }

            if (savedVideoBitrate) {
                videoBitrateInput.val(savedVideoBitrate);
            }

            if (savedVideoFrame) {
                videoFrameInput.val(savedVideoFrame);
            }

            if (savedAudioSource && checkDevice(allDevices.audioinput, savedAudioSource)) {
                audioSourceSelect.val(savedAudioSource);
            }
        }

        createInput();
    }


</script>

</body>

</html>