<?php
/**
 * Template Name: School List Page
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cso-hq
 */

get_header();

?>
	<?php
	if( have_posts() ) :
		while ( have_posts() ) :
			the_post(); 
            
            $school_types = get_field('school_types_to_display');
            $content = get_field('find_a_school_content');
            $school_search_color = get_field('school_search_color');
            $school_search_background_color = get_field('school_search_background_color');
            $school_list_color = get_field('school_list_color');
            $school_list_background_color = get_field('school_list_background_color');
            $school_card_color = get_field('school_card_color');
            $school_card_background_color = get_field('school_card_background_color');
            ?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
	<div class="entry-content">
        <section class="block text-block__text form <?= $school_search_color.' '.$school_search_background_color ?>" id="row0" 
        data-block-style="text-block__text find-a-school">
            <div class="xy-grid">
                <div class="xy-col text-wrapper" data-xy-col="xl-6 lg-6 md-6 sm-12" data-xy-start="auto">
                    <?= apply_filters('the_content', $content); ?>
                </div>
                <div class="xy-col" data-xy-col="xl-6 lg-6 md-6 sm-12" data-xy-start="xl-auto lg-auto md-auto sm-auto">
                    <form class="search-form find-a-school">
                        <label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24.387" height="24.831" viewBox="0 0 24.387 24.831" class="search-icon">
                                <g id="Group_151" data-name="Group 151" transform="translate(-1103.17 -101)">
                                    <g id="Ellipse_4" data-name="Ellipse 4" transform="translate(1103.17 101)" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="8.568" cy="8.568" r="8.568" stroke="none"/>
                                    <circle cx="8.568" cy="8.568" r="7.818" fill="none"/>
                                    </g>
                                    <path id="Path_404" data-name="Path 404" d="M1959,881l9.558,9.558" transform="translate(-841.531 -765.258)" fill="none" stroke="currentColor" stroke-width="1.5"/>
                                </g>
                            </svg>
                            <input type="search" class="search-field" placeholder="Enter keywords..." id="schoolPageSearch" />
                            <button class="search-submit" id="schoolPageSearchSubmit">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="38.453" height="29.921" viewBox="0 0 38.453 29.921">
                                    <defs>
                                        <clipPath id="clip-path">
                                        <rect id="Rectangle_124" data-name="Rectangle 124" width="38.1" height="27.007" transform="translate(0 0)" fill="none" stroke="rgba(0,0,0,0)" stroke-width="1.5"/>
                                        </clipPath>
                                    </defs>
                                    <g id="Group_295" data-name="Group 295" transform="translate(38.1 28.464) rotate(180)">
                                        <path id="Path_481" data-name="Path 481" d="M.707,13.5l13.15,13.15V.354Z" fill="none" stroke="rgba(0,0,0,0)" stroke-width="1.5"/>
                                        <g id="Group_291" data-name="Group 291">
                                        <g id="Group_290" data-name="Group 290" clip-path="url(#clip-path)">
                                            <path id="Path_482" data-name="Path 482" d="M13.857.354.707,13.5l13.15,13.15" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="1.5"/>
                                        </g>
                                        </g>
                                        <path id="Path_483" data-name="Path 483" d="M.707,13.5h0Z" fill="none" stroke="rgba(0,0,0,0)" stroke-width="1.5"/>
                                        <g id="Group_293" data-name="Group 293">
                                        <g id="Group_292" data-name="Group 292" clip-path="url(#clip-path)">
                                            <line id="Line_6" data-name="Line 6" x1="37.393" transform="translate(0.707 13.504)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="1.5"/>
                                        </g>
                                        </g>
                                    </g>
                                </svg>
                            </button>
                        </label>
                    </form>
                    <div>
                        <p>Filter schools by type</p>
                        <ul class="school-type-filters">
                        <?php foreach($school_types as $type):
                                $slug = $type->slug;
                                $name = $type->name;

                                echo "<li><button data-filter-type='$slug' class='btn btn-primary'>$name</a></li>";
                            endforeach;
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <section class="block school-section-nav <?= $school_list_color.' '.$school_list_background_color ?>" data-block-style="section-navigation">
            <nav>
                <b>Jump to section</b>
                <ul>
                    <?php 
                        foreach($school_types as $type):
                            $slug = $type->slug;
                            $name = $type->name;

                            echo "<li><a href='#rowSchoolType_$slug'>$name</a></li>";
                        endforeach;
                    ?>
                </ul>
            </nav>
            <div class='no-results-message' id='noSearchResults' style='display: none'>
                <h2>Sorry, no schools found for <span></span></h2>
            </div>
        </section>
        <?php
            foreach($school_types as $type):

                $slug = $type->slug;
                $name = $type->name;

                get_template_part( "template-parts/blocks/schools/block__school-type-grid", null, 
                    array(
                        'school_type' => $slug,
                        'item_colors' => $school_card_color.' '.$school_card_background_color,
                        'background_colors' => $school_list_color.' '.$school_list_background_color,
                        'title' => $name,
                    ) 
                ); 
            endforeach;
            
            include(get_template_directory() .'/template-parts/blocks.php'); ?>

    </div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->

<?php
		endwhile; // End of the loop.
	endif;
	?>

<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
<?php 
    wp_enqueue_script( 'csohq-find-a-school', get_stylesheet_directory_uri() . '/js/find-a-school.js', array(), _S_VERSION, true );
?>
<?php
get_footer();
