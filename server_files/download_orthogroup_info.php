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
			$info_q   = "SELECT DISTINCT `accession`,`type`,`prot_function`,`comments`,`location` FROM `$dataset` WHERE `accession` =  \"$accession\" ; ";
			$info_res = mysqli_query($db_cxn,$info_q);
			$info_num = mysqli_num_rows($info_res);
			if($info_num == 1 ){
				$info_row = mysqli_fetch_assoc($info_res);
				$accession_str     = $info_row["accession"];
				$type_str          = $info_row["type"];
				$prot_function_str = $info_row["prot_function"];
				$comments_str      = $info_row["comments"];
				$location_str      = $info_row["location"];

				$inner_xml_str .= "    <entry>\n".
				                  "      <info_query_str>0</info_query_str>\n".
				                  "      <accession>$accession_str</accession>\n".
								  "      <type>$type_str</type>\n".
								  "      <prot_function>$prot_function_str</prot_function>\n".
								  "      <comments>$comments_str</comments>\n".
								  "      <location>$location_str</location>\n".
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
           "<orthogroup_info>\n".
           "$inner_xml_str".
           "</orthogroup_info>\n";
print $xml_str;
?>