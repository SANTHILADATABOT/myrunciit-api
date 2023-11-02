<style>
   .brr .af-inner{
   border-bottom:1px solid #fff;
   }
   .brr .outer{
   border:#f5f5f5 solid 1px;
   }
   .brr .form-group {
   margin-bottom: 2px;
   padding: 10px;
   }
   .form-control{
   line-height:inherit;	
   }
   .cbz{
   border: 3px solid #eee;
   margin-top: 27px !important;	
   }
   .mpt-5{
   margin-top:3px !important;
   }
   .mv-5{
   margin-left:15px;
   }
   .af-inner{
   position:relative;	
   }
   
   .af-inner .tvg label{
   position: absolute;
   top: 22px;
   left: 15px;
   }
</style>
<?php 

   $sale_details = $this->db->get_where('sale',array('sale_id'=>$sale_id))->result_array();
   foreach($sale_details as $row){
   		$order_id=$row['sale_code'];
   		$payment_mode=$row['payment_type'];
		$info = json_decode($row['shipping_address'],true);
		$user_id=$row['buyer'];
		$f_name=$info['firstname'];
   		$l_name=$info['lastname'];
		$address1=$info['address1'];
		$address2=$info['address2'];
		$zip=$info['zip'];
		$phone=$info['phone'];
		$email=$info['email'];
		$ordered_date=date('d M, Y',$row['sale_datetime'] );
		$product_details = json_decode($row['product_details'], true);
		$i =0;
		$total = 0;
		foreach ($product_details as $row1) {
			$i++;
			$p_name=str_replace('%20', ' ',$row1['name']);
			$qty=$row1['qty'];
			$price=$row['grand_total'];
		}
	}

     echo form_open(base_url() . 'index.php/home/return_details/return/'.$sale_id.'/'.$product_id, array(
   
                        'class' => 'contact-form',
   
                        'method' => 'post',
   
                        'enctype' => 'multipart/form-data',
   
                        'id' => 'cancel_details'
   
                    ));
   
                ?>
<section class="page-section color">
   <div class="container">
      <div class="row">
         <div class="col-md-5">
            <div class="contact-info">
               <h2>
                  <span>
                  <?php echo translate('Return_Your_Order');?>
                  </span>
               </h2>
            </div>
         </div>
         <div class="col-md-12 ">
         		
            <div class="col-md-6 brr">
               <div class="outer">
                  <div class="form-group af-inner">
                     Order ID :
                     <label><?php echo $order_id; ?></label>
                    
                  </div>
               </div>
               <div class="outer ">
                  <div class="form-group af-inner">
                     Name: <label><?php echo $f_name.' '.$l_name; ?></label>
                  </div>
               </div>
               <div class="outer ">
                  <div class="form-group af-inner">
                     Address1:  <label><?php echo $address1; ?></label>
                    
                  </div>
               </div>
               <div class="outer">
                  <div class="form-group af-inner tvg">
                     Payment Type"<label> <?php echo $payment_mode; ?></label>
                    
                  </div>
               </div>
               
            </div>
            <div class="col-md-6 brr">
               <div class="outer">
                  <div class="form-group af-inner">
                     Address2:  <label><?php echo $address2; ?></label>
                     
                  </div>
               </div>
               <div class="outer ">
                  <div class="form-group af-inner">
                     Zip:  <label><?php echo $zip;?></label>
                    
                  </div>
               </div>
               <div class="outer ">
                  <div class="form-group af-inner">
                     Email:  <label><?php echo $email; ?></label>
                    
                  </div>
               </div>
               <div class="outer ">
                  <div class="form-group af-inner">
                     Order date:  <label><?php echo $ordered_date; ?></label>
                     
                  </div>
               </div>
               <div class="outer">
                  <div class="form-group af-inner">
                     Price :<label><?php echo $price; ?></label>
                    
                  </div>
               </div>
            </div>
             <div class="col-md-12 ">
            <div class="outer">
                  <div class="form-group af-inner tvg">
                     <label>  Return Reason</label>
                     <?php 
                        $return_reason= $this->db->get_where('return_reason	',array('status' => 1))->result_array();
                        ?>
                     <select name="return_reason" id="return_reason" class="form-control">
                        <?php foreach($return_reason as $retreason)   { ?>
                        <option value="<?php echo $retreason['id'];?>"><?php echo $retreason['return_reason'];?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="col-md-12 ">
            <div class="outer">
                  <div class="form-group af-inner tvg">
                     <label>  Return action</label>
                    
                     <select name="return_action" id="return_action" class="form-control">
                       
                        <option value="">Select Reason</option>
                        <option value="1">Exchange</option>
                        <option value="2">Refund</option>
                       
                     </select>
                  </div>
               </div>
            </div>
            <div class="clearfix">
               
               <div class="col-md-12 mpt-5">
                  <div class="outer">
                     <div class="form-group af-inner">
                        Remarks 
                        <textarea name="message" id="input-message" placeholder="Details about service" rows="2" cols="50" class="form-control placeholder test" data-toggle="tooltip" title="" style="height:auto"></textarea>
                     </div>
                  </div>
               </div>
            </div>
            <div class="clearfix col-md-12">
               <div class="outer required">
                  <div class="form-group af-inner mv-5">
                     <span class="form-button-submit btn btn-theme return_order enterer" data-ing='<?php echo translate('sending..'); ?>'>
                     <?php echo translate('return_order'); ?>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
</form> 


