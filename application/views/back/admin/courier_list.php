	<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('agent_name');?></th>
					<th><?php echo translate('address');?></th>
					
					<th><?php echo translate('city');?></th>
                    <th><?php echo translate('email');?></th>
                    <th><?php echo translate('mobile_phone');?>
                   
					<th class="text-right"><?php echo translate('options');?></th>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i = 0;
            	foreach($all_courier as $row){
            		$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
                <td><?php echo $row['agent_name']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['city']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['mobile_phone']; ?></td>
				
				<td class="text-right">
					<a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                    	onclick="ajax_modal('edit','<?php echo translate('edit_courier'); ?>','<?php echo translate('successfully_edited!'); ?>','courier_edit','<?php echo $row['agent_id']; ?>')" 
                        	data-original-title="Edit" data-container="body">
                            	<?php echo translate('edit');?>
                    </a>
					<a onclick="delete_confirm('<?php echo $row['agent_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" 
                    	data-original-title="Delete" data-container="body">
                        	<?php echo translate('delete');?>
                    </a>
				</td>
			</tr>
            <?php
            	}
			?>
			</tbody>
		</table>
	</div>
           
	<div id='export-div'>
		<h1 style="display:none;"><?php echo translate('category'); ?></h1>
		<table id="export-table" data-name='category' data-orientation='p' style="display:none;">
				<thead>
					<tr>
						<th><?php echo translate('no');?></th>
						<th><?php echo translate('name');?></th>
					</tr>
				</thead>
					
				<tbody >
				<?php
					$i = 0;
	            	foreach($all_courier as $row){
	            		$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $row['category_name']; ?></td>
				</tr>
	            <?php
	            	}
				?>
				</tbody>
		</table>
	</div>

