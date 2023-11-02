		


<div class="information-title">
    <?php 
   
$where = '(status="success" or status = "admin_pending" or status = "rejected" or status = "cancelled")';
$this->db->where($where);
$this->db->where('rewards !=','');
$this->db->where('rewards !=','0.00');
$this->db->where('buyer', $this->session->userdata('user_id'));
$this->db->select_sum('rewards');
$rewards = $this->db->get('sale')->result_array();
//echo $this->db->last_query();
//print_r($rewards);
echo "Rewards RM  ".($rewards[0]['rewards']);?>
</div>
<div class="details-wrap">                                    
    <div class="details-box orders">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo translate('Order ID');?></th>
                    
                    <th><?php echo translate('rewards');?></th>
                    
                    <th><?php echo translate('date');?></th>
                    <th><?php echo translate('note');?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
//echo "yy"; exit;
$where = '(status="success" or status = "admin_pending" or status = "rejected" or status = "cancelled")';
$this->db->where($where);
$this->db->where('rewards !=','');
$this->db->where('rewards !=','0.00');
$this->db->where('buyer', $this->session->userdata('user_id'));
$orders = $this->db->get('sale')->result_array();
//echo $this->db->last_query();
foreach ($orders as $row1) {
//print_r($row1); exit;

$i++;		

?>	
                
<tr>
  <td><?php echo $i; ?></td> 
  <td><?php echo $row1['order_id']; ?></td>
  <td><?php echo "RM ".$row1['rewards']; ?></td>
  
  <td><?php echo date('Y-m-d',$row1['sale_datetime']); ?></td>
  <td><?php if($row1['refund_status']=='1'){ echo "Refunded"; }?></td>
    
</tr>
<?php } ?>
            </tbody>
        </table>
    </div>
</div>

<input type="hidden" id="page_num2" value="0" />

<div class="pagination_box">

</div>


           

                                             






