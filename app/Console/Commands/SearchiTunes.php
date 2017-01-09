<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\LSR;

class SearchiTunes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:movies {query}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search iTunes for posters not already in the database.';

    protected $url = "http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsSearch";

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
        $term = $this->argument('query');
        $this->info('Searching "'.$term.'"');

        $args = [
          'term' => $term,
          'entity' => 'movie',
          'country' => 'gb',
          'limit' => 200,
        ];

        $searchUrl = $this->url.'?'.http_build_query($args);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $searchUrl);
      	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30");
        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        if(!isset($result->resultCount)) continue;
        $this->info($result->resultCount." result(s) found!");

        if(count($result->results) > 0) {
          foreach($result->results as $r) {

            if(!isset($r->trackName) || !isset($r->artworkUrl100)) continue;

            if(isset($r->releaseDate)) {
              $pieces = explode('-', $r->releaseDate);
              $title = $r->trackName.' ('.$pieces[0].')';
              $year = $pieces[0];
            } else {
              $title = $r->trackName;
              $year = "Unknown";
            }

            $jpgURL = str_replace('100x100', '600x600', $r->artworkUrl100);
            $lcrURL = str_replace('.jpg', '.lcr', $jpgURL);

            $lsr = LSR::where('title', '=', $r->trackName)
                ->where('year', '=', $year)
                ->first();

            if(is_null($lsr)) {
                $this->info("Ripping: '$title'");
                $filename = md5(time()).'.lcr';
                if($this->download($lcrURL, storage_path('lsrs').'/'.$filename)) {
                    $lsr = new LSR();
                    $lsr->title = $r->trackName;
                    $lsr->year = $year;
                    $lsr->file = $filename;
                    $lsr->save();
                    $this->info("Ripped: '$title'");
                } else {
                    $this->error("LSR Unavailable: '$title'");
                }
            } else {
                $this->info("Skipping: '$title'");
            }

          }

          $this->info("Have a nice day! :)");
        }

    }

    private function download($file_source, $file_target) {

        if($rh = @fopen($file_source, 'rb')) {
          $wh = fopen($file_target, 'w+b');
          if (!$rh || !$wh) {
              return false;
          }

          while (!feof($rh)) {
              if (fwrite($wh, fread($rh, 4096)) === FALSE) {
                  return false;
              }
              echo '|';
              flush();
          }

          fclose($rh);
          fclose($wh);
          echo "\n";
          return true;
        }

        return false;
    }


}
