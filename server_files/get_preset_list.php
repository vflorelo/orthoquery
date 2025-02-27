<?php
$db_host    = "localhost";
$db_user    = "dummy";
$db_pass    = "";
$db_name    = "orthologues";
$db_cxn     = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
mysqli_set_charset($db_cxn, "utf8");
$preset_q   = "SELECT DISTINCT `preset_id`,`preset_name` FROM `presets` ORDER BY `preset_id` ASC";
$preset_res = mysqli_query($db_cxn,$preset_q);
$preset_num = mysqli_num_rows($preset_res);
if($preset_num >=1){
  $inner_xml_str = "  <query_str>0</query_str>\n";
  foreach($preset_res as $preset_hit){
    $preset_id   = $preset_hit["preset_id"];
	$preset_name = $preset_hit["preset_name"];
    $inner_xml_str .= "  <entry>\n".
                      "    <preset_id>$preset_id</preset_id>\n".
                      "    <preset_name>$preset_name</preset_name>\n".
                      "  </entry>\n";
    	}
	}
else{
	$inner_xml_str .= "  <query_str>1</query_str>\n";
	}
$xml_str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
           "<presets>\n".
           "$inner_xml_str".
           "</presets>\n";
print $xml_str;
?>
