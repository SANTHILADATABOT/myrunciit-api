<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/marketing_vendor/do_add/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'marketing_vendor_add'
		));
	?>
        <div class="panel-body">
            <div class="form-group margin-top-15">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('owner_name'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="name" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('owner_name'); ?>" >
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('display_name'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="display_name" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('display_name'); ?>" >
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('email'); ?></label>
                <div class="col-sm-6">
                    <input type="email" name="email" id="demo-hor-1" class="form-control emails required" placeholder="<?php echo translate('email'); ?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('phone'); ?></label>
                <div class="col-sm-6">
                    <input type="number" name="phone" id="demo-hor-1" class="form-control emails required" placeholder="<?php echo translate('phone'); ?>" >
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('password'); ?></label>
                <div class="col-sm-6">
                    <input type="password" name="password" id="demo-hor-1" class="form-control pass1 required" placeholder="<?php echo translate('password'); ?>" >
                </div>
            </div>
            
        
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('company_name'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="company" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('company_name'); ?>" >
                </div>
            </div>
             <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('address_line_1'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="address1" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('address_line_1'); ?>" >
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('address_line_2'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="address2" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('address_line_2'); ?>" >
                </div>
            </div>
            
             <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('city'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="city" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('city'); ?>" >
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('state'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="state" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('state'); ?>" >
                </div>
            </div>
            
             <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('country'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="country" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('country'); ?>" >
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('zip'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="zip" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('zip'); ?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('geo_location'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="geo_loc" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('geo_location'); ?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('cover_photo'); ?></label>
                <div class="col-sm-6">
                    <input type="file" name="banner" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('cover_photo'); ?>" >
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('facebook'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="facebook" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('facebook'); ?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('instagram'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="instagram" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('instagram'); ?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('twitter'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="twitter" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('twitter'); ?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('youtube'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="youtube" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('youtube'); ?>" >
                </div>
            </div>
             <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('package'); ?></label>
                <div class="col-sm-6">
                   
                    <label class="radio-inline">
                    <input type="radio" name="pack" class=" required" value="1"> Priority Pack
                    </label>
                    
<label class="radio-inline">
<input type="radio" name="pack" class="required" value="2"> 
	Common Pack 
</label>
   
   <label class="radio-inline">
	<input type="radio" name="pack" class=" required" value="3"> 
		Similarity Pack 
	</label>

 <label class="radio-inline">
<input type="radio" name="pack" class=" required" value="4"> Banner </label>
</div>
</div>
            
            
       <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('accountant_name'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="account_name" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('accountant_name'); ?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('account_number'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="account_number" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('account_number'); ?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('ifsc_code'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="ifsc_code" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('ifsc_code'); ?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('branch'); ?></label>
                <div class="col-sm-6">
                    <input type="text" name="branch" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('branch'); ?>" >
                </div>
            </div>     
           
            
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-6">
                    <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-left" 
                        onclick="ajax_set_full('add','<?php echo translate('add_vendor'); ?>','<?php echo translate('successfully_added!'); ?>','marketing_vendor_add','')">
                        	<?php echo translate('reset');?>
                    </span>
                </div>
                
                <div class="col-md-6">
                    <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right" 
                    	onclick="form_submit('marketing_vendor_add')" >
                        	<?php echo translate('Proceed');?>
                    </span>
                </div>
            </div>
        </div>
	</form>
</div>

<script>
	$(document).ready(function() {
		$("form").submit(function(e){
			return false;
		});
		$(".sw2").each(function(){
			new Switchery(document.getElementById('per_'+$(this).data('id')), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
		});
	});
</script>