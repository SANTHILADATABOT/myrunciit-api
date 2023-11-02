<?php
if ($this->session->userdata('user_login') == "yes") {
    $user       = $this->session->userdata('user_id');
    $ship_address  = $this->db->get_where('shipping_address', array('user_id' => $user))->result_array();
    // print_r($ship_address);


    // echo "sd".$this->session->userdata('user_zips');
    //$tbl = strtolower("vendor");



?>
    <div class="row">
        <div class="col-md-12 hidden">
            <input type="radio" name="type" class="_2haq-9" id="delivery" value="" />Delivery
            <input type="radio" name="type" class="_2haq-9" id="delivery" value="" />Pickup
        </div>
        <div class="col-md-12">
            <div class="_2bai clearfix">

                <?php
                foreach ($ship_address as $sa) {
                    $this->db->select('*');
                    $this->db->from('vendor');
                    $this->db->where('status', 'approved');
                    $this->db->where('delivery', 'yes');
                    $this->db->where('vendor_id', $this->session->userdata('pickup_loc'));
                    $this->db->like('delivery_zipcode', $sa['zip_code']);

                    $allStores = $this->db->get()->result_array();
                    // print_r($sa);
                    // print_r($sa);
                    //echo "qry" . $this->db->last_query();
                ?>
                    <div data-useraddress="<?php echo $sa['address']; ?>,<?php echo $sa['city']; ?>,<?php echo $sa['state']; ?>,<?php echo str_replace('-','', $sa['country']); ?>" data-storeaddress="<?php echo $allStores[0]['address1']; ?>,<?php echo $allStores[0]['city']; ?>,<?php echo $allStores[0]['state']; ?>,<?php echo $allStores[0]['country']; ?>" data-storeid="<?php echo $allStores[0]['vendor_id']; ?>"  data-storelat="<?php echo $allStores[0]['latitude']; ?>" data-userlat="<?php echo $sa['latitude']; ?>" data-userlng="<?php echo $sa['longitude']; ?>" data-storelng="<?php echo $allStores[0]['longitude']; ?>">
                        <input type="radio" name="addreessList" class="_2haq-9" <?php
                                                                                if ($sa['set_default'] == 1) { ?> checked="checked" <?php }
                                                                                                                                if (empty($allStores)) {
                                                                                                                                    echo "disabled";
                                                                                                                                } ?> id="addr<?php echo $sa['id']; ?>" value="<?php echo $sa['id']; ?>" />
                        <label for="addr<?php echo $i; ?>" class="_1tkDFt clearfix">

                            <div class="_6ATDKp"></div>

                            <div class="_2o59RR _27CukN"><strong><?php echo $sa['name']; ?></strong> <?php echo $sa['address'] . ', ' . $sa['zip_code'] . ', ' . $sa['country'] . ', ' . $sa['state'] . ', ' . $sa['mobile']; ?>
                                <?php if (!empty($allStores)) { ?>
                                    <span style="color: #8dc43c;margin:0px 10px">(Delivery available)</span><?php } else { ?><span style="color: #cc0101;margin:0px 10px">(Delivery not available)</span><?php }  ?>
                                <span class="open-AddBookDialog colds colds_<?php echo $sa['id']; ?>" data-toggle="modal" data-target="#addaddress<?php echo $sa['id']; ?>" data-id="<?php echo $sa['id']; ?>"><i class="fa fa-pencil"></i></span>
                                <span class="open-AddBookDialog1" data-id="<?php echo $sa['id']; ?>"><i class="fa fa-trash"></i></span>


                            </div>
                        </label>
                    </div>
                    <div id="addaddress<?php echo $sa['id']; ?>" class="modal addresss fade" role="dialog">
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
                                                <?php
                                                echo form_open(base_url() . 'index.php/home/shippingaddressupdate/', array(
                                                    'class' => 'form-loginaa',
                                                    'method' => 'post',
                                                    'id' => 'form-loginaa'
                                                ));

                                                ?>
                                                <input type="hidden" name="bookId" id="bookId" value="" />
                                                <div class="row  br0" style="padding: 0;">
                                                    <div class="title">

                                                        <div class="option">

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input class="form-control" type="text" name="s_name" id="s_name" value="<?php echo $sa['name']; ?>" placeholder="Name">
                                                        </div>

                                                        <div class="form-group ">
                                                            <label>Phone No</label>
                                                            <input class="form-control" type="text" name="s_mobile" id="s_mobile" value="<?php echo $sa['mobile']; ?>" placeholder="Phone No">
                                                        </div>
                                                        <div class="form-group ">
                                                            <label>Email</label>
                                                            <input class="form-control" type="text" name="s_email" id="s_email" value="<?php echo $sa['email']; ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Address1</label>
                                                            <input class="form-control" type="hidden" name="cou_shrt" id="country_code2" value="">
                                                            <input class="form-control required" type="text" name="s_address" id="autocomplete2" value="<?php echo $sa['address']; ?>" placeholder="Address1">
                                                        </div>

                                                        <div class="form-group ">
                                                            <label>Latitude</label>
                                                            <input class="form-control" type="text"  name="s_latitude" id="s_latitude" value="<?php echo $sa['latitude']; ?>" placeholder="Latitude">
                                                        </div>
                                                        <div class="form-group ">
                                                            <label>Longitude</label>
                                                            <input class="form-control" type="text"  name="s_longitude" id="s_longitude" value="<?php echo $sa['longitude']; ?>" placeholder="longitude">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Apartment</label>
                                                            <input class="form-control" type="text" name="s_address1" id="s_address1" value="<?php echo $sa['address1']; ?>" placeholder="APT/SUITE # ">
                                                        </div>
                                                        <div class="form-group  ">
                                                            <label>Zip Code</label>
                                                            <input class="form-control required" type="text" name="s_zipcode" id="postal_code2" value="<?php echo $sa['zip_code']; ?>" maxlength="6" placeholder="Zip Code">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>City</label>
                                                            <input id="locality2" name="cities1" class="form-control required" type="text" value="<?php echo $sa['city']; ?>" placeholder="city">
                                                            <!-- <div  id="variants1">
                                <select name="city1" id="city" class="form-control demo-chosen-select" data-placeholder="Choose a state" tabindex="2" data-hide-disabled="true" style="border-color: rgb(147, 30, 205);"><option value="">Choose a City</option></select>
                                </div>-->
                                                        </div>
                                                        <div class="form-group">
                                                            <label>State</label>
                                                            <?php echo $this->db;?>
                                                            <!--<input id="administrative_area_level_123" name="state1" class="form-control required"  type="text" value="<?php echo $sa['state']; ?>" placeholder="State">-->
                                                            <!--  <div class=""  id="sub_cats1">
                                <select name="district1" id="district" class="form-control demo-chosen-select" data-placeholder="Choose a state" tabindex="2" data-hide-disabled="true" style="border-color: rgb(147, 30, 205);"><option value="">Choose a State</option></select>
                                </div>-->
                                                            <?php $state_set = $this->db->get('state')->result_array(); ?>
                                                            <select class="form-control required" name="state1" id="administrative_area_level_123" data-toggle="tooltip" title="<?php echo translate('state'); ?>">
                                                                <option value="">Select State</option>
                                                                <?php foreach ($state_set as $state) { ?>
                                                                    <option value="<?php echo $state['name']; ?>"><?php echo $state['name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label>Country</label>
                                                            <input id="country2" name="country1" class="form-control required" type="text" value="<?php echo $sa['country']; ?>" placeholder="Country" readonly>
                                                            <?php //echo $this->crud_model->select_html3('country','country1','name','add',' form-control demo-chosen-select required','','','','get_cat2'); 
                                                            ?>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <!--  <input type="submit" id='submitu' name='submitu' value="Submit" class="btn btn-theme-sm btn-block btn-sm  enterer" style="background:#cc0101;color:#fff">-->
                                                        <span class="btn btn-theme-sm btn-block  btn-sm address_btn enterer">
                                                            Edit
                                                        </span>
                                                    </div>
                                                </div>

                                                </form>
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
                                            <?php
                                            echo form_open(base_url() . 'index.php/home/login/add_address/', array(
                                                'class' => 'form-login',
                                                'method' => 'post',
                                                'id' => 'address'
                                            ));

                                            ?>
                                            <div class="row br0" style="padding: 0;">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input class="form-control required" type="text" name="name" value="" placeholder="Name">
                                                    </div>
                                                    <div class="clearfix">
                                                        <div class="form-group w33">
                                                            <label>Phone No</label>
                                                            <input class="form-control required" type="text" name="mobile" id="mbl" placeholder="Phone No">
                                                        </div>
                                                        <div class="form-group w67 pl15">
                                                            <label>Email</label>
                                                            <input class="form-control required" type="text" name="email" placeholder="Email">
                                                        </div>
                                                        <div class="form-group w67 pl15">
                                                            <label>Latitude</label>
                                                            <input class="form-control required" type="text" name="latitude" placeholder="Latitude" required>
                                                        </div>
                                                        <div class="form-group w67 pl15">
                                                            <label>Longitude</label>
                                                            <input class="form-control required" type="text" name="longitude" placeholder="Longitude" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Address1</label>
                                                        <input class="form-control" type="hidden" name="cou_shrt1" id="country_code" value="">
                                                        <input class="form-control required" type="text" name="street_address" id="autocomplete" placeholder="Address1">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Apartment</label>
                                                        <input class="form-control" type="text" name="street_address2" placeholder="APT/SUITE #  ">
                                                    </div>



                                                    <div class="form-group">

                                                        <label>Zip Code</label>
                                                        <input class="form-control required" type="text" name="zip_code" id="postal_code" maxlength="6" placeholder="Zip Code">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>City</label>
                                                        <input id="locality" name="cities" class="form-control required" type="text" value="" placeholder="City">


                                                    </div>
                                                    <div class="form-group">
                                                        <label>State</label>
                                                        <!-- <input id="administrative_area_level_1" name="state" class="form-control required" type="text" value="" placeholder="State">-->
                                                        <?php $state_set = $this->db->get('state')->result_array(); ?>
                                                        <select class="form-control required" name="state" id="administrative_area_level_1" data-toggle="tooltip" title="<?php echo translate('state'); ?>">
                                                            <option value="">Select State</option>
                                                            <?php foreach ($state_set as $state) { ?>
                                                                <option value="<?php echo $state['name']; ?>"><?php echo $state['name']; ?></option>
                                                            <?php } ?>
                                                        </select>


                                                    </div>
                                                    <div class="form-group ">
                                                        <label>Country</label>
                                                        <input id="country" name="country" class="form-control required" type="text" value="Malaysia" placeholder="Country" readonly>


                                                    </div>





                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <span class="btn btn-theme-sm btn-block  btn-sm address_btn enterer">
                                                        Add
                                                    </span>
                                                </div>
                                            </div>
                                            </form>
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
            
            <span class="btn btn-theme-dark" onclick="<?php if ($this->session->userdata('user_zips') != "") { ?> get_quotation(); <?php } else { ?> load_payments();  <?php }?> ">
                <?php echo translate('next'); ?>
            </span>
        </div>
    </div>

    <script>
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
        $(document).on("click", ".open-AddBookDialog", function() {
            var myBookId = $(this).data('id');
            $(".modal-body #bookId").val(myBookId);
            $.post("<?php echo base_url(); ?>index.php/home/shippingaddress/" + myBookId, function(data) {
                var address = data.split('^^');
                var count1 = address[5].split('-');
                $("#s_name").val(address[0]);
                $("#autocomplete2").val(address[1]);
                $("#s_address1").val(address[2]);
                $("#s_email").val(address[3]);
                $("#locality2").val(address[4]);
                $("#country2").val(count1[0]);
                $("#country_code2").val(count1[1]);
                $("#administrative_area_level_12").val(address[6]);
                $('#administrative_area_level_123 option[value="' + address[11] + '"]').attr("selected", "selected");
                //$("#administrative_area_level_123").val(address[11]);
                $("#s_mobile").val(address[7]);
                $("#postal_code2").val(address[8]);
            });
        });
        $(document).on("click", ".open-AddBookDialog1", function() {

            var myBookId = $(this).data('id');
            $.post("<?php echo base_url(); ?>index.php/home/shippingaddressdel/" + myBookId, function(data) {
                if (data == "done") {
                    $('#accordion').html('');
                    load_address_form();
                    $('.colds').hide();
                    var val2 = $("input[name$='addreessList']:checked").val();
                    $(".colds_" + val2).show();
                }
            });

        });
        $('#s_mobile,#s_zipcode,#mbl,#zip_code,#zip,#phone').keypress(function(event) {
            var keycode = event.which;
            if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
                event.preventDefault();
            }
        });
        $('#s_mobile,#s_zipcode,#mbl,#zip_code,#zip,#phone').on("cut copy paste", function(e) {
            e.preventDefault();
        });

        var placeSearch, autocomplete, autocomplete2;

        var componentForm = {

            locality: 'long_name',
            administrative_area_level_1: 'long_name',
            country: 'long_name',
            postal_code: 'short_name'
        };

        function initAutocomplete() {
            // Create the autocomplete object, restricting the search predictions to
            // geographical location types.
            autocomplete = new google.maps.places.Autocomplete(
                document.getElementById('autocomplete'), {
                    // componentRestrictions: {'country':["us"]},
                    types: ['geocode'] // (cities)
                });
            autocomplete2 = new google.maps.places.Autocomplete(
                document.getElementById('autocomplete2'), {
                    // componentRestrictions: {'country':["us"]},
                    types: ['geocode'] // (cities)
                });


            //console.log(autocomplete);
            // Avoid paying for data that you don't need by restricting the set of
            // place fields that are returned to just the address components.
            autocomplete.setFields(['address_component']);
            autocomplete2.setFields(['address_component']);

            // When the user selects an address from the drop-down, populate the
            // address fields in the form.
            autocomplete.addListener('place_changed', function() {
                fillInAddress(autocomplete, "");
            });
            autocomplete2.addListener('place_changed', function() {
                fillInAddress(autocomplete2, "2");
            });

        }

        function fillInAddress(autocomplete, unique) {

            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();
            //console.log(place);
            for (var component in componentForm) {
                if (!!document.getElementById(component + unique)) {
                    document.getElementById(component + unique).value = '';
                    document.getElementById(component + unique).disabled = false;
                }
            }

            // Get each component of the address from the place details
            // and fill the corresponding field on the form.

            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];

                if (componentForm[addressType] && document.getElementById(addressType + unique)) {

                    var val = place.address_components[i][componentForm[addressType]];
                    document.getElementById(addressType + unique).value = val;
                    if (addressType == "country") {
                        document.getElementById("country_code" + unique).value = place.address_components[i].short_name;
                    }
                }
            }

            document.getElementById("autocomplete" + unique).value = place.address_components[0][componentForm['locality']] + " " + place.address_components[1][componentForm['administrative_area_level_1']];
            <?php
            if ($this->session->userdata('pickup') == "") {
                if ($this->session->userdata('user_login') != "yes") { ?>
                    returnaddress();
                <?php }
            } else { ?>
                $('.payment').show();
            <?php } ?>
        }
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
    let selectMap;
    selectMap = new google.maps.Map(document.getElementById("selectMap"), {
        center: {
            lat: -34.397,
            lng: 150.644
        },
        zoom: 8,
    });
    $('#s_mobile,#s_zipcode,#mbl,#zip_code,#zip,#phone').keypress(function(event) {
        var keycode = event.which;
        if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
            event.preventDefault();
        }
    });
</script>