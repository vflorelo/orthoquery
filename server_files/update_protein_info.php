<?php
$accession    = $_POST["accession"];
$species_name = $_POST["species_name"];
$field_name   = $_POST["field_name"];
$field_value  = $_POST["field_value"];
$db_host      = "localhost";
$db_user      = "dummy";
$db_pass      = "";
$db_name      = "orthologues";
$db_cxn       = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
mysqli_set_charset($db_cxn, "utf8");
$update_q     = "UPDATE `$species_name` SET `$field_name` = \"$field_value\" WHERE `accession` = \"$accession\" ;";
$update_res   = mysqli_query($db_cxn,$update_q);
if( $update_res){
	$inner_xml_str = "  <query_str>0</query_str>\n".
	                 "  <species_name>$species_name</species_name>\n".
	                 "  <accession>$accession</accession>\n".
					 "  <field_name>$field_name</field_name>\n".
					 "  <field_value>$field_value</field_value>\n";
	}
else{
	$inner_xml_str .= "  <query_str>1</query_str>\n";
	}
$xml_str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
           "<protein_info>\n".
           "$inner_xml_str".
           "</protein_info>\n";
print $xml_str;
?>