<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontrak_id')->constrained('kontraks')->onDelete('cascade');
            $table->decimal('progress_persen', 5, 2); // dari integer ke decimal
            $table->decimal('nilai_realisasi', 15, 2);
            $table->date('tanggal_realisasi');
            $table->text('keterangan')->nullable(); // tambah kolom keterangan
            $table->timestamps();

            // Index untuk performa
            $table->index('kontrak_id');
            $table->index('tanggal_realisasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisasis');
    }
};
