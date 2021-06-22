<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('subject_code');
            $table->integer('credit_hour')->nullable();
            $table->integer('theory_practical')->default(1);//1 xa vane theory 2 xa vane practical 3 xa vane both
            $table->integer('compulsory_optional')->default(1);//1 compulsory 2 xa vane optional
            $table->integer('sort_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('s_classes');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('updated_by')->nullable();
            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')->references('id')->on('settings');
            $table->unsignedBigInteger('batch_id');
            $table->foreign('batch_id')->references('id')->on('batches');
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
        Schema::dropIfExists('subjects');
    }
}
