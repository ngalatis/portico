# Custom Login Page

A lightweight WordPress plugin that replaces the default login screen with a modern split-screen design.

Configure it under **Settings → Custom Login Page**:

- Background image with adjustable overlay opacity and direction
- Accent and hover colors
- Welcome title/text, form title and button text
- Optional social links (Facebook, X/Twitter, Instagram, email)

Requires WordPress 5.8+ and PHP 7.4+.

## Install

With WP-CLI:

```bash
wp plugin install https://github.com/wefixit/custom-login-page/releases/latest/download/custom-login-page.zip --activate
```

Or download `custom-login-page.zip` from the [latest release](https://github.com/wefixit/custom-login-page/releases/latest) and upload it via **Plugins → Add New → Upload Plugin**.

> Use the `custom-login-page.zip` release asset, not GitHub's "Source code" archive — the latter unpacks into a differently named folder and won't receive updates.

## Updates

The plugin checks this repository's GitHub releases (via [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker)). New versions show up in the WordPress dashboard like any other plugin, and work with `wp plugin update custom-login-page` and WordPress auto-updates.

## Releasing a new version

1. Bump the version in **both** the `Version:` header and `CLP_VERSION` in `custom-login-page.php`.
2. Commit, then tag and push:
   ```bash
   git tag v1.3.1
   git push origin master --tags
   ```
3. The `Release` GitHub Action verifies the versions match the tag, builds `custom-login-page.zip` and publishes the release. Sites pick up the update within ~12 hours (or immediately via **Dashboard → Updates → Check again**).

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
