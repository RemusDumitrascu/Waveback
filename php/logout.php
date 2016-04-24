<?php
	session_start();
	session_destroy();
	setcookie("remember", "", time() - 3600, "/");
	setcookie("name", "", time() - 3600, "/");
	setcookie("mail", "", time() - 3600, "/");
	header("Location:" . substr( dirname(__DIR__), strlen($_SERVER[ 'DOCUMENT_ROOT' ]) ) . "/index.php" );
?>