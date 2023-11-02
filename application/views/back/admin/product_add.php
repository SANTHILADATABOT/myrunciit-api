<div class="row">
    <div class="col-md-12">
		<?php
            echo form_open(base_url() . 'index.php/admin/product/do_add/', array(
                'class' => 'form-horizontal',
                'method' => 'post',
                'id' => 'product_add',
				'enctype' => 'multipart/form-data'
            ));
        ?>
            <!--Panel heading-->
            <div class="panel-heading">
                <div class="panel-control" style="float: left;">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#product_details" onclick="check_button('product_details');"><?php echo translate('product_details'); ?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#business_details" onclick="check_button('business_details');"><?php echo translate('business_details'); ?></a>
                        </li>
                        
                        
                        
                        <li>
                            <a data-toggle="tab" href="#customer_choice_options" onclick="check_button('customer_choice_options');"><?php echo translate('customer_choice_options'); ?></a>
                        </li>
                       
                         <li style="display:none;">
                            <a data-toggle="tab" href="#bidding_deatils" onclick="check_button('bidding_deatils');"><?php echo translate('product bidding'); ?></a>
                        </li>
                       
                        
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="tab-base">
                    <!--Tabs Content-->                    
                    <div class="tab-content">
                    	<div id="product_details" class="tab-pane fade active in">
        
                            <div class="form-group btm_border">
                                <h4 class="text-thin text-center"><?php echo translate('product_details'); ?></h4>                            
                            </div>

                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('choose_store');?></label>
                                <div class="col-sm-6">
                                    <?php echo $this->crud_model->select_html('vendor','vendor','name','add','demo-chosen-select required','','',NULL,''); ?>
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('product_title');?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="title" id="demo-hor-1" placeholder="<?php echo translate('product_title');?>" class="form-control required">
                                </div>
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('category');?></label>
                                <div class="col-sm-6">
                                    <?php echo $this->crud_model->select_html('category','category','category_name','add','demo-chosen-select required','','digital',NULL,'get_cat'); ?>
                                </div>
                            </div>
                            
                            <div class="form-group btm_border" id="sub" style="display:none;">
                                <label class="col-sm-4 control-label" for="demo-hor-3"><?php echo translate('sub-category');?></label>
                                <div class="col-sm-6" id="sub_cat">
                                </div>
                            </div>
                            
                            <div class="form-group btm_border" id="brn" style="display:none;">
                                <label class="col-sm-4 control-label" for="demo-hor-4"><?php echo translate('brand');?></label>
                                <div class="col-sm-6" id="brand">
                                </div>
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-5"><?php echo translate('unit_of_measurement');?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="unit" id="demo-hor-5" placeholder="<?php echo translate('unit_of_measurement_(e.g._kg,_pc_etc.)'); ?>" class="form-control unit required">
                                </div>
                            </div>              
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-11"><?php echo translate('tags');?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="tag" data-role="tagsinput" placeholder="<?php echo translate('tags');?>" class="form-control required">
                                </div>
                            </div>
                                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-12"><?php echo translate('images');?></label>
                                <div class="col-sm-6">
                                <span class="pull-left btn btn-default btn-file"> <?php echo translate('choose_file');?>
                                    <input type="file" multiple name="images[]" onchange="preview(this);" id="demo-hor-12" class="form-control required">
                                    </span>
                                    <br><br>
                                    <span id="previewImg" ></span>
                                </div>
                            </div>                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-18"><?php echo translate('product_video');?></label>
                                <div class="col-sm-6">
                                <span class="pull-left btn btn-default btn-file"> <?php echo translate('choose_file');?>
                                    <input type="file"  name="video" onchange="preview_video(this);" id="demo-hor-18" class="form-control required">
                                    </span>
                                    <p class="vidadd">Upload MP4 Video Format</p>
                                    <br><br>
                                    <div id="previewvedio"></div>
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-13"><?php echo translate('description'); ?></label>
                                <div class="col-sm-6">
                                    <textarea rows="9"  class="summernotes" data-height="200" data-name="description"></textarea>
                                </div>
                            </div>
                             <?php
                                $enq=$this->db->get_where('general_settings',array('type'=>'enquiry'))->row()->value;
                                //echo $this->db->last_query(); exit;
								if($enq=='ok') {
                                ?>
                            <div class="form-group btm_border auto_h">
                                <label class="col-sm-4 control-label" for="demo-hor-11"><?php echo translate('Enquiry_option');?></label>
                                <div class="col-sm-2">
                                <?php
                                $enq=$this->db->get_where('general_settings',array('type'=>'enquiry'))->row()->value;
                                //echo $this->db->last_query(); exit;
                                ?>
                                <label>
                                
                                <input type="radio" name="enquiry" id="watch-me" style="height:10px;" value="ok" class="3dc" <?php if($enq=='ok') { ?> checked <?php } ?>> Yes</label>
								 <label><input type="radio" name="enquiry" style="height:10px;" value="no" class="3dc" <?php if($enq=='no') { ?> checked <?php } ?>> No</label>
                                    
                                </div>
                            </div>
                            <?php } ?>
                            <?php
                                $subscribe=$this->db->get_where('general_settings',array('type'=>'subscribe'))->row()->value;
                                //echo $this->db->last_query(); exit;
								if($subscribe=='ok') {
                                ?>
                            <div class="form-group btm_border auto_h">
                                <label class="col-sm-4 control-label" for="demo-hor-11"><?php echo translate('subscribe_option');?></label>
                                <div class="col-sm-2">
                                <?php
                                $subscribe=$this->db->get_where('general_settings',array('type'=>'subscribe'))->row()->value;
                                //echo $this->db->last_query(); exit;
                                ?>
                                <label>
                                
                                <input type="radio" name="subscribe" id="watch-me" style="height:10px;" value="ok" class="3dc" <?php if($subscribe=='ok') { ?> checked <?php } ?>> Yes</label>
								 <label><input type="radio" name="subscribe" style="height:10px;" value="no" class="3dc" <?php if($subscribe=='no') { ?> checked <?php } ?>> No</label>
                                    
                                </div>
                            </div>
                            <?php } ?>
                            <?php
                                $call=$this->db->get_where('general_settings',array('type'=>'callnow'))->row()->value;
								if($call=='ok') {
                                ?>
                             <div class="form-group btm_border auto_h">
                                <label class="col-sm-4 control-label" for="demo-hor-11"><?php echo translate('display_phone');?></label>
                                <div class="col-sm-2">
                                <?php
                                $call=$this->db->get_where('general_settings',array('type'=>'callnow'))->row()->value;
                                ?>
                                <label><input type="radio" name="callnow" id="watch-me" style="height:10px;" value="ok" class="3dc" <?php if($call=='ok') { ?> checked <?php } ?>> Yes</label>
								 <label><input type="radio" name="callnow" style="height:10px;" value="no" class="3dc" <?php if($call=='no') { ?> checked <?php } ?>> No</label>
                                    
                                </div>
                            </div>
                            <?php } ?>
                             <div class="form-group btm_border auto_h hidden">
                                <label class="col-sm-4 control-label" for="demo-hor-11"><?php echo translate('AR');?></label>
                                <div class="col-sm-2">
                                
                                <label><input type="radio" name="ar" id="watch-me1" style="height:10px;" value="1" class="3dc"> Yes</label>
								 <label><input type="radio" name="ar" style="height:10px;" value="0" class="3dc" checked > No</label>
                                    
                                </div>
                            </div>
                            <div class="show1">
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-12"><?php echo translate('Upload_Android_GLB');?></label>
                                <div class="col-sm-6">
                                <span class="pull-left btn btn-default btn-file"> <?php echo translate('choose_file');?>
                                    <input type="file" name="images1" id="demo-hor-12" class="form-control">
                                    </span>
                                    <br><br>
                                    <span id="previewImg" ></span>
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-12"><?php echo translate('Upload_IOS_USDZ');?></label>
                                <div class="col-sm-6">
                                <span class="pull-left btn btn-default btn-file"> <?php echo translate('choose_file');?>
                                    <input type="file" name="images2" id="demo-hor-12" class="form-control">
                                    </span>
                                    <br><br>
                                    <span id="previewImg" ></span>
                                </div>
                            </div>
                            </div>
                            <div class="form-group btm_border" style="display:none;">
                                <label class="col-sm-4 control-label" for="demo-hor-11"><?php echo translate('3D');?></label>
                                <div class="col-sm-2">
                                <input type="checkbox" name="threed" style="height:10px;" value="1" class="form-control 3dc"> 
                                    
                                </div>
                            </div>
                            
                            <div class="form-group btm_border" style="display:none;">
                                <label class="col-sm-4 control-label" for="demo-hor-11"><?php echo translate('3D_url');?></label>
                                <div class="col-sm-6">
                               <input type="text" name="threed_url" style="height:30px;"  placeholder="<?php echo translate('3D_url');?>" class="form-control">
                                    
                                </div>
                            </div>
                            
                             <div class="form-group btm_border" style="display:none;">
                                <label class="col-sm-4 control-label" for="demo-hor-11"><?php echo translate('AR');?></label>
                                <div class="col-sm-2">
                                <input type="checkbox" name="ar" style="height:10px;" value="1" class="form-control arc"> 
                                    
                                </div>
                            </div>
                            
                             <div class="form-group btm_border" style="display:none;">
                                <label class="col-sm-4 control-label" for="demo-hor-11"><?php echo translate('ar_url');?></label>
                                <div class="col-sm-6">

                               <input type="text" name="ar_url" style="height:30px;"  placeholder="<?php echo translate('ar_url');?>" class="form-control arc">
                                    
                                </div>
                            </div>
                            
                            <div class="form-group btm_border" style="display:none;">
                                <label class="col-sm-4 control-label" for="demo-hor-11"><?php echo translate('QR_Code');?></label>
                                <div class="col-sm-2">
                                <input type="checkbox" name="qr" style="height:10px;" value="1" class="form-control arc"> 
                                    
                                </div>
                            </div>
                            
                             <div class="form-group btm_border" style="display:none;">
                                <label class="col-sm-4 control-label" for="demo-hor-11"><?php echo translate('qr_url');?></label>
                                <div class="col-sm-6">
                               <input type="text" name="qr_url" style="height:30px;"  placeholder="<?php echo translate('qr_image_url');?>" class="form-control arc">
                                    
                                </div>
                            </div>
                            
                            
                            <div id="more_additional_fields"></div>
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-inputpass"></label>
                                <div class="col-sm-6">
                                    <h4 class="pull-left">
                                        <i><?php echo translate('if_you_need_more_field_for_your_product_,_please_click_here_for_more...');?></i>
                                    </h4>
                                    <div id="more_btn" class="btn btn-mint btn-labeled fa fa-plus pull-right">
                                    <?php echo translate('add_more_fields');?></div>
                                </div>
                            </div>
                            

                        </div>
                        <div id="business_details" class="tab-pane fade">
                            <div class="form-group btm_border">
                                <h4 class="text-thin text-center"><?php echo translate('business_details'); ?></h4>                            
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-6"><?php echo translate('sale_price');?></label>
                                <div class="col-sm-4">
                                    <input type="number" name="sale_price" id="demo-hor-6" min='0' step='.01' placeholder="<?php echo translate('sale_price');?>" class="form-control required">
                                </div>
                                <span class="btn"><?php echo currency('','def'); ?> / </span>
                                <span class="btn unit_set"></span>
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-7"><?php echo translate('purchase_price');?></label>
                                <div class="col-sm-4">
                                    <input type="number" name="purchase_price" id="demo-hor-7" min='0' step='.01' placeholder="<?php echo translate('purchase_price');?>" class="form-control required">
                                </div>
                                <span class="btn"><?php echo currency('','def'); ?> / </span>
                                <span class="btn unit_set"></span>
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-8"><?php echo translate('purchase_shipping_cost');?></label>
                                <div class="col-sm-4">
                                    <input type="number" name="shipping_cost" id="demo-hor-8" min='0' step='.01' placeholder="<?php echo translate('purchase_shipping_cost');?>" class="form-control required">
                                </div>
                                <span class="btn"><?php echo currency('','def'); ?> / </span>
                                <span class="btn unit_set"></span>
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-9"><?php echo translate('purchase_product_tax');?></label>
                                <div class="col-sm-4">
                                    <input type="number" name="tax" id="demo-hor-9" min='0' step='.01' placeholder="<?php echo translate('purchase_product_tax');?>" class="form-control">
                                </div>
                                <div class="col-sm-1">
                                    <select class="demo-chosen-select" name="tax_type">
                                        <option value="percent">%</option>
                                        <option value="amount"><?php echo currency('','def'); ?></option>
                                    </select>
                                </div>
                                <span class="btn unit_set"></span>
                            </div>
                            <div class="form-group btm_border" style="display:none;">
                                <label class="col-sm-4 control-label" for="demo-hor-9"><?php echo translate('cashpack');?></label>
                                <div class="col-sm-4">
                                    <input type="number" name="cashpack" id="demo-hor-9" min='0' step='.01' placeholder="<?php echo translate('cashpack');?>" class="form-control">
                                </div>
                                <div class="col-sm-1">
                                    <select class="demo-chosen-select" name="cashpack_type">
                                        <option value="percent">%</option>
                                        <option value="amount">Flat</option>
                                    </select>
                                </div>
                                <span class="btn unit_set">/<?php echo $row['unit']; ?></span>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-10"><?php echo translate('sale_product_discount');?></label>
                                <div class="col-sm-4">
                                    <input type="number" name="discount" id="demo-hor-10" min='0' step='.01' placeholder="<?php echo translate('sale_product_discount');?>" class="form-control">
                                </div>
                                <div class="col-sm-1">
                                    <select class="demo-chosen-select" name="discount_type">
                                        <option value="percent">%</option>
                                        <option value="amount"><?php echo currency('','def'); ?></option>
                                    </select>
                                </div>
                                <span class="btn unit_set"></span>
                            </div>
                        </div>
                        
                        
                        
                        
                        <div id="customer_choice_options" class="tab-pane fade">
                            <div class="form-group btm_border">
                                <h4 class="text-thin text-center"><?php echo translate('customer_choice_options'); ?></h4>                            
                            </div>
                           <div class="form-group btm_border hidden">
                                <label class="col-sm-4 control-label" for="demo-hor-14"><?php echo translate('color'); ?></label>
                                <div class="col-sm-4"  id="more_colors">
                                  <div class="col-md-12" style="margin-bottom:8px;">
                                      <div class="col-md-10">
                                      <input type="text" value="" name="color_qty[]"  placeholder="Qty"  class="form-control" style="width:297px !important; display:none;" />
                                          <div class="input-group demo2">
                                          
                                               <input type="text" value="rgba(228,72,72,1)" name="color[]" class="form-control" />
                                               
                                               <span class="input-group-addon"><i></i></span>
                                            </div>
                                            
                                            
                                            
                                      </div>
                                      <span class="col-md-2">
                                          <span class="remove_it_v rmc btn btn-danger btn-icon icon-lg fa fa-trash" ></span>
                                      </span>
                                  </div>
                                </div>
                                <div class="col-sm-2">
                                    <div id="more_color_btn" class="btn btn-primary btn-labeled fa fa-plus">
                                        <?php echo translate('add_more_colors');?>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="more_additional_options"></div>
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-inputpass"></label>
                                <div class="col-sm-6">
                                    <h4 class="pull-left">
                                        <i><?php echo translate('if_you_need_more_choice_options_for_customers_of_this_product_,please_click_here.');?></i>
                                    </h4>
                                    <div id="more_option_btn" class="btn btn-mint btn-labeled fa fa-plus pull-right">
                                    <?php echo translate('add_customer_input_options');?></div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div id="bidding_deatils" class="tab-pane fade">
                            <div class="form-group btm_border">
                                <h4 class="text-thin text-center"><?php echo translate('bidding details'); ?></h4>                            
                            </div>
                            
                             <div class="form-group btm_border bidding_det">
                                <label class="control-label col-sm-4" for="demo-hor-6"><?php echo translate('need bidding');?></label>
                             <div class="col-sm-4">
                                    <input type="radio" name="product_bid" id="product_bid" value="1" style="margin-right:5px;">yes
                                	<input type="radio" name="product_bid"  value="0" style="margin-right:5px;" checked>no
                                    </div>
                               </div>
                            <div class="bidding">
                                
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-6"><?php echo translate('bidding start date');?></label>
                                <div class="col-sm-4">
                                    <input type="date" name="bid_start_date" id="bid_start_date" min='0' step='.01' placeholder="<?php echo translate('bidding start date');?>" class="form-control ">
                                </div>
                                <!--<span class="btn"><?php //echo currency('','def'); ?> / </span>
                                <span class="btn unit_set"></span>-->
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-7"><?php echo translate('bidding start time');?></label>
                                <div class="col-sm-4">
                                    <input type="text" name="bid_start_time" id="bid_start_time" min='0' step='.01' placeholder="<?php echo translate('bidding start time');?>" class="form-control ">
                                </div>
                                <!--<span class="btn"><?php //echo currency('','def'); ?> / </span>
                                <span class="btn unit_set"></span>-->
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-8"><?php echo translate('bidding end date');?></label>
                                <div class="col-sm-4">
                                    <input type="date" name="bid_end_date" id="bid_start_date" min='0' step='.01' placeholder="<?php echo translate('bidding end date');?>" class="form-control">
                                </div>
                               <!-- <span class="btn"><?php //echo currency('','def'); ?> / </span>
                                <span class="btn unit_set"></span>-->
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-9"><?php echo translate('bidding end time');?></label>
                                <div class="col-sm-4">
                                    <input type="text" name="bid_end_time" id="bid_end_time" min='0' step='.01' placeholder="<?php echo translate('bidding end time');?>" class="form-control">
                                </div>
                                <!--<div class="col-sm-1">
                                    <select class="demo-chosen-select" name="tax_type">
                                        <option value="percent">%</option>
                                        <option value="amount"><?php //echo currency('','def'); ?></option>
                                    </select>
                                </div>-->
                                <!--<span class="btn unit_set"></span>-->
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-10"><?php echo translate('maximum bidd amount');?></label>
                                <div class="col-sm-4">
                                    <input type="text" name="max_bid_amount" id="max_bid_amount" min='0' step='.01' placeholder="<?php echo translate('maximum bidd amount');?>" class="form-control">
                                </div>
                                <!--<div class="col-sm-1">
                                    <select class="demo-chosen-select" name="discount_type">
                                        <option value="percent">%</option>
                                        <option value="amount"><?php //echo currency('','def'); ?></option>
                                    </select>
                                </div>-->
                               <!-- <span class="btn unit_set"></span>-->
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-10"><?php echo translate('minimum bidd amount');?></label>
                                <div class="col-sm-4">
                                    <input type="text" name="min_bid_amount" id="min_bid_amount" min='0' step='.01' placeholder="<?php echo translate('minimum bidd amount');?>" class="form-control">
                                </div>
                                <!--<div class="col-sm-1">
                                    <select class="demo-chosen-select" name="discount_type">
                                        <option value="percent">%</option>
                                        <option value="amount"><?php //echo currency('','def'); ?></option>
                                    </select>
                                </div>-->
                               <!-- <span class="btn unit_set"></span>-->
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <span class="btn btn-purple btn-labeled fa fa-hand-o-right pull-right" id="next" onclick="next_tab()"><?php echo translate('next'); ?></span>
                <span class="btn btn-purple btn-labeled fa fa-hand-o-left pull-right" id="previous" onclick="previous_tab()"><?php echo translate('previous'); ?></span>
        
            </div>
    
            <div class="panel-footer">
                <div class="row">
                	<div class="col-md-10">
                        <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right " 
                            onclick="ajax_set_full('add','<?php echo translate('add_product'); ?>','<?php echo translate('successfully_added!'); ?>','product_add',''); "><?php echo translate('reset');?>
                        </span>
                    </div>
                    
                    <div class="col-md-2">
                        <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right enterer" onclick="form_submit('product_add','<?php echo translate('product_has_been_uploaded!'); ?>');proceed('to_add'); desc_validation();" ><?php echo translate('upload');?></span>
                    </div>
                    
                </div>
            </div>
    
        </form>
    </div>
