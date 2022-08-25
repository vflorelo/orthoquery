<?php
$db_host       = "localhost";
$db_user       = "dummy";
$db_pass       = "";
$db_name       = "orthologues";
$db_cxn        = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
mysqli_set_charset($db_cxn, "utf8");
$dataset_q     = "SELECT DISTINCT `dataset`,`protein_count` FROM `datasets` ORDER BY `dataset` ASC;";
$dataset_res   = mysqli_query($db_cxn,$dataset_q);
$dataset_num   = mysqli_num_rows($dataset_res);
if( $dataset_num >=1){
  $inner_xml_str = "  <query_str>0</query_str>\n" ;
  foreach($dataset_res as $dataset_hit){
    $dataset        = $dataset_hit["dataset"];
    $protein_count  = $dataset_hit["protein_count"];
    $inner_xml_str .= "  <entry>\n".
                      "    <dataset>$dataset</dataset>\n".
                      "    <protein_count>$protein_count</protein_count>\n".
                      "  </entry>\n";
    }
  }
else{
  $inner_xml_str .= "  <query_str>1</query_str>\n";
}
$xml_str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
           "<datasets>\n".
           "$inner_xml_str".
           "</datasets>\n";
print $xml_str;
?>
