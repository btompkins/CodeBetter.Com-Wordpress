</div><!-- end div tabs see header.php -->
<?php switch_to_blog(1); ?>	
	<div id="footer">
		<div class="container_12">
		<div id="footer-widget-area">
				<div class="grid_4">					
					<ul>
					<li><a href="<?php bloginfo('url'); ?>" title="home">Home</a></li>
					<?php wp_list_pages('title_li='); ?>
					<li><a href="http://feeds.feedburner.com/CodeBetter" rel="alternate" type="application/rss+xml"><img src="http://www.feedburner.com/fb/images/pub/feed-icon16x16.png" alt="" style="vertical-align:middle;border:0"/></a><a href="http://feeds.feedburner.com/CodeBetter"><img src="http://feeds.feedburner.com/~fc/CodeBetter?bg=FFFFFF&amp;fg=2E9BD2&amp;anim=1" height="26" width="88" style="vertical-align:middle;border:0"/></a></li>
					</ul>
				</div><!-- #second .widget-area -->
				<div class="grid_4">
					
					<h3>Friends of CodeBetter.Com</h3>
<ul>

<li><a href="/blogs/products/pages/64386.aspx">Red-Gate Tools For SQL and .NET</a></li>
<li><a href="/blogs/products/pages/64615.aspx">Telerik</a></li>
<li><a href="/blogs/products/pages/142174.aspx">JetBrains - ReSharper</a></li>
<li><a href="/blogs/products/pages/Beyond-Compare.aspx">Beyond Compare</a></li>
<li><a href="/blogs/products/pages/.NET-Memory-Profiler.aspx">.NET Memory Profiler</a></li>
<li><a href="http://www.ndepend.com/">NDepend</a></li>
<li><a href="http://www.sapphiresteel.com/">Ruby In Steel</a></li>
<li><a href="http://www.slickedit.com/">SlickEdit</a></li>
<li><a href="http://www.gurock.com/products/smartinspect/">SmartInspect .NET Logging</a></li>
<li>NGEDIT: <a href="http://www.viemu.com/">ViEmu</a> and <a href="http://www.codekana.com/">Codekana</a> </li>
<li><a href="http://www.devexpress.com/" target="_blank">DevExpress</a></li>
<li><a href="http://nhprof.com" target="_blank">NHibernate Profiler</a></li>
<li><a href="http://unfuddle.com" target="_blank">Unfuddle</a></li>
<li><a href="http://www.balsamiq.com/products/mockups" target="_blank">Balsamiq Mockups</a></li>
<li><a href="http://scrumy.com" target="_blank">Scrumy</a> &lt;-- NEW Friend!</li>

	</ul>				
				</div><!-- #third .widget-area -->
				<div class="grid_4">
					<?php bloginfo('name'); ?> &copy; '<?php echo date('y'); ?><br />
						<?php bloginfo('description'); ?><br />
				<?php printf( __( 'Proudly powered by <span id="generator-link">%s</span>.', 'twentyten' ), '<a href="http://wordpress.org/" title="' . esc_attr__( 'Semantic Personal Publishing Platform', 'twentyten' ) . '" rel="generator">' . __( 'WordPress', 'twentyten' ) . '</a>' ); ?>
					
				</div><!-- #fourth .widget-area -->
			</div><!-- #footer-widget-area -->		
		
			</div><!-- end div.container_12 -->

	</div><!-- end div#footer -->
		<?php restore_current_blog(); ?>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-531207-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script type='text/javascript' src='http://cdn1.codebetter.com/wp-content/plugins/syntaxhighlighter/syntaxhighlighter3/scripts/shCore.js?ver=3.0.83b'></script>
<script type='text/javascript' src='http://cdn1.codebetter.com/wp-content/plugins/syntaxhighlighter/syntaxhighlighter3/scripts/shBrushCSharp.js?ver=3.0.83b'></script>
<script type='text/javascript' src='http://cdn1.codebetter.com/wp-content/plugins/syntaxhighlighter/syntaxhighlighter3/scripts/shBrushRuby.js?ver=3.0.83b'></script>
<script type='text/javascript' src='http://cdn1.codebetter.com/wp-content/plugins/syntaxhighlighter/syntaxhighlighter3/scripts/shBrushJScript.js?ver=3.0.83b'></script>
<script type='text/javascript' src='http://cdn1.codebetter.com/wp-content/plugins/syntaxhighlighter/syntaxhighlighter3/scripts/shBrushXml.js?ver=3.0.83b'></script>
<script type='text/javascript' src='http://cdn1.codebetter.com/wp-content/plugins/syntaxhighlighter/third-party-brushes/shBrushFSharp.js?ver=3.0.83b'></script>
<script type='text/javascript'>
	(function(){
		var corecss = document.createElement('link');
		var themecss = document.createElement('link');
		var corecssurl = "http://cdn1.codebetter.com/wp-content/plugins/syntaxhighlighter/syntaxhighlighter3/styles/shCore.css?ver=3.0.83b";
		if ( corecss.setAttribute ) {
				corecss.setAttribute( "rel", "stylesheet" );
				corecss.setAttribute( "type", "text/css" );
				corecss.setAttribute( "href", corecssurl );
		} else {
				corecss.rel = "stylesheet";
				corecss.href = corecssurl;
		}
		document.getElementsByTagName("head")[0].insertBefore( corecss, document.getElementById("syntaxhighlighteranchor") );
		var themecssurl = "http://cdn1.codebetter.com/wp-content/plugins/syntaxhighlighter/syntaxhighlighter3/styles/shThemeDefault.css?ver=3.0.83b";
		if ( themecss.setAttribute ) {
				themecss.setAttribute( "rel", "stylesheet" );
				themecss.setAttribute( "type", "text/css" );
				themecss.setAttribute( "href", themecssurl );
		} else {
				themecss.rel = "stylesheet";
				themecss.href = themecssurl;
		}
		//document.getElementById("syntaxhighlighteranchor").appendChild(themecss);
		document.getElementsByTagName("head")[0].insertBefore( themecss, document.getElementById("syntaxhighlighteranchor") );
	})();
	SyntaxHighlighter.config.strings.expandSource = '+ expand source';
	SyntaxHighlighter.config.strings.help = '?';
	SyntaxHighlighter.config.strings.alert = 'SyntaxHighlighter\n\n';
	SyntaxHighlighter.config.strings.noBrush = 'Can\'t find brush for: ';
	SyntaxHighlighter.config.strings.brushNotHtmlScript = 'Brush wasn\'t configured for html-script option: ';
	SyntaxHighlighter.defaults['gutter'] = false;
    SyntaxHighlighter.defaults['tab-size'] = 2;
    SyntaxHighlighter.defaults['toolbar'] = false;
    SyntaxHighlighter.defaults['wrap-lines'] = false; 
	SyntaxHighlighter.defaults['pad-line-numbers'] = false;
	SyntaxHighlighter.all();
</script>
 
</body>
</html>