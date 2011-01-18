<?php
/**
 * @package wdc_settings
 * @version 0.2
 */
if (!class_exists('wdc_settings')) {
	class wdc_settings {
		function credits(){
			$credit = get_option('wdc_credits');
			if ( $credit == 'all' || $credit === '1' || ( $credit == 'home' && is_front_page()) ) {
				echo '<p>Powered by <a href="http://www.webdesigncompany.net">Web Design Company</a> <a href="http://www.webdesigncompany.net/wordpress/plugins/">Plugins</a></p>';
			}
		}
		
		function admin_menu() {
			add_menu_page('Volcanic', 'Volcanic', 'level_10', apply_filters('wdc-settings-url', 'wdc-settings'), apply_filters('wdc-settings-page', array(&$this, 'settings')), wdc_settings::get_url() . 'wdc-icon.png'); 
			do_action('wdc-menu-pages');
			add_submenu_page(apply_filters('wdc-settings-url', 'wdc-settings'), 'Credits', 'Credits', 'level_10', 'volcanic-credits', array(&$this, 'settings')); 
		}
		
		function get_url() {
			return WP_CONTENT_URL.'/plugins/'.basename(dirname(dirname(__FILE__))) . '/' . basename(dirname(__FILE__)) . '/';
		}
		
		function settings() {
			?>
				<div class="wrap">
				<h2>Volcanic Plugin Credits</h2>
				
				<?php if ( isset($_GET['updated']) ) : ?>
					<div class='updated fade'><p>Settings saved.</p></div>
				<?php endif; ?>
				<form method="post" action="options.php">
					<input type="hidden" name="action" value="update" />
					<?php wp_nonce_field('update-options'); ?>
					<input type="hidden" name="page_options" value="wdc_credits" />
					<h3>We Need Your Help!</h3>
  				<p>
            You've decided to use our plugins. You are AWESOME!<br />
            It takes a lot of time &amp; resources to create and maintain<br />
            them so we're glad you're making good use of them.
  	      </p>
  	      <p>
  	         If you like our plugins, please show your support by <br />
  	         allowing us to add a link at the bottom of your website.<br />
  				</p>
  				
  				<h4>WDC Plugins that you're currently using:</h4>
  				<?php 
  					$plugins = apply_filters('wdc_plugins', array());
  					sort($plugins);
  					echo '<ul style="list-style: square;list-style-position:inside;"><li>';
  					echo implode('</li>', $plugins);
  					echo '</li></ul>';
  				?> 
  				
  				<h4>Would you like to support us?</h4>
					<label for="wdc_credits_all"><input type="radio" id="wdc_credits_all" name="wdc_credits" value="all" <?php if ( get_option('wdc_credits') === "all" || get_option('wdc_credits') === "1" ) echo 'checked="checked"' ?> /> Add a link short credits in footer of all pages on my site</label><br />
					<label for="wdc_credits_home"><input type="radio" id="wdc_credits_home" name="wdc_credits" value="home" <?php if ( get_option('wdc_credits') === "home" ) echo 'checked="checked"' ?> /> Add a link short credits in footer of just the home page</label><br />
					<label for="wdc_credits_none"><input type="radio" id="wdc_credits_none" name="wdc_credits" value="none" <?php if ( get_option('wdc_credits') === "none" || get_option('wdc_credits') === "0" ) echo 'checked="checked"' ?> /> I'll show my support by writing a review of the plugin when I'm ready. (Please email us at <a href="mailto:awb@webdesigncompany.net">awb@webdesigncompany.net</a> if you do write a review. We'd like to read what you write.)</label>
					
					<p>
  				  PS: If the default credit message doesn't look right in your theme, <br />
  				  <a href="mailto:mr@webdesigncompany.net?subject=Customize Credits&body=Make the credits link look good.">email us</a> and we'll make it look great at no cost to you. <br />
  				</p>
  				
  				
					<p class="submit">
						<input type="submit" name="Submit" value="Save Changes" />
					</p>

				</form>
				
				</div>
			<?php
		}
	}
	
	$wdc_settings = new wdc_settings();
	add_action('wp_footer', array(&$wdc_settings, 'credits'));
	add_action('admin_menu', array(&$wdc_settings, 'admin_menu'));
}
 
?>