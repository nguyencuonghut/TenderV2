<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tender_id')->unsigned();
            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('quantity_unit', ['tấn', 'kg', 'chiếc']);
            $table->float('price');
            $table->enum('price_unit', ['đồng/kg', 'USD/tấn', 'USD/kg', 'đồng/chiếc']);
            $table->text('note')->nullable();
            $table->string('pack')->nullable();
            $table->text('delivery_time')->nullable();
            $table->text('delivery_place')->nullable();
            $table->text('payment_condition')->nullable();
            $table->integer('tender_quantity')->default(0);
            $table->enum('tender_quantity_unit', ['tấn', 'kg', 'chiếc'])->default('tấn');
            $table->boolean('is_selected')->default(false);
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
        Schema::dropIfExists('bids');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
