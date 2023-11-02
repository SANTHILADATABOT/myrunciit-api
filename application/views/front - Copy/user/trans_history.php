
<div class="information-title">
    <?php echo translate('your_transaction');?>
</div>
<div class="details-wrap">                                    
    <div class="details-box orders">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo translate('Transaction ID');?></th>
                    
                    <th><?php echo translate('Description');?></th>
                    <th><?php echo translate('Amount');?></th>
                    <th><?php echo translate('Status');?></th>
                    <th><?php echo translate('date');?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $trans_history=$this->db->order_by('id', 'DESC')->get_where('user_trans_log', array('user_id' => $this->session->userdata('user_id')))->result_array();
	$i = 0;
	foreach ($trans_history as $row1) {
		
		//echo '<pre>'; print_r($row1); 
		$i++;
?>
<tr>
  <td><?php echo $i; ?></td> 
  <td><?php echo $row1['ref_id']; ?></td>
  <td><?php echo $row1['description']; ?></td>
  <td><?php echo $row1['amount']; ?></td>
  <td><?php echo $row1['status']; ?></td>
  <td><?php echo date('Y-m-d',$row1['date']); ?></td>
    
</tr>
<?php } ?>
            </tbody>
        </table>
    </div>
</div>

<input type="hidden" id="page_num2" value="0" />

<div class="pagination_box">

</div>

