<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('middle_name',30)->nullable();
            $table->string('last_name',30)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_no')->nullable();
            $table->integer('user_type')->default(0); // 1 for admin, 2 for student, 3 for teacher, 4 for main, 5 for librarian, 6 for accountant
            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')->references('id')->on('settings');
            $table->rememberToken();
            $table->integer('batch_id')->nullable(); //nullable for first time and then this is compulsory
            $table->integer('is_active')->default(0); //at first always inactive
            $table->string('reset_time')->nullable(); // reset date/time
            $table->integer('created_by')->nullable(); // reset date/time
            $table->string('created_at_np',20);
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
        Schema::dropIfExists('users');
    }
}
