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

<!-- Animation Effect -->
<div class="page-transition"></div>

<!-- JavaScript Code -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuItems = document.querySelectorAll('.menu-item');
        const transitionEffect = document.querySelector('.page-transition'); // Transition element

        menuItems.forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent immediate page load

                // Start the page transition animation
                transitionEffect.classList.add('show'); // Show the transition effect

                // Delay the actual page navigation to sync with the animation duration
                setTimeout(() => {
                    // Redirect to the corresponding page after the animation
                    if (item.id === 'watchlist') {
                        window.location.href = 'pagesHTML/watchlist.php';
                    } else if (item.id === 'reviews') {
                        window.location.href = 'pagesHTML/reviews.php';
                    } else if (item.id === 'about') {
                        window.location.href = 'pagesHTML/about.php';
                    } else if (item.id === 'account') {
                        window.location.href = 'pagesHTML/account.php';
                    } else if (item.id === 'logout') {
                        window.location.href = 'pagesHTML/logout.php';
                    }
                }, 500); // 500ms delay to let the animation complete
            });
        });
    });
</script>