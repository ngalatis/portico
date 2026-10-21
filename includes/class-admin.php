<?php
/**
 * Admin settings class for Portico plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Portico_Admin {

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
            __('Portico – Custom Login Page', 'portico'),
            __('Portico', 'portico'),
            'manage_options',
            'portico',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'settings_page_portico') {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        wp_enqueue_style(
            'portico-admin-css',
            PORTICO_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            PORTICO_VERSION
        );

        wp_enqueue_script(
            'portico-admin-js',
            PORTICO_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'wp-color-picker'),
            PORTICO_VERSION,
            true
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('portico_options_group', 'portico_options', array($this, 'sanitize_options'));

        // Appearance Section
        add_settings_section(
            'portico_appearance_section',
            __('Appearance', 'portico'),
            array($this, 'appearance_section_callback'),
            'portico'
        );

        add_settings_field('background_image', __('Background Image', 'portico'), array($this, 'background_image_callback'), 'portico', 'portico_appearance_section');
        add_settings_field('accent_color', __('Accent Color', 'portico'), array($this, 'accent_color_callback'), 'portico', 'portico_appearance_section');
        add_settings_field('overlay_settings', __('Overlay Settings', 'portico'), array($this, 'overlay_settings_callback'), 'portico', 'portico_appearance_section');

        // Content Section
        add_settings_section(
            'portico_content_section',
            __('Content', 'portico'),
            array($this, 'content_section_callback'),
            'portico'
        );

        add_settings_field('welcome_title', __('Welcome Title', 'portico'), array($this, 'welcome_title_callback'), 'portico', 'portico_content_section');
        add_settings_field('welcome_text', __('Welcome Text', 'portico'), array($this, 'welcome_text_callback'), 'portico', 'portico_content_section');
        add_settings_field('form_title', __('Form Title', 'portico'), array($this, 'form_title_callback'), 'portico', 'portico_content_section');
        add_settings_field('button_text', __('Button Text', 'portico'), array($this, 'button_text_callback'), 'portico', 'portico_content_section');

        // Social Media Section
        add_settings_section(
            'portico_social_section',
            __('Social Media Links', 'portico'),
            array($this, 'social_section_callback'),
            'portico'
        );

        add_settings_field('show_social_icons', __('Show Social Icons', 'portico'), array($this, 'show_social_icons_callback'), 'portico', 'portico_social_section');
        add_settings_field('facebook_url', __('Facebook URL', 'portico'), array($this, 'facebook_url_callback'), 'portico', 'portico_social_section');
        add_settings_field('twitter_url', __('Twitter/X URL', 'portico'), array($this, 'twitter_url_callback'), 'portico', 'portico_social_section');
        add_settings_field('instagram_url', __('Instagram URL', 'portico'), array($this, 'instagram_url_callback'), 'portico', 'portico_social_section');
        add_settings_field('email_address', __('Email Address', 'portico'), array($this, 'email_address_callback'), 'portico', 'portico_social_section');
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
        echo '<p>' . __('Customize the visual appearance of your login page.', 'portico') . '</p>';
    }

    public function content_section_callback() {
        echo '<p>' . __('Customize the text content displayed on the login page.', 'portico') . '</p>';
    }

    public function social_section_callback() {
        echo '<p>' . __('Add social media links to your login page. Leave empty to hide individual icons.', 'portico') . '</p>';
    }

    /**
     * Field callbacks
     */
    public function background_image_callback() {
        $options = portico_get_options();
        ?>
        <input type="text" id="portico_background_image" name="portico_options[background_image]" value="<?php echo esc_attr($options['background_image']); ?>" class="regular-text" />
        <button type="button" id="portico_upload_button" class="button"><?php _e('Upload Image', 'portico'); ?></button>
        <button type="button" id="portico_remove_button" class="button"><?php _e('Remove', 'portico'); ?></button>
        <div id="portico_image_preview">
            <?php if (!empty($options['background_image'])): ?>
                <img src="<?php echo esc_url($options['background_image']); ?>" class="portico-preview-image">
            <?php endif; ?>
        </div>
        <?php
    }

    public function accent_color_callback() {
        $options = portico_get_options();
        ?>
        <p>
            <label><?php _e('Main Color:', 'portico'); ?></label><br>
            <input type="text" name="portico_options[accent_color]" value="<?php echo esc_attr($options['accent_color']); ?>" class="portico-color-picker" />
        </p>
        <p>
            <label><?php _e('Hover Color:', 'portico'); ?></label><br>
            <input type="text" name="portico_options[accent_color_hover]" value="<?php echo esc_attr($options['accent_color_hover']); ?>" class="portico-color-picker" />
        </p>
        <?php
    }

    public function overlay_settings_callback() {
        $options = portico_get_options();
        ?>
        <p>
            <label><?php _e('Overlay Opacity:', 'portico'); ?></label><br>
            <input type="range" id="portico_overlay_opacity" name="portico_options[overlay_opacity]" min="0" max="1" step="0.1" value="<?php echo esc_attr($options['overlay_opacity']); ?>" />
            <span id="portico_opacity_value"><?php echo esc_html($options['overlay_opacity']); ?></span>
        </p>
        <p>
            <label><?php _e('Gradient Direction:', 'portico'); ?></label><br>
            <select name="portico_options[overlay_direction]">
                <option value="right" <?php selected($options['overlay_direction'], 'right'); ?>><?php _e('Left to Right', 'portico'); ?></option>
                <option value="left" <?php selected($options['overlay_direction'], 'left'); ?>><?php _e('Right to Left', 'portico'); ?></option>
                <option value="bottom" <?php selected($options['overlay_direction'], 'bottom'); ?>><?php _e('Top to Bottom', 'portico'); ?></option>
                <option value="top" <?php selected($options['overlay_direction'], 'top'); ?>><?php _e('Bottom to Top', 'portico'); ?></option>
            </select>
        </p>
        <?php
    }

    public function welcome_title_callback() {
        $options = portico_get_options();
        ?>
        <input type="text" name="portico_options[welcome_title]" value="<?php echo esc_attr($options['welcome_title']); ?>" class="regular-text" />
        <p class="description"><?php _e('Use &lt;br&gt; for line breaks. Example: Welcome&lt;br&gt;Back', 'portico'); ?></p>
        <?php
    }

    public function welcome_text_callback() {
        $options = portico_get_options();
        ?>
        <textarea name="portico_options[welcome_text]" rows="3" class="large-text"><?php echo esc_textarea($options['welcome_text']); ?></textarea>
        <p class="description"><?php _e('HTML allowed: &lt;br&gt; for line breaks, &lt;a href="..."&gt; for links.', 'portico'); ?></p>
        <?php
    }

    public function form_title_callback() {
        $options = portico_get_options();
        ?>
        <input type="text" name="portico_options[form_title]" value="<?php echo esc_attr($options['form_title']); ?>" class="regular-text" />
        <?php
    }

    public function button_text_callback() {
        $options = portico_get_options();
        ?>
        <input type="text" name="portico_options[button_text]" value="<?php echo esc_attr($options['button_text']); ?>" class="regular-text" />
        <?php
    }

    public function show_social_icons_callback() {
        $options = portico_get_options();
        ?>
        <label>
            <input type="checkbox" name="portico_options[show_social_icons]" value="1" <?php checked($options['show_social_icons'], '1'); ?> />
            <?php _e('Display social media icons on the login page', 'portico'); ?>
        </label>
        <?php
    }

    public function facebook_url_callback() {
        $options = portico_get_options();
        ?>
        <input type="url" name="portico_options[facebook_url]" value="<?php echo esc_attr($options['facebook_url']); ?>" class="regular-text" placeholder="https://facebook.com/yourpage" />
        <?php
    }

    public function twitter_url_callback() {
        $options = portico_get_options();
        ?>
        <input type="url" name="portico_options[twitter_url]" value="<?php echo esc_attr($options['twitter_url']); ?>" class="regular-text" placeholder="https://twitter.com/yourhandle" />
        <?php
    }

    public function instagram_url_callback() {
        $options = portico_get_options();
        ?>
        <input type="url" name="portico_options[instagram_url]" value="<?php echo esc_attr($options['instagram_url']); ?>" class="regular-text" placeholder="https://instagram.com/yourhandle" />
        <?php
    }

    public function email_address_callback() {
        $options = portico_get_options();
        ?>
        <input type="email" name="portico_options[email_address]" value="<?php echo esc_attr($options['email_address']); ?>" class="regular-text" placeholder="contact@example.com" />
        <?php
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <div class="portico-preview-notice">
                <p>
                    <strong><?php _e('Preview your login page:', 'portico'); ?></strong>
                    <a href="<?php echo esc_url(wp_login_url()); ?>" target="_blank" class="button button-secondary">
                        <?php _e('Open Login Page', 'portico'); ?>
                    </a>
                </p>
            </div>

            <form method="post" action="options.php">
                <?php
                settings_fields('portico_options_group');
                do_settings_sections('portico');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
