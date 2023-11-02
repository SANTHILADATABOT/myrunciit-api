<div id="content-container">
<div class="content-wrapper-before"></div>

	<div id="page-title">
		<h1 class="page-header text-overflow" ><?php echo translate('manage_customer');?></h1>
	</div>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
			    <div class="col-md-12" style="border-bottom: 1px solid #ebebeb;padding: 25px 5px 5px 5px;">
					  <?php  echo form_open(base_url() . 'index.php/admin/user/', array(
                                'class' => 'form-horizontal',
                                'method' => 'post'
                                ));
                                ?>	
						    <div class="col-md-2">
						        <?php echo $this->crud_model->select_html('user','user','username','edit','demo-chosen-select form-control',$user,'','','','','');  ?>
						    </div>
						    <div class="col-md-2">
						        <select name="mode" class="form-control">
                   <option value="0">rewards</option>
                   <option value="low" <?php if($mode == 'low'){ echo 'selected="selected"'; }?>>low to high</option>
                   <option value="high" <?php if($mode == 'high'){ echo 'selected="selected"'; }?> >high to low</option>
               </select>
               </div>
			   
						    
               
						    <div class="col-md-2">
						        <input type="date" name="from" class="form-control" value="<?php echo $from ?>">
						    </div>
						    <div class="col-md-2">
						        <input type="date" name="to" class="form-control" value="<?php echo $to ?>">
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
	</div>
</div>
<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'user';
	var list_cont_func = 'list/<?php if($user) { echo $user; } else { echo "0"; } ?>/<?php if($from) { echo $from; } else { echo "0"; } ?>/<?php if($to) { echo $to; } else { echo "0"; } ?>/<?php if($mode) { echo $mode; } else { echo "0"; } ?>';
	var dlt_cont_func = 'delete';
</script>
