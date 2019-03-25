<?php
$db = mysqli_connect($SET['db']['sql_host'], $SET['db']['sql_user'], $SET['db']['sql_pass'],$SET['db']['sql_database']);
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
mysqli_query($db,"SET CHARSET ".$SET['db']['mysql_codepage']);
mysqli_query($db,"set character_set_client='".$SET['db']['sql_charset']."'");
mysqli_query($db,"set character_set_results='".$SET['db']['sql_charset']."'");
mysqli_query($db,"set collation_connection='".$SET['db']['sql_charset']."_general_ci'");


function __($val,$type=''){
	global $lang;
	if(isset($lang[$type][$val])){
		return $lang[$type][$val];
	}else{
		return "<i class='fa fa-exclamation-circle fa-fw'></i> ".$val;
	}
}
function _panel($data ,$type="info", $title=""){
	if($title == ''){ $title = __('Information');}
	echo "<div class=\"panel panel-".$type."\">
        <div class=\"panel-heading\">".$title."</div>
          <div class=\"panel-body\">
            ".$data."
          </div>
    </div>";
}
function abuse_show(){
	global $SET, $db;
	$line['count(*)'] = 0;
	$res = mysqli_query($db,"SELECT count(*) FROM ".$SET['db']['sql_tbl_prefix']."abuse");
	if(mysqli_num_rows($res)>0){
		$line = mysqli_fetch_assoc($res);
		
	}
	return $line['count(*)'];
}
if(!function_exists('file_perms')) {
	function file_perms($file, $octal = false)
		{
			if(!file_exists($file)) return false;
			$perms = fileperms($file);
			$cut = $octal ? 2 : 3;
			return substr(decoct($perms), $cut);
		}
}
function is_image($filename) {
  $is = @getimagesize($filename);
  if ( !$is ) {return false;}
  elseif(!in_array($is[2], array(1,2,3))){ return false;}
  else{ return true;}
}	
function resizeImage($filename, $newwidth, $newheight){

	list($width, $height) = getimagesize($filename);
    if($width > $height && $newheight < $height){
        $newheight = round($height / ($width / $newwidth));
    }elseif($width < $height && $newwidth < $width) {
        $newwidth = round($width / ($height / $newheight));    
    }else{
        
    }

    $thumb = imagecreatetruecolor($newwidth, $newheight);
	$gis = getimagesize($filename);
	
	$type        = $gis[2];
	switch($type)
        {
        case "1": $source = imagecreatefromgif($filename); break;
        case "2": $source = imagecreatefromjpeg($filename);break;
        case "3": $source = imagecreatefrompng($filename); break;
        default:  $source = imagecreatefromjpeg($filename);
        }
	
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
   	return imagejpeg($thumb);

}
function _alert($data,$type="info",$title="",$icon=''){
	$result = '<div class="alert alert-'.$type.'" role="alert">';
	if(strlen($title) !=0) {$result .= '<h4>';
		if(strlen($icon)!=0){
			$result .= '<i class="fa fa-'.$icon.'"></i> ';
		}
		$result .= $title.'</h4>';
	}
	$result .= $data.'</div>';
	return $result;
}
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
function formatBytes($size, $precision = 2){
	$base = log($size, 1024);
	$suffixes = array('', 'kb', 'Mb', 'Gb', 'Tb');   

	return round(pow(1024, $base - floor($base)), $precision) ." ". $suffixes[floor($base)];
}
function _loc($data){
	echo '<script type="application/javascript">
		document.location.href="'.$data.'";
	</script>';
	exit();
}
function short_url($count = 6){
	$words = $words_new = array();
	$low = range('a','z');
	$words = array_merge($words, $low);
	$upper = range('A','Z');
	$words = array_merge($words, $upper);
	$numerals = range('0','9');
	$words = array_merge($words, $numerals);
	
	for($n=0;$n<=$count;$n++){
		$words_new[]=$words[array_rand($words,1)];
	}
	return implode('',$words_new);
}
function GetListFiles($folder,&$all_files){
		$fp=opendir($folder);
		while($cv_file=readdir($fp)) {
			if(is_file($folder."/".$cv_file)) {
				$all_files[]=$folder."/".$cv_file;
			}elseif($cv_file!="." && $cv_file!=".." && is_dir($folder."/".$cv_file)){
				GetListFiles($folder."/".$cv_file,$all_files);
			}
		}
		closedir($fp);
	}
function _b($type = "b", $link='', $icon = "fa-floppy-o", $value = '', $id ="", $class="btn-primary"){
		if($value =='') {$value = __('Save');}
		if($type == "b"){
			$result = '<button type="submit" class="btn btn-labeled '.$class.'"><span class="btn-label"><i class="fa '.$icon.'"></i></span> '.$value.'</button>';
		}elseif($type == "a"){
			if(strlen($id) > 0) {$id = "id='".$id."' ";} else{ $id = '';}
			$result = '<a href="'.$link.'" '.$id.'class="btn btn-labeled '.$class.'"><span class="btn-label"><i class="fa '.$icon.'"></i></span> '.$value.'</a>';		
		}
		echo $result;
	}
function get_ip(){
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] <> ''){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif(isset($_SERVER['REMOTE_ADDR'])){
		$ip = $_SERVER['REMOTE_ADDR'];
	}else{
		$ip = $_SERVER['SERVER_ADDR'];
	}
	return $ip;
}
?>