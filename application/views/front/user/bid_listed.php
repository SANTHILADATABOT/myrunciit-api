<?php 
	$i = 0;
	foreach ($bids as $row1) {
		
		//echo '<pre>'; print_r($row1); 
		$i++;
?>
	<tr>
		<td class="image">
			<?php echo $i; ?>
		</td>
		
        <td class="order-id">
			<?php 
				echo $row1['unique_no']; 
				
			?>
		</td>
        
		<td class="description">
			<?php echo $row1['uname']; ?>
		</td>
		<td class="order-id">
			<?php 
				echo $row1['bid_amt']; 
				
			?>
		</td>
        <td class="order-id">
			<?php 
				echo $row1['payment_mode']; 
				
			?>
		</td>
		
        <td class="quantity">
			<?php echo $row1['createad_date']; ?>
		</td>
		<td class="add">
			<?php
			
			if($row1['final_bidder']==0)
			{
				echo 'Waiting for admin approval';
			}
			if($row1['final_bidder']==1)
			{
				echo 'Win the bidd';
			}
			if($row1['final_bidder']==2)
			{
				echo '-';
			}
			
			
			
			 
			
			
			
			
			?>
		</td>
	</tr>                                            
<?php 
	}
?>


<tr class="text-center" style="display:none;" >
	<td id="pagenation_set_links" ><?php echo $this->ajax_pagination->create_links(); ?></td>
</tr>
<!--/end pagination-->


<script>
	$(document).ready(function(){ 
		$('.pagination_box').html($('#pagenation_set_links').html());
	});
</script>


