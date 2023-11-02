<link rel="stylesheet" href="<?php echo base_url(); ?>template/back//amcharts/style.css" type="text/css">
<script src="<?php echo base_url(); ?>template/back/amcharts/amcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/serial.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/plugins/morris-js/morris.min.js"></script>
<script src="<?php echo base_url(); ?>template/back/plugins/gauge-js/gauge.min.js"></script>
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/pie.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script> -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css">
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css" type="text/css"> -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap.min.css" type="text/css">

<!-------- Chart Js Files ----------------->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<!-------- Chart Js Files ----------------->

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<div id="content-container">
	<div class="content-wrapper-before hidden"></div>    
	<div id="page-title"><br />
		<h3 class="page-header text-overflow"><?php echo translate('dashboard'); ?></h3>
	</div>
	<!-- <div id="page-title">
		<h3 class="page-header text-overflow"><?php //echo translate('overview'); ?></h3>
	</div> -->
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
                        <div class="col-md-3">
                            Store:
                            <select id="Sales_by_shop_storeid" class="form-control">
                                <option value="">Choose Store</option>
                                <?php
                                $this->db->select('vendor_id,name');
                                $vendor_list=$this->db->get('vendor')->result_array();
                                foreach($vendor_list as $vendor_list1){
                                    echo '<option value="'.$vendor_list1['vendor_id'].'">'.$vendor_list1['name'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Start Date:<input type="date" id="Sales_by_shop_stdt" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            End Date:<input type="date" id="Sales_by_shop_endt" class="form-control" />
                        </div>
                        <div  class="col-md-2"> 
                            <br><button type="submit" class="btn btn-success" id="Sales_by_shop_filterbtn" onclick="Sales_by_shop_filter(Sales_by_shop_storeid.value,Sales_by_shop_stdt.value,Sales_by_shop_endt.value)">Filter</button>
                            &nbsp;<button type="button" class="btn btn-info btn-refresh" onclick="Sales_by_shop_refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        </div>
					</div>
                    <figure class="highcharts-figure">
                        <div id="Sales_by_shop_chart"></div>
                    </figure>
<script>
function Sales_by_shop_filter(storeid,st_dt,en_dt)
{
    if(((st_dt!="") && (en_dt!=""))?(st_dt <= en_dt):false)
    {
        $("#Sales_by_shop_chart").html('');
        $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/Sales_Breakdown_by_Days_filter', {
            type: 'POST',
            data: { storeid:storeid,st_dt:st_dt,en_dt:en_dt },
            success: function (data, status, xhr) {
                var data1=JSON.parse(data);
                var header1=data1['header'];
                var vendor_list1=data1['vendor_list'];
                var list1=data1['list'];
                var chartData=[];
                for(let i1=0;i1<vendor_list1.length;i1++){
                    var id1=vendor_list1[i1]['vendor_id'];
                    var valu=0;
                    for(let i2=1;i2<header1.length;i2++){
                        if((list1.hasOwnProperty(header1[i2]))?(list1[header1[i2]].hasOwnProperty(id1)):false)
                        {if(list1[header1[i2]][id1]!=null){valu+=parseFloat(list1[header1[i2]][id1]);}}
                    }
                    chartData.push({ name: vendor_list1[i1]['name'], y: valu });
                }
                set_piechart_report('Sales_by_shop_chart','<?php echo translate('Sales_by_shop'); ?>',chartData);
            }
        });
    }
    else
    {
        if(st_dt==""){alert("Select Start date");$("#Sales_by_shop_stdt").focus();return false;}
        if(en_dt==""){alert("Select End date");$("#Sales_by_shop_endt").focus();return false;}
        if(st_dt > en_dt){alert("Select End date greater than Start date");$("#Sales_by_shop_endt").focus();return false;}
    }
}
function Sales_by_shop_refresh()
{
    $("#Sales_by_shop_storeid").val("");
    $("#Sales_by_shop_stdt").val("");
    $("#Sales_by_shop_endt").val("");
    $("#Sales_by_shop_chart").html('');
}
$(document).ready(function(){
    Sales_by_shop_filter('','2021-01-01','<?php echo date('Y-m-d'); ?>');
});
</script>
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
                        <div class="col-md-3">
                            Store:
                            <select id="sales_over_time_storeid" class="form-control">
                                <option value="">Choose Store</option>
                                <?php
                                $this->db->select('vendor_id,name');
                                $vendor_list=$this->db->get('vendor')->result_array();
                                foreach($vendor_list as $vendor_list1){
                                    echo '<option value="'.$vendor_list1['vendor_id'].'">'.$vendor_list1['name'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Start Date:<input type="date" id="sales_over_time_stdt" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            End Date:<input type="date" id="sales_over_time_endt" class="form-control" />
                        </div>
                        <div  class="col-md-2"> 
                            <br><button type="submit" class="btn btn-success" id="sales_over_time_filterbtn" onclick="sales_over_time_filter(sales_over_time_storeid.value,sales_over_time_stdt.value,sales_over_time_endt.value)">Filter</button>
                            &nbsp;<button type="button" class="btn btn-info btn-refresh" onclick="sales_over_time_refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        </div>
					</div>
                    <figure class="highcharts-figure">
                        <div id="sales_over_time_chart" style="height: 400px;"></div>
                    </figure>
<script>
function sales_over_time_filter(storeid,st_dt,en_dt)
{
    if(((st_dt!="") && (en_dt!=""))?(st_dt <= en_dt):false)
    {
        $("#sales_over_time_chart").html('');
        $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/Sales_Breakdown_by_Days_filter', {
            type: 'POST',
            data: { storeid:storeid,st_dt:st_dt,en_dt:en_dt },
            success: function (data, status, xhr) {
                var data1=JSON.parse(data);
                var header1=data1['header'];
                var categories=[];
                for(let i1=1;i1<header1.length;i1++){categories.push(header1[i1]);}
                var vendor_list1=data1['vendor_list'];
                var list1=data1['list'];
                var series=[];
                for(let i1=0;i1<vendor_list1.length;i1++){
                    var id1=vendor_list1[i1]['vendor_id'];
                    var valu=[];
                    for(let i2=1;i2<header1.length;i2++){
                        if((list1.hasOwnProperty(header1[i2]))?(list1[header1[i2]].hasOwnProperty(id1)):false)
                        {
                            if(list1[header1[i2]][id1]!=null){valu.push(parseFloat(list1[header1[i2]][id1]));}
                            else{valu.push(0);}
                        }
                        else{valu.push(0);}
                    }
                    series.push({ name: vendor_list1[i1]['name'], data: valu });
                }
                set_barchart_report('sales_over_time_chart',categories,series);
            }
        });
    }
    else
    {
        if(st_dt==""){alert("Select Start date");$("#sales_over_time_stdt").focus();return false;}
        if(en_dt==""){alert("Select End date");$("#sales_over_time_endt").focus();return false;}
        if(st_dt > en_dt){alert("Select End date greater than Start date");$("#sales_over_time_endt").focus();return false;}
    }
}
function sales_over_time_refresh()
{
    $("#sales_over_time_storeid").val("");
    $("#sales_over_time_stdt").val("");
    $("#sales_over_time_endt").val("");
    $("#sales_over_time_chart").html('');
}
$(document).ready(function(){
    sales_over_time_filter('','2021-01-01','<?php echo date('Y-m-d'); ?>');
});
</script>
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
                        <div class="col-md-3">
                            Store:
                            <select id="Sales_Breakdown_by_Days_storeid" class="form-control">
                                <option value="">Choose Store</option>
                                <?php
                                $this->db->select('vendor_id,name');
                                $vendor_list=$this->db->get('vendor')->result_array();
                                foreach($vendor_list as $vendor_list1){
                                    echo '<option value="'.$vendor_list1['vendor_id'].'">'.$vendor_list1['name'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Start Date:<input type="date" id="Sales_Breakdown_by_Days_stdt" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            End Date:<input type="date" id="Sales_Breakdown_by_Days_endt" class="form-control" />
                        </div>
                        <div  class="col-md-2"> 
                            <br><button type="submit" class="btn btn-success" id="Sales_Breakdown_by_Days_filterbtn" onclick="Sales_Breakdown_by_Days_filter(Sales_Breakdown_by_Days_storeid.value,Sales_Breakdown_by_Days_stdt.value,Sales_Breakdown_by_Days_endt.value)">Filter</button>
                            &nbsp;<button type="button" class="btn btn-info btn-refresh" onclick="Sales_Breakdown_by_Days_refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        </div>
					</div>
					<div class="panel-body">
                        <div class="table-responsive">
                            <table id="Sales_Breakdown_by_Days_table" class="table table-striped table-bordered" style="width:100%">
                            </table>
                        </div>
<script>
var Sales_Breakdown_by_Days_table=null;
function Sales_Breakdown_by_Days_filter(storeid,st_dt,en_dt)
{
    if(((st_dt!="") && (en_dt!=""))?(st_dt <= en_dt):false)
    {
        $("#Sales_Breakdown_by_Days_table").html('');
        $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/Sales_Breakdown_by_Days_filter', {
            type: 'POST',
            data: { storeid:storeid,st_dt:st_dt,en_dt:en_dt },
            success: function (data, status, xhr) {
                console.log('data1',data);
                var data1=JSON.parse(data);
                var header1=data1['header'];
                var vendor_list1=data1['vendor_list'];
                var list1=data1['list'];
                var header="",list="";
                for(let i1=0;i1<header1.length;i1++){
                    header+=("<th><div style='width:100px;'>"+header1[i1]+"</div></th>");
                }
                for(let i1=0;i1<vendor_list1.length;i1++){
                    var id1=vendor_list1[i1]['vendor_id'];
                    var list2="";
                    for(let i2=1;i2<header1.length;i2++){
                        if((list1.hasOwnProperty(header1[i2]))?(list1[header1[i2]].hasOwnProperty(id1)):false)
                        {
                            if(list1[header1[i2]][id1]!=null)
                            {list2+="<td>&nbsp;"+list1[header1[i2]][id1]+"</td>";}
                            else{list2+="<td>&nbsp;</td>";}
                        }
                        else{list2+="<td>&nbsp;</td>";}
                    }
                    list+="<tr><td>"+vendor_list1[i1]['name']+"</td>"+list2+"</tr>";
                }
                if(Sales_Breakdown_by_Days_table!=null)
                {Sales_Breakdown_by_Days_table.destroy();}
                $("#Sales_Breakdown_by_Days_table").html('<thead><tr>'+header+'</tr></thead><tbody>'+list+'</tbody>');
                Sales_Breakdown_by_Days_table=$('#Sales_Breakdown_by_Days_table').DataTable({
                    lengthChange: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'excel', 'pdf', 'print'
                    ]
                }).draw();
            }
        });
    }
    else
    {
        if(st_dt==""){alert("Select Start date");$("#Sales_Breakdown_by_Days_stdt").focus();return false;}
        if(en_dt==""){alert("Select End date");$("#Sales_Breakdown_by_Days_endt").focus();return false;}
        if(st_dt > en_dt){alert("Select End date greater than Start date");$("#Sales_Breakdown_by_Days_endt").focus();return false;}
    }
}
function Sales_Breakdown_by_Days_refresh()
{
    $("#Sales_Breakdown_by_Days_storeid").val("");
    $("#Sales_Breakdown_by_Days_stdt").val("");
    $("#Sales_Breakdown_by_Days_endt").val("");
    $("#Sales_Breakdown_by_Days_table").html('');
}
$(document).ready(function(){
    Sales_Breakdown_by_Days_filter('','2021-01-01','<?php echo date('Y-m-d'); ?>');
});
</script>
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
                        <div class="col-md-3">
                            Store:
                            <select id="visitors_shops_overview_storeid" class="form-control">
                                <option value="">Choose Store</option>
                                <?php
                                $this->db->select('vendor_id,name');
                                $vendor_list=$this->db->get('vendor')->result_array();
                                foreach($vendor_list as $vendor_list1){
                                    echo '<option value="'.$vendor_list1['vendor_id'].'">'.$vendor_list1['name'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            Customer:
                            <select id="visitors_shops_overview_buyerid" class="form-control">
                                <option value="">Choose Customer</option>
                                <?php
                                $this->db->select('user_id,username');
                                $visitors_list=$this->db->get('user')->result_array();
                                foreach($visitors_list as $visitors_list1){
                                    echo '<option value="'.$visitors_list1['user_id'].'">'.$visitors_list1['username'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Start Date:<input type="date" id="visitors_shops_overview_stdt" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            End Date:<input type="date" id="visitors_shops_overview_endt" class="form-control" />
                        </div>
                        <div  class="col-md-2"> 
                            <br><button type="button" class="btn btn-success" id="visitors_shops_overview_filterbtn" onclick="visitors_shops_overview_filter(visitors_shops_overview_storeid.value,visitors_shops_overview_buyerid.value,visitors_shops_overview_stdt.value,visitors_shops_overview_endt.value)">Filter</button>
                            &nbsp;<button type="button" class="btn btn-info btn-refresh" onclick="visitors_shops_overview_refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        </div>
					</div>
                    <figure class="highcharts-figure">
                        <div id="visitors_shops_overview_chart"></div>
                    </figure>
<script>
function visitors_shops_overview_filter(storeid,buyerid,st_dt,en_dt)
{
    if(((st_dt!="") && (en_dt!=""))?(st_dt <= en_dt):false)
    {
        $("#visitors_shops_overview_chart").html('');
        $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/visitors_shops_overview_filter', {
            type: 'POST',
            data: { storeid:storeid,buyerid:buyerid,st_dt:st_dt,en_dt:en_dt },
            success: function (data, status, xhr) {
                var data1=JSON.parse(data);
                var chartData=[];
                for(let i1=0;i1<data1.length;i1++){
                    var valu=parseFloat(data1[i1]['total_visitors']);
                    chartData.push({ name: data1[i1]['name'], y: valu });
                }
                set_piechart_report('visitors_shops_overview_chart','<?php echo translate('visitors_(shops_overview)'); ?>',chartData);
            }
        });
    }
    else
    {
        if(st_dt==""){alert("Select Start date");$("#visitors_shops_overview_stdt").focus();return false;}
        if(en_dt==""){alert("Select End date");$("#visitors_shops_overview_endt").focus();return false;}
        if(st_dt > en_dt){alert("Select End date greater than Start date");$("#visitors_shops_overview_endt").focus();return false;}
    }
}
function visitors_shops_overview_refresh()
{
    $("#visitors_shops_overview_storeid").val("");
    $("#visitors_shops_overview_stdt").val("");
    $("#visitors_shops_overview_endt").val("");
    $("#visitors_shops_overview_chart").html('');
}
$(document).ready(function(){
    visitors_shops_overview_filter('','','2021-01-01','<?php echo date('Y-m-d'); ?>');
});
</script>
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
                        <div class="col-md-3">
                            Store:
                            <select id="visitors_overview_storeid" class="form-control">
                                <option value="">Choose Store</option>
                                <?php
                                $this->db->select('vendor_id,name');
                                $vendor_list=$this->db->get('vendor')->result_array();
                                foreach($vendor_list as $vendor_list1){
                                    echo '<option value="'.$vendor_list1['vendor_id'].'">'.$vendor_list1['name'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            Customer:
                            <select id="visitors_overview_buyerid" class="form-control">
                                <option value="">Choose Customer</option>
                                <?php
                                $this->db->select('user_id,username');
                                $visitors_list=$this->db->get('user')->result_array();
                                foreach($visitors_list as $visitors_list1){
                                    echo '<option value="'.$visitors_list1['user_id'].'">'.$visitors_list1['username'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Start Date:<input type="date" id="visitors_overview_stdt" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            End Date:<input type="date" id="visitors_overview_endt" class="form-control" />
                        </div>
                        <div  class="col-md-2"> 
                            <br><button type="button" class="btn btn-success" id="visitors_overview_filterbtn" onclick="visitors_overview_filter(visitors_overview_storeid.value,visitors_overview_buyerid.value,visitors_overview_stdt.value,visitors_overview_endt.value)">Filter</button>
                            &nbsp;<button type="button" class="btn btn-info btn-refresh" onclick="visitors_overview_refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        </div>
					</div>
                    <figure class="highcharts-figure">
                        <div id="visitors_overview_chart" style="height: 400px;"></div>
                    </figure>
<script>
function visitors_overview_filter(storeid,buyerid,st_dt,en_dt)
{
    if(((st_dt!="") && (en_dt!=""))?(st_dt <= en_dt):false)
    {
        $("#visitors_overview_chart").html('');
        $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/visitors_breakdown_by_days_filter', {
            type: 'POST',
            data: { storeid:storeid,buyerid:buyerid,st_dt:st_dt,en_dt:en_dt },
            success: function (data, status, xhr) {
                var data1=JSON.parse(data);
                var header1=data1['header'];
                var categories=[];
                for(let i1=1;i1<header1.length;i1++){categories.push(header1[i1]);}
                var vendor_list1=data1['vendor_list'];
                var list1=data1['list'];
                var series=[];
                for(let i1=0;i1<vendor_list1.length;i1++){
                    var id1=vendor_list1[i1]['vendor_id'];
                    var valu=[];
                    for(let i2=1;i2<header1.length;i2++){
                        if((list1.hasOwnProperty(header1[i2]))?(list1[header1[i2]].hasOwnProperty(id1)):false)
                        {
                            if(list1[header1[i2]][id1]!=null){valu.push(parseFloat(list1[header1[i2]][id1]));}
                            else{valu.push(0);}
                        }
                        else{valu.push(0);}
                    }
                    series.push({ name: vendor_list1[i1]['name'], data: valu });
                }
                set_barchart_report('visitors_overview_chart',categories,series);
            }
        });
    }
    else
    {
        if(st_dt==""){alert("Select Start date");$("#visitors_overview_stdt").focus();return false;}
        if(en_dt==""){alert("Select End date");$("#visitors_overview_endt").focus();return false;}
        if(st_dt > en_dt){alert("Select End date greater than Start date");$("#visitors_overview_endt").focus();return false;}
    }
}
function visitors_overview_refresh()
{
    $("#visitors_overview_storeid").val("");
    $("#visitors_overview_buyerid").val("");
    $("#visitors_overview_stdt").val("");
    $("#visitors_overview_endt").val("");
    $("#visitors_overview_chart").html('');
}
$(document).ready(function(){
    visitors_overview_filter('','','2021-01-01','<?php echo date('Y-m-d'); ?>');
});
</script>
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
                        <div class="col-md-3">
                            Store:
                            <select id="visitors_breakdown_by_days_storeid" class="form-control">
                                <option value="">Choose Store</option>
                                <?php
                                $this->db->select('vendor_id,name');
                                $vendor_list=$this->db->get('vendor')->result_array();
                                foreach($vendor_list as $vendor_list1){
                                    echo '<option value="'.$vendor_list1['vendor_id'].'">'.$vendor_list1['name'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            Customer:
                            <select id="visitors_breakdown_by_days_buyerid" class="form-control">
                                <option value="">Choose Customer</option>
                                <?php
                                $this->db->select('user_id,username');
                                $visitors_list=$this->db->get('user')->result_array();
                                foreach($visitors_list as $visitors_list1){
                                    echo '<option value="'.$visitors_list1['user_id'].'">'.$visitors_list1['username'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Start Date:<input type="date" id="visitors_breakdown_by_days_stdt" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            End Date:<input type="date" id="visitors_breakdown_by_days_endt" class="form-control" />
                        </div>
                        <div  class="col-md-2"> 
                            <br><button type="button" class="btn btn-success" id="visitors_breakdown_by_days_filterbtn" onclick="visitors_breakdown_by_days_filter(visitors_breakdown_by_days_storeid.value,visitors_breakdown_by_days_buyerid.value,visitors_breakdown_by_days_stdt.value,visitors_breakdown_by_days_endt.value)">Filter</button>
                            &nbsp;<button type="button" class="btn btn-info btn-refresh" onclick="visitors_breakdown_by_days_refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        </div>
					</div>
					<div class="panel-body">
                        <div class="table-responsive">
                            <table id="visitors_breakdown_by_days_table" class="table table-striped table-bordered" style="width:100%">
                            </table>
                        </div>
<script>
var visitors_breakdown_by_days_table=null;
function visitors_breakdown_by_days_filter(storeid,buyerid,st_dt,en_dt)
{
    if(((st_dt!="") && (en_dt!=""))?(st_dt <= en_dt):false)
    {
        $("#visitors_breakdown_by_days_table").html('');
        $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/visitors_breakdown_by_days_filter', {
            type: 'POST',
            data: { storeid:storeid,buyerid:buyerid,st_dt:st_dt,en_dt:en_dt },
            success: function (data, status, xhr) {
                var data1=JSON.parse(data);
                var header1=data1['header'];
                var vendor_list1=data1['vendor_list'];
                var list1=data1['list'];
                var header="",list="";
                for(let i1=0;i1<header1.length;i1++){
                    header+=("<th><div style='width:100px;'>"+header1[i1]+"</div></th>");
                }
                for(let i1=0;i1<vendor_list1.length;i1++){
                    var id1=vendor_list1[i1]['vendor_id'];
                    var list2="";
                    for(let i2=1;i2<header1.length;i2++){
                        if((list1.hasOwnProperty(header1[i2]))?(list1[header1[i2]].hasOwnProperty(id1)):false)
                        {
                            if(list1[header1[i2]][id1]!=null)
                            {list2+="<td>&nbsp;"+list1[header1[i2]][id1]+"</td>";}
                            else{list2+="<td>&nbsp;</td>";}
                        }
                        else{list2+="<td>&nbsp;</td>";}
                    }
                    list+="<tr><td>"+vendor_list1[i1]['name']+"</td>"+list2+"</tr>";
                }
                if(visitors_breakdown_by_days_table!=null)
                {visitors_breakdown_by_days_table.destroy();}
                $("#visitors_breakdown_by_days_table").html('<thead><tr>'+header+'</tr></thead><tbody>'+list+'</tbody>');
                visitors_breakdown_by_days_table=$('#visitors_breakdown_by_days_table').DataTable({
                    lengthChange: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'excel', 'pdf', 'print'
                    ]
                }).draw();
            }
        });
    }
    else
    {
        if(st_dt==""){alert("Select Start date");$("#visitors_breakdown_by_days_stdt").focus();return false;}
        if(en_dt==""){alert("Select End date");$("#visitors_breakdown_by_days_endt").focus();return false;}
        if(st_dt > en_dt){alert("Select End date greater than Start date");$("#visitors_breakdown_by_days_endt").focus();return false;}
    }
}
function visitors_breakdown_by_days_refresh()
{
    $("#visitors_breakdown_by_days_storeid").val("");
    $("#visitors_breakdown_by_days_buyerid").val("");
    $("#visitors_breakdown_by_days_stdt").val("");
    $("#visitors_breakdown_by_days_endt").val("");
    $("#visitors_breakdown_by_days_table").html('');
}
$(document).ready(function(){
    visitors_breakdown_by_days_filter('','','2021-01-01','<?php echo date('Y-m-d'); ?>');
});
</script>
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
                        <div class="col-md-2">
                            Start Date:<input type="date" id="sales_analytics_stdt" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            End Date:<input type="date" id="sales_analytics_endt" class="form-control" />
                        </div>
                        <div  class="col-md-2"> 
                            <br><button type="button" class="btn btn-success" id="sales_analytics_filterbtn" onclick="sales_analytics_filter(sales_analytics_stdt.value,sales_analytics_endt.value)">Filter</button>
                            &nbsp;<button type="button" class="btn btn-info btn-refresh" onclick="sales_analytics_refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        </div>
					</div>
					<div class="panel-body">
						<h4>An Insight into the sales generated by your Shop</h4><br>
						<div class="col-md-4 col-lg-4">
                            <h4><?php echo translate('total_sales'); ?></h4>
                            <div style="width:100%;text-align:center;">
                                <h3 class="panel-title" id="sales_analytics_valu1">0</h3>
                            </div>
							<figure class="highcharts-figure">
							   <div id="sales_analytics_chart1"></div>
							</figure>
						</div>
                        <div class="col-md-4 col-lg-4">
                            <h4><?php echo translate('average_order_value'); ?></h4>
                            <div style="width:100%;text-align:center;">
                                <h3 class="panel-title" id="sales_analytics_valu2">0</h3>
                            </div>
							<figure class="highcharts-figure">
							   <div id="sales_analytics_chart2"></div>
							</figure>
						</div>
                        <div class="col-md-4 col-lg-4">
                            <h4><?php echo translate('total_orders'); ?></h4>
                            <div style="width:100%;text-align:center;">
                                <h3 class="panel-title" id="sales_analytics_valu3">0</h3>
                            </div>
							<figure class="highcharts-figure">
							   <div id="sales_analytics_chart3"></div>
							</figure>
						</div>
					</div>
<script>
var sales_analytics_table=null;
function sales_analytics_filter(st_dt,en_dt)
{
    if(((st_dt!="") && (en_dt!=""))?(st_dt <= en_dt):false)
    {
        $("#sales_analytics_valu1").html('0');
        $("#sales_analytics_chart1").html('');
        $("#sales_analytics_valu2").html('0');
        $("#sales_analytics_chart2").html('');
        $("#sales_analytics_valu3").html('0');
        $("#sales_analytics_chart3").html('');
        $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/sales_analytics_filter', {
            type: 'POST',
            data: { st_dt:st_dt,en_dt:en_dt },
            success: function (data, status, xhr) {
                var data1=JSON.parse(data);
                $("#sales_analytics_valu1").html(parseFloat(data1['total_sales']).toFixed(2));
                $("#sales_analytics_valu2").html(parseFloat(data1['average_order_value']).toFixed(2));
                $("#sales_analytics_valu3").html(parseFloat(data1['total_orders']).toFixed(2));
                var categories=[],series1=[],series2=[],series3=[];
                var total_sales_list=data1['total_sales_list'];
                for( var key in total_sales_list){
                    categories.push(key);
                    var value = total_sales_list[key];
                    series1.push(parseFloat(value['grand_total1']));
                    series2.push(parseFloat(value['average_order_value']));
                    series3.push(parseFloat(value['total_orders']));
                }
                set_linechart_report('sales_analytics_chart1',categories,series1);
                set_linechart_report('sales_analytics_chart2',categories,series2);
                set_linechart_report('sales_analytics_chart3',categories,series3);
            }
        });
    }
    else
    {
        if(st_dt==""){alert("Select Start date");$("#sales_analytics_stdt").focus();return false;}
        if(en_dt==""){alert("Select End date");$("#sales_analytics_endt").focus();return false;}
        if(st_dt > en_dt){alert("Select End date greater than Start date");$("#sales_analytics_endt").focus();return false;}
    }
}
function sales_analytics_refresh()
{
    $("#sales_analytics_stdt").val("");
    $("#sales_analytics_endt").val("");
    $("#sales_analytics_valu1").html('0');
    $("#sales_analytics_chart1").html('');
    $("#sales_analytics_valu2").html('0');
    $("#sales_analytics_chart2").html('');
    $("#sales_analytics_valu3").html('0');
    $("#sales_analytics_chart3").html('');
}
$(document).ready(function(){
    sales_analytics_filter('2021-01-01','<?php echo date('Y-m-d'); ?>');
});
</script>
				</div>
			</div>
		</div>
	</div>
	<div id="page-content">
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('Top_15_Products_by_Units_Sold'); ?>
						</h3>
					</div>
					<div class="panel-body">
                        <div class="col-md-3">
                            Products:
                            <select id="Top_15_Products_by_Units_Sold_itemid" class="form-control">
                                <option value="">Choose Product</option>
                                <?php
                                $this->db->select('product_id,title');
                                $this->db->order_by('title', 'desc');
                                $product_list=$this->db->get('product')->result_array();
                                foreach($product_list as $product_list1){
                                    //echo '<option value="'.$product_list1['product_id'].'">'.$product_list1['title'].'</option>';
                                    echo '<option value="'.$product_list1['title'].'">'.$product_list1['title'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div  class="col-md-2"> 
                            <br><button type="submit" class="btn btn-success" id="Top_15_Products_by_Units_Sold_filterbtn" onclick="Top_15_Products_by_Units_Sold_filter(Top_15_Products_by_Units_Sold_itemid.value)">Filter</button>
                            &nbsp;<button type="button" class="btn btn-info btn-refresh" onclick="Top_15_Products_by_Units_Sold_refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        </div>
					</div>
					<div class="panel-body">
                        <div class="table-responsive">
                            <table id="Top_15_Products_by_Units_Sold_table" class="table table-striped table-bordered" style="width:100%">
                            </table>
                        </div>
<script>
var Top_15_Products_by_Units_Sold_table=null;
function Top_15_Products_by_Units_Sold_filter(itemid)
{
    $("#Top_15_Products_by_Units_Sold_table").html('');
    $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/Top_15_Products_by_Units_Sold_filter', {
        type: 'POST',
        data: { itemid:itemid },
        success: function (data, status, xhr) {
            var data1=JSON.parse(data);
            var list="";
            for(let i1=0;i1<data1.length;i1++){
                list+="<tr><td>"+data1[i1]['name']+"</td><td>"+data1[i1]['qty']+"</td><td>"+data1[i1]['subtotal']+"</td></tr>";
            }
            if(Top_15_Products_by_Units_Sold_table!=null)
            {Top_15_Products_by_Units_Sold_table.destroy();}
            $("#Top_15_Products_by_Units_Sold_table").html('<thead><tr><th>Item Name</th><th>Total Quantity Ordered</th><th>Total Sales from Items</th></tr></thead><tbody>'+list+'</tbody>');
            Top_15_Products_by_Units_Sold_table=$('#Top_15_Products_by_Units_Sold_table').DataTable({
                lengthChange: true,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            }).draw();
        }
    });
}
function Top_15_Products_by_Units_Sold_refresh()
{
    $("#Top_15_Products_by_Units_Sold_itemid").val("");
    $("#Top_15_Products_by_Units_Sold_table").html('');
}
$(document).ready(function(){
    Top_15_Products_by_Units_Sold_filter('');
});
</script>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="page-content">
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('Sales_by_order_value'); ?>
						</h3>
					</div>
					<?php 
					/* $Sales_by_order_value = array();
						$Sales_by_order_value = $this->dashboard_model->Sales_by_order_value('2022-05-01','2022-05-30');
						print_r($Sales_by_order_value); */
					?>
					<div class="panel-body">
                        <div class="col-md-2">
                            Start Date:<input type="date"  id="Sales_by_value_stdt" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            End Date:<input type="date" id="Sales_by_value_endt" class="form-control" />
                        </div>
                        <div  class="col-md-2"> <br><button type="submit" class="btn btn-success" id="Sales_by_shop_filterbtn" onclick="Sales_by_order_value_filter(Sales_by_value_stdt.value,Sales_by_value_endt.value)">Filter</button>
                            &nbsp;<button type="button" class="btn btn-info btn-refresh" onclick="Sales_by_value_refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        </div>
                    </div>
                    <?php
                    /* $Sales_by_order_value_filter=$this->dashboard_model->Sales_by_order_value_filter('','');
                    echo json_encode($Sales_by_order_value_filter); */
                    ?>
						<figure class="highcharts-figure">
                           <div id="Sales_by_order_value_chart"></div>
						</figure>					
					
					
				</div>
 <script>
 function Sales_by_order_value_filter(st_dt,en_dt)
{
    if(((st_dt!="") && (en_dt!=""))?(st_dt <= en_dt):false)
    {
        $("#Sales_by_order_value_chart").html('');
        $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/Sales_by_order_value_filter', {
            type: 'POST',
            data: { st_dt:st_dt,en_dt:en_dt },
            success: function (data, status, xhr) {
                console.log(data);
                var data1=JSON.parse(data);
                //var explodedArray = Object.entries(data1);
                var series=[];
                for(var key in data1){
                    var val1=parseFloat(data1[key]);
                    series.push({name: key,y: val1,drilldown: key});
                }
                set_barchart_report1('Sales_by_order_value_chart',series);
            }
        });
    }
    else
    {
        if(st_dt==""){alert("Select Start date");$("#Sales_by_value_stdt").focus();return false;}
        if(en_dt==""){alert("Select End date");$("#Sales_by_value_endt").focus();return false;}
        if(st_dt > en_dt){alert("Select End date greater than Start date");$("#sales_over_time_endt").focus();return false;}
    }
}
function Sales_by_value_refresh()
{
    
    $("#Sales_by_value_stdt").val("");
    $("#Sales_by_value_endt").val("");
    $("#Sales_by_order_value_chart").html('');
}
$(document).ready(function(){
    Sales_by_order_value_filter('2021-01-01','<?php echo date('Y-m-d'); ?>');
});
</script>
			</div>
        </div>
	</div>

	<div id="page-content">
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('Sales_by_Stores_by_Selected_Dates'); ?>
						</h3>
					</div>
					<div class="panel-body">
                        <div class="col-md-3">
                            Store:
                            <select id="Sales_by_Stores_by_Selected_Dates_storeid" class="form-control">
                                <option value="">Choose Store</option>
                                <?php
                                $this->db->select('vendor_id,name');
                                $vendor_list=$this->db->get('vendor')->result_array();
                                foreach($vendor_list as $vendor_list1){
                                    //echo '<option value="'.$vendor_list1['vendor_id'].'">'.$vendor_list1['name'].'</option>';
                                    echo '<option value="'.$vendor_list1['name'].'">'.$vendor_list1['name'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div  class="col-md-2"> 
                            <br><button type="submit" class="btn btn-success" id="Sales_by_Stores_by_Selected_Dates_filterbtn" onclick="Sales_by_Stores_by_Selected_Dates_filter(Sales_by_Stores_by_Selected_Dates_storeid.value)">Filter</button>
                            &nbsp;<button type="button" class="btn btn-info btn-refresh" onclick="Sales_by_Stores_by_Selected_Dates_refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        </div>
					</div>
					<div class="panel-body">
                        <div class="table-responsive">
                            <table id="Sales_by_Stores_by_Selected_Dates_table" class="table table-striped table-bordered" style="width:100%">
                            </table>
                        </div>
<script>
var Sales_by_Stores_by_Selected_Dates_table=null;
function Sales_by_Stores_by_Selected_Dates_filter(storeid)
{
    $("#Sales_by_Stores_by_Selected_Dates_table").html('');
    $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/Sales_by_Stores_by_Selected_Dates_filter', {
        type: 'POST',
        data: { storeid:storeid },
        success: function (data, status, xhr) {
            var data1=JSON.parse(data);
            var header="<tr><th>S.no</th><th>Store Name</th><th>Number of Orders</th><th>Total Sales</th></tr>";
            var list="";
            for(let i1=0;i1<data1.length;i1++){
                list+="<tr><td>"+(i1+1)+"</td><td>"+data1[i1]['name']+"</td><td>"+data1[i1]['number_of_orders']+"</td><td>"+data1[i1]['total_amount']+"</td></tr>";
            }
            if(Sales_by_Stores_by_Selected_Dates_table!=null)
            {Sales_by_Stores_by_Selected_Dates_table.destroy();}
            $("#Sales_by_Stores_by_Selected_Dates_table").html('<thead>'+header+'</thead><tbody>'+list+'</tbody>');
            Sales_by_Stores_by_Selected_Dates_table=$('#Sales_by_Stores_by_Selected_Dates_table').DataTable({
                lengthChange: true,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ],
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var select = $('<select><option value=""></option></select>')
                            .appendTo($(column.header()))
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });
                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                        $(column.footer()).empty();
                    });
                }
            }).draw();
        }
    });
}
function Sales_by_Stores_by_Selected_Dates_refresh()
{
    $("#Sales_by_Stores_by_Selected_Dates_storeid").val("");
    $("#Sales_by_Stores_by_Selected_Dates_table").html('');
}
$(document).ready(function(){
    Sales_by_Stores_by_Selected_Dates_filter('');
});
</script>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="page-content">
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="panel panel-bordered">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo translate('sales_by_order_type'); ?></h3>
					</div>
					<div class="panel-body">
                        <div class="col-md-3">
                            Store:
                            <select id="sales_by_order_type_order_type" class="form-control">
                                <option value="">Choose Order Type</option>
                                <?php
                                $this->db->distinct()->select('order_type')->where("order_type!=''");
                                $order_type_list=$this->db->get('sale')->result_array();
                                foreach($order_type_list as $order_type_list1){
                                    echo '<option value="'.$order_type_list1['order_type'].'">'.$order_type_list1['order_type'].'</option>';
                                } ?>
                                <option value="others">others</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Start Date:<input type="date" id="sales_by_order_type_stdt" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            End Date:<input type="date" id="sales_by_order_type_endt" class="form-control" />
                        </div>
                        <div  class="col-md-2"> 
                            <br><button type="button" class="btn btn-success" id="sales_by_order_type_filterbtn" onclick="sales_by_order_type_filter(sales_by_order_type_order_type.value,sales_by_order_type_stdt.value,sales_by_order_type_endt.value)">Filter</button>
                            &nbsp;<button type="button" class="btn btn-info btn-refresh" onclick="sales_by_order_type_refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        </div>
					</div>
					<div class="panel-body">
								
						<figure class="highcharts-figure">
							<div id="sales_by_order_type_chart"></div>
						</figure>
