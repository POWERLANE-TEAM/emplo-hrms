<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Document;
use App\Models\EmploymentStatus;
use App\Models\PositionVacancy;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserStatus;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user_roles = UserRole::factory()->predefinedUserRoles();

        foreach ($user_roles as $user_role) {
            UserRole::create($user_role);
        }

        $user_statuses = UserStatus::factory()->predefinedUserStatuses();

        foreach ($user_statuses as $user_status) {
            UserStatus::create($user_status);
        }

        // User::factory()->create([
        //     'email' => 'test@example.com',
        //     'password' => Hash::make('P@ssw0rd'),
        // ]);

        Branch::factory(10)->create();
        Department::factory(10)->create();
        EmploymentStatus::factory(10)->create();
        $this->call(PositionSeeder::class);

        User::factory(10)->create();

        PositionVacancy::factory(25)->create();

        $documents = Document::factory()->predefinedDocuments();

        foreach ($documents as $document) {
            Document::create($document);
        }
    }
}
