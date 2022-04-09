<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('nro_document')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('raw_password')->nullable();
            $table->boolean('is_active')->default(1);
            $table->dateTime('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ruc')->nullable();
            $table->string('address')->nullable();
            $table->double('hours')->default(172)->nullable();
            $table->boolean('send_overdue')->default(true);
            $table->boolean('send_credentials')->default(true);
            $table->boolean('notify_deadline')->default(true);
            $table->boolean('notify_assignment')->default(true);
            $table->string('telephone')->nullable();
            $table->string('email');
            $table->string('password');
            $table->string('raw_password')->nullable();
            $table->string('webpage')->nullable();
            $table->text('src_img')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('nro_document')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('raw_password')->nullable();
            $table->boolean('status')->default(1);
            $table->dateTime('last_login')->nullable();
            $table->boolean('can_check_all_customers')->default(0);
            $table->boolean('can_be_check_all')->default(0);
            $table->rememberToken();
            $table->text('src_img')->nullable();
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('admins');
        Schema::dropIfExists('users');
        Schema::dropIfExists('companies');    }
};
