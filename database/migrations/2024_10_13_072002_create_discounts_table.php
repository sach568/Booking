<?php

// database/migrations/2024_xx_xx_create_discounts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'amount' or 'percentage'
            $table->decimal('value', 8, 2);
            $table->integer('max_uses')->nullable(); // Max uses of discount
            $table->decimal('max_amount', 8, 2)->nullable(); // Max discount amount
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}

