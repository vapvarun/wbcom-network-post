=== Network Posts Extended ===
Contributors: wbcomdesigns
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VBR3DEUQ5XVMU
Tags: network global posts, network posts, global posts, multisite posts, shared posts, display multisite posts
Requires at least: 5.7.1
Tested up to: 6.2
Stable tag: 1.0.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The plugin is designed to share posts, pages, and custom post types from across entire WordPress multisite network on any given page for any subdomain and the main blog.
== Description ==

<p>The plugin is designed to list posts, pages, and custom post types from across entire WordPress multisite network on any given page for any subdomain and the main blog.</p><p>If you would like to list all posts in a WordPress multisite installation on any other blog in the network this plugin will do that for you. Let's say you want to list the posts from all blogs or selected blogs on the main site you may do so with pagination or limit the amount of posts. You may also list the main blog in a multisite installation on any sub blog. You may list posts from blog1 and blog2 on blog3 or the main blog. Any combination is possible. This makes a perfect WordPress Multisite Posts listing plugin. You may also filter the listing anyway you desire by title keyword or category. Even custom categories.</p>

== Frequently Asked Questions ==

= Why is the plugin is only pulling in posts from main blog only? =

There are two answers to this question. 1) You have include_blog=&#39;1&#39; inside the shortcode. Simply remove this. 2) The other blogs are not setup as public. When you go in to network admin area and visite sites > (any subsite) > edit you will see a list of four checkboxes. Make sure the one marked public is checked. If a site is marked as private, spam, etc... anything other than public this plugin will not show it (as it shouldn't for security reasons).

= Should I network activate the plugin? =

You may network activate the plugin so it is available on all sites or activate individually. When network activated there will be a new menu for the plugin under settings &#62; Network Posts Thumbnails which will allow you to give certain permissions to blog owners when it comes to the thumbnail sizes. You may allow to create new sizes just on their blog or across the entire network which will affect everybody. I recommend only to allow it for their blog only. This allows you to also include it as a custom feature if you want to charge for this capability.

= May I only include an x amount of posts that I choose?  =

Yes, use include_post= and put in your posts in comma separated format surrounded by double quotes. Example include_post=&#39;5,78,896&#39;.

= My title is too long and looks ugly, anyway I can shorten it?  =

You may shorten it using the argument title_length=&#39;10&#39; will rounded it off to the last complete word before it reaches 10 characters.

= I would like to just show an X amount of random posts on the home page. Is it possible?  =

Use the following arguments: random=&#39;true&#39; and list=&#39;10&#39; will show ten different posts randomly whenever the page is loaded. If you add list=&#34;15&#34; it will show fifteen different posts randomly.

= May I order my posts in specific order by date or title?  =
Yes you may give specific ordering of your posts or pages via alphabetical order (by title), by date or page or post ID specific order.

= Does this plugin list pages from woocommerce?  =

Yes it now does as of version 0.1.4. You may list via page/post id or via taxonomy=&#39;custom woocommerce category&#39;. Woocommerce default directory/taxonomy is product show you would just use the argument taxonomy=&#39;Product&#39; which is the title of the directory. (Not case sensitive)
<strong>Note:</strong> Also works with Tips and Tricks eStore plugin.

= Will this plugin also include the prices from the products I create with the Woocommerce and eStore plugins?  =

Yes it will including the following argument: include_price=&#39;woocommerce&#39; or include_price=&#39;estore&#39;. If for some reason you have both plugins installed you would use include_price=&#39;estore|woocommerce&#39; if you want to list them both.

= Why when I use the following argument wrap_start=&#34;&lt;div style=&#34;color:blue;&#34;&gt;&#34; and wrap_end=&#34;&lt;/div&gt;&#34; the text does not change color? =

That is because since double quotes are used after the = sign they must be changed to single quotes and use double quotes in the html. For example you would have to have wrap_start=&#39;&lt;div style=&#34;color:blue;&#34;&gt;&#39;. <strong>Notice the double quotes in the html</strong> Do not forget to change the closing argument to wrap_end=&#39;&lt;/div&gt;&#39;

= Does this plugin work with custom post types. That is post_type=&#39;custom-post-type&#39;? =

Yes it now works with custom post types.

