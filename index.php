<!DOCTYPE html>
<?php
	session_start();
	if(isset($_SESSION['mail']) )
		header("Location: main.php");
	if(isset($_COOKIE["remember"]))
		if($_COOKIE["remember"] == "1")
			header("Location: main.php");
?>
<html>
	<head>
		<title>WaveBack</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>	
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<script type="text/javascript" src="js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<script type="text/javascript" src="js/animations.js"></script>
		<script type="text/javascript" src="js/index.js"></script>
	</head>
	<body>
		<?php
			
			$name = $mail = $pw = $pw_c = $regexp = "";
				function count_char($text, $char){
					$k = 0;
					for($i = 0; $i < strlen($text); $i++)
						if($text[$i] == $char)
							$k++;
					return $k;
				}
				function clean_input($data){ // remove unecessary spaces, backslashes, convert HTML characters
					$data = trim($data);
					$data = stripslashes($data);
					$data = htmlspecialchars($data);
					return $data;
				}
				function checkpw($pw){// Check if the password has at least one number in it
					for($i = 0; $i <= 9; $i++)
						if(strchr($pw, (string)$i ))
							return 1;
					return 0;
				}
				function check_mail($mail){// Check if the email has a dot before @
					for($i = strlen($mail) - 1; $i >=0; $i--){
						if($mail[$i] == '@')
							break;
						if($mail[$i] == '.')
							return 1;
					}
					return 0;
				}
				function check_for_SC($data,$regexp){// Check if $data has special characters in it, except for '@', '.', '_'
					for($i = 0; $i<strlen($data); $i++)
						if(strchr($regexp, $data[$i]))
							return 0;
					return 1;
				}
				function check_mail2($mail){// Check if the email has '_' after '@'
					for($i = strlen($mail)-1 ; $i>=0; $i--){
						if($mail[$i] == '@')
							break;
						if($mail[$i] == '_')
							return 0;
						}
					return 1;
				}

				if($_SERVER["REQUEST_METHOD"] == 'POST'){
					$name = clean_input($_POST["username"]);
					$mail = clean_input($_POST["mail_signup"]);
					$pw = clean_input($_POST["pass_signup"]);
					$pw_c = clean_input($_POST["confirm_pass"]);
					//$name = str_replace(' ', '_', $name);
					$username = "root";
					$password = "ilikeit2";					
					$i; $mail_ok = 1; $user_ok = 1;
					//initialize $regexp with all the special characters, except for '@', '_', '.'
					for($i = 32; $i<= 45; $i++)
						$regexp = $regexp.chr($i);
					$regexp = $regexp.'/';
					for($i = 58; $i<= 63; $i++)
						$regexp = $regexp.chr($i);
					for($i = 91; $i<= 94; $i++)
						$regexp = $regexp.chr($i);
					$regexp = $regexp.chr(96);
					for($i = 123; $i<= 255; $i++)
						$regexp = $regexp.chr($i);

						if($pw == $pw_c && strlen($pw) >= 8 && strlen($pw)<=16 && checkpw($pw) && check_for_SC($pw,$regexp) && !strchr($pw,'_') && !strchr($pw,'@') && !strchr($pw,'.'))
							if(check_for_SC($mail, $regexp) && check_mail($mail) && check_mail2($mail) )
								if( strlen($name)>=2 ){
									try {
										$conn = new PDO("mysql:host=localhost;dbname=mydb", $username, $password, array( PDO::ATTR_PERSISTENT => true) );
										$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
										$select = $conn->prepare("SELECT Username, Email FROM users");
										$select->execute();
										$result = $select->fetchAll();
											foreach ($result as $data) {
												if((string)$data['Email'] == $mail){
													$mail_ok = 0;
													break;
												}
												if((string)$data['Username'] == $name){
													$user_ok = 0;
													break;
												}
											}
											if($mail_ok && $user_ok){ 
												$query = $conn->prepare("INSERT INTO users (Username, Email, Password) VALUES (?,?,?)");
												$query->execute(array($name, $mail, $pw));
												date_default_timezone_set('Etc/UTC');
													$_SESSION['tmp_mail'] = $mail;
													$_SESSION['tmp_name'] = $name;
													$_SESSION['tmp_pass'] = $pw;
													mkdir("users/" . $name, 0777, true);
													mkdir("users/" . $name ."/Pictures", 0777, true);
													mkdir("users/" . $name."/Tracks", 0777, true);
													header("location: succes.php");	
													//}
												}
										}
									catch(PDOException $e){
											echo "Connection failed: " . $e->getMessage();
										}	
							}	
					}													
			?>
		<div class="header">
				<div class="login_switch"> Log In </div>
				<div class="signup_switch"> Sign Up </div>
				<div class="aboutus_switch">
					<p>About Us</p>
				</div>
		</div>
		<script type="text/javascript" >
		// This is called with the results from from FB.getLoginStatus().
		  function statusChangeCallback(response){
		    console.log('statusChangeCallback');
		    console.log( response );
		    // The response object is returned with a status field that lets the
		    // app know the current login status of the person.
		    // Full docs on the response object can be found in the documentation
		    // for FB.getLoginStatus().
		    if (response.status === 'connected') {
		      // Logged into your app and Facebook.
		      testAPI();
		    } else if (response.status === 'not_authorized') {
		      // The person is logged into Facebook, but not your app.
		      /*document.getElementById('status').innerHTML = 'Please log ' +
		        'into this app.';*/
		    } else {
		      // The person is not logged into Facebook, so we're not sure if
		      // they are logged into this app or not.
		      /*document.getElementById('status').innerHTML = 'Please log ' +
		        'into Facebook.';*/
		    }
		  }
		  // This function is called when someone finishes with the Login
		  // Button.  See the onlogin handler attached to it in the sample
		  // code below.
		  function checkLoginState() {
		    FB.getLoginStatus(function(response) {
		      statusChangeCallback(response);
		    });
		  }
		  window.fbAsyncInit = function() {
		  FB.init({
		    appId      : '267481923589568',
		    cookie     : true,  // enable cookies to allow the server to access 
		                        // the session
		    xfbml      : true,  // parse social plugins on this page
		    version    : 'v2.5' // use graph api version 2.5
		  });
		  // Now that we've initialized the JavaScript SDK, we call 
		  // FB.getLoginStatus().  This function gets the state of the
		  // person visiting this page and can return one of three states to
		  // the callback you provide.  They can be:
		  //
		  // 1. Logged into your app ('connected')
		  // 2. Logged into Facebook, but not your app ('not_authorized')
		  // 3. Not logged into Facebook and can't tell if they are logged into
		  //    your app or not.
		  //
		  // These three cases are handled in the callback function.
		  FB.getLoginStatus(function(response) {
		    statusChangeCallback(response);
		  });
		  };
		  // Load the SDK asynchronously
		  (function(d, s, id) {
		    var js, fjs = d.getElementsByTagName(s)[0];
		    if (d.getElementById(id)) return;
		    js = d.createElement(s); js.id = id;
		    js.src = "//connect.facebook.net/en_US/sdk.js";
		    fjs.parentNode.insertBefore(js, fjs);
		  }(document, 'script', 'facebook-jssdk'));
		  // Here we run a very simple test of the Graph API after login is
		  // successful.  See statusChangeCallback() for when this call is made.
		 	function testAPI() {
		    	//console.log('Welcome!  Fetching your information.... ');
		  		FB.api('/me', { locale: 'en_US', fields: 'name, email' }, function(response) {
		  			console.log(response);
					var post = $.post('php/facebook_login.php', {'name': response.name, 'email': response.email});
					post.done(function(data){
						if(data == 'ok')
							location.assign('main.php');
						else{
							alert(data);
							FB.logout(function(response) {
	  							//user is now logged out
							});
						}
					});
		   		});
		  }
		</script>
		<div class="login_thing">
			<div class="title">Log In</div>
			<div class="fb_login">
				<fb:login-button scope="public_profile,email" onlogin="checkLoginState();" class="facebook_button">    </fb:login-button>
			</div>
				<div class="form_container">
					<div class="login_warning">You should check your entries </div>
					<form class="login_form" action="php/login.php" method="POST">
						<p>Email</p>
							<input class = "text_inputs" type="text" name="mail_login" autocomplete="off" />
						<p>Password</p>
							<input class = "text_inputs" type="password" name="pass_login" maxlength = "16" autocomplete="off" />
						<div class="remember">
							<input type = "checkbox" name="remember"  value="remembered" id="checkbox"/>
							<span>Remember me</span> 
						</div>
						<button type="submit" class="login_submit">Log in</button>
					</form>
				</div>
			<script type="text/javascript" src = "js/login.js"></script>
		</div>
		<div class="signup_thing">
			<div class="title">Create your own account</div>

				<div class="form_container">
					<form class="signup_form" method="post">
						<div class="namehint">
							<div class="text">Use between 2 and 20 characters</div>
								<div class="arrow-border">
									<div class="arrow-body"></div>
								</div>
						</div>	
						<p>User name</p>
							<input class = "text_inputs" type="text" name="username" autocomplete="off" maxlength = "15" value = "<?php echo $name ?>" />
						<p>Email</p>
							<input class = "text_inputs" type="text" name="mail_signup" autocomplete="off" value = "<?php echo $mail ?>" />
							<div class = "mail_indications">
								<p>This is not a valid email adress</p>
							</div>
						<p>Password</p>
							<input class = "text_inputs" type="password" name="pass_signup" maxlength = "16" autocomplete="off" />
						<p>Confirm password</p>
							<input class = "text_inputs" type="password" name="confirm_pass" maxlength = "16" autocomplete="off" />
						<div class="password_indications">
							<div class="arrow-border">
								<div class="arrow-body"></div>
							</div>
							<div class="text">
								<ul>
									<li>Use between 8 and 16 characters</li>
									<li>You must use at least one number</li>
									<li>No special characters allowed</li>
									<li>The passwords must match</li>
								</ul>
							</div>
						</div>
						<button type="submit" class="login_submit signup_submit">Create account</button>
					</form>
				</div>
		</div>
		<div class="aboutus_thing">

			<div class="text-container">
				<hr>
				Our project is made for building a community for music producers who are looking to get their music to the next level.<br>
				If you are a pro music producer you can make an account, apply for a job and start giving feedback to begginer producers.
			</div>			
		</div>
	</body>
</html>