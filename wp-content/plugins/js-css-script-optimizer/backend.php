<?php
class evScriptOptimizerBackend {

    /**
     * init
     */
    function init() {
		register_activation_hook(__FILE__,       array(__CLASS__, 'activation_hook'));
		add_action('admin_menu',                 array(__CLASS__, 'admin_menu'));
        add_action('admin_head',                 array(__CLASS__, 'ajax_javascript'));
        add_action('admin_head',                 array(__CLASS__, 'admin_css'));
        add_action('wp_ajax_spacker_inc_script', array(__CLASS__, 'wp_ajax_spacker_inc_script'));
    }

    function activation_hook() {
        self::cache_directory();
        
        if (! is_array(evScriptOptimizer::$options['inc-js'])) {
            evScriptOptimizer::$options['inc-js'] = array(
                                                        'jquery' => array(
                                                            'name' => 'jquery',
                                                            'url' => 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'));
        }
    }

    function cache_directory() {
        if (is_writable(evScriptOptimizer::$cache_directory)) {
            evScriptOptimizer::$options['cache_dir_message'] = false;
            return true;
        }
        else {
            if (is_writable(evScriptOptimizer::$upload_path)) {
                if (@mkdir(evScriptOptimizer::$cache_directory) && @chmod(evScriptOptimizer::$cache_directory, 0777)) {
                    evScriptOptimizer::$options['cache_dir_message'] = false;
                    return true;
                }
                else {
                    evScriptOptimizer::$options['cache_dir_message'] = sprintf(__('Cannot create cache directory "%s". Please create this folder with a permissions to "write" (777)', 'spacker'), evScriptOptimizer::$upload_path);
                }
            }
            else {
                evScriptOptimizer::$options['cache_dir_message'] = sprintf(__('Uploads directory is not writeable by the web server. Please set permissions to 777 for the "%s"', 'spacker'), evScriptOptimizer::$upload_path);
            }
        }
        return false;
    }

    /**
     * admin_menu action
     */
    function admin_menu() {	
        add_options_page(__('JS & CSS Script Optimizer Options', 'spacker'), __('Script Optimizer', 'spacker'), 'manage_options', 'script-optimizer', array(__CLASS__, 'settings_section'));
    }

    function is_option_tab($tab_name) {
        if (($tab_name == 'basic') && (!isset($_GET['tab']) || ($_GET['tab'] == 'basic')))
            return true;

        return (isset($_GET['tab']) && $_GET['tab'] == $tab_name);
    }

    function settings_section() {
        if (!current_user_can('manage_options')) {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }

        $saved = false;
        if (isset($_POST['spacker']) && is_array($_POST['spacker'])) {
            //evScriptOptimizer::$options = $_POST['spacker'];
            self::options_join(evScriptOptimizer::$options, $_POST['spacker']);
			
            evScriptOptimizer::$options['cache'] = array();
            evScriptOptimizer::$options['cache-css'] = array();
            //print_r(evScriptOptimizer::$options);

            update_option('spacker-options', evScriptOptimizer::$options);
            $saved = true;
        }
        ?>
        <div class="wrap">
            <div class="icon32" id="icon-tools"><br></div>
            <h2 class="spacker-backend-title">
                <?php _e('JS & CSS Script Optimizer Options', 'spacker') ?>
                <sup><a href="http://4coder.info/en/wordpress-js-css-optimizer/?wpopt"><?php _e('(i)', 'spacker') ?></a></sup>
				
				<div class="spacker-donate">
					<form target="_blank" method="post" action="https://www.moneybookers.com/app/payment.pl">
						<fieldset>
							<legend><?php _e('Donate with Moneybookers:', 'spacker') ?></legend>

							<input type="hidden" value="evgennniy@gmail.com" name="pay_to_email">
							<input type="hidden" value="http://4coder.info/donate-thanks/" name="return_url">

							<input type="text" size="5" value="5.00" name="amount">

							<select size="1" name="currency">
								<option value="USD">$ USD</option>
								<option value="EUR">&#8364; EUR</option>
								<option value="GBP">&pound; GBP</option>
								<option value="JPY">&yen; JPY</option>
								<option value="CAD">$ CAD</option>
								<option value="AUD">$ AUD</option>
							</select>

							<input type="submit" value="Donate!" alt="Click to make a donation" class="button-primary">
							<input type="hidden" value="EN" name="language">
							<input type="hidden" value="Donation mission" name="detail1_description">
							<input type="hidden" value="Donation to evolve 4Coder.info" name="detail1_text">
						</fieldset>
					</form> 
				</div>
            </h2>
            <ul class="subsubsub" id="spacker-menu">
                <li><a <?php if (self::is_option_tab('basic')) 		echo 'class="current"'; ?> href="<?php echo add_query_arg('tab', 'basic') ?>">Basic </a> |</li>
                <li><a <?php if (self::is_option_tab('inc_js')) 	echo 'class="current"'; ?> href="<?php echo add_query_arg('tab', 'inc_js') ?>">Include JS</a> |</li>
                <li><a <?php if (self::is_option_tab('inc_css')) 	echo 'class="current"'; ?> href="<?php echo add_query_arg('tab', 'inc_css') ?>">Include CSS</a> |</li>
				<li><a <?php if (self::is_option_tab('help')) 		echo 'class="current"'; ?> href="<?php echo add_query_arg('tab', 'help') ?>">Help</a></li>
            </ul>
            <br class="clear"/>

            <?php if (! self::cache_directory()) { ?>
            <div class="error" id="message">
                <p><?php echo evScriptOptimizer::$options['cache_dir_message']; ?></p>
            </div>
            <?php } ?>

            <?php if ($saved) { ?>
            <div class="updated" id="message">
                <p><strong><?php _e('Options have been saved! Cache clear.', 'spacker'); ?></strong></p>
            </div>
            <?php } ?>
            
            <form action="" method="post">
                <?php
                if (self::is_option_tab('inc_js')) self::options_tab_inc_js();
                elseif (self::is_option_tab('inc_css')) self::options_tab_inc_css();
				elseif (self::is_option_tab('help')) self::options_tab_help();
                else self::options_tab_basic();
                ?>
            </form>
            <br/>
        </div>
        <?php
    }
	
	// --- Admin Tabs: ---
	
    function options_tab_basic() { ?>
        <table class="form-table">
            <tr valign="top">
                <td colspan="2">
                    <h3><?php _e('JavaScript output options'); ?></h3>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('For self-hosted only', 'spacker'); ?></th>
                <td>
					<input name="spacker[only-selfhosted-js]" type="hidden" value="0" />
                    <input name="spacker[only-selfhosted-js]" type="checkbox" id="only-selfhosted-js" value="1" <?php if (evScriptOptimizer::$options['only-selfhosted-js']) echo 'checked'; ?> />
                    <label for="only-selfhosted-js"><?php _e('Ignore external JavaScript', 'spacker'); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Pack JavaScripts', 'spacker'); ?></th>
                <td>
					<input name="spacker[packing-js]" type="hidden" value="0" />
                    <input name="spacker[packing-js]" type="checkbox" id="packing-js" value="1" <?php if (evScriptOptimizer::$options['packing-js']) echo 'checked'; ?> />
                    <label for="packing-js"><?php _e('Pack scripts using Dean Edwards\'s JavaScript Packer', 'spacker'); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Combine JavaScripts', 'spacker'); ?></th>
                <td>
                    <input name="spacker[combine-js]" type="radio" id="combine-js" value="combine" <?php if (evScriptOptimizer::$options['combine-js'] == 'combine') echo 'checked'; ?> />
                    <label for="combine-js"><?php _e('Combine all scripts into the two files (in the header & footer)', 'spacker'); ?></label><br/>

                    <input name="spacker[combine-js]" type="radio" id="move-bottom-js" value="move-bottom" <?php if (evScriptOptimizer::$options['combine-js'] == 'move-bottom') echo 'checked'; ?> />
                    <label for="move-bottom-js"><?php _e('Combine & Move all JavaScripts to the bottom', 'spacker'); ?></label><br/>

                    <input name="spacker[combine-js]" type="radio" id="no-combine-js" value="0" <?php if (! evScriptOptimizer::$options['combine-js']) echo 'checked'; ?> />
                    <label for="no-combine-js"><?php _e('Do not combine JavaScripts', 'spacker'); ?></label><br/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="exclude-js"><?php _e('Ignore next JavaScripts', 'spacker'); ?></label></th>
                <td>
                    <textarea name="spacker[exclude-js]" id="exclude-js" cols="60"><?php echo esc_attr(evScriptOptimizer::$options['exclude-js']); ?></textarea><br/>
                    <?php _e('Comma separated handles or file names. For example: jquery, jquery-ui.js', 'spacker'); ?>
                </td>
            </tr>

            <tr valign="top">
                <td colspan="2">
                    <h3><?php _e('Style-sheets output options'); ?></h3>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="spacker-css"><?php _e('Enable CSS optimizer', 'spacker'); ?></label></th>
                <td>
					<input name="spacker[css]" type="hidden" value="0" />
                    <input onchange="spacker_enable_css_options()" name="spacker[css]" type="checkbox" id="spacker-css" value="1" <?php if (evScriptOptimizer::$options['css']) echo 'checked'; ?> />
                    <label for="spacker-css"><?php _e('Use plugin for CSS', 'spacker'); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('For self-hosted only', 'spacker'); ?></th>
                <td>
					<input name="spacker[only-selfhosted-css]" type="hidden" value="0" />
                    <input name="spacker[only-selfhosted-css]" type="checkbox" id="only-selfhosted-css" value="1" <?php if (evScriptOptimizer::$options['only-selfhosted-css']) echo 'checked'; ?> />
                    <label for="only-selfhosted-css"><?php _e('Ignore external CSS', 'spacker'); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Combine CSS', 'spacker'); ?></th>
                <td>
					<input name="spacker[combining-css]" type="hidden" value="0" />
                    <input name="spacker[combining-css]" type="checkbox" id="combining-css" value="1" <?php if (evScriptOptimizer::$options['combining-css']) echo 'checked'; ?> />
                    <label for="combining-css"><?php _e('Combine all CSS scripts into the single file', 'spacker'); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Style-sheets packing', 'spacker'); ?></th>
                <td>
					<input name="spacker[packing-css]" type="hidden" value="0" />
                    <input name="spacker[packing-css]" type="checkbox" id="packing-css" value="1" <?php if (evScriptOptimizer::$options['packing-css']) echo 'checked'; ?> />
                    <label for="packing-css"><?php _e('Pack CSS files (remove comments, tabs, spaces, newlines)', 'spacker'); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="exclude-css"><?php _e('Ignore next CSS', 'spacker'); ?></label></th>
                <td>
                    <textarea name="spacker[exclude-css]" id="exclude-css" cols="60"><?php echo esc_attr(evScriptOptimizer::$options['exclude-css']); ?></textarea><br/>
                    <?php _e('Comma separated handles or file names. For example: jquery-ui.custom.css', 'spacker'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th colspan="2"><input type="submit" class="button-primary" name="spacker[save]" value="<?php _e('Save options', 'spacker') ?>" class="aligment-right" /></th>
            </tr>

            <script type="text/javascript">
                function spacker_enable_css_options() {
                    var spacker_css_el = document.getElementById('spacker-css');
                    var ch = spacker_css_el.checked;

                    document.getElementById('only-selfhosted-css').disabled = ! ch;
                    document.getElementById('combining-css').disabled = ! ch;
                    document.getElementById('packing-css').disabled = ! ch;
                    document.getElementById('exclude-css').disabled = ! ch;
                }
                spacker_enable_css_options();
            </script>
        </table>
        <?php
    }

    function get_spacker_inc_js_table() { ?>
        <table cellspacing="0" class="widefat post fixed" style="width: 100%">
            <thead>
                <tr>
                    <th class="manage-column" style="width:110px"><?php _e('Name', 'spacker') ?></th>
                    <th class="manage-column"><?php _e('URL', 'spacker') ?></th>
                    <th class="manage-column" style="width:110px"><?php _e('Action', 'spacker') ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="manage-column"><?php _e('Name', 'spacker') ?></th>
                    <th class="manage-column"><?php _e('URL', 'spacker') ?></th>
                    <th class="manage-column"><?php _e('Action', 'spacker') ?></th>
                </tr>
            </tfoot>
            <tbody>
                <?php
                if (is_array(evScriptOptimizer::$options['inc-js'])) {
                    foreach(evScriptOptimizer::$options['inc-js'] as $script) { ?>
                <tr>
                    <td><?php echo $script['name']; ?></td>
                    <td><?php echo $script['url']; ?></td>
                    <td><a href="#" rel="<?php echo $script['name']; ?>" class="delete"><?php _e('Delete', 'spacker'); ?></a></td>
                </tr>
                <?php }
                } ?>
            </tbody>
        </table>
        <?php
    }

    function get_spacker_inc_css_table() { ?>
        <table cellspacing="0" class="widefat post fixed" style="width: 100%">
            <thead>
                <tr>
                    <th class="manage-column" style="width:110px"><?php _e('Name', 'spacker') ?></th>
                    <th class="manage-column"><?php _e('URL', 'spacker') ?></th>
					<th class="manage-column" style="width:110px"><?php _e('Media', 'spacker') ?></th>
                    <th class="manage-column" style="width:110px"><?php _e('Action', 'spacker') ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="manage-column"><?php _e('Name', 'spacker') ?></th>
                    <th class="manage-column"><?php _e('URL', 'spacker') ?></th>
                    <th class="manage-column"><?php _e('Media', 'spacker') ?></th>
					<th class="manage-column"><?php _e('Action', 'spacker') ?></th>
                </tr>
            </tfoot>
            <tbody>
                <?php
                if (is_array(evScriptOptimizer::$options['inc-css'])) {
                    foreach(evScriptOptimizer::$options['inc-css'] as $script) { ?>
                <tr>
                    <td><?php echo $script['name']; ?></td>
                    <td><?php echo $script['url']; ?></td>
					<td><?php echo ucwords($script['media']); ?></td>
                    <td><a href="#" rel="<?php echo $script['name']; ?>" class="delete"><?php _e('Delete', 'spacker'); ?></a></td>
                </tr>
                <?php }
                } ?>
            </tbody>
        </table>
        <?php
    }

    function options_tab_inc_js() { ?>
        <h3>
			<?php _e("Include next JavaScript", 'spacker'); ?>
			<span id="spacker-ajax-status"><?php _e('Loading...', 'spacker'); ?></span>
		</h3>
		<p><?php _e('You can delete the hardcoded JavaScript inclluding from the theme files and to include the JavaScript files here.', 'spacker'); ?></p>
        <div id="spacker_inc_js_table">
            <?php self::get_spacker_inc_js_table(); ?>
        </div>
        <br class="clear"/>
        <div id="spacker_add_js_form">
            <label><span><?php _e('Name:', 'spacker'); ?></span> <input type="text" id="spacker_add_js_name"/></label>
            <br/>
            <label><span><?php _e('URL:', 'spacker'); ?></span> <input type="text" id="spacker_add_js_url"/></label>
            <br/>
            <input type="button" class="button-primary spacker-add-script-button" id="spacker-inc-add-js" value="<?php echo esc_attr(__('Add JavaScript', 'spacker')); ?>" />
        </div>
        <br class="clear"/>
        <?php
    }

    function options_tab_inc_css() { ?>
        <h3>
			<?php _e('Include next StyleSheet', 'spacker'); ?>
			<span id="spacker-ajax-status"><?php _e('Loading...', 'spacker'); ?></span>
		</h3>
		<p><?php _e('You can delete the hardcoded CSS inclluding from the theme files and to include the CSS files here.', 'spacker'); ?></p>
        <div id="spacker_inc_css_table">
            <?php self::get_spacker_inc_css_table(); ?>
        </div>
        <br class="clear"/>
        <div id="spacker_add_js_form">
            <label><span><?php _e('Name:', 'spacker'); ?></span> <input type="text" id="spacker_add_css_name"/></label>
            <br/>
            <label><span><?php _e('URL:', 'spacker'); ?></span> <input type="text" id="spacker_add_css_url"/></label>
            <br/>
            <label>
				<span><?php _e('Media:', 'spacker'); ?></span> 
				<select type="text" id="spacker_add_css_media">
					<option value="screen">Screen</option>
					<option value="print">Print</option>
					<option value="all">All</option>
				</select>			
			</label>
            <br/>
            <input type="button" class="button-primary spacker-add-script-button" id="spacker-inc-add-css" value="<?php echo esc_attr(__('Add CSS', 'spacker')); ?>" />
        </div>
        <br class="clear"/>
        <?php
    }
	
    function options_tab_help() { ?>
        <div class="block-content">
            <h3>Plugin Features</h3>
            <ul>
                <li>Combine several scripts into the single file (to minimize http requests)</li>
                <li>Pack scripts using Dean Edwards's JavaScript Packer</li>
                <li>You can move all JavaScripts to the bottom</li>
                <li>Combine all CSS scripts into the single files (with grouping by "media")</li>
                <li>Pack CSS files (remove comments, tabs, spaces, newlines)</li>
                <li>Ability to include JavaScript and CSS files (new)</li>
                <li>If any script fails and shows error you can add it to exclude list</li>
            </ul>

            <h3>Recommendations</h3>
            <ul>
                <li>This Plugin processes only those scripts that are included properly (using "wp_enqueue_script" or "wp_enqueue_style" function)</li>
                <li>Uploads directory should be writable</li>
				<li>Read <a title="Permanent Link to How to properly add CSS in WordPress" rel="bookmark" href="http://4coder.info/en/how-to-properly-add-css-in-wordpress/">How to properly add CSS in WordPress</a></li>
            </ul>
            <p>For more info visit <a href="http://4coder.info/en/wordpress-js-css-optimizer/" title="This WordPress plugin home page">http://4coder.info/en/wordpress-js-css-optimizer/</a>.</p>
        </div>

        <h3><?php _e("Help me to make my site more fast &rarr;", 'spacker'); ?></h3>

        <p><?php _e("We appreciate your attention to our plug-in and hope it works well for you.", 'spacker'); ?></p>
        <p><?php _e("If you need any support in the plug-in use or you need an advanced speed optimization please do not hesitate to contact us.", 'spacker'); ?></p>
        <p><?php _e("We will reply as soon as we can.", 'spacker'); ?></p>

		<div class="spacker-help">
			<?php _e("Email", 'spacker'); ?>: <a href="mailto:optimize@4coder.info">optimize@4coder.info</a>
		</div>
        <?php
    }
	
	

    function wp_ajax_spacker_inc_script() {
		// ----------------------------- Add JS --------------------------------
        if ($_POST['mode'] == 'add-js') { 
            if (! is_array(evScriptOptimizer::$options['inc-js'])) {
                evScriptOptimizer::$options['inc-js'] = array();
			}

            $name = trim($_POST['name']);
            $url  = trim($_POST['url']);
            $error_message = '';

            // Validate
            if (empty($name) || empty($url)) {
                $error_message = __('Fields "Name" and "URL" cannot be empty.', 'spacker');
            }
            elseif (isset(evScriptOptimizer::$options['inc-js'][$name])) {
                $error_message = __('This name is already used.', 'spacker');
            }
            else {
                // Add script
                evScriptOptimizer::$options['inc-js'][$name] = array('name'=> $name, 'url' => $url);
                update_option('spacker-options', evScriptOptimizer::$options);
            }

            // Output
            if ($error_message) { ?>
                <div class="error settings-error">
                    <p><strong><?php echo $error_message; ?></strong></p>
                </div>
            <?php
            } else { ?>
                <div class="updated">
                    <p><strong><?php _e('Script has been added.', 'spacker'); ?></strong></p>
                </div>
            <?php
            }
            self::get_spacker_inc_js_table();
        }

        // ---------------------------- Delete JS ------------------------------
        elseif($_POST['mode'] == 'delete-js') { 
            $name = $_POST['name'];

            if (! isset(evScriptOptimizer::$options['inc-js'][$name])) {
                $error_message = __('Cannot find this script.', 'spacker');
            }
            else {
                // Delete script
                unset(evScriptOptimizer::$options['inc-js'][$name]);
                update_option('spacker-options', evScriptOptimizer::$options);
            }

            // Output
            if ($error_message) { ?>
                <div class="error settings-error">
                    <p><strong><?php echo $error_message; ?></strong></p>
                </div>
            <?php
            } else { ?>
                <div class="updated">
                    <p><strong><?php _e('Script has been deleted.', 'spacker'); ?></strong></p>
                </div>
            <?php
            }
            self::get_spacker_inc_js_table();
        }

        // ----------------------------- Add CSS -------------------------------
        elseif ($_POST['mode'] == 'add-css') { 
            if (! is_array(evScriptOptimizer::$options['inc-css']))
                evScriptOptimizer::$options['inc-css'] = array();

            $name = trim($_POST['name']);
            $url  = trim($_POST['url']);
			$media  = trim($_POST['media']);
            $error_message = '';

            // Validate
            if (empty($name) || empty($url)) {
                $error_message = __('Fields "Name" and "URL" cannot be empty.', 'spacker');
            }
            elseif (isset(evScriptOptimizer::$options['inc-css'][$name])) {
                $error_message = __('This Name is already used.', 'spacker');
            }
            else {
                // Add script
                evScriptOptimizer::$options['inc-css'][$name] = array('name'=> $name, 'url' => $url, 'media' => $media);
                update_option('spacker-options', evScriptOptimizer::$options);
            }

            // Output
            if ($error_message) { ?>
                <div class="error settings-error">
                    <p><strong><?php echo $error_message; ?></strong></p>
                </div>
            <?php
            } else { ?>
                <div class="updated">
                    <p><strong><?php _e('Script has been added.', 'spacker'); ?></strong></p>
                </div>
            <?php
            }
            self::get_spacker_inc_css_table();
        }

        // ----------------------------- Delete CSS ----------------------------
        elseif($_POST['mode'] == 'delete-css') { 
            $name = $_POST['name'];

            if (! isset(evScriptOptimizer::$options['inc-css'][$name])) {
                $error_message = __('Cannot find this script.', 'spacker');
            }
            else {
                // Delete script
                unset(evScriptOptimizer::$options['inc-css'][$name]);
                update_option('spacker-options', evScriptOptimizer::$options);
            }

            // Output
            if ($error_message) { ?>
                <div class="error settings-error">
                    <p><strong><?php echo $error_message; ?></strong></p>
                </div>
            <?php
            } else { ?>
                <div class="updated">
                    <p><strong><?php _e('Script has been deleted.', 'spacker'); ?></strong></p>
                </div>
            <?php
            }
            self::get_spacker_inc_css_table();
        }
        
        die();
    }
    
    function ajax_javascript() {
        if (isset($_GET['page']) && ($_GET['page'] === 'script-optimizer')) { ?>
        <script type="text/javascript">
            function spacker_save_js(data){
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function(response) {
                    jQuery('#spacker_inc_js_table').html(response);
                });
            }

            function spacker_save_css(data){
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function(response) {
                    jQuery('#spacker_inc_css_table').html(response);
                });
            }

            jQuery(document).ready(function($) {
                jQuery('#spacker-inc-add-js').click(function() {
                    var data = {
                        action: 'spacker_inc_script'
                    };

                    data['mode'] = 'add-js';
                    data['name'] = $('#spacker_add_js_name').val();
                    data['url'] = $('#spacker_add_js_url').val();

                    spacker_save_js(data);
                });

                jQuery('#spacker_inc_js_table').delegate("a.delete", "click", function() {
                    var data = {
                        action: 'spacker_inc_script'
                    };

                    data['mode'] = 'delete-js';
                    data['name'] = jQuery(this).attr('rel');
                    spacker_save_js(data);
                    return false;
                });

                jQuery('#spacker-inc-add-css').click(function() {
                    var data = {
                        action: 'spacker_inc_script'
                    };

                    data['mode'] = 'add-css';
                    data['name'] = $('#spacker_add_css_name').val();
                    data['url'] = $('#spacker_add_css_url').val();
					data['media'] = $('#spacker_add_css_media').val();					

                    spacker_save_css(data);
                });

                jQuery('#spacker_inc_css_table').delegate("a.delete", "click", function() {
                    var data = {
                        action: 'spacker_inc_script'
                    };

                    data['mode'] = 'delete-css';
                    data['name'] = jQuery(this).attr('rel');
                    spacker_save_css(data);
                    return false;
                });

                // Loading status
                jQuery('#spacker-ajax-status').ajaxStart(function(){
                    jQuery(this).css('display', 'inline');
                });
                jQuery('#spacker-ajax-status').ajaxStop(function(){
                    jQuery(this).css('display', 'none');
                });
            });
        </script>
        <?php
        }
    }

    function admin_css() {
        if (isset($_GET['page']) && ($_GET['page'] === 'script-optimizer')) { ?>
        <style type="text/css">
            #spacker_add_js_form {
                padding: 6px;
                float: left;
                margin: 2px 0 10px;
            }

            #spacker_add_js_form label span {
                display: inline-block;
                width: 45px;
            }

            #spacker_add_js_form label input[type="text"],
			#spacker_add_js_form label select{
                width: 400px;
            }

