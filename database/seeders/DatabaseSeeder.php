<?php

namespace Database\Seeders;

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
        $sprints = [];

        $initialDate = date('Y-m-d H:i:s');
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
                    $this->generateTasks($sprintKey, $initialDate);
                }
            }
        }

        DB::table('sprints')->truncate();
        DB::table('sprints')->insert($sprints);
    }

    /**
     * @param string $sprintKey
     * @param string $sprintStartDate
     * @return void
     * @throws \Exception
     */
    private function generateTasks(string $sprintKey, string $sprintStartDate): void
    {
        $tasks = [];

        $taskKeyCount = 1;
        foreach ($this->getUsers() as $userName) {
            foreach ($this->getPossibleStoryPointsArrangement() as $storyPointsForTask) {
                $tasks[] = [
                    'event' => 'jira:issue_updated',
                    'sprint_key' => $sprintKey,
                    'task_key' => "MV-{$taskKeyCount}",
                    'task_url' => "https://jira.atlassian.com/rest/api/2/issue/{$sprintKey}",
                    'task_dev_sp' => $storyPointsForTask,
                    'task_qa_sp' => $this->getQaSpForTaskSp($storyPointsForTask),
                    'task_type' => "issue",
                    'task_created_at' => "",
                    'changed_field' => 'issuestatus',
                    'changed_from' => $changeFrom,
                    'changed_to' => $changeTO,
                    'author_email' => "{$userName}@gmail.com",
                    'author_url' => "https://jira.atlassian.com/rest/api/2/user?username={$userName}",
                    'author_key' => $userName,
                    'timestamp' => $initialDate,
                    'created_at' => $initialDate,
                    'updated_at' => $initialDate,
                ];
                $taskKeyCount++;
            }
        }

        DB::table('tasks')->truncate();
        DB::table('tasks')->insert($tasks);
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

        foreach ($possibleOptions as $option) {
            dump(array_sum($option));
        }

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
}
