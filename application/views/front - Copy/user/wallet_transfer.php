<?php 
	$i = 0;
	foreach ($wallet_history as $row1) {
		
		//echo '<pre>'; print_r($row1); 
		$i++;
?>
	<tr>
		<td class="image">
			<?php echo $i; ?>
		</td>
		
		<td class="description">
			<?php echo ucfirst($user_name[0]['username']); ?>
		</td>
		<td class="order-id">
			<?php 
				echo $row1['description']; 
				
			?>
		</td>
		
        <td class="quantity">
			<?php echo $row1['created_date']; ?>
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


