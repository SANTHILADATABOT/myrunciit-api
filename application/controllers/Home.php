<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller
{

    /*  
     *  Developed by: Active IT zone
     *  Date    : 14 July, 2015ipa
     *  Active Supershop eCommerce CMS
     *  http://codecanyon.net/user/activeitezone
     */

    function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
        $this->load->database();

        $this->load->library('paypal');
        $this->load->library('twoCheckout_Lib');
        $this->load->library('vouguepay');
        $this->load->library('someclass');
        $this->load->library('pum');
        // Set timezone
        date_default_timezone_set('Asia/Kuala_Lumpur');


        /*cache control*/
        //ini_set("user_agent","My-Great-Marketplace-App");
        $cache_time     =  $this->db->get_where('general_settings', array('type' => 'cache_time'))->row()->value;
        if (!$this->input->is_ajax_request()) {
            $this->output->set_header('HTTP/1.0 200 OK');
            $this->output->set_header('HTTP/1.1 200 OK');
            $this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
            $this->output->set_header('Cache-Control: post-check=0, pre-check=0');
            $this->output->set_header('Pragma: no-cache');
            if (
                $this->router->fetch_method() == 'index' ||
                $this->router->fetch_method() == 'featured_item' ||
                $this->router->fetch_method() == 'others_product' ||
                $this->router->fetch_method() == 'all_brands' ||
                $this->router->fetch_method() == 'all_category' ||
                $this->router->fetch_method() == 'all_vendor' ||
                $this->router->fetch_method() == 'blog' ||
                $this->router->fetch_method() == 'blog_view' ||
                $this->router->fetch_method() == 'vendor' ||
                $this->router->fetch_method() == 'category'
            ) {
                $this->output->cache($cache_time);
            }
        }
        $this->config->cache_query();
        $currency = $this->session->userdata('currency');
        if (!isset($currency)) {
            $this->session->set_userdata('currency', $this->db->get_where('business_settings', array('type' => 'home_def_currency'))->row()->value);
        }
       // setcookie('lang', $this->session->userdata('language'), time() + (86400), "/");
       // setcookie('curr', $this->session->userdata('currency'), time() + (86400), "/");
        //echo $_COOKIE['lang'];
    }
    // setting some variables for lalamove
    public $hostname = 'rest.sandbox.lalamove.com/v3';
    public $apiKey = 'pk_test_13334160b783cdb84d52d58123083e7a';
    public $secret = 'sk_test_91aDqy1/Uo8cB/873iKbEQ3IQjUpFKzNfhbOG8VN6Fp3LDApJEsgVR279kfCqcJY';
    public $market = 'MY';
    public $signature = '';
    public $time = null;
    public $body = null;
    public $total = null;
    public $currency = 'RM';
    public $orderId = null;
    public $quotationId = null;
    public $serviceType = 'MOTORCYCLE';
    public $language = 'EN_MY';
    public $specialRequests = ["DOOR_TO_DOOR"];
    // 1.489374, 
    public $stops = [
        [
            'coordinates' => ['lat' => '1.494265', 'lng' => '103.744422'],
            'address' => 'Larkin Jaya, 80350 Johor Bahru, Johor, Malaysia'
        ],
        [
            'coordinates' => ['lat' => '1.489374', 'lng' => '103.751763'],
            'address' => '4, Jalan Dato Jaafar, Taman Dato Onn, 80350 Johor Bahru, Johor, Malaysia'
        ]
    ];
    public $isRouteOptimized = false;
    public $item = [
        "quantity" => "1",
        "weight" => "LESS_THAN_3_KG",
        "categories" => [
            "FOOD_DELIVERY",
            "OFFICE_ITEM"
        ],
        "handlingInstructions" => [
            "KEEP_UPRIGHT"
        ]
    ];

    /* FUNCTION: Loads Homepage*/
    public function index()
    {
        echo $this->session->set_userdata('bidding_stock', '');
        //$this->output->enable_profiler(TRUE);
        //$page_data['min'] = $this->get_range_lvl('product_id !=', '', "min");
        //$page_data['max'] = $this->get_range_lvl('product_id !=', '', "max");
        $home_style =  $this->db->get_where('ui_settings', array('type' => 'home_page_style'))->row()->value;
        $page_data['page_name']     = "home/home" . $home_style;
        $page_data['asset_page']    = "home";
        $page_data['page_title']    = translate('home');
        $page_data['timeList'] = $this->createTimeSlots(60, '00:00', '23:00');
        $this->benchmark->mark('code_start');
        $this->load->view('front/index', $page_data);

        // Some code happens here

        $this->benchmark->mark('code_end');
    }

    function getNearestVendorName($pin='')
    {
        if ($pin == '') {
                    $pin = $this->input->post('pin');
                }

        $name = '';
        if ($pin != '') {
            // echo "PIN-".$pin."<br>";
        $this->db->select('vendor_id, name, zip, latitude, longitude');
        $this->db->from('vendor');
        $this->db->where('zip', $pin);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();
            $name = $result->name;
            $id = $result->vendor_id;
            $vendorName = $name;
            $vendorid = $id;
            // echo "VendorName--".$vendorName."<br>";
            
            // Store $vendorName in the session
            $this->session->set_userdata('vendorName', $vendorName);
            $this->session->set_userdata('vendorid',  $vendorid);
            
            $page_data['vendorName'] = $vendorName;
            $home_style =  $this->db->get_where('ui_settings', array('type' => 'home_page_style'))->row()->value;
            $page_data['page_name']     = "home/home" . $home_style;
            $page_data['asset_page']    = "home";
            $page_data['page_title']    = translate('home');
            
            $page_data['timeList'] = $this->createTimeSlots(60, '00:00', '23:00');
            $this->benchmark->mark('code_start');
            $this->load->view('front/index', $page_data);
            $this->load->view('front/header/header_1', $page_data);

            }
            else{

                
                $pin = $this->input->post('pin');
                $this->db->select('id, postal_code, latitude, longitude');
                $this->db->from('zipcodes');
                $this->db->where('postal_code', $pin);
                $query = $this->db->get();
                
                if ($query->num_rows() > 0) {
                    $result = $query->row();
                
                    $this->db->select('vendor_id, name, zip, latitude, longitude');
                    $this->db->from('vendor');
                    $vendors = $this->db->get()->result_array();
                
                    $zipcodeLatitude = $result->latitude;
                    $zipcodeLongitude = $result->longitude;
                
                    $shortestDistance = PHP_INT_MAX; // Initialize with a very large distance
                    $nearestVendor = null;
                
                    foreach ($vendors as $vendor) {
                        $vendorLatitude = $vendor['latitude'];
                        $vendorLongitude = $vendor['longitude'];
                
                        $long1 = deg2rad($zipcodeLongitude);
                        $lat1 = deg2rad($zipcodeLatitude);
                
                        $long2 = deg2rad($vendorLongitude);
                        $lat2 = deg2rad($vendorLatitude);
                
                        $dlong = $long2 - $long1;
                        $dlati = $lat2 - $lat1;
                
                        $val = pow(sin($dlati/2), 2) + cos($lat1) * cos($lat2) * pow(sin($dlong/2), 2);
                        $res = 2 * asin(sqrt($val));
                        $radius = 6371; // Earth's radius in kilometers
                        $distance = $res * $radius;
                
                        if ($distance <= 15 && $distance < $shortestDistance) {
                            $shortestDistance = $distance;
                            $nearestVendor = $vendor['name'];
                        }
                    }
                
                    if ($nearestVendor !== null) {
                       $vendorName = $nearestVendor;
                       $page_data['vendorName'] = $vendorName;
                       $home_style =  $this->db->get_where('ui_settings', array('type' => 'home_page_style'))->row()->value;
                       $page_data['page_name']     = "home/home" . $home_style;
                       $page_data['asset_page']    = "home";
                       $page_data['page_title']    = translate('home');
                       $page_data['timeList'] = $this->createTimeSlots(60, '00:00', '23:00');
                       $this->benchmark->mark('code_start');
                       $this->load->view('front/index', $page_data);
                       $this->load->view('front/header/header_1', $page_data);
                    } else {
                        //  echo "ABOVE";
                        $vendorId2 = 2;
                        $this->db->select('name');
                        $this->db->from('vendor');
                        $this->db->where('vendor_id', $vendorId2);
                        $query2 = $this->db->get();
                        $result2 = $query2->row();
                        $defvendorName = $result2->name;
                        $page_data['defvendorName'] = $defvendorName;
                        
                        $above15kms = "Oops! We don't serve in this area. Please try a different zip code.";
                        
                        $page_data['above15kms'] = $above15kms;
                        $this->session->set_userdata('above15kms', $above15kms);
                        // echo '<script>showAbove15kmsModal("'.$above15kms.'");</script>';
                        $this->session->set_userdata('showAbove15kmsModal', true);
                        $home_style =  $this->db->get_where('ui_settings', array('type' => 'home_page_style'))->row()->value;
                        $page_data['page_name']     = "home/home" . $home_style;
                        $page_data['asset_page']    = "home";
                        $page_data['page_title']    = translate('home');
                        $page_data['timeList'] = $this->createTimeSlots(60, '00:00', '23:00');
                        $this->benchmark->mark('code_start');
                        $this->load->view('front/index', $page_data);
                        $this->load->view('front/header/header_1', $page_data);
                        // $this->session->set_userdata('above15kms', $above15kms);
                        // echo '<script>showAbove15kmsModal("'.$above15kms.'");</script>';
                        // $this->session->set_userdata('above15kmsModal', true);
                        // echo $above15kms;

                    }
                } else {

                    $invalidCode = "Invalid Postal Code!!";
                    $page_data['invalidCode'] = $invalidCode;
                    $this->session->set_userdata('invalidCode', $invalidCode);
                    //   echo "Invalid postal code.";
                    $vendorId2 = 2;
                    $this->db->select('name');
                    $this->db->from('vendor');
                    $this->db->where('vendor_id', $vendorId2);
                    $query2 = $this->db->get();
                    if ($query2->num_rows() > 0) {
                        $result2 = $query2->row();
                        $vendorName = $result2->name;
                        $page_data['vendorName'] = $vendorName;
                        $home_style =  $this->db->get_where('ui_settings', array('type' => 'home_page_style'))->row()->value;
                        $page_data['page_name']     = "home/home" . $home_style;
                        $page_data['asset_page']    = "home";
                        $page_data['page_title']    = translate('home');
                        $page_data['timeList'] = $this->createTimeSlots(60, '00:00', '23:00');
                        $this->benchmark->mark('code_start');
                        $this->load->view('front/index', $page_data);
                        $this->load->view('front/header/header_1', $page_data);
                    }
                    
    
                
                }
        }

/////////////////////////////////////////////////////////
        }
    }

    function getAllCoupon()
    {
        $allCoupons = $this->db->get_where('coupon', ['status' => 'ok'])->result_array();
        echo json_encode($allCoupons);
    }
    function checkMaxOrderInTimeSlot()
    {
        $max_order = $this->input->post('max_order');
        $pickup_slot = $this->input->post('pickup_slot');
        $pickup_date = $this->input->post('pickup_date');
        $result = $this->db->get_where('sale', [
            'pickup_slot' => $pickup_slot,
            'pickup_date' => $pickup_date,
            'order_type' => 'pickup',
            'status' => 'admin_pending',
        ])->result_array();
        // print_r(count($result));
        if (count($result) <= $max_order) {
            echo 1;
        } else {
            echo -1;
        }
    }


    function setSessionData()
    {
        $keyArray = explode(',', $this->input->post('keys'));
        foreach ($keyArray as $item) {
            $this->session->set_userdata($item, $this->input->post($item));
        }
        //  $this->session->unset_userdata('pickup');
        print_r($this->session);
    }

    function getPickupDetailAsVendor()
    {
        try {

            $vendorId = $this->input->post('vendorId');
            $pickupDetail = $this->db->get_where('pickup_slot', ['vendor_id' => $vendorId])->result_array();
            // print_r('intervel '. $pickupDetail[0]['interval_in_minute'] );
            // exit;
            $timeList = $this->createTimeSlots($pickupDetail[0]['interval_in_minute'], '00:00', '23:00');
            // print_r($timeList);
            // exit;
            $tempData = [
                'timeList' => $timeList,
                'pickupDetail' => $pickupDetail
            ];
            echo json_encode($tempData);
        } catch (Exception $e) {
            echo '' . $e->getMessage();
        }
    }
    function createTimeSlots($interval, $start_time, $end_time)
    {
        $start = strtotime($start_time);
        $end = strtotime($end_time);
        $timeList = [];
        $i = 0;
        while ($start <= $end) {

            $i++;
            $timeList[$i]['slot_start_time'] = date('g:i a', $start);
            $endTime = $start + $interval * 60;
            $timeList[$i]['slot_end_time'] = date('g:i a', $endTime);
            $start = $endTime;
        }
        return $timeList;
    }

    /**
     * Encrypt a message
     *
     * @param string $message - message to encrypt
     * @param string $key - encryption key
     * @return string
     */
    function safeEncrypt($message, $key)
    {
        $nonce = random_bytes(
            SODIUM_CRYPTO_SECRETBOX_NONCEBYTES
        );

        $cipher = base64_encode(
            $nonce .
                sodium_crypto_secretbox(
                    $message,
                    $nonce,
                    $key
                )
        );
        sodium_memzero($message);
        sodium_memzero($key);
        print_r($cipher);
        return $cipher;
    }

    /**
     * Decrypt a message
     *
     * @param string $encrypted - message encrypted with safeEncrypt()
     * @param string $key - encryption key
     * @return string
     */
    function safeDecrypt($encrypted, $key)
    {
        $decoded = base64_decode($encrypted);
        if ($decoded === false) {
            throw new Exception('Scream bloody murder, the encoding failed');
        }
        if (mb_strlen($decoded, '8bit') < (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES)) {
            throw new Exception('Scream bloody murder, the message was truncated');
        }
        $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $plain = sodium_crypto_secretbox_open(
            $ciphertext,
            $nonce,
            $key
        );
        if ($plain === false) {
            throw new Exception('the message was tampered with in transit');
        }
        sodium_memzero($ciphertext);
        sodium_memzero($key);
        return $plain;
    }

    function top_bar_right()
    {
        $this->load->view('front/components/top_bar_right.php');
    }
    public function getQuotation()
    {
        
        $data = [
            'serviceType' => $this->serviceType,
            'language' => $this->language,
            'stops' => $this->stops,
            'item' => $this->item
        ];
        $url = 'https://' . $this->hostname . '/quotations';
        $this->time = (time()*1000);
        $string = "{".$this->time."}\r\n{POST}\r\n{/v2/orders}\r\n\r\n{".json_encode($data)."}";
        $this->signature = base64_encode(hash_hmac('sha256', $string, $this->secret, true));
        $authorization = 'hmac ' . $this->apiKey . ':' . $this->time . ':' . $this->signature;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type' => 'application/json',
            'Authorization' => $authorization,
            'Market' => $this->market
        ]);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {$error_msg = curl_error($ch);}
        if (isset($error_msg)) {print_r($error_msg);}
        curl_close($ch);
        echo '<pre>';
        print_r($result);
    }


    function load_portion($page = '')
    {
        $page = str_replace('-', '/', $page);
        $this->load->view('front/' . $page);
    }

    function vendor_profile($para1 = '', $para2 = '')
    {
        if ($this->crud_model->get_settings_value('general_settings', 'vendor_system') !== 'ok') {
            redirect(base_url() . 'index.php/home');
        }
        if ($para1 == 'get_slider') {
            $page_data['vendor_id']            = $para2;
            $this->db->where("status", "ok");
            $this->db->where('added_by', json_encode(array('type' => 'vendor', 'id' => $para2)));
            $page_data['sliders']       = $this->db->get('slides')->result_array();
            $this->load->view('front/vendor/public_profile/home/slider', $page_data);
        } else {
            $status = $this->db->get_where('vendor', array('vendor_id' => $para1))->row()->status;
            if ($status !== 'approved') {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_title']        = $this->crud_model->get_type_name_by_id('vendor', $para1, 'display_name');
            $page_data['asset_page']        = "vendor_public_home";
            $page_data['page_name']            = "vendor/public_profile";
            $page_data['content']            = "home";
            $this->db->where("status", "ok");
            $this->db->where('added_by', json_encode(array('type' => 'vendor', 'id' => $para1)));
            $page_data['sliders']       = $this->db->get('slides')->result_array();
            $page_data['vendor_info']       = $this->db->get_where('vendor', array('vendor_id' => $para1))->result_array();
            $page_data['vendor_tags']       = $this->db->get_where('vendor', array('vendor_id' => $para1))->row()->keywords;
            $page_data['vendor_id']            = $para1;
            $this->load->view('front/index', $page_data);
        }
    }
    /* FUNCTION: Loads Category filter page */
    function vendor_category($vendor, $para1 = "", $para2 = "", $min = "", $max = "", $text = '')
    {
        if ($this->crud_model->get_settings_value('general_settings', 'vendor_system') !== 'ok') {
            redirect(base_url() . 'index.php/home');
        }
        if ($para2 == "") {
            $page_data['all_products'] = $this->db->get_where('product', array(
                'category' => $para1
            ))->result_array();
        } else if ($para2 != "") {
            $page_data['all_products'] = $this->db->get_where('product', array(
                'sub_category' => $para2
            ))->result_array();
        }

        $brand_sub = explode('-', $para2);

        $sub     = 0;
        $brand  = 0;

        if (isset($brand_sub[0])) {
            $sub = $brand_sub[0];
        }
        if (isset($brand_sub[1])) {
            $brand = $brand_sub[1];
        }

        $page_data['range']            = $min . ';' . $max;
        $page_data['page_name']        = "vendor/public_profile";
        $page_data['content']          = "product_list";
        $page_data['asset_page']       = "product_list_other";
        $page_data['page_title']       = translate('products');
        $page_data['all_category']     = $this->db->get('category')->result_array();
        $page_data['all_sub_category'] = $this->db->get('sub_category')->result_array();
        $page_data['cur_sub_category'] = $sub;
        $page_data['cur_brand']        = $brand;
        $page_data['cur_category']     = $para1;
        $page_data['vendor_id']        = $vendor;
        $page_data['text']             = $text;
        $page_data['category_data']    = $this->db->get_where('category', array(
            'category_id' => $para1
        ))->result_array();
        $this->load->view('front/index', $page_data);
    }

    function vendor_featured($para1 = '', $para2 = '')
    {
        
        if ($this->crud_model->get_settings_value('general_settings', 'vendor_system') !== 'ok') {
            redirect(base_url() . 'index.php/home');
        }
      
        if ($para1 == 'get_list') {
            $page_data['vendor_id']            = $para2;
            $this->load->view('front/vendor/public_profile/featured/list_page', $page_data);
        } elseif ($para1 == 'get_ajax_list') {
            $this->load->library('Ajax_pagination');

            $vendor_id = $this->input->post('vendor');

            $this->db->where('status', 'ok');
            $this->db->where('featured', 'ok');
            $this->db->where('added_by', json_encode(array('type' => 'vendor', 'id' => $vendor_id)));
            // pagination
            $config['total_rows'] = $this->db->count_all_results('product');
            $config['base_url']   = base_url() . 'index.php?home/listed/';
            $config['per_page'] = 50;
            $config['uri_segment']  = 5;
            $config['cur_page_giv'] = $para2;

            $function                  = "filter_blog('0')";
            $config['first_link']      = '&laquo;';
            $config['first_tag_open']  = '<li><a onClick="' . $function . '">';
            $config['first_tag_close'] = '</a></li>';

            $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
            $last_start               = floor($rr) * $config['per_page'];
            $function                 = "filter_vendor_featured('" . $last_start . "')";
            $config['last_link']      = '&raquo;';
            $config['last_tag_open']  = '<li><a onClick="' . $function . '">';
            $config['last_tag_close'] = '</a></li>';

            $function                 = "filter_vendor_featured('" . ($para2 - $config['per_page']) . "')";
            $config['prev_tag_open']  = '<li><a onClick="' . $function . '">';
            $config['prev_tag_close'] = '</a></li>';

            $function                 = "filter_vendor_featured('" . ($para2 + $config['per_page']) . "')";
            $config['next_link']      = '&rsaquo;';
            $config['next_tag_open']  = '<li><a onClick="' . $function . '">';
            $config['next_tag_close'] = '</a></li>';

            $config['full_tag_open']  = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';

            $config['cur_tag_open']  = '<li class="active"><a>';
            $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

            $function                = "filter_vendor_featured(((this.innerHTML-1)*" . $config['per_page'] . "))";
            $config['num_tag_open']  = '<li><a onClick="' . $function . '">';
            $config['num_tag_close'] = '</a></li>';
            $this->ajax_pagination->initialize($config);

            $this->db->where('status', 'ok');
            $this->db->where('featured', 'ok');
            $this->db->where('added_by', json_encode(array('type' => 'vendor', 'id' => $vendor_id)));

            $page_data['products'] = $this->db->get('product', $config['per_page'], $para2)->result_array();
            $page_data['count']              = $config['total_rows'];

            $this->load->view('front/vendor/public_profile/featured/ajax_list', $page_data);
        } else {
            $page_data['page_title']        = translate('vendor_featured_product');
            $page_data['asset_page']        = "product_list_other";
            $page_data['page_name']            = "vendor/public_profile";
            $page_data['content']            = "featured";
            $page_data['vendor_id']            = $para1;
            $this->load->view('front/index', $page_data);
        }
    }
    function all_vendor()
    {
        if ($this->crud_model->get_settings_value('general_settings', 'vendor_system') !== 'ok') {
            redirect(base_url() . 'index.php/home');
        }
        $page_data['page_name']        = "vendor/all";
        $page_data['asset_page']       = "all_vendor";
        $page_data['page_title']       = translate('all_vendors');
        $this->load->view('front/index', $page_data);
    }

    function all_vendor_cat($para1 = '')
    {
        //echo $para1;
        if ($this->crud_model->get_settings_value('general_settings', 'vendor_system') !== 'ok') {
            redirect(base_url() . 'index.php/home');
        }
        $page_data['vendor_system_all']     =  $this->db->get_where('product', array('category' => $para1))->result_array();
        $page_data['vendor_system_cat']     =  $this->db->get_where('category', array('category_id' => $para1))->result_array();
        //echo $this->db->last_query();
        //print_r($page_data['vendor_system_all']); exit;
        $page_data['page_name']        = "vendor_cat/all";
        $page_data['asset_page']       = "all_vendor_cat";
        $page_data['page_title']       = translate('all_vendors_cat');
        $this->load->view('front/index', $page_data);
    }
    function vendor($vendor_id)
    {
        if ($this->crud_model->get_settings_value('general_settings', 'vendor_system') !== 'ok') {
            redirect(base_url() . 'index.php/home');
        }
        $vendor_system     =  $this->db->get_where('general_settings', array('type' => 'vendor_system'))->row()->value;
        if (
            $vendor_system     == 'ok' &&
            $this->db->get_where('vendor', array('vendor_id' => $vendor_id))->row()->status == 'approved'
        ) {
            $min = $this->get_range_lvl('added_by', '{"type":"vendor","id":"' . $vendor_id . '"}', "min");
            $max = $this->get_range_lvl('added_by', '{"type":"vendor","id":"' . $vendor_id . '"}', "max");
            $this->db->order_by('product_id', 'desc');
            $page_data['featured_data'] = $this->db->get_where('product', array(
                'featured' => "ok",
                'status' => 'ok',
                'added_by' => '{"type":"vendor","id":"' . $vendor_id . '"}'
            ))->result_array();
            $page_data['range']             = $min . ';' . $max;
            $page_data['all_category']      = $this->db->get('category')->result_array();
            $page_data['all_sub_category']  = $this->db->get('sub_category')->result_array();
            $page_data['page_name']         = 'vendor_home';
            $page_data['vendor']            = $vendor_id;
            $page_data['page_title']        = $this->db->get_where('vendor', array('vendor_id' => $vendor_id))->row()->display_name;
            $this->load->view('front/index', $page_data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }


    function surfer_info()
    {
        $this->crud_model->ip_data();
    }

    function wallet_paypal_success()
    {

        $id = $this->session->userdata('wallet_id');
        $page_data['wallet'] = $this->db->get_where('wallet_load', array('wallet_load_id' => $id))->result_array();
        $user = $page_data['wallet'][0]['user'];
        $amount = $page_data['wallet'][0]['amount'];

        $page_data['old_wallet'] = $this->db->get_where('user', array('user_id' => $user))->result_array();
        $old_amt = $page_data['old_wallet'][0]['wallet'];
        $new_amt = $old_amt + $amount;

        $data['wallet'] = $new_amt;
        $this->db->where('user_id', $user);
        $this->db->update('user', $data);
        $this->session->set_userdata('wallet_id', '');

        $from = 'demo.net';
        $to = $this->session->userdata('user_email');
        $subject = 'Acknowledgement for wallet transfer';
        $message = "<html><head><meta http-equiv=Content-Type content=text/html; charset=utf-8/><title>Oyabuy.net</title>
</head><body><table width=500 cellpadding=0 cellspacing=0 border=0 bgcolor=#F49E23 style=border:solid 10px #A5DCFF;><tr bgcolor=#FFFFFF height=25><td><table width=500 cellpadding=0 cellspacing=0 border=0 bgcolor=#F49E23 style=border:solid 10px #a5dcff;><tr bgcolor=#FFFFFF height=30><td height=27 valign=top style=font-family:Arial; font-size:12px; line-height:18px; text-decoration:none; color:#000000; padding-left:20px;><b>Wallet Transfer Acknowledgement</b></td>
</tr><tr bgcolor=#FFFFFF height=35><td height=24 style=padding-left:20px; font-family:arial; font-size:11px; line-height:18px; text-decoration:none; color:#000000;> NGN '" . $amount . "' Added your wallet</td></tr><tr bgcolor=#FFFFFF height=35><td height=23 style=padding-left:20px; font-family:arial; font-size:11px; line-height:18px; text-decoration:none; color:#000000;>Thanks for using oyabuy.net</td></tr></table></td></tr></table><body/><html/>";

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
        $headers .= "From: Oyabuy.net <info@oyabuy.net>\r\n";
        $email_response = mail($to, $subject, $message, $headers);




        redirect(base_url() . 'index.php/home/profile/part/wallet/', 'refresh');
    }


    function getVendorsBasedOnZipCode($param = '')
    {
        $this->db->select('*');
        $this->db->from('vendor');
        $this->db->where('status', 'approved');
        $this->db->where('delivery', 'yes');
        $this->db->like('delivery_zipcode', $param);
        $allStores = $this->db->get()->result_array();
        if (!empty($allStores)) {
            // print_r($allStores);
            $this->session->set_userdata('user_zips', $param);
            //   echo "dl".$this->session->userdata('user_zips'); 
            $this->session->set_userdata('pickup', "");
            // echo  "pk".$this->session->userdata('pickup'); 

        }


        // $tempArr = array_filter($allStores, function ($item)
        // {
        //     return $item;
        // });
        // return $tempArr;
        echo json_encode($allStores);
    }

    /* FUNCTION: Loads Customer Profile Page */
    function profile($para1 = "", $para2 = "", $para3 = "")
    {
        if ($this->session->userdata('user_login') != "yes") {
            redirect(base_url(), 'refresh');
        }
        if ($para1 == "info") {
            $page_data['user_info']     = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->result_array();
            $this->load->view('front/user/profile', $page_data);
        } elseif ($para1 == "wishlist") {
            $this->load->view('front/user/wishlist');
        } elseif ($para1 == "order_history") {
            $this->load->view('front/user/order_history');
        } elseif ($para1 == "wallet") {

            if ($this->crud_model->get_type_name_by_id('general_settings', '84', 'value') !== 'ok') {
                redirect(base_url() . 'home');
            }
            if ($para2 == "add_view") {
                $this->load->view('front/user/add_wallet');
            } else if ($para2 == "info_view") {
                $info = $this->db->get_where('wallet_load', array('wallet_load_id' => $para3))->row();
                $page_info['status'] = $info->status;
                $page_info['id'] = $para3;
                $page_info['payment_info'] = $info->payment_details;

                //echo $this->db->last_query();
                //$data['payment_info'] = $this->input->post('payment_info');
                // $this->db->where('wallet_load_id',$para3);
                //$this->db->update('user', $data); 
                $this->load->view('front/user/wallet_info', $page_info);
            } else if ($para2 == "add") {
                $grand_total = $this->input->post('amount');
                $amount_in_usd  = $grand_total;
                $method = $this->input->post('method_0');
                if ($method == 'paypal') {
                    $data['user']                   = $this->session->userdata('user_id');
                    $data['method']                 = $this->input->post('method_0');
                    $data['amount']                 = $grand_total;
                    $data['status']                 = 'due';
                    $data['payment_details']        = '[]';
                    $data['timestamp']              = time();


                    $data2['uid']                   = $this->session->userdata('user_id');
                    $data2['description']            = 'NGN ' . $grand_total . ' Credicted to your wallet';
                    $this->db->insert('user_log', $data2);

                    $this->db->insert('wallet_load', $data);
                    $id = $this->db->insert_id();
                    $this->session->set_userdata('wallet_id', $id);





                    $paypal_email = $this->crud_model->get_type_name_by_id('business_settings', '1', 'value');

                    /****TRANSFERRING USER TO PAYPAL TERMINAL****/
                    $this->paypal->add_field('rm', 2);
                    $this->paypal->add_field('no_note', 0);
                    $this->paypal->add_field('cmd', '_xclick');

                    //$this->paypal->add_field('amount', $this->cart->format_number($amount_in_usd));
                    $this->paypal->add_field('amount', $amount_in_usd);

                    //$this->paypal->add_field('amount', $grand_total);
                    $this->paypal->add_field('custom', $id);
                    $this->paypal->add_field('business', $paypal_email);
                    $this->paypal->add_field('notify_url', base_url() . 'index.php/home/wallet_paypal_ipn');
                    $this->paypal->add_field('cancel_return', base_url() . 'index.php/home/wallet_paypal_cancel');
                    $this->paypal->add_field('return', base_url() . 'index.php/home/wallet_paypal_success');

                    $this->paypal->submit_paypal_post();
                    // submit the fields to paypal
                } else if ($method == 'c2') {
                    $data['user']                   = $this->session->userdata('user_id');
                    $data['method']                 = $this->input->post('method_0');
                    $data['amount']                 = $grand_total;
                    $data['status']                 = 'due';
                    $data['payment_details']        = '[]';
                    $data['timestamp']              = time();
                    $this->db->insert('wallet_load', $data);
                    $id = $this->db->insert_id();
                    $this->session->set_userdata('wallet_id', $id);

                    $c2_user = $this->db->get_where('business_settings', array('type' => 'c2_user'))->row()->value;
                    $c2_secret = $this->db->get_where('business_settings', array('type' => 'c2_secret'))->row()->value;


                    $this->twocheckout_lib->set_acct_info($c2_user, $c2_secret, 'Y');
                    $this->twocheckout_lib->add_field('sid', $this->twocheckout_lib->sid);              //Required - 2Checkout account number
                    $this->twocheckout_lib->add_field('cart_order_id', $id);   //Required - Cart ID
                    $this->twocheckout_lib->add_field('total', $this->cart->format_number($amount_in_usd));

                    $this->twocheckout_lib->add_field('x_receipt_link_url', base_url() . 'index.php/home/wallet_twocheckout_success');
                    $this->twocheckout_lib->add_field('demo', $this->twocheckout_lib->demo);                    //Either Y or N

                    $this->twocheckout_lib->submit_form();
                } else if ($method == 'vp') {
                    $vp_id                  = $this->db->get_where('business_settings', array('type' => 'vp_merchant_id'))->row()->value;

                    $data['user']                   = $this->session->userdata('user_id');
                    $data['method']                 = $this->input->post('method_0');
                    $data['amount']                 = $grand_total;
                    $data['status']                 = 'due';
                    $data['payment_details']        = '[]';
                    $data['timestamp']              = time();
                    $this->db->insert('wallet_load', $data);
                    $id = $this->db->insert_id();
                    $this->session->set_userdata('wallet_id', $id);

                    /****TRANSFERRING USER TO vouguepay TERMINAL****/
                    $this->vouguepay->add_field('v_merchant_id', $vp_id);
                    $this->vouguepay->add_field('merchant_ref', $id);
                    $this->vouguepay->add_field('memo', 'Wallet Money Load');

                    $this->vouguepay->add_field('total', $amount_in_usd);

                    $this->vouguepay->add_field('notify_url', base_url() . 'index.php/home/wallet_vouguepay_ipn');
                    $this->vouguepay->add_field('fail_url', base_url() . 'index.php/home/wallet_vouguepay_cancel');
                    $this->vouguepay->add_field('success_url', base_url() . 'index.php/home/wallet_vouguepay_success');

                    $this->vouguepay->submit_vouguepay_post();
                    // submit the fields to vouguepay
                } else if ($method == 'stripe') {
                        
                    if ($this->input->post('stripeToken')) {

                        $stripe_api_key = $this->db->get_where('business_settings', array('type' => 'stripe_secret'))->row()->value;
                        require_once(APPPATH . 'libraries/stripe-php/init.php');
                        \Stripe\Stripe::setApiKey($stripe_api_key); //system payment settings
                        $user_email = $this->db->get_where('user', array('user_id' => $user))->row()->email;

                        $usera = \Stripe\Customer::create(array(
                            'email' => $user_email, // customer email id
                            'card'  => $_POST['stripeToken']
                        ));

                        $charge = \Stripe\Charge::create(array(
                            'customer'  => $usera->id,
                            'amount'    => ceil($amount_in_usd * 100),
                            'currency'  => 'USD'
                        ));

                        if ($charge->paid == true) {
                            $usera = (array) $usera;
                            $charge = (array) $charge;

                            $data['user']                   = $this->session->userdata('user_id');
                            $data['method']                 = $this->input->post('method_0');
                            $data['amount']                 = $grand_total;
                            $data['status']                 = 'paid';
                            $data['payment_details']        = "Customer Info: \n" . json_encode($usera, true) . "\n \n Charge Info: \n" . json_encode($charge, true);;
                            $data['timestamp']              = time();
                            $this->db->insert('wallet_load', $data);

                            $id = $this->db->insert_id();
                            $user = $this->db->get_where('wallet_load', array('wallet_load_id' => $id))->row()->user;
                            $amount = $this->db->get_where('wallet_load', array('wallet_load_id' => $id))->row()->amount;
                            $balance = base64_decode($this->db->get_where('user', array('user_id' => $user))->row()->wallet);
                            $new_balance = base64_encode($balance + $amount);
                            $this->db->where('user_id', $user);
                            $this->db->update('user', array('wallet' => $new_balance));

                            redirect(base_url() . 'index.php/home/profile/part/wallet/', 'refresh');
                        } else {
                            $this->session->set_flashdata('alert', 'unsuccessful_stripe');
                            redirect(base_url() . 'index.php/home/profile/part/wallet/', 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('alert', 'unsuccessful_stripe');
                        redirect(base_url() . 'index.php/home/profile/part/wallet/', 'refresh');
                    }
                } 
                else if($method =='ipay'){
                    
            $walletNo = 'WL' . substr(time(), 4) . rand(1, 10) . rand(1, 99);
            $request_data=[];

                   $request_data['user_id']=$this->session->userdata('user_id');;
                   $request_data['wallet_no']=$walletNo;
                   $request_data['amount']=$this->input->post('amount');
                   print_r($request_data);
                   $this->db->insert('ipay88_wallet_requestdata', $request_data);
                   $ipay88_wallet_requestdata_id = $this->db->insert_id();
                //   echo ' $ipay88_wallet_requestdata_id : '. $ipay88_wallet_requestdata_id;
                //   exit;
$url1=base_url() . 'index.php/home/ipay88_save_wallet/'.$ipay88_wallet_requestdata_id.'/';

                    $page_data['walletNo'] = $walletNo;
                   $page_data['return_page'] = $url1;
                   $page_data['backend_page'] = $url1;
                  
                       $this->load->view('front/shopping_cart/ipay88wallet',$page_data);
               
              }
                else if ($method == 'pum') {

                    $data['user']                   = $this->session->userdata('user_id');
                    $data['method']                 = $this->input->post('method_0');
                    $data['amount']                 = $grand_total;
                    $data['status']                 = 'due';
                    $data['payment_details']        = '[]';
                    $data['timestamp']              = time();
                    $this->db->insert('wallet_load', $data);
                    $id = $this->db->insert_id();
                    $this->session->set_userdata('wallet_id', $id);

                    $pum_merchant_key = $this->crud_model->get_settings_value('business_settings', 'pum_merchant_key', 'value');
                    $pum_merchant_salt = $this->crud_model->get_settings_value('business_settings', 'pum_merchant_salt', 'value');

                    $user_id = $this->session->userdata('user_id');
                    /****TRANSFERRING USER TO PUM TERMINAL****/
                    $this->pum->add_field('key', $pum_merchant_key);
                    $this->pum->add_field('txnid', substr(hash('sha256', mt_rand() . microtime()), 0, 20));
                    $this->pum->add_field('amount', $grand_total);
                    $this->pum->add_field('firstname', $this->db->get_where('user', array('user_id' => $user_id))->row()->username);
                    $this->pum->add_field('email', $this->db->get_where('user', array('user_id' => $user_id))->row()->email);
                    $this->pum->add_field('phone', $this->db->get_where('user', array('user_id' => $user_id))->row()->phone);
                    $this->pum->add_field('productinfo', 'Payment with PayUmoney');
                    $this->pum->add_field('service_provider', 'payu_paisa');
                    $this->pum->add_field('udf1', $id);

                    $this->pum->add_field('surl', base_url() . 'index.php/home/wallet_pum_success');
                    $this->pum->add_field('furl', base_url() . 'index.php/home/wallet_pum_failure');

                    // submit the fields to pum
                    $this->pum->submit_pum_post();
                } else if ($method == 'ssl') {
                    $data['user']                   = $this->session->userdata('user_id');
                    $data['method']                 = $this->input->post('method_0');
                    $data['amount']                 = $grand_total;
                    $data['status']                 = 'due';
                    $data['payment_details']        = '[]';
                    $data['timestamp']              = time();
                    $this->db->insert('wallet_load', $data);
                    $id = $this->db->insert_id();
                    $this->session->set_userdata('wallet_id', $id);

                    $ssl_store_id = $this->db->get_where('business_settings', array('type' => 'ssl_store_id'))->row()->value;
                    $ssl_store_passwd = $this->db->get_where('business_settings', array('type' => 'ssl_store_passwd'))->row()->value;
                    $ssl_type = $this->db->get_where('business_settings', array('type' => 'ssl_type'))->row()->value;

                    // $total_amount = $grand_total / $exchange;
                    $total_amount = $grand_total;

                    /* PHP */
                    $post_data = array();
                    $post_data['store_id'] = $ssl_store_id;
                    $post_data['store_passwd'] = $ssl_store_passwd;
                    $post_data['total_amount'] = $total_amount;
                    $post_data['currency'] = "BDT";
                    $post_data['tran_id'] = date('Ym', $data['timestamp']) . $id;
                    $post_data['success_url'] = base_url() . "home/wallet_sslcommerz_success";
                    $post_data['fail_url'] = base_url() . "home/wallet_sslcommerz_fail";
                    $post_data['cancel_url'] = base_url() . "home/wallet_sslcommerz_cancel";
                    # $post_data['multi_card_name'] = "mastercard,visacard,amexcard";  # DISABLE TO DISPLAY ALL AVAILABLE

                    # EMI INFO
                    $post_data['emi_option'] = "1";
                    $post_data['emi_max_inst_option'] = "9";
                    $post_data['emi_selected_inst'] = "9";

                    $user_id = $this->session->userdata('user_id');
                    $user_info = $this->db->get_where('user', array('user_id' => $user_id))->row();

                    $cus_name = $user_info->username . ' ' . $user_info->surname;

                    # CUSTOMER INFORMATION
                    $post_data['cus_name'] = $cus_name;
                    $post_data['cus_email'] = $user_info->email;
                    $post_data['cus_add1'] = $user_info->address1;
                    $post_data['cus_add2'] = $user_info->address2;
                    $post_data['cus_city'] = $user_info->city;
                    $post_data['cus_state'] = $user_info->state;
                    $post_data['cus_postcode'] = $user_info->zip;
                    $post_data['cus_country'] = $user_info->country;
                    $post_data['cus_phone'] = $user_info->phone;

                    # REQUEST SEND TO SSLCOMMERZ
                    if ($ssl_type == "sandbox") {
                        $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v3/api.php"; // Sandbox
                    } elseif ($ssl_type == "live") {
                        $direct_api_url = "https://securepay.sslcommerz.com/gwprocess/v3/api.php"; // Live
                    }

                    $handle = curl_init();
                    curl_setopt($handle, CURLOPT_URL, $direct_api_url);
                    curl_setopt($handle, CURLOPT_TIMEOUT, 30);
                    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
                    curl_setopt($handle, CURLOPT_POST, 1);
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                    if ($ssl_type == "sandbox") {
                        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC
                    } elseif ($ssl_type == "live") {
                        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, TRUE);
                    }


                    $content = curl_exec($handle);

                    $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

                    if ($code == 200 && !(curl_errno($handle))) {
                        curl_close($handle);
                        $sslcommerzResponse = $content;
                    } else {
                        curl_close($handle);
                        echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
                        exit;
                    }

                    # PARSE THE JSON RESPONSE
                    $sslcz = json_decode($sslcommerzResponse, true);

                    if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
                        # THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
                        echo "<script>window.location.href = '" . $sslcz['GatewayPageURL'] . "';</script>";
                        echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
                        header("Location: " . $sslcz['GatewayPageURL']);
                        exit;
                    } else {
                        echo "JSON Data parsing error!";
                    }
                }
                //$this->email_model->wallet_email('payment_info_require_mail_to_customer', $id);
                //$this->email_model->wallet_email('customer_added_wallet_to_admin', $id);
            } else if ($para2 == "set_info") {
                $data['status']                 = 'pending';
                $data['payment_details']        = $this->input->post('payment_info');
                $data['timestamp']              = time();
                $this->db->where('wallet_load_id', $para3);
                $this->db->update('wallet_load', $data);
                //echo $this->db->last_query();
                // $this->email_model->wallet_email('customer_set_payment_info_to_admin', $para3);
                //echo 'done';

                redirect(base_url() . 'index.php/home/profile/');
            } else {
                $page_data['wallt_amt'] = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->result_array();
                $this->load->view('front/user/wallet', $page_data);
            }
        } elseif ($para1 == "bidding_history") {
            $this->load->view('front/user/bidding_history');
        } elseif ($para1 == "downloads") {
            $this->load->view('front/user/downloads');
        } elseif ($para1 == "uploaded_product_status") {
            $page_data['customer_product_id'] = $para2;
            $this->load->view('front/user/uploaded_product_status', $page_data);
        } elseif ($para1 == "update_prod_status") {
            $data['is_sold'] = $this->input->post('is_sold');
            $this->db->where('customer_product_id', $para2);
            $this->db->update('customer_product', $data);
            redirect(base_url() . 'index.php/home/profile/part', 'refresh');
        } elseif ($para1 == "update_profile") {
            $page_data['user_info']     = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->result_array();
            $this->load->view('front/user/update_profile', $page_data);
        } elseif ($para1 == "wallet_history") {
            $this->load->view('front/user/wallet_history');
        } elseif ($para1 == "trans_history") {
            $this->load->view('front/user/trans_history');
        } elseif ($para1 == "rewards_history") {
            // echo "aa"; exit;
            $this->load->view('front/user/rewards_history');
        } elseif ($para1 == "subscribe_product_info") {
            $this->load->view('front/user/subscribe_product');
        } elseif ($para1 == "ticket") {
            $this->load->view('front/user/ticket');
        } elseif ($para1 == "message_box") {
            $page_data['ticket']  = $para2;
            $this->crud_model->ticket_message_viewed($para2, 'user');
            $this->load->view('front/user/message_box', $page_data);
        } elseif ($para1 == "post_product") {
            $this->load->view('front/user/post_product');
        } elseif ($para1 == "post_product_bulk") {

            /*if ($this->session->userdata('user_login') != "yes") {
                redirect(base_url() . 'home/login_set/login', 'refresh');
            }*/

            $physical_categories = $this->db->where('digital', null)->or_where('digital', '')->get('category')->result_array();
            $physical_sub_categories = $this->db->where('digital', null)->or_where('digital', '')->get('sub_category')->result_array();
            $digital_categories = $this->db->where('digital', 'ok')->get('category')->result_array();
            $digital_sub_categories = $this->db->where('digital', 'ok')->get('sub_category')->result_array();
            $brands = $this->db->get('brand')->result_array();

            $page_data['page_name'] = "customer_product_bulk_upload";
            $page_data['page_title'] = translate('Bulk upload');

            $page_data['upload_amount'] = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->product_upload;


            $page_data['physical_categories'] = $physical_categories;
            $page_data['physical_sub_categories'] = $physical_sub_categories;
            $page_data['digital_categories'] = $digital_categories;
            $page_data['digital_sub_categories'] = $digital_sub_categories;
            $page_data['brands'] = $brands;

            $this->load->view('front/user/post_product_bulk', $page_data);
        } elseif ($para1 == "message_view") {
            $page_data['ticket']  = $para2;
            $page_data['message_data'] = $this->db->get_where('ticket', array(
                'ticket_id' => $para2
            ))->result_array();
            $this->crud_model->ticket_message_viewed($para2, 'user');
            $this->load->view('front/user/message_view', $page_data);
        } elseif ($para1 == "do_post_product") {
            $upload_amount = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->product_upload;
            if ($upload_amount > 0) {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('title', 'Title', 'required');
                $this->form_validation->set_rules('category', 'Category', 'required');
                $this->form_validation->set_rules('sub_category', 'Sub Category', 'required');
                $this->form_validation->set_rules('prod_condition', 'Condition', 'required');
                $this->form_validation->set_rules('sale_price', 'Price', 'required');
                $this->form_validation->set_rules('location', 'Location', 'required');
                $this->form_validation->set_rules('description', 'Description', 'required');

                if ($this->form_validation->run() == FALSE) {
                    echo '<br>' . validation_errors();
                } else {
                    $options = array();
                    if ($_FILES["images"]['name'][0] == '') {
                        $num_of_imgs = 0;
                    } else {
                        $num_of_imgs = count($_FILES["images"]['name']);
                    }
                    $data['seo_title']          = $this->input->post('seo_title');
                    $data['seo_description']    = $this->input->post('seo_description');
                    $data['title'] = $this->input->post('title');
                    $data['category'] = $this->input->post('category');
                    $data['sub_category'] = $this->input->post('sub_category');
                    $data['brand'] = $this->input->post('brand');
                    $data['prod_condition'] = $this->input->post('prod_condition');
                    $data['sale_price'] = $this->input->post('sale_price');
                    $data['location'] = $this->input->post('location');
                    $data['description'] = $this->input->post('description');
                    $data['add_timestamp'] = time();
                    $data['status'] = 'ok';
                    $data['admin_status'] = 'ok';
                    $data['is_sold'] = 'no';
                    $data['rating_user'] = '[]';
                    $data['num_of_imgs'] = $num_of_imgs;
                    $data['front_image'] = 0;
                    $additional_fields['name'] = json_encode($this->input->post('ad_field_names'));
                    $additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
                    $data['additional_fields'] = json_encode($additional_fields);
                    $data['added_by'] = $this->session->userdata('user_id');

                    $this->db->insert('customer_product', $data);
                    // echo $this->db->last_query();
                    $id = $this->db->insert_id();
                    $this->benchmark->mark_time();

                    $this->crud_model->file_up("images", "customer_product", $id, 'multi');

                    $this->crud_model->set_category_data(0);
                    recache();

                    // Package Info subtract code
                    $data1['product_upload'] = $upload_amount - 1;
                    $this->db->where('user_id', $this->session->userdata('user_id'));
                    $this->db->update('user', $data1);

                    echo "done";
                }
            } else {
                echo "failed";
            }
        } elseif ($para1 == "uploaded_products") {
            $this->load->view('front/user/uploaded_products');
        } elseif ($para1 == "package_payment_info") {
            $this->load->view('front/user/package_payment_info');
        } elseif ($para1 == "view_package_details") {
            $info = $this->db->get_where('package_payment', array('package_payment_id' => $para2))->row();
            $page_info['det']['status'] = $info->payment_status;
            $page_info['id'] = $para2;
            $page_info['payment_details'] = $info->payment_details;
            $this->load->view('front/user/view_package_details', $page_info);
        } else {
            $page_data['part']     = 'info';
            if ($para2 == "info") {
                $page_data['part']     = 'info';
            } elseif ($para2 == "wishlist") {
                $page_data['part']     = 'wishlist';
            } elseif ($para2 == "order_history") {
                $page_data['part']     = 'order_history';
            } elseif ($para2 == "bidding_history") {
                $page_data['part']     = 'bidding_history';
            } elseif ($para2 == "downloads") {
                $page_data['part']     = 'downloads';
            } elseif ($para2 == "update_profile") {
                $page_data['part']     = 'update_profile';
            } elseif ($para2 == "wallet_history") {
                $page_data['part']     = 'wallet_history';
            } elseif ($para2 == "ticket") {
                $page_data['part']     = 'ticket';
            } elseif ($para2 == "wallet") {
                $page_data['part']     = 'wallet';
                if(isset($_SESSION['p']['balance_alert'])){
                    $page_data['balance_alert'] = $_SESSION['p']['balance_alert'];
                    unset($_SESSION['p']['balance_alert']);
                }
            }
            $page_data['user_info']     = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->result_array();
            $page_data['page_name']     = "user";
            $page_data['asset_page']    = "user_profile";
            $page_data['page_title']    = translate('my_profile');
            $this->load->view('front/index', $page_data);
        }
        /*$page_data['all_products'] = $this->db->get_where('user', array(
            'user_id' => $this->session->userdata('user_id')
        ))->result_array();
        $page_data['user_info']    = $this->db->get_where('user', array(
            'user_id' => $this->session->userdata('user_id')
        ))->result_array();*/
    }

    function ticket_message($para1 = '')
    {
        $page_data['page_name']  = "ticket_message";
        $page_data['ticket']  = $para1;
        $page_data['message_data'] = $this->db->get_where('ticket', array(
            'ticket_id' => $para1
        ))->result_array();
        $this->Crud_model->ticket_message_viewed($para1, 'user');
        $page_data['msgs']  = $this->db->get_where('ticket_message', array('ticket_id' => $para1))->result_array();
        $page_data['ticket_id']  = $para1;
        $page_data['page_name']  = "ticket_message";
        $page_data['page_title'] = translate('ticket_message');
        $this->load->view('front/index', $page_data);
    }

    function ticket_message_add()
    {
        $this->load->library('form_validation');
        $safe = 'yes';
        $char = '';
        foreach ($_POST as $row) {
            if (preg_match('/[\^}{#~|+¬]/', $row, $match)) {
                $safe = 'no';
                $char = $match[0];
            }
        }

        $this->form_validation->set_rules('sub', 'Subject', 'required');
        $this->form_validation->set_rules('reply', 'Message', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
        } else {
            if ($safe == 'yes') {
                $data['time']             = time();
                $data['subject']         = $this->input->post('sub');
                $id                      = $this->session->userdata('user_id');
                $data['from_where']     = json_encode(array('type' => 'user', 'id' => $id));
                $data['to_where']         = json_encode(array('type' => 'admin', 'id' => ''));
                $data['view_status']     = 'ok';
                $this->db->insert('ticket', $data);
                $ticket_id = $this->db->insert_id();

                $userlog['uid'] =  $this->session->userdata('user_id');
                $userlog['description'] = "Ticket Create Successfully ( " . $this->input->post('sub') . " )";
                $this->db->insert('user_log', $userlog);

                $data1['message'] = $this->input->post('reply');
                $data1['time'] = time();
                if (!empty($this->db->get_where('ticket_message', array('ticket_id' => $ticket_id))->row()->ticket_id)) {
                    $data1['from_where'] = $this->db->get_where('ticket_message', array('ticket_id' => $ticket_id))->row()->from_where;
                    $data1['to_where'] = $this->db->get_where('ticket_message', array('ticket_id' => $ticket_id))->row()->to_where;
                } else {
                    $data1['from_where'] = $this->db->get_where('ticket', array('ticket_id' => $ticket_id))->row()->from_where;
                    $data1['to_where'] = $this->db->get_where('ticket', array('ticket_id' => $ticket_id))->row()->to_where;
                }
                $data1['ticket_id'] = $ticket_id;
                $data1['view_status'] = json_encode(array('user_show' => 'ok', 'admin_show' => 'no'));
                $data1['subject']  = $this->db->get_where('ticket', array('ticket_id' => $ticket_id))->row()->subject;
                $this->db->insert('ticket_message', $data1);
                echo 'success#-#-#';
            } else {
                echo 'fail#-#-#Disallowed charecter : " ' . $char . ' " in the POST';
            }
        }
    }

    function ticket_reply($para1 = '')
    {
        $this->load->library('form_validation');
        $safe = 'yes';
        $char = '';
        foreach ($_POST as $row) {
            if (preg_match('/[\^}{#~|+¬]/', $row, $match)) {
                $safe = 'no';
                $char = $match[0];
            }
        }

        $this->form_validation->set_rules('reply', 'Message', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
        } else {
            if ($safe == 'yes') {
                $data['message'] = $this->input->post('reply');
                $data['time'] = time();
                if (!empty($this->db->get_where('ticket_message', array('ticket_id' => $para1))->row()->ticket_id)) {
                    $data['from_where'] = $this->db->get_where('ticket_message', array('ticket_id' => $para1))->row()->from_where;
                    $data['to_where'] = $this->db->get_where('ticket_message', array('ticket_id' => $para1))->row()->to_where;
                } else {
                    $data['from_where'] = $this->db->get_where('ticket', array('ticket_id' => $para1))->row()->from_where;
                    $data['to_where'] = $this->db->get_where('ticket', array('ticket_id' => $para1))->row()->to_where;
                }
                $data['ticket_id'] = $para1;
                $data['view_status'] = json_encode(array('user_show' => 'ok', 'admin_show' => 'no'));
                $data['subject']  = $this->db->get_where('ticket', array('ticket_id' => $para1))->row()->subject;
                $this->db->insert('ticket_message', $data);
                echo 'success#-#-#';
            } else {
                echo 'fail#-#-#Disallowed charecter : " ' . $char . ' " in the POST';
            }
        }
    }

    function ticket_listed($para2 = '')
    {
        $this->load->library('Ajax_pagination');

        $id = $this->session->userdata('user_id');
        $this->db->where('from_where', '{"type":"user","id":"' . $id . '"}');
        $this->db->or_where('to_where', '{"type":"user","id":"' . $id . '"}');
        $config['total_rows']     = $this->db->count_all_results('ticket');
        $config['base_url']       = base_url() . 'index.php/home/ticket_listed/';
        $config['per_page']     = 5;
        $config['uri_segment']  = 5;
        $config['cur_page_giv'] = $para2;

        $function                  = "ticket_listed('0')";
        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start               = floor($rr) * $config['per_page'];
        $function                 = "ticket_listed('" . $last_start . "')";
        $config['last_link']      = '&raquo;';
        $config['last_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function                 = "ticket_listed('" . ($para2 - $config['per_page']) . "')";
        $config['prev_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function                 = "ticket_listed('" . ($para2 + $config['per_page']) . "')";
        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open']  = '<ul class="pagination pagination-style-2 pagination-sm">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open']  = '<li class="active"><a rel="grow" class="btn-u btn-u-red grow" class="active">';
        $config['cur_tag_close'] = '</a></li>';

        $function                = "ticket_listed(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        $this->db->where('from_where', '{"type":"user","id":"' . $id . '"}');
        $this->db->or_where('to_where', '{"type":"user","id":"' . $id . '"}');
        $page_data['query'] = $this->db->get('ticket', $config['per_page'], $para2)->result_array();
        $this->load->view('front/user/ticket_listed', $page_data);
    }

    function order_listed($para2 = '')
    {
        $this->load->library('Ajax_pagination');

        $id = $this->session->userdata('user_id');
        $this->db->select('COUNT(*) as count');
        $this->db->from('sale');
        $this->db->where('(status="success" OR status="admin_pending")');
        $this->db->where('buyer', $id);
        $this->db->group_by('order_id, store_id');

        $query = $this->db->get();
        $count = $query->num_rows();
        $config['total_rows'] = $count;
        $config['base_url']     = base_url() . 'index.php/home/order_listed/';
        $config['per_page']     = 5;
        $config['uri_segment']  = 5;
        $config['cur_page_giv'] = $para2;

        $function                  = "order_listed('0')";
        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start               = floor($rr) * $config['per_page'];
        $function                 = "order_listed('" . $last_start . "')";
        $config['last_link']      = '&raquo;';
        $config['last_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function                 = "order_listed('" . ($para2 - $config['per_page']) . "')";
        $config['prev_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function                 = "order_listed('" . ($para2 + $config['per_page']) . "')";
        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open']  = '<ul class="pagination pagination-style-2 pagination-sm">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open']  = '<li class="active"><a rel="grow" class="btn-u btn-u-red grow" class="active">';
        $config['cur_tag_close'] = '</a></li>';

        $function                = "order_listed(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        /*** old 
        $this->ajax_pagination->initialize($config);
        $this->db->order_by('sale_id', 'desc');
        $where = '(status="success" or status = "admin_pending")';
        $this->db->where($where);
        $this->db->where('buyer', $id);
        $page_data['orders'] = $this->db->get('sale', $config['per_page'], $para2)->result_array();
        //  echo $this->db->last_query();
        $this->load->view('front/user/order_listed', $page_data); ***/
        /************ragav code @16/10/2023**********************/
         
        $this->ajax_pagination->initialize($config);
        $this->db->select('order_id, MAX(sale_id) as sale_id, store_id, MAX(sale_datetime) as sale_datetime, MAX(return_status) as return_status, MAX(order_trackment) as order_trackment, MAX(delivary_datetime) as delivary_datetime, MAX(cancel_status) as cancel_status, MAX(product_details) as product_details, MAX(lalamove_res) as lalamove_res, MAX(discount) as discount, SUM(grand_total) as grandtotal, SUM(vat) as tax');
        $this->db->where('buyer', $id);
        $this->db->where_in('status', array('success', 'admin_pending'));
        $this->db->group_by('order_id, store_id');
        $this->db->order_by('sale_id', desc);
        // $offset = $this->uri->segment(5, 0);
        $result = $this->db->get('sale', $config['per_page'], $para2);
        $page_data['orders'] = $result->result_array();
        // echo $this->db->last_query();
        $this->load->view('front/user/order_listed', $page_data);
        
    }

    function reward_listed($para2 = '')
    {
        $this->load->library('Ajax_pagination');

        $id = $this->session->userdata('user_id');
        $this->db->where('buyer', $id);
        $config['total_rows']   = $this->db->count_all_results('sale');
        $config['base_url']     = base_url() . 'index.php/home/reward_listed/';
        $config['per_page']     = 5;
        $config['uri_segment']  = 5;
        $config['cur_page_giv'] = $para2;

        $function                  = "order_listed('0')";
        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start               = floor($rr) * $config['per_page'];
        $function                 = "order_listed('" . $last_start . "')";
        $config['last_link']      = '&raquo;';
        $config['last_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function                 = "order_listed('" . ($para2 - $config['per_page']) . "')";
        $config['prev_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function                 = "order_listed('" . ($para2 + $config['per_page']) . "')";
        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open']  = '<ul class="pagination pagination-style-2 pagination-sm">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open']  = '<li class="active"><a rel="grow" class="btn-u btn-u-red grow" class="active">';
        $config['cur_tag_close'] = '</a></li>';

        $function                = "order_listed(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        $where = '(status="success" or status = "admin_pending")';
        $this->db->where($where);
        $this->db->where('buyer', $id);
        $page_data['orders'] = $this->db->get('sale', $config['per_page'], $para2)->result_array();
        //  echo $this->db->last_query();
        $this->load->view('front/user/reward_listed', $page_data);
    }

    function wallet_listed($para2 = '')
    {
        $this->load->library('Ajax_pagination');

        $id = $this->session->userdata('user_id');

        $this->db->where('user', $id);

        $config['total_rows']   = $this->db->count_all_results('wallet_load');;
        $config['base_url']     = base_url() . 'index.php/home/wallet_listed/';
        $config['per_page']     = 5;
        $config['uri_segment']  = 5;
        $config['cur_page_giv'] = $para2;

        $function                  = "wallet_listed('0')";
        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start               = floor($rr) * $config['per_page'];
        $function                 = "wallet_listed('" . $last_start . "')";
        $config['last_link']      = '&raquo;';
        $config['last_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function                 = "wallet_listed('" . ($para2 - $config['per_page']) . "')";
        $config['prev_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function                 = "wallet_listed('" . ($para2 + $config['per_page']) . "')";
        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open']  = '<ul class="pagination pagination-style-2 ">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open']  = '<li class="active"><a rel="grow" class="btn-u btn-u-red grow" class="active">';
        $config['cur_tag_close'] = '</a></li>';

        $function                = "wallet_listed(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        $this->db->order_by('wallet_load_id', 'DESC');
        $this->db->where('user', $id);
        $page_data['query'] = $this->db->get('wallet_load', $config['per_page'], $para2)->result_array();

        //echo '<pre>'; print_r($page_data['wallt_amt']); exit;
        $this->load->view('front/user/wallet_listed', $page_data);
    }


    function bid_listed($para2 = '')
    {
        $this->load->library('Ajax_pagination');

        $id = $this->session->userdata('user_id');
        $this->db->where('uid', $id);
        // $this->db->where('buyer', $id);
        $config['total_rows']   = $this->db->count_all_results('bidding_history');
        $config['base_url']     = base_url() . 'index.php/home/bid_listed/';
        $config['per_page']     = 5;
        $config['uri_segment']  = 5;
        $config['cur_page_giv'] = $para2;

        $function                  = "bid_listed('0')";
        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start               = floor($rr) * $config['per_page'];
        $function                 = "bid_listed('" . $last_start . "')";
        $config['last_link']      = '&raquo;';
        $config['last_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function                 = "bid_listed('" . ($para2 - $config['per_page']) . "')";
        $config['prev_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function                 = "bid_listed('" . ($para2 + $config['per_page']) . "')";
        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open']  = '<ul class="pagination pagination-style-2 pagination-sm">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open']  = '<li class="active"><a rel="grow" class="btn-u btn-u-red grow" class="active">';
        $config['cur_tag_close'] = '</a></li>';

        $function                = "bid_listed(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);

        // $page_data['bids'] = $this->db->get('bidding_history', $config['per_page'], $para2)->result_array();
        $page_data['bids'] = $this->db->get_where('bidding_history', array('uid' => $id))->result_array();
        $this->load->view('front/user/bid_listed', $page_data);
    }

    function wallet_transfer($para2 = '')
    {
        $this->load->library('Ajax_pagination');

        $id = $this->session->userdata('user_id');
        $this->db->where('uid', $id);
        // $this->db->where('buyer', $id);
        $config['total_rows']   = $this->db->count_all_results('user_log');
        $config['base_url']     = base_url() . 'index.php/home/wallet_tansfer/';
        $config['per_page']     = 5;
        $config['uri_segment']  = 5;
        $config['cur_page_giv'] = $para2;

        $function                  = "wallet_tansfer('0')";
        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start               = floor($rr) * $config['per_page'];
        $function                 = "wallet_tansfer('" . $last_start . "')";
        $config['last_link']      = '&raquo;';
        $config['last_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function                 = "wallet_tansfer('" . ($para2 - $config['per_page']) . "')";
        $config['prev_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function                 = "wallet_tansfer('" . ($para2 + $config['per_page']) . "')";
        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open']  = '<ul class="pagination pagination-style-2 pagination-sm">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open']  = '<li class="active"><a rel="grow" class="btn-u btn-u-red grow" class="active">';
        $config['cur_tag_close'] = '</a></li>';

        $function                = "wallet_tansfer(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);


        $page_data['wallet_history'] = $this->db->order_by('description', 'DESC')->get_where('user_log', array('uid' => $id, 'status' => 1))->result_array();


        $page_data['user_name'] = $this->db->get_where('user', array('user_id' => $id))->result_array();

        $this->load->view('front/user/wallet_transfer', $page_data);

        $data3['read_status'] = 1;
        $this->db->where('uid', $id);
        $this->db->update('user_log', $data3);
    }

    function subscribe_product($para2 = '')
    {
        $this->load->library('Ajax_pagination');

        $id = $this->session->userdata('user_id');
        $this->db->where('user_id', $id);
        $this->db->where('status', 1);
        $config['total_rows']   = $this->db->count_all_results('subscribe_sale');
        $config['base_url']     = base_url() . 'index.php/home/subscribe_product/';
        $config['per_page']     = 5;
        $config['uri_segment']  = 5;
        $config['cur_page_giv'] = $para2;

        $function                  = "subscribe_product('0')";
        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start               = floor($rr) * $config['per_page'];
        $function                 = "subscribe_product('" . $last_start . "')";
        $config['last_link']      = '&raquo;';
        $config['last_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function                 = "subscribe_product('" . ($para2 - $config['per_page']) . "')";
        $config['prev_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function                 = "subscribe_product('" . ($para2 + $config['per_page']) . "')";
        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open']  = '<ul class="pagination pagination-style-2 pagination-sm">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open']  = '<li class="active"><a rel="grow" class="btn-u btn-u-red grow" class="active">';
        $config['cur_tag_close'] = '</a></li>';

        $function                = "subscribe_product(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);

        $this->db->where('user_id', $id);
        $this->db->where('status', 1);
        $page_data['subscribe_product_history'] = $this->db->get('subscribe_sale', $config['per_page'], $para2)->result_array();

        $this->load->view('front/user/subscribe_product_history', $page_data);
    }


    function wish_listed($para2 = '')
    {
        $this->load->library('Ajax_pagination');

        $id = $this->session->userdata('user_id');
        $ids = json_decode($this->db->get_where('user', array('user_id' => $id))->row()->wishlist, true);
        // print(count($ids));
        $this->db->where_in('product_id', $ids);
        if(count($ids)!=0)
     {
         $config['total_rows']   = $this->db->count_all_results('product');
       
        $config['base_url']     = base_url() . 'index.php/home/wish_listed/';
        $config['per_page']     = 5;
        $config['uri_segment']  = 5;
        $config['cur_page_giv'] = $para2;

        $function                  = "wish_listed('0')";
        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start               = floor($rr) * $config['per_page'];
        $function                 = "wish_listed('" . $last_start . "')";
        $config['last_link']      = '&raquo;';
        $config['last_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function                 = "wish_listed('" . ($para2 - $config['per_page']) . "')";
        $config['prev_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function                 = "wish_listed('" . ($para2 + $config['per_page']) . "')";
        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open']  = '<ul class="pagination pagination-style-2 ">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open']  = '<li class="active"><a rel="grow" class="btn-u btn-u-red grow" class="active">';
        $config['cur_tag_close'] = '</a></li>';

        $function                = "wish_listed(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
         
        $ids = json_decode($this->db->get_where('user', array('user_id' => $id))->row()->wishlist, true);
        $this->db->where_in('product_id', $ids);
        $page_data['query'] = $this->db->get('product', $config['per_page'], $para2)->result_array();
        $this->load->view('front/user/wish_listed', $page_data);
    }
         else
         {
        $this->load->view('front/user/wish_listed', $page_data);;
         }
    }


    function downloads_listed($para2 = '')
    {
        $this->load->library('Ajax_pagination');

        $id = $this->session->userdata('user_id');
        $downloads = json_decode($this->db->get_where('user', array('user_id' => $id))->row()->downloads, true);
        $ids = array();
        foreach ($downloads as $row) {
            $ids[] = $row['product'];
        }
        if (count($ids) !== 0) {
            $this->db->where_in('product_id', $ids);
        } else {
            $this->db->where('product_id', 0);
        }

        $config['total_rows']   = $this->db->count_all_results('product');;
        $config['base_url']     = base_url() . 'index.php/home/downloads_listed/';
        $config['per_page']     = 5;
        $config['uri_segment']  = 5;
        $config['cur_page_giv'] = $para2;

        $function                  = "downloads_listed('0')";
        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start               = floor($rr) * $config['per_page'];
        $function                 = "downloads_listed('" . $last_start . "')";
        $config['last_link']      = '&raquo;';
        $config['last_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function                 = "downloads_listed('" . ($para2 - $config['per_page']) . "')";
        $config['prev_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function                 = "downloads_listed('" . ($para2 + $config['per_page']) . "')";
        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open']  = '<ul class="pagination pagination-style-2 ">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open']  = '<li class="active"><a rel="grow" class="btn-u btn-u-red grow" class="active">';
        $config['cur_tag_close'] = '</a></li>';

        $function                = "downloads_listed(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        if (count($ids) !== 0) {
            $this->db->where_in('product_id', $ids);
        } else {
            $this->db->where('product_id', 0);
        }
        $page_data['query'] = $this->db->get('product', $config['per_page'], $para2)->result_array();
        $this->load->view('front/user/downloads_listed', $page_data);
    }

    /* FUNCTION: Loads Customer Download */
    function download($id)
    {
        if ($this->session->userdata('user_login') != "yes") {
            redirect(base_url(), 'refresh');
        }
        $this->crud_model->download_product($id);
    }

    /* FUNCTION: Loads Customer Download Permission */
    function can_download($id)
    {
        if ($this->session->userdata('user_login') != "yes") {
            redirect(base_url(), 'refresh');
        }
        if ($this->crud_model->can_download($id)) {
            echo 'ok';
        } else {
            echo 'not';
        }
    }

    /* FUNCTION: Loads Category filter page */
    function category($para1 = "", $para2 = "", $min = "", $max = "", $text = '')
    {
        $vendorid_get = $this->session->userdata('vendorid');
        if($vendorid_get == ""){
            
            $vendorid_get ="2";
          
        }

        if ($para2 == "") {
            $store_info = $this->db->get_where('vendor', array('default_set' => 'ok','vendor_id'=>$vendorid_get))->row()->vendor_id;
                $this->db->where('store_id', $store_info);

            $page_data['all_products'] = $this->db->get_where('product', array(
                'category' => $para1
            ))->result_array();
            //  echo $this->db->last_query();
        } else if ($para2 != "") {
           $store_info = $this->db->get_where('vendor', array('default_set' => 'ok','vendor_id'=>$vendorid_get))->row()->vendor_id;
                $this->db->where('store_id', $store_info);
            $page_data['all_products'] = $this->db->get_where('product', array(
                'sub_category' => $para2
            ))->result_array();
        }

        if ($para1 == "" || $para1 == "0") {
            $type = 'other';
        } else {
            if ($this->db->get_where('category', array('category_id' => $para1))->row()->digital == 'ok') {
                $type = 'digital';
            } else {
                $type = 'other';
            }
        }

        $type = 'other';
        $brand_sub = explode('-', $para2);

        $sub     = 0;
        $brand  = 0;

        if (isset($brand_sub[0])) {
            $sub = $brand_sub[0];
        }
        if (isset($brand_sub[1])) {
            $brand = $brand_sub[1];
        }

        $page_data['range']            = $min . ';' . $max;
        $page_data['page_name']        = "product_list/" . $type;
        $page_data['asset_page']       = "product_list_" . $type;
        $page_data['page_title']       = translate('products');
        $page_data['all_category']     = $this->db->get('category')->result_array();
        $page_data['all_sub_category'] = $this->db->get('sub_category')->result_array();
        $page_data['cur_sub_category'] = $sub;
        $page_data['cur_brand']        = $brand;
        $page_data['cur_category']     = $para1;
        $page_data['text']             = $text;
        $page_data['category_data']    = $this->db->get_where('category', array(
            'category_id' => $para1
        ))->result_array();
        $this->load->view('front/index', $page_data);
    }



    function all_category()
    {
        $page_data['page_name']        = "others/all_category";
        $page_data['asset_page']       = "all_category";
        $page_data['page_title']       = translate('all_category');
        $this->load->view('front/index', $page_data);
    }
    function all_themes()
    {
        $page_data['page_name']        = "others/themes";
        $page_data['asset_page']       = "themes";
        $page_data['page_title']       = translate('themes');
        $this->load->view('front/index', $page_data);
    }

    function all_brands()
    {
        if ($this->crud_model->get_settings_value('general_settings', 'vendor_system') !== 'ok') {
            redirect(base_url());
        }
        $page_data['page_name']        = "others/all_brands";
        $page_data['asset_page']       = "all_brands";
        $page_data['page_title']       = translate('all_brands');
        $this->load->view('front/index', $page_data);
    }
    function faq()
    {
        $page_data['page_name']        = "others/faq";
        $page_data['asset_page']       = "all_category";
        $page_data['page_title']       = translate('frequently_asked_questions');
        $page_data['faqs']               = json_decode($this->crud_model->get_type_name_by_id('business_settings', '11', 'value'), true);
        $this->load->view('front/index', $page_data);
    }


    function about_us()
    {
        $page_data['page_name']        = "others/about_us";
        $page_data['asset_page']       = "all_category";
        $page_data['page_title']       = translate('about_us');
        $page_data['faqs']               = json_decode($this->crud_model->get_type_name_by_id('business_settings', '11', 'value'), true);
        $this->load->view('front/index', $page_data);
    }

    /* FUNCTION: Search Products */
    function home_search($param = '')
    {
        $type = $this->input->post('type');
        $category = $this->input->post('category');
        $this->session->set_userdata('searched_cat', $category);
        if ($param !== 'top') {
            $sub_category = $this->input->post('sub_category');
            $range        = $this->input->post('price');
            $brand           = $this->input->post('brand');
            $query           = $this->input->post('query');
            $p            = explode(';', $range);
            if($type!=""){
            redirect(base_url() . 'index.php/home/others_product/'.$type.'/' . $category . '/' . $sub_category . '/' . $brand . '/' . $p[0] . '/' . $p[1] . '/' . $query, 'refresh');
             }
             else
             {
                redirect(base_url() . 'index.php/home/category/' . $category . '/' . $sub_category . '-' . $brand . '/' . $p[0] . '/' . $p[1] . '/' . $query, 'refresh');
             }
        } else if ($param == 'top') {
            redirect(base_url() . 'index.php/home/category/' . $category, 'refresh');
        }
    }

    function text_search()
    {
       
        if ($this->crud_model->get_settings_value('general_settings', 'vendor_system') !== 'ok') {
            
            $search = $this->input->post('query');
            $category = $this->input->post('category');
           echo "<script>window.location.href='".base_url() . "index.php/home/category/" . $category . "/0-0/0/0/" . $search."';</script>";
            // redirect(base_url() . 'index.php/home/category/' . $category . '/0-0/0/0/' . $search, 'refresh');
        } else {
            
            $type = $this->input->post('type');
            $search = $this->input->post('query');
            $category = $this->input->post('category');
            
            if ($type == 'vendor') {
                 echo "<script>window.location.href='".base_url() . "index.php/home/store_locator/" . $search."';</script>";
                // redirect(base_url() . 'index.php/home/store_locator/' . $search, 'refresh');
            } else if ($type == 'product') {
                echo "<script>window.location.href='".base_url() . "index.php/home/category/" . $category . "/0-0/0/0/" . $search."';</script>";
                // redirect(base_url() . 'index.php/home/category/' . $category . '/0-0/0/0/' . $search, 'refresh');
            }
        }
    }

    /* FUNCTION: Check if user logged in */
    function is_logged()
    {
        if ($this->session->userdata('user_login') == 'yes') {
            echo 'yah!good';
        } else {
            echo 'nope!bad';
        }
    }

    function ajax_others_product_bk($para1 = "")
    {
        $physical_product_activation = $this->db->get_where('general_settings', array('type' => 'physical_product_activation'))->row()->value;
        $digital_product_activation = $this->db->get_where('general_settings', array('type' => 'digital_product_activation'))->row()->value;
        $vendor_system = $this->db->get_where('general_settings', array('type' => 'vendor_system'))->row()->value;

        $this->load->library('Ajax_pagination');
        $type = $this->input->post('type');
        if ($type == 'featured') {
            $this->db->where('featured', 'ok');
        } elseif ($type == 'todays_deal') {
            $this->db->where('deal', 'ok');
        } elseif ($type == "flash_deal") {
            // echo "yes"; exit;
            $this->db->where('today_status', '1');
        }
        $this->db->where('status', 'ok');

        if ($physical_product_activation == 'ok' && $digital_product_activation !== 'ok') {
            $this->db->where('download', NULL);
        } else if ($physical_product_activation !== 'ok' && $digital_product_activation == 'ok') {
            $this->db->where('download', 'ok');
        } else if ($physical_product_activation !== 'ok' && $digital_product_activation !== 'ok') {
            $this->db->where('product_id', '');
        }

        if ($vendor_system !== 'ok') {
            $this->db->like('added_by', '{"type":"admin"', 'both');
        }
        // pagination
        $config['total_rows'] = $this->db->count_all_results('product');
        $config['base_url']   = base_url() . 'index.php?home/listed/';
        $config['per_page'] = 50;
        $config['uri_segment']  = 5;
        $config['cur_page_giv'] = $para1;

        $function                  = "filter_others('0')";
        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start               = floor($rr) * $config['per_page'];
        $function                 = "filter_others('" . $last_start . "')";
        $config['last_link']      = '&raquo;';
        $config['last_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function                 = "filter_others('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function                 = "filter_others('" . ($para1 + $config['per_page']) . "')";
        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open']  = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open']  = '<li class="active"><a>';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function                = "filter_others(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);


        $this->db->order_by('product_id', 'desc');
        $this->db->where('status', 'ok');
        if ($type == 'featured') {
            $this->db->where('featured', 'ok');
        } elseif ($type == "flash_deal") {
            // echo "yes"; exit;
            $this->db->where('today_status', '1');
        } elseif ($type == 'todays_deal') {
            $this->db->where('deal', 'ok');
        } elseif ($type == 'bidding') {
            $this->db->where('bidding', '1');
            $date = date("Y-m-d");
            $this->db->where('bid_end_date >', $date);
            // $this->db->where('sale_price >=', $p[0]);

        }
        //echo $this->db->last_query();

        if ($physical_product_activation == 'ok' && $digital_product_activation !== 'ok') {
            $this->db->where('download', NULL);
        } else if ($physical_product_activation !== 'ok' && $digital_product_activation == 'ok') {
            $this->db->where('download', 'ok');
        } else if ($physical_product_activation !== 'ok' && $digital_product_activation !== 'ok') {
            $this->db->where('product_id', '');
        }

        if ($vendor_system !== 'ok') {
            $this->db->like('added_by', '{"type":"admin"', 'both');
        }

        $page_data['products']     = $this->db->get('product', $config['per_page'], $para1)->result_array();
        $page_data['count']              = $config['total_rows'];
        $page_data['page_type']            = $type;

        $this->load->view('front/others_list/listed', $page_data);
    }


    function ajax_others_product($para1 = "")
    {
        $physical_product_activation = $this->db->get_where('general_settings', array('type' => 'physical_product_activation'))->row()->value;
        $digital_product_activation = $this->db->get_where('general_settings', array('type' => 'digital_product_activation'))->row()->value;
        $vendor_system = $this->db->get_where('general_settings', array('type' => 'vendor_system'))->row()->value;

        $this->load->library('Ajax_pagination');
        $type = $this->input->post('type');
        $category = $this->input->post('category');
        $sub_category = $this->input->post('sub_category');
        $brand = $this->input->post('brand');
        $min_val = $this->input->post('min');
        $max_val = $this->input->post('max');
        $min = number_format($min_val, 2, '.', '');
        $max = number_format($max_val, 2, '.', ''); 
        $query_get = $this->input->post('query');
        if ($type == 'featured') {
            $this->db->where('featured', 'ok');
        } elseif ($type == 'todays_deal') {
            $this->db->where('deal', 'ok');
        } elseif ($type == "flash_deal") {
            // echo "yes"; exit;
            $this->db->where('today_status', '1');
        }
        

        if ($physical_product_activation == 'ok' && $digital_product_activation !== 'ok') {
            $this->db->where('download', NULL);
        } else if ($physical_product_activation !== 'ok' && $digital_product_activation == 'ok') {
            $this->db->where('download', 'ok');
        } else if ($physical_product_activation !== 'ok' && $digital_product_activation !== 'ok') {
            $this->db->where('product_id', '');
        }

        if ($vendor_system !== 'ok') {
            $this->db->like('added_by', '{"type":"admin"', 'both');
        }

        $this->db->select('product.*');

        $old_price = "CASE
            WHEN discount > 0 THEN
                CASE
                    WHEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1) IS NOT NULL THEN
                        CONCAT('<del>', FORMAT((SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1),'N2'), '</del>')
                    ELSE
                        CONCAT('<del>', FORMAT(product.sale_price,'N2'), '</del>')
                END
            ELSE ''
        END";
        
        $final_price = "CASE
            WHEN discount > 0 
            THEN ( CASE
            WHEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1) IS NOT NULL
            THEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1)
                ELSE product.sale_price
              END ) - ( discount * 
                CASE
                    WHEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1) IS NOT NULL
                    THEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1 )
                    ELSE product.sale_price
                END ) / 100
            ELSE 
                CASE
                    WHEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1) IS NOT NULL
                    THEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1)
                    ELSE product.sale_price
                END 
            END";
        
        $this->db->select($old_price . ' AS old_price', false);
        $this->db->select($final_price . ' AS price', false);

        $this->db->where('status', 'ok');
        if($query_get!="")
        {
        $this->db->like('title',$query_get);
        }
        // $this->db->order_by('product_id', 'desc');
        
        if($type == 'latest'){
            if($category!="0" && $category!="")
            {
                $this->db->where('category',$category);
            }
           if(($sub_category!="0" && $sub_category!=""))
            {
                $this->db->where('sub_category',$sub_category);
            }
          
           if(($brand!="0" && $brand!=""))
             {
                
                 $this->db->where('brand',$brand);
             }
        }
        if ($type == 'featured') {
            if($category!="0" && $category!="")
            {
                $this->db->where('category',$category);
            }
           if(($sub_category!="0" && $sub_category!=""))
            {
                $this->db->where('sub_category',$sub_category);
            }
          
           if(($brand!="0" && $brand!=""))
             {
                
                 $this->db->where('brand',$brand);
             }
            
            $this->db->where('featured', 'ok');
        } elseif ($type == "flash_deal") {
            
            // echo "yes"; exit;
            $this->db->where('today_status', '1');
        } elseif ($type == 'todays_deal') {
            // echo $category.",".$sub_category.",".$brand;
        
           
            if($category!="0" && $category!="")
            {
                $this->db->where('category',$category);
            }
           if(($sub_category!="0" && $sub_category!=""))
            {
                $this->db->where('sub_category',$sub_category);
            }
          
           if(($brand!="0" && $brand!=""))
             {
                
                 $this->db->where('brand',$brand);
             }
          
           
            $this->db->where('deal', 'ok');
         
             //end//
        } elseif ($type == 'bidding') {
            $this->db->where('bidding', '1');
            $date = date("Y-m-d");
            $this->db->where('bid_end_date >', $date);
            // $this->db->where('sale_price >=', $p[0]);

        }
        //echo $this->db->last_query();

        if ($physical_product_activation == 'ok' && $digital_product_activation !== 'ok') {
            $this->db->where('download', NULL);
        } else if ($physical_product_activation !== 'ok' && $digital_product_activation == 'ok') {
            $this->db->where('download', 'ok');
        } else if ($physical_product_activation !== 'ok' && $digital_product_activation !== 'ok') {
            $this->db->where('product_id', '');
        }

        if ($vendor_system !== 'ok') {
            $this->db->like('added_by', '{"type":"admin"', 'both');
        }
        $vendorid_get = $this->session->userdata('vendorid');
        if($vendorid_get == ""){
            
            $vendorid_get ="2";
          
        }
        // echo $vendorid_get;
         $this->db->where('store_id',$vendorid_get);
         $config['per_page'] = 50;
         $query =  $this->db->get('product', $config['per_page'], $para1);
        // echo $this->db->last_query();
         $result='';
             if ($query->num_rows() > 0) {
                 $result = $query->result_array();
                 $prices = array_column($result, 'price'); 
                 $filteredProducts = array_filter($result, function ($product) use ($min, $max) {
                     return $product['price'] >= $min && $product['price'] <= $max;
                 });
                
                 usort($filteredProducts, function ($a, $b) {
                     return $a['price'] - $b['price'];
                 });
                 if($min =="" && $max==""){
                   
                    array_multisort($prices, SORT_ASC, $result); 
                     $page_data['products'] = $result;
              
                 }
                 else
                 {
                     $page_data['products'] = $filteredProducts;
               
                 }
                 
                $this->db->select('product.*');

                $old_price1 = "CASE
                    WHEN discount > 0 THEN
                        CASE
                            WHEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1) IS NOT NULL THEN
                                CONCAT('<del>', FORMAT((SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1),'N2'), '</del>')
                            ELSE
                                CONCAT('<del>', FORMAT(product.sale_price,'N2'), '</del>')
                        END
                    ELSE ''
                END";
                
                $final_price1 = "CASE
                    WHEN discount > 0 
                    THEN ( CASE
                    WHEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1) IS NOT NULL
                    THEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1)
                        ELSE product.sale_price
                    END ) - ( discount * 
                        CASE
                            WHEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1) IS NOT NULL
                            THEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1 )
                            ELSE product.sale_price
                        END ) / 100
                    ELSE 
                        CASE
                            WHEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1) IS NOT NULL
                            THEN (SELECT MAX(amount) FROM multiple_option WHERE product_id = product.product_id AND status = 1)
                            ELSE product.sale_price
                        END 
                    END";
                
                $this->db->select($old_price1 . ' AS old_price', false);
                $this->db->select($final_price1 . ' AS price', false);
                $this->db->where('status', 'ok');
                $this->db->where('store_id',$vendorid_get);
            if($min=="" && $max=="" && $category=="" &&$sub_category=="" && $query_get=="" && $brand==""){

                if ($type == 'featured') {
                     $this->db->where('featured', 'ok');
                }
                 elseif ($type == "flash_deal") {
                    
                  
                     $this->db->where('today_status', '1');
                } elseif ($type == 'todays_deal') {
                        $this->db->where('deal', 'ok');
                }
                $count_rows = $this->db->get('product');
               
                $rows_total =   $count_rows->num_rows();
     
                $config['total_rows'] = $rows_total ;
       
            }
            else
                {
                    if($query_get!="")
                    {
                    $this->db->like('title',$query_get);
                    }
                    if($type=="latest")
                    {
                        if($category!="0" && $category!="")
                        {
                            $this->db->where('category',$category);
                        }
                       if(($sub_category!="0" && $sub_category!=""))
                        {
                            $this->db->where('sub_category',$sub_category);
                        }
                      
                       if(($brand!="0" && $brand!=""))
                         {
                            
                             $this->db->where('brand',$brand);
                         }
                    }
                    if ($type == 'featured') {
                        if($category!="0" && $category!="")
                        {
                            $this->db->where('category',$category);
                        }
                       if(($sub_category!="0" && $sub_category!=""))
                        {
                            $this->db->where('sub_category',$sub_category);
                        }
                      
                       if(($brand!="0" && $brand!=""))
                         {
                            
                             $this->db->where('brand',$brand);
                         }
                        $this->db->where('featured', 'ok');
                   }
                    elseif ($type == "flash_deal") {
                       
                        if($category!="0" && $category!="")
                        {
                            $this->db->where('category',$category);
                        }
                       if(($sub_category!="0" && $sub_category!=""))
                        {
                            $this->db->where('sub_category',$sub_category);
                        }
                      
                       if(($brand!="0" && $brand!=""))
                         {
                            
                             $this->db->where('brand',$brand);
                         }
                        $this->db->where('today_status', '1');
                   } elseif ($type == 'todays_deal') {
                    if($category!="0" && $category!="")
                    {
                        $this->db->where('category',$category);
                    }
                   if(($sub_category!="0" && $sub_category!=""))
                    {
                        $this->db->where('sub_category',$sub_category);
                    }
                  
                   if(($brand!="0" && $brand!=""))
                     {
                        
                         $this->db->where('brand',$brand);
                     }
                           $this->db->where('deal', 'ok');
                   }
                   $count_rows1 = $this->db->get('product');
               // echo $this->db->last_query();
                   $rows_total1='';
             if ($count_rows1->num_rows() > 0) {
                 $rows_total1 = $count_rows1->result_array();
           
                 $prices = array_column($rows_total1, 'price'); 
                 $filteredProducts1 = array_filter($rows_total1, function ($product) use ($min, $max) {
                     return $product['price'] >= $min && $product['price'] <= $max;
                 });
                 $count1 = count($filteredProducts1);
           
                 usort($filteredProducts1, function ($a, $b) {
                     return $a['price'] - $b['price'];
                 });
               
                    $config['total_rows']=$count1;
                }
                }
                    $config['base_url']   = base_url() . 'index.php?home/listed/';
       
        $config['uri_segment']  = 5;
        $config['cur_page_giv'] = $para1;

        $function                  = "filter_others('0')";
        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start               = floor($rr) * $config['per_page'];
        $function                 = "filter_others('" . $last_start . "')";
        $config['last_link']      = '&raquo;';
        $config['last_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function                 = "filter_others('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function                 = "filter_others('" . ($para1 + $config['per_page']) . "')";
        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open']  = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open']  = '<li class="active"><a>';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function                = "filter_others(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
             }
        //echo $this->db->last_query();
        $page_data['count']              = $config['total_rows'];
        $page_data['page_type']            = $type;

        $this->load->view('front/others_list/listed', $page_data);
    }


    /* FUNCTION: Loads Product List */
    function listed($para1 = "", $para2 = "", $para3 = "")
    {
        $this->load->library('Ajax_pagination');
        if ($para1 == "click") {
            $physical_product_activation = $this->db->get_where('general_settings', array('type' => 'physical_product_activation'))->row()->value;
            $digital_product_activation = $this->db->get_where('general_settings', array('type' => 'digital_product_activation'))->row()->value;
            $vendor_system = $this->db->get_where('general_settings', array('type' => 'vendor_system'))->row()->value;
            if ($this->input->post('range')) {
                $range = $this->input->post('range');
            }
            if ($this->input->post('text')) {
                $text = $this->input->post('text');
            }
            $vendorid_get = $this->session->userdata('vendorid');
            if($vendorid_get == ""){
                
                $vendorid_get ="2";
               // echo $vendorName;
            }
            $category     = $this->input->post('category');
            $category     = explode(',', $category);
            $sub_category = $this->input->post('sub_category');
            $sub_category = explode(',', $sub_category);
            $featured     = $this->input->post('featured');
            $brand           = $this->input->post('brand');
            $name         = '';
            $cat          = '';
            $setter       = '';
            $vendors      = array();
            $approved_users = $this->db->get_where('vendor', array('status' => 'approved'))->result_array();
            foreach ($approved_users as $row) {
                $vendors[] = $vendorid_get;
            }

            if ($vendor_system !== 'ok') {
                $this->db->like('added_by', '{"type":"admin"', 'both');
            }

            if ($physical_product_activation == 'ok' && $digital_product_activation !== 'ok') {
                $this->db->where('download', NULL);
            } else if ($physical_product_activation !== 'ok' && $digital_product_activation == 'ok') {
                $this->db->where('download', 'ok');
            } else if ($physical_product_activation !== 'ok' && $digital_product_activation !== 'ok') {
                $this->db->where('product_id', '');
            }

            if (isset($text)) {
                if ($text !== '') {
                    $this->db->like('title', $text, 'both');
                }
            }

            if ($vendor = $this->input->post('vendor')) {
                if (in_array($vendor, $vendors)) {
                    $this->db->where('added_by', '{"type":"vendor","id":"' . $vendor . '"}');
                } else {
                    $this->db->where('product_id', '');
                }
            }


            $this->db->where('status', 'ok');
            if ($featured == 'ok') {
                $this->db->where('featured', 'ok');
            }

            if ($brand !== '0' && $brand !== '') {
                $this->db->where('brand', $brand);
            }

            if (isset($range)) {
                $p = explode(';', $range);
                $this->db->where('sale_price >=', $p[0]);
                $this->db->where('sale_price <=', $p[1]);
            }

            $query = array();
            if (count($sub_category) > 0) {
                $i = 0;
                foreach ($sub_category as $row) {
                    $i++;
                    if ($row !== "") {
                        if ($row !== "0") {
                            $query[] = $row;
                            $setter  = 'get';
                        } else {
                            $this->db->where('sub_category !=', '0');
                        }
                    }
                }
                if ($setter == 'get') {
                    $this->db->where_in('sub_category', $query);
                }
            }

            if (count($category) > 0 && $setter !== 'get') {
                $i = 0;
                foreach ($category as $row) {
                    $i++;
                    if ($row !== "") {
                        if ($row !== "0") {
                            if ($i == 1) {
                                $this->db->where('category', $row);
                            } else {
                                $this->db->or_where('category', $row);
                            }
                        } else {
                            $this->db->where('category !=', '0');
                        }
                    }
                }
            }
            $this->db->order_by('product_id', 'desc');

            // pagination
                if ($para1 == "click") {
               
                $this->db->where('store_id', $vendorid_get);
                $query = $this->db->get('product');
               
$row_count = $query->num_rows();
            }
            elseif ($para1 == "load") {
                $row_count = $this->db->count_all_results('product');
            
            }
            $config['total_rows'] = $row_count;
            $config['base_url']   = base_url() . 'index.php?home/listed/';
            if ($featured !== 'ok') {
                $config['per_page'] = 50;
            } else if ($featured == 'ok') {
                $config['per_page'] = 50;
            }
            $config['uri_segment']  = 5;
            $config['cur_page_giv'] = $para2;

            $function                  = "do_product_search('0')";
            $config['first_link']      = '&laquo;';
            $config['first_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
            $config['first_tag_close'] = '</a></li>';

            $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
            $last_start               = floor($rr) * $config['per_page'];
            $function                 = "do_product_search('" . $last_start . "')";
            $config['last_link']      = '&raquo;';
            $config['last_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
            $config['last_tag_close'] = '</a></li>';

            $function                 = "do_product_search('" . ($para2 - $config['per_page']) . "')";
            $config['prev_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
            $config['prev_tag_close'] = '</a></li>';

            $function                 = "do_product_search('" . ($para2 + $config['per_page']) . "')";
            $config['next_link']      = '&rsaquo;';
            $config['next_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
            $config['next_tag_close'] = '</a></li>';

            $config['full_tag_open']  = '<ul class="pagination pagination-v2">';
            $config['full_tag_close'] = '</ul>';

            $config['cur_tag_open']  = '<li class="active"><a rel="grow" class="btn-u btn-u-red grow" class="active">';
            $config['cur_tag_close'] = '</a></li>';

            $function                = "do_product_search(((this.innerHTML-1)*" . $config['per_page'] . "))";
            $config['num_tag_open']  = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
            $config['num_tag_close'] = '</a></li>';
            $this->ajax_pagination->initialize($config);

            $vendorid_get = $this->session->userdata('vendorid');
            if($vendorid_get == ""){
                
                $vendorid_get ="2";
              
            }
            $store_info = $this->db->get_where('vendor', array('vendor_id' => $vendorid_get))->row()->vendor_id;
            $this->db->where('store_id', $store_info);
            $this->db->where('status', 'ok');
            if ($featured == 'ok') {
                $this->db->where('featured', 'ok');
                $grid_items_per_row = 3;
                $name               = 'Featured';
            } else {
                $grid_items_per_row = 3;
            }

            if (isset($text)) {
                if ($text !== '') {
                    $this->db->like('title', $text, 'both');
                }
            }

            if ($physical_product_activation == 'ok' && $digital_product_activation !== 'ok') {
                $this->db->where('download', NULL);
            } else if ($physical_product_activation !== 'ok' && $digital_product_activation == 'ok') {
                $this->db->where('download', 'ok');
            } else if ($physical_product_activation !== 'ok' && $digital_product_activation !== 'ok') {
                $this->db->where('product_id', '');
            }

            if ($vendor_system !== 'ok') {
                $this->db->like('added_by', '{"type":"admin"', 'both');
            }

            if ($vendor = $this->input->post('vendor')) {
                if (in_array($vendor, $vendors)) {
                    $this->db->where('added_by', '{"type":"vendor","id":"' . $vendor . '"}');
                } else {
                    $this->db->where('product_id', '');
                }
            }


            if ($brand !== '0' && $brand !== '') {
                $this->db->where('brand', $brand);
            }

            if (isset($range)) {
                $p = explode(';', $range);
                $this->db->where('sale_price >=', $p[0]);
                $this->db->where('sale_price <=', $p[1]);
            }

            $query = array();
            if (count($sub_category) > 0) {
                $i = 0;
                foreach ($sub_category as $row) {
                    $i++;
                    if ($row !== "") {
                        if ($row !== "0") {
                            $query[] = $row;
                            $setter  = 'get';
                        } else {
                            $this->db->where('sub_category !=', '0');
                        }
                    }
                }
                if ($setter == 'get') {
                    $this->db->where_in('sub_category', $query);
                }
            }

            if (count($category) > 0 && $setter !== 'get') {
                $i = 0;
                foreach ($category as $rowc) {
                    $i++;
                    if ($rowc !== "") {
                        if ($rowc !== "0") {
                            if ($i == 1) {
                                $this->db->where('category', $rowc);
                            } else {
                                $this->db->or_where('category', $rowc);
                            }
                        } else {
                            $this->db->where('category !=', '0');
                        }
                    }
                }
            }

            $sort = $this->input->post('sort');

            if ($sort == 'most_viewed') {
                $this->db->order_by('number_of_view', 'desc');
            }
            if ($sort == 'condition_old') {
                $this->db->order_by('product_id', 'asc');
            }
            if ($sort == 'condition_new') {
                $this->db->order_by('product_id', 'desc');
            }
            if ($sort == 'price_low') {
                $this->db->order_by('sale_price', 'asc');
            }
            if ($sort == 'price_high') {
                $this->db->order_by('sale_price', 'desc');
            } else {
                $this->db->order_by('product_id', 'desc');
            }

            $page_data['all_products'] = $this->db->get('product', $config['per_page'], $para2)->result_array();

            if ($name != '') {
                $name .= ' : ';
            }
            if (isset($rowc)) {
                $cat = $rowc;
            } else {
                if ($setter == 'get') {
                    $cat = $this->crud_model->get_type_name_by_id('sub_category', $sub_category[0], 'category');
                }
            }
            if ($cat !== '') {
                if ($cat !== '0') {
                    $name .= $this->crud_model->get_type_name_by_id('category', $cat, 'category_name');
                } else {
                    $name = 'All Products';
                }
            } else {
                $name = 'All Products';
            }
        } elseif ($para1 == "load") {
            $page_data['all_products'] = $this->db->get('product')->result_array();
        }
        $page_data['vendor_system']      = $this->db->get_where('general_settings', array('type' => 'vendor_system'))->row()->value;
        $page_data['category_data']      = $category;
        $page_data['viewtype']           =  $this->input->post('view_type');
        $page_data['name']               = $name;
        $page_data['count']              = $config['total_rows'];
        $page_data['grid_items_per_row'] = $grid_items_per_row;
        $this->load->view('front/product_list/other/listed', $page_data);
    }


    /* FUNCTION: Loads Custom Pages */
    function store_locator($parmalink = '')
    {
        if ($this->crud_model->get_settings_value('general_settings', 'vendor_system') !== 'ok') {
            redirect(base_url() . 'index.php/home');
        }
        $page_data['page_name']        = "others/store_locator";
        $page_data['asset_page']       = "store_locator";
        $page_data['page_title']       = translate('store_locator');
        $page_data['vendors'] = $this->db->get_where('vendor', array('status' => 'approved'))->result_array();
        $page_data['text'] = $parmalink;
        $this->load->view('front/index', $page_data);
    }


    /* FUNCTION: Loads Custom Pages */
    function page($parmalink = '')
    {
        $pagef                   = $this->db->get_where('page', array(
            'parmalink' => $parmalink
        ));
        if ($pagef->num_rows() > 0) {
            $page_data['page_name']  = "others/custom_page";
            $page_data['asset_page']  = "page";
            $page_data['tags']  = $pagef->row()->tag;
            $page_data['page_title'] = $parmalink;
            $page_data['page_items'] = $pagef->result_array();
            if ($this->session->userdata('admin_login') !== 'yes' && $pagef->row()->status !== 'ok') {
                redirect(base_url() . 'index.php/home/', 'refresh');
            }
        } else {
            redirect(base_url() . 'index.php/home/', 'refresh');
        }
        $this->load->view('front/index', $page_data);
    }


    /* FUNCTION: Loads Product View Page */
    function product_view($para1 = "", $para2 = "")
    {
        $product_data  = $this->db->get_where('product', array('product_id' => $para1, 'status' => 'ok'));

        $this->db->where('product_id', $para1);


        $this->db->update('product', array(
            'number_of_view' => $product_data->row()->number_of_view + 1,
            'last_viewed' => time()
        ));


        if ($product_data->row()->download == 'ok') {
            $type = 'digital';
        } else {
            $type = 'other';
        }

        $this->db->select_max('batch_no');
        $this->db->where('pid', $para1);
        $this->db->where('status', 1);
        $this->db->where('payment_status', 1);

        $resa2 = $this->db->get('bidding_history')->result_array();
        $page_data['baatch_max'] = $resa2[0]['batch_no'];

        $page_data['bidding_history'] = $this->db->get_where('bidding_history', array('pid' => $para1, 'status' => '1', 'payment_status' => '1'))->result_array();

        $this->db->select_max('bid_amt');
        $this->db->where('pid', $para1);
        $this->db->where('status', 1);
        $this->db->where('final_bidder', 0);
        $this->db->where('batch_no', 0);
        $this->db->where('payment_status', 1);

        $res1 = $this->db->get('bidding_history')->result_array();
        //echo $res1->num_rows(); 

        if ($res1[0]['bid_amt'] != '') {

            $page_data['max_amt'] = $res1[0]['bid_amt'] + 1;
        } else {
            $page_data['max_amt'] = $product_data->row()->min_bid_amount;
        }

        $this->db->select('*');
        $this->db->where('pid', $para1);
        $res3 = $this->db->get('bidding_history');

        $page_data['no_of_bidds'] = $res3->num_rows();
        $page_data['timeList'] = $this->createTimeSlots(60, '00:00', '23:00');
        $page_data['product_details'] = $this->db->get_where('product', array('product_id' => $para1, 'status' => 'ok'))->result_array();
        $page_data['page_name']    = "product_view/" . $type . "/page_view";
        $page_data['asset_page']   = "product_view_" . $type;
        $page_data['product_data'] = $product_data->result_array();
        $page_data['page_title']   = $product_data->row()->title;
        $page_data['product_tags'] = $product_data->row()->tag;

        $this->load->view('front/index', $page_data);
    }


    /* FUNCTION: Loads Product View Page */
    function quick_view($para1 = "")
    {
        $product_data              = $this->db->get_where('product', array(
            'product_id' => $para1,
            'status' => 'ok'
        ));

        if ($product_data->row()->download == 'ok') {
            $type = 'digital';
        } else {
            $type = 'other';
        }
        $page_data['product_details'] = $product_data->result_array();
        $page_data['page_title']   = $product_data->row()->title;
        $page_data['product_tags'] = $product_data->row()->tag;

        $this->load->view('front/product_view/' . $type . '/quick_view/index', $page_data);
    }
    function subscribe_view($para1 = "")
    {
        $product_data              = $this->db->get_where('product', array(
            'product_id' => $para1,
            'status' => 'ok'
        ));

        if ($product_data->row()->download == 'ok') {
            $type = 'digital';
        } else {
            $type = 'other';
        }
        $page_data['product_details'] = $product_data->result_array();
        $page_data['page_title']   = $product_data->row()->title;
        $page_data['product_tags'] = $product_data->row()->tag;

        $this->load->view('front/product_view/' . $type . '/subscribe_view/index', $page_data);
    }

    /* FUNCTION: Setting Frontend Language */
    function set_language($lang)
    {
        $this->session->set_userdata('language', $lang);
        $page_data['page_name'] = "home";
        recache();
    }

    /* FUNCTION: Setting Frontend Language */
    function set_currency($currency)
    {
        $this->session->set_userdata('currency', $currency);
        //recache();
        redirect(base_url());
    }

    /* FUNCTION: Loads Contact Page */
    function contact($para1 = "")
    {
        if ($this->crud_model->get_settings_value('general_settings', 'captcha_status', 'value') == 'ok') {
            $this->load->library('recaptcha');
        }
        $this->load->library('form_validation');
        if ($para1 == 'send') {
            $safe = 'yes';
            $char = '';
            foreach ($_POST as $row) {
                if (preg_match('/[\'^":()}{#~><>|=+¬]/', $row, $match)) {
                    $safe = 'no';
                    $char = $match[0];
                }
            }

            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('subject', 'Subject', 'required');
            $this->form_validation->set_rules('message', 'Message', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');

            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                if ($safe == 'yes') {
                    if ($this->crud_model->get_settings_value('general_settings', 'captcha_status', 'value') == 'ok') {
                        $captcha_answer = $this->input->post('g-recaptcha-response');
                        $response = $this->recaptcha->verifyResponse($captcha_answer);
                        if ($response['success']) {
                            $data['name']      = $this->input->post('name', true);
                            $data['subject']   = $this->input->post('subject');
                            $data['email']     = $this->input->post('email');
                            $data['message']   = $this->security->xss_clean(($this->input->post('message')));
                            $data['view']      = 'no';
                            $data['timestamp'] = time();

                            $this->db->insert('contact_message', $data);
                            $id = $this->db->insert_id();
                            $path = $_FILES['img']['name'];
                            $ext = pathinfo($path, PATHINFO_EXTENSION);
                            $data_banner['banner']          = 'contact_' . $id . '.' . $ext;
                            $this->crud_model->file_up("img", "contact", $id, '', 'no', '.' . $ext);
                            $this->db->where('contact_message_id', $id);
                            $this->db->update('contact', $data_banner);

                            $uid = $this->session->userdata('user_id');
                            if (isset($uid) && $uid != '') {
                                $userlog['uid'] =  $this->session->userdata('user_id');
                                $userlog['description'] = "Request Send To Contact form Successfully";
                                $this->db->insert('user_log', $userlog);
                            }

                            echo 'sent';
                        } else {
                            echo translate('captcha_incorrect');
                        }
                    } else {
                        $data['name']      = $this->input->post('name', true);
                        $data['subject']   = $this->input->post('subject');
                        $data['email']     = $this->input->post('email');
                        $data['message']   = $this->security->xss_clean(($this->input->post('message')));
                        $data['view']      = 'no';
                        $data['timestamp'] = time();
                        $this->db->insert('contact_message', $data);
                        $id = $this->db->insert_id();
                        $path = $_FILES['img']['name'];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        $data_banner['banner']          = 'contact_' . $id . '.' . $ext;
                        $this->crud_model->file_up("img", "contact", $id, '', 'no', '.' . $ext);
                        $this->db->where('contact_message_id', $id);
                        $this->db->update('contact', $data_banner);

                        $uid = $this->session->userdata('user_id');
                        if (isset($uid) && $uid != '') {
                            $userlog['uid'] =  $this->session->userdata('user_id');
                            $userlog['description'] = "Request Send To Contact form Successfully";
                            $this->db->insert('user_log', $userlog);
                        }
                        echo 'sent';
                    }
                } else {
                    echo 'Disallowed charecter : " ' . $char . ' " in the POST';
                }
            }
        } else {
            if ($this->crud_model->get_settings_value('general_settings', 'captcha_status', 'value') == 'ok') {
                $page_data['recaptcha_html'] = $this->recaptcha->render();
            }
            $page_data['page_name']      = "others/contact";
            $page_data['asset_page']      = "contact";
            $page_data['page_title']     = translate('contact');
            $this->load->view('front/index', $page_data);
        }
    }

    /* FUNCTION: Concerning Login */
    function vendor_logup($para1 = "", $para2 = "")
    {
        if ($this->crud_model->get_settings_value('general_settings', 'captcha_status', 'value') == 'ok') {
            $this->load->library('recaptcha');
        }
        $this->load->library('form_validation');
        if ($para1 == "add_info") {
            $msg = '';
            $this->load->library('form_validation');
            $safe = 'yes';
            $char = '';
            foreach ($_POST as $k => $row) {
                if (preg_match('/[\'^":()}{#~><>|=¬]/', $row, $match)) {
                    if ($k !== 'password1' && $k !== 'password2') {
                        $safe = 'no';
                        $char = $match[0];
                    }
                }
            }

            $this->form_validation->set_rules('name', 'Your First Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'valid_email|required|is_unique[vendor.email]', array('required' => 'You have not provided %s.', 'is_unique' => 'This %s already exists.'));
            $this->form_validation->set_rules('password1', 'Password', 'required|matches[password2]');
            $this->form_validation->set_rules('password2', 'Confirm Password', 'required');
            $this->form_validation->set_rules('address1', 'Address Line 1', 'required');
            $this->form_validation->set_rules('address2', 'Address Line 2', 'required');
            $this->form_validation->set_rules('display_name', 'Your Display Name', 'required');
            $this->form_validation->set_rules('state', 'State', 'required');
            $this->form_validation->set_rules('country', 'Country', 'required');
            $this->form_validation->set_rules('city', 'City', 'required');
            $this->form_validation->set_rules('zip', 'Zip', 'required');
            $this->form_validation->set_rules('terms_check', 'Terms & Conditions', 'required', array('required' => translate('you_must_agree_with_terms_&_conditions')));
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                if ($safe == 'yes') {
                    if ($this->crud_model->get_settings_value('general_settings', 'captcha_status', 'value') == 'ok') {
                        $captcha_answer = $this->input->post('g-recaptcha-response');
                        $response = $this->recaptcha->verifyResponse($captcha_answer);
                        if ($response['success']) {
                            $data['name']               = $this->input->post('name');
                            $data['email']              = $this->input->post('email');
                            $data['address1']           = $this->input->post('address1');
                            $data['address2']           = $this->input->post('address2');
                            $data['company']            = $this->input->post('company');
                            $data['display_name']       = $this->input->post('display_name');
                            $data['state']               = $this->input->post('state');
                            $data['country']               = $this->input->post('country');
                            $data['city']               = $this->input->post('city');
                            $data['zip']                   = $this->input->post('zip');
                            $data['create_timestamp']   = time();
                            $data['approve_timestamp']  = 0;
                            $data['approve_timestamp']  = 0;
                            $data['membership']         = 0;
                            $data['status']             = 'pending';

                            $data['store_city'] = $this->input->post('city');
                            $data['store_street'] = $this->input->post('address1');
                            $data['store_district'] = $this->input->post('state');
                            $data['store_country'] = $this->input->post('country');
                            $data['store_email'] = $this->input->post('email');


                            if ($this->input->post('password1') == $this->input->post('password2')) {
                                $password         = $this->input->post('password1');
                                $data['password'] = sha1($password);
                                $this->db->insert('vendor', $data);
                                //echo $this->db->last_query();
                                $msg = 'done';
                                if ($this->email_model->account_opening('vendor', $data['email'], $password) == false) {
                                    $msg = 'done';
                                } else {
                                    $msg = 'done';
                                }
                            }
                            echo $msg;
                        } else {
                            echo translate('please_fill_the_captcha');
                        }
                    } else {
                        $data['name']               = $this->input->post('name');
                        $data['email']              = $this->input->post('email');
                        $data['address1']           = $this->input->post('address1');
                        $data['address2']           = $this->input->post('address2');
                        $data['company']            = $this->input->post('company');
                        $data['display_name']       = $this->input->post('display_name');
                        $data['state']               = $this->input->post('state');
                        $data['country']               = $this->input->post('country');
                        $data['city']               = $this->input->post('city');
                        $data['zip']                   = $this->input->post('zip');
                        $data['create_timestamp']   = time();
                        $data['approve_timestamp']  = 0;
                        $data['approve_timestamp']  = 0;
                        $data['membership']         = 0;
                        $data['status']             = 'pending';


                        $data['store_city'] = $this->input->post('city');
                        $data['store_street'] = $this->input->post('address1');
                        $data['store_district'] = $this->input->post('state');
                        $data['store_country'] = $this->input->post('country');
                        $data['store_email'] = $this->input->post('email');

                        if ($this->input->post('password1') == $this->input->post('password2')) {
                            $password         = $this->input->post('password1');
                            $data['password'] = sha1($password);
                            $this->db->insert('vendor', $data);
                            $msg = 'done';
                            if ($this->email_model->account_opening('vendor', $data['email'], $password) == false) {
                                $msg = 'done';
                            } else {
                                $msg = 'done';
                            }
                        }
                        echo $msg;
                    }
                } else {
                    echo 'Disallowed charecter : " ' . $char . ' " in the POST';
                }
            }
        } else if ($para1 == 'registration') {
            if ($this->crud_model->get_settings_value('general_settings', 'vendor_system') !== 'ok') {
                redirect(base_url());
            }
            if ($this->crud_model->get_settings_value('general_settings', 'captcha_status', 'value') == 'ok') {
                $page_data['recaptcha_html'] = $this->recaptcha->render();
            }
            $page_data['page_name'] = "vendor/register";
            $page_data['asset_page'] = "register";
            $page_data['page_title'] = translate('registration');
            $this->load->view('front/index', $page_data);
        }
    }
    function vendor_login_msg()
    {
        $page_data['page_name'] = "vendor/register/login_msg";
        $page_data['asset_page'] = "register";
        $page_data['page_title'] = translate('registration');
        $this->load->view('front/index', $page_data);
    }
    /* FUNCTION: Concerning Login */
    function login($para1 = "", $para2 = "")
    {


        $page_data['page_name'] = "login";

        $this->load->library('form_validation');
        if ($para1 == "do_login") {
            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $signin_data = $this->db->get_where('user', array(
                    'email' => $this->input->post('email'),
                    'password' => sha1($this->input->post('password'))
                ));
                if ($signin_data->num_rows() > 0) {
                    foreach ($signin_data->result_array() as $row) {
                        $this->session->set_userdata('user_login', 'yes');
                        $this->session->set_userdata('user_id', $row['user_id']);
                        $this->session->set_userdata('user_name', $row['username']);
                        $this->session->set_userdata('user_email', $row['email']);
                        $this->session->set_flashdata('alert', 'successful_signin');
                        $this->db->where('user_id', $row['user_id']);


                        $userlog['uid'] =  $row['user_id'];
                        $userlog['description'] = "Login Successfully";
                        $this->db->insert('user_log', $userlog);

                        $this->db->update('user', array(
                            'last_login' => time()
                        ));
                        echo "login successfull";
                        //  redirect(base_url());
                        echo "<script>window.location.href='".base_url()."';</script>";
                        
                    }
                } else {
                    echo 'failed';
                }
            }
        } else if ($para1 == 'forget') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required');

            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $query = $this->db->get_where('user', array(
                    'email' => $this->input->post('email')
                ));
                if ($query->num_rows() > 0) {
                    $user_id          = $query->row()->user_id;
                    $phone          = $query->row()->phone;
                    $password         = substr(hash('sha512', rand()), 0, 12);
                    $data['password'] = sha1($password);
                    $this->db->where('user_id', $user_id);
                    $this->db->update('user', $data);
                    // echo $this->db->last_query();
                    /* $sid    = "AC87fe6c76cdfcb3dba5034f623690b040";
                    $token  = "5076c56d95cd4cb40a7a0e9cd4f14ca6";
                    $messagingServiceSid     =  "+60146482623";

                    $mobile = "+91" . $phone;
                    $sms  = "'Your new password is " . $password . ", Thanks";

                    require_once(APPPATH . 'libraries/Twilio/Twilio.php');
                    $ordersms = sendotp($sid, $token, $messagingServiceSid, $mobile, $sms); */
                    if ($this->email_model->password_reset_email('user', $user_id, $password)) {
                        echo 'email sent successfully';
                    } else {
                        echo 'email_not_sent';
                    }
                } else {
                    echo 'email_nay';
                }
            }
        } elseif ($para1 == 'add_address') {


            // $this->load->library('form_validation');
            // $safe = 'yes';
            // $char = '';

            // $this->form_validation->set_rules('name', 'name', 'required');
            // $this->form_validation->set_rules('mobile', 'Mobile', 'required|min_length[10]|max_length[11]', array('required' => 'You have not provided %s.'));
            // $this->form_validation->set_rules('email', 'Email', 'valid_email|required', array('required' => 'You have not provided %s.'));
            // $this->form_validation->set_rules('street_address', 'Address', 'required');
            // // $this->form_validation->set_rules('latitude', 'Latitude', 'required');
            // // $this->form_validation->set_rules('longitude', 'longitude', 'required');

            // $this->form_validation->set_rules('country', 'Country', 'required');

            // if ($this->form_validation->run() == FALSE) {
            //     echo validation_errors();
            // } else {
            //     if ($safe == 'yes') {
                    // $query = $this->db->get_where('shipping_address', array(
                    //     'user_id' => $this->session->userdata('user_id'), 'set_default' => "1"
                    // ));
                    // if ($query->num_rows() > 0) {
                    //     $data['set_default'] = "0";
                    // } else {
                    //     $data['set_default'] = "1";
                    // }
                    $data['set_default'] = "0";
                    $userID = $data['user_id'] = $id = $this->session->userdata('user_id');
                    $data['name'] = $this->input->post('name');
                    $data['mobile'] = $this->input->post('mobile');
                    $data['email'] = $this->input->post('email');
                    $data['latitude'] = $this->input->post('latitude');
                    $data['longitude'] = $this->input->post('longitude');
                    $data['address'] = $this->input->post('street_address');
                    $data['address1'] = $this->input->post('street_address2');
                    $data['city'] = $this->input->post('cities');
                    $data['state'] = $this->input->post('state');
                    $data['country'] = $this->input->post('country') . '-' . $this->input->post('cou_shrt1');
                    $data['zip_code'] = $this->input->post('zip_code');
                    $unicid = 'SHIP' . substr(time(), 4) . rand(100000, 999999);

                    $data['unique_id '] = $unicid;

                    $this->db->insert('shipping_address', $data);
                   
        }

        //$this->load->view('front/index', $page_data);
    }
    /* FUNCTION: Setting login page with facebook and google */
    function login_set($para1 = '', $para2 = '', $para3 = '')
    {
        if ($this->session->userdata('user_login') == "yes") {
            redirect(base_url() . 'index.php/home/profile', 'refresh');
        }
        if ($this->crud_model->get_settings_value('general_settings', 'captcha_status', 'value') == 'ok') {
            $this->load->library('recaptcha');
        }
        $this->load->library('form_validation');

        $fb_login_set = $this->crud_model->get_settings_value('general_settings', 'fb_login_set');
        $g_login_set  = $this->crud_model->get_settings_value('general_settings', 'g_login_set');
        $page_data    = array();
        $appid        = $this->db->get_where('general_settings', array(
            'type' => 'fb_appid'
        ))->row()->value;
        $secret       = $this->db->get_where('general_settings', array(
            'type' => 'fb_secret'
        ))->row()->value;
        $config       = array(
            'appId' => $appid,
            'secret' => $secret
        );
        $this->load->library('Facebook', $config);

        if ($fb_login_set == 'ok') {
            // Try to get the user's id on Facebook
            $userId = $this->facebook->getUser();

            // If user is not yet authenticated, the id will be zero
            if ($userId == 0) {
                // Generate a login url
                //$page_data['url'] = $this->facebook->getLoginUrl(array('scope'=>'email')); 
                $page_data['url'] = $this->facebook->getLoginUrl(array(
                    'redirect_uri' => site_url('home/login_set/back/' . $para2),
                    'scope' => array(
                        "email", "public_profile"
                    ) // permissions here
                ));
                //redirect($data['url']);
            } else {
                // Get user's data and print it
                $page_data['user'] = $this->facebook->api('/me');
                $page_data['url']  = site_url('home/login_set/back/' . $para2); // Logs off application
                //print_r($user);
            }
            if ($para1 == 'back') {
                $user = $this->facebook->api('/me');
                if ($user_id = $this->crud_model->exists_in_table('user', 'fb_id', $user['id'])) {
                } else {
                    $data['username']      = $user['first_name'];
                    $data['surname']       = $user['last_name'];
                    $data['email']         = $user['email'];
                    $data['fb_id']         = $user['id'];
                    $data['wishlist']      = '[]';
                    $data['creation_date'] = time();
                    $data['password']      = substr(hash('sha512', rand()), 0, 12);

                    $this->db->insert('user', $data);
                    $user_id = $this->db->insert_id();
                }
                $this->session->set_userdata('user_login', 'yes');
                $this->session->set_userdata('user_id', $user_id);
                $this->session->set_userdata('user_name', $this->db->get_where('user', array(
                    'user_id' => $user_id
                ))->row()->username);
                $this->session->set_flashdata('alert', 'successful_signin');

                $this->db->where('user_id', $user_id);
                $this->db->update('user', array(
                    'last_login' => time()
                ));

                if ($para2 == 'cart') {
                    redirect(base_url() . 'index.php/home/cart_checkout', 'refresh');
                } else {
                    redirect(base_url() . 'index.php/home', 'refresh');
                }
            }
        }


        if ($g_login_set == 'ok') {
            $this->load->library('googleplus');
            if (isset($_GET['code'])) { //just_logged in
                // echo $_GET['code'];
                $this->googleplus->client->authenticate();
                $_SESSION['token'] = $this->googleplus->client->getAccessToken();
                $g_user            = $this->googleplus->people->get('me');
                if ($user_id = $this->crud_model->exists_in_table('user', 'g_id', $g_user['id'])) {
                } else {
                    $data['username']      = $g_user['displayName'];
                    $data['email']         = 'required';
                    $data['wishlist']      = '[]';
                    $data['g_id']          = $g_user['id'];

                    $data['g_photo']       = $g_user['image']['url'];
                    $data['creation_date'] = time();
                    $data['password']      = substr(hash('sha512', rand()), 0, 12);
                    $this->db->insert('user', $data);
                    $user_id = $this->db->insert_id();
                }
                $this->session->set_userdata('user_login', 'yes');
                $this->session->set_userdata('user_id', $user_id);
                $this->session->set_userdata('user_name', $this->db->get_where('user', array(
                    'user_id' => $user_id
                ))->row()->username);
                $this->session->set_flashdata('alert', 'successful_signin');

                $this->db->where('user_id', $user_id);
                $this->db->update('user', array(
                    'last_login' => time()
                ));

                if ($para2 == 'cart') {
                    redirect(base_url() . 'index.php/home/cart_checkout', 'refresh');
                } else {
                    redirect(base_url() . 'index.php/home', 'refresh');
                }
            }
            if (@$_SESSION['token']) {
                $this->googleplus->client->setAccessToken($_SESSION['token']);
            }
            if ($this->googleplus->client->getAccessToken()) //already_logged_in
            {
                $page_data['g_user'] = $this->googleplus->people->get('me');
                $page_data['g_url']  = $this->googleplus->client->createAuthUrl();
                $_SESSION['token']   = $this->googleplus->client->getAccessToken();
            } else {
                $page_data['g_url'] = $this->googleplus->client->createAuthUrl();
            }
        }

        if ($para1 == 'login') {
            $page_data['page_name'] = "user/login";
            $page_data['asset_page'] = "login";
            $page_data['page_title'] = translate('login');
            if ($para2 == 'modal') {
                $this->load->view('front/user/login/quick_modal', $page_data);
            } else {
                $this->load->view('front/index', $page_data);
            }
        } elseif ($para1 == 'registration') {
            if ($this->crud_model->get_settings_value('general_settings', 'captcha_status', 'value') == 'ok') {
                $page_data['recaptcha_html'] = $this->recaptcha->render();
            }
            $page_data['page_name'] = "user/register";
            $page_data['asset_page'] = "register";
            $page_data['page_title'] = translate('registration');
            if ($para2 == 'modal') {
                $this->load->view('front/user/register/index', $page_data);
            } else {
                $this->load->view('front/index', $page_data);
            }
        }
    }

    /* FUNCTION: Logout set */
    function logout()
    {
        $appid  = $this->db->get_where('general_settings', array(
            'type' => 'fb_appid'
        ))->row()->value;
        $secret = $this->db->get_where('general_settings', array(
            'type' => 'fb_secret'
        ))->row()->value;
        $config = array(
            'appId' => $appid,
            'secret' => $secret
        );
        $this->load->library('Facebook', $config);

        $userlog['uid'] =  $this->session->userdata('user_id');
        $userlog['description'] = "Logout Successfully";
        $this->db->insert('user_log', $userlog);

        $this->facebook->destroySession();
        $this->session->sess_destroy();

echo "<script>window.location.href='".base_url()."';</script>";
        // redirect(base_url() . 'index.php', 'refresh');
    }

    /* FUNCTION: Logout */
    function logged_out()
    {
        $this->session->set_flashdata('alert', 'successful_signout');
        redirect(base_url() . 'index.php/home/', 'refresh');
    }

    /* FUNCTION: Check if Email user exists */
    function exists()
    {
        $email  = $this->input->post('email');
        $user   = $this->db->get('user')->result_array();
        $exists = 'no';
        foreach ($user as $row) {
            if ($row['email'] == $email) {
                $exists = 'yes';
            }
        }
        echo $exists;
    }

    /* FUNCTION: Newsletter Subscription */
    function subscribe()
    {
        $safe = 'yes';
        $char = '';
        foreach ($_POST as $row) {
            if (preg_match('/[\'^":()}{#~><>|=+¬]/', $row, $match)) {
                $safe = 'no';
                $char = $match[0];
            }
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
        } else {
            if ($safe == 'yes') {
                $subscribe_num = $this->session->userdata('subscriber');
                $email         = $this->input->post('email');
                $subscriber    = $this->db->get('subscribe')->result_array();
                $exists        = 'no';
                foreach ($subscriber as $row) {
                    if ($row['email'] == $email) {
                        $exists = 'yes';
                    }
                }
                if ($exists == 'yes') {
                    echo 'already';
                } else if ($subscribe_num >= 3) {
                    echo 'already_session';
                } else if ($exists == 'no') {
                    $subscribe_num = $subscribe_num + 1;
                    $this->session->set_userdata('subscriber', $subscribe_num);
                    $data['email'] = $email;
                    $this->db->insert('subscribe', $data);
                    echo 'done';
                }
            } else {
                echo 'Disallowed charecter : " ' . $char . ' " in the POST';
            }
        }
    }

    function getAllPreOrders()
    {
        $currentDate = date_create($this->input->post('currentDate'));
        $allPreOrders = $this->db->get_where('pre_order', ['status' => 'ok'])->result_array();
        $finalPreOrders = [];
        echo ($this->input);
        echo("currentDate - $currentDate"." --allPreOrders $allPreOrders ");
        foreach ($allPreOrders as $order) {
            $getDate = date_create($order['end_date']);
            $diff = date_diff($currentDate, $getDate);
            if ($diff->format('%R%a') >= 0) {
                # need to show this pre order
                $finalPreOrders[] = $order;
            }
        }
        if ($finalPreOrders[0]) {
            echo json_encode($finalPreOrders[0]);
        } else {
            echo 'no';
        }
    }

    /* FUNCTION: Customer Registration*/
    function registration($para1 = "", $para2 = "")
    {
        $safe = 'yes';
        $char = '';
        foreach ($_POST as $k => $row) {
            if (preg_match('/[\'^":()}{#~><>|=¬]/', $row, $match)) {
                if ($k !== 'password1' && $k !== 'password2') {
                    $safe = 'no';
                    $char = $match[0];
                }
            }
        }
        if ($this->crud_model->get_settings_value('general_settings', 'captcha_status', 'value') == 'ok') {
            $this->load->library('recaptcha');
        }
        $this->load->library('form_validation');
        $page_data['page_name'] = "registration";
        if ($para1 == "add_info") {
            $msg = '';
            $this->form_validation->set_rules('username', 'Your First Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|is_unique[user.email]|valid_email', array('required' => 'You have not provided %s.', 'is_unique' => 'This %s already exists.'));
            $this->form_validation->set_rules('password1', 'Password', 'required|matches[password2]');
            $this->form_validation->set_rules('password2', 'Confirm Password', 'required');
            $this->form_validation->set_rules('address1', 'Address Line 1', 'required');
            // $this->form_validation->set_rules('address2', 'Address Line 2', 'required');
            $this->form_validation->set_rules('phone', 'Phone', 'required|min_length[10]|max_length[11]', array('required' => 'You have not provided %s.'));
            $this->form_validation->set_rules('surname', 'Your Last Name', 'required');
            $this->form_validation->set_rules('zip', 'ZIP', 'required');
            $this->form_validation->set_rules('city', 'City', 'required');
            $this->form_validation->set_rules('state', 'State', 'required');
            $this->form_validation->set_rules('country', 'Country', 'required');
            // $this->form_validation->set_rules('age', 'Age', 'required');
            // $this->form_validation->set_rules('gender', 'Gender', 'required');
            $this->form_validation->set_rules('terms_check', 'Terms & Conditions', 'required', array('required' => translate('you_must_agree_with_terms_&_conditions')));

            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                if ($safe == 'yes') {
                    if ($this->crud_model->get_settings_value('general_settings', 'captcha_status', 'value') == 'ok') {

                        $captcha_answer = $this->input->post('g-recaptcha-response');
                        $response = $this->recaptcha->verifyResponse($captcha_answer);
                        if ($response['success']) {
                            $data['username']      = $this->input->post('username');
                            $data['email']         = $this->input->post('email');
                            $data['address1']      = $this->input->post('address1');
                            $data['address2']      = $this->input->post('address2');
                            $data['phone']         = $this->input->post('phone');
                            $data['surname']       = $this->input->post('surname');
                            $data['zip']           = $this->input->post('zip');
                            $data['city']          = $this->input->post('city');
                            $data['state']         = $this->input->post('state');
                            $data['age']           = $this->input->post('age');
                            $data['gender']        = $this->input->post('gender');
                            $data['country']       = $this->input->post('country');
                            $data['langlat']       = '';
                            $data['wishlist']      = '[]';
                            $data['creation_date'] = time();

                            $user_name=$this->input->post('username');
                            $sur_name=$this->input->post('surname');
                            $ship['name']      = $user_name." ".$sur_name;
                            $ship['email']         = $this->input->post('email');
                            $ship['address']      = $this->input->post('address1');
                            $ship['latitude']      = $this->input->post('latitude');
                            $ship['longitude']      = $this->input->post('longitude');
                            $ship['address1']      = $this->input->post('address2');
                            $ship['mobile']         = $this->input->post('phone');
                           // $data['surname']       = $this->input->post('surname');
                            $ship['zip_code']           = $this->input->post('zip');
                            $ship['city']          = $this->input->post('city');
                            $ship['state']         = $this->input->post('state');
                         //   $data['age']           = $this->input->post('age');
                            $ship['set_default']        = "1";
                            $ship['country']       = $this->input->post('country');

                            if ($this->input->post('password1') == $this->input->post('password2')) {
                                $password         = $this->input->post('password1');
                                $data['password'] = sha1($password);
                                $this->db->insert('user', $data);
                                $user_id_get = $this->db->insert_id();
                                $ship['user_id'] =  $user_id_get;
                                $unicid = 'REG' . substr(time(), 4) . rand(100000, 999999);
                                $ship['unique_id']=$unicid ;
                                $this->db->insert('shipping_address', $ship);
                                $insertIDSS = $this->db->insert_id();
                                $userlog['uid'] = $insertIDSS;
                                $userlog['description'] = "Register Successfully";
                                $this->db->insert('user_log', $userlog);



                                $msg = 'done';
                                if ($this->email_model->account_opening('user', $data['email'], $password) == false) {
                                    $msg = 'done';
                                } else {
                                    $msg = 'done';
                                }
                            }
                            echo $msg;
                        } else {
                            echo translate('please_fill_the_captcha');
                        }
                    } else {
                        $data['username']      = $this->input->post('username');
                        $data['email']         = $this->input->post('email');
                        $data['address1']      = $this->input->post('address1');
                        $data['address2']      = $this->input->post('address2');
                        $data['phone']         = $this->input->post('phone');
                        $data['surname']       = $this->input->post('surname');
                        $data['zip']           = $this->input->post('zip');
                        $data['city']          = $this->input->post('city');
                        $data['state']          = $this->input->post('state');
                        $data['country']          = $this->input->post('country');
                        $data['age']           = $this->input->post('age');
                        $data['gender']        = $this->input->post('gender');
                        $data['langlat']       = '';
                        $data['wishlist']      = '[]';
                        $data['creation_date'] = time();

                        $user_name=$this->input->post('username');
                        $sur_name=$this->input->post('surname');
                        $ship['name']      = $user_name." ".$sur_name;
                        $ship['email']         = $this->input->post('email');
                        $ship['address']      = $this->input->post('address1');
                        $ship['address1']      = $this->input->post('address2');
                        $ship['mobile']         = $this->input->post('phone');
                        $ship['latitude']      = $this->input->post('latitude');
                        $ship['longitude']      = $this->input->post('longitude');
                       // $data['surname']       = $this->input->post('surname');
                        $ship['zip_code']           = $this->input->post('zip');
                        $ship['city']          = $this->input->post('city');
                        $ship['state']         = $this->input->post('state');
                     //   $data['age']           = $this->input->post('age');
                        $ship['set_default']        = "1";
                        $ship['country']       = $this->input->post('country');

                        if ($this->input->post('password1') == $this->input->post('password2')) {
                            $password         = $this->input->post('password1');
                            $data['password'] = sha1($password);
                            $this->db->insert('user', $data);
                            $user_id_get = $this->db->insert_id();
                            $ship['user_id'] =  $user_id_get;
                            $unicid = 'REG' . substr(time(), 4) . rand(100000, 999999);
                            $ship['unique_id']=$unicid ;
                            $this->db->insert('shipping_address', $ship);
                            $insertIDSS = $this->db->insert_id();
                            $userlog['uid'] = $insertIDSS;
                            $userlog['description'] = "Register Successfully";
                            $this->db->insert('user_log', $userlog);

                            $msg = 'done';
                            $kaleyra_sms     =  $this->db->get_where('general_settings', array('general_settings_id' => '110'))->row()->kaleyra_sms_set;
                            if ($kaleyra_sms == 'ok') {
                                $mobile = $data['phone'];
                                $body  = "'Your registeration as a user was successfully, Thanks";
                                $template_id = 1607100000000112238;
                                $sendotp = $this->crud_model->send_sms_kaleyria($mobile, $template_id, $body);
                            }
                            $twilio_sms     =  $this->db->get_where('general_settings', array('general_settings_id' => '110'))->row()->twilio_sms_set;
                            if ($twilio_sms == 'ok') {
                                $sid     =  $this->db->get_where('general_settings', array('general_settings_id' => '115'))->row()->t_account_sid;
                                $token     =  $this->db->get_where('general_settings', array('general_settings_id' => '116'))->row()->t_auth_token;
                                $messagingServiceSid     =  $this->db->get_where('general_settings', array('general_settings_id' => '117'))->row()->twilio_number;
                                //  $sms="Your OTP: ".$otp."";
                                $mobile = $data['phone'];
                                $sms  = "'Your registeration as a user was successfully, Thanks";

                                require_once(APPPATH . 'libraries/Twilio/Twilio.php');
                                $ordersms = sendotp($sid, $token, $messagingServiceSid, $mobile, $sms);
                            }
                            if ($this->email_model->account_opening('user', $data['email'], $password) == false) {
                                $msg = 'done';
                            } else {
                                $msg = 'done';
                            }
                        }
                        echo $msg;
                    }
                } else {
                    echo 'Disallowed charecter : " ' . $char . ' " in the POST';
                }
            }
        } else if ($para1 == "update_info") {
            $this->form_validation->set_rules('phone', 'Phone', 'required|min_length[10]|max_length[11]', array('required' => 'You have not provided %s.'));
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
            $id = $this->session->userdata('user_id');
            $data['username']    = $this->input->post('username');
            $data['surname']     = $this->input->post('surname');
            $data['address1']    = $this->input->post('address1');
            $data['address2']    = $this->input->post('address2');
            $data['phone']       = $this->input->post('phone');
            $data['city']        = $this->input->post('city');
            $data['state']          = $this->input->post('state');
            $data['country']          = $this->input->post('country');
            $data['skype']       = $this->input->post('skype');
            $data['google_plus'] = $this->input->post('google_plus');
            $data['facebook']    = $this->input->post('facebook');
            $data['zip']         = $this->input->post('zip');
            
            $user_name1=$this->input->post('username');
            $sur_name1=$this->input->post('surname');
            $ship['name']      = $user_name1." ".$sur_name1;
            $ship['address']    = $this->input->post('address1');
            $ship['address1']    = $this->input->post('address2');
            $ship['latitude']      = $this->input->post('latitude');
            $ship['longitude']      = $this->input->post('longitude');
            $ship['mobile']       = $this->input->post('phone');
            $ship['city']        = $this->input->post('city');
            $ship['state']          = $this->input->post('state');
            $ship['country']          = $this->input->post('country');
            $ship['zip_code']         = $this->input->post('zip');

            if($this->input->post('currency_settings'))
            $data['default_currency_id']  = $this->input->post('currency_settings');
            else
            $data['default_currency_id']  =0;
            
            $this->db->where('user_id', $id);
            $this->db->update('user', $data);
            
            $this->db->where('user_id',$id);
            $this->db->like('unique_id', 'REG','after');
            $this->db->update('shipping_address', $ship);

            $userlog['uid'] = $id;
            $userlog['description'] = "Profile Update Successfully";
            $this->db->insert('user_log', $userlog);
            

            echo "done";
            }
        } else if ($para1 == "update_password") {
            $user_data['password'] = $this->input->post('password');
            $account_data          = $this->db->get_where('user', array(
                'user_id' => $this->session->userdata('user_id')
            ))->result_array();
            foreach ($account_data as $row) {
                if (sha1($user_data['password']) == $row['password']) {
                    if ($this->input->post('password1') == $this->input->post('password2')) {
                        $data['password'] = sha1($this->input->post('password1'));
                        $this->db->where('user_id', $this->session->userdata('user_id'));
                        $this->db->update('user', $data);

                        $userlog['uid'] = $this->session->userdata('user_id');
                        $userlog['description'] = "Password Update Successfully";
                        $this->db->insert('user_log', $userlog);
                        // echo "done";
                        echo translate('password_changed_successfully!');
                    } else {
                        echo translate('passwords_did_not_match!');
                    }
                } else {
                    echo translate('wrong_old_password!');
                }
            }
        } else if ($para1 == "change_picture") {
            $id = $this->session->userdata('user_id');
            $this->crud_model->file_up('img', 'user', $id, '', '', '.jpg');
            echo 'done';
        } else {
            $this->load->view('front/registration', $page_data);
        }
    }

    function error()
    {
        $prod=explode("/",parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $product_name = urldecode($prod[count($prod)-1]);
        $page_id = $this->db->get_where('page_seo', array('keywords' => $product_name,'active_status'=>"1"))->row()->page_id;
        if($page_id=="")
        {$page_id = $this->db->get_where('page_seo', array('description' => $product_name,'active_status'=>"1"))->row()->page_id;}
        if($page_id!="")
        {
            $parmalink = $this->db->get_where('page', array('page_id' => $page_id))->row()->parmalink;
            echo file_get_contents(base_url() . 'index.php/'.$parmalink);
        }
        else
        {
        $product_id = $this->db->get_where('product_seo', array('keywords' => $product_name,'active_status'=>"1"))->row()->product_id;
        if($product_id=="")
        {$product_id = $this->db->get_where('product_seo', array('description' => $product_name,'active_status'=>"1"))->row()->product_id;}
        if($product_id!="")
        {
            $product_data  = $this->db->get_where('product', array('product_id' => $product_id, 'status' => 'ok'));
            $this->db->where('product_id', $product_id);
            $this->db->update('product', array(
                'number_of_view' => $product_data->row()->number_of_view + 1,
                'last_viewed' => time()
            ));
    
            if ($product_data->row()->download == 'ok') {
                $type = 'digital';
            } else {
                $type = 'other';
            }
            $this->db->select_max('batch_no');
            $this->db->where('pid', $product_id);
            $this->db->where('status', 1);
            $this->db->where('payment_status', 1);
            $resa2 = $this->db->get('bidding_history')->result_array();
            $page_data['baatch_max'] = $resa2[0]['batch_no'];
            $page_data['bidding_history'] = $this->db->get_where('bidding_history', array('pid' => $product_id, 'status' => '1', 'payment_status' => '1'))->result_array();
            $this->db->select_max('bid_amt');
            $this->db->where('pid', $product_id);
            $this->db->where('status', 1);
            $this->db->where('final_bidder', 0);
            $this->db->where('batch_no', 0);
            $this->db->where('payment_status', 1);
    
            $res1 = $this->db->get('bidding_history')->result_array();
            if ($res1[0]['bid_amt'] != '') {
                $page_data['max_amt'] = $res1[0]['bid_amt'] + 1;
            } else {
                $page_data['max_amt'] = $product_data->row()->min_bid_amount;
            }
            $this->db->select('*');
            $this->db->where('pid', $product_id);
            $res3 = $this->db->get('bidding_history');
    
            $page_data['no_of_bidds'] = $res3->num_rows();
            $page_data['timeList'] = $this->createTimeSlots(60, '00:00', '23:00');
            $page_data['product_details'] = $this->db->get_where('product', array('product_id' => $product_id, 'status' => 'ok'))->result_array();
            $page_data['page_name']    = "product_view/" . $type . "/page_view";
            $page_data['asset_page']   = "product_view_" . $type;
            $page_data['product_data'] = $product_data->result_array();
            $page_data['page_title']   = $product_data->row()->title;
            $page_data['product_tags'] = $product_data->row()->tag;
            $this->load->view('front/index', $page_data);
    
        }
        else
        {
            $this->load->view('front/others/404_error');
        }
        }
    }


    /* FUNCTION: Product rating*/
    function rating($product_id, $rating)
    {
        if ($this->session->userdata('user_login') != "yes") {
            redirect(base_url() . 'index.php/home/login/', 'refresh');
        }
        if ($rating <= 5) {
            if ($this->crud_model->set_rating($product_id, $rating) == 'yes') {
                echo 'success';
            } else if ($this->crud_model->set_rating($product_id, $rating) == 'no') {
                echo 'already';
            }
        } else {
            echo 'failure';
        }
    }

    /* FUNCTION: Concerning Compare*/
    function compare($para1 = "", $para2 = "")
    {
        if ($para1 == 'add') {
            $this->crud_model->add_compare($para2);
        } else if ($para1 == 'remove') {
            $this->crud_model->remove_compare($para2);
        } else if ($para1 == 'num') {
            echo $this->crud_model->compared_num();
        } else if ($para1 == 'clear') {
            $this->session->set_userdata('compare', '');
            redirect(base_url() . 'index.php/home', 'refresh');
        } else if ($para1 == 'get_detail') {
            $product = $this->db->get_where('product', array('product_id' => $para2));
            $return = array();
            $return += array('image' => '<img src="' . $this->crud_model->file_view('product', $para2, '', '', 'thumb', 'src', 'multi', 'one') . '" width="100" />');
            $return += array('price' => currency() . $product->row()->sale_price);
            $return += array('description' => $product->row()->description);
            if ($product->row()->brand) {
                $return += array('brand' => $this->db->get_where('brand', array('brand_id' => $product->row()->brand))->row()->name);
            }
            if ($product->row()->sub_category) {
                $return += array('sub' => $this->db->get_where('sub_category', array('sub_category_id' => $product->row()->sub_category))->row()->sub_category_name);
            }
            echo json_encode($return);
        } else {
            if ($this->session->userdata('compare') == '[]') {
                redirect(base_url() . 'index.php/home/', 'refresh');
            }
            $page_data['page_name']  = "others/compare";
            $page_data['asset_page']  = "compare";
            $page_data['page_title'] = 'compare';
            $this->load->view('front/index', $page_data);
        }
    }
    function cancel_order()
    {
        $this->session->set_userdata('sale_id', '');
        $this->session->set_userdata('couponer', '');
        $this->cart->destroy();
        // redirect(base_url(), 'refresh');
         echo "<script>window.location.href='".base_url()."';</script>";
    }

    function quantity_value($para1='')
    {
        if($para1!=""){
        echo $para1;
        }
    }
    
    /* FUNCTION: Concering Add, Remove and Updating Cart Items*/
    function cart($para1 = '', $para2 = '', $para3 = '', $para4 = '', $para5 = '', $para6 = '')
    {
        $this->cart->product_name_rules = '[:print:]';
        if ($para1 == "add") {
            $qty = $this->input->post('qty');
            $cqty = $this->input->post('color_qty');
            $color = $this->input->post('color');
            $option = array('color' => array('title' => 'Color', 'value' => $color));
            $all_op = json_decode($this->crud_model->get_type_name_by_id('product', $para2, 'options'), true);
            // print_r($this->crud_model->get_type_name_by_id('product', $para2, 'options'));
            if ($all_op) {
                foreach ($all_op as $ro) {
                    $name = $ro['name'];
                    $title = $ro['title'];
                    $option[$name] = array('title' => $title, 'value' => $this->input->post($name));
                }
            }

            if ($para3 == 'pp') {
                $carted = $this->cart->contents();
                // print_r($carted);
                foreach ($carted as $items) {
                    if ($items['id'] == $para2) {
                        $data = array(
                            'rowid' => $items['rowid'],
                            'qty' => 0
                        );
                    } else {
                        $data = array(
                            'rowid' => $items['rowid'],
                            'qty' => $items['qty']
                        );
                    }
                    $this->cart->update($data);
                }
            }

            $data = array(
                'id' => $para2,
                'qty' => $qty,
                'option' => json_encode($option),
                //'price' => $this->crud_model->get_product_price($para2),
                // 'price' => $this->crud_model->get_multi_product_price($para2, json_encode($option)),
                'price'=>$this->input->post('displayAmt_price'),
                'name' => $this->crud_model->get_type_name_by_id('product', $para2, 'title'),
                'shipping' => $this->crud_model->get_shipping_cost($para2),
                'tax' => $this->crud_model->get_product_tax($para2),
                'image' => $this->crud_model->file_view('product', $para2, '', '', 'thumb', 'src', 'multi', 'one'),
                'coupon' => '',
                'subscribamt' => ''
            );

            $stock = $this->crud_model->get_type_name_by_id('product', $para2, 'current_stock');
            $multiple_price = $this->crud_model->get_type_name_by_id('product', $para2, 'multiple_price');
            if (isset($cqty) && $qty > $cqty) {
                //$this->cart->insert($data);
                echo 'shortage';
            }
            if (!$this->crud_model->is_added_to_cart($para2) || $para3 == 'pp') {
                /*  if ($stock >= $qty || $this->crud_model->is_digital($para2)) {
                    $this->cart->insert($data);
                    echo 'added';
                } else {
                    echo 'shortage';
                } */

                if ($multiple_price == '1') {
                    $this->cart->insert($data);
                        
                        echo 'added';
                    // $multi_stock = $this->crud_model->get_product_quanty($para2, json_encode($option));
                    // if ($multi_stock >= $qty) {
                    //     $this->cart->insert($data);
                        
                    //     echo 'added';
                    // } else {
                    //     echo 'shortage';
                    // }
                } else {
                    if ($stock >= $qty || $this->crud_model->is_digital($para2)) {
                        $this->cart->insert($data);
                        echo 'added';
                    } else {
                        echo 'shortage';
                    }
                }
            } else {
                echo 'already';
            }
            //var_dump($this->cart->contents());
        }

        if ($para1 == "add_bid") {
            $qty = $this->input->post('qty');
            $color = $this->input->post('color');
            $this->session->set_userdata('bidding_stock', 'Bidding');
            $option = array('color' => array('title' => 'Color', 'value' => $color));
            $all_op = json_decode($this->crud_model->get_type_name_by_id('product', $para2, 'options'), true);
            if ($all_op) {
                foreach ($all_op as $ro) {
                    $name = $ro['name'];
                    $title = $ro['title'];
                    $option[$name] = array('title' => $title, 'value' => $this->input->post($name));
                }
            }

            if ($para3 == 'pp') {
                $carted = $this->cart->contents();
                foreach ($carted as $items) {
                    if ($items['id'] == $para2) {
                        $data = array(
                            'rowid' => $items['rowid'],
                            'qty' => 0
                        );
                    } else {
                        $data = array(
                            'rowid' => $items['rowid'],
                            'qty' => $items['qty']
                        );
                    }
                    $this->cart->update($data);
                }
            }

            $data = array(
                'id' => $para2,
                //'qty' => $qty,
                'qty' => 1,
                'option' => json_encode($option),
                'price' => $para4,
                'name' => $this->crud_model->get_type_name_by_id('product', $para2, 'title'),
                //'shipping' => $this->crud_model->get_shipping_cost($para2),
                'shipping' => $this->crud_model->get_shipping_cost($para2),
                //'tax' => $this->crud_model->get_product_tax($para2),
                'tax' => 0,
                'image' => $this->crud_model->file_view('product', $para2, '', '', 'thumb', 'src', 'multi', 'one'),
                'coupon' => ''
            );
            //print_r($data);


            $data_bid['pid'] = $para2;
            $data_bid['uid'] = $para5;
            $data_bid['uname'] = $para6;
            $data_bid['bid_amt'] = $para4;
            $data_bid['time'] = time();
            $this->session->set_userdata('unique_no', time());
            $data_bid['unique_no'] = $this->session->userdata('unique_no');


            $this->db->insert('bidding_history', $data_bid);

            $new_bidd_id           = $this->db->insert_id();

            $this->session->set_userdata('new_bidd_id', $new_bidd_id);


            $stock = $this->crud_model->get_type_name_by_id('product', $para2, 'current_stock');

            if (!$this->crud_model->is_added_to_cart($para2) || $para3 == 'pp') {
                if ($stock >= $qty || $this->crud_model->is_digital($para2)) {
                    $this->cart->insert($data);
                    echo 'added';
                    //header('location:'.base_url().'index.php/home/cart_checkout');
                    //redirect(base_url() . 'index.php/home/cart_checkout', 'refresh');
                } else {
                    echo 'shortage';
                    redirect(base_url() . 'index.php/home/cart_checkout', 'refresh');
                }
            } else {
                echo 'already';
                redirect(base_url() . 'index.php/home/cart_checkout', 'refresh');
            }
            //var_dump($this->cart->contents());
        }

        if ($para1 == "add_subs") {
            $qty = $this->input->post('qty');
            $color = $this->input->post('color');
            $option = array('color' => array('title' => 'Color', 'value' => $color));
            $all_op = json_decode($this->crud_model->get_type_name_by_id('product', $para2, 'options'), true);
            if ($all_op) {
                foreach ($all_op as $ro) {
                    $name = $ro['name'];
                    $title = $ro['title'];
                    $option[$name] = array('title' => $title, 'value' => $this->input->post($name));
                }
            }

            if ($para3 == 'pp') {
                $carted = $this->cart->contents();
                foreach ($carted as $items) {
                    if ($items['id'] == $para2) {
                        $data = array(
                            'rowid' => $items['rowid'],
                            'qty' => 0
                        );
                    } else {
                        $data = array(
                            'rowid' => $items['rowid'],
                            'qty' => $items['qty']
                        );
                    }
                    $this->cart->update($data);
                }
            }

            $data = array(
                'id' => $para2,
                'qty' => $qty,
                'option' => json_encode($option),
                'price' => $this->crud_model->get_product_price($para2),
                'name' => $this->crud_model->get_type_name_by_id('product', $para2, 'title'),
                'shipping' => $this->crud_model->get_shipping_cost($para2),
                'tax' => $this->crud_model->get_product_tax($para2),
                'image' => $this->crud_model->file_view('product', $para2, '', '', 'thumb', 'src', 'multi', 'one'),
                'coupon' => '',
                'subscribamt' => $this->input->post('subscribamt')
            );
            $subscribe_days = array(
                'mon' => $this->input->post('mon'),
                'tue' => $this->input->post('tue'),
                'wed' => $this->input->post('wed'),
                'thu' => $this->input->post('thu'),
                'fri' => $this->input->post('fri'),
                'sat' => $this->input->post('sat'),
                'sun' => $this->input->post('sun')
            );
            $data_subs['subscribe_days'] = json_encode($subscribe_days, true);
            $data_subs['quantity'] = $this->input->post('qty');
            $data_subs['product_id'] = $para2;
            $data_subs['subscribe_recharge'] = $this->input->post('subscribe_recharge');
            $data_subs['user_id'] = $this->input->post('uid');
            $data_subs['subscribe_package'] = $this->input->post('subscribe_package');
            $data_subs['subscribe_package_amount'] = $this->input->post('subscribamt');
            $data_subs['subscribe_from'] = $this->input->post('start_date');
            $data_subs['status'] = 0;
            $data_subs['time'] = time();
            $data_subs['added_by'] = $this->input->post('added_by');
            $data_subs['vendor_id'] = $this->input->post('added_by_id');
            //print_r($data_subs);
            $this->db->insert('subscribe_sale', $data_subs);

            //$new_bidd_id           = $this->db->insert_id();

            //  $this->session->set_userdata('new_bidd_id', $new_bidd_id);


            $stock = $this->crud_model->get_type_name_by_id('product', $para2, 'current_stock');

            if (!$this->crud_model->is_added_to_cart($para2) || $para3 == 'pp') {
                if ($stock >= $qty || $this->crud_model->is_digital($para2)) {
                    $this->cart->insert($data);
                    echo 'added';
                    //print_r($data);
                    //header('location:'.base_url().'index.php/home/cart_checkout');
                    //redirect(base_url() . 'index.php/home/cart_checkout', 'refresh');
                } else {
                    echo 'shortage';
                    redirect(base_url() . 'index.php/home/cart_checkout', 'refresh');
                }
            } else {
                echo 'already';
                redirect(base_url() . 'index.php/home/cart_checkout', 'refresh');
            }
            //var_dump($this->cart->contents());
        }

        if ($para1 == "added_list") {
            $page_data['carted'] = $this->cart->contents();
            $this->load->view('front/added_list', $page_data);
        }

        if ($para1 == "empty") {
            $this->cart->destroy();
            $this->session->set_userdata('couponer', '');
        }
        if ($para1 == "in_cart") {

            if ($this->crud_model->is_added_to_cart($para2)) {
                $carted = $this->cart->contents();

                foreach ($carted as $items) {

                    if ($items['id'] == $para2) {

                        $var = $items['rowid'];
                    }
                }
                echo "already---" . $var;
            } else {
                echo "failed";
            }
        }
        if ($para1 == "quantity_update") {

            $carted = $this->cart->contents();
            foreach ($carted as $items) {
                if ($items['rowid'] == $para2) {
                    $product = $items['id'];
                }
            }
            $current_quantity = $this->crud_model->get_type_name_by_id('product', $product, 'current_stock');
            $msg              = 'not_limit';

            foreach ($carted as $items) {
                if ($items['rowid'] == $para2) {
                    if ($current_quantity >= $para3) {
                        $data = array(
                            'rowid' => $items['rowid'],
                            'qty' => $para3
                        );
                    } else {
                        $msg  = $current_quantity;
                        $data = array(
                            'rowid' => $items['rowid'],
                            'qty' => $current_quantity
                        );
                    }
                } else {
                    $data = array(
                        'rowid' => $items['rowid'],
                        'qty' => $items['qty']
                    );
                }
                $this->cart->update($data);
            }
            $return = '';
            $carted = $this->cart->contents();
            foreach ($carted as $items) {
                if ($items['rowid'] == $para2) {
                    $return = currency($items['subtotal']);
                }
            }
            $return .= '---' . $msg;
            echo $return;
        }

        if ($para1 == "remove_one") {
            $carted = $this->cart->contents();
            foreach ($carted as $items) {
                if ($items['rowid'] == $para2) {
                    $data = array(
                        'rowid' => $items['rowid'],
                        'qty' => 0
                    );
                } else {
                    $data = array(
                        'rowid' => $items['rowid'],
                        'qty' => $items['qty']
                    );
                }
                $this->cart->update($data);
            }

            $carted = $this->cart->contents();
            echo count($carted);
            if (count($carted) == 0) {
                $this->cart('empty');
            }
        }


        if ($para1 == "whole_list") {
            echo json_encode($this->cart->contents());
        }

        if ($para1 == 'calcs') {
            //$total = $this->cart->total();
            $total = 0;$tax=0;
            $carted = $this->cart->contents();
            foreach ($carted as $items){
                if($items['subtotal']!='')
                {$total+=floatval($items['subtotal']);}
                $tax_1=$this->crud_model->get_product_tax($items['id']);
                if(($tax_1!='') && ($items['qty']!=''))
                {$tax+=(floatval($tax_1) * floatval($items['qty']));}
            }


            /*  if ($this->crud_model->get_type_name_by_id('business_settings', '3', 'value') == 'product_wise') {
                $shipping = $this->crud_model->cart_total_it('shipping');
            } elseif ($this->crud_model->get_type_name_by_id('business_settings', '3', 'value') == 'fixed') {
                $shipping = $this->crud_model->get_type_name_by_id('business_settings', '2', 'value');
            } */
            //  if($this->db->get_where('business_settings', array('type'=>'delivery'))->row()->status=="ok"){ 
            $shipping = 0;
            if ($this->session->userdata('user_zips') != "") {
                //  echo "pk".$this->session->userdata('pickup');
                $free_deliv = $this->db->get_where('business_settings', array('type' => 'free_delivery'))->row()->value;
                if ($total < $free_deliv) {

                    $shipping_1 = $this->db->get_where('business_settings', array('type' => 'delivery_fee'))->row()->value;
                    if($shipping_1!='')
                    {$shipping=floatval($shipping_1);}
                    else{$shipping = 0;}
                } else {
                    $shipping = 0;
                }
            }
            //}
            //$tax   = $this->crud_model->cart_total_it('tax');


            $code = $this->input->get('code');
            $coupon = $this->db->get_where('coupon',array('code'=>$code));
            $couponData = $coupon->result_array();
            foreach($couponData as $row){
                $spec = json_decode($row['spec'],true);
                // echo "hii".implode(', ',$spec)."<br>";
            }
            $type = $spec['discount_type'];
            $value = $spec['discount_value'];
            ///////////////////////////////////////

            // $totalDiscount=$this->cart->total_discount();
            $grand = $total + $shipping + $tax;
            if($type == 'amount'){
                $totalDiscount = $grand - $value;
                $val1 = number_format($value,2);
            }
            else if($type == 'percent'){
                $val1 = number_format(($grand * $value/100),2);
                $totalDiscount = $grand - $val1;
            }
            if ($para2 == 'full') {

                $ship  = $shipping;
                $count = count($this->cart->contents());
                // print_r($this->cart->contents());

                /*if ($total == '') {
                    $total = 0;
                }*/
                if ($ship == '') {
                    $ship = 0;
                }
                /*if ($tax == '') {
                    $tax = 0;
                }
                if ($grand == '') {
                    $grand = 0;
                }*/
                if ($this->session->userdata('pickup') != "") {
                    $pickup = 0;
                } else {
                    $pickup = 1;
                }

                $total = currency().number_format($total,2);
                $ship  = currency().number_format($ship,2);
                $tax   = currency().number_format($tax,2);
                if($totalDiscount){
                    $grand = $totalDiscount;
                }
                $grand = currency().number_format($grand,2);
                $free_delivery = $this->db->get_where('business_settings', array('type' => 'free_delivery'))->row()->value;
                //echo "delx".$free_delivery;
                //$totalDiscount = $this->cart->total_discount() > 0 ? currency($this->cart->total_discount()) : "RM";
                $totalDiscount=$this->cart->total_discount();
                $totalDiscount=currency().number_format((($totalDiscount!='')?floatval($totalDiscount):0),2);
                echo $total . '-' . $ship . '-' . $tax . '-' . $grand . '-' . $count . '-' . $pickup . '-' . $free_delivery . '-' . $totalDiscount . '-' .$value. '-' .$val1;
            }

            if ($para2 == 'prices') {
                $carted = $this->cart->contents();
                $return = array();
                foreach ($carted as $row) {
                    if ($row['subscribamt'] > 0) {
                        $row['subtotal'] = $row['subtotal'] * $row['subscribamt'];
                    }

                    $return[] = array('id' => $row['rowid'], 'price' => currency($row['price']), 'subtotal' => currency($row['subtotal']));
                }
                echo json_encode($return);
            }
        }
    }
    // function priceget($para1 = '')
    // {
    //     // print_r($_POST);
    //     $optionCount = $this->input->post('optionCount');
    //     $data['product_color'] = $this->input->post('color');
    //     $data['product_id'] = $pid = $this->input->post('product_id');
    //     $qty = $this->input->post('qty');
    //     $result = array("color" => $data['product_color']);

    //     for ($i = 0; $i <= $optionCount; $i++) {
    //         // echo "a1".$this->input->post('choice_'.$i);
    //         if ($this->input->post('choice_' . $i)) {

    //             $data1 = str_replace('+', ' ', $this->input->post('choice_' . $i));
    //             $data2 = explode('^', $data1);
    //             $data3 = array(str_replace(' ', '_', $data2[0]) => str_replace('+', '_', $data2[1]));
    //             $result = $result + $data3;
    //         }
    //     }
    //     $data['other_option'] = json_encode($result, true);

    //     //  echo '<pre>'; print_r($data);
    //     $gr_multiple_option1 =  $this->db->get_where('product', array('product_id' => $data['product_id'], 'status' => "ok"))->result_array();

    //     $gr_multiple_option =  $this->db->get_where('multiple_option', array('product_color' => $data['product_color'], 'product_id' => $data['product_id'], 'other_option' => $data['other_option'], 'status' => 1))->result_array();

    //     if ($gr_multiple_option) {
    //         if ($gr_multiple_option1[0]['discount'] > 0) {
    //             if ($gr_multiple_option1[0]['discount_type'] == 'percent') {
    //                 $price = $gr_multiple_option[0]['amount'] - (($gr_multiple_option[0]['amount'] * $gr_multiple_option1[0]['discount']) / 100);
    //             }
    //             if ($gr_multiple_option1[0]['discount_type'] == 'amount') {
    //                 $price = $gr_multiple_option[0]['amount'] - $gr_multiple_option1[0]['discount'];
    //             }
    //         } else {
    //             $price = $gr_multiple_option[0]['amount'];
    //         }
    //         $price = number_format((float) $price, 2, '.', '');
    //         echo $price . '^' . $gr_multiple_option[0]['quantitty'];
    //     } else {


    //         $price = $gr_multiple_option1[0]['sale_price'];
    //         $price = number_format((float) $price, 2, '.', '');
    //         echo $price . '^' . $gr_multiple_option1[0]['current_stock'];
    //     }
    // }
    function priceget($para1 = '')
    {
          //  print_r($_POST);
//   echo "PARA1--".$para1;
        $optionCount = $this->input->post('optionCount');
        $data['product_color'] = $this->input->post('color');
        $data['product_id'] = $pid = $this->input->post('product_id');
        $qty = $this->input->post('qty');
        $rad_val = $this->input->post('choice_1');
        //   echo "QTYY--".$rad_val."--QTY";
        $result = array("color" => $data['product_color']);


        for ($i = 0; $i <= $optionCount; $i++) {
            
            if ($this->input->post('choice_' . $i)) {

                $data1 = str_replace('+', ' ', $this->input->post('choice_' . $i));
                // echo "data1--".$data1. "<br>";
                $data2 = explode('^', $data1);
                // print_r($data2)."<br>";
                $data3 = array(str_replace(' ', '_', $data2[0]) => str_replace('+', '_', $data2[1]));

                //  print_r($data3)."<br>";
                $result = $result + $data3;
                // print_r($result);
                
            }
        }
        
        $data['other_option'] = json_encode($result, true);


        //  echo '<pre>'; print_r($data);
        $gr_multiple_option1 =  $this->db->get_where('product', array('product_id' => $data['product_id'], 'status' => "ok"))->result_array();
        // echo "opt1-". $gr_multiple_option1;


        $gr_multiple_option =  $this->db->get_where('multiple_option', array('product_id' => $data['product_id'], 'status' => 1))->result_array();
        // print_r($gr_multiple_option);
        // if ($gr_multiple_option) {
            
        //     if ($gr_multiple_option1[0]['discount'] > 0) {
                
        //         if ($gr_multiple_option1[0]['discount_type'] == 'percent') {
        //             echo "A";
                    
        //             $price = $gr_multiple_option[0]['amount'] - (($gr_multiple_option[0]['amount'] * $gr_multiple_option1[0]['discount']) / 100);
        //             echo "RM".$price."<br>";
        //         }
        //         if ($gr_multiple_option1[0]['discount_type'] == 'amount') {
        //             $price = $gr_multiple_option[0]['amount'] - $gr_multiple_option1[0]['discount'];
        //             echo "RM".$price;
        //         }
        //     } else {

             
            // echo "ID".$gr_multiple_option[0]['product_id'];

            foreach ($gr_multiple_option as $option) {
                $quantity = $option['quantitty'];
                // echo "GQ-".$quantity;
                             
             if($rad_val === $quantity)
             {
                // echo "RADVAL--".$rad_val;
                if($gr_multiple_option1[0]['discount'] > 0){
                    $discount = $gr_multiple_option1[0]['discount'];
                    $price = $option['amount'];
                    $number = ($price - ($discount * $price / 100));
                    echo "RM".$number;
                }else{

                $price = $option['amount'];
                $price = number_format((float) $price, 2, '.', '');
                // echo "hii";
                echo "RM ".$price;
                break;
                }
             }
            }

        //     }
        //     $price = number_format((float) $price, 2, '.', '');
        //     // echo "YZ".$price . '^' . $gr_multiple_option[0]['quantitty'];
        // } else {

        //    $price = $gr_multiple_option1[0]['sale_price'];
        //     $price = number_format((float) $price, 2, '.', '');
        //       echo "RM ".$price . '^' . $gr_multiple_option1[0]['current_stock'];
        //     //  echo "RM ".$price/2;

        // }
    }
    function priceget_bk($para1 = '')
    {
        $optionCount = $this->input->post('optionCount');
        $data['product_color'] = $this->input->post('color');
        $data['product_id'] = $pid = $this->input->post('product_id');
        $qty = $this->input->post('qty');
        $result = array("color" => $data['product_color']);
        for ($i = 0; $i <= $optionCount; $i++) {
            if ($this->input->post('choice_' . $i)) {
                $data1 = str_replace('+', ' ', $this->input->post('choice_' . $i));
                $data2 = explode('^', $data1);
                $data3 = array(str_replace(' ', '_', $data2[0]) => str_replace('+', '_', $data2[1]));
                $result = $result + $data3;
            }
        }
        $data['other_option'] = json_encode($result, true);
        echo '<pre>';
        print_r($data);
        $gr_multiple_option1 =  $this->db->get_where('product', array('product_id' => $data['product_id'], 'status' => "ok"))->result_array();
        $gr_multiple_option =  $this->db->get_where('multiple_option', array('product_color' => $data['product_color'], 'product_id' => $data['product_id'], 'other_option' => $data['other_option'], 'status' => 1))->result_array();
        //  echo $this->db->last_query();
        //  print_r($gr_multiple_option);
        if ($gr_multiple_option) {
            if ($gr_multiple_option1[0]['discount'] > 0) {
                if ($gr_multiple_option1[0]['discount_type'] == 'percent') {
                    $price = $gr_multiple_option[0]['amount'] - (($gr_multiple_option[0]['amount'] * $gr_multiple_option1[0]['discount']) / 100);
                }
                if ($gr_multiple_option1[0]['discount_type'] == 'amount') {
                    $price = $gr_multiple_option[0]['amount'] - $gr_multiple_option1[0]['discount'];
                }
            } else {
                $price = $gr_multiple_option[0]['amount'];
            }
            $price = number_format((float) $price, 2, '.', '');
            echo $price . '^' . $gr_multiple_option[0]['quantitty'];
        } else {
            $price = $gr_multiple_option1[0]['sale_price'];
            $price = number_format((float) $price, 2, '.', '');
            echo $price . '^' . $gr_multiple_option1[0]['current_stock'];
        }
    }
    /* FUNCTION: Loads Cart Checkout Page*/
    function cart_checkout($para1 = "")
    {
        
            $carted = $this->cart->contents();
    //  echo count($carted);
    //  exit;
        //   $param2 = $_REQUEST['param2'];
         
        // if($param2){
        
        // $signin_data = $this->db->get_where('user', array('user_id' => $param2))->result_array();
        //     foreach ($signin_data as $row) {
        //         $this->session->set_userdata('user_login', 'yes');
        //         $this->session->set_userdata('user_id', $row['user_id']);
        //         $this->session->set_userdata('user_name', $row['username']);
        //         $this->session->set_userdata('user_email', $row['email']);
        //         break;
        //     }
            
       
        // }
        
        if($this->session->userdata('user_id')==""){
            redirect(base_url() . 'index.php/home/login_set/login', 'refresh');
        }
        
    
        if (count($carted) <= 0) {
            echo "<script>window.location.href='".base_url()."index.php/home/';</script>";
            // redirect(base_url() . 'index.php/home/', 'refresh');
        }
        if ($para1 == "orders") {
           
            $this->load->view('front/shopping_cart/order_set');
        } elseif ($para1 == "delivery_address") {
            $this->load->view('front/shopping_cart/delivery_address');
        } elseif ($para1 == 'get_slot_time') {


            echo $this->crud_model->select_html_time($para2);
        } elseif ($para1 == "payments_options") {
            $this->load->view('front/shopping_cart/payments_options');
        } else {
            if(($para1 == "ipay88_alert") && isset($_SESSION['p']['ipay88_alert'])){
                $page_data['ipay88_alert'] = $_SESSION['p']['ipay88_alert'];
                unset($_SESSION['p']['ipay88_alert']);
            }
            if(isset($_SESSION['p']['reward_balance_alert'])){
                $page_data['reward_balance_alert'] = $_SESSION['p']['reward_balance_alert'];
                unset($_SESSION['p']['reward_balance_alert']);
            }
            $page_data['logger']     = $para1;
            $page_data['page_name']  = "shopping_cart";
            $page_data['asset_page']  = "shopping_cart";
            $page_data['page_title'] = translate('my_cart');
            $page_data['carted']     = $this->cart->contents();
           
            $this->load->view('front/index', $page_data);
        }
    }


    // function cart_products(){
    //     $cart_product = array();
    //     $store_name = array();

    //       $carted = $this->cart->contents();
    //       foreach ($carted as $catdata){
    //         $cart_product[] = $this->db->get_where('product',array('product_id' => $catdata['id'], 'status' => "ok"))->result_array();
           
    //       }

    //       if(!empty($cart_product)){
    //         foreach ($cart_product as $cart_products){
    //             $store_name[] = $this->db->get_where('vendor',array('vendor_id' => $cart_products['store_id'], 'status' => "approved"))->result_array();
               
    //           }
    //       }

    //     echo json_encode($store_name);
    // }
    function cart_products(){
        $store_name = array();
    
        $carted = $this->cart->contents();
        foreach ($carted as $catdata){
            $cart_products[] = $this->db->get_where('product', array('product_id' => $catdata['id'], 'status' => "ok"))->row_array();
        //           if (!empty($cart_products)) {
        //     $vendor = $this->db->get_where('vendor', array('vendor_id' => $cart_products['store_id'], 'status' => "approved"))->row_array();
        //     if (!empty($vendor)) {
        //         $cart_products_info[] = array(
        //             'product' => $cart_products,
        //             'store' => $vendor
        //         );
        //     }
        // }
    }

    echo json_encode($cart_products);
            // if (!empty($cart_products)) {
            //     $vendor = $this->db->get_where('vendor', array('vendor_id' => $cart_products['store_id'], 'status' => "approved"))->row_array();
            //     if (!empty($vendor)) {
            //         $store_name[] = $vendor;
            //     }
            // }
        }
    
      
    
    


    function cart_checkout_bid($para1 = "")
    {
        $carted = $this->cart->contents();
        if (count($carted) <= 0) {
            redirect(base_url() . 'index.php/home/', 'refresh');
        }
        //echo 'a'.$this->session->userdata('bidding_stock');
        if ($para1 == "orders") {
            $this->load->view('front/shopping_cart/order_set');
        } elseif ($para1 == "delivery_address") {
            $this->load->view('front/shopping_cart/delivery_address');
        } elseif ($para1 == "payments_options") {
            $this->load->view('front/shopping_cart/payments_options');
        } else {
            $page_data['logger']     = $para1;
            $page_data['page_name']  = "shopping_cart";
            $page_data['asset_page']  = "shopping_cart";
            $page_data['page_title'] = translate('my_cart');
            $page_data['carted']     = $this->cart->contents();
            $this->load->view('front/index', $page_data);
        }
    }

    function cart_checkout_subs($para1 = "")
    {
        $carted = $this->cart->contents();
        if (count($carted) <= 0) {
            redirect(base_url() . 'index.php/home/', 'refresh');
        }
        //echo 'a'.$this->session->userdata('bidding_stock');
        if ($para1 == "orders") {
            $this->load->view('front/shopping_cart/order_set');
        } elseif ($para1 == "delivery_address") {
            $this->load->view('front/shopping_cart/delivery_address');
        } elseif ($para1 == "payments_options") {
            $this->load->view('front/shopping_cart/payments_options');
        } else {
            $page_data['logger']     = $para1;
            $page_data['page_name']  = "shopping_cart";
            $page_data['asset_page']  = "shopping_cart";
            $page_data['page_title'] = translate('my_cart');
            $page_data['carted']     = $this->cart->contents();
            $this->load->view('front/index', $page_data);
        }
    }


    /* FUNCTION: Loads Cart Checkout Page*/
    function coupon_check_bk()
    {
        $para1 = $this->input->post('code');
        $carted = $this->cart->contents();
        if (count($carted) > 0) {
            $p = $this->session->userdata('coupon_apply') + 1;
            $this->session->set_userdata('coupon_apply', $p);
            $p = $this->session->userdata('coupon_apply');
            if ($p < 10) {
                $c = $this->db->get_where('coupon', array('code' => $para1));
                $coupon = $c->result_array();
                //echo $c->num_rows();
                //,'till <= '=>date('Y-m-d')
                if ($c->num_rows() > 0) {
                    foreach ($coupon as $row) {
                        $spec = json_decode($row['spec'], true);
                        $coupon_id = $row['coupon_id'];
                        $till = strtotime($row['till']);
                    }
                    if ($till > time()) {
                        $ro = $spec;
                        $type = $ro['discount_type'];
                        $value = $ro['discount_value'];
                        $set_type = $ro['set_type'];
                        $set = json_decode($ro['set']);
                        if ($set_type !== 'total_amount') {
                            $dis_pro = array();
                            $set_ra = array();
                            if ($set_type == 'all_products') {
                                $set_ra[] = $this->db->get('product')->result_array();
                            } else {
                                foreach ($set as $p) {
                                    if ($set_type == 'product') {
                                        $set_ra[] = $this->db->get_where('product', array('product_id' => $p))->result_array();
                                    } else {
                                        $set_ra[] = $this->db->get_where('product', array($set_type => $p))->result_array();
                                    }
                                }
                            }
                            foreach ($set_ra as $set) {
                                foreach ($set as $n) {
                                    $dis_pro[] = $n['product_id'];
                                }
                            }
                            foreach ($carted as $items) {
                                if (in_array($items['id'], $dis_pro)) {
                                    $base_price = $this->crud_model->get_product_price($items['id']);
                                    if ($type == 'percent') {
                                        $discount = $base_price * $value / 100;
                                    } else if ($type == 'amount') {
                                        $discount = $value;
                                    }
                                    $data = array(
                                        'rowid' => $items['rowid'],
                                        'price' => $base_price - $discount,
                                        'coupon' => $coupon_id
                                    );
                                } else {
                                    $data = array(
                                        'rowid' => $items['rowid'],
                                        'price' => $items['price'],
                                        'coupon' => $items['coupon']
                                    );
                                }
                                $this->cart->update($data);
                            }
                            echo 'wise:-:-:' . translate('coupon_discount_activated');
                        } else {
                            $this->cart->set_discount($value);
                            echo 'total:-:-:' . translate('coupon_discount_activated') . ':-:-:' . currency() . $value;
                        }
                        $this->cart->set_coupon($coupon_id);
                        $this->session->set_userdata('couponer', 'done');
                        $this->session->set_userdata('coupon_apply', 0);
                    } else {
                        echo 'nope';
                    }
                } else {
                    echo 'nope';
                }
            } else {
                echo 'Too many coupon request!';
            }
        }
    }

    function checkCouponIsValid()
    {
        $code = $this->input->post('code');
        $subTotal = $this->input->post('subTotal');
        $couponData = $this->db->get_where('coupon', ['code' => $code])->result_array()[0];
        $currStoreID = $this->input->post('currstoreid');
        $currentDate = date('Y-m-d');
    
        ////////////////////
        if (empty($couponData)) {
            // Coupon code doesn't exist
            echo 0;
        } elseif (strtotime($couponData['till']) < strtotime($currentDate)) {
            echo 0;
        } elseif ($subTotal < $couponData['min_order_amount']) {
            // Subtotal is less than the minimum order amount
            echo 2;
        } elseif ($currStoreID !== $couponData['vendor_id']) {
            // Diff Store ID
            echo 3;
        } else {
            // Coupon is valid
            echo 1;
        }
        ////////////////////
    }





    function coupon_check()
    {
        $para1 = $this->input->post('code');
        $carted = $this->cart->contents();
        if (count($carted) > 0) {
            $p = $this->session->userdata('coupon_apply') + 1;
            $this->session->set_userdata('coupon_apply', $p);
            $p = $this->session->userdata('coupon_apply');
            if ($p < 10) {
                $c = $this->db->get_where('coupon', array('code' => $para1));
                $coupon = $c->result_array();
                //echo $c->num_rows();
                //,'till <= '=>date('Y-m-d')
                if ($c->num_rows() > 0) {
                    foreach ($coupon as $row) {
                        $spec = json_decode($row['spec'], true);
                        $coupon_id = $row['coupon_id'];
                        $coupon_code = $row['code'];
                        $till = strtotime($row['till']);
                    }
                    if ($till > time()) {
                        $ro = $spec;
                        $type = $ro['discount_type'];
                        $value = $ro['discount_value'];
                        $set_type = $ro['set_type'];
                        $set = json_decode($ro['set']);
                        if ($set_type !== 'total_amount') {
                            $dis_pro = array();
                            $set_ra = array();
                            if ($set_type == 'all_products') {
                                $this->db->order_by('number_of_view', 'desc');
                                $set_ra[] = $this->db->get('product')->result_array();
                            } else {
                                foreach ($set as $p) {
                                    if ($set_type == 'product') {
                                        $set_ra[] = $this->db->get_where('product', array('product_id' => $p))->result_array();
                                    } else {
                                        $set_ra[] = $this->db->get_where('product', array($set_type => $p))->result_array();
                                    }
                                }
                            }
                            foreach ($set_ra as $set) {
                                foreach ($set as $n) {
                                    $dis_pro[] = $n['product_id'];
                                }
                            }
                            foreach ($carted as $items) {
                                if (in_array($items['id'], $dis_pro)) {
                                    $base_price = $this->crud_model->get_product_price($items['id']);
                                    if ($type == 'percent') {
                                        $discount = $base_price * $value / 100;
                                    } else if ($type == 'amount') {
                                        $discount = $value;
                                    }
                                    $data = array(
                                        'rowid' => $items['rowid'],
                                        'coupon' => $discount
                                    );

                                    $total_discount += $discount;
                                } else {
                                    $data = array(
                                        'rowid' => $items['rowid'],
                                        'coupon' => $items['coupon']
                                    );
                                }
                                $this->cart->update($data);
                            }
                            $this->cart->set_discount($total_discount);
                            $this->cart->set_coupon($coupon_code);
                            // echo 'wise:-:-:'.$coupon_code.translate('_coupon_discount_activated').':-:-:'.$total_discount;
                        } else {
                            $this->cart->set_discount($value);
                            //  echo 'total:-:-:'.$coupon_code.translate('_coupon_discount_activated').':-:-:'.currency().$value;
                        }
                        $this->cart->set_coupon($coupon_code);
                        $this->session->set_userdata('couponer', 'done');
                        $this->session->set_userdata('coupon_apply', 0);
                    } else {
                        echo 'nope';
                    }
                } else {
                    // echo 'nope';
                }
            } else {
                echo 'Too many coupon request!';
            }
        }
    }
    function coupon_check_backup()
    {
        $para1 = $this->input->post('code');
        $carted = $this->cart->contents();
        if (count($carted) > 0) {
            $p = $this->session->userdata('coupon_apply') + 1;
            $this->session->set_userdata('coupon_apply', $p);
            $p = $this->session->userdata('coupon_apply');
            if ($p < 10) {
                $c = $this->db->get_where('coupon', array('code' => $para1));
                $coupon = $c->result_array();
                //echo $c->num_rows();
                //,'till <= '=>date('Y-m-d')
                if ($c->num_rows() > 0) {
                    foreach ($coupon as $row) {
                        $spec = json_decode($row['spec'], true);
                        $coupon_id = $row['coupon_id'];
                        $coupon_code = $row['code'];
                        $till = strtotime($row['till']);
                    }
                    if ($till > time()) {
                        $ro = $spec;
                        $type = $ro['discount_type'];
                        $value = $ro['discount_value'];
                        $set_type = $ro['set_type'];
                        $set = json_decode($ro['set']);
                        $total_discount = 0;
                        if ($set_type !== 'total_amount') {
                            $dis_pro = array();
                            $set_ra = array();
                            if ($set_type == 'all_products') {
                                $this->db->order_by('number_of_view', 'desc');
                                $set_ra[] = $this->db->get('product')->result_array();
                            } else {
                                foreach ($set as $p) {
                                    if ($set_type == 'product') {
                                        $set_ra[] = $this->db->get_where('product', array('product_id' => $p))->result_array();
                                    } else {
                                        $set_ra[] = $this->db->get_where('product', array($set_type => $p))->result_array();
                                    }
                                }
                            }
                            foreach ($set_ra as $set) {
                                foreach ($set as $n) {
                                    $dis_pro[] = $n['product_id'];
                                }
                            }
                            foreach ($carted as $items) {
                                // print_r($items);
                                // exit;
                                if (in_array($items['id'], $dis_pro)) {
                                    // need to check for multiple price here first
                                    $productDetail = $this->db->get_where('product', ['product_id' => $items['id']])->result_array()[0];
                                    if ($productDetail['multiple_price'] == 1) {
                                        # has multiple variant
                                        // file_put_contents(__DIR__.'/single-item.js', json_encode($items));
                                        $singleItemOption = json_decode($items['option']);
                                        $formatedSingleItemOption = [];
                                        if ($singleItemOption->color->title) {
                                            $formatedSingleItemOption[strtolower(preg_replace('/\s/i', '_', $singleItemOption->color->title))] = $singleItemOption->color->value;
                                        }
                                        if ($singleItemOption->choice_1->title) {
                                            $formatedSingleItemOption[preg_replace('/\s/i', '_', $singleItemOption->choice_1->title)] = preg_replace('/(Weight\^|Size\^)/i', '', $singleItemOption->choice_1->value);
                                        }
                                        if ($singleItemOption->choice_2->title) {
                                            $formatedSingleItemOption[preg_replace('/\s/i', '_', $singleItemOption->choice_2->title)] = preg_replace('/(Cut size\^|Cut_size\^)/i', '', $singleItemOption->choice_2->value);
                                        }
                                        // print_r(json_encode($formatedSingleItemOption));
                                        // now i need to match this single item option with the multi table option
                                        $multiTableOption = $this->db->get_where('multiple_option', [
                                            'product_id' => $productDetail['product_id'],
                                            'other_option' => json_encode($formatedSingleItemOption),
                                            'status' => 1
                                        ])->result_array()[0];
                                        // print_r($multiTableOption);
                                        // exit;
                                        // now need to chage data based on discount type
                                        if ($type == 'amount') {
                                            $discount = $multiTableOption['amount'];
                                        }
                                        if ($type == 'percent') {
                                            $discount = $multiTableOption['amount'] * $value / 100;
                                        }
                                        $data = array(
                                            'rowid' => $items['rowid'],
                                            'coupon' => $discount
                                        );
                                        $total_discount += $discount;
                                    } else {
                                        // don't have multi variant
                                        $base_price = $this->crud_model->get_product_price($items['id']);
                                        if ($type == 'percent') {
                                            $discount = $base_price * $value / 100;
                                        } else if ($type == 'amount') {
                                            $discount = $value;
                                        }
                                        $data = array(
                                            'rowid' => $items['rowid'],
                                            'coupon' => $discount
                                        );

                                        $total_discount += $discount;
                                    }
                                } else {
                                    $data = array(
                                        'rowid' => $items['rowid'],
                                        'coupon' => $items['coupon']
                                    );
                                }
                                $this->cart->update($data);
                            }
                            $this->cart->set_discount($total_discount);
                            $this->cart->set_coupon($coupon_code);
                            // echo 'wise:-:-:'.$coupon_code.translate('_coupon_discount_activated').':-:-:'.$total_discount;
                        } else {
                            $this->cart->set_discount($value);
                            //  echo 'total:-:-:'.$coupon_code.translate('_coupon_discount_activated').':-:-:'.currency().$value;
                        }
                        $this->cart->set_coupon($coupon_code);
                        $this->session->set_userdata('couponer', 'done');
                        $this->session->set_userdata('coupon_apply', 0);
                    } else {
                        // echo 'nope';
                    }
                } else {
                    // echo 'nope';
                }
            } else {
                echo 'Too many coupon request!';
            }
        }
    }

    function coupon_check_prs()
    {
        $para1 = $this->input->post('code');
        $carted = $this->cart->contents();
        if (count($carted) > 0) {
            $p = $this->session->userdata('coupon_apply') + 1;
            $this->session->set_userdata('coupon_apply', $p);
            $p = $this->session->userdata('coupon_apply');
            if ($p < 10) {
                $c = $this->db->get_where('coupon', array('code' => $para1));
                $coupon = $c->result_array();
                //echo $c->num_rows();
                //,'till <= '=>date('Y-m-d')
                if ($c->num_rows() > 0) {
                    foreach ($coupon as $row) {
                        $spec = json_decode($row['spec'], true);
                        $coupon_id = $row['coupon_id'];
                        $till = strtotime($row['till']);
                    }
                    if ($till > time()) {
                        $ro = $spec;
                        $type = $ro['discount_type'];
                        $value = $ro['discount_value'];
                        $set_type = $ro['set_type'];
                        $set = json_decode($ro['set']);
                        if ($set_type !== 'total_amount') {
                            $dis_pro = array();
                            $set_ra = array();
                            if ($set_type == 'all_products') {
                                $set_ra[] = $this->db->get('product')->result_array();
                            } else {
                                foreach ($set as $p) {
                                    if ($set_type == 'product') {
                                        $set_ra[] = $this->db->get_where('product', array('product_id' => $p))->result_array();
                                    } else {
                                        $set_ra[] = $this->db->get_where('product', array($set_type => $p))->result_array();
                                    }
                                }
                            }
                            foreach ($set_ra as $set) {
                                foreach ($set as $n) {
                                    $dis_pro[] = $n['product_id'];
                                }
                            }
                            foreach ($carted as $items) {
                                if (in_array($items['id'], $dis_pro)) {
                                    echo  $base_price = $this->crud_model->get_product_price($items['id']);
                                    if ($type == 'percent') {
                                        $discount = $base_price * $value / 100;
                                    } else if ($type == 'amount') {
                                        $discount = $value;
                                    }
                                    $data = array(
                                        'rowid' => $items['rowid'],
                                        'price' => $base_price - $discount,
                                        'coupon' => $coupon_id
                                    );
                                } else {
                                    $data = array(
                                        'rowid' => $items['rowid'],
                                        'price' => $items['price'],
                                        'coupon' => $items['coupon']
                                    );
                                }
                                $this->cart->update($data);
                            }
                            echo 'wise:-:-:' . translate('coupon_discount_activated');
                        } else {
                            $this->cart->set_discount($value);
                            echo 'total:-:-:' . translate('coupon_discount_activated') . ':-:-:' . currency() . $value;
                        }
                        $this->cart->set_coupon($coupon_id);
                        $this->session->set_userdata('couponer', 'done');
                        $this->session->set_userdata('coupon_apply', 0);
                    } else {
                        echo 'nope';
                    }
                } else {
                    echo 'nope';
                }
            } else {
                echo 'Too many coupon request!';
            }
        }
    }
    function response()
    {
        echo '<pre>';
        print_r($this->input->post());
        exit;
    }
    function ipayresponse()
    {
        //  echo '<pre>';
        // print_r($this->input->post());
        //print_r($_POST);
        //echo $this->input->post('Status');
        //exit;

        //echo 1; exit;
        if ($this->input->post('Status') == '1') {
            $payment_id = $this->input->post('PaymentId');
            $TransId = $this->input->post('TransId');
            $merchant_order_id = $this->input->post('RefNo');

            $data['payment_details'] = $response_array = json_encode($this->input->post(), 1);
            // echo "<pre>";print_r($response_array);exit;
            //Check success response


            $carted  = $this->cart->contents();
            $tot_rewards = $this->db->get_where('sale', array('order_id' => $merchant_order_id))->row()->order_amount;
            $total_invoice_id = $this->db->get_where('sale', array('order_id' => $merchant_order_id))->row()->total_invoice_id;
            $saleDet = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
            
            // print_r($saleDet);
            //  $saleDet=$saleDet[0];
            //$sale_id = $saleDet['sale_id'];
            //$user_id=$saleDet['buyer'];
            foreach ($carted as $value) {
                $this->crud_model->decrease_quantity($value['id'], $value['qty']);
                $data1['type']         = 'destroy';
                $data1['category']     = $this->db->get_where('product', array(
                    'product_id' => $value['id']
                ))->row()->category;
                $data1['sub_category'] = $this->db->get_where('product', array(
                    'product_id' => $value['id']
                ))->row()->sub_category;
                $data1['product']      = $value['id'];
                $data1['quantity']     = $value['qty'];
                $data1['total']        = 0;
                $data1['reason_note']  = 'sale';
                $data1['order_id']      = $merchant_order_id;
                $data1['datetime']     = time();
                $this->db->insert('stock', $data1);
                /*$pro_price     = $this->db->get_where('product', array(
                        'product_id' => $value['id']
                    ))->row()->sale_price;
                    
                    if ($this->session->userdata('user_login') == 'yes') {
                            $data['rewards']=($pro_price*2)/100;
                            }*/
            }
            foreach ($saleDet as $saldt) {
                //print_r($saldt);


                $payment_status[] = array('admin' => '', 'status' => 'paid');

                $data['status'] = 'admin_pending';
                $data['payment_status'] = json_encode($payment_status);
                $this->db->where('sale_id', $saldt['sale_id']);
                $this->db->update('sale', $data);
                //echo $this->db->last_query(); exit;

                //$tot_rewards += $saldt['rewards'];
                //echo $this->db->last_query();
            }

            if ($this->session->userdata('user_login') == 'yes') {
                $rewardsts = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
                $rewardsts = $rewardsts['0'];

                $this->wallet_model->add_reward_balance($tot_rewards, $this->session->userdata('user_id'));
                
                    $data_r['order_id'] = $merchant_order_id;
                    $data_r['buyer'] = $this->session->userdata('user_id');
                    $data_r['reward_amt'] = $tot_rewards;
                    $this->db->insert('rewards_log', $data_r);

                if ($rewardsts['rewards_using'] == '1') {
                    $this->wallet_model->reduce_reward_balance($rewardsts['reward_using_amt'], $this->session->userdata('user_id'));
                    foreach ($saleDet as $saldt) {
                        $data_re['rewards_using'] = '2';
                        $this->db->where('sale_id', $saldt['sale_id']);
                        $this->db->update('sale', $data_re);
                    }
                }
            }
            // $this->crud_model->digital_to_customer($sale_id);
            $this->cart->destroy();
            //$this->crud_model->digital_to_customer($order_id);
            //echo $total_invoice_id; exit;
            $info = json_decode($saldt['shipping_address'], true);


            $sid     =  "ACee2c631b4ec665941d34c9af2f50de93";
            $token     =  "762773fd21e6f653e87e9b8aa92b4dc3";
            $messagingServiceSid     =  "MG56f616e02ca64f6b8698004c9aafef2c";
            //  $sms="Your OTP: ".$otp."";
            //    $mobile = "+91".$info['phone'];
            //    $sms  = "'Your Order #" . $merchant_order_id . " placed successfully, Thanks";

            //   require_once(APPPATH . 'libraries/Twilio/Twilio.php');
            //   $ordersms = sendotp($sid, $token, $messagingServiceSid, $mobile, $sms);

            $this->crud_model->email_invoice_elastic($total_invoice_id, $merchant_order_id);
            // $this->crud_model->email_invoice1($order_id);
            $this->cart->destroy();
            $this->session->set_userdata('couponer', '');
            //redirect(base_url() . 'index.php/home/invoice/' . $merchant_order_id, 'refresh');
            // redirect(base_url() . 'index.php/home/invoice/total/' . $merchant_order_id, 'refresh');
            echo "<script>window.location.href = '" . base_url() . "index.php/home/invoice/total/" . $merchant_order_id . "';</script>";
            
        } else {

            $merchant_order_id = $this->input->post('RefNo');

            $data['payment_details'] = $response_array = json_encode($this->input->post(), 1);
            // echo "<pre>";print_r($response_array);exit;
            //Check success response


            $carted  = $this->cart->contents();
            // print_r( $carted); exit;
            $total_invoice_id = $this->db->get_where('sale', array('order_id' => $merchant_order_id))->row()->total_invoice_id;
            $saleDet = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
            // print_r($saleDet);
            //  $saleDet=$saleDet[0];
            //$sale_id = $saleDet['sale_id'];
            //$user_id=$saleDet['buyer'];

            foreach ($saleDet as $saldt) {
                //print_r($saldt);


                $payment_status[] = array('admin' => '', 'status' => 'failed');

                $data['status'] = 'failed';
                $data['payment_status'] = json_encode($payment_status);
                $this->db->where('sale_id', $saldt['sale_id']);
                $this->db->update('sale', $data);
                //echo $this->db->last_query(); exit;

                // $tot_rewards += $saldt['rewards'];
                //echo $this->db->last_query();
            }

            //   if ($this->session->userdata('user_login') == 'yes') {
            //      $rewardsts = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
            //      $rewardsts = $rewardsts['0'];

            //     $this->wallet_model->add_reward_balance($tot_rewards, $this->session->userdata('user_id'));
            // }
            // $this->crud_model->digital_to_customer($sale_id);
            // $this->cart->destroy();
            //$this->crud_model->digital_to_customer($order_id);
            //echo $total_invoice_iecho d; exit;

            $this->session->set_flashdata('alert', 'payment_failed');
            // redirect(base_url() . 'index.php/home/cart_checkout/', 'refresh');
            echo "<script>window.location.href = '" . base_url() . "index.php/home/cart_checkout/';</script>";
        }
    }



   //lalamove_sandbox
    /*public $lalamove_url = 'https://rest.sandbox.lalamove.com';
    public $lalamove_path = '/v3/quotations';
    public $lalamove_apiKey = 'pk_test_13334160b783cdb84d52d58123083e7a';
    public $lalamove_secret = 'sk_test_91aDqy1/Uo8cB/873iKbEQ3IQjUpFKzNfhbOG8VN6Fp3LDApJEsgVR279kfCqcJY';*/
    public $lalamove_url = 'https://rest.lalamove.com';
    public $lalamove_path = '/v3/quotations';
    public $lalamove_apiKey = 'pk_prod_7ab9bb93e0b18c42ceafac3e04fcdb59';
    public $lalamove_secret = 'sk_prod_QjJ0EtPk0xcC8kAhKpbY28we4GPL0p7sBAZtdCAnoL5uBkzCnLgDWKhLPkkMsYwg';
    public function getQuotation1($store,$stops)
    {
        /*$stops='{"coordinates": {"lat": "3.170610","lng": "101.696490"},"address": "No. 30 Ground Floor Jln Lumut,Kuala Lumpur,Wilayah Persekutuan,Malaysia - 50400"}';
        $store='{"coordinates": {"lat": "3.209340","lng": "101.631330"},"address": "541 Jln E3/7 Taman Ehsan Kepong,Kuala Lumpur,Wilayah Persekutuan,Malaysia - 52100"}';*/
        if(($store!=null) && ($stops!=null)){
            $time = (time() * 1000);
            $method = 'POST';
            $region = 'MY';
            $body = '{"data" : {
                "serviceType": "MOTORCYCLE",
                "specialRequests": [],
                "language": "en_MY",
                "stops": ['.$store.','.$stops.']
            }}';
            $rawSignature = "{$time}\r\n{$method}\r\n{$this->lalamove_path}\r\n\r\n{$body}";
            $signature = hash_hmac("sha256", $rawSignature, $this->lalamove_secret);
            $startTime = microtime(true);
            $token = $this->lalamove_apiKey.':'.$time.':'.$signature;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->lalamove_url.$this->lalamove_path,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 3,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HEADER => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    "Content-type: application/json; charset=utf-8",
                    "Authorization: hmac ".$token,
                    "Accept: application/json",
                    "Market: ".$region
                ),
            ));
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            $response0 = json_decode($response,true);
            $response1="";
            if(!isset($response0['errors'])){$response1=$response;}
            return $response1;
        }
        else{return "";}
    }
    function getQuotation_charge()
    {
        $loc=$this->input->post('loc');
        $lat=$this->input->post('lat');
        $lng=$this->input->post('lng');
        $delivery_estimation=0;
        $result=[];
        if(($loc!="") && ($lat!="") && ($lng!=""))
        {
            $stops='{"coordinates": {"lat": "'.$lat.'","lng": "'.$lng.'"},"address": "'.$loc.'"}';
            $carted   = $this->cart->contents();
            $store_id=[];
            foreach ($carted as $ct) {
                $store_id0 = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->store_id;
                if(!in_array($store_id0,$store_id)){$store_id[]=$store_id0;}
            }
            foreach ($store_id as $store_id1) {
                $store=null;
                $lalamove_store0=($this->db->get_where('vendor', array('vendor_id' => $store_id1))->result_array())[0];
                if(($lalamove_store0['latitude']!="") && ($lalamove_store0['longitude']!="") && ($lalamove_store0['address1']!=""))
                {$store='{"coordinates": {"lat": "'.$lalamove_store0['latitude'].'","lng": "'.$lalamove_store0['longitude'].'"},"address": "'.($lalamove_store0['address1'] . ',' . $lalamove_store0['city'] . ',' . $lalamove_store0['state'] . ',' . $lalamove_store0['country'] . ',' . $lalamove_store0['zip']).'"}';}
                if(($store!=null) && ($stops!=null)){
                    $time = (time() * 1000);
                    $method = 'POST';
                    $region = 'MY';
                    $body = '{"data" : {
                        "serviceType": "MOTORCYCLE",
                        "specialRequests": [],
                        "language": "en_MY",
                        "stops": ['.$store.','.$stops.']
                    }}';
                    $rawSignature = "{$time}\r\n{$method}\r\n{$this->lalamove_path}\r\n\r\n{$body}";
                    $signature = hash_hmac("sha256", $rawSignature, $this->lalamove_secret);
                    $startTime = microtime(true);
                    $token = $this->lalamove_apiKey.':'.$time.':'.$signature;
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $this->lalamove_url.$this->lalamove_path,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HEADER => false,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => $method,
                        CURLOPT_POSTFIELDS => $body,
                        CURLOPT_HTTPHEADER => array(
                            "Content-type: application/json; charset=utf-8",
                            "Authorization: hmac ".$token,
                            "Accept: application/json",
                            "Market: ".$region
                        ),
                    ));
                    $response = curl_exec($curl);
                    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    curl_close($curl);
                    $response0 = json_decode($response,true);
                    if(!isset($response0['errors'])){
                        if($response0['data']['priceBreakdown']['total']!="")
                       {
                            $delivery_estimation+=floatval($response0['data']['priceBreakdown']['total']);
                            $result[$store_id1]=$response;
                        }
                    }
                }
            }
        }
        $result=json_encode($result);
        echo json_encode(["result"=>$result,"value"=>$delivery_estimation,"display"=>currency().number_format($delivery_estimation,2)]);
    }
    /* FUNCTION: Finalising Purchase*/
    function cart_finish($para1 = "", $para2 = "")
    {
        
        if($this->session->userdata('user_id')==""){
            redirect(base_url() . 'index.php/home/login_set/login', 'refresh');
        }
        $payment_option_dis=$this->input->post('payment_option_dis');
        $user_id=$this->session->userdata('user_id');
        $rewards_post=$this->input->post('rewards');
        $payment_type_post=$this->input->post('payment_type');
        $carted = $this->cart->contents();
        if (count($carted) <= 0) {
            redirect(base_url() . 'index.php/home/', 'refresh');
        }
        $data['total_invoice_id'] = $total_invoice_id = $this->db->order_by('sale_id', 'desc')->limit('1')->get('sale')->row()->sale_id;

        $carted   = $this->cart->contents();
        $total    = $this->cart->total();
        $exchange = exchange('usd');
        $vat_per  = '';
        $vat      = 0;
        foreach ($carted as $ct) {
            $tax_1=$this->crud_model->get_product_tax($ct['id']);
            if($tax_1!=""){$vat+=floatval($tax_1);}
        }
        
        if ($this->session->userdata('user_zips') != "") {
            $shipping = $this->db->get_where('business_settings', array('type' => 'delivery_fee'))->row()->value;
        }
        $grand_total     = $total + $vat + $shipping;
        $product_details = json_encode($carted);

        /* $this->db->where('user_id', $user_id);
        $this->db->update('user', array(
            'langlat' => $this->input->post('langlat')
        )); */
        if(($payment_type_post != "") && ($payment_option_dis == "1")){
            $reward_amount=0.0;$rewards_using="0";$reward_using_amt=0.0;
            if ($rewards_post != "") {
                $user_reward = $this->db->get_where('user', array('user_id' => $user_id))->result_array();
                $reward_amount = ($user_reward[0]['rewards']!="")?floatval($user_reward[0]['rewards']):0.0;
                if ($reward_amount > 0.0) {
                    if ($para1 == 'go') {
                        $rewards_using="2";
                        if($reward_amount >= $grand_total){
                            $reward_amount=($reward_amount - $grand_total);
                            $reward_using_amt=$grand_total;
                            $grand_total=0.0;
                        } else {
                            $grand_total=($grand_total - $reward_amount);
                            $reward_using_amt=$reward_amount;
                            $reward_amount=0.0;
                        }
                    }
                }
            }
            if ($payment_type_post == 'cash_on_delivery') {
                if ($para1 == 'go') {
                    $order_id = 'OD' . substr(time(), 4) . rand(1, 10) . rand(1, 99);
                    $data['sale_datetime'] = time();
                    foreach ($carted as $ct) {
                        $no_qty = $ct['qty'];
                        $i = 1;
    
                        $pro['id'] = $ct['id'];
                        $pro['qty'] = $ct['qty'];
                        $pro['option'] = $ct['option'];
                        $pro['price'] = $ct['price'];
                        $rwd = $this->db->get_where('general_settings', array('type' => 'rewards'))->row()->value;
                        if ($rwd == 'ok') {
                            if ($this->session->userdata('user_login') == 'yes') {
                                $reward_p = $this->db->get_where('rewards', array('id' => '1'))->result_array();
                                $reward_p = $reward_p[0];
                                if ($reward_p['type'] == '%') {
                                    $data['rewards'] = ($ct['price'] * $reward_p['amount']) / 100;
                                } else if ($reward_p['type'] == 'flat') {
                                    $data['rewards'] = $reward_p['amount'];
                                }
                            }
                        }
                        $pro['name'] = $ct['name'];
                        // $data['shipping']=$shipping;
                        $data['shipping'] = $pro['shipping'] = $shipping;
                        $data['vat'] = $pro['tax'] = $this->crud_model->get_product_tax($ct['id']);
                        $pro['image'] = $ct['image'];
                        $pro['coupon'] = $ct['coupon'];
                        $rowid = $pro['rowid'] = $ct['rowid'];
                        $pro['subtotal'] = $ct['subtotal'];
                        $pro1 = array($rowid => $pro);
                        $data['product_details'] = json_encode($pro1);
                        //$data['product_details']= json_encode($pro);
                        //$data['shipping_address']= json_encode($_POST);
                        $data['store_id'] = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->store_id;
                        $cashpack = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->cashpack;
                        $cashpack_type = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->cashpack_type;
                        if ($cashpack > 0) {
                            if ($cashpack_type == 'amount') {
                                $data['cash_pack'] = $cashpack * $ct['qty'];
                            } else {
                                $data['cash_pack'] = (($ct['price'] / 100) * $cashpack) * $ct['qty'];
                            }
                        }
                        $address_unicid = $this->input->post('addreessList');
                        if ($address_unicid != "") {
                            $address_unicid = $this->input->post('addreessList');
                            $shipping_address = $this->db->get_where('shipping_address', array('id' => $address_unicid))->result_array();
                            $shipping_address = $shipping_address[0];
                            $sh['firstname'] = $shipping_address['name'];
                            $sh['address1'] = $shipping_address['address'];
                            $sh['address2'] = $shipping_address['address1'];
                            $sh['zip'] = $shipping_address['zip_code'];
                            $sh['phone'] = $shipping_address['mobile'];
                            $sh['email'] = $shipping_address['email'];
                            $ctysplit = explode('-', $shipping_address['country']);
                            $country = $sh['country'] = $ctysplit[0];
                            $state = $sh['state'] =  $shipping_address['district'];
                            $city = $sh['cities'] = $shipping_address['city'];
                            $sn_country = $sh['short_country'] = $ctysplit[1];
                            $data['shipping_address']  = json_encode($sh);
                        } else {
                            $user_details = ($this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->result_array())[0];
                            $sh['firstname'] = $user_details['username'];
                            $sh['lastname'] = $user_details['surname'];
                            $sh['address1'] = $user_details['address1'];
                            $sh['address2'] = $user_details['address2'];
                            $sh['zip'] = $user_details['zip'];
                            $sh['phone'] = $user_details['phone'];
                            $sh['email'] = $user_details['email'];
                            $sh['country'] = $user_details['country'];
                            $sh['state'] =  $user_details['state'];
                            $sh['cities'] = $user_details['city'];
                            $sh['short_country'] = $user_details['country'];
    
                            /* $sh['firstname'] = $_POST['firstname'];
                            $sh['lastname'] = $_POST['lastname'];
                            $sh['address1'] = $_POST['address1'];
                            $sh['address2'] = $_POST['address2'];
                            $sh['zip'] = $_POST['zip'];
                            $sh['phone'] = $_POST['phone'];
                            $sh['email'] = $_POST['email'];
                            $sh['country'] = $_POST['country'];
                            $sh['state'] =  $_POST['state'];
                            $sh['cities'] = $_POST['cities'];
                            $sh['short_country'] = $_POST['cou_shrt1']; */
    
                            $data['shipping_address'] = json_encode($sh);
                        }
                        $data['vat_percent'] = $vat_per;
                        $data['delivery_status']   = '[]';
                        $data['product_notes'] = $this->input->post('product_notes');
                        $grand = $grand_total;
                        $post_data['total_amount'] = $data['order_amount'] = $grand;
                        $data['payment_type'] = ($rewards_using=="2"?"Rewards(".$reward_using_amt.") + ":"").'cash_on_delivery';
                        if ($this->session->userdata('pickup') != "") {
                            $data['order_type'] = 'pickup';
                        }
                        if ($this->session->userdata('user_zips') != "") {
                            $data['order_type'] = 'delivery';
                        }
                        $data['payment_status']    = '[]';
                        $data['payment_details']   = '';
                        $tax_1=$this->crud_model->get_product_tax($ct['id']);
                        $product_total = ($tax_1 + $ct['price'] + $ct['shipping']) * $ct['qty'];
                        $data['grand_total'] = $product_total;
    
                        $data['delivary_datetime'] = '';
                        $data['group_deal'] = 1;
                        $data['order_id'] = $order_id;
                        $data['status'] = 'success';
                        $data['discount']=str_replace('RM','',$this->input->post('total_dis'));
                        //echo '<pre>'; print_r($data); exit;
                        $this->db->insert('sale', $data);
                        //echo $this->db->last_query();
                        $sale_id = $this->db->insert_id();
                        if ($this->session->userdata('user_login') == 'yes') {
                            $data['buyer'] = $this->session->userdata('user_id');
                        } else {
                            $data['buyer'] = "guest";
                            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            $charactersLength = strlen($characters);
                            $randomString = '';
                            for ($j = 0; $j < 10; $j++) {
                                $randomString .= $characters[rand(0, $charactersLength - 1)];
                            }
                            $data['guest_id'] = 'guest' . $sale_id . '-' . $randomString;
                        }
                        $vendors = $this->crud_model->vendors_in_sale($sale_id);
                        $delivery_status = array();
                        $payment_status = array();
                        foreach ($vendors as $p) {
                            $delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');
                            $payment_status[] = array('vendor' => $p, 'status' => 'due');
                            $data['sale_code'] = 'VE-' . $p . '-' . date('Ym', $data['sale_datetime']) . $sale_id;
                            //$data['seller']='VE-'.$p;
                        }
                        if ($this->crud_model->is_admin_in_sale($sale_id)) {
                            $delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');
                            $payment_status[] = array('admin' => '', 'status' => 'due');
                            $data['sale_code'] = 'AD-' . date('Ym', $data['sale_datetime']) . $sale_id;
                        }
    
                        $data['delivery_status'] = json_encode($delivery_status);
                        $data['payment_status'] = json_encode($payment_status);
                        if ($rewards_post != ""){
                            $data['rewards_using'] = $rewards_using;
                            $data['reward_using_amt'] = $reward_using_amt;
                            $users['rewards']=$reward_amount;
                            $this->db->where('user_id', $user_id);
                            $this->db->update('user', $users);
                        }
                        $data['store_id'] = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->store_id;
                        $data['lalamove_res']=$this->input->post('lalamove_res');
                        
                        $this->db->where('sale_id', $sale_id);
                        $this->db->update('sale', $data);
                        //echo $this->db->last_query();
                        $this->crud_model->digital_to_customer($sale_id);
    
    
                        //}
                    }
                    if ($this->session->userdata('user_id')) {
                        $lalamove_res0=$this->input->post('lalamove_res');
                   $delivery_charge=0;
                   if($lalamove_res0!="")
                  {
                    $lalamove_res = json_decode($lalamove_res0,true);
                   foreach($lalamove_res as $key=>$value)
                  {
                   if($value!="")
                  {
                   $lalamove_res1 = json_decode($value,true);
                   if($lalamove_res1['data']['priceBreakdown']['total']!="")
                   { $delivery_charge+=floatval($lalamove_res1['data']['priceBreakdown']['total']);}
                  }
                }
               }
               $tax_1=$this->crud_model->get_product_tax($ct['id']);
               $grand_totals = ($tax_1 +$ct['price'] + $ct['shipping']) * $ct['qty'];
               $discount = str_replace('RM','',$this->input->post('total_dis'));
                    $total=$delivery_charge+$grand_totals-$discount;
                        $datas['user_id'] = $this->session->userdata('user_id');
                        $datas['description'] = 'shopping';
                        $datas['mode'] = 'debit';
                        $datas['status'] = 'SUCCESS';
                        // $datas['servicetype'] = 8;
                        $datas['amount'] = $total;
                        $datas['date'] = time();
                        $datas['ref_id'] = $order_id;
                        $this->db->insert('user_trans_log', $datas);
                    }
                    foreach ($carted as $value) {
                        $this->crud_model->decrease_quantity($value['id'], $value['qty']);
                        $data1['type']         = 'destroy';
                        $data1['category']     = $this->db->get_where('product', array(
                            'product_id' => $value['id']
                        ))->row()->category;
                        $data1['sub_category'] = $this->db->get_where('product', array(
                            'product_id' => $value['id']
                        ))->row()->sub_category;
                        $data1['product']      = $value['id'];
                        $data1['quantity']     = $value['qty'];
                        $data1['total']        = 0;
                        $data1['reason_note']  = 'sale';
                        $data1['sale_id']      = $sale_id;
                        $data1['datetime']     = time();
                        $this->db->insert('stock', $data1);
                        $product_datasell  = $this->db->get_where('product', array('product_id' => $value['id']));
    
                        $this->db->where('product_id', $pro['id']);
    
                        $this->db->update('product', array(
                            'selling_view' => $product_datasell->row()->selling_view + 1
                        ));
                    }
                    $this->crud_model->digital_to_customer($order_id);
                    /* $sid    = "AC98744eee6355b83a7c15b1798bf4db6b";
                    $token  = "937e6d4ea90d7d82ac713c63872b7d20";
                    $messagingServiceSid     =  "+14155238886";
                    //$mobile = $sh['phone'];
                    $mobile = "+918526510484";
                    $sms  = "'Your Order #" . $order_id . " placed successfully, Thanks";
                    require_once(APPPATH . 'libraries/Twilio/Twilio.php');
                    $ordersms = sendotp($sid, $token, $messagingServiceSid, $mobile, $sms);
                    //sendotp_whatsapp($sid, $token, $messagingServiceSid, $mobile, $sms); */
                    $this->crud_model->email_invoice($order_id);
                    
                    $this->cart->destroy();
                    $this->session->set_userdata('couponer', '');
                    $this->getQuotation();
    
                    // redirect(base_url() . 'index.php/home/invoice/total/' . $order_id, 'refresh');
                    echo "<script>window.location.href = '".base_url() . 'index.php/home/invoice/total/' . $order_id."'; </script>";
                }
            } else if ($payment_type_post == 'ipay88') {
    
                if ($para1 == 'go') {
    
                    $order_id = 'OD' . substr(time(), 4) . rand(1, 10) . rand(1, 99);
                    $sh_1=[];
                    $address_unicid = $this->input->post('addreessList');
                    if ($address_unicid != "") {
                        $shipping_address = $this->db->get_where('shipping_address', array('id' => $address_unicid))->result_array();
                        if(count($shipping_address)>0){
                            
                            $sh_1['firstname'] = $shipping_address[0]['name'];
                            $sh_1['email'] = $shipping_address[0]['email'];
                            $sh_1['phone'] = $shipping_address[0]['mobile'];
                            
                        }
                    }
                    if(count($sh_1)==0)
                    {
                        $user_details = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->result_array();
                        if(count($shipping_address)>0){
                            $sh_1['firstname'] = $user_details[0]['username'];
                            $sh_1['email'] = $user_details[0]['email'];
                            $sh_1['phone'] = $user_details[0]['phone'];
                        }
                    }
                    if(count($sh_1)>0)
                {
                    if ($rewards_using=="2") {
                        $sh_1['grand_total'] = $data['order_amount'] - $reward_using_amt;
                    } else {
                        $sh_1['grand_total'] = $data['order_amount'];
                    }
                    $page_data['aut_id'] = $order_id;
                    $page_data['amount'] = $sh_1['grand_total'];
                    $page_data['client_name'] = $sh_1['firstname'];
                    $page_data['client_email'] = $sh_1['email'];
                    $page_data['client_phone'] = $sh_1['phone'];
                
                    $request_data=[];
                    $request_data['order_id']=$order_id;
                    $request_data['payment_option_dis']=$this->input->post('payment_option_dis');
                    $request_data['rewards']=$this->input->post('rewards');
                    $request_data['payment_type']=$this->input->post('payment_type');
                    $request_data['addreessList']=$this->input->post('addreessList');
                    $request_data['product_notes']=$this->input->post('product_notes');
                    $request_data['total_dis']=str_replace('RM','',$this->input->post('total_dis'));
                    $request_data['user_id']=$this->session->userdata('user_id');
                    $request_data['user_zips']=$this->session->userdata('user_zips');
                    $request_data['delivery_final_value']=$this->session->userdata('delivery_final_value');
                    $request_data['pickup']=$this->session->userdata('pickup');
                    $request_data['pickup_date']=$this->session->userdata('pickup_date');
                    $request_data['pickup_slot']=$this->session->userdata('pickup_slot');
                    $request_data['pre_order_status']=$this->session->userdata('pre_order_status');
                    $request_data['pre_order_date']=$this->session->userdata('pre_order_date');
                    $request_data['user_login']=$this->session->userdata('user_login');
                    $request_data1=[];
                    $request_data1['user_id']=$request_data['user_id'];
                    $request_data1['order_id']=$order_id;
                    $request_data1['request_data']=json_encode($request_data);
                    $request_data1['carted']=json_encode($this->cart->contents());
                    $request_data1['lalamove_res']=$this->input->post('lalamove_res');
                    $this->db->insert('ipay88_requestdata', $request_data1);
                    $ipay88_requestdata_id = $this->db->insert_id();
                    $url1=base_url() . 'index.php/home/ipay88_save_sales/'.$ipay88_requestdata_id.'/'.$order_id.'/';
                    $page_data['return_page'] = $url1;
                    $page_data['backend_page'] = $url1;
                    $this->load->view('front/shopping_cart/ipay88', $page_data);
                }
                }
            } else if ($payment_type_post == 'wallet') {
                $balance = $this->wallet_model->user_balance();
                if ($balance >= $grand_total) {
    
                    if ($para1 == 'go') {
                        $order_id = 'OD' . substr(time(), 4) . rand(1, 10) . rand(1, 99);
    
                        foreach ($carted as $ct) {
    
                            $no_qty = $ct['qty'];
                            $i = 1;
                            $pro['id'] = $ct['id'];
                            $pro['qty'] = $ct['qty'];
                            $pro['option'] = $ct['option'];
                            $pro['price'] = $ct['price'];
                            $rwd = $this->db->get_where('general_settings', array('type' => 'rewards'))->row()->value;
                            if ($rwd == 'ok') {
                                if ($this->session->userdata('user_login') == 'yes') {
                                    $reward_p = $this->db->get_where('rewards', array('id' => '1'))->result_array();
                                    $reward_p = $reward_p[0];
                                    if ($reward_p['type'] == '%') {
                                        $data['rewards'] = ($ct['price'] * $reward_p['amount']) / 100;
                                    } else if ($reward_p['type'] == 'flat') {
                                        $data['rewards'] = $reward_p['amount'];
                                    }
                                }
                            }
                            $pro['name'] = $ct['name'];
                            $data['shipping'] = $pro['shipping'] = $ct['shipping'];
                            $data['vat'] = $pro['tax'] = $this->crud_model->get_product_tax($ct['id']);
                            $pro['image'] = $ct['image'];
                            $pro['coupon'] = $ct['coupon'];
                            $rowid = $pro['rowid'] = $ct['rowid'];
                            $pro['subtotal'] = $ct['subtotal'];
                            $pro1 = array($rowid => $pro);
                            $data['product_details'] = json_encode($pro1);
                            //$data['product_details']= json_encode($pro);
                            $cashpack = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->cashpack;
                            $cashpack_type = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->cashpack_type;
                            if ($cashpack > 0) {
                                if ($cashpack_type == 'amount') {
                                    $data['cash_pack'] = $cashpack * $ct['qty'];
                                } else {
                                    $data['cash_pack'] = (($ct['price'] / 100) * $cashpack) * $ct['qty'];
                                }
                            }
                            $address_unicid = $this->input->post('addreessList');
                            if ($address_unicid != "") {
                                $address_unicid = $this->input->post('addreessList');
                                $shipping_address = $this->db->get_where('shipping_address', array('id' => $address_unicid))->result_array();
                                $shipping_address = $shipping_address[0];
                                $sh['firstname'] = $shipping_address['name'];
                                $sh['address1'] = $shipping_address['address'];
                                $sh['address2'] = $shipping_address['address1'];
                                $sh['zip'] = $shipping_address['zip_code'];
                                $sh['phone'] = $shipping_address['mobile'];
                                $sh['email'] = $shipping_address['email'];
                                $ctysplit = explode('-', $shipping_address['country']);
                                $country = $sh['country'] = $ctysplit[0];
                                $state = $sh['state'] =  $shipping_address['district'];
                                $city = $sh['cities'] = $shipping_address['city'];
                                $sn_country = $sh['short_country'] = $ctysplit[1];
                                $data['shipping_address']  = json_encode($sh);
                            } else {
                                $user_details = ($this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->result_array())[0];
                                $sh['firstname'] = $user_details['username'];
                                $sh['lastname'] = $user_details['surname'];
                                $sh['address1'] = $user_details['address1'];
                                $sh['address2'] = $user_details['address2'];
                                $sh['zip'] = $user_details['zip'];
                                $sh['phone'] = $user_details['phone'];
                                $sh['email'] = $user_details['email'];
                                $sh['country'] = $user_details['country'];
                                $sh['state'] =  $user_details['state'];
                                $sh['cities'] = $user_details['city'];
                                $sh['short_country'] = $user_details['country'];
                                $data['shipping_address'] = json_encode($sh);
                            }
                            $data['vat_percent'] = $vat_per;
                            $data['delivery_status']   = '[]';
                            $data['payment_type'] = ($rewards_using=="2"?"Rewards(".$reward_using_amt.") + ":"").'wallet';
                            $data['order_type'] = 'shopping';
                            $data['payment_status']    = '[]';
                            $data['payment_details']   = '';
                            $tax_1=$this->crud_model->get_product_tax($ct['id']);
                            $product_total = ($tax_1 + $ct['price'] + $ct['shipping']) * $ct['qty'];
                            $data['grand_total'] = $product_total;
                            $data['sale_datetime'] = time();
                            $data['delivary_datetime'] = '';
                            $data['group_deal'] = 1;
                            $data['order_id'] = $order_id;
                            $data['status'] = 'success';
                            $data['discount']=str_replace('RM','',$this->input->post('total_dis'));
                            //echo '<pre>'; print_r($data); exit;
                            $this->db->insert('sale', $data);
                            $sale_id = $this->db->insert_id();
                            if ($this->session->userdata('user_login') == 'yes') {
                                $data['buyer'] = $this->session->userdata('user_id');
                            } else {
                                $data['buyer'] = "guest";
                                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                $charactersLength = strlen($characters);
                                $randomString = '';
                                for ($i = 0; $i < 10; $i++) {
                                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                                }
                                $data['guest_id'] = 'guest' . $sale_id . '-' . $randomString;
                            }
                            $vendors = $this->crud_model->vendors_in_sale($sale_id);
                            $delivery_status = array();
                            $payment_status = array();
                            foreach ($vendors as $p) {
                                $delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');
                                $payment_status[] = array('vendor' => $p, 'status' => 'paid');
                                $data['sale_code'] = 'VE-' . $p . '-' . date('Ym', $data['sale_datetime']) . $sale_id;
                                //$data['seller']='VE-'.$p;
                            }
                            if ($this->crud_model->is_admin_in_sale($sale_id)) {
                                $delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');
                                $payment_status[] = array('admin' => '', 'status' => 'paid');
                                $data['sale_code'] = 'AD-' . date('Ym', $data['sale_datetime']) . $sale_id;
                            }
    
                            $data['delivery_status'] = json_encode($delivery_status);
                            $data['payment_status'] = json_encode($payment_status);
                            if ($rewards_post != ""){
                                $data['rewards_using'] = $rewards_using;
                                $data['reward_using_amt'] = $reward_using_amt;
                                $users['rewards']=$reward_amount;
                                $this->db->where('user_id', $user_id);
                                $this->db->update('user', $users);
                            }
                            $data['store_id'] = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->store_id;
                            $data['lalamove_res']=$this->input->post('lalamove_res');
                            
                            $this->db->where('sale_id', $sale_id);
                            $this->db->update('sale', $data);
                            $this->crud_model->digital_to_customer($sale_id);
    
                            //}
                        }
                        if ($this->session->userdata('user_id')) {
                            $lalamove_res0=$this->input->post('lalamove_res');
                   $delivery_charge=0;
                   if($lalamove_res0!="")
                  {
                    $lalamove_res = json_decode($lalamove_res0,true);
                   foreach($lalamove_res as $key=>$value)
                  {
                   if($value!="")
                  {
                   $lalamove_res1 = json_decode($value,true);
                   if($lalamove_res1['data']['priceBreakdown']['total']!="")
                   { $delivery_charge+=floatval($lalamove_res1['data']['priceBreakdown']['total']);}
                  }
                }
               }
               $tax_1=$this->crud_model->get_product_tax($ct['id']);
               $grand_totals = ($tax_1 +$ct['price'] + $ct['shipping']) * $ct['qty'];
               $discount = str_replace('RM','',$this->input->post('total_dis'));
                    $total=$delivery_charge+$grand_totals-$discount;
                            $datas['user_id'] = $this->session->userdata('user_id');
                            $datas['description'] = 'shopping';
                            $datas['mode'] = 'debit';
                            $datas['status'] = 'SUCCESS';
                            // $datas['servicetype'] = 8;
                            $datas['amount'] = $total;
                            $datas['date'] = time();
                            $datas['ref_id'] = $order_id;
                            $this->db->insert('user_trans_log', $datas);
                        }
                        $saleDet = $this->db->get_where('sale', array('order_id' => $order_id))->result_array();
                        foreach ($saleDet as $saldt) {
                            $tot_rewards += $saldt['rewards'];
                        }
                        $rewardsts = $this->db->get_where('sale', array('order_id' => $order_id))->result_array();
                        $rewardsts = $rewardsts[0];
                        $info = json_decode($rewardsts['shipping_address'], true);
                        $kaleyra_sms     =  $this->db->get_where('general_settings', array('general_settings_id' => '110'))->row()->kaleyra_sms_set;
                        if ($kaleyra_sms == 'ok') {
                            $mobile = $info['phone'];
                            $body  = "'Your Order #" . $order_id . " placed successfully, Thanks";
                            $template_id = 1607100000000112238;
                            $sendotp = $this->crud_model->send_sms_kaleyria($mobile, $template_id, $body);
                        }
                        $twilio_sms     =  $this->db->get_where('general_settings', array('general_settings_id' => '110'))->row()->twilio_sms_set;
                        if ($twilio_sms == 'ok') {
                            $sid     =  $this->db->get_where('general_settings', array('general_settings_id' => '115'))->row()->t_account_sid;
                            $token     =  $this->db->get_where('general_settings', array('general_settings_id' => '116'))->row()->t_auth_token;
                            $messagingServiceSid     =  $this->db->get_where('general_settings', array('general_settings_id' => '117'))->row()->twilio_number;
                            //  $sms="Your OTP: ".$otp."";
                            $mobile = $info['phone'];
                            $sms  = "'Your Order #" . $order_id . " placed successfully, Thanks";
    
                            require_once(APPPATH . 'libraries/Twilio/Twilio.php');
                            $ordersms = sendotp($sid, $token, $messagingServiceSid, $mobile, $sms);
                        }
                        $data4['uid'] = $this->session->userdata('user_id');
                        $data4['description'] = 'Rs ' . $grand_total . ' Debited from your wallet';
                        $this->db->insert('user_log', $data4);
    
    
                        $datar4['uid'] = $this->session->userdata('user_id');
                        $datar4['description'] = 'Rs ' . $tot_rewards . ' Rewards credited from your wallet';
                        $this->db->insert('user_log', $datar4);
                        //gopika
                        $update['payment_mode'] = $payment_type_post;
                        $this->db->where('id', $this->session->userdata('new_bidd_id'));
                        $this->db->update('bidding_history', $update);
                        $this->session->set_userdata('new_bidd_id', '');
    
                        if ($this->session->userdata('bidding_stock') != 'Bidding') {
                            foreach ($carted as $value) {
                                $this->crud_model->decrease_quantity($value['id'], $value['qty']);
                                $data1['type']         = 'destroy';
                                $data1['category']     = $this->db->get_where('product', array(
                                    'product_id' => $value['id']
                                ))->row()->category;
                                $data1['sub_category'] = $this->db->get_where('product', array(
                                    'product_id' => $value['id']
                                ))->row()->sub_category;
                                $data1['product']      = $value['id'];
                                $data1['quantity']     = $value['qty'];
                                $data1['total']        = 0;
                                $data1['reason_note']  = 'sale';
                                $data1['sale_id']      = $sale_id;
                                $data1['datetime']     = time();
                                $this->db->insert('stock', $data1);
                            }
                        }
    
                        $this->session->set_userdata('bidding_stock', '');
                        $this->wallet_model->reduce_user_balance($grand_total, $this->session->userdata('user_id'));
                        $this->wallet_model->add_reward_balance($tot_rewards, $this->session->userdata('user_id'));
                        //$this->crud_model->digital_to_customer($sale_id);
                        //$this->crud_model->email_invoice($sale_id);
                        $this->cart->destroy();
                        $this->session->set_userdata('couponer', '');
                        //echo $sale_id;
                        // redirect(base_url() . 'index.php/home/invoice/total/' . $order_id, 'refresh');
                        echo "<script>window.location.href = '".base_url() . 'index.php/home/invoice/total/' . $order_id."'; </script>";
                    }
                } else {
                    unset($_SESSION['p']['balance_alert']);
                    $_SESSION['p']['balance_alert'] = 'yes';
                    echo "<script>window.location.href = '".base_url() . 'index.php/home/profile/part/wallet/' ."'; </script>";
                }
            }
        } else if ($rewards_post != "") {
            $user_reward = $this->db->get_where('user', array('user_id' => $user_id))->result_array();
            $reward_amount = ($user_reward[0]['rewards']!="")?floatval($user_reward[0]['rewards']):0.0;
            if ($reward_amount >= $grand_total) {
                if ($para1 == 'go') {
                    $order_id = 'OD' . substr(time(), 4) . rand(1, 10) . rand(1, 99);
                    foreach ($carted as $ct) {

                        $no_qty = $ct['qty'];
                        $i = 1;

                        $pro['id'] = $ct['id'];
                        $pro['qty'] = $ct['qty'];
                        $pro['option'] = $ct['option'];
                        $pro['price'] = $ct['price'];
                        $rwd = $this->db->get_where('general_settings', array('type' => 'rewards'))->row()->value;
                        if ($rwd == 'ok') {
                            if ($this->session->userdata('user_login') == 'yes') {
                                $reward_p = $this->db->get_where('rewards', array('id' => '1'))->result_array();
                                $reward_p = $reward_p[0];
                                if ($reward_p['type'] == '%') {
                                    $data['rewards'] = ($ct['price'] * $reward_p['amount']) / 100;
                                } else if ($reward_p['type'] == 'flat') {
                                    $data['rewards'] = $reward_p['amount'];
                                }
                            }
                        }
                        $pro['name'] = $ct['name'];
                        $data['shipping'] = $pro['shipping'] = $ct['shipping'];
                        $data['vat'] = $pro['tax'] = $this->crud_model->get_product_tax($ct['id']);
                        $pro['image'] = $ct['image'];
                        $pro['coupon'] = $ct['coupon'];
                        $rowid = $pro['rowid'] = $ct['rowid'];
                        $pro['subtotal'] = $ct['subtotal'];
                        $pro1 = array($rowid => $pro);
                        $data['product_details'] = json_encode($pro1);
                        $cashpack = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->cashpack;
                        $cashpack_type = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->cashpack_type;
                        if ($cashpack > 0) {
                            if ($cashpack_type == 'amount') {
                                $data['cash_pack'] = $cashpack * $ct['qty'];
                            } else {
                                $data['cash_pack'] = (($ct['price'] / 100) * $cashpack) * $ct['qty'];
                            }
                        }
                        $address_unicid = $this->input->post('addreessList');
                        if ($address_unicid != "") {
                            $address_unicid = $this->input->post('addreessList');
                            $shipping_address = $this->db->get_where('shipping_address', array('id' => $address_unicid))->result_array();
                            $shipping_address = $shipping_address[0];
                            $sh['firstname'] = $shipping_address['name'];
                            $sh['address1'] = $shipping_address['address'];
                            $sh['address2'] = $shipping_address['address1'];
                            $sh['zip'] = $shipping_address['zip_code'];
                            $sh['phone'] = $shipping_address['mobile'];
                            $sh['email'] = $shipping_address['email'];
                            $ctysplit = explode('-', $shipping_address['country']);
                            $country = $sh['country'] = $ctysplit[0];
                            $state = $sh['state'] =  $shipping_address['district'];
                            $city = $sh['cities'] = $shipping_address['city'];
                            $sn_country = $sh['short_country'] = $ctysplit[1];
                            $data['shipping_address']  = json_encode($sh);
                        } else {
                            $user_details = ($this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->result_array())[0];
                            $sh['firstname'] = $user_details['username'];
                            $sh['lastname'] = $user_details['surname'];
                            $sh['address1'] = $user_details['address1'];
                            $sh['address2'] = $user_details['address2'];
                            $sh['zip'] = $user_details['zip'];
                            $sh['phone'] = $user_details['phone'];
                            $sh['email'] = $user_details['email'];
                            $sh['country'] = $user_details['country'];
                            $sh['state'] =  $user_details['state'];
                            $sh['cities'] = $user_details['city'];
                            $sh['short_country'] = $user_details['country'];

                            /* $sh['firstname'] = $_POST['firstname'];
                            $sh['lastname'] = $_POST['lastname'];
                            $sh['address1'] = $_POST['address1'];
                            $sh['address2'] = $_POST['address2'];
                            $sh['zip'] = $_POST['zip'];
                            $sh['phone'] = $_POST['phone'];
                            $sh['email'] = $_POST['email'];
                            $sh['country'] = $_POST['country'];
                            $sh['state'] =  $_POST['state'];
                            $sh['cities'] = $_POST['cities'];
                            $sh['short_country'] = $_POST['cou_shrt1']; */

                            $data['shipping_address'] = json_encode($sh);
                        }
                        $data['vat_percent'] = $vat_per;
                        $data['delivery_status']   = '[]';
                        $data['payment_type'] = 'rewards';
                        $data['order_type'] = 'shopping';
                        $data['payment_status']    = '[]';
                        $data['payment_details']   = '';
                        $tax_1=$this->crud_model->get_product_tax($ct['id']);
                        $product_total = ($tax_1 + $ct['price'] + $ct['shipping']) * $ct['qty'];
                        $data['grand_total'] = $product_total;
                        $data['sale_datetime'] = time();
                        $data['delivary_datetime'] = '';
                        $data['group_deal'] = 1;
                        $data['order_id'] = $order_id;
                        $data['status'] = 'success';
                        $data['rewards_using'] = '2';
                        $data['reward_using_amt'] = $grand_total;
                        $data['discount']=str_replace('RM','',$this->input->post('total_dis'));
                        $this->db->insert('sale', $data);
                        $sale_id = $this->db->insert_id();
                        if ($this->session->userdata('user_login') == 'yes') {
                            $data['buyer'] = $user_id;
                        } else {
                            $data['buyer'] = "guest";
                            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            $charactersLength = strlen($characters);
                            $randomString = '';
                            for ($i = 0; $i < 10; $i++) {
                                $randomString .= $characters[rand(0, $charactersLength - 1)];
                            }
                            $data['guest_id'] = 'guest' . $sale_id . '-' . $randomString;
                        }
                        $vendors = $this->crud_model->vendors_in_sale($sale_id);
                        $delivery_status = array();
                        $payment_status = array();
                        foreach ($vendors as $p) {
                            $delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');
                            $payment_status[] = array('vendor' => $p, 'status' => 'paid');
                            $data['sale_code'] = 'VE-' . $p . '-' . date('Ym', $data['sale_datetime']) . $sale_id;
                        }
                        if ($this->crud_model->is_admin_in_sale($sale_id)) {
                            $delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');
                            $payment_status[] = array('admin' => '', 'status' => 'paid');
                            $data['sale_code'] = 'AD-' . date('Ym', $data['sale_datetime']) . $sale_id;
                        }

                        $data['delivery_status'] = json_encode($delivery_status);
                        $data['payment_status'] = json_encode($payment_status);
                        
                        $data['store_id'] = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->store_id;
                        $data['lalamove_res']=$this->input->post('lalamove_res');
                        
                        $this->db->where('sale_id', $sale_id);
                        $this->db->update('sale', $data);
                        $this->crud_model->digital_to_customer($sale_id);
                    }
                    if ($user_id) {
                        $lalamove_res0=$this->input->post('lalamove_res');
                   $delivery_charge=0;
                   if($lalamove_res0!="")
                  {
                    $lalamove_res = json_decode($lalamove_res0,true);
                   foreach($lalamove_res as $key=>$value)
                  {
                   if($value!="")
                  {
                   $lalamove_res1 = json_decode($value,true);
                   if($lalamove_res1['data']['priceBreakdown']['total']!="")
                   { $delivery_charge+=floatval($lalamove_res1['data']['priceBreakdown']['total']);}
                  }
                }
               }
               $tax_1=$this->crud_model->get_product_tax($ct['id']);
               $grand_totals = ($tax_1 +$ct['price'] + $ct['shipping']) * $ct['qty'];
               $discount = str_replace('RM','',$this->input->post('total_dis'));
                    $total=$delivery_charge+$grand_totals-$discount;
                        $datas['user_id'] = $user_id;
                        $datas['description'] = 'shopping';
                        $datas['mode'] = 'debit';
                        $datas['status'] = 'SUCCESS';
                        // $datas['servicetype'] = 8;
                        $datas['amount'] = $total;
                        $datas['date'] = time();
                        $datas['ref_id'] = $order_id;
                        $this->db->insert('user_trans_log', $datas);

                        $balance_reward=($reward_amount - $grand_total);
                        $users['rewards']=$balance_reward;
                        $this->db->where('user_id', $user_id);
                        $this->db->update('user', $users);
                    }
                    $saleDet = $this->db->get_where('sale', array('order_id' => $order_id))->result_array();
                    foreach ($saleDet as $saldt) {
                        $tot_rewards += $saldt['rewards'];
                    }
                    $rewardsts = $this->db->get_where('sale', array('order_id' => $order_id))->result_array();
                    $rewardsts = $rewardsts[0];
                    $info = json_decode($rewardsts['shipping_address'], true);
                    $kaleyra_sms     =  $this->db->get_where('general_settings', array('general_settings_id' => '110'))->row()->kaleyra_sms_set;
                    if ($kaleyra_sms == 'ok') {
                        $mobile = $info['phone'];
                        $body  = "'Your Order #" . $order_id . " placed successfully, Thanks";
                        $template_id = 1607100000000112238;
                        $sendotp = $this->crud_model->send_sms_kaleyria($mobile, $template_id, $body);
                    }
                    $twilio_sms     =  $this->db->get_where('general_settings', array('general_settings_id' => '110'))->row()->twilio_sms_set;
                    if ($twilio_sms == 'ok') {
                        $sid     =  $this->db->get_where('general_settings', array('general_settings_id' => '115'))->row()->t_account_sid;
                        $token     =  $this->db->get_where('general_settings', array('general_settings_id' => '116'))->row()->t_auth_token;
                        $messagingServiceSid     =  $this->db->get_where('general_settings', array('general_settings_id' => '117'))->row()->twilio_number;
                        $mobile = $info['phone'];
                        $sms  = "'Your Order #" . $order_id . " placed successfully, Thanks";

                        require_once(APPPATH . 'libraries/Twilio/Twilio.php');
                        $ordersms = sendotp($sid, $token, $messagingServiceSid, $mobile, $sms);
                    }
                    $data4['uid'] = $user_id;
                    $data4['description'] = 'Rs ' . $grand_total . ' Debited from your wallet';
                    $this->db->insert('user_log', $data4);


                    $datar4['uid'] = $user_id;
                    $datar4['description'] = 'Rs ' . $tot_rewards . ' Rewards credited from your wallet';
                    $this->db->insert('user_log', $datar4);
                    
                    $update['payment_mode'] = "rewards";
                    $this->db->where('id', $this->session->userdata('new_bidd_id'));
                    $this->db->update('bidding_history', $update);
                    $this->session->set_userdata('new_bidd_id', '');

                    if ($this->session->userdata('bidding_stock') != 'Bidding') {
                        foreach ($carted as $value) {
                            $this->crud_model->decrease_quantity($value['id'], $value['qty']);
                            $data1['type']         = 'destroy';
                            $data1['category']     = $this->db->get_where('product', array(
                                'product_id' => $value['id']
                            ))->row()->category;
                            $data1['sub_category'] = $this->db->get_where('product', array(
                                'product_id' => $value['id']
                            ))->row()->sub_category;
                            $data1['product']      = $value['id'];
                            $data1['quantity']     = $value['qty'];
                            $data1['total']        = 0;
                            $data1['reason_note']  = 'sale';
                            $data1['sale_id']      = $sale_id;
                            $data1['datetime']     = time();
                            $this->db->insert('stock', $data1);
                        }
                    }

                    $this->session->set_userdata('bidding_stock', '');
                    //$this->wallet_model->reduce_user_balance($grand_total, $user_id);
                    //$this->wallet_model->add_reward_balance($tot_rewards, $user_id);
                    $this->cart->destroy();
                    $this->session->set_userdata('couponer', '');
                    // redirect(base_url() . 'index.php/home/invoice/total/' . $order_id, 'refresh');
                    echo "<script>window.location.href = '".base_url() . 'index.php/home/invoice/total/' . $order_id."'; </script>";
                }
            } else {
                unset($_SESSION['p']['reward_balance_alert']);
                $_SESSION['p']['reward_balance_alert'] = 'yes';
                echo "<script>window.location.href = '".base_url() . 'index.php/home/cart_checkout' . "'; </script>";
            }
        }
    }
 function ipay88_save_wallet($para1='')
    {
       
        
        if(($para1 == '')){
            redirect(base_url() . 'index.php/home/', 'refresh');
            exit;
        }
      
        $ipay88_wallet_requestdata=$this->db->get_where('ipay88_wallet_requestdata', array('id' => $para1))->result_array();
        if(count($ipay88_wallet_requestdata)==0){
            
            
            redirect(base_url() . 'index.php/home/', 'refresh');
            exit;
            
        }
        
          if($ipay88_wallet_requestdata[0]['user_id']==""){
             
            redirect(base_url() . 'index.php/home/login_set/login', 'refresh');
            exit;
        }
        else{
             $signin_data = $this->db->get_where('user', array('user_id' => $ipay88_wallet_requestdata[0]['user_id']))->result_array();
            foreach ($signin_data as $row) {
                $this->session->set_userdata('user_login', 'yes');
                $this->session->set_userdata('user_id', $row['user_id']);
                $this->session->set_userdata('user_name', $row['username']);
                $this->session->set_userdata('user_email', $row['email']);
                $this->session->set_userdata('user_login', 'yes');
                break;
            }
            
        }
          
         if($this->session->userdata('user_id')==""){
            redirect(base_url() . 'index.php/home/login_set/login', 'refresh');
            exit;
        }
        
        if($_REQUEST['Status']==0){
            
           
           redirect(base_url() . 'index.php/home/profile/part/wallet/', 'refresh');
            exit;
        }else{
            
            
            
                            $usera = (array) $usera;
                            $charge = (array) $charge;

                            $data['user']                   = $this->session->userdata('user_id');
                            $data['method']                 = 'ipay88';
                            $data['amount']                 = $_REQUEST['Amount'];
                            $data['status']                 = 'paid';
                            $data['payment_details']        = "Customer ref: \n" . $_REQUEST['RefNo'] ;
                            $this->db->insert('wallet_load', $data);

                            $id = $this->db->insert_id();
                            $user = $this->db->get_where('wallet_load', array('wallet_load_id' => $id))->row()->user;
                            $amount = $this->db->get_where('wallet_load', array('wallet_load_id' => $id))->row()->amount;
                            $balance = base64_decode($this->db->get_where('user', array('user_id' => $user))->row()->wallet);
                            $new_balance = base64_encode($balance + $amount);
                            $this->db->where('user_id', $user);
                            $this->db->update('user', array('wallet' => $new_balance));

                            redirect(base_url() . 'index.php/home/profile/part/wallet/', 'refresh');
                        
                        
            
            
        }
        
    //'index.php/home/profile/part/wallet/'
    }
    
    function ipay88_save_sales($para1 = '', $para2 = '')
    {
        if(($para1 == '') || ($para2 == '')){
            redirect(base_url() . 'index.php/home/', 'refresh');
            exit;
        }
        $ipay88_requestdata=$this->db->get_where('ipay88_requestdata', array('id' => $para1,'order_id' => $para2))->result_array();
        if(count($ipay88_requestdata)==0){
            redirect(base_url() . 'index.php/home/', 'refresh');
            exit;
        }else{
            $carted1=$ipay88_requestdata[0]['carted'];
            $lalamove_res1=$ipay88_requestdata[0]['lalamove_res'];
            $ipay88_requestdata=json_decode($ipay88_requestdata[0]['request_data'],true);
            $ipay88_requestdata['carted']=$carted1;
            $ipay88_requestdata['lalamove_res']=$lalamove_res1;
            $this->db->where("order_id!='".$ipay88_requestdata['order_id']."' and user_id='".$ipay88_requestdata['user_id']."'");
            $this->db->delete('ipay88_requestdata');
        }
        if($ipay88_requestdata['user_id']==""){
            redirect(base_url() . 'index.php/home/login_set/login', 'refresh');
            exit;
        }else{
            $signin_data = $this->db->get_where('user', array('user_id' => $ipay88_requestdata['user_id']))->result_array();
            foreach ($signin_data as $row) {
                $this->session->set_userdata('user_login', 'yes');
                $this->session->set_userdata('user_id', $row['user_id']);
                $this->session->set_userdata('user_name', $row['username']);
                $this->session->set_userdata('user_email', $row['email']);
                $this->session->set_userdata('user_login', 'yes');
                $this->session->set_userdata('delivery_final_value', $ipay88_requestdata['delivery_final_value']);
                $this->session->set_userdata('pickup_date', $ipay88_requestdata['pickup_date']);
                $this->session->set_userdata('pickup_slot', $ipay88_requestdata['pickup_slot']);
                $this->session->set_userdata('pre_order_status', $ipay88_requestdata['pre_order_status']);
                $this->session->set_userdata('pre_order_date', $ipay88_requestdata['pre_order_date']);
                $this->session->set_userdata('pickup', $ipay88_requestdata['pickup']);
                $this->session->set_userdata('user_zips', $ipay88_requestdata['user_zips']);
                break;
            }
        }
        if($this->session->userdata('user_id')==""){
            redirect(base_url() . 'index.php/home/login_set/login', 'refresh');
            exit;
        }
        $this->cart->destroy();
        $sales_check_duplicate_user_reward = $this->db->get_where('sale', array('order_id' => $ipay88_requestdata['order_id']))->result_array();
        if(count($sales_check_duplicate_user_reward)>0){
            unset($_SESSION['p']['ipay88_success']);
            $_SESSION['p']['ipay88_success'] = 'yes';
            redirect(base_url() . 'index.php/home/invoice/total/' . $ipay88_requestdata['order_id'].'/ipay88_success', 'refresh');
            exit();
        }
        $carted=json_decode($ipay88_requestdata['carted'],true);
        $this->cart->product_name_rules = '[:print:]';
        foreach ($carted as $ct) {
            $product_id=$ct['id'];
            if (!$this->crud_model->is_added_to_cart($product_id)) {
                $qty = $ct['qty'];
                $cartdata_1 = array(
                    'id' => $product_id,
                    'qty' => $qty,
                    'option' => $ct['option'],
                    'price'=>$ct['price'],
                    'name' => $ct['name'],
                    'shipping' => $ct['shipping'],
                    'tax' => $ct['tax'],
                    'image' => $ct['image'],
                    'coupon' => '',
                    'subscribamt' => ''
                );
                $this->cart->insert($cartdata_1);
            }
        }
        $ipay88_response=$_POST;
        //print_r($ipay88_response);
        //exit();
        if(trim($ipay88_response['Status'])!='1'){
            unset($_SESSION['p']['ipay88_alert']);
            $_SESSION['p']['ipay88_alert'] = 'yes';
            redirect(base_url() . 'index.php/home/cart_checkout/ipay88_alert', 'refresh');
            exit;
        }
        $payment_option_dis=$ipay88_requestdata['payment_option_dis'];
        $user_id=$ipay88_requestdata['user_id'];
        $rewards_post=$ipay88_requestdata['rewards'];
        $payment_type_post=$ipay88_requestdata['payment_type'];
        if (count($carted) <= 0) {
            redirect(base_url() . 'index.php/home/', 'refresh');
            exit;
        }
        $data['total_invoice_id'] = $total_invoice_id = $this->db->order_by('sale_id', 'desc')->limit('1')->get('sale')->row()->sale_id;

        $total    = $this->cart->total();
        $exchange = exchange('usd');
        $vat_per  = '';
        $vat      = 0;
        foreach ($carted as $ct) {
            $tax_1=$this->crud_model->get_product_tax($ct['id']);
            if($tax_1!=""){$vat+=floatval($tax_1);}
        }
        
        if ($ipay88_requestdata['user_zips'] != "") {
            $shipping = $this->db->get_where('business_settings', array('type' => 'delivery_fee'))->row()->value;
        }
        $grand_total     = $total + $vat + $shipping;
        
        $product_details = json_encode($carted);
        
        if(($payment_type_post != "") && ($payment_option_dis == "1")){
            $reward_amount=0.0;$rewards_using="0";$reward_using_amt=0.0;
            if ($rewards_post != "") {
                $user_reward = $this->db->get_where('user', array('user_id' => $user_id))->result_array();
                $reward_amount = ($user_reward[0]['rewards']!="")?floatval($user_reward[0]['rewards']):0.0;
                if ($reward_amount > 0.0) {
                    $rewards_using="2";
                    if($reward_amount >= $grand_total){
                        $reward_amount=($reward_amount - $grand_total);
                        $reward_using_amt=$grand_total;
                        $grand_total=0.0;
                    } else {
                        $grand_total=($grand_total - $reward_amount);
                        $reward_using_amt=$reward_amount;
                        $reward_amount=0.0;
                    }
                }
            }
            $order_id=$ipay88_requestdata['order_id'];
            $data['sale_datetime'] = time();
            foreach ($carted as $ct) {
                $no_qty = $ct['qty'];
                $i = 1;

                $pro['id'] = $ct['id'];
                $pro['qty'] = $ct['qty'];
                $pro['option'] = $ct['option'];
                $pro['price'] = $ct['price'];
                $rwd = $this->db->get_where('general_settings', array('type' => 'rewards'))->row()->value;
                
                $pro['name'] = $ct['name'];
                $data['shipping'] = $pro['shipping'] = $ct['shipping'];
                $data['vat'] = $pro['tax'] = $this->crud_model->get_product_tax($ct['id']);
                $pro['image'] = $ct['image'];
                $pro['coupon'] = $ct['coupon'];
                $rowid = $pro['rowid'] = $ct['rowid'];
                $pro['subtotal'] = $ct['subtotal'];
                $pro1 = array($rowid => $pro);
                $data['product_details'] = json_encode($pro1);
                $data['store_id'] = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->store_id;
                $cashpack = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->cashpack;
                $cashpack_type = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->cashpack_type;
                if ($cashpack > 0) {
                    if ($cashpack_type == 'amount') {
                        $data['cash_pack'] = $cashpack * $ct['qty'];
                    } else {
                        $data['cash_pack'] = (($ct['price'] / 100) * $cashpack) * $ct['qty'];
                    }
                }
                $address_unicid = $ipay88_requestdata['addreessList'];
                if ($address_unicid != "") {
                    $shipping_address = $this->db->get_where('shipping_address', array('id' => $address_unicid))->result_array();
                    $shipping_address = $shipping_address[0];
                    $sh['firstname'] = $shipping_address['name'];
                    $sh['address1'] = $shipping_address['address'];
                    $sh['address2'] = $shipping_address['address1'];
                    $sh['zip'] = $shipping_address['zip_code'];
                    $sh['phone'] = $shipping_address['mobile'];
                    $sh['email'] = $shipping_address['email'];
                    $ctysplit = explode('-', $shipping_address['country']);
                    $country = $sh['country'] = $ctysplit[0];
                    $state = $sh['state'] =  $shipping_address['district'];
                    $city = $sh['cities'] = $shipping_address['city'];
                    $sn_country = $sh['short_country'] = $ctysplit[1];
                    $data['shipping_address']  = json_encode($sh);
                } else {
                    $user_details = ($this->db->get_where('user', array('user_id' => $ipay88_requestdata['user_id']))->result_array())[0];
                    $sh['firstname'] = $user_details['username'];
                    $sh['lastname'] = $user_details['surname'];
                    $sh['address1'] = $user_details['address1'];
                    $sh['address2'] = $user_details['address2'];
                    $sh['zip'] = $user_details['zip'];
                    $sh['phone'] = $user_details['phone'];
                    $sh['email'] = $user_details['email'];
                    $sh['country'] = $user_details['country'];
                    $sh['state'] =  $user_details['state'];
                    $sh['cities'] = $user_details['city'];
                    $sh['short_country'] = $user_details['country'];
                    $data['shipping_address'] = json_encode($sh);
                }
                $data['vat_percent'] = $vat_per;
                $data['delivery_status']   = '[]';
                $data['product_notes'] = $ipay88_requestdata['product_notes'];
                $grand = $grand_total;
                $post_data['total_amount'] = $data['rewards'] = $data['order_amount'] = $grand+(float)$ipay88_requestdata['delivery_final_value'];

                $data['payment_type'] = ($rewards_using=="2"?"Rewards(".$reward_using_amt.") + ":"").'ipay88';
                if ($ipay88_requestdata['pickup'] != "") {
                    $data['order_type'] = 'pickup';
                }
                if ($ipay88_requestdata['user_zips'] != "") {
                    $data['order_type'] = 'delivery';
                }
                $data['payment_status']    = '[]';
                $data['payment_details']   = json_encode($ipay88_response);
                $tax_1=$this->crud_model->get_product_tax($ct['id']);
                $product_total = ($tax_1 + $ct['price'] + $ct['shipping']) * $ct['qty'];
                $data['grand_total'] = $product_total;
                
                $data['delivary_datetime'] = '';
                $data['group_deal'] = 1;
                $data['order_id'] = $order_id;
                $data['status'] = 'success';
                $data['pickup_date'] = $ipay88_requestdata['pickup_date'];
                $data['pickup_slot'] = $ipay88_requestdata['pickup_slot'];
                if ($ipay88_requestdata['pre_order_status']) {
                    $data['pre_order_status'] = $ipay88_requestdata['pre_order_status'];
                } else {
                    $data['pre_order_status'] = 'no';
                }
                if ($ipay88_requestdata['pre_order_date']) {
                    $data['pre_order_date'] = $ipay88_requestdata['pre_order_date'];
                }
                $data['discount'] = $ipay88_requestdata['total_dis'];
                $this->db->insert('sale', $data);
                // echo $this->db->last_query();
                $sale_id = $this->db->insert_id();

                $page_data['asset_page']    = "invoice";
                $this->session->set_userdata('allData', json_encode($data));
                if ($ipay88_requestdata['user_login'] == 'yes') {
                    $data['buyer'] = $ipay88_requestdata['user_id'];
                } else {
                    $data['buyer'] = "guest";
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $randomString = '';
                    for ($j = 0; $j < 10; $j++) {
                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                    }
                    $data['guest_id'] = 'guest' . $sale_id . '-' . $randomString;
                }
                $vendors = $this->crud_model->vendors_in_sale($sale_id);
                $delivery_status = array();
                $payment_status = array();
                foreach ($vendors as $p) {
                    $delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');
                    $payment_status[] = array('vendor' => $p, 'status' => 'due');
                    $data['sale_code'] = 'VE-' . $p . '-' . date('Ym', $data['sale_datetime']) . $sale_id;
                }
                if ($this->crud_model->is_admin_in_sale($sale_id)) {
                    $delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');
                    $payment_status[] = array('admin' => '', 'status' => 'due');
                    $data['sale_code'] = 'AD-' . date('Ym', $data['sale_datetime']) . $sale_id;
                }

                $data['delivery_status'] = json_encode($delivery_status);
                $data['payment_status'] = json_encode($payment_status);
                if ($rewards_post != ""){
                    $data['rewards_using'] = $rewards_using;
                    $data['reward_using_amt'] = $reward_using_amt;
                    $users['rewards']=$reward_amount;
                    $this->db->where('user_id', $user_id);
                    $this->db->update('user', $users);
                }
                $data['store_id'] = $this->db->get_where('product', array('product_id' => $ct['id']))->row()->store_id;
                $data['lalamove_res']=$ipay88_requestdata['lalamove_res'];
                
                $this->db->where('sale_id', $sale_id);
                $this->db->update('sale', $data);
                $this->crud_model->digital_to_customer($sale_id);
            }
            if ($rewards_using=="2") {
                $gtotals = $data['order_amount'] - $reward_using_amt;
                $data_r['order_id'] = $order_id;
                $data_r['buyer'] = $ipay88_requestdata['user_id'];
                $data_r['reward_amt'] = $reward_using_amt;
                $data_r['status'] = 'no';
                $this->db->insert('rewards_log', $data_r);
            } else {
                $gtotals = $data['order_amount'];
            }
            $page_data['grand_total'] = round($gtotals);
            if ($ipay88_requestdata['user_id']) {
                 $lalamove_res0=$this->input->post('lalamove_res');
                   $delivery_charge=0;
                   if($lalamove_res0!="")
                  {
                    $lalamove_res = json_decode($lalamove_res0,true);
                   foreach($lalamove_res as $key=>$value)
                  {
                   if($value!="")
                  {
                   $lalamove_res1 = json_decode($value,true);
                   if($lalamove_res1['data']['priceBreakdown']['total']!="")
                   { $delivery_charge+=floatval($lalamove_res1['data']['priceBreakdown']['total']);}
                  }
                }
               }
               $grand_total = $page_data['grand_total'];
               $discount = $ipay88_requestdata['total_dis'];
                    $total=$delivery_charge+$grand_total-$discount;
                $datas['user_id'] = $ipay88_requestdata['user_id'];
                $datas['description'] = 'shopping';
                $datas['mode'] = 'debit';
                $datas['status'] = 'pending';
                // $datas['servicetype'] = 8;
                $datas['amount'] = $total;
                $datas['date'] = time();
                $datas['ref_id'] = $order_id;

                $this->db->insert('user_trans_log', $datas);
            } else {
                 $lalamove_res0=$ipay88_requestdata['lalamove_res'];
                   $delivery_charge=0;
                   if($lalamove_res0!="")
                  {
                    $lalamove_res = json_decode($lalamove_res0,true);
                   foreach($lalamove_res as $key=>$value)
                  {
                   if($value!="")
                  {
                   $lalamove_res1 = json_decode($value,true);
                   if($lalamove_res1['data']['priceBreakdown']['total']!="")
                   { $delivery_charge+=floatval($lalamove_res1['data']['priceBreakdown']['total']);}
                  }
                }
               }
               $grand_total = $page_data['grand_total'];
               $discount = $ipay88_requestdata['total_dis'];
                    $total=$delivery_charge+$grand_total-$discount;
                $datas['user_id'] = 'guest';
                $datas['description'] = 'shopping';
                $datas['mode'] = 'debit';
                $datas['status'] = 'pending';
                // $datas['servicetype'] = 8;
                $datas['amount'] = $total;
                $datas['date'] = time();
                $datas['ref_id'] = $order_id;

                $this->db->insert('user_trans_log', $datas);
            }
            $this->crud_model->digital_to_customer($order_id);
            $this->crud_model->email_invoice($order_id);
            $this->cart->destroy();
            $this->session->set_userdata('couponer', '');
            unset($_SESSION['p']['ipay88_success']);
            $_SESSION['p']['ipay88_success'] = 'yes';
            redirect(base_url() . 'index.php/home/invoice/total/' . $order_id.'/ipay88_success', 'refresh');
        }
    }
    /* FUNCTION: Verify paypal payment by IPN*/
    function paypal_ipn()
    {
        //echo "a"; exit;
        if ($this->paypal->validate_ipn() == true) {

            $data['payment_details']   = json_encode($_POST);
            $data['payment_timestamp'] = strtotime(date("m/d/Y"));
            $data['payment_type']      = 'paypal';
            $sale_id                   = $_POST['custom'];
            $vendors = $this->crud_model->vendors_in_sale($sale_id);
            $payment_status = array();
            foreach ($vendors as $p) {
                $payment_status[] = array('vendor' => $p, 'status' => 'paid');
            }
            if ($this->crud_model->is_admin_in_sale($sale_id)) {
                $payment_status[] = array('admin' => '', 'status' => 'paid');
            }

            $data['status'] = 'success';
            $data['payment_status'] = json_encode($payment_status);
            //print_r($data); exit;
            $this->db->where('sale_id', $sale_id);
            $this->db->update('sale', $data);
            //echo $this->db->last_query();
        }
    }

    /* FUNCTION: Loads after cancelling paypal*/
    function paypal_cancel()
    {
        $sale_id = $this->session->userdata('sale_id');
        $this->db->where('sale_id', $sale_id);
        $this->db->delete('sale');
        $this->session->set_userdata('sale_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'index.php/home/cart_checkout/', 'refresh');
    }

    /* FUNCTION: Loads after successful paypal payment*/
    function paypal_success()
    {
        $carted  = $this->cart->contents();
        $sale_id = $this->session->userdata('sale_id');
        if ($this->session->userdata('bidding_stock') != 'Bidding') {
            foreach ($carted as $value) {
                $this->crud_model->decrease_quantity($value['id'], $value['qty']);
                $data1['type']         = 'destroy';
                $data1['category']     = $this->db->get_where('product', array(
                    'product_id' => $value['id']
                ))->row()->category;
                $data1['sub_category'] = $this->db->get_where('product', array(
                    'product_id' => $value['id']
                ))->row()->sub_category;

                $data1['product']      = $value['id'];
                $data1['quantity']     = $value['qty'];
                $data1['total']        = 0;
                $data1['reason_note']  = 'sale';
                $data1['sale_id']      = $sale_id;
                $data1['datetime']     = time();
                $this->db->insert('stock', $data1);
            }
        }


        $order_id = $this->session->userdata('order_id');
        $orders = $this->db->get_where('sale', array('order_id' => $order_id))->result_array();
        $sale_id = array();
        foreach ($orders as $ods) {
            $sale_id[] = $ods['sale_id'];
        }
        foreach ($sale_id as $id) {
            $vendors = $this->crud_model->vendors_in_sale($id);
            $payment_status = array();
            foreach ($vendors as $p) {
                $payment_status[] = array('vendor' => $p, 'status' => 'paid');
            }
            if ($this->crud_model->is_admin_in_sale($id)) {
                $payment_status[] = array('admin' => '', 'status' => 'paid');
            }

            $data['status'] = 'success';
            $data['payment_status'] = json_encode($payment_status);
            //print_r($data); exit;
            $this->db->where('sale_id', $id);
            $this->db->update('sale', $data);
        }

        $saleDet = $this->db->get_where('sale', array('order_id' => $order_id))->result_array();
        foreach ($saleDet as $saldt) {
            $tot_rewards += $saldt['rewards'];
        }


        $datar4['uid'] = $this->session->userdata('user_id');
        $datar4['description'] = 'Rs ' . $tot_rewards . ' Rewards credited from your wallet';
        $this->db->insert('user_log', $datar4);
        $this->wallet_model->add_reward_balance($tot_rewards, $this->session->userdata('user_id'));




        // $this->crud_model->digital_to_customer($sale_id);
        $this->cart->destroy();
        $this->session->set_userdata('couponer', '');
        //$this->crud_model->email_invoice($$order_id);
        $this->session->set_userdata('sale_id', '');
        $this->session->set_userdata('bidding_stock', '');
        redirect(base_url() . 'index.php/home/invoice/total/' . $order_id, 'refresh');
    }
    function iyzipay_success()
    {
        echo var_dump($_REQUEST);
        exit;
        //echo "a"; exit;
        require_once('libraries/samples/config.php');

        # create request class
        $request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId("123456789");
        $request->setToken($_POST['token']);
        echo $_POST['token'];
        # make request
        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, Config::options());

        # print result
        $rdt = $checkoutForm;
        //print_r($rdt); exit;

        //echo $_REQUEST['transaction_id'];
        //if($_REQUEST['status']=='Success'){
        $carted  = $this->cart->contents();
        //print_r($carted);
        echo   "sid" . $sale_id = $this->session->userdata('sale_id');
        echo  "oid" . $order_id = $this->session->userdata('order_id');
        exit;





        if ($this->session->userdata('bidding_stock') != 'Bidding') {
            foreach ($carted as $value) {
                $this->crud_model->decrease_quantity($value['id'], $value['qty']);
                $data1['type']         = 'destroy';
                $data1['category']     = $this->db->get_where('product', array(
                    'product_id' => $value['id']
                ))->row()->category;
                $data1['sub_category'] = $this->db->get_where('product', array(
                    'product_id' => $value['id']
                ))->row()->sub_category;

                $data1['product']      = $value['id'];
                $data1['quantity']     = $value['qty'];
                $data1['total']        = 0;
                $data1['reason_note']  = 'sale';
                $data1['sale_id']      = $order_id;
                $data1['datetime']     = time();
                $this->db->insert('stock', $data1);
            }
        }


        //$order_id=$this->session->userdata('order_id');
        //$order_id=$_REQUEST['transaction_id'];
        $orders = $this->db->get_where('sale', array('order_id' => $order_id))->result_array();
        $sale_id = array();
        foreach ($orders as $ods) {
            $sale_id[] = $ods['sale_id'];
            $tot_rewards += $saldt['rewards'];
        }
        foreach ($sale_id as $id) {
            $vendors = $this->crud_model->vendors_in_sale($id);
            $payment_status = array();
            foreach ($vendors as $p) {
                $payment_status[] = array('vendor' => $p, 'status' => 'paid');
            }
            if ($this->crud_model->is_admin_in_sale($id)) {
                $payment_status[] = array('admin' => '', 'status' => 'paid');
            }
            $data['payment_timestamp'] = strtotime(date("m/d/Y"));
            $data['status'] = 'success';
            $data['payment_status'] = json_encode($payment_status);
            //print_r($data); exit;
            $this->db->where('sale_id', $id);
            $this->db->update('sale', $data);
        }

        if ($this->session->userdata('user_login') == 'yes') {
            $this->wallet_model->add_reward_balance($tot_rewards, $this->session->userdata('user_id'));
        }






        // $this->crud_model->digital_to_customer($sale_id);
        $this->cart->destroy();
        $this->session->set_userdata('couponer', '');
        //$this->crud_model->email_invoice($$order_id);
        $this->session->set_userdata('sale_id', '');
        $this->session->set_userdata('bidding_stock', '');
        redirect(base_url() . 'index.php/home/invoice/total/' . $order_id, 'refresh');
        /*}
        else{
            
            $sale_id = $this->session->userdata('sale_id');
        $this->db->where('sale_id', $sale_id);
        $this->db->delete('sale');
        $this->session->set_userdata('sale_id', '');
        $this->session->set_flashdata('alert', 'payment_failled');
        redirect(base_url() . 'index.php/home/cart_checkout/', 'refresh');
            }*/
    }

    function ipayscuccess()
    {
        print_r($_REQUEST);
        exit;

        //reward update with reduce rewards amount

        //my transaction success or failed

    }
    function pum_success()
    {
        // echo 'abc
        $status = $_POST["status"];
        $firstname = $_POST["firstname"];
        $amount = $_POST["amount"];
        $txnid = $_POST["txnid"];
        $posted_hash = $_POST["hash"];
        $key = $_POST["key"];
        $productinfo = $_POST["productinfo"];
        $email = $_POST["email"];
        $udf1 = $_POST['udf1'];
        //print_r($_POST); exit;
        $salt = $this->crud_model->get_settings_value('business_settings', 'pum_merchant_salt', 'value');

        if (isset($_POST["additionalCharges"])) {
            $additionalCharges = $_POST["additionalCharges"];
            $retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '||||||||||' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
        } else {
            $retHashSeq = $salt . '|' . $status . '||||||||||' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
        }
        $hash = hash("sha512", $retHashSeq);

        if ($hash != $posted_hash) {
            $sale_id = $this->session->userdata('sale_id');
            $this->db->where('sale_id', $sale_id);
            $this->db->delete('sale');
            $this->session->set_userdata('sale_id', '');
            $this->session->set_flashdata('alert', 'payment_cancel');
            redirect(base_url() . 'home/cart_checkout/', 'refresh');
        } else {

            $sale_id = $this->session->userdata('sale_id');
            $data['payment_type'] = 'pum';
            $carted  = $this->cart->contents();
            $total_invoice_id = $this->db->get_where('sale', array('order_id' => $udf1))->row()->total_invoice_id;
            $saleDet = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
            //  $saleDet=$saleDet[0];
            //$sale_id = $saleDet['sale_id'];
            //$user_id=$saleDet['buyer'];
            foreach ($carted as $value) {
                $this->crud_model->decrease_quantity($value['id'], $value['qty']);
                $data1['type']         = 'destroy';
                $data1['category']     = $this->db->get_where('product', array(
                    'product_id' => $value['id']
                ))->row()->category;
                $data1['sub_category'] = $this->db->get_where('product', array(
                    'product_id' => $value['id']
                ))->row()->sub_category;
                $data1['product']      = $value['id'];
                $data1['quantity']     = $value['qty'];
                $data1['total']        = 0;
                $data1['reason_note']  = 'sale';
                $data1['sale_id']      = $merchant_order_id;
                $data1['datetime']     = time();
                $this->db->insert('stock', $data1);
                /*$pro_price     = $this->db->get_where('product', array(
                        'product_id' => $value['id']
                    ))->row()->sale_price;
                    
                    if ($this->session->userdata('user_login') == 'yes') {
                            $data['rewards']=($pro_price*2)/100;
                            }*/
            }
            foreach ($saleDet as $saldt) {
                //print_r($saldt);

                $vendors = $this->crud_model->vendors_in_sale($saldt['sale_id']);
                // print_r($vendors);
                $payment_status = array();
                foreach ($vendors as $p) {
                    $payment_status[] = array('vendor' => $p, 'status' => 'paid');
                }
                if ($this->crud_model->is_admin_in_sale($sale_id)) {
                    $payment_status[] = array('admin' => '', 'status' => 'paid');
                }
                $data['status'] = 'success';

                $data['payment_status'] = json_encode($payment_status);
                $this->db->where('sale_id', $saldt['sale_id']);
                $this->db->update('sale', $data);


                $tot_rewards += $saldt['rewards'];
                //echo $this->db->last_query();
            }
            if ($this->session->userdata('user_login') == 'yes') {
                $rewardsts = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
                $rewardsts = $rewardsts['0'];

                $this->wallet_model->add_reward_balance($tot_rewards, $this->session->userdata('user_id'));
            }
            $info = json_decode($rewardsts['shipping_address'], true);
            $kaleyra_sms     =  $this->db->get_where('general_settings', array('general_settings_id' => '110'))->row()->kaleyra_sms_set;
            if ($kaleyra_sms == 'ok') {
                $mobile = $info['phone'];
                $body  = "'Your Order #" . $order_id . " placed successfully, Thanks";
                $template_id = 1607100000000112238;
                $sendotp = $this->crud_model->send_sms_kaleyria($mobile, $template_id, $body);
            }
            $twilio_sms     =  $this->db->get_where('general_settings', array('general_settings_id' => '110'))->row()->twilio_sms_set;
            if ($twilio_sms == 'ok') {
                $sid     =  $this->db->get_where('general_settings', array('general_settings_id' => '115'))->row()->t_account_sid;
                $token     =  $this->db->get_where('general_settings', array('general_settings_id' => '116'))->row()->t_auth_token;
                $messagingServiceSid     =  $this->db->get_where('general_settings', array('general_settings_id' => '117'))->row()->twilio_number;
                //  $sms="Your OTP: ".$otp."";
                $mobile = $info['phone'];
                $sms  = "'Your Order #" . $order_id . " placed successfully, Thanks";

                require_once(APPPATH . 'libraries/Twilio/Twilio.php');
                $ordersms = sendotp($sid, $token, $messagingServiceSid, $mobile, $sms);
            }
            // $this->crud_model->digital_to_customer($sale_id);
            $this->cart->destroy();
            //$this->crud_model->digital_to_customer($order_id);
            $this->crud_model->email_invoice($udf1);
            // $this->crud_model->email_invoice1($order_id);
            $this->cart->destroy();
            $this->session->set_userdata('couponer', '');
            //redirect(base_url() . 'index.php/home/invoice/' . $merchant_order_id, 'refresh');
            redirect(base_url() . 'index.php/home/invoice/total/' . $udf1, 'refresh');
        }
    }

    function pum_failure()
    {
        $sale_id = $this->session->userdata('sale_id');
        $this->db->where('sale_id', $sale_id);
        $this->db->delete('sale');
        $this->session->set_userdata('sale_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'home/cart_checkout/', 'refresh');
    }
    function razorfailed()
    {
        $sale_id = $this->session->userdata('sale_id');
        $this->db->where('sale_id', $sale_id);
        $this->db->delete('sale');
        $this->session->set_userdata('sale_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'home/cart_checkout/', 'refresh');
    }
    public function razorcallback()
    {
        //echo 1; exit;
        if (!empty($this->input->post('razorpay_payment_id')) && !empty($this->input->post('merchant_order_id'))) {
            $razorpay_payment_id = $this->input->post('razorpay_payment_id');
            $merchant_order_id = $this->input->post('merchant_order_id');
            $currency_code = 'INR';
            $amount = $this->input->post('merchant_total');
            $success = false;
            $error = '';
            $ch = $this->get_curl_handle($razorpay_payment_id, $amount);
            $result = $ch;
            $response_array = json_decode($result, true);
            //echo "<pre>";print_r($response_array);exit;
            //Check success response
            if ($response_array['status'] === 'captured' || $response_array['status'] === 'authorized') {
                $data['payment_details']   = json_encode($response_array);
                $carted  = $this->cart->contents();
                $total_invoice_id = $this->db->get_where('sale', array('order_id' => $merchant_order_id))->row()->total_invoice_id;
                $saleDet = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
                //  $saleDet=$saleDet[0];
                //$sale_id = $saleDet['sale_id'];
                //$user_id=$saleDet['buyer'];
                foreach ($carted as $value) {
                    $this->crud_model->decrease_quantity($value['id'], $value['qty']);
                    $data1['type']         = 'destroy';
                    $data1['category']     = $this->db->get_where('product', array(
                        'product_id' => $value['id']
                    ))->row()->category;
                    $data1['sub_category'] = $this->db->get_where('product', array(
                        'product_id' => $value['id']
                    ))->row()->sub_category;
                    $data1['product']      = $value['id'];
                    $data1['quantity']     = $value['qty'];
                    $data1['total']        = 0;
                    $data1['reason_note']  = 'sale';
                    $data1['sale_id']      = $merchant_order_id;
                    $data1['datetime']     = time();
                    $this->db->insert('stock', $data1);
                    /*$pro_price     = $this->db->get_where('product', array(
                        'product_id' => $value['id']
                    ))->row()->sale_price;
                    
                    if ($this->session->userdata('user_login') == 'yes') {
                            $data['rewards']=($pro_price*2)/100;
                            }*/
                }
                foreach ($saleDet as $saldt) {
                    //print_r($saldt);

                    $vendors = $this->crud_model->vendors_in_sale($saldt['sale_id']);
                    // print_r($vendors);
                    $payment_status = array();
                    foreach ($vendors as $p) {
                        $payment_status[] = array('vendor' => $p, 'status' => 'paid');
                    }
                    if ($this->crud_model->is_admin_in_sale($sale_id)) {
                        $payment_status[] = array('admin' => '', 'status' => 'paid');
                    }
                    $data['status'] = 'success';
                    $data['payment_status'] = json_encode($payment_status);
                    $this->db->where('sale_id', $saldt['sale_id']);
                    $this->db->update('sale', $data);


                    $tot_rewards += $saldt['rewards'];
                    //echo $this->db->last_query();
                }

                if ($this->session->userdata('user_login') == 'yes') {
                    $rewardsts = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
                    $rewardsts = $rewardsts['0'];

                    $this->wallet_model->add_reward_balance($tot_rewards, $this->session->userdata('user_id'));
                }
                // $this->crud_model->digital_to_customer($sale_id);
                $this->cart->destroy();
                //$this->crud_model->digital_to_customer($order_id);
                //echo $total_invoice_id; exit;
                $info = json_decode($rewardsts['shipping_address'], true);
                $kaleyra_sms     =  $this->db->get_where('general_settings', array('general_settings_id' => '110'))->row()->kaleyra_sms_set;
                if ($kaleyra_sms == 'ok') {
                    $mobile = $info['phone'];
                    $body  = "'Your Order #" . $order_id . " placed successfully, Thanks";
                    $template_id = 1607100000000112238;
                    $sendotp = $this->crud_model->send_sms_kaleyria($mobile, $template_id, $body);
                }
                $twilio_sms     =  $this->db->get_where('general_settings', array('general_settings_id' => '110'))->row()->twilio_sms_set;
                if ($twilio_sms == 'ok') {
                    $sid     =  $this->db->get_where('general_settings', array('general_settings_id' => '115'))->row()->t_account_sid;
                    $token     =  $this->db->get_where('general_settings', array('general_settings_id' => '116'))->row()->t_auth_token;
                    $messagingServiceSid     =  $this->db->get_where('general_settings', array('general_settings_id' => '117'))->row()->twilio_number;
                    //  $sms="Your OTP: ".$otp."";
                    $mobile = $info['phone'];
                    $sms  = "'Your Order #" . $order_id . " placed successfully, Thanks";

                    require_once(APPPATH . 'libraries/Twilio/Twilio.php');
                    $ordersms = sendotp($sid, $token, $messagingServiceSid, $mobile, $sms);
                }
                $this->crud_model->email_invoice($merchant_order_id);
                // $this->crud_model->email_invoice1($order_id);
                $this->cart->destroy();
                $this->session->set_userdata('couponer', '');
                //redirect(base_url() . 'index.php/home/invoice/' . $merchant_order_id, 'refresh');
                redirect(base_url() . 'index.php/home/invoice/total/' . $merchant_order_id, 'refresh');
            } else {
                redirect($this->input->post('merchant_furl_id'));
            }
        } else {
            echo 'An error occured. Contact site administrator, please!';
        }
    }

    private function get_curl_handle($payment_id, $amount)
    {
        //echo 1; exit;
        //cnpwX2xpdmVfbWMwZmV2Y3dUVTJCbXM6d1ZZdUVHMUpaV29SZ1JrTlJIa1Jnd2dv //live
        //cnpwX3Rlc3Rfdlp5YVkwZ1VvdmR5bEw6YlY1dTBVcHRKUnMyd09MWld2UVlYRUZW //test
        $url = 'https://api.razorpay.com/v1/payments/' . $payment_id . '/capture';
        $key_id = RAZOR_KEY_ID;
        $key_secret = RAZOR_KEY_SECRET;
        $fields_string = '{
            "amount": ' . $amount . ',
            "currency": "INR"
        }'; //exit;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields_string,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic cnpwX3Rlc3Rfdlp5YVkwZ1VvdmR5bEw6YlY1dTBVcHRKUnMyd09MWld2UVlYRUZW',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    function twocheckout_success()
    {
        //$this->twocheckout_lib->set_acct_info('532001', 'tango', 'Y');
        $c2_user = $this->db->get_where('business_settings', array('type' => 'c2_user'))->row()->value;
        $c2_secret = $this->db->get_where('business_settings', array('type' => 'c2_secret'))->row()->value;

        $this->twocheckout_lib->set_acct_info($c2_user, $c2_secret, 'Y');
        $data2['response'] = $this->twocheckout_lib->validate_response();
        $status = $data2['response']['status'];
        if ($status == 'pass') {
            $sale_id = $this->session->userdata('sale_id');
            $data1['payment_details']   = json_encode($this->twocheckout_lib->validate_response());
            $data1['payment_timestamp'] = strtotime(date("m/d/Y"));
            $data1['payment_type']      = 'c2';
            $vendors = $this->crud_model->vendors_in_sale($sale_id);
            $payment_status = array();
            foreach ($vendors as $p) {
                $payment_status[] = array('vendor' => $p, 'status' => 'paid');
            }
            if ($this->crud_model->is_admin_in_sale($sale_id)) {
                $payment_status[] = array('admin' => '', 'status' => 'paid');
            }
            $data1['payment_status'] = json_encode($payment_status);
            $this->db->where('sale_id', $sale_id);
            $this->db->update('sale', $data1);


            $carted  = $this->cart->contents();
            $sale_id = $this->session->userdata('sale_id');
            foreach ($carted as $value) {
                $this->crud_model->decrease_quantity($value['id'], $value['qty']);
                $data1['type']         = 'destroy';
                $data1['category']     = $this->db->get_where('product', array(
                    'product_id' => $value['id']
                ))->row()->category;
                $data1['sub_category'] = $this->db->get_where('product', array(
                    'product_id' => $value['id']
                ))->row()->sub_category;
                $data1['product']      = $value['id'];
                $data1['quantity']     = $value['qty'];
                $data1['total']        = 0;
                $data1['reason_note']  = 'sale';
                $data1['sale_id']      = $sale_id;
                $data1['datetime']     = time();
                $this->db->insert('stock', $data1);
            }
            $this->crud_model->digital_to_customer($sale_id);
            $this->cart->destroy();
            $this->session->set_userdata('couponer', '');
            $this->crud_model->email_invoice($sale_id);
            $this->session->set_userdata('sale_id', '');
            redirect(base_url() . 'index.php/home/invoice/' . $sale_id, 'refresh');
        } else {
            //var_dump($data2['response']);
            $sale_id = $this->session->userdata('sale_id');
            $this->db->where('sale_id', $sale_id);
            $this->db->delete('sale');
            $this->session->set_userdata('sale_id', '');
            $this->session->set_flashdata('alert', 'payment_cancel');
            redirect(base_url() . 'index.php/home/cart_checkout/', 'refresh');
        }
    }
    /* FUNCTION: Verify vouguepay payment by IPN*/
    function vouguepay_ipn()
    {
        $res = $this->vouguepay->validate_ipn();
        $sale_id = $res['merchant_ref'];
        $merchant_id = 'demo';
        if ($res['total'] !== 0 && $res['status'] == 'Approved' && $res['merchant_id'] == $merchant_id) {
            $data['payment_details']   = json_encode($res);
            $data['payment_timestamp'] = strtotime(date("m/d/Y"));
            $data['payment_type']      = 'vouguepay';

            $vendors = $this->crud_model->vendors_in_sale($sale_id);
            $payment_status = array();
            foreach ($vendors as $p) {
                $payment_status[] = array('vendor' => $p, 'status' => 'paid');
            }
            if ($this->crud_model->is_admin_in_sale($sale_id)) {
                $payment_status[] = array('admin' => '', 'status' => 'paid');
            }
            $data['payment_status'] = json_encode($payment_status);
            $this->db->where('sale_id', $sale_id);
            $this->db->update('sale', $data);
        }
    }

    /* FUNCTION: Loads after cancelling vouguepay*/
    function vouguepay_cancel()
    {
        $sale_id = $this->session->userdata('sale_id');
        $this->db->where('sale_id', $sale_id);
        $this->db->delete('sale');
        $this->session->set_userdata('sale_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'index.php/home/cart_checkout/', 'refresh');
    }

    /* FUNCTION: Loads after successful vouguepay payment*/
    function vouguepay_success()
    {
        $carted  = $this->cart->contents();
        $sale_id = $this->session->userdata('sale_id');
        foreach ($carted as $value) {
            $size = $this->crud_model->is_added_to_cart($value['id'], 'option', 'choice_0');
            $this->crud_model->decrease_quantity($value['id'], $value['qty'], $size);
            $data1['type']         = 'destroy';
            $data1['category']     = $this->db->get_where('product', array(
                'product_id' => $value['id']
            ))->row()->category;
            $data1['sub_category'] = $this->db->get_where('product', array(
                'product_id' => $value['id']
            ))->row()->sub_category;
            $data1['product']      = $value['id'];
            $data1['quantity']     = $value['qty'];
            $data1['total']        = 0;
            $data1['reason_note']  = 'sale';
            $data1['size']         = $size;
            $data1['sale_id']      = $sale_id;
            $data1['datetime']     = time();
            $this->db->insert('stock', $data1);
        }
        $this->crud_model->digital_to_customer($sale_id);
        $this->cart->destroy();
        $this->session->set_userdata('couponer', '');
        $this->crud_model->email_invoice($sale_id);
        $this->session->set_userdata('sale_id', '');
        redirect(base_url() . 'index.php/home/invoice/' . $sale_id, 'refresh');
    }
    /* FUNCTION: Concerning wishlist*/
    function wishlist($para1 = "", $para2 = "")
    {
        if ($para1 == 'add') {
            $this->crud_model->add_wish($para2);
        } else if ($para1 == 'remove') {
            $this->crud_model->remove_wish($para2);
        } else if ($para1 == 'num') {
            echo $this->crud_model->wished_num();
        }
    }






    /* FUNCTION: Loads Contact Page */
    function blog($para1 = "")
    {
        $page_data['category'] = $para1;
        $page_data['page_name']   = 'blog';
        $page_data['asset_page']  = 'blog';
        $page_data['page_title']  = translate('blog');
        $this->load->view('front/index', $page_data);
    }

    /* FUNCTION: Loads Contact Page */
    function blog_by_cat($para1 = "")
    {
        $page_data['category'] = $para1;
        $this->load->view('front/blog/blog_list', $page_data);
    }

    function ajax_blog_list($para1 = "")
    {
        $this->load->library('Ajax_pagination');

        $category_id = $this->input->post('blog_category');
        if ($category_id !== '' && $category_id !== 'all') {
            $this->db->where('blog_category', $category_id);
        }

        // pagination
        $config['total_rows'] = $this->db->count_all_results('blog');
        $config['base_url']   = base_url() . 'index.php?home/listed/';
        $config['per_page'] = 3;
        $config['uri_segment']  = 5;
        $config['cur_page_giv'] = $para1;

        $function                  = "filter_blog('0')";
        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr                       = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start               = floor($rr) * $config['per_page'];
        $function                 = "filter_blog('" . $last_start . "')";
        $config['last_link']      = '&raquo;';
        $config['last_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function                 = "filter_blog('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function                 = "filter_blog('" . ($para1 + $config['per_page']) . "')";
        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open']  = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open']  = '<li class="active"><a>';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function                = "filter_blog(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open']  = '<li><a onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);

        $this->db->order_by('blog_id', 'desc');
        if ($category_id !== '' && $category_id !== 'all') {
            $this->db->where('blog_category', $category_id);
        }

        $page_data['blogs'] = $this->db->get('blog', $config['per_page'], $para1)->result_array();
        if ($category_id !== '' && $category_id !== 'all') {
            $category = $this->crud_model->get_type_name_by_id('blog_category', $category_id, 'name');
        } else {
            $category = translate('all_blogs');
        }
        $page_data['category_name']      = $category;
        $page_data['count']              = $config['total_rows'];

        $this->load->view('front/blog/ajax_list', $page_data);
    }

    /* FUNCTION: Loads Contact Page */
    function blog_view($para1 = "")
    {
        $page_data['blog']  = $this->db->get_where('blog', array('blog_id' => $para1))->result_array();
        $page_data['categories']  = $this->db->get('blog_category')->result_array();

        $this->db->where('blog_id', $para1);
        $this->db->update('blog', array(
            'number_of_view' => 'number_of_view' + 1
        ));
        $page_data['page_name']  = 'blog/blog_view';
        $page_data['asset_page']  = 'blog_view';
        $page_data['page_title']  = $this->db->get_where('blog', array('blog_id' => $para1))->row()->title;
        $this->load->view('front/index.php', $page_data);
    }

    function others_product($para1 = "",$para2 ="",$para3 ="",$para4 ="",$para5 ="",$para6 ="",$para7="")
    {
        if($para2=="") {
        $page_data['product_type'] = $para1;
        $page_data['page_name']   = 'others_list';
        $page_data['asset_page']  = 'product_list_other';
        $page_data['page_title']  = translate($para1);
        $this->load->view('front/index', $page_data);
       }
       else
       {
        $page_data['product_type'] = $para1;
        $page_data['category'] =$para2;
        $page_data['sub_category'] =$para3;
        $page_data['brand'] =$para4;
        $page_data['min'] =$para5;
        $page_data['max'] =$para6;
        $page_data['query'] =$para7;
        $page_data['page_name']   = 'others_list';
        $page_data['asset_page']  = 'product_list_other';
        $page_data['page_title']  = translate($para1);
        $this->load->view('front/index', $page_data);
       }
    }
    function product_by_type($para1 = "",$para2 ="",$para3 ="",$para4 ="",$para5 ="",$para6 ="",$para7="")
    {
        if($para2=="")
        {
        $page_data['product_type'] = $para1;
        $this->load->view('front/others_list/view', $page_data);
        }
        else
        {
            $page_data['product_type'] = $para1;
            $page_data['category'] =$para2;
            $page_data['sub_category'] =$para3;
            $page_data['brand'] =$para4;
            $page_data['min'] =$para5;
            $page_data['max'] =$para6;
            $page_data['query'] =$para7;
            $this->load->view('front/others_list/view', $page_data);
        }
    }


    function bid_product($para1 = "")
    {
        $page_data['product_type'] = $para1;
        $page_data['page_name']   = 'bid_list';
        $page_data['asset_page']  = 'product_list_other';
        $page_data['page_title']  = translate($para1);
        $this->load->view('front/index', $page_data);
    }
    function bid_by_type($para1 = "")
    {
        $page_data['product_type'] = $para1;
        $this->load->view('front/bid_list/view', $page_data);
    }

    /* FUNCTION: Concerning wishlist*/
    function chat($para1 = "", $para2 = "")
    {
    }

    /* FUNCTION: Check if Customer is logged in*/
    function check_login($para1 = "")
    {
        if ($para1 == 'state') {
            if ($this->session->userdata('user_login') == 'yes') {
                echo 'hypass';
            }
            if ($this->session->userdata('user_login') !== 'yes') {
                echo 'nypose';
            }
        } else if ($para1 == 'id') {
            echo $this->session->userdata('user_id');
        } else {
            echo $this->crud_model->get_type_name_by_id('user', $this->session->userdata('user_id'), $para1);
        }
    }
    /* FUNCTION: Invoice showing*/
    function invoice($para1 = "", $para2 = "", $para3 = "")
    {

        if (
            $this->session->userdata('user_login') != "yes"
            || $this->crud_model->get_type_name_by_id('sale', $para1, 'buyer') !==  $this->session->userdata('user_id')
        )

            //if ($this->session->userdata('user_login') != "yes")
            //    {
            //      redirect(base_url(), 'refresh');
            //    }
            if ($para1 == "total") {
                if(($para3 == "ipay88_success") && isset($_SESSION['p']['ipay88_success'])){
                    $page_data['ipay88_success'] = $_SESSION['p']['ipay88_success'];
                    unset($_SESSION['p']['ipay88_success']);
                }
                $page_data['order_id']    = $para2;
                $page_data['asset_page']    = "invoice";
                $page_data['page_name']  = "shopping_cart/invoice";
                $this->load->view('front/index', $page_data);
            } else {
                $page_data['sale_id']    = $para1;
                $page_data['asset_page']    = "invoice";
                $page_data['page_name']  = "shopping_cart/invoice";
                $page_data['page_title'] = translate('invoice');
                if ($para2 == 'email') {
                    $this->load->view('front/shopping_cart/invoice_email', $page_data);
                } else {
                    $this->load->view('front/index', $page_data);
                }
            }
        $unique_no =  $this->session->userdata('unique_no');
        $data1['payment_status'] = 1;
        $this->db->where('unique_no', $unique_no);
        $this->db->update('bidding_history', $data1);
    }
    function invoice_details($para1 = "", $para2 = "")
    {

        //if ($this->session->userdata('user_login') != "yes"
        //             || $this->crud_model->get_type_name_by_id('sale', $para1, 'buyer') !==  $this->session->userdata('user_id'))



        $page_data['order_id']    = $para1;
        $page_data['sale_id']    = $para2;
        $page_data['asset_page']    = "invoice_details";
        $page_data['page_name']  = "user/invoice_details";
        $this->load->view('front/index', $page_data);
    }
    function reviews($para1 = "", $para2 = "", $para3 = "")
    {

        if ($this->session->userdata('user_login') != "yes") {
            redirect(base_url(), 'refresh');
        }

        if ($para1 == "add") {

            $data['product_id'] = $para2;
            $data['user_id'] = $this->session->userdata('user_id');
            $data['title'] = $this->input->post('title');
            $data['rating'] = $this->input->post('rating');
            $data['description'] = $this->input->post('description');
            $data['status'] = 1;
            $data['order_id'] = $this->crud_model->get_type_name_by_id('sale', $para3, 'order_id');
            $data['sale_id'] = $para3;
            $data['date'] = time();
            $this->db->insert('review_product', $data);
            $datas['review'] = 1;
            $this->db->where('sale_id', $para3);
            $this->db->update('sale', $datas);
            echo "done";
        } else {


            $page_data['sale_id']    = $para1;
            $page_data['product_id']    = $para2;
            $page_data['asset_page']    = "review";
            $page_data['page_name']  = "user/review";
            $this->load->view('front/index', $page_data);
        }
    }
    function cancel_details($para1 = "", $para2 = "", $para3 = "")
    {

        if ($this->session->userdata('user_login') != "yes") {
            redirect(base_url(), 'refresh');
        }
        if ($para1 == 'cancel') {
            $sale_id = $para2;
            $product_id = $para3;
            $data['cancel_status'] = 1;
            $data['cancel_reason'] = 'user_cancel';
            $data['cancel_remarks'] = $this->input->post('message');
            $data['status'] = 'cancelled';
            //$data['order_trackment'] = 2;
            $price_sale = $this->db->get_where('sale', array(
                'sale_id' => $sale_id
            ))->result_array();
            $d_rewards = $price_sale[0]['grand_total'];
            $user_id = $price_sale[0]['buyer'];
            $user_rewards = $price_sale[0]['rewards'];


            $tot_rwds = $this->db->get_where('user', array(
                'user_id', $user_id
            ))->row()->rewards;
            $fin_rwds = $tot_rwds - $user_rewards;
            $data1['rewards'] = $fin_rwds + $d_rewards;

            $this->db->where('user_id', $user_id);

            $this->db->update('user', $data1);

            $data['refund_status'] = '1';
            $data['refund_date'] = time();
            $data['rewards'] = $d_rewards;



            $this->db->where('sale_id', $sale_id);
            $this->db->update('sale', $data);
            $this->crud_model->increase_quantity($product_id, 1);
            $data1['type']         = 'add';
            $data1['category']     = $this->db->get_where('product', array(
                'product_id' => $product_id
            ))->row()->category;
            $data1['sub_category'] = $this->db->get_where('product', array(
                'product_id' => $product_id
            ))->row()->sub_category;
            $data1['product']      = $product_id;
            $data1['quantity']     = 1;
            $data1['total']        = 0;
            $data1['reason_note']  = 'cancel_by_user';
            $data1['sale_id']      = $sale_id;
            $data1['datetime']     = time();
            $this->db->insert('stock', $data1);

            echo 'done';
        } else {


            $page_data['sale_id']    = $para1;
            $page_data['product_id']    = $para2;
            $page_data['asset_page']    = "cancel_details";
            $page_data['page_name']  = "user/cancel_details";
            $this->load->view('front/index', $page_data);
        }
    }
    function return_details($para1 = "", $para2 = "", $para3 = "")
    {

        if ($this->session->userdata('user_login') != "yes") {
            redirect(base_url(), 'refresh');
        }
        if ($para1 == 'return') {
            $sale_id = $para2;
            $product_id = $para3;
            $data['return_status'] = 1;
            $data['return_reason'] = $this->input->post('return_reason');
            $data['return_remarks'] = $this->input->post('message');
            $data['return_action'] = $this->input->post('return_action');
            $data['order_trackment'] = 1;
            $this->db->where('sale_id', $sale_id);
            $this->db->update('sale', $data);
            echo 'done';
        } else {


            $page_data['sale_id']    = $para1;
            $page_data['product_id']    = $para2;
            $page_data['asset_page']    = "return_details";
            $page_data['page_name']  = "user/return_details";
            $this->load->view('front/index', $page_data);
        }
    }
    /* FUNCTION: Legal pages load - terms & conditions / privacy policy*/
    function legal($type = "")
    {
        $page_data['type']       = $type;
        $page_data['page_name']  = "others/legal";
        $page_data['asset_page']    = "legal";
        $page_data['page_title'] = translate($type);
        // echo $page_data; exit;
        $this->load->view('front/index', $page_data);
    }


    /* FUNCTION: Price Range Load by AJAX*/
    function get_ranger($by = "", $id = "", $start = '', $end = '')
    {
        $min = $this->get_range_lvl($by, $id, "min");
        $max = $this->get_range_lvl($by, $id, "max");
        if ($start == '') {
            $start = $min;
        }
        if ($end == '') {
            $end = $max;
        }

        $return = '' . '<input type="text" id="rangelvl" value="" name="range" />' . '<script>' . ' $("#rangelvl").ionRangeSlider({' . '        hide_min_max: false,' . '       keyboard: true,' . '        min:' . $min . ',' . '      max:' . $max . ',' . '      from:' . $start . ',' . '       to:' . $end . ',' . '       type: "double",' . '        step: 1,' . '       prefix: "' . currency() . '",' . '      grid: true,' . '        onFinish: function (data) {' . "            filter('click','none','none','0');" . '     }' . '  });' . '</script>';
        return $return;
    }

    /* FUNCTION: Price Range Load by AJAX*/
    function get_range_lvl($by = "", $id = "", $type = "")
    {
        if ($type == "min") {
            $set = 'asc';
        } elseif ($type == "max") {
            $set = 'desc';
        }
        $this->db->limit(1);
        $this->db->order_by('sale_price', $set);
        if (count($a = $this->db->get_where('product', array(
            $by => $id
        ))->result_array()) > 0) {
            foreach ($a as $r) {
                return $r['sale_price'];
            }
        } else {
            return 0;
        }
    }

    /* FUNCTION: AJAX loadable scripts*/
    function others($para1 = "", $para2 = "", $para3 = "", $para4 = "")
    {
        if ($para1 == "get_sub_by_cat") {
            $return = '';
            $subs   = $this->db->get_where('sub_category', array(
                'category' => $para2
            ))->result_array();
            foreach ($subs as $row) {
                $return .= '<option  value="' . $row['sub_category_id'] . '">' . ucfirst($row['sub_category_name']) . '</option>' . "\n\r";
            }
            echo $return;
        } else if ($para1 == "get_range_by_cat") {
            if ($para2 == 0) {
                echo $this->get_ranger("product_id !=", "", $para3, $para4);
            } else {
                echo $this->get_ranger("category", $para2, $para3, $para4);
            }
        } else if ($para1 == "get_range_by_sub") {
            echo $this->get_ranger("sub_category", $para2);
        } else if ($para1 == 'text_db') {
            echo $this->db->set_update('front/index', $para2);
        } else if ($para1 == "get_home_range_by_cat") {
            echo round($this->get_range_lvl("category", $para2, "min"));
            echo '-';
            echo round($this->get_range_lvl("category", $para2, "max"));
        } else if ($para1 == "get_home_range_by_sub") {
            echo round($this->get_range_lvl("sub_category", $para2, "min"));
            echo '-';
            echo round($this->get_range_lvl("sub_category", $para2, "max"));
        }
    }

    //SITEMAP
    function sitemap()
    {
        header("Content-type: text/xml");
        $otherurls = array(
            base_url() . 'index.php/home/contact/',
            base_url() . 'index.php/home/legal/terms_conditions',
            base_url() . 'index.php/home/legal/privacy_policy'
        );
        $producturls = array();
        $products = $this->db->get_where('product', array('status' => 'ok'))->result_array();
        foreach ($products as $row) {
            $producturls[] = $this->crud_model->product_link($row['product_id']);
        }
        $vendorurls = array();
        $vendors = $this->db->get_where('vendor', array('status' => 'approved'))->result_array();
        foreach ($vendors as $row) {
            $vendorurls[] = $this->crud_model->vendor_link($row['vendor_id']);
        }
        $page_data['otherurls']  = $otherurls;
        $page_data['producturls']  = $producturls;
        $page_data['vendorurls']  = $vendorurls;
        $this->load->view('front/others/sitemap', $page_data);
    }

    function new_shop()
    {
        $page_data['page_name']        = "others/new_shop";
        $page_data['asset_page']       = "new_shop";
        $page_data['page_title']       = translate('new_shop');
        $this->load->view('front/index', $page_data);
    }
    function search_new()
    {
        $vendorid_get = $this->session->userdata('vendorid');
        if($vendorid_get == ""){
            
            $vendorid_get ="2";
          
        }
        $term = $this->input->get('term');
        $this->db->limit(10);
        $this->db->where('store_id',$vendorid_get);
        $this->db->where('status','ok');
        $this->db->like('title', $term);
        $data = $this->db->get("product")->result();
        echo json_encode($data);
    }
    function bundled_product()
    {
        $page_data['product_type'] = "";
        $page_data['page_name'] = 'bundled_product';
        $page_data['asset_page'] = 'product_list_other';
        $page_data['page_title'] = translate('bundled_product');
        $this->load->view('front/index', $page_data);
    }
    function product_by_bundle()
    {
        $this->load->view('front/bundled_product/view', $page_data);
    }
    function ajax_bundled_product($para1 = "")
    {
        $this->load->library('Ajax_pagination');

        $this->db->where('is_bundle', 'yes');
        $this->db->where('status', 'ok');

        // pagination
        $config['total_rows'] = $this->db->count_all_results('product');
        $config['base_url'] = base_url() . 'index.php?home/listed/';
        $config['per_page'] = 12;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para1;

        $function = "filter_others('0')";
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li><a onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start = floor($rr) * $config['per_page'];
        $function = "filter_others('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li><a onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function = "filter_others('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li><a onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function = "filter_others('" . ($para1 + $config['per_page']) . "')";
        $config['next_link'] = '&rsaquo;';
        $config['next_tag_open'] = '<li><a onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function = "filter_others(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li><a onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);


        $this->db->order_by('product_id', 'desc');
        $this->db->where('status', 'ok');
        $this->db->where('is_bundle', 'yes');

        $page_data['products'] = $this->db->get('product', $config['per_page'], $para1)->result_array();
        $page_data['count'] = $config['total_rows'];
        $page_data['page_type'] = $type;

        $this->load->view('front/bundled_product/listed', $page_data);
    }
    function customer_products($para1 = "")
    {
        if ($this->crud_model->get_type_name_by_id('general_settings', '83', 'value') == 'ok') {
            if ($para1 == "search") {

                $page_data['product_type'] = "";
                $page_data['category'] = $this->input->post('category');
                $page_data['title'] = $this->input->post('title');
                $page_data['brand'] = $this->input->post('brand');
                $page_data['sub_category'] = $this->input->post('sub_category');
                $page_data['condition'] = $this->input->post('condition');
                $page_data['page_name'] = 'customer_products';
                $page_data['asset_page'] = 'product_list_other';
                $page_data['page_title'] = translate('customer_products');
                $this->load->view('front/index', $page_data);
            } else {
                $page_data['product_type'] = "";
                $page_data['category'] = 0;
                $page_data['sub_category'] = 0;
                $page_data['title'] = "";
                $page_data['condition'] = "all";
                $page_data['brand'] = "";
                $page_data['page_name'] = 'customer_products';
                $page_data['asset_page'] = 'product_list_other';
                $page_data['page_title'] = translate('customer_products');
                $this->load->view('front/index', $page_data);
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    function product_by_customer($cat, $sub, $brand, $title, $condition)
    {
        $page_data['cat'] = $cat;
        $page_data['sub'] = $sub;
        $page_data['condition'] = $condition;
        $page_data['title'] = $title;
        $page_data['brand'] = $brand;
        $this->load->view('front/customer_products/view', $page_data);
    }
    function ajax_customer_products($para1 = "")
    {
        $this->load->library('Ajax_pagination');

        $this->db->where('is_sold', 'no');
        $this->db->where('status', 'ok');
        $this->db->where('admin_status', 'ok');

        if ($this->input->post('category') != 0) {
            $this->db->where('category', $this->input->post('category'));
        }

        if ($this->input->post('sub_category') != 0) {
            $this->db->where('sub_category', $this->input->post('sub_category'));
        }
        if ($this->input->post('condition') != 'all') {
            $this->db->where('prod_condition', $this->input->post('condition'));
        }
        if ($this->input->post('title') != '0') {
            $this->db->like('title', $this->input->post('title'), 'both');
        }
        if ($this->input->post('brand') != '0') {
            $this->db->like('brand', $this->input->post('brand'), 'both');
        }
        // pagination
        $config['total_rows'] = $this->db->count_all_results('customer_product');
        $config['base_url'] = base_url() . 'index.php?home/listed/';
        $config['per_page'] = 12;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para1;

        $function = "filter_others('0')";
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li><a onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start = floor($rr) * $config['per_page'];
        $function = "filter_others('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li><a onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';
        $function = "filter_others('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li><a onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function = "filter_others('" . ($para1 + $config['per_page']) . "')";
        $config['next_link'] = '&rsaquo;';
        $config['next_tag_open'] = '<li><a onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function = "filter_others(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li><a onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);


        $this->db->where('is_sold', 'no');
        $this->db->where('status', 'ok');
        $this->db->where('admin_status', 'ok');
        if ($this->input->post('category') != 0) {
            $this->db->where('category', $this->input->post('category'));
        }
        if ($this->input->post('sub_category') != 0) {
            $this->db->where('sub_category', $this->input->post('sub_category'));
        }
        if ($this->input->post('condition') != 'all') {
            $this->db->where('prod_condition', $this->input->post('condition'));
        }
        if ($this->input->post('title') != '0') {
            $this->db->like('title', $this->input->post('title'), 'both');
        }
        if ($this->input->post('brand') != '0') {
            $this->db->like('brand', $this->input->post('brand'), 'both');
        }
        $page_data['customer_products'] = $this->db->get('customer_product', $config['per_page'], $para1)->result_array();
        //print_r($page_data['customer_products']); exit;
        $page_data['count'] = $config['total_rows'];
        $page_data['page_type'] = $type;
        $this->load->view('front/customer_products/listed', $page_data);
    }
    function customer_product_view($para1 = "", $para2 = "")
    {
        if ($this->crud_model->get_type_name_by_id('general_settings', '83', 'value') == 'ok') {

            $product_data = $this->db->get_where('customer_product', array('customer_product_id' => $para1, 'status' => 'ok', 'is_sold' => 'no'));

            $this->db->where('customer_product_id', $para1);
            $this->db->update('customer_product', array(
                'number_of_view' => $product_data->row()->number_of_view + 1,
                'last_viewed' => time()
            ));

            $type = 'other';

            $page_data['product_details'] = $this->db->get_where('customer_product', array('customer_product_id' => $para1, 'status' => 'ok', 'is_sold' => 'no'))->result_array();
            $page_data['page_name'] = "customer_product_view/" . $type . "/page_view";
            $page_data['asset_page'] = "product_view_" . $type; //there is no asset page for customer product view
            $page_data['product_data'] = $product_data->result_array();
            $page_data['page_title'] = !empty($product_data->row()->seo_title) ? $product_data->row()->seo_title : $product_data->row()->title;;
            $page_data['page_description'] = !empty($product_data->row()->seo_description) ? $product_data->row()->seo_description : '';
            $page_data['product_tags'] = $product_data->row()->tag;

            $this->load->view('front/index', $page_data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    function quick_view_cp($para1 = "")
    {
        $product_data = $this->db->get_where('customer_product', array(
            'customer_product_id' => $para1,
            'status' => 'ok'
        ));

        $type = 'other';

        $page_data['product_details'] = $product_data->result_array();
        $page_data['page_title'] = !empty($product_data->row()->seo_title) ? $product_data->row()->seo_title : $product_data->row()->title;;
        $page_data['page_description'] = !empty($product_data->row()->seo_description) ? $product_data->row()->seo_description : '';
        $page_data['product_tags'] = $product_data->row()->tag;

        $this->load->view('front/customer_product_view/' . $type . '/quick_view/index', $page_data);
    }
    function premium_package($para1 = "", $para2 = "")
    {
        if ($this->crud_model->get_type_name_by_id('general_settings', '83', 'value') == 'ok') {
            if ($para1 == '') {
                $page_data['page_name'] = "premium_package";
                $page_data['asset_page'] = "legal";
                $page_data['page_title'] = translate('premium_packages');
                $this->load->view('front/index', $page_data);
            } elseif ($para1 == 'purchase') {
                if ($this->session->userdata('user_login') == "yes") {
                    $page_data['page_name'] = "premium_package/purchase";
                    $page_data['asset_page'] = "legal";
                    $page_data['page_title'] = translate('premium_packages');
                    $page_data['package_id'] = $para2;

                    $page_data['selected_plan'] = $this->db->get_where('package', array('package_id' => $para2))->result();

                    $this->load->view('front/index', $page_data);
                } else {
                    redirect(base_url('index.php/home/login_set/login'), 'refresh');
                }
            } elseif ($para1 == 'do_purchase') {
                if ($this->session->userdata('user_login') != "yes") {
                    redirect(base_url() . 'index.php/home/login_set/login', 'refresh');
                }

                if ($this->input->post('payment_type') == 'paypal') {

                    $user_id = $this->session->userdata('user_id');
                    $payment_type = $this->input->post('payment_type');
                    $package_id = $this->input->post('package_id');
                    $amount = $this->db->get_where('package', array('package_id' => $package_id))->row()->amount;
                    $package_name = $this->db->get_where('package', array('package_id' => $package_id))->row()->name;

                    $data['package_id'] = $package_id;
                    $data['user_id'] = $user_id;
                    $data['payment_type'] = 'Paypal';
                    $data['payment_status'] = 'due';
                    $data['payment_details'] = 'none';
                    $data['amount'] = $amount;
                    $data['purchase_datetime'] = time();

                    $this->db->insert('package_payment', $data);
                    $payment_id = $this->db->insert_id();
                    $paypal_email = $this->db->get_where('business_settings', array('type' => 'paypal_email'))->row()->value;

                    $data['payment_code'] = date('Ym', $data['purchase_datetime']) . $payment_id;

                    $this->session->set_userdata('payment_id', $payment_id);

                    /****TRANSFERRING USER TO PAYPAL TERMINAL****/
                    $this->paypal->add_field('rm', 2);
                    $this->paypal->add_field('cmd', '_xclick');
                    $this->paypal->add_field('business', $paypal_email);
                    $this->paypal->add_field('item_name', $package_name);
                    $this->paypal->add_field('amount', $amount);
                    $this->paypal->add_field('currency_code', 'USD');
                    $this->paypal->add_field('custom', $payment_id);

                    $this->paypal->add_field('notify_url', base_url() . 'index.php/home/cus_paypal_ipn');
                    $this->paypal->add_field('cancel_return', base_url() . 'index.php/home/cus_paypal_cancel');
                    $this->paypal->add_field('return', base_url() . 'index.php/home/cus_paypal_success');

                    // submit the fields to paypal
                    $this->paypal->submit_paypal_post();
                } else if ($this->input->post('payment_type') == 'stripe') {
                    if ($this->input->post('stripeToken')) {
                        $user_id = $this->session->userdata('user_id');
                        $payment_type = $this->input->post('payment_type');
                        $package_id = $this->input->post('package_id');
                        $amount = $this->db->get_where('package', array('package_id' => $package_id))->row()->amount;

                        $stripe_api_key = $this->db->get_where('business_settings', array('type' => 'stripe_secret'))->row()->value;

                        require_once(APPPATH . 'libraries/stripe-php/init.php');
                        \Stripe\Stripe::setApiKey($stripe_api_key); //system payment settings
                        $user_email = $this->db->get_where('user', array('user_id' => $user_id))->row()->email;

                        $user = \Stripe\Customer::create(array(
                            'email' => $user_email, // member email id
                            'card' => $_POST['stripeToken']
                        ));

                        $charge = \Stripe\Charge::create(array(
                            'customer' => $user->id,
                            'amount' => ceil($amount * 100),
                            'currency' => 'USD'
                        ));
                        if ($charge->paid == true) {
                            $user = (array)$user;
                            $charge = (array)$charge;

                            $data['package_id'] = $package_id;
                            $data['user_id'] = $user_id;
                            $data['payment_type'] = 'Stripe';
                            $data['payment_status'] = 'paid';
                            $data['payment_details'] = "User Info: \n" . json_encode($user, true) . "\n \n Charge Info: \n" . json_encode($charge, true);
                            $data['amount'] = $amount;
                            $data['purchase_datetime'] = time();
                            $data['expire'] = 'no';

                            $this->db->insert('package_payment', $data);
                            $payment_id = $this->db->insert_id();

                            $data1['payment_code'] = date('Ym', $data['purchase_datetime']) . $payment_id;
                            $data1['payment_timestamp'] = time();

                            $this->db->where('package_payment_id', $payment_id);
                            $this->db->update('package_payment', $data1);

                            $payment = $this->db->get_where('package_payment', array('package_payment_id' => $payment_id))->row();

                            $prev_product_upload = $this->db->get_where('user', array('user_id' => $payment->user_id))->row()->product_upload;

                            $data2['product_upload'] = $prev_product_upload + $this->db->get_where('package', array('package_id' => $payment->package_id))->row()->upload_amount;

                            $package_info[] = array(
                                'current_package' => $this->crud_model->get_type_name_by_id('package', $payment->package_id),
                                'package_price' => $this->crud_model->get_type_name_by_id('package', $payment->package_id, 'amount'),
                                'payment_type' => $data['payment_type'],
                            );
                            $data2['package_info'] = json_encode($package_info);

                            $this->db->where('user_id', $payment->user_id);
                            $this->db->update('user', $data2);
                            recache();

                            /*if ($this->email_model->subscruption_email('member', $payment->member_id, $payment->package_id)) {
                                //$this->session->set_flashdata('alert', 'email_sent');
                            } else {
                                $this->session->set_flashdata('alert', 'not_sent');
                            }

                            $this->session->set_flashdata('alert', 'stripe_success');
                            redirect(base_url() . 'home/invoice/'.$payment->package_payment_id, 'refresh');*/

                            redirect(base_url() . 'index.php/home/profile/part/payment_info', 'refresh');
                        } else {
                            $this->session->set_flashdata('alert', 'stripe_failed');
                            redirect(base_url() . 'index.php/home/premium_package', 'refresh');
                        }
                    } else {
                        $package_id = $this->input->post('package_id');
                        redirect(base_url() . 'index.php/home/premium_package/purchase/' . $package_id, 'refresh');
                    }
                } else if ($this->input->post('payment_type') == 'wallet') {
                    $balance = $this->wallet_model->user_balance();
                    $user_id = $this->session->userdata('user_id');
                    $payment_type = $this->input->post('payment_type');
                    $package_id = $this->input->post('package_id');
                    $amount = $this->db->get_where('package', array('package_id' => $package_id))->row()->amount;

                    if ($balance >= $amount) {
                        $data['package_id'] = $package_id;
                        $data['user_id'] = $user_id;
                        $data['payment_type'] = 'Wallet';
                        $data['payment_status'] = 'paid';
                        $data['payment_details'] = '';
                        $data['amount'] = $amount;
                        $data['purchase_datetime'] = time();
                        $data['expire'] = 'no';

                        $this->db->insert('package_payment', $data);
                        $payment_id = $this->db->insert_id();

                        $data1['payment_code'] = date('Ym', $data['purchase_datetime']) . $payment_id;
                        $data1['payment_timestamp'] = time();

                        $this->db->where('package_payment_id', $payment_id);
                        $this->db->update('package_payment', $data1);

                        $payment = $this->db->get_where('package_payment', array('package_payment_id' => $payment_id))->row();

                        $prev_product_upload = $this->db->get_where('user', array('user_id' => $payment->user_id))->row()->product_upload;

                        $data2['product_upload'] = $prev_product_upload + $this->db->get_where('package', array('package_id' => $payment->package_id))->row()->upload_amount;

                        $package_info[] = array(
                            'current_package' => $this->crud_model->get_type_name_by_id('package', $payment->package_id),
                            'package_price' => $this->crud_model->get_type_name_by_id('package', $payment->package_id, 'amount'),
                            'payment_type' => $data['payment_type'],
                        );
                        $data2['package_info'] = json_encode($package_info);

                        $this->db->where('user_id', $payment->user_id);
                        $this->db->update('user', $data2);

                        $this->wallet_model->reduce_user_balance($amount, $user_id);
                        recache();
                        redirect(base_url() . 'index.php/home/profile/part/payment_info', 'refresh');
                    } else {
                        redirect(base_url() . 'index.php/home/premium_package', 'refresh');
                    }
                } else if ($this->input->post('payment_type') == 'pum') {

                    $user_id = $this->session->userdata('user_id');
                    $payment_type = $this->input->post('payment_type');
                    $package_id = $this->input->post('package_id');
                    $amount = $this->db->get_where('package', array('package_id' => $package_id))->row()->amount;
                    $package_name = $this->db->get_where('package', array('package_id' => $package_id))->row()->name;

                    $data['package_id'] = $package_id;
                    $data['user_id'] = $user_id;
                    $data['payment_type'] = 'PayUmoney';
                    $data['payment_status'] = 'due';
                    $data['payment_details'] = 'none';
                    $data['amount'] = $amount;
                    $data['purchase_datetime'] = time();

                    $this->db->insert('package_payment', $data);
                    $payment_id = $this->db->insert_id();

                    $data['payment_code'] = date('Ym', $data['purchase_datetime']) . $payment_id;

                    $this->session->set_userdata('payment_id', $payment_id);

                    $pum_merchant_key = $this->crud_model->get_settings_value('business_settings', 'pum_merchant_key', 'value');
                    $pum_merchant_salt = $this->crud_model->get_settings_value('business_settings', 'pum_merchant_salt', 'value');

                    $user_id = $this->session->userdata('user_id');
                    /****TRANSFERRING USER TO PAYPAL TERMINAL****/
                    $this->pum->add_field('key', $pum_merchant_key);
                    $this->pum->add_field('txnid', substr(hash('sha256', mt_rand() . microtime()), 0, 20));
                    $this->pum->add_field('amount', $amount);
                    $this->pum->add_field('firstname', $this->db->get_where('user', array('user_id' => $user_id))->row()->username);
                    $this->pum->add_field('email', $this->db->get_where('user', array('user_id' => $user_id))->row()->email);
                    $this->pum->add_field('phone', $this->db->get_where('user', array('user_id' => $user_id))->row()->phone);
                    $this->pum->add_field('productinfo', 'Payment with PayUmoney');
                    $this->pum->add_field('service_provider', 'payu_paisa');
                    $this->pum->add_field('udf1', $payment_id);

                    $this->pum->add_field('surl', base_url() . 'index.php/home/cus_pum_success');
                    $this->pum->add_field('furl', base_url() . 'index.php/home/cus_pum_failure');

                    // submit the fields to pum
                    $this->pum->submit_pum_post();
                }
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    function get_dropdown_by_id($table, $field, $id, $name = 'name', $on_change = '', $fst_val = '')
    {
        echo $this->crud_model->select_html2($table, $table, $name, 'add', 'form-control selectpicker', '', $field, $id, $on_change, 'single', translate($table), $fst_val);
    }
    public function customer_product_bulk_upload_save()
    {
        /*if(demo()){
            $this->session->set_flashdata('error',translate('This operation is invalid for demo'));
            redirect('home/profile/part/post_product_bulk');
        }*/

        if (!file_exists($_FILES['bulk_file']['tmp_name']) || !is_uploaded_file($_FILES['bulk_file']['tmp_name'])) {
            $_SESSION['error'] = translate('File is not selected');
            //redirect('home/customer_product_bulk_upload');
            redirect(base_url() . 'index.php/home/profile/part/post_product_bulk');
        }

        $inputFileName = $_FILES['bulk_file']['tmp_name'];

        $inputFileType = $this->spreadsheet->identify($inputFileName);
        $reader = $this->spreadsheet->createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $products = array();
        if (!empty($sheetData)) {

            if (!isset($sheetData[1])) {
                $_SESSION['error'] = translate('Column names are missing');
                //redirect('home/customer_product_bulk_upload');
                redirect(base_url() . 'index.php/home/profile/part/post_product_bulk');
            }

            foreach ($sheetData[1] as $colk => $colv) {
                $col_map[$colk] = $colv;
            }


            if (!isset($sheetData[2])) {
                $_SESSION['error'] = translate('Data missing');
                //redirect('home/customer_product_bulk_upload');
                redirect(base_url() . 'index.php/home/profile/part/post_product_bulk');
            }

            for ($i = 2; $i <= count($sheetData); $i++) {
                $product = array();
                foreach ($sheetData[$i] as $colk => $colv) {
                    $product[$col_map[$colk]] = $colv;
                }
                $products[] = $product;
            }
        }


        if (!empty($products)) {
            foreach ($products as $product) {
                $upload_amount = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->product_upload;
                if ($upload_amount > 0) {
                    $this->customer_product_bulk_upload_save_single($product);
                }
            }
        }

        //exit;
        $_SESSION['success'] = translate('Products uploaded');
        //redirect('home/customer_product_bulk_upload');
        redirect(base_url() . 'index.php/home/profile/part/post_product_bulk');
    }

    public function customer_product_bulk_upload_save_single($product)
    {
        $image_urls = array();

        $product_data['num_of_imgs'] = 0;
        if (!empty($product['images'])) {
            $image_urls = explode(',', $product['images']);
            $product_data['num_of_imgs'] = count($image_urls);
        }

        $product_data['title'] = $product['title'];
        $product_data['description'] = $product['description'];
        $product_data['category'] = is_numeric($product['category']) ? $product['category'] : 0;
        $product_data['sub_category'] = is_numeric($product['sub_category']) ? $product['sub_category'] : 0;
        $product_data['brand'] = $product['brand'];
        $product_data['prod_condition'] = $product['condition'] != "used" ? "new" : "used";

        $product_data['sale_price'] = is_numeric($product['sale_price']) ? $product['sale_price'] : 0;

        $product_data['add_timestamp'] = time();
        $product_data['status'] = $product['published'] == 'yes' ? 'ok' : '';
        $product_data['admin_status'] = 'ok';
        $product_data['is_sold'] = 'no';
        $product_data['rating_user'] = '[]';

        $product_data['tag'] = $product['tag'];
        $product_data['color'] = null;

        $product_data['front_image'] = 0;

        $product_data['additional_fields'] = null;
        $product_data['added_by'] = $this->session->userdata('user_id');
        $product_data['options'] = json_encode($options = array());

        $this->db->insert('customer_product', $product_data);

        //echo $this->db->last->query().'\n';

        $product_id = $this->db->insert_id();
        $this->crud_model->set_category_data(0);
        recache();

        // Package Info subtract code
        $upload_amount = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->product_upload;
        $du['product_upload'] = $upload_amount - 1;
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->update('user', $du);

        if (!empty($image_urls)) {
            //if(!demo()){
            $this->crud_model->file_up_from_urls($image_urls, "customer_product", $product_id);
            //}
        }
    }
    function package_payment_list($para2 = '')
    {
        $this->load->library('Ajax_pagination');

        $id = $this->session->userdata('user_id');

        $this->db->where('user_id', $id);

        $config['total_rows'] = $this->db->count_all_results('package_payment');;
        $config['base_url'] = base_url() . 'index.php/home/package_payment_list/';
        $config['per_page'] = 5;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para2;

        $function = "package_payment_list('0')";
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start = floor($rr) * $config['per_page'];
        $function = "package_payment_list('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function = "package_payment_list('" . ($para2 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function = "package_payment_list('" . ($para2 + $config['per_page']) . "')";
        $config['next_link'] = '&rsaquo;';
        $config['next_tag_open'] = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination pagination-style-2 ">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="active"><a rel="grow" class="btn-u btn-u-red grow" class="active">';
        $config['cur_tag_close'] = '</a></li>';

        $function = "package_payment_list(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        $this->db->where('user_id', $id);
        $page_data['query'] = $this->db->order_by("package_payment_id", "desc")->get('package_payment', $config['per_page'], $para2)->result_array();
        $this->load->view('front/user/package_payment_list', $page_data);
    }
    function uploaded_products_list($para2 = '')
    {
        $this->load->library('Ajax_pagination');

        $id = $this->session->userdata('user_id');

        $this->db->where('added_by', $id);

        $config['total_rows'] = $this->db->count_all_results('customer_product');;
        $config['base_url'] = base_url() . 'index.php/home/uploaded_products_list/';
        $config['per_page'] = 5;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para2;

        $function = "uploaded_products_list('0')";
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start = floor($rr) * $config['per_page'];
        $function = "uploaded_products_list('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function = "uploaded_products_list('" . ($para2 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function = "uploaded_products_list('" . ($para2 + $config['per_page']) . "')";
        $config['next_link'] = '&rsaquo;';
        $config['next_tag_open'] = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination pagination-style-2 ">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="active"><a rel="grow" class="btn-u btn-u-red grow" class="active">';
        $config['cur_tag_close'] = '</a></li>';

        $function = "uploaded_products_list(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li><a rel="grow" class="btn-u btn-u-sea grow" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        $this->db->where('added_by', $id);
        $page_data['query'] = $this->db->get('customer_product', $config['per_page'], $para2)->result_array();

        $this->load->view('front/user/uploaded_products_list', $page_data);
    }
    function customer_product_status($para1 = "", $para2 = "")
    {
        if ($para1 == 'no') {
            $data['status'] = 'ok';
            $msg = 'Published';
        } elseif ($para1 == 'ok') {
            $data['status'] = 'no';
            $msg = 'Unpublished';
        }
        $this->db->where('customer_product_id', $para2);
        $this->db->update('customer_product', $data);
        echo $msg;
        // $this->load->view('front/user/uploaded_products');
    }
    function shippingaddressdel($id = '')
    {

        if ($id != '') {
            $this->db->where('id', $id);
            $this->db->delete('shipping_address');
            echo "done";
        } else {
            echo "failed";
        }
    }
    function shippingaddress($id = '')
    {

        if ($id != '') {
            $shipaddress = $this->db->get_where('shipping_address', array('id' => $id))->result_array();
            $shipaddress = $shipaddress[0];
            $cty = $shipaddress['city'];
            $state = $shipaddress['district'];
            $coutry = $shipaddress['country'];




            echo  $shipaddress['name'] . '^^' . $shipaddress['address'] . '^^' . $shipaddress['address1'] . '^^' . $shipaddress['email'] . '^^' . $shipaddress['city'] . '^^' . $shipaddress['country'] . '^^' . $shipaddress['district'] . '^^' . $shipaddress['mobile'] . '^^' . $shipaddress['zip_code'] . '^^' . $cty . '^^' . $coutry . '^^' . $shipaddress['state'];
            exit;
        }
    }
    function get_quotation()
    {
        $userlat = $this->input->post('userlat');
        $storeaddress = $this->input->post('storeaddress');
        $useraddress = $this->input->post('useraddress');
        $userlng = $this->input->post('userlng');
        $storelat = $this->input->post('storelat');
        $storelng = $this->input->post('storelng');
        $storeid = $this->input->post('storeid');
        $this->crud_model->getQuotation($storeid, $userlat, $userlng, $storelat, $storelng, $storeaddress, $useraddress);
    }
    function shippingaddressupdate($id = '')
    {
       
                $data1['id']           =  $this->input->post('bookId');
                $data['name']           =  $this->input->post('s_name');
                $data['mobile']           =  $this->input->post('s_mobile');
                $data['email']           =  $this->input->post('s_email');
                $data['latitude']           =  $this->input->post('s_latitude');
                $data['longitude']           =  $this->input->post('s_longitude');
                $data['address']           =  $this->input->post('s_address');
                $data['address1']           =  $this->input->post('s_address1');
                $data['country']           =  $this->input->post('country1') . '-' . $this->input->post('cou_shrt');
                $data['state']           =  $this->input->post('state1');
                $data['city']           =  $this->input->post('cities1');
                $data['zip_code']           =  $this->input->post('s_zipcode');
                //echo '<pre>'; print_r($data);
                
                $this->db->where('id', $data1['id']);
                $this->db->update('shipping_address', $data);
                   
        //redirect(base_url() . 'home/cart_checkout');



    }
    function checkProudctAvailability($storeId, $productId)
    {

        if (!$storeId) 0;
        $product_title = $this->db->get_where('product', array('product_id' => $productId))->row()->title;
        //$count = $this->db->get_where('product', ['product_id' => $productId, 'store_id' => $storeId])->num_rows();
        $count = $this->db->get_where('product', ['title' => $product_title, 'store_id' => $storeId, 'status' => 'ok'])->num_rows();
        //   echo $this->db->last_query();
        echo $count;
    }
    function pickup($para = '')
    {
        $this->session->unset_userdata('pickup');
        //$this->session->unset_userdata('user_zips');
        $this->session->set_userdata('pickup_loc');
        $this->session->set_userdata('pickup', "pickup");
        $this->session->set_userdata('pickup_loc', $para);
        $no_of_visitors = $this->db->get_where('vendor', array('vendor_id' => $para))->row()->no_of_visitors;
        $data['no_of_visitors'] = $no_of_visitors + 1;
        $this->db->where('vendor_id', $para);
        $this->db->update('vendor', $data);
        echo 1;
    }
    function deliverycodesearch($para = '')
    {
        $this->session->unset_userdata('pickup');
        $this->session->unset_userdata('user_zips');
        // $this->db->like('zip', $para);
        //$this->db->where('oid', $this->session->userdata('propertyIDS'));
        // $availavle=$this->db->get('admins');
        // echo $this->db->last_query();
        //if($availavle->num_rows() == 1){
        $this->session->set_userdata('user_zips', $para);
        echo 1;
        //  }
        //  else 
        //  {

        //  $this->session->unset_userdata('user_zips');
        //  $this->session->unset_userdata('pickup');

        //      echo 0; 
        //  }
    }
    function deliverycodecheck1($para = '')
    {
        if ($this->session->userdata('pickup') != '' && $this->session->userdata('user_zips') != '') {
        }
        $this->session->unset_userdata('pickup');
        $this->session->unset_userdata('user_zips');

        redirect(base_url() . 'home/');
    }
    function product_crm()
    {
        $products = $this->db->get_where('product', array(
            'crm' => 0
        ))->result_array();
        //print_r($products);exit;
        foreach ($products as $pro) {
            $data['id'] = $pro['product_id'];
            $data['vendor_id'] = $pro['vendor_id'];
            $data['rating_num'] = $pro['rating_num'];
            $data['rating_total'] = $pro['rating_total'];
            $data['rating_user'] = $pro['rating_user'];
            $data['title'] = $pro['title'];
            $data['added_by'] = $pro['added_by'];
            $data['category'] = $pro['category'];
            $data['description'] = $pro['description'];
            $data['specification'] = $pro['specification'];
            $data['return_policy'] = $pro['return_policy'];
            $data['weight'] = $pro['weight'];
            $data['height'] = $pro['height'];
            $data['width'] = $pro['width'];
            $data['breath'] = $pro['breath'];
            $data['shipping_class'] = $pro['shipping_class'];
            $data['cubical_from'] = $pro['cubical_from'];
            $data['cubical_to'] = $pro['cubical_to'];
            $data['sub_category'] = $pro['sub_category'];
            $data['num_of_imgs'] = $pro['num_of_imgs'];
            $data['sale_price'] = $pro['sale_price'];
            $data['discount_price'] = $pro['discount_price'];
            $data['purchase_price'] = $pro['purchase_price'];
            $data['shipping_cost'] = $pro['shipping_cost'];
            $data['cashback'] = $pro['cashback'];
            $data['cashback_type'] = $pro['cashback_type'];
            $data['add_timestamp'] = $pro['add_timestamp'];
            $data['featured'] = $pro['featured'];
            $data['tag'] = $pro['tag'];
            $data['status'] = $pro['status'];
            $data['front_image'] = $pro['front_image'];
            $data['brand'] = $pro['brand'];
            $data['current_stock'] = $pro['current_stock'];
            $data['unit'] = $pro['unit'];
            $data['additional_fields'] = $pro['additional_fields'];
            $data['number_of_view'] = $pro['number_of_view'];
            $data['background'] = $pro['background'];
            $data['discount'] = $pro['discount'];
            $data['discount'] = $pro['discount'];
            $data['discount_type'] = $pro['discount_type'];
            $data['tax'] = $pro['tax'];
            $data['tax_type'] = $pro['tax_type'];
            $data['color'] = $pro['color'];
            $data['options'] = $pro['options'];
            $data['main_image'] = $pro['main_image'];
            $data['download'] = $pro['download'];
            $data['download_name'] = $pro['download_name'];
            $data['deal'] = $pro['deal'];
            $data['deal_qty'] = $pro['deal_qty'];
            $data['dl_startdate'] = $pro['dl_startdate'];
            $data['dl_enddate'] = $pro['dl_enddate'];
            $data['num_of_downloads'] = $pro['num_of_downloads'];
            $data['update_time'] = $pro['update_time'];
            $data['requirements'] = $pro['requirements'];
            $data['logo'] = $pro['logo'];
            $data['video'] = $pro['video'];
            $data['retailler_limit'] = $pro['retailler_limit'];
            $data['last_viewed'] = $pro['last_viewed'];
            $data['p_condition'] = $pro['p_condition'];
            $data['sale_from'] = $pro['sale_from'];
            $data['sale_to'] = $pro['sale_to'];
            $data['sale_type'] = $pro['sale_type'];
            $data['sale_mode'] = $pro['sale_mode'];
            $data['processing_time'] = $pro['processing_time'];
            $data['processing_type'] = $pro['processing_type'];
            $data['qty'] = $pro['qty'];
            $data['del_status'] = $pro['del_status'];
            $data['products'] = $pro['products'];
            $data['is_bundle'] = $pro['is_bundle'];
            $data['vendor_featured'] = $pro['vendor_featured'];
            $data['bidding'] = $pro['bidding'];
            $data['bid_start_date'] = $pro['bid_start_date'];
            $data['bid_start_time'] = $pro['bid_start_time'];
            $data['bid_end_date'] = $pro['bid_end_date'];
            $data['bid_end_time'] = $pro['bid_end_time'];
            $data['min_bid_amount'] = $pro['min_bid_amount'];
            $data['max_bid_amount'] = $pro['max_bid_amount'];
            $data['threed'] = $pro['threed'];
            $data['threed_url'] = $pro['threed_url'];
            $data['ar'] = $pro['ar'];
            $data['ar_url'] = $pro['ar_url'];
            $data['qr'] = $pro['qr'];
            $data['qr_url'] = $pro['qr_url'];
            $data['review_count'] = $pro['review_count'];
            $data['rating'] = $pro['rating'];
            $data['enquiry'] = $pro['enquiry'];
            $data['callnow'] = $pro['callnow'];
            $DB2 = $this->load->database('crm', TRUE);
            $DB2->insert('products', $data);
            //echo $DB2->last_query();exit;
            $data2['crm'] = 1;
            $this->db->where('product_id', $pro['product_id']);
            $this->db->update('product', $data2);
        }
    }
    function category_crm()
    {
        $category = $this->db->get_where('category', array(
            'crm' => 0
        ))->result_array();
        foreach ($category as $pro) {
            $data['id'] = $pro['category_id'];

            $data['category_name'] = $pro['category_name'];
            $data['description'] = $pro['description'];
            $data['digital'] = $pro['digital'];

            $data['banner'] = $pro['banner'];
            $data['data_brands'] = $pro['data_brands'];
            $data['data_vendors'] = $pro['data_vendors'];
            $data['data_subdets'] = $pro['data_subdets'];
            $DB2 = $this->load->database('crm', TRUE);
            $DB2->insert('category', $data);
            //  echo $DB2->last_query();exit;
            $data2['crm'] = 1;
            $this->db->where('category_id', $pro['category_id']);
            $this->db->update('category', $data2);
        }
    }
    function sub_category_crm()
    {
        $sub_category = $this->db->get_where('sub_category', array(
            'crm' => 0
        ))->result_array();
        foreach ($sub_category as $pro) {
            $data['id'] = $pro['sub_category_id'];

            $data['sub_category_name'] = $pro['sub_category_name'];
            $data['description'] = $pro['description'];
            $data['category'] = $pro['category'];

            $data['brand'] = $pro['brand'];
            $data['digital'] = $pro['digital'];
            $data['banner'] = $pro['banner'];

            $DB2 = $this->load->database('crm', TRUE);
            $DB2->insert('sub_category', $data);
            //  echo $DB2->last_query();exit;
            $data2['crm'] = 1;
            $this->db->where('sub_category_id', $pro['sub_category_id']);
            $this->db->update('sub_category', $data2);
        }
    }
    function brand_crm()
    {
        $brand = $this->db->get_where('brand', array(
            'crm' => 0
        ))->result_array();
        foreach ($brand as $pro) {
            $data['id'] = $pro['brand_id'];

            $data['name'] = $pro['name'];
            $data['description'] = $pro['description'];
            $data['logo'] = $pro['logo'];



            $DB2 = $this->load->database('crm', TRUE);
            $DB2->insert('brand', $data);
            //echo $DB2->last_query();exit;
            $data2['crm'] = 1;
            $this->db->where('brand_id', $pro['brand_id']);
            $this->db->update('brand', $data2);
        }
    }
    function user_crm()
    {
        $category = $this->db->get_where('user', array(
            'crm' => 0
        ))->result_array();
        foreach ($category as $pro) {
            $data['id'] = $pro['user_id'];
            $data['username'] = $pro['username'];
            $data['surname'] = $pro['surname'];
            $data['email'] = $pro['email'];
            $data['phone'] = $pro['phone'];
            $data['address1'] = $pro['address1'];
            $data['address2'] = $pro['address2'];
            $data['city'] = $pro['city'];
            $data['zip'] = $pro['zip'];
            $data['langlat'] = $pro['langlat'];
            $data['password'] = $pro['password'];
            $data['fb_id'] = $pro['fb_id'];
            $data['g_id'] = $pro['g_id'];
            $data['g_photo'] = $pro['g_photo'];
            $data['creation_date'] = $pro['creation_date'];
            $data['google_plus'] = $pro['google_plus'];
            $data['skype'] = $pro['skype'];
            $data['facebook'] = $pro['facebook'];
            $data['wishlist'] = $pro['wishlist'];
            $data['last_login'] = $pro['last_login'];
            $data['user_type'] = $pro['user_type'];
            $data['wallet'] = $pro['wallet'];
            $data['user_type_till'] = $pro['user_type_till'];
            $data['left_product_type'] = $pro['left_product_type'];
            $data['downloads'] = $pro['downloads'];
            $data['country'] = $pro['country'];
            $data['state'] = $pro['state'];
            $data['product_upload'] = $pro['product_upload'];
            $data['package_info'] = $pro['package_info'];
            $DB2 = $this->load->database('crm', TRUE);
            $DB2->insert('customer', $data);
            $data2['crm'] = 1;
            $this->db->where('user_id', $pro['user_id']);
            $this->db->update('user', $data2);
        }
    }

    function sale_order_crm()
    {
        $sale = $this->db->get_where('sale', array(
            'crm' => 0
        ))->result_array();
        foreach ($sale as $pro) {
            $data['id'] = $pro['sale_id'];
            $data['order_id'] = $pro['order_id'];
            $data['delivery_agent_id'] = $pro['delivery_agent_id'];
            $data['delivery_pickup_date'] = $pro['delivery_pickup_date'];
            $data['delivery_pickup_time'] = $pro['delivery_pickup_time'];
            $data['read_status'] = $pro['read_status'];
            $data['package_details'] = $pro['package_details'];
            $data['bill_address'] = $pro['bill_address'];
            $data['order_type'] = $pro['order_type'];
            $data['group_deal'] = $pro['group_deal'];
            $data['vendor_delivery'] = $pro['vendor_delivery'];
            $data['order_notes'] = $pro['order_notes'];
            $data['created_datetime'] = $pro['created_datetime'];
            $data['sale_code'] = $pro['sale_code'];
            $data['buyer'] = $pro['buyer'];
            $data['guest_id'] = $pro['guest_id'];
            $data['seller'] = $pro['seller'];
            $data['product_details'] = $pro['product_details'];
            $data['shipping_address'] = $pro['shipping_address'];
            $data['vat'] = $pro['vat'];
            $data['vat_percent'] = $pro['vat_percent'];
            $data['imei'] = $pro['imei'];
            $data['shipping_id'] = $pro['shipping_id'];
            $data['shipping'] = $pro['shipping'];
            $data['payment_type'] = $pro['payment_type'];
            $data['payment_status'] = $pro['payment_status'];
            $data['payment_details'] = $pro['payment_details'];
            $data['payment_timestamp'] = $pro['payment_timestamp'];
            $data['grand_total'] = $pro['grand_total'];
            $data['sale_datetime'] = $pro['sale_datetime'];
            $data['delivary_datetime'] = $pro['delivary_datetime'];
            $data['status'] = $pro['status'];
            $data['delivery_status'] = $pro['delivery_status'];
            $data['cancel_status'] = $pro['cancel_status'];
            $data['cancel_reason'] = $pro['cancel_reason'];
            $data['cancel_remarks'] = $pro['cancel_remarks'];
            $data['return_status'] = $pro['return_status'];
            $data['return_reason'] = $pro['return_reason'];
            $data['return_remarks'] = $pro['return_remarks'];
            $data['return_action'] = $pro['return_action'];
            $data['order_trackment'] = $pro['order_trackment'];
            $data['review'] = $pro['review'];
            $data['viewed'] = $pro['viewed'];
            $data['exchange_id'] = $pro['exchange_id'];
            $data['refund_id'] = $pro['refund_id'];
            $DB2 = $this->load->database('crm', TRUE);
            $DB2->insert('invoices', $data);
            $data2['crm'] = 1;
            $this->db->where('sale_id', $pro['sale_id']);
            $this->db->update('sale', $data2);
        }
    }
    /*FUNCTION CCAVRequestHandler*/
    function ccav_requesthandler()
    {
        //  echo "<pre>"; print_r($_POST); echo "</pre>";exit;
        $merchant_data = '';
        $access_code = $this->db->get_where('business_settings', array('type' => 'cca_accesscode'))->row()->value; //Shared by CCAVENUES
        $working_key = $this->db->get_where('business_settings', array('type' => 'cca_workingkey'))->row()->value; //Shared by CCAVENUES


        $merchant_data .= 'currency=' . $_POST['currency'] . '&';
        foreach ($_POST as $key => $value) {


            if ($key == 'currency') {
                //$merchant_data.=' '.$key.'='.$value.'&';        
            } else {
                $merchant_data .= $key . '=' . $value . '&';
            }
        }

        $merchant_data = rtrim($merchant_data, '&');
        // echo '<br/>'.$merchant_data;exit;
        //echo '<br/>'.$this->someclass->some_method(); exit;
        //echo '<br/>Pack '. pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f); exit;
        $encrypted_data = $this->someclass->encrypt_cc($merchant_data, $working_key); // exit;
        //$encrypted_data=$this->crypto->encrypt_crypto($merchant_data,$working_key);
        //print_r($encrypted_data); exit;
        $cca_account_type = $this->db->get_where('business_settings', array('type' => 'cca_account_type'))->row()->value;

        //$cca_account_type = 'original';
        if ($cca_account_type == 'sandbox') {
            $url = "https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction";
        } else {
            //$url="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction";
            $url = "https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction";
        }
?>
        <form method="post" name="redirect" action="<?php echo $url; ?>">
            <?php
            echo "<input type='hidden' name=encRequest value=$encrypted_data>";
            echo "<input type='hidden' name=access_code value=$access_code>";
            ?>
        </form>
        <script language='javascript'>
            document.redirect.submit();
        </script>
<?php
    }
    function ccav_payment_cancel()
    {
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        exit;
    }
    function ccav_payment_success()
    {
        //echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        $workingKey = $this->db->get_where('business_settings', array('type' => 'cca_workingkey'))->row()->value;
        $encResponse = $_POST["encResp"];
        $rcvdString = $this->someclass->decrypt_cc($encResponse, $workingKey);
        $order_status = "";
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);
        for ($i = 0; $i < $dataSize; $i++) {
            $information = explode('=', $decryptValues[$i]);
            $responseMap[$information[0]] = $information[1];
        }
        //print_r($responseMap);
        $order_status = $responseMap['order_status'];
        //print_r($order_status); exit;
        if ($order_status == 'Success') {
            $data['payment_details']   = json_encode($responseMap);
            $carted  = $this->cart->contents();
            $saleDet = $this->db->get_where('sale', array('order_id' => $responseMap['order_id']))->result_array();
            $saleDet = $saleDet[0];
            $sale_id = $saleDet['sale_id'];
            $user_id = $saleDet['buyer'];
            //echo $sale_id; exit;
            foreach ($carted as $value) {
                $this->crud_model->decrease_quantity($value['id'], $value['qty']);
                $data1['type']         = 'destroy';
                $data1['category']     = $this->db->get_where('product', array(
                    'product_id' => $value['id']
                ))->row()->category;
                $data1['sub_category'] = $this->db->get_where('product', array(
                    'product_id' => $value['id']
                ))->row()->sub_category;
                $data1['product']      = $value['id'];
                $data1['quantity']     = $value['qty'];
                $data1['total']        = 0;
                $data1['reason_note']  = 'sale';
                $data1['sale_id']      = $sale_id;
                $data1['datetime']     = time();
                $this->db->insert('stock', $data1);
            }

            $vendors = $this->crud_model->vendors_in_sale($sale_id);
            $payment_status = array();
            foreach ($vendors as $p) {
                $payment_status[] = array('vendor' => $p, 'status' => 'paid');
            }
            if ($this->crud_model->is_admin_in_sale($sale_id)) {
                $payment_status[] = array('admin' => '', 'status' => 'paid');
            }
            $data['payment_status'] = json_encode($payment_status);
            $this->db->where('sale_id', $sale_id);
            $this->db->update('sale', $data);

            // $this->crud_model->digital_to_customer($sale_id);
            $this->cart->destroy();
            //  $this->session->set_userdata('couponer','');
            $this->crud_model->email_invoice($id);
            //$this->session->set_userdata('sale_id', '');
            $this->session->set_userdata('user_id', $user_id);
            $sale_code =  $this->crud_model->get_type_name_by_id('sale', $sale_id, 'sale_code');
            $msg = "Your Order Is Placed Successfully. Order Id: #" . $sale_code;
            //$this->crud_model->sendsmsval($_POST['phone'],$msg); 

            //$this->session->set_userdata('bidding_stock','');
            //redirect(base_url() . 'index.php/home/invoice/total/' . $id, 'refresh');
            redirect(base_url() . 'index.php/home/invoice/' . $sale_id, 'refresh');
        }
    }


    function crm_cron()
    {
        $this->product_crm();
        $this->category_crm();
        $this->sub_category_crm();
        $this->brand_crm();
        $this->user_crm();
        $this->sale_order_crm();
    }
    public function cronnew()
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 600);
        $this->load->helper('url');
        $this->load->helper('file');
        $this->load->dbforge();
        $this->cron_1();
        $this->cron_2();
    }
    public function cron_1()
    {


        $this->drop_tables();
        
    }
    public function drop_tables()
    {

        $tables = $this->db->list_tables($this->db->database);

        foreach ($tables as $table) {
            $this->dbforge->drop_table($table, TRUE);
        }
    }
    public function upload_sql($filename = '')
    {




        // Set line to collect lines that wrap
        $templine = '';
        // Read in entire file
        $lines = file($filename);

        // Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Add this line to the current templine we are creating
            $templine .= $line;
            // echo $templine;
            // If it has a semicolon at the end, it's the end of the query so can process this templine
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                $this->db->query($templine);

                // Reset temp variable to empty
                $templine = '';
            }
        }
    }


    public function cron_2()
    {
        $this->delete_uploads_folder('uploads');
        $this->extract_uploads_folder('uploads.zip');
    }
    function delete_uploads_folder($dirname = '')
    {




        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
        if (!$dir_handle)
            return false;
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file))
                    unlink($dirname . "/" . $file);
                else
                    $this->delete_uploads_folder($dirname . '/' . $file);
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }
    function extract_uploads_folder($zipped_file = 'uploads.zip', $destination = 'uploads/')
    {

        $zip = new ZipArchive();
        $file = $zip->open($zipped_file);
        if ($file === TRUE) {

            $zip->extractTo($destination);
            $zip->close();
        } else {
            echo 'failed';
        }
    }
    function subscrib_prod($para1 = "")
    {
        echo $this->db->get_where('subscribe_pro', array('id' => $para1))->row()->amount;
    }
    function email_invoice($para1 = "")
    {
        $page_data['sale_id'] = $para1;
        $this->load->view('front/shopping_cart/invoice_email', $page_data);
    }
    
    function today_deal_cron($para1 = "")
    {
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
            if($date>$end_d && $taketo['status']!=0)
            {
                	foreach($taketoday_deal as $todeal)
                {
                    
                    $poid1 = json_decode($todeal['product_id'], true);
						date_default_timezone_set("Asia/Calcutta"); 
						$new_current_time=time();
						$start_time=$todeal['toda_start_date'].' '.$todeal['today_start_time'];
						$new_start_time=strtotime($start_time);
						$end_time=$todeal['today_end_date'].' '.$todeal['today_end_time'];
						$new_end_time=strtotime($end_time);
			 			$new_start_time;
			 			$new_end_time;
						$new_current_time; 
							 if( $new_current_time>$new_end_time)
							 {
							     foreach($poid1 as $to_p)
		    {
		       $data['discount'] = '';
		       $data['discount_type']='';
		       $data['today_status']='0';
            $this->db->where('product_id', $to_p);
            $this->db->update('product', $data);
		    }
							     
							 }}
                
            }
        
        
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
