<?php
	$physical_system   	 =  $this->crud_model->get_type_name_by_id('general_settings','68','value');
	$digital_system   	 =  $this->crud_model->get_type_name_by_id('general_settings','69','value');
	$status= '';
	$value= '';
	if($physical_system !== 'ok' && $digital_system == 'ok'){
		$status= 'digital';
		$value= 'ok';
	}
	if($physical_system == 'ok' && $digital_system !== 'ok'){
		$status= 'digital';
		$value= NULL;
	}
	if($physical_system !== 'ok' && $digital_system !== 'ok'){
		$status= 'digital';
		$value= '0';
	}
?>
<div>
	<?php
        echo form_open(base_url() . 'index.php/admin/del_slot/do_add/', array(
            'class' => 'form-horizontal',
            'method' => 'post',
            'id' => 'del_slot_add',
            'enctype' => 'multipart/form-data'
        ));
    ?>
        <div class="panel-body">

            

            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('date');?></label>
                <div class="col-sm-6">
                    <input type="date" name="till" id="demo-hor-1" class="form-control">
                </div>
            </div>
            
            

            <?php /*?><div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('Time_from');?></label>
                <div class="col-sm-6">
                    <input type="text" name="from" id="demo-hor-1" 
                        placeholder="<?php echo translate('12:00 AM'); ?>" class="form-control required">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('Time_to');?></label>
                <div class="col-sm-6">
                    <input type="text" name="to" id="demo-hor-1" 
                        placeholder="<?php echo translate('12:00 PM'); ?>" class="form-control required">
                </div>
            </div><?php */?>
            
            
        </div>
	</form>
</div>
<script src="<?php echo base_url(); ?>template/back/js/custom/brand_form.js"></script>
<script type="text/javascript">
    $('.chos').on('change',function(){
        var a = $(this).val();
        $('.product').hide('slow');
        $('.category').hide('slow');
        $('.sub_category').hide('slow');
        $('.'+a).show('slow');
    });
</script>
