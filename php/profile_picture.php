<?php
	session_start();
	function check_type($extensions, $type){
		for ($i = 0; $i< count($extensions); $i++) 
			if($type == $extensions[$i])
				return 1;
		return 0;
	}
	function replace($text){
		for($i = 0; $i< strlen($text); $i++)
			if($text[$i] == '\\')
				$text[$i] = '/';
	}
		if(isset($_FILES['img']['name'])){
			$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
			$extensions = array('jpg', 'png', 'gif', 'jpeg' );
			$target_dir = dirname(__DIR__).'\users\\' . $name . '\Pictures\\';
			$_FILES['img']["name"] = str_replace(' ', '_', $_FILES['img']["name"]);
			$target_file = $target_dir. basename($_FILES['img']["name"]);
			$image_type = pathinfo($target_file, PATHINFO_EXTENSION);
			$check = getimagesize($_FILES['img']['tmp_name']);
				if(check_type($extensions, $image_type) && $_FILES['img']['size'] <= 2000000  && $check['mime'] !== false){
					try{
						$conn_pp = new PDO('mysql: host=localhost; dbname=mydb', "root", "ilikeit2");
						$conn_pp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							if(!file_exists($target_file)){
								$select = $conn_pp->prepare("SELECT `ProfilePicture` FROM `users` WHERE `Username` = '$name' ");
								$select->execute();
								$result = $select->fetchAll();
									foreach ($result as $data){
										if(strlen((string)$data['ProfilePicture']) > 0)
											unlink(str_replace('/', '\\', $_SERVER[ 'DOCUMENT_ROOT' ]). str_replace('/', '\\', $data['ProfilePicture']));
									}
								}
								if(move_uploaded_file($_FILES['img']['tmp_name'], $target_file)){
										$target_file = str_replace('\\', '/', $target_file);
										$target_file = substr($target_file, strlen($_SERVER[ 'DOCUMENT_ROOT' ]));
										$update = $conn_pp->prepare("UPDATE `users` SET `ProfilePicture` ='$target_file' WHERE `Username`= '$name' ");
										$update->execute();	
										echo $target_file;
								}
								else
									echo "no";
						
					}					
					catch(PDOException $e){
						echo $e->getMessage();
					}
			}
			else
				echo "no";
		}
?>