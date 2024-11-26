async function fetchMovies(query) {
    const url = `https://imdb8.p.rapidapi.com/v2/search?searchTerm=${encodeURIComponent(query)}`;
    const options = {
        method: 'GET',
        headers: {
            'x-rapidapi-key': '099c93e59bmsh5b63b7ecbb52195p1ea84djsn00ea468a0e19', // Hide this in production
            'x-rapidapi-host': 'imdb8.p.rapidapi.com'
        }
    };

    try {
        const response = await fetch(url, options);

        if (response.status === 429) {
            console.error("API Rate limit exceeded");
            return [];
        }

        if (!response.ok) {
            console.error(`Error fetching movies: ${response.statusText}`);
            return [];
        }

        const result = await response.json();

        // Check if the expected structure is present
        if (result?.data?.mainSearch?.edges) {
            // Filter movies based on the length of principalCredits
            const movies = result.data.mainSearch.edges
                .map(edge => {
                    const entity = edge.node.entity;

                    // Check if the movie has more than 0 principalCredits
                    if (entity.principalCredits && entity.principalCredits.length > 0) {
                        return {
                            title: entity.titleText?.text || 'Unknown Title',
                            imageUrl: entity.primaryImage?.url || 'placeholder.png',
                        };
                    }
                    return null; // If no principalCredits, exclude this movie
                })
                .filter(movie => movie !== null); // Remove null values (movies without principalCredits)

            return movies;
        } else {
            // Log the unexpected response structure for debugging
            console.error("Unexpected API response:", JSON.stringify(result, null, 2));
            return [];
        }
    } catch (error) {
        console.error("Error fetching movies:", error);
        return [];
    }
}


async function displayMovies(movies) {
    const movieContainer = document.getElementById('movie-list');
    movieContainer.innerHTML = ''; // Clear previous content

    if (movies.length === 0) {
        movieContainer.innerHTML = "<p>No movies or shows found.</p>";
        return;
    }

    for (const movie of movies) {
        const movieItem = document.createElement('div');
        movieItem.classList.add('movie-item');

        movieItem.innerHTML = `
            <img src="${movie.imageUrl}" alt="${movie.title}" class="movie-poster">
            <p class="movie-title">${movie.title}</p>
        `;

        movieContainer.appendChild(movieItem);
    }
}

async function init() {
    const searchInput = document.getElementById('search-input');

    searchInput.addEventListener('keydown', async (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            const query = searchInput.value.trim();

            if (query) {
                const movies = await fetchMovies(query);
                await displayMovies(movies);
            } else {
                displayMovies([]);
            }
        }
    });
}
// Initialize on page load
document.addEventListener('DOMContentLoaded', init);