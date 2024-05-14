<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStampsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stamps', function (Blueprint $table) {
            $table->id();
            $table->date('stamp_date');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rest_id')->nullable()->constrained()->cascadeOnDelete();
            $table->time('work_start_at');
            $table->time('work_end_at')->nullable();
            $table->time('work_time')->nullable();
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
        Schema::dropIfExists('stamps');
    }
}