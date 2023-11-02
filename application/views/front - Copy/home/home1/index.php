<div id="pnopoi"></div>
<?php
if ($this->crud_model->get_type_name_by_id('general_settings', '53', 'value') == 'ok') {
   include 'slider.php';
}
?>
<?php
if ($this->crud_model->get_type_name_by_id('general_settings', '62', 'value') == 'ok') {
   include 'category_menu.php';
}
?>
<?php
include 'top_banner.php';
?>
<?php
include 'advanced_search.php';
?>
<?php
if ($this->crud_model->get_type_name_by_id('ui_settings', '24', 'value') == 'ok') {
   include 'featured_products.php';
}
include 'flash_deal.php';
?>
<?php
if ($this->crud_model->get_type_name_by_id('general_settings', '58', 'value') == 'ok') {
   if ($this->crud_model->get_type_name_by_id('ui_settings', '25', 'value') == 'ok') {
      include 'vendors.php';
   }
}
?>
<?php
include 'category_products.php';
?>
<?php
if ($this->crud_model->get_type_name_by_id('general_settings', '82', 'value') == 'ok') {
   if ($this->crud_model->get_type_name_by_id('ui_settings', '40', 'value') == 'ok') {
      include 'product_bundle.php';
   }
}
if ($this->crud_model->get_type_name_by_id('general_settings', '83', 'value') == 'ok') {
   if ($this->crud_model->get_type_name_by_id('ui_settings', '43', 'value') == 'ok') {
      //include 'customer_products.php';
   }
}
?>
<?php
if ($this->crud_model->get_type_name_by_id('ui_settings', '26', 'value') == 'ok') {
   include 'blog.php';
}
?>
<?php
if ($this->crud_model->get_type_name_by_id('ui_settings', '31', 'value') == 'ok') {
   include 'special_products.php';
}
?>
<?php
if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') == 'ok') {
   if ($this->crud_model->get_type_name_by_id('ui_settings', '23', 'value') == 'ok') {
      include 'brands.php';
   }
}
?>
<?php include(__DIR__ . '/../../components/saudaModalComponent.php'); ?>

<?php 
$web_status=$this->db->get_where('general_settings', array('general_settings_id' => '112'))->row()->value;
if($web_status=='off') { ?>
<script>
$(document).ready(function(){
	
		$('#myModal').modal({
			backdrop: 'static'
		
	}); 
});
</script>
<style>
    .bs-example{
    	margin: 20px;
      
    }
</style>
<div class="bs-example">
    <!-- Button HTML (to Trigger Modal) -->
    
    
    <!-- Modal HTML -->
    <div id="myModal" class="modal fade" style="margin-top:15%;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <img src="<?php echo base_url(); ?>uploads/logo_image/logo_87.png" alt=""/>
                    <h4 class="modal-title txt">Online Orders Closed!</h4>
                    
                </div>
                
                
                
            </div>
        </div>
    </div>
</div>

<style>
.modal-header img {
   display: block;
   margin: 10px auto 0px;
   height: 40px;
   }
   .txt {
   font-size: 26px;
   text-align: center;
   color: #B31500;
   margin-top:20px
   }
</style>
<?php } ?>
