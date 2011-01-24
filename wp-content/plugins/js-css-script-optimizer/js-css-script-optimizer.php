<?php
/*
  Plugin Name: JS & CSS Script Optimizer
  Plugin URI: http://4coder.info/en/wordpress-js-css-optimizer/
  Version: 0.2.0
  Author: Evgeniy Kotelnitskiy
  Author URI: http://4coder.info/en/
  Description: Features: Combine all scripts into the single file, Pack scripts using <a href="http://joliclic.free.fr/php/javascript-packer/en/">PHP version of the Dean Edwards's JavaScript Packer</a>, Move all JavaScripts to the bottom, Combine all CSS scripts into the single file, Pack CSS files (remove comments, tabs, spaces, newlines).
*/
class evScriptOptimizer {

    static $upload_path = '';
    static $upload_url = '';
    static $plugin_path = '';
    static $cache_directory = '';
    static $cache_url = '';
    static $options = null;
    static $js_printed = false;
	static $css_printed = false;

    /**
     * init
     */
    function init() {
        
        // init some constants
        if (get_option('upload_url_path')) {
            self::$upload_path = get_option('upload_path');
            self::$upload_url = get_option('upload_url_path');
        }
        else {
            self::$upload_path = ABSPATH . 'wp-content/uploads/';
            self::$upload_url = site_url('/wp-content/uploads/');
        }
		if (substr(self::$upload_path, -1) != '/') self::$upload_path .= '/';
		if (substr(self::$upload_url, -1) != '/') self::$upload_url .= '/';
		
        self::$plugin_path = dirname(__FILE__);
        self::$cache_directory = self::$upload_path . 'spacker-cache/';
        self::$cache_url = self::$upload_url . 'spacker-cache/';

        // load plugin localizations
        load_plugin_textdomain('spacker', self::$plugin_path . '/lang', self::$plugin_path . '/lang');

        // load options
        self::$options = get_option('spacker-options');
        if (! is_array(self::$options)) {
            self::$options = array(
                'only-selfhosted-js'  => true,
                'combine-js'          => 'combine',
                'packing-js'          => true,
				'css'                 => true,
                'only-selfhosted-css' => true,
                'combining-css'       => true,
                'packing-css'         => true,
                'cache'               => array());
        }
				
        // add actions and hooks
		if (! is_admin()) {		
			add_action('wp_print_scripts',     array(__CLASS__, 'wp_print_scripts'), 200);
			if (self::$options['css']) {
				add_action('wp_print_styles',  array(__CLASS__, 'wp_print_styles'), 200);
			}
			add_action('wp_footer',            array(__CLASS__, 'footer'), 200);
			add_action('wp_head',              array(__CLASS__, 'head'), 200);

            // Include added scripts
            if (is_array(self::$options['inc-js'])) {
                foreach (self::$options['inc-js'] as $key => $js){
                    if ($js['url']) {
                        wp_deregister_script($key);
                        wp_register_script($key, $js['url'], false);
                    }
                    wp_enqueue_script($key);
                }
            }
			
            if (is_array(self::$options['inc-css'])) {
                foreach (self::$options['inc-css'] as $key => $css){
                    wp_enqueue_style($key, $css['url'], false, false, $css['media']);
                }
            }
        }
        else {
            require_once('backend.php');
            evScriptOptimizerBackend::init();
        }
    }

    /**
     * Check exclude list
     */
    function exclude_this_js($handle, $src) {
        static $exclude_js = false;
        if ($exclude_js === false) {
            $exclude_js = explode(',', self::$options['exclude-js']);
            foreach ($exclude_js as $_k => $_v) {
                $exclude_js[$_k] = trim($_v);
                if (! $exclude_js[$_k])
                    unset($exclude_js[$_k]);
            }
        }
        return (in_array($handle, $exclude_js) || in_array(basename($src), $exclude_js));
    }

