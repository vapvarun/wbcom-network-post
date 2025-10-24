# Network Posts Extended

**Contributors:** wbcomdesigns
**Donate link:** https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VBR3DEUQ5XVMU
**Tags:** network global posts, network posts, global posts, multisite posts, shared posts, display multisite posts
**Requires at least:** 5.7.1
**Tested up to:** 6.2
**Stable tag:** 1.0.0
**Requires PHP:** 7.2+
**Compatible with PHP:** 8.3+
**License:** GPLv2 or later
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

A WordPress multisite plugin to share posts, pages, and custom post types from across the entire network on any site.

---

## Description

The **Network Posts Extended** plugin allows you to list posts, pages, and custom post types from across your entire WordPress multisite network on any page, for any subdomain, or the main blog.

### Key Features

- ✅ Display posts from all sites or specific sites in your network
- ✅ List posts from any blog on any other blog in the network
- ✅ Support for custom post types (including WooCommerce products)
- ✅ Pagination support with customizable styling
- ✅ Filter by categories, tags, and custom taxonomies
- ✅ Display prices for WooCommerce and eStore products
- ✅ Custom excerpt length (by words or characters)
- ✅ Full post content display option
- ✅ Random post display
- ✅ Custom ordering (by date, title, or custom fields)
- ✅ Advanced Custom Fields (ACF) support
- ✅ Multiple layout options (default and inline)
- ✅ Elementor widget integration
- ✅ WordPress widget support
- ✅ Custom image sizes and thumbnail management
- ✅ Responsive design ready
- ✅ PHP 8.3+ compatible

---

## Installation

### Method 1: FTP Upload
1. Download and unzip the plugin file
2. Upload the `wbcom-network-post` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress

### Method 2: WordPress Admin
1. Go to Plugins → Add New
2. Search for "Network Posts Extended"
3. Click "Install Now" then "Activate"

### Method 3: Direct Upload
1. Go to Plugins → Add New → Upload Plugin
2. Choose the downloaded zip file
3. Click "Install Now" then "Activate"

---

## Basic Usage

### Shortcode

Add the shortcode to any page or post:

```
[netsposts]
```

### Common Examples

```php
// Show only pages from all sites
[netsposts post_type='page']

// Show posts from specific sites (IDs 3 and 11)
[netsposts include_blog='3,11']

// Show WooCommerce products with prices
[netsposts post_type='product' include_price='woocommerce']

// Show 10 random posts
[netsposts random='true' list='10']

// Show posts from specific category
[netsposts taxonomy='news,sports']

// Show posts in 3 columns
[netsposts columns='3']

// Use inline layout
[netsposts use_layout='inline']
```

### Elementor Widget

The plugin includes an Elementor widget with visual controls for:
- Column count (responsive)
- Masonry layout
- Image settings
- Typography
- Spacing
- And more...

---

## Frequently Asked Questions

### Why is the plugin only pulling posts from the main blog?

**Two possible reasons:**

1. **You have `include_blog='1'` in the shortcode** – Remove this to show all blogs
2. **Other blogs are not set as public** – Go to Network Admin → Sites → Edit (any subsite) and make sure the "Public" checkbox is checked. Private, spam, or archived sites won't be displayed.

### Should I network activate the plugin?

You can either:
- **Network activate** – Available on all sites with network-wide settings
- **Activate individually** – Only on specific sites

When network activated, you'll have access to **Network Posts Thumbnails** settings under Settings in Network Admin, allowing you to control thumbnail permissions across the network.

### Can I show only specific posts?

Yes! Use the `include_post` parameter:

```
[netsposts include_post='5,78,896']
```

### How do I shorten long titles?

Use the `title_length` parameter to limit characters:

```
[netsposts title_length='50']
```

### Can I order posts by a custom field?

Yes, with ACF support:

```
[netsposts order_by_acf='last_name asc']
```

### Does it work with WooCommerce?

Yes! Display WooCommerce products with prices:

```
[netsposts post_type='product' include_price='woocommerce']
```

Also compatible with **eStore** plugin.

### Can I use it in widgets?

Yes! Use the standard WordPress Text widget or the included Network Posts widget. The plugin also provides an Elementor widget.

### Will this work on a single site installation?

