<?php 

$GLOBALS['CHILD_THEME_COLORS'] = array(
	'white' => 'ffffff',
	'black' => '000000',
	'primary-dark' => '2b3990',
	'primary-light' => '8DC63F',
	'secondary-dark' => '00AEEF',
	'secondary-light' => 'D8F3FD',
	'warning' => 'C92D2D',
	'success' => '2DC98D'
);

$GLOBALS['GMAPS_API_KEY'] = get_option('csomaster_google_maps_api_key');

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css?v=0.8' );
}


/* Default brand colors for MCE color picker */
function csomaster_mce4_options($init) {

	// Loop through THEME_COLORS and add them to the MCE color picker
	$THEME_COLORS = $GLOBALS['CHILD_THEME_COLORS'];

	$custom_colours = "";

	foreach($THEME_COLORS as $name => $hex) {
		$custom_colours .= "'$hex',' $name',";
	}

    // build colour grid default+custom colors
    $init['textcolor_map'] = '['.$custom_colours.']';

    // change the number of rows in the grid if the number of colors changes
    // 8 swatches per row
    $init['textcolor_rows'] = 1;

    return $init;
}
add_filter('tiny_mce_before_init', 'csomaster_mce4_options');


// Method 2: Setting.

function cso_hq_acf_init() {
    acf_update_setting('google_api_key', $GLOBALS['GMAPS_API_KEY']);
}
add_action('acf/init', 'cso_hq_acf_init');


/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_stylesheet_directory().'/inc/custom-post-types.php';


/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_stylesheet_directory().'/inc/school-functions.php';

/**
 * Handle the theme updater
 */
require get_stylesheet_directory().'/inc/updater.php';


