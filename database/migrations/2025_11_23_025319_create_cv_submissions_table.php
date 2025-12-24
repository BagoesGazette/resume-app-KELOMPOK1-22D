<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cv_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // File information
            $table->string('cv_file_url');
            $table->string('cv_file_name');
            $table->string('cv_file_type'); // pdf, image, docx
            $table->integer('cv_file_size');
            
            // OCR and Processing Status
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('ocr_text')->nullable();
            $table->text('processing_error')->nullable();
            
            // AI Extracted Data
            $table->string('pendidikan_terakhir')->nullable();
            $table->text('rangkuman_pendidikan')->nullable();
            $table->string('ipk_nilai_akhir')->nullable();
            $table->string('pengalaman_kerja_terakhir')->nullable();
            $table->text('rangkuman_pengalaman_kerja')->nullable();
            $table->text('rangkuman_sertifikasi_prestasi')->nullable();
            $table->text('rangkuman_profil')->nullable();
            $table->json('hardskills')->nullable(); // Array of skills
            $table->json('softskills')->nullable(); // Array of skills
            
            // Validation status
            $table->boolean('is_validated')->default(false);
            $table->timestamp('validated_at')->nullable();

            $table->integer('total_pengalaman')->nullable();
            $table->integer('tipe_pendidikan')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cv_submissions');
    }
};