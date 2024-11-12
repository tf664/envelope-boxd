<?php
function getStarWars()
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://imdb8.p.rapidapi.com/auto-complete?q=Star%20Wars",  // Search for Star Wars movies
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: imdb8.p.rapidapi.com",
            "x-rapidapi-key: 69accaae64msh61b2e842f7a6607p1fa8dbjsnf2e6dbf2350d"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        // Decode JSON response and assign it to $data
        $data = json_decode($response, true);

        // Check if the 'd' key exists (which contains the movie data)
        if (isset($data['d'])) {
            // Loop through the movie data and display titles and poster images
            foreach ($data['d'] as $movie) {
                // Only display movies with a poster image
                if (isset($movie['i']['imageUrl']) && isset($movie['l'])) {
                    $poster_url = $movie['i']['imageUrl']; // Movie poster image URL
                    $title = $movie['l']; // Movie title

                    // Display the movie poster and title
                    echo "<div class='movie'>";
                    echo "<img src='" . $poster_url . "' alt='" . htmlspecialchars($title) . "' class='movie-poster'>";
                    echo "<p class='movie-title'>" . htmlspecialchars($title) . "</p>";
                    echo "</div>";
                }
            }
        } else {
            echo "No Star Wars movies found or the API response is unexpected.";
        }
    }
}
?>