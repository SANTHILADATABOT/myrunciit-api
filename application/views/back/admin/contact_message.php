<div id="content-container">
<div class="content-wrapper-before"></div>

    <div class="tab-base">
        <div class="panel">
            <div class="panel-body">
                <div class="tab-content">
				  <div class="row">
				    <div class="col-md-12">
					   <h1 class="page-header text-overflow"><?php echo translate('message_history');?></h1>
					</div>
					</div>
                    <div class="tab-pane fade active in" id="list" 
                        style="border:1px solid #ebebeb; border-radius:4px;">                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<span id="prod"></span>
<script>
    var base_url = '<?php echo base_url(); ?>';
    var user_type = 'admin';
    var module = 'contact_message';
    var list_cont_func = 'list';
    var dlt_cont_func = 'delete';

    function proceed(type) {
        if (type == 'to_list') {
            $(".pro_list_btn").show();
            $(".add_pro_btn").hide();
        } else if (type == 'to_add') {
            $(".add_pro_btn").show();
            $(".pro_list_btn").hide();
        }
    }
</script>

