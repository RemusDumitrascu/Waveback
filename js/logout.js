$(document).ready(function(){

	  // Load the SDK asynchronously
	  (function(d, s, id) {
	    var js, fjs = d.getElementsByTagName(s)[0];
	    if (d.getElementById(id)) return;
	    js = d.createElement(s); js.id = id;
	    js.src = "//connect.facebook.net/en_US/sdk.js";
	    fjs.parentNode.insertBefore(js, fjs);
	  }(document, 'script', 'facebook-jssdk'));

	//Submit form for logout
	$('.logout_button').click(function(){
		FB.init({ apiKey: '267481923589568',
		cookie     : true,  // enable cookies to allow the server to access
	    xfbml      : true,  // parse social plugins on this page
	    version    : 'v2.5' });

		FB.getLoginStatus(handleSessionResponse);
   	});
	function handleSessionResponse(response) {
	console.log(response);
    //if we dont have a session (which means the user has been logged out, redirect the user)
    if (response.status == "unknown" ) {
		$('.logout').submit();
        return;
    }
    //if we do have a non-null response.session, call FB.logout(),
    //the JS method will log the user out of Facebook and remove any authorization cookies
    FB.logout(handleSessionResponse);
	}
});