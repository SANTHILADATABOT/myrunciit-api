<?php
$edit_rights=$user_rights_12_0['edit_rights'];
$delete_rights=$user_rights_12_0['delete_rights'];
?>
<div class="panel-body" id="demo_s">
    <table id="demo-table" class="table table-striped" data-pagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true">
        <thead>
            <tr>
                <th><?php echo translate('logo'); ?></th>
                <th><?php echo translate('display_name'); ?></th>
                <th><?php echo translate('name'); ?></th>
                <th><?php echo translate('address'); ?></th>
                <th><?php echo translate('additional_store_information'); ?></th>
                <th><?php echo translate('pickup'); ?></th>
                <th><?php echo translate('delivery'); ?></th>
                <th><?php echo translate('phone'); ?></th>

                <th><?php echo translate('email'); ?></th>
                <th><?php echo translate('status'); ?></th>
                <th class="text-right"><?php echo translate('default_store'); ?></th>

                <th class="text-right"><?php echo translate('options'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($all_vendors as $row) {
                $i++;
            ?>
                <tr>
                    <td>
                        <?php
                        if (file_exists('uploads/vendor_logo_image/logo_' . $row['vendor_id'] . '.png')) {
                        ?>
                            <img class="img-sm img-border" src="<?php echo base_url(); ?>uploads/vendor_logo_image/logo_<?php echo $row['vendor_id']; ?>.png" />
                        <?php
                        } else {
                        ?>
                            <img class="img-sm img-border" src="<?php echo base_url(); ?>uploads/vendor_logo_image/default.jpg" alt="">
                        <?php
                        }
                        ?>

                    </td>
                    <td><?php echo $row['display_name']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['address1'] . ',' . $row['city'] . ',' . $row['state'] . ',' . $row['country'] . ',' . $row['zip']; ?></td>
                    <td><?php echo "Delivery Zipcode " . $row['delivery_zipcode']; ?></td>
                    <td><?php if ($row['pickup'] == 'yes') { ?> <i class="fa fa-check" aria-hidden="true" style="color:green"></i>
                        <?php } else { ?><i class="fa fa-close" aria-hidden="true" style="color:red"></i>
                        <?php } ?> </td>
                    <td><?php if ($row['delivery'] == 'yes') { ?> <i class="fa fa-check" aria-hidden="true" style="color:green"></i>
                        <?php } else { ?><i class="fa fa-close" aria-hidden="true" style="color:red"></i>
                        <?php } ?> </td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <div class="label label-<?php if ($row['status'] == 'approved') { ?>purple<?php } else { ?>danger<?php } ?>">
                            <?php echo $row['status']; ?>
                        </div>
                    </td>
                    <td>
                        <input class='aiz_switchery' type="checkbox" data-set='default_set' data-id='<?php echo $row['vendor_id']; ?>' data-tm='<?php echo translate('default_enabled'); ?>' data-fm='<?php echo translate('default_disabled'); ?>' <?php if ($row['default_set'] == 'ok') { ?>checked<?php } ?> />

                    </td>
                    <td class="text-right">
                        <a style="display:none;" class="btn btn-dark btn-xs btn-labeled fa fa-user" data-toggle="tooltip" onclick="ajax_modal('view','<?php echo translate('view_profile'); ?>','<?php echo translate('successfully_viewed!'); ?>','vendor_view','<?php echo $row['vendor_id']; ?>')" data-original-title="View" data-container="body">
                            <?php echo translate('profile'); ?>
                        </a>
                        <a class="btn btn-success btn-xs btn-labeled fa fa-check" data-toggle="tooltip" onclick="ajax_modal('approval','<?php echo translate('store_approval'); ?>','<?php echo translate('successfully_viewed!'); ?>','vendor_approval','<?php echo $row['vendor_id']; ?>')" data-original-title="View" data-container="body">
                            <?php
                            $commission_specific = $this->db->get_where('business_settings', array('type' => 'commission_type'))->row()->value;
                            if ($commission_specific == 'specific_vendor') {
                                echo translate('approval & commission');
                            } else {
                                echo translate('approval');
                            } ?>
                        </a>
                        <a style="display:none;" class="btn btn-info btn-xs btn-labeled fa fa-dollar" data-toggle="tooltip" onclick="ajax_modal('pay_form','<?php echo translate('pay_vendor'); ?>','<?php echo translate('successfully_viewed!'); ?>','vendor_pay','<?php echo $row['vendor_id']; ?>')" data-original-title="View" data-container="body">
                            <?php echo translate('pay'); ?>
                        </a>
                        <?php if($edit_rights=='1'){ ?>
                        <a class="btn btn-info btn-xs btn-labeled fa fa-dollar" data-toggle="tooltip" onclick="ajax_modal('edit','<?php echo translate('edit_store'); ?>','<?php echo translate('successfully_updated!'); ?>','vendor_edit','<?php echo $row['vendor_id']; ?>')" data-original-title="Edit" data-container="body">
                            <?php echo translate('Edit'); ?>
                        </a>
                        <?php } ?>
                        <?php if($delete_rights=='1'){ ?>
                        <a onclick="delete_confirm('<?php echo $row['vendor_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-xs btn-danger btn-labeled fa fa-trash" data-toggle="tooltip" data-original-title="Delete" data-container="body">
                            <?php echo translate('delete'); ?>
                        </a>
                        <?php } ?>
                        <a class="btn btn-xs btn-danger btn-labeled" onclick="pickup_slot_list('<?php echo $row['vendor_id'] ?>')" data-toggle="modal" data-target="#pickupHoursModal" data-vendorId="<?php echo $row['vendor_id'] ?>" data-original-title="Delete" data-container="body">
                            Pickup Slot
                        </a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="pickupHoursModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pickup hours</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Available Days</th>
                            <th>Slot Start</th>
                            <th>Slot End</th>
                            <th>Interval In Minute</th>
                            <th>Max Order</th>
                            <th>Action</th>
                        </tr>

                    </thead>
                    <tbody id="pickupHourBody">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pickupHoursModal_vendorId" value="" />
                <button type="button" class="btn btn-primary" id="pickup_slot_addbtn" onclick="pickup_slot_modal1('',pickupHoursModal_vendorId.value)">Add Hours</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="vendr"></div>
<div id='export-div' style="padding:40px;">
    <h1 id='export-title' style="display:none;"><?php echo translate('vendors'); ?></h1>
    <table id="export-table" data-export-types="['excel','pdf']" data-show-export="true" class="table" data-name='vendors' data-orientation='p' data-width='1500' style="display:none;">
        <colgroup>
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
        </colgroup>
        <thead>
        <tr>
                <th><?php echo translate('logo'); ?></th>
                <th><?php echo translate('display_name'); ?></th>
                <th><?php echo translate('name'); ?></th>
                <th><?php echo translate('address'); ?></th>
                <th><?php echo translate('additional_store_information'); ?></th>
                <th><?php echo translate('pickup'); ?></th>
                <th><?php echo translate('delivery'); ?></th>
                <th><?php echo translate('phone'); ?></th>

                <th><?php echo translate('email'); ?></th>
                <th><?php echo translate('status'); ?></th>
                <th class="text-right"><?php echo translate('default_store'); ?></th>

            </tr>
        </thead>



        <tbody>
        <?php
            $i = 0;
            foreach ($all_vendors as $row) {
                $i++;
            ?>
                <tr>
                    <td>
                        <?php
                        if (file_exists('uploads/vendor_logo_image/logo_' . $row['vendor_id'] . '.png')) {
                        ?>
                            <img class="img-sm img-border" width="100" height="100" src="<?php echo base_url(); ?>uploads/vendor_logo_image/logo_<?php echo $row['vendor_id']; ?>.png" />
                        <?php
                        } else {
                        ?>
                            <img class="img-sm img-border" width="100" height="100" src="<?php echo base_url(); ?>uploads/vendor_logo_image/default.jpg" alt="">
                        <?php
                        }
                        ?>

                    </td>
                    <td><?php echo $row['display_name']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['address1'] . ',' . $row['city'] . ',' . $row['state'] . ',' . $row['country'] . ',' . $row['zip']; ?></td>
                    <td><?php echo "Delivery Zipcode " . $row['delivery_zipcode']; ?></td>
                    <td><?php if ($row['pickup'] == 'yes') { ?><i class="fa fa-check" aria-hidden="true" style="color:green"></i>
                        <?php } else { ?><i class="fa fa-close" aria-hidden="true" style="color:red"></i>
                        <?php } ?> </td>
                    <td><?php if ($row['delivery'] == 'yes') { ?><i class="fa fa-check" aria-hidden="true" style="color:green"></i>
                        <?php } else { ?><i class="fa fa-close" aria-hidden="true" style="color:red"></i>
                        <?php } ?> </td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <div class="label label-<?php if ($row['status'] == 'approved') { ?>purple<?php } else { ?>danger<?php } ?>">
                            <?php echo $row['status']; ?>
                        </div>
                    </td>
                    <td>
                        <input class='aiz_switchery' type="checkbox" data-set='default_set' data-id='<?php echo $row['vendor_id']; ?>' data-tm='<?php echo translate('default_enabled'); ?>' data-fm='<?php echo translate('default_disabled'); ?>' <?php if ($row['default_set'] == 'ok') { ?>checked<?php } ?> />

                    </td>
                    
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    function set_switchery() {
        $(".aiz_switchery").each(function() {
            new Switchery($(this).get(0), {
                color: 'rgb(100, 189, 99)',
                secondaryColor: '#cc2424',
                jackSecondaryColor: '#c8ff77'
            });

            var changeCheckbox = $(this).get(0);
            var false_msg = $(this).data('fm');
            var true_msg = $(this).data('tm');
            changeCheckbox.onchange = function() {
                $.ajax({
                    url: base_url + 'index.php/admin/vendor/' + $(this).data('set') + '/' + $(this).data('id') + '/' + changeCheckbox.checked,
                    success: function(result) {
                        if (changeCheckbox.checked == true) {
                            $.activeitNoty({
                                type: 'success',
                                icon: 'fa fa-check',
                                message: true_msg,
                                container: 'floating',
                                timer: 3000
                            });
                            sound('published');
                        } else {
                            $.activeitNoty({
                                type: 'danger',
                                icon: 'fa fa-check',
                                message: false_msg,
                                container: 'floating',
                                timer: 3000
                            });
                            sound('unpublished');
                        }
                    }
                });
            };
        });
    }

    $(document).ready(function() {
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({
            width: '100%'
        });
    });
</script>

<div class="modal fade" id="pickup_slot_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pickup Slot</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="pickup_slot_id" value="" />
                <input type="hidden" id="pickup_slot_vendorid" value="" />
                <div class="card">
                    <div class="card-body">
                        <label><b>Available Days</b> <span class="text-danger">*</span></label>
                        <div class="row" id="availableDays_div"></div>
                        <div class="label label-danger" style="display:none;" id="pickup_slot_ch_note"></div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pickup_slot_starttm"><b>Pickup Slots Start At</b> <span class="text-danger">*</span></label>
                                    <select class="form-control" id="pickup_slot_starttm" style="width:100%;">
                                        <?php foreach ($timeList as $single) : ?>
                                        <option value="<?php echo $single['slot_start_time'] ?>"><?php echo $single['slot_start_time'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <div class="label label-danger" style="display:none;" id='pickup_slot_starttm_note'>&nbsp;</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pickup_slot_endtm"><b>Pickup Slot End At</b> <span class="text-danger">*</span></label>
                                    <select class="form-control" id="pickup_slot_endtm">
                                        <?php foreach ($timeList as $single) : ?>
                                        <option value="<?php echo $single['slot_start_time'] ?>"><?php echo $single['slot_start_time'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <div class="label label-danger" style="display:none;" id='pickup_slot_endtm_note'>&nbsp;</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="hidden" id="pickup_slot_interval" value="60">
                                    <label for="pickup_slot_maxOrder"><b>Max Orders Per Slot</b> <span class="text-danger">*</span></label>
                                    <input type="number" id="pickup_slot_maxOrder" class="form-control" value="10">
                                    <div class="label label-danger" style="display:none;" id='pickup_slot_maxOrder_note'>&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="pickup_slot_cancel(pickup_slot_vendorid.value)">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="pickup_slot_savefun(pickup_slot_id.value,pickup_slot_vendorid.value)">Save</button>
            </div>
        </div>
    </div>
</div>
<script>
    function pickup_slot_list(vendorId)
    {
        $("#pickupHoursModal #pickupHoursModal_vendorId").val(vendorId);
        $('#pickupHourBody').html("");
        $.post('<?php echo base_url('admin/getPickupDetailAsVendor/vendor_detail') ?>', 'vendorId=' + vendorId, function(data) {
            var pickupHourBody="";var days=[];
            var jsonData = JSON.parse(data);
            jsonData.forEach(element => {
                var slot_days=(element.slot_days!="")?JSON.parse(element.slot_days).toString():"";
                pickupHourBody+="<tr><td class='text-center'>"+slot_days+"</td><td class='text-center'>"+element.slot_start+"</td><td class='text-center'>"+element.slot_end+"</td><td class='text-center'>"+element.interval_in_minute+"</td><td class='text-center'>"+element.max_order+"</td><td><a class='btn btn-danger btn-xs btn-labeled' onclick=\"pickup_slot_modal1('"+element.id+"','"+vendorId+"')\"'><?php echo translate('Edit'); ?></a></td></tr>";
                if(slot_days!=""){
                    JSON.parse(element.slot_days).forEach(element1 => {
                        if(!days.includes(element1)){days.push(element1);}
                    });
                }
            });
            $('#pickupHourBody').html(pickupHourBody);
            document.getElementById("pickup_slot_addbtn").style.visibility = (days.length==7)?"hidden":"visible";
        });
    }
    const weekdays_Arr = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
    function pickup_slot_modal1(id,vendorId)
    {
        $("#pickupHoursModal").modal('hide');
        $("#pickup_slot_modal").modal('show');
        $("#pickup_slot_modal #pickup_slot_id").val(id);
        $("#pickup_slot_modal #pickup_slot_vendorid").val(vendorId);

        $("#pickup_slot_modal #availableDays_div").html("");
        $("#pickup_slot_modal #pickup_slot_starttm").val("");
        $("#pickup_slot_modal #pickup_slot_endtm").val("");
        $("#pickup_slot_modal #pickup_slot_interval").val("60");
        $("#pickup_slot_modal #pickup_slot_maxOrder").val("10");
        
        $("#pickup_slot_ch_note").hide();$("#pickup_slot_ch_note").html('&nbsp;');
        $("#pickup_slot_starttm_note").hide();$("#pickup_slot_starttm_note").html('&nbsp;');
        $("#pickup_slot_endtm_note").hide();$("#pickup_slot_endtm_note").html('&nbsp;');
        $("#pickup_slot_maxOrder_note").hide();$("#pickup_slot_maxOrder_note").html('&nbsp;');
        
        $.post('<?php echo base_url('admin/getPickupDetailAsVendor/vendor_detail') ?>', 'vendorId=' + vendorId, function(data) {
            var days=[];var pickup_slot_data=null;
            var jsonData = JSON.parse(data);
            if(id=="") {
                jsonData.forEach(element => {
                    if(element.slot_days!=""){
                        var slot_days = JSON.parse(element.slot_days);
                        slot_days.forEach(element1 => {
                            if(!days.includes(element1)){days.push(element1);}
                        });
                    }
                });
            } else {
                jsonData.forEach(element => {
                    if(element.id==id){
                        pickup_slot_data=element;
                    }else if(element.slot_days!=""){
                        var slot_days = JSON.parse(element.slot_days);
                        slot_days.forEach(element1 => {
                            if(!days.includes(element1)){days.push(element1);}
                        });
                    }
                });
            }
            var availableDays_div="";
            for(let i1=0;i1<weekdays_Arr.length;i1++){
                var weekdays1=weekdays_Arr[i1];
                if(!days.includes(weekdays1)){
                    availableDays_div+="<div class='col-lg-1'><input class='form-check-input pickup_slot_chdays' type='checkbox' id='pickup_slot_ch-"+weekdays1+"'><label class='form-check-label' for='pickup_slot_ch-"+weekdays1+"'>"+weekdays1+"</label></div>";
                }
            }
            $("#pickup_slot_modal #availableDays_div").html(availableDays_div);
            if(id!=""){
                if(pickup_slot_data.slot_days!=""){
                    var slot_days = JSON.parse(pickup_slot_data.slot_days);
                    slot_days.forEach(element1 => {
                        $("#pickup_slot_modal #pickup_slot_ch-"+element1).prop("checked",true);
                    });
                }
                $("#pickup_slot_modal #pickup_slot_starttm").val(pickup_slot_data.slot_start);
                $("#pickup_slot_modal #pickup_slot_endtm").val(pickup_slot_data.slot_end);
                $("#pickup_slot_modal #pickup_slot_interval").val(pickup_slot_data.interval_in_minute);
                $("#pickup_slot_modal #pickup_slot_maxOrder").val(pickup_slot_data.max_order);
            }
        });
    }
    function pickup_slot_savefun(id,vendor_id)
    {
        var pickup_slot_chdays=[];
        var chdays=document.getElementsByClassName('pickup_slot_chdays');
        for(let i1=0;i1<chdays.length;i1++){
            var ch1=chdays[i1].checked;
            if(ch1){pickup_slot_chdays.push(chdays[i1].id.split('-')[1]);}
        }
        var slot_start=$("#pickup_slot_modal #pickup_slot_starttm").val();
        var slot_end=$("#pickup_slot_modal #pickup_slot_endtm").val();
        var interval_in_minute=$("#pickup_slot_modal #pickup_slot_interval").val();
        var max_order=$("#pickup_slot_modal #pickup_slot_maxOrder").val();
        if((pickup_slot_chdays.length>0)&&((slot_start && slot_end)?(slot_start!=slot_end):false)&&(max_order!=""))
        {
            var slot_days=JSON.stringify(pickup_slot_chdays);
            $.post('<?php echo base_url('admin/getPickupDetailAsVendor/pickup_slot_save') ?>',
                'id='+id+'&vendor_id='+vendor_id+'&slot_days='+slot_days+'&slot_start='+slot_start+'&slot_end='+slot_end+'&interval_in_minute='+interval_in_minute+'&max_order='+max_order,
                function(data) {
                    pickup_slot_list(vendor_id);
                    $("#pickupHoursModal").modal('show');
                    $("#pickup_slot_modal").modal('hide');
                }
            );
        }
        else
        {
            if(pickup_slot_chdays.length==0)
            {$("#pickup_slot_ch_note").show();$("#pickup_slot_ch_note").html('*Days Not Selected');return false;}
            else{$("#pickup_slot_ch_note").hide();$("#pickup_slot_ch_note").html('&nbsp;');}
            if(!slot_start){$("#pickup_slot_starttm_note").show();$("#pickup_slot_starttm_note").html('*Slot Start Time');return false;}
            else{$("#pickup_slot_starttm_note").hide();$("#pickup_slot_starttm_note").html('&nbsp;');}
            if(!slot_end){$("#pickup_slot_endtm_note").show();$("#pickup_slot_endtm_note").html('*Slot End Time');return false;}
            else{$("#pickup_slot_endtm_note").hide();$("#pickup_slot_endtm_note").html('&nbsp;');}
            if((slot_start && slot_end)?(slot_start==slot_end):false)
            {$("#pickup_slot_endtm_note").show();$("#pickup_slot_endtm_note").html('*Select Different End Time');return false;}
            else{$("#pickup_slot_endtm_note").hide();$("#pickup_slot_endtm_note").html('&nbsp;');}
            if(max_order==""){$("#pickup_slot_maxOrder_note").show();$("#pickup_slot_maxOrder_note").html('*Enter Max Order');return false;}
            else{$("#pickup_slot_maxOrder_note").hide();$("#pickup_slot_maxOrder_note").html('&nbsp;');}
        }
    }
    function pickup_slot_cancel(vendor_id)
    {
        pickup_slot_list(vendor_id);
        $("#pickupHoursModal").modal('show');
        $("#pickup_slot_modal").modal('hide');
    }
</script>