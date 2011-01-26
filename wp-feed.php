<?php
/**
 * Custom feed replacement
 */

require( './wp-load.php' );
header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php do_action('rss2_ns'); ?>
>

<channel>
	<title><?php bloginfo_rss('name'); wp_title_rss(); ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss("description") ?></description>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<language><?php echo get_option('rss_language'); ?></language>
	<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
	<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
	<?php do_action('rss2_head'); ?>
	
	
	<?php
	global $query_string;
	
    $blog_list = $wpdb->get_results( "SELECT blog_id, last_updated FROM " . $wpdb->blogs. " WHERE public = '1' ", ARRAY_A );	
	$querystr = "";
	 
	$num_posts = get_option('posts_per_rss');
	 
	foreach($blog_list as $blog)
	{
		$blogPrefix = $wpdb->get_blog_prefix($blog["blog_id"]);
		$querystr .= $querystr == "" ? "" : " UNION ALL ";
		$querystr .= "
			SELECT *, '".$blog["blog_id"]."' as blog_id
			FROM  ".$blogPrefix."posts 
			WHERE post_status = 'publish' 
			AND post_type = 'post' ";
	}				
	$querystr .= " ORDER BY post_date DESC LIMIT ".$num_posts;	
	$wp_query->request = $querystr;
	$pageposts = $wpdb->get_results($wp_query->request, OBJECT);	
	$use_excerpt = False;
 
 ?>
 <?php if ($pageposts): ?>
  <?php foreach ($pageposts as $post): ?>
  <?php switch_to_blog($post->blog_id); ?>
  
    <?php setup_postdata($post); ?>
	<item>
		<title><?php the_title_rss() ?></title>
		<link><?php the_permalink_rss() ?></link>
		<comments><?php comments_link(); ?></comments>
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
		<dc:creator><?php the_author() ?></dc:creator>
		<?php the_category_rss() ?>
			<guid isPermaLink="false"><?php the_guid(); ?></guid>
		<?php if ($use_excerpt) : ?>		
			<description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
		<?php else : ?>
			<description><![CDATA[<?php the_content_feed('rss2') ?>]]></description>
			
		<?php endif; ?>
		<wfw:commentRss><?php echo get_post_comments_feed_link(null, 'rss2'); ?></wfw:commentRss>
		<?php rss_enclosure(); ?>
		<?php do_action('rss2_item'); ?>
	</item>
	<?php restore_current_blog(); ?>
  <?php endforeach; ?>
<?php endif; ?>
</channel>
</rss>
