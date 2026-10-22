# Portico – Custom Login Page

A lightweight WordPress plugin that replaces the default login screen with a modern split-screen design.

Configure it under **Settings → Portico**:

- Background image with adjustable overlay opacity and direction
- Accent and hover colors
- Welcome title/text, form title and button text
- Optional social links (Facebook, X/Twitter, Instagram, email)

Requires WordPress 5.8+ and PHP 7.4+.

Fresh installations use the bundled `assets/images/default-bg.jpg` background and the built-in English login text. Change or remove the image under **Settings → Portico**. Existing saved settings, including an empty background, take precedence over defaults.

Bundled background photograph: Sam Ferrara, Unsplash (original filename: `sam-ferrara-1527pjeb6jg-unsplash.jpg`).

## Install

With WP-CLI:

```bash
wp plugin install https://github.com/ngalatis/portico/releases/latest/download/portico.zip --activate
```

Or download `portico.zip` from the [latest release](https://github.com/ngalatis/portico/releases/latest) and upload it via **Plugins → Add New → Upload Plugin**.

> Use the `portico.zip` release asset, not GitHub's "Source code" archive — the latter unpacks into a differently named folder and won't receive updates.

## Upgrading from "Custom Login Page"

Portico was previously called Custom Login Page. On activation it imports your existing settings automatically. Deactivate and delete the old plugin first so the two don't both style the login screen:

```bash
wp plugin deactivate custom-login-page
wp plugin install https://github.com/ngalatis/portico/releases/latest/download/portico.zip --activate
wp plugin delete custom-login-page
```

## Updates

The plugin checks this repository's GitHub releases (via [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker)). New versions show up in the WordPress dashboard like any other plugin, and work with `wp plugin update portico` and WordPress auto-updates.

## Releasing a new version

1. Bump the version in **both** the `Version:` header and `PORTICO_VERSION` in `portico.php`.
2. Commit, then tag and push:
   ```bash
   git tag v1.3.1
   git push origin master --tags
   ```
3. The `Release` GitHub Action verifies the versions match the tag, builds `portico.zip` and publishes the release. Sites pick up the update within ~12 hours (or immediately via **Dashboard → Updates → Check again**).

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
