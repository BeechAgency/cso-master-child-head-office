<?php
    $school_data = get_school_data();

    $gallery_fields = array();
    $gallery_fields['image'] = null;
    $gallery_fields['position'] = null;
    $gallery_fields['gallery_ids'] = $school_data['school_gallery'];
    $gallery_fields['caption'] = $school_data['school_gallery_caption'];

    $image_text_fields = array();

    $image_text_fields['title'] = null;
    $image_text_fields['subtitle'] = null;
    $image_text_fields['cta'] = null;
    $image_text_fields['cta_secondary'] = null;
    
    $image_text_fields['content'] = $school_data['school_principle_text'];
    $image_text_fields['image'] = $school_data['school_principle_image'];
    $image_text_fields['position'] = 'image-right';

    $text_class_list = 'text-block__text basic has-transparent-background-color has-black-color';


    $image_text_fields_oosh = array();

    $image_text_fields_oosh['title'] = null;
    $image_text_fields_oosh['subtitle'] = null;
    $image_text_fields_oosh['cta'] = null;
    $image_text_fields_oosh['cta_secondary'] = null;
    
    $image_text_fields_oosh['content'] = $school_data['school_oosh_text'];
    $image_text_fields_oosh['image'] = $school_data['school_oosh_image'];
    $image_text_fields_oosh['position'] = 'image-right';



    $text_fields = array();

    $text_fields['style'] = 'columns';
    $text_fields['content'] = get_the_content();
    $text_fields['subtitle'] = null;
    $text_fields['title'] = null;
    $text_fields['cta'] = null;

    $lat;
    $lng;
    if(!empty($school_data['school_map_location'])):
        $lat = $school_data['school_map_location']['lat'];
        $lng = $school_data['school_map_location']['lng'];
    endif;

    $class_list = '';

    $theme_path = get_stylesheet_directory_uri();

    $nearby_schools = get_field('school_nearby_schools');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <!-- Flexible text with crest -->
	<section class="block text-block__text columns  has-white-background-color  has-primary-dark-color" id="rowContent" data-block-style="text-block__text columns">
		<div class="xy-grid">
            <div class="xy-col xy-grid text-column-wrapper" data-xy-col="12">
                <div class="xy-col text-column school-details <?= $school_data['school_crest_alignment'] ?>" data-xy-col="xl-4 lg-4 md-6 sm-12" data-xy-start="auto">
                    <?= $school_data['school_crest']; ?>
                    <h3><?= the_title(); ?>, <span class="location"><?= $school_data['school_location_name']; ?></span></h3>
                </div>
                            
                <div class="xy-col text-column" data-xy-col="xl-6 lg-6 md-6 sm-12" data-xy-start="xl-7 lg-7 md-auto sm-auto">
                    <?php the_content(); ?>
                </div>
            </div>
		</div>
	</section>

    <!-- Gallery -->
    <?php if(!empty($gallery_fields['gallery_ids'])) : ?>
	<section class="block images-block__images has-primary-dark-background-color has-white-color" id="rowGallery" data-block-style="image-gallery">
		<div class="xy-grid">
			<?php get_template_part( "template-parts/blocks/images/block__images", null, $gallery_fields ); ?>
		</div>
	</section>
    <?php endif; ?>

    <!-- School Info -->
	<section class="block text-block__text columns  has-primary-light-background-color  has-black-color" id="rowDetails" data-block-style="school-info">
		<div class="xy-grid">
            <div class="xy-col xy-grid text-column-wrapper" data-xy-col="12">
                <div class="xy-col text-column school-details" data-xy-col="xl-6 lg-6 md-6 sm-12" data-xy-start="auto">
                    <?php if(!empty($school_data['school_map_location'])): ?>
                    <iframe
                        width="100%"
                        height="650"
                        frameborder="0" style="border:0"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps/embed/v1/view?key=<?= $GLOBALS['GMAPS_API_KEY'] ?>&center=<?= $lat ?>,<?= $lng ?>&zoom=18"
                        allowfullscreen>
                    </iframe>
                    <?php endif; ?>
                </div>
                <div class="xy-col text-column school-details-right" data-xy-col="xl-6 lg-6 md-6 sm-12" data-xy-start="auto">
                    <h2>School Info</h2>
                    
                    <?= conditionally_output_field($school_data['school_email'], '<h4>Contact</h4><p class="details email"><a href="mailto:'.$school_data['school_email'].'"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="7.5" viewBox="0 0 10 7.5">
  <g id="icon_mail" transform="translate(0 -8)">
    <path id="Path_492" data-name="Path 492" d="M9.375,8H.625A.625.625,0,0,0,0,8.625V9.674l5,2,5-2V8.625A.625.625,0,0,0,9.375,8ZM0,10.695v4.18a.625.625,0,0,0,.625.625h8.75A.625.625,0,0,0,10,14.875v-4.18l-5,2Z" fill="#000000"/>
  </g>
</svg>
', '</a></p>'); ?>
                    <?= conditionally_output_field($school_data['school_phone'], '<p class="details phone"><a href="tel:'.$school_data['school_phone'].'"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="9.994" viewBox="0 0 10 9.994">
  <g id="icon_phone" transform="translate(-1.326 -1.334)">
    <path id="Path_491" data-name="Path 491" d="M5.377,3.627A3.094,3.094,0,0,0,3.619,1.365a.332.332,0,0,0-.2-.027c-1.8.3-2.074,1.349-2.085,1.392a.338.338,0,0,0,.007.184c2.151,6.673,6.62,7.91,8.09,8.316.113.031.206.057.277.08a.325.325,0,0,0,.1.016.337.337,0,0,0,.141-.031,2.761,2.761,0,0,0,1.366-2.152.34.34,0,0,0-.033-.209A3.232,3.232,0,0,0,9.17,7.461a.333.333,0,0,0-.3.072,4.553,4.553,0,0,1-1.447.9A6.425,6.425,0,0,1,4.356,5.319a3.654,3.654,0,0,1,.933-1.431A.34.34,0,0,0,5.377,3.627Z" transform="translate(0)" fill="#000000"/>
  </g>
</svg>
', '</a></p>'); ?>

                    <?= conditionally_output_field($school_data['school_address'], '<h4>Address</h4><p class="details address">', '</p>'); ?>
                    <?= conditionally_output_field($school_data['school_mailing_address'], '<p class="details mailing_address">', '</p>'); ?>

                    <?= conditionally_output_field($school_data['school_principal_name'], '<h4>Principal</h4><p class="details principal_name">', '</p>'); ?>

                    <?= conditionally_output_field($school_data['parish']['name'], '<h4>Parish</h4><p class="details parish">', '</p>'); ?>

                    <div class="school-btn-row">
                        <?php if(!empty($school_data['school_url'])) : ?>
                        <a href="<?= $school_data['school_url']['url']; ?>" class="btn btn-primary">
                            <?= $school_data['school_url']['title'] ?> <img src="<?= "$theme_path/images/external.svg" ?>" class="icon" />
                        </a>
                        <?php endif; ?>
                        <?php if(!empty($school_data['school_tour_url'])) : ?>
                        <a href="<?= $school_data['school_tour_url']['url']."?cso_school=".$school_data['school_email']; ?>" class="btn btn-primary"> 
                            <?= $school_data['school_tour_url']['title']; ?>
                        </a>
                        <?php endif; ?>

                    </div>
                    <p class="details download"><a href="<?= wp_get_attachment_url($school_data['school_annual_report']); ?>" download target="_blank"><img src="<?= "$theme_path/images/download.svg" ?>" class="icon" />Download Our Latest Annual Report</a></p>
                </div>
            </div>
		</div>
	</section>

    <!-- Text and Image // PRINCIPLE -->
    <?php if(!empty($image_text_fields['content'])) : ?>
	<section class="block text-block__image_text image-inset image-right has-white-background-color has-primary-dark-color" id="rowContent" data-block-style="principal-content">
		<div class="xy-grid">
			<?php get_template_part( "template-parts/blocks/text/block__image_text", null, $image_text_fields ); ?>
		</div>
	</section>
    <?php endif; ?>


    <!-- Text and Image // OOSH -->
    <?php if(!empty($image_text_fields_oosh['content'])) : ?>
	<section class="block text-block__image_text image-inset image-right has-white-background-color has-primary-dark-color" id="rowContent" data-block-style="oosh-content">
		<div class="xy-grid">
			<?php get_template_part( "template-parts/blocks/text/block__image_text", null, $image_text_fields_oosh ); ?>
		</div>
	</section>
    <?php endif; ?>

    <!-- Parish Info -->
    <?php 
        if(!empty($school_data['parish'])): 
            $parish = $school_data['parish'];
    ?>
	<section class="block text-block__image_text image-inset image-left has-white-color has-primary-dark-background-color has-special-gradient-top" id="rowContent" data-block-style="parish-information">
		<div class="xy-grid">
            <?php if (!empty($parish['image'])): ?>
			 <div class="xy-col image-wrapper type-image" data-xy-col="xl-6 lg-6 md-12">
                <?= $parish['image']; ?>
            </div>
            <?php endif; ?>
            <div class="xy-col text-wrapper<?= empty($parish['image']) ? ' text-center' : ''?>" data-xy-col="<?= empty($parish['image']) ? 'xl-10 lg-10 md-12' : 'xl-4 lg-5 md-12'; ?>" data-xy-start="<?= empty($parish['image']) ? 'xl-2 lg-2 md-auto sm-auto' : 'xl-8 lg-8 md-auto sm-auto' ?> " data-xy-items="align-center">
                <h5 class="has-primary-light-color">Associated Parish</h5>    
                <h2>Parish of <?= $parish['name']; ?></h2>   
                
                <?= !empty($parish['content']) ? apply_filters('the_content', $parish['content']) : ''; ?>

                <?= !empty($parish['cta']) ? do_a_cta(array(
                    'link' => $parish['cta']['url'],
                    'text' => $parish['cta']['title']
                )) : ''; ?>
            </div>
		</div>
	</section>
    <?php endif; ?>

    <!-- News -->
    <?php if(null === true): ?>
	<section class="block cards-block__article-list has-white-color has-secondary-light-background-color" id="rowContent" data-block-style="school-news">
		<div class="xy-grid">
			<?php 
            get_template_part( "template-parts/blocks/cards/block__article-list", null, 
                array(
                    'title_alignment' => 'text-left',
                    'posts_type' => 'list',
                    'background_color_cards' => 'has-primary-dark-background-color has-white-color',
                    'title' => '<h5>Stay up to date</h5><h2>The latest from '.get_the_title().'</h2>',
                    'style' => null
                ) 
            ); ?>
		</div>
    </section>
    <?php endif; ?>
    <!-- Nearby Schools -->
    <?php if(!empty($nearby_schools)): ?>
	<section class="block cards-block__image-cards has-white-background-color has-primary-dark-color" id="rowContent" data-block-style="nearby-schools">
        <div class="xy-col text-wrapper" data-xy-col="12">
            <div class="xy-grid">
                <div class="xy-col text-wrapper" data-xy-col="12">
                    <div class="heading-group text-left">
                        <h2>Nearby Schools</h2>
                    </div>
                </div>
                <div class="xy-col xy-grid card-wrapper" data-xy-col="12">
                    <?php 
                        // Get the schools
                        foreach($nearby_schools as $school) {
                            $school_ID = $school->ID;

                            get_template_part( "template-parts/parts/school-item", null, array(
                                'ID' => $school_ID,
                                'class' => 'xy-col card style-card has-primary-dark-background-color has-white-color'
                            ));
                        }
                    ?>
                </div> 
                <div class="xy-col text-center" data-xy-col="12">
                    <div class="button-row">
                        <a href="/schools" class="btn btn-secondary">See more schools</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->