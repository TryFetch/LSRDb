# LSRDb
### An open and free database of parallax posters for tvOS.

LSRDb was built for the Apple TV version of Fetch to provide the parallax posters that are common throughout tvOS. It's a simple Laravel install with a very basic API. LCR files are scraped from the iTunes Store using the `SearchiTunes` and `Chug` console commands.

LSRDb currently only scrapes movie posters from iTunes as the TV shows are in a square format which was not used by Fetch. There are a limited number of LCR files for TV shows which have been created manually in Photoshop.

## Installation

Clone the repo and run `composer install`. Run the migrations using Artisan and you're then good to run the commands mentioned above.
