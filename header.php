<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="headerStyle.css">
</head>
<body>
    <div class="page-transition"></div>

    <header class="banner">
        <div class="logo">EnvelopeBaskd</div>
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

            // Calculate header height and set as CSS variable
            const headerHeight = header.offsetHeight; // Dynamically calculate header height
            document.documentElement.style.setProperty('--header-height', `${headerHeight}px`);

            menuItems.forEach(item => {
                item.addEventListener('click', function (event) {
                    event.preventDefault(); // Prevent immediate page load

                    transitionEffect.classList.add('show');
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
                    }, 500); // 500ms delay to let the animation complete
                });
            });
        });
    </script>
</body>
</html>