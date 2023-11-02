<?php
	foreach($blogs as $row){
?>
<div class="thumbnail blog_box">
	<div class="row">
		<div class="col-md-4 col-sm-4 col-xs-12">
			<div class="media">
				<a class="media-link" href="<?php echo $this->crud_model->blog_link($row['blog_id']); ?>">
					<img src="<?php echo $this->crud_model->file_view('blog',$row['blog_id'],'','','thumb','src','',''); ?>" alt=""/>
				</a>
			</div>
		</div>
		<div class="col-md-8 col-sm-8 col-xs-12">
			<div class="caption">
				<h4 class="caption-title">
                    <a href="<?php echo $this->crud_model->blog_link($row['blog_id']); ?>">
                    	<?php echo $row['title']; ?>
                    </a>
                </h4>
				<div class="overflowed">
					<div class="availability"><?php echo translate('author'); ?>: <strong><?php echo $row['author']; ?></strong></div>
					<div class="price"><ins><?php echo $row['date']; ?></ins></div>
				</div>
				<div class="caption-text">
					<?php echo $row['summery']; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	}
?>
	
<table>
<tr class="text-center" style="display:none;" >
	<td id="pagenation_set_links" ><?php echo $this->ajax_pagination->create_links(); ?></td>
</tr>
</table>

<script>
	$(document).ready(function() {
		$('#title').html('<?php echo $category_name; ?>');
	});	
</script>
<script>
	$(document).ready(function(){ 
		$('.pagination_box').html($('#pagenation_set_links').html());
		$('html, body').animate({scrollTop: 2}, 500);
	});
</script>