<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $this->generateSprints();
    }

    /**
     * @return void
     */
    private function generateSprints(): void
    {
        DB::table('tasks')->truncate();
        DB::table('sprints')->truncate();

        $sprints = [];
        $initialDate = '2021-11-27 09:00:00';
        $taskKeyCount = 1;
        foreach (range(0, 30) as $count) {
            foreach (range(1,2) as $part) {
                if ($part === 2) {
                    $initialDate = date('Y-m-d H:i:s', strtotime('+2 weeks', strtotime($initialDate)));
                }

                if ($part % 2 == 0) {
                    $changeFrom = 'active';
                    $changeTO = 'closed';
                } else {
                    $changeFrom = 'new';
                    $changeTO = 'active';
                }

                $sprintKey = "MV Release 1.{$count}";
                $sprints[] = [
                    'event' => 'jira:sprint_updated',
                    'sprint_key' => $sprintKey,
                    'changed_field' => 'sprintstatus',
                    'changed_from' => $changeFrom,
                    'changed_to' => $changeTO,
                    'author_email' => 'bereznii.d@gmail.com',
                    'author_url' => 'https://jira.atlassian.com/rest/api/2/user?username=bereznii',
                    'author_key' => 'bereznii',
                    'timestamp' => $initialDate,
                    'created_at' => $initialDate,
                    'updated_at' => $initialDate,
                ];

                if ($part === 1) {
                    $this->generateTasks($sprintKey, $initialDate, $taskKeyCount);
                }
            }
        }

        DB::table('sprints')->insert($sprints);
    }

    /**
     * @param string $sprintKey
     * @param string $sprintStartDate
     * @return void
     * @throws \Exception
     */
    private function generateTasks(string $sprintKey, string $sprintStartDate, int &$taskKeyCount): void
    {
        $tasks = [];

        foreach ($this->getUsers() as $userName) {
            $tasksForSprint = $this->getPossibleStoryPointsArrangement();
            shuffle($tasksForSprint);

            foreach ($tasksForSprint as $storyPointsForTask) {
                $taskKey = "MV-{$taskKeyCount}";
                foreach ($this->getTaskHistory($storyPointsForTask, $sprintStartDate) as $taskEvent) {
                    $tasks[] = [
                        'event' => 'jira:issue_updated',
                        'sprint_key' => $sprintKey,
                        'sprint_started_at' => $sprintStartDate,
                        'task_key' => $taskKey,
                        'task_url' => "https://jira.atlassian.com/rest/api/2/issue/{$sprintKey}",
                        'task_dev_sp' => $taskEvent['task_dev_sp'],
                        'task_qa_sp' => $this->getQaSpForTaskSp($storyPointsForTask),
                        'task_type' => "issue",
                        'task_created_at' => $taskEvent['task_created_at'],
                        'changed_field' => 'issuestatus',
                        'changed_from' => $taskEvent['statusFrom'],
                        'changed_to' => $taskEvent['statusTo'],
                        'author_email' => "{$userName}@gmail.com",
                        'author_url' => "https://jira.atlassian.com/rest/api/2/user?username={$userName}",
                        'author_key' => $userName,
                        'timestamp' => null,
                        'created_at' => null,
                        'updated_at' => null,
                    ];
                    $taskKeyCount++;
                }
            }
        }

        DB::table('tasks')->insert($tasks);
    }

    /**
     * @param int $taskStoryPoints
     * @param string $sprintStartDate
     * @return \Generator
     */
    private function getTaskHistory(int $taskStoryPoints, string $sprintStartDate)
    {
        $lastValue = 'Backlog';
        $taskCreatedAt = $this->getRandomTaskCreatedAt($sprintStartDate);

        foreach ($this->getTaskStatuses() as $status) {
            yield [
                'task_dev_sp' => $taskStoryPoints,
                'task_created_at' => $taskCreatedAt,
                'statusFrom' => $lastValue,
                'statusTo' => $status,
            ];
            $lastValue = $status;
        }
    }

    /**
     * @param string $sprintStartDate
     * @return false|string
     * @throws \Exception
     */
    private function getRandomTaskCreatedAt(string $sprintStartDate): string
    {
        $days = random_int(1, 60);
        return date('Y-m-d H:i:s', strtotime('-' . $days . ' day', strtotime($sprintStartDate)));
    }

    /**
     * @param int $sp
     * @return string
     * @throws \Exception
     */
    private function getTaskStartedAtForPoints(int $sp, $taskStartedAt): string
    {
        switch ($sp) {
            case 13:
                $hours = 52;
                break;
            case 8:
                $hours = 32;
                break;
            case 5:
                $hours = 20;
                break;
            case 3:
                $hours = 12;
                break;
            case 2:
                $hours = 8;
                break;
            case 1:
            default:
                $hours = 4;
                break;
        }

        $start = new DateTime($taskStartedAt);

        foreach (range(0,500) as $tryHours) {
            $end = isset($end)
                ? date('Y-m-d H:i:s', strtotime('+1 hours', strtotime($end)))
                : date('Y-m-d H:i:s', strtotime('+1 hours', strtotime($taskStartedAt)));
            $endObj = new DateTime($end);
            $res = $this->getWorkingHours($start, $endObj, self::getWorkingHoursArray());

            if ($res === $hours) {
                return $end;
            }
        }

        throw new \RuntimeException('ERROR');
    }

    /**
     * @return string[]
     */
    private function getTaskStatuses(): array
    {
        //TODO: Backlog -> Todo -> Blocked -> InProgress -> Autotesting -> ForReview -> InReview
        // -> ForTesting -> InTesting -> ForBuild -> InBuild -> BuildTesting -> ProdTesting -> Done
        return [
//            'Backlog',
            'Todo',
//            'Blocked',
            'InProgress',
            'Autotesting',
            'ForReview',
            'InReview',
            'ForTesting',
            'InTesting',
            'ForBuild',
            'InBuild',
            'BuildTesting',
            'ProdTesting',
            'Done',
        ];
    }

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @param array $working_hours
     * @return int|mixed
     */
    function getWorkingHours(DateTime $start, DateTime $end, array $working_hours)
    {
        $seconds = 0; // Total working seconds

        // Calculate the Start Date (Midnight) and Time (Seconds into day) as Integers.
        $start_date = clone $start;
        $start_date = $start_date->setTime(0, 0, 0)->getTimestamp();
        $start_time = $start->getTimestamp() - $start_date;

        // Calculate the Finish Date (Midnight) and Time (Seconds into day) as Integers.
        $end_date = clone $end;
        $end_date = $end_date->setTime(0, 0, 0)->getTimestamp();
        $end_time = $end->getTimestamp() - $end_date;

        // For each Day
        for ($today = $start_date; $today <= $end_date; $today += 86400) {

            // Get the current Weekday.
            $today_weekday = date('w', $today);

            // Skip to next day if no hours set for weekday.
            if (!isset($working_hours[$today_weekday][0]) || !isset($working_hours[$today_weekday][1])) continue;

            // Set the office hours start/finish.
            $today_start = $working_hours[$today_weekday][0];
            $today_end = $working_hours[$today_weekday][1];

            // Adjust Start/Finish times on Start/Finish Day.
            if ($today === $start_date) $today_start = min($today_end, max($today_start, $start_time));
            if ($today === $end_date) $today_end = max($today_start, min($today_end, $end_time));

            // Add to total seconds.
            $seconds += $today_end - $today_start;

        }

        return $seconds / 60 / 60;
    }

    /**
     * @return int[]
     * @throws \Exception
     */
    private function getPossibleStoryPointsArrangement(): array
    {
        // optimal = 16
        $possibleOptions = [
            [8,5,2,1],
            [5,5,3,3],
            [8,2,2,2,2],
            [13,2],//15
            [13,1,2],
            [13,1,1],//15
            [8,1,1,1,2,3],
            [8,3,3,2],
            [5,3,3,3,2],
            [3,3,3,2,2,2,1],
            [3,1,1,1,5,5],
            [3,3,2,1,1,5,1],
            [3,2,2,2,1,5],//15
            [8,2,1,1,1,1,2],
            [8,2,1,1,1,1,2],
            [8,5,1,1,1],
        ];

        $randomKey = random_int(0,count($possibleOptions)-1);
        return $possibleOptions[$randomKey];
    }

    /**
     * @return string[]
     */
    private function getUsers(): array
    {
        return [
            'bart',
            'marge',
            'maggie',
            'homer',
            'lisa',
        ];
    }

    /**
     * @param int $taskSp
     * @return int
     */
    private function getQaSpForTaskSp(int $taskSp): int
    {
        switch ($taskSp) {
            case 13:
                return 5;
            case 8:
            case 5:
                return 3;
            case 3:
                return 2;
            case 2:
                return 1;
            case 1:
            default:
                return 0;
        }
    }

    /**
     * @return array
     */
    private static function getWorkingHoursArray(): array
    {
        return [
            null,
            [9*60*60,17*60*60,],
            [9*60*60,17*60*60,],
            [9*60*60,17*60*60,],
            [9*60*60,17*60*60,],
            [9*60*60,17*60*60,],
            null,
        ];
    }
}
