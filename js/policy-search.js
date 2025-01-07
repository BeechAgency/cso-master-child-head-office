const policyListOptions = {
    valueNames: [
        'title',
        { data: ['index', 'description', 'caption', 'file-name', 'title', 'year'] }
    ]
}

document.addEventListener('DOMContentLoaded', () => {
    const policyPageSearch = document.getElementById("policyPageSearch");

    const policyList = new List("policySearchContainer", policyListOptions);

    console.log('policyList', policyList);

    function handlePolicySearch(event) {
      const searchValue = event.target.value;

      if (searchValue) {
        policyList.search(searchValue);

        updateSearchResultCount(policyList.matchingItems.length);
      } else {
        policyList.search();
        updateSearchResultCount(policyList.matchingItems.length);
      }
    }

    policyPageSearch.addEventListener('input', handlePolicySearch);

    const policyPageSearchSubmit = document.getElementById("policyPageSearchSubmit");
    policyPageSearchSubmit.addEventListener('click', (event) => {
        event.preventDefault();

        const row1Element = document.getElementById("row1");
        if (row1Element) {
            row1Element.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

function updateSearchResultCount( resultsCount ) {
    const searchResultsCount = document.getElementById("searchResultsCount");

    searchResultsCount.innerHTML = `(${resultsCount})`;
}


