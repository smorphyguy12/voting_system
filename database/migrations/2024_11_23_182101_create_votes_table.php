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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('election_id');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users');
            $table->foreign('candidate_id')->references('id')->on('candidates');
            $table->foreign('election_id')->references('id')->on('elections');

            // Ensure one vote per student per election
            $table->unique(['student_id', 'election_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
