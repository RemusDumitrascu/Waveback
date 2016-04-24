<?php
	session_start();
	if(!isset($_SESSION['name']) && !isset($_COOKIE['name']))
		header("Location: /index.php");
	$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $name."'s requests";  ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>	
		<link rel="stylesheet" type="text/css" href="/css/main.css"/>
		<link rel="stylesheet" type="text/css" href="/css/profile.css"/>
		<script type="text/javascript" src="/js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="/js/jquery-ui.js"></script>
		<script type="text/javascript" src="/js/main_animations.js"></script>
		<script type="text/javascript" src="/js/pictures_set.js"></script>
		<style type="text/css">
			.header_info h2{
				color: #f4855b;
			}
			.header_info h4{
				line-height: 25px;
				font-weight: normal;
			}
			.header_info h2, h4{
				width:100%;
				margin:15px auto;
			}
			.header_info hr{
				border:1px solid #FFF;
				border-top-width: 0px;
				width:100%;
			}
			.request_all, .feedback_all{
				width:100%;
				margin:10px auto;
				font-size: 1em;
			}
			.request_all span, .feedback_all span{
				overflow: hidden;
			}
			.request_all img, .feedback_all img{
				display: inline-block;
				width: 100px;
				height: 100px;
				background-color: #FFF;
				background-size: 100px 100px;
				margin-right: 30px;
				border:none;
			}
			.feedback_all img{
				border-radius:50%;
			}
			.request_info, .feedback_info{
				margin-top:15px;
				display: inline-block;
				vertical-align: top;
				line-height: 30px;
			}
			.feedback_info{
				width:calc(100% - 150px);
				max-width: calc(100% - 150px);
			}
			.feedback_info .comment{
				white-space: normal;
				line-height: 30px;
				text-indent: 0px;
			}
			.on a:link, .on a:visited, .on a:hover, .on a:active{
				color:#5b9cc1;
				font-size: 0.9em;
				font-weight: bold;
				text-decoration: none;
			}
			@media screen and (max-width: 600px){
				.all_elements{
					margin:69px auto;
				}
				.header_info{
					width:95%;
					margin:0px auto;
				}
				.header_info h4{
					font-size: 0.9em;
				}
				.request_all, .feedback_all{
					width: 95%;
					font-size: 0.9em;
				}
				.feedback_all img, .request_all img{
					display: block;
					margin:0px auto;
				}
				.feedback_info, .request_info{
					margin:20px auto;
					display: block;
					text-align: center;
					text-align: -webkit-center;
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
							$pdo = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
							$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$query = $pdo->prepare("SELECT Professor FROM `users` WHERE Username = '$name' ");
							$query->execute();
							$value = $query->fetchAll();
								foreach ($value as $data) {
									if((string)$data['Professor'] == '0')
										echo '<a href="php/apply_for_job.php">' . '<div class="apply_switch"> Apply for a job  </div> </a>';
								}
						}
						catch(PDOException $e){

						}
					?>
				<form class="search" action="search.php" method="POST">
					<input class = "text_input" type="text" name="search" autocomplete="off" value="Search" required  />
				</form>
				
					<div class="aboutus_switch">
						<p>About Us</p>
					</div>
				<div class="menu-icon">
					<img src="/css/menu.png">
					<div class="dropdown_meniu_1200">
						<p>About Us</p>
						<hr>
						<a href="php/upload_track.php"><p class="meniu_1000">Upload Track</p></a>
						<?php
							foreach ($value as $data)
								if((string)$data['Professor'] == '0')
									echo '<hr><a href="apply_for_job.php"><p class="meniu_1000">Apply for a job</p></a>';
						?>
					</div>
				</div>
				<div class="user-info">
					<img src="">
					<p><?php echo $name;  ?> </p>
					<div class="dropdown">
						<a href="/main.php"> <p>Profile</p> </a>
						<hr>
						<p class="settings_button">Settings</p>
						<hr>
						<p class="logout_button">Log Out</p>
						<form class="logout" method="POST" action="logout.php"> </form>
					</div>
				</div>
				<a href="upload_track.php"><div class="upload_switch"> Upload Track </div> </a>
			</div>
		</div>
		<!--  ALL ELEMENTS   -->
		<div class="all_elements">
			<div class="header_info">
				<?php
					foreach ($value as $data)
						if((string)$data['Professor'] == '0')
							echo '<h2>Feedbacks</h2> ' . '<h4>Here you will find all the feedbacks you received.</h4>';
						else
							echo '<h2>Requests</h2> ' . '<h4>Here you will find all the requests you received.</h4>';
				?>
				<hr></hr>
			</div>
			<?php
				//Load all the requests
				foreach ($value as $data)
					if((string)$data['Professor'] == '1'){
						$query = $pdo->prepare("SELECT Name, Artist, PicturePath, ID, Checked FROM `tracks` WHERE Teacher = '$name' ORDER BY ReleaseDate DESC ");
						$query->execute();
						$request = $query->fetchAll();
							foreach ($request as $req) {
								echo '<div class="request_all"> <img src='. str_replace(' ', '%20', $req['PicturePath']) .'><div class="request_info"> '
									.'<span class="from"> From <a href=/user_profile.php?name='. str_replace(' ', '%20', $req['Artist']) . '> '. $req['Artist']
									.'</span> </a><br>'. "<span class='on'> On <a href = /track.php?song=" . $req['ID'] . '>' .  $req['Name']
									.'</a> </span> </div> </div>';
						}
					}
					else{
						$query = $pdo->prepare("SELECT Comment, ID FROM `comments` WHERE Artist ='$name' ORDER BY Incremented DESC ");
						$query->execute();
						$values = $query->fetchAll();
							foreach ($values as $data) {
								$id = (string)$data['ID'];
								$comment = (string)$data['Comment'];
								$query = $pdo->prepare("SELECT  Name, Teacher FROM `tracks` WHERE ID = '$id' ");
								$query->execute();
								$track_data = $query->fetchAll();
									foreach ($track_data as $key) {
										$teacher = $key['Teacher'];
										$track_name_feedback = $key['Name'];
										$query = $pdo->prepare("SELECT ProfilePicture FROM `users` WHERE Username = '$teacher'  ");
										$query->execute();
											foreach ($query->fetchAll() as $date) {
												$teacher_picture = str_replace(' ', '%20', $date['ProfilePicture']);
											}
									if(empty($teacher_picture))
										$teacher_picture = '/css/poza_profil.png';			
									}
								echo "<a href=/user_profile.php?name=". str_replace(' ', '%20', $teacher) . "><div class='feedback_all'> <img src = ". $teacher_picture
									 ." ></a> <div class='feedback_info'> <span class='on' > On <a href = /track.php?song=" . $id . "> " . $track_name_feedback 
									 . "</span></a> <br> <span class='comment'> " . $comment . ' </div> </div>';
							}
					}
			?>		
		</div>
		<!-- SETTINGS -->
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
								var post = $.post("php/update_profile.php", {'bio': bio, 'location': location});
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