    /**
     * Check exclude list for css
     */
    function exclude_this_css($handle, $src) {
        static $exclude_css = false;
        if ($exclude_css === false) {
            $exclude_css = explode(',', self::$options['exclude-css']);
            foreach ($exclude_css as $_k => $_css) {
                $exclude_css[$_k] = trim($_css);
                if (! $exclude_css[$_k]) unset($exclude_css[$_k]);
            }
        }
        return (in_array($handle, $exclude_css) || in_array(basename($src), $exclude_css));
    }

    /**
     * wp_print_scripts action
     *
     * @global $wp_scripts, $auto_compress_scripts
     */
    function wp_print_scripts() {
        if (is_admin()) return;

        global $wp_scripts, $auto_compress_scripts;
		if (! is_a($wp_scripts, 'WP_Scripts')) return;
		
        if (! is_array($auto_compress_scripts))
            $auto_compress_scripts = array();

        $queue = $wp_scripts->queue;
        $wp_scripts->all_deps($queue);
        $to_do = $wp_scripts->to_do;

        foreach ($to_do as $key => $handle) {
            $src = $wp_scripts->registered[$handle]->src;

            // Check exclude list
            if (self::exclude_this_js($handle, $src))
                continue;

            // Check host
            if (substr($src, 0, 4) != 'http') {
                $src = site_url($src);
                $external = false;
            }
            else {
                $home = get_option('home');		
                if (substr($src, 0, strlen($home)) == $home) {
                    $external = false;
                }
                else $external = true;
            }
			
            if (! self::$options['only-selfhosted-js'] || ! $external) {
                unset($wp_scripts->to_do[$key]);
                $auto_compress_scripts[$handle] = array('src' => $src, 'external' => $external);
            }
        }
        foreach ($wp_scripts->queue as $key => $handle) {
            if (isset($auto_compress_scripts[$handle]))
                unset($wp_scripts->queue[$key]);
        }

        // printing scripts hear or move to the bottom
        if (! self::$options['combine-js'] || self::$js_printed) {
            self::print_compressed_scripts();
        }       
    }

    /**
     * wp_print_styles action
     *
     * @global $wp_styles, $auto_compress_styles
     */
    function wp_print_styles() {
        if (is_admin()) return;
		
        global $wp_styles, $auto_compress_styles;
		if (! is_object($wp_styles)) return;
			
        if (! is_array($auto_compress_styles))
            $auto_compress_styles = array();
		
        $queue = $wp_styles->queue;
        $wp_styles->all_deps($queue);
        $to_do = $wp_styles->to_do;
        $queue_unset = array();
		
        foreach ($to_do as $key => $handle) {
            $src = $wp_styles->registered[$handle]->src;

            // Check exclude list
            if (self::exclude_this_css($handle, $src))
                continue;

            $media = ($wp_styles->registered[$handle]->args ? $wp_styles->registered[$handle]->args : 'screen');

            if (substr($src, 0, 4) != 'http') {
                $src = site_url($src);
                $external = false;
            }
            else {
                $home = get_option('home');
                if (substr($src, 0, strlen($home)) == $home) {
                    $external = false;
                }
                else $external = true;
            }

            if (! self::$options['only-selfhosted-css'] || ! $external) {
                unset($wp_styles->to_do[$key]);
                
                $auto_compress_styles[$media][$handle] = array('src' => $src, 'external' => $external);
                $queue_unset[$handle] = true;
            }
        }

        foreach ($wp_styles->queue as $key => $handle) {
            if (isset($queue_unset[$handle]))
                unset($wp_styles->queue[$key]);
        }

		// printing CSS
		if (self::$css_printed || !self::$options['combine-css']) {
			self::wp_head_print_styles();
		}
    }
	
	function wp_head_print_styles() {
		global $auto_compress_styles;
        foreach ($auto_compress_styles as $media => $scripts) {
            self::print_compressed_styles($media);
        }
		self::$css_printed = true;
	}

