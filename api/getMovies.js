// Function to get movies based on the user's search input
async function fetchMovies(query) {
    const url = `https://imdb8.p.rapidapi.com/auto-complete?q=${encodeURIComponent(query)}`;
    const options = {
        method: 'GET',
        headers: {
            'x-rapidapi-key': '099c93e59bmsh5b63b7ecbb52195p1ea84djsn00ea468a0e19', // Hide this in production
            'x-rapidapi-host': 'imdb8.p.rapidapi.com'
        }
    };

    try {
        const response = await fetch(url, options);

        if (response.status === 429) { // API rate limit error code
            console.error("API Rate limit exceeded");
            displayMovies([]); // Clear the movie container 
            return; 
        }

        const result = await response.json();

        if (result && result.d) {
            // filters for only movies and shows
            const filteredResults = result.d.filter(item => item.qid === 'movie' || item.qid === 'tvSeries');
            return filteredResults;
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

    if (movies.length === 0) {
        movieContainer.innerHTML = "<p>No movies or shows found.</p>"; // Show message if no results
        return;
    } 

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

    // Event listener for Enter key to trigger the search
    searchInput.addEventListener('keydown', async (event) => {
        if (event.key === "Enter") {
            event.preventDefault(); // Prevent form submission

            const query = searchInput.value.trim(); // Get the user's input
            if (query) {
                const movies = await fetchMovies(query); // Fetch movies based on query
                displayMovies(movies); // Display the results
            } else {
                displayMovies([]); // Clear results if input is empty
            }
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', init);
