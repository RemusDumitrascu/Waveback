
$(document).ready(function(){
	var slide = {
		'users': 1,
		'teachers': 0,
		'tracks':0
	}
	var hover = new Array(0, 1, 1);
	$('.teachers_switch').click(function(){
		if(!slide.teachers)
			if(slide.users){
				hover[1] = 0;
				hover[0] = hover[2] = 1;
				slide.users = slide.tracks = 0;
				$('.teachers_switch').css("color", "#f4855b");
				$('.users_switch').css("color", "#FFF" );
					$('.users').hide('slide', {direction: 'left'}, 300, function(){
						$('.teachers').show('slide', {direction: 'right'},200);
						slide.teachers = 1;
					});
			}
			else{
				hover[1] = 0;
				hover[0] = hover[2] = 1;
				slide.users = slide.tracks = 0;
				$('.teachers_switch').css("color", "#f4855b");
				$('.track_switch').css("color", "#FFF");
				$('.tracks').hide('slide', {direction: 'right'}, 300, function(){
						$('.teachers').show('slide', {direction: 'left'},200);
						slide.teachers = 1;
					});
			}
	});
	$('.track_switch').click(function(){
		if(!slide.tracks)
			if(slide.users){
				hover[2] = 0;
				hover[0] = hover[1] = 1;
				
				slide.users = slide.teachers = 0;
				$('.users_switch').css("color", "#FFF");
				$('.track_switch').css("color", "#f4855b");
					$('.users').hide('slide', {direction: 'left'}, 300, function(){
						$('.tracks').show('slide', {direction: 'right'},200);
						slide.tracks = 1;
					});
			}
			else{
				hover[2] = 0;
				hover[0] = hover[1] = 1;
				
				slide.users = slide.teachers = 0;
				$('.teachers_switch').css("color", "#FFF");
				$('.track_switch').css("color", "#f4855b");
					$('.teachers').hide('slide', {direction: 'left'}, 300, function(){
						$('.tracks').show('slide', {direction: 'right'},200);
						slide.tracks = 1;
					});
			}
	});
	$('.users_switch').click(function(){
		if(!slide.users)
			if(slide.teachers){
				hover[0] = 0;
				hover[1] = hover[2] = 1;
				slide.teachers = slide.tracks = 0;
				$('.teachers_switch').css("color", "#FFF");
				$('.users_switch').css("color", "#f4855b");
					$('.teachers').hide('slide', {direction: 'right'}, 300, function(){
						$('.users').show('slide', {direction: 'left'},200);
						slide.users = 1;
					});
			}
			else{
				hover[0] = 0;
				hover[1] = hover[2] = 1;
				slide.teachers = slide.tracks = 0;
				$('.track_switch').css("color", "#FFF");
				$('.users_switch').css("color", "#f4855b");
					$('.tracks').hide('slide', {direction: 'right'}, 300, function(){
						$('.users').show('slide', {direction: 'left'}, 200);
						slide.users = 1;
					});
			}
	});
	$('.header_picker span').hover(function(){
		if(hover[$(this).index()] )
			$(this).stop().animate({color: '#f4855b'}, 200);
	},
	function(){
		if(hover[$(this).index()] )
			$(this).stop().animate({color: '#FFF'}, 200);
	});
	//Search submit
	

});