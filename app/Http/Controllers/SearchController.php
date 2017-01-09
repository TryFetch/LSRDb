<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\LSR;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $title = $request->get('title');
        $year = $request->get('year');

        $GS = new \GoSquared([
            'site_token' => 'GSN-720413-S',
            'api_key' => 'FMH19BQ6KBRIFDPF',
        ]);

        $person = $GS->Person(1);
        $person->identify(['id' => 1, 'email' => 'user@domain.com']);
        $person->track_event('Search', [
            'title' => $title,
            'year' => $year,
        ]);

        $GS->track_event('Search', [
            'title' => $title,
            'year' => $year,
        ]);

        if($year) {
            $lsr = LSR::where('title', 'like', $title)
                ->orWhere('title', 'like', "$title ($year)")
                ->where('year', $year)
                ->first();
        } else {
            $lsr = LSR::where('title', 'like', $title)
                ->first();
        }

        if($lsr != null) {
            return response()->download(storage_path('lsrs/'.$lsr->file));
        } elseif($request->get('fallback')) {
            $fallback = $request->get('fallback');
            $buffer = file_get_contents($fallback);
            $finfo = new \finfo(FILEINFO_MIME);
            $mime = $finfo->buffer($buffer);
            return response($buffer)
                ->header('Content-Type', $mime);
        }

        abort(404);
        return;

    }

  }
