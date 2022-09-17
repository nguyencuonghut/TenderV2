<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateQuantityAndDeliveryTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quantity_and_delivery_times', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->enum('quantity_unit', ['tấn', 'kg', 'chiếc']);
            $table->text('delivery_time')->nullable();
            $table->bigInteger('material_id')->unsigned();
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->bigInteger('tender_id')->unsigned();
            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('quantity_and_delivery_times');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
