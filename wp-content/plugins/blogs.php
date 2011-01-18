<?php
/*
Plugin Name: List blogs Widget
Plugin URI: http://www.mikesmullin.com/
Description: Display a list of users in the Author group.
Version: 1.0
Author: Mike Smullin
Author URI: http://www.mikesmullin.com/
*/

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_blogs_init() {

  // Check for the required plugin functions. This will prevent fatal
  // errors occurring when you deactivate the dynamic-sidebar plugin.
  if ( !function_exists('register_sidebar_widget') )
    return;

  function sanitize_blogs($name) {
    $name = strtolower($name); // all lowercase
    $name = preg_replace('/[^a-z0-9 ]/','', $name); // nothing but a-z 0-9 and spaces
    $name = preg_replace('/\s+/','-', $name); // spaces become hyphens
    return $name;
  }

  // Options and default values for this widget
  function widget_blogs_options() {
    return array(
      'Title' => "Blogs" 
    );
  }

  // This is the function that outputs the blogs code.
  function widget_blogs($args) {
    // $args is an array of strings that help widgets to conform to
    // the active theme: before_widget, before_title, after_widget,
    // and after_title are the array keys. Default tags: li and h2.
    extract($args);

    // Each widget can store and retrieve its own options.
    // Here we retrieve any options that may have been set by the user
    // relying on widget defaults to fill the gaps.
    $options = array_merge(widget_blogs_options(), get_option('widget_blogs'));
    unset($options[0]); //returned by get_option(), but we don't need it

    // These lines generate our output. Widgets can be very complex
    // but as you can see here, they can also be very, very simple.
    echo $before_widget . $before_title . $options['Title'] . $after_title;

    // Translate yes/no values
    if(count($options))
    foreach($options as $k=>$v)
      switch(strtolower(trim($v))) {
        case 'yes': case 'true': case '1':
          $options[$k] = 1; break;
        default:
          $options[$k] = 0; break;
      }
?>

<!-- blogs -->
<ul>
<?php list_blogs(20, 60, false, '<li>', '</li>'); ?>
</ul>
<!-- /blogs -->

<?
    echo $after_widget;
  }

  function list_blogs($how_many, $how_long, $titleOnly, $begin_wrap, $end_wrap) {
	global $wpdb;
	$counter = 0;
	
	// get a list of blogs in order of most recent update. show only public and nonarchived/spam/mature/deleted
	$blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE
		public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' AND
		last_updated >= DATE_SUB(CURRENT_DATE(), INTERVAL $how_long DAY)
		ORDER BY last_updated DESC");

	if ($blogs) {
	
		foreach ($blogs as $blog) {		
			// we need _posts and _options tables for this to work
			$blogOptionsTable = "wp_".$blog."_options";
		    	$blogPostsTable = "wp_".$blog."_posts";
			$options = $wpdb->get_results("SELECT option_value FROM
				$blogOptionsTable WHERE option_name IN ('siteurl','blogname') 
				ORDER BY option_name DESC");
		        // we fetch the title and ID for the latest post
			$thispost = $wpdb->get_results("SELECT ID, post_title 
				FROM $blogPostsTable WHERE post_status = 'publish' 
				AND post_type = 'post' AND post_date >= DATE_SUB(CURRENT_DATE(), INTERVAL $how_long DAY)
				ORDER BY id DESC LIMIT 0,1");

			// if it is found put it to the output
			if($thispost) {			
				// get permalink by ID.  check wp-includes/wpmu-functions.php
				$thispermalink = get_blog_permalink($blog, $thispost[0]->ID);
				if ($titleOnly == false) {
					echo $begin_wrap.'<a href="'.$thispermalink
					.'">'.$thispost[0]->post_title.'</a> <br/> by <a href="'
					.$options[0]->option_value.'">'
					.$options[1]->option_value.'</a>'.$end_wrap;
					$counter++;
					} else {
						echo $begin_wrap.'<a href="'.$thispermalink
						.'">'.$thispost[0]->post_title.'</a>'.$end_wrap;
						$counter++;
					}
			}
			// don't go over the limit
			if($counter >= $how_many) { 
				break; 
			}
		}
	}
  }
  // This is the function that outputs the form to let the users edit
  // the widget's title. It's an optional feature that users cry for.
  function widget_blogs_control() {
    // Each widget can store and retrieve its own options.
    // Here we retrieve any options that may have been set by the user
    // relying on widget defaults to fill the gaps.
		if(($options = get_option('widget_blogs')) === FALSE) $options = array();
    $options = array_merge(widget_blogs_options(), $options);
    unset($options[0]); //returned by get_option(), but we don't need it

    // If user is submitting custom option values for this widget
    if ( $_POST['blogs-submit'] ) {
      // Remember to sanitize_blogs and format use input appropriately.
      foreach($options as $key => $value)
        if(array_key_exists('blogs-'.sanitize_blogs($key), $_POST))
          $options[$key] = strip_tags(stripslashes($_POST['blogs-'.sanitize_blogs($key)]));

      // Save changes
      update_option('widget_blogs', $options);
    }

    // Here is our little form segment. Notice that we don't need as
    // complete form. This will be embedded into the existing form.
    // Be sure you format your options to be valid HTML attributes
    // before displayihng them on the page.
    foreach($options as $key => $value): ?>
      <p style="text-align:left"><label for="blogs-<?=sanitize_blogs($key)?>"><?=$key?>: <input style="width: 200px;" id="blogs-<?=sanitize_blogs($key)?>" name="blogs-<?=sanitize_blogs($key)?>" type="text" value="<?=htmlspecialchars($value, ENT_QUOTES)?>" /></label></p>
    <? endforeach;
    echo '<input type="hidden" id="blogs-submit" name="blogs-submit" value="1" />';
  }

  // This registers our widget so it appears with the other available
  // widgets and can be dragged and dropped into any active sidebars.
  register_sidebar_widget('blogs', 'widget_blogs');

  // This registers our optional widget control form. Because of this
  // our widget will have a button that reveals a 300x100 pixel form.
  register_widget_control('blogs', 'widget_blogs_control', 220, 50 * count(widget_blogs_options()));
}

// Run our code later in case this loads prior to any required plugins.
add_action('plugins_loaded', 'widget_blogs_init');

?>