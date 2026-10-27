<?php
/**
 * Plugin Name:       KT Portfolio
 * Plugin URI:        https://github.com/Kheang-TE/kt-portfolio
 * Description:       Portfolio plugin with Custom Post Types, Gutenberg Blocks, REST API and Shortcodes
 * Version:           0.1.0
 * Requires at least: 6.3
 * Requires PHP:      8.2
 * Author:            Kheang TE
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       kt-portfolio
 * Domain Path:       /languages
 *
 * @package KT_Portfolio
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current plugin version.
 */
define( 'KT_PORTFOLIO_VERSION', '0.1.0' );

/**
 * Plugin root directory path.
 */
define( 'KT_PORTFOLIO_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin root directory URL.
 */
define( 'KT_PORTFOLIO_URL', plugin_dir_url( __FILE__ ) );

/**
 * Autoload dependencies and plugin classes.
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Initialize the plugin.
 */
function kt_portfolio_init(): void {
	// Load text domain for translations.
	load_plugin_textdomain( 'kt-portfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	// Initialize CPT.
	\KT_Portfolio\CPT\Projet_Post_Type::register();
}

// Hook into WordPress.
add_action( 'plugins_loaded', 'kt_portfolio_init', 10 );

/**
 * Activation hook - flush rewrite rules.
 */
function kt_portfolio_activate(): void {
	// Register CPT first so rewrite rules exist.
	\KT_Portfolio\CPT\Projet_Post_Type::register_post_type();

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'kt_portfolio_activate' );

/**
 * Deactivation hook - flush rewrite rules.
 */
function kt_portfolio_deactivate(): void {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'kt_portfolio_deactivate' );
