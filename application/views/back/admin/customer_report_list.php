<div class="panel-body" id="demo_s">
    <table id="demo-table" class="table table-striped" data-pagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true">
        <thead>
            <tr>
                <th><?php echo translate('s.no'); ?></th>
                <th><?php echo translate('order_id'); ?></th>
                <th><?php echo translate('purchase_date'); ?></th>
                <th><?php echo translate('payment_date'); ?></th>
                <th><?php echo translate('Buyer_Id'); ?></th>
                <th><?php echo translate('buyer_email'); ?></th>
                <th><?php echo translate('buyer_name'); ?></th>
                <th><?php echo translate('buyer_phone_number'); ?></th>

                <th><?php echo translate('product_name'); ?></th>
                <th><?php echo translate('quantity_purchased'); ?></th>
                <th><?php echo translate('item_price'); ?></th>
                <th><?php echo translate('recipient_name'); ?></th>
                <th><?php echo translate('ship_address_1'); ?></th>
                <th><?php echo translate('ship_address_2'); ?></th>
                <th><?php echo translate('ship_city'); ?></th>
                <th><?php echo translate('ship_state'); ?></th>
                <th><?php echo translate('ship_postal_code'); ?></th>
                <th><?php echo translate('payment_method'); ?></th>
                <!--th><?php echo translate('shipment_status'); ?></th-->



            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($all_sales as $row) {



                $i++;


                /*  $sold_by_type = json_decode($row['payment_status'],true);
                                $sold_by_type = $sold_by_type[0];
                                if(isset($sold_by_type['vendor'])){
                                    //print_r($sold_by_type);
                                $vendor_id = $sold_by_type['vendor'];
                                $vendor_det =$this->db->get_where('vendor', array('vendor_id' =>$vendor_id))->result_array();
                                 foreach($vendor_det as $vendor) {
                                           $display_name =  $vendor['display_name'];
                                           $address1 = $vendor['address1'];
                                           $city = $vendor['store_city'];
                                           $zip = $vendor['zip'];
                                           $state = $vendor['store_district'];
                                           $country = $vendor['store_country'];
                                           $gst = $vendor['gst'];
                                           $panno = $vendor['panno'];
                                 } 
                                }
                                 else{
                                     
                                      $display_name = $this->db->get_where('general_settings', array('type' => 'system_name'))->row()->value;
                                      $address1 =  $this->db->get_where('general_settings', array('type' => 'contact_address'))->row()->value;
                                     
                                 } */


                $product_details = json_decode($row['product_details'], true);
                foreach ($product_details as $row1) {
                    $price = $row1['price'];
                    $qty = $row1['qty'];
                    $tax = $row1['tax'];
                    $prod_id = $row1['id'];
                }
                $info = json_decode($row['shipping_address'], true);

            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['order_id']; ?></td>

                    <td><?php echo date('d M, Y', $row['sale_datetime']); ?></td>
                    <td><?php echo date('d M, Y', $row['sale_datetime']); ?></td>
                    <td><?php echo $row['buyer']; ?></td>
                    <td><?php echo $info['email']; ?></td>
                    <td><?php echo $info['firstname']; ?></td>
                    <td><?php echo $info['phone']; ?></td>

                    <td><?php echo $this->db->get_where('product', array('product_id' => $prod_id))->row()->title; ?></td>
                    <td><?php echo $qty; ?></td>
                    <td><?php echo $price * $qty; ?></td>

                    <td><?php echo $info['firstname']; ?></td>
                    <td><?php echo $info['address1']; ?></td>
                    <td><?php echo $info['address2']; ?></td>
                    <td><?php echo $info['cities']; ?></td>
                    <td><?php echo $info['state']; ?></td>
                    <td><?php echo $info['zip']; ?></td>
                    <td><?php echo $row['payment_type']; ?></td>

                    <!--td>
            
            <?php
                $this->benchmark->mark_time();
                $delivery_status = json_decode($row['delivery_status'], true);
                foreach ($delivery_status as $dev) {

            ?>

                <span class="label label-<?php if ($dev['status'] == 'delivered') { ?>purple<?php } else { ?>danger<?php } ?>">
                <?php
                    echo $dev['status'];
                ?>
                </span>
                <br>
                <?php
                }
                ?>
                </td-->
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<div id='export-div' style="padding:40px;">
    <h1 id='export-title' style="display:none;"><?php echo translate('users'); ?></h1>
    <table id="export-table" class="table" data-export-types="['excel','pdf']" data-show-export="true" data-name='users' data-orientation='p' data-width='9500' style="display:none;">
        <colgroup>
            <col width="50">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
        </colgroup>
        <thead>
            <tr>
                <th><?php echo translate('s.no'); ?></th>
                <th><?php echo translate('order_id'); ?></th>
                <th><?php echo translate('purchase_date'); ?></th>
                <th><?php echo translate('payment_date'); ?></th>
                <th><?php echo translate('Buyer_Id'); ?></th>
                <th><?php echo translate('buyer_email'); ?></th>
                <th><?php echo translate('buyer_name'); ?></th>
                <th><?php echo translate('buyer_phone_number'); ?></th>

                <th><?php echo translate('product_name'); ?></th>
                <th><?php echo translate('quantity_purchased'); ?></th>
                <th><?php echo translate('item_price'); ?></th>
                <th><?php echo translate('recipient_name'); ?></th>
                <th><?php echo translate('ship_address_1'); ?></th>
                <th><?php echo translate('ship_address_2'); ?></th>
                <th><?php echo translate('ship_city'); ?></th>
                <th><?php echo translate('ship_state'); ?></th>
                <th><?php echo translate('ship_postal_code'); ?></th>
                <th><?php echo translate('payment_method'); ?></th>
                



            </tr>
        </thead>



        <tbody>
        <?php
            $i = 0;
            foreach ($all_sales as $row) {



                $i++;


                /*  $sold_by_type = json_decode($row['payment_status'],true);
                                $sold_by_type = $sold_by_type[0];
                                if(isset($sold_by_type['vendor'])){
                                    //print_r($sold_by_type);
                                $vendor_id = $sold_by_type['vendor'];
                                $vendor_det =$this->db->get_where('vendor', array('vendor_id' =>$vendor_id))->result_array();
                                 foreach($vendor_det as $vendor) {
                                           $display_name =  $vendor['display_name'];
                                           $address1 = $vendor['address1'];
                                           $city = $vendor['store_city'];
                                           $zip = $vendor['zip'];
                                           $state = $vendor['store_district'];
                                           $country = $vendor['store_country'];
                                           $gst = $vendor['gst'];
                                           $panno = $vendor['panno'];
                                 } 
                                }
                                 else{
                                     
                                      $display_name = $this->db->get_where('general_settings', array('type' => 'system_name'))->row()->value;
                                      $address1 =  $this->db->get_where('general_settings', array('type' => 'contact_address'))->row()->value;
                                     
                                 } */


                $product_details = json_decode($row['product_details'], true);
                foreach ($product_details as $row1) {
                    $price = $row1['price'];
                    $qty = $row1['qty'];
                    $tax = $row1['tax'];
                    $prod_id = $row1['id'];
                }
                $info = json_decode($row['shipping_address'], true);

            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['order_id']; ?></td>

                    <td><?php echo date('d M, Y', $row['sale_datetime']); ?></td>
                    <td><?php echo date('d M, Y', $row['sale_datetime']); ?></td>
                    <td><?php echo $row['buyer']; ?></td>
                    <td><?php echo $info['email']; ?></td>
                    <td><?php echo $info['firstname']; ?></td>
                    <td><?php echo $info['phone']; ?></td>

                    <td><?php echo $this->db->get_where('product', array('product_id' => $prod_id))->row()->title; ?></td>
                    <td><?php echo $qty; ?></td>
                    <td><?php echo $price * $qty; ?></td>

                    <td><?php echo $info['firstname']; ?></td>
                    <td><?php echo $info['address1']; ?></td>
                    <td><?php echo $info['address2']; ?></td>
                    <td><?php echo $info['cities']; ?></td>
                    <td><?php echo $info['state']; ?></td>
                    <td><?php echo $info['zip']; ?></td>
                    <td><?php echo $row['payment_type']; ?></td>

                
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>