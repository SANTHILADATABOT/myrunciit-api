<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Wallet_model extends CI_Model
{
    /*	
	 *	Developed by: Active IT zone
	 *	Date	: 14 July, 2015
	 *	Active Supershop eCommerce CMS
	 *	http://codecanyon.net/user/activeitezone
	 */
	 
    function __construct()
    {
        parent::__construct();
    }

    function user_balance($id=''){
        if($this->session->userdata('user_login') == 'yes' || $this->session->userdata('admin_login') == 'yes'){ 
            if($id == ''){
                $id = $this->session->userdata('user_id');
            }
            $balance = $this->db->get_where('user',array('user_id'=>$id))->row()->wallet;
            if($balance == ''){
                return '0';
            } else {
                return $balance;
            }
        }
    }

    function add_user_balance($amount,$id=''){
        if($this->session->userdata('user_login') == 'yes' || $this->session->userdata('admin_login') == 'yes'){ 
            if($id == ''){
                $id = $this->session->userdata('user_id');
            }
            $balance = $this->db->get_where('user',array('user_id'=>$id))->row()->wallet;
            $new_balance = $balance+$amount;
            //echo $new_balance;
            $this->db->where('user_id',$id);
            $this->db->update('user',array('wallet'=>$new_balance));
            return $new_balance;
        }
    }

    function reduce_user_balance($amount,$id=''){
        if($this->session->userdata('user_login') == 'yes' || $this->session->userdata('admin_login') == 'yes'){ 
            if($id == ''){
                $id = $this->session->userdata('user_id');
            }
            $balance = $this->db->get_where('user',array('user_id'=>$id))->row()->wallet;
            $new_balance = $balance-$amount;
            $this->db->where('user_id',$id);
            $this->db->update('user',array('wallet'=>$new_balance));
            return $new_balance;
        }
    }
    
    function add_reward_balance($amount,$id=''){
       // echo $id; exit;
        if($this->session->userdata('user_login') == 'yes' || $this->session->userdata('admin_login') == 'yes'){ 
            if($id == ''){
                $id = $this->session->userdata('user_id');
            }
           // echo 1; exit;
            $balance = $this->db->get_where('user',array('user_id'=>$id))->row()->rewards;
            $new_balance = $balance+$amount;
            $this->db->where('user_id',$id);
            $this->db->update('user',array('rewards'=>$new_balance));
         //   echo $this->db->last_query(); exit;
            return $new_balance;
        }
    }
    
    function reduce_reward_balance($amount,$id=''){
       // echo $id; exit;
        if($this->session->userdata('user_login') == 'yes' || $this->session->userdata('admin_login') == 'yes'){ 
            if($id == ''){
                $id = $this->session->userdata('user_id');
            }
           // echo 1; exit;
            $balance = $this->db->get_where('user',array('user_id'=>$id))->row()->rewards;
            $new_balance = $balance-$amount;
            $this->db->where('user_id',$id);
            $this->db->update('user',array('rewards'=>$new_balance));
            return $new_balance;
        }
    }
	
}






