 //This will load your own tracks on your profile
 var waves = new Array(); var current_track = 0; var tracks_data; var i;	
 	$(document).ready(function(){
 		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				tracks_data = JSON.parse(xhr.responseText);
				//Append message if you have no tracks
				if(tracks_data.length == 0)
					$(' <div class="track_container" ><p class="no_tracks">' + "You haven't uploaded any tracks yet. "
						+'</p><p class="no_tracks">Click  <a href="php/upload_track.php"> ' 
						+ "here </a> to get started." +' </p></div>').prependTo(".personal_songs");
								//Load songs if you have any tracks
					for (i = 0; i < tracks_data.length  ; i++){
						current_track = 0;
						$(' <div class="track_container" > <div class="track_img" > <img src = ' + tracks_data[i].PicturePath.replace(/ /g, '%20')
							+ '> </div> <a href =  ' + "track.php?song=" +  tracks_data[i].ID  + '><p class= "track_name" > ' +  tracks_data[i].Name
							+ '</p> </a> <br> <div class = "play_pause">  </div>'
							+ '<div class="waveform"' + ' id= ' + tracks_data[i].ID  + ' > </div> <div class="like_main"> <img src="../css/Inima_alba.png"> </div> </div>  <div class="clear" > </div>' ).appendTo(".personal_songs");
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
						//console.log(i);
					}
					if(typeof waves[current_track] !== 'undefined'){
						waves[current_track].on('finish', function(){
 							console.log(waves.length)
 							if(current_track == waves.length - 1){
 								waves[current_track].stop();
 								$('.play_pause').eq(current_track).css('background-image', 'url(css/play.png)');
 								console.log(current_track);
 							}
 							else{
 								waves[current_track].stop();
 								waves[current_track + 1].play();
 								$('.play_pause').eq(current_track).css('background-image', 'url(css/play.png)');
 								$('.play_pause').eq(current_track + 1).css('background-image', 'url(css/pause.png)');
 								current_track++;
 							}
 						});
					}	
			}		
					$(window).on('resize', function(){
						$('.waveform canvas').width( $('.waveform').width());
						$('.waveform canvas').height('100px');
					});
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
								$('.play_pause').eq(current_track).css('background-image', 'url(css/pause.png)');
							else
								$('.play_pause').eq(current_track).css('background-image', 'url(css/play.png)');
						}
					});	
		}

		xhr.open("GET", '/php/loading_pers_tracks.php', true);
		xhr.send();	
	});
 