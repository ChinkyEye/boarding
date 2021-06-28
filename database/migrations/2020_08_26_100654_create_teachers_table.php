<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('government_id')->nullable();
            $table->string('insurance_id')->nullable();
            $table->string('pan_id')->nullable();
            $table->string('cinvestment_id');
            $table->string('pfund_id')->nullable();
            $table->string('slug');
            $table->string('designation');
            $table->string('teacher_code');
            $table->string('uppertype');
            $table->string('t_designation');
            $table->string('training');
            $table->string('qualification');
            $table->string('dob');
            $table->string('phone');
            $table->string('address');
            $table->string('gender');
            $table->string('religion');
            $table->string('marital_status');
            $table->string('j_date');
            $table->string('p_date')->nullable();
            $table->string('image');
            $table->integer('sort_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->unsignedBigInteger('nationality_id');
            $table->foreign('nationality_id')->references('id')->on('nationalities');
            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')->references('id')->on('settings');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('teachers');
    }
}
