<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/city/do_add/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'city_add',
			'enctype' => 'multipart/form-data'
		));

	?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('city_name');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="district_name" id="demo-hor-1" 
                    	class="form-control required" placeholder="<?php echo translate('district_name');?>" >
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
	
	$("#imgInp").change(function() {
		readURL(this);
	});
</script>