<?php
	function clean_input($data){ // remove unecessary spaces, backslashes, convert HTML characters
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	$id = $_POST['id'];
	$comment = clean_input($_POST['comment']);
	$artist = $_POST['artist'];
	try{
		$pdo = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("INSERT INTO `comments` (Comment, ID, Artist) VALUES (?, ?, ?)");
		$query->execute(array($comment, $id, $artist) );
		$query = $pdo->prepare("UPDATE `tracks` SET Checked = '1' WHERE ID = '$id' ");
		$query->execute();
		echo 'ok';
	}	
	catch(PDOException $e){
		echo $e->getMessage();
	}	



?>