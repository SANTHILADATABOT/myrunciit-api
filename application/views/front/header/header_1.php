<?php
		echo form_open(base_url() . 'index.php/home/getNearestVendorName/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'get_store_name',
			'enctype' => 'multipart/form-data'
		));
	?>
<!-- Header top bar -->
<div class="top-bar">
    <div class="container-fluid" style="padding:0px;">
        <div class="top-bar-left">
            <ul class="list-inline">
                <li class="">

                    <a class="wpsm-button white">
<?php //echo $this->session->userdata('pickup'); ?>
                       <!--<i class="pull-left"><img src="<?php //echo base_url(); ?>uploads/home_pages/maps-and-flags.png" alt="Delivery Check" style="width: 16px;"></i>-->
					   <?php //if ($this->session->userdata('user_zips')!="") { echo "Delivery to : ".$this->session->userdata('user_zips');} else if ($this->session->userdata('pickup')!="") {  echo  "Pickup :".$this->db->get_where('vendor' , array('vendor_id' => $this->session->userdata('pickup_loc')))->row()->name;} else { echo "pickup/delivery";} ?>

                    </a>
                </li>



                <!-- <li class=""><p><center>
                <a href="<php echo base_url() . 'home/getNearestVendorName/'; ?>" class="orderFrom" style="text-align:center;">
                <php 
                    echo "VENDOR NAME:".$vendorName;
                ?>(Change area)
                </a></center></p></li> -->
                <!---------------------------------------->
                <!-- <li class="">
    <p><center>
        <a href="#" class="" style="text-align:center;" data-toggle="modal" data-target="#myModala1">
            <php 
            echo "VENDOR NAME: " . $vendorName; 
            ?>(Change area)
        </a>
    </center></p>
</li> -->

<li class="">
    <p><center>
        <a href="#" class="" style="text-align:center;" data-toggle="modal" data-target="#myModala1">
        <?php
        $cart_get= $this->cart->contents();
        $count_cart= count($cart_get);
        //echo $count_cart;
        if($count_cart=="0"){

    ?>
        <a href="#" class="" style="text-align:center;" data-toggle="modal" data-target="#myModala1">
      <?php  
        } else 
       { ?>

        <a href="#" class="" style="text-align:center;" data-toggle="" data-target="">
    <?php
       }
           
           $vendorName = $this->session->userdata('vendorName');
           $vendorid = $this->session->userdata('vendorid');
           echo $vendorName;
               
            if($vendorName == ""){
                
                  $vendorName =$this->crud_model->default_vendorname();
                  echo $vendorName;
               
            }
            $cart_get= $this->cart->contents();
            $count_cart= count($cart_get);
           // echo $count_cart;
            if($count_cart=="0"){
            ?>(Change area)

            <?php }?>

            <!------------------------------------------------>
            <script>
    <?php if ($above15kms || $invalidCode): ?>
        // Show the modal
        var modal = document.getElementById("modal2");
        var closeButton = document.getElementsByClassName("close")[0];

        modal.style.display = "block";

        // Close the modal when the close button is clicked
        closeButton.onclick = function() {
            modal.style.display = "none";
        }

        // Close the modal when the background is clicked
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    <?php endif; ?>
</script>

            <!------------------------------------------------>

        </a>
    </center></p>
</li>

<!-- new code Include Owl Carousel CSS and JavaScript -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>


<style>
    /* Modal styles */
.modal2 {
  display: none; /* Hidden by default */
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
  z-index: 1;
}
.modal-content2 {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 10px; /* Adjust padding to reduce content size */
  border: 1px solid #888;
  max-width: 700px; /* Set a maximum width for the modal */
  width: 90%; /* Set a width, but allow it to adjust based on content */
}

/* Close button */
.close {
    color: black;
  /* color: #aaa; */
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
.navigation-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
}

.navigation {
  text-align: center;
}

.navigation-row {
  display: flex;
  align-items: center;
}

.navigation-wrapper .navigation-row .nav.sf-menu {
  white-space: nowrap; 
  overflow-x: auto; 
  display: flex; 
}

.navigation-wrapper .navigation-row .nav.sf-menu .category-item {
  margin-right: 15px;
  margin-top: 28px;
  margin-bottom: 4px;
  display: inline-block;
  white-space: nowrap; 
  height: 45px;
}

.category-item a {
  color: white;
  text-decoration: none;
}

.navigation-button {
  background-color: transparent;
  border: none;
  color: white;
  font-size: 24px;
  cursor: pointer;
}

.prev-button {
  margin-right: 10px;
}

.next-button {
  margin-left: 8px; 
}
.owl-carousel .owl-nav .owl-prev {
  display: none !important;
}
.owl-carousel .owl-dots {
  display: none !important;
}
</style>

