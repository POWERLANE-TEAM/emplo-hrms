<?php

use App\Models\Application;
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
        Schema::table('employees', function (Blueprint $table) {
            // will be used to retrieved the employee's hired date
            $table->foreignIdFor(Application::class, 'application_id')
                ->nullable()
                ->constrained('applications', 'application_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeignIdFor(Application::class, 'application_id');
        });
    }
};
