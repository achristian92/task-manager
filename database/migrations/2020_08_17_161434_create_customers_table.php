<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ruc')->nullable();
            $table->string('address')->nullable();
            $table->double('hours')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_telephone')->nullable();
            $table->string('contact_email')->nullable();
            $table->text('src_img')->nullable();
            $table->boolean('is_active')->default(1);
            $table->softDeletes('deleted_at', 0);
            $table->unsignedInteger('user_id')->nullable();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('customers');
    }
}
