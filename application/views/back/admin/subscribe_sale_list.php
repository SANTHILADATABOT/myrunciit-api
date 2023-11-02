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
					<th ><?php echo translate('status');?></th>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i=0;
            	foreach($subscribe_sale as $row){
            		$i++;
			?>
                <tr>
                    <td><?php echo $i;  ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('product', $row['product_id'], 'title') ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php  echo $this->crud_model->get_type_name_by_id('user',$row['user_id'],'username');  ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id1('subscribe_pro',$row['subscribe_recharge'],'delivery');   ?></td>
                    <td><?php echo $row['subscribe_package']; ?></td>
                    <td><?php echo $row['subscribe_from']; ?></td>
                    <td>	
                    <?php $days= json_decode($row['subscribe_days'],true); 
			
            			if($days['mon']=='ok'){ echo "Monday </br>"; } 
            			if($days['tue']=='ok'){ echo "tuesday </br>"; } 
            			if($days['wed']=='ok'){ echo "wednesday </br>"; } 
            			if($days['thu']=='ok'){ echo "thursday </br>"; } 
            			if($days['fri']=='ok'){ echo "friday </br>"; }
            			if($days['sat']=='ok'){ echo "saturday </br>"; } 
            			if($days['sun']=='ok'){ echo "sunday </br>"; } 
            			
			      ?></td>
                    <td ><?php echo translate('expired');?></td>
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







           