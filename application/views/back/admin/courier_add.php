<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/courier/do_add/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'category_add',
			'enctype' => 'multipart/form-data'
		));

	?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('agent_name');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="agent_name" id="demo-hor-1" 
                    	class="form-control required" placeholder="<?php echo translate('agent_name');?>" >
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-2">
                	<?php echo translate('address');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="address" id="demo-hor-2" 
                    	class="form-control required" placeholder="<?php echo translate('address');?>" >
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-3">
                	<?php echo translate('city');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="city" id="demo-hor-3" 
                    	class="form-control required" placeholder="<?php echo translate('city');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-4">
                	<?php echo translate('state');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="state" id="demo-hor-4" 
                    	class="form-control required" placeholder="<?php echo translate('state');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-5">
                	<?php echo translate('country');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="country" id="demo-hor-5" 
                    	class="form-control required" placeholder="<?php echo translate('country');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-6">
                	<?php echo translate('email');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="email" id="demo-hor-6" 
                    	class="form-control required" placeholder="<?php echo translate('email');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-7">
                	<?php echo translate('mobile_phone');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="mobile_phone" id="demo-hor-7" 
                    	class="form-control required" placeholder="<?php echo translate('mobile_phone');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-8">
                	<?php echo translate('username');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="username" id="demo-hor-8" 
                    	class="form-control required" placeholder="<?php echo translate('username');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-9">
                	<?php echo translate('password');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="password" id="demo-hor-9" 
                    	class="form-control required" placeholder="<?php echo translate('password');?>" >
                </div>
            </div>
            
            
        </div>
	</form>
</div>

<script>
	$(document).ready(function() {
		$("form").submit(function(e){
			event.preventDefault();
		});
	});
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
	
			reader.onload = function(e) {
				$('#wrap').hide('fast');
				$('#blah').attr('src', e.target.result);
				$('#wrap').show('fast');
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#imgInp").change(function() {
		readURL(this);
	});
</script>