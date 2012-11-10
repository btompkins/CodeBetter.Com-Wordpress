<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
*   @desc Set default options
*/

$shrsb_plugopts = shrsb_sb_set_options();

/*
 * @desc Set the settings either from database or default
 */
function shrsb_sb_set_options($action = NULL){
   
    /*
    *   @desc Most Popular Services List
    *   @note To change the most popular list also change the "Most Popular" link click handler in shareaholic-admin.js
    */
    $shrsb_most_popular = array (
        'shr-facebook',
        'shr-twitter',
        'shr-linkedin',
        'shr-googlebookmarks',
        'shr-delicious',
        'shr-stumbleupon',
        'shr-reddit',
        'shr-gmail',
        'shr-mail',
        'shr-printfriendly'
    );
    $defaultLikeButtonOrder = array(
        'shr-fb-like',
        'shr-fb-send',
        'shr-plus-one',
        'shr-tw-button'
    );
    
    $shrsb_sb_plugopts_default = array(
			'position' => 'below', // below, above, or manual
			'reloption' => 'nofollow', // 'nofollow', or ''
			'targetopt' => '_blank', // 'blank' or 'self'
			'perfoption' => '1', // performance script (GA)
			'showShareCount' => '1', // fb/twit share count

            'likeButtonSetTop' => '0', // Include like button below the Post Title
            'fbLikeButtonTop' => '0', // Include fb like button
            'fbSendButtonTop' => '0', // Include fb like button
            'googlePlusOneButtonTop' => '0', // Include Google Plus One button
            'tweetButtonTop' => '0', // Include Tweet button
            'likeButtonSetSizeTop' => "1", // Size of like buttons
            'likeButtonSetCountTop' => "true", // Show count with +1 button
            'likeButtonOrderTop' => $defaultLikeButtonOrder,
            'likeButtonSetAlignmentTop' => '0', // Alignment 0 => left, 1 => right

            'likeButtonSetBottom' => '1', // Include like button below the Post
            'fbLikeButtonBottom' => '0', // Include fb like button
            'fbSendButtonBottom' => '0', // Include fb like button
            'googlePlusOneButtonBottom' => '0', // Include Google Plus One button
            'tweetButtonBottom' => '0', // Include Tweet button
            'likeButtonSetSizeBottom' => "1", // Size of like buttons
            'likeButtonSetCountBottom' => "true", // Show count with +1 button
            'likeButtonOrderBottom' => $defaultLikeButtonOrder,
            'likeButtonSetAlignmentBottom' => '0', // Alignment 0 => left, 1 => right

            'fbNameSpace' => '1',  // Add fb name space to the html
            'preventminify' => '1',  // prevent wp_minify from minifying the js
            'shrlink' => '1', // show promo link
			'bgimg-yes' => 'yes', // 'yes' or blank
			'mobile-hide' => '', // 'yes' or blank
			'bgimg' => 'caring', // default bg image
			'shorty' => 'google', // default shortener
			'pageorpost' => 'postpageindexcategory',
			'bookmark' => $shrsb_most_popular ,//array_keys($shrsb_bookmarks_data),
			'feed' => '0', // 1 or 0
			'expand' => '1',
			'autocenter' => '1',
			'tweetconfig' => urlencode('${title} - ${short_link} via @Shareaholic'), // Custom configuration of tweet
		    'warn-choice' => '',
			'doNotIncludeJQuery' => '',
			'custom-mods' => '',
			'scriptInFooter' => '',
            'shareaholic-javascript' => '1',
            'shrbase' => 'http://www.shareaholic.com',
            'apikey' => '8afa39428933be41f8afdb8ea21a495c',
            'service' => '',
            'designer_toolTips' => '1',
            'tip_bg_color' => '#000000',  // tooltip background color
            'tip_text_color' => '#ffffff', // tooltip text color
            'spritegen_path' => SHRSB_UPLOADDIR_DEFAULT,
            'ogtags' => '1',  //OgTags
            'promo' => '1'
        );
    
        //Return default settings 
        if($action == "reset"){
            delete_option("SexyBookmarks");
            add_option("SexyBookmarks",$shrsb_sb_plugopts_default);
            return $shrsb_sb_plugopts_default;
        }

        //Get the settings from the database
        $database_Settings =  get_option('SexyBookmarks');
        
        
        if($database_Settings){//got the settings in the database
            
            $need_to_update = false;
            
            //Check whether all the settings are present or not
            foreach($shrsb_sb_plugopts_default as $k => $v){
                if( !array_key_exists( $k, $database_Settings)) {
                    $database_Settings[$k] = $v;
                    $need_to_update = true;
                }
            }
            //Check for the tweetbutton in likebutton set
            if(!in_array("shr-tw-button", $database_Settings["likeButtonOrderTop"]) ) array_push($database_Settings["likeButtonOrderTop"],"shr-tw-button");
            if(!in_array("shr-tw-button", $database_Settings["likeButtonOrderBottom"]) ) array_push($database_Settings["likeButtonOrderBottom"],"shr-tw-button");
            
            if($need_to_update) update_option("SexyBookmarks",$database_Settings);
            
            return $database_Settings;
            
        }else{
            //Add the settings
            add_option('SexyBookmarks',$shrsb_sb_plugopts_default);
            return $shrsb_sb_plugopts_default;
        }
}



