<?php
if(isset($_GET['img'])){
	require_once('../_config.php');
	require_once('../_functions.php');
	require_once('../_core.php');
	
	$result = '';
	$filename = explode("/",$_GET['img']);
	$if = end($filename);
	$fold = explode(".",$if);
	$que = current($fold);

	$res_cou = mysqli_query($db,"SELECT id,d_count FROM ".$SET['db']['sql_tbl_prefix']."images WHERE  short_url = '{$que}' LIMIT 1");
	if(mysqli_num_rows($res_cou) > 0){
		$line = mysqli_fetch_assoc($res_cou);
		mysqli_query($db,"UPDATE ".$SET['db']['sql_tbl_prefix']."images SET d_count = ".($line['d_count']+1)." WHERE id = {$line['id']} LIMIT 1");
		$result = 'ready';
  	}
	mysqli_close($db);
	print json_encode($result);
}
?>