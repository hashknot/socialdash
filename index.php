<!DOCTYPE html>
<?php 
require_once('invmode.php');
$app = new InvMode();
?>
<html>
	<head>
		<title>Social DashBoard</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0;" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="css/jquery.mobile-1.0.min.css" />
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="css/photoswipe.css" />
		<script type="text/javascript" src="js/jquery-1.6.4.min.js"></script>
		<script type="text/javascript" src="js/jquery.mobile-1.0.min.js"></script>
		<script type="text/javascript" src="js/klass.min.js"></script>
		<script type="text/javascript" src="js/code.photoswipe.jquery-3.0.4.min.js"></script>
		<script type="text/javascript" src="js/custom.js"></script>
	</head>
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
	       document.getElementById('gotoApp').style.visibility = 'visible'
	    };

	       // Load the SDK's source Asynchronously
	    (function(d){
		var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement('script'); js.id = id; js.async = true;
		js.src = "//connect.facebook.net/en_US/all.js";
		ref.parentNode.insertBefore(js, ref);
	     }(document));
	 </script>
	<script>
	function authUser(){
	   FB.getLoginStatus(function(response) {
		 if (response.status === 'connected') {
		 	window.location.href = 'authorize.php?user=' + response.authResponse.accessToken;
		 } else if (response.status === 'not_authorized') {
		 	console.log('Not Authorized');
		 	login();
		 } else {
		 	console.log('Not Logged in');
		 	login();
		 }
	      });
	}
	function login() {
		FB.login(function(response) {
		     if (response.authResponse) {
		 	window.location.href = 'authorize.php?user=' + response.authResponse.accessToken;
		     }
		     else{
		 	console.log('Did not Authorize');
		     }
	       },{scope: <?php echo "'$app->permissions'"; ?>});
	 }

	</script>
		<div data-role="page" data-add-back-btn="true">
			<div data-role="header" id="header">
				<h1>Your Social DashBoard</h1>
			</div><!-- /header -->
			<div data-role="content">
					<fieldset class="ui-grid-a">
					   <div class="ui-block-a" id="gotoApp" style="visibility:hidden"><button type="submit" onclick="authUser();"  data-theme="b">Go to App</button></div>
						<div class="ui-block-b"><button type="button" data-theme="a">Share</button></div>
					</fieldset>
					  <div class="ui-body ui-body-b ui-corner-all" style="max-width:30%;text-align:center;display:inline;min-width:175px;clear:none;float:left">
						    <h2>Why use Social DashBoard?</h2>
						     Social DashBoard is for you, if you want to see...
						     <ul style="text-align:left">
						    <li>Your messages without the other person knowing that you've seen.</li>
						    <li>Who's online without having to go online.</li>
						    <li>Your notifications.</li>
						  </ul>
					 </div>
					
					  <div class="ui-body ui-body-b ui-corner-all" style="max-width:30%;text-align:center;display:inline-block;min-width:175px;clear:none;float:left">
					    <h2>What is Social DashBoard?</h2>
						  Did you know... 
						     <ul style="text-align:left">
							    <li>Friends who are sitting `Idle` aren't visible in your ChatBox?</li>
							    <li>A `Seen` note is sent to the person when you read their messages sent to you?</li>
						    </ul>
						    Social DashBoard helps you bypass these restrictions.
					  </div>
					  <div class="ui-body ui-body-b ui-corner-all" style="max-width:30%;text-align:center;display:inline-block;min-width:175px;clear:none;float:left">
						    <h2>What's in Pipeline?</h2>
						    You'd love to know what's cooking in our mind.
						     <ul style="text-align:left">
							    <li>Private Chats.</li>
							    <li>Desktop notification when `X` person comes online ;)</li>
							    <li>Chrome extension for this app.</li>
							    <li>Lots More...</li>
							    
						    </ul>
					  </div>
			</div><!-- /content -->
			
			<div data-role="footer" data-theme="a" style='bottom:0px'>
				<div class="ui-bar" style='bottom:0px'>
					<p class="copyright">Copyright &copy; <a target="_blank" href="http://facebook.com/JAKWorks" class="ui-link">JAKWorks</a> | <a rel="external" href="privacy.html">Privacy Policy</a></p>
				</div>
			 </div>
		</div>
	</body>
</html>
