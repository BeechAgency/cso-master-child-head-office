/**
 * Handle the dynamic on page school searching
 */
const searchLists = {}; //[primaryList, secondaryList, flexibleLearningList, kto12List]
const searchListKeys = [];
const schoolLists = document.querySelectorAll('.school-type-list .xy-grid[id]');

let map = null;

let searchFilter = 'all';
let searchLocation = '';
let searchLocationGeo = null;
let searchLocationResults = null;
let searchRadius = parseFloat(10);

let mapIdleCount = 0;

let openInfoWindow = null; // Keep track of the currently open InfoWindow
let markers = [];
let userMarker = null;

const mapDefaults = {}
mapDefaults.lat = -32.4007455; // Dungog
mapDefaults.lng = 151.7544187;
mapDefaults.zoom = 9;

const rootPath = '../wp-content/themes/cso-master-child-head-office/images/';
const mapPin = new Map();
mapPin.set("primary", rootPath+"pin-primary_2.svg");
mapPin.set("secondary", rootPath+"pin-secondary_2.svg");
mapPin.set('high', rootPath+'pin-secondary_2.svg');
mapPin.set('k-to-12', rootPath+'pin-k-12_2.svg');
mapPin.set("flexible-learning-centres", rootPath+"pin-flc_2.svg");
mapPin.set("search", rootPath + "pin-user_2.svg");


const schoolList_options = {
  valueNames: [
    "school_name",
    "location",
    "type",
    { data: ["school-geo", "school-id"] },
  ],
};

schoolLists.forEach(list => {
    if(list.querySelectorAll('li').length === 0) return false;

    const id = list.id;

    searchListKeys.push(id);
    searchLists[id] = new List(id, schoolList_options);
});

function decodeHTMLEntities(text) {
  const parser = new DOMParser();
  const doc = parser.parseFromString(text, "text/html");
  return doc.documentElement.textContent;
}

function handleUpdateResultCount( count = 0 ) {
    const resultCountEl = document.querySelector("#searchResultNumber");

    if(resultCountEl) {
        resultCountEl.innerHTML = `${count}`;
    }
}

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

    //noSearchResultsEl.style.display = 'block';

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
  // Reset all the filter stuff
  if (searchFilter === "all") {
    handleListItemVisibility(school_locations);

    if(searchLocationGeo) {
      handleGeoSearchMarkers();
    } else {
      clearMarkers();
      addMarkers(school_locations, map);
    }
    return;
  }

  const filtered_schools = school_locations.filter(
    (school) => school.type === searchFilter
  );

  handleListItemVisibility(filtered_schools);
  handleUpdateResultCount(filtered_schools.length);

  // If we've got a geocoded address, then filter the markers around that address
  if(searchLocationGeo) {
    handleGeoSearchMarkers();
  } else {
    // Else just filter all the markers by type
    clearMarkers();
    addMarkers(filtered_schools, map);
  }

  //console.log('Filter active: ', searchFilter);
}

// Will need to split this out. Wrapping in a function so we don't get weird load events
function initSearchAndFilters() {
    const searchInput = document.querySelector("#schoolPageSearch");

    searchInput.addEventListener("input", (event) => {
    const query = event.target.value;

    searchListKeys.forEach((key) => {
        searchLists[key].search(query);
        handleListVisibilityToggle(key);
        handleNoResults(query);
    });
    });

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
}

/**
 * School Map Functions!
 */


function ensureNSW(query) {
  const statesAndTerritories = [
    "New South Wales",
    "Victoria",
    "Queensland",
    "South Australia",
    "Western Australia",
    "Tasmania",
    "Australian Capital Territory",
    "Northern Territory",
    "NSW",
    "VIC",
    "QLD",
    "SA",
    "WA",
    "TAS",
    "ACT",
    "NT",
  ];

    // Check if query already contains a postcode (4 consecutive digits)
    const hasPostcode = /\b\d{4}\b/.test(query);

    // Create a regex pattern to match any full state or territory name or abbreviation as a whole word
    const statePattern = new RegExp(
        `\\b(${statesAndTerritories
        .map((state) => state.toLowerCase())
        .join("|")})\\b`,
        "i"
    );

    // Check if query contains any Australian state or territory
    const hasStateOrTerritory = statePattern.test(query);

  // If neither postcode nor state/territory is present, append ", NSW"
  if (!hasPostcode && !hasStateOrTerritory) {
    query = `${query}, NSW`;
  }

  if(hasPostcode && query.length < 5) {
    query = `${query}, NSW`;
  }

  console.log("query", query, hasPostcode, hasStateOrTerritory);

  return query;
}

