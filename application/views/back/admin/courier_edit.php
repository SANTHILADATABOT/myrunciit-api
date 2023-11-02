<?php
	foreach($courier_data as $row){
?>
	<div class="tab-pane fade active in" id="edit">
		<?php
			echo form_open(base_url() . 'index.php/admin/courier/update/' . $row['agent_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'courier_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('agent_name');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="agent_name"  
                        	value="<?php echo $row['agent_name'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('agent_name');?>" >
					</div>
				</div>
                
                <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('address');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="address"  
                        	value="<?php echo $row['address'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('address');?>" >
					</div>
				</div>
                
                <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('city');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="city"  
                        	value="<?php echo $row['city'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('city');?>" >
					</div>
				</div>
                
                <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('state');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="state"  
                        	value="<?php echo $row['state'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('state');?>" >
					</div>
				</div>
                
                <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('country');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="country"  
                        	value="<?php echo $row['country'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('country');?>" >
					</div>
				</div>
                <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('email');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="email"  
                        	value="<?php echo $row['email'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('email');?>" >
					</div>
				</div>
                <div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('mobile_phone');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="mobile_phone"  
                        	value="<?php echo $row['mobile_phone'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('mobile_phone');?>" >
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