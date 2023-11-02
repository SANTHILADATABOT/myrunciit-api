<?php
//header('Access-Control-Allow-Origin: *'); 
@session_start();

@ob_start();

if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Webserviceagent extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('paypal');

        $this->load->library('twoCheckout_Lib');

        $this->load->library('vouguepay');

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
    function saless($para1 = '', $para2 = '')
    {
       // echo "a"; exit;
        $this->db->order_by('sale_id', 'desc');
			 $this->db->where('cancel_status', '0');
            $rs = $this->db->get('sale')->result_array();
            foreach($rs as $row){
            if($this->crud_model->is_sale_of_vendor($row['sale_id'],$para1)){
                $pg['sale_code']=$row['sale_code'];
                $pg['order_id']=$row['order_id'];
				$pg['grand_total']=$row['grand_total'];
                $pg['username']=$this->crud_model->get_type_name_by_id('user',$row['buyer'],'username'); 
                $pg['sale_datetime']=date('d-m-Y',$row['sale_datetime']);
				//
                $delivery_status = json_decode($row['delivery_status'],true); 
                    foreach ($delivery_status as $dev) {
						//print_r($dev); exit;
                    if(isset($dev['vendor'])){
                        if($dev['vendor'] == $para1){
                            
                           $pg['delivery_status']= $dev['status'];
                        }
                    }
                    }
        
       
        
         $payment_status = json_decode($row['payment_status'],true); 
                    foreach ($payment_status as $dev1) {
						if(isset($dev1['vendor'])){
                        if($dev1['vendor'] == $para1){
                            $pg['payment_status']= $dev1['status'];
                            
                        }}}
						$data[]=$pg;
    }
	 
	 //exit;
			}
			// print_r($pg); exit;
   
    
    	$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$data);
		
			exit(json_encode($value));
            }
			
			function categorys($para1 = '', $para2 = '', $para3 = '')
    {
		$this->db->order_by('category_id', 'desc');
		 $page_data['category'] = $this->db->get('category')->result_array();
		// echo $this->db->last_query(); exit;
			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);
		
			exit(json_encode($value));
	}


