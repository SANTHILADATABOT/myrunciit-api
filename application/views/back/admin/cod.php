<div id="content-container">
<div class="content-wrapper-before"></div>

	<div class="tab-base ">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content ">
					<div class="row">
					  <div class="col-md-12">
					      <h1 class="page-header text-overflow" ><?php echo translate('manage_cash_on_delivery');?></h1>
					  </div>
					</div>
				
				<?php
					echo form_open(base_url() . 'index.php/admin/cod/' , array(
						'class' => 'form-horizontal',
						'method' => 'post'
					));
					$vendor_0 = $this->db->get('vendor')->result_array();
					$category_0 = $this->db->get('category')->result_array();
					$sub_category_0 = $this->db->get('sub_category')->result_array();
					$product_0 = $this->db->get('product')->result_array();
					$filter_01=[];$filter_02=[];$vendor_01=[];$category_01=[];$sub_category_01=[];$product_01=[];
				?>
					<div style="padding: 25px 5px 5px 5px;" class="row">
						<div class="col-md-2">
							<label>Store Name</label>
							<select class="demo-chosen-select form-control2" id="vendor" name="vendor" onchange="set_by_vendor(this.value)">
								<option value="">Choose one</option>
								<?php foreach($vendor_0 as $vendor_1){
									$vendor_01[$vendor_1['vendor_id']]=$vendor_1['name'];
									?>
								<option value="<?php echo $vendor_1['vendor_id']; ?>" <?php if($vendor_1['vendor_id']==$vendor){echo " selected";} ?>><?php echo $vendor_1['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-2">
							<label>Category Name</label>
							<select class="demo-chosen-select form-control2" id="category" name="category" onchange="set_by_category(vendor.value,this.value)">
								<option value="">Choose one</option>
								<?php foreach($category_0 as $category_1){
									$category_01[$category_1['category_id']]=$category_1['category_name'];
									?>
								<option value="<?php echo $category_1['category_id']; ?>" <?php if($category_1['category_id']==$category){echo " selected";} ?>><?php echo $category_1['category_name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-2">
							<label>Sub Category Name</label>
							<select class="demo-chosen-select form-control2" id="sub_category" name="sub_category" onchange="set_by_sub_category(vendor.value,category.value,this.value)">
								<option value="">Choose one</option>
								<?php foreach($sub_category_0 as $sub_category_1){
									$sub_category_01[$sub_category_1['sub_category_id']]=$sub_category_1['sub_category_name'];
									?>
								<option value="<?php echo $sub_category_1['sub_category_id']; ?>" <?php if($sub_category_1['sub_category_id']==$sub_category){echo " selected";} ?>><?php echo $sub_category_1['sub_category_name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-2">
							<label>Product Name</label>
							<select class="demo-chosen-select form-control2" id="product" name="product">
								<option value="">Choose one</option>
								<?php foreach($product_0 as $product_1){
									$filter_01[$product_1['store_id']][]=$product_1['product_id'];
									$filter_02[$product_1['category']][$product_1['sub_category']][]=$product_1['product_id'];
									$product_01[$product_1['product_id']]=$product_1['title'];
									?>
								<option value="<?php echo $product_1['product_id']; ?>" <?php if($product_1['product_id']==$product){echo " selected";} ?>><?php echo $product_1['title']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-2">
							<button type="submit" class="btn btn-success" id="filter_btn" style="margin-top:26px;">Filter</button>
							&nbsp;<button type="button" class="btn btn-success" onclick="refresh_filter()" style="margin-top:26px;">Refresh</button>
						</div>
					</div>
				</form>
					<br>
                    <div class="tab-pane fade active in" 
                    	id="list" style="border:1px solid #ebebeb; border-radius:4px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	const filter_01=<?php echo json_encode($filter_01); ?>;
	const filter_02=<?php echo json_encode($filter_02); ?>;
	const vendor_01=<?php echo json_encode($vendor_01); ?>;
	const category_01=<?php echo json_encode($category_01); ?>;
	const sub_category_01=<?php echo json_encode($sub_category_01); ?>;
	const product_01=<?php echo json_encode($product_01); ?>;
	function set_by_vendor(vendor){
		var product1="<option value=''>Choose one</option>";
		var category1="<option value=''>Choose one</option>";
		var sub_category1="<option value=''>Choose one</option>";
		if(vendor!=""){
			var vendor1=filter_01[vendor];
			var product1_0=[];
			for(let i1=0;i1<vendor1.length;i1++){
				var product1_1=vendor1[i1];
				product1+="<option value='"+product1_1+"'>"+product_01[product1_1]+"</option>";
				product1_0.push(product1_1);
			}
			$.each(filter_02, function(key1, value1) {
				var ch_avail=false;
				$.each(value1, function(key2, value2) {
					for(let i2=0;i2<value2.length;i2++){
						if(product1_0.includes(value2[i2])){
							sub_category1+="<option value='"+key2+"'>"+sub_category_01[key2]+"</option>";
							ch_avail=true;break;
						}
					}
				});
				if(ch_avail){category1+="<option value='"+key1+"'>"+category_01[key1]+"</option>";}
			});
		}else{
			$.each(product_01, function(key, value) {
				product1+="<option value='"+key+"'>"+value+"</option>";
			});
			$.each(category_01, function(key, value) {
				category1+="<option value='"+key+"'>"+value+"</option>";
			});
			$.each(sub_category_01, function(key, value) {
				sub_category1+="<option value='"+key+"'>"+value+"</option>";
			});
		}
		$("#product").html(product1);
		$("#category").html(category1);
		$("#sub_category").html(sub_category1);
	}
	function set_by_category(vendor,category){
		var product1="<option value=''>Choose one</option>";
		var sub_category1="<option value=''>Choose one</option>";
		if(category!=""){
			var product1_0=[];
			$.each(filter_02[category], function(key2, value2) {
				sub_category1+="<option value='"+key2+"'>"+sub_category_01[key2]+"</option>";
				for(let i2=0;i2<value2.length;i2++){
					if(!product1_0.includes(value2[i2])){
						product1+="<option value='"+value2[i2]+"'>"+product_01[value2[i2]]+"</option>";
						product1_0.push(value2[i2]);
					}
				}
			});
		}else{
			set_by_vendor(vendor);
			return;
		}
		$("#product").html(product1);
		$("#sub_category").html(sub_category1);
	}
	function set_by_sub_category(vendor,category,sub_category){
		var product1="<option value=''>Choose one</option>";
		if(sub_category!=""){
			var product1_0=[];
			$.each(filter_02, function(key1, value1) {
				$.each(value1, function(key2, value2) {
					if(key2==sub_category){
						for(let i2=0;i2<value2.length;i2++){
							if(!product1_0.includes(value2[i2])){
								product1+="<option value='"+value2[i2]+"'>"+product_01[value2[i2]]+"</option>";
								product1_0.push(value2[i2]);
							}
						}
					}
				});
			});
		}else{
			set_by_category(vendor,category);
			return;
		}
		$("#product").html(product1);
	}
	function refresh_filter(){
		$("#vendor").val("");
		$("#category").val("");
		$("#sub_category").val("");
		$("#product").val("");
		$('#filter_btn').click();
	}
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'cod';
	var list_cont_func = 'list/<?php echo $vendor; ?>/<?php echo $category; ?>/<?php echo $sub_category; ?>/<?php echo $product; ?>';
	var dlt_cont_func = 'delete';
</script>

