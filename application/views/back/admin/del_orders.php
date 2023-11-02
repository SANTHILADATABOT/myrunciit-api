<div id="content-container">
<div class="content-wrapper-before"></div>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
			     <div class="tab-content">
					<div class="row" style="">
					<div class="col-md-12">
					 <div id="">
		                <h1 class="page-header text-overflow" ><?php echo translate('delivered_orders');?></h1>
	                 </div>
					</div>
					
					</div>
                <!-- LIST -->
                <div class="tab-pane fade active in" id="list" style="margin-top:30px">
                
                </div>
			  </div>
			</div>
        </div>
	</div>
</div>

<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'del_orders';
	/*var from = '<?php if($from) { echo $from; } else { echo ''; } ?>';
	var to = '<?php if($to) { echo $to; } else { echo ''; } ?>';
	var delstatus1 = '<?php echo $delstatus1; ?>';
	var pay_status1=0;
	var vendor_name1=0;*/
	var list_cont_func = 'list/<?php if($from) { echo $from; } else { echo "0"; } ?>/<?php if($to) { echo $to; } else { echo "0"; } ?>/<?php if($delstatus1) { echo $delstatus1; } else { echo "0"; } ?>';
//	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
</script>

