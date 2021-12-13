<?php

namespace App\Services;

use App\Repositories\MetricsRepository;

class MetricsService
{
    /** @var MetricsRepository */
    private $metricsRepository;

    /**
     * @param MetricsRepository $metricsRepository
     */
    public function __construct(MetricsRepository $metricsRepository)
    {
        $this->metricsRepository = $metricsRepository;
    }

    /**
     * @param array $dates
     * @return \Illuminate\Support\Collection
     */
    public function calculateMetrics(array $dates)
    {
        return collect([
            'AverageShareOfDevelopmentAndDeliveryChart' => $this->metricsRepository->getAverageShareOfDevelopmentAndDeliveryChart($dates),
            'TimeToMarketChart' => $this->metricsRepository->getTimeToMarketChart($dates),
            'TasksInStatusesTimeChart' => $this->metricsRepository->getTasksInStatusesTimeChart($dates),
            'DescriptionChangeChart' => $this->metricsRepository->getDescriptionChangeChart($dates),
            'TeamSpeedChart' => $this->metricsRepository->getTeamSpeedChart($dates),
            'AverageBugPercentagechart' => $this->metricsRepository->getAverageBugPercentagechart($dates),
            'BugLifetimechart' => $this->metricsRepository->getBugLifetimechart($dates),
            'Todochart' => $this->metricsRepository->getTodochart($dates),
        ]);
    }
}
