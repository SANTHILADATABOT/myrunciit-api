	<div class="panel-body" id="demo_s">
			<?php
			echo form_open(base_url() . 'index.php/admin/delivery_type/update/' , array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'delivery_type_edit'
			));
		?>
		<style>
.button {
  background-color: #008CBA;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}

input[type="checkbox"]:focus{
    border:0px;
    outline:0px;
}

input[type="radio"], input[type="checkbox"] {
	margin: 4px 0 0;
	margin-top: 1px \9;
	line-height: normal;
	-webkit-appearance: none;
	border: 0px;
}

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 70px;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
  width:65px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>
            <div class="panel-body">
                <?php $all_amount;
                 $delivery= $this->db->get_where('business_settings',array('type'=>'delivery'))->row()->status; 
                // echo $this->db->last_query();
                 $pickup= $this->db->get_where('business_settings',array('type'=>'pickup'))->row()->status;
                 $free_delivery= $this->db->get_where('business_settings',array('type'=>'free_delivery'))->row()->value;
                 $delivery_fee= $this->db->get_where('business_settings',array('type'=>'delivery_fee'))->row()->value;
            
                ?>
                
                
            <div class="col-md-6 hidden">    
                
                <div class="form-group margin-top-15">
                    <label class="col-sm-4 control-label switch" for="pickup"><?php echo translate('Pickup'); ?>
                    
                       <input type="checkbox" name="pickup" class="form-control placeholder name" <?php if($pickup=='ok'){ echo 'checked'; } ?> data-toggle="tooltip" title="<?php echo translate('name');?>" 
                                id="pickup" size="30" />
                                
                                <span class="slider"></span> 
                                
                                </label>
                    
                    
                </div>
                
                <div class="form-group margin-top-15">
                    <label class="col-sm-4 control-label switch" for="delivery"><?php echo translate('Delivery'); ?>
                    
                       <input type="checkbox" name="delivery" class="form-control placeholder name" <?php if($delivery=='ok'){ echo 'checked'; } ?> data-toggle="tooltip" title="<?php echo translate('name');?>" 
                                id="delivery" size="30" />
                                
                                 <span class="slider"></span>
                                 
                        </label>         
                                 
                   
                    
                </div>
                
            </div>    
                
                
             <div class="col-md-6">
                 <div class="form-group margin-top-15">
                    <label class="col-sm-6 control-label" for="demo-hor-1"><?php echo translate('Free_delivery_starting_from (RM)'); ?></label>
                    <div class="col-sm-6">
                       <input type="text"class="form-control placeholder name"  data-toggle="tooltip" title="<?php echo translate('name');?>" 
                                name="amount" id="amount" size="30" placeholder="<?php echo translate('eg:3000');?>" value="<?php echo $free_delivery;?>"/>
                    </div>
                </div>
                <div class="form-group margin-top-15">
                    <label class="col-sm-6 control-label" for="demo-hor-1"><?php echo translate('Delivery_fee'); ?></label>
                    <div class="col-sm-6">
                       <input type="text"class="form-control placeholder name"  data-toggle="tooltip" title="<?php echo translate('name');?>" 
                                name="fee" id="fee" size="30" placeholder="<?php echo translate('eg:3000');?>" value="<?php echo $delivery_fee;?>"/>
                    </div>
                </div>
               </div> 
           <div class="col-md-12">
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right" 
                            onclick="ajax_set_full('edit','<?php echo translate('edit_event'); ?>','<?php echo translate('successfully_edited!'); ?>','delivery_type_edit','')">
                                <?php echo translate('reset');?>
                        </span>
                        
                        <?php if($user_rights_30_0['edit_rights']=='1'){ ?>
                        <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right" 
                            onclick="form_submit('delivery_type_edit','<?php echo translate('successfully_edited!'); ?>')" >
                                <?php echo translate('Update');?>
                        </span>
                        <?php } ?>
                    </div>
                </div>
            </div>
            </div>
		</form>
	</div>
           


