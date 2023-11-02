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
	foreach($del_data as $row){
?>
    <div>
        <?php
			echo form_open(base_url() . 'index.php/admin/del_slot/update/' . $row['del_slot_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'del_slot_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
            <div class="panel-body">

                

                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('date');?></label>
                    <div class="col-sm-6">
                        <input type="date" name="till" id="demo-hor-1" value="<?php echo date('Y-m-d' ,$row['f_date']); ?>" class="form-control">
                    </div>
                </div>
                <?php /*?><div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('from');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="from" id="demo-hor-1" value="<?php echo $row['from_t']; ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('to');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="to" id="demo-hor-1" value="<?php echo $row['to_t']; ?>" class="form-control">
                    </div>
                </div><?php */?>
               
            </div>
        </form>
    </div>

<?php
	}
?>

<script src="<?php echo base_url(); ?>template/back/js/custom/brand_form.js"></script>


