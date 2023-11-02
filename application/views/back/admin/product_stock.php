<div id="content-container">
    <div class="content-wrapper-before"></div>
        <div style="background-color:#FFF">
            <?php $tab = (isset($_GET['tab'])) ? $_GET['tab'] : null; ?> 
            <ul class="nav nav-tabs">
				<?php if($user_rights_3_4["view_rights"]=="1"){ ?>
                <li class="<?php echo ($tab == 'product' || $tab == '') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/product_stock?tab=product'); ?>"><h4><?php echo translate('all_products'); ?></h4></a></li>
				<?php } ?>
				<?php if($user_rights_3_5["view_rights"]=="1"){ ?>
                <li class="<?php echo ($tab == 'product_bulk_upload') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/product_stock?tab=product_bulk_upload'); ?>"><h4><?php echo translate('product_bulk_upload'); ?></h4></a></li>
				<?php } ?>
				<?php //if($user_rights_3_6["view_rights"]=="1"){ ?>
                <!-- <li class="<?php echo ($tab == 'stock') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/product_stock?tab=stock'); ?>"><h4><?php echo translate('product_stock'); ?></h4></a></li> -->
				<?php //} ?>
                <?php if($user_rights_3_6["view_rights"]=="1"){ ?>
                <li class="<?php echo ($tab == 'deals') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/product_stock?tab=deals'); ?>"><h4><?php echo translate('deals'); ?></h4></a></li>
				<?php } ?>
            </ul>
        </div>  

        <?php 
        if($_GET['tab'] == '' || $_GET['tab'] == 'product') {
            ?>
        <div class="tab-base">
            <div class="panel">
                <div class="panel-body">
                    <div class="tab-content">
					<div class="row">
						<div class="col-md-4">
						<h6 class="page-header text-overflow" ><?php echo translate('manage_product');?></h6>
						</div>
						<div class="col-md-8">
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
                            <?php if($user_rights_3_4["add_rights"]=="1"){ ?>
                            <button class="btn btn-primary btn-labeled fa fa-plus-circle add_pro_btn pull-right" 
                                onclick="ajax_set_full('add','<?php echo translate('add_product'); ?>','<?php echo translate('successfully_added!'); ?>','product_add',''); proceed('to_list');"><?php echo translate('create_product');?>
                            </button>
                            <?php } ?>
                            <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" 
                                style="display:none;"  onclick="ajax_set_list();  proceed('to_add');"><?php echo translate('back_to_product_list');?>
                            </button>
						</div>
					</div>
                    <!-- LIST -->
                    <div class="tab-pane fade active in" id="list" style="border:1px solid #ebebeb; border-radius:4px;">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <?php } else if($_GET['tab'] == 'product_bulk_upload') { ?>
    <div class="tab-base">
        <div class="panel">
            <div class="panel-body">
                <div class="tab-content">
				<div class="row">
						<div class="col-md-10">
						<h6 class="page-header text-overflow" ><?php echo translate('Product bulk upload');?></h6>
						</div>						
					</div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="">
                                <div class="panel-heading">
                                    <h3>Instructions</h3>
                                </div>
                                <div class="panel-body">
                                    <ol>
                                        <li>
                                            Download the skeleton file and fill it with data.
                                        </li>
                                        <li>
                                            You can download the example file to understand how the data must be filled
                                        </li>
                                        <li>
                                            Once you have downloaded and filled the skeleton file , upload it in the form below and
                                            submit.
                                        </li>
                                        <li>
                                            Vendor, Brand, Category, Sub Category should already exist in their respective tables.
                                        </li>
                                        <li>
                                            Products should be uploaded successfully.
                                        </li>
                                    </ol>

                                    <div class="pdd1">
                                        <a class="btn btn-sm btn-primary btn-dark" target="_blank" download href="<?php echo base_url() . "uploads/bulk_skeletons/product_sample.xlsx" ?>"><?php echo translate('Download product bulk upload skeleton file'); ?></a>
                                        <a style="display:none;" class="btn btn-sm btn-primary btn-dark" target="_blank" download
                                            href="<?php echo base_url() . "uploads/bulk_skeletons/product.xlsx" ?>"><?php echo translate('Download product bulk upload skeleton file'); ?></a>
                                        <a style="display:none;" class="btn btn-sm btn-primary" target="_blank" download
                                            href="<?php echo base_url() . "uploads/bulk_skeletons/product_example.xlsx" ?>"><?php echo translate('Download product bulk upload example file'); ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="hidden">
                                <div class="">
                                    <h3>More Instructions</h3>
                                </div>
                                <div class="panel-body">
                                    <ol>
                                        <li>
                                            Category,Sub category and Brand should be in <code>numerical ids</code>.Click the <code>respected modals/pop-ups</code> to see the related ids
                                        </li>
                                        <li>
                                            Tax and Discount can be in percentage.For example if the discount is 15, write only
                                            15.If the discount is 15 percent, write 15%.Do the same for tax.
                                        </li>
                                        <li>
                                            Tags are comma separated.If you have tags like "baby" and "food" write
                                            <code>baby,food</code>.
                                        </li>
                                        <li>
                                            Image Urls are comma separated.If you have many image urls write like this: <code>http://imagescource/image001.jpg,http://anotherimagescource/image005.jpg</code>
                                        </li>
                                        <li>To publish automatically, fill the "published" column with <code>yes</code></li>
                                        <li>
                                            Products should be uploaded successfully.
                                        </li>
                                    </ol>

                                    <div>

                                        <button data-target="#product_category" type="button" class="btn btn-primary"
                                                data-toggle="modal"><?php echo translate("Category ID List") ?></button>
                                        <button data-target="#product_sub_category" type="button" class="btn btn-primary"
                                                data-toggle="modal"><?php echo translate("Sub Category ID List") ?></button>
                                        <button data-target="#product_brand" type="button" class="btn btn-primary"
                                                data-toggle="modal"><?php echo translate("Brand ID List") ?></button>

                                        <div id="product_category" class="modal fade bd-example-modal-lg" tabindex="-1"
                                                role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h5 class="modal-title" id=""><?php echo translate('Category IDs')?></h5>
                                                    </div>
                                                    <div class="modal-body" style="overflow:scroll; max-height:400px;">
                                                        <?php if(!empty($physical_categories)){ ?>
                                                            <table class="table table-bordered table-responsive dataTable">
                                                                <tr>
                                                                    <th><?php echo translate('Category ID')?></th>
                                                                    <th><?php echo translate('Category Name')?></th>
                                                                </tr>
                                                                <?php foreach($physical_categories as $physical_category){ ?>
                                                                    <tr>
                                                                        <td><?php echo $physical_category['category_id']?></td>
                                                                        <td><?php echo $physical_category['category_name']?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="product_sub_category" class="modal fade bd-example-modal-lg" tabindex="-1"
                                                role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h5 class="modal-title" id=""><?php echo translate('Sub Category IDs with Category ID')?></h5>
                                                    </div>
                                                    <div class="modal-body" style="overflow:scroll; max-height:400px;">
                                                        <?php if(!empty($physical_sub_categories)){ ?>
                                                            <table class="table table-bordered table-responsive dataTable">
                                                                <tr>
                                                                    <th><?php echo translate('Sub Category ID')?></th>
                                                                    <th><?php echo translate('Sub Category Name')?></th>
                                                                    <th><?php echo translate('Category ID')?></th>
                                                                </tr>
                                                                <?php foreach($physical_sub_categories as $physical_sub_category){ ?>
                                                                    <tr>
                                                                        <td><?php echo $physical_sub_category['sub_category_id']?></td>
                                                                        <td><?php echo $physical_sub_category['sub_category_name']?></td>
                                                                        <td><?php echo $physical_sub_category['category']?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="product_brand" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                                                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h5 class="modal-title" id=""><?php echo translate('Brand IDs')?></h5>

                                                    </div>
                                                    <div class="modal-body" style="overflow:scroll; max-height:400px;">
                                                        <?php if(!empty($brands)){ ?>
                                                            <table class="table table-bordered table-responsive dataTable">
                                                                <tr>
                                                                    <th><?php echo translate('Brand ID')?></th>
                                                                    <th><?php echo translate('Brand Name')?></th>
                                                                </tr>
                                                                <?php foreach($brands as $brand){ ?>
                                                                    <tr>
                                                                        <td><?php echo $brand['brand_id']?></td>
                                                                        <td><?php echo $brand['name']?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
							<br />
                            <div class="mb-0" style="">
                                <div class="panel-heading1">
                                    <h3><?php echo translate("Upload your products") ?></h3>
                                </div>
                                <div class="panel-body pdd1">
                                    <?php
                                    echo form_open(base_url() . 'index.php/admin/product_bulk_upload_save', array(
                                        'class' => 'form',
                                        'method' => 'post',
                                        'id' => '',
                                        'enctype' => 'multipart/form-data'
                                    ));
                                    ?>
                                    <div class="form-group">
                                        <span class="btn btn-default btn-file">
                                            <?php echo translate("Choose File") ?>
                                            <input type="file" class="form-control" name="bulk_file" accept=".xlsx,.xls,.csv">
                                        </span>
                                        <div><label>Accepted filetypes (xlsx, xls, csv)</label></div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-mint btn-labeled fa fa-upload" type="submit"><?php echo translate("Upload Products") ?></button>
                                    </div>
                                    <?php echo form_close() ?>


                                    <?php if ($this->session->flashdata('success')) { ?>
                                        <div class="alert alert-success alert-dismissible show" role="alert">
                                            <?php echo $this->session->flashdata('success') ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php } ?>
                                    <?php if ($this->session->flashdata('error')) { ?>
                                        <div class="alert alert-danger alert-dismissible show" role="alert">
                                            <?php echo $this->session->flashdata('error') ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
    var base_url = '<?php echo base_url(); ?>';
    var timer = '<?php $this->benchmark->mark_time(); ?>';
    var user_type = 'admin';
    var module = 'product_bulk_upload';
    var list_cont_func = '';
    var dlt_cont_func = '';

    document.addEventListener('DOMContentLoaded',function(e){

    })

</script>
        <?php }  else if($_GET['tab'] == 'stock') { ?>

	<!-- <div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
				<div class="row">
						<div class="col-md-4">
						<h6 class="page-header text-overflow" ><?php echo translate('manage_product_stock');?></h6>
						</div>
						<div class="col-md-8">-->
						<!-- <button class="btn btn-dark btn-labeled fa fa-minus-square pull-right" 
                    	onclick="ajax_modal('destroy','<?php echo translate('destroy_product_entry'); ?>','<?php echo translate('add_stock_entry_taken!'); ?>','stock_destroy','')">
                        	<?php echo translate('destroy');?>
                            	</button>            -->
                                <?php if($user_rights_3_6["add_rights"]=="1"){ ?>
					<!--<button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right mar-rgt" 
                    	onclick="ajax_modal('add','<?php echo translate('add_product_stock'); ?>','<?php echo translate('destroy_entry_taken!'); ?>', 'stock_add', '' )">
							<?php echo translate('create_stock');?>
                            	</button>
                                <?php } ?>
						</div>
					</div>-->
				<!-- LIST -->
				<!--<div class="tab-pane fade active in" id="list" style="border:1px solid #ebebeb; border-radius:4px;">
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var base_url 		= '<?php echo base_url(); ?>'
	var user_type 		= 'admin';
	var module 			= 'stock';
	var list_cont_func  = 'list';
	var dlt_cont_func 	= 'delete';
</script> -->
            <?php }  else if($_GET['tab'] == 'deals') { ?>
                <div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
				<div class="row">
						<div class="col-md-4">
						<h6 class="page-header text-overflow" ><?php echo translate('deals');?></h6>
						</div>
						<div class="col-md-8">
						<!-- <button class="btn btn-dark btn-labeled fa fa-minus-square pull-right" 
                    	onclick="ajax_modal('destroy','<?php echo translate('destroy_product_entry'); ?>','<?php echo translate('add_stock_entry_taken!'); ?>','stock_destroy','')">
                        	<?php echo translate('destroy');?>
                            	</button>            -->
                                <?php if($user_rights_3_15["add_rights"]=="1"){ ?>
					<!-- <button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right mar-rgt" 
                    	onclick="ajax_modal('add','<?php echo translate('add_product_stock'); ?>','<?php echo translate('destroy_entry_taken!'); ?>', 'stock_add', '' )">
							<?php echo translate('create_stock');?>
                            	</button> -->
                                <?php } ?>
						</div>
					</div><br /><br />
				<!-- LIST -->
				<div class="tab-pane fade active in" id="list" style="border:1px solid #ebebeb; border-radius:4px;">
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var base_url 		= '<?php echo base_url(); ?>'
	var user_type 		= 'admin';
	var module 			= 'product';
	var list_cont_func  = 'today_list';
	var dlt_cont_func 	= 'delete';
</script>
            <?php } ?>

</div>
<span id="prod" style="display:none;"></span>