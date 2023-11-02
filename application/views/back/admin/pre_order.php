<div id="content-container">
<div class="content-wrapper-before"></div>

	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
				    <div class="row">
				    <div class="col-md-12">
					  <h1 class="page-header text-overflow" ><?php echo translate('manage_pre_order');?></h1>
					</div>
					</div>

					<?php
				 date_default_timezone_set("Asia/Kuala_Lumpur");
				 $cur_dt=date('Y-m-d');
				 $this->db->order_by('id', 'desc');
				 $this->db->where('status', 'ok');
				 $pre_dts = $this->db->get('pre_order')->result_array();
				 $s_dt=$pre_dts[0]['start_date'];
				 $e_dt=$pre_dts[0]['end_date'];
				   if($pre_dts[0]['status']=='ok' && $s_dt <= $cur_dt && $e_dt>=$cur_dt){  }else {?>
				 
				<div style="border-bottom: 1px solid #ebebeb;padding: 25px 5px 5px 5px;"
                    	class="col-md-12" >
						<?php if($user_rights_28_0['add_rights']=='1'){ ?>
						<button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right mar-rgt" 
                        	onclick="ajax_modal('add','<?php echo translate('add_pre_order'); ?>','<?php echo translate('successfully_added!'); ?>','pre_order_add','')">
								<?php echo translate('create_pre_order');?>
                                	</button>
									<?php } ?>
					</div>
					<?php } ?>
					<br>
                    <div class="tab-pane fade active in" 
                    	id="list" style="border:1px solid #ebebeb; border-radius:4px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'pre_order';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
</script>

