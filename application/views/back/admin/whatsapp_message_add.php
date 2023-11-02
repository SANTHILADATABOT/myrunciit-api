<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/whatsapp_message/do_add/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'whatsapp_message_add',
			'enctype' => 'multipart/form-data'
		));
	?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('category_name');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="phoneNumbers" id="demo-hor-1" 
                    	class="form-control required" placeholder="+60162345678, +60168765432" >
                </div>
            </div>
            <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-2">
                	<?php echo translate('Description');?>
                </label>
                <div class="col-sm-12">
           <textarea class="summernotes" name="content" data-height='700' data-name='content' class="required form-control" rows="20" style="width: 100%;"></textarea>
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