<!-------------------------------Modal2----------------------------------->
<div id="modal2" class="modal" role="dialog" style="margin-top: 5%;">
<div class="modal-dialog">
  <div class="modal-content2">
    <span class="close">&times;</span>
    <p>&nbsp;</p>
    <!-- <div class="modal-body"> -->
    <h3 style="color:#e12c2e;"><center>You're Currently Shopping at</center></h3>
                <center><img src="<?php echo base_url(); ?>template/front/img/mricon.png" width="80px" /></center>
                <center><h4 style="text-align: center;">Oops! We don't serve in this area.</h4></center> 
                <!-- <center><h5 style="text-align: center;color:#e12c2e" onclick="spaned()">Please try a different zip code</h5></center>    -->
                <?php
                    echo '<center><li><a href="#myModala1" style="color: #e12c2e; text-align: center;" data-toggle="modal" data-dismiss="modal">' . " Please try a different zip code " . '</a></li></center>';
                ?>
            <!-- </div> -->
  </div>
</div>
</div>

<!---------------------------------------------Modal1Starts---------------------------------------------------->
<div id="myModala1" class="modal fade" role="dialog" style="margin-top: 5%;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="margin-top: -3%;">&times;</button>

                <!-- <h4 class="modal-title">Modal Header</h4> -->
            <!-- </div> -->
            <div class="modal-body">
                <h3 style="color:#e12c2e;"><center>You're Currently Shopping at</center></h3>
                <center><img src="<?php echo base_url(); ?>/template/front/img/mricon.png" width="80px" /></center>
                <p><center style="font-size:18px;color:#a7a7a7;padding-bottom:12px">Please Enter your Postcode Below</center></p>
                <center><label for="fname">Enter Postcode</label></center>
                <center><input type="text" id="pin" name="pin" onchange="pin_val();"></center><br>
                <center><button type="submit" id="checking" class="btn btn-danger">Check</button></center>
                <!-- <php
                 if($this->session->userdata('above15kms')) 
                 {  
                    echo '<p style="color: #e12c2e; text-align: center;">' . $above15kms . '</p>';
                 } 
                ?> -->
            </div>
        </div>
    </div>
</div>
<!-----------------------------------------Modal1Ends---------------------------------------------------------->
<script>
  
   $(document).ready(function()
   {
   // alert("hi");
   pin_val()
   });
   function pin_val()
    {
        let pin_value = document.getElementById('pin').value;
        let button_check = document.getElementById('checking');
        console.log(pin_value);
        let res = pin_value.trim();
        if(res=="")
        {
            button_check.disabled = true;
        }
        else
        {
            button_check.disabled = false;
        }
        
    }
    </script>
<!-----------------------------------------Modal2Starts-------------------------------------------------------->
                <div id="above15kmsModal" class="modal fade" role="dialog" style="margin-top: 5%;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <!-- <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <!-- <h4 class="modal-title">Modal Header</h4> -->
            <!-- </div> -->
            <div class="modal-body">
                <h3 style="color:#e12c2e;"><center>You're Currently Shopping at</center></h3>
                <center><img src="<?php echo base_url(); ?>/template/front/img/mricon.png" width="80px" /></center>
                <?php
                    echo '<center><li><a href="#myModala1" style="color: #e12c2e; text-align: center;" data-toggle="modal" data-dismiss="modal">' . "Oops! We don't serve in this area. Please try a different zip code " . '</a></li></center>';
                ?>
            </div>
        </div>
    </div>
