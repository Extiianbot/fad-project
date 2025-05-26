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
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_user_id')->constrained()->onDelete('cascade');
            $table->string('conference_type');
            $table->integer('number_of_participants');
            $table->date('date_of_event');
            $table->time('time_start');
            $table->time('time_end');
            $table->string('attachment')->nullable();
            $table->string('title_of_event');            
            $table->boolean('status')->default(false);
            $table->boolean('is_approved_by')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
