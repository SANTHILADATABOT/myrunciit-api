<div class="col-md-12">
    <div class="panel">
        <div class="panel-heading margin-bottom-20">
            <h3 class="panel-title">
                <?php echo translate('product_SEO_settings');?>
            </h3>
        </div>
        <div style="width:100%;overflow-y:scroll;height:500px;">
            <table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true">
                <tr>
                    <th>S.No</th>
                    <th><?php echo translate('product_title');?></th>
                    <th><?php echo translate('product_description');?></th>
                    <th><?php echo translate('keyword');?></th>
                    <th><?php echo translate('description');?></th>
                    <th><?php echo translate('active_status');?></th>
                    <th><?php echo translate('status');?></th>
                </tr>
                <?php
                $i1=1;
                foreach($products as $products1){
                    $product_id=$products1['product_id'];$product_seo_id=0;
                    $SEO_keyword0="";$SEO_description0="";
                    $SEO_keyword1="";$SEO_description1="";$SEO_active_status1="1";
                    if(array_key_exists($product_id,$product_seo)){
                        $product_seo_data=$product_seo[$product_id];$product_seo_id=1;
                        $SEO_keyword0=$SEO_keyword1=$product_seo_data['keywords'];
                        $SEO_description0=$SEO_description1=$product_seo_data['description'];
                        $SEO_active_status1=$product_seo_data['active_status'];
                    }else{
                        $SEO_keyword1=trim(preg_replace('/\s+/',' ', strip_tags($products1['title'])));
                        $SEO_description1=trim(preg_replace('/\s+/',' ', strip_tags($products1['description'])));
                    } ?>
                <tr>
                    <td><?php echo $i1; ?><input type="hidden" class="seo_id" value="<?php echo $product_id."_".$product_seo_id; ?>"></td>
                    <td><?php echo $products1['title']; ?></td>
                    <td><?php echo $products1['description']; ?></td>
                    <td>
                        <input type="hidden" id="seokeyword0_<?php echo $i1; ?>" value="<?php echo $SEO_keyword0; ?>" />
                        <textarea style="width: 200px;" id="seokeyword_<?php echo $i1; ?>" rows="3" class="form-control seo_keyword required" placeholder="<?php echo translate('SEO_keyword'); ?>" oninput="checkall_keydescr_unique('seo_keyword','Keyword');check_seo_keyval(<?php echo $i1; ?>)"><?php echo $SEO_keyword1; ?></textarea>
                        <div class="label label-danger" style="display:none;" id='seokeyword_<?php echo $i1; ?>_note'></div>
                    </td>
                    <td>
                        <input type="hidden" id="seodescription0_<?php echo $i1; ?>" value="<?php echo $SEO_description0; ?>" />
                        <textarea style="width: 200px;" id="seodescription_<?php echo $i1; ?>" rows="3" class="form-control seo_description required" placeholder="<?php echo translate('SEO_description'); ?>" oninput="checkall_keydescr_unique('seo_description','Description');check_seo_keyval(<?php echo $i1; ?>)"><?php echo $SEO_description1; ?></textarea>
                        <div class="label label-danger" style="display:none;" id='seodescription_<?php echo $i1; ?>_note'></div>
                    </td>
                    <td>
                        <input type="hidden" id="seo_ative_status0-<?php echo $i1; ?>" value="<?php echo ($SEO_active_status1=="1")?1:0; ?>" />
                        <input id="seo_ative_status-<?php echo $i1; ?>" class='seo_ative_status' type="checkbox" data-id='seo_ative_status-<?php echo $i1; ?>' <?php if($SEO_active_status1=="1"){echo " checked";} ?> />
                    </td>
                    <td>
                        <input type="hidden" id="seo_status-<?php echo $i1; ?>" class="seo_status" value="<?php echo ($product_seo_id!=0)?1:0; ?>" />
                        <img id="seo_statusimg-<?php echo $i1; ?>" src="<?php echo base_url(); ?>template/back/img/<?php echo (($product_seo_id!=0)?"icon_saved":"icon_unsaved"); ?>.png" style="width:30px;height:30px;" />
                    </td>
                </tr>
                <?php $i1++;} ?>
            </table>
        </div>
        <div class="panel-footer text-center">
            <button class="btn btn-success btn-labeled fa fa-check disabled" id="save_seo_product_btn" onclick="save_seo_product_data();"><?php echo translate('save');?></button>
            &nbsp;<button class="btn btn-success btn-labeled fa fa-refresh" onclick="seo_product_settings1();"><?php echo translate('refresh');?></button>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $(".seo_ative_status").each(function(){
        var seo1=$(this).data('id');
        var seo_index=seo1.split("-")[1];
        new Switchery(document.getElementById(seo1), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
        var changeCheckbox = $(this).get(0);
        changeCheckbox.onchange = function() {
            check_seo_keyval(seo_index);
        };
    });
    check_save_seo();
    checkall_keydescr_unique('seo_keyword','Keyword');
    checkall_keydescr_unique('seo_description','Description');
});
</script>