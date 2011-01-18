<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php
        if ( is_single() ) {
			single_post_title(); echo ' | '; bloginfo( 'name' );
		} elseif ( is_home() || is_front_page() ) {
			bloginfo( 'name' ); echo ' | '; bloginfo( 'description' ); twentyten_the_page_number();
		} elseif ( is_page() ) {
			single_post_title( '' ); echo ' | '; bloginfo( 'name' );
		} elseif ( is_search() ) {
			printf( __( 'Search results for "%s"', 'twentyten' ), esc_html( $s ) ); twentyten_the_page_number(); echo ' | '; bloginfo( 'name' ); 
		} elseif ( is_404() ) {
			_e( 'Not Found', 'twentyten' ); echo ' | '; bloginfo( 'name' );
		} else {
			wp_title( '' ); echo ' | '; bloginfo( 'name' ); twentyten_the_page_number();
		}
    ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	
	<link href="http://feeds.feedburner.com/CodeBetter" title="CodeBetter.Com &raquo; Feed" type="application/rss+xml" rel="alternate">
	<?php
		wp_enqueue_style( 'mainstyle', '/wp-content/themes/codebetter/style.css');
			wp_enqueue_style( 'jquerystyle', '/wp-content/themes/codebetter/jquery-ui-1.8.1.custom.css');	
	?>

	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
	<style type="text/css">body { padding-top:0px !important; }</style>

</head>

<body>

	<?php switch_to_blog(1); ?>		
		<div class="container_12 ui-tabs ui-widget ui-widget-content ui-corner-all" id="tabs">			
			<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
				<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#ui-tabs-1">CodeBetter.Com</a></li>
				<li class="ui-state-default ui-corner-top"><a href="http://devlicio.us">Devlicio.Us</a></li>				
				<div id="top-search">
				<form action="http://codebetter.com/globalsearch/" id="cse-search-box">
  <div>
   <fieldset id="search">
		<input type="hidden" name="cx" value="005178204031477491434:2bg5jtwgsfe" />
		<input type="hidden" name="cof" value="FORID:9" />
		<input type="hidden" name="ie" value="UTF-8" />
		<input type="text"  class="text-input" id="s" name="q" size="31" />
		<input type="image" src="/wp-content/themes/codebetter/images/search-img.png" class="form-button" id="searchsubmit" value="GO" name="">				   	
	</fieldset>
  </div>
</form>
<script type="text/javascript" src="http://www.google.com/cse/brand?form=cse-search-box&lang=en&sitesearch=true"></script>
				</div>				
			</ul><div id="ui-tabs-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom"></div><div id="ui-tabs-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide"></div>
		</div>
			
		<div id="main" class="container_12">
		
			<div id="logo" class="grid_3"><a href="/" title="CodeBetter.Com - <?php bloginfo('description'); ?>"><image src="/wp-content/themes/codebetter/images/codebetter_logo.png" height="48" width="223"></image></a></div>
			<div id="ad_leaderboard" class="grid_9">			</div>
			
			<div id="globalNav" class="grid_12">
				<ul>
					<li><a href="<?php bloginfo('url'); ?>" title="home">Home</a></li>
					<?php wp_list_pages('title_li=&exclude=41'); ?>
					<li><a href="http://feeds.feedburner.com/CodeBetter" rel="alternate" type="application/rss+xml"><img src="http://www.feedburner.com/fb/images/pub/feed-icon16x16.png" alt="" style="vertical-align:middle;border:0"/></a><a href="http://feeds.feedburner.com/CodeBetter"><img src="http://feeds.feedburner.com/~fc/CodeBetter?bg=EFEFEF&amp;fg=2E9BD2&amp;anim=1" height="26" width="88" style="vertical-align:middle;border:0"/></a></li>
				</ul>
								
			</div><!-- end div#globalNav.container_12 -->				
	<?php restore_current_blog(); ?>