            .spacker-add-script-button {
                float: right;
                margin: 4px 0 0;
            }

            .spacker_add_script_h {
                font-size: 1em;
                font-weight: normal;
                margin: 10px 2px 0;
            }

            .spacker-backend-title sup a {
                text-decoration: none;
            }

            #spacker-ajax-status {
                display: none;
                float: right;
                font-style: italic;
                color: #999;
				font-size: 15px;
				font-weight:normal;
            }

			.spacker-help {
				background-color: #FFFBCC;
				border-color: #E6DB55;
				border-radius: 4px 4px 4px 4px;
				border-style: solid;
				border-width: 1px;
				color: #555555;
				float: left;
				font-size: 12px;
				font-weight: bold;
				line-height: 14px;
				margin: 0 7px 7px 0;
				padding: 8px 10px;
			}
			
			.spacker-backend-title {
				position: relative;
			}
			
			.spacker-donate {
				background-color: #FFFBCC;
				border-color: #E6DB55;
				border-radius: 6px 6px 6px 6px;
				border-style: solid;
				border-width: 1px;
				color: #555555;
				float: right;
				font-size: 15px;
				font-style: normal;
				line-height: 26px;
				padding: 0 13px 7px;
				position: absolute;
				right: 0;
				top: 28px;
				z-index: 1;
			}

            .block-content ul{
                margin: 0px 0 4px 20px;
                list-style: circle;
            }
			
			#screen-meta {
				z-index: 1;
			}
        </style>
        <?php
        }
    }

    function options_join(&$options, &$merge) {
        foreach ($merge as $key => $m_val) {
            if (!is_array($m_val)){
                $options[$key] = $m_val;
            }
            else {
                nf_options_join($options[$key], $m_val);
            }
        }
    }
}
?>