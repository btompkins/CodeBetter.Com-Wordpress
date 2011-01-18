<?php
/*
Plugin Name: List Authors Widget
Plugin URI: http://www.mikesmullin.com/
Description: Display a list of users in the Author group.
Version: 1.0
Author: Mike Smullin
Author URI: http://www.mikesmullin.com/
*/

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_authors_init() {

  // Check for the required plugin functions. This will prevent fatal
  // errors occurring when you deactivate the dynamic-sidebar plugin.
  if ( !function_exists('register_sidebar_widget') )
    return;

  function sanitize($name) {
    $name = strtolower($name); // all lowercase
    $name = preg_replace('/[^a-z0-9 ]/','', $name); // nothing but a-z 0-9 and spaces
    $name = preg_replace('/\s+/','-', $name); // spaces become hyphens
    return $name;
  }

  // Options and default values for this widget
  function widget_authors_options() {
    return array(
      'Title' => "Authors",
      'Option Count' => "No",
      'Exclude Administrator' => "Yes",
      'Show Full Name' => "Yes",
      'Hide Empty' => "Yes"
    );
  }

  // This is the function that outputs the Authors code.
  function widget_authors($args) {
    // $args is an array of strings that help widgets to conform to
    // the active theme: before_widget, before_title, after_widget,
    // and after_title are the array keys. Default tags: li and h2.
    extract($args);

    // Each widget can store and retrieve its own options.
    // Here we retrieve any options that may have been set by the user
    // relying on widget defaults to fill the gaps.
    $options = array_merge(widget_authors_options(), get_option('widget_authors'));
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

<!-- Authors -->
<ul>
<?php list_blogauthors(20, 180, false, '<li>', '</li>'); ?>
</ul>
<!-- /Authors -->

<?
    echo $after_widget;
  }

  function list_blogauthors($how_many, $how_long, $titleOnly, $begin_wrap, $end_wrap) {
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
				if ($titleOnly == false) {
					echo $begin_wrap.'<a href="'
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
  function widget_authors_control() {
    // Each widget can store and retrieve its own options.
    // Here we retrieve any options that may have been set by the user
    // relying on widget defaults to fill the gaps.
		if(($options = get_option('widget_authors')) === FALSE) $options = array();
    $options = array_merge(widget_authors_options(), $options);
    unset($options[0]); //returned by get_option(), but we don't need it

    // If user is submitting custom option values for this widget
    if ( $_POST['authors-submit'] ) {
      // Remember to sanitize and format use input appropriately.
      foreach($options as $key => $value)
        if(array_key_exists('authors-'.sanitize($key), $_POST))
          $options[$key] = strip_tags(stripslashes($_POST['authors-'.sanitize($key)]));

      // Save changes
      update_option('widget_authors', $options);
    }

    // Here is our little form segment. Notice that we don't need as
    // complete form. This will be embedded into the existing form.
    // Be sure you format your options to be valid HTML attributes
    // before displayihng them on the page.
    foreach($options as $key => $value): ?>
      <p style="text-align:left"><label for="authors-<?=sanitize($key)?>"><?=$key?>: <input style="width: 200px;" id="authors-<?=sanitize($key)?>" name="authors-<?=sanitize($key)?>" type="text" value="<?=htmlspecialchars($value, ENT_QUOTES)?>" /></label></p>
    <? endforeach;
    echo '<input type="hidden" id="authors-submit" name="authors-submit" value="1" />';
  }

  // This registers our widget so it appears with the other available
  // widgets and can be dragged and dropped into any active sidebars.
  register_sidebar_widget('Authors', 'widget_authors');

  // This registers our optional widget control form. Because of this
  // our widget will have a button that reveals a 300x100 pixel form.
  register_widget_control('Authors', 'widget_authors_control', 220, 50 * count(widget_authors_options()));
}

// Run our code later in case this loads prior to any required plugins.
add_action('plugins_loaded', 'widget_authors_init');

?>