<?php 
	if (!isset($_SESSION)) session_start();
	ini_set('display_errors', 1);
	error_reporting (E_ALL);
	
	if(file_exists('_config.php') && filesize('_config.php')>0){
		require_once('_config.php');
	}else{?>
        <script type="application/javascript">
			document.location.href="install.php";
		</script>
<?php	}
	require_once('_functions.php');
	require_once('_core.php');
	
	if(isset($_POST['form_reg'])){
		if($_POST['user_password'] == $_POST['user_confirm']){
			$result = mysqli_query($db,"SELECT * FROM ".$SET['db']['sql_tbl_prefix']."users 
			WHERE name = '".$_POST['user_name']."' 
			AND pass='".sha1(trim($_POST['user_password']))."' 
			AND email = '".$_POST['user_email']."' LIMIT 1");
			$count=mysqli_num_rows($result);
			if($count == 0){
				mysqli_query($db,"INSERT INTO ".$SET['db']['sql_tbl_prefix']."users 
					(id,name,email,pass)
					 VALUES 
					('', '{$_POST['user_name']}', '{$_POST['user_email']}',sha1({$_POST['user_password']}))");
				$id = mysqli_insert_id();
				$_SESSION['user_id'] =  $id;
				$_SESSION['user_name'] =  $_POST['user_name'];
				_loc('/');	
			}
			$error = _alert(__('This user is already registered','i'),"danger");
		}else{
			$error = _alert(__('Password doesn\'t match confirmation','i'),"danger");
		}
	}
	
	$result = mysqli_query($db,"SELECT *,
(SELECT id FROM ".$SET['db']['sql_tbl_prefix']."abuse WHERE file = ".$SET['db']['sql_tbl_prefix']."images.short_url LIMIT 1) as abuse FROM ".$SET['db']['sql_tbl_prefix']."images");
	if(mysqli_num_rows($result) > 0){
		while($line = mysqli_fetch_array($result, MYSQL_ASSOC)){
			if((time()-$line['date_add'])>(60*60*24*$line['lifespan'])){
				$filename = explode("/",$line['file_name']);
				$img = end($filename);
				$fold = explode("/",$line['file_name']);
				$folder = current($fold);
				$img_ex = explode(".",$img);
				$img_file = 'images/'.$folder."/".$line['short_url'].".".end($img_ex);
				if(is_file($img_file)){
					unlink($img_file);
				}
				mysqli_query($db,"DELETE FROM ".$SET['db']['sql_tbl_prefix']."images WHERE id=".$line['id']." LIMIT 1");	
				if(strlen($line['abuse'])>0){
					mysqli_query($db,"DELETE FROM ".$SET['db']['sql_tbl_prefix']."images WHERE id=".$line['id']." LIMIT 1");	
				}
			}
		}
		
	}		
	if(!count($_GET) || isset($_GET['main'])){
		require_once('template/_header.php');
		require_once('template/_main.php');
		require_once('template/_footer.php');
	}else{
		if(isset($_GET['remove'])){
			require_once('template/_remove.php');
		}else{
			require_once('template/_header.php');
			if(isset($_GET['login'])){require_once('template/login.php');}
			elseif(isset($_GET['register'])){require_once('template/register.php');}
			elseif(isset($_GET['private'])){require_once('template/private.php');}
			elseif(isset($_GET['about'])){$id = 1; require_once('template/_page.php');}	
			elseif(isset($_GET['policy'])){$id = 2; require_once('template/_page.php');}
			elseif(isset($_GET['faq'])){$id = 3; require_once('template/_page.php');}	
			elseif(isset($_GET['developer'])){$id = 4; require_once('template/_page.php');}					
			elseif(isset($_GET['abuse'])){require_once('template/_abuse.php');}								
			elseif(isset($_GET['latest'])){require_once('template/_latest.php');}
			elseif(isset($_GET['contacts'])){$id = 5;require_once('template/_page.php');}
			elseif(isset($_GET['change-log'])){require_once('change-log.php');}
			elseif(isset($_GET['search'])){require_once('template/_search.php');}
			elseif(isset($_GET['admin']) && isset($_SESSION['admin']) && $_SESSION['admin'] == 'true'){require_once('admin/_admin.php');}
			elseif(isset($_GET['files']) && isset($_SESSION['user_id'])){require_once('template/_files.php');}											
			elseif(isset($_GET['abuse_list']) && isset($_SESSION['admin']) && $_SESSION['admin'] == 'true'){require_once('template/_abuse_list.php');}
			elseif(isset($_GET['works'])){require_once('template/works.php');}
			require_once('template/_footer.php');
		}
	}

?>