    function print_compressed_scripts() {
        global $auto_compress_scripts;
        if (! is_array($auto_compress_scripts) || ! count($auto_compress_scripts))
            return;

        $home = get_option('siteurl').'/';
        if (! is_array(self::$options['cache']))
            self::$options['cache'] = array();

        if (self::$options['combine-js']) {
            $handles = array_keys($auto_compress_scripts);
            $handles = implode(', ', $handles);
            $cache_name = md5($handles);
            $cache_file_path = self::$cache_directory . $cache_name . '.js';
            $cache_file_url = self::$cache_url . $cache_name . '.js';

            // Calc "modified tag"
            $fileId = 0;
            foreach ($auto_compress_scripts as $handle => $script) {
                if (! $script['external']) {
                    $path = ABSPATH . str_replace($home, '', $script['src']);
                    $fileId += @filemtime($path);
                }
            }

            //echo "$fileId<br>".self::$options['cache'][$cache_name]."<br>$cache_file_path<br>$cache_file_url<br>".is_readable($cache_file_path);
            // Find a cache
            if ((self::$options['cache'][$cache_name] == $fileId) && is_readable($cache_file_path)) {
                // Include script: ?>
                <script type="text/javascript" src="<?php echo $cache_file_url; ?>">/*Is Cache!*/</script>
                <?php
                $auto_compress_scripts = array();
                return;
            }

            // Build cache
            $scripts = '';
            foreach ($auto_compress_scripts as $handle => $script) {
                $src = $script['src'];
                $scripts .= "/* $handle: ($src) */\n";
                $contents = @file_get_contents($src);

                if (self::$options['packing-js']) {
                    require_once self::$plugin_path . '/JavaScriptPacker.php';
                    $packer = new JavaScriptPacker($contents);
                    $contents = $packer->pack();
                }
                $scripts .= $contents . "\n";
            }
            $comment = "/*\nCache: ".$handles."\n*/\n";

            // Save cache
            self::save_script($cache_file_path, $comment . $scripts);
            self::$options['cache'][$cache_name] = $fileId;
            update_option('spacker-options', self::$options);

            // Include script: ?>
            <script type="text/javascript" src="<?php echo $cache_file_url; ?>"></script>
            <?php
            $auto_compress_scripts = array();
            //--------------------------------------------------------------------------------
        }
        else {
            foreach ($auto_compress_scripts as $handle => $script) {
                $src = $script['src'];
                $fileId = 0;
                if (! $script['external']) {
                    $path = ABSPATH . str_replace($home, '', $script['src']);
                    $fileId = @filemtime($path);
                }
                $cache_name = md5($handle);
                $cache_file_path = self::$cache_directory . $cache_name . '.js';
                $cache_file_url = self::$cache_url . $cache_name . '.js';

                // Find a cache
                if ((self::$options['cache'][$cache_name] == $fileId) && is_readable($cache_file_path)) {
                    // Include script: ?>
                    <script type="text/javascript" src="<?php echo $cache_file_url; ?>">/*Is Cache!*/</script>
                    <?php
                    continue;
                }

                //echo "$src ($fileId)<br>";
                $content = @file_get_contents($src);
                if (self::$options['packing-js']) {
                    require_once self::$plugin_path . '/JavaScriptPacker.php';
                    $packer = new JavaScriptPacker($content);
                    $content = $packer->pack();
                }

                // Save cache
                $comment = "/* $handle: ($src) */\n";
                self::save_script($cache_file_path, $comment . $content);
                self::$options['cache'][$cache_name] = $fileId;
                ?>
                <script type="text/javascript" src="<?php echo $cache_file_url; ?>"></script>
                <?php
            }
            update_option('spacker-options', self::$options);
            $auto_compress_scripts = array();
        }
    }

    function compress_css($css, $path) {
        // remove comments, tabs, spaces, newlines, etc.
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);

        // url
        $dir = dirname($path).'/';
        $css = preg_replace('|url\(\'?"?([a-zA-Z0-9\-_\s\./]*)\'?"?\)|', "url(\"$dir$1\")", $css);

