<?php
$edit_rights=$user_rights_37_0['edit_rights'];
$delete_rights=$user_rights_37_0['delete_rights'];
?>
<div class="panel-body" id="demo_s">
        <table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >
            <thead>
                <tr>
                    <th><?php echo translate('no'); ?></th>
                    <th><?php echo translate('name'); ?></th>
                    <th><?php echo translate('email'); ?></th>
                    <th><?php echo translate('phone'); ?></th>
                    <th><?php echo translate('role'); ?></th>
                    <!-- <th class="text-right"><?php echo translate('options'); ?></th> -->
                    <th><?php echo translate('status'); ?></th>
                </tr>
            </thead>
            <tbody >
            <?php
				$i = 0;
                foreach($all_admins as $row){
					$i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td>
                        <div class="btn-group"> #<?php echo $row['name']; ?>
                            <?php if(($edit_rights=='1') || ($delete_rights=='1')){ ?>
                            <button class="btn btn-default" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="fa fa-chevron-circle-down pull-right" aria-hidden="true"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <?php if($edit_rights=='1'){ ?>
                                <li><a style="color:white;width:100%;text-align:left;" class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" onclick="set_staff_password('<?php echo $row['admin_id']; ?>','<?php echo $row['email']; ?>')" data-original-title="Set Password" data-container="body"><?php echo translate('set_password');?></a></li>
                                <li>
                                    <a style="color:white;width:100%;text-align:left;" class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" onclick="ajax_modal('edit','<?php echo translate('edit_admin'); ?>','<?php echo translate('successfully_edited!'); ?>','admin_edit','<?php echo $row['admin_id']; ?>')" data-original-title="Edit" data-container="body"><?php echo translate('edit');?>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if($delete_rights=='1'){ ?>
                                <li>
                                    <a style="color:white;width:100%;text-align:left;" onclick="delete_confirm('<?php echo $row['admin_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip"data-original-title="Delete" data-container="body"><?php echo translate('delete');?>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                            <?php } ?>
                        </div>
                    </td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('role',$row['role']); ?></td>
                    <!-- <td class="text-right">
                        <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                            onclick="ajax_modal('edit','<?php echo translate('edit_admin'); ?>','<?php echo translate('successfully_edited!'); ?>','admin_edit','<?php echo $row['admin_id']; ?>')" 
                                data-original-title="Edit" data-container="body">
                                    <?php echo translate('edit');?>
                        </a>
                        <?php if($row['admin_id'] !== '1'){ ?>
                        <a onclick="delete_confirm('<?php echo $row['admin_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" 
                        	class="btn btn-danger btn-xs btn-labeled fa fa-trash" 
                            	data-toggle="tooltip"data-original-title="Delete" data-container="body">
                                	<?php echo translate('delete');?>
                        </a>
                        <?php } ?>
                    </td> -->
                    <td><input class='aiz_switchery' type="checkbox" data-set='suspend_set' data-id="<?php echo $row['admin_id']; ?>" data-tm='<?php echo translate('Admin Enabled'); ?>' data-fm='<?php echo translate('Admin Suspended'); ?>' <?php if($row['status'] == 'ok'){ ?>checked<?php } ?> /></td>
                </tr>
            <?php
                }
            ?>
            </tbody>
        </table>
    </div>
           
    <div id='export-div'>
        <h1 style="display:none;"><?php echo translate('staffs');?></h1>
        <table id="export-table" data-name='staffs' data-orientation='l' style="display:none;">
                <thead>
                    <tr>
                        <th><?php echo translate('no'); ?></th>
                    <th><?php echo translate('name'); ?></th>
                    <th><?php echo translate('email'); ?></th>
                    <th><?php echo translate('phone'); ?></th>
                    <th><?php echo translate('role'); ?></th>
                    </tr>
                </thead>
                    
                <tbody >
                <?php
                    $i = 0;
                    foreach($all_admins as $row){
                        $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('role',$row['role']); ?></td>
                </tr>
                <?php
                    }
                ?>
                </tbody>
        </table>
    </div>
 
<script>
    function set_staff_password(admin_id,email){
        $.ajax({url: base_url+'index.php/admin/admins/set_password',
        type: "POST",
        data:{'admin_id':admin_id,'email':email},
        success: function(result){
            if(result!="Failed to set password"){
                $.activeitNoty({
                    type: 'success',
                    icon : 'fa fa-check',
                    message : result,
                    container : 'floating',
                    timer : 5000
                });
                sound('Admin Enabled');
            } else {
                $.activeitNoty({
                    type: 'danger',
                    icon : 'fa fa-check',
                    message : result,
                    container : 'floating',
                    timer : 3000
                });
                sound('Admin Suspended');
            }
        }});
    }
  function set_switchery1(){
		$(".aiz_switchery").each(function(){
			new Switchery($(this).get(0), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});

			var changeCheckbox = $(this).get(0);
			var false_msg = $(this).data('fm');
			var true_msg = $(this).data('tm');
			changeCheckbox.onchange = function() {
				$.ajax({url: base_url+'index.php/admin/admins/'+$(this).data('set')+'/'+$(this).data('id')+'/'+changeCheckbox.checked, 
				success: function(result){	
				  if(changeCheckbox.checked == true){
					$.activeitNoty({
						type: 'success',
						icon : 'fa fa-check',
						message : true_msg,
						container : 'floating',
						timer : 3000
					});
					sound('Admin Enabled');
				  } else {
					$.activeitNoty({
						type: 'danger',
						icon : 'fa fa-check',
						message : false_msg,
						container : 'floating',
						timer : 3000
					});
					sound('Admin Suspended');
				  }
				}});
			};
		});
	}

$(document).ready(function(){
	set_switchery1();
	$('#demo-table').bootstrapTable({

        }).on('all.bs.table', function(e, name, args) {
            //alert('1');
            //set_switchery();
        }).on('click-row.bs.table', function(e, row, $element) {

        }).on('dbl-click-row.bs.table', function(e, row, $element) {

        }).on('sort.bs.table', function(e, name, order) {

        }).on('check.bs.table', function(e, row) {

        }).on('uncheck.bs.table', function(e, row) {

        }).on('check-all.bs.table', function(e) {

        }).on('uncheck-all.bs.table', function(e) {

        }).on('load-success.bs.table', function(e, data) {
            set_switchery1();
        }).on('load-error.bs.table', function(e, status) {

        }).on('column-switch.bs.table', function(e, field, checked) {

        }).on('page-change.bs.table', function(e, size, number) {
            //alert('1');
            //set_switchery();
        }).on('search.bs.table', function(e, text) {
			set_switchery1();
        });
});
</script>