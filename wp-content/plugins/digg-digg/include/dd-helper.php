<?php
require_once 'dd-global-variable.php';

//filter for ajax floating javascript
function dd_filter_weird_characters($str){
	
	$str = str_replace("\\\"", "\"", $str);
	$str = str_replace("\'", "'", $str);
	
	return $str;
}

function dd_IsDisplayAllow($ddDisplay){
	
	if(dd_IsDisplayOptionAllow($ddDisplay[DD_DISPLAY_OPTION],$ddDisplay[DD_CATEORY_OPTION])){
		return true;
	}else{
		return false;
	}
}

/**
 * Check if the current page allow to display button
 * @param $ddDisplayOptions -> $ddNormalDisplay[DD_DISPLAY_OPTION])
 * @param $ddCategoryOptions -> $ddNormalDisplay[DD_CATEORY_OPTION]
 * 
 */
function dd_IsDisplayOptionAllow($ddDisplayOptions, $ddCategoryOptions){
	
	//get display option
    $dd_display_home = $ddDisplayOptions[DD_DISPLAY_OPTION_HOME];
	$dd_display_page = $ddDisplayOptions[DD_DISPLAY_OPTION_PAGE];
	$dd_display_post = $ddDisplayOptions[DD_DISPLAY_OPTION_POST];
	$dd_display_category = $ddDisplayOptions[DD_DISPLAY_OPTION_CAT];
	$dd_display_tag = $ddDisplayOptions[DD_DISPLAY_OPTION_TAG];
	$dd_display_archive = $ddDisplayOptions[DD_DISPLAY_OPTION_ARCHIVE];
	
	//TODO: can it display in feed?
	$dd_display_feed = DD_DISPLAY_OFF;

	if(is_home() && ($dd_display_home==DD_DISPLAY_ON)){
		return true;
	}else if(is_feed() && ($dd_display_feed==DD_DISPLAY_ON)){
		return true;
	}else if(is_single() && ($dd_display_post==DD_DISPLAY_ON)){
		
		//check category allow
		return dd_IsCategoryAllow($ddCategoryOptions);
		
		
	}else if(is_category() && ($dd_display_category==DD_DISPLAY_ON)){
		return true;
	}else if(is_page() && ($dd_display_page==DD_DISPLAY_ON)){
		return true;
	}else if(is_tag() && ($dd_display_tag==DD_DISPLAY_ON)){
		return true;
	}else if(is_archive() && ($dd_display_archive==DD_DISPLAY_ON)){
		return true;
	}else{
		return false;
	}
}


/**
 * Genaric function to get text base on id and key
 * @param $id - array key
 * @param $key - array key
 */
function dd_GetText($id, $key){
	
	if($id==DD_DISPLAY_OPTION){
		switch($key){
			case DD_DISPLAY_OPTION_HOME :
				return DD_DISPLAY_OPTION_LABEL_HOME;
				break;
			case DD_DISPLAY_OPTION_POST :
				return DD_DISPLAY_OPTION_LABEL_POST;
				break;
			case DD_DISPLAY_OPTION_PAGE :
				return DD_DISPLAY_OPTION_LABEL_PAGE;
				break;
			case DD_DISPLAY_OPTION_CAT :
				return DD_DISPLAY_OPTION_LABEL_CAT;
				break;
			case DD_DISPLAY_OPTION_TAG :
				return DD_DISPLAY_OPTION_LABEL_TAG;
				break;
			case DD_DISPLAY_OPTION_ARCHIVE :
				return DD_DISPLAY_OPTION_LABEL_ARCHIVE;
				break;
			default:
       			return DD_DEFAULT_TEXT;
				break;
		}
	}else if($id==DD_LINE_UP_OPTION){
		switch($key){
			case DD_LINE_UP_OPTION_SELECT_HORIZONTAL :
				return DD_LINE_UP_OPTION_LABEL_HORIZONTAL;
				break;
			case DD_LINE_UP_OPTION_SELECT_VERTICAL :
				return DD_LINE_UP_OPTION_LABEL_VERTICAL;
				break;
			default:
       			return DD_DEFAULT_TEXT;
				break;
		}
	}
}

