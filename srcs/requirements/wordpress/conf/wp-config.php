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
define('AUTH_KEY',         '~nO`z+ouiX|Gn_9w?+a(0-u!x7e*Q4SL6.!cvUwI^DPMx`9oY-_5mW;^qCa/|+bS');
define('SECURE_AUTH_KEY',  'INjM[0]L}dB.zsf+;J1]O*%|uT^-$B?18V-62az<g|j)[0,N+kNf=f-- WC4:Z+{');
define('LOGGED_IN_KEY',    '7PPnd(zLOR-n8=-AEto?K)@,Y)s}*G@[_|K~MosHz_c2*7+5aQNZf23|-{$9-<VL');
define('NONCE_KEY',        'im-=YyJ+0v(4Il,qr!8b`#sR.wpt.B/mt_jWNxE1+ZM+_;h^o aiM`[1v~Aj%S[g');
define('AUTH_SALT',        '75$C*OkKO[ZHMt@H& 6o/%<Oc{M^Rgxj4Zo2J13gIBz;iF^y.lffG1}#POkZw@Hc');
define('SECURE_AUTH_SALT', '-sh.bE|!H)52?%|N?@pHUw+7)?-ILy}QhYJyHcWd[H*^XkKS[TP!WR[`ugSe7H5H');
define('LOGGED_IN_SALT',   'YJ8{}Gk8a6}TWXIdmOFU;lwk>) a_Ud45]{6={]p>`~?i5_)P:FMi7|bbYn<,N4/');
define('NONCE_SALT',       '*;8bHeGOhF+C4(bK.<-o0Wa9.,,NjyeX*Km7J^<8(<eNz~P-o?eQD)Rf`ji=Xhgo');

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
