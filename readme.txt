=== WordPress Galleria ===
Contributors: sushkov
Donate link: http://stas.nerd.ro/donate/
Tags: gallery, photos, seamless integration, auto-creation, foto, gallery, galleria fancybox
Requires at least: 2.3
Tested up to: 3.0.1
Stable tag: 1.3

== Description ==

A plugin which generates photo galleries on the fly.
All the user should do is just upload his folders with photos to the server
in your uploads `wp-galleria/` folder.

The plugin automatically parses the directories inside and generates the pages and thumbnails.

WP-Galleria uses [FancyBox](http://fancybox.net/) to render photos.

Checkout the live demo.

== Updates ==

Version 1.1 is almost a complete rewrite of the plugin.
We needed something simple and working in small time, so was born wp-galleria.

Current limitations (mostly no limitations since v1.1):

* Do not use `.` (dots) in folder names. (Fixed)
* Limit your directory tree up to 3 levels only. (Fixed)
* If it looks broken, check out the CSS, or [report a bug](http://stas.nerd.ro/blog/index.php/about).
* No translations.

== Installation ==

= New installation =
1. Upload `wp-galleria` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Tools menu where you'll find 'WordPress Galleria' settings page

= Upgrade from 1.0 =
1. Backup your `wp-content/plugins/wp-galleria/data` folder!!!
1. Upgrade
1. Move your old contents into the new `wp-galleria` pics folder (you can find it in administration panel)

== Changelog ==

= 1.0 =
* Initial release. Unstable, limited!

= 1.1 =
* Almost a complete rewrite. Still as simple as it was.
* Fully rewrote the content generation part. Removed all previous limits.
* Now `data` folder is moved to `wp-content/wp-galleria`
* Updated all JavaScript parts. Latest FancyBox!
* Updated the translation.

= 1.2 =
* Forgot a small typo that can allow access to filesystem.

= 1.3 =
* Upgraded to be compatible with WordPress MultiSite

== Frequently Asked Questions ==

= Want to translate? =

Get the [pot](http://svn.wp-plugins.org/wp-galleria/trunk/l10n/wp-galleria.pot) file.

= Want to submit your translation? =

[Send me an email](http://stas.nerd.ro/index.php/about/) directly.
