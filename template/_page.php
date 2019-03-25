<?php
if(!isset($id)) _loc('/');
if(count($_POST)>0 && isset($_SESSION['admin']) && $_SESSION['admin'] == 'true'){
	mysqli_query($db,"UPDATE ".$SET['db']['sql_tbl_prefix']."page SET text = '".$_POST['content']."' WHERE id = {$_POST['id']} LIMIT 1");
	_loc($_SERVER['REQUEST_URI']);
}
$result = mysqli_query($db,"SELECT * FROM ".$SET['db']['sql_tbl_prefix']."page WHERE id = {$id} LIMIT 1");
if(mysqli_num_rows($result) > 0){
	$line = mysqli_fetch_array($result, MYSQL_ASSOC);
echo "<div class=\"container\" id=\"content\">
		  	<div class=\"row\">
				<div class=\"col-md-12\"><h1 class=\"page-header\">{$line['title']}";
				if(isset($_SESSION['admin']) && $_SESSION['admin'] == 'true'){
	?>
	<a href="" class="pull-right btn btn-primary edit_page" id="edit<?php echo $id?>" title="<?php echo __('Edit','i')?>"><i class="fa fa-pencil fa-fw"></i></a>	
<?php	}
echo " </h1>
					{$line['text']}
				</div>";
if($id == 5){				?>
    <div class="col-md-12">
    	<hr>
                <div id="note"></div>
                <div class="wpcf7" id="fields">
                    <form action="javascript:alert('Was send!');" id="ajax-contact-form" method="post" class="wpcf7-form" novalidate="">
                        <p>
                            <?php echo __('Name','i')?> (<?php echo __('required','i')?>)<br>
                            <span class="wpcf7-form-control-wrap your-name"><input type="text" name="your-name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"></span>
                        </p>
                        <p>
                            <?php echo __('Email','i')?> (<?php echo __('required','i')?>)<br>
                            <span class="wpcf7-form-control-wrap your-email"><input type="email" name="your-email" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email"></span>
                        </p>
                        <p>
                            <?php echo __('Subject','i')?><br>
                            <span class="wpcf7-form-control-wrap your-subject"><input type="text" name="your-subject" value="" size="40" class="wpcf7-form-control wpcf7-text"></span>
                        </p>
                        <p>
                            <?php echo __('Message','i')?><br>
                            <span class="wpcf7-form-control-wrap your-message"><textarea name="your-message" cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea"></textarea></span>
                        </p>
                        <p><input type="submit" value="<?php echo __('Send','i')?>" class="wpcf7-form-control wpcf7-submit">
                        </p>
                    </form>
                </div>
	</div>                
<?php	
}
	if(isset($_SESSION['admin']) && $_SESSION['admin'] == 'true'){
		echo "<div class=\"col-md-12\"><div id='edit'></div></div>";
	}
echo	"	</div>
		  </div>";
}else{
	_loc('/');
}
?>
