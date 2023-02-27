<?php 
    $fields = $args;

    $card_background_color = !empty($fields['background_color_cards']) ? $fields['background_color_cards'] : get_sub_field('_background_color_cards');

    $school_list = get_sub_field('_schools');

    $card_bg = get_sub_field('_background_color_card');
    $card_text = get_sub_field('_text_color_card');

    $item_colors = '';

?>

<div class="xy-col text-wrapper" data-xy-col="12">
    <div class="heading-group">
        <?= apply_filters('the_content', $fields['content']); ?>
    </div>
</div>

<ul class="xy-col xy-grid card-wrapper list school-list" data-xy-col="12">
    <?php 
        foreach($school_list as $school) {
            get_template_part( "template-parts/parts/school-item", null, array(
                'ID' => $school->ID,
                'class' => "xy-col card style-card $card_bg $card_text",
                'wrapper' => 'li'
            ));
        }
    ?>
</ul> 