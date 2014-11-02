
		<?php $id = get_first_post_ID(); ?>

		<div class="previews-top-right">
			<?php echo_first_post_preview_image($id); ?>
		</div>
		
		<div class="previews-top-left">
			<?php echo_first_post_preview($id); ?>
		</div>
		
		<div class="preview-topstory-related">
		<?php echo_first_post_preview_related($id); ?>
		</div>
		
		<div class="previews-clear"></div>
		
		

	<div id=previews-medium">

		<div class="previews-left">
			<?php echo_n_post_preview(2, 2); ?>
			<?php //13343echo('<img src="' . echo_featured_image(12932) . '" />'); ?>
			<?php //echo('<img src="' . echo_featured_image(13343) . '" />'); ?>
		</div>
		
		<div class="previews-middle">
			<?php echo_n_post_preview(3, 3); ?>
		</div>
			
		<div class="previews-right">
			<?php echo_n_post_preview(4, 4); // was 4, 6 ?>
		</div>
			
		<div class="previews-clear"></div>
		
		<div class="findmorenews">
			<!-- <a href="news">Find all our News & Updates here</a> -->
		</div>
	
	</div>
