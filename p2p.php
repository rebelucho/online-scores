<?php
require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.php';
// $id = $_GET["id"];

$user = null;
if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
function curdate() {
    return date('Y-m-d');
}

if (isset($_GET['stage']))
$_SESSION['stage'] = $_GET['stage'];

if (isset($_SESSION['key']))
$key = $_SESSION['key'];
else
$key = 0;


if (isset($_SESSION["stage"])){
    $stage = $_SESSION["stage"];
} else {
    $stage = 'list';
    $_SESSION["stage"] = 'list';
}

if (isset($_SESSION["dategame"])){
	$dategame = $_SESSION["dategame"];
} else {
	$dategame = curdate();
}

if (isset($_SESSION["name"])){
	$name = $_SESSION["name"];
} else {
	$name = "";
}

if (isset($_SESSION["tag"])){
	$tag = $_SESSION["tag"];
} else {
	$tag = "";
}

if (isset($_GET['admGame'])){
	$admGames = $_GET['admGames'];
} else {
	$admGames = false;
} 
	

if (isset($_GET['pageno'])) {
    // Если да то переменной $pageno его присваиваем
    $pageno = $_GET['pageno'];
} else { // Иначе
    // Присваиваем $pageno один
    $pageno = 1;
}


# stage
# list - список активных игр
# register - регистрация игры
# answer - регистрация второго участника
# start1Player or start2Player - кто из игроков бросает первым ???
# throw1Player - подход первого игрока
# throw2Player - подход второго игрока
# videoReg - регистрация в видео-комнате для видео p2p


?>


<div class="container">
<!-- <div class="currentThrowIcon"></div>
<div class="startPlayerIcon"></div> -->

<?php flash() ?>
</div>
<div class="container">

<?php 
if ($stage == 'list') {
?>

<div>
<h1 class="text-center">Список активных игр</h1>
<div class="h1 small text-muted text-center">за последний час</div>
</div>
<div class="container">

</div>
<div class="container">
<div class="row row-cols-1 row-cols-md-auto align-items-end justify-content-center">
	<!-- <div class="col" hidden>
		<label for="dateValue" class="form-label">Дата игры</label>
		<input class="form-control" id="dateValue" type="date" value="<?php echo $dategame; ?>" />
	</div> -->
	<div class="col">
		<label for="nameValue" class="form-label">Имя игрока</label>
		<input class="form-control" id="nameValue" type="text" value="<?php echo $name; ?>" />
	</div>
	<!-- <div class="col">
		<label for="tagValue" class="form-label">Тэг игры</label>
		<input class="form-control" id="tagValue" type="text" value="<?php echo $tag; ?>" />
	</div> -->
    <div class="col align-bottom justify-items-end">
        <input type="button" class="btn btn-primary btn_click_attr" value="Найти" onclick=setVar()>
    </div>
  </div>
</div>
</br>

<div id="game_list"></div>
<script type="text/javascript">
	let tag = ''
	let name = ''
	let admGames = '<?php echo $admGames; ?>'
	let pageno = '<?php echo $pageno; ?>'
	let dategame = '<?php echo $dategame; ?>'
    let listSet = 'p2p'
	
	getgame()

</script>

<?php 
}
?>


<?php 
if ($stage == 'register') {
?>
<div class="row text-center justify-content-center align-items-center g-2">
    <h1 class="mb-3">Запрос на игру</h1>
</div>
</div>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-md-3"></div>
        <div class="col-12 col-md-6">
            <form method="post" action="do_p2p.php">
                <div class="mb-3">
                    <label for="username" class="form-label">PLAYER 1 NAME</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="scores" class="form-label">Начальное кол-во очков</label>
                    <input type="text" class="form-control" id="scores" name="scores" required>
                </div>
                <div class="mb-3">
                    <label for="guid" class="form-label">GUID PLAYER 1</label>
                    <input type="text" class="form-control" id="guid" name="guid" required>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Получить ключ игры</button>
                </div>
            </form>
            </br>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<?php } 


