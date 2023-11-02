<!-- PAGE -->
<!--<section class="home-ban">
	<div class="container">
    	<div class="col-md-6"><a href="javascript:void(0);"><img src="<?php echo base_url(); ?>/template/img/health.jpg" class="bot-ban" alt=""></a></div>
        <div class="col-md-6"><a href="javascript:void(0);"><img src="<?php echo base_url(); ?>/template/img/electronics.jpg" class="bot-ban" alt=""></a></div>
    </div>
</section>-->

<section class="page-section featured-products">
    <div class="container">
        <h2 class="section-title section-title-lg">
            <?php echo translate('latest_products');?>
        </h2>
        <div class="featured-products-carousel">
            <div class="owl-carousel" id="featured-products-carousel-3">
                <?php
					$box_style =  $this->db->get_where('ui_settings',array('ui_settings_id' => 29))->row()->value;
					//$limit =  $this->db->get_where('ui_settings',array('ui_settings_id' => 20))->row()->value;
                    $featured=$this->crud_model->product_list_set('latest',20);
                    foreach($featured as $row){
                		echo $this->html_model->product_box($row, 'grid', $box_style);
					}
                ?>
            </div>
        </div>
    </div>
</section>


<section class="page-section featured-products">
    <div class="container">
        <h2 class="section-title section-title-lg">
            <?php echo translate('recently_viewed');?>
        </h2>
        <div class="featured-products-carousel">
            <div class="owl-carousel" id="featured-products-carousel-4">
                <?php
					$box_style =  $this->db->get_where('ui_settings',array('ui_settings_id' => 29))->row()->value;
					//$limit =  $this->db->get_where('ui_settings',array('ui_settings_id' => 20))->row()->value;
                    $featured=$this->crud_model->product_list_set('recently_viewed',20);
                    foreach($featured as $row){
                		echo $this->html_model->product_box($row, 'grid', $box_style);
					}
                ?>
            </div>
        </div>
    </div>
</section>



<section class="page-section featured-products">
    <div class="container">
        <h2 class="section-title section-title-lg">
            <?php echo translate('most_viewed');?>
        </h2>
        <div class="featured-products-carousel">
            <div class="owl-carousel" id="featured-products-carousel-5">
                <?php
					$box_style =  $this->db->get_where('ui_settings',array('ui_settings_id' => 29))->row()->value;
					//$limit =  $this->db->get_where('ui_settings',array('ui_settings_id' => 20))->row()->value;
                    $featured=$this->crud_model->product_list_set('most_viewed',20);
                    foreach($featured as $row){
                		echo $this->html_model->product_box($row, 'grid', $box_style);
					}
                ?>
            </div>
        </div>
    </div>
</section>
<section class="home-ban">
    <div class="container"> 
        <div class="section-header"> 
            <h2>What's Up With Us?</h2>
                <div class="blog-card-container"> 
                <?php
                    $limit =  "4";
                    $this->db->limit($limit);
                    $this->db->order_by("blog_id", "asc");
                    $blogs=$this->db->get('blog')->result_array();
                    foreach($blogs as $row){
                ?>
                    <a class="blog-card-wrap" href="<?php echo $this->crud_model->blog_link($row['blog_id']); ?>">
                    <?php
                        if(!file_exists('uploads/blog_image/blog_'.$row['blog_id'].'.jpg')){
                        ?>
                        <picture class="blog-card-img"><source media="(min-width: 768px)" srcset="<?php echo base_url(); ?>/uploads/blog_image/default.png"><img src="<?php echo base_url(); ?>/uploads/blog_image/default.png" alt="Recipe: Cream of Chicken"></picture>
                        <?php } else { ?>
                        <picture class="blog-card-img"><source media="(min-width: 768px)" srcset="<?php echo base_url(); ?>/uploads/blog_image/blog_<?php echo $row['blog_id']; ?>.jpg"><img src="<?php echo base_url(); ?>/uploads/blog_image/blog_<?php echo $row['blog_id']; ?>.jpg" alt="<?php echo $row['title']; ?>"></picture>
                        <?php } ?>
                        <div class="blog-overlay"></div>
                        <div class="blog-card-info"> 
                            <p class="blog-card-cat"> <i class="fa fa-bookmark"> </i><?php echo $row['title']; ?></p>
                            <?php echo $row['summery']; ?><span class="clearfix">Read More</span>
                        </div>
                    </a>
                    <?php } ?>
                   <?php /*?> <a class="blog-card-wrap" href="/">
                        <picture class="blog-card-img"><source media="(min-width: 768px)" srcset="<?php echo base_url(); ?>/template/img/img02.png"><img src="<?php echo base_url(); ?>/template/img/img02.png" alt="Love Is In The Air: Home Dinners for Valentineâ€™s Day"></picture>
                        <div class="blog-overlay"></div>
                        <div class="blog-card-info"> 
                            <p class="blog-card-cat"> <i class="fa fa-bookmark"> </i>Blog</p>
                            <p class="blog-card-title">Love Is In The Air: Home Dinners for Valentineâ€™s Day</p><span>Read More</span>
                        </div>
                    </a>
                    <a class="blog-card-wrap" href="/">
                        <picture class="blog-card-img"><source media="(min-width: 768px)" srcset="<?php echo base_url(); ?>/template/img/img03.png"><img src="<?php echo base_url(); ?>/template/img/img03.png" alt="14 Cooking Hacks That Can Make Your Life Easier"></picture>
                        <div class="blog-overlay"></div>
                        <div class="blog-card-info"> 
                            <p class="blog-card-cat"> <i class="fa fa-bookmark"> </i>Blog</p>
                            <p class="blog-card-title">14 Cooking Hacks That Can Make Your Life Easier</p><span>Read More</span>
                        </div>
                    </a>
                    <a class="blog-card-wrap" href="/">
                        <picture class="blog-card-img"><source media="(min-width: 768px)" srcset="<?php echo base_url(); ?>/template/img/img04.png"><img src="<?php echo base_url(); ?>/template/img/img04.png" alt="10 High-Protein Vegetables"></picture>
                        <div class="blog-overlay"></div>
                        <div class="blog-card-info"> 
                            <p class="blog-card-cat"> <i class="fa fa-bookmark"> </i>Blog</p>
                            <p class="blog-card-title">10 High-Protein Vegetables</p><span>Read More</span>
                        </div>
                    </a><?php */?>
                </div>
        </div>
    </div>
