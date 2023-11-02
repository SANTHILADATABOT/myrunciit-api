<div class="panel-body" id="demo_s">
    <style>
        img.logd {
            width: 80px;
            height: 80px;
            float: left;
            margin-right: 40px;
        }


        table.dc {
            width: 1000px;
        }

        .form-control.lnh {
            line-height: 15px;
        }

        .sbbt {
            text-transform: capitalize;
            font-size: 15px;
        }

        .fg label {
            display: block;
        }
    </style>
    <div class="panel-body" id="demo_s" style="padding:0px">


        <form class="form-inline" id="form1" name="form1" method="post" action="<?php echo base_url(); ?>index.php/admin/del_orders">
            <div class="form-group fg" style="width:100%; float:left;  padding-bottom: 20px;">
                <div class="col-md-4">
                    <label style="">From : </label>
                    <input style="float:left; width:100% !important;" class="form-control lnh" type="date" name="fromR" id="fromR" value="<?php if ($from) {
                                                                                                                                                echo $from;
                                                                                                                                            } else {
                                                                                                                                                $year   = date("Y");
                                                                                                                                                echo date('Y-m-d', strtotime($year . "-01-01"));
                                                                                                                                            } ?>" />
                </div>
                <div class="col-md-4">
                    <label style="" style=""> To : </label>

                    <input style="float:left; width:100% !important;" class="form-control lnh" type="date" name="toR" id="toR" value="<?php if ($to1) {
                                                                                                                                            echo $to1;
                                                                                                                                        } else {
                                                                                                                                            echo date('Y-m-d');
                                                                                                                                        } ?>" />
                </div>
                <div class="col-md-3">
                    <label style="" style=""> store: </label>
                    <select name="vendor" id="vendor" style="float:left; width:100% !important;" class="form-control lnh">
                        <option value="0">All</option>
                        <?php $venname = $this->db->get('vendor')->result_array();
                        foreach ($venname as $ven) { ?>
                            <option value="<?php echo $ven['vendor_id']; ?>" <?php if ($delstatus1 == $ven['vendor_id']) {
                                                                                    echo 'selected="selected"';
                                                                                } ?>><?php echo $ven['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
  
                <div class="col-md-1">
                    <input style="margin-top: 25px;" class="form-control btn-default sbbt" type="submit" name="date" id="date" value="submit" />
                </div>
            </div>
<br /><br />

        </form>
        <table id="demo-table" data-export-types="['excel','pdf']" data-show-export="true" class="table table-striped" data-pagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true">

            <thead>
                <tr>
                    <th style="width:4ex"><?php echo translate('ID'); ?></th>
                    <th><?php echo translate('sale_code'); ?></th>
                    <th><?php echo translate('store_name'); ?></th>
                    <th><?php echo translate('buyer'); ?></th>
                    <th><?php echo translate('product_name'); ?></th>
                    <th><?php echo translate('date'); ?></th>
                    <th><?php echo translate('total'); ?></th>
                    <th><?php echo translate('delivery_status'); ?></th>

                    <th class="text-right"><?php echo translate('options'); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                $i = 0;
                foreach ($all_sales as $row) {
                    $i++;
                ?>
                    <tr class="<?php if ($row['viewed'] !== 'ok') {
                                    echo 'pending';
                                } ?>">
                        <td><?php echo $i; ?></td>
                        <td>#<?php echo $row['order_id']; ?></td>
                        <td> <?php echo $this->db->get_where('vendor', array(
                                    'vendor_id' => $row['store_id']
                                ))->row()->name; ?></td>
                        <td><?php echo $this->crud_model->get_type_name_by_id('user', $row['buyer'], 'username'); ?></td>
                        <td><?php $dta = json_decode($row['product_details'], 1);
                            foreach ($dta as $dta1) {
                                echo $dta1['name'];
                            } ?></td>
                        <td><?php echo $row['created_datetime']; //date('d-m-Y',$row['sale_datetime']); 
                            ?></td>
                        <td class="pull-right"><?php echo currency('', 'def') . $this->cart->format_number($row['grand_total']); ?></td>
                        <td>
                            <?php
                            $this->benchmark->mark_time();
                            $delivery_status = json_decode($row['delivery_status'], true);
                            foreach ($delivery_status as $dev) {
                            ?>

                                <div class="label label-<?php if ($dev['status'] == 'delivered') { ?>purple<?php } else { ?>danger<?php } ?>"><?php if (isset($dev['vendor'])) {
                                                                                                                                                    echo $this->crud_model->get_type_name_by_id('vendor', $dev['vendor'], 'display_name') . ' <br/>(' . translate('vendor') . ') <br/>Status : ' . $dev['status'];
                                                                                                                                                } else if (isset($dev['admin'])) {
                                                                                                                                                    echo translate('admin') . ' : ' . $dev['status'];
                                                                                                                                                }
                                                                                                                                                ?>
                                </div>
                                <br>
                            <?php
                            }
                            ?>
                        </td>

                        <td class="text-left">

                            <a class="btn btn-info btn-xs btn-labeled fa fa-file-text" data-toggle="tooltip" onclick="ajax_set_full('view','<?php echo translate('title'); ?>','<?php echo translate('successfully_edited!'); ?>','sales_view','<?php echo $row['sale_id']; ?>')" data-original-title="Edit" data-container="body"><?php echo translate('invoice'); ?>
                            </a>


                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>


    <div id='export-div' style="padding:40px;">
        <h1 id='export-title' style="display:none;"><?php echo translate('Delivered Orders'); ?></h1>
        <table id="export-table" class="table" data-name='Delivered Orders' data-orientation='p' data-width='3500' style="display:none;">
            <colgroup>
                <col width="50">
                <col width="500">
                <col width="1000">
                <col width="500">
                <col width="500">
                <col width="500">
                <col width="500">
                <col width="800">
            </colgroup>
            <thead>
                <tr>
                    
                    <th><?php echo translate('ID'); ?></th>
                    <th><?php echo translate('sale_code'); ?></th>
                    <th><?php echo translate('store_name'); ?></th>
                    <th><?php echo translate('buyer'); ?></th>
                    <th><?php echo translate('product_name'); ?></th>
                    <th><?php echo translate('date'); ?></th>
                    <th><?php echo translate('total'); ?></th>
                    <th><?php echo translate('delivery_status'); ?></th>


                </tr>
            </thead>

            <tbody>
            <?php
                $i = 0;
                foreach ($all_sales as $row) {
                    $i++;
                ?>

                    <tr>
                        <td><?php echo $i; ?></td>
                        <td>#<?php echo $row['order_id']; ?></td>
                        <td> <?php echo $this->db->get_where('vendor', array(
                                    'vendor_id' => $row['store_id']
                                ))->row()->name; ?></td>
                        <td><?php echo $this->crud_model->get_type_name_by_id('user', $row['buyer'], 'username'); ?></td>
                        <td><?php $dta = json_decode($row['product_details'], 1);
                            foreach ($dta as $dta1) {
                                echo $dta1['name'];
                            } ?></td>
                        <td><?php echo $row['created_datetime']; //date('d-m-Y',$row['sale_datetime']); 
                            ?></td>
                        <td class="pull-right"><?php echo currency('', 'def') . $this->cart->format_number($row['grand_total']); ?></td>
                        <td>
                            <?php
                            $this->benchmark->mark_time();
                            $delivery_status = json_decode($row['delivery_status'], true);
                            foreach ($delivery_status as $dev) {
                            ?>

                                <div class="label label-<?php if ($dev['status'] == 'delivered') { ?>purple<?php } else { ?>danger<?php } ?>"><?php if (isset($dev['vendor'])) {
                                                                                                                                                    echo $this->crud_model->get_type_name_by_id('vendor', $dev['vendor'], 'display_name') . ' <br/>(' . translate('vendor') . ') <br/>Status : ' . $dev['status'];
                                                                                                                                                } else if (isset($dev['admin'])) {
                                                                                                                                                    echo translate('admin') . ' : ' . $dev['status'];
                                                                                                                                                }
                                                                                                                                                ?>
                                </div>
                                <br>
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

    <style type="text/css">
        .pending {
            background: #D2F3FF !important;
        }

        .pending:hover {
            background: #9BD8F7 !important;
        }
    </style>

    <script>
        /*
        $("#date").click(function(){
            var from=$('#fromR').val();
            var to=$('#toR').val();
            
            if(from !='' && to !='')
            {
                $.post(base_url+"index.php/admin/del_orders/list/"+from+"/"+to, function(data, status){
                });
            }
            
            else
            {
                $.post(base_url+"index.php/admin/del_orders/list/", function(data, status){
                });
            }
            
        });*/
    </script>