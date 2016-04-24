<!DOCTYPE html>
<?php
	require ("php/PHPMailer-master/PHPMailerAutoload.php");	
	session_start();
?>
<html>
	<head>
		<title>Signup Successful</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>	
		<link rel="stylesheet" type="text/css" href="css/succes.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<script type="text/javascript" src="js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
	</head>
	<body>
	<script type = "text/javascript">
		function back(){
			 window.history.back();
		}
	</script>
		<img onclick = "back() " src="css/blue-arrow.png">
		<p>You created your account successfully!</p>
		<?php
		//// PhpMailer class
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
				$mailer->setFrom('waveback@gmx.com', 'Waveback Team');                   
				$mailer->addAddress($_SESSION['tmp_mail'], $_SESSION['tmp_name']);
				$mailer->Subject = "Waveback Register";
				$mailer->isHTML(true);  
				$mailer->msgHTML(file_get_contents('php/mail-info.php'));
				//$mailer->msgHTML(file_get_contents(dirname(__FILE__).'\php\mail-info.php?name=' . $_SESSION['tmp_name']. "&pass=" .  $_SESSION['tmp_pass']));
					if(!$mailer->send())
						echo "<p>Couldn't send email with your information. </p>";
					else
						echo "<p>Check your email for your data. </p>";

				?>

	</body>
</html>