</div>
<!-----------------------------------------Modal2Ends------------------------------------------>             
 
                <!--<li class=""><p> <i class="pull-left"><img src="<?php //echo base_url(); ?>uploads/home_pages/maps-and-flags.png" alt="Delivery Check" style="width: 16px;"></i>Current address: <a href="#"><?php //if($this->session->userdata('pickup')!="") {  echo  $this->db->get_where('vendor' , array('vendor_id' => $this->session->userdata('pickup_loc')))->row()->zip;} else{ echo $this->db->get_where('vendor',array('default_set' => 'ok'))->row()->zip; } ?></a></p></li>-->
                
                <?php
                //$facebook =  $this->db->get_where('social_links',array('type' => 'facebook'))->row()->value;
            	//$instagram =  $this->db->get_where('social_links',array('type' => 'instagram'))->row()->value;
            	?>
            	
                <!--<li class="right"><a href="<?php echo $facebook; ?>"><i class="fa fa-facebook"></i></a></li>
                <li class="right"><a href="<?php echo $instagram; ?>"><i class="fa fa-instagram"></i></a></li>-->
                <?php 
                date_default_timezone_set("Asia/Kuala_Lumpur");
          $cur_dt=date('Y-m-d');
               // $this->db->order_by('id', 'desc');
                $this->db->where('status', 'ok');
           $pre_dts= $this->db->get('pre_order')->result_array();
          $s_dt=$pre_dts[0]['start_date'];
          $e_dt=$pre_dts[0]['end_date'];
            //if($pre_dts[0]['status']=='ok' && $s_dt <= $cur_dt && $e_dt>=$cur_dt){ 
            if($pre_dts[0]['status']=='ok' && $e_dt>$cur_dt) { ?>
                <!--<li class="center blink"><img src="<?php //echo base_url(); ?>uploads/preorder.png" alt="" width="100px;"></li>-->
                <?php } ?>
                </ul>
                <!--<li class="dropdown flags">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php
                            if($set_lang = $this->session->userdata('language')){} else {
                                $set_lang = $this->db->get_where('general_settings',array('type'=>'language'))->row()->value;
                            }
                            $lid = $this->db->get_where('language_list',array('db_field'=>$set_lang))->row()->language_list_id;
                            $lnm = $this->db->get_where('language_list',array('db_field'=>$set_lang))->row()->name;
                        ?>
                        <img src="<?php echo $this->crud_model->file_view('language_list',$lid,'','','no','src','','','.jpg') ?>" width="20px;" alt=""/> <span class="hidden-xs"><?php echo $lnm; ?></span><i class="fa fa-angle-down"></i></a>
                        <ul role="menu" class="dropdown-menu">
                            <?php
                                $langs = $this->db->get_where('language_list',array('status'=>'ok'))->result_array();
                                foreach ($langs as $row)
                                {
                            ?>
                                <li <?php if($set_lang == $row['db_field']){ ?>class="active"<?php } ?> >
                                    <a class="set_langs" data-href="<?php echo base_url(); ?>index.php/home/set_language/<?php echo $row['db_field']; ?>">
                                        <img src="<?php echo $this->crud_model->file_view('language_list',$row['language_list_id'],'','','no','src','','','.jpg') ?>" width="20px;" alt=""/>
                                        <?php echo $row['name']; ?>
                                        <?php if($set_lang == $row['db_field']){ ?>
                                            <i class="fa fa-check"></i>
                                        <?php } ?>
                                    </a>
                                </li>
                            <?php
                                }
                            ?>
                    </ul>
                </li>
                <li class="dropdown flags" style="z-index: 1001;">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php      
                            $userid= $this->session->userdata('user_id'); 
                        if($userid!=''){
                              $currency_id =$this->db->get_where('user' ,array('user_id' => $userid))->row()->default_currency_id;
                             if($currency_id=='' || $currency_id=='0' ){
                                    if($currency_id = $this->session->userdata('currency')){} else {
                                        $currency_id = $this->db->get_where('business_settings', array('type' => 'currency'))->row()->value;
                                    }
                             }
                        }
                        else{
                            if($currency_id = $this->session->userdata('currency')){} else {
                                $currency_id = $this->db->get_where('business_settings', array('type' => 'currency'))->row()->value;
                            }
                        }
                            $symbol = $this->db->get_where('currency_settings',array('currency_settings_id'=>$currency_id))->row()->symbol;
                            $c_name = $this->db->get_where('currency_settings',array('currency_settings_id'=>$currency_id))->row()->name;
                        ?>
                        <span class="hidden-xs"><?php echo $c_name; ?></span> (<?php echo $symbol; ?>)
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul role="menu" class="dropdown-menu currency">
                        <?php
                            $currencies = $this->db->get_where('currency_settings',array('status'=>'ok'))->result_array();
                            foreach ($currencies as $row)
                            {
                        ?>
                            <li <?php if($currency_id == $row['currency_settings_id']){ ?>class="active"<?php } ?> >
                                <a class="set_langs" data-href="<?php echo base_url(); ?>index.php/home/set_currency/<?php echo $row['currency_settings_id']; ?>">
                                    <?php echo $row['name']; ?> (<?php echo $row['symbol']; ?>)
                                    <?php if($currency_id == $row['currency_settings_id']){ ?>
                                        <i class="fa fa-check"></i>
                                    <?php } ?>
                                </a>
                            </li>
                        <?php
                            }
                        ?>
                    </ul>

                </li>-->
                <?php if($this->crud_model->get_type_name_by_id('general_settings','83','value') == 'ok'){ ?>
                    <!--<li class="dropdown flags" style="z-index: 1001;">
                        <a href="<?=base_url()?>index.php/home/premium_package" class="" >
                            <i class="fa fa-gift"></i> <?php echo translate('premium_packages');?>
                        </a>
                    </li>-->
                <?php } ?>

            </ul>
</form>
         </div>

    </div>
</div>
<!-- /Header top bar -->

