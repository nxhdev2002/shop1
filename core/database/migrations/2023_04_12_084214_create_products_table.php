<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description', 200);
            $table->longText('content')->nullable();
            $table->foreignId('seller_id')->constrained('users');
            $table->foreignId('category_id')->constrained('categories');
            $table->timestamp('guarantee');
            $table->string('picture_url');
            $table->decimal('price', 18, 8);
            $table->float('rank_point')->default(0);
            $table->tinyInteger('is_ads')->default(0);
            $table->tinyInteger('is_removed')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->integer('amount', false, false);

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
        Schema::dropIfExists('products');
    }
}
