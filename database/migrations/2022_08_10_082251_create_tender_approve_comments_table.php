<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTenderApproveCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_approve_comments', function (Blueprint $table) {
            $table->id();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('tender_approve_comments');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
