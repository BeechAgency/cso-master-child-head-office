class SchoolMap {
  constructor() {
    this.searchLists = {};
    this.searchListKeys = [];
    this.markers = [];
    this.userMarker = null;
    this.map = null;
    this.mapIdleCount = 0;
    this.openInfoWindow = null;
    this.mapViewActive = true;

    this.searchFilter = "all";
    this.searchLocation = "";
    this.searchLocationGeo = null;
    this.searchLocationResults = null;
    this.searchRadius = parseFloat(10);

    this.mapDefaults = {
      lat: -32.4007455,
      lng: 151.7544187,
      zoom: 9,
    };

    this.rootPath = "../../wp-content/themes/cso-master-child-head-office/images/";
    this.mapPin = new Map([
      ["primary", this.rootPath + "pin-primary_2.svg"],
      ["secondary", this.rootPath + "pin-secondary_2.svg"],
      ["high", this.rootPath + "pin-secondary_2.svg"],
      ["k-to-12", this.rootPath + "pin-k-12_2.svg"],
      ["flexible-learning-centres", this.rootPath + "pin-flc_2.svg"],
      ["search", this.rootPath + "pin-user_2.svg"],
    ]);

    this.schoolListOptions = {
      valueNames: [
        "school_name",
        "location",
        "type",
        { data: ["school-geo", "school-id"] },
      ],
    };

    this.initSchoolLists();
    this.initSearchAndFilters();
    this.handleMobileDrawerEvents();
  }

  initSchoolLists() {
    const listId = "schoolListContainer";
    
    this.searchListKeys.push(listId);

    this.searchLists[listId] = new List(
      "schoolListContainer",
      this.schoolListOptions
    );

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

  handleNoResults(query = "") {
    const noSearchResultsEl = document.querySelector("#noSearchResults");
    const queryWrapEl = noSearchResultsEl.querySelector("span");
    const resultEls = document.querySelectorAll(
      '.school-type-list[data-filter-active="true"] .xy-grid[id] li'
    );

    noSearchResultsEl.style.display = resultEls.length > 0 ? "none" : "block";
    if (!query) {
      const searchInput = document.querySelector("#schoolPageSearch");
      queryWrapEl.textContent = searchInput.value;
    } else {
      queryWrapEl.textContent = query;
    }
  }

  handleFilterButtonToggles() {
    console.log("handleFilterButtonToggles", this.searchFilter);

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

    const filteredSchools = school_locations.filter(
      (s) => s.type === this.searchFilter
    );
    this.handleListItemVisibility(filteredSchools);
    this.handleUpdateResultCount(filteredSchools.length);

    if (this.searchLocationGeo) {
      this.handleGeoSearchMarkers();
    } else {
      this.clearMarkers();
      this.addMarkers(filteredSchools, this.map);
    }
  }

  initSearchAndFilters() {
    
    const filterButtons = document.querySelectorAll(
      ".map-pills .btn, .school-mobile-filters li .btn"
    );
    filterButtons.forEach((button) => {
      const filter = button.dataset.schoolType;

      button.addEventListener("click", (event) => {
        filterButtons.forEach((btn) => btn.classList.remove("active"));

        event.target.classList.add("active");

        this.searchFilter = filter;

        this.handleFilterButtonToggles();

        //this.handleNoResults();
      });
    });
  }

  clearMarkers() {
    this.markers.forEach((marker) => marker.setMap(null));
    this.markers = [];
  }

  addMarkers(filteredSchools, map) {
    filteredSchools.forEach((school) => this.handleMarker(school, map));
  }

  handleMarker(school, map) {
    const marker = new google.maps.Marker({
      position: {
        lat: school?.map_location.lat,
        lng: school?.map_location.lng,
      },
      map,
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
      infoWindow.open({ anchor: marker, map, shouldFocus: false });
      this.openInfoWindow = infoWindow;
    });

    this.markers.push(marker);
  }

  infoWindowContent(school) {
    return `<div class="map-marker--info-window">
      <h6 class="card-date has-primary-light-color">
        <span class="location">${school.location}</span>
        <span class="type">${this.schoolType(school.type)}</span>
      </h6>
      <h4>${school.display_name}</h4>
      <a class="btn btn-primary" href="${
        school.link
      }" target="_blank">Learn more</a>
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

  handleListItemVisibility(schoolsToDisplay = []) {
    
    const displayIds = schoolsToDisplay.map((s) => s.postId.toString());

    //console.log("schoolsToDisplay:", displayIds);

    this.searchListKeys.forEach((key) => {
      const list = this.searchLists[key];
      list.items.forEach((item) => {
        const schoolId = item._values["school-id"];
        displayIds.includes(schoolId) ? item.show() : item.hide();
      });
    });

    this.handleUpdateResultCount(displayIds.length);
  }

  initMap() {
    const mapEl = document.getElementById("mapCompact");
    console.log('Map el!', mapEl);
    this.map = new google.maps.Map(mapEl, {
      center: { lat: this.mapDefaults.lat, lng: this.mapDefaults.lng },
      zoom: this.mapDefaults.zoom,
      clickableIcons: false,
      mapTypeId: "roadmap",
      disableDefaultUI: true,
      zoomControl: true,
      streetViewControl: false,
      fullscreenControl: false,
      gestureHandling: "greedy",

    });

    this.addMarkers(school_locations, this.map);

    google.maps.event.addListener(this.map, "idle", () => {
      this.mapIdleCount += 1;
      if (this.mapIdleCount < 2) return;
      const bounds = this.map.getBounds();
      const visibleMarkers = this.markers.filter((m) =>
        bounds.contains(m.getPosition())
      );
      this.handleListItemVisibility(visibleMarkers);
    });
  }

  ensureNSW(query) {
    const states = [
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
    const hasPostcode = /\b\d{4}\b/.test(query);
    const hasState = new RegExp(
      `\\b(${states.map((s) => s.toLowerCase()).join("|")})\\b`,
      "i"
    ).test(query);
    if ((!hasPostcode && !hasState) || (hasPostcode && query.length < 5)) {
      return `${query}, NSW`;
    }
    return query;
  }

  centerMapOnSearchLocation() {
    if (this.userMarker) this.userMarker.setMap(null);
    this.map.setCenter(this.searchLocationGeo);
    this.map.setZoom(13);
    const searchMarker = new google.maps.Marker({
      position: this.searchLocationGeo,
      map: this.map,
      title: "Search Location",
      icon: {
        url: this.mapPin.get("search"),
        scaledSize: new google.maps.Size(40, 57),
      },
    });
    this.userMarker = searchMarker;

    const infoWindow = new google.maps.InfoWindow({
      content: `<div>Search Location: ${this.searchLocationResults.formatted_address}</div>`,
    });
    searchMarker.addListener("click", () =>
      infoWindow.open(this.map, searchMarker)
    );
  }

  handleSearch() {
    const searchField = document.getElementById("schoolPageSearch");
    const searchTerm = searchField.value;
    const address = this.ensureNSW(searchTerm);
    this.searchLocation = address;

    const radiusInput = parseFloat(this.searchRadius);
    if (!address || !radiusInput)
      return console.error("Please provide a valid address and radius.");

    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address }, (results, status) => {
      if (status !== "OK" || !results)
        return console.error("Geocoding failed: " + status);
      const location = results[0].geometry.location;
      this.searchLocationGeo = location;
      this.searchLocationResults = results[0];
      this.centerMapOnSearchLocation();
      this.handleGeoSearchMarkers();
    });
  }

  handleGeoSearchMarkers() {
    const nearby = school_locations.filter((s) => {
      const dist = this.getDistance(
        s.map_location.lat,
        s.map_location.lng,
        this.searchLocationGeo.lat(),
        this.searchLocationGeo.lng()
      );
      return dist <= this.searchRadius;
    });
    this.clearMarkers();
    this.addMarkers(nearby, this.map);
    this.handleListItemVisibility(nearby);
  }

  getDistance(lat1, lng1, lat2, lng2) {
    const R = 6371;
    const dLat = this.toRad(lat2 - lat1);
    const dLng = this.toRad(lng2 - lng1);
    const a =
      Math.sin(dLat / 2) ** 2 +
      Math.cos(this.toRad(lat1)) *
        Math.cos(this.toRad(lat2)) *
        Math.sin(dLng / 2) ** 2;
    return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
  }

  toRad(deg) {
    return deg * (Math.PI / 180);
  }


  handleMobileDrawerEvents() {
    const drawerNav = document.querySelector(".school-section-nav nav");
    const applyButton = document.getElementById("applyFiltersButton");
    const closeButton = document.getElementById("closeFiltersButton");
    const clearButton = document.getElementById("clearSearchButton");
    const mobileFilterToggle = document.getElementById("mobileFilterToggle");

    const viewSwitchMap = document.getElementById("viewMapView");
    const viewSwitchList = document.getElementById("viewListView");
    const schoolListContainer = document.getElementById("schoolListContainer");
    const schoolMapContainer = document.getElementById("schoolMapContainer");

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

    viewSwitchMap.addEventListener('click', () => {
      if(this.mapViewActive) return;
      this.mapViewActive = true;
      schoolListContainer.classList.add('mobile-hide');
      schoolMapContainer.classList.remove('mobile-hide');
      viewSwitchMap.classList.add('active');
      viewSwitchList.classList.remove('active');
    });

    viewSwitchList.addEventListener('click', () => {
      if(!this.mapViewActive) return;
      this.mapViewActive = false;
      schoolListContainer.classList.remove('mobile-hide');
      schoolMapContainer.classList.add('mobile-hide');
      viewSwitchMap.classList.remove('active');
      viewSwitchList.classList.add('active');
    });
  }
}

const schoolSearch = new SchoolMap();
let mapInitialized = false;

if (!window.gmapsReady) {
  console.log('Gmaps not ready');
  window.initMap = () => {
    schoolSearch.initMap()
    mapInitialized = true;
  }
} else {
  console.log('Gmaps ready');
  schoolSearch.initMap();
  mapInitialized = true;
}
document.addEventListener("DOMContentLoaded", () => {
  console.log('Dom ready', mapInitialized);
  if (!mapInitialized) schoolSearch.initMap();
});