<!-- HEADER -->
<header class="header header-logo-left">
    <div class="header-wrapper">
        <div class="container">
            <!-- Logo -->
            <div class="logo">
            	<?php
					$home_top_logo = $this->db->get_where('ui_settings',array('type' => 'home_top_logo'))->row()->value;
				?>
                
           <a href="#" class="menu-toggle btn btn-theme-transparent"><i class="fa fa-bars"></i></a>
 
                
                <a href="<?php echo base_url();?>">
                	<img src="<?php echo base_url(); ?>uploads/logo_image/logo_<?php echo $home_top_logo; ?>.png" alt="logo"/>
             	</a>
            </div>
            <!-- /Logo -->
            <div class="hero1 hidden-xs hidden-sm ">
            <div class="hovermenu ttmenu">
              
                  <!-- end navbar-header -->
                  <!--<ul class="nav navbar-nav">
                     <li class="dropdown ttmenu-full active">
                        <a href="<?php echo base_url(); ?>index.php/home/all_category" data-toggle="dropdown" class="dropdown-toggle" style="width: auto;padding: 8px 18px;color: #fff;background: transparent;line-height: 26px;margin-top: 11px;font-size: 24px;"><i class="fa fa-bars"></i></a>
                        <?php 
						$this->db->limit(13);
						
						
						$all_category = $this->db->get('category')->result_array();	 ?>
								
                        <ul id="first-menu" class="dropdown-menu">
                       
                           <li>
                              <ul class="nav nav-pills nav-stacked main_b col-md-3">
                               <?php 
							   
							   	foreach($all_category as $row)
								{
								    	if($this->crud_model->if_publishable_category($row['category_id'])){
					//if($this->crud_model->is_publishable_count('category',$row['category_id'])>0) {
					                  
								 ?>
                                 <li class=""><a href="<?php echo base_url(); ?>index.php/home/category/<?php echo $row['category_id']; ?>" data-target="#main_<?php echo $row['category_id']; ?>"><?php echo $row['category_name']; ?></a></li>
                                 
                                 <?php  
					} //}
								 } 
								 if(count($all_category)>=13){
								 ?>
                                 <li><a href="<?php echo base_url(); ?>index.php/home/all_category">View All</a></li>
                                 <?php }?>
                              </ul>
                              <div class="tab-content col-md-9">
								 <?php 
							
							   	foreach($all_category as $row)
								{
									 ?>
                                 <div id="main_<?php echo $row['category_id']; ?>" class="tab-pane active">
                                    <div class="mm-content">
                                       <div class="tabbable col-md-12 pad-rt-0">
                                          <div class="col-md-3 pad-rt-0 desgn-fix1">
                                             <ul class="nav nav-pills nav-stacked inner">												 
                                             <?php 
                                             $this->db->limit(14);
									  
                    $sub_categories=$this->db->get_where('sub_category',array('category'=>$row['category_id']))->result_array();
                    foreach($sub_categories as $row1){
                    ?>
                                                <li class=""><a href="<?php echo base_url(); ?>index.php/home/category/<?php echo $row['category_id']; ?>/<?php echo $row1['sub_category_id']; ?>" data-target="#inner_<?php echo $row1['sub_category_id'];?>"><?php echo $row1['sub_category_name'];?></a></li>
                                                
                                               
                                               <?php   } 
											   if(count($sub_categories)>=14){
								 ?>
                                 <li><a href="<?php echo base_url(); ?>index.php/home/category/<?php echo $row['category_id']; ?>">View All</a></li>
                                 <?php }?>
                                             </ul>
                                          </div>
                                         <?php /* <div class="col-md-9 no-boxshadow desgn-fix">
                                             <div class="tab-content bxn">
                                              <?php 
											
											 
                  
                    foreach($sub_categories as $row2){ ?>
                                                <div id="inner_<?php echo $row2['sub_category_id'];?>" class="tab-pane active">
                                                   <div class="row padd0-15">
                                                      <div class="col-md-4 col-sm-6 col-xs-12 brs" style="">
                                                         <div class="box">
                                                            <ul>
                                                                              
                                                            </ul>
                                                         </div>
                                                         <!-- end box -->
                                                      </div>
                                                      <!-- end col -->
                                                      <div class="col-md-8 col-sm-6 col-xs-12 brs1">
                                                         <div class="row padd0-15">
                                                            <div class="col-md-6 pad-0 dont-show" >
                                                               <ul>
                                                                
                                                                  <li><a href="#">Popular Brands</a></li>
                                                                
                                                               </ul>
                                                            </div>
                                                            <div class="col-md-6 change-wid ng-scope" >
                                                               <div class="pad-0 mtye">
                                                                  <a href="javascript:void(0);">
                                                                     <img class="img-responsive pull-right immfr"  src="https://www.bigbasket.com/media/uploads/banner_images/NPL1533-NPL1534-29may20.jpg">
                                                                  </a>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      
                                                      
                                                      </div>
                                                      <!-- end col -->
                                                      <!-- end col -->
                                                   </div>
                                                   <!-- end row -->                  
                                                </div>
                                                <?php  } ?>
                                             </div>
                                          </div> */ ?>
                                       </div>
                                    </div>
                                 </div>
                        			<?php  } ?>
                              </div>
                              <!-- end ttmenu-content -->
                           </li>
                        </ul>
                     </li>
                     <!-- end mega menu -->
                     <!-- end mega menu -->
                     <!-- end mega menu -->
                     <!-- end mega menu -->
                  </ul>
               
               <!-- end navbar navbar-default clearfix -->
            </div>
            <!-- end menu 1 -->  
         </div>
            <div class="top-bar-right">
            ----- ----- -----
        	</div>
        	            <div class="top-ba-right">
        	            <a href="#" class="btn btn-theme-transparent" data-toggle="modal" data-target="#popup-cart" style="margin-right:15px">
                        <img src="<?php echo base_url(); ?>template/img/cart.png" alt="cart" class="hea-imge" /> 
                        <span class="cart_num"></span>
                        <span class="jum hidden-sm hidden-xs">Cart</span>  
                    </a>
                    <!----------------------------------------COMPARE----------------------------------------------->      
                    <a href="<?php echo base_url(); ?>index.php/home/compare" class="btn btn-theme-transparent" id="compare_tooltip" data-toggle="tooltip" data-original-title="<?php echo $this->crud_model->compared_num(); ?>" data-placement="top" >
                    	<img src="<?php echo base_url(); ?>template/img/compare.png" alt="Compare" class="hea-imge" />
						<span class="jum hidden-sm hidden-xs"><?php echo translate('compare'); ?></span>
                    </a>
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                 <!----------------------------------------COMPARE----------------------------------------------->
                    </div>
            <!-- Header shopping cart -->
            <div class="header-cart">
                <div class="cart-wrapper">
                    <!--<a href="<?php echo base_url(); ?>index.php/home/compare" class="btn btn-theme-transparent" id="compare_tooltip" data-toggle="tooltip" data-original-title="<?php echo $this->crud_model->compared_num(); ?>" data-placement="right" >
                    	<img src="<?php echo base_url(); ?>template/img/compare.png" alt="Compare" class="hea-imge" />
                        <span id="compare_num"><?php echo $this->crud_model->compared_num(); ?></span>
						<span class="jum hidden-sm hidden-xs"><?php echo translate('compare'); ?></span>
                    </a>-->
                    <!--<a href="#" class="btn btn-theme-transparent" data-toggle="modal" data-target="#popup-cart">
                        <img src="<?php echo base_url(); ?>template/img/cart.png" alt="cart" class="hea-imge" /> 
                        <span class="cart_num"></span>
                        <span class="jum hidden-sm hidden-xs">Cart</span>  
                    </a>-->
                    <!-- Mobile menu toggle button -->
                    <!-- /Mobile menu toggle button -->
                </div>
            </div>
            <!-- Header shopping cart -->
			<div class="clearfix"></div>
            <!-- Header search -->
            <div class="header-search">                            
                <?php
                    echo form_open(base_url() . 'index.php/home/text_search/', array(
                        'method' => 'post'
                    ));
                ?>
               
                    <input class="form-control" type="text" name="query" id="query" placeholder="<?php echo translate('what_are_you_looking_for');?>?" autocomplete="off"/>
                    <select
                        class="selectpicker header-search-select cat_select hidden" data-live-search="true" name="category"
                        data-toggle="tooltip" title="<?php echo translate('select');?>">
                        <option value="0"><?php echo translate('all_categories');?></option>
                        <?php 
                            if($vendorid=="")
                            {
                             $vendorid ='2';
                           //  echo "id=".$vendorid;
                            }
                            
             
                              $this->db->select('v.vendor_id,p.store_id,p.category');
                              $this->db->from('vendor as v');
                              $this->db->join('product as p', 'p.store_id = v.vendor_id');
                              $this->db->where('v.vendor_id',$vendorid);
                              $val= $this->db->get()->result_array();
                              $cat_values = array();
                              foreach($val as $result)
                              {
                                 $get_category =$result['category'];
                                 if (!in_array($get_category, $cat_values)) {
                                  $this->db->where('category_id',$get_category);
                                  $all_category = $this->db->get('category')->result_array();
             
                                 foreach($all_category as $row)
                                 {
                                 if($this->crud_model->if_publishable_category($row['category_id'])){
                                    ?>
                        <option value="<?php echo $row['category_id']; ?>"><?php echo $row['category_name']; ?></option>
                        <?php 
                         $cat_values[] = $get_category;
                                  }
                                }
								}
                            }
                        ?>
                    </select>
                     <button class="shrc_btn"><i class="fa fa-search"></i></button>
                    
                    <?php
                    	if ($this->crud_model->get_type_name_by_id('general_settings','58','value') == 'ok') {
					?>
                    <select
                        class="selectpicker header-search-select hidden" data-live-search="true" name="type" onchange="header_search_set(this.value);"
                        data-toggle="tooltip" title="<?php echo translate('select');?>">
                        <option value="product"><?php echo translate('product');?></option>
                        <option value="vendor"><?php echo translate('vendor');?></option>                    
                    </select>
                    <?php
						}
					?>

                </form>
            </div>
            
            <!-- /Header search -->
            
        </div>
    </div>
   <div class="navigation-wrapper">
        <div class="container">
            <!-- Navigation -->
            <?php
            	$others_list=$this->uri->segment(3);
			?>
            <nav class="navigation closed clearfix">
                <a href="#" class="menu-toggle-close btn"><i class="fa fa-times"></i></a>
                <div class="navigation-row">
                    <div class="navigation-button prev-button">
                    <i class="fa fa-chevron-left"></i>
                    </div>
                <ul class="nav sf-menu owl-carousel owl-theme" id="categories-carousel">
                <?php
								if($vendorid=="")
                                {
                                 $vendorid ='2';
                               //  echo "id=".$vendorid;
                                }
                                
                 
                                  $this->db->select('v.vendor_id,p.store_id,p.category');
                                  $this->db->from('vendor as v');
                                  $this->db->join('product as p', 'p.store_id = v.vendor_id');
                                  $this->db->where('v.vendor_id',$vendorid);
                                  $val= $this->db->get()->result_array();
                                  $cat_values = array();
                                  foreach($val as $result)
                                  {
                                     $get_category =$result['category'];
                                     if (!in_array($get_category, $cat_values)) {
                                      $this->db->where('category_id',$get_category);
                                      $all_category = $this->db->get('category')->result_array();
                 
                                     foreach($all_category as $row)
                                     {
                                     if($this->crud_model->if_publishable_category($row['category_id'])){
                                       
                                      }
							?>
                            <li  class="category-item">
                                <a href="<?php echo base_url(); ?>index.php/home/category/<?php echo $row['category_id']; ?>">
                                    <?php echo $row['category_name']; ?>
                                </a>
                            </li>
                            <?php
								$cat_values[] = $get_category;
                            }
                         }
                        }
							?>
                   <?php /*?> <li <?php if($asset_page=='home'){ ?>class="active"<?php } ?>>
                        <a href="<?php echo base_url(); ?>index.php/home">
                            <?php echo translate('homepage');?>
                        </a>
                    </li>
                    <li class="hidden-sm hidden-xs hidden<?php if($asset_page=='all_category'){ echo 'active'; } ?>">
                        <a href="<?php echo base_url(); ?>index.php/home/all_category">
							<?php echo translate('all_categories');?>
                        </a>
                        <ul>
                        	<?php
								$all_category = $this->db->get('category')->result_array();
								foreach($all_category as $row)
								{
									if($this->crud_model->if_publishable_category($row['category_id'])){
							?>
                            <li>
                                <a href="<?php echo base_url(); ?>index.php/home/category/<?php echo $row['category_id']; ?>">
                                    <?php echo $row['category_name']; ?>
                                </a>
                            </li>
                            <?php
									}
								}
							?>
                        </ul>
                    </li>
                    <li class="hidden-lg hidden-md hidden<?php if($asset_page=='all_category'){ echo 'active'; } ?>">
                        <a href="#">
							<?php echo translate('all_categories');?>
                        </a>
                        <ul>
                        	<?php
								$all_category = $this->db->get('category')->result_array();
								foreach($all_category as $row)
								{
									if($this->crud_model->if_publishable_category($row['category_id'])){
							?>
                            <li>
                                <a href="<?php echo base_url(); ?>index.php/home/category/<?php echo $row['category_id']; ?>">
                                    <?php echo $row['category_name']; ?>
                                </a>
                            </li>
                            <?php
									}
								}
							?>
                        </ul>
                    </li>
                    <li class="hidden-lg hidden-md <?php if($asset_page=='all_category'){ echo 'active'; } ?>">
                        <a href="<?php echo base_url(); ?>index.php/home/all_category">
                            <?php echo translate('all_sub_categories');?>
                        </a>
                    </li>
                    <li class="<?php if($others_list=='featured'){ echo 'active'; } ?>">
                        <a href="<?php echo base_url(); ?>index.php/home/others_product/featured">
                            <?php echo translate('featured_products');?>
                        </a>
                    </li>
                    <li class="<?php if($others_list=='todays_deal'){ echo 'active'; } ?>">
                        <a href="<?php echo base_url(); ?>index.php/home/others_product/todays_deal">
                            <?php echo translate('todays_deal');?>
                        </a>
                    </li>
                    <?php if($this->crud_model->get_type_name_by_id('general_settings','82','value') == 'ok'){
                            if($this->db->get_where('ui_settings',array('type'=>'header_bundled_product_status'))->row()->value == 'yes'){ ?>
                    <li <?php if($page_name=='bundled_product'){ ?>class="active"<?php } ?>>
                        <a href="<?php echo base_url(); ?>index.php/home/bundled_product">
                            <?php echo translate('combo_offer');?>
                        </a>
                    </li>
                     <?php } }?>
                    <?php if(0){
                            if(1){ ?>
                    <li <?php if($page_name=='customer_product_bulk_upload'){ ?>class="active"<?php } ?>>
                        <a href="<?php echo base_url(); ?>index.php/home/customer_product_bulk_upload">
                            <?php echo translate('Bulk upload');?>
                        </a>
                    </li>
                    <?php }} if($this->crud_model->get_type_name_by_id('general_settings','83','value') == 'ok'){
                                if($this->db->get_where('ui_settings',array('type'=>'header_classifieds_status'))->row()->value == 'yes'){?>
                    <li <?php if($page_name=='customer_products'){ ?>class="active"<?php } ?>>
                        <a href="<?php echo base_url(); ?>index.php/home/customer_products">
                            <?php echo translate('classifieds');?>
                        </a>
                    </li>
                    <?php }} ?>
                    <?php
                    	if ($this->crud_model->get_type_name_by_id('general_settings','58','value') !== 'ok') {
					?>
                    <li class="<?php if($others_list=='latest'){ echo 'active'; } ?>">
                        <a href="<?php echo base_url(); ?>index.php/home/others_product/latest">
                            <?php echo translate('latest_products');?>
                        </a>
                    </li>
                    <?php
						}
					?>
                    <?php
                    	if ($this->crud_model->get_type_name_by_id('general_settings','68','value') == 'ok') {
					?>
                    <li <?php if($asset_page=='all_brands'){ ?>class="active"<?php } ?>>
                        <a href="<?php echo base_url(); ?>index.php/home/all_brands/">
                            <?php echo translate('all_brands');?>
                        </a>
                    </li>
                    <?php
						}
					?>
                    <?php
                    	if ($this->crud_model->get_type_name_by_id('general_settings','58','value') == 'ok') {
					?>
                    <li <?php if($asset_page=='all_vendor'){ ?>class="active"<?php } ?>>
                        <a href="<?php echo base_url(); ?>index.php/home/all_vendor/">
                            <?php echo translate('all_vendors');?>
                        </a>
                    </li>
                    <?php
						}
					?>
                    <li class="hidden-sm hidden-xs hidden<?php if($asset_page=='blog'){ echo 'active'; } ?>">
                        <a href="<?php echo base_url(); ?>index.php/home/blog">
                            <?php echo translate('blogs');?>
                        </a>
                        <ul>
                        	<?php
								$blogs=$this->db->get('blog_category')->result_array();
								foreach($blogs as $row){
							?>
                            <li>
                                <a href="<?php echo base_url(); ?>index.php/home/blog/<?php echo $row['blog_category_id']; ?>">
                                    <?php echo $row['name']; ?>
                                </a>
                            </li>
                            <?php
								}
							?>
                        </ul>
                    </li>
                    <li class="hidden-lg hidden-md hidden<?php if($asset_page=='blog'){ echo 'active'; } ?>">
                        <a href="#">
                            <?php echo translate('blogs');?>
                        </a>
                        <ul>
                        	<?php
								$blogs=$this->db->get('blog_category')->result_array();
								foreach($blogs as $row){
							?>
                            <li>
                                <a href="<?php echo base_url(); ?>index.php/home/blog/<?php echo $row['blog_category_id']; ?>">
                                    <?php echo $row['name']; ?>
                                </a>
                            </li>
                            <?php
								}
							?>
                        </ul>
                    </li>
                     <li>
                        <a href="<?php echo base_url(); ?>index.php/home/all_themes">
                            <?php echo translate('themes');?>
                        </a>
                    </li>
                    <?php
                    	if ($this->crud_model->get_type_name_by_id('general_settings','58','value') == 'ok') {
					?>
                    <li class="hidden <?php if($asset_page=='store_locator'){ ?>active<?php } ?>">
                        <a href="<?php echo base_url(); ?>index.php/home/store_locator">
                            <?php echo translate('store_locator');?>
                        </a>
                    </li>
                    <?php
						}
					?>
                    <li class="hidden <?php if($asset_page=='contact'){ ?>active<?php } ?>">
                        <a href="<?php echo base_url(); ?>index.php/home/contact">
                            <?php echo translate('contact');?>
                        </a>
                    </li>
                    <li class="hidden">
                        <a href="#">
							<?php echo translate('more');?>
                        </a>
                        <ul>
                            <?php
								if ($this->crud_model->get_type_name_by_id('general_settings','58','value') == 'ok') {
							?>
							<li class="<?php if($others_list=='latest'){ echo 'active'; } ?>">
								<a href="<?php echo base_url(); ?>index.php/home/others_product/latest">
									<?php echo translate('latest_products');?>
								</a>
							</li>
							<?php
								}
							?>
                            <?php
							$this->db->where('status','ok');
                            $all_page = $this->db->get('page')->result_array();
							foreach($all_page as $row2){
							?>
                            <li>
                                <a href="<?php echo base_url(); ?>index.php/home/page/<?php echo $row2['parmalink']; ?>">
                                    <?php echo $row2['page_name']; ?>
                                </a>
                            </li>
                            <?php
							}
							?>
                        </ul>
                    </li><?php */?>
                </ul>
                <div class="navigation-button next-button">
          <i class="fa fa-chevron-right"></i>
        </div>
      </div>
            </nav>
            <!-- /Navigation -->
        </div>
    </div>
