<div id="content-container">
    <div class="content-wrapper-before"></div>
        <div style="background-color:#fff">
            <?php $tab = (isset($_GET['tab'])) ? $_GET['tab'] : null; ?> 
            <ul class="nav nav-tabs">
				<?php if($user_rights_4_7["view_rights"]=="1"){ ?>
                <li class="<?php echo ($tab == 'cancel_sales' || $tab == '') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/orders?tab=cancel_sales'); ?>"><h4><?php echo translate('admin_rejected_order'); ?></h4></a></li>
				<?php } ?>
				<?php if($user_rights_4_8["view_rights"]=="1"){ ?>
                <li class="<?php echo ($tab == 'user_cancel_sales') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/orders?tab=user_cancel_sales'); ?>"><h4><?php echo translate('user_cancelled_order'); ?></h4></a></li>
				<?php } ?>
				<?php if($user_rights_4_9["view_rights"]=="1"){ ?>
                <li class="<?php echo ($tab == 'successful_sales') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/orders?tab=successful_sales'); ?>"><h4><?php echo translate('successful_sales'); ?></h4></a></li>     
				<?php } ?>
            </ul>
        </div>  
	
        <?php 
        if($_GET['tab'] == '' || $_GET['tab'] == 'cancel_sales') {
            ?>
        <div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="row"><div class="col-md-5">
					<h6 class="page-header text-overflow" ><?php echo translate('admin_rejected_order');?></h6>			
				</div>
				<div class="col-md-7"></div>
				</div>
                <!-- LIST -->
                <div class="tab-pane fade active in" id="list">
                
                </div>
			</div>
        </div>
	</div>
	
    <script>
		var base_url = '<?php echo base_url(); ?>'
		var user_type = 'admin';
		var module = 'cancel_sales';
		var list_cont_func = 'list';
		var dlt_cont_func = 'delete';
	</script>

    <?php } else if($_GET['tab'] == 'user_cancel_sales') { ?>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="row"><div class="col-md-5">
					<h6 class="page-header text-overflow" ><?php echo translate('user_cancelled_order');?></h6>			
				</div>
				<div class="col-md-7"></div>
				</div>
                <!-- LIST -->
                <div class="tab-pane fade active in" id="list">
                
                </div>
			</div>
        </div>
	</div>

    <script>
		var base_url = '<?php echo base_url(); ?>'
		var user_type = 'admin';
		var module = 'user_cancel_sales';
		var list_cont_func = 'list';
		var dlt_cont_func = 'delete';
	</script>

        <?php }  else if ($_GET['tab'] == 'successful_sales') { ?>

	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="row"><div class="col-md-5">
					<h6 class="page-header text-overflow" ><?php echo translate('successful_sales');?></h6>			
				</div>
				<div class="col-md-7"></div>
				</div>
                <!-- LIST -->
                <div class="tab-pane fade active in" id="list">
                
                </div>
			</div>
        </div>
	</div>

    <script>
		var base_url = '<?php echo base_url(); ?>'
		var user_type = 'admin';
		var module = 'successful_sales';
		var list_cont_func = 'list';
		var dlt_cont_func = 'delete';
	</script>

            <?php } ?>

</div>