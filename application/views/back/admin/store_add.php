<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/store/do_add/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'store_add',
			'enctype' => 'multipart/form-data'
		));
	?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('store_name');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="name" id="demo-hor-1" 
                    	class="form-control required" placeholder="<?php echo translate('store_name');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-3">
                	<?php echo translate('address1');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="address1" id="demo-hor-3" 
                    	class="form-control required" placeholder="<?php echo translate('address1');?>" >
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-4">
                	<?php echo translate('address2');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="address2" id="demo-hor-4" 
                    	class="form-control required" placeholder="<?php echo translate('address2');?>" >
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
                <label class="col-sm-4 control-label" for="demo-hor-3">
                	<?php echo translate('state');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="state" id="demo-hor-3" 
                    	class="form-control required" placeholder="<?php echo translate('state');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-3">
                	<?php echo translate('country');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="country" id="demo-hor-3" 
                    	class="form-control required" placeholder="<?php echo translate('country');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-3">
                	<?php echo translate('zipcode');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="zipcode" id="demo-hor-3" 
                    	class="form-control required" placeholder="<?php echo translate('zipcode');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-3">
                	<?php echo translate('email');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="email" id="demo-hor-3" 
                    	class="form-control required" placeholder="<?php echo translate('email');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-3">
                	<?php echo translate('phone_number');?>
                </label>
                <div class="col-sm-6">
                    <input type="number" name="phone" id="demo-hor-3" 
                    	class="form-control required" placeholder="<?php echo translate('phone');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-3">
                	<?php echo translate('delivery_zipcode');?>
                </label>
                <div class="col-sm-6">
                    <textarea class="form-control" name="delivery_zipcode" id="delivery_zipcode" rows="3"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-2">
                    <?php echo translate('store_logo');?>
                </label>
                <div class="col-sm-6">
                    <span class="pull-left btn btn-default btn-file">
                        <?php echo translate('select_store_logo');?>
                        <input type="file" name="logo" id='imgInp' accept="image">
                    </span>
                    <br><br>
                    <span id='wrap' class="pull-left" >
                        
                    </span>
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