if ($stage == 'answer') {
?>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-md-3"></div>
        <div class="col-12 col-md-6">
            <form method="post" action="do_p2p.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Ваше имя</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="guid" class="form-label">GUID устройства</label>
                    <input type="text" class="form-control" id="guid" name="guid" value="<?php echo gen_password(14);?>" required>
                </div>
                <div class="mb-3">
                    <label for="key" class="form-label">Ключ игры</label>
                    <input type="text" class="form-control" id="key" name="key" value="<?php echo $key;?>" required>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Ответить на вызов</button>
                </div>
            </form>
            </br>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<?php
}
if ($stage == 'throw1Player') {
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
    $stmt->execute(['key' => $_SESSION['key']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-2"><?php echo $game['require1'];?> : <?php echo $game['require2'];?></div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-md-3"></div>
        <div class="col-12 col-md-6">
            <form method="post" action="do_p2p.php">
                <div class="mb-3">
                    <label for="scores" class="form-label">Набор игрок 1</label>
                    <input type="text" class="form-control" id="scores" name="scores" required>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </div>
            </form>
            </br>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>


<?php
}
if ($stage == 'throw2Player') {

    if (is_null($_SESSION['key'])){
        flash('Не выбран ключ игры, или сессия протухла');
        $_SESSION['stage'] = 'list';
        header('Location: /p2p.php');
        die;
    }

    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
    $stmt->execute(['key' => $_SESSION['key']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-2"><?php echo $game['require1'];?> : <?php echo $game['require2'];?></div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-md-3"></div>
        <div class="col-12 col-md-6">
            <form method="post" action="do_p2p.php">
                <div class="mb-3">
                    <label for="scores" class="form-label">Набор игрок 2</label>
                    <input type="text" class="form-control" id="scores" name="scores" required>
                </div>
                <div class="mb-3">
                    <label for="darts" class="form-label">Кол-во дротиков</label>
                    <input type="text" class="form-control" id="darts" name="darts" value="3" required>
                </div>
                <div class="mb-3">
                    <label for="doubleAttempts" class="form-label">Попыток удвоения</label>
                    <input type="text" class="form-control" id="doubleAttempts" name="doubleAttempts" value="0">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </div>
            </form>
            </br>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>


<?php
} if ($stage == 'videoReg') {
?>
    <div class="container">
        <div class="row justify-content-center align-items-center g-2">
            <div class="col-md-3"></div>
            <div class="col-12 col-md-6">
                <form method="post" action="do_p2p.php">
                    <div class="mb-3">
                        <label for="key" class="form-label">Ключ игры</label>
                        <input type="text" class="form-control" id="key" name="key" value="<?php echo $key;?>" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Включить видео</button>
                    </div>
                </form>
                </br>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

<?php
} if ($stage == 'videoP2P') {
    $room = $_SESSION['key'];
// echo $room;
?>
    <div class="container">


    <div class="row text-center wrapper-sm" ng-show="allLoading">          
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 bg-black dker" ng-hide="gameLoading" ng-switch="game.CurrentStatus" ng-cloak>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-6 bg-black dker">
                    <div class="w-full h-full wrapper-sm">
                        <div ng-hide="avLoading">
                            <video id="localVideo"  autoplay="true" muted="muted" style="width:100%;"></video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 bg-black dker max text-center" ng-show="avLoading">
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 bg-black dker max ng-hide" ng-hide="avLoading">
            <div class="max">
                <video id="remoteVideo" autoplay="true"  class="w-full h-full h"></video>
            </div>
        </div>
    </div>



        <div class="row justify-content-center align-items-center g-2">
            <div class="col-4">Контент игры</video></div>
            <!-- <div class="col-8"><video id="remoteVideo" autoplay="true" style="display:none"></video></div> -->
        </div>
        <div class="row justify-content-center align-items-center g-2">
            <!-- <div class="col-4"><video id="localVideo" autoplay="true" muted="muted" style="width: 640px; height: 480px;"></video></div> -->
            <div class="col">
                <div class="select">
                    <label for="audioSource">Audio input source: </label><select id="audioSource"></select>
                </div>
                <div class="select">
                    <label for="audioOutput">Audio output destination: </label><select id="audioOutput"></select>
                </div>
                <div class="select">
                    <label for="videoSource">Video source: </label><select id="videoSource"></select>
                </div>
                <div class="d-flex justify-content-end">
                    <button onclick="start()" class="btn btn-primary">Рестартовать видео</button>
                </div>
            </div>
            <!-- <div class="col-4"></div> -->
        </div>
    </div>

<script type="text/javascript">

    let answer = 0;
    let pc=null
	let localStream=null;
	let ws=null;
    let room = '<?php echo $room;?>';
    
    // console.log('room = '+room)
    // Not necessary with websockets, but here I need it to distinguish calls
    let unique = Math.floor(100000 + Math.random() * 900000);
    // let unique = room;


    const localVideo = document.getElementById('localVideo');
    const remoteVideo = document.getElementById('remoteVideo');
    const audioInputSelect = document.querySelector('select#audioSource');
    const audioOutputSelect = document.querySelector('select#audioOutput');
    const videoSelect = document.querySelector('select#videoSource');
    const selectors = [audioInputSelect, audioOutputSelect, videoSelect];
    var configuration  = {
        'iceServers': [
			{ 'urls': 'stun:stun.stunprotocol.org:3478' },
			{ 'urls': 'stun:stun.l.google.com:19302' },
			//{'urls': 'stun:stun1.l.google.com:19302' },
			//{'urls': 'stun:stun2.l.google.com:19302' }
        ]
    };
    
    audioOutputSelect.disabled = !('sinkId' in HTMLMediaElement.prototype);

    function gotDevices(deviceInfos) {
      // Handles being called several times to update labels. Preserve values.
      const values = selectors.map(select => select.value);
      selectors.forEach(select => {
        while (select.firstChild) {
          select.removeChild(select.firstChild);
        }
      });
      for (let i = 0; i !== deviceInfos.length; ++i) {
        const deviceInfo = deviceInfos[i];
        const option = document.createElement('option');
        option.value = deviceInfo.deviceId;
        if (deviceInfo.kind === 'audioinput') {
          option.text = deviceInfo.label || `microphone ${audioInputSelect.length + 1}`;
          audioInputSelect.appendChild(option);
        } else if (deviceInfo.kind === 'audiooutput') {
          option.text = deviceInfo.label || `speaker ${audioOutputSelect.length + 1}`;
          audioOutputSelect.appendChild(option);
        } else if (deviceInfo.kind === 'videoinput') {
          option.text = deviceInfo.label || `camera ${videoSelect.length + 1}`;
          videoSelect.appendChild(option);
        } else {
          console.log('Some other kind of source/device: ', deviceInfo);
        }
      }
      selectors.forEach((select, selectorIndex) => {
        if (Array.prototype.slice.call(select.childNodes).some(n => n.value === values[selectorIndex])) {
          select.value = values[selectorIndex];
        }
      });
    }

    navigator.mediaDevices.enumerateDevices().then(gotDevices).catch(handleError);

    // Attach audio output device to video element using device/sink ID.
    function attachSinkId(element, sinkId) {
      if (typeof element.sinkId !== 'undefined') {
        element.setSinkId(sinkId)
            .then(() => {
              console.log(`Success, audio output device attached: ${sinkId}`);
            })
            .catch(error => {
              let errorMessage = error;
              if (error.name === 'SecurityError') {
                errorMessage = `You need to use HTTPS for selecting audio output device: ${error}`;
              }
              console.error(errorMessage);
              // Jump back to first output device in the list as it's the default.
              audioOutputSelect.selectedIndex = 0;
            });
      } else {
        console.warn('Browser does not support output device selection.');
      }
    }

    function changeAudioDestination() {
      const audioDestination = audioOutputSelect.value;
      attachSinkId(localVideo, audioDestination);
    }

    function gotStream(stream) {
      window.stream = stream; // make stream available to console
      localVideo.srcObject = stream;
        localStream = stream;
      
      try {
                ws = new EventSource('video/serverGet.php?unique='+unique+'&room='+room);
            } catch(e) {
                console.error("Could not create eventSource ",e);
            }

            // Websocket-hack: EventSource does not have a 'send()'
            // so I use an ajax-xmlHttpRequest for posting data.
            // Now the eventsource-functions are equal to websocket.
			ws.send = function send(message) {
				 var xhttp = new XMLHttpRequest();
				 xhttp.onreadystatechange = function() {
					 if (this.readyState!=4) {
					   return;
					 }
					 if (this.status != 200) {
					   console.log("Error sending to server with message: " +message);
					 }
				 };
				 xhttp.open('POST', 'video/serverPost.php?unique='+unique+'&room='+room, true);
				 xhttp.setRequestHeader("Content-Type","Application/X-Www-Form-Urlencoded");
				 xhttp.send(message);
			}

            // Websocket-hack: onmessage is extended for receiving 
            // multiple events at once for speed, because the polling 
            // frequency of EventSource is low.
			ws.onmessage = function(e) {
				if (e.data.includes("_MULTIPLEVENTS_")) {
					multiple = e.data.split("_MULTIPLEVENTS_");
					for (x=0; x<multiple.length; x++) {
						onsinglemessage(multiple[x]);
					}
				} else {
					onsinglemessage(e.data);
				}
			}

            // Go show myself
            localVideo.addEventListener('loadedmetadata', 
                function () {
                    publish('client-call', null)
                }
            );
  
      // Refresh button list in case labels have become available
      return navigator.mediaDevices.enumerateDevices();
      
    }

    

    function handleError(error) {
      console.log('navigator.MediaDevices.getUserMedia error: ', error.message, error.name);
    }

    function start() {
      if (window.stream) {
        window.stream.getTracks().forEach(track => {
          track.stop();
        });
      }
      const audioSource = audioInputSelect.value;
      const videoSource = videoSelect.value;
      const constraints = {
        audio: {deviceId: audioSource ? {exact: audioSource} : undefined},
        video: {deviceId: videoSource ? {exact: videoSource} : undefined}
      };
      
    navigator.mediaDevices.getUserMedia(constraints).then(gotStream).then(gotDevices).catch(handleError);
    
    }

    
    // Start
    // navigator.mediaDevices.getUserMedia({
    //         audio: true, // audio is off here, enable this line to get audio too
    //         video: true
    //     }).then(function (stream) {
    //         localVideo.srcObject = stream;
    //         localStream = stream;

    //         try {
    //             ws = new EventSource('video/serverGet.php?unique='+unique+'&room='+room);
    //         } catch(e) {
    //             console.error("Could not create eventSource ",e);
    //         }

    //         // Websocket-hack: EventSource does not have a 'send()'
    //         // so I use an ajax-xmlHttpRequest for posting data.
    //         // Now the eventsource-functions are equal to websocket.
	// 		ws.send = function send(message) {
	// 			 var xhttp = new XMLHttpRequest();
	// 			 xhttp.onreadystatechange = function() {
	// 				 if (this.readyState!=4) {
	// 				   return;
	// 				 }
	// 				 if (this.status != 200) {
	// 				   console.log("Error sending to server with message: " +message);
	// 				 }
	// 			 };
	// 			 xhttp.open('POST', 'video/serverPost.php?unique='+unique+'&room='+room, true);
	// 			 xhttp.setRequestHeader("Content-Type","Application/X-Www-Form-Urlencoded");
	// 			 xhttp.send(message);
	// 		}

    //         // Websocket-hack: onmessage is extended for receiving 
    //         // multiple events at once for speed, because the polling 
    //         // frequency of EventSource is low.
	// 		ws.onmessage = function(e) {
	// 			if (e.data.includes("_MULTIPLEVENTS_")) {
	// 				multiple = e.data.split("_MULTIPLEVENTS_");
	// 				for (x=0; x<multiple.length; x++) {
	// 					onsinglemessage(multiple[x]);
	// 				}
	// 			} else {
	// 				onsinglemessage(e.data);
	// 			}
	// 		}

    //         // Go show myself
    //         localVideo.addEventListener('loadedmetadata', 
    //             function () {
    //                 publish('client-call', null)
    //             }
    //         );
			
    //     }).catch(function (e) {
    //         console.log("Problem while getting audio/video stuff ",e);
    //     });
		
    
    function onsinglemessage(data) {
        var package = JSON.parse(data);
        var data = package.data;
        
        console.log("received single message: " + package.event);
        switch (package.event) {
            case 'client-call':
                icecandidate(localStream);
                pc.createOffer({
                    offerToReceiveAudio: 1,
                    offerToReceiveVideo: 1
                }).then(function (desc) {
                    pc.setLocalDescription(desc).then(
                        function () {
                            publish('client-offer', pc.localDescription);
                        }
                    ).catch(function (e) {
                        console.log("Problem with publishing client offer"+e);
                    });
                }).catch(function (e) {
                    console.log("Problem while doing client-call: "+e);
                });
                break;
            case 'client-answer':
                if (pc==null) {
                    console.error('Before processing the client-answer, I need a client-offer');
                    break;
                }
                pc.setRemoteDescription(new RTCSessionDescription(data),function(){}, 
                    function(e) { console.log("Problem while doing client-answer: ",e);
                });
                break;
            case 'client-offer':
                icecandidate(localStream);
                pc.setRemoteDescription(new RTCSessionDescription(data), function(){
                    if (!answer) {
                        pc.createAnswer(function (desc) {
                                pc.setLocalDescription(desc, function () {
                                    publish('client-answer', pc.localDescription);
                                }, function(e){
                                    console.log("Problem getting client answer: ",e);
                                });
                            }
                        ,function(e){
                            console.log("Problem while doing client-offer: ",e);
                        });
                        answer = 1;
                    }
                }, function(e){
                    console.log("Problem while doing client-offer2: ",e);
                });
                break;
            case 'client-candidate':
               if (pc==null) {
                    console.error('Before processing the client-answer, I need a client-offer');
                    break;
                }
                pc.addIceCandidate(new RTCIceCandidate(data), function(){}, 
                    function(e) { console.log("Problem adding ice candidate: "+e);});
                break;
        }
    };

    function icecandidate(localStream) {
        pc = new RTCPeerConnection(configuration);
        pc.onicecandidate = function (event) {
            if (event.candidate) {
                publish('client-candidate', event.candidate);
            }
        };
        try {
            pc.addStream(localStream);
        }catch(e){
            var tracks = localStream.getTracks();
            for(var i=0;i<tracks.length;i++){
                pc.addTrack(tracks[i], localStream);
            }
        }
        pc.ontrack = function (e) {
            document.getElementById('remoteVideo').style.display="block";
            // document.getElementById('localVideo').style.display="none";
            document.getElementById('localVideo').style="width: 480px; height: 270px;";
            remoteVideo.srcObject = e.streams[0];
        };
    }

    function publish(event, data) {
        console.log("sending ws.send: " + event);
        ws.send(JSON.stringify({
            event:event,
            data:data
        }));
    }

    audioInputSelect.onchange = start;
    audioOutputSelect.onchange = changeAudioDestination;

    videoSelect.onchange = start;

    start();
</script>

<?php
}

?>