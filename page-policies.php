<?php
/**
 * Template Name: Policy Search Page
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
            
            // Get policy data
            $school_search_color = 'has-white-color';
            $school_search_background_color = 'has-primary-dark-background-color';

            $search_content = get_field('search_content') ?? 'Search Policies';
            $search_preamble = get_field('search_preamble') ?? 'Search for policies by keyword or category.';

            $policies = get_field('policies', 'options');
            ?>


<article id="post-<?php the_ID(); ?>" <?php post_class('policy-search-page'); ?> >
	<div class="entry-content">
        <div class="policy-search-container">
            <section class="block policy-search form <?= $school_search_color.' '.$school_search_background_color ?>" id="row0" 
            data-block-style="policy-search find-a-policy">
                <div class="xy-grid">
                    <div class="xy-col text-wrapper" data-xy-col="xl-6 lg-6 md-6 sm-12" data-xy-start="auto">
                        <?= apply_filters('the_content', $search_content); ?>

                        <form class="search-form find-a-policy">
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
                                <input type="search" class="search-field" placeholder="Enter keyword..." id="policyPageSearch" />
                                <button class="search-submit" id="policyPageSearchSubmit">
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
                        <span class="policy-results-count">Search Results <span id="searchResultsCount"></span></span>
                    </div>                   
                </div>
            </section>

            <section class="block policy-list has-primary-dark-color" id="row1">
                <!-- Policy List -->
                 <div id="policySearchContainer">
                    <ul class="policy-list policy-search-list list" id="policyList">
                    <?php
                        $policy_index = 0;
                        foreach($policies as $policy_group): 

                            $policy = $policy_group['policy'];
                            $type = $policy_group['type'];

                            $file = $policy['file'];

                            //if(empty($file)) continue;

                            $caption = $type === 'file' ? $file['caption'] : $policy['caption'];
                            $policy_url = $type === 'file' ?  $file['url'] : $policy['link'];
                            $description = $type === 'file'? $file['description'] : $policy['description'];
                            $file_name =  $type === 'file' ? $file['filename'] : '';
                            $title = $policy_group['title'];
                            $year = $policy_group['year'];

                            if($type === 'file') {
                                $title = !empty($policy_group['title']) ? $policy_group['title'] : $file['title'];
                                $year = !empty($policy_group['year']) ? $policy_group['year'] : substr($file['date'], 0, 4);
                            }


                           

                            $icon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.5 4.5C4.5 2.84315 5.84315 1.5 7.5 1.5H12.8787C13.4754 1.5 14.0477 1.73705 14.4697 2.15901L18.841 6.53033C19.2629 6.95229 19.5 7.52458 19.5 8.12132V19.5C19.5 21.1569 18.1569 22.5 16.5 22.5H7.5C5.84315 22.5 4.5 21.1569 4.5 19.5V4.5ZM7.5 3C6.67157 3 6 3.67157 6 4.5V19.5C6 20.3284 6.67157 21 7.5 21H16.5C17.3284 21 18 20.3284 18 19.5V9H14.25C13.0074 9 12 7.99264 12 6.75V3H7.5ZM14.25 7.5H17.6893L13.5 3.31066V6.75C13.5 7.16421 13.8358 7.5 14.25 7.5ZM8.25 12C7.83579 12 7.5 12.3358 7.5 12.75C7.5 13.1642 7.83579 13.5 8.25 13.5H15.75C16.1642 13.5 16.5 13.1642 16.5 12.75C16.5 12.3358 16.1642 12 15.75 12H8.25ZM7.5 15.75C7.5 15.3358 7.83579 15 8.25 15H15.75C16.1642 15 16.5 15.3358 16.5 15.75C16.5 16.1642 16.1642 16.5 15.75 16.5H8.25C7.83579 16.5 7.5 16.1642 7.5 15.75ZM8.25 18C7.83579 18 7.5 18.3358 7.5 18.75C7.5 19.1642 7.83579 19.5 8.25 19.5H15.75C16.1642 19.5 16.5 19.1642 16.5 18.75C16.5 18.3358 16.1642 18 15.75 18H8.25Z" fill="currentColor"/></svg>';

                            echo "<li 
                                class='policy-item' 
                                data-index='$policy_index'
                                data-description='$description'
                                data-caption='$caption'
                                data-file-name='$file_name'
                                data-title='$title'
                                data-year='$year'
                            >";
                            echo "  <a href='$policy_url' target='_blank'>
                                <span class='icon'>$icon</span>
                                <span class='title'>$title </span>
                                <span class='year'>$year</span>
                                </a>";
                            echo "</li>";
                            
                            $policy_index++;
                        endforeach;
                    ?>
                    </ul>
                </div>
                <!-- End: Policy List -->
             </section>
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

<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
<?php 
    wp_enqueue_script( 'csohq-policy-search', get_stylesheet_directory_uri() . '/js/policy-search.js', array(), _S_VERSION, true );
?>
<?php
get_footer();
