<?php
$accession    = $_POST["accession"];
$species_name = $_POST["species_name"];
$comment_num  = $_POST["comment_num"];
$db_host      = "localhost";
$db_user      = "dummy";
$db_pass      = "";
$db_name      = "orthologues";
$db_cxn       = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
mysqli_set_charset($db_cxn, "utf8");
$comments_q   = "SELECT `comments` FROM `$species_name` WHERE `accession` = \"$accession\" ;";
$comments_res = mysqli_query($db_cxn,$comments_q);
$comments_num = mysqli_num_rows($comments_res);
$upd_comments_str = "";
if($comments_num == 1 ){
	$comments_row = mysqli_fetch_assoc($comments_res);
	$comments_str = $comments_row["comments"];
	$comments_arr = explode("|",$comments_str);
	foreach($comments_arr as $comment){
		$comment_arr = explode(":",$comment);
		if($comment_arr[0] != $comment_num){
			$upd_comments_str .= $comment."|";
			}
		}
	$upd_comments_str = rtrim($upd_comments_str,"|");
	$update_q     = "UPDATE `$species_name` SET `comments` = \"$upd_comments_str\" WHERE `accession` = \"$accession\" ;";
	$update_res   = mysqli_query($db_cxn,$update_q);
	if( $update_res){
		$inner_xml_str = "  <query_str>0</query_str>\n".
						 "  <species_name>$species_name</species_name>\n".
						 "  <accession>$accession</accession>\n".
						 "  <old_comments>$comments_str</old_comments>\n".
						 "  <new_comments>$upd_comments_str</new_comments>\n";
		}
	else{
		$inner_xml_str .= "  <query_str>1</query_str>\n";
		}
	}


$xml_str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
           "<remove_comment>\n".
           "$inner_xml_str".
           "</remove_comment>\n";
print $xml_str;
?>