<div id="content-container">
<div class="content-wrapper-before"></div>
 
    <div class="tab-base">
        <div class="panel">
		
		   <div class="row">
			  <div class="col-md-12">
			    <h1 class="page-header text-overflow"><?php echo translate('manage_SEO');?></h1>
			  </div>
		    </div>
		
		
            <div class="tab-base tab-stacked-left">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#demo-stk-lft-tab-5">SEO <?php echo translate('settings');?></a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#social_count"><?php echo translate('social_network_reach');?></a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#alexa"><?php echo translate('alexa_traffic_metrics');?></a>
                    </li>
                    <li style="display:none;">
                        <a data-toggle="tab" href="#alexa_graphs"><?php echo translate('alexa_traffic_graphs');?></a>
                    </li>
                    <li style="display:none;">
                        <a data-toggle="tab" href="#google"><?php echo translate('search_index');?></a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#product_seo_settings"><?php echo translate('product_SEO_settings');?></a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#page_seo_settings"><?php echo translate('page_SEO_settings');?></a>
                    </li>
                </ul>

                <div class="tab-content bg_grey">

                    <div id="google" class="tab-pane fade">
                        <div class="">
                            <div class="">
                                <h3 class="panel-title"><?php echo translate('search_index');?></h3>
                            </div>
                                <div class="panel-body">
                                    
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tr>
                                                <td>Google Websearch Index (Total Search Results) </td>
                                                <td><h4><?php echo $this->crud_model->seo_stat('google_siteindex'); ?></h4></td>
                                            </tr>
                                            <tr>
                                                <td>Total Backlinks</td>
                                                <td><h4><?php echo $this->crud_model->seo_stat('google_back'); ?></h4></td>
                                            </tr>
                                            <tr>
                                                <td>Search Engine Traffic</td>
                                                <td><h4><?php echo $this->crud_model->seo_stat('search_graph_1'); ?></h4></td>
                                            </tr>
                                            <tr>
                                                <td>Search Engine Traffic Price</td>
                                                <td><h4><?php echo $this->crud_model->seo_stat('search_graph_2'); ?></h4></td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="social_count" class="tab-pane fade">
                        <div class="panel-inr">
                            <div class="">
                                <h3 class="panel-title"><?php echo translate('social_network_reach');?></h3>
                            </div>
                                <div class="panel-body">
                                    
                                    <div class="table-responsive">
                                        <table class="table table-striped">

                                            <tr>
                                                <td>Facebook Interactions</td>
                                                <td>
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                        <?php 
                                                            $fb = $this->crud_model->seo_stat('facebook');
                                                            foreach ($fb as $key => $value) {
                                                                echo '<tr>';
                                                                echo '<td>'.ucfirst(str_replace('_', ' ', $key)).'</td>';
                                                                echo '<td><h4>'.$value.'</h4></td>';
                                                                echo '</tr>';
                                                            }
                                                        ?>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Twitter Mentions</td>
                                                <td><h4><?php echo $this->crud_model->seo_stat('twitter'); ?></h4></td>
                                            </tr>
                                            <tr>
                                                <td>Google+ PlusOnes</td>
                                                <td><h4><?php echo $this->crud_model->seo_stat('gplus'); ?></h4></td>
                                            </tr>
                                            <tr>
                                                <td>LinkedIn Shares</td>
                                                <td><h4><?php echo $this->crud_model->seo_stat('linkedin'); ?></h4></td>
                                            </tr>
                                            <tr>
                                                <td>Pinterest Shares</td>
                                                <td><h4><?php echo $this->crud_model->seo_stat('pinterest'); ?></h4></td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="alexa" class="tab-pane fade">
                        <div class="panel-inr">
                            <div class="">
                                <h3 class="panel-title"><?php echo translate('alexa_traffic_rank');?></h3>
                            </div>
                                <div class="panel-body">

                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tr>
                                                <td>Last 3 Months</td>
                                                <td><h4><?php echo $this->crud_model->seo_stat('alexa_global'); ?></h4></td>
                                            </tr>
                                            <tr>
                                                <td>Country-specific</td>
                                                <td>
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                        <?php 
                                                            $fb = $this->crud_model->seo_stat('alexa_country');
                                                            if(!empty($fb)){
                                                                foreach ($fb as $key => $value) {
                                                                    echo '<tr>';
                                                                    echo '<td>'.ucfirst(str_replace('_', ' ', $key)).'</td>';
                                                                    echo '<td><h4>'.$value.'</h4></td>';
                                                                    echo '</tr>';
                                                                }
                                                            }
                                                        ?>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="alexa_graphs" class="tab-pane fade">
                        <div class="panel-inr">
                            <div class="">
                                <h3 class="panel-title"><?php echo translate('alexa_traffic_graphs');?></h3>
                            </div>
                                <div class="panel-body">
                                    
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tr>
                                                <td>Daily Traffic Trend</td>
                                                <td><?php echo $this->crud_model->seo_stat('alexa_traffic'); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Time on Site (in Minutes)</td>
                                                <td><?php echo $this->crud_model->seo_stat('alexa_time'); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Bounce Rate (Percent)</td>
                                                <td><?php echo $this->crud_model->seo_stat('alexa_bounce'); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Daily Pageviews (Percent)</td>
                                                <td><?php echo $this->crud_model->seo_stat('alexa_pageviews'); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- START : MANAGE SEO------>
                    <div id="demo-stk-lft-tab-5" class="tab-pane fade active in">
                         <div class="col-md-12">
                            <div class="panel-inr">
                                <div class="margin-bottom-20">
                                    <h3 class="panel-title">
                                        <?php echo translate('manage_search_engine_optimization');?>
                                    </h3>
                                </div>
                            <?php 
                                $description =  $this->db->get_where('general_settings',array('type' => 'meta_description'))->row()->value;
                                $keywords =  $this->db->get_where('general_settings',array('type' => 'meta_keywords'))->row()->value;
                                $author =  $this->db->get_where('general_settings',array('type' => 'meta_author'))->row()->value;
                                $revisit_after =  $this->db->get_where('general_settings',array('type' => 'revisit_after'))->row()->value;
                            ?>
							<?php
                                echo form_open(base_url() . 'index.php/admin/seo_settings/set', array(
                                    'class' => 'form-horizontal',
                                    'method' => 'post',
                                    'id' => '',
                                    'enctype' => 'multipart/form-data'
                                ));
                            ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        <?php echo translate('keywords'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="col-sm-">
                                            <input type="text"  data-role="tagsinput" name="keywords" value="<?php echo $keywords; ?>" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        <?php echo translate('author'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="col-sm-">
                                            <input type="text" name="author" value="<?php echo $author; ?>" class="form-control" >
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        <?php echo translate('revisit_after'); ?>
                                    </label>
                                    <div class="col-sm-6">
                                        <div class="col-sm-">
                                            <input type="number" name="revisit_after" value="<?php echo $revisit_after; ?>" class="form-control" >
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        <span style="float:left"><?php echo translate('days'); ?></span>
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        <?php echo translate('description'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="col-sm-">
                                            <textarea name="description" class="form-control" rows='8' ><?php echo $description; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">
                                        <?php echo translate('sitemap_link'); ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="col-sm-">
                                            <input type="text" value="<?php echo base_url(); ?>index.php/sitemap.xml" class="form-control" >
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-footer text-center">
                                    <span class="btn btn-success btn-labeled fa fa-check submitter enterer"  data-ing='<?php echo translate('saving'); ?>' data-msg='<?php echo translate('settings_updated!'); ?>'>
                                    <?php echo translate('save');?></span>
                                </div>
                            </form>               
                        </div>
                        </div> 
                    </div>
                    <!-- END : MANAGE SEO-->                    
                    <div id="product_seo_settings" class="tab-pane fade"></div>

                    <div id="page_seo_settings" class="tab-pane fade"></div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div style="display:none;" id="site"></div>
<!-- for logo settings -->
<script src="<?php $this->benchmark->mark_time(); echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<script>
    function check_save_seo(){
        var check_all=false;
        var seo_status1=document.getElementsByClassName('seo_status');
        for(let i1=0;i1<seo_status1.length;i1++){
            if(seo_status1[i1].value=='0'){check_all=true;break;}else{check_all=false;}
        }
        if(check_all){$("#save_seo_product_btn").removeClass("disabled");}
        else{$("#save_seo_product_btn").addClass("disabled");}
    }
    function checkall_keydescr_unique(comp,c1){
        var arr1={};
        $("."+comp).each(function(){
            var seo_keyword1 = $(this).get(0);
            var valu=seo_keyword1.value;
            if((arr1[valu] == undefined)){arr1[valu]=[];}
            arr1[valu].push(seo_keyword1.id);
        });
        $.each(arr1, function(index, value){
            for(let i1=0;i1<value.length;i1++){
                var id1="#"+value[i1]+"_note";
                if(value.length>1){
                    $(id1).show();
                    $(id1).html(c1+' Already Exist');
                }else{
                    $(id1).hide();
                    $(id1).html('');
                }
            }
        });
    }
    function check_seo_keyval(seo_index){
        var key1=$("#seokeyword0_"+seo_index).val();var key2=$("#seokeyword_"+seo_index).val();
        var des1=$("#seodescription0_"+seo_index).val();var des2=$("#seodescription_"+seo_index).val();
        var sts1=$("#seo_ative_status0-"+seo_index).val();var sts2=(document.getElementById("seo_ative_status-"+seo_index).checked?"1":"0");
        if((key2!=""?key1==key2:false) && (des2!=""?des1==des2:false) && (sts2!=""?sts1==sts2:false)) {
            document.getElementById("seo_status-"+seo_index).value="1";
            document.getElementById("seo_statusimg-"+seo_index).src="<?php echo base_url(); ?>template/back/img/icon_saved.png";
        } else {
            document.getElementById("seo_status-"+seo_index).value="0";
            document.getElementById("seo_statusimg-"+seo_index).src="<?php echo base_url(); ?>template/back/img/icon_unsaved.png";
        }
        check_save_seo();
    }
    function seo_product_settings1(){
        $("#product_seo_settings").html("");
        $.ajax({url: base_url+'index.php/admin/seo_settings/seo_product_settings/',
        success: function(result){
            $("#product_seo_settings").html(result);
        }});
    }
    function save_seo_product_data(){
        $("#save_seo_product_btn").addClass("disabled");
        var seo_valu=[];
        var seo_id0=document.getElementsByClassName("seo_id");
        var seo_keyword0=document.getElementsByClassName("seo_keyword");
        var seo_description0=document.getElementsByClassName("seo_description");
        var seo_ative_status0=document.getElementsByClassName("seo_ative_status");
        for(let i1=0;i1<seo_id0.length;i1++){
            var s1=seo_id0[i1].value.split("_");
            seo_valu.push({"product_id":s1[0]+"_"+(seo_ative_status0[i1].checked?"1":"0")/* ,"product_seo_id":s1[1] */,"keywords":seo_keyword0[i1].value,"description":seo_description0[i1].value/* ,"active_status":(seo_ative_status0[i1].checked?"1":"0") */});
        }
        $.ajax({url: base_url+'index.php/admin/seo_settings/save_seo_product/',
        method: "POST",
        data: {"seo_valu":seo_valu},
        success: function(result){
            $.activeitNoty({
                type: 'success',
                icon : 'fa fa-check',
                message : "Updated Successfully",
                container : 'floating',
                timer : 3000
            });
            sound('Admin Enabled');
            seo_product_settings1();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.activeitNoty({
                type: 'danger',
                icon : 'fa fa-check',
                message : "Update Failed",
                container : 'floating',
                timer : 3000
            });
            sound('Admin Suspended');
            $("#save_seo_product_btn").removeClass("disabled");
        }});
    }
    function check_save_seo_page(){
        var check_all=false;
        var seo_status_page1=document.getElementsByClassName('seo_status_page');
        for(let i1=0;i1<seo_status_page1.length;i1++){
            if(seo_status_page1[i1].value=='0'){check_all=true;break;}else{check_all=false;}
        }
        if(check_all){$("#save_seo_page_btn").removeClass("disabled");}
        else{$("#save_seo_page_btn").addClass("disabled");}
    }
    function checkall_keydescr_unique_page(comp,c1){
        var arr1page={};
        $("."+comp).each(function(){
            var seo_keyword1 = $(this).get(0);
            var valu=seo_keyword1.value;
            if((arr1page[valu] == undefined)){arr1page[valu]=[];}
            arr1page[valu].push(seo_keyword1.id);
        });
        $.each(arr1page, function(index, value){
            for(let i1=0;i1<value.length;i1++){
                var id1="#"+value[i1]+"_note";
                if(value.length>1){
                    $(id1).show();
                    $(id1).html(c1+' Already Exist');
                }else{
                    $(id1).hide();
                    $(id1).html('');
                }
            }
        });
    }
    function check_seo_keyval_page(seo_index){
        var key1=$("#seokeyword_page0_"+seo_index).val();var key2=$("#seokeyword_page_"+seo_index).val();
        var des1=$("#seodescription_page0_"+seo_index).val();var des2=$("#seodescription_page_"+seo_index).val();
        var sts1=$("#seo_ative_status_page0-"+seo_index).val();var sts2=(document.getElementById("seo_ative_status_page-"+seo_index).checked?"1":"0");
        if((key2!=""?key1==key2:false) && (des2!=""?des1==des2:false) && (sts2!=""?sts1==sts2:false)) {
            document.getElementById("seo_status_page-"+seo_index).value="1";
            document.getElementById("seo_status_pageimg-"+seo_index).src="<?php echo base_url(); ?>template/back/img/icon_saved.png";
        } else {
            document.getElementById("seo_status_page-"+seo_index).value="0";
            document.getElementById("seo_status_pageimg-"+seo_index).src="<?php echo base_url(); ?>template/back/img/icon_unsaved.png";
        }
        check_save_seo_page();
    }
    function seo_page_settings1(){
        $("#page_seo_settings").html("");
        $.ajax({url: base_url+'index.php/admin/seo_settings/seo_page_settings/',
        success: function(result){
            $("#page_seo_settings").html(result);
        }});
        
    }
	function save_seo_page_data(){
        $("#save_seo_page_btn").addClass("disabled");
        var seo_valu=[];
        var seo_id0=document.getElementsByClassName("seo_id_page");
        var seo_keyword0=document.getElementsByClassName("seo_keyword_page");
        var seo_description0=document.getElementsByClassName("seo_description_page");
        var seo_ative_status0=document.getElementsByClassName("seo_ative_status_page");

        for(let i1=0;i1<seo_id0.length;i1++){
            var s1=seo_id0[i1].value.split("_");
            seo_valu.push({"page_id":s1[0]+"_"+(seo_ative_status0[i1].checked?"1":"0")/* ,"product_seo_id":s1[1] */,"keywords":seo_keyword0[i1].value,"description":seo_description0[i1].value/* ,"active_status":(seo_ative_status0[i1].checked?"1":"0") */});
        }
        $.ajax({url: base_url+'index.php/admin/seo_settings/save_seo_page/',
        method: "POST",
        data: {"seo_valu":seo_valu},
        success: function(result){
            $.activeitNoty({
                type: 'success',
                icon : 'fa fa-check',
                message : "Updated Successfully",
                container : 'floating',
                timer : 3000
            });
            sound('Admin Enabled');
            seo_page_settings1();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.activeitNoty({
                type: 'danger',
                icon : 'fa fa-check',
                message : "Update Failed",
                container : 'floating',
                timer : 3000
            });
            sound('Admin Suspended');
            $("#save_seo_page_btn").removeClass("disabled");
        }});
    }
	$(document).ready(function() {
        seo_product_settings1();
        seo_page_settings1();
		
        $('.summernotes').each(function() {
            var now = $(this);
            var h = now.data('height');
            var n = now.data('name');
            now.closest('div').append('<input type="hidden" class="val" name="'+n+'">');
            now.summernote({
                height: h,
                onChange: function() {
                    now.closest('div').find('.val').val(now.code());
                }
            });
			now.closest('div').find('.val').val(now.code());
			now.focus();
        });
        load_dropzone();
        load_logos();
		
	});

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#wrap').hide('fast');
                $('#blah').attr('src', e.target.result);
                $('#wrap').show('fast');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#imgInp").change(function(){
        readURL(this);
    });


    var base_url = '<?php echo base_url(); ?>'
    var user_type = 'admin';
    var module = 'logo_settings';
    var list_cont_func = 'show_all';
    var dlt_cont_func = 'delete_logo';
</script>
<!-- for logo settings -->



<script type="text/javascript">

    $(document).ready(function() {
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
    });



    $(".range-def").on('slide', function(){
        var vals    = $("#nowslide").val();
        $(this).closest(".form-group").find(".range-def-val").html(vals);
        $(this).closest(".form-group").find("input").val(vals);
    });

    function sets(now){
        $(".range-def").each(function(){
            var min = $(this).data('min');
            var max = $(this).data('max');
            var start = $(this).data('start');
            $(this).noUiSlider({
                start: Number(start) ,
                range: {
                    'min': Number(min),
                    'max': Number(max)
                }
            }, true);
            if(now == 'first'){
                $(this).noUiSlider({
                    start: 500 ,
                    connect : 'lower',
                    range: {
                        'min': 0 ,
                        'max': 10
                    }
                },true).Link('lower').to($("#nowslide"));
                $(this).closest(".form-group").find(".range-def-val").html(start);
                $(this).closest(".form-group").find("input").val(start);
            } else if(now == 'later'){
                var than = $(this).closest(".form-group").find(".range-def-val").html();
                
                if(than !== 'undefined'){
                    $(this).noUiSlider({
                        start: than,
                        connect : 'lower',
                        range: {
                            'min': min ,
                            'max': max
                        }
                    },true).Link('lower').to($("#nowslide"));
                } 
                $(this).closest(".form-group").find(".range-def-val").html(than);
                $(this).closest(".form-group").find("input").val(than);
            }
        });
    }
	$(document).ready(function() {
        sets('later');
		$("form").submit(function(e){
			return false;
		});

	});
</script>
<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js">
</script>
<style>
.img-fixed{
	width: 100px;	
}
.tr-bg{
background-image: url(http://www.mikechambers.com/files/html5/canvas/exportWithBackgroundColor/images/transparent_graphic.png)	
}

.cc-selector input{
    margin:0;padding:0;
    -webkit-appearance:none;
       -moz-appearance:none;
            appearance:none;
}
 
.cc-selector input:active +.drinkcard-cc
{
	opacity: 1;
	border:4px solid #169D4B;
}
.cc-selector input:checked +.drinkcard-cc{
	-webkit-filter: none;
	-moz-filter: none;
	filter: none;
	border:4px solid black;
}
.drinkcard-cc{
	cursor:pointer;
	background-size:contain;
	background-repeat:no-repeat;
	display:inline-block;
	-webkit-transition: all 100ms ease-in;
	-moz-transition: all 100ms ease-in;
	transition: all 100ms ease-in;
	-webkit-filter:opacity(.3);
	-moz-filter:opacity(.3);
	filter:opacity(.3);
	transition: all .6s ease-in-out;
	border:4px solid transparent;
	border-radius:5px !important;
}
.drinkcard-cc:hover{
	-webkit-filter:opacity(1);
	-moz-filter: opacity(1);
	filter:opacity(1);
	transition: all .6s ease-in-out;
	border:4px solid #8400C5;
			
}

</style>

