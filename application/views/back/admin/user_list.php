<?php
$edit_rights=$user_rights_13_10['edit_rights'];
$delete_rights=$user_rights_13_10['delete_rights'];
?>
<div class="panel-body" id="demo_s">
<?php  echo form_open(base_url() . 'index.php/admin/update_user/', array(
                                'class' => 'form-horizontal',
                                'id' => 'update_user',
                                'method' => 'post'
                                ));
                                ?>	
    <div class="form-group col-md-6" style="display:none" id="customer_group_div">
        <div class="col-md-4">
            <label for="user_group">Customer Group</label>
            <select name="user_group" id="user_group" class="form-control" required>
                <option value="">--Select Customer Group--</option>
                <?php 
                $customer_group= $this->crud_model->customer_groups();
                foreach ($customer_group as $row){
                    ?>
                    <option value="<?php echo $row['user_group_id']; ?>"><?php echo $this->crud_model->get_type_name_by_id('user_group',$row['user_group_id'],'user_group_name')?></option>;
                <?php } ?>
            </select>
        </div>
        <input type="hidden" name="user_ids" id="user_ids" class="form-control">
        <div class="col-md-4 text-center">
            <!-- <span class="btn btn-success btn-labeled fa fa-check submitter enterer" data-ing='<?php echo translate('saving'); ?>' data-msg='<?php echo translate('customer_group_updated!'); ?>' >
                <?php echo translate('save');?>
            </span> -->
            <input type='submit' value='Save'>
        </div>
    </div>
    
    </form>
  
    <table id="demo-table" class="table table-striped" data-pagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true">        
        <thead>            
            <tr>
                <th><?php echo translate('no'); ?></th>
                <th><?php echo translate('select_customer_group'); ?>
           
    <input class='user_group_check' type="checkbox" id="select_all" onchange="selectAllCheckboxes();" />

