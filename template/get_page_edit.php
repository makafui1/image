<?php
if(isset($_GET['page_id'])){
	require_once('../_config.php');
	require_once('../_functions.php');
	require_once('../_core.php');
	$result = '';
	$res_cou = mysqli_query($db,"SELECT * FROM ".$SET['db']['sql_tbl_prefix']."page WHERE  id = '{$_GET['page_id']}'  LIMIT 1");
	if(mysqli_num_rows($res_cou) > 0){
		$line = mysqli_fetch_assoc($res_cou);
		$result = '<hr><form class="form-horizontal" action="" method="post">
		<input type="hidden" name="id" value="'.$_GET['page_id'].'" >
	  <div class="form-group">
		<label for="content" class="col-sm-2 control-label">Content</label>
		<div class="col-sm-10">
		  <textarea class="form-control editor" name="content" id="content" rows="20">'.$line['text'].'</textarea>
		</div>
	  </div>
	  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <button type="submit" class="btn btn-primary">Save</button>
		</div>
	  </div>
	</form>';
	}
	print json_encode($result);
}
?>