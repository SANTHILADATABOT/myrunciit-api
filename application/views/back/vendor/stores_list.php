	<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('store_name');?></th>
                    <th><?php echo translate('Products');?></th>
                    <th><?php echo translate('Orders');?></th>
                    <th><?php echo translate('Status');?></th>
                    <th><?php echo translate('Created_date');?></th>
					<th class="text-right"><?php echo translate('options');?></th>
				</tr>
			</thead>
				
			<tbody >
			<?php
	//	echo "<pre>";	print_r($all_stores); exit;
				$i = 0;
            	foreach($all_stores as $row){
          //  	    print_r($row);
            		$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
                <td><?php echo $row['store_name']; ?></td>
                <td><?php echo $this->db->get_where('product',array('store_id' => $row['store_id']))->num_rows(); ?></td>
                <td><?php echo $this->db->get_where('sale',array('store_id' => $row['store_id']))->num_rows(); ?></td>
                <td><?php if($row['status']=='no'){ ?> <a  class="btn btn-danger btn-xs btn-labeled">Inavtive </a><?php } else if($row['status']=='ok') { ?> <a  class="btn btn-success btn-xs btn-labeled">Active </a> <?php } ?></td>
				
               <td>	<?php echo date('d M,Y',$row['created_time']);?></td>
				<td class="text-right">
                
					<a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                    	onclick="ajax_modal('edit','<?php echo translate('edit_store'); ?>','<?php echo translate('successfully_edited!'); ?>','stores_edit','<?php echo $row['store_id']; ?>')" 
                        	data-original-title="Edit" data-container="body">
                            	<?php echo translate('edit');?>
                    </a>
					<a onclick="delete_confirm('<?php echo $row['store_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" 
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
   
