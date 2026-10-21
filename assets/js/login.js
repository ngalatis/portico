/**
 * Portico - Login Scripts
 */
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof porticoConfig === 'undefined') {
            return;
        }

        var loginDiv = document.getElementById('login');
        var form = document.getElementById('loginform');

        if (!loginDiv) {
            return;
        }

        // Add class to body if background image is set
        var style = getComputedStyle(document.documentElement);
        var bgImage = style.getPropertyValue('--portico-background-image').trim();
        if (bgImage && bgImage !== 'none') {
            document.body.classList.add('portico-has-bg-image');
        }

        // Build social icons HTML
        var socialHTML = '';
        if (porticoConfig.showSocial && porticoConfig.socialIcons && porticoConfig.socialIcons.length > 0) {
            socialHTML = '<div class="social-icons">';
            porticoConfig.socialIcons.forEach(function(icon) {
                socialHTML += '<a href="' + icon.url + '" target="_blank" rel="noopener" aria-label="' + icon.label + '">' + icon.icon + '</a>';
            });
            socialHTML += '</div>';
        }

        // Wrap all existing children (form, nav, backtoblog, etc.) in a wrapper div
        var wrapper = document.createElement('div');
        wrapper.className = 'login-form-wrapper';

        // Move all children except h1 (logo) into the wrapper
        var children = Array.from(loginDiv.children);
        children.forEach(function(child) {
            // Skip the h1 (WP logo) as we hide it anyway
            if (child.tagName !== 'H1') {
                wrapper.appendChild(child);
            }
        });
        loginDiv.appendChild(wrapper);

        // Add welcome section at the beginning of #login
        var welcomeHTML = '<div class="login-welcome">' +
            '<h2>' + porticoConfig.welcomeTitle + '</h2>' +
            '<p>' + porticoConfig.welcomeText + '</p>' +
            socialHTML +
            '</div>';
        loginDiv.insertAdjacentHTML('afterbegin', welcomeHTML);

        // Modify the form
        if (form) {
            // Add form title
            form.insertAdjacentHTML('afterbegin', '<h3 class="form-title">' + porticoConfig.formTitle + '</h3>');

            // Change submit button text
            var submitBtn = form.querySelector('#wp-submit');
            if (submitBtn) {
                submitBtn.value = porticoConfig.buttonText;
            }

            // Add password reset link next to remember me
            var forgetmenot = form.querySelector('.forgetmenot');
            if (forgetmenot) {
                forgetmenot.insertAdjacentHTML('afterend', '<span class="pw-reset-link"><a href="' + porticoConfig.lostPasswordUrl + '">' + porticoConfig.passwordResetText + '</a></span>');
            }
        }
    });

})();
