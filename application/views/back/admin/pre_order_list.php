<?php
$edit_rights=$user_rights_28_0['edit_rights'];
$delete_rights=$user_rights_28_0['delete_rights'];
?>
<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped" data-export-types="['excel','pdf']" data-show-export="true"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('start_date');?></th>
                    <th><?php echo translate('end_date');?></th>
					<th><?php echo translate('description');?></th>
                    <th><?php echo translate('status');?></th>
					<?php if(($edit_rights=='1') || ($delete_rights=='1')){ ?>
					<th class="text-right"><?php echo translate('options');?></th>
					<?php } ?>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i = 0;
            	foreach($all_categories as $row){
            		$i++;
					$startDateTime = $row['start_date'];
					$formattedDateTime = date('Y-m-d h:i A', strtotime($startDateTime));
					$endDateTime = $row['end_date'];
					$formattedEndDateTime = date('Y-m-d h:i A', strtotime($endDateTime));
			?>
			<tr>
				<td><?php echo $i; ?></td>
                <td><?php echo  $formattedDateTime; ?></td>
                <td><?php echo $formattedEndDateTime; ?></td>
				<td><?php echo $row['description']; ?></td>
                <td><input class='aiz_switchery' type="checkbox" data-set='status_set' data-id='<?php echo $row['id']; ?>' data-tm='<?php echo translate('pre_order_enabled'); ?>' data-fm='<?php echo translate('pre_order_disabled'); ?>' <?php if ($row['status'] == 'ok') { ?>checked<?php } ?> /></td>
                
				
				<?php if(($edit_rights=='1') || ($delete_rights=='1')){ ?>
				<td class="text-right">
					<?php if($edit_rights=='1'){ ?>
					<a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                    	onclick="ajax_modal('edit','<?php echo translate('edit_pre_order'); ?>','<?php echo translate('successfully_edited!'); ?>','pre_order_edit','<?php echo $row['id']; ?>')" 
                        	data-original-title="Edit" data-container="body">
                            	<?php echo translate('edit');?>
                    </a>
					<?php } ?>
					<?php if($delete_rights=='1'){ ?>
					<a onclick="delete_confirm('<?php echo $row['id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" 
                    	data-original-title="Delete" data-container="body">
					  
                        	<?php echo translate('delete');?>
                        	

                    </a>
					<?php } ?>
				</td>
				<?php } ?>
			</tr>
            <?php
            	}
			?>
			</tbody>
		</table>
	</div>
           
	<div id='export-div'>
		<h1 style="display:none;"><?php echo translate('category'); ?></h1>
		<table id="export-table" data-name='category' data-orientation='p' style="display:none;">
		<colgroup>
            <col width="100">
            <col width="300">
            <col width="300">
            
            
        </colgroup>
				<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('name');?></th>
                    <th><?php echo translate('banner');?></th>
					
				</tr>
				</thead>
					
				<tbody >
				<?php
				$i = 0;
            	foreach($all_categories as $row){
            		$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
                <td><?php echo $row['category_name']; ?></td>
                
				<td>
                    <?php
						if(file_exists('uploads/category_image/'.$row['banner'])){
					?>
					<img class="img-md" width="20" height="50" src="<?php echo base_url(); ?>uploads/category_image/<?php echo $row['banner']; ?>" />  
					<?php
						} else {
					?>
					<img class="img-md" width="20" height="20" src="<?php echo base_url(); ?>uploads/category_image/default.jpg" />
					<?php
						}
					?> 
               	</td>
               	
				
			</tr>
            <?php
            	}
			?>
				</tbody>
		</table>
	</div>

    <script type="text/javascript">
    function set_switchery() {
        $(".aiz_switchery").each(function() {
            new Switchery($(this).get(0), {
                color: 'rgb(100, 189, 99)',
                secondaryColor: '#cc2424',
                jackSecondaryColor: '#c8ff77'
            });

            var changeCheckbox = $(this).get(0);
            var false_msg = $(this).data('fm');
            var true_msg = $(this).data('tm');
            changeCheckbox.onchange = function() {
                $.ajax({
                    url: base_url + 'index.php/admin/pre_order/' + $(this).data('set') + '/' + $(this).data('id') + '/' + changeCheckbox.checked,
                    success: function(result) {
                        if (changeCheckbox.checked == true) {
                            $.activeitNoty({
                                type: 'success',
                                icon: 'fa fa-check',
                                message: true_msg,
                                container: 'floating',
                                timer: 3000
                            });
                            sound('published');
                        } else {
                            $.activeitNoty({
                                type: 'danger',
                                icon: 'fa fa-check',
                                message: false_msg,
                                container: 'floating',
                                timer: 3000
                            });
                            sound('unpublished');
                        }
                    }
                });
            };
        });
    }

    $(document).ready(function() {
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({
            width: '100%'
        });
    });
</script>