<?php 
    $id =  empty($args['ID']) ? get_the_ID() : $args['ID'];
    $type = 'school';
    $wrapper = $args['wrapper'] ?? 'article';
    $class = $args['class'] ?? 'card';
    $grid = $args['grid'] ?? 'xl-4 lg-4 md-6 sm-12';

    $placeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="12.747" height="18.885" viewBox="0 0 12.747 18.885">
        <path id="pin_drop_FILL0_wght400_GRAD0_opsz48" d="M16.874,17.739a18.637,18.637,0,0,0,3.706-3.812,6.147,6.147,0,0,0,1.251-3.411,4.887,4.887,0,0,0-1.759-3.942,4.731,4.731,0,0,0-1.617-.885,5.381,5.381,0,0,0-1.582-.271,5.381,5.381,0,0,0-1.582.271,4.731,4.731,0,0,0-1.617.885,4.887,4.887,0,0,0-1.759,3.942,6.147,6.147,0,0,0,1.251,3.411A18.637,18.637,0,0,0,16.874,17.739Zm0,1.794a22.146,22.146,0,0,1-4.8-4.65,7.562,7.562,0,0,1-1.57-4.367,6.5,6.5,0,0,1,.578-2.821,6.4,6.4,0,0,1,1.5-2.042,6.265,6.265,0,0,1,2.054-1.239,6.293,6.293,0,0,1,4.485,0A6.265,6.265,0,0,1,21.17,5.652a6.4,6.4,0,0,1,1.5,2.042,6.5,6.5,0,0,1,.578,2.821,7.562,7.562,0,0,1-1.57,4.367,22.146,22.146,0,0,1-4.8,4.65Zm0-7.507a1.652,1.652,0,0,0,1.168-2.821,1.652,1.652,0,0,0-2.337,2.337A1.592,1.592,0,0,0,16.874,12.026ZM10.5,22.885V21.468H23.247v1.416ZM16.874,10.515Z" transform="translate(-10.5 -4)" fill="currentColor"/>
      </svg>';
    $typeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="17.271" height="14.131" viewBox="0 0 17.271 14.131">
        <path id="school_FILL0_wght400_GRAD0_opsz48" d="M10.616,20.131,4.924,17.01V12.3L2,10.71,10.616,6l8.655,4.71v6.222H18.094V11.4l-1.786.9v4.71Zm0-6.045L16.8,10.71,10.616,7.393,4.473,10.71Zm0,4.71L15.13,16.3v-3.3l-4.514,2.414L6.1,12.967V16.3ZM10.636,14.086ZM10.616,15.538ZM10.616,15.538Z" transform="translate(-2 -6)" fill="currentColor"/>
      </svg>';

    $location = get_field('school_location_name', $id);

    $types = get_the_terms($id, 'school_type');
    $school_type = $types[0]->name;

    switch ($school_type) {
        case "Primary Schools":
            $school_type_display = 'Primary';
            break;
        case "Secondary Schools":
        case "High Schools":
            $school_type_display = 'Secondary';
            break;
        case "Flexible Learning Centres":
            $school_type_display = 'Flexible';
            break;
        default:
            $school_type_display = $school_type;
            break;
    }

    $image_id = get_post_thumbnail_id($id);

?>
<<?=$wrapper ?> class="school-card <?= $class ?>" <?= !empty($grid) ? 'data-xy-col="'.$grid.'"' : ''; ?> data-school-id="<?= $id; ?>" data-school-geo="NSW">
    <a href="<?= get_the_permalink($id); ?>" class='schoollink'><?php if( has_post_thumbnail($id) || $type === 'page' ) { echo wp_get_attachment_image($image_id,'large', false, array('class'=>'schoolimage schoolimageset dataschoolimageset')); } ?></a>
    <div class="card-content">
        <h6 class="card-date has-primary-light-color">
            <?php 
                echo "<span>$placeIcon <span class='location'>$location</span></span><span>$typeIcon <span class='type'>$school_type_display</span></span>";
            ?>
        </h6>
        <h4 class="card-title"><a href="<?= get_the_permalink($id); ?>" class='school_name'><?= get_field('school_location_name', $id); ?>, <?= get_the_title($id); ?></a></h4>

        <a href="<?= get_the_permalink($id); ?>" class="btn btn-primary schoolbtn">Read More</a>
    </div>
    
</<?= $wrapper ?>>