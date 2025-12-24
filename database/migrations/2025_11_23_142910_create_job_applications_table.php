<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jobopening_id');
            $table->foreign('jobopening_id')->references('id')->on('jobopening')->onDelete('cascade');
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cv_submission_id')->nullable()->constrained('cv_submissions')->onDelete('set null');
            
            // Status lamaran
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'interview', 'accepted', 'rejected'])->default('draft');
            
            // Additional data
            $table->text('cover_letter')->nullable();
            $table->string('expected_salary')->nullable();
            
            // Review by HR
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['jobopening_id', 'user_id']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
};