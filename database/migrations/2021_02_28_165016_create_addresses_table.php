<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
           $table->increments('id');
           $table->integer('user_id');
           $table->string('zip', 7);
           $table->string('state', 10);
           $table->string('city', 50);
           $table->string('street', 50);
           $table->string('tel', 11);
           $table->string('address_sum');
           $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
           $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
           $table->softDeletes();
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
