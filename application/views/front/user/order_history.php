
<div class="information-title">
    <?php echo translate('my_orders');?>
</div>
<div class="details-wrap">                                    
    <div class="details-box orders">
        
            <div id="result2">
            </div>
        
    </div>
</div>

<input type="hidden" id="page_num2" value="0" />

<div class="pagination_box">

</div>

<script>
    function order_listed(page){
        if(page == 'no'){
            page = $('#page_num2').val();   
        } else {
            $('#page_num2').val(page);
        }
        var alert = $('#result2');
        alert.load('<?php echo base_url();?>index.php/home/order_listed/'+page,
            function(){
                   $('html, body').animate({ scrollTop: 0 }, 'fast');
                //set_switchery();
            }
        );   
    }
    $(document).ready(function() {
        order_listed('0');
    });

</script>