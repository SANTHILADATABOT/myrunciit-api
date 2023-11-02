<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Admin extends CI_Controller
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
        $this->load->model('crud_model');
        $this->load->model('dashboard_model');
        /*cache control*/
        //$this->output->enable_profiler(TRUE);
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        //$this->crud_model->ip_data();
        $this->config->cache_query();
        // Set timezone
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $ipay_event1=$this->db->query("update sale set payment_details='[]',status='failed' where payment_type='ipay88' and status='pending' and CURRENT_TIMESTAMP()>=(FROM_UNIXTIME(sale_datetime)+INTERVAL 8 MINUTE)");
    }
    
    function getLastSevenDays()
    {
        $dateList = [];
        for ($i = 0; $i < 6; $i++) {
            $dateList[] = date('Y-m-d', strtotime('-' . $i . 'days'));
        }
        return $dateList;
    }
    function createTimeSlots($interval, $start_time, $end_time)
    {
        $start = strtotime($start_time);
        $end = strtotime($end_time);
        $timeList = [];
        $i = 0;
        while ($start <= $end) {

            $i++;
            $timeList[$i]['start_time'] = date('g:i a', $start);
            $endTime = $start + $interval * 60;
            $timeList[$i]['end_time'] = date('g:i a', $endTime);
            $start = $endTime;
        }
        return $timeList;
    }

    function get_user_rights($main_id,$sub_id)
    {
        $tb=$this->db->get_where('menu_permissions',array(
            'role_id'=>$this->session->userdata('session_role_id'),
            'main_menu_id'=>$main_id,
            'sub_menu_id'=>$sub_id
        ))->result_array();
        if($tb[0]['view_rights'] != '1'){
            $this->session->sess_destroy();
            redirect(base_url() . 'index.php/admin', 'refresh');
            return false;
        }        
        return $tb[0];
    }
    public function get_user_view_rights()
    {
        $tb=$this->db->get_where('menu_permissions',array(
            'role_id'=>$this->session->userdata('session_role_id')
        ))->result_array();
        $view_rights=[];
        for($i1=0;$i1<count($tb);$i1++)
        {
            $rights_1=$tb[$i1];
            $view_rights[$rights_1['main_menu_id']][$rights_1['sub_menu_id']]=$rights_1['view_rights'];
        }
        return $view_rights;
    }
    /* index of the admin. Default: Dashboard; On No Login Session: Back to login page. */
    public function index()
    {
        $page_data['timeList'] = $this->createTimeSlots(60, '00:00', '23:00');
        $page_data['lastSevenDays'] = $this->getLastSevenDays();
        if ($this->session->userdata('admin_login') == 'yes') {
            $page_data['page_name'] = "dashboard";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['user_rights_1_0'] = $this->get_user_rights(1,0);
            $this->load->view('back/index', $page_data);
        } else {
            $page_data['control'] = "admin";
            $this->load->view('back/login', $page_data);
        }
    }

    function dashboard_func($para1 = '')
    {
        if($para1 == 'Sales_Breakdown_by_Days_filter')
        {
            $storeid=$this->input->post('storeid');
            $st_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('st_dt'))));
            $en_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('en_dt'))));
            $start_dt=$st_dt->format('Y-m-d');$end_dt=$en_dt->format('Y-m-d');
            $header=["Store"];
            while($st_dt<=$en_dt){$header[]=$st_dt->format('d-m-Y');$st_dt=$st_dt->modify('+1 day');}
            $Sales_Breakdown_by_Days['header']=$header;
            if($storeid!=""){$this->db->where("vendor_id='$storeid'");}
            $this->db->select('vendor_id,name');
            $Sales_Breakdown_by_Days['vendor_list']=$this->db->get('vendor')->result_array();
            $list = $this->dashboard_model->Sales_Breakdown_by_Days($start_dt,$end_dt,$storeid);
            $list1=[];
            foreach($list as $_list){
                $list1[$_list['sale_date']][$_list['store_id']]=$_list['grand_total1'];
            }
            $Sales_Breakdown_by_Days['list'] = $list1;
            echo json_encode($Sales_Breakdown_by_Days);
        }else if($para1 == 'Sales_by_order_value_filter')
        {
          //  $storeid=$this->input->post('storeid');
            $st_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('st_dt'))));
            $en_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('en_dt'))));
            $start_dt=$st_dt->format('Y-m-d');$end_dt=$en_dt->format('Y-m-d');
            
            $Sales_by_order_value_filter = $this->dashboard_model->Sales_by_order_value_filter($start_dt,$end_dt);
            echo json_encode($Sales_by_order_value_filter);
        }
        else if($para1 == 'visitors_breakdown_by_days_filter')
        {
            $storeid=$this->input->post('storeid');
            $buyerid=$this->input->post('buyerid');
            $st_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('st_dt'))));
            $en_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('en_dt'))));
            $start_dt=$st_dt->format('Y-m-d');$end_dt=$en_dt->format('Y-m-d');
            $header=["Store"];
            while($st_dt<=$en_dt){$header[]=$st_dt->format('d-m-Y');$st_dt=$st_dt->modify('+1 day');}
            $overview_visitors_breakdown_by_days['header']=$header;
            if($storeid!=""){$this->db->where("vendor_id='$storeid'");}
            $this->db->select('vendor_id,name');
            $overview_visitors_breakdown_by_days['vendor_list']=$this->db->get('vendor')->result_array();
            $list = $this->dashboard_model->overview_visitors_breakdown_by_days($start_dt,$end_dt,$storeid,$buyerid);
            $list1=[];
            foreach($list as $_list){
                $list1[$_list['sale_date']][$_list['store_id']]=$_list['total_visitors'];
            }
            $overview_visitors_breakdown_by_days['list'] = $list1;
            echo json_encode($overview_visitors_breakdown_by_days);
        }
        else if($para1 == 'sales_analytics_filter')
        {
            $st_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('st_dt'))));
            $en_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('en_dt'))));
            $start_dt=$st_dt->format('Y-m-d');$end_dt=$en_dt->format('Y-m-d');
            $sales_analytics_list = $this->dashboard_model->sales_analytics($start_dt,$end_dt);
            echo json_encode($sales_analytics_list);
        }
        else if($para1 == 'Top_15_Products_by_Units_Sold_filter')
        {
            $itemid=$this->input->post('itemid');
            $top_15 = $this->dashboard_model->top_15($itemid,'','');
            echo json_encode($top_15);
        }
        else if($para1 == 'Sales_by_Stores_by_Selected_Dates_filter')
        {
            $storeid=$this->input->post('storeid');
            $Sales_by_Stores_by_Selected_Dates = $this->dashboard_model->Sales_by_Stores_by_Selected_Dates($storeid,'','');
            echo json_encode($Sales_by_Stores_by_Selected_Dates);
        }
        else if($para1 == 'sales_by_order_type_filter')
        {
            $order_type=$this->input->post('order_type');
            $st_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('st_dt'))));
            $en_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('en_dt'))));
            $start_dt=$st_dt->format('Y-m-d');$end_dt=$en_dt->format('Y-m-d');
            $sales_by_order_type = $this->dashboard_model->sales_by_order_type($order_type,$start_dt,$end_dt);
			echo json_encode($sales_by_order_type);
        }
        else if($para1 == 'inventory_filter')
        {
            $storeid=$this->input->post('storeid');
            $itemid=$this->input->post('itemid');
            $st_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('st_dt'))));
            $en_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('en_dt'))));
            $start_dt=$st_dt->format('Y-m-d');$end_dt=$en_dt->format('Y-m-d');
            $this->db->select('DATE_FORMAT(date(FROM_UNIXTIME(sale.sale_datetime)),\'%d-%m-%Y\') AS sale_date,vendor.name,sale.grand_total,sale.product_details');
            $this->db->group_by('sale_date,vendor.name');
            $this->db->order_by('sale_date,vendor.name', 'asc');
            if($start_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale.sale_datetime))>=\''.$start_dt.'\'');}
            if($end_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale.sale_datetime))<=\''.$end_dt.'\'');}
            $this->db->join('vendor','sale.store_id=vendor.vendor_id');
            // $sales_over_time_list=$this->db->get('sale')->result_array();
            $sales_over_time_list=$this->db->get('sale');
            $inventory_list=[];
            foreach ($sales_over_time_list as $inventory)
            {
                $data = json_decode($inventory['product_details'], true);
                foreach ($data as $key => $item) {
                    $id = $item['id'];
                    $this->db->select('product_id,title');
                    $this->db->where('product_id',$id);
                    $sales_over_time_list1 = $this->db->get('product')->result_array();
                    foreach ($sales_over_time_list1 as $inventory1)
                    {
                        $inventory_list[]=[
                            "name"=>$inventory['name'],
                            "sale_date"=>$inventory['sale_date'],
                            "title"=>$inventory1['title'],
                            "grand_total"=>$inventory['grand_total']
                        ];
                    }
                }
            }
            echo json_encode($inventory_list);
        }
        else if($para1 == 'customer_filter')
        {
            $customers_list = $this->dashboard_model->customers();
			echo json_encode($customers_list);
        }
        else if($para1 == 'visitors_shops_overview_filter')
        {
            $storeid=$this->input->post('storeid');
            $buyerid=$this->input->post('buyerid');
            $st_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('st_dt'))));
            $en_dt=new DateTime(date('Y-m-d',strtotime($this->input->post('en_dt'))));
            $start_dt=$st_dt->format('Y-m-d');$end_dt=$en_dt->format('Y-m-d');
            $visitors_shops_overview_list = $this->dashboard_model->overview_visitors_shops_overview($storeid,$buyerid,$start_dt,$end_dt);
            echo json_encode($visitors_shops_overview_list);
        }
    }

    function change_password($para1 = '', $para2 = '')
    {
        if ($para1 == 'update') {
            $admin_id=$this->input->post('admin_id');
            $admin_set_password=$this->db->get_where('admin_set_password', array('admin_id' => $admin_id))->result_array();
            if(count($admin_set_password)>0){
                $to = $this->db->get_where('admin', array('admin_id' => $admin_id))->row()->email;
                $password           = $this->input->post('password');
                $data['password']  = sha1($password);
                $this->db->where('admin_id', $admin_id);
                $this->db->update('admin', $data);

                $this->db->where('admin_id', $admin_id);
                $this->db->delete('admin_set_password');

                $from_name  = $this->db->get_where('general_settings', array('type' => 'system_name'))->row()->value;
                $sub="MyRunCiit Admin password Updated";
                $msg="Your MyRunCiit Admin panel Password updated successfully<br>Email : <b>".$to."</b><br>Password : <b>".$password."</b>";
                $this->crud_model->elastic_mail('',$from_name,$to, $sub, $msg);
                $this->session->sess_destroy();
                redirect(base_url() . 'index.php/admin', 'refresh');
            }else{
                redirect(base_url() . 'index.php/admin/');
            }
        }else{
            $admin_id=$para1;
            $admin_set_password=$this->db->get_where('admin_set_password', array('admin_id' => $admin_id))->result_array();
            if(count($admin_set_password)>0){
                date_default_timezone_set("Asia/Calcutta");
                $date_time = date("Y-m-d H:i:s");
                $date_time1=$admin_set_password[0]['date_time'];
                $datetime1 = strtotime($date_time);
                $datetime2 = strtotime($date_time1);
                $total_min=(abs($datetime1 - $datetime2) / 60);
                if($total_min<5){
                    $code=sha1($para2);
                    $code1=$admin_set_password[0]['code'];
                    if($code==$code1){
                        $page_data['control'] = "admin";
                        $page_data['admin']=$this->db->get_where('admin', array('admin_id' => $para1))->result_array();
                        $this->load->view('back/change_password', $page_data);
                    }else{
                        redirect(base_url() . 'index.php/admin/');
                    }
                }else{
                    $this->db->where('admin_id', $admin_id);
                    $this->db->delete('admin_set_password');
                    redirect(base_url() . 'index.php/admin/');
                }
            }else{
                redirect(base_url() . 'index.php/admin/');
            }
        }
    }

    /*Product Category add, edit, view, delete */
    function category($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('category')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['category_name'] = $this->input->post('category_name');
            $data['banner_status'] = $this->input->post('status_cate');
            $this->db->insert('category', $data);
            $id = $this->db->insert_id();
            //echo $this->db->last_query();
            $path = $_FILES['img']['name'];
           // echo $path;
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $data_banner['banner']          = 'category_' . $id . '.' . $ext;
            $this->crud_model->file_up("img", "category", $id, '', 'no', '.' . $ext);
           // echo $data_banner['banner'];
            $path1 = $_FILES['img_banner']['name'];
            //echo $_SERVER['DOCUMENT_ROOT'];
            $ext1 = pathinfo($path1, PATHINFO_EXTENSION);
            echo $ext1;
            $data_banner['category_banner']          = 'category_' . $id . '_imagebanner.' . $ext1;
            $this->crud_model->file_up("img_banner", "category", $id.'_imagebanner', '', 'no', '.' . $ext1);
            $this->db->where('category_id', $id);
            $this->db->update('category', $data_banner);
            $this->crud_model->set_category_data(0);
            recache();
        } else if ($para1 == 'edit') {
            $page_data['category_data'] = $this->db->get_where('category', array(
                'category_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/category_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['category_name'] = $this->input->post('category_name');
            $data['banner_status'] = $this->input->post('status_cate');
            $this->db->where('category_id', $para2);
            $this->db->update('category', $data);
            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_banner['banner']          = 'category_' . $para2 . '.' . $ext;
                $this->crud_model->file_up("img", "category", $para2, '', 'no', '.' . $ext);
            }
            if ($_FILES['img_banner']['name'] !== '') {
                $path1 = $_FILES['img_banner']['name'];
                $ext1 = pathinfo($path1, PATHINFO_EXTENSION);
                $data_banner['category_banner']          = 'category_' . $para2 . '_imagebanner.' . $ext1;
                $this->crud_model->file_up("img_banner", "category", $para2.'_imagebanner', '', 'no', '.' . $ext1);
            }
            $this->db->where('category_id', $para2);
            $this->db->update('category', $data_banner);            
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'delete') {
            unlink("uploads/category_image/" . $this->crud_model->get_type_name_by_id('category', $para2, 'banner'));
            $this->db->where('category_id', $para2);
            $this->db->delete('category');
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('category_id', 'desc');
            $this->db->where('digital=', NULL);
            $page_data['all_categories'] = $this->db->get('category')->result_array();
            $page_data['user_rights_22'] = $this->get_user_rights(2,2);
            $this->load->view('back/admin/category_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/category_add');
        } else if ($para1 == 'duplicate') {
            $page_data['category_data'] = $this->db->get_where('category', array(
                'category_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/category_duplicate', $page_data);
        } else if ($para1 == 'duplicate_new') {
            $data['category_name'] = $this->input->post('category_name');
            $this->db->insert('category', $data);
            $id = $this->db->insert_id();
            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_banner['banner']          = 'category_' . $id . '.' . $ext;
                $this->crud_model->file_up("img", "category", $id, '', 'no', '.' . $ext);
                $this->db->where('category_id', $id);
                $this->db->update('category', $data_banner);
            } else {
                $path = $this->input->post('img');
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_banner['banner']          = 'category_' . $id . '.' . $ext;
                $path2='category_' . $id . '.' . $ext;
                
                $dir = '/myrunciit/uploads/category_image';                          
                copy($_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $path, $_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $path2);   

                $this->db->where('category_id', $id);
                $this->db->update('category', $data_banner);
            }
            $this->crud_model->set_category_data(0);
            recache();
        } else if ($para1 == 'suspend_set') {
            $category = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('category_id', $category);
            $this->db->update('category', $data);
        } else {
            $page_data['page_name']      = "category";
            $page_data['all_categories'] = $this->db->get('category')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* VB Categories */
    function categories($para1 = '', $para2 = '')
    {
        $page_data['page_name']      = "categories";
        $page_data['view_rights']=$this->get_user_view_rights();
        $page_data['user_rights_21'] = $this->get_user_rights(2,1);
        $page_data['user_rights_22'] = $this->get_user_rights(2,2);
        $page_data['user_rights_23'] = $this->get_user_rights(2,3);
        $this->load->view('back/index', $page_data);
    }

    function courier($para1 = '', $para2 = '')
    {

        if ($para1 == 'do_add') {
            //echo "a"; exit;
            $data['agent_name'] = $this->input->post('agent_name');
            $data['agency_name'] = $this->input->post('agent_name');
            $data['address'] = $this->input->post('address');
            $data['city'] = $this->input->post('city');
            $data['state'] = $this->input->post('state');
            $data['country'] = $this->input->post('country');
            $data['email'] = $this->input->post('email');
            $data['mobile_phone'] = $this->input->post('mobile_phone');
            $data['agency_login'] = $this->input->post('username');
            $data['agency_pass'] = sha1($this->input->post('password') . '987ABLO@@##$$%%');
            $data['status'] = '1';
            $this->db->insert('delivery_agent', $data);
            $id = $this->db->insert_id();
            recache();
        } else if ($para1 == 'edit') {
            $page_data['courier_data'] = $this->db->get_where('delivery_agent', array(
                'agent_id' => $para2
            ))->result_array();
            //echo $this->db->last_query();
            $this->load->view('back/admin/courier_edit', $page_data);
        } elseif ($para1 == "update") {
            //  $data['category_name'] = $this->input->post('category_name');
            $data['agent_name'] = $this->input->post('agent_name');
            $data['agency_name'] = $this->input->post('agent_name');
            $data['address'] = $this->input->post('address');
            $data['city'] = $this->input->post('city');
            $data['state'] = $this->input->post('state');
            $data['country'] = $this->input->post('country');
            $data['email'] = $this->input->post('email');
            $data['mobile_phone'] = $this->input->post('mobile_phone');
            $this->db->where('agent_id', $para2);
            $this->db->update('delivery_agent', $data);

            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'delete') {
            //	unlink("uploads/category_image/" .$this->crud_model->get_type_name_by_id('category',$para2,'banner'));
            $this->db->where('agent_id', $para2);
            $this->db->delete('delivery_agent');
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('agent_id', 'desc');
            //$this->db->where('digital=',NULL);
            //  $page_data['all_courier'] = $this->db->get('delivery_agent')->result_array();
            $att = 0;
            $page_data['all_courier'] = $this->db->get_where('delivery_agent', array(
                'attendant' => $att
            ))->result_array();
            $this->load->view('back/admin/courier_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/courier_add');
        } else {
            $page_data['page_name']      = "courier";
            $page_data['all_courier'] = $this->db->get('delivery_agent')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    
    function city($para1 = '', $para2 = '')
    {

        if ($para1 == 'do_add') {
            //echo "a"; exit;
            $data['district_name'] = $this->input->post('district_name');

            $this->db->insert('district', $data);
            $id = $this->db->insert_id();
            recache();
        } else if ($para1 == 'edit') {
            $page_data['city_data'] = $this->db->get_where('district', array(
                'id' => $para2
            ))->result_array();
            //echo $this->db->last_query();
            $this->load->view('back/admin/city_edit', $page_data);
        } elseif ($para1 == "update") {
            //echo "a"; exit;
            //  $data['category_name'] = $this->input->post('category_name');
            $data['district_name'] = $this->input->post('district_name');

            $this->db->where('id', $para2);
            $this->db->update('district', $data);

            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'delete') {
            //	unlink("uploads/category_image/" .$this->crud_model->get_type_name_by_id('category',$para2,'banner'));
            $this->db->where('id', $para2);
            $this->db->delete('district');
            //$this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            //echo "a"; exit;
            $this->db->order_by('id', 'desc');
            //$this->db->where('digital=',NULL);
            $page_data['all_city'] = $this->db->get('district')->result_array();
            //echo $this->db->last_query(); exit;
            //  $att=0;
            $this->load->view('back/admin/city_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/city_add');
        } else {
            $page_data['page_name']      = "city";
            $page_data['all_city'] = $this->db->get('district')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }





    /*Digital Category add, edit, view, delete */
    function category_digital($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('category_digital')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '69', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['category_name'] = $this->input->post('category_name');
            $data['digital'] = 'ok';
            $this->db->insert('category', $data);
            $id = $this->db->insert_id();

            $path = $_FILES['img']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $data_banner['banner']          = 'category_' . $id . '.' . $ext;
            $this->crud_model->file_up("img", "category", $id, '', 'no', '.' . $ext);
            $this->db->where('category_id', $id);
            $this->db->update('category', $data_banner);
            $this->crud_model->set_category_data(0);

            recache();
        } else if ($para1 == 'edit') {
            $page_data['category_data'] = $this->db->get_where('category', array(
                'category_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/category_edit_digital', $page_data);
        } elseif ($para1 == "update") {
            $data['category_name'] = $this->input->post('category_name');
            $this->db->where('category_id', $para2);
            $this->db->update('category', $data);
            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_banner['banner']          = 'category_' . $para2 . '.' . $ext;
                $this->crud_model->file_up("img", "category", $para2, '', 'no', '.' . $ext);
                $this->db->where('category_id', $para2);
                $this->db->update('category', $data_banner);
            }
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'delete') {
            unlink("uploads/category_image/" . $this->crud_model->get_type_name_by_id('category', $para2, 'banner'));
            $this->db->where('category_id', $para2);
            $this->db->delete('category');
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('category_id', 'desc');
            $this->db->where('digital=', 'ok');
            $page_data['all_categories'] = $this->db->get('category')->result_array();
            $this->load->view('back/admin/category_list_digital', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/category_add_digital');
        } else {
            $page_data['page_name']      = "category_digital";
            $this->db->where('digital=', 'ok');
            $page_data['all_categories'] = $this->db->get('category')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* blogs */
    function blogs($para1 = '', $para2 = '')
    {
        $page_data['page_name']      = "blogs";        
        $page_data['view_rights']=$this->get_user_view_rights();
        $page_data['user_rights_18_13'] = $this->get_user_rights(18,13);
        $page_data['user_rights_18_14'] = $this->get_user_rights(18,14);
        $this->load->view('back/index', $page_data);
    }

    /*Product blog_category add, edit, view, delete */
    function blog_category($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('blog')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['name'] = $this->input->post('name');
            $this->db->insert('blog_category', $data);
            recache();
        } else if ($para1 == 'edit') {
            $page_data['blog_category_data'] = $this->db->get_where('blog_category', array(
                'blog_category_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/blog_category_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['name'] = $this->input->post('name');
            $this->db->where('blog_category_id', $para2);
            $this->db->update('blog_category', $data);
            recache();
        } elseif ($para1 == 'delete') {
            $this->db->where('blog_category_id', $para2);
            $this->db->delete('blog_category');
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('blog_category_id', 'desc');
            $page_data['all_categories'] = $this->db->get('blog_category')->result_array();
            $page_data['user_rights_18_13'] = $this->get_user_rights(18,13);
            $this->load->view('back/admin/blog_category_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/blog_category_add');
        } else {
            $page_data['page_name']      = "blog_category";
            $page_data['all_categories'] = $this->db->get('blog_category')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }


    function subscribe($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('user')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['delivery'] = $this->input->post('delivery');
            $data['amount'] = $this->input->post('amount');
            $this->db->insert('subscribe_pro', $data);
            //echo $this->db->last_query;
            recache();
        } else if ($para1 == 'edit') {
            $page_data['subscribe_data'] = $this->db->get_where('subscribe_pro', array(
                'id' => $para2
            ))->result_array();
            $this->load->view('back/admin/subscribe_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['delivery'] = $this->input->post('delivery');
            $data['amount'] = $this->input->post('amount');
            $this->db->where('id', $para2);
            $this->db->update('subscribe_pro', $data);
            recache();
        } elseif ($para1 == 'delete') {
            $this->db->where('id', $para2);
            $this->db->delete('subscribe_pro');
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('id', 'desc');
            $page_data['all_subscribe'] = $this->db->get('subscribe_pro')->result_array();

            $this->load->view('back/admin/subscribe_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/subscribe_add');
        } else {
            $page_data['page_name']      = "subscribe";
            $page_data['all_subscribe'] = $this->db->get('subscribe_pro')->result_array();
            //echo $this->db->last_query(); exit;
            $this->load->view('back/index', $page_data);
        }
    }


    /*Product slides add, edit, view, delete */
    function slides($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('slides')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $type                        = 'slides';
            $data['button_color']          = $this->input->post('color_button');
            $data['text_color']            = $this->input->post('color_text');
            $data['button_text']        = $this->input->post('button_text');
            $data['button_link']        = $this->input->post('button_link');
            $data['uploaded_by']        = 'admin';
            $data['status']                = 'ok';
            $data['added_by']           = json_encode(array('type' => 'admin', 'id' => $this->session->userdata('admin_id')));
            $this->db->insert('slides', $data);
            $id = $this->db->insert_id();
            $this->crud_model->file_up("img", "slides", $id, '', '', '.jpg');
            recache();
        } elseif ($para1 == "update") {
            $data['button_color']          = $this->input->post('color_button');
            $data['text_color']            = $this->input->post('color_text');
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
            $this->load->view('back/admin/slides_edit', $page_data);
        } elseif ($para1 == 'list') {
            $this->db->order_by('slides_id', 'desc');
            $this->db->where('uploaded_by', 'admin');
            $page_data['all_slidess'] = $this->db->get('slides')->result_array();
            $this->load->view('back/admin/slides_list', $page_data);
        } elseif ($para1 == 'slide_publish_set') {
            $slides_id = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('slides_id', $slides_id);
            $this->db->update('slides', $data);
            recache();
        } elseif ($para1 == 'vendor') {
            if ($this->crud_model->get_type_name_by_id('general_settings', '58', 'value') !== 'ok') {
                redirect(base_url() . 'index.php/admin');
            }
            $page_data['page_name']  = "slides_vendor";
            $this->load->view('back/index', $page_data);
        } elseif ($para1 == 'vendor_slides') {
            if ($this->crud_model->get_type_name_by_id('general_settings', '58', 'value') !== 'ok') {
                redirect(base_url() . 'index.php/admin');
            }
            $this->db->order_by('slides_id', 'desc');
            $this->db->where('uploaded_by', 'vendor');
            $page_data['all_slidess'] = $this->db->get('slides')->result_array();
            $this->load->view('back/admin/slides_list_vendor', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/slides_add');
        } else {
            $page_data['page_name']  = "slides";
            $page_data['all_slidess'] = $this->db->get('slides')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /*Product Category add, edit, view, delete */
    function blog($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('blog')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {


            //error_reporting(1);
            ini_set('display_errors', 1);


            echo "Calling <br/><pre>";
            print_r($_POST);
            $data['title']          = $this->input->post('title');
            $data['date']           = $this->input->post('date');
            $data['author']         = $this->input->post('author');
            $data['summery']        = $this->input->post('summery');
            $data['blog_category']  = $this->input->post('blog_category');
            $data['description']    = $this->input->post('description');

            echo "<br/><pre>";
            //print_r($data);
            $this->db->insert('blog', $data);
            $id = $this->db->insert_id();
            //echo $this->db->last_query();
            $this->crud_model->file_up("img", "blog", $id, '', '', '.jpg');
            recache();
            exit;
        } else if ($para1 == 'edit') {
            $page_data['blog_data'] = $this->db->get_where('blog', array(
                'blog_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/blog_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['title']          = $this->input->post('title');
            $data['date']           = $this->input->post('date');
            $data['author']         = $this->input->post('author');
            $data['summery']        = $this->input->post('summery');
            $data['blog_category']  = $this->input->post('blog_category');
            $data['description']    = $this->input->post('description');
            $this->db->where('blog_id', $para2);
            $this->db->update('blog', $data);
            $this->crud_model->file_up("img", "blog", $para2, '', '', '.jpg');
            recache();
        } elseif ($para1 == 'delete') {
            $this->crud_model->file_dlt('blog', $para2, '.jpg');
            $this->db->where('blog_id', $para2);
            $this->db->delete('blog');
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('blog_id', 'desc');
            $page_data['all_blogs'] = $this->db->get('blog')->result_array();
            $page_data['user_rights_18_14'] = $this->get_user_rights(18,14);
            $this->load->view('back/admin/blog_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/blog_add');
        } else {
            $page_data['page_name']      = "blog";
            $page_data['all_blogs'] = $this->db->get('blog')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }



    /*Product Sub-category add, edit, view, delete */
    function sub_category($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('sub_category')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['sub_category_name'] = $this->input->post('sub_category_name');
            $data['category']          = $this->input->post('category');
            if ($this->input->post('brand') == NULL) {
                $data['brand']             = '[]';
            } else {
                $data['brand']             = json_encode($this->input->post('brand'));
            }
            $this->db->insert('sub_category', $data);
            $id = $this->db->insert_id();

            $path = $_FILES['img']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $data_banner['banner']          = 'sub_category_' . $id . '.' . $ext;
            $this->crud_model->file_up("img", "sub_category", $id, '', 'no', '.' . $ext);
            $this->db->where('sub_category_id', $id);
            $this->db->update('sub_category', $data_banner);
            $this->crud_model->set_category_data(0);

            recache();
        } else if ($para1 == 'edit') {
            $page_data['sub_category_data'] = $this->db->get_where('sub_category', array(
                'sub_category_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/sub_category_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['sub_category_name'] = $this->input->post('sub_category_name');
            $data['category']          = $this->input->post('category');
            if ($this->input->post('brand') == NULL) {
                $data['brand']             = '[]';
            } else {
                $data['brand']             = json_encode($this->input->post('brand'));
            }
            $this->db->where('sub_category_id', $para2);
            $this->db->update('sub_category', $data);

            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_banner['banner']          = 'sub_category_' . $para2 . '.' . $ext;
                $this->crud_model->file_up("img", "sub_category", $para2, '', 'no', '.' . $ext);
                $this->db->where('sub_category_id', $para2);
                $this->db->update('sub_category', $data_banner);
            }
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'delete') {
            unlink("uploads/sub_category_image/" . $this->crud_model->get_type_name_by_id('sub_category', $para2, 'banner'));
            $this->db->where('sub_category_id', $para2);
            $this->db->delete('sub_category');
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('sub_category_id', 'desc');
            $this->db->where('digital=', NULL);
            $page_data['all_sub_category'] = $this->db->get('sub_category')->result_array();
            $page_data['user_rights_23'] = $this->get_user_rights(2,3);
            $this->load->view('back/admin/sub_category_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/sub_category_add');
        } else if ($para1 == 'duplicate') {
            $page_data['sub_category_data'] = $this->db->get_where('sub_category', array(
                'sub_category_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/sub_category_duplicate', $page_data);
        } else if ($para1 == 'duplicate_new') {
            $data['sub_category_name'] = $this->input->post('sub_category_name');
            $data['category']          = $this->input->post('category');
            if ($this->input->post('brand') == NULL) {
                $data['brand']             = '[]';
            } else {
                $data['brand']             = json_encode($this->input->post('brand'));
            }
            $this->db->insert('sub_category', $data);
            $id = $this->db->insert_id();
            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_banner['banner']          = 'sub_category_' . $id . '.' . $ext;
                $this->crud_model->file_up("img", "sub_category", $id, '', 'no', '.' . $ext);
                $this->db->where('sub_category_id', $id);
                $this->db->update('sub_category', $data_banner);
            } else {
                $path = $this->input->post('img');
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_banner['banner']          = 'sub_category_' . $id . '.' . $ext;
                $path2='sub_category_' . $id . '.' . $ext;
                
                $dir = '/myrunciit/uploads/sub_category_image';                          
                copy($_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $path, $_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $path2);   
                
                $this->db->where('sub_category_id', $id);
                $this->db->update('sub_category', $data_banner);
            }
            $this->crud_model->set_category_data(0);
            recache();
        } else if ($para1 == 'suspend_set') {
            $sub_category = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('sub_category_id', $sub_category);
            $this->db->update('sub_category', $data);
        } else {
            $page_data['page_name']        = "sub_category";
            $page_data['all_sub_category'] = $this->db->get('sub_category')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /*Digital Sub-category add, edit, view, delete */
    function sub_category_digital($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('sub_category_digital')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '69', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['sub_category_name'] = $this->input->post('sub_category_name');
            $data['category']          = $this->input->post('category');
            $data['digital']           = 'ok';
            $this->db->insert('sub_category', $data);
            $id = $this->db->insert_id();
            $path = $_FILES['img']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $data_banner['banner']          = 'sub_category_' . $id . '.' . $ext;
            $this->crud_model->file_up("img", "sub_category", $id, '', 'no', '.' . $ext);
            $this->db->where('sub_category_id', $id);
            $this->db->update('sub_category', $data_banner);
            $this->crud_model->set_category_data(0);

            recache();
        } else if ($para1 == 'edit') {
            $page_data['sub_category_data'] = $this->db->get_where('sub_category', array(
                'sub_category_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/sub_category_edit_digital', $page_data);
        } elseif ($para1 == "update") {
            $data['sub_category_name'] = $this->input->post('sub_category_name');
            $data['category']          = $this->input->post('category');
            $this->db->where('sub_category_id', $para2);
            $this->db->update('sub_category', $data);

            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_banner['banner']          = 'sub_category_' . $para2 . '.' . $ext;
                $this->crud_model->file_up("img", "sub_category", $para2, '', 'no', '.' . $ext);
                $this->db->where('sub_category_id', $para2);
                $this->db->update('sub_category', $data_banner);
            }
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'delete') {
            unlink("uploads/sub_category_image/" . $this->crud_model->get_type_name_by_id('sub_category', $para2, 'banner'));
            $this->db->where('sub_category_id', $para2);
            $this->db->delete('sub_category');
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('sub_category_id', 'desc');
            $this->db->where('digital=', 'ok');
            $page_data['all_sub_category'] = $this->db->get('sub_category')->result_array();
            $this->load->view('back/admin/sub_category_list_digital', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/sub_category_add_digital');
        } else {
            $page_data['page_name']        = "sub_category_digital";
            $this->db->where('digital=', 'ok');
            $page_data['all_sub_category'] = $this->db->get('sub_category')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    /*Product Brand add, edit, view, delete */
    function brand($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('brand')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $type                = 'brand';
            $data['name']        = $this->input->post('name');
            $this->db->insert('brand', $data);
            $id = $this->db->insert_id();

            $path = $_FILES['img']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $data_banner['logo']          = 'brand_' . $id . '.' . $ext;
            $this->crud_model->file_up("img", "brand", $id, '', 'no', '.' . $ext);
            $this->db->where('brand_id', $id);
            $this->db->update('brand', $data_banner);
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == "update") {
            $data['name']        = $this->input->post('name');
            $this->db->where('brand_id', $para2);
            $this->db->update('brand', $data);
            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_logo['logo']          = 'brand_' . $para2 . '.' . $ext;
                $this->crud_model->file_up("img", "brand", $para2, '', 'no', '.' . $ext);
                $this->db->where('brand_id', $para2);
                $this->db->update('brand', $data_logo);
            }
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'delete') {
            unlink("uploads/brand_image/" . $this->crud_model->get_type_name_by_id('brand', $para2, 'logo'));
            $this->db->where('brand_id', $para2);
            $this->db->delete('brand');
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'multi_delete') {
            $ids = explode('-', $param2);
            $this->crud_model->multi_delete('brand', $ids);
        } else if ($para1 == 'edit') {
            $page_data['brand_data'] = $this->db->get_where('brand', array(
                'brand_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/brand_edit', $page_data);
        } elseif ($para1 == 'list') {
            $this->db->order_by('brand_id', 'desc');
            $page_data['all_brands'] = $this->db->get('brand')->result_array();
            $page_data['user_rights_21'] = $this->get_user_rights(2,1);
            $this->load->view('back/admin/brand_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/brand_add');
        } elseif ($para1 == 'suspend_set') {
            $brand = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('brand_id', $brand);
            $this->db->update('brand', $data);
        } else {
            $page_data['page_name']  = "brand";
            $page_data['all_brands'] = $this->db->get('brand')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /*Product coupon add, edit, view, delete */
    function coupon($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('coupon')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['vendor_id'] = $this->input->post('vendor');
            $data['title'] = $this->input->post('title');
            $data['min_order_amount'] = $this->input->post('min_order_amount');
            $data['code'] = $this->input->post('code');
            $data['till'] = $this->input->post('till');
            $data['status'] = 'ok';
            $data['added_by'] = json_encode(array('type' => 'admin', 'id' => $this->session->userdata('admin_id')));
            $data['spec'] = json_encode(array(
                'set_type' => $this->input->post('set_type'),
                'set' => json_encode($this->input->post($this->input->post('set_type'))),
                'discount_type' => $this->input->post('discount_type'),
                'discount_value' => $this->input->post('discount_value'),
                'shipping_free' => $this->input->post('shipping_free')
            ));
            $this->db->insert('coupon', $data);
        } else if ($para1 == 'edit') {
            $page_data['coupon_data'] = $this->db->get_where('coupon', array(
                'coupon_id' => $para2
            ))->result_array();
            $page_data['vendors'] = $this->db->get('vendor')->result_array();
            $this->load->view('back/admin/coupon_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['vendor_id'] = $this->input->post('vendor');
            $data['title'] = $this->input->post('title');
            $data['min_order_amount'] = $this->input->post('min_order_amount');
            $data['code'] = $this->input->post('code');
            $data['till'] = $this->input->post('till');
            $data['spec'] = json_encode(array(
                'set_type' => $this->input->post('set_type'),
                'set' => json_encode($this->input->post($this->input->post('set_type'))),
                'discount_type' => $this->input->post('discount_type'),
                'discount_value' => $this->input->post('discount_value'),
                'shipping_free' => $this->input->post('shipping_free')
            ));
            $this->db->where('coupon_id', $para2);
            $this->db->update('coupon', $data);
        } elseif ($para1 == 'delete') {
            $this->db->where('coupon_id', $para2);
            $this->db->delete('coupon');
        } elseif ($para1 == 'list') {
            $this->db->order_by('coupon_id', 'desc');
            $page_data['all_coupons'] = $this->db->get('coupon')->result_array();
            $page_data['user_rights_6_0'] = $this->get_user_rights(6,0);
            $this->load->view('back/admin/coupon_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/coupon_add');
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
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['user_rights_6_0'] = $this->get_user_rights(6,0);
            $page_data['all_coupons'] = $this->db->get('coupon')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /*Product Sale Comparison Reports*/
    function report($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('report')) {
            redirect(base_url() . 'index.php/admin');
        }
        $page_data['page_name'] = "report";
        $page_data['products']  = $this->db->get('product')->result_array();
        $this->load->view('back/index', $page_data);
    }

    /*Product Stock Comparison Reports*/
    function report_stock($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('report')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
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
        if (!$this->crud_model->admin_permission('report')) {
            redirect(base_url() . 'index.php/admin');
        }
        $page_data['page_name'] = "report_wish";
        $this->load->view('back/index', $page_data);
    }

    /* VB Product Stock */
    function product_stock($para1 = '', $para2 = '', $para3 = '')
    {
        if ($this->input->post() != '') {
            $page_data['singvendor'] = $singvendor  = $this->input->post('vendor');
            $page_data['status'] = $status  = $this->input->post('status');
        }
        if ($para1 == 'export_excel') {
            if ($para2 != 0) {
                $this->db->where('store_id', $para2);
            }
            if ($para3 != 0) {
                if ($para3 == '1') {
                    $this->db->where('status', '0');
                }
                if ($para3 == '2') {
                    $this->db->where('status', 'ok');
                }
            }
            $page_data['excels'] = $this->db->get_where('product')->result_array();;
            $this->load->view('back/admin/export_excel', $page_data);
        }
        $page_data['page_name']      = "product_stock";        
        $page_data['view_rights']=$this->get_user_view_rights();
        $page_data['user_rights_3_4'] = $this->get_user_rights(3,4);
        $page_data['user_rights_3_5'] = $this->get_user_rights(3,5);
        $page_data['user_rights_3_6'] = $this->get_user_rights(3,6);
        $this->load->view('back/index', $page_data);
    }

    /* Product add, edit, view, delete, stock increase, decrease, discount */
    function product($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('product')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->input->post() != '') {
            $page_data['singvendor'] = $singvendor  = $this->input->post('vendor');
            $page_data['status'] = $status  = $this->input->post('status');
        }
        if ($para1 == 'today') {
            $this->load->view('back/admin/product_today');
        } elseif ($para1 == 'today_edit') {
            $page_data['today_details'] = $this->db->get_where('today_deals', array('today_id' => $para2))->result_array();
            $this->load->view('back/admin/product_today_edit', $page_data);
        } elseif ($para1 == 'today_add') {
            // $data['added_by']    =  $this->session->userdata('propertyIDS');
            $data['toda_start_date']    = $this->input->post('today_start_date');
            $data['today_start_time']    = $this->input->post('today_start_time');
            $data['today_end_date']      = $this->input->post('today_end_date');
            $data['today_end_time']      = $this->input->post('today_end_time');
            $data['product_id']             = json_encode($this->input->post('product'));
            $data['added_by']             = $this->session->userdata('admin_id');
            //print_r($data);
            $decode_data = json_decode($data['product_id'], true);
            foreach ($decode_data as $data2) {
                //  print_r($data2);
                $data4['today_status'] = 1;
                $this->db->where('product_id', $data2);
                $this->db->update('product', $data4);
                // echo $this->db->last_query();
            }

            $this->db->insert('today_deals', $data);
            // echo $this->db->last_query();
            // redirect('https://wadahstore.storesmartstore.com/myownstore/index.php/admin/product/', 'refresh');
            //  recache();

        } elseif ($para1 == 'todays_edit') {
            $data['toda_start_date']    = $this->input->post('today_start_date');
            $data['today_start_time']    = $this->input->post('today_start_time');
            $data['today_end_date']      = $this->input->post('today_end_date');
            $data['today_end_time']      = $this->input->post('today_end_time');
            $data['product_id']             = json_encode($this->input->post('product'));

            //print_r($data);
            $dataUnSet['today_status'] = 0;
            //  $this->db->where('oid', $this->session->userdata('propertyIDS'));  
            $this->db->update('product', $dataUnSet);
            $decode_data = json_decode($data['product_id'], true);
            foreach ($decode_data as $data2) {
                $data4['today_status'] = 1;
                $this->db->where('product_id', $data2);
                $this->db->update('product', $data4);

                echo $this->db->last_query();
            }
            $this->db->where('today_id', $para2);
            $this->db->update('today_deals', $data);
            echo $this->db->last_query();
            //redirect(base_url() . 'index.php/admin/product/', 'refresh');
            recache();
        } elseif ($para1 == 'today_deact') {
            $id = $para2;

            $data['status'] = 0;
            $this->db->where('today_id', $id);
            //$this->db->where('added_by', $this->session->userdata('propertyIDS')); 
            $this->db->update('today_deals', $data);

            $todaydeal_crons  = $this->db->get_where('today_deals', array('today_id' => $id))->result_array();
            //echo "<pre>"; print_r($deal_crons); echo "</pre>";
            foreach ($todaydeal_crons as $today) {
                $product_data = json_decode($today['product_id'], true);
                foreach ($product_data as $data3) {
                    $data1['today_status'] = 0;
                    //print_r($data1);
                    $this->db->where('product_id', $data3);
                    //$this->db->where('oid', $this->session->userdata('propertyIDS')); 
                    $this->db->update('product', $data1);
                    //echo $this->db->last_query();
                }
            }
            redirect(base_url() . 'index.php/admin/product/', 'refresh');
        } elseif ($para1 == 'do_add') {
            $options = array();
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
            $data['title']              = $this->input->post('title');
            $data['store_id']           = $this->input->post('vendor');
            $data['category']           = $this->input->post('category');
            $data['description']        = $this->input->post('description');
            $data['ar']                 = $this->input->post('ar');
            $data['enquiry']            = $this->input->post('enquiry');
            $data['subscribe']          = $this->input->post('subscribe');
            $data['callnow']            = $this->input->post('callnow');

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
            $choice_titles              = $this->input->post('op_title');
            $choice_types               = $this->input->post('op_type');
            $choice_no                  = $this->input->post('op_no');
            $data['added_by']           = json_encode(array('type' => 'admin', 'id' => $this->session->userdata('admin_id')));
            if (count($choice_titles) > 0) {
                foreach ($choice_titles as $i => $row) {
                    $choice_options         = $this->input->post('op_set' . $choice_no[$i]);
                    $options[]              =   array(
                        'no' => $choice_no[$i],
                        'title' => $choice_titles[$i],
                        'name' => 'choice_' . $choice_no[$i],
                        'type' => $choice_types[$i],
                        'option' => $choice_options
                    );
                }
            }
            $data['options']            = json_encode($options);
            //echo '<pre>'; print_r(post); exit;
            $this->db->insert('product', $data);
            echo $this->db->last_query(); 
            $id = $this->db->insert_id();
            $this->benchmark->mark_time();
            $this->crud_model->file_up("images", "product", $id, 'multi');
            $this->crud_model->file_up1("images1", "android", $id, '', 'no', '.glb');
            $this->crud_model->file_up1("images2", "ios", $id, '', 'no', '.usdz');
            $this->crud_model->file_up1("video", "product", $id, '', 'no');
            if ($_FILES['video']['tmp_name'] != '') {
                $this->crud_model->file_up2("video", "product", $id, '', 'no');
            }
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'delete_mp') {
            $this->db->where('id', $para2);
            $this->db->delete('multiple_option');
            recache();
        } elseif ($para1 == 'export_excel') {
            // echo $para2;
            //    echo $para3; exit;
            // if($para2!=0)
            //{
            //  $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$para2)));
            //}

            if ($para2 != 0) {
                $this->db->where('store_id', $para2);
            }
            if ($para3 != 0) {
                if ($para3 == '1') {
                    $this->db->where('status', '0');
                }
                if ($para3 == '2') {
                    $this->db->where('status', 'ok');
                }
            }
            $page_data['excels'] = $this->db->get_where('product')->result_array();
            // print_r($page_data['excels']);
            // exit;
            $this->load->view('back/admin/export_excel', $page_data);
        }
        //superagent
        elseif ($para1 == 'update_split') {

            foreach ($_POST as $key => $value) {
                if ($key != 'split_qty' && $key != 'split_price') {
                    $datas[$key] = $this->input->post($key);
                }
            }
            //echo '<pre>'; print_r($datas); exit;

            // $data['other_option']     = json_encode($datas, true);
            $data['product_color']  = $this->input->post('color');
            $data['quantitty']      = $this->input->post('split_qty');
            $data['amount']         = $this->input->post('split_price');
            $data['product_id']     = $para2;
            $data['created_by']     = $this->session->userdata('admin_id');

            // $precheck = $this->db->get_where('multiple_option', array('product_id' => $para2, 'other_option' => $data['other_option'], 'quantitty' => $data['quantitty'], 'amount' => $data['amount']))->row()->id;

            $precheck = $this->db->get_where('multiple_option', array('product_id' => $para2, 'quantitty' => $data['quantitty'], 'amount' => $data['amount']))->row()->id;

            $data1['multiple_price']     = 1;
            $this->db->where('product_id', $para2);
            $this->db->update('product', $data1);

            echo $this->db->last_query();
            // if ($precheck == '' && $data['quantitty'] != '' && $data['amount'] != '' && $data['other_option'] != '') {
            if ($precheck == '' && $data['quantitty'] != '' && $data['amount'] != '') {
                $this->db->insert('multiple_option', $data);
                echo $this->db->last_query();
                redirect(base_url() . 'index.php/admin/product/', 'refresh');
            }
        }
        //superagent
        elseif ($para1 == 'update_split_edit') {

            foreach ($_POST as $key => $value) {
                if ($key != 'split_qty' && $key != 'split_price' && $key != 'mid') {
                    $datas[$key] = $this->input->post($key);
                }
            }
            //echo '<pre>'; print_r($datas); exit;

            $mid = $this->input->post('mid');
            // $data['other_option']     = json_encode($datas, true);
            $data['product_color']  = $this->input->post('color');
            $data['quantitty']      = $this->input->post('split_qty');
            $data['amount']         = $this->input->post('split_price');
            $data['product_id']     = $para2;
            $data['modified_by']     = $this->session->userdata('admin_id');

            //echo '<pre>'; print_r($data); 
            // $precheck = $this->db->get_where('multiple_option', array('product_id' => $para2, 'other_option' => $data['other_option'], 'quantitty' => $data['quantitty'], 'amount' => $data['amount']))->row()->id;

            $precheck = $this->db->get_where('multiple_option', array('product_id' => $para2, 'quantitty' => $data['quantitty'], 'amount' => $data['amount']))->row()->id;




            // if ($precheck == '' && $data['quantitty'] != '' && $data['amount'] != '' && $data['other_option'] != '') {
            if ($precheck == '' && $data['quantitty'] != '' && $data['amount'] != '') {
                //$this->db->insert('multiple_option', $data);
                $this->db->where('id', $mid);
                $this->db->update('multiple_option', $data);
                redirect(base_url() . 'index.php/admin/product/', 'refresh');
            }
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
            $data['store_id']           = $this->input->post('vendor');
            $data['category']           = $this->input->post('category');
            $data['description']        = $this->input->post('description');
            $data['sub_category']       = $this->input->post('sub_category');
            $data['sale_price']         = $this->input->post('sale_price');
            $data['purchase_price']     = $this->input->post('purchase_price');
            $data['enquiry']        = $this->input->post('enquiry');
            $data['subscribe']        = $this->input->post('subscribe');
            $data['callnow']        = $this->input->post('callnow');
            $value  = $this->input->post('product_bid');
            if ($value == 1) {
                $data['bid_start_date']     = $this->input->post('bid_start_date');
                $data['bid_start_time']     = $this->input->post('bid_start_time');
                $data['bid_end_date']       = $this->input->post('bid_end_date');
                $data['bid_end_time']       = $this->input->post('bid_end_time');
                $data['min_bid_amount']     = $this->input->post('min_bid_amount');
                $data['max_bid_amount']     = $this->input->post('max_bid_amount');
            } elseif ($value == 0) {
                $data['bid_start_date']     = ' ';
                $data['bid_start_time']     = ' ';
                $data['bid_end_date']       = ' ';
                $data['bid_end_time']       = ' ';
                $data['min_bid_amount']     = ' ';
                $data['max_bid_amount']     = ' ';
            }
            $data['bidding']            = $this->input->post('product_bid');
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
            $data['ar']                   = $this->input->post('ar');
            $choice_titles              = $this->input->post('op_title');
            $choice_types               = $this->input->post('op_type');
            $choice_no                  = $this->input->post('op_no');
            if (count($choice_titles) > 0) {
                foreach ($choice_titles as $i => $row) {
                    $choice_options         = $this->input->post('op_set' . $choice_no[$i]);
                    $options[]              =   array(
                        'no' => $choice_no[$i],
                        'title' => $choice_titles[$i],
                        'name' => 'choice_' . $choice_no[$i],
                        'type' => $choice_types[$i],
                        'option' => $choice_options
                    );
                }
            }
            $data['options']            = json_encode($options);
            $img1 = $this->crud_model->file_up1("images1", "android", $para2, '', 'no', '.glb');
            $img2 = $this->crud_model->file_up1("images2", "ios", $para2, '', 'no', '.usdz');
            $this->crud_model->file_up("images", "product", $para2, 'multi');
            if ($_FILES['video']['tmp_name'] != '') {
                $this->crud_model->file_up2("video", "product", $para2, '', 'no', '');
            }
            $this->db->where('product_id', $para2);
            $this->db->update('product', $data);
            //echo $this->db->last_query(); 
            $this->crud_model->set_category_data(0);
            recache();
        } else if ($para1 == 'edit') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/product_edit', $page_data);
        } else if ($para1 == 'duplicate') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/product_duplicate', $page_data);
        }  elseif ($para1 == 'duplicate_new') {
            $options = array();

            $exist_img = $this->crud_model->get_type_name_by_id('product', $para2, 'num_of_imgs');
            $new_img = count($_FILES["images"]['name']);
            $num_of_imgs = $exist_img + $new_img;
            
            $data['title']              = $this->input->post('title');
            $data['store_id']           = $this->input->post('vendor');
            $data['category']           = $this->input->post('category');
            $data['description']        = $this->input->post('description');
            $data['ar']                 = $this->input->post('ar');
            $data['enquiry']            = $this->input->post('enquiry');
            $data['subscribe']          = $this->input->post('subscribe');
            $data['callnow']            = $this->input->post('callnow');

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
            $choice_titles              = $this->input->post('op_title');
            $choice_types               = $this->input->post('op_type');
            $choice_no                  = $this->input->post('op_no');
            $data['added_by']           = json_encode(array('type' => 'admin', 'id' => $this->session->userdata('admin_id')));
            if (count($choice_titles) > 0) {
                foreach ($choice_titles as $i => $row) {
                    $choice_options         = $this->input->post('op_set' . $choice_no[$i]);
                    $options[]              =   array(
                        'no' => $choice_no[$i],
                        'title' => $choice_titles[$i],
                        'name' => 'choice_' . $choice_no[$i],
                        'type' => $choice_types[$i],
                        'option' => $choice_options
                    );
                }
            }
            $data['options']            = json_encode($options);
            //echo '<pre>'; print_r(post); exit;
            $this->db->insert('product', $data);
            echo $this->db->last_query(); 
            $id = $this->db->insert_id();
            $this->benchmark->mark_time();

            for($i=1; $i<=$num_of_imgs; $i++) {
                $path = 'product_'.$para2.'_'.$i.'.jpg';
                $path2 = 'product_' . $id .'_'.$i.'.jpg';

                $path_thumb = 'product_'.$para2.'_'.$i.'_thumb.jpg';
                $path2_thumb = 'product_' . $id .'_'.$i. '_thumb.jpg';

                $dir = '/myrunciit/uploads/product_image'; 

                if(file_exists($_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $path)){
                    copy($_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $path, $_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $path2);
                    copy($_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $path_thumb, $_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $path2_thumb); 
                } else {
                    $this->crud_model->file_up("images", "product", $id, 'multi');
                    $this->crud_model->file_up1("images1", "android", $id, '', 'no', '.glb');
                    $this->crud_model->file_up1("images2", "ios", $id, '', 'no', '.usdz');
                }
            }

            if ($_FILES['video']['tmp_name'] != '') {
                $this->crud_model->file_up2("video", "product", $id, '', 'no');
            } else {
                $path = 'product_'.$para2.'.mp4';                
                $path2='product_' . $id . '.mp4';

                $file = 'product_'.$para2;                
                $file2='product_' . $id ;

                $dir = '/myrunciit/uploads/product_image';                                          
                copy($_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $path, $_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $path2);
                copy($_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $file, $_SERVER['DOCUMENT_ROOT'] . $dir . '/' . $file2);
            }
            $this->crud_model->set_category_data(0);
            recache();
        } else if ($para1 == 'view') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/product_view', $page_data);
        } else if ($para1 == 'bidd') {
            //$added_by=$data['added_by'] = json_encode(array('type'=>'admin','id'=>$this->session->userdata('admin_id')));	
            //$added_by = $this->db->get_where('product', array('product_id' => $para2,'status' => '1'))->result_array();	


            //$added_by = $this->db->get_where('product', array('product_id' => $para2))->result_array();
            //$added_by_nw=$added_by['added_by'];
            //$new=json_encode($added_by_nw);	
            //echo '<pre>'; print_r($new); exit;	

            $this->db->select_max('batch_no');
            $this->db->where('pid', $para2);
            $this->db->where('status', 1);
            $this->db->where('payment_status', 1);

            $resa2 = $this->db->get('bidding_history')->result_array();

            $page_data['baatch_max'] = $resa2[0]['batch_no'];

            $new = $this->db->get_where('product', array('product_id' => $para2))->result_array();
            $page_data['mode'] = json_decode($new[0]['added_by'], true);
            $page_data['product_bidd'] = $this->db->order_by('bid_amt', 'DESC')->get_where('bidding_history', array('pid' => $para2, 'status' => '1', 'payment_status' => '1'))->result_array();
            $this->load->view('back/admin/product_bidd', $page_data);
        } elseif ($para1 == 'delete') {
            // $this->crud_model->file_dlt('product', $para2, '.jpg', 'multi');
            // $this->db->where('product_id', $para2);
            // $this->db->delete('product');
            // $this->crud_model->set_category_data(0);
            // recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('product_id', 'desc');
            $this->db->where('download=', NULL);
            if ($para2 != 0) {
                $this->db->where('store_id', $para2);
            }
            if ($para3 != 0) {
                if ($para3 == '1') {
                    $this->db->where('status', '0');
                }
                if ($para3 == '2') {
                    $this->db->where('status', 'ok');
                }
            }
            $page_data['status'] = $para3;
            $page_data['store_id'] = $para2;
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $page_data['user_rights_3_4'] = $this->get_user_rights(3,4);
            //  echo $this->db->last_query(); exit;
            $this->load->view('back/admin/product_list', $page_data);
        } elseif ($para1 == 'today_list') {           
            $page_data['all_deals'] = $this->db->get('today_deals')->result_array();
            $page_data['user_rights_3_4'] = $this->get_user_rights(3,4);
            //  echo $this->db->last_query(); exit;
            $this->load->view('back/admin/deal_list', $page_data);
        } else if ($para1 == 'price_split') {
            $page_data['product_data'] = $this->db->get_where('product', array('product_id' => $para2))->result_array();
            $page_data['multi'] = $this->db->get_where('multiple_option', array('product_id' => $para2))->result_array();
            $this->load->view('back/admin/product_price_split', $page_data);
        } else if ($para1 == 'price_split_edit') {
            $para3 = explode("_", $para2);
            $page_data['product_data'] = $this->db->get_where('product', array('product_id' => $para3[0]))->result_array();
            $page_data['rest'] = $this->db->get_where('multiple_option', array('id' => $para3[1]))->result_array();
            //echo '<pre>'; print_r($re); exit;
            $this->load->view('back/admin/product_price_split_edit', $page_data);
        } elseif ($para1 == 'list_data') {
            $limit      = $this->input->get('limit');
            $search     = $this->input->get('search');
            $order      = $this->input->get('order');
            $offset     = $this->input->get('offset');
            $sort       = $this->input->get('sort');
            if ($search) {
                $this->db->like('title', $search, 'both');
            }
            if ($para2 != 0) {
                $this->db->where('store_id', $para2);
            }
            if ($para3 != 0) {
                if ($para3 == '1') {
                    $this->db->where('status', '0');
                }
                if ($para3 == '2') {
                    $this->db->where('status', 'ok');
                }
            }
            $this->db->where('download=', NULL);
            $total      = $this->db->get('product')->num_rows();
            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'product_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            if ($search) {
                $this->db->like('title', $search, 'both');
            }
            $this->db->where('download=', NULL);
            if ($para2 != 0) {
                $this->db->where('store_id', $para2);
            }
            if ($para3 != 0) {
                if ($para3 == '1') {
                    $this->db->where('status', '0');
                }
                if ($para3 == '2') {
                    $this->db->where('status', 'ok');
                }
            }
            $products   = $this->db->get('product', $limit, $offset)->result_array();
            //  echo $this->db->last_query();
            $data       = array();
            foreach ($products as $row) {

                $res    = array(
                    'image' => '',
                    'store_name' => '',
                    'title' => '',
                    'current_stock' => '',
                    'deal' => '',
                    'publish' => ''
                );

                $res['image']  = '<img class="img-sm" style="border:1px solid #ddd;padding:2px; border-radius:2px !important;" src="' . $this->crud_model->file_view('product', $row['product_id'], '', '', 'thumb', 'src', 'multi', 'one') . '"  />';
                $store_name  = $this->db->get_where('vendor', array(
                    'vendor_id' => $row['store_id']
                ))->row()->name;

                $res['store_name'] = "<div style='display: flex; justify-content: space-between; align-items: center;'><div style='text-align: left;'>".$store_name." </div> <div class=\"btn-group\" ><span id=\"action_dropdownMenu1\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                <i class=\"fa fa-chevron-circle-down\" aria-hidden=\"true\"></i></span>
                   <ul class=\"dropdown-menu\" aria-labelledby=\"action_dropdownMenu1\">
                           <li><a class=\"btn btn-info btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\" 
                               onclick=\"ajax_set_full('price_split','" . translate('edit_product') . "','" . translate('successfully_added!') . "','product_price_split','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"Product Price Split\" data-container=\"body\">
                                   " . translate('Price / Qty Split') . "
                </a></li>
                <li><a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\" 
                onclick=\"ajax_set_full('view','" . translate('view_product') . "','" . translate('successfully_viewed!') . "','product_view','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                    " . translate('view') . "
            </a></li>
           <li> <a class=\"btn btn-purple btn-xs btn-labeled fa fa-tag\" data-toggle=\"tooltip\"
            onclick=\"ajax_modal('add_discount','" . translate('view_discount') . "','" . translate('viewing_discount!') . "','add_discount','" . $row['product_id'] . "')\" data-original-title=\"Edit\" data-container=\"body\">
                " . translate('discount') . "
        </a></li>
       <li> <a class=\"btn btn-mint btn-xs btn-labeled fa fa-plus-square\" data-toggle=\"tooltip\" 
        onclick=\"ajax_modal('add_stock','" . translate('add_product_quantity') . "','" . translate('quantity_added!') . "','stock_add','" . $row['product_id'] . "')\" data-original-title=\"Edit\" data-container=\"body\">
            " . translate('stock') . "
    </a></li>
   <li> <a class=\"btn btn-dark btn-xs btn-labeled fa fa-minus-square\" data-toggle=\"tooltip\" 
        onclick=\"ajax_modal('destroy_stock','" . translate('reduce_product_quantity') . "','" . translate('quantity_reduced!') . "','destroy_stock','" . $row['product_id'] . "')\" data-original-title=\"Edit\" data-container=\"body\">
            " . translate('destroy') . "
    </a></li>

    <li><a class=\"btn btn-info  btn-xs btn-labeled fa fa-puzzle-piece\" data-toggle=\"tooltip\" onclick=\"ajax_set_full('duplicate','".translate('duplicate_product')."','".translate('successfully_duplicated!')."','product_duplicate','".$row['product_id']."');proceed('to_list');\" data-original-title=\"Duplicate\" data-container=\"body\">" . translate('duplicate') . "
    </a></li>
    
    <li><a class=\"btn btn-info btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\" onclick=\"ajax_set_full('edit','" . translate('edit_product') . "','" . translate('successfully_edited!') . "','product_edit','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">" . translate('edit') . "
    </a></li> 
    </ul></div></div>";

                $res['title']  = $row['title'];
                if ($row['status'] == 'ok') {
                    $res['publish']  = '<input id="pub_' . $row['product_id'] . '" class="sw1" type="checkbox" data-id="' . $row['product_id'] . '" checked />';
                } else {
                    $res['publish']  = '<input id="pub_' . $row['product_id'] . '" class="sw1" type="checkbox" data-id="' . $row['product_id'] . '" />';
                }
                if ($row['current_stock'] > 0) {
                    $res['current_stock']  = $row['current_stock'] . $row['unit'] . '(s)';
                } else {
                    $res['current_stock']  = '<span class="label label-danger">' . translate('out_of_stock') . '</span>';
                }
                if ($row['deal'] == 'ok') {
                    $res['deal']  = '<input id="deal_' . $row['product_id'] . '" class="sw3" type="checkbox" data-id="' . $row['product_id'] . '" checked />';
                } else {
                    $res['deal']  = '<input id="deal_' . $row['product_id'] . '" class="sw3" type="checkbox" data-id="' . $row['product_id'] . '" />';
                }
              

                if ($row['bidding'] == 1) {
                    $product_bidd = "<a class=\"btn btn-info btn-xs btn-labeled fa fa-thumbs-up\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('bidd','" . translate('bidding') . "','" . translate('successfully_viewed!') . "','product_view','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                    " . translate('bidd') . "
                            </a>";
                } else {
                    $product_bidd = '';
                }

                //add html for action
               
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
        } elseif ($para1 == 'sub_by_cat_coupon') {
            echo $this->crud_model->select_html('sub_category', 'sub_category', 'sub_category_name', 'add', 'demo-chosen-select required', '', 'category', $para2, 'product_by_sub');
        } elseif ($para1 == 'brand_by_sub') {
            $brands = json_decode($this->crud_model->get_type_name_by_id('sub_category', $para2, 'brand'), true);
            if (empty($brands)) {
                echo translate("No brands are available for this sub category");
            } else {
                echo $this->crud_model->select_html('brand', 'brand', 'name', 'add', 'demo-chosen-select required', '', 'brand_id', $brands, '', 'multi');
            }
        } elseif ($para1 == 'product_by_sub') {
            echo $this->crud_model->select_html('product', 'product', 'title', 'add', 'demo-chosen-select required', '', 'sub_category', $para2, 'get_pro_res');
        } elseif ($para1 == 'pur_by_pro') {
            echo $this->crud_model->get_type_name_by_id('product', $para2, 'purchase_price');
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/product_add');
        } elseif ($para1 == 'add_stock') {
            $data['product'] = $para2;
            $this->load->view('back/admin/product_stock_add', $data);
        } elseif ($para1 == 'destroy_stock') {
            $data['product'] = $para2;
            $this->load->view('back/admin/product_stock_destroy', $data);
        } elseif ($para1 == 'stock_report') {
            $data['product'] = $para2;
            $this->load->view('back/admin/product_stock_report', $data);
        } elseif ($para1 == 'sale_report') {
            $data['product'] = $para2;
            $this->load->view('back/admin/product_sale_report', $data);
        } elseif ($para1 == 'add_discount') {
            $data['product'] = $para2;
            $this->load->view('back/admin/product_add_discount', $data);
        } else if ($para1 == "prod_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = '1';
            } else if ($para3 == 'false') {
                $val = '0';
            }
            echo $val;
            $this->db->where('id', $para2);
            $this->db->update('bidding_history', array(
                'status' => $val
            ));
            recache();
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
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Digital add, edit, view, delete, stock increase, decrease, discount */
    function digital($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('product')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '69', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
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
            $data['update_time']        = time();
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
            $data['requirements']        =    '[]';
            $data['video']                =    '[]';

            $data['added_by']           = json_encode(array('type' => 'admin', 'id' => $this->session->userdata('admin_id')));

            $this->db->insert('product', $data);
            $id = $this->db->insert_id();
            $this->benchmark->mark_time();

            $this->crud_model->file_up("images", "product", $id, 'multi');

            $path = $_FILES['logo']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $data_logo['logo']          = 'digital_logo_' . $id . '.' . $ext;
            $this->db->where('product_id', $id);
            $this->db->update('product', $data_logo);
            $this->crud_model->file_up("logo", "digital_logo", $id, '', 'no', '.' . $ext);

            //Requirements add
            $requirements                =    array();
            $req_title                    =    $this->input->post('req_title');
            $req_desc                    =    $this->input->post('req_desc');
            if (!empty($req_title)) {
                foreach ($req_title as $i => $row) {
                    $requirements[]            =    array('index' => $i, 'field' => $row, 'desc' => $req_desc[$i]);
                }
            }

            $data_req['requirements']            =    json_encode($requirements);
            $this->db->where('product_id', $id);
            $this->db->update('product', $data_req);

            //File upload
            $rand           = substr(hash('sha512', rand()), 0, 20);
            $name           = $id . '_' . $rand . '_' . $_FILES['product_file']['name'];
            $da['download_name'] = $name;
            $da['download'] = 'ok';
            $folder = $this->db->get_where('general_settings', array('type' => 'file_folder'))->row()->value;
            move_uploaded_file($_FILES['product_file']['tmp_name'], 'uploads/file_products/' . $folder . '/' . $name);
            $this->db->where('product_id', $id);
            $this->db->update('product', $da);

            //vdo upload
            $video_details                =    array();
            if ($this->input->post('upload_method') == 'upload') {
                $video                 =     $_FILES['videoFile']['name'];
                $ext                   =     pathinfo($video, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES['videoFile']['tmp_name'], 'uploads/video_digital_product/digital_' . $id . '.' . $ext);
                $video_src             =     'uploads/video_digital_product/digital_' . $id . '.' . $ext;
                $video_details[]     =     array('type' => 'upload', 'from' => 'local', 'video_link' => '', 'video_src' => $video_src);
                $data_vdo['video']    =    json_encode($video_details);
                $this->db->where('product_id', $id);
                $this->db->update('product', $data_vdo);
            } elseif ($this->input->post('upload_method') == 'share') {
                $from                 = $this->input->post('site');
                $video_link         = $this->input->post('video_link');
                $code                = $this->input->post('video_code');
                if ($from == 'youtube') {
                    $video_src      = 'https://www.youtube.com/embed/' . $code;
                } else if ($from == 'dailymotion') {
                    $video_src       = '//www.dailymotion.com/embed/video/' . $code;
                } else if ($from == 'vimeo') {
                    $video_src       = 'https://player.vimeo.com/video/' . $code;
                }
                $video_details[]     =     array('type' => 'share', 'from' => $from, 'video_link' => $video_link, 'video_src' => $video_src);
                $data_vdo['video']    =    json_encode($video_details);
                $this->db->where('product_id', $id);
                $this->db->update('product', $data_vdo);
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
            if ($_FILES['product_file']['name'] !== '') {
                $rand           = substr(hash('sha512', rand()), 0, 20);
                $name           = $para2 . '_' . $rand . '_' . $_FILES['product_file']['name'];
                $data['download_name'] = $name;
                $folder = $this->db->get_where('general_settings', array('type' => 'file_folder'))->row()->value;
                move_uploaded_file($_FILES['product_file']['tmp_name'], 'uploads/file_products/' . $folder . '/' . $name);
            }

            $this->db->where('product_id', $para2);
            $this->db->update('product', $data);

            if ($_FILES['logo']['name'] !== '') {
                $path = $_FILES['logo']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_logo['logo']          = 'digital_logo_' . $para2 . '.' . $ext;
                $this->db->where('product_id', $para2);
                $this->db->update('product', $data_logo);
                $this->crud_model->file_up("logo", "digital_logo", $para2, '', 'no', '.' . $ext);
            }

            //Requirements add
            $requirements                =    array();
            $req_title                    =    $this->input->post('req_title');
            $req_desc                    =    $this->input->post('req_desc');
            if (!empty($req_title)) {
                foreach ($req_title as $i => $row) {
                    $requirements[]            =    array('index' => $i, 'field' => $row, 'desc' => $req_desc[$i]);
                }
            }
            $data_req['requirements']            =    json_encode($requirements);
            $this->db->where('product_id', $para2);
            $this->db->update('product', $data_req);

            //vdo upload
            $video_details                =    array();
            if ($this->input->post('upload_method') == 'upload') {
                $video                 =     $_FILES['videoFile']['name'];
                $ext                   =     pathinfo($video, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES['videoFile']['tmp_name'], 'uploads/video_digital_product/digital_' . $para2 . '.' . $ext);
                $video_src             =     'uploads/video_digital_product/digital_' . $para2 . '.' . $ext;
                $video_details[]     =     array('type' => 'upload', 'from' => 'local', 'video_link' => '', 'video_src' => $video_src);
                $data_vdo['video']    =    json_encode($video_details);
                $this->db->where('product_id', $para2);
                $this->db->update('product', $data_vdo);
            } elseif ($this->input->post('upload_method') == 'share') {
                $video = json_decode($this->crud_model->get_type_name_by_id('product', $para2, 'video'), true);
                if ($video[0]['type'] == 'upload') {
                    if (file_exists($video[0]['video_src'])) {
                        unlink($video[0]['video_src']);
                    }
                }
                $from                 = $this->input->post('site');
                $video_link         = $this->input->post('video_link');
                $code                = $this->input->post('video_code');
                if ($from == 'youtube') {
                    $video_src      = 'https://www.youtube.com/embed/' . $code;
                } else if ($from == 'dailymotion') {
                    $video_src       = '//www.dailymotion.com/embed/video/' . $code;
                } else if ($from == 'vimeo') {
                    $video_src       = 'https://player.vimeo.com/video/' . $code;
                }
                $video_details[]     =     array('type' => 'share', 'from' => $from, 'video_link' => $video_link, 'video_src' => $video_src);
                $data_vdo['video']    =    json_encode($video_details);
                $this->db->where('product_id', $para2);
                $this->db->update('product', $data_vdo);
            } elseif ($this->input->post('upload_method') == 'delete') {
                $data_vdo['video']    =    '[]';
                $this->db->where('product_id', $para2);
                $this->db->update('product', $data_vdo);

                $video = json_decode($this->crud_model->get_type_name_by_id('product', $para2, 'video'), true);
                if ($video[0]['type'] == 'upload') {
                    if (file_exists($video[0]['video_src'])) {
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
            $this->load->view('back/admin/digital_edit', $page_data);
        } else if ($para1 == 'view') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/digital_view', $page_data);
        } else if ($para1 == 'download_file') {
            $this->crud_model->download_product($para2);
        } else if ($para1 == 'can_download') {
            if ($this->crud_model->can_download($para2)) {
                echo "yes";
            } else {
                echo "no";
            }
        } elseif ($para1 == 'delete') {
            $this->crud_model->file_dlt('product', $para2, '.jpg', 'multi');
            unlink("uploads/digital_logo_image/" . $this->crud_model->get_type_name_by_id('product', $para2, 'logo'));
            $video = $this->crud_model->get_type_name_by_id('product', $para2, 'video');
            if ($video !== '[]') {
                $video_details = json_decode($video, true);
                if ($video_details[0]['type'] == 'upload') {
                    if (file_exists($video_details[0]['video_src'])) {
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
            $this->db->where('download=', 'ok');
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $this->load->view('back/admin/digital_list', $page_data);
        } elseif ($para1 == 'list_data') {
            $limit      = $this->input->get('limit');
            $search     = $this->input->get('search');
            $order      = $this->input->get('order');
            $offset     = $this->input->get('offset');
            $sort       = $this->input->get('sort');
            if ($search) {
                $this->db->like('title', $search, 'both');
            }
            $this->db->where('download=', 'ok');
            $total = $this->db->get('product')->num_rows();
            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'product_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            if ($search) {
                $this->db->like('title', $search, 'both');
            }
            $this->db->where('download=', 'ok');
            $products   = $this->db->get('product', $limit, $offset)->result_array();
            $data       = array();
            foreach ($products as $row) {

                $res    = array(
                    'image' => '',
                    'title' => '',
                    'deal' => '',
                    'publish' => '',
                    'featured' => '',
                    'options' => ''
                );

                $res['image']  = '<img class="img-sm" style="height:auto !important; border:1px solid #ddd;padding:2px; border-radius:2px !important;" src="' . $this->crud_model->file_view('product', $row['product_id'], '', '', 'thumb', 'src', 'multi', 'one') . '"  />';
                $res['title']  = $row['title'];
                if ($row['status'] == 'ok') {
                    $res['publish']  = '<input id="pub_' . $row['product_id'] . '" class="sw1" type="checkbox" data-id="' . $row['product_id'] . '" checked />';
                } else {
                    $res['publish']  = '<input id="pub_' . $row['product_id'] . '" class="sw1" type="checkbox" data-id="' . $row['product_id'] . '" />';
                }
                if ($row['deal'] == 'ok') {
                    $res['deal']  = '<input id="deal_' . $row['product_id'] . '" class="sw3" type="checkbox" data-id="' . $row['product_id'] . '" checked />';
                } else {
                    $res['deal']  = '<input id="deal_' . $row['product_id'] . '" class="sw3" type="checkbox" data-id="' . $row['product_id'] . '" />';
                }
                if ($row['featured'] == 'ok') {
                    $res['featured'] = '<input id="fet_' . $row['product_id'] . '" class="sw2" type="checkbox" data-id="' . $row['product_id'] . '" checked />';
                } else {
                    $res['featured'] = '<input id="fet_' . $row['product_id'] . '" class="sw2" type="checkbox" data-id="' . $row['product_id'] . '" />';
                }

                //add html for action
                $res['options'] = "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('view','" . translate('view_product') . "','" . translate('successfully_viewed!') . "','digital_view','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                    " . translate('view') . "
                            </a>
                            <a class=\"btn btn-purple btn-xs btn-labeled fa fa-tag\" data-toggle=\"tooltip\"
                                onclick=\"ajax_modal('add_discount','" . translate('view_discount') . "','" . translate('viewing_discount!') . "','add_discount','" . $row['product_id'] . "')\" data-original-title=\"Edit\" data-container=\"body\">
                                    " . translate('discount') . "
                            </a>
                            <a class=\"btn btn-mint btn-xs btn-labeled fa fa-download\" data-toggle=\"tooltip\" 
                                onclick=\"digital_download(" . $row['product_id'] . ")\" data-original-title=\"Download\" data-container=\"body\">
                                    " . translate('download') . "
                            </a>
                            
                            <a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('edit','" . translate('edit_product_(_digital_product_)') . "','" . translate('successfully_edited!') . "','digital_edit','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                                    " . translate('edit') . "
                            </a>
                            
                            <a onclick=\"delete_confirm('" . $row['product_id'] . "','" . translate('really_want_to_delete_this?') . "')\" 
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    " . translate('delete') . "
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
        } elseif ($para1 == 'pur_by_pro') {
            echo $this->crud_model->get_type_name_by_id('product', $para2, 'purchase_price');
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/digital_add');
        } elseif ($para1 == 'sale_report') {
            $data['product'] = $para2;
            $this->load->view('back/admin/product_sale_report', $data);
        } elseif ($para1 == 'add_discount') {
            $data['product'] = $para2;
            $this->load->view('back/admin/digital_add_discount', $data);
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
        } elseif ($para1 == 'video_preview') {
            if ($para2 == 'youtube') {
                echo '<iframe width="400" height="300" src="https://www.youtube.com/embed/' . $para3 . '" frameborder="0"></iframe>';
            } else if ($para2 == 'dailymotion') {
                echo '<iframe width="400" height="300" src="//www.dailymotion.com/embed/video/' . $para3 . '" frameborder="0"></iframe>';
            } else if ($para2 == 'vimeo') {
                echo '<iframe src="https://player.vimeo.com/video/' . $para3 . '" width="400" height="300" frameborder="0"></iframe>';
            }
        } else {
            $page_data['page_name']   = "digital";
            $this->db->order_by('product_id', 'desc');
            $this->db->where('download=', 'ok');
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Product Stock add, edit, view, delete, stock increase, decrease, discount */
    function stock($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('stock')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
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
        } else if ($para1 == 'edit') {
            $page_data['stock_data'] = $this->db->get_where('stock', array(
                'stock_id' => $para2
            ))->result_array();            
            $this->load->view('back/admin/stock_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['quantity'] = $this->input->post('quantity');
            $data['rate']          = $this->input->post('rate');
            $data['total']          = $this->input->post('total');
            $data['reason_note']          = $this->input->post('reason_note');
            $data['user_id']          = $_SESSION['user_id'];  
            $quantity = $this->crud_model->get_type_name_by_id('stock', $para2, 'quantity');
            if($data['quantity'] > $quantity){
                $data['type']          = 'add';
            } else if($data['quantity'] < $quantity){
                $data['type']          = 'destroy';
            }      
            $this->db->where('stock_id', $para2);
            $this->db->update('stock', $data);
            
            // $this->crud_model->set_category_data(0);
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
            $page_data['all_stock'] = $this->db->get('stock')->result_array();
            $page_data['user_rights_3_6'] = $this->get_user_rights(3,6);
            $this->load->view('back/admin/stock_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/stock_add');
        } elseif ($para1 == 'destroy') {
            $this->load->view('back/admin/stock_destroy');
        } elseif ($para1 == 'sub_by_cat') {
            echo $this->crud_model->select_html('sub_category', 'sub_category', 'sub_category_name', 'add', 'demo-chosen-select required', '', 'category', $para2, 'get_product');
        } elseif ($para1 == 'pro_by_sub') {
            echo $this->crud_model->select_html('product', 'product', 'title', 'add', 'demo-chosen-select required', '', 'sub_category', $para2, 'get_pro_res');
        } else {
            $page_data['page_name'] = "stock";
            $page_data['all_stock'] = $this->db->get('stock')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /*Frontend Banner Management */
    function banner($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('banner')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == "set") {
            $data['link']   = $this->input->post('link');
            $data['status'] = $this->input->post('status');
            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data['image_ext']          = '.' . $ext;
                $this->crud_model->file_up("img", "banner", $para2, '', '', '.' . $ext);
            }
            $this->db->where('banner_id', $para2);
            $this->db->update('banner', $data);
            $this->crud_model->file_up("img", "banner", $para2);
            recache();
        } else if ($para1 == 'banner_publish_set') {
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else if ($para3 == 'false') {
                $data['status'] = '0';
            }
            $this->db->where('banner_id', $para2);
            $this->db->update('banner', $data);
            recache();
        }
    }

    /* Managing sales by users */
    function sales($para1 = '', $para2 = '', $para3 = '', $para4 = '', $para5 = '', $para6 = '', $para7 = '', $para8 = '', $para9 = '')
    {
        if (!$this->crud_model->admin_permission('sale')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->input->post() != '') {

            $vendor = $page_data['vendor'] = $this->input->post('vendor');
            $from = $page_data['from'] = $this->input->post('from');
            $to = $page_data['to'] = $this->input->post('to');
            $mode = $page_data['mode'] = $this->input->post('mode');
            $delv_status = $page_data['delv_status'] = $this->input->post('delv_status');
            $order_status = $page_data['order_status'] = $this->input->post('order_status');
            $pre_order_status = $page_data['pre_order_status'] = $this->input->post('pre_order_status');
            $payment_sts = $page_data['payment_sts'] = $this->input->post('payment_sts'); 
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
            $all = $this->db->get_where('sale', array('payment_type' => 'go'))->result_array();
            foreach ($all as $row) {
                if ((time() - $row['sale_datetime']) > 600) {
                    $this->db->where('sale_id', $row['sale_id']);
                    $this->db->delete('sale');
                }
            }

            $this->db->select('
    MAX(sale_id) AS sale_id,
    order_id,
    MAX(store_id) AS store_id,
    MAX(delivery_agent_id) AS delivery_agent_id,
    MAX(delivery_pickup_date) AS delivery_pickup_date,
    MAX(delivery_pickup_time) AS delivery_pickup_time,
    MAX(read_status) AS read_status,
    MAX(package_details) AS package_details,
    MAX(bill_address) AS bill_address,
    MAX(order_type) AS order_type,
    MAX(group_deal) AS group_deal,
    MAX(vendor_delivery) AS vendor_delivery,
    MAX(order_notes) AS order_notes,
    MAX(created_datetime) AS created_datetime,
    MAX(sale_code) AS sale_code,
    MAX(buyer) AS buyer,
    MAX(guest_id) AS guest_id,
    MAX(seller) AS seller,
    MAX(product_details) AS product_details,
    MAX(shipping_address) AS shipping_address,
    MAX(vat) AS vat,
    MAX(vat_percent) AS vat_percent,
    MAX(imei) AS imei,
    MAX(shipping_id) AS shipping_id,
    MAX(shipping) AS shipping,
    MAX(payment_type) AS payment_type,
    MAX(payment_status) AS payment_status,
    MAX(payment_details) AS payment_details,
    MAX(payment_timestamp) AS payment_timestamp,
    MAX(grand_total) AS grand_total,
    MAX(order_amount) AS order_amount,
    MAX(sale_datetime) AS sale_datetime,
    MAX(delivary_datetime) AS delivary_datetime,
    MAX(status) AS status,
    MAX(refund_status) AS refund_status,
    MAX(refund_date) AS refund_date,
    MAX(delivery_status) AS delivery_status,
    MAX(cancel_status) AS cancel_status,
    MAX(cancel_reason) AS cancel_reason,
    MAX(cancel_remarks) AS cancel_remarks,
    MAX(return_status) AS return_status,
    MAX(return_reason) AS return_reason,
    MAX(return_remarks) AS return_remarks,
    MAX(return_action) AS return_action,
    MAX(order_trackment) AS order_trackment,
    MAX(review) AS review,
    MAX(viewed) AS viewed,
    MAX(exchange_id) AS exchange_id,
    MAX(refund_id) AS refund_id,
    MAX(crm) AS crm,
    MAX(courier_name) AS courier_name,
    MAX(awb_code) AS awb_code,
    MAX(awb_code_status) AS awb_code_status,
    MAX(shipment_id) AS shipment_id,
    MAX(shiprocket_orderid) AS shiprocket_orderid,
    MAX(pick_up_status) AS pick_up_status,
    MAX(pickup_details) AS pickup_details,
    MAX(pickup_date) AS pickup_date,
    MAX(pickup_slot) AS pickup_slot,
    MAX(total_invoice_id) AS total_invoice_id,
    MAX(promo_code) AS promo_code,
    MAX(product_notes) AS product_notes,
    MAX(rewards) AS rewards,
    MAX(pre_order_status) AS pre_order_status,
    MAX(pre_order_date) AS pre_order_date,
    MAX(rewards_using) AS rewards_using,
    MAX(reward_using_amt) AS reward_using_amt,
    MAX(staff_id) AS staff_id,
    MAX(lalamove_res) AS lalamove_res,
    MAX(discount) AS discount
');
$this->db->from('sale');
$this->db->group_by('order_id');
$this->db->order_by('sale_id', 'DESC');
            
            if ($para2 != '0') {

                //$v_where ='VE-'.$para2.'-';
                //$this->db->like('sale_code',$v_where);
                $this->db->where('store_id', $para2);
            }
            if ($para3 != '0') {
                $from = strtotime($para3 . ' 00:00:00');
                $this->db->where('sale_datetime >=', $from);
            }
            if ($para4 != '0') {
                $to = strtotime($para4 . ' 23:59:59');
                $this->db->where('sale_datetime <=', $to);
            }
            if ($para5 != '0') {

                $this->db->where('order_type', $para5);
            }
            if ($para6 != '0') {

                $this->db->like('delivery_status', $para6);
            }
            
            if ($para7 != '0') {

                $this->db->like('status', $para7);
            }
            if ($para8 != '0') {

                $this->db->like('pre_order_status', $para8);
            }
            if ($para9 != '0') {
                if($para9 == '1' ) {$pmt_sts = '{"admin":"","status":"failed"}';}
                if($para9 == '2' ) {$pmt_sts = '{"admin":"","status":"due"}';}
                if($para9 == '3' ) {$pmt_sts = '{"admin":"","status":"paid"}';}
                $this->db->like('payment_status', $pmt_sts);
            }
            $page_data['all_sales'] = $this->db->get('')->result_array();
            //echo $this->db->last_query();
            $new_stat = $page_data['all_sales'];
            $page_data['agent_id'] = $new_stat[0]['delivery_agent_id'];
            $page_data['user_rights_5_0'] = $this->get_user_rights(5,0);
            //$page_data['agent_id'] = $this->db->get_where('delivery_agent', array('delivery_agent_id' => $agent_id))->result_array();
            // echo '<pre>'; print_r($page_data['agtent_id']); exit;
            $this->load->view('back/admin/sales_list', $page_data);
        } elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/sales_view', $page_data);
        } elseif ($para1 == 'accept') {
            $data['viewed'] = 'ok';

            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $page_data['sale_id']         = $para2;
            $this->load->view('back/admin/sales_accept', $page_data);
        } elseif ($para1 == 'accept_set') {
            $status = $this->input->post('status');
            if ($status == 'approve') {
                
                $data['status'] = 'success';
                
                $this->crud_model->placeOrder($para2);
                // exit;
            }
            if ($status == 'reject') {

                $data['status'] = 'rejected';
            }


            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);

            $price_sale = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $d_rewards = $price_sale[0]['grand_total'];
            $user_id = $price_sale[0]['buyer'];
            $user_rewards = $price_sale[0]['rewards'];

            if ($user_id != '' && $user_id != 'guest') {
                $tot_rwds = $this->db->get_where('user', array(
                    'user_id', $user_id
                ))->row()->rewards;
                $fin_rwds = $tot_rwds - $user_rewards;
                $data1['rewards'] = $fin_rwds + $d_rewards;

                $this->db->where('user_id', $user_id);

                $this->db->update('user', $data1);

                $data_refund['refund_status'] = '1';
                $data_refund['refund_date'] = time();
                $data_refund['rewards'] = $d_rewards;
                $this->db->where('sale_id', $para2);
                $this->db->update('sale', $data_refund);
            }

            // echo $this->db->last_query();
            // $page_data['sale_id']         = $para2;
            // $this->load->view('back/admin/sales_accept', $page_data);
        } elseif ($para1 == 'assign') {

            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $page_data['sale_id']         = $para2;
            $this->load->view('back/admin/assign_staff_page', $page_data);
        } elseif ($para1 == 'assign_staff_set') {

            echo $data['staff_id'] = $this->input->post('admin');
            echo $para2;


            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            echo $this->db->last_query();
        } elseif ($para1 == 'send_invoice') {
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $text              = $this->load->view('back/includes_top', $page_data);
            $text .= $this->load->view('back/admin/sales_view', $page_data);
            $text .= $this->load->view('back/includes_bottom', $page_data);
        } elseif ($para1 == 'delivery_payment') {
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
            ))->row()->delivery_status, true);
            foreach ($delivery_status as $row) {
                if (isset($row['admin'])) {
                    $page_data['delivery_status'] = $row['status'];
                } else {
                    $page_data['delivery_status'] = '';
                }
            }
            $payment_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->payment_status, true);
            foreach ($payment_status as $row) {
                if (isset($row['admin'])) {
                    $page_data['payment_status'] = $row['status'];
                } else {
                    $page_data['payment_status'] = '';
                }
            }
            $page_data['deliveryResponse'] = $this->crud_model->getOrderDetail($para2);
            // exit;
            $this->load->view('back/admin/sales_delivery_payment', $page_data);
        } elseif ($para1 == 'delivery_payment_set') {
            $delivery_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->delivery_status, true);
            $new_delivery_status = array();
            foreach ($delivery_status as $row) {
                if (isset($row['admin'])) {
                    $new_delivery_status[] = array('admin' => '', 'status' => $this->input->post('delivery_status'), 'delivery_time' => $row['delivery_time']);
                } else {
                    $new_delivery_status[] = array('vendor' => $row['vendor'], 'status' => $row['status'], 'delivery_time' => $row['delivery_time']);
                }
            }
            $payment_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->payment_status, true);
            $new_payment_status = array();
            foreach ($payment_status as $row) {
                if (isset($row['admin'])) {
                    $new_payment_status[] = array('admin' => '', 'status' => $this->input->post('payment_status'));
                } else {
                    $new_payment_status[] = array('vendor' => $row['vendor'], 'status' => $row['status']);
                }
            }
            $data['payment_status']  = json_encode($new_payment_status);
            $data['delivery_status'] = json_encode($new_delivery_status);
            $data['payment_details'] = $this->input->post('payment_details');
            $delivery_status = $this->input->post('delivery_status');
            if ($delivery_status == "on_delivery") {
                $data['order_trackment'] = 6;
            } elseif ($delivery_status == "delivered") {
                $data['order_trackment'] = 3;
                if ($this->db->get_where('sale', array('sale_id' => $para2))->row()->cash_pack_status == 'pending') {
                    $data['cash_pack_status']     = 'success';
                    $user_id = $this->db->get_where('sale', array('sale_id' => $para2))->row()->buyer;
                    $cash_pack = $this->db->get_where('sale', array('sale_id' => $para2))->row()->cash_pack;
                    $this->wallet_model->add_reward_balance($cash_pack, $user_id);
                }
            }
            $data['delivary_datetime']     = time();
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/sales_add');
        } elseif ($para1 == 'total') {
            echo $this->db->get('sale')->num_rows();
        } else if ($para1 == 'assign_courier_set') {
            //echo 1; exit;
            $sale_id = $para2;
            //$courier_id=$this->input->post('assign_courier');
            //$data['courier_id']=$courier_id;
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $orderDet = $this->db->get_where('sale', array('sale_id' => $para2))->result_array();
            $orderDet = $orderDet[0];
            //echo "<pre>"; print_r($orderDet); echo "</pre>";
            $shipping_address = json_decode($orderDet['shipping_address'], 1);
            $order_id = $orderDet['order_id'];
            $order_date = date('Y-m-d', strtotime($orderDet['created_datetime']));
            $pickup_location = "Primary";
            $billing_customer_name = $shipping_address['firstname'];
            $billing_last_name = $shipping_address['firstname'];
            $billing_address = $shipping_address['address1'];
            $billing_city = $shipping_address['cities'];
            $billing_pincode = $shipping_address['zip'];
            $billing_state = $shipping_address['state'] = 'Tamilnadu';
            $billing_country = $shipping_address['country'] = 'India';
            $billing_email = $shipping_address['email'];
            $billing_phone = $shipping_address['phone'];
            $shipping_is_billing = true;
            $shipping_address = $billing_address;
            $shipping_pincode = $billing_pincode;
            $shipping_country = $billing_country;
            $shipping_state = $billing_state;
            $shipping_email = $billing_email;
            $shipping_phone = $billing_phone;
            $product_details = json_decode($orderDet['product_details'], 1);
            $order_items = array();
            foreach ($product_details as $pd) {
                $total = round($pd['subtotal']);
                $order_items[] = array("name" => $pd['name'], "sku" => 256, "units" => $pd['qty'], "selling_price" => $total, "discount" => "", "tax" => "", "hsn" => 441122);
            }
            if ($orderDet['payment_type'] == 'cash_on_delivery') {
                $payment_method = "COD";
            } else {
                $payment_method = "Prepaid";
            }
            $sub_total = round($orderDet['grand_total']);
            $length = 5;
            $breadth = 5;
            $height = 5;
            $weight = 0.5;
            $auth = $this->crud_model->authentication();
            $authtoken = $auth['token']; //exit;
            $orderdata = array("order_id" => $order_id, "order_date" => $order_date, "pickup_location" => $pickup_location, "channel_id" => '293502', "billing_customer_name" => $billing_customer_name, "billing_last_name" => $billing_last_name, "billing_address" => $billing_address, "billing_city" => $billing_city, "billing_pincode" => $billing_pincode, "billing_state" => $billing_state, "billing_country" => $billing_country, "billing_email" => $billing_email, "billing_phone" => $billing_phone, "shipping_is_billing" => $shipping_is_billing, "shipping_address" => $shipping_address, "shipping_pincode" => $shipping_pincode, "shipping_country" => $shipping_country, "shipping_state" => $shipping_state, "shipping_email" => $shipping_email, "shipping_phone" => $shipping_phone, "order_items" => $order_items, "payment_method" => $payment_method, "sub_total" => $sub_total, "length" => $length, "breadth" => $breadth, "height" => $height, "weight" => $weight);
            $postdata = json_encode($orderdata);
            //echo $postdata; //exit;
            $shipOrderdet = $this->crud_model->create_order($authtoken, $postdata);
            //print_r($shipOrderdet); exit;
            if ($shipOrderdet['message']) {
                $page_data['message'] = $shipOrderdet['message'];
                $page_data['errors'] = $shipOrderdet['errors'];
                $page_data['page_name'] = "assign_courier_set";
                $this->load->view('back/admin/assign_courier_set', $page_data);
            } else {
                $ship_orderid = $shipOrderdet['order_id'];
                $shipment_id = $shipOrderdet['shipment_id'];
                //echo "<pre>"; print_r($shipOrderdet); echo "</pre>";  
                $this->db->where('sale_id', $para2);
                $this->db->update('sale', array("shiprocket_orderid" => $ship_orderid, "shipment_id" => $shipment_id));
                echo "<pre>";
                print_r($shipOrderdet);
                echo "</pre>";
                $courier = $this->crud_model->generate_awb($authtoken, $shipment_id);
                //echo "<pre>"; print_r($courier); echo "</pre>";
                if ($courier['message'])
                //if($courier['awb_assign_status'] !=1 )
                {
                    $page_data['ship_orderid'] = $ship_orderid;
                    $page_data['shipment_id'] = $shipment_id;
                    $page_data['awb_message'] = $courier['message'];
                    $page_data['page_name'] = "assign_courier_set";
                    $this->load->view('back/admin/assign_courier_set', $page_data);
                } else {
                    $awb_code = $courier['response']['data']['awb_code'];
                    $awb_code_status = $courier['response']['data']['awb_code_status'];
                    $company_id = $courier['response']['data']['company_id'];
                    $courier_name = $courier['response']['data']['courier_name'];
                    $this->db->where('sale_id', $para2);
                    $this->db->update('sale', array("awb_code" => $awb_code, "awb_code_status" => $awb_code_status, "company_id" => $company_id, "courier_name" => $courier_name));
                    $page_data['ship_orderid'] = $ship_orderid;
                    $page_data['shipment_id'] = $shipment_id;
                    $page_data['awb_code'] = $awb_code;
                    $page_data['courier_name'] = $courier_name;
                    $page_data['page_name'] = "assign_courier_set";
                    $this->load->view('back/admin/assign_courier_set', $page_data);
                }
            }
        } elseif ($para1 == 'delivery_agent') {
            //  $page_data['page_name'] = "business_settings";
            //  $this->load->view('back/index', $page_data);
            $page_data['sale_id']         = $para2; //exit;
            $page_data['page_name'] = "delivery_agent";

            // print_r($page_data); exit;
            $this->load->view('back/admin/delivery_agent', $page_data);
        } elseif ($para1 == 'delivery_agent_set') {

            $data['delivery_agent_id'] = $this->input->post('delivery_agent');
            $data['delivery_pickup_date'] = $this->input->post('delivery_pickup_date');
            $data['delivery_pickup_time'] = $this->input->post('delivery_pickup_time');
            $courrer_email = $this->db->get_where('delivery_agent', array('agent_id' => $data['delivery_agent_id']))->result_array();
            $courreradmin_email = $this->db->get_where('admin', array('admin_id' => '1'))->result_array();
            //echo '<pre>'; print_r($courreradmin_email); exit;
            $courrer_product_id = $this->db->get('sale')->result_array();
            $new = json_decode($courrer_product_id[0]['product_details']);
            $courrer_product_id = json_decode($this->db->get('sale')->row()->product_details, true);
            $p_id = $courrer_product_id['id'];
            $courrier_product_name = $this->db->get_where('product', array('product_id' => $p_id))->result_array();
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);

            // 			$from='wwmalls.com';
            // 		$to=$courrer_email[0]['email'];
            // 		 $subject='Acknowledgement for Product Courrier';
            // 		$message="<html><head><meta http-equiv=Content-Type content=text/html; charset=utf-8/><title>wwmalls.com</title>
            // </head><body><table width=500 cellpadding=0 cellspacing=0 border=0 bgcolor=#F49E23 style=border:solid 10px #A5DCFF;><tr bgcolor=#FFFFFF height=25><td><table width=500 cellpadding=0 cellspacing=0 border=0 bgcolor=#F49E23 style=border:solid 10px #a5dcff;><tr bgcolor=#FFFFFF height=30><td height=27 valign=top style=font-family:Arial; font-size:12px; line-height:18px; text-decoration:none; color:#000000; padding-left:20px;><b>Product Courrier Assign Acknowledgement</b></td></tr><tr bgcolor=#FFFFFF height=35><td height=24 style=padding-left:20px; font-family:arial; font-size:11px; line-height:18px; text-decoration:none; color:#000000;>This is an Acknowledgement for product courrier assign to '".$courrer_email[0]['agent_name']."' for delivery</td></tr><tr bgcolor=#FFFFFF height=35><td height=23 style=padding-left:20px; font-family:arial; font-size:11px; line-height:18px; text-decoration:none; color:#000000;>Thanks for using wwmalls.net</td></tr></table></td></tr></table><body/><html/>";

            // 			$headers = 'MIME-Version: 1.0' . "\r\n";
            // 			$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
            // 			$headers .= "From: wwmalls.net <support@wwmalls.com>\r\n";
            // 			$to1=$courreradmin_email[0]['email'];

            // 			$email_response = mail($to,$subject,$message,$headers);

            // 			$email_response2 = mail($to1,$subject,$message,$headers);


            //echo $this->db->last_query();
        } elseif ($para1 == 'delivery_track') {
            $page_data['order_id']         = $para2;
            $page_data['page_name'] = "delivery_track";
            $page_data['shipdet'] = $orderdet = $this->db->get_where('sale', array('order_id' => $para2))->result_array();
            $orderdet = $orderdet[0];
            //$postdata=$orderdet['shipment_id'];
            $postdata = $orderdet['awb_code'];

            $auth = $this->crud_model->authentication();
            $authtoken = $auth['token'];
            $trackdetail = $this->crud_model->TrackByAWBcode($authtoken, $postdata);
            //$trackdetail = $this->crud_model->TrackByShimentId($authtoken,$postdata);
            $page_data['trackdetail'] = $trackdetail;
            $this->load->view('back/admin/delivery_track', $page_data);
        } else if ($para1 == 'send_pickup_set') {
            //echo 1; exit;
            $sale_id = $para2;
            $orderDet = $this->db->get_where('sale', array('sale_id' => $para2))->result_array();
            $orderDet = $orderDet[0];
            $postdata = $orderDet['shipment_id'];

            $auth = $this->crud_model->authentication();
            $authtoken = $auth['token'];


            $shipOrderdet = $this->crud_model->request_pickup($authtoken, $postdata);
            //print_r($shipOrderdet); //exit;
            $pickupstatus = $shipOrderdet['pickup_status'];
            $pickupdet = $shipOrderdet['response'];
            $pickupdet = json_encode($pickupdet);
            //print_r($shipOrderdet);  
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', array("pick_up_status" => $pickupstatus, "pickup_details" => $pickupdet));
            $page_data['shipmentId'] = $postdata;
            $page_data['pickupstatus'] = $pickupstatus;
            $page_data['pickupdet'] = $pickupdet;
            $page_data['page_name'] = "send_pickup_set";
            $this->load->view('back/admin/send_pickup_set', $page_data);
        } else {
            $page_data['page_name']      = "sales";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* VB Customers */
    function customers($para1 = '', $para2 = '', $para3 = '', $para4 = '', $para5 = '', $para6 = '')
    {
        if ($this->input->post() != '') {

            // $user = $page_data['user'] = $this->input->post('user');
            $cust_group = $page_data['user_group']    = $this->input->post('user_group');
            $post_code = $page_data['zip_code'] = $this->input->post('zip_code');
            $from = $page_data['from'] = $this->input->post('from');
            $to = $page_data['to'] = $this->input->post('to');
            $mode = $page_data['mode'] = $this->input->post('mode');
            $l_days = $page_data['l_days'] = $this->input->post('l_days');

        }
        $page_data['page_name']      = "customers";        
        $page_data['view_rights']=$this->get_user_view_rights();
        $page_data['user_rights_13_10'] = $this->get_user_rights(13,10);
        $page_data['user_rights_13_11'] = $this->get_user_rights(13,11);
        $page_data['user_rights_13_12'] = $this->get_user_rights(13,12);
       $this->load->view('back/index', $page_data);
    }

    /*User Management */
    function user($para1 = '', $para2 = '', $para3 = '', $para4 = '', $para5 = '', $para6 = '')
    {

        if (!$this->crud_model->admin_permission('user')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->input->post() != '') {

            $user = $page_data['user'] = $this->input->post('user');
            $cust_group = $page_data['user_group'] = $this->input->post('user_group');
            // $para5 = $this->input->post('zip_code');
            $post_code = $page_data['zip_code'] = $this->input->post('zip_code');
            $from = $page_data['from'] = $this->input->post('from');
            $to = $page_data['to'] = $this->input->post('to');
            $mode = $page_data['mode'] = $this->input->post('mode');
            $l_days = $page_data['l_days'] = $this->input->post('l_days');

            
        }
        if ($para1 == 'do_add') {
            $data['username']    = $this->input->post('user_name');
            $data['description'] = $this->input->post('description');
            $this->db->insert('user', $data);
        } else if ($para1 == 'edit') {
            $page_data['user_data'] = $this->db->get_where('user', array(
                'user_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/user_edit', $page_data);
        } elseif ($para1 == "update") {            
            $data['username']    = $this->input->post('name');
            $data['email']    = $this->input->post('email');
            $data['phone']    = $this->input->post('phone');
            $data['age']    = $this->input->post('age');
            $data['gender']    = $this->input->post('gender');
            $data['rewards']    = $this->input->post('rewards');
           // $data['description'] = $this->input->post('description');
            $this->db->where('user_id', $para2);
            $this->db->update('user', $data);
            echo $this->db->last_query();
            $path = $_FILES['image']['name'];
            $ext = '.' . pathinfo($path, PATHINFO_EXTENSION);
            $this->crud_model->file_up('image', 'user', $para2, '', '', $ext);
        } elseif ($para1 == 'delete') {
            $this->db->where('user_id', $para2);
            $this->db->delete('user');
        } elseif ($para1 == 'list') {

            $sql = "SELECT u.*
            FROM user AS u
            WHERE 1"; // Start building the SQL query

    if ($para2 != '0') {
        $sql = "SELECT u.*, ug.*
            FROM user AS u
            LEFT JOIN user_group AS ug ON FIND_IN_SET(u.user_id, ug.user)
            WHERE 1"; 
        $sql .= " AND ug.user_group_id = " . $this->db->escape($para2);
    }

    if ($para3 != '0' && $para4 != '0') {
        $from = strtotime($para3 . ' 00:00:00');
        $to = strtotime($para4 . ' 23:59:59');
        $sql .= " AND u.creation_date >= " . $this->db->escape($from) . " AND u.creation_date <= " . $this->db->escape($to);
    }

    if ($para5 != '0') {
        $sql .= " AND u.zip = " . $this->db->escape($para5);
    }

    // Add more conditions if needed

    $sql .= " GROUP BY u.user_id
              ORDER BY u.user_id DESC";

    $query = $this->db->query($sql);
    $page_data['all_users'] = $query->result_array();

    $page_data['user_rights_13_10'] = $this->get_user_rights(13, 10);
    $this->load->view('back/admin/user_list', $page_data);
        }elseif ($para1 == 'view') {
    
            $page_data['user_data'] = $this->db->get_where('user', array(
                'user_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/user_view', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/user_add');
        } else {
            $page_data['page_name'] = "user";
            $page_data['all_users'] = $this->db->get('user')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    function update_user() {
        $this->db->where('user_group_id', $this->input->post('user_group'));
        $q = $this->db->get('user_group');
        /* if u r fetching one row use row_array instead of result_array*/
        $row = $q->row_array();

        $row['user'] == '' ? $data['user'] = $this->input->post('user_ids') : $data['user'] = $row['user'] . ',' .$this->input->post('user_ids');            
        
        $this->db->where('user_group_id', $this->input->post('user_group'));
        $this->db->update('user_group', $data);

    }

    

    /*User Group add, edit, view, delete */
    function user_group($para1 = '', $para2 = '')
    {
        // echo "ABC";
        if (!$this->crud_model->admin_permission('user')) {
            redirect(base_url() . 'index.php/admin');
        }
        // if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
        //     redirect(base_url() . 'index.php/admin');
        // }
        if ($para1 == 'do_add') {
            $data['user_group_name'] = $this->input->post('user_group_name');
            $data['remarks'] = $this->input->post('remarks');
            $this->db->insert('user_group', $data);
            $id = $this->db->insert_id();

            recache();
        } else if ($para1 == 'edit') {
            $page_data['user_group_data'] = $this->db->get_where('user_group', array(
                'user_group_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/user_group_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['user_group_name'] = $this->input->post('user_group_name');
            $data['remarks'] = $this->input->post('remarks');
            $this->db->where('user_group_id', $para2);
            $this->db->update('user_group', $data);
            
            recache();
        } elseif ($para1 == 'delete') {           
            $this->db->where('user_group_id', $para2);
            $this->db->delete('user_group');

            recache();
        } elseif ($para1 == 'list') {
      
            $this->db->order_by('user_group_id', 'desc');
            // $this->db->where('digital=', NULL);
            $page_data['all_user_groups'] = $this->db->get('user_group')->result_array();
            $page_data['user_rights_13_11'] = $this->get_user_rights(13,11);
            $this->load->view('back/admin/user_group_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/user_group_add');
        } else {
            $page_data['page_name']      = "user_group";
            $page_data['all_user_groups'] = $this->db->get('user_group')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* membership_payment Management */
    function membership_payment($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('membership_payment') || $this->crud_model->get_type_name_by_id('general_settings', '58', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'delete') {
            $this->db->where('membership_payment_id', $para2);
            $this->db->delete('membership_payment');
        } else if ($para1 == 'list') {
            $this->db->order_by('membership_payment_id', 'desc');
            $page_data['all_membership_payments'] = $this->db->get('membership_payment')->result_array();
            $page_data['bank_deatils'] = $this->db->get('vendor', array('vendor_id'))->result_array();


            $this->load->view('back/admin/membership_payment_list', $page_data);
        } else if ($para1 == 'view') {
            $page_data['membership_payment_data'] = $this->db->get_where('membership_payment', array(
                'membership_payment_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/membership_payment_view', $page_data);
        } elseif ($para1 == 'upgrade') {
            if ($this->input->post('status')) {
                $membership = $this->db->get_where('membership_payment', array('membership_payment_id' => $para2))->row()->membership;
                $vendor = $this->db->get_where('membership_payment', array('membership_payment_id' => $para2))->row()->vendor;
                $data['status'] = $this->input->post('status');
                $data['details'] = $this->input->post('details');
                if ($data['status'] == 'paid') {
                    $this->crud_model->upgrade_membership($vendor, $membership);
                }

                $this->db->where('membership_payment_id', $para2);
                $this->db->update('membership_payment', $data);
            }
        } else {
            $page_data['page_name'] = "membership_payment";
            $page_data['all_membership_payments'] = $this->db->get('membership_payment')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function saveFormData()
    {
        $sat = is_null($this->input->post('sat')) ? 'off' : 'on';
        $sun = is_null($this->input->post('sun')) ? 'off' : 'on';
        $mon = is_null($this->input->post('mon')) ? 'off' : 'on';
        $tue = is_null($this->input->post('tue')) ? 'off' : 'on';
        $wed = is_null($this->input->post('wed')) ? 'off' : 'on';
        $thu = is_null($this->input->post('thu')) ? 'off' : 'on';
        $fri = is_null($this->input->post('fri')) ? 'off' : 'on';
        $startSlot = $this->input->post('startSlot');
        $endSlot = $this->input->post('endSlot');
        $interval_in_minute = $this->input->post('interval_in_minute');
        $maxOrder = $this->input->post('maxOrder');
        $vendorId = $this->input->post('vendorId');
        $availableDays = json_encode([
            'sat' => $sat,
            'sun' => $sun,
            'mon' => $mon,
            'tue' => $tue,
            'wed' => $wed,
            'thu' => $thu,
            'fri' => $fri,
        ]);

        $data['available_days'] = $availableDays;
        $data['slot_start'] = $startSlot;
        $data['vendor_id'] = $vendorId;
        $data['slot_end'] = $endSlot;
        $data['interval_in_minute'] = $interval_in_minute;
        $data['max_order'] = $maxOrder;
        // print_r($data);

        $checkRowExitsData = $this->db->get_where('pickup_slot', ['vendor_id' => $vendorId])->result_array();
        if (count($checkRowExitsData) > 0) {
            $this->db->where('vendor_id', $vendorId);
            $this->db->update('pickup_slot', $data);
        } else {
            $this->db->insert('pickup_slot', $data);
        }
        echo 'success';
        // exit;
    }
    function updateFormData()
    {
        $sat = is_null($this->input->post('sat')) ? 'off' : 'on';
        $sun = is_null($this->input->post('sun')) ? 'off' : 'on';
        $mon = is_null($this->input->post('mon')) ? 'off' : 'on';
        $tue = is_null($this->input->post('tue')) ? 'off' : 'on';
        $wed = is_null($this->input->post('wed')) ? 'off' : 'on';
        $thu = is_null($this->input->post('thu')) ? 'off' : 'on';
        $fri = is_null($this->input->post('fri')) ? 'off' : 'on';
        $startSlot = $this->input->post('startSlot');
        $endSlot = $this->input->post('endSlot');
        $interval_in_minute = $this->input->post('interval_in_minute');
        $maxOrder = $this->input->post('maxOrder');
        $availableDays = json_encode([
            'sat' => $sat,
            'sun' => $sun,
            'mon' => $mon,
            'tue' => $tue,
            'wed' => $wed,
            'thu' => $thu,
            'fri' => $fri,
        ]);

        $data['available_days'] = $availableDays;
        $data['slot_start'] = $startSlot;
        $data['vendor_id'] = $this->input->post('vendorId');
        $data['slot_end'] = $endSlot;
        $data['interval_in_minute'] = $interval_in_minute;
        $data['max_order'] = $maxOrder;
        print_r($data);
        $this->db->where('id', $this->input->post('pickupId'));
        $this->db->update('pickup_slot', $data);
        echo 'success';
        // exit;
    }
    function getPickupDetail()
    {
        $pickupId = $this->input->post('pickupId');
        $pickupDetail = $this->db->get_where('pickup_slot', ['id' => $pickupId])->result_array()[0];
        echo json_encode($pickupDetail);
    }
    function getPickupDetailAsVendor($para1='')
    {
        if($para1=="pickup_slot_save")
        {
            $data['slot_days']=$this->input->post('slot_days');
            $data['slot_start']=$this->input->post('slot_start');
            $data['slot_end']=$this->input->post('slot_end');
            $data['interval_in_minute']=$this->input->post('interval_in_minute');
            $data['max_order']=$this->input->post('max_order');
            if($this->input->post('id')=="")
            {
                $data['vendor_id']=$this->input->post('vendor_id');
                $this->db->insert('pickup_slot', $data);
            }
            else
            {
                $this->db->where('id', $this->input->post('id'));
                $this->db->where('vendor_id', $this->input->post('vendor_id'));
                $this->db->update('pickup_slot', $data);
            }
        }
        else //if($para1=="vendor_detail")
        {
            $this->db->select('id,vendor_id,slot_days,slot_start,slot_end,interval_in_minute,max_order');
            $pickupDetail = $this->db->get_where('pickup_slot', ['vendor_id' => $this->input->post('vendorId')])->result_array();
            echo json_encode($pickupDetail);
        }
    }
    function getTimeSlot($interval, $start_time, $end_time)
    {
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $startTime = $start->format('g:i a');
        $endTime = $end->format('g:i a');
        $i = 0;
        $time = [];
        while (strtotime($startTime) <= strtotime($endTime)) {
            $start = $startTime;
            $end = date('g:i a', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
            $startTime = date('g:i a', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
            $i++;
            if (strtotime($startTime) <= strtotime($endTime)) {
                $time[$i]['slot_start_time'] = $start;
                $time[$i]['slot_end_time'] = $end;
            }
        }
        return $time;
    }

    /* Vendor Management */
    function vendor($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('vendor') || $this->crud_model->get_type_name_by_id('general_settings', '58', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {


            $data['name'] = $this->input->post('name');
            $data['address1'] = $this->input->post('address1');
            $data['company'] = $this->input->post('name');
            $data['display_name'] = $this->input->post('name');
            $data['city'] = $this->input->post('city');
            $data['state'] = $this->input->post('state');
            $data['country'] = $this->input->post('country');
            $data['latitude'] = $this->input->post('latitude');
            $data['longitude'] = $this->input->post('longitude');
            $data['zip'] = $this->input->post('zipcode');
            $data['email'] = $this->input->post('email');
            $data['phone'] = $this->input->post('phone');
            $data['pickup'] = $this->input->post('pickup');
            $data['delivery'] = $this->input->post('delivery');
            $data['delivery_zipcode'] = $this->input->post('delivery_zipcode');
            $data['status'] = 'approved';
            $data['create_timestamp']   = time();
            $password         = '123456';
            $data['password'] = sha1($password);
            $this->db->insert('vendor', $data);
            echo $this->db->last_query();
            $id = $this->db->insert_id();
            $hist_data['vendor_id'] = $id;
            $hist_data['vendor_name'] = $this->input->post('name');
            $hist_data['admin_id'] = $this->session->userdata('admin_id');
            $this->db->insert('vendor_history', $hist_data);
            move_uploaded_file($_FILES["logo"]['tmp_name'], 'uploads/vendor_logo_image/logo_' . $id . '.png');
            recache();
        } elseif ($para1 == "update") {
            $data['name'] = $this->input->post('name');
            $data['address1'] = $this->input->post('address1');
            // $data['address2'] = $this->input->post('address2');
            $data['city'] = $this->input->post('city');
            $data['state'] = $this->input->post('state');
            $data['country'] = $this->input->post('country');
            $data['latitude'] = $this->input->post('latitude');
            $data['longitude'] = $this->input->post('longitude');
            $data['zip'] = $this->input->post('zip');
            $data['email'] = $this->input->post('email');
            $data['phone'] = $this->input->post('phone');
            $data['pickup'] = $this->input->post('pickup');
            $data['delivery'] = $this->input->post('delivery');
            $data['delivery_zipcode'] = $this->input->post('delivery_zipcode');
            $this->db->where('vendor_id', $para2);
            $this->db->update('vendor', $data);
            $hist_data['vendor_id'] = $para2;
            $hist_data['vendor_name'] = $this->input->post('name');
            $hist_data['admin_id'] = $this->session->userdata('admin_id');


            $this->db->where('vendor_id', $hist_data['vendor_id']);
            $this->db->update('vendor_history', $hist_data);
            if ($_FILES['logo']['name'] !== '') {
                move_uploaded_file($_FILES["logo"]['tmp_name'], 'uploads/vendor_logo_image/logo_' . $para2 . '.png');
            }
            //	$this->crud_model->set_category_data(0);
            recache();
        } else if ($para1 == 'delete') {
           
			    $page_data['admin_data'] = $this->db->get_where('admin', array(
                    'admin_id' => $this->session->userdata('admin_id')
                    ))->result_array();
                
                    $user_password = sha1($this->input->post('user_password'));
                    //echo "<br>";
                    $db_password=$page_data['admin_data'][0]['password'];
                
                    if($user_password==$db_password){
                         /* delete vendor products start */
            $this->db->where('added_by', json_encode(array('type' => 'vendor', 'id' => $para2)));
            $products = $this->db->get('product')->result_array();
            $ids = array();
            foreach ($products as $row) {
                $this->crud_model->file_dlt('product', $row['product_id'], '.jpg', 'multi');
                $this->db->where('product_id', $row['product_id']);
                $this->db->delete('product');
            }
            $this->crud_model->set_category_data(0);
            
        }
        /* delete vendor products end */
        unlink("uploads/vendor_logo_image/logo_" . $para2);
        $this->db->where('vendor_id', $para2);
        $this->db->delete('vendor');
            recache();
            $hist_data['deleted_status'] = '1';
            $hist_data['deleted_on'] = date('Y-m-d H:i:s');
            $this->db->where('vendor_id', $para2);
            $this->db->update('vendor_history', $hist_data);
        } else if ($para1 == 'list') {
            $page_data['pickupSlotList'] = $this->db->get('pickup_slot')->result_array();
            $page_data['timeList'] = $this->getTimeSlot(15, '00:00', '23:00');
            $this->db->order_by('vendor_id', 'desc');
            $page_data['all_vendors'] = $this->db->get('vendor')->result_array();
            $page_data['user_rights_12_0'] = $this->get_user_rights(12,0);
            $this->load->view('back/admin/vendor_list', $page_data);
        } else if ($para1 == 'view') {
            $page_data['vendor_data'] = $this->db->get_where('vendor', array(
                'vendor_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/vendor_view', $page_data);
        } else if ($para1 == 'pay_form') {
            $page_data['vendor_id'] = $para2;
            $this->load->view('back/admin/vendor_pay_form', $page_data);
        } else if ($para1 == 'approval') {
            $page_data['vendor_id'] = $para2;
            $page_data['status'] = $this->db->get_where('vendor', array(
                'vendor_id' => $para2
            ))->row()->status;
            $page_data['commission'] = $this->db->get_where('vendor', array(
                'vendor_id' => $para2
            ))->row()->commission;
            $this->load->view('back/admin/vendor_approval', $page_data);
        } else if ($para1 == 'add') {
            $this->load->view('back/admin/vendor_add');
        } else if ($para1 == 'edit') {
            // echo "adsf"; exit;
            $page_data['vendor_data'] = $this->db->get_where('vendor', array(
                'vendor_id' => $para2
            ))->result_array();
            //  echo $this->db->last_query();
            $this->load->view('back/admin/vendor_edit', $page_data);
        } else if ($para1 == 'commission_set') {

            $this->load->view('back/admin/vendor_commission');
        } else if ($para1 == 'update_commission') {
            print_r($this->input->post());
            $commission_type['value'] = $this->input->post('commission_type');
            $this->db->where('type', 'commission_type');
            $this->db->update('business_settings', $commission_type);
            echo $this->db->last_query();
            $commission_amount['value'] = $this->input->post('vendor_commission');
            $this->db->where('type', 'commission_amount');
            $this->db->update('business_settings', $commission_amount);
            echo $this->db->last_query();
        } else if ($para1 == "default_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }
            echo $val;
            //$this->db->where('vendor_id', $para2);
            $this->db->update('vendor', array(
                'default_set' => 'no'
            ));
            echo $this->db->last_query();
            $this->db->where('vendor_id', $para2);
            $this->db->update('vendor', array(
                'default_set' => $val
            ));
            echo $this->db->last_query();
            recache();
        } else if ($para1 == 'approval_set') {
            $vendor = $para2;
            $approval = $this->input->post('approval');
			//$user_password = $this->input->post('user_password');
			
            if ($approval == 'ok') {
                $data['status'] = 'approved';
            } else {
                $data['status'] = 'pending';
            }
			
			$page_data['admin_data'] = $this->db->get_where('admin', array(
                'admin_id' => $this->session->userdata('admin_id')
            ))->result_array();
			
			$user_password = sha1($this->input->post('user_password'));
			//echo "<br>";
			$db_password=$page_data['admin_data'][0]['password'];
			
			 if($user_password==$db_password){
				$this->db->where('vendor_id', $vendor);
				$this->db->update('vendor', $data);
			 }
			
			
			
            //  $data['commission'] =$this->input->post('vendor_commission');
            
            //  $this->email_model->status_email('vendor', $vendor);
            recache();
        } elseif ($para1 == 'pay') {
            $vendor         = $para2;
            $method         = $this->input->post('method');
            $amount         = $this->input->post('amount');
            $amount_in_usd  = $amount / exchange('usd');
            if ($method == 'paypal') {
                $paypal_email  = $this->crud_model->get_type_name_by_id('vendor', $vendor, 'paypal_email');
                $data['vendor_id']      = $vendor;
                $data['amount']         = $this->input->post('amount');
                $commission_specific = $this->db->get_where('business_settings', array('type' => 'commission_type'))->row()->value;
                if ($commission_specific == 'all_vendor') {
                    $commission_percentage = $this->db->get_where('business_settings', array('type' => 'commission_amount'))->row()->value;
                } else {
                    $commission_percentage = $this->db->get_where('vendor', array('vendor_id' => $para2))->row()->commission;
                }
                $data['commission_amount']  = ($data['amount'] / 100) * $commission_percentage;
                $data['paid_amount'] = $data['amount'] - $data['commission_amount'];
                $data['status']         = 'due';
                $data['method']         = 'paypal';
                $data['timestamp']      = time();
                $amount_in_usd  = $data['paid_amount'] / exchange('usd');
                $this->db->insert('vendor_invoice', $data);
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
                $this->paypal->add_field('notify_url', base_url() . 'index.php/admin/paypal_ipn');
                $this->paypal->add_field('cancel_return', base_url() . 'index.php/admin/paypal_cancel');
                $this->paypal->add_field('return', base_url() . 'index.php/admin/paypal_success');

                $this->paypal->submit_paypal_post();
                // submit the fields to paypal

            } else if ($method == 'c2') {
                $data['vendor_id']      = $vendor;
                $data['amount']         = $this->input->post('amount');
                $data['status']         = 'due';
                $data['method']         = 'c2';
                $data['timestamp']      = time();
                $commission_specific = $this->db->get_where('business_settings', array('type' => 'commission_type'))->row()->value;
                if ($commission_specific == 'all_vendor') {
                    $commission_percentage = $this->db->get_where('business_settings', array('type' => 'commission_amount'))->row()->value;
                } else {
                    $commission_percentage = $this->db->get_where('vendor', array('vendor_id' => $para2))->row()->commission;
                }
                $data['commission_amount']  = ($data['amount'] / 100) * $commission_percentage;
                $data['paid_amount'] = $data['amount'] - $data['commission_amount'];
                $this->db->insert('vendor_invoice', $data);
                $invoice_id             = $this->db->insert_id();
                $this->session->set_userdata('vendor_id', $vendor);
                $this->session->set_userdata('invoice_id', $invoice_id);
                $amount_in_usd  = $data['paid_amount'] / exchange('usd');
                $c2_user = $this->db->get_where('vendor', array('vendor_id' => $vendor))->row()->c2_user;
                $c2_secret = $this->db->get_where('vendor', array('vendor_id' => $vendor))->row()->c2_secret;


                $this->twocheckout_lib->set_acct_info($c2_user, $c2_secret, 'Y');
                $this->twocheckout_lib->add_field('sid', $this->twocheckout_lib->sid);              //Required - 2Checkout account number
                $this->twocheckout_lib->add_field('cart_order_id', $invoice_id);   //Required - Cart ID
                $this->twocheckout_lib->add_field('total', $this->cart->format_number($amount_in_usd));

                $this->twocheckout_lib->add_field('x_receipt_link_url', base_url() . 'index.php/admin/twocheckout_success');
                $this->twocheckout_lib->add_field('demo', $this->twocheckout_lib->demo);                    //Either Y or N

                $this->twocheckout_lib->submit_form();
            } else if ($method == 'vp') {
                $vp_id  = $this->crud_model->get_type_name_by_id('vendor', $vendor, 'vp_merchant_id');

                $data['vendor_id']      = $vendor;
                $data['amount']         = $this->input->post('amount');
                $data['status']         = 'due';
                $data['method']         = 'vouguepay';
                $data['timestamp']      = time();
                $commission_specific = $this->db->get_where('business_settings', array('type' => 'commission_type'))->row()->value;
                if ($commission_specific == 'all_vendor') {
                    $commission_percentage = $this->db->get_where('business_settings', array('type' => 'commission_amount'))->row()->value;
                } else {
                    $commission_percentage = $this->db->get_where('vendor', array('vendor_id' => $para2))->row()->commission;
                }
                $data['commission_amount']  = ($data['amount'] / 100) * $commission_percentage;
                $data['paid_amount'] = $data['amount'] - $data['commission_amount'];
                $this->db->insert('vendor_invoice', $data);
                $invoice_id           = $this->db->insert_id();
                $this->session->set_userdata('invoice_id', $invoice_id);
                //$vouguepay_id              = $this->crud_model->get_type_name_by_id('business_settings', '1', 'value');
                $system_title              = $this->crud_model->get_settings_value('general_settings', 'system_title', 'value');
                /****TRANSFERRING USER TO vouguepay TERMINAL****/
                $this->vouguepay->add_field('v_merchant_id', $vp_id);
                $this->vouguepay->add_field('merchant_ref', $invoice_id);
                $this->vouguepay->add_field('memo', 'Pay from ' . $system_title);
                //$this->vouguepay->add_field('developer_code', $developer_code);
                //$this->vouguepay->add_field('store_id', $store_id);


                $this->vouguepay->add_field('total', $data['paid_amount']);

                //$this->vouguepay->add_field('amount', $grand_total);
                //$this->vouguepay->add_field('custom', $sale_id);
                //$this->vouguepay->add_field('business', $vouguepay_email);

                $this->vouguepay->add_field('notify_url', base_url() . 'index.php/admin/vouguepay_ipn');
                $this->vouguepay->add_field('fail_url', base_url() . 'index.php/admin/vouguepay_cancel');
                $this->vouguepay->add_field('success_url', base_url() . 'index.php/admin/vouguepay_success');

                $this->vouguepay->submit_vouguepay_post();
                // submit the fields to vouguepay
            } else if ($method == 'stripe') {
                if ($this->input->post('stripeToken')) {

                    $vendor         = $para2;
                    $method         = $this->input->post('method');
                    $amount         = $this->input->post('amount');

                    $commission_specific = $this->db->get_where('business_settings', array('type' => 'commission_type'))->row()->value;
                    if ($commission_specific == 'all_vendor') {
                        $commission_percentage = $this->db->get_where('business_settings', array('type' => 'commission_amount'))->row()->value;
                    } else {
                        $commission_percentage = $this->db->get_where('vendor', array('vendor_id' => $para2))->row()->commission;
                    }
                    $data['commission_amount']  = ($amount / 100) * $commission_percentage;
                    $data['paid_amount'] = $amount - $data['commission_amount'];
                    $amount_in_usd  = $data['paid_amount'] / $this->db->get_where('business_settings', array('type' => 'exchange'))->row()->value;
                    $stripe_details      = json_decode($this->db->get_where('vendor', array(
                        'vendor_id' => $vendor
                    ))->row()->stripe_details, true);
                    $stripe_publishable  = $stripe_details['publishable'];
                    $stripe_api_key      =  $stripe_details['secret'];

                    require_once(APPPATH . 'libraries/stripe-php/init.php');
                    \Stripe\Stripe::setApiKey($stripe_api_key); //system payment settings
                    $vendor_email = $this->db->get_where('vendor', array('vendor_id' => $vendor))->row()->email;

                    $vendora = \Stripe\Customer::create(array(
                        'email' => $this->db->get_where('general_settings', array('type' => 'system_email'))->row()->value, // customer email id
                        'card'  => $_POST['stripeToken']
                    ));

                    $charge = \Stripe\Charge::create(array(
                        'customer'  => $vendora->id,
                        'amount'    => ceil($amount_in_usd * 100),
                        'currency'  => 'USD'
                    ));

                    if ($charge->paid == true) {
                        $vendora = (array) $vendora;
                        $charge = (array) $charge;

                        $data['vendor_id']          = $vendor;
                        $data['amount']             = $amount;
                        $data['status']             = 'paid';
                        $data['method']             = 'stripe';
                        $data['timestamp']          = time();
                        $data['payment_details']    = "Customer Info: \n" . json_encode($vendora, true) . "\n \n Charge Info: \n" . json_encode($charge, true);

                        $this->db->insert('vendor_invoice', $data);

                        redirect(base_url() . 'index.php/admin/vendor/', 'refresh');
                    } else {
                        $this->session->set_flashdata('alert', 'unsuccessful_stripe');
                        redirect(base_url() . 'index.php/admin/vendor/', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'unsuccessful_stripe');
                    redirect(base_url() . 'index.php/admin/vendor/', 'refresh');
                }
            } else if ($method == 'cash') {
                $data['vendor_id']          = $para2;
                $data['amount']             = $this->input->post('amount');
                $data['status']             = 'due';
                $data['method']             = 'cash';
                $data['timestamp']          = time();
                $commission_specific = $this->db->get_where('business_settings', array('type' => 'commission_type'))->row()->value;
                if ($commission_specific == 'all_vendor') {
                    $commission_percentage = $this->db->get_where('business_settings', array('type' => 'commission_amount'))->row()->value;
                } else {
                    $commission_percentage = $this->db->get_where('vendor', array('vendor_id' => $para2))->row()->commission;
                }
                $data['commission_amount']  = ($data['amount'] / 100) * $commission_percentage;
                $data['paid_amount'] = $data['amount'] - $data['commission_amount'];

                $data['payment_details']    = "";
                $this->db->insert('vendor_invoice', $data);
                redirect(base_url() . 'index.php/admin/vendor/', 'refresh');
            }
        } elseif ($para1 == 'password_check') {
			//echo $this->session->userdata('admin_id');
			//echo $para2;
			
			 $page_data['admin_data'] = $this->db->get_where('admin', array(
                'admin_id' => $this->session->userdata('admin_id')
            ))->result_array();
			
			$user_password = sha1($this->input->post('user_password'));
			//echo "<br>";
			$db_password=$page_data['admin_data'][0]['password'];
			
			 if($user_password===$db_password){
				echo "Match";
			 }
			else{
				echo "Notok";
			}
			
		} else {
            $page_data['page_name'] = "vendor";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['user_rights_12_0'] = $this->get_user_rights(12,0);
            $page_data['all_vendors'] = $this->db->get('vendor')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    function marketing_vendor($para1 = '', $para2 = '', $para3 = '')
    {
        //echo "a"; exit;
        if (!$this->crud_model->admin_permission('marketing_vendor')) {
            redirect(base_url() . 'index.php/admin');
        }
        $this->load->library('form_validation');
        $safe = 'yes';
        $char = '';

        if ($para1 == 'do_add') {
            //  $data['name']    = $this->input->post('name');
            // $data['surname'] = $this->input->post('description');
            $data['marketing_id'] = $this->session->userdata('admin_id');


            $data['name']               = $this->input->post('name');
            $data['email']              = $this->input->post('email');
            $data['phone']              = $this->input->post('phone');
            $data['address1']           = $this->input->post('address1');
            $data['address2']           = $this->input->post('address2');
            $data['company']            = $this->input->post('company');
            $data['display_name']       = $this->input->post('display_name');
            $data['state']               = $this->input->post('state');
            $data['country']               = $this->input->post('country');
            $data['city']               = $this->input->post('city');
            $data['zip']                   = $this->input->post('zip');
            $data['geo_loc']                   = $this->input->post('geo_loc');
            $data['facebook']                   = $this->input->post('facebook');
            $data['instagram']                   = $this->input->post('instagram');
            $data['twitter']                   = $this->input->post('twitter');
            $data['youtube']                   = $this->input->post('youtube');
            $data['account_name']                   = $this->input->post('account_name');
            $data['account_number']                   = $this->input->post('account_number');
            $data['ifsc_code']                   = $this->input->post('ifsc_code');
            $data['branch']                   = $this->input->post('branch');
            $data['pack']                   = $this->input->post('pack');

            $data['create_timestamp']   = time();
            $data['approve_timestamp']  = 0;
            $data['approve_timestamp']  = 0;
            $data['membership']         = 0;
            $data['status']             = 'approved';


            $data['store_city'] = $this->input->post('city');
            $data['store_street'] = $this->input->post('address1');
            $data['store_district'] = $this->input->post('state');
            $data['store_country'] = $this->input->post('country');
            $data['store_email'] = $this->input->post('email');


            $password         = $this->input->post('password');
            $data['password'] = sha1($password);
            $this->db->insert('vendor', $data);
            $id = $this->db->insert_id();
            move_uploaded_file($_FILES["banner"]['tmp_name'], 'uploads/vendor_banner_image/banner_' . $id . '.jpg');
            $msg = 'done';
            if ($this->email_model->account_opening('vendor', $data['email'], $password) == false) {
                $msg = 'done_and_sent';
            }
            echo $msg;

            //    $this->db->insert('vendor', $data); 
        } else if ($para1 == 'delete') {
            /* delete vendor products start */
            $this->db->where('added_by', json_encode(array('type' => 'vendor', 'id' => $para2)));
            $products = $this->db->get('product')->result_array();
            $ids = array();
            foreach ($products as $row) {
                $this->crud_model->file_dlt('product', $row['product_id'], '.jpg', 'multi');
                $this->db->where('product_id', $row['product_id']);
                $this->db->delete('product');
            }
            $this->crud_model->set_category_data(0);
            /* delete vendor products end */

            $this->db->where('vendor_id', $para2);
            $this->db->delete('vendor');

            recache();
        } else if ($para1 == 'list') {
            $this->db->order_by('vendor_id', 'desc');
            // $page_data['all_vendors'] = $this->db->get('vendor')->result_array();
            $page_data['all_vendors'] = $this->db->get_where('vendor', array('marketing_id' => $this->session->userdata('admin_id')))->result_array();
            //echo $this->db->last_query();
            $this->load->view('back/admin/marketing_vendor_list', $page_data);
        } else if ($para1 == 'view') {
            $page_data['vendor_data'] = $this->db->get_where('vendor', array(
                'vendor_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/vendor_view', $page_data);
        } else if ($para1 == 'pay_form') {
        } else if ($para1 == 'approval') {
            $page_data['vendor_id'] = $para2;
            $page_data['status'] = $this->db->get_where('vendor', array(
                'vendor_id' => $para2
            ))->row()->status;
            $this->load->view('back/admin/vendor_approval', $page_data);
        } else if ($para1 == 'add') {
            $this->load->view('back/admin/marketing_vendor_add');
        } else if ($para1 == 'approval_set') {
            $vendor = $para2;
            $approval = $this->input->post('approval');
            if ($approval == 'ok') {
                $data['status'] = 'approved';
            } else {
                $data['status'] = 'pending';
            }
            $this->db->where('vendor_id', $vendor);
            $this->db->update('vendor', $data);
            $this->email_model->status_email('vendor', $vendor);
            recache();
        } elseif ($para1 == 'pay') {
        } else {
            $page_data['page_name'] = "marketing_vendor";
            //  $page_data['all_vendors'] = $this->db->get('vendor')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* FUNCTION: Verify paypal payment by IPN*/
    function paypal_ipn()
    {
        if ($this->paypal->validate_ipn() == true) {

            $data['status']             = 'paid';
            $data['payment_details']    = json_encode($_POST);
            $invoice_id                 = $_POST['custom'];
            $this->db->where('vendor_invoice_id', $invoice_id);
            $this->db->update('vendor_invoice', $data);
        }
    }


    /* FUNCTION: Loads after cancelling paypal*/
    function paypal_cancel()
    {
        $invoice_id = $this->session->userdata('invoice_id');
        $this->db->where('vendor_invoice_id', $invoice_id);
        $this->db->delete('vendor_invoice');
        $this->session->set_userdata('vendor_invoice_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'index.php/admin/vendor/', 'refresh');
    }

    /* FUNCTION: Loads after successful paypal payment*/
    function paypal_success()
    {
        $this->session->set_userdata('invoice_id', '');
        redirect(base_url() . 'index.php/admin/vendor/', 'refresh');
    }

    function twocheckout_success()
    {

        //$this->twocheckout_lib->set_acct_info('532001', 'tango', 'Y');
        $c2_user = $this->db->get_where('vendor', array('vendor_id' => $this->session->userdata('vendor_id')))->row()->c2_user;
        $c2_secret = $this->db->get_where('vendor', array('vendor_id' => $this->session->userdata('vendor_id')))->row()->c2_secret;

        $this->twocheckout_lib->set_acct_info($c2_user, $c2_secret, 'Y');
        $data2['response'] = $this->twocheckout_lib->validate_response();
        $status = $data2['response']['status'];
        if ($status == 'pass') {
            $data1['status']             = 'paid';
            $data1['payment_details']   = json_encode($this->twocheckout_lib->validate_response());
            $invoice_id                 = $data2['response']['cart_order_id'];
            $this->db->where('vendor_invoice_id', $invoice_id);
            $this->db->update('vendor_invoice', $data1);
            redirect(base_url() . 'index.php/admin/vendor/', 'refresh');
        } else {
            //var_dump($data2['response']);
            $invoice_id = $this->session->userdata('invoice_id');
            $this->db->where('vendor_invoice_id', $invoice_id);
            $this->db->delete('vendor_invoice');
            $this->session->set_userdata('invoice_id', '');
            $this->session->set_userdata('vendor_id', '');
            $this->session->set_flashdata('alert', 'payment_cancel');
            redirect(base_url() . 'index.php/admin/vendor', 'refresh');
        }
    }
    /* FUNCTION: Verify vouguepay payment by IPN*/
    function vouguepay_ipn()
    {
        $res = $this->vouguepay->validate_ipn();
        $invoice_id = $res['merchant_ref'];
        $merchant_id = 'demo';

        if ($res['total'] !== 0 && $res['status'] == 'Approved' && $res['merchant_id'] == $merchant_id) {
            $data['payment_details']   = json_encode($res);
            $data['timestamp'] = strtotime(date("m/d/Y"));
            $data['status'] = 'paid';
            $this->db->where('vendor_invoice_id', $invoice_id);
            $this->db->update('vendor_invoice', $data);
        }
    }

    /* FUNCTION: Loads after cancelling vouguepay*/
    function vouguepay_cancel()
    {
        $invoice_id = $this->session->userdata('invoice_id');
        $this->db->where('vendor_invoice_id', $invoice_id);
        $this->db->delete('vendor_invoice');
        $this->session->set_userdata('invoice_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'index.php/admin/vendor/', 'refresh');
    }

    /* FUNCTION: Loads after successful vouguepay payment*/
    function vouguepay_success()
    {
        //$carted  = $this->cart->contents();
        $invoice_id = $this->session->userdata('invoice_id');

        //$this->crud_model->email_invoice($sale_id);
        $this->session->set_userdata('invoice_id', '');
        redirect(base_url() . 'index.php/admin/vendor/', 'refresh');
    }


    /* Pay to Vendor from Admin  */

    function pay_to_vendor($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('pay_to_vendor')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'list') {
            $this->db->order_by('vendor_invoice_id', 'desc');
            $page_data['vendor_payments'] = $this->db->get('vendor_invoice')->result_array();
            $this->load->view('back/admin/pay_to_vendor_list', $page_data);
        } else if ($para1 == 'delete') {
            $this->db->where('vendor_invoie_id', $para2);
            $this->db->delete('vendor_invoice');
        } elseif ($para1 == 'vendor_payment_status') {
            $page_data['vendor_invoice_id']         = $para2;
            $page_data['method']    = $this->db->get_where('vendor_invoice', array(
                'vendor_invoice_id' => $para2
            ))->row()->method;
            $page_data['payment_details'] = $this->db->get_where('vendor_invoice', array(
                'vendor_invoice_id' => $para2
            ))->row()->payment_details;
            $page_data['status'] =    $this->db->get_where('vendor_invoice', array('vendor_invoice_id' => $para2))->row()->status;

            $this->load->view('back/admin/pay_to_vendor_payment_status', $page_data);
        } elseif ($para1 == 'payment_status_set') {
            $data['status'] = $this->input->post('vendor_payment_status');
            $this->db->where('vendor_invoice_id', $para2);
            $this->db->update('vendor_invoice', $data);
        } else {
            $page_data['page_name'] = "pay_to_vendor";
            $page_data['vendor_payments'] = $this->db->get('vendor_invoice')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Membership Management */
    function membership($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('membership') || $this->crud_model->get_type_name_by_id('general_settings', '58', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['title']    = $this->input->post('title');
            $data['price']    = $this->input->post('price');
            $data['timespan']    = $this->input->post('timespan');
            $data['product_limit']    = $this->input->post('product_limit');
            $this->db->insert('membership', $data);
            $id = $this->db->insert_id();
            $this->crud_model->file_up("img", "membership", $id, '', '', '.png');
        } else if ($para1 == 'edit') {
            $page_data['membership_data'] = $this->db->get_where('membership', array(
                'membership_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/membership_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['title']    = $this->input->post('title');
            $data['price']    = $this->input->post('price');
            $data['timespan']    = $this->input->post('timespan');
            $data['product_limit']    = $this->input->post('product_limit');
            $this->db->where('membership_id', $para2);
            $this->db->update('membership', $data);
            $this->crud_model->file_up("img", "membership", $para2, '', '', '.png');
        } elseif ($para1 == "default_set") {
            $this->db->where('type', "default_member_product_limit");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('product_limit')
            ));
            $this->crud_model->file_up("img", "membership", 0, '', '', '.png');
        } elseif ($para1 == 'delete') {
            $this->db->where('membership_id', $para2);
            $this->db->delete('membership');
        } elseif ($para1 == 'list') {
            $this->db->order_by('membership_id', 'desc');
            $page_data['all_memberships'] = $this->db->get('membership')->result_array();
            $this->load->view('back/admin/membership_list', $page_data);
        } elseif ($para1 == 'view') {
            $page_data['membership_data'] = $this->db->get_where('membership', array(
                'membership_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/membership_view', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/membership_add');
        } elseif ($para1 == 'default') {
            $this->load->view('back/admin/membership_default');
        } elseif ($para1 == 'publish_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'approved';
            } else {
                $data['status'] = 'pending';
            }
            $this->db->where('membership_id', $product);
            $this->db->update('membership', $data);
        } else {
            $page_data['page_name'] = "membership";
            $page_data['all_memberships'] = $this->db->get('membership')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Administrator Management */
    function admins($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('admin')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['name']      = $this->input->post('name');
            $data['email']     = $this->input->post('email');
            //$password           = $this->input->post('password');
            $char1="0123456789!@#$%&*()abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $password="";for($i1=0;$i1<8;$i1++){$password.=$char1[rand(0,strlen($char1)-1)];}
            $data['password']  = sha1($password);
            $data['phone']     = $this->input->post('phone');
            $data['address']   = $this->input->post('address');
            $data['role']      = $this->input->post('role');
            $data['timestamp'] = time();
            $this->db->insert('admin', $data);
            $this->email_model->account_opening('admin', $data['email'], $password);
        } else if ($para1 == 'edit') {

            $page_data['admin_data'] = $this->db->get_where('admin', array(
                'admin_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/admin_edit', $page_data);
        } elseif ($para1 == "update") {
            // if ($para2 == '1' || $para2 == '3' || $para2 == '5' || $para2 == '6') {
            // } else {

                $data['name']    = $this->input->post('name');
                // $password           = $this->input->post('password');
                // $data['password']  = sha1($password);
                $data['phone']   = $this->input->post('phone');
                $data['address'] = $this->input->post('address');
                $data['role']    = $this->input->post('role');
                $this->db->where('admin_id', $para2);
                $this->db->update('admin', $data);
                $this->email_model->account_opening('admin', $data['email'], $password);
            // }
        } elseif ($para1 == 'set_password') {
            try{
                $to=$this->input->post('email');
                $admin_id=$this->input->post('admin_id');
                $code="";for($i1=0;$i1<10;$i1++){$code.=rand(0,9);}
                date_default_timezone_set("Asia/Calcutta");
                $data['email'] = $to;
                $data['code'] = sha1($code);
                $data['date_time'] = date("Y-m-d H:i:s");
                $id1  = $this->db->get_where('admin_set_password', array('admin_id' => $admin_id))->row()->admin_id;
                if($id1==""){
                    $data['admin_id'] = $admin_id;
                    $this->db->insert('admin_set_password', $data);
                }else{
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('admin_set_password', $data);
                }

                $from_name  = $this->db->get_where('general_settings', array('type' => 'system_name'))->row()->value;
                $sub="MyRunCiit set password";
                $msg="We Received your request to reset your MyRunCiit Admin panel Password<br>For Email : ".$to."<br><br><a href='".base_url()."index.php/admin/change_password/".$admin_id."/".$code."' style='background-color:#28a745!important;box-sizing:border-box;color:#fff;text-decoration:none;display:inline-block;font-size:inherit;font-weight:500;line-height:1.5;white-space:nowrap;vertical-align:middle;border-radius:.5em;padding:.75em 1.5em;border:1px solid #28a745' target='_blank'>Set Password</a><br><br><div style='color:red;'>(Note : Do not share this link to anyone)</div>";
                $this->crud_model->elastic_mail('',$from_name,$to, $sub, $msg);
                echo "Set Password link sent to mail<br>Check : ".$to."<br><b style='color:blue;'>Link Valid for 5 Min</b>";
            }
            catch(Exception $e){
                echo "Failed to set password";
            }
        } elseif ($para1 == 'delete') {
            // if ($para2 == '1' || $para2 == '3' || $para2 == '5' || $para2 == '6') {
            // } else {
                $this->db->where('admin_id', $para2);
                $this->db->delete('admin');
            // }
        } elseif ($para1 == 'list') {
            $this->db->order_by('admin_id', 'desc');
            $page_data['all_admins'] = $this->db->get('admin')->result_array();
            $page_data['user_rights_37_0'] = $this->get_user_rights(37,0);
            $this->load->view('back/admin/admin_list', $page_data);
        } elseif ($para1 == 'view') {
            $page_data['admin_data'] = $this->db->get_where('admin', array(
                'admin_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/admin_view', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/admin_add');
        } elseif ($para1 == 'suspend_set') {
            $brand = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('admin_id', $brand);
            $this->db->update('admin', $data);
        } else {
            $page_data['page_name']  = "admin";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['user_rights_37_0'] = $this->get_user_rights(37,0);
            $page_data['all_admins'] = $this->db->get('admin')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    function admins_log($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('admin')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->input->post() != '') {
            $from = $page_data['from'] = $this->input->post('from');
            $to = $page_data['to'] = $this->input->post('to');
        }
        if ($para1 == 'list') {            
            if ($para2 != '0') {
                $from = strtotime($para2 . ' 00:00:00');
                $this->db->where('timestamp >=', $from);
            }
            if ($para3 != '0') {
                $to = strtotime($para3 . ' 23:59:59');
                $this->db->where('timestamp <=', $to);
            }
            
            $this->db->order_by('admin_id', 'desc');
            $page_data['all_admins'] = $this->db->get('admin')->result_array();
            // echo $this->db->last_query();
            $this->load->view('back/admin/admins_log_list', $page_data);
        } else if ($para1 == 'view') {
            $this->db->order_by('id', 'desc');
            $description = array('Login Successfully', 'Logout Successfully');
            $this->db->where('admin_id', $para2);
            $this->db->where_in('description', $description);//WHERE author IN ('Bob', 'Geoff')
            $page_data['admin_log'] = $this->db->get('admin_log')->result_array();

            // echo $this->db->last_query();
            $page_data['view_rights']=$this->get_user_view_rights();
            $this->load->view('back/admin/admins_log_history', $page_data);
        } else {
            $page_data['page_name']  = "admins_log";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['all_admins'] = $this->db->get('admin')->result_array();
            // print_r($page_data); exit;
            $this->load->view('back/index', $page_data);
        }
    }

    /* Account Role Management */
    function role($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('role')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['name']        = $this->input->post('name');
            //$data['permission']  = json_encode($this->input->post('permission'));
            $data['description'] = $this->input->post('description');
            $perm1=$this->input->post('permission1');
            $perm2=$this->input->post('permission2');
            $perm3=$this->input->post('permission3');
            $perm4=$this->input->post('permission4');
            $this->db->insert('role', $data);
            $role_id = $this->db->insert_id();
            $menu_main_list = $this->db->get('menu_main')->result_array();
            $menu_sub_list = $this->db->get('menu_sub')->result_array();
            $menu_sub_list1=[];
            for($i1=0;$i1<count($menu_sub_list);$i1++)
            {$menu_sub_list1[$menu_sub_list[$i1]['main_menu_id']][$menu_sub_list[$i1]['id']]=$menu_sub_list[$i1];}
            foreach($menu_main_list as $menu_main_list1)
            {
                $id1=$menu_main_list1['id'];
                if($menu_main_list1['have_sub']=="1"){
                    foreach($menu_sub_list1[$id1] as $menu_sub_list2)
                    {
                        $id2=$menu_sub_list2['id'];
                        $data1=[];
                        $data1['role_id']=$role_id;
                        $data1['main_menu_id']=$id1;
                        $data1['sub_menu_id']=$id2;
                        $data1['view_rights']=(in_array("perm1_".$id1."_".$id2,$perm1))?"1":"0";
                        $data1['add_rights']=(in_array("perm2_".$id1."_".$id2,$perm2))?"1":"0";
                        $data1['edit_rights']=(in_array("perm3_".$id1."_".$id2,$perm3))?"1":"0";
                        $data1['delete_rights']=(in_array("perm4_".$id1."_".$id2,$perm4))?"1":"0";
                        $this->db->insert('menu_permissions', $data1);
                    }
                }else{
                    $data1=[];$id2="0";
                    $data1['role_id']=$role_id;
                    $data1['main_menu_id']=$id1;
                    $data1['sub_menu_id']=$id2;
                    $data1['view_rights']=(in_array("perm1_".$id1."_".$id2,$perm1))?"1":"0";
                    $data1['add_rights']=(in_array("perm2_".$id1."_".$id2,$perm2))?"1":"0";
                    $data1['edit_rights']=(in_array("perm3_".$id1."_".$id2,$perm3))?"1":"0";
                    $data1['delete_rights']=(in_array("perm4_".$id1."_".$id2,$perm4))?"1":"0";
                    $this->db->insert('menu_permissions', $data1);
                }
            }
        } elseif ($para1 == "update") {
            $data['name']        = $this->input->post('name');
            //$data['permission']  = json_encode($this->input->post('permission'));
            $data['description'] = $this->input->post('description');
            $perm1=$this->input->post('permission1');
            $perm2=$this->input->post('permission2');
            $perm3=$this->input->post('permission3');
            $perm4=$this->input->post('permission4');
            $this->db->where('role_id', $para2);
            $this->db->update('role', $data);
            $menu_main_list = $this->db->get('menu_main')->result_array();
            $menu_sub_list = $this->db->get('menu_sub')->result_array();
            $menu_sub_list1=[];
            for($i1=0;$i1<count($menu_sub_list);$i1++)
            {$menu_sub_list1[$menu_sub_list[$i1]['main_menu_id']][$menu_sub_list[$i1]['id']]=$menu_sub_list[$i1];}
            foreach($menu_main_list as $menu_main_list1)
            {
                $id1=$menu_main_list1['id'];
                if($menu_main_list1['have_sub']=="1"){
                    foreach($menu_sub_list1[$id1] as $menu_sub_list2)
                    {
                        $id2=$menu_sub_list2['id'];
                        $data1=[];
                        $data1['view_rights']=(in_array("perm1_".$id1."_".$id2,$perm1))?"1":"0";
                        $data1['add_rights']=(in_array("perm2_".$id1."_".$id2,$perm2))?"1":"0";
                        $data1['edit_rights']=(in_array("perm3_".$id1."_".$id2,$perm3))?"1":"0";
                        $data1['delete_rights']=(in_array("perm4_".$id1."_".$id2,$perm4))?"1":"0";
                        $this->db->where('role_id', $para2);
                        $this->db->where('main_menu_id', $id1);
                        $this->db->where('sub_menu_id', $id2);
                        $this->db->update('menu_permissions', $data1);
                    }
                }else{
                    $data1=[];$id2="0";
                    $data1['view_rights']=(in_array("perm1_".$id1."_".$id2,$perm1))?"1":"0";
                    $data1['add_rights']=(in_array("perm2_".$id1."_".$id2,$perm2))?"1":"0";
                    $data1['edit_rights']=(in_array("perm3_".$id1."_".$id2,$perm3))?"1":"0";
                    $data1['delete_rights']=(in_array("perm4_".$id1."_".$id2,$perm4))?"1":"0";
                    $this->db->where('role_id', $para2);
                    $this->db->where('main_menu_id', $id1);
                    $this->db->where('sub_menu_id', $id2);
                    $this->db->update('menu_permissions', $data1);
                }
            }
        } elseif ($para1 == 'delete') {
            if ($para2 == '1' || $para2 == '7' || $para2 == '5' || $para2 == '9') {
            } else {
                $this->db->where('role_id', $para2);
                $this->db->delete('role');
            }
        } elseif ($para1 == 'list') {
            $this->db->order_by('role_id', 'desc');
            $page_data['all_roles'] = $this->db->get('role')->result_array();
            $page_data['user_rights_39_0'] = $this->get_user_rights(39,0);
            $this->load->view('back/admin/role_list', $page_data);
        } elseif ($para1 == 'view') {
            $page_data['role_data'] = $this->db->get_where('role', array(
                'role_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/role_view', $page_data);
        } elseif ($para1 == 'add') {
            //$page_data['all_permissions'] = $this->db->get('permission')->result_array();
            $page_data['menu_main_list'] = $this->db->get('menu_main')->result_array();
            $page_data['menu_sub_list'] = $this->db->get('menu_sub')->result_array();
            $this->load->view('back/admin/role_add', $page_data);
        } else if ($para1 == 'edit') {
            /* if ($para2 == '1' || $para2 == '7' || $para2 == '5' || $para2 == '9') {
            } else { */
                //$page_data['all_permissions'] = $this->db->get('permission')->result_array();
                $page_data['role_data']       = $this->db->get_where('role', array(
                    'role_id' => $para2
                ))->result_array();
                $page_data['menu_main_list'] = $this->db->get('menu_main')->result_array();
                $page_data['menu_sub_list'] = $this->db->get('menu_sub')->result_array();
                
                $tb=$this->db->get_where('menu_permissions',array(
                    'role_id'=>$para2
                ))->result_array();
                $view_rights=[];
                for($i1=0;$i1<count($tb);$i1++)
                {
                    $rights_1=$tb[$i1];
                    $view_rights[$rights_1['main_menu_id']][$rights_1['sub_menu_id']]=$rights_1;
                }
                $page_data['menu_permissions_list']=$view_rights;
                $this->load->view('back/admin/role_edit', $page_data);
            //}
        } else {
            $page_data['page_name'] = "role";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['user_rights_39_0'] = $this->get_user_rights(39,0);
            $page_data['all_roles'] = $this->db->get('role')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }


    /* Checking if email exists*/
    function load_dropzone()
    {
        $this->load->view('back/admin/dropzone');
    }

    /* Checking if email exists*/
    function exists()
    {
        $email  = $this->input->post('email');
        $admin  = $this->db->get('admin')->result_array();
        $exists = 'no';
        foreach ($admin as $row) {
            if ($row['email'] == $email) {
                $exists = 'yes';
            }
        }
        echo $exists;
    }

    /* Login into Admin panel */
    function login($para1 = '')
    {
        if ($para1 == 'forget_form') {
            $page_data['control'] = 'admin';
            $this->load->view('back/forget_password', $page_data);
        } else if ($para1 == 'forget') {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $query = $this->db->get_where('admin', array(
                    'email' => $this->input->post('email')
                ));
                if ($query->num_rows() > 0) {
                    $admin_id         = $query->row()->admin_id;
                    $password         = substr(hash('sha512', rand()), 0, 12);
                    $data['password'] = sha1($password);
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('admin', $data);
                    if ($this->email_model->password_reset_email('admin', $admin_id, $password)) {
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

            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $login_data = $this->db->get_where('admin', array(
                    'email' => $this->input->post('email'),
                    'password' => sha1($this->input->post('password'))
                ));
                if ($login_data->num_rows() > 0) {
                    date_default_timezone_set("Asia/Kuala_Lumpur");
                    foreach ($login_data->result_array() as $row) {

                        $adminlog['admin_id'] =  $row['admin_id'];
                        $adminlog['description'] = "Login Successfully";
                        $this->db->insert('admin_log', $adminlog);

                        $this->db->where('admin_id', $row['admin_id']);
                        $this->db->update('admin', array(
                            'last_login' => time()
                        ));
                        $this->session->set_userdata('login', 'yes');
                        $this->session->set_userdata('admin_login', 'yes');
                        $this->session->set_userdata('admin_id', $row['admin_id']);
                        $this->session->set_userdata('admin_name', $row['name']);
                        $this->session->set_userdata('session_role_id', $row['role']);
                        $this->session->set_userdata('title', 'admin');
                        echo 'lets_login';
                    }
                } else {
                    echo 'login_failed';
                }
            }
        }
    }

    /* Loging out from Admin panel */
    function logout()
    {
        $adminlog['admin_id'] =  $this->session->userdata('admin_id');
        $adminlog['description'] = "Logout Successfully";
        $this->db->insert('admin_log', $adminlog);

        $this->session->sess_destroy();
        redirect(base_url() . 'index.php/admin', 'refresh');
    }

    /* Sending Newsletters */
    function newsletter($para1 = "")
    {
        
        if (!$this->crud_model->admin_permission('newsletter')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == "send") {
            $users       = explode(',', $this->input->post('users'));
            $subscribers = explode(',', $this->input->post('subscribers'));
            $text        = $this->input->post('text');
            $checkbox = $this->input->post('no_reply_checkbox') === 'true';
            $title       = $this->input->post('title');
            $from        = $this->input->post('from');
            $imagePaths = $this->extractImagePathsFromHtml($text);
            if($checkbox){
                $text .= "\nNo Reply This is an Autogenerated Email";
            }
            foreach ($users as $key => $user) {
                if ($user !== '') {
                    $this->email_model->newsletter($title, $text, $user, $from);
                }
            }
            $from_name  = $this->db->get_where('general_settings', array('type' => 'system_name'))->row()->value;
            foreach ($subscribers as $key => $subscriber) {
                if ($subscriber !== '') {
                    // $data[] = ['title' => $title, 'from'=>$from, 'user' => $user, 'checked' => $checkbox, 'images' => $this->getBase64ImageUrls($text)];
                    // echo  json_encode($data);
                    // exit;
                    // $this->email_model->newsletter($title, $text, $subscriber, $from);
                    $text=str_replace($imagePaths," ",$text);
                    $this->crud_model->elastic_mail1($from,$from_name,$subscriber, $sub, $text, $imagePaths);   
                }
            }
        }
        else if($para1 == "group"){
            $selectedValue = $_POST['selectedValue'];
            if($selectedValue === "0"){
                $this->db->select('email');
                $usersData = $this->db->get('user')->result_array();
                echo  json_encode($usersData);
                exit;
            }
            if (!empty($selectedValue)) {
                $this->db->select('user');
                $this->db->where('user_group_id', $selectedValue); 
                $usergroups = $this->db->get('user_group')->result_array();
                $userIds = array();
                foreach ($usergroups as $row) {
                    $ids = explode(',',$row['user']); 
                    $userIds = array_merge($userIds, $ids);
                }
                $userIds = array_unique($userIds);
                if (!empty($userIds)) {
                    $this->db->select('email');
                    $this->db->where_in('user_id', $userIds);
                    $usersData = $this->db->get('user')->result_array();
                } else {
                    $usersData = array(); // Empty array if no IDs found
                }
            }
           
            echo  json_encode($usersData);
            exit;
        }
         else {
            $page_data['users']       = $this->db->get('user')->result_array();
            $page_data['subscribers'] = $this->db->get('subscribe')->result_array();
            $page_data['page_name']   = "newsletter";
            $page_data['view_rights']=$this->get_user_view_rights();
        }
        $this->load->view('back/index', $page_data);
    }
    function whatsapp_message_bk($para1 = "")
    {

        if ($para1 == 'send') {
            print_r($_POST);
            print_r($this->input->post());
            exit;
            $phoneNumbers  = explode(',', $this->input->post('phoneNumbers'));
            $sid    = "AC87fe6c76cdfcb3dba5034f623690b040";
            $token  = "5076c56d95cd4cb40a7a0e9cd4f14ca6";
            $messagingServiceSid     =  "+60146482623";
            require_once(APPPATH . 'libraries/Twilio/Twilio.php');

            foreach ($phoneNumbers as $phone) {
                $mobile = $phone;
                $sms  = $this->input->post('content');;

                $ordersms = sendotp_whatsapp($sid, $token, $messagingServiceSid, $mobile, $sms);
            }
        } else {
            $page_data['page_name']      = "whatsapp_message";
            $page_data['all_mag'] = $this->db->get('whatsapp_log')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    function whatsapp_message($para1 = '', $para2 = '')
    {

        if ($para1 == 'do_add') {
            // print_r($_POST);
            //print_r($this->input->post());
            // exit;
            $phoneNumbers  = explode(',', $this->input->post('phoneNumbers'));
            $sid    = "AC98744eee6355b83a7c15b1798bf4db6b";
            $token  = "937e6d4ea90d7d82ac713c63872b7d20";
            $messagingServiceSid     =  "+14155238886"; // sandbox number
            require_once(APPPATH . 'libraries/Twilio/Twilio.php');

            foreach ($phoneNumbers as $phone) {
                $mobile = $phone;
                $sms  = $this->input->post('content');
                $messageData = sendotp_whatsapp($sid, $token, $messagingServiceSid, $mobile, $sms);
                print('message ' . $messageData);
                # insert into database
                $this->db->insert('whatsapp_log', [
                    'number' => $mobile,
                    'description' => $sms,
                    'status' => $messageData['status'],
                    'errorCode' => $messageData['errorCode'],
                    'date' => strtotime(date('Y-m-d'))
                ]);
                if ($messageData != 'failed') {
                    $this->session->set_flashdata('success', 'message is sent');
                } else {
                    # has error
                    $this->session->set_flashdata('error', 'Can not send message, please check your number');
                }
            }
            recache();
        } elseif ($para1 == 'delete') {
            unlink("uploads/category_image/" . $this->crud_model->get_type_name_by_id('category', $para2, 'banner'));
            $this->db->where('category_id', $para2);
            $this->db->delete('category');
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('id', 'desc');

            $page_data['all_msg'] = $this->db->get('whatsapp_log')->result_array();
            $this->load->view('back/admin/whatsapp_message_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/whatsapp_message_add');
        } else {
            $page_data['page_name']      = "whatsapp_message";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['user_rights_16_0'] = $this->get_user_rights(16,0);
            $page_data['all_msg'] = $this->db->get('whatsapp_log')->result_array();
            // print_r($page_data['all_msg']);
            $this->load->view('back/index', $page_data);
        }
    }

    /* Add, Edit, Delete, Duplicate, Enable, Disable Sliders */
    function slider($para1 = '', $para2 = '', $para3 = '')
    {
        if ($para1 == 'list') {
            $this->db->order_by('slider_id', 'desc');
            $page_data['all_slider'] = $this->db->get('slider')->result_array();
            $page_data['user_rights_19_0'] = $this->get_user_rights(19,0);
            $this->load->view('back/admin/slider_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/slider_set');
        } elseif ($para1 == 'add_form') {
            $page_data['style_id'] = $para2;
            $page_data['style']    = json_decode($this->db->get_where('slider_style', array(
                'slider_style_id' => $para2
            ))->row()->value, true);
            $this->load->view('back/admin/slider_add_form', $page_data);
        } else if ($para1 == 'delete') { //ll
            $elements = json_decode($this->db->get_where('slider', array(
                'slider_id' => $para2
            ))->row()->elements, true);
            $style    = $this->db->get_where('slider', array(
                'slider_id' => $para2
            ))->row()->style;
            $style    = json_decode($this->db->get_where('slider_style', array(
                'slider_style_id' => $style
            ))->row()->value, true);
            $images   = $style['images'];
            if (file_exists('uploads/slider_image/background_' . $para2 . '.jpg')) {
                unlink('uploads/slider_image/background_' . $para2 . '.jpg');
            }
            foreach ($images as $row) {
                if (file_exists('uploads/slider_image/' . $para2 . '_' . $row . '.png')) {
                    unlink('uploads/slider_image/' . $para2 . '_' . $row . '.png');
                }
            }
            $this->db->where('slider_id', $para2);
            $this->db->delete('slider');
            recache();
        } else if ($para1 == 'serial') {
            $this->db->order_by('serial', 'desc');
            $this->db->order_by('slider_id', 'desc');
            $page_data['slider'] = $this->db->get_where('slider', array(
                'status' => 'ok'
            ))->result_array();
            $this->load->view('back/admin/slider_serial', $page_data);
        } else if ($para1 == 'do_serial') {
            $input  = json_decode($this->input->post('serial'), true);
            $serial = array();
            foreach ($input as $r) {
                $serial[] = $r['id'];
            }
            $serial  = array_reverse($serial);
            $sliders = $this->db->get('slider')->result_array();
            foreach ($sliders as $row) {
                $data['serial'] = 0;
                $this->db->where('slider_id', $row['slider_id']);
                $this->db->update('slider', $data);
            }
            foreach ($serial as $i => $row) {
                $data1['serial'] = $i + 1;
                $this->db->where('slider_id', $row);
                $this->db->update('slider', $data1);
            }
            recache();
        } else if ($para1 == 'slider_publish_set') {
            $slider = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
                $data['serial'] = 0;
            }
            $this->db->where('slider_id', $slider);
            $this->db->update('slider', $data);
            recache();
        } else if ($para1 == 'edit') {
            $page_data['slider_data'] = $this->db->get_where('slider', array(
                'slider_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/slider_edit_form', $page_data);
        } elseif ($para1 == 'create') {
            $data['style']  = $this->input->post('style_id');
            $data['title']  = $this->input->post('title');
            $data['serial'] = 0;
            $data['status'] = 'ok';
            $style          = json_decode($this->db->get_where('slider_style', array(
                'slider_style_id' => $data['style']
            ))->row()->value, true);
            $images         = array();
            $texts          = array();
            foreach ($style['images'] as $image) {
                if ($_FILES[$image['name']]['name']) {
                    $images[] = $image['name'];
                }
            }
            foreach ($style['texts'] as $text) {
                if ($this->input->post($text['name']) !== '') {
                    $texts[] = array(
                        'name' => $text['name'],
                        'text' => $this->input->post($text['name']),
                        'color' => $this->input->post($text['name'] . '_color'),
                        'background' => $this->input->post($text['name'] . '_background'),
                        'fontFamily' => $this->input->post($text['name'] . '_fontFamily'),
                    );
                }
            }
            $elements         = array(
                'images' => $images,
                'texts' => $texts
            );
            $data['elements'] = json_encode($elements);
            $this->db->insert('slider', $data);
            $id = $this->db->insert_id();

            move_uploaded_file($_FILES['background']['tmp_name'], 'uploads/slider_image/background_' . $id . '.jpg');
            foreach ($elements['images'] as $image) {
                move_uploaded_file($_FILES[$image]['tmp_name'], 'uploads/slider_image/' . $id . '_' . $image . '.png');
            }
            recache();
        } elseif ($para1 == 'update') {
            $data['style'] = $this->input->post('style_id');
            $data['title'] = $this->input->post('title');
            $style         = json_decode($this->db->get_where('slider_style', array(
                'slider_style_id' => $data['style']
            ))->row()->value, true);
            $images        = array();
            $texts         = array();
            foreach ($style['images'] as $image) {
                if ($_FILES[$image['name']]['name'] || $this->input->post($image['name'] . '_same') == 'same') {
                    $images[] = $image['name'];
                }
            }
            foreach ($style['texts'] as $text) {
                if ($this->input->post($text['name']) !== '') {
                    $texts[] = array(
                        'name' => $text['name'],
                        'text' => $this->input->post($text['name']),
                        'color' => $this->input->post($text['name'] . '_color'),
                        'background' => $this->input->post($text['name'] . '_background'),
                        'fontFamily' => $this->input->post($text['name'] . '_fontFamily'),
                    );
                }
            }
            $elements         = array(
                'images' => $images,
                'texts' => $texts
            );
            $data['elements'] = json_encode($elements);
            $this->db->where('slider_id', $para2);
            $this->db->update('slider', $data);

            move_uploaded_file($_FILES['background']['tmp_name'], 'uploads/slider_image/background_' . $para2 . '.jpg');
            foreach ($elements['images'] as $image) {
                move_uploaded_file($_FILES[$image]['tmp_name'], 'uploads/slider_image/' . $para2 . '_' . $image . '.png');
            }
            recache();
        } else {
            $page_data['page_name'] = "slider";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['user_rights_19_0'] = $this->get_user_rights(19,0);
            $this->load->view('back/index', $page_data);
        }
    }
    function activation()
    {
        if (!$this->crud_model->admin_permission('business_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $page_data['page_name'] = "activation";
        $this->load->view('back/index', $page_data);
    }
    function faqs()
    {
        if (!$this->crud_model->admin_permission('business_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $page_data['page_name'] = "faq_settings";
        $page_data['view_rights']=$this->get_user_view_rights();
        $this->load->view('back/index', $page_data);
    }
    function payment_method()
    {
        if (!$this->crud_model->admin_permission('business_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $page_data['page_name'] = "payment_method";
        $this->load->view('back/index', $page_data);
    }
    function curency_method()
    {
        if (!$this->crud_model->admin_permission('business_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $page_data['page_name'] = "curency_method";
        $page_data['view_rights']=$this->get_user_view_rights();
        $this->load->view('back/index', $page_data);
    }

    /* Manage Frontend User Interface */
    function set_def_curr($para1 = '', $para2 = '', $para3 = '', $para4 = '')
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'home') {
            $this->db->where('type', "home_def_currency");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('home_def_currency')
            ));
        }
        if ($para1 == 'system') {
            $this->db->where('type', "currency");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('currency')
            ));

            $this->db->where('currency_settings_id', $this->input->post('currency'));
            $this->db->update('currency_settings', array(
                'exchange_rate_def' => '1'
            ));
        }
        recache();
    }


    /* Manage Frontend User Interface */
    function ui_settings($para1 = '', $para2 = '', $para3 = '', $para4 = '')
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'delivery_slot') {
            //echo 1;
            if ($this->input->post('delivery_slot') == 'on') {
                $res = 'ok';
            } else {
                $res = 'no';
            }
            $this->db->where('type', "delivery_slot");
            $this->db->update('general_settings', array(
                'value' => $res
            ));
            recache();
        }
        //echo $para1;exit;
        elseif ($para1 == "ui_home") {
            if ($para2 == 'update_home_page') {
                $this->db->where('type', "home_page_style");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('home_page')
                ));
                recache();
            } elseif ($para2 == 'home_vendor') {
                if ($this->crud_model->get_type_name_by_id('general_settings', '58', 'value') !== 'ok') {
                    redirect(base_url() . 'index.php/admin');
                }
                $this->db->where('type', "parallax_vendor_title");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('pv_title')
                ));
                $this->db->where('type', "no_of_vendor");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('vendor_no')
                ));
                if ($_FILES["par"]['tmp_name']) {
                    move_uploaded_file($_FILES["par"]['tmp_name'], 'uploads/others/parralax_vendor.jpg');
                }
                recache();
            } elseif ($para2 == 'home_search') {
                $this->db->where('type', "parallax_search_title");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('ps_title')
                ));
                if ($_FILES["par3"]['tmp_name']) {
                    move_uploaded_file($_FILES["par3"]['tmp_name'], 'uploads/others/parralax_search.jpg');
                }
                recache();
            } elseif ($para2 == 'home_blog') {
                $this->db->where('type', "parallax_blog_title");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('pb_title')
                ));
                $this->db->where('type', "no_of_blog");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('blog_no')
                ));
                if ($_FILES["par2"]['tmp_name']) {
                    move_uploaded_file($_FILES["par2"]['tmp_name'], 'uploads/others/parralax_blog.jpg');
                }
                recache();
            } elseif ($para2 == 'top_slide_categories') {
                $this->db->where('type', "top_slide_categories");
                $this->db->update('ui_settings', array(
                    'value' => json_encode($this->input->post('top_category'))
                ));

                $this->db->where('type', "no_of_todays_deal");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('deal_no')
                ));
                recache();
            }
            /*elseif ($para2 == 'todays_deal') {
                $this->db->where('type', "no_of_deal_products");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('flash_no')
                ));

                $this->db->where('type', "todays_deal_product_box_style");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('deal_pro_box')
                ));
                recache();
            }*/ elseif ($para2 == 'home_brand') {
                $this->db->where('type', "no_of_brands");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('brand_no')
                ));
                recache();
            } elseif ($para2 == 'home_featured') {
                $this->db->where('type', "no_of_featured_products");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('featured_no')
                ));

                $this->db->where('type', "featured_product_box_style");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('fea_pro_box')
                ));
                recache();
            } else if ($para2 == 'feature_publish_set') {
                if ($para4 == 'true') {
                    $data['value'] = 'ok';
                } else if ($para4 == 'false') {
                    $data['value'] = 'no';
                }
                $this->db->where('ui_settings_id', $para3);
                $this->db->update('ui_settings', $data);
                recache();
            } elseif ($para2 == 'home1_category') {
                $category = $this->input->post('category');
                $sub_category = $this->input->post('sub_category');
                $color_back = $this->input->post('color1');
                $color_text = $this->input->post('color2');
                $result = array();
                foreach ($category as $i => $row) {
                    $result[] = array(
                        'category' => $row,
                        'sub_category' => $sub_category[$row],
                        'color_back' => $color_back[$row],
                        'color_text' => $color_text[$row]
                    );
                }
                $data['value'] = json_encode($result);
                $this->db->where('type', 'home_categories');
                $this->db->update('ui_settings', $data);

                $this->db->where('type', "category_product_box_style");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('box_style')
                ));
                recache();
            } elseif ($para2 == 'home2_category') {
                //$box = $this->input->post('box');
                $category = $this->input->post('category');
                $sub_category = $this->input->post('sub_category');
                $color_back = $this->input->post('color1');
                $color_text = $this->input->post('color2');
                $result = array();
                foreach ($category as $i => $row) {
                    $result[] = array(
                        //'no'	=>$row,
                        'category' => $row,
                        'sub_category' => $sub_category[$row],
                        'color_back' => $color_back[$row],
                        'color_text' => $color_text[$row]
                    );
                }
                $data['value'] = json_encode($result);
                $this->db->where('type', 'home_categories');
                $this->db->update('ui_settings', $data);
                recache();
            } elseif ($para2 == 'cat_colors') {
                var_dump($para3);
            } else if ($para2 == 'customer_product_publish_set') {
                if ($para4 == 'true') {
                    $data['value'] = 'ok';
                } else if ($para4 == 'false') {
                    $data['value'] = 'no';
                }
                $this->db->where('ui_settings_id', $para3);
                $this->db->update('ui_settings', $data);
                recache();
            } else if ($para2 == 'product_bundle_publish_set') {
                if ($para4 == 'true') {
                    $data['value'] = 'ok';
                } else if ($para4 == 'false') {
                    $data['value'] = 'no';
                }
                $this->db->where('ui_settings_id', $para3);
                $this->db->update('ui_settings', $data);
                recache();
            }
        } elseif ($para1 == 'email_theme') {
            $this->db->where('type', "email_theme_style");
            $this->db->update('ui_settings', array(
                'value' => $this->input->post('email_theme')
            ));
            recache();
        } elseif ($para1 == "ui_category") {
            if ($para2 == 'update') {
                $this->db->where('type', "side_bar_pos_category");
                $this->db->update('ui_settings', array(
                    'value' => $this->input->post('side_bar_pos')
                ));
                recache();
            }
        } elseif ($para1 == 'sub_by_cat') {
            echo $this->crud_model->select_html('sub_category', 'sub-category', 'sub_category_name', 'add', 'demo-cs-multiselect', '', 'category', $para2, 'check_sub_length');
        }
        //$this->load->view('back/index', $page_data);
    }

    /* Checking Login Stat */
    function is_logged()
    {
        if ($this->session->userdata('admin_login') == 'yes') {
            echo 'yah!good';
        } else {
            echo 'nope!bad';
        }
    }

    /* Manage Frontend User Interface */
    function page_settings($para1 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $page_data['page_name'] = "page_settings";
        $page_data['tab_name']  = $para1;
        $this->load->view('back/index', $page_data);
    }

    /* Manage Frontend User Messages */
    function contact_message($para1 = "", $para2 = "")
    {
        if (!$this->crud_model->admin_permission('contact_message')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'delete') {
            $this->db->where('contact_message_id', $para2);
            $this->db->delete('contact_message');
        } elseif ($para1 == 'list') {
            $this->db->order_by('contact_message_id', 'desc');
            $page_data['contact_messages'] = $this->db->get('contact_message')->result_array();
            $this->load->view('back/admin/contact_message_list', $page_data);
        } elseif ($para1 == 'reply') {
            $data['reply'] = $this->input->post('reply');
            $this->db->where('contact_message_id', $para2);
            $this->db->update('contact_message', $data);
            $this->db->order_by('contact_message_id', 'desc');
            $query = $this->db->get_where('contact_message', array(
                'contact_message_id' => $para2
            ))->row();
            $this->email_model->do_email($data['reply'], 'RE: ' . $query->subject, $query->email);
        } elseif ($para1 == 'view') {
            $page_data['message_data'] = $this->db->get_where('contact_message', array(
                'contact_message_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/contact_message_view', $page_data);
        } elseif ($para1 == 'reply_form') {
            $page_data['message_data'] = $this->db->get_where('contact_message', array(
                'contact_message_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/contact_message_reply', $page_data);
        } else {
            $page_data['page_name']        = "contact_message";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['contact_messages'] = $this->db->get('contact_message')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Manage Logos */
    function logo_settings($para1 = "", $para2 = "", $para3 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == "select_logo") {
            $page_data['page_name'] = "select_logo";
        } elseif ($para1 == "delete_logo") {
            if (file_exists("uploads/logo_image/logo_" . $para2 . ".png")) {
                unlink("uploads/logo_image/logo_" . $para2 . ".png");
            }
            $this->db->where('logo_id', $para2);
            $this->db->delete('logo');
            recache();
        } elseif ($para1 == "set_logo") {

            $type    = $this->input->post('type');
            $logo_id = $this->input->post('logo_id');
            $this->db->where('type', $type);
            $this->db->update('ui_settings', array(
                'value' => $logo_id
            ));
            recache();
        } elseif ($para1 == "show_all") {
            $page_data['logo'] = $this->db->get('logo')->result_array();
            if ($para2 == "") {
                $this->load->view('back/admin/all_logo', $page_data);
            }
            if ($para2 == "selectable") {
                $page_data['logo_type'] = $para3;
                $this->load->view('back/admin/select_logo', $page_data);
            }
        } elseif ($para1 == "upload_logo") {
            foreach ($_FILES["file"]['name'] as $i => $row) {
                $data['name'] = '';
                $this->db->insert("logo", $data);
                $id = $this->db->insert_id();
                move_uploaded_file($_FILES["file"]['tmp_name'][$i], 'uploads/logo_image/logo_' . $id . '.png');
            }
            return;
        } elseif ($para1 == "upload_logo1") {
            $data['name'] = '';
            $this->db->insert("logo", $data);
            $id = $this->db->insert_id();
            echo $_FILES["logo"]['name'];
            move_uploaded_file($_FILES["logo"]['tmp_name'], 'uploads/logo_image/logo_' . $id . '.png');
        } else {
            $this->load->view('back/index', $page_data);
        }
    }

    /* Manage Favicons */
    function favicon_settings($para1 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $name = $_FILES['img']['name'];
        $ext  = end((explode(".", $name)));
        $this->db->where('type', 'fav_ext');
        $this->db->update('ui_settings', array(
            'value' => $ext
        ));
        move_uploaded_file($_FILES['img']['tmp_name'], 'uploads/others/favicon.' . $ext);
        recache();
    }

    /* Manage Frontend Facebook Login Credentials */
    function social_login_settings($para1 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $this->db->where('type', "fb_appid");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('appid')
        ));
        $this->db->where('type', "fb_secret");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('secret')
        ));
        $this->db->where('type', "application_name");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('application_name')
        ));
        $this->db->where('type', "client_id");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('client_id')
        ));
        $this->db->where('type', "client_secret");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('client_secret')
        ));
        $this->db->where('type', "redirect_uri");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('redirect_uri')
        ));
        $this->db->where('type', "api_key");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('api_key')
        ));
    }

    function sms_settings($para1 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $this->db->where('type', "k_sid");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('k_sid')
        ));
        $this->db->where('type', "k_apikey");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('k_apikey')
        ));
        $this->db->where('type', "k_sender");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('k_sender')
        ));
        $this->db->where('type', "t_account_sid");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('t_account_sid')
        ));
        $this->db->where('type', "t_auth_token");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('t_auth_token')
        ));
        $this->db->where('type', "twilio_number");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('twilio_number')
        ));
        $this->db->where('type', "elastic_apikey");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('elastic_apikey')
        ));
    }
    /* Manage Frontend Facebook Login Credentials */
    function product_comment($para1 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $this->db->where('type', "discus_id");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('discus_id')
        ));
        $this->db->where('type', "comment_type");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('type')
        ));
        $this->db->where('type', "fb_comment_api");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('fb_comment_api')
        ));
    }

    /* Manage Frontend Captcha Settings Credentials */
    function captcha_settings($para1 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $this->db->where('type', "captcha_public");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('cpub')
        ));
        $this->db->where('type', "captcha_private");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('cprv')
        ));
    }

    /* Manage Site Settings */
    function site_settings($para1 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $page_data['page_name'] = "site_settings";
        $page_data['view_rights']=$this->get_user_view_rights();
        $page_data['tab_name']  = $para1;
        $this->load->view('back/index', $page_data);
    }

    /* Manage Email Template */
    function email_template($para1 = "", $para2 = "")
    {
        $var = "demo";
        if (!$this->crud_model->admin_permission('email_template')) {
            redirect(base_url() . 'index.php/admin');
        }

        if ($para1 = "update" && $var == '') {
            $data['subject'] = $this->input->post('subject');
            $data['body'] = $this->input->post('body');

            $this->db->where('email_template_id', $para2);
            $this->db->update('email_template', $data);
        }
        $page_data['page_name'] = "email_template";
        $page_data['view_rights']=$this->get_user_view_rights();
        $page_data['table_info']  = $this->db->get('email_template')->result_array();;
        $this->load->view('back/index', $page_data);
    }

    /* Manage Languages */
    function language_settings($para1 = "", $para2 = "", $para3 = "")
    {
        if (!$this->crud_model->admin_permission('language')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'add_lang') {
            $this->load->view('back/admin/language_add');
        } elseif ($para1 == 'edit_lang') {
            $page_data['lang_data'] = $this->db->get_where('language_list', array('language_list_id' => $para2))->result_array();
            $this->load->view('back/admin/language_edit', $page_data);
        } elseif ($para1 == 'lang_list') {
            //if($para2 !== ''){
            $this->db->order_by('word_id', 'desc');
            $page_data['words'] = $this->db->get('language')->result_array();
            $page_data['lang']  = $para2;
            $page_data['user_rights_35_0'] = $this->get_user_rights(35,0);
            $this->load->view('back/admin/language_list', $page_data);
            //}
        } elseif ($para1 == 'list_data') {
            $limit      = $this->input->get('limit');
            $search     = $this->input->get('search');
            $order      = $this->input->get('order');
            $offset     = $this->input->get('offset');
            $sort       = $this->input->get('sort');
            if ($search) {
                $this->db->like('word', $search, 'both');
            }
            $total      = $this->db->get('language')->num_rows();
            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'word_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            if ($search) {
                $this->db->like('word', $search, 'both');
            }
            $lang       = $para2;
            if ($lang == 'undefined' || $lang == '') {
                if ($lang = $this->session->userdata('language')) {
                } else {
                    $lang = $this->db->get_where('general_settings', array(
                        'type' => 'language'
                    ))->row()->value;
                }
            }
            $words      = $this->db->get('language', $limit, $offset)->result_array();
            $data       = array();
            foreach ($words as $row) {

                $res    = array(
                    'no' => '',
                    'word' => '',
                    'translation' => '',
                    'options' => ''
                );

                $res['no']  = $row['word_id'];
                $res['word']  = '<div class="col-md-12 abv">' . ucwords(str_replace('_', ' ', $row['word'])) . '</div>';
                $res['translation']  =   form_open(base_url() . 'index.php/admin/language_settings/upd_trn/' . $row['word_id'], array(
                    'class' => 'form-horizontal trs',
                    'method' => 'post',
                    'id' => $lang . '_' . $row['word_id']
                ));
                $res['translation']  .=      '   <div class="col-md-8">';
                $res['translation']  .=      '      <input type="text" name="translation" value="' . $row[$lang] . '" class ="form-control ann" />';
                $res['translation']  .=      '      <input type="hidden" name="lang" value="' . $lang . '" />';
                $res['translation']  .=      '   </div>';
                $res['translation']  .=      '   <div class="col-md-4">';
                $res['translation']  .=      '       <span class="btn btn-success btn-xs btn-labeled fa fa-wrench submittera" data-wid="' . $lang . '_' . $row['word_id'] . '"  data-ing="' . translate('saving') . '" data-msg="' . translate('updated!') . '" >' . translate('save') . '</span>';
                $res['translation']  .=      '   </div>';
                $res['translation']  .=      '</form>';

                //add html for action
                $res['options'] = "<a onclick=\"delete_confirm('" . $row['word_id'] . "','" . translate('really_want_to_delete_this_word?') . "')\" 
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    " . translate('delete') . "
                            </a>";
                $data[] = $res;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );

            echo json_encode($result);
        } elseif ($para1 == 'upd_trn') {
            $word_id     = $para2;
            $translation = $this->input->post('translation');
            $language    = $this->input->post('lang');
            $word        = $this->db->get_where('language', array(
                'word_id' => $word_id
            ))->row()->word;
            add_translation($word, $language, $translation);
            recache();
        } elseif ($para1 == 'do_add_lang') {
            $data['name']   = $this->input->post('language');
            $this->db->insert('language_list', $data);

            $id             = $this->db->insert_id();
            $this->crud_model->file_up("icon", "language_list", $id, '', '', '.jpg');

            $language       = 'lang_' . $id;

            $this->db->where('language_list_id', $id);
            $this->db->update('language_list', array(
                'db_field' => $language,
                'status' => 'ok'
            ));

            add_language($language);
            recache();
        } elseif ($para1 == 'do_edit_lang') {
            $this->db->where('language_list_id', $para2);
            $this->db->update('language_list', array(
                'name' => $this->input->post('language')
            ));
            $this->crud_model->file_up("icon", "language_list", $para2, '', '', '.jpg');
            recache();
        } else if ($para1 == "lang_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('language_list_id', $para2);
            $this->db->update('language_list', array(
                'status' => $val
            ));
            recache();
        } elseif ($para1 == 'check_existed') {
            echo lang_check_exists($para2);
        } elseif ($para1 == 'lang_select') {
            $page_data['lang'] = $para2;
            $this->load->view('back/admin/language_select', $page_data);
        } elseif ($para1 == 'dlt_lang') {
            $this->db->where('db_field', $para2);
            $this->db->delete('language_list');
            $this->load->dbforge();
            $this->dbforge->drop_column('language', $para2);
            recache();
        } elseif ($para1 == 'dlt_word') {
            $this->db->where('word_id', $para2);
            $this->db->delete('language');
            recache();
        } else {
            $page_data['page_name'] = "language";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['user_rights_35_0'] = $this->get_user_rights(35,0);
            $this->load->view('back/index', $page_data);
        }
    }

    /* Manage Business Settings */
    function business_settings($para1 = "", $para2 = "", $para3 = "")
    {
        if (!$this->crud_model->admin_permission('business_settings')) {
            redirect(base_url() . 'index.php/admin');
        } else if ($para1 == "wallet_system_set") {

            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }


            $this->db->where('type', "wallet_system_set");
            $this->db->update('general_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "guest_checkout_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }

            $this->db->where('type', "guest_checkout_set");
            $this->db->update('general_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "enquiry") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
                $this->db->update('product', array(
                    'enquiry' => 'ok'
                ));
            } else if ($para3 == 'false') {
                $val = 'no';
                $this->db->update('product', array(
                    'enquiry' => 'no'
                ));
            }

            $this->db->where('type', "enquiry");
            $this->db->update('general_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "subscribe") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
                $this->db->update('product', array(
                    'enquiry' => 'ok'
                ));
            } else if ($para3 == 'false') {
                $val = 'no';
                $this->db->update('product', array(
                    'enquiry' => 'no'
                ));
            }

            $this->db->where('type', "subscribe");
            $this->db->update('general_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "rewards") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }

            $this->db->where('type', "rewards");
            $this->db->update('general_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "callnow") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
                $this->db->update('product', array(
                    'callnow' => 'ok'
                ));
            } else if ($para3 == 'false') {
                $val = 'no';
                $this->db->update('product', array(
                    'callnow' => 'no'
                ));
            }

            $this->db->where('type', "callnow");
            $this->db->update('general_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "cash_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "cash_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "cod_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "cod_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));
            echo $this->db->last_query();
            recache();
        } else if ($para1 == "paypal_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "paypal_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "stripe_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "stripe_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "c2_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "c2_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "vp_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "vp_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "shiprocket") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "shiprocket");
            $this->db->update('business_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "cc_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "cc_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "cur_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }
            echo $para3.'sfsdf'.$val;
            $data['status']    = $val;
            $this->db->where('currency_settings_id', $para2);
            $this->db->update('currency_settings', $data);
            recache();
        } else if ($para1 == "vendor_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "vendor_system");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "product_affiliation_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }

            $this->db->where('type', "product_affiliation_set");
            $this->db->update('general_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "physical_product_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "physical_product_activation");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "digital_product_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "digital_product_activation");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == 'set') {
            echo $this->input->post('stripe_set');
            $this->db->where('type', "paypal_set");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('paypal_set')
            ));
            $this->db->where('type', "stripe_set");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('stripe_set')
            ));
            $this->db->where('type', "cash_set");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('cash_set')
            ));
        } else if ($para1 == 'faq_set') {
            $faqs = array();
            $f_q  = $this->input->post('f_q');
            $f_a  = $this->input->post('f_a');
            foreach ($f_q as $i => $r) {
                $faqs[] = array(
                    'question' => $f_q[$i],
                    'answer' => $f_a[$i]
                );
            }
            $this->db->where('type', "faqs");
            $this->db->update('business_settings', array(
                'value' => json_encode($faqs)
            ));
        } else if ($para1 == 'set1') {
            $this->db->where('type', "paypal_email");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('paypal_email')
            ));

            $this->db->where('type', "paypal_type");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('paypal_type')
            ));
            $this->db->where('type', "stripe_secret");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('stripe_secret')
            ));
            $this->db->where('type', "stripe_publishable");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('stripe_publishable')
            ));
            $this->db->where('type', "c2_user");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('c2_user')
            ));
            $this->db->where('type', "c2_secret");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('c2_secret')
            ));
            $this->db->where('type', "c2_type");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('c2_type')
            ));
            $this->db->where('type', "vp_merchant_id");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('vp_merchant_id')
            ));
            $this->db->where('type', "shipping_cost_type");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('shipping_cost_type')
            ));
            $this->db->where('type', "shipping_cost");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('shipping_cost')
            ));
            $this->db->where('type', "shipment_info");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('shipment_info')
            ));

            $this->db->where('type', "shiprocket_email");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('shiprocket_email')
            ));

            $this->db->where('type', "shiprocket_password");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('shiprocket_pwd')
            ));

            $this->db->where('type', "cca_merchant_id");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('cca_merchantid')
            ));
            $this->db->where('type', "cca_accesscode");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('cca_accesscode')
            ));
            $this->db->where('type', "cca_workingkey");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('cca_workingkey')
            ));
            $this->db->where('type', "cca_account_type");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('cca_account_type')
            ));
        } else if ($para1 == 'set_currency') {
            $this->db->where('type', "currency");
            $this->db->update('business_settings', array(
                'value' => $para2
            ));
        } elseif ($para1 == 'currencies_select') {
            $currency = $this->db->get_where('business_settings', array('type' => "currency"))->row()->value;
            echo $this->crud_model->select_html('currency_settings', 'currency', 'name', 'edit', 'demo-chosen-select currency_o', $currency, 'status', 'ok');
        } else if ($para1 == 'set2') {
            $this->db->where('type', "currency");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('currency')
            ));
            $this->db->where('type', "currency_name");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('currency_name')
            ));
            $this->db->where('type', "exchange");
            $this->db->update('business_settings', array(
                'value' => $this->input->post('exchange')
            ));
            $this->db->where('type', "vendor_system");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('vendor_system')
            ));
            recache();
        } else if ($para1 == 'set_3') {
            $data['exchange_rate']    = $this->input->post('exchange');
            $this->db->where('currency_settings_id', $para2);
            $this->db->update('currency_settings', $data);
            $this->db->where('type', "vendor_system");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('vendor_system')
            ));
        } else if ($para1 == "bundle_product_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "bundle_product_activation");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "show_vendor_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "show_vendor_website");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "vendor_commission_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'yes';
            } else if ($para3 == 'false') {
                $val = 'no';
            }

            $this->db->where('type', "commission_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "vendor_package_set") {
            $val = '';
            if ($para3 == 'false') {
                $val = 'yes';
            } else if ($para3 == 'true') {
                $val = 'no';
            }

            $this->db->where('type', "commission_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "customer_product_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "customer_product_activation");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "guest_checkout_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }

            $this->db->where('type', "guest_checkout_set");
            $this->db->update('general_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "vendor_commission_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'yes';
            } else if ($para3 == 'false') {
                $val = 'no';
            }

            $this->db->where('type', "commission_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "cashback_system") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }

            $this->db->where('type', "cashback_system");
            $this->db->update('general_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "cashback_wallet_system") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }

            $this->db->where('type', "cashback_wallet_system");
            $this->db->update('general_settings', array(
                'value' => $val
            ));

            recache();
        } else if ($para1 == "pum_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }

            $this->db->where('type', "pum_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "razorpay_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }

            $this->db->where('type', "razorpay_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "iyzipay_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }

            $this->db->where('type', "iyzipay_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));
            recache();
        } else if ($para1 == "multi_store_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }

            $this->db->where('type', "multi_store_set");
            $this->db->update('business_settings', array(
                'value' => $val
            ));
            recache();
        } else {
            $page_data['page_name'] = "business_settings";
            $this->load->view('back/index', $page_data);
        }
    }

    /* Currency Format Settings */
    function set_currency_format()
    {
        if (!$this->crud_model->admin_permission('business_settings')) {
            redirect(base_url() . 'index.php/admin');
        }

        $this->db->where('type', 'currency_format');
        $this->db->update('business_settings', array(
            'value' => $this->input->post('currency_format')
        ));

        $this->db->where('type', 'symbol_format');
        $this->db->update('business_settings', array(
            'value' => $this->input->post('symbol_format')
        ));

        $this->db->where('type', 'no_of_decimals');
        $this->db->update('business_settings', array(
            'value' => $this->input->post('no_of_decimals')
        ));

        recache();
    }

    /* Manage Admin Settings */
    function manage_admin($para1 = "")
    {
        if ($this->session->userdata('admin_login') != 'yes') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'update_password') {
            $user_data['password'] = $this->input->post('password');
            $account_data          = $this->db->get_where('admin', array(
                'admin_id' => $this->session->userdata('admin_id')
            ))->result_array();
            foreach ($account_data as $row) {
                if (sha1($user_data['password']) == $row['password']) {
                    if ($this->input->post('password1') == $this->input->post('password2')) {
                        $data['password'] = sha1($this->input->post('password1'));
                        $this->db->where('admin_id', $this->session->userdata('admin_id'));
                        $this->db->update('admin', $data);
                        echo 'updated';
                    }
                } else {
                    echo 'pass_prb';
                }
            }
        } else if ($para1 == 'update_profile') {
            $this->db->where('admin_id', $this->session->userdata('admin_id'));
            $this->db->update('admin', array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone')
            ));
        } else {
            $page_data['page_name'] = "manage_admin";
            $page_data['view_rights']=$this->get_user_view_rights();
            $this->load->view('back/index', $page_data);
        }
    }

    /*Page Management */
    function page($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('page')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $parts             = array();
            $data['page_name'] = $this->input->post('page_name');
            $data['tag']        = $this->input->post('tag');
            $data['parmalink'] = $this->input->post('parmalink');
            $size              = $this->input->post('part_size');
            $type              = $this->input->post('part_content_type');
            $content           = $this->input->post('part_content');
            $widget            = $this->input->post('part_widget');
            //var_dump($widget);
            foreach ($size as $in => $row) {
                $parts[] = array(
                    'size' => $size[$in],
                    'type' => $type[$in],
                    'content' => $content[$in],
                    'widget' => $widget[$in]
                );
            }
            $data['parts']  = json_encode($parts);
            $data['status'] = '';
            $this->db->insert('page', $data);
            recache();
        } else if ($para1 == 'edit') {
            $page_data['page_data'] = $this->db->get_where('page', array(
                'page_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/page_edit', $page_data);
        } elseif ($para1 == "update") {
            $parts             = array();
            $data['page_name'] = $this->input->post('page_name');
            $data['tag']        = $this->input->post('tag');
            $data['parmalink'] = $this->input->post('parmalink');
            $size              = $this->input->post('part_size');
            $type              = $this->input->post('part_content_type');
            $content           = $this->input->post('part_content');
            $widget            = $this->input->post('part_widget');
            //var_dump($widget);
            foreach ($size as $in => $row) {
                $parts[] = array(
                    'size' => $size[$in],
                    'type' => $type[$in],
                    'content' => $content[$in],
                    'widget' => $widget[$in]
                );
            }
            $data['parts'] = json_encode($parts);
            $this->db->where('page_id', $para2);
            $this->db->update('page', $data);
            recache();
        } elseif ($para1 == 'delete') {
            $this->db->where('page_id', $para2);
            $this->db->delete('page');
            recache();
        } elseif ($para1 == 'list') {
            $page_data['all_page'] = $this->db->get('page')->result_array();
            $page_data['user_rights_27_0'] = $this->get_user_rights(27,0);
            $this->load->view('back/admin/page_list', $page_data);
        } else if ($para1 == 'page_publish_set') {
            $page = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('page_id', $page);
            $this->db->update('page', $data);
            recache();
        } elseif ($para1 == 'view') {
            $page_data['page_data'] = $this->db->get_where('page', array(
                'page_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/page_view', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/page_add');
        } else {
            $page_data['page_name'] = "page";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['user_rights_27_0'] = $this->get_user_rights(27,0);
            $page_data['all_pages'] = $this->db->get('page')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Manage General Settings */
    function general_settings($para1 = "", $para2 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == "terms") {
            $this->db->where('type', "terms_conditions");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('terms')
            ));
        }
        if ($para1 == "preloader") {
            $this->db->where('type', "preloader_bg");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('preloader_bg')
            ));
            $this->db->where('type', "preloader_obj");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('preloader_obj')
            ));
            $this->db->where('type', "preloader");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('preloader')
            ));
        }
        if ($para1 == "privacy_policy") {
            $this->db->where('type', "privacy_policy");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('privacy_policy')
            ));
        }
        if ($para1 == "set_slider") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            $this->db->where('type', "slider");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        if ($para1 == "set_slides") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            $this->db->where('type', "slides");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        if ($para1 == "set_admin_notification_sound") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            $this->db->where('type', "admin_notification_sound");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        if ($para1 == "g_analytics_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "g_analytics_set");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        if ($para1 == "set_home_notification_sound") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            $this->db->where('type', "home_notification_sound");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        if ($para1 == "set_power") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'on';
            } else if ($para2 == 'false') {
                $val = 'off';
            }
            $this->db->where('type', "power");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        if ($para1 == "fb_login_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "fb_login_set");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        if ($para1 == "g_login_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "g_login_set");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        if ($para1 == "kaleyra_sms_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "kaleyra_sms_set");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        if ($para1 == "twilio_sms_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "twilio_sms_set");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        if ($para1 == "set") {
            //  exit;
            $this->db->where('type', "system_name");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('system_name')
            ));
            $this->db->where('type', "system_email");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('system_email')
            ));

            $file_folder = $this->db->get_where('general_settings', array('type' => 'file_folder'))->row()->value;
            if (rename("uploads/file_products/" . $file_folder, "uploads/file_products/" . $this->input->post('file_folder'))) {
                $this->db->where('type', "file_folder");
                $this->db->update('general_settings', array(
                    'value' => $this->input->post('file_folder')
                ));
            }

            $this->db->where('type', "system_title");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('system_title')
            ));
            $this->db->where('type', "year");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('year')
            ));
            $this->db->where('type', "cache_time");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('cache_time')
            ));
            $this->db->where('type', "language");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('language')
            ));
            $volume = $this->input->post('admin_notification_volume');
            $this->db->where('type', "admin_notification_volume");
            $this->db->update('general_settings', array(
                'value' => $volume
            ));
            $volume = $this->input->post('homepage_notification_volume');
            $this->db->where('type', "homepage_notification_volume");
            $this->db->update('general_settings', array(
                'value' => $volume
            ));
        }
        if ($para1 == "contact") {
            $this->db->where('type', "contact_address");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('contact_address')
            ));
            $this->db->where('type', "contact_email");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('contact_email')
            ));
            $this->db->where('type', "contact_phone");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('contact_phone')
            ));
            $this->db->where('type', "contact_website");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('contact_website')
            ));
            $this->db->where('type', "contact_about");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('contact_about')
            ));
        }
        if ($para1 == "footer") {
            $this->db->where('type', "footer_text");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('footer_text', 'chaira_de')
            ));
            $this->db->where('type', "footer_category");
            $this->db->update('general_settings', array(
                'value' => json_encode($this->input->post('footer_category'))
            ));
        }
        if ($para1 == "font") {
            $this->db->where('type', "font");
            $this->db->update('ui_settings', array(
                'value' => $this->input->post('font')
            ));
        }
        if ($para1 == "color") {
            $this->db->where('type', "header_color");
            $this->db->update('ui_settings', array(
                'value' => $this->input->post('header_color')
            ));
            $this->db->where('type', "footer_color");
            $this->db->update('ui_settings', array(
                'value' => $this->input->post('header_color')
            ));
        }
        if ($para1 == "mail_status") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'smtp';
            } else if ($para2 == 'false') {
                $val = 'mail';
            }
            echo $val;
            $this->db->where('type', "mail_status");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        if ($para1 == "captcha_status") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "captcha_status");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }

        if ($para1 == "facebook_pixel_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "facebook_pixel_set");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        if ($para1 == "facebook_chat_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('type', "facebook_chat_set");
            $this->db->update('general_settings', array(
                'value' => $val
            ));
        }
        recache();
    }

    function customer_report($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('report')) {
            redirect(base_url() . 'index.php/admin');
        }

        if ($para1 == 'list') {
            $all = $this->db->get_where('sale', array('payment_type' => 'go'))->result_array();
            foreach ($all as $row) {
                if ((time() - $row['sale_datetime']) > 600) {
                    $this->db->where('sale_id', $row['sale_id']);
                    $this->db->delete('sale');
                }
            }
            $where = '(status="success" or status = "admin_pending")';
            $this->db->where($where);
            $this->db->order_by('sale_id', 'desc');
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            //  echo $this->db->last_query(); exit;
            $this->load->view('back/admin/customer_report_list', $page_data);
        } else {
            $page_data['page_name']      = "customer_report";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function smtp_settings($para1 = "", $para2 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == "set") {
            $this->db->where('type', 'smtp_host');
            $this->db->update('general_settings', array('value' => $this->input->post('smtp_host')));

            $this->db->where('type', 'smtp_port');
            $this->db->update('general_settings', array('value' => $this->input->post('smtp_port')));

            $this->db->where('type', 'smtp_user');
            $this->db->update('general_settings', array('value' => $this->input->post('smtp_user')));

            $this->db->where('type', 'smtp_pass');
            $this->db->update('general_settings', array('value' => $this->input->post('smtp_pass')));

            redirect(base_url() . 'index.php/admin/site_settings/smtp_settings/', 'refresh');
        }
    }
    /* Manage Social Links */
    function social_links($para1 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == "set") {
            $this->db->where('type', "facebook");
            $this->db->update('social_links', array(
                'value' => $this->input->post('facebook')
            ));
            $this->db->where('type', "instagram");
            $this->db->update('social_links', array(
                'value' => $this->input->post('instagram')
            ));
            $this->db->where('type', "google-plus");
            $this->db->update('social_links', array(
                'value' => $this->input->post('google-plus')
            ));
            $this->db->where('type', "twitter");
            $this->db->update('social_links', array(
                'value' => $this->input->post('twitter')
            ));
            $this->db->where('type', "skype");
            $this->db->update('social_links', array(
                'value' => $this->input->post('skype')
            ));
            $this->db->where('type', "pinterest");
            $this->db->update('social_links', array(
                'value' => $this->input->post('pinterest')
            ));
            $this->db->where('type', "youtube");
            $this->db->update('social_links', array(
                'value' => $this->input->post('youtube')
            ));
            redirect(base_url() . 'index.php/admin/site_settings/social_links/', 'refresh');
        }
        recache();
    }
    /* Manage SEO relateds */
    function seo_settings($para1 = "")
    {
        if (!$this->crud_model->admin_permission('seo')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == "set") {
            $this->db->where('type', "meta_description");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('description')
            ));
            $this->db->where('type', "meta_keywords");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('keywords')
            ));
            $this->db->where('type', "meta_author");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('author')
            ));

            $this->db->where('type', "revisit_after");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('revisit_after')
            ));
            recache();
        } elseif($para1 == "seo_product_settings") {
            $page_data['page_name'] = "seo_product_settings";
            $page_data['products'] = $this->db->get('product')->result_array();
            $product_seo=[];
            $product_seo0 = $this->db->get('product_seo')->result_array();
            foreach($product_seo0 as $product_seo1){
                $product_seo[$product_seo1['product_id']]=$product_seo1;
            }
            $page_data['product_seo'] = $product_seo;
            $this->load->view('back/index', $page_data);
        } elseif($para1 == "seo_page_settings"){

            $page_data['page_name'] = "seo_page_settings";
            $page_data['page'] = $this->db->get('page')->result_array();
            $page_seo=[];
            $page_seo0 = $this->db->get('page_seo')->result_array();
            foreach($page_seo0 as $page_seo1){
                $page_seo[$page_seo1['page_id']]=$page_seo1;
            }
            $page_data['page_seo'] = $page_seo;
            
            $this->load->view('back/index', $page_data);

        }elseif($para1 == "save_seo_page"){

            $seo_valu=$this->input->post('seo_valu');
            for($i1=0;$i1<count($seo_valu);$i1++){
                $seo_valu1=$seo_valu[$i1];$data1=[];
                $page_id1=explode("_",$seo_valu1['page_id']);
                $data1['keywords'] = str_replace(" ","-",$seo_valu1['keywords']);
                $data1['description'] = str_replace(" ","-",$seo_valu1['description']);
                $data1['active_status'] = $page_id1[1];
                $id1 = $this->db->get_where('page_seo', array('page_id' => $page_id1[0]))->row()->page_id;
                if($id1==""){
                    $data1['page_id'] = $page_id1[0];
                    $this->db->insert('page_seo', $data1);
                }else{
                    $this->db->where('page_id', $page_id1[0]);
                    $this->db->update('page_seo', $data1);
                }
            }

        } elseif($para1 == "save_seo_product") {
            $seo_valu=$this->input->post('seo_valu');
            for($i1=0;$i1<count($seo_valu);$i1++){
                $seo_valu1=$seo_valu[$i1];$data1=[];
                $product_id1=explode("_",$seo_valu1['product_id']);
                $data1['keywords'] = str_replace(" ","-",$seo_valu1['keywords']);
                $data1['description'] = str_replace(" ","-",$seo_valu1['description']);
                $data1['active_status'] = $product_id1[1];
                $id1 = $this->db->get_where('product_seo', array('product_id' => $product_id1[0]))->row()->product_id;
                if($id1==""){
                    $data1['product_id'] = $product_id1[0];
                    $this->db->insert('product_seo', $data1);
                }else{
                    $this->db->where('product_id', $product_id1[0]);
                    $this->db->update('product_seo', $data1);
                }
            }
        } else {
            require_once(APPPATH . 'libraries/SEOstats/bootstrap.php');
            $page_data['page_name'] = "seo";
            $page_data['view_rights']=$this->get_user_view_rights();
            $this->load->view('back/index', $page_data);
        }
    }

    function ticket($para1 = "", $para2 = "", $para3 = "")
    {
        if (!$this->crud_model->admin_permission('ticket')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'delete') {
            $this->db->where('ticket_id', $para2);
            $this->db->delete('ticket');
        } elseif ($para1 == 'list') {
            $this->db->order_by('ticket_id', 'desc');
            $page_data['tickets'] = $this->db->get('ticket')->result_array();
            $this->load->view('back/admin/ticket_list', $page_data);
        } elseif ($para1 == 'reply') {
            $data['message'] = $this->input->post('reply');
            $data['time'] = time();
            $data['from_where'] = json_encode(array('type' => 'admin', 'id' => ''));
            $data['to_where'] = $this->db->get_where('ticket_message', array('ticket_id' => $para2))->row()->from_where;
            $data['ticket_id'] = $para2;
            $data['view_status'] = json_encode(array('user_show' => 'no', 'admin_show' => 'ok'));
            $data['subject']  = $this->db->get_where('ticket_message', array('ticket_id' => $para2))->row()->subject;
            $this->db->insert('ticket_message', $data);
        } elseif ($para1 == 'view') {
            $page_data['message_data'] = $this->db->get_where('ticket', array(
                'ticket_id' => $para2
            ))->result_array();
            $this->crud_model->ticket_message_viewed($para2, 'admin');
            $page_data['tic'] = $para2;
            $this->load->view('back/admin/ticket_view', $page_data);
        } else if ($para1 == 'view_user') {
            $page_data['user_data'] = $this->db->get_where('user', array(
                'user_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/user_view', $page_data);
        } elseif ($para1 == 'reply_form') {
            $page_data['message_data'] = $this->db->get_where('ticket', array(
                'ticket_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/ticket_reply', $page_data);
        } else {
            $page_data['page_name']        = "ticket";
            $page_data['tickets'] = $this->db->get('ticket')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function display_settings($para1 = "")
    {
        if (!$this->crud_model->admin_permission('display_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $page_data['page_name'] = "display_settings";
        $page_data['view_rights']=$this->get_user_view_rights();
        $page_data['tab_name']  = $para1;
        $this->load->view('back/index', $page_data);
    }
    function preloader_view($para1 = "")
    {
        if (!$this->crud_model->admin_permission('display_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $page_data['from_admin'] = true;
        $page_data['preloader']  = $para1;
        $this->load->view('front/preloader', $page_data);
    }
    function captha_n_social_settings($para1 = "")
    {
        if (!$this->crud_model->admin_permission('captha_n_social_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $page_data['page_name'] = "captha_n_social_settings";
        $page_data['view_rights']=$this->get_user_view_rights();
        $page_data['tab_name']  = $para1;
        $this->load->view('back/index', $page_data);
    }
    function google_api_key($para1 = "")
    {
        if (!$this->crud_model->admin_permission('captha_n_social_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        $this->db->where('type', "google_api_key");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('api_key')
        ));
        recache();
    }
    function currency_settings($para1 = "", $para2 = "")
    {
        if (!$this->crud_model->admin_permission('business_settings')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'set_rate') {
            if ($this->input->post('exchange')) {
                echo $data['exchange_rate']            = $this->input->post('exchange');
            }
            if ($this->input->post('exchange_def')) {
                echo $data['exchange_rate_def']        = $this->input->post('exchange_def');
            }
            if ($this->input->post('name')) {
                echo $data['name']        = $this->input->post('name');
            }
            if ($this->input->post('symbol')) {
                echo $data['symbol']        = $this->input->post('symbol');
            }
            $this->db->where('currency_settings_id', $para2);
            $this->db->update('currency_settings', $data);
            recache();
        }
    }
    function default_images($para1 = "", $para2 = "")
    {
        if (!$this->crud_model->admin_permission('default_images')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == "set_images") {
            move_uploaded_file($_FILES[$para2]['tmp_name'], 'uploads/' . $para2 . '/default.jpg');
            recache();
        }
        $page_data['default_list'] = array('product_image', 'digital_logo_image', 'category_image', 'sub_category_image', 'brand_image', 'blog_image', 'banner_image', 'user_image', 'vendor_logo_image', 'vendor_banner_image', 'membership_image', 'slides_image');
        $page_data['page_name'] = "default_images";
        $this->load->view('back/index', $page_data);
    }
    function theme_part()
    {
        $this->load->view('back/admin/theme_part');
    }
    function logo_part()
    {
        $this->load->view('back/admin/logo_part');
    }
    function preloader_part()
    {

        $this->load->view('back/admin/preloader_settings');
    }
    function font_part()
    {

        $this->load->view('back/admin/font');
    }
    function favicon_part()
    {

        $this->load->view('back/admin/favicon');
    }
    function home_part()
    {
        $this->load->view('back/admin/home_settings');
    }
    function contact_part()
    {
        $this->load->view('back/admin/contact_set');
    }
    function footer_part()
    {
        $this->load->view('back/admin/footer_set');
    }
    function home_item_change($para1 = "")
    {
        $this->load->view('back/admin/home_change_' . $para1);
    }

    function min_bidd($para1 = '')
    {
        if (!$this->crud_model->admin_permission('product')) {
            redirect(base_url() . 'index.php/admin');
        }
        //echo $para1; 
        $stage1 = 0;
        $page_data['bidd'] = $this->db->get_where('bidding_history', array('id' => $para1))->result_array();
        //echo '<pre>'; print_r($page_data['bidd']);
        $uid = $page_data['bidd'][0]['uid'];
        $pid = $page_data['bidd'][0]['pid'];

        $page_data['bidd_deatils'] = $this->db->get_where('bidding_history', array('id !=' => $para1, 'payment_status' => 1, 'status' => 1, 'final_bidder' => 0, 'batch_no' => 0, 'pid' => $pid))->result_array();



        for ($i = 0; $i < count($page_data['bidd_deatils']); $i++) {
            $p_uid = $page_data['bidd_deatils'][$i]['uid'];

            $p_amt = $page_data['bidd_deatils'][$i]['bid_amt'];

            $ussr['old_amt'] = $this->db->get_where('user', array('user_id' => $p_uid))->result_array();

            $old_amt = $ussr['old_amt'][0]['wallet'];

            $new_user_balance = $old_amt + $p_amt;


            $data3['wallet'] = $new_user_balance;
            $this->db->where('user_id', $p_uid);
            $this->db->update('user', $data3);

            $data4['uid'] =  $p_uid;
            $data4['description'] = 'Bidd Amount ' . $p_amt . ' Refunded Your Wallet Sucessfully';
            $this->db->insert('user_log', $data4);
        }


        $data['final_bidder'] = 1;
        $this->db->where('id', $para1);
        $this->db->where('pid', $pid);
        $this->db->where('payment_status', 1);
        $this->db->where('status', 1);
        $this->db->where('final_bidder', 0);
        $this->db->where('batch_no', 0);
        $this->db->update('bidding_history', $data);

        $data1['final_bidder'] = 2;
        $this->db->where('pid', $pid);
        $this->db->where('id !=', $para1);
        $this->db->where('payment_status', 1);
        $this->db->where('status', 1);
        $this->db->where('final_bidder', 0);
        $this->db->where('batch_no', 0);
        $this->db->update('bidding_history', $data1);


        $this->db->select_max('batch_no');
        $biid = $this->db->get('bidding_history')->row();
        $new_bidd = $biid->batch_no + 1;
        $data2['batch_no'] = $new_bidd;
        $this->db->where('pid', $pid);
        $this->db->where('payment_status', 1);
        $this->db->where('status', 1);
        $this->db->where('batch_no', 0);
        $this->db->update('bidding_history', $data2);



        $category_id = $this->db->get_where('product', array('product_id' => $pid))->result_array();
        $cat_id = $category_id[0]['category'];
        $sub_id = $category_id[0]['sub_category'];

        $data5['product'] =  $pid;
        $data5['type'] =  'destroy';
        $data5['category'] =  $cat_id;
        $data5['sub_category'] =  $sub_id;
        $data5['quantity'] =  1;
        $data5['rate'] =  $p_amt;
        $data5['total'] =  $p_amt;
        $added_by = array('type' => 'admin', 'id' => '1');
        $data5['added_by'] =  json_encode($added_by, true);

        //echo '<pre>'; print_r($data5); 
        $this->db->insert('stock', $data5);
        $currenct_stock = $this->db->get_where('product', array('product_id' => $pid))->result_array();


        $new_stock =    $currenct_stock[0]['current_stock'] - 1;
        $da['current_stock'] = $new_stock;
        $this->db->where('product_id', $pid);
        $this->db->update('product', $da);
        //exit;
        echo 'Sucess';
    }

    /* VB Orders */
    function orders($para1 = '', $para2 = '')
    {
        $page_data['page_name']      = "orders";        
        $page_data['view_rights']=$this->get_user_view_rights();
        $page_data['user_rights_4_7'] = $this->get_user_rights(4,7);
        $page_data['user_rights_4_8'] = $this->get_user_rights(4,8);
        $page_data['user_rights_4_9'] = $this->get_user_rights(4,9);
        $this->load->view('back/index', $page_data);
    }

    function cancel_sales($para1 = '', $para2 = '')
    {


        if ($para1 == 'list') {
            $all = $this->db->get_where('sale', array('payment_type' => 'go'))->result_array();
            foreach ($all as $row) {
                if ((time() - $row['sale_datetime']) > 600) {
                    $this->db->where('sale_id', $row['sale_id']);
                    $this->db->delete('sale');
                }
            }
            $this->db->order_by('sale_id', 'desc');
            //user cancel
            //$this->db->where('cancel_status', '1');
            //admin reject
            $this->db->where('status', 'rejected');
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            $this->load->view('back/admin/cancel_sales_list', $page_data);
        } elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/sales_view', $page_data);
        } else {
            $page_data['page_name']      = "cancel_sales";
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function user_cancel_sales($para1 = '', $para2 = '')
    {


        if ($para1 == 'list') {
            $all = $this->db->get_where('sale', array('payment_type' => 'go'))->result_array();
            foreach ($all as $row) {
                if ((time() - $row['sale_datetime']) > 600) {
                    $this->db->where('sale_id', $row['sale_id']);
                    $this->db->delete('sale');
                }
            }
            $this->db->order_by('sale_id', 'desc');
            //user cancel
            //$this->db->where('cancel_status', '1');
            //admin reject
            $this->db->where('status', 'cancelled');
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            $this->load->view('back/admin/user_cancel_sales_list', $page_data);
        } elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/sales_view', $page_data);
        } else {
            $page_data['page_name']      = "user_cancel_sales";
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    function successful_sales($para1 = '', $para2 = '')
    {


        if ($para1 == 'list') {
            $all = $this->db->get_where('sale', array('payment_type' => 'go'))->result_array();
            foreach ($all as $row) {
                if ((time() - $row['sale_datetime']) > 600) {
                    $this->db->where('sale_id', $row['sale_id']);
                    $this->db->delete('sale');
                }
            }
            $delivery_status = '"status":"delivered"';

            $this->db->order_by('sale_id', 'desc');
            $this->db->like('delivery_status', $delivery_status);
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            $this->load->view('back/admin/successful_sales_list', $page_data);
        } elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/sales_view', $page_data);
        } else {
            $page_data['page_name']      = "successful_sales";
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    function failed_sales($para1 = '', $para2 = '')
    {


        if ($para1 == 'list') {
            $all = $this->db->get_where('sale', array('payment_type' => 'go'))->result_array();
            foreach ($all as $row) {
                if ((time() - $row['sale_datetime']) > 600) {
                    $this->db->where('sale_id', $row['sale_id']);
                    $this->db->delete('sale');
                }
            }
            $this->db->order_by('sale_id', 'desc');
            $this->db->where('status', 'failed');
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            $this->load->view('back/admin/fail_sales_list', $page_data);
        } elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/sales_view', $page_data);
        } else {
            $page_data['page_name']      = "failed_sales";
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function return_sales($para1 = '', $para2 = '', $para3 = '')
    {


        if ($para1 == 'list') {
            $all = $this->db->get_where('sale', array('payment_type' => 'go'))->result_array();
            foreach ($all as $row) {
                if ((time() - $row['sale_datetime']) > 600) {
                    $this->db->where('sale_id', $row['sale_id']);
                    $this->db->delete('sale');
                }
            }
            $this->db->order_by('sale_id', 'desc');
            $this->db->where('return_status', '1');
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            $this->load->view('back/admin/return_sales_list', $page_data);
        } elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/sales_view', $page_data);
        } else if ($para1 == 'accept') {
            $sale_id = $para2;
            $exchange_method = $para3;
            $data1['return_status'] = 2;
            $this->db->where('sale_id', $sale_id);
            $this->db->update('sale', $data1);
            if ($exchange_method == 2) {
            } elseif ($exchange_method == 1) {

                $order_history = $this->db->get_where('sale', array('sale_id' => $sale_id))->result_array();

                $order_history = $order_history[0];
                $data['exchange_id'] = 'A-EX' . substr(time(), 4) . rand(1000, 100000) . rand(11111, 999999);
                $data['order_id'] = 'OD' . substr(time(), 4) . rand(1, 10) . rand(1, 99);
                $data['order_type'] = 'shopping';
                $data['product_details'] = $order_history['product_details'];
                $data['shipping_address'] = $order_history['shipping_address'];
                $data['buyer'] = $order_history['buyer'];
                if ($order_history['buyer'] == "guest") {
                    $data['guest_id'] = $order_history['guest_id'];
                }
                $data['vat'] = $order_history['vat'];
                $data['shipping'] = $order_history['shipping'];
                $data['grand_total'] = $order_history['grand_total'];
                $data['sale_datetime']     = time();
                $data['payment_type'] = 'Paid';
                $data['status'] = 'success';
                $delivery_status = array();
                $payment_status = array();
                $delivery_status[] = array('admin' => '', 'status' => 'pending', 'delivery_time' => '');
                $payment_status[] = array('admin' => '', 'status' => 'paid');
                $data['delivery_status'] = json_encode($delivery_status);
                $data['payment_status'] = json_encode($payment_status);
                $data['group_deal'] = 1;
                //echo "<pre>"; print_r($data); exit;
                $this->db->insert('sale', $data);
                $new_sale_id = $this->db->insert_id();
                $data2['sale_code'] = 'AD-' . date('Ym', $data['sale_datetime']) . $new_sale_id;
                $this->db->where('sale_id', $new_sale_id);
                $this->db->update('sale', $data2);
                $this->crud_model->digital_to_customer($new_sale_id);
                $this->crud_model->email_invoice($new_sale_id);
                redirect(base_url() . 'index.php/admin/return_sales');
            }
        } else if ($para1 == 'decline') {
            $sale_id = $para2;
            $data['return_status'] = 3;
            $this->db->where('sale_id', $sale_id);
            $this->db->update('sale', $data);
            redirect(base_url() . 'index.php/admin/return_sales');
        } else {
            $page_data['page_name']      = "return_sales";
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function product_bundle($para1 = '', $para2 = '', $para3 = '', $para4 = '')
    {
        if (!$this->crud_model->admin_permission('product_bundle')) {
            redirect(base_url() . 'admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'do_add') {
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
            $products = array();
            $data['num_of_imgs']        = $num_of_imgs;
            $data['title']              = $this->input->post('title');
            $data['description']        = $this->input->post('description');
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
            $data['shipping_cost']      = $this->input->post('shipping_cost');
            $data['is_bundle']          = 'yes';
            $data['tag']                = $this->input->post('tag');
            $data['current_stock']      = '1';
            $data['unit']               = $this->input->post('unit');
            $product_no                 = $this->input->post('product_no');
            $product_id                 = $this->input->post('product');
            $product_quantity           = $this->input->post('quantity');
            $data['added_by']           = json_encode(array('type' => 'admin', 'id' => $this->session->userdata('admin_id')));
            if (count($product_id) > 0) {
                foreach ($product_id as $i => $row) {
                    $products[]              =   array(
                        'product_no' => $product_no[$i],
                        'product_id' => $product_id[$i],
                        'quantity' => $product_quantity[$i],
                    );
                }
            }
            $data['products']            = json_encode($products);
            $this->db->insert('product', $data);
            $id = $this->db->insert_id();
            $this->benchmark->mark_time();

            $this->crud_model->file_up("images", "product", $id, 'multi');

            recache();
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/product_bundle_add');
        } else if ($para1 == 'edit') {
            $page_data['product_bundle_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/product_bundle_edit', $page_data);
        } elseif ($para1 == 'update') {
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
            $num                        = $this->crud_model->get_type_name_by_id('product', $para2, 'num_of_imgs');
            $products = array();
            $data['num_of_imgs']        = $num + $num_of_imgs;
            $data['title']              = $this->input->post('title');
            $data['description']        = $this->input->post('description');
            $data['sale_price']         = $this->input->post('sale_price');
            $data['purchase_price']     = $this->input->post('purchase_price');
            $data['update_time']        = time();
            $data['tax']                = $this->input->post('tax');
            $data['discount']           = $this->input->post('discount');
            $data['discount_type']      = $this->input->post('discount_type');
            $data['tax_type']           = $this->input->post('tax_type');
            $data['shipping_cost']      = $this->input->post('shipping_cost');
            $data['tag']                = $this->input->post('tag');
            $data['unit']               = $this->input->post('unit');
            $product_no                 = $this->input->post('product_no');
            $product_id                 = $this->input->post('product');
            $product_quantity           = $this->input->post('quantity');
            $data['added_by']           = json_encode(array('type' => 'admin', 'id' => $this->session->userdata('admin_id')));
            if (count($product_id) > 0) {
                foreach ($product_id as $i => $row) {
                    $products[]              =   array(
                        'product_no' => $product_no[$i],
                        'product_id' => $product_id[$i],
                        'quantity' => $product_quantity[$i],
                    );
                }
            }
            $data['products']            = json_encode($products);

            $this->crud_model->file_up("images", "product", $para2, 'multi');


            $this->db->where('product_id', $para2);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'delete') {

            $this->crud_model->file_dlt('product', $para2, '.jpg', 'multi');
            $this->db->where('product_id', $para2);
            $this->db->delete('product');
            recache();
        } else if ($para1 == 'view') {
            $page_data['product_bundle_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/product_bundle_view', $page_data);
        } else if ($para1 == 'do_destroy') {
        } elseif ($para1 == 'list') {
            $this->db->order_by('product_id', 'desc');
            $page_data['all_product_bundle'] = $this->db->get_where('product', array('is_bundle' => 'yes'))->result_array();
            $this->load->view('back/admin/product_bundle_list', $page_data);
        } elseif ($para1 == 'list_data') {
            //echo "1"; exit;
            $limit      = $this->input->get('limit');
            $search     = $this->input->get('search');
            $order      = $this->input->get('order');
            $offset     = $this->input->get('offset');
            $sort       = $this->input->get('sort');
            if ($search) {
                $this->db->like('title', $search, 'both');
            }
            $this->db->where('is_bundle', 'yes');
            $total = $this->db->get('product')->num_rows();
            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'product_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            if ($search) {
                $this->db->like('title', $search, 'both');
            }
            $product_bundles   = $this->db->get_where('product', array('is_bundle' => 'yes'), $limit, $offset)->result_array();
            $data       = array();
            foreach ($product_bundles as $row) {

                $res    = array(
                    'image' => '',
                    'title' => '',
                    'publish' => '',
                    'featured' => '',
                    'options' => ''
                );

                $res['image']  = '<img class="img-sm" style="height:auto !important; border:1px solid #ddd;padding:2px; border-radius:2px !important;" src="' . $this->crud_model->file_view('product', $row['product_id'], '', '', 'thumb', 'src', 'multi', 'one') . '"  />';
                $res['title']  = $row['title'];

                if ($row['status'] == 'ok') {
                    $res['publish']  = '<input id="pub_' . $row['product_id'] . '" class="sw1" type="checkbox" data-id="' . $row['product_id'] . '" checked />';
                } else {
                    $res['publish']  = '<input id="pub_' . $row['product_id'] . '" class="sw1" type="checkbox" data-id="' . $row['product_id'] . '" />';
                }
                if ($row['current_stock'] > 0) {
                    $res['current_stock']  = $row['current_stock'] . $row['unit'] . '(s)';
                } else {
                    $res['current_stock']  = '<span class="label label-danger">' . translate('out_of_stock') . '</span>';
                }
                if ($row['featured'] == 'ok') {
                    $res['featured'] = '<input id="fet_' . $row['product_id'] . '" class="sw2" type="checkbox" data-id="' . $row['product_id'] . '" checked />';
                } else {
                    $res['featured'] = '<input id="fet_' . $row['product_id'] . '" class="sw2" type="checkbox" data-id="' . $row['product_id'] . '" />';
                }
                if ($row['deal'] == 'ok') {
                    $res['deal'] = '<input id="del_' . $row['product_id'] . '" class="sw3" type="checkbox" data-id="' . $row['product_id'] . '" checked />';
                } else {
                    $res['deal'] = '<input id="del_' . $row['product_id'] . '" class="sw3" type="checkbox" data-id="' . $row['product_id'] . '" />';
                }
                //add html for action

                $res['options'] = "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\"
                                onclick=\"ajax_set_full('view','" . translate('view_product_bundle') . "','" . translate('successfully_viewed!') . "','product_bundle_view','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                    " . translate('view') . "
                            </a>
                            <a class=\"btn btn-purple btn-xs btn-labeled fa fa-tag\" data-toggle=\"tooltip\"
                                onclick=\"ajax_modal('add_discount','" . translate('view_discount') . "','" . translate('viewing_discount!') . "','add_bundle_discount','" . $row['product_id'] . "')\" data-original-title=\"Edit\" data-container=\"body\">
                                    " . translate('discount') . "
                            </a>
                            <a class=\"btn btn-mint btn-xs btn-labeled fa fa-plus-square\" data-toggle=\"tooltip\"
                                onclick=\"ajax_modal('add_stock','" . translate('add_bundle_quantity') . "','" . translate('quantity_added!') . "','bundle_stock_add','" . $row['product_id'] . "')\" data-original-title=\"Edit\" data-container=\"body\">
                                    " . translate('stock') . "
                            </a>
                            <a class=\"btn btn-dark btn-xs btn-labeled fa fa-minus-square\" data-toggle=\"tooltip\"
                                onclick=\"ajax_modal('destroy_stock','" . translate('reduce_bundle_quantity') . "','" . translate('quantity_reduced!') . "','destroy_bundle_stock','" . $row['product_id'] . "')\" data-original-title=\"Edit\" data-container=\"body\">
                                    " . translate('destroy') . "
                            </a>

                            <a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\"
                                onclick=\"ajax_set_full('edit','" . translate('edit_product_bundle') . "','" . translate('successfully_edited!') . "','product_bundle_edit','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                                    " . translate('edit') . "
                            </a>

                            <a onclick=\"delete_confirm('" . $row['product_id'] . "','" . translate('really_want_to_delete_this?') . "')\"
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    " . translate('delete') . "
                            </a>";

                $data[] = $res;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );

            echo json_encode($result);
        } elseif ($para1 == 'add_discount') {
            $data['product_bundle'] = $para2;
            $this->load->view('back/admin/product_bundle_add_discount', $data);
        } elseif ($para1 == 'add_discount_set') {
            $product_bundle               = $this->input->post('product_bundle');
            $data['discount']      = $this->input->post('discount');
            $data['discount_type'] = $this->input->post('discount_type');
            $this->db->where('product_id', $product_bundle);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'add_stock') {
            $data['product_bundle'] = $para2;
            $this->load->view('back/admin/product_bundle_stock_add', $data);
        } elseif ($para1 == 'destroy_stock') {
            $data['product_bundle'] = $para2;
            $this->load->view('back/admin/product_bundle_stock_destroy', $data);
        } elseif ($para1 == 'bundle_publish_set') {
            $product_bundle = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('product_id', $product_bundle);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'bundle_featured_set') {
            $product_bundle = $para2;
            if ($para3 == 'true') {
                $data['featured'] = 'ok';
            } else {
                $data['featured'] = '0';
            }
            $this->db->where('product_id', $product_bundle);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'bundle_deal_set') {
            $product_bundle = $para2;
            if ($para3 == 'true') {
                $data['deal'] = 'ok';
            } else {
                $data['deal'] = '0';
            }
            $this->db->where('product_id', $product_bundle);
            $this->db->update('product', $data);
            recache();
        } else if ($para1 == 'dlt_img') {

            $a = explode('_', $para2);
            $this->crud_model->file_dlt('product', $a[0], '.jpg', 'multi', $a[1]);
            recache();
        } elseif ($para1 == 'sub_by_cat') {
            echo $this->crud_model->select_html('sub_category', 'sub_category[]', 'sub_category_name', 'add', 'demo-chosen-select required', '', 'category', $para2, 'get_brnd');
        } elseif ($para1 == 'brand_by_sub') {
            $brands = json_decode($this->crud_model->get_type_name_by_id('sub_category', $para2, 'brand'), true);
            /*if(empty($brands)){
                echo translate("<p class='control-label'>No brands are available for this sub category</p>");
            } else {*/
            echo $this->crud_model->select_html('brand', 'brand[]', 'name', 'add', 'demo-chosen-select required', '', 'brand_id', $brands, 'get_prod', 'multi', 'none');
            // }
        } elseif ($para1 == 'prod_by_brand') {
            if ($para2 == 'none') {
                $prod_ids = array();
                $products = $this->db->get_where('product', array('sub_category' => $para3, 'category' => $para4))->result_array();
                foreach ($products as $product) {
                    $prod_ids[] = $product['product_id'];
                }
                if (empty($prod_ids)) {
                    echo translate("<p class='control-label'>No Products are available for this brand</p>");
                } else {
                    echo $this->crud_model->select_html('product', 'product[]', 'title', 'add', 'demo-chosen-select required', '', 'product_id', $prod_ids, '', 'multi');
                }
            } else {
                $prod_ids = array();
                $products = $this->db->get_where('product', array('brand' => $para2, 'sub_category' => $para3, 'category' => $para4))->result_array();
                foreach ($products as $product) {
                    $prod_ids[] = $product['product_id'];
                }
                if (empty($prod_ids)) {
                    echo translate("<p class='control-label'>No Products are available for this brand</p>");
                } else {
                    echo $this->crud_model->select_html('product', 'product[]', 'title', 'add', 'demo-chosen-select required', '', 'product_id', $prod_ids, '', 'multi');
                }
            }
        } else {
            $page_data['page_name'] = "product_bundle";

            $this->load->view('back/index', $page_data);
        }
    }
    function customer_products($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('product')) {
            redirect(base_url() . 'admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'view') {
            $page_data['product_data'] = $this->db->get_where('customer_product', array(
                'customer_product_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/customer_product_view', $page_data);
        } elseif ($para1 == 'delete') {

            $this->crud_model->file_dlt('customer_product', $para2, '.jpg', 'multi');
            $this->db->where('customer_product_id', $para2);
            $this->db->delete('customer_product');
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('customer_product_id', 'desc');
            $page_data['all_product'] = $this->db->get('customer_product')->result_array();
            $this->load->view('back/admin/customer_product_list', $page_data);
        } elseif ($para1 == 'list_data') {
            $limit      = $this->input->get('limit');
            $search     = $this->input->get('search');
            $order      = $this->input->get('order');
            $offset     = $this->input->get('offset');
            $sort       = $this->input->get('sort');
            if ($search) {
                $this->db->like('title', $search, 'both');
            }
            $total      = $this->db->get('customer_product')->num_rows();
            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'customer_product_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            if ($search) {
                $this->db->like('title', $search, 'both');
            }
            $products   = $this->db->get('customer_product', $limit, $offset)->result_array();
            $data       = array();
            foreach ($products as $row) {

                $res    = array(
                    'image'        => '',
                    'title'        => '',
                    'uploaded_by'  => '',
                    'customer_status'       => '',
                    'publish'      => '',
                    'options'      => ''
                );

                $res['image']  = '<img class="img-sm" style="height:auto !important; border:1px solid #ddd;padding:2px; border-radius:2px !important;" src="' . $this->crud_model->file_view('customer_product', $row['customer_product_id'], '', '', 'thumb', 'src', 'multi', 'one') . '"  />';
                $res['title']  = '<a target="_blank" href="' . $this->crud_model->customer_product_link($row['customer_product_id']) . '">
                ' . $row['title'] . '
            </a>';
                $res['uploaded_by']  = $this->db->get_where('user', array('user_id' => $row['added_by']))->row()->username . ' ' . $this->db->get_where('user', array('user_id' => $row['added_by']))->row()->surname;

                if ($row['admin_status'] == 'ok') {
                    $res['publish']  = '<input id="pub_' . $row['customer_product_id'] . '" class="sw1" type="checkbox" data-id="' . $row['customer_product_id'] . '" checked />';
                } else {
                    $res['publish']  = '<input id="pub_' . $row['customer_product_id'] . '" class="sw1" type="checkbox" data-id="' . $row['customer_product_id'] . '" />';
                }

                if ($row['status'] == 'ok') {
                    $res['customer_status']  = ' <label class="label label-success publish_btn">' . translate('Published') . '</label>';
                } else {
                    $res['customer_status']  = ' <label class="label label-danger publish_btn">' . translate('Unpublished') . '</label>';
                }
                //add html for action
                if ($row['customer_product_id'] != '36' && $row['customer_product_id'] != '37') {
                    $res['options'] = "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\"
                                onclick=\"ajax_set_full('view','" . translate('view_product') . "','" . translate('successfully_viewed!') . "','product_view','" . $row['customer_product_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                    " . translate('view') . "
                            </a>

                            <a onclick=\"delete_confirm('" . $row['customer_product_id'] . "','" . translate('really_want_to_delete_this?') . "')\"
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    " . translate('delete') . "
                            </a>";
                } else {
                    $res['options'] = "This Customer Product For Demo Purpose Only We Can't Edit/Delete.If Need You Can Create Customer Product";
                }
                $data[] = $res;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );

            echo json_encode($result);
        } else if ($para1 == 'dlt_img') {

            $a = explode('_', $para2);
            $this->crud_model->file_dlt('customer_product', $a[0], '.jpg', 'multi', $a[1]);
            recache();
        } elseif ($para1 == 'sub_by_cat') {
            echo $this->crud_model->select_html('sub_category', 'sub_category', 'sub_category_name', 'add', 'demo-chosen-select required', '', 'category', $para2, 'get_brnd');
        } elseif ($para1 == 'brand_by_sub') {
            $brands = json_decode($this->crud_model->get_type_name_by_id('sub_category', $para2, 'brand'), true);
            if (empty($brands)) {
                echo translate("No brands are available for this sub category");
            } else {
                echo $this->crud_model->select_html('brand', 'brand', 'name', 'add', 'demo-chosen-select required', '', 'brand_id', $brands, '', 'multi');
            }
        } elseif ($para1 == 'product_by_sub') {
            echo $this->crud_model->select_html('product', 'product', 'title', 'add', 'demo-chosen-select required', '', 'sub_category', $para2, 'get_pro_res');
        } elseif ($para1 == 'pur_by_pro') {
            echo $this->crud_model->get_type_name_by_id('product', $para2, 'purchase_price');
        } elseif ($para1 == 'sale_report') {
            $data['product'] = $para2;
            $this->load->view('back/admin/product_sale_report', $data);
        } elseif ($para1 == 'product_publish_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['admin_status'] = 'ok';
            } else {
                $data['admin_status'] = 'no';
            }
            $this->db->where('customer_product_id', $product);
            $this->db->update('customer_product', $data);
            $this->crud_model->set_category_data(0);
            recache();
        } else {
            $page_data['page_name']   = "customer_products";
            $page_data['all_product'] = $this->db->get('customer_product')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function affiliation_settings($para1 = "", $para2 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == "set") {
            $affiliation_validity =
                is_numeric($this->input->post('affiliation_validity')) && $this->input->post('affiliation_validity') > 1
                ? $this->input->post('affiliation_validity') : 30;

            $affiliation_point_to_currency_rate =
                is_numeric($this->input->post('affiliation_point_to_currency_rate')) && $this->input->post('affiliation_point_to_currency_rate') > 1
                ? $this->input->post('affiliation_point_to_currency_rate') : 1.00;


            $this->db->where('type', 'affiliation_validity');
            $this->db->update('general_settings', array('value' => $affiliation_validity));

            $this->db->where('type', 'affiliation_point_to_currency_rate');
            $this->db->update('general_settings', array('value' => $affiliation_point_to_currency_rate));

            redirect(base_url() . 'admin/site_settings/affiliation_settings/', 'refresh');
        }
    }
    function google_analytics_key($para1 = "")
    {
        if (!$this->crud_model->admin_permission('captha_n_social_settings')) {
            redirect(base_url() . 'admin');
        }
        $this->db->where('type', "google_analytics_key");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('tracking_id')
        ));
        recache();
    }
    function facebook_pixel_settings($para1 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'admin');
        }
        $this->db->where('type', "facebook_pixel_id");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('facebook_pixel_id')
        ));
    }
    function facebook_chat_settings($para1 = "")
    {
        if (!$this->crud_model->admin_permission('site_settings')) {
            redirect(base_url() . 'admin');
        }
        $this->db->where('type', "facebook_chat_page_id");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('facebook_chat_page_id')
        ));

        $this->db->where('type', "facebook_chat_logged_in_greeting");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('facebook_chat_logged_in_greeting')
        ));

        $this->db->where('type', "facebook_chat_logged_out_greeting");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('facebook_chat_logged_out_greeting')
        ));

        $this->db->where('type', "facebook_chat_theme_color");
        $this->db->update('general_settings', array(
            'value' => $this->input->post('facebook_chat_theme_color')
        ));
    }
    function wallet_load($para1 = '', $para2 = '', $para3 = '')
    {
        // echo "WALLETABC";
        if (!$this->crud_model->admin_permission('user')) {
            redirect(base_url() . 'admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '84', 'value') !== 'ok') {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'delete') {

            $this->db->where('wallet_load_id', $para2);
            $this->db->delete('wallet_load');
        } else if ($para1 == 'list') {
            $this->db->order_by('wallet_load_id', 'desc');
            $page_data['all_wallet_loads'] = $this->db->get('wallet_load')->result_array();
            $page_data['user_rights_13_12'] = $this->get_user_rights(13,12);
            $this->load->view('back/admin/wallet_load_list', $page_data);
        } else if ($para1 == 'view') {
            $page_data['wallet_load_data'] = $this->db->get_where('wallet_load', array(
                'wallet_load_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/wallet_load_view', $page_data);
        } else if ($para1 == 'pay_form') {
            $page_data['wallet_load_id'] = $para2;
            $this->load->view('back/admin/wallet_load_pay_form', $page_data);
        } else if ($para1 == 'user_view') {
            $page_data['user_data'] = $this->db->get_where('user', array(
                'user_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/user_view', $page_data);
        } else if ($para1 == 'approval') {
            $page_data['wallet_load_id'] = $para2;
            $det = json_decode($this->db->get_where('wallet_load', array('wallet_load_id' => $para2))->row()->details, true);
            $page_data['payment_info'] = $this->db->get_where('wallet_load', array('wallet_load_id' => $para2))->row()->payment_details;
            $page_data['status'] = $det['status'];
            $this->load->view('back/admin/wallet_load_approval', $page_data);
        } else if ($para1 == 'add') {
            $this->load->view('back/admin/wallet_load_add');
        } else if ($para1 == 'approval_set') {
            $wallet_load = $para2;
            $approval = $this->input->post('approval');
            if ($approval == 'ok') {
                $data['details'] = json_encode(array('status' => 'paid'));
                $user = $this->db->get_where('wallet_load', array('wallet_load_id' => $wallet_load))->row()->user;
                $amount = $this->db->get_where('wallet_load', array('wallet_load_id' => $wallet_load))->row()->amount;
                $this->wallet_model->add_user_balance($amount, $user);
                $this->email_model->wallet_email('admin_approved_to_customer', $wallet_load);
            } else {
                $data['details'] = json_encode(array('status' => 'pending'));
            }
            $this->db->where('wallet_load_id', $wallet_load);
            $this->db->update('wallet_load', $data);
            //$this->email_model->status_email('wallet_load', $wallet_load);
            recache();
        } elseif ($para1 == 'pay') {
            $wallet_load         = $para2;
            $method         = $this->input->post('method');
            $amount         = $this->input->post('amount');
            $amount_in_usd  = $amount / exchange('usd');
        } else {
            $page_data['page_name'] = "wallet_load";
            $page_data['all_wallet_loads'] = $this->db->get('wallet_load')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function package($para1 = '', $para2 = '', $para3 = '')
    {
        /*if (!$this->crud_model->admin_permission('package')) {
            redirect(base_url() . 'admin');
        }*/
        if ($para1 == 'list') {
            $page_data['all_packages'] = $this->db->get("package")->result();
            $page_data['page_name'] = 'package';

            $this->load->view('back/admin/package_list', $page_data);
        } elseif ($para1 == "edit") {
            $page_data['get_package'] = $this->db->get_where("package", array("package_id" => $para2))->result();
            $page_data['page_name'] = 'package_edit';
            $this->load->view('back/admin/package_edit', $page_data);
        } elseif ($para1 == "update") {
            $package_id = $this->input->post('package_id');
            $data['name'] = $this->input->post('name');
            $data['amount'] = $this->input->post('amount');
            $data['upload_amount'] = $this->input->post('upload_amount');


            if ($_FILES['image']['name'] !== '') {
                $id = $package_id;
                $path = $_FILES['image']['name'];
                $ext = '.' . pathinfo($path, PATHINFO_EXTENSION);
                if ($ext == ".jpg" || $ext == ".JPG" || $ext == ".jpeg" || $ext == ".JPEG" || $ext == ".png" || $ext == ".PNG") {
                    $this->crud_model->file_up("image", "plan", $id, '', '', $ext);
                    $images[] = array('image' => 'plan_' . $id . $ext, 'thumb' => 'plan_' . $id . '_thumb' . $ext);
                    $data['image'] = json_encode($images);
                } else {
                    $this->session->set_flashdata('alert', 'failed_image');
                    redirect(base_url() . 'admin/package', 'refresh');
                }
            }

            $this->db->where('package_id', $para2);
            $result = $this->db->update('package', $data);
            if ($result) {
                $this->session->set_flashdata('alert', 'edit');
                redirect(base_url() . 'admin/package', 'refresh');
            } else {
                echo "Data Failed to Edit!";
            }
            exit;
        } else {
            $page_data['all_packages'] = $this->db->get("package")->result();
            $page_data['page_name'] = 'package';
            $this->load->view('back/index', $page_data);
        }
    }
    /*function vendor_commission(){
        if (!$this->crud_model->admin_permission('vendor')) {
            redirect(base_url() . 'admin');
        }
        $page_data['page_name'] = "vendor_commission";
        $this->load->view('back/index', $page_data);
    }
	function set_commission($para1 = '', $para2 = '',$para3 = '',$para4 = '')
    {
        if (!$this->crud_model->admin_permission('business_settings')) {
            redirect(base_url() . 'admin');
        }
        $this->db->where('type', "commission_amount");
        $this->db->update('business_settings', array(
            'value' => $this->input->post('vendor_commission')
        ));
        recache();

    }*/
    function package_payment($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('product')) {
            redirect(base_url() . 'admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'view') {
            $page_data['package_data'] = $this->db->get_where('package_payment', array(
                'package_payment_id' => $para2
            ))->row();
            $this->load->view('back/admin/package_payment_view', $page_data);
        } elseif ($para1 == 'delete') {

            $this->db->where('package_payment_id', $para2);
            $this->db->delete('package_payment');
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('package_payment_id', 'desc');
            $page_data['all_product'] = $this->db->get('package_payment')->result_array();
            $this->load->view('back/admin/package_payment_list', $page_data);
        } elseif ($para1 == 'list_data') {
            $limit      = $this->input->get('limit');
            $search     = $this->input->get('search');
            $order      = $this->input->get('order');
            $offset     = $this->input->get('offset');
            $sort       = $this->input->get('sort');
            if ($search) {
                $this->db->like('title', $search, 'both');
            }
            $total      = $this->db->get('package_payment')->num_rows();
            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'package_payment_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            if ($search) {
                $this->db->like('payment_type', $search, 'both');
            }
            $i = 1;
            $products   = $this->db->get('package_payment', $limit, $offset)->result_array();
            $data       = array();
            foreach ($products as $row) {

                $res    = array(
                    '#'    => '',
                    'customer_name'    => '',
                    'date'             => '',
                    'payment_type'     => '',
                    'amount'           => '',
                    'package'          => '',
                    'status'           => '',
                    'options'          => ''
                );
                $res['#'] = $i;
                $res['customer_name']  = $this->db->get_where('user', array('user_id' => $row['user_id']))->row()->username . ' ' . $this->db->get_where('user', array('user_id' => $row['user_id']))->row()->surname;
                $res['date']  = date('d/m/Y H:i A', $row['purchase_datetime']);
                $res['payment_type'] = "<center><span class='badge badge-primary'>" . $row['payment_type'] . "</span></center>";
                $res['amount'] = currency('', 'def') . ' ' . $this->cart->format_number($row['amount']);
                $res['package'] = $this->db->get_where('package', array('package_id' => $row['package_id']))->row()->name;
                if ($row['payment_status'] == 'paid') {
                    $res['status'] = "<center><span class='badge badge-success'>" . translate($row['payment_status']) . "</span></center>";
                } elseif ($row['payment_status'] == 'due') {
                    $res['status'] = "<center><span class='badge badge-danger'>" . translate($row['payment_status']) . "</span></center>";
                } elseif ($row['payment_status'] == 'pending') {
                    $res['status'] = "<center><span class='badge badge-info'>" . translate($row['payment_status']) . "</span></center>";
                }

                if ($row['status'] == 'ok') {
                    $res['customer_status']  = ' <label class="label label-success publish_btn">' . translate('Published') . '</label>';
                } else {
                    $res['customer_status']  = ' <label class="label label-danger publish_btn">' . translate('Unpublished') . '</label>';
                }
                //add html for action
                $res['options'] = "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\"
                                onclick=\"ajax_modal('view','" . translate('payment_details') . "','" . translate('successfully_saved!') . "','package_payment_view','" . $row['package_payment_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                    " . translate('view') . "
                            </a>
                            <a onclick=\"delete_confirm('" . $row['package_payment_id'] . "','" . translate('really_want_to_delete_this?') . "')\"
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    " . translate('delete') . "
                            </a>";
                $data[] = $res;
                $i++;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );

            echo json_encode($result);
        } else {
            $page_data['page_name']   = "package_payment";
            $page_data['all_product'] = $this->db->get('customer_product')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function delete_all_categories($para1 = '')
    {
        if (!$this->crud_model->admin_permission('delete_all_categories')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'delete') {

            $dir1 = 'uploads/category_image';
            $leave_files1 = array('default.jpg');

            foreach (glob("$dir1/*") as $file1) {
                if (!in_array(basename($file1), $leave_files1))
                    unlink($file1);
            }
            $dir2 = 'uploads/sub_category_image';
            $leave_files2 = array('default.jpg');

            foreach (glob("$dir2/*") as $file2) {
                if (!in_array(basename($file2), $leave_files2))
                    unlink($file2);
            }
            $this->db->empty_table('category');
            $this->db->empty_table('sub_category');
            recache();
        } else {
            $page_data['page_name'] = "delete_all_categories";
            $page_data['view_rights']=$this->get_user_view_rights();
            $this->load->view('back/index', $page_data);
        }
    }
    function delete_all_products($para1 = '')
    {
        if (!$this->crud_model->admin_permission('delete_all_products')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'delete') {

            $dir = 'uploads/product_image';
            $leave_files = array('default.jpg');

            foreach (glob("$dir/*") as $file) {
                if (!in_array(basename($file), $leave_files))
                    unlink($file);
            }
            // $this->db->delete('product');
            $this->db->empty_table('product');
            //echo $this->db->last_query();
            recache();
        } else {
            $page_data['page_name'] = "delete_all_products";
            $page_data['view_rights']=$this->get_user_view_rights();
            $this->load->view('back/index', $page_data);
        }
    }

    function delete_all_brands($para1 = '')
    {
        if (!$this->crud_model->admin_permission('delete_all_brands')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'delete') {

            $dir = 'uploads/brand_image';
            $leave_files = array('default.jpg');

            foreach (glob("$dir/*") as $file) {
                if (!in_array(basename($file), $leave_files))
                    unlink($file);
            }
            $this->db->empty_table('brand');
            recache();
        } else {
            $page_data['page_name'] = "delete_all_brands";
            $page_data['view_rights']=$this->get_user_view_rights();
            $this->load->view('back/index', $page_data);
        }
    }

    function delete_all_classified($para1 = '')
    {
        if (!$this->crud_model->admin_permission('delete_all_classified')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'delete') {

            $dir = 'uploads/customer_product_image';
            $leave_files = array('default.jpg');

            foreach (glob("$dir/*") as $file) {
                if (!in_array(basename($file), $leave_files))
                    unlink($file);
            }
            $this->db->empty_table('customer_product');
            recache();
        } else {
            $page_data['page_name'] = "delete_all_classified";
            $page_data['view_rights']=$this->get_user_view_rights();
            $this->load->view('back/index', $page_data);
        }
    }
    public function product_bulk_upload()
    {
        if (!$this->crud_model->admin_permission('product_bulk_upload')) {
            redirect(base_url() . 'admin');
        }

        $physical_categories =  $this->db->where('digital', null)->or_where('digital', '')->get('category')->result_array();
        $physical_sub_categories =  $this->db->where('digital', null)->or_where('digital', '')->get('sub_category')->result_array();
        $digital_categories =  $this->db->where('digital', 'ok')->get('category')->result_array();
        $digital_sub_categories =  $this->db->where('digital', 'ok')->get('sub_category')->result_array();
        $brands =  $this->db->get('brand')->result_array();

        $page_data['page_name'] = "product_bulk_upload";
        $page_data['physical_categories'] = $physical_categories;
        $page_data['physical_sub_categories'] = $physical_sub_categories;
        $page_data['digital_categories'] = $digital_categories;
        $page_data['digital_sub_categories'] = $digital_sub_categories;
        $page_data['brands'] = $brands;

        $this->load->view('back/index', $page_data);
    }

    public function product_bulk_upload_save_bk()
    {
        if (!file_exists($_FILES['bulk_file']['tmp_name']) || !is_uploaded_file($_FILES['bulk_file']['tmp_name'])) {
            $this->session->set_flashdata('error', translate('File is not selected'));
            redirect('admin/product_bulk_upload');
        }
        ini_set("memory_limit", "-1");
        ini_set('upload_max_size', '20480M');
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
        if (empty($error)) {
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
                $i = 0;
                $flag = true;
                $inc = 0;
                $image_url = array();
                foreach ($allDataInSheet as $value) {
                    $inc++;
                    if ($value['A'] != 'Product Name*' && $value['A'] != ' ') {
                        if ($inc > 1) {

                            if ($value['A'] != '') {

                                //$num_of_imgs = 0;

                                $category = $this->db->get_where('category', array('category_name' => $value['C']))->row()->category_id;

                                if ($category == '') {
                                    $data_c['category_name'] = $value['C'];
                                    $this->db->insert('category', $data_c);
                                    $category = $this->db->insert_id();
                                }

                                $brand = $this->db->get_where('brand', array('name' => $value['E']))->row()->brand_id;


                                if ($brand == '') {
                                    $data_b['name'] = $value['E'];
                                    $data_b['description'] = $value['E'];
                                    $this->db->insert('brand', $data_b);
                                    $brand = $this->db->insert_id();
                                }

                                $sub_category = $this->db->get_where('sub_category', array('sub_category_name' => $value['D']))->row()->sub_category_id;

                                if ($sub_category == '') {

                                    $data['sub_category_name'] = $value['D'];
                                    $data_s['category']          = $category;
                                    if ($brand == NULL) {
                                        $data_s['brand']             = '[]';
                                    } else {
                                        $data_s['brand']             = json_encode(array($brand), 1);
                                    }

                                    $data_s['sub_category_name'] = $value['D'];
                                    $this->db->insert('sub_category', $data_s);

                                    $sub_category = $this->db->insert_id();
                                }

                                $inserdata['category']             = $category;
                                $inserdata['sub_category']         = $sub_category;
                                $inserdata['brand']                 = $brand;
                                $this->db->select('product_id');
                                //echo "2";
                                $product_id = $this->db->get_where('product', array('category' => $category, 'sub_category' => $sub_category, 'title' => $value['A']))->row()->product_id;
                                //echo $this->db->last_query();



                                //echo $product_id;

                                if ($product_id == '') {
                                    //echo "1";
                                    if ($value['A'] != '')
                                        $inserdata['title']                 = $value['A'];
                                    else
                                        $inserdata['title']                 = 'null';

                                    if ($value['B'] != '')
                                        $inserdata['description']                 = $value['B'];
                                    else
                                        $inserdata['description']                 = 'null';



                                    if ($value['F'] != '')
                                        $inserdata['purchase_price']         = $value['F'];
                                    else
                                        $inserdata['purchase_price']         = 0;

                                    if ($value['G'] != '')
                                        $inserdata['sale_price']         = $value['G'];
                                    else
                                        $inserdata['sale_price']         = 'null';

                                    if ($value['H'] != '')
                                        $inserdata['tax']         = $value['H'];
                                    else
                                        $inserdata['tax']         = 0;

                                    if ($value['I'] != '')
                                        $inserdata['discount']         = $value['I'];
                                    else
                                        $inserdata['discount']         = 'null';


                                    if ($value['J'] != '')
                                        $inserdata['current_stock']         = $value['J'];
                                    else
                                        $inserdata['current_stock']         = 'null';


                                    if ($value['K'] != '')
                                        $inserdata['unit']         = $value['K'];
                                    else
                                        $inserdata['unit']         = 'null';






                                    if ($value['L'] != '')
                                        $inserdata['tax_type']         = $value['L'];
                                    else
                                        $inserdata['tax_type']         = '';




                                    if ($value['M'] != '')
                                        $inserdata['discount_type']         = $value['M'];
                                    else
                                        $inserdata['discount_type']         = '';












                                    $inserdata['status']             = 'ok';
                                    $inserdata['options']         = '[]';
                                    //$inserdata['num_of_imgs']     = $num_of_imgs;
                                    $inserdata['num_of_imgs']     = '0';
                                    $inserdata['add_timestamp']   = time();
                                    $inserdata['download']        = NULL;
                                    $inserdata['rating_user']      = '[]';
                                    $inserdata['color']      = '["rgba(204,204,204,1)"]';
                                    $inserdata['added_by']             = json_encode(array('type' => 'admin', 'id' => '1'));
                                    $store_id = $this->db->get_where('vendor', array('name' => $value['N']))->row()->vendor_id;
                                    if ($store_id != '') {
                                        $inserdata['store_id']         = $store_id;

                                        $i++;

                                        $result = $this->db->insert('product', $inserdata);

                                        //  echo  $this->db->last_query(); exit;
                                        $pinsert_id = $this->db->insert_id();
                                    }


                                    $stockData['type'] = 'add';
                                    $stockData['category'] = $category;
                                    $stockData['sub_category'] = $sub_category;
                                    $stockData['product'] = $pinsert_id;
                                    $stockData['quantity'] = $inserdata['current_stock'];
                                    $stockData['rate'] = $inserdata['sale_price'];
                                    $stockData['total'] = $inserdata['current_stock'] * $inserdata['purchase_price'];
                                    $stockData['datetime']    = time();
                                    $result1 = $this->db->insert('stock', $stockData);
                                } else {
                                    if ($value['A'] != '')
                                        $inserdata['title']                 = $value['A'];
                                    else
                                        $inserdata['title']                 = 'null';

                                    if ($value['B'] != '')
                                        $inserdata['description']                 = $value['B'];
                                    else
                                        $inserdata['description']                 = 'null';


                                    if ($value['F'] != '')
                                        $inserdata['purchase_price']         = $value['F'];
                                    else
                                        $inserdata['purchase_price']         = 0;

                                    if ($value['G'] != '')
                                        $inserdata['sale_price']         = $value['G'];
                                    else
                                        $inserdata['sale_price']         = 'null';

                                    if ($value['H'] != '')
                                        $inserdata['tax']         = $value['H'];
                                    else
                                        $inserdata['tax']         = 0;

                                    if ($value['I'] != '')
                                        $inserdata['discount']         = $value['I'];
                                    else
                                        $inserdata['discount']         = 'null';


                                    if ($value['J'] != '')
                                        $inserdata['current_stock']         = $value['J'];
                                    else
                                        $inserdata['current_stock']         = 'null';


                                    if ($value['K'] != '')
                                        $inserdata['unit']         = $value['K'];
                                    else
                                        $inserdata['unit']         = 'null';






                                    if ($value['L'] != '')
                                        $inserdata['tax_type']         = $value['L'];
                                    else
                                        $inserdata['tax_type']         = '';




                                    if ($value['M'] != '')
                                        $inserdata['discount_type']         = $value['M'];
                                    else
                                        $inserdata['discount_type']         = '';

                                    $productdet  = $this->db->get_where('product', array('product_id' => $product_id))->result_array();
                                    //echo "<pre>"; print_r($productdet); echo "</pre>";











                                    $inserdata['added_by']             = json_encode(array('type' => 'admin', 'id' => '1'));
                                    $inserdata['options']         = '[]';
                                    //$inserdata['num_of_imgs']     = $num_of_imgs;
                                    $inserdata['num_of_imgs']     = '0';
                                    $inserdata['add_timestamp']   = time();
                                    $inserdata['download']        = NULL;
                                    $inserdata['rating_user']      = '[]';
                                    $i++;

                                    $store_id = $this->db->get_where('vendor', array('name' => $value['N']))->row()->vendor_id;
                                    if ($store_id != '') {
                                        $inserdata['store_id']         = $store_id;
                                    }

                                    $this->db->where('product_id', $product_id);
                                    $this->db->update('product', $inserdata);


                                    $stockData['type'] = 'add';
                                    $stockData['category'] = $category;
                                    $stockData['sub_category'] = $sub_category;
                                    $stockData['product'] = $pinsert_id;
                                    $stockData['quantity'] = $inserdata['current_stock'];
                                    $stockData['rate'] = $inserdata['sale_price'];
                                    $stockData['total'] = $inserdata['current_stock'] * $inserdata['purchase_price'];
                                    $stockData['oid'] = $this->session->userdata('propertyIDS');
                                    $stockData['reason_note'] = 'Add Stock';
                                    $stockData['datetime']    = time();
                                    $result1 = $this->db->insert('stock', $stockData);
                                }
                            }
                        }
                    }
                }

                $this->session->set_flashdata('success', translate('updated_successfully'));
                redirect('admin/product_bulk_upload');
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                $this->session->set_flashdata('error', translate($e->getMessage()));
                redirect('admin/product_bulk_upload');
            }
        } else {
            $this->session->set_flashdata('error', translate($error['error']));
            redirect('admin/product_bulk_upload');
        }
    }


    public function product_bulk_upload_save()
    {
        if (!file_exists($_FILES['bulk_file']['tmp_name']) || !is_uploaded_file($_FILES['bulk_file']['tmp_name'])) {
            $this->session->set_flashdata('error', translate('File is not selected'));
            redirect('admin/product_stock?tab=product_bulk_upload');
        }
        ini_set("memory_limit", "-1");
        ini_set('upload_max_size', '20480M');
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
        if (empty($error)) {
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
                $inc = 0;
                foreach ($allDataInSheet as $value) {
                    $inc++;
                    if ($inc > 1) {
                        $title=$value['A'];
                        $vendor_name=$this->db->get_where('vendor', array('name' => $value['B']))->row()->vendor_id;
                        $brand_name=$this->db->get_where('brand', array('name' => $value['C']))->row()->brand_id;
                        $category_name=$this->db->get_where('category', array('category_name' => $value['D']))->row()->category_id;
                        $sub_category_name=$this->db->get_where('sub_category', array('sub_category_name' => $value['E']))->row()->sub_category_id;
                        $unit=$value['F'];
                        $tag=$value['G'];
                        $images=$value['H'];
                        $image_url = array();if ($images != '') {$image_url = explode(',', $images);}
                        $num_of_imgs=count($image_url);
                        $description=($value['I']!='')?$value['I']:'null';
                        $sale_price=($value['J']!='')?$value['J']:0;
                        $purchase_price=($value['K']!='')?$value['K']:0;
                        $shipping_cost=($value['L']!='')?$value['L']:0;
                        $tax=($value['M']!='')?$value['M']:0;
                        $tax_type=$value['N'];
                        $discount=($value['O']!='')?$value['O']:0;
                        $discount_type=$value['P'];
                        $current_stock=($value['Q']!='')?$value['Q']:0;
                        if (($title != '') && ($vendor_name != '') && ($brand_name != '') && ($category_name != '') && ($sub_category_name != ''))
                        {
                            $product_id = $this->db->get_where('product', array('category' => $category_name, 'sub_category' => $sub_category_name, 'title' => $title))->row()->product_id;
                            if ($product_id == '') {
                                $inserdata=array();
                                $inserdata['title']              = $title;
                                $inserdata['store_id']           = $vendor_name;
                                $inserdata['brand']              = $brand_name;
                                $inserdata['category']           = $category_name;
                                $inserdata['sub_category']       = $sub_category_name;
                                $inserdata['unit']               = $unit;
                                $inserdata['tag']                = $tag;
                                $inserdata['num_of_imgs']        = $num_of_imgs;
                                $inserdata['description']        = $description;
                                $inserdata['sale_price']         = $sale_price;
                                $inserdata['purchase_price']     = $purchase_price;
                                $inserdata['shipping_cost']      = $shipping_cost;
                                $inserdata['tax']                = $tax;
                                $inserdata['tax_type']           = $tax_type;
                                $inserdata['discount']           = $discount;
                                $inserdata['discount_type']      = $discount_type;
                                $inserdata['current_stock']      = $current_stock;
                                $inserdata['status']             = 'ok';
                                $inserdata['options']            = '[]';
                                $inserdata['add_timestamp']      = time();
                                $inserdata['download']           = NULL;
                                $inserdata['rating_user']        = '[]';
                                $inserdata['color']              = '["rgba(204,204,204,1)"]';
                                $inserdata['added_by']           = json_encode(array('type' => 'admin', 'id' => '1'));
                                $inserdata['featured']           = 'no';
                                $inserdata['front_image']        = 0;
                                $result = $this->db->insert('product', $inserdata);
                                $pinsert_id = $this->db->insert_id();
                                if (!empty($image_url)) {
                                    $this->crud_model->file_up_from_urls($image_url, "product", $pinsert_id);
                                }
                                $stockData['type'] = 'add';
                                $stockData['category'] = $category_name;
                                $stockData['sub_category'] = $sub_category_name;
                                $stockData['product'] = $pinsert_id;
                                $stockData['quantity'] = $current_stock;
                                $stockData['rate'] = $sale_price;
                                $stockData['total'] = ($current_stock * $purchase_price);
                                $stockData['datetime']    = time();
                                $result1 = $this->db->insert('stock', $stockData);
                            } else {
                                $inserdata=array();
                                $inserdata['store_id']           = $vendor_name;
                                $inserdata['brand']              = $brand_name;
                                $inserdata['unit']               = $unit;
                                $inserdata['tag']                = $tag;
                                $productdet  = $this->db->get_where('product', array('product_id' => $product_id))->result_array();
                                if ($num_of_imgs>0) {
                                    $images = $this->crud_model->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'all');
                                    if ($images) {
                                        foreach ($images as $row1) {
                                            $a = explode('.', $row1);
                                            $a = $a[(count($a) - 2)];
                                            $a = explode('_', $a);
                                            $p = $a[(count($a) - 2)];
                                            $i = $a[(count($a) - 3)];

                                            $imge_path = $i . '_' . $p;
                                            $a = explode('_', $imge_path);
                                            $this->crud_model->file_dlt('product', $a[0], '.jpg', 'multi', $a[1]);
                                        }
                                    }
                                } else {
                                    foreach ($productdet as $product) {
                                        $num_of_imgs = $product['num_of_imgs'];
                                    }
                                }
                                $inserdata['num_of_imgs']        = $num_of_imgs;
                                $inserdata['description']        = $description;
                                $inserdata['sale_price']         = $sale_price;
                                $inserdata['purchase_price']     = $purchase_price;
                                $inserdata['shipping_cost']      = $shipping_cost;
                                $inserdata['tax']                = $tax;
                                $inserdata['tax_type']           = $tax_type;
                                $inserdata['discount']           = $discount;
                                $inserdata['discount_type']      = $discount_type;
                                $stock = $this->db->get_where('product', array('product_id' => $product_id))->row()->current_stock;
                                $current_stock += $stock;
                                $inserdata['current_stock']      = $current_stock;
                                $inserdata['status']             = 'ok';
                                $inserdata['options']            = '[]';
                                $inserdata['add_timestamp']      = time();
                                $inserdata['download']           = NULL;
                                $inserdata['rating_user']        = '[]';
                                $inserdata['color']              = '["rgba(204,204,204,1)"]';
                                $inserdata['added_by']           = json_encode(array('type' => 'admin', 'id' => '1'));
                                $inserdata['featured']           = 'no';
                                $inserdata['front_image']        = 0;
                                $this->db->where('product_id', $product_id);
                                $this->db->update('product', $inserdata);
                                if (!empty($image_url)) {
                                    $this->crud_model->file_up_from_urls($image_url, "product", $product_id);
                                }
                                $stockData['type'] = 'add';
                                $stockData['category'] = $category_name;
                                $stockData['sub_category'] = $sub_category_name;
                                $stockData['product'] = $product_id;
                                $stockData['quantity'] = $current_stock;
                                $stockData['rate'] = $sale_price;
                                $stockData['total'] = ($current_stock * $purchase_price);
                                $stockData['datetime']    = time();
                                $stockData['oid'] = $this->session->userdata('propertyIDS');
                                $stockData['reason_note'] = 'Add Stock';
                                $result1 = $this->db->insert('stock', $stockData);
                            }
                        }
                        /* else{
                            $this->session->set_flashdata('error', translate('Check the Store Name,Brand,Category,SubCategory in File'));
                            redirect('admin/product_stock?tab=product_bulk_upload');
                        } */
                    }
                }
                $this->session->set_flashdata('success', translate('updated_successfully'));
                redirect('admin/product_stock?tab=product_bulk_upload');
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                $this->session->set_flashdata('error', translate($e->getMessage()));
                redirect('admin/product_stock?tab=product_bulk_upload');
            }
        } else {
            $this->session->set_flashdata('error', translate($error['error']));
            redirect('admin/product_stock?tab=product_bulk_upload');
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
        $product_data['sale_price'] = is_numeric($product['sale_price']) ? $product['sale_price'] : 0;

        $product_data['add_timestamp'] = time();
        $product_data['download'] = NULL;
        $product_data['featured'] = 'no';
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
        $product_data['added_by'] = json_encode(array('type' => 'admin', 'id' => $this->session->userdata('admin_id')));
        $product_data['options'] = json_encode($options = array());

        $this->db->insert('product', $product_data);
        $product_id = $this->db->insert_id();
        $this->crud_model->set_category_data(0);
        recache();

        if ($product_data['current_stock'] > 0) {
            $product_stock_data['type']         = 'add';
            $product_stock_data['product']      = $product_id;
            $product_stock_data['category']     = $product_data['category'];
            $product_stock_data['sub_category'] = $product_data['sub_category'];
            $product_stock_data['product']      = $product_data['product'];
            $product_stock_data['quantity']     = $product_data['current_stock'];
            $product_stock_data['rate']         = $product_data['purchase_price'];
            $product_stock_data['total']        = $product_data['purchase_price'] * $product_data['current_stock'];
            $product_stock_data['reason_note']  = 'bulk';
            $product_stock_data['datetime']     = time();
            $product_stock_data['current_stock'] = $product_data['current_stock'];
            $this->db->insert('stock', $product_stock_data);
        }

        if (!empty($image_urls)) {
            // if(!demo()){
            $this->crud_model->file_up_from_urls($image_urls, "product", $product_id);
            //}
        }
    }
    function del_slot($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('coupon')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['f_date'] = strtotime($this->input->post('till'));



            $this->db->insert('del_slot', $data);
        } else if ($para1 == 'edit') {
            $page_data['del_data'] = $this->db->get_where('del_slot', array(
                'del_slot_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/del_slot_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['f_date'] = strtotime($this->input->post('till'));

            $this->db->where('del_slot_id', $para2);
            $this->db->update('del_slot', $data);
        } elseif ($para1 == 'delete') {
            $this->db->where('del_slot_id', $para2);
            $this->db->delete('del_slot');
        } elseif ($para1 == 'list') {
            $date = strtotime(date("Y/m/d"));

            $this->db->where('f_date >=', $date);

            $this->db->order_by('del_slot_id', 'desc');
            $page_data['all_del_slot'] = $this->db->get('del_slot')->result_array();
            $this->load->view('back/admin/del_slot_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/del_slot_add');
        } else {
            $page_data['page_name']      = "del_slot";
            $page_data['all_del_slot'] = $this->db->get('del_slot')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    function del_slot_time($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('coupon')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['del_slot_id '] = $this->input->post('del_slot');
            $data['f_time'] = $this->input->post('f_time');
            $data['t_time'] = $this->input->post('t_time');
            $data['slot'] = $this->input->post('slot');
            $data['time_slot'] = $data['f_time'] . ' - ' . $data['t_time'];


            $this->db->insert('del_slot_time', $data);
            //echo $this->db->last_query(); exit;
        } else if ($para1 == 'edit') {
            $page_data['del_slot_time'] = $this->db->get_where('del_slot_time', array(
                'del_slot_time_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/del_slot_time_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['del_slot_id '] = $this->input->post('del_slot');
            $data['f_time'] = $this->input->post('f_time');
            $data['t_time'] = $this->input->post('t_time');
            $data['time_slot'] = $data['f_time'] . ' - ' . $data['t_time'];
            $data['slot'] = $this->input->post('slot');
            $this->db->where('del_slot_time_id', $para2);
            $this->db->update('del_slot_time', $data);
        } elseif ($para1 == 'delete') {
            $this->db->where('del_slot_time_id', $para2);
            $this->db->delete('del_slot_time');
        } elseif ($para1 == 'list') {

            $this->db->order_by('del_slot_time_id', 'desc');
            $page_data['all_del_slot_time'] = $this->db->get('del_slot_time')->result_array();
            //echo $this->db->last_query(); exit;
            $this->load->view('back/admin/del_slot_time_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/del_slot_time_add');
        } else {
            $page_data['page_name']      = "del_slot_time";
            $page_data['all_del_slot_time'] = $this->db->get('del_slot_time')->result_array();
            //echo $this->db->last_query(); exit;
            $this->load->view('back/index', $page_data);
        }
    }

    function rewards($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('coupon')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $data['del_slot_id '] = $this->input->post('del_slot');
            $data['f_time'] = $this->input->post('f_time');
            $data['t_time'] = $this->input->post('t_time');
            $data['slot'] = $this->input->post('slot');
            $data['time_slot'] = $data['f_time'] . ' - ' . $data['t_time'];


            $this->db->insert('del_slot_time', $data);
            //echo $this->db->last_query(); exit;
        } else if ($para1 == 'edit') {
            $page_data['rewards'] = $this->db->get_where('rewards', array(
                'id' => $para2
            ))->result_array();
            $this->load->view('back/admin/rewards_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['rewards'] = $this->input->post('rewards');
            $data['type'] = $this->input->post('type');

            $this->db->where('id', $para2);
            $this->db->update('rewards', $data);
        } elseif ($para1 == 'delete') {
            $this->db->where('del_slot_time_id', $para2);
            $this->db->delete('del_slot_time');
        } elseif ($para1 == 'list') {

            $this->db->order_by('id', 'desc');
            $page_data['rewards'] = $this->db->get('rewards')->result_array();
            $page_data['user_rights_29_0'] = $this->get_user_rights(29,0);
            //echo $this->db->last_query(); exit;
            $this->load->view('back/admin/rewards_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/del_slot_time_add');
        } else {
            $page_data['page_name']      = "rewards";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['rewards'] = $this->db->get('rewards')->result_array();
            //echo $this->db->last_query(); exit;
            $this->load->view('back/index', $page_data);
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
            $this->db->where('added_by', 'admin');
            $page_data['subscribe_sale'] = $this->db->get('subscribe_sale')->result_array();
            $this->load->view('back/admin/subscribe_sale_list', $page_data);
        } else {
            $page_data['page_name']      = "subscribe_sale";
            $page_data['subscribe_sale'] = $this->db->get_where('subscribe_sale', array('added_by' => 'admin'))->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /*rating & review */
    function review($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('user')) {
            redirect(base_url() . 'index.php/admin');
        } 
        
        if ($para1 == 'accept') {
            $id = $para2;
            $data['status'] = '1';
            $this->db->where('id', $id);
            $this->db->update('review_product', $data);
            $this->session->set_flashdata('acc', 'Accepted sucessfully');

            redirect(base_url() . 'index.php/admin/review');
        } elseif ($para1 == 'reject') {
            $id = $para2;
            $data['status'] = '2';
            $this->db->where('id', $id);
            $this->db->update('review_product', $data);
            $this->session->set_flashdata('rej', 'Rejected sucessfully');

            redirect(base_url() . 'index.php/admin/review');
        } elseif ($para1 == 'list') {
            if($para2!='0'){
                $this->db->select('rp.*,p.store_id,p.product_id');
                $this->db->from('review_product as rp');
                $this->db->join('product as p', 'p.product_id = rp.product_id', 'left');
                $this->db->where('p.store_id',$para2);
                $this->db->group_by('rp.id');
             }
            // $this->db->order_by('id', 'desc');
            $page_data['all_review'] = $this->db->get('review_product')->result_array();
            $page_data['user_rights_14_0'] = $this->get_user_rights(14,0);
            $this->load->view('back/admin/review_list', $page_data);
        } else if ($para1 == 'delete') {

            $this->db->where('id', $para2);
            $this->db->delete('review_product');

            recache();
        } else {
            $page_data['page_name'] = "review";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['all_users'] = $this->db->get('user')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function store($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('category')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {

            $data['name'] = $this->input->post('name');
            $data['address1'] = $this->input->post('address1');
            $data['address2'] = $this->input->post('address2');
            $data['city'] = $this->input->post('city');
            $data['state'] = $this->input->post('state');
            $data['country'] = $this->input->post('country');
            $data['zipcode'] = $this->input->post('zipcode');
            $data['email'] = $this->input->post('email');
            $data['phone'] = $this->input->post('phone');
            $data['delivery_zipcode'] = $this->input->post('delivery_zipcode');
            $this->db->insert('store', $data);
            echo $this->db->last_query();
            $id = $this->db->insert_id();
            move_uploaded_file($_FILES["logo"]['tmp_name'], 'uploads/store_logo_image/logo_' . $id . '.png');
            recache();
        } else if ($para1 == 'edit') {
            $page_data['store_data'] = $this->db->get_where('store', array(
                'store_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/store_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['name'] = $this->input->post('name');
            $data['address1'] = $this->input->post('address1');
            $data['address2'] = $this->input->post('address2');
            $data['city'] = $this->input->post('city');
            $data['state'] = $this->input->post('state');
            $data['country'] = $this->input->post('country');
            $data['zipcode'] = $this->input->post('zipcode');
            $data['email'] = $this->input->post('email');
            $data['phone'] = $this->input->post('phone');
            $data['delivery_zipcode'] = $this->input->post('delivery_zipcode');
            $this->db->where('store_id', $para2);
            $this->db->update('store', $data);
            if ($_FILES['logo']['name'] !== '') {
                move_uploaded_file($_FILES["logo"]['tmp_name'], 'uploads/store_logo_image/logo_' . $para2 . '.png');
            }
            //	$this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'delete') {
            unlink("uploads/store_logo_image/logo_" . $para2);
            $this->db->where('store_id', $para2);
            $this->db->delete('store');
            //	$this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('store_id', 'desc');
            //$this->db->where('digital=',NULL);
            $page_data['all_store'] = $this->db->get('store')->result_array();
            $this->load->view('back/admin/store_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/store_add');
        } else {
            $page_data['page_name']      = "store";
            $page_data['all_store'] = $this->db->get('store')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    function del_orders($para1 = '', $para2 = '', $para3 = '', $para4 = '')
    {
        // print_r($_POST); exit;
        $from           = $this->input->post('fromR');
        $to           = $this->input->post('toR');
        $del_status   = $this->input->post('vendor');

        if (isset($del_status) || $del_status != '' || $del_status != 0) {
            $page_data['delstatus1'] = $del_status;
        } else {
            $page_data['delstatus1'] = 0;
        }
        if (isset($from) && $from != '') {
            $page_data['from'] = $from;
        } else {
            $page_data['from'] = $from;
        }

        if (isset($to) && $to != '') {
            $page_data['to'] = $to;
        } else {
            $page_data['to'] = $to;
        }
        if (!$this->crud_model->admin_permission('sale')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'list') {
            $from = $para2;
            $to   = $para3;
            $delss   = $para4;

            if ($delss != ''  && $delss != '0') {
                $this->db->where('store_id', $delss);
            }

            if ($from != ''  && $from != '0'   &&  $to != ''  && $to != '0') {
                $page_data['from'] = $from;
                $page_data['to'] = $to;

                $page_data['from'] = $from;

                $page_data['to1'] = $to;
                $page_data['to'] = date('Y-m-d H:i:s', strtotime($page_data['to1'] . ' +1 day'));

                //$page_data['delstatus'] = $del_status;

                $from1 = strtotime($page_data['from'] . " 00:00:00");
                $to1 = strtotime(str_replace('00:00:00', '', $page_data['to']) . " 11:59:59");

                $this->db->where('sale_datetime >=', $from1);
                $this->db->where('sale_datetime <=', $to1);
            }
            $this->db->order_by('sale_id', 'desc');
            $this->db->like('delivery_status', 'delivered');
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            //	echo $this->db->last_query();
            $this->load->view('back/admin/del_orders_list', $page_data);
        } elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/sales_view', $page_data);
        } elseif ($para1 == 'send_invoice') {
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $text              = $this->load->view('back/includes_top', $page_data);
            $text .= $this->load->view('back/admin/sales_view', $page_data);
            $text .= $this->load->view('back/includes_bottom', $page_data);
        } elseif ($para1 == 'delivery_payment') {
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
            ))->row()->delivery_status, true);
            foreach ($delivery_status as $row) {
                if (isset($row['admin'])) {
                    $page_data['delivery_status'] = $row['status'];
                } else {
                    $page_data['delivery_status'] = '';
                }
            }
            $payment_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->payment_status, true);
            foreach ($payment_status as $row) {
                if (isset($row['admin'])) {
                    $page_data['payment_status'] = $row['status'];
                } else {
                    $page_data['payment_status'] = '';
                }
            }

            $this->load->view('back/admin/sales_delivery_payment', $page_data);
        } elseif ($para1 == 'delivery_payment_set') {
            $delivery_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->delivery_status, true);
            $new_delivery_status = array();
            foreach ($delivery_status as $row) {
                if (isset($row['admin'])) {
                    $new_delivery_status[] = array('admin' => '', 'status' => $this->input->post('delivery_status'), 'delivery_time' => $row['delivery_time']);
                } else {
                    $new_delivery_status[] = array('vendor' => $row['vendor'], 'status' => $row['status'], 'delivery_time' => $row['delivery_time']);
                }
            }
            $payment_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->payment_status, true);
            $new_payment_status = array();
            foreach ($payment_status as $row) {
                if (isset($row['admin'])) {
                    $new_payment_status[] = array('admin' => '', 'status' => $this->input->post('payment_status'));
                } else {
                    $new_payment_status[] = array('vendor' => $row['vendor'], 'status' => $row['status']);
                }
            }
            $data['payment_status']  = json_encode($new_payment_status);
            $data['delivery_status'] = json_encode($new_delivery_status);
            $data['payment_details'] = $this->input->post('payment_details');
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/sales_add');
        } elseif ($para1 == 'total') {
            echo $this->db->get('sale')->num_rows();
        } else {
            $page_data['page_name']      = "del_orders";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }


    function pen_orders($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('sale')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'list') {
            //  $this->db->order_by('sale_id', 'desc');
            //$this->db->like('delivery_status', 'pending');
            //$all = $this->db->get('sale')->result_array();
            $all = $this->db->get_where('sale', array('payment_type' => 'go'))->result_array();

            foreach ($all as $row) {
                if ((time() - $row['sale_datetime']) > 600) {
                    $this->db->where('sale_id', $row['sale_id']);
                    $this->db->delete('sale');
                }
            }
            $this->db->order_by('sale_id', 'desc');
            $this->db->like('delivery_status', 'pending');
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            //$page_data['all_sales'] = $this->db->get_where('sale',array('payment_type' => 'go', 'delivery_status' =>  'delivered%'))->result_array();
            //echo $this->db->last_query();
            $this->load->view('back/admin/pen_orders_list', $page_data);
        } elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/sales_view', $page_data);
        } elseif ($para1 == 'send_invoice') {
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $text              = $this->load->view('back/includes_top', $page_data);
            $text .= $this->load->view('back/admin/sales_view', $page_data);
            $text .= $this->load->view('back/includes_bottom', $page_data);
        } elseif ($para1 == 'delivery_payment') {
        } elseif ($para1 == 'delivery_payment_set') {
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/sales_add');
        } elseif ($para1 == 'total') {
            echo $this->db->get('sale')->num_rows();
        } else {
            $page_data['page_name']      = "pen_orders";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function on_orders($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->admin_permission('sale')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'list') {
            //  $this->db->order_by('sale_id', 'desc');
            //$this->db->like('delivery_status', 'pending');
            //$all = $this->db->get('sale')->result_array();
            $all = $this->db->get_where('sale', array('payment_type' => 'go'))->result_array();

            foreach ($all as $row) {
                if ((time() - $row['sale_datetime']) > 600) {
                    $this->db->where('sale_id', $row['sale_id']);
                    $this->db->delete('sale');
                }
            }
            $this->db->order_by('sale_id', 'desc');
            $this->db->like('delivery_status', 'on_delivery');
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            //$page_data['all_sales'] = $this->db->get_where('sale',array('payment_type' => 'go', 'delivery_status' =>  'delivered%'))->result_array();
            //echo $this->db->last_query();
            $this->load->view('back/admin/on_orders_list', $page_data);
        } elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/sales_view', $page_data);
        } elseif ($para1 == 'send_invoice') {
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $text              = $this->load->view('back/includes_top', $page_data);
            $text .= $this->load->view('back/admin/sales_view', $page_data);
            $text .= $this->load->view('back/includes_bottom', $page_data);
        } elseif ($para1 == 'delivery_payment') {
        } elseif ($para1 == 'delivery_payment_set') {
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/sales_add');
        } elseif ($para1 == 'total') {
            echo $this->db->get('sale')->num_rows();
        } else {
            $page_data['page_name']      = "on_orders";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    function delivery_type($para1 = '')
    {


        if ($para1 == 'edit') {
            $page_data['user_rights_30_0'] = $this->get_user_rights(30,0);
            $data['value'] = ''; // Reset the value to empty
        $this->db->where('type', 'free_delivery');
        // $this->db->where('oid', '1');
        $this->db->update('business_settings', $data);

        $data['value'] = ''; // Reset the value to empty
         $this->db->where('type', 'delivery_fee');
     // $this->db->where('oid', '1');
        $this->db->update('business_settings', $data);

        recache();
           $this->load->view('back/admin/delivery_type_view', $page_data);

            // $page_data['category_data'] = $this->db->get_where('gr_sale', array(
            //     'category_id' => $para2
            // ))->result_array();
            // $this->load->view('back/admin/category_edit', $page_data);
        } elseif ($para1 == "update") {

            $data['value'] = $this->input->post('amount');
            $this->db->where('type', 'free_delivery');
            // $this->db->where('oid', '1');
            $this->db->update('business_settings', $data);
            $data['value'] = $this->input->post('fee');
            $this->db->where('type', 'delivery_fee');
            // $this->db->where('oid', '1');
            $this->db->update('business_settings', $data);

            recache();
        } elseif ($para1 == 'list') {
            $page_data['all_amount'] = $this->db->get('business_settings')->result_array();
            $page_data['user_rights_30_0'] = $this->get_user_rights(30,0);
            $this->load->view('back/admin/delivery_type_view', $page_data);
        } else {
            $page_data['page_name']      = "delivery_type";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['all_categories'] = $this->db->get('business_settings')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    function cod($para1 = '',$para2 = '',$para3 = '',$para4 = '',$para5 = '')
    {
        if ($para1 == 'edit') {
            $product_id=$this->input->post('product_id');
            $ch_value=($this->input->post('ch_value')=="true")?"1":"0";
            $ch=$this->db->get_where('cash_on_delivery', array('product_id' => $product_id))->row()->id;
            if($ch==""){
                $data['product_id'] = $product_id;
                $data['status'] = $ch_value;
                $this->db->insert('cash_on_delivery', $data);
                echo $this->db->insert_id();
            }else{
                $data['status'] = $ch_value;
                $this->db->where('product_id',$product_id);
                $this->db->update('cash_on_delivery', $data);
            }
        } elseif ($para1 == "update") {
            $product_id=$this->input->post('product_id');
            $ch_value=($this->input->post('ch_value')=="true")?"1":"0";
            foreach($product_id as $product_id1){
                $ch=$this->db->get_where('cash_on_delivery', array('product_id' => $product_id1))->row()->id;
                if($ch==""){
                    $data['product_id'] = $product_id1;
                    $data['status'] = $ch_value;
                    $this->db->insert('cash_on_delivery', $data);
                    echo $this->db->insert_id();
                }else{
                    $data['status'] = $ch_value;
                    $this->db->where('product_id',$product_id1);
                    $this->db->update('cash_on_delivery', $data);
                }
            }
            /* $data['value'] = $this->input->post('cod');
            $this->db->where('type', 'cod');
            $this->db->update('business_settings', $data);
            recache(); */
        } elseif ($para1 == 'list') {
            $page_data['vendor'] = $para2;
            $page_data['category'] = $para3;
            $page_data['sub_category'] = $para4;
            $page_data['product'] = $para5;
            $page_data['cod'] = $this->db->get('business_settings')->result_array();
            $this->load->view('back/admin/cod_view', $page_data);
        } else {
            if ($this->input->post() != '') {
                $page_data['vendor'] = $this->input->post('vendor');
                $page_data['category'] = $this->input->post('category');
                $page_data['sub_category'] = $this->input->post('sub_category');
                $page_data['product'] = $this->input->post('product');
            }
            $page_data['page_name']      = "cod";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['all_categories'] = $this->db->get('business_settings')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }


    function pre_order($para1 = '', $para2 = '', $para3 = '')
    {
        
        if ($para1 == 'do_add') {
            $data['start_date'] = $this->input->post('start_date');
            $data['end_date'] = $this->input->post('end_date');
            $data['description'] = $this->input->post('description');
            $data['status'] = 'ok';
            $this->db->insert('pre_order', $data);
            $id = $this->db->insert_id();
            recache();
        } else if ($para1 == 'edit') {
            $page_data['category_data'] = $this->db->get_where('pre_order', array(
                'id' => $para2
            ))->result_array();
            $this->load->view('back/admin/pre_order_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['start_date'] = $this->input->post('start_date');
            $data['end_date'] = $this->input->post('end_date');
            $data['description'] = $this->input->post('description');
            $this->db->where('id', $para2);
            $this->db->update('pre_order', $data);
       
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('id', 'desc');
            $this->db->where('status', 'ok');
            $page_data['all_categories'] = $this->db->get('pre_order')->result_array();
            $page_data['user_rights_28_0'] = $this->get_user_rights(28,0);
            $this->load->view('back/admin/pre_order_list', $page_data);
        }else if ($para1 == "status_set") {
            $val = '';
            if ($para3 == 'true') {
                $val = 'ok';
            } else if ($para3 == 'false') {
                $val = 'no';
            }
            echo $val;
            //$this->db->where('vendor_id', $para2);
            $this->db->update('pre_order', array(
                'default_set' => 'no'
            ));
            //echo $this->db->last_query();
            $this->db->where('id', $para2);
            $this->db->update('pre_order', array(
                'status' => $val
            ));
            echo $this->db->last_query();
            recache();
        }elseif ($para1 == 'delete') {
           
            $this->db->where('id', $para2);
            $this->db->delete('pre_order');
           
           // recache();
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/pre_order_add');
        }elseif ($para1 == 'cron') {
         
          date_default_timezone_set("Asia/Kuala_Lumpur");
          $cur_dt=date('Y-m-d');
          $this->db->where('status', 'ok');
          $pre_dts= $this->db->get('pre_order')->result_array();
          $s_dt=$pre_dts[0]['start_date'];
          $e_dt=$pre_dts[0]['end_date'];
          if($e_dt<$cur_dt){
           // echo "true"; exit;
            $data['status'] = 'no';
            $this->db->where('end_date<', $cur_dt);
            $this->db->update('pre_order', $data);
           // echo $this->db->last_query(); exit;

           }
           // exit;
            $this->db->where('id', $para2);
            $this->db->delete('pre_order');
           
           // recache();
        } else {
           
            $page_data['page_name']      = "pre_order";
            $page_data['view_rights']=$this->get_user_view_rights();
            $page_data['user_rights_28_0'] = $this->get_user_rights(28,0);
            $this->db->order_by('id', 'desc');
            $this->db->where('status', 'ok');
            $page_data['all_pre_order'] = $this->db->get('pre_order')->result_array();
           // echo $this->db->last_query();
            $this->load->view('back/index', $page_data);
           // $this->load->view('back/admin/pre_order', $page_data);
        }
    }

    function markup($para1 = '')
    {


        if ($para1 == 'edit') {
            $page_data['category_data'] = $this->db->get_where('gr_sale', array(
                'category_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/category_edit', $page_data);
        } elseif ($para1 == "update") {

            $data['value'] = $this->input->post('markup_fee');
            $data['status'] = $this->input->post('status');
            $this->db->where('type', 'markup_fee');

            $this->db->update('business_settings', $data);

            recache();
        } elseif ($para1 == 'list') {
            $page_data['all_amount'] = $this->db->get('business_settings')->result_array();

            $this->load->view('back/admin/markup_view', $page_data);
        } else {
            $page_data['page_name']      = "markup";
            $page_data['all_categories'] = $this->db->get('business_settings')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
}
