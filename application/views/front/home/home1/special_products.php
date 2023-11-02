<!-- PAGE -->
<!--<section class="home-ban">
	<div class="container">
    	<div class="col-md-6"><a href="javascript:void(0);"><img src="<?php echo base_url(); ?>/template/img/health.jpg" class="bot-ban" alt=""></a></div>
        <div class="col-md-6"><a href="javascript:void(0);"><img src="<?php echo base_url(); ?>/template/img/electronics.jpg" class="bot-ban" alt=""></a></div>
    </div>
</section>-->
<?php /* ?>
<section class="page-section featured-products">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="section-title section-title-lg">
                    <?php echo translate('categories');?>
                </h2>
                <div class="thumbnail box-style-2 no-padding">
                    <div class="media row" id="categories_list_div"></div>
                </div>
            </div>
            <div class="col-md-12">
                <h2 class="section-title section-title-lg">
                    <?php echo translate('sub_categories');?>
                </h2>
                <div class="thumbnail box-style-2 no-padding">
                    <div class="media row" id="subcategories_list_div"></div>
                </div>
            </div>
        </div>
        <script>
        <?php
        $catg_list1=[];
        $Categories_tb = $this->db->get('category')->result_array();
        foreach($Categories_tb as $catg1){
            $catg_list1[]=[
                "imgsrc"=>base_url()."uploads/category_image/".((file_exists('uploads/category_image/'.$catg1['banner']) && ($catg1['banner']!=""))?$catg1['banner']:"default.jpg")."?r1=".random_int(1000, 9999),
                "link"=>base_url()."index.php/home/category/".$catg1['category_id'],
                "name"=>$catg1['category_name']
            ];
        }
        $subcatg_list1=[];
        $Sub_Categories_tb = $this->db->get('sub_category')->result_array();
        foreach($Sub_Categories_tb as $subcatg1){
            $subcatg_list1[]=[
                "imgsrc"=>base_url()."uploads/sub_category_image/".((file_exists('uploads/sub_category_image/'.$subcatg1['banner']) && ($subcatg1['banner']!=""))?$subcatg1['banner']:"default.jpg")."?r1=".random_int(1000, 9999),
                "link"=>base_url()."index.php/home/category/".$subcatg1['category']."/".$subcatg1["sub_category_id"],
                "name"=>$subcatg1['sub_category_name']
            ];
        } ?>
        function category_list_func(type){
            $("#categories_list_div").html("");
            const catg_list=<?php echo json_encode($catg_list1); ?>;
            let len=(type=="hide")?11:catg_list.length;
            var categories_list_div="";
            for(let i1=0;i1<len;i1++){
                categories_list_div+="<div class='col-md-2' style='text-align:center;'><img style='width:64px;height:64px;' src='"+catg_list[i1]['imgsrc']+"' /><br><label><a href='"+catg_list[i1]['link']+"'>"+catg_list[i1]['name']+"</a></label></div>";
            }
            if(type=="hide"){
                categories_list_div+="<div class='col-md-2' style='text-align:center;'><i style='width:64px;height:64px;' class='fas fa-expand-alt'></i><br><label><a href=\"javascript:category_list_func('show')\">Show All</a></label></div>";
            }
            $("#categories_list_div").html(categories_list_div);
        }
        function subcategory_list_func(type){
            const subcatg_list=<?php echo json_encode($subcatg_list1); ?>;
            let len=(type=="hide")?11:subcatg_list.length;
            var subcategories_list_div="";
            for(let i1=0;i1<len;i1++){
                subcategories_list_div+="<div class='col-md-2' style='text-align:center;'><img style='width:64px;height:64px;' src='"+subcatg_list[i1]['imgsrc']+"' /><br><label><a href='"+subcatg_list[i1]['link']+"'>"+subcatg_list[i1]['name']+"</a></label></div>";
            }
            if(type=="hide"){
                subcategories_list_div+="<div class='col-md-2' style='text-align:center;'><img style='width:64px;height:64px;' src='' /><br><label><a href=\"javascript:subcategory_list_func('show')\">Show All</a></label></div>";
            }
            $("#subcategories_list_div").html(subcategories_list_div);
        }
        $(document).ready(function(){
            category_list_func("hide");
            subcategory_list_func("hide");
        });
        </script>
    </div>
</section>
<?php */ ?>

