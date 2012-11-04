<?php
session_start();
session_destroy();
require_once('invmode.php');
$app = new InvMode();
?>
<!DOCTYPE html>
<html>
	<body>
	<div id="fb-root"></div>
	<script>
	   window.fbAsyncInit = function() {
	      // init the FB JS SDK
	      FB.init({
		  appId      : <?php echo "'$app->app_id'"; ?>,
		  channelUrl : <?php echo "'$app->appUrl/channel.html'" ?>,
		  status     : true, 
		  cookie     : true
	       });
	      FB.getLoginStatus(function(response) {
		    if (response.status === 'connected') {
		    	FB.logout(function(response){
			 alert('You have been logged out of this app and Facebook.');
			 });
		    }
		    window.location.href = '/index.php';
		 });
	       };
	    (function(d){
		var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement('script'); js.id = id; js.async = true;
		js.src = "//connect.facebook.net/en_US/all.js";
		ref.parentNode.insertBefore(js, ref);
	     }(document));
      </script>
      </body>
</html>

