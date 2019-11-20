<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->default('')->comment('名称');
            $table->unsignedDecimal('min_money', 10, 2)->default(0)->comment('包邮最低金额');
            $table->integer('weight')->default(0)->comment('重量k');
            $table->integer('fee')->default(0)->comment('费用');
            $table->integer('status')->default(0)->comment('状态');
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
        Schema::dropIfExists('expresses');
    }
}
