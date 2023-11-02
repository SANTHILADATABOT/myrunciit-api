<div>
    <?php
        echo form_open(base_url() . 'index.php/admin/vendor/delete/'.$vendor_id, array(
            'class' => 'form-horizontal',
            'method' => 'post',
            'id' => 'vendor_delete',
            'enctype' => 'multipart/form-data'
        ));
    ?>
        <div class="panel-body">
            
           
            
			
			<div class="form-group" id="password">
                <label class="col-sm-2 control-label" for="demo-hor-1"> </label>
                <div class="col-sm-2">
                	<h4><?php echo translate('enter_password'); ?></h4>
                </div>
                <div class="col-sm-4 text-center">
                	<input id="user_password"  data-size="switchery-lg" class='sw1 form-control' name="user_password" type="text"  />
                </div>
                <div class="col-sm-2">
                	
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        set_switchery();
    });


    $(document).ready(function() {
	    
        $("form").submit(function(e){
			//alert("PasWord");
            //return false;
        });
		 
		 
		
		
		
		$(".enterer").click(function(){
			//data_Status="";
			//if (($("input[name*='approval']:checked").length)==1) {
				user_password = $("#user_password").val();
				if(user_password==""){
					alert("Enter Password");
					return false;
				}
				else{
					
					//alert(user_password);
					//data = "{user_password:+user_password+"}";
					url = base_url+'index.php/'+user_type+'/'+module+'/password_check/';
					$.ajax({
					url: url, // form action url
					cache: false,
					dataType: "html",					
					type: 'POST',
					data:{user_password:user_password},
					beforeSend: function() {
				
					},
					success: function(data) {
					   data_Status =data;
					   //alert(data_Status);
					   if(data_Status=='Notok'){
						   //$('.approved').attr('checked', false);
						   alert("Password NotMatch Inthe User");
						   
					   }
					   else{
						   
						 alert("successfully_Deleted!");
							// your deletion code
							
					   }
					   
					   
					},
					error: function(e) {
						console.log(e)
					}
				});
				
				   
					
					//alert(data_Status);
					//	if(data_Status=='Notok'){
					//		alert("Password NotMatch Inthe User");
					//		data_Status="";
					//	    return false;
						
					// }	
				}
				
			//}
			
			
		});

    });
	
	
</script>



<div id="reserve"></div>

