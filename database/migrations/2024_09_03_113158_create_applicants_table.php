<?php

use App\Models\Barangay;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
