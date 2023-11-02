<div class="thumbnail box-style-2 no-padding">
    <div class="media">
    	<div class="cover"></div>
        <div class="media-link image_delay" data-src="<?php echo $this->crud_model->file_view('product',$product_id,'','','thumb','src','multi','one'); ?>" style="background-image:url('<?php echo img_loading(); ?>');background-size:cover;">
            
            <?php 
                $discount= $this->db->get_where('product',array('product_id'=>$product_id))->row()->discount ;           
                if($discount > 0){ 
            ?>
                <div class="sticker green">
                    <?php  // echo translate('discount');?> 
                    <?php 
                         $type = $this->db->get_where('product',array('product_id'=>$product_id))->row()->discount_type ; 
                         if($type =='amount'){
                              echo currency($discount); 
                              } else if($type == 'percent'){
                                   echo $discount; 
                    ?> 
                        % 
                    <?php 
                        }
                    ?>
                </div>
            <?php } ?>

            <span onclick="quick_view('<?php echo $this->crud_model->product_link($product_id,'quick'); ?>')">
                <span class="icon-view middle" data-toggle="tooltip" data-original-title="<?php  echo translate('quick_view'); ?>">
                    <strong><i class="fa fa-eye"></i></strong>
                </span>
            </span>
            <?php /* <span class="icon-view middle" data-toggle="tooltip" 
            	data-original-title="<?php if($this->crud_model->is_compared($product_id)=="yes"){ echo translate('compared'); } else { echo translate('compare'); } ?>"
                	onclick="do_compare(<?php echo $product_id; ?>,event)">
                <strong><i class="fa fa-exchange"></i></strong>
            </span>
            <span class="icon-view right" data-toggle="tooltip" 
            	data-original-title="<?php if($this->crud_model->is_wished($product_id)=="yes"){ echo translate('added_to_wishlist'); } else { echo translate('add_to_wishlist'); } ?>"
            		onclick="to_wishlist(<?php echo $product_id; ?>,event)">
                <strong><i class="fa fa-heart"></i></strong>
            </span> */ ?>
        </div>
    </div>
    <div class="caption text-center">
        <h4 class="caption-title1">
        	<a href="<?php echo $this->crud_model->product_link($product_id); ?>">
				<?php echo $title; ?>
            </a>
        </h4>
        <div class="price">
            <?php if($this->crud_model->get_type_name_by_id('product',$product_id,'discount') > 0){ ?> 
                <ins><sup><?php echo currency(); ?></sup><?php echo $this->crud_model->get_product_price($product_id); ?> </ins> 
                <del><sup><?php echo currency(); ?></sup><?php echo $sale_price; ?></del>
            <?php } else { ?>
                <ins><sup><?php echo currency(); ?></sup> <?php echo $sale_price; ?></ins> 
            <?php }?>
        </div>
        <?php
    	echo form_open('', array(

		'method' => 'post',

		'class' => 'sky-form',

	));
	?>
        <div class="RqEt">
        <div class="quantity">  
            <span class="btn" name="subtract"  onclick='decrease_val1("<?php echo $product_id; ?>");'><i class="fa fa-minus"></i></span>
            <input class="quantity-field"  type="text" min="1" max="<?php echo $this->crud_model->get_type_name_by_id('product',$product_id,'current_stock');?>" name="qty" value="<?php if($a = $this->crud_model->is_added_to_cart($product_id,'qty')){echo $a;} else {echo '1';} ?>" id="qtyh_<?php echo $product_id; ?>" onkeypress='check_ours("<?php echo $product_id; ?>")'/>
            <span class="btn" name="add" onclick='increase_val1("<?php echo $product_id; ?>");' ><i class="fa fa-plus"></i></span>
        </div>
        </div>
        <div class="cart">
     <!--   <span class="btn btn-block btn-theme btn-icon-left">-->
     <?php
                if($this->crud_model->get_type_name_by_id('product',$product_id,'current_stock') <=0 && !$this->crud_model->is_digital($product_id)){ 
            ?>
                <span class="btn btn-block btn-theme btn-icon-left sticker red">
                    <?php echo translate('out_of_stock'); ?>
                </span>
            <?php
                } else {
            ?>
            <span class="btn btn-block btn-theme btn-icon-left" data-toggle="tooltip" 
            	data-original-title="<?php if($this->crud_model->is_added_to_cart($product_id)){ echo translate('added_to_cart'); } else { echo translate('add_to_cart'); } ?>" 
            		data-placement="top"
                 		onclick="quick_view('<?php echo $this->crud_model->product_link($product_id,'quick'); ?>')" >
                    		in cart
            </span>
            <?php } ?>
        </div>
        
        </form>
     <!-- <div class="vendor">
            <?php // echo $this->crud_model->product_by($product_id,'with_link'); ?>
            <i class="fa fa-heart-o" data-toggle="tooltip" 
            	data-original-title="<?php if($this->crud_model->is_wished($product_id)=="yes"){ echo translate('added_to_wishlist'); } else { echo translate('add_to_wishlist'); } ?>"
            		onclick="to_wishlist(<?php echo $product_id; ?>,event)"></i>
             <i class="fa fa-exchange" data-toggle="tooltip" 
            	data-original-title="<?php if($this->crud_model->is_compared($product_id)=="yes"){ echo translate('compared'); } else { echo translate('compare'); } ?>"
                	onclick="do_compare(<?php echo $product_id; ?>,event)"></i>
            
          <div onclick="subscribe_view('<?php echo $this->crud_model->product_subscribe($product_id,'subscribe'); ?>')">
                <span class="icon-view left" data-original-title="<?php  echo translate('subscribe'); ?>">
                    <strong><i class="fa fa-eye"></i></strong>
         </div>     
        </div>-->
        <?php /*
if($this->session->userdata('user_id')!=''){?>
     <div class="col-lg-12 col-xs-12 p0 padding_12348">   
    <?php $callnow= $this->db->get_where('product',array('product_id'=>$product_id))->row()->callnow ; 
    if($enquiry=="ok"){
    ?>
          <div class="sv">
            <a class="btn btn-theme-transparent btn-icon-left phne" href="" style="width: 100%;">
            	<i class="fa fa-phone"></i>
            	Call Us @ <?php echo $this->crud_model->product_by_phone($product_id,'with_link');?>
            </a>  
          </div>    
    <?php } $enquiry= $this->db->get_where('product',array('product_id'=>$product_id))->row()->enquiry ; 
    if($enquiry=="ok"){
    ?>  
          <div class="sv">  
             <span onclick="quick_view('<?php echo $this->crud_model->product_link($product_id,'enquiry'); ?>')" class="btn btn-theme-transparent btn-icon-left enq" style="width: 100%;">
                <i class="fa fa-question-circle"></i>
            	Send Enquiry
            </span>
         </div>    
    <?php } ?> 
    </div>                
           
       <?php } */?> 
    </div>
</div>