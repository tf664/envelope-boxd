document.addEventListener('DOMContentLoaded', () => {
    const searchIcon = document.getElementById('search-icon');
    const searchInput = document.getElementById('search-input');
    const menu = document.querySelector('.menu');
    const searchContainer = document.querySelector('.search-container');

    // Toggles search input visibility
    searchIcon.addEventListener('click', () => {
        if (searchContainer.classList.contains('search-active')) {
            // Collapse
            searchContainer.classList.remove('search-active');
            menu.classList.remove('search-active');
        } else {
            // Expand
            searchContainer.classList.add('search-active');
            menu.classList.add('search-active');
        }
    });
});