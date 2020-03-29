<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('correlation_id')->unique()->index();
            $table->string('method', 10);
            $table->string('protocol_version', 10);
            $table->string('uri');
            $table->longText('request_headers')->nullable();
            $table->longText('request_body')->nullable();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->string('reason_phrase', 64)->nullable();
            $table->longText('response_headers')->nullable();
            $table->longText('response_body')->nullable();
            $table->dateTime('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_logs');
    }
}
