<?php
	require_once('../_config.php');
	require_once('../_functions.php');
	require_once('../_core.php');
	require_once('../template/_header.php');
	
	 $tag = $_SERVER['QUERY_STRING'];
	?> <script>
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
	<div class="container">
    <div class="row">
    	<div class="col-md-12">
        	<h1 class="page-header"><?php echo $tag?></h1>
		</div>
    </div>
	<div class="row foxsash_container">
    	
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
						global $SET,$tag,$db;
						$begin = $page*$onpage; 
						if($begin >0){$begin = $begin -$onpage;}
						$sql = "SELECT *, (SELECT name FROM ".$SET['db']['sql_tbl_prefix']."users WHERE id = ".$SET['db']['sql_tbl_prefix']."images.id_user LIMIT 1) as user_name FROM ".$SET['db']['sql_tbl_prefix']."images WHERE public = 1 AND password = '' AND tags LIKE  '%".$tag."%' ORDER BY date_add DESC LIMIT ".$begin.", ".$onpage;
						$result = mysqli_query($db,$sql);
						return $result;
				}
				function navigation($onpage, $page, $table)
				{
					global  $SET,$tag,$db;
					
					$re = '';
					$count = mysqli_query($db,"SELECT COUNT(id) FROM ".$SET['db']['sql_tbl_prefix']."images WHERE public = 1 AND password = '' AND  tags LIKE  '%".$tag."%'") or die(mysqli_error($db).$count);
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
			
			$img = end(explode("/",$line['file_name']));
			$folder = current(explode("/",$line['file_name']));
			$img_file = '../images/'.$folder."/".$line['short_url'].".".end(explode(".",$img));
			if(is_file($img_file)){
				$img_info = getimagesize($img_file);
				?>
			<div class="col-md-<?php echo $img_info[1]*2<$img_info[0]?6:3?> foxsash_item">
                  <!-- normal -->
                    <div class="ih-item square effect13 bottom_to_top">
                        <a href="<?php echo str_replace('tags/','',$home).$line['short_url']?>">
                            <div class="img"><img src="<?php echo $img_file?>" alt="img"></div>
                            <div class="info">
                              <h4><?php echo $img?></h4>
                              <p><?php echo __('size','i').': '.formatBytes(filesize($img_file))."<br>".date ("F d Y H:i:s", filemtime($img_file))?></p>
                            </div>
                        </a>
                    </div>
                    <!-- end normal -->
			 </div>
			<?php }else{
					
			}
		 }
  	echo "</div>";	  
	 if(strlen($navigation) > 0){
		?>
		<hr>
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
	 _panel(__('List of Users is empty. Please add.','i'));
 }
?>	    
    </div>
</div>
<?php 
	require_once('../template/_footer.php');	
?>