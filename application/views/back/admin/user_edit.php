<?php
	foreach($user_data as $row){
?>
	<div class="tab-pane fade active in" id="edit">
		<?php
			echo form_open(base_url() . 'index.php/admin/user/update/' . $row['user_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'user_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="image">
                    	<?php echo translate('image');?>
                        	</label>
					<div class="col-sm-6">
					<img class="img-sm img-circle img-border" <?php if (file_exists('uploads/user_image/user_' . $row['user_id'] . '.jpg')) { ?> src="<?php echo base_url(); ?>uploads/user_image/user_<?php echo $row['user_id']; ?>.jpg" <?php } else if ($row['fb_id'] != '') { ?> src="https://graph.facebook.com/<?php echo $row['fb_id']; ?>/picture?type=large" data-im='fb' <?php } else if ($row['g_id'] != '') { ?> src="<?php echo $row['g_photo']; ?>" <?php } else { ?> src="<?php echo base_url(); ?>uploads/user_image/no_image.png" <?php } ?> />
					<!-- <img class="img-sm img-circle img-border" src="<?php echo base_url(); ?>uploads/user_image/user_<?php echo $row['user_id']; ?>.jpg"  /> -->
						<input type="file" name="image"  
                        	value="<?php echo $row['image'];?>" id="image" 
                            	class="form-control" placeholder="<?php echo translate('image');?>" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="name">
                    	<?php echo translate('name');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="name"  
                        	value="<?php echo $row['username'];?>" id="name" 
                            	class="form-control required" placeholder="<?php echo translate('name');?>" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="email">
                    	<?php echo translate('email');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="email"  
                        	value="<?php echo $row['email'];?>" id="email" 
                            	class="form-control required" placeholder="<?php echo translate('email');?>" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="phone">
                    	<?php echo translate('phone');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="phone"  
                        	value="<?php echo $row['phone'];?>" id="phone" 
                            	class="form-control required" placeholder="<?php echo translate('phone');?>" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="age">
                    	<?php echo translate('age');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="age"  
                        	value="<?php echo $row['age'];?>" id="age" 
                            	class="form-control" placeholder="<?php echo translate('age');?>" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="gender">
                    	<?php echo translate('gender');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="gender"  
                        	value="<?php echo $row['gender'];?>" id="gender" 
                            	class="form-control" placeholder="<?php echo translate('gender');?>" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('rewards');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="rewards"  
                        	value="<?php echo $row['rewards'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('rewards');?>" >
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