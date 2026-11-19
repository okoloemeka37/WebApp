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
        Schema::create('reaction', function (Blueprint $table) {
            $table->id(); // WhatsApp message unique ID
            $table->text('reaction_id');
            $table->string('group_id'); // Foreign key reference to group
            $table->string('participant_contact_id')->nullable();   
            $table->boolean('is_from_me')->default(false);
            $table->text('body')->nullable();
            $table->text('reactionTo')->nullable();
             $table->timestamp('message_timestamp');
            $table->string('message_type');
             $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reaction');
    }
};
