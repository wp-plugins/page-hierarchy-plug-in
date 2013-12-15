=== Sub Page hierarchy ===
Contributors: mpntod
Donate link: http://www.martintod.org.uk/blog/?p=96
Tags: menu,widget
Requires at least: 2.0
Tested up to: 3.8
Author URI: http://www.martintod.org.uk/blog/
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a sidebar widget to let you show the list of pages beneath a particular page on your site

== Description ==

Adds a sidebar widget to let you show the list of pages beneath a particular page on your site

A quick way to show a subset of pages - without having to show all the pages on the site.

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


= Help. My widget disappeared with the latest upgrade. Is that right? =

Yes. Unfortunately, moving from the deprecated `register_sidebar_widget()` to the new `wp_register_sidebar_widget()` function removes your widget from the widget area or sidebar.  If you just put it back into the widget area or sidebar, your settings should still be there.

== Upgrade notice ==
= 1.5 =
* Adds missing `ul` tags. Removes deprecated function. WARNING: This will require you to re-add your widget to the sidebar or widget area.

== Changelog ==
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