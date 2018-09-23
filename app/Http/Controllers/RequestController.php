<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = $request -> all();
        $url = $url['url'];
        $handle = curl_init();
        $url = env('API_URL').$url;
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($handle);
        curl_close($handle);
        return $output;
    }
}
