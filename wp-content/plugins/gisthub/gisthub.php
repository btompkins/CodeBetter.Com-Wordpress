<?php
/*
Plugin Name: gisthub 
Plugin URI: http://wordpress.org/extend/plugins/gisthub
Description: Makes embedding Github.com gists super awesomely easy.
Usage: Drop in the embed code from github between the gist shortcode.
[gist]<script src="http://gist.github.com/447298.js?file=github_gist_wordpress_plugin_test.txt"></script>[/gist].
Version: 1.0
Author: Tyler Montgomery
Author URI: http://github.com/ubermajestix
Copyright 2010 Tyler Montgomery

Based on: 

GitHub Gist Wordpress Plugin 
http://wordpress.org/extend/plugins/github-gist
Version: 1.1 
Author: Jingwen Owen Ou 
Author URI: http://owenou.com

and

GitHub Gist Shortcode
http://www.entropytheblog.com/blog/2008/12/wordpress-github-gist-shortcode-plugin/
Version: 0.2
Author: Paul William
Author URI: http://www.entropytheblog.com/blog/

and 

http://arin.me/blog/embed-a-gist-in-your-wordpress-blog

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
  function gist($atts, $content=null) {
    //parse the id and file if you use the [gist id=0000 file=blah.rb] format
    extract(shortcode_atts(array(
      'id' => null,
      'file' => null,
    ), $atts)); 
   
    // parse id and file if you just paste the embed code between the gist shortcode
    // ex: [gist]<script src="https://gist.github.com/0000.js?file=blah.rb"></script>[/gist]
    $pattern = "/gist.github.com\/(\d+).js(\?file=(\S+))\">/";
    if ($content != null && $id == null && $file == null && preg_match($pattern, $content, $matches)) {
	    $id = $matches[1];
	  	$file = $matches[3];
	  }
    
    //silently fail if both id and file are missing
    if ($id == null && $file == null) {
      return ' id: '.$id.' file:'.$file;
    }
    
    // build the embed code
    $html =  "<script src=\"http://gist.github.com/".trim($id).".js";
    // put the file in there if needed
    if($file != null){
     $html = $html."?file=".trim($file);
    }
    // finish it off
    $html = $html."\"></script>";
    
    //fetch the json version to get the content for noscript
    $url = 'http://gist.github.com/' . trim($id) . '.json';
    if($file != null){
     $url = $url."?file=".trim($file);
    }
    
    $json  = json_decode(file_get_contents($url), true);
    $html = $html."<noscript><link rel=\"stylesheet\" href=\"".$json['stylesheet']."\">".$json['div']."</noscript>";
    
    return $html;
  }

  add_shortcode('gist', 'gist');

?>