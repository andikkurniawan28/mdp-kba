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
        Schema::create('analisa_on_farms', function (Blueprint $table) {
            $table->id();
            $table->string('spta')->nullable()->unique();
            $table->string('nomor_antrian')->nullable()->unique();
            $table->string('register')->nullable()->unique();
            $table->string('nopol')->nullable();
            $table->string('petani')->nullable();
            $table->float('posbrix')->nullable();
            $table->timestamp('posbrix_at')->nullable();
            $table->string('gelas_ari')->nullable();
            $table->float('brix_ari')->nullable();
            $table->float('pol_ari')->nullable();
            $table->float('rendemen_ari')->nullable();
            $table->timestamp('ari_at')->nullable();
            $table->integer('nomor_core')->nullable();
            $table->string('gelas_core')->nullable();
            $table->float('brix_core')->nullable();
            $table->float('pol_core')->nullable();
            $table->float('rendemen_core')->nullable();
            $table->timestamp('core_at')->nullable();
            $table->float('meja_tebu')->nullable();
            $table->float('daduk')->nullable();
            $table->float('akar')->nullable();
            $table->float('tali_pucuk')->nullable();
            $table->float('pucuk')->nullable();
            $table->float('sogolan')->nullable();
            $table->float('tebu_muda')->nullable();
            $table->float('lelesan')->nullable();
            $table->float('terbakar')->nullable();
            $table->float('kocor_air')->nullable();
            $table->float('atpsd')->nullable();
            $table->string('mutu_tebu')->nullable();
            $table->timestamp('mbs_at')->nullable();
            $table->float('bobot_timbangan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisa_on_farms');
    }
};
