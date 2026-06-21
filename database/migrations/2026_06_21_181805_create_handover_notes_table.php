<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('handover_notes', function (Blueprint $table) {
            $table->id();
            $table->date('note_date');
            $table->text('note');
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Only one note per day
            $table->unique('note_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('handover_notes');
    }
};
