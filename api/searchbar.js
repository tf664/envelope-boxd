document.addEventListener('DOMContentLoaded', () => {
    const searchIcon = document.getElementById('search-icon');
    const searchInput = document.getElementById('search-input');
    const menu = document.querySelector('.menu');
    const searchContainer = document.querySelector('.search-container');

    // Toggle search input visibility
    searchIcon.addEventListener('click', () => {
        if (searchContainer.classList.contains('search-active')) {
            // Collapse the search bar
            searchContainer.classList.remove('search-active');
            menu.classList.remove('search-active');
        } else {
            // Expand the search bar
            searchContainer.classList.add('search-active');
            menu.classList.add('search-active');
        }
    });
});