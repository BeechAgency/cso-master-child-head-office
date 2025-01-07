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
});

function updateSearchResultCount( resultsCount ) {
    const searchResultsCount = document.getElementById("searchResultsCount");

    searchResultsCount.innerHTML = `(${resultsCount})`;
}


