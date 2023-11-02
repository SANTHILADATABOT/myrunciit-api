<?php
    $discus_id = $this->db->get_where('general_settings',array('type'=>'discus_id'))->row()->value;
    $fb_id = $this->db->get_where('general_settings',array('type'=>'fb_comment_api'))->row()->value;
    $comment_type = $this->db->get_where('general_settings',array('type'=>'comment_type'))->row()->value;
?>
<!-- PAGE -->
<section class="page-section specification hidden">
    <div class="container">
        <div class="tabs-wrapper content-tabs">
            <ul class="nav nav-tabs">
           <?php if($row['bidding']=='1'){  ?>
            <li><a href="#tab1" data-toggle="tab"><?php echo translate('bidding histry'); ?></a></li>
            <?php } ?>
            
                <li <?php if($comment_type == ''){ ?>class="active"<?php } ?> ><a href="#tab2" data-toggle="tab"><?php echo translate('full_description'); ?></a></li>
                <li ><a href="#tab3" data-toggle="tab"><?php echo translate('additional_specification'); ?></a></li>
                <li ><a href="#tab4" data-toggle="tab"><?php echo translate('shipment_info'); ?></a></li>
                
                <?php /*?><li <?php if($comment_type !== ''){ ?>class="active"<?php } ?> ><a href="#tab5" data-toggle="tab"><?php echo translate('reviews'); ?></a></li><?php */?>
                <li><a href="#tab6" data-toggle="tab"><?php echo translate('reviews'); ?></a></li>
            </ul>
            <div class="tab-content">
                  <div class="tab-pane fade" id="tab1">
                  
                    
                    <?php 
					
					//print_r( $baatch_max); 
					if($baatch_max>0)
					{
						$new=count($bidding_history['batch']);
						for($iz=0; $iz<$baatch_max; $iz++)
						{
						?>
                        <br />
<br />

                    <table style="width:100%; border:1px #666666;">
                        <tr>
                          <th colspan="5" align="left" valign="middle" style="color:#FFF; font-weight:bold;" bgcolor="#003399">&nbsp; &nbsp;Batch No (
                           <?php if($iz==0){echo 'current';}else { echo $iz;}?> )</th>
                        </tr>
                        <tr>
                        <th bgcolor="#0066FF">&nbsp; &nbsp;S.No</th>
                        <th width="16%" bgcolor="#0066FF"  style="color:#FFF; font-weight:bold;" >User Name</th>
                        <th width="16%" bgcolor="#0066FF" style="color:#FFF; font-weight:bold;" >Bidd Amount</th>
                        <th width="28%" bgcolor="#0066FF" style="color:#FFF; font-weight:bold;" >Action</th>
                        <th width="22%" bgcolor="#0066FF" style="color:#FFF; font-weight:bold;" >Time</th>
                      </tr>
                    <?php
                        //echo $this->db->get_where('business_settings',array('type'=>'shipment_info'))->row()->value;
						//echo '<pre>'; print_r($bidding_history); 
						$i=1;
					foreach($bidding_history as $bid_his)
					{
						
						if($bid_his['batch_no']==$iz)
						{
						
					
					?>	
                        <tr>
                        <td height="35" bgcolor="#CCCCCC" style="color:#000; font-weight:bold;" >&nbsp; &nbsp;<?php echo $i;?></td>
                        <td bgcolor="#CCCCCC"  style="color:#000; font-weight:bold;" ><?php echo $bid_his['uname'];?></td>
                        <td bgcolor="#CCCCCC"  style="color:#000; font-weight:bold;" ><?php echo $bid_his['bid_amt'];?></td>
                        <td bgcolor="#CCCCCC"  style="color:#000; font-weight:bold;" ><?php 
						
						if($bid_his['final_bidder']==0)
							{
								echo 'Waiting for admin approval';
							}
							if($bid_his['final_bidder']==1)
							{
								echo 'Win the bidd';
							}
							if($bid_his['final_bidder']==2)
							{
								echo '-';
							}
						?></td>
                        <td bgcolor="#CCCCCC"  style="color:#000; font-weight:bold;" ><?php echo date('d/m/Y h:i:s A',$bid_his['time']);?></td>
                        </tr>
                        
                        
                    <?php  
					$i++;
					} }  ?>
                    </table>
                    
                    <?php } }
					
					?>
                </div>
                
                
                <div class="tab-pane fade <?php if($comment_type == ''){ ?>in active<?php } ?>" id="tab2">
                    <?php echo $row['description'];?>
                </div>
                <div class="tab-pane fade" id="tab3">
                    <div class="panel panel-sea margin-bottom-40">
                    <?php 
                        $a = $this->crud_model->get_additional_fields($row['product_id']);
                        if(count($a)>0){
                    ?>
                        <table class="table table-bordered">
                            <tbody>
                            <?php
                                foreach ($a as $val) {
                            ?>
                                <tr>
                                    <td style="text-align:center;"><?php echo $val['name']; ?></td>
                                    <td style="text-align:center;"><?php echo $val['value']; ?></td>
                                </tr>
                            <?php
                                }
                            ?>
                            </tbody>
                        </table>
                    <?php 
                        }
                    ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab4">
                    <?php
                        echo $this->db->get_where('business_settings',array('type'=>'shipment_info'))->row()->value;
                    ?>
                </div>
                <div class="tab-pane fade" id="tab6">
                    <?php
                        $reviewsa=$this->db->get_where('review_product',array('product_id'=>$row['product_id']))->result_array();
						foreach($reviewsa as $review) { 
                    ?>
                    <div class="col-sm-12">
<div class="col-sm-1">
<div class="thumbnail">
<img class="img-responsive user-photo" src="<?php 
                            if(file_exists('uploads/user_image/user_'.$review['user_id'].'.jpg')){ 
                                echo $this->crud_model->file_view('user',$review['user_id'],'100','100','no','src','','','.jpg').'?t='.time();
                            }  else {
                                echo base_url(). "uploads/user_image/default.jpg";
                            } ?>" width="180">
</div><!-- /thumbnail -->
</div><!-- /col-sm-1 -->

<div class="col-sm-5">
<div class="panel panel-default">
<div class="panel-heading">
<strong><?php echo $this->crud_model->get_type_name_by_id('user', $review['user_id'], 'username');?></strong> <span class="text-muted pull-right"><?php echo "commented on " .date('d M, Y',strtotime($review['created_date'])); ?></span>
</div>
<div class="panel-body">
<div>
<span class="label label-success">
						   <?php echo  $review['rating']; ?>
                           <i class="fa fa-star"></i>                	</span>
                           <span style="margin-left:10px;">
                           		<?php echo  $review['title']; ?>
                           </span>
</div>
<?php echo  $review['description']; ?>
</div><!-- /panel-body -->
</div><!-- /panel panel-default -->
</div><!-- /col-sm-5 -->

<!-- /col-sm-1 -->

<!-- /col-sm-5 -->
</div>
                    <?php } ?>
                </div>
              
                <div class="tab-pane fade" id="tab5" style="display:none;">
					<?php if($comment_type == 'disqus'){ ?>
                    <div id="disqus_thread"></div>
                    <script type="text/javascript">
                        /* * * CONFIGURATION VARIABLES * * */
                        var disqus_shortname = '<?php echo $discus_id; ?>';
                        
                        /* * * DON'T EDIT BELOW THIS LINE * * */
                        (function() {
                            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                        })();
                    </script>
                    <script type="text/javascript">
                        /* * * CONFIGURATION VARIABLES * * */
                            var disqus_shortname = '<?php echo $discus_id; ?>';
                        
                        /* * * DON'T EDIT BELOW THIS LINE * * */
                        (function () {
                            var s = document.createElement('script'); s.async = true;
                            s.type = 'text/javascript';
                            s.src = '//' + disqus_shortname + '.disqus.com/count.js';
                            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
                        }());
                    </script>
                    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
                    <?php
                        }
                        else if($comment_type == 'facebook'){
                    ?>

                        <div id="fb-root"></div>
                        <script>(function(d, s, id) {
                          var js, fjs = d.getElementsByTagName(s)[0];
                          if (d.getElementById(id)) return;
                          js = d.createElement(s); js.id = id;
                          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=<?php echo $fb_id; ?>";
                          fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));</script>
                        <div class="fb-comments" data-href="<?php echo $this->crud_model->product_link($row['product_id']); ?>" data-numposts="5"></div>

                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-section specification">
    <div class="container">
    	<?php if($row['bidding']=='1'){  ?>
            <div class="specification1">
                <h3 class="title_head"><?php echo translate('bidding histry'); ?></h3>
                 <?php 
					
					//print_r( $baatch_max); 
					if($baatch_max>0)
					{
						$new=count($bidding_history['batch']);
						for($iz=0; $iz<$baatch_max; $iz++)
						{
						?>
                        <br />
<br />

                    <table style="width:100%; border:1px #666666;">
                        <tr>
                          <th colspan="5" align="left" valign="middle" style="color:#FFF; font-weight:bold;" bgcolor="#003399">&nbsp; &nbsp;Batch No (
                           <?php if($iz==0){echo 'current';}else { echo $iz;}?> )</th>
                        </tr>
                        <tr>
                        <th bgcolor="#0066FF">&nbsp; &nbsp;S.No</th>
                        <th width="16%" bgcolor="#0066FF"  style="color:#FFF; font-weight:bold;" >User Name</th>
                        <th width="16%" bgcolor="#0066FF" style="color:#FFF; font-weight:bold;" >Bidd Amount</th>
                        <th width="28%" bgcolor="#0066FF" style="color:#FFF; font-weight:bold;" >Action</th>
                        <th width="22%" bgcolor="#0066FF" style="color:#FFF; font-weight:bold;" >Time</th>
                      </tr>
                    <?php
                        //echo $this->db->get_where('business_settings',array('type'=>'shipment_info'))->row()->value;
						//echo '<pre>'; print_r($bidding_history); 
						$i=1;
					foreach($bidding_history as $bid_his)
					{
						
						if($bid_his['batch_no']==$iz)
						{
						
					
					?>	
                        <tr>
                        <td height="35" bgcolor="#CCCCCC" style="color:#000; font-weight:bold;" >&nbsp; &nbsp;<?php echo $i;?></td>
                        <td bgcolor="#CCCCCC"  style="color:#000; font-weight:bold;" ><?php echo $bid_his['uname'];?></td>
                        <td bgcolor="#CCCCCC"  style="color:#000; font-weight:bold;" ><?php echo $bid_his['bid_amt'];?></td>
                        <td bgcolor="#CCCCCC"  style="color:#000; font-weight:bold;" ><?php 
						
						if($bid_his['final_bidder']==0)
							{
								echo 'Waiting for admin approval';
							}
							if($bid_his['final_bidder']==1)
							{
								echo 'Win the bidd';
							}
							if($bid_his['final_bidder']==2)
							{
								echo '-';
							}
						?></td>
                        <td bgcolor="#CCCCCC"  style="color:#000; font-weight:bold;" ><?php echo date('d/m/Y h:i:s A',$bid_his['time']);?></td>
                        </tr>
                        
                        
                    <?php  
					$i++;
					} }  ?>
                    </table>
                    
                    <?php } }
					
					?>
            </div>
            
            <?php } ?>
          
        <div class="specification1">
            
        	<h3 class="title_head"><?php echo translate('full_description'); ?></h3>
        
        	    	<?php 
						
						$prod_id = $row['product_id']; 
					
                    if(file_exists('uploads/product_image/product_'.$prod_id.'.mp4')) { 
                    ?>
                    	<div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12 others-img">
            <?php echo $row['description'];?></div>
            
                    <div class="col-md-6 col-sm-12 col-xs-12">
                     
                          <div class="" id="set_video">
                             
                            <video controls class="img-responsive main-img" style="height: 400px;">
                               <source src="<?php echo $video?>">
                            </video>
                           
                          </div>  
                       
                    </div>
                    </div>
                     <?php  } else{ ?>
                      <?php echo $row['description'];?></div>
                      <?php  } ?>
        </div>
        <div class="specification1">
        	<h3 class="title_head"><?php echo translate('additional_specification'); ?></h3>
        	<?php 
                        $a = $this->crud_model->get_additional_fields($row['product_id']);
                        if(count($a)>0){
                    ?>
                        <table class="table table-bordered">
                            <tbody>
                            <?php
                                foreach ($a as $val) {
                            ?>
                                <tr>
                                    <td style="text-align:center;"><?php echo $val['name']; ?></td>
                                    <td style="text-align:center;"><?php echo $val['value']; ?></td>
                                </tr>
                            <?php
                                }
                            ?>
                            </tbody>
                        </table>
                    <?php 
                        }
                    ?>
        </div>
        <div class="specification1">
        	<h3 class="title_head"><?php echo translate('shipment_info'); ?></h3>
        	<?php
                        echo $this->db->get_where('business_settings',array('type'=>'shipment_info'))->row()->value;
                    ?>
        </div>
        
          <div class="specification1">
        	<h3 class="title_head"><?php echo translate('reviews'); ?></h3>
 <?php
                                  $this->db->where('status', 1);
                        $reviewsa=$this->db->get_where('review_product',array('product_id'=>$row['product_id']))->result_array();
						foreach($reviewsa as $review) { 
                    ?>
                    <div class="col-sm-12">
<div class="col-sm-1">
<div class="thumbnail">
<img class="img-responsive user-photo" src="<?php 
                            if(file_exists('uploads/user_image/user_'.$review['user_id'].'.jpg')){ 
                                echo $this->crud_model->file_view('user',$review['user_id'],'100','100','no','src','','','.jpg').'?t='.time();
                            }  else {
                                echo base_url(). "uploads/user_image/default.jpg";
                            } ?>" width="180">
</div><!-- /thumbnail -->
</div><!-- /col-sm-1 -->

<div class="col-sm-5">
<div class="panel panel-default">
<div class="panel-heading">
<strong><?php echo $this->crud_model->get_type_name_by_id('user', $review['user_id'], 'username');?></strong> <span class="text-muted pull-right"><?php echo "commented on " .date('d M, Y',strtotime($review['created_date'])); ?></span>
</div>
<div class="panel-body">
<div>
<span class="label label-success">
						   <?php echo  $review['rating']; ?>
                           <i class="fa fa-star"></i>                	</span>
                           <span style="margin-left:10px;">
                           		<?php echo  $review['title']; ?>
                           </span>
</div>
<?php echo  $review['description']; ?>
</div><!-- /panel-body -->
</div><!-- /panel panel-default -->
</div><!-- /col-sm-5 -->

<!-- /col-sm-1 -->

<!-- /col-sm-5 -->
</div>
                    <?php } ?>        </div>
        
    </div>
</section>

<!-- /PAGE -->
<style>
@media(max-width: 768px) {
	.specification .nav-tabs>li{
		float: none;
		display: block;
		text-align: center;
	}
}
</style>