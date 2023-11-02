<?php
$edit_rights=$user_rights_29_0['edit_rights'];
?>
<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('amount');?></th>
					<th><?php echo translate('type');?></th>
                    
					
					<?php if($edit_rights=='1'){ ?>
					<th class="text-right"><?php echo translate('options');?></th>
					<?php } ?>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i = 0;
			//	print_r($all_del_slot_time); exit;
            	foreach($rewards as $row){
					//echo "a"; exit;
				
            		$i++;
            		
			?>
			<tr>
				<td><?php echo $i; ?></td>
				
                
                <td><?php echo $row['rewards']; ?></td>
                <td><?php echo $row['type']; ?></td>
               
               
				<?php if($edit_rights=='1'){ ?>
				<td class="text-right">
					<a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                    	onclick="ajax_modal('edit','<?php echo translate('rewards'); ?>','<?php echo translate('successfully_edited!'); ?>','rewards_edit','<?php echo $row['id']; ?>')" 
                        	data-original-title="Edit" data-container="body">
                            	<?php echo translate('edit');?>
                    </a>
					
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
		<h1 style="display:none;"><?php echo translate('Manage Rewards'); ?></h1>
		<table id="export-table" data-name='rewards' data-orientation='p' style="display:none;">
				<thead>
					<tr>
						<th><?php echo translate('no');?></th>
					<th><?php echo translate('amount');?></th>
					<th><?php echo translate('type');?></th>
					</tr>
				</thead>
					
				<tbody >
				<?php
				$i = 0;
			//	print_r($all_del_slot_time); exit;
            	foreach($rewards as $row){
					//echo "a"; exit;
				
            		$i++;
            		
			?>
				<tr>
					<td><?php echo $i; ?></td>
				
                
                <td><?php echo $row['rewards']; ?></td>
                <td><?php echo $row['type']; ?></td>
				</tr>
	            <?php
	            	}
				?>
				</tbody>
		</table>
	</div>

