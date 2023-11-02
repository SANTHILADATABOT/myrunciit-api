<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    //overview
     function Sales_Breakdown_by_Days($start_dt='',$end_dt='',$vendor_id = '')
     {
        $this->load->database();
        $this->db->select('DATE_FORMAT(date(FROM_UNIXTIME(sale_datetime)),\'%d-%m-%Y\') AS sale_date,store_id,sum(grand_total) as grand_total1');
        $this->db->where('cancel_status', '0');
        $this->db->where_not_in('status', array('pending', 'failed', 'rejected'));
        $this->db->group_by('sale_date,store_id');
        $this->db->order_by('sale_date,store_id', 'asc');
        if($start_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale_datetime))>=\''.$start_dt.'\'');}
        if($end_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale_datetime))<=\''.$end_dt.'\'');}
        if($vendor_id!=''){$this->db->where_in('store_id', explode(",",$vendor_id));}
        return $this->db->get('sale')->result_array();
    }
    function overview_visitors_breakdown_by_days($start_dt='',$end_dt='',$vendor_id = '',$visitors_id = '')
    {
        $this->load->database();
        $this->db->select('DATE_FORMAT(date(FROM_UNIXTIME(sale_datetime)),\'%d-%m-%Y\') AS sale_date,store_id,count(distinct buyer) as total_visitors');
        $this->db->group_by('sale_date,store_id');
        $this->db->order_by('sale_date,store_id', 'asc');
        $this->db->where("cancel_status=0 and status not in ('pending','failed','rejected')");
        if($start_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale_datetime))>=\''.$start_dt.'\'');}
        if($end_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale_datetime))<=\''.$end_dt.'\'');}
        if($vendor_id!=''){$this->db->where_in('store_id', explode(",",$vendor_id));}
        if($visitors_id!=''){$this->db->where_in('buyer', explode(",",$visitors_id));}
        return $this->db->get('sale')->result_array();
    }
    //sales
    function sales_analytics($start_dt='',$end_dt='')
    {
        $this->load->database();
        $res=[];
        $this->db->select('sum(grand_total) as grand_total1');
        $this->db->where("cancel_status=0 and status not in ('pending','failed','rejected')");
        if($start_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale_datetime))>=\''.$start_dt.'\'');}
        if($end_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale_datetime))<=\''.$end_dt.'\'');}
        $res['total_sales']=$this->db->get('sale')->row()->grand_total1;
        
        $this->db->select('count(sale_id) as total_orders');
        $this->db->where("cancel_status=0 and status not in ('pending','failed','rejected')");
        if($start_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale_datetime))>=\''.$start_dt.'\'');}
        if($end_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale_datetime))<=\''.$end_dt.'\'');}
        $res['total_orders']=$this->db->get('sale')->row()->total_orders;

        $res['average_order_value']=floatval($res['total_sales'])/floatval($res['total_orders']);
        
        $this->db->select('DATE_FORMAT(date(FROM_UNIXTIME(sale_datetime)),\'%Y-%m-%d\') AS sale_date,sum(grand_total) as grand_total1,count(sale_id) as total_orders,(sum(grand_total)/count(sale_id)) as average_order_value');
        $this->db->group_by('sale_date');
        $this->db->order_by('sale_date', 'asc');
        $this->db->where("cancel_status=0 and status not in ('pending','failed','rejected')");
        if($start_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale_datetime))>=\''.$start_dt.'\'');}
        if($end_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale_datetime))<=\''.$end_dt.'\'');}
        $sales_list=$this->db->get('sale')->result_array();
        $total_sales_list=[];
        foreach($sales_list as $sales_list1){
            $total_sales_list[date('d-m-Y',strtotime($sales_list1['sale_date']))]=['grand_total1'=>$sales_list1['grand_total1'],'average_order_value'=>$sales_list1['average_order_value'],'total_orders'=>$sales_list1['total_orders']];
        }
        //ksort($total_sales_list);
        $res['total_sales_list']=$total_sales_list;
        return $res;
    }
    function top_15($a_title='',$a_qty='', $a_subtotal = '')
    {
        $s=array();
        $this->db->select('*');
        $this->db->from('vendor');
        $this->db->join('sale', 'sale.store_id = vendor.vendor_id');
        $this->db->where('sale.cancel_status', '0');
        $this->db->where_not_in('sale.status', array('pending', 'failed', 'rejected'));
        $this->db->or_where('vendor.status', 'approved');
        $this->db->group_by('sale.store_id');
        $this->db->order_by('sale.store_id', 'desc');
        // $allSales = $this->db->get()->result_array();
        $allSales = $this->db->get();
        $data = array();
        foreach ($allSales as $allSale) {
            $product_details = json_decode($allSale['product_details'], true);
            foreach ($product_details as $product) {
                $id = $product['id'];
                $qty = $product['qty'];
                $subtotal = $product['subtotal'];
                $this->db->select('title');
                $this->db->from('product');
                $this->db->where('product_id', $id);
                $productTitle = $this->db->get()->row('title');
                if(isset($data[$id])){
                    $data[$id]['qty'] += $qty;
                    $data[$id]['subtotal'] += $subtotal;
                }
                else{
                    $data[] = ['name' =>$productTitle, 'qty' => $qty, 'subtotal' => $subtotal];
                }
            }
            $s[] = ['product_details' => $product_details];
        }
        if(($a_title=='') && ($a_qty=='') && ($a_subtotal=='')) {
            return $data;
        }
        else
        {
            $filter_1=explode(',',$a_title);
            $filter_2=explode(',',$a_qty);
            $filter_3=explode(',',$a_subtotal);
            $filteredData =[];
            foreach ($data as $key => $value) {
                if (in_array($value['name'],$filter_1) || in_array($value['qty'],$filter_2) || in_array($value['subtotal'],$filter_3)) {
                    $filteredData[$key] =$value;
                }
            }
            return $filteredData;
        }
    }
    function Sales_by_Stores_by_Selected_Dates()
    {
        $this->db->select('sale.store_id, SUM(sale.order_amount) AS total_amount, COUNT(sale.grand_total) AS number_of_orders');
        $this->db->from('vendor');
        $this->db->join('sale', 'sale.store_id = vendor.vendor_id');
        $this->db->where('sale.cancel_status', '0');
        $this->db->where_not_in('sale.status', array('pending', 'failed', 'rejected'));
        $this->db->group_by('sale.store_id');
        $this->db->order_by('sale.store_id', 'desc');
        $allSales = $this->db->get()->result_array();
        $s_store=[];
        foreach ($allSales as $allSale)
        {
            $s_id = $allSale['store_id'];
            $this->db->select('name');
            $this->db->from('vendor');
            $this->db->where('vendor_id', $s_id);
            $s_name = $this->db->get()->row('name');
            $s_store[] = ['name' => $s_name, 'number_of_orders' => $allSale['number_of_orders'], 'total_amount'=>number_format($allSale['total_amount'], 2)];
        }
        return $s_store;
        /* $st_name='',$n_orders='',$t_sales=''
        if(($st_name=='') && ($n_orders=='') && ($t_sales=='')) {
            return $s_store;
        }
        else
        {
            $filter_1=explode(',',$st_name);
            $filter_2=explode(',',$n_orders);
            $filter_3=explode(',',$t_sales);
            $filteredData =[];
            foreach ($s_store as $key => $value) {
                if (in_array($value['name'],$filter_1) || in_array($value['number_of_orders'],$filter_2) || in_array($value['total_amount'],$filter_3)) {
                    $filteredData[$key] =$value;
                }
            }
            return $filteredData;
        } */
    }
    function sales_by_order_type($order_type,$start_dt='',$end_dt='')
    {
        $this->load->database();
        $cond=" where cancel_status=0 and status not in ('pending','failed','rejected')";
        if($start_dt!=''){$cond.=" and date(FROM_UNIXTIME(sale_datetime))>='".$start_dt."'";}
        if($end_dt!=''){$cond.=" and date(FROM_UNIXTIME(sale_datetime))<='".$end_dt."'";}
        if($order_type=='')
        {$col="IF(order_type='pickup',sum(grand_total),0) as total_pickup,IF(order_type='delivery',sum(grand_total),0) as total_delivery,IF(order_type='shopping',sum(grand_total),0) as total_shopping,IF((order_type!='pickup' and order_type!='delivery' and order_type!='shopping'),sum(grand_total),0) as total_others";}
        else
        {
            if($order_type=='pickup')
            {$col="IF(order_type='pickup',sum(grand_total),0) as total_pickup,0 as total_delivery,0 as total_shopping,0 as total_others";}
            else if($order_type=='delivery')
            {$col="0 as total_pickup,IF(order_type='delivery',sum(grand_total),0) as total_delivery,0 as total_shopping,0 as total_others";}
            else if($order_type=='shopping')
            {$col="0 as total_pickup,0 as total_delivery,IF(order_type='shopping',sum(grand_total),0) as total_shopping,0 as total_others";}
            else
            {$col="0 as total_pickup,0 as total_delivery,0 as total_shopping,IF((order_type!='pickup' and order_type!='delivery' and order_type!='shopping'),sum(grand_total),0) as total_others";}
        }
        $list=$this->db->query("select DATE_FORMAT(date(FROM_UNIXTIME(sale_datetime)),'%Y-%m-%d') AS sale_date,".$col." from sale".$cond." group by sale_date ORDER BY sale_date ASC");
        $sales_by_order=[];
        foreach($list as $list1){
            $sales_by_order[date('d-m-Y',strtotime($list1['sale_date']))]=['total_pickup'=>$list1['total_pickup'],'total_delivery'=>$list1['total_delivery'],'total_shopping'=>$list1['total_shopping'],'total_others'=>$list1['total_others']];
        }
        //ksort($sales_by_order);
        return $sales_by_order;
    }

    function overview_sales_over_time($start_dt='',$end_dt='',$vendor_id = '')
    {
        $this->load->database();
        $this->db->select('DATE_FORMAT(date(FROM_UNIXTIME(sale.sale_datetime)),\'%d-%m-%Y\') AS sale_date,vendor.name,sum(sale.grand_total) as grand_total1');
        $this->db->group_by('sale_date,vendor.name');
        $this->db->order_by('sale_date,vendor.name', 'asc');
        $this->db->where("sale.cancel_status=0 and sale.status not in ('pending','failed','rejected')");
        if($start_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale.sale_datetime))>=\''.$start_dt.'\'');}
        if($end_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale.sale_datetime))<=\''.$end_dt.'\'');}
        if($vendor_id!=''){$this->db->where_in('sale.store_id', explode(",",$vendor_id));}
        $this->db->join('vendor','sale.store_id=vendor.vendor_id');
        return $this->db->get('sale')->result_array();
    }
    function overview_visitors_shops_overview($vendor_id = '',$visitors_id = '',$start_dt='',$end_dt='')
    {
        $this->load->database();
        $this->db->select('sale.store_id,vendor.name,count(distinct sale.buyer) as total_visitors');
        $this->db->group_by('sale.store_id');
        $this->db->order_by('sale.store_id', 'asc');
        $this->db->where("sale.cancel_status=0 and sale.status not in ('pending','failed','rejected')");
        if($vendor_id!=''){$this->db->where_in('sale.store_id', explode(",",$vendor_id));}
        if($visitors_id!=''){$this->db->where_in('sale.buyer', explode(",",$visitors_id));}
        if($start_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale.sale_datetime))>=\''.$start_dt.'\'');}
        if($end_dt!=''){$this->db->where('date(FROM_UNIXTIME(sale.sale_datetime))<=\''.$end_dt.'\'');}
        $this->db->join('vendor','sale.store_id=vendor.vendor_id');
        return $this->db->get('sale')->result_array();
    }
    //customers
    function customers()
    {
        $this->load->database();
        $this->db->select('username as first_name,surname as last_name,email,phone,(select count(sale_id) from sale where buyer=user.user_id) as no_of_orders,(select sum(grand_total) from sale where buyer=user.user_id) as total_spent,(select max(DATE_FORMAT(date(FROM_UNIXTIME(sale_datetime)),\'%d-%m-%Y\')) from sale where buyer=user.user_id) as days_since_last_purchase,IF((select count(subscribe_id) from subscribe where email=user.email)>0,\'Yes\',\'No\') as subscribed');
        $customers_list=$this->db->get('user')->result_array();
		$cur_date = new DateTime(date('d-m-Y'));
        for($i1=0;$i1<count($customers_list);$i1++){
            if($customers_list[$i1]['days_since_last_purchase']!=""){
                $last_purchase = new DateTime(date('d-m-Y',strtotime($customers_list[$i1]['days_since_last_purchase'])));
                $customers_list[$i1]['days_since_last_purchase']=intval($cur_date->diff($last_purchase)->format("%a"));
            }else{
                $customers_list[$i1]['days_since_last_purchase']="No Purchase";
            }
        }
        return $customers_list;
    }
    // vigneshwaran
    function Sales_by_order_value($start_dt='',$end_dt='')
    {
        // $this->load->database();
        // $this->db->select('select sale.store_id as store_id, sale.order_id, COUNT(sale.order_id) as number_of_orders');
        // $this->db->from('vendor');
        // $this->db->join('sale', 'sale.store_id = vendor.vendor_id');
        // $this->db->where('sale.cancel_status', '0');
        // $this->db->where('sale.status' != 'pending');
        // $this->db->where('sale.status' != 'failed');
        // $this->db->where('sale.status' != 'rejected');
        // $this->db->groupby('sale.stor_id');
        // $this->db->order_by('sale.store_id', 'desc');
        // return $this->db->get()->result_array();
        // return 'test';
        // exit();
            $this->load->database();
            $this->db->select('sale.store_id as store_id, sale.order_id, COUNT(sale.order_id) as number_of_orders');
            $this->db->from('vendor');
            $this->db->join('sale', 'sale.store_id = vendor.vendor_id');
            $this->db->where('sale.cancel_status', '0');
            if($start_dt!=''){
                $from = strtotime($start_dt . ' 00:00:00');
                $this->db->where('sale_datetime >=', $from);
            }
            if($end_dt!=''){
                $to = strtotime($end_dt . ' 23:59:59');
                $this->db->where('sale_datetime <=', $to);
            }
            $this->db->where_not_in('sale.status', array('pending', 'failed', 'rejected')); // Corrected usage
            $this->db->group_by('sale.store_id'); // Corrected typo
            $this->db->order_by('sale.store_id', 'desc');
            return $this->db->get();
            




    }

    function splitNo($min,$max)
    {
        $diff=$max-$min;
        $start=0;$end=0;
        $sp1=1;$sp2=0;$split=0;
        while($diff >= $sp1)
        {
            if($diff <= ($sp1*10))
            {
              $sp1_1=$sp1;$sp1_2=($sp1*10);
              $split=($sp1/2);
              for($i1=$sp1_2;$i1>=$sp1_1;$i1-=$split)
              {if($i1-$split < $max){$end=$i1;break;}}
              break;
            }
            $sp1*=10;
        }
        $numParts = ($end/$split);
        if ($numParts <= 0){return [];}
        else
        {
            $range = $end - $start;
            $partSize = $range / $numParts;
            $divisions = [];
            for ($i = 0; $i < $numParts; $i++) {
                $partStart = $start + ($i * $partSize);
                $partEnd = $partStart + $partSize;
                $divisions[] = [$partStart,$partEnd];
            }
            return $divisions;
        }
    }
    function Sales_by_order_value_filter($start_dt='',$end_dt='')
    {
        $this->load->database();
        $cond=" where cancel_status=0 and status not in ('pending','failed','rejected')";
        if($start_dt!=''){$cond.=" and date(FROM_UNIXTIME(sale_datetime))>='".$start_dt."'";}
        if($end_dt!=''){$cond.=" and date(FROM_UNIXTIME(sale_datetime))<='".$end_dt."'";}
        $this->db->query("select grand_total from sale".$cond);
        $sale1 = $this->db->get();
        $sale_min=0;$sale_max=0;
        foreach($sale1 as $sale2){
            $val=($sale2['grand_total']!="")?floatval($sale2['grand_total']):0;
            if($sale_min>$val){$sale_min=$val;}
            if($sale_max<$val){$sale_max=$val;}
        }
        $divisions = $this->splitNo($sale_min,$sale_max);
        if(count($divisions)>0){
            $col="";
            for($i1=0;$i1<count($divisions);$i1++){
                $col.=($i1>0?",":"")."IF(grand_total>='".$divisions[$i1][0]."' and grand_total<='".$divisions[$i1][1]."',COUNT(order_id),0) as order_value".($i1+1);
            }
            $list=$this->db->query("select $col from sale $cond");
            $list = $this->db->get();
            if(count($list)>0){
                $Sales_by_order_value_list=[];
                for($i1=0;$i1<count($divisions);$i1++){
                    $Sales_by_order_value_list['RM'.$divisions[$i1][0].'-'.$divisions[$i1][1]]=$list[0]['order_value'.($i1+1)];
                }
                return $Sales_by_order_value_list;
            }
            else{return [];}
        }
        else{return [];}
    }

}