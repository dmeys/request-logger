<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestLogsTable extends Migration
{
    public function up()
    {
        Schema::create(config('request-logger.table_name'), function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('fingerprint_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->string('method', 20);
            $table->string('url');
            $table->smallInteger('response_status_code')->unsigned();
            $table->integer('duration_ms')->unsigned();
            $table->smallInteger('memory')->unsigned();
            $table->string('ip', 20);
            $table->timestamp('date', 6)->index();
            $table->string('log_file');
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('request-logger.table_name'));
    }
}
