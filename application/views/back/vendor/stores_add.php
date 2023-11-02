<div>
	<?php
   echo form_open(base_url() . 'index.php/vendor/stores/do_add/', array(
   	'class' => 'form-horizontal',
   	'method' => 'post',
   	'id' => 'stores_add',
   	'enctype' => 'multipart/form-data'
   ));
   ?>
        <div class="panel-body">
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('Store Name:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                       <input type="text" name="store_name" class="form-control required" />
                      
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('Owner Name:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                       <input type="text" name="owner_name" class="form-control required" />
                       
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('Mobile Number:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                       <input type="number" class="form-control required" name="mobile"  maxlength="10" placeholder="Mobile Number" id="mobile">
                       
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('Landline Number:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                       <input type="number" class="form-control required" name="landline_num"  maxlength="10" placeholder="Landline Number" id="landline_num">
                       
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('Email Address:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                       <input type="email" class="form-control required" name="email" placeholder="Email ID" id="email">
                       
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('Address:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                       <input type="text" class="form-control required" name="address" placeholder="Address" id="address">
                       
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('Country:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                       <input type="text" class="form-control required" name="country" placeholder="Country" id="country">
                       
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('State:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                       <input type="text" class="form-control required" name="state" placeholder="State" id="state">
                       
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('City:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                       <input type="text" class="form-control required" name="city" placeholder="City" id="city">
                       
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('Postal Code:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                       <input type="number" class="form-control required" name="postal_code" placeholder="postal_code" id="postal_code">
                       
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('Profile Picture:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                      <input type="file" name="prifileimg" id='imgInp' accept="image">
                       
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('Cover Picture:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                     <input type="file" name="coverimg" id='imgInp1' accept="image">
                       
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('Upload Document:');?></label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                     <input type="file" name="passport_copy" id='passport_copy' accept="image">
                       
                    </div>
                </div>
            </div>
           
        </div>
	</form>
</div>



