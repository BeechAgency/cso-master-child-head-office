<?php 
    $fields = $args;

    $card_background_color = !empty($fields['background_color_cards']) ? $fields['background_color_cards'] : get_sub_field('_background_color_cards');

    $school_list = get_sub_field('_schools');

    $card_bg = get_sub_field('_background_color_card');
    $card_text = get_sub_field('_text_color_card');

    $all_schools = cso_hq_get_school_list_by_type();
?>

<div class="xy-col text-wrapper xy-flex" data-xy-col="12" data-xy-flex="space-between" data-xy-items="align-start">
    <div class="heading-group">
        <?= apply_filters('the_content', $fields['content']); ?>
    </div>
    <div>
        <form class="search-form find-a-school no-margin" id='blockSchoolFinderForm<?= $fields['c'] ?>'>
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
                <input type="search" class="search-field" placeholder="Enter keywords..." id="" />
                <button class="search-submit" id="">
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
                                <path id="Path_482" data-name="Path 482" d="M13.857.354.707,13.5l13.15,13.15" fill="none" stroke="currentcolor" stroke-miterlimit="10" stroke-width="1.5"/>
                            </g>
                            </g>
                            <path id="Path_483" data-name="Path 483" d="M.707,13.5h0Z" fill="none" stroke="rgba(0,0,0,0)" stroke-width="1.5"/>
                            <g id="Group_293" data-name="Group 293">
                            <g id="Group_292" data-name="Group 292" clip-path="url(#clip-path)">
                                <line id="Line_6" data-name="Line 6" x1="37.393" transform="translate(0.707 13.504)" fill="none" stroke="currentcolor" stroke-miterlimit="10" stroke-width="1.5"/>
                            </g>
                            </g>
                        </g>
                    </svg>
                </button>
            </label>
        </form>
    </div>
</div>

<ul class="xy-col xy-grid card-wrapper list school-list" data-xy-col="12" id='blockSchoolFinder<?= $fields['c'] ?>'>
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

<div class="xy-col text-center" data-xy-col="12">
    <div class="button-row">
        <?= do_a_cta($fields['cta']); ?>
    </div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
<script type="text/javascript" defer>
    var schoolValues = [
        <?php 
            foreach($all_schools as $school) {
                $id = $school['postId'];
                $name = $school['name'];
                $link = $school['link'];
                $src = $school['imageSrc'];
                $srcSet = $school['imageSrcSet'];

                $location = get_field('school_location_name', $id);

                $types = get_the_terms($id, 'school_type');
                $school_type = $types[0]->name;
                echo "{ 'school_name' : '$name', 'type' : '$school_type', 'location' : '$location', 'schoolimage': '$src', 'dataschoolimageset' : '$srcSet', 'schoolimageset' : '$srcSet','schoollink' : '$link', 'schoolbtn': '$link' },";
            }
            ?>
    ];

    function shuffleArray(arr) {
        arr.sort(() => Math.random() - 0.5);
    }
    shuffleArray(schoolValues);

    
    var searchList = '';

    const schoolListOptions = {
        valueNames: ['school_name', 'location', 'type', 
            { name: 'schoolimage', attr: 'src' }, 
            { name: 'dataschoolimageset', attr: 'data-srcset' },
            { name: 'schoolimageset', attr: 'srcset' },
            { name : 'schoollink', attr: 'href'}, 
            { name: 'schoolbtn', attr: 'href' }
        ],
        page: 3
    }
    const schoolList = document.querySelector('#blockSchoolFinder<?= $fields['c'] ?>');
    const schoolWrapper = schoolList.parentElement


    searchList = new List(schoolWrapper, schoolListOptions, schoolValues);

    // Handle the good stuff.
    const searchForm = document.querySelector('#blockSchoolFinderForm<?= $fields['c'] ?>');
    const searchInput = searchForm.querySelector('input.search-field');
    const searchButton = searchForm.querySelector('button');

    searchInput.addEventListener('input', event => {
        const query = event.target.value

        //console.log(searchList, schoolValues);

        searchList.search(query);
        return
    })


    document.querySelector('form.find-a-school').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.querySelector('.school-section-nav').scrollIntoView();
        }
    });
    
    //console.log('Hi 2');
</script>