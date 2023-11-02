<style>
 
 .stylll{
    position: relative;
    top: 70px;
    left:20px;
 }
 
 #selectBox{
    min-width: 150px;
    height: 30px; 
 }
 
 </style>
 
 <?php 
	$multi_store=$this->db->get_where('business_settings',array('type'=>'multi_store_set'))->row()->value;
							if($multi_store=='ok'){ 
 $stores=$this->db->get_where('stores', array('status' => 'ok','vendor_id'=>$this->session->userdata('vendor_id')))->result_array();
                   $vend= str_replace("%20","",$this->session->userdata('store_id'));
            
                     ?>
                     
                <div class="stylll">     
                    Select Store : <select name="store_id" id="selectBox" class="" onchange="changeFunc();">
                        <option value="" >Select Store</option>
                         <?php foreach($stores as $ven){ 
                         
                         ?>
                         
                         <option value="<?php echo $ven['store_id']; ?>" <?php if($vend==$ven['store_id']) { ?> selected <?php  } ?>   ><?php echo $ven['store_name']; ?> </option>
                         <?php } ?>
                     </select>
                </div>  
                <?php } ?>
<div class="panel-body" id="demo_s">
    <table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true"  data-show-toggle="true" data-show-columns="true" data-search="true" >

        <thead>
            <tr>
                <th style="width:4ex"><?php echo translate('ID');?></th>
                <th><?php echo translate('sale_code');?></th>
                <th><?php echo translate('order_id');?></th>
                <?php 	$multi_store=$this->db->get_where('business_settings',array('type'=>'multi_store_set'))->row()->value;
							if($multi_store=='ok'){ ?>
                <th><?php echo translate('store_name');?></th>
                <?php } ?>
                <th><?php echo translate('buyer');?></th>
                <th><?php echo translate('date');?></th>
                <th><?php echo translate('total');?></th>
                <th><?php echo translate('delivery_status');?></th>
                <th><?php echo translate('payment_status');?></th>
                <th class="text-right"><?php echo translate('options');?></th>
            </tr>
        </thead>
            
        <tbody>
        <?php
            $i = 0;
            if($this->session->userdata('store_id')!=""){
             $ven= str_replace("%20","",$this->session->userdata('store_id'));
             $this->db->where('store_id',$ven );
                 
                
            
            $this->db->group_by('order_id', 'desc');
            $this->db->order_by('sale_id', 'desc');
            $all_sales= $this->db->get('sale')->result_array();
         }
         $this->session->unset_userdata('vendor');
            foreach($all_sales as $row){
				//print_r($row);
                if($this->crud_model->is_sale_of_vendor($row['sale_id'],$this->session->userdata('vendor_id'))){
                $i++;
        ?>
        <tr class="<?php if($row['viewed'] !== 'ok'){ echo 'pending'; } ?>" >
            <td><?php echo $i; ?></td>
            <td>#<?php echo $row['sale_code']; ?></td>
            <td>#<?php echo $row['order_id']; ?></td>
            <?php 	$multi_store=$this->db->get_where('business_settings',array('type'=>'multi_store_set'))->row()->value;
							if($multi_store=='ok'){ ?>
            <td><?php echo $this->db->get_where('stores',array('store_id'=>$row['store_id']))->row()->store_name;  ?></td>
            <?php } ?>
            <td><?php echo $this->crud_model->get_type_name_by_id('user',$row['buyer'],'username'); ?></td>
            <td><?php echo date('d-m-Y',$row['sale_datetime']); ?></td>
            <td class="pull-right"><?php echo currency('','def').$this->cart->format_number($this->crud_model->vendor_share_in_sale($row['sale_id'],$this->session->userdata('vendor_id'))['total']); ?></td>
            <td>
                <?php 
                    $delivery_status = json_decode($row['delivery_status'],true); 
                    foreach ($delivery_status as $dev) {
                    if(isset($dev['vendor'])){
                        if($dev['vendor'] == $this->session->userdata('vendor_id')){
                ?>
                <span class="label label-<?php if($dev['status'] == 'delivered'){ ?>purple<?php } else { ?>danger<?php } ?>">
                    <?php
                        echo $dev['status'];
                    ?>
                </span>
                <?php
                            }
                        }
                    }
                ?>
            </td>
            <td>

                <?php 
                    $payment_status = json_decode($row['payment_status'],true); 
                    foreach ($payment_status as $dev) {if(isset($dev['vendor'])){
                        if($dev['vendor'] == $this->session->userdata('vendor_id')){
                ?>
                <span class="label label-<?php if($dev['status'] == 'paid'){ ?>purple<?php } else { ?>danger<?php } ?>">
                <?php
                    echo  $dev['status']; 
                ?>
                </span>
                <?php
                            }
                        }
                    }
                ?>
            </td>
            <td class="text-right">
            <?php if($row['delivery_agent_id']==0) { ?>
            <a class="btn btn-success btn-xs btn-labeled fa fa-usd" data-toggle="tooltip" 
                    onclick="ajax_modal('delivery_agent','<?php echo translate('Assign_courier'); ?>','<?php echo translate('successfully_edited!'); ?>','delivery_agent','<?php echo $row['sale_id']; ?>')" 
                        data-original-title="Edit" data-container="body">
                            <?php echo translate('Assign_courier'); ?>
                </a>
                <?php  }else{?>
                
                
                <a class="btn btn-success btn-xs btn-labeled"><?php  $del_name = $this->db->get_where('delivery_agent',array('agent_id'=>$row['delivery_agent_id']))->row()->agent_name; 
				//echo $this->db->last_query();
				
				 echo $del_name;?></a>
            <?php    }
                ?>

                <a class="btn btn-info btn-xs btn-labeled fa fa-file-text" data-toggle="tooltip" 
                    onclick="ajax_set_full('view','<?php echo translate('title'); ?>','<?php echo translate('successfully_edited!'); ?>','sales_view','<?php echo $row['sale_id']; ?>')" 
                        data-original-title="Edit" data-container="body"><?php echo translate('full_invoice'); ?>
                </a>
                
                <a class="btn btn-success btn-xs btn-labeled fa fa-usd" data-toggle="tooltip" 
                    onclick="ajax_modal('delivery_payment','<?php echo translate('delivery_payment'); ?>','<?php echo translate('successfully_edited!'); ?>','delivery_payment','<?php echo $row['sale_id']; ?>')" 
                        data-original-title="Edit" data-container="body">
                            <?php echo translate('delivery_status'); ?>
                </a>
                
                <a onclick="delete_confirm('<?php echo $row['sale_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" 
                    class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" 
                        data-original-title="Delete" data-container="body"><?php echo translate('delete'); ?>
                </a>
            </td>
        </tr>
        <?php
                }
            }
        ?>
        </tbody>
    </table>
