<section class="page-section color get_into">
    <div class="container">
        <div class="row margin-top-0">
            <div class="col-sm-8 col-sm-offset-2">
                
				<?php
                    echo form_open(base_url() . 'index.php/home/registration/add_info/', array(
                        'class' => 'form-login',
                        'method' => 'post',
                        'id' => 'sign_form'
                    ));
                    $fb_login_set = $this->crud_model->get_type_name_by_id('general_settings','51','value');
                    $g_login_set = $this->crud_model->get_type_name_by_id('general_settings','52','value');
                ?>
                	<div class="row box_shape">
                        <div class="title">
                            <?php echo translate('customer_registration');?>
                            <div class="option">
                            	<?php echo translate('already_a_member_?_click_to_');?>
                                <?php
									if ($this->crud_model->get_type_name_by_id('general_settings','58','value') !== 'ok') {
								?>
                                <a href="<?php echo base_url(); ?>index.php/home/login_set/login"> 
                                    <?php echo translate('login');?>!
                                </a>
                                <?php
									}else{
								?>
                                <a href="<?php echo base_url(); ?>index.php/home/login_set/login"> 
                                    <?php echo translate('login');?>! <?php echo translate('as_customer');?>
                                </a>
                                <?php //echo translate('_or_');?>
                                <!--<a href="<?php //echo base_url(); ?>index.php/home/vendor_logup/registration"> -->
                                <!--    <?php //echo translate('sign_up');?>! <?php //echo translate('as_vendor');?>-->
                                <!--</a>-->
                                <?php
									}
								?>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control required" name="username" type="text" placeholder="<?php echo translate('first_name');?>" data-toggle="tooltip" title="<?php echo translate('first_name');?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control required" name="surname" type="text" placeholder="<?php echo translate('last_name');?>" data-toggle="tooltip" title="<?php echo translate('last_name');?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control emails required" name="email" type="email" placeholder="<?php echo translate('email');?>" data-toggle="tooltip" title="<?php echo translate('email');?>">
                            </div>
                            <div id='email_note'></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" name="phone" type="text" placeholder="<?php echo translate('phone');?>" data-toggle="tooltip" title="<?php echo translate('phone');?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control pass1 required" type="password" name="password1" placeholder="<?php echo translate('password');?>" data-toggle="tooltip" title="<?php echo translate('password');?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control pass2 required" type="password" name="password2" placeholder="<?php echo translate('confirm_password');?>" data-toggle="tooltip" title="<?php echo translate('confirm_password');?>">
                            </div>
                            <div id='pass_note'></div> 
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input class="form-control required" onchange="findCoordinates()" name="address1" id = "address1" type="text" placeholder="<?php echo translate('address_line_1');?>" data-toggle="tooltip" title="<?php echo translate('address_line_1');?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input class="form-control required" name="latitude" id="latitude" type="hidden" value="" data-toggle="tooltip">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input class="form-control required" name="longitude" id="longitude" type="hidden" value="" data-toggle="tooltip">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input class="form-control required" name="address2" type="text" placeholder="<?php echo translate('address_line_2');?>" data-toggle="tooltip" title="<?php echo translate('address_line_2');?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control required" name="age" type="number" placeholder="<?php echo translate('age');?>" data-toggle="tooltip" title="<?php echo translate('age');?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" style="border: 1px solid #e9e9e9;">
                               <form >
                                <input type="radio" id="male" name="gender" value="male">
                                <label for="male" style="bottom: -14px;position: relative;">male</label>
                                <input type="radio" id="female" name="gender" value="female">
                                <label for="female" style="bottom: -14px;position: relative;">Female</label>
                            </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control required" type="text" name="city" placeholder="<?php echo translate('city');?>" data-toggle="tooltip" title="<?php echo translate('city');?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php $state_set = $this->db->get('state')->result_array(); ?>
                                <select class="form-control required" name="state" data-toggle="tooltip" title="<?php echo translate('state');?>">
                                    <option value="">Select State</option>
                                    <?php foreach($state_set as $state)
                                    { ?>
                                        <option value="<?php echo $state['name']; ?>"><?php echo $state['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                     <?php /* ?>   <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control required" type="text" name="state" placeholder="<?php echo translate('state');?>" data-toggle="tooltip" title="<?php echo translate('state');?>">
                            </div>
                        </div> <?php */ ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control required" type="text" name="country" value="Malaysia" readyonly placeholder="<?php echo translate('country');?>" data-toggle="tooltip" title="<?php echo translate('country');?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="">
                                <input class="form-control required" name="zip" type="text" placeholder="<?php echo translate('zip');?>" data-toggle="tooltip" title="<?php echo translate('zip');?>">
                            </div>
                        </div>
                        <div class="col-md-12 terms">
                            <input  name="terms_check" type="checkbox" value="ok" > 
                            <?php echo translate('i_agree_with');?>
                            <a href="<?php echo base_url();?>index.php/home/legal/terms_conditions" target="_blank">
                                <?php echo translate('terms_&_conditions');?>
                            </a>
                        </div>
                        <?php
							if($this->crud_model->get_settings_value('general_settings','captcha_status','value') == 'ok'){
						?>
                        <div class="col-md-12">
                            <div class="outer required">
                                <div class="form-group">
                                    <?php echo $recaptcha_html; ?>
                                </div>
                            </div>
                        </div>
                        <?php
							}
						?>
                        <div class="col-md-12">
                            <span class="btn btn-theme-sm btn-block btn-theme-dark pull-right logup_btn" data-ing='<?php echo translate('registering..'); ?>' data-msg="">
                                <?php echo translate('register');?>
                            </span>
                        </div>
                        <!--- Facebook and google login -->
                        <?php if($fb_login_set == 'ok' || $g_login_set == 'ok'){ ?>
                            <div class="col-md-12 col-lg-12">
                                <h2 class="login_divider"><span>or</span></h2>
                            </div>
                        <?php if($fb_login_set == 'ok'){ ?>
                            <div class="col-md-12 col-lg-6 <?php if($g_login_set !== 'ok'){ ?>mr_log<?php } ?>">
                                <?php if (@$user): ?>
                                    <a class="btn btn-theme btn-block btn-icon-left facebook" href="<?= $url ?>">
                                        <i class="fa fa-facebook"></i>
                                        <?php echo translate('sign_in_with_facebook');?>
                                    </a>
                                <?php else: ?>
                                    <a class="btn btn-theme btn-block btn-icon-left facebook" href="<?= $url ?>">
                                        <i class="fa fa-facebook"></i>
                                        <?php echo translate('sign_in_with_facebook');?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php } if($g_login_set == 'ok'){ ?>  
                            <div class="col-md-12 col-lg-6 <?php if($fb_login_set !== 'ok'){ ?>mr_log<?php } ?>">
                                <?php if (@$g_user): ?>
                                    <a class="btn btn-theme btn-block btn-icon-left google" style="background:#ce3e26" href="<?= $g_url ?>">
                                        <i class="fa fa-google"></i>
                                        <?php echo translate('sign_in_with_google');?>
                                    </a>
                               <?php else: ?>
                                    <a class="btn btn-theme btn-block btn-icon-left google" style="background:#ce3e26" href="<?= $g_url ?>">
                                        <i class="fa fa-google"></i>
                                        <?php echo translate('sign_in_with_google');?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php
                                }
                            }
                        ?>
                    </div>
            	</form>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        // function findCoordinates() {
        //   //  alert();
        // 	//document.getElementById("result").innerHTML = "";
        //     var apiKey = 'b9df6f5c-bf07-4b43-8a23-d9785646e632'; // Replace with your GraphHopper API key
        //     var address = document.getElementById("address1").value;
        //     var latitude_get = document.getElementById("latitude").value;
        //     var longitude_get = document.getElementById("longitude").value;
        //     if(address!=""){
        //     // Encode the address for the URL
        //     var encodedAddress = encodeURIComponent(address);

        //     // Construct the API request URL
        //     var apiUrl = `https://graphhopper.com/api/1/geocode?q=${encodedAddress}&key=${apiKey}`;

        //     // Make an AJAX GET request
        //     $.ajax({
        //         url: apiUrl,
        //         type: 'GET',
        //         dataType: 'json',
        //         success: function (data) {
        //             if (data.hits && data.hits.length > 0) {
        //                 var latitude = data.hits[0].point.lat;
        //                 var longitude = data.hits[0].point.lng;
        //                 document.getElementById("latitude").value=latitude;
        //                 document.getElementById("longitude").value=longitude;
        //                console.log("Address: " + address + "<br>Latitude: " + latitude + "<br>Longitude: " + longitude);
        //             } else {
                        
        //                 console.log("No results found for the given address.");
        //                 document.getElementById("latitude").value="";
        //                 document.getElementById("longitude").value="";
        //                // document.getElementById("result").innerHTML = ;
        //             }
        //         },
        //         error: function () {
        //             console.log("Error: Unable to retrieve data from GraphHopper.");
        //          //   document.getElementById("result").innerHTML = "";
        //         }
        //     });
        // }
        // else{
        //     alert("Please Enter your Address");
        //     document.getElementById("latitude").value="";
        //                 document.getElementById("longitude").value="";
        // }
        // }

        function findCoordinates() {
          //  alert();
        	//document.getElementById("result").innerHTML = "";
          //  var apiKey = 'b9df6f5c-bf07-4b43-8a23-d9785646e632'; // Replace with your GraphHopper API key
            var address = document.getElementById("address1").value;
            var latitude_get = document.getElementById("latitude").value;
            var longitude_get = document.getElementById("longitude").value;
            if(address!=""){
            // Encode the address for the URL
            var encodedAddress = encodeURIComponent(address);

            // Construct the API request URL
            var apiUrl="https://api.opencagedata.com/geocode/v1/json?key=e3d089afaf5247708550efcf8a4a27f4&q="+encodedAddress+"&pretty=1&no_annotations=1";

            // Make an AJAX GET request
            $.ajax({
                url: apiUrl,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (data.results && data.results.length > 0) {
                    	var latitude = data.results[0].geometry.lat;
                        var longitude = data.results[0].geometry.lng;
                        var lat_val = latitude.toFixed(6);
                        var log_val = longitude.toFixed(6);
                        document.getElementById("latitude").value=lat_val;
                        document.getElementById("longitude").value=log_val;
                    //    console.log("Address: " + address + "<br>Latitude: " + latitude + "<br>Longitude: " + longitude);
                     }
                     else{
                        document.getElementById("latitude").value="";
                        document.getElementById("longitude").value="";
                     }
                
                  /*  if (data.hits && data.hits.length > 0) {
                        var latitude = data.hits[0].point.lat;
                        var longitude = data.hits[0].point.lng;
                        document.getElementById("latitude").value=latitude;
                        document.getElementById("longitude").value=longitude;
                       console.log("Address: " + address + "<br>Latitude: " + latitude + "<br>Longitude: " + longitude);
                    } else {
                        
                        console.log("No results found for the given address.");
                        document.getElementById("latitude").value="";
                        document.getElementById("longitude").value="";
                       // document.getElementById("result").innerHTML = ;
                    }*/
                },
                error: function () {
                    console.log("Error: Unable to retrieve data from GraphHopper.");
                    document.getElementById("latitude").value="";
                    document.getElementById("longitude").value="";
                }
            });
        }
        else{
            alert("Please Enter your Address");
            document.getElementById("latitude").value="";
                        document.getElementById("longitude").value="";
        }
        }
        </script>
<style>
	.get_into .terms a{
		margin:5px auto;
		font-size: 14px;
		line-height: 24px;
		font-weight: 400;
		color: #00a075;
		cursor:pointer;
		text-decoration:underline;
	}
	
	.get_into .terms input[type=checkbox] {
		margin:0px;
		width:15px;
		height:15px;
		vertical-align:middle;
	}
</style>