<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/vendor/do_add/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'vendor_add',
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
                    	class="form-control required" placeholder="<?php echo translate('address');?>" >
                </div>
            </div>
            <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-10">
                    	<?php echo translate('latitude');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="latitude" required  
                        	 id="demo-hor-10" 
                            	class="form-control required" placeholder="<?php echo translate('latitude');?>" oninput="validate_lat()">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-11">
                    	<?php echo translate('longitude');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="longitude" required  
                        	 id="demo-hor-11" 
                            	class="form-control required" placeholder="<?php echo translate('longitude');?>" oninput="validate_log()">
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
                <label class="col-sm-4 control-label" for="demo-hor-3">
                	<?php echo translate('pickup/delivery');?>
                </label>
                <div class="col-sm-6">
               <div class="col-sm-4">
<input type="checkbox" class="form-control" id="pickup" name="pickup" value="yes" style="width: 15px;">
Pickup</div>
<div class="col-sm-4">
<input type="checkbox" id="delivery" class="form-control" name="delivery" value="yes"  style="width: 15px;">
Delivery
</div>
<div class="col-sm-4"></div>
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

    function validate_lat() {           
        let inputNumber = document.getElementById("demo-hor-10").value;
        if (inputNumber.indexOf('.') !== -1 && inputNumber.split('.')[1].length > 6) {
            alert("You cannot enter more than 6 decimal places.");
            document.getElementById("demo-hor-10").value = inputNumber.substring(0, inputNumber.indexOf('.') + 7);
        }
    }

    function validate_log() {        
        let inputNumber = document.getElementById("demo-hor-11").value;
        if (inputNumber.indexOf('.') !== -1 && inputNumber.split('.')[1].length > 6) {
            alert("You cannot enter more than 6 decimal places.");
            document.getElementById("demo-hor-11").value = inputNumber.substring(0, inputNumber.indexOf('.') + 7);
        }
    }
</script>