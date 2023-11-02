<div id="content-container">
<div class="content-wrapper-before"></div>

	<?php if ($this->session->flashdata('acc')) { ?>
    <div class="alert alert-success alert-dismissible show" role="alert">
       <?php echo $this->session->flashdata('acc') ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
          </button>
    </div>
<?php } ?>
<?php if ($this->session->flashdata('rej')) { ?>
    <div class="alert alert-danger alert-dismissible show" role="alert">
       <?php echo $this->session->flashdata('rej') ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
          </button>
    </div>
<?php } ?>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
					<div class="row">
					  <div class="col-md-12">
					    <h1 class="page-header text-overflow"><?php echo translate('manage review & rating');?></h1>
					 </div>		
					</div>
					<br>
                    <div class="col-md-12" style="border-bottom: 1px solid #ebebeb;padding: 25px 5px 5px 5px;">
					  <?php  echo form_open(base_url() . 'index.php/admin/review/', array(
                                'class' => 'form-horizontal',
                                'method' => 'post',
								'id' => 'filter-form'
                                ));
                                ?>	
						    <div class="col-md-3">
                            <?php echo $this->crud_model->select_html('vendor', 'vendor', 'name', 'edit', 'demo-chosen-select form-control', $name, '', '', '', '', '');  ?>
						    </div> 
						    <div  class="col-md-1"> 
						        <button type="submit" class="btn btn-success">Filter</	button>
                            </div>
						    
						    <div  class="col-md-1"> 
						        <button type="button" class="btn btn-info btn-refresh" onclick="refresh_filter()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
						    </div>
                            <div class="col-md-7"></div>

                            </div>
                            </br>
                    <!-- LIST -->
                    <div class="tab-pane fade active in" id="list" style="border:1px solid #ebebeb; border-radius:4px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'review';
	var list_cont_func = 'list/<?php if($vendor) { echo $vendor; } else { echo "0"; } ?>';
	var dlt_cont_func = 'delete';

    function refresh_filter(){
       
       document.getElementsByName("vendor")[0].value="";
       
       $('form#filter-form').submit();
   }
</script>
