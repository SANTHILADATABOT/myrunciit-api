<?php
$edit_rights=$user_rights_18_13['edit_rights'];
$delete_rights=$user_rights_18_13['delete_rights'];
?>
<div class="panel-body" id="demo_s">
	<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >
		<thead>
			<tr>
				<th><?php echo translate('no');?></th>
				<th><?php echo translate('name');?></th>
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
			<td>						
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="text-align: left;"><?php echo $row['name'];; ?></div>
					<?php if(($edit_rights=='1') || ($delete_rights=='1')){ ?>
                    <div class="btn-group">
                        <!-- <button class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> -->
                            <i class="fa fa-chevron-circle-down icon-default" aria-hidden="true" id="dropdownMenu<?php echo $i; ?>" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></i>
                        <!-- </button> -->
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu<?php echo $i; ?>">
						<?php if($edit_rights=='1'){ ?>
						<li><a style="color:white;width:100%;text-align:left;" class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" onclick="ajax_modal('edit','<?php echo translate('edit_blog_category'); ?>','<?php echo translate('successfully_edited!'); ?>','blog_category_edit','<?php echo $row['blog_category_id']; ?>')" data-original-title="Edit" data-container="body"><?php echo translate('edit');?></a></li>
						<?php } ?>
						<?php if($delete_rights=='1'){ ?>
                        <li><a style="color:white;width:100%;text-align:left;" onclick="delete_confirm('<?php echo $row['blog_category_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" data-original-title="Delete" data-container="body"><?php echo translate('delete');?></a></li>
						<?php } ?>
                        </ul>
                    </div>
					<?php } ?>
                </div>
            </td>
		</tr>
		<?php
			}
		?>
		</tbody>
	</table>
</div>
           
<div id='export-div'>
	<h1 style="display:none;"><?php echo translate('blog_category'); ?></h1>
	<table id="export-table" data-name='blog_category' data-orientation='p' style="display:none;">
		<thead>
			<tr>
				<th><?php echo translate('no');?></th>
				<th><?php echo translate('name');?></th>
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
			<td><?php echo $row['name']; ?></td>
		</tr>
		<?php
			}
		?>
		</tbody>
	</table>
</div>