<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/role/do_add/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'role_add'
		));
	?>
        <div class="panel-body">
            <div class="form-group margin-top-15">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('name'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="name" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('name'); ?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('description'); ?></label>
                <div class="col-sm-6">
                    <textarea name="description" class="form-control required" placeholder="<?php echo translate('description'); ?>" ></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-3"><?php echo translate('permissions'); ?></label>
                <div class="col-sm-8">
                	<table class="table table-striped" style="width:100%;">
                        <tr>
                            <th style="width:10px;"></th>
                            <th></th>
                            <th style="width:20px;">View</th>
                            <th style="width:20px;">Add</th>
                            <th style="width:20px;">Edit</th>
                            <th style="width:20px;">Delete</th>
                        </tr>
                    <?php
                    $menu_sub_list1=[];
                    for($i1=0;$i1<count($menu_sub_list);$i1++)
                    {$menu_sub_list1[$menu_sub_list[$i1]['main_menu_id']][$menu_sub_list[$i1]['id']]=$menu_sub_list[$i1];}
                    $i1=1;
                    foreach($menu_main_list as $menu_main_list1)
                    {
                        $id1=$menu_main_list1['id'];
                        ?>
                        <tr>
                            <td style="width:10px;"><?php echo $i1;$i1++; ?>.</td>
                            <td><?php echo ucfirst($menu_main_list1['screen_name']); ?></td>
                            <?php if($menu_main_list1['have_sub']=="0"){ ?>
                            <td style="width:20px;"><input id="perm1_<?php echo $id1; ?>_0" class='sw_1' type="checkbox" value="perm1_<?php echo $id1; ?>_0" name="permission1[]" data-id='perm1_<?php echo $id1; ?>_0' /></td>
                            <td style="width:20px;"><input id="perm2_<?php echo $id1; ?>_0" class='sw_1' type="checkbox" value="perm2_<?php echo $id1; ?>_0" name="permission2[]" data-id='perm2_<?php echo $id1; ?>_0' /></td>
                            <td style="width:20px;"><input id="perm3_<?php echo $id1; ?>_0" class='sw_1' type="checkbox" value="perm3_<?php echo $id1; ?>_0" name="permission3[]" data-id='perm3_<?php echo $id1; ?>_0' /></td>
                            <td style="width:20px;"><input id="perm4_<?php echo $id1; ?>_0" class='sw_1' type="checkbox" value="perm4_<?php echo $id1; ?>_0" name="permission4[]" data-id='perm4_<?php echo $id1; ?>_0' /></td>
                            <?php } ?>
                        </tr>
                        <?php if($menu_main_list1['have_sub']=="1"){ ?>
                        <tr>
                            <td colspan="6">
                            <table class="table table-striped" style="width:100%;">
                            <?php
                            $i2=0;
                            foreach($menu_sub_list1[$menu_main_list1['id']] as $menu_sub_list2)
                            {
                                $id2=$menu_sub_list2['id'];
                                ?>
                                <tr>
                                    <td style="width:10px;"><?php echo range('A', 'Z')[$i2];$i2++; ?>)</td>
                                    <td><?php echo ucfirst($menu_sub_list2['sub_screen_name']); ?></td>
                                    <td style="width:20px;"><input id="perm1_<?php echo $id1; ?>_<?php echo $id2; ?>" value="perm1_<?php echo $id1; ?>_<?php echo $id2; ?>" class='sw_1 perm1_<?php echo $id1; ?>' type="checkbox" name="permission1[]" data-id='perm1_<?php echo $id1; ?>_<?php echo $id2; ?>' /></td>
                                    <td style="width:20px;"><input id="perm2_<?php echo $id1; ?>_<?php echo $id2; ?>" value="perm2_<?php echo $id1; ?>_<?php echo $id2; ?>" class='sw_1 perm2_<?php echo $id1; ?>' type="checkbox" name="permission2[]" data-id='perm2_<?php echo $id1; ?>_<?php echo $id2; ?>' /></td>
                                    <td style="width:20px;"><input id="perm3_<?php echo $id1; ?>_<?php echo $id2; ?>" value="perm3_<?php echo $id1; ?>_<?php echo $id2; ?>" class='sw_1 perm3_<?php echo $id1; ?>' type="checkbox" name="permission3[]" data-id='perm3_<?php echo $id1; ?>_<?php echo $id2; ?>' /></td>
                                    <td style="width:20px;"><input id="perm4_<?php echo $id1; ?>_<?php echo $id2; ?>" value="perm4_<?php echo $id1; ?>_<?php echo $id2; ?>" class='sw_1 perm4_<?php echo $id1; ?>' type="checkbox" name="permission4[]" data-id='perm4_<?php echo $id1; ?>_<?php echo $id2; ?>' /></td>
                                </tr>
                            <?php
                            } ?>
                            </table>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php
                    } ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-11">
                    <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right" 
                        onclick="ajax_set_full('add','<?php echo translate('add_role'); ?>','<?php echo translate('successfully_added!'); ?>','role_add','')">
                        	<?php echo translate('reset');?>
                    </span>
                </div>
                
                <div class="col-md-1">
                    <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right" 
                    	onclick="form_submit('role_add')" >
                        	<?php echo translate('save');?>
                    </span>
                </div>
            </div>
        </div>
	</form>
</div>

<script>
	$(document).ready(function() {
		$("form").submit(function(e){
			return false;
		});
		$(".sw_1").each(function(){
			new Switchery(document.getElementById($(this).data('id')), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
		});
	});
</script>

<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'role';
	var list_cont_func = 'list/<?php if($name) { echo $name; } else { echo ""; } ?>/<?php if($description) { echo $description; } else { echo ""; } ?>/<?php if($permission1) { echo $permission1; } else { echo ""; } ?>/<?php if($permission2) { echo $permission2; } else { echo ""; } ?>/<?php if($permission3) { echo $permission3; } else { echo ""; } ?>/<?php if($permission4) { echo $permission4; } else { echo ""; } ?>';
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