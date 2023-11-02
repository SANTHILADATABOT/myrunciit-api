
<aside class="col-md-3 sidebar" id="sidebar">
    <!-- widget shop categories -->
    <span class="btn btn-theme-transparent pull-left hidden-lg hidden-md" onClick="close_sidebar();" style="border-radius:50%; position: absolute; top:5px;">
        <i class="fa fa-times"></i>
    </span>
    <div class="widget shop-categories">
        <div class="widget-content">
            <ul>   
                <li class="title-for-list">
                    <span class="arrow search_cat search_cat_click all_category_set" style="display:none;" data-cat="0" 
                        data-min="<?php echo floor($this->crud_model->get_range_lvl('product_id !=', '0', "min")); ?>" 
                           data-max="<?php echo ceil($this->crud_model->get_range_lvl('product_id !=', '0', "max")); ?>" 
                            data-brands="<?php echo $this->db->get_where('general_settings',array('type'=>'data_all_brands'))->row()->value; ?>"
                                data-vendors="<?php echo $this->db->get_where('general_settings',array('type'=>'data_all_vendors'))->row()->value; ?>"
                           >
                                    <i class="fa fa-angle-down"></i>
                    </span>
                    <a href="#" class="search_cat" data-cat="0"
                        data-min="<?php echo floor($this->crud_model->get_range_lvl('product_id !=', '0', "min")); ?>" 
                           data-max="<?php echo ceil($this->crud_model->get_range_lvl('product_id !=', '0', "max")); ?>" >
                        <?php echo translate('all_products');?>
                    </a>
                </li>                                                 
                <?php
                    $vendorid = $this->session->userdata('vendorid');
                    if($vendorid=="")
                    {
                     $vendorid ='2';
                  
                    }
                 
                    $this->db->select('v.vendor_id,p.store_id,p.category,p.sub_category');
                    $this->db->from('vendor as v');
                    $this->db->join('product as p', 'p.store_id = v.vendor_id');
                    $this->db->where('v.vendor_id',$vendorid);
                    $val= $this->db->get()->result_array();
                    $cat_values = array();
                    foreach($val as $result)
                    {
                       $get_category =$result['category'];
                       if (!in_array($get_category, $cat_values)) {
                        $this->db->where('category_id',$get_category);
                       $all_category = $this->db->get('category')->result_array();
                    
                       foreach($all_category as $row)
                       {
                           if($this->crud_model->if_publishable_category($row['category_id'])){
                ?>
                <li>
                    <span class="arrow search_cat search_cat_click" data-cat="<?php echo $row['category_id']; ?>" 
                        data-min="<?php echo floor($this->crud_model->get_range_lvl('category', $row['category_id'], "min")); ?>" 
                           data-max="<?php echo ceil($this->crud_model->get_range_lvl('category', $row['category_id'], "max")); ?>" 
                            data-brands="<?php echo $row['data_brands']; ?>"
                                data-vendors="<?php echo $row['data_vendors']; ?>"
                           >
                                    <i class="fa fa-angle-down"></i>
                    </span>
                    <a href="#" class="search_cat" data-cat="<?php echo $row['category_id']; ?>"
                        data-min="<?php echo floor($this->crud_model->get_range_lvl('category', $row['category_id'], "min")); ?>" 
                            data-max="<?php echo ceil($this->crud_model->get_range_lvl('category', $row['category_id'], "max")); ?>" >
                        <?php echo $row['category_name']; ?>
                    </a>
                    <ul class="children">
                        <?php
                            $sub_category = $this->db->get_where('sub_category',array('category'=>$row['category_id']))->result_array();
                            foreach($sub_category as $row1)
                            {
                        ?>
                        <li class="on_click_search checkbox"
                            data-min="<?php echo floor($this->crud_model->get_range_lvl('sub_category', $row1['sub_category_id'], "min")); ?>" 
                                data-max="<?php echo ceil($this->crud_model->get_range_lvl('sub_category', $row1['sub_category_id'], "max")); ?>" >
                            <label for="sub_<?php echo $row1['sub_category_id']; ?>" onClick="check(this)" >
                                <input type="checkbox" name="jsut_show" id="sub_<?php echo $row1['sub_category_id']; ?>" class="search_sub" value="<?php echo $row1['sub_category_id']; ?>">
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                <?php echo $row1['sub_category_name']; ?>
                                <span class="count">
                                   <?php echo $this->crud_model->is_publishable_count('sub_category',$row1['sub_category_id']); ?>
                                </span>
                            </label>
                        </li>
                        <?php  
                            }
                        ?>
                    </ul>
                </li>
                <?php  
                           }
                    $cat_values[] = $get_category;

                }
            }
        }
                ?>
            </ul>
        </div>
    </div>
    <!-- /widget shop categories -->
    <!-- widget price filter -->
    <div class="widget widget-filter-price" style="display:none;">
        <h4 class="widget-title">
            <?php echo translate('price');?>
        </h4>
        <div class="widget-content">
            <div id="slider-range"></div>
            <input type="text" id="amount" style="width:100%;text-align:center;" disabled />
        </div>
    </div>
    <!-- /widget price filter -->
    <br>
    <div class="row hidden-sm hidden-xs">
    <?php
		echo $this->html_model->widget('special_products');
	?>
    </div>
</aside>

<input type="hidden" id="univ_max" value="<?php echo $this->crud_model->get_range_lvl('product_id !=', '', "max"); ?>">
<input type="hidden" id="cur_cat" value="0">
<?php include 'search_script.php'; ?>