function subcategorys($para1 = '', $para2 = '', $para3 = '')
    {
		$this->db->order_by('sub_category_id', 'desc');
			 $this->db->where('category', $para1);
		 $page_data['subcategory'] = $this->db->get('sub_category')->result_array();
		// echo $this->db->last_query(); exit;
			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);
		
			exit(json_encode($value));
	}
	
	function brands($para1 = '', $para2 = '', $para3 = '')
    {
		$this->db->order_by('sub_category_id', 'desc');
			 $this->db->where('category', $para1);
		 $subcategory = $this->db->get('sub_category')->result_array();
		$brands=json_decode($this->crud_model->get_type_name_by_id('sub_category',$para2,'brand'),true);
		//print_r($brands);
		foreach($brands as $brds){
			$this->db->where('brand_id', $brds);
		 $getbrd = $this->db->get('brand')->result_array();
		 foreach($getbrd as $getbrds){
			 $gt['brand_id']=$getbrds['brand_id'];
			 $gt['name']=$getbrds['name'];
		// echo $this->db->last_query();
			}
			$data[]=$gt;
		}
		// echo $this->db->last_query(); exit;
			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$data);
		
			exit(json_encode($value));
	}
			
			
			function products($para1 = '', $para2 = '', $para3 = '')
    {
       
        if ($para1 == 'do_add') {
			
				$datas = json_decode($this->input->raw_input_stream,1);
        //   print_r($datas);
             //$data['image']              = $datas['image'];
            $data['sku_id']              = $datas['barcode'];
			$data['title']              = $datas['title'];
            $data['category']           = $datas['category'];
            $data['description']        = $datas['description'];
            $data['sub_category']       = $datas['sub_category'];
            $data['sale_price']         = $datas['sale_price'];
            $data['purchase_price']     = $datas['purchase_price'];
			
            $data['status']             = '0';
            $data['rating_user']        = '[]';
            $data['tax']                = $datas['tax'];
            $data['discount']           = $datas['discount'];
            $data['discount_type']      = $datas['discount_type'];
            $data['tax_type']           = $datas['tax_type'];
            $data['shipping_cost']      = $datas['shipping_cost'];
           
            $data['brand']              = $datas['brand'];
            $data['unit']               = $datas['unit'];
			$data['vendor_id']               = $datas['vendor_id'];
           
			$data['added_by']           = json_encode(array('type'=>'vendor','id'=>$data['vendor_id']));
			
            
			if($this->crud_model->can_add_product($data['vendor_id'])){
                $this->db->insert('product', $data);
				//echo $this->db->last_query(); exit;
					$pro_id           = $this->db->insert_id();
					$results['status'] = 'SUCCESS';
					$results['product_id'] = $pro_id;

					$results['Message'] = "Product Added successfully";

					echo json_encode($results,true);

					exit;
				//$id = $this->db->insert_id();
				//$this->benchmark->mark_time();
				//$this->crud_model->file_up("images", "product", $id, 'multi');
            }
        }
		
		 else if ($para1 == "update") {
          $datas = json_decode($this->input->raw_input_stream,1);
           $data['title']              = $datas['title'];
            $data['category']           = $datas['category'];
            $data['description']        = $datas['description'];
            $data['sub_category']       = $datas['sub_category'];
            $data['sale_price']         = $datas['sale_price'];
            $data['purchase_price']     = $datas['purchase_price'];
			
          //  $data['status']             = '0';
            $data['rating_user']        = '[]';
            $data['tax']                = $datas['tax'];
            $data['discount']           = $datas['discount'];
            $data['discount_type']      = $datas['discount_type'];
            $data['tax_type']           = $datas['tax_type'];
            $data['shipping_cost']      = $datas['shipping_cost'];
           
            $data['brand']              = $datas['brand'];
            $data['unit']               = $datas['unit'];
			//$data['vendor_id']               = $datas['vendor_id'];
           // $this->crud_model->file_up("images", "product", $para2, 'multi');
            
            $this->db->where('product_id', $datas['product_id']);
            $this->db->update('product', $data);
			//echo $this->db->last_query();
			$results['status'] = 'SUCCESS';
			$results['product_id'] = $datas['product_id'];
			$results['Message'] = "Product Updated successfully";

			echo json_encode($results,true);

			exit;
		//	$this->crud_model->set_category_data(0);
         //   recache();
        } else if ($para1 == 'edit') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/product_edit', $page_data);
        } else if ($para1 == 'view') {
         
			//$this->db->order_by('product_id', 'desc');
			//$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$para2)));
			//$this->db->where('download=',NULL);
            $all_product = $this->db->get_where('product', array(
                'product_id' => $para2))->result_array();
			foreach($all_product as $all_product1){
				$data['title']              = $all_product1['title'];
            $category           = $all_product1['category'];
			$data['category'] = $this->db->get_where('category', array('category_id' => $category))->row()->category_name;
            $data['description']        = $all_product1['description'];
            $sub_category       = $all_product1['sub_category'];
			$data['sub_category'] = $this->db->get_where('sub_category', array('sub_category_id' => $sub_category))->row()->sub_category_name;
            $data['sale_price']         = $all_product1['sale_price'];
            $data['purchase_price']     = $all_product1['purchase_price'];
			
           
            $data['tax']                = $all_product1['tax'];
            $data['discount']           = $all_product1['discount'];
            $data['discount_type']      = $all_product1['discount_type'];
            $data['tax_type']           = $all_product1['tax_type'];
            $data['shipping_cost']      = $all_product1['shipping_cost'];
           
            $brand              = $all_product1['brand'];
			$data['brand'] = $this->db->get_where('brand', array('brand_id' => $brand))->row()->name;
            $data['unit']               = $all_product1['unit'];
			$data['img']=$this->crud_model->file_view('product',$all_product1['product_id'],'','','thumb','src','multi','one');
			$page_data[]=$data;	
			}
			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);
		
			exit(json_encode($value));
           // $this->load->view('back/vendor/product_view', $page_data);
        } 
		
		
		elseif ($para1 == 'delete') {
            $this->crud_model->file_dlt('product', $para2, '.jpg', 'multi');
            $this->db->where('product_id', $para2);
            $this->db->delete('product');
			$results['status'] = 'SUCCESS';

							$results['Message'] = "Product Deleted successfully";

							echo json_encode($results,true);

							exit;
        } elseif ($para1 == 'list') {
            $this->db->order_by('product_id', 'desc');
			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$para2)));
			$this->db->where('download=',NULL);
            $all_product = $this->db->get('product')->result_array();
			foreach($all_product as $all_product1){
			$data['product_id']              = $all_product1['product_id'];
			$data['title']              = $all_product1['title'];
            $category=$data['category_id']           = $all_product1['category'];
			$data['category'] = $this->db->get_where('category', array('category_id' => $category))->row()->category_name;
            $data['description']        = $all_product1['description'];
            $sub_category       = $data['sub_category_id'] =$all_product1['sub_category'];
			$data['sub_category'] = $this->db->get_where('sub_category', array('sub_category_id' => $sub_category))->row()->sub_category_name;
            $data['sale_price']         = $all_product1['sale_price'];
            $data['purchase_price']     = $all_product1['purchase_price'];
			
           
            $data['tax']                = $all_product1['tax'];
            $data['discount']           = $all_product1['discount'];
            $data['discount_type']      = $all_product1['discount_type'];
            $data['tax_type']           = $all_product1['tax_type'];
            $data['shipping_cost']      = $all_product1['shipping_cost'];
			$data['current_stock']      = $all_product1['current_stock'];
           
            $brand              = $data['brand_id'] =$all_product1['brand'];
			$data['brand'] = $this->db->get_where('brand', array('brand_id' => $brand))->row()->name;
            $data['unit']               = $all_product1['unit'];
			$data['img']=$this->crud_model->file_view('product',$all_product1['product_id'],'','','thumb','src','multi','one');
			$page_data[]=$data;	
			}
			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);
		
			exit(json_encode($value));
          //  $this->load->view('back/vendor/product_list', $page_data);
        }
		
		elseif ($para1 == 'do_addstock') {
			 $datas = json_decode($this->input->raw_input_stream,1);
			 
            $data['type']         = 'add';
            $data['category']     = $datas['category'];
            $data['sub_category'] = $datas['sub_category'];
            $data['product']      = $datas['product'];
            $data['quantity']     = $datas['quantity'];
            $data['rate']         = $datas['price'];
            $data['total']        = $data['quantity']*$data['rate'];
            $data['reason_note']  = $datas['reason_note'];
            $data['added_by']     = json_encode(array('type'=>'vendor','id'=>$datas['vendor_id']));
            $data['datetime']     = time();
            $this->db->insert('stock', $data);
            $prev_quantity          = $this->crud_model->get_type_name_by_id('product', $data['product'], 'current_stock');
            $data1['current_stock'] = $prev_quantity + $data['quantity'];
            $this->db->where('product_id', $data['product']);
            $this->db->update('product', $data1);
           	$results['status'] = 'SUCCESS';
					//$results['product_id'] = $pro_id;

					$results['Message'] = "Stock Added successfully";

					echo json_encode($results,true);

					exit;
        }
		else if ($para1 == 'do_destroy') {
			$datas = json_decode($this->input->raw_input_stream,1);
            $data['type']         = 'destroy';
            $data['category']     = $datas['category'];
            $data['sub_category'] = $datas['sub_category'];
            $data['product']      = $datas['product'];
            $data['quantity']     = $datas['quantity'];
            $data['rate']         = $datas['price'];
            $data['total']        = '0';
            $data['reason_note']  = $datas['reason_note'];
            $data['added_by']     = json_encode(array('type'=>'vendor','id'=>$datas['vendor_id']));
            $data['datetime']     = time();
            $this->db->insert('stock', $data);
            $prev_quantity = $this->crud_model->get_type_name_by_id('product', $data['product'], 'current_stock');
            $current       = $prev_quantity - $data['quantity'];
            if ($current <= 0) {
                $current = 0;
            }
            $data1['current_stock'] = $current;
            $this->db->where('product_id', $data['product']);
            $this->db->update('product', $data1);
			$results['status'] = 'SUCCESS';
					//$results['product_id'] = $pro_id;

					$results['Message'] = "Stock Destroy successfully";

					echo json_encode($results,true);

					exit;
           // recache();
        }
		elseif ($para1 == 'prolist') {
            $this->db->order_by('product_id', 'desc');
			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$para2)));
			$this->db->where('status','ok');
			$this->db->where('download=',NULL);
            $all_product = count($this->db->get('product')->result_array());
			
			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$all_product);
		
			exit(json_encode($value));
          //  $this->load->view('back/vendor/product_list', $page_data);
        }
		
		 elseif ($para1 == 'list_data') {
            $limit      = $this->input->get('limit');
            $search     = $this->input->get('search');
            $order      = $this->input->get('order');
            $offset     = $this->input->get('offset');
            $sort       = $this->input->get('sort');
            if($search){
                $this->db->like('title', $search, 'both');
            }
			$this->db->where('download=',NULL);
			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $total      = $this->db->get('product')->num_rows();
            $this->db->limit($limit);
			if($sort == ''){
				$sort = 'product_id';
				$order = 'DESC';
			}
            $this->db->order_by($sort,$order);
            if($search){
                $this->db->like('title', $search, 'both');
            }
			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
			$this->db->where('download=',NULL);
            $products   = $this->db->get('product', $limit, $offset)->result_array();
            $data       = array();
            foreach ($products as $row) {

                $res    = array(
                             'image' => '',
                             'title' => '',
                             'current_stock' => '',
                             'publish' => '',
                             'options' => ''
                          );

                $res['image']  = '<img class="img-sm" style="height:auto !important; border:1px solid #ddd;padding:2px; border-radius:2px !important;" src="'.$this->crud_model->file_view('product',$row['product_id'],'','','thumb','src','multi','one').'"  />';
                $res['title']  = $row['title'];
                if($row['status'] == 'ok'){
                    $res['publish']  = '<input id="pub_'.$row['product_id'].'" class="sw1" type="checkbox" data-id="'.$row['product_id'].'" checked />';
                } else {
                    $res['publish']  = '<input id="pub_'.$row['product_id'].'" class="sw1" type="checkbox" data-id="'.$row['product_id'].'" />';
                }
                if($row['current_stock'] > 0){ 
                    $res['current_stock']  = $row['current_stock'].$row['unit'].'(s)';                     
                } else {
                    $res['current_stock']  = '<span class="label label-danger">'.translate('out_of_stock').'</span>';
                }

				if($row['bidding']==1)
					{
						$product_bidd="<a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('bidd','".translate('bidding')."','".translate('successfully_viewed!')."','product_view','".$row['product_id']."');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                    ".translate('bidd')."
                            </a>";
					}
					else
					{
						$product_bidd='';
					}



                //add html for action
                $res['options'] = $product_bidd."  <a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('view','".translate('view_product')."','".translate('successfully_viewed!')."','product_view','".$row['product_id']."');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                    ".translate('view')."
                            </a>
                            <a class=\"btn btn-purple btn-xs btn-labeled fa fa-tag\" data-toggle=\"tooltip\"
                                onclick=\"ajax_modal('add_discount','".translate('view_discount')."','".translate('viewing_discount!')."','add_discount','".$row['product_id']."')\" data-original-title=\"Edit\" data-container=\"body\">
                                    ".translate('discount')."
                            </a>
                            <a class=\"btn btn-mint btn-xs btn-labeled fa fa-plus-square\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_modal('add_stock','".translate('add_product_quantity')."','".translate('quantity_added!')."','stock_add','".$row['product_id']."')\" data-original-title=\"Edit\" data-container=\"body\">
                                    ".translate('stock')."
                            </a>
                            <a class=\"btn btn-dark btn-xs btn-labeled fa fa-minus-square\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_modal('destroy_stock','".translate('reduce_product_quantity')."','".translate('quantity_reduced!')."','destroy_stock','".$row['product_id']."')\" data-original-title=\"Edit\" data-container=\"body\">
                                    ".translate('destroy')."
                            </a>
                            
                            <a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('edit','".translate('edit_product')."','".translate('successfully_edited!')."','product_edit','".$row['product_id']."');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                                    ".translate('edit')."
                            </a>
                            
                            <a onclick=\"delete_confirm('".$row['product_id']."','".translate('really_want_to_delete_this?')."')\" 
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    ".translate('delete')."
                            </a>";
                $data[] = $res;
            }
            $result = array(
                             'total' => $total,
                             'rows' => $data
                           );

            echo json_encode($result);

        } else if ($para1 == 'dlt_img') {
            $a = explode('_', $para2);
            $this->crud_model->file_dlt('product', $a[0], '.jpg', 'multi', $a[1]);
            recache();
        } elseif ($para1 == 'sub_by_cat') {
            echo $this->crud_model->select_html('sub_category', 'sub_category', 'sub_category_name', 'add', 'demo-chosen-select required', '', 'category', $para2, 'get_brnd');
        } elseif ($para1 == 'brand_by_sub') {
			$brands=json_decode($this->crud_model->get_type_name_by_id('sub_category',$para2,'brand'),true);
            echo $this->crud_model->select_html('brand', 'brand', 'name', 'add', 'demo-chosen-select required', '', 'brand_id', $brands, '', 'multi');
        } elseif ($para1 == 'product_by_sub') {
            echo $this->crud_model->select_html('product', 'product', 'title', 'add', 'demo-chosen-select required', '', 'sub_category', $para2, 'get_pro_res');
        } elseif ($para1 == 'pur_by_pro') {
            echo $this->crud_model->get_type_name_by_id('product', $para2, 'purchase_price');
        } elseif ($para1 == 'add') {
            if($this->crud_model->can_add_product($this->session->userdata('vendor_id'))){
                $this->load->view('back/vendor/product_add');
            } else {
                $this->load->view('back/vendor/product_limit');
            }
        } elseif ($para1 == 'add_stock') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/product_stock_add', $data);
        } elseif ($para1 == 'destroy_stock') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/product_stock_destroy', $data);
        } elseif ($para1 == 'stock_report') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/product_stock_report', $data);
        } elseif ($para1 == 'sale_report') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/product_sale_report', $data);
        } elseif ($para1 == 'add_discount') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/product_add_discount', $data);
        } elseif ($para1 == 'product_featured_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['featured'] = 'ok';
            } else {
                $data['featured'] = '0';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'product_deal_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['deal'] = 'ok';
            } else {
                $data['deal'] = '0';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'product_publish_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
			$this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'add_discount_set') {
            $product               = $this->input->post('product');
            $data['discount']      = $this->input->post('discount');
            $data['discount_type'] = $this->input->post('discount_type');
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
			$this->crud_model->set_category_data(0);
            recache();
        } else {
            $page_data['page_name']   = "product";
			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    
     function manage_vendor($para1 = "",$para2 = "")
    {
        
        if ($para1 == 'update_password') {
            $datas = json_decode($this->input->raw_input_stream,1);
           // print_r($datas);
           $user_data['cpassword'] = $datas['cpassword'];
            $user_data['password'] = $datas['password1'];
            $user_data['password2'] = $datas['password2'];
            $account_data          = $this->db->get_where('vendor', array(
                'vendor_id' => $datas['vendor_id']
            ))->result_array();
           //  echo $this->db->last_query();
            foreach ($account_data as $row) {
               //  print_r($row); exit; 
                if (sha1($user_data['cpassword']) == $row['password']) {
                  //  echo "a";
                    if ($user_data['password'] == $user_data['password2']) {
                        $data['password'] = sha1($user_data['password']);
                        $this->db->where('vendor_id', $datas['vendor_id']);
                        $this->db->update('vendor', $data);
                     //   echo $this->db->last_query();
                       // echo 'updated';
                       	$results['status'] = 'SUCCESS';

						$results['Message'] = "Password Updated successfully";

							echo json_encode($results,true);

							exit;
                    }
                    
                    else{
                        
                        	$results['status'] = 'FAILED';

						$results['Message'] = "Password and confirm password not match";

							echo json_encode($results,true);

							exit;
                        
                    }
                } 
                else{
                
                	$results['status'] = 'FAILED';

						$results['Message'] = "Incorrect old password";

							echo json_encode($results,true);

							exit;
                }
                
            }
        } else if ($para1 == 'update_profile') {
            
             $datas = json_decode($this->input->raw_input_stream,1);
         
            
           //  $datas = json_decode($this->input->raw_input_stream,1);
            $data['name']              = $datas['name'];
            $data['email']           = $datas['email'];
            $data['address1']        = $datas['address1'];
            $data['address2']       = $datas['address2'];
            $data['company']         = $datas['company'];
            $data['display_name']     = $datas['display_name'];
			$data['city']                = $datas['city'];
            $data['state']           = $datas['state'];
            $data['country']      = $datas['country'];
            $data['zip']           = $datas['zip'];
            $data['details']      = $datas['details'];
            $data['phone']              = $datas['phone'];
            $data['store_city']               = $datas['city'];
            $data['store_street']               = $datas['store_street'];
            $data['store_district']               = $datas['store_district'];
            $data['store_country']               = $datas['store_country'];
            $data['store_email']               = $datas['store_email'];
            $data['store_phone']               = $datas['store_phone'];
			//$data['vendor_id']               = $datas['vendor_id'];
           // $this->crud_model->file_up("images", "product", $para2, 'multi');
            
            $this->db->where('vendor_id', $datas['vendor_id']);
            $this->db->update('vendor', $data);
			//echo $this->db->last_query();
			$results['status'] = 'SUCCESS';

							$results['Message'] = "Profile Updated successfully";

							echo json_encode($results,true);

							exit;
            
            
        } else {
            $page_data['page_name'] = "manage_vendor";
            $this->load->view('back/index', $page_data);
        }
    }
    
    
    
    function coupon($para1 = '', $para2 = '', $para3 = '')
    {
        
        if ($para1 == 'do_add') {
            $datas = json_decode($this->input->raw_input_stream,1);
            $data['title'] = $datas['title'];
            $data['code'] = $datas['code'];
            $data['till'] = $datas['till'];
            $data['status'] = 'ok';
            $data['added_by'] = json_encode(array('type'=>'vendor','id'=>$datas['vendor_id']));
            $data['spec'] = json_encode(array(
                                'set_type'=>'product',
                                'set'=>json_encode($datas['product']),
                                'discount_type'=>$datas['discount_type'],
                                'discount_value'=>$datas['discount_value'],
                                'shipping_free'=>'null'
                            ));
            $this->db->insert('coupon', $data);
            
            	$results['status'] = 'SUCCESS';

							$results['Message'] = "Coupon Code Created successfully";

							echo json_encode($results,true);

							exit;
            
        } else if ($para1 == 'view') {
            $coupon_data = $this->db->get_where('coupon', array(
                'coupon_id' => $para2
            ))->result_array();
		//	print_r($coupon_data);
			foreach($coupon_data as $datas){
				 $data['title'] = $datas['title'];
            $data['code'] = $datas['code'];
            $data['till'] = $datas['till'];
			 $spec = json_decode($datas['spec'],true);
			// print_r($spec);
			  $e_match = json_decode($spec['set']);
                                if ($e_match == NULL) {
                                    $e_match = array();
                                }
				$products = $this->db->get_where('product',array('added_by'=>json_encode(array('type'=>'vendor','id'=>$para3))))->result_array();
                                foreach ($products as $row1) {
                                    if($this->crud_model->is_publishable($row1['product_id'])){
										$data['product_name'] =$row1['title'];
										
									}
								}
				$data['discount_type'] = $spec['discount_type'];
            $data['discount_value'] = $spec['discount_value'];
			//print_r($data);
				$page_data[]=$data;	
			}
			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);
		
			exit(json_encode($value));
				
			
            //$this->load->view('back/vendor/coupon_edit', $page_data);
        } elseif ($para1 == "update") {
           $datas = json_decode($this->input->raw_input_stream,1);
            $data['title'] = $datas['title'];
            $data['code'] = $datas['code'];
            $data['till'] = $datas['till'];
            $data['status'] = 'ok';
            $data['added_by'] = json_encode(array('type'=>'vendor','id'=>$datas['vendor_id']));
            $data['spec'] = json_encode(array(
                                'set_type'=>'product',
                                'set'=>json_encode($datas['product']),
                                'discount_type'=>$datas['discount_type'],
                                'discount_value'=>$datas['discount_value'],
                                'shipping_free'=>'null'
                            ));
            $this->db->where('coupon_id', $datas['coupon_id']);
            $this->db->update('coupon', $data);
            
            	$results['status'] = 'SUCCESS';

							$results['Message'] = "Coupon Code Updated successfully";

							echo json_encode($results,true);

							exit;
           
        } elseif ($para1 == 'delete') {
            $this->db->where('coupon_id', $para2);
            $this->db->delete('coupon');
			$results['status'] = 'SUCCESS';

							$results['Message'] = "Coupon Code Deleted successfully";

							echo json_encode($results,true);

							exit;
        } elseif ($para1 == 'list') {
		//echo	$dt='{"type":"vendor", "id":"'.$para2.'"';
			//echo $vl=str_replace("","",$dt);
            $this->db->order_by('coupon_id', 'desc');
			//$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$para2)));
			$this->db->where('added_by', '{"type":"vendor","id":"'.$para2.'"}');
            $all_coupons = $this->db->get('coupon')->result_array();
			//echo $this->db->last_query();
			//exit;
            	foreach($all_coupons as $row){
					//if($this->crud_model->is_added_by('coupon',$row['coupon_id'],$para2)){
					//print_r($row);
					 $gt['coupon_id']=$row['coupon_id'];
			 $gt['title']=$row['title'];
			 $gt['code']=$row['code'];
            		
            		    	
			 $by = json_decode($row['added_by'],true);
             $name = $this->crud_model->get_type_name_by_id($by['type'],$by['id'],'name'); 
			// $gt['added_by']=$name($by['type']);
			  $gt['status']=$row['status'];
			 
		// echo $this->db->last_query();
		//	}
			
		$data[]=$gt;	
			
		}
		
		// echo $this->db->last_query(); exit;
			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$data);
		
			exit(json_encode($value));
            
          //  $this->load->view('back/vendor/coupon_list', $page_data);
        }
		 elseif ($para1 == 'totcoupon') {
		//echo	$dt='{"type":"vendor", "id":"'.$para2.'"';
			//echo $vl=str_replace("","",$dt);
            $this->db->order_by('coupon_id', 'desc');
			//$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$para2)));
			$this->db->where('added_by', '{"type":"vendor","id":"'.$para2.'"}');
            $all_coupons = count($this->db->get('coupon')->result_array());
			
		
		// echo $this->db->last_query(); exit;
			$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$all_coupons);
		
			exit(json_encode($value));
            
          //  $this->load->view('back/vendor/coupon_list', $page_data);
        }
		
		 elseif ($para1 == 'add') {
            $this->load->view('back/vendor/coupon_add');
        } elseif ($para1 == 'publish_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('coupon_id', $product);
            $this->db->update('coupon', $data);
        } else {
            $page_data['page_name']      = "coupon";
            $page_data['all_coupons'] = $this->db->get('coupon')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
	
	
	

    function category($para1 = "", $para2 = "", $min = "", $max = "", $text ='')

    {

		if ($para2 == "") {

            $page_data['all_products1'] = $this->db->get_where('product', array(

                'category' => $para1,
				'status' => 'ok'

            ))->result_array();

        } else if ($para2 != "") {

            $page_data['all_products1'] = $this->db->get_where('product', array(

                'sub_category' => $para2,
				'status' => 'ok'

            ))->result_array();

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
			
			  $p['brandname']=$this->crud_model->get_type_name_by_id('brand',$p['brand'],'name');
			  if(file_exists('uploads/brand_image/'.$this->crud_model->get_type_name_by_id('brand',$p['brand'],'logo'))){
				  $p['brandimage']=base_url().'uploads/brand_image/'.$this->crud_model->get_type_name_by_id('brand',$p['brand'],'logo');
			   }
			  else
			  {
				 $p['brandimage']=base_url()."uploads/brand_image/default.jpg";
			  }
		  
			$product_id=$p['product_id'];
			if($p['discount']=='')
			{
				$p['discount']=0.00;
			}
			$p['banner']=$this->crud_model->file_view('product',$product_id,'','','thumb','src','multi','one');
			$p['sale_price']=$p['retailler_price'];
			$temppProduct[]=$p;
		}
		$page_data['all_products']=$temppProduct;
		unset($page_data['all_products1']);
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data['all_products']);
		exit(json_encode($value));
    }

	function all_category(){
        $categories=$this->db->get('category')->result_array();
		foreach($categories as $row){
			if($this->crud_model->if_publishable_category($row['category_id'])){
				$row['count']=$this->crud_model->is_publishable_count('category',$row['category_id']);
				$sub_categories=$this->db->get_where('sub_category',array('category'=>$row['category_id']))->result_array();
				$result= array();
				 foreach($sub_categories as $row1){
				   $brands = json_decode($row1['brand'],TRUE);
					foreach($brands as $row2)
					{
						if(!in_array($row2,$result))
						{
							array_push($result,$row2);
						}
					}
				  }
				  $i=0;
				  foreach($result as $row3){
					  $row[$i]['brandname']=$this->crud_model->get_type_name_by_id('brand',$row3,'name');
					  if(file_exists('uploads/brand_image/'.$this->crud_model->get_type_name_by_id('brand',$row3,'logo'))){
						  $row[$i]['brandimage']=base_url().'uploads/brand_image/'.$this->crud_model->get_type_name_by_id('brand',$row3,'logo');
					   }
					  else
					  {
						 $row[$i]['brandimage']=base_url()."uploads/brand_image/default.jpg";
					  }
				  }
				  
				$row['banner']=base_url().'uploads/category_image/'.$row['banner'];
				$response['category'][]=$row;
			}
		}
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$response);
		exit(json_encode($value));
	}
	function sub_category($para1 = ""){
			if($this->crud_model->if_publishable_category($para1)){
				$sub_categories=$this->db->get_where('sub_category',array('category'=>$para1))->result_array();
				for($i=0;$i<4;$i++)
				{
					if($i>0)
					{ 
						$fc='_'.$i;
					}
					else
						$fc='';
						
					if(file_exists('uploads/category_banner/'.$para1.$fc.'.jpg')){
						$response['ad_banner'][] = base_url().'uploads/category_banner/'.$para1.$fc.'.jpg';
					}
					else
					{
						$response['ad_banner'][] ='';
					}
				}
				foreach($sub_categories as $row1){
					$sub['sub_category_id'] =$row1[sub_category_id];
                    $sub['sub_category_name'] = $row1['sub_category_name'];
					$sub['digital'] =$row1['digital'];
					if(file_exists('uploads/sub_category_image/'.$row1['banner'])){
						$sub['banner'] = base_url().'uploads/sub_category_image/'.$row1['banner'];
					}
					else
					{
						$sub['banner'] =base_url().'uploads/sub_category_image/default.jpg';
					}
					$row['sub_categories'][]=$sub;
				}
				$response['sub_category']=$row['sub_categories'];
			}
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
	function faq(){
        $page_data['page_name']        = "others/faq";
        $page_data['asset_page']       = "all_category";
        $page_data['page_title']       = translate('frequently_asked_questions');
		$page_data['faqs']			   = json_decode($this->crud_model->get_type_name_by_id('business_settings', '11', 'value'),true);
		$this->load->view('front/index', $page_data);
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

    

    function ajax_others_product($para1 = "") 

    {

		$physical_product_activation = $this->db->get_where('general_settings',array('type'=>'physical_product_activation'))->row()->value;

		$digital_product_activation = $this->db->get_where('general_settings',array('type'=>'digital_product_activation'))->row()->value;

		$vendor_system = $this->db->get_where('general_settings',array('type'=>'vendor_system'))->row()->value;

		

        $this->load->library('Ajax_pagination');

        $type=$this->input->post('type');

		if($type=='featured'){

        	$this->db->where('featured','ok');

		}elseif($type=='todays_deal'){

			$this->db->where('deal','ok');

		}

		$this->db->where('status','ok');

		

		if($physical_product_activation == 'ok' && $digital_product_activation !== 'ok'){

			$this->db->where('download',NULL);

		} else if($physical_product_activation !== 'ok' && $digital_product_activation == 'ok'){

			$this->db->where('download','ok');

		} else if($physical_product_activation !== 'ok' && $digital_product_activation !== 'ok'){

			$this->db->where('product_id','');

		}

		

		if($vendor_system !== 'ok'){

			$this->db->like('added_by', '{"type":"admin"', 'both');

		} 

		

        // pagination

        $config['total_rows'] = $this->db->count_all_results('product');

        $config['base_url']   = base_url() . 'index.php?home/listed/';

        $config['per_page'] = 9;
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

		$this->db->where('status','ok');

		if($type=='featured'){

        	$this->db->where('featured','ok');

		}elseif($type=='todays_deal'){

			$this->db->where('deal','ok');

		}

		

		if($physical_product_activation == 'ok' && $digital_product_activation !== 'ok'){

			$this->db->where('download',NULL);

		} else if($physical_product_activation !== 'ok' && $digital_product_activation == 'ok'){

			$this->db->where('download','ok');

		} else if($physical_product_activation !== 'ok' && $digital_product_activation !== 'ok'){

			$this->db->where('product_id','');

		}

		

		if($vendor_system !== 'ok'){

			$this->db->like('added_by', '{"type":"admin"', 'both');

		} 

		

        $page_data['products'] 	= $this->db->get('product', $config['per_page'], $para1)->result_array();

        $page_data['count']              = $config['total_rows'];

		$page_data['page_type']       	 = $type;

        

        $this->load->view('front/others_list/listed', $page_data);

    }

	

    /* FUNCTION: Loads Product List */

    function listed($para1 = "", $para2 = "", $para3 = "")

    {

        $this->load->library('Ajax_pagination');

        if ($para1 == "click") {			

			$physical_product_activation = $this->db->get_where('general_settings',array('type'=>'physical_product_activation'))->row()->value;

			$digital_product_activation = $this->db->get_where('general_settings',array('type'=>'digital_product_activation'))->row()->value;

			$vendor_system = $this->db->get_where('general_settings',array('type'=>'vendor_system'))->row()->value;

            if ($this->input->post('range')) {

                $range = $this->input->post('range');

            }

            if ($this->input->post('text')) {

                $text = $this->input->post('text');

            }

            $category     = $this->input->post('category');

            $category     = explode(',', $category);

            $sub_category = $this->input->post('sub_category');

            $sub_category = explode(',', $sub_category);

            $featured     = $this->input->post('featured');

            $brand     	  = $this->input->post('brand');

            $name         = '';

            $cat          = '';

            $setter       = '';

            $vendors      = array();

            $approved_users = $this->db->get_where('vendor',array('status'=>'approved'))->result_array();

            foreach ($approved_users as $row) {

                $vendors[] = $row['vendor_id'];

            } 

			

			if($vendor_system !== 'ok'){

				$this->db->like('added_by', '{"type":"admin"', 'both');

			} 

	

			if($physical_product_activation == 'ok' && $digital_product_activation !== 'ok'){

				$this->db->where('download',NULL);

			} else if($physical_product_activation !== 'ok' && $digital_product_activation == 'ok'){

				$this->db->where('download','ok');

			} else if($physical_product_activation !== 'ok' && $digital_product_activation !== 'ok'){

				$this->db->where('product_id','');

			}

			

            if(isset($text)){

                if($text !== ''){

                    $this->db->like('title', $text);

					$this->db->or_like('tag', $text);

                }

            }



            if($vendor = $this->input->post('vendor')){

                if(in_array($vendor, $vendors)){

                    $this->db->where('added_by', '{"type":"vendor","id":"'.$vendor.'"}');

                } else {

                    $this->db->where('product_id','');

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

                $this->db->where('retailler_price >=', $p[0]);

                $this->db->where('retailler_price <=', $p[1]);

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

            $config['total_rows'] = $this->db->count_all_results('product');

            $config['base_url']   = base_url() . 'index.php?home/listed/';

            if ($featured !== 'ok') {

                $config['per_page'] = 9;

            } else if ($featured == 'ok') {

                $config['per_page'] = 9;

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

            

            

            $this->db->where('status', 'ok');

            if ($featured == 'ok') {

                $this->db->where('featured', 'ok');

                $grid_items_per_row = 3;

                $name               = 'Featured';

            } else {

                $grid_items_per_row = 3;

            }

            

            if(isset($text)){

                if($text !== ''){

                    $this->db->like('title', $text);

					$this->db->or_like('tag', $text);

                }

            }



			if($physical_product_activation == 'ok' && $digital_product_activation !== 'ok'){

				$this->db->where('download',NULL);

			} else if($physical_product_activation !== 'ok' && $digital_product_activation == 'ok'){

				$this->db->where('download','ok');

			} else if($physical_product_activation !== 'ok' && $digital_product_activation !== 'ok'){

				$this->db->where('product_id','');

			}

			

			if($vendor_system !== 'ok'){

				$this->db->like('added_by', '{"type":"admin"', 'both');

			} 

			

            if($vendor = $this->input->post('vendor')){

                if(in_array($vendor, $vendors)){

                    $this->db->where('added_by', '{"type":"vendor","id":"'.$vendor.'"}');

                } else {

                    $this->db->where('product_id','');

                }                

            }

            

			

            if ($brand !== '0' && $brand !== '') {

                $this->db->where('brand', $brand);

            }

			

            if (isset($range)) {

                $p = explode(';', $range);

                $this->db->where('retailler_price >=', $p[0]);

                $this->db->where('retailler_price <=', $p[1]);

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

			

			if($sort== 'most_viewed'){

				$this->db->order_by('number_of_view', 'desc');

			}

			if($sort== 'condition_old'){

				$this->db->order_by('product_id', 'asc');

			}

			if($sort== 'condition_new'){

				$this->db->order_by('product_id', 'desc');

			}

			if($sort== 'price_low'){

				$this->db->order_by('retailler_price', 'asc');

			}

			if($sort== 'price_high'){

				$this->db->order_by('retailler_price', 'desc');

			}

			else{

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

        $page_data['vendor_system'] 	 = $this->db->get_where('general_settings',array('type' => 'vendor_system'))->row()->value;

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

	

    

    /* FUNCTION: Loads Product View Page */

    function quick_view($para1 = "")

    {

        $product_data              = $this->db->get_where('product', array(

            'product_id' => $para1,

            'status' => 'ok'

        ));


        $page_data['product_details'] = $product_data->result_array();
		$page_data['product_details']=$page_data['product_details'][0];

        //$page_data['page_title']   = $product_data->row()->title;
		$page_data['product_details']['sale_price']=$page_data['product_details']['retailler_price'];
		$page_data['product_details']['purchase_price']=$page_data['product_details']['retailler_price'];
        $page_data['product_tags'] = $product_data->row()->tag;

		$page_data['product_details']['banner']=$this->crud_model->file_view('product',$para1,'','','thumb','src','multi','one');
		$page_data['product_details']['additional_specification']=$this->crud_model->get_additional_fields($row['product_id']);			       	$page_data['product_details']['shipment_info']=$this->db->get_where('business_settings',array('type'=>'shipment_info'))->row()->value;
		$page_data['product_details']['product_by']=$this->crud_model->product_by($para1,'with_link');
		$value=array("status"=>"SUCCESS","Message"=>"SUCCESS", "Response"=>$page_data);
		exit(json_encode($value));

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


								$data['password'] = sha1($password);

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

							$data['password'] = sha1($password);

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

			$signin_data = $this->db->get_where('vendor', array(

				'email' => $datas['email'],

				'password' => sha1($datas['password'])

			));
			//echo $this->db->last_query();

			//echo $signin_data->num_rows();

			if ($signin_data->num_rows() > 0) {

				foreach ($signin_data->result_array() as $row) {

					

					//$accessToken = bin2hex(openssl_random_pseudo_bytes(16));

					$accessToken = rand(1234567890,16);

				

					$response['id']=$row['vendor_id'];

					$response['name']=$row['name'];

					$response['display_name']=$row['display_name'];

					$response['city']=$row['city'];
					
					$response['state']=$row['state'];

					$response['country']=$row['country'];

					$response['email']=$row['email'];

					$response['company']=$row['company'];
					
					$response['display_name']=$row['display_name'];

					$response['address1']=$row['address1'];

					$response['address2']=$row['address2'];
					$response['zip']=$row['zip'];
					$response['details']=$row['details'];
					$response['phone']=$row['phone'];

				


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
				$query = $this->db->get_where('user_login', array(
					'email_id' => $this->input->post('email')
				));
				if ($query->num_rows() > 0) {
					$user_id          = $query->row()->id;
					$password         = substr(rand(), 0, 12);
					$data['password'] = $password;
					$this->db->where('id', $user_id);
					$this->db->update('user_login', $data);
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

        $this->load->library('form_validation');

        $page_data['page_name'] = "registration";

        if ($para1 == "add_info") {

        	$msg = '';

			$this->form_validation->set_rules('username', 'Your First Name', 'required');

            $this->form_validation->set_rules('email', 'Email', 'required|is_unique[user.email]|valid_email',array('required' => 'You have not provided %s.', 'is_unique' => 'This %s already exists.'));

            $this->form_validation->set_rules('password1', 'Password', 'required|matches[password2]');

            $this->form_validation->set_rules('password2', 'Confirm Password', 'required');

            $this->form_validation->set_rules('address1', 'Address Line 1', 'required');

            $this->form_validation->set_rules('address2', 'Address Line 2', 'required');

            $this->form_validation->set_rules('phone', 'Phone', 'required');

            $this->form_validation->set_rules('surname', 'Your Last Name', 'required');

            $this->form_validation->set_rules('zip', 'ZIP', 'required');

            $this->form_validation->set_rules('city', 'City', 'required');

            $this->form_validation->set_rules('state', 'State', 'required');

            $this->form_validation->set_rules('country', 'Country', 'required');

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

							$data['langlat']       = '';

							$data['wishlist']      = '[]';

							$data['creation_date'] = time();

							

							if ($this->input->post('password1') == $this->input->post('password2')) {

								$password         = $this->input->post('password1');

								$data['password'] = sha1($password);

								$this->db->insert('user_login', $data);

								$msg = 'done';

								if($this->email_model->account_opening('user', $data['email'], $password) == false){

									$msg = 'done_but_not_sent';

								}else{

									$msg = 'done_and_sent';

								}

							}

							echo $msg;

						}else{

							echo translate('please_fill_the_captcha');

						}

					}else{

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

						$data['langlat']       = '';

						$data['wishlist']      = '[]';

						$data['creation_date'] = time();

						

						if ($this->input->post('password1') == $this->input->post('password2')) {

							$password         = $this->input->post('password1');

							$data['password'] = sha1($password);

							$this->db->insert('user_login', $data);

							$msg = 'done';

							if($this->email_model->account_opening('user', $data['email'], $password) == false){

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

            $account_data          = $this->db->get_where('user_login', array(

                'id' => $this->session->userdata('user_id')

            ))->result_array();

            foreach ($account_data as $row) {

                if (sha1($user_data['password']) == $row['password']) {

                    if ($this->input->post('password1') == $this->input->post('password2')) {

                        $data['password'] = sha1($this->input->post('password1'));

                        $this->db->where('id', $this->session->userdata('user_id'));

                        $this->db->update('user_login', $data);

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

            $return += array('price' => currency().$product->row()->retailler_price);

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
		if(isset($datas['userID']) && $datas['userID']!='' && $datas['userID']!=0 && $datas['mode']=='user')
		{
			$userID=$datas['userID'];
			$cartDetails=$datas['cart'];
			$userDetails=$this->db->get_where('user_login',array('id'=>$userID))->result_array();
			$userDetails=$userDetails[0];
			$balance=$userDetails['balance'];
			if($datas['payment_type']=='wallet')
			{
				$data['buyer'] = $userID;
				foreach($datas['cart'] as $cart)
				{
					$cartV['id']=$product_id=$cart['product_id'];	
					$productInfo[] = $this->db->get_where('product',array('product_id'=>$product_id))->result_array();
					$productInfo = $productInfo[0][0];
					$quantity=$cartV['qty']=$cart['qty'];	
					$productColor=$cartOption['color']=$productInfo['color'];	
					$productName=$cartOption['title']=$productInfo['title'];	
					$cartOption['value']="";
					$cartV['option']	=$cartOption;
					$productPrice=$cartV['price']=$productInfo['retailler_price'];	
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
					$cartV['subtotal']=$grand_total=$salePrice+$tax+$productInfo['shipping_cost'];	
					$cartArray[rand(10000,100000).rand(10000,100000)]=$cartV;
				}
				if($balance<=$grand_total)
				{
					$results['status'] = 'FAILED';
					$results['Message'] = 'Insufficient Balance';
					$results['Response'] = 'Insufficient Balance';
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
				$data['payment_type']      = 'Wallet Pay';
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

                    $this->db->where('id', $tmpUserId);

                    $this->db->update('user_login', $data2);
					$results['status'] = 'SUCCESS';
					$results['Message'] = 'Order Completed-'.$sale_id;
					$results['Response'] = array("order_id"=>$sale_id,"product_name"=>$productName,"product_shipping_cost"=>$shipping,"product_tax"=>$tax,"product_tax_type"=>$productInfo['tax_type'],"product_image"=>$productImage,"color"=>$productColor,"product_price"=>$productPrice,"qty"=>$quantity,"sub_total"=>$salePrice,"discount_amount"=>0,"tax_amount"=>$tax,"total_amount"=>$grand_total,"firstname"=>$firstname,"lastname"=>$lastname,"address1"=>$address1,"address2"=>$address2,"email"=>$email,"zip"=>$zip,"phone"=>$mobile,"shipping_status"=>"Processing","payment_type"=>$payment_type,"create_date"=>date('Y-m-d h:i:s'));
					echo json_encode($results,true);
					exit;
			}
		}
		else
		{
			$results['status'] = 'FAILED';
			$results['Message'] = 'Please login to continue';
			$results['Response'] = 'Please login to continue';
			echo json_encode($results,true);
			exit;
		}
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
		  if(isset($productInfo['brand']) && $productInfo['brand']!='' && $productInfo['brand']!='null')
		  $brand_id 				=	$productInfo['brand'];
		  else
		  $brand_id 				=0;
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
			$retailler_cashback=0;
			$distributor_cashback=0;
			$cashback=0;	
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
		  $this->db->insert('agent_sales', $productInsertData);	
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
			  
			  $content='Order Received: We have received your order for '.$pname1.' '.$pname2.' with order id '.$order_id.' amounting to Rs.'.$total_amount.'. You can expect in next 4-5 working days. We will send you an update when your order is packed. Thanks for shopping in www.paytm-clone.com';
			  
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
			  $insertSMS['template_name']="paytm-clone-RETAILLER-SHOPPING-ORDER";
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
			  $device_tracking['request']=json_encode($productInsertData);
			  $device_tracking['action']='agent_cart_checkout';
			  $this->db->insert('device_tracking', $device_tracking);	
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

            $this->db->update('user_login', array(

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

                    $data['payment_type']      = 'Wallet Pay';

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

                    $this->db->update('user_login', $data2);

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

                        $customer_email = $this->db->get_where('user_login' , array('id' => $this->session->userdata('user_id')))->row()->email;

                        

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

    function blog_by_cat($para1 = "")

    {

        $page_data['category']= $para1;

        $this->load->view('front/blog/blog_list', $page_data);   

    }

    

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

	function product_by_type($para1 = ""){

		$page_data['product_type']= $para1;

        $this->load->view('front/others_list/view', $page_data);

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

    function invoice($para1 = "", $para2 = "")

    {

        if ($this->session->userdata('user_login') != "yes"

             || $this->crud_model->get_type_name_by_id('sale', $para1, 'buyer') !==  $this->session->userdata('user_id'))

        {

            redirect(base_url(), 'refresh');

        }



        $page_data['sale_id']    = $para1;

        $page_data['asset_page']    = "invoice";

        $page_data['page_name']  = "shopping_cart/invoice";

        $page_data['page_title'] = translate('invoice');

        if($para2 == 'email'){

            $this->load->view('front/shopping_cart/invoice_email', $page_data);

        } else {

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

        $this->db->order_by('retailler_price', $set);

        if (count($a = $this->db->get_where('product', array(

            $by => $id

        ))->result_array()) > 0) {

            foreach ($a as $r) {

                return $r['retailler_price'];

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

    function sitemap(){

		header("Content-type: text/xml");

        $otherurls = array(

                        base_url().'index.php/home/contact/',

                        base_url().'index.php/home/legal/terms_conditions',

                        base_url().'index.php/home/legal/privacy_policy'

                    );

        $producturls = array();

        $products = $this->db->get_where('product',array('status'=>'ok'))->result_array();

        foreach ($products as $row) {

            $producturls[] = $this->crud_model->product_link($row['product_id']);

        }

        $vendorurls = array();

        $vendors = $this->db->get_where('vendor',array('status'=>'approved'))->result_array();

        foreach ($vendors as $row) {

            $vendorurls[] = $this->crud_model->vendor_link($row['vendor_id']);

        }

        $page_data['otherurls']  = $otherurls;

        $page_data['producturls']  = $producturls;

        $page_data['vendorurls']  = $vendorurls;

        $this->load->view('front/others/sitemap', $page_data);

    }

    

}



/* End of file home.php */

/* Location: ./application/controllers/home.php */

