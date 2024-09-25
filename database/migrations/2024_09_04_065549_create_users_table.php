<?php

use App\Models\UserStatus;
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
        Schema::create('user_statuses', function (Blueprint $table) {
            $table->id('user_status_id');
            $table->string('user_status_name', 100);
            $table->longText('user_status_desc')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->morphs('account');
            $table->string('email', 320)->unique();
            $table->string('password');
            $table->string('google_id')->nullable();

            $table->foreignIdFor(UserStatus::class, 'user_status_id')
                ->constrained('user_statuses', 'user_status_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 320)->primary();
            $table->string('token', 191);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_statuses');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
