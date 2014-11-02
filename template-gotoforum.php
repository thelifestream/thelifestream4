<?php
/*
Template Name: Go to Forum
*/
?>

<?php get_header(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<section id="primary">
	<div id="content" role="main">
	
		<?php if (have_posts()) : while (have_posts()) : the_post();?>
		
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->
			
			<div class="entry-content">
			<?php 
				$threadID = mysql_real_escape_string($_GET["forumthread"]);
				$threadTitle = forum_get_thread_title($threadID);
				$wholeurl = "http://thelifestream.net/forums/showthread.php?goto=newpost&t=" . $threadID;
				
				if ($threadTitle == null) echo "<p>The threadID is invalid. Please try again.</p>";
				else {
					echo "<p>";
					echo "You've shown interest in the thread ";
					echo '<a href="' . $wholeurl . '">' . $threadTitle . "</a>";
					echo "</p>";
					the_content();
				}
			?>
			</div>

		<?php endwhile; endif; ?>
		
	<footer class="entry-meta">
		<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	
		
	</div><!-- #content -->
	</section><!-- #primary -->
	
</article><!-- #post-<?php the_ID(); ?> -->

<?php get_footer(); ?>