/**
 * Merge two arrays, $newArray's new key will remain, but value will override by $mergeArray
 * $a = array('abcArray' => array('a'=>'1','b'=>'2','c'=>'3'));
 * $b = array('abcArray' => array('a'=>'1','b'=>'3'));
 * $result = dd_array_merge_subkey($a,$b);
 * $result = array('abcArray' => array('a'=>'1','b'=>'3','c'=>'3'));
 */
function dd_array_merge_subkey($newArray, $oldArray){
	
	foreach(array_keys($newArray) as $key){
	    	
		foreach(array_keys($newArray[$key]) as $subkey){
			
			//echo '<h1>' . $key . '-' . $subkey . '</h1>';
			if(isset($oldArray[$key][$subkey])){
				$newArray[$key][$subkey] = $oldArray[$key][$subkey];
			}
			
		}
    }	
	return $newArray;
}

//initial the value
function dd_initialIt(){
	
	//http://codex.wordpress.org/Function_Reference/register_activation_hook
	dd_clear_form_global_config();
	dd_clear_form_normal_display();
	dd_clear_form_float_display();
}

//global config, clear form
function dd_clear_form_global_config($type=""){
	
	global $ddGlobalConfig;
	
	$old_ddGlobalConfig = get_option(DD_GLOBAL_CONFIG);
	
	if($old_ddGlobalConfig==DD_EMPTY_VALUE || $type==DD_FUNC_TYPE_RESET){
		//reset all value
		update_option(DD_GLOBAL_CONFIG, $ddGlobalConfig);
		
	}else{
		//in case new key is added during plugin initilization, merge new and old array
		$result = dd_array_merge_subkey($ddGlobalConfig,$old_ddGlobalConfig);
		update_option(DD_GLOBAL_CONFIG, $result);
	}
	
}

//normal display, clear form
function dd_clear_form_normal_display($type=""){
	
	global $ddNormalDisplay,$ddNormalButtons;
	
	/********* Normal Display **************/
	$old_ddNormalDisplay = get_option(DD_NORMAL_DISPLAY_CONFIG);
	
	if($old_ddNormalDisplay==DD_EMPTY_VALUE || $type==DD_FUNC_TYPE_RESET){
		//reset all value	
		
		update_option(DD_NORMAL_DISPLAY_CONFIG, $ddNormalDisplay);
		
	}else{
		//in case new key is added during plugin initilization, merge new and old array
		$result = dd_array_merge_subkey($ddNormalDisplay,$old_ddNormalDisplay);
		update_option(DD_NORMAL_DISPLAY_CONFIG, $result);
	}
	
	
	if(get_option(DD_NORMAL_BUTTON)==DD_EMPTY_VALUE || $type==DD_FUNC_TYPE_RESET){
		//reset all value
		update_option(DD_NORMAL_BUTTON, $ddNormalButtons);
		
	}else{
		//in case any new class is added during plugin initilization, 
		//new class properties will not added until the save operation	
		$old_ddNormalButtons = get_option(DD_NORMAL_BUTTON);
		$result = dd_array_merge_subkey($ddNormalButtons,$old_ddNormalButtons);
		
		//add for final display
		foreach($result[DD_NORMAL_BUTTON_DISPLAY] as $key => $value){
			if(($value->getOptionAppendType()!=DD_SELECT_NONE)){
				$result[DD_NORMAL_BUTTON_FINAL][$key] = $value;
			}
	    }
			
		update_option(DD_NORMAL_BUTTON, $result);
		
	}

}

//float display, clear form
function dd_clear_form_float_display($type=""){
	
	global $ddFloatDisplay,$ddFloatButtons;
	
	/********* Float Display **************/
	$old_ddFloatDisplay = get_option(DD_FLOAT_DISPLAY_CONFIG);
	
	if($old_ddFloatDisplay==DD_EMPTY_VALUE || $type==DD_FUNC_TYPE_RESET){
		//reset all value	
		update_option(DD_FLOAT_DISPLAY_CONFIG, $ddFloatDisplay);
		
	}else{
		//in case new key is added during plugin initilization, merge new and old array
		$result = dd_array_merge_subkey($ddFloatDisplay,$old_ddFloatDisplay);
		update_option(DD_FLOAT_DISPLAY_CONFIG, $result);
	}
	
	
	if(get_option(DD_FLOAT_BUTTON)==DD_EMPTY_VALUE || $type==DD_FUNC_TYPE_RESET){
		//reset all value
		update_option(DD_FLOAT_BUTTON, $ddFloatButtons);
		
	}else{
		//in case any new class is added during plugin initilization, 
		//new class properties will not added until the save operation	
		$old_ddFloatButtons = get_option(DD_FLOAT_BUTTON);
		$result = dd_array_merge_subkey($ddFloatButtons,$old_ddFloatButtons);

		//add for final display
	    foreach($result[DD_FLOAT_BUTTON_DISPLAY] as $key => $value){
			if(($value->getOptionAjaxLeftFloat()!=DD_DISPLAY_OFF)){
				$result[DD_FLOAT_BUTTON_FINAL][$key] = $value;
			}
	    }
	         
		update_option(DD_FLOAT_BUTTON, $result);
		
	}
	    
}

