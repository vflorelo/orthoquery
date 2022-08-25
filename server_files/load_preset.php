<?php
$preset_id = $_POST["preset_id"];
$db_host   = "localhost";
$db_user   = "dummy";
$db_pass   = "";
$db_name   = "orthologues";
$db_cxn    = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
mysqli_set_charset($db_cxn, "utf8");
$preset_q   = "SELECT DISTINCT `preset_taxa` FROM `presets` WHERE `preset_id` = \"$preset_id\" ;";
$preset_res = mysqli_query($db_cxn,$preset_q);
$preset_num = mysqli_num_rows($preset_res);
if($preset_num == 1){
	$inner_xml_str = "  <query_str>0</query_str>\n";
	$preset_row = mysqli_fetch_assoc($preset_res);
	$preset_taxa = $preset_row["preset_taxa"];
	$taxa_arr = explode(",",$preset_taxa);
	foreach($taxa_arr as $taxon){
		$inner_xml_str .= "  <dataset>$taxon</dataset>\n";
    	}
	}
else{
	$inner_xml_str .= "  <query_str>1</query_str>\n";
	}
$xml_str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
           "<load_preset>\n".
           "$inner_xml_str".
           "</load_preset>\n";
print $xml_str;
?>
