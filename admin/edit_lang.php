<?php
if(isset($_GET['del_lang']) && ($_GET['del_lang'].'php' <> $_SET['lang'])){
				$set_lang = explode('.',$_SET['lang']);
				unlink('lang/'.$_GET['del_lang'].".php");
				_loc("?admin&edit_lang=".$set_lang[0]);
}
if(count($_POST)>0 && isset($_POST['la'])){
		if(fopen('lang/'.$_POST['la'].".php", "r")){
			$code =	'<?php $name_lang = "'.$_POST['name_lang'].'";'."\n\t".'$lang = array('."\n";
			foreach($_POST['lang'] as $key => $val){
				$code .= "\t\t\t'".$key."' => array(\n";
				foreach($val as $k=>$v){
						$v = str_replace("\"", '&quot;', $v);
						$k = str_replace("\"", '&quot;', $k);						
						$code .= "\t\t\t\t"."'".$k."' => '".$v."',"."\n";
				}
				$code .="\t\t\t".'),'."\n";
			}
			
			$code .=')'."\n";
			$code .='?>';
			$code  = str_replace("''", "\"", $code);
	
					$fp = fopen('lang/'.$_POST['la'].".php", 'w+');
					$conf_test = fwrite($fp, $code);
					fclose($fp);
		}
		_loc("?admin&edit_lang=".$_POST['la']);
			
}
if(isset($_GET['generation'])){
				
				$files_array = array('admin','tags','template');
				include('lang/'.$_GET['generation'].".php");
				if(fopen('lang/'.$_GET['generation'].".php", "r")){
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

					if(file_perms('lang/'.$_GET['generation'].".php") != 666) {
						chmod('lang/'.$_GET['generation'].".php",0666);
					}
							$fp = fopen('lang/'.$_GET['generation'].".php", 'w+');
							$conf_test = fwrite($fp, $code);
							fclose($fp);
			
				}
				_loc("?admin&edit_lang=".$_GET['edit_lang']);
			
} 
if(strlen($_GET['edit_lang'])>1){ include('lang/'.$_GET['edit_lang'].".php");} ?>
			<?php foreach($lang as $key=> $val){
					foreach($val as $k_key=>$k_val){
						$lang_alf[$k_key[0]][] = array($k_key=>$k_val);
					}
				}
					
					ksort($lang_alf);
					echo '<div class="btn-group">';
					$edit_lang = explode(".",$k_val);
					$edit_lang = $edit_lang[0];
					foreach($lang_alf as $key => $val){
						echo '<a href="?admin&edit_lang='.$_GET['edit_lang'].'#'.$key.'" class="btn btn-sm btn-default loot">'.$key.'</a>';
					}?>
					<a class="btn btn-primary  btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
					<?php echo __('Action','i');?>
                    <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu pull-right">
                    <!-- dropdown menu links -->
                    <li><a href="?admin&edit_lang=<?php echo  $_GET['edit_lang']?>&generation=<?php echo  $_GET['edit_lang']?>" title="<?php echo __('Refresh language','i');?>"><i class="fa fa-refresh"></i> <?php echo __('Refresh language','i');?></a></li>
                    <?php if($_GET['edit_lang'] != 'gb' || $_GET['edit_lang'] != $SET['lang']){?>
                    <li class="divider"></li>                                
                    <li><a href="?admin&edit_lang=<?php echo  $_GET['edit_lang']?>&del_lang=<?php echo  $_GET['edit_lang']?>" onClick="return confirm('<?php echo __('Remove','i');?>?');"><i class="fa fa-times"></i> <?php echo __('Remove','i');?></a></li>
                    <?php } ?>
                  </ul> 
					</div>
                    <hr>
                    <h4 id="translate"><?php echo $name_lang?></h4><hr>
                    <form action="" method="post">
                    <input type="hidden" name="la" value="<?php echo  $_GET['edit_lang']?>">
                    <input type="hidden" name="name_lang" value="<?php echo  $name_lang?>">                   
                    <div id="alf">
                    </div>
								<table class="table table-bordered table-hover">	
                                	<thead>
                                    	<tr>
                                        	<th class="col-md-1 center">##</th>
	                                    	<th class="col-md-5"><?php echo __('Key','i');?></th>
                                            <th class="col-md-8"><?php echo __('Value','i');?></th>
                                        </tr>    
                                    </thead>
                                    <tbody>
									<?php ksort($lang);
									$af = "";
									$k = array('o'=>'Other','p'=>'Post','i'=>'Interface');
									foreach($lang as $key => $val){
										$nn=1;
										echo '<tr class="warning"><th colspan="3">'.$k[$key].'</th></tr>';
										foreach($val as $v_key=>$v_val){
										echo "<tr";
										if($af == "" || $v_key[0] != $af){
											$af = $v_key[0];
											echo " id='".$af."'";
										}
										if($v_key == $v_val && $name_lang != 'English'){
										echo " class ='danger'";
										}else{	 echo " class ='info'";}
										echo ">";
										?>
                                        	<td class="center"><?php echo  $nn++;?></td>
                                        	<td><?php echo  $v_key?></td>
                                            <td><?php
                                            if(strlen($v_val) < 100){?>
											<input type="text" value="<?php echo  $v_val?>" name="lang['<?php echo  $key?>']['<?php echo $v_key?>']" class="form-control">
                                            <?php }else{?>
                                            <textarea name="lang['<?php echo  $key?>']['<?php echo  $v_key?>']" class="form-control" rows="5"><?php echo  $v_val?></textarea>
                                            <?php }?>
                                            </td>
                                        </tr>
									<?php }
										}
									?>
                                    </tbody>
                                    <tfoot>
                                    	<tr>
                                        	<th colspan="3"><?php _b();?></th>
                                        </tr>
                                    </tfoot>
								</table>         
							</form>
                         </div>   		
			</div>