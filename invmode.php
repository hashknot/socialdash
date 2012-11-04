<?php
require_once("fbApp.php");
setlocale(LC_ALL, 'en_IN');

function is_assoc($arr) {
   return (is_array($arr) && count(array_filter(array_keys($arr),'is_string')) == count($arr));
}

class InvMode extends fbApp{

   function __construct__($cliMode=false,$useCurl = false){
      $this->InvMode();
   }

   function InvMode($cliMode=false,$useCurl = false){
      parent::fbApp($cliMode,$useCurl);
   }

   public function getUnreadThreadsQuery($inJSON = true) {
      $q['unreadThreads'] = "select thread_id,recipients from thread where unread!=0 and folder_id=0";
      $q['recipients']= "select first_name,uid from user where uid in (select recipients from #unreadThreads)";
      return( $inJSON ? json_encode($q) : $q );
   }   


   public function getUnreadMessages($thread_id) {
      $q['unreadMessages'] = "select author_id,body from message where thread_id='$thread_id' ORDER BY created_time DESC LIMIT 10";
      $q['authors']= "select name,uid from user where uid in (select author_id from #unreadMessages)";
      $query = json_encode($q);
      $result = $this->fqlQuery($query);
      return $this->makeAssociative($result);
   }


   public function getNotificationsQuery($inJSON = true){
      $q['notifications'] = 'SELECT sender_id,title_text,body_text FROM notification WHERE recipient_id = me() AND is_unread!=0 ORDER BY updated_time ASC';
      return( $inJSON ? json_encode($q) : $q );
   }

   public function getOnlineFriendsQuery($inJSON = true) {
      $q['onlineFriends'] = 'SELECT online_presence,name FROM user WHERE online_presence in ("active","idle") AND uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) ORDER BY name';
      return( $inJSON ? json_encode($q) : $q );
   }

   public function generateMultiQuery( /*pass all the ASSOCIATVE ARRAYS of the FORM ['QueryName'] => 'Query' here. You will get the Result of each query in respective ['QueryName'].*/ ){
      $args = func_get_args();
      $result = array();
      foreach($args as $value){
	 if(!is_assoc($value)){
	    die(var_dump($value).' is not an associative array. You need to pass an associative array to generateMultiQuery()');
	 }
	 $result = array_merge($result,$value); 
      }
      return json_encode($result);
   }

   //Function to make the Query Result more associative to obtain the individiual query results by using using 'QueryName' associative key. E.g. $assocResult['unreadMessages'].
   public function makeAssociative($result){
      $assocResult = array();
      foreach($result['data'] as $oneQueryResult){
	 foreach($oneQueryResult as $key=>$value){
	    $assocResult[$oneQueryResult['name']] = $oneQueryResult['fql_result_set'];
	 }
      }
      return $assocResult;
   }


   public function getAllInfo(){
      $m = $this->getUnreadThreadsQuery(false);
      $n = $this->getNotificationsQuery(false);
      $o = $this->getOnlineFriendsQuery(false);
      $result =  $this->fqlQuery($this->generateMultiQuery($n,$o,$m));
      $assocResult = $this->makeAssociative($result);	//Make the Query Result more associative to obtain the individiual query results by using using 'QueryName' associative key. E.g. $assocResult['unreadMessages'].
      return $assocResult;
   }
}

?>
