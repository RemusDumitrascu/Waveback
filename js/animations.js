function main(){
	var form_switch = true; var i;
	var hover = new Array(0,0,1);
	var slide = {
		'login': 0,
		'signup':0,
		'aboutus' : 1
	};
	$(".aboutus_thing").show("explode", { pieces: 8 });
		$('.login_switch').click(function(){
			if(!slide.login)
				if(slide.aboutus){
					hover[2] = 0;
					hover[0] = 1;
					$('.login_switch').css("background-color", "#42718c");
					$('.aboutus_switch').css("background-color", "#5b9cc1");
					$(".aboutus_thing").hide("slide",{direction: 'right'}, 500,function(){
						$(".login_thing").show("slide",{direction: 'left'},200);
						slide.login = 1;
						slide.aboutus = 0;

					});
				}
			else 
				if(slide.signup){
					hover[1] = 0;
					hover[0] = 1;
						$('.login_switch').css("background-color", "#42718c");
						$('.signup_switch').css("background-color", "#5b9cc1");
							$(".signup_thing").hide("slide",{direction: 'right'}, 500,function(){
								$(".login_thing").show("slide",{direction: 'left'},200);
								slide.login = 1;
								slide.signup = 0;

							});				
				}
		});
		$('.aboutus_switch').click(function(){
			if(!slide.aboutus)
				if(slide.login){
					hover[2] = 1;
					hover[0] = 0;
					$('.login_switch').css("background-color", "#5b9cc1");
					$('.aboutus_switch').css("background-color", "#42718c");
					$(".login_thing").hide("slide",{direction: 'left'}, 500,function(){
						$(".aboutus_thing").show("slide",{direction: 'right'},200);
							slide.login = 0;
							slide.aboutus = 1;
						});					
					}
				else if(slide.signup){
					hover[2] = 1;
					hover[1] = 0;
					$('.signup_switch').css("background-color", "#5b9cc1");
					$('.aboutus_switch').css("background-color", "#42718c");
					$(".signup_thing").hide("slide",{direction: 'left'}, 500,function(){
						$(".aboutus_thing").show("slide",{direction: 'right'},200);
							slide.signup = 0;
							slide.aboutus = 1;
						});					
					}

		});
		$(".signup_switch").click(function(){
			if(!slide.signup)
				if(slide.aboutus){
					hover[2] = 0;
					hover[1] = 1;
					$('.signup_switch').css("background-color", "#42718c");
					$('.aboutus_switch').css("background-color", "#5b9cc1");
					$(".aboutus_thing").hide("slide",{direction: 'right'}, 500,function(){
						$(".signup_thing").show("slide",{direction: 'left'},200);
							slide.signup = 1;
							slide.aboutus = 0;
						});					
					}
				else if(slide.login){
					hover[1] = 1;
					hover[0] = 0;
					$('.login_switch').css("background-color", "#5b9cc1");
					$('.signup_switch').css("background-color", "#42718c");
					$(".login_thing").hide("slide",{direction: 'left'}, 500,function(){
						$(".signup_thing").show("slide",{direction: 'right'},200);
							slide.login = 0;
							slide.signup = 1;
						});					
					}
		});
		
		$(".aboutus_switch, .login_switch, .signup_switch").hover(function(){
			if(!hover[$(this).index()])
				$(this).stop().animate({backgroundColor: '#42718c'}, 200, function(){});
		},
		function(){
			if(!hover[$(this).index()])
				$(this).stop().animate({backgroundColor: '#5b9cc1'}, 200);
		});
	}
$(document).ready(main);