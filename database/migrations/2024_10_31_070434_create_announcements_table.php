<?php

use App\Models\Announcement;
use App\Models\Employee;
use App\Models\JobFamily;
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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id('announcement_id');
            $table->string('announcement_title');
            $table->longText('announcement_description');

            $table->foreignIdFor(Employee::class, 'published_by')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamp('published_at')->nullable();
            $table->timestamp('modified_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('announcement_details', function (Blueprint $table) {
            $table->id('announcement_detail_id');

            $table->foreignIdFor(Announcement::class, 'announcement_id')
                ->constrained('announcements', 'announcement_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(JobFamily::class, 'job_family_id')
                ->constrained('job_families', 'job_family_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('announcement_details');
    }
};