</div>  
    <div id='export-div' style="padding:40px;">
        <h1 id ='export-title' style="display:none;"><?php echo translate('sales'); ?></h1>
        <table id="export-table" class="table" data-name='sales' data-orientation='l' data-width='1500' style="display:none;">
                <colgroup>
                    <col width="50">
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="250">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sale Code</th>
                        <th>Buyer</th>
                        <th>Date</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody >
                <?php
                    $i = 0;
                    foreach($all_sales as $row){
                        if($this->crud_model->is_sale_of_vendor($row['sale_id'],$this->session->userdata('vendor_id'))){
                        $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td>#<?php echo $row['sale_code']; ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('user',$row['buyer'],'username'); ?></td>
                    <td><?php echo date('d-m-Y',$row['sale_datetime']); ?></td>
                    <td><?php echo currency('','def').$this->cart->format_number($this->crud_model->vendor_share_in_sale($row['sale_id'],$this->session->userdata('vendor_id'))['total']); ?></td>               
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
<script type="text/javascript">

   function changeFunc() {
    var selectBox = document.getElementById("selectBox");
    var selectedValue = selectBox.options[selectBox.selectedIndex].value;
    //alert(selectedValue);
    $.ajax({
			url: base_url+'index.php/vendor/sales/vendor_search/'+selectedValue,
			
			success: function(data) {
			    
			    location.reload();
			},
			error: function(e) {
				console.log(e)
			}
		});
    
   }

  </script>



           