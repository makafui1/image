<div class="container">
    <div class="row">
        <div class="col-md-12">
        <h1 class="page-header"><?php echo __('My files','i')?></h1>
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
						$sql = "SELECT * FROM ".$SET['db']['sql_tbl_prefix']."images WHERE id_user = {$_SESSION['user_id']} ORDER by date_add DESC LIMIT ".$begin.", ".$onpage;
						$result = mysqli_query($db,$sql) or die(mysqli_error($db).$count);
						return $result;
				}
				function navigation($onpage, $page, $table)
				{
					global  $SET,$db;
					
					$re = '';
					$count = mysqli_query($db,"SELECT count(*) FROM ".$SET['db']['sql_tbl_prefix']."images WHERE id_user = {$_SESSION['user_id']} ORDER by date_add DESC") or die(mysqli_error($db).$count);
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
							$re .= "<li><a href='?files&page=1'><i class='fa fa-fast-backward'></i></a></li>";
							$re .= "<li><a href='?files&page=".($cpage-1)."'><i class='fa fa-backward'></i></a></li>";
							}
						}
						if ($stpage>1) $re .= "<li class='disabled'><a href='#'>...</a></li>"; 
						for ($i=$stpage;$i<=$endpage;$i++) { 
							if ($i==$cpage) { $re .= '<li class="active"><a href="#">'.$i.'</a></li>'; }
							else { $re .= '<li><a href="?files&page='.$i.'">'.$i.'</a></li>'; }
						}
						if ($endpage<$pagescount) $re .= "<li class='disabled'><a href='#'>...</a></li>";
						if ($cpage<=$pagescount) {			
							if($cpage == $pagescount){
								$re .= '<li class="disabled"><a href="#"><i class="fa fa-forward"></i></a></li>';						
								$re .= "<li class='disabled'><a href='#'><i class='fa fa-fast-forward'></i></a></li>";
							}else{
								$re .= '<li><a href="?files&page='.($cpage+1).'"><i class="fa fa-forward"></i></a></li>';						
								$re .= "<li><a href='?files&page=".($pagescount)."'><i class='fa fa-fast-forward'></i></a></li>";
							}
						}
						if($endpage <= 1) $re = '';
						return $re;

						
				}
				$onpage = 25; 
				$table = $SET['db']['sql_tbl_prefix']."images";
				$page = page(); 
				$result = sql_query($onpage, $page, $table); 
				$navigation = navigation($onpage, $page, $table);
		if(mysqli_num_rows($result)>0){?>
         <table class="table table-striped table-bordered table-condensed">
                <tbody>
                    <tr>
                        <th width="15"><input data-check-all-input="file[]" type="checkbox"></th>
                        <th></th>
                        <th><?php echo __('File name','i')?></th>
                        <th width="70"><?php echo __('Size','i')?></th>
                        <th width="70"><?php echo __('Downloads','i')?></th>
                        <th width="180"><?php echo __('Upload date','i')?></th>
                        <th colspan="2" class="text-center"><?php echo __('Actions','i')?></th>
                    </tr>
<?php			while($line = mysqli_fetch_assoc($result)){
				$filename = explode("/",$line['file_name']);
				$img = end($filename);
				$fold = explode("/",$line['file_name']);
				$folder = current($fold);
				$img_ex = explode(".",$img);
				$img_file = 'images/'.$folder."/".$line['short_url'].".".end($img_ex);
				
				?>
                    <tr>
                        <td align="center"><input id="file_" name="file[]" type="checkbox" value="63211569"></td>
                        <td align="center"><i class="fa fa-<?php if(strlen($line['password'])>0){?>lock text-danger<?php }else{?>unlock-alt text-success<?php }?> fw"<?php if(strlen($line['password'])>0){?> rel="tooltip" data-placement="right" data-original-title="<?php echo $line['password']?>"<?php }?>></i></td>
                        <td><a href="<?php echo $home.$line['short_url']?>"><?php echo end($fold)?></a></td>
                        <td><?php echo formatBytes(filesize($img_file))?></td>
                        <td class="text-center"><?php echo $line['d_count']?></td>
                        <td class="nowrap"><?php echo date('F d Y H:i:s',$line['date_add'])?></td>
                        <td align="center" width="60"><a href="<?php echo $line['short_url']?>"><?php echo __('Edit','i')?></a></td>
                        <td align="center" width="60"><a href="/?remove=<?php echo sha1($line['short_url'])?>" onClick="return confirm('<?php echo __('Are you sure?','i')?>')" data-method="delete" rel="nofollow"><?php echo __('Remove','i')?></a></td>
                    </tr>
            <?php }?>        
                </tbody>
            </table>
       <?php    if(strlen($navigation) > 0){?>
		<hr>
        <div class="text-center">
            <ul class="pagination pagination-sm">
                <?php echo $navigation?>
            </ul>	
        </div>
        <hr>
        <?php }	?>
	<?php }else{
		echo _alert(__('Not uploaded yet.','i'));
	 }?>
        </div>
    </div>
</div>
