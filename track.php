<?php
	session_start();
	if(!isset($_REQUEST['song']))
		header("Location: /main.php");
	else{
		try{
			$teacher_img = '';
			$id = $_REQUEST['song'];
			$ok = 0;
			$pdo = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$select = $pdo->prepare("SELECT ID, Name, Artist, Description, Path, PicturePath, ReleaseDate, Teacher, Likes FROM `tracks` ");
			$select->execute();
			$values = $select->fetchAll();
				foreach ($values as $data)
					if($data['ID'] == $id){
						$track_name = $data['Name'];
						$artist = $data['Artist'];
						$description = $data['Description'];
						$img_path = str_replace(' ', '%20', $data['PicturePath']);
						$track_path = $data['Path'];
						$release_date = $data['ReleaseDate'];
						$teacher = $data['Teacher'];
						$likes = $data['Likes'];
						$ok = 1;
					}
			if($ok == 0)
				header("Location: /main.php");
			else{
				$select1 = $pdo->prepare("SELECT ProfilePicture FROM `users` WHERE Username = '$teacher' ");
				$select1->execute();
				$teacher_info = $select1->fetchAll();
					foreach ($teacher_info as $date) 
						$teacher_img = str_replace(' ', '%20', $date['ProfilePicture'] );
				if(empty($teacher_img))
					$teacher_img = "css/poza_profil.png";
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}		
	}
	
	$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name']; 
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $track_name  . '-' . $artist; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>	
		<link rel="stylesheet" type="text/css" href="css/main.css"/>
		<link rel="stylesheet" type="text/css" href="css/profile.css"/>
		<link rel="stylesheet" type="text/css" href="css/other_profile.css">
		<script type="text/javascript" src="js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<script type="text/javascript" src="js/main_animations.js"></script>
		<script type="text/javascript" src="js/pictures_set.js"></script>
		<script type="text/javascript" src="js/wavesurfer.js"></script>
		<script type="text/javascript" src="js/audio_player.js"></script>
		<script type="text/javascript" src="js/reply_submit.js"></script>
	</head>
	<body>
		<div class="over_body"></div>
<!--  HEADER    -->
		<div class="header">
			<div class="container">
					<?php
						try{
							$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
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
				<form class="search" action="php/search.php" method="POST">
					<input class = "text_input" type="text" name="search" autocomplete="off" value="Search"  />
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
						<a href="main.php" > <p>Profile</p> </a>
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
		
			<div class="all_elements">
				<div class="track_container">
					<div class="track_picture">
					</div>
					<div class="track">
					<h2>
						<?php
							echo $track_name;
						?>
					</h2>
					<br>
						<?php
							echo '<a href=user_profile.php?name=' . str_replace(' ', '%20', $artist).'> <p>' . $artist . ' </p> </a>';
						?>
					<br>				
					<div class="play_pause_other">
					</div>
					<div id="waveform"></div>
					<div class="like"><img src="/css/Inima_alba.png" ></div>
					<script>
						$(document).ready(function(){
							var wavesurfer = WaveSurfer.create({
    							container: '#waveform',
   							 	waveColor: '#F4855B',
   							 	progressColor: '#C3623E',
   							 	height:140,
   							 	pixelRatio:1,
   							 	hideScrollbar: true,
   								normalize: true,
   							 	barWidth: 2
						});
						wavesurfer.load(<?php  echo json_encode($track_path)?>);
						$('.teacher_img').css("background-image", "url(" + <?php echo json_encode($teacher_img); ?>  + ")" );
						$('.track_picture').css("background-image", "url(" + <?php echo json_encode($img_path);  ?> + ")");
						$('.play_pause_other').click(function(){
							if ( $(this).css('background-image') == 'url("http://localhost/css/play.png")')
									$(this).css('background-image', 'url(css/pause.png)');
								else
									$(this).css('background-image', 'url(css/play.png)');
							wavesurfer.playPause();
						});
						$('<div style="clear: both;" class="clear> </div>"').appendTo('#waveform');
						//resize event for waveform
						$(window).on('resize', function(){
							$('#waveform canvas').width( $('#waveform').width());
							$('#waveform canvas').height('140px');
						});
						//finish event for song
						wavesurfer.on('finish', function(){
							wavesurfer.stop();
							$('.play_pause_other').css('background-image', 'url(css/play.png)');
						});
						//space event for song
						$(window).unbind('keydown').keydown(function(e){
							//console.log( $(e.target) ==  $('#comment')[0] );
							if( e.which === 32)
								if( $(e.target) ==  $('#comment')[0]){
									e.preventDefault();
									e.stopPropagation();
									wavesurfer.playPause();
									if ( $('.play_pause_other').css('background-image') == 'url("http://localhost/css/play.png")')
										$('.play_pause_other').css('background-image', 'url(css/pause.png)');
									else
										$('.play_pause_other').css('background-image', 'url(css/play.png)');
								}
						});
					});
						

					</script>	
					</div>
			</div>
			<hr>
			<div class="feedback_panel">
				<p><b>Release date:</b> <?php echo $release_date;   ?></p>
				<p><b>Likes:</b> <?php echo $likes;    ?></p>
				<p><?php echo $description; ?></p>
			</div>
			<div class="music">
				<h1>Feedback</h1>
				<div class="comments">
					<?php
						//Load comments 
						try{
							$query = $pdo->prepare("SELECT Comment, ID, Incremented FROM `comments` WHERE Artist = '$artist' ");
							$query->execute();
							$comment = $query->fetchAll();
								foreach ($comment as $data) {
									if($id == $data['ID'] )
										echo '<div class="teacher_info"> <div class="teacher_img"> </div>'. '<a href = user_profile.php?name=' 
											. str_replace(' ', '%20', $teacher) .  '><p class="teacher_name"> '. $teacher
											. '</p></a></div> <span class="comment" data-id=' . (string)$data['Incremented'] .  '> ' 
											. $data['Comment'] . '</span>';
										/*echo '<div class="user_reply_info"><div class="user_reply_image"> </div> ' . '<a href = user_profile.php?name= ' 
											. str_replace(' ', '%20', $teacher) . '<p class="user_reply_name"> '. $teacher
											.'</p></a></div><span class="reply">'. $data['Comment']   . '</span>';*/
										if($name == $artist)
											echo '<div class="reply_initial_button"> Reply </div>';

								}
						}
						catch(PDOException $e){
							echo $e->getMessage();
						}
					?>
				</div>
				<form action = "comment_submit.php" method="POST" style="<?php 
					if($name != $teacher)
						echo 'display: none';
				    ?>" class="form" id="teacher_comment">
				<textarea id="comment"></textarea>
				<button class="comment_submit" type="submit" >Submit</button>
				</form> 
				<script type="text/javascript">
					//Reply submit
					$(document).ready(function(){

								//if(e.which == 13 && reply.length !== 0 && $(e.target) === $('.reply_textarea')[0])
									//alert(reply);				
						//Teacher comment submit
						$('.form').submit(function(event){
							var comment = $('#comment').val();
							if(comment.length !=0) {
								var post = $.post("php/comment_submit.php" , {'comment' : comment, 'id': <?php  echo json_encode($id);  ?>, 'artist' : <?php  echo json_encode($artist);  ?> });
									post.done(function(data){
										if(data == "ok" ){
											$('<div class="teacher_info"> <div class="teacher_img"> </div> ' + '<a href = user_profile.php?name=' + <?php echo json_encode(str_replace(' ', '%20', $teacher)); ?> 
											+'><p class="teacher_name">'+ <?php echo json_encode($teacher); ?> +'</p></a> </div>  <span class="comment"> ' + comment + '</span>').appendTo('.comments');
											$('.teacher_img').css("background-image", "url(" + <?php echo json_encode($teacher_img);  ?> + ")" );
											$('#comment').val('');
										}
											
									});
							}
							event.preventDefault();
						});						
					});

				</script>
			</div>
				</div>
			<!-- Settings   -->
			<div class="settings">
				<h2>Settings</h2>
				<hr>
				<div class="form">
					<p>Description</p>
					<textarea id="bio" maxlength="100" autofocus>
						<?php
							try{
								$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
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