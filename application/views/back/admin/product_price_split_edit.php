<?php
    foreach($product_data as $row){
        $res = $this->db->get_where('vendor', array(
            'vendor_id' => $row['store_id']
        ))->row()->name;
?>
<div class="row">
    <div class="col-md-12">
        <?php
			echo form_open(base_url() . 'index.php/admin/product/update_split_edit/' . $row['product_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'product_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
            <!--Panel heading-->
            <div class="panel-heading">
                <div class="panel-control" style="float: left;">
                    <ul class="nav nav-tabs" style="display:none;">
                        <li class="active">
                            <a data-toggle="tab" href="#product_details"><?php echo translate('product_details'); ?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#business_details"><?php echo translate('business_details'); ?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#customer_choice_options"><?php echo translate('customer_choice_options'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <center><h2 style="color:red"><?php echo $row['title'];?></h2></center>
            <br>
            <center><h4 style="color:blue"><?php echo $res;?></h4></center>
            <div class="panel-body">
                <div class="tab-base">
                    <!--Tabs Content-->                    
                    <div class="tab-content">
                                 
                        
                        
                        <?php 
						 $all_af = $this->crud_model->get_additional_fields($row['product_id']);
                                $all_c = json_decode($row['color'],true);
								$all_cqty = json_decode($row['color_qty'],true);
                                $all_op = json_decode($row['options'],true);
						?>
                        <div id="customer_choice_options" class="tab-pane fade active in">
                            
                            <div class="form-group btm_border hidden">
                                <label class="col-sm-4 control-label" for="demo-hor-15" style="font-weight:bold; font-size:18px !important;">
                                    <?php echo translate('product_color_options');?>
                                        </label>
                                        <input type="hidden" name="mid" id="mid" value="<?php echo $rest[0]['id']?>" />
                                    <div class="col-sm-6"  id="more_colors">
                                    
                                     
                                     <div style="float:left;">
                                     <?php 										
                                         if($all_c)
											{
												$p21=0;
                                                foreach($all_c as $p1)
												{
                                        ?>
                                        <input required="required" type="radio" id="color_<?php echo $p21; ?>" name="color" <?php if($p1==$rest[0]['product_color']) {?> checked="checked" <?php } ?>value="<?php  echo $p1; ?>" />
                                        <span style="width:15px !important; display:inline-block; height:15px; background-color:<?php echo $p1; ?>; ">&nbsp;</span>
                                         <?php  $p21++; } } ?>
                                       </div>
                                    
                                    </div>
                            </div>
                            <div id="more_additional_options">
              
                            </div>
                            
                            </div>
                            
                          <div class="col-sm-12"> 
							<div class="col-md-5"  id="more_colors"> 
                            <div class="col-sm-3 inr-fnme"  id="more_colors" style="text-align:right !important;">Quantity</div>
                            <div class="col-sm-9"  id="more_colors">
                            <input  required="required" type="text" id="split_qty" class="form-control-inr" name="split_qty" value="<?php echo $rest[0]['quantitty']; ?>" placeholder="Quantity" />
							</div>
						  </div>
                            
                             <div class="col-md-5"  id="more_colors">
                            <div class="col-sm-3"  id="more_colors" style="margin-top:15px; text-align:right !important;">Amount</div>
                             <div class="col-sm-9"  id="more_colors" style="">
                             <input  required="required" type="text" id="split_price" class="form-control-inr" name="split_price" value="<?php echo $rest[0]['amount']; ?>" placeholder="Price" />
                            </div>
                            </div>
							<div class="col-md-2"  id="more_colors">
								<span class="btn btn-success btn-md btn-labeled fa fa-wrench pull-right enterer" onclick="form_submit('product_edit','<?php echo translate('successfully_edited!'); ?>');proceed('to_add');" ><?php echo translate('Update');?></span> 
							</div>
                        </div>

                    </div>
                </div>
        </form>
      </div>
    </div>
<?php
    }
?>
<!--Bootstrap Tags Input [ OPTIONAL ]-->
<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<input type="hidden" id="option_count" value="<?php if($r == 1){ echo $row1['no']; } else { echo '0'; } ?>">

<script type="text/javascript">

    function set_select(){
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
    }
    
    $(document).ready(function() {
        //set_select();
        //set_summer();
        //createColorpickers();
    });

    function other(){
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
        $('#sub').show('slow');
    }
    function get_cat(id){
		$('#brn').hide('slow');
        $('#sub').hide('slow');
        ajax_load(base_url+'index.php/admin/product/sub_by_cat/'+id,'sub_cat','other');
    }
	function get_brnd(id){
        $('#brn').hide('slow');
        ajax_load(base_url+'index.php/admin/product/brand_by_sub/'+id,'brand','other');
        $('#brn').show('slow');
    }

    function get_sub_res(id){}

    $(".unit").on('keyup',function(){
        $(".unit_set").html($(".unit").val());
    });
	
	function createColorpickers() {
	
		$('.demo2').colorpicker({
			format: 'rgba'
		});
		
	}
	
    
    
    
    
    $('body').on('click', '.rmo', function(){
        $(this).parent().parent().remove();
    });

    
    $('body').on('click', '.rmon', function(){
        var co = $(this).closest('.form-group').data('no');
        $(this).parent().parent().remove();
        if($(this).parent().parent().parent().html() == ''){
            $(this).parent().parent().parent().html(''
                +'   <input type="hidden" name="op_set'+co+'[]" value="none" >'
            );
        }
    });

	
	
	$(document).ready(function() {
		//$("form").submit(function(e){event.preventDefault();});
	});
</script>
<style>
	.btm_border{
		border-bottom: 1px solid #ebebeb;
		padding-bottom: 15px;	
	}
</style>

