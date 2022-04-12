<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_planned')->default(true);
            $table->string('previous_id')->nullable();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->longText('description2')->nullable();

            $table->string('time_estimate')->default('00:00');
            $table->string('time_real')->default('00:00')->nullable();
            $table->string('total_time_real')->default('00:00')->nullable();

            $table->date('start_date');
            $table->date('due_date');
            $table->date('deadline')->nullable();

            $table->dateTime('created_date')->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->dateTime('completed_date')->nullable();
            $table->boolean('is_approved_change_date')->default(false);
            $table->date('approved_change_date')->nullable();
            $table->date('completed_date_manual')->nullable();
            $table->boolean('different_completed_date')->default(false);

            $table->boolean('is_priority')->default(0);
            $table->boolean('is_partial')->default(0);
            $table->boolean('is_assign')->default(0);
            $table->boolean('is_reassign')->default(0);
            $table->boolean('notified')->default(0);
            $table->boolean('with_subactivities')->default(0);

            $table->boolean('is_active')->default(1);
            $table->string('status')->default('planned');

            $table->unsignedInteger('created_by_id');
            $table->unsignedInteger('updated_by_id')->nullable();
            $table->unsignedInteger('approved_by_id')->nullable();
            $table->unsignedInteger('approved_change_date_by')->nullable();

            $table->unsignedInteger('tag_id')->nullable();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('user_id');
            $table->integer('company_id')->default(1);
            //$table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
