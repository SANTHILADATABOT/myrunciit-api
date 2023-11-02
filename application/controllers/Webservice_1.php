<?php
error_reporting(0);
//header('Access-Control-Allow-Origin: http://demosample.in'); 
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

		//print_r($con);

        $this->load->library('paypal');

        $this->load->library('twoCheckout_Lib');

        $this->load->library('vouguepay');

		$instamojoParams=array("api_key"=>"9171c1fe7db83b578d5a43ba9f38b832","auth_token"=>"439799c55744eeac7c56764463f67acf","endpoint"=>'https://www.instamojo.com/api/1.1/');

		//$this->load->library('instamojo',$instamojoParams);

		$cache_time	 =  $this->db->get_where('general_settings',array('type' => 'cache_time'))->row()->value;

		if(!$this->input->is_ajax_request()){

			$this->output->set_header('HTTP/1.0 200 OK');

			$this->output->set_header('HTTP/1.1 200 OK');

			$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');

			$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');

			$this->output->set_header('Cache-Control: post-check=0, pre-check=0');

			$this->output->set_header('Pragma: no-cache');

            if($this->router->fetch_method() == 'index' || 

                $this->router->fetch_method() == 'featured_item' || 

                    $this->router->fetch_method() == 'others_product' || 

						$this->router->fetch_method() == 'all_brands' || 

							$this->router->fetch_method() == 'all_category' || 

								$this->router->fetch_method() == 'all_vendor' || 

									$this->router->fetch_method() == 'blog' || 

										$this->router->fetch_method() == 'blog_view' || 

											$this->router->fetch_method() == 'vendor' || 

												$this->router->fetch_method() == 'category' ||

												$this->router->fetch_method()=='sub_category'

												){

                $this->output->cache($cache_time);

            }

		}

		$this->config->cache_query();

		$currency = $this->session->userdata('currency');

		if(!isset($currency)){

			$this->session->set_userdata('currency',$this->db->get_where('business_settings', array('type' => 'home_def_currency'))->row()->value);

		}

		setcookie('lang', $this->session->userdata('language'), time() + (86400), "/");

		setcookie('curr', $this->session->userdata('currency'), time() + (86400), "/");

		

		$UPURL = explode('/',"$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); 

		

		

		if($_SERVER['DOCUMENT_ROOT']=='D:/xampplite/htdocs' || $_SERVER['DOCUMENT_ROOT']=='D:/xampp/htdocs' || $_SERVER['DOCUMENT_ROOT']=='C:/xampplite/htdocs' || $_SERVER['DOCUMENT_ROOT']=='C:/xampp/htdocs') 

		{ 

		$UPURL = 'http://'.$UPURL[0].'/'.$UPURL[1].'/'.$UPURL[2].'/';

			 $propertyIDS =  file_get_contents($UPURL.'id.txt'); 	

		}

		else 

		{

			$UPURL = 'http://'.$UPURL[0].'/'.$UPURL[1].'/';

			$ch = curl_init();

			curl_setopt ($ch, CURLOPT_URL, $UPURL.'id.txt');

			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

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

		$home_style =  $this->db->get_where('ui_settings',array('type' => 'home_page_style'))->row()->value;

        $page_data['page_name']     = "home/home".$home_style;

        $page_data['asset_page']    = "home";

        $page_data['page_title']    = translate('home');

        $this->benchmark->mark('code_start');

        $this->load->view('front/index', $page_data);

		$this->benchmark->mark('code_end');

	}

	function getFeatured()

	{

		$featuredProducts=$this->crud_model->product_list_set('featured',30);

		foreach($featuredProducts as $fp)

		{
			$product_id=$fp['product_id'];

			$fp['banner']=$this->crud_model->file_view('product',$product_id,'','','thumb','src','multi','one');

			$featured['featuredProducts'][]=$fp;

		}

		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$featured);

		exit(json_encode($value));

	}

	function getLatestProducts()

	{

		$latest['latestProducts']=$this->crud_model->product_list_set('latest',10);

		foreach($latest['latestProducts'] as $lP)

		{

			$product_id=$lP['product_id'];

			$lP['banner']=$this->crud_model->file_view('product',$product_id,'','','thumb','src','multi','one');

			$latestProducts['latestProducts'][]=$lP;

		}

		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$latestProducts);

		exit(json_encode($value));

	}

	function getMostViewedProducts()

	{

		$mostViewed['mostViewedProducts']=$this->crud_model->product_list_set('most_viewed',10);

		foreach($mostViewed['mostViewedProducts'] as $mv)

		{

				$product_id=$mv['product_id'];

				$mv['banner']=$this->crud_model->file_view('product',$product_id,'','','thumb','src','multi','one');

				$mostViewed1['latestProducts'][]=$mv;

		}

		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$mostViewed1);

		exit(json_encode($value));

	}

	function getRecentlyViewed()

	{

		$recent=$this->crud_model->product_list_set('recently_viewed',10);

		$i=0;

		foreach($recent as $rc)

		{

				$product_id=$rc['product_id'];

				$recentProduct['recentlyViewedProducts'][$i]=$rc;

				$recentProduct['recentlyViewedProducts'][$i]['banner']=$this->crud_model->file_view('product',$product_id,'','','thumb','src','multi','one');

				$i++;

		}

		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$recentProduct);

		exit(json_encode($value));

	}

	function getDealProduct()

	{
		$deal['dealProducts']=$this->crud_model->product_list_set('deal',10);

		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$deal);

		exit(json_encode($value));

	}

    function top_bar_right(){

    	$this->load->view('front/components/top_bar_right.php');

    }

    function load_portion($page = ''){

        $page = str_replace('-', '/', $page);

        $this->load->view('front/'.$page);

    }

    function vendor_profile($para1='',$para2=''){

		if ($this->crud_model->get_settings_value('general_settings','vendor_system') !== 'ok') {

			redirect(base_url() . 'index.php/home');

		}

		if($para1=='get_slider'){

			$page_data['vendor_id']			=$para2;

			$this->db->where("status", "ok");

			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$para2)));

			$page_data['sliders']       = $this->db->get('slides')->result_array();

			$this->load->view('front/vendor/public_profile/home/slider',$page_data);

		}else{

			$status=$this->db->get_where('vendor',array('vendor_id' => $para1))->row()->status;

			if($status !== 'approved'){

				redirect(base_url(), 'refresh');

			}

			$page_data['page_title']        = $this->crud_model->get_type_name_by_id('vendor',$para1,'display_name');

			$page_data['asset_page']        = "vendor_public_home";

			$page_data['page_name']        	= "vendor/public_profile";

			$page_data['content']        	= "home";

			$this->db->where("status", "ok");

			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$para1)));

			$page_data['sliders']       = $this->db->get('slides')->result_array();

			$page_data['vendor_info']       = $this->db->get_where('vendor',array('vendor_id' => $para1))->result_array();

			$page_data['vendor_tags']       = $this->db->get_where('vendor',array('vendor_id' => $para1))->row()->keywords;

			$page_data['vendor_id']			=$para1;

			$this->load->view('front/index', $page_data);

		}

	}

	/* FUNCTION: Loads Category filter page */

    function vendor_category($vendor,$para1 = "", $para2 = "", $min = "", $max = "", $text ='')

    {

        if ($this->crud_model->get_settings_value('general_settings','vendor_system') !== 'ok') {

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

		$brand_sub = explode('-',$para2);

		$sub 	= 0;

		$brand  = 0;

		if(isset($brand_sub[0])){

			$sub = $brand_sub[0];

		}

		if(isset($brand_sub[1])){

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

	function vendor_featured($para1='',$para2=''){

		if ($this->crud_model->get_settings_value('general_settings','vendor_system') !== 'ok') {

			redirect(base_url() . 'index.php/home');

		}

		if($para1=='get_list'){

			$page_data['vendor_id']			=$para2;

			$this->load->view('front/vendor/public_profile/featured/list_page',$page_data);

		}elseif($para1=='get_ajax_list'){

			$this->load->library('Ajax_pagination');

			$vendor_id = $this->input->post('vendor');

			$this->db->where('status','ok');

			$this->db->where('featured','ok');

			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$vendor_id)));

			// pagination

			$config['total_rows'] = $this->db->count_all_results('product');

			$config['base_url']   = base_url() . 'index.php?home/listed/';

			$config['per_page'] = 9;

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

			$this->db->where('status','ok');

			$this->db->where('featured','ok');

			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$vendor_id)));

			$page_data['products'] = $this->db->get('product', $config['per_page'], $para2)->result_array();

			$page_data['count']              = $config['total_rows'];

			$this->load->view('front/vendor/public_profile/featured/ajax_list', $page_data);

		}else{

			$page_data['page_title']        = translate('vendor_featured_product');

			$page_data['asset_page']        = "product_list_other";

			$page_data['page_name']        	= "vendor/public_profile";

			$page_data['content']        	= "featured";

			$page_data['vendor_id']			=$para1;

			$this->load->view('front/index', $page_data);

		}

	}

	function all_vendor(){

		if ($this->crud_model->get_settings_value('general_settings','vendor_system') !== 'ok') {

			redirect(base_url() . 'index.php/home');

		}

		$page_data['page_name']        = "vendor/all";

        $page_data['asset_page']       = "all_vendor";

        $page_data['page_title']       = translate('all_vendors');

		$this->load->view('front/index', $page_data);

	}

    function vendor($vendor_id){

		if ($this->crud_model->get_settings_value('general_settings','vendor_system') !== 'ok') {

			redirect(base_url() . 'index.php/home');

		}

		$vendor_system	 =  $this->db->get_where('general_settings',array('type' => 'vendor_system'))->row()->value;

        if($vendor_system	 == 'ok' && 

			$this->db->get_where('vendor',array('vendor_id'=>$vendor_id))->row()->status == 'approved'){

            $min = $this->get_range_lvl('added_by', '{"type":"vendor","id":"'.$vendor_id.'"}', "min");

            $max = $this->get_range_lvl('added_by', '{"type":"vendor","id":"'.$vendor_id.'"}', "max");

            $this->db->order_by('product_id', 'desc');

            $page_data['featured_data'] = $this->db->get_where('product', array(

                'featured' => "ok",

                'status' => 'ok',

                'added_by' => '{"type":"vendor","id":"'.$vendor_id.'"}'

            ))->result_array();

            $page_data['range']             = $min . ';' . $max;

            $page_data['all_category']      = $this->db->get('category')->result_array();

            $page_data['all_sub_category']  = $this->db->get('sub_category')->result_array();

            $page_data['page_name']         = 'vendor_home';

            $page_data['vendor']            = $vendor_id;

            $page_data['page_title']        = $this->db->get_where('vendor',array('vendor_id'=>$vendor_id))->row()->display_name;

            $this->load->view('front/index', $page_data); 

        } else {

             redirect(base_url(), 'refresh');

        }

    }

    function surfer_info(){

        $this->crud_model->ip_data();   

    }

    /* FUNCTION: Loads Customer Profile Page */

    function profile($para1="",$para2="")
    {
        if($para1=="info"){

            $page_data['user_info']     = $this->db->get_where('user',array('user_id'=>$para2))->result_array();

			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);
			exit(json_encode($value));
        }

        if($para1=="wishlist")
		{
	        $ids = implode(',',$this->db->get_where('user',array('user_id'=>$para2))->row()->wishlist);
    	    $this->db->where_in('product_id', $ids);
	        $page_data['wishlist'] = $this->db->get('product', 100, $para2)->result_array();
			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);
			exit(json_encode($value));
        }

        if($para1=="order_history")
		{
        $this->db->where('buyer', $para2);
        $page_data = $this->db->get('sale')->result_array();
		foreach($page_data as $pg)
		{
			$product_details=json_decode($pg['product_details'],1);
			$pg['product_details']='';
			foreach($product_details as $pk)
			{
				$color=json_decode($pk['option']['color'],1);
				$pk['option']['color']=$color[0];
				$pk['name']=$color[0];
				$pg['product_details'][]=$pk;
			}
			$pg['shipping_address']=json_decode($pg['shipping_address'],1);
			$pg['delivery_status']=json_decode($pg['delivery_status'],1);
			$data[]=$pg;
		}
		if(count($data)>0)
		{
			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$data);
		}
		else
		{
			$value=array("status"=>"FAILED","Message"=>"FAILED", "Response"=>$data);
		}

		exit(json_encode($value));

        }

        elseif($para1=="downloads"){

            $this->load->view('front/user/downloads');

        }

        if($para1=="update_profile"){

            $page_data['user_info']     = $this->db->get_where('user',array('user_id'=>$para2))->result_array();

            $value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);
			exit(json_encode($value));

        }

        elseif($para1=="ticket"){

            $this->load->view('front/user/ticket');

        }

		elseif($para1=="message_box"){

			$page_data['ticket']  = $para2;
			$this->db->where('from_where','{"type":"user","id":"'.$para2.'"}');
			$msgs  = $this->db->get_where('ticket')->result_array();
            $value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=> $msgs);
			exit(json_encode($value));
        }

        if($para1=="message_view"){

			$page_data['ticket']  = $para2;

			$page_data['message_data'] = $this->db->get_where('ticket', array(

				'ticket_id' => $para2

			))->result_array();

			$this->crud_model->ticket_message_viewed($para2,'user');

            $this->load->view('front/user/message_view',$page_data);

        } 

    }
	
	function get_vendor_product($para2='')
	{	
		$this->db->where('added_by','{"type":"vendor","id":"'.$para2.'"}');
		$gr_vendor=$this->db->get('product')->result_array();
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$gr_vendor);
		exit(json_encode($value));

	}

	function getBalance()

	{

		$datas = json_decode($this->input->raw_input_stream,1);

		$user_id=$datas['userID'];

		$response['balance']=$this->db->get_where('user_login',array('id'=>$user_id))->row()->balance;

		$results['status'] = 'SUCCESS';

		$results['Response'] = $response;

		echo json_encode($results,true);

		exit;

	}

	function ticket_message($para1=''){

        $page_data['page_name']  = "ticket_message";

        $page_data['ticket']  = $para1;

		$page_data['message_data'] = $this->db->get_where('ticket', array(

			'ticket_id' => $para1

		))->result_array();

		$this->Crud_model->ticket_message_viewed($para1,'user');

		$page_data['msgs']  = $this->db->get_where('ticket_message',array('ticket_id'=>$para1))->result_array();

		$page_data['ticket_id']  = $para1;

        $page_data['page_name']  = "ticket_message";

        $page_data['page_title'] = translate('ticket_message');

		$this->load->view('front/index', $page_data);

	}

	function ticket_message_add(){

		$this->load->library('form_validation');

		$safe = 'yes';

		$char = '';

		foreach($_POST as $row){

			if (preg_match('/[\^}{#~|+¬]/', $row,$match))

			{

				$safe = 'no';

				$char = $match[0];

			}

		}

		$this->form_validation->set_rules('sub', 'Subject', 'required');

		$this->form_validation->set_rules('reply', 'Message', 'required');

		if ($this->form_validation->run() == FALSE)

		{

			echo validation_errors();

		}

		else

		{

			if($safe == 'yes'){

				$data['time'] 			= time();

				$data['subject'] 		= $this->input->post('sub');

				$id              		= $this->session->userdata('user_id');

				$data['from_where'] 	= json_encode(array('type'=>'user','id'=>$id));

				$data['to_where'] 		= json_encode(array('type'=>'admin','id'=>''));

				$data['view_status'] 	= 'ok';

				$this->db->insert('ticket',$data);

				$ticket_id = $this->db->insert_id();	

				$data1['message'] = $this->input->post('reply');

				$data1['time'] = time();

				if(!empty($this->db->get_where('ticket_message',array('ticket_id'=>$ticket_id))->row()->ticket_id))

				{ 

					$data1['from_where'] = $this->db->get_where('ticket_message',array('ticket_id'=>$ticket_id))->row()->from_where;

					$data1['to_where'] = $this->db->get_where('ticket_message',array('ticket_id'=>$ticket_id))->row()->to_where;

				} else {

					$data1['from_where'] = $this->db->get_where('ticket',array('ticket_id'=>$ticket_id))->row()->from_where;

					$data1['to_where'] = $this->db->get_where('ticket',array('ticket_id'=>$ticket_id))->row()->to_where;

				}

				$data1['ticket_id']= $ticket_id;

				$data1['view_status']= json_encode(array('user_show'=>'ok','admin_show'=>'no'));

				$data1['subject']  = $this->db->get_where('ticket',array('ticket_id'=>$ticket_id))->row()->subject;

				$this->db->insert('ticket_message',$data1);

				echo 'success#-#-#';

			} else {

				echo 'fail#-#-#Disallowed charecter : " '.$char.' " in the POST';

			}

		}

	}

	function ticket_reply($para1='') {

		 $this->load->library('form_validation');

		$safe = 'yes';

		$char = '';

		foreach($_POST as $row){

			if (preg_match('/[\^}{#~|+¬]/', $row,$match))

			{

				$safe = 'no';

				$char = $match[0];

			}

		}

		$this->form_validation->set_rules('reply', 'Message', 'required');

		if ($this->form_validation->run() == FALSE)

		{

			echo validation_errors();

		}

		else

		{

			if($safe == 'yes'){

				$data['message'] = $this->input->post('reply');

				$data['time'] = time();

				if(!empty($this->db->get_where('ticket_message',array('ticket_id'=>$para1))->row()->ticket_id))

				{ 

					$data['from_where'] = $this->db->get_where('ticket_message',array('ticket_id'=>$para1))->row()->from_where;

					$data['to_where'] = $this->db->get_where('ticket_message',array('ticket_id'=>$para1))->row()->to_where;

				} else {

					$data['from_where'] = $this->db->get_where('ticket',array('ticket_id'=>$para1))->row()->from_where;

					$data['to_where'] = $this->db->get_where('ticket',array('ticket_id'=>$para1))->row()->to_where;

				}

				$data['ticket_id']= $para1;

				$data['view_status'] = json_encode(array('user_show'=>'ok','admin_show'=>'no'));

				$data['subject']  = $this->db->get_where('ticket',array('ticket_id'=>$para1))->row()->subject;

				$this->db->insert('ticket_message',$data);

				echo 'success#-#-#';

			} else {

				echo 'fail#-#-#Disallowed charecter : " '.$char.' " in the POST';

			}

		}

	} 

	function ticket_listed($para2='')

	{

		$this->load->library('Ajax_pagination');

		$id= $this->session->userdata('user_id');

        $this->db->where('from_where','{"type":"user","id":"'.$id.'"}');

		$this->db->or_where('to_where','{"type":"user","id":"'.$id.'"}');

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

        $this->db->where('from_where','{"type":"user","id":"'.$id.'"}');

		$this->db->or_where('to_where','{"type":"user","id":"'.$id.'"}');

		$page_data['query'] = $this->db->get('ticket', $config['per_page'], $para2)->result_array();

		$this->load->view('front/user/ticket_listed',$page_data);

	}

    function order_listed($para3='')

    {   $id= $para3;


        $this->db->where('buyer', $id);


        $page_data = $this->db->get('sale')->result_array();

		//echo $this->db->last_query();

		foreach($page_data as $pg)

		{

			$product_details=json_decode($pg['product_details'],1);

			$pg['product_details']='';

			//print_r($product_details); exit;

			foreach($product_details as $pk)

			{

				$color=json_decode($pk['option']['color'],1);

				$pk['option']['color']=$color[0];

				

				$pk['name']=$color[0];

				$pg['product_details'][]=$pk;

			}

			$pg['shipping_address']=json_decode($pg['shipping_address'],1);

			$pg['delivery_status']=json_decode($pg['delivery_status'],1);

			$data[]=$pg;

			

		}

		if(count($data)>0)

		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$data);

		else

		$value=array("status"=>"FAILED","Message"=>"FAILED", "Response"=>$data);

		exit(json_encode($value));

    }

    function wish_listed($para2='',$para3='')

    {

        $this->load->library('Ajax_pagination');

        $id= $para3;

        $ids = json_decode($this->db->get_where('user_login',array('id'=>$id))->row()->wishlist,true);

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

        $ids = json_decode($this->db->get_where('user_login',array('id'=>$id))->row()->wishlist,true);

        $this->db->where_in('product_id', $ids);

        $page_data['query'] = $this->db->get('product', $config['per_page'], $para2)->result_array();

        $this->load->view('front/user/wish_listed',$page_data);

    }

    function downloads_listed($para2='')

    {

        $this->load->library('Ajax_pagination');

        $id= $this->session->userdata('user_id');

        $downloads = json_decode($this->db->get_where('user_login',array('id'=>$id))->row()->downloads,true);

		$ids = array();

		foreach($downloads as $row){

			$ids[] = $row['product'];

		}

		if(count($ids)!== 0){

        	$this->db->where_in('product_id', $ids);

    	}

    	else{

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

        if(count($ids)!== 0){

        	$this->db->where_in('product_id', $ids);

    	}

    	else{

    		$this->db->where('product_id', 0);

    	}

        $page_data['query'] = $this->db->get('product', $config['per_page'], $para2)->result_array();

        $this->load->view('front/user/downloads_listed',$page_data);

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

        if($this->crud_model->can_download($id)){

            echo 'ok';

        } else {

            echo 'not';

        }

    }

    function category($para1 = "", $para2 = "", $min = "", $max = "", $text ='')

    {

		if ($para2 == "") {

            $page_data['all_products1'] = $this->db->get_where('product', array('category' => $para1))->result_array();

        } else if ($para2 != "") {

            $page_data['all_products1'] = $this->db->get_where('product', array('sub_category' => $para2))->result_array();

        }

		if($para1 == "" || $para1 == "0"){

			$type = 'other';

		} else {

			if($this->db->get_where('category',array('category_id'=>$para1))->row()->digital == 'ok'){

				$type = 'digital';

			} else {

				$type = 'other';

			}

		}

		$type = 'other';

		$brand_sub = explode('-',$para2);

		$sub 	= 0;

		$brand  = 0;		

		if(isset($brand_sub[0])){

			$sub = $brand_sub[0];

		}

		if(isset($brand_sub[1])){

			$brand = $brand_sub[1];

		}

		

		foreach($page_data['all_products1'] as $p)

		{	

		

		$product_id=$p['product_id'];

			if($p['discount']=='')

			{

				$p['discount']=0.00;

			}

			$p['banner']=$this->crud_model->file_view('product',$product_id,'','','thumb','src','multi','one');

			$temppProduct[]=$p;

		

		}

		$page_data['all_products']=$temppProduct;

		unset($page_data['all_products1']);

		if(count($page_data['all_products'])!=0)

		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data['all_products']);

		else

		$value=array("status"=>"FAILED","Message"=>"No Products Found");

		exit(json_encode($value));

    }

	function all_category($para1 = ""){

		$categories=$this->db->get('category')->result_array();
		foreach($categories as $row)

		{

			if($this->crud_model->if_publishable_category($row['category_id'])){

				$row['count']=$this->crud_model->is_publishable_count('category',$row['category_id']);

				$sub_categories=$this->db->get_where('sub_category',array('category'=>$row['category_id']))->result_array();

				$row['banner']=base_url().'uploads/category_image/'.$row['banner'];

				$response['category'][]=$row;

			}

		}

		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$response);

		exit(json_encode($value));

	}

	function sub_category($para1 = "")
	{
		$sub_categories=$this->db->get_where('sub_category',array('category'=>$para1))->result_array();

				foreach($sub_categories as $row1){

					$sub['sub_category_id'] =$row1['sub_category_id'];

                    $sub['sub_category_name'] = $row1['sub_category_name'];

					$sub['digital'] =$row1['digital'];

					$row[]=$sub;

				}
				$response['sub_category']=$row;
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$response);

		exit(json_encode($value));
	}
	
	

	function all_brands(){

		if ($this->crud_model->get_settings_value('general_settings','vendor_system') !== 'ok') 		{

			redirect(base_url());

		}

        $page_data['page_name']        = "others/all_brands";

        $page_data['asset_page']       = "all_brands";

        $page_data['page_title']       = translate('all_brands');

		$this->load->view('front/index', $page_data);

	}

	function faq($oid)
	{	
		$faq = array();
		$faq['faq'] = $this->db->get_where('business_settings',array('type'=>'faqs'))->result_array();
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$faq);
		exit(json_encode($value));
	}
	


    /* FUNCTION: Search Products */

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

            redirect(base_url() . 'index.php/home/category/' . $category . '/' . $sub_category . '-'.$brand.'/' . $p[0] . '/' . $p[1] . '/' . $query, 'refresh');

        } else if ($param == 'top') {

            redirect(base_url().'index.php/home/category/' . $category, 'refresh');

        }

    }
	
	

    function text_search(){

		if ($this->crud_model->get_settings_value('general_settings','vendor_system') !== 'ok') {

			$search = $this->input->post('query');

			$category = $this->input->post('category');

			redirect(base_url() . 'index.php/home/category/'.$category.'/0-0/0/0/'.$search, 'refresh');

		}else{

			$type = $this->input->post('type');

			$search = $this->input->post('query');

			$category = $this->input->post('category');

			if($type == 'vendor'){

				redirect(base_url() . 'index.php/home/store_locator/'.$search, 'refresh');

			} else if($type == 'product'){

				redirect(base_url() . 'index.php/home/category/'.$category.'/0-0/0/0/'.$search, 'refresh');

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

		if ($this->crud_model->get_settings_value('general_settings','vendor_system') !== 'ok') {

			redirect(base_url() . 'index.php/home');

		}

        $page_data['page_name']        = "others/store_locator";

        $page_data['asset_page']       = "store_locator";

        $page_data['page_title']       = translate('store_locator');

        $page_data['vendors'] = $this->db->get_where('vendor',array('status'=>'approved'))->result_array();

        $page_data['text'] = $parmalink;

		$this->load->view('front/index', $page_data);

    }

    /* FUNCTION: Loads Custom Pages */

    function page($parmalink = '')

    {

        $pagef                   = $this->db->get_where('page', array(

            'parmalink' => $parmalink

        ));

        if($pagef->num_rows() > 0){

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

    function product_view($para1 = "",$para2 = "")

    {

        $product_data   	= $this->db->get_where('product', array('product_id' => $para1,'status' => 'ok'));

        $this->db->where('product_id', $para1);

        $this->db->update('product', array(

            'number_of_view' => $product_data->row()->number_of_view+1,

            'last_viewed' => time()

        ));

		if($product_data->row()->download == 'ok'){

			$type = 'digital';

		} else {

			$type = 'other';

		}

		$page_data['product_details']=$this->db->get_where('product', array('product_id' => $para1,'status' => 'ok'))->result_array();

        $page_data['page_name']    = "product_view/".$type."/page_view";

        $page_data['asset_page']   = "product_view_".$type;

        $page_data['product_data'] = $product_data->result_array();

		$page_data['product_data']=$page_data['product_data'][0];

        $page_data['page_title']   = $product_data->row()->title;

        $page_data['product_tags'] = $product_data->row()->tag;

		$page_data['product_data']['banner']=$this->crud_model->file_view('product',$para1,'','','thumb','src','multi','one');			       

		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);

		exit(json_encode($value));

        //$this->load->view('front/index', $page_data);

    }

	function contact_address($para1 = "",$para2 = "")

    {

		$page_data['contact_address'] =  $this->db->get_where('general_settings',array('type' => 'contact_address'))->row()->value;

		//echo $this->db->last_query(); exit;

    $page_data['contact_phone'] =  $this->db->get_where('general_settings',array('type' => 'contact_phone'))->row()->value;

    $page_data['contact_email'] =  $this->db->get_where('general_settings',array('type' => 'contact_email'))->row()->value;

    $page_data['contact_website'] =  $this->db->get_where('general_settings',array('type' => 'contact_website'))->row()->value;

    $page_data['contact_about'] =  $this->db->get_where('general_settings',array('type' => 'contact_about'))->row()->value;

				       

		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);

		exit(json_encode($value));

        //$this->load->view('front/index', $page_data);

    }

    /* FUNCTION: Loads Product View Page */

    function quick_view($para1 = "")
    {
        $product_data   = $this->db->get_where('product', array('product_id' => $para1,'status' => 'ok'));
        $page_data['product_details'] = $product_data->result_array();
		if(count($page_data['product_details'])!=0)
		{
			$page_data['product_details']=$page_data['product_details'][0];

			$page_data['product_details']['description']=str_replace('</li>','</p>',str_replace('<li>','<p>',str_replace('</ul>','',str_replace('<ul>','',str_replace('<div>','',str_replace('</div>','',$page_data['product_details']['description']))))));

			$page_data['product_tags'] = $product_data->row()->tag;

			$page_data['product_details']['banner']=$this->crud_model->file_view('product',$para1,'','','thumb','src','multi','one');

			$page_data['product_details']['additional_specification']=$this->crud_model->get_additional_fields($row['product_id']);			       	$page_data['product_details']['shipment_info']=$this->db->get_where('business_settings',array('type'=>'shipment_info'))->row()->value;

			$page_data['product_details']['product_by']=$this->crud_model->product_by($para1,'with_link');

			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);

			exit(json_encode($value));

		}

		else

		{

			$value=array("status"=>"FAILED","Message"=>"Product not available");

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

		if($this->crud_model->get_settings_value('general_settings','captcha_status','value') == 'ok'){

        	$this->load->library('recaptcha');

		}

        $this->load->library('form_validation');

        if ($para1 == 'send') {

            $safe = 'yes';

            $char = '';

            foreach($_POST as $row){

                if (preg_match('/[\'^":()}{#~><>|=+¬]/', $row,$match))

                {

                    $safe = 'no';

                    $char = $match[0];

                }

            }

            $this->form_validation->set_rules('name', 'Name', 'required');

            $this->form_validation->set_rules('subject', 'Subject', 'required');

            $this->form_validation->set_rules('message', 'Message', 'required');

            $this->form_validation->set_rules('email', 'Email', 'required');

            if ($this->form_validation->run() == FALSE)

            {

                echo validation_errors();

            }

            else

            {

                if($safe == 'yes'){

					if($this->crud_model->get_settings_value('general_settings','captcha_status','value') == 'ok'){

						$captcha_answer = $this->input->post('g-recaptcha-response');

						$response = $this->recaptcha->verifyResponse($captcha_answer);

						if ($response['success']) {

							$data['name']      = $this->input->post('name',true);

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

					}else{

						$data['name']      = $this->input->post('name',true);

						$data['subject']   = $this->input->post('subject');

						$data['email']     = $this->input->post('email');

						$data['message']   = $this->security->xss_clean(($this->input->post('message')));

						$data['view']      = 'no';

						$data['timestamp'] = time();

						$this->db->insert('contact_message', $data);

						echo 'sent';

					}

                } else {

                    echo 'Disallowed charecter : " '.$char.' " in the POST';

                }

            }

        } else {

			if($this->crud_model->get_settings_value('general_settings','captcha_status','value') == 'ok'){

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

		if($this->crud_model->get_settings_value('general_settings','captcha_status','value') == 'ok'){

			$this->load->library('recaptcha');

		}

        $this->load->library('form_validation');

        if ($para1 == "add_info") {

        	$msg = '';

            $this->load->library('form_validation');

            $safe = 'yes';

            $char = '';

            foreach($_POST as $k=>$row){

                if (preg_match('/[\'^":()}{#~><>|=¬]/', $row,$match))

                {

                    if($k !== 'password1' && $k !== 'password2')

                    {

                        $safe = 'no';

                        $char = $match[0];

                    }

                }

            }

            $this->form_validation->set_rules('name', 'Your First Name', 'required');

            $this->form_validation->set_rules('email', 'Email', 'valid_email|required|is_unique[vendor.email]',array('required' => 'You have not provided %s.', 'is_unique' => 'This %s already exists.'));

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

            if ($this->form_validation->run() == FALSE)

            {

                echo validation_errors();

            }

            else

            {

                if($safe == 'yes'){

					if($this->crud_model->get_settings_value('general_settings','captcha_status','value') == 'ok'){

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

								if($this->email_model->account_opening('vendor', $data['email'], $password) == false){

									$msg = 'done_but_not_sent';

								}else{

									$msg = 'done_and_sent';

								}

							}

							echo $msg;

						} else {

							echo translate('please_fill_the_captcha');

						}

					}else{

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

							if($this->email_model->account_opening('vendor', $data['email'], $password) == false){

								$msg = 'done_but_not_sent';

							}else{

								$msg = 'done_and_sent';

							}

						}

						echo $msg;

					}

                } else {

                    echo 'Disallowed charecter : " '.$char.' " in the POST';

                }

            }

        } else if($para1 == 'registration') {

			if ($this->crud_model->get_settings_value('general_settings','vendor_system') !== 'ok') {

				redirect(base_url());

			}

			if($this->crud_model->get_settings_value('general_settings','captcha_status','value') == 'ok'){

				$page_data['recaptcha_html'] = $this->recaptcha->render();

			}

			$page_data['page_name'] = "vendor/register";

			$page_data['asset_page'] = "register";

        	$page_data['page_title'] = translate('registration');

            $this->load->view('front/index', $page_data);

        }

    }

	function vendor_login_msg(){

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

			$datas = json_decode($this->input->raw_input_stream,1);

			//print_r($datas);

			$signin_data = $this->db->get_where('user', array(

				'email' => $datas['email'],

				'password' => sha1($datas['password'])

			));

			//echo $signin_data->num_rows();

			if ($signin_data->num_rows() > 0) {

				foreach ($signin_data->result_array() as $row) {

					

					//$accessToken = bin2hex(openssl_random_pseudo_bytes(16));

					$accessToken = rand(1234567890,16);

					$this->session->set_userdata('user_login', 'yes');

					$this->session->set_userdata('user_id', $row['user_id']);

					$this->session->set_userdata('username', $row['username']);

					$this->session->set_flashdata('alert', 'successful_signin');

					$_SESSION['user']['first_name']=$row['username'];

					$_SESSION['user']['id']=$row['user_id'];

					$_SESSION['user']['email']=$row['email'];

					$_SESSION['user']['mobile_number']=$row['phone'];


					$response['id']=$row['user_id'];

					$response['first_name']=$row['username'];

					$response['email_id']=$row['email'];

					$response['mobile_number']=$row['phone'];


					$response['accessToken']=$accessToken;

					$results['status'] = 'SUCCESS';

					$results['Message'] = 'Login Success';

					$results['Response'] = $response;

					echo json_encode($results,true);

					exit;

				}

			}

			else 

			{

				$results['status'] = 'FAILED';

				$results['Message'] = 'Invalid username or password';

				echo json_encode($results,true);

				exit;

			}

        } else if ($para1 == 'forget') {

        	$datas = json_decode($this->input->raw_input_stream,1);	

				$email=$datas['email'];

				$query=$this->db->select('*')->from('user_login')

					->group_start()

							->or_group_start()

									->where('email_id', $email)

									->where('email_id !=', 'NULL')

									->where('email_id !=', '')

							->group_end()

							->or_group_start()

									->where('mobile_number', $email)

									->where('mobile_number !=','')

							->group_end()

					->group_end()

					->where('status',1)

					->get();

				if ($query->num_rows() > 0) {

					$user_id          = $query->row()->id;

					$mobile_number          = $query->row()->mobile_number;

					$password         = substr(rand(), 0, 12);

					$data['password'] = md5($password.'987ABLO@@##$$%%');

					$this->db->where('id', $user_id);

					$this->db->update('user_login', $data);

					$content='Dear User, Your new passowrd is '.$password.' Thanks for using momomal.com';

					$this->crud_model->sendsms($mobile_number,$content);

					if ($this->email_model->password_reset_email('user', $user_id, $password)) {

						$results['status'] = 'SUCCESS';

						$results['Message'] = 'Your new password sent to your email';

						$results['Response'] = 'E-mail Sent';

						echo json_encode($results,true);

						exit;

					} else {

						$results['status'] = 'FAILED';

						$results['Message'] = 'Mail sending failed';

						$results['Response'] = 'Mail sending failed';

						echo json_encode($results,true);

						exit;

					}

				} else {

					$results['status'] = 'FAILED';

					$results['Message'] = 'Mail ID not found';

					$results['Response'] = 'Mail ID not found';

					echo json_encode($results,true);

					exit;

				}

			}

    }

    /* FUNCTION: Setting login page with facebook and google */

    function login_set($para1 = '', $para2 = '', $para3 = '')

    {

        if ($this->session->userdata('user_login') == "yes") {

            redirect(base_url().'index.php/home/profile', 'refresh');

        }

		if($this->crud_model->get_settings_value('general_settings','captcha_status','value') == 'ok'){

			$this->load->library('recaptcha');

		}

        $this->load->library('form_validation');

        $fb_login_set = $this->crud_model->get_settings_value('general_settings','fb_login_set');

        $g_login_set  = $this->crud_model->get_settings_value('general_settings','g_login_set');

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

                        "email","public_profile"

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

                if ($user_id = $this->crud_model->exists_in_table('user_login', 'fb_id', $user['id'])) {

                } else {

                    $data['username']      = $user['first_name'];

                    $data['surname']       = $user['last_name'];

                    $data['email']         = $user['email'];

                    $data['fb_id']         = $user['id'];

                    $data['wishlist']      = '[]';

                    $data['creation_date'] = time();

                    $data['password']      = substr(hash('sha512', rand()), 0, 12);

                    $this->db->insert('user_login', $data);

                    $user_id = $this->db->insert_id();

                }

                $this->session->set_userdata('user_login', 'yes');

                $this->session->set_userdata('user_id', $user_id);

                $this->session->set_userdata('user_name', $this->db->get_where('user_login', array(

                    'id' => $user_id

                ))->row()->username);

                $this->session->set_flashdata('alert', 'successful_signin');

                $this->db->where('id', $user_id);

                $this->db->update('user_login', array(

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

                $this->googleplus->client->authenticate();

                $_SESSION['token'] = $this->googleplus->client->getAccessToken();

                $g_user            = $this->googleplus->people->get('me');

                if ($user_id = $this->crud_model->exists_in_table('user_login', 'g_id', $g_user['id'])) {

                } else {

                    $data['username']      = $g_user['displayName'];

                    $data['email']         = 'required';

                    $data['wishlist']      = '[]';

                    $data['g_id']          = $g_user['id'];

                    $data['g_photo']       = $g_user['image']['url'];

                    $data['creation_date'] = time();

                    $data['password']      = substr(hash('sha512', rand()), 0, 12);

                    $this->db->insert('user_login', $data);

                    $user_id = $this->db->insert_id();

                }

                $this->session->set_userdata('user_login', 'yes');

                $this->session->set_userdata('user_id', $user_id);

                $this->session->set_userdata('user_name', $this->db->get_where('user_login', array(

                    'id' => $user_id

                ))->row()->username);

                $this->session->set_flashdata('alert', 'successful_signin');

                $this->db->where('id', $user_id);

                $this->db->update('user_login', array(

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

            if($para2 == 'modal'){

                $this->load->view('front/user/login/quick_modal', $page_data);

            } else {

                $this->load->view('front/index', $page_data);

            }

        } elseif ($para1 == 'registration') {

			if($this->crud_model->get_settings_value('general_settings','captcha_status','value') == 'ok'){

				$page_data['recaptcha_html'] = $this->recaptcha->render();

			}

            $page_data['page_name'] = "user/register";

			$page_data['asset_page'] = "register";

        	$page_data['page_title'] = translate('registration');

            if($para2 == 'modal'){

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

        $this->facebook->destroySession();

        $this->session->sess_destroy();

        redirect(base_url() . 'index.php/home/logged_out', 'refresh');

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

        foreach($_POST as $row){

            if (preg_match('/[\'^":()}{#~><>|=+¬]/', $row,$match))

            {

                $safe = 'no';

                $char = $match[0];

            }

        }

        $this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email', 'required');

		if ($this->form_validation->run() == FALSE)

		{

			echo validation_errors();

		}

		else

		{

            if($safe == 'yes'){

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

                echo 'Disallowed charecter : " '.$char.' " in the POST';

            }

		}

    }

    /* FUNCTION: Customer Registration*/

    function registration($para1 = "", $para2 = "")

    {

        $safe = 'yes';

        $char = '';

        foreach($_POST as $k=>$row){

            if (preg_match('/[\'^":()}{#~><>|=¬]/', $row,$match))

            {

                if($k !== 'password1' && $k !== 'password2')

                {

                    $safe = 'no';

                    $char = $match[0];

                }

            }

        }

		if($this->crud_model->get_settings_value('general_settings','captcha_status','value') == 'ok'){

			$this->load->library('recaptcha');

		}

        $page_data['page_name'] = "registration";

        if ($para1 == "add_info") {

        	$msg = '';

			$datas = json_decode($this->input->raw_input_stream,1);

			//print_r($datas);

			$this->crud_model->get_settings_value('general_settings','captcha_status','value');

			if($safe == 'yes'){

				if($this->crud_model->get_settings_value('general_settings','captcha_status','value') == 'ok'){

					$captcha_answer = $this->input->post('g-recaptcha-response');

					$response = $this->recaptcha->verifyResponse($captcha_answer);

					if ($response['success']) {

						$data['first_name']    = $datas['firstname'];

						$data['last_name']     = $datas['lastname'];

						$data['email_id']      = $datas['email'];

						$data['address1']      = $datas['address1'];

						$data['address2']      = $datas['address2'];

						$data['mobile_number'] = $datas['phone'];

						$data['pincode']       = $datas['zip'];

						$data['city']          = $datas['city'];

						$data['state']         = $datas['state'];

						$data['country']       = $datas['country'];

						$data['langlat']       = '';

						$data['wishlist']      = '[]';

						$data['creation_date'] = time();

						$password              = $datas['password'];


						$data['password']      = md5($password);

						$account_data = $this->db->select('*')->from('user')

						->group_start()

								->or_group_start()

										->where('email_id', $data['email_id'])

								->group_end()

								->or_group_start()

										->where('mobile_number', $data['mobile_number'])

								->group_end()

						->group_end()

						->get()->num_rows();

						if($account_data == 0)

						{

							$this->db->insert('user', $data);

							$msg = 'done';

							if($this->email_model->account_opening('user', $data['email_id'], $password) == false){

								$msg = 'done_but_not_sent';

							}else{

								$msg = 'done_and_sent';

							}

						}

						else

						{

							$msg = "Email or Mobile Number already exists";

						}

					}else{

						echo translate('please_fill_the_captcha');

					}

				}

				else{

					$data['username']    = $datas['firstname'];

					$data['surname']     = $datas['lastname'];

					$data['email']      = $datas['email'];

					$data['phone'] = $datas['phone'];

					$data['zip']       = $datas['zip'];

					$data['city']          = $datas['city'];

					$data['state']         = $datas['state'];

					$data['country']       = $datas['country'];

					$data['creation_date'] = time();

					$password              = $datas['password'];

					$data['password'] = md5($password);


					 					//$account_data = $this->db->select('*')->from('user')->group_start()->or_group_start()->where('email', $data['email_id'])->group_end()->or_group_start()->where('mobile', $data['mobile_number'])->group_end()->group_end()->get()->num_rows();

					//$this->db->where('email', $data['email_id']);

					//$this->db->where('mobile', $data['mobile_number']);

			

			//$page_data['sliders']       = $this->db->get('slides')->result_array();

					//$account_data = $this->db->get('user')->result_array();

					$account_data = $this->db->get_where('user', array('email' => $data['email'],'phone'=>$data['phone']))->num_rows();

					

					if($account_data == 0)

					{

						$this->db->insert('user', $data);

						/*if ($this->db->affected_rows() > 0 ) {

							$return_message = 'Insert successful';

							}else{

							$return_message = 'Failed to insert record';

							}

							echo $return_message; exit;*/

							

						$results['status'] = 'SUCCESS';

							$results['Message'] = "Registred successfully";

							echo json_encode($results,true);

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

					}

					else

					{

						$results['status'] = 'FAILED';

						$results['Message'] = "Email or Mobile Number already exists";

						echo json_encode($results,true);

						exit;

					}

				}

			} else {

				echo 'Disallowed charecter : " '.$char.' " in the POST';

			}



        }

        else if ($para1 == "update_info") {

            $id                  = $this->session->userdata('user_id');

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

            $this->db->where('id', $id);

            $this->db->update('user_login', $data);

            echo "done";

        }

        else if ($para1 == "update_password") {

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

        } 

        else if ($para1 == "change_picture")

        {

            $id                  = $this->session->userdata('user_id');

            $this->crud_model->file_up('img','user',$id,'','','.jpg');  

            echo 'done';

        } else {

            $this->load->view('front/registration', $page_data);

        }

    }

    function error()

    {

        $this->load->view('front/others/404_error');

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

            $this->session->set_userdata('compare','');

            redirect(base_url().'index.php/home', 'refresh');

        } else if ($para1 == 'get_detail') {

            $product = $this->db->get_where('product',array('product_id'=>$para2));

            $return = array();

            $return += array('image' => '<img src="'.$this->crud_model->file_view('product',$para2,'','','thumb','src','multi','one').'" width="100" />');

            $return += array('price' => currency().$product->row()->sale_price);

            $return += array('description' => $product->row()->description);

            if($product->row()->brand){

                $return += array('brand' => $this->db->get_where('brand',array('brand_id'=>$product->row()->brand))->row()->name);

            }

            if($product->row()->sub_category){

                $return += array('sub' => $this->db->get_where('sub_category',array('sub_category_id'=>$product->row()->sub_category))->row()->sub_category_name);

            }

            echo json_encode($return);

        } else {

            if($this->session->userdata('compare') == '[]'){

                redirect(base_url() . 'index.php/home/', 'refresh');

            }



            $page_data['page_name']  = "others/compare";

			$page_data['asset_page']  = "compare";

            $page_data['page_title'] = 'compare';

            $this->load->view('front/index', $page_data);

        }

    }

	function cancel_order(){

        $this->session->set_userdata('sale_id', '');

        $this->session->set_userdata('couponer','');

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

            $option = array('color'=>array('title'=>'Color','value'=>$color));

            $all_op = json_decode($this->crud_model->get_type_name_by_id('product',$para2,'options'),true);

            if($all_op){

                foreach ($all_op as $ro) {  

                    $name = $ro['name'];

                    $title = $ro['title'];

                    $option[$name] = array('title'=>$title,'value'=>$this->input->post($name));

                }

            }

            if($para3 == 'pp') {

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

            $this->session->set_userdata('couponer','');

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

            if(count($carted) == 0){

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

                    $return[] = array('id'=>$row['rowid'],'price'=>currency($row['price']),'subtotal'=>currency($row['subtotal']));

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

        if($para1 == "orders"){

            $this->load->view('front/shopping_cart/order_set');  

        } elseif($para1 == "delivery_address"){

            $this->load->view('front/shopping_cart/delivery_address');  

        } elseif($para1 == "payments_options"){

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

		$datas = json_decode($this->input->raw_input_stream,1);

		if(isset($datas['userID']) && $datas['userID']!='' && $datas['userID']!=0 && $datas['mode']=='user' && isset($datas['cart']) && $datas['cart']!='' && is_array($datas['cart']))

		{

			$userID=$datas['userID'];

			$cartDetails=$datas['cart'];

			$userDetails=$this->db->get_where('user',array('user_id'=>$userID))->result_array();

			$userDetails=$userDetails[0];

			$balance=$userDetails['balance'];

			$data['buyer'] = $userID;

			foreach($datas['cart'] as $cart)

			{

				$cartV['id']=$product_id=$cart['product_id'];	

				$productInfo[] = $this->db->get_where('product',array('product_id'=>$product_id))->result_array();

				$productInfo = $productInfo[0][0];

				$quantity=$cartV['qty']=$cart['qty'];	

				if($cart['qty']=='' || $cart['qty']==0 || $productInfo['title']=='' || $productInfo['color']=='' || $productInfo['sale_price']=='')

				{

					$results['status'] = 'FAILED';

					$results['Message'] = 'Invalid Request';

					$results['Response'] = 'Invalid Request';

					echo json_encode($results,true);

					exit;

				}

				$productColor=$cartOption['color']=$productInfo['color'];	

				$productName=$cartOption['title']=$productInfo['title'];	

				$cartOption['value']="";

				$cartV['option']	=$cartOption;

				$productPrice=$cartV['price']=$productInfo['sale_price'];	

				$cartV['name']=$productInfo['title'];	

				$shipping=$cartV['shipping']=$productInfo['shipping_cost'];	

				$salePrice=$productPrice*$quantity;

				if($productInfo['tax']!='' && $productInfo['tax']!=0)

				{

					if($productInfo['tax_type']=='percent')

					{

						$tax=$salePrice*($productInfo['tax']/100);

					}

					else

					$tax=$productInfo['tax'];

				}

				else

				$tax=0.00;

				$cartV['tax']=$tax;	

				$productImage=$cartV['image']=$this->crud_model->file_view('product', $cart['product_id'], '', '', 'thumb', 'src', 'multi', 'one');	

				$cartV['coupon']=$cart['coupon'];	

				$cartV['subtotal']=$grand_total+=$salePrice+$tax+$productInfo['shipping_cost'];	

				$cartArray[rand(10000,100000).rand(10000,100000)]=$cartV;

			}

			if($datas['firstname']=='' || $datas['address1']=='' || $datas['zip']=='')

			{

					$results['status'] = 'FAILED';

					$results['Message'] = 'Invalid Request';

					$results['Response'] = 'Invalid Request';

					echo json_encode($results,true);

					exit;

			}

			$firstname=$shippingAddress['firstname']=$datas['firstname'];

			$lastname=$shippingAddress['lastname']=$datas['lastname'];

			$address1=$shippingAddress['address1']=$datas['address1'];

			$address2=$shippingAddress['address2']=$datas['address2'];

			$zip=$shippingAddress['zip']=$datas['zip'];

			$email=$shippingAddress['email']=$datas['email'];

			$mobile=$shippingAddress['phone']=$datas['phone'];

			$shippingAddress['langlat']=$datas['langlat'];

			$payment_type=$shippingAddress['payment_type']=$datas['payment_type'];

			$data['product_details']   = json_encode($cartArray);

			$data['shipping_address']  = json_encode($shippingAddress);

			$data['vat']               = $tax;

			$data['vat_percent']       = $productInfo['tax'];

			$data['shipping']          = $shipping;

			$data['delivery_status']   = '';

			$data['payment_status']    = '[]';

			$data['payment_details']   = '';

			$data['grand_total']       = $grand_total;

			$data['sale_datetime']     = time();

			$data['delivary_datetime'] = '';

			$data['status']      = 'pending';

			
			if($datas['payment_type']=='wallet')

			{

				if($balance<=$grand_total)

				{

					$results['status'] = 'FAILED';

					$results['Message'] = 'Insufficient Balance';

					$results['Response'] = 'Insufficient Balance';

					echo json_encode($results,true);

					exit;

				}	

				$data['payment_type']      = 'wallet';

				$data['order_id']='MOMOD'.substr(time(),4).rand(1,10).rand(1,99).($this->db->count_all_results('sale')+1);

				$this->db->insert('sale', $data);

				$sale_id           = $this->db->insert_id();

				$vendors = $this->crud_model->vendors_in_sale($sale_id);

				$delivery_status = array();

				$payment_status = array();

				foreach ($vendors as $p) {

					$delivery_status[] = array('vendor'=>$p,'status'=>'pending','delivery_time'=>'');

					$payment_status[] = array('vendor'=>$p,'status'=>'paid');

				}

				if($this->crud_model->is_admin_in_sale($sale_id)){

					$delivery_status[] = array('admin'=>'','status'=>'pending','delivery_time'=>'');

					$payment_status[] = array('admin'=>'','status'=>'paid');

				}

				echo $sale_code=$data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id; exit;

				$data['delivery_status'] = json_encode($delivery_status);

				$data['payment_status'] = json_encode($payment_status);

				$data['payment_timestamp']=date('Y-m-d H:i:s');

				$data['status'] = 'success';

				$this->db->where('sale_id', $sale_id);

				$this->db->update('sale', $data);

				foreach ($datas['cart'] as $value) {

                        $this->crud_model->decrease_quantity($value['product_id'], $value['qty']);

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

					$currentBalance = $balance-$grand_total;

					$data2['balance'] = $currentBalance;

                    $this->db->where('id', $userID);

                    $this->db->update('user_login', $data2);

					$paymentDetails=$this->db->get_where('sale',array('sale_code'=>$sale_code,'status'=>'success'))->result_array();

					if(isset($paymentDetails[0]))

					{

						$paymentDetails=$paymentDetails[0];

						$product_details=json_decode($paymentDetails['product_details'],1);

						foreach($product_details as $p)

						{

							$pDte[]=$p;

						}

						$product_details=$pDte;

						$grand_total=$paymentDetails['grand_total'];

						$shipping_address=json_decode($paymentDetails['shipping_address'],1);

						$payment_type=$paymentDetails['payment_type'];

						$payment_status=json_decode($paymentDetails['payment_status'],1);

						$delivery_status=json_decode($paymentDetails['delivery_status'],1);

						$sale_id=$paymentDetails['sale_id'];

						$order_id=$paymentDetails['order_id'];

						$sale_code=$paymentDetails['sale_code'];

						$results['status'] = 'SUCCESS';

						$results['Message'] = 'Order Completed-'.$order_id;

						$results['Response'] = array("sale_code"=>$sale_code,"order_id"=>$order_id,"product_details"=>$product_details,"total_amount"=>$grand_total,"shipping_address"=>$shipping_address,"payment_status"=>$payment_status,"delivery_status"=>$delivery_status,"payment_type"=>$payment_type,"create_date"=>date('Y-m-d h:i:s'));

						echo json_encode($results,true);

						exit;

					}

					else

					{

						$results['status'] = 'FAILED';

						$results['Message'] = 'Invalid Order';

						echo json_encode($results,true);

						exit;

					}

					echo json_encode($results,true);

					exit;

			}
			
			if($datas['payment_type']=='cash_on_delivery')

			{

				

				$data['payment_type']      = 'cash_on_delivery';

				$data['order_id']='UNI'.substr(time(),4).rand(1,10).rand(1,99).($this->db->count_all_results('sale')+1);

				//echo '<pre>'; print_r($data);
				$this->db->insert('sale', $data);

				$this->db->last_query(); 

				$sale_id           = $this->db->insert_id();

				$vendors = $this->crud_model->vendors_in_sale($sale_id);

				$delivery_status = array();

				$payment_status = array();

				foreach ($vendors as $p) {

					$delivery_status[] = array('vendor'=>$p,'status'=>'pending','delivery_time'=>'');

					$payment_status[] = array('vendor'=>$p,'status'=>'paid');

				}

				if($this->crud_model->is_admin_in_sale($sale_id)){

					$delivery_status[] = array('admin'=>'','status'=>'pending','delivery_time'=>'');

					$payment_status[] = array('admin'=>'','status'=>'pending');

				}

				$sale_code=$data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;

				$data['delivery_status'] = json_encode($delivery_status);

				$data['payment_status'] = json_encode($payment_status);

				$data['payment_timestamp']=date('Y-m-d H:i:s');

				$data['status'] = 'success';

				$this->db->where('sale_id', $sale_id);

				$this->db->update('sale', $data);

				foreach ($datas['cart'] as $value) {

                        $this->crud_model->decrease_quantity($value['product_id'], $value['qty']);

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

					//$currentBalance = $balance-$grand_total;

					//$data2['balance'] = $currentBalance;

                    //$this->db->where('id', $userID);

                    //$this->db->update('user', $data2);
					//echo json_encode($sale_code,true);
					$paymentDetails=$this->db->get_where('sale',array('sale_code'=>$sale_code,'status'=>'success'))->result_array();

					if(isset($paymentDetails[0]))

					{

						$paymentDetails=$paymentDetails[0];

						$product_details=json_decode($paymentDetails['product_details'],1);

						foreach($product_details as $p)

						{

							$pDte[]=$p;

						}

						$product_details=$pDte;

						$grand_total=$paymentDetails['grand_total'];

						$shipping_address=json_decode($paymentDetails['shipping_address'],1);

						$payment_type=$paymentDetails['payment_type'];

						$payment_status=json_decode($paymentDetails['payment_status'],1);

						$delivery_status=json_decode($paymentDetails['delivery_status'],1);

						$sale_id=$paymentDetails['sale_id'];

						$order_id=$paymentDetails['order_id'];

						$sale_code=$paymentDetails['sale_code'];

						$results['status'] = 'SUCCESS';

						$results['Message'] = 'Order Completed-'.$order_id;

						$results['Response'] = array("sale_code"=>$sale_code,"order_id"=>$order_id,"product_details"=>$product_details,"total_amount"=>$grand_total,"shipping_address"=>$shipping_address,"payment_status"=>$payment_status,"delivery_status"=>$delivery_status,"payment_type"=>$payment_type,"create_date"=>date('Y-m-d h:i:s'));

						echo json_encode($results,true);

						exit;

					}

					else

					{

						$results['status'] = 'FAILED';

						$results['Message'] = 'Invalid Order';

						echo json_encode($results,true);

						exit;

					}

					echo json_encode($results,true);

					exit;

			}

			if($datas['payment_type']=='instamojo')

			{

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

                        $delivery_status[] = array('vendor'=>$p,'status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('vendor'=>$p,'status'=>'pending');

                    }

                    if($this->crud_model->is_admin_in_sale($sale_id)){

                        $delivery_status[] = array('admin'=>'','status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('admin'=>'','status'=>'pending');

                    }

                    $saleCode=$data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;

                    $data['delivery_status'] = json_encode($delivery_status);

                    $data['payment_status'] = json_encode($payment_status);

                    $this->db->where('sale_id', $sale_id);

                    $this->db->update('sale', $data);

                    $this->session->set_userdata('sale_id', $sale_id);

					/*instamojo*/

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

					}

			}

			if($datas['payment_type']=='paypal')

			{

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

                        $delivery_status[] = array('vendor'=>$p,'status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('vendor'=>$p,'status'=>'pending');

                    }

                    if($this->crud_model->is_admin_in_sale($sale_id)){

                        $delivery_status[] = array('admin'=>'','status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('admin'=>'','status'=>'pending');

                    }

                    $saleCode=$data['sale_code'] = date('Ym', $data['sale_datetime']) . $sale_id;

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

					$results['Response'] = array("url"=>$url,"payment_id"=>$payment_id, "sale_code"=>$saleCode);

							echo json_encode($results,true);

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

			

		}

		else

		{

			$results['status'] = 'FAILED';

			$results['Message'] = 'Invalid Request';

			$results['Response'] = 'Invalid Request';

			echo json_encode($results,true);

			exit;

		}

	}

	function handleredirect($param1)

    {

		$payment_id=$_GET['payment_id'];

		$datas = json_decode($this->input->raw_input_stream,1);

		$paymentRequestId=$_GET['payment_request_id'];

		$response = $this->instamojo->paymentRequestPaymentStatus($paymentRequestId,$payment_id);

		$data['payment_timestamp']=date('Y-m-d H:i:s');

		if($response['payment']['status']=='Credit')

		{	

			$sale_id=$this->db->get_where('sale', array(

					'payment_id' => $paymentRequestId

				))->row()->sale_id;

			$payment_status[] = array('admin'=>'','status'=>'paid','payment_id'=>$paymentRequestId);

			$data['order_id']='MOMOD'.substr(time(),4).rand(1,10).rand(1,99).($this->db->count_all_results('sale')+1);

			$data['payment_status']=json_encode($payment_status);

			$data['payment_details']=json_encode($response);

			$data['status']='success';

			$this->db->where('sale_id', $sale_id);

            $this->db->update('sale', $data);

			$carted=json_decode($this->db->get_where('sale', array(

					'payment_id' => $paymentRequestId

				))->row()->product_details,1);

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

			$buyercashback=0;

			$buyer = $this->db->get_where('sale',array('sale_id'=>$sale_id))->row()->buyer; 			

			$buyerbalance = $this->db->get_where('user_login',array('id'=>$buyer))->row()->balance; 

			foreach ($carted as $value1)

			{

			$cashback=$this->db->get_where('product',array('product_id' => $value1['id']))->row()->cashback;

			if($cashback>0) {

				$type = $this->db->get_where('product',array('product_id'=>$value1['id']))->row()->cashback_type ; 

					if($type =='amount')

					{

						$buyercashback +=($cashback * $value1['qty']);

					}

					else if($type =='percent')

					{

						$sale_price=$this->db->get_where('product',array('product_id' => $value1['id']))->row()->sale_price;											

						$cashbackamt = ( $sale_price * $cashback / 100);

						$buyercashback +=($cashbackamt * $value1['qty']);

					}

				}

			} 

			if(isset($buyercashback) && $buyercashback>0)

			{

				$cback = $buyerbalance + $buyercashback;

				$this->db->where('id', $buyer);

				$this->db->update('user_login', array('balance'=>$cback));

				$orderid = $this->db->get_where('sale',array('sale_id'=>$sale_id))->row()->order_id; 	

				$saleprice=$this->db->get_where('product',array('product_id' => $value1['id']))->row()->sale_price;																					

				$data2['user_id'] = $buyer;

				$data2['mode'] =  'credit';

				$data2['ref_id'] = $orderid;

				$data2['servicetype'] = 8;

				$data2['amount'] = $saleprice;

				$data2['balance'] = $cback;

				$data2['description'] = 'Shopping Cashback amount Rs. '.$buyercashback.' for Ref Id :'.$orderid;

				$data2['book_status'] = 'success';

				$data2['time_format'] = time();

				$data2['m_app'] = 1;

				$this->db->insert('user_trans_log', $data2);

			}

			$this->crud_model->email_invoice($sale_id);

			$this->crud_model->sms_order($sale_id);

			redirect(base_url() . 'index.php/webservice/close', 'refresh');

		}

		else

		{

			$sale_id=$this->db->get_where('sale', array(

					'payment_id' => $paymentRequestId

				))->row()->sale_id;

			$data['payment_details']=json_encode($response);

			$data['status']='failed';

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

	function agent_cart_checkout()

    {

		  $datas = json_decode($this->input->raw_input_stream,1);

		  $action 					= 	$datas['action'];

		  $agent_id 				= 	$datas['agent_id'];

		  $categroy_id				=	$datas['cid'];

		  $sub_category_id			=	$datas['sid'];

	 	  $product_id				=	$datas['pid'];

		  $qty						=	$datas['qty'];

		  $productInfo[] = $this->db->get_where('product',array('product_id'=>$product_id))->result_array();

		  $productInfo = $productInfo[0][0];

		  if($productInfo['current_stock']==0 || $productInfo['current_stock']=='' || $productInfo['current_stock']==null)

		  {

				$results['status'] = 'FAILED';

				$results['Message'] = 'Out Of Stock';

				$results['Response'] = 'Out Of Stock';

				echo json_encode($results,true);

				exit;

     	  }

		  if($productInfo['current_stock']<$qty)

		  {

			  	$results['status'] = 'FAILED';

				$results['Message'] = 'Your Order Quatity is Greater Then Current Stock';

				$results['Response'] = 'Your Order Quatity is Greater Then Current Stock';

				echo json_encode($results,true);

				exit;

		  }

		  $agentInfo[] = $this->db->get_where('agents',array('agent_id'=>$agent_id))->result_array();

		  $agentTotalSalesCount[] = $this->db->get_where('agent_sales',array('agent_id'=>$agent_id))->result_array();

		  $ZonalInfo = $this->db->get_where('zonall',array('id'=>$agentInfo[0][0]['zonal_id']))->result_array();

		  $AreaInfo = $this->db->get_where('zonal_area',array('id'=>$agentInfo[0][0]['area_id']))->result_array();

		  $ChannelInfo = $this->db->get_where('zonal_channel',array('id'=>$agentInfo[0][0]['channel_id']))->result_array();

		  $DistributorInfo = $this->db->get_where('zonal_distributor',array('id'=>$agentInfo[0][0]['distributor_id']))->result_array();

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

		  $sub_total				=	$product_price*$qty;

		  if($product_discount_type=='percent')

		  $discount_amount			=	($sub_total*$product_discount)/100;

		  else

		  $discount_amount			=	$product_discount;

			$discount_amount=0;

		  if($product_tax_type=='percent')

		  $tax_amount				=	($sub_total*$product_tax)/100;

		  else 

		  $tax_amount				=	$product_tax;	

		$tax_amount=0;

		  $total_amount				=	$sub_total;

		  $create_date				= 	date('Y-m-d',time());

		  $cbalnc					=	$agentInfo[0][0]['account_balance'];

		  $agentsalescount 			=	count($agentTotalSalesCount[0]);

		  $agentsalescount			=	$agentsalescount+1;

		  $order_id 				=	"MOPRO-".$agent_id.'-'.$agentsalescount; 

		  $orederId1='OD'.time().rand(10,999);

		if($productInfo['retailler_cashback']!=0)

		{

			$retailler_cashback_value=$productInfo['retailler_cashback'];

			$distributor_cashback_value=$productInfo['distributor_cashback'];

			//Retailler Cashback

			if($productInfo['retailler_cashback_type']=='percent')

			$retailler_cashback	=(($product_price*$retailler_cashback_value)/100)*$qty;

			else 

			$retailler_cashback	=$retailler_cashback_value*$qty;	

			//DIstr Cashback

			if($productInfo['retailler_cashback_type']=='percent')

			$distributor_cashback	=(($product_price*$distributor_cashback_value)/100)*$qty;

			else 

			$distributor_cashback	=$distributor_cashback_value*$qty;	

			$cashback=1;	

		}

		else

		{

			$retailler_cashback=0.1;

			$distributor_cashback=0.1;

			$cashback=1;	

		}		  

		  if($payment_option=='wallet')

			{

				if($cbalnc>=$total_amount)

					{	

						$ubal = $cbalnc-$total_amount;

					}

				else 

					{

						$results['status'] = 'FAILED';

						$results['Message'] = 'Your Account Balance Very Low To Purchase This Product';

						$results['Response'] = 'Your Account Balance Very Low To Purchase This Product';

						echo json_encode($results,true);

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

		  if($agentSalesId>0)

		  {

			  $this->db->where('agent_id', $agent_id);

  	          $this->db->update('agents', array('account_balance' => $ubal));

			  $latestAgentInfo[] = $this->db->get_where('agents',array('agent_id'=>$agent_id))->result_array();

			  $latestAgentInfo=$latestAgentInfo[0][0];

			  $cbalnc= $latestAgentInfo['account_balance'];

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

			  $agent_log_datas['time'] = date('h:i:s',time());

			  $agent_log_datas['payment_date'] = date('Y-m-d',time());

			  $this->db->insert('agent_log', $agent_log_datas);	

			  $pname=explode(' ',$product_name);

			  $pname1Count=strlen($pname[0]);

			  if(isset($pname[1]))

			  $pname2Count=strlen($pname[1]);

			  if($pname1Count<18)

			  $pname1=$pname[0];

			  else

			  $pname1 =substr($pname[0], 0,22);

			  if(isset($pname2Count))

			  if($pname2Count<18)

			  $pname2=$pname[1];

			  else

			  $pname2 =substr($pname[1], 0,18);

			  $content='Order Received: We have received your order for '.$pname1.' '.$pname2.' with order id '.$order_id.' amounting to Rs.'.$total_amount.'. You can expect in next 4-5 working days. We will send you an update when your order is packed. Thanks for shopping in www.momomal.com';

			  $insSaleLog['zonal_id'] = $zonal_id;

			  $insSaleLog['area_id'] = $area_id;

			  $insSaleLog['channel_id'] = $channel_id;

			  $insSaleLog['distributor_id'] = $distributor_id;

			  $insSaleLog['agent_id'] = $agent_id;

			  $insSaleLog['mode'] = 'debit';

			  $insSaleLog['amount'] = $total_amount;

			  $insSaleLog['balance'] = $ubal;

			  $insSaleLog['description'] = 'Shopping completed Order id:'.$order_id;

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

			  $insertSMS['template_name']="MOMOMAL-RETAILLER-SHOPPING-ORDER";

			  $variables[]=$pname1.' '.$pname2;

			  $variables[]=$order_id;

			  $variables[]=$total_amount;

			  $insertSMS['variables']=implode('|||',$variables);

			  $this->db->insert('sendsms',$insertSMS);

			  $OrderInformatoin = $this->db->get_where('agent_sales',array('order_id'=>$order_id))->result_array();

			  $results['status'] = 'SUCCESS';

			  $results['Message'] = 'SUCCESS';

			  $results['Response'] = $OrderInformatoin[0];

			  echo json_encode($results,true);

			  exit;

		  }

		  else 

		  {

			  $results['status'] = 'FAILED';

			  $results['Message'] = 'Some Database Error Please Inform To Website Administrator';

			  $results['Response'] = 'Some Database Error Please Inform To Website Administrator';

			  echo json_encode($results,true);

			  exit;

		  }

    }

    function Agent_shopping_report(){

                $datas = json_decode($this->input->raw_input_stream,1);

		$agent_id 				= 	$datas['agentId'];

		if(isset($agent_id) || $agent_id!='' || $agent_id!=0) 

		{

			$agent_sales = $this->db->get_where('agent_sales',array('agent_id'=>$agent_id))->result_array();

			foreach($agent_sales as $row)

				{

						$response[]=$row;

				}

				$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$response);

		}

		else 

		{

			$value=array("status"=>"FAILED","Message"=>"FAILED", "Response"=>"Invalid Data");	

		}

		exit(json_encode($value));

	}

    /* FUNCTION: Loads Cart Checkout Page*/

    function coupon_check()

    {

        $para1 = $this->input->post('code');

        $carted = $this->cart->contents();

        if (count($carted) > 0) {

            $p = $this->session->userdata('coupon_apply')+1;

            $this->session->set_userdata('coupon_apply',$p);

            $p = $this->session->userdata('coupon_apply');

            if($p < 10){

                $c = $this->db->get_where('coupon',array('code'=>$para1));

                $coupon = $c->result_array();

                //echo $c->num_rows();

                //,'till <= '=>date('Y-m-d')

                if($c->num_rows() > 0){

                    foreach ($coupon as $row) {

                        $spec = json_decode($row['spec'],true);

                        $coupon_id = $row['coupon_id'];

                        $till = strtotime($row['till']);

                    }

                    if($till > time()){

                        $ro = $spec;

                        $type = $ro['discount_type'];

                        $value = $ro['discount_value'];

                        $set_type = $ro['set_type'];

                        $set = json_decode($ro['set']);

                        if($set_type !== 'total_amount'){

                            $dis_pro = array();

                            $set_ra = array();

                            if($set_type == 'all_products'){

                                $set_ra[] = $this->db->get('product')->result_array();

                            } else {

                                foreach ($set as $p) {

                                    if($set_type == 'product'){

                                        $set_ra[] = $this->db->get_where('product',array('product_id'=>$p))->result_array();

                                    } else {

                                        $set_ra[] = $this->db->get_where('product',array($set_type=>$p))->result_array();

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

                                    if($type == 'percent'){

                                        $discount = $base_price*$value/100;

                                    } else if($type == 'amount') {

                                        $discount = $value;

                                    }

                                    $data = array(

                                        'rowid' => $items['rowid'],

                                        'price' => $base_price-$discount,

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

                            echo 'wise:-:-:'.translate('coupon_discount_activated');

                        } else {

                            $this->cart->set_discount($value);

                            echo 'total:-:-:'.translate('coupon_discount_activated').':-:-:'.currency().$value;

                        }

                        $this->cart->set_coupon($coupon_id);

                        $this->session->set_userdata('couponer','done');

                        $this->session->set_userdata('coupon_apply',0);

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

                        $delivery_status[] = array('vendor'=>$p,'status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('vendor'=>$p,'status'=>'due');

                    }

                    if($this->crud_model->is_admin_in_sale($sale_id)){

                        $delivery_status[] = array('admin'=>'','status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('admin'=>'','status'=>'due');

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

			        $c2_user = $this->db->get_where('business_settings',array('type'=>'c2_user'))->row()->value; 

			        $c2_secret = $this->db->get_where('business_settings',array('type'=>'c2_secret'))->row()->value; 

                    $this->db->insert('sale', $data);

                    $sale_id           = $this->db->insert_id();

                    $vendors = $this->crud_model->vendors_in_sale($sale_id);

                    $delivery_status = array();

                    $payment_status = array();

                    foreach ($vendors as $p) {

                        $delivery_status[] = array('vendor'=>$p,'status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('vendor'=>$p,'status'=>'due');

                    }

                    if($this->crud_model->is_admin_in_sale($sale_id)){

                        $delivery_status[] = array('admin'=>'','status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('admin'=>'','status'=>'due');

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

					$this->twocheckout_lib->add_field('total',$this->cart->format_number(($grand_total / $exchange)));			

					$this->twocheckout_lib->add_field('x_receipt_link_url', base_url().'index.php/home/twocheckout_success');

					$this->twocheckout_lib->add_field('demo', $this->twocheckout_lib->demo);					//Either Y or N

				    $this->twocheckout_lib->submit_form();

                    // submit the fields to paypal

                }

            }else if ($this->input->post('payment_type') == 'vp') {

                if ($para1 == 'go') {

                    if($this->session->userdata('user_login') != 'yes'){

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

                        $delivery_status[] = array('vendor'=>$p,'status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('vendor'=>$p,'status'=>'due');

                    }

                    if($this->crud_model->is_admin_in_sale($sale_id)){

                        $delivery_status[] = array('admin'=>'','status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('admin'=>'','status'=>'due');

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

                    $this->vouguepay->add_field('memo', 'Order from '.$system_title);

                    $i = 1;

                    $tax = 0;

                    $shipping = 0;

                    $total = 0;

                    $this->vouguepay->add_field('total', ($grand_total/$exchange));

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

                        $delivery_status[] = array('vendor'=>$p,'status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('vendor'=>$p,'status'=>'due');

                    }

                    if($this->crud_model->is_admin_in_sale($sale_id)){

                        $delivery_status[] = array('admin'=>'','status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('admin'=>'','status'=>'due');

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

                    $this->session->set_userdata('couponer','');

                    //echo $sale_id;

                    redirect(base_url() . 'index.php/home/invoice/' . $sale_id, 'refresh');

                }

			} else if ($this->input->post('payment_type') == 'wallet') {

                if ($para1 == 'go') {

					$tmpUserId='';

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

                        $delivery_status[] = array('vendor'=>$p,'status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('vendor'=>$p,'status'=>'paid');

                    }

                    if($this->crud_model->is_admin_in_sale($sale_id)){

                        $delivery_status[] = array('admin'=>'','status'=>'pending','delivery_time'=>'');

                        $payment_status[] = array('admin'=>'','status'=>'paid');

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

                    $this->session->set_userdata('couponer','');

					$currentBalance = $_SESSION['user']['balance']-$grand_total;

					$data2['balance'] = $currentBalance;

                    $this->db->where('id', $tmpUserId);

                    $this->db->update('user', $data2);

					$_SESSION['user']['balance'] = $currentBalance;

					$tmpUserId='';

					$currentBalance='';

                    //echo $sale_id;

                    redirect(base_url() . 'index.php/home/invoice/' . $sale_id, 'refresh');

                }	

            } else if ($this->input->post('payment_type') == 'stripe') {

                if ($para1 == 'go') {

                    if(isset($_POST['stripeToken'])) {

                        require_once(APPPATH . 'libraries/stripe-php/init.php');

                        $stripe_api_key = $this->db->get_where('business_settings' , array('type' => 'stripe_secret'))->row()->value;

                        \Stripe\Stripe::setApiKey($stripe_api_key); //system payment settings

                        $customer_email = $this->db->get_where('user' , array('id' => $this->session->userdata('user_id')))->row()->email;

                        $customer = \Stripe\Customer::create(array(

                            'email' => $customer_email, // customer email id

                            'card'  => $_POST['stripeToken']

                        ));

                        $charge = \Stripe\Charge::create(array(

                            'customer'  => $customer->id,

                            'amount'    => ceil($grand_total*100/$exchange),

                            'currency'  => 'USD'

                        ));

                        if($charge->paid == true){

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

                            $data['payment_details']   = "Customer Info: \n".json_encode($customer,true)."\n \n Charge Info: \n".json_encode($charge,true);

                            $data['grand_total']       = $grand_total;

                            $data['sale_datetime']     = time();

                            $data['delivary_datetime'] = '';

                            $this->db->insert('sale', $data);

                            $sale_id           = $this->db->insert_id();

                            $vendors = $this->crud_model->vendors_in_sale($sale_id);

                            $delivery_status = array();

                            $payment_status = array();

                            foreach ($vendors as $p) {

                                $delivery_status[] = array('vendor'=>$p,'status'=>'pending','delivery_time'=>'');

                                $payment_status[] = array('vendor'=>$p,'status'=>'paid');

                            }

                            if($this->crud_model->is_admin_in_sale($sale_id)){

                                $delivery_status[] = array('admin'=>'','status'=>'pending','delivery_time'=>'');

                                $payment_status[] = array('admin'=>'','status'=>'paid');

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

                            $this->session->set_userdata('couponer','');

                            redirect(base_url() . 'index.php/home/invoice/' . $sale_id, 'refresh');

                        } else {

                            $this->session->set_flashdata('alert', 'unsuccessful_stripe');

                            redirect(base_url() . 'index.php/home/cart_checkout/', 'refresh');

                        }

                    } else{

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

                    $merchant_id=$_POST['Merchant_Id'];  // Merchant id(also User_Id) 

                    $amount=$_POST['Amount'];            // your script should substitute the amount here in the quotes provided here

                    $order_id=$_POST['Order_Id'];        //your script should substitute the order description here in the quotes provided here

                    $url=$_POST['Redirect_Url'];         //your redirect URL where your customer will be redirected after authorisation from CCAvenue

                    $billing_cust_name=$_POST['billing_cust_name'];

                    $billing_cust_address=$_POST['billing_cust_address'];

                    $billing_cust_country=$_POST['billing_cust_country'];

                    $billing_cust_state=$_POST['billing_cust_state'];

                    $billing_city=$_POST['billing_city'];

                    $billing_zip=$_POST['billing_zip'];

                    $billing_cust_tel=$_POST['billing_cust_tel'];

                    $billing_cust_email=$_POST['billing_cust_email'];

                    $delivery_cust_name=$_POST['delivery_cust_name'];

                    $delivery_cust_address=$_POST['delivery_cust_address'];

                    $delivery_cust_country=$_POST['delivery_cust_country'];

                    $delivery_cust_state=$_POST['delivery_cust_state'];

                    $delivery_city=$_POST['delivery_city'];

                    $delivery_zip=$_POST['delivery_zip'];

                    $delivery_cust_tel=$_POST['delivery_cust_tel'];

                    $delivery_cust_notes=$_POST['delivery_cust_notes'];

                    $working_key='CF939418BB6847E03D0D4DEAD5CBC19B';    //Put in the 32 bit alphanumeric key in the quotes provided here.

                    $checksum=getchecksum($merchant_id,$amount,$order_id,$url,$working_key); // Method to generate checksum

                    $merchant_data= 'Merchant_Id='.$merchant_id.'&Amount='.$amount.'&Order_Id='.$order_id.'&Redirect_Url='.$url.'&billing_cust_name='.$billing_cust_name.'&billing_cust_address='.$billing_cust_address.'&billing_cust_country='.$billing_cust_country.'&billing_cust_state='.$billing_cust_state.'&billing_cust_city='.$billing_city.'&billing_zip_code='.$billing_zip.'&billing_cust_tel='.$billing_cust_tel.'&billing_cust_email='.$billing_cust_email.'&delivery_cust_name='.$delivery_cust_name.'&delivery_cust_address='.$delivery_cust_address.'&delivery_cust_country='.$delivery_cust_country.'&delivery_cust_state='.$delivery_cust_state.'&delivery_cust_city='.$delivery_city.'&delivery_zip_code='.$delivery_zip.'&delivery_cust_tel='.$delivery_cust_tel.'&billing_cust_notes='.$delivery_cust_notes.'&Checksum='.$checksum  ;

                    $encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.

                }

            }else if ($this->input->post('payment_type') == 'skrill') {

				if ($para1 == 'go') {

					$config ['detail1_text'] = ''; //Text about your services

					$config ['amount'] = '200'; // get post values

					$this->load->library('skrill', $config);

					$this->skrill->pay();

				}

			}

        }

		else {

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

                $payment_status[] = array('vendor'=>$p,'status'=>'paid');

            }

            if($this->crud_model->is_admin_in_sale($sale_id)){

                $payment_status[] = array('admin'=>'','status'=>'paid');

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

        $this->session->set_userdata('couponer','');

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

        $this->session->set_userdata('couponer','');

        $this->crud_model->email_invoice($sale_id);

		redirect(base_url() . 'index.php/webservice/close', 'refresh');

        //$this->session->set_userdata('sale_id', '');

       // redirect(base_url() . 'index.php/home/invoice/' . $sale_id, 'refresh');

    }

    function twocheckout_success()

    {

		//$this->twocheckout_lib->set_acct_info('532001', 'tango', 'Y');

        $c2_user = $this->db->get_where('business_settings',array('type'=>'c2_user'))->row()->value; 

        $c2_secret = $this->db->get_where('business_settings',array('type'=>'c2_secret'))->row()->value; 

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

                $payment_status[] = array('vendor'=>$p,'status'=>'paid');

            }

            if($this->crud_model->is_admin_in_sale($sale_id)){

                $payment_status[] = array('admin'=>'','status'=>'paid');

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

	        $this->session->set_userdata('couponer','');

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

        if ($res['total'] !== 0 && $res['status'] == 'Approved' && $res['merchant_id'] == $merchant_id){

            $data['payment_details']   = json_encode($res);

            $data['payment_timestamp'] = strtotime(date("m/d/Y"));

            $data['payment_type']      = 'vouguepay';

            $vendors = $this->crud_model->vendors_in_sale($sale_id);

            $payment_status = array();

            foreach ($vendors as $p) {

                $payment_status[] = array('vendor'=>$p,'status'=>'paid');

            }

            if($this->crud_model->is_admin_in_sale($sale_id)){

                $payment_status[] = array('admin'=>'','status'=>'paid');

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

            $this->crud_model->decrease_quantity($value['id'], $value['qty'],$size);

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

        $this->session->set_userdata('couponer','');

        $this->crud_model->email_invoice($sale_id);

        $this->session->set_userdata('sale_id', '');

        redirect(base_url() . 'index.php/home/invoice/' . $sale_id, 'refresh');

    }

    /* FUNCTION: Concerning wishlist*/

    function wishlist($para1 = "", $para2 = "",$para3 = "")

    {

        if ($para1 == 'add') {

            $this->crud_model->add_wish_webservice($para2,$para3);

			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>"Wishlist added successfully");

			exit(json_encode($value)); 

        } else if ($para1 == 'remove') {

            $this->crud_model->remove_wish_webservice($para2,$para3);

			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>"Wishlist removed successfully");

			exit(json_encode($value)); 

        } else if ($para1 == 'list') {

            $wishlist['wishlistProducts']=json_decode($this->crud_model->wished_num_webservice($para2));

			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$wishlist);

			exit(json_encode($value)); 

        }

		else if ($para1 == 'count') {

            $wishlist['WishlistCount']=count(json_decode($this->crud_model->wished_num_webservice($para2)));

			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$wishlist);

			exit(json_encode($value)); 

        }  

    }

    /* FUNCTION: Loads Contact Page */

    function blog($para1 = "")

    {

        $page_data['category']= $para1;

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

        if($category_id !== '' && $category_id !== 'all'){

            $this->db->where('blog_category',$category_id);

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

        if($category_id !== '' && $category_id !== 'all'){

            $this->db->where('blog_category',$category_id);

        }

        $page_data['blogs'] = $this->db->get('blog', $config['per_page'], $para1)->result_array();

        if($category_id !== '' && $category_id !== 'all'){

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

        $page_data['blog']  = $this->db->get_where('blog',array('blog_id'=>$para1))->result_array();

		$page_data['categories']  = $this->db->get('blog_category')->result_array();	

        $this->db->where('blog_id', $para1);

        $this->db->update('blog', array(

            'number_of_view' => 'number_of_view' + 1

        ));

        $page_data['page_name']  = 'blog/blog_view';

		$page_data['asset_page']  = 'blog_view';

        $page_data['page_title']  = $this->db->get_where('blog',array('blog_id'=>$para1))->row()->title;

        $this->load->view('front/index.php', $page_data);   

    }

	function others_product($para1 = ""){

		$page_data['product_type']= $para1;

        $page_data['page_name']   = 'others_list';

        $page_data['asset_page']  = 'product_list_other';

        $page_data['page_title']  = translate($para1);

        $this->load->view('front/index', $page_data);

	}

	function product_by_type($para1 = "")
	{
		$page_data['product_type']= $para1;
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

    function invoice()

    {

		$datas = json_decode($this->input->raw_input_stream,1);

		$sale_code=$datas['sale_code'];

		$paymentDetails=$this->db->get_where('sale',array('sale_code'=>$sale_code,'status'=>'success'))->result_array();

		if(isset($paymentDetails[0]))

		{

			$paymentDetails=$paymentDetails[0];

			$product_details=json_decode($paymentDetails['product_details'],1);

			foreach($product_details as $p)

			{

				$pDte[]=$p;

			}

			$product_details=$pDte;

			$grand_total=$paymentDetails['grand_total'];

			$shipping_address=json_decode($paymentDetails['shipping_address'],1);

			$payment_type=$paymentDetails['payment_type'];

			$payment_status=json_decode($paymentDetails['payment_status'],1);

			$delivery_status=json_decode($paymentDetails['delivery_status'],1);

			$sale_id=$paymentDetails['sale_id'];

			$order_id=$paymentDetails['order_id'];

			$sale_code=$paymentDetails['sale_code'];

			$results['status'] = 'SUCCESS';

			$results['Message'] = 'Order Completed-'.$order_id;

			$results['Response'] = array("sale_code"=>$sale_code,"order_id"=>$order_id,"product_details"=>$product_details,"total_amount"=>$grand_total,"shipping_address"=>$shipping_address,"payment_status"=>$payment_status,"delivery_status"=>$delivery_status,"payment_type"=>$payment_type,"create_date"=>date('Y-m-d h:i:s'));

			echo json_encode($results,true);

			exit;

		}

		else

		{

			$results['status'] = 'FAILED';

			$results['Message'] = 'Invalid Order';

			echo json_encode($results,true);

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

        $return = '' . '<input type="text" id="rangelvl" value="" name="range" />' . '<script>' . '	$("#rangelvl").ionRangeSlider({' . '		hide_min_max: false,' . '		keyboard: true,' . '		min:' . $min . ',' . '		max:' . $max . ',' . '		from:' . $start . ',' . '		to:' . $end . ',' . '		type: "double",' . '		step: 1,' . '		prefix: "'.currency().'",' . '		grid: true,' . '		onFinish: function (data) {' . "			filter('click','none','none','0');" . '		}' . '	});' . '</script>';

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

        } else if($para1 == 'text_db'){

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
		$otherurls = array(base_url().'index.php/home/contact/',base_url().'index.php/home/legal/terms_conditions',base_url().'index.php/home/legal/privacy_policy');
        $producturls = array();
        $products = $this->db->get_where('product',array('status'=>'ok'))->result_array();
        foreach ($products as $row) 
		{
            $producturls[] = $this->crud_model->product_link($row['product_id']);
        }
        $vendorurls = array();
        $vendors = $this->db->get_where('vendor',array('status'=>'approved'))->result_array();
		
        foreach ($vendors as $row) 
		{
            $vendorurls[] = $this->crud_model->vendor_link($row['vendor_id']);
        }
        $page_data['otherurls']  = $otherurls;
        $page_data['producturls']  = $producturls;
        $page_data['vendorurls']  = $vendorurls;
	
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);
		exit(json_encode($value));	
	}
	
	
	
	
	 /* FUNCTION: Loads Contact Page */

    function blog_by_id($para1 = "")
    {
        $blog['blog_by_id'] = $this->db->get_where('blog',array('blog_id'=>$para1))->result_array();
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$blog);
		exit(json_encode($value));

    }

    /* FUNCTION: Loads Contact Page */

    function blog_by_cat($para1 = "")
    {
        $blog = array();
        $blog['blog_by_cat'] = $this->db->get_where('blog',array('blog_category'=>$para1))->result_array();

		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$blog);
		exit(json_encode($value));
    }
	
	function siteInformation($oid)
	{	
		$siteInformation = array();
		$siteInformation['siteInformation'] = $this->db->get_where('general_settings')->result_array();
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$siteInformation);
		exit(json_encode($value));
	}
	
	function blog_cat()
    {
        $blog['blog_cat'] = $this->db->get_where('blog_category')->result_array();
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$blog);
		exit(json_encode($value));
    }
	
	function user_subscribe()
	{	
		$user_subscribe = array();
		$user_subscribe['user_subscribe'] = $this->db->get_where('subscribe')->result_array();
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$user_subscribe);
		exit(json_encode($value));
	}
	function social_media()
	{	
		$gr_social_links = array();
		$gr_social_links['social_links'] = $this->db->get_where('social_links')->result_array();
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$gr_social_links);
		exit(json_encode($value));
	}
	
	function business_settings()
	{
		$business_settings = array();
		$business_settings['business_settings'] = $this->db->get_where('business_settings')->result_array();
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$business_settings);
		exit(json_encode($value));
	}

}

/* End of file home.php */

/* Location: ./application/controllers/home.php */