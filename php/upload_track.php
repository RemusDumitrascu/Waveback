<!DOCTYPE html>
<?php
	session_start();
	if(!isset($_SESSION['name']) && !isset($_COOKIE['name']))
		header("Location:/index.php");
	?>
<html>
	<head>
		<title>Upload Track</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>	
		<link rel="stylesheet" type="text/css" href="/css/main.css">
		<link rel="stylesheet" type="text/css" href="/css/profile.css"/>
		<script type="text/javascript" src="/js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="/js/jquery-ui.js"></script>
		<script type="text/javascript" src="/js/main_animations.js"></script>
		<script type="text/javascript" src="/js/pictures_set.js"></script>
		<script type="text/javascript" src="/js/jquery-formdata/jquery.formdata.js"></script>
		<script type="text/javascript" src="/js/track_check.js"></script>
		<style>
		.content_container{
			margin:20px auto;
			width:100%;
			border:1px solid #FFF;
			z-index: 2;
		}
		.main_content{
			position: relative;
			top:40px;
			z-index: 2;
			width:65%;
			margin:0px auto;
		}
		.all_elements{
			margin:49px auto;
		}
		.all_elements p{
			margin:20px auto;
			font-size: 1.2em;
			text-align: center;
			text-align: -webkit-center;
		}
		.error_message{
			position: absolute;
			margin:20px auto;
			width:200px;
			right:0px;
			display: inline;
			font-family: Corbel;
		} 
		.error_message .content{
			z-index: 8;
			display: none;
			float:right;
			width:180px;
			margin-right:20px;
			margin-top:10px;
			padding:15px 10px 15px 10px;
			background-color:#FFF;
			color:#000;
			border:1px solid #000;
			-moz-box-shadow: 0px 0px 10px #000000;
			-webkit-box-shadow: 0px 0px 10px #000000;
			box-shadow: 0px 0px 10px #000000;
		}
		.content p{
			margin:0px auto;
			font-size: 0.9em;
		}
		.content ul{
			padding-left:20px;
		}
		.content img{
			width:18px;
			height: 18px;
			float: right;
			margin-top:5px;
			margin-right:5px;
			cursor: pointer;
		}
		.upload_button, .upload_finish{
			display: block;
			margin:30px auto;
			width:150px;
			padding:5px;
			color:#FFF;
			font-family: Corbel;
			font-size: 1em;
			background-color: #F4855B;
			border-radius:5px;
			cursor: pointer;
			text-align: center;
		}
		.content_container hr{

			width:95%;
			border: 1px solid #FFF;
			border-bottom-width: 0px;
		}
		#info{
			font-size: 0.9em;
			line-height: 22px;
			width:95%;
			text-align: left;
			margin-left:auto;
		}
		a:link, a:visited, a:hover, a:active{
			color:#FFF;
			text-decoration: none;
		}
		.initial_content{
			display: block;
			text-align: center;
			text-align: -webkit-center;
		}
		.second_content{
			display: none;
		}
		.track_image{
			width:180px;
			height: 180px;
			background-size: 180px 180px;
			display: block;
			background-color: #FFF;
			margin:10px auto;
			float: left;
		}
		.track_image img{
			width:20px ;
			height: 20px;
			float:right;
			margin-top:5px;
			margin-right: 5px;
			cursor: pointer;
		}
		.track_form{
			margin:10px auto;
			width:60%;
			display: block;
			float: right;
		}
		.track_form p{
			margin:10px auto;
			margin-top:0px;
			text-align: left;
			font-size: 1em;
		}
		.track_form .text_inputs{
			font-size: 0.9em;
		}
		.track_form textarea{
			display: block;
			width:100%;
			height: 100px;
			max-height: 300px;
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
		.upload_status{
			width:100%;
			display: none;
			padding:5px 0px 5px 0px;
			font-family: Corbel;
			font-size: 0.9em;
			background-color: #2A952A;
			border:1px solid #39ac39;
		}
		.upload_status p{
			margin:10px auto;
		}
		select{
			border-radius: 5px;
			color: #333;
			outline: none;
			padding: 2px;
			padding-right: 15px;
		}
		@media screen and (max-width: 1000px){
			.track_form .text_inputs{
				width:100%;
			}
			.track_form textarea{
				width:98%;
				max-width:98%;
			}
			
		}
		@media screen and (max-width: 850px){
			.track_image{
				float: none;
			}
			.track_form{
				float: none;
				width: 85%;
			}
			.main_content{
				width: 80%;
			} 
		}
		@media screen and (max-width: 600px){
			.main_content{
				width:95%;
			}
			.all_elements p{
				font-size:1.1em;
			}
			.upload_button{
				font-size: 0.9em;
			}
			#info{
				font-size: 0.8em;
			}
			.track_form{
				width:95%;
			}
			.track_form p{
				font-size: 0.9em;
			}
			.track_form .text_inputs{
				font-size: 0.8em;
			}
			.error_message{
				margin:5px auto;
			}
			.error_message .content{
				font-size: 0.9em;
				margin:5px auto;
			}
		}
		@media screen and (max-width: 560px){
			.error_message{
				z-index: 8;
			}
		}
		</style>
	</head>
	<body>
		<div class="over_body"></div>
		<div class="header">
			<div class="container">
				<?php
					try{
						$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
 						$pdo = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
 						$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 						$select = $pdo->prepare("SELECT Professor FROM `users` WHERE Username = '$name' ");
 						$select->execute();
 						$result = $select->fetchAll();
 							foreach ($result as $data) {
 								if((string)$data['Professor'] == '0')
									echo '<a href="apply_for_job.php">' . '<div class="apply_switch"> Apply for a job  </div> </a>';
 							}
					}
					catch(PDOException $e){
						}
				?>
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
						<hr>
						<p class="settings_button">Settings</p>
						<hr>
						<p class="logout_button">Log Out</p>
						<form class="logout" method="POST" action="logout.php"> </form>
					</div>
				</div>
				<div class="upload_switch"> Upload Track </div>
			</div>
		</div>
		<div class="all_elements">
		<div class="error_message">
			<div class="content">
				<img src="/css/x-icon.png">
				
			</div>
			<br><br><br><br><br>
			<div class="content">
				<img src="/css/x-icon.png">
				<ul>
					<li> Maximum size for image is 2 MB </li>
					<li> Image must be a jpeg, png, gif or jpg </li>
				</ul>
			</div>
			<br><br><br><br><br>
		</div>		
		<div class="main_content">	
			<div class="upload_status"></div>
				<div class="content_container">
					<div class="initial_content">
						<p>Choose file to upload</p>
						<div class="upload_button">Choose file </div>
						<input style="display:none;" type="file" id="track_upload" name="file" value="" />
						<hr></hr>
						<p id="info">We only accept mp3s and wavs .</p>
						<p id="info">Maximum size is 1 GB. </p>
						<?php
							try{
	 							foreach ($result as $data)
	 								if( (string)$data['Professor'] == '0'){
	 									echo '<p id="info">Rememeber that whenever you want to upload a song you have to select a teacher from whom you want to receive feedback. </p>'.
	 									'<p id="info"> He is the only one allowed to comment on that specific track. </p>';
	 								}
							}
							catch(PDOException $e){
								echo $e->getMessage();
							}

						?>
						
						
					</div>
					<script>
						$('.upload_button').click(function(){
							document.getElementById('track_upload').click();
						});

					</script>
					<div class="second_content">
						<div class="track_image">
							<img  src="/css/tool.png">
							<input style="display:none;" type="file" id="track_img" name="file" value="" />
						</div>
						<div class="track_form">
								<p>Track Name</p>
								<input class="text_inputs" name="track_name" autocomplete="off" maxlength="150" />
	 							<p>Description</p>
	 							<textarea id="description"></textarea>
	 							
	 							<?php
	 								try{
	 										foreach ($result as $data) 
	 											if((string)$data['Professor'] == '0' ){
	 												echo "<p>Select a teacher </p>" . '<select class="teacher"> ';
	 												$select = $pdo->prepare("SELECT Username FROM `users` WHERE Professor = '1' ");
	 												$select->execute();
	 												$teacher = $select->fetchAll();
	 													foreach ($teacher as $data) {
	 														echo "<option value=' " . $data['Username'] . " '> " . $data['Username']. "</option>";
	 													}
	 												echo "</select>";
	 											}
	 									}
	 								catch(PDOException $e){
										echo $e->getMessage();
										}
	 								?>
	 							<div  class="upload_finish" >Upload Song </div>
						</div>
						<script type="text/javascript">
							//Preview the image
							$('.track_image img').click(function(){
								document.getElementById('track_img').click();
							});
						</script>
					</div>
				</div>
			</div>
		</div>
		<div class="settings">
				<h2>Settings</h2>
				<hr>
				<div class="form">
					<p>Description</p>
					<textarea id="bio" maxlength="100" autofocus>
						<?php
							try{
								$select2 = $pdo->prepare("SELECT Bio, Location FROM `users` WHERE Username = '$name' ");
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
						//submit settings form 
						$(".save").click(function(){
							var bio = $("#bio").val();
							var location = $("input[name = loc]").val();
							if(bio != $('.about').text() || location != $('.location').text() ){
								var post = $.post("update_profile.php", {'bio': bio, 'location': location});
									post.done(function(data){
										$('.settings').fadeOut(300,function(){
											$('.over_body').fadeOut(300);
											$('.about').text(bio);
											if(location.length == 0){
												$(".location").remove();
												$('<div class = "location"></div>').appendTo(".user-profile");
											}
												
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