<?php

function csomaster_set_custom_header_data() {

    $header_data = get_header_data();

    $externalIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="12.225" height="12.225" viewBox="0 0 12.225 12.225">
    <path id="open_in_new_FILL0_wght400_GRAD0_opsz48" d="M7.019,18.225A1.044,1.044,0,0,1,6,17.206V7.019A1.044,1.044,0,0,1,7.019,6h4.737V7.019H7.019V17.206H17.206V12.469h1.019v4.737a1.045,1.045,0,0,1-1.019,1.019Zm3.43-3.718-.713-.73,6.757-6.757H12.774V6h5.45v5.45H17.206v-3.7Z" transform="translate(-6 -6)" fill="currentColor"/>
  </svg>';

    if(get_post_type() === 'school') {
        $header_data['header_background_color'] = 'has-black-background-color';
        $header_data['header_text_color'] = 'has-white-color';

        $header_data['header_text'] = get_field('school_byline'); 
        $header_data['header_text_alignment'] = 'text-center';
        $header_data['header_style'] = 'full';
        $header_data['header_gradient'] = true;

        $header_data['header_image'] = get_post_thumbnail_id();
        $header_data['header_image_mobile'] = null;

        $cta1 = get_field('school_url');
        $cta2 = get_field('school_tour_url'); 

        if(!empty($cta2)) {
            $cta2['url'] .= "?cso_school=".get_field('school_email');
        }

        $header_data['header_text_cta'] = !empty($cta1) ? array('text' => $cta1['title'].$externalIcon, 'link' => $cta1['url'], 'classes' => 'btn-primary has-external-icon') : null; // Group with two properties: text and link
        $header_data['header_text_cta_secondary'] = !empty($cta2) ? array('text' => $cta2['title'], 'link' => $cta2['url'], 'classes' => 'btn-primary') : null; // Group with two properties: text and link

    } 

    return $header_data;
}

//add_action('csomaster_custom_header_data', 'cso_hq_get_school_header');



function cso_hq_school_header_section() {
    if(get_post_type() === 'school') {
        $data = get_school_data();
        $location = $data['school_location_name'];
        $type = $data['school_type'];
        
        $placeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="12.747" height="18.885" viewBox="0 0 12.747 18.885">
        <path id="pin_drop_FILL0_wght400_GRAD0_opsz48" d="M16.874,17.739a18.637,18.637,0,0,0,3.706-3.812,6.147,6.147,0,0,0,1.251-3.411,4.887,4.887,0,0,0-1.759-3.942,4.731,4.731,0,0,0-1.617-.885,5.381,5.381,0,0,0-1.582-.271,5.381,5.381,0,0,0-1.582.271,4.731,4.731,0,0,0-1.617.885,4.887,4.887,0,0,0-1.759,3.942,6.147,6.147,0,0,0,1.251,3.411A18.637,18.637,0,0,0,16.874,17.739Zm0,1.794a22.146,22.146,0,0,1-4.8-4.65,7.562,7.562,0,0,1-1.57-4.367,6.5,6.5,0,0,1,.578-2.821,6.4,6.4,0,0,1,1.5-2.042,6.265,6.265,0,0,1,2.054-1.239,6.293,6.293,0,0,1,4.485,0A6.265,6.265,0,0,1,21.17,5.652a6.4,6.4,0,0,1,1.5,2.042,6.5,6.5,0,0,1,.578,2.821,7.562,7.562,0,0,1-1.57,4.367,22.146,22.146,0,0,1-4.8,4.65Zm0-7.507a1.652,1.652,0,0,0,1.168-2.821,1.652,1.652,0,0,0-2.337,2.337A1.592,1.592,0,0,0,16.874,12.026ZM10.5,22.885V21.468H23.247v1.416ZM16.874,10.515Z" transform="translate(-10.5 -4)" fill="currentColor"/>
      </svg>';
        $typeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="17.271" height="14.131" viewBox="0 0 17.271 14.131">
        <path id="school_FILL0_wght400_GRAD0_opsz48" d="M10.616,20.131,4.924,17.01V12.3L2,10.71,10.616,6l8.655,4.71v6.222H18.094V11.4l-1.786.9v4.71Zm0-6.045L16.8,10.71,10.616,7.393,4.473,10.71Zm0,4.71L15.13,16.3v-3.3l-4.514,2.414L6.1,12.967V16.3ZM10.636,14.086ZM10.616,15.538ZM10.616,15.538Z" transform="translate(-2 -6)" fill="currentColor"/>
      </svg>';

        echo "<div class='school-details'><span class='location'>$placeIcon $location</span><span class='type'>$typeIcon $type</span></div>";
    }
}
    
add_action('csomaster_before_custom_header_text_layout', 'cso_hq_school_header_section');


