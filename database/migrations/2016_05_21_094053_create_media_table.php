<?php

use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('media', function ($table) {

            $table->increments('id');
            $table->string("type", 50)->nullable()->index();
            $table->string("path")->nullable()->index();
            $table->string("title")->nullable()->index();
            $table->text("description")->nullable();
            $table->string("provider", 50)->nullable()->index();
            $table->string("provider_id")->nullable()->index();
            $table->string("provider_image")->nullable()->index();
            $table->integer("user_id")->default(0)->index();
            $table->integer("length")->nullable()->index();
            $table->string("hash")->nullable()->index();
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('updated_at')->nullable()->index();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media');
    }
}
