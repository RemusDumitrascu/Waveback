<?php
	session_start();
	if(!isset($_SESSION['name']) && !isset($_COOKIE['name']))
		header("Location: index.php");
	$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
	?>
<!DOCTYPE html>
<html>
	<head>
		<title>Your Profile</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>	
		<link rel="stylesheet" type="text/css" href="css/main.css"/>
		<link rel="stylesheet" type="text/css" href="css/profile.css"/>
		<script type="text/javascript" src="js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<script type="text/javascript" src="js/main_animations.js"></script>
		<script type="text/javascript" src="js/pictures_set.js"></script>
		<script type="text/javascript" src="js/wavesurfer.js"></script>
		<script type="text/javascript" src="js/load_personal_tracks.js"></script>
		<script type="text/javascript" src="js/logout.js"></script>
	</head>
	<body>
		<div class="over_body"></div>
		<div class="pictures_info">
			<div class="error_img"> </div>
			<ul>
				<li>The image has to have less than 2 MB</li>
				<li>We only accept images that are a jpg, jpeg, png or gif</li>
			<ul>
		</div>		
		<!--  HEADER    -->
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
				<form class="search" action="php/search.php" method="GET">
					<input class = "text_input" type="text" name="search" autocomplete="off" value="Search" required />
				</form>
				
					<div class="aboutus_switch">
						<p>About Us</p>
					</div>
				<div class="menu-icon">
					<img src="css/menu.png">
					<div class="dropdown_meniu_1200">
						<p>About Us</p>
						<hr>
						<a href="php/upload_track.php"><p class="meniu_1000">Upload Track</p></a>
						<?php
							foreach ($value as $data)
								if((string)$data['Professor'] == '0')
									echo '<hr><a href="php/apply_for_job.php"><p class="meniu_1000">Apply for a job</p></a>';
						?>
					</div>
				</div>
				<div class="user-info">
					<img src="">
					<p><?php echo $name;  ?> </p>
					<div class="dropdown">
						<p>Profile</p>
						<hr>
						<p class="settings_button">Settings</p>
						<hr>
						<p class="logout_button">Log Out</p>
						<form class="logout" method="POST" action="php/logout.php"> </form>
					</div>
				</div>
				<a href="php/upload_track.php"><div class="upload_switch"> Upload Track </div> </a>
			</div>
		</div>
		<!--  ALL ELEMENTS   -->
			<div class="all_elements">
				<div class="cover_picture">
					<input style="display:none;" type="file" id="cover_img_upload" name="file" />
					<div class="user-profile">
						<div class="profile_picture">
							<div class="upload">
								<input style="display:none;" type="file" id="profile_img_upload" name="file" />
								<img src="css/tool.png" >	
							</div>
							<script type="text/javascript">
								function upload_cover(){
									document.getElementById('cover_img_upload').click();
									}
								$('#profile_img_upload').change(function(){
									var img = document.getElementById('profile_img_upload');
									var fd = new FormData();
									fd.append('img', img.files[0]);
										var post = new XMLHttpRequest();
										post.onreadystatechange = function(){
											if(post.readyState == 4 && post.status == 200)
												if(post.responseText == "no" ){
													var preview1 = new FileReader();
													$('.pictures_info ul li:first-child').text("The image has to have less than 2 MB");
													preview1.onload = function(e){
														$('.error_img').css("border-radius", "50%").css('background-image', "url(" + e.target.result  + ")");
													}
													preview1.readAsDataURL(img.files[0]);
													$('.over_body').fadeIn(200, function(){
														$('.pictures_info').fadeIn(200);
													});
												}
												else{
													//alert(post.responseText);
													var preview = new FileReader();
													preview.onload = function(e){
														$('.profile_picture').css("background-image","url(" + e.target.result + ")");
														$('.user-info img').attr({src: e.target.result });
													}
													preview.readAsDataURL(img.files[0]);	
												}
											}
										post.open("POST","php/profile_picture.php", true);
										post.send(fd);
								});
								$('#cover_img_upload').change(function(){
									var cover = document.getElementById('cover_img_upload');
									var cover_fd = new FormData();
									cover_fd.append('img', cover.files[0]);
										var post1 = new XMLHttpRequest();
										post1.onreadystatechange = function(){
											if(post1.readyState == 4 && post1.status == 200)
												if(post1.responseText == "no"){
													var preview_c1 = new FileReader();
													$('.pictures_info ul li:first-child').text("The image has to have less than 3 MB");
													preview_c1.onload = function(){
														$('.error_img').css("border-radius", "0px").css('background-image', "url(" + e.target.result  + ")");
													}
													$('.over_body').fadeIn(200, function(){
														$('.pictures_info').fadeIn(200);
													});
													preview_c1.readAsDataURL(cover.files[0]);
												}
												else{
													var preview_c2 = new FileReader();
													preview_c2.onload = function(e){
														$('.cover_picture').css("background-image", "url(" + e.target.result + ")");
													}
													preview_c2.readAsDataURL(cover.files[0]);
												}
										}
										post1.open("POST", "php/cover_picture.php", true);
										post1.send(cover_fd);
								});
							</script>				
						</div>
						<div class="username"> 
							<?php 
								echo $name;
							?>
						</div><br>
							<?php
								try{
									$conn = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
									$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									$select = $conn->prepare("SELECT Location, Bio FROM users WHERE Username = '$name' ");
									$select->execute();
									$result = $select->fetchAll();
											foreach ($result as $data)
													if(strlen((string)$data['Location']))
														echo '<div class = "location">' . (string)$data['Location'] . '</div>';
								
									}
								catch(PDOException $e){
									echo $e->getMessage();
								}
							?>
					</div>
					<div class="upload_cover_container">
						<img onclick = "upload_cover()" class="cover_upload" src="css/tool.png">
					</div>
				</div>
			<div class="sub_meniu">
					<p class="liked_switch">Liked</p>
					<p class="tracks_switch">Tracks</p><br>
					<hr>
			</div>
			<div class="music">
				<div class="personal_songs">

				</div>
				<div class="liked_songs">
				</div>
			</div>
			<div class="feedback_panel">
					<p class="about">
						<?php
							try{
								foreach ($result as $data) {
									echo (string)$data['Bio'];
								}
							}
							catch(PDOException $e){
									echo $e->getMessage();
								}
						?>
					</p>
					<?php
						foreach ($value as $data) {
							if( (string)$data['Professor'] == '0'){
								echo " <a href=php/all_requests-feedbacks.php> <h2> Recent feedback </h2> </a>";
								try{
									$query = $pdo->prepare("SELECT Comment, ID FROM `comments` WHERE Artist ='$name' ORDER BY Incremented DESC LIMIT 3 ");
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
															if(empty($teacher_picture))
																$teacher_picture = 'css/poza_profil.png';
														}

													
												}
										echo "<div class='feedback'> <img class='teacher_image' src = ". $teacher_picture
											 ." ><div class='info' ><span class='on_name' > On <a href = track.php?song=" . $id . "> " . $track_name_feedback 
											 . "</span></a> <br> <span class='comment'> " . $comment . ' </div> </div>';
										}
									
								}
								catch(PDOException $e){
									echo "<p>Couldn't  connect to server </p>";
								}
							}
							else{
								echo "<a href='php/all_requests-feedbacks.php'> <h2> Recent requests </h2> </a>";
								try{
									$conn1 = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
									$conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									$select1 = $conn1->prepare("SELECT Name, Artist, Checked, PicturePath, ID FROM `tracks` WHERE Teacher = '$name' ORDER BY ReleaseDate DESC");
									$select1->execute();
									$request = $select1->fetchAll();
									$k = 1;
										foreach($request as $data) {
											if( (string)$data['Checked'] == '0'){
												if($k <= 3)
													echo '<div class="request">' . '<img class="track_image" src= ' . str_replace(' ', '%20', (string)$data['PicturePath']) . ' >'.
														'<div class="info"> <span class="from"> From '. $data['Artist'] . "</span> <br>" .
														"<span class='on'> On <a href = track.php?song=" . $data['ID'] . '>' .  $data['Name'] 
														. "</a> </span> </div> </div> <hr>" ;
												$k++;
											}
										}
								}
								catch(PDOException $e){

								}
							}
						}
					?>
				</div>
			</div>
			<div class="img_info">

			</div>
			<!--  SETTINGS  -->
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