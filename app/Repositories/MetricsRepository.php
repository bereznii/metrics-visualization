<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class MetricsRepository
{
    /**
     * @return array[]
     */
    public function getAverageShareOfDevelopmentAndDeliveryChart(array $dates): array
    {
        $res = DB::select("
            SELECT
                t1.sprint_key,
                SUM(t1.timestamp) as dev_time,
                t2.dep_time
            FROM tasks t1
            JOIN
                (SELECT
                    sprint_key,
                    SUM(timestamp) as dep_time
                FROM tasks
                WHERE changed_field = 'issuestatus'
                    && changed_to IN ('InTesting','ForBuild','InBuild','BuildTesting','ProdTesting')
                GROUP BY sprint_key)
                t2 ON t1.sprint_key = t2.sprint_key
            WHERE changed_field = 'issuestatus'
                && t1.changed_to IN ('InProgress','Autotesting','ForReview','InReview','ForTesting')
                && sprint_started_at BETWEEN ? AND ?
            GROUP BY t1.sprint_key;
        ", array_values($dates));

        $devTimeArray = array_map(fn ($item) => $item->dev_time, $res);
        $depTimeArray = array_map(fn ($item) => $item->dep_time, $res);
        $sprints = array_map(fn ($item) => $item->sprint_key, $res);

        return [
            'sprints' => $sprints,
            'data' => [
                [
                    'name' => 'Розробка',
                    'data' => $devTimeArray
                ],
                [
                    'name' => 'Доставка',
                    'data' => $depTimeArray
                ]
            ]
        ];
    }

    /**
     * @return \int[][][]
     */
    public function getTimeToMarketChart(array $dates)
    {
        $res = DB::select("
            SELECT
                task_dev_sp,
                AVG(t1.time_to_market) as time_to_market
            FROM (SELECT
                task_dev_sp,
                SUM(timestamp) time_to_market
            FROM tasks
            WHERE sprint_started_at BETWEEN ? AND ?
            GROUP BY task_key, task_dev_sp ORDER BY task_dev_sp ASC) t1
            GROUP BY t1.task_dev_sp;
        ", array_values($dates));

        $timeToMarket = array_map(fn ($item) => $item->time_to_market, $res);

        return [
            ['data' => $timeToMarket]
        ];
    }

    /**
     * @return \int[][][]
     */
    public function getTasksInStatusesTimeChart(array $dates)
    {
        $res = DB::select("
            SELECT
                changed_from,
                AVG(timestamp) as avg_time
            FROM tasks
            WHERE changed_field = 'issuestatus'
            && sprint_started_at BETWEEN ? AND ?
            GROUP BY changed_from;
        ", array_values($dates));

        $series = array_map(fn ($item) => $item->avg_time, $res);

        return [
            ['data' => $series]
        ];
    }

    /**
     * @return \int[][][]
     */
    public function getDescriptionChangeChart(array $dates)
    {
        $res = DB::select("
            SELECT
                tasks.sprint_key,
                IFNULL(t2.count_summary_change, 0) AS count_summary_change
            FROM tasks
            LEFT JOIN (SELECT
                           sprint_key,
                           COUNT(*) count_summary_change
                       FROM tasks
                       WHERE changed_field = 'issuesummary'
                        && sprint_started_at BETWEEN ? AND ?
                       GROUP BY sprint_key) t2
            ON tasks.sprint_key = t2.sprint_key
            GROUP BY sprint_key, t2.count_summary_change;
        ", array_values($dates));

        $series = array_map(fn ($item) => $item->count_summary_change, $res);
        $sprints = array_map(fn ($item) => $item->sprint_key, $res);

        return [
            'sprints' => $sprints,
            'data' => [
                [
                    'name' => 'Задач зі зміненим описом',
                    'data' => $series
                ]
            ]
        ];
    }

    /**
     * @return \int[][][]
     */
    public function getTeamSpeedChart(array $dates)
    {
        $res = DB::select("
            SELECT
                sprint_key,
                SUM(task_dev_sp) sum_dev_sp
            FROM tasks
            WHERE changed_field = 'issuestatus'
                && changed_from like 'ToDo'
                && sprint_started_at BETWEEN ? AND ?
            GROUP BY sprint_key;
        ", array_values($dates));

        $series = array_map(fn ($item) => $item->sum_dev_sp, $res);
        $sprints = array_map(fn ($item) => $item->sprint_key, $res);

        return [
            'sprints' => $sprints,
            'data' => [
                [
                    'name' => 'Кількість Story Points',
                    'data' => $series
                ],
            ]
        ];
    }

    /**
     * @return \int[][][]
     */
    public function getAverageBugPercentagechart(array $dates)
    {
        $res = DB::select("
            SELECT
                tasks.sprint_key,
                CONCAT(
                    CAST(IFNULL(t2.count_bugs, 0) AS UNSIGNED) / t3.count_tasks * 100,
                    '%'
                ) AS bug_percent
            FROM tasks
            LEFT JOIN (SELECT
                        sprint_key,
                        COUNT(*) count_bugs
                    FROM tasks
                    WHERE task_type = 'bug' && changed_from like 'ToDo'
                    GROUP BY sprint_key) t2
                   ON tasks.sprint_key = t2.sprint_key
            LEFT JOIN (SELECT
                           sprint_key,
                           count(*) count_tasks
                       FROM tasks
                       WHERE changed_field = 'issuestatus' && changed_from like 'ToDo'
                       GROUP BY sprint_key) t3
                   ON tasks.sprint_key = t3.sprint_key
            && sprint_started_at BETWEEN ? AND ?
            GROUP BY sprint_key, t2.count_bugs, t3.count_tasks;
        ", array_values($dates));

        $series = array_map(fn ($item) => $item->bug_percent, $res);
        $sprints = array_map(fn ($item) => $item->sprint_key, $res);

        return [
            'sprints' => $sprints,
            'data' => [[
                'name' => 'Середня доля багів',
                'data' => $series
            ]],
        ];
    }

    /**
     * @return \int[][][]
     */
    public function getBugLifetimechart(array $dates)
    {
        $res = DB::select("
            SELECT
                task_key,
                SUM(timestamp) bug_fix_time
            FROM tasks
            WHERE task_type = 'bug'
            && sprint_started_at BETWEEN ? AND ?
            GROUP BY task_key;
        ", array_values($dates));

        $series = array_map(fn ($item) => $item->bug_fix_time, $res);
        $sprints = array_map(fn ($item) => $item->task_key, $res);

        return [
            'sprints' => $sprints,
            'data' => [
                [
                    'name' => 'Час життя багів в годинах',
                    'data' => $series
                ],
            ],
        ];
    }

    /**
     * @return \int[][][]
     */
    public function getTodochart(array $dates)
    {
        $res = DB::select("
            SELECT
                tasks.sprint_key,
                IFNULL(t2.count_reopens, 0) AS count_reopens
            FROM tasks
            LEFT JOIN (SELECT
                    sprint_key,
                    COUNT(*) count_reopens
                FROM tasks
                WHERE changed_to = 'ToDo'
                && sprint_started_at BETWEEN ? AND ?
                GROUP BY sprint_key) t2
            ON tasks.sprint_key = t2.sprint_key
            GROUP BY sprint_key, t2.count_reopens;
        ", array_values($dates));

        $series = array_map(fn ($item) => $item->count_reopens, $res);
        $sprints = array_map(fn ($item) => $item->sprint_key, $res);

        return [
            'sprints' => $sprints,
            'data' => [
                [
                    'name' => 'Кількість перевідкритих задач',
                    'data' => $series
                ],
            ],
        ];
    }
}
