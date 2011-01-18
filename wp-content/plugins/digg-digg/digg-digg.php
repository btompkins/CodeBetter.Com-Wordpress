<?php
/*
Plugin Name: Digg Digg
Version: 4.5.0.6
Plugin URI: http://www.mkyong.com/blog/digg-digg-wordpress-plugin
Author: Yong Mook Kim
Author URI: http://www.mkyong.com/
Description: All-in-One social voted buttons [<a href="admin.php?page=dd_button_normal_setup">Digg Digg Configuration</a>]
*/ 
/*
ini_set('display_errors',1);
error_reporting(E_ALL);
*/

require_once 'include/dd-global-variable.php';
require_once 'include/dd-printform.php';
require_once 'include/dd-helper.php';
require_once 'include/dd-manual.php';

//plugin initial during activation, reset all value
register_activation_hook( __FILE__, 'dd_initialIt');

//hook the content
add_filter('the_excerpt', 'dd_content_hook');
add_filter('the_content', 'dd_content_hook');

//add digg digg css file
add_action('wp_head', 'dd_print_css');

function dd_print_css()
{
	echo '<link rel="stylesheet" href="' .DD_PLUGIN_URL . '../css/diggdigg-style.css?ver=' . DD_VERSION . '" type="text/css" media="screen" />';
}

//enable the jQuery library in Wordpress
add_action('init', 'dd_enable_jquery');


function dd_enable_jquery() {
	//not load for admin page
	if (!is_admin()) {
		wp_enqueue_script('jquery');
	}
}

function dd_content_hook($content = ''){

	//if this page is excluded to display, just return
	if(dd_isThisPageExcluded($content)==true){
		return $content;
	}
	
    global $wp_query;
    $post = $wp_query->post; //get post content
    
    $id = $post->ID; //get post id
    $commentcount = $post->comment_count; //get post comments
    $postlink = get_permalink($id); //get post link
    $title = trim($post->post_title); // get post title
    
    //Get post URL
    $link = split(DD_DASH,$postlink); //split the link with '#', for comment link
 	$url = $link[0];
 
 	$dd_global_config = get_option(DD_GLOBAL_CONFIG);
 	
    //get normal display settings
    $ddNormalDisplay = get_option(DD_NORMAL_DISPLAY_CONFIG);
    
    //if normal display is enabled
    if($ddNormalDisplay[DD_STATUS_OPTION][DD_STATUS_OPTION_DISPLAY]==DD_DISPLAY_ON){
    	
	    //if display is allow
	    if(dd_IsDisplayAllow($ddNormalDisplay)){
	    
	    	$dd_display_buttons = get_option(DD_NORMAL_BUTTON);
		
			$dd_sorting_data = array();

			//construct URL
			foreach(array_keys($dd_display_buttons[DD_NORMAL_BUTTON_FINAL]) as $key){
				
				$obj = $dd_display_buttons[DD_NORMAL_BUTTON_FINAL][$key];
				$obj->constructURL($url,$title,$obj->getOptionButtonDesign(),$id, $obj->getOptionLazyLoad(), $dd_global_config,$commentcount);
			
				$dd_sorting_data[$obj->getOptionButtonWeight().'-'.$obj->name] = $obj;
			}
			
			krsort($dd_sorting_data,SORT_NUMERIC);
			
			//get line up options - horizontal and vertical	 
		    $dd_line_up = $ddNormalDisplay[DD_LINE_UP_OPTION][DD_LINE_UP_OPTION_SELECT];
			
		    $content = generateDiggDiggDiv($content,$dd_sorting_data, $dd_line_up);
	    
	    }
	    
    }
    
    //get all floating display settings
    $ddFloatDisplay = get_option(DD_FLOAT_DISPLAY_CONFIG);
    
    //if floating display is enabled
    if($ddFloatDisplay[DD_STATUS_OPTION][DD_STATUS_OPTION_DISPLAY]==DD_DISPLAY_ON){
    	
    	//if display is allow
	    if(dd_IsDisplayAllow($ddFloatDisplay)){
	    
	    	$dd_display_buttons = get_option(DD_FLOAT_BUTTON);
		
			$dd_sorting_data = array();

			//echo '<h1>$wp_query->current_post : ' . $wp_query->current_post . ' $wp_query->post_count : ' . $wp_query->post_count . '</h1>';
			//home, cat and looping post will caused post_count > 1
			if($wp_query->post_count > 1){
				//make sure the floating script only run once in home, cat or looping post
				if($wp_query->current_post==0){
					
					$dd_title = '';
					
					if(is_home()){
						$dd_title = get_bloginfo('description');
					}else{
						$dd_title = single_cat_title("",false);
					}
					 
					foreach(array_keys($dd_display_buttons[DD_FLOAT_BUTTON_FINAL]) as $key){
						
						$obj = $dd_display_buttons[DD_FLOAT_BUTTON_FINAL][$key];
						
						//get current page URL, not post URL
						//get default button design
						$obj->constructURL(getCurPageURL(),$dd_title,$obj->float_button_design,$id, $obj->getOptionLazyLoad(), $dd_global_config,$commentcount);
					
						$dd_sorting_data[$obj->getOptionButtonWeight().'-'.$obj->name] = $obj;
					}
				}
				
			}else{
				//post page usually has post_count = 1
				foreach(array_keys($dd_display_buttons[DD_FLOAT_BUTTON_FINAL]) as $key){
					
					$obj = $dd_display_buttons[DD_FLOAT_BUTTON_FINAL][$key];
					$obj->constructURL($url,$title,$obj->float_button_design,$id, $obj->getOptionLazyLoad(), $dd_global_config,$commentcount);
				
					$dd_sorting_data[$obj->getOptionButtonWeight().'-'.$obj->name] = $obj;
				}
			}
			
			//TODO : Is this really require a sorting here?
			krsort($dd_sorting_data,SORT_NUMERIC);
	
		    $content = generateAjaxFloatDiv($content,$dd_sorting_data, $ddFloatDisplay);

	    }
	    
    }
    return $content;
}

