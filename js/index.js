var reg =" ", xhr, user_request;
function initialize_regexp(){
	var i;
		for(i = 32; i<= 45; i++)
			reg +=String.fromCharCode(i);
		reg+='/';
		for(i = 58; i<= 63; i++)
			reg +=String.fromCharCode(i);
		for(i = 91; i<= 94; i++)
			reg +=String.fromCharCode(i);
		reg+=String.fromCharCode(96);
		for(i = 123; i<= 255; i++)
			reg +=String.fromCharCode(i);
}
function check_password(pw){
	for(var i = 0; i<= 9; i++)
		if(pw.indexOf(String(i)) !== -1)
			return true;
	return false;
}
function check_pass2(pw){
	for(var i = 0; i< reg.length; i++)
		if(pw.indexOf(reg[i]) !== -1)
			return false;
		if(pw.indexOf('_') !== -1)
			return false;
		if(pw.indexOf('@') != -1)
			return false;
		if(pw.indexOf('.') != -1)
			return false;
	return true;
}
function check_mail(email){
	for(var i = 0; i<reg.length; i++)
		if(email.indexOf(reg[i]) != -1)
			return false;
	return true;
}
function check_mail2(mail){// Check if the email adress has _ after @
	for(var i = mail.length - 1; i>=0; i--){
		if(mail[i] === '@')
			break;
		if(mail[i] === '_')
			return false;
	}
	return true;
}
function main(){
		initialize_regexp();
		var server_mail = $('input[name=mail_signup]').val();
		var server_user = $('input[name=username]').val();
			if(server_user.length>0)
				if(server_user.length <2){
						$(".namehint .text").text("Use between 2 and 20 characters");
						$(".namehint").fadeIn(500);
					}
					else{
						user_request = new XMLHttpRequest();
						user_request.onreadystatechange = function(){
							if(user_request.readyState == 4 && user_request.status == 200)
								if(user_request.responseText == 0){
									$(".namehint .text").text("Name already taken");
									$(".namehint").fadeIn(500);
								}
								else if(user_request.responseText == 1 )
									$(".namehint").fadeOut(400);
							}	
						user_request.open("GET","php/usercheck.php?data=" + server_user, true);
						user_request.send();
						}
				else{
					$(".namehint").fadeOut(500,function(){
						$(".namehint .text").text("Use between 2 and 20 characters");
					});
				}
		if(server_mail.length>0)
			if(server_mail.indexOf('@') === -1 || server_mail.indexOf('.') === -1 || !check_mail(server_mail) || !check_mail2(server_mail) ){
				$(".mail_indications").text("This is not a valid email adress");
				$('.mail_indications').animate({height:'19px', margin:'20px auto'},300);
				}	
				else{
						xhr = new XMLHttpRequest();
						xhr.onreadystatechange = function(){
							if(xhr.readyState == 4 && xhr.status == 200)
								if(xhr.responseText == 1)
									$('.mail_indications').animate({height:'0px', margin:'0px auto'},300);
								else{	
									$(".mail_indications").text("Email is already taken");
									$('.mail_indications').animate({height:'19px', margin:'20px auto'},300);
								}
							}
						xhr.open("GET","php/mailcheck.php?data=" + server_mail, true);
						xhr.send();
					}
			else{
				$('.mail_indications').animate({height:'0px', margin:'0px auto'},300);
				$(".mail_indications").text("This is not a valid email adress");
				}

			$(document).keyup(function(e){ // Keyup event for showing up hints
				var username = $('input[name=username]').val();
				var mail = $('input[name=mail_signup]').val();//Take value from mail input
				var pw = $("input[name=pass_signup]").val();//Take value from password input
				var confirm_pw = $("input[name=confirm_pass]").val();
				var pw_len = pw.length;// Length of password

				if(mail.length>0)
					if(mail.indexOf('@') === -1 || mail.indexOf('.') === -1 || !check_mail(mail) || !check_mail2(mail) ){
						$(".mail_indications").text("This is not a valid email adress");
						$('.mail_indications').animate({height:'19px', margin:'20px auto'},200);	
					}
					else {
						xhr = new XMLHttpRequest();
						xhr.onreadystatechange = function(){
							if(xhr.readyState == 4 && xhr.status == 200)
								if(xhr.responseText == 1)
									$('.mail_indications').animate({height:'0px', margin:'0px auto'},200);
								else{	
									$(".mail_indications").text("Email is already taken");
									$('.mail_indications').animate({height:'19px', margin:'20px auto'},200);
								}
							}
						xhr.open("GET","php/mailcheck.php?data=" + mail, true);
						xhr.send();
					}
				else{
					$('.mail_indications').animate({height:'0px', margin:'0px auto'},200);
					$(".mail_indications").text("This is not a valid email adress");
				}
		
				if(pw_len >0 || confirm_pw.length>0)
					if(pw != confirm_pw || pw_len<8 || pw_len>16 || !check_password(pw) || !check_pass2(pw)){
						$('.password_indications').show(300);
							//If both passwords are the same make the hint green
							if(pw === confirm_pw)
								$('.text ul li:nth-child(4)').addClass('checked_hint');
							else
								$('.text ul li:nth-child(4)').removeClass('checked_hint');
							//If the password has the right length make the specific hint green	
							if(pw_len >= 8 && pw_len <=16)
								$('.password_indications .text ul li:first-child').addClass('checked_hint');
							else
								$('.password_indications .text ul li:first-child').removeClass('checked_hint');
							if(check_password(pw))
								$('.password_indications .text ul li:nth-child(2)').addClass('checked_hint');
							else
								$('.password_indications .text ul li:nth-child(2)').removeClass('checked_hint');
							if(check_pass2(pw))
								$('.password_indications .text ul li:nth-child(3)').addClass('checked_hint');
							else
								$('.password_indications .text ul li:nth-child(3)').removeClass('checked_hint');
						}
					else
						$('.password_indications').hide(300);
				else
					$('.password_indications').hide(300);
				if(username.length>0)
					if(username.length <2){
						$(".namehint .text").text("Use between 2 and 20 characters");
						$(".namehint").fadeIn(500);
					}
					else{
						user_request = new XMLHttpRequest();
						user_request.onreadystatechange = function(){
							if(user_request.readyState == 4 && user_request.status == 200)
								if(user_request.responseText == 0){
									$(".namehint .text").text("Name already taken");
									$(".namehint").fadeIn(500);
								}
								else if(user_request.responseText == 1 )
									$(".namehint").fadeOut(400);
							}	
						user_request.open("GET","php/usercheck.php?data=" + username, true);
						user_request.send();
						}
				else{
					$(".namehint").fadeOut(500,function(){
						$(".namehint .text").text("Use between 2 and 20 characters");
					});
				}
			});	
	}
$(document).ready(main);