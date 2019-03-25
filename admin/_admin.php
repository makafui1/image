<?php
require_once('admin/_header.php');
if(count($_GET) == 1 || isset($_GET['general'])){
	require_once('admin/_geniral.php');
}elseif(isset($_GET['gallery'])){
	require_once('admin/_gallery.php');
}elseif(isset($_GET['language'])){
	require_once('admin/_language.php');
}elseif(isset($_GET['edit_lang'])){
	require_once('admin/edit_lang.php');
}elseif(isset($_GET['user_list'])){
	require_once('admin/user_list.php');
}
require_once('admin/_footer.php');      