<?php
/*
Plugin Name: Sub Page List
Description: An easy widget to let you show a clickable list of pages below a particular 'parent' page on your site
Author: Martin Tod
Version: 2.0.2

*/

/*
Version 1.0 (August 27, 2006) Original public version
Version 1.1 (September 12, 2006) Fixes a bug which means that page number isn't remembered.
Version 1.2 (November 26, 2007) Update by Will Howells for v2 change in DB structure for pages.
Version 1.3 (June 22, 2012) Fixing the invisible drop-down box problem.
Version 1.4 (January 13, 2013) Remove deprecated functions
Version 1.5 (December 15, 2013) Add <ul> tags
Version 2.0 (February 8, 2014) Proper support for multi-widget
Version 2.0.1 (February 9, 2014) Completed internationalisation
Version 2.0.2 (July 30, 2014) Addresses bug with 
*/

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.

// This is the original version of the code - which supports old widgets.  If you have no old widgets, it doesn't run.
function widget_subpagehierarchy_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

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
		echo $before_widget . $before_title . $title . $after_title . "<ul>";
		wp_list_pages("sort_column=menu_order&child_of=$headpage&title_li=" );
		echo "</ul>" . $after_widget;
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_subpagehierarchy_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_subpagehierarchy');
		if ( !is_array($options) )
			$options = array('title'=>__('New title','subpagehierarchy'), 'headpage'=>'0');
		if ( $_POST['subpagehierarchy-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['subpagehierarchy-title']));
			$options['headpage'] = strip_tags(stripslashes($_POST['subpagehierarchy-headpage']));
			update_option('widget_subpagehierarchy', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$headpage = intval($options['headpage']);
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="subpagehierarchy-title">'.__('Title:', 'subpagehierarchy').' <input style="width: 200px;" id="subpagehierarchy-title" name="subpagehierarchy-title" type="text" value="'.$title.'" /></label></p>';
		// Get the details for the drop down box
		$dropargs = array(
			'selected'           => $headpage,
			'name'               => "subpagehierarchy-headpage",
			'id'                 => "subpagehierarchy-headpage"
		);
		$pages = get_pages();
		if(!empty($pages)):
			echo '<p style="text-align:right;"><label for="subpagehierarchy-headpage">'. __('Head page:', 'subpagehierarchy');
			wp_dropdown_pages( $dropargs );
			echo '</label></p>';
		else:
			echo '<p style="text-align:right;"><em>'.__('To use this widget, please add some pages to your site.','subpagehierarchy').'</em></p>';
		endif;
		echo '<input type="hidden" id="subpagehierarchy-submit" name="subpagehierarchy-submit" value="1" />';
	}	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	wp_register_sidebar_widget('ldpagehierarchy', __('Sub page hierarchy [old]','subpagehierarchy'), 'widget_subpagehierarchy', array('description'=> __( 'Adds a sidebar widget to let you show the list of pages beneath a particular page on your site.', 'subpagehierarchy' )));

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	wp_register_widget_control('ldpagehierarchy', __('Sub page hierarchy [old]','subpagehierarchy'), 'widget_subpagehierarchy_control');
}

// Check if there are any old widgets in place...
$ldpagehierarchy_listofwidgets = get_option('sidebars_widgets');
foreach ($ldpagehierarchy_listofwidgets AS $ldpagehierarchy_sidebar) {
	if( is_array($ldpagehierarchy_sidebar) && in_array('ldpagehierarchy',$ldpagehierarchy_sidebar) ) add_action('plugins_loaded', 'widget_subpagehierarchy_init');
}

// Start again from scratch - this version of the code handles multiple widgets

class subpagehierarchy_widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// widget actual processes
		parent::__construct(
			'subpagehierarchy_widget', // Base ID
			__('Sub page hierarchy', 'subpagehierarchy'), // Name
			array( 'description' => __( 'Adds a sidebar widget to let you show the list of pages beneath a particular page on your site.', 'subpagehierarchy' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		$title = apply_filters( 'widget_title', $instance['title'] );
		$headpage = intval($instance['headpage']);
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		echo "<ul class='subpagehierarchy_list'>";
		wp_list_pages("sort_column=menu_order&child_of=$headpage&title_li=" );
		echo "</ul>".$args['after_widget'];		
	}

	/**
	 * Ouputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_subpagehierarchy');
		if ( !isset($instance['title']) ):
			$instance = array('title'=>__('','subpagehierarchy') , 'headpage'=> 0 );
		endif;
		$title = $instance['title'];
		$headpage = $instance['headpage'];
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','subpagehierarchy' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
		$pages = get_pages();
		$dropargs = array(
			'selected'           => $headpage,
			'name'               => $this->get_field_name( 'headpage' ),
			'id'                 => $this->get_field_id( 'headpage' )
		);
		if(!empty($pages)):
		?>
			<p><label for="<?php $this->get_field_id( 'headpage' ) ?>"><?php _e('Head page:', 'subpagehierarchy'); ?></label>
			<?php 
			wp_dropdown_pages( $dropargs ); 
			echo "</p>";
		else:
			?>
			<p class="widefat"><em><?php _e('To use this widget, please add some pages to your site.','subpagehierarchy'); ?></em></p>
			<?php
		endif;
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array('title'=>'','headpage'=>0);
		if(!empty($new_instance['title'])):
			$instance['title'] = strip_tags( $new_instance['title'] );
		endif;
		$instance['headpage'] = intval($new_instance['headpage']);
		return $instance;	
	}
}

// register Foo_Widget widget
function register_subpagehierarchy_widget() {
    register_widget( 'subpagehierarchy_widget' );
}
add_action( 'widgets_init', 'register_subpagehierarchy_widget' );

?>