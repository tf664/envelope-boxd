<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="page-transition"></div>

    <header class="banner">
        <a href="/EnvelopeBaskd/envelope-baskd/index.php" class="logo">EnvelopeBaskd</a>
        <nav class="menu">
            <div class="menu-item" id="watchlist">Watchlist</div>
            <div class="menu-item" id="reviews">Reviews</div>
            <div class="menu-item" id="about">About</div>
            <div class="menu-item" id="logout">Logout</div>
        </nav>
        <div class="icons">
            <div class="search-container">
                <input type="text" id="search-input" class="search-input" placeholder="Search for a movie...">
                <div class="icon" id="search-icon">🔍</div>
            </div>
        </div>
    </header>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuItems = document.querySelectorAll('.menu-item');
        const transitionEffect = document.querySelector('.page-transition'); // Transition element
        const header = document.querySelector('.banner'); // Header element
        const logo = document.querySelector('.logo'); // Logo element

        // Calculate the header height dynamically and set it as a CSS variable
        const headerHeight = header.offsetHeight; // Dynamically calculate header height
        document.documentElement.style.setProperty('--header-height', `${headerHeight}px`);

        // Add event listeners to menu items
        menuItems.forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent immediate page load

                // Trigger the transition effect
                transitionEffect.classList.add('show');

                // After the transition (500ms), redirect to the new page
                setTimeout(() => {
                    if (item.id === 'watchlist') {
                        window.location.href = 'pagesHTML/watchlist.html';
                    } else if (item.id === 'reviews') {
                        window.location.href = 'pagesHTML/reviews.html';
                    } else if (item.id === 'about') {
                        window.location.href = 'pagesHTML/about.html';
                    } else if (item.id === 'logout') {
                        window.location.href = 'loginSystem/logout.php';
                    }
                }, 500); // 500ms delay to allow transition to complete
            });
        });
    });
</script>


</body>

</html>