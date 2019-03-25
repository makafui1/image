<?php 
	 $p_ar = explode('/',$_SERVER["REQUEST_URI"]);
	 $p_end = end($p_ar);
	$home = str_replace($p_end,'',curPageURL());
	if(count($_POST) && isset($_POST['ad']) && file_perms('_config.php') == '666'){
			if($_SERVER['SERVER_ADDR'] == '151.248.126.10'){?>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <?php echo "It prohibited on demo site"?>
                    </div>
                </div>    
            </div>
<?			}else{
				
				$code =	'<?php'."\r\n".'$SET = array();'. "\n";
				unset($_POST['ad']);
				foreach($_POST as $key=> $val){
					if($key != 'db'){
						$code .= "\t".'$SET[\''.$key.'\'] = \''.addslashes($val)."';\n";
					}else{
						foreach($val as $k=>$v){
							$code .= "\t".'$SET[\'db\'][\''.$k.'\'] = \''.$v[0]."';\n";
						}
					}
				}
				$code .='?>';
				$fp = fopen('_config.php', 'w+');
				$conf_test = fwrite($fp, $code);
				fclose($fp);
				_loc($home.'?admin');
			}
	}
?>
<?php 
	$error = array();
	if(file_perms('_config.php') != '666') {$error[]= "Set permission '<strong>666</strong>' to file <b>config.php</b>";}
	if(count($error) > 0){?>
    	<div class="row">
        	<div class="col-md-4"></div>
            <div class="col-md-6">
                <div class="alert alert-danger">
                    <?php echo "<li>".implode('<li>',$error)?>
                </div>
            </div>    
        </div>
      <?php }
	    ?>
<form action="" method="post" class="form-horizontal">
	<input type="hidden" name="ad" value="1" />
<?php
foreach($SET as $key=>$val){
	if($key != 'db'){?>
    <div class="form-group">
        <label for="<?php echo $key?>" class="col-md-3 control-label"><?php echo ucfirst(str_replace("_"," ",$key));?></label>
        <div class="col-md-9">
        <?
		if($key == 'lang'){
			  	echo "<select class=\"form-control\" name='lang'>";
			  	$all_files=array();
				GetListFiles(dirname(__DIR__)."/lang",$all_files);
				foreach($all_files as $value){
					$a = explode("/",$value);
					$a1 = end($a);
					$a2 = explode(".",$a1);
					echo "<option value='".current($a2)."'";
					if(current($a2) == $SET['lang']){
						echo ' selected="selected"';
					}
					echo ">".current($a2)."</option>";
				}
				echo "</select>";
		 }elseif(!in_array($key,array('google_analytics','ads_468x60','ads_250','ads_728x90'))){?>
          <input <?php
          if(in_array(htmlspecialchars(stripslashes($val)),array('0','1'))){
			  echo 'type="checkbox" class="switch" data-animate="true"';
			   if($val == 1){ echo ' checked="checked"';  } 
		  }else{
			  echo 'type="text" class="form-control input-lg"';
			 
		  }
		  
		  ?> id="<?php echo $key?>" name="<?php echo $key?>" <?php if($key == 'version'){?> readonly="readonly"<?php }?> value="<?php echo htmlspecialchars(stripslashes($val))?>">
          <?php }else{?>
          <textarea class="form-control" id="<?php echo $key?>" name="<?php echo $key?>" rows="5"><?php echo htmlspecialchars(stripslashes($val))?></textarea>
          <?php }?>
        </div>
     </div>
 	<?php }else{
    		foreach($val as $k=>$v){?>
            <div class="form-group">
                <label for="<?php echo $k?>" class="col-md-3 control-label">MYSQL DB "<?php echo $k?>"</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="<?php echo $k?>" name="db[<?php echo $k?>][]" value="<?php echo htmlspecialchars($v)?>">
                </div>
             </div>
<?php			}?>
    <?php }?>
<?php }?>
  <hr>
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <?php _b();?>
    </div>
  </div>
</form>
<div class="clearfix"></div>