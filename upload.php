<!DOCTYPE html>
<html>
	<head>
		<title>Upload</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>	
		<script type="text/javascript" src="js/jquery-1.11.3.js"></script>
		<style>
			html{
				width:100%;
				height: 100%;
			}
			body{
				font-family: Corbel;
				color:#FFF;
				padding:0px;
				margin:0px auto;
				background: url("css/congruent_outline.png");
				background-repeat: repeat;
				text-align: center;
				text-align: -webkit-center;
			}
			.upload_button{
				margin:30px auto;
				cursor: pointer;
				width:100px;
				border-radius:5px;
				background-color: #F4855B;
				padding:10px 12px 10px 12px;
				overflow: hidden;
				text-align: center;
				text-align: -webkit-center;
			}
			.upload_button input{
				display: none;
				overflow: hidden;
				position: absolute;
				cursor: pointer;
				top:0;
				left:0;
				opacity: 0;
			    -moz-opacity: 0;
			    filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);
				height: 100%;
			}
			.img_container{
				margin:0px auto;
				width:250px;
				height: 250px;
				border:1px solid #FFF;
				background-color: transparent;
			}
			.img_container img{
				width:100%;
				height: 100%;
			}
			.overbody{
				position: absolute;
				top:0px;
				left:0px;
				height: 100%;
				width: 100%;
				background-color: #404040;
				opacity: 0.5;
				z-index: 10;
			}
			.alert_box{

			}
		</style>
	</head>
	<body>
		<div class="overbody"></div>
		<p>Change your profile picture</p>
		<div onclick="upload_img()" class="upload_button">
				<input type="file" id="profile_img_upload" name="file" size="10"/>
				Upload Image
		</div>
		<script type="text/javascript">
			function upload_img(){
				document.getElementById('profile_img_upload').click();
			}
		</script>
		<div class="img_container">
			<img src="" alt="Select an image for your profile">
		</div>
		<script type="text/javascript">
			$('.overbody').click(function(){
				$('.overbody').fadeOut(500);
			});
			$('#profile_img_upload').change(function(){
				var img = document.getElementById("profile_img_upload");
					//Output the name and the size of the image
					if('files' in img)
						if('name' in img.files[0] && 'size' in img.files[0])
							$("<p>" + img.files[0].size/1000 + " KB</p><p>" + img.files[0].name + "</p>").appendTo("body");
				//Preview the image
				var preview = new FileReader();
				preview.onload = function(e){
					$('.img_container img').attr({'src': e.target.result});
				}
				preview.readAsDataURL(img.files[0]);

				//Send image to server
				var fd = new FormData();
				fd.append("img", img.files[0]);
				var post = new XMLHttpRequest();
				post.onreadystatechange = function(){
					if(post.readyState == 4 && post.status == 200)
						if(post.responseText)
							alert(post.responseText);
				}
				post.open("POST", "php/profile_picture.php", true);
				post.send(fd);
			});
		</script>
	</body>
</html>