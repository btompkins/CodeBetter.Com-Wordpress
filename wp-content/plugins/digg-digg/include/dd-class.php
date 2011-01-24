<?php
class BaseDD {
	
	//String for replacement.
	const VOTE_URL = 'VOTE_URL'; 
	const VOTE_TITLE = 'VOTE_TITLE';
	const VOTE_BUTTON_DESIGN = 'VOTE_BUTTON_DESIGN';
	const SCHEDULER_TIMER = 'SCHEDULER_TIMER';
	const POST_ID = 'POST_ID';
	
	const VOTE_BUTTON_DESIGN_LAZY_WIDTH = 'VOTE_BUTTON_DESIGN_LAZY_WIDTH';
	const VOTE_BUTTON_DESIGN_LAZY_HEIGHT = 'VOTE_BUTTON_DESIGN_LAZY_HEIGHT';
	
	//default value
	const DEFAULT_APPEND_TYPE = 'none';
	const DEFAULT_OPTION_AJAX = 'false';
	const DEFAULT_BUTTON_DESIGN = 'Normal';
	const DEFAULT_BUTTON_WEIGHT = '100';

    // define properties
    var $name; //name of the vote button
    var $websiteURL; //website URL
    var $apiURL; //Button API for development
    
    var $baseURL; // vote button URL, before construt
    var $baseURL_lazy; // vote button URL, before construt, lazy version
    var $baseURL_lazy_script; // jQuery script , lazy version
    var $scheduler_lazy_script; //scheduler function
    var $scheduler_lazy_timer; //miliseconds
	
    var $finalURL; //final URL for display, after constructs
    var $finalURL_lazy;//final lazy URL for display, after constructs
    var $finalURL_lazy_script;//final jQuery, after constructs
    var $final_scheduler_lazy_script; //final scheduler, after constructs
    
    var $isEncodeRequired = true; //is URL or title need encode?
    
    var $islazyLoadAvailable = true; //is lazy load avaliable?
    
    //contains DD option value, in array
    var $wp_options; 
    var $option_append_type;
	var $option_button_design;
	var $option_button_weight;
	var $option_ajax_left_float;
	var $option_lazy_load;
	 
	var $button_weight_value;
	
	//default float button design
	var $float_button_design = self::DEFAULT_BUTTON_DESIGN;
	
	//default button layout, suit in most cases
	var $buttonLayout = array(
		"Normal" => "Normal",
		"Compact" => "Compact"
	);
	
	public function getButtonDesign($button){
    	return $this->buttonLayout[$button];
    }
    
    //default lazy button layout, suit in most cases
    var $buttonLayoutLazy = array(
		"Normal" => "Normal",
		"Compact" => "Compact"
	);
	
    public function getButtonDesignLazy($button){
    	return $this->buttonLayoutLazy[$button];
    }
    
    var $buttonLayoutLazyWidth = array(
		"Normal" => "51",
		"Compact" => "120"
	);
	
	public function getButtonDesignLazyWidth($button){
    	return $this->buttonLayoutLazyWidth[$button];
    }
    
    var $buttonLayoutLazyHeight = array(
		"Normal" => "69",
		"Compact" => "22"
	);
	
	public function getButtonDesignLazyHeight($button){
    	return $this->buttonLayoutLazyHeight[$button];
    }
    
    //constructor
    function BaseDD($name, $websiteURL, $apiURL, $baseURL) {
    	$this->name = $name;
    	$this->websiteURL = $websiteURL;
    	$this->apiURL = $apiURL;
    	$this->baseURL = $baseURL;
    	
    	$this->initWPOptions();
    }
    
    //Construct the wordpress options value, save into wp_options
    private function initWPOptions() {
    	$this->wp_options = array();
    	$this->wp_options[$this->option_append_type] = self::DEFAULT_APPEND_TYPE;
    	$this->wp_options[$this->option_button_design] = self::DEFAULT_BUTTON_DESIGN;
    	$this->wp_options[$this->option_ajax_left_float] = self::DEFAULT_OPTION_AJAX;
    	$this->wp_options[$this->option_lazy_load] = self::DEFAULT_OPTION_AJAX;
    	$this->wp_options[$this->option_button_weight] = $this->button_weight_value;
    }
    
    public function getOptionLazyLoad(){
    	return $this->wp_options[$this->option_lazy_load];
    }
    public function getOptionAjaxLeftFloat(){
    	return $this->wp_options[$this->option_ajax_left_float];
    }
    public function getOptionButtonWeight(){
    	return $this->wp_options[$this->option_button_weight];
    }
    public function getOptionAppendType(){
    	return $this->wp_options[$this->option_append_type];
    }
    public function getOptionButtonDesign(){
    	return $this->wp_options[$this->option_button_design];
    }
    
	//construct base URL, based on $lazy value
 	public function constructURL($url, $title,$button, $postId, $lazy, $globalcfg = '', $commentcount = ''){
    	
 		//Encode require?
 		//rawurlencode - replace space with %20
    	//urlencode - replace space with + 
 		if($this->isEncodeRequired){
 			$title = rawurlencode($title);
    		$url = rawurlencode($url);
 		}
 		
    	if($lazy==DD_EMPTY_VALUE || $lazy==false){
    		$this->constructNormalURL($url, $title,$button, $postId);
    	}else{
    		$this->constructLazyLoadURL($url, $title,$button, $postId);
    	}
    	
    }
    
    //normal url
	public function constructNormalURL($url, $title,$button, $postId){
		
    	$finalURL = $this->baseURL;
    	$finalURL = str_replace(self::VOTE_BUTTON_DESIGN,$this->getButtonDesign($button),$finalURL);
    	$finalURL = str_replace(self::VOTE_TITLE,$title,$finalURL);
    	$finalURL = str_replace(self::VOTE_URL,$url,$finalURL);
    	$this->finalURL = $finalURL;
    }
    
