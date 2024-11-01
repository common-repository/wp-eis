=== WP EIS ===
Contributors: mehral
Tags: slider, slideshow, responsive slider, responsive, slideshow, slideshow manager
Requires at least: 3.4
Tested up to: 3.5.1
Stable tag: 1.3.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A responsive slideshow.

== Description ==

This slideshow will adjust automatically to its surrounding container and we can navigate through the slides by using the thumbnail previewer or the autoplay slideshow option. 

= Demo =
You can see [__WP EIS__](http://demo.mehral.com/wp-eis/)

= Tutorial =
* English [__WP EIS__](http://blog.mehral.com/projects/eis-wordpress-plugin/)
* Persian [__WP EIS__](http://fablog.mehral.com/go/eis-wordpress-plugin/)

= Docs & Support =

If you have any questions about this plugin you can put your comments here [__Mehral Blog__](http://blog.mehral.com/projects/eis-wordpress-plugin/) and I will answer to your comments as soon as possible.

= Attention =
Before installing this plugin, please read installation section 

= WP EIS Needs Your Support =

If you enjoy using WP EIS and find it useful, please consider [__My Facebook__](http://www.facebook.com/mehralco). Your supporting will help encourage and support the plugin's continued development..


= Translate =

* Persian (fa_IR)
* English 


If you have created your own language pack, or have an update of an existing one, you can send [gettext PO and MO files](http://codex.wordpress.org/Translating_WordPress) to [me](http://blog.mehral.com/plugins-support/) so that I can append it into WP EIS.


= Photos =
* [__Andimayr - Photographer__](http://www.andimayr.de/)
* [__Hadi Karimi - Graphic Designer__](http://qleph.com/)

== Installation ==

1. Upload the entire `wp-eis` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Place `<?php echo do_shortcode('[wp_eis id="slideshow ID"]'); ?>` in everywhere in your theme.
4. Place `[wp_eis id="slideshow ID"]` in your post but for best display consider the post's width with your Images's width

IMPORTANT!!!!
PLEASE MAKE SURE THAT YOUR THEME HAS THE FOLLOWING LINE IN ITS HEADER FILE:
`<?php wp_enqueue_script("jquery"); ?>`

BEFORE 

`<?php wp_head(); ?>`

You will find 'WP EIS' menu in your WordPress admin panel.

For basic usage, you can also have a look at the [plugin homepage](http://blog.mehral.com/projects/eis-wordpress-plugin/).

== Frequently Asked Questions ==

Do you have questions or issues with WP EIS? Use this support channel appropriately.

1. [Support Site](http://blog.mehral.com/projects/eis-wordpress-plugin/)


== Screenshots ==

1. screenshot-1.png 
2. screenshot-2.png 
3. screenshot-3.png 
4. screenshot-4.png 
5. screenshot-5.png 

== Changelog ==

= 1.3.3 =
* Fixed some errors in the javascript file
* Fixed users capability
* Fixed shorcode sections 
* Added loading setting section
* Added pagination for all photos
* Change titles mode to the optional mode

= 1.3.2 =
* Supported utf-8 character in ajax interaction
* Improved user response in adding, deleting or updating items
* Updated language files
* Improved Add slide section
* Improved Add images section
* Added WordPress editor for updating image informations
* Added hyperlink and another tags into titles
* Fixed styles and javascript for theme

= 1.3.1 =
* Fixed error in shortcode

= 1.3.0 =
* Added multiuploader for uploading multiple images at one time
* Advanced and basic mode for novice and professional users
* Advanced settings for slideshow appearance 
* Added user role capability in wordpress multiple users
* Fixed styles and javascript in a theme
* Supported ajax in persian language
* Optimized database

= 1.2.0 =
* removed noscript style
* Changed Persian mo and po file

= 1.1.0 =
* Fixed autoplay option.
* There was a problem about using this plugin in a theme.
* changed some logo.
= 1.0.0 =
* The very first version of this plugin :)

== Upgrade Notice ==

The current version of WP_EIS requires WordPress 3.4 or higher. If you use older version of WordPress, you need to upgrade WordPress first.
