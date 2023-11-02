<div class="col-md-12">
    <div class="panel">
        <div class="panel-heading margin-bottom-20">
            <h3 class="panel-title">
                <?php echo translate('page_SEO_settings');?>
            </h3>
        </div>
        <div style="width:100%;overflow-y:scroll;height:500px;">
        <table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true">
            <tr>
                <th>S.No</th>
                <th><?php echo translate('page_title');?></th>
                <th><?php echo translate('keyword');?></th>
                <th><?php echo translate('description');?></th>
                <th><?php echo translate('active_status');?></th>
                <th><?php echo translate('status');?></th>
            </tr>
            <?php
            $i1=1;
            foreach($page as $page1){
                $page_id=$page1['page_id'];
                $page_seo_id=0;
                $SEO_keyword0="";$SEO_description0="";
                $SEO_keyword1="";$SEO_description1="";$SEO_active_status1="1";
                if(array_key_exists($page_id,$page_seo)){
                    $page_seo_data=$page_seo[$page_id];
                    $page_seo_id=1;
                    $SEO_keyword0=$SEO_keyword1=$page_seo_data['keywords'];
                    $SEO_description0=$SEO_description1=$page_seo_data['description'];
                    $SEO_active_status1=$page_seo_data['active_status'];
                }else{
                    $SEO_keyword1=$SEO_description1=trim(preg_replace('/\s+/',' ', strip_tags($page1['page_name'])));
                } ?>
            <tr>
                <td><?php echo $i1; ?><input type="hidden" class="seo_id_page" value="<?php echo $page_id."_".$page_seo_id; ?>"></td>
                <td><?php echo $page1['page_name']; ?></td>
                <td>
                    <input type="hidden" id="seokeyword_page0_<?php echo $i1; ?>" value="<?php echo $SEO_keyword0; ?>" />
                    <textarea style="width: 200px;" id="seokeyword_page_<?php echo $i1; ?>" rows="3" class="form-control seo_keyword_page required" placeholder="<?php echo translate('SEO_keyword'); ?>" oninput="checkall_keydescr_unique_page('seo_keyword_page','page_name');check_seo_keyval_page(<?php echo $i1; ?>)"><?php echo $SEO_keyword1; ?></textarea>
                    <div class="label label-danger" style="display:none;" id='seokeyword_page_<?php echo $i1; ?>_note'></div>
                </td>
                <td>
                    <input type="hidden" id="seodescription_page0_<?php echo $i1; ?>" value="<?php echo $SEO_description0; ?>" />
                    <textarea style="width: 200px;" id="seodescription_page_<?php echo $i1; ?>" rows="3" class="form-control seo_description_page required" placeholder="<?php echo translate('SEO_description'); ?>" oninput="checkall_keydescr_unique_page('seo_description_page','content');check_seo_keyval_page(<?php echo $i1; ?>)"><?php echo $SEO_description1; ?></textarea>
                    <div class="label label-danger" style="display:none;" id='seodescription_page_<?php echo $i1; ?>_note'></div>
                </td>
                <td>
                    <input type="hidden" id="seo_ative_status_page0-<?php echo $i1; ?>" value="<?php echo ($SEO_active_status1=="1")?1:0; ?>" />
                    <input id="seo_ative_status_page-<?php echo $i1; ?>" class='seo_ative_status_page' type="checkbox" data-id='seo_ative_status_page-<?php echo $i1; ?>' <?php if($SEO_active_status1=="1"){echo " checked";} ?> />
                </td>
                <td>
                    <input type="hidden" id="seo_status_page-<?php echo $i1; ?>" class="seo_status_page" value="<?php echo ($page_seo_id!=0)?1:0; ?>" />
                    <img id="seo_status_pageimg-<?php echo $i1; ?>" src="<?php echo base_url(); ?>template/back/img/<?php echo (($page_seo_id!=0)?"icon_saved":"icon_unsaved"); ?>.png" style="width:30px;height:30px;" />
                </td>
            </tr>
            <?php $i1++;} ?>
        </table>
        </div>
        <div class="panel-footer text-center">
        <button class="btn btn-success btn-labeled fa fa-check disabled" id="save_seo_page_btn" onclick="save_seo_page_data();"><?php echo translate('save');?></button>
            &nbsp;<button class="btn btn-success btn-labeled fa fa-refresh" onclick="seo_page_settings1();"><?php echo translate('refresh');?></button>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $(".seo_ative_status_page").each(function(){
        var seo1=$(this).data('id');
        var seo_index=seo1.split("-")[1];
        new Switchery(document.getElementById(seo1), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
        var changeCheckbox = $(this).get(0);
        changeCheckbox.onchange = function() {
            check_seo_keyval_page(seo_index);
        };
    });
    check_save_seo_page();
    checkall_keydescr_unique_page('seo_keyword_page','page_name');
    checkall_keydescr_unique_page('seo_description_page','content');
});
</script>