function generateDiggDiggDiv($content,$dd_all_data, $dd_line_up){
	
	$dd_before_content = DD_EMPTY_VALUE;
	$dd_after_content = DD_EMPTY_VALUE; 
	$dd_left_float = DD_EMPTY_VALUE; 
	$dd_right_float = DD_EMPTY_VALUE;
	
	//for lazy load
	$dd_jQuery_script =DD_EMPTY_VALUE;
	$dd_scheduler_script =DD_EMPTY_VALUE;
	
	foreach($dd_all_data as $obj){
		
		$finalURL=DD_EMPTY_VALUE;
		$name=$obj->name;
		
		if($obj->getOptionLazyLoad()==DD_EMPTY_VALUE){
			$finalURL=$obj->finalURL;
		}else{
			$finalURL=$obj->finalURL_lazy;
			$dd_jQuery_script.=$obj->finalURL_lazy_script;
			$dd_scheduler_script.=$obj->final_scheduler_lazy_script;
		}
		
		if($obj->getOptionAppendType() == DD_SELECT_LEFT_FLOAT){
			$dd_left_float .= generateDiv($finalURL,$dd_line_up,$name);
		}else if($obj->getOptionAppendType() == DD_SELECT_RIGHT_FLOAT){
			$dd_right_float .= generateDiv($finalURL,$dd_line_up,$name);
		}else if($obj->getOptionAppendType() == DD_SELECT_BEFORE_CONTENT){
			$dd_before_content .= generateDiv($finalURL,$dd_line_up,$name);
		}else if($obj->getOptionAppendType() == DD_SELECT_AFTER_CONTENT){
			$dd_after_content .= generateDiv($finalURL,$dd_line_up,$name);
		}
		
	}
	
	//combine all scripts and html tags for display
	if($dd_before_content!=DD_EMPTY_VALUE){
		$dd_before_content = "<div class='dd_post_share'><div class='dd_buttons'>" . $dd_before_content. "</div><div style='clear:both'></div></div>";	
	}
		
	if($dd_after_content!=DD_EMPTY_VALUE){
		$dd_after_content = "<div class='dd_post_share'><div class='dd_buttons'>" . $dd_after_content. "</div><div style='clear:both'></div></div>";	
	}
	
	if($dd_left_float!=DD_EMPTY_VALUE){
		$dd_left_float = "<div class='dd_post_share dd_post_share_left'><div class='dd_buttons'>" . $dd_left_float. "</div></div>";	
	}
		
	if($dd_right_float!=DD_EMPTY_VALUE){
		$dd_right_float = "<div class='dd_post_share dd_post_share_right'><div class='dd_buttons'>" . $dd_right_float. "</div></div>";	
	}	
	
	if($dd_jQuery_script!=DD_EMPTY_VALUE){
		$dd_jQuery_script = "<script type=\"text/javascript\">" . $dd_jQuery_script . "</script>";
	}

	$scheduler = DD_EMPTY_VALUE;
	if($dd_scheduler_script!=DD_EMPTY_VALUE){
		$scheduler = "<script type=\"text/javascript\">jQuery(document).ready(function($) { " . $dd_scheduler_script . " });</script>";
	}

	$content = $dd_left_float . $dd_right_float . $dd_before_content . $content . $dd_after_content . $scheduler . $dd_jQuery_script . DD_AUTHOR_SITE;
	
	return $content;
}

