<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('news_articles', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('cover_image');
        });
    }

    public function down()
    {
        Schema::table('news_articles', function (Blueprint $table) {
            $table->dropColumn('video_url');
        });
    }
};
