<?php
    foreach($product_data as $row){

        $res = $this->db->get_where('vendor', array(
            'vendor_id' => $row['store_id']
        ))->row()->name;
		
		//echo '<pre>'; print_r($row); exit;
?>
<div class="row">
    <div class="col-md-12">
        <?php
			echo form_open(base_url() . 'index.php/admin/product/update_split/' . $row['product_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'product_price_split',
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
                                    <div class="col-sm-6"  id="more_colors">
                                    
                                     
                                     <div style="float:left;">
                                     <?php 										
                                         if($all_c)
											{
												$p21=0;
                                                foreach($all_c as $p1)
												{
													
                                        ?>
                                        <input required="required" type="radio" id="color_<?php echo $p21; ?>" name="color" value="<?php echo $p1; ?>" checked/>
                                        <span style="width:15px !important; display:inline-block; height:15px; background-color:<?php echo $p1; ?>; ">&nbsp;</span>
                                         <?php  $p21++; } } ?>
                                       </div>
                                    
                                    </div>
                            </div>
                            <div id="more_additional_options">
                            
                             <!-- <label class="col-sm-4 control-label" for="demo-hor-15" style="font-weight:bold; font-size:18px !important;">
                                    <?php echo translate('Other Option');?>
                                        </label> -->
                                        
                            <div class="col-sm-6"  id="more_colors">
                               <div style="float:left;">
                             <?php
                                $r = 0;
                                if(!empty($all_op)){
                                    foreach($all_op as $i=>$row1){
                                        $r = 1;
                            ?> 
                            
                            
                            	<p>
                                <h4><?php echo $row1['title']; ?></h4>
                                <?php if($row1['type'] == 'text' || $row1['type'] == ''){ ?>
                                <input type="hidden" name="op_set<?php echo $row1['no']; ?>[]" value="none" >  
                                <?php } else { 
								$as=0;
								foreach ($row1['option'] as $key => $row2) { ?>
                                
                                <input  required="required" type="radio" style="margin-left:30px;" id="<?php echo $row1['title'].'_'.$as; ?>"  name="<?php echo $row1['title']; ?>" value="<?php echo $row2; ?>" /> <?php echo $row2; ?>
                                <?php
								
								$as++; } } ?> 
                                </p>
                               
                            	
                            
                            
                            
                            <?php $r++; } } ?>
                            </div>
              
                            </div>
                            
                            </div>
                            
                         <div class="col-md-12"> 
                            <div class="col-md-5"  id="more_colors">
							   <div class="col-md-3 inr-fnme">Quantity</div>
							   <div class="col-md-9"><input  required="required" class="form-control-inr" type="text" id="split_qty" name="split_qty" value="" placeholder="Quantity" /></div>
							   
							</div>
							
                            <div class="col-md-5"  id="more_colors">
							
							   <div class="col-md-3 inr-fnme">Amount</div>
							   <div class="col-md-9"><input  required="required" class="form-control-inr" type="text" id="split_price" name="split_price" value="" placeholder="Price" /></div>
							
                                                        
                            </div>
                            <div class="col-md-2"  id="more_colors"><span class="btn btn-success inr-btn btn-md btn-labeled fa fa-wrench pull-right enterer" onclick="form_submit('product_price_split','<?php echo translate('successfully_added!'); ?>');proceed('to_add');" ><?php echo translate('Update');?></span> 
							</div>
                        </div>

                        </div>

                    </div>
                </div>
            </div>

        </form>

<div CLASS="fixed-table-container">
<div CLASS="fixed-table-header">
<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true"  data-show-toggle="true" data-show-columns="true" data-search="true" >

        <thead>
            <tr>
                <th style="width:4px"><?php echo translate('S.No');?></th>                
                <!-- <th><?php echo translate('Other Option');?></th> -->
                <th><?php echo translate('Product Quantity');?></th>
                <th><?php echo translate('Product amount');?></th>
                <th><?php echo translate('Created By');?></th>
                <th><?php echo translate('Modified By');?></th>
                <th><?php echo translate('Action');?></th>
            </tr>
        </thead>
            
        <tbody>
        <?php
            $i = 1;
           foreach($multi as $row2)
		   {
			   //echo '<pre>'; print_r($row2); exit;
				
        ?>

        <tr class="">
        <td><?php echo $i; ?></td>
        <!-- <td><?php  $options = json_decode($row2['other_option'],true); 
		foreach($options as $key => $value)
		{
			if($key=='color')
			 $key.' : <span style="background:'.$row2["product_color"].';  width:15px !important; float:none; padding-left:25px; height:15px; display:none;">&nbsp;</span><br/>';	
			else 
			echo $key.' : '.$value.'<br/>';	
		}
		?></td> -->
        <td><?php echo $row2['quantitty']; ?></td>
        <td><?php echo $row2['amount']; ?></td>
        <td><?php echo translate($this->crud_model->get_type_name_by_id('admin', $row2['created_by'], 'name')); ?></td>
        <td><?php echo translate($this->crud_model->get_type_name_by_id('admin', $row2['modified_by'], 'name')); ?></td>
        <td><?php echo "<a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('price_split_edit','".translate('edit_price')."','".translate('successfully_edited!')."','price_split_edit','".$row2['product_id'].'_'.$row2['id']."');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                                    ".translate('edit')."
                            </a>
                            
                            <a onclick=\"delete_confirm1('".$row2['id']."','Really want to delete this?')\" 
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    ".translate('delete')."
                            </a>"; ?></td>

                        
                                    
                                    
            
            
           
            
        </tr>
        <?php
        $i++;  }
        ?>
        </tbody>
    </table>
	</div></div>
    </div>
</div>
<?php
    }
?>
<!--Bootstrap Tags Input [ OPTIONAL ]-->
<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<input type="hidden" id="option_count" value="<?php if($r == 1){ echo $row1['no']; } else { echo '0'; } ?>">
<script type="text/javascript">


function delete_confirm1(id,msg){
		msg = '<div class="modal-title">'+msg+'</div>';
		bootbox.confirm(msg, function(result) {
			if (result) {
				ajax_load(base_url+'index.php/'+user_type+'/'+module+'/delete_mp/'+id,'list','delete');
				$.activeitNoty({
					type: 'danger',
					icon : 'fa fa-check',
					message : dss,
					container : 'floating',
					timer : 3000
				});
				sound('delete');
			}else{
				$.activeitNoty({
					type: 'danger',
					icon : 'fa fa-minus',
					message : cncle,
					container : 'floating',
					timer : 3000
				});
				sound('cancelled');
			};
		});
	}

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