<script>
function sales_by_order_type_filter(order_type,st_dt,en_dt)
{
    if(((st_dt!="") && (en_dt!=""))?(st_dt <= en_dt):false)
    {
        $("#sales_by_order_type_chart").html('');
        $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/sales_by_order_type_filter', {
            type: 'POST',
            data: { order_type:order_type,st_dt:st_dt,en_dt:en_dt },
            success: function (data, status, xhr) {
                var categories=[],series_pickup=[],series_delivery=[],series_shopping=[],series_others=[];
                var data1=JSON.parse(data);
                for( var key in data1){
                    categories.push(key);
                    var value = data1[key];
                    series_pickup.push(parseFloat(value['total_pickup']));
                    series_delivery.push(parseFloat(value['total_delivery']));
                    series_shopping.push(parseFloat(value['total_shopping']));
                    series_others.push(parseFloat(value['total_others']));
                }
                var series=[
                    {name: 'pickup',data: series_pickup},
                    {name: 'delivery',data: series_delivery},
                    {name: 'shopping',data: series_shopping},
                    {name: 'others',data: series_others}
                ];
                set_columnchart_report('sales_by_order_type_chart',categories,series);
            }
        });
    }
    else
    {
        if(st_dt==""){alert("Select Start date");$("#sales_by_order_type_stdt").focus();return false;}
        if(en_dt==""){alert("Select End date");$("#sales_by_order_type_endt").focus();return false;}
        if(st_dt > en_dt){alert("Select End date greater than Start date");$("#sales_by_order_type_endt").focus();return false;}
    }
}
function sales_by_order_type_refresh()
{
    $("#sales_by_order_type_order_type").val("");
    $("#sales_by_order_type_stdt").val("");
    $("#sales_by_order_type_endt").val("");
    $("#sales_by_order_type_chart").html('');
}
$(document).ready(function(){
    sales_by_order_type_filter('','2021-01-01','<?php echo date('Y-m-d'); ?>');
});
</script>
					</div>
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
					<div class="panel-body">
                        <div class="col-md-3">
                            Store:
                            <select id="inventory_storeid" class="form-control">
                                <option value="">Choose Store</option>
                                <?php
                                $this->db->select('vendor_id,name');
                                $vendor_list=$this->db->get('vendor')->result_array();
                                foreach($vendor_list as $vendor_list1){
                                    echo '<option value="'.$vendor_list1['vendor_id'].'">'.$vendor_list1['name'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            Products:
                            <select id="inventory_itemid" class="form-control">
                                <option value="">Choose Product</option>
                                <?php
                                $this->db->select('product_id,title');
                                $this->db->order_by('title', 'desc');
                                $product_list=$this->db->get('product')->result_array();
                                foreach($product_list as $product_list1){
                                    //echo '<option value="'.$product_list1['product_id'].'">'.$product_list1['title'].'</option>';
                                    echo '<option value="'.$product_list1['title'].'">'.$product_list1['title'].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Start Date:<input type="date" id="inventory_stdt" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            End Date:<input type="date" id="inventory_endt" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            <br><button type="button" class="btn btn-success" id="inventory_filterbtn" onclick="inventory_filter(inventory_storeid.value,inventory_itemid.value,inventory_stdt.value,inventory_endt.value)">Filter</button>
                            &nbsp;<button type="button" class="btn btn-info btn-refresh" onclick="inventory_refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        </div>
					</div>
					<div class="panel-body">
                        <div class="table-responsive">
                            <table id="inventory_table" class="table table-striped table-bordered" style="width:100%">
                            </table>
                        </div>
<script>
var inventory_table=null;
function inventory_filter(storeid,itemid,st_dt,en_dt)
{
    if(((st_dt!="") && (en_dt!=""))?(st_dt <= en_dt):false)
    {
        $("#inventory_table").html('');
        $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/inventory_filter', {
            type: 'POST',
            data: { storeid:storeid,itemid:itemid,st_dt:st_dt,en_dt:en_dt },
            success: function (data, status, xhr) {
                var data1=JSON.parse(data);
                var header="<tr><th>Store Name</th><th>Delivery Date</th><th>Item</th><th>Quantity</th></tr>";
                var list="";
                for(let i1=0;i1<data1.length;i1++){
                    list+="<tr><td>"+data1[i1]['name']+"</td><td>"+data1[i1]['sale_date']+"</td><td>"+data1[i1]['title']+"</td><td>"+data1[i1]['grand_total']+"</td></tr>";
                }
                if(inventory_table!=null)
                {inventory_table.destroy();}
                $("#inventory_table").html('<thead>'+header+'</thead><tbody>'+list+'</tbody>');
                inventory_table=$('#inventory_table').DataTable({
                    lengthChange: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'excel', 'pdf', 'print'
                    ]
                }).draw();
            }
        });
    }
    else
    {
        if(st_dt==""){alert("Select Start date");$("#inventory_stdt").focus();return false;}
        if(en_dt==""){alert("Select End date");$("#inventory_endt").focus();return false;}
        if(st_dt > en_dt){alert("Select End date greater than Start date");$("#inventory_endt").focus();return false;}
    }
}
function inventory_refresh()
{
    $("#inventory_storeid").val("");
    $("#inventory_itemid").val("");
    $("#inventory_stdt").val("");
    $("#inventory_endt").val("");
    $("#inventory_table").html('');
}
$(document).ready(function(){
    inventory_filter('','','2021-01-01','<?php echo date('Y-m-d'); ?>');
});
</script>
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
                        <div class="table-responsive">
                            <table id="customer_table" class="table table-striped table-bordered" style="width:100%">
                            </table>
                        </div>
