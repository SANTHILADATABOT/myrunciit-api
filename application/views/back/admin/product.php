<div id="content-container">
<div class="content-wrapper-before"></div>

	<div id="page-title">
		<h1 class="page-header text-overflow"><?php echo translate('manage_product');?></h1>
	</div>
        <div class="tab-base">
            <div class="panel">
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="col-md-12" style="border-bottom: 1px solid #ebebeb;padding: 5px;">
                              <?php 
                                $this->db->where('status',1);
                              //  $this->db->where('added_by',$this->session->userdata('propertyIDS'));
                                $today   = $this->db->get('today_deals')->num_rows();
                               // echo $this->db->last_query();
                                //$deals = $this->db->get("deals", array("status"=>1))->result_array();
                                if($today!=1)
                                {
                            ?>
                            <button class="btn btn-primary btn-labeled fa fa-plus-circle add_pro_btn pull-right" 
                                onclick="ajax_set_full('today','<?php echo translate('add_flash_deals'); ?>','<?php echo translate('successfully_added!'); ?>','today_add',''); proceed('to_list');"><?php echo translate('create_flash_deals');?>
                            </button>
                            <?php } else { ?>
                                 <?php
                                 $this->db->where('status',1);
                               // $this->db->where('added_by',$this->session->userdata('propertyIDS'));
                                    $todaytdeals_get  = $this->db->get('today_deals')->result_array();
                                    //echo "<pre>"; print_r($deals_get); echo "</pre>";
                                 ?>
                                   <button class="btn btn-success btn-labeled fa fa-pencil add_pro_btn pull-right" 
                                onclick="ajax_set_full('today_edit','<?php echo translate('edit_flash_deals'); ?>','<?php echo translate('successfully_edited!'); ?>','todays_edit',<?php echo $todaytdeals_get[0]['today_id']?>); proceed('to_list');"><?php echo translate('flash_deal_edit');?>
                            </button>
                                 <a href="<?php echo base_url(); ?>admin/product/today_deact/<?php echo $todaytdeals_get[0]['today_id']?>" class="btn btn-warning btn-labeled fa fa-trash pull-right">
                                        <?php echo translate('Delete_flash_deal');?>
                                </a>
                            <?php } ?>
                            <button class="btn btn-primary btn-labeled fa fa-plus-circle add_pro_btn pull-right" 
                                onclick="ajax_set_full('add','<?php echo translate('add_product'); ?>','<?php echo translate('successfully_added!'); ?>','product_add',''); proceed('to_list');"><?php echo translate('create_product');?>
                            </button>
                            <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" 
                                style="display:none;"  onclick="ajax_set_list();  proceed('to_add');"><?php echo translate('back_to_product_list');?>
                            </button>
                        </div>
                    <!-- LIST -->
                    <div class="tab-pane fade active in" id="list" style="border:1px solid #ebebeb; border-radius:4px;">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<span id="prod" style="display:none;"></span>
<script>
	var base_url = '<?php echo base_url(); ?>';
	var timer = '<?php $this->benchmark->mark_time(); ?>';
	var user_type = 'admin';
	var module = 'product';
var list_cont_func = 'list/<?php if($singvendor){ echo $singvendor; } else { echo "0"; } ?>/<?php if($status){ echo $status; } else { echo "0"; } ?>';
	var dlt_cont_func = 'delete';

	
	function proceed(type){
		if(type == 'to_list'){
			$(".pro_list_btn").show();
			$(".add_pro_btn").hide();
		} else if(type == 'to_add'){
			$(".add_pro_btn").show();
			$(".pro_list_btn").hide();
		}
	}
</script>

