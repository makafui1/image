  <div class="container">	
  	<div class="row">	
    	<div class="col-md-3"></div>
    	<div class="col-md-6">
<!-- contetn-->

		  <div class="text-center mb35">
          	<a href="/?latest">
            	<img src="<?php echo $SET['site_logo']?>" alt="<?php echo $SET['site_name']?>" class="img-responsive center-block">
			</a>
          </div>  
          <div class="clearfix"></div>
          <div class="cover">
             
          	 <form action="" method="post" id="img_form" enctype="multipart/form-data" class="mb35">
             		<div class="input-group" id="remote">
	                <input type="url" name='remote' class="form-control" required="required">
	                  <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit"><?php echo __('Upload','i')?></button>
                      </span>
                    </div>
                    <input id="userid" name="userid" type="hidden" value="<?php if(isset($_SESSION['user_id'])){
					echo $_SESSION['user_id'];
				}else{
					echo 0;
				}?>">	                
			        <input id="input-id" type="file" accept="image/*" name="fH_file" class="file-loading form-control input-lg">
   			        
             </form> 
             <div class="clearfix"></div>
             <p class="text-center">
	             <input id="selector" type="checkbox" data-off-color="primary" checked="checked" data-on-text="<?php echo __('Local','i')?>" data-off-text="<?php echo __('Remote','i')?>">  
             </p>   
          </div>
          <div class="tags">
          	<?php $res_post = mysqli_query($db,"SELECT tags
				 FROM ".$SET['db']['sql_tbl_prefix']."images WHERE tags != '' AND public = 1") or exit(mysqli_error($db));
				if(mysqli_num_rows($res_post)>0){
					$tags = $tags_ =  array();
					while($tag = mysqli_fetch_assoc($res_post)){
						$tags = $tags_;
						$tags_ = explode(',',$tag['tags']);
						foreach($tags_ as $k =>$v){
							$tags_[$k] = trim($v);
						}
						$tags_ = array_merge($tags_,$tags);
					}					
					$tags = array_count_values($tags_);
					$i ='0';
					foreach($tags as $tag => $num) {
						$i = $i+1;
							echo '<a href="/tags/'.urldecode($tag).'" style="font-size:'.(1+$num/5).'em !important">'.$tag.'</a> ';
					}
					
				}?>
          </div>
<!-- contetn-->