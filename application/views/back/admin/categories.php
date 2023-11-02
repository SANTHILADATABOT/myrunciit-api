<div id="content-container">
    <div class="content-wrapper-before"></div>
        <div style="background-color:#FFF">
            <?php $tab = (isset($_GET['tab'])) ? $_GET['tab'] : null; ?> 
            <ul class="nav nav-tabs">
				<?php if($user_rights_21["view_rights"]=="1"){ ?>
				<li class="<?php echo ($tab == 'brands' || $tab == '') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/categories?tab=brands'); ?>"><h4><?php echo translate('brands'); ?></h4></a></li>
				<?php } ?>
				<?php if($user_rights_22["view_rights"]=="1"){ ?>
                <li class="<?php echo ($tab == 'category') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/categories?tab=category'); ?>"><h4><?php echo translate('category'); ?></h4></a></li>
				<?php } ?>
				<?php if($user_rights_23["view_rights"]=="1"){ ?>
                <li class="<?php echo ($tab == 'sub-category') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/categories?tab=sub-category'); ?>"><h4><?php echo translate('sub-category'); ?></h4></a></li>       
				<?php } ?>         
            </ul>
        </div>  

        <?php 
        if($_GET['tab'] == 'category') {
            ?>
        
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
					<div class="row">
						<div class="col-md-10">
						<h6 class="page-header text-overflow" ><?php echo translate('manage_categories_(_physical_product_)');?></h6>
						</div>
						<div class="col-md-2">
							<?php if($user_rights_22["add_rights"]=="1"){ ?>
						<button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right mar-rgt" 
						onclick="ajax_modal('add','<?php echo translate('add_category_(_physical_product_)'); ?>','<?php echo translate('successfully_added!'); ?>','category_add','')">
							<?php echo translate('create_category');?>
								</button>
								<?php } ?>
						</div>
					</div>
					<br>
                    <div class="tab-pane fade active in" 
                    	id="list" style="border:1px solid #ebebeb; border-radius:4px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'category';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
</script>
    <?php } else if($_GET['tab'] == 'sub-category') { ?>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
				<div class="row">
					<div class="col-md-10">
					<h6 class="page-header text-overflow" ><?php echo translate('manage_sub_categories_(_physical_product_)');?></h6>
					</div>
					<div class="col-md-2">
							<?php if($user_rights_23["add_rights"]=="1"){ ?>
					<button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right" 
                            onclick="ajax_modal('add','<?php echo translate('add_sub-category_(_physical_product_)'); ?>','<?php echo translate('successfully_added!'); ?>','sub_category_add','')">
                                <?php echo translate('create_sub_category');?>
                                    </button>
								<?php } ?>
					</div>
                    <!-- LIST -->
                    <div class="tab-pane fade active in" id="list" style="border:1px solid #ebebeb; border-radius:4px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'sub_category';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
</script>
        <?php }  else if($_GET['tab'] == '' || $_GET['tab'] == 'brands') { ?>

	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
				<div class="row">
					<div class="col-md-10">
					<h6 class="page-header text-overflow" ><?php echo translate('manage_brands_(_physical_product_)');?></h6>
					</div>
					<div class="col-md-2">
							<?php if($user_rights_21["add_rights"]=="1"){ ?>
					<button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right" 
                        	onclick="ajax_modal('add','<?php echo translate('add_brand_(_physical_product_)'); ?>','<?php echo translate('successfully_added!');?>','brand_add','')">
								<?php echo translate('create_brand');?>
						</button>
							<?php } ?>
					</div>
					<div class="tab-pane fade active in" id="list" 
                    	style="border:1px solid #ebebeb; 
                        	border-radius:4px;">
					</div>
				</div>
			</div>
		</div>
	</div>
    <script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'brand';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
</script>
            <?php } ?>

</div>