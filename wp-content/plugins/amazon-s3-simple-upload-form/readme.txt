=== Plugin Name ===

Contributors: Adam Murray
Donate link: #
Tags: S3, Amazon, upload, attachment, media, file browser, video uploader, cloudfront
Requires at least: 2.5
Tested up to: 3.0
Stable Tag: 1.1

== Description ==

A simple upload form that allows a user to upload files to a specific Amazon S3 bucket.  The user can also browse and download files within the Amazon S3 bucket.

= Partial Feature List =

* Upload files to a specfic Amazon S3 bucket
* Browse files within a specific Amazon S3 bucket
* Download files from a specific Amazon S3 bucket
* Access Amazon S3 login page

= Support =

* Submit questions via contact form (http://www.twodoorscreative.com/contactus/)
* Author is available for professional consulting to meet your configuration, troubleshooting and customization needs.
	
== Installation == 

Simple Amazon S3 Upload Form can be installed automatically via the Plugins tab in your blog administration panel.

= For manual install =

	- Download plugin and place in 'wp-content/plugins' folder.
	- Activate plugin by clicking on "Plugins" on the main administration menu, click on "Installed", find the "Simple Amazon S3 Upload Form" plugin, and 		
	  "Activate.
	  
    CONFIGURATION :

	- Click on "Settings" on the main administration menu.
	- Fill in your Amazon S3 Access Key, Secret Key (will remain hidden), and Bucket Name (ID).
	- Click "Save Changes"
	
	- Currently, Simple Amazon S3 Upload Form only supports uploads to one S3 bucket.
	
    UPLOADING :

	- Click on "Media" on the main administration menu.
	- Click on "S3 Upload Form".
	- Browse for your file, and click upload (will be adding a progress bar later). 
	- Once uploaded, you will receive a confirmation message.  If there is a problem, an error message will appear.
	
    BROWSING FILES :

	- Click on "Media" on the main administration menu.
	- Click on "S3 Bucket Contents".
	- Filenames will be evident, and simply click "Download File" to download each individual file within your bucket.
	
	- I will eventually be paginating the files.
	
== Frequently Asked Questions ==

None yet.

== Screenshots ==

None.

== Changelog ==

1.0.1 - Added file URL to "S3 Bucket Contents", so users could easily embed files into HTML.

1.0.2 - Made file navigation easier by adding Wordpress table and style. Also added file size to display.

1.1 - Made compatible with WP 3.0
