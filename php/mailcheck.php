<?php
	$mail = $_REQUEST["data"];
	$username = "root";
	$password = "ilikeit2"; $ok = 1;
			try {
				$conn = new PDO("mysql:host=localhost;dbname=mydb", $username, $password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$getEmail = $conn->prepare("SELECT Email FROM users");
				$getEmail->execute();
				$emails = $getEmail -> fetchAll();
					foreach ($emails as $name) {
						if((string)$name['Email'] == $mail){
							$ok = 0;
							break;
							}
						}
				echo $ok;
				}
			catch(PDOException $e)
				{
				$conn_message = "Connection failed: " . $e->getMessage();
			}

?>