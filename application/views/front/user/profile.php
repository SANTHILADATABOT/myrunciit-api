<?php
	 foreach($user_info as $row)
		{
?>
<div class="row profile">
	<div class="col-lg-3 col-md-3 col-sm-4">
        <div class="thumbnail user-profile-img no-border no-padding thumbnail-banner size-1x1-b alt-font ">
            <div class="media">
                <a class="media-link" href="#">
                    <div class="img-bg" id="blah"  
                        style="background-size: cover; background-image: url('<?php 
                            if(file_exists('uploads/user_image/user_'.$row['user_id'].'.jpg')){ 
                                echo $this->crud_model->file_view('user',$row['user_id'],'100','100','no','src','','','.jpg').'?t='.time();
                            } else if($row['fb_id'] !== ''){ 
                                echo "https://graph.facebook.com/". $row['fb_id'] ."/picture?type=large";
                            } else if($row['g_id'] !== ''){
                                echo $row['g_photo'];
                            } else {
                                echo base_url(). "uploads/user_image/default.jpg";
                            } ?>')">
                    </div>
                </a>
            </div>
        </div>
        <div class="form-body pic_changer window_set" style="margin-top:0px; display:block;">
            <?php
                echo form_open(base_url() . 'index.php/home/registration/change_picture/'.$row['user_id'], array(
                    'class' => '',
                    'method' => 'post',
                    'id' => 'fff',
                    'enctype' => 'multipart/form-data'
                ));
            ?>
                <span id="inppic" class="set_image">
                    <label class="btn btn-theme btn-theme-sm btn-block" for="imgInp">
                        <span><?php echo translate('change_profile');?></span>
                    </label>
                    <input type="file" style="display:none;" id="imgInp" name="img" />
                </span>
                <span id="savepic" style="display:none;">
                    <span class="btn btn-theme btn-block btn-theme-sm signup_btn" onclick="abnv('inppic'); change_state('normal');"  data-ing="<?php echo translate('saving');?>..." data-success="<?php echo translate('profile_picture_saved_successfully!'); ?>" data-unsuccessful="<?php echo translate('edit_failed!'); ?> <?php echo translate('try_again!'); ?>" data-reload="no" >
                        <span><?php echo translate('save_changes');?></span>
                    </span>
                </span>
            </form>
        </div>
        <input type="hidden" id="state" value="normal" />
    </div>
    <div class="col-lg-9 col-md-9 col-sm-8">
        <div class="information-title" >
            <?php echo translate('profile_information');?>
        </div>
        <div class="row">
        	<div class="media-body" style="padding-right: 20px;padding-left: 20px;">
                            <table class="table table-condensed" style="font-size: 14px; margin-bottom: 0px">
                                <tr>
                                    <td><b><?php echo translate('first_name');?></b></td>
                                    <td align="left"><?php echo $row['username'];?></td>
                                    <td><b><?php echo translate('last_name');?></b></td>
                                    <td><?php echo $row['surname'];?></td>
                                </tr>
                                <tr>
                                    <td><b><?php echo translate('email');?></b></td>
                                    <td><?php echo $row['email'];?></td>
                                    <td><b><?php echo translate('contact_no');?></b></td>
                                    <td><?php echo $row['phone'];?></td>
                                </tr>
                                <tr>
                                    <td><b><?php echo translate('address');?></b></td>
                                    <td><?php echo $row['address1'];?> <?php echo $row['address2'];?></td>
                                    <td><b><?php echo translate('country');?></b></td>
                                    <td><?php echo $row['country'];?></td>
                                </tr>
                                <tr>
                                    <td><b><?php echo translate('state');?></b></td>
                                    <td><?php echo $row['state'];?></td>
                                    <td><b><?php echo translate('city');?></b></td>
                                    <td><?php echo $row['city'];?></td>
                                 
                                  
                                </tr>
                                <tr style="display:none;">
                                       <td><b><?php echo translate('Default Currency');?></b></td>
                                         <td><?php  $currency_id= $row['default_currency_id'];
                                             if($currency_id==''){
                                                 echo 'Default Currency not set';
                                             }
                                             else if($currency_id!='') {
                                             echo $res1 = $this->db->get_where('currency_settings',array('currency_settings_id' => $currency_id))->row()->name;
                                             }
                                    ?></td>
                                </tr>
                            </table>
                        </div>		
            
        </div>
        <div class="details-wrap" style="margin-top:30px;display:none;" >
            <div class="details-box orders">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="description">
                                <?php echo translate('name');?> :
                            </td>
                            <td class="diliver-date"> <?php echo $row['username'];?> </td>
                        </tr>
                        <tr>
                            <td class="description">
                                <?php echo translate('email');?> :
                            </td>
                            <td class="diliver-date"> <?php echo $row['email'];?> </td>
                        </tr>
                        <tr>
                            <td class="description">
                                <?php echo translate('address');?> :
                            </td>
                            <td class="diliver-date"> <?php echo $row['address1'];?> <?php echo $row['address2'];?> </td>
                        </tr>
                        <tr>
                            <td class="description">
                                <?php echo translate('contact_no');?> :
                            </td>
                            <td class="diliver-date"> <?php echo $row['phone'];?> </td>
                        </tr>
                        <tr>
                            <td class="description">
                                <?php echo translate('city');?> :
                            </td>
                            <td class="diliver-date"> <?php echo $row['city'];?> </td>
                        </tr>
                        <tr>
                            <td class="description">
                                <?php echo translate('state');?> :
                            </td>
                            <td class="diliver-date"> <?php echo $row['state'];?> </td>
                        </tr>
                        <tr>
                            <td class="description">
                                <?php echo translate('country');?> :
                            </td>
                            <td class="diliver-date"> <?php echo $row['country'];?> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-md-6">
    <h3 class="block-title"><span><?php echo translate('purchase_summary');?></span></h3>
                <div class="thumbnail no-border no-padding thumbnail-banner size-1x3" style="height:auto;">
                    <div class="media">
                        <div class="media-link">
                            <div class="caption">
                                <div class="caption-wrapper div-table">
                                    <div class="caption-inner div-cell">
                                        <h2 class="caption-title"><span><?php echo translate('total_purchase');?> : <?php echo currency($this->crud_model->user_total(0)); ?></span></h2>
                                        <h3 class="caption-sub-title"><span><?php echo translate('last_7_days');?> : <?php echo currency($this->crud_model->user_total(7)); ?></span></h3>
                                        <h3 class="caption-sub-title"><span><?php echo translate('last_30_days');?> : <?php echo currency($this->crud_model->user_total(30)); ?></span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            <h3 class="block-title"><span><?php echo translate('others_info');?></span></h3>
                <div class="thumbnail no-border no-padding thumbnail-banner size-1x3" style="height:auto;">
                    <div class="media">
                        <div class="media-link">
                            <div class="caption text-right">
                                <div class="caption-wrapper div-table">
                                    <div class="caption-inner div-cell">
                                        <h2 class="caption-title"><span><?php echo translate('wished_products');?> : <?php echo $this->crud_model->user_wished(); ?></span></h2>
                                        <h3 class="caption-sub-title"><span><?php echo translate('user_since');?> : <?php echo date('d M, Y',$row['creation_date']); ?></span></h3>
                                        <h3 class="caption-sub-title"><span><?php echo translate('last_login');?> : <?php echo date('d M, Y',$row['last_login']); ?></span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
              <?php if($this->crud_model->get_type_name_by_id('general_settings','83','value') == 'ok'){ ?>
            <div class="col-md-4" style="display:none;">
            <h3 class="block-title"><span><?php echo translate('package_info');?></span></h3>
                <div class="thumbnail no-border no-padding thumbnail-banner size-1x3" style="height:auto;">
                    <div class="media">
                        <div class="media-link">
                            <div class="caption text-right">
                                <div class="caption-wrapper div-table">
                                    <div class="caption-inner div-cell">
                                        <h2 class="caption-title"><span><?php echo translate('remaining_upload_amount');?> : <?php echo $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->product_upload; ?></span></h2>
                                     <?php 
                                        $package_info = json_decode($row['package_info'], true);
                                    ?>
                                        <h3 class="caption-sub-title"><span> <?php echo translate('current_package');?> : <?php if ($row['package_info'] == "[]" || $row['package_info'] == "") { echo translate('default'); } else { echo $package_info[0]['current_package'];}?></span></h3>
                                        <h3 class="caption-sub-title"><span><?php echo translate('payment_type');?> : <?php if ($row['package_info'] == "[]" || $row['package_info'] == "") { echo translate('none'); } else { echo $package_info[0]['payment_type'];}?></span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
</div>
<?php
	}
?> 
<script type="text/javascript">

	function abnv(thiss){
		$('#savepic').hide();
		$('#inppic').hide();
		$('#'+thiss).show();
	}
	function change_state(va){
		$('#state').val(va);
	}

	$('.user-profile-img').on('mouseenter',function(){
		//$('.pic_changer').show('fast');
	});

	//$('.set_image').on('click',function(){
	//    $('#imgInp').click();
	//});

	$('.user-profile-img').on('mouseleave',function(){
		if($('#state').val() == 'normal'){
			//$('.pic_changer').hide('fast');
		}
	});
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$('#blah').css('backgroundImage', "url('"+e.target.result+"')");
				$('#blah').css('backgroundSize', "cover");
			}
			reader.readAsDataURL(input.files[0]);
			abnv('savepic');
			change_state('saving');
		}
	}

	$("#imgInp").change(function() {
		readURL(this);
	});
	
	
	window.addEventListener("keydown", checkKeyPressed, false);
	 
	function checkKeyPressed(e) {
		if (e.keyCode == "13") {
			$(":focus").closest('form').find('.submit_button').click();
		}
	}

</script>