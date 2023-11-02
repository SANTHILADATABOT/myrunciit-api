<?php
if ($this->session->userdata('user_login') == "yes") {
    $user       = $this->session->userdata('user_id');
    $ship_address  = $this->db->get_where('shipping_address', array('user_id' => $user))->result_array();
?>
    <div class="row">
        <div class="col-md-12 hidden">
            <input type="radio" name="type" class="_2haq-9" id="delivery" value="" />Delivery
            <input type="radio" name="type" class="_2haq-9" id="delivery" value="" />Pickup
        </div>
        <div class="col-md-12">
            <div class="_2bai clearfix">
                <?php
                $user_shipping_loc=[];
                $shop_address=["latitude"=>"","longitude"=>""];
                $default_address_id='';

                foreach ($ship_address as $sa) {
                    $sa_id=$sa['id'];
                    $shipping_address=$sa['address'].','.$sa['city'].','.$sa['state'].','.$sa['country'].','.$sa['zip_code'];
                    $user_shipping_loc[]="find_shipp_Coordinates('".$sa['id']."','".$shipping_address."');";
                    $this->db->select('*');
                    $this->db->from('vendor');
                    $this->db->where('status', 'approved');
                    $this->db->where('delivery', 'yes');
                    $this->db->where('vendor_id', $this->session->userdata('vendorid'));
                    // $this->db->like('delivery_zipcode', $sa['zip_code']);
                    $allStores = $this->db->get()->result_array();
                    
                ?>
                    <div data-useraddress="<?php echo $sa['address']; ?>,<?php echo $sa['city']; ?>,<?php echo $sa['state']; ?>,<?php echo str_replace('-','', $sa['country']); ?>" data-storeaddress="<?php echo $allStores[0]['address1']; ?>,<?php echo $allStores[0]['city']; ?>,<?php echo $allStores[0]['state']; ?>,<?php echo $allStores[0]['country']; ?>" data-storeid="<?php echo $allStores[0]['vendor_id']; ?>"  data-storelat="<?php echo $allStores[0]['latitude']; ?>" data-userlat="<?php echo $sa['latitude']; ?>" data-userlng="<?php echo $sa['longitude']; ?>" data-storelng="<?php echo $allStores[0]['longitude']; ?>">
                        <input type="radio" name="addreessList" onclick=" get_location_distance('<?php echo $sa['id']; ?>',address_shipping_loc<?php echo $sa['id']; ?>.value,address_shipping_lat<?php echo $sa['id']; ?>.value,address_shipping_lng<?php echo $sa['id']; ?>.value,'<?php echo $allStores[0]['latitude']; ?>','<?php echo $allStores[0]['longitude']; ?>');" class="_2haq-9" <?php if ($sa['set_default'] == 1) {$default_address_id=$sa['id'];$shop_address["latitude"]=$allStores[0]['latitude'];$shop_address["longitude"]=$allStores[0]['longitude']; ?> checked="checked" <?php } if (empty($allStores)) { echo "disabled"; } ?> id="addr<?php echo $sa['id']; ?>" value="<?php echo $sa['id']; ?>" />
                        <label for="addr<?php echo $sa['id']; ?>" class="_1tkDFt clearfix">
                            <div class="_6ATDKp"></div>
                            <div class="_2o59RR _27CukN"><strong><?php echo $sa['name']; ?></strong> <?php echo $sa['address'] . ', ' . $sa['zip_code'] . ', ' . $sa['country'] . ', ' . $sa['state'] . ', ' . $sa['mobile']; ?><span id="address_shipping_status<?php echo $sa['id']; ?>"></span></div>
                        </label>
                        <input type='hidden' id='address_shipping_loc<?php echo $sa['id']; ?>' value='' />
                        <input type='hidden' id='address_shipping_lat<?php echo $sa['id']; ?>' value='' />
                        <input type='hidden' id='address_shipping_lng<?php echo $sa['id']; ?>' value='' />
                        <?php if ($sa['set_default'] != 1) { ?>
                        <span class="open-AddBookDialog colds colds_<?php echo $sa['id']; ?>" data-toggle="modal" data-target="#addaddress<?php echo $sa['id']; ?>" data-id="<?php echo $sa['id']; ?>"><i class="fa fa-pencil"></i></span>
                        <span class="open-AddBookDialog1" onclick="deleteshippingaddress('<?php echo $sa['id']; ?>');" data-id="<?php echo $sa['id']; ?>"><i class="fa fa-trash"></i></span>
                        <?php } ?>
                    </div>
                    <div id="addaddress<?php echo $sa_id; ?>" class="modal addresss fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-border">
                                    <div class="close-now clearfix">
                                        <button type="button" data-dismiss="modal" class="close1">×</button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="clearfix pope">
                                            <?php if ($this->session->userdata('pickup') == "") { ?>
                                                <h3 class="insuff">Edit Delivery Address</h3>
                                            <?php } else { ?>
                                                <h3 class="insuff">Edit Billing Address</h3>
                                            <?php } ?>
                                            <div class="col-lg-12 col-xs-12 signup-right" id="signup_form1">
                                                <div class="row  br0" style="padding: 0;">
                                                    <div class="title">

                                                        <div class="option">

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input class="form-control" type="text" name="s_name" id="sb_name<?php echo $sa_id; ?>" value="<?php echo $sa['name']; ?>" placeholder="Name">
                                                        </div>

                                                        <div class="form-group ">
                                                            <label>Phone No</label>
                                                            <input class="form-control" type="text" name="s_mobile" id="sb_mobile<?php echo $sa_id; ?>" value="<?php echo $sa['mobile']; ?>" placeholder="Phone No">
                                                        </div>
                                                        <div class="form-group ">
                                                            <label>Email</label>
                                                            <input class="form-control" type="text" name="s_email" id="sb_email<?php echo $sa_id; ?>" value="<?php echo $sa['email']; ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Address1</label>
                                                            <input class="form-control" type="hidden" name="cou_shrt" id="country_code2" value="">
                                                            <input class="form-control required" type="text" name="s_address" id="sb_address1<?php echo $sa_id; ?>" value="<?php echo $sa['address']; ?>" placeholder="Address1">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Apartment</label>
                                                            <input class="form-control" type="text" name="s_address1" id="sb_address2<?php echo $sa_id; ?>" value="<?php echo $sa['address1']; ?>" placeholder="APT/SUITE # ">
                                                        </div>
                                                        <div class="form-group  ">
                                                            <label>Zip Code</label>
                                                            <input class="form-control required" type="text" name="s_zipcode" id="sb_zip_code<?php echo $sa_id; ?>" value="<?php echo $sa['zip_code']; ?>" maxlength="6" placeholder="Zip Code">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>City</label>
                                                            <input id="sb_cities<?php echo $sa_id; ?>" name="cities1" class="form-control required" type="text" value="<?php echo $sa['city']; ?>" placeholder="city">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>State</label>
                                                            <?php echo $this->db;?>
                                                            <?php $state_set = $this->db->get('state')->result_array(); ?>
                                                            <select class="form-control required" name="state1" id="sb_state<?php echo $sa_id; ?>" data-toggle="tooltip" title="<?php echo translate('state'); ?>">
                                                                <option value="">Select State</option>
                                                                <?php foreach ($state_set as $state) { ?>
                                                                    <option value="<?php echo $state['name']; ?>" <?php if($sa['state']==$state['name']){ echo 'selected';  } ?> ><?php echo $state['name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label>Country</label>
                                                            <input id="sb_country<?php echo $sa_id; ?>" name="country1" class="form-control required" type="text" value="<?php echo $sa['country']; ?>" placeholder="Country" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <span class="btn btn-theme-sm btn-block  btn-sm enterer" style="background: #014282;color: #fff;border-radius: 10px;" onclick="shippingaddr_add_update('<?php echo $sa_id; ?>',sb_name<?php echo $sa_id; ?>.value,sb_mobile<?php echo $sa_id; ?>.value,sb_email<?php echo $sa_id; ?>.value,sb_address1<?php echo $sa_id; ?>.value,sb_address2<?php echo $sa_id; ?>.value,sb_zip_code<?php echo $sa_id; ?>.value,sb_cities<?php echo $sa_id; ?>.value,sb_state<?php echo $sa_id; ?>.value,sb_country<?php echo $sa_id; ?>.value);">Edit</span>
                                                    </div>
                                                </div>

                                                <!-- </form> -->
                                            </div>




                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php }  ?>

                <div id="addaddress" class="modal addresss fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-border">
                                <div class="close-now clearfix">
                                    <button type="button" data-dismiss="modal" class="close1">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="pope clearfix">
                                        <?php if ($this->session->userdata('pickup') == "") { ?>
                                            <h3 class="insuff">Add New Delivery Address</h3>
                                        <?php } else { ?>
                                            <h3 class="insuff">Add Billing Address</h3>
                                        <?php } ?>
                                        <div class="col-lg-12 col-xs-12" id="signup_form">
                                       <div class="row br0" style="padding: 0;">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input class="form-control required" type="text" name="name" id="sb_name" value="" placeholder="Name">
                                                    </div>
                                                    <div class="clearfix">
                                                        <div class="form-group w33">
                                                            <label>Phone No</label>
                                                            <input class="form-control required" type="text" name="mobile" id="sb_mobile" placeholder="Phone No">
                                                        </div>
                                                        <div class="form-group w67 pl15">
                                                            <label>Email</label>
                                                            <input class="form-control required" type="text" name="email" id="sb_email" placeholder="Email">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Address1</label>
                                                        <input class="form-control" type="hidden" name="cou_shrt1" id="country_code" value="">
                                                        <input class="form-control required" type="text" name="street_address" id="sb_address1" placeholder="Address1">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Apartment</label>
                                                        <input class="form-control" type="text" name="street_address2" id="sb_address2" placeholder="APT/SUITE #  ">
                                                    </div>



                                                    <div class="form-group">

                                                        <label>Zip Code</label>
                                                        <input class="form-control required" type="text" name="zip_code" id="sb_zip_code" maxlength="6" placeholder="Zip Code">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>City</label>
                                                        <input id="sb_cities" name="cities" class="form-control required" type="text" value="" placeholder="City">


                                                    </div>
                                                    <div class="form-group">
                                                        <label>State</label>
                                                        <?php $state_set = $this->db->get('state')->result_array(); ?>
                                                        <select class="form-control required" name="state" id="sb_state" data-toggle="tooltip" title="<?php echo translate('state'); ?>">
                                                            <option value="">Select State</option>
                                                            <?php foreach ($state_set as $state) { ?>
                                                                <option value="<?php echo $state['name']; ?>"><?php echo $state['name']; ?></option>
                                                            <?php } ?>
                                                        </select>


                                                    </div>
                                                    <div class="form-group ">
                                                        <label>Country</label>
                                                        <input id="sb_country" name="country" class="form-control required" type="text" value="Malaysia" placeholder="Country" readonly>


                                                    </div>





                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <span class="btn btn-theme-sm btn-block  btn-sm enterer" style="background: #014282;color: #fff;border-radius: 10px;" onclick="shippingaddr_add_update('',sb_name.value,sb_mobile.value,sb_email.value,sb_address1.value,sb_address2.value,sb_zip_code.value,sb_cities.value,sb_state.value,sb_country.value);">
                                                        Add
                                                    </span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <?php //echo $this->session->userdata('pickup'); 
                //echo $this->session->userdata('user_zips');
                ?>
                <p class="colds_1" data-toggle="modal" data-target="#addaddress" style="cursor: pointer;padding-top: 15px;display: inline-block;">+ Add Address</p>
                <div class="clearfix"></div>

                <?php if ($this->crud_model->get_type_name_by_id('general_settings', '108', 'value') == 'ok') { ?>
                    <h2>Pickup slots</h2>
                    <div class="col-md-12">
                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('Delivery Date'); ?></label>
                            <div class="col-sm-6">
                                <?php echo $this->crud_model->select_html_new(); ?>
                            </div>
                        </div>
                        <div class="form-group demo-chosen-select" id="slot_time1" style="display:none;">
                            <label class="col-sm-4 control-label" for="demo-hor-3"><?php echo translate('Delivery Time'); ?></label>
                            <div class="col-sm-6" id="slot_time">
                            </div>
                        </div>
                        <div class="col-md-4 hidden"><label style="color:#f58633">Delivery Date</label>
                            <div class="form-group">
                                <?php ?><select class="form-control" name="del_city1">
                                    <option value="">---Select Delivery Date---</option>
                                    <?php
                                    $date = strtotime(date("Y/m/d"));
                                    $NewDate = strtotime(Date('y-m-d', strtotime('+6 days')));
                                    $this->db->where('f_date >=', $date);
                                    $this->db->where('f_date <=', $NewDate);
                                    $del_city = $this->db->get('del_slot')->result_array();
                                    foreach ($del_city as $dcity) { ?>
                                        <option value="<?php echo $dcity['del_slot_id']; ?>"><?php echo date('Y-m-d', $dcity['f_date']); ?></option>
                                    <?php } ?>

                                </select><?php   ?>

                            </div>
                        </div>

                        <div class="col-md-4 hidden"><label style="color:#f58633">Delivery Time Slot</label>
                            <div class="form-group" id="state1">
                                <?php ?><select class="form-control" name="slot">
                                    <option value="">---Select Delivery slot---</option>

                                    <?php $del_city = $this->db->get('del_slot_time')->result_array();
                                    foreach ($del_city as $dcity) {
                                        date_default_timezone_set('Asia/Calcutta');
                                        $expire = strtotime(date("H:i", strtotime('+1 hour')));
                                        $pic = strtotime($dcity['f_time']);
                                        $this->db->where('del_slot_id', $dcity['del_slot_id']);
                                        $del = $this->db->get('del_slot')->row()->f_date;

                                        $this->db->where('pickup_slot', $dcity['del_slot_time_id']);
                                        $cou = $this->db->get('sale')->num_rows();
                                        if ($cou < $dcity['slot']) {
                                            if ($date == $del) {
                                                if ($expire <= $pic) {
                                    ?>
                                                    <option value="<?php echo $dcity['del_slot_time_id']; ?>"><?php echo date('g:i a', strtotime($dcity['f_time'])) . '-' . date('g:i a', strtotime($dcity['t_time'])) ?></option>
                                                <?php }
                                            } elseif ($date <= $del) { ?>
                                                <option value="<?php echo $dcity['del_slot_time_id']; ?>"><?php echo date('g:i a', strtotime($dcity['f_time'])) . '-' . date('g:i a', strtotime($dcity['t_time'])) ?></option>
                                        <?php
                                            }
                                        } ?>
                                    <?php } ?>


                                </select><?php ?>

                            </div>
                        </div>

                    </div>
                <?php } ?>



            </div>
        </div>
        <div class="col-md-12">
            
            <span class="btn btn-theme-dark" id="next_hid" onclick="<?php if ($this->session->userdata('user_zips') != "") { ?> get_quotation(); <?php } else { ?> load_payments();  <?php }?> ">
                <?php echo translate('next'); ?>
            </span>
        </div>
    </div>
    <style>
#pop{
 position:absolute;
 left:50%;
 top:50%;
 transform:translate(-50%,-50%);
}





/* The Modal (background) */
.modal_error {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content_error {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  border: 1px solid #888;
  width: 100%;
  height: 100%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
    color: black;
  /* color: #aaa; */
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}



</style>

<div id="myModalerror" class="modal_error" role="dialog" style="margin-top: 5%;">
    <div class="modal-dialog">
        <div class="modal-content_error">
            <!--<span class="close">&times;</span>-->
                <h3 style="color:#e12c2e;"><center>You're Currently Shopping at</center></h3>
                <center><img src="<?php echo base_url(); ?>/template/front/img/mricon.png" width="80px" /></center>
                <center><h4 style="text-align: center;">Oops! We don't serve in this area.</h4></center>
                <center><h5 style="text-align: center;color:#e12c2e" onclick="spaned()">Please try a different zip code</h5></center>   
        </div>
    </div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
    // function find_shipp_Coordinates(id,address) {
    //     $("#address_shipping_status"+id).html("<span style='color: #cc0101;margin:0px 10px'>(Delivery not available)</span>");
    //     $("#addr"+id).attr("disabled","true");
    //     $("#address_shipping_loc"+id).val('');$("#address_shipping_lat"+id).val('');$("#address_shipping_lng"+id).val('');
    //     var apiUrl = "https://graphhopper.com/api/1/geocode?key=5e81377e-fff2-42a6-aab9-2684af5574c3&q="+encodeURIComponent(address);
    //     $.ajax({url: apiUrl,type: 'GET',dataType: 'json',
    //         success: function (data) {
    //             if (data.hits && data.hits.length > 0) {
    //                 var latitude = data.hits[0].point.lat;
    //                 var longitude = data.hits[0].point.lng;
    //                 $("#address_shipping_status"+id).html("<span style='color: #8dc43c;margin:0px 10px'>(Delivery available)</span>");
    //                 $("#addr"+id).removeAttr("disabled");
    //                 $("#address_shipping_loc"+id).val(address);
    //                 $("#address_shipping_lat"+id).val(latitude);
    //                 $("#address_shipping_lng"+id).val(longitude);
    //             }
    //         }
    //     });
    // }
    function shippingaddr_add_update(id,sb_name,sb_mobile,sb_email,sb_address1,sb_address2,sb_zip_code,sb_cities,sb_state,sb_country)
    {
        if(sb_name && sb_mobile && sb_email && sb_address1 && sb_zip_code && sb_cities && sb_state && sb_country)
        {
            var mobile_regex = /^[0-9]{10,11}$/;
            var email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            var zip_regex = /^[0-9]+$/;
            if((!mobile_regex.test(sb_mobile)) || (!email_regex.test(sb_email)) || (!zip_regex.test(sb_zip_code)))
            {
                if(!mobile_regex.test(sb_mobile)){alert("Enter valid phone no");$("#sb_mobile"+id).focus();}
                else if(!email_regex.test(sb_email)){alert("Enter valid Email");$("#sb_email"+id).focus();}
                else{alert("Enter valid Zipcode");$("#sb_zip_code"+id).focus();}
            }
            else
            {
            var address=sb_address1+","+sb_address2+","+sb_cities+","+sb_state+","+sb_country+","+sb_zip_code;
            var apiUrl="https://api.opencagedata.com/geocode/v1/json?key=e3d089afaf5247708550efcf8a4a27f4&q="+encodeURIComponent(address)+"&pretty=1&no_annotations=1";
            $.ajax({url: apiUrl,type: 'GET',dataType: 'json',
                success: function (data) {
                    if (data.results && data.results.length > 0) {
                        var latitude=data.results[0].geometry.lat;
                        var longitude=data.results[0].geometry.lng;
                        var lat_val = latitude.toFixed(6);
                        var log_val = longitude.toFixed(6);
                        if(id==""){
                            var data1={name:sb_name,mobile:sb_mobile,email:sb_email,latitude:lat_val,longitude:log_val,street_address:sb_address1,street_address2:sb_address2,zip_code:sb_zip_code,cities:sb_cities,state:sb_state,country:sb_country};
                            $.ajax({url: "<?php echo base_url(); ?>index.php/home/login/add_address/",type: 'POST',data:data1,
                                success: function (data) {
                                    
                                        $("#addaddress").modal('hide');
                                        $('.modal-backdrop').hide();
                                        swal("","Address added successfully","success");
                                        load_address_form();
                                   
                                }
                            });
                        }else{
                            var data1={bookId:id,s_name:sb_name,s_mobile:sb_mobile,s_email:sb_email,s_latitude:latitude,s_longitude:longitude,s_address:sb_address1,s_address1:sb_address2,s_zipcode:sb_zip_code,cities1:sb_cities,state1:sb_state,country1:sb_country};
                            $.ajax({url: "<?php echo base_url(); ?>index.php/home/shippingaddressupdate/",type: 'POST',data:data1,
                                success: function (data) {
                                    
                                        $("#addaddress"+id).modal('hide');
                                        $('.modal-backdrop').hide();
                                        swal("","Address Updated successfully","success");
                                        load_address_form();
                                    
                                }
                            });
                        }
                    }else{
                        alert("Enter valid location");
                    }
                },
                error: function (error) {
                    alert("Error finding location");
                }
            });
        }
        }
        else
        {
            alert("Enter all field");
            if(!sb_name){$("#sb_name"+id).focus();}
            else if(!sb_mobile){$("#sb_mobile"+id).focus();}
            else if(!sb_email){$("#sb_email"+id).focus();}
            else if(!sb_address1){$("#sb_address1"+id).focus();}            
            else if(!sb_zip_code){$("#sb_zip_code"+id).focus();}
            else if(!sb_cities){$("#sb_cities"+id).focus();}
            else if(!sb_state){$("#sb_state"+id).focus();}
            else if(!sb_country){$("#sb_country"+id).focus();}
        }
    }
    function find_shipp_Coordinates(id,address) {
        $("#address_shipping_status"+id).html("<span style='color: #cc0101;margin:0px 10px'>(Invalid Address)</span>");
        $("#addr"+id).attr("disabled","true");
        $("#address_shipping_loc"+id).val('');$("#address_shipping_lat"+id).val('');$("#address_shipping_lng"+id).val('');
        var apiUrl="https://api.opencagedata.com/geocode/v1/json?key=e3d089afaf5247708550efcf8a4a27f4&q="+encodeURIComponent(address)+"&pretty=1&no_annotations=1";
        $.ajax({url: apiUrl,type: 'GET',dataType: 'json',
            success: function (data) {
                if (data.results && data.results.length > 0) {
                    $("#address_shipping_loc"+id).val(address);
                    var lat=data.results[0].geometry.lat;var lng=data.results[0].geometry.lng;
                    $("#address_shipping_lat"+id).val(lat);
                    $("#address_shipping_lng"+id).val(lng);
                    if(id=='<?php echo $default_address_id; ?>')
                    {get_location_distance(id,address,lat,lng,'<?php echo $shop_address["latitude"]; ?>','<?php echo $shop_address["longitude"]; ?>');}
                    else
                    {
                        var last_lat='<?php echo $shop_address["latitude"]; ?>',last_long='<?php echo $shop_address["longitude"]; ?>';
                        if((lat!="") && (lng!="") && (last_lat!="") && (last_long!=""))
                        {
                            $.ajax({url: "https://graphhopper.com/api/1/route?point="+lat+","+lng+"&point="+last_lat+","+last_long+"&vehicle=car&debug=true&key=5e81377e-fff2-42a6-aab9-2684af5574c3&type=json",type: "GET",
                                success: function(data) {
                                    var dist_time=[];
                                    data["paths"].forEach(function (item) {
                                        if(item["distance"]!="")
                                        {
                                            var totalDist=parseFloat(item["distance"])/1000;
                                            if(totalDist>0 && totalDist<=50){dist_time.push(totalDist);}
                                        }
                                    });
                                    if (dist_time.length > 0) {
                                        get_delivery_estimation(address,lat,lng);
                                        $("#address_shipping_status"+id).html("<span style='color: #8dc43c;margin:0px 10px'>(Delivery available)</span>");
                                        $("#addr"+id).removeAttr("disabled");
                                    }
                                    else {show_next_hid_popup1(id);}
                                },
                                error: function (error) {show_next_hid_popup1(id);}
                            });
                        } else {show_next_hid_popup1(id);}
                    }
                }
            }
        });
    }
    function get_location_distance(id,address,first_lat,first_long,last_lat,last_long)
    {
        if((first_lat!="") && (first_long!="") && (last_lat!="") && (last_long!=""))
        {
            $.ajax({url: "https://graphhopper.com/api/1/route?point="+first_lat+","+first_long+"&point="+last_lat+","+last_long+"&vehicle=car&debug=true&key=5e81377e-fff2-42a6-aab9-2684af5574c3&type=json",type: "GET",
                success: function(data) {
                    var dist_time=[];
                    data["paths"].forEach(function (item) {
                        if(item["distance"]!="")
                        {
                            var totalDist=parseFloat(item["distance"])/1000;
                            if(totalDist>0 && totalDist<=50){dist_time.push(totalDist);}
                        }
                    });
                    if (dist_time.length > 0) {
                        get_delivery_estimation(address,first_lat,first_long);
                        document.getElementById("payment-option-div").style.display="block";
                        $('#next_hid').show();
                        $("#address_shipping_status"+id).html("<span style='color: #8dc43c;margin:0px 10px'>(Delivery available)</span>");
                        $("#addr"+id).removeAttr("disabled");
                    } else {
                        show_next_hid_popup(id);
                    }
                },
                error: function (error) {
                    show_next_hid_popup(id);
                }
            });
        } else {
            show_next_hid_popup(id);
        }
    }
    function show_next_hid_popup(id)
    {
        $('#myModalerror').show();
        document.getElementById("payment-option-div").style.display="none";
        $('#next_hid').hide();
        $("#address_shipping_status"+id).html("<span style='color: #cc0101;margin:0px 10px'>(Delivery not available)</span>");
        $("#addr"+id).attr("disabled","true");
        $("#address_shipping_loc"+id).val('');$("#address_shipping_lat"+id).val('');$("#address_shipping_lng"+id).val('');
    }
    function show_next_hid_popup1(id)
    {
        $("#address_shipping_status"+id).html("<span style='color: #cc0101;margin:0px 10px'>(Delivery not available)</span>");
        $("#addr"+id).attr("disabled","true");
        $("#address_shipping_loc"+id).val('');$("#address_shipping_lat"+id).val('');$("#address_shipping_lng"+id).val('');
    }
    $(document).ready(function(){
        $('#next_hid').hide();
        <?php foreach($user_shipping_loc as $user_shipping_loc1){echo $user_shipping_loc1;} ?>
    });

    function spaned() {
                $('#myModalerror').hide();
                $('#next_hid').hide();
            }
            function Distance(lat1, lon1, lat2, lon2)
            {
                
               
                // Radius of the Earth in kilometers
                const earthRadius = 6371;
                // Convert latitude and longitude from degrees to radians
                const lat1Rad = toRadians(lat1);
                const lon1Rad = toRadians(lon1);
                const lat2Rad = toRadians(lat2);
                const lon2Rad = toRadians(lon2);

                // Haversine formula
                const dLat = lat2Rad - lat1Rad;
                const dLon = lon2Rad - lon1Rad;
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(lat1Rad) * Math.cos(lat2Rad) *
                        Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                const distance = earthRadius * c;

            // return distance;
            const formattedDistance = distance.toFixed(0);
         
            if (formattedDistance > 15) { 
               // console.log(formattedDistance);
              //  alert("Oops!", "We Dont Serve Your Zipcode Yet!", "warning");
              $('#myModalerror').show();
           
            
              //  swal("Oops!", "We Dont Serve Your Zipcode Yet!", "warning");
              //  console.log(formattedDistance);
                $('#next_hid').hide();
            } else {
                console.log(formattedDistance);
                $('#next_hid').show();
            }
       
            // console.log();
            }
            function toRadians(degrees) {
                return degrees * (Math.PI / 180);
            }

        function get_delivery_estimation(loc,lat,lng)
        {
            $("#lalamove_res").val('');
            $.ajax({url: "<?php echo base_url(); ?>index.php/home/getQuotation_charge",type: 'POST',data:{loc:loc,lat:lat,lng:lng},
                success: function (data) {
                    var d1=JSON.parse(data);
                    $("#priceBreakDownTotal").html(d1['display']).fadeIn();
                    
                    var total1=parseFloat($("#total").html().toUpperCase().replace('RM', ''));
                    var total_1=(!isNaN(total1))?total1:0.0;
                    var tax1=parseFloat($("#tax").html().toUpperCase().replace('RM', ''));
                    var tax_1=(!isNaN(tax1))?tax1:0.0;
                    // var disco1=parseFloat($("#disco").html().toUpperCase().replace('RM', ''));
                    var disco1=parseFloat($("#total_dis").val().toUpperCase().replace('RM', ''));
                    var disco_1=(!isNaN(disco1))?disco1:0.0;
                    var delivery1=parseFloat(d1['value']);
                    var delivery_1=(!isNaN(delivery1))?delivery1:0.0;
                    var grand_1=(total_1+tax_1-disco_1+delivery_1);
                    $("#grand").html("<?php echo currency(); ?>"+(grand_1.toFixed(2))).fadeIn();
                    $("#lalamove_res").val(d1['result']);
                }
            });
        }
        function other() {
            $('.demo-chosen-select').chosen();
            $('.demo-cs-multiselect').chosen({
                width: '100%'
            });
            $('#slot_time1').show('slow');
        }

        function get_slot(id, now) {
            $('#slot_time1').show('slow');
            ajax_load(base_url + 'index.php/home/cart_checkout/get_slot_time/' + id, 'slot_time', 'other');
        }
        // $(document).on("click", ".open-AddBookDialog", function() {
        //     var myBookId = $(this).data('id');
        //     $(".modal-body #bookId").val(myBookId);
        //     $.post("<?php echo base_url(); ?>index.php/home/shippingaddress/" + myBookId, function(data) {
        //         var address = data.split('^^');
        //         var count1 = address[5].split('-');
        //         $("#s_name").val(address[0]);
        //         $("#autocomplete2").val(address[1]);
        //         $("#s_address1").val(address[2]);
        //         $("#s_email").val(address[3]);
        //         $("#locality2").val(address[4]);
        //         $("#country2").val(count1[0]);
        //         $("#country_code2").val(count1[1]);
        //         $("#administrative_area_level_12").val(address[6]);
        //         $('#administrative_area_level_123 option[value="' + address[11] + '"]').attr("selected", "selected");
        //         //$("#administrative_area_level_123").val(address[11]);
        //         $("#s_mobile").val(address[7]);
        //         $("#postal_code2").val(address[8]);
        //     });
        // });
        // $(document).on("click", ".open-AddBookDialog1", function() {
            function deleteshippingaddress(myBookId){
            if (confirm("Confirm delete Shipping Address") == true) {
                // var myBookId = $(this).data('id');
                $.post("<?php echo base_url(); ?>index.php/home/shippingaddressdel/" + myBookId, function(data) {
                    if (data == "done") {
                        $('#accordion').html('');
                        load_address_form();
                        $('.colds').hide();
                        var val2 = $("input[name$='addreessList']:checked").val();
                        $(".colds_" + val2).show();
                    }
                });
            }
        }
        // });
        // $('#s_mobile,#s_zipcode,#mbl,#zip_code,#zip,#phone').keypress(function(event) {
        //     var keycode = event.which;
        //     if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
        //         event.preventDefault();
        //     }
        // });
        // $('#s_mobile,#s_zipcode,#mbl,#zip_code,#zip,#phone').on("cut copy paste", function(e) {
        //     e.preventDefault();
        // });

        // var placeSearch, autocomplete, autocomplete2;

        // var componentForm = {

        //     locality: 'long_name',
        //     administrative_area_level_1: 'long_name',
        //     country: 'long_name',
        //     postal_code: 'short_name'
        // };

        // function initAutocomplete() {
        //     // Create the autocomplete object, restricting the search predictions to
        //     // geographical location types.
        //     autocomplete = new google.maps.places.Autocomplete(
        //         document.getElementById('autocomplete'), {
        //             // componentRestrictions: {'country':["us"]},
        //             types: ['geocode'] // (cities)
        //         });
        //     autocomplete2 = new google.maps.places.Autocomplete(
        //         document.getElementById('autocomplete2'), {
        //             // componentRestrictions: {'country':["us"]},
        //             types: ['geocode'] // (cities)
        //         });


        //     //console.log(autocomplete);
        //     // Avoid paying for data that you don't need by restricting the set of
        //     // place fields that are returned to just the address components.
        //     autocomplete.setFields(['address_component']);
        //     autocomplete2.setFields(['address_component']);

        //     // When the user selects an address from the drop-down, populate the
        //     // address fields in the form.
        //     autocomplete.addListener('place_changed', function() {
        //         fillInAddress(autocomplete, "");
        //     });
        //     autocomplete2.addListener('place_changed', function() {
        //         fillInAddress(autocomplete2, "2");
        //     });

        // }

        // function fillInAddress(autocomplete, unique) {

        //     // Get the place details from the autocomplete object.
        //     var place = autocomplete.getPlace();
        //     //console.log(place);
        //     for (var component in componentForm) {
        //         if (!!document.getElementById(component + unique)) {
        //             document.getElementById(component + unique).value = '';
        //             document.getElementById(component + unique).disabled = false;
        //         }
        //     }

        //     // Get each component of the address from the place details
        //     // and fill the corresponding field on the form.

        //     for (var i = 0; i < place.address_components.length; i++) {
        //         var addressType = place.address_components[i].types[0];

        //         if (componentForm[addressType] && document.getElementById(addressType + unique)) {

        //             var val = place.address_components[i][componentForm[addressType]];
        //             document.getElementById(addressType + unique).value = val;
        //             if (addressType == "country") {
        //                 document.getElementById("country_code" + unique).value = place.address_components[i].short_name;
        //             }
        //         }
        //     }

        //     document.getElementById("autocomplete" + unique).value = place.address_components[0][componentForm['locality']] + " " + place.address_components[1][componentForm['administrative_area_level_1']];
        //     <?php
        //     if ($this->session->userdata('pickup') == "") {
        //         if ($this->session->userdata('user_login') != "yes") { ?>
        //             returnaddress();
                <?php // }
           // } else { ?>
              //  $('.payment').show();
            <?php //} ?>
        // }
    </script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrEzQZBq1GTor9CDqmgMnHkBpfPPj1_ZM&libraries=places&callback=initAutocomplete" async defer></script> -->




<?php } ?>
<?php if ($this->session->userdata('user_login') != "yes") { ?>
    <div class="row">
        <div class="col-md-12" style="display:none;">
            <input type="radio" name="type" class="_2haq-9" id="delivery" value="" />Delivery
            <input type="radio" name="type" class="_2haq-9" id="delivery" value="" />Pickup
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input class="form-control required" value="<?php echo $username; ?>" name="firstname" type="text" placeholder="<?php echo translate('first_name'); ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input class="form-control required" value="<?php echo $surname; ?>" name="lastname" type="text" placeholder="<?php echo translate('last_name'); ?>">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <input class="form-control required address" name="address1" value="<?php echo $address1; ?>" type="text" placeholder="<?php echo translate('address_line_1'); ?>">
            </div>

        </div>
        <div class="col-md-12">
            <div class="form-group">
                <input class="form-control required address" name="address2" value="<?php echo $address2; ?>" type="text" placeholder="<?php echo translate('address_line_2'); ?>">
            </div>
        </div>
        <div id="selectMap" style="height: 30vh;"></div>
        <div class="col-md-4">
            <div class="form-group">
                <?php // echo "zip".$this->session->userdata('user_zips'); 
                ?>
                <input class="form-control required" id="zip" name="zip" type="text" <?php if ($this->session->userdata('user_zips') != '') { ?> readonly <?php } ?> value="<?php if ($this->session->userdata('user_zips') != '') {
                                                                                                                                                                                echo $this->session->userdata('user_zips');
                                                                                                                                                                            } else {
                                                                                                                                                                                echo $zip;
                                                                                                                                                                            } ?>" placeholder="<?php echo translate('postcode/ZIP'); ?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <input class="form-control required email" value="<?php echo $email; ?>" name="email" type="text" placeholder="<?php echo translate('email'); ?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <input class="form-control required" type="text" value="<?php echo $phone; ?>" name="phone" id="phone" type="text" minlength="10" maxlength="10" placeholder="<?php echo translate('phone_number'); ?>">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <?php $state_set = $this->db->get('state')->result_array(); ?>
                <select class="form-control required" name="state" data-toggle="tooltip" title="<?php echo translate('state'); ?>">
                    <option value="">Select State</option>
                    <?php foreach ($state_set as $state) { ?>
                        <option value="<?php echo $state['name']; ?>"><?php echo $state['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <input name="country" class="form-control required" type="text" value="Malaysia" placeholder="Country" readonly>
            </div>
        </div>

        <?php /* <div class="col-sm-12" id="lnlat" style="display:none;">
                                                                            <div class="form-group">
                                                                            <div class="col-sm-12">
                                                                            <input id="langlat" value="" type="text" placeholder="langitude - latitude" name="langlat" class="form-control" readonly>
                                                                            </div>
                                                                            </div>
                                                                            </div>
                                                                            <div class="col-sm-12" id="maps" style="height:400px;">
                                                                            <div class="form-group">
                                                                            <div id="map-canvas" style="height:400px;">
                                                                            </div>
                                                                            </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-md-12" style="display:none;">
                                                                            <div class="checkbox">
                                                                            <label>
                                                                            
                                                                            <input type="checkbox"> 
                                                                            <?php echo translate('ship_to_different_address_for_invoice');?>
                                                                            </label>
                                                                            </div>
                                                                            </div>
                                                                            */ ?>

        <div class="col-md-12">
            <span class="btn btn-theme-dark" onclick="load_payments();" style="background: #e57129;border: 1px solid #e57129;">
                <?php echo translate('next'); ?>
            </span>
        </div>

    </div>
<?php } ?>

<input type="hidden" id="first" value="yes" />

<script>
    // let selectMap;
    // selectMap = new google.maps.Map(document.getElementById("selectMap"), {
    //     center: {
    //         lat: -34.397,
    //         lng: 150.644
    //     },
    //     zoom: 8,
    // });
    // $('#s_mobile,#s_zipcode,#mbl,#zip_code,#zip,#phone').keypress(function(event) {
    //     var keycode = event.which;
    //     if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
    //         event.preventDefault();
    //     }
    // });
</script>