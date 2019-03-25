            </div>
            <?php if(!count($_GET) || isset($_GET['main'])){?><div class="col-md-3"></div>
			<?php if(isset($SET['ads_728x90']) && strlen($SET['ads_728x90'])>0){
				 echo "<div class='col-md-12'><div class='text-center' style='margin-top:35px'>".stripslashes($SET['ads_728x90'])."</div></div>";
			 }?> 
			<?php }?>
		</div>
    </div>
	<footer class="footer">
	       	<div class="container">
            	<div class="row">
                <div class="col-md-2">
               <?php 
			   if(isset($SET['show_language']) && $SET['show_language'] >= 1){
	
				  $home = '';			
				   if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
					   $ar_request = explode('?',$_SERVER['REQUEST_URI']);
					   if(count($ar_request)<2){
						   $home = '../';
					   }
				   }
			   $lang_f = scandir($home.'lang/');
							foreach($lang_f as $v){
								if($v != '.' && $v != '..' && $v != '.DS_Store'){
									$lng = explode(".",$v);
									$nav_lang = file_get_contents($home."lang/".$v);
									$name_lang = explode("\n",$nav_lang);
									$regexp_code = "|\"(.*)\"|";
									$regexp_text = $name_lang[0];
									preg_match_all($regexp_code,$regexp_text,$out);
									echo "<a href='/?main&lng=".strtolower($lng[0])."' class='pull-left flag flag-".strtolower($lng[0])."' title='".$out[1][0]."' id='".strtolower($lng[0])."' style='margin-top:4px; margin-right:5px'></a>";
								}
							}
			   }?>
               	</div>
               	<div class="col-md-7 text-center">
				<?php if(isset($SET['page_about']) && $SET['page_about']==1){?>	                
                  <a href="/?about"><?php echo __('About us','i')?></a> |
                <?php }?>   
                <?php if(isset($SET['page_policy']) && $SET['page_policy']==1){?>	 	
                  <a href="/?policy"><?php echo __('Policy and Term','i')?></a> |
                <?php }?>  
                 <?php if(isset($SET['page_faq']) && $SET['page_faq']==1){?>	 
                  <a href="/?faq"><?php echo __('FAQ','i')?></a> |
                <?php }?>  
                 <?php if(isset($SET['page_developer']) && $SET['page_developer']==1){?>	 
                  <a href="/?developer"><?php echo __('Developer','i')?></a> |
                 <?php }?>
                  <?php if(isset($SET['page_contacts']) && $SET['page_contacts']==1){?>	  
                  <a href="/?contacts"><?php echo __('Contact','i')?></a>
                 <?php }?> 
				 </div>
                  <div class="col-md-3"><p class="pull-right copy">© <?php if(date("Y",time())=='2015'){?>2015<?php }else{ echo '2015-'.date("Y",time());}?> <?php echo $SET['site_name']?> <?php if(isset($SET['show_version']) && $SET['show_version'] =1){?><a href="?change-log" target="_blank"><?php echo $SET['version']?></a><?php }?>.  Programming by <a href="http://codecanyon.net/user/FoxSash/?ref=FoxSash" target="_blank">FoxSash</a></p></div>
				
                </div>
        	</div>
	</footer>            
    <!-- Bootstrap core JavaScript
    ================================================== -->
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="/assets/js/masonry.pkgd.min.js"></script>
    <script src="/assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="/assets/js/bootstrap-switch.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
    <script src="/assets/js/fileinput.min.js" type="text/javascript"></script>
    <?php if(isset($SET['infinite_scroll']) && $SET['infinite_scroll'] == 1){?>
    <script src="/assets/js/jquery.infinitescroll.min.js" type="text/javascript"></script>
    <?php }?>
	<?php if(isset($SET['lang']) && is_file('assets/js/fileinput_locale_'.$SET['lang'].".js")){?>
    <script src="/assets/js/fileinput_locale_<?php echo $lang_l?>.js"></script>
    <?php }?>
    <script src="/assets/js/isotope.pkgd.min.js"></script>
    <script src="/assets/js/jquery.waitforimages.js"></script>
    <script src="/assets/js/custom.js" type="text/javascript"></script>
    <?php if(isset($SET['google_analytics']) && strlen($SET['google_analytics']) > 0){?>
    <?php echo $SET['google_analytics']."\n"?>
    <?php } ?>
  </body>
</html>