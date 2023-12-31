<div id="content-container">
<div class="content-wrapper-before"></div>

	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
				
				<div class="row">
				<div class="col-md-7">
				<h1 class="page-header text-overflow" ><?php echo translate('Manage_roles');?></h1>
				</div>
					<div class="col-md-5">

						<?php if($user_rights_39_0['add_rights']=='1'){ ?>
						<button class="btn btn-primary btn-labeled fa fa-plus-circle add_pro_btn pull-right mar-rgt" onclick="ajax_set_full('add','<?php echo translate('add_role'); ?>','<?php echo translate('successfully_added!'); ?>','role_add',''); proceed('to_list');"><?php echo translate('create_role');?>
                        </button>
						<?php } ?>
                        <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" 
                            style="display:none;"  onclick="ajax_set_list();  proceed('to_add');"><?php echo translate('back_to_role_list');?>
                        </button>
					</div>
					</div>
					
					<br>
                    <!-- LIST -->
                    <div class="tab-pane fade active in" id="list" style="border:1px solid #ebebeb; border-radius:4px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'role';
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
