<?php
$edit_rights=$user_rights_21['edit_rights'];
$delete_rights=$user_rights_21['delete_rights'];
?>
<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
<style>
    .excelss {
	margin-top: 5px;
	padding: 10px;
	background: #ce3b50;
	font-size: 12px;
}
    .col-sm-3.txss {
	    padding-left: 0px;
}
    .cssnew {
        top:43px;
        padding-left: 0px;
    }
</style>
<div class="panel-body" id="demo_s">
    <div class="col-md-12">
        <a class="pull-right btn btn-primary btn-xs excelss" href="<?php echo base_url();?>admin/product_stock/export_excel/<?php echo $store_id ?>/<?php echo $status ?>"><i class="fa fa-file-excel-o"></i>Export Products</a>
    </div>
    <div class="col-md-12 cssnew">
        
        <?php
        echo form_open(base_url() . 'index.php/admin/product_stock', array(
            'class' => 'form-horizontal',
            'method' => 'post'
        ));
        ?>
        <div class="col-sm-3 txss">
            <?php echo $this->crud_model->select_html('vendor', 'vendor', 'name', 'edit', 'demo-chosen-select form-control', $store_id, '', '', '', '', '');  ?>
        </div>
        <div class="col-sm-3 txss">
            <select name="status" class="form-control">
                <option value="0">All</option>
                <option value="1" <?php if ($status == '1') {
                                        echo 'selected="selected"';
                                    } ?>>Pending</option>
                <option value="2" <?php if ($status == '2') {
                                        echo 'selected="selected"';
                                    } ?>>Accept</option>
            </select>
        </div>
        <div class="col-md-3 vendors">
            <input type="submit" class="btn btn-primary" value="Filter" style="background: #044484;">
        </div>
        </form>
    </div>
    
    
        
        <table id="events-table" class="table table-striped"  data-url="<?php echo base_url(); ?>index.php/admin/product/list_data/<?php echo $store_id ?>/<?php echo $status ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"  data-show-refresh="true" data-search="true"  data-show-export="false" >
        <thead>
            <tr>
                <th data-field="image" data-align="center" data-sortable="true">
                    <?php echo translate('image'); ?>
                </th>
                <th data-field="store_name" data-align="left" data-sortable="true" class="width150">
                    <?php echo translate('store_name'); ?>
                </th>
                <th data-field="title" data-align="left" data-sortable="true" class="width150">
                    <?php echo translate('title'); ?>
                </th>
                <th data-field="current_stock" data-sortable="true">
                    <?php echo translate('current_quantity'); ?>
                </th>
                <th data-field="deal" data-sortable="false">
                    <?php echo translate("today's_deal"); ?>
                </th>
                <th data-field="publish" data-sortable="false">
                    <?php echo translate('publish'); ?>
                </th>
                
                <!-- <th data-field="options" data-sortable="false" data-force-hide="true" data-align="center">
                    <?php echo translate('options'); ?>
                </th> -->
            </tr>
        </thead>
    </table>
</div>
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class=""><!--panel panel-bordered-->
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php echo translate('latest_products'); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div class="" id="demo_s">
							<table id="demo-table" class="table table-striped" data-pagination="false" data-show-refresh="false" data-show-toggle="false" data-show-columns="false" data-search="false">

								<thead>

									<tr>

										<td>Product Name</td>
										<td>Created Date</td>
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
										<!--<tr>--->
										<?php } ?>



								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#events-table').bootstrapTable({
            

            /*
            onAll: function (name, args) {
                console.log('Event: onAll, data: ', args);
            }
            onClickRow: function (row) {
                $result.text('Event: onClickRow, data: ' + JSON.stringify(row));
            },
            onDblClickRow: function (row) {
                $result.text('Event: onDblClickRow, data: ' + JSON.stringify(row));
            },
            onSort: function (name, order) {
                $result.text('Event: onSort, data: ' + name + ', ' + order);
            },
            onCheck: function (row) {
                $result.text('Event: onCheck, data: ' + JSON.stringify(row));
            },
            onUncheck: function (row) {
                $result.text('Event: onUncheck, data: ' + JSON.stringify(row));
            },
            onCheckAll: function () {
                $result.text('Event: onCheckAll');
            },
            onUncheckAll: function () {
                $result.text('Event: onUncheckAll');
            },
            onLoadSuccess: function (data) {
                $result.text('Event: onLoadSuccess, data: ' + data);
            },
            onLoadError: function (status) {
                $result.text('Event: onLoadError, data: ' + status);
            },
            onColumnSwitch: function (field, checked) {
                $result.text('Event: onSort, data: ' + field + ', ' + checked);
            },
            onPageChange: function (number, size) {
                $result.text('Event: onPageChange, data: ' + number + ', ' + size);
            },
            onSearch: function (text) {
                $result.text('Event: onSearch, data: ' + text);
            }
            */
        }).on('all.bs.table', function(e, name, args) {
            //alert('1');
            //set_switchery();
        }).on('click-row.bs.table', function(e, row, $element) {

        }).on('dbl-click-row.bs.table', function(e, row, $element) {

        }).on('sort.bs.table', function(e, name, order) {

        }).on('check.bs.table', function(e, row) {

        }).on('uncheck.bs.table', function(e, row) {

        }).on('check-all.bs.table', function(e) {

        }).on('uncheck-all.bs.table', function(e) {

        }).on('load-success.bs.table', function(e, data) {
            set_switchery();
        }).on('load-error.bs.table', function(e, status) {

        }).on('column-switch.bs.table', function(e, field, checked) {

        }).on('page-change.bs.table', function(e, size, number) {
            //alert('1');
            //set_switchery();
        }).on('search.bs.table', function(e, text) {

        });
    });
</script>
<style>
    /* .width150 {
        min-width: 250px;
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn-labeled:not(.btn-block):not(.form-icon),
    #container .table td,
    #container .table th {
        font-size: 13px;
    } */
</style>