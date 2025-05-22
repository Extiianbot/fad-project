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
        Schema::create('transportations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_user_id')->constrained()->onDelete('cascade');
            $table->string('destination');
            $table->integer('number_of_passengers');
            $table->string('purpose');
            $table->datetime('date_time_needed');
            $table->datetime('date_time_returned');
            $table->string('attachment')->nullable();
            $table->boolean('is_approved_by')->default(false);
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportations');
    }
};