<script>
var customer_table=null;
function customer_filter()
{
    $("#customer_table").html('');
    $.ajax('<?php echo base_url(); ?>index.php/admin/dashboard_func/customer_filter', {
        type: 'POST',
        success: function (data, status, xhr) {
            var data1=JSON.parse(data);
            var header="<tr><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th><th>Number of Orders</th><th>Total Spent</th><th>Day Since Last Purchase</th><th>Subscribed</th></tr>";
            var list="";
            for(let i1=0;i1<data1.length;i1++){
                list+="<tr><td>"+data1[i1]['first_name']+"</td><td>"+data1[i1]['last_name']+"</td><td>"+data1[i1]['email']+"</td><td>"+data1[i1]['phone']+"</td><td>"+data1[i1]['no_of_orders']+"</td><td>"+data1[i1]['total_spent']+"</td><td>"+data1[i1]['days_since_last_purchase']+"</td><td>"+data1[i1]['subscribed']+"</td></tr>";
            }
            if(customer_table!=null)
            {customer_table.destroy();}
            $("#customer_table").html('<thead>'+header+'</thead><tbody>'+list+'</tbody>');
            customer_table=$('#customer_table').DataTable({
                lengthChange: true,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ],
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var select = $('<select><option value=""></option></select>')
                            .appendTo($(column.header()))
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });
                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                        $(column.footer()).empty();
                    });
                }
            }).draw();
        }
    });
}
$(document).ready(function(){
    customer_filter();
});
</script>
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

