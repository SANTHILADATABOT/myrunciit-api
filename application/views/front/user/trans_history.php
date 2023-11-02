<div class="information-title">
    <?php echo translate('your_transaction');?>
</div>


<div class="details-wrap">
    <div class="details-box orders">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo translate('Order ID');?></th>
                    <th><?php echo translate('Transaction ID');?></th>
                    <th><?php echo translate('Order Type');?></th>
                    <th><?php echo translate('Amount');?></th>
                    <th><?php echo translate('Status');?></th>
                    <th><?php echo translate('date');?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $this->db->select('order_id, store_id, MAX(sale_datetime) as sale_datetime,max(payment_Details) as payment_Details,max(order_type) as order_type, SUM(grand_total) as grandtotal, MAX(discount) as discount, MAX(lalamove_res) as lalamove_res,max(status) as status, MAX(sale_id) as sale_id');
                $this->db->where('buyer', $this->session->userdata('user_id'));
                $this->db->where('payment_type','ipay88');
                $this->db->group_by('order_id, store_id');
                $this->db->order_by('sale_id', desc);
                $result = $this->db->get('sale');
                $trans_history = $result->result_array();
                $i = 0;
                foreach ($trans_history as $row1) {
                    $i++;
                    $paymentDetails = json_decode($row1['payment_Details'], true);
                    
                    $price = $row1['grandtotal'];
                    $discount_details = $row1['discount'];
                    $delivery_charge=0;
                    if($row1['lalamove_res']!="")
                    {
                        $lalamove_res = json_decode($row1['lalamove_res'],true);
                        foreach($lalamove_res as $key=>$value)
                        {
                            if($value!="")
                            {
                                $lalamove_res1 = json_decode($value,true);
                                if($lalamove_res1['data']['priceBreakdown']['total']!="")
                                { $delivery_charge+=floatval($lalamove_res1['data']['priceBreakdown']['total']);}
                            }
                        }
                    }
                    $total = $delivery_charge+$price-$discount_details;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo  $row1['order_id']; ?></td>
                    <td><?php echo $paymentDetails['TransId']; ?></td>
                    <td><?php echo $row1['order_type']; ?></td>
                    <td><?php echo currency().number_format($total,2); ?></td>
                    <td><?php echo $row1['status']; ?></td>
                    <td><?php echo date('Y-m-d', $row1['sale_datetime']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div class="pagination_box">
    <ul class="pagination">
        <!-- Pagination links will be dynamically generated here -->
    </ul>
</div>

<script>
$(document).ready(function() {
    var table = $('.table');
    var rows = table.find('tbody tr');
    var paginationBox = $('.pagination_box ul');
    var currentPage = 1;

    // Function to display a specific page
    function showPage(page, pageSize) {
        rows.hide().slice((page - 1) * pageSize, page * pageSize).show();
    }

    // Function to generate pagination links
    function generatePaginationLinks(pageSize, rowCount) {
        paginationBox.empty();
        var pageCount = Math.ceil(rowCount / pageSize);

        // Add a "Previous" button if not on the first page
        if (currentPage > 1) {
            paginationBox.append('<li><a href="#" data-page="' + (currentPage - 1) + '">&laquo; Previous</a></li>');
        }

        // Display page numbers
        for (var i = 1; i <= pageCount; i++) {
            paginationBox.append('<li><a href="#" data-page="' + i + '">' + i + '</a></li>');
        }

        // Add a "Next" button if not on the last page
        if (currentPage < pageCount) {
            paginationBox.append('<li><a href="#" data-page="' + (currentPage + 1) + '">Next &raquo;</a></li>');
        }
    }

    // Calculate pageSize based on the count of rows returned by the query
    var rowCount = <?php echo count($trans_history); ?>; // Modify this line to get the actual count

    // You can also set the initial pageSize based on rowCount if needed
    var pageSize = 10; // Set an initial value

    showPage(currentPage, pageSize);
    generatePaginationLinks(pageSize, rowCount);

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

        showPage(currentPage, pageSize);
        generatePaginationLinks(pageSize, rowCount);
        return false;
    });
});
</script>



