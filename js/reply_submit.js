var inputs = new Array();
$(document).ready(function(){
	$('.reply_initial_button').click(function(){
		var index = $(this).index();
		if( typeof inputs[index] == 'undefined'){
			$(this).after('<input id="reply_textarea" name="reply" type="text" />');
			inputs[index] = 1;
		}

	});
							$("#reply_textarea").on('keypress', function(event){
							alert("cmon");
							var reply = $(this).val();
							
	    					if(event.which == 13){
	    						
	    					}
						});	
});