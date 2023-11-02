<?php 
	$contact_address =  $this->db->get_where('general_settings',array('type' => 'contact_address'))->row()->value;
	$contact_phone =  $this->db->get_where('general_settings',array('type' => 'contact_phone'))->row()->value;
	$contact_email =  $this->db->get_where('general_settings',array('type' => 'contact_email'))->row()->value;
	$contact_website =  $this->db->get_where('general_settings',array('type' => 'contact_website'))->row()->value;
	$contact_about =  $this->db->get_where('general_settings',array('type' => 'contact_about'))->row()->value;
	$year =  $this->db->get_where('general_settings',array('type' => 'year'))->row()->value;
	$facebook =  $this->db->get_where('social_links',array('type' => 'facebook'))->row()->value;
	$instagram =  $this->db->get_where('social_links',array('type' => 'instagram'))->row()->value;
	$googleplus =  $this->db->get_where('social_links',array('type' => 'google-plus'))->row()->value;
	$twitter =  $this->db->get_where('social_links',array('type' => 'twitter'))->row()->value;
	$skype =  $this->db->get_where('social_links',array('type' => 'skype'))->row()->value;
	$youtube =  $this->db->get_where('social_links',array('type' => 'youtube'))->row()->value;
	$pinterest =  $this->db->get_where('social_links',array('type' => 'pinterest'))->row()->value;
	
	$footer_text =  $this->db->get_where('general_settings',array('type' => 'footer_text'))->row()->value;
	$footer_category =  json_decode($this->db->get_where('general_settings',array('type' => 'footer_category'))->row()->value);
