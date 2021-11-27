<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sprints', function (Blueprint $table) {
            $table->id();
            $table->string('event')->nullable(); //webhookEvent
            $table->string('sprint_key')->nullable()->nullable(); //issue.sprint
            $table->string('changed_field')->nullable()->nullable(); //changelog.items.*.field
            $table->string('changed_from')->nullable()->nullable(); //changelog.items.*.fromString
            $table->string('changed_to')->nullable()->nullable(); //changelog.items.*.toString
            $table->string('author_email')->nullable()->nullable(); //user.emailAddress
            $table->string('author_url')->nullable()->nullable(); //user.self
            $table->string('author_key')->nullable()->nullable(); //user.key
            $table->dateTime('timestamp')->nullable()->nullable(); //timestamp
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('event')->nullable(); //webhookEvent
            $table->string('sprint_key')->nullable(); //issue.sprint
            $table->dateTime('sprint_started_at')->nullable(); //issue.sprint
            $table->string('task_key')->nullable(); //issue.key
            $table->string('task_url')->nullable(); //issue.self
            $table->integer('task_dev_sp')->nullable(); //issue.fields.devStoryPoints
            $table->integer('task_qa_sp')->nullable(); //issue.fields.qaStoryPoints
            $table->string('task_type')->nullable(); //issue.fields.type
            $table->dateTime('task_created_at')->nullable(); //issue.fields.created
            $table->string('changed_field')->nullable(); //changelog.items.*.field
            $table->string('changed_from')->nullable(); //changelog.items.*.fromString
            $table->string('changed_to')->nullable(); //changelog.items.*.toString
            $table->string('author_email')->nullable(); //user.emailAddress
            $table->string('author_url')->nullable(); //user.self
            $table->string('author_key')->nullable(); //user.key
            $table->integer('timestamp')->nullable(); //timestamp
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sprints');
        Schema::dropIfExists('tasks');
    }
}
