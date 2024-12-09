<?php

use App\Models\Barangay;
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
            $table->foreignIdFor(Barangay::class, 'present_barangay')
                ->constrained('barangays', 'id')
                ->cascadeOnDelete();

            $table->longText('permanent_address');
            $table->foreignIdFor(Barangay::class, 'permanent_barangay')
                ->constrained('barangays', 'id')
                ->cascadeOnDelete();

            $table->string('contact_number', 11)->index();
            $table->string('sex');
            $table->string('civil_status');
            $table->date('date_of_birth')->nullable();
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
