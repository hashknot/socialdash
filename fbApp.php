<?php

class fbApp {
   public  $tokenUrl = '/authorize.php';
   public  $onAuthSuccessUrl = 'dashboard.php#notifications';
   public  $cliMode = false;
   public  $permissions = 'friends_online_presence, read_mailbox, manage_notifications';
   public  $redirect_url = '';
   public  $useCurl = false;
   public  $CURL_OPTS = null;
   private $accessToken = '';
   public  $app_id;
   public  $app_secret;

    //appUrl not end with trailing '/'. Make sure of that.
   public  $appUrl = 'https://socialdash.herokuapp.com';


   function __construct__($cli = false,$useCurl = false) {
      $this->fbApp($cli,$useCurl);
   }

   function fbApp($cli = false,$useCurl = false){
      $this->app_id = getenv('FBAPP_ID');
      $this->app_secret = getenv('FBAPP_SECRET');
      $this->useCurl = $useCurl;
      $this->redirect_uri =$this->appUrl.$this->tokenUrl;
      $this->cliMode = $cli;
      if($this->useCurl){
	 $this->CURL_OPTS = array(
	       CURLOPT_CONNECTTIMEOUT => 10,
	       CURLOPT_RETURNTRANSFER => true,
	       CURLOPT_TIMEOUT        => 60,
	       CURLOPT_USERAGENT      => 'facebook-php-3.2'
	       );
      }
   }

   public function readFromUrl($url){
      if($this->useCurl){
	 $ch = curl_init($url);
	 curl_setopt_array($ch,$this->CURL_OPTS);
	 $result = curl_exec($ch);
	 curl_close($ch);
	 return $result;
      }
      else{
	 return file_get_contents($url);
      }
   }

   public function getUser($accessToken){
      $response = $this->readFromUrl('https://graph.facebook.com/me?fields=id&'.$accessToken);
      $userId = json_decode($response,true);
      return $userId['id'];
   }

   public function setAccessToken($newAccessToken){
      $this->accessToken = $newAccessToken;
   }

   public function authorize() {
      session_start();

      if(isset($_GET['user'])){
	 $this->setAccessToken('access_token='.$_GET['user']);
	 $_SESSION['appObject'] = $this;	//Share the object throughout the app now.
	 header('Location:'.$this->onAuthSuccessUrl);
	 exit(0);
      }
      $code = $_REQUEST["code"];
      if(empty($code)) {
	 $dialog_url = 'https://www.facebook.com/dialog/oauth?';
	 $parameters = 'client_id='	.urlencode($this->app_id      	)
	    . '&redirect_uri='		.urlencode($this->redirect_uri	)
	    . '&scope='			.urlencode($this->permissions 	);
	 $dialog_url .= $parameters;
	 header("Location:$dialog_url");
      }
      else{ 
	 $accessTokenUrl = "https://graph.facebook.com/oauth/access_token?"
	    . "client_id="		.urlencode($this->app_id	)
	    . "&redirect_uri="		.urlencode($this->redirect_uri	)
	    . "&client_secret="		.urlencode($this->app_secret	)
	    . "&code="			.urlencode($code		);
	 $accessToken = $this->readFromUrl($accessTokenUrl);
	 $this->setAccessToken($accessToken);
	 $_SESSION['appObject'] = $this;	//Share the object throughout the app now.
	 header('Location:'.$this->onAuthSuccessUrl);
      }
      exit(0);
   }

   public function fqlQuery($query) {
      if($this->cliMode){
	 return $this->fqlQueryCli($query);
      }
      else{
	 $fqlURL = 'https://graph.facebook.com/fql?q=';
	 $fqlQuery = $fqlURL . urlencode($query) . '&' . $this->accessToken;
	 $response = $this->readFromUrl($fqlQuery);
	 return json_decode($response,true);
      }
   }

   public function fqlQueryCli($query){
      $userAgent = 'facebook-php-3.2';
      $fqlUrl = 'https://graph.facebook.com/fql?q=';
      $fqlQuery = $fqlUrl . urlencode($query) . '&' . $this->accessToken;
      $curlEncodedQuery = "'" . $fqlQuery . "'";
      $response = exec("curl -s -A $userAgent $curlEncodedQuery");
      return(json_decode($response,true));
   }
}

?>
