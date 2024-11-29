async function fetchMovies(query) {
    const url = `https://online-movie-database.p.rapidapi.com/v2/search?searchTerm==${encodeURIComponent(query)}`;
    const options = {
        method: 'GET',
        headers: {
         'x-rapidapi-key': 'd3c650deedmsh7c3e51e84954f61p1aa812jsn45d37325bbd5',
		'x-rapidapi-host': 'online-movie-database.p.rapidapi.com'
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

        if (result?.data?.mainSearch?.edges) {
            // Filters movies based on the length of principalCredits
            const movies = result.data.mainSearch.edges
                .map(edge => {
                    const entity = edge.node.entity;

                    // Checks if the movie has more than 0 principalCredits
                    if (entity.principalCredits && entity.principalCredits.length > 0) {
                        return {
                            title: entity.titleText?.text || 'Unknown Title',
                            imageUrl: entity.primaryImage?.url || 'placeholder.png',
                        };
                    }
                    return null; // If there is no principalCredits, the movie gets excluded
                })
                .filter(movie => movie !== null);

            return movies;
        } else {
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
    movieContainer.innerHTML = ''; // Clears previous content

    if (movies.length === 0) {
        movieContainer.innerHTML = "<p>No movies or shows found.</p>";
        return;
    }

    for (const movie of movies) {
        const movieItem = document.createElement('div');
        movieItem.classList.add('movie-item');

        // Uses the movie title in the URL query string
        movieItem.innerHTML = `
            <a href="pages/specificMovie.php?title=${encodeURIComponent(movie.title)}" class="movie-link">
                <img src="${movie.imageUrl}" alt="${movie.title}" class="movie-poster">
                <p class="movie-title">${movie.title}</p>
            </a>
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