    //lazy load url
    public function constructLazyLoadURL($url, $title,$button, $postId){
    	
    	$finalURL_lazy = $this->baseURL_lazy;
    	
    	$finalURL_lazy = str_replace(self::VOTE_BUTTON_DESIGN,$this->getButtonDesignLazy($button),$finalURL_lazy);
    	$finalURL_lazy = str_replace(self::VOTE_TITLE,$title,$finalURL_lazy);
    	$finalURL_lazy = str_replace(self::VOTE_URL,$url,$finalURL_lazy);
    	$finalURL_lazy = str_replace(self::POST_ID,$postId,$finalURL_lazy);
    	
    	$this->finalURL_lazy = $finalURL_lazy;
    	
    	//lazy loading javascript
    	$finalURL_lazy_script = $this->baseURL_lazy_script;
    	
    	$finalURL_lazy_script = str_replace(self::VOTE_BUTTON_DESIGN_LAZY_WIDTH,$this->getButtonDesignLazyWidth($button),$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(self::VOTE_BUTTON_DESIGN_LAZY_HEIGHT,$this->getButtonDesignLazyHeight($button),$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(self::VOTE_BUTTON_DESIGN,$this->getButtonDesignLazy($button),$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(self::VOTE_TITLE,$title,$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(self::VOTE_URL,$url,$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(self::POST_ID,$postId,$finalURL_lazy_script);
    	$this->finalURL_lazy_script = $finalURL_lazy_script;
    	
    	//scheduler to run the lazy loading
    	$final_scheduler_lazy_script = $this->scheduler_lazy_script;
    	$final_scheduler_lazy_script = str_replace(self::SCHEDULER_TIMER,$this->scheduler_lazy_timer,$final_scheduler_lazy_script);
    	$final_scheduler_lazy_script = str_replace(self::POST_ID,$postId,$final_scheduler_lazy_script);
    	$this->final_scheduler_lazy_script =  $final_scheduler_lazy_script;
    	
    }
}

//base class for iframe baseURL
class BaseIFrameDD extends BaseDD{
	const EXTRA_VALUE = "EXTRA_VALUE";
	
	var $buttonLayoutWidthHeight = array(
		"Normal" => "height=\"69\" width=\"51\"",
		"Compact" => "height=\"22\" width=\"120\"",
	);
	
	public function getIframeWH($button){
    	return $this->buttonLayoutWidthHeight[$button];
    }
    
	public function constructNormalURL($url, $title,$button, $postId){
		
    	$finalURL = $this->baseURL;
    	$finalURL = str_replace(parent::VOTE_BUTTON_DESIGN,$this->getButtonDesign($button),$finalURL);
    	$finalURL = str_replace(parent::VOTE_TITLE,$title,$finalURL);
    	$finalURL = str_replace(parent::VOTE_URL,$url,$finalURL);
    	$finalURL = str_replace(parent::POST_ID,$postId,$finalURL);
    	
    	$finalURL = str_replace(self::EXTRA_VALUE,$this->getIframeWH($button),$finalURL);
    	$this->finalURL = $finalURL;
    }
}
	
//warning : in baseURL or lazyURL, all text have to enclose by html tag, else it will display pure text in the_excerpt mode

//digg.com
class DD_Digg extends BaseDD{
	
	const NAME = "Digg";
	const URL_WEBSITE = "http://www.digg.com";
	const URL_API = "http://about.digg.com/downloads/button/smart";
	
	//const BASEURL = "<script src='http://widgets.digg.com/buttons.js' type='text/javascript'></script><a class='DiggThisButton VOTE_BUTTON_DESIGN' href='http://digg.com/submit?url=VOTE_URL&amp;title=VOTE_TITLE'></a>";
	//new smart digg able to load asynchronously and not block other downloads
	const BASEURL = "<script type='text/javascript'>(function() {var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];s.type = 'text/javascript';s.async = true;s.src = 'http://widgets.digg.com/buttons.js';s1.parentNode.insertBefore(s, s1);})();</script> <a class='DiggThisButton VOTE_BUTTON_DESIGN' href='http://digg.com/submit?url=VOTE_URL&amp;title=VOTE_TITLE'></a>";
	
	const BASEURL_LAZY = "<div class='dd-digg-ajax-load dd-digg-POST_ID'></div><a class='DiggThisButton DD_DIGG_AJAX_POST_ID VOTE_BUTTON_DESIGN'></a>";
	const BASEURL_LAZY_SCRIPT = " function loadDigg_POST_ID(){ jQuery(document).ready(function(\$) { \$('.dd-digg-POST_ID').remove();\$('.DD_DIGG_AJAX_POST_ID').attr('href','http://digg.com/submit?url=VOTE_URL&amp;title=VOTE_TITLE');\$.getScript('http://widgets.digg.com/buttons.js'); }); }";
	const SCHEDULER_LAZY_SCRIPT = "window.setTimeout('loadDigg_POST_ID()',SCHEDULER_TIMER);";
	const SCHEDULER_LAZY_TIMER = "1000";
    
	const OPTION_APPEND_TYPE = "dd_digg_appendType";
	const OPTION_BUTTON_DESIGN = "dd_digg_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_digg_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_digg_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_digg_lazy_load";
	
	const DEFAULT_BUTTON_WEIGHT = "100";
	
	var $buttonLayout = array(
		"Normal" => "DiggMedium",
		"Compact" => "DiggCompact",
		"Icon" => "DiggIcon" 
	);
    
	var $buttonLayoutLazy = array(
		"Normal" => "DiggMedium",
		"Compact" => "DiggCompact",
		"Icon" => "DiggIcon" 
	);
	
    public function DD_Digg() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->baseURL_lazy = self::BASEURL_LAZY;
    	$this->baseURL_lazy_script = self::BASEURL_LAZY_SCRIPT;
    	$this->scheduler_lazy_script = self::SCHEDULER_LAZY_SCRIPT;
    	$this->scheduler_lazy_timer = self::SCHEDULER_LAZY_TIMER;
    	
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
        parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
      
    } 
}

//lazying floating is not display in firefox only?
//linkedin.com
class DD_Linkedin extends BaseDD{
	
	const NAME = "Linkedin";
	const URL_WEBSITE = "http://www.linkedin.com";
	const URL_API = "http://www.linkedin.com/publishers";
	
	const BASEURL = "<script type='text/javascript' src='http://platform.linkedin.com/in.js'></script><script type='in/share' data-url='VOTE_URL' data-counter='VOTE_BUTTON_DESIGN'></script>";
	
	const BASEURL_LAZY = "<div class='dd-linkedin-ajax-load dd-linkedin-POST_ID'></div><script type='in/share' data-url='VOTE_URL' data-counter='VOTE_BUTTON_DESIGN'></script>";
	const BASEURL_LAZY_SCRIPT = " function loadLinkedin_POST_ID(){ jQuery(document).ready(function(\$) { \$('.dd-linkedin-POST_ID').remove();\$.getScript('http://platform.linkedin.com/in.js'); }); }";
	const SCHEDULER_LAZY_SCRIPT = "window.setTimeout('loadLinkedin_POST_ID()',SCHEDULER_TIMER);";
	const SCHEDULER_LAZY_TIMER = "1000";
    
	const OPTION_APPEND_TYPE = "dd_linkedin_appendType";
	const OPTION_BUTTON_DESIGN = "dd_linkedin_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_linkedin_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_linkedin_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_linkedin_lazy_load";
	
	const DEFAULT_BUTTON_WEIGHT = "99";
	
	var $buttonLayout = array(
		"Normal" => "top",
		"Compact" => "right",
		"NoCount" => "none" 
	);
    
	var $buttonLayoutLazy = array(
		"Normal" => "top",
		"Compact" => "right",
		"NoCount" => "none" 
	);
	
	//url or title encode is not require
	var $isEncodeRequired = false;
	
    public function DD_Linkedin() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->baseURL_lazy = self::BASEURL_LAZY;
    	$this->baseURL_lazy_script = self::BASEURL_LAZY_SCRIPT;
    	$this->scheduler_lazy_script = self::SCHEDULER_LAZY_SCRIPT;
    	$this->scheduler_lazy_timer = self::SCHEDULER_LAZY_TIMER;
    	
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
        parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
      
    } 
}

//reddit.com
class DD_Reddit extends BaseIFrameDD{
	
	const NAME = "Reddit";
	const URL_WEBSITE = "http://www.reddit.com";
	const URL_API = "http://www.reddit.com/buttons/";
	
	//const BASEURL = "<script type='text/javascript'>reddit_url = VOTE_URL;reddit_title = VOTE_TITLE;reddit_newwindow='1';</script><script type='text/javascript' src='http://www.reddit.com/static/button/VOTE_BUTTON_DESIGN'></script>";
	const BASEURL = "<iframe src=\"http://www.reddit.com/static/button/VOTE_BUTTON_DESIGN&url=VOTE_URL&title=VOTE_TITLE&newwindow='1'\" EXTRA_VALUE scrolling='no' frameborder='0'></iframe>";
	
	const BASEURL_LAZY = "<div class='dd-reddit-ajax-load dd-reddit-POST_ID'></div><iframe class='DD_REDDIT_AJAX_POST_ID' src='#' height='0' width='0' scrolling='no' frameborder='0'></iframe>";
	const BASEURL_LAZY_SCRIPT = " function loadReddit_POST_ID(){ jQuery(document).ready(function(\$) { \$('.dd-reddit-POST_ID').remove();\$('.DD_REDDIT_AJAX_POST_ID').attr('width','VOTE_BUTTON_DESIGN_LAZY_WIDTH');\$('.DD_REDDIT_AJAX_POST_ID').attr('height','VOTE_BUTTON_DESIGN_LAZY_HEIGHT');\$('.DD_REDDIT_AJAX_POST_ID').attr('src','http://www.reddit.com/static/button/VOTE_BUTTON_DESIGN&amp;url=VOTE_URL&amp;title=VOTE_TITLE&amp;newwindow=1'); }); }";
	const SCHEDULER_LAZY_SCRIPT = "window.setTimeout('loadReddit_POST_ID()',SCHEDULER_TIMER);";
	const SCHEDULER_LAZY_TIMER = "1000";
	
	const OPTION_APPEND_TYPE = "dd_reddit_appendType";
	const OPTION_BUTTON_DESIGN = "dd_reddit_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_reddit_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_reddit_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_reddit_lazy_load";
	
	var $buttonLayout = array(
		"Normal" => "button2.html?width=51", 
		"Compact" => "button1.html?width=120", 
		"Icon" => "button3.html?width=69"
	);
	
	var $buttonLayoutLazy = array(
		"Normal" => "button2.html?width=51", 
		"Compact" => "button1.html?width=120", 
		"Icon" => "button3.html?width=69"
	);
	
	var $buttonLayoutLazyWidth = array(
		"Normal" => "51",
		"Compact" => "120",
		"Icon" => "69"
	);
    
    var $buttonLayoutLazyHeight = array(
		"Normal" => "69",
		"Compact" => "22",
    	"Icon" => "52"
	);
	
	var $buttonLayoutWidthHeight = array(
		"Normal" => "height=\"69\" width=\"51\"",
		"Compact" => "height=\"22\" width=\"120\"",
		"Icon" => "height=\"52\" width=\"69\""
	);
	
	const DEFAULT_BUTTON_WEIGHT = "99";
	
	public function DD_Reddit() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
			
		$this->baseURL_lazy = self::BASEURL_LAZY;
    	$this->baseURL_lazy_script = self::BASEURL_LAZY_SCRIPT;
    	$this->scheduler_lazy_script = self::SCHEDULER_LAZY_SCRIPT;
    	$this->scheduler_lazy_timer = self::SCHEDULER_LAZY_TIMER;
    	
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
         parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
    }
    
}

//Google Buzz
class DD_GBuzz extends BaseDD{
	
	const NAME = "Google Buzz";
	const URL_WEBSITE = "http://www.google.com";
	const URL_API = "http://www.google.com/buzz/api/admin/configPostWidget";
	const URL_API2 = "http://code.google.com/apis/buzz/buttons_and_gadgets.html";
	const BASEURL = "<a title='Post on Google Buzz' class='google-buzz-button' href='http://www.google.com/buzz/post' data-button-style='VOTE_BUTTON_DESIGN' data-url='VOTE_URL'></a><script type='text/javascript' src='http://www.google.com/buzz/api/button.js'></script>";

	const OPTION_APPEND_TYPE = "dd_gbuzz_appendType";
	const OPTION_BUTTON_DESIGN = "dd_gbuzz_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_gbuzz_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_gbuzz_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_gbuzz_lazy_load";
	
	//DD_GBUZZ_AJAX_POST_ID
	const BASEURL_LAZY = "<div class='dd-gbuzz-ajax-load dd-gbuzz-POST_ID'></div><a title='Post on Google Buzz' class='google-buzz-button' href='http://www.google.com/buzz/post' data-button-style='VOTE_BUTTON_DESIGN' data-url='VOTE_URL'></a>";
	const BASEURL_LAZY_SCRIPT = " function loadGBuzz_POST_ID(){ jQuery(document).ready(function(\$) { \$('.dd-gbuzz-POST_ID').remove();\$.getScript('http://www.google.com/buzz/api/button.js'); }); }";
	const SCHEDULER_LAZY_SCRIPT = "window.setTimeout('loadGBuzz_POST_ID()',SCHEDULER_TIMER);";
	const SCHEDULER_LAZY_TIMER = "1000";
	
	const DEFAULT_BUTTON_WEIGHT = "98";
	
	var $buttonLayout = array(
		"Normal" => "normal-count", 
		"Compact" => "small-count"
	);
	
	var $buttonLayoutLazy = array(
		"Normal" => "normal-count", 
		"Compact" => "small-count"
	);

	//url or title dun need to encode
	var $isEncodeRequired = false;
	
    public function DD_GBuzz() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->baseURL_lazy = self::BASEURL_LAZY;
    	$this->baseURL_lazy_script = self::BASEURL_LAZY_SCRIPT;
    	$this->scheduler_lazy_script = self::SCHEDULER_LAZY_SCRIPT;
    	$this->scheduler_lazy_timer = self::SCHEDULER_LAZY_TIMER;
    	
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
         parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
        
    }     
   
}

//dzone.com
class DD_DZone extends BaseDD{
	
