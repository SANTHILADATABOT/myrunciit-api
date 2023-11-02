
<div class="information-title">
    <?php echo translate('your_order_history');?>
</div>
<div class="details-wrap">                                    
    <div class="details-box orders">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo translate('unique number');?></th>
                    <th><?php echo translate('Name');?></th>
                    <th><?php echo translate('Bidding Amount');?></th>
                    <th><?php echo translate('Payment Mode');?></th>
                    <th><?php echo translate('date');?></th>
                    <th><?php echo translate('status');?></th>
                </tr>
            </thead>
            <tbody id="result2">
            </tbody>
        </table>
    </div>
</div>

<input type="hidden" id="page_num2" value="0" />

<div class="pagination_box">

</div>

<script>
    function bid_listed(page){
        if(page == 'no'){
            page = $('#page_num2').val();   
        } else {
            $('#page_num2').val(page);
        }
        var alert = $('#result2');
        alert.load('<?php echo base_url();?>index.php/home/bid_listed/'+page,
            function(){
                //set_switchery();
            }
        );   
    }
    $(document).ready(function() {
        bid_listed('0');
    });

</script>