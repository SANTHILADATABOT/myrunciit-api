<?php
//print_r($del_slot_time); exit;
	foreach($rewards as $row){
?>
	<div class="tab-pane fade active in" id="edit">
		<?php
			echo form_open(base_url() . 'index.php/admin/rewards/update/' . $row['id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'rewards_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('amount');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="rewards"  
                        	value="<?php echo $row['rewards'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('rewards');?>" >
					</div>
				</div>
				
				
                
               <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('type');?></label>
                <div class="col-sm-6">
                   
                   	<?php ?><select class="form-control" name="type">
                	<option value="">---Select Type---</option>
                	<option value="%" <?php if ($row['type'] == '%') echo ' selected="selected"'; ?>>%</option>
                	<option value="flat" <?php if ($row['type'] == 'flat') echo ' selected="selected"'; ?>>Flat</option>
                   
					   
                </select><?php ?>
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