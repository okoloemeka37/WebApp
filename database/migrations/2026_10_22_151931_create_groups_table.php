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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->text('group_id')->nullable();
            $table->text('subject')->nullable();
            $table->text('subjectOwner')->nullable();
            $table->timestamp('subjectTime')->nullable();
            $table->text('pictureUrl')->nullable();
            $table->string('size')->nullable();
            $table->string('group_creation_time')->nullable();
            $table->text('desc')->nullable();
             $table->text('owner')->nullable();
            $table->text('descId')->nullable();
            $table->string('restrict')->nullable();
            $table->string('announce')->nullable();
            $table->string('isCommunity')->nullable();
            $table->string('isCommunityAnnounce')->nullable();
            $table->string('linkedParent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
