<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_reporting(0);
if(isset($_SESSION["sel_lan"]))
{
$currentLang = $_SESSION["sel_lan"];
include_once "languages/{$currentLang}.php";
}
try {
  $dating = new mysqli("localhost", "heavensm_app", "qg!sZ-9k^cHx", "heavensm_chatdb");
  $dating->set_charset("utf8mb4"); 
} catch(Exception $e) {
  error_log($e->getMessage());
  //Should be a message a typical user could understand
}
$prints = $dating->query("select * from tbl_meet")->fetch_assoc();	
$set = $dating->query("SELECT * FROM `tbl_setting`")->fetch_assoc();
	$apiKey = $set['map_key'];
	
	if($_SESSION["stype"] == 'Staff')
	{
	$sdata = $dating->query("SELECT * FROM `tbl_manager` where email='".$_SESSION['datingname']."'")->fetch_assoc();
	           $interest_per = explode(',',$sdata['interest']);
			   $page_per = explode(',',$sdata['page']);
			   $faq_per = explode(',',$sdata['faq']);
			   $plist_per = explode(',',$sdata['plist']);
			   $language_per = explode(',',$sdata['language']);
			   $payout_per = explode(',',$sdata['payout']);
			   $religion_per = explode(',',$sdata['religion']);
			   $gift_per = explode(',',$sdata['gift']);
			   $rgoal_per = explode(',',$sdata['rgoal']);
			   $plan_per = explode(',',$sdata['plan']);
			   $package_per = explode(',',$sdata['package']);
			   $ulist_per = explode(',',$sdata['ulist']);
			   $fakeuser_per = explode(',',$sdata['fakeuser']);
			   $report_per = explode(',',$sdata['report']);
			   $notification_per = explode(',',$sdata['notification']);
			   $wallet_per = explode(',',$sdata['wallet']);
			   $coin_per = explode(',',$sdata['coin']);
	}
?>