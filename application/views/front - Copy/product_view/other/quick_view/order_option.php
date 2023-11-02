<!--specialagentV1-->
<?php
	echo form_open('', array(
		'method' => 'post',
		'id'=>'pro_order',
		'name'=>'pro_order',
		'class' => 'sky-form',
	));
	//echo '<pre>'; print_r($multiple_option); exit;
	$otherOptionss = json_decode($multiple_option[$as1]['other_option'],true);
	//echo '<pre>'; print_r($otherOptionss);
	//echo $otherOptionss['color'];
	
	$all_op = json_decode($row['options'],true); //print_r($all_op);
//	echo count($all_op); 
    $all_c = json_decode($row['color'],true);
	$all_cqty = json_decode($row['color_qty'],true);
	
?>
    <div class="order">	
        <div class="buttons">
        <input type="hidden" id="optionCount" name="optionCount" value="<?php echo count($all_op); ?>" />
        <input type="hidden" id="product_id" name="product_id" value="<?php echo $row['product_id']; ?>" />
            <?php if($all_c) {  ?>
            <div class="options hidden">
                <h3 class="title"><?php echo translate('color_:');?></h3>
                <div class="content">
                    <ul class="list-inline colors">
                        <?php
                            $n = 0;
							$p1=0;
                            foreach($all_c as $i => $p){
								
                                $c = '';
                                $n++;
                                if($a = $this->crud_model->is_added_to_cart($row['product_id'],'option','color')){
                                    if($a == $p){
                                        $c = 'checked';
                                    }
                                } else {
                                    if($n == 1){
                                        $c = 'checked';
                                    }
                                }
                        ?> 

                        <input type="radio"  style="display:none;" id="c1-<?php echo $i; ?>"  <?php if(isset($otherOptionss['color']) && $otherOptionss['color']==$p ) { ?> checked="checked" <?php } ?> value="<?php echo $p; ?>" name="color_qty"  >
                         <li style="margin-left:10%;">
                         <?php //echo $otherOptionss['color'].'-'.$p; ?>
                         
                         <input onclick="return samedo(<?php echo $i; ?>), pricevariant();" type="radio" style="display:none;" id="c-<?php echo $i; ?>"  value="<?php echo $p; ?>" <?php echo $c; ?> name="color"  >
                          <label style="background:<?php echo $p; ?>;" for="c-<?php echo $i; ?>" ></label>
                         <?php //if($all_cqty[$p1]!='') { ?><!-- <br/>--><?php //echo $all_cqty[$p1]; ?> <!--Qty--> <?php //} ?>                            </li>  
                        <?php
						$p1++;
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <?php } ?>
            
            <?php
                if($all_op)
				{
                    foreach($all_op as $i=>$row1)
					{
                        $type = $row1['type'];
                        $name = $row1['name'];
                        $title = $row1['title'];
                        $option = $row1['option'];
            ?>
            <div class="options">
                <h3 class="title"><?php echo $title.' :';?></h3>
                <div class="">
                <?php
                    if($type == 'radio')
					{
                ?>
                    <div class="custom_radio">
                    <?php
                        $i=1;
                        foreach ($option as $op) 
						{
							//echo $title;
                    ?>
                      <input type="radio" onclick="return  pricevariant();" class="optional" name="<?php echo $name;?>" <?php if(isset($otherOptionss[str_replace(" ",'_',$title)]) && $otherOptionss[str_replace(" ",'_',$title)]==$op ) { ?> checked="checked" <?php } elseif($i==1) { ?> checked="checked" <?php } ?> value="<?php echo $title.'^'.$op;?>" <?php if($this->crud_model->is_added_to_cart($row['product_id'], 'option', $name) == $op){ echo 'checked'; } ?> id="<?php echo $name.'_'.$i; ?>">
                      <label class="radio circle" for="<?php echo $name.'_'.$i; ?>">
                        <span class="big">
                          <span class="small"></span>
                        </span>
                        <?php echo $op;?>
                      </label>
                    <?php
                        $i++;
                        }
                    ?>
                    </div>
                <?php
                    } else if($type == 'text'){
                ?>
                    <label class="textarea">
                        <textarea class="optional" rows="5" cols="30" name="<?php echo $name;?>"><?php echo $this->crud_model->is_added_to_cart($row['product_id'], 'option', $name); ?></textarea>
                    </label>
                <?php
                    } else if($type == 'single_select'){
                ?>
                    <label class="select">
                        <select name="<?php echo $name; ?>" class="optional selectpicker input-price" data-live-search="true" >
                            <option value=""><?php echo translate('choose_one'); ?></option>
                            <?php
                                foreach ($option as $op) {
                            ?>
                            <option value="<?php echo $op; ?>" <?php if($this->crud_model->is_added_to_cart($row['product_id'], 'option', $name) == $op){ echo 'selected'; } ?> ><?php echo $op; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                        <i></i>
                    </label>
                    <?php
                        } else if($type == 'multi_select') {
                    ?>
                    <div class="checkbox">
                    <?php
                        $j=1;
                        foreach ($option as $op){
                    ?>
                    <label for="<?php echo 'check_'.$j; ?>" onClick="check(this)" >
                        <input type="checkbox" id="<?php echo 'check_'.$j; ?>" class="optional" name="<?php echo $name;?>[]" value="<?php echo $op;?>" <?php if(!is_array($chk = $this->crud_model->is_added_to_cart($row['product_id'], 'option', $name))){ $chk = array(); } if(in_array($op, $chk)){ echo 'checked'; } ?>>
                        <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                        <?php echo $op;?>
                    </label>
                    <?php
                        $j++;
                        }
                    ?>
                    </div>
                <?php
                    }
                ?>
                </div>
            </div>
            <?php
                    }
                }
            ?>
            <script type="text/javascript">
				function pricevariant()
				{
					var key ="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
					var option_count = <?php echo count($all_op); ?>;
					var color_qty = $('input[name="color_qty"]').val(); 
					var i=1;
					if(color_qty!='')
					{
						var choice = ['color'+'-'+color_qty];
					}
					else 
					{
						var choice='';	
					}	
					$.post("<?php echo base_url(); ?>home/priceget", $("#pro_order").serialize()).done(function(data) 
					{
						$('.cart').show();
		                if (data.trim().length > 0) 
						{
							if(data!=0)
								{
									
									var data1 = data.split('^');
				                    $("#displayAmt").text(data1[0]);   
									if(data1[1]>0)
									{
										//$("#display_stock").text(data1[1]);
										$("#display_stock").text(' In Stock ');
										$("#display_stock_out").text(data1[1]);
									}
									else 
									{
										$("#display_stock").text(' In stock ');
										$("#display_stock_out").text(' Out Of Stock ');  
									}
								}
                		} 
		            });
					
					return true;
				}
				
				function samedo(val) { $("#c1-"+val).prop("checked", true ); } </script>
            <div class="item_count">
                <div class="quantity product-quantity">
                    <span class="btn" name='subtract' onclick='decrease_val();'>
                        <i class="fa fa-minus"></i>
                    </span>
                    
                    <?php 
					if($row['multiple_price']==1) 
					{
						$cstock = $multiple_option[$as1]['quantitty'];
					}
					else 
					{
						$cstock = $row['current_stock'];
					}
					$cstock = $row['current_stock'];
				?>	
                    
                    
                    <input  type="number" class="form-control qty quantity-field cart_quantity" min="1" max="<?php echo $cstock; ?>" name='qty' value="<?php if($a = $this->crud_model->is_added_to_cart($row['product_id'],'qty')){echo $a;} else {echo '1';} ?>" id='qty'/>
                    <span class="btn" name='add' onclick='increase_val();'>
                        <i class="fa fa-plus">
                    </i></span>
                </div>
                <?php
				if($row['multiple_price']==1) 
				{
				//echo '<pre>'; print_r($multiple_option);	
				if($multiple_option[$as1]['quantitty']>0)
				{
				?>
                <div  class="stock">
                    <?php /*?><span id="display_stock"><?php echo $multiple_option[$as1]['quantitty']; ?> </span> <?php echo ' ( Unit :'.$row['unit'].' )'.translate('_available');?><?php */?>
                    <span id="display_stock">In stock </span>
                </div>
                <?php 
				}
				else 
				{
				?>
                <div id="display_stock_out" class="out_of_stock">
                    <?php echo translate('out_of_stock'); ?>
                </div>
                <?php 	
				}
				}
				else if($row['current_stock'] > 0){
                ?>
                <div id="display_stock" class="stock">
                    <?php /*?><?php echo $row['current_stock'].' (Unit :'.$row['unit'].' )'.translate('_available');?><?php */?>
                    <?php echo "In stock"; ?>
                </div>
                <?php
                    }else{
                ?>
                <div id="display_stock_out" class="out_of_stock">
                    <?php echo translate('out_of_stock');?>
                </div>
                <?php
                    }
                ?>
                
                <div class="clearfix"></div>
                <div class="clearfix mbts" style="margin-top:20px">
    <?php if($row['ar']=='1'){ ?>
    <a class="button show-image inited" data-elementor-open-lightbox="no" title="Scan this QR with App" aria-hidden="true" data-toggle="modal" data-target="#myModal" href="<?php echo $row['ar_url']; ?>" target="_blank" rel="magnific">View Live in AR</a>
    
    <a class="button show-image_s inited1" data-elementor-open-lightbox="no" title="Qr Works" aria-hidden="true" data-toggle="modal" data-target="#msv" href="" target="_blank" rel="magnific"> How AR Works </a>
    
    
    <?php } ?>
     <?php if($row['threed']=='100'){ ?>
    <a class="button inited"data-toggle="modal" data-toggle="modal" data-target="#3dimg" title="Scan this QR with  App" aria-hidden="true" href="javascript:void(0);"target="_blank" rel="magnific">View in 3D</a>
    <?php } ?>
    <?php if($row['qr']=='100'){ ?>
    <a class="button inited"data-toggle="modal" data-toggle="modal" data-target="#3dimg1" title="Scan this QR with  App" aria-hidden="true" href="javascript:void(0);"target="_blank" rel="magnific">Scan QR to display AR</a>
    <?php } ?>
    </div>
            </div>
        </div>
    </div>
    <div class="buttons" style="display:inline-flex;">
        <a class="btn btn-add-to cart" onClick="showPopup(event,'<?php echo $row['product_id']; ?>','<?php echo $row['store_id']; ?>')">
            <i class="fa fa-shopping-cart vc"></i>
			<?php if($this->crud_model->is_added_to_cart($row['product_id'])=="yes"){ 
                echo translate('added_to_cart');  
                } else { 
                echo translate('add_to_cart');  
                } 
            ?>
        </a>
        <?php 
            $wish = $this->crud_model->is_wished($row['product_id']); 
        ?>
        <span class="btn btn-add-to <?php if($wish == 'yes'){ echo 'wished';} else{ echo 'wishlist';} ?>" onclick="to_wishlist(<?php echo $row['product_id']; ?>,event)">
            <i class="fa fa-heart"></i>
            <span class="hidden-sm hidden-xs">
				<?php if($wish == 'yes'){ 
                    echo translate('_added_to_wishlist'); 
                    } else { 
                    echo translate('_add_to_wishlist');
                    } 
                ?>
            </span>
        </span>
        <?php 
            $compare = $this->crud_model->is_compared($row['product_id']); 
        ?>
        <span class="btn btn-add-to compare btn_compare"  onclick="do_compare(<?php echo $row['product_id']; ?>,event)">
            <i class="fa fa-exchange"></i>
            <span class="hidden-sm hidden-xs">
				<?php if($compare == 'yes'){ 
                    echo translate('_compared'); 
                    } else { 
                    echo translate('_compare');
                    } 
                ?>
            </span>
        </span>
    </div> 
</form>
<div id="pnopoi"></div>
<div class="buttons">
    <div id="share"></div>
</div>
<hr class="page-divider small"/>

<div class="modal fade view_in_air" id="myModal" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-body">
<label><?php echo strtoupper($row['title']);?></label>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">


#card {
margin: 3em auto;
display: flex;
flex-direction: column;
max-width: 600px;
border-radius: 6px;
box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
overflow: hidden;
}

model-viewer {
width: 100%;
height: 400px;
}

#card model-viewer .container{
background-color: #f57521 !important;
}

