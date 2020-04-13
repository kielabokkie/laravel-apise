<?php

namespace Kielabokkie\Apise\Http\Controllers;

use Illuminate\Routing\Controller;
use Kielabokkie\Apise\Models\ApiLog;

class HomeController extends Controller
{
    /**
     * Display the Apise view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = ApiLog::all()->sortByDesc('id');

        return view('apise::layout', compact('logs'));
    }

    /**
     * Retrieve the logs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logs()
    {
        $logs = ApiLog::all()->sortByDesc('id');

        return response()->json($logs->values());
    }
}
