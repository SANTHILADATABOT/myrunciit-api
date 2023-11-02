<!--CONTENT CONTAINER-->

<div id="content-container" style="padding-top:0px !important;">
    <div class="text-center pad-all">       
        <h4 class="text-lg text-overflow mar-no"><?php echo ($admin_log[0]['admin_id']!="") ? "Name :" : ""; ?> <?php echo translate($this->crud_model->get_type_name_by_id('admin',$admin_log[0]['admin_id']))?></h4>
        <hr>
    </div>


<div class="row">
    <div class="col-sm-12">
        <div class="panel-body">
            <table class="table table-striped" style="border-radius:3px;">
                <?php 
                if($admin_log) {
                    foreach($admin_log as $row)
                    { 
                ?>
                <?php if($row['description'] == 'Logout Successfully'){ ?>
                <tr>
                    <th class="custom_td">Logout Date</th>
                    <td class="custom_td"><?php echo $row['created_date']; ?></td>
                </tr>
                <?php } ?>
                <?php if($row['description'] == 'Login Successfully'){ ?>
                <tr>
                    <th class="custom_td">Login Date</th>
                    <td class="custom_td"><?php echo $row['created_date']; ?></td>                    
                </tr>
                <?php } ?>
				<?php 
                    }
                } else {
                    ?>
                    <tr>
                    <th class="custom_td">No records found</th>
                </tr>
                    <?php
                }
                ?>
            </table>
          </div>
        </div>
    </div>					
</div>					
</div>
</div>

            
<style>
.custom_td{
border-left: 1px solid #ddd;
border-right: 1px solid #ddd;
border-bottom: 1px solid #ddd;
}
</style>