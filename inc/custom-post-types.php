<?php

// Register Custom Post Type
function cso_hq_register_school_post_type() {

	$labels = array(
		'name'                  => _x( 'Schools', 'Post Type General Name', 'cso_school' ),
		'singular_name'         => _x( 'School', 'Post Type Singular Name', 'cso_school' ),
		'menu_name'             => __( 'Schools', 'cso_school' ),
		'name_admin_bar'        => __( 'School', 'cso_school' ),
		'archives'              => __( 'School Archives', 'cso_school' ),
		'attributes'            => __( 'School Attributes', 'cso_school' ),
		'parent_item_colon'     => __( 'Parent Item:', 'cso_school' ),
		'all_items'             => __( 'All Schools', 'cso_school' ),
		'add_new_item'          => __( 'Add New School', 'cso_school' ),
		'add_new'               => __( 'Add School', 'cso_school' ),
		'new_item'              => __( 'New School', 'cso_school' ),
		'edit_item'             => __( 'Edit School', 'cso_school' ),
		'update_item'           => __( 'Update School', 'cso_school' ),
		'view_item'             => __( 'View School', 'cso_school' ),
		'view_items'            => __( 'View Schools', 'cso_school' ),
		'search_items'          => __( 'Search School', 'cso_school' ),
		'not_found'             => __( 'Not found', 'cso_school' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'cso_school' ),
		'featured_image'        => __( 'Featured Image', 'cso_school' ),
		'set_featured_image'    => __( 'Set featured image', 'cso_school' ),
		'remove_featured_image' => __( 'Remove featured image', 'cso_school' ),
		'use_featured_image'    => __( 'Use as featured image', 'cso_school' ),
		'insert_into_item'      => __( 'Insert into School', 'cso_school' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'cso_school' ),
		'items_list'            => __( 'Items list', 'cso_school' ),
		'items_list_navigation' => __( 'Items list navigation', 'cso_school' ),
		'filter_items_list'     => __( 'Filter items list', 'cso_school' ),
	);
	$rewrite = array(
		'slug'                  => 'schools',
		'with_front'            => false,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'School', 'cso_school' ),
		'description'           => __( 'Record that represents a school', 'cso_school' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'            => array( 'school_type', 'parish' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
        'menu_icon' => 'dashicons-location-alt',	
	);
	register_post_type( 'school', $args );

}
add_action( 'init', 'cso_hq_register_school_post_type', 0 );


// Register Custom Taxonomy
function cso_hq_generate_school_type_tax() {

	$labels = array(
		'name'                       => _x( 'School Types', 'Taxonomy General Name', 'cso_hq_school_type' ),
		'singular_name'              => _x( 'School Type', 'Taxonomy Singular Name', 'cso_hq_school_type' ),
		'menu_name'                  => __( 'School Types', 'cso_hq_school_type' ),
		'all_items'                  => __( 'All School Types', 'cso_hq_school_type' ),
		'parent_item'                => __( 'Parent School Type', 'cso_hq_school_type' ),
		'parent_item_colon'          => __( 'Parent School Type:', 'cso_hq_school_type' ),
		'new_item_name'              => __( 'New School Type Name', 'cso_hq_school_type' ),
		'add_new_item'               => __( 'Add New School Type', 'cso_hq_school_type' ),
		'edit_item'                  => __( 'Edit School Type', 'cso_hq_school_type' ),
		'update_item'                => __( 'Update School Type', 'cso_hq_school_type' ),
		'view_item'                  => __( 'View School Type', 'cso_hq_school_type' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'cso_hq_school_type' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'cso_hq_school_type' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'cso_hq_school_type' ),
		'popular_items'              => __( 'Popular Items', 'cso_hq_school_type' ),
		'search_items'               => __( 'Search Items', 'cso_hq_school_type' ),
		'not_found'                  => __( 'Not Found', 'cso_hq_school_type' ),
		'no_terms'                   => __( 'No items', 'cso_hq_school_type' ),
		'items_list'                 => __( 'Items list', 'cso_hq_school_type' ),
		'items_list_navigation'      => __( 'Items list navigation', 'cso_hq_school_type' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'school_type', array( 'school' ), $args );

}
add_action( 'init', 'cso_hq_generate_school_type_tax', 0 );

// Register Custom Taxonomy
function cso_hq_generate_parish_tax() {

	$labels = array(
		'name'                       => _x( 'Parishes', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Parish', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Parishes', 'text_domain' ),
		'all_items'                  => __( 'All Parishes', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Parish Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Parish', 'text_domain' ),
		'edit_item'                  => __( 'Edit Parish', 'text_domain' ),
		'update_item'                => __( 'Update Parish', 'text_domain' ),
		'view_item'                  => __( 'View Parish', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove parishes', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Parishes', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Items list', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'parish', array( 'school' ), $args );

}
add_action( 'init', 'cso_hq_generate_parish_tax', 0 );