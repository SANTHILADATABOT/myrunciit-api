		<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('delivery_slot_date');?></th>
					<th><?php echo translate('Slot');?></th>
                    <th><?php echo translate('from_time');?></th>
                    <th><?php echo translate('to_time');?></th>
					
                   
					<th class="text-right"><?php echo translate('options');?></th>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i = 0;
			//	print_r($all_del_slot_time); exit;
            	foreach($all_del_slot_time as $row){
					//echo "a"; exit;
					$date= date('Y-m-d',$this->crud_model->get_type_name_by_id('del_slot',$row['del_slot_id'],'f_date'));
						$date1=strtotime(date('y-m-d'));
            		$i++;
            		if($date<=$date1 ){
			?>
			<tr>
				<td><?php echo $i; ?></td>
				
                <td><?php echo date('Y-m-d',$this->crud_model->get_type_name_by_id('del_slot',$row['del_slot_id'],'f_date')); ?></td>
                <td><?php echo $row['slot']; ?></td>
                <td><?php echo date('g:i a',strtotime($row['f_time'])); ?></td>
                <td><?php echo date('g:i a',strtotime($row['t_time'])); ?></td>
               
				
				<td class="text-right">
					<a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                    	onclick="ajax_modal('edit','<?php echo translate('del_slot_time'); ?>','<?php echo translate('successfully_edited!'); ?>','del_slot_time_edit','<?php echo $row['del_slot_time_id']; ?>')" 
                        	data-original-title="Edit" data-container="body">
                            	<?php echo translate('edit');?>
                    </a>
					<a onclick="delete_confirm('<?php echo $row['del_slot_time_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" 
                    	data-original-title="Delete" data-container="body">
                        	<?php echo translate('delete');?>
                    </a>
				</td>
			</tr>
            <?php
            }	}
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
						<th><?php echo translate('district_name');?></th>
					</tr>
				</thead>
					
				<tbody >
				<?php
					$i = 0;
	            	foreach($all_city as $row){
	            		$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $row['district_name']; ?></td>
				</tr>
	            <?php
	            	}
				?>
				</tbody>
		</table>
	</div>

