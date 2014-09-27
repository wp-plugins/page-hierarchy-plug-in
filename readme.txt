=== Sub Page List Widget ===
Contributors: mpntod
Donate link: http://www.martintod.org.uk/blog/?p=96
Tags: widget,list,page,sidebar,menu,parent,child
Requires at least: 2.2.0
Tested up to: 4.0
Author URI: http://www.martintod.org.uk/
Stable tag: 2.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An easy widget to let you show a clickable list of pages linked to a particular 'parent' page on your site

== Description ==

A quick way to show a set of the pages in a particular part of your site without having to show all the pages on the site.

Adds a widget to let you show a clickable list of pages linked to a particular 'parent' page on your site.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `ldpagehierarchy.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Design->Widgets and click on 'add' next to 'sub page hierarchy'
1. Go to the right of the page and hit 'edit' next to 'sub page hierarchy'
1. Select the top page of your hierarchy under 'head page'
1. Click 'change' and then 'save changes'
== Frequently asked questions ==

= Help. My widget disappeared with the upgrade to version 1.4. Is that right? =

Yes. Unfortunately, this happened with the upgrade to version 1.4.  Moving from the deprecated `register_sidebar_widget()` to the new `wp_register_sidebar_widget()` function removes your widget from the widget area or sidebar.  If you just put it back into the widget area or sidebar, your settings should still be there.

== Upgrade notice ==
= 2.0.2 =
* Supports multiple widgets. Addresses error with `wp_get_sidebars_widgets();` function.

== Changelog ==
= 2.0.3 =
* Changed name of the plug-in from 'Sub Page Hierarchy' to 'Sub Page List Widget'

= 2.0.2 =
* Addresses error with `wp_get_sidebars_widgets();` function.

= 2.0.1 =
* Supports internationalisation

= 2.0 =
* Supports multiple widgets

= 1.5 =
* Add missing `ul` tags.

= 1.4 =
* Remove deprecated functions

= 1.3 =
* Fixing the invisible drop-down box problem. Moving to the standard Wordpress  `wp_dropdown_pages()` function.

= 1.2 =
* Update by Will Howells for v2 change in DB structure for pages.

= 1.1 =
* Fixes a bug which means that page number isn't remembered.

= 1.0 =
* Original public version
