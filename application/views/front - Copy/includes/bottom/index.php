		<script>
            var base_url = "<?php echo base_url(); ?>";
        </script>
        <script src="<?php echo base_url(); ?>template/front/js/ajax_method.js"></script>
        <script src="<?php echo base_url(); ?>template/front/js/bootstrap-notify.min.js"></script>
        <script src="<?php echo base_url(); ?>template/front/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>template/front/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>template/front/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
        <!-- JS Global -->
        <script src="<?php echo base_url(); ?>template/front/plugins/superfish/js/superfish.min.js"></script>
        <script src="<?php echo base_url(); ?>template/front/plugins/jquery.sticky.min.js"></script>
        <script src="<?php echo base_url(); ?>template/front/plugins/jquery.easing.min.js"></script>
        <script src="<?php echo base_url(); ?>template/front/plugins/jquery.smoothscroll.min.js"></script>
        <script src="<?php echo base_url(); ?>template/front/plugins/smooth-scrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>template/front/plugins/jquery.cookie.js"></script>
        
        <script src="<?php echo base_url(); ?>template/front/plugins/modernizr.custom.js"></script>
        <script src="<?php echo base_url(); ?>template/front/modal/js/jquery.active-modals.js"></script>
        <script src="<?php echo base_url(); ?>template/front/js/theme.js"></script>
    <script type="text/javascript">
     function decrease_val1(id){

	var value=$('#qtyh_'+id).val();

	if(value > 1){

		var value=--value;

	}

	//$('.quantity-field').val(value);
	$('input#qtyh_'+id).val(value);
		$.ajax({
				url: base_url+'index.php/home/cart/in_cart/'+id,

				beforeSend: function() {
				},

				success: function(data) {
					 var res = data.split('---');
					if(res[0]=="already"){
					   	 $.ajax({

				            url: base_url+'index.php/home/cart/quantity_update/'+res[1]+'/'+value,
				            beforeSend: function() {

				            },
				            success: function(data) {

					

					reload_header_cart();
					if (document.location.href == '<?php echo base_url(); ?>home/cart_checkout') {
					    load_orders();
					}

				

					

				},

				error: function(e) {

					console.log(e)

				}

			});
					   
					}

				},
			
			});

}
function check_ours(id){
    var value=$('#qty_'+id).val();
     if(value==0){
        var value=$('#qty_'+id).val(1);
        }
    
}
    function increase_val1(id){
    var button=$(this);
	var value=$('#qtyh_'+id).val();

	var max_val =parseInt($('#qtyh_'+id).attr('max'));
	//alert(max_val);

	if(value < max_val){

		var value=++value;

	}
  
	$('input#qtyh_'+id).val(value);
	
	$.ajax({
				url: base_url+'index.php/home/cart/in_cart/'+id,

				beforeSend: function() {
				},

				success: function(data) {
					 var res = data.split('---');
					if(res[0]=="already"){
					   	 $.ajax({

				            url: base_url+'index.php/home/cart/quantity_update/'+res[1]+'/'+value,
				            beforeSend: function() {

				            },
				            success: function(data) {

					

					reload_header_cart();
					if (document.location.href == '<?php echo base_url(); ?>home/cart_checkout') {
					    load_orders();
					}

				

					

				},

				error: function(e) {

					console.log(e)

				}

			});
					   
					}

				},
			
			});

}
		function decrease_val2(id){
			var value=$('#qtyc_'+id).val();
			var rowid=$('#qtyc_'+id).data('rowid');
			if(value > 1){
				var value=--value;
			}
			$('input#qtyc_'+id).val(value);
    		$.ajax({
				url: base_url+'index.php/home/cart/quantity_update/'+rowid+'/'+value,

				beforeSend: function() {
				},
				success: function(data) {
					reload_header_cart();
					if (document.location.href == '<?php echo base_url(); ?>home/cart_checkout') {
					    load_orders();
					}
				},
				error: function(e) {
					console.log(e)
				}
			});
		}
		function increase_val2(id){
			var value=$('#qtyc_'+id).val();
			var rowid=$('#qtyc_'+id).data('rowid');
			var max_val =parseInt($('#qtyc_'+id).attr('max'));
			if(value < max_val){
				var value=++value;
			}
			$('input#qtyc_'+id).val(value);
   			$.ajax({
				url: base_url+'index.php/home/cart/quantity_update/'+rowid+'/'+value,
				beforeSend: function() {
				},
				success: function(data) {
					reload_header_cart();
						if (document.location.href == '<?php echo base_url(); ?>home/cart_checkout') {
					    load_orders();
					}
				},

				error: function(e) {
					console.log(e)
				}
			});
		}
	</script>    
        <?php
	       include $asset_page.'.php';
		?>


        <form id="cart_form_singl">
                <input type="hidden" name="color" value="">
                <input type="hidden" name="qty" value="1">
        </form>