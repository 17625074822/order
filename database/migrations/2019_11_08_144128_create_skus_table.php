<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->comment('商品id')->index();
            $table->string('version', 100)->comment('版本');
            $table->integer('quantity')->default(0)->comment('数量');
            $table->unsignedDecimal('price', 10, 2)->default(0)->comment('单价');
            $table->integer('weight')->default(0)->comment('重量(克)');
            $table->integer('sale_num')->default(0)->comment('售出数量');
            $table->unsignedInteger('status')->default(10)->comment('状态');
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skus');
    }
}