	const NAME = "DZone";
	const URL_WEBSITE = "http://www.dzone.com";
	const URL_API = "http://www.dzone.com/links/buttons.jsp";
	const BASEURL = "<iframe src='http://widgets.dzone.com/links/widgets/zoneit.html?url=VOTE_URL&amp;title=VOTE_TITLE&amp;t=VOTE_BUTTON_DESIGN frameborder='0' scrolling='no'></iframe>";
	
	const BASEURL_LAZY = "<div class='dd-dzone-ajax-load dd-dzone-POST_ID'></div><iframe class='DD_DZONE_AJAX_POST_ID' src='#' height='0' width='0' scrolling='no' frameborder='0'></iframe>";
	const BASEURL_LAZY_SCRIPT = " function loadDzone_POST_ID(){ jQuery(document).ready(function(\$) { \$('.dd-dzone-POST_ID').remove();\$('.DD_DZONE_AJAX_POST_ID').attr('width','VOTE_BUTTON_DESIGN_LAZY_WIDTH');\$('.DD_DZONE_AJAX_POST_ID').attr('height','VOTE_BUTTON_DESIGN_LAZY_HEIGHT');\$('.DD_DZONE_AJAX_POST_ID').attr('src','http://widgets.dzone.com/links/widgets/zoneit.html?url=VOTE_URL&title=VOTE_TITLE&t=VOTE_BUTTON_DESIGN'); }); }";
	const SCHEDULER_LAZY_SCRIPT = "window.setTimeout('loadDzone_POST_ID()',SCHEDULER_TIMER);";
	const SCHEDULER_LAZY_TIMER = "1000";
	
	const OPTION_APPEND_TYPE = "dd_dzone_appendType";
	const OPTION_BUTTON_DESIGN = "dd_dzone_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_dzone_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_dzone_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_dzone_lazy_load";
	
	const DEFAULT_BUTTON_WEIGHT = "97";

	var $buttonLayout = array(
		"Normal" => "1' height='70' width='50'", 
		"Compact" => "2' height='25' width='155'"
	);
	
	var $buttonLayoutLazy = array(
		"Normal" => "1", 
		"Compact" => "2"
	);
	
	var $buttonLayoutLazyWidth = array(
		"Normal" => "50",
		"Compact" => "155"
	);
    
    var $buttonLayoutLazyHeight = array(
		"Normal" => "70",
		"Compact" => "25"
	);
	
    public function DD_DZone() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->baseURL_lazy = self::BASEURL_LAZY;
    	$this->baseURL_lazy_script = self::BASEURL_LAZY_SCRIPT;
    	$this->scheduler_lazy_script = self::SCHEDULER_LAZY_SCRIPT;
    	$this->scheduler_lazy_timer = self::SCHEDULER_LAZY_TIMER;
    	
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
        parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
    } 
    
}

//facebook.com
class DD_FbShare extends BaseDD{
	
	const NAME = "Facebook Share";
	const URL_WEBSITE = "http://www.facebook.com";
	const URL_API = "http://www.facebook.com/share/";
	const BASEURL = "<a name='fb_share' type='VOTE_BUTTON_DESIGN' share_url='VOTE_URL' href='http://www.facebook.com/sharer.php'></a><script src='http://static.ak.fbcdn.net/connect.php/js/FB.Share' type='text/javascript'></script>";
	
	const OPTION_APPEND_TYPE = "dd_fbshare_appendType";
	const OPTION_BUTTON_DESIGN = "dd_fbshare_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_fbshare_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_fbshare_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_fbshare_lazy_load";
	
	const BASEURL_LAZY = "<div class='dd-fbshare-ajax-load dd-fbshare-POST_ID'></div><a class='DD_FBSHARE_AJAX_POST_ID' name='fb_share' type='VOTE_BUTTON_DESIGN' share_url='VOTE_URL' href='http://www.facebook.com/sharer.php'></a>";
	const BASEURL_LAZY_SCRIPT = " function loadFBShare_POST_ID(){ jQuery(document).ready(function(\$) { \$('.dd-fbshare-POST_ID').remove(); \$.getScript('http://static.ak.fbcdn.net/connect.php/js/FB.Share'); }); }";
	const SCHEDULER_LAZY_SCRIPT = "window.setTimeout('loadFBShare_POST_ID()',SCHEDULER_TIMER);";
	const SCHEDULER_LAZY_TIMER = "1000";
	
	const DEFAULT_BUTTON_WEIGHT = "95";
	
	var $buttonLayout = array(
		"Normal" => "box_count",
		"Compact" => "button_count"
	);
    
	var $buttonLayoutLazy = array(
		"Normal" => "box_count",
		"Compact" => "button_count"
	);
	
	//url or title dun need to encode
	var $isEncodeRequired = false;
	
