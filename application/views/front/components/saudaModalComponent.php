
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlVdIucfxScZzEGUmEbcOzarwn6Kc-GAg&libraries=places"></script>
<script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
<script src="https://momentjs.com/downloads/moment-timezone-with-data-1970-2030.js"></script>
<div id="saudaModal" style="display:none;" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content clearfix">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                <img src="<?php echo base_url(); ?>uploads/logo_image/logo_87.png" alt="" />
            </div>
            <div class="modal-body">
                <div id="pops_0">
                    <h4 class="modal-title">Select PICKUP/DELIVERY</h4>
                    <div class="row mtsd">

                        <div class="col-md-6 col-md-offset-3 dstf">
                            <div class="col-md-6 col-xs-6 col-sm-6 gtdf">
                                <div class="box_p">
                                    <a href="javascript:void(0);" onClick="set_pops2('pops_0','pops_3','pickup'); set_pops23('pos_5')">
                                        <div class="box_img">
                                            <img src="<?php echo base_url(); ?>template/front/img/pickup.png" style="" width="80">
                                        </div>
                                        <p>Pick Up</p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-6 col-sm-6 gtdf">
                                <div class="box_p">
                                    <a href="javascript:void(0);" onClick="set_pops2('pops_0','pops_1','delivery')">
                                        <div class="box_img">
                                            <img src="<?php echo base_url(); ?>template/front/img/delivery.png" width="80">
                                            <!-- <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQL2o-GvfvQgvKPx9yd5qmgi7PJRP4ZI9N6hA&amp;usqp=CAU" width="80">-->

                                        </div>
                                        <p>Delivery</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($this->session->userdata('user_login') != "yes") { ?>
                        <div class="auth-flow__auth">Already have an account? <a href="<?php echo base_url(); ?>home/login_set/login">Sign in</a></div>
                    <?php } ?>
                </div>
                <div id="pops_1" style="display:none;">
                    <h4 class="modal-title">Absurdly fresh groceries, delivered.</h4>
                    <p>Enter your zip code to see if we deliver to your address.</p>
                    <div class="form-group clearfix">
                        <input type="text" class="form-control" placeholder="Zip Code" name="zipcodecheck1" id="zipcodecheck1" value="">
                        <span class="zip"></span>
                        <button type="button" onClick="onDeliveryClicked()" id="continueBtn" class="btn btn-primary new_btn">Continue</button>
                    </div>
                    <p id="pincode" style="font-size: 18px;"></p>
                    <div id="zipcodeerror" class="clearfix" style="display:none !important; color:#F00 !important; font-size:22px !important;">Sorry, we don't serve in "<span id="zipcodevalue"></span>"</div>
                    <p id="tryerror" style="display:none;" class="try_zip"><a href="javascript:void(0);" onClick="set_pops1('pops_1','pops_2')">Try With another ZIP Code</a></p>
                    <?php if ($this->session->userdata('user_login') != "yes") { ?>
                        <div class="auth-flow__auth">Already have an account? <a href="<?php echo base_url(); ?>home/login_set/login">Sign in</a></div>
                    <?php } ?>
                    <div><a href="javascript:void(0);" onClick="set_pops2('pops_1','pops_0')">Back</a></div>
                </div>
                <div id="pops_2" style="display:none">
                    <div class="clearfix">
                        <div class="alert-danger text-danger errorMessage">store does not have this product</div>
                        <div class="form-group" id="storeList">

                        </div>
                        <div class="col-lg-12">
                            <div id="preOrderDeliveryNotAvailable" style="display: none;" class="alert-danger text-danger">Pre Order is not available for this day</div>
                        </div>
                        <div class="form-group" id="pre_order_delivery_checkbox_section">
                            <label for="pre_order_delivery_date_selected">Pre Order</label>
                            <input type="checkbox" name="pre_order_delivery_checkbox_selected" id="pre_order_delivery_checkbox_selected">
                        </div>
                        <div class="form-group" id="pre_order_delivery_date_section">
                            <label for="pre_order_delivery_date_selected">Pre Order Date</label>
                            <input type="date" name="pre_order_delivery_date_selected" data-status="" id="pre_order_delivery_date_selected">
                        </div>
                        <?php if ($this->session->userdata('user_login') != "yes") { ?>
                            <div class="clearfix text-center">
                                <button type="button" onClick="continueDeliveryShiping()" class="btn btn-primary sign_mail contis continueShopping" style="display: none;">Continue Shopping</button>
                            </div>
                            <div class="clearfix text-center hidden">
                                <a href="<?php echo base_url() . 'home/login_set/registration'; ?>"><button type="button" class="btn btn-primary sign_mail">Sign Up With Email</button></a>
                            </div>

                            <div class="auth-flow__auth">Already have an account? <a href="<?php echo base_url(); ?>home/login_set/login">Sign in</a></div>
                            <div class="text-center ma_wi clearfix hidden">
                                <div class="col-md-5 col-xs-5"><span class="brder"></span></div>
                                <div class="col-md-2 col-xs-2">Or</div>
                                <div class="col-md-5 col-xs-5"><span class="brder rts"></span></div>
                            </div>
                            <div class="text-center ma_wi145 clearfix hidden">
                                <div class="col-md-6 log-face <?php if ($g_login_set !== 'ok') { ?>mr_log<?php } ?>">
                                    <?php if (@$user) : ?>
                                        <a href="<?= $url ?>" class="clearfix">
                                            <i class="fa fa-facebook-square"></i><span>Facebook</span>
                                        </a>
                                    <?php else : ?>
                                        <a href="<?= $url ?>" class="clearfix">
                                            <i class="fa fa-facebook-square"></i><span>Facebook</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 log-goo <?php if ($fb_login_set !== 'ok') { ?>mr_log<?php } ?>">
                                    <?php if (@$g_user) : ?>
                                        <a href="<?= $g_url ?>" class="clearfix">
                                            <img src="<?php echo base_url(); ?>uploads/google.png">
                                            <span>Google</span>
                                        </a>
                                    <?php else : ?>
                                        <a href="<?= $g_url ?>" class="clearfix">
                                            <img src="<?php echo base_url(); ?>uploads/google.png">
                                            <span>Google</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-center ma_wi1 m20_ clearfix" style="display:none;">
                                <div class="col-md-6 log-face"><a href="javascript:void(0);" class="clearfix"><i class="fa fa-facebook-square"></i><span>Facebook</span></a></div>
                                <div class="col-md-6 log-goo"><a href="javascript:void(0);" class="clearfix"><img src="<?php echo base_url(); ?>uploads/google.png"><span>Google</span></a></div>
                            </div>
                        <?php }  ?>
                        <?php if ($this->session->userdata('user_login') == "yes") { ?>
                            <div class="clearfix text-center">
                                <button type="button" onClick="continueDeliveryShiping()" class="btn btn-primary sign_mail contis continueShopping" style="display: none;">Continue Shopping</button>
                            </div>
                        <?php } ?>
                        <p class="try_zip"><a href="javascript:void(0);" onClick="set_pops('pops_2','pops_1')">Try With another ZIP Code</a></p>
                    </div>
                </div>
                <div id="pops_3" style="display:none">
                    <div class="clearfix">
                        <h4 class="modal-title">Pickup Location <span id="dzip"></span>.</h4>
                        <div class="alert-danger text-danger errorMessage">store does not have this product</div>


                        <div class="form-group clearfix">
                            <!-- this vendor table is acting like a store here -->
                            <?php 
                            $vendorid = $this->session->userdata('vendorid');
                            if($vendorid=="")
                            {
                                $vendorid="2";
                            }
                            $store_address =  $this->db->get_where('vendor', array('status' => 'approved', 'pickup' => 'yes','vendor_id'=>$vendorid))->result_array();
                           // echo  $this->db->last_query();
                            foreach ($store_address as $store_info) {
                            ?>
                                <p style="text-align: left;">
                                    <input value="<?php echo $store_info['vendor_id']; ?>" class="storeType" type="radio" name="pickup" onClick="set_pops22('pos_5')">
                                    <label for="<?php echo $store_info['vendor_id']; ?>" style="background: #cc0101;color: #fff;padding: 3px 8px;margin-left: 5px;border-radius: 3px;"><?php echo $store_info['name'] . ' -'; ?><?= $store_info['id']; ?>
                                    </label><?php echo "<span style='display: block;margin-left: 19px;margin-top: 5px;'>" . $store_info['city'] . "," . $store_info['state'] . "," . $store_info['country'] . "</span>"; ?>
                                </p>

                            <?php } ?>

                        </div>
