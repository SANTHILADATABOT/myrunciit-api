<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/product_bundle/add_discount_set/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'add_bundle_discount',
			'enctype' => 'multipart/form-data'
		));
	?>
        <div class="panel-body">
            <input type="hidden" name="product_bundle" value="<?php echo $product_bundle; $type = $this->crud_model->get_type_name_by_id('product',$product_bundle,'discount_type');?>" >
            
            <div class="form-group btm_border">
                <label class="col-sm-3 control-label" for="demo-hor-1"><?php echo translate('bundle_discount');?></label>
                <div class="col-sm-4">
                    <input type="number" name="discount" id="demo-hor-1"  min='0' step='.01' value="<?php echo $this->crud_model->get_type_name_by_id('product',$product_bundle,'discount'); ?>" placeholder="Product Bundle Discount" class="form-control">
                </div>
                <div class="col-sm-2">
                    <select class="demo-chosen-select" name="discount_type">
                        <option value="percent" <?php if($type == 'percent'){ echo 'selected'; } ?> >%</option>
                        <option value="amount" <?php if($type == 'amount'){ echo 'selected'; } ?> >$</option>
                    </select>
                </div>
                <span class="btn unit_set">/<?php echo $this->crud_model->get_type_name_by_id('product',$product_bundle,'unit'); ?></span>
            </div>

        </div>
        
    </form>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
    });


	$(document).ready(function() {
		$("form").submit(function(e){
			event.preventDefault();
		});
	});
</script>

