<?php

echo form_open(
    base_url() . 'home/cart_finish/go',
    array(

        'method' => 'post',

        'enctype' => 'multipart/form-data',

        'id' => 'cart_form'

    )

);

?>

<!--<script src="js/sjs.js"></script>-->

<!-- PAGE -->

<section class="page-section color">

    <div class="container box_shadow">


        <div class="col-md-9">
            <h3 class="block-title alt">

                <i class="fa fa-angle-down"></i>

                <?php //echo translate('1');
                ?>

                <?php echo translate('my_cart'); ?>

            </h3>

            <div class="orders">



            </div>



            <h3 class="block-title alt">

                <i class="fa fa-angle-down"></i>

                <?php //echo translate('2');
                ?>

                <?php
                if ($this->session->userdata('pickup') == "") {
                    echo translate('shipping & delivery_address');
                } else {
                    echo translate('billing_customer_information');
                } ?>

            </h3>

            <div action="#" class="form-delivery delivery_address">

            </div>



            <div id="payment-option-div">
            <h3 class="block-title alt">

                <i class="fa fa-angle-down"></i>

                <?php //echo translate('3');
                ?>

                <?php echo translate('payments_options'); ?>

            </h3>

            <div class="panel-group payments-options" id="accordion" role="tablist" aria-multiselectable="true">

            </div>
            </div>



            <div class="overflowed">

                <a class="btn btn-theme-dark" href="<?php echo base_url(); ?>home/cancel_order" style="background:#e57129;border:1px solid #e57129;">

                    <?php echo translate('Clear_Cart'); ?>

                </a>

                <span class="btn btn-theme pull-right disabled" id="order_place_btn" onclick="cart_submission(this);" >

                    <?php echo translate('place_order'); ?>

                </span>

            </div>
        </div>
        <div class="col-md-3 order">
            <h3 class="block-title">
                <span>
                    <?php echo translate('shopping_cart'); ?>
                </span>
            </h3>
            <div class="shopping-cart" style="background: #f5f8fa;">
                <table>
                    <tr>
                        <td style="text-align:left;font-weight:600;"><?php echo translate('subtotal'); ?>:</td>
                        <td style="text-align:right;" id="total"></td>
                    </tr>
                    <tr>
                        <td style="text-align:left;font-weight:600;"><?php echo translate('tax'); ?>:</td>
                        <td style="text-align:right;" id="tax"></td>
                    </tr>
                    <?php if ($this->session->userdata('user_zips') != "") { ?>
                        <tr>
                            <td>
                                <div class="tooltip1"><i class="fa fa-info-circle"></i>
                                    <div class="tooltiptext1">
                                        <p>Free Delivery for orders <?php $free_delivery = $this->db->get_where('business_settings', array('type' => 'free_delivery'))->row()->value;
                                                                    echo currency($free_delivery);  ?> and more.Delivery Fees <?php $ship_cost = $this->db->get_where('business_settings', array('type' => 'delivery_fee'))->row()->value;
                                                                    echo currency($ship_cost);  ?> for orders below <?php echo currency($free_delivery); ?> </p>
                                    </div>
                                </div>

                                <?php echo translate('delivery_Fees'); ?>:
                                
                            </td>
                            <td id="shipping"></td>
                            
                        </tr>
                    <?php }    ?>
                    <tr style="display:none;">

                        <td>
                            <div class="tooltip1"><i class="fa fa-info-circle"></i>
                                <div class="tooltiptext1">
                                    <p>Free Delivery for orders <?php $free_delivery = $this->db->get_where('business_settings', array('type' => 'free_delivery'))->row()->value;
                                                                echo currency($free_delivery);  ?> and more.Delivery Fees <?php $ship_cost = $this->db->get_where('business_settings', array('type' => 'delivery_fee'))->row()->value;
                                                                    echo currency($ship_cost);  ?> for orders below <?php echo currency($free_delivery); ?> </p>
                                </div>
                            </div>
                            <?php echo translate('delivery_Fees');

                            //$shipping= $this->db->get_where('gr_product',array('product_id'=>$items['id'],'oid'=>$this->session->userdata('propertyIDS')))->result_array();

                            //	echo '<pre>'; print_r($shipping);

                            ?>:
                        </td>

                        <!--<td id="shipping"><?php //echo currency(0.00); 
                                                ?></td>-->
                        <td id="shipping"><?php echo currency($shipping[0]['shipping_cost']); ?></td>

                    </tr>

                    <tr class="coupon_disp" style="display: none;">
                        <td style="text-align:left;font-weight:600;"><?php echo translate('coupon_discount'); ?></td>
                        <td style="text-align:right;" id="disco">
                          <sup><?php echo currency(); ?></sup>  <?php echo ($this->cart->total_discount()); ?>
                        </td>
                    </tr>
                    <tr class="coupon_disp" style="display: none;">
                        <td style="text-align:left;font-weight:600;"><?php echo translate('delivery_estimate'); ?></td>
                        <td style="text-align:right;" >
                          <sup><?php echo currency(); ?></sup>  <span id="priceBreakDownTotal"></span>
                        </td>
                    </tr>

                    <tfoot>
                        <tr>
                            <td style="text-align:left;"><?php echo translate('grand_total'); ?>:</td>
                            <td style="text-align:right;" class="grand_total" id="grand"></td>
                        </tr>
                    </tfoot>
                </table>

                <?php if ($this->session->userdata('user_id') != '') {
                    $rpoints = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->rewards;
                    if ($rpoints > '0') {
                ?>

                        <table>
                            <tr>
                                <td style="text-align:left;"><?php echo translate('available_reward'); ?></td>
                                <td style="text-align:right;">
                                    <span style="margin:30px;"><sup><?php echo currency()?></sup><?php echo  $rpoints ?></span>
                                    <span>
                                        <?php echo translate('use_reward'); ?>&nbsp;<input type="checkbox" id="rewards" name="rewards" value="<?php echo $rpoints; ?>" onclick="payment_type_div_req()">
                                        <input type="hidden" id="rewards_amt" value="<?php echo $rpoints; ?>" />
                                        <input type="hidden" name="payment_option_dis" id="payment_option_dis" value="1" />
                                    </span>
                                </td>
                            </tr>
                        </table>
                <?php }
                } ?>
                <?php if ($this->cart->total_discount() <= 0 && $this->session->userdata('couponer') !== 'done' && $this->cart->get_coupon() == 0) { ?>
                    <h5>
                        <?php echo translate('enter_your_coupon_code_if_you_have_one'); ?>.
                    </h5>
                    <div class="form-group">
                        <input type="text" class="form-control coupon_code" placeholder="Enter your coupon code">
                    </div>
                    <span class="btn btn-theme btn-block coupon_btn">
                        <?php echo translate('apply_coupon'); ?>
                    </span>
                    <br>
                    <div class="wells availableCouponSection">
                        <h4>Available Coupon Code</h4>
                        <ul class="list-group" id="availableCoupons">

                        </ul>
                    </div>
                <?php } else { ?>
                    <p>
                        <?php echo translate('coupon_already_activated'); ?>
                    </p>
                <?php } ?>
            </div>


        </div>
    </div>