<!---- Sales Over Time ----->
<style>
.highcharts-credits {
    display:none;
}
#contain {
    height: 400px;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

</style>

<script>
function set_barchart_report(compName,categories,series)
{
    Highcharts.chart(compName, {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Monthly Average Rainfall'
        },
        subtitle: {
            text: 'Source: WorldClimate.com'
        },
        xAxis: {
            categories: categories,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {text: 'Rainfall (mm)'}
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: series
    });
}
</script>
<!---- Sales Over Time ----->

<!---- Sales By Shop ----->
<script>
// Data retrieved from https://netmarketshare.com/
// Radialize the colors
Highcharts.setOptions({
    colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: {
                cx: 0.5,
                cy: 0.3,
                r: 0.7
            },
            stops: [
                [0, color],
                [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
            ]
        };
    })
});

function set_piechart_report(compName,title,data)
{
    Highcharts.chart(compName, {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: title,
            align: 'left'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {valueSuffix: '%'}
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    connectorColor: 'silver'
                }
            }
        },
        series: [{
            name: 'Share',
            data: data
        }]
    });
}
</script>
<!---- Sales By Shop ----->

<!---- Sales By Shop ----->

<!---- Visitors  ----->
<script>
// Data retrieved from https://olympics.com/en/olympic-games/beijing-2022/medals
/* Highcharts.chart('containervis', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45
        }
    },
    title: {
        text: 'Beijing 2022 gold medals by country',
        align: 'left'
    },
    subtitle: {
        text: '3D donut in Highcharts',
        align: 'left'
    },
    plotOptions: {
        pie: {
            innerSize: 100,
            depth: 45
        }
    },
    series: [{
        name: 'Medals',
        data: [
            ['Norway', 16],
            ['Germany', 12],
            ['USA', 8],
            ['Sweden', 8],
            ['Netherlands', 8],
            ['ROC', 6],
            ['Austria', 7],
            ['Canada', 4],
            ['Japan', 3]

        ]
    }]
}); */
</script>
<!---- Visitors  ----->
<!---- Sales Analysis sa1  ----->
<script>
// Data retrieved from https://www.vikjavev.no/ver/#2022-06-13,2022-06-14
function set_linechart_report(compName,categories,series)
{
    Highcharts.chart(compName, {
        chart: {
            zoomType: 'x'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        accessibility: {
            screenReaderSection: {
                beforeChartFormat: '<{headingTagName}>{chartTitle}</{headingTagName}><div>{chartSubtitle}</div><div>{chartLongdesc}</div><div>{xAxisDescription}</div><div>{yAxisDescription}</div>'
            }
        },
        tooltip: {
            valueDecimals: 2
        },
        xAxis: {
            categories: categories
        },
        plotOptions: {
            series: {
                marker: {
                    enabled: false
                }
            }
        },
        series: [{
            data: series,
            lineWidth: 2,
        }]
    });
}
</script>
<script>
function set_columnchart_report(compName,categories,series)
{
    Highcharts.chart(compName, {
        chart: {
            type: 'column'
        },
        title: {
            text: '',
            align: 'left'
        },
        xAxis: {
            categories: categories
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Count trophies'
            },
            stackLabels: {
                enabled: true
            }
        },
        legend: {
            align: 'left',
            x: 70,
            verticalAlign: 'top',
            y: 70,
            floating: true,
            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: series
    });
}
</script>
<!-----Sales By Order value --------------->


<!-----Sales By Order Type --------------->
<script>
Highcharts.chart('containersot', {
    chart: {
        type: 'column'
    },
    title: {
        align: 'left',
        text: 'Browser market shares. January, 2022'
    },
    subtitle: {
        align: 'left',
        text: 'Click the columns to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total percent market share'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
    },

    series: [
        {
            name: 'Browsers',
            colorByPoint: true,
            data: [
                {
                    name: 'Chrome',
                    y: 63.06,
                    drilldown: 'Chrome'
                },
                {
                    name: 'Safari',
                    y: 19.84,
                    drilldown: 'Safari'
                },
                {
                    name: 'Firefox',
                    y: 4.18,
                    drilldown: 'Firefox'
                },
                {
                    name: 'Edge',
                    y: 4.12,
                    drilldown: 'Edge'
                },
                {
                    name: 'Opera',
                    y: 2.33,
                    drilldown: 'Opera'
                },
                {
                    name: 'Internet Explorer',
                    y: 0.45,
                    drilldown: 'Internet Explorer'
                },
                {
                    name: 'Other',
                    y: 1.582,
                    drilldown: null
                }
            ]
        }
    ],
    drilldown: {
        breadcrumbs: {
            position: {
                align: 'right'
            }
        },
        series: [
            {
                name: 'Chrome',
                id: 'Chrome',
                data: [
                    [
                        'v65.0',
                        0.1
                    ],
                    [
                        'v64.0',
                        1.3
                    ],
                    [
                        'v63.0',
                        53.02
                    ],
                    [
                        'v62.0',
                        1.4
                    ],
                    [
                        'v61.0',
                        0.88
                    ],
                    [
                        'v60.0',
                        0.56
                    ],
                    [
                        'v59.0',
                        0.45
                    ],
                    [
                        'v58.0',
                        0.49
                    ],
                    [
                        'v57.0',
                        0.32
                    ],
                    [
                        'v56.0',
                        0.29
                    ],
                    [
                        'v55.0',
                        0.79
                    ],
                    [
                        'v54.0',
                        0.18
                    ],
                    [
                        'v51.0',
                        0.13
                    ],
                    [
                        'v49.0',
                        2.16
                    ],
                    [
                        'v48.0',
                        0.13
                    ],
                    [
                        'v47.0',
                        0.11
                    ],
                    [
                        'v43.0',
                        0.17
                    ],
                    [
                        'v29.0',
                        0.26
                    ]
                ]
            },
            {
                name: 'Firefox',
                id: 'Firefox',
                data: [
                    [
                        'v58.0',
                        1.02
                    ],
                    [
                        'v57.0',
                        7.36
                    ],
                    [
                        'v56.0',
                        0.35
                    ],
                    [
                        'v55.0',
                        0.11
                    ],
                    [
                        'v54.0',
                        0.1
                    ],
                    [
                        'v52.0',
                        0.95
                    ],
                    [
                        'v51.0',
                        0.15
                    ],
                    [
                        'v50.0',
                        0.1
                    ],
                    [
                        'v48.0',
                        0.31
                    ],
                    [
                        'v47.0',
                        0.12
                    ]
                ]
            },
            {
                name: 'Internet Explorer',
                id: 'Internet Explorer',
                data: [
                    [
                        'v11.0',
                        6.2
                    ],
                    [
                        'v10.0',
                        0.29
                    ],
                    [
                        'v9.0',
                        0.27
                    ],
                    [
                        'v8.0',
                        0.47
                    ]
                ]
            },
            {
                name: 'Safari',
                id: 'Safari',
                data: [
                    [
                        'v11.0',
                        3.39
                    ],
                    [
                        'v10.1',
                        0.96
                    ],
                    [
                        'v10.0',
                        0.36
                    ],
                    [
                        'v9.1',
                        0.54
                    ],
                    [
                        'v9.0',
                        0.13
                    ],
                    [
                        'v5.1',
                        0.2
                    ]
                ]
            },
            {
                name: 'Edge',
                id: 'Edge',
                data: [
                    [
                        'v16',
                        2.6
                    ],
                    [
                        'v15',
                        0.92
                    ],
                    [
                        'v14',
                        0.4
                    ],
                    [
                        'v13',
                        0.1
                    ]
                ]
            },
            {
                name: 'Opera',
                id: 'Opera',
                data: [
                    [
                        'v50.0',
                        0.96
                    ],
                    [
                        'v49.0',
                        0.82
                    ],
                    [
                        'v12.1',
                        0.14
                    ]
                ]
            }
        ]
    }
});

</script>
<script>
function set_barchart_report1(compName,series)
{
Highcharts.chart(compName, {
    chart: {
        type: 'column'
    },
    title: {
        align: 'left',
        text: "Sales By Order Value"
    },
    subtitle: {
        align: 'left',
        text: 'Source: WorldClimate.com'
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total percent market share'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
    },
    series:[
        {
            name: 'Browsers',
            colorByPoint: true,
            data: series,
        }
    ],
    /* series: [
        {
            name: 'Browsers',
            colorByPoint: true,
            data: [
                {
                    name: 'Chrome',
                    y: 63.06,
                    drilldown: 'Chrome'
                },
                {
                    name: 'Safari',
                    y: 19.84,
                    drilldown: 'Safari'
                },
                {
                    name: 'Firefox',
                    y: 4.18,
                    drilldown: 'Firefox'
                },
                {
                    name: 'Edge',
                    y: 4.12,
                    drilldown: 'Edge'
                },
                {
                    name: 'Opera',
                    y: 2.33,
                    drilldown: 'Opera'
                },
                {
                    name: 'Internet Explorer',
                    y: 0.45,
                    drilldown: 'Internet Explorer'
                },
                {
                    name: 'Other',
                    y: 1.582,
                    drilldown: null
                }
            ]
        }
    ], */
    drilldown: {
        breadcrumbs: {
            position: {
                align: 'right'
            }
        },
        series: [
            {
                name: 'Chrome',
                id: 'Chrome',
                data: [
                    [
                        'v65.0',
                        0.1
                    ],
                    [
                        'v64.0',
                        1.3
                    ],
                    [
                        'v63.0',
                        53.02
                    ],
                    [
                        'v62.0',
                        1.4
                    ],
                    [
                        'v61.0',
                        0.88
                    ],
                    [
                        'v60.0',
                        0.56
                    ],
                    [
                        'v59.0',
                        0.45
                    ],
                    [
                        'v58.0',
                        0.49
                    ],
                    [
                        'v57.0',
                        0.32
                    ],
                    [
                        'v56.0',
                        0.29
                    ],
                    [
                        'v55.0',
                        0.79
                    ],
                    [
                        'v54.0',
                        0.18
                    ],
                    [
                        'v51.0',
                        0.13
                    ],
                    [
                        'v49.0',
                        2.16
                    ],
                    [
                        'v48.0',
                        0.13
                    ],
                    [
                        'v47.0',
                        0.11
                    ],
                    [
                        'v43.0',
                        0.17
                    ],
                    [
                        'v29.0',
                        0.26
                    ]
                ]
            },
            {
                name: 'Firefox',
                id: 'Firefox',
                data: [
                    [
                        'v58.0',
                        1.02
                    ],
                    [
                        'v57.0',
                        7.36
                    ],
                    [
                        'v56.0',
                        0.35
                    ],
                    [
                        'v55.0',
                        0.11
                    ],
                    [
                        'v54.0',
                        0.1
                    ],
                    [
                        'v52.0',
                        0.95
                    ],
                    [
                        'v51.0',
                        0.15
                    ],
                    [
                        'v50.0',
                        0.1
                    ],
                    [
                        'v48.0',
                        0.31
                    ],
                    [
                        'v47.0',
                        0.12
                    ]
                ]
            },
            {
                name: 'Internet Explorer',
                id: 'Internet Explorer',
                data: [
                    [
                        'v11.0',
                        6.2
                    ],
                    [
                        'v10.0',
                        0.29
                    ],
                    [
                        'v9.0',
                        0.27
                    ],
                    [
                        'v8.0',
                        0.47
                    ]
                ]
            },
            {
                name: 'Safari',
                id: 'Safari',
                data: [
                    [
                        'v11.0',
                        3.39
                    ],
                    [
                        'v10.1',
                        0.96
                    ],
                    [
                        'v10.0',
                        0.36
                    ],
                    [
                        'v9.1',
                        0.54
                    ],
                    [
                        'v9.0',
                        0.13
                    ],
                    [
                        'v5.1',
                        0.2
                    ]
                ]
            },
            {
                name: 'Edge',
                id: 'Edge',
                data: [
                    [
                        'v16',
                        2.6
                    ],
                    [
                        'v15',
                        0.92
                    ],
                    [
                        'v14',
                        0.4
                    ],
                    [
                        'v13',
                        0.1
                    ]
                ]
            },
            {
                name: 'Opera',
                id: 'Opera',
                data: [
                    [
                        'v50.0',
                        0.96
                    ],
                    [
                        'v49.0',
                        0.82
                    ],
                    [
                        'v12.1',
                        0.14
                    ]
                ]
            }
        ]
    }
});
}
</script>