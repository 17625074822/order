<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number', 32)->unique()->comment('订单号');
            $table->unsignedBigInteger('user_id')->index()->comment('下单人');
            $table->decimal('product_fee', 10, 2)->default(0)->comment('商品总额');
            $table->decimal('express_fee', 10, 2)->default(0)->comment('运费');
            $table->decimal('total_fee', 10, 2)->default(0)->comment('订单总额');
            $table->unsignedTinyInteger('status')->default(10)->comment('订单状态');
            $table->unsignedTinyInteger('delivery_status')->default(10)->comment('运输状态');
            $table->unsignedTinyInteger('payment_status')->default(10)->comment('支付状态');
            $table->string('receiver_name', 50)->default('')->comment('收货人');
            $table->integer('receiver_province')->default(0)->comment('省');
            $table->integer('receiver_city')->default(0)->comment('市');
            $table->integer('receiver_district')->default(0)->comment('区');
            $table->string('receiver_detail', 255)->default('')->comment('详细地址');
            $table->string('receiver_mobile', 50)->default('')->comment('手机');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
