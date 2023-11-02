<?php
   
   $sale_details = $this->db->get_where('sale',array('sale_id'=>$sale_id))->result_array();
   $orders_details  = $this->db->get_where('sale',array('order_id'=>$order_id,'sale_id!='=>$sale_id))->result_array();
  
  
   
   ?>
<div class="_2kIypH" style="background: #f1f3f6;">
   <div class="_2QEGRr">
      <div class="_2rhtPx">
         <div class="_1qzOXJ"></div>
         <?php  foreach($sale_details as $row) {
			$info = json_decode($row['shipping_address'],true); 
			$product_details = json_decode($row['product_details'], true);
			?>
         <div class="_1GRhLX _17XScb _3qesVJ row">
            <div class="col-4-12 _1MoCT-">
               <div class="_15sywe">
                  <div class="_2Zi11O"><span class="S19oOu">Delivery Address</span></div>
                  <div class="_3OsVcL">
                     <div class="_1TPQHf">
                        <div class="_1MbX3l"><?php echo $info['firstname']; ?> <?php echo $info['lastname']; ?> </div>
                     </div>
                     <div class="wRBMLW"><?php echo $info['email']; ?></div>
                     <div class="_3N_1fR"><?php echo $info['address1']; ?>,<?php echo $info['address2']; ?><?php echo $info['zip']; ?></div>
                     <div class="_1TPQHf _1GAUB_">
                        <div>
                           <span class="_1MbX3l">Phone number</span>
                           <div class="_21vjgA"><?php echo $info['phone']; ?></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-4-12 _1sgB7C _1MoCT-">
            </div>
            <div class="col-4-12 _3FXQ6P _1MoCT-">
               <span class="_1CWg6K">More actions</span>
               <div class="row _3xbQnf">
                  <div class="_3PNaZO"><img src="//img1a.flixcart.com/www/linchpin/fk-cp-zion/img/downloadInvoice_686685.png" class="_2Q-6ef"><span class="_3skYqB">Invoice</span></div>
                  <a href="<?php echo base_url(); ?>index.php/home/invoice/total/<?php echo $order_id; ?>" class="_2AkmmA _1qifsA _3c5Gic"><span>View</span></a>
               </div>
            </div>
         </div>
         <?php foreach ($product_details as $row1) {
			 	$tax = $row1['tax'];
				$shipping = $row1['shipping'];
				$subtotal = $row1['subtotal'];
				$total = $tax+$shipping+$subtotal;
			  ?>
         <div class="_1GRhLX _1YeuTr">
            <div class="_3WXnMt">
               <div class="row">
                  <div class="col-3-12 _22pgKz">
                     <div class="row" style="position: relative;">
                        <div class="col-4-12 _2l-734">
                           <a href="javascript:void(0);">
                              <div class="_3BTv9X" style="height: 75px; width: 75px;"><img class="_1Nyybr _30XEf0" alt="" src="<?php echo $row1['image']; ?>"></div>
                           </a>
                        </div>
                        <div class="col-8-12">
                           <a class="_2AkmmA row NPoy5u" href="javascript:void(0);"><?php echo $row1['name']; ?></a>
                           <div class="row _3Vj7el" style="display:none;"><span class="_3PqwaQ"><span class="_3Vj7el">Color: </span><span class="_14N9bh">Pink</span></span><span class="_3PqwaQ"><span class="_3Vj7el">Size: </span><span class="_14N9bh">L</span></span><span class="_3PqwaQ"></span></div>
                           <div class="row _3Vj7el"><span class="_3Vj7el _3cWeIX">Seller: </span><span class="_14N9bh"><?php echo $this->crud_model->product_by($row1['id'],'with_link');?></span></div>
                           <div class="_3nqhTk">
                              <div class="_13fZDJ">
                                 <div class=""><?php echo currency($total);?></div>
                                 
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-5-12 _3ncsB7">
                     
                  </div>
                  <div class="_2HvExN col-4-12">
                      
                     <div>
                        <div class="_30ud5x _3ELbo9"></div>
                        <span class="_7BRRQk">
                        	
                            Order Placed on <?php echo date('d M Y',$row['sale_datetime']); ?> 
                            
                        </span>
                     </div>
                     <?php if($row['status']!='success' && $row['status']!='rejected') { ?>
                     <div class="paz6BF">
                        <div class="_1S3Y5S row"><a href="<?php echo base_url(); ?>index.php/home/cancel_details/<?php echo $row['sale_id']; ?>/<?php echo $row1['id']; ?>">Cancel Order</a></span></div>
                     </div>
                     <?php } ?>
                      <?php if($row['order_trackment']==1) { ?>
                      <div>
                        <div class="_30ud5x _3C8AL-"></div>
                        <span class="_7BRRQk">
                        	
                             Return <?php if($row['return_status']==1) { echo "Request"; } elseif($row['return_status']==2) { echo "Acccepted"; }  elseif($row['return_status']==3) { echo "Reject"; } ?>
                            
                        </span>
                        <div class="_2t-3dH">As per your request, your item has been  <?php if($row['return_status']==1) { echo "Request"; } elseif($row['return_status']==2) { echo "Acccepted"; }  elseif($row['return_status']==3) { echo "Reject"; } ?></div>
                        <?php if($row['return_status']==3) { ?>
                         <div class="paz6BF">
                        <div class="_1S3Y5S row"><a href="<?php echo base_url(); ?>index.php/home/return_details/<?php echo $row['sale_id']; ?>/<?php echo $row1['id']; ?>">Return</a></div>
                     </div>
                     <?php } ?>
                        <?php if($row1['review']==0) { ?>
                        <div class="_1S3Y5S row"><a href="<?php echo base_url(); ?>index.php/home/reviews/<?php echo $row['sale_id']; ?>/<?php echo $row1['id']; ?>">Rate &amp; Review Product</a></span></div>
                        <?php } else  { ?>
                        	<div class="_1S3Y5S row"> Already Reviewed By You</div>
                        <?php } ?>
                     </div>
                     <?php } ?>
                     <?php if($row['order_trackment']==2) { ?>
                      <div>
                        <div class="_30ud5x VGlMMD"></div>
                        <span class="_7BRRQk">
                        	
                            Cancelled
                            
                        </span>
                        <div class="_2t-3dH">As per your request, your item has been cancelled</div>
                     </div>
                     <?php } ?>
                     <?php if($row['order_trackment']==3) { ?>
                      <div>
                        <div class="_30ud5x _3ELbo9"></div>
                        <span class="_7BRRQk">
                        	
                            Order Deliverd on <?php echo date('d M Y',$row['delivary_datetime']); ?> 
                            
                        </span>
                        <div class="_2t-3dH">Your item has been delivered</div>
                     </div>
                    
                     <div class="paz6BF">
                        <div class="_1S3Y5S row"><a href="<?php echo base_url(); ?>index.php/home/return_details/<?php echo $row['sale_id']; ?>/<?php echo $row1['id']; ?>">Return</a></div>
                     </div>
                     
                     <div class="paz6BF">
                        <?php if($row['review']==0) { ?>
                        <div class="_1S3Y5S row"><a href="<?php echo base_url(); ?>index.php/home/reviews/<?php echo $row['sale_id']; ?>/<?php echo $row1['id']; ?>">Rate &amp; Review Product</a></span></div>
                        <?php } else  { ?>
                        	<div class="_1S3Y5S row"> Already Reviewed By You</div>
                        <?php } ?>
                     </div>
                     <?php } ?>
                     <?php if($row['order_trackment']==4) { ?>
                      <div>
                        <div class="_30ud5x VGlMMD"></div>
                        <span class="_7BRRQk">
                        	
                            Cancelled
                            
                        </span>
                        <div class="_2t-3dH"> your item has been cancelled by admin</div>
                     </div>
                     <?php } ?>
                      <?php if($row['order_trackment']==5) { ?>
                      <div>
                        <div class="_30ud5x VGlMMD"></div>
                        <span class="_7BRRQk">
                        	
                            Cancelled
                            
                        </span>
                        <div class="_2t-3dH"> your item has been cancelled by vendor</div>
                     </div>
                     <?php } ?>
                      <?php if($row['order_trackment']==6) { ?>
                     <div>
                        <div class="_30ud5x _3ELbo9"></div>
                        <span class="_7BRRQk">
                        	
                            Order Shipped on <?php echo date('d M Y',$row['sale_datetime']); ?> 
                            
                        </span>
                     </div>
                     <?php } ?>
                  </div>
               </div>
                <div class="_3Qc25m"  style="display:none;"><div class="_3pPqLM qpOh1B">Return policy valid till <?php echo date('d M Y',$row['delivary_datetime']); ?></div><a class="fac1Fm" href="javascript:void(0);">Know more</a></div>
               <div class="_3YJBAw"></div>
            </div>
         </div>
         <?php } } ?>
         <?php if(count($orders_details)>0) { ?>
         <div class="_1GRhLX">
            <div class="_2rVMf-"><span>Other items in this order</span></div>
           	 <?php foreach ($orders_details as $od) {
				 $product_details = json_decode($od['product_details'], true);
				 foreach ($product_details as $row1) {
			 	$tax = $row1['tax'];
				$shipping = $row1['shipping'];
				$subtotal = $row1['subtotal'];
				$total = $tax+$shipping+$subtotal;
			  ?>
            
             <div class="_3WXnMt">
               <div class="row">
               	   
                  <div class="col-3-12 _22pgKz">
                   <a href="<?php echo base_url(); ?>index.php/home/invoice_details/<?php echo $od['order_id']; ?>/<?php echo $od['sale_id']; ?>">
                     <div class="row" style="position: relative;">
                        <div class="col-4-12 _2l-734">
                           
                              <div class="_3BTv9X" style="height: 75px; width: 75px;"><img class="_1Nyybr _30XEf0" alt="" src="<?php echo $row1['image']; ?>"></div>
                           
                        </div>
                        <div class="col-8-12">
                           <p class="_2AkmmA row NPoy5u" href="javascript:void(0);"><?php echo $row1['name']; ?></p>
                           <div class="row _3Vj7el" style="display:none;"><span class="_3PqwaQ"><span class="_3Vj7el">Color: </span><span class="_14N9bh">Pink</span></span><span class="_3PqwaQ"><span class="_3Vj7el">Size: </span><span class="_14N9bh">L</span></span><span class="_3PqwaQ"></span></div>
                           <div class="row _3Vj7el"><span class="_3Vj7el _3cWeIX">Seller: </span><span class="_14N9bh"><?php echo $this->crud_model->product_by($row1['id']);?></span></div>
                           <div class="_3nqhTk">
                              <div class="_13fZDJ">
                                 <div class=""><?php echo currency($total);?></div>
                                 
                              </div>
                           </div>
                        </div>
                     </div>
                   </a>
                  </div>
                  <div class="col-5-12 _3ncsB7">
                     
                  </div>
                 
                  <div class="_2HvExN col-4-12">
                     <?php if($od['order_trackment']==0) { ?>
                     <div>
                        <div class="_30ud5x _3ELbo9"></div>
                        <span class="_7BRRQk">
                        	
                            Order Placed on <?php echo date('d M Y',$od['sale_datetime']); ?> 
                            
                        </span>
                     </div>
                     <div class="paz6BF"  style="display:none;">
                        <div class="_1S3Y5S row"><a href="<?php echo base_url(); ?>index.php/home/cancel_details/<?php echo $row['sale_id']; ?>/<?php echo $row1['id']; ?>">Cancel Order</a></span></div>
                     </div>
                     <?php } ?>
                      <?php if($od['order_trackment']==1) { ?>
                      <div>
                        <div class="_30ud5x _3C8AL-"></div>
                        <span class="_7BRRQk">
                        	
                             Return <?php if($od['return_status']==1) { echo "Request"; } elseif($od['return_status']==2) { echo "Acccepted"; }  elseif($od['return_status']==3) { echo "Reject"; } ?>
                            
                        </span>
                        <div class="_2t-3dH">As per your request, your item has been  <?php if($od['return_status']==1) { echo "Request"; } elseif($od['return_status']==2) { echo "Acccepted"; }  elseif($od['return_status']==3) { echo "Reject"; } ?></div>
                         <?php if($od['return_status']==3) { ?>
                         <div class="paz6BF">
                        <div class="_1S3Y5S row"><a href="<?php echo base_url(); ?>index.php/home/return_details/<?php echo $row['sale_id']; ?>/<?php echo $row1['id']; ?>">Return</a></div>
                     </div>
                     <?php } ?>
                        <?php if($row1['review']==0) { ?>
                        <div class="_1S3Y5S row"><a href="<?php echo base_url(); ?>index.php/home/reviews/<?php echo $od['sale_id']; ?>/<?php echo $row1['id']; ?>">Rate &amp; Review Product</a></span></div>
                        <?php } else  { ?>
                        	<div class="_1S3Y5S row"> Already Reviewed By You</div>
                        <?php } ?>
                     </div>
                     <?php } ?>
                     <?php if($od['order_trackment']==2) { ?>
                      <div>
                        <div class="_30ud5x VGlMMD"></div>
                        <span class="_7BRRQk">
                        	
                            Cancelled
                            
                        </span>
                        <div class="_2t-3dH">As per your request, your item has been cancelled</div>
                     </div>
                     <?php } ?>
                     <?php if($od['order_trackment']==3) { ?>
                      <div>
                        <div class="_30ud5x _3ELbo9"></div>
                        <span class="_7BRRQk">
                        	
                            Order Deliverd on <?php echo date('d M Y',$od['delivary_datetime']); ?> 
                            
                        </span>
                        <div class="_2t-3dH">Your item has been delivered</div>
                     </div>
                     <div class="paz6BF"  style="display:none;"><span class="_1ONp6J _1Wn3y5">Return policy valid till <?php echo date('d M Y',$od['delivary_datetime']); ?></span></div>
                      <div class="paz6BF">
                        <div class="_1S3Y5S row"><a href="<?php echo base_url(); ?>index.php/home/return_details/<?php echo $od['sale_id']; ?>/<?php echo $row1['id']; ?>">Return</a></span></div>
                     </div>
                      <?php if($row['review']==0) { ?>
                        <div class="_1S3Y5S row"><a href="<?php echo base_url(); ?>index.php/home/reviews/<?php echo $row['sale_id']; ?>/<?php echo $row1['id']; ?>">Rate &amp; Review Product</a></span></div>
                        <?php } else  { ?>
                        	<div class="_1S3Y5S row"> Already Reviewed By You</div>
                        <?php } ?>
                     
                     <?php } ?>
                     <?php if($od['order_trackment']==4) { ?>
                      <div>
                        <div class="_30ud5x VGlMMD"></div>
                        <span class="_7BRRQk">
                        	
                            Cancelled
                            
                        </span>
                        <div class="_2t-3dH"> your item has been cancelled by admin</div>
                     </div>
                     <?php } ?>
                      <?php if($od['order_trackment']==5) { ?>
                      <div>
                        <div class="_30ud5x VGlMMD"></div>
                        <span class="_7BRRQk">
                        	
                            Cancelled
                            
                        </span>
                        <div class="_2t-3dH"> your item has been cancelled by vendor</div>
                     </div>
                     <?php } ?>
                      <?php if($od['order_trackment']==6) { ?>
                     <div>
                        <div class="_30ud5x _3ELbo9"></div>
                        <span class="_7BRRQk">
                        	
                            Order Shipped on <?php echo date('d M Y',$od['sale_datetime']); ?> 
                            
                        </span>
                     </div>
                     <?php } ?>
                  </div>
               </div>
               
               
               <div class="_3Qc25m"  style="display:none;"><div class="_3pPqLM qpOh1B">Return policy valid till <?php echo date('d M Y',$row['delivary_datetime']); ?></div><a class="fac1Fm" href="javascript:void(0);">Know more</a></div>
               <div class="_3YJBAw"></div>
            </div>
            
          		<?php } } ?>
            
         </div>
         <?php } ?>
      </div>
   </div>
</div>
