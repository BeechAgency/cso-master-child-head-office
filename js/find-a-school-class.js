class SchoolSearchHandler {
  constructor() {
    this.searchLists = {};
    this.searchListKeys = [];
    this.schoolLists = document.querySelectorAll('.school-type-list .xy-grid[id]');
    this.map = null;
    this.searchFilter = 'all';
    this.searchLocation = '';
    this.searchLocationGeo = null;
    this.searchLocationResults = null;
    this.searchRadius = parseFloat(10);
    this.mapIdleCount = 0;
    this.openInfoWindow = null;
    this.markers = [];
    this.userMarker = null;
    this.mapDefaults = {
      lat: -32.4007455,
      lng: 151.7544187,
      zoom: 9
    };
    this.rootPath = '../wp-content/themes/cso-master-child-head-office/images/';
    this.mapPin = new Map([
      ["primary", this.rootPath + "pin-primary_2.svg"],
      ["secondary", this.rootPath + "pin-secondary_2.svg"],
      ["high", this.rootPath + 'pin-secondary_2.svg'],
      ["k-to-12", this.rootPath + 'pin-k-12_2.svg'],
      ["flexible-learning-centres", this.rootPath + 'pin-flc_2.svg'],
      ["search", this.rootPath + "pin-user_2.svg"]
    ]);
    this.schoolListOptions = {
      valueNames: [
        "school_name",
        "location",
        "type",
        { data: ["school-geo", "school-id"] },
      ],
    };

    this.init();
  }

  init() {
    this.initializeSchoolLists();
    this.initializeMap();
    this.attachEventListeners();
  }

  initializeSchoolLists() {
    this.schoolLists.forEach(list => {
      if (list.querySelectorAll('li').length === 0) return;
      const id = list.id;
      this.searchListKeys.push(id);
      this.searchLists[id] = new List(id, this.schoolListOptions);
    });
  }

  decodeHTMLEntities(text) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(text, "text/html");
    return doc.documentElement.textContent;
  }

  handleUpdateResultCount(count = 0) {
    const resultCountEl = document.querySelector("#searchResultNumber");
    if (resultCountEl) resultCountEl.innerHTML = `${count}`;
  }

  handleListVisibilityToggle(listId = null) {
    const listWrapEl = document.querySelector('#' + listId);
    if (!listWrapEl) return false;
    const filterActive = listWrapEl.parentElement.parentElement.dataset.filterActive;
    if (filterActive === 'false') return false;
    const listItems = listWrapEl.querySelectorAll('li');
    listWrapEl.parentElement.parentElement.style.display = listItems.length === 0 ? 'none' : 'block';
  }

  handleNoResults(query = '') {
    const noSearchResultsEl = document.querySelector('#noSearchResults');
    const queryWrapEl = noSearchResultsEl.querySelector('span');
    const schoolTypeFilters = document.querySelectorAll('.school-type-filters button');
    const resultEls = document.querySelectorAll('.school-type-list[data-filter-active="true"] .xy-grid[id] li');

    if (resultEls.length > 0) {
      noSearchResultsEl.style.display = 'none';
      //schoolTypeFilters.forEach(btn => btn.removeAttribute('disabled', true))
      return;
    }

    if (!query) {
      queryWrapEl.textContent = searchInput.value;
      return false;
    }
    queryWrapEl.textContent = query;
    //schoolTypeFilters.forEach(btn => btn.setAttribute('disabled', true))
  }

  handleFilterButtonToggles() {
    if (this.searchFilter === "all") {
      this.handleListItemVisibility(school_locations);
      if (this.searchLocationGeo) {
        this.handleGeoSearchMarkers();
      } else {
        this.clearMarkers();
        this.addMarkers(school_locations, this.map);
      }
      return;
    }

    const filtered_schools = school_locations.filter(
      (school) => school.type === this.searchFilter
    );

    this.handleListItemVisibility(filtered_schools);
    this.handleUpdateResultCount(filtered_schools.length);

    if (this.searchLocationGeo) {
      this.handleGeoSearchMarkers();
    } else {
      this.clearMarkers();
      this.addMarkers(filtered_schools, this.map);
    }
  }

  initMap() {
    const mapEl = document.getElementById("map");
    this.map = new google.maps.Map(mapEl, {
      center: { lat: this.mapDefaults.lat, lng: this.mapDefaults.lng },
      zoom: this.mapDefaults.zoom,
      clickableIcons: false,
    });

    this.addMarkers(school_locations, this.map);

    google.maps.event.addListener(this.map, "idle", () => {
      this.mapIdleCount += 1;
      if (this.mapIdleCount < 2) return;

      const bounds = this.map.getBounds();
      const visibleMarkers = this.markers.filter((marker) =>
        bounds.contains(marker.getPosition())
      );
      this.handleListItemVisibility(visibleMarkers);
    });
  }

  clearMarkers() {
    this.markers.forEach((marker) => marker.setMap(null));
    this.markers = [];
  }

  addMarkers(filteredSchools, map) {
    filteredSchools.forEach(school => {
      this.handleMarker(school, map);
    });
  }

  handleMarker(school, map) {
    const marker = new google.maps.Marker({
      position: {
        lat: school?.map_location.lat,
        lng: school?.map_location.lng,
      },
      map: map,
      title: this.decodeHTMLEntities(school.display_name),
      postId: school.postId,
      school_type: school.type,
      icon: {
        url: this.mapPin.get(school.type),
        scaledSize: new google.maps.Size(33, 47),
      },
    });

    const infoWindow = new google.maps.InfoWindow({
      content: this.infoWindowContent(school),
    });

    
    marker.addListener("click", () => {
      if (this.openInfoWindow) {
        this.openInfoWindow.close();
      }

      infoWindow.open({
        anchor: marker,
        map: map,
        shouldFocus: false,
      });

      this.openInfoWindow = infoWindow;
    });

    this.markers.push(marker);
  }

  infoWindowContent(school) {
    return `<div class="map-marker--info-window">
        <h6 class="card-date has-primary-light-color">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="12.747" height="18.885" viewBox="0 0 12.747 18.885"><path id="pin_drop_FILL0_wght400_GRAD0_opsz48" d="M16.874,17.739a18.637,18.637,0,0,0,3.706-3.812,6.147,6.147,0,0,0,1.251-3.411,4.887,4.887,0,0,0-1.759-3.942,4.731,4.731,0,0,0-1.617-.885,5.381,5.381,0,0,0-1.582-.271,5.381,5.381,0,0,0-1.582.271,4.731,4.731,0,0,0-1.617.885,4.887,4.887,0,0,0-1.759,3.942,6.147,6.147,0,0,0,1.251,3.411A18.637,18.637,0,0,0,16.874,17.739Zm0,1.794a22.146,22.146,0,0,1-4.8-4.65,7.562,7.562,0,0,1-1.57-4.367,6.5,6.5,0,0,1,.578-2.821,6.4,6.4,0,0,1,1.5-2.042,6.265,6.265,0,0,1,2.054-1.239,6.293,6.293,0,0,1,4.485,0A6.265,6.265,0,0,1,21.17,5.652a6.4,6.4,0,0,1,1.5,2.042,6.5,6.5,0,0,1,.578,2.821,7.562,7.562,0,0,1-1.57,4.367,22.146,22.146,0,0,1-4.8,4.65Zm0-7.507a1.652,1.652,0,0,0,1.168-2.821,1.652,1.652,0,0,0-2.337,2.337A1.592,1.592,0,0,0,16.874,12.026ZM10.5,22.885V21.468H23.247v1.416ZM16.874,10.515Z" transform="translate(-10.5 -4)" fill="currentColor"></path></svg> 
            <span class="location">${school.location}</span></span>
            <span><svg xmlns="http://www.w3.org/2000/svg" width="17.271" height="14.131" viewBox="0 0 17.271 14.131"><path id="school_FILL0_wght400_GRAD0_opsz48" d="M10.616,20.131,4.924,17.01V12.3L2,10.71,10.616,6l8.655,4.71v6.222H18.094V11.4l-1.786.9v4.71Zm0-6.045L16.8,10.71,10.616,7.393,4.473,10.71Zm0,4.71L15.13,16.3v-3.3l-4.514,2.414L6.1,12.967V16.3ZM10.636,14.086ZM10.616,15.538ZM10.616,15.538Z" transform="translate(-2 -6)" fill="currentColor"></path></svg> 
            <span class="type">${this.schoolType(school.type)}</span></span>
        </h6>
        <h4>${school.display_name}</h4>
        <a class="btn btn-primary" href="${school.link}" target="_blank">Learn more</a>
    </div>`;
  }

  schoolType(string) {
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

  centerMapOnSearchLocation() {
    if (this.userMarker) {
      this.userMarker.setMap(null);
    }

    this.map.setCenter(this.searchLocationGeo);
    this.map.setZoom(13);

    const searchMarker = new google.maps.Marker({
      position: this.searchLocationGeo,
      map: this.map,
      title: "Search Location",
      icon: {
        url: this.mapPin.get("search"),
        scaledSize: new google.maps.Size(40, 57)
      },
    });

    this.userMarker = searchMarker;

    const infoWindow = new google.maps.InfoWindow({
      content: `<div>Search Location: ${this.searchLocationResults.formatted_address}</div>`,
    });
    searchMarker.addListener("click", () => infoWindow.open(this.map, searchMarker));    
  }

  handleListItemVisibility(schoolsToDisplay = []) {
    if (!schoolsToDisplay.length) return;

    const display_ids = schoolsToDisplay.map((school) => school.postId.toString());

    this.searchListKeys.forEach((key) => {
      const list = this.searchLists[key];

      list.items.forEach((item) => {
        const school_id = item._values['school-id'];
        if (display_ids.includes(school_id)) {
          item.show();
        } else {
          item.hide();
        }
      });

      this.handleListVisibilityToggle(key);
    });

    this.handleUpdateResultCount(display_ids.length);
  }

  handleSearch() {
    const searchField = document.getElementById("schoolPageSearch");
    const searchTerm = searchField.value;
    const address = this.ensureNSW(searchTerm);
    this.searchLocation = address;

    const radiusInput = parseFloat(this.searchRadius);
    if (!address || !radiusInput) {
      console.error("Please provide a valid address and radius.");
      return;
    }

    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: address }, (results, status) => {
      if (status !== "OK" && !results) return console.error("Geocoding failed: " + status);

      const location = results[0].geometry.location;
      this.searchLocationGeo = location;
      this.searchLocationResults = results[0];

      this.centerMapOnSearchLocation();
      this.handleGeoSearchMarkers();
    });
  }

  handleGeoSearchMarkers() {
    if (!this.searchLocationGeo) return false;
    const location = this.searchLocationGeo;
    const latInput = location.lat();
    const lngInput = location.lng();

    let filteredLocations = school_locations.filter((location) => {
      const distance = this.getDistance(
        latInput,
        lngInput,
        location.map_location.lat,
        location.map_location.lng
      );

      return distance <= this.searchRadius;
    });

    if (this.searchFilter !== "all") {
      filteredLocations = filteredLocations.filter((location) => {
        return location.type === this.searchFilter;
      });
    }

    this.handleListItemVisibility(filteredLocations);
    this.clearMarkers();
    this.addMarkers(filteredLocations, this.map);
  }

  getDistance(lat1, lng1, lat2, lng2) {
    const R = 6371;
    const dLat = this.toRad(lat2 - lat1);
    const dLng = this.toRad(lng2 - lng1);
    const a = Math.sin(dLat / 2) ** 2 +
      Math.cos(this.toRad(lat1)) * Math.cos(this.toRad(lat2)) *
      Math.sin(dLng / 2) ** 2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
  }

  toRad(deg) {
    return deg * (Math.PI / 180);
  }

  ensureNSW(query) {
    const statesAndTerritories = [
      "New South Wales", "Victoria", "Queensland", "South Australia",
      "Western Australia", "Tasmania", "Australian Capital Territory",
      "Northern Territory", "NSW", "VIC", "QLD", "SA", "WA", "TAS", "ACT", "NT"
    ];

    const hasPostcode = /\b\d{4}\b/.test(query);
    const statePattern = new RegExp(`\\b(${statesAndTerritories.map((state) => state.toLowerCase()).join("|")})\\b`, "i");
    const hasStateOrTerritory = statePattern.test(query);

    if (!hasPostcode && !hasStateOrTerritory) {
      query = `${query}, NSW`;
    }

    if (hasPostcode && query.length < 5) {
      query = `${query}, NSW`;
    }

    console.log("query", query, hasPostcode, hasStateOrTerritory);

    return query;
  }

  attachEventListeners() {
    const searchInput = document.querySelector("#schoolPageSearch");
    searchInput.addEventListener("input", (event) => {
      const query = event.target.value;
      this.searchListKeys.forEach((key) => {
        this.searchLists[key].search(query);
        this.handleListVisibilityToggle(key);
        this.handleNoResults(query);
      });
    });

    const filterButtons = document.querySelectorAll('.school-type-filters button');
    filterButtons.forEach(button => {
      button.addEventListener('click', event => {
        this.handleFilterButtonToggles();
        this.searchListKeys.forEach(key => {
          this.handleListVisibilityToggle(key);
        });
        this.handleNoResults();
      });
    });

    const schoolPageSearchSubmitButton = document.querySelector('#schoolPageSearchSubmit');
    schoolPageSearchSubmitButton.addEventListener('click', e => {
      e.preventDefault();
      document.querySelector('.school-section-nav').scrollIntoView();
    });

    const clearSearchButton = document.getElementById("clearSearchButton");
    clearSearchButton.addEventListener("click", (e) => {
      e.preventDefault();
      this.resetSearch();
    });

    this.handleSearchFilterButtons();
    this.handleHideMapButton();
    this.handleMobileDrawerEvents();
  }

  handleHideMapButton() {
    const hideMapButton = document.getElementById("hideMapButton");
    hideMapButton.addEventListener("click", () => {
      const mapEl = document.getElementById("map");
      mapEl.classList.toggle("hide-map");

      hideMapButton.children[1].textContent = mapEl.classList.contains("hide-map")
        ? "Show Map"
        : "Hide Map";
    });
  }

  handleMobileDrawerEvents() {
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

    [applyButton, closeButton, clearButton].forEach(button => {
      button.addEventListener('click', handleCloseDrawer);
    });
  }

  handleSearchFilterButtons() {
    const filterButtons = document.querySelectorAll(".school-section-nav nav ul .btn");

    filterButtons.forEach((button) => {
      button.addEventListener("click", (e) => {
        e.preventDefault();
        filterButtons.forEach((btn) => btn.classList.remove("active"));
        button.classList.add("active");
        this.searchFilter = button.dataset.schoolType;
        this.handleFilterButtonToggles();
      });
    });
  }

  resetSearch() {
    const searchField = document.getElementById("schoolPageSearch");
    searchField.value = "";

    this.clearMarkers();
    this.addMarkers(school_locations, this.map);

    this.handleUpdateResultCount(school_locations.length);

    this.searchFilter = "all";
    const filterButtons = document.querySelectorAll(".school-section-nav nav ul .btn");
    filterButtons.forEach((btn) => btn.classList.remove("active"));
    filterButtons[0].classList.add("active");

    this.searchLocationGeo = null;
    this.searchLocationResults = null;
    this.searchLocation = "";

    this.searchListKeys.forEach((key) => {
      this.searchLists[key].show(0, 100);
      this.handleListVisibilityToggle(key);
    });
  }
}

window.addEventListener('load', () => {
  new SchoolSearchHandler();
});