<section class="page-section invoice">
    <div class="container">
    	<?php
			if(isset($sale_id)) {
			$sale_details = $this->db->get_where('sale',array('sale_id'=>$sale_id))->result_array();
			foreach($sale_details as $row){
		?>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="invoice_body">
                    <div class="invoice-title">
                        <div class="invoice_logo hidden-xs">
                        	<?php
								$home_top_logo = $this->db->get_where('ui_settings',array('type' => 'home_top_logo'))->row()->value;
							?>
							<img src="<?php echo base_url(); ?>uploads/logo_image/logo_<?php echo $home_top_logo; ?>.png" alt="logo" width="200"/>
                        </div>
                        <div class="invoice_info">
                            <p><b><?php echo translate('invoice'); ?> # :</b><?php echo $row['sale_code']; ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <address>
                                <strong>
                                    <h4>
                                        <?php echo translate('billed_to'); ?> :
                                    </h4>
                                </strong>
                                <?php
									$info = json_decode($row['shipping_address'],true);
								?>
                                <p>
                                    <b><?php echo translate('first_name'); ?> :</b>
                                    <?php echo $info['firstname']; ?>
                                </p>
                                <p>
                                    <b><?php echo translate('last_name'); ?> :</b>
                                    <?php echo $info['lastname']; ?>
                                </p>
                                <p>
                                    <b><?php echo translate('address'); ?> :</b>
                                    <br>
                                    <?php echo $info['address1']; ?> <br>
									<?php echo $info['address2']; ?> <br>
                                    <?php echo translate('zip');?> : <?php echo $info['zip']; ?> <br>
                                    <?php echo translate('phone');?> : <?php echo $info['phone']; ?> <br>
                                    <?php echo translate('e-mail');?> : <a href=""><?php echo $info['email']; ?></a>
                                </p>
                                <?php if($this->crud_model->get_type_name_by_id('general_settings','108','value')=='ok') { ?>
                                <p>
                                    <b><?php echo translate('Delivery slot'); ?> :</b>
                                    <?php  $order_details[0]['pickup_slot'];
                                    $slot=$this->db->get_where('del_slot_time',array('del_slot_time_id'=>$order_details[0]['pickup_slot']))->result_array();
                                    
                                
                                    $slot_date=$this->db->get_where('del_slot',array('del_slot_id'=>$slot[0]['del_slot_id']))->result_array();
                                  
                                    echo date('g:i a',strtotime($slot_date[0]['f_time'])).'-'.date('g:i a',strtotime($slot_date[0]['t_time'])); ?> 
                                    &nbsp;

                                    <?php
                                      echo date('Y-m-d',$slot_date[0]['f_date']);
                                    ?>
                                </p> <?php } ?>
                            </address>
                        </div>
                        
                        <div class="col-md-6 col-sm-6 col-xs-6 hidden-xs text-right">
                            <address>
                                <strong>
                                    <h4>
                                        <?php echo translate('shipped_to'); ?> :
                                    </h4>
                                </strong>
                                <p>
                                    <b><?php echo translate('first_name'); ?> :</b>
                                    <?php echo $info['firstname']; ?>
                                </p>
                                <p>
                                    <b><?php echo translate('last_name'); ?> :</b>
                                    <?php echo $info['lastname']; ?>
                                </p>
                                <p>
                                    <b><?php echo translate('address'); ?> :</b>
                                    <br>
                                    <?php echo $info['address1']; ?> <br>
									<?php echo $info['address2']; ?> <br>
                                    <?php echo translate('zip');?> : <?php echo $info['zip']; ?> <br>
                                    <?php echo translate('phone');?> : <?php echo $info['phone']; ?> <br>
                                    <?php echo translate('e-mail');?> : <a href=""><?php echo $info['email']; ?></a>
                                </p>
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6 ">
                            <address>
                                <strong>
                                    <h4>
                                        <?php echo translate('payment_details'); ?> :
                                    </h4>
                                </strong>
                                <p>
                                    <b><?php echo translate('payment_status'); ?> :</b>
                                    <i><?php echo translate($this->crud_model->sale_payment_status($row['sale_id'])); ?></i>
                                </p>
                                <p>
                                    <b><?php echo translate('payment_method'); ?> :</b>
                                    <?php if($info['payment_type'] == 'c2'){
                                        echo 'TwoCheckout';
                                    }else{
                                        echo ucfirst(str_replace('_', ' ', $info['payment_type'])); 
                                    }?>
                                </p>
                            </address>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6  text-right">
                            <address>
                                <strong>
                                    <h4>
                                        <?php echo translate('order_date'); ?> :
                                    </h4>
                                    <p>
                                        <?php echo date('d M, Y',$row['sale_datetime'] );?>
                                    </p>
                                </strong>
                            </address>
                        </div>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong><?php echo translate('payment_invoice');?></strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                             <td class="text-center"><strong><?php echo translate('image');?></strong></td>
                                            <td class="text-center"><strong><?php echo translate('item');?></strong></td>
                                            <td class="text-center"><strong><?php echo translate('options');?></strong></td>
                                            
                                            <td class="text-right"><strong><?php echo translate('unit_cost');?></strong></td>
                                            <td class="text-right"><strong><?php echo translate('VAT');?></strong></td>
                                             <td class="text-right"><strong><?php echo translate('shipping');?></strong></td>
                                            <td class="text-right"><strong><?php echo translate('total');?></strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
											
											$product_details = json_decode($row['product_details'], true);
											
											$total = 0;
											foreach ($product_details as $row1) {
												$tax = $row1['tax'];
												$shipping = $row1['shipping'];
												$subtotal = $row1['subtotal'];
												$total = $tax+$shipping+$subtotal;
												
												
										?>
                                        <tr>
                                           	<td class="text-center"><img src="<?php echo $row1['image']; ?>" alt="" width="80" height="80"/></td>
                                            <td class="text-center"><?php echo $row1['name']; ?><br />
                                            	 Seller:<?php echo $this->crud_model->product_by($row1['id'],'with_link');?><br />
                                                 Qty:<?php echo $row1['qty']; ?>
                                            </td>
                                            <td class="text-center">
                                            <?php 
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
											?>
                                            </td>
                                            <td class="text-right">
												<?php echo currency($row1['price']); ?>
                                            </td>
                                            <td class="text-right"><?php echo currency($row1['tax']); ?></td>
                                            <td class="text-right"><?php echo currency($row1['shipping']); ?></td>
                                            <td class="text-right">
												<?php echo currency($row1['price']+$row1['tax']+$row1['shipping']); 
													
												?>
                                            </td>
                                        </tr>
                                        <?php
											}
										
										?>
                                        <tr>
                                        	<td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                             <td class="thick-line"></td>
                                            <td class="thick-line text-right">
                                            	<strong>
                                            		<?php echo translate('sub_total_amount');?> :
                                                </strong>
                                            </td>
                                            <td class="thick-line text-right">
                                            	<?php echo currency($subtotal);?>
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-right">
                                            	<strong>
                                            		<?php echo translate('tax');?> :
                                                </strong>
                                            </td>
                                            <td class="no-line text-right">
                                            	<?php echo currency($tax);?>
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-right">
                                            	<strong>
                                            		<?php echo translate('shipping');?> :
                                                </strong>
                                            </td>
                                            <td class="no-line text-right">
                                            	<?php echo currency($shipping);?>
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                             <td class="no-line"></td>
                                            <td class="no-line text-right">
                                            	<strong>
                                            		<?php echo translate('grand_total');?> :
                                                </strong>
                                            </td>
                                            <td class="no-line text-right">
                                            	<?php echo currency($total);?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10 col-md-offset-1 btn_print hidden-xs" style="margin-top:10px;">
            	<span class="btn btn-info pull-right" onClick="print_invoice()">
					<?php echo translate('print'); ?>
               	</span>
            </div>
        </div>
        <?php
			}
			}
		?>
        <?php
			if(isset($order_id)) {
			    
			$order_details = $this->db->get_where('sale',array('order_id'=>$order_id))->result_array();
			
				
		?>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="invoice_body">
                    <div class="invoice-title">
                        <div class="invoice_logo hidden-xs">
                        	<?php
								$home_top_logo = $this->db->get_where('ui_settings',array('type' => 'home_top_logo'))->row()->value;
							?>
							<img src="<?php echo base_url(); ?>uploads/logo_image/logo_<?php echo $home_top_logo; ?>.png" alt="logo" width="200"/>
                        </div>
                        <div class="invoice_info">
                            <p><b><?php echo translate('order_id'); ?> :</b><?php echo $order_id; ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <address>
                                <strong>
                                    <h4>
                                        <?php echo translate('billed_to'); ?> :
                                    </h4>
                                </strong>
                                <?php
									$info = json_decode($order_details[0]['shipping_address'],true);
								?>
                                <p>
                                    <b><?php echo translate('first_name'); ?> :</b>
                                    <?php echo $info['firstname']; ?>
                                </p>
                                <p>
                                    <b><?php echo translate('last_name'); ?> :</b>
                                    <?php echo $info['lastname']; ?>
                                </p>
                                <p>
                                    <b><?php echo translate('address'); ?> :</b>
                                    <br>
                                    <?php echo $info['address1']; ?> <br>
									<?php echo $info['address2']; ?> <br>
                                    <?php echo translate('zip');?> : <?php echo $info['zip']; ?> <br>
                                    <?php echo translate('phone');?> : <?php echo $info['phone']; ?> <br>
                                    <?php echo translate('e-mail');?> : <a href=""><?php echo $info['email']; ?></a>
                                </p>
                            </address>
                        </div>
                        
                        <div class="col-md-6 col-sm-6 col-xs-6 hidden-xs text-right">
                            <address>
                                <strong>
                                    <h4>
                                        <?php echo translate('shipped_to'); ?> :
                                    </h4>
                                </strong>
                                <p>
                                    <b><?php echo translate('first_name'); ?> :</b>
                                    <?php echo $info['firstname']; ?>
                                </p>
                                <p>
                                    <b><?php echo translate('last_name'); ?> :</b>
                                    <?php echo $info['lastname']; ?>
                                </p>
                                <p>
                                    <b><?php echo translate('address'); ?> :</b>
                                    <br>
                                    <?php echo $info['address1']; ?> <br>
									<?php echo $info['address2']; ?> <br>
                                    <?php echo translate('zip');?> : <?php echo $info['zip']; ?> <br>
                                    <?php echo translate('phone');?> : <?php echo $info['phone']; ?> <br>
                                    <?php echo translate('e-mail');?> : <a href=""><?php echo $info['email']; ?></a>
                                </p>
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6 ">
                            <address>
                                <strong>
                                    <h4>
                                        <?php echo translate('payment_details'); ?> :
                                    </h4>
                                </strong>
                               <?php /*?> <p>
                                    <b><?php echo translate('payment_status'); ?> :</b>
                                    <i><?php echo translate($this->crud_model->sale_payment_status($row['sale_id'])); ?></i>
                                </p><?php */?>
                                <p>
                                    <b><?php echo translate('payment_method'); ?> :</b>
                                    <?php 
                                    $payment_type = $order_details[0]['payment_type'];
                                    
                                    if($payment_type == 'c2'){
                                        echo 'TwoCheckout';
                                    }else if($payment_type=='pum'){
                                        echo "Payumoney";
                                    }else{
                                        echo ucfirst(str_replace('_', ' ', $payment_type)); 
                                    }?>
                                </p>
                            </address>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6  text-right">
                            <?php if($sale_details1['order_type']=='pickup'){ ?>
                             <p>
                                    <b><?php echo translate('pickup_order_info'); ?> :</b>
                                    <br>
                                    <?php echo "Date :".date('d M, Y',$order_details[0]['pickup_date']); ?> <br>
									<?php echo "Time :".$order_details[0]['pickup_slot']; ?> <br>
                                    
                                </p>
                                <?php } ?>
                            <address>
                                <strong>
                                    <h4>
                                        <?php echo translate('order_date'); ?> :
                                    </h4>
                                    <p>
                                        <?php echo date('d M, Y',$order_details[0]['sale_datetime'] );?>
                                    </p>
                                </strong>
                            </address>
                        </div>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong><?php echo translate('payment_invoice');?></strong></h3>
                        </div>
                       
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                             <td class="text-center"><strong><?php echo translate('image');?></strong></td>
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
											foreach($order_details as $row) {
											$product_details = json_decode($row['product_details'], true);
											
											$total = 0;
											foreach ($product_details as $row1) {
												
												$tax += $row1['tax'];
												$shipping += $row1['shipping'];
												$subtotal += $row1['subtotal'];
												$total += $tax+$shipping+$subtotal;
												
										?>
                                        <tr>
                                           	<td class="text-center"><img src="<?php echo $row1['image']; ?>" alt="" width="80" height="80"/></td>
                                            <td class="text-left"><?php echo $row1['name']; ?><br />
                                            	 Seller:<?php echo $this->crud_model->product_by($row1['id'],'with_link');?><br />
                                                 Qty:<?php echo $row1['qty']; ?>
                                            </td>
                                            <td class="text-center">
                                            <?php 
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
											?>
                                            </td>
                                            <td class="text-right">
												<?php echo currency($row1['price']); ?>
                                            </td>
                                            <td class="text-right"><?php echo currency($row1['tax']); ?></td>
                                            <td class="text-right"><?php echo currency($row1['shipping']); ?></td>
                                            <td class="text-right">
												<?php echo currency($row1['subtotal']); 
													
												?>
                                            </td>
                                        </tr>
                                        <?php
											}
											}
										?>
                                        <tr>
                                        	<td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                             <td class="thick-line"></td>
                                            <td class="thick-line text-right">
                                            	<strong>
                                            		<?php echo translate('sub_total_amount');?> :
                                                </strong>
                                            </td>
                                            <td class="thick-line text-right">
                                            	<?php echo currency($subtotal);?>
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-right">
                                            	<strong>
                                            		<?php echo translate('tax');?> :
                                                </strong>
                                            </td>
                                            <td class="no-line text-right">
                                            	<?php echo currency($tax);?>
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-right">
                                            	<strong>
                                            		<?php echo translate('shipping');?> :
                                                </strong>
                                            </td>
                                            <td class="no-line text-right">
                                            	<?php echo currency($shipping);?>
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td class="no-line"></td>
                                            <td class="no-line"></td>
                                                <td class="no-line"></td>
                                            <td class="no-line"></td>
                                             <td class="no-line"></td>
                                            <td class="no-line text-right">
                                            	<strong>
                                            		<?php echo translate('grand_total');?> :
                                                </strong>
                                            </td>
                                            <td class="no-line text-right">
                                            	<?php echo currency($total);?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        
                         
                    </div>
                    <?php if($order_details[0]['product_notes']!='') {?>
                    <p>
                             
                                     Note:   <?php echo $order_details[0]['product_notes'];?>
                                    </p>
                                    <?php } ?>
                </div>
                
            </div>
            
            <div class="col-md-10 col-md-offset-1 btn_print hidden-xs" style="margin-top:10px;">
            	<span class="btn btn-info pull-right" onClick="print_invoice()">
					<?php echo translate('print'); ?>
               	</span>
            </div>
        </div>
        <?php
			
			}
		?>
    </div>
</section>
<script>
function print_invoice(){
	window.print();
}
</script>
<style type="text/css">    
    @media print {
        .top-bar{
            display: none !important;
        }
        header{
            display: none !important;
        }
        footer{
            display: none !important;
        }
        .to-top{
            display: none !important;
        }
        .btn_print{
            display: none !important;
        }
        .invoice{
            padding: 0px;
        }
        .table{
            margin:0px;
        }
        address{
            margin-bottom: 0px;
			border:1px solid #fff !important;
        }
    }
</style>

