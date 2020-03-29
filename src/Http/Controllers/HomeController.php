<?php

namespace Kielabokkie\GuzzleApiService\Http\Controllers;

use Illuminate\Routing\Controller;
use Kielabokkie\GuzzleApiService\Models\ApiLog;

class HomeController extends Controller
{
    /**
     * Display the Telescope view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = ApiLog::all();

        return view('api-service::layout', compact('logs'));
    }
}
