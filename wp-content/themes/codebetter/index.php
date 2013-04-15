<?php 

$last_updated = $wpdb->get_var( "SELECT last_updated FROM " . $wpdb->blogs. " WHERE public = '1' order by
 `last_updated` desc limit 1");
	
	if(isset($last_updated)){
        $post_mod_date=date("D, d M Y H:i:s",strtotime($last_updated));
        header('Last-Modified: '.$post_mod_date.' GMT');
    }

get_header();?>

	<div id="content">
		<div id="main" class="grid_8">
		<?php if(function_exists('wp_greet_box')){wp_greet_box();} ?>
		<?php get_template_part( 'loop', 'index' ); ?>
		</div><!-- #main -->
		<div id="sidebar" class="grid_4">
			<?php get_sidebar(); ?>
		</div><!--sidebar-->
	</div><!-- #content -->	
<?php get_footer(); ?>