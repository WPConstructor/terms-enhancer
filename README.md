<h1 align="center">WPConstructor Terms Enhancer</h1>

<p align="center"><img src="https://wpconstructor.com/assets/images/wpconstructor-terms-enhancer-logo.png" alt="Terms Enhancer Logo" width="400"></p>

Enhance the WordPress `core/post-terms` block with:

- ✅ Display term usage counts
- ✅ Disable links for terms with only one post
- ✅ Automatically remove `href` for single-use terms
- ✅ Gutenberg editor integration
- ✅ Lightweight and dependency-free frontend

---

## Features

### Display Term Counts

If enabled, the number of posts assigned to each term will be automatically displayed.

Example:

```
PHP (5)
MySQL (1)
JavaScript (3)
```

---

### Prevent navigation to single-post term archives

If enabled, links for terms used only once will be visually disabled and their href attribute will be removed automatically.

Example:

Before:

```
<a href="/tag/test/">Test</a>
```

After:

```
<a style="cursor:not-allowed">Test</a>
```

This helps prevent users from navigating to archive pages containing only a single post.

---

## Installation

### From Release ZIP

1. Download the [latest release ZIP from GitHub](https://github.com/WPConstructor/terms-enhancer/releases/).
2. In WordPress Admin go to:

```
Plugins → Add New → Upload Plugin
```

3. Upload the ZIP file.
4. Activate the plugin.

---

## Usage

1. Add the **Post Terms** block in the Gutenberg editor.
2. Enable:
   - **Display term counts**
   - **Disable single links**

The plugin automatically enhances the frontend output.

---

## Requirements

- PHP 7.1+
- WordPress 6.0+
- Composer (development only)

---

## Screenshots

<p align="center"><img src="https://wpconstructor.com/assets/images/terms-block-screenshot.jpg" alt="Screenshot of the terms block."></p>

<p align="center"><img src="https://wpconstructor.com/assets/images/terms-block-settings-screenshot.jpg" alt="Screenshot of the settings of the terms block."></p>

---

## License

[GPL-3.0-or-later](https://www.gnu.org/licenses/gpl-3.0.html)

---

## Author

Developed by [WPConstructor](https://wpconstructor.com)