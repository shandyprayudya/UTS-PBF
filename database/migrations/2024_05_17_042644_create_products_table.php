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
            $table->string('name', 255)->nullable(false);
            $table->text('description');
            $table->integer('price')->nullable(false);
            $table->string('image', 255);
            $table->unsignedBigInteger('category_id')->nullable(false);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('restrict');
            $table->date('expired_at')->nullable(false);
            $table->string('modified_by', 255)->nullable(false)->comment('email user');
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
