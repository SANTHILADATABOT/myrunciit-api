<style>
    .label_set_green{
        background: linear-gradient(to right, rgb(9, 48, 40), rgb(35, 122, 87));
    }
    .label_set_yellow{
        background: linear-gradient(to right, rgb(254, 171, 52), rgb(240, 173, 78));
    }
    .label_set_red{
        background: linear-gradient(to right, rgb(237, 33, 58), rgb(147, 41, 30));
    }
</style>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js">
  </script>

<?php
$delete_rights=$user_rights_5_0['delete_rights'];
?>
<div class="panel-body" id="demo_s">
    <table id="demo-table" class="table table-striped" data-export-types="['excel','pdf']" data-show-export="true"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >
        <thead>
            <tr>
                <th style="width:4ex"><?php echo translate('ID');?></th>
                <th><?php echo translate('order_no');?></th>
                <th><?php echo translate('order_date');?></th>
                <th><?php echo translate('Store_name');?></th>
                <!-- <th><?php echo translate('brand_name');?></th> -->
                <th><?php echo translate('order_type');?></th>
                <th><?php echo translate('Pickup/Delivery Date');?></th>
                <th><?php echo translate('Customer');?></th>
                <th><?php echo translate('Customer_mobile');?></th>
                <th><?php echo translate('delivery_status');?></th>
                <th><?php echo translate('payment_status');?></th>
                <th><?php echo translate('Amount');?></th>
                <?php /*<th><?php echo translate('Promo_code');?></th>*/ ?>
                <th><?php echo translate('Pre_Order_Status');?></th>
            </tr>
        </thead>
            
        <tbody>
        <?php
        $i = 0;
        foreach($all_sales as $row){
            //echo '<pre>'; print_r($row);
            $i++; 
        ?>
        <tr class="<?php if($row['viewed'] !== 'ok'){ echo 'pending'; } ?>" >
            <td><?php echo $i; ?></td>
            <td>						
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="text-align: left;">#<?php echo $row['order_id']; ?></div>
                    <div class="btn-group">
                        <!-- <button class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> -->
                            <i class="fa fa-chevron-circle-down icon-default" aria-hidden="true" id="dropdownMenu<?php echo $i; ?>" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></i>
                        <!-- </button> -->
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu<?php echo $i; ?>">
                        <?php if($row['status']=='admin_pending') { ?>
                        <li><a class="btn btn-info btn-xs btn-labeled fa fa-file-text" style="color:white;width:100%;text-align:left;" data-toggle="tooltip" onclick="ajax_modal('accept','<?php echo translate('status'); ?>','<?php echo "Order ".$row['status']; ?>','sales_accept','<?php echo $row['sale_id']; ?>')" data-original-title="Edit" data-container="body"><?php echo translate('accept_order'); ?></a></li>
                        <?php } else if($row['status']=='success'){ ?>
                        <li><a class="btn btn-info btn-xs btn-labeled fa fa-check" style="color:white;width:100%;text-align:left;" data-toggle="tooltip" data-original-title="Edit" data-container="body"><?php echo translate('order_accepted'); ?></a></li>
                        <?php } else if($row['status']=='rejected'){ ?>
                        <li><a class="btn btn-danger btn-xs btn-labeled fa fa-close" style="color:white;width:100%;text-align:left;" data-toggle="tooltip" data-original-title="Edit" data-container="body"><?php echo translate('order_rejected'); ?></a></li>
                        <?php } ?>
                        <?php if($row['staff_id']==''){ ?>
                        <!-- <li><a class="btn btn-info btn-xs btn-labeled fa fa-user" style="color:white;width:100%;text-align:left;" data-toggle="tooltip" onclick="ajax_modal('assign','<?php echo translate('order_assign'); ?>','<?php echo translate('order_assigned!'); ?>','sales_assign','<?php echo $row['sale_id']; ?>')" data-original-title="Assign" data-container="body"><?php echo translate('assign_order'); ?></a></li> -->
                        <?php } else {
                        $this->db->where('admin_id', $row['staff_id']);
                        $orderDet = $this->db->get('admin')->result_array();
                        ?>
                        <li><a class="btn btn-info btn-xs btn-labeled fa fa-check" style="color:white;width:100%;text-align:left;" data-toggle="tooltip" data-container="body"><?php echo $orderDet[0]['name']; ?></a></li>
                        <?php } ?>
                        <li><a class="btn btn-info btn-xs btn-labeled fa fa-file-text" style="color:white;width:100%;text-align:left;" data-toggle="tooltip" onclick="open_orderdetail();ajax_set_full_sales_orderdetail('view','<?php echo translate('title'); ?>','<?php echo translate('successfully_edited!'); ?>','sales_view','<?php echo $row['sale_id']; ?>')" data-original-title="Edit" data-container="body"><?php echo translate('order_details'); ?></a></li>
                        <?php if($row['status']=='success'){ ?>
                            <li><a class="btn btn-success btn-xs btn-labeled fa fa-usd" style="color:white;width:100%;text-align:left;" data-toggle="tooltip" onclick="ajax_modal('delivery_payment','<?php echo translate('delivery_payment'); ?>','<?php echo translate('successfully_edited!'); ?>','delivery_payment','<?php echo $row['sale_id']; ?>')" data-original-title="Edit" data-container="body"><?php echo translate('delivery_status'); ?></a></li>
                        <?php } ?>
                        <?php if($delete_rights=='1'){ ?>
                        <li><a onclick="delete_confirm('<?php echo $row['sale_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" style="color:white;width:100%;text-align:left;" data-toggle="tooltip" data-original-title="Delete" data-container="body"><?php echo translate('delete'); ?></a></li>
                        <?php } ?>
                        </ul>
                    </div>
                </div>
            </td>
            <td><?php echo date('d-m-Y',$row['sale_datetime']); ?></td>
            <td><?php echo $lp = $this->db->get_where('vendor', array('vendor_id' => $row['store_id']))->row()->name; ?></td>     
            <td><?php echo $row['order_type']; ?></td>
            <td><?php if($row['pickup_date']!=""){echo $row['pickup_date'].'<br>'.$row['pickup_slot'];}else{echo "-";} ?></td>
            <td><?php echo $this->crud_model->get_type_name_by_id('user',$row['buyer'],'username'); ?></td>
            <td><?php echo $this->crud_model->get_type_name_by_id('user',$row['buyer'],'phone'); ?></td>
            <td>
				<?php 
                $this->benchmark->mark_time();
                $delivery_status = json_decode($row['delivery_status'],true); 
                foreach ($delivery_status as $dev) {
                ?>
                <div class="label <?php if($dev['status']=='pending'){ ?>label_set_red<?php }else if($dev['status']=='on_delivery'){ ?>label_set_yellow<?php }else{ ?>label_set_green<?php } ?>">
                <?php
                if(isset($dev['vendor'])){
                    echo $this->crud_model->get_type_name_by_id('vendor', $dev['vendor'], 'display_name').' ('.translate('vendor').') : '.$dev['status'];
                } else if(isset($dev['admin'])) {
                    echo translate('admin').' : '.$dev['status'];
                } ?>
                </div>
                <?php } ?>
            </td>
            <td>
                <?php 
                $payment_status = json_decode($row['payment_status'],true); 
                foreach ($payment_status as $dev) {
                ?>
                <div class="label <?php if($dev['status']=='paid'){ ?>label_set_green<?php }else{ ?>label_set_red<?php } ?>">
                <?php
                if(isset($dev['vendor'])){
                    echo $this->crud_model->get_type_name_by_id('vendor', $dev['vendor'], 'display_name').' ('.translate('vendor').') : '.$dev['status'];
                } else if(isset($dev['admin'])) {
                    echo translate('admin').' : '.$dev['status'];
                } ?>
                </div>
                <br>
                <?php } ?>
            </td>
            <td><?php echo currency('','def').$this->cart->format_number($row['grand_total']); ?></td>
            <?php /* <td><?php echo $row['promo_code']; ?></td> */ ?>
            <td>
                <?php //echo $row['pre_order_status'] ? $row['pre_order_status'] : 'No'; 
                if ($row['pre_order_status'] == 'ok') { ?> <i class="fa fa-check" aria-hidden="true" style="color:green"></i>
                <?php } else { ?><i class="fa fa-close" aria-hidden="true" style="color:red"></i>
                <?php } ?>
            </td>
        </tr>
        <?php
            }
        ?>
        </tbody>
    </table>
