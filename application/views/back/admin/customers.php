<div id="content-container">
    <div class="content-wrapper-before"></div>
        <div style="background-color:#fff">
            <?php $tab = (isset($_GET['tab'])) ? $_GET['tab'] : null; ?> 
            <ul class="nav nav-tabs">
				<?php if($user_rights_13_10['view_rights']=='1'){ ?>
                <li class="<?php echo ($tab == 'user' || $tab == '') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/customers?tab=user'); ?>"><h4><?php echo translate('customers'); ?></h4></a></li>
				<?php } ?>
				<?php if($user_rights_13_11['view_rights']=='1'){ ?>
                <li class="<?php echo ($tab == 'user_group') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/customers?tab=user_group'); ?>"><h4><?php echo translate('customers_group'); ?></h4></a></li>
				<?php } ?>
				<?php if($user_rights_13_12['view_rights']=='1'){ ?>
                <li class="<?php echo ($tab == 'wallet_load') ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/customers?tab=wallet_load'); ?>"><h4><?php echo translate('manage_wallet_loads'); ?></h4></a></li>
				<?php } ?>
            </ul>
        </div>  
	
        <?php 
        if($_GET['tab'] == '' || $_GET['tab'] == 'user') {
            ?>
        <div class="tab-base">
		<div class="panel">
			<div class="panel-body">
            <div class="row">
                <h6 class="page-header text-overflow" ><?php echo translate('manage_customer');?></h6>			
            </div>
            <div class="col-md-12" style="padding: 25px 5px 5px 5px;">
					  <?php  echo form_open(base_url() . 'index.php/admin/customers/', array(
                                'class' => 'form-horizontal',
                                'method' => 'post',
								'id' => 'filter-form'
                                ));
                                ?>	
						    <!-- <div class="col-md-2">
						        <?php echo $this->crud_model->select_html('user','user','username','edit','demo-chosen-select form-control',$user,'','','','','');  ?>
						    </div> -->
						    <!-- <div class="col-md-2">
						        <select name="mode" class="form-control2">
                   <option value="0">rewards</option>
                   <option value="low" <?php if($mode == 'low'){ echo 'selected="selected"'; }?>>low to high</option>
                   <option value="high" <?php if($mode == 'high'){ echo 'selected="selected"'; }?> >high to low</option>
               </select>
               </div> -->

			   <!----------customerGroup----------------->
			<div class="col-md-2">
				<select name="user_group" id="user_group" class="form-control2">
					<option value="">Select Customer Group</option>
					<?php 
					$customer_group = $this->crud_model->customer_groups();
					foreach ($customer_group as $row) {
						$selected = ($row['user_group_id'] == $user_group) ? 'selected' : '';
					?>
						<option value="<?php echo $row['user_group_id']; ?>" <?php echo $selected; ?>>
							<?php echo $this->crud_model->get_type_name_by_id('user_group', $row['user_group_id'], 'user_group_name'); ?>
						</option>
					<?php } ?>
				</select>
				<?php
	?>
			</div>
		
			   <!----------customerGroup------------------>


						    <div class="col-md-2">
						        <input type="date" name="from" class="form-control2" value="<?php echo $from ?>">
						    </div>
						    <div class="col-md-2">
						        <input type="date" name="to" class="form-control2" value="<?php echo $to ?>">
						    </div>



			<!-------------------POSTALCODE(zip)------------------------------------>

				<div class="col-md-2">
					<select name="zip_code" id="zip_code" class="form-control2">
						<option value="">Select Zip Code</option>
						<?php
						$postal_codes = $this->crud_model->postal_codes();
						foreach ($postal_codes as $entry) {
							$zip = $entry['zip'];
							$user_ids = implode(', ', $entry['user_ids']);
							echo '<option value="' . $zip . '">' . $zip . ' </option>';
						}
						?>
					</select>
				</div>


		<!----------------------POSTALCODE(zip)--------------------------------->				



						    
						    <div  class="col-md-1"> 
						        <button type="submit" class="btn btn-success">Filter</button>
						    </div>
						    
						    <div  class="col-md-2"> 
						        <button type="button" class="btn btn-info btn-refresh"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
						    </div>
						    
						</form>
						    
					</form>
				</div>
					<br /><br />
                <!-- LIST -->
                <div class="tab-pane fade active in" id="list">
                
                </div>
			</div>
        </div>
	</div>
	
    <script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'user';

	//var list_cont_func = 'list/<php if($user) { echo $user; } else { echo "0"; } ?>/<php if($from) { echo $from; } else { echo "0"; } ?>/<php if($to) { echo $to; } else { echo "0"; } ?>/<php if($mode) { echo $mode; } else { echo "0"; } ?>';
	var list_cont_func = 'list/<?php if($user_group) { echo $user_group; } else { echo "0"; } ?>/<?php if($from) { echo $from; } else { echo "0"; } ?>/<?php if($to) { echo $to; } else { echo "0"; } ?>/<?php if($zip_code) { echo $zip_code; } else { echo "0"; } ?>/<?php if($mode) { echo $mode; } else { echo "0"; } ?>';

	var dlt_cont_func = 'delete';




$(document).ready(function() {
    // When the Refresh button is clicked
    $(".btn-refresh").click(function() {
        // Reset the mode dropdown to its default value (rewards)
        $("select[name='mode']").val("0");

		$("select[name='user_group']").val("0");
		$("select[name='zip_code']").val("0");

        
        // Reset the date inputs to empty
        $("input[name='from']").val("");
        $("input[name='to']").val("");

		$("form#filter-form").submit();
    });
});

</script>

    <?php } else if($_GET['tab'] == 'user_group') { ?>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
            <div class="tab-content">
				<div class="row">
					<div class="col-md-10">
					<h6 class="page-header text-overflow" ><?php echo translate('manage_customer_group');?></h6>
					</div>
					<div class="col-md-2">
					<?php if($user_rights_13_11['add_rights']=='1'){ ?>
					<button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right mar-rgt" 
                        	onclick="ajax_modal('add','<?php echo translate('add_user_group'); ?>','<?php echo translate('successfully_added!'); ?>','user_group_add','')">
								<?php echo translate('create_customer_group');?>
                                	</button>
									<?php } ?>
					</div>
                    <br>
					<div class="tab-pane fade active in" id="list" 
                    	style="border:1px solid #ebebeb; 
                        	border-radius:4px;">
					</div>
				</div>
               <br>
                    <!-- LIST -->
                    <div class="tab-pane fade active in" id="list">
                    
                    </div>
                </div>
			</div>
        </div>
	</div>

    <script>
		var base_url = '<?php echo base_url(); ?>'
		var user_type = 'admin';
		var module = 'user_group';
		var list_cont_func = 'list';
		var dlt_cont_func = 'delete';
	</script>

        <?php }  else if ($_GET['tab'] == 'wallet_load') { ?>

	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<h6 class="page-header text-overflow" ><?php echo translate('manage_wallet_loads');?></h6>			
				</div>
                <!-- LIST -->
                <div class="tab-pane fade active in" id="list">
                
                </div>
			</div>
        </div>
	</div>

    <script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'wallet_load';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
</script>
<script src="https://checkout.stripe.com/checkout.js"></script>
            <?php } ?>

</div>