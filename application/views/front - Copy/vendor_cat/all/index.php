<!-- BREADCRUMBS -->
<section class="page-section breadcrumbs">
    <div class="container">
        <div class="page-header">
            <h2 class="section-title section-title-lg">
                <span>
                    <?php echo translate('vendors_category');?>
                </span>
            </h2>
        </div>
    </div>
</section>
<!-- /BREADCRUMBS -->

<!-- PAGE -->
<section class="page-section all-vendors">
    <div class="container">
        <div class="row">
        	<?php 
		//	echo "A"; 
			//	print_r($vendor_system_cat); exit;
				foreach($vendor_system_all as $rows){
					$data_v=json_decode($rows['added_by'],1);
					//print_r($data_v);
					if($data_v['type']='vendor') {
						$vid[]=$data_v['id'];
					}
				}
				//	$all_vendors_store = $this->db->get_where('vendor',array('vendor_id'=>$data_v['id'],'status'=>'approved'))->result_array();

 $cat_i=array_unique($vid);
// print_r($cat_i);
//for($i=0;$i<count($cat_i);$i++){
	
	foreach($cat_i as $rs)
	//print_r($rs); exit;
{
	
	
	
	
		$all_vendors_store = $this->db->get_where('vendor',array('vendor_id'=>$rs,'status'=>'approved'))->result_array();
		//echo $this->db->last_query();
		$row=$all_vendors_store[0];
		//print_r($all_vendors_store); exit;
			?>
            <div class="col-md-3 col-sm-6 col-xs-12">
            	<div class="vendor-details">
                	<div class="vendor-banner">
                    	<?php if(file_exists('uploads/vendor_banner_image/banner_'.$row['vendor_id'].'.jpg')){?>
                    		<img src="<?php echo base_url();?>uploads/vendor_banner_image/banner_<?php echo $row['vendor_id'];?>.jpg"/>
                        <?php }else{?>
                        	<img src="<?php echo base_url();?>uploads/vendor_banner_image/default.jpg"/>	
                        <?php }?>
                    </div>
                   
                    <div class="vendor-products" >
                    	<h4><?php echo translate('sold_category_of_vendor');?>:</h4>
                        <div class="product-category">
                        <?php
                        //	$vendor_categories = $this->crud_model->vendor_categories($row['vendor_id']);
							//foreach($vendor_categories as $row1){
								foreach($vendor_system_cat as $row12){
								$all_vendors_store_1 = $this->db->get_where('category',array('category_id'=>$row12['category_id']))->result_array();
								//echo $this->db->last_query();
								}
								
						?>
                        	<div class="category-name-box">
                            	<a href="<?php echo base_url(); ?>index.php/home/vendor_category/<?php echo $row['vendor_id'].'/'.$row12['category_id'];?>">
                            		<?php echo 
									$row12['category_name'];
									
									// $this->crud_model->get_type_name_by_id('category',$row1,'category_name'); ?>
                                </a>
                            </div>
                        <?php
							//}
						?>
                        </div>
                         <div class="vendor-profile">
                    	<h3>
                        	<a href="<?php echo $this->crud_model->vendor_link($row['vendor_id']); ?>">
							<?php echo $row['display_name'];?>
                            </a>
                        </h3>
                        <h5><?php echo $row['address1'];?></h5>
                        <h5>
                        	<strong><?php echo translate('email'); ?>: </strong><?php echo $row['email'];?>
                            <?php
								if($row['phone'] !== NULL){
							?>
                            <strong><?php echo translate('phone'); ?>: </strong><?php echo $row['phone'];?>
                            <?php
								}
							?>
                        </h5>
                          <?php echo translate('vendor_rating'); ?>
                                         <div class="rating ratings_show pull-right" data-original-title="<?php echo $rating = $this->crud_model->vendor_rating($rs); ?>"	
                                            data-toggle="tooltip" data-placement="left">
                                            <?php
                                                $r = $rating;
                                                $i = 6;
                                                while($i>1){
                                                    $i--;
                                            ?>
                                                <span class="star <?php if($i<=$rating){ echo 'active'; } $r++; ?>"></span>
                                            <?php
                                                }
                                            ?>
                                       
                      
                                  
                    </div>
                        
                    </div>
                    <div class="vendor-btn">
                        	<a href="<?php echo $this->crud_model->vendor_link($row['vendor_id']); ?>" class="btn btn-custom btn-block btn-theme">
                            	<?php echo translate('visit_my_shop');?>
                            </a>
                        </div>
                    <div class="vendor-photo">
                	<?php if(file_exists('uploads/vendor_logo_image/logo_'.$row['vendor_id'].'.png')){?>
                    <img src="<?php echo base_url();?>uploads/vendor_logo_image/logo_<?php echo $row['vendor_id'];?>.png" />
                    <?php }else{?>
                    	<img src="<?php echo base_url();?>uploads/vendor_logo_image/default.jpg"/>
                    <?php }?>
                </div>
                </div>
                
                </div>
            </div>
        	<?php 
				//}
}
?>
        </div>
    </div>
    
    </div>
</section>
<!-- /PAGE -->