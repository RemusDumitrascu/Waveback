<?php
	try{
		$users_array = array();
		$user_to_search = $_POST['search'];
		$pdo = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("SELECT Username, Location, ProfilePicture FROM `users` WHERE Professor = '0' ");
		$query->execute();
		$user = $query->fetchAll();
			foreach ($user as $value) {
				if(strlen(strstr( strtolower((string)$value['Username']), strtolower($user_to_search))) != 0 )
					if(empty($value['ProfilePicture']))
						$users_array[] = array("username" => $value['Username'], "location" => $value['Location'], "profile_picture" => "/css/poza_profil.png" );
					else
						$users_array[] = array("username" => $value['Username'], "location" => $value['Location'], "profile_picture" => str_replace(' ', '%20', $value['ProfilePicture'] ) );
		}
		echo json_encode($users_array);		
	}
	catch(PDOException $e){

	}

?>