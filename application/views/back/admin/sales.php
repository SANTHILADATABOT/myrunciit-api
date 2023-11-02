<div id="content-container">
	<div class="content-wrapper-before"></div>

	<div class="tab-base">
		<div class="panel">
		
		   <div class="row">
			  <div class="col-md-12">
			    <h1 class="page-header text-overflow"><?php echo translate('manage_sale'); ?></h1>
			  </div>
		    </div>
			<br /><br />
			<div class="panel-body" id="filter_field_div">
				<div class="col-md-12" id="filterg">
					<?php echo form_open(base_url() . 'index.php/admin/sales/', array(
						'class' => 'form-horizontal',
						'method' => 'post'
					));
					?>
					<div class="col-md-3">
						<?php echo $this->crud_model->select_html('vendor', 'vendor', 'name', 'edit', 'demo-chosen-select form-control', $vendor, '', '', '', '', '');  ?>
					</div>
					<div class="col-md-2">
						<select name="mode" id="mode1" class="form-control2">
							<option value="0">Order Type</option>
							<option value="pickup" <?php if ($mode == 'pickup') {
														echo 'selected="selected"';
													} ?>>Pickup</option>
							<option value="delivery" <?php if ($mode == 'delivery') {
															echo 'selected="selected"';
														} ?>>Delivery</option>
						</select>
					</div>
					<div class="col-md-2">
						<select name="pre_order_status" id="pre_order_status1" class="form-control2">
							<option value="0">Pre Orders</option>
							<option value="ok" <?php if ($pre_order_status == 'ok') {
													echo 'selected="selected"';
												} ?>>Yes</option>
							<option value="no" <?php if ($pre_order_status == 'no') {
													echo 'selected="selected"';
												} ?>>No</option>
						</select>
					</div>
					<div class="col-md-2">
						<select name="delv_status" id="delv_status1" class="form-control2">
							<option value="0">delivery status</option>
							<option value="delivered" <?php if ($delv_status == 'delivered') {
															echo 'selected="selected"';
														} ?>>Delivered</option>
							<option value="pending" <?php if ($delv_status == 'pending') {
														echo 'selected="selected"';
													} ?>>Pending</option>
							<option value="on_delivery" <?php if ($delv_status == 'on_delivery') {
															echo 'selected="selected"';
														} ?>>Out For Delivery</option>
						</select>
					</div>
					<div class="col-md-2">
						<select name="order_status" id="order_status1" class="form-control2">
							<option value="0">order status</option>
							<option value="success" <?php if ($order_status == 'success') {
														echo 'selected="selected"';
													} ?>>Accepted</option>
							<option value="admin_pending" <?php if ($order_status == 'admin_pending') {
																echo 'selected="selected"';
															} ?>>Waiting for accept</option>

						</select>
					</div>
					
					<div class="col-md-2">
					<div style="margin-top:25px">
						<select name="payment_sts" id="payment_sts1" class="form-control2" >
							<option value="0">Payment status</option>
							<option value='1' <?php if ($payment_sts == '1') {
														echo 'selected="selected"';
													} ?>>Failed</option>
							<option value='2' <?php if ($payment_sts == '2') {
																echo 'selected="selected"';
															} ?>>Due</option>
							<option value='3' <?php if ($payment_sts == '3') {
																echo 'selected="selected"';
															} ?>>Paid</option>

						</select>
														</div>
					</div>

					<div class="col-md-2">
						<div style="margin-top:25px">Start date:<input type="date" name="from" id="from1" class="form-control2" value="<?php echo $from ?>"></div>
					</div>
					<div class="col-md-2">
						<div style="margin-top:25px">End date:<input type="date" name="to" id="to1" class="form-control2" value="<?php echo $to ?>"></div>
					</div>
					
					<div class="col-md-3">
					<div style="margin-top:46px">&nbsp;<button type="submit" class="btn btn-success" id="filter_btn">Filter</button>
						&nbsp;<button type="button" class="btn btn-success" onclick="refresh_filter()">Refresh</button></div>
					</div>
					
					</form>
				</div>
				<br>
				<!-- LIST -->
				<div class="tab-pane fade active in" id="list">

				</div>
			</div>
			<div class="panel-body" id="back_btn_div" style="display:none;">
				<div class="tab-content">
					<div class="col-md-12">
                        <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" onclick="open_saleslist()"><?php echo translate('back_to_manage_sale_list');?></button>
					</div>
                </div>
				<br>
				<!-- LIST -->
				<div class="tab-pane fade active in" id="order_detail_div">

				</div>
            </div>
		</div>
	</div>
</div>

<script>
	function refresh_filter(){
		document.getElementsByName("vendor")[0].value="";
		$("#mode1").val("0");
		$("#pre_order_status1").val("0");
		$("#delv_status1").val("0");
		$("#order_status1").val("0");
		$("#from1").val("");
		$("#to1").val("");
		$('#filter_btn').click();
	}
	function open_orderdetail()
	{
		document.getElementById("back_btn_div").style.display = "block";
		document.getElementById("filter_field_div").style.display = "none";
	}
	function open_saleslist()
	{
		$("#order_detail_div").html("");
		document.getElementById("back_btn_div").style.display = "none";
		document.getElementById("filter_field_div").style.display = "block";
	}
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'sales';
	var list_cont_func = 'list/<?php if ($vendor) {
									echo $vendor;
								} else {
									echo "0";
								} ?>/<?php if ($from) {
			echo $from;
		} else {
			echo "0";
		} ?>/<?php if ($to) {
			echo $to;
		} else {
			echo "0";
		} ?>/<?php if ($mode) {
			echo $mode;
		} else {
			echo "0";
		} ?>/<?php if ($delv_status) {
			echo $delv_status;
		} else {
			echo "0";
		} ?>/<?php if ($order_status) {
			echo $order_status;
		} else {
			echo "0";
		} ?>/<?php if ($pre_order_status) {
			echo $pre_order_status;
		} else {
			echo "0";
		} ?>/<?php if ($payment_sts) {
			echo $payment_sts;
		} else {
			echo "0";
		} ?>';
	var dlt_cont_func = 'delete';
</script>