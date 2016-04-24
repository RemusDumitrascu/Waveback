$(document).ready(function(){
	//Handle the upload of the song
	var song_fd, img_fd,song ,img;
	$('#track_upload').change(function(){
		song = document.getElementById("track_upload");
		song_fd = new FormData();
		song_fd.append("song", song.files[0]);
		//Send track for procesing
		if(typeof song.files[0] !== "undefined"){
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 && xhr.status == 200)
					if(xhr.responseText !== "no"){
						$('.initial_content').hide("slide", {direction: 'up'}, 400, function(){
							$('.content_container').css("border", "none").css("border-top", "1px solid #FFF");
							$('.second_content').show("slide", {direction: 'down'}, 400);
							
						});
						$("input[name=track_name]").val(song.files[0].name.slice(0, song.files[0].name.lastIndexOf('.')));
					}
					else{
						$('#info').animate({
							color: '#E85151'
						}, 300);
					}
			}
			xhr.open("POST", "/php/track_check.php", true);
			xhr.send(song_fd);
		}
	});
	//Handle the upload of the image for the track
	$('#track_img').change(function(){
		img = document.getElementById("track_img");
		img_fd = new FormData();
		img_fd.append("img", img.files[0]);
			if(typeof img.files[0] !== "undefined"){
				var img_post = new XMLHttpRequest();
				img_post.onreadystatechange = function(){
					if(img_post.readyState == 4 && img_post.status == 200)
						if(img_post.responseText == "no"){
							$('.error_message .content:first-child p').remove();
							$("<p>" + "Maximum size for image is 2 MB <br> Image must be a jpeg, png, gif or jpg. " + "</p>").appendTo('.error_message .content:first-child');
							$('.error_message .content:first-child').stop().fadeIn(400).delay(30000).fadeOut(500);
						}
						else{
							var img_preview = new FileReader();
							img_preview.onload = function(e){
							$('.track_image').css('background-image', 'url(' + e.target.result + ')');
							}
							img_preview.readAsDataURL(img.files[0]);
						}
				}
				img_post.open("POST", "/php/track_img_check.php", true );
				img_post.send(img_fd);
				
			}
	});
	$('.upload_finish').click(function(){
		var description = $('#description').val().trim();
		var track_name = $('input[name=track_name]').val().trim();
		var teacher = $('.teacher option:selected').text().trim();
		if(description.length == 0 || track_name.length == 0){
			$('.error_message .content:first-child p').remove();
			$('<p>' + "All fields must be completed. " + "</p>").appendTo('.error_message .content:first-child');
			$('.error_message .content:first-child').stop().fadeIn(400).delay(30000).fadeOut(500);
			
		}
		else{
			
			var fd = new FormData();
			fd.append('song', song.files[0]);
			fd.append('description', description);
			fd.append('track_name', track_name);
			if(typeof teacher != 'undefined')
				fd.append('teacher', teacher);
			if(typeof img != 'undefined')
				fd.append('track_image', img.files[0]);
			//Send the data from the form, along with the song and the image to the server
			var post = $.ajaxFormData({
				url : '/php/track_finish.php',
				data: fd,
				contentType: false,
				processData: false,
				type : "POST",
    			success : function(data){
    				switch(data){
    					case "You already have a song with the same name." :
    						$(".upload_status p").remove();
    						$("<p>" + data + "</p>").appendTo(".upload_status");
    						$('.upload_status').css({
    							'background-color' : '#CB7478',
    							'border' : '1px solid #D94F55'
    						}).show("slide", {direction: 'down'}, 300);
    						break;
    					case "Song uploaded successfully!" :
    						$(".upload_status p").remove();
    						$("<p>" + data + "</p>").appendTo(".upload_status");
    						$('.upload_status').css({
    							'background-color' : '#39ac39',
    							'border' : '1px solid #2A952A'
    						}).show("slide", {direction: 'down'}, 300);
    						break;
    					default :
    						$(".upload_status p").remove();
    						$("<p>" + data + "</p>").appendTo(".upload_status");
    						$('.upload_status').css({
    							'background-color' : '#CB7478',
    							'border' : '1px solid #D94F55'
    						}).show("slide", {direction: 'down'}, 300);
    						break;
    				}
    			}
			});
		}
		
	});	

});