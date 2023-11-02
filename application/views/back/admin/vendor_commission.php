<div>  
	<?php
        echo form_open(base_url() . 'index.php/admin/vendor/update_commission/', array(
            'class' => 'form-horizontal',
            'method' => 'post',
            'enctype' => 'multipart/form-data',
            'id'=>'commission_set'
        ));
    ?>     
     <div class="panel-body"> 
    
        <div class="form-group">
        	
            <div class="col-sm-12">
                <div class="input-group">
                    <div class="col-md-4"><label>Commission Type</label></div>
                    <div class="col-md-8">
                        <?php 
                        $commission_type = $this->db->get_where('business_settings', array('type' => 'commission_type'))->row()->value;
                         ?>     
                        <input type="radio" name="commission_type" id="all_vendor" value="all_vendor" <? if($commission_type=='all_vendor') { echo 'checked="checked"';}?>><label>All Vendor</label>
                        <input type="radio" name="commission_type" id="specific_vendor" value="specific_vendor" <? if($commission_type=='specific_vendor') { echo 'checked="checked"';}?>><label>Specific Vendor</label>
                    </div>
                    
                </div>
            </div>
          </div>
          <? if($commission_type=='all_vendor'){
                $display='block';
              } else {
                  $display='none';
              }
          ?>
          <div class="form-group" >
            <div class="col-sm-12" id="amount" style="display:<? echo $display ?> ">
                <div class="input-group">
                    <div class="col-md-4">
                        <label>Commission Amount</label>
                    </div>
                    
                    <div class="col-md-8">
                        <?php 
                        $commission_amount = $this->db->get_where('business_settings', array('type' => 'commission_amount'))->row()->value;
                        ?>     
                        <input type="number" name="vendor_commission" value="<?php echo $commission_amount?>" max-length="2" class="form-control required" placeholder="In Percentage">
                    </div>
                    
                </div>
            </div>
          </div>  
       </div>
    </form>
</div>
<script>
$("#all_vendor").click(function(){
    $("#amount").show();
});
$("#specific_vendor").click(function(){
    $("#amount").hide();
});
</script>
