<div id="content-container">
    <div class="content-wrapper-before"></div>
	<div style="background-color:#fff">
		<?php $tab = (isset($_GET['tab'])) ? $_GET['tab'] : null; ?> 
		<ul class="nav nav-tabs">
            <?php if($user_rights_18_13['view_rights']=='1'){ ?>
			<li class="<?php echo ($tab == 'blog_category' || $tab == '') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/blogs?tab=blog_category'); ?>"><h4><?php echo translate('blog_categories'); ?></h4></a></li>
            <?php } ?>
            <?php if($user_rights_18_14['view_rights']=='1'){ ?>
			<li class="<?php echo ($tab == 'blog') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/blogs?tab=blog'); ?>"><h4><?php echo translate('all_blogs'); ?></h4></a></li>
            <?php } ?>
		</ul>
	</div>
	<?php
	if($_GET['tab'] == '' || $_GET['tab'] == 'blog_category')
	{ ?>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="row"><div class="col-md-7">
					<h6 class="page-header text-overflow" ><?php echo translate('manage_blog_categories');?></h6>			
				</div>
				<div class="col-md-5">
				   <?php if($user_rights_18_13['add_rights']=='1'){ ?>
                    <button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right mar-rgt" onclick="ajax_modal('add','<?php echo translate('add_blog_category'); ?>','<?php echo translate('successfully_added!'); ?>','blog_category_add','')"><?php echo translate('create_blog_category');?></button>
                    <?php } ?>
                </div>
				</div>
                <br>
                <div class="tab-pane fade active in" id="list">
                </div>
			</div>
        </div>
	</div>
    <script>
        var base_url = '<?php echo base_url(); ?>'
        var user_type = 'admin';
        var module = 'blog_category';
        var list_cont_func = 'list';
        var dlt_cont_func = 'delete';
    </script>
    <?php } else if($_GET['tab'] == 'blog') { ?>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
					<div class="row">
						<div class="col-md-7">
							<h6 class="page-header text-overflow" ><?php echo translate('manage_blog');?></h6>
						</div>
						<div class="col-md-5">
                            <?php if($user_rights_18_14['add_rights']=='1'){ ?>
                            <button class="btn btn-primary btn-labeled fa fa-plus-circle add_pro_btn pull-right" onclick="ajax_set_full('add','<?php echo translate('add_blog'); ?>','<?php echo translate('successfully_added!'); ?>','blog_add',''); proceed('to_list');"><?php echo translate('create_blog');?></button>
                            <?php } ?>
                            <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" style="display:none;"  onclick="ajax_set_list();  proceed('to_add');"><?php echo translate('back_to_blog_list');?></button>
                        </div>
					 </div>
                        <!-- LIST 
                        <div class="tab-pane fade active in" id="list">
                        </div>-->
						
					<!-- LIST -->
					<div class="tab-pane fade active in" id="list">
					</div>
				  </div>
                </div>
			</div>
        </div>
	</div>
    <span id="prod"></span>
    <script>
        var base_url = '<?php echo base_url(); ?>'
        var user_type = 'admin';
        var module = 'blog';
        var list_cont_func = 'list';
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
	<?php } ?>