.attribution {
display: flex;
flex-direction: row;
justify-content: space-between;
margin: 1em;
}

.attribution h1 {
margin: 0 0 0.25em;
}

.attribution img {
opacity: 0.5;
height: 2em;
}

.attribution .cc {
flex-shrink: 0;
text-decoration: none;
}


</style>
<!-- The following libraries and polyfills are recommended to maximize browser support -->
<!-- NOTE: you must adjust the paths as appropriate for your project -->
<!-- ðŸš¨ REQUIRED: Web Components polyfill to support Edge and Firefox < 63 -->
<script src="https://unpkg.com/@webcomponents/webcomponentsjs@2.1.3/webcomponents-loader.js"></script>
<!-- ðŸ’ OPTIONAL: Intersection Observer polyfill for better performance in Safari and IE11 -->
<script src="https://unpkg.com/intersection-observer@0.5.1/intersection-observer.js"></script>
<!-- ðŸ’ OPTIONAL: Resize Observer polyfill improves resize behavior in non-Chrome browsers -->
<script src="https://unpkg.com/resize-observer-polyfill@1.5.0/dist/ResizeObserver.js"></script>
<!-- ðŸ’ OPTIONAL: Fullscreen polyfill is needed to fully support AR features -->
<script src="https://unpkg.com/fullscreen-polyfill@1.0.2/dist/fullscreen.polyfill.js"></script>
<!-- ðŸ’ OPTIONAL: Include prismatic.js for Magic Leap support -->
<script src="https://unpkg.com/@magicleap/prismatic/prismatic.min.js"></script>
<!-- ðŸ’ OPTIONAL: The :focus-visible polyfill removes the focus ring for some input types -->
<script src="https://unpkg.com/focus-visible@5.0.2/dist/focus-visible.js" defer></script>
<div id="card">
<img src="<?php echo base_url(); ?>uploads/logo_image/logo_<?php echo $home_top_logo; ?>.png" alt="vensyemall" style="position: absolute; z-index: 10;height: 70px;padding: 10px;"/>
<model-viewer src="<?php echo base_url().'uploads/android_image/android_'.$row['product_id'].'.glb';?>?v=<?php echo $times; ?>"
ios-src="<?php echo base_url().'uploads/ios_image/ios_'.$row['product_id'].'.usdz';?>?v=<?php echo $times; ?>"
alt="A 3D model of an astronaut" background-color="#fff" shadow-intensity="1" camera-controls interaction-prompt="auto" auto-rotate ar magic-leap>
</model-viewer>
</div>
<!-- ðŸ’ Include both scripts below to support all browsers! -->
<!-- Loads <model-viewer> for modern browsers: -->
<script type="module"
src="https://unpkg.com/@google/model-viewer@v0.7.2/dist/model-viewer.js">
</script>
<!-- Loads <model-viewer> for old browsers like IE11: -->
<script nomodule
src="https://unpkg.com/@google/model-viewer@v0.7.2/dist/model-viewer-legacy.js">
</script>




