<?php
	$user = $_REQUEST["data"];
	$username = "root";
	$password = "ilikeit2"; $ok = 1;
	try{
		$conn = new PDO("mysql:host=localhost;dbname=mydb", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$get_names = $conn->prepare("SELECT Username FROM users");
		$get_names->execute();
		$names = $get_names->fetchAll();
			foreach ($names as $name) {
				if((string)$name['Username'] == $user){
					$ok = 0;
					break;
				}
			}
			echo $ok;
		}
	catch(PDOException $e){
		echo $e->getMessage();
	}

?>