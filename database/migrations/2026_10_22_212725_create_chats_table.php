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
        Schema::create('chats', function (Blueprint $table) {
            $table->id(); // WhatsApp message unique ID
            $table->string('message_id');
            $table->string('group_id'); // Foreign key reference to group
            $table->string('participant_contact_id')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('message_type')->default('conversation');
            $table->text('body')->nullable();
            $table->boolean('is_from_me')->default(false);
            $table->string('quoted_message_id')->nullable();
            $table->string('source')->nullable(); 
            $table->string('status')->nullable();
            $table->timestamp('message_timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
