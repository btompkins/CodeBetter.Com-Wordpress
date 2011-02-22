<?php
/*
Plugin Name: WPMU Recent Posts Widget
Plugin URI: http://wordpress.org/extend/plugins/multisite-recent-posts-widget/
Description: Creates a widget to show a list of the most recent posts across a WordPress MU installation.  This plugin is based on the work of many authors.
Version: 1.1
Author: Angelo
Author URI: http://bitfreedom.com/
*/

/*
Parameter explanations
$how_many: how many recent posts are being displayed
$how_long: time frame to choose recent posts from (in days), set to 0 to disable
$titleOnly: true (only title of post is displayed) OR false (title of post and name of blog are displayed)
$begin_wrap: customise the start html code to adapt to different themes
$end_wrap: customise the end html code to adapt to different themes

Sample call: wpmu_recent_posts_mu(5, 30, true, '<li>', '</li>'); >> 5 most recent entries over the past 30 days, displaying titles only
*/

function wpmu_recent_posts_mu($how_many=10, $how_long=0, $titleOnly=true, $begin_wrap="\n<li>", $end_wrap="</li>") {
	global $wpdb;
	global $table_prefix;
	$counter = 0;
	
	// get a list of blogs in order of most recent update. show only public and nonarchived/spam/mature/deleted
	if ($how_long > 0) {
		$blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE
			public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0'
			AND last_updated >= DATE_SUB(CURRENT_DATE(), INTERVAL $how_long DAY)
			ORDER BY last_updated DESC");
	} else {
		$blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE
			public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0'
			ORDER BY last_updated DESC");
	}

	if ($blogs) {
		// Should we make <ul> optional since this is a widget now?
		echo "<ul>";
		foreach ($blogs as $blog) {
			// we need _posts and _options tables for this to work
			$blogOptionsTable = $wpdb->base_prefix.$blog."_options";
		    	$blogPostsTable = $wpdb->base_prefix.$blog."_posts";
			$options = $wpdb->get_results("SELECT option_value FROM
				$blogOptionsTable WHERE option_name IN ('siteurl','blogname') 
				ORDER BY option_name DESC");
		        // we fetch the title and ID for the latest post
			if ($how_long > 0) {
				$thispost = $wpdb->get_results("SELECT ID, post_title
					FROM $blogPostsTable WHERE post_status = 'publish'
					AND ID > 1
					AND post_type = 'post'
					AND post_date >= DATE_SUB(CURRENT_DATE(), INTERVAL $how_long DAY)
					ORDER BY id DESC LIMIT 0,1");
			} else {
				$thispost = $wpdb->get_results("SELECT ID, post_title
					FROM $blogPostsTable WHERE post_status = 'publish'
					AND ID > 1
					AND post_type = 'post'
					ORDER BY id DESC LIMIT 0,1");
			}
			// if it is found put it to the output
			if($thispost) {
				// get permalink by ID.  check wp-includes/wpmu-functions.php
				$thispermalink = get_blog_permalink($blog, $thispost[0]->ID);
				if ($titleOnly == false) {
					echo $begin_wrap.'<a href="'
					.$thispermalink.'">'.$thispost[0]->post_title.'</a> by <a href="'
					.$options[0]->option_value.'">'
					.$options[1]->option_value.'</a>'.$end_wrap;
					$counter++;
					} else {
						echo $begin_wrap.$thispost[0]->post_author.'<a href="'.$thispermalink
						.'">'.$thispost[0]->post_title.'</a>'.$end_wrap;
						$counter++;
					}					
			}				
			
			// don't go over the limit
			if($counter >= $how_many) {
				break; 
			}
		}
		echo "</ul>";
	}
}

function wpmu_recent_posts_control() {
	$options = get_option('wpmu_recent_posts_widget');

	if (!is_array( $options )) {
		$options = array(
			'title' => 'Last Posts',
			'number' => '10',
			'days' => '-1'
		);
	}

	if ($_POST['wpmu_recent_posts_submit']) {
		$options['title'] = htmlspecialchars($_POST['wpmu_recent_posts_title']);
		$options['number'] = intval($_POST['wpmu_recent_posts_number']);
		$options['days'] = intval($_POST['wpmu_recent_posts_days']);
		update_option("wpmu_recent_posts_widget", $options);
	}

?>

	<p>
	<label for="wpmu_recent_posts_title">Title: </label>
	<br /><input type="text" id="wpmu_recent_posts_title" name="wpmu_recent_posts_title" value="<?php echo $options['title'];?>" />
	<br /><label for="wpmu_recent_posts_number">Number of posts to show: </label>
	<input type="text" size="3" id="wpmu_recent_posts_number" name="wpmu_recent_posts_number" value="<?php echo $options['number'];?>" />
	<br /><label for="wpmu_recent_posts_days">Number of days to limit: </label>
	<input type="text" size="3" id="wpmu_recent_posts_days" name="wpmu_recent_posts_days" value="<?php echo $options['days'];?>" />
	<input type="hidden" id="wpmu_recent_posts_submit" name="wpmu_recent_posts_submit" value="1" />
	</p>

<?php
}

function wpmu_recent_posts_widget($args) {

	extract($args);

	$options = get_option("wpmu_recent_posts_widget");

	if (!is_array( $options )) {
		$options = array(
			'title' => 'Last Posts',
			'number' => '10',
			'days' => '-1'
		);
	}

	echo $before_widget;
	echo "$before_title $options[title] $after_title";
	wpmu_recent_posts_mu($options['number'],$options['days'],false,"\n<li>","</li>");
	echo $after_widget;
}

function wpmu_recent_posts_init() {

	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	register_sidebar_widget(__("WPMU Recent Posts"),"wpmu_recent_posts_widget");
	register_widget_control(__("WPMU Recent Posts"),"wpmu_recent_posts_control");
}

add_action("plugins_loaded","wpmu_recent_posts_init");
?>
