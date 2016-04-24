<?php
	function clean_input($data){ // remove unecessary spaces, backslashes, convert HTML characters
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
	}
	$name = isset($_SESSION['name']) ? $_SESSION['name']: $_COOKIE['name']  ;
	$bio = clean_input($_POST['bio']);
	$location = clean_input($_POST['location']);
	try{
		$pdo = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("UPDATE `users` SET Location = '$location', Bio = '$bio' WHERE Username = '$name'  ");
		$query->execute();
	}
	catch(PDOException $e){
		echo "Couldn't connect to database" . $e->getMessage();
	}
?>