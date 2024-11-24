<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>

    <!-- External or Internal CSS -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* CSS for page transition */
        .page-transition {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8); /* Black background with opacity */
            transform: translateX(100%); /* Initially off-screen */
            transition: transform 0.5s ease, opacity 0.5s ease;
            opacity: 0;
            z-index: 9999;
        }

        .page-transition.show {
            transform: translateX(0); /* Slide into view */
            opacity: 1; /* Fade in */
        }
    </style>
</head>
<body>
    <!-- Page transition element -->
    <div class="page-transition"></div>

    <header class="banner">
        <div class="logo">EnvelopeBaskd</div>
        <nav class="menu">
            <div class="menu-item" id="watchlist">Watchlist</div>
            <div class="menu-item" id="reviews">Reviews</div>
            <div class="menu-item" id="about">About</div>
            <div class="menu-item" id="account">Account</div>
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
                        } else if (item.id === 'account') {
                            window.location.href = 'pagesHTML/account.html';
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
