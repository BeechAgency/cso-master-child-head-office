<?php 
    $footer_background_color = get_field('footer_background_color', 'option');
    $footer_auxiliary_background_color = get_field('footer_auxiliary_background_color', 'option');
    
    $school_details = get_school_details();

    $address = $school_details['school_address'];
    $school_phone = $school_details['school_phone'];
    $school_email = $school_details['school_email'];
    $school_social = $school_details['school_social'];
    $footer_text = get_field('footer_text', 'option');

    $term_dates = the_term_dates();

    $primary_schools = cso_hq_get_school_list_by_type('primary');
    $secondary_schools = cso_hq_get_school_list_by_type('secondary');
    $k_to_12_schools = cso_hq_get_school_list_by_type('k-to-12');
    $flexible_schools = cso_hq_get_school_list_by_type('flexible-learning-centres');
    
?>
<footer>
    <div class="xy-grid has-gutter <?= $footer_background_color ?> has-black-color">
        <div class="xy-col flow-columns" data-xy-col="xl-12 lg-12 md-12 sm-12">
            <h5>K–12 Schools</h5>
            <ul class="footer-school-list">
                <?= cso_hq_list_schools($k_to_12_schools); ?>
            </ul>

           <h5>Secondary Schools</h5>
            <ul class="footer-school-list">
                <?= cso_hq_list_schools($secondary_schools); ?>
            </ul>


            <h5>Primary Schools</h5>
            <ul class="footer-school-list">
                <?= cso_hq_list_schools($primary_schools); ?>
            </ul>


           <h5>Flexible Learning Centres</h5>
            <ul class="footer-school-list">
                <?= cso_hq_list_schools($flexible_schools); ?>
            </ul>
        </div>
        
    </div>

    <div class="xy-grid has-gutter <?= $footer_auxiliary_background_color ?>">
        <div class="xy-col" data-xy-col="xl-4 lg-4 md-4 sm-12">
            <?= conditionally_output_field(get_acf_image('logo','full','main','option'), '<div class="footer-logo">','</div>'); ?>
        </div>
        <div class="xy-col" data-xy-col="xl-8 lg-8 md-8 sm-12">
            <div class="xy-flex">
                <div class="xy-col" data-xy-col="6">
                    <?= conditionally_output_field($address, '<p class="address">', '</p>'); ?>
                </div>
                <div class="xy-col" data-xy-col="6">
                    <?= conditionally_output_field($school_phone, '<p class="phone">', '</p>'); ?>
                    <?= conditionally_output_field($school_email, '<p class="email">', '</p>'); ?>
                </div>
            </div>
            <div class="">
                <div class="term-dates">
                    <?php
                        foreach($term_dates as $index => $info) :
                            $term = $info['term'];
                            $text = $info['text'];

                            echo "<b>$term</b> $text <span class='pipe'>|</span>";
                        endforeach;
                    ?>
                </div>
                <?= conditionally_output_field(get_field('acknowledgement','option'), '<div class="footer-acknowledgement"><p class="small">', '</p></div>' ); ?>
            </div>
        </div>            
        <!--
        <div class="breadcrumbs xy-col" data-xy-col="xl-6 lg-6 md-6 sm-12">
            <?= the_breadcrumb(); ?>
        </div>
        -->
        <div class="xy-col" data-xy-col="12">
            <div class="xy-flex">
                <nav class="xy-col" data-xy-col="xl-9 lg-9 md-9 sm-12">
                    <?php csomaster_nav_location('footer'); ?>
                    <?php csomaster_nav_location('footer-auxiliary'); ?>
                </nav>
                <div class="nav xy-col" data-xy-col="xl-3 lg-3 md-3 sm-12">
                    <?php if($school_social) : ?>
                        <ul class="social">
                            <?php foreach($school_social as $social => $value) : 
                                if(empty($value)) continue; 
                                // get theme path
                                $theme_path = get_stylesheet_directory_uri();
                                ?>
                                <li>
                                    <a href="<?= $value ?>" target="_blank" class="<?= $social ?>"><?php /* ucfirst($social); */ echo "<img src='$theme_path/images/$social.svg' />"; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</footer>