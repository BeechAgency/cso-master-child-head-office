<?php 
    $fields = $args;

    $school_list_data = cso_hq_get_school_list();
?>
<div class="xy-col school-mobile-heading" data-xy-col="12" data-xy-items="align-start">
    <?= apply_filters('the_content', $fields['content']); ?>
</div>
<div class="xy-col school-section-nav school-mobile-filters" data-xy-col="12" data-xy-items="align-start">
    <button class="mobile-filter-toggle" id="mobileFilterToggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M21 12L21 4H23V7L30 7V9L23 9V12H21ZM2 7H18V9H2V7ZM30 15V17H12V15H30ZM16 25V23H2V25H16ZM2 15H7L7 12H9L9 20H7V17H2V15ZM30 25H21V28H19L19 20H21V23H30V25Z" fill="currentColor"/>
        </svg>
        Filter Results
    </button>
    <nav>
        <div class="mobile-filter-header">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M21 12L21 4H23V7L30 7V9L23 9V12H21ZM2 7H18V9H2V7ZM30 15V17H12V15H30ZM16 25V23H2V25H16ZM2 15H7L7 12H9L9 20H7V17H2V15ZM30 25H21V28H19L19 20H21V23H30V25Z" fill="currentColor"/>
                </svg>    
                Filters
            </div>
            <button class="mobile-filter-close" id="closeFiltersButton">
                <svg xmlns="http://www.w3.org/2000/svg" width="14.884" height="14.885" viewBox="0 0 14.884 14.885">
                    <g id="Group_274" data-name="Group 274" transform="translate(-1203.591 -91.023)">
                        <path id="Path_474" data-name="Path 474" d="M0,0H19.55" transform="translate(1204.121 91.554) rotate(45)" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        <path id="Path_477" data-name="Path 477" d="M0,0H19.55" transform="translate(1217.945 91.554) rotate(135)" fill="none" stroke="currentColor" stroke-width="1.5"/>
                    </g>
                </svg>
            </button>
        </div>
        <b>
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M21 12L21 4H23V7L30 7V9L23 9V12H21ZM2 7H18V9H2V7ZM30 15V17H12V15H30ZM16 25V23H2V25H16ZM2 15H7L7 12H9L9 20H7V17H2V15ZM30 25H21V28H19L19 20H21V23H30V25Z" fill="currentColor"/>
            </svg>    
            Filter By Age
        </b>
        <ul>
            <li><a class="btn btn-primary active" data-school-type="all" href="#rowSchoolType_all">All</a></li>
            <li><a class="btn btn-primary" data-school-type="k-to-12" href="#rowSchoolType_k-to-12">K–12</a></li>
            <li><a class="btn btn-primary" data-school-type="primary" href="#rowSchoolType_primary">Primary</a></li>
            <li><a class="btn btn-primary" data-school-type="secondary" href="#rowSchoolType_secondary">Secondary</a></li>
        </ul>
        <div class="nav-button-group">
            <a href="#" class="clear-search" id="clearSearchButton">Clear Search</a>
            <button class="apply-search btn btn-secondary" id="applyFiltersButton">Apply Filters</button>
        </div>
    </nav>
</div>
<div class="xy-col school-mobile-view-switch" data-xy-col="12" data-xy-items="align-start">
    <a class="btn btn-primary active" id="viewMapView">Map View</a>
    <a class="btn btn-primary" id="viewListView">List View</a>
</div>
<div class="xy-col xy-grid extra-gap" data-xy-col="12" data-xy-items="align-start">
    <div class="school-list xy-col mobile-hide" id="schoolListContainer" data-xy-col="xl-4 lg-4 md-12 sm-12">
        <?= apply_filters('the_content', $fields['content']); ?>
        <ul class="school-simple-list list"  data-xy-col="12" id="mapCompactSearchList">
        <?php 
            foreach($school_list_data as $school) {
                get_template_part( "template-parts/parts/school-item", 'compact', array(
                    'ID' => $school['postId'],
                    'class' => "",
                ));
            }
        ?>
        </ul> 
    </div>

    <div class="xy-col map-wrapper" data-xy-col="xl-8 lg-8 md-12 sm-12" id="schoolMapContainer">

        <ul class="map-pills">
            <li><a class="btn btn-primary active" data-school-type="all" href="#rowSchoolType_all">All</a></li>
            <li><a class="btn btn-primary" data-school-type="k-to-12" href="#rowSchoolType_k-to-12">K–12</a></li>
            <li><a class="btn btn-primary" data-school-type="primary" href="#rowSchoolType_primary">Primary</a></li>
            <li><a class="btn btn-primary" data-school-type="secondary" href="#rowSchoolType_secondary">Secondary</a></li>
        </ul>

        <div id="mapCompact" class="map-compact">

        </div>
    </div>
</div><!-- .xy-col // Schol Map Inner -->

<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
<script type="text/javascript">
    const school_locations = JSON.parse(`<?php echo json_encode($school_list_data); ?>`);
    let gmapsReady = false;
    window.initMap = function() {
        gmapsReady = true;
    }
</script>
<?php 
    wp_enqueue_script( 'csohq-school-map', get_stylesheet_directory_uri() . '/js/school-map.js', array(), _S_VERSION, true );
?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GLOBALS['GMAPS_API_KEY']; ?>&libraries=places&callback=initMap" async defer loading='async'></script>
<!-- end school map scripts -->