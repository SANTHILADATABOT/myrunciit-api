<!-- PAGE -->
<link rel="stylesheet" href="<?php echo base_url(); ?>template/front/js/share/jquery.share.css">
<script src="<?php echo base_url(); ?>template/front/js/share/jquery.share.js"></script>

<?php  
	foreach($product_details as $row){
	$thumbs = $this->crud_model->file_view('product',$row['product_id'],'','','thumb','src','multi','all');
	$mains = $this->crud_model->file_view('product',$row['product_id'],'','','no','src','multi','all'); 
?>
<section class="page-section">
    <div class="row product-single">
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-2 others-img">
                    <?php
                        $i=1;
                        foreach ($thumbs as $id=>$row1) {
                    ?>
                    <div class="related-product" id="main<?php echo $i; ?>">
                        <img class="img-responsive img" data-src="<?php echo $thumbs[$id]; ?>" src="<?php echo $row1; ?>" alt=""/>
                    </div>
                    <?php
                        $i++;
                        }
                    ?>
                </div>
                <div class="col-md-10">
                    <img class="img-responsive main-img" id="set_image" src="" alt=""/>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <h3 class="product-title"><?php echo $row['title'];?></h3>
            <hr class="page-divider"/>
            <div class="product-price">
                <?php echo translate('price_:');?>
                <?php if($row['discount'] > 0){ ?> 
                    <ins>
                        <?php echo currency($this->crud_model->get_product_price($row['product_id'])); ?>
                        <unit><?php echo ' /'.$row['unit'];?></unit>
                    </ins> 
                    <del><?php echo currency($row['sale_price']); ?></del>
                    <span class="label label-success">
                    <?php 
                        echo translate('discount:_').$row['discount'];
                        if($row['discount_type']=='percent'){
                            echo '%';
                        }
                        else{
                            echo currency();
                        }
                    ?>
                    </span>
                <?php } else { ?>
                    <ins>
                        <?php echo currency($row['sale_price']); ?>
                        <unit><?php echo ' /'.$row['unit'];?></unit>
                    </ins> 
                <?php }?>
            </div>
            <?php
                include 'order_option.php';
            ?>
        </div>
    </div>
</section>
<?php
	}
?>

<!-- /PAGE -->
                
<script>
function load_days(value){
   $('#packdays').removeClass('hidden')
   if(value == "allday"){
    

       document.getElementById("mon").checked = true;
       document.getElementById("tue").checked = true;
       document.getElementById("wed").checked = true;
       document.getElementById("thu").checked = true;
       document.getElementById("fri").checked = true;
       document.getElementById("sat").checked = true;
       document.getElementById("sun").checked = true;
   }else if(value == "weekday"){
       
       
       document.getElementById("mon").checked = true;
       document.getElementById("tue").checked = true;
       document.getElementById("wed").checked = true;
       document.getElementById("thu").checked = true;
       document.getElementById("fri").checked = true;
       document.getElementById("sat").checked = false;
       document.getElementById("sun").checked = false;
   }else if(value == "weekend"){
       
      
       
       document.getElementById("mon").checked = false;
       document.getElementById("tue").checked = false;
       document.getElementById("wed").checked = false;
       document.getElementById("thu").checked = false;
       document.getElementById("fri").checked = false;
       document.getElementById("sat").checked = true;
       document.getElementById("sun").checked = true;
   }
}

	$(".img").click(function(){
		var src = $(this).data('src');
		$("#set_image").attr("src", src);
		$(".related-product").removeClass("selected");
		$(this).closest(".related-product").addClass("selected");
	});
	$(document).ready(function() {
		$("#main1").addClass("selected");
		var src=$("#main1").find(".img").data('src');
		$("#set_image").attr("src", src);
	});
	
	$(function(){
		if($('#main1').length > 0){
			$('#main1').click();
		}
	});
</script>
<script>
	$('body').on('click', '.rev_show', function(){
		$('.ratings_show').hide('fast');
		$('.inp_rev').show('slow');
	});
</script>
<style>
	.rate_it{
		display:none;	
	}
	.product-single .fix-length{
		height:225px; 
		overflow:auto;
	}
</style>