No, this plugin requires WordPress Multisite. For single sites, check out the [Single Site Posts Extended](https://agaveplugins.com/plugins/) version.

### How do I use custom layouts?

The plugin includes two default layouts:

```
[netsposts use_layout='default']  // Default layout
[netsposts use_layout='inline']   // Inline layout (thumbnail + text side-by-side)
```

You can also add custom CSS to style the output.

---

## Configuration

### Network Settings (Network Admin)

Go to **Network Admin → Settings → Network Posts Thumbnails** to:
- Allow/restrict thumbnail resizing per blog
- Set global or per-blog thumbnail sizes
- Manage image resizing permissions

### Blog Settings

Go to **Settings → Network Posts** on any site to:
- Configure excerpt tag stripping
- Set up custom thumbnail sizes
- Manage blog-specific options

---

## Shortcode Parameters

For a complete list of all shortcode parameters and examples, visit:

👉 [https://agaveplugins.com/tutorials/plugins/multisite/network-posts-extended/](https://agaveplugins.com/tutorials/plugins/multisite/network-posts-extended/)

### Most Common Parameters

| Parameter | Description | Example |
|-----------|-------------|---------|
| `include_blog` | Specific blog IDs to include | `include_blog='1,3,5'` |
| `exclude_blog` | Specific blog IDs to exclude | `exclude_blog='2,4'` |
| `post_type` | Post type to display | `post_type='page'` |
| `taxonomy` | Filter by category/tag | `taxonomy='news,sports'` |
| `list` | Number of posts to show | `list='10'` |
| `columns` | Number of columns | `columns='3'` |
| `random` | Show random posts | `random='true'` |
| `thumbnail` | Show featured images | `thumbnail='true'` |
| `size` | Image size | `size='300,200'` |
| `excerpt_length` | Excerpt word limit | `excerpt_length='20'` |
| `full_text` | Show full content | `full_text='true'` |
| `paginate` | Enable pagination | `paginate='true'` |
| `use_layout` | Layout type | `use_layout='inline'` |

---

## Advanced Features

### Custom Post Types

```
[netsposts post_type='books']
```

### ACF Integration

Display ACF fields:

```
[netsposts include_acf_fields='author,publisher,year']
```

Order by ACF field:

```
[netsposts order_by_acf='event_date asc']
```

### Category Offset

Skip recent posts in specific categories:

```
[netsposts taxonomy_offset_names='books,flowers,sports' taxonomy_offsets='5,4,10']
```

### Dynamic AJAX Pagination

Enable dynamic loading without page refresh:

```
[netsposts paginate='true' load_posts_dynamically='true']
```

### Custom Title Links

Add a heading with link:

```
[netsposts main_title='Latest Blog Posts' main_title_link='https://example.com/all-posts/']
```

---

## Technical Requirements

- **WordPress:** 5.7.1 or higher
- **PHP:** 7.2 or higher (tested up to PHP 8.3)
- **Environment:** WordPress Multisite installation
- **Optional:** Elementor (for widget support)
- **Optional:** Advanced Custom Fields (for ACF integration)
- **Optional:** WooCommerce or eStore (for product pricing)

---

## Changelog

### 1.0.0
- Initial release
- PHP 8.3+ compatibility
- Modernized Elementor widget (compatible with Elementor 3.5+)
- Fixed Masonry layout issues
- Improved JavaScript performance
- Enhanced security (removed eval() usage)
- Added comprehensive error handling
- Optimized CSS and JavaScript loading
- Added proper asset versioning
- Full WordPress Coding Standards compliance

### 7.3.8
- Legacy version

---

## Development

### Code Standards

- ✅ WordPress Coding Standards compliant
- ✅ PHP 8.3+ compatible
- ✅ PSR-4 autoloading
- ✅ Namespaced code (`NetworkPosts\Components\*`)
- ✅ Full PHPDoc documentation
- ✅ Security best practices (escaping, sanitization, nonce verification)

### Architecture

```
wbcom-network-post/
├── components/          # Core functionality (PSR-4 autoloaded)
│   ├── db/             # Database query builders
│   ├── settings/       # Admin settings pages
│   └── resizer/        # Image resizing system
├── views/              # Template files
├── dist/               # Compiled JavaScript
├── css/                # Stylesheets
└── language/           # Translation files
```

### Build System

This plugin uses **Grunt** to create distribution packages for release.

#### Setup

1. Install Node.js and npm (if not already installed)
2. Install dependencies:

```bash
npm install
```

#### Build Commands

```bash
# Create distribution zip file (default task)
npm run zip

# Build files only (without creating zip)
npm run build

# Clean build/release directories
npm run clean
```

The distribution zip file will be created in the `release/` folder with the naming format:
```
release/wbcom-network-post-v1.0.0.zip
```

#### What Gets Included

The build process automatically excludes development files:
- ❌ `node_modules/`
- ❌ `build/` and `release/` directories
- ❌ `.git/` repository files
- ❌ Build configuration files (`package.json`, `Gruntfile.js`)
- ❌ IDE configuration files
- ❌ Log files and temporary files

All plugin files (PHP, JavaScript, CSS, images, translations) are included in the distribution package.

---

## Support & Documentation

- **Documentation:** [https://agaveplugins.com/tutorials/plugins/multisite/network-posts-extended/](https://agaveplugins.com/tutorials/plugins/multisite/network-posts-extended/)
- **Support:** [https://wbcomdesigns.com/](https://wbcomdesigns.com/)
- **Author:** Wbcom Designs

---

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

---

## Credits

Developed by **Wbcom Designs**
Visit: [https://wbcomdesigns.com/](https://wbcomdesigns.com/)

---

## Screenshots

1. **Inline Layout** - Using `use_layout='inline'` for side-by-side thumbnail and content
2. **Custom Thumbnails** - Custom thumbnail sizes via `size='custom-thumbnail-size'`
3. **Multi-column Display** - Multiple columns with responsive CSS
4. **Image Resizing** - Admin interface for custom image sizes and thumbnail regeneration
5. **Elementor Widget** - Visual widget builder with live preview
6. **Masonry Layout** - Dynamic masonry grid layout with Elementor

---

**⭐ If you find this plugin helpful, please consider leaving a review!**
