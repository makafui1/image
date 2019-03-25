<!-- header -->  
<!DOCTYPE html>
<!-- 
Script Name: Images Hosting with Twitter Bootstrap 3.3.5
Version: <?php echo $SET['version']."\n"?>
Author: FoxSash
Website: http://www.foxsash.com/
Contact: foxsash82@gmail.com
Follow: https://twitter.com/FoxSash_RU
Purchase: 
http://themeforest.net/user/FoxSash/portfolio?ref=FoxSash
http://codecanyon.net/user/FoxSash/portfolio?ref=FoxSash
License: You must have a valid license purchased only from codecanyon.net(the above link) in order to legally use the script for your project.
-->
<?php
	ini_set('display_errors', 1);
	error_reporting (E_ALL);
$error = array();
if(!function_exists('file_perms')) {
	function file_perms($file, $octal = false)
	{
		if(!file_exists($file)) return false;
		$perms = fileperms($file);
		$cut = $octal ? 2 : 3;
		return substr(decoct($perms), $cut);
	}
}
if(count($_POST) > 0){
	if (isset($_SESSION)){
		$error=array();
		$_SESSION = array();
		session_destroy();
	}
	
	if(empty($_POST['host']))			{$error[]= "Empty <b>SQL Host</b>";}
	if(empty($_POST['sql_database']))	{$error[]= "Empty <b>SQL Database</b>";}
	if(empty($_POST['sql_user']))		{$error[]= "Empty <b>SQL User</b>";}
	if(empty($_POST['sql_pass']))		{$error[]= "Empty <b>SQL Password</b>";}
	if(empty($_POST['base_url']))		{$error[]= "Empty <b>Base URL</b>";}
	if(empty($_POST['name']))			{$error[]= "Empty <b>Administrator</b>";}
	if(empty($_POST['pass']))			{$error[]= "Empty <b>Password</b>";}
	if(empty($_POST['email']))			{$error[]= "Empty <b>Email</b>";}	
	
	$connect = @mysqli_connect(trim($_POST['host']),trim($_POST['sql_user']),trim($_POST['sql_pass']),trim($_POST['sql_database']));
	
	$v=@mysqli_fetch_array(mysqli_query($db,"SELECT VERSION();"));
	$mysql_ver =  explode(".", $v[0]);
	$mysql_ver = $mysql_ver[0];
	
	$php_ver = explode(".", phpversion());
	$php_ver = $php_ver[0];
	if(file_perms('images') <> 77) {$error[]= "Set permission '<strong>777</strong>' to folder <b>images</b>";}
	if(file_perms('lang') <> 77) {$error[]= "Set permission '<strong>777</strong>' to folder <b>lang</b>";}

	if(isset($mysql_ver) && $mysql_ver>5){$error[]= "MySQL version must be more than <b>5</>";}
	if(isset($php_ver) && $php_ver>5){$error[]= "PHP version must be more than <b>5</b>";}

	if(!$connect){$error[]= "Error: <b>Connection to the database</b>";}
		
	if(!count($error)){
			if(fopen('_config.php', "w+")){
				$code =	'<?php 
$SET = array();
$SET[\'version\'] = \'1.3\';
$SET[\'db\'][\'sql_host\'] = \''.$_POST['host'].'\';
$SET[\'db\'][\'sql_user\'] = \''.$_POST['sql_user'].'\';
$SET[\'db\'][\'sql_pass\'] = \''.$_POST['sql_pass'].'\';
$SET[\'db\'][\'sql_database\'] = \''.$_POST['sql_database'].'\';
$SET[\'db\'][\'mysql_codepage\'] = \'utf8\';
$SET[\'db\'][\'sql_charset\'] = \'utf8\';
$SET[\'db\'][\'sql_tbl_prefix\'] = \''.$_POST['sql_tbl_prefix'].'\';
$SET[\'show_version\'] = \'1\';
$SET[\'copyright\'] = \'Programming by <a href=\"http://codecanyon.net/user/FoxSash/?ref=FoxSash\" target=\"_blank\">FoxSash</a>\';
$SET[\'site_name\'] = \'ImgHosting\';
$SET[\'home_url\'] = \''.$_POST['base_url'].'\';
$SET[\'site_logo\'] = \'assets/img/logo.png\';
$SET[\'abuse_email\'] = \''.$_POST['email'].'\';
$SET[\'site_author\'] = \'FoxSash\';
$SET[\'site_description\'] = \'Free file hosting without waiting and captcha. Preview for images. ImgHosting — fast and easy file sharing.\';
$SET[\'site_keywords\'] = \'file exchange, free file exchange, best file exchange, fast file exchange, file hosting, file storage, file sharing, image hosting, fotohosting, videohosting, audiohosting, share file, share image, review files, preview\';
$SET[\'lang\'] = \'gb\';
$SET[\'watermark\'] = \'1\';
$SET[\'watermark_text\'] = \'img-hosting.foxsash.com\';
$SET[\'watermark_color\'] = \'#6f5499\';
$SET[\'watermark_opacity\'] = \'80\';	
$SET[\'latest_files_images\'] = \'0\';
$SET[\'page_about\'] = \'1\';
$SET[\'page_policy\'] = \'1\';
$SET[\'page_faq\'] = \'1\';
$SET[\'page_developer\'] = \'1\';
$SET[\'page_contacts\'] = \'1\';
$SET[\'google_analytics\'] = \'\';
$SET[\'ads_468x60\'] = \'\';
$SET[\'ads_250\'] = \'\';
$SET[\'ads_728x90\'] = \'\';
$SET[\'thumb_width\'] = \'250\';
$SET[\'thumb_height\'] = \'250\';
$SET[\'infinite_scroll\'] = \'0\';
$SET[\'show_language\'] = \'0\';
$SET[\'show_tags_cloud\'] = \'1\';
?>';

				$fp = fopen('_config.php', 'w+');
				$conf_test = fwrite($fp, $code);
				fclose($fp);
				require_once('_config.php');
				require_once('_functions.php');
				require_once('_core.php');			

							
mysqli_query ($db,"SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';");

mysqli_query ($db,"CREATE TABLE IF NOT EXISTS `".$SET['db']['sql_tbl_prefix']."abuse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `descr` text NOT NULL,
  `ip` varchar(250) NOT NULL,
  `date_add` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");


mysqli_query ($db,"CREATE TABLE IF NOT EXISTS `".$SET['db']['sql_tbl_prefix']."images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `file_name` text NOT NULL,
  `short_url` varchar(11) NOT NULL,
  `tags` text NOT NULL,
  `description` text NOT NULL,
  `removal_code` varchar(11) NOT NULL,
  `password` varchar(11) NOT NULL,
  `lifespan` int(11) NOT NULL,
  `date_add` int(11) NOT NULL,
  `public` int(1) NOT NULL,
  `img_del` int(1) NOT NULL,
  `d_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");


mysqli_query ($db,"CREATE TABLE IF NOT EXISTS `".$SET['db']['sql_tbl_prefix']."page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");


mysqli_query ($db,"INSERT INTO `".$SET['db']['sql_tbl_prefix']."page` (`id`, `title`, `text`) VALUES
(1, 'About us', 'Your text here'),
(2, 'Policy and Term', '<p>Your text here</p>'),
(3, 'FAQ', 'Your text here'),
(4, 'Developer', 'Your text here'),
(5, 'Contact', 'Your text here');");

mysqli_query ($db,"CREATE TABLE IF NOT EXISTS `".$SET['db']['sql_tbl_prefix']."users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `pass` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;");


mysqli_query ($db,"INSERT INTO `".$SET['db']['sql_tbl_prefix']."users` (`id`, `name`, `email`, `pass`) VALUES
(1, '".$_POST['name']."', '".$_POST['email']."', '".sha1($_POST['pass'])."')");
_loc($_POST['base_url']);

				}else{
					
				$error[] = "Abortive to create file '_config.php'";
				}
	}
}
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Image Storage System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Fast Email Sender">
    <meta name="author" content="Foxsash">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>  
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </head>

<body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-55392426-1', 'auto');
  ga('send', 'pageview');

</script>
<?php 
if(!function_exists('curPageURL')) {
	function curPageURL(){
	$pageURL = 'http://';
	if ($_SERVER["SERVER_PORT"] != "80") {
	 $pageURL .=
	 $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	}
	else {
	 $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	
	return $pageURL;
}
}


$p_ar = explode('/',$_SERVER["REQUEST_URI"]);
$p_end = end($p_ar);
$home = str_replace($p_end,'',curPageURL());	
?>
	<div class="container">
    	<div class="row">
          <?php if(count($error) > 0){?>
           	<div class="col-md-6">
          		<div class="alert alert-danger" style="margin-top:200px;">
                	<?php echo "<li>".implode('<li>',$error)?>
                </div>
            </div>    
		  <?php }?>
    	<div class="col-md-<?php if(count($error) > 0){?>6<?php }else{?>12<?php }?>">
        <form class="form-horizontal" action="" method="post">
        <div class="modal" style="display:block">
         <div class="modal-dialog">
               <div class="modal-content">
          <div class="modal-header">
			<h2><img src="assets/img/logo.png" height="24"> Install: ImgHosting</h2>
          </div>
          <div class="modal-body">
              <div class="form-group">
                <label class="col-md-3 control-label" for="host">MySQL Server</label>
                <div class="col-md-9">
                  <input type="text" id="host"  class="form-control"  name="host" value="<?php if(isset($_POST['host'])){echo $_POST['host'];}else{ echo "localhost";}?>" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="sql_database">MySQL database</label>
                <div class="col-md-9">
                  <input type="text" id="sql_database"  class="form-control"  name="sql_database" value="<?php if(isset($_POST['sql_database'])){echo $_POST['sql_database'];}?>" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="sql_user">MySQL user</label>
                <div class="col-md-9">
                  <input type="text" id="sql_user"  class="form-control"  name="sql_user" value="<?php if(isset($_POST['sql_user'])){echo $_POST['sql_user'];}?>" required>
                </div>
              </div>
             	<div class="form-group">
                <label class="col-md-3 control-label" for="sql_pass">MySQL password</label>
                <div class="col-md-9">
                  <input type="text" id="sql_pass"  class="form-control"  name="sql_pass" value="<?php if(isset($_POST['sql_pass'])){echo $_POST['sql_pass'];}?>" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="sql_tbl_prefix">Table prefix</label>
                <div class="col-md-9">
                  <input type="text" id="sql_tbl_prefix"  class="form-control"  name="sql_tbl_prefix" value="<?php if(isset($_POST['sql_tbl_prefix'])){echo $_POST['sql_tbl_prefix'];} else {echo "imh_";}?>">
                </div>
              </div>
              <hr>
               <div class="form-group">
                <label class="col-md-3 control-label" for="base_url">Base url</label>
                <div class="col-md-9">
                  <input type="text" id="base_url"  class="form-control"  name="base_url" value="<?php echo $home;?>" required>
                </div>
              </div>
              <hr>
               <div class="form-group">
                <label class="col-md-3 control-label" for="name">Administrator</label>
                <div class="col-md-9">
                  <input type="text" id="name" name="name"  class="form-control"  value="<?php if(isset($_POST['name'])){echo $_POST['name'];}?>" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="email_from">Email</label>
                <div class="col-md-9">
                  <input type="email" id="email_from"  class="form-control"  name="email" value="<?php echo 'info@'.$_SERVER['SERVER_NAME']?>" required>
                </div>
              </div>
		     <div class="form-group">
                <label class="col-md-3 control-label" for="pass">Password</label>
                <div class="col-md-9">
                  <input type="password" id="pass" name="pass"  class="form-control"  value="<?php if(isset($_POST['pass'])){echo $_POST['pass'];}?>" required>
                </div>
              </div>
          </div>
          <div class="modal-footer">
          	<div class="row">
            <div class="col-md-6">
             <div class="font11 text-left">
             2015 <?php if(date("Y",time()) != 2015){?>- <?php echo date("Y",time());}?> Image Storage System 1.3<br>&copy; Programming by <a href="http://codecanyon.net/user/FoxSash/?ref=FoxSash" target="_blank">FoxSash</a>
             </div>
             </div>
             <div class="col-md-6">
	             <div class="pull-right"><button type="submit" class="btn  btn-primary">Install</button></div>
             </div>
             </div>
          </div>
        </div>
       </form>
        </div>
        </div>
        </div>
    </div>
  </body>
</html>