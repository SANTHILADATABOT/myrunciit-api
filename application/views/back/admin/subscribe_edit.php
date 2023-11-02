<?php
//echo "a"; exit;
	foreach($subscribe_data as $row){
?>
	<div class="tab-pane fade active in" id="edit">
		<?php
			echo form_open(base_url() . 'index.php/admin/subscribe/update/' . $row['id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'subscribe_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('Recharge / Topup Deliveries');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="delivery"  
                        	value="<?php echo $row['delivery'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('Recharge / Topup Deliveries');?>" >
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('Recharge / Topup Deliveries');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="amount"  
                        	value="<?php echo $row['amount'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('amount');?>" >
					</div>
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
</script>