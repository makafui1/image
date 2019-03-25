<?php
if (!isset($_SESSION)) session_start();

$p_ar = explode('/',$_SERVER["REQUEST_URI"]);
$p_end = end($p_ar);
if(!function_exists('curPageURL')) {
	require_once('_config.php');
	require_once('_functions.php');
}
$home = str_replace($p_end,'',curPageURL());	
if (isset($_POST['form_login'])&& isset($_POST['login']) && isset($_POST['pass'])) {
		$result = mysqli_query($db,"SELECT * FROM ".$SET['db']['sql_tbl_prefix']."users WHERE (email='{$_POST['login']}' OR name = '{$_POST['login']}') AND pass='".sha1(trim($_POST['pass']))."' LIMIT 1");
		if(mysqli_num_rows($result) > 0){
			$line = mysqli_fetch_array($result, MYSQL_ASSOC);
			
			if($line['id'] == 1){
				$_SESSION['admin'] = 'true';
			}else{
				$_SESSION['admin'] = 'false';
			}
			
			$_SESSION['user_id'] =  $line['id'];
			$_SESSION['user_name'] =  $line['name'];
		}else{
			$_SESSION['admin'] = 'false';
			if(isset($_SESSION['user_id'])) unset($_SESSION['user_id']);
			if(isset($_SESSION['user_name'])) unset($_SESSION['user_name']);			
		}
		_loc('/');
	}
	if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['logout'])) { 
		$_SESSION = array();
		session_destroy();
		_loc('/');
	}
	if(isset($_GET['lng'])){
		$_SESSION['lng'] = $_GET['lng'];
		_loc('/');
	 }
	if(isset($_SESSION['lng'])){
		require_once('lang/'.$_SESSION['lng'].".php");
	}else{
		require_once('lang/'.$SET['lang'].".php");
	}
	
if(count($_FILES)>0 && $_FILES['fH_file']['size'] > 0){
		$file_type = explode("/",$_FILES['fH_file']['type']);
		if(current($file_type) == 'image'){
		$short_url = short_url();
		$res = mysqli_query($db,"SELECT * FROM ".$SET['db']['sql_tbl_prefix']."images WHERE short_url = '{$short_url}' LIMIT 1");
		if(mysqli_num_rows($res)==0){
			$uploaddir = 'images/';
			$folder = date('d-m-Y',time());
			if(!is_dir('images/'.$folder)){
				mkdir('images/'.$folder,0777);
			}
			$filename = explode(".",$_FILES['fH_file']['name']);
			$ext = end($filename);
			$uploadfile = $uploaddir.$folder."/".$short_url.".".$ext;
			if(copy($_FILES['fH_file']['tmp_name'], $uploadfile)){
				if(isset($_SESSION['user_id'])){
					$id_user = $_SESSION['user_id'];
				}else{
					$id_user = 0;
				}
				mysqli_query($db,"INSERT INTO ".$SET['db']['sql_tbl_prefix']."images 
					(id,id_user,file_name,short_url,lifespan,tags,date_add,d_count)
					 VALUES 
					('', '{$id_user}', '".$folder."/".$_FILES['fH_file']['name']."','{$short_url}','90','','".time()."','0')");
				$_SESSION['FILES'] = $_FILES['fH_file'];
				_loc($home.$short_url);
			}
		}
		}
}

if(count($_POST)>0 && isset($_POST['editfile'])){
	$res = mysqli_query($db,"SELECT * FROM ".$SET['db']['sql_tbl_prefix']."images WHERE short_url = '{$_POST['editfile']}' LIMIT 1");
		if(mysqli_num_rows($res)>0){
			$line = mysqli_fetch_assoc($res);			
			if(isset($_POST['public'])){$public = 1;}else{$public=0;}
			mysqli_query($db,"UPDATE ".$SET['db']['sql_tbl_prefix']."images SET 
			tags = '{$_POST['tags']}',
			description = '".mysqli_real_escape_string($db,$_POST['description'])."',
			removal_code = '".mysqli_real_escape_string($db,$_POST['removal_code'])."',
			password = '".mysqli_real_escape_string($db,$_POST['img_pas'])."',
			lifespan = '{$_POST['lifespan']}',
			public = '{$public}'
			WHERE id = {$line['id']} LIMIT 1 ");
			unset($_SESSION['FILES']);
			_loc("/".$_POST['editfile']);
		}else{	
		}
}
if(count($_POST)>0 && isset($_POST['remote'])){
	if(is_image($_POST['remote'])){
		$short_url = short_url();
		$res = mysqli_query($db,"SELECT * FROM ".$SET['db']['sql_tbl_prefix']."images WHERE short_url = '{$short_url}' LIMIT 1");
		if(mysqli_num_rows($res)==0){
			$uploaddir = 'images/';
			$folder = date('d-m-Y',time());
			if(!is_dir('images/'.$folder)){
				mkdir('images/'.$folder,0777);
			}
			$filename = explode(".",$_POST['remote']);
			$ext = end($filename);
			$file_e = explode("/",$_POST['remote']);
			$file_name = end($file_e);
			$uploadfile = $uploaddir.$folder."/".$short_url.".".$ext;
			
			$imageContent = file_get_contents($_POST['remote']);
	        file_put_contents($uploadfile, $imageContent);
			
			if(is_file($uploadfile)){
					$id_user = $_POST['userid'];
				mysqli_query($db,"INSERT INTO ".$SET['db']['sql_tbl_prefix']."images 
					(id,id_user,file_name,short_url,lifespan,tags,date_add,public,d_count)
					 VALUES 
					('', '{$id_user}', '".$folder."/".$file_name."','{$short_url}','90','','".time()."','1','0')");
				$_SESSION['FILES'] = 'remote';
				
				_loc($home.$short_url);
			}

		}
	
	}
	exit();
}
if(count($_POST)>0 && isset($_POST['abuse'])){
	mysqli_query($db,"INSERT INTO ".$SET['db']['sql_tbl_prefix']."abuse 
					(id, file, email, descr, ip, date_add)
					 VALUES 
					('', '{$_POST['abuse_file']}', '{$_POST['email']}','".mysqli_real_escape_string($db,$_POST['descr'])."','".get_ip()."','".time()."')");
	_loc($SET['home_url'].$_POST['abuse_file']);					
}
?>