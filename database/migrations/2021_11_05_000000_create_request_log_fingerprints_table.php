<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestLogFingerprintsTable extends Migration
{
    public function up()
    {
        Schema::create(config('request-logger.table_name') . '_fingerprints', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('fingerprint', 50)->index();
            $table->integer('repeating')->default(0);
            $table->timestamps(6);
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('request-logger.table_name') . '_fingerprints');
    }
}
