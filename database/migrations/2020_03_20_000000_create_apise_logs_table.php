<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiseLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apise_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('correlation_id')->unique()->index();
            $table->string('method', 10);
            $table->string('protocol_version', 10);
            $table->string('host');
            $table->string('uri');
            $table->longText('query_params')->nullable();
            $table->longText('request_headers')->nullable();
            $table->longText('request_body')->nullable();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->string('reason_phrase', 64)->nullable();
            $table->longText('response_headers')->nullable();
            $table->longText('response_body')->nullable();
            $table->string('tag', 32)->nullable();
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
        Schema::dropIfExists('apise_logs');
    }
}
