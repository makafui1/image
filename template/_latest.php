<div class="container">
	<div class="row">
    	<div class="col-md-12"> 
	     	<h1 class="page-header pull-left"><?php echo __('Latest files','i')?></h1>
            <?php
            if(isset($SET['ads_468x60']) && strlen($SET['ads_468x60'])>0){
				echo "<div class='pull-right'>".stripslashes($SET['ads_468x60'])."</div>";
			}
			?>
        </div>
    </div>
    <script>
	jQuery.noConflict()(function($){
	if($("div").is(".foxsash_container")) {
			var $container = $('.foxsash_container');
			$container.imagesLoaded(function() {
					$container.masonry({
					  itemSelector: '.foxsash_item',
					  isAnimated: true,
					});
			});
			<?php if(isset($SET['infinite_scroll']) && $SET['infinite_scroll'] == 1){?>
			$container.infinitescroll({
				// selector for the paged navigation (it will be hidden)
				navSelector  : "ul.pagination",
				// selector for the NEXT link (to page 2)
				nextSelector : "ul.pagination a.forward",
				// selector for all items you'll retrieve
				itemSelector : ".foxsash_item",
				animate      : true,
		
				// finished message
				loading: {
					finishedMsg: 'No more pages to load.'
					}
				},
		
				// Trigger Masonry as a callback
				function( newElements ) {
					// hide new items while they are loading
					var $newElems = $( newElements ).css({ opacity: 0 });
					// ensure that images load before adding to masonry layout
					$newElems.imagesLoaded(function(){
						// show elems now they're ready
						$newElems.animate({ opacity: 1 });
						$container.masonry( 'appended', $newElems, true );
					});
		
			});
			<?php }?>
			
		}
	});
	</script>	
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
						$sql = "SELECT *, (SELECT name FROM ".$SET['db']['sql_tbl_prefix']."users WHERE id = ".$SET['db']['sql_tbl_prefix']."images.id_user LIMIT 1) as user_name FROM ".$SET['db']['sql_tbl_prefix']."images WHERE public = 1 AND password = '' ORDER BY date_add DESC LIMIT ".$begin.", ".$onpage;
						$result = mysqli_query($db,$sql) or die(mysqli_error($db).$count);
						return $result;
				}
				function navigation($onpage, $page, $table)
				{
					global  $SET,$db;
					
					$re = '';
					$count = mysqli_query($db,"SELECT COUNT(id) FROM ".$SET['db']['sql_tbl_prefix']."images WHERE public = 1 AND password = ''") or die(mysqli_error($db).$count);
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
								$re .= '<li><a href="?latest&page='.($cpage+1).'" class="forward"><i class="fa fa-forward"></i></a></li>';						
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
		 $i=0;
		 while($line = mysqli_fetch_array($result, MYSQL_ASSOC)){
			 $filename = explode("/",$line['file_name']);
			$img = end($filename);
			$fold = explode("/",$line['file_name']);
			$folder = current($fold);
			$img_expl = explode(".",$img);
			$img_file = 'images/'.$folder."/".$line['short_url'].".".end($img_expl);
			if(is_file($img_file) && $SET['latest_files_images']==1){
				$img_info = getimagesize($img_file);
				?>
			<div class="col-md-<?php echo $img_info[1]*2<$img_info[0] && $img_info[1] > 350?6:3?> foxsash_item">
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
			<?php
			if(isset($SET['ads_250']) && strlen($SET['ads_250'])>0 && $i==6){?>
            <div class="col-md-3 foxsash_item">
                  <!-- normal -->
                    <div class="ih-item square">
                        <a href="#">
                            <div class="img text-center"><?php echo stripslashes($SET['ads_250'])?></div>
                        </a>
                    </div>
                    <!-- end normal -->
			 </div>
			<?php }
			}elseif(is_file($img_file) && $SET['latest_files_images']==0){
				echo "<li><a href='".$home.$line['short_url']."'>".$img."</a> - ".formatBytes(filesize($img_file))." ".$line['tags']."</li>";
									
			}
			
		 }
  	echo "</div>";	  
	 if(strlen($navigation) > 0){
		?>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
        <div class="text-center">
            <ul class="pagination pagination-sm">
                <?php echo $navigation?>
            </ul>	
        </div>
        </div>
        <?php }	
 }else{
	 _panel(__('List empty.','i'));
 }
?>	    
		</div>
    </div>
</div>