<?php 
	$i = 0;
	foreach ($subscribe_product_history as $row) {
		$i++;
?>
	<tr>
	    <td>
			<?php echo $i; ?>
		</td>
		<td>
			<?php echo $this->crud_model->get_type_name_by_id('product', $row['product_id'], 'title') ?>
		</td>
		
		<td>
		   <?php echo $row['quantity']; ?>
		</td>
		<td>
			<?php echo $this->crud_model->get_type_name_by_id1('subscribe_pro',$row['subscribe_recharge'],'delivery'); ?>
		</td>
		
        <td>
			<?php echo $row['subscribe_package']; ?>
		</td>
		 <td>
			<?php echo $row['subscribe_from']; ?>
		</td>
		 <td>
			<?php $days= json_decode($row['subscribe_days'],true); 
			
			if($days['mon']=='ok'){ echo "Monday </br>"; } 
			if($days['tue']=='ok'){ echo "tuesday </br>"; } 
			if($days['wed']=='ok'){ echo "wednesday </br>"; } 
			if($days['thu']=='ok'){ echo "thursday </br>"; } 
			if($days['fri']=='ok'){ echo "friday </br>"; }
			if($days['sat']=='ok'){ echo "saturday </br>"; } 
			if($days['sun']=='ok'){ echo "sunday </br>"; } 
			
			?>
		</td>
		 <td>
			<?php
		$current_date=date('Y-m-d');
		$vals = array_count_values($days);
    	$deliver_days =	        $vals['ok'];
    	$total_deliver   =      $row['subscribe_package_amount'];
    	$frm_date   =	        $row['subscribe_from'];
    	
    	if( $current_date > $frm_date ){
    	
            	if($deliver_days == 7)
            	{
            	    $expired_date = date('Y-m-d', strtotime($row['subscribe_from']. "+ $total_deliver days"));
            	   
                    	   if($expired_date <= $current_date){
                    	       echo translate('subscription is expired');
                    	   }
                    	 else
                    	   {
                    	        $date1=date_create($current_date);
                                $date2=date_create($frm_date);
                                $diff=date_diff($date1,$date2);
                                $pending = $diff->format("%R%a"); 
                                $pending =$total_deliver - $pending;
                                
                               echo translate( $pending.'  deliveries_pending');
                    	   }
            	}
            	else{
            	    
            	    
            	    
            	}
	    
    	}
    	else
        {
            echo translate('no_actions');
        }
			
			 ?>
		</td>
		
	</tr>                                            
<?php 
	}
?>


<tr class="text-center" style="display:none;">
	<td id="pagenation_set_links"><?php echo $this->ajax_pagination->create_links(); ?></td>
</tr>
<!--/end pagination-->


<script>
	$(document).ready(function(){ 
		$('.pagination_box').html($('#pagenation_set_links').html());
	});
</script>