function schoolType(string) {
  switch (string) {
    case "primary":
      return "Primary";
    case "secondary":
    case "high":
      return "Secondary";
    case "k-to-12":
      return "K–12";
    case "flexible-learning-centres":
      return "Flexible Learning Centres";
    default:
      return string;
  }
}

function infoWindowContent(school) {
  return `<div class="map-marker--info-window">
        <h6 class="card-date has-primary-light-color">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="12.747" height="18.885" viewBox="0 0 12.747 18.885"><path id="pin_drop_FILL0_wght400_GRAD0_opsz48" d="M16.874,17.739a18.637,18.637,0,0,0,3.706-3.812,6.147,6.147,0,0,0,1.251-3.411,4.887,4.887,0,0,0-1.759-3.942,4.731,4.731,0,0,0-1.617-.885,5.381,5.381,0,0,0-1.582-.271,5.381,5.381,0,0,0-1.582.271,4.731,4.731,0,0,0-1.617.885,4.887,4.887,0,0,0-1.759,3.942,6.147,6.147,0,0,0,1.251,3.411A18.637,18.637,0,0,0,16.874,17.739Zm0,1.794a22.146,22.146,0,0,1-4.8-4.65,7.562,7.562,0,0,1-1.57-4.367,6.5,6.5,0,0,1,.578-2.821,6.4,6.4,0,0,1,1.5-2.042,6.265,6.265,0,0,1,2.054-1.239,6.293,6.293,0,0,1,4.485,0A6.265,6.265,0,0,1,21.17,5.652a6.4,6.4,0,0,1,1.5,2.042,6.5,6.5,0,0,1,.578,2.821,7.562,7.562,0,0,1-1.57,4.367,22.146,22.146,0,0,1-4.8,4.65Zm0-7.507a1.652,1.652,0,0,0,1.168-2.821,1.652,1.652,0,0,0-2.337,2.337A1.592,1.592,0,0,0,16.874,12.026ZM10.5,22.885V21.468H23.247v1.416ZM16.874,10.515Z" transform="translate(-10.5 -4)" fill="currentColor"></path></svg> 
            <span class="location">${school.location}</span></span>
            <span><svg xmlns="http://www.w3.org/2000/svg" width="17.271" height="14.131" viewBox="0 0 17.271 14.131"><path id="school_FILL0_wght400_GRAD0_opsz48" d="M10.616,20.131,4.924,17.01V12.3L2,10.71,10.616,6l8.655,4.71v6.222H18.094V11.4l-1.786.9v4.71Zm0-6.045L16.8,10.71,10.616,7.393,4.473,10.71Zm0,4.71L15.13,16.3v-3.3l-4.514,2.414L6.1,12.967V16.3ZM10.636,14.086ZM10.616,15.538ZM10.616,15.538Z" transform="translate(-2 -6)" fill="currentColor"></path></svg> 
            <span class="type">${schoolType(
                school.type
            )}</span></span>
        </h6>
        <h4>${school.display_name}</h4>
        <a class="btn btn-primary" href="${
            school.link
        }" target="_blank">Learn more</a>
    </div>`;
}


/*
const displayed_schools = createObservableArray([], (updatedArray) => {
  //console.log("Array changed:", updatedArray);
});
*/

function clearMarkers() {
  markers.forEach(
    (marker) => {
        marker.setMap(null)
  });
  markers = [];
}

// Function to add markers to the map
function addMarkers( filteredSchools, map ) {
    filteredSchools.forEach(school => {
        handleMarker(school, map);
    });
}

// The individual marker and associated event listeners
function handleMarker( school, map ) {
    const marker = new google.maps.Marker({
      position: {
        lat: school?.map_location.lat,
        lng: school?.map_location.lng,
      },
      map: map,
      title: decodeHTMLEntities(school.display_name),
      postId: school.postId,
      school_type: school.type,
      icon: {
        url: mapPin.get(school.type), // Custom blue marker
        scaledSize: new google.maps.Size(33, 47),
      },
    });

    const infoWindow = new google.maps.InfoWindow({
        content: infoWindowContent(school),
    });

    // Add a click listener to the marker to show the info window
    marker.addListener("click", () => {
        if (openInfoWindow) {
            openInfoWindow.close(); // Close the previously open InfoWindow
        }

        infoWindow.open({
            anchor: marker,
            map: map,
            shouldFocus: false,
        });

        openInfoWindow = infoWindow; // Keep track of the currently open InfoWindow
    });

    markers.push(marker);
}


