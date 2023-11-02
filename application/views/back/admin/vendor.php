<div id="content-container">
<div class="content-wrapper-before"></div>

	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
					<div class="row">
					 <div class="col-md-5">
					    <h1 class="page-header text-overflow"><?php echo translate('manage_stores');?></h1>
					 </div>
					 <div class="col-md-7">
					
					    <button style="display:none;" class="btn btn-primary btn-labeled fa fa-plus-circle pull-right" 
                        	onclick="ajax_modal('commission_set','<?php echo translate('commission_set)'); ?>','<?php echo translate('successfully_added!');?>','update_commission','')">
								<?php echo translate('commission');?>
						</button>
						<?php if($user_rights_12_0['add_rights']=='1'){ ?>
					<button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right mar-rgt" 
                        	onclick="ajax_modal('add','<?php echo translate('add_vendor'); ?>','<?php echo translate('successfully_added!'); ?>','vendor_add','')">
								<?php echo translate('create_store');?>
                                	</button>

									
                            <!----------------------VendorHistory------------------------------>
                            
                            <button id="historyButton" class="btn btn-primary btn-labeled fa fa-history pull-right mar-rgt">Show History</button>

                            <!----------------------VendorHistory------------------------------>
									
									<?php } ?>
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

<!---------------VendorHistoryModal--------------------->
<div id="historyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo translate('Deleted Vendor_History');?></h4>
            </div>
            <div class="modal-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th><?php echo translate('S.No');?></th>
                    <th><?php echo translate('Vendor Name');?></th>
                    <th><?php echo translate('Deleted By');?></th>
                    <th><?php echo translate('Deleted On');?></th>
                </tr>
                </thead>
                <tbody>
                    <?php 
                    $this->db->select('vh.vendor_id,vh.vendor_name,vh.admin_id,vh.deleted_on,a.admin_id,a.name');
                    $this->db->from('vendor_history as vh');
                    $this->db->join('admin as a', 'vh.admin_id = a.admin_id', 'left');
                    $this->db->where('vh.deleted_status','1');
                    // $this->db->group_by('vh.vendor_id');
                    $vendor_history = $this->db->get('vendor_history')->result_array();
                    $serial_number = 1;
                    
                    foreach ($vendor_history as $history_item) { ?>
                        <tr>
                            <td><?php echo $serial_number++; ?></td>
                            <td><?php echo $history_item['vendor_name'];?></td>
                            <td><?php echo $history_item['name'];?></td>
                            <td><?php echo $history_item['deleted_on'];?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>


<script src="path/to/jquery.js"></script>
<script src="path/to/bootstrap.js"></script>
<script>
    $(document).ready(function() {
        $('#historyButton').click(function() {
            $('#historyModal').modal('show');
        });
    });
</script>

  
<!---------------VendorHistoryModal--------------------->
<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'vendor';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
</script>
<script src="https://checkout.stripe.com/checkout.js"></script>
