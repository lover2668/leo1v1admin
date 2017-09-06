<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTLocationSubjectGradeTextbookInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_student_subject_list', function( Blueprint $table)
        {
            t_field($table->integer("userid"),"学生");
            t_field($table->integer("subject"),"科目");
            t_field($table->integer("editionid"),"教材版本");
            $table->primary(["userid","subject"]);


        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
