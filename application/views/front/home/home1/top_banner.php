<!-- PAGE -->
<?php /*
<section class="page-section featured-products">
    <div class="container">
        <h2 class="section-title bg_deal section-title-lg">
            <span>
            	<span class="thin"> <?php echo translate('today_deals');?></span>
            </span>
            
            
            <span class="pull-right rht_section">
            	<a class="fo-oo" href="<?php echo base_url(); ?>/index.php/home/others_product/todays_deal"> <?php echo translate('see all');?>
                	
                </a>
            </span>
        </h2>
        <div class="featured-products-carousel">
            <div class="owl-carousel" id="featured-products-carousel-6">
                <?php
					$box_style =  $this->db->get_where('ui_settings',array('ui_settings_id' => 29))->row()->value;
					//$limit =  $this->db->get_where('ui_settings',array('ui_settings_id' => 20))->row()->value;
                    $featured=$this->crud_model->product_list_set('today_deals',20);
                    foreach($featured as $row){
                		echo $this->html_model->product_box($row, 'grid', $box_style);
					}
                ?>
            </div>
        </div>
    </div>
</section> */?>


<?php
	$this->db->where("place", "after_slider");
	$this->db->where("status", "ok");
	$banners=$this->db->get('banner')->result_array();

	$count=count($banners);
	if($count==1){
		$md=12;
		$sm=12;
		$xs=12;
	}elseif($count==2){
		$md=6;
		$sm=6;
		$xs=6;
	}elseif($count==3){
		$md=4;
		$sm=4;
		$xs=12;
	}
	elseif($count==4){
		$md=3;
		$sm=6;
		$xs=6;
	}
	
	// if($count!==0){
?>
<!--<section class="page-section">
    <div class="container">
     <p class="browse-shop-title d-md-none">Browse by shops</p>
        <div class="browse-shop-logos">
              <ul>
                  <li class="d-none d-md-inline-block"><img src="<?php echo base_url(); ?>/uploads/slider_image/text-browseshop.png" alt="Browse Our Shops"></li>
                  <li>
                      <ul class="browse-shop-images">
                      <?php
                    //   $vendorid = $this->session->userdata('vendorid');
                    //      if($vendorid=="")
                    //      {
                    //         $vendorid ="2";
                    //      }  
                    //          $this->db->where('vendor_id',$vendorid);
					// 		  $this->db->order_by("vendor_id", "desc");
                    // $vendors=$this->db->get('vendor')->result_array();
                    // foreach($vendors as $row){ 
					// if(!file_exists(base_url().'uploads/vendor_logo_image/logo_'.$row['vendor_id'].'.png')){
					?>
                          <li class="col-4">
                          

                              <a href="#shop-tpl">
                                                            <img src="<?php echo base_url(); ?>/uploads/vendor_logo_image/logo_<?php echo $row['vendor_id']; ?>.png" alt="Browse products from TPL Fresh Mart" style="width:80%">-->
                                  <!--<p class="d-none">Browse products from TPL Fresh Mart</p>-->
                           <!--   </a>
                          </li>
                          <?php //} } ?>
                            <?php /*?><li class="col-4">
                              <a href="#shop-alshah"><img src="<?php echo base_url(); ?>/uploads/slider_image/logo-alshah.png" alt="Browse products from AlShah Fresh &amp;amp; Frozen" style="width:80%">
                              
                              </a>
                            </li>
                            <li class="col-4">
                              <a href="#shop-harvestmart"><img src="<?php echo base_url(); ?>/uploads/slider_image/logo-harvestmart.png" alt="Browse products from Harvest Mart" style="width:80%">
                              
                              </a>
                            </li><?php */?>
                      </ul>
                  </li>
              </ul>
        </div>
    
</section>-->
<?php
	// }
?>
<section class="page-section featured-products">
    <div class="container">
        <h2 class="section-title bg_deal section-title-lg">
            <span><?php echo translate('today_deals');?></span>
            
            
            <span class="pull-right rht_section">
            	<a class="fo-oo" style="color:#044484;" href="<?php echo base_url(); ?>index.php/home/others_product/todays_deal"> <?php echo translate('see all');?>
                	
                </a>
            </span>
        </h2>
        <div class="featured-products-carousel">
            <div class="owl-carousel" id="featured-products-carousel-6">
                <?php
					$box_style =  $this->db->get_where('ui_settings',array('ui_settings_id' => 29))->row()->value;
					//$limit =  $this->db->get_where('ui_settings',array('ui_settings_id' => 20))->row()->value;
                    $featured=$this->crud_model->product_list_set('deal',20);
                  //  echo "<pre>"; print_r($featured);
                    foreach($featured as $row){
                        
                		echo $this->html_model->product_box($row, 'grid', $box_style);
					}
                ?>
            </div>
        </div>
    </div>
    <?php $numRows = count($featured); ?>
    <input type="hidden" id="num" class="form-control" value="<?php echo $numRows;?>" />
</section>
<style>
    .browse-shop-logos {
    background-color: #F5F8FA;
    border-radius: 10px;
    border: 1px solid #c4c6c8;
}
.browse-shop-logos ul {
    padding: 10px;
    margin-bottom: 0;
}
.browse-shop-logos ul li.d-md-inline-block {
    width: 25%;
}
.browse-shop-images li {
    width: 28%;
}
.browse-shop-logos li {
    display: inline-block;
    margin: 0 5px;
    vertical-align: middle;
}
.browse-shop-logos ul li.d-md-inline-block + li {
    margin-right: auto;
    margin-left: 0;
    width: 72%;
}
.browse-shop-logos ul {
    padding: 10px;
}


</style>
<!-- /PAGE -->