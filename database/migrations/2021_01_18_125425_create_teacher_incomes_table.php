<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('date',20);
            $table->string('month');
            $table->string('amount');
            $table->string('grade')->nullable();
            $table->string('total1')->nullable();
            $table->string('total2')->nullable();
            $table->string('mahangi_vatta')->nullable();
            $table->string('durgam_vatta')->nullable();
            $table->string('citizen_investment_deduction')->nullable();
            $table->string('loan_deduction')->nullable();
            $table->string('cloth_amount')->nullable();
            $table->string('insurance')->nullable();
            $table->string('total_insurance')->nullable();
            $table->decimal('permanent_allowance', 10)->nullable();
            $table->decimal('pradyanadhyapak_bhattā', 10)->nullable();
            $table->decimal('chadparva_kharcha', 10)->nullable();
            $table->decimal('pension', 10)->nullable();
            $table->decimal('pension_added', 10)->nullable();
            $table->decimal('pro_f1', 10)->nullable();
            $table->decimal('pro_f2', 10)->nullable();
            $table->decimal('total_pf', 10)->nullable();
            $table->decimal('soc_sec_tax', 10)->nullable();
            $table->decimal('rcv_amount', 10)->nullable();
            $table->decimal('sal_tax', 10)->nullable();
            $table->decimal('net_salary', 10)->nullable();
            $table->decimal('gross_salary', 10)->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('teacher_incomes');
    }
}