</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>

</div>
</div>


<div class="modal fade view_in_air" id="msv" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
            <div class="modal-body">
            	<img src="<?php echo base_url(); ?>uploads/dod3D.gif" class="col-md-12 col-xs-12 col-sm-12">
            </div>
            
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
            
			</div>
	</div>
</div>


<script>
	$(document).ready(function() {
		$('#share').share({
			networks: ['facebook','twitter','linkedin'],
			theme: 'square'
		});
	});
</script>
<script>
$(document).ready(function() {
	check_checkbox();
});
function check_checkbox(){
	$('.checkbox input[type="checkbox"]').each(function(){
        if($(this).prop('checked') == true){
			$(this).closest('label').find('.cr-icon').addClass('add');
		}else{
			$(this).closest('label').find('.cr-icon').addClass('remove');
		}
    });
}
function check(now)
{
	if($(now).find('input[type="checkbox"]').prop('checked') == true)
	{
		$(now).find('.cr-icon').removeClass('remove');
		$(now).find('.cr-icon').addClass('add');
	}
	else
	{
		$(now).find('.cr-icon').removeClass('add');
		$(now).find('.cr-icon').addClass('remove');
	}
}
function decrease_val(){
	var value=$('.quantity-field').val();
	if(value > 1){
		var value=--value;
	}
	$('.quantity-field').val(value);
}
function increase_val(){
	var value=$('.quantity-field').val();
	var max_val =parseInt($('.quantity-field').attr('max'));
	if(value < max_val){
		var value=++value;
	}
	$('.quantity-field').val(value);
}
</script>

<style>
.mbts{
	margin-bottom:10px;	
}

</style>
<style type="text/css">
.button.show-image::before {
    content: '\f06e' !important;
    font-weight: 400;
    margin-right: 10px !important;
    font-size: 1.28em;
    font-family: "fontAwesome" !important;
    display: inline-block !important;
}

.button.show-image_s::before{
	content: '\f12e' !important;
    font-weight: 400;
    margin-right: 10px !important;
    font-size: 1.28em;
    font-family: "fontAwesome" !important;
    display: inline-block !important;	
}

.button.show-image, .button.inited{
	background:#0d0d0d;
	padding:20px;
	color:#fff;
	line-height:45px;	
}

.button.show-image_s, .button.inited1 {
    background: #d54848;
	padding: 20px;
	color: #fff;
    line-height: 45px;
}

.ar-wrap > a.show-image {
    padding-left: 2em !important;
    padding-right: 2.2em !important;
    position: relative;
}

.mbts{
	margin-bottom:10px;	
}


</style>