<?php
if(isset($_GET['pass']) && isset($_GET['short_url'])){
	require_once('../_config.php');
	require_once('../_functions.php');
	require_once('../_core.php');
	
	$result = '';

	

	$res_cou = mysqli_query($db,"SELECT * FROM ".$SET['db']['sql_tbl_prefix']."images WHERE  short_url = '{$_GET['short_url']}' AND password = '{$_GET['pass']}' LIMIT 1");
	if(mysqli_num_rows($res_cou) > 0){
		$line = mysqli_fetch_assoc($res_cou);
		$result['status'] = '1';
		$filename = explode("/",$line['file_name']);
		$img = end($filename);
		$folder = $filename[0];
		$img_ex = explode(".",$img);
		$img_file = 'images/'.$folder."/".$line['short_url'].".".end($img_ex);
		$result['file']	= '<a href="'.$line['short_url'].'.v"><img alt="" src="../'.$img_file.'" class="img-responsive img-thumbnail"></a>';
		$result['download'] = '<hr><a href="'.$img_file.'" class="btn btn-primary btn_download" download rel="nofollow" title="'.__('Download','i').' '.$img.'>">'.__('Download','i').'</a>';
  	}else{
		$result['status'] =  '0';
	}
	mysqli_close($db);
	print json_encode($result);
}
?>