?>
<footer class="footer1 clearfix">

	<div class="footer1-widgets">
	  <div class="subs_bg">	
        <div class="container">
            <ul class="adlogo"> 
                <li><span><img src="<?php echo base_url(); ?>/uploads/footer/icon-footer-box.png" alt="Free Delivery"></span><p> <strong>Free delivery </strong>above RM350</p></li>
                <li><span><img src="<?php echo base_url(); ?>/uploads/footer/icon-footer-cart_return.png" alt="Easy Returns"></span><p> <strong>Easy Returns and Refunds </strong>for your orders*</p></li>
                <li><span><img src="<?php echo base_url(); ?>/uploads/footer/icon-footer-customer_service.png" alt="Top-Notch Support"></span><p> <strong>Top-Notch Support </strong>to help with your orders.</p></li>
                <li><span><img src="<?php echo base_url(); ?>/uploads/footer/icon-footer-shield.png" alt="Secure Payment"></span><p> <strong>Secure Payment </strong>for a worry free checkout </p></li>
            </ul>
              </div> 

             </div> 
          </div>     
              
              
              <div class="sub_ffoot_bg clearfix dis">
                <div class="container">

                   <div class="row">
               <div class="widget">
              	<div class="col-md-4 clearfix" style="padding:0px;">
				        <div class="col-lg-5 col-md-3 col-xs-12 text-center">
                        	<div class="foo-bos">Address</div>
                        	<div class="media-list" style="padding-top:15px;">
                                <div class="media">
                                
                                <div class="media-body">
                                    <?php /*?><strong>                <i class="fa fa-home"></i>
                <?php echo translate('address');?>:</strong><?php */?>
                                    <?php echo $contact_address;?>
                                </div>
                              </div>
                          </div>
                            
                        </div>
                    <div class="col-lg-5">
                    <p>Sign up for our newsletter to get firsthand news on our sale or when our shelves are restocked.</p>
                    <?php
							echo form_open(base_url() . 'index.php/home/subscribe', array(
								'class' => '',
								'method' => 'post'
							));
						?>    
							<div class="">
                            	<div class="">
									<input type="text" class="form-control col-md-8" name="email" id="subscr" placeholder="<?php echo translate('email_address'); ?>">
                                	<button class="arrow_btn"><i class="fa fa-long-arrow-right"></i></button>
                                </div>
					   </form>
                      </div> 
                    </div>
                    
                  <div class="col-lg-5">
                    <ul class="footer-contact-sm">
                        <li class="socialmedia"><a target="_blank" href="<?php echo $instagram; ?>"><i class="fa fa-instagram"></i><span>@myrunciit</span></a></li>
                        <li class="socialmedia"><a target="_blank" href="<?php echo $facebook; ?>"><i class="fa fa-facebook"></i><span>fb.me/myrunciit</span></a></li>
                    </ul>
                    </div>
                  </div>  
                 </div>
                 
                 
                <div class="col-md-8">
                    
                    <div class="col-md-3 hidden-xs hidden-sm" style="margin-top:10px;">
                        <div class="widget widget-categories">
                            <h4 class="widget-title"><?php echo translate('categories');?></h4>
                            <ul>
                                <?php
                                    foreach($footer_category as $row){
                                        if($this->crud_model->if_publishable_category($row)){
                                ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>index.php/home/category/<?php echo $row; ?>">
                                            <?php
                                                echo $this->crud_model->get_type_name_by_id('category',$row,'category_name');
                                            ?>
                                        </a>
                                    </li>
                                <?php
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
 
                    <div class="col-md-3 col-sm-12 hidden-xs" style="margin-top:10px;">
                        <div class="widget widget-categories">
                            <h4 class="widget-title"><?php echo translate('useful_links');?></h4>
                            <ul>
                                <li>
                                    <a href="<?php echo base_url(); ?>index.php/home/"><?php echo translate('home');?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>index.php/home/category/0/0-0"><?php echo translate('all_products');?>
                                    </a>
                                </li>
                                 <li>
                                  
                                    <a href="<?php echo base_url(); ?>index.php/home/others_product/latest">
									<?php echo translate('latest_products');?>
								</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>index.php/home/others_product/featured"><?php echo translate('featured_products');?>
                                    </a>
                                  
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>index.php/home/contact/"><?php echo translate('contact');?>
                                    </a>
                                </li>
                                <?php
                                $this->db->where('status','ok');
                                $all_page = $this->db->get('page')->result_array();
                                foreach($all_page as $row){
                                ?>
                                <li>
                                    <a href="<?php echo base_url(); ?>index.php/home/page/<?php echo $row['parmalink']; ?>">
                                        <?php echo $row['page_name']; ?>
                                    </a>
                                </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-3 hidden-xs hidden-sm" style="margin-top:10px;">
                        <div class="widget widget-categories">
                            <h4 class="widget-title"><?php echo translate('useful_links');?></h4>
                            <ul>
                               
                                    
                    		<li> 
                    			<a href="<?php echo base_url(); ?>index.php/home/about_us">
                              		<?php echo translate('About us');?>
                    	    	</a>
                    		</li>
                            
                            <li> 
                    			<a href="<?php echo base_url(); ?>index.php/home/faq">
                              		<?php echo translate('faq');?>
                    	    	</a>
                    		</li>
                            
                           <li> 
                    		<a href="<?php echo base_url(); ?>index.php/home/legal/terms_conditions" class="link"><?php echo translate('terms_&_condition'); ?>
							</a> 
                    		</li> 
                            
                            
                            <li> 
                    		<a href="<?php echo base_url(); ?>index.php/home/legal/privacy_policy" class="link">
							<?php echo translate('privacy_policy'); ?>
						   </a>
							</a> 
                    		</li>        
                                    
                               
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-3 hidden-xs hidden-sm" style="margin-top:10px;">
                        <div class="widget contact">
                            <h4 class="widget-title"><?php echo translate('contact_us');?></h4>
                            <div class="media-list">
                                
                                <div class="media">
                                    <!--<i class="pull-left fa fa-phone"></i>-->
                                    <div class="media-body">
                                     
                                        <?php echo $contact_phone;?>
                                    </div>
                                </div>
                                <div class="media">
                                    <!--<i class="pull-left fa fa-globe"></i>-->
                                    <div class="media-body">
                                       
                                        <a href="https://<?php echo $contact_website;?>"><?php echo $contact_website;?></a>
                                    </div>
                                </div>
                                <div class="media">
                                    <!--<i class="pull-left fa fa-envelope-o"></i>-->
                                    <div class="media-body">
                                       
                                        <a href="mailto:<?php echo $contact_email;?>">
                                            <?php echo $contact_email;?>
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                 
                </div>
                </div>

      </div>  
      
      
      
     <div class="sub_mfoot_bg clearfix">
                <div class="container">

                   <div class="row">
               <div class="widget">
              	<div class="col-md-12 clearfix" style="padding:0px;">

                    <div class="col-sm-12">
                    <p>Sign up for our newsletter to get firsthand news on our sale or when our shelves are restocked.</p>
                    <?php
							echo form_open(base_url() . 'index.php/home/subscribe', array(
								'class' => '',
								'method' => 'post'
							));
						?>    
							<div class="">
                            	<div class="">
									<input type="text" class="form-control col-md-8" name="email" id="subscr" placeholder="<?php echo translate('email_address'); ?>">
                                	<button class="arrow_btn"><i class="fa fa-long-arrow-right"></i></button>
                                </div>
					   </form>
                      </div> 
                    </div>
                  </div>  
                 </div>
                 
                 
                <div class="col-md-10" style="margin-bottom:10px;">
                    
                    <div class="col-md-3" style="margin-top:10px;">
                        <div class="widget widget-categories">
                            <h4 class="collapsible"><?php echo translate('categories');?></h4>
                            <ul class="content">
                                <?php
                                    foreach($footer_category as $row){
                                        if($this->crud_model->if_publishable_category($row)){
                                ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>index.php/home/category/<?php echo $row; ?>">
                                            <?php
                                                echo $this->crud_model->get_type_name_by_id('category',$row,'category_name');
                                            ?>
                                        </a>
                                    </li>
                                <?php
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
 
                    <div class="col-md-3" style="margin-top:10px;">
                        <div class="widget widget-categories">
                            <h4 class="collapsible"><?php echo translate('useful_links');?></h4>
                            <ul class="content">
                                <li>
                                    <a href="<?php echo base_url(); ?>index.php/home/"><?php echo translate('home');?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>index.php/home/category/0/0-0"><?php echo translate('all_products');?>
                                    </a>
                                </li>
                                 <li>
                                  
                                    <a href="<?php echo base_url(); ?>index.php/home/others_product/latest">
									<?php echo translate('latest_products');?>
								</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>index.php/home/others_product/featured"><?php echo translate('featured_products');?>
                                    </a>
                                  
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>index.php/home/contact/"><?php echo translate('contact');?>
                                    </a>
                                </li>
                                <?php
                                $this->db->where('status','ok');
                                $all_page = $this->db->get('page')->result_array();
                                foreach($all_page as $row){
                                ?>
                                <li>
                                    <a href="<?php echo base_url(); ?>index.php/home/page/<?php echo $row['parmalink']; ?>">
                                        <?php echo $row['page_name']; ?>
                                    </a>
                                </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-3" style="margin-top:10px;">
                        <div class="widget widget-categories">
                            <h4 class="collapsible"><?php echo translate('useful_links');?></h4>
                            <ul class="content">
                               
                                    
                    		<li> 
                    			<a href="<?php echo base_url(); ?>index.php/home/about_us">
                              		<?php echo translate('About us');?>
                    	    	</a>
                    		</li>
                            
                            <li> 
                    			<a href="<?php echo base_url(); ?>index.php/home/faq">
                              		<?php echo translate('faq');?>
                    	    	</a>
                    		</li>
                            
                           <li> 
                    		<a href="<?php echo base_url(); ?>index.php/home/legal/terms_conditions" class="link"><?php echo translate('terms_&_condition'); ?>
							</a> 
                    		</li> 
                            
                            
                            <li> 
                    		<a href="<?php echo base_url(); ?>index.php/home/legal/privacy_policy" class="link">
							<?php echo translate('privacy_policy'); ?>
						   </a>
							</a> 
                    		</li>        
                                    
                               
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-3" style="margin-top:10px;">
                        <div class="widget contact" style="border-bottom: 1px solid;">
                            <h4 class="collapsible"><?php echo translate('contact_us');?></h4>
                            <div class="media-list content">
                                
                                <div class="media">
                                    <i class="pull-left fa fa-phone"></i>
                                    <div class="media-body">
                                     
                                        <?php echo $contact_phone;?>
                                    </div>
                                </div>
                                <div class="media">
                                    <i class="pull-left fa fa-globe"></i>
                                    <div class="media-body">
                                       
                                        <a href="https://<?php echo $contact_website;?>"><?php echo $contact_website;?></a>
                                    </div>
                                </div>
                                <div class="media">
                                    <i class="pull-left fa fa-envelope-o"></i>
                                    <div class="media-body">
                                       
                                        <a href="mailto:<?php echo $contact_email;?>">
                                            <?php echo $contact_email;?>
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                     <div class="col-xs-12 text-center" style="margin-top:0px;">
                    <ul class="footer-contact-sm">
                        <li class="socialmedia"><a href="#"><i class="fa fa-instagram"></i><span>@myrunciit</span></a></li>
                        <li class="socialmedia"><a href="#"><i class="fa fa-facebook"></i><span>fb.me/myrunciit</span></a></li>
                    </ul>
                    </div>
                <div class="col-xs-12 text-center" style="margin-top:0px;">
                	<div class="foo-bos">Address</div>
                	<div class="media-list" style="padding-top:15px;">
                        <div class="media">
                        
                        <div class="media-body">
                            <?php /*?><strong>                <i class="fa fa-home"></i>
        <?php echo translate('address');?>:</strong><?php */?>
                            <?php echo $contact_address;?>
                        </div>
                      </div>
                  </div>
                            
                </div>
                </div>
                </div>

      </div>  
    
 <!-- <div class="footer_new_bg clearfix dis">    
    <div class="container" style="padding:0px;">  
    <div class="widget">
    	<div class="col-lg-3 col-md-3 col-xs-12 text-left">
        <div class="foo-bos">Join us on</div>
        <ul class="social-icons">
								<li><a href="<?php echo $facebook;?>" class="facebook"><i class="fa fa-facebook"></i></a></li>
								<li><a href="<?php echo $twitter;?>" class="twitter"><i class="fa fa-twitter"></i></a></li>
								<li><a href="<?php echo $googleplus;?>" class="google"><i class="fa fa-google-plus"></i></a></li>
								<li><a href="<?php echo $pinterest;?>" class="pinterest"><i class="fa fa-pinterest"></i></a></li>
								<li><a href="<?php echo $youtube;?>" class="youtube"><i class="fa fa-youtube"></i></a></li>
								<li><a href="<?php echo $skype;?>" class="skype"><i class="fa fa-skype"></i></a></li>
							</ul>
        </div>
        <div class="col-lg-5 col-md-3 col-xs-12 text-center">
        	<div class="foo-bos">Address</div>
        	<div class="media-list" style="padding-top:15px;">
                <div class="media">
                
                <div class="media-body">
                    <?php /*?><strong>                <i class="fa fa-home"></i>
<?php echo translate('address');?>:</strong><?php */?>
                    <?php echo $contact_address;?>
                </div>
              </div>
          </div>
            
        </div>
        <div class="col-lg-4 col-md-3 col-xs-12 text-center dis">
        <div class="foo-bos">Payment Methods</div>
					<div class="payments" style="font-size: 30px;">
						<ul>
							<li><i class="fa fa-cc-paypal"></i></li>
							<li><i class="fa fa-cc-visa"></i></li>
							<li><i class="fa fa-cc-mastercard"></i></li>
							<li><i class="fa fa-cc-discover"></i></li>
						</ul>
					</div>
        </div>
    </div>  
    </div>
  </div>      -->
        <hr>
	<div class="footer1-meta">
		<div class="container">
            <div class="col-lg-4 col-sm-4">
            	<a href="<?php echo base_url(); ?>">
                  	<img class="img-responsive foot-logo" src="<?php echo $this->crud_model->logo('home_bottom_logo'); ?>" alt="">
				</a>
            </div>
			<div class="copyright text-center col-lg-4 col-sm-4">
    			<?php echo $year; ?> &copy; 
    			<?php echo translate('all_rights_reserved'); ?> @ 
    			<a href="<?php echo base_url(); ?>">
    				<?php echo $system_title; ?>
    			</a> 
		    </div>
		    <div class="col-lg-4 col-sm-4">
		        <div class="logoimg">
		        <img src="<?php echo base_url(); ?>/uploads/footer/logo-banks.png" alt="We accept payments via Visa, MasterCard, FPX, GrabPay, Touch n Go eWallet, MayBank QRPay">
		        </div>
		    </div>
		</div>
	</div>
</footer>
<style>
.link:hover{
	text-decoration:underline;
}
.collapsible {
  background-color: #fff;
  color: #616B74;
  cursor: pointer;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  margin-left: 15px;
}
.collapsible:after {
  content: '\f107';
  color: #616B74;
  font-size: 22px;
  float: right;
  margin-right: 70px;
  font-family: 'FontAwesome';
}

.collapsible.active:after {
  content: "\f106";
  color: #014282;
  font-size: 22px;
}
.content {
  padding: 0 18px;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
  background-color: #fff;
}
.sub_mfoot_bg .widget.widget-categories {
    border-bottom: 1px solid;
}
.content li {
    display: block !important;
}
h4.collapsible.active {
    background-color: #f2b894;
    box-shadow: 0 0 0 0.25rem rgb(229 113 41 / 25%);
    color: #014282;
}
</style>

<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  });
}
</script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/62a96f4ab0d10b6f3e77689a/1g5itlfgo';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->