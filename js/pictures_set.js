$(document).ready(function(){
	var basic = "/css/poza_profil.png";
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200)
			if(xhr.responseText == "no"){
				$('.profile_picture').css("background-image", "url(" + basic + ")");
				$('.user-info img').attr({src: basic });
			}
			else{
				$('.profile_picture').css("background-image", "url(" + xhr.responseText + ")");
				$('.user-info img').attr({src: xhr.responseText });
			}
		}
	xhr.open("GET", "/php/user_pp_set.php", true);
	xhr.send();
	var cover = new XMLHttpRequest();
	cover.onreadystatechange = function(){
		if(cover.readyState == 4 && cover.status == 200)
			if(cover.responseText == "no")
				$('.cover_picture').css("background-image", "url()");
			else{
				$('.cover_picture').css("background-image", "url(" + cover.responseText + ")");
			}
	}
	cover.open("GET", "/php/user_cp_set.php", true);
	cover.send();
});		