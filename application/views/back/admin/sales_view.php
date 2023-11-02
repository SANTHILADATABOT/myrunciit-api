
<div class="panel-body ">
        <?php
        $sale = $this->db->get_where('sale', array('order_id' => $sale[0]['order_id']))->result_array();
        $productid_by_brand=[];
        foreach($sale as $order_details_1)
        {
            $product_details_1 = json_decode($order_details_1['product_details'], true);
            foreach ($product_details_1 as $product_details_11)
            {
                $product_brand_id = $this->db->get_where('product',array('product_id'=>$product_details_11['id']))->row()->brand;
                $productid_by_brand[$product_brand_id][]=$product_details_11;
            }
        }
        $row = $sale[0];
        // foreach ($sale as $row) 
        {
            $info = json_decode($row['shipping_address'], true);
            //invoice and map
        ?>

            <div class="col-md-2"></div>
            <div class="col-md-8 clearfix">
                    <div id="quart">
                        <?php
                        foreach($productid_by_brand as $brand_id=>$productid_by_brand_1)
                        { ?>
                        <div class="bordered print printwe" id="print_div_quart_<?php echo $brand_id; ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-lg-6 col-md-6 col-sm-6 pad-all">
                                        <img src="<?php echo $this->crud_model->logo('home_top_logo'); ?>" alt="Active Super Shop" width="55%">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 pad-all">
                                        <b class="pull-right pt-inc">
                                            <?php echo translate('invoice_no'); ?> : <?php echo $row['sale_code']; ?>
                                        </b>
                                        <br>
                                        <b class="pull-right pt-inc">
                                            <?php echo translate('date_:'); ?> <?php echo date('d M, Y', $row['sale_datetime']); ?>
                                        </b>
                                    </div>
                                </div>

                                <div class="col-md-12 pad-top">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <!--Panel heading-->
                                        <div class="panel panel-bordered-grey shadow-none">
                                            <div class="panel-heading">
                                                <h1 class="panel-title"><?php echo translate('client_information'); ?></h1>
                                            </div>
                                            <!--List group-->
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td><b><?php echo translate('first_name'); ?></b></td>
                                                        <td><?php echo $info['firstname']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?php echo translate('last_name'); ?></b></td>
                                                        <td><?php echo $info['lastname']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?php echo translate('phone'); ?></b></td>
                                                        <td><?php echo $info['phone']; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <!--Panel heading-->
                                        <div class="panel panel-bordered-grey shadow-none">
                                            <div class="panel-heading">
                                                <h1 class="panel-title"><?php echo translate('payment_detail'); ?></h1>
                                            </div>
                                            <!--List group-->
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td><b><?php echo translate('payment_status'); ?></b></td>
                                                        <td><i><?php echo translate($this->crud_model->sale_payment_status($row['sale_id'], 'admin')); ?></i></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?php echo translate('payment_method'); ?></b></td>
                                                        <td>
                                                            <?php
                                                            $payment_type = $row['payment_type'];

                                                            if ($payment_type == 'c2') {
                                                                echo 'TwoCheckout';
                                                            } else if ($payment_type == 'pum') {
                                                                echo "Payumoney";
                                                            } else {
                                                                echo ucfirst(str_replace('_', ' ', $payment_type));
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?php echo translate('payment_date'); ?></b></td>
                                                        <td><?php echo date('d M, Y', $row['sale_datetime']); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="panel panel-bordered-grey shadow-none">
                                            <div class="panel-heading">
                                                <h1 class="panel-title"><?php echo translate('brand_details'); ?></h1>
                                            </div>
                                            <p>
                                                <b><?php echo translate('brand_name'); ?> :</b>
                                                <?php echo translate($this->db->get_where('brand',array('brand_id'=>$brand_id))->row()->name); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-body" id="demo_s">
                                <div class="panel panel-bordered panel-dark shadow-none">
                                    <div class="panel-heading">
                                        <h1 class="panel-title"><?php echo translate('payment_invoice'); ?></h1>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?php echo translate('no'); ?></th>
                                                    <th><?php echo translate('item'); ?></th>
                                                    <th><?php echo translate('options'); ?></th>
                                                    <th><?php echo translate('quantity'); ?></th>
                                                    <th><?php echo translate('unit_cost'); ?></th>
                                                    <th><?php echo translate('total'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                //$product_details = json_decode($row['product_details'], true);
                                                $i = 0;
                                                $total = 0;
                                                $vat = 0;
                                                foreach ($productid_by_brand_1 as $row1) {
                                                    if ($this->crud_model->is_added_by('product', $row1['id'], 0, 'admin')) {
                                                        $i++;
                                                ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo $row1['name']; ?></td>
                                                            <td>
                                                                <?php
                                                                $all_o = json_decode($row1['option'], true);
                                                                $color = $all_o['color']['value'];
                                                                if ($color) {
                                                                ?>
                                                                    <div style="background:<?php echo $color; ?>; height:25px; width:25px;"></div>
                                                                <?php
                                                                }
                                                                ?>
                                                                <?php
                                                                foreach ($all_o as $l => $op) {
                                                                    if ($l !== 'color' && $op['value'] !== '' && $op['value'] !== NULL) {
                                                                ?>
                                                                        <?php echo $op['title'] ?> :
                                                                        <?php
                                                                        if (is_array($va = $op['value'])) {
                                                                            echo $va = join(', ', $va);
                                                                        } else {
                                                                            echo $va;
                                                                        }
                                                                        ?>
                                                                        <br>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?php echo $row1['qty']; ?></td>
                                                            <td><?php echo currency('', 'def') . $this->cart->format_number($row1['price']); ?></td>
                                                            <td><?php echo currency('', 'def') . $this->cart->format_number($row1['subtotal']);
                                                                $total += $row1['subtotal']; ?></td>
                                                            <?php
                                                            $vat += $row1['tax'];
                                                            ?>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="col-lg-6 col-md-6 col-sm-6 pull-right margin-top-20">
                                            <div class="panel panel-colorful panel-grey shadow-none">
                                                <table class="table" border="0">
                                                    <tbody>
                                                        <tr>
                                                            <td><b><?php echo translate('sub_total_amount'); ?></b></td>
                                                            <td><?php echo currency('', 'def') . $this->cart->format_number($total); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b><?php echo translate('tax'); ?></b></td>
                                                            <td><?php echo currency('', 'def') . $this->cart->format_number($vat); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b><?php echo translate('grand_total'); ?></b></td>
                                                            <td><?php echo currency('', 'def') . $this->cart->format_number($total + $vat); ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <!--List group-->
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <!--Panel heading-->
                                            <div class="panel panel-colorful panel-grey shadow-none">
                                                <div class="panel-heading">
                                                    <h1 class="panel-title"><?php echo translate('client_information'); ?></h1>
                                                </div>
                                                <!--List group-->
                                                <table class="table" border="0">
                                                    <tbody>
                                                        <tr>
                                                            <td><b><?php echo translate('address_line_1'); ?></b></td>
                                                            <td><?php echo $info['address1']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b><?php echo translate('address_line_2'); ?></b></td>
                                                            <td><?php echo $info['address2']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b><?php echo translate('zipcode'); ?></b></td>
                                                            <td><?php echo $info['zip']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b><?php echo translate('phone'); ?></b></td>
                                                            <td><?php echo $info['phone']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b><?php echo translate('e-mail'); ?></b></td>
                                                            <td><?php echo $info['email']; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <!--Panel heading-->
                                            <div class="panel panel-bordered-grey shadow-none">
                                                <div class="panel-heading">
                                                    <h1 class="panel-title"><?php echo translate('payment_detail'); ?></h1>
                                                </div>
                                                <!--List group-->
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td><b><?php echo translate('payment_status'); ?></b></td>
                                                            <td><?php echo translate($this->crud_model->sale_payment_status($row['sale_id'], 'admin')); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b><?php echo translate('payment_method'); ?></b></td>
                                                            <td>
                                                                <?php
                                                                $payment_type = $row['payment_type'];

                                                                if ($payment_type == 'c2') {
                                                                    echo 'TwoCheckout';
                                                                } else if ($payment_type == 'pum') {
                                                                    echo "Payumoney";
                                                                } else {
                                                                    echo ucfirst(str_replace('_', ' ', $payment_type));
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><b><?php echo translate('payment_date'); ?></b></td>
                                                            <td><?php echo date('d M, Y', $row['sale_datetime']); ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right print_btn">
                                <span class="btn btn-success pull-right btn-md btn-labeled fa fa-reply margin-top-10" onclick="print_invoice('print_div_quart_<?php echo $brand_id; ?>')">
                                    <?php echo translate('print'); ?>&nbsp;(<?php echo $row['sale_code']; ?> / A)
                                </span>
                            </div>
                        </div>
                        <br>
                        <?php } ?>
                    </div>
                        <?php if(count($productid_by_brand)>1){ ?>
                        <div class="row">
                            <div class="col-md-12 text-right print_btn">
                                <span class="btn btn-success pull-right btn-md btn-labeled fa fa-reply margin-top-10" onclick="print_invoice('quart')">
                                    <?php echo translate('print_all'); ?>
                                </span>
                            </div>
                        </div>
                        <?php } ?>

                </div>
                <div class="row" style="height:300px;" id="mapa"></div>
            </div>
            <div class="col-md-2"></div>
    </div>
</div>
<!--End Invoice Footer-->
<?php
            $position = explode(',', str_replace('(', '', str_replace(')', '', $info['langlat'])));
?>

<script>
    $.getScript("http://maps.google.com/maps/api/js?v=3.exp&signed_in=true&callback=MapApiLoaded&key=<?php echo $this->db->get_where('general_settings', array('type' => 'google_api_key'))->row()->value; ?>", function() {});

    function MapApiLoaded() {
        var map;

        function initialize() {
            var mapOptions = {
                zoom: 16,
                center: {
                    lat: <?php echo $position[0]; ?>,
                    lng: <?php echo $position[1]; ?>
                }
            };
            map = new google.maps.Map(document.getElementById('mapa'),
                mapOptions);

            var marker = new google.maps.Marker({
                position: {
                    lat: <?php echo $position[0]; ?>,
                    lng: <?php echo $position[1]; ?>
                },
                map: map
            });

            var infowindow = new google.maps.InfoWindow({
                content: '<p><?php echo translate('marker_location'); ?>:</p><p><?php echo $info['address1']; ?> </p><p><?php echo $info['address2']; ?> </p><p><?php echo translate('city'); ?>: <?php echo $info['city']; ?> </p><p><?php echo translate('ZIP'); ?>: <?php echo $info['zip']; ?> </p>'
            });
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map, marker);
            });
        }
        initialize();
    }
</script>

<?php
        }
?>
<script>
function print_invoice(div_id)
{
    var printContents = document.getElementById(div_id).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
<style>
    @media print {
        .print_btn {
            display: none;
        }
        .print {
        width: 100%;
        }

        #navbar-container {
            display: none;
        }

        #page-title {
            display: none;
        }
        #filterg {
            display: none;
        }
        #mapa {
            display: none;
        }
        #footer{
            display: none;
        }

        .panel-heading {
            display: none;
        }
        .brand-icon{
            display: none;
        }

        .print {
            width: 100%;
        }

        .col-md-6 {
            width: 50%;
            float: left;
        }
            button#scroll-top {
            display: none;
        }
    }
      @media print {
    body, html {
   margin:0;
   padding:0;
   border:0 !important;
   }
 
   .pad-all {
	padding: 15px;
    }
   
   .print_btn, .hide_onPr{
   display:none;	
   }
   
   .panel-body, .panel-body.ssv{
   padding:0px 15px !important;
   }
   
   .col-md-8.print, .col-md-6{
   padding-left:5px !important;
   padding-right:5px !important;
   width: 100%;
   }
   
   .bordered.print{
   padding:2px !important;
   border:0px !important;
   }
   
   .dnes{
   display:block;
   }
   .panel-title{
   padding:3px !important;
   font-size:10px;
   }
   .panel.panel-bordered-grey, .panel{
   margin-bottom:15px !important; 
   margin-top:0px !important;
   }
   .printwe .hde{
   display:block !important;
   height:10px !important;
   }
   .table tr td p{
   margin-bottom:0px !important;
   }
   #mapa{
   display:none;
   }
   .tb_prt.table thead tr th{
   border-color:#ddd !important;
   border:0 !important;
   }
   .tab-content{
   padding:2px;
   }
   #navbar-container{
   display: none;
   }
   #page-title{
   display: none;
   }
   #mapa{
   display: none;
   }
   .printwe .panel-heading, .printwe .panel.panel-bordered-grey{
   border:0px !important;
   text-align:left !important;
   }
   .printwe .panel-heading h1{
   font-size:10px !important;
   text-align:left !important;
   }
   .printwe .panel-title{
   padding-left:0px;
   }
   .printwe .print{
   width: 100%;
   }
   .printwe .col-md-6{
    margin-top: 5px;   
   width: 100%;
   float: none;
   }
   .printwe .hsx{
   width:50%;
   display:block;
   }
   .printwe .pad-all.w-100-d{
   width:100%;
   display:block;
   }
   .printwe #demo_s .panel-bordered-dark, .printwe #demo_s .panel-dark.panel-bordered, .printwe .panel > .table-bordered, .printwe .panel > .table-responsive > .table-bordered {
   padding:0px;
   }
   .printwe .col-lg-6, .printwe .col-md-6{
   padding:0px;
   }
   .printwe .table tr td, .printwe .table tr td p{
   padding:2px !important;
   margin:0px !important;
   border:0px;
   }
   .printwe .panel{
   margin-top:4px !important;
   margin-bottom:4px !important;
   }
   .printwe .tab-content, .printwe #demo-s{
   padding:0px;
   }
   .printwe .table-bordered.tb_prt{
   margin:0px;
   padding:2px;
   display:block;
   }
   .printwe .table-bordered.tb_prt thead tr th, .printwe .table-bordered.tb_prt tbody tr td{
   border:0!important;
   padding:2px !important;
   margin:0px !important;
   font-size:10px !important;
   }
   .printwe .table-bordered.tb_prt {
   border-collapse: collapse;
   border-spacing: 0;
   }
   .printwe .table-bordered.tb_prt > tbody > tr > td, .printwe .table-bordered.tb_prt > tbody > tr > th, .printwe .table-bordered.tb_prt > tfoot > tr > td,.printwe .table-bordered.tb_prt > tfoot > tr > th, .printwe .table-bordered.tb_prt > thead > tr > td, .printwe .table-bordered.tb_prt > thead > tr > th {
   border:0;
   padding:2px !important;
   }
   .printwe .table-responsive{
   overflow:visible;
   }
   /* .tb_prt thead tr th, #container .tb_prt td, #container .tb_prt th{
   border:1px solid #ddd !important;
   border-color:#ddd !important;
   } */
   #container .table td, #container .table th {
   border-color: #ddd !important;
   }
   .only_ppr{
   display:none !important;
   }
   .only_print, tr.only_print{
   border:0 !important;
   border-color:#ddd !important;
   width:100% !important;
   text-align: left;
   display:block !important;
   height:auto;
   padding:4x;
   text-align: left;
   }
   .p-30{
   padding:4px !important;
   margin-top:-4px !important;
   }
   .brand-icon{
   display:none !important;
   }
   .tab-content, .col-lg-6, .col-lg-12, .col-sm-12, .col-md-8, .col-lg-8, .col-lg-6, .col-md-6, .col-sm-6{
   padding:2px !important;
   }
   .ss{
   display:block !important;
   width:100%;
   }
   .table tr td, .table tr td p{
   padding:2px !important;
   margin-top:-6px !important;
   border:0px !important;
   }
   .panel-bordered-grey, .panel-grey.panel-bordered, .b-00, .b-00.panel-bordered,  .panel-grey.b-00, .panel-bordered-grey.b-00{
   border:0px !important;
   }
   .b-00 .panel-title{
   margin-top:-12px !important;
   margin-bottom:0px !important;
   padding-left:3px !important;
   }
   .logo{
   padding:7px !important;
   margin-left:70px !important;
   width:70px;
   }
