<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// get form data
	$stream_key = $_POST['stream_key'];
	$bitrate = $_POST['bitrate'];
	$resolution = $_POST['resolution'];
	$framerate = $_POST['framerate'];
	$codec = $_POST['codec'];

	// set ffmpeg command
	$ffmpeg_cmd = "ffmpeg -f v4l2 -i /dev/video0 -f alsa -i hw:0 -c:v $codec -b:v $bitrate -s $resolution -r $framerate -c:a aac -f flv rtmp://a.rtmp.youtube.com/live2/$stream_key";

	// execute ffmpeg command
	exec($ffmpeg_cmd);

	// return success response
	echo json_encode(['status' => 'success']);
	exit;
}
?>
