<?php
/*
Plugin Name: Simple Amazon S3 Upload Form
Plugin URI: http://www.twodoorscreative.com/wordpress-plugin-simple-amazon-s3-upload-form/
Description: Simple form that allows users to upload files to a specific Amazon S3 bucket.
Author: Adam Murray
Author URI: http://twodoorscreative.com
Version:1.0.3
*/

/*  Copyright 2009  Adam Murray  (email : adam@twodoorscreative.com)

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
	// Add Admin Menu

add_action('admin_menu', 's3admin_menu');


function s3admin_menu()
	{
	//Add upload form Submenu
	add_media_page( 'S3 Upload Form', 'S3 Upload Form', 'administrator', 's3_uploader', 's3form_content');
	add_media_page(' S3 Bucket Contents' , 'S3 Bucket Contents' , 'administrator' , 's3uploader', 's3bucket_content');
	
	//Add settings submenu
	add_options_page( 'Amazon S3 Video Upload Settings', 'S3 Upload Settings', 'manage_options', 'S3-Upload-Settings', 's3settings');
	}

	function s3form_content()
		{
		include('s3form.php');
		}
		
	function s3bucket_content()
		{
		include('s3contents.php');
		}
		
	function s3settings()
		{
		include('s3settings.php');	
		}
		
	
		
//Settings API

add_action('admin_init', 'plugin_admin_init');

function plugin_admin_init()
	{
	register_setting( 's3upload_settings', 's3key');
	register_setting( 's3upload_settings', 's3key_secret');
	register_setting( 's3upload_settings', 's3bucketID');
	}
	
?>