.pull-right {
    float: left !important;
}
   .fosml{
   font-size:8px;
   }
   .ssv{
   margin-left:-10px;
   margin-right:-10px;
   }
   .br_nil{
   border:0px !important;
   }
   .plx{
   float: left;
   display: block;
   position: relative;
   left: -4.25em;
   margin-top:-20px;
   }
   .bf_span{
   position:relative;
   }
   .bf_span .bf_span_bff::before {
   content: ":";
   position: absolute;
   top: -1px;
   /* left: 0px; */
   right: -5px;
   }
   .panel>.panel-heading:after, .panel.panel-colorful>.panel-heading:after {
   content: '';
   display: block;
   position: absolute;
   height: 0;
   left: 3px;
   right: 12px;
   top:35px;
   border-bottom: 1px solid rgba(0,0,0,0.15);
   }
table.table.table-bordered.table-striped {
    margin-left: -70px;
}
.panel.panel-colorful.panel-grey.shadow-none {
    width: 100%;
}
   #footer, #scroll-top{
   display:none;
   }
   .bbss {
   display: block;
   margin: 0px auto;
   text-align: center;
   font-size: 9px;
   }
   a[href]:after {
   content: " (" attr(href) ")";
   }
   a[href]:after {
   content: none !important;
   }
   }
   @page {
   size: auto;   
   margin: 0;
   padding: 0;
   }
   .pull-right-sn {
       float:right !important;
   }
   
   
   @media only screen and (max-width: 400px) {
   .pull-right-sn {
       float:left !important;
   }
}

</style>
















