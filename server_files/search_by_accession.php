<?php
$datasets      = $_POST["datasets"];
$page_num      = intval($_POST["page_num"]);
$ortho_mode    = $_POST["ortho_mode"];
$accession_list= $_POST["accession_list"];
$accession_arr = explode(",",$accession_list);
$accession_count = count($accession_arr);
$datasets_arr  = explode(",",$datasets);
$db_host       = "localhost";
$db_user       = "dummy";
$db_pass       = "";
$db_name       = "orthologues";
$db_cxn        = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
mysqli_set_charset($db_cxn, "utf8");
$start_hit     = intval(($page_num-1)*50);
if($ortho_mode=="strict"){
	$incl_str = "AND";
	}
else if($ortho_mode=="relaxed"){
	$incl_str = "OR";
	}
$inner_xml_str =	"  <page_num>$page_num</page_num>\n".
					"  <accession_list>$accession_list</accession_list>\n".
					"  <accession_count>$accession_count</accession_count>\n";
foreach($accession_arr as $accession){
	$limit_str     = "(`protein_list` LIKE \"%,$accession,%\" OR `protein_list` LIKE \"%$accession,%\" OR `protein_list` LIKE \"%,$accession%\") AND (" ;
	foreach($datasets_arr as $dataset){
		$limit_str  .= " (`$dataset` IS NOT NULL AND `$dataset` != \"\" ) $incl_str"; 
		}
	$limit_str = rtrim($limit_str,$incl_str);
	$limit_str = "$limit_str)";
	$ortho_q     = "SELECT DISTINCT `Orthogroup`,`protein_count` FROM `orthogroups` WHERE $limit_str ORDER BY `orthogroup` ASC LIMIT $start_hit,50 ;";
	$ortho_res   = mysqli_query($db_cxn,$ortho_q);
	$ortho_num   = mysqli_num_rows($ortho_res);
	if( $ortho_num >=1){
		foreach($ortho_res as $ortho_hit){
			$Orthogroup     = $ortho_hit["Orthogroup"];
			$protein_count  = $ortho_hit["protein_count"];
			$inner_xml_str .=	"  <entry>\n".
								"    <query_str>0</query_str>\n".
								"    <Orthogroup>$Orthogroup</Orthogroup>\n".
								"    <protein_count>$protein_count</protein_count>\n".
								"    <accession>$accession</accession>\n".
								"  </entry>\n";
			}
		}
	else{
		$inner_xml_str .=	"  <entry>\n".
							"    <query_str>1</query_str>\n".
							"    <accession>$accession</accession>\n".
							"  </entry>\n";
		}
	}
$xml_str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
           "<orthogroups>\n".
           "$inner_xml_str".
           "</orthogroups>\n";
print $xml_str;
?>
