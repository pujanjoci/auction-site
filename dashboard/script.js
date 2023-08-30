        document.addEventListener('DOMContentLoaded', function () {
            const searchForm = document.getElementById('search-form');
            const searchBar = document.getElementById('search-bar');
            const searchIcon = document.getElementById('search-icon');

            const performSearch = () => {
                const searchTerm = searchBar.value;
                const encodedSearchTerm = encodeURIComponent(searchTerm);
                const newUrl = window.location.origin + window.location.pathname + '?search-term=' + encodedSearchTerm;

                window.location.href = newUrl;
            };

            searchForm.addEventListener('submit', function (event) {
                event.preventDefault();
                performSearch();
            });

            searchBar.addEventListener('keyup', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    performSearch();
                }
            });

            searchIcon.addEventListener('click', function (event) {
                event.preventDefault();
                performSearch();
            });
        });