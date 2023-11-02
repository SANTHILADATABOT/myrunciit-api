<div id="content-container">
<div class="content-wrapper-before"></div>

    <div class="tab-base">
        <div class="panel">
		
		   <div class="row">
			  <div class="col-md-12">
			    <h1 class="page-header text-overflow"><?php echo translate('Third Party Settings'); ?></h1>
			  </div>
		    </div>
		
            <div class="tab-base tab-stacked-left">
                <ul class="nav nav-tabs">
 
                    <li class="active">
                        <a data-toggle="tab" href="#demo-stk-lft-tab-1"><?php echo translate('captcha_settings');?></a>
                    </li>
                     <li>
                        <a data-toggle="tab" href="#demo-stk-lft-tab-2"><?php echo translate('social_login_configuaration');?></a>
                    </li>
                    <li style="display:none;">
                        <a data-toggle="tab" href="#demo-stk-lft-tab-3"><?php echo translate('product_comment_settings');?></a>
                    </li>
                   	<li>
                        <a data-toggle="tab" href="#demo-stk-lft-tab-4"><?php echo translate('google_map');?></a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#demo-stk-lft-tab-5"><?php echo translate('google_analytics');?></a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#demo-stk-lft-tab-6"><?php echo translate('facebook_pixel_configuration');?></a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#demo-stk-lft-tab-7"><?php echo translate('facebook_chat_configuration');?></a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#demo-stk-lft-tab-8"><?php echo translate('sms');?></a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#demo-stk-lft-tab-9"><?php echo translate('email');?></a>
                    </li>
                </ul>

                <div class="tab-content bg_grey">
                 <!--UPLOAD : general settings ---------->
                    <!--UPLOAD : captcha settings ---------->
                    <div id="demo-stk-lft-tab-1" class="tab-pane fade active in">
                        <div class="col-md-12">
                            <div class="panel-inr">
                                <div class="margin-bottom-15">
                                    <h3 class="panel-title"><?php echo translate('save_captcha_settings');?></h3>
                                </div>
                                <?php $cpub =  $this->db->get_where('general_settings',array('type' => 'captcha_public'))->row()->value;?>
                                <?php $cprv =  $this->db->get_where('general_settings',array('type' => 'captcha_private'))->row()->value;?>
								<?php
                                    echo form_open(base_url() . 'index.php/admin/captcha_settings/', array(
                                        'class' => 'form-horizontal',
                                        'method' => 'post',
                                        'id' => '',
                                        'enctype' => 'multipart/form-data'
                                    ));
                                ?>
                                	<div class="form-group">
                                        <label class="col-sm-2 control-label" >
											<?php echo translate('captcha_status');?>
                                    	</label>
                                        <div class="col-sm-8">
                                            <input id="captcha_status" class='sw4' data-set='captcha_status' type="checkbox" <?php if($this->crud_model->get_settings_value('general_settings','captcha_status','value') == 'ok'){ ?>checked<?php } ?> />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
											<?php echo translate('public_key');?>
                                            	</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="cpub" value="<?php echo $cpub; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
											<?php echo translate('private_key');?>
                                            	</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="cprv" value="<?php echo $cprv; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="panel-footer text-center">
                                        <span class="btn btn-success btn-labeled fa fa-check submitter enterer"  data-ing='<?php echo translate('saving'); ?>' data-msg='<?php echo translate('settings_updated!'); ?>'>
                                            <?php echo translate('save');?>
                                        </span>
                                    </div>
                                </form> 
                            </div>                
                        </div>
                    </div>
                     <!--UPLOAD : SOCIAL login settings---------->
                    <div id="demo-stk-lft-tab-2" class="tab-pane fade">
                        <div class="col-md-12">
                            <div class="panel-inr">
                <?php 
                    $appid =  $this->db->get_where('general_settings',array('type' => 'fb_appid'))->row()->value;
                    $secret =  $this->db->get_where('general_settings',array('type' => 'fb_secret'))->row()->value;                    
                    $application_name =  $this->db->get_where('general_settings',array('type' => 'application_name'))->row()->value;
                    $client_id =  $this->db->get_where('general_settings',array('type' => 'client_id'))->row()->value;
                    $client_secret =  $this->db->get_where('general_settings',array('type' => 'client_secret'))->row()->value;
                    $redirect_uri =  $this->db->get_where('general_settings',array('type' => 'redirect_uri'))->row()->value;
                    $api_key =  $this->db->get_where('general_settings',array('type' => 'fb_secret'))->row()->value;
					$fb_login_set = $this->crud_model->get_type_name_by_id('general_settings','51','value');
					$g_login_set = $this->crud_model->get_type_name_by_id('general_settings','52','value');
					 $g_analytics_set = $this->crud_model->get_type_name_by_id('general_settings','80','value');
                    $api_key = $this->db->get_where('general_settings',array('type'=>'api_key'))->row()->value;
					$kaleyra_sms_set = $this->crud_model->get_type_name_by_id('general_settings','110','value');
					$twilio_sms_set = $this->crud_model->get_type_name_by_id('general_settings','111','value');
					$k_sid = $this->crud_model->get_type_name_by_id('general_settings','112','value');
					$k_apikey = $this->crud_model->get_type_name_by_id('general_settings','113','value');
					$k_sender = $this->crud_model->get_type_name_by_id('general_settings','114','value');
					$t_account_sid = $this->crud_model->get_type_name_by_id('general_settings','115','value');
					$t_auth_token = $this->crud_model->get_type_name_by_id('general_settings','116','value');
					$twilio_number = $this->crud_model->get_type_name_by_id('general_settings','117','value');
					$elastic_mail_set=$this->crud_model->get_type_name_by_id('general_settings','118','value');
					$elastic_apikey=$this->crud_model->get_type_name_by_id('general_settings','119','value');
					
                ?>
							<?php
                                echo form_open(base_url() . 'index.php/admin/social_login_settings/', array(
                                    'class' => 'form-horizontal',
                                    'method' => 'post',
                                    'id' => '',
                                    'enctype' => 'multipart/form-data'
                                ));
                            ?>
                            <style>
                        	<?php if($fb_login_set !== 'ok'){ ?>
								.fb_log_ins{
									display: none;
								}
                        	<?php } if($g_login_set !== 'ok'){ ?>
								.g_log_ins{
									display: none;
								}
                        	<?php } ?>
							</style>
                                    <div class="margin-bottom-15">
                                        <h3 class="panel-title"><?php echo translate('facebook_login_settings');?></h3> 
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail"><?php echo translate('status');?></label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input id="fb_login_set" class='sw5' data-set='fb_login_set' type="checkbox" <?php if($fb_login_set == 'ok'){ ?>checked<?php } ?> />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group fb_log_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">App ID</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="appid" value="<?php echo $appid; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group fb_log_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail"><?php echo translate('secret');?></label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="secret" value="<?php echo $secret; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="panel-heading margin-bottom-15">
                                        <h3 class="panel-title"><?php echo translate('google+_login_settings');?></h3>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail"><?php echo translate('status');?></label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input id="g_login_set" class='sw5' data-set='g_login_set' type="checkbox" <?php if($g_login_set == 'ok'){ ?>checked<?php } ?> />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group g_log_ins" >
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
											<?php echo translate('application_name');?>
                                      	</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="application_name" value="<?php echo $application_name; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="form-group g_log_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        	<?php echo translate('client');?> ID
                                    	</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="client_id" value="<?php echo $client_id; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group g_log_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        	<?php echo translate('client_secret');?>
                                       	</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="client_secret" value="<?php echo $client_secret; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group g_log_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
											<?php echo translate('redirect');?> URI
                                       	</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="redirect_uri" value="<?php echo $redirect_uri; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group g_log_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
											API <?php echo translate('key');?>
                                       	</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="api_key" value="<?php echo $api_key; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="panel-footer text-center">
                                        <span class="btn btn-success btn-labeled fa fa-check submitter enterer"  data-ing='<?php echo translate('saving'); ?>' data-msg='<?php echo translate('settings_updated!'); ?>'>
                                            <?php echo translate('save');?>
                                        </span>
                                    </div>
                                </form> 
                            </div>                
                        </div>
                    </div>
                    <span id="genset"></span>
                    <!--UPLOAD : SOCIAL media config END---------->
                    <div id="demo-stk-lft-tab-3" class="tab-pane fade">
                        <div class="col-md-12">
                            <div class="panel-inr">
                            <?php 
                                $discus_id = $this->db->get_where('general_settings',array('type'=>'discus_id'))->row()->value;
                                $fb_id = $this->db->get_where('general_settings',array('type'=>'fb_comment_api'))->row()->value;
                                $comment_type = $this->db->get_where('general_settings',array('type'=>'comment_type'))->row()->value;
                            ?>
								<?php
                                    echo form_open(base_url() . 'index.php/admin/product_comment/', array(
                                        'class' => 'form-horizontal',
                                        'method' => 'post',
                                        'id' => '',
                                        'enctype' => 'multipart/form-data'
                                    ));
                                ?>
                                    <div class="margin-bottom-15">
                                        <h3 class="panel-title"><?php echo translate('product_comment_settings');?></h3>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                            <?php echo translate('type');?>
                                                </label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <select class="demo-chosen-select" name="type">
                                                    <option value=""><?php echo translate('none'); ?></option>
                                                    <option value="facebook" <?php if($comment_type == 'facebook'){ ?>selected<?php } ?>><?php echo translate('facebook_comment'); ?></option>
                                                    <option value="disqus" <?php if($comment_type == 'disqus'){ ?>selected<?php } ?>><?php echo translate('disqus_comment'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                            <?php echo translate('discus_ID');?>
                                                </label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="discus_id" value="<?php echo $discus_id; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                            <?php echo translate('fb_comment_id');?>
                                                </label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="fb_comment_api" value="<?php echo $fb_id; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="panel-footer text-center">
                                        <span class="btn btn-success btn-labeled fa fa-check submitter enterer"  data-ing='<?php echo translate('saving'); ?>' data-msg='<?php echo translate('settings_updated!'); ?>'>
                                            <?php echo translate('save');?>
                                        </span>
                                    </div>
                                </form> 
                            </div>                
                        </div>
                    </div>
                    <div id="demo-stk-lft-tab-4" class="tab-pane fade">
                        <div class="col-md-12">
                            <div class="panel-inr">
                            <?php 
                                $api_key = $this->db->get_where('general_settings',array('type'=>'google_api_key'))->row()->value;
                            ?>
								<?php
                                    echo form_open(base_url() . 'index.php/admin/google_api_key/', array(
                                        'class' => 'form-horizontal',
                                        'method' => 'post',
                                        'id' => '',
                                        'enctype' => 'multipart/form-data'
                                    ));
                                ?>
                                    <div class="margin-bottom-15">
                                        <h3 class="panel-title"><?php echo translate('google_map_api_settings');?></h3>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputkey">
                                            <?php echo translate('api_key');?>
                                        </label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-8">
                                                <div class="col-sm-">
                                                    <input type="text" name="api_key" value="<?php echo $api_key; ?>" class="form-control" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="panel-footer text-center">
                                        <span class="btn btn-success btn-labeled fa fa-check submitter enterer"  data-ing='<?php echo translate('saving'); ?>' data-msg='<?php echo translate('settings_updated!'); ?>'>
                                            <?php echo translate('save');?>
                                        </span>
                                    </div>
                                </form> 
                            </div>                
                        </div>
                    </div>
                     <div id="demo-stk-lft-tab-5" class="tab-pane fade">
                        <div class="col-md-12">
                            <div class="panel-inr">
                            <?php 
                                $analytics_key = $this->db->get_where('general_settings',array('type'=>'google_analytics_key'))->row()->value;
                            ?>
                                <?php
                                    echo form_open(base_url() . 'index.php/admin/google_analytics_key/', array(
                                        'class' => 'form-horizontal',
                                        'method' => 'post',
                                        'id' => '',
                                        'enctype' => 'multipart/form-data'
                                    ));
                                ?>
                                    <div class="margin-bottom-15">
                                        <h3 class="panel-title"><?php echo translate('google_analytics_settings');?></h3>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail"><?php echo translate('status');?></label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input id="g_analytics_set" class='sw5' data-set='g_analytics_set' type="checkbox" <?php if($g_analytics_set == 'ok'){ ?>checked<?php } ?> />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group g_analy_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputkey">
                                            <?php echo translate('tracking_id');?>
                                        </label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-8">
                                                <div class="col-sm-">
                                                    <input type="text" name="tracking_id" value="<?php echo $analytics_key; ?>" class="form-control" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="panel-footer text-right">
                                        <span class="btn btn-success btn-labeled fa fa-check submitter enterer"  data-ing='<?php echo translate('saving'); ?>' data-msg='<?php echo translate('settings_updated!'); ?>'>
                                            <?php echo translate('save');?>
                                        </span>
                                    </div>
                                </form> 
                            </div>                
                        </div>
                    </div>
                    <!--UPLOAD : captcha settings ---------->
                    <div id="demo-stk-lft-tab-6" class="tab-pane fade">
                        <div class="col-md-12">
                            <div class="panel-inr">
                                <div class="margin-bottom-15">
                                    <h3 class="panel-title"><?php echo translate('Facebook pixel settings');?></h3>
                                </div>
                                <?php
                                echo form_open(base_url() . 'index.php/admin/facebook_pixel_settings/', array(
                                    'class' => 'form-horizontal',
                                    'method' => 'post',
                                    'id' => '',
                                    'enctype' => 'multipart/form-data'
                                ));
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >
                                        <?php echo translate('facebook_pixel_set');?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input id="facebook_pixel_set" class='swpixel' data-set='facebook_pixel_set' type="checkbox" <?php if($this->crud_model->get_settings_value('general_settings','facebook_pixel_set','value') == 'ok'){ ?>checked<?php } ?> />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        <?php echo translate('Facebook pixel id');?>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="col-sm-">
                                            <input type="text" name="facebook_pixel_id" value="<?php echo $this->crud_model->get_settings_value('general_settings','facebook_pixel_id','value'); ?>" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="panel-footer text-right">
                                        <span class="btn btn-success btn-labeled fa fa-check submitter enterer"  data-ing='<?php echo translate('saving'); ?>' data-msg='<?php echo translate('settings_updated!'); ?>'>
                                            <?php echo translate('save');?>
                                        </span>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="demo-stk-lft-tab-7" class="tab-pane fade">
                        <div class="col-md-12">
                            <div class="panel-inr">
                                <div class="margin-bottom-15">
                                    <h3 class="panel-title"><?php echo translate('Facebook chat settings');?></h3>
                                </div>
                                <?php
                                echo form_open(base_url() . 'index.php/admin/facebook_chat_settings/', array(
                                    'class' => 'form-horizontal',
                                    'method' => 'post',
                                    'id' => '',
                                    'enctype' => 'multipart/form-data'
                                ));
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >
                                        <?php echo translate('facebook_chat_set');?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input id="facebook_chat_set" class='swpixel' data-set='facebook_chat_set' type="checkbox" <?php if($this->crud_model->get_settings_value('general_settings','facebook_chat_set','value') == 'ok'){ ?>checked<?php } ?> />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        <?php echo translate('Facebook page id');?>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="col-sm-">
                                            <input type="text" name="facebook_chat_page_id" value="<?php echo $this->crud_model->get_settings_value('general_settings','facebook_chat_page_id','value'); ?>" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        <?php echo translate('Facebook chat logged in greeting');?>(<small><?php echo translate('Optional');?></small>)
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="col-sm-">
                                            <input type="text" name="facebook_chat_logged_in_greeting" value="<?php echo $this->crud_model->get_settings_value('general_settings','facebook_chat_logged_in_greeting','value'); ?>" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        <?php echo translate('Facebook chat logged out greeting');?>(<small><?php echo translate('Optional');?></small>)
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="col-sm-">
                                            <input type="text" name="facebook_chat_logged_out_greeting" value="<?php echo $this->crud_model->get_settings_value('general_settings','facebook_chat_logged_out_greeting','value'); ?>" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        <?php echo translate('Facebook chat theme color');?> (<small><?php echo translate('Optional');?></small>)
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="col-sm-">
                                            <input type="color" name="facebook_chat_theme_color" value="<?php echo $this->crud_model->get_settings_value('general_settings','facebook_chat_theme_color','value'); ?>" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="panel-footer text-right">
                                        <span class="btn btn-success btn-labeled fa fa-check submitter enterer"  data-ing='<?php echo translate('saving'); ?>' data-msg='<?php echo translate('settings_updated!'); ?>'>
                                            <?php echo translate('save');?>
                                        </span>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="demo-stk-lft-tab-8" class="tab-pane fade">
                        <div class="col-md-12">
                            <div class="panel-inr">
               
							<?php
                                echo form_open(base_url() . 'index.php/admin/sms_settings/', array(
                                    'class' => 'form-horizontal',
                                    'method' => 'post',
                                    'id' => '',
                                    'enctype' => 'multipart/form-data'
                                ));
                            ?>
                            <style>
                        	<?php if($kaleyra_sms_set !== 'ok'){ ?>
								.kaleyra_sms_ins{
									display: none;
								}
                        	
                        	<?php } if($twilio_sms_set !== 'ok'){ ?>
								.twilio_sms_ins{
									display: none;
								}
                        	<?php } ?>
							</style>
                                    <div class="margin-bottom-15">
                                        <h3 class="panel-title"><?php echo translate('kaleyra_settings');?></h3> 
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail"><?php echo translate('status');?></label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input id="kaleyra_sms_set" class='sw5' data-set='kaleyra_sms_set' type="checkbox" <?php if($kaleyra_sms_set == 'ok'){ ?>checked<?php } ?> />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group kaleyra_sms_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">SID</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="k_sid" value="<?php echo $k_sid; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group kaleyra_sms_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail"><?php echo translate('apikey');?></label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="k_apikey" value="<?php echo $k_apikey; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="form-group kaleyra_sms_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail"><?php echo translate('sender');?></label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="k_sender" value="<?php echo $k_sender; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="panel-heading margin-bottom-15">
                                        <h3 class="panel-title"><?php echo translate('twilio_settings');?></h3>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail"><?php echo translate('status');?></label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input id="twilio_sms_set" class='sw5' data-set='twilio_sms_set' type="checkbox" <?php if($twilio_sms_set == 'ok'){ ?>checked<?php } ?> />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group twilio_sms_ins" >
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
											<?php echo translate('account_sid');?>
                                      	</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="t_account_sid" value="<?php echo $t_account_sid; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="form-group twilio_sms_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        	<?php echo translate('auth_token');?> ID
                                    	</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="t_auth_token" value="<?php echo $t_auth_token; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group twilio_sms_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        	<?php echo translate('twilio_number');?>
                                       	</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="twilio_number" value="<?php echo $twilio_number; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <br />
                                    <div class="panel-footer text-center">
                                        <span class="btn btn-success btn-labeled fa fa-check submitter enterer"  data-ing='<?php echo translate('saving'); ?>' data-msg='<?php echo translate('settings_updated!'); ?>'>
                                            <?php echo translate('save');?>
                                        </span>
                                    </div>
                                </form> 
                            </div>                
                        </div>
                    </div>
                    
                    
                    <div id="demo-stk-lft-tab-9" class="tab-pane fade">
                        <div class="col-md-12">
                            <div class="panel-inr">
               
							<?php
                                echo form_open(base_url() . 'index.php/admin/sms_settings/', array(
                                    'class' => 'form-horizontal',
                                    'method' => 'post',
                                    'id' => '',
                                    'enctype' => 'multipart/form-data'
                                ));
                            ?>
                            <style>
                        	<?php if($elastic_mail_set !== 'ok'){ ?>
								.elastic_mail_ins{
									display: none;
								}
                        	
                        	
                        	<?php } ?>
							</style>
                                    <div class="margin-bottom-15">
                                        <h3 class="panel-title"><?php echo translate('elastic_mail_settings');?></h3> 
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail"><?php echo translate('status');?></label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input id="elastic_mail_set" class='sw5' data-set='elastic_mail_set' type="checkbox" <?php if($elastic_mail_set == 'ok'){ ?>checked<?php } ?> />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group elastic_mail_ins">
                                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">elastic_apikey</label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-">
                                                <input type="text" name="elastic_apikey" value="<?php echo $elastic_apikey; ?>" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                   
                                    
                                    <br />
                                    <div class="panel-footer text-center">
                                        <span class="btn btn-success btn-labeled fa fa-check submitter enterer"  data-ing='<?php echo translate('saving'); ?>' data-msg='<?php echo translate('settings_updated!'); ?>'>
                                            <?php echo translate('save');?>
                                        </span>
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
<div style="display:none;" id="site"></div>
<!-- for logo settings -->
<script>
    var base_url = '<?php echo base_url(); ?>'
    var user_type = 'admin';
    var module = 'general_settings';
    var list_cont_func = '';
    var dlt_cont_func = '';

$(document).ready(function() {
	$('.demo-chosen-select').chosen();
	$('.demo-cs-multiselect').chosen({
		width: '100%'
	});
	set_summer();
});

function set_summer(){
	$('.summernotes').each(function() {
		var now = $(this);
		var h = now.data('height');
		var n = now.data('name');
		if(now.closest('div').find('.val').length == 0){
			now.closest('div').append('<input type="hidden" class="val" name="'+n+'">');
		}
		now.summernote({
			height: h,
			onChange: function() {
				now.closest('div').find('.val').val(now.code());
			}
		});
		now.closest('div').find('.val').val(now.code());
	});
}

$(document).ready(function() {
	$("form").submit(function(e){
		return false;
	});

});

</script>
<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js">
</script>

