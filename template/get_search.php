<?php
if(isset($_GET['keyword'])){
	require_once('../_config.php');
	require_once('../_functions.php');
	require_once('../_core.php');
	
	$result = '';
	$res_cou = mysqli_query($db,"SELECT id,file_name,short_url FROM ".$SET['db']['sql_tbl_prefix']."images WHERE file_name LIKE  '%".$_GET['keyword']."%' LIMIT 5");
	if(mysqli_num_rows($res_cou) > 0){
		while($line = mysqli_fetch_assoc($res_cou)){
			$result .=	'<li class="font11"><a href="/'.$line['short_url'].'" title="'.end(explode("/",$line['file_name'])).'">'.end(explode("/",$line['file_name'])).'</a></li>';

 	 	}   
  	}
	mysqli_close($db);
	print json_encode($result);
}
?>