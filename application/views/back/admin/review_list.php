<style>
    .acc1 {
        display: block;
        background: green;
        padding: 3px 2px;
        color: white;
        font-size: 16px;
        /*	width: 37%; */
        text-align: center;
        border-radius: 7px;
        font-size: 14px;
        margin-right: 5px;
    }

    .rej1 {
        display: block;
        background: red;
        padding: 5px;
        color: white;
        font-size: 16px;
        /*	width: 37%; */
        text-align: center;
        border-radius: 7px;
        font-size: 14px;
        margin-right: 5px;
    }
</style>
<?php
$delete_rights=$user_rights_14_0['delete_rights'];
?>
<div class="panel-body" id="demo_s">
    <table id="demo-table" class="table table-striped" data-pagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true">
        <colgroup>
            <col width="100">
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
                <th><?php echo translate('order_id'); ?></th>
                <th><?php echo translate('product_name'); ?></th>
                <th><?php echo translate('user_name'); ?></th>
                <th><?php echo translate('rating'); ?></th>
                <th><?php echo translate('review'); ?></th>
                <th><?php echo translate('review_date'); ?></th>
                <th><?php echo translate('options'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($all_review as $row) {
                $i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('product', $row['product_id'], 'title'); ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('user', $row['user_id'], 'username'); ?></td>
                    <td><?php echo $row['rating']; ?><i class="fa fa-star" aria-hidden="true"></i></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo date("d-m-Y",strtotime($row['created_date'])); ?></td>
                    <td>
                        <?php if ($row['status'] == 0) { ?>
                            <a class="btn btn-success btn-xs btn-labeled fa fa-check" href="<?php echo base_url(); ?>admin/review/accept/<?php echo $row['id']; ?>">
                                <?php echo translate('accept'); ?>
                            </a>
                            <a class="btn btn-danger btn-xs btn-labeled fa fa-ban" href="<?php echo base_url(); ?>admin/review/reject/<?php echo $row['id']; ?>">
                                <?php echo translate('reject'); ?>
                            </a>
                        <?php } elseif ($row['status'] == 1) {  ?>
                            <span class="acc1">Accepted</span>
                        <?php } elseif ($row['status'] == 2) { ?>
                            <span class="rej1">Rejected</span>
                        <?php } ?>
                        <?php if($delete_rights=='1'){ ?>
                        <a onclick="delete_confirm('<?php echo $row['id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-xs btn-danger btn-labeled fa fa-trash" data-toggle="tooltip" data-original-title="Delete" data-container="body">
                            <?php echo translate('delete'); ?>
                        </a>
                        <?php } ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<div id='export-div' style="padding:40px;">
    <h1 id='export-title' style="display:none;"><?php echo translate('review_list'); ?></h1>
    <table id="export-table" class="table" data-export-types="['excel','pdf']" data-show-export="true" data-name='users' data-orientation='p' data-width='1500' style="display:none;">
        <colgroup>
            <col width="50">
            <col width="300">
            <col width="300">
            <col width="300">
            <col width="150">
            <col width="500">
        </colgroup>
        <thead>
            <tr>
                <th><?php echo translate('no'); ?></th>
                <th><?php echo translate('order_id'); ?></th>
                <th><?php echo translate('product_name'); ?></th>
                <th><?php echo translate('user_name'); ?></th>
                <th><?php echo translate('rating'); ?></th>
                <th><?php echo translate('review'); ?></th>
            </tr>
        </thead>



        <tbody>
            <?php
            $i = 0;
            foreach ($all_review as $row) {
                $i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('product', $row['product_id'], 'title'); ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('user', $row['user_id'], 'username'); ?></td>
                    <td><?php echo $row['rating']; ?><i class="fa fa-star" aria-hidden="true"></i></td>
                    <td><?php echo $row['description']; ?></td>
                    

                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>