	<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,4" data-show-toggle="false" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('product_name');?></th>
					<th><?php echo translate('quantity');?></th>
					<th><?php echo translate('user_name');?></th>
					<th><?php echo translate('delivery_package');?></th>
					<th><?php echo translate('subscribed_package');?></th>
					<th><?php echo translate('subscribed_from');?></th>
					<th><?php echo translate('subscribe_days');?></th>
					<th class="text-right"><?php echo translate('options');?></th>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i=0;
            	foreach($subscribe_sale as $row){
            		$i++;
			?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['product_id']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['subscribe_recharge']; ?></td>
                    <td><?php echo $row['subscribe_package']; ?></td>
                    <td><?php echo $row['subscribe_from']; ?></td>
                    <td><?php echo $row['subscribe_days']; ?></td>
                    <td class="text-right">
                        
                        <a onclick="delete_confirm('<?php echo $row['id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" 
                            class="btn btn-danger btn-xs btn-labeled fa fa-trash" 
                                data-toggle="tooltip" data-original-title="Delete" 
                                    data-container="body"><?php echo translate('delete');?>
                        </a>
                    </td>
                </tr>
            <?php
            	}
			?>
			</tbody>
		</table>
	</div>
           

<style>
	.highlight{
		background-color: #E7F4FA;
	}
</style>







           