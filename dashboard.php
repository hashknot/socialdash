<?php
	require("invmode.php");
	session_start();
	if(!isset($_SESSION['appObject']) || empty($_SESSION['appObject']))
	{
		header("Location:index.php");
		exit(0);
        }
	$invisible = $_SESSION['appObject'];
	$result = $invisible->getAllInfo();
?>
<html>
	<head>
		<title>Social DashBoard</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0;" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="css/jquery.mobile-1.0.min.css" />
		<link rel="stylesheet" href="css/tablestyle.css" />
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="css/photoswipe.css" />
		<script type="text/javascript" src="js/jquery-1.6.4.min.js"></script>
		<script type="text/javascript" src="js/jquery.mobile-1.0.min.js"></script>
		<script type="text/javascript" src="js/klass.min.js"></script>
		<script type="text/javascript" src="js/custom.js"></script>
	</head>
	<body>
		<div id="notifications" data-role="page" data-add-back-btn="false" >
			<div data-role="header" id="header">
			   <h1>Your Social DashBoard<p style="display:inline;clear:none;float:right;font-size:14px;margin:0px"><a rel="external" style="text-decoration:none" href="logout.php">Logout</a></p></h1>
			</div><!-- /header -->
			<div data-role="content">
			     <div data-role="collapsible-set" data-theme="a">
				  <div data-role="collapsible" align="center">
					  <h3>Notifications</h3>
					  <p>
					       <?php
						    if($result['notifications'])
						    {
							 echo "<table id='table-2'>
							 <thead><tr><td>Title</td><td>Context</td></tr></thead>";
							 foreach($result['notifications'] as $value){
								 echo "<tr><td>".$value['title_text']."</td><td>".$value['body_text']."</td></tr>";
							 }
							 echo "</table>";
						    }
						    else
							 echo "Hurray! No notifications.";
					       ?>
					  </p>
				  </div>					
				  <div data-role="collapsible" align="center">
					  <h3>Unread Messages</h3>
					  <p>
					       <?php
						    if($result['unreadThreads']) {
							$recipients = array();
							foreach($result['recipients'] as $value){
							      $recipients["'".$value['uid']."'"] = $value['first_name'];
							}
							echo "<table id='table-2'><thead><tr><td>Users</td><td> </td></tr></thead>";
							foreach($result['unreadThreads'] as $thread){
							      echo "<tr><td>";
							      foreach($thread['recipients'] as $participant)
								 echo $recipients["'".$participant."'"].' ';
								 echo "</td><td><a rel='external' target='_blank' href='messages.php?thread_id=".$thread['thread_id']."'>View Messages</a></td></tr>";
							}   
							echo "</table>";
						    }
						    else
							echo "Hurray! No unread messages.";
					       ?>
					  </p>
				  </div>
				  <div data-role="collapsible" align="center">					
					  <h3>Online Friends</h3>
					  <p>
					      <?php
						   if($result['onlineFriends']) {
							   echo "<table id='table-2'>
							   <thead><tr><td>Status</td><td>Name</td></tr></thead>";
							   foreach($result['onlineFriends'] as $value){
								   echo "<tr><td>".$value['online_presence']."</td><td>".$value['name']."</td></tr>";
							   }
							   echo "</table>";
						   }
						   else
							   echo "Oops! No one online.";
					      ?>
					  </p>
				  </div>
			     </div>
			<p class="copyright">Copyright &copy; <a target="_blank" href="http://facebook.com/JAKWorks" class="ui-link">JAKWorks</a> | <a rel="external" href="privacy.html">Privacy Policy</a></p> </div>
			<div data-role="footer" data-theme="a">
				<div class="ui-bar">
					<a href="" data-role="button" data-icon="arrow-u" data-theme="a" style="float:right;" class="returnTopAction">Return top</a>             
				</div>
			</div>
		</div>
	</body>
</html>
