<!--CONTENT CONTAINER-->
<?php 
		
		//echo '<pre>'; print_r($row); 
?>

	<hr style="margin: 10px 0 !important;">
    <div class="row">
    <div class="col-md-12">

    <?php 
	//echo $baatch_max; exit;
	if($baatch_max>0)
					{
						$new=count($bidding_history['batch']);
						for($iz=0; $iz<$baatch_max; $iz++)
						{
						
						?>
    
    
        <table class="table table-striped" >
                       
                         <tr>
                           <th colspan="5" align="left" valign="middle" bgcolor="#0066FF"><span style="color:#FFF; font-weight:bold;">Batch No(
                           <?php if($iz==0){echo 'current';}else { echo $iz;}?> )</span></th>
                         </tr>
                         <tr>
                        <th bgcolor="#0066FF"  style="color:#FFF; font-weight:bold;" >&nbsp; &nbsp;S.No</th>
                        <th width="16%" bgcolor="#0066FF"  style="color:#FFF; font-weight:bold;" >User Name</th>
                        <th width="16%" bgcolor="#0066FF" style="color:#FFF; font-weight:bold;" >Bidd Amount</th>
                        <th width="28%" bgcolor="#0066FF" style="color:#FFF; font-weight:bold;" >Time</th>
                        <th width="22%" bgcolor="#0066FF" style="color:#FFF; font-weight:bold;" ><?php if($iz==0){ ?>Select Winner<?php } else{ echo 'Winner Status' ;}?></th>
                      </tr>
                        
                    <?php
						$i=1;
					foreach($product_bidd as $row)
        { 
		//echo '<pre>'; print_r($row); 
					if($row['batch_no']==$iz)
						{
							
					?>	
                        <tr>
                        <td height="35" bgcolor="#CCCCCC" style="color:#000; font-weight:bold;" >&nbsp; &nbsp;<?php echo $i;?></td>
                        <td bgcolor="#CCCCCC"  style="color:#000; font-weight:bold;" ><?php echo $row['uname'];?></td>
                        <td bgcolor="#CCCCCC"  style="color:#000; font-weight:bold;" ><?php echo $row['bid_amt'];?></td>
                        <td bgcolor="#CCCCCC"  style="color:#000; font-weight:bold;" ><?php echo date('d/m/Y h:i:s A',$row['time']);?></td>
                        
                        
                        <td bgcolor="#CCCCCC"  style="color:#000; font-weight:bold;">
                            <?php 
						//echo '<pre>'; print_r($mode);
							if($iz==0)
						{
						if($mode['type']=='vendor')
						{
						?>
                        <input id="final_bidder_<?php echo $i;?>" class="" name="final_bidder" type="radio" value="<?php echo $row['id'];?>" onclick="return final_bidd(this.value);"/>
                                        
                        
                        </td><?php }
						
						}
						
						else
						{
							if($row['final_bidder']==1)
							{
								echo 'Win the bidd';
							}
							if($row['final_bidder']==2)
							{
								echo '-';
							}
						}
						 ?>
                         
                        </tr>
                        
                    <?php  
					$i++;
					} }  ?>
                    </table>
                    
                    <?php } }?>
    </div>
</div>				

        
<style>
.custom_td{
border-left: 1px solid #ddd;
border-right: 1px solid #ddd;
border-bottom: 1px solid #ddd;
}
</style>






<script>
	function final_bidd(val)
	{
		//alert(val); 
		var base_url = '<?php echo base_url(); ?>index.php/vendor/min_bidd/'+val;
		$.post(base_url, function(data) {
		 alert(data);
		window.location.replace("<?php echo base_url(); ?>index.php/vendor/product/");
		 });
	}
</script>