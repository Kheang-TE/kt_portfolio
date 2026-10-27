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
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Current plugin version
 */
define('KT_PORTFOLIO_VERSION', '0.1.0');

/**
 * Activation hook - flush rewrite rules
 */
function kt_portfolio_activate(): void {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'kt_portfolio_activate');

/**
 * Deactivation hook - flush rewrite rules
 */
function kt_portfolio_deactivate(): void {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'kt_portfolio_deactivate');