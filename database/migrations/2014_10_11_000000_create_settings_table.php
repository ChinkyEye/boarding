<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_type')->default(0);// 1 for admin, 2 for student, 3 for teacher, 4 for main, 5 for librarian, 6 for accountant
            $table->string('school_name');
            $table->string('school_code')->unique();
            $table->string('type_of_school');
            $table->string('level');
            $table->string('running_class');
            $table->string('slug')->unique();
            $table->string('address')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('email')->unique();
            $table->string('principal_name')->nullable();
            $table->text('url')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->string('created_at_np',20);
            // logo
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
        Schema::dropIfExists('settings');
    }
}
