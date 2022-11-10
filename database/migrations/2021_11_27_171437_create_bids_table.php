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
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->bigInteger('supplier_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('quantity_id')->unsigned();
            $table->foreign('quantity_id')->references('id')->on('quantity_and_delivery_times')->onDelete('cascade');
            $table->float('price');
            $table->enum('price_unit', ['đồng/kg', 'USD/tấn', 'USD/kg', 'đồng/chiếc']);
            $table->text('note')->nullable();
            $table->text('seller')->nullable();
            $table->string('pack')->nullable();
            $table->text('delivery_time')->nullable();
            $table->integer('tender_quantity')->default(0);
            $table->enum('tender_quantity_unit', ['tấn', 'kg', 'chiếc', '%'])->default('tấn');
            $table->integer('bid_quantity')->default(0);
            $table->enum('bid_quantity_unit', ['tấn', 'kg', 'chiếc'])->default('tấn');
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
