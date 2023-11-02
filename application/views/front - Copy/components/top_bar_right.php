
            <ul class="list-inline">
            
            
                <li class="hidden-xs hidden-lg hidden-md hidden-sm">
                    <a href="<?php echo base_url(); ?>index.php/home/faq">
                        <?php echo translate('faq');?>
                    </a>
                </li>
                <?php
                    if($this->session->userdata('user_login')=='yes'){ 
                ?>
                 <li class="icon-user">
                    <a href="#" class="log-btn"> 
                    <img src="https://myrunciit.cryptocurrencyintegrations.com//uploads/wallet/wallet2.png" class="hea-imge">
                        <span class="cart_num1 wallet_css">
						<?php 
					$rewards=	$this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->rewards;
					if($rewards!=''){ echo $rewards;  } else { echo "0.00"; }
						 ?>
						 </span>
                    </a>
                </li>
                <?php } ?>
                <li class="dropdown">
                	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-user" style="font-size: 22px;margin-right: 5px"></i>
					<?php
                    if($this->session->userdata('user_login')!='yes'){ 
                ?>
				<span class="jum"><?php echo translate('login');?></span>
                <?php } else {?>
                <span class="jum">Account</span>
                <?php }?>
                    </a>
                	<ul role="menu" class="dropdown-menu">
                	    
                	    
                <?php
                    if($this->session->userdata('user_login')!='yes'){ 
                ?>
                <li class="icon-user">
                    <a href="<?php echo base_url(); ?>index.php/home/login_set/login" class="log-btn"> 
                        <span><?php echo translate('login');?></span>
                    </a>
                </li>
                <?php
                	if ($this->crud_model->get_type_name_by_id('general_settings','58','value') !== 'ok') {
				?>
                <li class="icon-user">
                    <a href="<?php echo base_url(); ?>index.php/home/login_set/registration">
                        <span><?php echo translate('registration');?></span>
                    </a>
                </li>
                <?php
					}else{
				?>
                
                    	<li>
                            <a href="<?php echo base_url(); ?>index.php/home/login_set/registration">
                                <span><?php echo translate('customer_registration');?></span>
                            </a>
                        </li>
                        <li style="display:none;";>
                            <a href="<?php echo base_url(); ?>index.php/home/vendor_logup/registration">
                                <span><?php echo translate('vendor_registration');?></span>
                            </a>
                        </li>
            
                <?php
					}
				?>
                <?php } else {?>
                <li class="icon-user">
                    <a href="<?php echo base_url(); ?>index.php/home/profile/" style="padding-right: 10px;">
                        <span><?php echo translate('my_profile');?></span>
                   <?php        
				   $wallet_notify= $this->db->get_where('user_log', array('uid' => $this->session->userdata('user_id'),'read_status'=>0))->result_array();;
				   
				   $wallet_notify_count=count($wallet_notify);
				   //echo '<pre>'; print_r($wallet_notify_count); exit;
				   ?>
                     <i class="fa fa-bell-o"></i><small class="notification-badge"><?php echo $wallet_notify_count; ?></small>
                    </a>
                    
                </li>
                <li class="icon-user" style="margin-left: 0;">
                    <a href="<?php echo base_url(); ?>index.php/home/profile/part/wishlist">
                        <span><?php echo translate('wishlist');?></span>
                    </a>
                </li>
                <li class="icon-user">
                    <a href="<?php echo base_url(); ?>index.php/home/logout/">
                        <span><?php echo translate('logout');?></span>
                    </a>
                </li>
                <?php }?>
                
                	</ul>
                </li>
                
                
            </ul>