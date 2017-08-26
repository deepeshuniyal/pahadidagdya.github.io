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
define('DB_NAME', 'pddb');

/** MySQL database username */
define('DB_USER', 'gmst');

/** MySQL database password */
define('DB_PASSWORD', 'gm123#123');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** FTP Settings */
define("FTP_HOST", "35.154.150.23");
define("FTP_USER", "ftpuser");
##Deny from all
define("FTP_PASS", "bagoli@123");

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'J8`P[ZC^T)W|nDq#8Ylp4t]^R5Okz3,?3BraHQfq<|H%|Ns2i^6b|mtvLs;4]1[5');
define('SECURE_AUTH_KEY',  'nK!d!1J-Z]ZegkGxwgPsk^0&dna_tjglkV<|y]Y5Vr~Mr|Y_x%cUr$ r#Lj>DJXl');
define('LOGGED_IN_KEY',    'i}#LOp<lcBEtLXH?H3PqV3?YpWXt^J3k)2=IA#|a1s5Of_Cn]O7KOVFR%ca (EeK');
define('NONCE_KEY',        'k*JMU2+s@Z@r;F}YJA6QLcG_y[:Y7<pKJhE<6cv9AFCl8_U8<GRP.g#cYxotCP1N');
define('AUTH_SALT',        '=lXNY(m23cU-n]&_E^HX2A0fpf7:m^,xE~OXiY$~lgC!<}$jUQfXG||7_?^![9sE');
define('SECURE_AUTH_SALT', 'ebGJLg~%,C4/{p8ZX6B<_WP&V(j<WP!G$WQlfu2]x<EB)@;6y{^e0?@?X4/.`E3p');
define('LOGGED_IN_SALT',   '>lWwLJGd:/89#BsiLI=QDF/h&1)l^pQ6K{wa.qVxIQ0j^a9l@<SsU;<./CCvi{x`');
define('NONCE_SALT',       ')AngtSN, tiXm&NdpW2d?W1k{>Pr9Oa6Im]wJ,gUEOU#9J9UrJ(:B_2VotD_`gr9');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define( 'WPCF7_VALIDATE_CONFIGURATION', false );



define('WP_MEMORY_LIMIT', '64M');
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
