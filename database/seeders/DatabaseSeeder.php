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
     */
    public function run()
    {
        $this->generateSprints();
        $this->generateTasks();
    }

    /**
     * @return void
     */
    private function generateTasks(): void
    {
        $tasks = [];

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

                $tasks[] = [
                    'event' => 'jira:sprint_updated',
                    'sprint_key' => "MV Release 1.{$count}",
                    'task_key' => "",
                    'task_url' => "",
                    'task_dev_sp' => "",
                    'task_qa_sp' => "",
                    'task_type' => "",
                    'task_created_at' => "",
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
            }
        }

        DB::table('tasks')->truncate();
        DB::table('tasks')->insert($tasks);
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

                $sprints[] = [
                    'event' => 'jira:sprint_updated',
                    'sprint_key' => "MV Release 1.{$count}",
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
            }
        }

        DB::table('sprints')->truncate();
        DB::table('sprints')->insert($sprints);
    }
}