//add to database
//$shrsb_plugopts['tweetconfig'] = urlencode($shrsb_plugopts['tweetconfig']);

add_option('SHRSB_apikey', $shrsb_plugopts['apikey']);
add_option('SHRSB_CustomSprite', '');
add_option('SHRSB_DefaultSprite',true);



//Remove the propeller Service
if(isset ($shrsb_plugopts) && isset($shrsb_plugopts['service'])){
   $services = explode(',', $shrsb_plugopts['service']);

   if(!empty($services)){
       foreach ($services as $k => $v){
           if($v == '77'){
               unset ($services[$k]);
           }
       }
       $shrsb_plugopts['service'] = implode(',', $services );
   }
}


/*
*   @note Make sure spritegen_path is defined
*/

//if(!isset($shrsb_plugopts['spritegen_path'])) {
//    $shrsb_plugopts['spritegen_path'] = SHRSB_UPLOADDIR_DEFAULT;
//}else{
//    if($shrsb_plugopts['spritegen_path'] == 'SHRSB_UPLOADDIR_DEFAULT')
//       $shrsb_plugopts['spritegen_path'] = SHRSB_UPLOADDIR_DEFAULT;
//}

//Check for POST
if(isset($_POST['save_changes_sb']) ){
    //Define the default path for Spritegen Directory
    if(isset($_POST['spritegen_path']) && $_POST['spritegen_path'] !=  SHRSB_UPLOADDIR_DEFAULT){
        //Create the Directory
        $p = shrb_addTrailingChar(stripslashes($_POST['spritegen_path']),"/");

        define('SHRSB_UPLOADDIR', $p);
        define('SHRSB_UPLOADPATH', shr_dir_to_path($p));
    }else{
        define('SHRSB_UPLOADDIR', SHRSB_UPLOADDIR_DEFAULT);
        define('SHRSB_UPLOADPATH', SHRSB_UPLOADPATH_DEFAULT);
    }
}else{
    if( isset($_POST['reset_all_options_sb'])|| (isset($shrsb_plugopts['spritegen_path']) && $shrsb_plugopts['spritegen_path'] == SHRSB_UPLOADDIR_DEFAULT) ){
        // For Reseting the data Or First Time Install
        define('SHRSB_UPLOADDIR', SHRSB_UPLOADDIR_DEFAULT);
        define('SHRSB_UPLOADPATH', SHRSB_UPLOADPATH_DEFAULT);
    }else{
        $p = shrb_addTrailingChar(stripslashes($shrsb_plugopts['spritegen_path']),"/");
        define('SHRSB_UPLOADDIR', $p);
        define('SHRSB_UPLOADPATH', shr_dir_to_path($p));
    }
}


// Some new default options may not be present in the database.
// Add them if they aren't.
//if(!isset($shrsb_plugopts['designer_toolTips'])) {
//    $shrsb_plugopts['designer_toolTips'] = '1';
//    $shrsb_plugopts['tip_bg_color'] = '#000000';  // tooltip background color
//    $shrsb_plugopts['tip_text_color'] = '#ffffff'; // tooltip text color
//}

//if(!isset($shrsb_plugopts['likeButtonSetTop'])) {
//      $shrsb_plugopts['likeButtonSetTop'] = '0'; // Include fb like button
//      $shrsb_plugopts['fbLikeButtonTop'] = '0'; // if fb like button is included. Include in bottom right by default
//      $shrsb_plugopts['fbSendButtonTop' ] =  '0'; // Include fb like button
//      $shrsb_plugopts['googlePlusOneButtonTop' ] =  '0'; // Include Google Plus One button
//      $shrsb_plugopts['likeButtonSetSizeTop' ] =  "1"; // Size of like buttons
//      $shrsb_plugopts['likeButtonSetCountTop'] =  "true"; // Show count with +1 button
//      $shrsb_plugopts['likeButtonOrderTop' ] =  $defaultLikeButtonOrder;
//      $shrsb_plugopts['likeButtonSetAlignmentTop' ] =  '0'; // Alignment 0 => left, 1 => right
//      $shrsb_plugopts['likeButtonSetBottom'] = '1'; // Include fb like button
//      $shrsb_plugopts['fbLikeButtonBottom'] = '0'; // if fb like button is included. Include in bottom right by default
//      $shrsb_plugopts['fbSendButtonBottom' ] =  '0'; // Include fb like button
//      $shrsb_plugopts['googlePlusOneButtonBottom' ] =  '0'; // Include Google Plus One button
//      $shrsb_plugopts['likeButtonSetSizeBottom' ] =  "1"; // Size of like buttons
//      $shrsb_plugopts['likeButtonSetCountBottom'] =  "true"; // Show count with +1 button
//      $shrsb_plugopts['likeButtonOrderBottom' ] =  $defaultLikeButtonOrder;
//      $shrsb_plugopts['likeButtonSetAlignmentBottom' ] =  '0'; // Alignment 0 => left, 1 => right
//}