</header>
<!-- /HEADER -->



<?php  
 $curr =$this->session->userdata('currency');
 if($curr !=$currency_id){
  	redirect(base_url().'index.php/home/set_currency/'.$currency_id);
} ?>
<script> // new code
 $(document).ready(function() {
  var owl = $("#categories-carousel");
  owl.owlCarousel({
    items: 7,
    loop: false,
    margin: 15,
    nav: false, 
    dots: false,
    responsive: {
      0: {
        items: 2
      },
      768: {
        items: 4
      },
      992: {
        items: 7
      }
    },
    slideBy: 1, // Set to 1 to slide one item at a time
  });

  // Custom navigation event handlers
  $(".prev-button").click(function() {
    owl.trigger("prev.owl.carousel");
  });

  $(".next-button").click(function() {
    owl.trigger("next.owl.carousel");
  });
});
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.set_langs').on('click',function(){
            var lang_url = $(this).data('href');                                    
            $.ajax({url: lang_url, success: function(result){
                location.reload();
            }});
        });
        $('.top-bar-right').load('<?php echo base_url(); ?>index.php/home/top_bar_right');
    });
</script>
<script>
var input = document.getElementById("query");
input.addEventListener("keyup", function(event) {
  if (event.keyCode === 13) {
   event.preventDefault();
   document.getElementById("myBtn").click();
  }
});
</script>
<style>
@-webkit-keyframes blinker {
  from {opacity: 1.0;}
  to {opacity: 0.0;}
}
.blink{
	text-decoration: blink;
	-webkit-animation-name: blinker;
	-webkit-animation-duration: 0.6s;
	-webkit-animation-iteration-count:infinite;
	-webkit-animation-timing-function:ease-in-out;
	-webkit-animation-direction: alternate;
}
li.center.blink{margin-left:250px;margin-bottom:15px;width:100px;}
    .dropdown-menu .active a{
        color: #fff !important;
    }
    .dropdown-menu li a{
        cursor: pointer;
    }
    .header-search select {
        display: none !important;
    }
	.cat_select button{
		right:170px !important;
	}
	@media (max-width: 992px){
	 li.center.blink{margin-left:50px}
	}
	@media (max-width: 768px) {
		.cat_select button{
			right:80px !important;
		}
		li.center.blink{margin-left:0px;margin-right:0px;}
	}