</th>

                <th><?php echo translate('customer_group'); ?></th>
                <th><?php echo translate('image'); ?></th>
                <th><?php echo translate('name'); ?></th>
                <th><?php echo translate('email'); ?></th>
                <th><?php echo translate('phone'); ?></th>
                <th><?php echo translate('age'); ?></th>
                <th><?php echo translate('gender'); ?></th>
                <th><?php echo translate('total_purchase'); ?></th>
                <th><?php echo translate('last_purchase'); ?></th>
                <th><?php echo translate('total_rewards'); ?></th>
                <th><?php echo translate('last_purchase_date'); ?> <i class="fa fa-arrows-v" aria-hidden="true"></i></th>
                <th><?php echo translate('Created On'); ?></th>
            </tr>
        </thead>
        <tbody>

            <?php
            $i = 0;
            foreach ($all_users as $row) {
                $query = $this->db->query("SELECT group_concat(user_group_name) as ugn FROM `user_group` WHERE FIND_IN_SET ('".$row['user_id']."', user)");
                //echo "Query: " . $this->db->last_query();
                $data = array_shift($query->result_array());
                $data['ugn'] == '' ? $user_group = '-' : $user_group = $data['ugn'];
                $i++;
    
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><input class='user_group_check' name = 'user_id' type="checkbox" value="<?php echo $row['user_id']; ?>" onchange="customerGroup();" /></td>
                    <td><?php echo $user_group; ?></td>
                    <td>
                        <img class="img-sm img-circle img-border" <?php if (file_exists('uploads/user_image/user_' . $row['user_id'] . '.jpg')) { ?> src="<?php echo base_url(); ?>uploads/user_image/user_<?php echo $row['user_id']; ?>.jpg" <?php } else if ($row['fb_id'] != '') { ?> src="https://graph.facebook.com/<?php echo $row['fb_id']; ?>/picture?type=large" data-im='fb' <?php } else if ($row['g_id'] != '') { ?> src="<?php echo $row['g_photo']; ?>" <?php } else { ?> src="<?php echo base_url(); ?>uploads/user_image/no_image.png" <?php } ?> />
                    </td>
                    <td>						
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="text-align: left;">#<?php echo $row['username']; ?></div>
                            <div class="btn-group">
                                <!-- <button class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> -->
                                    <i class="fa fa-chevron-circle-down icon-default" aria-hidden="true" id="dropdownMenu<?php echo $i; ?>" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></i>
                                <!-- </button> -->
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu<?php echo $i; ?>">
                                    <li><a style="display:none;color:white;width:100%;text-align:left;" class="btn btn-mint btn-xs btn-labeled fa fa-location-arrow" data-toggle="tooltip" onclick="ajax_modal('view','<?php echo translate('view_profile'); ?>','<?php echo translate('successfully_viewed!'); ?>','user_view','<?php echo $row['user_id']; ?>')" data-original-title="View" data-container="body"><?php echo translate('profile'); ?></a></li>
                                        <?php if($edit_rights=='1'){ ?>
                                        <li><a style="color:white;width:100%;text-align:left;" class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" onclick="ajax_modal('edit','<?php echo translate('edit_user'); ?>','<?php echo translate('successfully_edited!'); ?>','user_edit','<?php echo $row['user_id']; ?>')" data-original-title="Edit" data-container="body"><?php echo translate('edit'); ?></a></li>
                                        <?php } ?>
                                        <?php if($delete_rights=='1'){ ?>
                                        <li><a style="color:white;width:100%;text-align:left;" onclick="delete_confirm('<?php echo $row['user_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-xs btn-danger btn-labeled fa fa-trash" data-toggle="tooltip" data-original-title="Delete" data-container="body"><?php echo translate('delete'); ?></a></li>
                                        <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['age']; ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td class="text-right"><?php echo currency('', 'def') . $this->crud_model->total_purchase($row['user_id']); ?></td>

                    <td><?php
                        $this->db->order_by('sale_id', 'desc');
                        $lp = $this->db->get_where('sale', array(
                            'buyer' => $row['user_id']
                        ))->result_array();
                        if ($lp[0]['sale_datetime'] != '') {
                            echo date('d M,Y', $lp[0]['sale_datetime']);
                        } else {
                            echo "-";
                        }
                        ?></td>
                    <td><?php echo $row['rewards']; ?></td>
                    <td><?php
                        $this->db->order_by('sale_id', 'desc');
                        $lp = $this->db->get_where('sale', array(
                            'buyer' => $row['user_id']
                        ))->result_array();
                        $now = time(); // or your date as well
                        $your_date = $lp[0]['sale_datetime'];
                        if ($lp[0]['sale_datetime'] != '') {
                            $datediff = $now - $your_date;

                            echo round($datediff / (60 * 60 * 24));
                        } else {
                            echo "-";
                        }
                        ?></td>
                        <td><?php echo date('d M,Y', $row['creation_date']); ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<div id='export-div' style="padding:40px;">
    <h1 id='export-title' style="display:none;"><?php echo translate('users'); ?></h1>
    <table id="export-table" class="table" data-export-types="['excel','pdf']" data-show-export="true" data-name='users' data-orientation='l' data-width='1500' style="display:none;">
        <colgroup>
            <col width="100">
            <col width="500">
            <col width="150">
            <col width="150">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="300">
        </colgroup>
        <thead>
            <tr>
                <th><?php echo translate('no'); ?></th>
                <th><?php echo translate('select_customer_group'); ?></th>
                <th><?php echo translate('customer_group'); ?></th>
                <th><?php echo translate('image'); ?></th>
                <th><?php echo translate('name'); ?></th>
                <th><?php echo translate('email'); ?></th>
                <th><?php echo translate('phone'); ?></th>
                <th><?php echo translate('age'); ?></th>
                <th><?php echo translate('gender'); ?></th>
                <th><?php echo translate('total_purchase'); ?></th>
                <th><?php echo translate('last_purchase'); ?></th>
                <th><?php echo translate('total_rewards'); ?></th>
                <th><?php echo translate('last_purchase_date'); ?></th>
                <th><?php echo translate('Created On'); ?></th>

            </tr>
        </thead>



        <tbody>
        <?php
            $i = 0;
            foreach ($all_users as $row) {
                $query = $this->db->query("SELECT group_concat(user_group_name) as ugn FROM `user_group` WHERE FIND_IN_SET ('".$row['user_id']."', user)");
                $data = array_shift($query->result_array());
                $data['ugn'] == '' ? $user_group = '-' : $user_group = $data['ugn'];
                $i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><input class='user_group_check' name = 'user_id' type="checkbox" value="<?php echo $row['user_id']; ?>" onchange="customerGroup();" /></td>
                    <td><?php echo $user_group; ?></td>
                    <td>
                    <img class="img-sm img-circle img-border" <?php if (file_exists('uploads/user_image/user_' . $row['user_id'] . '.jpg')) { ?> src="<?php echo base_url(); ?>uploads/user_image/user_<?php echo $row['user_id']; ?>.jpg" <?php } else if ($row['fb_id'] != '') { ?> src="https://graph.facebook.com/<?php echo $row['fb_id']; ?>/picture?type=large" data-im='fb' <?php } else if ($row['g_id'] != '') { ?> src="<?php echo $row['g_photo']; ?>" <?php } else { ?> src="<?php echo base_url(); ?>uploads/user_image/no_image.png" <?php } ?> />
                    </td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['age']; ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td class="text-right"><?php echo currency('', 'def') . $this->crud_model->total_purchase($row['user_id']); ?></td>

                    <td><?php
                        $this->db->order_by('sale_id', 'desc');
                        $lp = $this->db->get_where('sale', array(
                            'buyer' => $row['user_id']
                        ))->result_array();
                        if ($lp[0]['sale_datetime'] != '') {
                            echo date('d M,Y', $lp[0]['sale_datetime']);
                        } else {
                            echo "-";
                        }
                        ?></td>
                    <td><?php echo $row['rewards']; ?></td>
                    <td><?php
                        $this->db->order_by('sale_id', 'desc');
                        $lp = $this->db->get_where('sale', array(
                            'buyer' => $row['user_id']
                        ))->result_array();
                        $now = time(); // or your date as well
                        $your_date = $lp[0]['sale_datetime'];
                        if ($lp[0]['sale_datetime'] != '') {
                            $datediff = $now - $your_date;

                            echo round($datediff / (60 * 60 * 24));
                        } else {
                            echo "-";
                        }
                        ?></td>
                        <td><?php echo date('d M,Y', $row['creation_date']); ?></td>
                    
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<script>    
           $(function () {  
  $('table')  
    .on('click', 'th', function () {  
      var index = $(this).index(),  
          rows = [],  
          thClass = $(this).hasClass('asc') ? 'desc' : 'asc';  
      $('#demo-table th').removeClass('asc desc');  
      $(this).addClass(thClass);  
      $('#demo-table tbody tr').each(function (index, row) {  
        rows.push($(row).detach());  
      });  
      rows.sort(function (a, b) {  
        var aValue = $(a).find('td').eq(index).text(),  
            bValue = $(b).find('td').eq(index).text();  
        return aValue > bValue  
             ? 1  
             : aValue < bValue  
             ? -1  
             : 0;  
      });  
      if ($(this).hasClass('desc')) {  
        rows.reverse();  
      }  
      $.each(rows, function (index, row) {  
        $('#demo-table tbody').append(row);  
      });  
    });  
});  

function customerGroup() {
    if($('.user_group_check').is(":checked"))  { 
        $("#customer_group_div").show();  
        var allVals = [];
        $('input[name="user_id"]:checked').each(function() {
            allVals.unshift($(this).val());
        });
        $('#user_ids').val(allVals);
    } else {
        $("#customer_group_div").hide();
    }
}

////////////selectAllCheckBox/////////////////

    function selectAllCheckboxes() {
    var checkboxes = document.getElementsByClassName('user_group_check');
    var selectAllCheckbox = document.getElementById('select_all');
    
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = selectAllCheckbox.checked;
    }
    
    updateSelectedUserIds();
}

function updateSelectedUserIds() {
    if($('.user_group_check').is(":checked"))  { 
        $("#customer_group_div").show(); 

    var allVals = [];
    $('input[name="user_id"]:checked').each(function() {
        $("#customer_group_div").show(); 
        allVals.unshift($(this).val());
    });
    $('#user_ids').val(allVals);

}else{
    $("#customer_group_div").hide();
}

}

////////////selectAllCheckBox///////////////////////

$("#update_user").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var actionUrl = "<?php echo base_url(); ?>index.php/admin/update_user/"; 

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
            if(data=='updated'){
                window.location.reload();
            }
        }
    });

});

 </script> 