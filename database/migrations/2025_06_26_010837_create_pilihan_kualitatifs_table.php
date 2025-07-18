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
        Schema::create('pilihan_kualitatifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parameter_id')->constrained()->onDelete('cascade');
            $table->foreignId('jenis_pilihan_kualitatif_id')->constrained()->onDelete('cascade');
            // $table->float('nilai')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilihan_kualitatifs');
    }
};
