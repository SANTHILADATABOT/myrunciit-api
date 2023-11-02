<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/category/do_add/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'category_add',
			'enctype' => 'multipart/form-data'
		));
	?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('category_name');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="category_name" id="demo-hor-1" 
                    	class="form-control required" placeholder="<?php echo translate('category_name');?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-2">
                    <?php echo translate('category_icon');?>
                </label>
                <div class="col-sm-6">
                    <span class="pull-left btn btn-default btn-file">
                        <?php echo translate('select_category_icon');?>
                        <input type="file" name="img" id='imgInp' accept="image">
                    </span>
                    <br><br>
                    <span id='wrap' class="pull-left" >
                        <img src="<?php echo base_url(); ?>uploads/category_image/default.jpg" 
                            width="100%" id='blah' >
                    </span>
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-2">
                    <?php echo translate('category_banner');?>
                </label>
                <div class="col-sm-6">
                    <span class="pull-left btn btn-default btn-file">
                        <?php echo translate('select_category_banner');?>
                        <input type="file" name="img_banner" id='img_banner' accept="image">
                    </span>
                    <br><br>
                    <span id='wrap1' class="pull-left" >
                        <img src="<?php echo base_url(); ?>uploads/category_image/default.jpg" 
						width="250px" height="250px" id='blah1' >
                    </span>
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-2">
                    <?php echo translate('banner_status');?>
                </label>
				<div class="col-sm-6">
					<input type="radio" name="status_cate" value="1"/>Enable
					&nbsp;
					<input type="radio" name="status_cate" value="0"/>Disable
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

	function readURL1(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
	
			reader.onload = function(e) {
				$('#wrap1').hide('fast');
				$('#blah1').attr('src', e.target.result);
				$('#wrap1').show('fast');
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#imgInp").change(function() {
		readURL(this);
	});
	$("#img_banner").change(function() {
		readURL1(this);
	});
</script>