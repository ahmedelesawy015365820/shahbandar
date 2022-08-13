<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type');
            $table->unsignedBigInteger('value')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('use_times')->nullable();
            $table->unsignedBigInteger('used_times')->default(0);
            $table->date('start_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->unsignedDecimal('greater_than')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('discounts');
    }
}
