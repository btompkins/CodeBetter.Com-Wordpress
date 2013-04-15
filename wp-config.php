<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */


// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_codebetter');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'bupfre26');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '^dpzej{4$Xew6(gNcn,hWTbTIC6%*@wp@FF9JJ)Lh$#||hxx:z~R.IGh|NYDZWGh');
define('SECURE_AUTH_KEY',  'X(*M`;e~}28*FG#U/QYGU/nKt~0Gc!~U|AU<OOWckCi9Q(d}Z^~gLCp6M :CR(h%');
define('LOGGED_IN_KEY',    '](7@ci}`kO@+=xE_CiIFdtx-:_$?:18sT1njds;kQ(0V(yc*>N*$#K}`dfPEdo8k');
define('NONCE_KEY',        'I8PmtqupyG?7C-_+`cU]PB8PX/EGzk?IFN<$hlYM,?ea-wT;sh|p^^+i?u8fZa>s');
define('AUTH_SALT',        '_rbg70ZXzRzoUvG1563@#)$A$$`+cZX+QPQ[;o@rw0The=7.#pf44#bIM+Lt3uoK');
define('SECURE_AUTH_SALT', ',2[e6iTu$;1>,xnhJIe;QtJN{CB|g 2KqVG% #SE7rP~Ru7*y&$inB3;Y 70allr');
define('LOGGED_IN_SALT',   'BgBOyxBUFjn+BefGb||44gUbc?%9L:i|`T=B~hZLC`6BkEUp5L(!M&*aWU~On Yg');
define('NONCE_SALT',       '?9cNDI9<[UclJh4Y{, ,Jq@H1$MI=VXKB;sGB4,-Do|{[*<%B@aGQcDH1MRNyYV+');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

define( 'MULTISITE', true );
define( 'VHOST', 'no' );
$base = '/';
define( 'DOMAIN_CURRENT_SITE', 'codebetter.com' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('WP_ALLOW_MULTISITE', true);
define('WP_ALLOW_REPAIR', true);
