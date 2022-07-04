<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsAndAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_and_announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url');
            $table->text('content');
            $table->string('image_name');
            $table->string('image_path');
            $table->string('type');
            $table->integer('admin_user_id');
            $table->integer('number_of_views');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('news_and_announcements');
    }
}
