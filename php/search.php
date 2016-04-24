<?php
	session_start();
	if(!isset($_SESSION['name']) && !isset($_COOKIE['name']))
		header("Location: /index.php");
	$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_COOKIE['name'];
	$search_query = $_REQUEST['search'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Search</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>	
		<link rel="stylesheet" type="text/css" href="/css/main.css"/>
		<link rel="stylesheet" type="text/css" href="/css/profile.css"/>
		<script type="text/javascript" src="/js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="/js/jquery-ui.js"></script>
		<script type="text/javascript" src="/js/main_animations.js"></script>
		<script type="text/javascript" src="/js/pictures_set.js"></script>
		<script type="text/javascript" src="/js/search_stuff.js"></script>
		<script type="text/javascript" src="/js/wavesurfer.js"></script>
		<style>
			.header_picker{
				margin:20px auto;
			}
			.header_picker hr{
				border:1px solid #FFF;
				border-top-width: 0px;
			}
			.header_picker span{
				font-size: 1em;
				margin-right:20px;
				cursor: pointer;
			}
			.header_picker span:first-child{
				color: #f4855b;
			}
			/*.header_picker span:hover{
				color: #f4855b;
			}*/
			.users, .teachers, .tracks{
				margin:10px auto;
				padding:10px 0px;
				width:100%;
				z-index: 3;
			}
			.teachers, .tracks{
				display: none;
			}
			.user_search{
				margin:10px auto;
			}
			.user_search img{
				width:100px;
				height: 100px;
				margin-right:30px;
				background-size: 100px 100px;
				display: inline-block;
				border-radius:50%;
			}
			.user_info{
				margin-top:15px;
				display: inline-block;
				vertical-align: top;
				line-height: 30px;
			}
			.name{
				font-size: 1em;
			}
			.search_location{
				font-size: 0.9em;
				font-style: italic;
			}
			.not_found{
				background-color: rgba(0,0,0,0.75);
				width:100%;
				text-align: center;
				text-align: -webkit-center;
			}
			.not_found p{
				margin:0px auto;
				padding:15px 0px;
				font-size: 1.1em;
			}
			.track_name:nth-child(2){
				margin-top:0px;
			}
			.waveform{
				width:100% !important;
			}
			@media screen and (max-width: 600px){
				.header_picker, .users, .teachers, .tracks{
					width:95%;
				}
				.header_picker span{
					font-size: 0.9em;
				}
				.not_found p{
					font-size: 1em;
				}
				.all_elements{
					margin:74px auto;
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
										echo '<a href="apply_for_job.php">' . '<div class="apply_switch"> Apply for a job  </div> </a>';
								}
						}
						catch(PDOException $e){

						}
					?>
				<form class="search" action="search.php" method="POST">
					<input class = "text_input" type="text" name="search" autocomplete="off" value= '<?php echo $search_query;  ?>' required  />
				</form>
				
					<div class="aboutus_switch">
						<p>About Us</p>
					</div>
				<div class="menu-icon">
					<img src="/css/menu.png">
					<div class="dropdown_meniu_1200">
						<p>About Us</p>
						<hr>
						<a href="upload_track.php"><p class="meniu_1000">Upload Track</p></a>
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
			<div class="header_picker">
				<span class="users_switch">Users </span>
				<span class="teachers_switch">Teachers </span>
				<span class="track_switch">Tracks </span>
				<hr></hr>
			</div>
			<div class="users">     </div>
			<div class="teachers">  </div>
			<div class="tracks">   </div>
		</div>
		<script>
			//Load search results
			var users_data, teachers_data, tracks_data, i; var current_track = 0;
			$(document).ready(function(){
				var  waves = new Array();
				var search_query = $('.search .text_input').val();
					//Load users
					var post1 = $.post('/php/search_users.php', {'search': search_query});
					post1.done(function(data){
						$('.user_search').remove();
						$('.not_found').remove();
						$('')
						users_data = JSON.parse(data);
						if(users_data.length == 0)
							$('<div class="not_found"> <p> There are no users with this name. </p> <p> Try searching something else. </p> </div>' ).appendTo('.users');
						console.log(data);
						for( i = 0; i<users_data.length; i++){
							$('<div class="user_search"><img src=' + users_data[i].profile_picture  +'> <div class="user_info"> ' + ' <a target="_blank" href=/user_profile.php?name=' + users_data[i].username.replace(' ', '%20')
								+'><span class="name"> ' + users_data[i].username + '</span> </a><br><span class="search_location">' + users_data[i].location  +'</span> </div></div>').appendTo('.users');
						}
					});
					//Load teachers
					var post2 = $.post('/php/search_teachers.php', {'search': search_query});
					post2.done(function(data){
						teachers_data = JSON.parse(data);
						if(teachers_data.length == 0)
							$('<div class="not_found"> <p> There are no teachers with this name. </p> <p> Try searching something else. </p> </div>' ).appendTo('.teachers');
						for( i = 0; i<teachers_data.length; i++){
							$('<div class="user_search"><img src=' + teachers_data[i].profile_picture  +'> <div class="user_info"> ' + ' <a target="_blank" href=/user_profile.php?name=' + teachers_data[i].username.replace(' ', '%20')
								+'><span class="name"> ' + teachers_data[i].username + '</span> </a><br><span class="search_location">' + teachers_data[i].location  +'</span> </div></div>').appendTo('.teachers');
						}
					});
					//Load tracks
					var post3 = $.post('/php/search_tracks.php', {'search': search_query});
					post3.done(function(data){
						tracks_data = JSON.parse(data);
							if(tracks_data.length == 0)
								$('<div class="not_found"> <p> We couldn`t find any tracks. </p> <p> Try searching something else. </p> </div>' ).appendTo('.tracks');
							//Load songs if you have any
							for (i = 0; i < tracks_data.length  ; i++){
								current_track = 0;
								$(' <div class="track_container" > <div class="track_img" > <img src =' + tracks_data[i].picture.replace(' ', '%20')
									+ '> </div> <a target="_blank" href =  ' + "/track.php?song=" +  tracks_data[i].ID  + '><p class="track_name" > ' +  tracks_data[i].name
									+ '</p> </a> <br> <a target="_blank" href=/user_profile.php?name=' + tracks_data[i].artist.replace(' ', '%20') + '> <p class="track_name">' + tracks_data[i].artist + '</p> </a> <br><div class = "play_pause">  </div>'
									+ '<div class="waveform"' + ' id=' + tracks_data[i].ID  + ' ></div> <div class="like_main"> <img src="../css/Inima_alba.png"> </div> <div class="clear" > </div> </div>' ).prependTo(".tracks");
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
								waves[i].load(tracks_data[i].path);
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
								$(this).css('background-image', 'url(/css/pause.png)');
							else
								$(this).css('background-image', 'url(/css/play.png)');
									waves[index].playPause();
						});
					});
				//Submit event
				$('.search').submit(function(event){
					var  waves = new Array();
					event.preventDefault();
					var search_query = $('.search .text_input').val();
					var post1 = $.post('/php/search_users.php', {'search': search_query});
					post1.done(function(data){
						$('.user_search').remove();
						$('.not_found').remove();
						$('.track_container').remove();
						users_data = JSON.parse(data);
						if(users_data.length == 0)
							$('<div class="not_found"> <p> There are no users with this name. </p> <p> Try searching something else. </p> </div>' ).appendTo('.users');
						console.log(data);
						for( i = 0; i<users_data.length; i++){
							$('<div class="user_search"><img src=' + users_data[i].profile_picture  +'> <div class="user_info"> ' + ' <a target="_blank" href=/user_profile.php?name=' + users_data[i].username.replace(' ', '%20')
								+'><span class="name"> ' + users_data[i].username + '</span> </a><br><span class="search_location">' + users_data[i].location  +'</span> </div></div>').appendTo('.users');
						}
						
					});
					var post2 = $.post('/php/search_teachers.php', {'search': search_query});
					post2.done(function(data){
						teachers_data = JSON.parse(data);
						if(teachers_data.length == 0)
							$('<div class="not_found"> <p> There are no teachers with this name. </p> <p> Try searching something else. </p> </div>' ).appendTo('.teachers');
						for( i = 0; i<teachers_data.length; i++){
							$('<div class="user_search"><img src=' + teachers_data[i].profile_picture  +'> <div class="user_info"> ' + ' <a target="_blank" href=/user_profile.php?name=' + teachers_data[i].username.replace(' ', '%20')
								+'><span class="name"> ' + teachers_data[i].username + '</span> </a><br><span class="search_location">' + teachers_data[i].location  +'</span> </div></div>').appendTo('.teachers');
						}
					});
					//Load Tracks
					var post3 = $.post('/php/search_tracks.php', {'search': search_query});
					post3.done(function(data){
						tracks_data = JSON.parse(data);
							if(tracks_data.length == 0)
								$('<div class="not_found"> <p> We couldn`t find any tracks. </p> <p> Try searching something else. </p> </div>' ).appendTo('.tracks');
							//Load songs if you have any
							for (i = 0; i < tracks_data.length  ; i++){
								current_track = 0;
								$(' <div class="track_container" > <div class="track_img" > <img src = ' + tracks_data[i].picture.replace(' ', '%20')
									+ '> </div> <a target="_blank" href =  ' + "/track.php?song=" +  tracks_data[i].ID  + '><p class= "track_name" > ' +  tracks_data[i].name
									+ '</p> </a> <br> <a target="_blank" href=/user_profile.php?name=' + tracks_data[i].artist.replace(' ', '%20') + '> <p class="track_name">' + tracks_data[i].artist + '</p> </a> <br><div class = "play_pause">  </div>'
									+ '<div class="waveform"' + ' id= ' + tracks_data[i].ID  + ' ></div>  <div class="clear" > </div> </div>' ).prependTo(".tracks");
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
								waves[i].load(tracks_data[i].path);
							}
					});

				});
					$(window).on('resize', function(){
						$('.waveform canvas').width( $('.waveform').width());
						$('.waveform canvas').height('100px');
					});

					//PlayPause with space
					$(window).unbind('keydown').keydown(function(e){
						if(e.which === 32 )
							if( $('.form .text_input')[0] != e.target && $('#bio')[0] != e.target){
								e.preventDefault();
								e.stopPropagation();
								waves[current_track].playPause();
								for(var j = 0 ; j< tracks_data.length; j++)
									if(!waves[j].backend.isPaused() && j !== current_track){
										waves[j].pause();
										$('.play_pause').eq(j).css('background-image', 'url(css/play.png)');
									}
							if( $('.play_pause').eq(current_track).css('background-image') == 'url("http://localhost/css/play.png")')
								$('.play_pause').eq(current_track).css('background-image', 'url(/css/pause.png)');
							else
								$('.play_pause').eq(current_track).css('background-image', 'url(/css/play.png)');
						}
					});		
			});

		</script>
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