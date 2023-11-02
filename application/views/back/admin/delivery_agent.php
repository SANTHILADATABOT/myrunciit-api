<div>
	<?php
//echo "sid".$sale_id; exit;
	//echo "vid".$this->session->userdata('vendor_id'); exit;
       // if($this->crud_model->is_sale_of_vendor($sale_id,$this->session->userdata('vendor_id'))){
        echo form_open(base_url() . 'index.php/admin/sales/delivery_agent_set/' . $sale_id, array(
            'class' => 'form-horizontal',
            'method' => 'post',
            'id' => 'delivery_agent',
            'enctype' => 'multipart/form-data'
        ));
    ?>
        <div class="panel-body">
			
                

            <div class="form-group">
                
                <div class="row">
                <div class="clearfix">
                <div class="col-sm-4">
                <label class="control-label" for="demo-hor-2"><?php echo translate('delivery_agent'); ?></label>
                </div>
               
                	<?php /*?><?php
                    	$from = array('pending','on_delivery','delivered');
						echo $this->crud_model->select_html($from,'delivery_status','','edit','demo-chosen-select',$delivery_status);
					?><?php */?>
                     <div class="col-sm-8 countdelivery">
                     <?php $att=0; echo $this->crud_model->select_html1('delivery_agent','delivery_agent','agent','add','demo-chosen-select','','','','','',$att); 
					 
					 
					 ?>
                     </div>
                     </div>
                     <div class="clearfix" style="margin-bottom:15px;">
                     <div class="col-sm-4">
                     <label class="control-label" for="demo-hor-2">Courier Pickup Date</label>
                     </div>
                     <div class="col-sm-8">
                     <input type="date" style="width:80%;" class="form-control" name="delivery_pickup_date" id="delivery_pickup_date" placeholder="pickup_date" />
                     </div>
                     </div>
                     
                     <div class="clearfix" style="margin-bottom:15px;">
                     <div class="col-sm-4">
                     <label class="control-label" for="demo-hor-2">Courier Pickup time</label>
                     </div>
                     <div class="col-sm-8">
                     <input type="text" style="width:80%;" name="delivery_pickup_time" class="form-control" id="delivery_pickup_time" placeholder="00:00:00 AM/PM" />
                    </div>
                    
                </div>
            </div>

        </div>
    </form>
    <?php
     //   }
    ?>
</div>


