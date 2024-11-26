const movieContainer = document.getElementById('movie-list');
const scrollLeftButton = document.getElementById('scroll-left');
const scrollRightButton = document.getElementById('scroll-right');

const scrollAmount = 300; // Adjust the scroll amount as needed

scrollLeftButton.addEventListener('click', () => {
    movieContainer.scrollBy({
        left: -scrollAmount,
        behavior: 'smooth'
    });
});

scrollRightButton.addEventListener('click', () => {
    movieContainer.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
    });
});