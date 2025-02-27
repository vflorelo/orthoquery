<?php
$accession    = $_POST["accession"];
$species_name = $_POST["species_name"];
$db_host      = "localhost";
$db_user      = "dummy";
$db_pass      = "";
$db_name      = "orthologues";
$db_cxn       = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
mysqli_set_charset($db_cxn, "utf8");
$protein_q    = "SELECT DISTINCT `accession`,`sequence`,`type`,`prot_function`,`comments`,`location` FROM `$species_name` WHERE `accession` = \"$accession\" ;";
$protein_res  = mysqli_query($db_cxn,$protein_q);
$protein_num  = mysqli_num_rows($protein_res);
if( $protein_num ==1){
	$protein_row = mysqli_fetch_assoc($protein_res);
	extract($protein_row);
	$inner_xml_str = "  <query_str>0</query_str>\n".
	                 "  <species_name>$species_name</species_name>\n".
	                 "  <accession>$accession</accession>\n".
					 "  <sequence>$sequence</sequence>\n".
					 "  <type>$type</type>\n".
					 "  <prot_function>$prot_function</prot_function>\n".
					 "  <comments>$comments</comments>\n".
					 "  <location>$location</location>\n";
	}
else{
	$inner_xml_str .= "  <query_str>1</query_str>\n";
	}
$xml_str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
           "<orthogroup_info>\n".
           "$inner_xml_str".
           "</orthogroup_info>\n";
print $xml_str;
?>