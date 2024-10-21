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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id('applicant_id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->longText('present_address');
            $table->string('present_barangay', 10);
            $table->foreign('present_barangay')
                ->references('barangay_code')->on('barangays')
                ->onDelete('cascade');

            $table->longText('permanent_address');
            $table->string('permanent_barangay', 10);
            $table->foreign('permanent_barangay')
                ->references('barangay_code')->on('barangays')
                ->onDelete('cascade');

            $table->string('contact_number', 11)->index();
            $table->enum('sex', ['MALE', 'FEMALE']);
            $table->enum('civil_status', ['SINGLE', 'MARRIED', 'WIDOWED', 'LEGALLY SEPARATED']);
            $table->date('date_of_birth')->nullable();
            $table->jsonb('education')->nullable();
            $table->jsonb('experience')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
