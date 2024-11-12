async function getStarwarsData() {
    const url = 'https://imdb8.p.rapidapi.com/auto-complete?q=Star%20Wars';
    const options = {
        method: 'GET',
        headers: {
            'x-rapidapi-key': '69accaae64msh61b2e842f7a6607p1fa8dbjsnf2e6dbf2350d',
            'x-rapidapi-host': 'imdb8.p.rapidapi.com'
        }
    };

    try {
        const response = await fetch(url, options);
        const data = await response.json(); // parse JSON data

        const list = data.d;
        const moviesContainer = document.querySelector('.movies');

        // Clear the container first
        moviesContainer.innerHTML = '';

        list.forEach((result) => {
            const name = result.l;
            const poster = result.i?.imageUrl || ''; // use optional chaining to handle missing images
            const movie = `
                <li class="movie">
                <img src="${poster}" alt="${name}" class="movie-poster">
                <h2 class="movie-title">${name}</h2>
                </li>
                `;
            moviesContainer.innerHTML += movie;
        });
        /* const moviesContainer = document.querySelector('.movies'); moviesContainer.innerHTML = ''; // Clear container if (result && result.d) { result.d.forEach(movie => { if (movie.i && movie.l) { const movieDiv = document.createElement('div'); movieDiv.classList.add('movie'); const img = document.createElement('img'); img.src = movie.i.imageUrl; img.alt = movie.l; const title = document.createElement('p'); title.textContent = movie.l; title.classList.add('movie-title'); movieDiv.appendChild(img); movieDiv.appendChild(title); moviesContainer.appendChild(movieDiv); } }); */
    } catch (error) {
        console.error('Error fetching Star Wars data:', error);
    }
}

getStarwarsData();