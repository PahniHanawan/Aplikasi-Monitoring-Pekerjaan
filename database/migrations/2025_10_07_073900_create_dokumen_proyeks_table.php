<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dokumen_proyeks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained()->onDelete('cascade');
            $table->foreignId('kontrak_id')->constrained()->onDelete('cascade');
            $table->enum('jenis_dokumen', ['bast_i', 'bast_ii', 'kwitansi', 'spp']);
            $table->string('nama_file');
            $table->string('original_filename');
            $table->integer('file_size');
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_upload');
            $table->timestamps();

            // Index untuk performa query
            $table->index(['proyek_id', 'kontrak_id', 'jenis_dokumen']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('dokumen_proyeks');
    }
};
