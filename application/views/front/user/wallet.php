<div id="window">
    <div class="details-wrap">
        <div class="row">
            <div class="col-md-12">
                <div class="tabs-wrapper content-tabs">
                 <div class="btn btn-theme btn-theme-sm btn-block" style="width:20%;"onclick="wallet('<?php echo base_url(); ?>index.php/home/profile/wallet/add_view')">
                    <?php echo translate('deposit_to_wallet'); ?>
                    
                  
                </div>
                
              <h4>
                Wallet : RM  <?php echo $wallt_amt[0]['wallet']; ?>
                </h4>
                </div>
                
                
                    
                    
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1">
                            <div class="wallet">
            <table class="table" style="background: #fff;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo translate('amount');?></th>
                        <th><?php echo translate('time');?></th>
                        <th><?php echo translate('details');?></th>
                        <!--<th><?php //echo translate('payment_info');?></th>-->
                    </tr>
                </thead>
                <tbody id="result6">
                </tbody>
            </table>
        </div>   
                            <input type="hidden" id="page_num6" value="0" />

                            <div class="pagination_box">

                            </div>

                             
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>                                                                    
            function wallet_listed(page){
                if(page == 'no'){
                    page = $('#page_num6').val();   
                } else {
                    $('#page_num6').val(page);
                }
                var alerta = $('#result6');
                alerta.html('<td colspan="5" align="center"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></td>');
                alerta.load('<?php echo base_url();?>index.php/home/wallet_listed/'+page,
                    function(){
                        //set_switchery();
                    }
                );   
            }
            $(document).ready(function() {
                wallet_listed('0');
            });

        </script>