        return $css;
    }
	
    /*
     * Print CSS
     */
    function print_compressed_styles($media = 'screen') {
        global $auto_compress_styles;
        if (! is_array($auto_compress_styles[$media]) || ! count($auto_compress_styles[$media]))
            return;

        $home = get_option('siteurl').'/';
        if (! is_array(self::$options['cache-css']))
            self::$options['cache-css'] = array();

        if (self::$options['combining-css']) {
            $handles = array_keys($auto_compress_styles[$media]);
			$handles = implode(', ', $handles);
            $cache_name = md5($handles);
            $cache_file_path = self::$cache_directory . $cache_name . '.css';
            $cache_file_url = self::$cache_url . $cache_name . '.css';

            // Calc "modified tag"
            $fileId = 0;
            foreach ($auto_compress_styles[$media] as $handle => $script) {
                if (! $script['external']) {
                    $path = ABSPATH . str_replace($home, '', $script['src']);
                    $fileId += @filemtime($path);
                }
            }

            //echo "$fileId<br>".self::$options['cache'][$cache_name]."<br>$cache_file_path<br>$cache_file_url<br>".is_readable($cache_file_path);
            // Find a cache
            if ((self::$options['cache-css'][$cache_name] == $fileId) && is_readable($cache_file_path)) {
                // Include script: ?>
                <link rel="stylesheet" href="<?php echo $cache_file_url; ?>" type="text/css" media="<?php echo $media; ?>" /><!-- Is Cache! -->
                <?php
                $auto_compress_styles[$media] = array();
                return;
            }

            // Build cache
            $scripts = '';
            foreach ($auto_compress_styles[$media] as $handle => $script) {
                $src = $script['src'];
                $scripts .= "/* $handle: ($src) */\n";
                $content = @file_get_contents($src);

                if (self::$options['packing-css']) {
                    $content = self::compress_css($content, $src);
                }
                $scripts .= $content . "\n";
            }
            $comment = "/*\nCache: ".$handles."\n*/\n";

            // Save cache
            self::save_script($cache_file_path, $comment . $scripts);
            self::$options['cache-css'][$cache_name] = $fileId;
            update_option('spacker-options', self::$options);

            // Include script: ?>
            <link rel="stylesheet" href="<?php echo $cache_file_url; ?>" type="text/css" media="<?php echo $media; ?>" />
            <?php
            $auto_compress_styles[$media] = array();
            //--------------------------------------------------------------------------------
        }
        else {
            foreach ($auto_compress_styles[$media] as $handle => $script) {
                $src = $script['src'];
                $fileId = 0;
                if (! $script['external']) {
                    $path = ABSPATH . str_replace($home, '', $script['src']);
                    $fileId = @filemtime($path);
                }
                $cache_name = md5($handle);
                $cache_file_path = self::$cache_directory . $cache_name . '.css';
                $cache_file_url = self::$cache_url . $cache_name . '.css';

                // Find a cache
                if ((self::$options['cache-css'][$cache_name] == $fileId) && is_readable($cache_file_path)) {
                    // Include script: ?>
                    <link rel="stylesheet" href="<?php echo $cache_file_url; ?>" type="text/css" media="<?php echo $media; ?>" /><!-- Is Cache! -->
                    <?php
                    continue;
                }

                //echo "$src ($fileId)<br>";
                $content = @file_get_contents($src);
                if (self::$options['packing-css']) {
                    $content = self::compress_css($content, $src);
                }

                // Save cache
                $comment = "/* $handle: ($src) */\n";
                self::save_script($cache_file_path, $comment . $content);
                self::$options['cache-css'][$cache_name] = $fileId;
                ?>
                <link rel="stylesheet" href="<?php echo $cache_file_url; ?>" type="text/css" media="<?php echo $media; ?>" />
                <?php
            }
            update_option('spacker-options', self::$options);
            $auto_compress_styles[$media] = array();
        }
    }

    function save_script($filename, $content) {
        if (is_writable(self::$upload_path)) {
            $fhandle = @fopen($filename, 'w+');
            if ($fhandle) fwrite($fhandle, $content, strlen($content));
        }
        return false;
    }

    function head() {
        if (self::$options['combine-js'] == 'combine') {
            self::print_compressed_scripts();
        }
		if (self::$options['combine-css']) {
			self::wp_head_print_styles();
		}
    }

    function footer() {
        if (self::$options['combine-js']) {
            self::$js_printed = true;
            self::print_compressed_scripts();
        }
    }
}

evScriptOptimizer::init();
