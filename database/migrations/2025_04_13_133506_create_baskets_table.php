<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketsTable extends Migration
{
    public function up()
    {
        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User ID referencing the users table
            $table->string('sku');
            $table->string('product_name');
            $table->decimal('product_price', 8, 2); // Price of the product
            $table->integer('quantity')->default(1); // Quantity of the product in the basket
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('baskets');
    }
}