    public function DD_FbShare() {
    	
    	//wordpress option value
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->baseURL_lazy = self::BASEURL_LAZY;
    	$this->baseURL_lazy_script = self::BASEURL_LAZY_SCRIPT;
    	$this->scheduler_lazy_script = self::SCHEDULER_LAZY_SCRIPT;
    	$this->scheduler_lazy_timer = self::SCHEDULER_LAZY_TIMER;
    	
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
         parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
      
    } 
}

//fbshare.me
class DD_FbShareMe extends BaseDD{
	
	const NAME = "fbShare.me";
	const URL_WEBSITE = "http://www.fbshare.me";
	const URL_API = "http://www.fbshare.me";
	const BASEURL = "<script>var fbShare = {url: 'VOTE_URL',size: 'VOTE_BUTTON_DESIGN',}</script><script src='http://widgets.fbshare.me/files/fbshare.js'></script>";

	const OPTION_APPEND_TYPE = "dd_fbshareme_appendType";
	const OPTION_BUTTON_DESIGN = "dd_fbshareme_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_fbshareme_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_fbshareme_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_fbshareme_lazy_load";

	const BASEURL_LAZY = "<div class='dd-fbshareme-ajax-load dd-fbshareme-POST_ID'></div><iframe class=\"DD_FBSHAREME_AJAX_POST_ID\" src='#' height='0' width='0' scrolling='no' frameborder='0' allowtransparency='true'></iframe>";
	const BASEURL_LAZY_SCRIPT = " function loadFBShareMe_POST_ID(){ jQuery(document).ready(function(\$) { \$('.dd-fbshareme-POST_ID').remove();\$('.DD_FBSHAREME_AJAX_POST_ID').attr('width','VOTE_BUTTON_DESIGN_LAZY_WIDTH');\$('.DD_FBSHAREME_AJAX_POST_ID').attr('height','VOTE_BUTTON_DESIGN_LAZY_HEIGHT');\$('.DD_FBSHAREME_AJAX_POST_ID').attr('src','http://widgets.fbshare.me/files/fbshare.php?url=VOTE_URL&size=VOTE_BUTTON_DESIGN');  }); }";
	const SCHEDULER_LAZY_SCRIPT = "window.setTimeout('loadFBShareMe_POST_ID()',SCHEDULER_TIMER);";
	const SCHEDULER_LAZY_TIMER = "1000";
	
	var $buttonLayout = array(
		"Normal" => "large",
		"Compact" => "small"
	);
    
	var $buttonLayoutLazy = array(
		"Normal" => "large",
		"Compact" => "small"
	);
	
	var $buttonLayoutLazyWidth = array(
		"Normal" => "53",
		"Compact" => "80"
	);
    
    var $buttonLayoutLazyHeight = array(
		"Normal" => "69",
		"Compact" => "18"
	);
	
	const DEFAULT_BUTTON_WEIGHT = "94";
	
	//url or title dun need to encode
	var $isEncodeRequired = false;
	
    public function DD_FbShareMe() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->baseURL_lazy = self::BASEURL_LAZY;
    	$this->baseURL_lazy_script = self::BASEURL_LAZY_SCRIPT;
    	$this->scheduler_lazy_script = self::SCHEDULER_LAZY_SCRIPT;
    	$this->scheduler_lazy_timer = self::SCHEDULER_LAZY_TIMER;

		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
         parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
      
    } 
}

//facebook.com like fucntion
class DD_FbLike extends BaseIFrameDD{
	
	const NAME = "Facebook Like";
	const URL_WEBSITE = "http://www.facebook.com";
	const URL_API = "http://developers.facebook.com/docs/reference/plugins/like";
	const BASEURL = "<iframe src='http://www.facebook.com/plugins/like.php?href=VOTE_URL&amp;locale=FACEBOOK_LOCALE&amp;VOTE_BUTTON_DESIGN' scrolling='no' frameborder='0' style='border:none; overflow:hidden; EXTRA_VALUE' allowTransparency='true'></iframe>";
	const FB_LOCALES = "http://www.facebook.com/translations/FacebookLocales.xml";
	
	const OPTION_APPEND_TYPE = "dd_fblike_appendType";
	const OPTION_BUTTON_DESIGN = "dd_fblike_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_fblike_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_fblike_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_fblike_lazy_load";
	
	const BASEURL_LAZY = "<div class='dd-fblike-ajax-load dd-fblike-POST_ID'></div><iframe class=\"DD_FBLIKE_AJAX_POST_ID\" src='#' height='0' width='0' scrolling='no' frameborder='0' allowTransparency='true'></iframe>";
	const BASEURL_LAZY_SCRIPT = " function loadFBLike_POST_ID(){ jQuery(document).ready(function(\$) { \$('.dd-fblike-POST_ID').remove();\$('.DD_FBLIKE_AJAX_POST_ID').attr('width','VOTE_BUTTON_DESIGN_LAZY_WIDTH');\$('.DD_FBLIKE_AJAX_POST_ID').attr('height','VOTE_BUTTON_DESIGN_LAZY_HEIGHT');\$('.DD_FBLIKE_AJAX_POST_ID').attr('src','http://www.facebook.com/plugins/like.php?href=VOTE_URL&amp;locale=FACEBOOK_LOCALE&amp;VOTE_BUTTON_DESIGN'); }); }";
	const SCHEDULER_LAZY_SCRIPT = "window.setTimeout('loadFBLike_POST_ID()',SCHEDULER_TIMER);";
	const SCHEDULER_LAZY_TIMER = "1000";
	
	const LIKE_STANDARD = "layout=standard&amp;action=like&amp;width=350&amp;height=23&amp;colorscheme=light"; //350x23
	const LIKE_BUTTON_COUNT= "layout=button_count&amp;action=like&amp;width=92&amp;height=20&amp;colorscheme=light"; //92x20
	const LIKE_BOX_COUNT= "layout=box_count&amp;action=like&amp;width=50&amp;height=60&amp;colorscheme=light"; //50x60
	const RECOMMEND_STANDARD = "layout=standard&amp;action=recommend&amp;width=400&amp;height=23&amp;colorscheme=light"; //400x23
	const RECOMMEND_BUTTON_COUNT= "layout=button_count&amp;action=recommend&amp;width=130&amp;height=20&amp;colorscheme=light"; //130x20
	const RECOMMEND_BOX_COUNT= "layout=box_count&amp;action=recommend&amp;width=90&amp;height=60&amp;colorscheme=light"; //90x60

	const EXTRA_VALUE = "EXTRA_VALUE";
	const FACEBOOK_LOCALE = "FACEBOOK_LOCALE";
	
	const DEFAULT_BUTTON_WEIGHT = "94";
	
	var $float_button_design = "Like Box Count";
	
	var $buttonLayout = array(
		"Like Standard" => self::LIKE_STANDARD,
		"Like Button Count" => self::LIKE_BUTTON_COUNT,
		"Like Box Count" => self::LIKE_BOX_COUNT,
		"Recommend Standard" => self::RECOMMEND_STANDARD,
		"Recommend Button Count" => self::RECOMMEND_BUTTON_COUNT,
		"Recommend Box Count" => self::RECOMMEND_BOX_COUNT
	);
    
	var $buttonLayoutLazy = array(
		"Like Standard" => self::LIKE_STANDARD,
		"Like Button Count" => self::LIKE_BUTTON_COUNT,
		"Like Box Count" => self::LIKE_BOX_COUNT,
		"Recommend Standard" => self::RECOMMEND_STANDARD,
		"Recommend Button Count" => self::RECOMMEND_BUTTON_COUNT,
		"Recommend Box Count" => self::RECOMMEND_BOX_COUNT
	);
	
	//TODO : need testing to adjust the width
	var $buttonLayoutWidthHeight = array(
		"Like Standard" => "width:500px; height:23px;",
		"Like Button Count" => "width:92px; height:20px;",
		"Like Box Count" => "width:50px; height:60px;",
		"Recommend Standard" => "width:500px; height:23px;",
		"Recommend Button Count" => "width:130px; height:20px;",
		"Recommend Box Count" => "width:90px; height:60px;"
	);
	
	var $buttonLayoutLazyWidth = array(
		"Like Standard" => "500",
		"Like Button Count" => "92",
		"Like Box Count" => "50",
		"Recommend Standard" => "500",
		"Recommend Button Count" => "130",
		"Recommend Box Count" => "90"
	);
    
