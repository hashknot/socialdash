<?php
require_once('invmode.php');
session_start();
if(!isset($_GET['thread_id']) || empty($_GET['thread_id']) || !isset($_SESSION['appObject'])){
   echo 'Nothing here mate.';
   exit(0);
}
$app = $_SESSION['appObject'];

$result = $app->getUnreadMessages($_GET['thread_id']);

$authors = array();
foreach($result['authors'] as $value){
   $authors["'".$value['uid']."'"] = $value['name'];
}

$output = "";

echo '<em>Recent 10 Interactions :</em><br /><br />';
foreach($result['unreadMessages'] as $message){
   $line = "";
   $line .=  '<strong>'.$authors["'".$message['author_id']."'"].'</strong> : <br />';
   $line .=  $message['body'];
   $line .= '<br />';
   $line .= '<br />';
   $output = $line.$output;
}
echo "<code>$output</code>";
?>
