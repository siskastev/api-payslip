<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaySlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_slips', function (Blueprint $table) {
            $table->id();
            $table->decimal('basic_salary',10,0,true)->default(2000000);
            $table->decimal('allowance',10,0,true)->default(0);
            $table->decimal('late_attendance',10,0)->default(0);
            $table->date('salary_date');
            $table->timestamps();
            $table->index('salary_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_slips');
    }
}
