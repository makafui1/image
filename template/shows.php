<?php
	ini_set('display_errors', 1);
	error_reporting (E_ALL);
	require_once('../_config.php');
	require_once('../_functions.php');
	require_once('../_core.php');

	$query = $_SERVER['REDIRECT_QUERY_STRING'];
	$p_ar = explode('/',$_SERVER["REQUEST_URI"]);
	$p_end = end($p_ar);
	$ext_array = array('image/png'=>'image.png','image/jpeg'=>'image.jpg','image/gif'=>'image.gif');
	$thumb_array = array('image/png'=>'.png','image/jpeg'=>'.jpg','image/gif'=>'.gif');
	
	if(isset($p_ar[2]) && in_array($p_ar[2],$ext_array)){
		$res_post = mysqli_query($db,"SELECT * FROM ".$SET['db']['sql_tbl_prefix']."images WHERE short_url = '".$p_ar[1]."' LIMIT 1");
		if(mysqli_num_rows($res_post)>0){
			$line = mysqli_fetch_assoc($res_post);			
			$filename = explode("/",$line['file_name']);
			$img = end($filename);
			$folder = $filename[0];
			$img_expl = explode(".",$img);
			$img_file = '../images/'.$folder."/".$line['short_url'].".".end($img_expl);
			if($line['lifespan'] > 0){
				$lifespan = $line['lifespan']*24*60*60;
				if(time() - $line['date_add'] > $lifespan){
					unlink($img_file);
					mysqli_query($db,"UPDATE ".$SET['db']['sql_tbl_prefix']."images SET img_del = 1 WHERE id = {$line['id']} LIMIT 1");
					_loc($home.$line['short_url']);
				}
			}
			header('Content-Type: '.array_search($p_ar[2],$ext_array));
			echo(file_get_contents($img_file)); 
		}
	exit();
	}elseif(isset($p_ar[2]) && in_array(str_replace("thumb",'',$p_ar[2]),$thumb_array)){
		
		
		$res_post = mysqli_query($db,"SELECT * FROM ".$SET['db']['sql_tbl_prefix']."images WHERE short_url = '".$p_ar[1]."' LIMIT 1");
		if(mysqli_num_rows($res_post)>0){
			$line = mysqli_fetch_assoc($res_post);
			$filename = explode("/",$line['file_name']);
			$img = end($filename);			
			$folder = $filename[0];
			$img_ex = explode(".",$img);
			$img_file = '../images/'.$folder."/".$line['short_url'].".".end($img_ex);
			
			header('Content-Type: '.array_search('.'.end($img_ex),$thumb_array));
			if(end($img_ex) != 'gif'){
				$myimage = resizeImage($img_file, $SET['thumb_width'], $SET['thumb_height']);
			}else{
				
				$myimage = file_get_contents($img_file);
			}
			print $myimage;
			exit();
		}
		
		exit();
	}
	require_once('_header.php');
	
	$qdot = explode(".",$p_end);
	
	$q_end = end($qdot);
	
	if(count($qdot)==1){
		$res_post = mysqli_query($db,"SELECT *, 
		(SELECT name FROM ".$SET['db']['sql_tbl_prefix']."users WHERE id = ".$SET['db']['sql_tbl_prefix']."images.id_user LIMIT 1) as user_name,
		(SELECT file FROM ".$SET['db']['sql_tbl_prefix']."abuse WHERE file = ".$SET['db']['sql_tbl_prefix']."images.short_url LIMIT 1) as abuse		
		 FROM ".$SET['db']['sql_tbl_prefix']."images WHERE short_url = '".$qdot[0]."' LIMIT 1") or exit(mysqli_error($db));
		if(mysqli_num_rows($res_post)>0){
			
			$line = mysqli_fetch_assoc($res_post);
			$filename = explode("/",$line['file_name']);
			
			$img = end($filename);
			
			$folder = $filename[0];
			
			$img_ex = explode(".",$img);
		
			$img_file = '../images/'.$folder."/".$line['short_url'].".".end($img_ex);
			
			if(!is_file('../images/'.$folder."/".$line['short_url'].".".end($img_ex))) {
				_loc('/');
				exit();
			}
			$img_info = getimagesize($img_file);
			if(isset($_SESSION['FILES'])){?>
<script>
jQuery.noConflict()(function($){
	$(document).ready(function () {
		if($("div").is("#editfile")) { 
			 $('#editfile').modal();
		}
	});
});
</script>
<?php		}?>
<div class="modal fade" id="editfile" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="post" class="form-horizontal">
      <input type="hidden" name="editfile" value="<?php echo $line['short_url']?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo mb_strtoupper($img)?></h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="url" class="col-sm-4 control-label">URL</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="url" onclick="this.select();" readonly="readonly" value="<?php echo $home.$line['short_url']?>">
            </div>
        </div> 
        <div class="form-group">
            <label for="tags" class="col-sm-4 control-label"><?php echo __('Tags','i')?></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="tags" name="tags" size="50" value="<?php if(isset($line['tags'])) echo $line['tags'];?>">
              <p class="help-block"><?php echo __('comma separated, 10 maximum','i')?></p>
            </div>
        </div> 
        <div class="form-group">
            <label for="description" class="col-sm-4 control-label"><?php echo __('Description','i')?></label>
            <div class="col-sm-8">
              <textarea class="form-control" id="description" name="description" rows="5"><?php if(isset($line['description'])) echo $line['description'];?></textarea>
            </div>
        </div> 
        <div class="form-group">
            <label for="removal_code" class="col-sm-4 control-label"><?php echo __('Removal code','i')?></label>
            <div class="col-sm-8">
              <input type="password" class="form-control" id="removal_code" name="removal_code" value="<?php if(isset($line['removal_code'])) echo $line['removal_code'];?>">
            </div>
        </div>   
        <div class="form-group">
            <label for="password" class="col-sm-4 control-label"><?php echo __('Password to download','i')?></label>
            <div class="col-sm-8">
              <input type="password" class="form-control" id="password" name="img_pas" value="<?php if(isset($line['img_pas'])) echo $line['img_pas'];?>">
            </div>
        </div> 
        <div class="form-group">
            <label for="password" class="col-sm-4 control-label"><?php echo __('Lifespan','i')?></label>
            <div class="col-sm-8">
            <select class="form-control" id="fileset_lifespan" name="lifespan">
                <option value="1"<?php if(isset($line['lifespan']) && $line['lifespan']=='1'){?> selected="selected"<?php }?>>1 <?php echo __('day','i')?></option>
                <option value="5"<?php if(isset($line['lifespan']) && $line['lifespan']=='5'){?> selected="selected"<?php }?>>5 <?php echo __('days','i')?></option>
                <option value="30"<?php if(isset($line['lifespan']) && $line['lifespan']=='30'){?> selected="selected"<?php }?>>30 <?php echo __('days','i')?></option>
                <option value="90"<?php if((isset($line['lifespan']) && $line['lifespan']=='90') || !isset($_POST['lifespan']) ){?> selected="selected"<?php }?>>90 <?php echo __('days','i')?></option>
			</select>  
            </div>
        </div>  
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
                <div class="checkbox">
                    <label>
	                    <input type="checkbox" checked="checked" name="public" value="1"> <?php echo __('Public','i')?>
                    </label>
                </div>
            </div>
        </div>   	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close','i')?></button>
        <button type="submit" class="btn btn-primary"><?php echo __('Update','i')?></button>
      </div>
      </form>
    </div>
  </div>
</div>
<div class="container" id="content" style=" padding-bottom:60px;">
<div class="row">
<div class="col-md-12">
	<h1 class="page-header"><?php if(strlen($line['abuse'])>0){?><span class="abuse_blink">[ABUSE]</span><?php }?><?php if($line['password']==''){?>
    <a href="<?php echo $img_file?>" class="btn_download" rel="nofollow" download title="<?php echo __('Download','i')?> <?php echo $img?>">
	<?php }?>
	<?php echo strtolower($img);
		$startdate= date('d.m.Y',$line['date_add']);
		$enddate = strtotime("+".$line['lifespan']." day", strtotime(preg_replace('~^(\d+)\.(\d+)\.(\d+)$~', '$3-$2-$1', $startdate))); 
		$dend = round(($enddate-time())/24/60/60); 
	
	?>
    <?php if($line['password']==''){?></a><?php }?> <small>(<?php echo formatBytes(filesize($img_file))?>)</small><div class="pull-right"><small><?php echo __('Lifespan','i').": ".$dend?></small></div></h1>
    <div class="row">
    	<div class="col-md-4">
        	<?php 
			if(isset($line['password']) && $line['password']==''){
				
				if($line['lifespan'] > 0 || $line['lifespan'] == 0 || !is_file($img_file)){
				$lifespan = $line['lifespan']*24*60*60;
				if(time() - $line['date_add'] > $lifespan){
					echo _alert('File is deleted',"danger");
				}else{
				?>
            <a href="<?php echo $line['short_url']?>.v">
               <img alt="" src="<?php echo $img_file?>" class="img-responsive img-thumbnail">
            </a>
            <?php }
				}
			
			}else{?>
            <form action="" id="get_link">
                <div class="input-group">
                 <input name="short_url" id="short_url" type="hidden" value="<?php echo $line['short_url']?>">
                 <input name="link_pass" id="link_pass" class="form-control" type="password" value="" placeholder="Password" required="required">
                 <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"><?php echo __('Get link','i')?></button>
                  </span>
                </div>
               </form>
			<?php }?>
        </div>
        <div class="col-md-8 file_info">
            <div class="mb25 font9">
                <div><strong>MD5</strong>: <?php echo md5_file($img_file)?></div>
                <div><strong>SHA1</strong>: <?php echo sha1_file($img_file)?></div>
            </div>
            <ul class="det">
                <li><i class="fa fa-user fa-fw"></i> <?php if($line['user_name'] ==''){?>anonymous<?php }else{ echo $line['user_name']; }?></li>
                <li><i class="fa fa-clock-o fa-fw"></i> <?php echo date ("F d, Y H:i:s", filemtime($img_file))?></li>
                <li><i class="fa fa-download fa-fw"></i> <?php echo __('Downloaded','i')?> <?php echo $line['d_count']?> <?php echo __('times','i')?> </li>
                <li><i class="fa fa-qrcode fa-fw"></i> <a href="#" id="btn-qr-modal">QR</a></li>
                <li><i class="fa fa-minus-circle fa-fw"></i> <a href="/?abuse=<?php echo $line['short_url']?>" rel="nofollow"><?php echo __('Abuse','i')?></a></li>
            </ul>
            <div>
            <?php if(isset($line['description']) && strlen($line['description'])>0){?>
            <p><strong><?php echo __('Description','i')?>:</strong> <i><?php echo $line['description']?></i></p>
            <?php }
			
			$soc_url = urldecode($home.$line['short_url']."/".$ext_array[$img_info['mime']]);
			?>
            <div>
                <a class="btn btn-social-icon btn-facebook" title="Facebook" href="https://www.facebook.com/sharer/sharer.php?display=popup&amp;u=<?php echo $soc_url?>"><i class="fa fa-facebook"></i></a>
                <a class="btn btn-social-icon btn-google" title="Google" href="https://plus.google.com/share?url=<?php echo $soc_url?>"><i class="fa fa-google-plus"></i></a>
                <a class="btn btn-social-icon btn-linkedin" title="Linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $soc_url?>&title=<?php echo urlencode($SET['site_name'])?>&summary=<?php echo urlencode($SET['site_name'])?>&source=<?php echo urlencode($SET['site_name'])?>"><i class="fa fa-linkedin"></i></a>
                <a class="btn btn-social-icon btn-pinterest" title="Pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo $soc_url?>&media=<?php echo $soc_url?>&description=<?php echo urlencode($home)?>"><i class="fa fa-pinterest"></i></a>
                <a class="btn btn-social-icon btn-twitter" title="Twitter" href="https://twitter.com/intent/tweet?text=<?php echo urlencode($SET['site_name'])?>&amp;url=<?php echo $soc_url?>"><i class="fa fa-twitter"></i></a>
                <a class="btn btn-social-icon btn-vk" title="VK" href="http://vk.com/share.php?url=<?php echo $soc_url?>"><i class="fa fa-vk"></i></a>
            </div>
            <?php if(isset($line['tags']) && $tags = explode(",",$line['tags'])){?>
            <?php if(count($tags) > 0 && $tags[0]!=''){?>
            <div class="clearfix mt15">
				<strong><?php echo __('Tags','i')?></strong>: 
			<?php foreach($tags as $k=>$v){
			?>
            <a href="/tags/<?php echo urlencode(mb_strtolower(trim($v)))?>"><?php echo trim($v)?></a>
            <?php 
				if(count($tags)-1 != $k) echo ",";
			}?>
            </div>
            <?php }?>
            <?php }?>
            </div>
            <?php if(isset($line['password']) && $line['password']==''){?>
            <hr>
            <a href="<?php echo $img_file?>" class="btn btn-primary btn_download" download rel="nofollow" title="Download <?php echo $img?>"><?php echo __('Download','i')?></a>
            <?php if((isset($_SESSION['user_id']) && $_SESSION['user_id'] == $line['id_user']) || (isset($_SESSION['admin']) && $_SESSION['admin'] == 'true')){?>
            <a href="#" class="btn btn-primary" id="bt_editfile"><?php echo __('Edit','i')?></a>
            <a href="/?remove=<?php echo sha1($line['short_url'])?>" class="btn btn-danger" onClick="return confirm('<?php echo __('Are you sure?','i')?>')" data-method="delete" rel="nofollow" title="<?php echo __('Remove','i')?>"><?php echo __('Remove','i')?></a>
            <?php }
			}
			?>
        </div>
        <div class="modal fade" id="qr-modal" tabindex="-1">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">QR-code</h4>
              </div>
              <div class="modal-body">
					<img src="http://chart.apis.google.com/chart?cht=qr&chs=250x250&chld=L|0&chl=<?php echo $home.$line['short_url']?>" class="center-block" alt="">
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div>
    </div>
    <hr>
    <div class="row">
    	<div class="col-md-7">
        <?php 

		
		
		$type_array = array(
			'image/jpeg' => 'JPEG (Joint Photographic Experts Group JFIF format)',
			'image/gif' => 'GIF (Graphics Interchange Format)',
			'image/png' => 'PNG (Portable Network Graphics)'
		);
		if($img_info['mime'] == 'image/jpeg'){
			$exif_data = @exif_read_data($img_file);
			if(isset($exif_data['Model'])){
			$model 				= $exif_data['Model'];
			}
			if(isset($exif_data['DateTime'])){
			$Savetime			= $exif_data['DateTime'];
			}
			if(isset($exif_data['DateTimeOriginal'])){
			$DateTimeOriginal  	= $exif_data['DateTimeOriginal'];
			}
			if(isset($exif_data['DateTimeDigitized'])){
			$DateTimeDigitized 	= $exif_data['DateTimeDigitized'];
			}
		}else{
			$exif_data  =array();
		}
		$size 				= $img_info[0].'x'.$img_info[1];
		
		?>
        <table class="table table-bordered table-condensed">
            <tbody>
                <tr>
                    <td colspan="2"><h4><?php echo __('General','i')?></h4></td>
                </tr>
                <tr>
                    <td><?php echo __('Format','i')?>:</td>
                    <td><?php echo $type_array[$img_info['mime']]?></td>
                </tr>
                <tr>
                    <td><?php echo __('Size','i')?>:</td>
                    <td><?php echo $size?></td>
                </tr>
                <tr>
                    <td><?php echo __('Print size','i')?>:</td>
                    <td><?php echo $size?></td>
                </tr>
                <tr>
                    <td><?php echo __('Color depth','i')?>:</td>
                    <td><?php echo $img_info['bits']?>-bit</td>
                </tr>
                <tr>
                    <td><?php echo __('Color space','i')?>:</td>
                    <td>sRGB</td>
                </tr>
                <tr>
                    <td><?php echo __('Palette','i')?>:</td>
                    <td>True color</td>
                </tr>
                <?php if(isset($exif_data) && count($exif_data)>0 && 
				(isset($DateTimeOriginal) || isset($DateTimeDigitized) || isset($Savetime) || isset($model))
				){
					?>
                <tr>
                    <td colspan="2"><h4>EXIF</h4></td>
                </tr>
                <?php if(isset($DateTimeOriginal)){?>
                <tr>
                    <td><?php echo __('Shot time','i')?>:</td>
                    <td><?php echo $DateTimeOriginal?></td>
                </tr>
                <?php }?>
                <?php if(isset($DateTimeDigitized)){?>
                <tr>
                    <td><?php echo __('Digitized time','i')?>:</td>
                    <td><?php echo $DateTimeDigitized?></td>
                </tr>
                <?php }?>
                <?php if(isset($Savetime)){?>
                <tr>
                    <td><?php echo __('Save time','i')?>:</td>
                    <td><?php echo $Savetime?></td>
                </tr>
                <?php }?>
                <?php if(isset($model)){?>
                <tr>
                    <td><?php echo __('Camera model','i')?>:</td>
                    <td><?php echo $model?></td>
                </tr>
                <?php }?>
                <?php }?>
            </tbody>
        </table>
        </div>
        <div class="col-md-5">
        	<?php if(isset($line['password']) && $line['password']==''){?>
            <form action="#" class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="fake optional control-label" style="padding-top:0" for="fileset_thumb_html">HTML (<?php echo __('preview','i')?>)</label>
                    <div class="controls">
                        <input class="form-control" id="thumb_html" name="thumb_html" onclick="this.select();" readonly="readonly" type="text" value="<a href='<?php echo $home.$line['short_url']?>.v'><img src='<?php echo $home.$line['short_url']?>/thumb.<?php echo end(explode(".",$img))?>'/></a>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="fake optional control-label" for="fileset_image_html">HTML (<?php echo __('image','i')?>)</label>
                    <div class="controls">
                        <input class="form-control" id="image_html" name="image_html" onclick="this.select();" readonly="readonly" type="text" value="<a href='<?php echo $home.$line['short_url']?>.v'><img src='<?php echo $home.$line['short_url']?>/image.<?php echo end(explode(".",$img))?>'/></a>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="fake optional control-label" for="fileset_thumb_bb">BB (<?php echo __('preview','i')?>)</label>
                    <div class="controls">
                        <input class="form-control" id="thumb_bb" name="thumb_bb" onclick="this.select();" readonly="readonly" type="text" value="[url=<?php echo $home.$line['short_url']?>.v][img]<?php echo $home.$line['short_url']?>/thumb.<?php echo end(explode(".",$img))?>[/img][/url]">
                    </div>
                </div>
                <div class="form-group">
                    <label class="fake optional control-label" for="fileset_image_bb">BB (<?php echo __('image','i')?>)</label>
                    <div class="controls">
                        <input class="form-control" id="image_bb" name="image_bb" onclick="this.select();" readonly="readonly" type="text" value="[url=<?php echo $home.$line['short_url']?>.v][img]<?php echo $home.$line['short_url']?>/image.<?php echo end(explode(".",$img))?>[/img][/url]">
                    </div>
                </div>
                <div class="form-group">
                    <label class="fake optional control-label" for="fileset_direct_link"><?php echo __('Direct link','i')?></label>
                    <div class="controls">
                        <input class="form-control" id="direct_link" name="direct_link" onclick="this.select();" readonly="readonly" type="text" value="<?php echo $home.$line['short_url']."/".$ext_array[$img_info['mime']]?>">
                    </div>
                </div>
            </form>
            <?php }?>
        </div>
    </div>
</div>
</div>    
</div>
<?php
		}else{
			echo '<center class="http-error">
					<h1>Oops, 404 Error</h1>
					<p>The page you were looking for could not be found.</p>
				 </center>';
				 
		}
	}else{
		if($qdot[1]=='v'){
			$res_post = mysqli_query($db,"SELECT * FROM ".$SET['db']['sql_tbl_prefix']."images WHERE short_url = '".$qdot[0]."' LIMIT 1") or exit(mysqli_error($db));
			if(mysqli_num_rows($res_post)>0){
				$line = mysqli_fetch_assoc($res_post);
				$filename = explode("/",$line['file_name']);
				$img = end($filename);				
				$folder = $filename[0];
				$img_ex = explode(".",$img);
				$img_file = '../images/'.$folder."/".$line['short_url'].".".end($img_ex);
				$img_info = getimagesize($img_file);
				if(isset($line['password']) && $line['password']==''){
				?>
    <div class="container" id="content">
        <div class="row">
            <div class="col-md-12 text-center">
                <div style="position:relative; display:inline-block;">
            	<a href="<?php echo $line['short_url']?>" style="z-index:4">
                	<img alt="" src="<?php echo $img_file?>" class="img-responsive thumbnail center-block">
                </a>    
                <div class="btn-group" style="position:absolute; left:100%; top:10px; z-index:10;  background-color:#101010">
                    <a href="<?php echo $home.$line['short_url']?>" class="btn" style=" color: #fff;"><i class="fa fa-long-arrow-left fa-fw fa-2x"></i></a>
                    <a href="<?php echo $home.$line['short_url']."/".$ext_array[$img_info['mime']]?>" class="btn" style=" color: #fff"><i class="fa fa-tv fa-fw fa-2x"></i></a>
                </div>

                </div>
            </div>
            <?php 			
			$soc_url = urldecode($home.$line['short_url']."/".$ext_array[$img_info['mime']]);?>
			<div class="text-center">
                <a class="btn btn-social-icon btn-facebook" title="Facebook" href="https://www.facebook.com/sharer/sharer.php?display=popup&amp;u=<?php echo $soc_url?>"><i class="fa fa-facebook"></i></a>
                <a class="btn btn-social-icon btn-google" title="Google" href="https://plus.google.com/share?url=<?php echo $soc_url?>"><i class="fa fa-google-plus"></i></a>
                <a class="btn btn-social-icon btn-linkedin" title="Linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $soc_url?>&title=<?php echo urlencode($SET['site_name'])?>&summary=<?php echo urlencode($SET['site_name'])?>&source=<?php echo urlencode($SET['site_name'])?>"><i class="fa fa-linkedin"></i></a>
                <a class="btn btn-social-icon btn-pinterest" title="Pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo $soc_url?>&media=<?php echo $soc_url?>&description=<?php echo urlencode($home)?>"><i class="fa fa-pinterest"></i></a>
                <a class="btn btn-social-icon btn-twitter" title="Twitter" href="https://twitter.com/intent/tweet?text=<?php echo urlencode($SET['site_name'])?>&amp;url=<?php echo $soc_url?>"><i class="fa fa-twitter"></i></a>
                <a class="btn btn-social-icon btn-vk" title="VK" href="http://vk.com/share.php?url=<?php echo $soc_url?>"><i class="fa fa-vk"></i></a>                 
            </div>
            <?php
            if(isset($SET['ads_468x60']) && strlen($SET['ads_468x60'])>0){
				echo "<div class='text-center' style='margin-top:25px;'>".stripslashes($SET['ads_468x60'])."</div>";
			}
			?>
        </div>
    </div>			
<?php			}else{?>
			<div class="container" id="content">
        <div class="row">
        	<div class="col-md-3"></div>
            <div class="col-md-6">
			  <form action="" id="get_link">
                <div class="input-group">
                 <input name="short_url" id="short_url" type="hidden" value="<?php echo $line['short_url']?>">
                 <input name="link_pass" id="link_pass" class="form-control" type="password" value="" placeholder="Password" required="required">
                 <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"><?php echo __('Get link','i')?></button>
                  </span>
                </div>
               </form>
               </div>
               <div class="col-md-3"></div>
               </div>
               </div>
              <?php 
				}
			}
		}
		
	}
	require_once('_footer.php');
?>