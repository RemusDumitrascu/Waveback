<?php
	session_start();
	try{
		$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
		$pdo = new PDO("mysql: host=localhost; dbname=mydb", "root", "ilikeit2");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$select = $pdo->prepare("SELECT `CoverPicture` FROM `users` WHERE `Username` = '$name' ");
		$select->execute();
		$result = $select->fetchAll();
				foreach ($result as $data)
					if(strlen((string)$data['CoverPicture']) > 0)
						echo  str_replace(' ', '%20', substr(dirname(__DIR__), strlen($_SERVER[ 'DOCUMENT_ROOT' ])) . (string)$data['CoverPicture'] );
					else
						echo "no";
		}
		catch(PDOException $e){
			echo "no";
		}




?>