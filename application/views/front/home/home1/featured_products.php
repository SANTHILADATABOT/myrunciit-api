<!-- PAGE -->
<section class="page-section featured-products hidden">
    <div class="container">
        <h2 class="section-title section-title-lg">
        <span>
            <?php echo translate('latest');?>
                <?php echo translate('featured');?> 
                <?php echo translate('product');?>
            </span>
            <span class="pull-right rht_section nocolr">
            	<a class="fo-oo" href="<?php echo base_url(); ?>index.php/home/others_product/featured"> <?php echo translate('see all');?>
                	
                </a>
            </span>
            
        </h2>
        <div class="featured-products-carousel">
            <div class="owl-carousel" id="featured-products-carousel">
                <?php
					$box_style =  $this->db->get_where('ui_settings',array('ui_settings_id' => 29))->row()->value;
					$limit =  $this->db->get_where('ui_settings',array('ui_settings_id' => 20))->row()->value;
                    $featured=$this->crud_model->product_list_set('featured',$limit);
                    foreach($featured as $row){
                		echo $this->html_model->product_box($row, 'grid', $box_style);
					}
                ?>
                        <div class="quantity product-quantity">  <span class="btn" name="subtract" onclick="decrease_val2(243);"><i class="fa fa-minus"></i></span><input type="text" class="form-control qty quantity-field cart_quantity" min="1" max="10" name="qty" value="1" id="qtyc_243" data-rowid="cb70ab375662576bd1ac5aaf16b3fca4" onchange="check_ours(243);"><span class="btn" name="add" onclick="increase_val2(243);"><i class="fa fa-plus"></i></span>
            </div>
        </div>
    </div>
</section>
<!-- /PAGE -->
<script>
$(document).ready(function(){
	setTimeout( function(){ 
		set_featured_product_box_height();
	},1000 );
});

function set_featured_product_box_height(){
	var max_title=0;
	$('.featured-products .caption-title').each(function(){
        var current_height= parseInt($(this).css('height'));
		if(current_height >= max_title){
			max_title = current_height;
		}
    });
	$('.featured-products .caption-title').css('height',max_title);
}
</script>

<!--<section class="home-ban">
	<div class="container">
    	<div class="col-md-6"><a href="javascript:void(0);"><img src="<?php echo base_url(); ?>/template/img/automobile.jpg" class="bot-ban" alt=""></a></div>
        <div class="col-md-6"><a href="javascript:void(0);"><img src="<?php echo base_url(); ?>/template/img/sports.jpg" class="bot-ban" alt=""></a></div>
    </div>
</section>-->