
<?php //if($modes='shopping') { ?>
<section class="page-section invoice">
    <div class="container">
<?php
//echo $address_unicid;
//$this->db->select_sum('grand_total');
$saleDet=$this->db->get_where('sale',array('order_id'=>$order_id))->result_array();
//echo "<pre>"; print_r($saleDet); echo "</pre>";
$saleDet=$saleDet[0];
$grand_total=$saleDet['grand_total'];
$shipping_address=$this->db->get_where('shipping_address',array('id'=>$address_unicid))->result_array();
//echo $this->db->last_query(); exit;
$shipping_address=$shipping_address[0];
//print_r($shipping_address); exit;
$merchantId = $this->db->get_where('business_settings' , array('type' => 'cca_merchant_id'))->row()->value;
?>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="invoice_body">
                    <div class="row">
                    <?php
                    echo form_open(base_url() . 'index.php/home/ccav_requesthandler/', array(
                        'class' => 'form-login',
                        'method' => 'post',
                        'id' => 'frmPayment',
						'name'=>'frmPayment'
                    ));
					?>
                    <?php //echo $saleDet['grand_total']; ?>
                            <input type="hidden" name="merchant_id" value="<?php echo $merchantId; ?>"> 
                            <input type="hidden" name="language" value="EN"> 
                            <input type="hidden" name="amount" value="1.00">
                            <input type="hidden" name="currency" value="INR"> 
                            <input type="hidden" name="redirect_url" value="<?php echo base_url(); ?>index.php/home/ccav_payment_success/"> 
                            <input type="hidden" name="cancel_url" value="<?php echo base_url(); ?>index.php/home/ccav_payment_cancel/"> 
                            
                            <div>
                            <input type="hidden" name="billing_name" value="<?php echo $shipping_address['name']; ?>" class="form-field" Placeholder="Billing Name"> 
                            <input type="hidden" name="billing_address" value="<?php echo $shipping_address['address']; ?>" class="form-field" Placeholder="Billing Address">
                            </div>
                            <div>
                            <input type="hidden" name="billing_city" value=<?php echo $shipping_address['city']; ?>/>
                            <input type="hidden" name="billing_state" value="<?php echo $shipping_address['state']; ?>" class="form-field" Placeholder="State"> 
                            <input type="hidden" name="billing_zip" value="<?php echo $shipping_address['zip_code']; ?>" class="form-field" Placeholder="Zipcode">
                            </div>
                            <div>
                            <input type="hidden" name="billing_country" value=<?php echo $shipping_address['country']; ?> class="form-field" Placeholder="Country">
                            <input type="hidden" name="billing_tel" value=<?php echo $shipping_address['mobile']; ?> class="form-field" Placeholder="Phone">
                            </div> 
                            <div>
                            <input type="hidden" name="billing_email" value=<?php echo $shipping_address['email']; ?> class="form-field" Placeholder="Email">
                            </div>
                            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"  >
                            <div>
                          <?php /* <button class="btn-payment" type="submit">Pay Now</button>*/ ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
                
    </div>
</section>
<script language='javascript'>document.frmPayment.submit();</script>

<?php// } ?>