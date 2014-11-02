
<?php
/*
Template Name: This is the Frontpage
*/
?>

<?php get_header(); ?>

	<div class="frontpage-primary">
		<div class="frontpage-content">
			<?php include('frontpage-previews.php'); ?>
		</div>
	</div>

	<!-- bottom section, highlights, two columns -->
	<div class="frontpage-highlights">
		<div class="frontpage-primary">
			<div class="tls_border">
				<h3>More News</h3>
			</div>

			<div class="frontpage-content">
				<div class="frontpage-content-left">
					<?php echo echo_posts_for_smaller_preview(); ?>
				</div>
				<div class="frontpage-clear"></div>
				<a href="news">Find all our News &amp; Updates here</a>
			</div>
		</div>

		<div class="frontpage-primary">
			<div class="tls_border">
				<h3>Latest from the Forums</h3>
			</div>

			<div class="frontpage-content">
				<div class="frontpage-content-right">
					<?php $forumCats = array(3, 4, 5, 6, 7, 8, 9, 10, 11, 14, 16, 17, 18, 19, 20, 21, 22, 23, 24, 46, 37, 36, 38, 47, 39, 41, 55, 43, 44, 48, 51, 56, 57, 58, 59, 61, 60, 65, 68); ?>
					<?php $catsNotLinked = array(6, 46, 47, 23, 24, 70, 18); // Categories that should not be linked directly to the forums ?>
					<?php $excludedThreads = array(10241, 10238, 11166, 5005, 6648, 5880); ?>
					<?php $noOfPosts = 8; // Wanted no of posts output ?>
					<?php forum_get_latest_posts($forumCats, $catsNotLinked, $excludedThreads, $noOfPosts); ?>
				</div>
				<div class="frontpage-clear"></div>
				<a href="forums">Read more on the forums</a>
			</div>
		</div>
	</div>
<?php get_footer(); ?>
