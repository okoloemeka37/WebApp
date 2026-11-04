<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agentTomessage', function (Blueprint $table) {
            $table->id();
            $table->text('message_id');
            $table->integer('agent_id');
            $table->integer('replyInterval')->nullable();
            $table->text('group_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agentTomessage');
    }
};
