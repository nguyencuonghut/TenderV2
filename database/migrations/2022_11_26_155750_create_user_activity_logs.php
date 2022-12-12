<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivityLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('tender_id')->unsigned();
            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
            $table->bigInteger('quantity_id')->unsigned();
            $table->foreign('quantity_id')->references('id')->on('quantity_and_delivery_times')->onDelete('cascade');
            $table->enum('activity_type', ['Thêm', 'Sửa', 'Xóa']);
            $table->float('old_price')->default(0);
            $table->enum('old_price_unit', ['đồng/kg', 'USD/tấn', 'USD/kg', 'đồng/chiếc'])->default('đồng/kg');
            $table->float('new_price')->default(0);
            $table->enum('new_price_unit', ['đồng/kg', 'USD/tấn', 'USD/kg', 'đồng/chiếc'])->default('đồng/kg');
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
        Schema::dropIfExists('user_activity_logs');
    }
}
