
<div class="information-title">
    <?php echo translate('your_order_history');?>
</div>
<div class="details-wrap">                                    
    <div class="details-box orders">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    
                    <th><?php echo translate('Name');?></th>
                    <th><?php echo translate('Description');?></th>
                    <th><?php echo translate('date');?></th>
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
    function wallet_transfer(page){
        if(page == 'no'){
            page = $('#page_num2').val();   
        } else {
            $('#page_num2').val(page);
        }
        var alert = $('#result2');
        alert.load('<?php echo base_url();?>index.php/home/wallet_transfer/'+page,
            function(){
                //set_switchery();
            }
        );   
    }
    $(document).ready(function() {
        wallet_transfer('0');
    });

</script>