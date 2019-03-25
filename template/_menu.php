<div class="navbar navbar-inverse" role="navigation">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main_menu">
			<span class="sr-only"><?php echo __('Toggle navigation','i')?></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a href="<?php echo $SET['home_url']?>" title="<?php echo $SET['site_name']?>" class="navbar-brand">
            	<img src="/<?php echo $SET['site_logo']?>" class="pull-left" alt="<?php echo $SET['site_name']?>" width="24" class="pull-left" style="margin-right:5px;"> <?php echo $SET['site_name']?>
			</a>
		</div>
		<div class="collapse navbar-collapse" id="main_menu">
            <ul class="nav navbar-nav  pull-left">
        	<?php if(isset($_SESSION['user_name'])){?>
                <li><a href="/?files"><?php echo __('Files','i')?></a></li>
            <?php }?>                                
			</ul>
            <?php if($_SERVER['SERVER_ADDR'] == '151.248.126.10'){?>
            <span style="display:inline-block; margin-top:10px">
            <a href="http://codecanyon.net/item/imghosting-image-storage-system/12487281?ref=FoxSash" target="_blank" rel="tooltip" data-placement="bottom" title="" data-original-title="Buy Now" class="btn btn-warning"><i class="fa fa-usd fa-fw"></i> Purchase</a>
            <a href="?works" class="btn btn-primary"><i class="fa fa-star fa-fw"></i> Other works</a>
            </span>
            <?php }?>
			<ul class="nav navbar-nav pull-right">
            	<?php if(isset($_SESSION['user_name'])){
				 if(isset($_SESSION['admin']) && $_SESSION['admin'] == 'true'){
					$quota = formatBytes(disk_free_space("."));?>
                <li><a class="danger"><span class="badge progress-bar-info"><?php echo $quota?></span></a></li>   
                <?php }?>
                 <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] == 'true' && abuse_show() > 0){?>
                 <li><a class="danger" href="/?abuse_list"><span class="badge progress-bar-danger"><?php echo __('Abuse','i').": ".abuse_show()?></span></a></li>   
                 <?php }?>
				<li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  <?php echo $_SESSION['user_name']?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] == 'true'){?>
         	       <li><a href="/?admin"><i class="fa fa-gears fa-fw"></i> <?php echo __('Control Panel','i')?></a></li>
                   <li class="divider"></li>
                <?php }?>   
                  <li><a href="/?logout" title="<?php echo __('Logout','i')?>"><i class="fa fa-sign-out fa-fw"></i> <?php echo __('Logout','i')?></a></li>
                </ul>
                
                <?php }else{?>
                <li><a href="/?register" title="Sign up"><?php echo __('Sign up','i')?></a></li>
                <li><a href="/?login" title="Sign in"><?php echo __('Sign in','i')?></a></li> 
                <?php }?>
                <li><div class="col-md-3"><form class="navbar-form" action="" method="get" enctype="multipart/form-data">
                    <input type="text" class="form-control" id="search_area" name="search" value="<?php if(isset($_GET['search'])) echo $_GET['search']?>" placeholder="<?php echo __('Search','i')?>...">
                    <ul id="quicksearch" style="z-index:999"></ul>
                  </form></div></li>           
			</ul>
		</div>
	</div>
</div>
