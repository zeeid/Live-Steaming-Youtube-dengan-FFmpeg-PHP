<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// get form data
	$stream_key = $_POST['stream_key'];
	$video_path = $_POST['video_path'];
	$video_codec = $_POST['video_codec'];
	$audio_codec = $_POST['audio_codec'];
	$preset = $_POST['preset'];
	$bitrate = $_POST['bitrate'];
	$resolution = $_POST['resolution'];
	$framerate = $_POST['framerate'];

	// build ffmpeg command
	$ffmpeg_cmd = 'ffmpeg -re -i "'.$video_path.'"';
	$ffmpeg_cmd .= ' -c:v '.$video_codec.' -c:a '.$audio_codec.' -preset '.$preset.' -b:v '.$bitrate.'k';
	$ffmpeg_cmd .= ' -s '.$resolution.' -r '.$framerate.' -f flv "rtmp://a.rtmp.youtube.com/live2/'.$stream_key.'" ';
	
	set_time_limit(1800);
	ob_implicit_flush(true);
	// start ffmpeg process
	$descriptorspec = array(
		0 => array('pipe', 'r'), // stdin is a pipe that the child will read from
		1 => array('pipe', 'w'), // stdout is a pipe that the child will write to
		2 => array('pipe', 'w') // stderr is a pipe that the child will write to
	);
	$process = proc_open($ffmpeg_cmd, $descriptorspec, $pipes);

	if (is_resource($process)) {
		// send success response to client
		$response = array(
			'status' => 'success',
			'ffmpeg_cmd' => $ffmpeg_cmd
		);
		echo json_encode($response);

		// wait for ffmpeg process to finish
		while (($line = fgets($pipes[1])) !== false) {
			// output ffmpeg log to console
			echo $line;

			// flush output buffer to prevent server timeout
			ob_flush();
			flush();
		}

		// close pipes and process
		fclose($pipes[0]);
		fclose($pipes[1]);
		fclose($pipes[2]);
		proc_close($process);
	} else {
		// send error response to client
		$response = array(
			'status' => 'error',
			'message' => 'Gagal Memulai Live Streaming'
		);
		echo json_encode($response);
	}

}
?>