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
            $table->bigIncrements('id');
            $table->string('name', 255)->default('')->comment('名称');
            $table->unsignedBigInteger('express_id')->default(0)->comment('运费模板ID')->index();
            $table->tinyInteger('status')->default(10)->comment('状态');
            $table->integer('quantity')->default(0)->comment('库存');
            $table->integer('sale_num')->default(0)->comment('售出数量');
            $table->timestamps();
            $table->foreign('express_id')->references('id')->on('expresses')->onDelete('cascade');
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
