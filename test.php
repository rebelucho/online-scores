<?php
require_once __DIR__.'/inc/boot.php';
if (isset($_REQUEST['id']))
    $id = $_REQUEST['id'];
else
    $id = 6666;

if (isset($_REQUEST['enable'])) {

    if ($_REQUEST['enable'] == 'true' || $_REQUEST['enable'] == 1) {
        $enable = 1;
    } else if ($_REQUEST['enable'] == 'false' || $_REQUEST['enable'] == 0) {
        $enable = 0;
    }
}
else {
    $enableVideo = 0;
}

// echo $id;
// echo $enableVideo;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test send</title>
</head>
<body>
    Hello world

<script>
let gameId = '<?php echo $id; ?>';
let enableVideo = '<?php echo $enable; ?>';
let xhr = new XMLHttpRequest();
var body = 'id=' + gameId + '&enable=' + enableVideo;
// var body = 'id=' + encodeURIComponent(gameId) +  '&enable=' + encodeURIComponent(enableVideo);
xhr.open('POST', '/do_video.php', true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
xhr.send(body);
</script>

</body>
</html>