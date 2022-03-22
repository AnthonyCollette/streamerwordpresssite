<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'anthonyczt864');

/** MySQL database username */
define('DB_USER', 'anthonyczt864');

/** MySQL database password */
define('DB_PASSWORD', 'Ba4sM95X9fXD');

/** MySQL hostname */
define('DB_HOST', 'anthonyczt864.mysql.db:3306');

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
define('AUTH_KEY',         'qrmr2MbVHXdLLKjvTutjjd2P/tcVlo2HUkVP+DIYIwjp/z1mJ2PaJ+RnX0s9');
define('SECURE_AUTH_KEY',  'EPk4reN800uTETIQ6WiDA99q8rNlU1z1yK+iEHgn5M9Lfk5s2QfW6vIUWFWm');
define('LOGGED_IN_KEY',    '4gum8oaGFqk88fmewccqTVfONZTVgm32dQoTlaqFoO+3KBfiTxGuarQjXqeX');
define('NONCE_KEY',        'PsaFHHALJDLB17GPE7tK5ES1w4nglZF3mkC4FBOqiafFUw1r3D3LZockbxR5');
define('AUTH_SALT',        'divpJ6MGI25FsW9RoOanaElA69EMbbBMzJVQ4OKtHXuzE1+SIEkS1KsTOeSN');
define('SECURE_AUTH_SALT', 'QIkM+Gni8+/s6wSACOYIiva7WL3gaJPlcZu38HU00XywpM4f2VnnWOeJe1CO');
define('LOGGED_IN_SALT',   '7GmJxJgEIdF2Sjvb4Vo8kW503sAgBe1tIx+7iymVaWlmzMbeByeGJsATtFdC');
define('NONCE_SALT',       'Q6KG6g5b0YIAFereGm+5yiw72A/R84l0EkECwQalduulkngxTD7GPjFW4iME');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'mod571_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/* Fixes "Add media button not working", see http://www.carnfieldwebdesign.co.uk/blog/wordpress-fix-add-media-button-not-working/ */
define('CONCATENATE_SCRIPTS', false );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
