<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ManageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('manage');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $lsr = new \App\LSR();
      $lsr->title = $request->get('title');
      $lsr->year = $request->get('year');

      $filename = md5(time()).'.lsr';
      $request->file('lsr')->move(storage_path('lsrs'), $filename);

      $lsr->file = $filename;
      $lsr->save();

      return redirect('manage');

    }

}
