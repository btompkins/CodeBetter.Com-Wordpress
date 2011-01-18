=== JS & CSS Script Optimizer ===
Contributors: evgenniy
Donate link: http://4coder.info/en/
Tags: performance, javascript, css, script, js, compress, pack, combine, optimization
Requires at least: 2.8
Tested up to: 3.1
Stable tag: trunk

This plugin will help you make your Website more quick. It can grouping and packing Java Scripts and CSS. Also it makes fewer HTTP requests.

== Description ==
= Features =
- Combine several scripts into the single file (to minimize http requests)
- Pack scripts using Dean Edwards's JavaScript Packer
- You can move all JavaScripts to the bottom
- Combine all CSS scripts into the single files (with grouping by "media")
- Pack CSS files (remove comments, tabs, spaces, newlines)
- Ability to include JavaScript and CSS files (new)
- If any script fails and shows error you can add it to exclude list

= Recommendations =
- This Plugin processes only those scripts that are included properly (using "wp_enqueue_script" or "wp_enqueue_style" function)
- Uploads directory should be writable
- Read <a title="Permanent Link to How to properly add CSS in WordPress" rel="bookmark" href="http://4coder.info/en/how-to-properly-add-css-in-wordpress/">How to properly add CSS in WordPress</a>

For more info visit <a title="This WordPress plugin home page" href="http://4coder.info/en/wordpress-js-css-optimizer/">http://4coder.info/en/wordpress-js-css-optimizer/</a>.

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Plugin settings page
1. Ability to include JavaScript files
1. Ability to include CSS files

== Changelog ==
= 0.1.7 =
* Bug with options saving
= 0.1.4 =
* Added helpful information
* Some bugs are fixed
= 0.1.3 =
* CSS grouping has been updated
= 0.1.2 =
* Ability to include JavaScript and CSS scripts has been added
= 0.1.0 =
* Release version!
= 0.0.8 =
* Bug with WYSIWYG is fixed
= 0.0.7 =
* Bug with URLs in CSS is fixed
* Settings page is more useful
* And Some other bugs are fixed
= 0.0.5 =
* Added exclude scripts list
* Modified "Combine JavaScripts" options
* Grouping CSS by "media" (screen, print e.t.c.)
= 0.0.4 =
* Some bugs are fixed
= 0.0.2 =
* Beta version of the JS & CSS Script Optimizer