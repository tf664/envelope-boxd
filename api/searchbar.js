document.addEventListener('DOMContentLoaded', function () {
    const searchIcon = document.getElementById('search-icon');
    const searchContainer = document.querySelector('.search-container');
    const searchInput = document.querySelector('.search-input');

    // Toggle search container visibility when search icon is clicked
    searchIcon.addEventListener('click', function () {
        searchContainer.classList.toggle('search-active');
    });

    // Close the search input if clicked outside the search container
    window.addEventListener('click', function (e) {
        if (!searchContainer.contains(e.target) && !searchIcon.contains(e.target)) {
            searchContainer.classList.remove('search-active');
        }
    });
});