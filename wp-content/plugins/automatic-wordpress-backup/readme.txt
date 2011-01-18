=== Automatic WordPress Backup ===
Contributors: dancoulter
Tags: backup automatic s3 zip backups scheduled
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: 2.0.3

Automatically back up important bits of your WordPress install to Amazon S3.

== Description ==

Using this plugin, you can easily and automatically backup important parts of
your WordPress install to Amazon S3.  Amazon S3 is an extremely cheap service
that is easy to set up.  For pennies a month, you can make sure that your
important files will be kept safe.

Important caveat: this plugin currently has to be run on a linux server. 
Also, the wp-content/uploads folder has to be server-writable or it won't be
able to create the zips for backup.

For full info and installation instructions, visit http://www.webdesigncompany.net/automatic-wordpress-backup/

== Installation ==

Before you can use this plugin, you need to be sure to sign up for an Amazon
S3 account. Once you get your access key and secret key, you can paste them
into your plugin's settings page. From there, the setup should be pretty
simple.
