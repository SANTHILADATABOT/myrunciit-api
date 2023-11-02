<div class="panel-body" id="demo_s">
    <table id="demo-table" class="table table-striped" data-export-types="['excel','pdf']" data-show-export="true"  data-pagination="true" data-show-refresh="true"  data-show-toggle="true" data-show-columns="true" data-search="true" >

        <thead>
            <tr>
                <th style="width:4ex"><?php echo translate('ID');?></th>
                <th><?php echo translate('sale_code');?></th>
                <th><?php echo translate('order_id');?></th>
                <th><?php echo translate('buyer');?></th>
                <th><?php echo translate('buyer_phone');?></th>
                <th><?php echo translate('ordered_date');?></th>
                <th><?php echo translate('total');?></th>
                <th><?php echo translate('cancel_status');?></th>
                <th><?php echo translate('cancel_remarks');?></th>
                 <!-- <th class="text-right"><?php echo translate('options');?></th> -->
                
              
            </tr>
        </thead>
            
        <tbody>
        <?php
            $i = 0;
            foreach($all_sales as $row){
				//print_r($row);
                if(!$this->crud_model->is_sale_of_vendor($row['sale_id'],$this->session->userdata('vendor_id'))){
                $i++;
        ?>
        <tr class="<?php if($row['viewed'] !== 'ok'){ echo 'pending'; } ?>" >
            <td><?php echo $i; ?></td>
            <td>#<?php echo $row['sale_code']; ?></td>
            <td><a class="btn btn-labeled " onclick="ajax_set_full('view','<?php echo translate('title'); ?>','<?php echo translate('successfully_edited!'); ?>','sales_view','<?php echo $row['sale_id']; ?>')" data-original-title="Edit" data-container="body">#<?php echo $row['order_id']; ?> </a>   
            </td>
            <td><?php echo $this->crud_model->get_type_name_by_id('user',$row['buyer'],'username'); ?></td>
            <td><?php echo $this->crud_model->get_type_name_by_id('user',$row['buyer'],'phone'); ?></td>
            <td><?php echo date('d-m-Y',$row['sale_datetime']); ?></td>
            <td><?php //echo  currency('','def').$this->cart->format_number($this->crud_model->vendor_share_in_sale($row['sale_id'],$this->session->userdata('vendor_id'))['total']); 
            echo $row['grand_total'];
            ?></td>
             <td><?php  echo "cancelled"; ?></td>
              <td><?php echo $row['cancel_remarks']; ?></td>
             <!-- <td class="text-right">
          

                <a class="btn btn-info btn-xs btn-labeled fa fa-file-text" data-toggle="tooltip" 
                    onclick="ajax_set_full('view','<?php echo translate('title'); ?>','<?php echo translate('successfully_edited!'); ?>','sales_view','<?php echo $row['sale_id']; ?>')" 
                        data-original-title="Edit" data-container="body"><?php echo translate('full_invoice'); ?>
                </a>
                
                
                
                
            </td>             -->
            
        </tr>
        <?php
                }
            }
        ?>
        </tbody>
    </table>
</div>  
    <div id='export-div'>
        <h1 id ='export-title' style="display:none;"><?php echo translate('Rejected Orders'); ?></h1>
        <table id="export-table" data-name='cancel_sales' data-orientation='p'  style="display:none;">
                <colgroup>
                    <col width="50">
                    <col width="250">
                    <col width="250">
                    <col width="250">
                    <col width="250">
                    <col width="250">
                    <col width="250">
                    <col width="250">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        
                <th><?php echo translate('sale_code');?></th>
                <th><?php echo translate('order_id');?></th>
                <th><?php echo translate('buyer');?></th>
                <th><?php echo translate('ordered_date');?></th>
                <th><?php echo translate('total');?></th>
                <th><?php echo translate('cancel_status');?></th>
                <th><?php echo translate('cancel_remarks');?></th>
                       
                    </tr>
                </thead>

                <tbody >
                <?php
            $i = 0;
            foreach($all_sales as $row){
				//print_r($row);
                if(!$this->crud_model->is_sale_of_vendor($row['sale_id'],$this->session->userdata('vendor_id'))){
                $i++;
        ?>
                <tr>
                    
                    <td><?php echo $i; ?></td>
            <td>#<?php echo $row['sale_code']; ?></td>
            <td>#<?php echo $row['order_id']; ?></td>
            <td><?php echo $this->crud_model->get_type_name_by_id('user',$row['buyer'],'username'); ?></td>
            <td><?php echo date('d-m-Y',$row['sale_datetime']); ?></td>
            <td><?php  echo $row['grand_total'];  ?></td>
             <td><?php  //echo $this->crud_model->get_type_name_by_id1('cancel_reason',$row['cancel_reason'],'cancel_reason'); 
            echo "cancelled";
             ?></td>
              <td><?php echo $row['cancel_remarks']; ?></td> 
                    
                </tr>
                <?php
                        }
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



           