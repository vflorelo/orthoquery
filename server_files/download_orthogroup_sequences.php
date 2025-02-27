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
$inner_xml_str = "  <orthogroup>$orthogroup</orthogroup>\n".
                 "  <num_species>$num_species</num_species>\n";
foreach ($datasets_arr as $dataset){
	$dataset_q   = "SELECT DISTINCT `$dataset` FROM `orthogroups` WHERE `Orthogroup` = \"$orthogroup\" ;";
	$dataset_res = mysqli_query($db_cxn,$dataset_q);
	$dataset_num = mysqli_num_rows($dataset_res);
	if($dataset_num == 1){
		$inner_xml_str .= "  <dataset>\n".
		                  "    <name>$dataset</name>\n".
						  "    <dataset_query_str>0</dataset_query_str>\n";
		$dataset_row = mysqli_fetch_assoc($dataset_res);
		$accession_str = $dataset_row["$dataset"];
		$accession_arr = explode(",",$accession_str);
		foreach ($accession_arr as $accession){
			$sequence_q   = "SELECT DISTINCT `sequence` FROM `$dataset` WHERE `accession` =  \"$accession\" ; ";
			$sequence_res = mysqli_query($db_cxn,$sequence_q);
			$sequence_num = mysqli_num_rows($sequence_res);
			if($sequence_num == 1 ){
				$sequence_row = mysqli_fetch_assoc($sequence_res);
				$sequence_str = $sequence_row["sequence"];
				$inner_xml_str .= "    <entry>\n".
				                  "      <sequence_query_str>0</sequence_query_str>\n".
				                  "      <accession>$accession</accession>\n".
								  "      <sequence>$sequence_str</sequence>\n".
								  "    </entry>\n";
				}
			else{
				$inner_xml_str .= "    <entry>\n".
				                  "      <sequence_query_str>1</sequence_query_str>\n".
				                  "      <accession>$accession</accession>\n".
								  "    </entry>\n";
				}
			}
		$inner_xml_str .= "  </dataset>\n";
		}
	else{
		$inner_xml_str .= "  <dataset>\n".
		                  "    <name>$dataset</name>\n".
						  "    <dataset_query_str>1</dataset_query_str>\n".
						  "  </dataset>\n";
		}
	}
$xml_str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
           "<orthogroup_sequences>\n".
           "$inner_xml_str".
           "</orthogroup_sequences>\n";
print $xml_str;
?>