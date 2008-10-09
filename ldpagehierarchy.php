<?php
/*
Plugin Name: Sub page hierarchy
Description: Adds a sidebar widget to let you select a subpage hierarchy to display on your site
Author: Martin Tod
Version: 1.1
Author URI: http://www.martintod.org.uk/blog/
*/
/*  Copyright 2006  Martin Tod  (email : martin@martintod.org.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
Version 1.0 (August 27, 2006) Original public version
Version 1.1 (September 12, 2006) Fixes a bug which means that page number isn't remembered.
*/

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_subpagehierarchy_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;
	// This function calls the drop down menu
	function widget_subpagehierarchy_page_rows($headpage, $parent = 0, $level = 0, $pages = 0) {
		global $wpdb, $class, $post;
		if (!$pages)
			$pages = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_status = 'static' ORDER BY menu_order");	
		if ($pages) {
			foreach ($pages as $post) {
				start_wp();
				if ($post->post_parent == $parent) {
					$post->post_title = wp_specialchars($post->post_title);
					$pad = str_repeat('&#8212;', $level);
					$id = $post->ID;
					if($id == $headpage) { $selectString=' selected';}else{unset($selectString);}
					$class = ('alternate' == $class) ? '' : 'alternate';
					echo '  <option value="'.$post->ID.'"'.$selectString.">$pad".$post->post_title."</option>\n";	
					widget_subpagehierarchy_page_rows($headpage,$id, $level +1, $pages);
				}
			}
		} else {
			return false;
		}
	}


	// This is the function that outputs the list of sub-pages.
	function widget_subpagehierarchy($args) {
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_subpagehierarchy');
		$title = $options['title'];
		$headpage = $options['headpage'];
		settype($headpage,"integer");
		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.
		echo $before_widget . $before_title . $title . $after_title;
		wp_list_pages("sort_column=menu_order&child_of=$headpage&title_li=" );
		echo $after_widget;
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_subpagehierarchy_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_subpagehierarchy');
		if ( !is_array($options) )
			$options = array('title'=>'Pages', 'headpage'=>'0');
		if ( $_POST['subpagehierarchy-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['subpagehierarchy-title']));
			$options['headpage'] = strip_tags(stripslashes($_POST['subpagehierarchy-headpage']));
			update_option('widget_subpagehierarchy', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$headpage = htmlspecialchars($options['headpage'], ENT_QUOTES);
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="subpagehierarchy-title">Title: <input style="width: 200px;" id="subpagehierarchy-title" name="subpagehierarchy-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="subpagehierarchy-headpage">Head page:<select id="subpagehierarchy-headpage" name="subpagehierarchy-headpage">';
		// Get the details for the drop down box
		widget_subpagehierarchy_page_rows($headpage);
		echo '</select></label></p>';
		echo '<input type="hidden" id="subpagehierarchy-submit" name="subpagehierarchy-submit" value="1" />';
	}	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget('Sub page hierarchy', 'widget_subpagehierarchy');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control('Sub page hierarchy', 'widget_subpagehierarchy_control', 400, 200);
}

// Run our code later in case this loads prior to any required plugins.
add_action('plugins_loaded', 'widget_subpagehierarchy_init');

?>