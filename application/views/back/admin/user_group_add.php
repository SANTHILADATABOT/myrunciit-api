<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/user_group/do_add/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'user_group_add',
			'enctype' => 'multipart/form-data'
		));
	?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('user_group_name');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="user_group_name" id="demo-hor-1" 
                    	class="form-control required" placeholder="<?php echo translate('user_group_name');?>" >
                </div>
            </div>
            <div class="form-group">
				<label class="col-sm-4 control-label" for="demo-hor-2">
                	<?php echo translate('remarks');?>
                </label>
                <div class="col-sm-6">
					<textarea name="remarks" id="demo-hor-2" class="form-control " placeholder="<?php echo translate('remarks'); ?>"></textarea>
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
</script>