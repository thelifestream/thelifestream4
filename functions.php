<?php
/**
 * Twenty Eleven functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyeleven_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 * 
 * Unlike style.css, the functions.php of a child theme does not override its counterpart from the parent. 
 * Instead, it is loaded in addition to the parent�s functions.php. (Specifically, it is loaded right 
 * before the parent�s file.)
 **/

	/*
	 * ADDING ADDITIONAL CATEGORIES OF VARIOUS KINDS
	 */

	// Size of header image
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyeleven_header_image_width', 1024 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyeleven_header_image_height', 181 ) );
	
	// Add new size category
	add_action( 'after_setup_theme', 'theme_setup' );
	function theme_setup() { // theme_setup can change name here, and add_action can be placed below
  		add_image_size( 'preview', 500, 250, true ); // true/false refers to crop
  		//add_image_size( 'mediumpreview', 298, 213, true); // We no longer need this, there will only be one preview size
	}
	
	// Give Editors access to edit Widgets, Headers, Background etc (can't separate out the Widgets unfortunately)
	// so they can edit the Happening Now banner/ Widget
	$role = get_role('editor');
	$role->add_cap('edit_theme_options');

	// Register new Widget "Featured"
	register_sidebar( array(
		'name' => __( 'Featured', 'twentyeleven' ),
		'id' => 'featured',
		'description' => __( 'Featured', 'twentyeleven' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );
	
	function posted_on() {
		printf( __( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'twentyeleven' ), get_the_author() ) ),
			get_the_author()
		);
	}
	
	function posted_by_author() {
	printf( __( '<span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'twentyeleven' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentyeleven' ), get_the_author() ) ),
		get_the_author()
	);
	}

	function twentyeleven_posted_on() {
		printf( __( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'twentyeleven' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'twentyeleven' ), get_the_author() ) ),
			get_the_author()
		);
	}
	
	

	/*
	 * FEATURED IMAGES AND IMAGE HANDLING
	 */
	
	// Get thumbnail for post ('featured image') - MEDIUM version. Returns URL
	function get_featured_image_medium($postID) {	
		if (has_post_thumbnail($postID)) {
			$previewimage = wp_get_attachment_image_src(get_post_thumbnail_id($postID), 'preview');
			return $previewimage[0]; // URL
		} else { // Look for attached images and use one of them
			return get_first_post_image_medium($postID);
		}
	}
	
	// Returns the URL of the first attached image of a post in preview size
	function get_first_post_image_medium($postID) {
		$attachmentimages = get_attached_images_for_post($postID);
		
		if ($attachmentimages) {
			foreach ($attachmentimages as $a) {
				$firstuploadedimage = $a; // The first uploaded is in the back of the array for some reason
			}
			return $firstuploadedimage[0]; // URL

		} else { // we have no attachments, should return a random TLS image else {
			echo('No attachments');
		}
	}
	
	// Returns attachmed images for a post, arrays with URL, width & height
	function get_attached_images_for_post($postID) {
		$args = array(
		'post_type' => 'attachment',
		'numberposts' => null,
		'post_status' => null,
		'post_parent' => $postID
		);
		$attachments = get_posts($args);
		$attachmentimages = array();
		foreach ($attachments as $attachment) {	
			$attachmentimages[] = wp_get_attachment_image_src($attachment->ID, 'preview');
		}
		return $attachmentimages;
	}
	
	
	/*
	 * GETTING THE X/NTH POSTS AND STUFF
	 */
	
	// Get the first post ID
	function get_first_post_ID() {
		$args = array(
			'posts_per_page' => 1,
			'post_type' => 'post'
		);
		query_posts($args);
		$result = null;
		while(have_posts()) {
			the_post();
			$result = get_the_ID();
		}
		wp_reset_query();
		return $result;
	}

	// Get the first category for a post (by ID)
	function get_categories_for_post($id) {
		$categories = wp_get_post_categories($id);
		return $categories[0];

	}
	
	// Return ID of the 4 latest posts to use in the next function to check for posts in categories already listed
	// on the front page
	function get_four_latest_posts() {
		$result = array();
		$args = array('showposts' => 4, 'post_type' => 'post');
		query_posts($args);
		while (have_posts()) {
			the_post();	
			$result[] = get_the_ID();
		}
		wp_reset_query();
		return $result;
	}
	
	// Gets (3) latest posts from a cat and returns Array. Replaces get_3_last_posts_from_category($catid)
	function get_latest_posts_from_category($catid) {
		$args = array(
			'cat' => $catid,
			'showposts' => 3, //Change $showposts=x to change the number of links output
			'offset' => 1,
			'post_type' => 'post',
			'post_status' => 'publish',
		);
		$postsArray = array();
		query_posts($args);
		while (have_posts()) {
			the_post();
			// get the id's and chuck them in an array
			$postsArray[] = get_the_ID();
		}
		wp_reset_query();
		return $postsArray;
	}
	
	// Gets 3 latest posts from a cat and returns an Array
	function get_3_last_posts_from_category($catid) {
		$fourLatestPosts = get_four_latest_posts();
		$counter = 0; // counts posts in the same category
		foreach ($fourLatestPosts as $entry) {
			if (get_categories_for_post($entry) == $catid) {
				$counter++;
			}
		}
		$args = array(
			'cat' => $catid,
			'showposts' => 3, //Change $showposts=x to change the number of links output
			'offset' => $counter,
			'post_type' => 'post'
		);
		$postsArray = array();
		query_posts($args);
		while (have_posts()) {
			the_post();
			// get the id's and chuck them in an array
			$postsArray[] = get_the_ID();
		}
		wp_reset_query();
		return $postsArray;
	}
	
	// Echo the latest posts from a category
	function echo_last_posts_from_category($catid) {
		//$postsArray = get_3_last_posts_from_category($catid);
		$postsArray = get_latest_posts_from_category($catid);
		foreach ($postsArray as $postID) {
			echo('<li><a href="');
			echo(get_permalink($postID));
			echo(' ">');
			echo(get_the_title($postID));
			echo('</a></li>');
		}
	}
	
	// Returns an array of the first nth posts (change number)
	// TODO: consider using get_posts() for internal usage
	function get_first_nth_postids($top) {
		$allposts = array();
		query_posts(array('showposts' => $top, 'post_type' => 'post'));
		while (have_posts()) {
			the_post();
			$allposts[] = get_the_ID();
		}
		wp_reset_query();
		return $allposts;
	}

	// copypasta from http://stackoverflow.com/questions/2690504/php-producing-relative-date-time-from-timestamps
	// edited: show moar info if later date.
	function relativeTime($date) {
		$date = (int) $date;
	    $now = time();
	    $diff = $now - $date;

	    if ($diff < 60){
	        return sprintf($diff > 1 ? '%s seconds ago' : 'a second ago', $diff);
	    }

	    $diff = floor($diff/60);

	    if ($diff < 60){
	        return sprintf($diff > 1 ? '%s minutes ago' : 'one minute ago', $diff);
	    }

	    $diff = floor($diff/60);

	    if ($diff < 24){
	        return sprintf($diff > 1 ? '%s hours ago' : 'an hour ago', $diff);
	    }

	    $diff = floor($diff/24);

	    if ($diff < 7){
	    	$res = sprintf($diff > 1 ? '%s days ago' : 'yesterday', $diff);
	    	$res += 'at ';
	    	$res += date('h:i A', $date);
	    	return $res;
	    }

 		// output format for posts more than a week old
	    return date("F j, Y h:i A", $date);
	}

	// use this for both articles and forum poasts.
	function output_highlight_row($title, $link, $date, $categoryLink, $categoryName) {
		$timestamp = date('l, F j, Y h:i A', $date);
		$relativeDate = relativeTime($date);

		// check the total length of the date + category, if too long, shorten the category name
		// to something something chars + ellipsis because I dunno how the fuck to do it in CSS and I fail.
		$displayCategoryName = $categoryName;
		if (strlen($categoryName . $relativeDate) > 50) $displayCategoryName = substr($categoryName, 0, 26) . "...";
		$displayTitle = $title;
		if (strlen($title) > 50) $displayTitle = substr($title, 0, 47) . "...";


		// ew
		printf('<li><a href="%s">%s</a>', $link, $displayTitle);
		printf('<div class="post_meta">');
		printf('<span class="postdate" title="%s">%s in</span>', $timestamp, $relativeDate);
		printf(' <span><a href="%s" title="%s">%s</a></span>', $categoryLink, $categoryName, $displayCategoryName);
		printf('</div></li>');
	}

	function output_post_for_smaller_preview($post) {
		$permalink = get_permalink($post);
		$title = get_the_title($post);
		$postTimestamp = get_the_time('U', $post);
		$relativeDate = relativeTime($postTimestamp);
		$poastdate = get_the_time('l, F j, Y h:i A', $post);
		$theCategory = get_categories_for_post($post);
		$poastcat = get_the_category_by_ID($theCategory);
		$poastcaturl = get_category_link($theCategory);
		
		output_highlight_row($title, $permalink, $postTimestamp, $poastcaturl, $poastcat);
	}

	// Echo the smaller previews (only titles with links) - update function above to change how many
	function echo_posts_for_smaller_preview() {
		$firstfifteenposts = get_first_nth_postids(12);
		$previewposts = array_slice($firstfifteenposts, 4, 8);
		$bool = true;
		echo '<ul>';
		foreach($previewposts as $post) {
			output_post_for_smaller_preview($post);
		}
		echo '</ul>';
	}

	// Echo the first post preview, with title and excerpt
	function echo_first_post_preview($id) {
		$theCategory = get_categories_for_post($id);
		$theExcerpt = get_post($id)->post_excerpt;
		$theExcerpt = substr($theExcerpt, 0, 161);
		$theExcerpt = rtrim($theExcerpt, ' '); // Trim off the last character if whitespace to avoid "some word ..."
		echo('<p class="previews-stories-category"><a href="' . get_category_link($theCategory) . '">');
		echo(get_the_category_by_ID($theCategory));
		echo('</a></p><h1><a href="');
		echo(get_permalink($id));
		echo('">');
		echo(get_the_title($id));
		echo('</a></h1>');
		//below is the old function used - I'll leave it in if we ever decide we need the first x characters from a post
		//echo('<p class="previews-topstoryexcerpt">' . new_excerpt_firstpreview($id, 20, '...', ''));
		echo('<p class="previews-topstoryexcerpt">' . $theExcerpt);
		if (strlen($theExcerpt) >= 160) { // If Excerpt reaches the max limit, chances are we have to shave it off with an ...
			echo('...');
		}
		echo('<a href="');
		echo(get_permalink($id));
		echo('"> Continue reading this story</a></p>');
	}
	
	// ... and with related articles
	function echo_first_post_preview_related($id) {
		$theCategory = get_categories_for_post($id);
		//$latestPostsFromCat = get_3_last_posts_from_category($theCategory);
		$latestPostsFromCat = get_latest_posts_from_category($theCategory);
		if (count($latestPostsFromCat) > 0) {
			echo('<p>Related articles</p>');
			echo('<ul>');
			echo_last_posts_from_category($theCategory);
			echo('</ul>');
		}
	}
	
	// Echo the first post previews belonging image
	function echo_first_post_preview_image($id) {
		echo('<img src="' . get_featured_image_medium($id) . '" />');
	}
	
	// Creates 'custom made' the_excerpt
	// From http://smashingwp.com/tutorials/add-a-custom-excerp-size-in-you-wordpress-theme/
	function new_excerpt_firstpreview($id, $excerpt_length, $ending, $superending) {
		$post = get_post($id);
		$text = $post->post_content;
		$text = strip_shortcodes( $text );
	
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text);
                
		$words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
		if ( count($words) > $excerpt_length ) {
			array_pop($words);
			$text = implode(' ', $words);
			$text = $text . $ending;
			$result ="$text $superending";
		} else {
			$text = implode(' ', $words);
			$result = "<p>$test</p>";
		}
        
		// filthy hack for Flare social media thing
        $result = substr($result, 28);
		
        return $result;
	}
	
	// Echo previews A to B
	function echo_n_post_preview($fromPreviewNo, $toPreviewNo) {
		query_posts(array('posts_per_page' => $toPreviewNo, 'post_type' => 'post'));
		$count = 1;
		while ( have_posts() ) {
			the_post();
			if ($count >= $fromPreviewNo) {
				$theID = get_the_ID();
				$theCategory = get_categories_for_post($theID);
				$previewImageElements = get_featured_image_medium($theID);		
				echo('</p><div class="mediumimg">');
				//echo('<img src="' . $previewImageElements[0] . '" width="' . $previewImageElements[1] . '" height="' . $previewImageElements[2] . '"/>');
				echo('<img src="' . $previewImageElements . '" />');
				echo('</div>');
				echo('<p class="previews-stories-category"><a href="' . get_category_link($theCategory) . '">');
				echo(get_the_category_by_ID($theCategory));
				echo('</a></p><a href="');
				echo the_permalink($theID);
				echo('"><h2>');
				echo the_title();
				echo('</h2></a>');
			}
			$count ++;
		}
		wp_reset_query();
	}

	/*
	 * FIXXES
	 */

	// Function found on Internet. Added to be able to add the sidebar to single Posts ("blogposts") as it was
	// removed as being default in Twenty Eleven - see The Lifestream 4 documentation for sauce
	add_filter('body_class', 'fix_body_class_for_sidebar', 20, 2);
	function fix_body_class_for_sidebar($wp_classes, $extra_classes) {
		if (is_single()) {
		   if (in_array('singular',$wp_classes)){
				foreach($wp_classes as $key => $value) {
					if ($value == 'singular') {
						unset($wp_classes[$key]);
					}
				}
			}
		}
		return array_merge($wp_classes, (array) $extra_classes);
	}
	
	/*
	 * PULLING OUT STUFF FROM THE FORUMS
	 */
	
	// Get the name of a thread
	function forum_get_thread_title($threadid) {
		if (!is_numeric($threadid)) return null; // Just to be sure
		include('forumcall.php');
		$query_posts = "select title
			from thread
			where threadid = " . $threadid;
		$result = mysql_query($query_posts);
		include('forumcallclose.php');
		$row = mysql_fetch_row($result);
		return $row[0];
	}
	
	// Returns a thread from the forum.
	function forum_get_thread($threadid) {
		include('forumcall.php');
		
		$query_posts = "select post.postid, post.dateline, post.userid, user.username, customavatar.filename, usergroup.usertitle, post.pagetext
			from post
			inner join user
			on post.userid = user.userid
			inner join customavatar
			on user.userid = customavatar.userid
			inner join usergroup
			on user.displaygroupid = usergroup.usergroupid
			where threadid=" . $threadid . "
			order by postid";
		
		$result = mysql_query($query_posts);
		
		if ($result == '') {
			echo('String is empty');
			echo('<br />');
		}
		
		$numberofrows = mysql_num_rows($result);
		$numberofcolumns = mysql_num_fields($result);
		
		for ($i = 0; $i < $numberofrows; $i++) {
			$row = mysql_fetch_row($result);
			for ($j = 0; $j < $numberofcolumns; $j++) {
				echo($row[$j]);
				echo ' ';
			}
			echo '<br />';
		}
		include('forumcallclose.php');
	}
	
	// Returns query string for getting the n first posts from the forum
	function forum_get_latest_posts_string($forumcats, $excludedThreads, $noOfPosts) {
		
		// Creating string for forum categories to be included
		$stringForumcats = $forumcats[0]; 
		for ($i = 1; $i < count($forumcats); $i++) {
			$stringForumcats .= ", " . $forumcats[$i]; 
		}
		
		// Creating string for threads to be excluded (NSFW etc)
		$stringExcludedThreads = $excludedThreads[0]; 
		for ($i = 1; $i < count($excludedThreads); $i++) {
			$stringExcludedThreads .= ", " . $excludedThreads[$i]; 
		}
		
		include('forumcall.php');

		$query_posts = "
			select thread.threadid, thread.title, thread.forumid, forum.title, thread.lastpost
			from thread
			inner join post
			on post.postid = thread.lastpostid
			inner join forum
			on forum.forumid = thread.forumid
			where thread.forumid IN ( "; 
		$query_posts .= $stringForumcats;
		$query_posts .= " ) AND thread.threadid NOT IN (";
		$query_posts .= $stringExcludedThreads;
		$query_posts .= ")
			order by thread.lastpost DESC
			LIMIT 0, ";
		$query_posts .= $noOfPosts;
		$query_posts .= " ";

		$result = mysql_query($query_posts);
		include('forumcallclose.php');
		return $result;
	}
	
	// Returns the n threads with the latest posts from the forums
	function forum_get_latest_posts($forumcats, $catsNotLinked, $excludedThreads, $noOfPosts) {
		if (!$forumcats || $noOfPosts < 1) break; // Only continue if $categories has content
		$result = forum_get_latest_posts_string($forumcats, $excludedThreads, $noOfPosts);
		$numberofrows = mysql_num_rows($result);

		echo '<ul class="forum-posts">';
		for ($i = 0; $i < $numberofrows; $i++) {
			$row = mysql_fetch_row($result);
			
			$threadUrl = "http://thelifestream.net/forums/showthread.php?goto=newpost&t={$row[0]}";
			$title = $row[1];
			$timestamp = $row[4];
			$categoryUrl = "http://thelifestream.net/forums/forumdisplay.php?f={$row[2]}";
			$categoryName = $row[3];
			
			if (in_array($row[2], $catsNotLinked)) {
				$threadUrl = "http://thelifestream.net/welcome-to-our-forums/?forumthread=" . $row[0]; // Overwrite
			}
			output_highlight_row($title, $threadUrl, $timestamp, $categoryUrl, $categoryName);
		}
		echo '</ul>';
	}
	
	/*
	 * WIDGETS
	 */
	
	// Class for creating the unique "Menu for Sidebar" Widget
	class MenuForSidebar extends WP_Widget {
	
		// Constructor
		function MenuForSidebar() {
			// Instantiate the parent object
			$widget_ops = array( 'description' => __('Use this widget to add menus to the Sidebar.') );
			parent::__construct( 'menu_sidebar', 'Menu for Sidebar', $widget_ops );
		}
	
		function widget( $args, $instance ) {
			// Widget output
			
			$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
			
			/* Remove check for now - gives us pain
			 * if ( !$nav_menu )
				echo('Empty?!');
				return;*/
			
			$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$instance['imagelink'] = apply_filters( 'widget_title', empty( $instance['imagelink'] ) ? '' : $instance['imagelink'], $instance, $this->id_base );
			
			$beforeimagetag = '<img src="';
			$afterimagetag = '" />';
			
			echo $args['before_widget'];
			
			if ( !empty($instance['imagelink']) ) {
				echo $args['before_title'] . $beforeimagetag . $instance['imagelink'] . $afterimagetag . $args['after_title'];
			} else {
				echo $args['before_title'] . $instance['title'] . $args['after_title'];
			}
	
			wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu ) );
	
			echo $args['after_widget'];
		}
	
		function update( $new_instance, $old_instance ) {
			// Save widget options
			$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
			$instance['imagelink'] = strip_tags( stripslashes($new_instance['imagelink']) );
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
			return $instance;
		}
	
		function form( $instance ) {
			// Output admin widget options form
			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			$imagelink = isset( $instance['imagelink'] ) ? $instance['imagelink'] : '';
			$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
	
			// Get menus
			$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
	
			// If no menus exists, direct the user to go and create some.
			if ( !$menus ) {
				echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
				return;
			}
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('imagelink'); ?>"><?php _e('Image link:') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('imagelink'); ?>" name="<?php echo $this->get_field_name('imagelink'); ?>" value="<?php echo $imagelink; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
				<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
			<?php
				foreach ( $menus as $menu ) {
					$selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
					echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
				}
			?>
				</select>
			</p>
			<?php
		}
	}

	function ourwidgets_register_widgets() {
		register_widget( 'MenuForSidebar' );
	}
	add_action( 'widgets_init', 'ourwidgets_register_widgets' );


			
?>