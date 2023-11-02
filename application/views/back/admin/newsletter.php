<div id="content-container">
<div class="content-wrapper-before"></div>

	<div class="tab-base">
		<!--Tabs Content-->
		<div class="panel">
		<!--Panel heading-->
			<div class="panel-body">
				<div class="tab-content">
				
			   <div class="row">
				  <div class="col-md-12">
					<h1 class="page-header text-overflow" ><?php echo translate('send_newsletter')?></h1>
				  </div>
				</div>
				<br /><br />
					<div class="tab-pane fade active in" id="lista">
						<div class="panel-body" id="demo_s">
							<?php
                                echo form_open(base_url() . 'index.php/admin/newsletter/send/', array(
                                    'class' => 'form-horizontal',
                                    'method' => 'post'
                                ));
                            ?>
		                        <div class="row">
									<div class="option" style="margin-bottom: 9px;margin-left: 15px;">
									<h3 class="panel-title" style="margin-left: -15px;">Option</h3>
										<select name="option" id="option" class="form-control lnh" style="width: 23.5%;margin-left: -11px;">
											<option value="">select option</option>
											<option value="0">user</option>
											<option value="1">subscribers</option>
										</select>
									</div>
								
			                        <?php
				                        $user_list = array();
				                        $subscribers_list = array();
				                        foreach ($users as $row) {
				                        	$user_list[] = $row['email'];
				                        }
				                        foreach ($subscribers as $row) {
				                        	$subscribers_list[] = $row['email'];
				                        }
			                        	$user_list = join(',',$user_list);
			                        	$subscribers_list = join(',',$subscribers_list);
			                        ?>
									<div class="email_user" >
	                            	<h3 class="panel-title"><?php echo translate('e-mails_(users)')?></h3>
									
								<div class="col-sm-3" style="margin-bottom: 21px;">
								<button id="cancel"  class="btn btn-danger"  style="position: absolute; top: 0; right: 0;">cancel</button>
									<label style="float:left; margin-top:6px; width:100% !important;" style="float:left;" for="group_name"> Group Name: </label>
                    				<select name="vendor" id="vendor" style="float:left; width:50% !important;" class="form-control lnh" onchange="group(this.value)">
                        				<option value="0">All</option>
                        				<?php $usergroups = $this->db->get('user_group')->result_array();
                        				foreach ($usergroups as $usergroup) { ?>
                    					<option value="<?php echo $usergroup['user_group_id']; ?>"><?php echo $usergroup['user_group_name']; ?></option>
                        				<?php } ?>
                    				</select>
                				</div>
					                <div class="form-group btm_border">
					                    <div class="col-sm-12">	
					                        <input type="text" name="subscribers" data-role="tagsinput" 
					                        	placeholder="<?php echo translate('e-mails_(users)')?>" class="form-control"
					                        		value="" id="subscribers">
					                    </div>
					                </div>
									</div>
									<div class="email_subscribers">
	                            	<h3 class="panel-title"><?php echo translate('e-mails_(subscribers)')?></h3>
									<div class="col-sm-3"style="margin-bottom: 25px;">
									<label style="float:left; margin-top:6px; width:100% !important;" style="float:left;" for="sub_mail"> Subscribers Email: </label>
									<select  id="sub_mail" style="float:left; width:100% !important;"  class="form-control lnh" multiple onchange="addSelectedEmailsToUsers()">
									
											<?php
											$subscribers = $this->db->get('subscribe')->result_array();
											foreach ($subscribers as $subscriber) {
											?>
											<option value="<?php echo $subscriber['subscribe_id']; ?>"><?php echo $subscriber['email']; ?></option>
											<?php
											}
											?>
										</select>
									</div>
									<button id="cancel_sub" style="margin-left: 4px;margin-top: 29px;" class="btn btn-danger">cancel</button>
					                <div class="form-group btm_border">
					                    <div class="col-sm-12">
					                        <input type="text" name="users" data-role="tagsinput" 
					                        	placeholder="<?php echo translate('e-mails_(subscribers)')?>" class="form-control"
					                        		value="" id="users">
					                    </div>
					                </div>
									</div>
	                            	<h3 class="panel-title"><?php echo translate('from_:_email_address')?></h3>
					                <div class="form-group btm_border">
					                    <div class="col-sm-12">
					                        <input type="email" name="from" 
                                            	placeholder="<?php echo translate('from_:_email_address')?>" class="form-control required">
					                
					                    </div>
					                </div>
	                            	<h3 class="panel-title"><?php echo translate('newsletter_subject')?></h3>
					                <div class="form-group btm_border">
					                    <div class="col-sm-12">
					                        <input type="text" name="title" 
                                            	placeholder="<?php echo translate('newsletter_subject')?>" class="form-control required">
					                    </div>
					                </div>
									<!-- check box -->
									<h3 class="panel-title">No Reply</h3>
					                <div class="form-group btm_border">
					                    <div class="col-sm-12">
										<label class="checkbox-inline">
            								<!-- <input type="checkbox" id="no_reply_checkbox" value="true" checked> No Reply -->
											<input type="checkbox" id="no_reply_checkbox" name="no_reply_checkbox" value="true" checked> No Reply
        								</label>
					                    </div>
					                </div>
									<!--  -->
	                            	
	                            	<h3 class="panel-title"><?php echo translate('newsletter_content')?></h3>
	                                <textarea class="summernotes" data-height='700' data-name='text' class="required" ></textarea>

	                            </div>
	                            <div class="panel-footer text-center">
	                                <span class="btn btn-info submitter enterer"  data-ing='<?php echo translate('sending'); ?>' data-msg='<?php echo translate('sent!'); ?>'>
										<?php echo translate('send')?>
                                        	</span>
	                            </div>
	                        </form>
	                    </div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>


