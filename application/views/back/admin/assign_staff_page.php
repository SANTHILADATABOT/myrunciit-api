<div>
	<?php
        echo form_open(base_url() . 'index.php/admin/sales/assign_staff_set/' . $sale_id, array(
            'class' => 'form-horizontal',
            'method' => 'post',
            'id' => 'sales_assign',
            'enctype' => 'multipart/form-data'
        ));
    ?>
        
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('choose_staff'); ?></label>
                    <div class="col-sm-6">
                        <?php
                            
                       $att=0;    
                       echo $this->crud_model->select_html('admin', 'admin', 'name', 'add', 'demo-chosen-select required','','','','','',$att); 
						
                        ?>
                    </div>
                </div>
            
			
          

        </div>
    </form>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
        total();
    });

    function total(){
        var total = Number($('#quantity').val())*Number($('#rate').val());
        $('#total').val(total);
    }

    $(".totals").change(function(){
        total();
    });
	
	$(document).ready(function() {
		$("form").submit(function(e){
			event.preventDefault();
		});
	});
</script>
<div id="reserve"></div>

