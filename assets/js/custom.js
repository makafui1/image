jQuery.noConflict()(function($){
	if($("input").is(".switch")) {
				$(".switch").bootstrapSwitch();
			}	
	$(document).ready(function () {
		if($("input").is("#selector")){
			$('#remote').hide();
			$('#selector').on('switchChange.bootstrapSwitch', function (event, state) {		
				if ($(this).is(':checked')) {
					$('.file-input').show();
					$('.file-input-new').show();
					$('#img_form').attr('enctype','multipart/form-data');
					$('#remote').hide();
				} else {
					$('.file-input').hide();
					$('#img_form').removeAttr('enctype');
					$('#remote').show();
					$('.file-input-new').hide();
				}
			});
		}
		
		
        $("#ajax-contact-form").submit(function() {
            // this points to our form
            var str = $(this).serialize(); // Serialize the data for the POST-request
            var result = '';
            $.ajax({

                type: "POST",
                url: 'template/contact.php',
                data: str,
                success: function(msg) {
                    if (msg == 'OK') {
                        result = '<div class="alert alert-info">Message was sent to website administrator, thank you!</div>';
                        $("#fields").hide();
                    } else {
                        result = msg;
                    }
                    $("#note").html(result);

                }
            });
            return false;
        });

		$('[rel=tooltip]').tooltip();
		
		$("#input-id").fileinput({
			dropZoneEnabled: false,
			showPreview:false,
			uploadUrl: "../../images/",
			allowedFileExtensions: ['jpg', 'png', 'gif'],
			uploadAsync: false,
		    maxFileCount: 1,
			uploadExtraData: function() {
				return {
					userid: $("#userid").val()
				};
			}
		});
		$("#selector").bootstrapSwitch();
		$('#translate').click(function () {
			$('tr').each(function(index, tr) {
				var lines = $('td', tr).map(function(index, td) {
					return $(td).text();
				});
				$(this).find("td input").val(lines[1]);
				$(this).find("td textarea").val(lines[1]);
			});
		});
		$('#input-id').on('filebatchuploadsuccess', function(event, data, previewId, index) {
			if(data.response.status ==='ready'){
				document.location.href=data.response.short_url;
			}
		});
		$('#bt_editfile').click(function () {
			 $('#editfile').modal();
		});
		$('#btn-qr-modal').click(function () {
			$('#qr-modal').modal();
			event.preventDefault();
		});
		
		$(".btn_download").click(function () {
			var url = 'template/get_download.php';
			var img = $(this).attr('href');
			$.get
				  (
					 url,"img=" + img ,function(result)
						{
							if(result.length > 0){
								
							}
							},
						"json"
						);
			
		});
		$(".del_abuse_img").click(function () {
			var url = 'template/_get_ajax_abuse.php';
			var id = this.id.replace(/[^\d\.]/g, '');
			$.get
				  (
					 url,"del_img=" + id ,function(result)
						{
							if(result == 'true'){
								$('td.item'+id).remove();
							}
						},
						"json"
						);
			return false;			
		});
		$(".edit_page").click(function () {
			var url = 'template/get_page_edit.php';
			var id = this.id.replace(/[^\d\.]/g, '');
			$.get
				  (
					 url,"page_id=" + id ,function(result)
						{
							$('#edit').html(result);
							tinymce.init({selector:'.editor',
							 plugins: [
								"advlist autolink lists link image charmap print preview anchor",
								"searchreplace visualblocks code fullscreen",
								"insertdatetime media table contextmenu paste"
							],
							toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
							});
						},
						"json"
						);
			return false;			
		});
		
		$("#search_area").keyup(function (e) {
			var s = $(this).val();
			var url = 'template/get_search.php';
			if(s ==''){
				$("#quicksearch").html();
				$("#quicksearch").hide();
			}else {
			$.get
				  (
					 url,"keyword=" + s ,function(result)
						{
							if(result.length > 0){
								$("#quicksearch").html(result);
								$("#quicksearch").show();
								$("#quicksearch").keydown(function () {
									
								});
							}else{
								$("#quicksearch").html();
								$("#quicksearch").hide();
							}
							},
						"json"
						);
			}
			event.preventDefault();
		});
				
		
		if($("input").is("#link_pass")) {
			$("#get_link button").click(function () {
				var pass = $('#link_pass').val();
				var short_url = $('#short_url').val();
				if(pass.length > 0){
					var url = 'template/get_pass.php';
					$.get(
					 url,"pass=" + pass+"&short_url="+short_url ,function(result)
						{
							if(result.status == '1'){
								$(result.file).insertAfter( "#get_link" );
								$('#get_link').remove();
								$('.file_info').append(result.download);
									$(".btn_download").click(function () {
										var url = 'template/get_download.php';
										var img = $(this).attr('href');
										$.get
											  (
												 url,"img=" + img ,function(result)
													{
														if(result.length > 0){
															
														}
														},
													"json"
													);
										
									});
							}else{
								$('.alert').remove();
								$('#get_link').append('<div class="alert alert-danger mt30" role="alert">Password error</div>');
							}
							},
						"json"
						);
					
				}
				event.preventDefault();
			});
			event.preventDefault();
		}
	});
	if($("div").is("#works")) {
			var url = 'http://php.foxsash.com/foxsash_works.php';
			$("#works").html('Loading...');
			$.get(
				url, "works",
				function(result,status) {
					$("#works").html(result);
					var $container = $('.foxsash_container');
				
					if ($container.length) {
						$container.waitForImages(function() {
				
							// initialize isotope
							$container.isotope({
								itemSelector: '.foxsash_item',
								layoutMode: 'masonry',
							});
				
							$('#filters a:first-child').addClass('filter_current');
							// filter items when filter link is clicked
							$("a", "#filters").on("click", function(e) {
								var selector = $(this).attr('data-filter');
								$container.isotope({
									filter: selector
								});
								$(this).removeClass('filter_button').addClass('filter_button filter_current').siblings().removeClass('filter_button filter_current').addClass('filter_button');
				
								return false;
							});
				
						}, null, true);
					}
				},
				"json"
			);
			return false;	
		}
});