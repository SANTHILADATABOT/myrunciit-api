<div id="content-container">
<div class="content-wrapper-before"></div>

	<div class="tab-base">
		<!--  -->
		<div class="panel">
			<div class="panel-body">
            <div class="row">
                <h6 class="page-header text-overflow" ><?php echo translate('manage_staffs_log');?></h6>			
            </div><br><br>
            <div class="col-md-12" >
					  <?php  echo form_open(base_url() . 'index.php/admin/admins_log/', array(
                                'class' => 'form-horizontal',
                                'method' => 'post'
                                ));
                                ?>							    
               
						    <div class="col-md-2">
						        <input type="date" name="from" class="form-control2 value="<?php echo $from ?>">
						    </div>
						    <div class="col-md-2">
						        <input type="date" name="to" class="form-control2 value="<?php echo $to ?>">
						    </div>
						    
						    <div  class="col-md-2"> 
						        <button type="submit" class="btn btn-success">Filter</button>
						    </div>
						    						    						    
						</form>
						    
					</form>
				</div>
					<br>
                <!-- LIST -->
                <div class="tab-pane fade active in" id="list">
                
                </div>
			</div>
        </div>
		<!--  -->
	</div>
</div>
<script>
	var base_url = '<?php echo base_url(); ?>';
	var user_type = 'admin';
	var module = 'admins_log';
	var list_cont_func = 'list/<?php if($from) { echo $from; } else { echo "0"; } ?>/<?php if($to) { echo $to; } else { echo "0"; } ?>';
	var dlt_cont_func = 'delete';
</script>
