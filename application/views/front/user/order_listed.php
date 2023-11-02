
	
        	<div class="ShLswe">
            <?php 
	
	foreach ($orders as $row1) {
		$pro=json_decode($row1['product_details'],true);
			foreach ($pro as $prd) {
			//	print_r($prd);
		$tax = $row1['tax'];
         $price = $row1['grandtotal'];
         $discount_details = json_decode($row1['discount'], true);
         $delivery_charge=0;
       if($row1['lalamove_res']!="")
      {
        $lalamove_res = json_decode($row1['lalamove_res'],true);
       foreach($lalamove_res as $key=>$value)
      {
       if($value!="")
      {
       $lalamove_res1 = json_decode($value,true);
       if($lalamove_res1['data']['priceBreakdown']['total']!="")
       { $delivery_charge+=floatval($lalamove_res1['data']['priceBreakdown']['total']);}
      }
    }
   }
   $discount = number_format($discount_details,2);
    //   $discount = $this->cart->total_discount();
       $subtotal = $price- $discount;
        $total = $delivery_charge+$subtotal;
?>			
           	<div class="_2WFi0x">
               <div class="row">
                  <div class="col-6-12">
                  	 <a class="" href="<?php echo base_url(); ?>index.php/home/invoice_details/<?php echo $row1['order_id']; ?>/<?php echo $row1['sale_id']; ?>">
                     <div class="row">
                        <!--<div class="col-3-12">-->
                        <!--   <div class="J2h1WZ">-->
                        <!--      <div class="_3BTv9X" style="height: 75px; width: 75px;"><img class="_1Nyybr  _30XEf0" alt="" src="<?php echo $prd['image']; ?>"></div>-->
                        <!--   </div>-->
                        <!--</div>-->
                        <div class="col-3-12">
                           <div class="_3D-3p2">
                              <span class="row _13y4_y _1iu0PI"><?php echo $row1['order_id']; ?></span>
                              <div class="row _3i00zY" style="display:none;"><span class="J1KvyN"><span class="_3i00zY">Color: </span><span class="_2dTbPB">Pink</span></span><span class="J1KvyN"><span class="_3i00zY">Size: </span><span class="_2dTbPB">M</span></span><span class="J1KvyN"></span></div>
                              <div class="row _3i00zY"><span class="_3i00zY _2n1WrW">Seller: </span><span class="_2dTbPB"><?php echo $this->crud_model->product_by($prd['id']);?></span></div>
                           </div>
                        </div>
                        <div class="col-8-12">
                           <div class="J2h1WZ">
                              <div class="_3BTv9X" style=""><span class="_2dTbPB"><?php echo $this->crud_model->get_type_name_by_id('vendor', $row1['store_id'], 'display_name'); ?></span></div>
                           </div>
                        </div>
                     </div>
                     </a>
                  </div>
                  <div class="col-2-12 JL36Xz"> <a class="" href="<?php echo base_url(); ?>index.php/home/invoice_details/<?php echo $row1['order_id']; ?>/<?php echo $row1['sale_id']; ?>"><?php echo currency() . number_format($total, 2); ?></a></div>
                  <div class="col-4-12 _3Yi3bU">
                  	 <?php if($row1['order_trackment']==0) { ?>
                     <div>
                        <div class="_30ud5x _3ELbo9"></div>
                        <span class="_7BRRQk">
                        	
                            Order Placed on <?php echo date('d M Y',$row1['sale_datetime']); ?> 
                            
                        </span>
                     </div>
                     <?php } ?>
                      <?php if($row1['order_trackment']==1) {
						  ?>
                      <div>
                        <div class="_30ud5x _3C8AL-"></div>
                        <span class="_7BRRQk"  style="display:none;">
                        	
                            Return <?php if($row1['return_status']==1) { echo "Request"; } elseif($row1['return_status']==2) { echo "Acccepted"; }  elseif($row1['return_status']==3) { echo "Reject"; } ?>
                            
                        </span>
                        
                        <!--<div class="_2t-3dH">As per your request, your item has been <?php if($row1['return_status']==1) { echo "Request"; } elseif($row1['return_status']==2) { echo "Acccepted"; }  elseif($row1['return_status']==3) { echo "Rejected"; } ?></div>-->
                        <?php /*if($row1['review']==0) { 
							
						?>
                        <div class="_1S3Y5S row"><a href="<?php echo base_url(); ?>index.php/home/reviews/<?php echo $row1['sale_id']; ?>/<?php echo $prd['id']; ?>">Rate &amp; Review Product</a></span></div>
                        <?php } else  { ?>
                        	<div class="_1S3Y5S row"> Already Reviewed By You</div>
                        <?php } */?>
                     </div>
                     <?php } ?>
                     <?php if($row1['cancel_status']==1) { ?>
                      <div>
                        <div class="_30ud5x VGlMMD"></div>
                        <span class="_7BRRQk">
                        	
                            Cancelled
                            
                        </span>
                        <!--<div class="_2t-3dH">As per your request, your item has been cancelled</div>-->
                     </div>
                     <?php } ?>
                     <?php if($row1['order_trackment']==3) { ?>
                      <div>
                        <div class="_30ud5x _3ELbo9"></div>
                        <span class="_7BRRQk">
                        	
                            Order Deliverd on <?php echo date('d M Y',$row1['delivary_datetime']); ?> 
                            
                        </span>
                        <!--<div class="_2t-3dH">Your item has been delivered</div>-->
                     </div>
                     <!--<div class="paz6BF"  style="display:none;"><span class="_1ONp6J _1Wn3y5">Return policy valid till <?php echo date('d M Y',$row1['delivary_datetime']); ?></span></div>-->
                     
                      <?php /*if($row1['review']==0) { ?>
                        <div class="_1S3Y5S row"><a href="<?php echo base_url(); ?>index.php/home/reviews/<?php echo $row1['sale_id']; ?>/<?php echo $prd['id']; ?>">Rate &amp; Review Product</a></span></div>
                        <?php } else  { ?>
                        	<div class="_1S3Y5S row"> Already Reviewed By You</div>
                        <?php } */?>
                     <?php } ?>
                     <?php if($row1['order_trackment']==4) { ?>
                      <div>
                        <div class="_30ud5x VGlMMD"></div>
                        <span class="_7BRRQk">
                        	
                            Cancelled
                            
                        </span>
                        <!--<div class="_2t-3dH"> your item has been cancelled by admin</div>-->
                     </div>
                     <?php } ?>
                      <?php if($row1['order_trackment']==5) { ?>
                      <div>
                        <div class="_30ud5x VGlMMD"></div>
                        <span class="_7BRRQk">
                        	
                            Cancelled
                            
                        </span>
                        <!--<div class="_2t-3dH"> your item has been cancelled by vendor</div>-->
                     </div>
                     <?php } ?>
                      <?php if($row1['order_trackment']==6) { ?>
                     <div>
                        <div class="_30ud5x _3ELbo9"></div>
                        <span class="_7BRRQk">
                        	
                            Order Shipped on <?php echo date('d M Y',$row1['sale_datetime']); ?> 
                            
                        </span>
                     </div>
                     <?php } ?>
                     
                  </div>
               </div>
            </div>

                <?php 
	}
	}
