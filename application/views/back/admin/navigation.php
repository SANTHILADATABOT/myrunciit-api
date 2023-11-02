<?php
$physical_check = $this->crud_model->get_type_name_by_id('general_settings', '68', 'value');
$digital_check = $this->crud_model->get_type_name_by_id('general_settings', '69', 'value');
$bundle_check = $this->crud_model->get_type_name_by_id('general_settings', '82', 'value');
$customer_product_check = $this->crud_model->get_type_name_by_id('general_settings', '83', 'value');
?>
<nav id="mainnav-container">
    <div id="mainnav">
        <!--Menu-->
        <div id="mainnav-menu-wrap">
            <div class="nano">
                <div class="nano-content" style="overflow-x:auto;">
                    <ul id="mainnav-menu" class="list-group">
                        <!--Category name-->
                        <li class="list-header"></li>
                        <li class="list-header1">
                            <a id="main-title-icon-div" href="<?php echo base_url(); ?>index.php/<?php echo $this->session->userdata('title'); ?>">
                                <img id="main-title-icon1" src="<?php echo $this->crud_model->logo('admin_login_logo'); ?>" alt="<?php echo $system_name; ?>" class="brand-icon">
                                <?php $ext =  $this->db->get_where('ui_settings',array('type' => 'fav_ext'))->row()->value;?>
                                <img id="main-title-icon2" src="<?php echo base_url(); ?>uploads/others/favicon.<?php echo $ext; ?>" class="brand-icon" style="display:none;">
                                <div class="brand-title" style="display:none;">
                                    <span class="brand-text"><?php echo $system_name; ?></span>
                                </div>
                            </a>
                        </li>
                        <!--Menu list item-->
                        <?php if($view_rights["1"]["0"]=="1"){ ?>
                        <li <?php if ($page_name == "dashboard") { ?> class="active-link" <?php } ?> style="border-top:1px solid rgba(69, 74, 84, 0.7);padding-top:30px">
                            <a href="<?php echo base_url(); ?>index.php/admin/">
                                <i class="fa fa-tachometer"></i>
                                <span class="menu-title">
                                    <?php echo translate('dashboard'); ?>
                                </span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php
                        
                        if ($physical_check == 'ok' && $digital_check == 'ok') {
                            if ((
                                $this->crud_model->admin_permission('category') ||
                                $this->crud_model->admin_permission('sub_category') ||
                                $this->crud_model->admin_permission('brand') ||
                                $this->crud_model->admin_permission('product') ||
                                $this->crud_model->admin_permission('stock') ||
                                $this->crud_model->admin_permission('product_bundle') ||
                                $this->crud_model->admin_permission('category_digital') ||
                                $this->crud_model->admin_permission('sub_category_digital') ||
                                $this->crud_model->admin_permission('digital')
                            ) && (($view_rights["2"]["1"]=="1") || ($view_rights["2"]["2"]=="1") || ($view_rights["2"]["3"]=="1") || ($view_rights["3"]["4"]=="1") || ($view_rights["3"]["5"]=="1") || ($view_rights["3"]["6"]=="1"))) {
                        ?>
						
						<h6 style="padding:12px 0px 12px 15px;font-weight:700;font-family: Be Vietnam,sans-serif;color:#044484">MANAGE</h6>
						
                                <li <?php if (
                                        $page_name == "category" ||
                                        $page_name == "sub_category" ||
                                        $page_name == "product" ||
                                        $page_name == "stock" ||
                                        $page_name == "product_bundle" ||
                                        $page_name == "category_digital" ||
                                        $page_name == "sub_category_digital" ||
                                        $page_name == "digital" || $page_name === "customer_products"
                                    ) { ?> class="active-sub" <?php } ?>>
                                    <a href="#">
                                        <!-- <i class="fa fa-shopping-cart"></i> -->
                                        <i class="fa fa-sliders" aria-hidden="true"></i>
                                        <span class="menu-title">
                                            <?php echo translate('products'); ?>
                                        </span>
                                        <i class="fa arrow"></i>
                                    </a>

                                    <!-- VB PRODUCTS START -->
                                    <ul class="collapse <?php if (
                                                        $page_name == "categories" ||
                                                        $page_name == "product_stock" 
                                                    ) { ?>
                                                                 in
                                                                    <?php } ?>">
                                    <?php
                                                    if (($this->crud_model->admin_permission('categories')) && (($view_rights["2"]["1"]=="1") || ($view_rights["2"]["2"]=="1") || ($view_rights["2"]["3"]=="1"))) {
                                                    ?>
                                                        <li <?php if ($page_name == "categories") { ?> class="active-link" <?php } ?>>
                                                            <a href="<?php echo base_url(); ?>index.php/admin/categories">
                                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                                <i class="fa fa-sitemap" aria-hidden="true"></i>
                                                                <?php echo translate('categories'); ?>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                    if (($this->crud_model->admin_permission('product_stock')) && (($view_rights["3"]["4"]=="1") || ($view_rights["3"]["5"]=="1") || ($view_rights["3"]["6"]=="1"))) {
                                                    ?>
                                                        <li <?php if ($page_name == "product_stock") { ?> class="active-link" <?php } ?>>
                                                            <a href="<?php echo base_url(); ?>index.php/admin/product_stock">
                                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                                <!-- <i class="fa fa-linode" aria-hidden="true"></i> -->
                                                                <!-- <i class="fa fa-wpforms" aria-hidden="true"></i> -->
                                                                <i class="fa fa-indent" aria-hidden="true"></i>
                                                                <?php echo translate('product_stock'); ?>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                    ?>
                                                    </ul>

                                    <!-- VB PRODUCTS END -->

                                    <!--PRODUCT------------------>
                                    <ul class="collapse <?php if (
                                                            $page_name == "category" ||
                                                            $page_name == "sub_category" ||
                                                            $page_name == "product" ||
                                                            $page_name == "product_bulk_upload" ||
                                                            $page_name == "brand" ||
                                                            $page_name == "stock" ||
                                                            $page_name == "product_bundle" ||
                                                            $page_name == "category_digital" ||
                                                            $page_name == "sub_category_digital" ||

                                                            $page_name == "digital" || $page_name === "customer_products"
                                                        ) { ?>
                                                                             		in
                                                                                		<?php } ?> >">
                                        <?php
                                        if (
                                            $this->crud_model->admin_permission('category') ||
                                            $this->crud_model->admin_permission('sub_category') ||
                                            $this->crud_model->admin_permission('brand') ||
                                            $this->crud_model->admin_permission('product') ||
                                            $this->crud_model->admin_permission('product_bulk_upload') ||
                                            $this->crud_model->admin_permission('stock') ||
                                            $this->crud_model->admin_permission('product_bundle')
                                        ) {
                                        ?>
                                            <!--Menu list item-->
                                            <li <?php if (
                                                    $page_name == "category" ||
                                                    $page_name == "sub_category" ||
                                                    $page_name == "brand" ||
                                                    $page_name == "product" ||
                                                    $page_name == "product_bulk_upload" ||
                                                    $page_name == "stock" ||
                                                    $page_name == "product_bundle"
                                                ) { ?> class="active-sub" <?php } ?>>
                                   
                                                <!--PRODUCT------------------>
                                                <ul class="collapse <?php if (
                                                                        $page_name == "category" ||
                                                                        $page_name == "sub_category" ||
                                                                        $page_name == "product" ||
                                                                        $page_name == "product_bulk_upload" ||
                                                                        $page_name == "brand" ||
                                                                        $page_name == "stock" ||
                                                                        $page_name == "product_bundle"
                                                                    ) { ?>
                                                                                     in
                                                                                        <?php } ?> ">                                                   
                                                    <?php                                                    
                                                    if ($this->crud_model->admin_permission('product_bundle') && $bundle_check == 'ok') {
                                                    ?>
                                                        <li style="display:none;" <?php if ($page_name == "product_bundle") { ?> class="active-link" <?php } ?>>
                                                            <a href="<?php echo base_url(); ?>index.php/admin/product_bundle">
                                                                <i class="fa fa-circle fs_i"></i>
                                                                <?php echo translate('product_bundle'); ?>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </li>

                                        <?php
                                        }
                                        ?>

                                        <?php
                                        if (
                                            $this->crud_model->admin_permission('category_digital') ||
                                            $this->crud_model->admin_permission('sub_category_digital') ||
                                            $this->crud_model->admin_permission('digital')
                                        ) {
                                        ?>
                                            <!--Menu list item-->
                                            <li style="display:none;" <?php if (
                                                                            $page_name == "category_digital" ||
                                                                            $page_name == "sub_category_digital" ||

                                                                            $page_name == "digital"
                                                                        ) { ?> class="active-sub" <?php } ?>>
                                                <a href="#">
                                                    <i class="fa fa-list-ul"></i>
                                                    <span class="menu-title">
                                                        <?php echo translate('digital_products'); ?>
                                                    </span>
                                                    <i class="fa arrow"></i>
                                                </a>
                                                <!--digital------------------>
                                                <ul class="collapse <?php if (
                                                                        $page_name == "category_digital" ||
                                                                        $page_name == "sub_category_digital" ||

                                                                        $page_name == "digital"
                                                                    ) { ?>
                                                                                     in
                                                                                        <?php } ?> >">

                                                    <?php
                                                    if ($this->crud_model->admin_permission('category')) {
                                                    ?>
                                                        <li <?php if ($page_name == "category_digital") { ?> class="active-link" <?php } ?>>
                                                            <a href="<?php echo base_url(); ?>index.php/admin/category_digital">
                                                                <i class="fa fa-circle fs_i"></i>
                                                                <?php echo translate('category'); ?>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                    if ($this->crud_model->admin_permission('sub_category')) {
                                                    ?>
                                                        <li <?php if ($page_name == "sub_category_digital") { ?> class="active-link" <?php } ?>>
                                                            <a href="<?php echo base_url(); ?>index.php/admin/sub_category_digital">
                                                                <i class="fa fa-circle fs_i"></i>
                                                                <?php echo translate('sub-category'); ?>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                    if ($this->crud_model->admin_permission('digital')) {
                                                    ?>
                                                        <li <?php if ($page_name == "digital") { ?> class="active-link" <?php } ?>>
                                                            <a href="<?php echo base_url(); ?>index.php/admin/digital">
                                                                <i class="fa fa-circle fs_i"></i>
                                                                <?php echo translate('all_digitals'); ?>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    } ?>
                                                </ul>
                                            </li>

                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if ($this->crud_model->admin_permission('customer_products') && $customer_product_check == 'ok') {
                                        ?>
                                            <li style="display:none;" <?php if ($page_name == "customer_products") { ?> class="active-link" <?php } ?>>
                                                <a href="<?php echo base_url(); ?>index.php/admin/customer_products/">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <span class="menu-title">
                                                        <?php echo translate('classified_products'); ?>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </li>
                        <?php
                            }
                        }
                        ?>



                        <?php
                        if ($physical_check == 'ok' && $digital_check !== 'ok') {
                            if (
                                $this->crud_model->admin_permission('category') ||
                                $this->crud_model->admin_permission('sub_category') ||
                                $this->crud_model->admin_permission('brand') ||
                                $this->crud_model->admin_permission('product') ||
                                $this->crud_model->admin_permission('stock')
                            ) {
                        ?>
                                <!--Menu list item-->
                                <li <?php if (
                                        $page_name == "category" ||
                                        $page_name == "sub_category" ||
                                        $page_name == "brand" ||
                                        $page_name == "product" ||
                                        $page_name == "stock"
                                    ) { ?> class="active-sub" <?php } ?>>
                                    <a href="#">
                                        <i class="fa fa-list"></i>
                                        <span class="menu-title">
                                            <?php echo translate('products'); ?>
                                        </span>
                                        <i class="fa arrow"></i>
                                    </a>

                                    <!--PRODUCT------------------>
                                    <ul class="collapse <?php if (
                                                            $page_name == "category" ||
                                                            $page_name == "sub_category" ||
                                                            $page_name == "product" ||
                                                            $page_name == "brand" ||
                                                            $page_name == "stock"
                                                        ) { ?>
																				 in
																					<?php } ?> ">

                                        <?php
                                        if ($this->crud_model->admin_permission('category')) {
                                        ?>
                                            <li <?php if ($page_name == "category") { ?> class="active-link" <?php } ?>>
                                                <a href="<?php echo base_url(); ?>index.php/admin/category">
                                                    <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('category'); ?>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        if ($this->crud_model->admin_permission('brand')) {
                                        ?>
                                            <li <?php if ($page_name == "brand") { ?> class="active-link" <?php } ?>>
                                                <a href="<?php echo base_url(); ?>index.php/admin/brand">
                                                    <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('brands'); ?>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        if ($this->crud_model->admin_permission('sub_category')) {
                                        ?>
                                            <li <?php if ($page_name == "sub_category") { ?> class="active-link" <?php } ?>>
                                                <a href="<?php echo base_url(); ?>index.php/admin/sub_category">
                                                    <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('sub-category'); ?>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        if ($this->crud_model->admin_permission('product')) {
                                        ?>
                                            <li <?php if ($page_name == "product") { ?> class="active-link" <?php } ?>>
                                                <a href="<?php echo base_url(); ?>index.php/admin/product">
                                                    <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('all_products'); ?>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        if ($this->crud_model->admin_permission('stock')) {
                                        ?>
                                            <li <?php if ($page_name == "stock") { ?> class="active-link" <?php } ?>>
                                                <a href="<?php echo base_url(); ?>index.php/admin/stock">
                                                    <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('product_stock'); ?>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </li>

                        <?php
                            }
                        }
                        ?>
                        <?php
                        if ($physical_check !== 'ok' && $digital_check == 'ok') {
                            if (
                                $this->crud_model->admin_permission('category_digital') ||
                                $this->crud_model->admin_permission('sub_category_digital') ||
                                $this->crud_model->admin_permission('digital')
                            ) {
                        ?>
                                <!--Menu list item-->
                                <li <?php if (
                                        $page_name == "category_digital" ||
                                        $page_name == "sub_category_digital" ||
                                        $page_name == "digital"
                                    ) { ?> class="active-sub" <?php } ?>>
                                    <a href="#">
                                        <i class="fa fa-list-ul"></i>
                                        <span class="menu-title">
                                            <?php echo translate('products'); ?>
                                        </span>
                                        <i class="fa arrow"></i>
                                    </a>
                                    <!--digital------------------>
                                    <ul class="collapse <?php if (
                                                            $page_name == "category_digital" ||
                                                            $page_name == "sub_category_digital" ||
                                                            $page_name == "digital"
                                                        ) { ?>
																				 in
																					<?php } ?> >">

                                        <?php
                                        if ($this->crud_model->admin_permission('category')) {
                                        ?>
                                            <li <?php if ($page_name == "category_digital") { ?> class="active-link" <?php } ?>>
                                                <a href="<?php echo base_url(); ?>index.php/admin/category_digital">
                                                    <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('category'); ?>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        if ($this->crud_model->admin_permission('sub_category')) {
                                        ?>
                                            <li <?php if ($page_name == "sub_category_digital") { ?> class="active-link" <?php } ?>>
                                                <a href="<?php echo base_url(); ?>index.php/admin/sub_category_digital">
                                                    <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('sub-category'); ?>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        if ($this->crud_model->admin_permission('digital')) {
                                        ?>
                                            <li <?php if ($page_name == "digital") { ?> class="active-link" <?php } ?>>
                                                <a href="<?php echo base_url(); ?>index.php/admin/digital">
                                                    <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('all_products'); ?>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </li>

                        <?php
                            }
                        }
                        ?>
                                                                    <!-- VB SALES START -->
                        <?php if(($view_rights["4"]["7"]=="1") || ($view_rights["4"]["8"]=="1") || ($view_rights["4"]["9"]=="1") || ($view_rights["5"]["0"]=="1") || ($view_rights["6"]["0"]=="1") || ($view_rights["7"]["0"]=="1")){ ?>
                        <li <?php if (
                                        $page_name == "return_sales" ||
                                        $page_name == "cancel_sales" ||
                                        $page_name == "sales"
                                    ) { ?> class="active-sub" <?php } ?>>
                                    <a href="#">
                                        <i class="fa fa-shopping-cart"></i>
                                        <span class="menu-title">
                                            <?php echo translate('sales'); ?>
                                        </span>
                                        <i class="fa arrow"></i>
                                    </a>

                                    <!-- VB PRODUCTS START -->
                                    <ul class="collapse <?php if (
                                                        $page_name == "return_sales" ||
                                                        $page_name == "cancel_sales" ||
                                                        $page_name == "sales"
                                                    ) { ?>
                                                                 in
                                                                    <?php } ?>">
                                    <?php
                                                    if (($this->crud_model->admin_permission('orders')) && (($view_rights["4"]["7"]=="1") || ($view_rights["4"]["8"]=="1") || ($view_rights["4"]["9"]=="1"))) {
                                                    ?>
                                                        <li <?php if ($page_name == "orders") { ?> class="active-link" <?php } ?>>
                                                            <a href="<?php echo base_url(); ?>index.php/admin/orders">
                                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                                <!-- <i class="fa fa-shopping-bag" aria-hidden="true"></i> -->
                                                                <!-- <i class="fa fa-shopping-basket" aria-hidden="true"></i> -->
                                                                <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
                                                                <?php echo translate('orders'); ?>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                    if (($this->crud_model->admin_permission('sales')) && ($view_rights["5"]["0"]=="1")) {
                                                    ?>
                                                        <li <?php if ($page_name == "sales") { ?> class="active-link" <?php } ?>>
                                                            <a href="<?php echo base_url(); ?>index.php/admin/sales">
                                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                                <i class="fa fa-balance-scale" aria-hidden="true"></i>
                                                                <?php echo translate('manage_sales'); ?>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                    if (($this->crud_model->admin_permission('coupon')) && ($view_rights["6"]["0"]=="1")) {
                                                    ?>
                                                        <li <?php if ($page_name == "coupon") { ?> class="active-link" <?php } ?>>
                                                            <a href="<?php echo base_url(); ?>index.php/admin/coupon/">
                                                                <i class="fa fa-tag"></i>
                                                                <span class="menu-title">
                                                                    <?php echo translate('discount_coupon'); ?>
                                                                </span>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                    if($view_rights["7"]["0"]=="1"){
                                                    ?>
                                                    <li <?php if ($page_name == "customer_report") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/customer_report/">
                                            <!-- <i class="fa fa-circle fs_i"></i> -->
                                            <i class="fa fa-server" aria-hidden="true"></i>
                                            <?php echo translate('Customers order summary'); ?>
                                        </a>
                                    </li>
                                                    <?php } ?>
                                                    </ul>

                                    <!-- VB PRODUCTS END -->
                      
                                </li>
                        <?php } ?>

                                    <!-- VB SALES END -->

                        <!--ORDER-------------------->
                        <?php
                        if ($this->crud_model->admin_permission('order')) {
                        ?>
                            <!-- <li <?php if (
                                    $page_name == "return_sales" ||
                                    $page_name == "cancel_sales" 
                                ) { ?> class="active-sub" <?php } ?>>
                                <a href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="menu-title">
                                        <?php echo translate('orders'); ?>
                                    </span>
                                    <i class="fa arrow"></i>
                                </a>

                                <ul class="collapse <?php if (
                                                        $page_name == "return_sales" ||
                                                        $page_name == "cancel_sales" 
                                                    ) { ?>
                                                                 in
                                                                    <?php } ?>">
                                 
                                    <li <?php if ($page_name == "cancel_sales") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/cancel_sales">
                                            <i class="fa fa-usd"></i>
                                            <span class="menu-title">
                                                <?php echo translate('rejected_order'); ?>
                                            </span>
                                        </a>
                                    </li>
                                    <li <?php if ($page_name == "user_cancel_sales") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/user_cancel_sales">
                                            <i class="fa fa-usd"></i>
                                            <span class="menu-title">
                                                <?php echo translate('user_cancelled_order'); ?>
                                            </span>
                                        </a>
                                    </li>
                                    <li <?php if ($page_name == "failed_sales") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/failed_sales">
                                            <i class="fa fa-usd"></i>
                                            <span class="menu-title">
                                                <?php echo translate('failed_order'); ?>
                                            </span>
                                        </a>
                                    </li>
                                    <li style="display:none;" <?php if ($page_name == "return_sales") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/return_sales">
                                            <i class="fa fa-usd"></i>
                                            <span class="menu-title">
                                                <?php echo translate('return_order'); ?>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li> -->
                        <?php
                        }
                        ?>

                        <!--SALE-------------------->
                        <?php
                        if ($this->crud_model->admin_permission('sale')) {
                        ?>
                            <!-- <li <?php if (
                                    $page_name == "sales"
                                ) { ?> class="active-sub" <?php } ?>>
                                <a href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="menu-title">
                                        <?php echo translate('sales'); ?>
                                    </span>
                                    <i class="fa arrow"></i>
                                </a>

                                <ul class="collapse <?php if (
                                                        $page_name == "sales"
                                                    ) { ?>
                                                                 in
                                                                    <?php } ?>">

                                    <li <?php if ($page_name == "sales") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/sales/">
                                            <i class="fa fa-usd"></i>
                                            <span class="menu-title">
                                                <?php echo translate('sale'); ?>
                                            </span>
                                        </a>
                                    </li>
                                    <?php
                                    if ($this->crud_model->admin_permission('coupon')) {
                                    ?>
                                        <li <?php if ($page_name == "coupon") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/coupon/">
                                                <i class="fa fa-tag"></i>
                                                <span class="menu-title">
                                                    <?php echo translate('discount_coupon'); ?>
                                                </span>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </li> -->
                        <?php
                        }
                        ?>

                        <li style="display:none;" <?php if (
                                                        $page_name == "vendor" ||
                                                        $page_name == "membership_payment" ||
                                                        $page_name == "slides_vendor" ||
                                                        $page_name == "membership"
                                                    ) { ?> class="active-sub" <?php } ?>>
                            <a href="#">
                                <!-- <i class="fa fa-user-plus"></i> -->
                                <i class="fa fa-archive" aria-hidden="true"></i>
                                <span class="menu-title">
                                    <?php echo translate('stores'); ?>
                                </span>
                                <i class="fa arrow"></i>
                            </a>

                            <!--REPORT-------------------->
                            <ul class="collapse <?php if (
                                                    $page_name == "vendor" ||

                                                    $page_name == "slides_vendor"
                                                ) { ?>
                                                                             in
                                                                                <?php } ?> ">
                                <li <?php if ($page_name == "store") { ?> class="active-link" <?php } ?>>
                                    <a href="<?php echo base_url(); ?>index.php/admin/store/">
                                        <!-- <i class="fa fa-circle fs_i"></i> -->
                                        <i class="fa fa-list-alt" aria-hidden="true"></i>
                                        <?php echo translate('store_list'); ?>
                                    </a>
                                </li>

                                <li <?php if ($page_name == "slides_store") { ?> class="active-link" <?php } ?>>
                                    <a href="<?php echo base_url(); ?>index.php/admin/slides/store">
                                        <i class="fa fa-circle fs_i"></i>
                                        <?php echo translate('store\'s_slides'); ?>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <?php
                        if (($this->crud_model->admin_permission('report')) && (/* ($view_rights["8"]["0"]=="1") ||  */($view_rights["9"]["0"]=="1") || ($view_rights["10"]["0"]=="1") || ($view_rights["11"]["0"]=="1"))) {
                        ?>
                            <li <?php if (
                                    $page_name == "report" ||
                                    $page_name == "report_stock" ||
                                    $page_name == "report_wish"
                                ) { ?> class="active-sub" <?php } ?>>
                                <a href="#">
                                    <i class="fa fa-file-text"></i>
                                    <span class="menu-title">
                                        <?php echo translate('reports'); ?>
                                    </span>
                                    <i class="fa arrow"></i>
                                </a>

                                <!--REPORT-------------------->
                                <ul class="collapse <?php if (
                                                        $page_name == "sale" ||
                                                        $page_name == "report_stock" ||
                                                        $page_name == "report_wish"
                                                    ) { ?>
                                                                             in
                                                                                <?php } ?> ">
                                    <?php /* if($view_rights["8"]["0"]=="1"){ ?>
                                    <li <?php if ($page_name == "sale") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/sales/">
                                            <i class="fa fa-usd"></i>
                                            <span class="menu-title">
                                                <?php echo translate('sale'); ?>
                                            </span>
                                        </a>
                                    </li>
                                    <?php } */ ?>
                                    <?php if($view_rights["9"]["0"]=="1"){ ?>
                                    <li <?php if ($page_name == "report_delivered") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/del_orders/">
                                            <!-- <i class="fa fa-circle fs_i"></i> -->
                                            <i class="fa fa-link" aria-hidden="true"></i>
                                            <?php echo translate('order_delivered_Product'); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if($view_rights["10"]["0"]=="1"){ ?>
                                    <li <?php if ($page_name == "report_pending") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/pen_orders/">
                                            <!-- <i class="fa fa-circle fs_i"></i> -->
                                            <i class="fa fa-chain-broken" aria-hidden="true"></i>
                                            <?php echo translate('order_pending_Product'); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if($view_rights["11"]["0"]=="1"){ ?>
                                    <li <?php if ($page_name == "report_on_delivery") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/on_orders/">
                                            <!-- <i class="fa fa-circle fs_i"></i> -->
                                            <!-- <i class="fa fa-random" aria-hidden="true"></i> -->
                                            <i class="fa fa-truck" aria-hidden="true"></i>
                                            <?php echo translate('order__on_delivery_product'); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>
                        <?php if ($this->crud_model->get_type_name_by_id('general_settings', '108', 'value') == 'ok') { ?>

                            <li style="display:none;" <?php if ($page_name == "del_slot") { ?> class="active-link" <?php } ?> style="border-top:1px solid rgba(69, 74, 84, 0.7);">
                                <a href="<?php echo base_url(); ?>index.php/admin/del_slot">
                                    <i class="fa fa-tachometer"></i>
                                    <span class="menu-title">
                                        <?php echo translate('delivery_slot_date'); ?>
                                    </span>
                                </a>
                            </li>
                            <li style="display:none;" <?php if ($page_name == "del_slot_time") { ?> class="active-link" <?php } ?> style="border-top:1px solid rgba(69, 74, 84, 0.7);">
                                <a href="<?php echo base_url(); ?>index.php/admin/del_slot_time">
                                    <i class="fa fa-tachometer"></i>
                                    <span class="menu-title">
                                        <?php echo translate('delivery_slot_time'); ?>
                                    </span>
                                </a>
                            </li>
                            <?php
                        }
                        if ($this->crud_model->get_type_name_by_id('general_settings', '58', 'value') == 'ok') {
                            if ((
                                $this->crud_model->admin_permission('vendor') ||
                                $this->crud_model->admin_permission('membership_payment') ||
                                $this->crud_model->admin_permission('membership')
                            ) && ($view_rights["12"]["0"]=="1")) {
                            ?>
                                <li <?php if (
                                        $page_name == "vendor" ||
                                        $page_name == "membership_payment" ||
                                        $page_name == "slides_vendor" ||
                                        $page_name == "membership"
                                    ) { ?> class="active-sub" <?php } ?>>
                                    <a href="#">
                                        <!-- <i class="fa fa-user-plus"></i> -->
                                        <i class="fa fa-archive" aria-hidden="true"></i>
                                        <span class="menu-title">
                                            <?php echo translate('stores'); ?>
                                        </span>
                                        <i class="fa arrow"></i>
                                    </a>

                                    <!--REPORT-------------------->
                                    <ul class="collapse <?php if (
                                                            $page_name == "vendor" ||
                                                            $page_name == "vendor_commission" ||
                                                            $page_name == "membership_payment" ||
                                                            $page_name == "pay_to_vendor" ||
                                                            $page_name == "slides_vendor" ||
                                                            $page_name == "membership"
                                                        ) { ?>
                                                                             in
                                                                                <?php } ?> ">
                                        <li <?php if ($page_name == "vendor") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/vendor/">
                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                <i class="fa fa-list-alt" aria-hidden="true"></i>
                                                <?php echo translate('stores_list'); ?>
                                            </a>
                                        </li>
                                        <?php /*?><li <?php if($page_name=="pay_to_vendor"){?> class="active-link" <?php } ?> >
                                    <a href="<?php echo base_url(); ?>index.php/admin/pay_to_vendor/">
                                        <i class="fa fa-circle fs_i"></i>
                                        <?php echo translate('pay_to_vendor');?>
                                    </a>
                                </li>
                                <li <?php if($page_name=="membership_payment"){?> class="active-link" <?php } ?> >
                                    <a href="<?php echo base_url(); ?>index.php/admin/membership_payment/">
                                        <i class="fa fa-circle fs_i"></i>
                                        <?php echo translate('package_payments');?>
                                    </a>
                                </li>
                                <li <?php if($page_name=="membership"){?> class="active-link" <?php } ?> >
                                    <a href="<?php echo base_url(); ?>index.php/admin/membership/">
                                        <i class="fa fa-circle fs_i"></i>
                                        <?php echo translate('vendor_packages');?>
                                    </a>
                                </li><?php */ ?>
                                        <?php /*?><li <?php if($page_name=="vendor_commission"){?> class="active-link" <?php } ?> >
                                    <?php if($this->db->get_where('business_settings', array('type' => 'commission_set'))->row()->value == 'yes'){?>
                                    <a href="<?php echo base_url(); ?>index.php/admin/vendor_commission/">
                                        <i class="fa fa-circle fs_i"></i>
                                        <?php echo translate('vendor_commission');?>
                                    </a>
                                    <?php }?>
                                </li>
                                <li <?php if($page_name=="slides_vendor"){?> class="active-link" <?php } ?> >
                                    <a href="<?php echo base_url(); ?>index.php/admin/slides/vendor">
                                        <i class="fa fa-circle fs_i"></i>
                                        <?php echo translate('vendor\'s_slides');?>
                                    </a>
                                </li><?php */ ?>
                                    </ul>
                                </li>
                        <?php
                            }
                        }
                        ?>





                        <?php
                        if ($this->crud_model->admin_permission('marketing_vendor')) {
                        ?>
						
                        <h6 style="padding:12px 0px 12px 15px;font-weight:700;font-family: Be Vietnam,sans-serif;color:#044484">UNDERSTAND</h6>
						
                            <li style="display:none;" <?php if (
                                                            $page_name == "marketing_vendor_add" ||
                                                            $page_name == "marketing_vendor_list"
                                                        ) { ?> class="active-sub" <?php } ?>>
                                <a href="#">
                                    <i class="fa fa-file-text"></i>
                                    <span class="menu-title">
                                        <?php echo translate('marketing_vendor'); ?>
                                    </span>
                                    <i class="fa arrow"></i>
                                </a>

                                <!--REPORT-------------------->
                                <ul class="collapse <?php if (
                                                        $page_name == "marketing_vendor_add" ||
                                                        $page_name == "marketing_vendor_list"
                                                    ) { ?>
                                                                             in
                                                                                <?php } ?> ">


                                    <li <?php if ($page_name == "marketing_vendor") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/marketing_vendor/">
                                            <i class="fa fa-circle fs_i"></i>
                                            <?php echo translate('marketing_vendor'); ?>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        <?php
                        }
                        ?>
                    
                        <?php
                        if (($this->crud_model->admin_permission('user')) && (($view_rights["13"]["10"]=="1") || ($view_rights["13"]["11"]=="1") || ($view_rights["13"]["12"]=="1") || ($view_rights["14"]["0"]=="1"))) {
                        ?>
                            <li <?php if (
                                    $page_name == "user" ||
                                    $page_name == "wallet_load" ||
                                    $page_name == "package" || $page_name == "review" ||  $page_name === "package_payment"
                                ) { ?> class="active-sub" <?php } ?>>
                                <a href="#">
                                    <!-- <i class="fa fa-users"></i> -->
                                    <!-- <i class="fa fa-handshake-o" aria-hidden="true"></i> -->
                                    <!-- <i class="fa fa-user-circle-o" aria-hidden="true"></i> -->
                                    <!-- <i class="fa fa-user-circle" aria-hidden="true"></i> -->
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                    <span class="menu-title">
                                        <?php echo translate('customers'); ?>
                                    </span>
                                    <i class="fa arrow"></i>
                                </a>

                                <ul class="collapse <?php if (
                                                        $page_name == "user" ||
                                                        $page_name == "wallet_load" || $page_name == "package" || $page_name == "package" || $page_name === "package_payment"
                                                    ) { ?>
                                                                 in
                                                                    <?php } ?>">
                                                                    <?php
                                    if (($this->crud_model->admin_permission('user')) && (($view_rights["13"]["10"]=="1") || ($view_rights["13"]["11"]=="1") || ($view_rights["13"]["12"]=="1"))) {
                                    ?>
                                        <li <?php if ($page_name == "customers") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/customers">
                                            <i class="fa fa-users"></i>
                                                <?php echo translate('customers'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($this->crud_model->admin_permission('user')) {
                                    ?>
                                        <!-- <li <?php if ($page_name == "user") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/user">
                                                <i class="fa fa-circle fs_i"></i>
                                                <?php echo translate('customers'); ?>
                                            </a>
                                        </li> -->
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($this->crud_model->admin_permission('user')) {
                                    ?>
                                        <!-- <li <?php if ($page_name == "user_group") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/user_group">
                                                <i class="fa fa-circle fs_i"></i>
                                                <?php echo translate('customers_group'); ?>
                                            </a>
                                        </li> -->
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($this->crud_model->admin_permission('user') && $this->crud_model->get_type_name_by_id('general_settings', '84', 'value') == 'ok') {
                                    ?>
                                        <!-- <li <?php if ($page_name == "wallet_load") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/wallet_load">
                                                <i class="fa fa-circle fs_i"></i>
                                                <?php echo translate('wallet_loads'); ?>
                                            </a>
                                        </li> -->
                                    <?php
                                    }
                                    if ($this->crud_model->admin_permission('package') && $customer_product_check == 'ok') {
                                    ?>
                                        <li style="display:none;" <?php if ($page_name == "package") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/package">
                                                <i class="fa fa-circle fs_i"></i>
                                                <?php echo translate('premium_package'); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php
                                    if (($this->crud_model->admin_permission('user')) && ($view_rights["14"]["0"]=="1")) {
                                    ?>
                                        <li <?php if ($page_name == "review") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/review">
                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <?php echo translate('Rating & Review'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($this->crud_model->admin_permission('package_payment') && $customer_product_check == 'ok') {
                                    ?>
                                        <li style="display:none;" <?php if ($page_name == "package_payment") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/package_payment/">
                                                <i class="fa fa-gift"></i>
                                                <span class="menu-title">
                                                    <?php echo translate('customer_package_payments'); ?>
                                                </span>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>


                        <?php
                        if ($this->crud_model->admin_permission('user')) {
                        ?>
                            <li style="display:none;" <?php if ($page_name == "user") { ?> class="active-link" <?php } ?>>
                                <a href="<?php echo base_url(); ?>index.php/admin/subscribe/">
                                    <i class="fa fa-users"></i>
                                    <span class="menu-title">
                                        <?php echo translate('Subscribe'); ?>
                                    </span>
                                </a>
                            </li>
                        <?php
                        }
                        ?>

                        <?php
                        if ((
                            $this->crud_model->admin_permission('newsletter') ||
                            $this->crud_model->admin_permission('contact_message') ||
                            $this->crud_model->admin_permission('whatsapp_message')
                        ) && (($view_rights["15"]["0"]=="1") || ($view_rights["16"]["0"]=="1") || ($view_rights["17"]["0"]=="1"))) {
                        ?>
                            <li <?php if (
                                    $page_name == "newsletter" ||
                                    $page_name == "contact_message" || $page_name === "ticket"
                                ) { ?> class="active-sub" <?php } ?>>
                                <a href="#">
                                    <i class="fa fa-envelope"></i>
                                    <span class="menu-title">
                                        <?php echo translate('messages'); ?>
                                    </span>
                                    <i class="fa arrow"></i>
                                </a>

                                <ul class="collapse <?php if (
                                                        $page_name == "newsletter" ||
                                                        $page_name == "contact_message" || $page_name === "ticket"
                                                    ) { ?>
                                                                 in
                                                                    <?php } ?>">

                                    <?php
                                    if (($this->crud_model->admin_permission('newsletter')) && ($view_rights["15"]["0"]=="1")) {
                                    ?>
                                        <li <?php if ($page_name == "newsletter") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/newsletter">
                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                                                <?php echo translate('newsletters'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if (($this->crud_model->admin_permission('whatsapp_message')) && ($view_rights["16"]["0"]=="1")) {
                                    ?>
                                        <li <?php if ($page_name == "whatsapp_message") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/whatsapp_message">
                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                                <?php echo translate('whatsapp'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if (($this->crud_model->admin_permission('contact_message')) && ($view_rights["17"]["0"]=="1")) {
                                    ?>
                                        <li <?php if ($page_name == "contact_message") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/contact_message">
                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                <i class="fa fa-comments" aria-hidden="true"></i>
                                                <?php echo translate('message_history'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($this->crud_model->admin_permission('ticket')) {
                                    ?>
                                        <li style="display:none;" <?php if ($page_name == "ticket") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/ticket/">
                                                <i class="fa fa-life-ring"></i>
                                                <span class="menu-title">
                                                    <?php echo translate('ticket'); ?>
                                                </span>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>
                        <?php
                        if (($this->crud_model->admin_permission('blogs')) && (($view_rights["18"]["13"]=="1") || ($view_rights["18"]["14"]=="1"))) {
                        ?>
                        <li <?php if ($page_name == "blogs") { ?> class="active-link" <?php } ?> style="border-top:1px solid rgba(69, 74, 84, 0.7);">
                            <a href="<?php echo base_url(); ?>index.php/admin/blogs">
                                <!-- <i class="fa fa-user"></i> -->
                                <i class="fa fa-rss" aria-hidden="true"></i>
                                <span class="menu-title">
                                    <?php echo translate('blog'); ?>
                                </span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php
                        /* if ($this->crud_model->admin_permission('blog')) {
                        ?>
                            <li <?php if ($page_name == "blog" || $page_name == "blog_category") { ?> class="active-sub" <?php } ?>>
                                <a href="#">
                                    <i class="fa fa-user"></i>
                                    <span class="menu-title">
                                        <?php echo translate('blog'); ?>
                                    </span>
                                    <i class="fa arrow"></i>
                                </a>
                                <ul class="collapse <?php if ($page_name == "blog" || $page_name == "blog_category") {
                                                        echo 'in';
                                                    } ?>">
                                    <?php
                                    if ($this->crud_model->admin_permission('blog')) {
                                    ?>
                                        <!--Menu list item-->
                                        <li <?php if ($page_name == "blog_category") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/blog_category/">
                                                <i class="fa fa-circle fs_i"></i>
                                                <?php echo translate('blog_categories'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($this->crud_model->admin_permission('blog')) {
                                    ?>
                                        <li <?php if ($page_name == "blog") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/blog/">
                                                <i class="fa fa-circle fs_i"></i>
                                                <?php echo translate('all_blogs'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                            <?php  ?>
                        <?php
                        } */
                        ?>
                        <?php
                        if ((
                            $this->crud_model->admin_permission('slider') ||
                            $this->crud_model->admin_permission('slides') ||
                            $this->crud_model->admin_permission('display_settings') ||
                            $this->crud_model->admin_permission('site_settings') ||
                            $this->crud_model->admin_permission('email_template') ||
                            $this->crud_model->admin_permission('captha_n_social_settings') ||
                            $this->crud_model->admin_permission('page')
                        ) && (($view_rights["19"]["0"]=="1") || ($view_rights["20"]["0"]=="1") || ($view_rights["21"]["0"]=="1") || ($view_rights["22"]["0"]=="1") || ($view_rights["23"]["0"]=="1") || ($view_rights["24"]["0"]=="1") || ($view_rights["25"]["0"]=="1") || ($view_rights["26"]["0"]=="1") || ($view_rights["27"]["0"]=="1"))) {
                        ?>
						
						<h6 style="padding:12px 0px 12px 15px;font-weight:700;font-family: Be Vietnam,sans-serif;color:#044484">SETTINGS</h6>
						
                            <li <?php if (
                                    $page_name == "slider" ||
                                    $page_name == "slides" ||
                                    $page_name == "display_settings" ||
                                    $page_name == "site_settings" ||
                                    $page_name == "email_template" ||
                                    $page_name == "captha_n_social_settings" ||
                                    $page_name == "default_images" ||
                                    $page_name == "page"
                                ) { ?> class="active-sub" <?php } ?>>
                                <a href="#">
                                    <i class="fa fa-desktop"></i>
                                    <span class="menu-title">
                                        <?php echo translate('frontend_settings'); ?>
                                    </span>
                                    <i class="fa arrow"></i>
                                </a>
                                <!--Submenu-->
                                <ul class="collapse <?php if (
                                                        $page_name == "slider" ||
                                                        $page_name == "slides" ||
                                                        $page_name == "display_settings" ||
                                                        $page_name == "site_settings" ||
                                                        $page_name == "email_template" ||
                                                        $page_name == "captha_n_social_settings" ||
                                                        $page_name == "default_images" ||
                                                        $page_name == "page"
                                                    ) { ?>
																						in
																						<?php } ?>">

                                    <?php
                                    if ((
                                        $this->crud_model->admin_permission('slider') ||
                                        $this->crud_model->admin_permission('slides')
                                    ) && ($view_rights["19"]["0"]=="1")) {
                                    ?>
									
                                        <li <?php if (
                                                $page_name == "slider" ||
                                                $page_name == "slides"
                                            ) { ?> class="active-sub" <?php } ?>>
                                            <a href="#">
                                                <i class="fa fa-sliders"></i>
                                                <span class="menu-title">
                                                    <?php echo translate('slider_settings'); ?>
                                                </span>
                                                <i class="fa arrow"></i>
                                            </a>

                                            <!--REPORT-------------------->
                                            <ul class="collapse <?php if (
                                                                    $page_name == "slider" ||
                                                                    $page_name == "slides"
                                                                ) { ?>
                                                                         in
                                                                            <?php } ?> ">
                                                <li <?php if ($page_name == "slider") { ?> class="active-link" <?php } ?>>
                                                    <a href="<?php echo base_url(); ?>index.php/admin/slider/">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <!-- <i class="fa fa-object-ungroup" aria-hidden="true"></i> -->
                                                        <?php echo translate('layer_slider'); ?>
                                                    </a>
                                                </li>
                                                <li style="display:none;" <?php if ($page_name == "slides") { ?> class="active-link" <?php } ?>>
                                                    <a href="<?php echo base_url(); ?>index.php/admin/slides/">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('top_slides'); ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if (($this->crud_model->admin_permission('display_settings')) && (($view_rights["20"]["0"]=="1") || ($view_rights["21"]["0"]=="1") || ($view_rights["22"]["0"]=="1") || ($view_rights["23"]["0"]=="1"))) {
                                        $tab = $this->uri->segment(3);
                                    ?>
                                        <li <?php if ($page_name == "display_settings") { ?> class="active-sub" <?php } ?>>
                                            <a href="#">
                                                <i class="fa fa-television"></i>
                                                <span class="menu-title">
                                                    <?php echo translate('display_settings'); ?>
                                                </span>
                                                <i class="fa arrow"></i>
                                            </a>

                                            <!--PRODUCT------------------>
                                            <ul class="collapse <?php if ($page_name == "display_settings") { ?>
                                                                                     in
                                                                                        <?php } ?> ">

                                                <li style="display:none;" <?php if ($tab == 'home') { ?>class="active-link" <?php } ?>>
                                                    <a href="<?php echo base_url(); ?>index.php/admin/display_settings/home">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('home_page'); ?>
                                                    </a>
                                                </li>
                                                <?php if($view_rights["20"]["0"]=="1"){ ?>
                                                <li <?php if ($tab == 'contact') { ?>class="active-link" <?php } ?>>
                                                    <a href="<?php echo base_url(); ?>index.php/admin/display_settings/contact">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <!-- <i class="fa fa-address-card-o" aria-hidden="true"></i> -->
                                                        <!-- <i class="fa fa-address-card" aria-hidden="true"></i> -->
                                                        <!-- <i class="fa fa-address-book-o" aria-hidden="true"></i> -->
                                                        <!-- <i class="fa fa-address-book" aria-hidden="true"></i> -->
                                                        <?php echo translate('contact_page'); ?>
                                                    </a>
                                                </li>
                                                <?php } ?>
                                                <?php if($view_rights["21"]["0"]=="1"){ ?>
                                                <li <?php if ($tab == 'footer') { ?>class="active-link" <?php } ?>>
                                                    <a href="<?php echo base_url(); ?>index.php/admin/display_settings/footer">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('footer'); ?>
                                                    </a>
                                                </li>
                                                <?php } ?>
                                                <li <?php if ($tab == 'theme') { ?>class="active-link" <?php } else { ?>class="hidden " <?php } ?>>
                                                    <a href="<?php echo base_url(); ?>index.php/admin/display_settings/theme">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('theme_color'); ?>
                                                    </a>
                                                </li>
                                                <?php if($view_rights["22"]["0"]=="1"){ ?>
                                                <li <?php if ($tab == 'logo') { ?>class="active-link" <?php } ?>>
                                                    <a href="<?php echo base_url(); ?>index.php/admin/display_settings/logo">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('logo'); ?>
                                                    </a>
                                                </li>
                                                <?php } ?>
                                                <?php if($view_rights["23"]["0"]=="1"){ ?>
                                                <li <?php if ($tab == 'favicon') { ?>class="active-link" <?php } ?>>
                                                    <a href="<?php echo base_url(); ?>index.php/admin/display_settings/favicon">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('favicon'); ?>
                                                    </a>
                                                </li>
                                                <?php } ?>
                                                <li <?php if ($tab == 'font') { ?>class="active-link" <?php } else { ?>class="hidden " <?php } ?>>
                                                    <a href="<?php echo base_url(); ?>index.php/admin/display_settings/font">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('fonts'); ?>
                                                    </a>
                                                </li>
                                                <li <?php if ($tab == 'preloader') { ?>class="active-link" <?php } else { ?>class="hidden " <?php } ?>>
                                                    <a href="<?php echo base_url(); ?>index.php/admin/display_settings/preloader">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('preloader'); ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ((
                                        $this->crud_model->admin_permission('site_settings') ||
                                        $this->crud_model->admin_permission('email_template') ||
                                        $this->crud_model->admin_permission('captha_n_social_settings')
                                    ) && (($view_rights["24"]["0"]=="1") || ($view_rights["25"]["0"]=="1") || ($view_rights["26"]["0"]=="1"))) {
                                    ?>
                                        <li <?php if (
                                                $page_name == "site_settings" ||
                                                $page_name == "email_template" ||
                                                $page_name == "captha_n_social_settings"
                                            ) { ?> class="active-sub" <?php } ?>>
                                            <a href="#">
                                                <i class="fa fa-wrench"></i>
                                                <span class="menu-title">
                                                    <?php echo translate('site_settings'); ?>
                                                </span>
                                                <i class="fa arrow"></i>
                                            </a>
                                            <!--Submenu-->
                                            <ul class="collapse <?php if (
                                                                    $page_name == "site_settings" ||
                                                                    $page_name == "email_template" ||
                                                                    $page_name == "captha_n_social_settings"
                                                                ) { ?>
                                                                     		in
                                                                        		<?php } ?>">

                                                <?php
                                                if (($this->crud_model->admin_permission('site_settings')) && ($view_rights["24"]["0"]=="1")) {
                                                ?>
                                                    <li <?php if ($page_name == "site_settings") { ?> class="active-link" <?php } ?>>
                                                        <a href="<?php echo base_url(); ?>index.php/admin/site_settings/general_settings/">
                                                            <i class="fa fa-circle fs_i"></i>
                                                            <?php echo translate('general_settings'); ?>
                                                        </a>
                                                    </li>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                if (($this->crud_model->admin_permission('email_template')) && ($view_rights["25"]["0"]=="1")) {
                                                ?>
                                                    <li <?php if ($page_name == "email_template") { ?> class="active-link" <?php } ?>>
                                                        <a href="<?php echo base_url(); ?>index.php/admin/email_template/">
                                                            <i class="fa fa-circle fs_i"></i>
                                                            <?php echo translate('email_templates'); ?>
                                                        </a>
                                                    </li>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                if (($this->crud_model->admin_permission('captha_n_social_settings')) && ($view_rights["26"]["0"]=="1")) {
                                                ?>
                                                    <!--Menu list item-->
                                                    <li <?php if ($page_name == "captha_n_social_settings") { ?> class="active-link" <?php } ?>>
                                                        <a href="<?php echo base_url(); ?>index.php/admin/captha_n_social_settings/">
                                                            <i class="fa fa-circle fs_i"></i>
                                                            <?php echo translate('third_party_settings'); ?>
                                                        </a>
                                                    </li>
                                                    <!--Menu list item-->
                                                <?php
                                                }
                                                ?>

                                            </ul>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if (($this->crud_model->admin_permission('page')) && ($view_rights["27"]["0"]=="1")) {
                                    ?>

                                        <li <?php if ($page_name == "page") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/page/">
                                                <i class="fa fa-code"></i>
                                                <span class="menu-title">
                                                    <?php echo translate('build_responsive_pages'); ?>
                                                </span>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($this->crud_model->admin_permission('default_images')) {
                                    ?>
                                        <li style="display:none;" <?php if ($page_name == "default_images") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/default_images/">
                                                <i class="fa fa-camera"></i>
                                                <span class="menu-title">
                                                    <?php echo translate('set_default_images'); ?>
                                                </span>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>
                        <?php
                        if (($this->crud_model->admin_permission('business_settings')) && (($view_rights["28"]["0"]=="1") || ($view_rights["29"]["0"]=="1") || ($view_rights["30"]["0"]=="1") || ($view_rights["31"]["0"]=="1") || ($view_rights["32"]["0"]=="1") || ($view_rights["33"]["0"]=="1") || ($view_rights["34"]["0"]=="1") || ($view_rights["35"]["0"]=="1") || ($view_rights["36"]["0"]=="1"))) {
                        ?>
                            <li <?php if (
                                    $page_name == "activation" ||
                                    $page_name == "payment_method" ||
                                    $page_name == "curency_method" ||
                                    $page_name == "faq_settings" || $page_name == "seo_settings" || $page_name === "language" || $page_name === "manage_admin"
                                ) { ?> class="active-sub" <?php } ?>>
                                <a href="#">
                                    <i class="fa fa-briefcase"></i>
                                    <span class="menu-title">
                                        <?php echo translate('business_settings'); ?>
                                    </span>
                                    <i class="fa arrow"></i>
                                </a>

                                <!--REPORT-------------------->
                                <ul class="collapse <?php if (
                                                        $page_name == "activation" ||
                                                        $page_name == "rewards" ||
                                                        $page_name == "delivery_type" ||
                                                        $page_name == "cod" ||
                                                        $page_name == "markup" ||
                                                        $page_name == "payment_method" ||
                                                        $page_name == "curency_method" ||
                                                        $page_name == "faq_settings" || $page_name == "seo_settings" || $page_name === "language" || $page_name === "manage_admin"
                                                    ) { ?>
                                                                             in
                                                                                <?php } ?> ">

                                    <li style="display:none;" <?php if ($page_name == "activation") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/activation/">
                                            <i class="fa fa-circle fs_i"></i>
                                            <?php echo translate('activation'); ?>
                                        </a>
                                    </li>
                                    <?php if($view_rights["28"]["0"]=="1"){ ?>
                                    <li <?php if ($page_name == "pre_order") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/pre_order/">
                                            <!-- <i class="fa fa-circle fs_i"></i> -->
                                            <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
                                            <?php echo translate('pre_order'); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if($view_rights["29"]["0"]=="1"){ ?>
                                    <li <?php if ($page_name == "rewards") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/rewards/">
                                            <!-- <i class="fa fa-circle fs_i"></i> -->
                                            <i class="fa fa-gift" aria-hidden="true"></i>
                                            <?php echo translate('rewards'); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if($view_rights["30"]["0"]=="1"){ ?>
                                    <li <?php if ($page_name == "delivery_type") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/delivery_type/">
                                            <!-- <i class="fa fa-circle fs_i"></i> -->
                                            <i class="fa fa-usd" aria-hidden="true"></i>
                                            <?php echo translate('delivery_fee'); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if($view_rights["31"]["0"]=="1"){ ?>
                                    <li <?php if ($page_name == "cod") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/cod/">
                                            <!-- <i class="fa fa-circle fs_i"></i> -->
                                            <i class="fa fa-money" aria-hidden="true"></i>
                                            <?php echo translate('cash_on_delivery'); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <li style="display:none;" <?php if ($page_name == "markup") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/markup/">
                                            <i class="fa fa-circle fs_i"></i>
                                            <?php echo translate('markup_fee'); ?>
                                        </a>
                                    </li>
                                    <li style="display:none;" <?php if ($page_name == "payment_method") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/payment_method/">
                                            <i class="fa fa-circle fs_i"></i>
                                            <?php echo translate('payment_method'); ?>
                                        </a>
                                    </li>
                                    <?php if($view_rights["32"]["0"]=="1"){ ?>
                                    <li <?php if ($page_name == "curency_method") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/curency_method/">
                                            <!-- <i class="fa fa-circle fs_i"></i> -->
                                            <i class="fa fa-gbp" aria-hidden="true"></i>
                                            <?php echo translate('currency_') ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if($view_rights["33"]["0"]=="1"){ ?>
                                    <li <?php if ($page_name == "faq_settings") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/faqs/">
                                            <!-- <i class="fa fa-circle fs_i"></i> -->
                                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                                            <?php echo translate('faqs'); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php
                                    if (($this->crud_model->admin_permission('seo')) && ($view_rights["34"]["0"]=="1")) {
                                    ?>
                                        <li <?php if ($page_name == "seo_settings") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/seo_settings">
                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                <i class="fa fa-search-plus" aria-hidden="true"></i>
                                                <span class="menu-title">
                                                    SEO
                                                </span>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if (($this->crud_model->admin_permission('language')) && ($view_rights["35"]["0"]=="1")) {
                                    ?>
                                        <li <?php if ($page_name == "language") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/language_settings">
                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                <i class="fa fa-language" aria-hidden="true"></i>
                                                <span class="menu-title">
                                                    <?php echo translate('language'); ?>
                                                </span>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>                                    
                                    <li style="display:none;" <?php if ($page_name == "courier") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/courier/">
                                            <i class="fa fa-users"></i>
                                            <span class="menu-title">
                                                <?php echo translate('courier_service'); ?>
                                            </span>
                                        </a>
                                    </li>
                                    <li style="display:none;" <?php if ($page_name == "city") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/city/">
                                            <i class="fa fa-users"></i>
                                            <span class="menu-title">
                                                <?php echo translate('city_management'); ?>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>
                        <?php
                        if ((
                            $this->crud_model->admin_permission('role') ||
                            $this->crud_model->admin_permission('admin')
                        ) && (($view_rights["37"]["0"]=="1") || ($view_rights["38"]["0"]=="1") || ($view_rights["39"]["0"]=="1"))) {
                        ?>
						
						<h6 style="padding:12px 0px 12px 15px;font-weight:700;font-family: Be Vietnam,sans-serif;color:#044484">STAFFS</h6>
						
                            <li <?php if (
                                    $page_name == "role" ||
                                    $page_name == "admin"
                                ) { ?> class="active-sub" <?php } ?>>
                                <a href="#">
                                    <!-- <i class="fa fa-user"></i> -->
                                    <!-- <i class="fa fa-id-badge" aria-hidden="true"></i> -->
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                    <span class="menu-title">
                                        <?php echo translate('staffs'); ?>
                                    </span>
                                    <i class="fa arrow"></i>
                                </a>

                                <ul class="collapse <?php if (
                                                        $page_name == "admin" ||
                                                        $page_name == "role"
                                                    ) { ?>
                                                                 in
                                                                    <?php } ?>">

                                    <?php
                                    if ($this->crud_model->admin_permission('admin')) {
                                    ?>
                                        <?php if($view_rights["37"]["0"]=="1"){ ?>
                                        <li <?php if ($page_name == "admin") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/admins/">
                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                <i class="fa fa-user" aria-hidden="true"></i>
                                                <?php echo translate('manage_staffs'); ?>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if($view_rights["38"]["0"]=="1"){ ?>
                                        <li <?php if ($page_name == "staff_log") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/admins_log/">
                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                <?php echo translate('staff_log'); ?>
                                            </a>
                                        </li>
                                        <?php } ?>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if (($this->crud_model->admin_permission('role')) && ($view_rights["39"]["0"]=="1")) {
                                    ?>
                                        <!--Menu list item-->
                                        <li <?php if ($page_name == "role") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/role/">
                                                <!-- <i class="fa fa-circle fs_i"></i> -->
                                                <!-- <i class="fa fa-universal-access" aria-hidden="true"></i> -->
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                <?php echo translate('staff_permissions'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <?php if($view_rights["36"]["0"]=="1"){ ?>
                                    <li <?php if ($page_name == "manage_admin") { ?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>index.php/admin/manage_admin/">
                                            <i class="fa fa-lock"></i>
                                            <span class="menu-title">
                                                <?php echo translate('manage_admin_profile'); ?>
                                            </span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>



                        <?php
                        if ((
                            $this->crud_model->admin_permission('delete_all_categories') ||
                            $this->crud_model->admin_permission('delete_all_products') ||
                            $this->crud_model->admin_permission('delete_all_brands') ||
                            $this->crud_model->admin_permission('delete_all_classified')
                        ) && (($view_rights["40"]["0"]=="1") || ($view_rights["41"]["0"]=="1") || ($view_rights["42"]["0"]=="1") || ($view_rights["43"]["0"]=="1"))) {
                        ?>
                            <!--Menu list item-->
							
							<h6 style="padding:12px 0px 12px 15px;font-weight:700;font-family: Be Vietnam,sans-serif;color:#044484">TERMINAL</h6>
							
                            <li <?php if (
                                    $page_name == "delete_all_categories" ||
                                    $page_name == "delete_all_products" ||
                                    $page_name == "delete_all_brands" ||
                                    $page_name == "delete_all_classified"
                                ) { ?> class="active-sub" <?php } ?>>
                                <a href="#">
                                    <i class="fa fa-trash"></i>
                                    <span class="menu-title">
                                        <?php echo translate('delete_contents'); ?>
                                    </span>
                                    <i class="fa arrow"></i>
                                </a>
                                <!--digital------------------>
                                <ul class="collapse <?php if (
                                                        $page_name == "delete_all_categories" ||
                                                        $page_name == "delete_all_products" ||
                                                        $page_name == "delete_all_brands" ||
                                                        $page_name == "delete_all_classified"
                                                    ) { ?>
                                                                                 in
                                                                                    <?php } ?> >">

                                    <?php
                                    if (($this->crud_model->admin_permission('category')) && ($view_rights["40"]["0"]=="1")) {
                                    ?>

                                        <li <?php if ($page_name == "delete_all_categories") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/delete_all_categories">
                                                <i class="fa fa-circle fs_i"></i>
                                                <?php echo translate('delete_all_categories'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    if (($this->crud_model->admin_permission('sub_category')) && ($view_rights["41"]["0"]=="1")) {
                                    ?>
                                        <li <?php if ($page_name == "delete_all_products") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/delete_all_products">
                                                <i class="fa fa-circle fs_i"></i>
                                                <?php echo translate('delete_all_products'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    if (($this->crud_model->admin_permission('delete_all_brands')) && ($view_rights["42"]["0"]=="1")) {
                                    ?>
                                        <li <?php if ($page_name == "delete_all_brands") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/delete_all_brands">
                                                <i class="fa fa-circle fs_i"></i>
                                                <?php echo translate('delete_all_brands'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    if (($this->crud_model->admin_permission('delete_all_classified')) && ($view_rights["43"]["0"]=="1")) {
                                    ?>
                                        <li <?php if ($page_name == "delete_all_classified") { ?> class="active-link" <?php } ?>>
                                            <a href="<?php echo base_url(); ?>index.php/admin/delete_all_classified">
                                                <i class="fa fa-circle fs_i"></i>
                                                <?php echo translate('delete_all_classified'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    } ?>
                                </ul>
                            </li>

                        <?php
                        }
                        ?>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</nav>
<style>
    .activate_bar {
        border-left: 3px solid #1ACFFC;
        transition: all .6s ease-in-out;
    }

    .activate_bar:hover {
        border-bottom: 3px solid #1ACFFC;
        transition: all .6s ease-in-out;
        background: #1ACFFC !important;
        color: #000 !important;
    }

    ul ul ul li a {
        padding-left: 80px !important;
    }

    ul ul ul li a:hover {
        background: #2f343b !important;
    }
</style>