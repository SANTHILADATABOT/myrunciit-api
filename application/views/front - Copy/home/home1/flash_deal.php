<?php /*?><?php
					
					$limit1 =  $this->db->get_where('gr_ui_settings',array('type' => 'no_of_featured_products','oid'=>$this->session->userdata('propertyIDS')))->row()->value;
                    $featured1=$this->crud_model->product_list_set('flash',$limit1);
                    ?>
                    
<section class="page-section featured-products as <?php if(empty($featured1)) { echo'hidden'; } ?>">
    <div class="container box-sha">
		<div class="_1dPkhG clearfix">
			<h2 class="puxlXr"><span class="sv"><?php echo translate('flash');?> <?php echo translate('deal');?></span> </h2>
		<!--	<div class="_2Umlwf"><a class="_2AkmmA _1eFTEo" href="<?php echo base_url(); ?>home/others_product/todays_deal">VIEW ALL</a></div>-->
		</div>
        <div class="featured-products-carousel">
            <div class="owl-carousel" id="featured-products-carousel2">
                <?php
					$box_style =  $this->db->get_where('gr_ui_settings',array('type' => 'featured_product_box_style','oid'=>$this->session->userdata('propertyIDS')))->row()->value;
					$box_style=9;
					$limit =  $this->db->get_where('gr_ui_settings',array('type' => 'no_of_featured_products','oid'=>$this->session->userdata('propertyIDS')))->row()->value;
                    $featured=$this->crud_model->product_list_set('flash',$limit);
                    foreach($featured as $row)
					{
						echo $this->html_model->product_box($row, 'grid', $box_style);
					}
                ?>
            </div>
        </div>
    </div>
</section>
<?php */?>
<?php
					
				//	$limit1 =  $this->db->get_where('ui_settings',array('type' => 'no_of_featured_products','oid'=>$this->session->userdata('propertyIDS')))->row()->value;
				$limit1 = 12;
                    $featured1=$this->crud_model->product_list_set('today_deals',$limit1);
                    ?>
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
?>

<section class="page-section featured-products as <?php if(empty($featured1)) { echo'hidden'; } ?>">
    <div class="container box-sha">
		<div class="_1dPkhG clearfix">
			<h2 class="section-title section-title-lg"><span><?php echo translate('flash');?> <?php echo translate('deal');?></span> 
            <?php 
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
                 
                <span style="color:#B31500; margin-right:5px;" id="demonew"></span>
            <?php } } }  ?>
            <span class="pull-right rht_section">
            	<a class="fo-oo"  href="<?php echo base_url(); ?>/index.php/home/others_product/flash_deal"> <?php echo translate('see all');?></a>
            </span>
            </h2>
		<!--	<div class="_2Umlwf"><a class="_2AkmmA _1eFTEo" href="<?php echo base_url(); ?>home/others_product/todays_deal">VIEW ALL</a></div>-->
		</div>
        <div class="featured-products-carousel">
            <div class="owl-carousel" id="featured-products-carousel-3">
            	
        
                <?php
				//	$box_style =  $this->db->get_where('gr_ui_settings',array('type' => 'featured_product_box_style','oid'=>$this->session->userdata('propertyIDS')))->row()->value;
				//	$box_style=7;
				//	$limit =  $this->db->get_where('gr_ui_settings',array('type' => 'no_of_featured_products','oid'=>$this->session->userdata('propertyIDS')))->row()->value;
				$limit=12;
                    $featured=$this->crud_model->product_list_set('today_deals',$limit);
                    foreach($featured as $row)
					{
					//	print_r($row);
						$box_style=2;
						echo $this->html_model->product_box($row, 'grid', $box_style);
					}
                ?>
            </div>
        </div>
    </div>
</section>
<?php } ?>
<script>

$(document).ready(function(){
	setTimeout( function(){ 
		set_featured_product_box_height();
	},200 );
});

function set_featured_product_box_height()
{
	var max_title=0;
	$('.featured-products .caption-title').each(function()
	{
        var current_height= parseInt($(this).css('height'));
		if(current_height >= max_title)
		{
			max_title = current_height;
		}
    });
	$('.featured-products .caption-title').css('height',max_title);
}

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