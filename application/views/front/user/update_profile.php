
<?php
    foreach($user_info as $row)
    {
?>
    <div class="information-title">
        <?php echo translate('profile_information');?>
    </div>
    
    <div class="details-wrap">
        <div class="row">
            <div class="col-md-12">
                <div class="tabs-wrapper content-tabs">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                <?php echo translate('personal_info');?>
                            </a>
                        </li>
                        <li>
                            <a href="#tab2" data-toggle="tab">
                                <?php echo translate('change_password');?>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1">
                             <div class="details-wrap">
                                <div class="block-title alt"> 
                                    <i class="fa fa-angle-down"></i> 
                                    <?php echo translate('change_your_profile_information');?>
                                </div>
                                <div class="details-box">
                                    <?php
                                        echo form_open(base_url() . 'index.php/home/registration/update_info/', array(
                                            'class' => 'form-login',
                                            'method' => 'post',
                                            'enctype' => 'multipart/form-data'
                                        ));
                                    ?>    
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="username" value="<?php echo $row['username']; ?>" type="text" placeholder="<?php echo translate('name');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6" style="display:none">
                                                <div class="form-group">
                                                    <input class="form-control" name="email" value="<?php echo $row['email']; ?>" type="email" placeholder="<?php echo translate('email');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input class="form-control" name="address1" value="<?php echo $row['address1']; ?>" id="address1" onchange="findCoordinates()" type="text" placeholder="<?php echo translate('address 1');?>">
                                                </div>
                                            </div>
                                            <?php 
                                            $this->db->where('user_id',$row['user_id']);
                                            $this->db->like('unique_id', 'REG','after');
                                            $ship_add_user =  $this->db->get('shipping_address')->result_array();
                                            foreach($ship_add_user as $row1)
                                            {                                            
                                            ?>
                                            <!-- <div class="col-md-12"> -->
                                                <div class="form-group">
                                                    <input class="form-control required" name="latitude" id="latitude" type="hidden" value="<?php echo $row1['latitude']; ?>" data-toggle="tooltip">
                                                </div>
                                            <!-- </div> -->
                                            <!-- <div class="col-md-12"> -->
                                                <div class="form-group">
                                                    <input class="form-control required" name="longitude" id="longitude" type="hidden" value="<?php echo $row1['longitude']; ?>" data-toggle="tooltip">
                                                </div>
                                            <!-- </div> -->
                                            <?php }?>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input class="form-control" name="address2" value="<?php echo $row['address2']; ?>" type="text" placeholder="<?php echo translate('address 2');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="phone" value="<?php echo $row['phone']; ?>" type="tel" placeholder="<?php echo translate('phone');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <input class="form-control" name="city" value="<?php echo $row['city']; ?>" type="text" placeholder="<?php echo translate('city');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <input class="form-control" name="state" value="<?php echo $row['state']; ?>" type="text" placeholder="<?php echo translate('state');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <input class="form-control" name="country" value="<?php echo $row['country']; ?>" type="text" placeholder="<?php echo translate('country');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="zip" value="<?php echo $row['zip']; ?>" type="text" placeholder="<?php echo translate('ZIP');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="skype" value="<?php echo $row['skype']; ?>" type="text" placeholder="<?php echo translate('skype');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="google_plus" value="<?php echo $row['google_plus']; ?>" type="text" placeholder="<?php echo translate('google_plus');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="facebook" value="<?php echo $row['facebook']; ?>" type="text" placeholder="<?php echo translate('facebook');?>">
                                                </div>
                                            </div>
                                               <div class="form-group hidden">
                                                <label class="col-md-2 control-label" style="margin-top: 35px;margin-left: 60px;"><?php echo translate('Currency');?></label>
                                                <div class="col-md-4">
                                                    <?php echo $this->crud_model->select_html('currency_settings','currency_settings','name','edit','demo-chosen-select form-control',$row['default_currency_id'],'status','ok'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <span class="btn btn-theme pull-right signup_btn" data-unsuccessful='<?php echo translate('info_update_unsuccessful!'); ?>' data-success='<?php echo translate('info_updated_successfully!'); ?>' data-ing='<?php echo translate('updating..') ?>' >
                                                    <?php echo translate('update');?>
                                                </span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>   
                        </div>
                        <div class="tab-pane fade" id="tab2">
                            <div class="details-wrap">
                                <div class="block-title alt"> <i class="fa fa-angle-down"></i> <?php echo translate('change_your_password');?></div>
                                <div class="details-box">
                                    <?php
                                        echo form_open(base_url() . 'index.php/home/registration/update_password/', array(
                                            'class' => 'form-delivery',
                                            'method' => 'post',
                                            'enctype' => 'multipart/form-data'
                                        ));
                                    ?> 
                                        <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    <input required name="password" id="password-input" type="password" placeholder="<?php echo translate('old_password');?>" class="form-control">
                                                    <span toggle="#password-input" class="eye-icon"><i class="fa fa-eye"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <input required name="password1" id="password1-input" type="password" placeholder="<?php echo translate('new_password');?>" class="form-control">
                                                    <span toggle="#password1-input" class="eye-icon"><i class="fa fa-eye"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <input required name="password2" id="password2-input" type="password" placeholder="<?php echo translate('confirm_new_password');?>" class="form-control">
                                                    <span toggle="#password2-input" class="eye-icon"><i class="fa fa-eye"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12">
                                            <span type="submit" class="btn btn-theme pull-right signup_btn" data-unsuccessful='<?php echo translate(''); ?>' data-success='<?php echo translate(''); ?>' data-ing='<?php echo translate('updating..') ?>' >
                                                    <?php echo translate('update');?> 
                                                </button>
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
    </div>  
<?php
    }
?>
     
<script>
    $(document).ready(function() {
        // Toggle password visibility and change the icon
        $(".eye-icon").click(function() {
            var input = $($(this).attr("toggle"));
            var icon = $(this).find("i");

            if (input.attr("type") === "password") {
                input.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                input.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });
    });

    function findCoordinates() {
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
                       console.log("Address: " + address + "<br>Latitude: " + lat_val + "<br>Longitude: " + log_val);
                     }
                     else{
                        document.getElementById("latitude").value="";
                        document.getElementById("longitude").value="";
                     }                
                },
                error: function () {
                    console.log("Error: Unable to retrieve data from GraphHopper.");
                    document.getElementById("latitude").value="";
                    document.getElementById("longitude").value="";
                }
            });
        } else {
            alert("Please Enter your Address");
            document.getElementById("latitude").value="";
            document.getElementById("longitude").value="";
        }
    }
    
</script>                                   