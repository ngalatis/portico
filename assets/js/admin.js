/**
 * Portico - Admin Scripts
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialize color pickers
        $('.portico-color-picker').wpColorPicker();

        // Media uploader for background image
        $('#portico_upload_button').on('click', function(e) {
            e.preventDefault();

            var mediaUploader = wp.media({
                title: 'Select Background Image',
                button: { text: 'Use this image' },
                multiple: false
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#portico_background_image').val(attachment.url);
                $('#portico_image_preview').html('<img src="' + attachment.url + '" class="portico-preview-image">');
            });

            mediaUploader.open();
        });

        // Remove background image
        $('#portico_remove_button').on('click', function(e) {
            e.preventDefault();
            $('#portico_background_image').val('');
            $('#portico_image_preview').html('');
        });

        // Update opacity value display
        $('#portico_overlay_opacity').on('input', function() {
            $('#portico_opacity_value').text($(this).val());
        });
    });

})(jQuery);