/**
 * Validate whether allow buttons to display
 * @return true page excluded, do not display
 */
function dd_isThisPageExcluded($content){
	
	if (preg_match("/" . DD_DISABLED . "/i", $content)) {
	    return true;
	} else {
	    return false;
	}
	
}

/**
 * Check if the current category allow to display button
 * @param $category_options -> $ddNormalDisplay[DD_CATEORY_OPTION])
 */
function dd_IsCategoryAllow($category_options){
	
	$catOptions = $category_options[DD_CATEORY_OPTION_RADIO];
	$catOptionsInclude = $category_options[DD_CATEORY_OPTION_TEXT_INCLUDE];
	$catOptionsExclude = $category_options[DD_CATEORY_OPTION_TEXT_EXCLUDE];
	
	if($catOptions == DD_CATEORY_OPTION_RADIO_INCLUDE){
		
		return dd_IsCategoryInclude($catOptionsInclude);
		
	}else if($catOptions == DD_CATEORY_OPTION_RADIO_EXCLUDE){

		return !dd_IsCategoryExclude($catOptionsExclude);
		
	}else{
		
		return dd_IsCategoryInclude($catOptionsInclude);
	}
	
}

/**
 * 
 * @param $category_allow - categories allow to display
 * @return true = allow, false = disallow
 */
function dd_IsCategoryInclude($category_allow){
	
	$category_allow = trim(strtolower($category_allow));
	
	//echo 'Category allow : ' . $category_allow; 
	if($category_allow == '' || ($category_allow==strtolower(DD_ALL_VALUE))){
		return true;
	}

	$cats_allow = explode(",", strtolower($category_allow));

	foreach((get_the_category()) as $post_category) {
		
		//echo '<br/> category name : ' . $post_category->cat_name; 
		foreach($cats_allow as $cat_allow){
			
			$post_category_name = strtolower($post_category->cat_name);
			//echo '<br/>Category allow loop : ' . $cat_allow . '<br/> current category name : ' . $post_category_name;

			if($post_category_name==trim($cat_allow)){
				//echo ' match';
				return true;
			}else{
				//echo ' not match';
			}
		}
	}
	return false;
}

/**
 * 
 * @param $category_disallow - categories disallow to display
 * @return true = disallow, false = allow
 */
function dd_IsCategoryExclude($category_disallow){
	
	$category_disallow = trim(strtolower($category_disallow));
	
	//echo 'Category disallow : ' . $category_disallow; 
	if($category_disallow == '' || ($category_disallow==strtolower(DD_NONE_VALUE))){
		return false;
	}

	$cats_disallow = explode(",", strtolower($category_disallow));

	foreach((get_the_category()) as $post_category) {
		
		//echo '<br/> category name : ' . $post_category->cat_name; 
		foreach($cats_disallow as $cat_disallow){
			
			$post_category_name = strtolower($post_category->cat_name);
			//echo '<br/>Category allow loop : ' . $cat_disallow . '<br/> current category name : ' . $post_category_name;

			if($post_category_name==trim($cat_disallow)){
				//echo ' match';
				return true;
			}else{
				//echo ' not match';
			}
		}
	}
	return false;
}

function get_server() {
	$protocol = 'http';
	if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') {
		$protocol = 'https';
	}
	$host = $_SERVER['HTTP_HOST'];
	$baseUrl = $protocol . '://' . $host;
	if (substr($baseUrl, -1)=='/') {
		$baseUrl = substr($baseUrl, 0, strlen($baseUrl)-1);
	}
	return $baseUrl;
}

