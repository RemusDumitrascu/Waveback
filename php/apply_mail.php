<?php
	require ("PHPMailer-master/PHPMailerAutoload.php");
	session_start();
	function clean_input($data){ // remove unecessary spaces, backslashes, convert HTML characters
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
	}
	$name = isset($_SESSION['name']) ? $_SESSION['name']: $_COOKIE['name']  ;
	$skype_id = clean_input($_POST['skype']);
	$pers_info = clean_input($_POST['pers_info']);
	$links = clean_input($_POST['links']);
	try{
		$conn = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$update = $conn->prepare("UPDATE `users` SET Skype = '$skype_id', Prof = '$pers_info', Links = '$links' WHERE Username = '$name' ");
		$update->execute();
		$select = $conn->prepare("SELECT Email FROM `users` WHERE Username = '$name' ");
		$select->execute();
		$result = $select->fetchAll();
			foreach ($result as $data) {
				$mail = $data['Email'];
			}
			$mailer = new PhpMailer();
			$mailer->isSMTP();
			$mailer->CharSet = 'UTF-8';
			$mailer->Debugoutput = 'html';
			$mailer->Host = 'mail.gmx.com'; 
			$mailer->Port = 587; 
			$mailer->SMTPSecure = 'tls';  
			$mailer->SMTPAuth = true;    
			$mailer->Username = 'waveback@gmx.com';                 
			$mailer->Password = 'qwerty1234';    
			$mailer->isHTML(true);                         
			$mailer->setFrom('waveback@gmx.com', 'Waveback Team');                   
			$mailer->addAddress($mail, $name);
			$mailer->Subject = "Waveback Register";
			$mailer->Body = "<p>Thanks for applying ! We will process your request and schedule a skype call with you ! </p> <br> <p>Best regards, Waveback Team.";
				if(!$mailer->send())
					echo $mailer->ErrorInfo;
				else
					echo "Email sent!";

	}
	catch(PDOException $e){
		echo "Couldn't connect to the server" . $e->getMessage();
	}



?>