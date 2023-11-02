<?php
$edit_rights=$user_rights_13_11['edit_rights'];
$delete_rights=$user_rights_13_11['delete_rights'];
?>
	<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped" data-export-types="['excel','pdf']" data-show-export="true"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('name');?></th>
                    <th><?php echo translate('remarks');?></th>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i = 0;
            	foreach($all_user_groups as $row){
            		$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td>						
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div style="text-align: left;">#<?php echo $row['user_group_name']; ?></div>
						<?php if(($edit_rights=='1') || ($delete_rights=='1')){ ?>
						<div class="btn-group">
							<!-- <button class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> -->
								<i class="fa fa-chevron-circle-down icon-default" aria-hidden="true" id="dropdownMenu<?php echo $i; ?>" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></i>
							<!-- </button> -->
							<ul class="dropdown-menu" aria-labelledby="dropdownMenu<?php echo $i; ?>">
							<?php if($edit_rights=='1'){ ?>
							<li><a style="color:white;width:100%;text-align:left;" class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" onclick="ajax_modal('edit','<?php echo translate('edit_customer_group'); ?>','<?php echo translate('successfully_edited!'); ?>','user_group_edit','<?php echo $row['user_group_id']; ?>')" data-original-title="Edit" data-container="body"><?php echo translate('edit');?></a></li>
							<?php } ?>
							<?php if($delete_rights=='1'){ ?>
							<li><a style="color:white;width:100%;text-align:left;" onclick="delete_confirm('<?php echo $row['user_group_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" data-original-title="Delete" data-container="body"><?php echo translate('delete');?></a></li>
							<?php } ?>
							</ul>
						</div>
						<?php } ?>
					</div>
				</td>          
				<td><?php echo $row['remarks']; ?></td>
			</tr>
            <?php
            	}
			?>
			</tbody>
		</table>
	</div>
           
	<div id='export-div'>
		<h1 style="display:none;"><?php echo translate('user_group'); ?></h1>
		<table id="export-table" data-name='user_group' data-orientation='p' style="display:none;">
		<colgroup>
            <col width="100">
            <col width="300">
            <col width="300">
            
            
        </colgroup>
				<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('name');?></th>
                    <th><?php echo translate('remarks');?></th>
					
				</tr>
				</thead>
					
				<tbody >
				<?php
				$i = 0;
            	foreach($all_user_groups as $row){
            		$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
                <td><?php echo $row['user_group_name']; ?></td>                
				<td><?php echo $row['remarks']; ?></td>
               	
				
			</tr>
            <?php
            	}
			?>
				</tbody>
		</table>
	</div>

