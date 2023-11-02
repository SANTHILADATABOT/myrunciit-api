<!-- BREADCRUMBS -->
<section class="page-section breadcrumbs">
    <div class="container">
        <div class="page-header">
            <h2 class="section-title section-title-lg">
                <span>
                    <?php echo translate('all_categories');?>
                </span>
            </h2>
        </div>
    </div>
</section>
<!-- /BREADCRUMBS -->

<!-- PAGE -->
<section class="page-section">
    <div class="container">
        <div class="row">
            <?php
            $categories=$this->db->get('category')->result_array();
            foreach($categories as $row){
				if($this->crud_model->if_publishable_category($row['category_id'])){
            ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="all-brands-list">
                    <div class="brands-list-heading" style="min-height:70px;">
                        <div class="heading-text">
                        
    <img class="col-xs-2" style="height:30px;margin-top:15px;" src="<?php echo base_url();?>uploads/category_image/<?php echo $row['banner'];?>" />

                            <a style="line-height:50px;" href="<?php echo base_url(); ?>index.php/home/all_vendor_cat/<?php echo $row['category_id']; ?>">
                                <?php echo $row['category_name'];?>
                                (<?php
                                    echo $this->crud_model->is_publishable_count('category',$row['category_id']);
                                ?>)
                            </a>
                        </div>
                    </div>
                    <div class="brands-list-body" style="background-image:linear-gradient(rgba(255, 255, 255, 0.87),rgba(241, 241, 241, 0.98));display:none;">
                   
                        <div class="brands-show">
                            <table>
                                <tr>
                                    <td class="brand-image">
                                        
										<img class="image_delay" src="" data-src=""> 
										<!--<img  class="image_delay" src="<?php echo img_loading(); ?>" data-src="<?php echo base_url(); ?>uploads/sub_category_image/default.jpg" />-->
										
                                    </td>
                                    <td class="brand-name">
                                        <a href="">
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
				}
            }
            ?>
        </div>
    </div>
</section>
<!-- /PAGE -->