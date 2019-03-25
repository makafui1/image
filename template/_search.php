<div class="container">
	<div class="col-md-12">
    <h2 class="page-header"><?php echo __('Search','i')?></h2>
	<?php if(isset($SET['ads_728x90']) && strlen($SET['ads_728x90'])>0){
     echo "<div class='text-center' style='margin-top:35px'>".stripslashes($SET['ads_728x90'])."</div>";
     }?> 
    <div class="panel panel-primary"> <div class="panel-heading"><?php echo __('You search','i')?>: <u><b><?php echo $_GET['search']?></b></u></div></div>
    <div class="row foxsash_container">
    	<div class="col-md-12">
	<?php
	function page()
				{
					if(empty($_GET["page"])){
						$page = 0;
					} else {
						if(!is_numeric($_GET["page"])) die(__('Invalid format for the page number!','i'));
						$page = $_GET["page"];
					}
						return $page;
				}
				function sql_query($onpage, $page, $table)
				{
						global $SET,$db;
						$begin = $page*$onpage; 
						if($begin >0){$begin = $begin -$onpage;}
						$sql = "SELECT *, (SELECT name FROM ".$SET['db']['sql_tbl_prefix']."users WHERE id = ".$SET['db']['sql_tbl_prefix']."images.id_user LIMIT 1) as user_name FROM ".$SET['db']['sql_tbl_prefix']."images WHERE public = 1 AND password = '' AND file_name LIKE '%".$_GET['search']."%' ORDER BY date_add DESC LIMIT ".$begin.", ".$onpage;
						$result = mysqli_query($db,$sql) or die(mysqli_error($db).$count);
						return $result;
				}
				function navigation($onpage, $page, $table)
				{
					global  $SET,$db;
					
					$re = '';
					$count = mysqli_query($db,"SELECT COUNT(id) FROM ".$SET['db']['sql_tbl_prefix']."images WHERE public = 1 AND password = '' AND file_name LIKE '%".$_GET['search']."%'") or die(mysqli_error($db).$count);
						$count = mysqli_fetch_array($count);
						$count = $count[0];
						$pages = $count/$onpage;
							
						$itemscount= $count; 
						$itemsperpage=$onpage; 
						if (isset($_GET['page']))
							{ $cpage=$_GET['page']; }
							else { $cpage=1; }
						
						$pagedisprange=3; 
						$pagescount=ceil($itemscount/$itemsperpage); 
						$stpage=$cpage-$pagedisprange;
						if ($stpage<1) { $stpage=1; }
						$endpage=$cpage+$pagedisprange;
						if ($endpage>$pagescount) { $endpage=$pagescount; }
						if ($cpage>=1) {
							if($cpage==1){
								$re .= "<li class='disabled'><a href='#'><i class='fa fa-fast-backward'></i></a></li>";
								$re .= "<li class='disabled'><a href='#'><i class='fa fa-backward'></i></a></li>";
							}else{
							$re .= "<li><a href='?latest&page=1'><i class='fa fa-fast-backward'></i></a></li>";
							$re .= "<li><a href='?latest&page=".($cpage-1)."'><i class='fa fa-backward'></i></a></li>";
							}
						}
						if ($stpage>1) $re .= "<li class='disabled'><a href='#'>...</a></li>"; 
						for ($i=$stpage;$i<=$endpage;$i++) { 
							if ($i==$cpage) { $re .= '<li class="active"><a href="#">'.$i.'</a></li>'; }
							else { $re .= '<li><a href="?latest&page='.$i.'">'.$i.'</a></li>'; }
						}
						if ($endpage<$pagescount) $re .= "<li class='disabled'><a href='#'>...</a></li>";
						if ($cpage<=$pagescount) {			
							if($cpage == $pagescount){
								$re .= '<li class="disabled"><a href="#"><i class="fa fa-forward"></i></a></li>';						
								$re .= "<li class='disabled'><a href='#'><i class='fa fa-fast-forward'></i></a></li>";
							}else{
								$re .= '<li><a href="?latest&page='.($cpage+1).'"><i class="fa fa-forward"></i></a></li>';						
								$re .= "<li><a href='?latest&page=".($pagescount)."'><i class='fa fa-fast-forward'></i></a></li>";
							}
						}
						if($endpage <= 1) $re = '';
						return $re;

						
				}
				$onpage = 20; 
				$table = $SET['db']['sql_tbl_prefix']."images";
				$page = page(); 
				$result = sql_query($onpage, $page, $table); 
				$navigation = navigation($onpage, $page, $table); 
				$ext_array = array('image/png'=>'image.png','image/jpeg'=>'image.jpg','image/gif'=>'image.gif');
				$thumb_array = array('image/png'=>'.png','image/jpeg'=>'.jpg','image/gif'=>'.gif');
	 if(mysqli_num_rows($result)>0){
		 while($line = mysqli_fetch_array($result, MYSQL_ASSOC)){
			 $filename = explode("/",$line['file_name']);
			$img = end($filename);
			$folder = current($filename);
			$img_ex = explode(".",$img);
			$img_file = 'images/'.$folder."/".$line['short_url'].".".end($img_ex);
			if(is_file($img_file) && $SET['latest_files_images']==1){
				$img_info = getimagesize($img_file);
				?>
			<div class="col-md-<?php echo $img_info[1]*2<$img_info[0]?6:3?> foxsash_item">
                  <!-- normal -->
                    <div class="ih-item square effect13 bottom_to_top">
                        <a href="<?php echo $home.$line['short_url']?>">
                            <div class="img"><img src="<?php echo $img_file?>" alt="img"></div>
                            <div class="info">
                              <h4><?php echo $img?></h4>
                              <p><?php echo __('size','i').': '.formatBytes(filesize($img_file))."<br>".date ("F d Y H:i:s", filemtime($img_file))?></p>
                            </div>
                        </a>
                    </div>
                    <!-- end normal -->
			 </div>
			<?php }elseif(is_file($img_file) && $SET['latest_files_images']==0){
				echo "<li><a href='".$home.$line['short_url']."'>".str_replace($_GET['search'],"<b class='text-danger'>".$_GET['search']."</b>",$img)."</a> - ".formatBytes(filesize($img_file))." ".$line['tags']."</li>";
									
			}
			
		 }
  	echo "</div>";	  
	 if(strlen($navigation) > 0){
		?>
		<hr>
        <div class="text-center">
            <ul class="pagination pagination-sm">
                <?php echo $navigation?>
            </ul>	
        </div>
        <hr>
        <?php }	
 }else{
	 _panel(__('List empty.','i'));
 }
?>	    
		</div>
    </div>
</div>