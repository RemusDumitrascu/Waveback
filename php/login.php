<?php
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		session_start();
		function clean_input($data){ // remove unecessary spaces, backslashes, convert HTML characters
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
			}
		$email = clean_input($_POST["mail"]);
		$password = clean_input($_POST["password"]);
		$checkbox = isset($_POST["checkbox"]) ? "1" : "0";
		$ok = 0; $name;
		$message='';
			try{
				$conn = new PDO("mysql:host=localhost;dbname=mydb", "root", "ilikeit2");
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$get_data = $conn->prepare("SELECT Email, Password, Username FROM users ");
				$get_data->execute();
					foreach ($get_data->fetchAll() as $data){
						if((string)$data['Email'] == $email)
							if((string)$data['Password'] != $password){
								$message = "pass";
								break;
							}
							else{
								$ok = 1;
								$name = (string)$data['Username'];
								break;								
							}
						else
							$message = "mail";
					}
					if($ok){
						setcookie("remember", $checkbox, time()+(86400*30), "/" );
						setcookie("mail", $email, time()+(86400*30), "/");
						setcookie("name", $name, time()+(86400*30), "/");
						$_SESSION['name'] = $name;
						$_SESSION['mail'] = $email;
						echo "10";
						//header("Location:". substr(dirname(__DIR__), strlen($_SERVER[ 'DOCUMENT_ROOT' ])). "/main.php");
						exit();
						}
					else{
						//header("Location: wrong.php?message=". $message);
						echo "1";
					}
				}
			catch(PDOException $e){
				echo "Couldn't connect to the database: ". $e->getMessage();
			}
	}

?>