<div id="pos_5"><a href="javascript:void(0);" onClick="set_pops2('pops_3','pops_0')">Back</a></div>
                    </div>
                </div>
                <div id="pos_4">
                    <div class="clearfix">
                        <div class="form-group clearfix">
                            <h4 class="card-title">Available Days</h4>

                            <form id="form3" method="post">
                                <div class="row">

                                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                        <div id="availableDaysSection"></div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group" id="pre_order_btn_section" style="display:flex;align-items: baseline;column-gap: 3px;">
                                            <input type="radio" name="pre_order_btn" id="pre_order_btn">
                                            <label for="pre_order_btn">Pre Order</label>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-12" id="pre_order_date_section">
                                        <div style="text-align: left;font-weight: 600;">
                                            <?php
                                            date_default_timezone_set('Asia/Kuala_Lumpur');
                                            $cur_dt = date('Y-m-d');
                                            // $this->db->order_by('id', 'desc');
                                            $this->db->where('status', 'ok');
                                            $pre_dts = $this->db->get('pre_order')->result_array();
                                            $s_dt = $pre_dts[0]['start_date'];
                                            $e_dt = $pre_dts[0]['end_date'];
                                            if ($pre_dts[0]['status'] == 'ok' && $s_dt <= $cur_dt && $e_dt >= $cur_dt) {
                                                echo $pre_dts[0]['description'];
                                            }
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="pre_order_date_selected" style="float: left;">Pre Order Date</label>
                                            <input type="date" min="" name="pre_order_date_selected" id="pre_order_date_selected" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div id="preOrderNotAvailable" class="alert-danger text-danger">Pre Order is not available for this day</div>
                                </div>

                                <div class="col-lg-12" id="slotSection">

                                    <div class="orderslt">
                                        <div class="" style="font-weight:600;">Pickup Slot Start</div>
                                        <div class="">
                                            <div class="form-group">

                                                <select class="form-control" name="startSlot" id="form3StartSlot" style="width:max-content;">
                                                    <?php foreach ($timeList as $single) : ?>
                                                        <option value="<?php echo $single['slot_start_time'] ?>"><?php echo $single['slot_start_time'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="" style="font-weight:600;">Pickup Slot End</div>
                                        <div class="">
                                            <div class="form-group">

                                                <select class="form-control" name="endSlot" id="form3EndSlot" style="width:max-content;">
                                                    <?php foreach ($timeList as $single) : ?>
                                                        <option value="<?php echo $single['slot_start_time'] ?>"><?php echo $single['slot_start_time'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <button type="button" onClick="locationpick()" class="btn btn-primary new_btn conus" style="margin: 1rem auto;display: block;float: none;">Continue</button>
                                    </div>
                                    <div><a href="javascript:void(0);" onClick="set_pops21('pops_3','pos_4','pops_0')">Back</a></div>

                                </div>

                                <div id="emptySlot">
                                    <p>Closed</p>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    const currentTimeZone = 'Asia/Kuala_Lumpur';
    let startSlicedArr = [];
    let preOrderStatus = 'no';
    let endSlicedArr = [];
    let preORderResponseData = [];
    const daysArr = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat']; // this array is the base according to moment doc
    let finalArr = [];
    let jsonData = null;
    let currentTimeList = null;

    function preferredOrder(obj, order) {
        var newObject = {};
        for (var i = 0; i < order.length; i++) {
            if (obj.hasOwnProperty(order[i])) {
                newObject[order[i]] = obj[order[i]];
            }
        }
        return newObject;
    }

    function showTimeWithInvervel(givenArr) {
        $.each($('#form3StartSlot').children(), function(ind, item) {
            if (givenArr.includes($(item).val())) {
                $(item).show();
            } else {
                $(item).hide();
            }
        })
        $.each($('#form3EndSlot').children(), function(ind, item) {
            if (givenArr.includes($(item).val())) {
                $(item).show();
            } else {
                $(item).hide();
            }
        })
    }


    function calculateMinutes(min) {
        console.log('passed min value', min);
        const currentIntervel = parseInt(jsonData.interval_in_minute);
        if (min <= currentIntervel) {
            min = 00;
        } else {
            min = currentIntervel;
        }

        console.log('min value', min);
        return min;
    }
    $(document).ready(function() {
        $('.errorMessage').hide();
        $('#pre_order_delivery_date_section').hide();
        $('#pre_order_delivery_checkbox_section').hide();
        $('#pre_order_date_section').hide();
        $('#slotSection').hide();
        $(".pac-container pac-logo").removeClass("pac-logo");
        $('#pos_4').hide();
        $('.errorMessage').hide();
        $('#emptySlot').hide();

        $(document).on('change', '#pre_order_delivery_date_selected', function() {
            const dateSelected = $(this).val();
            console.log('dateSelected', dateSelected);
            $('.continueShopping').show();
        })

        $(document).on('click', '.storeType', function() {
            $('#pre_order_delivery_date_section').hide();
            $('#pre_order_delivery_checkbox_section').hide();
            $('#pre_order_date_section').hide();
            $('#pre_order_btn').prop('checked', false);
            $('#pre_order_date_selected').val('');
            const transportType = localStorage.getItem('transportType');
            console.log('transportType', transportType);
            if (transportType == 'delivery') {
                // for delivery
                $('#pre_order_delivery_checkbox_selected').attr('checked', false);
                
                // get all the pre orders
                const now = moment().tz(currentTimeZone);
                const url = '<?php echo base_url('home/getAllPreOrders') ?>';
                $.post(url, {
                    currentDate: now.format('YYYY-MM-DD')
                }, function(res) {
                    console.log('getAllPreOrders', res);
                    res='yes';
                    if (res != 'no') {

                        preORderResponseData = JSON.parse(res);
                        $('#pre_order_delivery_checkbox_section').show();
                        $('#preOrderDeliveryNotAvailable').hide();
                        console.log('response', preORderResponseData);
                        // if selected date is greater then start_date then show selected date
                        const responseStartDate = moment(preORderResponseData.start_date, 'YYYY-MM-DD');
                        console.log('responseStartDate', responseStartDate);
                        const dateDiff = parseInt(moment.duration(now.diff(responseStartDate)).asDays());
                        console.log('date diff', dateDiff);
                        $('#pre_order_delivery_date_selected').attr('min', dateDiff > 0 ? now.format('YYYY-MM-DD') : preORderResponseData.start_date);
                        $('#pre_order_delivery_date_selected').attr('max', preORderResponseData.end_date);
                        $('#pre_order_delivery_date_selected').data('status', preORderResponseData.status);
                    } else {
                        alert('No Pre Order is found');
                        $('#pre_order_delivery_date_section').hide();
                        $('.continueShopping').show();
                    }

                }).fail(function(error) {
                    console.log(error);
                })
            } else {
                // for pickup
                console.log('testing');
                $('#slotSection').hide();
                $('#preOrderNotAvailable').hide();
                $('#preOrderDeliveryNotAvailable').hide();
                $('#pre_order_delivery_date_section').hide();
                $('#pre_order_btn_section').hide();

                $('#emptySlot').hide();
                $('#pos_4').show();
                $('#availableDaysSection').show();
                $(this).attr('checked', 'checked');
                const vendorId = $(this).val();
                localStorage.setItem('selectedStoreId', vendorId);
                console.log('vendore id', vendorId);
                $('#pos_4').show();
                const url = '<?php echo base_url('home/getPickupDetailAsVendor') ?>';
                $.post(url, '&vendorId=' + vendorId, function(data) {
                    console.log('mixed data', JSON.parse(data));
                    currentTimeList = JSON.parse(data).timeList;
                    console.log('currentTimeList', currentTimeList);

                    const today = moment().tz(currentTimeZone).format('ddd').toLowerCase();
                    const getIndex = daysArr.indexOf(today);
                    console.log('getIndex', getIndex);
                    startSlicedArr = daysArr.slice(getIndex);
                    endSlicedArr = getIndex != 1 ? daysArr.slice(0, getIndex - 1) : [daysArr[0]];
                    finalArr = [...startSlicedArr, ...endSlicedArr];

                    console.log('today', today);
                    console.log('finalArr', finalArr);
                    $('#availableDaysSection').children().remove();
                    jsonData = JSON.parse(data).pickupDetail[0];
                    console.log('json data', jsonData);
                    const availableData = JSON.parse(jsonData.available_days);
                    const formatedObj = preferredOrder(availableData, finalArr);
                    console.log('formatedObj', formatedObj);
                    const finalDateList = [];
                    for (const key in formatedObj) {
                        if (Object.hasOwnProperty.call(formatedObj, key)) {
                            const element = formatedObj[key];
                            // console.log(key);
                            if (element == 'on') {
                                finalDateList.push(key);
                                $('#availableDaysSection').append(`
                           <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 radioSectionInput" style="padding:0px;">
                               <input class="form-check-input " value="${key}" name="daySelected" type="radio"  id="inlineFormCheck${key}">
                               <label class="form-check-label" for="inlineFormCheck${key}">
                                   ${key}
                               </label>
                           </div>
                           `);
                            }
                        }
                    }
                    hidePreviousTime(jsonData.slot_start, jsonData.slot_end);
                    console.log('finalDateList', finalDateList);
                    // hide today if today time is over
                    hideTodayIfTimeIsOver(finalDateList[0], jsonData.slot_start);

                }).fail(function(error) {
                    console.log(error);
                })
            }
        })

        $(document).on('change', '#pre_order_delivery_checkbox_selected', function(e) {
            e.preventDefault();
            if ($(this).is(':checked')) {
                console.log('is checked');
                $('#pre_order_delivery_date_section').show();
            } else {
                console.log('unchecked');
                $('#pre_order_delivery_date_section').hide();
            }
        })

        function getEndTime(startTime){
            console.log('getEndTime start time', startTime);
            const startTimeArr = startTime.split(':');
            const endMinute = parseInt(jsonData.interval_in_minute) + parseInt(startTimeArr[1].substr(0, 2));
            const hour = parseInt(startTimeArr[0]);
            let calculatedHour = hour;
            let calculatedMinute = endMinute;
            if (endMinute == 60 || endMinute > 60) {
                calculatedHour = hour + 1;
            }
            if (endMinute == 60) {
                calculatedMinute = '00';
            }
            if (endMinute > 60) {
                calculatedMinute = endMinute - 60;
            }
            if (calculatedHour > 12) {
                calculatedHour = 1;
            }

            const endTime = `${calculatedHour}:${calculatedMinute} ${startTimeArr[1].substr(-2)}`;
            console.log('endTime', endTime);
            return endTime;
        }


        $('#form3StartSlot').change(function() {
            console.log('start time changed');
            const startTime = $(this).val();
            const endTime = getEndTime(startTime);
            showSpecificTime(endTime);
        });

        function showSpecificTime(endTime) {
            $('#form3EndSlot').val(endTime);
            $.each($('#form3EndSlot').children(), function(ind, item) {
                if ($(item).val() == endTime) {
                    $(item).show();
                } else {
                    $(item).hide();
                }
            })
        }

        function hidePreviousTime(slot_start, slot_end) {
            console.log(`slot_start slot_end ${slot_start}, ${slot_end}`);
            const timeList = currentTimeList;
            // console.log('updated time list', timeList);
            const formatedList = [];
            for (const key in timeList) {
                if (Object.hasOwnProperty.call(timeList, key)) {
                    const element = timeList[key];
                    // console.log(element);
                    formatedList.push(element.slot_start_time);
                }
            }
            console.log('formatedList', formatedList);
            const slicedStartArr = formatedList.slice(formatedList.indexOf(slot_start), formatedList.indexOf(slot_end));
            const slicedEndArr = [formatedList[formatedList.indexOf(slot_start) + 1]];
            console.log('sliced start arr', slicedStartArr);
            console.log('sliced end arr', slicedEndArr);
            if (slicedStartArr && slicedEndArr) {
                $('#emptySlot').hide();
                $.each($('#form3StartSlot').children(), function(ind, item) {
                    const checkValue = slicedStartArr.includes($(item).val().trim());
                    if (checkValue) {
                        $(item).show();
                    }else {
                        $(item).hide();
                    }
                });
                $.each($('#form3EndSlot').children(), function(ind, item) {
                    if (slicedEndArr.includes($(item).val())) {
                        $(item).show();
                    }else {
                        $(item).hide();
                    }
                });
            } else {
                $('#slotSection').hide();
                $('#emptySlot').show();

            }


        }


        function setSlotTime(jsonData) {
            // check the minutes and set
            const startTime = moment().tz(currentTimeZone);
            const endTime = moment().tz(currentTimeZone);
            const formatedStartTime = startTime.add(1, 'hours');
            const formatedEndTime = endTime.add(2, 'hours');
            const startMinute = calculateMinutes(parseInt(formatedStartTime.format('mm')));
            const endMinute = calculateMinutes(parseInt(formatedEndTime.format('mm')));
            const startTimeString = `${formatedStartTime.format('h')}:${startMinute == 60 || startMinute == 0 ? '00': startMinute} ${formatedStartTime.format('mm a').substr(-2)}`;
            const endTimeString = `${formatedEndTime.format('h')}:${endMinute == 60 || endMinute == 0 ? '00': endMinute} ${formatedEndTime.format('mm a').substr(-2)}`;

            console.log('startTimeString', startTimeString);
            console.log('endTimeString', endTimeString);
            const startTimeArr = startTimeString.split(':')[0];
            const endTimeArr = endTimeString.split(':')[0];
            if (startTimeArr[0] > endTimeArr[0] && startTimeArr[1].substr(-2) == endTimeArr.substr(-2)) {
                $('#slotSection').hide();
                $('#emptySlot').hide();
            } else {

                $('#form3StartSlot').val(startTimeString);
                $('#form3EndSlot').val(endTimeString);
                hidePreviousTime(startTimeString, jsonData.slot_end);
            }
        }

        function showAllDropdownItems() {

            $.each($('#form3StartSlot').children(), function(ind, item) {
                if (startSlicedArr.includes($(item).val())) {
                    $(item).show();
                }

            });
            $.each($('#form3EndSlot').children(), function(ind, item) {
                if (endSlicedArr.includes($(item).val())) {
                    $(item).show();
                }

            });
        }
        // pre order

        function checkBeforeShowingPreOrderButton(selectedDay) {

            const now = moment().tz(currentTimeZone);
            now.weekday(daysArr.indexOf(finalArr[0])); // setting the first day of week
            const selecteDate = now.add(finalArr.indexOf(selectedDay), 'days');
            console.log('selected date', selecteDate.format('YYYY-MM-DD'));
            const daysAgo = selecteDate.fromNow(true);
            let pattern = /(days|day|seconds)/;
            console.log('from now', daysAgo);
            if (pattern.test(daysAgo)) {
                console.log('not outdated');
                // not outdated
                // get all the pre orders
                const url = '<?php echo base_url('home/getAllPreOrders') ?>';
                $.post(url, {
                    currentDate: selecteDate.format('YYYY-MM-DD')
                }, function(res) {
                    console.log('getAllPreOrders', res);
                    if (res != 'no') {

                        preORderResponseData = JSON.parse(res);
                        $('#preOrderNotAvailable').hide();
                        console.log('response', preORderResponseData);
                        // if selected date is greater then start_date then show selected date
                        const responseStartDate = moment(preORderResponseData.start_date, 'YYYY-MM-DD');

                        console.log('responseStartDate', responseStartDate);
                        const dateDiff = parseInt(moment.duration(selecteDate.diff(responseStartDate)).asDays());
                        console.log('date diff', dateDiff);
                        $('#pre_order_date_selected').attr('min', dateDiff > 0 ? moment().format('YYYY-MM-DD') : preORderResponseData.start_date);
                        $('#pre_order_date_selected').attr('max', preORderResponseData.end_date);
                        $('#pre_order_btn').show();
                        $('#pre_order_btn_section').show();
                    } else {
                        // $('#preOrderNotAvailable').show();
                        $('#pre_order_btn').hide();
                        $('#pre_order_btn_section').hide();
                        $('#pre_order_date_section').hide();
                        console.log('get start time',startSlicedArr[0]);
                    }

                }).fail(function(error) {
                    console.log(error);
                })
            } else {
                // outdated
                console.log('outdated');
                // $('#preOrderNotAvailable').show();
            }

        }
        $('#pre_order_btn').change(function(e) {
            e.preventDefault();
            const status = $('#pre_order_btn:checked').val();
            console.log('pre order button', status);
            preOrderStatus = status == 'on' ? 'ok' : 'no';
            console.log('preOrderStatus', preOrderStatus);
            // show the pre order date section
            if (status == 'on') {
                $('#pre_order_date_section').show();
            }

            // set pre order date for pickup
            const transportType = localStorage.getItem('transportType');
            console.log('transportType', transportType);
            if (transportType == 'pickup') {
                // uncheck all the available days
                $.each($('#availableDaysSection input[name="daySelected"]'), function(index, item) {
                    $(item).attr('checked', false);
                })
                // get all the pre orders
                const now = moment().tz(currentTimeZone);
                const url = '<?php echo base_url('home/getAllPreOrders') ?>';
                $.post(url, {
                    currentDate: now.format('YYYY-MM-DD')
                }, function(res) {
                    console.log('getAllPreOrders', res);
                    if (res != 'no') {

                        preORderResponseData = JSON.parse(res);
                        $('#preOrderDeliveryNotAvailable').hide();
                        console.log('response', preORderResponseData);
                        // if selected date is greater then start_date then show selected date
                        const responseStartDate = moment(preORderResponseData.start_date, 'YYYY-MM-DD');
                        console.log('responseStartDate', responseStartDate);
                        const dateDiff = parseInt(moment.duration(now.diff(responseStartDate)).asDays());
                        console.log('date diff', dateDiff);
                        $('#pre_order_date_selected').attr('min', dateDiff > 0 ? now.format('YYYY-MM-DD') : preORderResponseData.start_date);
                        $('#pre_order_date_selected').attr('max', preORderResponseData.end_date);
                        $('#pre_order_date_selectpre_order_btnpleaseed').data('status', preORderResponseData.status);
                    } else {
                        alert('No Pre Order is found');
                        $('#pre_order_date_section').hide();
                        $('.continueShopping').show();
                    }

                }).fail(function(error) {
                    console.log(error);
                })

            }
        });

        function hideTodayIfTimeIsOver(selectedDay, slotStart) {
            const now = moment().tz(currentTimeZone);
            const todayDayName = now.format('dddd').toLowerCase().slice(0, 3);

            console.log('todayDayName', todayDayName);
            console.log('selectedDay', selectedDay);
            let toDayIsSelected = false;
            if (todayDayName == selectedDay) {
                console.log('today is ' + selectedDay);
                toDayIsSelected = true;
                const startTime = moment().tz(currentTimeZone);
                const formatedStartTime = startTime.add(1, 'hours');
                const startMinute = calculateMinutes(parseInt(formatedStartTime.format('mm')));
                const startTimeString = `${formatedStartTime.format('h')}:${startMinute == 60 || startMinute == 0 ? '00': startMinute} ${formatedStartTime.format('mm a').substr(-2)}`;
                console.log(`startTimeString and slot start ${startTimeString} ${slotStart}`);
                // format the time as moment for the visibility of today
                const startTimeMoment = moment(startTimeString, 'HH:mm a');
                const endTimeMoment = moment(slotStart, 'HH:mm a');
                const difference = moment.duration(startTimeMoment.diff(endTimeMoment));
                console.log('difference', difference.asHours());
                if (difference.asHours() < 0) {

                    $('#availableDaysSection input[name="daySelected"]').each(function(index, item) {
                        if ($(this).val() == selectedDay) {
                            console.log('hide', $(this));
                            $(this).parent('.radioSectionInput').hide();
                        }
                    });
                }
            }

        }
        $('#pre_order_date_selected').change(function(e) {
            e.preventDefault();
            let selectedDay = $('#pre_order_date_selected').val();
            selectedDay = moment(selectedDay).tz(currentTimeZone).format('ddd').toLowerCase();
            startDoingOtherThings(selectedDay);
        });

        function startDoingOtherThings(selectedDay) {
            const now = moment().tz(currentTimeZone);
            console.log('selectedDay', selectedDay);
            const todayDayName = now.format('dddd').toLowerCase().slice(0, 3);

            console.log('todayDayName', todayDayName);
            console.log('selectedDay', selectedDay);
            checkBeforeShowingPreOrderButton(selectedDay);
            $('#slotSection').show();
            showAllDropdownItems();
            let toDayIsSelected = false;
            let finalStratTimeString = '';
            if (todayDayName == selectedDay) {
                console.log('today is selected');
                toDayIsSelected = true;
                const startTime = moment().tz(currentTimeZone);
                const formatedStartTime = startTime.add(1, 'hours');
                const startMinute = calculateMinutes(parseInt(formatedStartTime.format('mm')));
                const startTimeString = `${formatedStartTime.format('h')}:${startMinute == 60 || startMinute == 0 ? '00': startMinute} ${formatedStartTime.format('mm a').substr(-2)}`;
                console.log('startTimeString', startTimeString);
                finalStratTimeString = startTimeString;
            }
            console.log('finalStratTimeString', finalStratTimeString);

            const jStartTimeArr = jsonData.slot_start.split(':');
            const jStartMinute = parseInt(jStartTimeArr[1].substr(0, 2));
            const endMinute = jStartMinute + parseInt(jsonData.interval_in_minute);
            const formatedString = `${endMinute == 60 ? (parseInt(jStartTimeArr[0]) + 1): jStartTimeArr[0]}:${endMinute == 60 ? '00': endMinute} ${jStartTimeArr[1].substr(-2)}`;
            // console.log('end time', jStartMinute);
            console.log('startTime', jsonData.slot_start);
            console.log('endTime', formatedString);
            $('#form3StartSlot').val(toDayIsSelected ? finalStratTimeString : jsonData.slot_start);
            const finalEndTime = getEndTime(toDayIsSelected ? finalStratTimeString : jsonData.slot_start);
            console.log('finalEndTime', finalEndTime);
            hidePreviousTime(toDayIsSelected ? finalStratTimeString : jsonData.slot_start, jsonData.slot_end);
            $('#form3EndSlot').val(finalEndTime);


        }
        $('#availableDaysSection').change(function(e) {
            e.preventDefault();
            $('#pre_order_btn').prop('checked', false);
            const status = $('#pre_order_btn:checked').val();
            if (status == 'on') {
                $('#pre_order_date_section').show();
            } else {
                $('#pre_order_date_section').hide();
            }
            const selectedDay = $('input[name="daySelected"]:checked').val();
            console.log('selectedDay', selectedDay);
            startDoingOtherThings(selectedDay);
        })

    })
</script>
<script>
    function set_pops2(val1, val2, transportType = null) {
        $('#' + val1).hide();
        $('#' + val2).show();
        if (transportType) localStorage.setItem('transportType', transportType);

        $('.errorMessage').hide();
    }
    function set_pops21(val1, val2, val3) {
        $('#' + val1).hide();
        $('#' + val2).hide();
        $('#' + val3).show();
    }
    function set_pops22(val1) {
        $('#' + val1).hide();
        $('.errorMessage').hide();
    }
    function set_pops23(val1) {
        $('#' + val1).show();
    }

    function onDeliveryClicked() {
        $('.errorMessage').hide();
        $('#pre_order_delivery_date_section').hide();
        const zip = $('#zipcodecheck1').val();

        if (!zip) {
            alert('Please type a zip code');
            return;
        }
        set_pops('pops_1', 'pops_2', 'delivery');
        localStorage.setItem('user_zips', zip);
        console.log('zip value', zip);
        $.post("<?php echo base_url() . 'home/getVendorsBasedOnZipCode/'; ?>" + zip, function(data) {
            // console.log('all zip based stores', data);
            const allStores = JSON.parse(data);
            $('#storeList').children().remove();
            if (allStores.length > 0) {
                $('.continueShopping').show();
                $('#storeList').append(`
            <h4 class="modal-title">Yay! We deliver <span id="dzip"></span>.</h4>
            `);
                $.each(allStores, function(ind, item) {
                    $('#storeList').append(`
                <p style="text-align: left;">
                                <input value="${item.vendor_id}" class="storeType" type="radio" name="delivery">
                                <label for="${item.vendor_id}" style="background: #cc0101;color: #fff;padding: 3px 8px;margin-left: 5px;border-radius: 3px;">${item.name}
                                </label><span style='display: block;margin-left: 19px;margin-top: 5px;'>${item.city},${item.state},${item.country}</span>
                            </p>
                `);
                })
            } else {
                $('.continueShopping').hide();
                $('#storeList').append(`
            <h4 class="modal-title">Sorry no store is available</span>.</h4>
            `);
            }

        }).fail(error => {
            console.log('error', error);
        })
    }

    function set_pops(val1, val2) {
        var zip = $('#zipcodecheck1').val();

        split_string = zip.split(/(\d+)/);

        $.post("<?php echo base_url() . 'home/deliverycodesearch/'; ?>" + split_string[1], function(data) {

            if (data == 1) {
                $('#' + val2).show('fast');
                $('#' + val1).hide('fast');
                //$('#zipcodecheck').val()='';
                $('#zipcodeerror').hide('fast');
                $('#zipcodevalue').hide('fast');
                $('#try').hide('fast');
                $('#tryerror').hide('fast');
                $('#dzip').html(split_string[1]);
                $('.top-bar-right').load('<?php echo base_url(); ?>home/top_bar_right');
            } else {
                $('#' + val2).hide('fast');
                $('#' + val1).show('fast');
                $('#tryerror').show('fast');
                $('#zipcodeerror').show('fast');
                $('#zipcodevalue').show('fast');
                $('#zipcodevalue').html(split_string[1]);
            }
        });







    }



    function setPickupOrDelivery(storeId) {
        $.post("<?php echo base_url() . 'home/pickup/'; ?>" + storeId, function(data) {
            if (data == 1) {
                $('#saudaModal .modal').click();
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        });
    }

    $('.errorMessage').hide();

    function locationpick() {
        const selectedDay = $('input[name="daySelected"]:checked').val();
        const preOrderStatus = $('#pre_order_btn:checked').val();
        const preOrderDateValue = $('#pre_order_date_selected').val();
        console.log('preOrderStatus', preOrderStatus == 'on');
        console.log('preOrderDateValue', preOrderDateValue.length == 0);
        if (preOrderStatus == 'on' && preOrderDateValue.length == 0) {
            alert('please select pre order date');
            return;
        }
        if (!selectedDay && preOrderStatus != 'on') {
            alert('please select an available day');
            return;
        }
        var storeId = $('input[name=pickup]:checked').val();
        const daySelected = $('input[name="daySelected"]:checked').val();
        const startSlotValue = $('#form3StartSlot').val();
        const endSlotValue = $('#form3EndSlot').val();
        const getDayIndex = daysArr.indexOf(daySelected);
        const pickup_date = moment().tz(currentTimeZone).days(getDayIndex).format('YYYY-MM-DD');
        const pickup_slot = `${startSlotValue}-${endSlotValue}`;
        const max_order = jsonData.max_order;
        const pre_order_date_selected = $('#pre_order_date_selected').val();

        console.log('pickup date and slot', `${pickup_date} ${pickup_slot}`);
        localStorage.setItem('pickup_detail', JSON.stringify({
            pickup_date,
            pickup_slot,
            max_order
        }));
        // check max order in this time slot
        checkMaxOrderIsInTimeSlot().then(function(data) {

            console.log('getResult', data);
            if (data == false) {
                alert('maximum order limit in this slot exits');
                return;
            }
            // store data in php session
            $.post("<?php echo base_url() . 'home/setSessionData/'; ?>", {
                pickup_date,
                pickup_slot,
                pre_order_status: preOrderStatus == 'on' ? 'ok' : 'no',
                pre_order_date: preOrderStatus == 'on' ? pre_order_date_selected : null,
                keys: 'pickup_date,pickup_slot,pre_order_status,pre_order_date'
            }, function(data) {
                console.log('session response', data);
            });
            // some other work
            var productId = localStorage.getItem('selectedProductId');
            localStorage.setItem('selectedStoreId', storeId);
            if (storeId && storeId != "" && normalProcess) {
                setPickupOrDelivery(storeId);
            } else {

                $.post("<?php echo base_url() . 'home/checkProudctAvailability/'; ?>" + storeId + '/' + productId, function(data) {
                    $('.errorMessage').hide();
                    if (data != 1) {
                        $('.errorMessage').show();
                        return;
                    } else {
                        product_to_cart(productId, 'pro_order');

                    }
                });
            }
        });


    }
    $('.errorMessage').hide();

    function continueDeliveryShiping() {
        const productId = localStorage.getItem('selectedProductId');
        const storeId = $('input[name=delivery]:checked').val();
        const preORderStatus = $('#pre_order_delivery_checkbox_selected').is(':checked');
        const preOrderDateValue = $('#pre_order_delivery_date_selected').val();
        //alert(preOrderDateValue); break;
        console.log('preOrderDateValue', preOrderDateValue != '');
        console.log('preORderStatus', preORderStatus);
        if (preOrderDateValue == '' && preORderStatus) {
            alert('Please select a date');
            return;
        }

        console.log('store id', storeId);
        if (!storeId) {
            alert('Please select a store');
            return;
        }

        localStorage.setItem('selectedStoreId', storeId);
        // store data in php session
        $.post("<?php echo base_url() . 'home/setSessionData/'; ?>", {
            user_zips: localStorage.getItem('user_zips'),
            pre_order_status: preORderStatus ? 'ok' : 'no',
            pre_order_date: preORderStatus ? $('#pre_order_delivery_date_selected').val() : null,
            keys: 'user_zips,pre_order_date,pre_order_status'
        }, function(data) {
            console.log('session response', data);
        });

        // check prodocut is available
        $.post("<?php echo base_url() . 'home/checkProudctAvailability/'; ?>" + storeId + '/' + productId, function(data) {
            console.log('checkProudctAvailability', data);
            $('.errorMessage').hide();
            if (data != 1) {
                $('.errorMessage').show();
                return;
            } else {
                product_to_cart(productId, 'pro_order');

            }
        });
    }

    function set_pops1(val1, val2) {
        //$('#zipcodecheck').val()="";	
        $('#' + val2).hide();
        $('#' + val1).show();
        $('#tryerror').hide();
        $('#zipcodeerror').hide();
        //$('#zipcodecheck').val()='';

    }
</script>
<script>
    $(function() {

        var autocomplete;
        var geocoder;

        var input = document.getElementById('zipcodecheck1');
        var options = {
            componentRestrictions: {
                'country': ["us", "in"]
            },
            types: ['(regions)'] // (cities)
        };
        $("html").removeClass("pac-logo");

        autocomplete = new google.maps.places.Autocomplete(input, options);

        $('#go').click(function() {
            var location = autocomplete.getPlace();
            geocoder = new google.maps.Geocoder();
            console.log(location['geometry'])
            lat = location['geometry']['location'].lat();
            lng = location['geometry']['location'].lng();
            var latlng = new google.maps.LatLng(lat, lng);
            $("html").removeClass("pac-logo");

            // http://stackoverflow.com/a/5341468
            geocoder.geocode({
                'latLng': latlng
            }, function(results) {
                for (i = 0; i < results.length; i++) {
                    for (var j = 0; j < results[i].address_components.length; j++) {
                        for (var k = 0; k < results[i].address_components[j].types.length; k++) {
                            if (results[i].address_components[j].types[k] == "postal_code") {
                                zipcode = results[i].address_components[j].short_name;
                                $('span.zip').html(zipcode);
                                $("html").removeClass("pac-logo");
                            }
                        }
                    }
                }
            });

        });


    });



    function set_html(hide, show) {
        $('#' + show).show('fast');
        $('#' + hide).hide('fast');
    }
</script>
<style>
    .mt-2 {
        margin-top: 1rem;
    }

    #saudaModal .modal-dialog {
        max-width: 800px;
        width: 100%;
        text-align: center;
        margin: 80px auto;
    }

    #saudaModal .modal-content {
        -webkit-box-shadow: none;
        box-shadow: none;
        padding: 20px 32px 40px;
        border-radius: 0;
        border: 0;
        color: #403e3b;
    }

    #saudaModal .modal-header {
        padding: 0;
        border-bottom: 0;
    }

    #saudaModal .close {
        font-size: 26px;
        font-weight: normal;
        color: #403e3b;
        text-shadow: none;
        filter: alpha(opacity=50);
        opacity: .5;
    }

    #saudaModal .modal-header img {
        display: block;
        margin: 10px auto 0px;
        height: 35px;
    }

    #saudaModal .modal-body h4 {
        font-size: 26px;
        text-align: center;
    }

    #saudaModal .modal-content p {
        font-size: 16px;
        margin: 15px 0;
    }

    #saudaModal .form-group {
        text-align: center;
        max-width: 480px;
        margin: 15px auto;
    }

    #saudaModal .form-control {
        border: 1px solid #d5d4d0;
        border-radius: 5px;
        max-width: 300px;
        text-align: center;
        color: #403e3b;
        float: left;
    }

    span.zip {
        margin: 0;
        display: block;
        text-transform: uppercase;
        font-size: 0.9em;
        color: #999;
    }

    #saudaModal .btn-primary {
        max-width: 150px;
        float: left;
        border-radius: 5px;
        width: 150px;
        margin-left: 15px;
        padding: 10px;
        background: #cc0101;
        border: 0;
    }

    .auth-flow__auth {
        margin-top: 0px;
    }

    .try_zip a {
        font-size: 13px;
        color: #cc0101;
    }

    .auth-flow__auth {
        margin-top: 0px;
    }

    .auth-flow__auth a {
        color: #cc0101;
    }

    #saudaModal .btn-primary.sign_mail {
        float: none;
        width: 300px;
        display: block;
        margin: 20px auto;
        max-width: inherit;
        font-weight: bold;
        font-size: 16px;
    }

    .ma_wi {
        max-width: 300px;
        margin: 0px auto;
    }

    .brder {
        border-bottom: 1px solid #ccc;
        width: 76px;
        background: #ccc;
        height: 1px;
        position: absolute;
        left: 0px;
        top: 10px;
    }

    .brder.rts {
        left: auto;
        right: 0;
    }

    .ma_wi145 {
        max-width: 340px;
        margin: 20px auto 0px;
    }

    .log-face {
        padding-left: 0;
    }

    .log-face a {
        border: 1px solid #3b5998;
        padding: 8px 13px;
        display: block;
        width: 100%;
        border-radius: 3px;
        color: #3b5998;
    }

    .ma_wi145 .log-face a {
        padding: 8px 25px;
    }

    .log-face a i {
        color: #3b5998;
        font-size: 24px;
        float: left;
        margin-right: 10px;
    }

    .log-face a span {
        float: left;
    }

    .log-goo {
        padding-right: 0;
    }

    .ma_wi145 .log-goo a {
        padding: 8px 30px;
    }

    .log-goo a img {
        width: 25px;
        margin-right: 10px;
        float: left;
    }

    .log-goo a span {
        float: left;
    }

    .ma_wi1 {
        max-width: 300px;
        margin: 20px auto 50px;
    }

    .pac-container {
        background-color: #fff;
        position: absolute !important;
        z-index: 1000;
        border-radius: 2px;
        border-top: 1px solid #d9d9d9;
        font-family: Arial, sans-serif;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        overflow: hidden;
    }

    div.pac-container {
        z-index: 1050 !important;
    }

    div.pac-container {
        border-radius: 4px;
        font-family: 'Poppins', sans-serif !important;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 10px 0px, rgba(0, 0, 0, 0.23) 0px 3px 10px 0px !important;
    }

    .pac-logo::after {
        background-image: none !important;
    }

    .pac-item-query {
        font-size: 16px !important;
        color: rgb(66, 66, 66) !important;
        font-weight: bold;
        display: block;
    }

    .pac-item {
        padding: 8px !important;
        border-top: 1px solid #e6e6e6;
        font-size: 14px !important;
        color: rgb(117, 117, 117) !important;
        padding-left: 35px !important;
        line-height: 22px !important;
    }

    .pac-icon {
        position: absolute;
        left: 10px;
    }

    .box_p {
        border: 1px solid #ccc;
        margin-top: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .box_img {
        padding: 10px;
    }

    #saudaModal .box_p p {
        margin: 0;
        background: #eee;
        padding: 5px;
    }

    .orderslt {
        display: flex;
        align-items: center;
        justify-content: space-around;
        align-content: center;
        column-gap: 10px;
        margin-top: 15px;
    }

    .orderslt .form-group {
        margin: 0 auto !important;
    }

    div#availableDaysSection {
        display: flex;
    }

    #availableDaysSection .col-lg-1.radioSectionInput {
        display: flex;
        align-items: flex-start;
        justify-content: space-around;
        column-gap: 3px;
        align-content: center;
        margin-right: 18px;
    }

    input#pre_order_btn {
        margin-top: 18px;
    }

    @media (max-width: 576px) {
        #saudaModal .modal-content {
            padding: 20px 0px 40px;
        }

        div#availableDaysSection {
            display: flex;
            flex-wrap: wrap;
        }


    }
</style>