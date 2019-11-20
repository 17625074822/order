<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->default(0)->comment('会员ID')->index();
            $table->string('name', 50)->default('')->comment('收货人');
            $table->integer('province')->default(0)->comment('省');
            $table->integer('city')->default(0)->comment('市');
            $table->integer('district')->default(0)->comment('区');
            $table->string('detail', 255)->default('')->comment('详细地址');
            $table->string('mobile', 50)->default('')->comment('手机');
            $table->unsignedTinyInteger('status')->default(10)->comment('状态');
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
        Schema::dropIfExists('addresses');
    }
}
