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
        Schema::create('mediaDetails', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
             $table->text('message_id')->nullable();
            $table->text('group_id')->nullable();
        $table->text("mimetype")->nullable();
       $table->text("fileLength")->nullable();
        $table->text("height")->nullable();
        $table->text("width")->nullable();
        $table->longText('base64')->nullable();
        $table->string('type')->nullable();
        $table->longText('thumbnailBase64')->nullable();
         $table->longText('seconds')->nullable();
         $table->longText('waveform')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mediaDetails');
    }
};
