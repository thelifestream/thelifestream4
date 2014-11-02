<?php 
/*
 *  Template Name: Content Overview
 */
?>

<?php get_header(); ?>

		<section id="primary">
			<div id="content" role="main">
			
			<?php if (have_posts()) : while (have_posts()) : the_post();?>
				<?php get_template_part( 'content', 'page' ); ?>
 			<?php endwhile; endif; ?>
 			
 			<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>

			<h1>News & Updates</h1>
			
			<h2>Archives by Month:</h2>
	  			<ul>
	    			<?php wp_get_archives('type=monthly'); ?>
	  			</ul>

			<h2>Archives by Subject:</h2>
				<ul>
					<?php wp_list_categories(); ?>
			</ul>
			
			<h1>Content</h1>
			
			<?php echo "Loop through all pages and their children to show a map of the entire content." ;?>
			
			<?php include(TEMPLATEPATH . '/final-fantasy-vii/12150/site-goals-set-down-tls-now-recruiting/') ;?>

			</div><!-- #content -->
		</section><!-- #primary -->
		
<?php get_footer(); ?>
