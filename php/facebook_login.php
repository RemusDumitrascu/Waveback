<?php
	session_start();
	$name = $_POST['name'];
	$email = $_POST['email'];
	$first_login = 1;
	try{
		$pdo = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("SELECT Username, Email, Password FROM `users` ");
		$query->execute();
		$user = $query->fetchAll();
		foreach ($user as $value) {
			if($value['Username'] == $name && $value['Email'] == $email && (string)$value['Password']  == "fb"){
				echo 'ok';
				$first_login = 0;
				setcookie("remember", '1', time()+(86400*30), "/" );
				setcookie("mail", $email, time()+(86400*30), "/");
				setcookie("name", $name, time()+(86400*30), "/");
				$_SESSION['name'] = $name;
				$_SESSION['mail'] = $email;
			}
			else if($value['Username'] == $name || $value['Email'] == $email){
				echo 'Acces denied!';
				$first_login = 0;
			}
		}
		if($first_login){
				$query1 = $pdo->prepare("INSERT INTO `users`(Username, Email, Password) VALUES(?, ?, ?) ");
				$query1->execute( array($name, $email, "fb") );
				setcookie("remember", '1', time()+(86400*30), "/" );
				setcookie("mail", $email, time()+(86400*30), "/");
				setcookie("name", $name, time()+(86400*30), "/");
				$_SESSION['name'] = $name;
				$_SESSION['mail'] = $email;
				mkdir(dirname(__DIR__)  . "/users/" . $name, 0777, true);
				mkdir(dirname(__DIR__)  . "/users/" . $name ."/Pictures", 0777, true);
				mkdir(dirname(__DIR__)  . "/users/" . $name ."/Tracks", 0777, true);
				echo 'ok';
		}
	}
	catch(PDOException $e){
		echo "Couldn't connect to database" . $e->getMessage();
	}
?>