</section>



<!-- /PAGE -->
<style>

.section-header {
    padding: 0;
    margin: 0;
}
.section-header h2, .section-header .h2 {
    text-transform: uppercase;
    font-weight: 600;
}
.blog-card-wrap {
    display: block;
    background-color: #FFF;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
    text-decoration: none;
    color: #000F1D;
    height: auto;
    margin-bottom: 10px;
    overflow: hidden;
    position: relative;
}
.blog-card-wrap .blog-card-img, .blog-card-wrap .blog-card-info {
    display: inline-block;
    vertical-align: top;
}
.blog-card-wrap .blog-card-img {
    width: 110px;
}
.blog-card-wrap .blog-card-img img, .blog-card-wrap .blog-card-img source {
    border-radius: 20px 0 0 20px;
}
img {
    max-width: 100%;
}
.blog-card-wrap .blog-overlay {
    display: none;
}
.blog-card-wrap .blog-card-info {
    padding: 10px 15px;
    background: #fff;
    width: calc(100% - 113px);
}
.blog-card-wrap .blog-card-img, .blog-card-wrap .blog-card-info {
    display: inline-block;
    vertical-align: top;
}
.blog-card-wrap .blog-card-cat, .blog-card-wrap .blog-card-info span {
    font-size: 0.75em;
}
.blog-card-wrap .blog-card-cat {
    color: #005e1a;
    margin-bottom: 0px;
}
p {
    margin-top: 0;
    margin-bottom: 1rem;
}
.blog-card-wrap .blog-card-cat i {
    color: #91928b;
    font-size: 90%;
    margin-right: 2px;
}
.blog-card-wrap .blog-card-title {
    line-height: 1.15em;
    color: #000F1D;
}
.blog-card-wrap .blog-card-cat, .blog-card-wrap .blog-card-info span {
    font-size: 12px;
}
.blog-card-wrap .blog-card-info span {
    text-decoration: underline;
    color: #009D2B;
    /*position: absolute;*/
    bottom: 5px;
}
@media (min-width: 768px){
.blog-card-wrap {
    width: calc(24% - 16px);
    display: inline-block;
    vertical-align: top;
    margin: 0 10px;
    height: auto;
}
.blog-card-wrap:first-child {
    margin-left: 0;
}
.blog-card-wrap .blog-card-img, .blog-card-wrap .blog-card-info {
    display: block;
}
.blog-card-wrap .blog-card-img {
    width: auto;
}
.blog-card-wrap .blog-overlay {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: white;
    background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(1, 66, 130, 0.2) 80%);
}
.blog-card-wrap .blog-card-info {
    position: absolute;
    z-index: 1;
    bottom: 10px;
    width: calc(100% - 20px);
    border-radius: 10px;
    left: 10px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
}
.blog-card-wrap .blog-card-img, .blog-card-wrap .blog-card-info {
    display: block;
}
}
  
</style>
<script>
$(document).ready(function(){
    setTimeout(function(){
        set_special_product_box();
    },500);
});

function set_special_product_box(){
    var max_height = 0;
    $('.product-box-sm').each(function(){
        var current_height= parseInt($(this).css('height'));
        if(current_height >= max_height){
            max_height = current_height;
        }
    });
    $('.product-box-sm').css('height',max_height);
    
    var max_title=0;
    $('.special-products .inro-section').each(function(){
        var current_height= parseInt($(this).css('height'));
        if(current_height >= max_title){
            max_title = current_height;
        }
    });
    $('.special-products .inro-section').css('height',max_title);

    $('[data-toggle="tooltip"]').tooltip({placement:"auto"});
}
</script>