</style>
<?php
if ($this->crud_model->get_type_name_by_id('general_settings','58','value') !== 'ok') {
?>
<style>
.header.header-logo-left .header-search .header-search-select .dropdown-toggle {
    right: 40px !important;
}
</style>
<?php
}
?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	
	 
	
  var BASE_URL = "<?php echo base_url(); ?>";

 $(document).ready(function() {
    $('.orderFrom').click(function(e){
        e.preventDefault();
        console.log('testing');
        openSuadModal(e);
    })
    $( "#query" ).autocomplete({

        source: function(request, response) {
			
            $.ajax({
            url: BASE_URL + "index.php/home/search_new",
            data: {
                    term : request.term
             },
            dataType: "json",
            success: function(data){
				
				
				   var resp = $.map(data,function(obj){
					   
						return obj.title;
				   }); 
               		response(resp);
				//$('#query').closest("form").submit();
				
            }
        });
    },
    minLength: 2
 });
});
(function($) {
         "use strict";
         
         
         
          
         
         /* ==============================================
         TABBED HOVER -->
         =============================================== */
          
          $('.nav-pills > li ').hover( function(){
            if($(this).hasClass('hoverblock'))
              return;
              else
			 
              $(this).find('a').tab('show');
          });
         
         
         
         /* ==============================================
         MENU HOVER -->
         =============================================== */
         $(".hovermenu .dropdown").hover(
         	function() { 
         		$(this).addClass('open') ;
         		$('.main_b li').removeClass('active') ;
         		$('.inner li').removeClass('active') ;
         		$('.main_b li:first-child').find('a').tab('show');
         		$('.inner li:first-child').find('a').tab('show');
         		
         		
         	},
         	function() { 
         		$(this).removeClass('open') ;
         		
         		
         	}
         );
         
         
         
         
         })(jQuery);

</script>  
<style>
.ui-widget-content {
	position: absolute !important;
	background: #fff;
	border: 0;
	box-shadow: 0px 4px 4px #ccc;
}
.ui-menu-item {
	padding: 5px 10px;
	border-bottom: 1px solid #f6f6f6;
}
.ui-state-active, .ui-widget-content .ui-state-active {
	border:0px;
	
}
#cart_form .btn-theme-dark {
	background-color: #da0a22;
	border-color: #da0a22;
	color: #ffffff;
	width: 150px;
}

</style>


