<?php
/**
 * Plugin Name: Portico – Custom Login Page
 * Plugin URI: https://github.com/wefixit/portico
 * Description: Customize the WordPress login page with a modern, professional design. Includes settings for background image, colors, welcome text, and social media links.
 * Version: 1.3.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: WEFIXIT
 * Author URI: https://wefixit.gr
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: portico
 * Update URI: https://github.com/wefixit/portico
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('PORTICO_VERSION', '1.3.0');
define('PORTICO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PORTICO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PORTICO_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('PORTICO_GITHUB_REPO', 'https://github.com/wefixit/portico/');

/**
 * Get plugin options with defaults
 *
 * @return array Plugin options merged with defaults
 */
function portico_get_options() {
    $defaults = array(
        'background_image' => '',
        'accent_color' => '#e65c00',
        'accent_color_hover' => '#cc5200',
        'welcome_title' => 'Welcome<br>Back',
        'welcome_text' => 'Enter your credentials to access your account and manage your website.',
        'form_title' => 'Sign in',
        'button_text' => 'Sign in now',
        'show_social_icons' => '1',
        'facebook_url' => '',
        'twitter_url' => '',
        'instagram_url' => '',
        'email_address' => '',
        'overlay_opacity' => '0.5',
        'overlay_direction' => 'right',
    );

    $options = get_option('portico_options', array());
    return wp_parse_args($options, $defaults);
}

/**
 * Import settings from the plugin's former name (Custom Login Page) on activation
 */
register_activation_hook(__FILE__, function() {
    $legacy = get_option('clp_options');
    if (false === get_option('portico_options') && is_array($legacy)) {
        add_option('portico_options', $legacy);
    }
});

// Load plugin classes
require_once PORTICO_PLUGIN_DIR . 'includes/class-admin.php';
require_once PORTICO_PLUGIN_DIR . 'includes/class-login.php';

// Check GitHub releases for updates
require_once PORTICO_PLUGIN_DIR . 'lib/plugin-update-checker/plugin-update-checker.php';
$portico_update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    PORTICO_GITHUB_REPO,
    __FILE__,
    'portico'
);
$portico_update_checker->getVcsApi()->enableReleaseAssets('/portico\\.zip/');

// Initialize plugin components
add_action('plugins_loaded', function() {
    new Portico_Admin();
    new Portico_Login();
});

// Add settings link on plugins page
add_filter('plugin_action_links_' . PORTICO_PLUGIN_BASENAME, function($links) {
    $settings_link = '<a href="options-general.php?page=portico">' . __('Settings', 'portico') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
});