</section>

<!-- /PAGE -->

</form>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlVdIucfxScZzEGUmEbcOzarwn6Kc-GAg&libraries=places" defer></script>

<script>
    function payment_type_div_req(){
        var reward_ch=document.getElementById("rewards").checked;
        if(reward_ch)
        {
            var grand1=parseFloat($("#grand").html().toUpperCase().replace('RM', ''));
            var grand_total1=(!isNaN(grand1))?grand1:0.0;
            var rewards1=parseFloat($("#rewards_amt").val());
            var rewards_amt1=(!isNaN(rewards1))?rewards1:0.0;
            if(grand_total1>rewards_amt1){
                document.getElementById("payment-option-div").style.display="block";
                $("#payment_option_dis").val("1");
            }else{
                document.getElementById("payment-option-div").style.display="none";
                $("#payment_option_dis").val("0");
            }
        }
        else{
            document.getElementById("payment-option-div").style.display="block";
            $("#payment_option_dis").val("1");
        }
    }
</script>
<script>
    $(document).ready(function() {

        var top = Number(200);

        $('.orders').html('<div style="text-align:center;width:100%;height:' + (top * 2) + 'px; position:relative;top:' + top + 'px;"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>');

        var state = check_login_stat('state');

        state.success(function(data) {
            //  alert(data);

            if (data == ' hypass') {

                load_orders();

            } else {

                signin('guest_checkout');

            }

        });

    });



    function load_orders() {

        var top = Number(200);
       

        $('.orders').html('<div style="text-align:center;width:100%;height:' + (top * 2) + 'px; position:relative;top:' + top + 'px;"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>');

        $('.orders').load('<?php echo base_url(); ?>home/cart_checkout/orders');

    }



    function load_address_form() {



        var top = Number(200);

        $('.delivery_address').html('<div style="text-align:center;width:100%;height:' + (top * 2) + 'px; position:relative;top:' + top + 'px;"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>');



        $('.delivery_address').load('<?php echo base_url(); ?>home/cart_checkout/delivery_address',

            function() {

                var top_off = $('.header').height();

                $('.selectpicker').selectpicker();

                $('html, body').animate({

                    scrollTop: $(".delivery_address").offset().top - (2 * top_off)

                }, 1000);

            }

        );

    }

    function get_quotation() {
        $('input[type="radio"]').each(function(index, item) {
            if($(this).is(':checked')){
                const grand = $(document).find('#grand');
                const priceBreakDownTotal = $(document).find('#priceBreakDownTotal');
                const userlat = $(this).parent().data('userlat');
                const userlng = $(this).parent().data('userlng');
                const storelat = $(this).parent().data('storelat');
                const storelng = $(this).parent().data('storelng');
                const storeaddress = $(this).parent().data('storeaddress');
                const useraddress = $(this).parent().data('useraddress');
                console.log(`${userlat},${userlng},${storelat},${storelng},${useraddress},${storeaddress}`);
                const url = "<?= base_url()?>home/get_quotation";
                console.log(url);
                $.post(url,{
                    userlat,
                    userlng,
                    storelat,
                    storelng,
                    storeaddress,
                    useraddress
                },res => {
                    console.log(res);
                    if(res){

                        const jsonData = JSON.parse(res).data;
                        let grandPrice = grand.text().replace('RM','');
                        const finalValue = parseFloat(grandPrice) + parseFloat(jsonData.priceBreakdown.total);
                        priceBreakDownTotal.text(jsonData.priceBreakdown.total);
                        grand.text('RM'+ finalValue);
                        console.log('final vlaue', finalValue);
                        // $this->session->set_userdata('delivery_final_value',finalValue);
                        <?php 
                        //$abc = "<script>document.write(finalValue)</script>";
                        // $this->session->set_userdata('delivery_final_value',$abc);
                       
                        ?> 
                        
                        load_payments();
                    }
                })
            }
        });
    }



    function load_payments() {
        var okay = 'yes';
        var sel = 'no';
		<?php if($this->session->userdata('user_login')!= "yes"){  ?>
        $('.delivery_address').find('.required').each(function(){

            if($(this).is('select') || $(this).is('input')){
				
               var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
                if (testEmail.test($(".email").val())){}else{
                     $(this).closest('.form-group').find('.email').focus();
                }
                if ($('#zip').val().length < 5){
                    okay = 'no';
                    $(this).closest('.form-group').find('#zip').focus();
                }
                    
                 if ($('#phone').val().length < 9){
                     okay = 'no';
                     $(this).closest('.form-group').find('#phone').focus();
                 }


                if($(this).val() == ''){

                    okay = 'no';

                    if($(this).is('select')){

                        $(this).closest('.form-group').find('.selectpicker').focus();

                    } else {

                        if(sel == 'no'){

                            $(this).focus();

                        }

                    }



                    //alert(okay);

                    //$(this).css('background','red');

                }

            }

        });
		<?php }  else { ?>
		    
		     
		    <?php
		    $user = $this->session->userdata('user_id'); 
            $addre_count = $this->db->get_where('shipping_address',array('user_id'=>$user))->result_array();  
		    if(count($addre_count)>0) 
		    {
		    ?>
		 if($(this).is('select')){
                if($(this).val() == ''){
					okay = 'no';
				}
		 }
		<?php } else  {
		    ?>
		   
		    okay = 'no';
		    
	<?php }	} ?>
        if (okay == 'yes') {
            $('#order_place_btn').removeClass('disabled')
            var top = Number(200);

            $('.payments-options').html('<div style="text-align:center;width:100%;height:' + (top * 2) + 'px; position:relative;top:' + top + 'px;"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>');

            $('.payments-options').load('<?php echo base_url(); ?>home/cart_checkout/payments_options',

                function() {

                    var top_off = $('.header').height();

                    $('html, body').animate({

                        scrollTop: $(".payments-options").offset().top - (2 * top_off)

                    }, 1000);

                }

            );

        } else {

            var top_off = $('.header').height();

            $('html, body').animate({

                scrollTop: $(".delivery_address").offset().top - (2 * top_off)

            }, 1000);

        }

    }



    function radio_check(id) {

        $("#visa").prop("checked", false);

        $("#mastercardd").prop("checked", false);

        $("#mastercard").prop("checked", false);

        $("#ccavenue").prop("checked", false);

        $("#" + id).prop("checked", true);

    }
</script>
<style>
    /* Tooltip container */
    .tooltip1 {
        position: relative;
        display: inline-block;
        color: #333;
    }

    .tooltip1 .tooltiptext1 {
        visibility: hidden;
        width: 230px;
        background-color: #fff;
        color: #333;
        text-align: left;
        padding: 0;
        border-radius: 3px;
        position: absolute;
        z-index: 1;
        top: 100%;
        left: 0;
        border: 1px solid #ccc;
        font-size: 12px;
    }

    .tooltip1 .tooltiptext1 p {
        margin-bottom: 0px;
        padding: 5px;


    }

    /* Show the tooltip text when you mouse over the tooltip container */
    .tooltip1:hover .tooltiptext1 {
        visibility: visible;
    }

    .tooltiptext1::before {
        content: '';
        position: absolute;
        top: -9px;
        left: 2px;
        width: 0;
        height: 0;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-bottom: 10px solid #ccc;
    }

    .wells {
        min-height: 20px;
        padding: 5px;
        margin-bottom: 20px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
    }
</style>