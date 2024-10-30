=== Image & Video Lightbox ===
Contributors: aryadhiratara
Tags: gallery, image, video, lightbox, blocks, gutenberg
Requires at least: 5.8
Tested up to: 6.2
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically adds Lightbox functionality to images and videos without the need to set the link to media file manually one by one.


== Description ==

Automatically adds Lightbox functionality to images displayed by WordPress (Gutenberg) Gallery and Image Blocks, as well as GenerateBlocks Image Blocks, and also videos created by the core Video Block, using the FSlightbox library.

This lightweight plugin will automatically adds Lightbox functionality to all images displayed by

- the Core Gallery Block,
- the Core Image Block,
- GenerateBlocks Image Block,

and videos displayed by

- the core Video Block

without the needs to set each gallery/image and video link to the media file so you don't need to set them manually one by one.

**Note**: You can exclude specific image/video from getting the lightbox functionality by adding `no-lightbox` class to the image/video element. 

## About The Plugin

- This plugin has no settings _(you can customized the default configuration using filters)_, hence does not add any data to the database, so you don't need to worry about 'database leftovers' if you deactivate the plugin.

- This plugin will automatically add the wrapper link with the necessary attribute on each image and videos that uses the blocks mentioned above to add the lightbox functionality using a few lines of inline script. So it will not make any changes to your original image and video tags (if you check from the page's HTML source), and will not add extra weight / DOM depth to your page's HTML.

- By default, this plugin only works with the native WordPress Gallery, Image, and Video Blocks, as well as GenerateBlocks Image Blocks. You can add additional selectors using filters to make this functionality also works with other image/video blocks. If you need to, send me a request to have your image/video block included by default.

- This plugin will only enqueued its assets (1 JS file) to pages/posts that use blocks, and will not enqueue assets if the page/post doesn't use one of them.

- I provided simple hover CSS which are not enabled by default. You can enable it using filter (_see below_).

##To add other image/video block:

**Add the CSS class uses by the image/video block element**, e.g:

    add_filter( 'use_lightbox_in', function($use_lightbox_in) {
	    return $use_lightbox_in . ',.your-other-plugin-block-image-parent-element img, .your-other-plugin-image-class';
	} );

**Add the block info/name so that this plugin will enqueued the FSlightbox JavaScripts if the block is use in a post/page**, e.g:

    add_filter( 'use_lightbox_if', function($use_lightbox_if) {
	    return $use_lightbox_if . '|| has_block( "kadence/advancedgallery" ) || has_block( "your-plugin-block-name/the-block-name" )';
	} );

###To only enable Lightbox Functionality on certain page/post types:
Simply use the `wp_dequeue` function**, e.g.:

    add_action( 'wp_enqueue_scripts', function() {
	    if ( ! is_singular('post') ) { // only enable in single post type
	        wp_dequeue_script( 'image-video-lightbox' );
	    }
	});
	
###To only enable simple hover CSS:

    add_filter('use_lightbox_css', function () {
	   return true;
	});


###Why using FSlightbox?
- **[FSlightbox](https://github.com/banthagroup/fslightbox)** is Vanilla Javascript Lightbox Library written in pure JavaScript without jQuery or any other additional dependencies.  It’s lightweight (around **9kb** *gzip)!
&nbsp;
- The JavaScript is delayable (_*yes, it's important for me_). This is the only lightbox library I found that works when the JavaScript is delayed.

&nbsp;
## My other Plugins:

- **[Optimize More!](https://wordpress.org/plugins/optimize-more/)** -  A DIY WordPress Page Speed Optimization Pack. Features:
 - **Load CSS Asynchronously** - selectively load CSS file(s) asynchronously on selected post/page types.
 - **Delay CSS and JS until User Interaction** - selectively delay CSS/JS load until user interaction on selected post/page types.
 - **Preload Critical CSS, JS, and Font Files** - selectively preload critical CSS/JS/Font file(s) on selected post/page types.
 - **Remove Unused CSS and JS Files** - selectively remove unused CSS/JS file(s) on selected post/page types.
 - **Load Gutenberg CSS conditionally** - Load each CSS of the core blocks will only get enqueued when the block gets rendered on a page.
 - **Advance Defer JS** - hold JavaScripts load until everything else has been loaded. Adapted from the legendary **varvy's defer js** method _*recommended for defer loading 3rd party scripts like ads, pixels, and trackers_
 - **Defer JS** - selectively defer loading JavaScript file(s) on selected post/page types.
 - **Remove Passive Listener Warnings** - Remove the "Does not use passive listeners to improve scrolling performance" warning on Google PageSpeed Insights

- **[Lazyload, Preload, and more!](https://wordpress.org/plugins/lazyload-preload-and-more/)** - This tiny little plugin (around **14kb** zipped) will automatically:
 - **lazyload** your below the fold images/iframes/videos,
 - **preload** your featured images,
 - and **add `loading="eager"`** to your featured image and all images that have `no-lazy` or `skip-lazy` class.

- **[Shop Extra](https://wordpress.org/plugins/shop-extra/)** - A lightweight plugin to optimize your WooCommerce & Business site:
 - **Floating WhatsApp Chat Widget** (can be use without WooCommerce),
 - **WhatsApp Order Button for WooCommrece**,
 - **Hide/Disable WooCommerce Elements**,
 - **WooCommerce Strings Translations**,
 - and many more.

- **[Animate on Scroll](https://wordpress.org/plugins/animate-on-scroll/)** - Animate any Elements on scroll using the popular AOS JS library simply by adding class names. This plugin helps you integrate easily with AOS JS library to add any AOS animations to WordPress. Simply add the desired AOS animation to your element class name with "aos-" prefix and the plugin will add the corresponding aos attribute to the element tag.

&nbsp;

== Installation ==

#### From within WordPress

1. Visit `Plugins > Add New`
1. Search for `Image & Video Lightbox` or `Arya Dhiratara`
1. Activate the plugin from your Plugins page


#### Manually

1. Download the plugin using the download link in this WordPress plugins repository
1. Upload the plugin folder to your `/wp-content/plugins/` directory
1. Activate the plugin from your Plugins page


== Frequently Asked Questions ==

= Why using FSlightbox library? =

It's lightweight (around 9kb *gzip), no dependencies, and since I love to delaying JavaScripts until user interaction, this is the only library I found that works when the JS is delayed.

= Can this plugin works with other Image or Video Block? =

Just try it. You can use filters like the example I wrote in the plugin description above.

= Can I exclude specific image? =

Yes. Just add `no-lightbox` class to the image/video elements.

== Screenshots ==

1. Image Lightbox using this plugin
2. Video Lightbox using this plugin
2. Simple hover CSS provided by this plugin (not enabled by default)


== Changelog ==


= 1.0.0 =

- Initial release