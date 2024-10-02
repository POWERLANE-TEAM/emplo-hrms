<?php

namespace Tests\Feature;

use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRole;

// make sure that your users table is seeded prior to running this test

class RouteMiddlewareAuthorizationTest extends TestCase
{
    // negative testing
    public function test_basic_level_cannot_access_employee_dashboard(): void
    {   
        // get the first user from users table
        $basic_user = User::findOrFail(1);

        // assign a basic level user role to the user
        $basic_user->assignRole(UserRole::BASIC);
    
        // access the employee dashboard via get request to URI
        $response = $this->actingAs($basic_user)->get('/fake-uri1');

        // return a 403 forbidden status code
        $response->assertForbidden();
    }

    // positive testing
    public function test_intermediate_level_can_view_employee_information(): void
    {
        // get the second user from users table
        $intermediate_user = User::findOrFail(2);

        // assign the intermediate level user role to the user
        $intermediate_user->assignRole(UserRole::INTERMEDIATE);

        // access a fake uri with a middleware permission to view employee information
        $response = $this->actingAs($intermediate_user)->get('/fake-uri2');

        // return a 200 status code
        $response->assertOk();
    }

    // positive testing
    public function test_advanced_level_can_bypass_all_permission_checks(): void
    {
        // get the third user from users table
        $advanced_user = User::findOrFail(3);

        // assign the advanced level user role to the user
        $advanced_user->assignRole(UserRole::ADVANCED);

        // access a fake uri with a middleware permission accessible only to advanced level
        $response = $this->actingAs($advanced_user)->get('/fake-uri3');

        // return a 200 status code
        $response->assertOk();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
