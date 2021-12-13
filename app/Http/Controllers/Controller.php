<?php

namespace App\Http\Controllers;

use App\Http\Resources\DefaultCollection;
use App\Services\MetricsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var MetricsService */
    private $metricsService;

    /**
     * @param MetricsService $metricsService
     */
    public function __construct(MetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('index');
    }

    /**
     * @param Request $request
     * @return DefaultCollection
     */
    public function metrics(Request $request)
    {
        return new DefaultCollection(
            $this->metricsService->calculateMetrics($request->all())
        );
    }
}
