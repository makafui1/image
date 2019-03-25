<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="modal" style="display:block; z-index:0; position:relative">
				<div class="modal-dialog">
					<div class="modal-content">
  						<form class="form-horizontal" action="" method="post">
                        	<input type="hidden" name="abuse" value="1">
						<div id="login_block">
                        <div class="modal-header">
                            <h4 class="modal-title"><?php echo __('Report abuse','i')?></h4>
                          </div>
								<div class="modal-body">
									<div class="form-group">
                                        <label for="file" class="col-sm-2 control-label"><?php echo __('File','i')?></label>
                                        <div class="col-md-10">
	                                        <input type="text" id="file" class="form-control input-lg" readonly="readonly" name="abuse_file" value="<?php echo $_GET['abuse']?>" required>
                                        </div>
									</div>
									<div class="form-group">
	                                    <label for="email" class="col-sm-2 control-label"><?php echo __('Email','i')?></label>
                                        <div class="col-md-10">
											<input type="email" id="email" class="form-control input-lg" name="email" value="" required>
                                        </div>
									</div>	
                                    <div class="form-group">
										<label for="email" class="col-sm-2 control-label"><?php echo __('Description','i')?></label>
                                        <div class="col-md-10">
											<textarea id="descr" class="form-control input-lg" name="descr" rows="7" required></textarea>
										</div>
									</div>									
                                    You can also <a href="mailto:<?php echo $SET['abuse_email']?>?body=<?php echo $home.$_GET['abuse']?>">email us</a> the links.
								</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary btn-lg btn-block"><?php echo __('Submit','i')?></button>
						</div>
						</form>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>