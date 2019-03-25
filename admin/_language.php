<?php
if(isset($_GET['lang_del']) && ($_GET['lang_del'].'php' <> $SET['lang'])){
				$set_lang = explode('.',$SET['lang']);
				unlink('lang/'.$_GET['lang_del'].".php");
				_loc("?admin&language");
			

}elseif(isset($_GET['gen'])){
		
	$files_array = array('admin','tags','template');
	if($_GET['gen'] != 'all'){
	include('lang/'.$_GET['gen'].".php");
	if(fopen('lang/'.$_GET['gen'].".php", "r")){
	$code_array =array();	
	$code =	'<?php $name_lang = "'.$name_lang.'";'."\n\t".'$lang = array('."\n";
		foreach($files_array as $dir){
		$res=array(); 
		$fp=scandir($dir); 
			if($fp[2]){ 
				for($i=2,$c=count($fp);$i<$c;$i++){ 
				if(is_dir($fd=$dir."/".$fp[$i])){ 
					$res[$dir][$fd]=array_shift(scandir($fd)); 
				}else{ 
					$res[$dir][]=$fp[$i]; 
				} 
			} 
		} 	
		foreach($res as $file_name){
						
			foreach($file_name as $file){				
				$regexp_text = file_get_contents($dir."/".$file);
				
				$regexp_code = "|__\((.*)\)|imuUs";
				preg_match_all($regexp_code,$regexp_text,$out);
				asort($out[1]);
				foreach($out[1] as $val){
					$val = str_replace("\"", '&quot;', $val);
					$val = trim($val,"'");
					$n_val = explode("','",$val);
	  				if(isset($n_val[1])){ $k = $n_val[1];} else{ $k ='o';}
					if(isset($lang[$k][$n_val[0]])){
						$code_array[$k][$n_val[0]] = $lang[$k][$n_val[0]];
					}else{
						$code_array[$k][$n_val[0]] = $n_val[0];
					}
					
				}
				
			}
		}	
		}


				if(isset($code_array['o'])){		
					$code_array['o']=array_unique($code_array['o']);
				}
				if(isset($code_array['p'])){		
				$code_array['p']=array_unique($code_array['p']);
				}
				if(isset($code_array['i'])){		
				$code_array['i']=array_unique($code_array['i']);							
				}
				ksort($code_array);
				unset($code_array['$val']);
				unset($code_array['$val,$type=']);
				foreach($code_array as $key => $val){
					$code .= "\t\t\t'".$key."' => array(\n";
					foreach($val as $k=>$v){
						if(isset($lang[$key][$v])){
							$code .= "\t\t\t\t"."'".$k."' => '".$v."',"."\n";
						}else{
							$code .= "\t\t\t\t"."'".$k."' => '".$v."',"."\n";
						}
						
					}
					$code .="\t\t\t".'),'."\n";
				}
				$code .=')'."\n";
		$code .='?>';
		$code  = str_replace("''", "'", $code);
		
		if(file_perms('lang/'.$_GET['gen'].".php") <> 666) {
			chmod('lang/'.$_GET['gen'].".php",0666);
		}
				$fp = fopen('lang/'.$_GET['gen'].".php", 'w+');
				$conf_test = fwrite($fp, $code);
				fclose($fp);

		}

	}elseif($_GET['gen'] == 'all'){
			$lg=scandir('lang/'); 
			unset($lg['0'],$lg['1']);		
			foreach($lg as $name){
				include('lang/'.$name);
				if(fopen('lang/'.$name, "r")){
				$code_array =array();	
				$code =	'<?php $name_lang = "'.$name_lang.'";'."\n\t".'$lang = array('."\n";
					foreach($files_array as $dir){
					$res=array(); 
					$fp=scandir($dir); 
						if($fp[2]){ 
							for($i=2,$c=count($fp);$i<$c;$i++){ 
							if(is_dir($fd=$dir."/".$fp[$i])){ 
								$res[$dir][$fd]=array_shift(scandir($fd)); 
							}else{ 
								$res[$dir][]=$fp[$i]; 
							} 
						} 
					} 	
					foreach($res as $file_name){
									
						foreach($file_name as $file){
			
							$regexp_text = file_get_contents($dir."/".$file);
							$regexp_code = "|__\('(.*)\'\)|imuUs";
							preg_match_all($regexp_code,$regexp_text,$out);
							asort($out[1]);
							foreach($out[1] as $val){
								$val = str_replace("\"", '&quot;', $val);
								$val = trim($val,"'");
								$n_val = explode("','",$val);
								if(isset($n_val[1])){ $k = $n_val[1];} else{ $k ='o';}
								if(isset($lang[$k][$n_val[0]])){
									$code_array[$k][$n_val[0]] = $lang[$k][$n_val[0]];
								}else{
									$code_array[$k][$n_val[0]] = $n_val[0];
								}
								
							}
							
						}
					}	
					}
							
					if(isset($code_array['o'])){		
						$code_array['o']=array_unique($code_array['o']);
					}
					if(isset($code_array['p'])){
						$code_array['p']=array_unique($code_array['p']);
					}
					if(isset($code_array['i'])){
						$code_array['i']=array_unique($code_array['i']);							
					}
					ksort($code_array);
					unset($code_array['$val']);
					unset($code_array['$val,$type=']);
					
					foreach($code_array as $key => $val){
						$code .= "\t\t\t'".$key."' => array(\n";
						foreach($val as $k=>$v){
							if(isset($lang[$key][$v])){
								$code .= "\t\t\t\t"."'".$k."' => '".$v."',"."\n";
							}else{
								$code .= "\t\t\t\t"."'".$k."' => '".$v."',"."\n";
							}
							
						}
						$code .="\t\t\t".'),'."\n";
					}
					$code .=')'."\n";
					$code .='?>';
					if(file_perms('lang/'.$name) != 666) {
						chmod('lang/'.$name,0666);
					}
							$fp = fopen('lang/'.$name, 'w+');
							$conf_test = fwrite($fp, $code);
							fclose($fp);
			
					}
			}
		}

		_loc("?admin&language");
	
}
if(isset($_GET['lang_del']) && ($_GET['lang_del'] != $SET['lang']) && $_GET['lang_del'] !='gb'){
				unlink('lang/'.$_GET['lang_del'].".php");
				_loc("?admin&language");

}elseif(count($_POST) && isset($_POST['language'])){
				
				$trans = include("lang/".$SET['lang'].".php");
				$_POST['name_file'] = strtolower($_POST['name_file']);
				if(@filesize("lang/".$_POST['name_file'].".php") < 1){
					$code =	'<?php $name_lang = "'.trim($_POST['language']).'";
					$lang = array(';
					foreach($lang as $key => $val){
								$val = str_replace("'", '', $key);
								$code .= '"'.$key.'" => "'.$key.'",'."\n";
							}
							$code .=')'."\n";
					$code .='?>';
					$fp = fopen('lang/'.$_POST['name_file'].".php", 'w+');
					$conf_test = fwrite($fp, $code);
					fclose($fp);
					
				}
				_loc("?admin&language");

}
?>
<div id="form-content" class="panel panel-default">
	<div class="panel-heading"><?php echo __('New language','i');?></div>
	<div class="panel-body">
    <form  method="post" action="" class="form-horizontal">
		    <input type="hidden" name="nl" value="1" />
            <div class="form-group">
              <label for="language" class="col-md-3 control-label"><?php echo __('Language','i');?></label>
              <div class="col-md-9">
	              <input type="text" class="form-control" id="language" name="language" >
              </div>
            </div>
		      <div class="form-group">
              <label for="name_file" class="col-md-3 control-label"><?php echo __('Short name - 2 character','i');?></label>
              <div class="col-md-9">
	              <input type="text" class="form-control" maxlength="2" id="name_file" name="name_file" required value="" >
              </div>
            </div>
            <hr>
        <div class="form-group">
        	<div class="col-md-offset-3 col-md-9">
	             <button type="submit" class="btn btn-primary btn-labeled"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> <?php echo __('Save','i')?></button>
            </div>
        </div>
    </form>
    </div>
</div>
<form action="" method="post">
<table class="table table-bordered table-hover table-striped">
	<thead>
    	<tr>
        	<th class="col-md-3"><?php echo __('Language name','i');?></th>
            <th class="col-md-6"><?php echo __('Progress Translate','i');?></th>
            <th class="col-md-2 center"><?php echo __('Operations','i');?></th>
        </tr>
    </thead>
    <tbody>
<?php $lang_f = scandir('lang/');
				$a = 1; 
				$count_f = count($lang_f)-2;
				foreach($lang_f as $v){
					if($v != '.' && $v != '..' && $v != '.DS_Store'){
						$nav_lang = file_get_contents("lang/".$v);
						$name_lang = explode("\n",$nav_lang);
						$regexp_code = "|\"(.*)\"|";
						$regexp_text = $name_lang[0];
						preg_match_all($regexp_code,$regexp_text,$out);
						$edit_lang = explode(".",$v);
						$edit_lang = $edit_lang[0];
						ob_start(); 
						$ret = array();
						include_once('lang/'.$v);
						$i=0;
						foreach($lang as $k=>$val){
							foreach($val as $kk=>$vv){
								if($kk==$vv){ $i++;}
							}
						}
						if(isset($lang['o'])){ $count_o = count($lang['o']);}else{$count_o=0;}
						if(isset($lang['p'])){ $count_p = count($lang['p']);}else{$count_p=0;}
						if(isset($lang['i'])){ $count_i = count($lang['i']);}else{$count_i=0;}
						$ret['count'] = $count_o +$count_p+$count_i ;
						
						if($v == "gb.php"){
							$ret['p'] =  0;	
						}else{
							
							$ret['p'] = round($i*100/($ret['count']),2);
						}

						echo serialize($ret);
						$pr = ob_get_contents(); 
						ob_end_clean();
						$pr = unserialize($pr);
				?>
                <tr>
                	<td><div class="pull-left flag flag-<?php echo $edit_lang?>" title="<?php echo $out[1][0]?>" style="margin-top:5px; margin-right:5px"></div>  <?php echo $out[1][0]?></td>
                	<td><div class="progress<?php if(round((100-$pr['p'])) != 100){echo " progress-bar-warning progress-striped active";}?>" style="margin-bottom:0">
                      <div class="progress-bar" style="width: <?php echo round((100-$pr['p']),2)?>%;"><?php echo round((100-$pr['p']),2)?>%</div>
                  </div></td>
                	<td class="center"><div class="btn-group btn-group-sm">
                       <a href="?admin&edit_lang=<?php echo $edit_lang;?>" class="btn btn-primary" title="<?php echo __('Edit','i');?>"><span class="fa fa-pencil"></span></a>
                       <a href="?admin&language&gen=<?php echo $edit_lang;?>" class="btn btn-primary" title="<?php echo __('Generate','i');?>"><span class="fa fa-refresh"></span></a>
                        <?php if($edit_lang != 'gb'){?>
                      <a href="?admin&language&lang_del=<?php echo $edit_lang;?>" class="btn btn-primary" title="<?php echo __('Remove','i')?>" onClick="return confirm('<?php echo __('Remove','i');?>?')"><span class="fa fa-times"></span></a>
                        <?php }?>
                    </div></td>  
                </tr>
                <?php } 
				}?>
	</tbody>
    <tfoot>
        <tr>
	        <td colspan="2"></td>
            <td class="center"><a href="?admin&language&gen=all" class="btn btn-primary" title="<?php echo __('Generate','i');?>"><span class="fa fa-refresh"></span></a></td>
        </tr>
    </tfoot>
</table>    
</form>			