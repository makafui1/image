<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="modal" style="display:block; z-index:0; position:relative">
				<div class="modal-dialog">
					<div class="modal-content">
  						<form class="form-horizontal" action="" method="post">
                        <input type="hidden" name="form_reg" value="1">
						<div id="login_block">
		                        <div class="modal-header">
                                    <h4 class="modal-title"><?php echo __('Registration','i')?></h4>
                                </div>
								<div class="modal-body">
                                	<?php if(isset($error)){
										echo $error;
									}?>
									<div class="form-group">
                                        <label for="user_name" class="col-sm-2 control-label"><?php echo __('Name','i')?></label>
                                        <div class="col-md-10">
                                            <input type="text" id="user_name" class="form-control input-lg" name="user_name" value="<?php if(isset($_POST['user_name'])){echo $_POST['user_name'];}?>" required="required">
                                        </div>
									</div>
									<div class="form-group">
										<label for="user_email" class="col-sm-2 control-label"><?php echo __('Email','i')?></label>
										<div class="col-md-10">
											<input type="email" id="user_email" class="form-control input-lg" name="user_email" value="<?php if(isset($_POST['user_email'])){echo $_POST['user_email'];}?>" required="required">
										</div>
									</div>	
                                    <div class="form-group">
										<label for="user_password" class="col-sm-2 control-label"><?php echo __('Password','i')?></label>
										<div class="col-md-10">
											<input type="password" id="user_password" class="form-control input-lg" name="user_password" value="<?php if(isset($_POST['user_password'])){echo $_POST['user_password'];}?>" required="required">
										</div>
									</div>	
                                    <div class="form-group">
										<label for="user_confirm" class="col-sm-2 control-label"><?php echo __('Confirm','i')?></label>
										<div class="col-md-10">
											<input type="password" id="user_confirm" class="form-control input-lg" name="user_confirm" value="<?php if(isset($_POST['user_confirm'])){echo $_POST['user_confirm'];}?>" required="required">
										</div>
									</div>									
								</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary btn-lg btn-block" id="login"><?php echo __('Sign up','i')?></button>
						</div>
						</form>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>