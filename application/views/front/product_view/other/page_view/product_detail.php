<!--specialagentV1-->
<?php
	$thumbs = $this->crud_model->file_view('product',$row['product_id'],'','','thumb','src','multi','all');
	$mains = $this->crud_model->file_view('product',$row['product_id'],'','','no','src','multi','all'); 
	$video = $this->crud_model->file_view1('product',$row['product_id']).'.mp4';
	
	
?>
<section class="page-section light">
    <div class="container">
        <div class="row product-single">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-md-2 col-sm-2 col-xs-2 others-img">
                    	<?php
							$i=1;
							foreach ($thumbs as $id=>$row1) 
							{
						?>
                        <div class="related-product " id="main<?php echo $i; ?>">
                          <img class="img-responsive img" data-src="<?php echo $mains[$id]; ?>" src="<?php echo $row1; ?>" alt=""/>
                        </div>
                        <?php
							$i++;
							}
						?>
					<!--Video content deleted from here-->
					
                    </div>
                    <div class="col-md-10 col-sm-10 col-xs-10 zoom">
                        <div class="badges">
                            <?php if($row['featured'] == 'ok'){ ?>
                            <div class="hot"><?php echo translate('featured'); ?></div>
                            <?php } ?>
                            <?php if($row['deal'] == 'ok'){ ?>
                            <div class="new circle7"><?php echo translate("today's_deal"); ?></div>
                            <?php } ?>
                        </div>
                        <img class="img-responsive main-img zoom" id="set_image" src="" alt=""/>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <h3 class="product-title"><?php echo $row['title'];?></h3>
                <div class="product-info">
                	<p>
                        <a href="<?php echo base_url(); ?>index.php/home/category/<?php echo $row['category']; ?>">
                        	<?php echo $this->crud_model->get_type_name_by_id('category',$row['category'],'category_name');?>
                        </a>
                    </p>
                    ||
                    <p>
                    	<a href="<?php echo base_url(); ?>index.php/home/category/<?php echo $row['category']; ?>/<?php echo $row['sub_category']; ?>">
							<?php echo $this->crud_model->get_type_name_by_id('sub_category',$row['sub_category'],'sub_category_name');?>
                        </a>
                    </p>
                    ||
                    <p>
                    	<a href="<?php echo base_url(); ?>index.php/home/category/<?php echo $row['category']; ?>/<?php echo $row['sub_category']; ?>-<?php echo $row['brand']; ?>">
						<?php echo $this->crud_model->get_type_name_by_id('brand',$row['brand'],'name');?>
                        </a>
                    </p>
                    
                </div>
                <div class="added_by" style="display:none;">
                	<?php echo translate('sold_by_:');?>
                	<p>
						<?php echo $this->crud_model->product_by($row['product_id'],'with_link');?>
                    </p>
                </div>
                <div class="product-rating clearfix">
                    <div class="rating ratings_show" data-original-title="<?php echo $rating = $this->crud_model->rating($row['product_id']); ?>"	
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
                    
                    <div class="rating inp_rev list-inline" style="display:none;" data-pid='<?php echo $row['product_id']; ?>'>
                        <span class="star rate_it" id="rating_5" data-rate="5"></span>
                        <span class="star rate_it" id="rating_4" data-rate="4"></span>
                        <span class="star rate_it" id="rating_3" data-rate="3"></span>
                        <span class="star rate_it" id="rating_2" data-rate="2"></span>
                        <span class="star rate_it" id="rating_1" data-rate="1"></span>
                    </div>
                    <a class="reviews ratings_show" href="#">
                    	<?php echo $row['rating_num']; ?>
                    	<?php echo translate('rating(s)'); ?> 
                    </a>  
                    <?php  
						if($this->session->userdata('user_login') == 'yes'){
							$user_id = $this->session->userdata('user_id');
							$rating_user = json_decode($row['rating_user'],true);
							if(!in_array($user_id,$rating_user)){
					?>
                    <a class="add-review rev_show ratings_show" href="#">
						| <?php echo translate('add_your_review');?>
                    </a>
                    <?php 
							}
						}
					?>
                </div>
         
                <hr class="page-divider"/>
                <div class="product-price">
                    <?php echo translate('price_:'); ?>
                    <?php if($row['multiple_price']==1) { 
					$multiple_option=$this->db->get_where('multiple_option', array('product_id' => $row['product_id'],'status' => '1'))->result_array();
					$minAmount = min(array_column($multiple_option, 'amount'));
					
					$as=0; 
					$as1 = 0;
					foreach($multiple_option as $datas)
					{
						if($datas['amount']==$minAmount)
						{
							$as1= $as;	
						}	
						$as++;
					}
					//echo $row['discount'];
					if($row['discount'] > 0){
                        $displayAmt_price=$this->crud_model->get_product_price1($row['product_id'],$minAmount);
						$displayAmt_price=($displayAmt_price!="")?floatval($displayAmt_price):0;
                        ?>
                        <script>
                            $(document).ready(function(){
                                $("#displayAmt_price").val('<?php echo $displayAmt_price; ?>');
                            });
                        </script>
					<ins id="displayAmt">
							<?php //echo currency($minAmount); ?>
                            <?php echo currency().number_format($displayAmt_price,2);  ?>
                            
                        </ins> 
                        
                        <unit><?php echo ' /'.$row['unit'];?></unit>
                        <del><?php// echo currency($minAmount); ?></del>
                        <span class="label label-success">
						<?php 
							echo translate('discount:_').$row['discount'];
							if($row['discount_type']=='percent'){
								echo '%';
							}
							else{
							//	echo currency($minAmount);
							
							    	echo currency();
							}
						?>
                      	</span>
                    <?php } else {
						$minAmount=($minAmount!="")?floatval($minAmount):0;
						?>
                        <script>
                            $(document).ready(function(){
                                $("#displayAmt_price").val('<?php echo $minAmount; ?>');
                            });
                        </script>
                        <ins>
							<span id="displayAmt"><?php echo currency().number_format($minAmount,2); ?></span>
                        	<unit><?php echo ' /'.$row['unit'];?></unit>
                        </ins>
					<?php 
					} 
					
					}
					else 
					{
						if($row['discount'] > 0){  
				// 			$displayAmt_price=$this->crud_model->get_product_price($row['product_id']);
				// 			$displayAmt_price=($displayAmt_price!="")?floatval($displayAmt_price):0;
					?> 
					<script>
						$(document).ready(function(){
							$("#displayAmt_price").val('<?php echo $displayAmt_price; ?>');
						});
					</script>
                        <ins>
							<?php echo ($this->crud_model->get_product_price($row['product_id'],$minAmount)); ?>
                            <unit><?php echo ' /'.$row['unit'];?></unit>
                        </ins> 
                        <del><?php // echo currency($row['sale_price']); ?></del>
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
                    <?php } else {
						$displayAmt_price=$row['sale_price'];
						$displayAmt_price=($displayAmt_price!="")?floatval($displayAmt_price):0;
						?>
					<script>
						$(document).ready(function(){
							$("#displayAmt_price").val('<?php echo $displayAmt_price; ?>');
						});
					</script>
                        <ins>
							<?php echo currency().number_format($displayAmt_price,2); ?>
                        	<unit><?php echo ' /'.$row['unit'];?></unit>
                        </ins> 
                    <?php } }?>
                </div>
                <?php 
                    include 'order_option.php';
                ?>
            </div>
        </div>
    </div>
</section>

<!-- /PAGE -->
                
<script>
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
		//setTimeout(function(){ 
			$('.zoom').zoome({hoverEf:'transparent',showZoomState:true,magnifierSize:[200,200]});
		//}, 3000);
		
	});
	
	function destroyZoome(obj){
		if(obj.parent().hasClass('zm-wrap'))
		{
			obj.unwrap().next().remove();
		}
	}
</script>
<script>
	$('body').on('click', '.rev_show', function()
	{
		$('.ratings_show').hide('fast');
		$('.inp_rev').show('slow');
	});
</script>
<style>
	.rate_it{
		display:none;	
	}

	.product-single .badges div{
		padding: 0 5px;
	}

	.product-price del {
		font-weight: 400;
		font-size: 24px;
	}
</style>