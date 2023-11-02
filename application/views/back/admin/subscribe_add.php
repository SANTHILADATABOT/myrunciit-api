<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/subscribe/do_add/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'subscribe_add',
			'enctype' => 'multipart/form-data'
		));
	?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('Recharge / Topup Deliveries');?>
                    	</label>
                <div class="col-sm-6">
                    <input type="text" name="delivery" id="demo-hor-1" 
                    	class="form-control required" placeholder="<?php echo translate('Recharge / Topup Deliveries');?>" >
                </div></div>
                
                 <div class="form-group">
                 <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('Amount');?>
                    	</label>
                <div class="col-sm-6">
                    <input type="text" name="amount" id="demo-hor-1" 
                    	class="form-control required" placeholder="<?php echo translate('amount');?>" >
                </div></div>
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
</script>