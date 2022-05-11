<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->bigInteger('material_id')->unsigned();
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->text('packing')->nullable();
            $table->text('origin')->nullable();
            $table->text('delivery_condition');
            $table->text('payment_condition');
            $table->text('certificate')->nullable();
            $table->text('other_term')->nullable();
            $table->dateTime('tender_start_time');
            $table->dateTime('tender_end_time');
            $table->bigInteger('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on('admins')->onDelete('cascade');
            $table->enum('status', ['Open', 'Closed', 'In-progress']);
            $table->text('supplier_ids');
            $table->bigInteger('approver_id')->unsigned()->nullable();
            $table->foreign('approver_id')->references('id')->on('admins')->onDelete('cascade');
            $table->dateTime('tender_in_progress_time')->nullable();
            $table->dateTime('tender_closed_time')->nullable();
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
        Schema::dropIfExists('tenders');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
