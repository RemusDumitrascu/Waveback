function main(){
	$(".login_form").submit(function(event){
		event.preventDefault();
		var form = $('.login_form'),  mail = form.find("input[name=mail_login]").val(), password = form.find("input[name=pass_login]").val(), url = form.attr("action") ;
		var checkbox = document.getElementById("checkbox").checked;
			var post = $.post(url, {'password': password,'mail':mail, 'checkbox':checkbox});
			post.done(function(data){
				if(data.length == 1){
					$('.login_warning').fadeIn(500);
				}
				else
					if(data.length == 2){
						window.location.assign("main.php");
					}
			});
	});
}
$(document).ready(main);