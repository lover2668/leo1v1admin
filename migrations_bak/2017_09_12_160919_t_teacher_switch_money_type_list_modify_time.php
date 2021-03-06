<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherSwitchMoneyTypeListModifyTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_switch_money_type_list', function( Blueprint $table)
        {
            t_field($table->integer("put_time"),"提出时间");
            t_field($table->integer("confirm_time"),"审核时间");
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