<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<script>
		function group(selectedValue) {
		

					$.ajax({
        type: "POST",
        // url: base_url+'index.php/'+user_type+'/'+module+'/send/',  // Replace with the actual path to your PHP script
		url: "<?php echo base_url('index.php/admin/newsletter/group'); ?>", // Modify the URL here
        data: { selectedValue: selectedValue },
		dataType:'json',
        success: function(data) {
			var user_list = data;
            // var subscribers_list = data.subscribers_list;
			
            // Update the input values
            $('input[name="subscribers"]').tagsinput('removeAll');
            //  $('input[name="subscribers"]').tagsinput('add', user_list[0]['email']);
			// console.log('user_list',user_list[0]['email']);
			var concatenatedEmails = '';
			$.each(user_list, function(index, item) {

				// subscribersInput.tagsinput('add', { value: item.email, text: item.email });
				concatenatedEmails += item.email + ',';
				$('input[name="subscribers"]').tagsinput('add', concatenatedEmails);
				console.log('data',data);
			});
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });

				
			
	 
    }

	var selectedEmails = '';

	function addSelectedEmailsToUsers() {
		var selectElement = document.getElementById("sub_mail");
    	 selectedEmails = ''; // Clear the array before populating with new selections

    for (var i = 0; i < selectElement.options.length; i++) {
        if (selectElement.options[i].selected) {
			selectedEmails += selectElement.options[i].text + ',';
            // selectedEmails.push(selectElement.options[i].text);
        }
    }

	$('input[name="users"]').tagsinput('add', selectedEmails);
   
   
}




	$(document).ready(function() {

	

		var vendorDropdown = $('#vendor');
		vendorDropdown.hide();
		var cancelButton = $('#cancel');
		cancelButton.hide();
		var sub_mailDropdown = $('#sub_mail');
		sub_mailDropdown.hide();
		var cancel_subButton = $('#cancel_sub');
		cancel_subButton.hide();
		$('label[for="sub_mail"]').hide();
		$('label[for="group_name"]').hide();
		$('.email_user').hide();
		$('.email_subscribers').hide();
		
	
		cancelButton.click(function(e) {
			e.preventDefault();
			$('input[name="subscribers"]').tagsinput('removeAll');
		});
		cancel_subButton.click(function(e) {
			e.preventDefault();
			$('input[name="users"]').tagsinput('removeAll');
		});
		$('#option').change(function() {
			var selectedOption  = $(this).val();
			var emailListusers = $('#subscribers');
			var emailListsubscribers = $('#users');
			
			
			
			if (selectedOption === '0') {
				
				emailListsubscribers.tagsinput('removeAll');
				emailListusers.tagsinput('add', <?php echo json_encode($user_list); ?>);
				vendorDropdown.show();
				cancelButton.show();
				sub_mailDropdown.hide();
			cancel_subButton.hide();
			$('label[for="group_name"]').show();
			$('label[for="sub_mail"]').hide();
			$('.email_user').show();
			$('.email_subscribers').hide();
			
			
			
				
        } else if (selectedOption === '1') {
            emailListusers.tagsinput('removeAll');
            emailListsubscribers.tagsinput('add', <?php echo json_encode($subscribers_list); ?>);
			addSelectedEmailsToUsers();
			vendorDropdown.hide();
			cancelButton.hide();
			sub_mailDropdown.show();
			cancel_subButton.show();
			$('label[for="sub_mail"]').show();
			$('label[for="group_name"]').hide();
			$('.email_subscribers').show();
			$('.email_user').hide();
			
        }
			
		});
	
		$('.summernotes').each(function() {
			var now = $(this);
			var h = now.data('height');
			var n = now.data('name');
			now.closest('div').append('<input type="hidden" class="val" name="' + n + '">');
			now.summernote({
				height: h,
				onChange: function() {
					now.closest('div').find('.val').val(now.code());
				}
			});
			now.closest('div').find('.val').val(now.code());
		});
		
	});
	
	var base_url = '<?php echo base_url(); ?>';
	var user_type = 'admin';
	var module = 'newsletter';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
</script>