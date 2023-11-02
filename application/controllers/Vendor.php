<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Vendor extends CI_Controller
{
    /*  
     *  Developed by: Active IT zone
     *  Date    : 14 July, 2015
     *  Active Supershop eCommerce CMS
     *  http://codecanyon.net/user/activeitezone
     */
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('paypal');
        $this->load->library('twoCheckout_Lib');
        $this->load->library('vouguepay');
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        //$this->crud_model->ip_data();
		$vendor_system	 =  $this->db->get_where('general_settings',array('type' => 'vendor_system'))->row()->value;
		if($vendor_system !== 'ok'){
			redirect(base_url(), 'refresh');
		}
    }
    
    /* index of the vendor. Default: Dashboard; On No Login Session: Back to login page. */
    public function index()
    {
        if ($this->session->userdata('vendor_login') == 'yes') {
            $page_data['page_name'] = "dashboard";
            $this->load->view('back/index', $page_data);
        } else {
            $page_data['control'] = "vendor";
            $this->load->view('back/login',$page_data);
        }
    }
    /*Product slides add, edit, view, delete */
    function slides($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->vendor_permission('slides')) {
            redirect(base_url() . 'index.php/vendor');
        }
        if ($para1 == 'do_add') {
            $type                		= 'slides';
            $data['button_color']      	= $this->input->post('color_button');
			$data['text_color']        	= $this->input->post('color_text');
			$data['button_text']        = $this->input->post('button_text');
			$data['button_link']        = $this->input->post('button_link');
			$data['uploaded_by']		= 'vendor';
			$data['added_by']           = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $this->db->insert('slides', $data);
            $id = $this->db->insert_id();
            $this->crud_model->file_up("img", "slides", $id, '', '', '.jpg');
            recache();
        } elseif ($para1 == "update") {
            $data['button_color']      	= $this->input->post('color_button');
			$data['text_color']        	= $this->input->post('color_text');
			$data['button_text']        = $this->input->post('button_text');
			$data['button_link']        = $this->input->post('button_link');
            $this->db->where('slides_id', $para2);
            $this->db->update('slides', $data);
            $this->crud_model->file_up("img", "slides", $para2, '', '', '.jpg');
            recache();
        } elseif ($para1 == 'delete') {
            $this->crud_model->file_dlt('slides', $para2, '.jpg');
            $this->db->where('slides_id', $para2);
            $this->db->delete('slides');
            recache();
        } elseif ($para1 == 'multi_delete') {
            $ids = explode('-', $param2);
            $this->crud_model->multi_delete('slides', $ids);
        } else if ($para1 == 'edit') {
            $page_data['slides_data'] = $this->db->get_where('slides', array(
                'slides_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/slides_edit', $page_data);
        } elseif ($para1 == 'list') {
            $this->db->order_by('slides_id', 'desc');
			$this->db->where('added_by', json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $page_data['all_slidess'] = $this->db->get('slides')->result_array();
            $this->load->view('back/vendor/slides_list', $page_data);
        }elseif ($para1 == 'slide_publish_set') {
            $slides_id = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('slides_id', $slides_id);
            $this->db->update('slides', $data);
            recache();
        } elseif ($para1 == 'add') {
            $this->load->view('back/vendor/slides_add');
        } else {
            $page_data['page_name']  = "slides";
            $page_data['all_slidess'] = $this->db->get('slides')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    /* Login into vendor panel */
    function login($para1 = '')
    {
        if ($para1 == 'forget_form') {
            $page_data['control'] = 'vendor';
            $this->load->view('back/forget_password',$page_data);
        } else if ($para1 == 'forget') {
            
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');         
            if ($this->form_validation->run() == FALSE)
            {
                echo validation_errors();
            }
            else
            {
                $query = $this->db->get_where('vendor', array(
                    'email' => $this->input->post('email')
                ));
                if ($query->num_rows() > 0) {
                    $vendor_id         = $query->row()->vendor_id;
                    $password         = substr(hash('sha512', rand()), 0, 12);
                    $data['password'] = sha1($password);
                    $this->db->where('vendor_id', $vendor_id);
                    $this->db->update('vendor', $data);
                    if ($this->email_model->password_reset_email('vendor', $vendor_id, $password)) {
                        echo 'email_sent';
                    } else {
                        echo 'email_not_sent';
                    }
                } else {
                    echo 'email_nay';
                }
            }
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');
            
            if ($this->form_validation->run() == FALSE)
            {
                echo validation_errors();
            }
            else
            {
                $login_data = $this->db->get_where('vendor', array(
                    'email' => $this->input->post('email'),
                    'password' => sha1($this->input->post('password'))
                ));
                if ($login_data->num_rows() > 0) {
                    if($login_data->row()->status == 'approved'){
                        foreach ($login_data->result_array() as $row) {
                            $this->session->set_userdata('login', 'yes');
                            $this->session->set_userdata('vendor_login', 'yes');
                            $this->session->set_userdata('vendor_id', $row['vendor_id']);
                            $this->session->set_userdata('vendor_name', $row['display_name']);
                            $this->session->set_userdata('title', 'vendor');
                            echo 'lets_login';
                        }
                    } else {
                        echo 'unapproved';
                    }
                } else {
                    echo 'login_failed';
                }
            }
        }
    }
    
    
    /* Loging out from vendor panel */
    function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url() . 'index.php/vendor', 'refresh');
    }
    
    /*Product coupon add, edit, view, delete */
    function coupon($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->vendor_permission('coupon')) {
            redirect(base_url() . 'index.php/vendor');
        }
        if ($para1 == 'do_add') {
            $data['title'] = $this->input->post('title');
            $data['code'] = $this->input->post('code');
            $data['till'] = $this->input->post('till');
            $data['status'] = 'ok';
            $data['added_by'] = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $data['spec'] = json_encode(array(
                                'set_type'=>'product',
                                'set'=>json_encode($this->input->post('product')),
                                'discount_type'=>$this->input->post('discount_type'),
                                'discount_value'=>$this->input->post('discount_value'),
                                'shipping_free'=>$this->input->post('shipping_free')
                            ));
            $this->db->insert('coupon', $data);
        } else if ($para1 == 'edit') {
            $page_data['coupon_data'] = $this->db->get_where('coupon', array(
                'coupon_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/coupon_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['title'] = $this->input->post('title');
            $data['code'] = $this->input->post('code');
            $data['till'] = $this->input->post('till');
            $data['spec'] = json_encode(array(
                                'set_type'=>'product',
                                'set'=>json_encode($this->input->post('product')),
                                'discount_type'=>$this->input->post('discount_type'),
                                'discount_value'=>$this->input->post('discount_value'),
                                'shipping_free'=>$this->input->post('shipping_free')
                            ));
            $this->db->where('coupon_id', $para2);
            $this->db->update('coupon', $data);
        } elseif ($para1 == 'delete') {
            $this->db->where('coupon_id', $para2);
            $this->db->delete('coupon');
        } elseif ($para1 == 'list') {
            $this->db->order_by('coupon_id', 'desc');
            $page_data['all_coupons'] = $this->db->get('coupon')->result_array();
            $this->load->view('back/vendor/coupon_list', $page_data);
        } elseif ($para1 == 'add') {
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
    
    /*Product Sale Comparison Reports*/
    function report($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->vendor_permission('report')) {
            redirect(base_url() . 'index.php/vendor');
        }
        $page_data['page_name'] = "report";
		$physical_system   	 =  $this->crud_model->get_type_name_by_id('general_settings','68','value');
		$digital_system   	 =  $this->crud_model->get_type_name_by_id('general_settings','69','value');
		if($physical_system !== 'ok' && $digital_system == 'ok'){
			$this->db->where('download','ok');
		}
		if($physical_system == 'ok' && $digital_system !== 'ok'){
			$this->db->where('download',NULL);
		}
		if($physical_system !== 'ok' && $digital_system !== 'ok'){
			$this->db->where('download','0');
		}
		$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
        $page_data['products']  = $this->db->get('product')->result_array();
        $this->load->view('back/index', $page_data);
    }
    
    /*Product Stock Comparison Reports*/
    function report_stock($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->vendor_permission('report')) {
            redirect(base_url() . 'index.php/vendor');
        }
		if ($this->crud_model->get_type_name_by_id('general_settings','68','value') !== 'ok') {
			redirect(base_url() . 'index.php/admin');
		}
        $page_data['page_name'] = "report_stock";
        if ($this->input->post('product')) {
            $page_data['product_name'] = $this->crud_model->get_type_name_by_id('product', $this->input->post('product'), 'title');
            $page_data['product']      = $this->input->post('product');
        }
        $this->load->view('back/index', $page_data);
    }
    
    /*Product Wish Comparison Reports*/
    function report_wish($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->vendor_permission('report')) {
            redirect(base_url() . 'index.php/vendor');
        }
        $page_data['page_name'] = "report_wish";
        $this->load->view('back/index', $page_data);
    }
    
    /* Product add, edit, view, delete, stock increase, decrease, discount */
    function product($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->vendor_permission('product')) {
            redirect(base_url() . 'index.php/vendor');
        }
		if ($this->crud_model->get_type_name_by_id('general_settings','68','value') !== 'ok') {
			redirect(base_url() . 'index.php/admin');
		}
        if ($para1 == 'do_add') {
            $options = array();
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
            $data['store_id']              = $this->input->post('store_id');
            $data['title']              = $this->input->post('title');
            $data['category']           = $this->input->post('category');
            $data['description']        = $this->input->post('description');
            $data['sub_category']       = $this->input->post('sub_category');
            $data['sale_price']         = $this->input->post('sale_price');
            $data['purchase_price']     = $this->input->post('purchase_price');
			$data['bid_start_date']     = $this->input->post('bid_start_date');
			$data['bid_start_time']     = $this->input->post('bid_start_time');
			$data['bid_end_date']       = $this->input->post('bid_end_date');
			$data['bid_end_time']       = $this->input->post('bid_end_time');
			$data['min_bid_amount']     = $this->input->post('min_bid_amount');
			$data['max_bid_amount']     = $this->input->post('max_bid_amount'); 
			$data['bidding']            = $this->input->post('product_bid'); 
            $data['add_timestamp']      = time();
			$data['download']           = NULL;
			$data['featured']           = 'no';
            $data['status']             = 'ok';
            $data['rating_user']        = '[]';
            $data['tax']                = $this->input->post('tax');
            $data['discount']           = $this->input->post('discount');
            $data['discount_type']      = $this->input->post('discount_type');
            $data['cashpack']           = $this->input->post('cashpack');
            $data['cashpack_type']      = $this->input->post('cashpack_type');
            $data['tax_type']           = $this->input->post('tax_type');
            $data['shipping_cost']      = $this->input->post('shipping_cost');
            $data['tag']                = $this->input->post('tag');
            $data['color']              = json_encode($this->input->post('color'));
            $data['num_of_imgs']        = $num_of_imgs;
            $data['current_stock']      = $this->input->post('current_stock');
            $data['front_image']        = 0;
            $additional_fields['name']  = json_encode($this->input->post('ad_field_names'));
            $additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
            $data['additional_fields']  = json_encode($additional_fields);
            $data['brand']              = $this->input->post('brand');
            $data['unit']               = $this->input->post('unit');
            $data['enquiry']            = $this->input->post('enquiry');
            $data['subscribe']               = $this->input->post('subscribe');
            $choice_titles              = $this->input->post('op_title');
            $choice_types               = $this->input->post('op_type');
            $choice_no                  = $this->input->post('op_no');
			$data['added_by']           = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
			if(count($choice_titles ) > 0){
				foreach ($choice_titles as $i => $row) {
					$choice_options         = $this->input->post('op_set'.$choice_no[$i]);
					$options[]              =   array(
													'no' => $choice_no[$i],
													'title' => $choice_titles[$i],
													'name' => 'choice_'.$choice_no[$i],
													'type' => $choice_types[$i],
													'option' => $choice_options
												);
				}
			}
            $data['options']            = json_encode($options);
            
			if($this->crud_model->can_add_product($this->session->userdata('vendor_id'))){
                $this->db->insert('product', $data);
				$id = $this->db->insert_id();
				$this->benchmark->mark_time();
				$this->crud_model->file_up("images", "product", $id, 'multi');
            } else {
                echo 'already uploaded maximum product';
            }
			$this->crud_model->set_category_data(0);
            recache();
        } else if ($para1 == "update") {
            $options = array();
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
            $num                        = $this->crud_model->get_type_name_by_id('product', $para2, 'num_of_imgs');
            $download                   = $this->crud_model->get_type_name_by_id('product', $para2, 'download');
            $data['store_id']              = $this->input->post('store_id');
            $data['title']              = $this->input->post('title');
            $data['category']           = $this->input->post('category');
            $data['description']        = $this->input->post('description');
            $data['sub_category']       = $this->input->post('sub_category');
            $data['sale_price']         = $this->input->post('sale_price');
            $data['purchase_price']     = $this->input->post('purchase_price');
			$data['enquiry']            = $this->input->post('enquiry');
            $data['subscribe']               = $this->input->post('subscribe');
            $data['bidding']            = $this->input->post('product_bid');
             if($data['bidding'] === 1){
			$data['bid_start_date']     = $this->input->post('bid_start_date');
			$data['bid_start_time']     = $this->input->post('bid_start_time');
			$data['bid_end_date']       = $this->input->post('bid_end_date');
			$data['bid_end_time']       = $this->input->post('bid_end_time');
			$data['min_bid_amount']     = $this->input->post('min_bid_amount');
			$data['max_bid_amount']     = $this->input->post('max_bid_amount');
            }
            else{
            $data['bid_start_date']     = '';
			$data['bid_start_time']     = '';
			$data['bid_end_date']       = '';
			$data['bid_end_time']       = '';
			$data['min_bid_amount']     = '';
			$data['max_bid_amount']     = ''; 
            }
            $data['tax']                = $this->input->post('tax');
            $data['discount']           = $this->input->post('discount');
            $data['discount_type']      = $this->input->post('discount_type');
            $data['cashpack']           = $this->input->post('cashpack');
            $data['cashpack_type']      = $this->input->post('cashpack_type');
            $data['tax_type']           = $this->input->post('tax_type');
            $data['shipping_cost']      = $this->input->post('shipping_cost');
            $data['tag']                = $this->input->post('tag');
            $data['color']              = json_encode($this->input->post('color'));
            $data['num_of_imgs']        = $num + $num_of_imgs;
            $data['front_image']        = 0;
            $additional_fields['name']  = json_encode($this->input->post('ad_field_names'));
            $additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
            $data['additional_fields']  = json_encode($additional_fields);
            $data['brand']              = $this->input->post('brand');
            $data['unit']               = $this->input->post('unit');
            $choice_titles              = $this->input->post('op_title');
            $choice_types               = $this->input->post('op_type');
            $choice_no                  = $this->input->post('op_no');
			if(count($choice_titles ) > 0){
				foreach ($choice_titles as $i => $row) {
					$choice_options         = $this->input->post('op_set'.$choice_no[$i]);
					$options[]              =   array(
													'no' => $choice_no[$i],
													'title' => $choice_titles[$i],
													'name' => 'choice_'.$choice_no[$i],
													'type' => $choice_types[$i],
													'option' => $choice_options
												);
				}
			}
            $data['options']            = json_encode($options);
            $this->crud_model->file_up("images", "product", $para2, 'multi');
            
            $this->db->where('product_id', $para2);
            $this->db->update('product', $data);
            echo $this->db->last_query();
			$this->crud_model->set_category_data(0);
            recache();
        } else if ($para1 == 'edit') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/product_edit', $page_data);
        } else if ($para1 == 'view') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/product_view', $page_data);
        } 
		
		
		else if ($para1 == 'bidd') 
		{
			$this->db->select_max('batch_no');
         $this->db->where('pid', $para2);
		 $this->db->where('status', 1);
		 $this->db->where('payment_status', 1);
		 
		 $resa2 = $this->db->get('bidding_history')->result_array();
		// echo $this->db->last_query();
		 $page_data['baatch_max']=$resa2[0]['batch_no']; 
			
			//echo  $page_data['baatch_max']; exit;
			$new=$this->db->get_where('product', array('product_id' => $para2))->result_array();
				$page_data['mode'] = json_decode($new[0]['added_by'],true); 
            $page_data['product_bidd'] = $this->db->order_by('bid_amt', 'DESC')->get_where('bidding_history', array('pid' => $para2,'status' => '1','payment_status' => '1'))->result_array();
            $this->load->view('back/vendor/product_bidd', $page_data);
		}elseif ($para1 == 'delete') {
            $this->crud_model->file_dlt('product', $para2, '.jpg', 'multi');
            $this->db->where('product_id', $para2);
            $this->db->delete('product');
			$this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('product_id', 'desc');
			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
			$this->db->where('download=',NULL);
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $this->load->view('back/vendor/product_list', $page_data);
        } elseif ($para1 == 'list_data') {
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
                             'store_name' => '',
                             'title' => '',
                             'current_stock' => '',
                             'publish' => '',
                             'options' => ''
                          );

                $res['image']  = '<img class="img-sm" style="height:auto !important; border:1px solid #ddd;padding:2px; border-radius:2px !important;" src="'.$this->crud_model->file_view('product',$row['product_id'],'','','thumb','src','multi','one').'"  />';
                $multi_store=$this->db->get_where('business_settings',array('type'=>'multi_store_set'))->row()->value;
							if($multi_store=='ok'){
                $store_name=$this->db->get_where('stores',array('store_id'=>$row['store_id']))->row()->store_name;
                $res['store_name']  = $store_name;
							}
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
	
	/* Digital add, edit, view, delete, stock increase, decrease, discount */
    function digital($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->vendor_permission('product')) {
            redirect(base_url() . 'index.php/vendor');
        }
		if ($this->crud_model->get_type_name_by_id('general_settings','69','value') !== 'ok') {
			redirect(base_url() . 'index.php/admin');
		}
        if ($para1 == 'do_add') {
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
			if($this->crud_model->can_add_product($this->session->userdata('vendor_id'))){
                $data['title']              = $this->input->post('title');
				$data['category']           = $this->input->post('category');
				$data['description']        = $this->input->post('description');
				$data['sub_category']       = $this->input->post('sub_category');
				$data['sale_price']         = $this->input->post('sale_price');
				$data['purchase_price']     = $this->input->post('purchase_price');
				$data['add_timestamp']      = time();
				$data['featured']           = 'no';
				$data['status']             = 'ok';
				$data['rating_user']        = '[]';
				$data['tax']                = $this->input->post('tax');
				$data['discount']           = $this->input->post('discount');
				$data['discount_type']      = $this->input->post('discount_type');
				$data['tax_type']           = $this->input->post('tax_type');
				$data['shipping_cost']      = 0;
				$data['tag']                = $this->input->post('tag');
				$data['num_of_imgs']        = $num_of_imgs;
				$data['front_image']        = $this->input->post('front_image');
				$additional_fields['name']  = json_encode($this->input->post('ad_field_names'));
				$additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
				$data['additional_fields']  = json_encode($additional_fields);
				$data['requirements']		=	'[]';
				$data['video']				=	'[]';
				
				$data['added_by']           = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
				
				$this->db->insert('product', $data);
				$id = $this->db->insert_id();
				$this->benchmark->mark_time();
				
				$this->crud_model->file_up("images", "product", $id, 'multi');
				
				$path = $_FILES['logo']['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$data_logo['logo'] 		 = 'digital_logo_'.$id.'.'.$ext;
				$this->db->where('product_id' , $id);
				$this->db->update('product' , $data_logo);
				$this->crud_model->file_up("logo", "digital_logo", $id, '','no','.'.$ext);
				
				//Requirements add
				$requirements				=	array();
				$req_title					=	$this->input->post('req_title');
				$req_desc					=	$this->input->post('req_desc');
				if(!empty($req_title)){
					foreach($req_title as $i => $row){
						$requirements[]			=	array('index'=>$i,'field'=>$row,'desc'=>$req_desc[$i]);
					}
				}
				
				$data_req['requirements']			=	json_encode($requirements);
				$this->db->where('product_id' , $id);
				$this->db->update('product' , $data_req);
				
				//File upload
				$rand           = substr(hash('sha512', rand()), 0, 20);
				$name           = $id.'_'.$rand.'_'.$_FILES['product_file']['name'];
				$da['download_name'] = $name;
				$da['download'] = 'ok';
				$folder = $this->db->get_where('general_settings', array('type' => 'file_folder'))->row()->value;
				move_uploaded_file($_FILES['product_file']['tmp_name'], 'uploads/file_products/' . $folder .'/' . $name);
				$this->db->where('product_id', $id);
				$this->db->update('product', $da);
				
				//vdo upload
				$video_details				=	array();
				if($this->input->post('upload_method') == 'upload'){				
					$video 				= 	$_FILES['videoFile']['name'];
					$ext   				= 	pathinfo($video,PATHINFO_EXTENSION);
					move_uploaded_file($_FILES['videoFile']['tmp_name'],'uploads/video_digital_product/digital_'.$id.'.'.$ext);
					$video_src 			= 	'uploads/video_digital_product/digital_'.$id.'.'.$ext;
					$video_details[] 	= 	array('type'=>'upload','from'=>'local','video_link'=>'','video_src'=>$video_src);
					$data_vdo['video']	=	json_encode($video_details);
					$this->db->where('product_id',$id);
					$this->db->update('product',$data_vdo);		
				}
				elseif ($this->input->post('upload_method') == 'share'){
					$from 				= $this->input->post('site');
					$video_link 		= $this->input->post('video_link');
					$code				= $this->input->post('video_code');
					if($from=='youtube'){
						$video_src  	= 'https://www.youtube.com/embed/'.$code;
					}else if($from=='dailymotion'){
						$video_src   	= '//www.dailymotion.com/embed/video/'.$code;
					}else if($from=='vimeo'){
						$video_src   	= 'https://player.vimeo.com/video/'.$code;
					}
					$video_details[] 	= 	array('type'=>'share','from'=>$from,'video_link'=>$video_link,'video_src'=>$video_src);
					$data_vdo['video']	=	json_encode($video_details);
					$this->db->where('product_id',$id);
					$this->db->update('product',$data_vdo);	
				}
            } else {
                echo 'already uploaded maximum product';
            }
			$this->crud_model->set_category_data(0);
            recache();
        } else if ($para1 == "update") {
            $options = array();
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
            $num                        = $this->crud_model->get_type_name_by_id('product', $para2, 'num_of_imgs');
            $download                   = $this->crud_model->get_type_name_by_id('product', $para2, 'download');
            $data['title']              = $this->input->post('title');
            $data['category']           = $this->input->post('category');
            $data['description']        = $this->input->post('description');
            $data['sub_category']       = $this->input->post('sub_category');
            $data['sale_price']         = $this->input->post('sale_price');
            $data['purchase_price']     = $this->input->post('purchase_price');
            $data['tax']                = $this->input->post('tax');
            $data['discount']           = $this->input->post('discount');
            $data['discount_type']      = $this->input->post('discount_type');
            $data['tax_type']           = $this->input->post('tax_type');
            $data['tag']                = $this->input->post('tag');
			$data['update_time']        = time();
            $data['num_of_imgs']        = $num + $num_of_imgs;
            $data['front_image']        = $this->input->post('front_image');
            $additional_fields['name']  = json_encode($this->input->post('ad_field_names'));
            $additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
            $data['additional_fields']  = json_encode($additional_fields);
			
			//File upload
            $this->crud_model->file_up("images", "product", $para2, 'multi');
            if($_FILES['product_file']['name'] !== ''){
                $rand           = substr(hash('sha512', rand()), 0, 20);
                $name           = $para2.'_'.$rand.'_'.$_FILES['product_file']['name'];
                $data['download_name'] = $name;
                $folder = $this->db->get_where('general_settings', array('type' => 'file_folder'))->row()->value;
                move_uploaded_file($_FILES['product_file']['tmp_name'], 'uploads/file_products/' . $folder .'/' . $name);
            }
			
            $this->db->where('product_id', $para2);
            $this->db->update('product', $data);
			
			if($_FILES['logo']['name'] !== ''){
                $path = $_FILES['logo']['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$data_logo['logo'] 		 = 'digital_logo_'.$para2.'.'.$ext;
				$this->db->where('product_id' , $para2);
				$this->db->update('product' , $data_logo);
				$this->crud_model->file_up("logo", "digital_logo", $para2, '','no','.'.$ext);
            }
			
			//Requirements add
			$requirements				=	array();
			$req_title					=	$this->input->post('req_title');
			$req_desc					=	$this->input->post('req_desc');
			if(!empty($req_title)){
				foreach($req_title as $i => $row){
					$requirements[]			=	array('index'=>$i,'field'=>$row,'desc'=>$req_desc[$i]);
				}
			}
			$data_req['requirements']			=	json_encode($requirements);
			$this->db->where('product_id' , $para2);
			$this->db->update('product' , $data_req);
			
			//vdo upload
			$video_details				=	array();
			if($this->input->post('upload_method') == 'upload'){				
				$video 				= 	$_FILES['videoFile']['name'];
				$ext   				= 	pathinfo($video,PATHINFO_EXTENSION);
				move_uploaded_file($_FILES['videoFile']['tmp_name'],'uploads/video_digital_product/digital_'.$para2.'.'.$ext);
				$video_src 			= 	'uploads/video_digital_product/digital_'.$para2.'.'.$ext;
				$video_details[] 	= 	array('type'=>'upload','from'=>'local','video_link'=>'','video_src'=>$video_src);
				$data_vdo['video']	=	json_encode($video_details);
				$this->db->where('product_id',$para2);
				$this->db->update('product',$data_vdo);		
			}
			elseif ($this->input->post('upload_method') == 'share'){
				$video= json_decode($this->crud_model->get_type_name_by_id('product',$para2,'video'),true);
				if($video[0]['type'] == 'upload'){
					if(file_exists($video[0]['video_src'])){
						unlink($video[0]['video_src']);			
					}
				}
				$from 				= $this->input->post('site');
				$video_link 		= $this->input->post('video_link');
				$code				= $this->input->post('video_code');
				if($from=='youtube'){
					$video_src  	= 'https://www.youtube.com/embed/'.$code;
				}else if($from=='dailymotion'){
					$video_src   	= '//www.dailymotion.com/embed/video/'.$code;
				}else if($from=='vimeo'){
					$video_src   	= 'https://player.vimeo.com/video/'.$code;
				}
				$video_details[] 	= 	array('type'=>'share','from'=>$from,'video_link'=>$video_link,'video_src'=>$video_src);
				$data_vdo['video']	=	json_encode($video_details);
				$this->db->where('product_id',$para2);
				$this->db->update('product',$data_vdo);	
			}
			elseif ($this->input->post('upload_method') == 'delete'){
				$data_vdo['video']	=	'[]';
				$this->db->where('product_id',$para2);
				$this->db->update('product',$data_vdo);
				
				$video= json_decode($this->crud_model->get_type_name_by_id('product',$para2,'video'),true);
				if($video[0]['type'] == 'upload'){
					if(file_exists($video[0]['video_src'])){
						unlink($video[0]['video_src']);			
					}
				}
			}
			$this->crud_model->set_category_data(0);
			
            recache();
        } else if ($para1 == 'edit') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/digital_edit', $page_data);
        } else if ($para1 == 'view') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/digital_view', $page_data);
        } else if ($para1 == 'download_file') {
            $this->crud_model->download_product($para2);
        } else if ($para1 == 'can_download') {
            if($this->crud_model->can_download($para2)){
				echo "yes";
			} else{
				echo "no";
			}
        } elseif ($para1 == 'delete') {
            $this->crud_model->file_dlt('product', $para2, '.jpg', 'multi');
			unlink("uploads/digital_logo_image/" .$this->crud_model->get_type_name_by_id('product',$para2,'logo'));
			$video=$this->crud_model->get_type_name_by_id('product',$para2,'video');
			if($video!=='[]'){
				$video_details= json_decode($video,true);
				if($video_details[0]['type'] == 'upload'){
					if(file_exists($video_details[0]['video_src'])){
						unlink($video_details[0]['video_src']);			
					}
				}
			}
            $this->db->where('product_id', $para2);
            $this->db->delete('product');
			$this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('product_id', 'desc');
			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
			$this->db->where('download=','ok');
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $this->load->view('back/vendor/digital_list', $page_data);
        } elseif ($para1 == 'list_data') {
            $limit      = $this->input->get('limit');
            $search     = $this->input->get('search');
            $order      = $this->input->get('order');
            $offset     = $this->input->get('offset');
            $sort       = $this->input->get('sort');
            if($search){
                $this->db->like('title', $search, 'both');
            }
			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
			$this->db->where('download=','ok');
            $total= $this->db->get('product')->num_rows();
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
			$this->db->where('download=','ok');
            $products   = $this->db->get('product', $limit, $offset)->result_array();
            $data       = array();
            foreach ($products as $row) {

                $res    = array(
                             'image' => '',
                             'title' => '',
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

                //add html for action
                $res['options'] = "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('view','".translate('view_product')."','".translate('successfully_viewed!')."','digital_view','".$row['product_id']."');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                    ".translate('view')."
                            </a>
                            <a class=\"btn btn-purple btn-xs btn-labeled fa fa-tag\" data-toggle=\"tooltip\"
                                onclick=\"ajax_modal('add_discount','".translate('view_discount')."','".translate('viewing_discount!')."','add_discount','".$row['product_id']."')\" data-original-title=\"Edit\" data-container=\"body\">
                                    ".translate('discount')."
                            </a>
                            <a class=\"btn btn-mint btn-xs btn-labeled fa fa-download\" data-toggle=\"tooltip\" 
                                onclick=\"digital_download(".$row['product_id'].")\" data-original-title=\"Download\" data-container=\"body\">
                                    ".translate('download')."
                            </a>
                            
                            <a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('edit','".translate('edit_product_(_digital_product_)')."','".translate('successfully_edited!')."','digital_edit','".$row['product_id']."');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
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
            echo $this->crud_model->select_html('sub_category', 'sub_category', 'sub_category_name', 'add', 'demo-chosen-select required', '', 'category', $para2, '');
        } elseif ($para1 == 'product_by_sub') {
            echo $this->crud_model->select_html('product', 'product', 'title', 'add', 'demo-chosen-select required', '', 'sub_category', $para2, 'get_pro_res');
        } 
		elseif ($para1 == 'pur_by_pro') {
            echo $this->crud_model->get_type_name_by_id('product', $para2, 'purchase_price');
        }elseif ($para1 == 'add') {
            if($this->crud_model->can_add_product($this->session->userdata('vendor_id'))){
                $this->load->view('back/vendor/digital_add');
            } else {
                $this->load->view('back/vendor/product_limit');
            }
            //$this->load->view('back/vendor/digital_add');
        } elseif ($para1 == 'sale_report') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/product_sale_report', $data);
        } elseif ($para1 == 'add_discount') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/digital_add_discount', $data);
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
        }elseif ($para1 == 'video_preview') {
			if($para2 == 'youtube'){
				echo '<iframe width="400" height="300" src="https://www.youtube.com/embed/'.$para3.'" frameborder="0"></iframe>';
			}else if($para2 == 'dailymotion'){
				echo '<iframe width="400" height="300" src="//www.dailymotion.com/embed/video/'.$para3.'" frameborder="0"></iframe>';
			}else if($para2 == 'vimeo'){
				echo '<iframe src="https://player.vimeo.com/video/'.$para3.'" width="400" height="300" frameborder="0"></iframe>';
			}
		}else {
            $page_data['page_name']   = "digital";
            $this->db->order_by('product_id', 'desc');
			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
			$this->db->where('download=','ok');
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    
    /* Product Stock add, edit, view, delete, stock increase, decrease, discount */
    function stock($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->vendor_permission('stock')) {
            redirect(base_url() . 'index.php/vendor');
        }
        if ($para1 == 'do_add') {
            $data['type']         = 'add';
            $data['category']     = $this->input->post('category');
            $data['sub_category'] = $this->input->post('sub_category');
            $data['product']      = $this->input->post('product');
            $data['quantity']     = $this->input->post('quantity');
            $data['rate']         = $this->input->post('rate');
            $data['total']        = $this->input->post('total');
            $data['reason_note']  = $this->input->post('reason_note');
            $data['added_by']     = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $data['datetime']     = time();
            $this->db->insert('stock', $data);
            $prev_quantity          = $this->crud_model->get_type_name_by_id('product', $data['product'], 'current_stock');
            $data1['current_stock'] = $prev_quantity + $data['quantity'];
            $this->db->where('product_id', $data['product']);
            $this->db->update('product', $data1);
            recache();
        } else if ($para1 == 'do_destroy') {
            $data['type']         = 'destroy';
            $data['category']     = $this->input->post('category');
            $data['sub_category'] = $this->input->post('sub_category');
            $data['product']      = $this->input->post('product');
            $data['quantity']     = $this->input->post('quantity');
            $data['total']        = $this->input->post('total');
            $data['reason_note']  = $this->input->post('reason_note');
            $data['added_by']     = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
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
            recache();
        } elseif ($para1 == 'delete') {
            $quantity = $this->crud_model->get_type_name_by_id('stock', $para2, 'quantity');
            $product  = $this->crud_model->get_type_name_by_id('stock', $para2, 'product');
            $type     = $this->crud_model->get_type_name_by_id('stock', $para2, 'type');
            if ($type == 'add') {
                $this->crud_model->decrease_quantity($product, $quantity);
            } else if ($type == 'destroy') {
                $this->crud_model->increase_quantity($product, $quantity);
            }
            $this->db->where('stock_id', $para2);
            $this->db->delete('stock');
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('stock_id', 'desc');
			$this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $page_data['all_stock'] = $this->db->get('stock')->result_array();
            $this->load->view('back/vendor/stock_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/vendor/stock_add');
        } elseif ($para1 == 'destroy') {
            $this->load->view('back/vendor/stock_destroy');
        } elseif ($para1 == 'sub_by_cat') {
			$subcat_by_vendor= $this->crud_model->vendor_sub_categories($this->session->userdata('vendor_id'),$para2);
			$result = '';
			$result .=  "<select name=\"sub_category\" class=\"demo-chosen-select required\" onChange=\"get_product(this.value);\"><option value=\"\">".translate('select_sub_category')."</option>";
			foreach ($subcat_by_vendor as $row){
				$result .=  "<option value=\"".$row."\">".$this->crud_model->get_type_name_by_id('sub_category',$row,'sub_category_name')."</option>";
			}
			$result .=  "</select>";
			echo $result;
        }elseif ($para1 == 'pro_by_sub') {
			$product_by_vendor= $this->crud_model->vendor_products_by_sub($this->session->userdata('vendor_id'),$para2);
			$result = '';
			$result .=  "<select name=\"product\" class=\"demo-chosen-select required\" onChange=\"get_pro_res(this.value);\"><option value=\"\">".translate('select_product')."</option>";
			foreach ($product_by_vendor as $row){
				$result .=  "<option value=\"".$row."\">".$this->crud_model->get_type_name_by_id('product',$row,'title')."</option>";
			}

			$result .=  "</select>";
			echo $result;
        }
		else {
            $page_data['page_name'] = "stock";
            $page_data['all_stock'] = $this->db->get('stock')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    
    /* Managing sales by users */
   function sales($para1 = '', $para2 = '')
    {
		
        if (!$this->crud_model->vendor_permission('sale')) {
            redirect(base_url() . 'index.php/vendor');
        }
        if ($para1 == 'delete') {
            $carted = $this->db->get_where('stock', array(
                'sale_id' => $para2
            ))->result_array();
            foreach ($carted as $row2) {
                $this->stock('delete', $row2['stock_id']);
            }
            $this->db->where('sale_id', $para2);
            $this->db->delete('sale');
        } elseif ($para1 == 'list') {
             if($para2=="vendor_search"){
                 
                 
            }
            $all = $this->db->get_where('sale',array('payment_type' => 'go'))->result_array();
            foreach ($all as $row) {
                if((time()-$row['sale_datetime']) > 600){
                    $this->db->where('sale_id', $row['sale_id']);
                    $this->db->delete('sale');
                }
            }
            $this->db->order_by('sale_id', 'desc');
			 $this->db->where('cancel_status', '0');
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            $this->load->view('back/vendor/sales_list', $page_data);
        } 
		elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/sales_view', $page_data);
        } elseif ($para1 == 'send_invoice') {
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $text              = $this->load->view('back/includes_top', $page_data);
            $text .= $this->load->view('back/vendor/sales_view', $page_data);
            $text .= $this->load->view('back/includes_bottom', $page_data);
        } elseif ($para1 == 'delivery_agent') {
          //  $page_data['page_name'] = "business_settings";
          //  $this->load->view('back/index', $page_data);
          $page_data['sale_id']         = $para2;
                        $page_data['page_name'] = "delivery_agent";
                 
           // print_r($page_data); exit;
             $this->load->view('back/vendor/delivery_agent', $page_data);
        }
		elseif ($para1 == 'delivery_payment') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale_id']         = $para2;
            $page_data['payment_type']    = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->payment_type;
            $page_data['payment_details'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->payment_details;
            $delivery_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->delivery_status,true);
            foreach ($delivery_status as $row) {
                if(isset($row['vendor'])){
                    if($row['vendor'] == $this->session->userdata('vendor_id')){
                        $page_data['delivery_status'] = $row['status'];
                    }
                }
            }
            $payment_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->payment_status,true);
            foreach ($payment_status as $row) {
                if(isset($row['vendor'])){
                    if($row['vendor'] == $this->session->userdata('vendor_id')){
                        $page_data['payment_status'] = $row['status'];
                    }
                }
            }
            
            $this->load->view('back/vendor/sales_delivery_payment', $page_data);
        } elseif ($para1 == 'delivery_agent_set') {
          
            $data['delivery_agent_id'] = $this->input->post('delivery_agent');
			$data['delivery_pickup_date'] = $this->input->post('delivery_pickup_date');
			$data['delivery_pickup_time'] = $this->input->post('delivery_pickup_time');
			$courrer_email = $this->db->get_where('delivery_agent', array('agent_id' =>$data['delivery_agent_id']))->result_array();
			$courreradmin_email = $this->db->get_where('admin', array('admin_id' =>'1'))->result_array();
			//echo '<pre>'; print_r($courreradmin_email); exit;
			$courrer_product_id = $this->db->get('sale')->result_array();
			$new=json_decode($courrer_product_id[0]['product_details']);
			$courrer_product_id = json_decode($this->db->get('sale')->row()->product_details,true);
			$p_id=$courrer_product_id['id'];
			$courrier_product_name = $this->db->get_where('product', array('product_id' =>$p_id))->result_array();
			$this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
			
			$from='oyabuy.net';
		$to=$courrer_email[0]['email'];
		 $subject='Acknowledgement for Product Courrier';
		$message="<html><head><meta http-equiv=Content-Type content=text/html; charset=utf-8/><title>Oyabuy.net</title>
</head><body><table width=500 cellpadding=0 cellspacing=0 border=0 bgcolor=#F49E23 style=border:solid 10px #A5DCFF;><tr bgcolor=#FFFFFF height=25><td><table width=500 cellpadding=0 cellspacing=0 border=0 bgcolor=#F49E23 style=border:solid 10px #a5dcff;><tr bgcolor=#FFFFFF height=30><td height=27 valign=top style=font-family:Arial; font-size:12px; line-height:18px; text-decoration:none; color:#000000; padding-left:20px;><b>Product Courrier Assign Acknowledgement</b></td></tr><tr bgcolor=#FFFFFF height=35><td height=24 style=padding-left:20px; font-family:arial; font-size:11px; line-height:18px; text-decoration:none; color:#000000;>This is an Acknowledgement for product courrier assign to '".$courrer_email[0]['agent_name']."' for delivery</td></tr><tr bgcolor=#FFFFFF height=35><td height=23 style=padding-left:20px; font-family:arial; font-size:11px; line-height:18px; text-decoration:none; color:#000000;>Thanks for using oyabuy.net</td></tr></table></td></tr></table><body/><html/>";

			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
			$headers .= "From: Oyabuy.net <info@oyabuy.net>\r\n";
			$to1=$courreradmin_email[0]['email'];
			
			$email_response = mail($to,$subject,$message,$headers);
			
			$email_response2 = mail($to1,$subject,$message,$headers);
			
			
			//echo $this->db->last_query();
        }elseif ($para1 == 'delivery_payment_set') {
            $delivery_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->delivery_status,true);
            $new_delivery_status = array();
            foreach ($delivery_status as $row) {
                if(isset($row['vendor'])){
                    if($row['vendor'] == $this->session->userdata('vendor_id')){
                        $new_delivery_status[] = array('vendor'=>$row['vendor'],'status'=>$this->input->post('delivery_status'),'delivery_time'=>$row['delivery_time']);
                    } else {
                        $new_delivery_status[] = array('vendor'=>$row['vendor'],'status'=>$row['status'],'delivery_time'=>$row['delivery_time']);
                    }
                }
                else if(isset($row['admin'])){
                    $new_delivery_status[] = array('admin'=>'','status'=>$row['status'],'delivery_time'=>$row['delivery_time']);
                }
            }
            $payment_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->payment_status,true);
            $new_payment_status = array();
            foreach ($payment_status as $row) {
                if(isset($row['vendor'])){
                    if($row['vendor'] == $this->session->userdata('vendor_id')){
                        $new_payment_status[] = array('vendor'=>$row['vendor'],'status'=>$this->input->post('payment_status'));
                    } else {
                        $new_payment_status[] = array('vendor'=>$row['vendor'],'status'=>$row['status']);
                    }
                }
                else if(isset($row['admin'])){
                    $new_payment_status[] = array('admin'=>'','status'=>$row['status']);
                }
            }
            if($this->input->post('delivery_status')=="delivered") {
				if($this->db->get_where('sale', array('sale_id' => $para2))->row()->cash_pack_status=='pending'){
				$data['cash_pack_status']     = 'success';
				$user_id = $this->db->get_where('sale', array('sale_id' => $para2))->row()->buyer;
				$cash_pack = $this->db->get_where('sale', array('sale_id' => $para2))->row()->cash_pack;
				$this->wallet_model->add_reward_balance($cash_pack,$user_id);
				}
			}
            var_dump($new_payment_status);
            $data['payment_status']  = json_encode($new_payment_status);
            $data['delivery_status'] = json_encode($new_delivery_status);
            $data['payment_details'] = $this->input->post('payment_details');
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/vendor/sales_add');
        }elseif ($para1 == 'vendor_search') {
         echo $para2;
         $this->session->set_userdata('store_id', $para2);
          echo $this->session->userdata('store_id');
        //echo 1;
        }  elseif ($para1 == 'total') {
            $sales = $this->db->get('sale')->result_array();
			$i = 0;
			foreach($sales as $row){
				if($this->crud_model->is_sale_of_vendor($row['sale_id'],$this->session->userdata('vendor_id'))){
					$i++;
				}
			}
			echo $i;
        } else {
            $page_data['page_name']      = "sales";
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
	
	
	function cancel_sales($para1 = '', $para2 = '')
    {
		
        if (!$this->crud_model->vendor_permission('sale')) {
            redirect(base_url() . 'index.php/vendor');
        }
        if ($para1 == 'list') {
            $all = $this->db->get_where('sale',array('payment_type' => 'go'))->result_array();
            foreach ($all as $row) {
                if((time()-$row['sale_datetime']) > 600){
                    $this->db->where('sale_id', $row['sale_id']);
                    $this->db->delete('sale');
                }
            }
            $this->db->order_by('sale_id', 'desc');
			 $this->db->where('cancel_status', '1');
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            $this->load->view('back/vendor/cancel_sales_list', $page_data);
        } 
		elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/sales_view', $page_data);
        } 
		else {
            $page_data['page_name']      = "cancel_sales";
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    
	/* Payments From Admin */
	
	function admin_payments($para1='', $para2=''){
		if(!$this->crud_model->vendor_permission('pay_to_vendor')){
			redirect(base_url() . 'index.php/vendor');
		}
		if($para1 == 'list'){
			$this->db->order_by('vendor_invoice_id','desc');
			$page_data['payment_list']	= $this->db->get_where('vendor_invoice',array('vendor_id' => $this->session->userdata('vendor_id')))->result_array();
			$this->load->view('back/vendor/admin_payments_list',$page_data);
		}
        else if($para1 == 'view'){
            $page_data['details']  = $this->db->get_where('vendor_invoice',array('vendor_id' => $this->session->userdata('vendor_id'), 'vendor_invoice_id' => $para2))->result_array();
            $this->load->view('back/vendor/admin_payments_view',$page_data);
        }
		else{
			$page_data['page_name'] = 'admin_payments';
			$this->load->view('back/index',$page_data);
		}
		
	}
	
	/* Package Upgrade History */ 
	
	function upgrade_history($para1='',$para2=''){
		if(!$this->crud_model->vendor_permission('business_settings')){
			redirect(base_url() . 'index.php/vendor');
		}
		if($para1=='list'){
			$this->db->order_by('membership_payment_id','desc');
			$page_data['package_history']	= $this->db->get_where('membership_payment',array('vendor' => $this->session->userdata('vendor_id')))->result_array();
			$this->load->view('back/vendor/upgrade_history_list',$page_data);
		}
		else if($para1 == 'view'){
			$page_data['upgrade_history_data'] = $this->db->get_where('membership_payment',array('membership_payment_id' => $para2))->result_array();
			$this->load->view('back/vendor/upgrade_history_view',$page_data);
		}
		else{
			$page_data['page_name'] = 'upgrade_history';
			$this->load->view('back/index',$page_data);
		}
	}
	
    /* Checking Login Stat */
    function is_logged()
    {
        if ($this->session->userdata('vendor_login') == 'yes') {
            echo 'yah!good';
        } else {
            echo 'nope!bad';
        }
    }
    
    /* Manage Site Settings */
    function site_settings($para1 = "")
    {
        if (!$this->crud_model->vendor_permission('site_settings')) {
            redirect(base_url() . 'index.php/vendor');
        }
        $page_data['page_name'] = "site_settings";
        $page_data['tab_name']  = $para1;
        $this->load->view('back/index', $page_data);
    }
    

    /* Manage Business Settings */
    function package($para1 = "", $para2 = "")
    {
        if ($para1 == 'upgrade') {
            $method         = $this->input->post('method');
            $type           = $this->input->post('membership');
            $vendor         = $this->session->userdata('vendor_id');
            if($type !== '0'){
                $amount         = $this->db->get_where('membership',array('membership_id'=>$type))->row()->price;
                $amount_in_usd  = $amount/exchange('usd');
                if ($method == 'paypal') {

                    $paypal_email           = $this->db->get_where('business_settings',array('type'=>'paypal_email'))->row()->value;
                    $data['vendor']         = $vendor;
                    $data['amount']         = $amount;
                    $data['status']         = 'due';
                    $data['method']         = 'paypal';
                    $data['membership']     = $type; 
                    $data['timestamp']      = time();

                    $this->db->insert('membership_payment', $data);
                    $invoice_id           = $this->db->insert_id();
                    $this->session->set_userdata('invoice_id', $invoice_id);
                    
                    /****TRANSFERRING USER TO PAYPAL TERMINAL****/
                    $this->paypal->add_field('rm', 2);
                    $this->paypal->add_field('no_note', 0);
                    $this->paypal->add_field('cmd', '_xclick');
                    
                    $this->paypal->add_field('amount', $this->cart->format_number($amount_in_usd));

                    //$this->paypal->add_field('amount', $grand_total);
                    $this->paypal->add_field('custom', $invoice_id);
                    $this->paypal->add_field('business', $paypal_email);
                    $this->paypal->add_field('notify_url', base_url() . 'index.php/vendor/paypal_ipn');
                    $this->paypal->add_field('cancel_return', base_url() . 'index.php/vendor/paypal_cancel');
                    $this->paypal->add_field('return', base_url() . 'index.php/vendor/paypal_success');
                    
                    $this->paypal->submit_paypal_post();
                    // submit the fields to paypal

                }else if ($method == 'c2') {
                    $data['vendor']         = $vendor;
                    $data['amount']         = $amount;
                    $data['status']         = 'due';
                    $data['method']         = 'c2';
                    $data['membership']     = $type; 
                    $data['timestamp']      = time();

                    $this->db->insert('membership_payment', $data);
                    $invoice_id           = $this->db->insert_id();
                    $this->session->set_userdata('invoice_id', $invoice_id);

                    $c2_user = $this->db->get_where('business_settings',array('type' => 'c2_user'))->row()->value; 
                    $c2_secret = $this->db->get_where('business_settings',array('type' => 'c2_secret'))->row()->value;
                    

                    $this->twocheckout_lib->set_acct_info($c2_user, $c2_secret, 'Y');
                    $this->twocheckout_lib->add_field('sid', $this->twocheckout_lib->sid);              //Required - 2Checkout account number
                    $this->twocheckout_lib->add_field('cart_order_id', $invoice_id);   //Required - Cart ID
                    $this->twocheckout_lib->add_field('total',$this->cart->format_number($amount_in_usd));          
                    
                    $this->twocheckout_lib->add_field('x_receipt_link_url', base_url().'index.php/vendor/twocheckout_success');
                    $this->twocheckout_lib->add_field('demo', $this->twocheckout_lib->demo);                    //Either Y or N
                    
                    $this->twocheckout_lib->submit_form();
                }else if($method == 'vp'){
                    $vp_id                  = $this->db->get_where('business_settings',array('type'=>'vp_merchant_id'))->row()->value;
                    $data['vendor']         = $vendor;
                    $data['amount']         = $amount;
                    $data['status']         = 'due';
                    $data['method']         = 'vouguepay';
                    $data['membership']     = $type; 
                    $data['timestamp']      = time();

                    $this->db->insert('membership_payment', $data);
                    $invoice_id           = $this->db->insert_id();
                    $this->session->set_userdata('invoice_id', $invoice_id);

                    /****TRANSFERRING USER TO vouguepay TERMINAL****/
                    $this->vouguepay->add_field('v_merchant_id', $vp_id);
                    $this->vouguepay->add_field('merchant_ref', $invoice_id);
                    $this->vouguepay->add_field('memo', 'Package Upgrade to '.$type);
                    //$this->vouguepay->add_field('developer_code', $developer_code);
                    //$this->vouguepay->add_field('store_id', $store_id);

                    
                    $this->vouguepay->add_field('total', $amount);

                    //$this->vouguepay->add_field('amount', $grand_total);
                    //$this->vouguepay->add_field('custom', $sale_id);
                    //$this->vouguepay->add_field('business', $vouguepay_email);

                    $this->vouguepay->add_field('notify_url', base_url() . 'index.php/vendor/vouguepay_ipn');
                    $this->vouguepay->add_field('fail_url', base_url() . 'index.php/vendor/vouguepay_cancel');
                    $this->vouguepay->add_field('success_url', base_url() . 'index.php/vendor/vouguepay_success');
                    
                    $this->vouguepay->submit_vouguepay_post();
                    // submit the fields to vouguepay
                } else if ($method == 'stripe') {
                    if($this->input->post('stripeToken')) {
                        
                        $stripe_api_key = $this->db->get_where('business_settings' , array('type' => 'stripe_secret'))->row()->value;
                        require_once(APPPATH . 'libraries/stripe-php/init.php');
                        \Stripe\Stripe::setApiKey($stripe_api_key); //system payment settings
                        $vendor_email = $this->db->get_where('vendor' , array('vendor_id' => $vendor))->row()->email;
                        
                        $vendora = \Stripe\Customer::create(array(
                            'email' => $vendor_email, // customer email id
                            'card'  => $_POST['stripeToken']
                        ));

                        $charge = \Stripe\Charge::create(array(
                            'customer'  => $vendora->id,
                            'amount'    => ceil($amount_in_usd*100),
                            'currency'  => 'USD'
                        ));

                        if($charge->paid == true){
                            $vendora = (array) $vendora;
                            $charge = (array) $charge;
                            
                            $data['vendor']         = $vendor;
                            $data['amount']         = $amount;
                            $data['status']         = 'paid';
                            $data['method']         = 'stripe';
                            $data['timestamp']      = time();
                            $data['membership']     = $type;
                            $data['details']        = "Customer Info: \n".json_encode($vendora,true)."\n \n Charge Info: \n".json_encode($charge,true);
                            
                            $this->db->insert('membership_payment', $data);
                            $this->crud_model->upgrade_membership($vendor,$type);
                            redirect(base_url() . 'index.php/vendor/package/', 'refresh');
                        } else {
                            $this->session->set_flashdata('alert', 'unsuccessful_stripe');
                            redirect(base_url() . 'index.php/vendor/package/', 'refresh');
                        }
                        
                    } else{
                        $this->session->set_flashdata('alert', 'unsuccessful_stripe');
                        redirect(base_url() . 'index.php/vendor/package/', 'refresh');
                    }
                } else if ($method == 'cash') {
                    $data['vendor']         = $vendor;
                    $data['amount']         = $amount;
                    $data['status']         = 'due';
                    $data['method']         = 'cash';
                    $data['timestamp']      = time();
                    $data['membership']     = $type;
                    $this->db->insert('membership_payment', $data);
                    redirect(base_url() . 'index.php/vendor/package/', 'refresh');
                } else {
                    echo 'putu';
                }
            } else {
                redirect(base_url() . 'index.php/vendor/package/', 'refresh');
            }
        } else {
            $page_data['page_name'] = "package";
            $this->load->view('back/index', $page_data);
        }
    }
    
    /* FUNCTION: Verify paypal payment by IPN*/
    function paypal_ipn()
    {
        if ($this->paypal->validate_ipn() == true) {
            
            $data['status']         = 'paid';
            $data['details']        = json_encode($_POST);
            $invoice_id             = $_POST['custom'];
            $this->db->where('membership_payment_id', $invoice_id);
            $this->db->update('membership_payment', $data);
            $type = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->membership;
            $vendor = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->vendor;
            $this->crud_model->upgrade_membership($vendor,$type);
        }
    }
    

    /* FUNCTION: Loads after cancelling paypal*/
    function paypal_cancel()
    {
        $invoice_id = $this->session->userdata('invoice_id');
        $this->db->where('membership_payment_id', $invoice_id);
        $this->db->delete('membership_payment');
        $this->session->set_userdata('invoice_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'index.php/vendor/package/', 'refresh');
    }
    
    /* FUNCTION: Loads after successful paypal payment*/
    function paypal_success()
    {
        $this->session->set_userdata('invoice_id', '');
        redirect(base_url() . 'index.php/vendor/package/', 'refresh');
    }
    
    function twocheckout_success()
    {

        /*$this->twocheckout_lib->set_acct_info('532001', 'tango', 'Y');*/
        $c2_user = $this->db->get_where('business_settings',array('type' => 'c2_user'))->row()->value; 
        $c2_secret = $this->db->get_where('business_settings',array('type' => 'c2_secret'))->row()->value;
        
        $this->twocheckout_lib->set_acct_info($c2_user, $c2_secret, 'Y');
        $data2['response'] = $this->twocheckout_lib->validate_response();
        //var_dump($this->twocheckout_lib->validate_response());
        $status = $data2['response']['status'];
        if ($status == 'pass') {
            $data1['status']             = 'paid';
            $data1['details']   = json_encode($this->twocheckout_lib->validate_response());
            $invoice_id         = $this->session->userdata('invoice_id');
            $this->db->where('membership_payment_id', $invoice_id);
            $this->db->update('membership_payment', $data1);
            $type = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->membership;
            $vendor = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->vendor;
            $this->crud_model->upgrade_membership($vendor,$type);
            redirect(base_url() . 'index.php/vendor/package/', 'refresh');

        } else {
            //var_dump($data2['response']);
            $invoice_id = $this->session->userdata('invoice_id');
            $this->db->where('membership_payment_id', $invoice_id);
            $this->db->delete('membership_payment');
            $this->session->set_userdata('invoice_id', '');
            $this->session->set_flashdata('alert', 'payment_cancel');
            redirect(base_url() . 'index.php/vendor/package', 'refresh');
        }
    }
 /* FUNCTION: Verify vouguepay payment by IPN*/
    function vouguepay_ipn()
    {
        $res = $this->vouguepay->validate_ipn();
        $invoice_id = $res['merchant_ref'];
        $merchant_id = 'demo';

        if ($res['total'] !== 0 && $res['status'] == 'Approved' && $res['merchant_id'] == $merchant_id) {
            $data['status']         = 'paid';
            $data['details']        = json_encode($res);
            $this->db->where('membership_payment_id', $invoice_id);
            $this->db->update('membership_payment', $data);
        }
    }
    
    /* FUNCTION: Loads after cancelling vouguepay*/
    function vouguepay_cancel()
    {
        $invoice_id = $this->session->userdata('invoice_id');
        $this->db->where('membership_payment_id', $invoice_id);
        $this->db->delete('membership_payment');
        $this->session->set_userdata('invoice_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'index.php/vendor/package/', 'refresh');
    }
    
    /* FUNCTION: Loads after successful vouguepay payment*/
    function vouguepay_success()
    {
        $this->session->set_userdata('invoice_id', '');
        redirect(base_url() . 'index.php/vendor/package/', 'refresh');
    }
    /* Manage Business Settings */
    function business_settings($para1 = "", $para2 = "")
    {
        if (!$this->crud_model->vendor_permission('business_settings')) {
            redirect(base_url() . 'index.php/vendor');
        }
        if ($para1 == "cash_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'cash_set' => $val
            ));
            recache();
        }
        else if ($para1 == "paypal_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'paypal_set' => $val
            ));
            recache();
        }
		 else if ($para1 == "pum_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'pum_set' => $val
            ));
            recache();
        }
        else if ($para1 == "stripe_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'stripe_set' => $val
            ));
            recache();
        }
		else if ($para1 == "c2_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'c2_set' => $val
            ));
            recache();
        }
        else if ($para1 == "vp_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'vp_set' => $val
            ));
            recache();
        }
        else if ($para1 == "membership_price") {
            echo $this->db->get_where('membership',array('membership_id'=>$para2))->row()->price;
        }
        	else if ($para1 == "membership_info") {
            $return = '<div class="table-responsive"><table class="table table-striped">';
            if($para2 !== '0'){
                $results = $this->db->get_where('membership',array('membership_id'=>$para2))->result_array();
                foreach ($results as $row) {
                    $return .= '<tr>';
                    $return .= '<td>'.translate('title').'</td>';
                    $return .= '<td>'.$row['title'].'</td>';
                    $return .= '</tr>';
					if($row['title']=='Standard')
					{
                    $return .= '<tr>';
                    $return .= '<td>'.translate('price').'</td>';
                    //$return .= '<td>'.currency($row['price'],'def').'</td>';
					$return .= '<td> NGN'.$row['price'].'</td>';
                    $return .= '</tr>';
					}
					if($row['title']=='Premium')
					{
					$return .= '<tr>';
                    $return .= '<td>'.translate('price').'</td>';
                    //$return .= '<td>'.currency($row['price'],'def').'</td>';
					$return .= '<td>'.$row['price'].'</td>';
                    $return .= '</tr>';
					}
                    $return .= '<tr>';
                    $return .= '<td>'.translate('timespan').'</td>';
                    $return .= '<td>'.$row['timespan'].'</td>';
                    $return .= '</tr>';

                    $return .= '<tr>';
                    $return .= '<td>'.translate('maximum_product').'</td>';
                    $return .= '<td>'.$row['product_limit'].'</td>';
                    $return .= '</tr>';
					
					if($row['title']=='Standard')
					{
					$return .= '<tr>';
                    $return .= '<td>'.translate('Promo Ads').'</td>';
                    $return .= '<td>'.$row['prom_ads'].'</td>';
                    $return .= '</tr>';
					}
					if($row['title']=='Premium')
					{
					$return .= '<tr>';
                    $return .= '<td>'.translate('Ads Plan').'</td>';
                    $return .= '<td>'.$row['ads_plan'].'</td>';
                    $return .= '</tr>';
					}
					if($row['title']=='Premium')
					{
					$return .= '<tr>';
                    $return .= '<td>'.translate('Delivery plan').'</td>';
                    $return .= '<td>'.$row['delivary_plan'].'</td>';
                    $return .= '</tr>';
					}
                }
            } else if($para2 == '0'){
                $return .= '<tr>';
                $return .= '<td>'.translate('title').'</td>';
                $return .= '<td>'.translate('default').'</td>';
                $return .= '</tr>';

                $return .= '<tr>';
                $return .= '<td>'.translate('price').'</td>';
                $return .= '<td>'.translate('free').'</td>';
                $return .= '</tr>';

                $return .= '<tr>';
                $return .= '<td>'.translate('timespan').'</td>';
                $return .= '<td>'.translate('lifetime').'</td>';
                $return .= '</tr>';

                $return .= '<tr>';
                $return .= '<td>'.translate('maximum_product').'</td>';
                $return .= '<td>'.$this->db->get_where('general_settings',array('type'=>'default_member_product_limit'))->row()->value.'</td>';
                $return .= '</tr>';
            }
            $return .= '</table></div>';
            echo $return;
        }
       
	   
	    else if ($para1 == 'set') {
            $publishable    = $this->input->post('stripe_publishable');
            $secret         = $this->input->post('stripe_secret');
            $stripe         = json_encode(array('publishable'=>$publishable,'secret'=>$secret));
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'paypal_email' => $this->input->post('paypal_email')
            ));
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'stripe_details' => $stripe
            ));
			$this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'c2_user' => $this->input->post('c2_user'),
                'c2_secret' => $this->input->post('c2_secret'),
            ));
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'vp_merchant_id' => $this->input->post('vp_merchant_id')
            ));
			$this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'pum_merchant_key' => $this->input->post('pum_merchant_key')
            ));
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'pum_merchant_salt' => $this->input->post('pum_merchant_salt')
            ));
			
			$this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array('bank_name' => $this->input->post('bank_name'),
											  'b_name' => $this->input->post('b_name'),
											  'bank_branch' => $this->input->post('bank_branch'),
											  'account_no' => $this->input->post('account_no'),
											  'ifsc_code' => $this->input->post('ifsc_code')
            ));
            recache();
        } else {
            $page_data['page_name'] = "business_settings";
            $this->load->view('back/index', $page_data);
        }
    }
    

    /* Manage vendor Settings */
    function manage_vendor($para1 = "")
    {
        if ($this->session->userdata('vendor_login') != 'yes') {
            redirect(base_url() . 'index.php/vendor');
        }
        if ($para1 == 'update_password') {
            $user_data['password'] = $this->input->post('password');
            $account_data          = $this->db->get_where('vendor', array(
                'vendor_id' => $this->session->userdata('vendor_id')
            ))->result_array();
            foreach ($account_data as $row) {
                if (sha1($user_data['password']) == $row['password']) {
                    if ($this->input->post('password1') == $this->input->post('password2')) {
                        $data['password'] = sha1($this->input->post('password1'));
                        $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
                        $this->db->update('vendor', $data);
                        echo 'updated';
                    }
                } else {
                    echo 'pass_prb';
                }
            }
        } else if ($para1 == 'update_profile') {
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'address1' => $this->input->post('address1'),
                'address2' => $this->input->post('address2'),
                'company' => $this->input->post('company'),
                'display_name' => $this->input->post('display_name'),
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'country' => $this->input->post('country'),
				'zip' => $this->input->post('zip'),
				
                'details' => $this->input->post('details'),
                'phone' => $this->input->post('phone'),
                'lat_lang' => $this->input->post('lat_lang'),
				
				
                        
				'store_city' => $this->input->post('city'),
                'store_street' => $this->input->post('address1'),
                'store_district' => $this->input->post('state'),
				'store_country' => $this->input->post('country'),
                'store_email' => $this->input->post('email'),
                'store_phone' => $this->input->post('phone')
                
            ));
        } else {
            $page_data['page_name'] = "manage_vendor";
            $this->load->view('back/index', $page_data);
        }
    }

    /* Manage General Settings */
    function general_settings($para1 = "", $para2 = "")
    {
        if (!$this->crud_model->vendor_permission('site_settings')) {
            redirect(base_url() . 'index.php/vendor');
        }

    }
    
    /* Manage Social Links */
    function social_links($para1 = "")
    {
        if (!$this->crud_model->vendor_permission('site_settings')) {
            redirect(base_url() . 'index.php/vendor');
        }
        if ($para1 == "set") {

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'facebook' => $this->input->post('facebook')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'google_plus' => $this->input->post('google-plus')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'twitter' => $this->input->post('twitter')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'skype' => $this->input->post('skype')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'pinterest' => $this->input->post('pinterest')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'youtube' => $this->input->post('youtube')
				
				
            ));
			$this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'instagram' => $this->input->post('instagram')
				
				
            ));
            recache();
            redirect(base_url() . 'index.php/vendor/site_settings/social_links/', 'refresh');
        
        }
    }

    /* Manage SEO relateds */
    function seo_settings($para1 = "")
    {
        if (!$this->crud_model->vendor_permission('site_settings')) {
            redirect(base_url() . 'index.php/vendor');
        }
        if ($para1 == "set") {
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'description' => $this->input->post('description')
            ));
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'keywords' => $this->input->post('keywords')
            ));
            recache();
        }
    }
    /* Manage Favicons */
    function vendor_images($para1 = "")
    {
        if (!$this->crud_model->vendor_permission('site_settings')) {
            redirect(base_url() . 'index.php/vendor');
        }
        move_uploaded_file($_FILES["logo"]['tmp_name'], 'uploads/vendor_logo_image/logo_' . $this->session->userdata('vendor_id') . '.png');
        move_uploaded_file($_FILES["banner"]['tmp_name'], 'uploads/vendor_banner_image/banner_' . $this->session->userdata('vendor_id') . '.jpg');
        recache();
    }
	
	function min_bidd($para1 = '')
    {
        if (!$this->crud_model->vendor_permission('site_settings')) {
            redirect(base_url() . 'index.php/vendor');
        }
		//Secho $para1; 
		$stage1=0;
		$page_data['bidd'] = $this->db->get_where('bidding_history', array('id' => $para1))->result_array();
      //echo '<pre>'; print_r($page_data['bidd']);
		     $uid=$page_data['bidd'][0]['uid'];
		   $pid=$page_data['bidd'][0]['pid'];
		  
		   $page_data['bidd_deatils'] = $this->db->get_where('bidding_history', array('id !=' => $para1,'payment_status'=> 1,'status'=> 1,'final_bidder'=> 0,'batch_no'=> 0,'pid'=> $pid))->result_array();
		   
		   //echo '<pre>'; print_r($page_data['bidd_deatils']); 
		   //echo count($page_data['bidd_deatils']); 
		   for($i=0; $i<count($page_data['bidd_deatils']); $i++ )
		   {
			   $p_uid=$page_data['bidd_deatils'][$i]['uid']; 
			   
			   $p_amt=$page_data['bidd_deatils'][$i]['bid_amt']; 
			   
			   $ussr['old_amt'] = $this->db->get_where('user', array('user_id' => $p_uid))->result_array();
			   
			    $old_amt= $ussr['old_amt'][0]['wallet']; 
			   
			    $new_user_balance=$old_amt+$p_amt;

						   		
				$data3['wallet']=$new_user_balance;
				$this->db->where('user_id',$p_uid);
                $this->db->update('user',$data3);
				
				$data4['uid'] =  $p_uid;
				$data4['description'] = 'Bidd Amount '.$p_amt.' Refunded Your Wallet Sucessfully';
            	$this->db->insert('user_log',$data4);
		   
		   }
		

			$data['final_bidder']=1; 
		    $this->db->where('id',$para1);
			$this->db->where('pid',$pid);
		    $this->db->where('payment_status',1);
			$this->db->where('status',1);
			$this->db->where('final_bidder',0);
			$this->db->where('batch_no',0);
            $this->db->update('bidding_history',$data);
			
			$data1['final_bidder']=2; 
			$this->db->where('pid',$pid);
		    $this->db->where('id !=',$para1);
		    $this->db->where('payment_status',1);
			$this->db->where('status',1);
			$this->db->where('final_bidder',0);
			$this->db->where('batch_no',0);
            $this->db->update('bidding_history',$data1);
			
			
			$this->db->select_max('batch_no');
	       $biid = $this->db->get('bidding_history')->row();  
		   $new_bidd=$biid->batch_no+1;
		   $data2['batch_no']=$new_bidd; 
		    $this->db->where('pid',$pid);
		    $this->db->where('payment_status',1);
			$this->db->where('status',1);
			$this->db->where('batch_no',0);
            $this->db->update('bidding_history',$data2);
		
			
			$category_id= $this->db->get_where('product', array('product_id' =>$pid))->result_array();
			  $cat_id=$category_id[0]['category'];
			  $sub_id=$category_id[0]['sub_category'];
			  
			  $vendor_id=$this->session->userdata('vendor_id');
			  $data5['product'] =  $pid;
			   $data5['type'] =  'destroy';
			   $data5['category'] =  $cat_id;
			   $data5['sub_category'] =  $sub_id;
			   $data5['quantity'] =  1;
			   $data5['rate'] =  $p_amt;
			   $data5['added_by'] =  '{"type":"vendor","id":"'.$vendor_id.'"}';
            	$this->db->insert('stock',$data5);
			
			$currenct_stock= $this->db->get_where('product', array('product_id' =>$pid))->result_array();
					
				$new_stock=	$currenct_stock[0]['current_stock']-1;
				$da['current_stock']=$new_stock;
				$this->db->where('product_id',$pid);
	            $this->db->update('product',$da);	
		
			echo 'Sucess';
			
		   
    }
	public function product_bulk_upload()
    {
        if (!$this->crud_model->vendor_permission('product')) {
            redirect(base_url() . 'vendor');
        }

        $physical_categories =  $this->db->where('digital',null)->or_where('digital','')->get('category')->result_array();
        $physical_sub_categories =  $this->db->where('digital',null)->or_where('digital','')->get('sub_category')->result_array();
        $digital_categories =  $this->db->where('digital','ok')->get('category')->result_array();
        $digital_sub_categories =  $this->db->where('digital','ok')->get('sub_category')->result_array();
        $brands =  $this->db->get('brand')->result_array();

        $page_data['page_name'] = "product_bulk_upload";
        $page_data['physical_categories'] = $physical_categories;
        $page_data['physical_sub_categories'] = $physical_sub_categories;
        $page_data['digital_categories'] = $digital_categories;
        $page_data['digital_sub_categories'] = $digital_sub_categories;
        $page_data['brands'] = $brands;

        $this->load->view('back/index', $page_data);

    }

    public function product_bulk_upload_save()
    {
       if(!file_exists($_FILES['bulk_file']['tmp_name']) || !is_uploaded_file($_FILES['bulk_file']['tmp_name'])){
            $this->session->set_flashdata('error',translate('File is not selected'));
            redirect('admin/product_bulk_upload');
        }
       ini_set("memory_limit", "-1");
		ini_set('upload_max_size' , '20480M');
		ini_set('post_max_size', '20480M');
			$path = 'uploads/';
			require_once APPPATH . "/third_party/PHPExcel.php";
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'xlsx|xls|csv';
			$config['remove_spaces'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);            
			if (!$this->upload->do_upload('bulk_file')) {
				$error = array('error' => $this->upload->display_errors());
			} else {
				$data = array('upload_data' => $this->upload->data());
			}
			if(empty($error)){
			  if (!empty($data['upload_data']['file_name'])) {
				$import_xls_file = $data['upload_data']['file_name'];
			} else {
				$import_xls_file = 0;
			}
	        $inputFileName = $path . $import_xls_file; 
		
			
			
			try {
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
				$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
				$flag = true;
				$i=0;
				$inc = 0;
				$image_url = array();
				foreach ($allDataInSheet as $value) {
				    //echo "<pre>".print_r($allDataInSheet)."</pre>";
				    $inc++;
				    if($value['A']!='Product Name*')
				    {
				        
				    if($inc>1)
				        {
				  
				  if($value['A']!='')
				  {
				      
					  //$num_of_imgs = 0;
					  
					  $category=$this->db->get_where('category', array('category_name' => $value['C']))->row()->category_id;
					 
					  if($category=='')
					  {
						  $data_c['category_name'] = $value['C'];
                         $this->db->insert('category', $data_c);
                         $category = $this->db->insert_id();
                         
                          $admindata['user_type'] = $this->session->userdata('title');
                            $admindata['allnames'] = $data_c['category_name'];
                            $admindata['message'] = "Category (".$data_b['allnames'].") added Successfully";
                            $admindata['created_datetime'] = date("Y-m-d H:i:s");
                            $admindata['function'] = 'category';
                			$this->db->insert('all_log', $admindata);
					 
					  }
					   
					   $brand=$this->db->get_where('brand', array('name' => $value['H']))->row()->brand_id;
					   
					
					   if($brand=='')
					   {
						  $data_b['name'] = $value['H'];
						  $data_b['description'] = $value['H'];
           				  $this->db->insert('brand', $data_b);
                          $brand = $this->db->insert_id();
                          $admindata['user_type'] = $this->session->userdata('title');
                            $admindata['allnames'] = $data_b['name'];
                            $admindata['message'] = "Brand (".$data_b['name'].") added Successfully";
                            $admindata['created_datetime'] = date("Y-m-d H:i:s");
                            $admindata['function'] = 'brand';
                			$this->db->insert('all_log', $admindata);
						}
					 	 
						 $sub_category=$this->db->get_where('sub_category', array('sub_category_name' => $value['D']))->row()->sub_category_id;
						 
					    if($sub_category==''){
						 
						 $data['sub_category_name'] = $value['D'];
                         $data_s['category']          = $category;
            			if($brand==NULL)
            			{
            				$data_s['brand']             = '[]';
            			}
            			else
            			{
            				$data_s['brand']             = json_encode(array($brand),1);
            			}
						 
            			$data_s['sub_category_name'] = $value['D'];
                        $this->db->insert('sub_category', $data_s);
                        
                        $sub_category = $this->db->insert_id();
                        
						    $admindata['user_type'] = $this->session->userdata('title');
                            $admindata['allnames'] = $data_s['sub_category_name'];
                            $admindata['message'] = "subcategory (".$data_s['sub_category_name'].") added Successfully";
                            $admindata['created_datetime'] = date("Y-m-d H:i:s");
                            $admindata['function'] = 'sub_category';
                			$this->db->insert('all_log', $admindata);
						  }

						  $inserdata['category'] 			= $category;
						  $inserdata['sub_category'] 		= $sub_category;
						  $inserdata['brand'] 				= $brand;
						  
						  //$this->db->select('product_id');
						  $product_id = $this->db->get_where('product',array('category' => $category,'sub_category'=>$sub_category,'title'=>$value['A']))->row()->product_id;
			if($product_id=='')
			{
                    //echo "1";
						  if($value['A']!='')
						  $inserdata['title'] 				= $value['A'];
						  else
						  $inserdata['title'] 				= 'null';
						  
						  if($value['E']!='')
						  $inserdata['variant'] 		= $value['E'];
						  else
						  $inserdata['variant'] 		= 'null';
						  
						  if($value['F']!='')
						  $inserdata['country_of_origin'] 		= $value['F'];
						  else
						  $inserdata['country_of_origin'] 		= 0;
						  
						  if($value['G']!='')
						  $inserdata['manufacturer'] 		= $value['G'];
						  else
						  $inserdata['manufacturer'] 		= 'null';
						  
						  if($value['I']!='')
						  $inserdata['cost_to_admin'] 		= $value['I'];
						  else
						  $inserdata['cost_to_admin'] 		= 0;
						  
						  if($value['J']!='')
						  $inserdata['admin_Share'] 		= $value['J'];
						  else
						  $inserdata['admin_Share'] 		= 'null';
						  
						  if($value['K']!='')
						  $inserdata['sale_price'] 		= $value['K'];
						  else
						  $inserdata['sale_price'] 		= 'null';
						  
						  if($value['L']!='')
						  $inserdata['discount'] 		= $value['L'];
						  else
						  $inserdata['discount'] 		= 'null';
						  
						  if($value['M']!='')
						  $inserdata['discount_type'] 		= $value['M'];
						  else
						  $inserdata['discount_type'] 		= 'percent';
						  
						  if($value['N']!='')
						  $inserdata['tax'] 		= $value['N'];
						  else
						  $inserdata['tax'] 		= 0;
						  
						  if($value['O']!='')
						  $inserdata['tax_type'] 		= 'percent';
						  else
						  $inserdata['tax_type'] 		= 'percent';
						  
						  if($value['Z']!='')
						  $inserdata['shipping_cost'] 		= $value['Z'];
						  else
						  $inserdata['shipping_cost'] 		= '0.00';
						 
						  
						  
						  if($value['P']!='')
						  $inserdata['current_stock'] 		= $value['P'];
						  else
						  $inserdata['current_stock'] 		= 'null';
						  
						  if($value['Q']!='')
						  $inserdata['tag'] 		= $value['Q'];
						  else
						  $inserdata['tag'] 		= 'null';
						  
						  if($value['R']!='')
						  $inserdata['unit'] 		= $value['R'];
						  else
						  $inserdata['unit'] 		= 'null';
						  
						  if($value['S']!='')
						  $inserdata['hsn_id'] 		= $value['S'];
						  else
						  $inserdata['hsn_id'] 		= 0;
						  
						  if($value['T']=='yes')
						  $inserdata['return_set'] 		= 1;
						  else
						  $inserdata['return_set'] 		= 0;
						  
						  /*if($value['U']=='yes')
						  $inserdata['replacement_set'] 		= 1;
						  else
						  $inserdata['replacement_set'] 		= 0;*/
						  
						  if (!empty($value['U'])) {
                            $image_url = explode(',', $value['U']);
                            $num_of_imgs = count($image_url);
                            }
                            else
                            {
                                $num_of_imgs = 0;
                            }
						  if($value['V']!='')
						  {
						    $inserdata['description'] 		= $value['V'];
						  }
						  else
						  {
						    $inserdata['description'] 		= 'null';
						  }
						  
						  if($value['W']!='')
						  {
						    $inserdata['how_use'] 		= $value['W'];
						  }
						  else
						  {
						    $inserdata['how_use'] 		= 'null';
						  }
						  
						  if($value['B']!='')
						  {
						    $inserdata['key_point'] 		= $value['B'];
						  }
						  else
						  {
						    $inserdata['key_point'] 		= 'null';
						  }
						  
						  if($value['X']!='')
						  {
						      //echo $value['Y'];
    						 // $additional_fields['name']  = json_encode([$value['X']]);
                              //$additional_fields['value'] = json_encode([$value['X']]);
    						  //$inserdata['additional_fields'] 		= json_encode($additional_fields);
    						  $inserdata['additional_info'] 		= $value['X'];
						  }
						  else
						  {
						    $inserdata['additional_info'] 		= 'null';
						  }
						  if($value['Y']!='')
						  {
						    $inserdata['purchase_price'] 		= $value['Y'];
						  }
						  else
						  {
						    $inserdata['purchase_price'] 		= 'null';
						  }
                       
						  $inserdata['status'] 			= 'ok';
						  $inserdata['options'] 		= '[]';
						  $inserdata['num_of_imgs'] 	= $num_of_imgs;
						  $inserdata['add_timestamp']   = time();
						  $inserdata['download']        = NULL;
						  $inserdata['rating_user']      = '[]';
						  $inserdata['added_by'] = $stockData['added_by'] = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
						  $inserdata['vendor_id'] = $this->session->userdata('vendor_id');
						  $i++;
						  
						 // echo "<pre>"; print_r($inserdata); echo "</pre>";
						  $result = $this->db->insert('product', $inserdata); 
						  $pinsert_id = $this->db->insert_id();
						  //echo $this->db->last_query();
						  $data5['product_code']="AD/PI/".str_pad($pinsert_id, 5, "0", STR_PAD_LEFT);
                          $this->db->where('product_id', $pinsert_id);
                          $this->db->update('product', $data5);
						  
						  if(!empty($image_url)){
                                    $this->crud_model->file_up_from_urls($image_url,"product", $pinsert_id);
                            }
						  $admindata['user_type'] = $this->session->userdata('title');
                            $admindata['allnames'] = $inserdata['title'];
                            $admindata['message'] = "product  (".$admindata['allnames'].") added Successfully";
                            $admindata['created_datetime'] = date("Y-m-d H:i:s");
                            $admindata['function'] = 'product';
                			$this->db->insert('all_log', $admindata);
						  
// 						  if($this->session->userdata('role') != 1){
//             $subadmin['sub_admin_id'] = $this->session->userdata('admin_id');
//             $subadmin['sub_admin_name'] = $this->session->userdata('admin_name');
//             $subadmin['message'] = "Sub-admin ".$subadmin['sub_admin_name']." has upload a new Product is ".$inserdata['title'];
//             $this->db->insert('sub_admin_log', $subadmin);
// 			}
						  
						  $stockData['type'] = 'add';
						  $stockData['category'] = $category;
						  $stockData['sub_category'] = $sub_category;
						  $stockData['product'] = $pinsert_id;
						  $stockData['quantity'] = $inserdata['current_stock'];
						  $stockData['rate'] = $inserdata['sale_price'];
						  $stockData['total'] = $inserdata['current_stock']*$inserdata['purchase_price'];
						  $stockData['datetime']	= time();
						  $result1 = $this->db->insert('stock', $stockData); 
						  
			}
			else 
			{
						if($value['A']!='')
						  $inserdata['title'] 				= $value['A'];
						  else
						  $inserdata['title'] 				= 'null';
						  
						  if($value['E']!='')
						  $inserdata['variant'] 		= $value['E'];
						  else
						  $inserdata['variant'] 		= 'null';
						  
						  if($value['F']!='')
						  $inserdata['country_of_origin'] 		= $value['F'];
						  else
						  $inserdata['country_of_origin'] 		= 0;
						  
						  if($value['G']!='')
						  $inserdata['manufacturer'] 		= $value['G'];
						  else
						  $inserdata['manufacturer'] 		= 'null';
						  
						  if($value['I']!='')
						  $inserdata['cost_to_admin'] 		= $value['I'];
						  else
						  $inserdata['cost_to_admin'] 		= 0;
						  
						  if($value['J']!='')
						  $inserdata['admin_Share'] 		= $value['J'];
						  else
						  $inserdata['admin_Share'] 		= 'null';
						  
						  if($value['K']!='')
						  $inserdata['sale_price'] 		= $value['K'];
						  else
						  $inserdata['sale_price'] 		= 'null';
						  
						  if($value['L']!='')
						  $inserdata['discount'] 		= $value['L'];
						  else
						  $inserdata['discount'] 		= 'null';
						  
						  if($value['M']!='')
						  $inserdata['discount_type'] 		= $value['M'];
						  else
						  $inserdata['discount_type'] 		= 'null';
						  
						  if($value['N']!='')
						  $inserdata['tax'] 		= $value['N'];
						  else
						  $inserdata['tax'] 		= 0;
						  
						  if($value['O']!='')
						  $inserdata['tax_type'] 		= 'percent';
						  else
						  $inserdata['tax_type'] 		= 'percent';
						  
						  if($value['Z']!='')
						  $inserdata['shipping_cost'] 		= $value['Z'];
						  else
						  $inserdata['shipping_cost'] 		= '0.00';
						 
						  
						  if($value['P']!='')
						  $inserdata['current_stock'] 		= $value['P'];
						  else
						  $inserdata['current_stock'] 		= 'null';
						  
						  if($value['Q']!='')
						  $inserdata['tag'] 		= $value['Q'];
						  else
						  $inserdata['tag'] 		= 'null';
						  
						  if($value['R']!='')
						  $inserdata['unit'] 		= $value['R'];
						  else
						  $inserdata['unit'] 		= 'null';
						  
						  if($value['S']!='')
						  $inserdata['hsn_id'] 		= $value['S'];
						  else
						  $inserdata['hsn_id'] 		= 0;
						  
						  if($value['T']=='yes')
						  $inserdata['return_set'] 		= 1;
						  else
						  $inserdata['return_set'] 		= 0;
						  
						  /*if($value['U']=='yes')
						  $inserdata['replacement_set'] 		= 1;
						  else
						  $inserdata['replacement_set'] 		= 0;*/
						  
						  if($value['V']!='')
						  {
						    $inserdata['description'] 		= $value['V'];
						  }
						  else
						  {
						    $inserdata['description'] 		= 'null';
						  }
						  
						  if($value['W']!='')
						  {
						    $inserdata['how_use'] 		= $value['W'];
						  }
						  else
						  {
						    $inserdata['how_use'] 		= 'null';
						  }
						  
						  if($value['B']!='')
						  {
						    $inserdata['key_point'] 		= $value['B'];
						  }
						  else
						  {
						    $inserdata['key_point'] 		= 'null';
						  }
						  
						  if($value['X']!='')
						  {
    						  $additional_fields['name']  = json_encode([$value['X']]);
                              $additional_fields['value'] = json_encode([$value['X']]);
    						  $inserdata['additional_fields'] 		= json_encode($additional_fields);
						  }
						  else
						  {
						    $inserdata['additional_fields'] 		= 'null';
						  }
						  if($value['Y']!='')
						  {
						    $inserdata['purchase_price'] 		= $value['Y'];
						  }
						  else
						  {
						    $inserdata['purchase_price'] 		= 'null';
						  }

						  $productdet  = $this->db->get_where('product', array('product_id'=>$product_id))->result_array();
						  //echo "<pre>"; print_r($productdet); echo "</pre>";
						  if (!empty($value['V'])) 
						  {
						      $images = $this->crud_model->file_view('product',$product_id,'','','thumb','src','multi','all');
                              if($images){
                                foreach ($images as $row1){
                                    $a = explode('.', $row1);
                                    $a = $a[(count($a)-2)];
                                    $a = explode('_', $a);
                                    $p = $a[(count($a)-2)];
                                    $i = $a[(count($a)-3)];
                    
                                    $imge_path = $i.'_'.$p;
                                    $a = explode('_', $imge_path);
                                    $this->crud_model->file_dlt('product', $a[0], '.jpg', 'multi', $a[1]);
                                }
                                
                            } 
                            $image_url = explode(',', $value['U']);
                            $num_of_imgs = count($image_url);
                          }
                          elseif($value['U']=='')
                          {
                            foreach($productdet as $product)
                            {
                                $num_of_imgs = $product['num_of_imgs'];
                            }
                            
                          }
                          
                          
						  //$inserdata['status'] 			= 'ok';
						  $inserdata['options'] 		= '[]';
						  $inserdata['num_of_imgs'] 	= $num_of_imgs;
						  $inserdata['add_timestamp']   = time();
						  $inserdata['download']        = NULL;
						  $inserdata['rating_user']      = '[]';
						  //$inserdata['added_by'] = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
						  //$inserdata['vendor_id'] = $this->session->userdata('vendor_id');

						  if(!empty($image_url)){
						            
                                    $this->crud_model->file_up_from_urls($image_url,"product", $product_id);
                            }
						  
						 // echo '<pre>'; print_r($inserdata); echo '</pre>'; 
						  $this->db->where('product_id',$product_id);
        	              $this->db->update('product',$inserdata);
        	             //echo $this->db->last_query();
                            
//         	               if($this->session->userdata('role') != 1){
//             $subadmin['sub_admin_id'] = $this->session->userdata('admin_id');
//             $subadmin['sub_admin_name'] = $this->session->userdata('admin_name');
//             $subadmin['message'] = "Sub-admin ".$subadmin['sub_admin_name']." has update a new Product is ".$inserdata['title'];
//             $this->db->insert('sub_admin_log', $subadmin);
// 			}
        	 
						  //$result = $this->db->insert('product', $inserdata); 
						 // echo $sql = $this->db->last_query(); exit;
						  //$pinsert_id = $this->db->insert_id();
						  
						  /*$stockData['type'] = 'add';
						  $stockData['category'] = $category;
						  $stockData['sub_category'] = $sub_category;
						  $stockData['product'] = $pinsert_id;
						  $stockData['quantity'] = $inserdata['current_stock'];
						  $stockData['rate'] = $inserdata['sale_price'];
						  $stockData['total'] = $inserdata['current_stock']*$inserdata['purchase_price'];
						  $stockData['oid'] = $this->session->userdata('propertyIDS');
						  $stockData['reason_note'] = 'Add Stock';
						  $stockData['datetime']	= time();
						  $result1 = $this->db->insert('stock', $stockData); 			*/
			 
			   
			}
			
					 
				  }
				  
				        }
				    }
				    
				   //echo $this->db->last_query()."</br>"; 
				}  
				
			if($product_id=='')
			{
			    $this->session->set_flashdata('success',translate('upload_successfully'));
                redirect('vendor/product_bulk_upload');
			}
			else
			{
			    $this->session->set_flashdata('success',translate('updated_successfully'));
                redirect('vendor/product_bulk_upload');
			}
				
				     
 
		  } catch (Exception $e) {
		      $this->session->set_flashdata('error',$e->getMessage());
			   die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME).'": '.$e->getMessage());
			    redirect('vendor/product_bulk_upload');
			}
		  }else{
			   $this->session->set_flashdata('error',translate($error['error']));
			    redirect('admin/product_bulk_upload');
				
			}
		
    }
    public function product_bulk_upload_save_single($product)
    {
        $image_urls = array();
        $product_stock_data = array();
        $product_data['num_of_imgs'] = 0;
        if (!empty($product['images'])) {
            $image_urls = explode(',', $product['images']);
            $product_data['num_of_imgs'] = count($image_urls);
        }

        $product_data['title'] = $product['title'];
        $product_data['description'] = $product['description'];
        $product_data['category'] = is_numeric($product['category']) ? $product['category'] : 0;
        $product_data['sub_category'] = is_numeric($product['sub_category']) ? $product['sub_category'] : 0;
        $product_data['brand'] = is_numeric($product['brand']) ? $product['brand'] : 0;

        $product_data['purchase_price'] = is_numeric($product['purchase_price']) ? $product['purchase_price'] : 0;
        $product_data['sale_price'] = is_numeric($product['sale_price']) ? $product['sale_price']: 0;

        $product_data['add_timestamp'] = time();
        $product_data['download'] = NULL;
        $product_data['featured'] = 'no';
        $product_data['vendor_featured'] = 'no';
        $product_data['status'] = $product['published'] == 'yes' ? 'ok' : 0;
        $product_data['rating_user'] = '[]';

        if (strpos($product['tax'], '%') !== false) {
            $tax = str_replace("%", "", $product['tax']);
            $product_data['tax'] = is_numeric($tax) ? $tax : 0;
            $product_data['tax_type'] = 'percent';
        } else {
            $tax = $product['tax'];
            $product_data['tax'] = is_numeric($tax) ? $tax : 0;
            $product_data['tax_type'] = 'amount';
        }

        if (strpos($product['discount'], '%') !== false) {
            $discount = str_replace("%", "", $product['discount']);
            $product_data['discount'] = is_numeric($discount) ? $discount : 0;
            $product_data['discount_type'] = 'percent';
        } else {
            $discount = $product['discount'];
            $product_data['discount'] = is_numeric($discount) ? $discount : 0;
            $product_data['discount_type'] = 'amount';
        }

        $product_data['shipping_cost'] = is_numeric($product['shipping_cost']) ? $product['shipping_cost'] : 0;
        $product_data['tag'] = $product['tag'];
        $product_data['is_bundle'] = 'no';
        $product_data['color'] = null;
        $product_data['current_stock'] = is_numeric($product['add_stock']) ? $product['add_stock'] : 0;



        $product_data['front_image'] = 0;

        $product_data['additional_fields'] = null;
        $product_data['unit'] = is_numeric($product['unit']) ? $product['unit'] : "";
        $product_data['added_by'] = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
        $product_data['options'] = json_encode($options = array());

        $this->db->insert('product', $product_data);
        $product_id = $this->db->insert_id();
        $this->crud_model->set_category_data(0);
        recache();

        if($product_data['current_stock'] > 0){
            $product_stock_data['type']         = 'add';
            $product_stock_data['product']      = $product_id;
            $product_stock_data['category']     = $product_data['category'];
            $product_stock_data['sub_category'] = $product_data['sub_category'];
            $product_stock_data['product']      = $product_data['product'];
            $product_stock_data['quantity']     = $product_data['current_stock'];
            $product_stock_data['rate']         = $product_data['purchase_price'];
            $product_stock_data['total']        = $product_data['purchase_price'] * $product_data['current_stock'] ;
            $product_stock_data['reason_note']  = 'bulk';
            $product_stock_data['added_by']     = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $product_stock_data['datetime']     = time();
            $product_stock_data['current_stock']= $product_data['current_stock'];
            $this->db->insert('stock', $product_stock_data);
        }

        if(!empty($image_urls)){
            //if(!demo()){
                $this->crud_model->file_up_from_urls($image_urls, "product", $product_id);
            //}
        }

    }
    
     function subscribe_product_sale($para1 = '', $para2 = '', $para3 = '')
    { 
        if ($this->session->userdata('admin_login') != 'yes') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'delete') {
            $this->db->where('id', $para2);
            $this->db->delete('subscribe_sale');
        } elseif ($para1 == 'list') {
            $this->db->order_by('id', 'desc');
            $this->db->where('added_by', 'vendor');
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $page_data['subscribe_sale'] = $this->db->get('subscribe_sale')->result_array();
            $this->load->view('back/admin/subscribe_sale_list', $page_data);
        } else {
            $page_data['page_name']      = "subscribe_sale";
            $page_data['subscribe_sale'] = $this->db->get_where('subscribe_sale',array('added_by'=>'vendor','vendor_id'=>$this->session->userdata('vendor_id')))->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
	
	function stores($para1 = '', $para2 = '')
    {
       // echo "a"; exit;
        
        if ($para1 == 'do_add') {
            $data['vendor_id'] = $this->session->userdata('vendor_id');
            //$data['business_type1'] = $this->input->post('business_type1');
//            $data['business_type2'] = $this->input->post('business_type2');
            $data['store_name'] = $this->input->post('store_name');
            $data['owner_name'] = $this->input->post('owner_name');
           // $data['manager_name'] = $this->input->post('manager_name');
//            $data['country_code'] = $this->input->post('country_code');
            $data['mobile'] = $this->input->post('mobile');
            //$data['landline_code'] = $this->input->post('landline_code');
            $data['landline_num'] = $this->input->post('landline_num');
            $client_email=$data['email'] = $this->input->post('email');
            $data['country'] = $this->input->post('country');
            $data['state'] = $this->input->post('state');
            $data['city'] = $this->input->post('cities');
            $data['address'] = $this->input->post('address');
            $data['postal_code'] = $this->input->post('postal_code');
            //$data['website'] = $this->input->post('website');
            //$data['year_founded'] = $this->input->post('year_founded');
            //$data['subscription'] = $this->input->post('subscription');
            //$data['open_time'] = $this->input->post('open_time');
            //$data['close_time'] = $this->input->post('close_time');
            //$data['cod'] = $this->input->post('cod');
            //$data['online_pay'] = $this->input->post('online_pay');
            //$data['self_ship'] = $this->input->post('self_ship');
            //$data['pickup_shipping'] = $this->input->post('pickup_shipping');
            //$data['bank_name'] = $this->input->post('bank_name');
            //$data['branch_name'] = $this->input->post('branch_name');
            //$data['account'] = $this->input->post('account');
            //$data['iban_nb'] = $this->input->post('iban_nb');
            //$data['swift_code'] = $this->input->post('swift_code');
            //$data['additioanl_info'] = $this->input->post('additioanl_info');
            //$data['lbp_rate'] = $this->input->post('lbp_rate');
            //$data['pickup_name'] = $this->input->post('pickup_name');
            //$data['pinckup_ccode'] = $this->input->post('pinckup_ccode');
            //$data['pickup_mobile'] = $this->input->post('pickup_mobile');
            //$data['pickup_country'] = $this->input->post('pickup_country');
            //$data['pickup_city'] = $this->input->post('pickup_city');
            //$data['pickup_postal_code'] = $this->input->post('pickup_postal_code');
            //$data['pickup_address'] = $this->input->post('pickup_address');
            //$data['pickup_liban'] = $this->input->post('pickup_liban');
            //$data['meta_url'] = $this->input->post('meta_url');
            //$data['meta_title'] = $this->input->post('meta_title');
            //$data['meta_author'] = $this->input->post('meta_author');
            //$data['meta_keywords'] = $this->input->post('meta_keywords');
            //$data['meta_discription'] = $this->input->post('meta_discription');
            //$data['pickup_country'] = $this->input->post('pickup_country');
            $data['created_time'] = time();
            
            $this->db->insert('stores', $data);
            echo $this->db->last_query();
            $id = $this->db->insert_id();
			
			$path = $_FILES['prifileimg']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$data_banner['profile_img'] 		 = 'store_'.$id.'_profileimg.'.$ext;
            $this->crud_model->file_up("prifileimg", "store", $id.'_profileimg', '', 'no', '.'.$ext);
            
            $path1 = $_FILES['coverimg']['name'];
			$ext1 = pathinfo($path1, PATHINFO_EXTENSION);
			$data_banner['coverimg'] 		 = 'store_'.$id.'_coverimg.'.$ext1;
            $this->crud_model->file_up("coverimg", "store", $id.'_coverimg', '', 'no', '.'.$ext1);
            
            $path2 = $_FILES['passport_copy']['name'];
			$ext2 = pathinfo($path2, PATHINFO_EXTENSION);
			$data_banner['passport_copy'] 		 = 'store_'.$id.'_passport.'.$ext2;
            $this->crud_model->file_up("passport_copy", "store", $id.'_passport', '', 'no', '.'.$ext2);
            
			$this->db->where('store_id', $id);
            $this->db->update('stores', $data_banner);
            redirect(base_url() . 'index.php/vendor/stores');
			
        } elseif ($para1 == 'sub_by_cat') {
		    
		    //echo $para2; exit;
            echo $this->crud_model->select_html_s('state', 'state', 'name', 'add', 'form-control demo-chosen-select required', '', 'country_id', $para2, 'get_city');
            
			exit;
        }
		elseif ($para1 == 'city') {
			
            echo $this->crud_model->select_html_cities('cities', 'cities', 'name', 'add', 'form-control demo-chosen-select', '', 'state_id', $para2, '');
			exit;
        }else if ($para1 == 'edit') {
            $page_data['store_data'] = $this->db->get_where('stores', array('store_id' => $para2))->result_array();
            $this->load->view('back/vendor/stores_edit', $page_data);
        } elseif ($para1 == "update") {
            
            $data['vendor_id'] = $this->session->userdata('vendor_id');
            $data['business_type1'] = $this->input->post('business_type1');
            $data['business_type2'] = $this->input->post('business_type2');
            $data['store_name'] = $this->input->post('store_name');
            $data['owner_name'] = $this->input->post('owner_name');
            $data['manager_name'] = $this->input->post('manager_name');
            $data['country_code'] = $this->input->post('country_code');
            $data['mobile'] = $this->input->post('mobile');
            $data['landline_code'] = $this->input->post('landline_code');
            $data['landline_num'] = $this->input->post('landline_num');
            $data['email'] = $this->input->post('email');
            $data['country'] = $this->input->post('country');
            $data['state'] = $this->input->post('state');
            $data['city'] = $this->input->post('cities');
            $data['address'] = $this->input->post('address');
            $data['postal_code'] = $this->input->post('postal_code');
            $data['website'] = $this->input->post('website');
            $data['year_founded'] = $this->input->post('year_founded');
            $data['subscription'] = $this->input->post('subscription');
            $data['open_time'] = $this->input->post('open_time');
            $data['close_time'] = $this->input->post('close_time');
            $data['cod'] = $this->input->post('cod');
            $data['online_pay'] = $this->input->post('online_pay');
            $data['self_ship'] = $this->input->post('self_ship');
            $data['pickup_shipping'] = $this->input->post('pickup_shipping');
            $data['bank_name'] = $this->input->post('bank_name');
            $data['branch_name'] = $this->input->post('branch_name');
            $data['account'] = $this->input->post('account');
            $data['iban_nb'] = $this->input->post('iban_nb');
            $data['swift_code'] = $this->input->post('swift_code');
            $data['additioanl_info'] = $this->input->post('additioanl_info');
            $data['lbp_rate'] = $this->input->post('lbp_rate');
            $data['pickup_name'] = $this->input->post('pickup_name');
            $data['pinckup_ccode'] = $this->input->post('pinckup_ccode');
            $data['pickup_mobile'] = $this->input->post('pickup_mobile');
            $data['pickup_country'] = $this->input->post('pickup_country');
            $data['pickup_city'] = $this->input->post('pickup_city');
            $data['pickup_postal_code'] = $this->input->post('pickup_postal_code');
            $data['pickup_address'] = $this->input->post('pickup_address');
            $data['pickup_liban'] = $this->input->post('pickup_liban');
            $data['meta_url'] = $this->input->post('meta_url');
            $data['meta_title'] = $this->input->post('meta_title');
            $data['meta_author'] = $this->input->post('meta_author');
            $data['meta_keywords'] = $this->input->post('meta_keywords');
            $data['meta_discription'] = $this->input->post('meta_discription');
            $data['pickup_country'] = $this->input->post('pickup_country');
            $this->db->where('store_id', $para2);
            $this->db->update('stores', $data);
            echo $this->db->last_query();
            $id = $this->db->insert_id();
			if($_FILES['prifileimg']['name'] !=''){
			$path = $_FILES['prifileimg']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$data_banner['banner'] 		 = 'store_'.$id.'_prifileimg.'.$ext;
            $this->crud_model->file_up("prifileimg", "store", $id.'_prifileimg', '', 'no', '.'.$ext);
            $path1 = $_FILES['coverimg']['name'];
			}
			if($_FILES['prifileimg']['name'] !=''){
			$ext1 = pathinfo($path1, PATHINFO_EXTENSION);
			$data_banner['banner'] 		 = 'store_'.$id.'_coverimg.'.$ext1;
            $this->crud_model->file_up("prifileimg", "store", $id.'_coverimg', '', 'no', '.'.$ext1);
			}
            if($_FILES['passport_copy']['name'] !=''){
            $path2 = $_FILES['passport_copy']['name'];
			$ext2 = pathinfo($path2, PATHINFO_EXTENSION);
			$data_banner['banner'] 		 = 'store_'.$id.'_passport.'.$ext2;
            $this->crud_model->file_up("passport_copy", "store", $id.'_passport', '', 'no', '.'.$ext2);
            }
// 			$this->db->where('store_id', $id);
//             $this->db->update('store', $data_banner);

            redirect(base_url() . 'index.php/vendor/stores');
			
        
        } elseif ($para1 == 'delete') {
			//unlink("uploads/store_image/store_".$para2."_passport");
            $this->db->where('store_id', $para2);
            $this->db->delete('stores');
        } elseif ($para1 == 'list') {
            $this->db->order_by('store_id', 'desc');
			$this->db->where('vendor_id=',$this->session->userdata('vendor_id'));
            $page_data['all_stores'] = $this->db->get('stores')->result_array();
			//echo $this->db->last_query();
            $this->load->view('back/vendor/stores_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/vendor/stores_add');
        } else {
            $page_data['page_name']      = "stores";
			$this->db->order_by('store_id', 'desc');
			$this->db->where('vendor_id=',$this->session->userdata('vendor_id'));
            $page_data['all_stores'] = $this->db->get('stores')->result_array();
            //echo $this->db->last_query();
            $this->load->view('back/index', $page_data);
        }
    }
	

}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */