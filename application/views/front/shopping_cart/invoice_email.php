
<!--=== Breadcrumbs ===-->
    <div style="padding:10px;background:rgba(212, 224, 212, 0.72)">
        <center>
            <h1 class="text-center; "><?php echo translate('invoice_paper');?></h1>
        </center><!--/container-->
    </div><!--/breadcrumbs-->
    <!--=== End Breadcrumbs ===-->

    <!--=== Content Part ===-->
    <table width="100%" style="background:rgba(212, 224, 212, 0.17);">
    <?php
        $sale_details = $this->db->get_where('sale',array('order_id'=>$sale_id))->result_array();
		$sale_details1=$sale_details[0];
	//	print_r($sale_details1); exit;
        ?>
        <!--Invoice Header-->
        <tr>
            <td style="padding:10px;">
                <img src="<?php echo $this->crud_model->logo('home_top_logo'); ?>" alt="" width="60%">
            </td>
            <td>
                <table>
                    <tr><td><strong><?php echo translate('invoice_no');?></strong> : <?php echo $sale_details1['order_id']; ?> </td></tr>
                    <tr><td><strong><?php echo translate('date');?></strong> : <?php echo date('d M, Y',$sale_details1['sale_datetime'] );?></td></tr>
                </table>
            </td>
        </tr>
        <!--End Invoice Header-->

        <!--Invoice Detials-->
        <tr>
            <td style="padding:20px;">
                <div class="tag-box tag-box-v3">
                    <?php
                        $info = json_decode($sale_details1['shipping_address'],true);
                    ?>
                    <h2><?php echo translate('client_information:');?></h2>
                    <table>
                        <tr><td><strong><?php echo translate('first_name:');?></strong> <?php echo $info['firstname']; ?></td></tr>
                        <tr><td><strong><?php echo translate('last_name:');?></strong> <?php echo $info['lastname']; ?></td></tr>
                      <tr><td> <strong><?php echo translate('address1:');?></strong> <?php echo $info['address1']; ?> <?php echo $info['address2']; ?> </td></tr>
									
                              <tr><td> <strong>      <?php echo translate('zip');?> </strong> <?php echo $info['zip']; ?> </td></tr>
                                  <tr><td> <strong>  <?php echo translate('phone');?> : <?php echo $info['phone']; ?> </td></tr>
                                  <tr><td> <strong>  <?php echo translate('e-mail');?> : <a href=""><?php echo $info['email']; ?></a> </td></tr>
                    </table>
                </div>        
            </td>
            <td>
                <div class="tag-box tag-box-v3">
                    <h2><?php echo translate('payment_details_:');?></h2>  
                    <table>       
                        <tr style="display:none;"><td><strong><?php echo translate('payment_status_:');?></strong> <i><?php echo translate($this->crud_model->sale_payment_status($sale_details1['order_id'])); ?></i></td></tr>
                        <tr><td><strong><?php echo translate('payment_method_:');?></strong> <?php echo ucfirst(str_replace('_', ' ', $sale_details1['payment_type'])); ?></td></tr>  
                        <?php if($sale_details1['order_type']=='pickup'){ ?><tr><td><strong><?php echo translate('Pickup Order Info:');?></strong> <?php echo  "Date :".date('d M, Y',$sale_details1['pickup_date']); ?> <?php echo "Time :".$sale_details1['pickup_slot']; ?></td></tr>  <?php } ?>
                    </table>
                </div>
            </td>
        </tr>
      
        <!--End Invoice Detials-->

        <!--Invoice Table-->
        <tr>
            <td style="padding:10px 5px 0px; background:purple; color:white; text-align:center;" colspan="2" >
                <h3><?php echo translate('payment_invoice');?></h3>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding:0px;">
            <table width="100%">
                <thead>
                   
                    <tr>
                                            <td class="text-left"><strong><?php echo translate('Sno');?></strong></td>
                                            <td class="text-left"><strong><?php echo translate('item');?></strong></td>
                                            <td class="text-center"><strong><?php echo translate('options');?></strong></td>
                                            
                                            <td class="text-right"><strong><?php echo translate('unit_cost');?></strong></td>
                                            <td class="text-right"><strong><?php echo translate('VAT');?></strong></td>
                                             <td class="text-right"><strong><?php echo translate('shipping');?></strong></td>
                                            <td class="text-right"><strong><?php echo translate('total');?></strong></td>
                                        </tr>
                    
                </thead>
                <tbody>
                    <?php
					 $i =1;
                        $total = 0;
					foreach($sale_details as $row) {
                        $product_details = json_decode($row['product_details'], true);
                       
                        foreach ($product_details as $row1) {
							
												$tax += $row1['tax'];
												$shipping += $row1['shipping'];
												$subtotal += $row1['subtotal'];
												$total = $tax+$shipping+$subtotal;
                            
                    ?>
                        <tr>
                            <td style="padding: 5px;text-align:center;background:rgba(128, 128, 128, 0.18)"><?php echo $i; ?></td>
                            <td style="padding: 5px;text-align:center;background:rgba(128, 128, 128, 0.18)"><?php echo $row1['name']; ?> <br />
                                                 Qty:<?php echo $row1['qty']; ?></td>
                            <td style="padding: 5px;text-align:center;background:rgba(128, 128, 128, 0.18)"><?php 
												$option = json_decode($row1['option'],true);
												//print_r($option);
												foreach ($option as $l => $op) {
													if($l !== 'color' && $op['value'] !== '' && $op['value'] !== NULL){
											?>
												<?php echo $op['title'] ?> : 
												<?php 
													if(is_array($va = $op['value'])){ 
														echo $va = join(', ',$va); 
													} else {
														echo $va;
													}
												?>
												<br>
											<?php
													}
												} 
											?></td>
                            <td style="padding: 5px;text-align:center;background:rgba(128, 128, 128, 0.18)"><?php echo currency($row1['price']); ?></td>
                            <td style="padding: 5px;text-align:center;background:rgba(128, 128, 128, 0.18)"><?php echo currency($row1['tax']); ?></td>
                            <td style="padding: 5px;text-align:right;background:rgba(128, 128, 128, 0.18)"><?php echo currency($row1['shipping']); ?></td>
                            <td style="padding: 5px;text-align:right;background:rgba(128, 128, 128, 0.18)"><?php echo currency($row1['subtotal']); 
													
												?></td>
                        </tr>
                    <?php
							
                        }
						$i++;	
					}
					?>
                </tbody>
            </table>
            <td>
        </tr>
        <!--End Invoice Table-->

        <!--Invoice Footer-->
        <tr>
            <td width="50%" style="background:rgba(212, 224, 212, 0.72)">
                 <table>
                    <tr >
                        <td style="padding:10px 20px;"><h2><?php echo translate('address');?></h2></td>
                    </tr>
                    <tr>
                        <td style="padding:3px 20px;">
                            <?php echo $info['address1']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:3px 20px;">
                            <?php echo $info['address2']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:3px 20px;">
                            <?php echo translate('zip');?> : <?php echo $info['zip']; ?> 
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:3px 20px;">
                            <?php echo translate('phone');?> : <?php echo $info['phone']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:3px 20px;">
                            <?php echo translate('e-mail');?> : <?php echo $info['email']; ?>
                        </td>    
                    </tr> 
                 </table> 
            </td>
            <td style="text-align:right;">
                 <table width="100%">
                    <tr>
                        <td style="text-align:right;padding:3px; width:80%; "><h3><?php echo translate('sub_total_amount');?> :</h3></td>
                        <td style="text-align:right;padding:3px"><h3><?php echo currency().$subtotal;?></h3></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;padding:3px; width:80%;"><h3><?php echo translate('tax');?> :</h3></td>
                        <td style="text-align:right;padding:3px"><h3><?php echo currency().$tax;?></h3></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;padding:3px; width:80%;"><h3><?php echo translate('shipping');?> :</h3></td>
                        <td style="text-align:right;padding:3px"><h3><?php echo currency().$shipping;?></h3></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;padding:3px; width:80%;"><h2><?php echo translate('grand_total');?> :</h2></td>
                        <td style="text-align:right;padding:3px"><h2><?php echo currency().$total;?></h2></td>
                    </tr>
                 </table>
               
            </td>
        </tr>
    
    </table><!--/container-->     
    <!--=== End Content Part ===-->
    <h4>
        ** You can download purchased (fully paid) digital products form your profile.
    </h4>