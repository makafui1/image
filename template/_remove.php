<?php
$res = mysqli_query($db,"SELECT *,
(SELECT file FROM ".$SET['db']['sql_tbl_prefix']."abuse WHERE file = ".$SET['db']['sql_tbl_prefix']."images.short_url LIMIT 1) as abuse	
 FROM ".$SET['db']['sql_tbl_prefix']."images WHERE sha1(short_url) = '".$_GET['remove']."' LIMIT 1");
if(mysqli_num_rows($res)>0){
	$line = mysqli_fetch_assoc($res);
	mysqli_query($db,"DELETE FROM ".$SET['db']['sql_tbl_prefix']."images WHERE id=".$line['id']." LIMIT 1");	
	$filename = explode("/",$line['file_name']);
	$img = end($filename);
	$folder = current($filename);
	$img_ex = explode(".",$img);
	$img_file = 'images/'.$folder."/".$line['short_url'].".".end($img_ex);
	unlink($img_file);	
	if(strlen($line['abuse'])>0){
		mysqli_query($db,"DELETE FROM ".$SET['db']['sql_tbl_prefix']."abuse WHERE file=".$line['abuse']."");	
	}
}
	_loc("/");

exit();
?>