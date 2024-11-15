document.addEventListener('DOMContentLoaded', function() {
    const searchIcon = document.getElementById('search-icon');
    const searchContainer = document.querySelector('.search-container');

    searchIcon.addEventListener('click', function() {
        searchContainer.classList.toggle('search-active'); // toggles the search-active class on click
    });
});