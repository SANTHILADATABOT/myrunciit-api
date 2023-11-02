<?php
	foreach($category_data as $row){
?>
	<div class="tab-pane fade active in" id="edit">
		<?php
			echo form_open(base_url() . 'index.php/admin/category/update/' . $row['category_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'category_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('category_name');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="category_name"  
                        	value="<?php echo $row['category_name'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('category_name');?>" >
					</div>
				</div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('category_icon');?></label>
                    <div class="col-sm-6">
                        <span class="pull-left btn btn-default btn-file">
                            <?php echo translate('select_category_icon');?>
                            <input type="file" name="img" id='imgInp' accept="image">
                        </span>
                        <br><br>
                        <span id='wrap' class="pull-left" >
                            <?php
								if(file_exists('uploads/category_image/'.$row['banner'])){
							?>
							<img src="<?php echo base_url(); ?>uploads/category_image/<?php echo $row['banner']; ?>" width="100%" id='blah' />  
							<?php
								} else {
							?>
							<img src="<?php echo base_url(); ?>uploads/category_image/default.jpg" width="100%" id='blah' />
							<?php
								}
							?> 
                        </span>
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('category_banner');?></label>
                    <div class="col-sm-6">
                        <span class="pull-left btn btn-default btn-file">
                            <?php echo translate('select_category_banner');?>
                            <input type="file" name="img_banner" id='img_banner' accept="image">
                        </span>
                        <br><br>
                        <span id='wrap1' class="pull-left" >
                            <?php
								if(file_exists('uploads/category_image/'.$row['icon'])){
							?>
							<img src="<?php echo base_url(); ?>uploads/category_image/<?php echo $row['category_banner']; ?>" width="250px" height="250px" id='blah1' />  
							<?php
								} else {
							?>
							<img src="<?php echo base_url(); ?>uploads/category_image/default.jpg" width="250px" height="250px" id='blah1' />
							<?php
								}
							?> 
                        </span>
                    </div>
                </div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-2">
						<?php echo translate('banner_status');?>
					</label>
					<div class="col-sm-6">
						<input type="radio" name="status_cate" value="1" <?php if($row['banner_status']=='1'){echo "checked";}?>/>Enable
						&nbsp;
						<input type="radio" name="status_cate" value="0"<?php if($row['banner_status']=='0'){echo "checked";}?>/>Disable
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
	function readURL_banner(input) {
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
		readURL_banner(this);
	});
	
</script>