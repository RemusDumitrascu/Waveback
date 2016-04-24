<?php
	session_start();
	function check_type($extensions, $type){
		for ($i = 0; $i< count($extensions); $i++) 
			if($type == $extensions[$i])
				return 1;
		return 0;
	}	
	if(isset($_FILES['song']['name']) ){
		$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
		$extensions = array('mp3', 'wav');
		$target_dir = dirname(__DIR__). ' \users\\ '. $name . '\\Tracks\\'  ;
		$target_file = $target_dir . basename($_FILES['song']['name']);
		$song_extension = pathinfo($target_file, PATHINFO_EXTENSION);
			if(check_type($extensions, $song_extension) && $_FILES['song']['size'] <= 100000000 )
				echo $target_file;
			else
				echo "no";
	}

?>