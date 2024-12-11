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

            $school_list_data = cso_hq_get_school_list();
            ?>


<article id="post-<?php the_ID(); ?>" <?php post_class('school-search-page'); ?> >
	<div class="entry-content">
        <div class="school-search-container">
        <section class="block school-search form <?= $school_search_color.' '.$school_search_background_color ?>" id="row0" 
        data-block-style="school-search find-a-school">
            <div class="xy-grid">
                <div class="xy-col text-wrapper" data-xy-col="xl-6 lg-6 md-6 sm-12" data-xy-start="auto">
                    <?= apply_filters('the_content', $content); ?>

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
                </div>                   
            </div>
        </section>

        <section class="block school-section-nav <?= $school_list_color.' '.$school_list_background_color ?>" data-block-style="section-navigation">
            <nav>
                <b>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21 12L21 4H23V7L30 7V9L23 9V12H21ZM2 7H18V9H2V7ZM30 15V17H12V15H30ZM16 25V23H2V25H16ZM2 15H7L7 12H9L9 20H7V17H2V15ZM30 25H21V28H19L19 20H21V23H30V25Z" fill="#2b3990"/>
                    </svg>    
                    Filter By Age
                </b>
                <ul>
                    <?php 
                        foreach($school_types as $type):
                            $slug = $type->slug;
                            $name = $type->name;

                            //echo "<li><a class='btn btn-primary' href='#rowSchoolType_$slug'>$name</a></li>";
                        endforeach;
                    ?>
                    <li><a class="btn btn-primary active" data-school-type="all" href="#rowSchoolType_all">All</a></li>
                    <li><a class="btn btn-primary" data-school-type="k-to-12" href="#rowSchoolType_k-to-12">K–12</a></li>
                    <li><a class="btn btn-primary" data-school-type="primary" href="#rowSchoolType_primary">Primary</a></li>
                    <li><a class="btn btn-primary" data-school-type="secondary" href="#rowSchoolType_secondary">Secondary</a></li>
                </ul>
                <a href="#" class="clear-search" id="clearSearchButton">Clear Search</a>
            </nav>
        </section>
        <section class="block school-map has-primary-dark-color" data-block-style="school-map">
            <div class='search-results-message' id='searchResultsMessage' style=''>
                <span>Showing <span id="searchResultNumber"><?= count($school_list_data); ?></span> results</span>
                <button class="hide-map" id="hideMapButton">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.20435 3.99882L9.2514 3.99756L9.29715 3.99877C9.31959 4.00006 9.34207 4.00239 9.36453 4.0058L9.38061 4.0096C9.46602 4.02332 9.55089 4.05383 9.63179 4.10175L9.68366 4.13529L14.754 7.70056L19.821 4.13864C20.2887 3.80985 20.9225 4.10537 20.9954 4.64772L21.0023 4.7522V15.3847C21.0023 15.5941 20.915 15.7921 20.7642 15.9329L20.6837 15.9983L15.1837 19.8647C14.9168 20.0523 14.5958 20.0366 14.3558 19.8878L9.251 16.2966L4.18132 19.8613C3.71361 20.1901 3.07987 19.8946 3.00693 19.3523L3 19.2478V8.61523C3 8.40591 3.08735 8.20783 3.23812 8.06706L3.31868 8.00167L8.81868 4.13529C8.88268 4.0903 8.94979 4.057 9.01815 4.03442L9.14062 4.00642L9.20435 3.99882ZM19.5023 6.19621L15.5023 9.00812V17.8071L19.5023 14.9952V6.19621ZM8.5 6.19287L4.5 9.00478V17.8038L8.5 14.9918V6.19287ZM10.0023 6.19287V14.9918L14.0023 17.8038V9.00478L10.0023 6.19287Z" fill="currentColor"/>
                    </svg>

                    <span>Hide Map</span>
                </button>
            </div>
             <div class='results-message no-results-message' id='noSearchResults' style='display: none'>
                <h2>Sorry, no schools found for <span>XYZ</span></h2>
            </div>
            <div class="map" id="map">
            </div>
        </section>

        <!-- School List -->
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
        ?>
        <!-- End: School List -->
        </div> <!-- End: School Search Container -->
        <!-- Page Blocks Begin -->
        <?php
            include(get_template_directory() .'/template-parts/blocks.php'); ?>
        <!-- End: Page Blocks -->
    </div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->

<?php
		endwhile; // End of the loop.
	endif;
	?>
<script type="text/javascript">
    const school_locations = JSON.parse(`<?php echo json_encode($school_list_data); ?>`);
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GLOBALS['GMAPS_API_KEY']; ?>&libraries=places" async defer loading='async'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>

<?php 
    wp_enqueue_script( 'csohq-find-a-school', get_stylesheet_directory_uri() . '/js/find-a-school.js', array(), _S_VERSION, true );
?>
<?php
get_footer();
