	<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('name');?></th>
                    <th><?php echo translate('banner');?></th>
					<th class="text-right"><?php echo translate('options');?></th>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i = 0;
            	foreach($all_store as $row){
            		$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
                <td><?php echo $row['name']; ?></td>
                
				<td>
                   <?php
                    if(file_exists('uploads/store_logo_image/logo_'.$row['store_id'].'.png')){
                ?>
                <img class="img-sm img-border"
                    src="<?php echo base_url(); ?>uploads/store_logo_image/logo_<?php echo $row['store_id']; ?>.png" />  
                <?php
                    } else {
                ?>
                <img class="img-sm img-border"
                    src="<?php echo base_url(); ?>uploads/store_logo_image/default.jpg" alt="">
                <?php
                    }
                ?>
               	</td>
               	
				<td class="text-right">
               
					<a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                    	onclick="ajax_modal('edit','<?php echo translate('edit_store'); ?>','<?php echo translate('successfully_edited!'); ?>','store_edit','<?php echo $row['store_id']; ?>')" 
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
           
	<div id='export-div'>
		<h1 style="display:none;"><?php echo translate('store'); ?></h1>
		<table id="export-table" data-name='store' data-orientation='p' style="display:none;">
				<thead>
					<tr>
						<th><?php echo translate('no');?></th>
						<th><?php echo translate('name');?></th>
					</tr>
				</thead>
					
				<tbody >
				<?php
					$i = 0;
	            	foreach($all_store as $row){
	            		$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $row['name']; ?></td>
				</tr>
	            <?php
	            	}
				?>
				</tbody>
		</table>
	</div>

