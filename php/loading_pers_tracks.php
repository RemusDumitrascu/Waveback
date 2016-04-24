 <?php
	session_start();
	try{
		$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
		$pdo = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("SELECT Name, Path, PicturePath, ID  FROM `tracks` WHERE Artist = '$name' ORDER BY ReleaseDate DESC  ");
		$query->execute();
		$data = $query->fetchAll();
		echo json_encode($data);
	}
	catch(PDOException $e){
		echo "Couldn't connect to the database: ". $e->getMessage();
	}



?>