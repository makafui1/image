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
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $SET['site_description']?>">
    <meta name="keywords" content="<?php echo $SET['site_keywords']?>">
    <meta name="author" content="<?php echo $SET['site_author']?>">

    <title><?php echo $SET['site_name']?></title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/assets/css/fileinput.min.css" media="all"  type="text/css" />

	<link rel="stylesheet" href="/assets/css/jasny-bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-switch.min.css">
    <link rel="stylesheet" href="/assets/css/ihover.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-social.css">
    <link rel="stylesheet" href="/assets/css/main_style.css">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="/<?php echo $SET['site_logo']?>" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] == 'true'){?>
    <script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
    <?php }?>
  </head>
  <body>
    <?php require_once('_menu.php');?>