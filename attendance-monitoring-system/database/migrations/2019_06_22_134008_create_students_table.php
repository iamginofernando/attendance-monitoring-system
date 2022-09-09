<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('student_id');
            $table->string('student_rfid')->nullable();
            $table->string('section');
            $table->string('contact_no');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name');
            $table->string('profile_img');
            $table->string('address');
            $table->string('email');
            $table->dateTime('birthday');
            $table->string('guardian');
            $table->string('g_full_name');
            $table->string('g_contact_no');
            $table->string('g_alt_contact_no');
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
        Schema::dropIfExists('students');
    }
}
