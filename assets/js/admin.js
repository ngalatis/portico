/**
 * Custom Login Page - Admin Scripts
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialize color pickers
        $('.clp-color-picker').wpColorPicker();

        // Media uploader for background image
        $('#clp_upload_button').on('click', function(e) {
            e.preventDefault();

            var mediaUploader = wp.media({
                title: 'Select Background Image',
                button: { text: 'Use this image' },
                multiple: false
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#clp_background_image').val(attachment.url);
                $('#clp_image_preview').html('<img src="' + attachment.url + '" class="clp-preview-image">');
            });

            mediaUploader.open();
        });

        // Remove background image
        $('#clp_remove_button').on('click', function(e) {
            e.preventDefault();
            $('#clp_background_image').val('');
            $('#clp_image_preview').html('');
        });

        // Update opacity value display
        $('#clp_overlay_opacity').on('input', function() {
            $('#clp_opacity_value').text($(this).val());
        });
    });

})(jQuery);
