<?php
// $edit_rights=$user_rights_21['edit_rights'];
// $delete_rights=$user_rights_21['delete_rights'];
?>
<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped" data-pagination="true" data-ignorecol="0,4" data-show-toggle="true" data-show-columns="false" >

			<thead>
				<tr>
					<th><?php echo translate('no'); ?></th>
					<th><?php echo translate('product_name'); ?></th>
					<th><?php echo translate('start_date'); ?></th>
					<th><?php echo translate('start_time'); ?></th>
					<th><?php echo translate('end_date'); ?></th>
					<th><?php echo translate('end_time'); ?></th>
					<th><?php echo translate('status');?></th>
					<?php //if(($edit_rights=='1') || ($delete_rights=='1')){ ?>
					<!-- <th class="text-right"><?php echo translate('options'); ?></th> -->
					<?php //} ?>
				</tr>
			</thead>

			<tbody>
				<?php
				$i = 0;
				foreach ($all_deals as $row) {
					$i++;
					$status = $row['status'] == '0' ? 'Inactive' : 'Active';				
				?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $this->crud_model->get_type_name_by_id('product', str_replace(array( '["', '"]' ), '', $row['product_id']), 'title'); ?></td>
						<td><?php echo date('d-m-Y',strtotime($row['toda_start_date'])); ?></td>
						<td><?php echo date('h:i a',strtotime($row['today_start_time'])); ?></td>
						<td><?php echo date('d-m-Y', strtotime($row['today_end_date'])); ?></td>
						<td><?php echo date('h:i a',strtotime($row['today_end_time'])); ?></td>
						<td><?php echo $status; ?></td>
					<?php //if(($edit_rights=='1') || ($delete_rights=='1')){ ?>
						<td class="text-right">
						<?php //if($edit_rights=='1'){ ?>
							<!-- <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" onclick="ajax_modal('today_edit','<?php echo translate('edit_deal'); ?>','<?php echo translate('successfully_edited!'); ?>','today_edit','<?php echo $row['today_id']; ?>')" data-original-title="Edit" data-container="body"><?php echo translate('edit'); ?>
							</a> -->
						<?php// } ?>
						<?php //if($delete_rights=='1'){ ?>
							<!-- <a onclick="delete_confirm('<?php echo $row['brand_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" data-original-title="Delete" data-container="body"><?php echo translate('delete'); ?>
							</a> -->
						<?php// } ?>
						</td>
					<?php// } ?>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
