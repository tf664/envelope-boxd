// Function to get movies based on the user's search input
async function fetchMovies(query) {
    const url = `https://imdb8.p.rapidapi.com/auto-complete?q=${encodeURIComponent(query)}`;
    const options = {
        method: 'GET',
        headers: {
            'x-rapidapi-key': '69accaae64msh61b2e842f7a6607p1fa8dbjsnf2e6dbf2350d', // Hide this in production
            'x-rapidapi-host': 'imdb8.p.rapidapi.com'
        }
    };

    try {
        const response = await fetch(url, options);
        const result = await response.json();

        if (result && result.d) {
            // Return the list of movies
            return result.d; // Assuming 'd' contains the array of movies
        } else {
            console.error("Unexpected API response: " + result);
            return [];
        }
    } catch (error) {
        console.error("Error fetching movies ", error);
        return [];
    }
}

function displayMovies(movies) {
    const movieContainer = document.getElementById('movie-list');
    movieContainer.innerHTML = ''; // Clear previous content

    movies.forEach(movie => {
        const movieItem = document.createElement('div'); // Use div instead of li
        movieItem.classList.add('movie'); // Apply styling

        const imageUrl = movie.i?.imageUrl || 'placeholder.png'; 
        const title = movie.l || 'Title not available';

        movieItem.innerHTML = `
            <img src="${imageUrl}" alt="${title}" class="movie-poster">
            <p class="movie-title">${title}</p>
        `;
        movieContainer.appendChild(movieItem); // Append directly to movie-container
    });
}

// Main function to initialize movie fetching and set up search functionality
async function init() {
    const searchInput = document.getElementById('search-input');

    // Initial load with a default query
    let movies = await fetchMovies(''); // Default initial search
    if (movies) displayMovies(movies);

    // Set up search input listener to fetch and display movies based on user input
    searchInput.addEventListener('input', async () => {
        const query = searchInput.value.trim(); // Get the user's input
        if (query) { // Only search if there's an input
            movies = await fetchMovies(query); // Fetch movies based on query
            if (movies) displayMovies(movies); // Display the results
        } else {
            movieList.innerHTML = ''; // Clear results if input is empty
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', init);