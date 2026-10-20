<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kontraks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained('proyeks')->onDelete('cascade');
            $table->string('bagian'); // perencana, pelaksana, pengawas, pengelola teknis
            $table->string('mitra');
            $table->decimal('pagu_biaya', 15, 2)->nullable();
            $table->integer('masa_pemeliharaan')->nullable();
            $table->string('nomor_spk')->unique();
            $table->date('tanggal')->nullable();
            $table->integer('jangka_waktu')->nullable(); // hari
            $table->decimal('nilai_kontrak', 15, 2);

            // Tambahan untuk file SPK
            $table->string('file_spk')->nullable();
            $table->string('original_filename')->nullable();
            $table->string('file_size')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontraks');
    }
};
