<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('google_id');
            $table->enum('role', array('GUEST', 'USER', 'MANAGER', 'SYSADMIN'));
            $table->integer('applicant_id')->unsigned()->nullable();
            $table->integer('employee_id')->unsigned()->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->increments('session_id')->primary();
            $table->foreignIdFor(User::class)->nullable()->index();
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->integer('payload');
            $table->dateTime('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