// Haversine formula to calculate distance between two lat/lng points in km
function getDistance(lat1, lng1, lat2, lng2) {
    const R = 6371; // Radius of the Earth in km
    const dLat = toRad(lat2 - lat1);
    const dLng = toRad(lng2 - lng1);
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
              Math.sin(dLng / 2) * Math.sin(dLng / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

// Convert degrees to radians
function toRad(deg) {
    return deg * (Math.PI / 180);
}

function initMap() {
  //const { Map } = await google.maps.importLibrary("maps");
  //const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

  const mapEl = document.getElementById("map");
  //console.log("MAP YEAH BOI!", mapEl, school_locations[0]); // Create the map centered at the first location

  // Setup the Map Object
  map = new google.maps.Map(mapEl, {
    center: { lat: mapDefaults.lat, lng: mapDefaults.lng },
    zoom: mapDefaults.zoom,
    clickableIcons: false,
  });

  // Initial population of map
  addMarkers(school_locations, map);

  // Assuming `map` is your Google Map instance and `markers` is an array of your marker objects
  google.maps.event.addListener(map, "idle", () => {
    // We don't want to trigger on the first map idle event
    mapIdleCount += 1;

    if (mapIdleCount < 2) return;

    const bounds = map.getBounds(); // Get the current bounds of the map
    const visibleMarkers = markers.filter((marker) =>
      bounds.contains(marker.getPosition())
    );
    handleListItemVisibility(visibleMarkers);
  });  
}


function centerMapOnSearchLocation() {
    // Center the map on the search location
    if(userMarker) {
        userMarker.setMap(null);
    }

    map.setCenter(searchLocationGeo);
    map.setZoom(13);

    // Add a custom marker at the search location
    const searchMarker = new google.maps.Marker({
      position: searchLocationGeo,
      map: map,
      title: "Search Location",
      icon: {
        url: mapPin.get("search"), // Custom blue marker
        scaledSize: new google.maps.Size(40, 57)
      },
    });

    userMarker = searchMarker;

    // Optional: Show an InfoWindow for the search location
    const infoWindow = new google.maps.InfoWindow({
      content: `<div>Search Location: ${searchLocationResults.formatted_address}</div>`,
    });
    searchMarker.addListener("click", () => infoWindow.open(map, searchMarker));
}

function handleListItemVisibility( schoolsToDisplay = [] ) {
    
    if(!schoolsToDisplay.length) {
        return;
    }

    const display_ids = schoolsToDisplay.map((school) => {
        return school.postId.toString();
    });
    //console.log("Handle Visiblity: ", schoolsToDisplay, display_ids);

    searchListKeys.forEach((key) => {
      const list = searchLists[key];
        
      list.items.forEach((item) => {
          const school_id = item._values['school-id'];

          //console.log('ITEM: ', item, school_id);
            if (display_ids.includes(school_id)) {
                //console.warn("SHOWING", school_id, item);
                item.show();
            } else {
                item.hide();
            }
      })

      // Ensure the section displays if there are results
      handleListVisibilityToggle(key);
    });

    handleUpdateResultCount(display_ids.length);
}

function handleSearch() {
  // Get the search field
  const searchField = document.getElementById("schoolPageSearch");

  // Extract the search term value
  const searchTerm = searchField.value;

  // Validate the term to ensure it is an address
  const address = ensureNSW(searchTerm);
  searchLocation = address;

  console.log("Be searching!", address);

  const radiusInput = parseFloat(searchRadius);

  if (!address || !radiusInput) {
    console.error("Please provide a valid address and radius.");
    return;
  }

  // Use Geocoding API to convert address to lat/lng
  const geocoder = new google.maps.Geocoder();

  // Attempt Geocoding the address term
  geocoder.geocode({ address: address }, (results, status) => {
    console.log("Attempt geocoding address");

    if (status !== "OK" && !results)
      return console.error("Geocoding failed: " + status);

    const location = results[0].geometry.location;

    // Setting this globally so we can resuse it
    searchLocationGeo = location;
    searchLocationResults = results[0];

    console.log("This be the geo'd location: ", location);

    // Centre the map on the location
    centerMapOnSearchLocation();

    // Handle the markers based on the geocoded location
    handleGeoSearchMarkers();
  });
}


function initPageSearchButtonHandler() {

    const searchPageSubmitButton = document.getElementById("schoolPageSearchSubmit");
    if(!searchPageSubmitButton) return;

    const form = document.querySelector("form.find-a-school");

    searchPageSubmitButton.addEventListener("click", () => {
        handleSearch(); 
        document.querySelector(".school-section-nav").scrollIntoView();
    });


    
      form.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
          e.preventDefault();
          document.querySelector(".school-section-nav").scrollIntoView();
          handleSearch(); 
        }
      });
}

