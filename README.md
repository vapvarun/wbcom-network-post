# Network Posts Extended

[![WordPress](https://img.shields.io/badge/WordPress-5.7.1+-blue.svg)](https://wordpress.org)
[![PHP](https://img.shields.io/badge/PHP-7.2+-purple.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-GPL%20v2+-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

A powerful WordPress Multisite plugin that allows you to display posts, pages, and custom post types from across your entire network on any site. Features Elementor integration, advanced filtering, and flexible layouts including Masonry grid support.

---

## 🚀 Features

- **Network-Wide Content Sharing** - Display posts from any site across your multisite network
- **Elementor Widget** - Drag-and-drop widget with visual controls and live preview
- **Masonry Layout** - Beautiful responsive grid with automatic height calculations
- **Advanced Filtering** - Filter by site, post type, category, tag, or custom taxonomy
- **WooCommerce Support** - Display products with pricing from WooCommerce or eStore
- **Custom Post Types** - Full support for any custom post type
- **Flexible Layouts** - Default, inline, and custom layout options
- **Responsive Design** - Mobile-first design with customizable breakpoints
- **Load More Pagination** - AJAX-powered infinite scroll and load more button
- **Image Management** - Custom thumbnail sizes with automatic resizing
- **ACF Integration** - Display and order by Advanced Custom Fields
- **PHP 8.3+ Compatible** - Modern, optimized codebase

---

## 📋 Requirements

- **WordPress:** 5.7.1 or higher
- **PHP:** 7.2 or higher (tested up to PHP 8.3)
- **Environment:** WordPress Multisite installation (subdirectory or subdomain)
- **Optional:** Elementor (for widget support)
- **Optional:** Advanced Custom Fields (for ACF integration)
- **Optional:** WooCommerce or eStore (for product pricing)

---

## 📦 Installation

### Method 1: WordPress Admin (Recommended)

1. Go to **Plugins** → **Add New**
2. Search for "Network Posts Extended"
3. Click **Install Now** then **Activate**

### Method 2: Manual Upload

1. Download the plugin zip file
2. Go to **Plugins** → **Add New** → **Upload Plugin**
3. Choose the zip file and click **Install Now**
4. Click **Activate**

### Method 3: FTP/SFTP

1. Download and unzip the plugin
2. Upload the `wbcom-network-post` folder to `/wp-content/plugins/`
3. Activate through the **Plugins** menu in WordPress

### Network Activation

You can either:
- **Network activate** - Available on all sites with network-wide settings
- **Activate individually** - Only on specific sites where needed

---

## 🎯 Quick Start

### Basic Shortcode

Display posts from all public sites in your network:

```php
[netsposts]
```

### Common Examples

```php
// Show 10 posts in 3 columns with masonry layout
[netsposts list="10" columns="3"]

// Show posts from specific sites (IDs 1, 3, and 5)
[netsposts include_blog="1,3,5"]

// Show WooCommerce products with prices
[netsposts post_type="product" include_price="woocommerce"]

// Show pages instead of posts
[netsposts post_type="page"]

// Show posts from specific categories
[netsposts taxonomy="news,sports"]

// Show random posts
[netsposts random="true" list="10"]

// Show posts with custom excerpt length
[netsposts excerpt_length="30"]
```

### Using the Elementor Widget

1. Edit any page with Elementor
2. Search for **"Network Posts"** widget
3. Drag it onto your page
4. Configure options in the sidebar:
   - Layout (columns, masonry)
   - Query filters (sites, categories)
   - Display options (image, excerpt, read more)
   - Styling (colors, typography, spacing)
5. Click **Update** to save

---

## 📖 Documentation

### Shortcode Parameters

| Parameter | Description | Example |
|-----------|-------------|---------|
| `include_blog` | Specific blog IDs to include | `include_blog="1,3,5"` |
| `exclude_blog` | Specific blog IDs to exclude | `exclude_blog="2,4"` |
| `post_type` | Post type to display | `post_type="page"` |
| `taxonomy` | Filter by category/tag | `taxonomy="news,sports"` |
| `exclude_taxonomy` | Exclude categories/tags | `exclude_taxonomy="draft"` |
| `list` | Number of posts per page | `list="10"` |
| `columns` | Number of columns | `columns="3"` |
| `random` | Show random posts | `random="true"` |
| `thumbnail` | Show featured images | `thumbnail="true"` |
| `size` | Image size (name or dimensions) | `size="medium"` or `size="300,200"` |
| `excerpt_length` | Excerpt word limit | `excerpt_length="20"` |
| `title_length` | Title character limit | `title_length="50"` |
| `full_text` | Show full content | `full_text="true"` |
| `show_excerpt` | Show/hide excerpt | `show_excerpt="false"` |
| `paginate` | Enable pagination | `paginate="true"` |
| `use_layout` | Layout type | `use_layout="inline"` |
| `read_more_text` | Custom read more text | `read_more_text="Continue Reading"` |
| `order_post_by` | Order by date/title | `order_post_by="date_order desc"` |
| `include_price` | Show product prices | `include_price="woocommerce"` |

---

## 🎨 Layouts

### Default Layout
Standard vertical layout with image on top, title, meta, excerpt, and read more link.

### Inline Layout
Horizontal layout with thumbnail and content side-by-side:
```php
[netsposts use_layout="inline"]
```

### Masonry Layout (Elementor Only)
Dynamic grid with varied heights - enable via Elementor widget settings.

---

## 🔧 Configuration

### Network Settings

Go to **Network Admin** → **Settings** → **Network Posts Thumbnails**:
- Allow/restrict thumbnail resizing per blog
- Set global or per-blog thumbnail sizes
- Manage image resizing permissions

### Blog Settings

Go to **Settings** → **Network Posts** on any site:
- Configure excerpt tag stripping
- Set up custom thumbnail sizes
- Manage blog-specific options

---

## 💻 Advanced Usage

### Custom Post Types

```php
[netsposts post_type="books"]
```

### ACF Integration

Display ACF fields:
```php
[netsposts include_acf_fields="author,publisher,year"]
```

Order by ACF field:
```php
[netsposts order_by_acf="event_date asc"]
```

### Category Offset

Skip recent posts in specific categories:
```php
[netsposts taxonomy_offset_names="books,flowers,sports" taxonomy_offsets="5,4,10"]
```

### Dynamic AJAX Pagination

Enable dynamic loading without page refresh:
```php
[netsposts paginate="true" load_posts_dynamically="true"]
```

### Custom Title Links

Add a heading with link:
```php
[netsposts main_title="Latest Blog Posts" main_title_link="https://example.com/all-posts/"]
```

### WooCommerce Products

Display products with prices:
```php
[netsposts post_type="product" include_price="woocommerce" taxonomy="featured"]
```

---

## 🛠️ Development

### Architecture

```
wbcom-network-post/
├── components/             # Core functionality (PSR-4 autoloaded)
│   ├── db/                # Database query builders
│   │   ├── category/      # Category filtering
│   │   └── NetsPostsQuery.php
│   ├── settings/          # Admin settings pages
│   └── resizer/           # Image resizing system
├── views/                 # Template files
│   └── post/              # Post display templates
├── dist/                  # Compiled JavaScript
├── css/                   # Stylesheets
├── language/              # Translation files (i18n)
└── network-posts-extended.php  # Main plugin file
```

### Code Standards

- ✅ WordPress Coding Standards compliant
- ✅ PHP 8.3+ compatible
- ✅ PSR-4 autoloading with namespaces
- ✅ Namespaced code (`NetworkPosts\Components\*`)
- ✅ Full PHPDoc documentation
- ✅ Security best practices (escaping, sanitization, nonce verification)
- ✅ Modern JavaScript (ES6+)
- ✅ No eval() or unsafe code patterns

### Build System

This plugin uses **Grunt** to create distribution packages for release.

#### Setup

Install Node.js and npm, then run:

```bash
npm install
```

#### Build Commands

```bash
# Create distribution zip file (most common)
npm run zip

# Build files only (without creating zip)
npm run build

# Clean build/release directories
npm run clean
```

#### Build Output

The distribution zip file will be created at:
```
release/wbcom-network-post-v2.0.0.zip
```

#### What Gets Included/Excluded

**Included:**
- ✅ All PHP files (plugin core)
- ✅ JavaScript and CSS assets
- ✅ Template files (`views/`)
- ✅ Translation files (`language/`)
- ✅ Documentation (`readme.txt`)

**Excluded:**
- ❌ `node_modules/`
- ❌ `build/` and `release/` directories
- ❌ `.git/` repository files
- ❌ Build configuration (`package.json`, `Gruntfile.js`)
- ❌ IDE configuration files (`.vscode/`, `.idea/`)
- ❌ Log files and temporary files

---

## ❓ FAQ

### Why is the plugin only showing posts from the main blog?

**Two possible reasons:**

1. **You have `include_blog='1'` in the shortcode** - Remove this to show posts from all sites
2. **Other sites are not set as public** - Go to **Network Admin** → **Sites** → **Edit** (any subsite) and make sure the "Public" checkbox is checked. Private, spam, archived, or deleted sites won't be displayed.

### Should I network activate the plugin?

You can do either:
- **Network activate** - Available on all sites with network-wide settings
- **Activate individually** - Only on specific sites

When network activated, you'll have access to **Network Posts Thumbnails** settings in Network Admin for managing thumbnail permissions across the network.

### Can I show only specific posts?

Yes! Use the `include_post` parameter:
```php
[netsposts include_post="5,78,896"]
```

### How do I shorten long titles?

Use the `title_length` parameter to limit characters:
```php
[netsposts title_length="50"]
```

### Can I order posts by a custom field?

Yes, with ACF support:
```php
[netsposts order_by_acf="last_name asc"]
```

### Does it work with WooCommerce?

Yes! Display WooCommerce products with prices:
```php
[netsposts post_type="product" include_price="woocommerce"]
```

Also compatible with **eStore** plugin.

### Will this work on a single site installation?

No, this plugin requires WordPress Multisite.

### How do I customize the output styling?

You can add custom CSS targeting these classes:
- `.netsposts-items` - Container
- `.netsposts-content` - Individual post card
- `.elementor-post__thumbnail` - Featured image
- `.elementor-post__title` - Post title
- `.elementor-post__excerpt` - Post excerpt
- `.netsposts-read-more-link` - Read more link

---

## 📝 Changelog

### 2.0.0 - 2025-10-24
- **Fixed:** Masonry layout not loading on frontend
- **Added:** Fallback initialization for Elementor widgets
- **Added:** Grunt build system for distribution packages
- **Improved:** JavaScript loading and initialization reliability
- **Enhanced:** Build process with automated zip creation
- **Updated:** Version management across all files

### 1.0.0 - 2025-10-22
- **Initial Release:** Complete rewrite and modernization
- **Added:** PHP 8.3+ compatibility
- **Added:** Modernized Elementor widget (compatible with Elementor 3.5+)
- **Fixed:** Masonry layout calculation issues
- **Improved:** JavaScript performance and loading
- **Enhanced:** Security (removed eval() usage, added nonce verification)
- **Added:** Comprehensive error handling
- **Optimized:** CSS and JavaScript loading
- **Added:** Proper asset versioning
- **Compliance:** Full WordPress Coding Standards

### 7.3.8
- Legacy version (prior to rewrite)

---

## 🤝 Support & Contributing

### Support

🆘 **Get Help:** [https://wbcomdesigns.com/](https://wbcomdesigns.com/)

### Contributing

Contributions are welcome! Please ensure your code follows WordPress Coding Standards.

---

## 📄 License

This plugin is licensed under the **GPL v2 or later**.

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

**Full License:** [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)

---

## 👥 Credits

**Developed by:** [Wbcom Designs](https://wbcomdesigns.com/)
**Maintained by:** Varun Kumar Dubey

---

## ⭐ Support This Project

If you find this plugin helpful, please:
- ⭐ Star this repository
- 📝 Leave a review on WordPress.org
- 🐛 Report issues or suggest features
- 💝 [Consider donating](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VBR3DEUQ5XVMU)

---

**Made with ❤️ for the WordPress Multisite community**