    var $buttonLayoutLazyHeight = array(
		"Like Standard" => "23",
		"Like Button Count" => "20",
		"Like Box Count" => "60",
		"Recommend Standard" => "23",
		"Recommend Button Count" => "20",
		"Recommend Box Count" => "60"
	);
	
    public function DD_FbLike() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->baseURL_lazy = self::BASEURL_LAZY;
    	$this->baseURL_lazy_script = self::BASEURL_LAZY_SCRIPT;
    	$this->scheduler_lazy_script = self::SCHEDULER_LAZY_SCRIPT;
    	$this->scheduler_lazy_timer = self::SCHEDULER_LAZY_TIMER;
    	
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
         parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
        
    } 
    
	//lazy load url
    public function constructLazyLoadURL($url, $title,$button, $postId){
    	
    	$finalURL_lazy = $this->baseURL_lazy;
    	
    	$finalURL_lazy = str_replace(parent::VOTE_BUTTON_DESIGN,$this->getButtonDesignLazy($button),$finalURL_lazy);
    	$finalURL_lazy = str_replace(parent::VOTE_TITLE,$title,$finalURL_lazy);
    	$finalURL_lazy = str_replace(parent::VOTE_URL,$url,$finalURL_lazy);
    	$finalURL_lazy = str_replace(parent::POST_ID,$postId,$finalURL_lazy);	
    	$this->finalURL_lazy = $finalURL_lazy;
    	
    	//lazy loading javascript
    	$finalURL_lazy_script = $this->baseURL_lazy_script;
    	
    	$finalURL_lazy_script = str_replace(parent::VOTE_BUTTON_DESIGN_LAZY_WIDTH,$this->getButtonDesignLazyWidth($button),$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(parent::VOTE_BUTTON_DESIGN_LAZY_HEIGHT,$this->getButtonDesignLazyHeight($button),$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(parent::VOTE_BUTTON_DESIGN,$this->getButtonDesignLazy($button),$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(parent::VOTE_TITLE,$title,$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(parent::VOTE_URL,$url,$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(parent::POST_ID,$postId,$finalURL_lazy_script);
    	
    	//add new line
    	//convert &amp; to &
    	$finalURL_lazy_script = str_replace("&amp;","&",$finalURL_lazy_script);
    	
    	$this->finalURL_lazy_script = $finalURL_lazy_script;
    	
    	//scheduler to run the lazy loading
    	$final_scheduler_lazy_script = $this->scheduler_lazy_script;
    	$final_scheduler_lazy_script = str_replace(parent::SCHEDULER_TIMER,$this->scheduler_lazy_timer,$final_scheduler_lazy_script);
    	$final_scheduler_lazy_script = str_replace(parent::POST_ID,$postId,$final_scheduler_lazy_script);
    	$this->final_scheduler_lazy_script =  $final_scheduler_lazy_script;
    	
    }
    
	public function constructURL($url, $title,$button, $postId, $lazy, $globalcfg = '', $commentcount = ''){
    	
 		if($this->isEncodeRequired){
 			$title = rawurlencode($title);
    		$url = rawurlencode($url);
 		}
 		
 		$facebook_locale = '';
 		if($globalcfg!=''){
 			$facebook_locale = $globalcfg[DD_GLOBAL_FACEBOOK_OPTION][DD_GLOBAL_FACEBOOK_OPTION_LOCALE]; 
 		}
	
    	if($lazy==DD_EMPTY_VALUE){

    		$this->baseURL = str_replace(self::FACEBOOK_LOCALE,$facebook_locale,$this->baseURL);
    		$this->constructNormalURL($url, $title,$button, $postId);
    		
    	}else{

    		$this->baseURL_lazy_script = str_replace(self::FACEBOOK_LOCALE,$facebook_locale,$this->baseURL_lazy_script);
    		$this->constructLazyLoadURL($url, $title,$button, $postId);
    	}
    	
    }
    
}

//delicious.com
class DD_Delicious extends BaseDD{
	
	const NAME = "Delicious";
	const URL_WEBSITE = "http://www.delicious.com";
	const URL_API = "http://www.delicious.com/help/feeds";
	
	const BASEURL = "<a class='dd-delicious' style='background:url(VOTE_BUTTON_DESIGN)no-repeat;' href='http://delicious.com/save' onclick=\"window.open('http://delicious.com/save?v=5&amp;noui&amp;jump=close&amp;url='+encodeURIComponent('VOTE_URL')+'&amp;title='+encodeURIComponent('VOTE_TITLE'),'delicious', 'toolbar=no,width=550,height=550'); return false;\"><span id='DD_DELICIOUS_AJAX_POST_ID'>SAVED_COUNT</span></a>";
	
	const OPTION_APPEND_TYPE = "dd_delicious_appendType";
	const OPTION_BUTTON_DESIGN = "dd_delicious_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_delicious_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_delicious_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_delicious_lazy_load";

	const BASEURL_LAZY = "<a class='dd-delicious' style='background:url(VOTE_BUTTON_DESIGN)no-repeat;' href='http://delicious.com/save' onclick=\"window.open('http://delicious.com/save?v=5&amp;noui&amp;jump=close&amp;url='+encodeURIComponent('VOTE_URL')+'&amp;title='+encodeURIComponent('VOTE_TITLE'),'delicious', 'toolbar=no,width=550,height=550'); return false;\"><span id='DD_DELICIOUS_AJAX_POST_ID'>SAVED_COUNT</span></a>";
	const BASEURL_LAZY_SCRIPT = " function loadDelicious_POST_ID(){ jQuery(document).ready(function(\$) { \$('.dd-delicious-POST_ID').remove();\$.getJSON('http://feeds.delicious.com/v2/json/urlinfo/data?url=VOTE_URL&amp;callback=?',function(data) {var msg ='';var count = 0;if (data.length > 0) {count = data[0].total_posts;if(count ==0){msg = '0';}else if(count ==1){msg = '1';}else{msg = count}}else{msg = '0';}\$('#DD_DELICIOUS_AJAX_POST_ID').text(msg);}); }); }";
	const SCHEDULER_LAZY_SCRIPT = "window.setTimeout('loadDelicious_POST_ID()',SCHEDULER_TIMER);";
	const SCHEDULER_LAZY_TIMER = "1000";
		
	const DEFAULT_BUTTON_WEIGHT = "93";
	
	const SAVED_COUNT = "SAVED_COUNT";

	//url or title encode is not require
	var $isEncodeRequired = false;
	
	var $buttonLayout = array(
		"Normal" => DD_PLUGIN_STYLE_DELICIOUS,
		"Compact" => DD_PLUGIN_STYLE_DELICIOUS_COMPACT
	);
	
	var $buttonLayoutLazy = array(
		"Normal" => DD_PLUGIN_STYLE_DELICIOUS,
		"Compact" => DD_PLUGIN_STYLE_DELICIOUS_COMPACT
	);
	
    public function DD_Delicious() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->baseURL_lazy = self::BASEURL_LAZY;
    	$this->baseURL_lazy_script = self::BASEURL_LAZY_SCRIPT;
    	$this->scheduler_lazy_script = self::SCHEDULER_LAZY_SCRIPT;
    	$this->scheduler_lazy_timer = self::SCHEDULER_LAZY_TIMER;
    	
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
         parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
    }  

	//implement own normal constructor
	public function constructNormalURL($url, $title,$button, $postId){
		
		$count = '';
    	$shareUrl = urlencode($url);
		$deliciousStats = json_decode(file_get_contents('http://feeds.delicious.com/v2/json/urlinfo/data?url='.$shareUrl));
		
		if(!empty($deliciousStats)){
			if($deliciousStats[0]->total_posts == 0) {
			    $count = '0';
			} elseif($deliciousStats[0]->total_posts == 1) {
			    $count = '1';
			} else {
			    $count = $deliciousStats[0]->total_posts;
			}
		}else{
			$count = '0';
		}
		
		$finalURL = $this->baseURL;
    	$finalURL = str_replace(parent::VOTE_BUTTON_DESIGN,$this->getButtonDesign($button),$finalURL);
    	$finalURL = str_replace(parent::VOTE_TITLE,$title,$finalURL);
    	$finalURL = str_replace(parent::VOTE_URL,$url,$finalURL);
    	$finalURL = str_replace(self::SAVED_COUNT,$count,$finalURL);
    	
    	$this->finalURL = $finalURL;
    }
   
	//lazy load url
    public function constructLazyLoadURL($url, $title,$button, $postId){
    	
    	$finalURL_lazy = $this->baseURL_lazy;
    	
    	$finalURL_lazy = str_replace(parent::VOTE_BUTTON_DESIGN,$this->getButtonDesignLazy($button),$finalURL_lazy);
    	$finalURL_lazy = str_replace(parent::VOTE_TITLE,$title,$finalURL_lazy);
    	$finalURL_lazy = str_replace(parent::VOTE_URL,$url,$finalURL_lazy);
    	$finalURL_lazy = str_replace(parent::POST_ID,$postId,$finalURL_lazy);
    	
    	//add new line
    	$finalURL_lazy = str_replace(self::SAVED_COUNT,'',$finalURL_lazy);

    	$this->finalURL_lazy = $finalURL_lazy;
    	
    	//lazy loading javascript
    	$finalURL_lazy_script = $this->baseURL_lazy_script;
    	
    	$finalURL_lazy_script = str_replace(parent::VOTE_BUTTON_DESIGN_LAZY_WIDTH,$this->getButtonDesignLazyWidth($button),$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(parent::VOTE_BUTTON_DESIGN_LAZY_HEIGHT,$this->getButtonDesignLazyHeight($button),$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(parent::VOTE_BUTTON_DESIGN,$this->getButtonDesignLazy($button),$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(parent::VOTE_TITLE,$title,$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(parent::VOTE_URL,$url,$finalURL_lazy_script);
    	$finalURL_lazy_script = str_replace(parent::POST_ID,$postId,$finalURL_lazy_script);
    	
    	$this->finalURL_lazy_script = $finalURL_lazy_script;
    	
    	//scheduler to run the lazy loading
    	$final_scheduler_lazy_script = $this->scheduler_lazy_script;
    	$final_scheduler_lazy_script = str_replace(parent::SCHEDULER_TIMER,$this->scheduler_lazy_timer,$final_scheduler_lazy_script);
    	$final_scheduler_lazy_script = str_replace(parent::POST_ID,$postId,$final_scheduler_lazy_script);
    	$this->final_scheduler_lazy_script =  $final_scheduler_lazy_script;
    }
}

//stumbleupon
class DD_StumbleUpon extends BaseDD{
	
	const NAME = "Stumbleupon";
	const URL_WEBSITE = "http://www.stumbleupon.com";
	const URL_API = "http://www.stumbleupon.com/badges/";
	const BASEURL = "<script src='http://www.stumbleupon.com/hostedbadge.php?s=VOTE_BUTTON_DESIGN&amp;r=VOTE_URL'></script>";
	
	const OPTION_APPEND_TYPE = "dd_stumbleupon_appendType";
	const OPTION_BUTTON_DESIGN = "dd_stumbleupon_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_stumbleupon_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_stumbleupon_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_stumbleupon_lazy_load";

	const DEFAULT_BUTTON_WEIGHT = "91";
	
	var $islazyLoadAvailable = false;
	
	var $buttonLayout = array(
		"Normal" => "5",
		"Compact" => "1"
	);
	
    public function DD_StumbleUpon() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
         parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
    }     
}

//buzz.yahoo.com
class DD_YBuzz extends BaseDD{

	const NAME = "Yahoo Buzz";
	const URL_WEBSITE = "http://buzz.yahoo.com";
	const URL_API = "http://buzz.yahoo.com/buttons";
	
	const BASEURL = "<script type='text/javascript'>yahooBuzzArticleHeadline=\"VOTE_TITLE\";yahooBuzzArticleId=\"VOTE_URL\";</script><script type='text/javascript' src='http://d.yimg.com/ds/badge2.js' badgetype='VOTE_BUTTON_DESIGN'></script>";
	
	const OPTION_APPEND_TYPE = "dd_ybuzz_appendType";
	const OPTION_BUTTON_DESIGN = "dd_ybuzz_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_ybuzz_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_ybuzz_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_ybuzz_lazy_load";
	
	const DEFAULT_BUTTON_WEIGHT = "90";
	
	var $islazyLoadAvailable = false;
	
	var $buttonLayout = array(
		"Normal" => "square",
		"Compact" => "small-votes",
		"Compact_Text" => "text-votes"
	);
	
	//url or title dun need to encode
	var $isEncodeRequired = false;
	
	//constructor
    public function DD_YBuzz() {
    	
    	//wordpress option value
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
    	
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
         parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
    } 
    
}

//sphinn.com
class DD_Sphinn extends BaseIFrameDD{
	
	const NAME = "Sphinn";
	const URL_WEBSITE = "http://www.sphinn.com";
	const URL_API = "http://sphinn.com/widgets/";
	
	const BASEURL = "<iframe src='http://sphinn.com/evb/VOTE_BUTTON_DESIGN?url=VOTE_URL' EXTRA_VALUE scrolling='no' frameborder='0' allowtransparency='true'></iframe>";
	
	const OPTION_APPEND_TYPE = "dd_sphinn_appendType";
	const OPTION_BUTTON_DESIGN = "dd_sphinn_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_sphinn_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_sphinn_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_sphinn_lazy_load";
	
	const BASEURL_LAZY = "<div class='dd-sphinn-ajax-load dd-sphinn-POST_ID'></div><iframe class='DD_SPHINN_AJAX_POST_ID' width='0' height='0' scrolling='no' frameborder='0' src='#'></iframe>";
	const BASEURL_LAZY_SCRIPT = " function loadSphinn_POST_ID(){ jQuery(document).ready(function(\$) { \$('.dd-sphinn-POST_ID').remove();\$('.DD_SPHINN_AJAX_POST_ID').attr('width','VOTE_BUTTON_DESIGN_LAZY_WIDTH');\$('.DD_SPHINN_AJAX_POST_ID').attr('height','VOTE_BUTTON_DESIGN_LAZY_HEIGHT');\$('.DD_SPHINN_AJAX_POST_ID').attr('src','http://sphinn.com/evb/VOTE_BUTTON_DESIGN?url=VOTE_URL'); }); }";
	const SCHEDULER_LAZY_SCRIPT = "window.setTimeout('loadSphinn_POST_ID()',SCHEDULER_TIMER);";
	const SCHEDULER_LAZY_TIMER = "1000";

	const DEFAULT_BUTTON_WEIGHT = "92";
	
	var $buttonLayout = array(
		"Normal" => "vurl.php",
		"Compact" => "hurl.php"
	);
    
	var $buttonLayoutWidthHeight = array(
		"Normal" => "height='68' width='50'",
		"Compact" => "height='22' width='110'",
	);
	
	var $buttonLayoutLazy = array(
		"Normal" => "vurl.php",
		"Compact" => "hurl.php"
	);
	
	var $buttonLayoutLazyWidth = array(
		"Normal" => "50",
		"Compact" => "110"
	);
    
    var $buttonLayoutLazyHeight = array(
		"Normal" => "68",
		"Compact" => "22"
	);
	
    public function DD_Sphinn() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->baseURL_lazy = self::BASEURL_LAZY;
    	$this->baseURL_lazy_script = self::BASEURL_LAZY_SCRIPT;
    	$this->scheduler_lazy_script = self::SCHEDULER_LAZY_SCRIPT;
    	$this->scheduler_lazy_timer = self::SCHEDULER_LAZY_TIMER;
    	
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
         parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
        
    } 
    
}

//blogengage.com
class DD_BlogEngage extends BaseDD{

	const NAME = "BlogEngage";
	const URL_WEBSITE = "http://www.blogengage.com";
	const URL_API = "http://www.blogengage.com/profile_promo.php";
	
	const BASEURL = "<script type='text/javascript'>submit_url = 'VOTE_URL';</script><script src='http://blogengage.com/evb/VOTE_BUTTON_DESIGN'></script>";
  
	const OPTION_APPEND_TYPE = "dd_blogengage_appendType";
	const OPTION_BUTTON_DESIGN = "dd_blogengage_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_blogengage_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_blogengage_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_blogengage_lazy_load";
	
	const DEFAULT_BUTTON_WEIGHT = "84";
	
	var $islazyLoadAvailable = false;
	
	//url or title dun need to encode
	var $isEncodeRequired = false;
	
	var $buttonLayout = array(
		"Normal" => "button4.php"
	);
	
    public function DD_BlogEngage() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
         parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
    } 
    
}  

//designbump.com
class DD_DesignBump extends BaseDD{
	
	const NAME = "DesignBump";
	const URL_WEBSITE = "http://www.designbump.com";
	const URL_API = "http://designbump.com/content/evb";

	const BASEURL = "<script type='text/javascript'>url_site='VOTE_URL'; </script> <script src='http://designbump.com/sites/all/modules/drigg_external/js/button.js' type='text/javascript'></script>";
	
	const OPTION_APPEND_TYPE = "dd_designbump_appendType";
	const OPTION_BUTTON_DESIGN = "dd_designbump_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_designbump_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_designbump_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_designbump_lazy_load";
	
	const DEFAULT_BUTTON_WEIGHT = "87";
	
	var $islazyLoadAvailable = false;
	
	//url or title dun need to encode
	var $isEncodeRequired = false;
	
	var $buttonLayout = array(
		"Normal" => "Normal"
	);
	
	//constructor
    public function DD_DesignBump() {
    	
    	//wordpress option value
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
        parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);

    } 
    
}

//thewebblend.com
class DD_TheWebBlend extends BaseDD{

	const NAME = "TheWebBlend";
	const URL_WEBSITE = "http://www.thewebblend.com";
	const URL_API = "http://thewebblend.com/tools/vote";

	const BASEURL = "<script type='text/javascript'>url_site='VOTE_URL'; VOTE_BUTTON_DESIGN</script><script src='http://thewebblend.com/sites/all/modules/drigg_external/js/button.js' type='text/javascript'></script>";
	
	const OPTION_APPEND_TYPE = "dd_webblend_appendType";
	const OPTION_BUTTON_DESIGN = "dd_webblend_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_webblend_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_webblend_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_webblend_lazy_load";
	
	var $islazyLoadAvailable = false;
	
	//url or title dun need to encode
	var $isEncodeRequired = false;
	
	const DEFAULT_BUTTON_WEIGHT = "85";
	
	var $buttonLayout = array(
		"Normal" => "",
		"Compact" => "badge_size='compact';"
	);
	
	//constructor
    public function DD_TheWebBlend() {
    	
    	//wordpress option value
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
        parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
    }    
}

//official twitter button
class DD_Twitter extends BaseDD{
	
	const NAME = "Twitter";
	const URL_WEBSITE = "http://www.twitter.com";
	const URL_API = "http://twitter.com/goodies/tweetbutton";

	const BASEURL ="<a href=\"http://twitter.com/share\" class=\"twitter-share-button\" data-url=\"VOTE_URL\" data-count=\"VOTE_BUTTON_DESIGN\" data-text=\"VOTE_TITLE\" data-via=\"VOTE_SOURCE\" ></a><script type=\"text/javascript\" src=\"http://platform.twitter.com/widgets.js\"></script>";
	
	const OPTION_APPEND_TYPE = "dd_twitter_appendType";
	const OPTION_BUTTON_DESIGN = "dd_twitter_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_twitter_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_twitter_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_twitter_lazy_load";
	
	const BASEURL_LAZY ="<div class='dd-twitter-ajax-load dd-twitter-POST_ID'></div><a href=\"http://twitter.com/share\" class=\"twitter-share-button\" data-url=\"VOTE_URL\" data-count=\"VOTE_BUTTON_DESIGN\" data-text=\"VOTE_TITLE\" data-via=\"VOTE_SOURCE\" ></a>";
	const BASEURL_LAZY_SCRIPT = " function loadTwitter_POST_ID(){ jQuery(document).ready(function(\$) { \$('.dd-twitter-POST_ID').remove();\$.getScript('http://platform.twitter.com/widgets.js'); }); }";
	const SCHEDULER_LAZY_SCRIPT = "window.setTimeout('loadTwitter_POST_ID()',SCHEDULER_TIMER);";
	const SCHEDULER_LAZY_TIMER = "1000";

	const DEFAULT_BUTTON_WEIGHT = "98";
	
	var $buttonLayout = array(
		"Normal" => "vertical",
		"Compact" => "horizontal"
	);
	
	var $buttonLayoutLazy = array(
		"Normal" => "vertical",
		"Compact" => "horizontal"
	);
	
	//url or title dun need to encode
	var $isEncodeRequired = false;
	
	const VOTE_SOURCE = "VOTE_SOURCE";
	
    public function DD_Twitter() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->baseURL_lazy = self::BASEURL_LAZY;
    	$this->baseURL_lazy_script = self::BASEURL_LAZY_SCRIPT;
    	$this->scheduler_lazy_script = self::SCHEDULER_LAZY_SCRIPT;
    	$this->scheduler_lazy_timer = self::SCHEDULER_LAZY_TIMER;
    	
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
        parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
        
    }

 	public function constructURL($url, $title,$button, $postId, $lazy, $globalcfg = '', $commentcount = ''){
    	
 		if($this->isEncodeRequired){
 			$title = rawurlencode($title);
    		$url = rawurlencode($url);
 		}
 		
 		$twitter_source = '';
 		if($globalcfg!=''){
 			$twitter_source = $globalcfg[DD_GLOBAL_TWITTER_OPTION][DD_GLOBAL_TWITTER_OPTION_SOURCE]; 
 		}

    	if($lazy==DD_EMPTY_VALUE){
    		//format twitter source
    		$this->baseURL = str_replace(self::VOTE_SOURCE,$twitter_source,$this->baseURL);
    		
    		$this->constructNormalURL($url, $title,$button, $postId);
    		
    	}else{
    		//format twitter source
    		$this->baseURL_lazy = str_replace(self::VOTE_SOURCE,$twitter_source,$this->baseURL_lazy);
    	
    		$this->constructLazyLoadURL($url, $title,$button, $postId);
    	}
    	
    }

}

//tweetmeme.com
class DD_TweetMeme extends BaseDD{
	
	const NAME = "TweetMeme";
	const URL_WEBSITE = "http://www.tweetmeme.com/";
	const URL_API = "http://wordpress.org/extend/plugins/tweetmeme/";
	
	const BASEURL ="<iframe src='http://api.tweetmeme.com/button.js?url=VOTE_URL&source=VOTE_SOURCE&service=VOTE_SERVICE_NAME&service_api=VOTE_SERVICE_API&style=VOTE_BUTTON_DESIGN frameborder='0' scrolling='no'></iframe>";
	const OPTION_APPEND_TYPE = "dd_tweetmeme_appendType";
	const OPTION_BUTTON_DESIGN = "dd_tweetmeme_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_tweetmeme_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_tweetmeme_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_tweetmeme_lazy_load";
	
	const BASEURL_LAZY = "<div class='dd-tweetmeme-ajax-load dd-tweetmeme-POST_ID'></div><iframe class='DD_TWEETMEME_AJAX_POST_ID' src='#' height='0' width='0' scrolling='no' frameborder='0'></iframe>";
	const BASEURL_LAZY_SCRIPT = " function loadTweetMeme_POST_ID(){ jQuery(document).ready(function(\$) { \$('.dd-tweetmeme-POST_ID').remove();\$('.DD_TWEETMEME_AJAX_POST_ID').attr('width','VOTE_BUTTON_DESIGN_LAZY_WIDTH');\$('.DD_TWEETMEME_AJAX_POST_ID').attr('height','VOTE_BUTTON_DESIGN_LAZY_HEIGHT');\$('.DD_TWEETMEME_AJAX_POST_ID').attr('src','http://api.tweetmeme.com/button.js?url=VOTE_URL&source=VOTE_SOURCE&style=VOTE_BUTTON_DESIGN&service=VOTE_SERVICE_NAME&service_api=VOTE_SERVICE_API'); }); }";
	const SCHEDULER_LAZY_SCRIPT = "window.setTimeout('loadTweetMeme_POST_ID()',SCHEDULER_TIMER);";
	const SCHEDULER_LAZY_TIMER = "1000";
	
	var $buttonLayout = array(
		"Normal" => "normal' height='61' width='50'",
		"Compact" => "compact' height='20' width='90'"
	);
	
	var $buttonLayoutLazy = array(
		"Normal" => "normal",
		"Compact" => "compact"
	);
	
	var $buttonLayoutLazyWidth = array(
		"Normal" => "50",
		"Compact" => "90"
	);
    
    var $buttonLayoutLazyHeight = array(
		"Normal" => "61",
		"Compact" => "20"
	);
	
	//url or title dun need to encode
	var $isEncodeRequired = false;
	
    const DEFAULT_BUTTON_WEIGHT = "97";
          
	const VOTE_SOURCE = "VOTE_SOURCE";
	const VOTE_SERVICE_NAME = "VOTE_SERVICE_NAME";
	const VOTE_SERVICE_API = "VOTE_SERVICE_API";
	
	//constructor
    public function DD_TweetMeme() {
    	
    	//wordpress option value
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->baseURL_lazy = self::BASEURL_LAZY;
    	$this->baseURL_lazy_script = self::BASEURL_LAZY_SCRIPT;
    	$this->scheduler_lazy_script = self::SCHEDULER_LAZY_SCRIPT;
    	$this->scheduler_lazy_timer = self::SCHEDULER_LAZY_TIMER;

		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
        parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
    } 
    
	public function constructURL($url, $title,$button, $postId, $lazy, $globalcfg = '',$commentcount = ''){
    	
 		if($this->isEncodeRequired){
 			$title = rawurlencode($title);
    		$url = rawurlencode($url);
 		}
 		
 		$source = '';
 		$service = '';
 		$serviceapi = '';
 		
 		if($globalcfg!=''){
 			$source = $globalcfg[DD_GLOBAL_TWEETMEME_OPTION][DD_GLOBAL_TWEETMEME_OPTION_SOURCE]; 
 			$service = $globalcfg[DD_GLOBAL_TWEETMEME_OPTION][DD_GLOBAL_TWEETMEME_OPTION_SERVICE];
 			$serviceapi = $globalcfg[DD_GLOBAL_TWEETMEME_OPTION][DD_GLOBAL_TWEETMEME_OPTION_SERVICE_API];
 		}

    	if($lazy==DD_EMPTY_VALUE){

    		$this->baseURL = str_replace(self::VOTE_SOURCE,$source,$this->baseURL);
    		$this->baseURL = str_replace(self::VOTE_SERVICE_NAME,$service,$this->baseURL);
    		$this->baseURL = str_replace(self::VOTE_SERVICE_API,$serviceapi,$this->baseURL);
    	
    		$this->constructNormalURL($url, $title,$button, $postId);
    		
    	}else{

    		$this->baseURL_lazy_script = str_replace(self::VOTE_SOURCE,$source,$this->baseURL_lazy_script);
    		$this->baseURL_lazy_script = str_replace(self::VOTE_SERVICE_NAME,$service,$this->baseURL_lazy_script);
    		$this->baseURL_lazy_script = str_replace(self::VOTE_SERVICE_API,$serviceapi,$this->baseURL_lazy_script);
    	
    		$this->constructLazyLoadURL($url, $title,$button, $postId);
    	}
    	
    }
    
}

//topsy.com
class DD_Topsy extends BaseDD{
	
	const NAME = "Topsy";
	const URL_WEBSITE = "http://www.topsy.com";
	const URL_API = "http://labs.topsy.com/button/retweet-button/";
	
	const BASEURL = "<script type=\"text/javascript\" src=\"http://cdn.topsy.com/topsy.js?init=topsyWidgetCreator\"></script><div class=\"topsy_widget_data\"><!--{\"url\":\"VOTE_URL\",\"style\":\"VOTE_BUTTON_DESIGN\",\"theme\":\"VOTE_THEME\",\"nick\":\"VOTE_SOURCE\"}--></div>";

	const OPTION_APPEND_TYPE = "dd_topsy_appendType";
	const OPTION_BUTTON_DESIGN = "dd_topsy_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_topsy_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_topsy_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_topsy_lazy_load";
		
    const DEFAULT_BUTTON_WEIGHT = "96";

	const VOTE_SOURCE = "VOTE_SOURCE";
	const VOTE_THEME = "VOTE_THEME";
	
	var $islazyLoadAvailable = false;
	var $isEncodeRequired = false;
	
	var $buttonLayout = array(
		"Normal" => "big",
		"Compact" => "compact"
	);
	
    public function DD_Topsy() {
    	
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;

		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
        parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
        
    } 
    
    public function constructURL($url, $title,$button, $postId, $lazy, $globalcfg = '', $commentcount = ''){

    	if($this->isEncodeRequired){
 			$title = rawurlencode($title);
    		$url = rawurlencode($url);
 		}
 		
 		$source = '';
 		$theme = '';
 		
 		if($globalcfg!=''){
 			$source = $globalcfg[DD_GLOBAL_TOPSY_OPTION][DD_GLOBAL_TOPSY_OPTION_SOURCE]; 
 			$theme = $globalcfg[DD_GLOBAL_TOPSY_OPTION][DD_GLOBAL_TOPSY_OPTION_THEME];
 		}
 		
    	$finalURL = '';
    	$finalURL = str_replace(parent::VOTE_BUTTON_DESIGN,$this->getButtonDesign($button),$this->baseURL);
    	$finalURL = str_replace(parent::VOTE_URL,$url,$finalURL);
    	$finalURL = str_replace(self::VOTE_SOURCE,$source,$finalURL);
    	$finalURL = str_replace(self::VOTE_THEME,$theme,$finalURL);
    
    	$this->finalURL = $finalURL;

    	
    }
    
}

//post comments
class DD_Comments extends BaseDD{

	const NAME = "Comments";
	const URL_WEBSITE = "http://none";
	const URL_API = "http://none";
	
	const BASEURL = "<div id='dd_comments'><a class='clcount' href=VOTE_URL><span class='ctotal'>COMMENTS_COUNT</span></a><a class='clink' href=VOTE_URL></a></div>";
	
	const OPTION_APPEND_TYPE = "dd_comments_appendType";
	const OPTION_BUTTON_DESIGN = "dd_comments_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_comments_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_comments_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_comments_lazy_load";
	
	const DEFAULT_BUTTON_WEIGHT = "88";
	
	const COMMENTS_COUNT = "COMMENTS_COUNT";
	const COMMENTS_RESPONSE_ID = "#respond";
	
	var $islazyLoadAvailable = false;
	
	var $buttonLayout = array(
		"Normal" => "Normal",
	);
    
    public function DD_Comments() {
    	
    	$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
        parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
        
    }     
    
    public function constructURL($url, $title,$button, $postId, $lazy, $globalcfg = '', $commentcount = ''){
    	$result = '';
    	
    	$url = $url . self::COMMENTS_RESPONSE_ID;
    	$result = str_replace(self::VOTE_URL,$url,$this->baseURL);
    	$result = str_replace(self::COMMENTS_COUNT,$commentcount,$result);
    	
    	$this->finalURL = $result;
    }
    
}

//serpd.com
class DD_Serpd extends BaseDD{
	
	const NAME = "Serpd";
	const URL_WEBSITE = "http://www.serpd.com";
	const URL_API = "http://www.serpd.com/widgets/";
	const BASEURL = "<script type=\"text/javascript\">submit_url = \"VOTE_URL\";</script><script type=\"text/javascript\" src=\"http://www.serpd.com/index.php?page=evb\"></script>";
	
	const OPTION_APPEND_TYPE = "dd_serpd_appendType";
	const OPTION_BUTTON_DESIGN = "dd_serpd_buttonDesign";
	const OPTION_BUTTON_WEIGHT = "dd_serpd_button_weight";
	const OPTION_AJAX_LEFT_FLOAT = "dd_serpd_ajax_left_float";
	const OPTION_LAZY_LOAD = "dd_serpd_lazy_load";
	
	const DEFAULT_BUTTON_WEIGHT = "86";
	
	var $islazyLoadAvailable = false;
	
	//url or title dun need to encode
	var $isEncodeRequired = false;
	
	var $buttonLayout = array(
		"Normal" => "",
	);
	
	//constructor
    public function DD_Serpd() {
    	
    	//wordpress option value
		$this->option_append_type = self::OPTION_APPEND_TYPE;
		$this->option_button_design = self::OPTION_BUTTON_DESIGN;
		$this->option_button_weight = self::OPTION_BUTTON_WEIGHT;
		$this->option_ajax_left_float = self::OPTION_AJAX_LEFT_FLOAT;
		$this->option_lazy_load = self::OPTION_LAZY_LOAD;
		
		$this->button_weight_value = self::DEFAULT_BUTTON_WEIGHT;
		
        parent::BaseDD(self::NAME, self::URL_WEBSITE, self::URL_API, self::BASEURL);
    }    
}

?>