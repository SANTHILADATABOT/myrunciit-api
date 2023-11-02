<?php
	foreach($store_data as $row){
?>
	<div class="tab-pane fade active in" id="edit">
		<?php
			echo form_open(base_url() . 'index.php/admin/store/update/' . $row['store_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'store_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-2">
                    	<?php echo translate('store_name');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="name"  
                        	value="<?php echo $row['name'];?>" id="demo-hor-2" 
                            	class="form-control required" placeholder="<?php echo translate('store_name');?>" >
					</div>
				</div>
            <div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-2">
                    	<?php echo translate('address1');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="address1"  
                        	value="<?php echo $row['address1'];?>" id="demo-hor-2" 
                            	class="form-control required" placeholder="<?php echo translate('address1');?>" >
					</div>
				</div>
                <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-3">
                    	<?php echo translate('address2');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="address2"  
                        	value="<?php echo $row['address2'];?>" id="demo-hor-3" 
                            	class="form-control required" placeholder="<?php echo translate('address2');?>" >
					</div>
				</div>
                <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-4">
                    	<?php echo translate('city');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="city"  
                        	value="<?php echo $row['city'];?>" id="demo-hor-4" 
                            	class="form-control required" placeholder="<?php echo translate('city');?>" >
					</div>
				</div>
                <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-5">
                    	<?php echo translate('state');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="state"  
                        	value="<?php echo $row['state'];?>" id="demo-hor-5" 
                            	class="form-control required" placeholder="<?php echo translate('state');?>" >
					</div>
				</div>
                <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-6">
                    	<?php echo translate('country');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="country"  
                        	value="<?php echo $row['country'];?>" id="demo-hor-6" 
                            	class="form-control required" placeholder="<?php echo translate('country');?>" >
					</div>
				</div>
                <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-7">
                    	<?php echo translate('zipcode');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="zipcode"  
                        	value="<?php echo $row['zipcode'];?>" id="demo-hor-7" 
                            	class="form-control required" placeholder="<?php echo translate('zipcode');?>" >
					</div>
				</div>
                <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-8">
                    	<?php echo translate('email');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="email"  
                        	value="<?php echo $row['email'];?>" id="demo-hor-8" 
                            	class="form-control required" placeholder="<?php echo translate('email');?>" >
					</div>
				</div>
                
                  <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-9">
                    	<?php echo translate('phone_number');?>
                        	</label>
					<div class="col-sm-6">
						<input type="number" name="phone"  
                        	value="<?php echo $row['phone'];?>" id="demo-hor-8" 
                            	class="form-control required" placeholder="<?php echo translate('phone_number');?>" >
					</div>
				</div>
                            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-3">
                	<?php echo translate('delivery_zipcode');?>
                </label>
                <div class="col-sm-6">
                    <textarea class="form-control" name="delivery_zipcode" id="delivery_zipcode" rows="3"><?php echo $row['delivery_zipcode'];?> </textarea>
                </div>
            </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('category_banner');?></label>
                    <div class="col-sm-6">
                        <span class="pull-left btn btn-default btn-file">
                            <?php echo translate('select_category_banner');?>
                            <input type="file" name="logo" id='imgInp' accept="image">
                        </span>
                        <br><br>
                        <span id='wrap' class="pull-left" >
                            <?php
								if(file_exists('uploads/store_logo_image/logo_'.$row['store_id'].'.png')){
							?>
							<img src="<?php echo base_url(); ?>uploads/store_logo_image/logo_<?php echo $row['store_id']; ?>.png" width="100%" id='blah' />  
							<?php
								} else {
							?>
							<img src="<?php echo base_url(); ?>uploads/store_logo_image/default.jpg" width="100%" id='blah' />
							<?php
								}
							?> 
                        </span>
                    </div>
                </div>
			</div>
		</form>
	</div>
<?php
	}
?>

<script>
	$(document).ready(function() {
	    $("form").submit(function(e) {
	        return false;
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