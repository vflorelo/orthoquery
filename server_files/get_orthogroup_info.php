<?php
$datasets     = $_POST["datasets"];
$orthogroup   = $_POST["orthogroup"];
$datasets_arr = explode(",",$datasets);
$num_species  = count($datasets_arr);
$db_host      = "localhost";
$db_user      = "dummy";
$db_pass      = "";
$db_name      = "orthologues";
$db_cxn       = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
mysqli_set_charset($db_cxn, "utf8");
foreach($datasets_arr as $dataset){
	$search_str .= "`$dataset`,"; 
	}
$search_str   = rtrim($search_str,",");
$ortho_q      = "SELECT DISTINCT `Orthogroup`,$search_str FROM `orthogroups` WHERE `Orthogroup` = \"$orthogroup\" ;";
$ortho_res    = mysqli_query($db_cxn,$ortho_q);
$ortho_num    = mysqli_num_rows($ortho_res);
if( $ortho_num >=1){
	$inner_xml_str = "  <query_str>0</query_str>\n".
	                 "  <num_species>$num_species</num_species>\n";
	foreach($ortho_res as $ortho_hit){
		$Orthogroup     = $ortho_hit["Orthogroup"];
		foreach ($datasets_arr as $dataset){
			$accession_str = $ortho_hit["$dataset"];
			$accession_arr = explode(",",$accession_str);
			$inner_xml_str .= "  <entry>\n".
                              "    <species_name>$dataset</species_name>\n";
			foreach($accession_arr as $accession){
				$inner_xml_str .= "    <accession>$accession</accession>\n";
    			}
			$inner_xml_str .= "  </entry>\n";
			}
		}
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