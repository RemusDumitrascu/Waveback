$(document).ready(function(){
	var hover = true;
	//$('.search').css("line-height", $('.upload_switch').outerHeight() );
	$('.over_body').click(function(){
		$('.settings').fadeOut(300,function(){
			$('.over_body').fadeOut(300);
		});
		$('.pictures_info').fadeOut(300,function(){
			$('.over_body').fadeOut(300);
		});
	});
	$(".apply_switch, .aboutus_switch, .menu-icon, .user-info, .upload_switch").hover(function(){
		$(this).stop().animate({backgroundColor: '#42718c'}, 300);
	},
	function(){
		$(this).stop().animate({backgroundColor: '#5b9cc1'}, 300);
	});
	//Click for uploading profile image
		$('.profile_picture .upload').click(function(){
			document.getElementById('profile_img_upload').click();
		});
	var dd_height = $('.user-info').width( );
	$('.dropdown').css("width", dd_height);

	$('.settings_button').click(function(){
		$('.over_body').fadeIn(500,function(){

		});
		$('.settings').fadeIn(500,function(){
		});
	})
	$('.cancel').click(function(){
		$('.settings').fadeOut(300,function(){
			$('.over_body').fadeOut(300);
		});
	})
	//Delete search text on focus
	$('.search .text_input').focus(function(){
		$('.search .text_input').val('');
	});
	//Closing messages
	$('.content img').click(function(){
		var index = $('.error_message .content img').index(this);
		$('.content').eq(index).stop().fadeOut(100);
	});
	/*$('.logout_button').click(function(){
		$('.logout').submit(function(){
		});
	});*/
});