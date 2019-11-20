<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->default(0)->comment('订单ID')->index();
            $table->unsignedBigInteger('product_id')->default(0)->comment('订单ID')->index();
            $table->string('product_full_name', 255)->comment('商品完整名称');
            $table->unsignedBigInteger('sku_id')->default(0)->comment('SKU_ID')->index();
            $table->integer('quantity')->default(0)->comment('数量');
            $table->unsignedDecimal('price', 10, 2)->default(0)->comment('单价');
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
