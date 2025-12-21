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
        Schema::table('job_applications', function (Blueprint $table) {
              // Acceptance fields
            $table->timestamp('accepted_at')->nullable()->after('interview_notes');
            $table->date('start_date')->nullable()->after('accepted_at');
            $table->bigInteger('offered_salary')->nullable()->after('start_date');
            $table->text('acceptance_notes')->nullable()->after('offered_salary');
            
            // Rejection fields
            $table->timestamp('rejected_at')->nullable()->after('acceptance_notes');
            $table->string('rejection_reason', 255)->nullable()->after('rejected_at');
            $table->text('rejection_notes')->nullable()->after('rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
             // Drop acceptance fields
            $table->dropColumn([
                'accepted_at',
                'start_date',
                'offered_salary',
                'acceptance_notes',
            ]);
            
            // Drop rejection fields
            $table->dropColumn([
                'rejected_at',
                'rejection_reason',
                'rejection_notes',
            ]);
        });
    }
};
