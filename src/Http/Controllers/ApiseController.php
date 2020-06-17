<?php

namespace Kielabokkie\Apise\Http\Controllers;

use Illuminate\Routing\Controller;
use Kielabokkie\Apise\Models\ApiLog;
use Kielabokkie\Apise\Traits\AuthorizesRequests;

class ApiseController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the Apise dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('apise::layout');
    }

    /**
     * Retrieve the logs, 20 at a time.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logs($fromId = null)
    {
        $totalRecords = ApiLog::count();

        $query = ApiLog::orderBy('id', 'desc')
            ->take(20);

        if ($fromId !== null) {
            $query->where('id', '<', $fromId);
        }

        $logs = $query->get()->toArray();

        $data = [
            'logs' => $logs,
            'total' => $totalRecords
        ];

        return response()->json($data);
    }

    /**
     * Retrieve the latest logs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest($fromId)
    {
        $logs = ApiLog::where('id', '>', $fromId)
            ->whereNotNull('status_code')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        return response()->json($logs);
    }
}