</div>

<script src="<?php $this->benchmark->mark_time(); echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js">
</script>

<input type="hidden" id="option_count" value="-1">

<script>

    function check_button(val){
        if(val == 'product_details'){
            $('#previous').hide();
            $('#next').show();
        } else if(val == 'business_details'){
            $('#previous').show();
            $('#next').show();
        } else if(val == 'customer_choice_options'){
            $('#previous').show();
            $('#next').hide();
        } else if(val == 'bidding_deatils'){
            $('#previous').show();
            $('#next').hide();
        }
    }
    $(document).ready(function(){
        check_button('product_details');
    });

    window.preview = function (input) {
        if (input.files && input.files[0]) {
            $("#previewImg").html('');
            $(input.files).each(function () {
                var reader = new FileReader();
                reader.readAsDataURL(this);
                reader.onload = function (e) {
                    $("#previewImg").append("<div style='float:left;border:4px solid #303641;padding:5px;margin:5px;'><img height='80' src='" + e.target.result + "'></div>");
                }
            });
        }
    }

    window.preview_video = function(input) {
    if (input.files && input.files[0]) {
        $("#previewvedio").html(''); // Clear any previous previews
        $(input.files).each(function() {
            var reader = new FileReader();
            reader.readAsDataURL(this);
            reader.onload = function(e) {
                $("#previewvedio").append("<div style='float:left;border:4px solid #303641;padding:5px;margin:5px;'><video controls height='100'><source height='80' src='" + e.target.result + "'></video></div>");
            };
        });
    }
};

    function other_forms(){}
	
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

    function option_count(type){
        var count = $('#option_count').val();
        if(type == 'add'){
            count++;
        }
        if(type == 'reduce'){
            count--;
        }
        $('#option_count').val(count);
    }

    function set_select(){
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
    }
	
    $(document).ready(function() {
        set_select();
		set_summer();
		createColorpickers();
    });

    function other(){
        set_select();
        $('#sub').show('slow');
    }
    function get_cat(id,now){
        $('#sub').hide('slow');
        ajax_load(base_url+'index.php/admin/product/sub_by_cat/'+id,'sub_cat','other');
    }
	function get_brnd(id){
        $('#brn').hide('slow');
        ajax_load(base_url+'index.php/admin/product/brand_by_sub/'+id,'brand','other');
        $('#brn').show('slow');
    }
    function get_sub_res(id){}

    $(".unit").on('keyup',function(){
        $(".unit_set").html($(".unit").val());
    });

	function createColorpickers() {
	
		$('.demo2').colorpicker({
			format: 'rgba'
		});
		
	}
    
    $("#more_btn").click(function(){
        $("#more_additional_fields").append(''
            +'<div class="form-group">'
            +'    <div class="col-sm-4">'
            +'        <input type="text" name="ad_field_names[]" class="form-control required"  placeholder="<?php echo translate('field_name'); ?>">'
            +'    </div>'
            +'    <div class="col-sm-5">'
            +'        <textarea rows="9"  class="summernotes" data-height="100" data-name="ad_field_values[]"></textarea>'
            +'    </div>'
            +'    <div class="col-sm-2">'
            +'        <span class="remove_it_v rms btn btn-danger btn-icon btn-circle icon-lg fa fa-times" onclick="delete_row(this)"></span>'
            +'    </div>'
            +'</div>'
        );
        set_summer();
    });
    
    function next_tab(){
        $('.nav-tabs li.active').next().find('a').click();                    
    }
    function previous_tab(){
        $('.nav-tabs li.active').prev().find('a').click();                     
    }
    
    $("#more_option_btn").click(function(){
        option_count('add');
        var co = $('#option_count').val();
        $("#more_additional_options").append(''
            +'<div class="form-group" data-no="'+co+'">'
            +'    <div class="col-sm-4">'
            +'        <input type="text" name="op_title[]" class="form-control required"  placeholder="<?php echo translate('customer_input_title'); ?>">'
            +'    </div>'
            +'    <div class="col-sm-5">'
            +'        <select class="demo-chosen-select op_type required" name="op_type[]" >'
            +'            <option value="">(none)</option>'
                       
            +'            <option value="radio">Radio</option>'
            +'        </select>'
            +'        <div class="col-sm-12 options">'
            +'          <input type="hidden" name="op_set'+co+'[]" value="none" >'
            +'        </div>'
            +'    </div>'
            +'    <input type="hidden" name="op_no[]" value="'+co+'" >'
            +'    <div class="col-sm-2">'
            +'        <span class="remove_it_o rmo btn btn-danger btn-icon btn-circle icon-lg fa fa-times" onclick="delete_row(this)"></span>'
            +'    </div>'
            +'</div>'
        );
        set_select();
    });
    
    $("#more_additional_options").on('change','.op_type',function(){
        var co = $(this).closest('.form-group').data('no');
        if($(this).val() !== 'text' && $(this).val() !== ''){
            $(this).closest('div').find(".options").html(''
                +'    <div class="col-sm-12">'
                +'        <div class="col-sm-12 options margin-bottom-10"></div><br>'
                +'        <div class="btn btn-mint btn-labeled fa fa-plus pull-right add_op">'
                +'        <?php echo translate('add_options_for_choice');?></div>'
                +'    </div>'
            );
        } else if ($(this).val() == 'text' || $(this).val() == ''){
            $(this).closest('div').find(".options").html(''
                +'    <input type="hidden" name="op_set'+co+'[]" value="none" >'
            );
        }
    });
    
    $("#more_additional_options").on('click','.add_op',function(){
        var co = $(this).closest('.form-group').data('no');
        $(this).closest('.col-sm-12').find(".options").append(''
            +'    <div>'
            +'        <div class="col-sm-10">'
            +'          <input type="text" name="op_set'+co+'[]" class="form-control required"  placeholder="<?php echo translate('option_name'); ?>">'
            +'        </div>'
            +'        <div class="col-sm-2">'
            +'          <span class="remove_it_n rmon btn btn-danger btn-icon btn-circle icon-sm fa fa-times" onclick="delete_row(this)"></span>'
            +'        </div>'
            +'    </div>'
        );
    });
    
    $('body').on('click', '.rmo', function(){
        $(this).parent().parent().remove();
    });

    $('body').on('click', '.rmon', function(){
        var co = $(this).closest('.form-group').data('no');
        $(this).parent().parent().remove();
        if($(this).parent().parent().parent().html() == ''){
            $(this).parent().parent().parent().html(''
                +'   <input type="hidden" name="op_set'+co+'[]" value="none" >'
            );
        }
    });

    $('body').on('click', '.rms', function(){
        $(this).parent().parent().remove();
    });

    $("#more_color_btn").click(function(){
        $("#more_colors").append(''
            +'      <div class="col-md-12" style="margin-bottom:8px;">'
            +'          <div class="col-md-10">'
            +'              <div class="input-group demo2">'
			+'		     	   <input type="text" value="#ccc" name="color[]" class="form-control" />'
			+'		     	   <span class="input-group-addon"><i></i></span>'
			+'		        </div>'
            +'          </div>'
            +'          <span class="col-md-2">'
            +'              <span class="remove_it_v rmc btn btn-danger btn-icon icon-lg fa fa-trash" ></span>'
            +'          </span>'
            +'      </div>'
  		);
		createColorpickers();
    });		           

    $('body').on('click', '.rmc', function(){
        $(this).parent().parent().remove();
    });


	$(document).ready(function() {
		$("form").submit(function(e){
			event.preventDefault();
		});
	});
$(document).ready(function() {
		$(".show1").hide();
   $('.auto_h input[type="radio"]').click(function() {
    var a = $(this).val();
       //if($(this).attr('id') == 'watch-me') {
      if(a == 1){
            $('.show1').show();           
       }

      else {
            $('.show1').hide();   
       }
   });
});
$(document).ready(function() {
		$(".bidding").hide();
   $('.bidding_det input[type="radio"]').click(function() {
       if($(this).attr('id') == 'product_bid') {
            $('.bidding').show();           
       }
       else {
            $('.bidding').hide();   
       }
   });
});
</script>

<script>
function desc_validation()
{
	//alert("ghfhgdty");
	$( ".note-editable" ).each( function( i ) {
		//alert($( this ).text());
		if($( this ).text()==''){
		alert(i+1 + " Description Empty Value. Enter Description");
		$( this ).after('<span style="color:#fff;" class="require_alert label label-danger" >*Required</span>');
		return false;
		}
    
  } );
}

</script>

<style>
	.btm_border{
		border-bottom: 1px solid #ebebeb;
		padding-bottom: 15px;	
	}
	#bidding_deatils input[type="date"]{
		line-height:inherit;	
	}
</style>


<!--Bootstrap Tags Input [ OPTIONAL ]-->

