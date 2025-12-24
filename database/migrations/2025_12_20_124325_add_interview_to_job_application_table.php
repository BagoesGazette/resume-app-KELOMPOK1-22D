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
            $table->datetime('interview_date')->nullable()->after('ranking');
            $table->string('interview_type')->nullable()->after('interview_date'); // online, onsite, phone
            $table->string('interview_location')->nullable()->after('interview_type'); // link/alamat
            $table->text('interview_notes')->nullable()->after('interview_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['interview_date', 'interview_type', 'interview_location', 'interview_notes']);
        });
    }
};
