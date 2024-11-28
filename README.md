# Envelope Baskd
Envelope Baskd is a website to save movies in a watchlist and review them, that allows users to log in, view movies, and submit their personal reviews with ratings. This platform is built using PHP and MySQL in a retro pixel design.
## Features

- User Login and Registering: Users can log in and access their personal review lists.
- Movie Reviews: Users can write, update, and delete reviews for movies.
- Rating System: A rating system from 1 to 10 is available for each movie review.

## Tech Stack

**PHP:** 
**JavaScript** 
**HTML** 
**CSS**

**MySQL** Database management system

**API**: Online Movie Database by apidojo





## Installation

Put workspace folder into xampp's htdocs folder

```bash
C:\xampp\htdocs\EnvelopeBaskd\envelope-baskd
```
Correct naming is important


Put the database "userdb" into 

```bash
C:\xampp\mysql\data
```
Or import it via phpMyAdmin via the import function
    
## Structure

```bash
/envelope-baskd/
├── /.vscode/  
├── /api/
|   ├── getMovies.js        # API search calls 
|   ├── searchBar.js        # animation for searchbar
├── /loginSystem/           # Login and authentication-related files
│   ├── connection.php      # Database connection details
|   ├── login.php           
|   ├── loginSystemStyle.css # specific tylesheet for login/register         
|   ├── logout.php          
├── /pages/                 # Stores the pages being navigated by header.php
│   ├── about.php
│   ├── reviews.php
│   ├── specificMovie.php
│   ├── watchlist.php
├── /watchlist/           # contains logic for watchlist db
│   ├── addToWatchlist.php
│   ├── removeFromWatchlist.php
├── /review/              # contains logic for review db
│   ── addToReview.php
├── header.php            # headerbar on top of every page
├── index.php             # mainpage 
├── placeholder.png       # placeholder for missing movie-poster
├── README.md             # This file  :D
├── styles.css            # general styling
```bash


### Known problems
- Can't change review-text or rating for single movie but instead changes review properties for all movies
- can add the same movies multiple times for reviews

