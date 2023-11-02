<?php
//print_r($del_slot_time); exit;
	foreach($del_slot_time as $row){
?>
	<div class="tab-pane fade active in" id="edit">
		<?php
			echo form_open(base_url() . 'index.php/admin/del_slot_time/update/' . $row['del_slot_time_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'del_slot_time_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('from_time');?>
                        	</label>
					<div class="col-sm-6">
						<input type="time" name="f_time"  
                        	value="<?php echo $row['f_time'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('from_time');?>" >
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('to_time');?>
                        	</label>
					<div class="col-sm-6">
						<input type="time" name="t_time"  
                        	value="<?php echo $row['t_time'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('to_time');?>" >
					</div>
				</div>
                
               <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('delivery_date');?></label>
                <div class="col-sm-6">
                   
                   	<?php ?><select class="form-control" name="del_slot">
                	<option value="">---Select Delivery Date---</option>
                   <?php 
                   $date=strtotime(date("Y/m/d"));
          
     $this->db->where('f_date >=', $date);
                   
                   $del_city = $this->db->get('del_slot')->result_array();
				   foreach($del_city as $dcity){ ?>
					   <option value="<?php echo $dcity['del_slot_id']; ?>" <?php if($row['del_slot_id']== $dcity['del_slot_id']){ ?> selected <?php } ?> ><?php echo date('Y-m-d',$dcity['f_date']); ?></option>
				   <?php } ?> 
					   
                </select><?php ?>
                </div>
            </div>
             <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('Slot');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="slot" id="demo-hor-1"  value="<?php echo $row['slot'];?>"
                    	class="form-control required" placeholder="<?php echo translate('100');?>" >
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