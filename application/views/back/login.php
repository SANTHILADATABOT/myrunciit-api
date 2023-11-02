<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="refresh" content="300">
	<title><?php echo translate('login');?> | <?php echo $this->db->get_where('general_settings',array('type' => 'system_name'))->row()->value;?></title>

	<!--STYLESHEET-->
	<link rel="stylesheet" type="text/css" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!--Roboto Font [ OPTIONAL ]-->
	<link href="http://fonts.googleapis.com/css?family=Roboto:400,700,300,500" rel="stylesheet" type="text/css">
	<!--Bootstrap Stylesheet [ REQUIRED ]-->
	<link href="<?php echo base_url(); ?>template/back/css/bootstrap.min.css" rel="stylesheet">
	<!--Activeit Stylesheet [ REQUIRED ]-->
	<link href="<?php echo base_url(); ?>template/back/css/activeit.min.css" rel="stylesheet">	
	<!--Font Awesome [ OPTIONAL ]-->
	<link href="<?php echo base_url(); ?>template/back/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!--Demo [ DEMONSTRATION ]-->
	<link href="<?php echo base_url(); ?>template/back/css/demo/activeit-demo.min.css" rel="stylesheet">

	<!--SCRIPT-->
	<!--Page Load Progress Bar [ OPTIONAL ]-->
	<link href="<?php echo base_url(); ?>template/back/plugins/pace/pace.min.css" rel="stylesheet">
	<script src="<?php echo base_url(); ?>template/back/plugins/pace/pace.min.js"></script>
	<?php $ext =  $this->db->get_where('ui_settings',array('type' => 'fav_ext'))->row()->value; $this->benchmark->mark_time();?>
	<link rel="shortcut icon" href="<?php echo base_url(); ?>uploads/others/favicon.<?php echo $ext; ?>">

<style>
	.brt{
		font-size:17px;
		position:relative;	
	}
	
th, td {
    padding: 2px;
}
table, td, th {
    border: 2px solid #333;
    text-align: center;
}

table {
  border-collapse: collapse;
  width: 100%;
}

table th{
	background:#eee;	
}

.brt::before{
    border-bottom: 1px solid #947f7f;
    content: "";
    height: 1px;
    width:75px;
    display: inline-block;
	position:absolute;
	left:0px;
	top: 10px;
}

 .brt::after {
    border-bottom: 1px solid #947f7f;
    content: "";
    height: 1px;
    width: 75px;
    display: inline-block;
	position:absolute;
	right:0px;
	top: 10px;
}
	
</style>

</head>

<body>
	<div id="container" class="cls-container" style="background-image:url(<?php echo base_url(); ?>uploads/others/repeat.jpg);">
		<!-- BACKGROUND IMAGE -->
		<div id="bg-overlay"></div>
		<!-- LOGIN FORM -->
		<div class="cls-content">
			<div class="cls-content-sm panel panel-colorful panel-login" style="margin-top: 50px !important;">
				<div class="panel-body">
                	<a class="box-inline" href="<?php echo base_url(); ?>index.php/<?php echo $this->session->userdata('title'); ?>">
						<img src="<?php echo $this->crud_model->logo('admin_login_logo'); ?>" class="log_icon">
					</a>
					<p class="tite"><?php echo translate('sign_in_to_your_account');?></p>
					<?php
						echo form_open(base_url() . 'index.php/'.$control.'/login/', array(
							'method' => 'post',
							'id' => 'login'
						));
					?>
                   
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon lgn-i"  style="border:0;"><i class="fa fa-user" style="color:#6b6f82 !important;font-size:20px;"></i></div>
								<input type="text"  name="email"  class="form-control" placeholder="<?php echo translate('email'); ?>">
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon lgn-i"  style="border:0;"><i class="fa fa-key" style="color:#6b6f82 !important;font-size:20px;"></i></div>
								<input type="password" id="password" name="password" class="form-control" placeholder="<?php echo translate ('password'); ?>" >
								<div class="input-group-addon lgn-i"  style="border-bottom: 1px solid #9e9e9e; background: white;">
								<i style="font-size:25px;" class="ion-eye" aria-hidden="true"  id="eye" onclick="toggle()"></i></div>
						    </div>
					    </div>
						<div class="row">
                        
                        <div class="col-xs-12 text-right">
                            	<div class="pad-ver">
                                    <a href="#" onclick="ajax_modal('forget_form','<?php echo translate('forget_password'); ?>','<?php echo translate('email_sent_with_new_password!'); ?>','forget','')" class="btn-link mar-rgt ico_styl" style="color:#39a4cd !important;"><?php echo translate('forgot_password');?> ?</a>
                                </div>
							</div>
                            
                        	<div class="col-xs-12 text-center">
								<div class="form-group text-right main_login">
								<span class="btn btn-login btn-labeled fa fa-unlock-alt snbtn" onclick="form_submit('login')">
                                    	<?php echo translate('sign_in');?>
                                    </span>
								</div>
							</div>
                        
                        
							
							
						</div>
					</form>
                    
				</div>
			</div>
		</div>
	</div>
	<!--jQuery [ REQUIRED ]-->
	<script src="<?php echo base_url(); ?>template/back/js/jquery-2.1.1.min.js"></script>
    
	<!--BootstrapJS [ RECOMMENDED ]-->
	<script src="<?php echo base_url(); ?>template/back/js/bootstrap.min.js"></script>
    
	<!--Activeit Admin [ RECOMMENDED ]-->
	<script src="<?php echo base_url(); ?>template/back/js/activeit.min.js"></script>

	<!--Background Image [ DEMONSTRATION ]-->
	<script src="<?php echo base_url(); ?>template/back/js/demo/bg-images.js"></script>
    
	<!--Bootbox Modals [ OPTIONAL ]-->
	<script src="<?php echo base_url(); ?>template/back/plugins/bootbox/bootbox.min.js"></script>

	<!--Demo script [ DEMONSTRATION ]-->
	<script src="<?php echo base_url(); ?>template/back/js/ajax_login.js"></script>
	
	<script>
		var state=false;
			function toggle()
			{
				if(state)
				{
					document.getElementById('password').setAttribute("type","password");

					state= false;
				}
				else
				{
					document.getElementById('password').setAttribute("type","text");
					state =true;
				}
			}
	</script>
	
	<script>
        var base_url = "<?php echo base_url(); ?>";
        var cancdd = "<?php echo translate('cancelled'); ?>";
        var req = "<?php echo translate('this_field_is_required'); ?>";
		var sing = "<?php echo translate('signing_in...'); ?>";
		var nps = "<?php echo translate('new_password_sent_to_your_email'); ?>";
		var lfil = "<?php echo translate('login_failed!'); ?>";
		var wrem = "<?php echo translate('wrong_e-mail_address!_try_again'); ?>";
		var lss = "<?php echo translate('login_successful!'); ?>";
		var sucss = "<?php echo translate('SUCCESS!'); ?>";
		var rpss = "<?php echo translate('reset_password'); ?>";
        var user_type = "<?php echo $control; ?>";
        var module = "login";
		var unapproved = "<?php echo translate('account_not_approved._wait_for_approval.'); ?>";
		
		window.addEventListener("keydown", checkKeyPressed, false);
		function checkKeyPressed(e) {
		    if (e.keyCode == "13") {
				$('body').find(':focus').closest('form').find('.snbtn').click();
				if($('body').find('.modal-content').find(':focus').closest('form').closest('.modal-content').length > 0){
					$('body').find('.modal-content').find(':focus').closest('form').closest('.modal-content').find('.snbtn_modal').click();
				}
		    }
		}
    </script>
</body>
</html>
