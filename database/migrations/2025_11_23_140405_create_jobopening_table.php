<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jobopening', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('perusahaan');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('tipe')->nullable(); // full-time, part-time, contract
            $table->date('tanggal_tutup')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->string('category')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lowongans');
    }
};