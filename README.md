# WP Rest API Plugin
 WordPress plugin using REST API fetches data from https://jsonplaceholder.typicode.com/ , filters it & displays according to userID. It uses AJAX for all the requests except the inital data on the table
 
# Requirements
- WordPress 5.0
- PHP 7.4
- MySQL 5.6

# Installation and usage instructions
Set up instructions:
- Download the plugin & Install it, do not activate it.
- Open terminal, navigate to this plugin directory and run `composer install`
- Activate the plugin, then navigate to https://YOURURL/custom_url
- There is no admin page for the plugin, everything runs on the page above.

# non-obvious implementation choices
I decided to use GuzzleHttp instead of wp_request() so that I could use composer, & also give myself abit of a challenge by using someting new.
The custom_url implementation can be improved, at the moment there's a 404 error on the console, this also disables the use of dashicons for some reason.

# possible improvements
- The AJAX calls are quite slow, if the data is increased there'll be a problem
- the custom url implementation needs some work
- I'd use SASS instead of plain CSS 
