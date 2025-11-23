<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themeforest.net/user/nouthemes/portfolio
 * @since             1.0.0
 * @package           Leopard_Wordpress_Offload_Media
 *
 * @wordpress-plugin
 * Plugin Name:       Leopard - WordPress offload media
 * Plugin URI:        https://themeforest.net/user/nouthemes/portfolio
 * Description:       Leopard – WordPress offload media copies files from your WordPress Media Library to Amazon S3, Wasabi, Google cloud storage, DigitalOcean Spaces and rewrites URLs to server the files from that same storage provider, or from the CDN of your choice (CloudFront).
 * Version:           2.0.32.2
 * Author:            Nouthemes
 * Author URI:        https://themeforest.net/user/nouthemes/portfolio
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       leopard-wordpress-offload-media
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LEOPARD_WORDPRESS_OFFLOAD_MEDIA_VERSION', '2.0.32.2' );
define( "LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PLUGIN_DIR", plugin_dir_path(__FILE__) );
define( "LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PLUGIN_URI", plugin_dir_url(__FILE__) );
define( "LEOPARD_WORDPRESS_OFFLOAD_MEDIA_DEFAULT_EXPIRES", 900 );
define( "LEOPARD_WORDPRESS_OFFLOAD_MEDIA_DIR_FILE", __FILE__ );
define( "LEOPARD_WORDPRESS_OFFLOAD_MEDIA_MINIMUM_PHP_VERSION", '7.0' );
//raz0r
update_option('nou_leopard_offload_media_license_active', 1);
update_option('nou_leopard_offload_media_license_key', '*************');
update_option('nou_leopard_offload_media_license_email', 'email@mail.com');
if ( ! defined( 'FS_CHMOD_FILE' ) ) {
	define( 'FS_CHMOD_FILE', ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
}

define( 
	"LEOPARD_WORDPRESS_OFFLOAD_MEDIA_CORS_AllOWED_METHODS", 
	array(
		'GET', 
		'HEAD',
		'PUT',
		'POST',
		'DELETE'
	) 
);

define( 
	"LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PROVIDER", 
	array(
		'aws' => esc_html__('Amazon S3', 'leopard-wordpress-offload-media'), 
		'wasabi' => esc_html__('Wasabi', 'leopard-wordpress-offload-media'),
		'google' => esc_html__('Google Cloud Storage', 'leopard-wordpress-offload-media'),
		'DO' => esc_html__('DigitalOcean Spaces', 'leopard-wordpress-offload-media'),
		'bunnycdn' => esc_html__('Bunny CDN', 'leopard-wordpress-offload-media'),
		'cloudflare-r2' => esc_html__('Cloudflare R2', 'leopard-wordpress-offload-media')
	) 
);

define( 
	"LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PROVIDER_SYNC", 
	LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PROVIDER 
);

define( 
	"LEOPARD_WORDPRESS_OFFLOAD_MEDIA_DO_REGIONS", 
	array(
        'nyc3' => 'New York City, United States',
        'sfo2' => 'San Francisco, United States',
        'sgp1' => 'Singapore',
        'fra1' => 'Frankfurt, Germany',
        'ams3' => 'Amsterdam',
		'sfo3' => 'San Francisco 3, United States',
	) 
);

$upload_dir = wp_upload_dir();

define( 
	"LEOPARD_WORDPRESS_OFFLOAD_MEDIA_CACHE_PATH", 
	$upload_dir['basedir'] . '/leopard-wordpress-offload' 
);

define( 
	"LEOPARD_WORDPRESS_OFFLOAD_MEDIA_CACHE_KEY_ATTACHED_FILE", 
	'leopard_posturl_'
);

define( 
	"LEOPARD_WORDPRESS_OFFLOAD_MEDIA_CACHE_TIMEOUT_ATTACHED_FILE", 
	60
);

define( 
	"LEOPARD_WORDPRESS_OFFLOAD_MEDIA_ITEMS_TABLE", 
	'leopard_items'
);

require_once( LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PLUGIN_DIR . 'libraries/action-scheduler/action-scheduler.php' );
require_once( LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PLUGIN_DIR . 'includes/class-leopard-wordpress-offload-media-licenser.php' );

function nou_leopard_offload_media_increase_time_limit( $time_limit ) {
	return 120;
}
add_filter( 'action_scheduler_queue_runner_time_limit', 'nou_leopard_offload_media_increase_time_limit' );

function nou_leopard_offload_media_increase_action_scheduler_batch_size( $batch_size ) {
	return 50;
}
add_filter( 'action_scheduler_queue_runner_batch_size', 'nou_leopard_offload_media_increase_action_scheduler_batch_size' );

function nou_leopard_offload_media_increase_action_scheduler_concurrent_batches( $concurrent_batches ) {
	return 2;
}
add_filter( 'action_scheduler_queue_runner_concurrent_batches', 'nou_leopard_offload_media_increase_action_scheduler_concurrent_batches' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-leopard-wordpress-offload-media-activator.php
 */
function activate_leopard_wordpress_offload_media() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-leopard-wordpress-offload-media-activator.php';
	Leopard_Wordpress_Offload_Media_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-leopard-wordpress-offload-media-deactivator.php
 */
function deactivate_leopard_wordpress_offload_media() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-leopard-wordpress-offload-media-deactivator.php';
	Leopard_Wordpress_Offload_Media_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_leopard_wordpress_offload_media' );
register_deactivation_hook( __FILE__, 'deactivate_leopard_wordpress_offload_media' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';
require plugin_dir_path( __FILE__ ) . 'functions/cache.php';

$GLOBALS['LeopardInstanceCache'] = nou_leopard_offload_media_instance_cache();

require plugin_dir_path( __FILE__ ) . 'functions/global.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-leopard-wordpress-offload-media.php';

/**
 * Transform full width hyphens and other variety hyphens in half size into simple hyphen,
 * and avoid consecutive hyphens and also at the beginning and end as well.
 */
function nou_leopard_offload_media_format_hyphens( $str ) {
	$hyphen = '-';
	$hyphens = [
		'﹣', '－', '−', '⁻', '₋',
		'‐', '‑', '‒', '–', '—',
		'―', '﹘', 'ー','ｰ',
	];
	$str = str_replace( $hyphens, $hyphen, $str );
	// remove at the beginning and end.
	$beginning = mb_substr( $str, 0, 1 );
	if ( $beginning === $hyphen ) {
		$str = mb_substr( $str, 1 );
	}
	$end = mb_substr( $str, -1 );
	if ( $end === $hyphen ) {
		$str = mb_strcut( $str, 0, mb_strlen( $str ) - 1 );
	}
	$str = preg_replace( '/-{2,}/u', '-', $str );
	$str = trim( $str, implode( '', $hyphens ) );
	return $str;
}
/**
 * Filter {@see sanitize_file_name()} and return an unique file name.
 *
 * @param  string $filename
 * @return string
 */

function nou_leopard_offload_media_change_file_name( $file ) {
	$newName = nou_leopard_offload_media_modify_uploaded_file_names($file['name']);
	$file['name'] = $newName;
	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'nou_leopard_offload_media_change_file_name' );

function nou_leopard_offload_media_modify_uploaded_file_names( $filename ) {
	
    $info = pathinfo( $filename );
    $ext  = empty( $info['extension'] ) ? '' : '.' . $info['extension'];
	if(empty($ext)){
		return $filename;
	}

	$name = basename( $filename, $ext );
	$name = remove_accents($name);
	
	// Related to English
	$name = str_replace( "'s", "", $name );
	$name = str_replace( "n\'t", "nt", $name );
	$name = preg_replace( "/\'m/i", "-am", $name );

	// We probably do not want those neither
	$name = str_replace( "'", "-", $name );
	$name = preg_replace( "/\//s", "-", $name );
	$name = str_replace( ['.','…'], "", $name );
	$name = str_replace(' ', '-', $name);
	$name = preg_replace('/[^A-Za-z0-9\-]/', "-", $name);
	$name = nou_leopard_offload_media_format_hyphens($name);
	
	if($name === '-'){
		$name = 'leopard-wom-' . time() . '-' . mt_rand();
	}

	$object_versioning = get_option('nou_leopard_offload_media_object_versioning');
	if(empty($object_versioning)){
		return $name . $ext;
	}
	
	return $name. ('-'.time().'-'.mt_rand()) . $ext;
}

add_filter( 'sanitize_file_name', 'nou_leopard_offload_media_modify_uploaded_file_names', 10 );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_leopard_wordpress_offload_media() {

	$plugin = new Leopard_Wordpress_Offload_Media();
	$plugin->run();

}
run_leopard_wordpress_offload_media();