function get_school_data( $pageId = null ) {
    $school_data = array();

    $fields = array(
        'school_principal_name',
        'school_gallery', 
        'school_gallery_caption', 
        'school_principle_text', 
        'school_nearby_schools', 
        'school_location_name', 
        'school_byline', 
        'school_url',
        'school_tour_url',
        'school_email', 
        'school_phone', 
        'school_map_location', 
        'school_address', 
        'school_mailing_address', 
        'school_annual_report', 
        'school_news_endpoint',
        'school_oosh_text',
        'school_crest_alignment'
    );

    foreach( $fields as $field ) {
        $school_data[$field] = get_field($field, $pageId);
    } 

    $school_data['school_principle_image'] = get_acf_image('school_principle_image', 'full', 'main', $pageId);
    $school_data['school_oosh_image'] = get_acf_image('school_oosh_image', 'full', 'main', $pageId);
    $school_data['school_crest'] = get_acf_image('school_crest', 'full', 'main', $pageId);

    //var_dump($school_data);

    // Parish data
    $parishes = get_the_terms(null, 'parish');
    $parish = $parishes[0];

    $parish_id = $parish->term_taxonomy_id;

    $school_data['parish'] = get_parish_data($parish_id);


    $types = get_the_terms(null, 'school_type');
    $school_data['school_type'] = $types[0]->name;

    

    return $school_data;
}

function get_parish_data( $tax_id = null ) {
    if($tax_id === null) return false;

    $acf_tax_id = 'term_'.$tax_id;
    $term = get_term_by('term_taxonomy_id', $tax_id, 'parish');

    $parish = array();
    $parish['name'] = $term->name;
    $parish['slug'] = $term->slug;
    $parish['description'] = $term->description;

    $parish['image'] = get_acf_image('parish_featured_image', 'full', 'main', $acf_tax_id);
    $parish['content']= get_field('parish_content', $acf_tax_id);
    $parish['cta']= get_field('parish_cta', $acf_tax_id);

    return $parish;
}

// Custom comparison function
function cso_hq_compare_by_display_name($a, $b) {
    return strcmp($a['display_name'], $b['display_name']);
}

function cso_hq_get_school_list_by_type( $type = null ) {
    $output = array();
    $postType = 'school';
    $numberOfPosts	= 72;

    $postOptions = array();

    $args = array(
        'posts_per_page' => $numberOfPosts,
        'post_type' => $postType,
        'order'=>'ASC',
        'orderby'=>'title'
    );

    if(!empty($type)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'school_type',
                'field' => 'slug',
                'terms' => $type
            )
        );
    }

    $schoolPosts = new WP_Query(
        $args
    );

    if( $schoolPosts->have_posts() ) : 
        while( $schoolPosts->have_posts() ): $schoolPosts->the_post();

            $imageId = get_post_thumbnail_id(null, 'large');
            $imageSrcSet = wp_get_attachment_image_srcset($imageId, 'large');
            $imageSrc = wp_get_attachment_image_src($imageId, 'large');

            
            $output[] = array(
                'name' => get_the_title(),
                'link' => get_the_permalink(),
                'type' => $type,
                'postId' => get_the_ID(),
                'imageId' => $imageId,
                'imageSrcSet' => $imageSrcSet,
                'imageSrc'=> $imageSrc[0],
                'location' => get_field('school_location_name'),
                'display_name' =>  get_field('school_location_name') .', '. get_the_title()
            );

        endwhile;
    endif;
    wp_reset_postdata();

    usort($output, 'cso_hq_compare_by_display_name');

    return $output;
}

function cso_hq_list_schools( $list = array() ) {
    foreach( $list as $school ) {
        extract($school);

        echo "<li class='school-list-item type-$type'><a href='$link'>$display_name</a></li>";
    }
}


/** 
 * Dynamically create list in grav forms of the schools
 */

/* Add gforms predefined choices */
add_filter( 'gform_predefined_choices', 'st_nicks_add_predefined_choice' );
function st_nicks_add_predefined_choice( $choices ) {
   $schools = get_school_email_list();

   $choices['School List'] = $schools;

   return $choices;
}


function get_school_email_list() {
    $args = array(
        'post_type' => 'school', // Replace with your custom post type name
        'posts_per_page' => -1,
        'orderby' => 'title', 
        'order' => 'ASC',      
    );
    
    $query = new WP_Query($args);
    
    $schools = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $title = get_the_title();
            $email = get_field('school_email', get_the_ID());
            $location = get_field('school_location_name', get_the_ID());

            $schools[] = "$location, $title|$email";
            /*$schools[] = array(
                'title' => $title,
                'email' => $email
            );*/
        }
    
        wp_reset_postdata(); // Restore the original post data
    }
    
    sort($schools);

    return $schools;
}