<link rel="stylesheet" href="<?php echo base_url(); ?>template/back//amcharts/style.css" type="text/css">
<script src="<?php echo base_url(); ?>template/back/amcharts/amcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/serial.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/plugins/morris-js/morris.min.js"></script>
<script src="<?php echo base_url(); ?>template/back/plugins/gauge-js/gauge.min.js"></script>

<div id="content-container">
	<div class="content-wrapper-before hidden"></div>

	<div id="page-title">
		<h1 class="page-header text-overflow"><?php echo translate('dashboard'); ?></h1>
	</div>
	<div id="page-content">


		<div class="row">
		   

			<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #1396f1;">
					<div class="panel-heading">
						<h3 class="panel-title">Total visitors</h3>
					</div>
					<div class="panel-body">
						<div class="text-center">

							<p class="h4">



								<span> <?php
										$result2 =	$this->db->query('SELECT sum(no_of_visitors) FROM vendor')->result_array();

										echo $result2[0]['sum(no_of_visitors)']; ?></span>


							</p>
						</div>
					</div>
				</div>
			</div>
			<?php
			$user = $this->db->get('vendor')->result_array();
			foreach ($user as $row_u) {
				//$fin = ($this->crud_model->month_total('sale', 'category', $row['category_id'])) - ($this->crud_model->month_total('stock', 'category', $row['category_id'], 'type', 'add'));
				//$u_id=	$this->db->distinct($row_u['user_id']);

				$result2 = $this->db->get_where('vendor', array('vendor_id' => $row_u['vendor_id']))->result_array();
				//echo "qry".$this->db->last_query(); exit;
				$stock = 0;
				foreach ($result2 as $row) {
					$stock = $row['no_of_visitors'];
				} ?>
				<div class="col-md-4 col-lg-4">
					<div class="panel panel-bordered clred" style="background: #1396f1;">
						<div class="panel-heading">
							<h3 class="panel-title"><?php echo $row_u['name']; ?> visitors</h3>
						</div>
						<div class="panel-body">
							<div class="text-center">

								<p class="h4">
									<span> <?php echo $stock; ?></span>

								</p>
							</div>
						</div>
					</div>
				</div>

			<?php }  ?>


		</div>
		
		
		<div class="row">
		   

			<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #1396f1;">
					<div class="panel-heading">
						<h3 class="panel-title">Total visitors</h3>
					</div>
					<div class="panel-body">
						<div class="text-center">

							<p class="h4">



								<span> <?php
										$result2 =	$this->db->query('SELECT sum(no_of_visitors) FROM vendor')->result_array();

										echo $result2[0]['sum(no_of_visitors)']; ?></span>


							</p>
						</div>
					</div>
				</div>
			</div>
			<?php
			$user = $this->db->get('vendor')->result_array();
			foreach ($user as $row_u) {
				//$fin = ($this->crud_model->month_total('sale', 'category', $row['category_id'])) - ($this->crud_model->month_total('stock', 'category', $row['category_id'], 'type', 'add'));
				//$u_id=	$this->db->distinct($row_u['user_id']);

				$result2 = $this->db->get_where('vendor', array('vendor_id' => $row_u['vendor_id']))->result_array();
				//echo "qry".$this->db->last_query(); exit;
				$stock = 0;
				foreach ($result2 as $row) {
					$stock = $row['no_of_visitors'];
				} ?>
				<div class="col-md-4 col-lg-4">
					<div class="panel panel-bordered clred" style="background: #1396f1;">
						<div class="panel-heading">
							<h3 class="panel-title"><?php echo $row_u['name']; ?> visitors</h3>
						</div>
						<div class="panel-body">
							<div class="text-center">

								<p class="h4">
									<span> <?php echo $stock; ?></span>

								</p>
							</div>
						</div>
					</div>
				</div>

			<?php }  ?>


		</div>



		<div class="row hidden" <?php if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') == 'ok') {
								} else { ?>style="display:none;" <?php } ?>>
			<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #1396f1;">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('24_hours_stock'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="text-center">
							<canvas id="gauge1" height="70" class="canvas-responsive"></canvas>
							<p class="h4">
								<span><?php echo currency('', 'def'); ?></span>
								<span id="gauge1-txt">0</span>
							</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered  clred" style="background: #e7314e;">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('24_hours_sale'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="text-center">
							<canvas id="gauge2" height="70" class="canvas-responsive"></canvas>
							<p class="h4">
								<span><?php echo currency('', 'def'); ?></span>
								<span id="gauge2-txt">0</span>
							</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #4d45ff;">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('24_hours_destroy'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="text-center">
							<canvas id="gauge3" height="70" class="canvas-responsive"></canvas>
							<p class="h4">
								<span><?php echo currency('', 'def'); ?></span>
								<span id="gauge3-txt">0</span>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row" <?php if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok' && $this->crud_model->get_type_name_by_id('general_settings', '69', 'value') == 'ok') {
							} else { ?>style="display:none;" <?php } ?>>
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('24_hours_sale'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="text-center">
							<canvas id="gauge4" height="70" class="canvas-responsive"></canvas>
							<p class="h4">
								<span><?php echo currency('', 'def'); ?></span>
								<span id="gauge4-txt">0</span>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row" <?php if ($this->crud_model->get_type_name_by_id('general_settings', '58', 'value') == 'ok') {
							} else { ?>style="display:none;" <?php } ?>>
			<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #1396f1;">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('total_active_stores'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="text-center">
							<h6>
								<?php echo $this->db->get_where('vendor', array('status = ' => 'approved'))->num_rows(); ?>
							</h6>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #1396f1;">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('total_pending_stores'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="text-center">
							<h6>
								<?php echo $this->db->get_where('vendor', array('status != ' => 'approved'))->num_rows(); ?>
							</h6>
						</div>
					</div>
				</div>
			</div>
            
            

			<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #1396f1;">
					<div class="panel-heading">
						<h3 class="panel-title">Gross Revenue</h3>
					</div>
					<div class="panel-body">
						<div class="text-center">

							<h6>



								<?php
								$where = '(status="success" or status = "admin_pending")';
								$this->db->where($where);
								$this->db->select_sum('grand_total');
								$this->db->from('sale');

								$result2 =	$this->db->get()->result_array();
								// print_r($result2);
								//  echo $this->db->last_query();
								//$result2 =	$this->db->query('SELECT sum(grand_total) FROM sale')->result_array();
								//$result2[0]['sum(grand_total)'];

								echo "RM" . $result2[0]['grand_total']; ?>

								<?php
								/*	$where = '(status="success" or status = "admin_pending")';$this->db->where($where);
								//$this->db->select_sum('grand_total');
                                $result2 =$this->db->get('sale')->result_array();
                                echo $this->db->last_query();
                                $sum = 0; foreach($result2 as $key => $value) { $sum += $value['grand_total']; } echo $sum; */
								?>


							</h6>
						</div>
					</div>
				</div>
			</div>

		</div>
		<?php
		$this->db->select('*');
		$this->db->from('sale');
		$this->db->where('status', 'success');
		$this->db->or_where('status', 'admin_pending');
		$getAllSales = $this->db->get()->result_array();
		// echo '<pre>';
		// print_r($getAllSales);
		function getTotalPickup($carry, $item)
		{
			if ($item['order_type'] == 'pickup') {
				$carry++;
				return $carry;
			}
		}
		function getTotalDelivery($carry, $item)
		{
			if ($item['order_type'] == 'delivery') {
				$carry++;
				return $carry;
			}
		}
		function getTotalPromotion($carry, $item)
		{
			$jsonData = json_decode($item['product_detail']);
			if (count($jsonData['coupon']) > 0) {
				$carry++;
				return $carry;
			}
		}
		$totalPickupOrders = array_reduce($getAllSales, 'getTotalPickup');
		$totalDeliveryOrders = array_reduce($getAllSales, 'getTotalDelivery');
		$totalSalesByPromotion = array_reduce($getAllSales, 'getTotalPromotion');
		?>
		<!-- get all product data -->
		<?php 
		$this->db->select('*');
		$this->db->from('product');
		$allProducts = $this->db->get()->result_array();

		function totalInStock($carry, $item)
		{
			if ($item['current_stock'] > 0) {
				$carry ++;
				return $carry;
			}
		}
		function totalOutOfStock($carry, $item)
		{
			if ($item['current_stock'] == 0) {
				$carry ++;
				return $carry;
			}
		}
		$totalInStockProducts = array_reduce($allProducts, 'totalInStock');
		$totalOutOfStockProducts = array_reduce($allProducts, 'totalOutOfStock');
		?>
		<div class="row">
			<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #1396f1;">
					<div class="panel-heading">
						<h3 class="panel-title">Total Pickup Orders</h3>
					</div>
					<div class="panel-body">
						<div class="text-center">

							<h6><?php echo $totalPickupOrders ? $totalPickupOrders : 0 ?></h6>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #1396f1;">
					<div class="panel-heading">
						<h3 class="panel-title">Total Delivery Orders</h3>
					</div>
					<div class="panel-body">
						<div class="text-center">

							<h6><?php echo $totalDeliveryOrders ? $totalDeliveryOrders : 0 ?></h6>
						</div>
					</div>
				</div>
			</div>
				<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #1396f1;">
					<div class="panel-heading">
						<h3 class="panel-title">Number of items sold</h3>
					</div>
					<div class="panel-body">
						<div class="text-center">

							<h6>
							    
				<?php
				// 	$this->db->select_sum('quantity');
				// 	$this->db->from('stock');
				// $this->db->where("reason_note = 'sale' AND type = 'destroy' AND sale_id!=0 AND product!='NULL'");
				// // $this->db->from('stock');
			$q=$this->db->query("SELECT sum(quantity) as no_of_items_sold FROM `stock` where reason_note='sale' and type='destroy' and sale_id!=0 and product!='NULL'");
				
			echo($q->result_array()[0]['no_of_items_sold']);
				 ?>
							
							</h6>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #1396f1;">
					<div class="panel-heading">
						<h3 class="panel-title">Sales by promotion</h3>
					</div>
					<div class="panel-body">
						<div class="text-center">

							<h6>
								<?php echo $totalSalesByPromotion ? $totalSalesByPromotion : 0 ?>
							</h6>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #1396f1;">
					<div class="panel-heading">
						<h3 class="panel-title">Invertory</h3>
					</div>
					<div class="panel-body">
						<div class="text-center">

							<h6>
								Products In Stock: <?php echo $totalInStockProducts ? $totalInStockProducts : 0 ?>
							</h6>
							<h6>
							Products Out Of Stock: <?php echo $totalOutOfStockProducts ? $totalOutOfStockProducts : 0 ?>
							</h6>
						</div>
					</div>
				</div>
			</div>
            <div class="col-md-4 col-lg-4">
				<div class="panel panel-bordered clred" style="background: #1396f1;">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('total_customers'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="text-center">
							<h6>
								<?php echo $this->db->get_where('user')->num_rows(); ?>
							</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" >
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('Customer demographics (Age & Gender)'); ?></h3>
					</div>
					 <?php
		    $start_age=1;
		    $end_age=10;
		    $this->db->where('age BETWEEN "' . $start_age . '" AND "' . $end_age . '"');
		    $this->db->where('gender','male');
		    
		    $a1 = $this->db->get('user')->num_rows();
		    
		    $start_age1=11;
		    $end_age1=20;
		    $this->db->where('age BETWEEN "' . $start_age1 . '" AND "' . $end_age1 . '"');
		    $this->db->where('gender','male');
		    
		    $a2 = $this->db->get('user')->num_rows();
		    
		    $start_age2=21;
		    $end_age2=30;
		    $this->db->where('age BETWEEN "' . $start_age2 . '" AND "' . $end_age2 . '"');
		    $this->db->where('gender','male');
		    
		    $a3 = $this->db->get('user')->num_rows();
		    
		    $start_age3=31;
		    $end_age3=40;
		    $this->db->where('age BETWEEN "' . $start_age3 . '" AND "' . $end_age3 . '"');
		    $this->db->where('gender','male');
		    
		    $a4 = $this->db->get('user')->num_rows();
		    
		    $start_age4=41;
		    $end_age4=50;
		    $this->db->where('age BETWEEN "' . $start_age4 . '" AND "' . $end_age4 . '"');
		    $this->db->where('gender','male');
		    
		    $a5 = $this->db->get('user')->num_rows();
		    
		    
		    $start_age5=51;
		    $end_age5=60;
		    $this->db->where('age BETWEEN "' . $start_age5 . '" AND "' . $end_age5 . '"');
		    $this->db->where('gender','male');
		    
		    $a6 = $this->db->get('user')->num_rows();
		    
		    $start_age6=61;
		    $end_age6=70;
		    $this->db->where('age BETWEEN "' . $start_age6 . '" AND "' . $end_age6 . '"');
		    $this->db->where('gender','male');
		    
		    $a7 = $this->db->get('user')->num_rows();
			
 



 
$dataPoints1 = array(
	array("label"=> "10", "y"=> $a1),
	array("label"=> "20", "y"=> $a2),
	array("label"=> "30", "y"=> $a3),
	array("label"=> "40", "y"=> $a4),
	array("label"=> "50", "y"=> $a5),
	array("label"=> "60", "y"=> $a6),
	array("label"=> "70", "y"=> $a7)
);

  $start_ages=1;
		    $end_ages=10;
		    $this->db->where('age BETWEEN "' . $start_ages . '" AND "' . $end_ages . '"');
		    $this->db->where('gender','female');
		    
		    $a1s = $this->db->get('user')->num_rows();
		    
		    $start_age1s=11;
		    $end_age1s=20;
		    $this->db->where('age BETWEEN "' . $start_age1s . '" AND "' . $end_age1s . '"');
		    $this->db->where('gender','female');
		    
		    $a2s = $this->db->get('user')->num_rows();
		    
		    $start_age2s=21;
		    $end_age2s=30;
		    $this->db->where('age BETWEEN "' . $start_age2s . '" AND "' . $end_age2s . '"');
		    $this->db->where('gender','female');
		    
		    $a3s = $this->db->get('user')->num_rows();
		    
		    $start_age3s=31;
		    $end_age3s=40;
		    $this->db->where('age BETWEEN "' . $start_age3s . '" AND "' . $end_age3s . '"');
		    $this->db->where('gender','female');
		    
		    $a4s = $this->db->get('user')->num_rows();
		    
		    $start_age4s=41;
		    $end_age4s=50;
		    $this->db->where('age BETWEEN "' . $start_age4s . '" AND "' . $end_age4s . '"');
		    $this->db->where('gender','female');
		    
		    $a5s = $this->db->get('user')->num_rows();
		    
		    
		    $start_age5s=51;
		    $end_age5s=60;
		    $this->db->where('age BETWEEN "' . $start_age5s . '" AND "' . $end_age5s . '"');
		    $this->db->where('gender','female');
		    
		    $a6s = $this->db->get('user')->num_rows();
		    
		    $start_age6s=61;
		    $end_age6s=70;
		    $this->db->where('age BETWEEN "' . $start_age6s . '" AND "' . $end_age6s . '"');
		    $this->db->where('gender','female');
		    
		    $a7s = $this->db->get('user')->num_rows();

$dataPoints2 = array(
	array("label"=> "10", "y"=> $a1s),
	array("label"=> "20", "y"=> $a2s),
	array("label"=> "30", "y"=> $a3s),
	array("label"=> "40", "y"=> $a4s),
	array("label"=> "50", "y"=> $a5s),
	array("label"=> "60", "y"=> $a6s),
	array("label"=> "70", "y"=> $a7s)
);
	
?>


		   
					<div class="panel-body">
						<div class="text-center">
						<div id="chartContainer" style="height: 370px; width: 100%;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered ">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('Traffic overview
'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="text-center">
							<div class="col-md-12 col-lg-12">
								<div class="panel-body">
									<div id="chartdiv5" style="width: 100%; height: 300px;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>





		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('Sales_by_shop'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div id="chartdiv" style="width: 100%; height: 300px;"></div>
					</div>
				</div>
			</div>
		
		</div>
			<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('Rating'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div id="chartrate" style="width: 100%; height: 300px;"></div>
					</div>
				</div>
			</div>
		
		</div>	
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('Sales by order value'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div id="chartdiv3" style="width: 100%; height: 300px;"></div>
					</div>
				</div>
			</div>
		
		</div>
		
		
		
		<?php 
		// all user data
		$allUsers = $this->db->get('user')->result_array();
		$arrSlot = [
			'Male(0-20)' => 0,
			'Male(20-40)' => 0,
			'Male(40-60)' => 0,
			'Male(60-80)' => 0,
			'Female(0-20)' => 0,
			'Female(20-40)' => 0,
			'Female(40-60)' => 0,
			'Female(60-80)' => 0,
		];
		foreach ($allUsers as $user) {
			$key = $user['gender'] == 'male' ? 'Male' : 'Female';
			if ($user['age'] <= 80 && $user['age'] >= 60) {
				$arrSlot[$key.'(60-80)'] += 1; 
			}
			if ($user['age'] <= 60 && $user['age'] >= 40) {
				$arrSlot[$key.'(60-80)'] += 1; 
			}
			if ($user['age'] <= 40 && $user['age'] >= 20) {
				$arrSlot[$key.'(60-80)'] += 1; 
			}
			if ($user['age'] <= 20 && $user['age'] >= 0) {
				$arrSlot[$key.'(60-80)'] += 1; 
			}
		}
	//	print_r($arrSlot);
		?>

		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('Traffic_&_sales_breakdown_by_days'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div class="panel-body" id="demo_s">
							<table id="demo-table" class="table table-striped" data-pagination="false" data-show-refresh="false" data-show-toggle="false" data-show-columns="false" data-search="false">

								<thead>
									<tr>
										<th>Date</th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											echo date('d-m-y', $c1time); ?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-1 day", $c1time);
											$back2etime = strtotime("-1 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-2 day", $c1time);
											$back2etime = strtotime("-2 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-3 day", $c1time);
											$back2etime = strtotime("-3 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-4 day", $c1time);
											$back2etime = strtotime("-4 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-5 day", $c1time);
											$back2etime = strtotime("-5 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-6 day", $c1time);
											$back2etime = strtotime("-6 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>


										<th>Total</th>

									</tr>
								</thead>

								<tbody>
									<tr>
										<td>Total sale</td>
										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $c1time . '" AND "' . $ctime . '"');
											$all_sales = $this->db->get('sale')->result_array();
											$total = 0;
											$qty = 0;
											$average_sales = 0;
											$average_unit = 0;
											$coupon = 0;
											$average_sales_total = 0;
											foreach ($all_sales as $row) {
												$total += $row['grand_total'];

												$product_details = json_decode($row['product_details'], true);
												foreach ($product_details as $product_detail) {
													$qty += $product_detail['qty'];
													$order_item2[] = $product_detail['id'];
													$order_item2 =  array_unique($order_item2);
													$coupon += $product_detail['coupon'];
												}
												$average_sales = $total / count($order_item2);
												$average_unit = $qty / count($order_item2);
												$average_sales_total = $total / count($all_sales);
												$coupon = $coupon / count($all_sales);
											}
											?>

											<?php
											$a = number_format((float)$total, 2, '.', '');
											echo currency() . number_format((float)$total, 2, '.', ''); ?></td>

										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-1 day", $c1time);
											$back2etime = strtotime("-1 day", $ctime);
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);

											$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
											$all_sales = $this->db->get('sale')->result_array();
											$total = 0;
											$qty = 0;
											$average_sales = 0;
											$average_unit = 0;
											$coupon = 0;
											$average_sales_total = 0;
											foreach ($all_sales as $row) {
												$total += $row['grand_total'];

												$product_details = json_decode($row['product_details'], true);
												foreach ($product_details as $product_detail) {
													$qty += $product_detail['qty'];
													$order_item1[] = $product_detail['id'];
													$order_item1 =  array_unique($order_item1);
													$coupon += $product_detail['coupon'];
												}
												$average_sales = $total / count($order_item1);
												$average_unit = $qty / count($order_item1);
												$average_sales_total = $total / count($all_sales);
												$coupon = $coupon / count($all_sales);
											}
											?>
											<?php echo currency() . number_format((float)$total, 2, '.', ''); ?>
										</td>
										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-2 day", $c1time);
											$back2etime = strtotime("-2 day", $ctime);
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
											$all_sales = $this->db->get('sale')->result_array();
											$total = 0;
											$qty = 0;
											$average_sales = 0;
											$average_unit = 0;
											$coupon = 0;
											$average_sales_total = 0;
											foreach ($all_sales as $row) {
												$total += $row['grand_total'];

												$product_details = json_decode($row['product_details'], true);
												foreach ($product_details as $product_detail) {
													$qty += $product_detail['qty'];
													$order_item1[] = $product_detail['id'];
													$order_item1 =  array_unique($order_item1);
													$coupon += $product_detail['coupon'];
												}
												$average_sales = $total / count($order_item1);
												$average_unit = $qty / count($order_item1);
												$average_sales_total = $total / count($all_sales);
												$coupon = $coupon / count($all_sales);
											}
											?>
											<?php
											$b = number_format((float)$total, 2, '.', '');
											echo currency() . number_format((float)$total, 2, '.', ''); ?>
										</td>

										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-3 day", $c1time);
											$back2etime = strtotime("-3 day", $ctime);
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
											$all_sales = $this->db->get('sale')->result_array();
											$total = 0;
											$qty = 0;
											$average_sales = 0;
											$average_unit = 0;
											$coupon = 0;
											$average_sales_total = 0;
											foreach ($all_sales as $row) {
												$total += $row['grand_total'];

												$product_details = json_decode($row['product_details'], true);
												foreach ($product_details as $product_detail) {
													$qty += $product_detail['qty'];
													$order_item1[] = $product_detail['id'];
													$order_item1 =  array_unique($order_item1);
													$coupon += $product_detail['coupon'];
												}
												$average_sales = $total / count($order_item1);
												$average_unit = $qty / count($order_item1);
												$average_sales_total = $total / count($all_sales);
												$coupon = $coupon / count($all_sales);
											}
											?>
											<?php
											$c = number_format((float)$total, 2, '.', '');
											echo currency() . number_format((float)$total, 2, '.', ''); ?>
										</td>

										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-4 day", $c1time);
											$back2etime = strtotime("-4 day", $ctime);
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
											$all_sales = $this->db->get('sale')->result_array();
											$total = 0;
											$qty = 0;
											$average_sales = 0;
											$average_unit = 0;
											$coupon = 0;
											$average_sales_total = 0;
											foreach ($all_sales as $row) {
												$total += $row['grand_total'];

												$product_details = json_decode($row['product_details'], true);
												foreach ($product_details as $product_detail) {
													$qty += $product_detail['qty'];
													$order_item1[] = $product_detail['id'];
													$order_item1 =  array_unique($order_item1);
													$coupon += $product_detail['coupon'];
												}
												$average_sales = $total / count($order_item1);
												$average_unit = $qty / count($order_item1);
												$average_sales_total = $total / count($all_sales);
												$coupon = $coupon / count($all_sales);
											}
											?>
											<?php
											$d = number_format((float)$total, 2, '.', '');
											echo currency() . number_format((float)$total, 2, '.', ''); ?>
										</td>

										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-5 day", $c1time);
											$back2etime = strtotime("-5 day", $ctime);
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
											$all_sales = $this->db->get('sale')->result_array();
											$total = 0;
											$qty = 0;
											$average_sales = 0;
											$average_unit = 0;
											$coupon = 0;
											$average_sales_total = 0;
											foreach ($all_sales as $row) {
												$total += $row['grand_total'];

												$product_details = json_decode($row['product_details'], true);
												foreach ($product_details as $product_detail) {
													$qty += $product_detail['qty'];
													$order_item1[] = $product_detail['id'];
													$order_item1 =  array_unique($order_item1);
													$coupon += $product_detail['coupon'];
												}
												$average_sales = $total / count($order_item1);
												$average_unit = $qty / count($order_item1);
												$average_sales_total = $total / count($all_sales);
												$coupon = $coupon / count($all_sales);
											}
											?>
											<?php
											$e = number_format((float)$total, 2, '.', '');
											echo currency() . number_format((float)$total, 2, '.', ''); ?>
										</td>

										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-6 day", $c1time);
											$back2etime = strtotime("-6 day", $ctime);
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
											$all_sales = $this->db->get('sale')->result_array();
											$total = 0;
											$qty = 0;
											$average_sales = 0;
											$average_unit = 0;
											$coupon = 0;
											$average_sales_total = 0;
											foreach ($all_sales as $row) {
												$total += $row['grand_total'];

												$product_details = json_decode($row['product_details'], true);
												foreach ($product_details as $product_detail) {
													$qty += $product_detail['qty'];
													$order_item1[] = $product_detail['id'];
													$order_item1 =  array_unique($order_item1);
													$coupon += $product_detail['coupon'];
												}
												$average_sales = $total / count($order_item1);
												$average_unit = $qty / count($order_item1);
												$average_sales_total = $total / count($all_sales);
												$coupon = $coupon / count($all_sales);
											}
											?>
											<?php
											$f = number_format((float)$total, 2, '.', '');
											echo currency() . number_format((float)$total, 2, '.', ''); ?>
										</td>
										<td>
											<?php
											$tot = $a + $b + $c + $d + $e + $f;
											echo currency() . number_format((float)$tot, 2, '.', ''); ?></td>
									</tr>
									<?php /*?>        <tr>
            <?php
            $i = 0;
			$ctime = strtotime(date('d-m-Y 23:59:59',time()));
			$c1time = strtotime(date('d-m-Y 00:00:00',time()));
	        $back2stime=strtotime("-1 day", $c1time);
	        $back2etime=strtotime("-1 day", $ctime);
			$this->db->where('sale_datetime BETWEEN "'.$back2stime.'" AND "'.$back2etime.'"');
			$all_sales = $this->db->get('sale')->result_array();
			$total = 0;
			$qty = 0;
			$average_sales= 0;
			$average_unit = 0;
			$coupon = 0;
			$average_sales_total = 0;
		    foreach($all_sales as $row){
		        $total += $row['grand_total'];
		        
		       $product_details =json_decode($row['product_details'],true);
		       foreach($product_details as $product_detail){
		           $qty +=$product_detail['qty'];
		           $order_item1[]=$product_detail['id'];
		           $order_item1 =  array_unique($order_item1);
		           $coupon += $product_detail['coupon'];
		           
		       }
		        $average_sales = $total/count($order_item1);
		        $average_unit = $qty/count($order_item1);
		        $average_sales_total = $total/count($all_sales);
		        $coupon = $coupon/count($all_sales);
			}
        ?>
            <td><?php echo date('d-m-y',$back2stime); ?></td>
            <td><?php echo currency().number_format((float)$total, 2, '.', ''); ?></td>
            <td><?php echo $qty; ?></td>
            <td><?php echo count($order_item1); ?></td>
            <td><?php echo currency().number_format((float)$average_sales, 2, '.', ''); ?></td>
            <td><?php echo number_format((float)$average_unit, 2, '.', ''); ?></td>
            <td><?php echo number_format((float)$average_sales_total, 2, '.', ''); ?></td>
            <!--<td><?php //echo number_format((float)$average_unit, 2, '.', ''); ?></td>-->
            <!--<td><?php //echo currency().number_format((float)$average_sales, 2, '.', ''); ?></td>-->
            <td><?php echo number_format((float)$coupon, 2, '.', ''); ?></td>
        </tr>
        <tr>
            <?php
            $i = 0;
			$ctime = strtotime(date('d-m-Y 23:59:59',time()));
			$c1time = strtotime(date('d-m-Y 00:00:00',time()));
	        $back2stime=strtotime("-2 day", $c1time);
	        $back2etime=strtotime("-2 day", $ctime);
			$this->db->where('sale_datetime BETWEEN "'.$back2stime.'" AND "'.$back2etime.'"');
			$all_sales = $this->db->get('sale')->result_array();
			$total = 0;
			$qty = 0;
			$average_sales= 0;
			$average_unit = 0;
			$coupon = 0;
			$average_sales_total = 0;
		    foreach($all_sales as $row){
		        $total += $row['grand_total'];
		        
		       $product_details =json_decode($row['product_details'],true);
		       foreach($product_details as $product_detail){
		           $qty +=$product_detail['qty'];
		           $order_item[]=$product_detail['id'];
		           $order_item =  array_unique($order_item);
		           $coupon += $product_detail['coupon'];
		           
		       }
		        $average_sales = $total/count($order_item);
		        $average_unit = $qty/count($order_item);
		        $average_sales_total = $total/count($all_sales);
		        $coupon = $coupon/count($all_sales);
			}
        ?>
            <td><?php echo date('d-m-y',$back2stime); ?></td>
            <td><?php echo currency().number_format((float)$total, 2, '.', ''); ?></td>
            <td><?php echo $qty; ?></td>
            <td><?php echo count($order_item); ?></td>
            <td><?php echo currency().number_format((float)$average_sales, 2, '.', ''); ?></td>
            <td><?php echo number_format((float)$average_unit, 2, '.', ''); ?></td>
            <td><?php echo number_format((float)$average_sales_total, 2, '.', ''); ?></td>
            <!--<td><?php //echo number_format((float)$average_unit, 2, '.', ''); ?></td>-->
            <!--<td><?php //echo currency().number_format((float)$average_sales, 2, '.', ''); ?></td>-->
            <td><?php echo number_format((float)$coupon, 2, '.', ''); ?></td>
        </tr>
<?php */ ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>



		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('orders_breakdown_by_days'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div class="panel-body" id="demo_s">
							<table id="demo-table" class="table table-striped" data-pagination="false" data-show-refresh="false" data-show-toggle="false" data-show-columns="false" data-search="false">

								<thead>
									<tr>
										<th>Date</th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											echo date('d-m-y', $c1time); ?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-1 day", $c1time);
											$back2etime = strtotime("-1 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-2 day", $c1time);
											$back2etime = strtotime("-2 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-3 day", $c1time);
											$back2etime = strtotime("-3 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-4 day", $c1time);
											$back2etime = strtotime("-4 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-5 day", $c1time);
											$back2etime = strtotime("-5 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-6 day", $c1time);
											$back2etime = strtotime("-6 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>


										<th>Total</th>

									</tr>

								</thead>

								<tbody>
									<tr>
										<td>shops</td>
										<td>orders</td>
										<td>orders</td>
										<td>orders</td>
										<td>orders</td>
										<td>orders</td>
										<td>orders</td>
										<td>orders</td>
										<td>orders</td>
									</tr>
									<tr>
										<?php
										$all_vendors = $this->db->get_where('vendor', array('status' => 'approved'))->result_array();
										foreach ($all_vendors as $all_vendor) {

										?>
									<tr>
										<td>
											<?php echo $all_vendor['name']; ?>
										</td>
										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $c1time . '" AND "' . $ctime . '"');
											$this->db->where('store_id', $all_vendor['vendor_id']);
											echo $a = $this->db->get('sale')->num_rows();


											?>

										</td>


										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-1 day", $c1time);
											$back2etime = strtotime("-1 day", $ctime);
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
											$this->db->where('store_id', $all_vendor['vendor_id']);
											echo $b = $this->db->get('sale')->num_rows();


											?>

										</td>

										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-2 day", $c1time);
											$back2etime = strtotime("-2 day", $ctime);
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
											$this->db->where('store_id', $all_vendor['vendor_id']);
											echo $c = $this->db->get('sale')->num_rows();


											?>

										</td>
										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-3 day", $c1time);
											$back2etime = strtotime("-3 day", $ctime);
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
											$this->db->where('store_id', $all_vendor['vendor_id']);
											echo $d = $this->db->get('sale')->num_rows();


											?>

										</td>
										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-4 day", $c1time);
											$back2etime = strtotime("-4 day", $ctime);
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
											$this->db->where('store_id', $all_vendor['vendor_id']);
											echo $e = $this->db->get('sale')->num_rows();


											?>

										</td>
										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-5 day", $c1time);
											$back2etime = strtotime("-5 day", $ctime);
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
											$this->db->where('store_id', $all_vendor['vendor_id']);
											echo $f = $this->db->get('sale')->num_rows();


											?>

										</td>
										<td>
											<?php
											$i = 0;
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-6 day", $c1time);
											$back2etime = strtotime("-6 day", $ctime);
											$where = '(status="success" or status = "admin_pending")';
											$this->db->where($where);
											$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
											$this->db->where('store_id', $all_vendor['vendor_id']);
											echo $g = $this->db->get('sale')->num_rows();


											?>

										</td>
										<td><?php echo $tot = $a + $b + $c + $d + $e + $f + $g; ?></td>
									</tr>

								<?php } ?>

								</tr>

								<tr>
									<td>Total </td>
									<td><?php
										$i = 0;
										$ctime = strtotime(date('d-m-Y 23:59:59', time()));
										$c1time = strtotime(date('d-m-Y 00:00:00', time()));
										$where = '(status="success" or status = "admin_pending")';
										$this->db->where($where);
										$this->db->where('sale_datetime BETWEEN "' . $c1time . '" AND "' . $ctime . '"');
										//$this->db->where('store_id',$all_vendor['vendor_id']);
										echo $a = $this->db->get('sale')->num_rows();


										?> </td>

									<td><?php
										$i = 0;
										$ctime = strtotime(date('d-m-Y 23:59:59', time()));
										$c1time = strtotime(date('d-m-Y 00:00:00', time()));
										$back2stime = strtotime("-1 day", $c1time);
										$back2etime = strtotime("-1 day", $ctime);
										$where = '(status="success" or status = "admin_pending")';
										$this->db->where($where);
										$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
										//$this->db->where('store_id',$all_vendor['vendor_id']);
										echo $a1 = $this->db->get('sale')->num_rows();


										?> </td>
									<td><?php
										$i = 0;
										$ctime = strtotime(date('d-m-Y 23:59:59', time()));
										$c1time = strtotime(date('d-m-Y 00:00:00', time()));
										$back2stime = strtotime("-2 day", $c1time);
										$back2etime = strtotime("-2 day", $ctime);
										$where = '(status="success" or status = "admin_pending")';
										$this->db->where($where);
										$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
										//$this->db->where('store_id',$all_vendor['vendor_id']);
										echo $b1 = $this->db->get('sale')->num_rows();


										?> </td>
									<td><?php
										$i = 0;
										$ctime = strtotime(date('d-m-Y 23:59:59', time()));
										$c1time = strtotime(date('d-m-Y 00:00:00', time()));
										$back2stime = strtotime("-3 day", $c1time);
										$back2etime = strtotime("-3 day", $ctime);
										$where = '(status="success" or status = "admin_pending")';
										$this->db->where($where);
										$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
										//$this->db->where('store_id',$all_vendor['vendor_id']);
										echo $c1 = $this->db->get('sale')->num_rows();


										?> </td>
									<td><?php
										$i = 0;
										$ctime = strtotime(date('d-m-Y 23:59:59', time()));
										$c1time = strtotime(date('d-m-Y 00:00:00', time()));
										$back2stime = strtotime("-4 day", $c1time);
										$back2etime = strtotime("-4 day", $ctime);
										$where = '(status="success" or status = "admin_pending")';
										$this->db->where($where);
										$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
										//$this->db->where('store_id',$all_vendor['vendor_id']);
										echo $d1 = $this->db->get('sale')->num_rows();


										?> </td>
									<td><?php
										$i = 0;
										$ctime = strtotime(date('d-m-Y 23:59:59', time()));
										$c1time = strtotime(date('d-m-Y 00:00:00', time()));
										$back2stime = strtotime("-5 day", $c1time);
										$back2etime = strtotime("-5 day", $ctime);
										$where = '(status="success" or status = "admin_pending")';
										$this->db->where($where);
										$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
										//$this->db->where('store_id',$all_vendor['vendor_id']);
										echo $e1 = $this->db->get('sale')->num_rows();


										?> </td>
									<td><?php
										$i = 0;
										$ctime = strtotime(date('d-m-Y 23:59:59', time()));
										$c1time = strtotime(date('d-m-Y 00:00:00', time()));
										$back2stime = strtotime("-6 day", $c1time);
										$back2etime = strtotime("-6 day", $ctime);
										$where = '(status="success" or status = "admin_pending")';
										$this->db->where($where);
										$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
										//$this->db->where('store_id',$all_vendor['vendor_id']);
										echo $f1 = $this->db->get('sale')->num_rows();


										?> </td>
									<td>
										<?php echo $tot_orders = $a1 + $b1 + $c1 + $d1 + $e1 + $f1; ?>
									</td>
								</tr>
							
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>

        
        <div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('rating'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div class="panel-body" id="demo_s">
							<table id="demo-table" class="table table-striped" data-pagination="false" data-show-refresh="false" data-show-toggle="false" data-show-columns="false" data-search="false">

								<thead>
									<tr>
										<th>Date</th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											echo date('d-m-y', $c1time); ?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-1 day", $c1time);
											$back2etime = strtotime("-1 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-2 day", $c1time);
											$back2etime = strtotime("-2 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-3 day", $c1time);
											$back2etime = strtotime("-3 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-4 day", $c1time);
											$back2etime = strtotime("-4 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-5 day", $c1time);
											$back2etime = strtotime("-5 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-6 day", $c1time);
											$back2etime = strtotime("-6 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>


										

									</tr>
								</thead>

								<tbody>
									<tr>
										<td>Avg Rating</td>
										<td>
											<?php
											$i1 = 0;
											$cstime = strtotime(date('d-m-Y 23:59:59', time()));
											$cs1time = strtotime(date('d-m-Y 00:00:00', time()));
											$this->db->where('status',1);
											$this->db->where('date BETWEEN "' . $cs1time . '" AND "' . $cstime . '"');
											$all_ratings = $this->db->get('review_product')->result_array();
                                            //echo $this->db->last_query(); exit;
											$totals = 0;
											
											foreach ($all_ratings as $row_r) {
												$total_rating += $row_r['rating'];
                                                $rat_count=count($all_ratings);

												
												$over_all = $total_rating /$rat_count;
											}
											?>

											<?php
											echo number_format((float)$over_all, 2, '.', '');
											 ?></td>
                                             <td>
											<?php
											$i2 = 0;
											$cwtime = strtotime(date('d-m-Y 23:59:59', time()));
											$cw1time = strtotime(date('d-m-Y 00:00:00', time()));
											$backw2stime = strtotime("-1 day", $c1time);
											$backw2etime = strtotime("-1 day", $ctime);
											$this->db->where('status',1);
											$this->db->where('date BETWEEN "' . $cs1time . '" AND "' . $cstime . '"');
											$all_ratingsw = $this->db->get('review_product')->result_array();
											$totalw = 0;
											
											foreach ($all_ratingw as $row_w) {
												$total_ratingw += $row_w['rating'];
                                                $rat_countw=count($all_ratingsw);

												
												$over_allw = $total_ratingw /$rat_countw;
											}
											?>
											
										</td>
										<td>
                                        <?php
											$i3 = 0;
											$cwtime3 = strtotime(date('d-m-Y 23:59:59', time()));
											$cw1time3 = strtotime(date('d-m-Y 00:00:00', time()));
											$backw2stime3 = strtotime("-2 day", $c1time3);
											$backw2etime3 = strtotime("-2 day", $ctime3);
											$this->db->where('status',1);
											$this->db->where('date BETWEEN "' . $cs1time3 . '" AND "' . $cstime3 . '"');
											$all_ratingsw3 = $this->db->get('review_product')->result_array();
											$totalw3 = 0;
											
											foreach ($all_ratingw3 as $row_w3) {
												$total_ratingw3 += $row_w3['rating'];
                                                $rat_countw3=count($all_ratingsw3);

												
												$over_allw3 = $total_ratingw3 /$rat_countw3;
											}
											?>
										</td>

										<td>
                                        <?php
											$i4 = 0;
											$cwtime4 = strtotime(date('d-m-Y 23:59:59', time()));
											$cw1time4 = strtotime(date('d-m-Y 00:00:00', time()));
											$backw2stime4 = strtotime("-3 day", $c1time4);
											$backw2etime4 = strtotime("-3 day", $ctime4);
											$this->db->where('status',1);
											$this->db->where('date BETWEEN "' . $cs1time4 . '" AND "' . $cstime4 . '"');
											$all_ratingsw4 = $this->db->get('review_product')->result_array();
											$totalw4 = 0;
											
											foreach ($all_ratingw4 as $row_w4) {
												$total_ratingw4 += $row_w4['rating'];
                                                $rat_countw4=count($all_ratingsw4);

												
												$over_allw4 = $total_ratingw4 /$rat_countw4;
											}
											?>
										</td>

										<td>
                                        <?php
											$i5 = 0;
											$cwtime5 = strtotime(date('d-m-Y 23:59:59', time()));
											$cw1time5 = strtotime(date('d-m-Y 00:00:00', time()));
											$backw2stime5 = strtotime("-4 day", $c1time5);
											$backw2etime5 = strtotime("-4 day", $ctime5);
											$this->db->where('status',1);
											$this->db->where('date BETWEEN "' . $cs1time5 . '" AND "' . $cstime5 . '"');
											$all_ratingsw5 = $this->db->get('review_product')->result_array();
											$totalw5 = 0;
											
											foreach ($all_ratingw5 as $row_w5) {
												$total_ratingw5 += $row_w5['rating'];
                                                $rat_countw5=count($all_ratingsw5);

												
												$over_allw5 = $total_ratingw5 /$rat_countw5;
											}
											?>
										</td>

										<td>
                                        <?php
											$i6 = 0;
											$cwtime6 = strtotime(date('d-m-Y 23:59:59', time()));
											$cw1time6 = strtotime(date('d-m-Y 00:00:00', time()));
											$backw2stime6 = strtotime("-5 day", $c1time6);
											$backw2etime6 = strtotime("-5 day", $ctime6);
											$this->db->where('status',1);
											$this->db->where('date BETWEEN "' . $cs1time6 . '" AND "' . $cstime6 . '"');
											$all_ratingsw6 = $this->db->get('review_product')->result_array();
											$totalw6 = 0;
											
											foreach ($all_ratingw6 as $row_w6) {
												$total_ratingw6 += $row_w6['rating'];
                                                $rat_countw6=count($all_ratingsw6);

												
												$over_allw6 = $total_ratingw6 /$rat_countw6;
											}
											?>
										</td>

										<td>
                                        <?php
											$i7 = 0;
											$cwtime7 = strtotime(date('d-m-Y 23:59:59', time()));
											$cw1time7 = strtotime(date('d-m-Y 00:00:00', time()));
											$backw2stime7 = strtotime("-5 day", $c1time7);
											$backw2etime7 = strtotime("-5 day", $ctime7);
											$this->db->where('status',1);
											$this->db->where('date BETWEEN "' . $cs1time7 . '" AND "' . $cstime7 . '"');
											$all_ratingsw7 = $this->db->get('review_product')->result_array();
											$totalw7 = 0;
											
											foreach ($all_ratingw7 as $row_w7) {
												$total_ratingw7 += $row_w7['rating'];
                                                $rat_countw7=count($all_ratingsw7);

												
												$over_allw7 = $total_ratingw7 /$rat_countw7;
											}
											?>
										</td>

										
										
											
									</tr>
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>

		<!-- sale by hour section -->
		<?php

		$this->db->select('*');
		$this->db->from('vendor');
		$this->db->join('sale', 'sale.store_id = vendor.vendor_id');
		$this->db->where('sale.status', 'admin_pending');
		$this->db->or_where('sale.status', 'success');
		$this->db->or_where('vendor.status', 'approved');
		$this->db->group_by('sale.store_id');
	//$allSales = $this->db->get()->result_array();
		$allSales = $this->db->get();
	//	echo $this->db->last_query();
		// check and put time in time slot
		$formatedTimeList = [];
		foreach ($timeList as $singleTime) {
			$formatedTimeList[$singleTime['start_time'] . '-' . $singleTime['end_time']] = [];
		}
		foreach ($allSales as $singleSale) {
			$singleSaleDate = date('Y-m-d', $singleSale['sale_datetime']);
			$today = date('Y-m-d');
			// print_r('singleSaleDate '.$singleSaleDate);
			// print_r('today '.$today);
			if ($singleSaleDate == $today) {

				$saleTime = date('g:i a', $singleSale['sale_datetime']);
				// print_r($saleTime);
				foreach ($formatedTimeList as $key => $value) {
					// get start and end time
					$startTime = explode('-', $key)[0];
					$endTime = explode('-', $key)[1];
					$formatedSaleTime = explode(':', $saleTime)[0] . ':00 ' . substr(explode(':', $saleTime)[1], -2);
					if ($formatedSaleTime == $startTime) {
						$formatedTimeList[$startTime . '-' . $endTime][] = $singleSale['grand_total'];
					}
				}
			}
		}

		function sum($carry, $item)
		{
			$carry += $item;
			return $carry;
		}
		// echo '<pre>';
		// print_r($formatedTimeList);
		// amount of order related section php code
		$formatedDaysList = [];
		$formatedDaysListToGetOrderAverage = [];
		foreach ($lastSevenDays as $singleDay) {
			$formatedDaysList[$singleDay] = 0;
			$formatedDaysListToGetOrderAverage[$singleDay] = 0;
		}
		foreach ($allSales as $singleSale) {
			foreach ($formatedDaysList as $keyDay => $value) {
				if ($keyDay == date('Y-m-d', $singleSale['sale_datetime'])) {
					$formatedDaysList[$keyDay] = $value++;
				}
			}
		}
		foreach ($allSales as $singleSale) {
			foreach ($formatedDaysListToGetOrderAverage as $keyDay => $value) {
				if ($keyDay == date('Y-m-d', $singleSale['sale_datetime'])) {
					$formatedDaysListToGetOrderAverage[$keyDay] = $singleSale['grand_total'] / $formatedDaysList[$keyDay];
				}
			}
		}
		// print_r($formatedDaysList);
		?>

		<!-- amount of orders -->

		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							Amount Of Orders
						</h3>
					</div>
					<div class="panel-body">
						<div class="panel-body" id="demo_s">
							<table id="demo-table" class="table table-striped" data-pagination="false" data-show-refresh="false" data-show-toggle="false" data-show-columns="false" data-search="false">

								<thead>
									<tr>
										<th>Store Name</th>
										<?php foreach ($formatedDaysList as $key => $singleDay) : ?>
											<th><?php echo $key ?></th>
										<?php endforeach ?>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($allSales as $singleSale) : ?>
										<tr>
											<td><?php echo $singleSale['name'] ?></td>
											<?php foreach ($formatedDaysList as $keyDay => $count) : ?>
												<td><?php echo $count ?></td>
											<?php endforeach ?>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- average order value -->

		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							Average Order Value
						</h3>
					</div>
					<div class="panel-body">
						<div class="panel-body" id="demo_s">
							<table id="demo-table" class="table table-striped" data-pagination="false" data-show-refresh="false" data-show-toggle="false" data-show-columns="false" data-search="false">

								<thead>
									<tr>
										<th>Store Name</th>
										<?php foreach ($formatedDaysListToGetOrderAverage as $key => $singleDay) : ?>
											<th><?php echo $key ?></th>
										<?php endforeach ?>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($allSales as $singleSale) : ?>
										<tr>
											<td><?php echo $singleSale['name'] ?></td>
											<?php foreach ($formatedDaysListToGetOrderAverage as $keyDay => $value) : ?>
												<td><?php echo $value ?></td>
											<?php endforeach ?>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							Sales By Hour
						</h3>
					</div>
					<div class="panel-body">
						<div class="panel-body" id="demo_s">
							<div class="scrollme">
								<table id="demo-table" class="table table-responsive table-striped" data-pagination="false" data-show-refresh="false" data-show-toggle="false" data-show-columns="false" data-search="false">
									<thead>
										<tr>
											<th><strong>Store Name</strong></th>
											<?php foreach ($formatedTimeList as $slot => $data) : ?>

												<th><strong><?php echo  $slot ?></strong></th>
											<?php endforeach ?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($allSales as $singleSale) : ?>

											<tr>
												<td><?php echo $singleSale['name'] ?></td>
												<?php foreach ($formatedTimeList as $slot => $singleTimeList) : ?>
													<?php

													$result = array_reduce($singleTimeList, 'sum');
													?>
													<td><?php echo $result ? currency() . $result : '-' ?></td>
												<?php endforeach ?>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">

							<?php
                            
                            echo translate('new_customers_breakdown_&_retention'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div class="panel-body" id="demo_s">
							<table id="demo-table" class="table table-striped" data-pagination="false" data-show-refresh="false" data-show-toggle="false" data-show-columns="false" data-search="false">

								<thead>
									<tr>
										<th>Date</th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											echo date('d-m-y', $c1time); ?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-1 day", $c1time);
											$back2etime = strtotime("-1 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-2 day", $c1time);
											$back2etime = strtotime("-2 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-3 day", $c1time);
											$back2etime = strtotime("-3 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-4 day", $c1time);
											$back2etime = strtotime("-4 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-5 day", $c1time);
											$back2etime = strtotime("-5 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-6 day", $c1time);
											$back2etime = strtotime("-6 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>

										<th>Total</th>


									</tr>

								</thead>

								<tbody>

									<tr>


									<tr>
										<td>
											orders
										</td>
										<td>
											<?php
											$i = 0;


											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-7 day", $c1time);
											$back2etime = strtotime("-7 day", $ctime);
											$this->db->where('creation_date BETWEEN "' . $back2stime . '" AND "' . $ctime . '"');
											$all_vendors = $this->db->get('user')->result_array();
											//echo $this->db->last_query();
											$a = 0;
											$tot = 0;
											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$where = '(status="success" or status = "admin_pending")';
												$this->db->where($where);
												$this->db->where('sale_datetime BETWEEN "' . $c1time . '" AND "' . $ctime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$a += $this->db->get('sale')->num_rows();
											}
											echo $tot += $a;

											?>

										</td>


										<td>
											<?php

											$b = 0;
											$totb = 0;
											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$back2stime = strtotime("-1 day", $c1time);
												$back2etime = strtotime("-1 day", $ctime);
												$where = '(status="success" or status = "admin_pending")';
												$this->db->where($where);
												$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$b += $this->db->get('sale')->num_rows();
											}
											echo $totb += $b;


											?>

										</td>

										<td>
											<?php

											$c = 0;
											$totc = 0;
											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$back2stime = strtotime("-2 day", $c1time);
												$back2etime = strtotime("-2 day", $ctime);
												$where = '(status="success" or status = "admin_pending")';
												$this->db->where($where);
												$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$c += $this->db->get('sale')->num_rows();
											}
											echo $totc += $c;


											?>

										</td>
										<td>
											<?php
											$d = 0;
											$totd = 0;

											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$back2stime = strtotime("-3 day", $c1time);
												$back2etime = strtotime("-3 day", $ctime);
												$this->db->where('status', 'admin_pending');
												$this->db->or_where('status', 'success');
												$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$d += $this->db->get('sale')->num_rows();
											}
											echo $totd += $d;


											?>

										</td>
										<td>
											<?php
											$e = 0;
											$tote = 0;
											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$back2stime = strtotime("-4 day", $c1time);
												$back2etime = strtotime("-4 day", $ctime);
												$where = '(status="success" or status = "admin_pending")';
												$this->db->where($where);
												$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$e += $this->db->get('sale')->num_rows();
											}
											echo $tote += $e;


											?>

										</td>
										<td>
											<?php
											$f = 0;
											$totf = 0;
											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$back2stime = strtotime("-5 day", $c1time);
												$back2etime = strtotime("-5 day", $ctime);
												$this->db->where('status', 'admin_pending');
												$this->db->or_where('status', 'success');
												$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$f += $this->db->get('sale')->num_rows();
											}
											echo $totf += $f;


											?>

										</td>
										<td>
											<?php
											$g = 0;
											$totg = 0;
											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$back2stime = strtotime("-5 day", $c1time);
												$back2etime = strtotime("-5 day", $ctime);
												$this->db->where('status', 'admin_pending');
												$this->db->or_where('status', 'success');
												$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$g += $this->db->get('sale')->num_rows();
											}
											echo $totg += $g;


											?>

										</td>
										<td><?php echo $tot = $a + $b + $c + $d + $e + $f + $g; ?></td>
									</tr>



									</tr>


									<?php /*?>        <tr>
            <?php
            $i = 0;
			$ctime = strtotime(date('d-m-Y 23:59:59',time()));
			$c1time = strtotime(date('d-m-Y 00:00:00',time()));
	        $back2stime=strtotime("-1 day", $c1time);
	        $back2etime=strtotime("-1 day", $ctime);
			$this->db->where('sale_datetime BETWEEN "'.$back2stime.'" AND "'.$back2etime.'"');
			$all_sales = $this->db->get('sale')->result_array();
			$total = 0;
			$qty = 0;
			$average_sales= 0;
			$average_unit = 0;
			$coupon = 0;
			$average_sales_total = 0;
		    foreach($all_sales as $row){
		        $total += $row['grand_total'];
		        
		       $product_details =json_decode($row['product_details'],true);
		       foreach($product_details as $product_detail){
		           $qty +=$product_detail['qty'];
		           $order_item1[]=$product_detail['id'];
		           $order_item1 =  array_unique($order_item1);
		           $coupon += $product_detail['coupon'];
		           
		       }
		        $average_sales = $total/count($order_item1);
		        $average_unit = $qty/count($order_item1);
		        $average_sales_total = $total/count($all_sales);
		        $coupon = $coupon/count($all_sales);
			}
        ?>
            <td><?php echo date('d-m-y',$back2stime); ?></td>
            <td><?php echo currency().number_format((float)$total, 2, '.', ''); ?></td>
            <td><?php echo $qty; ?></td>
            <td><?php echo count($order_item1); ?></td>
            <td><?php echo currency().number_format((float)$average_sales, 2, '.', ''); ?></td>
            <td><?php echo number_format((float)$average_unit, 2, '.', ''); ?></td>
            <td><?php echo number_format((float)$average_sales_total, 2, '.', ''); ?></td>
            <!--<td><?php //echo number_format((float)$average_unit, 2, '.', ''); ?></td>-->
            <!--<td><?php //echo currency().number_format((float)$average_sales, 2, '.', ''); ?></td>-->
            <td><?php echo number_format((float)$coupon, 2, '.', ''); ?></td>
        </tr>
        <tr>
            <?php
            $i = 0;
			$ctime = strtotime(date('d-m-Y 23:59:59',time()));
			$c1time = strtotime(date('d-m-Y 00:00:00',time()));
	        $back2stime=strtotime("-2 day", $c1time);
	        $back2etime=strtotime("-2 day", $ctime);
			$this->db->where('sale_datetime BETWEEN "'.$back2stime.'" AND "'.$back2etime.'"');
			$all_sales = $this->db->get('sale')->result_array();
			$total = 0;
			$qty = 0;
			$average_sales= 0;
			$average_unit = 0;
			$coupon = 0;
			$average_sales_total = 0;
		    foreach($all_sales as $row){
		        $total += $row['grand_total'];
		        
		       $product_details =json_decode($row['product_details'],true);
		       foreach($product_details as $product_detail){
		           $qty +=$product_detail['qty'];
		           $order_item[]=$product_detail['id'];
		           $order_item =  array_unique($order_item);
		           $coupon += $product_detail['coupon'];
		           
		       }
		        $average_sales = $total/count($order_item);
		        $average_unit = $qty/count($order_item);
		        $average_sales_total = $total/count($all_sales);
		        $coupon = $coupon/count($all_sales);
			}
        ?>
            <td><?php echo date('d-m-y',$back2stime); ?></td>
            <td><?php echo currency().number_format((float)$total, 2, '.', ''); ?></td>
            <td><?php echo $qty; ?></td>
            <td><?php echo count($order_item); ?></td>
            <td><?php echo currency().number_format((float)$average_sales, 2, '.', ''); ?></td>
            <td><?php echo number_format((float)$average_unit, 2, '.', ''); ?></td>
            <td><?php echo number_format((float)$average_sales_total, 2, '.', ''); ?></td>
            <!--<td><?php //echo number_format((float)$average_unit, 2, '.', ''); ?></td>-->
            <!--<td><?php //echo currency().number_format((float)$average_sales, 2, '.', ''); ?></td>-->
            <td><?php echo number_format((float)$coupon, 2, '.', ''); ?></td>
        </tr>
<?php */ ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('existing_customers_breakdown_&_retention'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div class="panel-body" id="demo_s">
							<table id="demo-table" class="table table-striped" data-pagination="false" data-show-refresh="false" data-show-toggle="false" data-show-columns="false" data-search="false">

								<thead>
									<tr>
										<th>Date</th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											echo date('d-m-y', $c1time); ?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-1 day", $c1time);
											$back2etime = strtotime("-1 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-2 day", $c1time);
											$back2etime = strtotime("-2 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-3 day", $c1time);
											$back2etime = strtotime("-3 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-4 day", $c1time);
											$back2etime = strtotime("-4 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-5 day", $c1time);
											$back2etime = strtotime("-5 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>
										<th><?php
											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-6 day", $c1time);
											$back2etime = strtotime("-6 day", $ctime);
											echo date('d-m-y', $back2stime);
											?></th>

										<th>Total</th>


									</tr>

								</thead>

								<tbody>

									<tr>


									<tr>
										<td>
											orders
										</td>
										<td>
											<?php
											$i = 0;


											$ctime = strtotime(date('d-m-Y 23:59:59', time()));
											$c1time = strtotime(date('d-m-Y 00:00:00', time()));
											$back2stime = strtotime("-7 day", $c1time);
											$back2etime = strtotime("-7 day", $ctime);
											$this->db->where('creation_date<', $back2etime);
											$all_vendors = $this->db->get('user')->result_array();
											//echo $this->db->last_query(); exit;
											$a = 0;
											$tot = 0;
											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$where = '(status="success" or status = "admin_pending")';
												$this->db->where($where);
												$this->db->where('sale_datetime BETWEEN "' . $c1time . '" AND "' . $ctime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$a += $this->db->get('sale')->num_rows();
											}
											echo $tot += $a;

											?>

										</td>


										<td>
											<?php

											$b = 0;
											$totb = 0;
											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$back2stime = strtotime("-1 day", $c1time);
												$back2etime = strtotime("-1 day", $ctime);
												$where = '(status="success" or status = "admin_pending")';
												$this->db->where($where);
												$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$b += $this->db->get('sale')->num_rows();
											}
											echo $totb += $b;


											?>

										</td>

										<td>
											<?php

											$c = 0;
											$totc = 0;
											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$back2stime = strtotime("-2 day", $c1time);
												$back2etime = strtotime("-2 day", $ctime);
												$where = '(status="success" or status = "admin_pending")';
												$this->db->where($where);
												$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$c += $this->db->get('sale')->num_rows();
											}
											echo $totc += $c;


											?>

										</td>
										<td>
											<?php
											$d = 0;
											$totd = 0;

											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$back2stime = strtotime("-3 day", $c1time);
												$back2etime = strtotime("-3 day", $ctime);
												$where = '(status="success" or status = "admin_pending")';
												$this->db->where($where);
												$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$d += $this->db->get('sale')->num_rows();
											}
											echo $totd += $d;


											?>

										</td>
										<td>
											<?php
											$e = 0;
											$tote = 0;
											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$back2stime = strtotime("-4 day", $c1time);
												$back2etime = strtotime("-4 day", $ctime);
												$where = '(status="success" or status = "admin_pending")';
												$this->db->where($where);
												$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$e += $this->db->get('sale')->num_rows();
											}
											echo $tote += $e;


											?>

										</td>
										<td>
											<?php
											$f = 0;
											$totf = 0;
											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$back2stime = strtotime("-5 day", $c1time);
												$back2etime = strtotime("-5 day", $ctime);
												$where = '(status="success" or status = "admin_pending")';
												$this->db->where($where);
												$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$f += $this->db->get('sale')->num_rows();
											}
											echo $totf += $f;


											?>

										</td>
										<td>
											<?php
											$g = 0;
											$totg = 0;
											foreach ($all_vendors as $all_vendor) {
												$ctime = strtotime(date('d-m-Y 23:59:59', time()));
												$c1time = strtotime(date('d-m-Y 00:00:00', time()));
												$back2stime = strtotime("-5 day", $c1time);
												$back2etime = strtotime("-5 day", $ctime);
												$where = '(status="success" or status = "admin_pending")';
												$this->db->where($where);
												$this->db->where('sale_datetime BETWEEN "' . $back2stime . '" AND "' . $back2etime . '"');
												$this->db->where('buyer', $all_vendor['user_id']);
												$g += $this->db->get('sale')->num_rows();
											}
											echo $totg += $g;


											?>

										</td>
										<td><?php echo $tot = $a + $b + $c + $d + $e + $f + $g; ?></td>
									</tr>



									</tr>


									<?php /*?>        <tr>
            <?php
            $i = 0;
			$ctime = strtotime(date('d-m-Y 23:59:59',time()));
			$c1time = strtotime(date('d-m-Y 00:00:00',time()));
	        $back2stime=strtotime("-1 day", $c1time);
	        $back2etime=strtotime("-1 day", $ctime);
			$this->db->where('sale_datetime BETWEEN "'.$back2stime.'" AND "'.$back2etime.'"');
			$all_sales = $this->db->get('sale')->result_array();
			$total = 0;
			$qty = 0;
			$average_sales= 0;
			$average_unit = 0;
			$coupon = 0;
			$average_sales_total = 0;
		    foreach($all_sales as $row){
		        $total += $row['grand_total'];
		        
		       $product_details =json_decode($row['product_details'],true);
		       foreach($product_details as $product_detail){
		           $qty +=$product_detail['qty'];
		           $order_item1[]=$product_detail['id'];
		           $order_item1 =  array_unique($order_item1);
		           $coupon += $product_detail['coupon'];
		           
		       }
		        $average_sales = $total/count($order_item1);
		        $average_unit = $qty/count($order_item1);
		        $average_sales_total = $total/count($all_sales);
		        $coupon = $coupon/count($all_sales);
			}
        ?>
            <td><?php echo date('d-m-y',$back2stime); ?></td>
            <td><?php echo currency().number_format((float)$total, 2, '.', ''); ?></td>
            <td><?php echo $qty; ?></td>
            <td><?php echo count($order_item1); ?></td>
            <td><?php echo currency().number_format((float)$average_sales, 2, '.', ''); ?></td>
            <td><?php echo number_format((float)$average_unit, 2, '.', ''); ?></td>
            <td><?php echo number_format((float)$average_sales_total, 2, '.', ''); ?></td>
            <!--<td><?php //echo number_format((float)$average_unit, 2, '.', ''); ?></td>-->
            <!--<td><?php //echo currency().number_format((float)$average_sales, 2, '.', ''); ?></td>-->
            <td><?php echo number_format((float)$coupon, 2, '.', ''); ?></td>
        </tr>
        <tr>
            <?php
            $i = 0;
			$ctime = strtotime(date('d-m-Y 23:59:59',time()));
			$c1time = strtotime(date('d-m-Y 00:00:00',time()));
	        $back2stime=strtotime("-2 day", $c1time);
	        $back2etime=strtotime("-2 day", $ctime);
			$this->db->where('sale_datetime BETWEEN "'.$back2stime.'" AND "'.$back2etime.'"');
			$all_sales = $this->db->get('sale')->result_array();
			$total = 0;
			$qty = 0;
			$average_sales= 0;
			$average_unit = 0;
			$coupon = 0;
			$average_sales_total = 0;
		    foreach($all_sales as $row){
		        $total += $row['grand_total'];
		        
		       $product_details =json_decode($row['product_details'],true);
		       foreach($product_details as $product_detail){
		           $qty +=$product_detail['qty'];
		           $order_item[]=$product_detail['id'];
		           $order_item =  array_unique($order_item);
		           $coupon += $product_detail['coupon'];
		           
		       }
		        $average_sales = $total/count($order_item);
		        $average_unit = $qty/count($order_item);
		        $average_sales_total = $total/count($all_sales);
		        $coupon = $coupon/count($all_sales);
			}
        ?>
            <td><?php echo date('d-m-y',$back2stime); ?></td>
            <td><?php echo currency().number_format((float)$total, 2, '.', ''); ?></td>
            <td><?php echo $qty; ?></td>
            <td><?php echo count($order_item); ?></td>
            <td><?php echo currency().number_format((float)$average_sales, 2, '.', ''); ?></td>
            <td><?php echo number_format((float)$average_unit, 2, '.', ''); ?></td>
            <td><?php echo number_format((float)$average_sales_total, 2, '.', ''); ?></td>
            <!--<td><?php //echo number_format((float)$average_unit, 2, '.', ''); ?></td>-->
            <!--<td><?php //echo currency().number_format((float)$average_sales, 2, '.', ''); ?></td>-->
            <td><?php echo number_format((float)$coupon, 2, '.', ''); ?></td>
        </tr>
<?php */ ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('top_(10)_frequently_sold_products'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div class="panel-body" id="demo_s">
							<table id="demo-table" class="table table-striped" data-pagination="false" data-show-refresh="false" data-show-toggle="false" data-show-columns="false" data-search="false">

								<thead>

									<tr>

										<td>Product name</td>
										<td>Orders count</td>
									</tr>
								</thead>

								<tbody>
									<?php
									$this->db->limit(10);
									$set = 'desc';
									$this->db->order_by('selling_view', $set);
									$all_pros = $this->db->get_where('product', array('status' => 'ok'))->result_array();
									//echo  $this->db->last_query();
									foreach ($all_pros as $top_sale) {  ?>
										<tr>

											<td><?php echo $top_sale['title']; ?></td>
											<td><?php echo $top_sale['selling_view']; ?></td>

										</tr>
										<tr>
										<?php } ?>



								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>


		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('most_top_10_view_products'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div class="panel-body" id="demo_s">
							<table id="demo-table" class="table table-striped" data-pagination="false" data-show-refresh="false" data-show-toggle="false" data-show-columns="false" data-search="false">

								<thead>

									<tr>

										<td>Product name</td>
										<td>View count</td>
									</tr>
								</thead>

								<tbody>
									<?php
									$this->db->limit(10);
									$set = 'desc';
									$this->db->order_by('number_of_view', $set);
									$all_pros = $this->db->get_where('product', array('status' => 'ok'))->result_array();
									//echo  $this->db->last_query();
									foreach ($all_pros as $top_sale) {  ?>
										<tr>

											<td><?php echo $top_sale['title']; ?></td>
											<td><?php echo $top_sale['number_of_view']; ?></td>

										</tr>
										<tr>
										<?php } ?>



								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('latest_products'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div class="panel-body" id="demo_s">
							<table id="demo-table" class="table table-striped" data-pagination="false" data-show-refresh="false" data-show-toggle="false" data-show-columns="false" data-search="false">

								<thead>

									<tr>

										<td>Product name</td>
										<td>created_date</td>
									</tr>
								</thead>

								<tbody>
									<?php
									$this->db->limit(10);
									$set = 'desc';
									$this->db->order_by('product_id', $set);
									$all_pros = $this->db->get_where('product', array('status' => 'ok'))->result_array();
									//echo  $this->db->last_query();
									foreach ($all_pros as $top_sale) {  ?>
										<tr>

											<td><?php echo $top_sale['title']; ?></td>
											<td><?php echo date('d/m/Y', $top_sale['add_timestamp']); ?></td>

										</tr>
										<tr>
										<?php } ?>



								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>


		<div class="row hidden">
			<div class="col-md-6 col-lg-6">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('category_wise_monthly_sale'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div id="chartdiv2" style="width: 100%; height: 300px;"></div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('category_wise_monthly_grand_profit'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div id="chartdiv4" style="width: 100%; height: 300px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php
$ago = time() - 86400;
$result = $this->db->get_where('stock', array('datetime >= ' => $ago, 'datetime <= ' => time()))->result_array();
$result2 = $this->db->get_where('sale', array('sale_datetime >= ' => $ago, 'sale_datetime <= ' => time()))->result_array();
$stock = 0;
foreach ($result as $row) {
	if ($row['type'] == 'add') {
		$stock += $row['total'];
	}
}
$destroy = 0;
foreach ($result as $row) {
	if ($row['type'] == 'destroy') {
		if ($row['reason_note'] !== 'sale') {
			$destroy += $row['total'];
		}
	}
}
$sale = 0;
foreach ($result2 as $row) {
	$sale += $row['grand_total'];
}
?>


<script>
	var base_url = '<?php echo base_url(); ?>';
	var stock = <?php if ($stock == 0) {
					echo .1;
				} else {
					echo $stock;
				} ?>;
	var stock_max = <?php echo ($stock * 3.5 / 3 + 100); ?>;
	var destroy = <?php if ($destroy == 0) {
						echo .1;
					} else {
						echo $destroy;
					} ?>;
	var destroy_max = <?php echo ($destroy * 3.5 / 3 + 100); ?>;
	var sale = <?php if ($sale == 0) {
					echo .1;
				} else {
					echo $sale;
				} ?>;
	var sale_max = <?php echo ($sale * 3.5 / 3 + 100); ?>;
	var currency = '<?php echo currency('', 'def'); ?>';
	var cost_txt = '<?php echo translate('cost'); ?>(<?php echo currency('', 'def'); ?>)';
	var value_txt = '<?php echo translate('value'); ?>(<?php echo currency('', 'def'); ?>)';
	var loss_txt = '<?php echo translate('loss'); ?>(<?php echo currency('', 'def'); ?>)';
	var pl_txt = '<?php echo translate('count'); ?>';

	var sale_details = [
		<?php
		$this->db->where('delivery_status', 'pending');
		$sales = $this->db->get('sale')->result_array();
		foreach ($sales as $row) {
			$orders 	= json_decode($row['shipping_address'], true);
			$address 	= str_replace("'", "", $orders['address1']) . ' ' . str_replace("'", "", $orders['address2']);
			$langlat 	= explode(',', str_replace('(', '', str_replace(')', '', $orders['langlat'])));
			$grand_total = $row['grand_total'];
		?>['<?php echo $address; ?>', <?php echo $langlat[0]; ?>, <?php echo $langlat[1]; ?>, '<?php echo currency('', 'def') . $this->cart->format_number($grand_total); ?>'],
		<?php } ?>
	];

	var sale_details1 = [];

	var chartData1 = [
		<?php
		$vens = $this->db->get('vendor')->result_array();
		foreach ($vens as $ven_us) {
			$where = '(status="success" or status = "admin_pending")';
			$this->db->where($where);
			//$this->db->or_where('status', 'success');
			$result2s = $this->db->get_where('sale', array('store_id' => $ven_us['vendor_id']))->result_array();
			//	echo $this->db->last_query(); exit;
			$stocks = 0;
			foreach ($result2s as $rows) {
				//print_r($row);
				$stockss += $rows['grand_total'];
			}
		?> {
				"country": "<?php echo $ven_us['name']; ?>",
				"visits": <?php echo $stockss; ?>,
				"color": "#458fd2"
			},
		<?php
		}
		?>
	];
    	var chartRate1 = [
		<?php
		$ratings=$this->db->query("select p.title as title,sum(rp.rating)/count(rp.rating) as AverageRating from review_product rp,product p where p.product_id=rp.product_id GROUP By rp.product_id")->result_array();
	
		foreach ($ratings as $rate) {
		
		?> {
				"country": "<?php echo $rate['title']; ?>",
				"visits": "<?php echo $rate['AverageRating']; ?>",
				"color": "#458fd2"
			},
		<?php
		}
		?>
	];
	var chartData2 = [
		<?php
		$categories = $this->db->get('category')->result_array();
		foreach ($categories as $row) {
			$this->crud_model->month_total('sale', 'category', $row['category_id']);
		?> {
				"country": "<?php echo $row['category_name']; ?>",
				"visits": <?php echo $this->crud_model->month_total('sale', 'category', $row['category_id']); ?>,
				"color": "#00a65a"
			},
		<?php
		}
		?>
	];

var chartData3 = [
		<?php
		$user1 = $this->db->get('vendor')->result_array();
		foreach ($user1 as $row_u1) {
		$ctime1 = strtotime(date('d-m-Y 23:59:59', time()));
		$c1time1 = strtotime(date('d-m-Y 00:00:00', time()));
		$this->db->where('sale_datetime BETWEEN "' . $c1time1 . '" AND "' . $ctime1 . '"');
		$this->db->where('store_id',$row_u1['vendor_id']);
		$stock1 = $this->db->get('sale')->num_rows();
      
			//$stock = 0;
			//foreach ($all_sales1 as $row1) {
				//print_r($row);
			//	$stock1 = $row1['no_of_visitors'];
		//	}
		?> {
				"country": "<?php echo $row_u1['name']; ?>",
				"visits": <?php echo $stock1; ?>,
				"color": "#458fd2"
			},
		<?php
		}
		?>
	];

	var chartData4 = [
		<?php
		$categories = $this->db->get('category')->result_array();
		foreach ($categories as $row) {
			$fin = ($this->crud_model->month_total('sale', 'category', $row['category_id'])) - ($this->crud_model->month_total('stock', 'category', $row['category_id'], 'type', 'add'));
		?> {
				"country": "<?php echo $row['category_name']; ?>",
				"visits": <?php echo $fin; ?>,
				"color": "#458fd2"
			},
		<?php
		}
		?>
	];

	<?php /*?>var chartData5 = [
		{
			"country": "Default",
			"visits": <?php echo $this->db->get_where('vendor',array('membership'=>'0'))->num_rows(); ?> ,
			"color": "#458fd2"
		},
		<?php
			$membership_type = $this->db->get('membership')->result_array();
			foreach($membership_type as $row) {
				$fin = $this->db->get_where('vendor',array('membership'=>$row['membership_id']))->num_rows();
		?>
		{
			"country": "<?php echo $row['title']; ?>",
			"visits": <?php echo $fin; ?> ,
			"color": "#458fd2"
		},
		<?php
		}
		?>
	];<?php */ ?>
	var chartData5 = [
		<?php
		$user = $this->db->get('vendor')->result_array();
		foreach ($user as $row_u) {
			//$fin = ($this->crud_model->month_total('sale', 'category', $row['category_id'])) - ($this->crud_model->month_total('stock', 'category', $row['category_id'], 'type', 'add'));
			//$u_id=	$this->db->distinct($row_u['user_id']);

			$result2 = $this->db->get_where('vendor', array('vendor_id' => $row_u['vendor_id']))->result_array();
			//echo "qry".$this->db->last_query(); exit;
			$stock = 0;
			foreach ($result2 as $row) {
				//print_r($row);
				$stock = $row['no_of_visitors'];
			}
		?> {
				"country": "<?php echo $row_u['name']; ?>",
				"visits": <?php echo $stock; ?>,
				"color": "#458fd2"
			},
		<?php
		}
		?>
	];
	<?php /*?>var chartData6 = [
		<?php
			$ven = $this->db->get('vendor')->result_array();
		//	echo $this->db->last_query(); exit;
			foreach($ven as $ven_u) {
				//$fin = ($this->crud_model->month_total('sale', 'category', $row['category_id'])) - ($this->crud_model->month_total('stock', 'category', $row['category_id'], 'type', 'add'));
			//$u_id=	$this->db->distinct($row_u['user_id']);
				
				$result2 = $this->db->get_where('sale',array('store_id'=>$ven_u['vendor_id']))->result_array();
			//	echo $this->db->last_query(); exit;
	$stocks = 0;
	foreach($result2 as $row){
		
			$stocks += $row['grand_total'];
		}
		?>
		{
			"country": "<?php echo $ven_u['name']; ?>",
			"visits": <?php echo $stocks; ?> ,
			"color": "#458fd2"
		},
		<?php
	}
		?>
	];<?php */ ?>

	<?php /*?>var chartData6 = [
		<?php
			$ven = $this->db->get('vendor')->result_array();
			foreach($ven as $ven_u) {
				$result2 = $this->db->get_where('sale',array('store_id'=>$ven_u['vendor_id']))->result_array();
				foreach($result2 as $row){
		
			$stocks += $row['grand_total'];
		}
		?>
		{
			"country": "<?php echo $ven_u['name']; ?>",
			"visits": <?php echo $stocks; ?> ,
			"color": "#458fd2"
		},
		<?php
		}
		?>
	];<?php */ ?>
</script>
<script src="<?php echo base_url(); ?>template/back/js/custom/dashboard.js"></script>
<style>
	#actions {
		list-style: none;
		padding: 0;
	}

	.scrollme {
		width: 75vw;
		overflow-x: auto;
	}

	#inline-actions {
		padding-top: 10px;
	}

	.item {
		margin-left: 20px;
	}

	.page-header {
		color: #333;
	}

	.clred {
		color: #fff;
	}

	.clred .panel-heading::after {
		border-bottom: 1px solid #fff;
	}
</style>

<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Customer Demographics (Age & Gender)"
	},
	axisY:{
		includeZero: true
	},
	legend:{
		cursor: "pointer",
		verticalAlign: "center",
		horizontalAlign: "right",
		itemclick: toggleDataSeries
	},
	data: [{
		type: "column",
		name: "Male",
		indexLabel: "{y}",
		yValueFormatString: "#",
		showInLegend: true,
		dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
	},{
		type: "column",
		name: "Female",
		indexLabel: "{y}",
		yValueFormatString: "#",
		showInLegend: true,
		dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart.render();
}
 
}
</script>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>