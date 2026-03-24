<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv('DB_NAME') );

/** Database username */
define( 'DB_USER', getenv('DB_USER') );

/** Database password */
define( 'DB_PASSWORD', getenv('DB_PASSWORD') );

/** Database hostname */
define( 'DB_HOST', getenv('DB_HOST') );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '*?7JhAC@GtsYpqAgu]Swa.8y+7!D<dX,EO(-w45wXPN/H`p1WE]D6Svhy$E^2?7`');
define('SECURE_AUTH_KEY',  '{Yjr(rTo? -v:.V@|qPK>&-5QG@%GYI7nS^Ea&$!>/I(YrE VC|/KX#s.>-JBx?2');
define('LOGGED_IN_KEY',    'p[(fo9%Y&V++(wo9~>9,{V)?N<VKRNxAc MzjG9dX#~S@qy-v.%t+H)icaF#;=l~');
define('NONCE_KEY',        '#N;Tss@;C=uP[Ou+4o_2bk?IZdt+H! gLM,NjjTlOZ8>`#[eP;W|~g%{~df.NkoS');
define('AUTH_SALT',        'XgN>gz7(eVQ%$)`+b!]6C%!^J(~XZ0>; %;%fe(Cb1^U!Gk[rVKvFW@7(bH]R Ew');
define('SECURE_AUTH_SALT', 'Wg sU+]e$Fb99z(i$01VY[c4r1C7zs|kQqM`Q[-4<f- gBupJ-3T)p0rEj#E>+4c');
define('LOGGED_IN_SALT',   ',-|?zH8X:]~3V6#fAWo[aU+oW.mW8teHSbzAN/ecEmQi:#d|ziyx. Z#cHvE6y!L');
define('NONCE_SALT',       'S}D=fB#k-`I/plY?%+!_XQza$7%yOK0h!L-meIi~-[Y4o8]#Ig)lH+iY}XB/_Kt<');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
