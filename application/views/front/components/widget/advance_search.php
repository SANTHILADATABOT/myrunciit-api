<div class="col-md-12">
	<div style="background:#fff; padding:15px;">
        <h4 class="section-title">
            <?php echo translate('advance_search'); ?>
        </h4>
        <?php
            echo form_open(base_url() . 'index.php/home/home_search/text', array(
                'class' => 'sky-form',
                'method' => 'post',
                'enctype' => 'multipart/form-data',
                'style' => 'border:none !important;'
            ));
        ?>
        <input type="hidden" name="type" value="<?php echo $product_type; ?>" id="others_product_type" />
        <input type="hidden" name="min_get" value="<?php echo $min; ?>" id="min_get" />
       <input type="hidden" name="max_get" value="<?php echo $max; ?>" id="max_get" />
            <div class="row">
                <div class="col-md-12">
                    <select class="selectpicker" data-live-search="true" name="category" data-toggle="tooltip" title="<?php echo translate('select');?>" onChange="set_search_by_cat(this);">
                        <option value="0"  data-cat="0" 
                            data-min="<?php echo ($this->crud_model->get_range_lvl('product_id !=', '0', "min")); ?>" 
                               data-max="<?php echo ($this->crud_model->get_range_lvl('product_id !=', '0', "max")); ?>" 
                                data-brands="<?php echo $this->db->get_where('general_settings',array('type'=>'data_all_brands'))->row()->value; ?>" 
                                    data-subdets='[]'>
                                    <?php echo translate('all_categories');?>
                        </option>
                        <?php 
                            $vendorid = $this->session->userdata('vendorid');
                            if($vendorid=="")
                            {
                             $vendorid ='2';
                         
                            }
                            // echo "id=".$vendorid;
                              $this->db->select('v.vendor_id,p.store_id,p.category');
                              $this->db->from('vendor as v');
                              $this->db->join('product as p', 'p.store_id = v.vendor_id');
                              $this->db->where('v.vendor_id',$vendorid);
                              $this->db->where('p.status','ok');
                              if($product_type=="todays_deal"){
                              $this->db->where('p.deal','ok');
                              }
                              $val= $this->db->get()->result_array();
                              $cat_values = array();
                              foreach($val as $result)
                              {
                                 $get_category =$result['category'];
                                 if (!in_array($get_category, $cat_values)) {
                                  $this->db->where('category_id',$get_category);
                                  $all_category = $this->db->get('category')->result_array();
                            foreach ($all_category as $row1) {
								if($this->crud_model->if_publishable_category($row1['category_id'])){
                        ?>
                        <option <?php if ($category == $row1['category_id']) {
														echo 'selected="selected"';
													} ?> value="<?php echo $row1['category_id']; ?>" 
                            data-cat="<?php echo $row1['category_id']; ?>" 
                                data-min="<?php echo round($this->crud_model->get_range_lvl('category', $row1['category_id'], "min")); ?>" 
                                   data-max="<?php echo round($this->crud_model->get_range_lvl('category', $row1['category_id'], "max")); ?>" 
                                    data-brands="<?php echo $row1['data_brands']; ?>" 
                                        data-subdets='<?php echo $row1['data_subdets']; ?>'>
                                            <?php echo $row1['category_name']; ?>
                        </option>
                        <?php 
								}
                            $cat_values[] = $get_category;
                        }
                      }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-12 search_sub">
                    <select class="selectpicker header-search-select" data-live-search="true" name="sub_category" data-toggle="tooltip" title="<?php echo translate('select');?>">
                        <option value="0" ><?php echo translate('all_sub_categories');?></option>
                        <?php if($category!=""){
                            $this->db->where("category",$category);
                            $sub_category_0 = $this->db->get('sub_category')->result_array();
                            //$product_0 = $this->db->get('product')->result_array();
                        foreach($sub_category_0 as $sub_category_1){ ?>
                        <option value="<?php echo $sub_category_1['sub_category_id']; ?>" <?php if($sub_category_1['sub_category_id']==$sub_category){echo " selected";} ?>><?php echo $sub_category_1['sub_category_name']; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
                <?php
                	if ($this->crud_model->get_type_name_by_id('general_settings','68','value') == 'ok') {
				?>
                <div class="col-md-12 search_brands">
                    <select class="selectpicker header-search-select" data-live-search="true" name="brand" data-toggle="tooltip" title="<?php echo translate('select');?>">
                        <option value="0" ><?php echo translate('all_brands');?></option>
                        <?php if($brand!=""){
                            $this->db->where("brand_id",$brand);
                            $brand_0 = $this->db->get('brand')->result_array();
                            //$product_0 = $this->db->get('product')->result_array();
                        foreach($brand_0 as $brand_1){ ?>
                        <option value="<?php echo $brand_1['brand_id']; ?>" <?php if($brand_1['brand_id']==$brand){echo " selected";} ?>><?php echo $brand_1['name']; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
                <?php
					}
				?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget widget-filter-price" style="padding: 2px 20px;height: 40px;">                
                        <div id="slider-range" style="width:85%;"></div>
                        <input type="text" style="position: absolute; width:75%; text-align:center;margin-top: -30px;border: none;" id="amount" disabled />
                        <input type="hidden" name="price" id="rangeaa" />
                        <input type="hidden" id="univ_max" value="<?php echo $this->crud_model->get_range_lvl('product_id !=', '', "max"); ?>">
                    </div>
                </div>
                <div class="col-md-12">
                <input class="form-control" type="text" name="query" placeholder="<?php echo translate('what_are_you_looking_for'); ?>?" value="<?php echo $query;?>">
                </div>
                <div class="col-md-12">
                    <button class="btn btn-theme btn-block" style="padding:10px 20px;">
                        <span class="fa fa-search" aria-hidden="true"></span>
                        <span class=""> <?php echo translate('search'); ?> </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function set_search_by_cat(now){
        var cat 		= $(now).data('cat');
        var min 		= Math.floor($(now).find(':selected').data('min'));
        var max 		= Number($(now).find(':selected').data('max'));
        var brands 		= $(now).find(':selected').data('brands');
        var subdets 	= $(now).find(':selected').data('subdets');
                                
        brands = brands.split(';;;;;;');
        var select_brand_options = '';
        for(var i=0, len=brands.length; i < len; i++){
            brand = brands[i].split(':::');
            if(brand.length == 2){		
                select_brand_options = select_brand_options
                                       +'        <option value="'+brand[0]+'" >'+brand[1]+'</option>'
            }
        }
        
        var select_brand_html =  '<select class="selectpicker input-price " name="brand" data-live-search="true" '
                                +'	data-width="100%" data-toggle="tooltip" title="Select" >'
                                +'		<option value="0"><?php echo translate('all_brands'); ?></option>'
                                +		select_brand_options
                                +'</select>';
        $('.search_brands').html(select_brand_html);
        
        
        var select_sub_options = '';
        $.each(subdets, function (i, v) {
            var min = v.min;
            var max = v.max;
            var brands = v.brands;
            var sub_id = v.sub_id;
            var sub_name = v.sub_name;	
			select_sub_options = select_sub_options
								   +'        <option value="'+sub_id+'" data-subcat="'+sub_id+'"  data-min="'+min+'"  data-max="'+max+'" data-brands="'+brands+'" >'+sub_name+'</option>';
        });
        
        var select_sub_html =  '<select class="selectpicker input-price " name="sub_category" data-live-search="true" '
                                +'	data-width="100%" data-toggle="tooltip" title="Select" onchange="set_search_by_scat(this)" >'
                                +'		<option value="0"><?php echo translate('all_sub_categories'); ?></option>'
                                +		select_sub_options
                                +'</select>';
        $('.search_sub').html(select_sub_html);
        
        $('.selectpicker').selectpicker();
        set_price_slider(min,max,min,max);
        
    }
    
    
    function set_search_by_scat(now){
        var scat 		= $(now).data('subcat');
        var min 		= Math.floor($(now).find(':selected').data('min'));
        var max 		= Number($(now).find(':selected').data('max'));
        var brands 		= $(now).find(':selected').data('brands');						
        
        brands = brands.split(';;;;;;');
        var select_brand_options = '';
        for(var i=0, len=brands.length; i < len; i++){
            brand = brands[i].split(':::');
            if(brand.length == 2){		
                select_brand_options = select_brand_options
                                       +'        <option value="'+brand[0]+'" >'+brand[1]+'</option>'
            }
        }
        
        var select_brand_html =  '<select class="selectpicker input-price " name="brand" data-live-search="true" '
                                +'	data-width="100%" data-toggle="tooltip" title="Select" >'
                                +'		<option value="0"><?php echo translate('all_brands'); ?></option>'
                                +		select_brand_options
                                +'</select>';
        $('.search_brands').html(select_brand_html);
        
        $('.selectpicker').selectpicker();
        set_price_slider(min,max,min,max);
        
    }
    
    function set_price_slider(min,max,univ_min,univ_max){ 
        var priceSliderRange = $('#slider-range');
        if ($.ui) {
            /**/
            if ($(priceSliderRange).length) {
                $(priceSliderRange).slider({
                    range: true,
                    min: univ_min,
                    max: univ_max,
                    values: [min, max],
                    slide: function (event, ui){
                        $("#amount").val(currency + (Number(ui.values[0])*exchange) + " - " + currency + (Number(ui.values[1])*exchange));
                        $("#rangeaa").val(ui.values[0] + ";" + ui.values[1]);
                    },
                    stop: function( event, ui ) {
                        do_product_search();
                    }
                });
                $("#amount").val(
                    currency + Number($("#slider-range").slider("values", 0))*exchange + " - " + currency + Number($("#slider-range").slider("values", 1))*exchange
                );
                $("#rangeaa").val($("#slider-range").slider("values", 0) + ";" + $("#slider-range").slider("values", 1));
            }
            
        }
    }
    
    $(document).ready(function(e) {
        var univ_max = $('#univ_max').val(); 
        var min =  $('#min_get').val();
       var max = $('#max_get').val();

        if(min !="" && max != "")
        {
       
         set_price_slider(min,max,0,univ_max);
        }
        else{
        
           set_price_slider(0,univ_max,0,univ_max);
       }
        // set_price_slider(0,univ_max,0,univ_max);
        setTimeout(function(){ $('.selectpicker').selectpicker(); }, 3000);
    });
</script>