//if(!isset($shrsb_plugopts['ogtags'])) {
//    $shrsb_plugopts['ogtags'] = "1";
//}

//if(!isset($shrsb_plugopts['fbNameSpace'])) {
//    $shrsb_plugopts['fbNameSpace'] = "1";
//}

//if(!isset($shrsb_plugopts['preventminify'])) {
//    $shrsb_plugopts['preventminify'] = "1";
//}

//if($shrsb_plugopts['fbNameSpace'] == '1') {
//    add_filter('language_attributes', 'shrsb_addFBNameSpace');
//}

//$shrsb_plugopts['tweetconfig'] = urldecode($shrsb_plugopts['tweetconfig']);

$shrsb_plugopts['apikey'] = get_option('SHRSB_apikey');
$shrsb_custom_sprite = get_option('SHRSB_CustomSprite');


// code to remove redundant data fields from the database
//if(isset($shrsb_plugopts['twittcat'])) {
//    $shrsb_plugopts['ybuzzcat'] = '';
//    $shrsb_plugopts['ybuzzmed'] = '';
//    $shrsb_plugopts['twittcat'] = '';
//    $shrsb_plugopts['defaulttags'] = '';
//}

// Some databases got corrupted. This will set things in place.
if($shrsb_plugopts['shrbase'] != 'http://www.shareaholic.com'){
    $shrsb_plugopts['shrbase'] = 'http://www.shareaholic.com';
}

// Reset depreciated url shorteners
//if($shrsb_plugopts['shorty'] == 'slly' || $shrsb_plugopts['shorty'] == 'cligs' || $shrsb_plugopts['shorty'] == 'snip' || $shrsb_plugopts['shorty'] == 'tinyarrow' || $shrsb_plugopts['shorty'] == 'b2l' || $shrsb_plugopts['shorty'] == 'trim' || $shrsb_plugopts['shorty'] == 'e7t')  {
//
//    $shrsb_plugopts['shortyapi']['snip']['user'] = '';
//    $shrsb_plugopts['shortyapi']['snip']['key'] = '';
//    $shrsb_plugopts['shortyapi']['trim']['chk'] = '';
//    $shrsb_plugopts['shortyapi']['trim']['user'] = '';
//    $shrsb_plugopts['shortyapi']['trim']['pass'] = '';
//    $shrsb_plugopts['shortyapi']['tinyarrow']['chk'] = '';
//    $shrsb_plugopts['shortyapi']['tinyarrow']['user'] = '';
//    $shrsb_plugopts['shortyapi']['cligs']['chk'] = '';
//    $shrsb_plugopts['shortyapi']['cligs']['key'] = '';
//}

/*
*   @desc Re-name values
*/

//if($shrsb_plugopts['shorty'] == 'tiny') {
//    $shrsb_plugopts['shorty'] = 'tinyurl';
//}
//
//if($shrsb_plugopts['shorty'] == 'googl') {
//    $shrsb_plugopts['shorty'] = 'google';
//}

/*
*   @desc Fix short URLs corrupt value
*/

//if(isset($shrsb_plugopts['shortyapi'])){
//
//    if(strpos($shrsb_plugopts['shortyapi']['bitly']['user'],"sexybookmarks/sexy-bookmarks.php")) {
//        $shrsb_plugopts['shortyapi']['bitly']['user'] = "";
//    }
//    if(strpos($shrsb_plugopts['shortyapi']['bitly']['key'],"sexybookmarks/sexy-bookmarks.php")) {
//        $shrsb_plugopts['shortyapi']['bitly']['key'] = "";
//    }
//    if(strpos($shrsb_plugopts['shortyapi']['jmp']['user'],"sexybookmarks/sexy-bookmarks.php")) {
//        $shrsb_plugopts['shortyapi']['jmp']['user'] = "";
//    }
//    if(strpos($shrsb_plugopts['shortyapi']['jmp']['key'],"sexybookmarks/sexy-bookmarks.php") ) {
//        $shrsb_plugopts['shortyapi']['jmp']['key'] = "";
//    }
//    if(strpos($shrsb_plugopts['shortyapi']['supr']['chk'],"sexybookmarks/sexy-bookmarks.php") ) {
//        $shrsb_plugopts['shortyapi']['supr']['chk'] = "";
//    }
//    if(strpos($shrsb_plugopts['shortyapi']['supr']['user'],"sexybookmarks/sexy-bookmarks.php") ) {
//        $shrsb_plugopts['shortyapi']['supr']['user'] = "";
//    }
//    if(strpos($shrsb_plugopts['shortyapi']['supr']['key'],"sexybookmarks/sexy-bookmarks.php") ) {
//        $shrsb_plugopts['shortyapi']['supr']['key'] = "";
//    }
//
//}/* Short URLs End */

?>
