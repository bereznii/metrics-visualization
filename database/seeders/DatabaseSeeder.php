<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    private const DEV_STATUSES = [
        'InProgress',
        'Autotesting',
        'ForReview',
        'InReview',
        'ForTesting',
    ];

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
     * @throws \Exception
     */
    private function generateSprints(): void
    {
        DB::table('tasks')->truncate();
        DB::table('sprints')->truncate();

        $sprints = [];
        $initialDate = '2021-11-27 09:00:00';
        $taskKeyCount = 1;
//        foreach (range(0, 0) as $count) {
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
     * @param int $taskKeyCount
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
                    $statusUser = in_array($taskEvent['statusTo'], self::DEV_STATUSES)
                        ? $userName
                        : 'lisa';

                    $tasks[] = [
                        'event' => 'jira:issue_updated',
                        'sprint_key' => $sprintKey,
                        'sprint_started_at' => $sprintStartDate,
                        'task_key' => $taskKey,
                        'task_url' => "https://jira.atlassian.com/rest/api/2/issue/{$sprintKey}",
                        'task_dev_sp' => $taskEvent['task_dev_sp'],
                        'task_qa_sp' => $this->getQaSpForTaskSp($storyPointsForTask),
                        'task_type' => $taskEvent['task_type'],
                        'task_created_at' => $taskEvent['task_created_at'],
                        'changed_field' => $taskEvent['changedField'],
                        'changed_from' => $taskEvent['statusFrom'],
                        'changed_to' => $taskEvent['statusTo'],
                        'author_email' => "{$statusUser}@gmail.com",
                        'author_url' => "https://jira.atlassian.com/rest/api/2/user?username={$statusUser}",
                        'author_key' => $statusUser,
                        'timestamp' => $taskEvent['timestamp'],
                    ];
                }
                $taskKeyCount++;
            }
        }

        DB::table('tasks')->insert($tasks);
    }

    /**
     * @param int $taskStoryPoints
     * @param string $sprintStartDate
     * @param bool $reopen
     * @return \Generator
     * @throws \Exception
     */
    private function getTaskHistory(int $taskStoryPoints, string $sprintStartDate)
    {
        $taskType = 'issue';
        if ($taskStoryPoints === 100) { // if issue is a BUG
            $taskType = 'bug';
            $taskStoryPoints = 1;
        }

        $reopen = false;
        $lastValue = 'ToDo';
        $taskCreatedAt = $this->getRandomTaskCreatedAt($sprintStartDate);

        foreach ($this->getTaskStatuses() as $status) {
            if ($taskStoryPoints !== 100 && random_int(0,120) === 0) {
                yield [
                    'task_dev_sp' => $taskStoryPoints,
                    'task_created_at' => $taskCreatedAt,
                    'changedField' => 'issuesummary',
                    'task_type' => $taskType,
                    'statusFrom' => 'from something',
                    'statusTo' => 'to something',
                    'timestamp' => 0,
                ];
            }

            yield [
                'task_dev_sp' => $taskStoryPoints,
                'task_created_at' => $taskCreatedAt,
                'changedField' => 'issuestatus',
                'task_type' => $taskType,
                'statusFrom' => $lastValue,
                'statusTo' => $status,
                'timestamp' => $this->addWorkingHours($taskStoryPoints, $status),
            ];
            $lastValue = $status;

            // Reopen задачи
            if (in_array($status, ['InReview', 'InTesting']) && random_int(0,40) === 0) {
                yield [
                    'task_dev_sp' => $taskStoryPoints,
                    'task_created_at' => $taskCreatedAt,
                    'changedField' => 'issuestatus',
                    'task_type' => $taskType,
                    'statusFrom' => $lastValue,
                    'statusTo' => 'ToDo',
                    'timestamp' => 0,
                ];
                $reopen = true;
                break;
            }
        }

        if ($reopen) {
            foreach ($this->getTaskStatuses() as $status) {
                if ($taskStoryPoints !== 100 && random_int(0,120) === 0) {
                    yield [
                        'task_dev_sp' => $taskStoryPoints,
                        'task_created_at' => $taskCreatedAt,
                        'changedField' => 'issuesummary',
                        'task_type' => $taskType,
                        'statusFrom' => 'from something',
                        'statusTo' => 'to something',
                        'timestamp' => 0,
                    ];
                }

                yield [
                    'task_dev_sp' => $taskStoryPoints,
                    'task_created_at' => $taskCreatedAt,
                    'changedField' => 'issuestatus',
                    'task_type' => $taskType,
                    'statusFrom' => $lastValue,
                    'statusTo' => $status,
                    'timestamp' => $this->addWorkingHours($taskStoryPoints, $status, true),
                ];
                $lastValue = $status;
            }
        }
    }

    /**
     * @param int $taskStoryPoints
     * @param string $status
     * @param bool $reopenScenario
     * @return int
     */
    private function addWorkingHours(int $taskStoryPoints, string $status, bool $reopenScenario = false): ?int
    {
        switch ($taskStoryPoints) {
            case 13: //dev 52 hours
                switch ($status) {
                    case 'Autotesting':
                        return $reopenScenario ? rand(3,7) : rand(49,52);
                    case 'InProgress':
                    case 'ForReview':
                    case 'InReview':
                    case 'ForTesting':
                    case 'InTesting':
                        return rand(0,1);
                    case 'ForBuild':
                        return rand(4,8);
                    case 'InBuild':
                        return rand(2,4);
                    case 'BuildTesting':
                    case 'ProdTesting':
                        return rand(2,4);
                    case 'Done':
                        return rand(4,6);
                    default:
                        return null;
                }
            case 8: //32 hours
                switch ($status) {
                    case 'Autotesting':
                        return $reopenScenario ? rand(2,6) : rand(29,32);
                    case 'InProgress':
                    case 'ForReview':
                    case 'InReview':
                    case 'ForTesting':
                    case 'InTesting':
                        return rand(0,1);
                    case 'ForBuild':
                        return rand(3,6);
                    case 'InBuild':
                        return rand(2,4);
                    case 'BuildTesting':
                    case 'ProdTesting':
                        return rand(2,4);
                    case 'Done':
                        return rand(4,6);
                    default:
                        return null;
                }
            case 5: //20 hours
                switch ($status) {
                    case 'Autotesting':
                        return $reopenScenario ? rand(1,4) : rand(17,20);
                    case 'InProgress':
                    case 'ForReview':
                    case 'InReview':
                    case 'ForTesting':
                    case 'InTesting':
                        return rand(0,1);
                    case 'ForBuild':
                        return rand(2,4);
                    case 'InBuild':
                        return rand(2,4);
                    case 'BuildTesting':
                    case 'ProdTesting':
                        return rand(2,4);
                    case 'Done':
                        return rand(4,6);
                    default:
                        return null;
                }
            case 3: //12 hours
                switch ($status) {
                    case 'Autotesting':
                        return $reopenScenario ? rand(1,3) : rand(9,12);
                    case 'InProgress':
                    case 'ForReview':
                    case 'InReview':
                    case 'ForTesting':
                    case 'InTesting':
                        return rand(0,1);
                    case 'ForBuild':
                        return rand(1,3);
                    case 'InBuild':
                        return rand(2,4);
                    case 'BuildTesting':
                    case 'ProdTesting':
                        return rand(2,4);
                    case 'Done':
                        return rand(4,6);
                    default:
                        return null;
                }
            case 2: //8 hours
                switch ($status) {
                    case 'Autotesting':
                        return $reopenScenario ? rand(1,3) : rand(5,8);
                    case 'InProgress':
                    case 'ForReview':
                    case 'InReview':
                    case 'ForTesting':
                    case 'InTesting':
                        return rand(0,1);
                    case 'ForBuild':
                        return rand(1,2);
                    case 'InBuild':
                        return rand(2,4);
                    case 'BuildTesting':
                    case 'ProdTesting':
                        return rand(2,4);
                    case 'Done':
                        return rand(4,6);
                    default:
                        return null;
                }
            case 1:
            default: //4 hours
                switch ($status) {
                    case 'Autotesting':
                        return $reopenScenario ? rand(0,1) : rand(1,4);
                    case 'InProgress':
                    case 'ForReview':
                    case 'InReview':
                    case 'ForTesting':
                    case 'InTesting':
                        return rand(0,1);
                    case 'ForBuild':
                        return rand(1,2);
                    case 'InBuild':
                        return rand(2,4);
                    case 'BuildTesting':
                    case 'ProdTesting':
                        return rand(2,4);
                    case 'Done':
                        return rand(4,6);
                    default:
                        return null;
                }
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
     * @return string[]
     */
    private function getTaskStatuses(): array
    {
        //TODO: Backlog -> Todo -> Blocked -> InProgress -> Autotesting -> ForReview -> InReview
        // -> ForTesting -> InTesting -> ForBuild -> InBuild -> BuildTesting -> ProdTesting -> Done
        return [
//            'Backlog',
//            'Todo',
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
        $randomOption = $possibleOptions[$randomKey];

        if (random_int(0,8) === 0) {
            $randomOption[] = 100;
            dump('bug');
        }

        return $randomOption;
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
//            'lisa',//qa
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
}
