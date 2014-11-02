<?php 
/*
 *  Template Name: Parentpage
 */
?>

<?php get_header(); ?>

		<section id="primary">
			<div id="content" role="main">
			
			<?php if (have_posts()) : while (have_posts()) : the_post();?>
				<?php get_template_part( 'content', 'page' ); ?>
	 			<?php $id = get_the_ID() ;?>
			<?php endwhile; endif; ?>
			
			<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
			
			<h2>In this topic:</h2>
			
 			<?php wp_list_pages( array('child_of'=>$id) ); ?>


			</div><!-- #content -->
		</section><!-- #primary -->
		
<?php get_footer(); ?>