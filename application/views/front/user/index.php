<?php /*?><section class="page-section profile_top" >
    <div class="wrap container">
    	<div class="row">
            <div class="col-md-10" >
                <div class="top_nav">
                    <ul>
                        <li class="active">
                            <span id="info">
                                <?php echo translate('profile');?>
                            </span>
                        </li>
                        <li>
                        	<span id="wishlist">
                        		<?php echo translate('wishlist');?>
                        	</span>
                        </li>
                        <li>
                        	<span id="order_history">
                        		<?php echo translate('order_history');?>
                        	</span>
                        </li>
                        <li>
                        	<span id="bidding_history">
                        		<?php echo translate('bidding_history');?>
                        	</span>
                        </li>
                        <li>
                        	<span id="downloads">
                        		<?php echo translate('downloads');?>
                        	</span>
                        </li>
                        <li>
                        	<span id="update_profile">
                        		<?php echo translate('edit_profile');?>
                        	</span>
                        </li>
                        <li>
                        	<span id="ticket">
                        		<?php echo translate('support_ticket');?>
                        	</span>
                        </li>
                        
                        <li>
                        	<span id="wallet">
                        		<?php echo translate('wallet');?>
                        	</span>
                        </li>
                        
                        <li>
                        	<span id="wallet_history">
                        		<?php echo translate('user log');?>
                        	</span>
                        </li>
                      </ul>
                </div>
            </div>
            <div class="col-md-2">
                <div class="top_nav">
                    <ul>
                        <li><a style="color:#F00;" href="<?php echo base_url(); ?>index.php/home/logout/">logout</a></li>
                     </ul>
                </div>
            </div>
        </div>
	</div>
</section><?php */?>
<hr class="hr_sp">
<section class="page-section">
    <div class="wrap container">
    	<div class="row profile">
        <div class="col-lg-3 col-md-3">
                    <input type="hidden" id="state" value="normal" />
                    <div class="widget account-details">
                    <div class="information-title" style="margin-bottom: 0px;"><?php echo translate('my_profile');?></div>
                        <ul class="pleft_nav">
                        
                        <li class="active">
                            <span id="info">
                                <?php echo translate('profile');?>
                            </span>
                        </li>
                        <li>
                        	<span id="wallet">
                        		<?php echo translate('wallet');?>
                        	</span>
                        </li>
                        <li>
                        	<span id="wishlist">
                        		<?php echo translate('wishlist');?>
                        	</span>
                        </li>
                        
                        <li>
                        	<span id="order_history">
                        		<?php echo translate('order_history');?>
                        	</span>
                        </li>
                        <li style="display:none;">
                        	<span id="subscribe_product">
                        		<?php echo translate('subscribe_product');?>
                        	</span>
                        </li>
                        <li  style="display:none;">
                        	<span id="bidding_history">
                        		<?php echo translate('bidding_history');?>
                        	</span>
                        </li>
                        <li  style="display:none;">
                        	<span id="downloads">
                        		<?php echo translate('downloads');?>
                        	</span>
                        </li>
                        <li>
                        	<span id="update_profile">
                        		<?php echo translate('edit_profile');?>
                        	</span>
                        </li>
                        <li style="display:none;">
                        	<span id="ticket">
                        		<?php echo translate('support_ticket');?>
                        	</span>
                        </li>
                        
                        <li>
                        	<span id="rewards_history">
                        		<?php echo translate('rewards_log');?>
                        	</span>
                        </li>
                        <li>
                        	<span id="trans_history">
                        		<?php echo translate('my transaction');?>
                        	</span>
                        </li>
                        <li>
                        	<span id="wallet_history">
                        		<?php echo translate('user log');?>
                        	</span>
                        </li>
                        <?php if($this->crud_model->get_type_name_by_id('general_settings','83','value') == 'ok'){ ?>
                                 <li style="display:none;">
                        			<span id="uploaded_products">
									<?php echo translate('uploaded_products');?></span>
                                </li>
                                  <li style="display:none;">
                        			<span id="package_payment_info">
									<?php echo translate('package_payment_info');?></span>
                                </li>
                              
                                <?php } ?>
                        <?php if($this->crud_model->get_type_name_by_id('general_settings','83','value') == 'ok'){ ?>
                         <li style="display:none;">
                        	<span id="post_product">
                        		<?php echo translate('post_product');?>
                        	</span>
                        </li>
                               <li style="display:none;">
                        	<span id="post_product_bulk">
                        		<?php echo translate('post_product_bulk');?>
                        	</span>
                        </li>
                            <?php } ?>
                        </ul>
                        </div>
                        </div>
                     
         <div class="col-lg-9 col-md-9">
        <div id="profile-content">
        </div>
        </div>
        </div
    ></div>
</section>
<!-- Modal For C-C Post confirm -->
<div class="modal fade" id="prodPostModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?=translate('confirm_your_upload')?></h4>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div><?=translate('your_remaining_product_upload_amount:_').'<b><span class="post_amount">0</span></b><br>'.translate('uploading_a_product_will_cost_you_1_upload_amount</br><b class="text-danger">After_uploading_a_product_you_can_not_edit_it_again</b>')?></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger post_confirm_close" data-dismiss="modal"><?=translate('close')?></button>
                <button type="button" class="btn btn-theme btn-theme-sm post_confirm" style="text-transform: none;font-weight: 400;"><?=translate('confirm')?></button>
            </div>
        </div>
    </div>
</div>
<!-- Modal For C-C Post confirm -->

<!-- Modal For C-C Status change -->
<div class="modal fade" id="statusChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?=translate('change_availability_status')?></h4>
            </div>
            <div class="modal-body">
                <div class="text-center content_body" id="content_body">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal For C-C Status change -->
<script>
	var top = Number(200);
	var loading_set = '<div style="text-align:center;width:100%;height:'+(top*2)+'px; position:relative;top:'+top+'px;"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>';
	
	$('#info').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/info");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#wishlist').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/wishlist");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#order_history').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/order_history");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#bidding_history').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/bidding_history");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#downloads').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/downloads");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#update_profile').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/update_profile");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#ticket').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/ticket");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#wallet').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/wallet");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#wallet_history').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/wallet_history");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	
	$('#trans_history').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/trans_history");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	
	$('#rewards_history').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/rewards_history");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#post_product').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/post_product");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#post_product_bulk').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/post_product_bulk");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#uploaded_products').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/uploaded_products");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#package_payment_info').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/package_payment_info");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#subscribe_product').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/subscribe_product_info");
		$("li").removeClass("active");
		$(this).closest("li").addClass("active");
	});
	$('#message_view').on('click',function(){
		$("#profile-content").html(loading_set);
		$("#profile-content").load("<?php echo base_url()?>index.php/home/profile/message_view");
	});
	function view_package_details(hurl){
		$('#qoiqois').data('ajax',hurl);
		$('#qoiqois').click();
	}
	
	$(document).ready(function(){
		$("#<?php echo $part; ?>").click();
    });
</script>
<style type="text/css">
    .pagination_box a{
        cursor: pointer;
    }
</style>
<?php if($balance_alert=="yes"){ ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){
	Swal.fire(
		'<?php echo translate('insufficient_balance_in_wallet');?>',
		'<h4><?php echo translate('deposit_to_wallet_then_proceed');?></h4>',
		'warning'
	);
});
</script>
<?php } ?>