function getCurPageURL() {
	 $pageURL = 'http';
	 
	 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
	 	$pageURL .= "s";
	 }
	 $pageURL .= "://";
	 
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 
	 return $pageURL;
}

//get normal display settings
$ddNormalDisplayTemp = get_option(DD_NORMAL_DISPLAY_CONFIG);
//if diggdigg excerp is allow
if($ddNormalDisplayTemp[DD_EXCERP_OPTION][DD_EXCERP_OPTION_DISPLAY]==DD_DISPLAY_ON){
    	
	remove_filter('get_the_excerpt', 'wp_trim_excerpt');
	add_filter('get_the_excerpt', 'dd_exclude_js_trim_excerpt');
	
}

//clone from wordpress 3.0.1 wp_trim_excerpt, add extra js filter
function dd_exclude_js_trim_excerpt($text) {
	
	// Only generate excerpt if it does not exist
	if($text==''){
		
		$text = get_the_content('');
		$text = strip_shortcodes( $text );
	
		$text = apply_filters('the_content', $text);
	
		//exclude js script , in order to display button in the_excerpt() mode
		$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
		
		//exclude css , in order to display ajax float button in the_excerpt() mode
		$text = preg_replace('@<style[^>]*?>.*?</style>@si', '', $text);
		
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text);
		$excerpt_length = apply_filters('excerpt_length', 55);
		$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
		$words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
		if ( count($words) > $excerpt_length ) {
			array_pop($words);
			$text = implode(' ', $words);
			$text = $text . $excerpt_more;
		} else {
			$text = implode(' ', $words);
		}
	
	}
	return $text;
}

/*************** Digg Digg Admin menu ***************/
add_action('admin_init', 'dd_admin_init');
add_action('admin_menu', 'dd_admin_menu');

/**
 * Menu Handling
 */
function dd_admin_menu() {
	
	$page = add_menu_page('Digg Digg', 'Digg Digg', 'manage_options', 'dd_button_setup');
	
	add_submenu_page('dd_button_setup', 'Digg Digg --> Global Configuration', 'Global Config', 'manage_options', 'dd_button_setup', 'dd_button_global_setup');
	add_submenu_page('dd_button_setup', 'Digg Digg --> Normal Button Configuration ', 'Normal Display', 'manage_options', 'dd_button_normal_setup', 'dd_button_normal_setup');
	add_submenu_page('dd_button_setup', 'Digg Digg --> Floating Button Configuration', 'Floating Display', 'manage_options', 'dd_button_floating_setup', 'dd_button_floating_setup');
	add_submenu_page('dd_button_setup', 'Digg Digg --> Toolbar Configuration', 'Toolbar Display', 'manage_options', 'dd_button_toolbar_setup', 'dd_button_toolbar_setup');
	add_submenu_page('dd_button_setup', 'Digg Digg --> Manual Placement', 'Manual Placement', 'manage_options', 'dd_button_manual_setup', 'dd_button_manual_setup');
	add_submenu_page('dd_button_setup', 'Digg Digg --> FAQ', 'FAQ', 'manage_options', 'dd_button_faq_setup', 'dd_button_faq_setup');

	/* Load css style for all digg digg menu page */
    add_action('admin_print_styles', 'dd_admin_styles');
}

//admin page
function dd_admin_init() {
	wp_register_style('dd_admin_style', DD_PLUGIN_URL . '../css/diggdigg-style-admin.css');
}

function dd_admin_styles(){
	wp_enqueue_style('dd_admin_style');
}
/*************** Digg Digg Admin menu ***************/


//not implement yet
//make sure wordpress > 2.3 and PHP >= 5
function dd_check_version(){
	
	global $wp_version;
	
	echo '<h1>Current PHP version: ' . phpversion() . '</h1>';
	
	echo '<h1>Current Wordpress version: ' . $wp_version . '</h1>';
	
	$exit_msg="<div id='errmessage' class='error fade'><p>Digg Digg requires WordPress 2.3 or newer. <a href='http://codex.wordpress.org/Upgrading_WordPress'>Please update!</a></p></div>";
	
	if (version_compare($wp_version,"2.3",">"))
	{
		exit ($exit_msg);
	}
	
}
?>