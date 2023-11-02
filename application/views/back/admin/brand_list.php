<?php
$edit_rights=$user_rights_21['edit_rights'];
$delete_rights=$user_rights_21['delete_rights'];
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js">
  </script>
<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped" data-export-types="['excel','pdf']" data-show-export="true" data-pagination="true" data-show-refresh="true" data-ignorecol="0,4" data-show-toggle="true" data-show-columns="false" data-search="true">

			<thead>
				<tr>
					<th><?php echo translate('no'); ?></th>
					<th><?php echo translate('logo'); ?></th>
					<th><?php echo translate('name'); ?></th>
					<th><?php echo translate('status');?></th>
					<?php if(($edit_rights=='1') || ($delete_rights=='1')){ ?>
					<th class="text-right"><?php echo translate('options'); ?></th>
					<?php } ?>
				</tr>
			</thead>

			<tbody>
				<?php
				$i = 0;
				foreach ($all_brands as $row) {
					$i++;
				?>
					<tr>
						<td><?php echo $i; ?></td>
						<td>
							<?php
							if (file_exists('uploads/brand_image/' . $row['logo'])) {
							?>
								<img class="img-md" src="<?php echo base_url(); ?>uploads/brand_image/<?php echo $row['logo']; ?>" />
							<?php
							} else {
							?>
								<img class="img-md" src="<?php echo base_url(); ?>uploads/brand_image/default.jpg" />
							<?php
							}
							?>
						</td>
						<td><?php echo $row['name']; ?></td>
						<td>
							<input class='aiz_switchery' type="checkbox" data-set='suspend_set' data-id="<?php echo $row['brand_id']; ?>" data-tm='<?php echo translate('Brand Enabled'); ?>' data-fm='<?php echo translate('Brand Suspended'); ?>' <?php if($row['status'] == 'ok'){ ?>checked<?php } ?> />
						</td>
					<?php if(($edit_rights=='1') || ($delete_rights=='1')){ ?>
						<td class="text-right">
						<?php if($edit_rights=='1'){ ?>
							<a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" onclick="ajax_modal('edit','<?php echo translate('edit_brand_(_physical_product_)'); ?>','<?php echo translate('successfully_edited!'); ?>','brand_edit','<?php echo $row['brand_id']; ?>')" data-original-title="Edit" data-container="body"><?php echo translate('edit'); ?>
							</a>
						<?php } ?>
						<?php if($delete_rights=='1'){ ?>
							<a onclick="delete_confirm('<?php echo $row['brand_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" data-original-title="Delete" data-container="body"><?php echo translate('delete'); ?>
							</a>
						<?php } ?>
						</td>
					<?php } ?>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>

	<div id='export-div'>
		<h1 style="display:none;"><?php echo translate('brand'); ?></h1>
		<table id="export-table" data-name='brand' data-orientation='p' style="display:none;">
		<colgroup>
            <col width="100">
            <col width="300">
            <col width="300">
            <col width="200">
            
        </colgroup>
			<thead>
				<tr>
					<th><?php echo translate('no'); ?></th>
					<th><?php echo translate('logo'); ?></th>
					<th><?php echo translate('name'); ?></th>
					<th><?php echo translate('status');?></th>
				</tr>
			</thead>

			<tbody>
				<?php
				$i = 0;
				foreach ($all_brands as $row) {
					$i++;
				?>
					<tr height="100">
						<td><?php echo $i; ?></td>
						<td>
							<div class="p-3">
							<?php
							if (file_exists('uploads/brand_image/' . $row['logo'])) {
							?>
								<img class="img-md" width="50" height="50" src="<?php echo base_url(); ?>uploads/brand_image/<?php echo $row['logo']; ?>" />
							<?php
							} else {
							?>
								<img class="img-md" width="50" height="50" src="<?php echo base_url(); ?>uploads/brand_image/default.jpg" />
							<?php
							}
							?>
							</div>
						</td>
						<td><?php echo $row['name']; ?></td>
						<td><?php if($row['status'] == 'ok'){ echo 'Enabled'; } else { echo 'Suspended'; } ?></td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>

	<style>
		.highlight {
			background-color: #E7F4FA;
		}
	</style>

<script>
  function set_switchery1(){
		$(".aiz_switchery").each(function(){
			new Switchery($(this).get(0), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});

			var changeCheckbox = $(this).get(0);
			var false_msg = $(this).data('fm');
			var true_msg = $(this).data('tm');
			changeCheckbox.onchange = function() {
				$.ajax({url: base_url+'index.php/admin/brand/'+$(this).data('set')+'/'+$(this).data('id')+'/'+changeCheckbox.checked, 
				success: function(result){	
				  if(changeCheckbox.checked == true){
					$.activeitNoty({
						type: 'success',
						icon : 'fa fa-check',
						message : true_msg,
						container : 'floating',
						timer : 3000
					});
					sound('Brand Enabled');
				  } else {
					$.activeitNoty({
						type: 'danger',
						icon : 'fa fa-check',
						message : false_msg,
						container : 'floating',
						timer : 3000
					});
					sound('Brand Suspended');
				  }
				}});
			};
		});
	}

$(document).ready(function(){
	set_switchery1();
	$('#demo-table').bootstrapTable({

}).on('all.bs.table', function(e, name, args) {
	//alert('1');
	//set_switchery();
}).on('click-row.bs.table', function(e, row, $element) {

}).on('dbl-click-row.bs.table', function(e, row, $element) {

}).on('sort.bs.table', function(e, name, order) {

}).on('check.bs.table', function(e, row) {

}).on('uncheck.bs.table', function(e, row) {

}).on('check-all.bs.table', function(e) {

}).on('uncheck-all.bs.table', function(e) {

}).on('load-success.bs.table', function(e, data) {
	set_switchery1();
}).on('load-error.bs.table', function(e, status) {

}).on('column-switch.bs.table', function(e, field, checked) {

}).on('page-change.bs.table', function(e, size, number) {
	//alert('1');
	//set_switchery();
}).on('search.bs.table', function(e, text) {
	set_switchery1();
});
});
</script>