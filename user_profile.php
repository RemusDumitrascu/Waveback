<?php
	session_start();
	if(!isset($_SESSION['name']) && !isset($_COOKIE['name']))
		header("Location: index.php");
	$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
	$username = $_REQUEST['name'];
	if(!isset($username) )
		header('Location: main.php');
	else{
		try{
			$ok = 0;
			$conn3 = new PDO("mysql:host=localhost; dbname=mydb", "root", "ilikeit2");
			$conn3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$profile_query = $conn3->prepare("SELECT Username, Location, Bio, ProfilePicture, CoverPicture FROM `users` ");
			$profile_query->execute();
			$user_data = $profile_query->fetchAll();
				foreach ($user_data as $value)
					if($value['Username'] == $username){
						$bio = $value['Bio'];
						$location = $value['Location'];
						$profile_picture = str_replace(' ', '%20', $value['ProfilePicture']);
						$cover_picture = str_replace(' ', '%20', $value['CoverPicture']);
						$ok = 1;
					}
			if($ok == 0)
				header('Location: main.php');
		}
		catch(PDOException $e){

			}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $username . "'s profile"; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>	
		<link rel="stylesheet" type="text/css" href="css/main.css"/>
		<link rel="stylesheet" type="text/css" href="css/profile.css"/>
		<script type="text/javascript" src="js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<script type="text/javascript" src="js/main_animations.js"></script>
		<script type="text/javascript" src="js/wavesurfer.js"></script>
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
							$query = $pdo->prepare("SELECT Professor, ProfilePicture FROM `users` WHERE Username = '$name' ");
							$query->execute();
							$value = $query->fetchAll();
								foreach ($value as $data) {
									$pp = str_replace(' ', '%20', $data['ProfilePicture']);
									if((string)$data['Professor'] == '0')
										echo '<a href="php/apply_for_job.php">' . '<div class="apply_switch"> Apply for a job  </div> </a>';
								}
							if(empty($pp))
								$pp = 'css/poza_profil.png';
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
					<img src=" <?php  echo $pp;  ?>">
					<p><?php echo $name;  ?> </p>
					<div class="dropdown">
						<a href="main.php"> <p>Profile</p> </a>
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
							
						</div>
						<div class="username"> 
							<?php 
								echo $username;
							?>
						</div><br>
						
							<?php
								if(strlen($location) != 0)
									echo '<div class="user_location">' .  $location . '</div>';
							?>
					</div>
					<div class="upload_cover_container">
					</div>
				</div>
			<div class="sub_meniu">
					<p class="tracks_switch">Tracks</p><br>
					<hr>
			</div>
			<div class="feedback_panel">
					<p class="about">
						<?php
							echo $bio;
						?>
					</p>
				</div>
			<div class="music">
				<div class="personal_songs">

				</div>
			</div>
			<div class="img_info">

			</div>
			</div>
			<script>
				//Setting the cover picture and the profile picture up
				var basic = 'css/poza_profil.png';
				var profile_picture = <?php echo json_encode($profile_picture); ?> ;
				var cover_picture = <?php echo json_encode($cover_picture); ?>;
				if(profile_picture.length == 0)
					$('.profile_picture').css("background-image", "url(" + basic + ")" );
				else
					$('.profile_picture').css("background-image", "url(" + profile_picture + ")" );
				if(cover_picture.length !== 0)
					$('.cover_picture').css("background-image", "url(" + cover_picture + ")" );
				//Load tracks for the specific user
				var username = <?php echo json_encode($username);  ?>;
				var waves = new Array(); var tracks_data; var current_track; var i;
				var post = $.post('php/load_user_tracks.php', {'username': username});
					post.done(function(data){
						tracks_data = JSON.parse(data);
						//Append message if you have no tracks
						if(tracks_data.length == 0)
							$(' <div class="track_container" ><p class="no_tracks">' + "This user hasn't uploaded any tracks yet. " +'</p></div>').prependTo(".personal_songs");
						//Load songs if you have any tracks
							for (i = 0; i < tracks_data.length  ; i++) {
								//console.log(i);
								current_track = 0;
								$(' <div class="track_container" > <div class="track_img" > <img src = ' + tracks_data[i].PicturePath.replace(' ', '%20')
									+ '> </div> <a href =  ' + "track.php?song=" +  tracks_data[i].ID  + '><p class= "track_name" > ' +  tracks_data[i].Name
									+ '</p> </a> <br> <div class = "play_pause">  </div>'
									+ '<div class="waveform"' + ' id= ' + tracks_data[i].ID  + ' ></div> <div class="like_main"> <img src="/css/Inima_alba.png"> </div>  <div class="clear" > </div></div>' ).appendTo(".personal_songs");
								var selector = '#\\33 ' + String(tracks_data[i].ID ).slice(1, String(tracks_data[i].ID ).length);
								waves[i] = WaveSurfer.create({
									container: selector,
									waveColor: '#F4855B',
			   						progressColor: '#C3623E',
					   				height:100,
					   				pixelRatio:1,
					   				hideScrollbar: true,
					   				normalize: true,
					   				barWidth: 2
								});
								waves[i].load(tracks_data[i].Path);
								console.log(i);
							}
							//Click event for play-pause
						$('.play_pause').click(function(){
							var index = $('.play_pause').index(this);
							current_track = index;
							for(var j = 0 ; j< tracks_data.length; j++)
								if(!waves[j].backend.isPaused() && j != index){
									waves[j].pause();
									$('.play_pause').eq(j).css('background-image', 'url(css/play.png)');
								}
								if( $(this).css('background-image') == 'url("http://localhost/css/play.png")')
									$(this).css('background-image', 'url(css/pause.png)');
								else
									$(this).css('background-image', 'url(css/play.png)');
							waves[index].playPause();
						});
					//PlayPause with space
						$(window).unbind('keydown').keydown(function(e){
								if(e.which === 32){
									e.preventDefault();
									e.stopPropagation();
									waves[current_track].playPause();
									for(var j = 0 ; j< tracks_data.length; j++)
										if(!waves[j].backend.isPaused() && j !== current_track){
											waves[j].pause();
											$('.play_pause').eq(j).css('background-image', 'url(css/play.png)');
										}
								if( $('.play_pause').eq(current_track).css('background-image') == 'url("http://localhost/css/play.png")')
									$('.play_pause').eq(current_track).css('background-image', 'url(css/pause.png)');
								else
									$('.play_pause').eq(current_track).css('background-image', 'url(css/play.png)');
								}
							});	
					$(window).on('resize', function(){
						$('.waveform canvas').width( $('.waveform').width());
						$('.waveform canvas').height('100px');
					});										
				});	
			</script>
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