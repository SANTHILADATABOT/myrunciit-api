

    <table class="table table-bordered carter_table" style="background: #fff;">
        <thead>
            <tr>
                <th class="hidden-sm hidden-xs"><?php echo translate('image');?></th>
                <th><?php echo translate('product_details');?></th>
                <th><?php echo translate('unit_price');?></th>
                <th style="text-align:center;"><?php echo translate('quantity');?></th>
                <th><?php echo translate('subtotal');?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $carted = $this->cart->contents(); 
                foreach ($carted as $items){ 
            ?>
            <tr data-rowid="<?php echo $items['rowid']; ?>" >
                <td class="image hidden-sm hidden-xs" align="center">
                    <a class="media-link" href="<?php echo $this->crud_model->product_link($items['id']); ?>">
                        <i class="fa fa-plus"></i>
                        <img src="<?php echo $items['image']; ?>" width="60" alt=""/>
                    </a>
                </td>
                <td class="description">
                    <h4 style="">
                        <a href="<?php echo $this->crud_model->product_link($items['id']); ?>">
                            <?php echo $items['name']; ?>
                        </a>
                    </h4>
                    <hr class="mr0" style="display:none;">
                    <?php 
                     /*   $color = $this->crud_model->is_added_to_cart($items['id'],'option','color'); 
                        if($color){ 
                    ?>
                   <div style="background:<?php echo $color; ?>; height:15px; width:15px;border-radius:50%;"></div>
                    <hr class="mr0">
                    <?php
                        } */
                    ?>
                    
                    <?php
                        $all_o = json_decode($items['option'],true);
                        foreach ($all_o as $l => $op) { 
                            if($l !== 'color' && $op['value'] !== '' && $op['value'] !== NULL){ 
                    ?> 
                            <span style="font-size:13px;"><?php echo $op['title']; ?></span>
                    : 
                    <?php 
                        if(is_array($va = $op['value'])){ 
					?>
                    <span style="font-size:13px !important;"><?php echo $va = join(', ',$va); ?></span>
                    <?php
                        } else {
					?>
                    <span style="font-size:13px !important;"><?php echo $va; ?></span>
                    <?php
                        }
                    ?>
                    <hr class="mr0">
                    <?php
                            }
                        }
                    ?>
                    <a href="<?php echo $this->crud_model->product_link($items['id']); ?>" class="change_choice_btn">
                        <?php echo translate('change_choices'); ?>
                    </a>
                    <?php
                    $cod_status=$this->db->get_where('cash_on_delivery', array('product_id' => $items['id']))->row()->status;
                    if($cod_status=="1"){ ?>
                    <div style="color: #8dc43c;"><?php echo translate('cash_on_delivery_available'); ?></div>
                    <?php }else{ ?>
                    <div style="color: #cc0101;"><?php echo translate('cash_on_delivery_not-available'); ?></div>
                    <?php } ?>
                </td>
                <td class="quantity pric">
                <sup> <?php echo currency(); ?> </sup>  <?php $price_1=($items['price']!='')?floatval($items['price']):0;echo number_format($price_1,2); ?>
                </td>
                <td class="quantity product-single" style="text-align:center;">
					<?php
                        if(!$this->crud_model->is_digital($items['id'])){
                    ?>
                    <span class="buttons">
                        <div class="quantity product-quantity">
                            <button type='button' class="btn in_xs quantity-button minus"  value='minus' >
                                <i class="fa fa-minus"></i>
                            </button>
                            <input min="1" type="number" class="form-control qty in_xs quantity-field quantity_field" data-rowid="<?php echo $items['rowid']; ?>" data-limit='no' value="<?php echo $items['qty']; ?>" id='qty1' onblur="check_ok(this);" />
                            <button type='button' class="btn in_xs quantity-button plus"  value='plus' >
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </span>
                    <?php
						}
					?>
                </td>
                <td class="total">
                    <span class="sub_total">
                       <sup> <?php echo currency(); ?> </sup><?php $sub_total_1=($items['subtotal']!='')?floatval($items['subtotal']):0;echo number_format($sub_total_1,2); ?> 
                    </span>
                </td>
                <td class="total">
                    <span class="close" style="color:#f00;">
                        <i class="fa fa-trash"></i>
                    </span>
                </td>
            </tr>
            
            <?php
                }
            ?>
            
        </tbody>
    </table>


<textarea id="product_notes" name="product_notes" placeholder="Delivery or pickup notes" class="form-control" style="border: 1px solid #ccc;"></textarea>
<br>

    <span class="btn btn-theme-dark" onclick="load_address_form();" style="background:#e57129;border:1px solid #e57129;">
        <?php echo translate('next');?>
    </span>



<?php
    echo form_open('', array(
    'method' =>
    'post', 'id' => 'coupon_set' )); 
?> 
<input type="hidden" id="coup_frm" name="code">
</form>

<script type="text/javascript">
    $( document ).ready(function() {		
		$('body').on('click','.close', function(){
			var here = $(this);
			var rowid = here.closest('tr').data('rowid');
			var thetr = here.closest('tr');
			var list1 = $('#total');
			$.ajax({
				url: base_url+'index.php/home/cart/remove_one/'+rowid,
				beforeSend: function() {
					list1.html('...');
				},
				success: function(data) {
					list1.html(data).fadeIn();
					notify(cart_product_removed,'success','bottom','right');
					//sound('cart_product_removed');
					reload_header_cart();
					others_count();
					thetr.hide('fast');
					if(data == 0){
						location.replace('<?php echo base_url();?>');	
					}
				},
				error: function(e) {
					console.log(e)
				}
			});
		});
		
        update_calc_cart();
    });
</script>

