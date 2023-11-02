<link rel="stylesheet" href="<?php echo base_url(); ?>template/back//amcharts/style.css" type="text/css">
<script src="<?php echo base_url(); ?>template/back/amcharts/amcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/serial.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/plugins/morris-js/morris.min.js"></script>
<script src="<?php echo base_url(); ?>template/back/plugins/gauge-js/gauge.min.js"></script>
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/pie.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<div id="content-container">
	<div class="content-wrapper-before hidden"></div>    
	<div id="page-title"><br />
		<h3 class="page-header text-overflow"><?php echo translate('dashboard'); ?></h3>
	</div>
	<div id="page-title">
		<h3 class="page-header text-overflow"><?php echo translate('overview'); ?></h3>
	</div>
	<div id="page-content">
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<div class="panel panel-bordered">
				<div class="panel-heading">
					<h3 class="panel-title">
						<?php echo translate('Sales_by_shop'); ?>
					</h3>
				</div>
				<div class="panel-body">
          			<div id="settings-icon"  style="margin-left: 73%;margin-top: 19px;">
						<i class="fa fa-cog"></i>
						<i class="fa fa-expand" style="margin-left: 20px;"></i>
						<i class="fa fa-bars" id="menu-icon" style="margin-left: 20px;"></i>
						<div id="sort-dropdown" class="dropdown-content">
							<a href="#" id="sort-high-low">Sort by Value (High to Low)</a>
							<a href="#" id="sort-low-high">Sort by Value (Low to High)</a>
						</div>
                    	<i class="fa fa-filter" style="margin-left: 20px;"></i>
                      	<div id="funnel-dropdown" class="dropdown-content form">
                          	<form id="date-filter-form" class="p-3">
                              	<div class="mb-3">
                                  	<label for="date-from" class="form-label">Date From:</label>
                                  	<input type="date" class="form-control" id="date-from" name="date-from">
                              	</div>
								<div class="mb-3">
									<label for="date-to" class="form-label">Date To:</label>
									<input type="date" class="form-control" id="date-to" name="date-to">
								</div>
								<div class="mb-3">
									<label for="date-type" class="form-label">Date Type:</label>
									<select class="form-select" id="date-type" name="date-type">
										<option value="option1">Option 1</option>
										<option value="option2">Option 2</option>
									</select>
								</div>
                              	<button type="button" class="btn btn-primary" id="apply-date-filter">Apply</button>
                          	</form>
                      	</div>
                  	</div>
                    <div id="dropdown" class="dropdown-content">
                        <a href="#" id="download-excel">Download Excel</a>
                        <a href="#" id="download-csv">Download CSV</a>
                    </div>
                </div>
				<div id="chartdiv" style="width: 100%; height: 300px;">          
          		</div>
			</div>
				</div>
			</div>
		
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('sales_over_time'); ?></h3>
					</div>
					<div class="panel-body">
						<?php
						 $this->db->select('vendor_id,name');
						$vendor_list=$this->db->get('vendor')->result_array();
						print_r($vendor_list);

						$sales_over_time_list = $this->dashboard_model->overview_sales_over_time('','','');
						print_r($sales_over_time_list); 
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('Sales_Breakdown_by_Days'); ?>
						</h3>
					</div>
					<div class="panel-body">
					<?php
						$this->db->select('vendor_id,name');
						$vendor_list=$this->db->get('vendor')->result_array();
						print_r($vendor_list);

						$Sales_Breakdown_by_Days = $this->dashboard_model->Sales_Breakdown_by_Days();
						print_r($Sales_Breakdown_by_Days);
					?>
						<div id="chartdivbr" style="width: 100%; height: 300px;"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('visitors_(shops_overview)'); ?></h3>
					</div>
					<div class="panel-body">
						<?php
						
						$this->db->select('vendor_id,name');
						$vendor_list=$this->db->get('vendor')->result_array();
						print_r($vendor_list);
						$this->db->select('user_id,username');
						$visitors_list=$this->db->get('user')->result_array();
						print_r($visitors_list);
						
						$visitors_shops_overview_list = $this->dashboard_model->overview_visitors_shops_overview('','');
						print_r($visitors_shops_overview_list); 
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('visitors_overview'); ?></h3>
					</div>
					<div class="panel-body">
						<?php
						$this->db->select('vendor_id,name');
						$vendor_list=$this->db->get('vendor')->result_array();
						print_r($vendor_list);
						$this->db->select('user_id,username');
						$visitors_list=$this->db->get('user')->result_array();
						print_r($visitors_list);
						
						$visitors_breakdown_by_days_list = $this->dashboard_model->overview_visitors_breakdown_by_days('','','','');
						print_r($visitors_breakdown_by_days_list);
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('visitors_breakdown_by_days'); ?></h3>
					</div>
					<div class="panel-body">
						<?php
						
						$this->db->select('vendor_id,name');
						$vendor_list=$this->db->get('vendor')->result_array();
						print_r($vendor_list);
						$this->db->select('user_id,username');
						$visitors_list=$this->db->get('user')->result_array();
						print_r($visitors_list);
						
						$visitors_breakdown_by_days_list = $this->dashboard_model->overview_visitors_breakdown_by_days('','','','');
						print_r($visitors_breakdown_by_days_list); 
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="page-title">
		<h3 class="page-header text-overflow"><?php echo translate('sales'); ?></h3>
	</div>
	<div id="page-content">
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('sales_analytics'); ?></h3>
					</div>
					<div class="panel-body">
						<h4>An Insight into the sales generated by your Shop</h4><br>
						<?php  $sales_analytics_list = $this->dashboard_model->sales_analytics('',''); ?>
						<table style="width: 100%;">
							<tr>
								<td>
                                    <?php echo translate('total_sales'); ?><br>
                                    <?php echo $sales_analytics_list['total_sales']; ?>
                                </td>
								<td>
                                    <?php echo translate('average_order_value'); ?><br>
                                    <?php echo $sales_analytics_list['average_order_value']; ?>
                                </td>
								<td>
                                    <?php echo translate('total_orders'); ?><br>
                                    <?php echo $sales_analytics_list['total_orders']; ?>
                                </td>
							</tr>
							<tr>
								<td>
                                    <?php print_r($sales_analytics_list['total_sales_list']); ?>
                                </td>
								<td>
                                    <?php print_r($sales_analytics_list['average_order_value_list']); ?>
                                </td>
								<td>
                                    <?php print_r($sales_analytics_list['total_orders_list']); ?>
                                </td>
							</tr>
						</table>
						<?php  ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	

	<!-- Vigneshwaran -->
	

	



		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('Top_15_Products_by_Units_Sold'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<?php
						$top_15 = $this->dashboard_model->top_15();
						print_r($top_15);
						?>
						<div id="chartdivsales" style="width: 100%; height: 300px;"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('Sales_by_order_value'); ?>
						</h3>
					</div>
					<?php 
					$Sales_by_order_value = array();
						$Sales_by_order_value = $this->dashboard_model->Sales_by_order_value('2022-05-01','2022-05-30');
						print_r($Sales_by_order_value);
					?>
					<div class="panel-body">
						<div id="chartdiv" style="width: 100%; height: 300px;"></div>
					</div>
				</div>
			</div>

		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('Sales_by_Stores_by_Selected_Dates'); ?>
						</h3>
					</div>
					<?php
					$Sales_by_Stores_by_Selected_Dates = array();
					$Sales_by_Stores_by_Selected_Dates = $this->dashboard_model->Sales_by_Stores_by_Selected_Dates();
					print_r($Sales_by_Stores_by_Selected_Dates);

					?>
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
						<h3 class="panel-title"><?php echo translate('sales_order_type'); ?></h3>
					</div>
					<div class="panel-body">
						<?php
						$sales_by_order_type = $this->dashboard_model->sales_by_order_type('','');
						print_r($sales_by_order_type); 
						?>
					</div>
				</div>
			</div>
		</div>
		

<!-- Priyadharshini -->

	<!----------------------Inventory---start------------------------------------------------------------->
	
	<div id="page-title">
		<h3 class="page-header text-overflow"><?php echo translate('inventory'); ?></h3>
	</div>
	<div id="page-content">
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('inventory'); ?>
						</h3>
					</div>
					<?php 
					/* $this->db->select('vendor_id,name');
						$vendor_list=$this->db->get('vendor')->result_array();
						print_r($vendor_list); */
                        $from1 = $this->input->post('from');
						$to1 = $this->input->post('to');
						
						
					?>
					<div class="panel-body">
						<form method="post">
								
							<div class="col-md-2">
								Start date:<input type="date" name="from" id="from1" class="form-control" value="<?php echo $from1; ?>">
							</div>
							<div class="col-md-2">
								End date:<input type="date" name="to" id="to1" class="form-control" value="<?php echo $to1;?>">
							</div>
							<div  class="col-md-1"> 
						        <button type="submit" class="btn btn-success" id="filter_btn">Filter</button>
                            </div>
							<div  class="col-md-2"> 
						        <button type="button" class="btn btn-info btn-refresh"><i class="fa fa-refresh" aria-hidden="true" onclick="refresh_filter()"></i> Refresh</button>
						    </div>
						</form>
						<?php
						if($from1==""){
						 $this->db->select('DATE_FORMAT(date(FROM_UNIXTIME(sale.sale_datetime)),\'%Y-%m-%d\') AS sale_date,vendor.name,sale.grand_total,sale.product_details');
						$this->db->group_by('sale_date,vendor.name');
						$this->db->order_by('sale_date,vendor.name', 'asc');
						$this->db->join('vendor','sale.store_id=vendor.vendor_id');
						$sales_over_time_list = $this->db->get('sale')->result_array();
						}
						else
						{
							$this->db->select('DATE_FORMAT(date(FROM_UNIXTIME(sale.sale_datetime)),\'%d-%m-%Y\') AS sale_date,vendor.name,sale.grand_total,sale.product_details');
							$this->db->group_by('sale_date,vendor.name');
							$this->db->order_by('sale_date,vendor.name', 'asc');
							if($from1!=''){$this->db->where('date(FROM_UNIXTIME(sale.sale_datetime))>=\''.$from1.'\'');}
							if($to1!=''){$this->db->where('date(FROM_UNIXTIME(sale.sale_datetime))<=\''.$to1.'\'');}
							$this->db->join('vendor','sale.store_id=vendor.vendor_id');
							$sales_over_time_list=$this->db->get('sale')->result_array();
						}
						//print_r($sales_over_time_list);
						?>
						<div class="panel-body" id="demo_s">
							
						<table id="example" class="table table-striped table-bordered" style="width:100%">
								<thead>

									<tr>
										<td>Store</td>
										<td>Delivery Date</td>
										<td>Item</td>
										<td>Quantity</td>
									</tr>
								</thead>

								<tbody id="list">
									<?php
									
									foreach ($sales_over_time_list as $inventory) {
										
										$jsonString =$inventory['product_details'];

										// Decode the JSON string into a PHP associative array
										$data = json_decode($jsonString, true);

										// Iterate through the associative array to access each component
										foreach ($data as $key => $item) {
											$id = $item['id'];
                                            $this->db->select('product_id,title');
											$this->db->where('product_id',$id);
											$sales_over_time_list1 = $this->db->get('product')->result_array();
											//print_r($sales_over_time_list1);
											foreach ($sales_over_time_list1 as $inventory1){
											 ?>
                                   
											
										<tr>
										<td><?php echo $inventory['name']; ?></td>
										<td><?php echo $inventory['sale_date']; ?></td>
										<td><?php echo $inventory1['title']; ?></td> 
										<td><?php echo $inventory['grand_total']; ?></td>
								

										</tr>
										
										<?php }}} ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="page-title">
		<h3 class="page-header text-overflow"><?php echo translate('customer'); ?></h3>
	</div>
	<div id="page-content">
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('customer'); ?></h3>
					</div>
					<div class="panel-body">
						<?php
						 $customers_list = $this->dashboard_model->customers();
						print_r($customers_list); 
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	<script>
		$(document).ready(function()
		{
			$('#example').DataTable(); 
		});  
		
	function refresh_filter(){
          $("#from1").val("");
		$("#to1").val(""); 

	   $('#filter_btn').click();
   }

	</script>
<!----------------------Inventory-End--------------------------------------------------------------->

<script>
     	var chartData1 = [
		<?php
		$vens = $this->db->get('vendor')->result_array();
		foreach ($vens as $ven_us) {
			$where = '(status="success" or status = "admin_pending")';
			$this->db->where($where);
			//$this->db->or_where('status', 'success');
			$result2s = $this->db->get_where('sale', array('store_id' => $ven_us['vendor_id']))->result_array();
			//	echo $this->db->last_query(); exit;
			$stockss = 0;
			foreach ($result2s as $rows) {
				//print_r($row);
				$stockss += $rows['grand_total'];
			}
      echo '{ "country": "' . $ven_us['name'] . '", "visits": ' . $stockss . ', "color": "#458fd2" },';
		?> 
		<?php
		}
		?>
	];
console.log("chartData1",chartData1);
        var chart;
        AmCharts.ready(function() {
            chart = new AmCharts.AmPieChart();
            chart.dataProvider = chartData1;
            chart.titleField = "country";
            chart.valueField = "visits";
            chart.outlineColor = "#FFFFFF";
            chart.outlineAlpha = 0.8;
            chart.outlineThickness = 2;
            chart.startDuration = 1;

            chart.innerRadius = "40%"; 
            chart.labelRadius = 10;  
            var balloon = chart.balloon;
        balloon.adjustBorderColor = true;
        balloon.color = "#000000";
        balloon.cornerRadius = 5;
          
    

            var legend = new AmCharts.AmLegend();
            legend.align = "center";
            legend.markerType = "circle";
            chart.addLegend(legend);
            

            chart.write("chartdiv");
        });

        var settingsIcons = document.getElementById("settings-icon");
        settingsIcons.addEventListener("click", function(){
          document.getElementById("dropdown").classList.toggle("show");
        });

              // Add click events to the dropdown options
          var downloadExcelButton = document.getElementById("download-excel");
          downloadExcelButton.addEventListener("click", function() {
              // Generate and download Excel file
                // ...
                var xls = "Country\tVisits\n";
                for (var i = 0; i < chartData1.length; i++) {
                    xls += chartData1[i].country + "\t" + chartData1[i].visits + "\n";
                }
                var blob = new Blob([xls], { type: "application/vnd.ms-excel" });
                var link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = "chart_data.xls";
                link.click();
          });

          var downloadCsvButton = document.getElementById("download-csv");
          downloadCsvButton.addEventListener("click", function() {
              // Generate and download Excel/CSV file
              var csv = "Country,visits\n";
              for (var i = 0; i < chartData1.length; i++) {
                  csv += chartData1[i].country + "," + chartData1[i].visits + "\n";
              }
              var blob = new Blob([csv], { type: "text/csv" });
              var link = document.createElement("a");
              link.href = window.URL.createObjectURL(blob);
              link.download = "chart_data.csv";
              link.click();
          });

                var expandIcon = document.querySelector("#settings-icon .fa-expand");
                var chartContainer = document.getElementById("chartdiv");
                var isExpanded = false;

                expandIcon.addEventListener("click", function(event) {
                event.stopPropagation(); // Prevent event propagation
                if (isExpanded) {
                    // Contract the chart (reset size)
                    chartContainer.style.height = "300px";
                    isExpanded = false;
                } else {
                    // Expand the chart
                    chartContainer.style.height = "600px"; // Adjust the desired height
                    isExpanded = true;
                }
                chart.invalidateSize(); // Refresh the chart's size

});

var menuHamburger = document.getElementById("menu-icon");
menuHamburger.addEventListener("click", function() {
      event.stopPropagation();
        document.getElementById("sort-dropdown").classList.toggle("show");
    });

    var sortHighLowButton = document.getElementById("sort-high-low");
    sortHighLowButton.addEventListener("click", function() {
      event.stopPropagation();
        chartData1.sort(function(a, b) {
            return b.visits - a.visits;
        });
        chart.dataProvider = chartData1;
        chart.validateData();
    });

    var sortLowHighButton = document.getElementById("sort-low-high");
    sortLowHighButton.addEventListener("click", function() {
      event.stopPropagation();
        chartData1.sort(function(a, b) {
            return a.visits - b.visits;
        });
        chart.dataProvider = chartData1;
        chart.validateData();
    });


    var filterIcon = document.querySelector("#settings-icon .fa-filter");
filterIcon.addEventListener("click", function(event) {
    event.stopPropagation();
    document.getElementById("funnel-dropdown").classList.toggle("show");
});


// Apply date filter
var applyDateFilterButton = document.getElementById("apply-date-filter");
applyDateFilterButton.addEventListener("click", function (event) {
    event.stopPropagation();
    
    // Get the selected filter values
    var dateFrom = document.getElementById("date-from").value;
    var dateTo = document.getElementById("date-to").value;
    var dateType = document.getElementById("date-type").value;

    // // Apply the filter to the chart data
    // var filteredChartData = chartData1.filter(function (data) {
    //     // Apply your filter logic here based on the selected values
    //     // For example, check if the data falls within the selected date range
    //     // and matches the selected date type
        
    //     // Return true if the data matches the filter, otherwise false
    // });

    // // Update the chart data and redraw the chart
    // chart.dataProvider = filteredChartData;
    // chart.validateData();
    
});

      
    </script>

<style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }

        #chartdiv {
            width: 100%;
            height: 400px;
        }
        #settings-icon {
    display: flex;
    align-items: center;
    margin-left: 73%;
    margin-top: 19px;
}

#settings-icon i {
    font-size: 24px; /* Adjust the size as needed */
    color: #4285F4; /* Change the color to your desired color */
    cursor: pointer;
    margin-right: 10px; /* Add some space between icons */
}
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.1);
        padding: 10px;
        border-radius: 4px;
        right: 361px;
        top: 116px;
        z-index: 1;
    }
    

    .dropdown-content a {
        display: block;
        padding: 8px;
        text-decoration: none;
        color: #333;
        transition: background-color 0.3s;
    }

    .dropdown-content a:hover {
        background-color: #f5f5f5;
    }

    .show {
        display: block;
    }
    </style>
<!-- not necessary -->
	