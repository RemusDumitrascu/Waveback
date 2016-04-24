<?php
	try{
		$tracks_array = array();
		$user_to_search = $_POST['search'];
		$pdo = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("SELECT Name, Path, PicturePath, ID, Artist FROM `tracks` ");
		$query->execute();
		$user = $query->fetchAll();
			foreach ($user as $value) {
				if(strlen(strstr( strtolower((string)$value['Name']), strtolower($user_to_search))) != 0 || strlen(strstr( strtolower((string)$value['Artist']), strtolower($user_to_search))) )
					$tracks_array[] = array("name" => $value['Name'], "artist" => $value['Artist'], "path" => str_replace(' ', '%20', $value['Path'] ),  "picture" => str_replace(' ', '%20', $value['PicturePath'] ), "ID" => $value['ID'] );
		}
		echo json_encode($tracks_array);		
	}
	catch(PDOException $e){

	}
?>