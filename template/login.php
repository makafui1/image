<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="modal" style="display:block; z-index:0; position:relative">
				<div class="modal-dialog">
					<div class="modal-content">
  						<form class="form-horizontal" action="" method="post">
                        	<input type="hidden" name="form_login" value="1">
						<div id="login_block">
                        		<div class="modal-header">
                                <h4 class="modal-title"><?php echo __('Sign in','i')?></h4>                                
                                  </div>
								<div class="modal-body">
                                <?php if($_SERVER['SERVER_ADDR'] == '151.248.126.10'){?>
                                <div class="alert alert-info">Login: <strong>admin</strong><br>Password: <strong>123</strong></div>
                                <?php }?>    
									<div class="form-group">
										<div class="col-md-12">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-user fa-2x fa-fw"></i></span>
												<input type="text" id="inputLogin" class="form-control input-lg" name="login" value="" placeholder="<?php echo __('Login','i')?>" required="required">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-12">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-lock fa-2x fa-fw"></i></span>
												<input type="password" id="inputPassword" class="form-control input-lg" name="pass" value="" placeholder="<?php echo __('Password','i')?>" required="required">
											</div>
										</div>
									</div>									
								</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary btn-lg btn-block" id="login"><i class="fa fa-sign-in fa-fw"></i> <?php echo __('Sign in','i')?></button>
						</div>
						</form>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>