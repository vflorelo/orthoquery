<?php
$preset_name = $_POST["preset_name"];
$datasets    = $_POST["datasets"];
$db_host     = "localhost";
$db_user     = "dummy";
$db_pass     = "";
$db_name     = "orthologues";
$db_cxn      = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
mysqli_set_charset($db_cxn, "utf8");
$last_preset_q   = "SELECT `preset_id` FROM `presets` ORDER BY `assoc` DESC LIMIT 1 ;";
$last_preset_res = mysqli_query($db_cxn,$last_preset_q);
if($last_preset_res){
	$last_preset_row = mysqli_fetch_assoc($last_preset_res);
	$last_preset_id  = $last_preset_row["preset_id"];
	$last_preset_num = intval(explode("_",$last_preset_id)[1]);
	$new_preset_num  = intval($last_preset_num + 1);
	$new_preset_id   = "tax-preset_$new_preset_num" ;
	$new_preset_q    = "INSERT INTO `presets` (`preset_id`,`preset_name`,`preset_taxa`) VALUES (\"$new_preset_id\",\"$preset_name\",\"$datasets\");";
	$new_preset_res  = mysqli_query($db_cxn,$new_preset_q);
	if( $new_preset_res){
		$query_str = 0;
		}
	else{
		$query_str = 1;
		}
	}
else{
	$query_str = 2;
	}
$xml_str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
           "<save_preset>\n".
           "  <query_str>$query_str</query_str>\n".
           "</save_preset>\n";
print $xml_str;
?>