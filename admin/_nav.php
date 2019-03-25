<div class="list-group">
    <a href="?admin&general" class="list-group-item<?php if(count($_GET) == 1 || isset($_GET['general'])){?> active<?php } ?>"><i class="fa fa-dashboard fa-fw"></i> <?php echo __('General','i')?></a>
    <a href="?admin&user_list" class="list-group-item<?php if(isset($_GET['user_list'])){?> active<?php } ?>"><i class="fa fa-users fa-fw"></i> <?php echo __('User list','i')?></a>
    <a href="?admin&language" class="list-group-item<?php if(isset($_GET['language']) || isset($_GET['edit_lang'])){?> active<?php } ?>"><i class="fa fa-flag fa-fw"></i> <?php echo __('Languages','i')?></a>
</div>
