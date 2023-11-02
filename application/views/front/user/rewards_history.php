<div class="information-title">
    <?php 
    $where = "(status='success' or status = 'admin_pending' or status = 'rejected' or status = 'cancelled') and (rewards_using='2')";
    $this->db->select('order_id,reward_using_amt,sale_datetime,refund_status');
    $this->db->where($where);
    $this->db->order_by('sale_datetime', 'desc');
    $this->db->where('buyer', $this->session->userdata('user_id'));
    $orders = $this->db->get('sale')->result_array();
    $rewards = 0;
    foreach ($orders as $row1){
        if ($row1['reward_using_amt'] != "") {
            $rewards += floatval($row1['reward_using_amt']);
        }
    }
    echo translate("used_Rewards ") . currency() . number_format((($rewards != "") ? floatval($rewards) : 0), 2);
    ?>
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
                $i = 0;
                foreach ($orders as $row1) {
                    $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td> 
                    <td><?php echo $row1['order_id']; ?></td>
                    <td><?php echo currency() . number_format(($row1['reward_using_amt'] != "" ? floatval($row1['reward_using_amt']) : 0), 2); ?></td>
                    <td><?php echo date('Y-m-d', $row1['sale_datetime']); ?></td>
                    <td><?php if ($row1['refund_status'] == '1') { echo "Refunded"; } else { echo '-'; } ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<input type="hidden" id="page_num2" value="0" />
<div class="pagination_box">
    <ul class="pagination">
        <!-- Pagination links will be dynamically generated here -->
    </ul>
</div>

<script>
$(document).ready(function() {
    var pageSize = 10; // You can change this to 25 if needed
    var table = $('.table');
    var rows = table.find('tbody tr');
    var pageCount = Math.ceil(rows.length / pageSize);
    var paginationBox = $('.pagination_box ul');
    var currentPage = 1;

    // Function to display a specific page
    function showPage(page) {
        rows.hide().slice((page - 1) * pageSize, page * pageSize).show();
    }

    // Initialize pagination with the current page size
    showPage(currentPage);

    // Function to generate pagination links
    function generatePaginationLinks() {
        paginationBox.empty();

        // Add a "Previous" button if not on the first page
        if (currentPage > 1) {
            paginationBox.append('<li><a href="#" data-page="' + (currentPage - 1) + '">&laquo; Previous</a></li>');
        }

        // Display page numbers from 1 to 10
        for (var i = 1; i <= Math.min(10, pageCount); i++) {
            paginationBox.append('<li><a href="#" data-page="' + i + '">' + i + '</a></li>');
        }

        // Add a "Next" button if not on the last page
        if (currentPage < pageCount) {
            paginationBox.append('<li><a href="#" data-page="' + (currentPage + 1) + '">Next &raquo;</a></li>');
        }
    }

    generatePaginationLinks();

    // Handle pagination link clicks
    paginationBox.on('click', 'a', function() {
        var page = parseInt($(this).data('page'));

        // Handle "Previous" and "Next" buttons
        if (page === currentPage - 1) {
            currentPage--;
        } else if (page === currentPage + 1) {
            currentPage++;
        } else {
            currentPage = page;
        }

        showPage(currentPage);
        generatePaginationLinks();
        return false;
    });
});
</script>



