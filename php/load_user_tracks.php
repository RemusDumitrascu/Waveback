<?php
	$user = $_POST['username'];
	try{
		$pdo = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("SELECT Name, Path, PicturePath, ID  FROM `tracks` WHERE Artist = '$user' ORDER BY ReleaseDate DESC  ");
		$query->execute();
		$data = $query->fetchAll();
		echo json_encode($data);
	}
	catch(PDOException $e){
		echo "no";
	}




?>