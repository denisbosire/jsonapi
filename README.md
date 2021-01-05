# WP Rest API Plugin
 WordPress plugin using REST API fetches data from https://jsonplaceholder.typicode.com/ , filters it & displays according to userID. It uses AJAX for all the requests except the inital data on the table
 
# Requirements (tested on)
- WordPress 5.0
- PHP +7
- MySQL 5.5

# Installation and usage instructions
You can download & install the plugin without any special requirements
- After activatinng the plugin navigate to https://YOURURL/custom_url
- there is no admin page for the plugin, everything runs on the page above.
* if this plugin was publicly available, I would have set it up as a composer package, unfortunately this is not possible with a private repo like this one.

# non-obvious implementation choices
I decided to use GuzzleHttp instead of wp_request() so that I could use composer, & also give myself abit of a challenge by using someting new.
The custom_url implementation can be improved, at the moment there's a 404 error on the console, this also disables the use of dashicons for some reason.

# possible improvements
- The AJAX calls are quite slow, if the data is increased there will be a problem
- the custom url implementation needs some work
- I'd use SASS instead of plain CSS 
- use wp packagist to use the plugin as a composer package

# New Challenges faced
- I have never done unit testing on WP, using phpunit was pretty straight forward although I found it difficult to write proper tests for this plugin, I would have used brain monkey if the plugin itself utilized WP functions in the Get_Rest_Api class. This is something I'm willing to learn, & would probably incorporate in future plugins
- Using composer: although I have experience using composer on laravel, converting the plugin into a composer package was slightly difficult since WP packagist requires a public repo (according to my knowledge).
