<div>
	<?php
        echo form_open(base_url() . 'index.php/admin/sales/accept_set/' . $sale_id, array(
            'class' => 'form-horizontal',
            'method' => 'post',
            'id' => 'sales_accept',
            'enctype' => 'multipart/form-data'
        ));
    ?>
        <div class="panel-body">
			
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('order_status'); ?></label>
                    <div class="col-sm-6">
                        <?php
                            $from = array('approve','reject');
                            echo $this->crud_model->select_html($from,'status','','edit','demo-chosen-select','');
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

