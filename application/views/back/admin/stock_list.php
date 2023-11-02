<?php
$edit_rights=$user_rights_3_6['edit_rights'];
$delete_rights=$user_rights_3_6['delete_rights'];
?>
	<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped" data-pagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true">
			<thead>
				<tr>
					<th style="width:4ex"><?php echo translate('ID'); ?></th>
					<th><?php echo translate('product_title'); ?></th>
					<th><?php echo translate('entry_type'); ?></th>
					<th><?php echo translate('last_modified_user'); ?></th>
					<th><?php echo translate('quantity'); ?></th>
					<th><?php echo translate('note'); ?></th>
					<th class="text-right"><?php echo translate('options'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($all_stock as $row) {
				?>
					<tr>
						<td><?php echo $row['stock_id']; ?></td>
						<td><?php echo $this->crud_model->get_type_name_by_id('product', $row['product'], 'title'); ?></td>
						<td><?php echo $row['type']; ?></td>
						<td><?php if($row['user_id'] == 0) { echo '-'; } else { echo $this->crud_model->get_type_name_by_id('admin', $row['user_id'], 'name'); } ?></td>
						<td><?php echo $row['quantity']; ?></td>
						<td><?php echo $row['reason_note']; ?></td>
						<td class="text-right">
							<?php if($edit_rights=='1'){ ?>
							 <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                            onclick="ajax_modal('edit','<?php echo translate('edit_stock'); ?>','<?php echo translate('successfully_edited!'); ?>','stock_edit','<?php echo $row['stock_id']; ?>')" 
                                data-original-title="Edit" 
                                    data-container="body"><?php echo translate('edit');?>
                        </a>
								<?php } ?>
							<?php
							
							if ($row['type'] == 'add') {
							?>
								<a onclick="delete_confirm('<?php echo $row['stock_id']; ?>','<?php echo translate('added_quantity_will_be_reduced.'); ?> <?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-xs btn-danger btn-labeled fa fa-trash" data-toggle="tooltip" data-original-title="Delete" data-container="body"><?php echo translate('delete'); ?>
								</a>
							<?php
							} else if ($row['type'] == 'destroy') {
							?><?php if($delete_rights=='1'){ ?>
								<a onclick="delete_confirm('<?php echo $row['stock_id']; ?>','<?php echo translate('reduced_quantity_will_be_added.'); ?> <?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-xs btn-danger btn-labeled fa fa-trash" data-toggle="tooltip" data-original-title="Delete" data-container="body"><?php echo translate('delete'); ?>
								</a>
								<?php } ?>
							<?php
							}
							?>
						</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<div id='export-div' style="padding:40px;">
		<h1 id='export-title' style="display:none;"><?php echo translate('product_stock'); ?></h1>
		<table id="export-table" class="table" data-export-types="['excel','pdf']" data-show-export="true" data-name='product_stock' data-orientation='p' data-width='1500' style="display:none;">
			<colgroup>
				<col width="50">
				<col width="150">
				<col width="150">
				<col width="150">
				<col width="150">
			</colgroup>
			<thead>
				<tr>
					<th style="width:4ex"><?php echo translate('ID'); ?></th>
					<th><?php echo translate('product_title'); ?></th>
					<th><?php echo translate('entry_type'); ?></th>
					<th><?php echo translate('quantity'); ?></th>
					<th><?php echo translate('note'); ?></th>
				</tr>
			</thead>



			<tbody>
				<?php
				foreach ($all_stock as $row) {
				?>
					<tr>
						<td><?php echo $row['stock_id']; ?></td>
						<td><?php echo $this->crud_model->get_type_name_by_id('product', $row['product'], 'title'); ?></td>
						<td><?php echo $row['type']; ?></td>
						<td><?php echo $row['quantity']; ?></td>
						<td><?php echo $row['reason_note']; ?></td>
						
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>