<?php
$catg_list0=[];
$vendorid = $this->session->userdata('vendorid');
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
//echo $this->db->last_query();
$cat_values = array();
foreach($val as $result)
{
 $get_category =$result['category'];
 if (!in_array($get_category, $cat_values)) {
    {
        $this->db->where('category_id',$get_category);
        $Categories_tb = $this->db->get('category')->result_array();
foreach($Categories_tb as $catg1){
    $catg_list0[]=[
        "imgsrc"=>base_url()."uploads/category_image/".((file_exists('uploads/category_image/'.$catg1['banner']) && ($catg1['banner']!=""))?$catg1['banner']:"default.jpg")."?r1=".random_int(1000, 9999),
        "link"=>base_url()."index.php/home/category/".$catg1['category_id'],
        "name"=>$catg1['category_name']
    ];
}
$this->db->where('category',$catg1['category_id']);
$Sub_Categories_tb = $this->db->get('sub_category')->result_array();
foreach($Sub_Categories_tb as $subcatg1){
    $catg_list0[]=[
        "imgsrc"=>base_url()."uploads/sub_category_image/".((file_exists('uploads/sub_category_image/'.$subcatg1['banner']) && ($subcatg1['banner']!=""))?$subcatg1['banner']:"default.jpg")."?r1=".random_int(1000, 9999),
        "link"=>base_url()."index.php/home/category/".$subcatg1['category']."/".$subcatg1["sub_category_id"],
        "name"=>$subcatg1['sub_category_name']." (".$this->db->get_where('category', array('category_id' => $subcatg1["category"]))->row()->category_name.")"
    ];
}
$cat_values[] = $get_category;
}}}
$catg_list0_len1=count($catg_list0);
$catg_list0_len2=intval($catg_list0_len1/2);
$catg_list1=[];
for($i0=0;$i0<$catg_list0_len2;$i0++){$catg_list1[]=$catg_list0[$i0];}
$catg_list2=[];
for($i0=$catg_list0_len2;$i0<$catg_list0_len1;$i0++){$catg_list2[]=$catg_list0[$i0];}
$catg_list0=[];
?>
<section class="page-section featured-products">
    <div class="container">
        <h2 class="section-title section-title-lg">
            <?php echo translate('Categories');?>
        </h2>
    </div>
    <div class="container">
        <div class="featured-products-carousel col-md-12">
            <div class="owl-carousel" id="featured-products-carousel-9">
            <?php
            for($i0=0;($i0<count($catg_list1))||($i0<count($catg_list2));$i0++)
            { ?>
                <div class="thumbnail box-style-2 no-padding" style="border: 0px;background:white;padding: 0px;">
                <?php if($i0<count($catg_list1)){ ?>
                <div style="border: 1px solid #e3cfca;border-radius: 0;background: linear-gradient(180deg,#fff,#fdf4f2);transition: all 0.4s ease-in-out;padding: 5px;border-radius: 5px;margin-bottom:3px;">
                    <div class="media">
                        <div class="media-link image_delay">
                            <center><img src="<?php echo $catg_list1[$i0]["imgsrc"]; ?>" style="" /></center>
                        </div>
                    </div>
                    <div class="caption text-center">
                        <h4 class="caption-title1"><a href="<?php echo $catg_list1[$i0]["link"]; ?>"><?php echo $catg_list1[$i0]["name"]; ?></a>&nbsp;</h4>
                    </div>
                </div>
                <?php } ?>
                <?php if($i0<count($catg_list2)){ ?>
                <div style="border: 1px solid #e3cfca;border-radius: 0;background: linear-gradient(180deg,#fff,#fdf4f2);transition: all 0.4s ease-in-out;padding: 5px;border-radius: 5px;">
                    <div class="media">
                        <div class="media-link image_delay">
                            <center><img src="<?php echo $catg_list2[$i0]["imgsrc"]; ?>" style="" /></center>
                        </div>
                    </div>
                    <div class="caption text-center">
                        <h4 class="caption-title1"><a href="<?php echo $catg_list2[$i0]["link"]; ?>"><?php echo $catg_list2[$i0]["name"]; ?></a>&nbsp;</h4>
                    </div>
                </div>
                <?php } ?>
                </div>
            <?php } ?>
            </div>
        </div>
        <div class="col-md-12">
            <center><h4 class="caption-title1 btn-icon-left" style="text-align:center;margin:30px 0px  30px 0px"><a href="<?php echo base_url()."index.php/home/category/0/0-0"; ?>">Show All</a></h4></center>
        </div>
    </div>
</section>

<?php
$catg_list0=[];
$vendorid = $this->session->userdata('vendorid');
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
//echo $this->db->last_query();
$cat_values1 = array();
foreach($val as $result)
{
 $get_category =$result['category'];
 if (!in_array($get_category, $cat_values1)) {
    {
        $this->db->where('category_id',$get_category);
        $Categories_tb = $this->db->get('category')->result_array();
foreach($Categories_tb as $catg1){
    if($catg1[banner_status]=="1" && $catg1[banner_status]!=""){
    $catg_list0[]=[
        "imgsrc"=>base_url()."uploads/category_image/".((file_exists('uploads/category_image/'.$catg1['category_banner']) && ($catg1['category_banner']!=""))?$catg1['category_banner']:"default.jpg")."?r1=".random_int(1000, 9999),
        "link"=>base_url()."index.php/home/category/".$catg1['category_id'],
        "name"=>$catg1['category_name']
    ];
}
}
$cat_values1[] = $get_category;
}}}

$catg_list0_len1=count($catg_list0);
$catg_list1=[];
for($i0=0;$i0<$catg_list0_len1;$i0++){$catg_list1[]=$catg_list0[$i0];}
$catg_list0=[];
?>
<section class="page-section featured-products">
    <div class="container">
        <h2 class="section-title section-title-lg">

            <?php echo translate('category_banners');?>
        </h2>
    </div>
    <div class="container">
        <div class="featured-products-carousel col-md-12">
            <div class="owl-carousel" id="featured-products-carousel-8">
            <?php
            for($i0=0;($i0<count($catg_list1));$i0++)
            { ?>
                <div class="thumbnail box-style-2 no-padding">
                <?php if($i0<count($catg_list1)){ ?>
                    <div class="media">
                        <div class="media-link image_delay">
                            <center><img src="<?php echo $catg_list1[$i0]["imgsrc"]; ?>" style="" /></center>
                        </div>
                    </div>
                    <div class="caption text-center">
                        <h4 class="caption-title1"><a href="<?php echo $catg_list1[$i0]["link"]; ?>"><?php echo $catg_list1[$i0]["name"]; ?></a>&nbsp;</h4>
                    </div>
                    <hr>
                <?php } ?>
                </div>
            <?php } ?>
            </div>
        </div>
    </div>
</section>
<section class="page-section featured-products">
    <div class="container">
        <h2 class="section-title section-title-lg">

            <?php echo translate('latest_products');?>
        </h2>
        <div class="featured-products-carousel col-md-12">
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
        <div class="featured-products-carousel col-md-12">
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
        <div class="featured-products-carousel col-md-12">
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
