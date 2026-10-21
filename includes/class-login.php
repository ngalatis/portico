<?php
/**
 * Login page customization class for Portico plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Portico_Login {

    private $options;

    public function __construct() {
        add_action('login_enqueue_scripts', array($this, 'enqueue_login_styles'), 99);
        add_action('login_footer', array($this, 'enqueue_login_scripts'));
        add_filter('login_headerurl', array($this, 'custom_login_url'));
        add_filter('login_headertext', array($this, 'custom_login_title'));
        add_action('login_head', array($this, 'output_css_variables'));
    }

    /**
     * Output CSS custom properties for dynamic values
     */
    public function output_css_variables() {
        $options = portico_get_options();
        $bg_image = $options['background_image'];
        $accent = $options['accent_color'];
        $accent_hover = $options['accent_color_hover'];
        $opacity = floatval($options['overlay_opacity']);
        $direction = $options['overlay_direction'];
        ?>
        <style id="portico-css-variables">
            :root {
                --portico-accent-color: <?php echo esc_attr($accent); ?>;
                --portico-accent-hover: <?php echo esc_attr($accent_hover); ?>;
                --portico-overlay-opacity: <?php echo esc_attr($opacity); ?>;
                --portico-overlay-opacity-half: <?php echo esc_attr($opacity * 0.5); ?>;
                --portico-overlay-direction: <?php echo esc_attr($direction); ?>;
                <?php if (!empty($bg_image)): ?>
                --portico-background-image: url('<?php echo esc_url($bg_image); ?>');
                <?php else: ?>
                --portico-background-image: none;
                <?php endif; ?>
            }
        </style>
        <?php
    }

    /**
     * Enqueue login page styles
     */
    public function enqueue_login_styles() {
        $options = portico_get_options();

        wp_enqueue_style(
            'portico-login-css',
            PORTICO_PLUGIN_URL . 'assets/css/login.css',
            array('login'),
            PORTICO_VERSION
        );
    }

    /**
     * Enqueue login page scripts and output configuration
     */
    public function enqueue_login_scripts() {
        $options = portico_get_options();
        $show_social = $options['show_social_icons'] === '1';

        // Build social icons data
        $social_icons = array();

        if (!empty($options['facebook_url'])) {
            $social_icons[] = array(
                'url' => esc_url($options['facebook_url']),
                'label' => 'Facebook',
                'icon' => '<svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>'
            );
        }

        if (!empty($options['twitter_url'])) {
            $social_icons[] = array(
                'url' => esc_url($options['twitter_url']),
                'label' => 'Twitter',
                'icon' => '<svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'
            );
        }

        if (!empty($options['email_address'])) {
            $social_icons[] = array(
                'url' => 'mailto:' . esc_attr($options['email_address']),
                'label' => 'Email',
                'icon' => '<svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>'
            );
        }

        if (!empty($options['instagram_url'])) {
            $social_icons[] = array(
                'url' => esc_url($options['instagram_url']),
                'label' => 'Instagram',
                'icon' => '<svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>'
            );
        }

        // Pass configuration to JavaScript
        $config = array(
            'welcomeTitle' => wp_kses_post($options['welcome_title']),
            'welcomeText' => wp_kses_post($options['welcome_text']),
            'formTitle' => esc_html($options['form_title']),
            'buttonText' => esc_attr($options['button_text']),
            'showSocial' => $show_social,
            'socialIcons' => $social_icons,
            'lostPasswordUrl' => esc_url(wp_lostpassword_url()),
            'passwordResetText' => esc_html__('Password reset', 'portico'),
        );

        wp_enqueue_script(
            'portico-login-js',
            PORTICO_PLUGIN_URL . 'assets/js/login.js',
            array(),
            PORTICO_VERSION,
            true
        );

        wp_localize_script('portico-login-js', 'porticoConfig', $config);
    }

    /**
     * Change login logo URL
     */
    public function custom_login_url() {
        return home_url();
    }

    /**
     * Change login logo title
     */
    public function custom_login_title() {
        return get_bloginfo('name');
    }
}
