<?php
$edit_rights=$user_rights_6_0['edit_rights'];
$delete_rights=$user_rights_6_0['delete_rights'];
?>
<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped" data-export-types="['excel','pdf']" data-show-export="true"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,4" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
                    <th><?php echo translate('Store_name');?></th>
					<th><?php echo translate('title');?></th>
					<th><?php echo translate('minimum_order_amount (RM)');?></th>
					<!--<th><?php echo translate('category');?></th>
					<th><?php echo translate('sub_category');?></th>
					<th><?php echo translate('product');?></th>-->
					<th><?php echo translate('code');?></th>
					<th style="display:none;"><?php echo translate('added_by');?></th>
					<th><?php echo translate('status');?></th>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i=0;
            	foreach($all_coupons as $row){
            		$i++;
			?>
                <tr>
                    <td><?php echo $i; ?></td>
            <td><?php echo $lp = $this->db->get_where('vendor', array('vendor_id' => $row['vendor_id']))->row()->name; ?></td>
                    <td>						
					<div style="display: flex; justify-content: space-between; align-items: center;">
							<div style="text-align: left;"><?php echo $row['title']; ?></div>
							<?php if (($edit_rights == '1') || ($delete_rights == '1')) { ?>
								<div class="btn-group">
									<!-- <button class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> -->
										<i class="fa fa-chevron-circle-down icon-default" aria-hidden="true" id="dropdownMenu1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></i>
									<!-- </button> -->
									<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
										<?php if (($edit_rights == '1') || ($delete_rights == '1')) { ?>
											<li>
												<a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" onclick="ajax_modal('edit','<?php echo translate('edit_coupon'); ?>','<?php echo translate('successfully_edited!'); ?>','coupon_edit','<?php echo $row['coupon_id']; ?>')" data-original-title="Edit" data-container="body"><?php echo translate('edit'); ?>
												</a>
											</li>
										<?php } ?>
										<?php if (($edit_rights == '1') || ($delete_rights == '1')) { ?>
											<li>
												<a onclick="delete_confirm('<?php echo $row['coupon_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" data-original-title="Delete" data-container="body"><?php echo translate('delete'); ?>
												</a>
											</li>
										<?php } ?>
									</ul>
								</div>
							<?php } ?>
						</div>
					</td>
                    <td><?php echo $row['min_order_amount']; ?></td>
					<!--<td>
						<?php 
						$spec =  json_decode($row['spec'],true);
						$catname = $this->crud_model->get_type_name_by_id('category',$spec['pro_category'],'category_name'); 
						echo $catname; 
						?>
					</td>
					<td>
					<?php 
						$spec =  json_decode($row['spec'],true);
						$subcatname = $this->crud_model->get_type_name_by_id('sub_category',$spec['pro_sub_category'],'sub_category_name'); 
						echo $subcatname; 
						?>
					</td>
					<td>
					<?php 
						$spec =  json_decode($row['spec'],true);
						$getproduct = $this->crud_model->get_type_name_by_id('product',$spec['product'],'title'); 
						echo $getproduct; 
						?>
					</td>-->
                    <td><?php echo $row['code']; ?></td>
                    <td style="display:none;">
                    	<?php
                    		$by = json_decode($row['added_by'],true);
                    		$name = $this->crud_model->get_type_name_by_id($by['type'],$by['id'],'name'); 
                    	?>
                    	<?php echo $name; ?> (<?php echo $by['type']; ?>)
                    </td>
		            <td>
		                <input id="pub_<?php echo $row['coupon_id']; ?>" class='sw1' type="checkbox" data-id='<?php echo $row['coupon_id']; ?>' <?php if($row['status'] == 'ok'){ ?>checked<?php } ?> />
		            </td>

                </tr>
            <?php
            	}
			?>
			</tbody>
		</table>
	</div>
    <div id="coupn"></div>
	<div id='export-div'>
		<h1 style="display:none;"><?php echo translate('coupon'); ?></h1>
		<table id="export-table" data-name='coupon' data-orientation='p' style="display:none;">
				<thead>
					<tr>
						<th><?php echo translate('no');?></th>
						<th><?php echo translate('title');?></th>
					<th><?php echo translate('minimum_order_amount (RM)');?></th>
					<th><?php echo translate('code');?></th>
				
					<th><?php echo translate('status');?></th>
					</tr>
				</thead>
					
				<tbody >
			<?php
				$i=0;
            	foreach($all_coupons as $row){
            		$i++;
			?>
				<tr>
					<td><?php echo $i; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['min_order_amount']; ?></td>
                    <td><?php echo $row['code']; ?></td>
                
		            <td>
		            </td>
				</tr>
	            <?php
	            	}
				?>
				</tbody>
		</table>
	</div>

<style>
	.highlight{
		background-color: #E7F4FA;
	}
</style>







           