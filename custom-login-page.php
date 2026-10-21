<?php
/**
 * Plugin Name: Custom Login Page
 * Plugin URI: https://github.com/wefixit/custom-login-page
 * Description: Customize the WordPress login page with a modern, professional design. Includes settings for background image, colors, welcome text, and social media links.
 * Version: 1.3.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: WEFIXIT
 * Author URI: https://wefixit.gr
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: custom-login-page
 * Update URI: https://github.com/wefixit/custom-login-page
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('CLP_VERSION', '1.3.0');
define('CLP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CLP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CLP_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('CLP_GITHUB_REPO', 'https://github.com/wefixit/custom-login-page/');

/**
 * Get plugin options with defaults
 *
 * @return array Plugin options merged with defaults
 */
function clp_get_options() {
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

    $options = get_option('clp_options', array());
    return wp_parse_args($options, $defaults);
}

// Load plugin classes
require_once CLP_PLUGIN_DIR . 'includes/class-admin.php';
require_once CLP_PLUGIN_DIR . 'includes/class-login.php';

// Check GitHub releases for updates
require_once CLP_PLUGIN_DIR . 'lib/plugin-update-checker/plugin-update-checker.php';
$clp_update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    CLP_GITHUB_REPO,
    __FILE__,
    'custom-login-page'
);
$clp_update_checker->getVcsApi()->enableReleaseAssets('/custom-login-page\\.zip/');

// Initialize plugin components
add_action('plugins_loaded', function() {
    new CLP_Admin();
    new CLP_Login();
});

// Add settings link on plugins page
add_filter('plugin_action_links_' . CLP_PLUGIN_BASENAME, function($links) {
    $settings_link = '<a href="options-general.php?page=custom-login-page">' . __('Settings', 'custom-login-page') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
});
