<?php
/**
 * Plugin Name:	Image & Video Lightbox
 * Description: Automatically adds Lightbox functionality to images displayed by WordPress (Gutenberg) Gallery and Image Blocks, as well as GenerateBlocks Image Blocks, and also videos created by the core Video Block without the needs to set each gallery/image and video link to the media file manually one by one.
 * Author: Arya Dhiratara
 * Author URI: https://dhiratara.com/
 * Version:	1.0.0
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
 
namespace ImageVideoLightbox;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

define('ImageVideoLightbox_VERSION', '1.0.0');

class ImageVideoLightbox {
    
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	public function register_assets() {
		
		wp_register_script( 'image-video-lightbox', plugin_dir_url( __FILE__ ) . 'js/fslightbox.min.js', [], ImageVideoLightbox_VERSION, true );
		wp_register_style( 'image-video-lightbox' , false );
		
		// filters the image files to be targeted by this plugin using the images class name
		$use_lightbox_in = apply_filters( 'use_lightbox_in', '.wp-block-image img,.gb-block-image img, .wp-block-video video' );

		// create a.href attribute wrapper for the targeted images
		$lightbox_inline_script = '
			const elements = document.querySelectorAll("' . esc_attr( $use_lightbox_in ) . '");
			elements.forEach(function (element) {
				if (element.classList.contains("no-lightbox") || hasParentWithClass(element, "no-lightbox")) {
					return;
				}

				var wrapper = document.createElement("a");
				wrapper.setAttribute("data-fslightbox", "");
				wrapper.setAttribute("aria-label", "Open in LightBox");

				var sourceElement = element.querySelector("source");
				var sourceSrc = sourceElement ? sourceElement.src : null;
				var mediaSrc = sourceSrc || element.src || element.dataset.src;

				if (element.tagName === "IMG" || element.tagName === "VIDEO") {
					if (mediaSrc) {
						wrapper.setAttribute("href", mediaSrc);
					} else {
						return;
					}
				} else {
					return;
				}

				element.parentNode.insertBefore(wrapper, element);
				wrapper.appendChild(element);
				
			});
			
			refreshFsLightbox();

			function hasParentWithClass(element, className) {
				var parent = element.parentNode;
				while (parent !== document.body) {
					if (parent.classList.contains(className)) {
						return true;
					}
					parent = parent.parentNode;
				}
				return false;
			}
		';

		// minify the inline script before inject
		$lightbox_inline_script = preg_replace(['/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/','/\>[^\S ]+/s','/[^\S ]+\</s','#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si'],['','>','<','$1$2$3$4$5$6$7'], $lightbox_inline_script);

		wp_add_inline_script( 'image-video-lightbox', '' . wp_strip_all_tags( $lightbox_inline_script ) . '' );
		
		$lightbox_inline_style = "
			a[data-fslightbox] {
				position: relative;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			a[data-fslightbox]:hover:before,
			a[data-fslightbox]:before {
				position: absolute;
				content: '';
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				width: 100%;
				height: 100%;
				z-index: 0;
				transition:all .225s linear
			}
			a[data-fslightbox]:hover:before {
				background-color: rgba(0,0,0,0.625);
			}
			a[data-fslightbox]:hover:after {
				position: absolute;
				content: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 15 15'%3E%3Cpath fill='%23eaeaea' d='M7.5,5.5c-1.1,0-1.9,0.9-1.9,2s0.9,2,1.9,2s1.9-0.9,1.9-2S8.6,5.5,7.5,5.5z M14.7,6.9c-0.9-1.6-2.9-5.2-7.1-5.2S1.3,5.3,0.4,6.9L0,7.5l0.4,0.6c0.9,1.6,2.9,5.2,7.1,5.2s6.3-3.7,7.1-5.2L15,7.5L14.7,6.9z M7.5,11.8c-3.2,0-4.9-2.8-5.7-4.3C2.6,6,4.3,3.2,7.5,3.2s4.9,2.8,5.7,4.3C12.4,9,10.8,11.8,7.5,11.8z'%3E%3C/path%3E%3C/svg%3E\");
				width: calc(24px + (36 - 24) * ((100vw - 300px) / (1920 - 300)));
				height: auto;
				transition: transform 0.15s cubic-bezier(0.455, 0.03, 0.515, 0.955),background 0.15s cubic-bezier(0.455, 0.03, 0.515, 0.955);
				z-index: 2;
				filter: drop-shadow(0 1px 12px #333);
			}
		";

		// minify the inline style before inject
		$lightbox_inline_style = preg_replace(['#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s','#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si','#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si','#(?<=[\s:,\-])0+\.(\d+)#s',],['$1','$1$2$3$4$5$6$7','$1','.$1',], $lightbox_inline_style);

		wp_add_inline_style( 'image-video-lightbox', '' . wp_strip_all_tags( $lightbox_inline_style ) . '' );
	}

	public function enqueue_assets() {
		
		// filters whether this plugin assets have to be enqueued.
		$use_lightbox_if = apply_filters( 'use_lightbox_if',
			has_block( 'core/video' ) ||
			has_block( 'core/gallery' ) ||
			has_block( 'core/image' ) ||
			has_block( 'core/media-text' ) ||
			get_post_gallery() ||
			has_block( 'generateblocks/image' )
		);

		if ( $use_lightbox_if ) {
			wp_enqueue_script( 'image-video-lightbox' );
		}
		
		// filters whether to use the this plugin lightbox css
		$use_lightbox_css = apply_filters('use_lightbox_css', false);
		
		if ( $use_lightbox_if && $use_lightbox_css ) {
			wp_enqueue_style( 'image-video-lightbox' );
		}
		

	}

}

$image_video_lightbox = new ImageVideoLightbox();
$image_video_lightbox->initialize();