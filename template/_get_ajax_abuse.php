<?php if(isset($_GET['del_img'])){
	require_once('../_config.php');
	require_once('../_functions.php');
	require_once('../_core.php');
	$result = 'false';
	$res = mysqli_query($db,"SELECT *,
(SELECT id FROM ".$SET['db']['sql_tbl_prefix']."abuse WHERE file = ".$SET['db']['sql_tbl_prefix']."images.short_url LIMIT 1) as abuse	
 FROM ".$SET['db']['sql_tbl_prefix']."images WHERE id = '".$_GET['del_img']."' LIMIT 1");
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
			mysqli_query($db,"DELETE FROM ".$SET['db']['sql_tbl_prefix']."abuse WHERE id=".$line['abuse']."");	
		}
		$result = "true";
	}
	echo $result;
	}
?>	