?>
             </div>
                                             



<div class="text-center" style="display:none;" >
	<div id="pagenation_set_links" ><?php echo $this->ajax_pagination->create_links(); ?></div>
</div>
<!--/end pagination-->


<script>
	$(document).ready(function(){ 
		$('.pagination_box').html($('#pagenation_set_links').html());
	});
</script>
<style>

</style>


<?php /*?><?php 
	$i = 0;
	foreach ($orders as $row1) {
		$i++;
?>
	<tr>
		<td class="image">
			<?php echo $i; ?>
		</td>
		<td class="quantity">
			<?php echo date('d M Y',$row1['sale_datetime']); ?>
		</td>
		<td class="description">
			<?php echo currency($row1['grand_total']); ?>
		</td>
		<td class="order-id">
			<?php 
				$payment_status = json_decode($row1['payment_status'],true); 
				foreach ($payment_status as $dev) {
			?>

			<span class="label label-<?php if($dev['status'] == 'paid'){ ?>success<?php } else { ?>danger<?php } ?>" style="margin:2px;">
			<?php
					if(isset($dev['vendor'])){
						echo $this->crud_model->get_type_name_by_id('vendor', $dev['vendor'], 'display_name').' ('.translate('vendor').') : '.$dev['status'];
					} else if(isset($dev['admin'])) {
						echo translate('admin').' : '.$dev['status'];
					}
			?>
			</span>
			<br>
			<?php
				}
			?>
		</td>
		<td class="order-id">
			<?php 
				$delivery_status = json_decode($row1['delivery_status'],true); 
				foreach ($delivery_status as $dev) {
			?>

			<span class="label label-<?php if($dev['status'] == 'delivered'){ ?>success<?php } else { ?>danger<?php } ?>" style="margin:2px;">
			<?php
					if(isset($dev['vendor'])){
						echo $this->crud_model->get_type_name_by_id('vendor', $dev['vendor'], 'display_name').' ('.translate('vendor').') : '.$dev['status'];
					} else if(isset($dev['admin'])) {
						echo translate('admin').' : '.$dev['status'];
					}
			?>
			</span>
			<br>
			<?php
				}
			?>
		</td>
		<td class="add">
			<a class="btn btn-theme btn-theme-xs" href="<?php echo base_url(); ?>index.php/home/invoice/<?php echo $row1['sale_id']; ?>"><?php echo translate('invoice');?></a>
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
</script><?php */?>


