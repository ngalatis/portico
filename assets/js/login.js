/**
 * Custom Login Page - Login Scripts
 */
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof clpConfig === 'undefined') {
            return;
        }

        var loginDiv = document.getElementById('login');
        var form = document.getElementById('loginform');

        if (!loginDiv) {
            return;
        }

        // Add class to body if background image is set
        var style = getComputedStyle(document.documentElement);
        var bgImage = style.getPropertyValue('--clp-background-image').trim();
        if (bgImage && bgImage !== 'none') {
            document.body.classList.add('clp-has-bg-image');
        }

        // Build social icons HTML
        var socialHTML = '';
        if (clpConfig.showSocial && clpConfig.socialIcons && clpConfig.socialIcons.length > 0) {
            socialHTML = '<div class="social-icons">';
            clpConfig.socialIcons.forEach(function(icon) {
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
            '<h2>' + clpConfig.welcomeTitle + '</h2>' +
            '<p>' + clpConfig.welcomeText + '</p>' +
            socialHTML +
            '</div>';
        loginDiv.insertAdjacentHTML('afterbegin', welcomeHTML);

        // Modify the form
        if (form) {
            // Add form title
            form.insertAdjacentHTML('afterbegin', '<h3 class="form-title">' + clpConfig.formTitle + '</h3>');

            // Change submit button text
            var submitBtn = form.querySelector('#wp-submit');
            if (submitBtn) {
                submitBtn.value = clpConfig.buttonText;
            }

            // Add password reset link next to remember me
            var forgetmenot = form.querySelector('.forgetmenot');
            if (forgetmenot) {
                forgetmenot.insertAdjacentHTML('afterend', '<span class="pw-reset-link"><a href="' + clpConfig.lostPasswordUrl + '">' + clpConfig.passwordResetText + '</a></span>');
            }
        }
    });

})();
