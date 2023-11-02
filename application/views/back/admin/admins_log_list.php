    <div class="panel-body" id="demo_s">
        <table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >
            <thead>
                <tr>
                    <th><?php echo translate('no'); ?></th>
                    <th><?php echo translate('name'); ?></th>
                    <th><?php echo translate('email'); ?></th>
                    <th><?php echo translate('phone'); ?></th>
                    <th><?php echo translate('role'); ?></th>
                    <th class="text-right"><?php echo translate('last_login'); ?></th>
                </tr>
            </thead>
            <tbody >
            <?php
				$i = 0;
                foreach($all_admins as $row){
					$i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td>
                        <div class="btn-group"> <?php echo $row['name']; ?>
                            <button class="btn btn-default" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="fa fa-chevron-circle-down pull-right" aria-hidden="true"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <li>
                                    <a class="btn btn-info btn-xs btn-labeled fa fa-location-arrow" data-toggle="tooltip" onclick="ajax_set_full('view','<?php echo translate('History'); ?>','','view_history','<?php echo $row['admin_id']; ?>'); proceed('to_list');" data-original-title="View" data-container="body"><?php echo translate('history'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('role',$row['role']); ?></td>
                    <td><?php  echo date('Y-m-d h:i A',$row['last_login']); ?></td>
                    
                </tr>
            <?php
                }
            ?>
            </tbody>
        </table>
    </div>
   
    <div id='export-div'>
        <h1 style="display:none;"><?php echo translate('staffs_log');?></h1>
        <table id="export-table" data-name='staffs_log' data-orientation='l' style="display:none;">
                <thead>
                    <tr>
                        <th><?php echo translate('no'); ?></th>
                    <th><?php echo translate('name'); ?></th>
                    <th><?php echo translate('email'); ?></th>
                    <th><?php echo translate('phone'); ?></th>
                    <th><?php echo translate('role'); ?></th>
                    <th class="text-right"><?php echo translate('last_login'); ?></th>
                    </tr>
                </thead>
                    
                <tbody >
                <?php
                    $i = 0;
                    foreach($all_admins as $row){
                        $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('role',$row['role']); ?></td>
                    <td><?php  echo date('Y-m-d',$row['last_login']); ?></td>
                </tr>
                <?php
                    }
                ?>
                </tbody>
        </table>
    </div>
           