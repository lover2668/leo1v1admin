<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderInfoInvoicePublishFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //       
        Schema::table('db_weiyi.t_order_info', function( Blueprint $table)
        {
            t_field($table->integer("order_stamp_flag"),"是否已盖章" );
        });

        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('db_weiyi.t_order_info', function( Blueprint $table)
        {
            $table->dropColumn( "invoice_publish_flag");
        });

        //
    }
}
