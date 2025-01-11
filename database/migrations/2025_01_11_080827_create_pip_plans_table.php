<?php

use App\Models\RegularPerformance;
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
        Schema::create('pip_plans', function (Blueprint $table) {
            $table->id('pip_id');

            $table->foreignIdFor(RegularPerformance::class, 'regular_performance_id')
                ->constrained('regular_performances', 'regular_performance_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('details');
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('modified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pip_plans');
    }
};
