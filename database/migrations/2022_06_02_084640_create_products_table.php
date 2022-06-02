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
            // "->id()" creates a 'bigint' column; an 'int' column is enough for us.
            $table->integer('id')->autoIncrement()->unsigned();
            $table->integer('id_category')->unsigned();
            $table->string('name');
            $table->string('sku');
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('quantity')->unsigned()->default(0);
            $table->integer('created_by')->unsigned()->comment('The ID of the user that created this product.');
            $table->integer('updated_by')->unsigned()->nullable()->comment('The ID of the user that updated this product.');
            $table->timestamp('created_at')->default(\DB::raw('NOW()'));
            $table->timestamp('updated_at')->nullable();

            // foreign keys
            $table->foreign('id_category')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