function handleGeoSearchMarkers() {
    if (!searchLocationGeo) return false;
    const location = searchLocationGeo;;

    const latInput = location.lat();
    const lngInput = location.lng();

    // Filter pins using the lat/lng from the geocoded address
    let filteredLocations = school_locations.filter((location) => {
        const distance = getDistance(
            latInput,
            lngInput,
            location.map_location.lat,
            location.map_location.lng
        );

        return distance <= searchRadius;
    });

    //  Filter by type
    if (searchFilter !== "all") {
        filteredLocations = filteredLocations.filter((location) => {
            return location.type === searchFilter;
        });
    }

    console.log("Filtered locations:", filteredLocations);

    handleListItemVisibility(filteredLocations);

    // Clear existing pins and add filtered ones
    clearMarkers();
    addMarkers(filteredLocations, map);
}

function handleHideMapButton() {
    const hideMapButton = document.getElementById("hideMapButton");

    hideMapButton.addEventListener("click", () => {
        const mapEl = document.getElementById("map");
       
        mapEl.classList.toggle("hide-map");

        hideMapButton.children[1].textContent = mapEl.classList.contains("hide-map")
          ? "Show Map"
          : "Hide Map";
        
    });
}

function handleClearSearchButton() {
    const clearSearchButton = document.getElementById("clearSearchButton");

    clearSearchButton.addEventListener("click", (e) => {
      e.preventDefault();

      const searchField = document.getElementById("schoolPageSearch");
      // Reset the search field
      searchField.value = "";

      // Reset the map
      clearMarkers();
      addMarkers(school_locations, map);

      // Update the search count
      handleUpdateResultCount(school_locations.length);

      // Reset the search filters
      searchFilter = "all";

      const filterButtons = document.querySelectorAll(
        ".school-section-nav nav ul .btn"
      );
      filterButtons.forEach((btn) => {
        btn.classList.remove("active");
      });
      filterButtons[0].classList.add("active");

      // Reset the geocoded location
      searchLocationGeo = null;
      searchLocationResults = null;
      searchLocation = "";

      // Make everything visible
      searchListKeys.forEach((key) => {
        searchLists[key].show(0, 100);
        handleListVisibilityToggle(key);
      });
    });
}

function handleSearchFilterButtons() {
    const filterButtons = document.querySelectorAll(
      ".school-section-nav nav ul .btn"
    );

    filterButtons.forEach((button) => {
      button.addEventListener("click", ( e ) => {
        e.preventDefault();

        filterButtons.forEach((btn) => {
          btn.classList.remove("active");
        });
        button.classList.add("active");
        const type = button.dataset.schoolType;

        searchFilter = type;

        console.log("Search Filter:", searchFilter);
        handleFilterButtonToggles();
      });
    });
}

function handleMobileDrawerEvents() {
    const drawerNav = document.querySelector(".school-section-nav nav");
    const applyButton = document.getElementById("applyFiltersButton");
    const closeButton = document.getElementById("closeFiltersButton");
    const clearButton = document.getElementById("clearSearchButton");
    const mobileFilterToggle = document.getElementById("mobileFilterToggle");

    function handleCloseDrawer() {
        drawerNav.classList.remove('active');
    }

    mobileFilterToggle.addEventListener('click', () => {
        drawerNav.classList.toggle('active');
    });

    applyButton.addEventListener('click', () => {
        handleCloseDrawer();
    });
    closeButton.addEventListener('click', () => {
        handleCloseDrawer();
    });
    clearButton.addEventListener('click', () => {
        handleCloseDrawer();
    });

}


window.addEventListener('load', () => {
    initMap();

    initPageSearchButtonHandler();

    handleHideMapButton();
    handleClearSearchButton();
    handleSearchFilterButtons();
    initSearchAndFilters(); 
    handleMobileDrawerEvents();

});