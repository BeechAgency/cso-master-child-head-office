<?php 
    extract($args);

    $item_colors = empty($item_colors) ? 'has-primary-dark-background-color has-white-color' : $item_colors;
    $background_colors = empty($background_colors) ? 'has-white-background-color has-primary-dark-color' : $background_colors;
?>
<!-- School Type -->
<section class="block cards-block__image-cards school-type-list <?= $background_colors ?>" id="rowSchoolType_<?= $school_type ?>" data-block-style="school-type-list <?= $school_type ?>" data-filter-active="true">
    <div class="xy-col text-wrapper" data-xy-col="12">
        <div class="xy-grid" id="schoolTypeList_<?= $school_type ?>">
            <div class="xy-col text-wrapper" data-xy-col="12">
                <div class="heading-group text-left">
                    <h2><?= $title ?></h2>
                </div>
            </div>
            <ul class="xy-col xy-grid card-wrapper list" data-xy-col="12">
                <?php 
                    $school_list = cso_hq_get_school_list_by_type( $school_type );

                    foreach($school_list as $school) {

                            get_template_part( "template-parts/parts/school-item", null, array(
                                'ID' => $school['postId'],
                                'class' => "xy-col card style-card $item_colors",
                                'wrapper' => 'li'
                            ));
                            
                    }
                ?>
            </ul> 
        </div>
    </div>
</section>