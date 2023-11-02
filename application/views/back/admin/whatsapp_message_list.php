	<div class="panel-body" id="demo_s">
	<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('whatsapp_number');?></th>
                    <th><?php echo translate('Description');?></th>
                    <th><?php echo translate('Status');?></th>
                    <th><?php echo translate('Date');?></th>
				
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i = 0;
            	foreach($all_msg as $row){
            		$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
                <td><?php echo $row['number']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo date('d F Y H:i:s',$row['date']); ?></td>
                
			</tr>
            <?php
            	}
			?>
			</tbody>
		</table>
	</div>
           
 <div id='export-div'>
        <h1 style="display:none;"><?php echo translate('Whatsapp_marketing'); ?></h1>
        <table id="export-table" data-name='Whatsapp_marketing' data-orientation='l' style="display:none;">
               	<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('whatsapp_number');?></th>
                    <th><?php echo translate('Description');?></th>
                    <th><?php echo translate('Status');?></th>
                    <th><?php echo translate('Date');?></th>
				
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i = 0;
            	foreach($all_msg as $row){
            		$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
                <td><?php echo $row['number']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo date('d F Y H:i:s',$row['date']); ?></td>
                
			</tr>
            <?php
            	}
			?>
			</tbody>
        </table>
    </div>