function generateDiv($link, $dd_line_up,$name){
	
	if($dd_line_up==DD_LINE_UP_OPTION_SELECT_HORIZONTAL){
		return "<div class='dd_button'>" .$link. "</div>" ;
	}else if($dd_line_up==DD_LINE_UP_OPTION_SELECT_VERTICAL){
		
		//TODO:for facebook only?
		if ($name=="Facebook") {
			return "<div class='dd_button_v'>" .$link. "</div><div style='clear:left'></div>" ;		
		}
		else{
			return "<div class='dd_button_v'>" .$link. "</div>" ;
		}
		
	}else{
		return "<div class='dd_button'>" .$link. "</div>" ;
	}
	
}

function generateAjaxFloatDiv($content,$dd_all_data,$ddFloatDisplay){
	
	$result=DD_EMPTY_VALUE;
	
	$dd_jQuery_script =DD_EMPTY_VALUE;
	$dd_scheduler_script =DD_EMPTY_VALUE;
	
	foreach($dd_all_data as $obj){
	
		$finalURL=DD_EMPTY_VALUE;

		if($obj->getOptionLazyLoad()==DD_EMPTY_VALUE){
			$finalURL=$obj->finalURL;
		}else{
			$finalURL=$obj->finalURL_lazy;
			$dd_jQuery_script.=$obj->finalURL_lazy_script;
			$dd_scheduler_script.=$obj->final_scheduler_lazy_script;
		}
		
		$result .= "<div class='dd_button_v'>" . $finalURL . "</div><div style='clear:left'></div>" ;	

	}
	
	if($result != DD_EMPTY_VALUE){
		
		//add extra service?
		if($ddFloatDisplay[DD_EXTRA_OPTION_ADDTHIS][DD_EXTRA_OPTION_ADDTHIS_STATUS]==DD_DISPLAY_ON){
			
			$result .= "<div class='dd_button_v'>" . $ddFloatDisplay[DD_EXTRA_OPTION_ADDTHIS][DD_EXTRA_OPTION_ADDTHIS_JS] . "</div><div style='clear:left'></div>" ;	
			
		}
		
		//output javascript
		$floatingJS = '<style type="text/css" media="screen">' . $ddFloatDisplay[DD_FLOAT_OPTION][DD_FLOAT_OPTION_INITIAL_POSITION] . '</style>';
		$floatingJS .= '<script type="text/javascript">' . $ddFloatDisplay[DD_FLOAT_OPTION][DD_FLOAT_OPTION_SCROLLING_POSITION] . '</script>';

		//disable the digg digg credit link?
		if($ddFloatDisplay[DD_FLOAT_OPTION][DD_FLOAT_OPTION_CREDIT]==DD_DISPLAY_ON){
			$result = $floatingJS . "<div id='dd_ajax_float'>" . $result . "</div>";
		}else{
			$result = $floatingJS. "<div id='dd_ajax_float'>" . $result . "<div id='dd_name'><a href='http://www.mkyong.com/blog/digg-digg-wordpress-plugin/' target='_blank'>Digg Digg</a></div></div>";
		}
		
		
		//check browser's width
		$hidewidth = $ddFloatDisplay[DD_EXTRA_OPTION][DD_EXTRA_OPTION_SCREEN_WIDTH];
		
		//if no width is found, set it to zero
		if(trim($hidewidth)==''){
			$hidewidth = 0;
		}
		
		$hideButtonJs = "<script type=\"text/javascript\">jQuery(document).ready(function($) { 
		
			if($(window).width()> " . $hidewidth . "){ 
				$('#dd_ajax_float').show()
			}else{
				$('#dd_ajax_float').hide()
			}
	
			$(window).resize(function() { 
				
				if($(window).width()> " . $hidewidth . "){ 
					$('#dd_ajax_float').show()
				}else{
					$('#dd_ajax_float').hide()
				}
				
			});  

		});</script>";
		
		//Lazy loading
		if($dd_jQuery_script!=DD_EMPTY_VALUE){
			$dd_jQuery_script = "<script type=\"text/javascript\">" . $dd_jQuery_script . "</script>";
		}
	
		$scheduler = DD_EMPTY_VALUE;
		if($dd_scheduler_script!=DD_EMPTY_VALUE){
			$scheduler = "<script type=\"text/javascript\">jQuery(document).ready(function($) { " . $dd_scheduler_script . " });</script>";
		}

		//when scrollbar beyond this y value, the ajax scroll effect started.
		$content = $hidejsscript . $scheduler . $dd_jQuery_script . $result . $hideButtonJs . "<div class='dd_content_wrap'>" . $content . "</div>";
		
	}
	
	return $content;
}
?>