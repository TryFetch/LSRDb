<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Chug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chug through TMDB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $url = "http://api.themoviedb.org/3/movie/popular";
      $apiKey = "effc766d4c2565abd1e93fb7f5f7c628";

      for ($i=1; $i < 1000; $i++) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.'?page='.$i.'&api_key='.$apiKey);
      	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        foreach($result->results as $movie) {
          $this->info("FOUND: {$movie->original_title}");
          \Artisan::call('search:movies', [
              'query' => $movie->original_title
          ]);
        }

      }

    }
}
