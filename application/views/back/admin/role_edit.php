	<div class="tab-pane fade active in" id="edit">
		<?php
			echo form_open(base_url() . 'index.php/admin/role/update/' . $role_data[0]['role_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'role_edit'
			));
		?>
            <div class="panel-body">
                <div class="form-group margin-top-15">
                    <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('name'); ?></label>
                    <div class="col-sm-6">
                        <input type="text" name="name" id="demo-hor-1" value="<?php echo $role_data[0]['name']; ?>" class="form-control required" placeholder="<?php echo translate('name'); ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('description'); ?></label>
                    <div class="col-sm-6">
                        <textarea name="description" class="form-control required" placeholder="<?php echo translate('description'); ?>" ><?php echo $role_data[0]['description']; ?></textarea>
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
                                <?php if($menu_main_list1['have_sub']=="0"){
                                    $values=$menu_permissions_list[$id1]["0"];
                                    ?>
                                <td style="width:20px;"><input id="perm1_<?php echo $id1; ?>_0" value="perm1_<?php echo $id1; ?>_0" class='sw_1' type="checkbox" name="permission1[]" data-id='perm1_<?php echo $id1; ?>_0' <?php if($values['view_rights']=="1"){echo " checked";} ?> /></td>
                                <td style="width:20px;"><input id="perm2_<?php echo $id1; ?>_0" value="perm2_<?php echo $id1; ?>_0" class='sw_1' type="checkbox" name="permission2[]" data-id='perm2_<?php echo $id1; ?>_0' <?php if($values['add_rights']=="1"){echo " checked";} ?> /></td>
                                <td style="width:20px;"><input id="perm3_<?php echo $id1; ?>_0" value="perm3_<?php echo $id1; ?>_0" class='sw_1' type="checkbox" name="permission3[]" data-id='perm3_<?php echo $id1; ?>_0' <?php if($values['edit_rights']=="1"){echo " checked";} ?> /></td>
                                <td style="width:20px;"><input id="perm4_<?php echo $id1; ?>_0" value="perm4_<?php echo $id1; ?>_0" class='sw_1' type="checkbox" name="permission4[]" data-id='perm4_<?php echo $id1; ?>_0' <?php if($values['delete_rights']=="1"){echo " checked";} ?> /></td>
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
                                    $values=$menu_permissions_list[$id1][$id2];
                                    ?>
                                    <tr>
                                        <td style="width:10px;"><?php echo range('A', 'Z')[$i2];$i2++; ?>)</td>
                                        <td><?php echo ucfirst($menu_sub_list2['sub_screen_name']); ?></td>
                                        <td style="width:20px;"><input id="perm1_<?php echo $id1; ?>_<?php echo $id2; ?>" value="perm1_<?php echo $id1; ?>_<?php echo $id2; ?>" class='sw_1 perm1_<?php echo $id1; ?>' type="checkbox" name="permission1[]" data-id='perm1_<?php echo $id1; ?>_<?php echo $id2; ?>' <?php if($values['view_rights']=="1"){echo " checked";} ?> /></td>
                                        <td style="width:20px;"><input id="perm2_<?php echo $id1; ?>_<?php echo $id2; ?>" value="perm2_<?php echo $id1; ?>_<?php echo $id2; ?>" class='sw_1 perm2_<?php echo $id1; ?>' type="checkbox" name="permission2[]" data-id='perm2_<?php echo $id1; ?>_<?php echo $id2; ?>' <?php if($values['add_rights']=="1"){echo " checked";} ?> /></td>
                                        <td style="width:20px;"><input id="perm3_<?php echo $id1; ?>_<?php echo $id2; ?>" value="perm3_<?php echo $id1; ?>_<?php echo $id2; ?>" class='sw_1 perm3_<?php echo $id1; ?>' type="checkbox" name="permission3[]" data-id='perm3_<?php echo $id1; ?>_<?php echo $id2; ?>' <?php if($values['edit_rights']=="1"){echo " checked";} ?> /></td>
                                        <td style="width:20px;"><input id="perm4_<?php echo $id1; ?>_<?php echo $id2; ?>" value="perm4_<?php echo $id1; ?>_<?php echo $id2; ?>" class='sw_1 perm4_<?php echo $id1; ?>' type="checkbox" name="permission4[]" data-id='perm4_<?php echo $id1; ?>_<?php echo $id2; ?>' <?php if($values['delete_rights']=="1"){echo " checked";} ?> /></td>
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
                            onclick="ajax_set_full('edit','<?php echo translate('edit_role'); ?>','<?php echo translate('successfully_edited!'); ?>','role_edit','<?php echo $role_data[0]['role_id']; ?>')">
                                <?php echo translate('reset');?>
                        </span>
                    </div>
                    
                    <div class="col-md-1">
                        <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right" 
                            onclick="form_submit('role_edit','<?php echo translate('successfully_edited!'); ?>')" >
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