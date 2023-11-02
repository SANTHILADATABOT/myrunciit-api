
    <?php  
         date_default_timezone_set("Asia/Calcutta");
		// $this->db->where('added_by',$this->session->userdata('propertyIDS'));
        $todaydeal_id = $this->db->get('today_deals')->result_array();
		foreach($todaydeal_id as $todaydeal)
		{
		    $today = json_decode($todaydeal['product_id'], true);
		    foreach($today as $to)
		    {
		        //echo "<pre>"; print_r($dss); echo "</pre>";
		      $taketoday_deal=$this->db->get_where('today_deals', array('today_id' => $todaydeal['today_id']))->result_array();
		      $taketoday_deal1=$this->db->get_where('today_deals', array('today_id' => $todaydeal['today_id']))->result_array();
		    }
		}
		
		$date = date('Y-m-d H:i');
        foreach($taketoday_deal1 as $taketo)
        {
            $start_date = $taketo['toda_start_date'].' '.$taketo['today_start_time'];
            $end_d = $taketo['today_end_date'].' '.$taketo['today_end_time'];
        }
            if($date>=$start_date && $date<$end_d && $taketo['status']!=0)
            {
                foreach($taketoday_deal as $todeal)
                {
                    $poid1 = json_decode($todeal['product_id'], true);
                    foreach($poid1 as $po1)
                    {
						date_default_timezone_set("Asia/Calcutta"); 
						$new_current_time=time();
						$start_time=$todeal['toda_start_date'].' '.$todeal['today_start_time'];
						$new_start_time=strtotime($start_time);
						$end_time=$todeal['today_end_date'].' '.$todeal['today_end_time'];
						$new_end_time=strtotime($end_time);
			 			$new_start_time;
			 			$new_end_time;
						$new_current_time; 
							 if($new_current_time>$new_start_time && $new_current_time<$new_end_time)
							 {
							     ?>
							    <div style="text-align:center"> <span style="color:#ff0000;font-size: 28px;" id="demonew"></span></div>
							     <?php
							     
							 } } }}
                
                
?>


  
<!-- BREADCRUMBS -->
<section class="page-section breadcrumbs">
    <div class="container">
        <div class="page-header">
            <h2 class="section-title section-title-lg">
                <span>
                    <?php echo translate($product_type);?>  
                    <span class="thin"> <?php echo translate('products');?></span>
                </span>
            </h2>
         </div>
    </div>
</section>
<!-- /BREADCRUMBS -->
<!-- PAGE WITH SIDEBAR -->
<input type="hidden" value="<?php echo $product_type; ?>" id="type" />
<section class="page-section with-sidebar">
    <div class="container">
        <div class="row">
            <!-- SIDEBAR -->
            <?php 
                include 'sidebar.php';
            ?>
            <!-- /SIDEBAR -->
            <!-- CONTENT -->
            <div class="col-md-9" id="content">
                <!-- /shop-sorting -->
                <div id="page_content">
                
                </div>
            </div>
            <!-- /CONTENT -->
        </div>
    </div>
</section>
<!-- /PAGE WITH SIDEBAR -->
<script>
	function product_by_type(type){	
		var top = Number(200);
		var loading_set = '<div style="text-align:center;width:100%;height:'+(top*2)+'px; position:relative;top:'+top+'px;"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>';
		$("#page_content").html(loading_set);
		$("#page_content").load("<?php echo base_url()?>index.php/home/product_by_type/"+type);
	}
	$(document).ready(function(){
		var product_type=$('#type').val();
		product_by_type(product_type);
    });
</script>
<?php if($todeal['today_end_date']!="" && $todeal['today_end_time']!="" ) { ?>
<script>
var countDownDate26 = new Date("<?php echo date('M d, Y',strtotime($todeal['today_end_date'])).' '.$todeal['today_end_time']; ?>").getTime();

var x12 = setInterval(function() {
  var now2 = new Date().getTime();
  var distance2 = countDownDate26 - now2;
  var days2 = Math.floor(distance2 / (1000 * 60 * 60 * 24));
  var hours2 = Math.floor((distance2 % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes2 = Math.floor((distance2 % (1000 * 60 * 60)) / (1000 * 60));
  var seconds2 = Math.floor((distance2 % (1000 * 60)) / 1000);
    
  document.getElementById("demonew").innerHTML = days2 + "d " + hours2 + "h "
  + minutes2 + "m " + seconds2 + "s ";
  // If the count down is over, write some text 
  if (distance2 < 0) {
    clearInterval(x12);
    document.getElementById("demonew").innerHTML = "EXPIRED";
  }
}, 1000);
</script>
<?php } ?>