<?php
	session_start();
	//error_reporting(E_ALL);
	function clean_input($data){ // remove unecessary spaces, backslashes, convert HTML characters
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	function now($str_user_timezone,
       $str_server_timezone = CONST_SERVER_TIMEZONE,
       $str_server_dateformat = CONST_SERVER_DATEFORMAT) {
 
  	// set timezone to user timezone
	  date_default_timezone_set($str_user_timezone);
	 
	  $date = new DateTime('now');
	  $date->setTimezone(new DateTimeZone($str_server_timezone));
	  $str_server_now = $date->format($str_server_dateformat);
	 
	  // return timezone to server default

	  date_default_timezone_set($str_server_timezone);
	 
	  return $str_server_now;
	}
	function generate(){
		$id = "3";
		for($i = 1; $i<= 5; $i++)
			$id .= rand(1 , 9);
		return $id;
	}
	$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
	$track_name = clean_input($_POST['track_name']);
	$description = clean_input($_POST['description']);
	$teacher = $_POST['teacher'];
	$target_path = dirname(__DIR__) . "\users\\" . $name . "\Tracks\\" ;
	$song_path = $target_path . str_replace(' ', '', substr($_FILES['song']['name'], 0, strrpos($_FILES['song']['name'], '.') ))  . "\\" . basename($_FILES['song']['name']);
	$img_path = isset($_FILES['track_image']['name']) ? $target_path . str_replace(' ', '', substr($_FILES['song']['name'], 0, strrpos($_FILES['song']['name'], '.') )) . "\\" . basename($_FILES['track_image']['name']) : "" ;
		if(file_exists($song_path) || file_exists($target_path. $track_name))
			echo "You already have a song with the same name.";
		else{
			try{
				$pdo = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$select = $pdo->prepare("SELECT ID FROM `tracks` ");
				$select->execute();
				$ids = $select->fetchAll();
				$id = generate() . $name[0];
					foreach ($ids as $data) {
						while ($id == (string)$data['ID'] )
							$id = generate() . $name[0];
					}
				$select1 = $pdo->prepare("SELECT Professor FROM `users` WHERE Username = '$name' ");
				$select1->execute();
				$pp = $select1->fetchAll();
					foreach ($pp as $data) {
						if($data['Professor'] == '1')
							$teacher = "";
					}
				mkdir($target_path . str_replace(' ', '', substr($_FILES['song']['name'], 0, strrpos($_FILES['song']['name'], '.') )) , 0777, true);
				if(move_uploaded_file($_FILES['song']['tmp_name'], $song_path) ){
					$song_path = substr( str_replace('\\', '/', $song_path), strlen($_SERVER[ 'DOCUMENT_ROOT' ]) );
					if(isset($_FILES['track_image']['name']) )
						if(move_uploaded_file($_FILES['track_image']['tmp_name'] , $img_path) )
							$img_path = substr( str_replace('\\', '/', $img_path), strlen($_SERVER[ 'DOCUMENT_ROOT' ]) );
					define('CONST_SERVER_TIMEZONE', 'UTC');
					define('CONST_SERVER_DATEFORMAT', 'YmdHis');			
					$insert = $pdo->prepare("INSERT INTO `tracks`(ID, Name, Artist, Path, PicturePath, Description, Teacher, ReleaseDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ");
					$insert->execute(array($id, $track_name, $name, $song_path, $img_path, $description, $teacher, now('Europe/Bucharest')  ));		
					echo "Song uploaded successfully!" ;
				}
			}
			catch(PDOException $e){
				echo "Couldn't connect to the database: ". $e->getMessage();
			}
		}
?>