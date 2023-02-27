
/* Mega Menu Interactions */ 
( function() {

    /**
     * Handle the dynamic on page school searching
     */
    const searchLists = {}; //[primaryList, secondaryList, flexibleLearningList, kto12List]
    const searchListKeys = [];
    const schoolLists = document.querySelectorAll('.school-type-list .xy-grid[id]');

    const schoolList_options = {
        valueNames: ['school_name', 'location', 'type']
    }

    schoolLists.forEach(list => {
        if(list.querySelectorAll('li').length === 0) return false;

        const id = list.id;

        searchListKeys.push(id);
        searchLists[id] = new List(id, schoolList_options);
    });

    const searchInput = document.querySelector('#schoolPageSearch');
    
    searchInput.addEventListener('input', event => {
        const query = event.target.value

        searchListKeys.forEach( key => {
            searchLists[key].search(query)
            handleListVisibilityToggle(key)
            handleNoResults(query)
        })
    })

    function handleListVisibilityToggle( listId = null ) {
        const listWrapEl = document.querySelector('#'+listId);

        if(!listWrapEl) return false;

        const filterActive = listWrapEl.parentElement.parentElement.dataset.filterActive;

        if(filterActive === 'false') return false;

        const listItems = listWrapEl.querySelectorAll('li')

        if(listItems.length === 0) {
            listWrapEl.parentElement.parentElement.style.display = 'none'
            return;
        }

        listWrapEl.parentElement.parentElement.style.display = 'block'
        return;
    }

    function handleNoResults( query = '' ) {
        const noSearchResultsEl = document.querySelector('#noSearchResults');
        const queryWrapEl = noSearchResultsEl.querySelector('span')

        const schoolTypeFilters = document.querySelectorAll('.school-type-filters button')

        const resultEls = document.querySelectorAll('.school-type-list[data-filter-active="true"] .xy-grid[id] li')

        if(resultEls.length > 0) {
            noSearchResultsEl.style.display = 'none';
            //schoolTypeFilters.forEach(btn => btn.removeAttribute('disabled', true))
            return;
        }

        noSearchResultsEl.style.display = 'block';

        if(!query) {
            queryWrapEl.textContent = searchInput.value;
            return false;
        }
        queryWrapEl.textContent = query;
        //schoolTypeFilters.forEach(btn => btn.setAttribute('disabled', true))

    }

    /**
     * Handle the filter buttons
     */
    
    function handleFilterButtonToggles() {
        // Get lists
        const lists = document.querySelectorAll('.school-type-list');

        // Get active buttons
        const activeButtons = document.querySelectorAll('.school-type-filters button.active');

        // show all and return if nothing is active
        if(activeButtons.length === 0) {
            lists.forEach(list => {
                list.style.display = 'block';
                list.setAttribute('data-filter-active', true);
            })

            return true;
        } 

        // Hide all
        lists.forEach(list => {
            list.style.display = 'none';
            list.setAttribute('data-filter-active', false);
        })

        // Display active
        activeButtons.forEach( btn => {
            const filter = btn.dataset.filterType;
            const list = document.querySelector('#rowSchoolType_'+filter);

            if(!list) return false;

            list.setAttribute('data-filter-active', true);
            list.style.display = 'block';
        })
    }


    const filterButtons = document.querySelectorAll('.school-type-filters button');

    filterButtons.forEach(button => {
        const filter = button.dataset.filterType;
        const listRow = document.querySelector('#rowSchoolType_'+filter);

        button.addEventListener('click', event => {
            const target = event.target;
            const isActive = target.classList.contains('active');

            target.classList.toggle('active');

            /**
             * If it is not active when clicking it it means it is now active, so do the active stuff
             */
            handleFilterButtonToggles();

            searchListKeys.forEach( key => {
                handleListVisibilityToggle(key)
            })
            handleNoResults()
        })
    });

    const schoolPageSearchSubmitButton = document.querySelector('#schoolPageSearchSubmit');

    schoolPageSearchSubmitButton.addEventListener('click', e => {
        e.preventDefault();
        document.querySelector('.school-section-nav').scrollIntoView();
    });

    document.querySelector('form.find-a-school').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.querySelector('.school-section-nav').scrollIntoView();
        }
    });

} () );