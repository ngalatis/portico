<?php
/**
 * Admin settings class for Custom Login Page plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class CLP_Admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Add settings page to admin menu
     */
    public function add_settings_page() {
        add_options_page(
            __('Custom Login Page', 'custom-login-page'),
            __('Custom Login Page', 'custom-login-page'),
            'manage_options',
            'custom-login-page',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'settings_page_custom-login-page') {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        wp_enqueue_style(
            'clp-admin-css',
            CLP_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            CLP_VERSION
        );

        wp_enqueue_script(
            'clp-admin-js',
            CLP_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'wp-color-picker'),
            CLP_VERSION,
            true
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('clp_options_group', 'clp_options', array($this, 'sanitize_options'));

        // Appearance Section
        add_settings_section(
            'clp_appearance_section',
            __('Appearance', 'custom-login-page'),
            array($this, 'appearance_section_callback'),
            'custom-login-page'
        );

        add_settings_field('background_image', __('Background Image', 'custom-login-page'), array($this, 'background_image_callback'), 'custom-login-page', 'clp_appearance_section');
        add_settings_field('accent_color', __('Accent Color', 'custom-login-page'), array($this, 'accent_color_callback'), 'custom-login-page', 'clp_appearance_section');
        add_settings_field('overlay_settings', __('Overlay Settings', 'custom-login-page'), array($this, 'overlay_settings_callback'), 'custom-login-page', 'clp_appearance_section');

        // Content Section
        add_settings_section(
            'clp_content_section',
            __('Content', 'custom-login-page'),
            array($this, 'content_section_callback'),
            'custom-login-page'
        );

        add_settings_field('welcome_title', __('Welcome Title', 'custom-login-page'), array($this, 'welcome_title_callback'), 'custom-login-page', 'clp_content_section');
        add_settings_field('welcome_text', __('Welcome Text', 'custom-login-page'), array($this, 'welcome_text_callback'), 'custom-login-page', 'clp_content_section');
        add_settings_field('form_title', __('Form Title', 'custom-login-page'), array($this, 'form_title_callback'), 'custom-login-page', 'clp_content_section');
        add_settings_field('button_text', __('Button Text', 'custom-login-page'), array($this, 'button_text_callback'), 'custom-login-page', 'clp_content_section');

        // Social Media Section
        add_settings_section(
            'clp_social_section',
            __('Social Media Links', 'custom-login-page'),
            array($this, 'social_section_callback'),
            'custom-login-page'
        );

        add_settings_field('show_social_icons', __('Show Social Icons', 'custom-login-page'), array($this, 'show_social_icons_callback'), 'custom-login-page', 'clp_social_section');
        add_settings_field('facebook_url', __('Facebook URL', 'custom-login-page'), array($this, 'facebook_url_callback'), 'custom-login-page', 'clp_social_section');
        add_settings_field('twitter_url', __('Twitter/X URL', 'custom-login-page'), array($this, 'twitter_url_callback'), 'custom-login-page', 'clp_social_section');
        add_settings_field('instagram_url', __('Instagram URL', 'custom-login-page'), array($this, 'instagram_url_callback'), 'custom-login-page', 'clp_social_section');
        add_settings_field('email_address', __('Email Address', 'custom-login-page'), array($this, 'email_address_callback'), 'custom-login-page', 'clp_social_section');
    }

    /**
     * Sanitize options
     */
    public function sanitize_options($input) {
        $sanitized = array();

        $sanitized['background_image'] = esc_url_raw($input['background_image'] ?? '');
        $sanitized['accent_color'] = sanitize_hex_color($input['accent_color'] ?? '#e65c00');
        $sanitized['accent_color_hover'] = sanitize_hex_color($input['accent_color_hover'] ?? '#cc5200');
        $sanitized['welcome_title'] = wp_kses_post($input['welcome_title'] ?? '');
        $sanitized['welcome_text'] = wp_kses_post($input['welcome_text'] ?? '');
        $sanitized['form_title'] = sanitize_text_field($input['form_title'] ?? '');
        $sanitized['button_text'] = sanitize_text_field($input['button_text'] ?? '');
        $sanitized['show_social_icons'] = isset($input['show_social_icons']) ? '1' : '0';
        $sanitized['facebook_url'] = esc_url_raw($input['facebook_url'] ?? '');
        $sanitized['twitter_url'] = esc_url_raw($input['twitter_url'] ?? '');
        $sanitized['instagram_url'] = esc_url_raw($input['instagram_url'] ?? '');
        $sanitized['email_address'] = sanitize_email($input['email_address'] ?? '');
        $sanitized['overlay_opacity'] = floatval($input['overlay_opacity'] ?? 0.5);
        $sanitized['overlay_direction'] = sanitize_text_field($input['overlay_direction'] ?? 'right');

        return $sanitized;
    }

    /**
     * Section callbacks
     */
    public function appearance_section_callback() {
        echo '<p>' . __('Customize the visual appearance of your login page.', 'custom-login-page') . '</p>';
    }

    public function content_section_callback() {
        echo '<p>' . __('Customize the text content displayed on the login page.', 'custom-login-page') . '</p>';
    }

    public function social_section_callback() {
        echo '<p>' . __('Add social media links to your login page. Leave empty to hide individual icons.', 'custom-login-page') . '</p>';
    }

    /**
     * Field callbacks
     */
    public function background_image_callback() {
        $options = clp_get_options();
        ?>
        <input type="text" id="clp_background_image" name="clp_options[background_image]" value="<?php echo esc_attr($options['background_image']); ?>" class="regular-text" />
        <button type="button" id="clp_upload_button" class="button"><?php _e('Upload Image', 'custom-login-page'); ?></button>
        <button type="button" id="clp_remove_button" class="button"><?php _e('Remove', 'custom-login-page'); ?></button>
        <div id="clp_image_preview">
            <?php if (!empty($options['background_image'])): ?>
                <img src="<?php echo esc_url($options['background_image']); ?>" class="clp-preview-image">
            <?php endif; ?>
        </div>
        <?php
    }

    public function accent_color_callback() {
        $options = clp_get_options();
        ?>
        <p>
            <label><?php _e('Main Color:', 'custom-login-page'); ?></label><br>
            <input type="text" name="clp_options[accent_color]" value="<?php echo esc_attr($options['accent_color']); ?>" class="clp-color-picker" />
        </p>
        <p>
            <label><?php _e('Hover Color:', 'custom-login-page'); ?></label><br>
            <input type="text" name="clp_options[accent_color_hover]" value="<?php echo esc_attr($options['accent_color_hover']); ?>" class="clp-color-picker" />
        </p>
        <?php
    }

    public function overlay_settings_callback() {
        $options = clp_get_options();
        ?>
        <p>
            <label><?php _e('Overlay Opacity:', 'custom-login-page'); ?></label><br>
            <input type="range" id="clp_overlay_opacity" name="clp_options[overlay_opacity]" min="0" max="1" step="0.1" value="<?php echo esc_attr($options['overlay_opacity']); ?>" />
            <span id="clp_opacity_value"><?php echo esc_html($options['overlay_opacity']); ?></span>
        </p>
        <p>
            <label><?php _e('Gradient Direction:', 'custom-login-page'); ?></label><br>
            <select name="clp_options[overlay_direction]">
                <option value="right" <?php selected($options['overlay_direction'], 'right'); ?>><?php _e('Left to Right', 'custom-login-page'); ?></option>
                <option value="left" <?php selected($options['overlay_direction'], 'left'); ?>><?php _e('Right to Left', 'custom-login-page'); ?></option>
                <option value="bottom" <?php selected($options['overlay_direction'], 'bottom'); ?>><?php _e('Top to Bottom', 'custom-login-page'); ?></option>
                <option value="top" <?php selected($options['overlay_direction'], 'top'); ?>><?php _e('Bottom to Top', 'custom-login-page'); ?></option>
            </select>
        </p>
        <?php
    }

    public function welcome_title_callback() {
        $options = clp_get_options();
        ?>
        <input type="text" name="clp_options[welcome_title]" value="<?php echo esc_attr($options['welcome_title']); ?>" class="regular-text" />
        <p class="description"><?php _e('Use &lt;br&gt; for line breaks. Example: Welcome&lt;br&gt;Back', 'custom-login-page'); ?></p>
        <?php
    }

    public function welcome_text_callback() {
        $options = clp_get_options();
        ?>
        <textarea name="clp_options[welcome_text]" rows="3" class="large-text"><?php echo esc_textarea($options['welcome_text']); ?></textarea>
        <p class="description"><?php _e('HTML allowed: &lt;br&gt; for line breaks, &lt;a href="..."&gt; for links.', 'custom-login-page'); ?></p>
        <?php
    }

    public function form_title_callback() {
        $options = clp_get_options();
        ?>
        <input type="text" name="clp_options[form_title]" value="<?php echo esc_attr($options['form_title']); ?>" class="regular-text" />
        <?php
    }

    public function button_text_callback() {
        $options = clp_get_options();
        ?>
        <input type="text" name="clp_options[button_text]" value="<?php echo esc_attr($options['button_text']); ?>" class="regular-text" />
        <?php
    }

    public function show_social_icons_callback() {
        $options = clp_get_options();
        ?>
        <label>
            <input type="checkbox" name="clp_options[show_social_icons]" value="1" <?php checked($options['show_social_icons'], '1'); ?> />
            <?php _e('Display social media icons on the login page', 'custom-login-page'); ?>
        </label>
        <?php
    }

    public function facebook_url_callback() {
        $options = clp_get_options();
        ?>
        <input type="url" name="clp_options[facebook_url]" value="<?php echo esc_attr($options['facebook_url']); ?>" class="regular-text" placeholder="https://facebook.com/yourpage" />
        <?php
    }

    public function twitter_url_callback() {
        $options = clp_get_options();
        ?>
        <input type="url" name="clp_options[twitter_url]" value="<?php echo esc_attr($options['twitter_url']); ?>" class="regular-text" placeholder="https://twitter.com/yourhandle" />
        <?php
    }

    public function instagram_url_callback() {
        $options = clp_get_options();
        ?>
        <input type="url" name="clp_options[instagram_url]" value="<?php echo esc_attr($options['instagram_url']); ?>" class="regular-text" placeholder="https://instagram.com/yourhandle" />
        <?php
    }

    public function email_address_callback() {
        $options = clp_get_options();
        ?>
        <input type="email" name="clp_options[email_address]" value="<?php echo esc_attr($options['email_address']); ?>" class="regular-text" placeholder="contact@example.com" />
        <?php
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <div class="clp-preview-notice">
                <p>
                    <strong><?php _e('Preview your login page:', 'custom-login-page'); ?></strong>
                    <a href="<?php echo esc_url(wp_login_url()); ?>" target="_blank" class="button button-secondary">
                        <?php _e('Open Login Page', 'custom-login-page'); ?>
                    </a>
                </p>
            </div>

            <form method="post" action="options.php">
                <?php
                settings_fields('clp_options_group');
                do_settings_sections('custom-login-page');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
