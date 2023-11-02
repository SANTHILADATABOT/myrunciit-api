
<div class="information-title">
    <?php echo translate('subcribe_product_history');?>
</div>
<div class="details-wrap">                                    
    <div class="details-box orders">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo translate('product_name');?></th>
                    <th><?php echo translate('quantity');?></th>
                    <th><?php echo translate('delivery_package');?></th>
                    <th><?php echo translate('subscribed_package');?></th>
                    <th><?php echo translate('subscribed_from');?></th>
                    <th><?php echo translate('subscribe_days');?></th>
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
    function subscribe_product(page){
        if(page == 'no'){
            page = $('#page_num2').val();   
        } else {
            $('#page_num2').val(page);
        }
        var alert = $('#result2');
        alert.load('<?php echo base_url();?>index.php/home/subscribe_product/'+page,
            function(){
                //set_switchery();
            }
        );   
    }
    $(document).ready(function() {
        subscribe_product('0');
    });

</script>