= Can I show full post from any blog on any site?  =

Yes you can by using the following argument full_text=&#39;true&#39;

= I have custom image sizes I have already created and uploaded. How can I use them with your plugin without having to go through the process of re-creating image sizes with your plugin?  =

You can use them directly as a featured image or you can install the plugin https://wordpress.org/plugins/featured-image-from-url/ and put in the link directly to the images. This plugin will automatically switch to the one listed here. Don&#39;t forget to change size=&#39;H,W&#39; to the dimensions of the featured image.

= The default layout is quite ugly. How do I improve it?  =

Using css I have made this plugin very flexible. It now contains two default layouts. Their names are &#34;default&#34; and &#34;inline&#34;. You may choose either one by using use_layout=&#39;default&#39; or use_layout=&#39;inline&#39;.

= Can I use shortcode attributes in dynamically created url?  =

Yes you may now use the shortcode attributes in a url. Example: http://localhost/wordpress/home-page/?column=infinite&include_blog=1,2,3&taxonomy=wordpress-develop,second-posts

= Where do I put the shortcodes?  =

Paste the shortcode on any page, post or custom post type using the <strong><em>Text</em></strong> not the <strong><em>Visual</em></strong> area of the posts editor field.

= How do I use this plugin in widgets?  =

Use the default WordPress text widget and post the code in there under the <strong><em>Text</em></strong> not <strong><em>Visual</em></strong> code area so it will not mess with the shortcode. This widget is now automatically shortcode ready. No need for a special widget or plugin to activate shortcodes in widgets.

= Is it possible to include custom post type meta information?  =

As long as you use the <a href="https://wordpress.org/plugins/advanced-custom-fields/">Advanced Custom Fields Plugin</a> it will be possible to do so. Read the readme.txt file for arguments to add to the shortcode.

= Can I offset posts by skipping the three most recent posts and choose which category I'd like to offset?  =

Yes you now can with the following two arguments. 1) taxonomy_offset_names='' and 2) taxonomy_offsets=''. So for example if you wanted to offset three different categories it would look like so: [netsposts taxonomy_offset_names='books,flowers,sports' taxonomy_offsets='5,4,10'] then books would be offset by 5, flowers 4, and sports 10. To offset by tags include the following argument: taxonomy_offset_type='' so it will now work with tags. As of now the only argument it accepts are category, or tag, or any. So if you want to offset by certain tag you would add taxonomy_offset_type='tag'. Default it offsets by post type when argument is not used.

= I have my my multisite installed in a subfolder and the permalinks sometimes adds a /blog/ to the permalink. How can I remove this?  =

Add the following argument: remove_blog_prefix=&#39;true&#39;.

= Will this plugin work on a non-multisite/single site installation?  =

No it will not but you may get the single site version here: [Single Site Posts Extended](https://agaveplugins.com/plugins/)

= May I use an ACF custom field to order my posts? I want to order by peoples last name =

You may do so using the following argument: order_by_acf=&#39;field_name asc/desc&#39; but only use asc or desc depending on the direction you would like. It works both numerically and alphabetically.

= How do I create a title for the particular list and make it link to any page I like? =

You do so with the following parameters. 1) main_title='List of Blogs About Bikes' main_title_link='https://mysite.com/my-bike-page/'.

== Screenshots ==

1. Using argument use_layout='inline'.
2. Showing custom thumbnail size by using size='custom-thumbnail-size'
3. Can be displayed in multiple columns with some css editing.
4. Image resizing. You may set up custom image sizes and regenerate thumbnails.

== Changelog ==

== 7.3.8 ==
Initial Release

== List of Arguments ==

This list of arguments have been moved to [https://agaveplugins.com/npe-tutorial](https://agaveplugins.com/tutorials/plugins/multisite/network-posts-extended/)

*Shortcode Examples:*

1. [netsposts post_type='page'] - Will only show a list of pages from all sites.
1. [netsposts post_type='books' - Will only show posts in the custom post type of **books**.
1. [netsposts include_blog='3,11' - Will only show posts from the sites with the **ID** of 3 and 11. No other posts will be shown.

*Key Features:*

* Shows posts with excerpts from content or manual excerpt field.
* Can limit length of excerpt by letters or words.
* Has the option to show full content of posts.
