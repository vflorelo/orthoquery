<?php
$datasets     = $_POST["datasets"];
$ortho_mode   = $_POST["ortho_mode"];
$datasets_arr = explode(",",$datasets);
$num_species  = count($datasets_arr);
$db_host      = "localhost";
$db_user      = "dummy";
$db_pass      = "";
$db_name      = "orthologues";
$db_cxn       = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
$file_name    = uniqid("orthodump_");
$tsv_str      = "Orthogroup";
mysqli_set_charset($db_cxn, "utf8");
$dataset_q = "SELECT DISTINCT `Orthogroup`";
$limit_str = "";
if($ortho_mode=="strict"){
	$incl_str = "AND";
	}
else if($ortho_mode=="relaxed"){
	$incl_str = "OR";
	}
foreach ($datasets_arr as $dataset){
	$tsv_str .= "\t$dataset";
	$dataset_q .= ",`$dataset`" ;
	$limit_str .= " (`$dataset` IS NOT NULL AND `$dataset` != \"\" ) $incl_str";
	}
$tsv_str .= "\n";
$limit_str = rtrim($limit_str,$incl_str);
$dataset_q .= " FROM `orthogroups` WHERE $limit_str ; ";
$dataset_res = mysqli_query($db_cxn,$dataset_q);
$dataset_num = mysqli_num_rows($dataset_res);
$inner_xml_str = "  <num_species>$num_species</num_species>\n";
if( ($dataset_res) && ($dataset_num > 0)){
	$inner_xml_str .= "  <query_str>0</query_str>\n";
	$inner_xml_str .= "  <ortho_count>$dataset_num</ortho_count>\n";
	$inner_xml_str .= "  <file_name>$file_name</file_name>\n";
	foreach($dataset_res as $dataset_row ){
		$tsv_str .= implode("\t",$dataset_row);
		$tsv_str .= "\n";
		}
	file_put_contents($file_name,$tsv_str);
	}
else if( ($dataset_res) && ($dataset_num == 0)){
	$inner_xml_str .= "  <query_str>1</query_str>\n";
	$inner_xml_str .= "  <ortho_count>$dataset_num</ortho_count>\n";
	}
else{
	$inner_xml_str .= "  <query_str>2</query_str>\n";
	}
$xml_str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
           "<datasets>\n".
		   "<query>$dataset_q</query>\n".
           "$inner_xml_str".
           "</datasets>\n";
print $xml_str;
?>