</div>  
    <div id='export-div' style="padding:70px;">
		<h1 id ='export-title' style="display:none;"><?php echo translate('sales'); ?></h1>
		<table id="export-table" class="table" data-name='sales' data-orientation='l' data-width='2000' style="display:none;">
				<colgroup>
					<col width="50">
					<col width="200">
					<col width="200">
					<col width="200">
					<col width="200">
					<col width="200">
					<col width="200">
					<col width="200">
					<col width="200">
					<col width="200">
					<col width="400">
				</colgroup>
				<thead>
                <tr>
                <th style="width:4ex"><?php echo translate('ID');?></th>
                <th><?php echo translate('order_no');?></th>
                <th><?php echo translate('order_date');?></th>
                <th><?php echo translate('Store');?></th>
                <th><?php echo translate('brand');?></th>
                <th><?php echo translate('order_type');?></th>
                <th><?php echo translate('Pickup/Delivery Date');?></th>
                <th><?php echo translate('Customer');?></th>
                <th><?php echo translate('delivery_status');?></th>
                <th><?php echo translate('payment_status');?></th>
                <th><?php echo translate('Amount');?></th>
                
                <?php /*<th><?php echo translate('Promo_code');?></th> */ ?>
                <th><?php echo translate('Pre_Order_Status');?></th>
            </tr>
				</thead>

				<tbody >
				<?php
            $i = 0;
            foreach($all_sales as $row){
				//echo '<pre>'; print_r($row);
                $i++; 
        ?>
        <tr class="<?php if($row['viewed'] !== 'ok'){ echo 'pending'; } ?>" >
            <td><?php echo $i; ?></td>
            <td>#<?php echo $row['order_id']; ?></td>
            <td><?php echo date('d-m-Y',$row['sale_datetime']); ?></td>
            <td> 
                <?php echo $lp = $this->db->get_where('vendor', array(
                            'vendor_id' => $row['store_id']
                        ))->row()->name; ?></td>
                        <!-- brand Name start -->
   <?php 
          
          $product_details= $row['product_details']; 
          $data = json_decode($product_details, true);

          foreach ($data as $key => $item) {
             $product_id= $item[id];
              if($product_id!=""){
              $this->db->select('product_id,brand');
              $this->db->where('product_id',$product_id);
              $product_result = $this->db->get('product')->result_array();
              $count=count($product_result);
              if($count!="0"){
            foreach ($product_result as $brand_get)
                  {?>
                       <td><?php echo $this->db->get_where('brand', array('brand_id' => $brand_get['brand']))->row()->name;?></td>
                  
                <?php   }
          }
          else{?>
              <td><?php echo "-";?></td>
        <?php }
        }
      
          else{?>
                  <td><?php echo "-";?></td>
         <?php }
      }
          ?>
          <!-- brand Name End -->
            <td><?php echo $row['order_type']; ?></td>
            <td><?php echo $row['pickup_date'] .'<br> '. $row['pickup_slot']; ?></td>
            <td><?php echo $this->crud_model->get_type_name_by_id('user',$row['buyer'],'username'); ?></td>
            
            
            <td>
				<?php 
					$this->benchmark->mark_time();
                    $delivery_status = json_decode($row['delivery_status'],true); 
                    foreach ($delivery_status as $dev) {
                ?>

                <div class="label label-<?php if($dev['status'] == 'delivered'){ ?>purple<?php } else { ?>danger<?php } ?>">
                <?php
                        if(isset($dev['vendor'])){
                            echo $this->crud_model->get_type_name_by_id('vendor', $dev['vendor'], 'display_name').' ('.translate('vendor').') : '.$dev['status'];
                        } else if(isset($dev['admin'])) {
                            echo translate('admin').' : '.$dev['status'];
                        }
                ?>
                </div>
                <br>
                <?php
                    }
                ?>
            </td>
            
            <td>

                <?php 
                    $payment_status = json_decode($row['payment_status'],true); 
                    foreach ($payment_status as $dev) {
                ?>

                <div class="label label-<?php if($dev['status'] == 'paid'){ ?>purple<?php } else { ?>danger<?php } ?>">
                <?php
                        if(isset($dev['vendor'])){
                            echo $this->crud_model->get_type_name_by_id('vendor', $dev['vendor'], 'display_name').' ('.translate('vendor').') : '.$dev['status'];
                        } else if(isset($dev['admin'])) {
                            echo translate('admin').' : '.$dev['status'];
                        }
                ?>
                </div>
                <br>
                <?php
                    }
                ?>
            </td>
           <td><?php echo currency('','def').$this->cart->format_number($row['grand_total']); ?></td>
           
           <?php /* <td><?php echo $row['promo_code']; ?></td> */ ?>
           <td>
            <?php //echo $row['pre_order_status'] ? $row['pre_order_status'] : 'No'; 
             if ($row['pre_order_status'] == 'ok') { echo 'checked';
                 } else { 
                    echo '-';
                 } ?>
            </td>
            
        </tr>
        <?php
            }
        ?>
				</tbody>
		</table>
	</div>
    
<style type="text/css">
	.pending{
		background: #D2F3FF  !important;
	}
	.pending:hover{
		background: #9BD8F7 !important;
	}
</style>



           