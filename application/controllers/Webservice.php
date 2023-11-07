<?php
//error_reporting(1);
//header('Access-Control-Allow-Origin:*'); 
@session_start();
@ob_start();
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Webservice extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('paypal');
		$this->load->library('twoCheckout_Lib');
		$this->load->library('vouguepay');
		$this->load->library('pum');
		$instamojoParams = array("api_key" => "9171c1fe7db83b578d5a43ba9f38b832", "auth_token" => "439799c55744eeac7c56764463f67acf", "endpoint" => 'https://www.instamojo.com/api/1.1/');
		//$this->load->library('instamojo',$instamojoParams);
		$cache_time	 =  $this->db->get_where('general_settings', array('type' => 'cache_time'))->row()->value;
		if (!$this->input->is_ajax_request()) {
			$this->output->set_header('HTTP/1.0 200 OK');
			$this->output->set_header('HTTP/1.1 200 OK');
			$this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
			$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
			$this->output->set_header('Cache-Control: post-check=0, pre-check=0');
			$this->output->set_header('Pragma: no-cache');
			if ($this->router->fetch_method() == 'index' || $this->router->fetch_method() == 'featured_item' || $this->router->fetch_method() == 'others_product' || $this->router->fetch_method() == 'all_brands' || $this->router->fetch_method() == 'all_category' || $this->router->fetch_method() == 'all_vendor' || $this->router->fetch_method() == 'blog' || $this->router->fetch_method() == 'blog_view' || $this->router->fetch_method() == 'vendor' || $this->router->fetch_method() == 'category' || $this->router->fetch_method() == 'sub_category') {
				$this->output->cache($cache_time);
			}
		}
		$this->config->cache_query();
		$currency = $this->session->userdata('currency');
		if (!isset($currency)) {
			$this->session->set_userdata('currency', $this->db->get_where('business_settings', array('type' => 'home_def_currency'))->row()->value);
		}
		setcookie('lang', $this->session->userdata('language'), time() + (86400), "/");
		setcookie('curr', $this->session->userdata('currency'), time() + (86400), "/");
		$UPURL = explode('/', "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
		if ($_SERVER['DOCUMENT_ROOT'] == 'D:/xampplite/htdocs' || $_SERVER['DOCUMENT_ROOT'] == 'D:/xampp/htdocs' || $_SERVER['DOCUMENT_ROOT'] == 'C:/xampplite/htdocs' || $_SERVER['DOCUMENT_ROOT'] == 'C:/xampp/htdocs') {
			$UPURL = 'http://' . $UPURL[0] . '/' . $UPURL[1] . '/' . $UPURL[2] . '/';
			$propertyIDS =  file_get_contents($UPURL . 'id.txt');
		} else {
			$UPURL = 'http://' . $UPURL[0] . '/' . $UPURL[1] . '/';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $UPURL . 'id.txt');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$contents = curl_exec($ch);
			curl_close($ch);
			$propertyIDS =  $contents;
		}
		$this->session->set_userdata('propertyIDS', $propertyIDS);
		//$propertyIDS =  file_get_contents($UPURL.'id.txt'); 		
		//$this->session->set_userdata('propertyIDS', $propertyIDS);
	}
	/* FUNCTION: Loads Homepage*/
	public function index()

	{

		$home_style =  $this->db->get_where('ui_settings', array('type' => 'home_page_style'))->row()->value;

		$page_data['page_name']     = "home/home" . $home_style;

		$page_data['asset_page']    = "home";

		$page_data['page_title']    = translate('home');

		$this->benchmark->mark('code_start');

		$this->load->view('front/index', $page_data);

		$this->benchmark->mark('code_end');
	}





	function getFeatured()
	{

		$featuredProducts = $this->crud_model->product_list_set('featured', 30);

		foreach ($featuredProducts as $fp) {
			$product_id = $fp['product_id'];

			$fp['banner'] = $this->crud_model->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');

			$featured['featuredProducts'][] = $fp;
		}

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $featured);

		exit(json_encode($value));
	}

	//Get_Quotation Mohana (Lalamove Integration)
	function get_quotation($para1, $para2, $para3, $para4, $para5, $para6)
	{
		// $userlat = $this->input->post('userlat');
		// $storeaddress = $this->input->post('storeaddress');
		// $useraddress = $this->input->post('useraddress');
		// $userlng = $this->input->post('userlng');
		// $storelat = $this->input->post('storelat');
		// $storelng = $this->input->post('storelng');
		$userlat = $para1;
		$userlng = $para2;
		$storelat = $para3;
		$storelng = $para4;
		$storeaddress = $para5;
		$useraddress = $para6;




		// $storeid = $this->input->post('storeid');
		$this->crud_model->getQuotation($storeid, $userlat, $userlng, $storelat, $storelng, $storeaddress, $useraddress);
	}

	function getrelated_product($id, $para1)
	{
		$recommends = $this->crud_model->product_list_set_app('related', 12, $id, $para1);
		foreach ($recommends as $rec) {
			//echo '<pre>'; print_r($rec);


			$page_data1['product_details']['product_id'] =  $rec['product_id'];
			$page_data1['product_details']['rating_num'] =  $rec['rating_num'];
			$page_data1['product_details']['rating_total'] =  $rec['rating_total'];
			$page_data1['product_details']['rating_user'] =  $rec['rating_user'];
			$page_data1['product_details']['title'] =  $rec['title'];
			$page_data1['product_details']['added_by'] =  $rec['added_by'];
			$page_data1['product_details']['category'] =  $rec['category'];
			$page_data1['product_details']['description'] =  $rec['description'];
			$page_data1['product_details']['sub_category'] =  $rec['sub_category'];
			$page_data1['product_details']['num_of_imgs'] =  $rec['num_of_imgs'];

			if ($page_data1['product_details']['num_of_imgs'] > 0) {
				$ab1 = 0;
				for ($ab = 1; $ab <= $page_data1['product_details']['num_of_imgs']; $ab++) {
					$pid = $page_data1['product_details']['product_id'];
					$productImage[$ab1] = 'https://myrunciit.my/uploads/product_image/product_' . $pid . '_' . $ab . '.jpg';
					$ab1++;
				}
				$page_data1['product_details']['product_image'] = $productImage;
			} else {
				$page_data1['product_details']['product_image'] = array('https://myrunciit.my/uploads/product_image/default.jpg');
			}


			$page_data1['sale_price'] =  $rec['sale_price'];
			$page_data1['purchase_price'] =  $rec['purchase_price'];
			$page_data1['shipping_cost'] =  $rec['shipping_cost'];
			$page_data1['add_timestamp'] =  $rec['add_timestamp'];
			$page_data1['featured'] =  $rec['featured'];
			$page_data1['tag'] =  $rec['tag'];
			$page_data1['status'] =  $rec['status'];
			$page_data1['front_image'] =  $rec['front_image'];
			$page_data1['brand'] =  $rec['brand'];
			$page_data1['current_stock'] =  $rec['current_stock'];
			$page_data1['unit'] =  $rec['unit'];
			$page_data1['additional_fields'] =  $rec['additional_fields'];
			$page_data1['number_of_view'] =  $rec['number_of_view'];
			$page_data1['background'] =  $rec['background'];
			$page_data1['discount'] =  $rec['discount'];
			$page_data1['discount_type'] =  $rec['discount_type'];
			$page_data1['tax'] =  $rec['tax'];
			$page_data1['tax_type'] =  $rec['tax_type'];
			$page_data1['color'] =  $rec['color'];
			$page_data1['options'] =  $rec['options'];
			$page_data1['main_image'] =  $rec['main_image'];
			$page_data1['download'] =  $rec['download'];
			$page_data1['download_name'] =  $rec['download_name'];
			$page_data1['deal'] =  $rec['deal'];
			$page_data1['num_of_downloads'] =  $rec['num_of_downloads'];
			$page_data1['update_time'] =  $rec['update_time'];
			$page_data1['requirements'] =  $rec['requirements'];
			$page_data1['logo'] =  $rec['logo'];
			$page_data1['video'] =  $rec['video'];
			$page_data1['last_viewed'] =  $rec['last_viewed'];
			$page_data1['products'] =  $rec['products'];
			$page_data1['is_bundle'] =  $rec['is_bundle'];
			$page_data1['vendor_featured'] =  $rec['vendor_featured'];

			$page_data2['product_details'][] = $page_data1;
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data2);

		exit(json_encode($value));
	}
	function getLatestProducts($para1 = '')

	{

		$latest['latestProducts'] = $this->crud_model->product_list_set_app('latest', 10, $para1);

		foreach ($latest['latestProducts'] as $lP) {

			$product_id = $lP['product_id'];

			$lP['banner'] = $this->crud_model->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');

			$latestProducts['latestProducts'][] = $lP;
		}

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $latestProducts);

		exit(json_encode($value));
	}

	function getMostViewedProducts($para1 = '')

	{

		$mostViewed['mostViewedProducts'] = $this->crud_model->product_list_set_app('most_viewed', 10, $para1);


		foreach ($mostViewed['mostViewedProducts'] as $mv) {

			$product_id = $mv['product_id'];

			$mv['banner'] = $this->crud_model->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');

			$mostViewed1['latestProducts'][] = $mv;
		}

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $mostViewed1);

		exit(json_encode($value));
	}

	function getRecentlyViewed($para1 = '')

	{

		$recent = $this->crud_model->product_list_set_app('recently_viewed', 10, $para1);

		$i = 0;

		foreach ($recent as $rc) {

			$product_id = $rc['product_id'];

			$recentProduct['recentlyViewedProducts'][$i] = $rc;

			$recentProduct['recentlyViewedProducts'][$i]['banner'] = $this->crud_model->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');

			$i++;
		}

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $recentProduct);

		exit(json_encode($value));
	}

	function getDealProduct($para1)

	{
		$deal['dealProducts'] = $this->crud_model->product_list_set_app('deal', 10, $para1);

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $deal);

		exit(json_encode($value));
	}

	function top_bar_right()
	{

		$this->load->view('front/components/top_bar_right.php');
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

			$page_data['vendor_id']			= $para2;

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

			$page_data['page_name']        	= "vendor/public_profile";

			$page_data['content']        	= "home";

			$this->db->where("status", "ok");

			$this->db->where('added_by', json_encode(array('type' => 'vendor', 'id' => $para1)));

			$page_data['sliders']       = $this->db->get('slides')->result_array();

			$page_data['vendor_info']       = $this->db->get_where('vendor', array('vendor_id' => $para1))->result_array();

			$page_data['vendor_tags']       = $this->db->get_where('vendor', array('vendor_id' => $para1))->row()->keywords;

			$page_data['vendor_id']			= $para1;

			$this->load->view('front/index', $page_data);
		}
	}

	function vendor_info($para1 = "", $para2 = "", $para3 = "")
	{
		$product_data   = $this->db->get_where('vendor', array('vendor_id' => $para1));
		$page_data['vendor_info'] = $product_data->result_array();
		if (count($page_data['vendor_info']) != 0) {
			$page_data['vendor_info'] = $page_data['vendor_info'][0];

			$page_data1['vendor_details']['vendor_id'] =  $page_data['vendor_info']['vendor_id'];
			$page_data1['vendor_details']['name'] =  $page_data['vendor_info']['name'];
			$page_data1['vendor_details']['email'] =  $page_data['vendor_info']['email'];
			$page_data1['vendor_details']['company'] =  $page_data['vendor_info']['company'];
			$page_data1['vendor_details']['display_name'] =  $page_data['vendor_info']['display_name'];
			$page_data1['vendor_details']['address1'] =  $page_data['vendor_info']['address1'];
			$page_data1['vendor_details']['address2'] =  $page_data['vendor_info']['address2'];
			//$page_data1['vendor_details']['store_phone'] =  $page_data['vendor_info']['store_phone'];
			$page_data1['vendor_details']['city'] =  $page_data['vendor_info']['city'];
			$page_data1['vendor_details']['state'] =  $page_data['vendor_info']['state'];
			$page_data1['vendor_details']['country'] =  $page_data['vendor_info']['country'];
			$page_data1['vendor_details']['logo'] =  base_url() . 'uploads/vendor_logo_image/logo_' . $para1 . '.png';
			$page_data1['vendor_details']['member_since'] =  date("d M, Y", $this->crud_model->get_type_name_by_id('vendor', $para1, 'create_timestamp'));
			$page_data1['vendor_details']['facebook'] =  $page_data['vendor_info']['facebook'];
			$page_data1['vendor_details']['google_plus'] =  $page_data['vendor_info']['google_plus'];
			$page_data1['vendor_details']['twitter'] =  $page_data['vendor_info']['twitter'];
			$page_data1['vendor_details']['skype'] =  $page_data['vendor_info']['skype'];
			$page_data1['vendor_details']['youtube'] =  $page_data['vendor_info']['youtube'];
			$page_data1['vendor_details']['pinterest'] =  $page_data['vendor_info']['pinterest'];
			$page_data1['vendor_details']['rating'] =  $this->crud_model->vendor_rating($para1);

			if ($para2 != '' & $para3 == 'b') {
				$datas['vendor_id'] = $para1;
				$datas['user_id'] = $para2;
				$datas['date'] = date("Y/m/d");
				$this->db->insert('from_add_vendor', $datas);
			}

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data1);

			exit(json_encode($value));



			//echo json_encode($page_data1['product_details']);

			exit;

			$page_data['product_details']['description'] = str_replace('</li>', '</p>', str_replace('<li>', '<p>', str_replace('</ul>', '', str_replace('<ul>', '', str_replace('<div>', '', str_replace('</div>', '', $page_data['product_details']['description']))))));

			$page_data['product_tags'] = $product_data->row()->tag;

			$page_data['product_details']['banner'] = $this->crud_model->file_view('product', $para1, '', '', 'thumb', 'src', 'multi', 'one');

			$page_data['product_details']['additional_specification'] = $this->crud_model->get_additional_fields($row['product_id']);
			$page_data['product_details']['shipment_info'] = $this->db->get_where('business_settings', array('type' => 'shipment_info'))->row()->value;

			$page_data['product_details']['product_by'] = $this->crud_model->product_by($para1, 'with_link');

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data);

			exit(json_encode($value));
		} else {
			$value = array("status" => "FAILED", "Message" => "Product not available");
			exit(json_encode($value));
		}
	}

	/* FUNCTION: Loads Category filter page */

	function vendor_product($para1 = "", $para2 = "")

	{



		//$page_data['all_products1'] = $this->db->get_where('product', array('added_by',json_encode(array('type'=>'vendor','id'=>$para1))))->result_array();
		//$this->db->where('added_by'=>json_encode(array('type'=>'vendor','id'=>$para1)));
		$this->db->where('added_by', json_encode(array('type' => 'vendor', 'id' => $para1)));
		$this->db->where('status', 'ok');
		$page_data['all_products1'] = $this->db->get('product')->result_array();
		//echo $this->db->last_query(); exit;





		foreach ($page_data['all_products1'] as $p) {



			$product_id = $p['product_id'];

			if ($p['discount'] == '') {

				$p['discount'] = 0.00;
			}

			$p['banner'] = $this->crud_model->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');

			$temppProduct[] = $p;
		}

		$page_data['all_products'] = $temppProduct;

		unset($page_data['all_products1']);

		if (count($page_data['all_products']) != 0)

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data['all_products']);

		else

			$value = array("status" => "FAILED", "Message" => "No Products Found");

		exit(json_encode($value));
	}

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

		$sub 	= 0;

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

		$page_data['cur_brand'] 	   = $brand;

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

			$page_data['vendor_id']			= $para2;

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

			$config['per_page'] = 20;

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

			$page_data['page_name']        	= "vendor/public_profile";

			$page_data['content']        	= "featured";

			$page_data['vendor_id']			= $para1;

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

	function vendor($vendor_id)
	{

		if ($this->crud_model->get_settings_value('general_settings', 'vendor_system') !== 'ok') {

			redirect(base_url() . 'index.php/home');
		}

		$vendor_system	 =  $this->db->get_where('general_settings', array('type' => 'vendor_system'))->row()->value;

		if (
			$vendor_system	 == 'ok' &&

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

	/* FUNCTION: Loads Customer Profile Page */



	function profile($para1 = "", $para2 = "", $para3 = "", $para4 = "", $para5 = "")
	{
		//echo "dd".$para2; exit;
		if ($para1 == "info") {

			$page_data['user_info']     = $this->db->get_where('user', array('user_id' => $para2))->result_array();

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data);
			exit(json_encode($value));
		}
		if ($para1 == "wishlist") {
			$ids = implode(',', $this->db->get_where('user', array('user_id' => $para2))->row()->wishlist);
			$this->db->where_in('product_id', $ids);
			$page_data['wishlist'] = $this->db->get('product', 100, $para2)->result_array();
			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data);
			exit(json_encode($value));
		}
		if ($para1 == "order_history1") {
			$this->db->where('buyer', $para2);
			$page_data = $this->db->get('sale')->result_array();
			$as = 0;
			foreach ($page_data as $pg) {
				$product_details = json_decode($pg['product_details'], 1);
				$pg['product_details'] = '';
				foreach ($product_details as $pk) {
					$color = json_decode($pk['option']['color'], 1);

					$pk['option']['color'] = $color[0];
					$pk['name'] = $color[0];
					$pg['product_details'][] = $pk;
					$pid = $pk['id'];
					$pidData =  $this->db->get_where('product', array('product_id' => $pid))->result_array();
					//print_r($pidData);
					$pg['product_details'][$as]['product_id']  = $pid;
					$pg['product_details'][$as]['product_name']  = $pidData[0]['title'];
					$as++;
				}
				$pg['shipping_address'] = json_decode($pg['shipping_address'], 1);
				$pg['delivery_status'] = json_decode($pg['delivery_status'], 1);
				$data[] = $pg;
			}
			if (count($data) > 0) {
				$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $data);
			} else {
				$value = array("status" => "FAILED", "Message" => "FAILED", "Response" => $data);
			}

			exit(json_encode($value));
		}
		if ($para1 == "order_historyold") {
			$this->db->where('buyer', $para2);
			$page_data = $this->db->get('sale')->result_array();

			foreach ($page_data as $pg) {
				$product_details = json_decode($pg['product_details'], 1);
				$pg['product_details'] = '';
				$as = 0;
				foreach ($product_details as $pk) {
					$color = json_decode($pk['option']['color'], 1);

					$pk['option']['color'] = $color[0];
					$pk['name'] = $color[0];
					$pg['product_details'][] = $pk;
					$pid = $pk['id'];
					$pidData =  $this->db->get_where('product', array('product_id' => $pid))->result_array();
					//print_r($pidData);
					$pg['product_details'][$as]['product_id']  = $pid;
					$pg['product_details'][$as]['product_name']  = $pidData[0]['title'];
					$as++;
				}
				$pg['shipping_address'] = json_decode($pg['shipping_address'], 1);
				$pg['delivery_status'] = json_decode($pg['delivery_status'], 1);
				$data[] = $pg;
			}
			if (count($data) > 0) {
				$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $data);
			} else {
				$value = array("status" => "FAILED", "Message" => "FAILED", "Response" => $data);
			}

			exit(json_encode($value));
		}
		if ($para1 == "order_history11") {
			$this->db->where('buyer', $para2);
			$page_data = $this->db->get('sale')->result_array();
			//echo '<pre>'; print_r($returnproduct); exit;				
			foreach ($page_data as $pg) {
				$product_details = json_decode($pg['product_details'], 1);
				$pg['product_details'] = '';
				$as = 0;
				$funal_qty = 0;
				foreach ($product_details as $pk) {
					$color = json_decode($pk['option']['color'], 1);
					$pk['option']['color'] = $color[0];
					$pk['name'] = $color[0];
					$pg['product_details'][] = $pk;
					$pid = $pk['id'];
					$pidData =  $this->db->get_where('product', array('product_id' => $pid))->result_array();
					//print_r($pidData);
					$pg['product_details'][$as]['product_id']  = $pid;
					$pg['product_details'][$as]['product_name']  = $pidData[0]['title'];
					$funal_qty = $funal_qty + $pk['qty'];
					$as++;
				}
				$pg['funal_qty'] = $funal_qty;
				$pg['shipping_address'] = json_decode($pg['shipping_address'], 1);
				$pg['delivery_status'] = json_decode($pg['delivery_status'], 1);

				if ($pg['order_trackment'] == 0) {
					$pg['order_date'] = date('d M Y', $pg['sale_datetime']);
				}
				if ($pg['order_trackment'] == 1) {
					if ($pg['return_status'] == 1) {
						$pg['return_stat'] = "Return Request";
					} elseif ($pg['return_status'] == 2) {
						$pg['return_stat'] = "Return Acccepted";
					} elseif ($pg['return_status'] == 3) {
						$pg['return_stat'] = "Return Reject";
					}
				}
				if ($pg['cancel_status'] == 1) {
					$pg['cancel_stat'] = "Cancelled";
				} elseif ($pg['cancel_status'] == 2) {
					$pg['cancel_stat'] = "Waiting For Apporval";
				}
				if ($pg['order_trackment'] == 3) {
					$pg['delivery_date'] = date('d M Y', $pg['delivary_datetime']);

					//return
					$return = $this->crud_model->get_type_name_by_id('product', $pid, 'return_days');
					$deliverydate = date('d M Y', $pg['delivary_datetime']);
					$returnd = strtotime($deliverydate . "+" . $return . "days");
					$today = strtotime(date("d M Y"));
					if ($today <= $returnd) {
						$masg = 'Return policy valid till';
						$magsg1 = date('d M Y', $returnd);
						$pg['return'] = $masg . ' ' . $magsg1;
					} else {
						$pg['return'] = 'Return Closed';
					}
				}
				$data[] = $pg;
			}

			if (count($page_data) > 0) {
				$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $data);
			} else {
				$value = array("status" => "FAILED", "Message" => "FAILED", "Response" => $data);
			}
			exit(json_encode($value));
		}
		if ($para1 == "order_history") {
			// $this->db->where('buyer', $para2);
			// $this->db->order_by('sale_id', 'desc');
			// $page_data = $this->db->get('sale')->result_array();
			// //echo '<pre>'; print_r($page_data); exit;				
			// $pg= array();
			// foreach($page_data as $pg)
			// {
			// //
			// 	$product_details=json_decode($pg['product_details'],1);
			// 	//$pg['product_details']='';
			// 	$as = 0;
			// 	$funal_qty=0;

			// 	//$pk = array();


			// 	foreach($product_details as $pk)
			// 	{
			// 	    //echo '<br/>a'; print_r($pk);
			// 		$color=json_decode($pk['option']['color'],1);					
			// 		$pk['option']['color']=$color[0];
			// 		$pk['name']=$color[0];
			// 		$pid = $pk['id'];
			// 		$pg['product_details']=array($pk);
			// 		$pidData =  $this->db->get_where('product',array('product_id'=>$pid))->result_array();
			// 		$pg['product_details'][$as]['product_id']  = $pid;
			// 		$pg['product_details'][$as]['product_name']  = $pidData[0]['title'];
			// 		$funal_qty=$funal_qty+$pk['qty'];			
			// 		$as++;
			// 	}
			//     //echo '<br/>b'; print_r($pg); exit;
			// 	$pg['funal_qty']=$funal_qty;

			// 	$pg['shipping_address']=json_decode($pg['shipping_address'],1);
			// 	$pg['delivery_status']=json_decode($pg['delivery_status'],1);	


			// 	if($pg['order_trackment']==0) 
			// 	{
			// 	    $pg['order_date']=date('d M Y',$pg['sale_datetime']);
			// 	}
			// 	if($pg['order_trackment']==1) 
			// 	{
			// 	    if($pg['return_status']==1) 
			// 	    {
			// 	        $pg['return_stat']="Return Request";
			// 	    }
			// 	    elseif($pg['return_status']==2) 
			// 	    {
			// 	        $pg['return_stat']="Return Acccepted"; 
			// 	    }  
			// 	    elseif($pg['return_status']==3) 
			// 	    { 
			// 	        $pg['return_stat']="Return Reject"; 

			// 	    }
			// 	}
			// 	if($pg['cancel_status']==1) 
			// 	{
			// 	    $pg['cancel_stat']="Cancelled";
			// 	}
			// 	elseif($pg['cancel_status']==2) 
			// 	{
			// 	   $pg['cancel_stat']="Waiting For Apporval"; 
			// 	}



			// 	if($pg['order_trackment']==3) {
			// 	     $pg['delivery_date']=date('d M Y',$pg['delivary_datetime']);

			// 	     //return
			// 	     $return = $this->crud_model->get_type_name_by_id('product', $pid, 'return_days'); 
			//          $deliverydate = date('d M Y', $pg['delivary_datetime']);
			//          $returnd = strtotime($deliverydate."+".$return."days");
			//          $today = strtotime(date("d M Y"));
			//          if($today<=$returnd)
			//          {
			//              $masg='Return policy valid till';
			//              $magsg1=date('d M Y',$returnd);
			//              $pg['return']=$masg.' '.$magsg1; 
			//          }
			//          else
			//          {
			//              $pg['return']='Return Closed';
			//          }
			// 	}
			// 	$data[]=$pg;
			// }

			// if(count($page_data)>0)
			// {
			// 	$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$data);
			// }
			// else
			// {
			// 	$value=array("status"=>"FAILED","Message"=>"FAILED", "Response"=>$data);
			// }
			// exit(json_encode($value));

			$this->db->select('order_id, MAX(sale_id) as sale_id, store_id, MAX(sale_datetime) as sale_datetime, MAX(return_status) as return_status, MAX(order_trackment) as order_trackment, MAX(delivary_datetime) as delivary_datetime, MAX(cancel_status) as cancel_status, MAX(product_details) as product_details, MAX(lalamove_res) as lalamove_res, MAX(discount) as discount, SUM(grand_total) as grandtotal, SUM(vat) as tax');
			$this->db->where('buyer', $para2);
			$this->db->where_in('status', array('success', 'admin_pending'));
			$this->db->group_by('order_id, store_id');
			$this->db->order_by('sale_id', 'desc');
			$orders = $this->db->get('sale')->result_array();
			$data = [];
			if (count($orders) > 0) {
				foreach ($orders as $row1) {
					$as = 0;
					$final_qty = 0;
					$product_details = json_decode($row1['product_details'], true);
					foreach ($product_details as $prd) {
						$final_qty += $prd['qty'];
						$color = json_decode($prd['option']['color'], 1);
						$product_details[$as]['option']['color'] = $color[0];
						$product_details[$as]['name'] = $color[0];
						$product_details[$as]['product_name'] = $this->db->get_where('product', array('product_id' => $prd['id']))->row()->title;
						$product_details[$as]['Seller'] = $this->crud_model->product_by($prd['id']);
						$as++;
					}
					$row1['product_details'] = json_encode($product_details);
					$row1['final_qty'] = $final_qty;
					$tax = floatval($row1['tax']);
					$price = floatval($row1['grandtotal']);
					$discount = floatval($row1['discount']);
					$delivery_charge = 0;
					if ($row1['lalamove_res'] != "") {
						$lalamove_res = json_decode($row1['lalamove_res'], true);
						foreach ($lalamove_res as $key => $value) {
							if ($value != "") {
								$lalamove_res1 = json_decode($value, true);
								if ($lalamove_res1['data']['priceBreakdown']['total'] != "") {
									$delivery_charge += floatval($lalamove_res1['data']['priceBreakdown']['total']);
								}
							}
						}
					}
					$row1['store_name'] = $this->crud_model->get_type_name_by_id('vendor', $row1['store_id'], 'display_name');
					$row1['total_amount'] = $delivery_charge + $price - $discount;
					if ($row1['order_trackment'] == 0) {
						$row1['sale_datetime_display'] = date('d M Y', $row1['sale_datetime']);
					}
					if ($row1['order_trackment'] == 1) {
						if ($row1['return_status'] == 1) {
							$row1['return_status_1'] = "Request";
						} elseif ($row1['return_status'] == 2) {
							$row1['return_status_1'] = "Acccepted";
						} elseif ($row1['return_status'] == 3) {
							$row1['return_status_1'] = "Reject";
						}
					}
					if ($row1['cancel_status'] == 1) {
						$row1['cancel_status_1'] = "Cancelled";
					}
					if ($row1['order_trackment'] == 3) {
						$row1['delivary_datetime_1'] = date('d M Y', $row1['delivary_datetime']);
					}
					if ($row1['order_trackment'] == 4) {
						$row1['order_trackment_1'] = "Cancelled";
					}
					if ($row1['order_trackment'] == 5) {
						$row1['order_trackment_1'] = "Cancelled";
					}
					$row1['product_details'] = json_decode($row1['product_details'], 1);
					$row1['shipping_address'] = json_decode($row1['shipping_address'], 1);
					$row1['payment_status'] = json_decode($row1['payment_status'], 1);
					$row1['payment_details'] = json_decode($row1['payment_details'], 1);
					$row1['delivery_status'] = json_decode($row1['delivery_status'], 1);
					$row1['lalamove_res'] = json_decode($row1['lalamove_res'], 1);
					$data[] = $row1;
				}
				exit(json_encode(array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $data)));
			} else {
				exit(json_encode(array("status" => "FAILED", "Message" => "FAILED", "Response" => $data)));
			}
		}
		if ($para1 == "invoice_details") { //	echo $para4;
			$sale_details = $this->db->get_where('sale', array('sale_id' => $para4))->result_array();
			$orders_details  = $this->db->get_where('sale', array('order_id' => $para3, 'sale_id!=' => $para4))->result_array();
			// print_r($sale_details);
			foreach ($sale_details as $sale) {
				$info = json_decode($sale['shipping_address'], true);
				$sale['shipaddress'] = $info;
				$sale['delivery_status'] = json_decode($sale['delivery_status'], 1);
				$product_details = json_decode($sale['product_details'], true);
				foreach ($product_details as $row1) {
					$sale['tax'] = $row1['tax'] * $row1['qty'];
					$sale['shipping'] = $row1['shipping'];
					$sale['subtotal'] = $row1['subtotal'];
					$sale['qty'] = $row1['qty'];
					$sale['image'] = $row1['image'];
					$sale['name'] = $row1['name'];
					//$sale['productlink']=$this->crud_model->product_by($row1['id'],'with_link');
					$sale['total'] = currency($row1['tax'] + $row1['shipping'] + $row1['subtotal']);
					$sale['order_id'] = $sale['order_id'];
					$sale['sale_id'] = $sale['sale_id'];
					$sale['seller_name'] = $this->crud_model->product_by($row1['id'], '');
					if ($sale['order_trackment'] == 0) {
						$sale['orderdate'] = date('d M Y', $sale['sale_datetime']);
					}
					if ($sale['order_trackment'] == 1) {
						if ($sale['return_status'] == 1) {
							$sale['return_stat'] = "Return Request";
						} elseif ($sale['return_status'] == 2) {
							$sale['return_stat'] = "Return Acccepted";
						} elseif ($sale['return_status'] == 3) {
							$sale['return_stat'] = "Return Reject";
						}
					}
					if ($sale['cancel_status'] == 1) {
						$sale['cancel_stat'] = "Cancelled";
					} elseif ($sale['cancel_status'] == 2) {
						$sale['cancel_stat'] = "Waiting For Apporval";
					}
					if ($sale['order_trackment'] == 3) {
						$sale['delivery_date'] = date('d M Y', $sale['delivary_datetime']);

						//return
						$return = $this->crud_model->get_type_name_by_id('product', $row1['id'], 'return_days');
						$deliverydate = date('d M Y', $sale['delivary_datetime']);
						$sale['rate_review'] = $sale['review'];

						$returnd = strtotime($deliverydate . "+" . $return . "days");
						$today = strtotime(date("d M Y"));
						if ($today <= $returnd) {
							$masg = 'Return policy valid till';
							$magsg1 = date('d M Y', $returnd);
							$sale['return'] = $masg . ' ' . $magsg1;
						} else {
							$sale['return'] = 'Return Closed';
						}
					}
					if ($sale['order_trackment'] == 4) {
						$sale['cancel_stat'] = "your item has been cancelled by admin";
					}
					if ($sale['order_trackment'] == 5) {
						$sale['cancel_stat'] = "your item has been cancelled by vendor";
					}
					if ($sale['order_trackment'] == 6) {
						$sale['shippuing_stat'] = "Order Shipped";
					}
				}
				$data[] = $sale;
			}
			if (count($sale_details) > 0) {
				$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $data);
			} else {
				$value = array("status" => "FAILED", "Message" => "FAILED", "Response" => $data);
			}
			exit(json_encode($value));
		}
		if ($para1 == "tax_invoice") {
			//	echo $para4; 
			//  $sale_details = $this->db->get_where('sale',array('sale_id'=>$para4))->result_array();
			$orders_details  = $this->db->get_where('sale', array('order_id' => $para3, 'sale_id!=' => $para4))->result_array();
			$sale_details = $this->db->get_where('sale', array('sale_code' => $para4))->result_array();
			//echo $this->db->last_query(); 
			//print_r($sale_details); exit;
			$row = $sale_details[0];
			//print_r($row);
			$sold_by_type = explode('-', $row['sale_code']);
			if ($sold_by_type[0] == 'VE') {

				$vendor_id = $sold_by_type[1];
				$vendor_det = $this->db->get_where('vendor', array('vendor_id' => $vendor_id))->result_array();
				foreach ($vendor_det as $vendor) {
					$sale['display_name'] =  $vendor['display_name'];
					$sale['address1'] = $vendor['address1'];
					$sale['city'] = $vendor['store_city'];
					$sale['zip'] = $vendor['zip'];
					$sale['state'] = $vendor['store_district'];
					$sale['country'] = $vendor['store_country'];
					$sale['gst'] = $vendor['gst'];
					$sale['panno'] = $vendor['panno'];
				}
			} else {
				$sale['display_name'] = $this->db->get_where('general_settings', array('type' => 'system_name'))->row()->value;
				$sale['address1'] = $this->db->get_where('general_settings', array('type' => 'contact_address'))->row()->value;
			}
			$info = json_decode($row['shipping_address'], true);
			$sale['shipaddress'] = $info;
			$sale['state_name'] = $this->crud_model->get_type_name_by_id('state', $info['state'], 'name');
			$sale['state_ut_code'] = $this->crud_model->get_type_name_by_id('state', $info['state'], 'ut_code');
			$sale['place_of_supply'] = $this->crud_model->get_type_name_by_id('state', $info['state'], 'name');
			$sale['place_of_delivery'] = $this->crud_model->get_type_name_by_id('state', $info['state'], 'name');

			$sale['order_id'] = $row['order_id'];
			$sale['sale_date'] = date('d M, Y', $row['sale_datetime']);
			$sale['invoice_code'] = $row['invoice_code'];
			$sale['invocie_date'] = date('d M, Y', $row['sale_datetime']);
			$prod_count = 0;
			$total = 0;
			foreach ($sale_details as $details) {
				// $info = json_decode($details['shipping_address'],true);
				//$sale['shipaddress']=$info;
				//$sale['delivery_status']=json_decode($sale['delivery_status'],1);
				//$product_details = json_decode($sale['product_details'], true);
				$product_details = json_decode($details['product_details'], true);
				foreach ($product_details as $row1) {

					$prod_count++;



					//   $total += ($row1['tax']+$row1['price'])*$row1['qty'];


					$tax += $row1['tax'] * $row1['qty'];
					$shipping = $row1['shipping'];
					// $sale['subtotal'] = $row1['subtotal'];
					// $sale['qty'] = $row1['qty'];
					$sc1['image'] = $row1['image'];
					$sc1['name'] = $row1['name'];
					//$sale['productlink']=$this->crud_model->product_by($row1['id'],'with_link');
					// $sale['total'] = currency($row1['tax']+$row1['shipping']+$row1['subtotal']);
					$total += ($row1['tax'] + $row1['price']) * $row1['qty'];
					$sc1['hsn_code'] = $this->crud_model->get_type_name_by_id('product', $row1['id'], 'hsn_id');
					$sc1['unit_price'] = round($row1['price']);
					$sc1['qty'] = $row1['qty'];
					$sc1['net_amt'] = round($row1['price'] * $row1['qty']);
					$sc1['product_name'] = $row1['name'];
					$sc1['sno'] = $prod_count;
					if ($row1['applied_gst'] == 'outer_country') {
						$sc1['tax_type'] = 'IGST';
					} else {
						$sc1['tax_type'] = 'CGST<br>SGST';
					}

					$tax_rate = $row1['tax_rate'];

					if ($row1['applied_gst'] == 'outer_country') {
						$sc1['tax_rate'] = $tax_rate . '%';
					} else {

						$sc1['tax_rate'] = ($tax_rate / 2) . '%<br>' . ($tax_rate / 2) . '%';
					}

					$tax_amount = $this->cart->format_number($row1['tax'] * $row1['qty']);

					if ($row1['applied_gst'] == 'outer_country') {

						$sc1['tax_amount'] = $tax_amount;
					} else {
						$sc1['tax_amount'] = ($tax_amount / 2) . '<br>' . currency() . ($tax_amount / 2);
					}

					$sc1['row_total'] = $this->cart->format_number(($row1['price'] + $row1['tax']) * $row1['qty']);
					// $sale['order_id']=$sale['order_id'];
					// $sale['sale_id']=$sale['sale_id'];
					$sc1['seller_name'] = $this->crud_model->product_by($row1['id'], '');


					if ($row['shipping'] > 0) {
						$vend_count = $this->db->get_where('sale', array('total_invoice_id' => $row['total_invoice_id']))->num_rows();
						$ship = $this->crud_model->get_type_name_by_id('business_settings', '2', 'value');
						$shipamount = $ship / $vend_count;
						$shipping_tax_amount = 18 / 100 * $shipamount;
						$sc1['shipping_amount'] = $shipping_amount = $row['shipping'] - $shipping_tax_amount;
					} else {
						$shipping_tax_amount = 0;
					}

					if ($row1['applied_gst'] == 'outer_country') {
						$sc1['shiiping_tax_type'] = 'IGST';
					} else {
						$sc1['shiiping_tax_type'] = 'CGST<br>SGST';
					}

					$shipping_tax_rate = 18;
					if ($row1['applied_gst'] == 'outer_country') {
						$sc1['shiiping_tax_rate'] = $shipping_tax_rate . "%";
					} else {
						$sc1['shiiping_tax_rate'] = ($shipping_tax_rate / 2) . '%<br>' . ($shipping_tax_rate / 2) . '%';
					}

					if ($row1['applied_gst'] == 'outer_country') {
						$sc1['shiiping_tax_amount'] = $shipping_tax_amount;
					} else {
						$sc1['shiiping_tax_amount'] = ($shipping_tax_amount / 2) . '<br>' . currency() . ($shipping_tax_amount / 2);
					}
					$sc1['tot_shiiping'] = round($row['shipping']);


					$sale['order_info'][] = $sc1;
				}
				$sale['tot_tax'] = $tax + $sc1['shiiping_tax_amount'];
				$sale['fnal_total'] = $this->cart->format_number($total + $shipping);
				$sale['amount_in_word'] = $this->crud_model->getIndianCurrency($this->cart->format_number($sale['fnal_total']));
				$data['invoice'] = $sale;
			}
			if (count($sale_details) > 0) {
				$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $data);
			} else {
				$value = array("status" => "FAILED", "Message" => "FAILED", "Response" => $data);
			}
			exit(json_encode($value));
		} elseif ($para1 == "downloads") {

			//$this->load->view('front/user/downloads');

		}
		if ($para1 == "update_profile") {

			$page_data['user_info']     = $this->db->get_where('user', array('user_id' => $para2))->result_array();

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data);
			exit(json_encode($value));
		} elseif ($para1 == "ticket") {
			$this->load->view('front/user/ticket');
		} elseif ($para1 == "message_box") {
			$page_data['ticket']  = $para2;
			$this->db->where('from_where', '{"type":"user","id":"' . $para2 . '"}');
			$msgs  = $this->db->get_where('ticket')->result_array();
			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $msgs);
			exit(json_encode($value));
		}
		if ($para1 == "message_view") {

			$page_data['ticket']  = $para2;

			$page_data['message_data'] = $this->db->get_where('ticket', array(

				'ticket_id' => $para2

			))->result_array();

			$this->crud_model->ticket_message_viewed($para2, 'user');

			$this->load->view('front/user/message_view', $page_data);
		}
		if ($para1 == "address_view") {
			try{
			$addresslist = $this->db->get_where('shipping_address', array('unique_id' => $para2))->result_array();
			foreach ($addresslist as $al) {
				$res['unique_id'] = $al['unique_id'];
				$res['name'] = $al['name'];
				$res['address'] = $al['address'];
				$res['address2'] = $al['address1'];
				$res['country'] = $al['country'];
				//	$res['country_code']=$al['country_code'];
				$res['mobile'] = $al['mobile'];
				$res['zip_code'] = $al['zip_code'];
				// $res['state'] = $this->db->get_where('state', array('state_id' => $al['state']))->row()->name;
				$res['state'] =  $al['state'];
				//	$res['instruction']=$al['instruction'];
				$res['email'] = $al['email'];
				$res['city'] = $al['city'];
				//	$res['district']=$al['district'];
				//	$res['delivery_method']=$al['delivery_method'];
				$response[] = $res;
			}
			if(count($res)==0){
				exit(json_encode(array(
					"status" => "FAILED",
					"message" => "Invalid Address Details",
					"response" => null
				)));
			}
			$value = array(
				"status" => "SUCCESS",
				"message" => "SUCCESS",
				"response" => $response
			);
			exit(json_encode($value));
		}
		catch(Exception $e){
			exit(json_encode(array(
				"status" => "FAILED",
				"message" => $e->getMessage(),
				"response" => $e
			)));
		}
		}
		if ($para1 == "add_address") {

			try{
			$datas = json_decode($this->input->raw_input_stream, 1);
			$data['user_id'] = $datas['userid'];
			$query = $this->db->get_where('shipping_address', array(
				'user_id' => $data['userid'], 'set_default' => "1"
			));
			if ($query->num_rows() > 0) {
				$data['set_default'] = "0";
			} else {
				$data['set_default'] = "1";
			}
			$data['name'] = $datas['name'];
			$data['mobile'] = $datas['mobile'];
			$data['email'] = $datas['email'];
			$data['address'] = $datas['street_address'];
			$data['address1'] = $datas['street_address2'];
			$data['city'] = $datas['city'];
			//	$data['district']=$datas['district'];
			$data['zip_code'] = $datas['zip_code'];
			$data['state'] = $datas['state'];
			$data['country'] = $datas['country'];
			//	$data['instruction']=$datas['instruct'];
			$unicid = 'SHIP' . substr(time(), 4) . rand(100000, 999999);
			$data['unique_id'] = $unicid;

			$data['latitude'] = $datas['latitude'];
			$data['longitude'] = $datas['longitude'];

			$count = $this->db->get_where('shipping_address',['user_id'=> $data['user_id'],'name'=> $data['name'], 'mobile'=> $data['mobile'],'email'=> $data['email'],'address'=> $data['address'],'address1'=> $data['address1'],'city'=> $data['city'],'zip_code'=> $data['zip_code'], 'state'=> $data['state'],'country'=> $data['country'],'latitude'=> $data['latitude'],'longitude'=> $data['longitude']])->result_array();
		
			if(count($count)>=1){
				$value = array(
					"status" => "FAILED",
					"Message" => "Address Already Exist",
					"Response" => $count[0]['unique_id'],
				);
				exit(json_encode($value));
			}

			$this->db->insert('shipping_address', $data);
			//echo $this->db->last_query();
			$value = array(
				"status" => "SUCCESS",
				"message" => "Address stored Successfully",
				"response" => $unicid
			);
			exit(json_encode($value));
		}catch(Exception $ex){
			exit(json_encode( array(
				"status" => "FAILED",
				"message" => $e->getMessage(),
				"response" => $e
			)));
		}
		}
		if ($para1 == "edit_address") {
			try{
			$datas = json_decode($this->input->raw_input_stream, 1);
			$data['user_id'] = $datas['userid'];
			$data['name'] = $datas['name'];
			$data['mobile'] = $datas['mobile'];
			$data['email'] = $datas['email'];
			$data['address'] = $datas['street_address'];
			$data['address1'] = $datas['street_address2'];
			$data['city'] = $datas['city'];
			//	$data['district']=$datas['district'];
			$data['state'] = $datas['state'];
			//$data['country ']=$datas['country'];
			$data['country'] = $datas['country'];
			$data['zip_code'] = $datas['zip_code'];
			//	$data['country ']=$datas['country'];
			//	$data['instruction']=$datas['instruct'];
			$this->db->where('unique_id', $datas['unique_id']);
			$res = $this->db->update('shipping_address', $data);
			//echo $this->db->last_query();
			$value = array(
				"status" => "SUCCESS",
				"message" => "Address Updated Successfully..!",
				"response" => $datas['unique_id']
			);
			exit(json_encode($value));
		}
		catch(Exception $e){
			exit(json_encode(array(
				"status" => "FAILED",
				"message" => $e->getMessage(),
				"response" => $e
			)));
		}
		}
		if ($para1 == 'delete_address') {
			try{
			$this->db->where('unique_id', $para2);
			$this->db->delete('shipping_address');
			$value = array(
				"status" => "SUCCESS",
				"message" => "Address Removed Successfully"
			);
			exit(json_encode($value));
		}catch(Exception $e){
			exit(json_encode(["status"=>"FAILED","message"=>$e->getMessage(),"response"=>$e]));
		}
		}
		if ($para1 == "address_list") {
			try{
			$addresslist = $this->db->get_where('shipping_address', array('user_id' => $para3))->result_array();
			foreach ($addresslist as $al) {
				$res['unique_id'] = $al['unique_id'];
				$res['name'] = $al['name'];
				$res['address'] = $al['address'];
				$res['address1'] = $al['address1'];
				$res['country'] = $al['country'];
				// $res['state'] = $this->db->get_where('state', array('state_id' => $al['state']))->row()->name;
				$res['state'] = $al['state'];
				//	$res['country_code']=$al['country_code'];
				$res['mobile'] = $al['mobile'];
				$res['zip_code'] = $al['zip_code'];
				//	$res['instruction']=$al['instruction'];
				$res['email'] = $al['email'];
				$res['city'] = $al['city'];
				//	$res['district']=$al['district'];
				$response[] = $res;
			}
			$value = array(
				"status" => "SUCCESS",
				"message" => "SUCCESS",
				"response" => $response
			);
			exit(json_encode($value));
		}
		catch(Exception $e){
			exit(json_encode(["status"=>"FAILED","message"=>$e->getMessage(),"response"=>$e]));
		}
		}
		


		if ($para1 == "returnproducts_list") {
			$this->db->where('user_id', $para2);
			$page_data = $this->db->get('gr_return_order')->result_array();
			$data1 = '';
			foreach ($page_data as $pg) {
				$data1['ordered_date'] = $pg['ordered_date'];
				$data1['remark'] = $pg['damage_product_description'];
				$data1['order_id'] = $pg['order_id'];
				$data1['return_qty'] = $pg['return_qty'];
				$data1['p_name'] = $pg['p_name'];
				$data1['return_status'] = $pg['return_status'];
				$data1['return_reason'] = $pg['return_reason'];
				$return_reason = $this->db->get_where('return_reason', array('id' => $data1['return_reason']))->result_array();
				//echo '<pre>'; print_r($return_reason); exit;
				$data1['reason'] = $return_reason[0]['return_reason'];
				$data2[] = $data1;
			}

			if (count($data1) > 0) {
				$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $data2);
			} else {
				$value = array("status" => "FAILED", "Message" => "FAILED", "Response" => $data2);
			}

			exit(json_encode($value));
		}
		if ($para1 == "replaceproducts_list") {
			$this->db->where('user_id', $para2);
			$page_data = $this->db->get('gr_replace_order')->result_array();
			$data1 = '';
			foreach ($page_data as $pg) {
				$data1['ordered_date'] = $pg['ordered_date'];
				$data1['remark'] = $pg['damage_product_description'];
				$data1['order_id'] = $pg['order_id'];
				$data1['return_qty'] = $pg['return_qty'];
				$data1['p_name'] = $pg['p_name'];
				$data1['return_status'] = $pg['return_status'];
				$return_reason = $this->db->get_where('return_reason', array('id' => $data1['return_reason']))->result_array();
				$data1['reason'] = $return_reason[0]['return_reason'];
				$data2[] = $data1;
			}

			if (count($data1) > 0) {
				$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $data2);
			} else {
				$value = array("status" => "FAILED", "Message" => "FAILED", "Response" => $data2);
			}
			exit(json_encode($value));
		}
		if ($para1 == "cancelproducts_list") {
			$this->db->where('user_id', $para2);
			$page_data = $this->db->get('gr_cancel_order')->result_array();
			$data1 = '';
			foreach ($page_data as $pg) {
				$data1['ordered_date'] = $pg['ordered_date'];
				$data1['payment_method'] = $pg['payment_method'];
				$data1['order_id'] = $pg['order_id'];
				$data1['remark'] = $pg['message'];
				$data1['cancel_reason'] = $pg['cancel_reason'];
				$data1['cancel_status'] = $pg['cancel_status'];
				$return_reason = $this->db->get_where('cancel_reason', array('id' => $data1['cancel_reason']))->result_array();
				$data1['reason'] = $return_reason[0]['cancel_reason'];
				$prod_inof = json_decode($pg['product_details'], true);
				//echo '<pre>'; print_r($prod_inof); exit;
				foreach ($prod_inof as $ps) {
					//echo '<pre>'; print_r($ps);
					$data1['productname'] 		= $ps['name'];
					$data1['product_id'] 		= $ps['id'];
				}
				$data2[] = $data1;
			}
			if (count($data1) > 0) {
				$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $data2);
			} else {
				$value = array("status" => "FAILED", "Message" => "FAILED", "Response" => $data2);
			}
			exit(json_encode($value));
		}
	}
	function return_reason()
	{
		$retrun_reason = array();
		$retrun_reason = $this->db->get_where('return_reason')->result_array();
		if (count($retrun_reason) != 0) {
			foreach ($retrun_reason as $sc) {
				$sc1 = array('id' => $sc['id'], 'return_reason' => $sc['return_reason'], 'status' => $sc['status']);
				$row['retrun_reason'][] = $sc1;
			}
			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $row);
		} else {
			$value = array("status" => "FAILED", "Message" => "FAILED", "Response" => $row);
		}
		exit(json_encode($value));
	}

	function cancel_reason()
	{
		$cancel_reason = array();
		$cancel_reason = $this->db->get_where('cancel_reason')->result_array();
		if (count($cancel_reason) != 0) {
			foreach ($cancel_reason as $sc) {
				$sc1 = array('id' => $sc['id'], 'cancel_reason' => $sc['cancel_reason'], 'status' => $sc['status']);
				$row['cancel_reason'][] = $sc1;
				//$response['category'][]=$row;
			}
			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $row);
		} else {
			$value = array("status" => "FAILED", "Message" => "FAILED", "Response" => $row);
		}
		exit(json_encode($value));
	}
	function get_vendor_product($para2 = '')
	{
		$this->db->where('added_by', '{"type":"vendor","id":"' . $para2 . '"}');
		$gr_vendor = $this->db->get('product')->result_array();
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $gr_vendor);
		exit(json_encode($value));
	}

	function getBalance()

	{

		$datas = json_decode($this->input->raw_input_stream, 1);

		$user_id = $datas['userID'];

		$response['balance'] = $this->db->get_where('user_login', array('id' => $user_id))->row()->balance;

		$results['status'] = 'SUCCESS';

		$results['Response'] = $response;

		echo json_encode($results, true);

		exit;
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



	function add_review()
	{
		try{
			
		$datas = json_decode($this->input->raw_input_stream, 1);
		
		if (isset($datas['pid']) && $datas['pid'] != '' && isset($datas['user_id']) && $datas['user_id'] != '') {
			
			$total_review_count = $this->db->get_where('review_product', array('product_id' => $datas['pid'], 'user_id'=>$datas['user_id'],'order_id'=>$datas['order_id'], 'sale_id'=>$datas['sale_id']))->num_rows();
			if($total_review_count>0){
				echo json_encode(['status'=> 'FAILED', 'message'=>"You already submitted the review for this order", 'response'=> null]);
				exit();
			}

			$data['product_id'] 	= $datas['pid'];
			$data['user_id'] 		= $datas['user_id'];
			$data['title'] 			= $datas['title'];
			$data['rating'] 		= $datas['rating'];
			$data['description'] 	= $datas['description'];
			$data['order_id'] 		= $datas['order_id'];
			$data['sale_id'] 		= $datas['sale_id'];
			$data['status'] 		= 0;
			//	$data['time_format'] 		= time();


			$this->db->insert('review_product', $data);
			// $this->email_model->user_review_mail($data['user_id'], $data['rating'], $data['description'], $data['product_id'], $data['order_id']);
			//echo $this->db->last_query();
			$product_reviews = $this->db->insert_id();
			$datasr['review'] = 1;
			$this->db->where('sale_id', $data['sale_id']);
			$this->db->update('sale', $datasr);
			// echo $this->db->last_query();


			$results['status'] = 'Success';
			$results['message'] = 'Review Submitted Successfully';
			$results['Response'] = 'Review Submitted Successfully';
			echo json_encode($results, true);
			exit;
		} else {
			$results['status'] = 'FAILED';
			$results['message'] = 'Failed to Submit your Review';
			$results['Response'] = 'Parameter Missing';
			echo json_encode($results, true);
			exit();
		}
	}catch(Exception $e){
		echo json_encode(['status'=> 'FAILED', 'message'=>$e->getMessage(), 'response'=> $e]);
	}
	}

	function viewReviewByProductId($para1 = '')
	{
		if ($para1 != '') {
			$total_review_count      = $this->db->get_where('review_product', array('status' => 1, 'product_id' => $para1))->num_rows();

			if ($total_review_count > 0) {
				$review_data     = $this->db->get_where('review_product', array('status' => 1, 'product_id' => $para1))->result_array();
				foreach ($review_data as $sc) {
					$sc1 = array('id' => $sc['id'], 'rating' => $sc['rating'], 'title' => $sc['title'], 'description' => $sc['description'], 'review_date' => date('d M, Y', strtotime($sc['created_date'])), 'username' => $this->crud_model->get_type_name_by_id('user', $sc['user_id'], 'username'), 'user_img' => base_url() . 'uploads/user_image/user_' . $sc['user_id'] . '.jpg');
					$row['cancel_reason'][] = $sc1;
				}
				//	echo  $this->db->last_query();
				$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $row);
				exit(json_encode($value));
			} else {
				$value = array("status" => "Failed", "Message" => "Failed", "Response" => "No Record Found");
				exit(json_encode($value));
			}
		} else {
			$value = array("status" => "Failed", "Message" => "Failed", "Response" => "Product Id Missing");
			exit(json_encode($value));
		}
	}

	function getProductReviews(){
		try{
		$reviews=$this->db->get_where('review_product',array('product_id'=>$this->input->post('product_id'), 'status'=> 1))->result_array();
		echo json_encode(['status'=>'SUCCESS', 'message'=>'SUCCESS','response'=>$reviews]);
	}
	catch(Exception $e){
			echo json_encode(['status'=>'FAILED', 'message'=>$e->getMessage(),'response'=>$reviews]);
		}
		
	}
	function defaul_store($para1 = '')
	{

		$store_info      = $this->db->get_where('vendor', array('default_set' => 'ok'))->result_array();


		foreach ($store_info as $sc) {
			$sc1 = array('store_id' => $sc['vendor_id'], 'store_name' => $sc['name'], 'zip_code' => $sc['zip']);
			$row['default_store'][] = $sc1;
		}
		//	echo  $this->db->last_query();
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $row);
		exit(json_encode($value));
	}

	function ticket_message_add()
	{

		$datas = json_decode($this->input->raw_input_stream, 1);

		//echo '<pre>'; print_r($datas); exit;

		if (isset($datas['sub']) && $datas['sub'] != '' && isset($datas['user_id']) && $datas['user_id'] != ''  && isset($datas['reply']) && $datas['reply'] != '') {

			$data['time'] 			= time();

			$data['subject'] 		= $datas['sub'];

			$id              		= $datas['user_id'];

			$data['from_where'] 	= json_encode(array('type' => 'user', 'id' => $id));

			$data['to_where'] 		= json_encode(array('type' => 'admin', 'id' => ''));

			$data['view_status'] 	= 'ok';

			$this->db->insert('ticket', $data);

			$ticket_id = $this->db->insert_id();

			$data1['message'] = $datas['reply'];

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

			$results['status'] = 'SUCCESS';

			$results['Response'] = 'Ticket Message Send Successfully';

			echo json_encode($results, true);

			exit;
		} else {
			$results['status'] = 'FAILED';

			$results['Response'] = 'Parameter Missing';

			echo json_encode($results, true);
		}
	}

	function ticket_reply()
	{

		$datas = json_decode($this->input->raw_input_stream, 1);

		//$this->form_validation->set_rules('reply', 'Message', 'required');
		//echo '<pre>'; print_r($datas);
		$datas['mid'] = $datas['para1'];
		if (!isset($datas['mid']) && $datas['mid'] == '' || !isset($datas['reply']) && $datas['reply'] == '') {
			$results['status'] = 'FAILED';
			$results['Response'] = 'Parameter Missing';
			echo json_encode($results, true);
		} else {
			$data['message'] = $datas['reply'];
			$data['time'] = time();
			if (!empty($this->db->get_where('ticket_message', array('ticket_id' => $datas['para1']))->row()->ticket_id)) {
				$data['from_where'] = $this->db->get_where('ticket_message', array('ticket_id' => $datas['para1']))->row()->from_where;
				$data['to_where'] = $this->db->get_where('ticket_message', array('ticket_id' => $datas['para1']))->row()->to_where;
			} else {
				$data['from_where'] = $this->db->get_where('ticket', array('ticket_id' => $datas['para1']))->row()->from_where;
				$data['to_where'] = $this->db->get_where('ticket', array('ticket_id' => $datas['para1']))->row()->to_where;
			}

			$data['ticket_id'] = $datas['para1'];
			$data['view_status'] = json_encode(array('user_show' => 'ok', 'admin_show' => 'no'));
			$data['subject']  = $this->db->get_where('ticket', array('ticket_id' => $datas['para1']))->row()->subject;
			$this->db->insert('ticket_message', $data);
			$results['status'] = 'SUCCESS';
			$results['Response'] = 'Replay Message Send Successfully';
			echo json_encode($results, true);
		}
	}

	function ticket_listed($para2 = '')

	{

		$this->load->library('Ajax_pagination');

		$id = $this->session->userdata('user_id');

		$this->db->where('from_where', '{"type":"user","id":"' . $id . '"}');

		$this->db->or_where('to_where', '{"type":"user","id":"' . $id . '"}');

		$config['total_rows'] 	= $this->db->count_all_results('ticket');

		$config['base_url']   	= base_url() . 'index.php/home/ticket_listed/';

		$config['per_page'] 	= 5;

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

	function order_listed($para3 = '')

	{
		$id = $para3;


		$this->db->where('buyer', $id);


		$page_data = $this->db->get('sale')->result_array();

		//echo $this->db->last_query();

		foreach ($page_data as $pg) {

			$product_details = json_decode($pg['product_details'], 1);

			$pg['product_details'] = '';

			//print_r($product_details); exit;

			foreach ($product_details as $pk) {

				$color = json_decode($pk['option']['color'], 1);

				$pk['option']['color'] = $color[0];



				$pk['name'] = $color[0];

				$pg['product_details'][] = $pk;
			}

			$pg['shipping_address'] = json_decode($pg['shipping_address'], 1);

			$pg['delivery_status'] = json_decode($pg['delivery_status'], 1);

			$data[] = $pg;
		}

		if (count($data) > 0)

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $data);

		else

			$value = array("status" => "FAILED", "Message" => "FAILED", "Response" => $data);

		exit(json_encode($value));
	}

	function wish_listed($para2 = '', $para3 = '')

	{

		$this->load->library('Ajax_pagination');

		$id = $para3;

		$ids = json_decode($this->db->get_where('user_login', array('id' => $id))->row()->wishlist, true);

		$this->db->where_in('product_id', $ids);

		$config['total_rows']   = $this->db->count_all_results('product');;

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

		$ids = json_decode($this->db->get_where('user_login', array('id' => $id))->row()->wishlist, true);

		$this->db->where_in('product_id', $ids);

		$page_data['query'] = $this->db->get('product', $config['per_page'], $para2)->result_array();

		$this->load->view('front/user/wish_listed', $page_data);
	}

	function downloads_listed($para2 = '')

	{

		$this->load->library('Ajax_pagination');

		$id = $this->session->userdata('user_id');

		$downloads = json_decode($this->db->get_where('user_login', array('id' => $id))->row()->downloads, true);

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

	function category($para1 = "", $para2 = "", $para3 = "", $min = "", $max = "", $text = '')

	{

		//echo "p2".$para2;
		if ($para2 == "0") {
			$this->db->order_by('product_id', 'desc');
			$page_data['all_products1'] = $this->db->get_where('product', array('category' => $para1, 'store_id' => $para3))->result_array();
			//echo $this->db->last_query(); exit;

		} else if ($para2 != "") {
			$this->db->order_by('product_id', 'desc');
			$page_data['all_products1'] = $this->db->get_where('product', array('sub_category' => $para2, 'store_id' => $para3))->result_array();
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
		//print_r($brand_sub); exit;
		$sub 	= 0;

		$brand  = 0;

		if (isset($brand_sub[0])) {

			$sub = $brand_sub[0];
		}

		if (isset($brand_sub[1])) {

			$brand = $brand_sub[1];
		}

		//echo $this->db->last_query();

		foreach ($page_data['all_products1'] as $p) {



			$product_id = $p['product_id'];

			if ($p['discount'] == '') {

				$p['discount'] = 0.00;
			}


			$p['banner'] = $this->crud_model->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');

			$temppProduct[] = $p;
		}

		$page_data['all_products'] = $temppProduct;

		unset($page_data['all_products1']);

		if (count($page_data['all_products']) != 0)

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data['all_products']);

		else

			$value = array("status" => "FAILED", "Message" => "No Products Found");

		exit(json_encode($value));
	}

	function category_brand($para1 = "", $para2 = "", $min = "", $max = "", $text = '')

	{

		if ($para2 != "") {
			$brand_sub = explode('-', $para2);
			//print_r($brand_sub); exit;
			$sub 	= 0;

			$brand  = 0;

			if (isset($brand_sub[0])) {

				$sub = $brand_sub[0];
			}

			if (isset($brand_sub[1])) {

				$brand = $brand_sub[1];
			}

			$page_data['all_products1'] = $this->db->get_where('product', array('category' => $para1, 'brand' => $brand))->result_array();
		}



		$type = 'other';

		//echo $this->db->last_query();



		foreach ($page_data['all_products1'] as $p) {



			$product_id = $p['product_id'];

			if ($p['discount'] == '') {

				$p['discount'] = 0.00;
			}


			$p['banner'] = $this->crud_model->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');

			$temppProduct[] = $p;
		}

		$page_data['all_products'] = $temppProduct;

		unset($page_data['all_products1']);

		if (count($page_data['all_products']) != 0)

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data['all_products']);

		else

			$value = array("status" => "FAILED", "Message" => "No Products Found");

		exit(json_encode($value));
	}

	// function all_category($para1 = "")
	// {
	//    // echo "a"; exit;
	// 	$categories=$this->db->get('category')->result_array();
	// //	$order = array(77, 72, 73, 76, 74, 75,90);
	// //	 usort($categories, function ($a, $b) use ($order) {
	//                                  //   $pos_a = array_search($a['category_id'], $order);
	//                                 //    $pos_b = array_search($b['category_id'], $order);
	//                                  //   return $pos_a - $pos_b;
	//                              //   });
	// 	foreach($categories as $row)

	// 	{

	// 		if($this->crud_model->if_publishable_category($row['category_id'])){

	// 			$row['count']=$this->crud_model->is_publishable_count('category',$row['category_id']);

	// 			 $sub_categories=$this->db->get_where('sub_category',array('category'=>$row['category_id']))->result_array();

	// 			//echo '<pre>'; print_r($sub_categories); exit;
	// 			foreach($sub_categories as $sc)
	// 			{ 
	// 			$sc1= array('category_id'=>$row['category_id'],'sub_category_id'=>$sc['sub_category_id'],'sub_category_name'=>$sc['sub_category_name'],'banner'=>base_url().'uploads/sub_category_image/'.$sc['banner']);
	// 			$row['sub_category'][]=$sc1;
	// 			}
	// 			//sub_category_image


	// 			//echo '<pre>'; print_r($row['sub_category']);

	// 			$row['banner']=base_url().'uploads/category_image/'.$row['banner'];

	// 			$response['category'][]=$row;




	// 		}

	// 	}

	// 	$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$response);

	// 	exit(json_encode($value));

	// }

	function all_category($para1 = "")
	{
		$vendor_id = ($_GET['vendor_id'] != '') ? $_GET['vendor_id'] : $_POST['vendor_id'];
		$this->db->select('category.*');
		$this->db->from('category');
		$this->db->join('product', 'category.category_id = product.category');
		$this->db->where('product.store_id', $vendor_id);
		$this->db->group_by('category.category_id');
		$categories = $this->db->get()->result_array();
		foreach ($categories as $row) {
			if ($this->crud_model->if_publishable_category($row['category_id'])) {
				$row['count'] = $this->crud_model->is_publishable_count('category', $row['category_id']);
				$sub_categories = $this->db->get_where('sub_category', array('category' => $row['category_id']))->result_array();
				foreach ($sub_categories as $sc) {
					$sc1 = array('category_id' => $row['category_id'], 'sub_category_id' => $sc['sub_category_id'], 'sub_category_name' => $sc['sub_category_name'], 'banner' => base_url() . 'uploads/sub_category_image/' . $sc['banner']);
					$row['sub_category'][] = $sc1;
				}
				$row['banner'] = base_url() . 'uploads/category_image/' . $row['banner'];
				$response['category'][] = $row;
			}
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);
		exit(json_encode($value));
	}


	function all_store($para1 = "")
	{





		$store = $this->db->get_where('vendor', array('status' => 'approved'))->result_array();

		//echo '<pre>'; print_r($sub_categories); exit;
		foreach ($store as $sc) {
			$sc1 = array('store_id' => $sc['vendor_id'], 'store_name' => $sc['name'], 'address' => $sc['city'] . ',' . $sc['state'] . ',' . $sc['country'], 'banner' => base_url() . 'uploads/vendor_logo_image/logo_' . $sc['vendor_id'] . '.png');
			$row['all_stores'][] = $sc1;
		}
		//sub_category_image


		//echo '<pre>'; print_r($row['sub_category']);

		//	$row['banner']=base_url().'uploads/category_image/'.$row['banner'];

		$response['browse_by_shop'] = $row;








		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function shipping_address($para1 = "")
	{





		$store = $this->db->get_where('shipping_address', array('user_id' => $para1))->result_array();

		//echo '<pre>'; print_r($sub_categories); exit;
		foreach ($store as $sc) {
			$sc1 = array('id' => $sc['id'], 'unique_id' => $sc['unique_id'], 'name' => $sc['name'], 'address' => $sc['address'], 'address1' => $sc['address1'], 'country' => $sc['country'], 'mobile' => $sc['mobile'], 'zip_code' => $sc['zip_code'], 'email' => $sc['email'], 'city' => $sc['city'], 'state' => $sc['state'], 'set_default' => $sc['set_default']);
			$row['shipping_address'][] = $sc1;
		}
		//sub_category_image


		//echo '<pre>'; print_r($row['sub_category']);

		//	$row['banner']=base_url().'uploads/category_image/'.$row['banner'];

		$response['user_address'] = $row;








		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function check_delivery_address($para1 = "", $para2 = "")
	{
		$store = $this->db->get_where('shipping_address', array('user_id' => $para1))->result_array();

		//echo '<pre>'; print_r($sub_categories); exit;
		foreach ($store as $sc) {

			$this->db->select('*');
			$this->db->from('vendor');
			$this->db->where('status', 'approved');
			$this->db->where('delivery', 'yes');
			$this->db->where('vendor_id', $para2);
			$this->db->like('delivery_zipcode', $sc['zip_code']);

			$allStores = $this->db->get()->result_array();
			//echo $this->db->last_query();
			if (!empty($allStores)) {

				$del_status = "Available";
			} else {

				$del_status = "Unavailable";
			}
			$sc1 = array('id' => $sc['id'], 'unique_id' => $sc['unique_id'], 'name' => $sc['name'], 'address' => $sc['address'], 'address1' => $sc['address1'], 'country' => $sc['country'], 'mobile' => $sc['mobile'], 'zip_code' => $sc['zip_code'], 'email' => $sc['email'], 'city' => $sc['city'], 'state' => $sc['state'], 'set_default' => $sc['set_default'], 'status' => $del_status);
			$row['shipping_address'][] = $sc1;
		}
		//sub_category_image


		//echo '<pre>'; print_r($row['sub_category']);

		//	$row['banner']=base_url().'uploads/category_image/'.$row['banner'];

		$response['browse_by_shop'] = $row;








		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}
	function store_pickup_info($para1 = "")
	{
		$store = $this->db->get_where('pickup_slot', array('vendor_id' => $para1))->result_array();

		//echo '<pre>'; print_r($sub_categories); exit;
		foreach ($store as $sc) {
			$store_inf = $this->db->get_where('vendor', array('vendor_id' => $para1))->result_array();
			$sc1 = array('store_id' => $sc['vendor_id'], 'id' => $sc['id'], 'strat_time' => $sc['slot_start'], 'end_time' => $sc['slot_end'], 'interval_in_minute' => $sc['interval_in_minute'], 'slot_max_order' => $sc['max_order'], 'available_days' => $sc['available_days'], 'store_name' => $store_inf[0]['name'], 'city' => $store_inf[0]['city'], 'state' => $store_inf[0]['state'], 'country' => $store_inf[0]['country']);
			$row['all_stores'][] = $sc1;
		}
		//sub_category_image


		//echo '<pre>'; print_r($row['sub_category']);

		//	$row['banner']=base_url().'uploads/category_image/'.$row['banner'];

		$response['browse_by_shop'] = $row;








		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function pickup_slot_time($para1 = "")
	{
		$store = $this->db->get_where('pickup_slot', array('vendor_id' => $para1))->result_array();

		//echo '<pre>'; print_r($sub_categories); exit;
		foreach ($store as $sc) {
			$sc1 = array('id' => $sc['id'], 'strat_time' => $sc['slot_start'], 'end_time' => $sc['slot_end']);
			$row[] = $sc1;
		}
		//sub_category_image


		//echo '<pre>'; print_r($row['sub_category']);

		//	$row['banner']=base_url().'uploads/category_image/'.$row['banner'];

		$response['pickup_slot_time'] = $row;








		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}
	function pickup_slot_time_interval($para1 = "")
	{
		$store = $this->db->get_where('pickup_slot', array('vendor_id' => $para1))->result_array();

		//echo '<pre>'; print_r($sub_categories); exit;
		foreach ($store as $sc) {
			$sc1 = array('id' => $sc['id'], 'interval_in_minute' => $sc['interval_in_minute'], 'slot_max_order' => $sc['max_order']);
			$row[] = $sc1;
		}
		//sub_category_image


		//echo '<pre>'; print_r($row['sub_category']);

		//	$row['banner']=base_url().'uploads/category_image/'.$row['banner'];

		$response['slot_interval_max_orders'][] = $row;








		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}
	function pickup_available_date($para1 = "")
	{
		$store = $this->db->get_where('pickup_slot', array('vendor_id' => $para1))->result_array();

		//echo '<pre>'; print_r($sub_categories); exit;
		foreach ($store as $sc) {
			$sc1 = array('id' => $sc['id'], 'available_days' => $sc['available_days']);
			$row[] = $sc1;
		}
		//sub_category_image


		//echo '<pre>'; print_r($row['sub_category']);

		//	$row['banner']=base_url().'uploads/category_image/'.$row['banner'];

		$response['available_days'] = $row;

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function pickup_slot_check_max_order($para1 = "", $para2 = "", $para3 = "")
	{
		$datas = json_decode($this->input->raw_input_stream, 1);
		$pickup_date = $datas['pickup_date'];
		$s_date = $datas['start_time'];
		$e_date = $datas['end_time'];
		$store_id = $datas['store_id'];
		$p_slots = $s_date . '-' . $e_date;
		$store = $this->db->get_where('sale', array('store_id' => $store_id, 'pickup_date' => $pickup_date, 'pickup_slot' => $p_slots))->result_array();
		// echo $this->db->last_query();
		$tot_pickorder = count($store);
		if ($tot_pickorder == $datas['max_order']) {

			$response['order_limit'] = "maximum order limit in this slot exits";
		} else {
			$response['order_limit'] = "available";
		}

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function rewards_history($para1 = "")
	{
		$where = '(status="success" or status = "admin_pending" or status = "rejected" or status = "cancelled")';
		$this->db->where($where);
		$this->db->where('rewards !=', '');
		$this->db->where('rewards !=', '0.00');
		$this->db->where('buyer', $para1);
		$orders = $this->db->get('sale')->result_array();
		//	echo $this->db->last_query();
		//echo '<pre>'; print_r($sub_categories); exit;
		foreach ($orders as $sc) {
			if ($sc['refund_status'] == '1') {
				$status = "Refunded";
			}
			$sc1 = array('order_id' => $sc['order_id'], 'rewards' => "RM" . $sc['rewards'], 'date' => date('Y-m-d', $sc['sale_datetime']), 'status' => $status);
			$row[] = $sc1;
		}
		$response['rewards_history'] = $row;







		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function rewards_total($para1 = "")
	{
		$where = '(status="success" or status = "admin_pending" or status = "rejected" or status = "cancelled")';
		$this->db->where($where);
		$this->db->where('rewards !=', '');
		$this->db->where('rewards !=', '0.00');
		$this->db->where('buyer', $para1);
		$this->db->select_sum('rewards');
		$rewards = $this->db->get('sale')->result_array();

		$response['total_rewards'] = $rewards[0]['rewards'];
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function transaction_history($para1 = "")
	{
		$this->db->order_by('id', 'DESC');
		$trans_history = $this->db->get_where('user_trans_log', array('user_id' => $para1))->result_array();
		//	echo $this->db->last_query();
		//echo '<pre>'; print_r($sub_categories); exit;
		foreach ($trans_history as $sc) {

			$sc1 = array('ref_id' => $sc['ref_id'], 'description' => $sc['description'], 'amount' => $sc['amount'], 'status' => $sc['status'], 'date' => date('Y-m-d', $sc['date']));
			$row[] = $sc1;
		}
		$response['transaction_history'] = $row;







		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function all_category_pro_bk($para1 = "")
	{
		$categories = $this->db->get('category')->result_array();
		$order = array(77, 72, 73, 76, 74, 75, 90);
		usort($categories, function ($a, $b) use ($order) {
			$pos_a = array_search($a['category_id'], $order);
			$pos_b = array_search($b['category_id'], $order);
			return $pos_a - $pos_b;
		});
		foreach ($categories as $row) {

			if ($this->crud_model->if_publishable_category($row['category_id'])) {

				$row['count'] = $this->crud_model->is_publishable_count('category', $row['category_id']);
				// $this->db->order_by('product_id', 'desc');

				$sub_categories = $this->db->get_where('product', array('category' => $row['category_id']), 6)->result_array();
				$this->db->limit(6);
				//echo '<pre>'; print_r($sub_categories); exit;
				foreach ($sub_categories as $sc) {
					$sc1 = array('category_id' => $row['category_id'], 'product_id' => $sc['product_id'], 'title' => $sc['title'], 'sale_price' => $sc['sale_price'], 'banner' => base_url() . 'uploads/product_image/product_' . $sc['product_id'] . '_1.jpg');
					$row['products'][] = $sc1;
				}
				//sub_category_image


				//echo '<pre>'; print_r($row['sub_category']);

				//$row['banner']=base_url().'uploads/category_image/'.$row['banner'];

				$response['category'][] = $row;
			}
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}
	function all_category_pro($para1 = "")
	{
		$categories = json_decode($this->crud_model->get_settings_value('ui_settings', 'home_categories'), true);
		foreach ($categories as $row)
		//print_r($row);
		{
			$categories = $this->db->get_where('category', array('category_id' => $row['category']))->result_array();
			$categories = $categories[0];
			//    print_r($row);
			//print_r($row['sub_category']);
			if ($this->crud_model->if_publishable_category($row['category'])) {
				if (!empty($row['sub_category'])) {
					$sub_categories = $row['sub_category'];
					//	print_r($sub_categories);
					foreach ($sub_categories as $row2) {

						//	print_r($row2);
						$products = $this->crud_model->product_list_set('sub_category', 6, $row2);
						foreach ($products as $row3) {
							$sc1 = array('category_id' => $row3['category'], 'product_id' => $row3['product_id'], 'title' => $row3['title'], 'sale_price' => $row3['sale_price'], 'banner' => base_url() . 'uploads/product_image/product_' . $row3['product_id'] . '_1.jpg');
							$categories['products'][] = $sc1;
						}
					}
					//sub_category_image


					//echo '<pre>'; print_r($row['sub_category']);

					//$row['banner']=base_url().'uploads/category_image/'.$row['banner'];

					$response['category'][] = $categories;
				}
			}
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function sub_category($para1 = "")
	{
		$sub_categories = $this->db->get_where('sub_category', array('category' => $para1))->result_array();

		foreach ($sub_categories as $row1) {

			$sub['sub_category_id'] = $row1['sub_category_id'];

			$sub['sub_category_name'] = $row1['sub_category_name'];

			$sub['digital'] = $row1['digital'];
			$sub['banner'] = base_url() . 'uploads/sub_category_image/' . $row1['banner'];

			$row[] = $sub;
		}
		$response['sub_category'] = $row;
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
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

	function faq1()
	{
		$faq = array();
		$faq['faq'] = $this->db->get_where('business_settings', array('type' => 'faqs'))->result_array();
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $faq);
		exit(json_encode($value));
	}
	function faq()
	{

		$page_data['faqs']       = json_decode($this->crud_model->get_type_name_by_id('business_settings', '11', 'value'), true);
		$value = array(
			"status" => "SUCCESS",
			"Message" => "SUCCESS",
			"Response" => $page_data
		);
		exit(json_encode($value));
	}

	function brands($oid)
	{
		$brand = array();
		//	$brand['brand'] = $this->db->get_where('brand')->result_array();
		$brand = $this->db->get_where('brand')->result_array();


		//echo '<pre>'; print_r($sub_categories);
		foreach ($brand as $sc) {
			$sc1 = array('brand_id' => $sc['brand_id'], 'name' => $sc['name'], 'banner' => base_url() . 'uploads/brand_image/' . $sc['logo']);
			$row['brand'][] = $sc1;
			//$response['category'][]=$row;
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $row);
		exit(json_encode($value));
	}

	function brands_cat()
	{
		$categories = $this->db->get('category')->result_array();
		$order = array(77, 72, 73, 76, 74, 75, 90);
		usort($categories, function ($a, $b) use ($order) {
			$pos_a = array_search($a['category_id'], $order);
			$pos_b = array_search($b['category_id'], $order);
			return $pos_a - $pos_b;
		});
		foreach ($categories as $row) {
			//print_r($row); exit;
			if ($this->crud_model->if_publishable_category($row['category_id'])) {

				$row['count'] = $this->crud_model->is_publishable_count('category', $row['category_id']);

				$sub_categories = $this->db->get_where('sub_category', array('category' => $row['category_id']))->result_array();
				$result = array();
				foreach ($sub_categories as $row1) {
					$brands = json_decode($row1['brand'], TRUE);
					foreach ($brands as $row2) {
						if (!in_array($row2, $result)) {
							array_push($result, $row2);
						}
					}
				}
				foreach ($result as $row3) {
					$sc1 = array('category_id' => $row['category_id'], 'sub_category_id' => '0-' . $row3, 'brand_name' => $this->crud_model->get_type_name_by_id('brand', $row3, 'name'), 'banner' => base_url() . 'uploads/brand_image/' . $this->crud_model->get_type_name_by_id('brand', $row3, 'logo'));
					$row['brand'][] = $sc1;
				}
				//echo '<pre>'; print_r($sub_categories); exit;
				/*foreach($sub_categories as $sc)
				{ 
				$sc1= array('category_id'=>$row['category_id'],'sub_category_id'=>$sc['sub_category_id'],'sub_category_name'=>$sc['sub_category_name'],'banner'=>base_url().'uploads/sub_category_image/'.$sc['banner']);
				$row['sub_category'][]=$sc1;
				}*/
				//sub_category_image


				//echo '<pre>'; print_r($row['sub_category']);

				$row['banner'] = base_url() . 'uploads/category_image/' . $row['banner'];

				$response['category'][] = $row;
			}
		}

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function seller_list($oid)
	{
		$vendor = array();
		$vendor['vendor'] = $this->db->get_where('vendor', array('status' => 'approved'))->result_array();
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $vendor);
		exit(json_encode($value));
	}
	function seller_details($para1 = "")
	{
		$vendor = $this->db->get_where('vendor', array('vendor_id' => $para1))->result_array();

		foreach ($vendor as $row1) {

			$sub['vendor_id'] = $row1['vendor_id'];

			$sub['name'] = $row1['name'];

			$sub['email'] = $row1['email'];

			$sub['company'] = $row1['company'];

			$sub['display_name'] = $row1['display_name'];

			$sub['address1'] = $row1['address1'];

			$sub['address2'] = $row1['address2'];

			$sub['city'] = $row1['city'];

			$sub['state'] = $row1['state'];

			$sub['country'] = $row1['country'];

			$sub['zip'] = $row1['zip'];
			$sub['membership_date'] = date('d/m/Y', $row1['create_timestamp']);

			$row[] = $sub;
		}
		$response['vendor'] = $row;
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function seller_products($para1 = "")
	{
		$vendor = $this->db->get_where('vendor', array('vendor_id' => $para1))->result_array();

		foreach ($vendor as $row1) {
			$sub['vendor_id'] = $row1['vendor_id'];
			$sub['name'] = $row1['name'];
			$sub['email'] = $row1['email'];
			$sub['company'] = $row1['company'];
			$sub['display_name'] = $row1['display_name'];
			$sub['address1'] = $row1['address1'];
			$sub['address2'] = $row1['address2'];
			$sub['city'] = $row1['city'];
			$sub['state'] = $row1['state'];
			$sub['country'] = $row1['country'];
			$sub['zip'] = $row1['zip'];
			$sub['membership_date'] = date('d/m/Y', $row1['create_timestamp']);
			$row[] = $sub;
		}
		$response['vendor'] = $row;


		$this->db->order_by('product_id', 'desc');
		$this->db->where('added_by', json_encode(array('type' => 'vendor', 'id' => $para1)));
		$this->db->where('download=', NULL);
		$this->db->where('status=', ok);
		$page_data['all_product'] = $this->db->get('product')->result_array();



		foreach ($page_data['all_product'] as $p) {



			$product_id = $p['product_id'];

			if ($p['discount'] == '') {

				$p['discount'] = 0.00;
			}

			$p['banner'] = $this->crud_model->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');

			$temppProduct[] = $p;
		}

		$page_data['all_products'] = $temppProduct;

		unset($page_data['all_products1']);

		if (count($page_data['all_products']) != 0)

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data['all_products']);
		else

			$value = array("status" => "FAILED", "Message" => "No Products Found");

		//$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$response);

		exit(json_encode($value));
	}


	function product_search($para1 = '')
	{

		if ($para1 != '') {
			$para1 = str_replace('%20', ' ', $para1);
			$productID = '';
			$subCatID = '';
			$CatID = '';
			$brandId = '';

			$product  = $this->db->select('*')->from('product')->where("title LIKE '$para1%'")->get()->result_array();

			foreach ($product as $pdata) {
				if ($productID == '') {
					$productID = 	$pdata['product_id'];
				} else {
					$productID = $productID . ',' . $pdata['product_id'];
				}
			}

			if ($productID == '') {
				$sub_category  = $this->db->select('*')->from('sub_category')->where("sub_category_name LIKE '$para1%'")->get()->result_array();

				foreach ($sub_category as $scdata) {
					if ($subCatID == '') {
						$subCatID = 	$scdata['sub_category_id'];
					} else {
						$subCatID = $subCatID . ',' . $scdata['sub_category_id'];
					}
				}
			}

			if ($subCatID == '') {
				$category  = $this->db->select('*')->from('category')->where("category_name LIKE '$para1%'")->get()->result_array();

				foreach ($category as $cdata) {
					if ($CatID == '') {
						$CatID = 	$cdata['category_id'];
					} else {
						$CatID = $CatID . ',' . $cdata['category_id'];
					}
				}
			}

			if ($CatID == '') {
				$brand  = $this->db->select('*')->from('brand')->where("name LIKE '$para1%'")->get()->result_array();

				foreach ($brand as $bdata) {
					if ($brandId == '') {
						$brandId = 	$bdata['brand_id'];
					} else {
						$brandId = $brandId . ',' . $bdata['brand_id'];
					}
				}
			}

			if ($productID != '') {
				//echo '<br/>Based Product';
				$productID1 = explode(',', trim($productID));
				$this->db->where_in('product_id', $productID1);
				$this->db->where('status', 'ok');
			}

			if ($subCatID != '') {
				//echo '<br/>Based Sub Cat';
				$subCatID1 = explode(',', trim($subCatID));
				$this->db->where_in('sub_category', $subCatID1);
				$this->db->where('status', 'ok');
			}

			if ($CatID != '') {
				//echo '<br/>Based Categroy';
				$CatID1 = explode(',', trim($CatID));
				$this->db->where_in('category', $CatID1);
				$this->db->where('status', 'ok');
			}

			if ($brandId != '') {
				//echo '<br/>Based Brand';
				$brandId1 = explode(',', trim($brandId));
				$this->db->where_in('brand', $brandId1);
				$this->db->where('status', 'ok');
			}

			$page_data['product_details1'] = $this->db->select('*')->from('product')->get()->result_array();

			//echo '<pre>'; print_r($page_data['product_details1']); exit;


			if (count($page_data['product_details1']) != 0) {
				//$page_data['product_details1']				   =  $page_data['product_details1'][0];	

				//echo '<pre>'; print_r($page_data['product_details1']); exit;
				foreach ($page_data['product_details1'] as $pdatas) {
					$page_data1['product_id']   = $pdatas['product_id'];
					$page_data1['rating_num']   =  $pdatas['rating_num'];


					$page_data1['rating_total'] =  $pdatas['rating_total'];
					$page_data1['rating_user']  =  $pdatas['rating_user'];
					$page_data1['title'] 	   =  $pdatas['title'];
					$page_data1['added_by'] 	   =  $pdatas['added_by'];
					$vdatas = json_decode($page_data1['added_by'], 1);
					if ($vdatas['type'] == 'vendor') {
						$vendor_det   = $this->db->get_where('vendor', array('vendor_id' => $vdatas['id']));
						//echo $this->db->last_query(); 
						$page_data3['vendor_det'] = $vendor_det->result_array();
						$page_data3['vendor_det'] = $page_data3['vendor_det'][0];
						$page_data1['vendor_name'] 	=  $page_data3['vendor_det']['name'];
						$page_data1['vendor_id'] 	=  $page_data3['vendor_det']['vendor_id'];
					} else {
						$page_data1['vendor_name'] =  'techn';
					}
					$page_data1['category'] =  $pdatas['category'];

					$category   = $this->db->get_where('category', array('category_id' => $page_data1['category']));
					//echo $this->db->last_query(); 
					$page_data4['category'] = $category->result_array();
					$page_data4['category'] = $page_data4['category'][0];
					$page_data1['category_name'] =  $page_data4['category']['category_name'];





					$page_data1['description'] =  $pdatas['description'];
					$page_data1['sub_category'] =  $pdatas['sub_category'];


					$sub_category   = $this->db->get_where('sub_category', array('sub_category_id' => $page_data1['sub_category']));
					//echo $this->db->last_query(); 
					$page_data4['sub_category'] = $sub_category->result_array();
					$page_data4['sub_category'] = $page_data4['sub_category'][0];
					$page_data1['sub_category_name'] =  $page_data4['sub_category']['sub_category_name'];
					$page_data1['num_of_imgs'] =  $pdatas['num_of_imgs'];

					if ($page_data1['num_of_imgs'] > 0) {
						$pid = $page_data1['product_id'];
						//$ab1=0;
						//for($ab=1; $ab<=$page_data1['num_of_imgs']; $ab++)
						//	{
						//$pid = $page_data1['product_id'];
						//$productImage[$ab1]= 'https://myrunciit.my/uploads/product_image/product_'.$pid.'_'.$ab.'.jpg';	
						//$ab1++;
						//	}
						$page_data1['product_image'] = 'https://myrunciit.my/uploads/product_image/product_' . $pid . '_1.jpg';
					} else {
						$page_data1['product_image'] = 'https://myrunciit.my/uploads/product_image/default.jpg';
					}

					//print_r($page_data1['product_image']); exit;

					$page_data1['sale_price'] =  $pdatas['sale_price'];
					$page_data1['purchase_price'] =  $pdatas['purchase_price'];
					$page_data1['shipping_cost'] =  $pdatas['shipping_cost'];
					$page_data1['add_timestamp'] =  $pdatas['add_timestamp'];
					$page_data1['featured'] =  $pdatas['featured'];
					$page_data1['tag'] =  $pdatas['tag'];
					$page_data1['status'] =  $pdatas['status'];
					$page_data1['front_image'] =  $pdatas['front_image'];
					$page_data1['brand'] =  $pdatas['brand'];

					$brand   = $this->db->get_where('brand', array('brand_id' => $page_data1['brand']));
					//echo $this->db->last_query(); 
					$page_data2['brand_detail'] = $brand->result_array();
					$page_data2['brand_detail'] = $page_data2['brand_detail'][0];
					$page_data1['brand_name'] =  $page_data2['brand_detail']['name'];



					$page_data1['current_stock'] =  $pdatas['current_stock'];
					$page_data1['unit'] =  $pdatas['unit'];
					$page_data1['additional_fields'] =  $pdatas['additional_fields'];
					$page_data1['number_of_view'] =  $pdatas['number_of_view'];
					$page_data1['background'] =  $pdatas['background'];
					$page_data1['discount'] =  $pdatas['discount'];
					$page_data1['discount_type'] =  $pdatas['discount_type'];
					$page_data1['tax'] =  $pdatas['tax'];
					$page_data1['tax_type'] =  $pdatas['tax_type'];
					$page_data1['color'] =  $pdatas['color'];
					$page_data1['options'] =  $pdatas['options'];
					$page_data1['main_image'] =  $pdatas['main_image'];
					$page_data1['download'] =  $pdatas['download'];
					$page_data1['download_name'] =  $pdatas['download_name'];
					$page_data1['deal'] =  $pdatas['deal'];
					$page_data1['num_of_downloads'] =  $pdatas['num_of_downloads'];
					$page_data1['update_time'] =  $pdatas['update_time'];
					$page_data1['requirements'] =  $pdatas['requirements'];
					$page_data1['logo'] =  $pdatas['logo'];
					$page_data1['video'] =  $pdatas['video'];
					$page_data1['last_viewed'] =  $pdatas['last_viewed'];
					//$page_data1['products'] =  $pdatas['products'];
					$page_data1['is_bundle'] =  $pdatas['is_bundle'];
					$page_data1['vendor_featured'] = $pdatas['vendor_featured'];

					$page_data11[] = $page_data1;
				}
				$response['product'] = $page_data11;
				//echo '<pre>'; print_r($response); exit;

				$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

				exit(json_encode($value));



				//echo json_encode($page_data1['product_details']);

				//exit;

				//$page_data['product_details']['description']=str_replace('</li>','</p>',str_replace('<li>','<p>',str_replace('</ul>','',str_replace('<ul>','',str_replace('<div>','',str_replace('</div>','',$page_data['product_details']['description']))))));

				//$page_data['product_tags'] = $product_data->row()->tag;

				//$page_data['product_details']['banner']=$this->crud_model->file_view('product',$para1,'','','thumb','src','multi','one');

				//$page_data['product_details']['additional_specification']=$this->crud_model->get_additional_fields($row['product_id']);			       	$page_data['product_details']['shipment_info']=$this->db->get_where('business_settings',array('type'=>'shipment_info'))->row()->value;

				//$page_data['product_details']['product_by']=$this->crud_model->product_by($para1,'with_link');


				//$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);

				//exit(json_encode($value));

			} else {
				$value = array("status" => "FAILED", "Message" => "Product not available");
				exit(json_encode($value));
			}
		} else {
			$value = array("status" => "Failed", "Message" => "Failed", "Response" => 'Please Enter Text');
			exit(json_encode($value));
		}
	}

	function brand_wise_products($para1 = "")
	{
		$bproduct = $this->db->get_where('product', array('brand' => $para1))->result_array();

		foreach ($bproduct as $row1) {

			$sub['product_id'] = $row1['product_id'];

			$sub['rating_num'] = $row1['rating_num'];

			$sub['rating_total'] = $row1['rating_total'];

			$sub['title'] = $row1['title'];

			$sub['category'] = $row1['category'];

			$sub['sub_category'] = $row1['sub_category'];

			$sub['description'] = $row1['description'];

			$sub['sale_price'] = $row1['sale_price'];

			$sub['purchase_price'] = $row1['purchase_price'];

			$sub['shipping_cost'] = $row1['shipping_cost'];

			$sub['tag'] = $row1['tag'];
			$sub['current_stock'] = $row1['current_stock'];
			$sub['unit'] = $row1['unit'];
			$sub['number_of_view'] = $row1['number_of_view'];
			$sub['discount'] = $row1['discount'];
			$sub['discount_type'] = $row1['discount_type'];
			$sub['tax'] = $row1['tax'];
			$sub['tax_type'] = $row1['tax_type'];
			$sub['color'] = $row1['color'];

			$sub['product_added_date'] = date('d/m/Y', $row1['add_timestamp']);
			$sub['banner'] = $this->crud_model->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');

			$row[] = $sub;
		}
		$response['bproduct'] = $row;
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}


	function home_search($param = '')

	{

		$category = $this->input->post('category');

		$this->session->set_userdata('searched_cat', $category);

		if ($param !== 'top') {

			$sub_category = $this->input->post('sub_category');

			$range        = $this->input->post('price');

			$brand 		  = $this->input->post('brand');

			$query 		  = $this->input->post('query');

			$p            = explode(';', $range);

			redirect(base_url() . 'index.php/home/category/' . $category . '/' . $sub_category . '-' . $brand . '/' . $p[0] . '/' . $p[1] . '/' . $query, 'refresh');
		} else if ($param == 'top') {

			redirect(base_url() . 'index.php/home/category/' . $category, 'refresh');
		}
	}



	function text_search()
	{

		if ($this->crud_model->get_settings_value('general_settings', 'vendor_system') !== 'ok') {

			$search = $this->input->post('query');

			$category = $this->input->post('category');

			redirect(base_url() . 'index.php/home/category/' . $category . '/0-0/0/0/' . $search, 'refresh');
		} else {

			$type = $this->input->post('type');

			$search = $this->input->post('query');

			$category = $this->input->post('category');

			if ($type == 'vendor') {

				redirect(base_url() . 'index.php/home/store_locator/' . $search, 'refresh');
			} else if ($type == 'product') {

				redirect(base_url() . 'index.php/home/category/' . $category . '/0-0/0/0/' . $search, 'refresh');
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

		$product_data   	= $this->db->get_where('product', array('product_id' => $para1, 'status' => 'ok'));

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

		$page_data['product_details'] = $this->db->get_where('product', array('product_id' => $para1, 'status' => 'ok'))->result_array();

		$page_data['page_name']    = "product_view/" . $type . "/page_view";

		$page_data['asset_page']   = "product_view_" . $type;

		$page_data['product_data'] = $product_data->result_array();

		$page_data['product_data'] = $page_data['product_data'][0];

		$page_data['page_title']   = $product_data->row()->title;

		$page_data['product_tags'] = $product_data->row()->tag;

		$page_data['product_data']['banner'] = $this->crud_model->file_view('product', $para1, '', '', 'thumb', 'src', 'multi', 'one');

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data);

		exit(json_encode($value));

		//$this->load->view('front/index', $page_data);

	}

	function contact_address($para1 = "", $para2 = "")

	{

		$page_data['contact_address'] =  $this->db->get_where('general_settings', array('type' => 'contact_address'))->row()->value;

		//echo $this->db->last_query(); exit;

		$page_data['contact_phone'] =  $this->db->get_where('general_settings', array('type' => 'contact_phone'))->row()->value;

		$page_data['contact_email'] =  $this->db->get_where('general_settings', array('type' => 'contact_email'))->row()->value;

		$page_data['contact_website'] =  $this->db->get_where('general_settings', array('type' => 'contact_website'))->row()->value;

		$page_data['contact_about'] =  $this->db->get_where('general_settings', array('type' => 'contact_about'))->row()->value;



		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data);

		exit(json_encode($value));

		//$this->load->view('front/index', $page_data);

	}

	/* FUNCTION: Loads Product View Page */

	function get_vendor_info($para1 = '')
	{
		if ($para1 == '') {
			$vendor_data   = $this->db->get_where('vendor', array('status' => 'approved'))->result_array();
		} else {
			$vendor_data   = $this->db->get_where('vendor', array('vendor_id' => $para1, 'status' => 'approved'))->result_array();
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $vendor_data);

		exit(json_encode($value));
	}

	function notificationCount($para1 = '')
	{
		$notificationCount   = $this->db->get_where('user_log', array('uid' => $para1, 'read_status' => '0'));
		$notificationCount = $notificationCount->result_array();
		$value = array("status" => "SUCCESS", "Message" => "Notification Count", "Response" => count($notificationCount));
		exit(json_encode($value));
	}


	function view_notification($para1 = '')
	{
		$notificationCount   = $this->db->get_where('user_log', array('uid' => $para1, 'status' => '1'));
		$notificationCount = $notificationCount->result_array();
		$value = array("status" => "SUCCESS", "Message" => "Notification Count", "Response" => $notificationCount);

		$this->db->where('uid', $para1);
		$this->db->update('user_log', array('read_status' => 1));
		exit(json_encode($value));
	}

	function quick_view($para1 = "")
	{
		$product_data   = $this->db->get_where('product', array('product_id' => $para1, 'status' => 'ok'));
		$page_data['product_details1'] = $product_data->result_array();
		if (count($page_data['product_details1']) != 0) {
			$page_data['product_details1'] = $page_data['product_details1'][0];

			$page_data1['product_details']['product_id'] =  $page_data['product_details1']['product_id'];
			$page_data1['product_details']['store_id'] =  $page_data['product_details1']['store_id'];
			$page_data1['product_details']['multiple_price'] =  $page_data['product_details1']['multiple_price'];
			$page_data1['product_details']['rating_num'] =  $page_data['product_details1']['rating_num'];
			$page_data1['product_details']['rating_total'] =  $page_data['product_details1']['rating_total'];
			$page_data1['product_details']['rating_user'] =  $page_data['product_details1']['rating_user'];
			$page_data1['product_details']['title'] =  $page_data['product_details1']['title'];
			$page_data1['product_details']['added_by'] =  $page_data['product_details1']['added_by'];

			$vdatas = json_decode($page_data1['product_details']['added_by'], 1);
			if ($vdatas['type'] == 'vendor') {
				$vendor_det   = $this->db->get_where('vendor', array('vendor_id' => $vdatas['id']));
				//echo $this->db->last_query(); 
				$page_data3['vendor_det'] = $vendor_det->result_array();
				$page_data3['vendor_det'] = $page_data3['vendor_det'][0];
				$page_data1['product_details']['vendor_name'] =  $page_data3['vendor_det']['name'];
				$page_data1['product_details']['vendor_id'] =  $page_data3['vendor_det']['vendor_id'];
				$page_data1['product_details']['trade_name'] =  $page_data3['vendor_det']['trade_name'];
			} else {

				$page_data1['product_details']['vendor_name'] =  'techn';
			}

			$page_data1['product_details']['category'] =  $page_data['product_details1']['category'];
			$category   = $this->db->get_where('category', array('category_id' => $page_data1['product_details']['category']));
			//echo $this->db->last_query(); 
			$page_data4['category'] = $category->result_array();
			$page_data4['category'] = $page_data4['category'][0];
			$page_data1['product_details']['category_name'] =  $page_data4['category']['category_name'];
			$page_data1['product_details']['description'] =  $page_data['product_details1']['description'];
			$page_data1['product_details']['how_use'] =  str_replace("\r\n ", "", $page_data['product_details1']['how_use']);
			$page_data1['product_details']['key_point'] =  $page_data['product_details1']['key_point'];
			$page_data1['product_details']['additional_info'] =  $page_data['product_details1']['additional_info'];
			$page_data1['product_details']['country_of_origin'] =  $page_data['product_details1']['country_of_origin'];
			$page_data1['product_details']['manufacturer'] =  $page_data['product_details1']['manufacturer'];
			$page_data1['product_details']['return_set'] =  $page_data['product_details1']['return_set'];
			$page_data1['product_details']['sub_category'] =  $page_data['product_details1']['sub_category'];
			$sub_category   = $this->db->get_where('sub_category', array('sub_category_id' => $page_data1['product_details']['sub_category']));
			//echo $this->db->last_query(); 
			$page_data4['sub_category'] = $sub_category->result_array();
			$page_data4['sub_category'] = $page_data4['sub_category'][0];
			$page_data1['product_details']['sub_category_name'] =  $page_data4['sub_category']['sub_category_name'];
			$page_data1['product_details']['num_of_imgs'] =  $page_data['product_details1']['num_of_imgs'];
			if ($page_data1['product_details']['num_of_imgs'] > 0) {
				$ab1 = 0;
				for ($ab = 1; $ab <= $page_data1['product_details']['num_of_imgs']; $ab++) {
					$pid = $page_data1['product_details']['product_id'];
					$productImage[$ab1] = 'https://myrunciit.my/uploads/product_image/product_' . $pid . '_' . $ab . '.jpg';
					$ab1++;
				}
				$page_data1['product_details']['product_image'] = $productImage;
			} else {
				$page_data1['product_details']['product_image'] = array('https://myrunciit.my/uploads/product_image/default.jpg');
			}

			//print_r($page_data1['product_details']['product_image']); exit;

			$page_data1['product_details']['sale_price'] =  $page_data['product_details1']['sale_price'];
			$page_data1['product_details']['purchase_price'] =  $page_data['product_details1']['purchase_price'];
			$page_data1['product_details']['shipping_cost'] =  $page_data['product_details1']['shipping_cost'];
			$page_data1['product_details']['add_timestamp'] =  $page_data['product_details1']['add_timestamp'];
			$page_data1['product_details']['featured'] =  $page_data['product_details1']['featured'];
			$page_data1['product_details']['tag'] =  $page_data['product_details1']['tag'];
			$page_data1['product_details']['status'] =  $page_data['product_details1']['status'];
			$page_data1['product_details']['front_image'] =  $page_data['product_details1']['front_image'];
			$page_data1['product_details']['brand'] =  $page_data['product_details1']['brand'];

			$brand   = $this->db->get_where('brand', array('brand_id' => $page_data1['product_details']['brand']));
			//echo $this->db->last_query(); 
			$page_data2['brand_detail'] = $brand->result_array();
			$page_data2['brand_detail'] = $page_data2['brand_detail'][0];
			$page_data1['product_details']['brand_name'] =  $page_data2['brand_detail']['name'];

			$page_data1['product_details']['current_stock'] =  $page_data['product_details1']['current_stock'];
			$page_data1['product_details']['unit'] =  $page_data['product_details1']['unit'];
			$page_data1['product_details']['additional_fields'] =  $page_data['product_details1']['additional_fields'];
			$page_data1['product_details']['number_of_view'] =  $page_data['product_details1']['number_of_view'];
			$page_data1['product_details']['background'] =  $page_data['product_details1']['background'];
			$page_data1['product_details']['discount'] =  $page_data['product_details1']['discount'];
			$page_data1['product_details']['discount_type'] =  $page_data['product_details1']['discount_type'];
			$page_data1['product_details']['tax'] =  $page_data['product_details1']['tax'];
			$page_data1['product_details']['tax_type'] =  $page_data['product_details1']['tax_type'];
			$page_data1['product_details']['color'] =  $page_data['product_details1']['color'];
			$page_data1['product_details']['options'] =  $page_data['product_details1']['options'];
			$page_data1['product_details']['main_image'] =  $page_data['product_details1']['main_image'];
			$page_data1['product_details']['download'] =  $page_data['product_details1']['download'];
			$page_data1['product_details']['download_name'] =  $page_data['product_details1']['download_name'];
			$page_data1['product_details']['deal'] =  $page_data['product_details1']['deal'];
			$page_data1['product_details']['num_of_downloads'] =  $page_data['product_details1']['num_of_downloads'];
			$page_data1['product_details']['update_time'] =  $page_data['product_details1']['update_time'];
			$page_data1['product_details']['requirements'] =  $page_data['product_details1']['requirements'];
			$page_data1['product_details']['logo'] =  $page_data['product_details1']['logo'];
			$page_data1['product_details']['video'] =  $page_data['product_details1']['video'];
			$page_data1['product_details']['last_viewed'] =  $page_data['product_details1']['last_viewed'];
			$page_data1['product_details']['products'] =  $page_data['product_details1']['products'];
			$page_data1['product_details']['is_bundle'] =  $page_data['product_details1']['is_bundle'];
			$page_data1['product_details']['vendor_featured'] =  $page_data['product_details1']['vendor_featured'];


			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data1);

			exit(json_encode($value));



			//echo json_encode($page_data1['product_details']);

			exit;

			$page_data['product_details']['description'] = str_replace('</li>', '</p>', str_replace('<li>', '<p>', str_replace('</ul>', '', str_replace('<ul>', '', str_replace('<div>', '', str_replace('</div>', '', $page_data['product_details']['description']))))));

			$page_data['product_tags'] = $product_data->row()->tag;

			$page_data['product_details']['banner'] = $this->crud_model->file_view('product', $para1, '', '', 'thumb', 'src', 'multi', 'one');

			$page_data['product_details']['additional_specification'] = $this->crud_model->get_additional_fields($row['product_id']);
			$page_data['product_details']['shipment_info'] = $this->db->get_where('business_settings', array('type' => 'shipment_info'))->row()->value;

			$page_data['product_details']['product_by'] = $this->crud_model->product_by($para1, 'with_link');

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data);

			exit(json_encode($value));
		} else {
			$value = array("status" => "FAILED", "Message" => "Product not available");
			exit(json_encode($value));
		}
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

		recache();
	}

	/* FUNCTION: Loads Contact Page */

	function contact($para1 = "")

	{

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

							$data['state']       		= $this->input->post('state');

							$data['country']       		= $this->input->post('country');

							$data['city']       		= $this->input->post('city');

							$data['zip']       			= $this->input->post('zip');

							$data['create_timestamp']   = time();

							$data['approve_timestamp']  = 0;

							$data['approve_timestamp']  = 0;

							$data['membership']         = 0;

							$data['status']             = 'pending';

							if ($this->input->post('password1') == $this->input->post('password2')) {

								$password         = $this->input->post('password1');

								$data['password'] = md5($password);

								$this->db->insert('vendor', $data);

								$msg = 'done';

								if ($this->email_model->account_opening('vendor', $data['email'], $password) == false) {

									$msg = 'done_but_not_sent';
								} else {

									$msg = 'done_and_sent';
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

						$data['state']       		= $this->input->post('state');

						$data['country']       		= $this->input->post('country');

						$data['city']       		= $this->input->post('city');

						$data['zip']       			= $this->input->post('zip');

						$data['create_timestamp']   = time();

						$data['approve_timestamp']  = 0;

						$data['approve_timestamp']  = 0;

						$data['membership']         = 0;

						$data['status']             = 'pending';

						if ($this->input->post('password1') == $this->input->post('password2')) {

							$password         = $this->input->post('password1');

							$data['password'] = md5($password);

							$this->db->insert('vendor', $data);

							$msg = 'done';

							if ($this->email_model->account_opening('vendor', $data['email'], $password) == false) {

								$msg = 'done_but_not_sent';
							} else {

								$msg = 'done_and_sent';
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


	function vendor_slides($para1 = "", $para2 = "")
	{

		//$page_data['vendor_id']			=$para2;
		$this->db->where("status", "ok");
		$this->db->where('added_by', json_encode(array('type' => 'vendor', 'id' => $para1)));
		$sliders       = $this->db->get('slides')->result_array();

		foreach ($sliders as $row1) {
			$sub['slides_id'] = $row1['slides_id'];
			$sub['button_color'] = $row1['button_color'];
			$sub['text_color'] = $row1['text_color'];
			$sub['button_text'] = $row1['button_text'];
			$sub['button_link'] = $row1['button_link'];
			//$sub['button_color'] = $row1['button_color'];
			//$sub['banner'] = base_url().'uploads/slides_image/slides_'.$row1['slides_id'].'.jpg';
			$sub['digital'] = $row1['digital'];
			//$sub['slides'] = base_url().'uploads/vendor_banner_image/banner_'.$para1.'.jpg';
			$sub['slides'] = 'banner_' . $para1 . '.jpg';
			$sub['banner'] = $sub['image'] = 'slides_' . $row1['slides_id'] . '.jpg';
			$row[] = $sub;
			$response['slides'] = $row;
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);
		exit(json_encode($value));
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

		//echo "a"; exit;

		$page_data['page_name'] = "login";

		$this->load->library('form_validation');

		if ($para1 == "do_login") {

			$datas = json_decode($this->input->raw_input_stream, 1);

			//print_r($datas);

			$signin_data = $this->db->get_where('user', array(

				'email' => $datas['email'],

				'password' => sha1($datas['password'])

			));

			//echo $signin_data->num_rows();

			if ($signin_data->num_rows() > 0) {

				foreach ($signin_data->result_array() as $row) {



					//$accessToken = bin2hex(openssl_random_pseudo_bytes(16));

					$accessToken = rand(1234567890, 16);

					$this->session->set_userdata('user_login', 'yes');

					$this->session->set_userdata('user_id', $row['user_id']);

					$userlog['uid'] =  $row['user_id'];
					$userlog['description'] = "Login Successfully";
					$this->db->insert('user_log', $userlog);

					$this->session->set_userdata('username', $row['username']);

					$this->session->set_flashdata('alert', 'successful_signin');

					$userlog['uid'] =  $row['user_id'];
					$userlog['description'] = "Login Successfully";
					$this->db->insert('user_log', $userlog);

					$_SESSION['user']['first_name'] = $row['username'];

					$_SESSION['user']['id'] = $row['user_id'];

					$_SESSION['user']['email'] = $row['email'];

					$_SESSION['user']['mobile_number'] = $row['phone'];


					$response['id'] = $row['user_id'];

					$response['first_name'] = $row['username'];

					$response['email_id'] = $row['email'];

					$response['mobile_number'] = $row['phone'];


					$response['accessToken'] = $accessToken;

					$results['status'] = 'SUCCESS';

					$results['Message'] = 'Login Success';

					$results['Response'] = $response;

					echo json_encode($results, true);

					exit;
				}
			} else {

				$results['status'] = 'FAILED';

				$results['Message'] = 'Invalid username or password';

				echo json_encode($results, true);

				exit;
			}
		} else if ($para1 == 'forget') {

			$datas = json_decode($this->input->raw_input_stream, 1);
			//echo 1; //exit;
			$email = $datas['email'];

			$query = $this->db->select('*')->from('user')

				->group_start()

				->or_group_start()

				->where('email', $email)

				->where('email !=', 'NULL')

				->where('email !=', '')

				->group_end()

				->or_group_start()

				->where('phone', $email)

				->where('phone !=', '')

				->group_end()

				->group_end()

				->get();

			if ($query->num_rows() > 0) {

				$user_id          = $query->row()->user_id;

				$mobile_number          = $query->row()->phone;

				$password         = substr(rand(), 0, 12);

				//$data['password'] = md5($password.'987ABLO@@##$$%%');
				$data['password'] = sha1($password);

				$this->db->where('user_id', $user_id);

				$this->db->update('user', $data);

				$content = 'Dear User, Your new passowrd is ' . $password . ' Thanks for using myrunciit.my';

				//$this->crud_model->sendsms($mobile_number,$content);

				if ($this->email_model->password_reset_email('user', $user_id, $password)) {

					$results['status'] = 'SUCCESS';

					$results['Message'] = 'Your new password sent to your email';

					$results['Response'] = 'E-mail Sent';

					echo json_encode($results, true);

					exit;
				} else {

					$results['status'] = 'SUCCESS';

					$results['Message'] = 'Your new password sent to your email';

					$results['Response'] = 'E-mail Sent';

					echo json_encode($results, true);

					exit;
				}
			} else {

				$results['status'] = 'FAILED';

				$results['Message'] = 'Mail ID not found';

				$results['Response'] = 'Mail ID not found';

				echo json_encode($results, true);

				exit;
			}
		}
	}

	/* FUNCTION: Setting login page with facebook and google */

	function login_set($para1 = '', $para2 = '', $para3 = '')

	{

		//if ($this->session->userdata('user_login') == "yes") {
		//            redirect(base_url().'index.php/home/profile', 'refresh');

		//      }

		if ($this->crud_model->get_settings_value('general_settings', 'captcha_status', 'value') == 'ok') {
			$this->load->library('recaptcha');
		}

		$this->load->library('form_validation');

		//$fb_login_set = $this->crud_model->get_settings_value('general_settings','fb_login_set');

		//$g_login_set  = $this->crud_model->get_settings_value('general_settings','g_login_set');

		$page_data    = array();


		if ($para1 == 'registration') {

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

	function logout($para1 = '')
	{
		$userlog['uid'] =  $para1;
		$userlog['description'] = "Logout Successfully";
		$this->db->insert('user_log', $userlog);
		$results['status'] = 'SUCCESS';
		$results['Message'] = 'Logout Success';
		$results['Response'] = 'Logout Success';
		echo json_encode($results, true);
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

		$user   = $this->db->get('user_login')->result_array();

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

	/* FUNCTION: Customer Registration*/

	function registration($para1 = "", $para2 = "")
	{

		$safe = 'yes';

		$char = '';


		$page_data['page_name'] = "registration";

		if ($para1 == "add_info") {

			$msg = '';
			$datas = json_decode($this->input->raw_input_stream, 1);

			//print_r($datas); exit;


			//$this->crud_model->get_settings_value('general_settings','captcha_status','value');

			//if($safe == 'yes'){


			//else{


			$data['username']    = $datas['firstname'];

			$data['surname']     = $datas['lastname'];

			$data['email']       = $datas['email'];

			$data['phone']       = $datas['phone'];

			$data['address1']    = $datas['address1'];

			$data['address2']    = $datas['address2'];

			$data['age']         = $datas['age'];

			$data['gender']      = $datas['gender'];

			$data['zip']         = $datas['zip'];

			$data['city']        = $datas['city'];

			$data['state']       = $datas['state'];

			$data['country']     = $datas['country'];

			$data['creation_date'] = time();

			$password              = $datas['password'];

			$data['password'] = sha1($password);


			//$account_data = $this->db->select('*')->from('user')->group_start()->or_group_start()->where('email', $data['email_id'])->group_end()->or_group_start()->where('mobile', $data['mobile_number'])->group_end()->group_end()->get()->num_rows();

			//$this->db->where('email', $data['email_id']);

			//$this->db->where('mobile', $data['mobile_number']);



			//$page_data['sliders']       = $this->db->get('slides')->result_array();

			//$account_data = $this->db->get('user')->result_array();

			$account_data = $this->db->get_where('user', array('email' => $data['email'], 'phone' => $data['phone']))->num_rows();



			if ($account_data == 0) {

				$this->db->insert('user', $data);

				$insertIDSS = $this->db->insert_id();
				//$datas['customer_code']="PYC/CI/".str_pad($insertIDSS, 5, "0", STR_PAD_LEFT); 
				$this->db->where('user_id', $insertIDSS);
				$this->db->update('user', $datas);
				$userlog['uid'] = $insertIDSS;
				$userlog['description'] = "Register Successfully";
				$this->db->insert('user_log', $userlog);

				/*if ($this->db->affected_rows() > 0 ) {

							$return_message = 'Insert successful';

							}else{

							$return_message = 'Failed to insert record';

							}

							echo $return_message; exit;*/



				$results['status'] = 'SUCCESS';

				$results['Message'] = "Registred successfully";

				echo json_encode($results, true);

				exit;



				/*if($this->email_model->account_opening('user', $data['email_id'], $password) == false){

							$results['status'] = 'SUCCESS';

							$results['Message'] = "Registred successfully";

							echo json_encode($results,true);

							exit;

						}else{

							$results['status'] = 'SUCCESS';

							$results['Message'] = "Registred successfully";

							echo json_encode($results,true);

							exit;

						}*/
			} else {

				$results['status'] = 'FAILED';

				$results['Message'] = "Email or Mobile Number already exists";

				echo json_encode($results, true);

				exit;
			}


			//}

			//} else {

			//	echo 'Disallowed charecter : " '.$char.' " in the POST';

			//}



		} else if ($para1 == "update_info") {
			$msg = '';
			$datas = json_decode($this->input->raw_input_stream, 1);

			$id                    = $datas['user_id'];

			$data['username']    = $datas['firstname'];

			$data['surname']     = $datas['lastname'];

			$data['email']       = $datas['email'];

			$data['phone']       = $datas['phone'];

			$data['address1']    = $datas['address1'];

			$data['address2']    = $datas['address2'];

			$data['age']         = $datas['age'];

			$data['gender']      = $datas['gender'];

			$data['zip']         = $datas['zip'];

			$data['city']        = $datas['city'];

			$data['state']       = $datas['state'];

			$data['country']     = $datas['country'];

			$data['creation_date'] = time();

			$account_data = $this->db->get_where('user', array('email' => $data['email'], 'phone' => $data['phone'], 'user_id!=' => $id))->num_rows();
			if ($account_data == 0) {
				$this->db->where('user_id', $id);
				$this->db->update('user', $data);
				$results['status'] = 'SUCCESS';
				$results['Message'] = "Profile Updated successfully";
				echo json_encode($results, true);
				exit;
			} else {
				$results['status'] = 'FAILED';
				$results['Message'] = "Email or Mobile Number already exists";
				echo json_encode($results, true);
				exit;
			}
		} else if ($para1 == "update_password") {

			$user_data['password'] = $this->input->post('password');

			$account_data          = $this->db->get_where('user', array(

				'id' => $this->session->userdata('user_id')

			))->result_array();

			foreach ($account_data as $row) {

				if (md5($user_data['password']) == $row['password']) {

					if ($this->input->post('password1') == $this->input->post('password2')) {

						$data['password'] = md5($this->input->post('password1'));

						$this->db->where('id', $this->session->userdata('user_id'));

						$this->db->update('user', $data);

						echo "done";
					} else {

						echo translate('passwords_did_not_match!');
					}
				} else {

					echo translate('wrong_old_password!');
				}
			}
		} else if ($para1 == "change_picture") {

			$id                  = $this->session->userdata('user_id');

			$this->crud_model->file_up('img', 'user', $id, '', '', '.jpg');

			echo 'done';
		} else {

			$this->load->view('front/registration', $page_data);
		}
	}

	function error()

	{

		$this->load->view('front/others/404_error');
	}

	function update_password($para1, $para2, $para3, $para4)

	{

		// echo "1"; exit;

		// $datas = json_decode($this->input->raw_input_stream,1);
		$user_data['user_id'] = $para1;
		$user_data['old_password'] = $para2;
		$user_data['password'] = $para3;
		$user_data['new_password2'] = $para4;

		//print_r($user_data);
		$account_data          = $this->db->get_where('user', array(

			'user_id' => $user_data['user_id']

		))->result_array();
		// echo $this->db->last_query(); exit;

		foreach ($account_data as $row) {

			if (sha1($user_data['old_password']) == $row['password']) {

				if ($user_data['password'] == $user_data['new_password2']) {

					$data['password'] = sha1($user_data['password']);

					$this->db->where('user_id', $user_data['user_id']);

					$this->db->update('user', $data);

					$results['status'] = 'SUCCESS';
					$results['Message'] = "New password updated!";
					echo json_encode($results, true);
					exit;
				} else {

					$results['status'] = 'FAILED';
					$results['Message'] = "Password & Confirm Password does not match!";
					echo json_encode($results, true);
					exit;
				}
			} else {
				//echo "1"; exit;
				$results['status'] = 'FAILED';
				$results['Message'] = "Old Password Wrong";
				echo json_encode($results, true);
				exit;
			}
		}
	}

	/* FUNCTION: Product rating*/

	function rating($id, $product_id, $rating)

	{
		if ($rating <= 5) {
			if ($this->crud_model->set_rating_app($id, $product_id, $rating) == 'yes') {
				$results['status'] = 'Success';

				$results['Message'] = "Rating Successfully";

				echo json_encode($results, true);
			} else if ($this->crud_model->set_rating_app($id, $product_id, $rating) == 'no') {
				$results['status'] = 'Already Rating This Product';

				$results['Message'] = "Rating Successfully";

				echo json_encode($results, true);
			}
		} else {

			$results['status'] = 'Failed';

			$results['Message'] = "Rating Count Is Wrong ";

			echo json_encode($results, true);
		}
	}

	/* FUNCTION: Concerning Compare*/

	function compare($para1 = "", $para2 = "",$para3 = "" )
	{
		try{
		if ($para1 == 'add') {
			switch ($result = $this->crud_model->add_compare_ws($para2)) {
				case empty($para2):
					echo json_encode(["status" => "FAILED", 'message' => "Invalid Request", 'Response' => '']);
				case ($result == "done"):
					echo json_encode(["status" => "SUCCESS", 'message' => "Product Added to Compare List", 'Response' => '']);
					break;
				case ($result == "cat_full"):
					echo json_encode(["status" => "FAILED", 'message' => "Compare List is Full", 'Response' => '']);
					break;
				case ($result == "already"):
					echo json_encode(["status" => "FAILED", 'message' => "Product Already in Compare List", 'Response' => '']);
					break;
				default:
					return json_encode(["status" => "FAILED", 'message' => "Failed to Add", 'Response' => '']);
					break;
			}
		} else if ($para1 == 'remove') {
			if (!empty($para2)) {
				$before_count = $this->crud_model->compared_num();
				$this->crud_model->remove_compare($para2);
				$after_count = $this->crud_model->compared_num();

				if (($before_count - 1) === $after_count) {
					echo json_encode(["status" => "SUCCESS", 'message' => "Product Removed in Compare List", 'Response' => '']);
				} else {
					echo json_encode(["status" => "FAILED", 'message' => "Failed to Remove", 'Response' => '']);
				}
			} else {
				echo json_encode(["status" => "FAILED", 'message' => "Invalid Request", 'Response' => '']);
			}
		} else if ($para1 == 'num') {
			echo json_encode(["status" => "SUCCESS", 'message' => "Compare List is Cleared", 'Response' => $this->crud_model->compared_num()]);
		} else if ($para1 == 'clear') {
			$this->session->set_userdata('compare', '');
			$after_count = $this->crud_model->compared_num();
			if ($after_count > 0) {
				echo json_encode(["status" => "SUCCESS", 'message' => "", 'Response' => '']);
			} else {
				echo json_encode(["status" => "FAILED", 'message' => "", 'Response' => '']);
			}
		} else if ($para1 == 'get_detail') {
			if (!empty($para2)) {
				$product = $this->db->get_where('product', array('product_id' => $para2));
				$return_array['product'] = $product->row();
				$return_array['img_src'] = $this->crud_model->file_view('product', $para2, '', '', 'thumb', 'src', 'multi', 'one');
				$return_array['brand_detail'] = $this->db->get_where('brand', array('brand_id' => $product->row()->brand))->row()->name;
				$return_array['category_name'] = $this->db->get_where('category', array('category_id' => $product->row()->category))->row()->category_name;
				$return_array['sub_category_name'] = $this->db->get_where('sub_category', array('sub_category_id' => $product->row()->sub_category))->row()->sub_category_name;
				
				if(empty($para3)){
					echo json_encode(["status" => "SUCCESS", 'message' => "", 'Response' => $return_array]);
				}
				else{
					return $return_array;
				}

			}
			else{
				echo json_encode(["status" => "FAILED", 'message' => "Compare List is Empty", 'Response' => '']);
			}
		} else {
			if ($this->session->userdata('compare') == '[]') {
				echo json_encode(["status" => "FAILED", 'message' => "Compare List is Empty", 'Response' => '']);
			}
			else{
				$compare_product_list=[];
				$items = json_decode($this->session->userdata('compare'));
				 foreach($items as $item)
				 {
					$arr=$this->compare('get_detail',$item,'callback');
					array_push($compare_product_list,$arr);
				 }
				 echo json_encode(["status" => "success", 'message' => "", 'Response' => $compare_product_list]);
			}
		}
	}
	catch(Exception $e){
		echo json_encode(["status" => "FAILED", 'message' => $e->getMessage(), 'Response' => '']);
	}
	}

	function cancel_order()
	{

		$this->session->set_userdata('sale_id', '');

		$this->session->set_userdata('couponer', '');

		$this->cart->destroy();

		redirect(base_url(), 'refresh');
	}

	/* FUNCTION: Concering Add, Remove and Updating Cart Items*/

	function cart($para1 = '', $para2 = '', $para3 = '', $para4 = '')

	{

		$this->cart->product_name_rules = '[:print:]';

		if ($para1 == "add") {

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

				'coupon' => ''

			);

			$stock = $this->crud_model->get_type_name_by_id('product', $para2, 'current_stock');

			if (!$this->crud_model->is_added_to_cart($para2) || $para3 == 'pp') {

				if ($stock >= $qty || $this->crud_model->is_digital($para2)) {

					$this->cart->insert($data);

					echo 'added';
				} else {

					echo 'shortage';
				}
			} else {

				echo 'already';
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

			$total = $this->cart->total();

			if ($this->crud_model->get_type_name_by_id('business_settings', '3', 'value') == 'product_wise') {

				$shipping = $this->crud_model->cart_total_it('shipping');
			} elseif ($this->crud_model->get_type_name_by_id('business_settings', '3', 'value') == 'fixed') {

				$shipping = $this->crud_model->get_type_name_by_id('business_settings', '2', 'value');
			}

			$tax   = $this->crud_model->cart_total_it('tax');

			$grand = $total + $shipping + $tax;

			if ($para2 == 'full') {

				$ship  = $shipping;

				$count = count($this->cart->contents());

				if ($total == '') {

					$total = 0;
				}

				if ($ship == '') {

					$ship = 0;
				}

				if ($tax == '') {

					$tax = 0;
				}

				if ($grand == '') {

					$grand = 0;
				}

				$total = currency($total);

				$ship  = currency($ship);

				$tax   = currency($tax);

				$grand = currency($grand);

				echo $total . '-' . $ship . '-' . $tax . '-' . $grand . '-' . $count;
			}

			if ($para2 == 'prices') {

				$carted = $this->cart->contents();

				$return = array();

				foreach ($carted as $row) {

					$return[] = array('id' => $row['rowid'], 'price' => currency($row['price']), 'subtotal' => currency($row['subtotal']));
				}

				echo json_encode($return);
			}
		}
	}

	/* FUNCTION: Loads Cart Checkout Page*/

	function cart_checkout($para1 = "")

	{

		$carted = $this->cart->contents();

		if (count($carted) <= 0) {

			redirect(base_url() . 'index.php/home/', 'refresh');
		}

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

	function user_checkoutApi()

	{

		$datas = json_decode($this->input->raw_input_stream, 1);
		$method = 'new';
		if ($method == 'new') {
			if ($datas['mode'] == 'user' && isset($datas['cart']) && $datas['cart'] != '' && is_array($datas['cart'])) {
				//echo "a"; exit;
				$data['total_invoice_id'] = $total_invoice_id = $this->db->order_by('sale_id', 'desc')->limit('1')->get('sale')->row()->sale_id;
				foreach ($datas['cart'] as $count) {

					$added_by = json_decode($this->db->get_where('product', array('product_id' => $count['product_id']))->row()->added_by, true);
					if ($added_by['type'] == 'vendor') {
						$vendorcount[] = $added_by['id'];
					} else {
						$vendorcount[] = 'admin';
					}
				}
				// ++++++++++++++    THIS IS FOR IF ERR IN SHIPPING FUTURE(NEED TO CHANGE INVOICE ALSO) +++++++++++
				//$vendor_prod_count = array_count_values($vendorcount);
				$vendorcount = array_unique($vendorcount);
				//$order_id='OD'.substr(time(),4).rand(1,10).rand(1,99);
				$data['sale_datetime'] = time();
				$invoice_id = uniqid();
				$vat_per = '';
				$userID = $datas['userID'];
				$shipping = $datas['shipping'];
				$shipping_tax = $datas['shipping_tax'];
				$cartDetails = $datas['cart'];
				$userDetails = $this->db->get_where('user', array('user_id' => $userID))->result_array();
				$userDetails = $userDetails[0];
				$balance = $userDetails['wallet'];
				$data['buyer'] = $userID;

				if ($datas['payment_type'] == 'cash_on_delivery') {
					foreach ($datas['cart'] as $cart) {
						$no_qty = $cart['qty'];
						$i = 1;
						$product_id = $cart['product_id'];
						$productInfo[] = $this->db->get_where('product', array('product_id' => $product_id))->result_array();
						$productInfo = $productInfo[0][0];
						$pro['id'] = $cart['product_id'];
						$pro['qty'] = $cart['qty'];
						$productColor = $cartOption['color'] = $productInfo['color'];
						$productName = $cartOption['title'] = $productInfo['title'];
						$cartOption['value'] = "";
						$pro['option'] = $cartOption;
						$pro['price'] = $productInfo['sale_price'];
						$pro['name'] = $productInfo['title'];
						$data['shipping'] = $pro['shipping'] = $shipping;
						if ($productInfo['discount'] != '0.00') {
							if ($productInfo['discount_type'] == 'percent') {
								$modes = '%';
								$pro['discount'] = $productInfo['discount'] . '' . $modes;
								$val_dis = $productInfo['sale_price'] * ($productInfo['discount'] / 100);
								$pro['saleprice'] = $productPrice = $cartV['price'] = $productInfo['sale_price'] - $val_dis;
							} else {
								$modes = 'Rs.';
								$pro['discount'] = $modes . '' . $productInfo['discount'];
								$pro['saleprice'] = $productPrice = $cartV['price'] = $productInfo['sale_price'] - $productInfo['discount'];
							}
						} else {
							$pro['saleprice'] = $productPrice = $cartV['price'] = $productInfo['sale_price'];
						}
						$address_unicid = $datas['address_unicid'];
						if ($address_unicid != "") {
							$shipping_address = $this->db->get_where('shipping_address', array('unique_id' => $address_unicid))->result_array();
							//	echo $this->db->last_query();
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
							$sh['firstname'] = $datas['firstname'];
							$sh['lastname'] = $datas['lastname'];
							$sh['address1'] = $datas['address1'];
							$sh['address2'] = $datas['address2'];
							$sh['zip'] = $datas['zip'];
							$sh['phone'] = $datas['email'];
							$sh['email'] = $datas['phone'];
							$country = $sh['country'] = $datas['country'] = 'India';
							$sh['state'] =  $datas['state'] = 'TamilNadu';
							$sh['cities'] = $datas['cities'] = 'Trichy';
							$sh['short_country'] = $datas['cou_shrt1'] = 'IND';
							$data['shipping_address'] = json_encode($sh);
						}
						$salePrice = $productPrice * $cart['qty'];

						if ($productInfo['tax'] != '' && $productInfo['tax'] != 0) {
							if ($productInfo['tax_type'] == 'percent') {
								$tax = $salePrice * ($productInfo['tax'] / 100);
							} else
								$tax = $productInfo['tax'];
						} else
							$tax = 0.00;
						$pro['tax'] = $cartV['tax'] = $tax;
						$pro['image'] = $this->crud_model->file_view('product', $cart['product_id'], '', '', 'thumb', 'src', 'multi', 'one');
						$pro['coupon'] = $cart['coupon'];
						$rowid = $pro['rowid'] = rand(10000, 100000) . rand(10000, 100000);

						//	$pro['subtotal']=$salePrice +$pro['gst_amount']; //+$pro['tax'];
						$pro['subtotal'] = $salePrice + $tax;
						$pro1 = array($rowid => $pro);
						$data['product_details'] = json_encode($pro1);

						$data['vat_percent'] = $vat_per;
						$data['delivery_status']   = '[]';
						$data['payment_type'] = 'cash_on_delivery';
						$data['order_type'] = 'shopping';
						$data['payment_status']    = '[]';
						$data['payment_details']   = '';
						//	$product_total=($productInfo['sale_price']+$data['shipping'])*$cart['qty']; //$ct['tax']+

						$data['grand_total'] += $pro['subtotal'];
						$data['delivary_datetime'] = '';
						$data['group_deal'] = 0;
						$data['invoice_id'] = $invoice_id;
						//$data['order_id']=$order_id;
						$data['status'] = 'success';
						//	echo "<pre>"; print_r($data); echo "</pre>";
						$this->db->insert('sale', $data);
						//	echo $this->db->last_query(); exit;
						$sale_id = $this->db->insert_id();
						if ($userID != '') {
							$data['buyer'] = $userID;
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
						$order_id = $data['order_id'] = "PYC-OD-" . str_pad($sale_id, 6, "0", STR_PAD_LEFT);
						$data['invoice_code'] = "PYC/IN/" . str_pad($sale_id, 6, "0", STR_PAD_LEFT);
						$data['delivery_status'] = json_encode($delivery_status);
						$data['payment_status'] = json_encode($payment_status);
						//echo "<pre>"; print_r($productInfo); echo "</pre>";
						$this->db->where('sale_id', $sale_id);
						$this->db->update('sale', $data);
						$this->crud_model->digital_to_customer($sale_id);
					}
					foreach ($datas['cart'] as $value) {
						$this->crud_model->decrease_quantity($value['product_id'], $value['qty']);
						$data1['type']         = 'destroy';
						$data1['category']     = $this->db->get_where('product', array('product_id' => $value['product_id']))->row()->category;
						$data1['sub_category'] = $this->db->get_where('product', array('product_id' => $value['product_id']))->row()->sub_category;
						$data1['product']      = $value['product_id'];
						$data1['quantity']     = $value['qty'];
						$data1['total']        = 0;
						$data1['reason_note']  = 'sale';
						$data1['sale_id']      = $sale_id;
						$data1['datetime']     = time();
						$this->db->insert('stock', $data1);
					}
					$this->crud_model->email_invoice($order_id);
					$this->cart->destroy();
					$this->session->set_userdata('couponer', '');

					//INVOICE
					$order_details = $this->db->get_where('sale', array('order_id' => $order_id))->result_array();
					foreach ($order_details as $row) {
						$product_details = json_decode($row['product_details'], true);
						$total = 0;
						foreach ($product_details as $p) {
							$pDte[] = $p;
						}
						$product_details = $pDte;
						$gst_amount += $p['tax'];
						$subtotal += $p['subtotal'];
						$total += $subtotal;
						$tot_qty += $p['qty'];
					}
					$grand_total = round($total + $shipping);
					$shipping_address = json_decode($order_details[0]['shipping_address'], true);
					$payment_type = $order_details[0]['payment_type'];
					$payment_status = json_decode($order_details[0]['payment_status'], 1);
					$delivery_status = json_decode($order_details[0]['delivery_status'], 1);
					$results['status'] = 'SUCCESS';
					$results['Message'] = 'Order Completed-' . $order_id;
					$results['Response'] = array("order_id" => $order_id, "product_details" => $product_details, "shipping_cost" => $shipping, "total_amount" => $grand_total, "shipping_address" => $shipping_address, "payment_status" => $payment_status, "delivery_status" => $delivery_status, "payment_type" => $payment_type, "create_date" => date('Y-m-d h:i:s'));
					echo json_encode($results, true);
					exit;
				} elseif ($datas['payment_type'] == 'ipay88') {
					$order_id = $data['order_id'] = 'OD' . substr(time(), 4) . rand(1, 10) . rand(1, 99);
					//echo "ee"; exit;
					foreach ($datas['cart'] as $cart) {
						$no_qty = $cart['qty'];
						$i = 1;
						$product_id = $cart['product_id'];
						$productInfo[] = $this->db->get_where('product', array('product_id' => $product_id))->result_array();
						$productInfo = $productInfo[0][0];
						$pro['id'] = $cart['product_id'];
						$pro['qty'] = $cart['qty'];
						//echo $cart['weight']; exit;
						$cartOption['color'] = array("title" => "Color", "value" => $cart['color']);

						$cartOption['choice_0'] = array("title" => "Weight", "value" => $cart['weight']);

						$cartOption['choice_1'] = array("title" => "Cut size", "value" => $cart['cutsize']);


						$pro['option'] = $cartOption;
						$pro['price'] = $cart['price'];
						$rwd = $this->db->get_where('general_settings', array('type' => 'rewards'))->row()->value;

						if ($rwd == 'ok') {
							if ($datas['userID'] != '') {
								$reward_p = $this->db->get_where('rewards', array('id' => '1'))->result_array();
								$reward_p = $reward_p[0];
								//  print_r($reward_p);
								if ($reward_p['type'] == '%') {
									$data['rewards'] = ($cart['price'] * $reward_p['rewards']) / 100;
								} else if ($reward_p['type'] == 'flat') {
									$data['rewards'] = $reward_p['rewards'];
								}
							}
						}
						$pro['subtotal'] = $cart['price'] * $cart['qty'];
						$pro['name'] = $productInfo['title'];

						if ($datas['order_type'] == 'delivery') {
							$pro['shipping'] = $data['shipping'] = $datas['delivery_fee'];
						} else {
							$pro['shipping'] = $data['shipping'] = '0';
						}

						if ($productInfo['tax'] != '' && $productInfo['tax'] != 0) {
							if ($productInfo['tax_type'] == 'percent') {
								$tax = $cart['price'] * ($productInfo['tax'] / 100);
							} else
								$tax = $productInfo['tax'];
						} else
							$tax = 0.00;
						$pro['tax'] = $cartV['tax'] = $tax;
						//	$pro['saleprice']=$productPrice=$cartV['price']=$productInfo['sale_price'];


						$address_unicid = $datas['address_unicid'];
						if ($address_unicid != "") {

							$shipping_address = $this->db->get_where('shipping_address', array('unique_id' => $address_unicid))->result_array();
							//echo $this->db->last_query();
							$shipping_address = $shipping_address[0];
							$sh['firstname'] = $shipping_address['name'];
							$sh['address1'] = $shipping_address['address'];
							$sh['address2'] = $shipping_address['address1'];
							$sh['zip'] = $shipping_address['zip_code'];
							$sh['phone'] = $shipping_address['mobile'];
							$sh['email'] = $shipping_address['email'];
							//$ctysplit=explode('-',$shipping_address['country']);
							$country = $sh['country'] = $shipping_address['country'];
							$state = $sh['state'] =  $shipping_address['state'];
							$city = $sh['cities'] = $shipping_address['city'];
							//$sn_country =$sh['short_country'] = $ctysplit[1]; 
							$data['shipping_address']  = json_encode($sh);
						} else {
							$sh['firstname'] = $_POST['firstname'];
							$sh['lastname'] = $_POST['lastname'];
							$sh['address1'] = $_POST['address1'];
							$sh['address2'] = $_POST['address2'];
							$sh['zip'] = $_POST['zip'];
							$sh['phone'] = $_POST['phone'];
							$sh['email'] = $_POST['email'];

							$sh['country'] = $_POST['country'];
							$sh['state'] = $state = $_POST['state'];
							$sh['cities'] = $_POST['cities'];
							$sh['short_country'] = $_POST['cou_shrt1'];
							$data['shipping_address'] = json_encode($sh);
						}

						$pro['image'] = $this->crud_model->file_view('product', $cart['product_id'], '', '', 'thumb', 'src', 'multi', 'one');
						$pro['coupon'] = $cart['coupon'];
						//$pro['tax_rate']=$this->crud_model->get_type_name_by_id('product',$cart['product_id'] , 'tax');
						$rowid = $pro['rowid'] = rand(10000, 100000) . rand(10000, 100000);
						$salePrice = $cart['price'] * $cart['qty'];
						$pro['subtotal'] = $salePrice + $tax + $shipping; //+$pro['tax'];
						//$pro['subtotal']=$salePrice +$tax; //+$pro['tax'];
						$pro1 = array($rowid => $pro);
						//print_r($pro1); exit;
						$data['product_details'] = json_encode($pro1);
						//print_r($data['product_details']); 
						$data['vat_percent'] = $vat_per;
						$data['vat']               	= $tax;
						$data['store_id'] = $datas['store_id'];
						$data['pickup_date'] = $datas['pickup_date'];
						$data['pickup_slot'] = $datas['pickup_slot'];
						$data['pre_order_status'] = $datas['pre_order_status'];
						$data['pre_order_date'] = $datas['pre_order_date'];


						$data['delivery_status']   = '[]';
						$data['payment_type'] = 'ipay88';
						$data['order_type'] = $datas['order_type'];
						$data['payment_status']    = '[]';
						$data['payment_details']   = '';
						if ($datas['reward_using_amt'] != '') {
							$data['rewards_using'] = $datas['rewards_using'];
							$data['reward_using_amt'] = $datas['reward_using_amt'];
						}
						//$product_total=($productInfo['sale_price']+$data['shipping'])*$cart['qty']; //$ct['tax']+
						//$data['grand_total']=$grand_total= $product_total+$pro['gst_amount'];
						//echo"st".$pro['subtotal'];
						//echo"tx".$tax;
						$product_total = ($tax + $cart['price'] + $shipping) * $cart['qty'];
						$data['grand_total'] = $product_total;
						$order_amount += $data['grand_total'];
						//$grand_total+=$pro['subtotal']+$tax;
						$data['delivary_datetime'] = '';
						$data['group_deal'] = 0;
						//$data['invoice_id'] = $invoice_id;
						//$data['order_id']=$order_id;
						$data['status'] = 'pending';
						//echo "<pre>"; print_r($data); echo "</pre>";
						$this->db->insert('sale', $data);
						//echo $this->db->last_query(); exit;
						$sale_id = $this->db->insert_id();
						if ($userID != '') {
							$data['buyer'] = $userID;
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
							//$data['sale_code'] = 'VE-'.$p.'-'.date('Ym', $data['sale_datetime']) . $sale_id;
							$data['sale_code'] = 'VE-' . $p . '-' . $total_invoice_id;
							//$order_id= $order_id= $data['order_id']='OD' . substr(time(), 4) . rand(1, 10) . rand(1, 99);; 
							//$data['invoice_code'] ="PYC/IN/".$p."/".str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT); 
							//$data['seller']='VE-'.$p;
						}
						if ($this->crud_model->is_admin_in_sale($sale_id)) {
							$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');
							$payment_status[] = array('admin' => '', 'status' => 'due');

							$data['sale_code'] = 'AD-' . date('Ym', $data['sale_datetime']) . $sale_id;
							//$data['sale_code'] = 'AD-PYC-'.$total_invoice_id;


							//$data['invoice_code'] ="PYC/IN/00/".str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT); 
						}
						// $order_id=$data['order_id']="PYC-OD-".str_pad($sale_id, 6, "0", STR_PAD_LEFT); 
						//$data['invoice_code'] ="PYC/IN/".str_pad($sale_id, 6, "0", STR_PAD_LEFT); 
						$data['delivery_status'] = json_encode($delivery_status);
						$data['payment_status'] = json_encode($payment_status);
						if ($datas['order_type'] == 'delivery') {
							$data['order_amount'] = $order_amount + $datas['delivery_fee'];
						} else {
							$data['order_amount'] = $order_amount;
						}

						//echo "<pre>"; print_r($productInfo); echo "</pre>";
						$this->db->where('sale_id', $sale_id);
						$this->db->update('sale', $data);
						$this->crud_model->digital_to_customer($sale_id);
					}
					//echo"<br>".$grand_total;
					//echo"<br>".$or_tot; exit;


					$page_data['grand_total'] = $data['order_amount'];
					if ($datas['reward_using_amt'] != '') {
						//  echo $data['order_amount'];
						//  echo "<br>". $_POST['rewards'];
						$gtotals = $data['order_amount'] - $datas['reward_using_amt'];
						$data_r['order_id'] = $order_id;
						$data_r['buyer'] = $datas['userID'];
						$data_r['reward_amt'] = $datas['reward_using_amt'];
						$this->db->insert('rewards_log', $data_r);
						//echo $this->db->last_query();

						//$dt_rewarduse['reward_using']='1';
						//$this->db->where('user_id', $this->session->userdata('user_id'));
						//$this->db->update('user', $dt_rewarduse);
					} else {
						$gtotals = $data['order_amount'];
					}
					$page_data['grand_total'] = round($gtotals);
					if ($datas['userID']) {
						$datass['user_id'] = $datas['userID'];
						$datass['description'] = 'shopping';
						$datass['mode'] = 'debit';
						$datass['status'] = 'pending';
						// $datas['servicetype'] = 8;
						$datass['amount'] = $page_data['grand_total'];
						$datass['date'] = time();
						$datass['ref_id'] = $order_id;

						$this->db->insert('user_trans_log', $datass);
						// $this->db->last_query();
					} else {
						$datas['user_id'] = 'guest';
						$datas['description'] = 'shopping';
						$datas['mode'] = 'debit';
						$datas['status'] = 'pending';
						// $datas['servicetype'] = 8;
						$datas['amount'] = $page_data['grand_total'];
						$datas['date'] = time();
						$datas['ref_id'] = $order_id;

						$this->db->insert('user_trans_log', $datas);
					}
					$datawe['order_id'] = $page_data['order_id'] = $order_id;
					$page_data['userdet'] = $userID;
					$page_data['itemInfo'] = $data['product_details'];
					$page_data['address'] = $data['shipping_address'];
					$page_data['return_url'] = base_url() . 'index.php/webservice/razorcallback';
					$page_data['surl'] = base_url() . 'index.php/webservice/razorsuccess';
					$page_data['furl'] = base_url() . 'index.php/webservice/razorfailed';
					//$page_data['currency_code'] = 'INR';
					$datawe['request'] = json_encode($page_data);
					//$this->db->insert('razerpay_log', $datawe);
					//echo "<pre>"; print_r($page_data); echo "</pre>"; exit;
					$results['status'] = 'SUCCESS';
					$results['Message'] = 'Order created-' . $order_id;
					$url = base_url() . 'index.php/webservice/ipay88_checkout_page/' . $order_id;
					$results['Response'] = array("order_id" => $order_id, "order_amount" => $gtotals, "url" => $url);
					echo json_encode($results, true);
					exit;
					//$this->load->view('front/shopping_cart/checkoutrazorpayapi', $page_data);
				} elseif ($datas['payment_type'] == 'razorpay') {
					//echo "ee";
					foreach ($datas['cart'] as $cart) {


						//print_r($cart);
						$no_qty = $cart['qty'];
						$i = 1;
						$product_id = $cart['product_id'];
						$productInfo[] = $this->db->get_where('product', array('product_id' => $product_id))->result_array();
						$productInfo = $productInfo[0][0];
						$pro['id'] = $cart['product_id'];
						$pro['qty'] = $cart['qty'];
						$productColor = $cartOption['color'] = $productInfo['color'];
						$productName = $cartOption['title'] = $productInfo['title'];
						$cartOption['value'] = "";
						$pro['option'] = $cartOption;
						$pro['price'] = $productInfo['sale_price'];
						$pro['subtotal'] = $productInfo['sale_price'] * $cart['qty'];
						$pro['name'] = $productInfo['title'];
						$data['shipping'] = $pro['shipping'] = ($shipping + $shipping_tax) / count($vendorcount);
						if ($productInfo['discount'] != '0.00') {
							if ($productInfo['discount_type'] == 'percent') {
								$modes = '%';
								$pro['discount'] = $productInfo['discount'] . '' . $per;
								$val_dis = $productInfo['sale_price'] * ($productInfo['discount'] / 100);
								$pro['saleprice'] = $productPrice = $cartV['price'] = $productInfo['sale_price'] - $val_dis;
							} else {
								$modes = 'Rs.';
								$pro['discount'] = $modes . '' . $productInfo['discount'];
								$pro['saleprice'] = $productPrice = $cartV['price'] = $productInfo['sale_price'] - $productInfo['discount'];
							}
						} else {
							$pro['saleprice'] = $productPrice = $cartV['price'] = $productInfo['sale_price'];
						}

						$address_unicid = $datas['address_unicid'];
						if ($address_unicid != "") {

							$shipping_address = $this->db->get_where('shipping_address', array('unique_id' => $address_unicid))->result_array();
							//echo $this->db->last_query();
							$shipping_address = $shipping_address[0];
							$sh['firstname'] = $shipping_address['name'];
							$sh['address1'] = $shipping_address['address'];
							$sh['address2'] = $shipping_address['address1'];
							$sh['zip'] = $shipping_address['zip_code'];
							$sh['phone'] = $shipping_address['mobile'];
							$sh['email'] = $shipping_address['email'];
							$ctysplit = explode('-', $shipping_address['country']);
							$country = $sh['country'] = $ctysplit[0];
							$state = $sh['state'] =  $shipping_address['state'];
							$city = $sh['cities'] = $shipping_address['city'];
							$sn_country = $sh['short_country'] = $ctysplit[1];
							$data['shipping_address']  = json_encode($sh);
						} else {
							$sh['firstname'] = $_POST['firstname'];
							$sh['lastname'] = $_POST['lastname'];
							$sh['address1'] = $_POST['address1'];
							$sh['address2'] = $_POST['address2'];
							$sh['zip'] = $_POST['zip'];
							$sh['phone'] = $_POST['phone'];
							$sh['email'] = $_POST['email'];

							$sh['country'] = $_POST['country'];
							$sh['state'] = $state = $_POST['state'];
							$sh['cities'] = $_POST['cities'];
							$sh['short_country'] = $_POST['cou_shrt1'];
							$data['shipping_address'] = json_encode($sh);
						}

						//$salePrice=$productPrice*$cart['qty'];

						if ($productInfo['tax'] != '' && $productInfo['tax'] != 0) {
							if ($productInfo['tax_type'] == 'percent') {
								$tt = $productPrice * ($productInfo['tax'] / 100);
								$tax = $tt * $cart['qty'];
							} else {
								$tt = $productInfo['tax'];
								$tax = $productInfo['tax'] * $cart['qty'];
							}
						} else {
							$tax = 0.00;
						}
						$pro['tax'] = $cartV['tax'] = $tt;
						//echo "tx".$tax;


						$added_by = json_decode($this->db->get_where('product', array('product_id' => $product_id))->row()->added_by, true);
						if ($added_by['type'] == 'admin') {
							$product_state = 'India';
						} else if ($added_by['type'] == 'vendor') {
							$product_state = $this->db->get_where('vendor', array('vendor_id' => $added_by['id']))->row()->store_district;
						}

						$state = $this->crud_model->get_type_name_by_id('state', $state, 'name');

						if ($state == $product_state) {
							//$pro['gst_amount'] =$gst_amount = $this->crud_model->get_product_gst($ct['id'], 'inter_country')*$ct['qty'];
							$pro['applied_gst'] = 'inter_country';
						} else {
							// $pro['gst_amount'] =$gst_amount = $this->crud_model->get_product_gst($ct['id'], 'outer_country')*$ct['qty'];
							$pro['applied_gst'] = 'outer_country';
						}

						//$pro['tax']=$cartV['tax']=$tax;
						$pro['image'] = $this->crud_model->file_view('product', $cart['product_id'], '', '', 'thumb', 'src', 'multi', 'one');
						$pro['coupon'] = $cart['coupon'];
						$pro['tax_rate'] = $this->crud_model->get_type_name_by_id('product', $cart['product_id'], 'tax');
						$rowid = $pro['rowid'] = rand(10000, 100000) . rand(10000, 100000);
						//$salePrice=$productInfo['sale_price']*$cart['qty'];
						//$pro['subtotal']=$salePrice +$pro['gst_amount']; //+$pro['tax'];
						//$pro['subtotal']=$salePrice +$tax; //+$pro['tax'];
						$pro1 = array($rowid => $pro);
						$data['product_details'] = json_encode($pro1);
						//print_r($data['product_details']); exit;
						$data['vat_percent'] = $vat_per;
						$data['vat']               	= $tt;
						$data['delivery_status']   = '[]';
						$data['payment_type'] = 'razorpay';
						$data['order_type'] = 'shopping';
						$data['payment_status']    = '[]';
						$data['payment_details']   = '';
						//$product_total=($productInfo['sale_price']+$data['shipping'])*$cart['qty']; //$ct['tax']+
						//$data['grand_total']=$grand_total= $product_total+$pro['gst_amount'];
						//echo"st".$pro['subtotal'];
						//echo"tx".$tax;
						$data['grand_total'] += $pro['subtotal'] + $tax;
						$grand_total += $pro['subtotal'] + $tax;
						$data['delivary_datetime'] = '';
						$data['group_deal'] = 0;
						$data['invoice_id'] = $invoice_id;
						//$data['order_id']=$order_id;
						$data['status'] = 'pending';
						//echo "<pre>"; print_r($data); echo "</pre>";
						$this->db->insert('sale', $data);
						//echo $this->db->last_query();
						$sale_id = $this->db->insert_id();
						if ($userID != '') {
							$data['buyer'] = $userID;
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
							//$data['sale_code'] = 'VE-'.$p.'-'.date('Ym', $data['sale_datetime']) . $sale_id;
							$data['sale_code'] = 'VE-' . $p . '-' . $total_invoice_id;
							$order_id = $data['order_id'] = "PYC-OD-" . $p . "-" . str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT);
							$data['invoice_code'] = "PYC/IN/" . $p . "/" . str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT);
							//$data['seller']='VE-'.$p;
						}
						if ($this->crud_model->is_admin_in_sale($sale_id)) {
							$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');
							$payment_status[] = array('admin' => '', 'status' => 'due');
							//$data['sale_code'] = 'AD-'.date('Ym', $data['sale_datetime']) . $sale_id;
							$data['sale_code'] = 'AD-PYC-' . $total_invoice_id;
							$order_id = $data['order_id'] = "PYC-OD-00-" . str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT);
							$data['invoice_code'] = "PYC/IN/00/" . str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT);
						}
						// $order_id=$data['order_id']="PYC-OD-".str_pad($sale_id, 6, "0", STR_PAD_LEFT); 
						//$data['invoice_code'] ="PYC/IN/".str_pad($sale_id, 6, "0", STR_PAD_LEFT); 
						$data['delivery_status'] = json_encode($delivery_status);
						$data['payment_status'] = json_encode($payment_status);
						//echo "<pre>"; print_r($productInfo); echo "</pre>";
						$this->db->where('sale_id', $sale_id);
						$this->db->update('sale', $data);
						$this->crud_model->digital_to_customer($sale_id);
					}
					//echo"<br>".$grand_total;
					//echo"<br>".$grand_total;

					$page_data['grand_total'] = $grand_total + $shipping + $shipping_tax;
					$datawe['order_id'] = $page_data['order_id'] = $order_id;
					$page_data['userdet'] = $userID;
					$page_data['itemInfo'] = $data['product_details'];
					$page_data['address'] = $data['shipping_address'];
					$page_data['return_url'] = base_url() . 'index.php/webservice/razorcallback';
					$page_data['surl'] = base_url() . 'index.php/webservice/razorsuccess';
					$page_data['furl'] = base_url() . 'index.php/webservice/razorfailed';
					$page_data['currency_code'] = 'INR';
					$datawe['request'] = json_encode($page_data);
					$this->db->insert('razerpay_log', $datawe);
					//echo "<pre>"; print_r($page_data); echo "</pre>"; exit;
					$results['status'] = 'SUCCESS';
					$results['Message'] = 'Order created-' . $order_id;
					$url = base_url() . 'index.php/webservice/razerxheckout/' . $order_id;
					$results['Response'] = array("order_id" => $order_id, "url" => $url);
					echo json_encode($results, true);
					exit;
					//$this->load->view('front/shopping_cart/checkoutrazorpayapi', $page_data);
				}

				if ($datas['payment_type'] == 'pum') {
					//echo "ee";
					foreach ($datas['cart'] as $cart) {


						//print_r($cart);
						$no_qty = $cart['qty'];
						$i = 1;
						$product_id = $cart['product_id'];
						$productInfo[] = $this->db->get_where('product', array('product_id' => $product_id))->result_array();
						$productInfo = $productInfo[0][0];
						$pro['id'] = $cart['product_id'];
						$pro['qty'] = $cart['qty'];
						$productColor = $cartOption['color'] = $productInfo['color'];
						$productName = $cartOption['title'] = $productInfo['title'];
						$cartOption['value'] = "";
						$pro['option'] = $cartOption;
						$pro['price'] = $productInfo['sale_price'];
						$data['rewards'] = ($pro['price'] * 2) / 100;
						$pro['subtotal'] = $productInfo['sale_price'] * $cart['qty'];
						$pro['name'] = $productInfo['title'];
						$data['shipping'] = $pro['shipping'] = ($shipping + $shipping_tax) / count($vendorcount);
						if ($productInfo['discount'] != '0.00') {
							if ($productInfo['discount_type'] == 'percent') {
								$modes = '%';
								$pro['discount'] = $productInfo['discount'] . '' . $per;
								$val_dis = $productInfo['sale_price'] * ($productInfo['discount'] / 100);
								$pro['saleprice'] = $productPrice = $cartV['price'] = $productInfo['sale_price'] - $val_dis;
							} else {
								$modes = 'Rs.';
								$pro['discount'] = $modes . '' . $productInfo['discount'];
								$pro['saleprice'] = $productPrice = $cartV['price'] = $productInfo['sale_price'] - $productInfo['discount'];
							}
						} else {
							$pro['saleprice'] = $productPrice = $cartV['price'] = $productInfo['sale_price'];
						}

						$address_unicid = $datas['address_unicid'];
						if ($address_unicid != "") {

							$shipping_address = $this->db->get_where('shipping_address', array('unique_id' => $address_unicid))->result_array();
							//echo $this->db->last_query();
							$shipping_address = $shipping_address[0];
							$sh['firstname'] = $shipping_address['name'];
							$sh['address1'] = $shipping_address['address'];
							$sh['address2'] = $shipping_address['address1'];
							$sh['zip'] = $shipping_address['zip_code'];
							$sh['phone'] = $shipping_address['mobile'];
							$sh['email'] = $shipping_address['email'];
							$ctysplit = explode('-', $shipping_address['country']);
							$country = $sh['country'] = $ctysplit[0];
							$state = $sh['state'] =  $shipping_address['state'];
							$city = $sh['cities'] = $shipping_address['city'];
							$sn_country = $sh['short_country'] = $ctysplit[1];
							$data['shipping_address']  = json_encode($sh);
						} else {
							$sh['firstname'] = $_POST['firstname'];
							$sh['lastname'] = $_POST['lastname'];
							$sh['address1'] = $_POST['address1'];
							$sh['address2'] = $_POST['address2'];
							$sh['zip'] = $_POST['zip'];
							$sh['phone'] = $_POST['phone'];
							$sh['email'] = $_POST['email'];

							$sh['country'] = $_POST['country'];
							$sh['state'] = $state = $_POST['state'];
							$sh['cities'] = $_POST['cities'];
							$sh['short_country'] = $_POST['cou_shrt1'];
							$data['shipping_address'] = json_encode($sh);
						}

						//$salePrice=$productPrice*$cart['qty'];

						if ($productInfo['tax'] != '' && $productInfo['tax'] != 0) {
							if ($productInfo['tax_type'] == 'percent') {
								$tt = $productPrice * ($productInfo['tax'] / 100);
								$tax = $tt * $cart['qty'];
							} else {
								$tt = $productInfo['tax'];
								$tax = $productInfo['tax'] * $cart['qty'];
							}
						} else {
							$tax = 0.00;
						}
						$pro['tax'] = $cartV['tax'] = $tt;
						//echo "tx".$tax;


						$added_by = json_decode($this->db->get_where('product', array('product_id' => $product_id))->row()->added_by, true);
						if ($added_by['type'] == 'admin') {
							$product_state = 'India';
						} else if ($added_by['type'] == 'vendor') {
							$product_state = $this->db->get_where('vendor', array('vendor_id' => $added_by['id']))->row()->store_district;
						}

						$state = $this->crud_model->get_type_name_by_id('state', $state, 'name');

						if ($state == $product_state) {
							//$pro['gst_amount'] =$gst_amount = $this->crud_model->get_product_gst($ct['id'], 'inter_country')*$ct['qty'];
							$pro['applied_gst'] = 'inter_country';
						} else {
							// $pro['gst_amount'] =$gst_amount = $this->crud_model->get_product_gst($ct['id'], 'outer_country')*$ct['qty'];
							$pro['applied_gst'] = 'outer_country';
						}

						//$pro['tax']=$cartV['tax']=$tax;
						$pro['image'] = $this->crud_model->file_view('product', $cart['product_id'], '', '', 'thumb', 'src', 'multi', 'one');
						$pro['coupon'] = $cart['coupon'];
						$pro['tax_rate'] = $this->crud_model->get_type_name_by_id('product', $cart['product_id'], 'tax');
						$rowid = $pro['rowid'] = rand(10000, 100000) . rand(10000, 100000);
						//$salePrice=$productInfo['sale_price']*$cart['qty'];
						//$pro['subtotal']=$salePrice +$pro['gst_amount']; //+$pro['tax'];
						//$pro['subtotal']=$salePrice +$tax; //+$pro['tax'];
						$pro1 = array($rowid => $pro);
						$data['product_details'] = json_encode($pro1);
						//print_r($data['product_details']); exit;
						$data['vat_percent'] = $vat_per;
						$data['vat']               	= $tt;
						$data['delivery_status']   = '[]';
						$data['payment_type'] = 'pum';
						$data['order_type'] = 'shopping';
						$data['payment_status']    = '[]';
						$data['payment_details']   = '';
						//$product_total=($productInfo['sale_price']+$data['shipping'])*$cart['qty']; //$ct['tax']+
						//$data['grand_total']=$grand_total= $product_total+$pro['gst_amount'];
						//echo"st".$pro['subtotal'];
						//echo"tx".$tax;
						$data['grand_total'] += $pro['subtotal'] + $tax;
						$grand_total += $pro['subtotal'] + $tax;
						$data['delivary_datetime'] = '';
						$data['group_deal'] = 0;
						$data['invoice_id'] = $invoice_id;
						//$data['order_id']=$order_id;
						$data['status'] = 'pending';
						if ($datas['rewards'] != '') {
							$data['rewards_using'] = '1';
							$data['reward_using_amt'] = $datas['rewards'];
						}
						//echo "<pre>"; print_r($data); echo "</pre>";
						$this->db->insert('sale', $data);
						//echo $this->db->last_query();
						$sale_id = $this->db->insert_id();
						if ($userID != '') {
							$data['buyer'] = $userID;
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
							//$data['sale_code'] = 'VE-'.$p.'-'.date('Ym', $data['sale_datetime']) . $sale_id;
							$data['sale_code'] = 'VE-' . $p . '-' . $total_invoice_id;
							$order_id = $data['order_id'] = "PYC-OD-" . $p . "-" . str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT);
							$data['invoice_code'] = "PYC/IN/" . $p . "/" . str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT);
							//$data['seller']='VE-'.$p;
						}
						if ($this->crud_model->is_admin_in_sale($sale_id)) {
							$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');
							$payment_status[] = array('admin' => '', 'status' => 'due');
							//$data['sale_code'] = 'AD-'.date('Ym', $data['sale_datetime']) . $sale_id;
							$data['sale_code'] = 'AD-PYC-' . $total_invoice_id;
							$order_id = $data['order_id'] = "PYC-OD-00-" . str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT);
							$data['invoice_code'] = "PYC/IN/00/" . str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT);
						}
						// $order_id=$data['order_id']="PYC-OD-".str_pad($sale_id, 6, "0", STR_PAD_LEFT); 
						//$data['invoice_code'] ="PYC/IN/".str_pad($sale_id, 6, "0", STR_PAD_LEFT); 
						$data['delivery_status'] = json_encode($delivery_status);
						$data['payment_status'] = json_encode($payment_status);
						//echo "<pre>"; print_r($productInfo); echo "</pre>";
						$this->db->where('sale_id', $sale_id);
						$this->db->update('sale', $data);
						$this->crud_model->digital_to_customer($sale_id);
					}
					//echo"<br>".$grand_total;
					//echo"<br>".$grand_total;
					$data['grand_total'] = $grand_total + $shipping + $shipping_tax;
					if ($datas['rewards'] != '') {
						$gtotals = $data['grand_total'] - $datas['rewards'];
						$data_r['order_id'] = $order_id;
						$data_r['buyer'] = $userID;
						$data_r['reward_amt'] = $datas['rewards'];
						$this->db->insert('rewards_log', $data_r);

						//$dt_rewarduse['reward_using']='1';
						//$this->db->where('user_id', $this->session->userdata('user_id'));
						//$this->db->update('user', $dt_rewarduse);
					} else {
						$gtotals = $data['grand_total'];
					}
					$page_data['grand_total'] = round($gtotals);

					//$page_data['grand_total'] =$grand_total+$shipping+$shipping_tax; 
					$datawe['order_id'] = $page_data['order_id'] = $order_id;
					$page_data['userdet'] = $userID;
					$page_data['itemInfo'] = $data['product_details'];
					$page_data['address'] = $data['shipping_address'];
					$page_data['return_url'] = base_url() . 'index.php/webservice/pum_success';
					$page_data['surl'] = base_url() . 'index.php/webservice/pum_success';
					$page_data['furl'] = base_url() . 'index.php/webservice/pum_failure';
					$page_data['currency_code'] = 'INR';
					$datawe['request'] = json_encode($page_data);
					$this->db->insert('payu_log', $datawe);
					//echo $this->db->last_query();
					//echo "<pre>"; print_r($page_data); echo "</pre>"; exit;
					$results['status'] = 'SUCCESS';
					$results['Message'] = 'Order created-' . $order_id;
					$url = base_url() . 'index.php/webservice/payucheckout/' . $order_id;
					$results['Response'] = array("order_id" => $order_id, "url" => $url);
					echo json_encode($results, true);
					exit;
					//$this->load->view('front/shopping_cart/checkoutrazorpayapi', $page_data);
				}
				if ($datas['payment_type'] == 'wallet') {
					foreach ($datas['cart'] as $cart) {
						$no_qty = $cart['qty'];
						$i = 1;
						$product_id = $cart['product_id'];
						$productInfo[] = $this->db->get_where('product', array('product_id' => $product_id))->result_array();

						$productInfo = $productInfo[0][0];
						$pro['id'] = $cart['product_id'];
						$pro['qty'] = $cart['qty'];
						$productColor = $cartOption['color'] = $productInfo['color'];
						$productName = $cartOption['title'] = $productInfo['title'];
						$cartOption['value'] = "";
						$pro['option'] = $cartOption;
						$pro['price'] = $productInfo['sale_price'];
						$pro['name'] = $productInfo['title'];
						$data['shipping'] = $pro['shipping'] = $shipping + $shipping_tax;
						if ($productInfo['discount'] != '0.00') {
							if ($productInfo['discount_type'] == 'percent') {
								$modes = '%';
								$pro['discount'] = $productInfo['discount'] . '' . $modes;
								$val_dis = $productInfo['sale_price'] * ($productInfo['discount'] / 100);
								$pro['saleprice'] = $productPrice = $cartV['price'] = $productInfo['sale_price'] - $val_dis;
							} else {
								$modes = 'Rs.';
								$pro['discount'] = $modes . '' . $productInfo['discount'];
								$pro['saleprice'] = $productPrice = $cartV['price'] = $productInfo['sale_price'] - $productInfo['discount'];
							}
						} else {
							$pro['saleprice'] = $productPrice = $cartV['price'] = $productInfo['sale_price'];
						}
						$address_unicid = $datas['address_unicid'];
						if ($address_unicid != "") {
							$shipping_address = $this->db->get_where('shipping_address', array('unique_id' => $address_unicid))->result_array();
							//	echo $this->db->last_query();
							$shipping_address = $shipping_address[0];
							$sh['firstname'] = $shipping_address['name'];
							$sh['address1'] = $shipping_address['address'];
							$sh['address2'] = $shipping_address['address1'];
							$sh['zip'] = $shipping_address['zip_code'];
							$sh['phone'] = $shipping_address['mobile'];
							$sh['email'] = $shipping_address['email'];
							$ctysplit = explode('-', $shipping_address['country']);
							$country = $sh['country'] = $ctysplit[0];
							$state = $sh['state'] =  $shipping_address['state'];
							$city = $sh['cities'] = $shipping_address['city'];
							$sn_country = $sh['short_country'] = $ctysplit[1];
							$data['shipping_address']  = json_encode($sh);
						} else {
							$sh['firstname'] = $datas['firstname'];
							$sh['lastname'] = $datas['lastname'];
							$sh['address1'] = $datas['address1'];
							$sh['address2'] = $datas['address2'];
							$sh['zip'] = $datas['zip'];
							$sh['phone'] = $datas['email'];
							$sh['email'] = $datas['phone'];
							$country = $sh['country'] = $datas['country'] = 'India';
							$sh['state'] =  $datas['state'] = 'TamilNadu';
							$sh['cities'] = $datas['cities'] = 'Trichy';
							$sh['short_country'] = $datas['cou_shrt1'] = 'IND';
							$data['shipping_address'] = json_encode($sh);
						}
						$salePrice = $productPrice * $cart['qty'];

						if ($productInfo['tax'] != '' && $productInfo['tax'] != 0) {
							if ($productInfo['tax_type'] == 'percent') {
								//$tax=$salePrice*($productInfo['tax']/100);
								$tt = $productPrice * ($productInfo['tax'] / 100);
								$tax = $tt * $cart['qty'];
							} else {
								//$tax=$productInfo['tax'];
								$tt = $productInfo['tax'];
								$tax = $productInfo['tax'] * $cart['qty'];
							}
						} else {
							$tax = 0.00;
						}
						$pro['tax'] = $cartV['tax'] = $tt;
						$added_by = json_decode($this->db->get_where('product', array('product_id' => $product_id))->row()->added_by, true);
						if ($added_by['type'] == 'admin') {
							$product_state = 'India';
						} else if ($added_by['type'] == 'vendor') {
							$product_state = $this->db->get_where('vendor', array('vendor_id' => $added_by['id']))->row()->store_district;
						}

						$state = $this->crud_model->get_type_name_by_id('state', $state, 'name');

						if ($state == $product_state) {
							//$pro['gst_amount'] =$gst_amount = $this->crud_model->get_product_gst($ct['id'], 'inter_country')*$ct['qty'];
							$pro['applied_gst'] = 'inter_country';
						} else {
							// $pro['gst_amount'] =$gst_amount = $this->crud_model->get_product_gst($ct['id'], 'outer_country')*$ct['qty'];
							$pro['applied_gst'] = 'outer_country';
						}
						$pro['image'] = $this->crud_model->file_view('product', $cart['product_id'], '', '', 'thumb', 'src', 'multi', 'one');
						$pro['coupon'] = $cart['coupon'];
						$rowid = $pro['rowid'] = rand(10000, 100000) . rand(10000, 100000);

						//	$pro['subtotal']=$salePrice +$pro['gst_amount']; //+$pro['tax'];
						$pro['subtotal'] = $salePrice + $tax;
						$pro1 = array($rowid => $pro);
						$data['product_details'] = json_encode($pro1);

						$data['vat_percent'] = $vat_per;
						$data['vat']               	= $tt;
						$data['delivery_status']   = '[]';
						$data['payment_type'] = 'wallet';
						$data['order_type'] = 'shopping';
						$data['payment_status']    = '[]';
						$data['payment_details']   = '';
						//	$product_total=($productInfo['sale_price']+$data['shipping'])*$cart['qty']; //$ct['tax']+

						$data['grand_total'] += $pro['subtotal'] + $tax;
						$data['delivary_datetime'] = '';
						$data['group_deal'] = 0;
						$data['invoice_id'] = $invoice_id;
						//$data['order_id']=$order_id;
						$data['status'] = 'success';
						if ($balance <= ($data['grand_total'] + $shipping + $shipping_tax)) {
							$results['status'] = 'FAILED';
							$results['Message'] = 'Insufficient Balance';
							$results['Response'] = 'Insufficient Balance';
							echo json_encode($results, true);
							exit;
						}
						//	echo "<pre>"; print_r($data); echo "</pre>";
						$this->db->insert('sale', $data);
						//	echo $this->db->last_query(); exit;
						$sale_id = $this->db->insert_id();
						if ($userID != '') {
							$data['buyer'] = $userID;
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
							$payment_status[] = array('vendor' => $p, 'status' => 'paid');
							//$data['sale_code'] = 'VE-'.$p.'-'.date('Ym', $data['sale_datetime']) . $sale_id;
							$data['sale_code'] = 'VE-' . $p . '-' . $total_invoice_id;
							$order_id = $data['order_id'] = "PYC-OD-" . $p . "-" . str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT);
							$data['invoice_code'] = "PYC/IN/" . $p . "/" . str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT);
							//$data['seller']='VE-'.$p;
						}
						if ($this->crud_model->is_admin_in_sale($sale_id)) {
							$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');
							$payment_status[] = array('admin' => '', 'status' => 'paid');
							//$data['sale_code'] = 'AD-'.date('Ym', $data['sale_datetime']) . $sale_id;
							$data['sale_code'] = 'AD-PYC-' . $total_invoice_id;
							$order_id = $data['order_id'] = "PYC-OD-00-" . str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT);
							$data['invoice_code'] = "PYC/IN/00/" . str_pad($total_invoice_id, 6, "0", STR_PAD_LEFT);
						}

						// $order_id=$data['order_id']="PYC-OD-".str_pad($sale_id, 6, "0", STR_PAD_LEFT); 
						// $data['invoice_code'] ="PYC/IN/".str_pad($sale_id, 6, "0", STR_PAD_LEFT); 

						$data['delivery_status'] = json_encode($delivery_status);
						$data['payment_status'] = json_encode($payment_status);
						//echo "<pre>"; print_r($productInfo); echo "</pre>";
						$this->db->where('sale_id', $sale_id);
						$this->db->update('sale', $data);
						$this->crud_model->digital_to_customer($sale_id);
					}

					$data4['uid'] = $userID;
					$data4['description'] = 'Rs ' . $data['grand_total'] + $shipping + $shipping_tax . ' Debited from your wallet';
					$this->db->insert('user_log', $data4);
					foreach ($datas['cart'] as $value) {
						$this->crud_model->decrease_quantity($value['product_id'], $value['qty']);
						$data1['type']         = 'destroy';
						$data1['category']     = $this->db->get_where('product', array('product_id' => $value['product_id']))->row()->category;
						$data1['sub_category'] = $this->db->get_where('product', array('product_id' => $value['product_id']))->row()->sub_category;
						$data1['product']      = $value['product_id'];
						$data1['quantity']     = $value['qty'];
						$data1['total']        = 0;
						$data1['reason_note']  = 'sale';
						$data1['sale_id']      = $sale_id;
						$data1['datetime']     = time();
						$this->db->insert('stock', $data1);
					}
					//echo $data['grand_total']+$shipping; exit;
					$this->wallet_model->reduce_user_balanceapp(($data['grand_total'] + $shipping), $userID);
					$this->crud_model->email_invoice($order_id);
					$this->cart->destroy();
					$this->session->set_userdata('couponer', '');

					//INVOICE
					$order_details = $this->db->get_where('sale', array('order_id' => $order_id))->result_array();
					foreach ($order_details as $row) {
						$product_details = json_decode($row['product_details'], true);
						$total = 0;
						foreach ($product_details as $p) {
							$pDte[] = $p;
						}
						$product_details = $pDte;
						$gst_amount += $p['tax'];
						$subtotal += $p['subtotal'];
						$total += $subtotal;
						$tot_qty += $p['qty'];
					}
					$grand_total = round($total + $shipping + $shipping_tax);

					$shipping_address = json_decode($order_details[0]['shipping_address'], true);
					$payment_type = $order_details[0]['payment_type'];
					$payment_status = json_decode($order_details[0]['payment_status'], 1);
					$delivery_status = json_decode($order_details[0]['delivery_status'], 1);
					$results['status'] = 'SUCCESS';
					$results['Message'] = 'Order Completed-' . $order_id;
					$results['Response'] = array("order_id" => $order_id, "product_details" => $product_details, "shipping_cost" => $shipping, "total_amount" => $grand_total, "shipping_address" => $shipping_address, "payment_status" => $payment_status, "delivery_status" => $delivery_status, "payment_type" => $payment_type, "create_date" => date('Y-m-d h:i:s'));
					echo json_encode($results, true);
					exit;
				}
			} else {
				$results['status'] = 'FAILED';
				$results['Message'] = 'Invalid Request';
				$results['Response'] = 'Invalid Request';
				echo json_encode($results, true);
				exit;
			}
		} else {
			if (isset($datas['userID']) && $datas['userID'] != '' && $datas['userID'] != 0 && $datas['mode'] == 'user' && isset($datas['cart']) && $datas['cart'] != '' && is_array($datas['cart'])) {
				$userID = $datas['userID'];
				$cartDetails = $datas['cart'];
				$userDetails = $this->db->get_where('user', array('user_id' => $userID))->result_array();
				$userDetails = $userDetails[0];
				$balance = $userDetails['balance'];
				$data['buyer'] = $userID;
				foreach ($datas['cart'] as $cart) {
					$cartV['id'] = $product_id = $cart['product_id'];
					$productInfo[] = $this->db->get_where('product', array('product_id' => $product_id))->result_array();
					$productInfo = $productInfo[0][0];
					$quantity = $cartV['qty'] = $cart['qty'];
					if ($cart['qty'] == '' || $cart['qty'] == 0 || $productInfo['title'] == '' || $productInfo['sale_price'] == '') {
						$results['status'] = 'FAILED';
						$results['Message'] = 'Invalid Request';
						$results['Response'] = 'Invalid Request';
						echo json_encode($results, true);
						exit;
					}
					$productColor = $cartOption['color'] = $productInfo['color'];
					$productName = $cartOption['title'] = $productInfo['title'];
					$cartOption['value'] = "";
					$cartV['option']	= $cartOption;

					if ($productInfo['discount'] != '0.00') {
						if ($productInfo['discount_type'] == 'percent') {
							$val_dis = $productInfo['sale_price'] * ($productInfo['discount'] / 100);
							$productPrice = $cartV['price'] = $productInfo['sale_price'] - $val_dis;
						} else
							$productPrice = $cartV['price'] = $productInfo['sale_price'] - $productInfo['discount'];
					} else {
						$productPrice = $cartV['price'] = $productInfo['sale_price'];
					}
					$sku_no = $productInfo['sku_no'];
					$cartV['name'] = $productInfo['title'];
					//$shipping=$cartV['shipping']=$productInfo['shipping_cost'];	
					if ($this->crud_model->get_type_name_by_id('business_settings', '3', 'value') == 'product_wise') {
						//$cartV['shipping']=  $shipping = $this->crud_model->cart_total_it('shipping');
						$shipping =   $cartV['shipping'] =  $productInfo['shipping_cost'];
					} else {
						$shipping = $this->crud_model->get_type_name_by_id('business_settings', '2', 'value');
					}
					$salePrice = $productPrice * $quantity;
					if ($productInfo['tax'] != '' && $productInfo['tax'] != 0) {
						if ($productInfo['tax_type'] == 'percent') {
							$tax = $salePrice * ($productInfo['tax'] / 100);
						} else
							$tax = $productInfo['tax'];
					} else
						$tax = 0.00;
					$cartV['tax'] = $tax;
					$productImage = $cartV['image'] = $this->crud_model->file_view('product', $cart['product_id'], '', '', 'thumb', 'src', 'multi', 'one');
					$cartV['coupon'] = $cart['coupon'];
					//$cartV['subtotal']=$grand_total+=$salePrice+$tax+$shipping;	
					$cartV['subtotal'] = $salePrice + $tax + $productInfo['shipping_cost'];
					$grand_totals += $salePrice + $tax;
					//$cartV['subtotal']=$salePrice+$tax+$shipping;	

					//$grand_total+=$salePrice+$tax+$shipping;
					$cartArray[rand(10000, 100000) . rand(10000, 100000)] = $cartV;
				}
				if ($datas['firstname'] == '' || $datas['address1'] == '' || $datas['zip'] == '') {
					$results['status'] = 'FAILED';
					$results['Message'] = 'Invalid Request';
					$results['Response'] = 'Invalid Request';
					echo json_encode($results, true);
					exit;
				}
				$grand_total				= $grand_totals + $shipping;
				$firstname					= $shippingAddress['firstname']		= $datas['firstname'];
				$lastname					= $shippingAddress['lastname']		= $datas['lastname'];
				$address1					= $shippingAddress['address1']		= $datas['address1'];
				$address2					= $shippingAddress['address2']		= $datas['address2'];
				$zip						= $shippingAddress['zip']			= $datas['zip'];
				$email						= $shippingAddress['email']			= $datas['email'];
				$mobile						= $shippingAddress['phone']			= $datas['phone'];
				$shippingAddress['langlat']	= $datas['langlat'];
				$payment_type				= $shippingAddress['payment_type']	= $datas['payment_type'];
				$data['product_details']   	= json_encode($cartArray);
				$data['shipping_address']  	= json_encode($shippingAddress);
				$data['vat']               	= $tax;
				$data['vat_percent']       	= $productInfo['tax'];
				$data['shipping']          	= $shipping;
				//$data['sku_no']         	 = $sku_no;
				$data['delivery_status']   	= '';
				$data['payment_status']    	= '[]';
				$data['payment_details']   	= '';
				$data['grand_total']       	= $grand_total;
				$data['sale_datetime']     	= time();
				$data['delivary_datetime'] 	= '';
				$data['status']      	   	= 'pending';
				if ($datas['payment_type'] == 'wallet') {
					if ($balance <= $grand_total) {
						$results['status'] = 'FAILED';
						$results['Message'] = 'Insufficient Balance';
						$results['Response'] = 'Insufficient Balance';
						echo json_encode($results, true);
						exit;
					}
					$data['payment_type']      = 'wallet';
					$data['order_id'] = 'MOMOD' . substr(time(), 4) . rand(1, 10) . rand(1, 99) . ($this->db->count_all_results('sale') + 1);
					$this->db->insert('sale', $data);
					$sale_id           = $this->db->insert_id();
					$vendors = $this->crud_model->vendors_in_sale($sale_id);
					$delivery_status = array();
					$payment_status = array();
					foreach ($vendors as $p) {
						$delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');
						$payment_status[] = array('vendor' => $p, 'status' => 'paid');
					}
					if ($this->crud_model->is_admin_in_sale($sale_id)) {
						$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');
						$payment_status[] = array('admin' => '', 'status' => 'paid');
					}
					$sale_code = $data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;
					exit;
					$data['delivery_status'] = json_encode($delivery_status);
					$data['payment_status'] = json_encode($payment_status);
					$data['payment_timestamp'] = date('Y-m-d H:i:s');
					$data['status'] = 'success';
					$this->db->where('sale_id', $sale_id);
					$this->db->update('sale', $data);
					foreach ($datas['cart'] as $value) {
						$this->crud_model->decrease_quantity($value['product_id'], $value['qty']);
						$data1['type']         = 'destroy';
						$data1['category']     = $this->db->get_where('product', array('product_id' => $value['id']))->row()->category;
						$data1['sub_category'] = $this->db->get_where('product', array('product_id' => $value['id']))->row()->sub_category;
						$data1['product']      = $value['id'];
						$data1['quantity']     = $value['qty'];
						$data1['total']        = 0;
						$data1['reason_note']  = 'sale';
						$data1['sale_id']      = $sale_id;
						$data1['datetime']     = time();
						$this->db->insert('stock', $data1);
					}

					$this->crud_model->digital_to_customer($sale_id);
					$this->crud_model->email_invoice($sale_id);
					$currentBalance = $balance - $grand_total;
					$data2['wallet'] = $currentBalance;
					$this->db->where('id', $userID);
					$this->db->update('user', $data2);
					$paymentDetails = $this->db->get_where('sale', array('sale_code' => $sale_code, 'status' => 'success'))->result_array();
					if (isset($paymentDetails[0])) {
						$paymentDetails = $paymentDetails[0];
						$product_details = json_decode($paymentDetails['product_details'], 1);
						foreach ($product_details as $p) {
							$pDte[] = $p;
						}
						$product_details = $pDte;
						$grand_total = $paymentDetails['grand_total'];
						$shipping_address = json_decode($paymentDetails['shipping_address'], 1);
						$payment_type = $paymentDetails['payment_type'];
						$payment_status = json_decode($paymentDetails['payment_status'], 1);
						$delivery_status = json_decode($paymentDetails['delivery_status'], 1);
						$sale_id = $paymentDetails['sale_id'];
						$order_id = $paymentDetails['order_id'];
						$sale_code = $paymentDetails['sale_code'];
						$results['status'] = 'SUCCESS';
						$results['Message'] = 'Order Completed-' . $order_id;
						$results['Response'] = array("sale_code" => $sale_code, "order_id" => $order_id, "product_details" => $product_details, "total_amount" => $grand_total, "shipping_address" => $shipping_address, "payment_status" => $payment_status, "delivery_status" => $delivery_status, "payment_type" => $payment_type, "create_date" => date('Y-m-d h:i:s'));
						echo json_encode($results, true);
						exit;
					} else {
						$results['status'] = 'FAILED';
						$results['Message'] = 'Invalid Order';
						echo json_encode($results, true);
						exit;
					}
					echo json_encode($results, true);
					exit;
				}
				if ($datas['payment_type'] == 'cash_on_delivery') {
					$data['payment_type']      = 'cash_on_delivery';
					$data['order_id'] = 'PTP' . substr(time(), 4) . rand(1, 10) . rand(1, 99) . ($this->db->count_all_results('sale') + 1);
					$this->db->insert('sale', $data);
					$sale_id           = $this->db->insert_id();

					$vendors = $this->crud_model->vendors_in_sale($sale_id);
					$delivery_status = array();
					$payment_status = array();
					foreach ($vendors as $p) {
						$delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');
						$payment_status[] = array('vendor' => $p, 'status' => 'paid');
					}
					if ($this->crud_model->is_admin_in_sale($sale_id)) {
						$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');
						$payment_status[] = array('admin' => '', 'status' => 'pending');
					}
					$sale_code = $data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;
					$data['delivery_status'] = json_encode($delivery_status);
					$data['payment_status'] = json_encode($payment_status);
					$data['payment_timestamp'] = date('Y-m-d H:i:s');
					$data['status'] = 'success';
					$this->db->where('sale_id', $sale_id);
					$this->db->update('sale', $data);

					foreach ($datas['cart'] as $value) {
						$this->crud_model->decrease_quantity($value['product_id'], $value['qty']);
						$data1['type']         = 'destroy';
						$data1['category']     = $this->db->get_where('product', array('product_id' => $value['id']))->row()->category;
						$data1['sub_category'] = $this->db->get_where('product', array('product_id' => $value['id']))->row()->sub_category;
						$data1['product']      = $value['id'];
						$data1['quantity']     = $value['qty'];
						$data1['total']        = 0;
						$data1['reason_note']  = 'sale';
						$data1['sale_id']      = $sale_id;
						$data1['datetime']     = time();
						$this->db->insert('stock', $data1);
					}
					$this->crud_model->digital_to_customer($sale_id);
					$this->crud_model->email_invoice($sale_id);
					$paymentDetails = $this->db->get_where('sale', array('sale_code' => $sale_code, 'status' => 'success'))->result_array();
					if (isset($paymentDetails[0])) {
						$paymentDetails = $paymentDetails[0];
						$product_details = json_decode($paymentDetails['product_details'], 1);
						foreach ($product_details as $p) {
							$pDte[] = $p;
						}
						$product_details = $pDte;
						$grand_total = $paymentDetails['grand_total'];
						$shipping_address = json_decode($paymentDetails['shipping_address'], 1);
						$payment_type = $paymentDetails['payment_type'];
						$payment_status = json_decode($paymentDetails['payment_status'], 1);
						$delivery_status = json_decode($paymentDetails['delivery_status'], 1);
						$sale_id = $paymentDetails['sale_id'];
						$order_id = $paymentDetails['order_id'];
						$sale_code = $paymentDetails['sale_code'];
						$results['status'] = 'SUCCESS';
						$results['Message'] = 'Order Completed-' . $order_id;
						$results['Response'] = array("sale_code" => $sale_code, "order_id" => $order_id, "sku_no" => $sku_no, "product_details" => $product_details, "shipping_cost" => $shipping, "total_amount" => $grand_total, "shipping_address" => $shipping_address, "payment_status" => $payment_status, "delivery_status" => $delivery_status, "payment_type" => $payment_type, "create_date" => date('Y-m-d h:i:s'));
						echo json_encode($results, true);
						exit;
					} else {
						$results['status'] = 'FAILED';
						$results['Message'] = 'Invalid Order';
						echo json_encode($results, true);
						exit;
					}
					echo json_encode($results, true);
					exit;
				}

				if ($datas['payment_type'] == 'instamojo') {

					$data['payment_type']      = 'instamojo';

					$this->db->insert('sale', $data);

					$sale_id           = $this->db->insert_id();

					$vendors 				   = $this->crud_model->vendors_in_sale($sale_id);

					$delivery_status 		   = array();

					$payment_status 		   = array();

					$system_title              = $this->crud_model->get_settings_value('general_settings', 'system_title', 'value');

					$vouguepay_id              = $this->crud_model->get_settings_value('business_settings', 'vp_merchant_id', 'value');;

					$merchant_ref              = $sale_id;

					foreach ($vendors as $p) {

						$delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('vendor' => $p, 'status' => 'pending');
					}

					if ($this->crud_model->is_admin_in_sale($sale_id)) {

						$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('admin' => '', 'status' => 'pending');
					}

					$saleCode = $data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;

					$data['delivery_status'] = json_encode($delivery_status);

					$data['payment_status'] = json_encode($payment_status);

					$this->db->where('sale_id', $sale_id);

					$this->db->update('sale', $data);

					$this->session->set_userdata('sale_id', $sale_id);

					/*instamojo*/

					$amount = $grand_total;

					$purpose = "Shopping " . $saleCode;

					try {

						$response = $this->instamojo->paymentRequestCreate(array(

							"purpose" => $purpose,

							"amount" => $amount,

							"redirect_url" => base_url() . "index.php/webservice/handleredirect/" . $time,

							"allow_repeated_payments" => false

						));

						$data1['payment_id'] = $response['id'];

						$this->db->where('sale_id', $sale_id);

						$this->db->update('sale', $data1);

						$url = $response['longurl'];

						$payment_id = $response['id'];

						$results['status'] = 'SUCCESS';

						$results['Message'] = 'Redirect to payment gateway';

						$results['Response'] = array("url" => $url, "payment_id" => $payment_id, "sale_code" => $saleCode);

						echo json_encode($results, true);

						exit;
					} catch (Exception $e) {

						$results['status'] = 'FAILED';

						$results['Message'] = 'Please try again later';

						$results['Response'] = 'Please try again later';

						echo json_encode($results, true);

						exit;
					}
				}

				if ($datas['payment_type'] == 'paypal') {

					$data['payment_type']      = 'paypal';

					$this->db->insert('sale', $data);

					$sale_id           = $this->db->insert_id();

					$vendors 				   = $this->crud_model->vendors_in_sale($sale_id);

					$delivery_status 		   = array();

					$payment_status 		   = array();

					$paypal_email              = $this->crud_model->get_type_name_by_id2('business_settings', 'paypal_email', 'value');

					// $system_title              = $this->crud_model->get_settings_value('general_settings', 'system_title', 'value');

					//$vouguepay_id              = $this->crud_model->get_settings_value('business_settings', 'vp_merchant_id', 'value');;

					$merchant_ref              = $sale_id;

					foreach ($vendors as $p) {

						$delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('vendor' => $p, 'status' => 'pending');
					}

					if ($this->crud_model->is_admin_in_sale($sale_id)) {

						$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('admin' => '', 'status' => 'pending');
					}

					$saleCode = $data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;

					$data['delivery_status'] = json_encode($delivery_status);

					$data['payment_status'] = json_encode($payment_status);

					$this->db->where('sale_id', $sale_id);

					$this->db->update('sale', $data);

					$this->session->set_userdata('sale_id', $sale_id);

					/*paypal*/

					$this->paypal->add_field('rm', 2);

					$this->paypal->add_field('no_note', 0);

					$this->paypal->add_field('cmd', '_cart');

					$this->paypal->add_field('upload', '1');

					$i = 1;





					$this->paypal->add_field('item_number_' . $i, $i);

					$this->paypal->add_field('item_name_' . $i, $val['name']);

					$this->paypal->add_field('amount_' . $i, $this->cart->format_number(($val['price'] / $exchange)));

					if ($this->crud_model->get_type_name_by_id2('business_settings', 'shipping_cost_type', 'value') == 'product_wise') {

						$this->paypal->add_field('shipping_' . $i, $this->cart->format_number((($val['shipping'] / $exchange) * $val['qty'])));
					}

					$this->paypal->add_field('tax_' . $i, $this->cart->format_number(($val['tax'] / $exchange)));

					$this->paypal->add_field('quantity_' . $i, $val['qty']);

					$i++;
				}

				if ($this->crud_model->get_type_name_by_id2('business_settings', 'shipping_cost_type', 'value') == 'fixed') {

					$this->paypal->add_field('shipping_1', $this->cart->format_number(($this->crud_model->get_type_name_by_id2('business_settings', 'shipping_cost', 'value') / $exchange)));
				}



				// $this->paypal->add_field('return', base_url() . 'webservice/paypal_success1');

				$this->paypal->add_field('custom', $sale_id);

				$this->paypal->add_field('business', $paypal_email);

				$this->paypal->add_field('notify_url', base_url() . 'webservice/paypal_ipn');

				$this->paypal->add_field('cancel_return', base_url() . 'webservice/paypal_cancel');

				$this->paypal->add_field('return', base_url() . 'webservice/paypal_success1');



				$this->paypal->submit_paypal_post();

				$results['status'] = 'SUCCESS';

				$results['Response'] = array("url" => $url, "payment_id" => $payment_id, "sale_code" => $saleCode);

				echo json_encode($results, true);

				exit;

				/*end paypal*/

				/*instamojo
		
							$amount=$grand_total;
		
							$purpose="Shopping ".$saleCode;
		
							try {
		
								$response = $this->instamojo->paymentRequestCreate(array(
		
									"purpose" => $purpose,
		
									"amount" => $amount,
		
									"redirect_url" => base_url()."index.php/webservice/handleredirect/".$time,
		
									"allow_repeated_payments"=> false
		
									));
		
									$data1['payment_id']=$response['id'];
		
									$this->db->where('sale_id', $sale_id);
		
									$this->db->update('sale', $data1);
		
									$url=$response['longurl'];
		
									$payment_id=$response['id'];
		
									$results['status'] = 'SUCCESS';
		
									$results['Message'] = 'Redirect to payment gateway';
		
									$results['Response'] = array("url"=>$url,"payment_id"=>$payment_id, "sale_code"=>$saleCode);
		
									echo json_encode($results,true);
		
									exit;
		
							}
		
							catch (Exception $e) {
		
								$results['status'] = 'FAILED';
		
								$results['Message'] = 'Please try again later';
		
								$results['Response'] = 'Please try again later';
		
								echo json_encode($results,true);
		
								exit;
		
							}*/
			} else {

				$results['status'] = 'FAILED';

				$results['Message'] = 'Invalid Request';

				$results['Response'] = 'Invalid Request';

				echo json_encode($results, true);

				exit;
			}
		}
	}

	function handleredirect($param1)

	{

		$payment_id = $_GET['payment_id'];

		$datas = json_decode($this->input->raw_input_stream, 1);

		$paymentRequestId = $_GET['payment_request_id'];

		$response = $this->instamojo->paymentRequestPaymentStatus($paymentRequestId, $payment_id);

		$data['payment_timestamp'] = date('Y-m-d H:i:s');

		if ($response['payment']['status'] == 'Credit') {

			$sale_id = $this->db->get_where('sale', array(

				'payment_id' => $paymentRequestId

			))->row()->sale_id;

			$payment_status[] = array('admin' => '', 'status' => 'paid', 'payment_id' => $paymentRequestId);

			$data['order_id'] = 'MOMOD' . substr(time(), 4) . rand(1, 10) . rand(1, 99) . ($this->db->count_all_results('sale') + 1);

			$data['payment_status'] = json_encode($payment_status);

			$data['payment_details'] = json_encode($response);

			$data['status'] = 'success';

			$this->db->where('sale_id', $sale_id);

			$this->db->update('sale', $data);

			$carted = json_decode($this->db->get_where('sale', array(

				'payment_id' => $paymentRequestId

			))->row()->product_details, 1);

			//$carted  = $this->cart->contents();

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

				$data1['total']        = $value['price'];

				$data1['reason_note']  = 'sale';

				$data1['sale_id']      = $sale_id;

				$data1['datetime']     = time();

				$this->db->insert('stock', $data1);
			}

			$this->crud_model->digital_to_customer($sale_id);

			$buyercashback = 0;

			$buyer = $this->db->get_where('sale', array('sale_id' => $sale_id))->row()->buyer;

			$buyerbalance = $this->db->get_where('user_login', array('id' => $buyer))->row()->balance;

			foreach ($carted as $value1) {

				$cashback = $this->db->get_where('product', array('product_id' => $value1['id']))->row()->cashback;

				if ($cashback > 0) {

					$type = $this->db->get_where('product', array('product_id' => $value1['id']))->row()->cashback_type;

					if ($type == 'amount') {

						$buyercashback += ($cashback * $value1['qty']);
					} else if ($type == 'percent') {

						$sale_price = $this->db->get_where('product', array('product_id' => $value1['id']))->row()->sale_price;

						$cashbackamt = ($sale_price * $cashback / 100);

						$buyercashback += ($cashbackamt * $value1['qty']);
					}
				}
			}

			if (isset($buyercashback) && $buyercashback > 0) {

				$cback = $buyerbalance + $buyercashback;

				$this->db->where('id', $buyer);

				$this->db->update('user_login', array('balance' => $cback));

				$orderid = $this->db->get_where('sale', array('sale_id' => $sale_id))->row()->order_id;

				$saleprice = $this->db->get_where('product', array('product_id' => $value1['id']))->row()->sale_price;

				$data2['user_id'] = $buyer;

				$data2['mode'] =  'credit';

				$data2['ref_id'] = $orderid;

				$data2['servicetype'] = 8;

				$data2['amount'] = $saleprice;

				$data2['balance'] = $cback;

				$data2['description'] = 'Shopping Cashback amount Rs. ' . $buyercashback . ' for Ref Id :' . $orderid;

				$data2['book_status'] = 'success';

				$data2['time_format'] = time();

				$data2['m_app'] = 1;

				$this->db->insert('user_trans_log', $data2);
			}

			$this->crud_model->email_invoice($sale_id);

			$this->crud_model->sms_order($sale_id);

			redirect(base_url() . 'index.php/webservice/close', 'refresh');
		} else {

			$sale_id = $this->db->get_where('sale', array(

				'payment_id' => $paymentRequestId

			))->row()->sale_id;

			$data['payment_details'] = json_encode($response);

			$data['status'] = 'failed';

			$this->db->where('sale_id', $sale_id);

			$this->db->update('sale', $data);

			$this->crud_model->email_invoice($sale_id);

			$this->crud_model->sms_order($sale_id);

			redirect(base_url() . 'index.php/webservice/close', 'refresh');
		}
	}

	function close()

	{

		echo "Please wait... We are processing your payment...";
	}
	function failed()

	{

		echo "Your payment failed...";
	}

	function agent_cart_checkout()

	{

		$datas = json_decode($this->input->raw_input_stream, 1);

		$action 					= 	$datas['action'];

		$agent_id 				= 	$datas['agent_id'];

		$categroy_id				=	$datas['cid'];

		$sub_category_id			=	$datas['sid'];

		$product_id				=	$datas['pid'];

		$qty						=	$datas['qty'];

		$productInfo[] = $this->db->get_where('product', array('product_id' => $product_id))->result_array();

		$productInfo = $productInfo[0][0];

		if ($productInfo['current_stock'] == 0 || $productInfo['current_stock'] == '' || $productInfo['current_stock'] == null) {

			$results['status'] = 'FAILED';

			$results['Message'] = 'Out Of Stock';

			$results['Response'] = 'Out Of Stock';

			echo json_encode($results, true);

			exit;
		}

		if ($productInfo['current_stock'] < $qty) {

			$results['status'] = 'FAILED';

			$results['Message'] = 'Your Order Quatity is Greater Then Current Stock';

			$results['Response'] = 'Your Order Quatity is Greater Then Current Stock';

			echo json_encode($results, true);

			exit;
		}

		$agentInfo[] = $this->db->get_where('agents', array('agent_id' => $agent_id))->result_array();

		$agentTotalSalesCount[] = $this->db->get_where('agent_sales', array('agent_id' => $agent_id))->result_array();

		$ZonalInfo = $this->db->get_where('zonall', array('id' => $agentInfo[0][0]['zonal_id']))->result_array();

		$AreaInfo = $this->db->get_where('zonal_area', array('id' => $agentInfo[0][0]['area_id']))->result_array();

		$ChannelInfo = $this->db->get_where('zonal_channel', array('id' => $agentInfo[0][0]['channel_id']))->result_array();

		$DistributorInfo = $this->db->get_where('zonal_distributor', array('id' => $agentInfo[0][0]['distributor_id']))->result_array();

		$zonal_id 				=	$agentInfo[0][0]['zonal_id'];

		$zonal_name				=   $ZonalInfo[0]['zonal_name'];

		$zonal_mobile				=   $ZonalInfo[0]['cp_mobile'];

		$zonal_email				=   $ZonalInfo[0]['cp_email'];

		$area_id					=	$agentInfo[0][0]['area_id'];

		$area_name				=   $AreaInfo[0]['area_name'];

		$area_mobile				=   $AreaInfo[0]['cp_mobile'];

		$area_email				=   $AreaInfo[0]['cp_email'];

		$channel_id				=	$agentInfo[0][0]['channel_id'];

		$channel_name				=   $ChannelInfo[0]['channel_name'];

		$channel_mobile			=   $ChannelInfo[0]['cp_mobile'];

		$channel_email			=   $ChannelInfo[0]['cp_email'];

		$distributor_id			=	$agentInfo[0][0]['distributor_id'];

		$distributor_name			=   $DistributorInfo[0]['distributor_name'];

		$distributor_mobile		=   $DistributorInfo[0]['cp_mobile'];

		$distributor_email		=   $DistributorInfo[0]['cp_email'];

		$time_format				=	time();

		$product_shipping_cost	=	$productInfo['shipping_cost'];

		$product_unit				=	$productInfo['unit'];

		$product_discount			=	$productInfo['discount'];

		$product_discount_type	= 	$productInfo['discount_type'];

		$product_tax				= 	$productInfo['tax'];

		$product_tax_type			=	$productInfo['tax_type'];

		$product_image			=	'-';

		$product_name				= 	$productInfo['title'];

		$product_price			= 	$productInfo['retailler_price'];

		$color 					=	$productInfo['color'];

		$first_name 				=	$datas['fname'];

		$last_name				=	$datas['lname'];

		$mobile					=	$datas['mobile'];

		$email					=	$datas['email'];

		$address1					=	$datas['address1'];

		$address2					=	$datas['address2'];

		$zip 						=	$datas['zip'];

		$payment_option			=	'wallet';

		$brand_id 				=	$productInfo['brand'];

		$sub_total				=	$product_price * $qty;

		if ($product_discount_type == 'percent')

			$discount_amount			=	($sub_total * $product_discount) / 100;

		else

			$discount_amount			=	$product_discount;

		$discount_amount = 0;

		if ($product_tax_type == 'percent')

			$tax_amount				=	($sub_total * $product_tax) / 100;

		else

			$tax_amount				=	$product_tax;

		$tax_amount = 0;

		$total_amount				=	$sub_total;

		$create_date				= 	date('Y-m-d', time());

		$cbalnc					=	$agentInfo[0][0]['account_balance'];

		$agentsalescount 			=	count($agentTotalSalesCount[0]);

		$agentsalescount			=	$agentsalescount + 1;

		$order_id 				=	"MOPRO-" . $agent_id . '-' . $agentsalescount;

		$orederId1 = 'OD' . time() . rand(10, 999);

		if ($productInfo['retailler_cashback'] != 0) {

			$retailler_cashback_value = $productInfo['retailler_cashback'];

			$distributor_cashback_value = $productInfo['distributor_cashback'];

			//Retailler Cashback

			if ($productInfo['retailler_cashback_type'] == 'percent')

				$retailler_cashback	= (($product_price * $retailler_cashback_value) / 100) * $qty;

			else

				$retailler_cashback	= $retailler_cashback_value * $qty;

			//DIstr Cashback

			if ($productInfo['retailler_cashback_type'] == 'percent')

				$distributor_cashback	= (($product_price * $distributor_cashback_value) / 100) * $qty;

			else

				$distributor_cashback	= $distributor_cashback_value * $qty;

			$cashback = 1;
		} else {

			$retailler_cashback = 0.1;

			$distributor_cashback = 0.1;

			$cashback = 1;
		}

		if ($payment_option == 'wallet') {

			if ($cbalnc >= $total_amount) {

				$ubal = $cbalnc - $total_amount;
			} else {

				$results['status'] = 'FAILED';

				$results['Message'] = 'Your Account Balance Very Low To Purchase This Product';

				$results['Response'] = 'Your Account Balance Very Low To Purchase This Product';

				echo json_encode($results, true);

				exit;
			}
		}

		$productInsertData['zonal_id'] = $zonal_id;

		$productInsertData['area_id'] = $area_id;

		$productInsertData['channel_id'] = $channel_id;

		$productInsertData['distributor_id'] = $distributor_id;

		$productInsertData['agent_id'] = $agent_id;

		$productInsertData['categroy_id'] = $categroy_id;

		$productInsertData['sub_category_id'] = $sub_category_id;

		$productInsertData['brand_id'] = $brand_id;

		$productInsertData['product_id'] = $product_id;

		$productInsertData['product_name'] = $product_name;

		$productInsertData['product_shipping_cost'] = $product_shipping_cost;

		$productInsertData['product_unit'] = $product_unit;

		$productInsertData['product_discount'] = $product_discount;

		$productInsertData['product_discount_type'] = $product_discount_type;

		$productInsertData['product_tax'] = $product_tax;

		$productInsertData['product_tax_type'] = $product_tax_type;

		$productInsertData['product_image'] = $product_image;

		$productInsertData['product_price'] = $product_price;

		$productInsertData['qty'] = $qty;

		$productInsertData['sub_total'] = $sub_total;

		$productInsertData['discount_amount'] = $discount_amount;

		$productInsertData['tax_amount'] = $tax_amount;

		$productInsertData['total_amount'] = $total_amount;

		$productInsertData['first_name'] =  $first_name;

		$productInsertData['last_name'] = $last_name;

		$productInsertData['mobile'] = $mobile;

		$productInsertData['email'] = $email;

		$productInsertData['address1'] = $address1;

		$productInsertData['address2'] = $address2;

		$productInsertData['zip'] = $zip;

		$productInsertData['payment_option'] = $payment_option;

		$productInsertData['shipping_status'] = 'Processing';

		$productInsertData['time_format'] = $time_format;

		$productInsertData['color'] = $color;

		$productInsertData['order_id'] = $order_id;

		$productInsertData['create_date'] = $create_date;

		$productInsertData['znap_orderID'] = $orederId1;

		$productInsertData['agent_commission'] = $retailler_cashback;

		$productInsertData['dist_commission'] = $distributor_cashback;

		$productInsertData['cashback'] = $cashback;

		$this->db->insert('agent', $productInsertData);

		$agentSalesId = 0;

		$agentSalesId = $this->db->insert_id();

		if ($agentSalesId > 0) {

			$this->db->where('agent_id', $agent_id);

			$this->db->update('agents', array('account_balance' => $ubal));

			$latestAgentInfo[] = $this->db->get_where('agents', array('agent_id' => $agent_id))->result_array();

			$latestAgentInfo = $latestAgentInfo[0][0];

			$cbalnc = $latestAgentInfo['account_balance'];

			$agent_log_datas['booking_id'] = $order_id;

			$agent_log_datas['agent_id'] = $agent_id;

			$agent_log_datas['amount'] = $total_amount;

			$agent_log_datas['balance'] = $cbalnc;

			$agent_log_datas['credit'] = 0;

			$agent_log_datas['debit'] = $total_amount;

			$agent_log_datas['pay_for'] = 'Booking';

			$agent_log_datas['pay_in'] = $payment_option;

			$agent_log_datas['remark'] = 'Product Purchase';

			$agent_log_datas['status'] = 'success';

			$agent_log_datas['time'] = date('h:i:s', time());

			$agent_log_datas['payment_date'] = date('Y-m-d', time());

			$this->db->insert('agent_log', $agent_log_datas);

			$pname = explode(' ', $product_name);

			$pname1Count = strlen($pname[0]);

			if (isset($pname[1]))

				$pname2Count = strlen($pname[1]);

			if ($pname1Count < 18)

				$pname1 = $pname[0];

			else

				$pname1 = substr($pname[0], 0, 22);

			if (isset($pname2Count))

				if ($pname2Count < 18)

					$pname2 = $pname[1];

				else

					$pname2 = substr($pname[1], 0, 18);

			$content = 'Order Received: We have received your order for ' . $pname1 . ' ' . $pname2 . ' with order id ' . $order_id . ' amounting to Rs.' . $total_amount . '. You can expect in next 4-5 working days. We will send you an update when your order is packed. Thanks for shopping in www.momomal.com';

			$insSaleLog['zonal_id'] = $zonal_id;

			$insSaleLog['area_id'] = $area_id;

			$insSaleLog['channel_id'] = $channel_id;

			$insSaleLog['distributor_id'] = $distributor_id;

			$insSaleLog['agent_id'] = $agent_id;

			$insSaleLog['mode'] = 'debit';

			$insSaleLog['amount'] = $total_amount;

			$insSaleLog['balance'] = $ubal;

			$insSaleLog['description'] = 'Shopping completed Order id:' . $order_id;

			$insSaleLog['book_status'] = 'SUCCESS';

			$insSaleLog['time_format'] = time();

			$insSaleLog['ref_id'] = $order_id;

			$insSaleLog['servicetype'] = 8;

			$this->db->insert('agent_sale_log', $insSaleLog);

			$insertSMS['agent_id'] = $agent_id;

			$insertSMS['mobile'] = $mobile;

			$insertSMS['content'] = $content;

			$insertSMS['priority'] = 1;

			$insertSMS['ticket_no'] = $order_id;

			$insertSMS['template_name'] = "MOMOMAL-RETAILLER-SHOPPING-ORDER";

			$variables[] = $pname1 . ' ' . $pname2;

			$variables[] = $order_id;

			$variables[] = $total_amount;

			$insertSMS['variables'] = implode('|||', $variables);

			$this->db->insert('sendsms', $insertSMS);

			$OrderInformatoin = $this->db->get_where('agent_sales', array('order_id' => $order_id))->result_array();

			$results['status'] = 'SUCCESS';

			$results['Message'] = 'SUCCESS';

			$results['Response'] = $OrderInformatoin[0];

			echo json_encode($results, true);

			exit;
		} else {

			$results['status'] = 'FAILED';

			$results['Message'] = 'Some Database Error Please Inform To Website Administrator';

			$results['Response'] = 'Some Database Error Please Inform To Website Administrator';

			echo json_encode($results, true);

			exit;
		}
	}

	function Agent_shopping_report()
	{

		$datas = json_decode($this->input->raw_input_stream, 1);

		$agent_id 				= 	$datas['agentId'];

		if (isset($agent_id) || $agent_id != '' || $agent_id != 0) {

			$agent_sales = $this->db->get_where('agent_sales', array('agent_id' => $agent_id))->result_array();

			foreach ($agent_sales as $row) {

				$response[] = $row;
			}

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);
		} else {

			$value = array("status" => "FAILED", "Message" => "FAILED", "Response" => "Invalid Data");
		}

		exit(json_encode($value));
	}

	/* FUNCTION: Loads Cart Checkout Page*/
	function show_coupon($para1 = "")

	{
		$today = date('Y-m-d');

		$allCoupons = $this->db->get_where('coupon', ['status' => 'ok', 'till>' => $today])->result_array();
		//
		//echo $this->db->last_query();
		foreach ($allCoupons as $sc) {
			$sc1 = array('coupon_id' => $sc['coupon_id'], 'title' => $sc['title'], 'code' => $sc['code'], 'till' => $sc['till'], 'min_order_amount' => $sc['min_order_amount']);
			$row['available_coupon'][] = $sc1;
		}
		//sub_category_image


		//echo '<pre>'; print_r($row['sub_category']);

		//	$row['banner']=base_url().'uploads/category_image/'.$row['banner'];

		$response['available_coupons'] = $row;








		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function coupon_check($para1 = "", $price = "")

	{

		$datas = json_decode($this->input->raw_input_stream, 1);
		$para1 = $datas['coupon_code'];
		$para2 = $datas['order_amount'];


		$c = $this->db->get_where('coupon', array('code' => $para1));

		$coupon = $c->result_array();

		if ($datas['order_amount'] >= $coupon[0]['min_order_amount']) {

			if ($c->num_rows() > 0) {

				foreach ($coupon as $row) {

					$spec = json_decode($row['spec'], true);

					$coupon_id = $row['coupon_id'];

					$till = strtotime($row['till']);
				}

				if ($till >= time()) {

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
							$pro_id = $dis_pro;
						}
					}


					$results['status'] = 'Coupon Code Activated Successfully';
					//$results['Message'] = 'Order Completed-'.$order_id;
					$results['Response'] = array("id" => $coupon_id, "coupon_code" => $para1, "set_type" => $set_type, "set" => $pro_id, "discount_type" => $type, "discount_value" => $value);
					echo json_encode($results, true);
					exit;
				}
			} else {

				$results['status'] = 'FAILED';
				$results['Message'] = 'Invalid Coupon Code';
			}
		} else {

			$results['status'] = 'FAILED';
			$results['Message'] = 'Must be more then minimum order amount';
		}

		echo json_encode($results, true);
		exit;
	}

	function coupon_check1()

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

	/* FUNCTION: Finalising Purchase*/

	function cart_finish($para1 = "", $para2 = "")

	{

		$carted = $this->cart->contents();

		if (count($carted) <= 0) {

			redirect(base_url() . 'index.php/home/', 'refresh');
		}

		if ($this->session->userdata('user_login') == 'yes') {

			$carted   = $this->cart->contents();

			$total    = $this->cart->total();

			$exchange = exchange('usd');

			$vat_per  = '';

			$vat      = $this->crud_model->cart_total_it('tax');

			if ($this->crud_model->get_type_name_by_id('business_settings', '3', 'value') == 'product_wise') {

				$shipping = $this->crud_model->cart_total_it('shipping');
			} else {

				$shipping = $this->crud_model->get_type_name_by_id('business_settings', '2', 'value');
			}

			$grand_total     = $total + $vat + $shipping;

			$product_details = json_encode($carted);

			$this->db->where('id', $this->session->userdata('user_id'));

			$this->db->update('user', array(

				'langlat' => $this->input->post('langlat')

			));

			if ($this->input->post('payment_type') == 'paypal') {

				if ($para1 == 'go') {

					$data['buyer']             = $this->session->userdata('user_id');

					$data['product_details']   = $product_details;

					$data['shipping_address']  = json_encode($_POST);

					$data['vat']               = $vat;

					$data['vat_percent']       = $vat_per;

					$data['shipping']          = $shipping;

					$data['delivery_status']   = '[]';

					$data['payment_type']      = $para1;

					$data['payment_status']    = '[]';

					$data['payment_details']   = 'none';

					$data['grand_total']       = $grand_total;

					$data['sale_datetime']     = time();

					$data['delivary_datetime'] = '';

					$paypal_email              = $this->crud_model->get_type_name_by_id('business_settings', '1', 'value');

					$this->db->insert('sale', $data);

					$sale_id           = $this->db->insert_id();

					$vendors = $this->crud_model->vendors_in_sale($sale_id);

					$delivery_status = array();

					$payment_status = array();

					foreach ($vendors as $p) {

						$delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('vendor' => $p, 'status' => 'due');
					}

					if ($this->crud_model->is_admin_in_sale($sale_id)) {

						$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('admin' => '', 'status' => 'due');
					}

					$data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;

					$data['delivery_status'] = json_encode($delivery_status);

					$data['payment_status'] = json_encode($payment_status);

					$this->db->where('sale_id', $sale_id);

					$this->db->update('sale', $data);

					$this->session->set_userdata('sale_id', $sale_id);

					/****TRANSFERRING USER TO PAYPAL TERMINAL****/

					$this->paypal->add_field('rm', 2);

					$this->paypal->add_field('no_note', 0);

					$this->paypal->add_field('cmd', '_cart');

					$this->paypal->add_field('upload', '1');

					$i = 1;

					foreach ($carted as $val) {

						$this->paypal->add_field('item_number_' . $i, $i);

						$this->paypal->add_field('item_name_' . $i, $val['name']);

						$this->paypal->add_field('amount_' . $i, $this->cart->format_number(($val['price'] / $exchange)));

						if ($this->crud_model->get_type_name_by_id('business_settings', '3', 'value') == 'product_wise') {

							$this->paypal->add_field('shipping_' . $i, $this->cart->format_number((($val['shipping'] / $exchange) * $val['qty'])));
						}

						$this->paypal->add_field('tax_' . $i, $this->cart->format_number(($val['tax'] / $exchange)));

						$this->paypal->add_field('quantity_' . $i, $val['qty']);

						$i++;
					}

					if ($this->crud_model->get_type_name_by_id('business_settings', '3', 'value') == 'fixed') {

						$this->paypal->add_field('shipping_1', $this->cart->format_number(($this->crud_model->get_type_name_by_id('business_settings', '2', 'value') / $exchange)));
					}

					//$this->paypal->add_field('amount', $grand_total);

					//$this->paypal->add_field('currency_code', currency_code());

					$this->paypal->add_field('custom', $sale_id);

					$this->paypal->add_field('business', $paypal_email);

					$this->paypal->add_field('notify_url', base_url() . 'index.php/home/paypal_ipn');

					$this->paypal->add_field('cancel_return', base_url() . 'index.php/home/paypal_cancel');

					$this->paypal->add_field('return', base_url() . 'index.php/home/paypal_success');

					$this->paypal->submit_paypal_post();

					// submit the fields to paypal

				}
			} else if ($this->input->post('payment_type') == 'c2') {

				if ($para1 == 'go') {

					$data['buyer']             = $this->session->userdata('user_id');

					$data['product_details']   = $product_details;

					$data['shipping_address']  = json_encode($_POST);

					$data['vat']               = $vat;

					$data['vat_percent']       = $vat_per;

					$data['shipping']          = $shipping;

					$data['delivery_status']   = '[]';

					$data['payment_type']      = $para1;

					$data['payment_status']    = '[]';

					$data['payment_details']   = 'none';

					$data['grand_total']       = $grand_total;

					$data['sale_datetime']     = time();

					$data['delivary_datetime'] = '';

					$c2_user = $this->db->get_where('business_settings', array('type' => 'c2_user'))->row()->value;

					$c2_secret = $this->db->get_where('business_settings', array('type' => 'c2_secret'))->row()->value;

					$this->db->insert('sale', $data);

					$sale_id           = $this->db->insert_id();

					$vendors = $this->crud_model->vendors_in_sale($sale_id);

					$delivery_status = array();

					$payment_status = array();

					foreach ($vendors as $p) {

						$delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('vendor' => $p, 'status' => 'due');
					}

					if ($this->crud_model->is_admin_in_sale($sale_id)) {

						$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('admin' => '', 'status' => 'due');
					}

					$data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;

					$data['delivery_status'] = json_encode($delivery_status);

					$data['payment_status'] = json_encode($payment_status);

					$this->db->where('sale_id', $sale_id);

					$this->db->update('sale', $data);

					$this->session->set_userdata('sale_id', $sale_id);

					$this->twocheckout_lib->set_acct_info($c2_user, $c2_secret, 'Y');

					$this->twocheckout_lib->add_field('sid', $this->twocheckout_lib->sid);				//Required - 2Checkout account number

					$this->twocheckout_lib->add_field('cart_order_id', $sale_id);	//Required - Cart ID

					$this->twocheckout_lib->add_field('total', $this->cart->format_number(($grand_total / $exchange)));

					$this->twocheckout_lib->add_field('x_receipt_link_url', base_url() . 'index.php/home/twocheckout_success');

					$this->twocheckout_lib->add_field('demo', $this->twocheckout_lib->demo);					//Either Y or N

					$this->twocheckout_lib->submit_form();

					// submit the fields to paypal

				}
			} else if ($this->input->post('payment_type') == 'vp') {

				if ($para1 == 'go') {

					if ($this->session->userdata('user_login') != 'yes') {

						$data['buyer']             = 'guest';
					} else {

						$data['buyer']             = $this->session->userdata('user_id');
					}

					$data['product_details']   = $product_details;

					$data['shipping_address']  = json_encode($_POST);

					$data['vat']               = $vat;

					$data['vat_percent']       = $vat_per;

					$data['shipping']          = $shipping;

					$data['delivery_status']   = '[]';

					$data['payment_type']      = $para1;

					$data['payment_status']    = '[]';

					$data['payment_details']   = 'none';

					$data['grand_total']       = $grand_total;

					$data['sale_datetime']     = time();

					$data['delivary_datetime'] = '';

					//$vouguepay_id              = $this->crud_model->get_type_name_by_id('business_settings', '1', 'value');

					$this->db->insert('sale', $data);

					$sale_id 				   = $this->db->insert_id();

					$vendors 				   = $this->crud_model->vendors_in_sale($sale_id);

					$delivery_status 		   = array();

					$payment_status 		   = array();

					$system_title              = $this->crud_model->get_settings_value('general_settings', 'system_title', 'value');

					$vouguepay_id              = $this->crud_model->get_settings_value('business_settings', 'vp_merchant_id', 'value');;

					$merchant_ref              = $sale_id;

					foreach ($vendors as $p) {

						$delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('vendor' => $p, 'status' => 'due');
					}

					if ($this->crud_model->is_admin_in_sale($sale_id)) {

						$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('admin' => '', 'status' => 'due');
					}

					$data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;

					$data['delivery_status'] = json_encode($delivery_status);

					$data['payment_status'] = json_encode($payment_status);

					$this->db->where('sale_id', $sale_id);

					$this->db->update('sale', $data);

					$this->session->set_userdata('sale_id', $sale_id);

					/****TRANSFERRING USER TO vouguepay TERMINAL****/

					$this->vouguepay->add_field('v_merchant_id', $vouguepay_id);

					$this->vouguepay->add_field('merchant_ref', $merchant_ref);

					$this->vouguepay->add_field('memo', 'Order from ' . $system_title);

					$i = 1;

					$tax = 0;

					$shipping = 0;

					$total = 0;

					$this->vouguepay->add_field('total', ($grand_total / $exchange));

					//$this->vouguepay->add_field('amount', $grand_total);

					//$this->vouguepay->add_field('custom', $sale_id);

					//$this->vouguepay->add_field('business', $vouguepay_email);

					$this->vouguepay->add_field('notify_url', base_url() . 'index.php/home/vouguepay_ipn');

					$this->vouguepay->add_field('fail_url', base_url() . 'index.php/home/vouguepay_cancel');

					$this->vouguepay->add_field('success_url', base_url() . 'index.php/home/vouguepay_success');

					$this->vouguepay->submit_vouguepay_post();

					// submit the fields to vouguepay

				}
			} else if ($this->input->post('payment_type') == 'cash_on_delivery') {

				if ($para1 == 'go') {

					$data['buyer']             = $this->session->userdata('user_id');

					$data['product_details']   = $product_details;

					$data['shipping_address']  = json_encode($_POST);

					$data['vat']               = $vat;

					$data['vat_percent']       = $vat_per;

					$data['shipping']          = $shipping;

					$data['delivery_status']   = '[]';

					$data['payment_type']      = 'cash_on_delivery';

					$data['payment_status']    = '[]';

					$data['payment_details']   = '';

					$data['grand_total']       = $grand_total;

					$data['sale_datetime']     = time();

					$data['delivary_datetime'] = '';

					$this->db->insert('sale', $data);

					$sale_id           = $this->db->insert_id();

					$vendors = $this->crud_model->vendors_in_sale($sale_id);

					$delivery_status = array();

					$payment_status = array();


					foreach ($vendors as $p) {

						$delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('vendor' => $p, 'status' => 'due');
					}

					if ($this->crud_model->is_admin_in_sale($sale_id)) {

						$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('admin' => '', 'status' => 'due');
					}

					$data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;

					$data['delivery_status'] = json_encode($delivery_status);

					$data['payment_status'] = json_encode($payment_status);

					$this->db->where('sale_id', $sale_id);

					$this->db->update('sale', $data);

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

					$this->crud_model->email_invoice($sale_id);

					$this->cart->destroy();

					$this->session->set_userdata('couponer', '');

					//echo $sale_id;

					redirect(base_url() . 'index.php/home/invoice/' . $sale_id, 'refresh');
				}
			} else if ($this->input->post('payment_type') == 'wallet') {

				if ($para1 == 'go') {

					$tmpUserId = '';

					$tmpUserId = $data['buyer']             = $this->session->userdata('user_id');

					$data['product_details']   = $product_details;

					$data['shipping_address']  = json_encode($_POST);

					$data['vat']               = $vat;

					$data['vat_percent']       = $vat_per;

					$data['shipping']          = $shipping;

					$data['delivery_status']   = '[]';

					$data['payment_type']      = 'wallet';

					$data['payment_status']    = '[]';

					$data['payment_details']   = '';

					$data['grand_total']       = $grand_total;

					$data['sale_datetime']     = time();

					$data['delivary_datetime'] = '';

					$this->db->insert('sale', $data);

					$sale_id           = $this->db->insert_id();

					exit;

					$vendors = $this->crud_model->vendors_in_sale($sale_id);

					$delivery_status = array();

					$payment_status = array();

					foreach ($vendors as $p) {

						$delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('vendor' => $p, 'status' => 'paid');
					}

					if ($this->crud_model->is_admin_in_sale($sale_id)) {

						$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');

						$payment_status[] = array('admin' => '', 'status' => 'paid');
					}

					$data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;

					$data['delivery_status'] = json_encode($delivery_status);

					$data['payment_status'] = json_encode($payment_status);

					$this->db->where('sale_id', $sale_id);

					$this->db->update('sale', $data);

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

					$this->crud_model->email_invoice($sale_id);

					$this->cart->destroy();

					$this->session->set_userdata('couponer', '');

					$currentBalance = $_SESSION['user']['balance'] - $grand_total;

					$data2['balance'] = $currentBalance;

					$this->db->where('id', $tmpUserId);


					$this->db->update('user', $data2);

					$_SESSION['user']['balance'] = $currentBalance;

					$tmpUserId = '';

					$currentBalance = '';

					//echo $sale_id;

					redirect(base_url() . 'index.php/home/invoice/' . $sale_id, 'refresh');
				}
			} else if ($this->input->post('payment_type') == 'stripe') {

				if ($para1 == 'go') {

					if (isset($_POST['stripeToken'])) {

						require_once(APPPATH . 'libraries/stripe-php/init.php');

						$stripe_api_key = $this->db->get_where('business_settings', array('type' => 'stripe_secret'))->row()->value;

						\Stripe\Stripe::setApiKey($stripe_api_key); //system payment settings

						$customer_email = $this->db->get_where('user', array('id' => $this->session->userdata('user_id')))->row()->email;

						$customer = \Stripe\Customer::create(array(

							'email' => $customer_email, // customer email id

							'card'  => $_POST['stripeToken']

						));

						$charge = \Stripe\Charge::create(array(

							'customer'  => $customer->id,

							'amount'    => ceil($grand_total * 100 / $exchange),

							'currency'  => 'USD'

						));

						if ($charge->paid == true) {

							$customer = (array) $customer;

							$charge = (array) $charge;

							$data['buyer']             = $this->session->userdata('user_id');

							$data['product_details']   = $product_details;

							$data['shipping_address']  = json_encode($_POST);

							$data['vat']               = $vat;

							$data['vat_percent']       = $vat_per;

							$data['shipping']          = $shipping;

							$data['delivery_status']   = 'pending';

							$data['payment_type']      = 'stripe';

							$data['payment_status']    = 'paid';

							$data['payment_details']   = "Customer Info: \n" . json_encode($customer, true) . "\n \n Charge Info: \n" . json_encode($charge, true);

							$data['grand_total']       = $grand_total;

							$data['sale_datetime']     = time();

							$data['delivary_datetime'] = '';

							$this->db->insert('sale', $data);

							$sale_id           = $this->db->insert_id();

							$vendors = $this->crud_model->vendors_in_sale($sale_id);

							$delivery_status = array();

							$payment_status = array();

							foreach ($vendors as $p) {

								$delivery_status[] = array('vendor' => $p, 'status' => 'pending', 'delivery_time' => '');

								$payment_status[] = array('vendor' => $p, 'status' => 'paid');
							}

							if ($this->crud_model->is_admin_in_sale($sale_id)) {

								$delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');

								$payment_status[] = array('admin' => '', 'status' => 'paid');
							}

							$data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;

							$data['delivery_status'] = json_encode($delivery_status);

							$data['payment_status'] = json_encode($payment_status);

							$this->db->where('sale_id', $sale_id);

							$this->db->update('sale', $data);

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

							$this->crud_model->email_invoice($sale_id);

							$this->cart->destroy();

							$this->session->set_userdata('couponer', '');

							redirect(base_url() . 'index.php/home/invoice/' . $sale_id, 'refresh');
						} else {

							$this->session->set_flashdata('alert', 'unsuccessful_stripe');

							redirect(base_url() . 'index.php/home/cart_checkout/', 'refresh');
						}
					} else {

						$this->session->set_flashdata('alert', 'unsuccessful_stripe');

						redirect(base_url() . 'index.php/home/cart_checkout/', 'refresh');
					}
				}
			} else if ($this->input->post('payment_type') == 'ccavenue') {

				if ($para1 == 'go') {

					//CCAvenue Access code : AVDR05CG72BR76RDRB

					//Working Key :  CF939418BB6847E03D0D4DEAD5CBC19B

					require_once(APPPATH . 'libraries/ccavenue/adler32.php');

					require_once(APPPATH . 'libraries/ccavenue/Aes.php');

					error_reporting(0);

					$merchant_id = $_POST['Merchant_Id'];  // Merchant id(also User_Id) 

					$amount = $_POST['Amount'];            // your script should substitute the amount here in the quotes provided here

					$order_id = $_POST['Order_Id'];        //your script should substitute the order description here in the quotes provided here

					$url = $_POST['Redirect_Url'];         //your redirect URL where your customer will be redirected after authorisation from CCAvenue

					$billing_cust_name = $_POST['billing_cust_name'];

					$billing_cust_address = $_POST['billing_cust_address'];

					$billing_cust_country = $_POST['billing_cust_country'];

					$billing_cust_state = $_POST['billing_cust_state'];

					$billing_city = $_POST['billing_city'];

					$billing_zip = $_POST['billing_zip'];

					$billing_cust_tel = $_POST['billing_cust_tel'];

					$billing_cust_email = $_POST['billing_cust_email'];

					$delivery_cust_name = $_POST['delivery_cust_name'];

					$delivery_cust_address = $_POST['delivery_cust_address'];

					$delivery_cust_country = $_POST['delivery_cust_country'];

					$delivery_cust_state = $_POST['delivery_cust_state'];

					$delivery_city = $_POST['delivery_city'];

					$delivery_zip = $_POST['delivery_zip'];

					$delivery_cust_tel = $_POST['delivery_cust_tel'];

					$delivery_cust_notes = $_POST['delivery_cust_notes'];

					$working_key = 'CF939418BB6847E03D0D4DEAD5CBC19B';    //Put in the 32 bit alphanumeric key in the quotes provided here.

					$checksum = getchecksum($merchant_id, $amount, $order_id, $url, $working_key); // Method to generate checksum

					$merchant_data = 'Merchant_Id=' . $merchant_id . '&Amount=' . $amount . '&Order_Id=' . $order_id . '&Redirect_Url=' . $url . '&billing_cust_name=' . $billing_cust_name . '&billing_cust_address=' . $billing_cust_address . '&billing_cust_country=' . $billing_cust_country . '&billing_cust_state=' . $billing_cust_state . '&billing_cust_city=' . $billing_city . '&billing_zip_code=' . $billing_zip . '&billing_cust_tel=' . $billing_cust_tel . '&billing_cust_email=' . $billing_cust_email . '&delivery_cust_name=' . $delivery_cust_name . '&delivery_cust_address=' . $delivery_cust_address . '&delivery_cust_country=' . $delivery_cust_country . '&delivery_cust_state=' . $delivery_cust_state . '&delivery_cust_city=' . $delivery_city . '&delivery_zip_code=' . $delivery_zip . '&delivery_cust_tel=' . $delivery_cust_tel . '&billing_cust_notes=' . $delivery_cust_notes . '&Checksum=' . $checksum;

					$encrypted_data = encrypt($merchant_data, $working_key); // Method for encrypting the data.

				}
			} else if ($this->input->post('payment_type') == 'skrill') {

				if ($para1 == 'go') {

					$config['detail1_text'] = ''; //Text about your services

					$config['amount'] = '200'; // get post values

					$this->load->library('skrill', $config);

					$this->skrill->pay();
				}
			}
		} else {

			//echo 'nope';

			redirect(base_url() . 'index.php/home/cart_checkout/need_login', 'refresh');
		}
	}

	/* FUNCTION: Verify paypal payment by IPN*/

	function paypal_ipn()

	{

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

			$data['payment_status'] = json_encode($payment_status);

			$this->db->where('sale_id', $sale_id);

			$this->db->update('sale', $data);
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
	}

	function paypal_success1()

	{

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

		redirect(base_url() . 'index.php/webservice/close', 'refresh');

		//$this->session->set_userdata('sale_id', '');

		// redirect(base_url() . 'index.php/home/invoice/' . $sale_id, 'refresh');

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

	function wishlist($para1 = "", $para2 = "", $para3 = "")

	{

		if ($para1 == 'add') {

			$this->crud_model->add_wish_webservice($para2, $para3);

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => "Wishlist added successfully");

			exit(json_encode($value));
		} else if ($para1 == 'remove') {

			$this->crud_model->remove_wish_webservice($para2, $para3);

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => "Wishlist removed successfully");

			exit(json_encode($value));
		} else if ($para1 == 'list') {

			$wishlist['wishlistProducts'] = json_decode($this->crud_model->wished_num_webservice($para2));

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $wishlist);

			exit(json_encode($value));
		} else if ($para1 == 'count') {

			$wishlist['WishlistCount'] = count(json_decode($this->crud_model->wished_num_webservice($para2)));

			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $wishlist);

			exit(json_encode($value));
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

	function others_product($para1 = "")
	{

		$page_data['product_type'] = $para1;

		$page_data['page_name']   = 'others_list';

		$page_data['asset_page']  = 'product_list_other';

		$page_data['page_title']  = translate($para1);

		$this->load->view('front/index', $page_data);
	}

	function product_by_type($para1 = "")
	{
		$page_data['product_type'] = $para1;
		$this->load->view('front/others_list/view', $page_data);
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

	function invoice($para1 = "")
	{
		//echo 1; exit;
		//$datas = json_decode($this->input->raw_input_stream,1);

		//$sale_code=$datas['order_id'];
		$sale_code = $para1;

		$paymentDetail = $this->db->get_where('sale', array('order_id' => $sale_code, 'status' => 'admin_pending'))->result_array();

		if (isset($paymentDetail[0])) {
			foreach ($paymentDetail as $paymentDetails) {
				// print_r($paymentDetails);
				//$paymentDetails=$paymentDetails[0];

				$product_details = json_decode($paymentDetails['product_details'], 1);

				foreach ($product_details as $p) {

					$pDte[] = $p;
				}

				$product_details = $pDte;

				$grand_total = $paymentDetails['grand_total'];

				$shipping_address = json_decode($paymentDetails['shipping_address'], 1);

				$payment_type = $paymentDetails['payment_type'];

				$payment_status = json_decode($paymentDetails['payment_status'], 1);

				$delivery_status = json_decode($paymentDetails['delivery_status'], 1);

				//$sale_id=$paymentDetails['sale_id'];

				$order_id = $paymentDetails['order_id'];

				$sale_code = $paymentDetails['sale_code'];

				$results['status'] = 'SUCCESS';

				$results['Message'] = 'Order Completed-' . $order_id;
			}

			$results['Response'] = array("order_id" => $order_id, "product_details" => $product_details, "total_amount" => $grand_total, "shipping_address" => $shipping_address, "payment_status" => $payment_status, "delivery_status" => $delivery_status, "payment_type" => $payment_type, "create_date" => date('Y-m-d h:i:s'));

			echo json_encode($results, true);

			exit;
		} else {

			$results['status'] = 'FAILED';

			$results['Message'] = 'Invalid Order';

			echo json_encode($results, true);

			exit;
		}
	}


	/* FUNCTION: Legal pages load - terms & conditions / privacy policy*/

	function legal($type = "")

	{

		$page_data['type']       = $type;

		$page_data['page_name']  = "others/legal";

		$page_data['asset_page']    = "legal";

		$page_data['page_title'] = translate($type);

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

		$return = '' . '<input type="text" id="rangelvl" value="" name="range" />' . '<script>' . '	$("#rangelvl").ionRangeSlider({' . '		hide_min_max: false,' . '		keyboard: true,' . '		min:' . $min . ',' . '		max:' . $max . ',' . '		from:' . $start . ',' . '		to:' . $end . ',' . '		type: "double",' . '		step: 1,' . '		prefix: "' . currency() . '",' . '		grid: true,' . '		onFinish: function (data) {' . "			filter('click','none','none','0');" . '		}' . '	});' . '</script>';

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
		$otherurls = array(base_url() . 'index.php/home/contact/', base_url() . 'index.php/home/legal/terms_conditions', base_url() . 'index.php/home/legal/privacy_policy');
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

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $page_data);
		exit(json_encode($value));
	}




	/* FUNCTION: Loads Contact Page */

	function blog_by_id($para1 = "")
	{
		$premium_package = $this->db->get_where('blog', array('blog_id' => $para1))->result_array();
		foreach ($premium_package as $pp) {
			$data1['blog_id'] = $pp['blog_id'];
			$data1['title'] = $pp['title'];
			$data1['summery'] = $pp['summery'];
			$data1['description'] = $pp['description'];
			$data1['author'] = $pp['author'];
			$data1['date'] = $pp['date'];

			$data1['image'] = base_url() . 'uploads/blog_image/' . $pp['banner'];


			$premium_package1['blog_List'][] = $data1;
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $premium_package1);
		//  exit(json_encode($value));  

		exit(json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	/* FUNCTION: Loads Contact Page */

	function blog_by_cat($para1 = "")
	{
		$blog = array();
		$premium_package = $this->db->get_where('blog', array('blog_category' => $para1))->result_array();

		foreach ($premium_package as $pp) {
			$data1['blog_id'] = $pp['blog_id'];
			$data1['title'] = $pp['title'];
			$data1['summery'] = $pp['summery'];
			$data1['author'] = $pp['author'];
			$data1['date'] = $pp['date'];

			$data1['image'] = base_url() . 'uploads/blog_image/' . $pp['banner'];


			$premium_package1['blog_List'][] = $data1;
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $premium_package1);
		exit(json_encode($value));

		//$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$blog);
		//exit(json_encode($value));
	}
	function blog_all_list($para1 = "")
	{
		$blog = array();
		$premium_package = $this->db->get('blog')->result_array();

		foreach ($premium_package as $pp) {
			$data1['blog_id'] = $pp['blog_id'];
			$data1['title'] = $pp['title'];
			$data1['summery'] = $pp['summery'];
			$data1['author'] = $pp['author'];
			$data1['date'] = $pp['date'];

			$data1['image'] = base_url() . 'uploads/blog_image/' . $pp['banner'];
			https: //myrunciit.my/uploads/blog_image/blog_9_thumb.jpg

			$premium_package1['blog_List'][] = $data1;
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $premium_package1);
		exit(json_encode($value));

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $blog);
		exit(json_encode($value));
	}

	function siteInformation($oid)
	{
		$siteInformation = array();
		$siteInformation['siteInformation'] = $this->db->get_where('general_settings')->result_array();
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $siteInformation);
		exit(json_encode($value));
	}

	function blog_cat()
	{
		$blog['blog_cat'] = $this->db->get_where('blog_category')->result_array();
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $blog);
		exit(json_encode($value));
	}
	function Cancellation()
	{
		$cancel = $this->db->get_where('page', array('page_name' => 'Cancellation', 'status' => 'ok'))->result_array();

		foreach ($cancel as $pp) {
			$data1['page_id'] = $pp['page_id'];
			$data1['parts'] = $pp['parts'];
			$data1['tag'] = $pp['tag'];


			$premium_package1['Cancellation_Policy'][] = $data1;
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $premium_package1);
		exit(json_encode($value));

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $blog);
		exit(json_encode($value));
	}

	function terms_conditions()
	{
		$cancel = $this->db->get_where('general_settings', array('type' => 'terms_conditions'))->result_array();

		foreach ($cancel as $pp) {
			$data1['type'] = $pp['type'];
			$data1['value'] = $pp['value'];



			$premium_package1['terms_conditions'][] = $data1;
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $premium_package1);
		exit(json_encode($value));
	}

	function privacy_policy()
	{
		$cancel = $this->db->get_where('general_settings', array('type' => 'privacy_policy'))->result_array();

		foreach ($cancel as $pp) {
			$data1['type'] = $pp['type'];
			$data1['value'] = $pp['value'];



			$premium_package1['return_policy'][] = $data1;
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $premium_package1);
		exit(json_encode($value));
	}



	function user_subscribe()
	{
		$user_subscribe = array();
		$user_subscribe['user_subscribe'] = $this->db->get_where('subscribe')->result_array();
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $user_subscribe);
		exit(json_encode($value));
	}
	function social_media()
	{
		$gr_social_links = array();
		$gr_social_links['social_links'] = $this->db->get_where('social_links')->result_array();
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $gr_social_links);
		exit(json_encode($value));
	}

	function business_settings()
	{
		$business_settings = array();
		$business_settings['business_settings'] = $this->db->get_where('business_settings')->result_array();
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $business_settings);
		exit(json_encode($value));
	}
	function bundled_product()
	{
		$bundled_product = array();
		$this->db->order_by('product_id', 'desc');
		$this->db->where('status', 'ok');
		$this->db->where('is_bundle', 'yes');
		$bundled_product['bundled_product'] = $this->db->get('product')->result_array();
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $bundled_product);
		exit(json_encode($value));
	}
	function get_state()
	{
		$states = array();
		$this->db->order_by('state_id', 'asc');
		$this->db->where('country_id', '1');
		//$this->db->where('is_bundle', 'yes');
		$states['state_list'] = $this->db->get('state')->result_array();
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $states);
		exit(json_encode($value));
	}
	function get_city($para1 = "")
	{
		$states = array();
		$this->db->order_by('cities_id', 'asc');
		$this->db->where('state_id', $para1);
		//$this->db->where('is_bundle', 'yes');
		$cities['city_list'] = $this->db->get('cities')->result_array();
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $cities);
		exit(json_encode($value));
	}
	function customer_product()
	{

		$customer_product = array();
		$this->db->where('is_sold', 'no');
		$this->db->where('status', 'ok');
		$this->db->where('admin_status', 'ok');
		$customer_product1 = $this->db->get('customer_product')->result_array();
		foreach ($customer_product1 as $CP) {
			$pro_id = $CP['customer_product_id'];
			$num_of_img =  $CP['num_of_imgs'];

			if ($num_of_img > 0) {
				$ab1 = 0;
				for ($ab = 1; $ab <= $num_of_img; $ab++) {
					$pid = $pro_id;
					$productImage[$ab1] = base_url() . 'uploads/customer_product_image/customer_product_' . $pid . '_' . $ab . '.jpg';
					$ab1++;
				}
				$CP['banner'] = $productImage;
			} else {
				$CP['banner'] = array('' . base_url() . 'uploads/customer_product_image/default.jpg');
			}
			$customer_product1[''][] = $CP;
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $customer_product1);
		exit(json_encode($value));
	}
	function premium_package($para1 = "", $para2 = "")
	{
		if ($para1 == 'purchase') {
			if ($this->session->userdata('user_login') == "yes") {
				$premium_package1 = array();
				$this->db->where('package_id', $para2);
				$premium_package = $this->db->get('package')->result_array();
				foreach ($premium_package as $pp) {
					$data1['package_id'] = $pp['package_id'];
					$data1['name'] = $pp['name'];
					$data1['amount'] = $pp['amount'];
					$data1['upload_amount'] = $pp['upload_amount'];
					$image = json_decode($pp['image'], true);
					foreach ($image as $img) {
						$data1['image'] = base_url() . 'uploads/plan_image/' . $img['image'];
						$data1['thumb'] = base_url() . 'uploads/plan_image/' . $img['thumb'];
					}
					$premium_package1['premium_package'][] = $data1;
				}
				$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $premium_package1);
				exit(json_encode($value));
			}
		} else {
			$premium_package1 = array();

			$premium_package = $this->db->get('package')->result_array();
			foreach ($premium_package as $pp) {
				$data1['package_id'] = $pp['package_id'];
				$data1['name'] = $pp['name'];
				$data1['amount'] = $pp['amount'];
				$data1['upload_amount'] = $pp['upload_amount'];
				$image = json_decode($pp['image'], true);
				foreach ($image as $img) {
					$data1['image'] = base_url() . 'uploads/plan_image/' . $img['image'];
					$data1['thumb'] = base_url() . 'uploads/plan_image/' . $img['thumb'];
				}
				$premium_package1['premium_package'][] = $data1;
			}
			$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $premium_package1);
			exit(json_encode($value));
		}
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
				$total_invoice_id = $this->db->get_where('sale', array('order_id' => $merchant_order_id))->row()->total_invoice_id;
				//  $order_details=$this->db->get_where('sale',array('order_id'=>$merchant_order_id))->result_array();
				$order_details = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
				//echo "<pre>";	print_r($order_details); exit;
				foreach ($order_details as $row) {
					$product_details = json_decode($row['product_details'], true);
					$total = 0;
					foreach ($product_details as $row1) {
						$this->crud_model->decrease_quantity($row1['id'], $row1['qty']);
						$data1['type']         = 'destroy';
						$data1['category']     = $this->db->get_where('product', array('product_id' => $row1['id']))->row()->category;
						$data1['sub_category'] = $this->db->get_where('product', array('product_id' => $row1['id']))->row()->sub_category;
						$data1['product']      = $row1['id'];
						$data1['quantity']     = $row1['qty'];
						$data1['total']        = 0;
						$data1['reason_note']  = 'sale';
						$data1['sale_id']      = $row['sale_id'];
						$data1['datetime']     = time();
						$this->db->insert('stock', $data1);
					}
					$vendors = $this->crud_model->vendors_in_sale($row['sale_id']);
					$payment_status = array();
					foreach ($vendors as $p) {
						$payment_status[] = array('vendor' => $p, 'status' => 'paid');
					}
					if ($this->crud_model->is_admin_in_sale($sale_id)) {
						$payment_status[] = array('admin' => '', 'status' => 'paid');
					}
					$data['status'] = 'success';
					$data['payment_status'] = json_encode($payment_status);
					$this->db->where('sale_id', $row['sale_id']);
					$this->db->update('sale', $data);
				}
				//$this->crud_model->email_invoice($merchant_order_id);
				$this->crud_model->email_invoice($total_invoice_id);
				$this->session->set_userdata('couponer', '');
				redirect(base_url() . 'index.php/webservice/close', 'refresh');
			} else {
				redirect($this->input->post('merchant_furl_id'));
			}
		} else {
			echo 'An error occured. Contact site administrator, please!';
		}
	}
	public function pum_failure()
	{
		redirect(base_url() . 'index.php/webservice/failed', 'refresh');
	}
	public function pum_success()
	{
		//echo 1; exit;
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
			redirect(base_url() . 'index.php/webservice/failed', 'refresh');
		} else {

			// $data['payment_details']   = json_encode($response_array);
			$total_invoice_id = $this->db->get_where('sale', array('order_id' => $udf1))->row()->total_invoice_id;
			//  $order_details=$this->db->get_where('sale',array('order_id'=>$merchant_order_id))->result_array();
			$order_details = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
			//echo "<pre>";	print_r($order_details); exit;
			foreach ($order_details as $row) {
				$product_details = json_decode($row['product_details'], true);
				$total = 0;
				foreach ($product_details as $row1) {
					$this->crud_model->decrease_quantity($row1['id'], $row1['qty']);
					$data1['type']         = 'destroy';
					$data1['category']     = $this->db->get_where('product', array('product_id' => $row1['id']))->row()->category;
					$data1['sub_category'] = $this->db->get_where('product', array('product_id' => $row1['id']))->row()->sub_category;
					$data1['product']      = $row1['id'];
					$data1['quantity']     = $row1['qty'];
					$data1['total']        = 0;
					$data1['reason_note']  = 'sale';
					$data1['sale_id']      = $row['sale_id'];
					$data1['datetime']     = time();
					$this->db->insert('stock', $data1);
				}
				$vendors = $this->crud_model->vendors_in_sale($row['sale_id']);
				$payment_status = array();
				foreach ($vendors as $p) {
					$payment_status[] = array('vendor' => $p, 'status' => 'paid');
				}
				if ($this->crud_model->is_admin_in_sale($sale_id)) {
					$payment_status[] = array('admin' => '', 'status' => 'paid');
				}
				$data['status'] = 'success';
				$data['payment_status'] = json_encode($payment_status);
				$this->db->where('sale_id', $row['sale_id']);
				$this->db->update('sale', $data);
				$tot_rewards += $row['rewards'];
			}
			$rewardsts = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
			$rewardsts = $rewardsts['0'];
			if ($rewardsts['rewards_using'] == '1') {
				$this->wallet_model->reduce_reward_balanceapp($rewardsts['reward_using_amt'], $rewardsts['buyer']);
				foreach ($saleDet as $saldt) {
					$data_re['rewards_using'] = '2';
					$this->db->where('sale_id', $saldt['sale_id']);
					$this->db->update('sale', $data_re);
				}
			}
			$this->wallet_model->add_reward_balanceapp($tot_rewards, $rewardsts['buyer']);
			//$this->crud_model->email_invoice($merchant_order_id);
			$this->crud_model->email_invoice($total_invoice_id);
			$this->session->set_userdata('couponer', '');
			redirect(base_url() . 'index.php/webservice/close', 'refresh');
			//$order_details1 = $this->db->get_where('sale',array('order_id'=>$merchant_order_id))->result_array();
			//				 foreach($order_details1 as $rowor) 
			//				 {
			//					$product_details1 = json_decode($rowor['product_details'], true);
			//					$total = 0;
			//					foreach($product_details1 as $p)
			//					{
			//						$pDte[]=$p;
			//					}
			//					$product_details=$pDte;
			//					$gst_amount += $p['gst_amount'];
			//					$shipping += $p['shipping']*$p['qty'];
			//					$subtotal += $p['subtotal'];
			//					$total += $shipping+$subtotal;
			//					$new_sub += $p['subtotal'];
			//					$tot_qty +=$p['qty'];
			//				 }
			//				 $grand_total=$total;
			//				 $shipping_address=json_decode($order_details1[0]['shipping_address'],true);						 
			//				 $payment_type=$order_details1[0]['payment_type'];
			//				 $payment_status=json_decode($order_details1[0]['payment_status'],1);
			//				 $delivery_status=json_decode($order_details1[0]['delivery_status'],1);
			//				 $results['status'] = 'SUCCESS';
			//				 $results['Message'] = 'Order Completed-'.$merchant_order_id;
			//				 $results['Response'] = array("order_id"=>$merchant_order_id,"product_details"=>$product_details,"shipping_cost"=>$shipping,"total_amount"=>$grand_total,"shipping_address"=>$shipping_address,"payment_status"=>$payment_status,"delivery_status"=>$delivery_status,"payment_type"=>$payment_type,"create_date"=>date('Y-m-d h:i:s'));
			//				echo json_encode($results,true);
			//				exit;


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
				'Authorization: Basic cnpwX2xpdmVfbWMwZmV2Y3dUVTJCbXM6d1ZZdUVHMUpaV29SZ1JrTlJIa1Jnd2dv',
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}
	function ipay88_checkout_page($para1 = "")
	{
		//$this->db->group_by('order_id');

		$saleDet = $this->db->get_where('sale', array('order_id' => $para1))->result_array();
		foreach ($saleDet as $saleinfo) {
			$grand_total = $saleinfo['order_amount'];
			unset($_SESSION['p']['aut_id']);
			$_SESSION['p']['aut_id'] = $aut_id = $para1;

			unset($_SESSION['p']['return_page']);
			$_SESSION['p']['return_page'] = $return_page  = base_url() . 'index.php/Webservice/ipayresponse_app';
			unset($_SESSION['p']['req_page']);
			$_SESSION['p']['backend_page'] = $backend_page  = base_url() . 'index.php/Webservice/ipaybackend_app';

			unset($_SESSION['p']['amount']);
			$_SESSION['p']['amount'] = $_SESSION['p']['amount'] =  $grand_total;
			//exit;
			$sh = json_decode($saleinfo['shipping_address'], 1);
			unset($_SESSION['p']['client_email']);
			$_SESSION['p']['client_email'] = $client_email = $sh['email'];

			unset($_SESSION['p']['client_name']);
			$_SESSION['p']['client_name'] = $client_name = $sh['firstname'];

			unset($_SESSION['p']['client_phone']);
			$_SESSION['p']['client_phone'] = $client_phone = $sh['phone'];
		}

		require_once(APPPATH . 'libraries/ipay88/final_app.php');
		// 	exit;
		$merchantId = $this->db->get_where('business_settings', array('type' => 'cca_merchant_id'))->row()->value;
		//$posturl= base_url().'index.php/webservice/ccav_requesthandler/'; 
		//	$redirect_url= base_url().'index.php/webservice/ccav_payment_success/'; 
		//$cancel_url= base_url().'index.php/webservice/ccav_payment_cancel/'; 

	}
	function ipayresponse_app()
	{
		// echo '<pre>';
		// print_r($this->input->post());
		// print_r($_POST);
		// echo $this->input->post('Status');
		// exit;

		//echo 1; exit;
		if ($this->input->post('Status') == '1') {
			$payment_id = $this->input->post('PaymentId');
			$TransId = $this->input->post('TransId');
			$merchant_order_id = $this->input->post('RefNo');

			$data['payment_details'] = $response_array = json_encode($this->input->post(), 1);
			//  echo "<pre>";print_r($response_array);exit;
			//Check success response


			$carted  = $this->cart->contents();
			$total_invoice_id = $this->db->get_where('sale', array('order_id' => $merchant_order_id))->row()->total_invoice_id;
			$saleDet = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
			// echo $this->db->last_query(); exit;
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
				// print_r($saldt); exit;


				$payment_status[] = array('admin' => '', 'status' => 'paid');

				$data['status'] = 'admin_pending';
				$data['payment_status'] = json_encode($payment_status);
				$this->db->where('sale_id', $saldt['sale_id']);
				$this->db->update('sale', $data);
				//echo $this->db->last_query(); exit;

				$tot_rewards += $saldt['rewards'];
				//echo $this->db->last_query();
			}

			if ($saldt['buyer'] != 'guest') {
				$rewardsts = $this->db->get_where('sale', array('total_invoice_id' => $total_invoice_id))->result_array();
				$rewardsts = $rewardsts['0'];

				$this->wallet_model->add_reward_balance($tot_rewards, $saldt['buyer']);

				if ($rewardsts['rewards_using'] == '1') {
					$this->wallet_model->reduce_reward_balance($rewardsts['reward_using_amt'], $saldt['buyer']);
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
			redirect(base_url() . 'index.php/webservice/close', 'refresh');
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
				$value = array("status" => "SUCCESS", "Message" => "Payment Failed", "Response" => $data['status']);
				exit(json_encode($value));
			}
		}
	}
	function razerxheckout($para1 = "", $para2 = "")
	{
		$order_details1 = $this->db->get_where('razerpay_log', array('order_id' => $para1))->result_array();
		$order_details1 = $order_details1[0];
		$details = json_decode($order_details1['request'], 1);
		//echo "<pre>";print_r($details);exit;
		$page_data['grand_total'] = round($details['grand_total']);
		$page_data['order_id'] = $order_details1['order_id'];
		$page_data['userdet'] = $details['userdet'];
		$page_data['itemInfo'] = $details['itemInfo'];
		$page_data['address'] = $details['address'];
		$page_data['return_url'] = $details['return_url'];
		$page_data['surl'] = $details['surl'];
		$page_data['furl'] = $details['furl'];
		$page_data['currency_code'] = $details['currency_code'];
		$this->load->view('front/shopping_cart/checkoutrazorpay', $page_data);
	}
	function payucheckout($para1 = "", $para2 = "")
	{
		$order_details1 = $this->db->get_where('payu_log', array('order_id' => $para1))->result_array();
		$order_details1 = $order_details1[0];
		$details = json_decode($order_details1['request'], 1);
		//echo "<pre>";print_r($details);exit;
		$page_data['grand_total'] = round($details['grand_total']);
		//$page_data['grand_total'] = '1'; 
		$page_data['order_id'] = $order_details1['order_id'];
		$page_data['userdet'] = $details['userdet'];
		$page_data['itemInfo'] = $details['itemInfo'];
		$address = json_decode($details['address'], 1);
		//echo $address['firstname']; exit;

		$pum_merchant_key = $this->crud_model->get_settings_value('business_settings', 'pum_merchant_key', 'value');
		$pum_merchant_salt = $this->crud_model->get_settings_value('business_settings', 'pum_merchant_salt', 'value');

		$user_id = $this->session->userdata('user_id');
		/****TRANSFERRING USER TO PAYPAL TERMINAL****/
		$this->pum->add_field('key', $pum_merchant_key);
		$this->pum->add_field('txnid', substr(hash('sha256', mt_rand() . microtime()), 0, 20));
		$this->pum->add_field('amount', $page_data['grand_total']);


		$this->pum->add_field('firstname',  $address['firstname']);


		$this->pum->add_field('email', $address['email']);


		$this->pum->add_field('phone', $address['phone']);
		$this->pum->add_field('productinfo', 'pepyourcar product');
		// $this->pum->add_field('service_provider', 'payu_paisa');
		$this->pum->add_field('udf1', $page_data['order_id']);

		$this->pum->add_field('surl', base_url() . 'webservice/pum_success');
		$this->pum->add_field('furl', base_url() . 'webservice/pum_failure');
		//print_r($this->pum->add_field); exit;

		// submit the fields to pum
		$this->pum->submit_pum_post();
		//$this->load->view('front/shopping_cart/checkoutrazorpay', $page_data); 
	}
	function returnproducts_add()
	{
		$msg = '';
		$datas = json_decode($this->input->raw_input_stream, 1);
		$method = 'new';
		if ($method == 'new') {
			$sale_id = $datas['sale_id'];
			$product_id = $datas['product_id'];
			$data['return_status'] = 1;
			$data['return_reason'] = $datas['return_reason'];
			$data['return_remarks'] = $datas['return_remarks'];
			$data['return_action'] = $datas['return_action'];
			$data['order_trackment'] = 1;
			$this->db->where('sale_id', $sale_id);
			$this->db->update('sale', $data);
			$results['status'] = 'SUCCESS';
			$results['Message'] = "Return successfully";
			echo json_encode($results, true);
			exit;
		} else {
			$data['return_option']      = $datas['return_option'];
			$data['user_id']            = $datas['user_id'];
			$user_infor = $this->db->get_where('user', array('user_id' => $data['user_id']))->result_array();
			$user_infor = $user_infor[0];
			$data['name']               = $user_infor['username'];
			$data['email']              = $user_infor['email'];
			$data['address1'] 	        = $user_infor['address1'];
			$data['address2'] 	        = $user_infor['address2'];
			$data['zip']                = $user_infor['zip'];
			$data['pid']                = $datas['pid'];
			$prod_infor = $this->db->get_where('product', array('product_id' => $data['pid']))->result_array();
			//echo '<pre>'; print_r($prod_infor); exit;	
			$prod_infor = $prod_infor[0];

			$data['p_name'] 		    = $prod_infor['title'];
			$data['price'] 		        = $prod_infor['sale_price'];
			$more_inof = json_decode($prod_infor['added_by'], true);
			$data['added_by_mode'] 		= $more_inof['type'];
			$data['added_by_id'] 		= $more_inof['id'];
			$data['order_id']           = $datas['order_id'];
			$sale_infor = $this->db->get_where('sale', array('sale_code' => $data['order_id']))->result_array();
			$sale_infor = $sale_infor[0];
			//echo '<pre>'; print_r($sale_infor); exit;
			//echo ''; exit;
			$data['shipping']           = $sale_infor['shipping'];
			$data['payment_method']     = $sale_infor['payment_type'];
			$data['ordered_date'] 		= $sale_infor['created_datetime'];
			$data['qty'] 		        = $datas['qty'];
			$data['return_qty']         = $datas['return_qty'];
			$data['damage_product_description'] = $datas['damage_product_description'];
			$data['other_option']       = $datas['other_option'];
			$data['current_qty'] 		= $datas['qty'] - $datas['return_qty'];
			$data['return_status'] 		= 'Pending';
			$data['return_reason '] 	= $datas['return_reason'];
			$data['message']            = $datas['damage_product_description'];
			//echo '<pre>'; print_r($data);
			$account_data = $this->db->get_where('sale', array('sale_code' => $data['order_id']))->num_rows();
			if ($account_data == 1) {
				$this->db->insert('gr_return_order', $data);
				//echo $this->db->last_query(); exit;
				//exit;
				$insertIDSS = $this->db->insert_id();
				$results['status'] = 'SUCCESS';
				$results['Message'] = "Return successfully";
				echo json_encode($results, true);
				exit;
			} else {
				$results['status'] = 'FAILED';
				$results['Message'] = "Order ID Does Not Exsists";
				echo json_encode($results, true);
				exit;
			}
		}
	}
	function cancelproducts_add()
	{
		$msg = '';
		$datas = json_decode($this->input->raw_input_stream, 1);
		//echo '<pre>'; print_r($datas); exit;
		$method = 'new';
		if ($method == 'new') {
			//$data['user_id']            = $datas['user_id'];
			//$user_infor=$this->db->get_where('user',array('user_id' => $data['user_id']))->result_array();
			//$user_infor=$user_infor[0];
			$sale_id = $datas['sale_id'];
			$product_id = $datas['product_id'];
			$data['cancel_status'] = 2;
			$data['cancel_reason'] =  $datas['cancel_reason'];
			$data['cancel_remarks'] = $datas['message'];
			$this->db->where('sale_id', $sale_id);
			$this->db->update('sale', $data);
			$results['status'] = 'SUCCESS';
			$results['Message'] = "Cancel successfully";
			echo json_encode($results, true);
			exit;
		} else {
			$data['user_id']            = $datas['user_id'];
			$user_infor = $this->db->get_where('user', array('user_id' => $data['user_id']))->result_array();
			$user_infor = $user_infor[0];
			//echo '<pre>'; print_r($user_infor); exit;
			$data['name']               = $user_infor['username'];
			$data['email']              = $user_infor['email'];
			$data['address1'] 	        = $user_infor['address1'];
			$data['address2'] 	        = $user_infor['address2'];
			$data['zip']                = $user_infor['zip'];
			$data['order_id']           = $datas['order_id'];
			$sale_infor = $this->db->get_where('sale', array('sale_code' => $data['order_id']))->result_array();
			$sale_infor = $sale_infor[0];
			$data['product_details']           = $sale_infor['product_details'];
			$data['payment_status']           = $sale_infor['payment_status'];
			$data['sale_datetime']           = $sale_infor['sale_datetime'];
			$data['price']           = $sale_infor['grand_total'];
			$data['shipping']           = $sale_infor['shipping'];
			$data['payment_method']     = $sale_infor['payment_type'];
			$data['ordered_date'] 		= $sale_infor['created_datetime'];
			$data['message'] 		        = $datas['message'];
			$data['cancel_reason'] 		        = $datas['cancel_reason'];
			$data['cancel_status'] 		        = 'pending';
			$account_data = $this->db->get_where('sale', array('sale_code' => $data['order_id']))->num_rows();
			if ($account_data == 1) {
				$this->db->insert('gr_cancel_order', $data);
				$insertIDSS = $this->db->insert_id();
				$results['status'] = 'SUCCESS';
				$results['Message'] = "Cancel successfully";
				echo json_encode($results, true);
				exit;
			} else {
				$results['status'] = 'FAILED';
				$results['Message'] = "Order ID Does Not Exsists";
				echo json_encode($results, true);
				exit;
			}
		}
	}
	function replaceproducts_add()
	{
		$msg = '';
		$datas = json_decode($this->input->raw_input_stream, 1);
		$method = 'new';
		if ($method == 'new') {
			$sale_id = $datas['sale_id'];
			$product_id = $datas['product_id'];
			$data['return_status'] = 1;
			$data['return_reason'] = $datas['return_reason'];
			$data['return_remarks'] = $datas['damage_product_description'];
			$data['return_action'] = $datas['return_action'];
			$data['order_trackment'] = 1;
			$this->db->where('sale_id', $sale_id);
			$this->db->update('sale', $data);
			$results['status'] = 'SUCCESS';
			$results['Message'] = "Replace successfully";
			echo json_encode($results, true);
			exit;
		} else {

			$data['return_option']      = $datas['return_option'];
			$data['user_id']            = $datas['user_id'];
			$user_infor = $this->db->get_where('user', array('user_id' => $data['user_id']))->result_array();
			$user_infor = $user_infor[0];
			$data['name']               = $user_infor['username'];
			$data['email']              = $user_infor['email'];
			$data['address1'] 	        = $user_infor['address1'];
			$data['address2'] 	        = $user_infor['address2'];
			$data['zip']                = $user_infor['zip'];
			$data['pid']                = $datas['pid'];
			$prod_infor = $this->db->get_where('product', array('product_id' => $data['pid']))->result_array();
			$prod_infor = $prod_infor[0];
			$data['p_name'] 		    = $prod_infor['title'];
			$data['price'] 		        = $prod_infor['sale_price'];
			$more_inof = json_decode($prod_infor['added_by'], true);
			$data['added_by_mode'] 		= $more_inof['type'];
			$data['added_by_id'] 		= $more_inof['id'];
			$data['order_id']           = $datas['order_id'];
			$sale_infor = $this->db->get_where('sale', array('sale_code' => $data['order_id']))->result_array();
			$sale_infor = $sale_infor[0];
			$data['shipping']           = $sale_infor['shipping'];
			$data['payment_method']     = $sale_infor['payment_type'];
			$data['ordered_date'] 		= $sale_infor['created_datetime'];
			$data['qty'] 		        = $datas['qty'];
			$data['return_qty']         = $datas['return_qty'];
			$data['damage_product_description'] = $datas['damage_product_description'];
			$data['other_option']       = $datas['other_option'];
			$data['current_qty'] 		= $datas['qty'] - $datas['return_qty'];
			$data['return_status'] 		= 'Pending';
			$data['return_reason '] 	= $datas['return_reason'];
			$data['message']            = $datas['damage_product_description'];
			$data['return_status']      = 'pending';
			$account_data = $this->db->get_where('sale', array('sale_code' => $data['order_id']))->num_rows();
			if ($account_data == 1) {
				$this->db->insert('gr_replace_order', $data);
				$insertIDSS = $this->db->insert_id();
				$results['status'] = 'SUCCESS';
				$results['Message'] = "Replace successfully";
				echo json_encode($results, true);
				exit;
			} else {
				$results['status'] = 'FAILED';
				$results['Message'] = "Order ID Does Not Exsists";
				echo json_encode($results, true);
				exit;
			}
		}
	}

	function addmoney()
	{
		$datas = json_decode($this->input->raw_input_stream, 1);
		$grand_total = $datas['amount'];
		$amount_in_usd  = $grand_total;
		$method = $datas['method'];
		if ($method == 'razorpay') {
			$data['user']                   = $datas['user_id'];
			$data['method']                 = $datas['method'];
			$data['amount']                 = $grand_total;
			$data['status']                 = 'due';
			$data['payment_details']        = '[]';
			$data['timestamp']              = time();
			$data['unicid']                  = 'BE' . substr(time(), 4) . rand(1, 10) . rand(1, 99);
			$this->db->insert('wallet_load', $data);
			$id = $this->db->insert_id();
			$page_data['grand_total'] = $data['amount'];
			$datawe['order_id'] = $page_data['order_id'] = $data['unicid'];
			$page_data['return_url'] = base_url() . 'index.php/webservice/callback_wallet';
			$page_data['surl'] = base_url() . 'index.php/webservice/walletsuccess';
			$page_data['furl'] = base_url() . 'index.php/webservice/walletfailed';
			$page_data['currency_code'] = 'INR';
			$datawe['request'] = json_encode($page_data);
			$this->db->insert('razerpay_log', $datawe);
			$results['status'] = 'SUCCESS';
			$results['Message'] = 'Wallet created-' . $page_data['order_id'];
			$url = base_url() . 'index.php/webservice/razerwallet/' . $page_data['order_id'];
			$results['Response'] = array("order_id" => $page_data['order_id'], "url" => $url);
			echo json_encode($results, true);
			exit;
		}
	}
	function razerwallet($para1 = "", $para2 = "")
	{
		$order_details1 = $this->db->get_where('razerpay_log', array('order_id' => $para1))->result_array();
		$order_details1 = $order_details1[0];
		$details = json_decode($order_details1['request'], 1);
		//echo "<pre>";print_r($details);exit;
		$page_data['grand_total'] = $details['grand_total'];
		$page_data['order_id'] = $order_details1['order_id'];
		$page_data['return_url'] = $details['return_url'];
		$page_data['surl'] = $details['surl'];
		$page_data['furl'] = $details['furl'];
		$page_data['currency_code'] = $details['currency_code'];
		$this->load->view('front/user/checkoutwalletapi', $page_data);
	}
	function callback_wallet()
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
				$datas['payment_details']   = json_encode($response_array);
				$page_data['wallet'] = $this->db->get_where('wallet_load', array('unicid' => $merchant_order_id))->result_array();
				$user = $page_data['wallet'][0]['user'];
				$amount = $page_data['wallet'][0]['amount'];
				$datas['status']   = 'paid';

				//*---------update walletaddStatus*--------------------------*
				$this->db->where('unicid', $merchant_order_id);
				$this->db->update('wallet_load', $datas);
				//*----------------------------------*

				$page_data['old_wallet'] = $this->db->get_where('user', array('user_id' => $user))->result_array();
				$old_amt = $page_data['old_wallet'][0]['wallet'];
				$new_amt = $old_amt + $amount;

				//*---------userWallet Update*--------------------------*
				$data['wallet'] = $new_amt;
				$this->db->where('user_id', $user);
				$this->db->update('user', $data);
				//*-------------------------------------------------------*
				//*---------userLog Create*--------------------------*
				$data2['uid']                   = $user;
				$data2['description']            = 'Rs. ' . $amount . ' Credicted to your wallet';
				$this->db->insert('user_log', $data2);
				//*--------------------------------------------------------*
				$from = 'myrunciit';
				$to = $page_data['old_wallet'][0]['email'] = '';
				$subject = 'Acknowledgement for wallet transfer';
				$message = "<html><head><meta http-equiv=Content-Type content=text/html; charset=utf-8/><title>Oyabuy.net</title>
        </head><body><table width=500 cellpadding=0 cellspacing=0 border=0 bgcolor=#F49E23 style=border:solid 10px #A5DCFF;><tr bgcolor=#FFFFFF height=25><td><table width=500 cellpadding=0 cellspacing=0 border=0 bgcolor=#F49E23 style=border:solid 10px #a5dcff;><tr bgcolor=#FFFFFF height=30><td height=27 valign=top style=font-family:Arial; font-size:12px; line-height:18px; text-decoration:none; color:#000000; padding-left:20px;><b>Wallet Transfer Acknowledgement</b></td>
        </tr><tr bgcolor=#FFFFFF height=35><td height=24 style=padding-left:20px; font-family:arial; font-size:11px; line-height:18px; text-decoration:none; color:#000000;> INR '" . $amount . "' Added your wallet</td></tr><tr bgcolor=#FFFFFF height=35><td height=23 style=padding-left:20px; font-family:arial; font-size:11px; line-height:18px; text-decoration:none; color:#000000;>Thanks for using https://myrunciit.my/</td></tr></table></td></tr></table><body/><html/>";

				$headers = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
				$headers .= "From: MyRunCiit <>\r\n";
				$email_response = mail($to, $subject, $message, $headers);
				redirect(base_url() . 'index.php/webservice/close', 'refresh');
			} else {
				$page_data['unicid'] = $merchant_order_id;
				redirect(base_url() . 'index.php/webservice/close', 'refresh');
				//redirect(base_url() . 'index.php/home/profile/part/wallet/', 'refresh');
			}
		} else {
			echo 'An error occured. Contact site administrator, please!';
		}
	}
	function walletresult($para1 = "", $para2 = "")
	{
		$walletDet = $this->db->get_where('wallet_load', array('unicid' => $para1))->result_array();
		$walletDet = $walletDet[0];
		//echo '<pre>'; print_r($walletDet); echo '</pre>';
		$userdet = $this->db->get_where('user', array('user_id' => $walletDet['user']))->result_array();
		$userdet = $userdet[0];
		$res['status'] = $walletDet['status'];
		$res['unicid'] = $walletDet['unicid'];
		$res['date'] = date("Y-m-d h:i:s A", $walletDet['timestamp']);
		$res['paymentType'] = ucfirst($walletDet['method']);
		$res['username'] = $userdet['username'];
		$res['email'] = $userdet['email'];
		$res['amount'] = $walletDet['amount'];
		$res['userBalance'] = $userdet['wallet'];
		$results['status'] = 'SUCCESS';
		$results['Message'] = 'Wallet Added-' . $res['unicid'];
		$results['Response'] = $res;
		echo json_encode($results, true);
		exit;
	}
	function order_shipcharges_bk()
	{
		$old = 'NEW';
		if ($old == '$old') {
			$datas = json_decode($this->input->raw_input_stream, 1);
			$method = 'shipping';
			if ($method == 'new') {
				if (isset($datas['userID']) && $datas['userID'] != '' && $datas['userID'] != 0 && $datas['mode'] == 'user' && isset($datas['cart']) && $datas['cart'] != '' && is_array($datas['cart'])) {

					$vat_per = '';
					$userID = $datas['userID'];
					$cartDetails = $datas['cart'];
					$userDetails = $this->db->get_where('user', array('user_id' => $userID))->result_array();
					$userDetails = $userDetails[0];
					$balance = $userDetails['balance'];
					$data['buyer'] = $userID;

					foreach ($datas['cart'] as $cart) {
						$no_qty = $cart['qty'];
						$i = 1;
						$product_id = $cart['product_id'];
						$productInfo[] = $this->db->get_where('product', array('product_id' => $product_id))->result_array();
						$productInfo = $productInfo[0][0];
						$pro['id'] = $cart['product_id'];
						$pro['qty'] = $cart['qty'];
						$productColor = $cartOption['color'] = $productInfo['color'];
						$productName = $cartOption['title'] = $productInfo['title'];
						$cartOption['value'] = "";
						$pro['option'] = $cartOption;
						$pro['price'] = $productInfo['sale_price'];
						$pro['name'] = $productInfo['title'];
						if ($this->crud_model->get_type_name_by_id('business_settings', '3', 'value') == 'product_wise') {
							$data['shipping'] = $pro['shipping'] =  $productInfo['shipping_cost'];
						} else if ($this->crud_model->get_type_name_by_id('business_settings', '3', 'value') == 'fixed') {
							$data['shipping'] = $pro['shipping'] = $this->crud_model->get_type_name_by_id('business_settings', '2', 'value');
						} else {
							//===================================shipcharge calculation start======================================================
							//$productdet = $this->db->get_where('product',array('product_id'=>$cart['product_id']))->result_array();
							//$productdet=$productdet[0];
							$added_by = json_decode($productInfo['added_by'], true);
							if ($added_by['type'] == 'admin') {
							} else if ($added_by['type'] == 'vendor') {
								$vendorDEtails = $this->db->get_where('vendor', array('vendor_id' => $added_by['id']))->result_array();
								$vendorDEtails = $vendorDEtails[0];
								$fromstate = $vendorDEtails['store_district'];
								$user_id = $userID;
								$userdet = $this->db->get_where('shipping_address', array('unique_id' => $datas['address_unicid']))->result_array();
								$userdet = $userdet[0];
								$tostate = $userdet['state'];
								$shipcharge = $this->db->get_where('shipcharge', array('status' => 1))->result_array();
								foreach ($shipcharge as $ship) {
									$fromstate1 = $this->db->get_where('state', array('state_id' => $ship['fromstate']))->row()->name;
									$tostate1 = $this->db->get_where('state', array('state_id' => $ship['tostate']))->row()->name;
									if (($fromstate == $fromstate1) && ($tostate == $tostate1)) {
										$weight = 500;
										if ($productInfo['weight'] = '') {
											$productwt = 500;
										} else {
											$productwt = $productInfo['weight'];
										}
										if ($weight < $y) {
											$data['shipping'] = $pro['shipping'] = $ship['maxcharge'];
										} else {
											$data['shipping'] = $pro['shipping'] = $ship['mincharge'];
										}
									}
								}
							}
						}


						if ($productInfo['discount'] != '0.00') {
							if ($productInfo['discount_type'] == 'percent') {
								$val_dis = $productInfo['sale_price'] * ($productInfo['discount'] / 100);
								$productPrice = $cartV['price'] = $productInfo['sale_price'] - $val_dis;
							} else
								$productPrice = $cartV['price'] = $productInfo['sale_price'] - $productInfo['discount'];
						} else {
							$productPrice = $cartV['price'] = $productInfo['sale_price'];
						}

						$address_unicid = $datas['address_unicid'];
						if ($address_unicid != "") {

							$shipping_address = $this->db->get_where('shipping_address', array('unique_id' => $address_unicid))->result_array();
							//	echo $this->db->last_query();
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
							$data['shipping_address']  = $sh;
						} else {
							$sh['firstname'] = $datas['firstname'];
							$sh['lastname'] = $datas['lastname'];
							$sh['address1'] = $datas['address1'];
							$sh['address2'] = $datas['address2'];
							$sh['zip'] = $datas['zip'];
							$sh['phone'] = $datas['email'];
							$sh['email'] = $datas['phone'];
							$country = $sh['country'] = $datas['country'] = 'India';
							$sh['state'] =  $datas['state'] = 'TamilNadu';
							$sh['cities'] = $datas['cities'] = 'Trichy';
							$sh['short_country'] = $datas['cou_shrt1'] = 'IND';
							$data['shipping_address'] = $sh;
						}
						//	$pro['gst_amount'] =$gst_amount = $this->crud_model->get_product_gst($cart['product_id'], 'inter_country')*$cart['qty'];
						//  	$pro['applied_gst']= 'inter_country';
						$salePrice = $productPrice * $cart['qty'];

						if ($productInfo['tax'] != '' && $productInfo['tax'] != 0) {
							if ($productInfo['tax_type'] == 'percent') {
								$tax = $salePrice * ($productInfo['tax'] / 100);
							} else
								$tax = $productInfo['tax'];
						} else
							$tax = 0.00;
						$cartV['tax'] = $tax;
						$pro['image'] = $this->crud_model->file_view('product', $cart['product_id'], '', '', 'thumb', 'src', 'multi', 'one');
						$pro['coupon'] = $cart['coupon'];
						$rowid = $pro['rowid'] = rand(10000, 100000) . rand(10000, 100000);
						$pro['subtotal'] = $salePrice + $tax;
						$pro1 = array($rowid => $pro);
						$data['product_details'] = $pro1;
						$data['payment_type'] = $datas['payment_type'];
						$data['order_type'] = 'shopping';
						$data['grand_total'] = $pro['subtotal'] + $data['shipping'];
						$data['group_deal'] = 0;
					}



					$results['status'] = 'SUCCESS';
					$results['Message'] = 'Order details';
					$results['Response'] = array("product_details" => $pro, $cart => $pro, "shipping_cost" => $data['shipping'], "total_amount" => $data['grand_total'], "shipping_address" => $data['shipping_address'], "ship_id" => $datas['address_unicid'], "payment_type" => $data['payment_type'], "create_date" => date('Y-m-d h:i:s'));
					echo json_encode($results, true);
					exit;
				} else {
					$results['status'] = 'FAILED';
					$results['Message'] = 'Invalid Request';
					$results['Response'] = 'Invalid Request';
					echo json_encode($results, true);
					exit;
				}
			}
		} else {
			$shipsetprice = '500';
			$shipping = $this->crud_model->get_type_name_by_id('business_settings', '2', 'value');
			$results['status'] = 'SUCCESS';
			$results['Message'] = 'Shipping details';
			$results['Response'] = array("baseprice" => $shipsetprice, "shipping_cost" => $shipping);
			echo json_encode($results, true);
			exit;
		}
	}
	function order_shipcharges()
	{
		if ($this->crud_model->get_type_name_by_id('business_settings', '3', 'value') == 'fixed') {

			$shipsetprice = '500';
			$shipping = $this->crud_model->get_type_name_by_id('business_settings', '2', 'value');
			$shipping_tax = 18 / 100 * $this->crud_model->get_type_name_by_id('business_settings', '2', 'value');
			$results['status'] = 'SUCCESS';
			$results['Message'] = 'Shipping details';
			$results['Response'] = array("baseprice" => $shipsetprice, "shipping_cost" => $shipping, "shipping_tax" => $shipping_tax);
			echo json_encode($results, true);
			exit;
		}
	}
	function dhl($para1 = '', $para2 = '')
	{
		$response = $this->crud_model->tracking($para1);
		$results['status'] = 'SUCCESS';
		$results['Message'] = 'Tracking Success';
		$results['Response'] = $response;
		echo json_encode($results, true);
		exit;
	}
	function slides()
	{
		//echo "aa"; exit;
		//$slides = array();
		$this->db->where('added_by', json_encode(array('type' => 'admin', 'id' => '1')));
		$this->db->where('status', 'ok');
		//$slides= $this->db->get_where('slides',array('status'=>'ok'))->result_array();
		$slides = $this->db->get('appslides')->result_array();
		foreach ($slides as $row1) {

			$sub['appslides_id'] = $row1['appslides_id'];


			$sub['appslides'] = 'appslides_' . $row1['appslides_id'] . '.jpg';
			$sub['banner'] = $row1['banner'];

			//	$sub['digital'] =$row1['digital'];

			$row[] = $sub;
		}
		$response['slides'] = $row;
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function after_banner()
	{
		//echo "aa"; exit;
		//$slides = array();
		//$this->db->where('added_by',json_encode(array('type'=>'admin','id'=>'1')));
		$this->db->where('place', 'after_slider');
		$this->db->where('status!=', '0');
		//$slides= $this->db->get_where('slides',array('status'=>'ok'))->result_array();
		$slides = $this->db->get('banner')->result_array();
		foreach ($slides as $row1) {

			$sub['banner_id'] = $row1['banner_id'];


			$sub['appslides'] = base_url() . 'uploads/banner_image/banner_' . $row1['banner_id'] . '' . $row1['image_ext'];
			$sub['cat_link'] = $row1['cat_link'];
			//https://myrunciit.my/uploads/banner_image/banner_4.png

			//	$sub['digital'] =$row1['digital'];

			$row[] = $sub;
		}
		$response['banner_slides'] = $row;
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function coupon_show($para1 = "")
	{
		$cou = $this->db->get_where('coupon', array('status' => 'ok'))->result_array();

		$cou = $cou[0];
		$row['title'] = $cou['title'];
		$row['coupon_code'] = $cou['code'];

		$response['coupon'] = $row;
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}



	function priceget($para1 = '')
	{
		// print_r($_POST);
		$datas = json_decode($this->input->raw_input_stream, 1);
		$data['product_id'] = $pid = $datas['product_id'];
		$options = $this->db->get_where('product', array('product_id' => $data['product_id']))->row()->options;
		$optionCount = count(json_decode($options, true));
		//$optionCount = $this->input->post('optionCount');
		$data['product_color'] = "rgba(204,204,204,1)";

		$qty = $datas['qty'];
		$result = array("color" => $data['product_color']);

		for ($i = 0; $i <= $optionCount; $i++) {
			// echo "a1".$this->input->post('choice_'.$i);
			if ($datas['choice_' . $i]) {

				$data1 = str_replace('+', ' ', $datas['choice_' . $i]);
				$data2 = explode('^', $data1);
				$data3 = array(str_replace(' ', '_', $data2[0]) => str_replace('+', '_', $data2[1]));
				$result = $result + $data3;
			}
		}
		$data['other_option'] = json_encode($result, true);

		//  echo '<pre>'; print_r($data);
		$gr_multiple_option1 =  $this->db->get_where('product', array('product_id' => $data['product_id'], 'status' => "ok"))->result_array();

		$gr_multiple_option =  $this->db->get_where('multiple_option', array('product_color' => $data['product_color'], 'product_id' => $data['product_id'], 'other_option' => $data['other_option'], 'status' => 1))->result_array();

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
			$row['multi_var_price'] = number_format((float) $price, 2, '.', '');
			$row['current_qty'] = $gr_multiple_option1[0]['current_stock'];
			$row['multi_var_price_qty'] = $price . '^' . $gr_multiple_option[0]['quantitty'];
		} else {


			$price = $gr_multiple_option1[0]['sale_price'];
			$row['multi_var_price'] = number_format((float) $price, 2, '.', '');
			$row['current_qty'] = $gr_multiple_option1[0]['current_stock'];
			$row['multi_var_price_qty'] = $price . '^' . $gr_multiple_option1[0]['current_stock'];
		}

		$response['option_price'] = $row;
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function getPickupDetailAsVendor($para1 = '')
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
			$response['option_price'] = json_encode($tempData);
		} catch (Exception $e) {
			echo '' . $e->getMessage();
		}
		$value = array("status" => "SUCCESS", "message" => "SUCCESS", "response" => $response);

		exit(json_encode($value));
	}
	function getPickupLocation(){
		try{
		$vendorid = $this->session->userdata('vendorid');
		if($vendorid=="")
		{
			$vendorid="2";
		}
		$store_address =  $this->db->get_where('vendor', array('status' => 'approved', 'pickup' => 'yes','vendor_id'=>$vendorid))->result_array();
		
		if(count($store_address)>0)
		{
			$value = array("status" => "SUCCESS", "message" => "SUCCESS", "response" => $store_address);}
		else{
			$value = array("status" => "FAILED", "message" => "No data found", "response" => null);
		}
	}
	catch(Exception $e){
		$value = array("status" => "FAILED", "message" => $e->getMessage(), "response" => null);
	}
	exit(json_encode($value));
	}
	 function getAllPreOrders()
    {
	try{	
        $currentDate = date_create($this->input->post('currentDate'));
        $allPreOrders = $this->db->get_where('pre_order', ['status' => 'ok'])->result_array();
        $finalPreOrders = [];
        
        foreach ($allPreOrders as $order) {
            $getDate = date_create($order['end_date']);
            $diff = date_diff($currentDate, $getDate);
            if ($diff->format('%R%a') >= 0) {
                # need to show this pre order
                $finalPreOrders[] = $order;
            }
        }
        if ($finalPreOrders[0]) {
			echo json_encode(["status" =>"SUCCESS", "message"=>"SUCCESS", "response"=> $finalPreOrders[0]]);
        } else {
            echo json_encode(["status" =>"FAILED", "message"=>"No data found", "response"=> $finalPreOrders[0]]);
        }
	}
	catch(Exception $ex){
		echo json_encode(["status" =>"FAILED", "message"=>$ex->getMessage() , "response"=> null]);
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

	function pre_order($para1 = "")
	{
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$cur_dt = date('Y-m-d');
		// $this->db->order_by('id', 'desc');
		$this->db->where('status', 'ok');
		$pre_dts = $this->db->get('pre_order')->result_array();
		$s_dt = $pre_dts[0]['start_date'];
		$e_dt = $pre_dts[0]['end_date'];

		if ($pre_dts[0]['status'] == 'ok' && $e_dt > $cur_dt) {

			$response['pre_order'] = "yes";
		} else {
			$response['pre_order'] = "no";
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}
	function delivery_pre_order($para1 = "")
	{
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$cur_dt = date('Y-m-d');
		// $this->db->order_by('id', 'desc');
		$this->db->where('status', 'ok');
		$pre_dts = $this->db->get('pre_order')->result_array();
		$s_dt = $pre_dts[0]['start_date'];
		$e_dt = $pre_dts[0]['end_date'];

		if ($pre_dts[0]['status'] == 'ok' && $e_dt > $cur_dt) {

			$response['pre_order'] = "yes";
		} else {
			$response['pre_order'] = "no";
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}
	function pre_order_date($para1 = "")
	{
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$cur_dt = date('Y-m-d');
		// $this->db->order_by('id', 'desc');
		$this->db->where('status', 'ok');
		$pre_dts = $this->db->get('pre_order')->result_array();
		//print_r( $pre_dts);
		foreach ($pre_dts as $sc) {

			$sc1 = array('id' => $sc['id'], 'start_date' => $sc['start_date'], 'end_date' => $sc['end_date'], 'description' => $sc['description']);
			$row[] = $sc1;
		}
		$response['pre_order_date'] = $row;


		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function pk_product_available_stroewise_check($para1 = "", $para2 = "")
	{
		//echo "a"; exit;
		$this->db->where('product_id', $para2);
		$this->db->where('store_id', $para1);
		$pre_dts = $this->db->get('product')->result_array();
		//  echo $this->db->last_query();
		//   print_r($pre_dts);  
		if (!empty($pre_dts)) {

			$response['product_status'] = "Available";
		} else {
			$response['product_status'] = "store does not have this product";
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

	function delivery_product_available_stroewise_check($para1 = "", $para2 = "")
	{
		//echo "a"; exit;
		$this->db->where('product_id', $para2);
		$this->db->where('store_id', $para1);
		$pre_dts = $this->db->get('product')->result_array();
		//  echo $this->db->last_query();
		//   print_r($pre_dts);  
		if (!empty($pre_dts)) {

			$response['product_status'] = "Available";
		} else {
			$response['product_status'] = "store does not have this product";
		}
		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}


	function delivery_zipcode_store($para1 = "")
	{
		$this->db->select('*');
		$this->db->from('vendor');
		$this->db->where('status', 'approved');
		$this->db->where('delivery', 'yes');
		$this->db->like('delivery_zipcode', $para1);
		$allStores = $this->db->get()->result_array();
		// 		print_r($allStores); exit;
		if (!empty($allStores)) {
			foreach ($allStores as $sc) {

				$sc1 = array('store_id' => $sc['vendor_id'], 'name' => $sc['name'], 'city' => $sc['city'], 'city' => $sc['city'], 'state' => $sc['state'], 'country' => $sc['country'], 'zip' => $sc['zip']);
				$row[] = $sc1;
			}
			$response['delivery_stores'] = $row;
		} else {
			$response['delivery_stores'] = "Sorry no store is available";
		}


		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}
	function delivery_fee($para1 = "", $para2 = "", $para3 = "")
	{

		$free_delivery	 =  $this->db->get_where('business_settings', array('business_settings_id' => '44'))->row()->value;
		//echo $this->db->last_query();
		$delivery_fee	 =  $this->db->get_where('business_settings', array('business_settings_id' => '45'))->row()->value;



		$sc1 = array('free_delivery_charge' => $free_delivery, 'delivery_charge' => $delivery_fee);
		$row[] = $sc1;


		$response['delviery_fee'] = $row;

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}
	function delivery_check($para1 = "")
	{

		$free_delivery	 =  $this->db->get_where('business_settings', array('business_settings_id' => '44'))->row()->value;
		//echo $this->db->last_query();
		$delivery_fee	 =  $this->db->get_where('business_settings', array('business_settings_id' => '45'))->row()->value;

		if ($para1 >= $delivery_fee) {
			$response['delviery_fee'] = "Free delivery";
		} else {
			$response['delviery_fee'] = $delivery_fee;
		}

		$value = array("status" => "SUCCESS", "Message" => "SUCCESS", "Response" => $response);

		exit(json_encode($value));
	}

}

/* End of file home.php */

/* Location: ./application/controllers/home.php */