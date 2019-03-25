<?php
// upload.php
// 'images' refers to your file input name attribute
$p_ar = explode('/',$_SERVER["REQUEST_URI"]);
$p_end = end($p_ar);
if(!function_exists('curPageURL')) {
	require_once('../_config.php');
	require_once('../_functions.php');
}
$home = str_replace($p_end,'',curPageURL());

if(count($_FILES)>0 && $_FILES['fH_file']['size'] > 0 && current(explode("/",$_FILES['fH_file']['type'])) == 'image'){ 
		$short_url = short_url();

		$res = mysqli_query($db,"SELECT * FROM ".$SET['db']['sql_tbl_prefix']."images WHERE short_url = '{$short_url}' LIMIT 1");
		if(mysqli_num_rows($res)==0){
			$folder = date('d-m-Y',time());
			if(!is_dir($folder)){
				mkdir($folder,0777);
			}
			$ext = end(explode(".",$_FILES['fH_file']['name']));
			$uploadfile = $folder."/".$short_url.".".$ext;
			
						
			if(copy($_FILES['fH_file']['tmp_name'], $uploadfile)){
				$output['status'] = 'ready';
				mysqli_query($db,"INSERT INTO ".$SET['db']['sql_tbl_prefix']."images 
					(id,id_user,file_name,short_url,lifespan,tags,date_add,public,d_count)
					 VALUES 
					('', '{$_POST['userid']}', '".$folder."/".$_FILES['fH_file']['name']."','{$short_url}','90','','".time()."','1','0')");
				$output['short_url'] = $SET['home_url'].$short_url;
				echo json_encode($output);					

			}

		}
}
?>