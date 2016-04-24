<?php
	session_start();
	if(!isset($_SESSION['name']) && !isset($_COOKIE['name']))
		header("Location:/index.php");
	$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
	?>
<!DOCTYPE html>
<html>
	<head>
		<title>Apply For Professor</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>	
		<link rel="stylesheet" type="text/css" href="/css/main.css">
		<link rel="stylesheet" type="text/css" href="/css/profile.css"/>
		<script type="text/javascript" src="/js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="/js/jquery-ui.js"></script>
		<script type="text/javascript" src="/js/main_animations.js"></script>
		<script type="text/javascript" src="/js/pictures_set.js"></script>
		<style>
			.apply_switch{
				background-color: #42718c;
			}
			.all_elements h1{
				margin:0px auto;
				padding:0px;
				text-align: center;
				text-align: -webkit-center;
				font-family: Source Sans Pro;
				font-size: 1.7em;
				font-weight: normal;
			}
			.all_elements hr{
				border:1px solid #5b9cc1;
				border-bottom-width:0px;
				border-radius: 25%;
				width:1px;
			}
			.all_elements #info{
				margin:20px auto;
				width:90%;
				text-align: center;
				text-align: -webkit-center;
				line-height: 25px;
				font-size: 1em;
				font-family: Source Sans Pro;
			}
			.text_inputs{
				display: block;
				font-weight: bold;
				color: #FFF;
				font-size: 0.8em;
				text-indent: 5px;
				height:22px;
				width: 100%;
				margin:20px auto;
				border: 1px solid #FFF; 
			    background: rgba(255,255,255,0.0);
			}
			.text_inputs:focus, form textarea:focus{
				outline: none;
				border:1px solid #68b3dd;
			}
			.all_elements form{
				width:300px;
				margin:0px auto;
				text-align: center;
				text-align: -webkit-center;
			}
			form p{
				text-align: left;
				font-family: Corbel;
			}
			form textarea{
				display: block;
				width:100%;
				height: 100px;
				max-height: 400px;
				max-width: 100%;
				line-height: 25px;
				font-weight: bold;
				color: #FFF;
				font-size: 0.9em;
				text-indent: 5px;
				margin:20px auto;
				overflow: hidden;
				border: 1px solid #FFF; 
				font-family: Corbel;
			    background: rgba(255,255,255,0.0);
			}
			form textarea:nth-child(6){
				height:100px;
				max-height: 100px;
			}
			.submit{
				width:110px;
				margin:20px auto;
				padding:12px;
				font-family: Corbel;
				font-size:1em;
				border-radius:5px;
				color:#FFF;
				background-color: #F4855B;
				cursor: pointer;
				outline:none;
				border:none;
			}
			a:link, a:visited, a:hover, a:active{
				color:#FFF;
				text-decoration: none;
			}
			@media screen and (max-width: 600px){
				.all_elements h1{
					margin:20px auto;
					font-size: 1.5em;
				}
				.all_elements{
					width: 90%;
				}
				.all_elements #info{
					width:100%;
					font-size: 0.9em;
				}
				.all_elements form{
					width:90%;
				}
				form p{
					width:100%;
				}
			}
		</style>
	</head>
	<body>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.all_elements hr').animate({width: '100%'}, 700);
			});
		</script>
		<div class="header">
			<div class="container">
				<div class="apply_switch"> Apply for a job</div>
				<form class="search" action="search.php" method="POST">
					<input class = "text_input" type="text" name="search" autocomplete="off" value="Search"  />
				</form>
				<div class="aboutus_switch">
					<p>About Us</p>
				</div>
				<div class="menu-icon">
					<img src="/css/menu.png">
				</div>
				<div class="user-info">
					<img src="">
					<p><?php 
							echo $name;
						?>
					</p>
					<div class="dropdown">
						<a href="/main.php">  <p>Profile</p>  </a>
						<hr></hr>
						<p class="settings_button">Settings</p>
						<hr>
						<p class="logout_button">Log Out</p>
						<form class="logout" method="POST" action="logout.php"> </form>
					</div>					
				</div>
				<a href="upload_track.php"> <div class="upload_switch"> Upolad Track </div> </a>
			</div>
		</div>
		<div class="all_elements">
			<h1>Here you can apply for professor</h1>
			<hr>
			<p id="info">Once you become a professor, you have to give feedback to at least 20 people/day and analize the songs's production quality and the arrangement of the elements as well.</p>
			<form class="apply" method="POST">
				<p> Your Skype ID </p>
				<input class="text_inputs" type="text" name="skype_id" autocomplete="off" >	
				<p> Tell us a bit about yourself</p>
				<textarea id="pers_info"></textarea>
				<p>Links (ex: Soundcloud, Youtube, etc.)</p>
				<textarea id="links"></textarea>
					<button type="submit"class="submit">Send Email</button>
			</form>
			<script type="text/javascript">
				$('.apply').submit(function(event){
					event.preventDefault();
					var skype = $('input[name=skype_id]').val();
					var info = $('#pers_info').val();
					var links = $('#links').val();
						if(!skype.length || !info.length || !links.length)
							alert("All the fields must be completed!");
						else{
							var post = $.post("apply_mail.php", {'skype': skype, 'pers_info':info, 'links': links});
							post.done(function(data){
								alert(data);
							});
						}
				});
			</script> 
		</div>
		<div class="settings">
				<h2>Settings</h2>
				<hr>
				<div class="form">
					<p>Description</p>
					<textarea id="bio" maxlength="100" autofocus>
						<?php
							try{
								$conn2 = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
								$conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								$select2 = $conn2->prepare("SELECT Bio, Location FROM `users` WHERE Username = '$name' ");
								$select2->execute();
								$result = $select2->fetchAll();
									foreach ($result as $data) {
										echo $data['Bio'];
									}

							}
							catch(PDOException $e){
								echo $e->getMessage();
							}
						?>
					</textarea>
					<p>Location</p>
					<input class="text_input" type="text" name="loc" maxlength = "16" autocomplete="off" value="
					<?php
						try{
							foreach ($result as $data) {
								echo $data['Location'];
							}
						}
						catch(PDOException $e){
								echo $e->getMessage();
							}
					?>"
					/>
					<button class="cancel">Cancel</button>
					<button class="save">Save</button>
					<script type="text/javascript">
						$(".save").click(function(){
							var bio = $("#bio").val();
							var location = $("input[name = loc]").val();
							if(bio != $('.about').text() || location != $('.location').text() ){
								var post = $.post("update_profile.php", {'bio': bio, 'location': location});
									post.done(function(data){
										$('.settings').fadeOut(300,function(){
											$('.over_body').fadeOut(300);
											$('.about').text(bio);
												$(".location").remove();
												$('<div class = "location"> </div>').appendTo(".user-profile");
												$('.location').text(location);
												if(location.length == 0)
													$(".location").remove();
												
											$('.location').text(location);
										});
									});

								}
							else{
								$('.settings').fadeOut(300,function(){
									$('.over_body').fadeOut(300);
								});
							}
						});
					</script>
				</div>
			</div>
	</body>
</html>