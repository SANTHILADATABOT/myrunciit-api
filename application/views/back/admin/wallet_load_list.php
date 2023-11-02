<?php
$edit_rights=$user_rights_13_12['edit_rights'];
$delete_rights=$user_rights_13_12['delete_rights'];
?>
<div class="panel-body" id="demo_s">
    <table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" >
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo translate('customer');?></th>
                <th><?php echo translate('amount');?></th>
                <th><?php echo translate('method');?></th>
                <th><?php echo translate('status');?></th>
            </tr>
        </thead>				
        <tbody >
        <?php
            $i = 0;
            foreach($all_wallet_loads as $row){
                $det = json_decode($row['details'],true);
                $i++;
        ?>                
        <tr>    
            <td><?php echo $i; ?></td>       
            <td>						
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="text-align: left;">
                        <a class="btn btn-dark btn-xs btn-labeled fa fa-user" data-toggle="tooltip" onclick="ajax_modal('user_view','<?php echo translate('view_profile'); ?>','<?php echo translate('successfully_viewed!'); ?>','user_view','<?php echo $row['user']; ?>')" data-original-title="View" data-container="body"><?php  $lkk = $this->db->get_where('user',array('user_id'=>$row['user']))->row();echo $lkk->username.' '.$lkk->surname;?></a>
                    </div>
                    <?php if($delete_rights=='1'){ ?>
                    <div class="btn-group">
                        <!-- <button class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> -->
                            <i class="fa fa-chevron-circle-down icon-default" aria-hidden="true" id="dropdownMenu<?php echo $i; ?>" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></i>
                        <!-- </button> -->
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu<?php echo $i; ?>">
                        <li><a style="color:white;width:100%;text-align:left;" onclick="delete_confirm('<?php echo $row['wallet_load_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-xs btn-danger btn-labeled fa fa-trash" data-toggle="tooltip" data-original-title="Delete" data-container="body"><?php echo translate('delete');?></a></li>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </td>           
            <td><?php echo currency('','def').' '.$this->cart->format_number($row['amount']); ?></td>
            <td><?php echo $row['method']; ?></td>
            <td>
                <a class="btn btn-<?php if($row['status'] == 'paid'){ ?>purple<?php } else { ?>danger<?php } ?> btn-xs btn-labeled fa fa-user" data-toggle="tooltip" onclick="ajax_modal('approval','<?php echo translate('view_status'); ?>','<?php echo translate('successfully_saved!'); ?>','wallet_load_approval','<?php echo $row['wallet_load_id']; ?>')" data-original-title="View" data-container="body"><?php echo translate($row['status']); ?></a>
            </td>
        </tr>
        <?php
            }
        ?>
        </tbody>
    </table>
</div>
    <div id="vendr"></div>
    <div id='export-div' style="padding:40px;">
		<h1 id ='export-title' style="display:none;"><?php echo translate('wallet_loads'); ?></h1>
		<table id="export-table" class="table" data-name='wallet_loads' data-orientation='p' data-width='1500' style="display:none;">
				<colgroup>
					<col width="50">
					<col width="150">
					<col width="150">
                    <col width="150">
                    <col width="150">
				</colgroup>
				<thead>
					<tr>
						<th><?php echo translate('no');?></th>
                        <th><?php echo translate('amount');?></th>
                        <th><?php echo translate('method');?></th>
                        <th><?php echo translate('status');?></th>
					</tr>
				</thead>



				<tbody >
				<?php
					$i = 0;
	            	foreach($all_wallet_loads as $row){
                        $det = json_decode($row['details'],true);
	            		$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['method']; ?></td>
                    <td><?php echo $row['status']; ?></td>       	
				</tr>
	            <?php
	            	}
				?>
				</tbody>
		</table>
	</div>
           