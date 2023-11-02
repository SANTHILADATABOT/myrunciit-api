<style>
.button {
  background-color: #008CBA;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}

input[type="checkbox"]:focus{
    border:0px;
    outline:0px;
}

input[type="radio"], input[type="checkbox"] {
	margin: 4px 0 0;
	margin-top: 1px \9;
	line-height: normal;
	-webkit-appearance: none;
	border: 0px;
}

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 70px;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
  width:65px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>
<div class="panel-body" id="demo_s">
	<?php             
	//$cod= $this->db->get_where('business_settings',array('type'=>'cash_set'))->result_array(); 
	$store0=array();
	$vendor1 = $this->db->get('vendor')->result_array();
	foreach($vendor1 as $vendor2){$store0[$vendor2['vendor_id']]=$vendor2['name'];}
	$category0=array();
	$category1 = $this->db->get('category')->result_array();
	foreach($category1 as $category2){$category0[$category2['category_id']]=$category2['category_name'];}
	$sub_category0=array();
	$sub_category1 = $this->db->get('sub_category')->result_array();
	foreach($sub_category1 as $sub_category2){$sub_category0[$sub_category2['sub_category_id']]=$sub_category2['sub_category_name'];}

	$cond=[];
	if($vendor!=""){$cond["store_id"]=$vendor;}
	if($category!=""){$cond["category"]=$category;}
	if($sub_category!=""){$cond["sub_category"]=$sub_category;}
	if($product!=""){$cond["product_id"]=$product;}
	$products0 = $this->db->get_where('product', $cond)->result_array();
	
	$cash_on_delivery0=array();
	$cash_on_delivery1 = $this->db->get('cash_on_delivery')->result_array();
	foreach($cash_on_delivery1 as $cash_on_delivery2){$cash_on_delivery0[$cash_on_delivery2['product_id']]=$cash_on_delivery2;}
	?>
	<?php /* if(count($products0)>0){ ?>
	<div style="text-align:right;">
		Select All<input class='aiz_switchery1' type="checkbox" data-id="select_all_sw1" />
	</div>
	<?php } */ ?>
	<table id="demo-table" class="table table-striped" style="width:100%;" data-pagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true">
		<tr>
			<th>S.No</th>
			<th><?php echo translate('store_name');?></th>
			<th><?php echo translate('category_name');?></th>
			<th><?php echo translate('sub_category_name');?></th>
			<th><?php echo translate('product_title');?></th>
			<th><?php echo translate('active_status');?></th>
		</tr>
		<?php
		$i1=1;
		foreach($products0 as $products1){				?>
		<tr>
			<td><?php echo $i1;$i1++; ?></td>
			<td><?php echo $store0[$products1['store_id']]; ?></td>
			<td><?php echo $category0[$products1['category']]; ?></td>
			<td><?php echo $sub_category0[$products1['sub_category']]; ?></td>
			<td><div style="width:200px;"><?php echo $products1['title']; ?></div></td>
			<td><input class='aiz_switchery' type="checkbox" data-id="<?php echo $products1['product_id']; ?>" data-tm="<?php echo $products1['title']."<br>".translate('cash_on_delivery_enabled'); ?>" data-fm="<?php echo $products1['title']."<br>".translate('cash_on_delivery_disabled'); ?>" <?php if($cash_on_delivery0[$products1['product_id']]['status'] == '1'){ ?>checked<?php } ?> /></td>
		</tr>
		<?php } ?>
	</table>
</div>
<Script>
  function set_switchery2(){
	$(".aiz_switchery1").each(function(){
		new Switchery($(this).get(0), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});

		var changeCheckbox = $(this).get(0);
		changeCheckbox.onchange = function() {
			var product_id=[];
			$(".aiz_switchery").each(function(){
				product_id.push($(this).data('id'));
			});
			$.ajax({url: base_url+'index.php/admin/cod/update',
			type: "POST",
			data:{'product_id':product_id,'ch_value':changeCheckbox.checked},
			success: function(result){
				location.reload();
			}});
		};
	});
}
  function set_switchery1(){
	$(".aiz_switchery").each(function(){
		new Switchery($(this).get(0), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});

		var comp=$(this);
		var changeCheckbox = $(this).get(0);
		var false_msg = $(this).data('fm');
		var true_msg = $(this).data('tm');
		changeCheckbox.onchange = function() {
			var product_id=comp.data('id');
			$.ajax({url: base_url+'index.php/admin/cod/edit',
			type: "POST",
			data:{'product_id':product_id,'ch_value':changeCheckbox.checked},
			success: function(result){
				if(changeCheckbox.checked == true){
					$.activeitNoty({
						type: 'success',
						icon : 'fa fa-check',
						message : true_msg,
						container : 'floating',
						timer : 3000
					});
					sound('published');
				} else {
					$.activeitNoty({
						type: 'danger',
						icon : 'fa fa-check',
						message : false_msg,
						container : 'floating',
						timer : 3000
					});
					sound('unpublished');
				}
			}});
		};
	});
}
$(document).ready(function(){
	set_switchery1();
	set_switchery2();
});
</Script>

